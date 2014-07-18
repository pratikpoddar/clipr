from django.shortcuts import render_to_response
from django.template import RequestContext
from django.contrib.auth.decorators import login_required
from django.views.decorators.csrf import ensure_csrf_cookie # to prevent cross-site request forgery

@login_required
@ensure_csrf_cookie
def tag_page(request):
	context_instance = RequestContext(request)
	return render_to_response('m_tagpage.html', {'pid':request.GET.get('pid')}, context_instance)
