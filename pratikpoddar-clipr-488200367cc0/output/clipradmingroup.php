<?php

  // TODO: To be doable only by admin

  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: change_admin_group, productid: ".$_GET['productId'].", user: NA, sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 

  $sql = "DELETE from productGroup where productid=".$_GET['productId'];
  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};     
  
  if ($_GET['tag'] != "") {
	 $sql = "INSERT INTO productGroup(productid, groupid, userid) VALUES(".$_GET['productId'].",".$_GET['tag'].", ".$_GET['uid'].")";
	 $result = mysql_query($sql,$con);
	 if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};     
  }
  
  mysql_close($con);
?>

