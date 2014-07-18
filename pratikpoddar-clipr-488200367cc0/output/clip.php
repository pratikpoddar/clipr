<?php
  require_once( "../lib/KLogger.php");
  require_once "resolveurl.php";
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  require_once "outpututils.php";

  function checkPermfbpostactivity($con, $uid) {
      $sql = "SELECT fbactivitypost from emailCommunication where userid=$uid";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      return $row['fbactivitypost'];
  }

  function addClipTag($con, $uid, $pid, $tag)
  {
    if($uid!=0){
      $sql = "INSERT ignore INTO clipsTable (userid, productid, clipTag)  Values( ".$uid.",".$pid.",'".$tag."' )";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};    

      $sql = "INSERT INTO activityTable (userid, action, objproductid, board)  VALUES (".$uid.", 'clipped a product', ".$pid.", '".$tag."')";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};
      increaseForClip($con, $pid);
    }
  }

  $clipTags = array();
  if (defined('STDIN')) {
    $userId = $argv[1];
    $productId = $argv[2];
    if($argc == 4) 
      $clipTags = array($argv[3]);
    $oldstate = "Clip";
  } 
  else 
  { 
    $userId = $_GET['userId'];
    $productId = $_GET['productId'];
    $oldstate = $_GET['oldstate'];

    if (isset($_GET['clipTags']) && ($_GET['clipTags'] != "null") && ($_GET['clipTags'] != "") ) 
      $clipTags = $_GET['clipTags'];

    // Permission Check only if doing it from web
    if (!isset($_COOKIE['loggedUserId']) || $_COOKIE['loggedUserId'] != $userId) { die('Permission Error'); }

    require_once 'utils.php';
    require_once '../lib/facebook-php-sdk/src/facebook.php';
    Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;

    $userfbphp = $facebook->getUser();

    if ($userfbphp !=0 && isset($_COOKIE['loggedUserId']) && $userfbphp != $_COOKIE['loggedUserId'])
      die ('Login Problem');
  }

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: clipping, productid: ".$_GET['productId'].", user: ".$userfbphp.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 


  $sql = "DELETE FROM clipsTable where userid=".$userId." and productid=".$productId;
  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};    

  $sql = "DELETE from activityTable where userid = $userId and action = 'clipped a product' and objproductid = $productId";
  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};    

  if($oldstate == "Clip"){

    if (!empty($clipTags)) 
    {
      foreach ($clipTags as $clipTag) {
        $clipTag = strtolower(str_replace(" ", "_", trim($clipTag)));
        addClipTag($con, $userId, $productId, $clipTag);
      }
    }
    else {
      addClipTag($con, $userId, $productId, "wishlist");
    }

    if (checkPermfbpostactivity($con, $userId)){
      $a = 'php publishclip.php '.$userId.' '.$productId.' > /dev/null &';
      exec($a);
    }
  }
  $msg = "Success";

  if (!defined('STDIN')) {
    $res = array('message'=>$msg, 'clipperstring'=> getClippersString($con,getProductInfo($con, $productId)), 'clipperlist'=> getClippersList($con, $productId));
    echo json_encode($res);
  }
  else
    echo $msg;

  mysql_close($con);
?>

