<?php 
	require_once 'utils.php';
?>

<?php
	$now = time( );
	$then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", $now + 60*60*8);
	$expires = 60*60*8;

	header("Expires: $then");
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);

	function getTopSimilarProducts($con, $pid){
		$retarr = array();
		if ($pid != 0)
		{
			exec('cd ..; python userProductMappings.py --productid='.$pid.'; cd output');
			$sql = "SELECT distinct similar,image,title from productTopSimilar as pts join productDetail as pd on pts.similar = pd.productid where pts.productid =$pid and pts.similar != pts.productid limit 6";
			$result = mysql_query($sql,$con);
			if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};	
			while($row = mysql_fetch_array($result))
			{
				$row['title'] = trim($row['title']);
				array_push($retarr, $row);
			}
		}
		return $retarr;
	}

	function getTopSimilarClips($con, $pid){
		$retarr = array();
		if ($pid != 0)
		{
			$sql = "SELECT ct2.productid as similar,image,title, count(*) as num from clipsTable as ct1 join clipsTable as ct2 join productDetail as pd on ct1.userid = ct2.userid and ct2.productid = pd.productid where ct1.productid != ct2.productid and ct1.productid = $pid group by ct2.productid order by num desc limit 6";
			$result = mysql_query($sql,$con);
			if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};	
			while($row = mysql_fetch_array($result))
			{
				$row['title'] = trim($row['title']);
				array_push($retarr, $row);
			}
		}
		return $retarr;
	}

	function insertIntoViewTable($con, $pid, $uid){
		if ($pid != 0 )
		{
			$sql = "INSERT into productView(userid, productid, views) VALUES($uid, $pid, 1) ON DUPLICATE KEY UPDATE views = views+1";
			$result = mysql_query($sql,$con);
			if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
			increaseForView($con, $pid);
		}
	}

	$pid = $_GET['id'];

	insertIntoViewTable($con, $pid, $user);
	$pinfo = getProdInfoForUser($con, $pid, $loggedUserID);

	// Additional info required
    $listofimages = explode('$$$', $pinfo['image']);
	$buybutton = "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Go to the seller's website\" class='cliprproduct btn' href=\"cliprtransfer?link=".($pinfo['link'])."\" target='_blank'><i class='icon-shopping-cart'></i>Buy at Seller Page</a>";

    $pinfo['clipbutton'] = getClipButton ($con, $loggedUserID, $pid, "product", "right","cliprproduct");
    $pinfo['tagbutton'] = "<a id='product-tag-btn' rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Tag a friend\" data-productid=".$pid." class='cliprproduct btn btn-primary tag-btn' href='javascript:void(0)'>Tag</a>";
    $pinfo['buybutton'] = $buybutton;
    $pinfo['similar'] = getTopSimilarProducts($con,$_GET['id']);
    $pinfo['similarclips'] = getTopSimilarClips($con,$_GET['id']);

	$loggedinFlag = 0;
	if($loggedUserID > 0)
		$loggedinFlag = 1;
	$finalres = json_encode(array('pinfo'=>$pinfo, 'loggedinflag' =>$loggedinFlag));
	echo $finalres;
?>
<?php 
  require_once 'sqlcloser.php';
?>
