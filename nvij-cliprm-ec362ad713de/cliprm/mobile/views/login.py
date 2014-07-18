from django.shortcuts import render_to_response
from django.template import RequestContext
from django.http import HttpResponseRedirect

def login_page(request):
	# <a href="{% url 'socialauth_begin' 'facebook' %}?{{ redirect_querystring }}">	here {{ user.first_name }}</a>
	context_instance = RequestContext(request)
	redirect_url = request.GET.get('next','/')
	if request.user.is_authenticated():
		return HttpResponseRedirect(redirect_url)
	return render_to_response('m_login.html', {'next': redirect_url }, context_instance)
