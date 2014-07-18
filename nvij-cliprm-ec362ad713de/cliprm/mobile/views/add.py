from django.http import Http404, HttpResponse
from django.shortcuts import render_to_response
from django.template import RequestContext

def d_add(request,status=""):
	context_instance = RequestContext(request)
	fail=False
	if status and status == 'fail':
		fail=True
	return render_to_response('d_add.html',{'fail':fail},context_instance)
