<?php 
	require_once "../output/utils.php";
	require_once "../output/collageutils.php";

	$collage = json_decode($_GET['collage'],true);
	$id = rand ( 10000000 , 100000000 );
	$comm = getImageMagickCommand($collage, $id, true);//.' > /dev/null &';
	exec($comm);

	echo $id;
	require_once "sqlcloser.php";
?>
