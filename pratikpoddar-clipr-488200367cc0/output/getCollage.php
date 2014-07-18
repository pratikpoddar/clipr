<?php 
	require_once "../output/utils.php";
	require_once "../output/collageutils.php";

	if(isset($_GET['cid']))
		$finalres = fetchCollageById($con, $_GET['cid']);
	else
		$finalres = fetchCollageByUser($con, $_GET['uid']);

	echo json_encode($finalres);

	require_once "sqlcloser.php";
?>
