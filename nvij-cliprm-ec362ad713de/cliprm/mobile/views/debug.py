from django.http import Http404, HttpResponse

def temp(request):
	return HttpResponse(request.META['REMOTE_ADDR'])