from django.utils import simplejson
from django.http import Http404, HttpResponse, HttpResponseRedirect
from social_auth.models import UserSocialAuth
from social_auth.backends.facebook import FacebookBackend
from facepy import GraphAPI
from django.contrib.auth import get_user_model
from models import Emailcommunication
import logging

# Get an instance of a logger
logger = logging.getLogger(__name__)

def create_custom_user(backend, details, response, uid, username, user=None, *args,
				**kwargs):
	"""Custom Create user (adds userid). Depends on get_username pipeline."""
	logger.error("create start")
	if user:
		logger.error("create early end")
		return {'user': user}
	if not username:
		return None

	# Avoid hitting field max length
	email = details.get('email')
	original_email = None
	if email and UserSocialAuth.email_max_length() < len(email):
		original_email = email
		email = ''
	logger.error("create end")
	return {
		'user': UserSocialAuth.create_user(username=username, email=email, userid=response['id']),
		'original_email': original_email,
		'is_new': True
	}

def debug(backend, details, response, user=None, is_new=False,
						*args, **kwargs):
	""" Debug """
	logger.error("debug end")
	return 

def populateuserinfo(backend, details, response, user=None, is_new=False,
						*args, **kwargs):
	""" Add additional info for user """

	logger.error("populate start")
	DATA_FIELDS = ['id','name','birthday','work','location','education','email','username','first_name','last_name','gender']

	if user is None:
		return
	if backend.__class__ != FacebookBackend:
		return
	uid = response['id']
	access_token = UserSocialAuth.objects.filter(provider='facebook').get(uid=response['id']).tokens['access_token']
	graph = GraphAPI(access_token)
	res = graph.get(str(uid)+'?fields='+reduce(lambda x,y: x+','+y,DATA_FIELDS))
	user.accesstoken = access_token
	user.first_name = res.get('first_name')
	user.last_name = res.get('last_name')
	user.birthday = res.get('birthday')
	user.fullname = res.get('name')
	user.gender = res.get('gender')
	if res.get('location'):
		user.location = res.get('location').get('name','')
	if res.get('hometown'):
		user.hometown = res.get('hometown').get('name','')
	if res.get('work'):
		user.work= getSanitizedWork(res['work'])
	if res.get('education'):
		user.education= getSanitizedWork(res['education'])
	user.save()
	if not Emailcommunication.objects.filter(user=user).exists():
		communication_entry = Emailcommunication(user=user)
		communication_entry.save()
	logger.error("populate end")
	return 

def getSanitizedEducation (educationProfile):
	""" Helper function to get education profile as a structured string """
	education = ""
	for value in educationProfile:
		if value.get('school') and value['school'].get('name'):
			education = education + value['school']['name']+'---'
		if value.get('concentration'):
			for concentration in value['concentration']:
				education = education + concentration['name']+'---'
	return education

def getSanitizedWork (workProfile):
	""" Helper function to get work profile as a structured string """
	work = ""
	for value in workProfile:
		if value.get('employer') and value['employer'].get('name'):
			work = work + value['employer']['name']+'---'
	return work
