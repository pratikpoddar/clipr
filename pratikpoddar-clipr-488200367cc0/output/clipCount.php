<?php

  	$con = mysql_connect("localhost", "root", "12345678");
  	if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
	$dbconfig = parse_ini_file ( "../../db.ini");
	$dbname = $dbconfig['db_name'];
	mysql_select_db($dbname, $con);

	$sql = "SELECT count(productid) as num from clipsTable where TIMESTAMPDIFF(SECOND, timestamp, CURRENT_TIMESTAMP())<".$_GET['loadtime'];
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};     
    $row = mysql_fetch_array($result);

    echo $row['num'];
	mysql_close($con);
?>
