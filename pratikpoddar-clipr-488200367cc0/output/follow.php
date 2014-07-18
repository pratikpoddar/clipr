<?php

  $uid1 = $_GET['uid1'];
  $uid2 = $_GET['uid2'];
  $action = $_GET['action'];

  require '../lib/facebook-php-sdk/src/facebook.php';
  Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;

  $facebook = new Facebook(array(
    'appId'  => '143944345745133',
    'secret' => 'ea707d171512a76fce376cdcef53cd62',
    // 'cookie' => true,
  ));

  $userfbphp = $facebook->getUser();

  if ($userfbphp !=0 && isset($_COOKIE['loggedUserId']) && $userfbphp != $_COOKIE['loggedUserId'])
    echo "Invalid user. Login to follow";
  
  else{
        
    require_once( "../lib/KLogger.php");
    $log = new KLogger('../../logFiles/', KLogger::INFO);

    $con = mysql_connect("localhost", "root", "12345678");
    if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
    $dbconfig = parse_ini_file ( "../../db.ini");
    $dbname = $dbconfig['db_name'];
    mysql_select_db($dbname, $con);

    $sql = "SELECT * FROM fbfriend where userid=".$uid1." and friendid=".$uid2;
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

    $msg = "No Change Made";

    $row = mysql_fetch_array($result);
    
    if (empty($row) && $action == "follow") {
      $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: follow_new, productid: NA, user: ".$uid1.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId'].", subject: ".$uid2); 
  	   $sql = "INSERT INTO fbfriend (userid, friendid, follow)  VALUES (".$uid1.",".$uid2.", 1)";
       $sql2 = "INSERT INTO activityTable (userid, action, objuserid)  VALUES (".$uid1.", 'followed', ".$uid2.")";
  	   $msg = "You are succesfully 'Following' now";
  	}

    if (!empty($row) && $action == "unfollow") {
       $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: unfollow, productid: NA, user: ".$uid1.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId'].", subject: ".$uid2); 
    	 $sql = "UPDATE fbfriend SET follow=0 where userid=".$uid1." and friendid=".$uid2;
       $sql2 = "INSERT INTO activityTable (userid, action, objuserid)  VALUES (".$uid1.", 'un-followed', ".$uid2.")";
    	 $msg = "You are succesfully 'Un-following' now";
    }

    if (!empty($row) && $action == "follow") {
       $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: follow_old, productid: NA, user: ".$uid1.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId'].", subject: ".$uid2); 
       $sql = "UPDATE fbfriend SET follow=1 where userid=".$uid1." and friendid=".$uid2;
       $sql2 = "INSERT INTO activityTable (userid, action, objuserid)  VALUES (".$uid1.", 'followed', ".$uid2.")";
       $msg = "You are succesfully 'Following' now";
    }

    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

    $result = mysql_query($sql2,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql2 . mysql_error());};     

    echo $msg;

  }
  mysql_close($con);
?>

