<?php 
	require_once 'utils.php';
	require_once 'prodlistutils.php';
?>
<?php 
	$pinfos = array();
	foreach ($listOfGroups as $groupName) {
	  $i = $_GET['pagination'] + 1;
	  $nproduct=0;
	  foreach ($productIds as $pid) {
		if ($groupNames[$nproduct] == $groupName)
		{
			$pinfo = getProductInfo($con, $pid, true);
			$listofimages = explode('$$$', $pinfo['image']);
			$pinfo['description'] = strip_tags(implodeDescription( explodeDescription($pinfo['description'])));
			$pinfo['displayimage'] = sanitize(getBigImage($listofimages[0]));
			$pinfo['clippers'] = (getClippersString($con, $pinfo));
			$pinfo['docid'] = sanitize($groupName."col".$i);
			$pinfo['clipunclip'] = getClipUnclip($con, $pid, $loggedUserID);
			$relevanttag = getRelevantTag($con, $loggedUserID,$loggedUserID, $pid);
			if($_GET['page']=="friendsuggestion"){
				if(!isset($_GET['friendid']))
					die('friend not chosen');
				$friendid = $_GET['friendid'];
				$relevanttag = getRelevantTag($con, $friendid, $loggedUserID, $pid);
			}
			$pinfo['tag']=ucwords(str_replace("_", " ",$relevanttag));
			$pinfo['clipcount'] = getClippersCount($con, $pid);
		    $hash = md5($listofimages[0]);
		    list($width, $height, $type, $attr) = getimagesize("../prodImage/".$hash."-big.jpg");
			$pinfo['imgwidth'] = $width;
			$pinfo['imgheight'] = $height;
			array_push($pinfos, $pinfo);
			$i = ( $i % 2 )+1;
		}
		$nproduct++;
	  }
	}
	$nexturl = "";
	if (count($productIds) >= $GLOBALS['PAGINATION_SIZE']) {
		if (!isset($_GET['tag']) && !isset($_GET['friendid']) )
			$nexturl = "getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE']); 
		else if( !isset($_GET['friendid']) )
			$nexturl = "getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE'])."&tag=".$_GET['tag'];
		else 
			$nexturl = "getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE'])."&friendid=".$_GET['friendid'];

	}

	// stopped logged out feed at 40
	if($_GET['page']=="trending" && $_GET['userId']==0 && $_GET['pagination'] >= 20)
		$nexturl = "";

	$finalres = json_encode(array('pinfos'=>$pinfos, 'nextlink'=>$nexturl));
	echo $finalres;
?>
<?php 
	require 'sqlcloser.php';
?>
