<?php
	require_once "utils.php";
	$stat = $_POST['status'];
	assert($stat == 0 || $stat == 1);
	$sql = "UPDATE emailCommunication set fbstreampost = $stat where userid=$loggedUserID";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
?>
