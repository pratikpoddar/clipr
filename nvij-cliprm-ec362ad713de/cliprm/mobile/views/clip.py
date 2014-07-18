from mobile.models import Clipstable, Productgroup, Productdetail
from mobile.decorators import ajax_login_required
from django.http import Http404, HttpResponse
from django.utils import simplejson
from facepy import GraphAPI
from django.db.models import F

@ajax_login_required
def clip_product(request):
	if request.is_ajax():
		pid = request.GET.get('pid')
		uid = request.user.userid
		if not Clipstable.objects.filter(user__userid=uid).filter(product__productid=pid).exists():
			new_entry = Clipstable(user=request.user, product=Productdetail.objects.get(productid=pid), cliptag=getClipTag(pid),points=1)
			new_entry.save()
			# increase product score for clipping
			Productdetail.objects.filter(productid=pid).update(score=F('score') + 1)
		publish_to_fb( request.user, pid )
		return HttpResponse("hi")

def getClipTag(pid):
	defaultBoardMap = {
		"apparel_accessories" : "fashion",
		"cosmetics" : "fashion",
		"apparel" : "fashion",
		"books" : "fun",
		"kitchen_accessories" : "home",
		"home_furnishing" : "home",
		"electronics" : "gadget",
		"sports_equipments" : "fitness",
		"health_and_wellness" : "fitness",
		"home_accessories" : "home",
		"office_accessories" : "home",
		"bathroom_accessories" : "home",
		"home_decor" : "home",
		"travel_accessories" : "fun",
		"bar_accessories" : "fun"
	};
	group = Productgroup.objects.filter(product__productid=pid)
	if len(group)>0:
		return defaultBoardMap[group[0].groupid.groupname]

def publish_to_fb( user, pid ):
	""" publish the clip action to facebook """
	graph = GraphAPI(user.accesstoken)
	graph.post(path='/me/cliprin:clip', access_token=user.accesstoken,product='http://clipr.in/output/fbproduct?id='+str(pid), method='POST')
