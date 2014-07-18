<?php
  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);
  $uid = $_GET['userid'];

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: get_interests, productid: NA, user: ".$uid.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 
  $sql = "SELECT interest from interestsTable where userid= ".$uid." and source = ".$uid;
  $result = mysql_query($sql,$con);
  if (!$result) {
    error_log(mysql_error()); 
    die('Error: ' . $sql . mysql_error());
  }
  $interestres = array();
  while($row = mysql_fetch_array($result)) {
     array_push($interestres, $row);
  }
  $finalres = json_encode( $interestres );
  echo $finalres;

  mysql_close($con);
?>
