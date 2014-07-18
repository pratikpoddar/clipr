<?php
  require_once( "../lib/KLogger.php");
  require_once "resolveurl.php";
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  function checkPermfbpostactivity($con, $uid) {
      $sql = "SELECT fbactivitypost from emailCommunication where userid=$uid";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      return $row['fbactivitypost'];
  }

  function getUidFromFbName($con, $fbname){
    $sql = "SELECT userid from fbdata where fbname is not null and fbname = '$fbname'";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);
    if(empty($row))
      return 0;
    else
      return $row['userid'];
  }

  function addUserTag($con, $uid, $pid, $tuid)
  {
    if( $uid != 0 ){
      $sql = "INSERT ignore INTO tagTable (userid, productid, taggeduser)  Values( ".$uid.",".$pid.",'".$tuid."' )";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};    
      increaseForTag($con, $pid);
    }
  }

  $taggedUsers = json_decode($_GET['tags'],true);
  $productId = $_GET['pid'];
  $comment = $_GET['comment'];

  require_once 'utils.php';

  $userfbphp = $facebook->getUser();

  // Permission Check only if doing it from web
  if (!isset($_COOKIE['loggedUserId']) || $_COOKIE['loggedUserId'] != $userfbphp) { die('Permission Error'); }


  if ($userfbphp == 0)
    die ('Login Problem');

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: tagging, productid: ".$_GET['pid'].", user: ".$taggedUsers.", sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$userfbphp); 

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};

  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  if (!empty($taggedUsers)) 
  {
    $tagstring = "".$taggedUsers[0];
    $firstUser = true;
    foreach ($taggedUsers as $tuid) {
      addUserTag($con, $userfbphp, $productId, $tuid);
      if($firstUser)
        $firstUser = false;
      else
        $tagstring = $tagstring.",".$tuid;
    }
    if (checkPermfbpostactivity($con, $userfbphp)){
      $a = 'php publishtag.php '.$userfbphp.' '.$productId.' '.$tagstring.' "'.str_replace("\"", "\\\"", str_replace( "\\", "\\\\", $comment)) .'" > /dev/null &';
      exec($a);
    }
  }

  mysql_close($con);
?>
