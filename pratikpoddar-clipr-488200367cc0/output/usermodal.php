<?php 
  header("Pragma: public");
  header("Cache-Control: maxage=20");
  header('Expires: ' . date('D, d M Y H:i:s', time() + (60*1)) . ' GMT');

  require_once "utils.php";

  function getFollowerJson($con, $uid, $luid){
    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from fbfriend as ff join fbdata as fd on ff.userid = fd.userid where ff.follow = 1 and ff.friendid = ".$uid."  ";
    return getUserJson($con,$sql,$luid);
  }

  function getUserJson($con,$sql,$luid){
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
      $row['followbtn'] = getFollowButton($con,$luid, $row['userid']);
      array_push($ret, $row);
    }
    return $ret;
  }

  function getFollowingJson($con,$uid, $luid){
    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from fbfriend as ff join fbdata as fd on ff.friendid = fd.userid  where ff.follow = 1 and fd.accessToken != \"\" and ff.userid = ".$uid." "; 
    return getUserJson($con,$sql,$luid);
  }
  
  function getClippedJson($con,$pid, $luid){
    $sql = "SELECT distinct fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from clipsTable as ct join fbdata as fd where fd.userid = ct.userid and ct.productid=$pid";
    return getUserJson($con,$sql,$luid);
  }


  if($_GET['relation'] == "follower")
    $users = getFollowerJson($con,$_GET['uid'], $loggedUserID);
  else if ($_GET['relation'] == "following")
    $users = getFollowingJson($con,$_GET['uid'], $loggedUserID);
  else if ($_GET['relation'] == "clipped")
    $users = getClippedJson($con,$_GET['pid'], $loggedUserID);

  echo json_encode( array( 'loggedUserId' => $loggedUserID, 'users' => $users) );
  require_once "sqlcloser.php";
?>
