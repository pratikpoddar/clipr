<?php

	  require_once( "../lib/KLogger.php");
  	$log = new KLogger('../../logFiles/', KLogger::INFO);

    if ($_COOKIE['loggedUserId'] != $_GET['userId']) { die('Permission Error'); }
    require '../lib/facebook-php-sdk/src/facebook.php';
    Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;

    $facebook = new Facebook(array(
      'appId'  => '143944345745133',
      'secret' => 'ea707d171512a76fce376cdcef53cd62',
      // 'cookie' => true,
    ));

    $userfbphp = $facebook->getUser();

    if ($userfbphp !=0 && isset($_COOKIE['loggedUserId']) && $userfbphp != $_COOKIE['loggedUserId'])
      die ('Login Problem');


  	$con = mysql_connect("localhost", "root", "12345678");
  	if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
    $dbconfig = parse_ini_file ( "../../db.ini");
    $dbname = $dbconfig['db_name'];
    mysql_select_db($dbname, $con);


    $sql = "UPDATE emailCommunication SET ".$_GET['col']."=(".$_GET['col']."+1)%2 WHERE userid = ".$_GET['userId'];
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};

    echo "Communication Preferences Changed";

    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: change_email_prefs, productid: NA, user: ".$_GET['userId'].", sessionid: ".$_COOKIE['sessionid'].", preference_column: ".$_GET['col']); 

  	mysql_close($con);

?>


