<?php

  $now = time( );
  $then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", $now + 60*60*8);
  $expires = 60*60*8;

  header("Expires: $then");
  header("Pragma: public");
  header("Cache-Control: maxage=".$expires);

  $uid = $_GET['userid'];
  $query = $_GET['partial'];
// /^[a-zA-Z\d ,.]+$/
  if( !preg_match('/^[a-zA-Z\d ]+$/',$query) ) {
    $emptyres = json_encode(array('names' => array()));
    echo $emptyres;
    exit(1);
  }

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);

  $sql = "SELECT fbdata.userid, CONCAT(firstname,' ',lastname) as name, gender from fbdata join fbfriend on fbdata.userid = fbfriend.friendid and fbfriend.userid= ".$uid." and CONCAT(firstname,' ',lastname) like '%".$query."%'";
  $result = mysql_query($sql,$con);
  if (!$result) {
    error_log(mysql_error()); 
    die('Error: ' . $sql . mysql_error());
  }
  $friendres = array();
  while($row = mysql_fetch_array($result)) {
     array_push($friendres, $row);
  }
  $finalres = json_encode( array( 'names' => $friendres ) );
  echo $finalres;


  mysql_close($con);
?>
