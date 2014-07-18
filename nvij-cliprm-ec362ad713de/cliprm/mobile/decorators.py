from django.utils import simplejson
from django.http import Http404, HttpResponse
from django.conf import settings

def ajax_login_required(view_func):
    def wrap(request, *args, **kwargs):
        if request.user.is_authenticated():
            return view_func(request, *args, **kwargs)
        LOGIN_URL = getattr(settings, "LOGIN_URL",None)
        json = simplejson.dumps({ 'not_authenticated': True, 'login_url':LOGIN_URL })
        return HttpResponse(json, mimetype='application/json')
    wrap.__doc__ = view_func.__doc__
    wrap.__dict__ = view_func.__dict__
    return wrap
