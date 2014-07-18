<?php

  require_once 'utils.php';

  function resultToActivityArray($con, $result, $class) {
      
      $activityArr = array();
      while($act = mysql_fetch_row($result)) {

        if ($act[1] == 0) {
          $actUser = getUserInfo($con, $act[0]);
          $actObjProd = getProductInfo($con, $act[2]);
          if($act[3] == "clipped a product"){
            $board = $act[4];
            if ($board != "NoTag")
              $boardinfo =  " to the board <a class='ajax-link' href='clips/".$act[0]."'><span style='text-transform:capitalize;'>".str_replace("_"," ",$act[4])."</span></a>";
            else
              $boardinfo = "";
            array_push($activityArr, 
                "<div class=$class><img class='pull-left' src='http://graph.facebook.com/".$act[0]."/picture?type=square' style='max-height:30px;padding-right:10px;padding-bottom:2px'>
                  <span class='' style='padding-bottom:10px;word-wrap:break-word;word-break:break-all;'>
                    <a href='clips/".$act[0]."' class='ajax-link'>".$actUser['firstname']." ".$actUser['lastname']."</a>&nbsp;".$act[3]."&nbsp;<a href='product/".$act[2]."' class='ajax-link'>".$actObjProd['title']."</a>".$boardinfo."</span><span class='pull-right' style='margin-top:5px;margin-left:5px;font-size:90%'>".$act[5]."</span><br/><br/></div>"
            ); 
          }
          else
            array_push($activityArr, 
                "<div class=$class><img class='pull-left' src='http://graph.facebook.com/".$act[0]."/picture?type=square' style='max-height:30px;padding-right:10px;padding-bottom:2px'>
                  <span class='' style='padding-bottom:10px;word-wrap:break-word;word-break:break-all;'>
                    <a href='clips/".$act[0]."' class='ajax-link'>".$actUser['firstname']." ".$actUser['lastname']."</a>&nbsp;".$act[3]."&nbsp;<a href='product/".$act[2]."' class='ajax-link'>".$actObjProd['title']."</a></span><span class='pull-right' style='margin-top:5px;margin-left:5px;font-size:90%'>".$act[5]."</span><br/><br/></div>"
            );
        }

        if ($act[2] == 0) {
          $actUser = getUserInfo($con, $act[0]);
          $actObjUser = getUserInfo($con, $act[1]);
          
          array_push($activityArr, 
            "<div class=$class>
              <img class='pull-left' src='http://graph.facebook.com/".$act[0]."/picture?type=square' style='max-height:30px;padding-right:10px'>
              <span class='' style='padding-bottom:2px;word-wrap:break-word;word-break:break-all;'>
                <a href='clips/".$act[0]."' class='ajax-link'>".$actUser['firstname']." ".$actUser['lastname']."</a>&nbsp;".$act[3]."&nbsp;
                <a href='clips/".$act[1]."' class='ajax-link'>".$actObjUser['firstname']." ".$actObjUser['lastname']."</a>
              </span>
              <span class='pull-right' style='margin-top:5px;margin-left:5px;font-size:90%'>".$act[5]."</span><br/><br/>
            </div>"
          ); 
        }
      }
      return $activityArr;
  }

  function getActivity ($con, $userId) {
      $sql = "SET SESSION time_zone = '+5:30'";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      $sql = "SELECT notification_clear_time from notificationTime where userid = ".$userId;

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      $row = mysql_fetch_array($result);
      $hasEntry = false;
      $dt = new DateTime("@0"); // epoch time
      $lastTime = $dt->format('Y-m-d H:i:s');
      if ( !empty($row) ) {
        $hasEntry = true;
        $lastTime = $row['notification_clear_time'];
      }

      $activityArr = array();

      $sql = "SELECT userid, objuserid, objproductid, action, board, DATE_FORMAT(time,'%b %d %Y %h:%i %p') FROM (SELECT * from activityTable where userid in (SELECT friendid from fbfriend where userid = $userId) and time > cast( '$lastTime' as DATETIME) ) as acttab order by time DESC";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     
      $activityArr =  array_merge($activityArr, resultToActivityArray($con, $result, 'new_notification'));

      $sql = "SELECT userid, objuserid, objproductid, action, board, DATE_FORMAT(time,'%b %d %Y %h:%i %p') FROM (SELECT * from activityTable where userid in (SELECT friendid from fbfriend where userid = $userId) and time <= cast( '$lastTime' as DATETIME) ) as acttab order by time DESC limit 40";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     
      $activityArr =  array_merge($activityArr, resultToActivityArray($con, $result, 'old_notification'));

      // notification being displayed: set notification_clear_time
      
      $sql = "SET SESSION time_zone = '+0:00'";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      $sql = "UPDATE notificationTime set notification_clear_time = UTC_TIMESTAMP() where userid = ".$userId." ";

      if(!$hasEntry){
        $sql = "INSERT INTO notificationTime(userid, notification_clear_time) VALUES(".$userId.", UTC_TIMESTAMP())";
      }

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      return $activityArr;
    }   

    if ($loggedUserID != 0) {
      $loggedUserinfo = getUserInfo($con, $loggedUserID);
    }
   
    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: view_activity, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
?>

<style type="text/css">

.old_notification {
  padding: 8px;
  margin: 4px;
}

.new_notification {
  padding: 8px;
  margin: 4px;
  background-color: #eee;
}

</style>

<div class="row-fluid">
  <h3 style="text-align:center">What your friends have been upto!</h3>
    <?php 
      $activityArr = getActivity($con, $loggedUserID);
      foreach ($activityArr as $activity) {
        echo $activity;
      }
    ?>
</div>
