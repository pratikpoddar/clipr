<?php

  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  $userId = $_GET['userId'];
  $source = $_GET['userId'];
  if(isset($_GET['source']))
    $source = $_GET['source'];

  $interests = $_GET['interests'];

  if(!is_numeric($userId))
    die('wrong input');

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: submit_interest, productid: NA, user: ".$userId.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 
  // Permission Check
  if ($_COOKIE['loggedUserId'] != $source) { die('Permission Error'); }

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $sql = "DELETE from interestsTable where userid=".$userId." and source = ".$source;
  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

  foreach ($interests as $interest) {
    if(!is_numeric($interest))
      die('wrong input');
    $sql = "INSERT INTO interestsTable (userid, interest, source)  VALUES (".$userId.",".$interest.",".$source.")";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     
  }

  echo "Submitted";

  mysql_close($con);

?>
