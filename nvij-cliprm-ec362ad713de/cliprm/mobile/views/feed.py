from random import randint
from itertools import groupby
from django.contrib.auth.decorators import login_required
from django.http import Http404, HttpResponse
from django.shortcuts import render_to_response
from mobile.models import Clipstable, Productdetail, CliprUser, Fbfriend, Activitytable
from django.db.models import Count
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.template import RequestContext

FEED_PAGE_SIZE=200

@login_required
def m_my_feed(request):
	return my_feed(request, 'm_feed.html')

@login_required
def d_my_feed(request):
	return my_feed(request, 'd_feed.html')

def my_feed(request, template):
	context_instance = RequestContext(request)
	uid = request.user.userid

	friends = Fbfriend.objects.filter(userid__userid=uid).exclude(friendid__userid=uid).values_list('friendid',flat=True)
	res = Activitytable.objects.select_related().filter(userid__userid__in=friends).order_by('-time')

	paginator = Paginator(res, FEED_PAGE_SIZE)
	page=request.GET.get("page",1)
	try:
		page=int(page)
		if page > paginator.num_pages:
			raise Http404
		elif page < 1:
			stories = paginator.page(1)
		else:
			stories = paginator.page(page)
	except PageNotAnInteger:
		# If page is not an integer, deliver first page.
		stories = paginator.page(1)
	collections = group_stories(stories)
	return render_to_response(template, {'story_collections': collections,'page':page, 'page_type':'feed','page_id':'feed'}, context_instance)

def group_stories(stories):
	grouped_stories=[]
	for date, story_group in groupby(stories, lambda x: x.time.date()):
		for user_and_action, story_sub_group in groupby(sorted(story_group,key=lambda x: (x.userid, x.action)), lambda x: (x.userid, x.action)):
			story_collection = list(story_sub_group)
			# remove duplicate entries
			action = user_and_action[1].split()[0]
			if action == "followed" or action == "un-followed":
				story_collection_dict = {x.objuserid.userid: x for x in story_collection}
				story_collection = list(story_collection_dict.values())
			else:
				story_collection_dict = {x.objproduct.productid: x for x in story_collection}
				story_collection = list(story_collection_dict.values())
			num_stories = len(story_collection)
			group_key = {'date':date,'user':user_and_action[0],'action':user_and_action[1].split()[0],'count':num_stories}
			# large => id of randomly chosen odd-indexed story whose image will be amplified
			grouped_stories.append({'stories': story_collection, 'keys':group_key,'odd':num_stories%2==1 or num_stories > 9,'large':randint(1,(min(num_stories,9)+1)/2)*2-1})
	return grouped_stories
