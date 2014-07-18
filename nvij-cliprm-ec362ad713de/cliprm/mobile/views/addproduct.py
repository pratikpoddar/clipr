from django.http import HttpResponseRedirect, HttpResponse
from backend.crawlers.addutils import addSingleProduct
from django.contrib.auth.decorators import login_required

@login_required
def d_addproduct(request):
	link=request.GET.get('link','')
	if link:
		# return HttpResponse(link)
		pid = addSingleProduct(link,request.user.userid)
		if pid:
			return HttpResponseRedirect("/product/"+str(pid))
		else:
			return HttpResponseRedirect("/desktop/add/fail")
	else:
		return HttpResponseRedirect("/desktop/add/fail")
