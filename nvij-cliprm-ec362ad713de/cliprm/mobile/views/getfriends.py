from mobile.models import Fbfriend
from django.http import Http404, HttpResponse
from django.utils import simplejson
from mobile.decorators import ajax_login_required

@ajax_login_required
def get_friends(request):
	query = request.GET.get('q')
	limit = int(request.GET.get('limit'))
	uid = request.user.userid
	friends = Fbfriend.objects.filter(userid__userid=uid).filter(friendid__fullname__icontains=query)[:limit]
	response_data={}
	friendlist = []
	for friend in friends:
		tempdict = {}
		tempdict['id'] = friend.friendid.userid
		tempdict['name'] = friend.friendid.fullname
		friendlist.append(tempdict)
	response_data['result'] = friendlist
	return HttpResponse(simplejson.dumps(response_data), content_type="application/json")
