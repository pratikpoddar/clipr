from django.contrib.auth.decorators import login_required
from django.http import Http404, HttpResponse
from django.shortcuts import render_to_response
from mobile.models import Clipstable, Productdetail, CliprUser
from django.db.models import Count
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.template import RequestContext
from django.views.decorators.csrf import ensure_csrf_cookie # to prevent cross-site request forgery
import logging

# Get an instance of a logger
logger = logging.getLogger(__name__)

PAGE_SIZE=20

def trending(request,template='d_home.html'):
	if request.user.is_authenticated():
		return trending_loggedin(request,template)
	res = Productdetail.objects.order_by('-score').values('productid')
	return render_trending(res, request, template)

@login_required
def trending_loggedin(request,template):
	res = Productdetail.objects.order_by('-score').values('productid')
	return render_trending(res, request, template)

def render_trending(res, request,template):
	context_instance = RequestContext(request)
	paginator = Paginator(res, PAGE_SIZE)
	page=request.GET.get("page",1)
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
		each_item['clippers_count'] = Clipstable.objects.filter(product__productid=each_item['productid']).values('user').distinct().count()
		each_item['clippers'] = map(lambda x: CliprUser.objects.get(userid=x['user']), Clipstable.objects.filter(product__productid=each_item['productid']).values('user').distinct().order_by('?')[:5])
		each_item['product'] = Productdetail.objects.get(productid=each_item['productid'])
		each_item['clipped'] = request.user.is_authenticated() and Clipstable.objects.filter(product=each_item['product']).filter(user=request.user).exists()
	return render_to_response(template, {'products': products,'page':page, 'page_type':'trending','page_prefix':'?','page_id':'trending'}, context_instance)


@ensure_csrf_cookie
def d_trending(request):
	return trending(request,'d_home.html')
def m_trending(request):
	return trending(request,'m_home.html')
