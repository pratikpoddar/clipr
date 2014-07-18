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
		# publish_to_fb( request.user, pid )
		return HttpResponse("hi")
	query = request.GET.get('query')
	search_in = request.GET.get('in',None)
	
	$sql = "SELECT productid, title, max(sc) as score from 
	(
	SELECT productid, title, 4 as sc FROM productDetail where title like '%".$query."%'
	UNION SELECT productid, title, 3 as sc FROM productDetail where CONCAT(description, \" \", siteId) like '%".$query."%'
	UNION SELECT productid, title, 2 as sc FROM productDetail where CONCAT(title, \" \", description, \" \", siteId) like '%".$query."%'
	UNION SELECT productid, title, 1 as sc FROM productDetail where CONCAT(siteId, \" \", title, \" \", description, \" \", siteId) like '%".str_replace(" ", "%", $query)."%'
	) as tempTable group by productid order by score desc limit 20";
	$result = mysql_query($sql,$con);
	if (!$result) 
	{ 
	error_log(mysql_error()); die('Error: ' . $sql . mysql_error());
	}

	$titleres = array();
	while($row = mysql_fetch_array($result)) {
	$res=array();
	$res['productid'] = $row['productid'];
	$res['title'] = htmlentities( $row['title'], ENT_QUOTES,'UTF-8'); 
	$res['score'] = $row['score'];
	array_push($titleres, $res);
	}
	
	$sql = "SELECT id, name, max(sc) as score from 
	(
		SELECT id, name, 5 as sc FROM allTags where name like '%".$query."%'
	UNION SELECT id, name, 5 as sc FROM allTags where name like '%".str_replace(" ", "_", $query)."%'
	UNION SELECT id, name, 5 as sc FROM allTags where alternatives like '%".$query."%'
	) as tempTable group by id order by score desc limit 10";

	$sql = "SELECT id,name,5 as score from allTags where name like '%".$query."%' limit 10";
	$result = mysql_query($sql,$con);
	if (!$result) 
	{ 
	error_log(mysql_error()); die('Error: ' . $sql . mysql_error());
	}

	$tagres = array();
	while($row = mysql_fetch_array($result)) {
	 array_push($tagres, $row);
	}

	$sql = "SELECT id, groupname, max(sc) as score from 
	(
		SELECT id, groupname, 5 as sc FROM groupTable where groupname like '%".$query."%'
	UNION SELECT id, groupname, 5 as sc FROM groupTable where groupname like '%".str_replace(" ", "_", $query)."%'
	UNION SELECT id, groupname, 5 as sc FROM groupTable where alternatives like '%".$query."%'
	) as tempTable group by id order by score desc limit 10";

	$result = mysql_query($sql,$con);
	if (!$result) 
	{ 
	error_log(mysql_error()); die('Error: ' . $sql . mysql_error());
	}

	$groupres = array();
	while($row = mysql_fetch_array($result)) {
	 array_push($groupres, $row);
	}

	$sql = "SELECT userid, CONCAT(firstname,' ',lastname) as name, 5 as score FROM fbdata where CONCAT(firstname,' ',lastname) like '%".$query."%' and accessToken != ''";
	$result = mysql_query($sql,$con);
	if (!$result) 
	{ 
	error_log(mysql_error()); die('Error: ' . $sql . mysql_error());
	}

	$userres = array();
	while($row = mysql_fetch_array($result)) {
	 array_push($userres, $row);
	}
	$finalres = json_encode(json_encode( array('products' => $titleres,'tags' => $tagres, 'groups'=>$groupres, 'users'=>$userres )));

	echo $finalres;

	mysql_close($con);
?>

