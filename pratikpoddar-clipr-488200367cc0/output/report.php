<?php

  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  $userId = $_GET['userId'];
  $productId = $_GET['productId'];
  
  // Permission Check
  if ($_COOKIE['loggedUserId'] != $userId) { die('Permission Error'); }
  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: reporting, productid: ".$productId.", user: ".$userId.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 
  
  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $sql = "SELECT * FROM reportedProducts where userid=".$userId." and productid=".$productId;
  $result = mysql_query($sql,$con);
  if (!$result) 
  { 
    error_log(mysql_error()); die('Error: ' . $sql . mysql_error());
  }

  $msg = "Repeat";

  $sql = "";
  $row = mysql_fetch_array($result);
  if (empty($row) ) 
  {
    $sql = "INSERT INTO reportedProducts (userid, productid)  VALUES (".$userId.",".$productId.")";
    $msg = "Success";
	}
  
  if ($sql != "") {
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};    
  }

  echo $msg;

  mysql_close($con);
?>

