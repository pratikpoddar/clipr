<?php
  require_once '../lib/facebook-php-sdk/src/facebook.php';
  Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
  require_once "resolveurl.php";
  require_once "globalboards.php";
  require_once "loggedoutRestrictedPages.php";
  require_once 'outpututils.php';
  /*
  **This file exports following global variables:
  **
  **  $con          connection to mysql
  **  $facebook     facebook objec
  **  $user         userid returned by facebook
  **  $loggedUserID loggeduserid(same as user, reflecting the name of user who is logged in) Also $loggedUserName
  **  $userID       userid(same as loggeduserid or the userid of a person whose clipboard is being viewed) Also $userName
  **  $loginUrl     facebook login url, to be used if user is not logged in
  **  $log          object to be used for logging
  **  $redirect_uri uri to which page needs to be redirected after logging in
  */

  $PAGINATION_SIZE = 20;
  // facebook object
  $facebook = new Facebook(array(
    'appId'  => '143944345745133',
    'secret' => 'ea707d171512a76fce376cdcef53cd62',
    // 'cookie' => true,
  ));

  $fbDialogUrl = 'https://www.facebook.com/dialog/';
  $user = $facebook->getUser();
  
  // Double check to make sure we still have a valid userid/session
  if ($user) {
       try {
          $user_profile = $facebook->api('/me');
       } catch (FacebookApiException $e) {
          $user = 0;
       }
  } 
  //setting loggedUserID and userID 
  $loggedUserID = $user;

  if (isset($_GET['userId']))
    $userID = $_GET['userId'];
  else
    $userID = $loggedUserID; 

  $isSessionStart = 0;
  if (!isset($_COOKIE["sessionid"])) {
    $isSessionStart = 1;
    $sessionid = mt_rand();
    setcookie('sessionid', $sessionid, time()+36000, '/', 'clipr.in');
  }
  else
    $sessionid = $_COOKIE["sessionid"];

  $referrer = "";
  if (isset($_GET["ref"])) {
    $referrer = $_GET["ref"];
    setcookie('referrer', $_GET["ref"], time()+36000, '/', 'clipr.in');
  }
  else if (isset($_COOKIE["referrer"]))
    $referrer = $_COOKIE["referrer"];

  // Setting cookies sessionid
  if ($user) { 
    setcookie('loggedUserId', $user, time()+36000, '/', 'clipr.in');
    setcookie('loggedUserName', $user_profile['name'], time()+36000, '/', 'clipr.in');
  } else {
    setcookie('loggedUserId', '', time()-36000, '/', 'clipr.in');
    setcookie('loggedUserName', '', time()-36000, '/', 'clipr.in');
  }

  if (isset($_GET['pagetype'])  && $_GET['pagetype']== 'productbase')
    $origin = getAbsoluteUrl("../output/product/".$_GET['id']);
  else if (isset($_GET['pagetype'])  && $_GET['pagetype']== 'prodlistbase' && isset($_GET['page']) && $_GET['page']=="clips")
    $origin = getAbsoluteUrl("../output/clips/".$userID);
  else if (isset($_GET['pagetype'])  && $_GET['pagetype']== 'prodlistbase' && isset($_GET['page']) && $_GET['page']=="tagged")
    $origin = getAbsoluteUrl("../output/tag/".$_GET['tag']);
  else
    $origin = getAbsoluteUrl("../output/home");
  // fb login parameters
  $loginUrlParams = array(
    'scope' => 'email,user_activities,user_interests,user_likes,user_hometown,friends_hometown,user_birthday,friends_birthday,user_location,friends_location,publish_actions',
    'redirect_uri' => getAbsoluteUrl('../output/closePage'),
    'display' => 'popup'
  );


  $loginUrl = "";
  $logoutUrl = "";
  $logoutUrlParams = array( 'next' => getAbsoluteUrl('../output/logout') );
  // getting login url from facebook if user is invalid
  if (!$user) {
    $loginUrl = $facebook->getLoginUrl($loginUrlParams);
  }
  else
    $logoutUrl = $facebook->getLogoutUrl($logoutUrlParams);

  if ($user !=0 && isset($_COOKIE['loggedUserId']) && $user != $_COOKIE['loggedUserId'])
    die ('Login Problem');
  
  if ($user==0 && isset($_GET["pagetype"]) && in_array($_GET['pagetype'],$loggedoutRestrictedPages ) ) {
    header( 'Location: home' );
  }

  // $expires = 60*60*24*30;
  // header("Pragma: public");
  // header("Cache-Control: maxage=".$expires);
  // header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');


  //SANITY CHECK Checking consistency of url param to prevent sql injection attacks
  if(isset($_GET['tag'])){
    if( !preg_match('/^[a-zA-Z\d_]+$/',$_GET['tag']) ) 
      die('wrong input');
  }
  if(isset($_GET['friendid'])){
    if(!is_numeric($_GET['friendid']))
      die('wrong input');
  }
  if( !is_numeric($userID) || !is_numeric($loggedUserID) ){
    die('wrong input');
  }


  //setting username and logged username for all files that include me
  $userName = "";
  if ($userID != 0) {
    $userinfo = getUserInfo($con, $userID);
    $userName = $userinfo['firstname']." ".$userinfo['lastname'];  
  }

  $loggedUserName = "";
  if ($loggedUserID != 0) {
    $loggedUserinfo = getUserInfo($con, $loggedUserID);
    $loggedUserName = $loggedUserinfo['firstname']." ".$loggedUserinfo['lastname'];  
  }
?>