from mobile.decorators import ajax_login_required
from django.http import Http404, HttpResponse
from mobile.models import Emailcommunication

@ajax_login_required
def change_pref(request):
	if request.is_ajax():
		column = request.POST.get('col')
		new_state = 0
		if request.POST.get('new_state') == "true":
			new_state=1
		updated_column = {column:new_state}
		Emailcommunication.objects.filter(user=request.user).update(**updated_column)
		return HttpResponse(new_state)
	else:
		raise Http404
