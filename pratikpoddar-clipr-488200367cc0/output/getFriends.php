<?php

  $now = time( );
  $then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", $now + 60*60*8);
  $expires = 60*60*8;

  header("Expires: $then");
  header("Pragma: public");
  header("Cache-Control: maxage=".$expires);

  $uid = $_GET['userid'];
  $query = trim($_GET['q']);
  $limit = 0;
  if(isset($_GET['limit']))
    $limit = $_GET['limit'];
  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $sql = "SELECT fbdata.userid as id, CONCAT(firstname,' ',lastname) as name, fbname from fbdata join fbfriend on fbdata.userid = fbfriend.friendid where CONCAT(firstname,' ',lastname) like '%".$query."%' and fbfriend.userid= ".$uid." limit ".$limit;
  $result = mysql_query($sql,$con);
  if (!$result) {
    error_log(mysql_error()); 
    die('Error: ' . $sql . mysql_error());
  }
  $friendres = array();
  while($row = mysql_fetch_array($result)) {
     array_push($friendres, $row);
  }
  $finalres = json_encode( $friendres );
  echo $finalres;

  mysql_close($con);
?>
