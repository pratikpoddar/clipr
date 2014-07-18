import logging
import sys
from django.utils.safestring import mark_safe
from django.shortcuts import get_object_or_404
from django.shortcuts import render_to_response
from mobile.models import Clipstable, Productdetail, Productview, Deletedproducts, Producttopsimilar, Fbfriend, CliprUser
from django.db.models import Count
from django.http import Http404
import hashlib
from django.template import RequestContext
from itertools import chain
from django.views.decorators.csrf import ensure_csrf_cookie # to prevent cross-site request forgery


NUM_CLIPPERS_REQUIRED = 7
def m_product(request, pid):
	return product_detail(request, pid, 'm_product.html')

def d_product(request, pid):
	return product_detail(request, pid, 'd_product.html')

@ensure_csrf_cookie
def product_detail(request, pid, template):
	context_instance = RequestContext(request)
	is_ajax = request.GET.get('ajax',False)
	if not pid.isdigit():
		raise Http404
	pid = int(pid)
	p = get_object_or_404(Productdetail,productid=pid)
	clipped = request.user.is_authenticated() and Clipstable.objects.filter(product__productid=pid).filter(user=request.user.userid).exists()
	num_clips = Clipstable.objects.filter(product__productid=pid).values('user').distinct().count()
	is_removed_or_out_of_stock = Deletedproducts.objects.filter(product__productid=pid).exists() or p.availability.lower() == "out of stock"
	num_views = Productview.objects.filter(product__productid=pid).values('userid').distinct().count()

	# products calculated as similar products
	similar_products = map( lambda x: x.similar, list(Producttopsimilar.objects.filter(productid__productid=pid).exclude(similar__productid=pid)[:6]))

	# products with maximum common clippers
	product_clippers = Clipstable.objects.filter(product__productid=pid).values_list('user',flat=True)
	common_clipped = Clipstable.objects.filter(user__userid__in=product_clippers).exclude(product__productid=pid).values('user','product').distinct().values('product').annotate(np=Count('user')).order_by('-np')[:6]
	common_clippers_products = Productdetail.objects.filter(productid__in=map(lambda x: x['product'],common_clipped))

	if request.user.is_authenticated():
		clippers = get_custom_clippers(request.user.userid, pid)
	else:
		clippers = get_clippers_from_dict(Clipstable.objects.filter(product__productid=pid).values('user').distinct()[:NUM_CLIPPERS_REQUIRED])
	description = get_product_description(pid)

	group=""
	tags=[]
	if p.productgroup_set and p.productgroup_set.all():
		group = p.productgroup_set.all()[0].groupid
	if p.cliprtagtable_set:
		tags = map(lambda x: x.cliprtag, p.cliprtagtable_set.all())


	return render_to_response(template, {
		'product': p,
		'group':group,
		'tags':tags, 
		'description':mark_safe(description),
		'clipped':clipped, 
		'num_clips':num_clips,
		'num_views':num_views,
		'is_removed_or_out_of_stock':is_removed_or_out_of_stock, 
		'similar_products':similar_products, 
		'clippers':clippers,
		'common_clippers_products':common_clippers_products,
		'is_ajax':is_ajax
		}, context_instance)

def get_custom_clippers(uid, pid):
	"""get clippers to be displayed on product page based on logged in user."""
	# find all friends
	friends = Fbfriend.objects.filter(userid__userid=uid).values_list('friendid',flat=True)
	# find friends who have clipped
	friend_clippers = Clipstable.objects.filter(product__productid=pid).filter(user__userid__in=friends).values('user').distinct()[:NUM_CLIPPERS_REQUIRED]
	# find if you need more clippers
	num_more_clippers_required = NUM_CLIPPERS_REQUIRED - len(friend_clippers)
	# if more clippers required chose from non-friends to avoid duplication
	if num_more_clippers_required > 0:
		non_friend_clippers = Clipstable.objects.filter(product__productid=pid).exclude(user__userid__in=friends).values('user').distinct()[:num_more_clippers_required]
		all_clippers = list(chain(friend_clippers,non_friend_clippers))
	else:
		# else just keep previous set of clippers
		all_clippers = friend_clippers
	# get details of all clippers
	return get_clippers_from_dict(all_clippers)

def get_clippers_from_dict(users):
	ids = map( lambda x: x['user'],users)
	return CliprUser.objects.filter(userid__in=ids)

def get_product_description(pid):
	import subprocess
	from os.path import abspath, dirname
	basepath = reduce( lambda x,y: x+'/'+y,abspath(dirname(__file__)).split('/')[:-2])
	proc = subprocess.Popen("php " + basepath + "/backend/sanitize.php "+str(pid),shell=True,stdout=subprocess.PIPE)
	return proc.stdout.read()
