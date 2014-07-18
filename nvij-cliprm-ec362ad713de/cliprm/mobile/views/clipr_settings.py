from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response
from mobile.models import Emailcommunication, Intereststable, Fbfriend, Clipstable, Addedproduct
from django.template import RequestContext
from django.views.decorators.csrf import ensure_csrf_cookie # to prevent cross-site request forgery

@ensure_csrf_cookie
@login_required
def d_my_settings(request):
	return my_settings(request)

def my_settings(request, template='d_settings.html'):
	context_instance = RequestContext(request)
	preferences = Emailcommunication.objects.get(user=request.user)
	interests = Intereststable.objects.filter(user=request.user,source=request.user)
	return render_to_response( template, 
		{'stats': get_stats(request.user),
		'preferences': preferences,
		'interests':interests
		}, context_instance)

def get_stats(user):
	followers = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(friendid=user).exclude(userid=user).count()
	following = Fbfriend.objects.exclude(friendid__accesstoken="").filter(follow=1).filter(userid=user).exclude(friendid=user).count()
	clipped = Clipstable.objects.filter(user=user).values('product').distinct().count()
	added = Addedproduct.objects.filter(user=user).values('product').distinct().count()
	return {'clipped':clipped,'added':added, 'followers':followers, 'following': following}
