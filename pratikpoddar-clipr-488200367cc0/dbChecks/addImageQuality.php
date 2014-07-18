<?php

function biggerImage($file1, $file2) {
	list($width1, $height1, $type, $attr) = getimagesize($file1);
	list($width2, $height2, $type, $attr) = getimagesize($file2);
	var_dump($width1, $width2, $height1, $height2);
	if (($width1 > $width2) && ($height1 > $height2))
		return true;
	else
		return false;
}
function insertImageQuality($pid, $quality, $con){
	$sql = "UPDATE productDetail set imageQuality = $quality where productid = $pid";	
	$result = mysql_query($sql,$con);
	if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());}
}

function getBigImage($image) {
$hash = md5($image);
return "http://clipr.in/prodImage/".$hash."-big.jpg";
}

function getOrigImage($image) {
$hash = md5($image);
return "http://clipr.in/prodImage/".$hash."-orig.jpg";
}

$con = mysql_connect("localhost", "root", "12345678");
if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());}
$dbconfig = parse_ini_file ( "../../db.ini");
$dbname = $dbconfig['db_name'];
mysql_select_db($dbname, $con);
mysql_query("set names 'utf8'");

$sql = "SELECT productid, image from productDetail";
$result = mysql_query($sql,$con);
if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
while($row = mysql_fetch_array($result))
{
	$listofimages = explode('$$$', $row['image']);
	$bigimglink = getBigImage($listofimages[0]);
	$origimglink = getOrigImage($listofimages[0]);
	echo $row['productid'];
	biggerImage($origimglink, $bigimglink);
}

?>