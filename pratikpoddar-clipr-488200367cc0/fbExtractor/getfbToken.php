<?php
  require('../output/outpututils.php');
  require_once( "../lib/KLogger.php");

  $log = new KLogger('../../logFiles/', KLogger::INFO);


  require_once '../lib/facebook-php-sdk/src/facebook.php';
  Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;

  $facebook = new Facebook(array(
    'appId'  => '143944345745133',
    'secret' => 'ea707d171512a76fce376cdcef53cd62',
    // 'cookie' => true,
  ));
  $user = $facebook->getUser();

  $facebook->setExtendedAccessToken();

  // Hack: Added getExtendedAccessToken in base_facebook.php
  $longAccessToken = $facebook->getExtendedAccessToken();
  $facebook->setAccessToken($longAccessToken);

?>	
<?php 
  $GLOBALS['log']->logInfo("--------- Got accessToken : ".$longAccessToken); 
  
  if (isset($_GET['ref']) && $user != 0 && $_GET['ref'] != 0){
    $ref = $_GET['ref'];
    $sql="SELECT * from fbdata where userid = $user and accessToken != ''";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);
    if (empty($row)){
      $GLOBALS['log']->logInfo("--------- Successful Referral : ".$user.", ".$ref);
      $sql="INSERT INTO inviteStats(referrer, userid) values('$ref','$user')";
      if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};
    }
  }
  $accessToken = $longAccessToken;

  $GLOBALS['log']->logInfo("--------- Got user from accessToken : ".$longAccessToken.", ".$facebook->getUser());

  $sql="INSERT IGNORE INTO accessTokenTable (accessToken) VALUES('$accessToken')";
  if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};

  if ($user)
  {
    $sql="INSERT INTO loginActivity (userid) VALUES($user)";
    if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};
  }

  $sql = "INSERT IGNORE INTO fbdata (userid,fbname) VALUES('".$facebook->getUser()."','".$facebook->getUser()."')";
  if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};
  $command = "php ../fbExtractor/fbdataextractor.php ".$accessToken." > /dev/null &";
  exec($command);

  if(!isset($_GET['login']))
    if ($user)
    {
      $sql = "SELECT interest from interestsTable where userid=".$facebook->getUser()." and source=".$facebook->getUser();
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};
      if (!($interest = mysql_fetch_row($result))) 
      {
        $redirect = urldecode($_GET['origin']);
        header( 'Location: '.$redirect ) ;
      }
      else
      {
        header( 'Location: '.$_GET['origin'] ) ;
      }
    }
    else {
      if(!isset($_GET['origin']))
        header( 'Location: home' ) ;
      else
        header( 'Location: '.$_GET['origin'] ) ;
    }
?>
<?php
	require('../output/sqlcloser.php');
?>
