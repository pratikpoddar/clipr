from mobile.decorators import ajax_login_required
from django.http import HttpResponse
from mobile.models import Tagtable, CliprUser, Productdetail
from facepy import GraphAPI
from django.db.models import F

@ajax_login_required
def tag_friend(request):
	if request.is_ajax():
		pid = request.POST.get('pid')
		uid = request.user.userid
		fids = request.POST.getlist('friends[]')
		for fid in fids:
			new_entry = Tagtable(user=request.user, product=Productdetail.objects.get(productid=pid), taggeduser=CliprUser.objects.get(userid=fid))
			new_entry.save()
		# increase product score for tagging
		Productdetail.objects.filter(productid=pid).update(score=F('score') + 1)
		comment = request.POST.get('comment')
		# post to fb here
		publish_to_fb( request.user, fids, pid, comment)
		return HttpResponse("hi")
	else:
		return HttpResponse("bye")

def publish_to_fb( user, fids, pid, comment ):
	""" publish the opengraph tag action to facebook """
	graph = GraphAPI(user.accesstoken)
	message = reduce(lambda x,y:x+y,map(lambda x: "@["+str(x)+"] ",fids)) + comment
	graph.post(path='/me/cliprin:comment', access_token=user.accesstoken,product='http://clipr.in/output/fbproduct?id='+str(pid),message=message, method='POST')
