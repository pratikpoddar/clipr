from django.utils.safestring import mark_safe
from django.http import Http404, HttpResponse
from django.shortcuts import render_to_response
from mobile.models import Clipstable, Productdetail, CliprUser, Addedproduct, Fbfriend
from django.db.models import Count
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.template import RequestContext
from django.contrib.auth.decorators import login_required
from django.views.decorators.csrf import ensure_csrf_cookie # to prevent cross-site request forgery

PRODUCT_PAGE_SIZE=20
USER_PAGE_SIZE=24

###############################################################################
# Clipboards
###############################################################################
@ensure_csrf_cookie
def m_clipboard(request, uid):
	return get_clipboard(request, uid, "m_clipboard.html")

@ensure_csrf_cookie
def d_clipboard(request, uid):
	return get_clipboard(request, uid)

@ensure_csrf_cookie
@login_required
def m_my_clipboard(request):
	return get_clipboard(request, request.user.userid, "m_clipboard.html")

@ensure_csrf_cookie
@login_required
def d_my_clipboard(request):
	return get_clipboard(request, request.user.userid)

def get_clipboard(request, uid, template="d_clipboard.html"):
	context_instance = RequestContext(request)
	
	# page number
	page=request.GET.get("page",1)

	page_id = "clips-"+str(uid)

	# board name
	tag=request.GET.get("board","")
	page_prefix="?"
	# products order by cliptags
	if not tag:
		res = Clipstable.objects.filter(user__userid=uid).order_by('cliptag').values('product').distinct()
	else:
		res = Clipstable.objects.filter(user__userid=uid).filter(cliptag=tag).values('product').distinct()
		page_prefix="?board="+tag+"&"
		page_id = page_id+"-"+tag

	# all cliptags
	cliptags = Clipstable.objects.filter(user__userid=uid).values('cliptag').distinct()
	for cliptag in cliptags:
		cliptag['name'] = cliptag['cliptag'].replace('_',' ')

	# pagination
	paginator = Paginator(res, PRODUCT_PAGE_SIZE)
	try:
		page=int(page)
		if page > paginator.num_pages:
			raise Http404
		elif page < 1:
			products = paginator.page(1)
		else:
			products = paginator.page(page)
	except PageNotAnInteger:
		# If page is not an integer, deliver first page.
		products = paginator.page(1)

	for each_item in products:
		each_item['clippers_count'] = Clipstable.objects.filter(product__productid=each_item['product']).values('user').distinct().count()
		each_item['product'] = Productdetail.objects.filter(productid=each_item['product'])[0]
	return render_to_response(template, 
		{'stats': get_stats(uid), 
		'products': products, 
		'board_owner':CliprUser.objects.get(userid=uid), 
		'page':page, 
		'boards':cliptags,
		'page_prefix':mark_safe(page_prefix),
		'page_id':page_id,
		'is_following': request.user.is_authenticated() and is_following(request.user.userid,uid)
		}, context_instance)

###############################################################################
# Added products
###############################################################################

def d_added_products(request, uid):
	return added_products(request,uid,'d_added.html')

def m_added_products(request, uid):
	return added_products(request,uid,'m_added.html')

def added_products(request, uid, template):
	context_instance = RequestContext(request)
	
	# page number
	page=request.GET.get("page",1)
	page_id = "added-"+str(uid)
	page_prefix="?"
	# products order by cliptags
	res = Addedproduct.objects.filter(user__userid=uid).values('product').distinct()

	# pagination
	paginator = Paginator(res, PRODUCT_PAGE_SIZE)
	try:
		page=int(page)
		if page > paginator.num_pages:
			raise Http404
		else:
			products = paginator.page(page)
	except PageNotAnInteger:
		# If page is not an integer, raise error.
		raise Http404

	for each_item in products:
		each_item['product'] = Productdetail.objects.filter(productid=each_item['product'])[0]
	return render_to_response(template, 
		{ 'stats': get_stats(uid), 
		'products': products, 
		'board_owner':CliprUser.objects.get(userid=uid), 
		'page':page, 
		'page_prefix':mark_safe(page_prefix),
		'page_id':page_id,
		'is_following': request.user.is_authenticated() and is_following(request.user.userid,uid)
		}, context_instance)

###############################################################################
# Followers and following
###############################################################################


@ensure_csrf_cookie
def m_followers(request, uid):
	return followers(request, uid, "m_followers.html")

@ensure_csrf_cookie
def d_followers(request, uid):
	return followers(request, uid, "d_followers.html")

@ensure_csrf_cookie
def m_following(request, uid):
	return following(request, uid, "m_following.html")

@ensure_csrf_cookie
def d_following(request, uid):
	return following(request, uid, "d_following.html")

def followers(request, uid, template):
	res = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(friendid__userid=uid).exclude(userid__userid=uid).values('userid')
	res = map (lambda x: {'user':CliprUser.objects.get(userid=x['userid'])}, res)
	return render_users(request, uid, res, "followers", template)

def following(request, uid, template):
	res = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(userid__userid=uid).exclude(friendid__userid=uid).values('friendid')
	res = map (lambda x: {'user':CliprUser.objects.get(userid=x['friendid'])}, res)
	return render_users(request, uid, res, "following", template)


def render_users(request, uid, res, role, template):
	context_instance = RequestContext(request)
	# page number
	page=request.GET.get("page",1)
	page_id = role+"-"+str(uid)
	# products order by cliptags

	# pagination
	paginator = Paginator(res, USER_PAGE_SIZE)
	try:
		page=int(page)
		if page > paginator.num_pages:
			raise Http404
		else:
			users = paginator.page(page)
	except PageNotAnInteger:
		# If page is not an integer, raise error.
		raise Http404
	for each_user in users:
		if request.user.is_authenticated():
			each_user['following'] = is_following( request.user.userid, each_user['user'].userid )
		else:
			each_user['following']=False
		each_user['clipped_list'] = map( lambda x: Productdetail.objects.get(productid=x['product']), 
			Clipstable.objects.filter(user__userid=each_user['user'].userid).order_by('cliptag').values('product').distinct()[:6])
	return render_to_response(template, { 
			'stats': get_stats(uid), 
			'users': users, 
			'board_owner':CliprUser.objects.get(userid=uid), 
			'page':page, 
			'page_id':page_id,
			'is_following': request.user.is_authenticated() and is_following(request.user.userid,uid)}, 
		context_instance)

###############################################################################
# Global stats
###############################################################################
def get_stats(uid):
	followers = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(friendid__userid=uid).exclude(userid__userid=uid).count()
	following = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(userid__userid=uid).exclude(friendid__userid=uid).count()
	clipped = Clipstable.objects.filter(user__userid=uid).values('product').distinct().count()
	added = Addedproduct.objects.filter(user__userid=uid).values('product').distinct().count()
	return {'clipped':clipped,'added':added, 'followers':followers, 'following': following}

def is_following(uid1,uid2):
	return Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(
				userid__userid=uid1).filter(friendid__userid=uid2).exists()