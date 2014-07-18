<?php 
	require_once 'utils.php';
?>

<?php
	$now = time( );
	$expires = 60*60*8;
	$then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", $now + $expires);

	header("Expires: $then");
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);

	$pid = $_GET['pid'];
	$pinfo = getProductInfo($con, $pid);
	$listofimages = explode('$$$', $pinfo['image']);
	$images = array();
	foreach($listofimages as $image)
		array_push($images, array('small'=> getSmallImage($image), 'big' => getBigImage($image)));

	$finalres = json_encode($images);
	echo $finalres;
?>
<?php 
  require_once 'sqlcloser.php';
?>
