<?php  

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);
  mysql_query("set names 'utf8'");

  function getFbUserName($id){
    return (json_decode(file_get_contents('http://graph.facebook.com/'.$id))->username);
  }

  function insertFbUserName($con, $uid, $uname){
    $sql = "UPDATE fbdata set fbname = '$uname' where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
  }

  function addAllFbNames($con){
    $sql = "SELECT userid from fbdata where fbname is null or fbname = ''";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    while($row = mysql_fetch_array($result)) {
      $uid = $row['userid'];
      $uname = getFbUserName($uid);
      insertFbUserName($con, $uid, $uname);
    }
  }

  addAllFbNames($con);

?>