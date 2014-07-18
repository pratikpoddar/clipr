from mobile.decorators import ajax_login_required
from django.http import Http404, HttpResponse
from mobile.models import Fbfriend, CliprUser

@ajax_login_required
def follow_user(request):
	if request.is_ajax():
		follow_flag = int(request.POST.get('follow'))
		fid = request.POST.get('fid')
		if Fbfriend.objects.filter(userid=request.user, friendid__userid=fid).exists():
			old_entry = Fbfriend.objects.get(userid=request.user, friendid__userid=fid)
			old_entry.follow=follow_flag
			old_entry.save()
		else:
			new_entry = Fbfriend(userid=request.user, friendid=CliprUser.objects.get(userid=fid), follow=follow_flag)
			new_entry.save()
		return HttpResponse("done")
	else:
		raise Http404
