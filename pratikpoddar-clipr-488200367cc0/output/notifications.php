<?php

  $loggedUserID=0;
  if (isset($_COOKIE['loggedUserId']))
    $loggedUserID = $_COOKIE['loggedUserId'];
  else
    die('invalid session');
  if( !is_numeric($loggedUserID) )
    die('wrong input');

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $sql = "SELECT notification_clear_time from notificationTime where userid = ".$loggedUserID;

  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

  $row = mysql_fetch_array($result);
  $dt = new DateTime("@0"); // epoch time
  $lastTime = $dt->format('Y-m-d H:i:s');
  if ( !empty($row) ) {
    $lastTime = $row['notification_clear_time'];
  }

  $sql = "SELECT count(*) as numnotifications FROM (SELECT * from activityTable where userid = $loggedUserID and time > cast( '$lastTime' as DATETIME) UNION (SELECT * from activityTable where userid in (SELECT friendid from fbfriend where userid = $loggedUserID) and time > cast( '$lastTime' as DATETIME))) as acttab order by time DESC";
  $result = mysql_query($sql,$con);
  if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};

  $row = mysql_fetch_array($result);
  echo $row['numnotifications']; 

  mysql_close($con);

?>
