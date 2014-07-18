<?php
	require_once "utils.php";
	$sql = "SELECT fbstreampost from emailCommunication where userid=$loggedUserID and fbstreampost >= 0";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    if($loggedUserID == 0)
    	echo json_encode(false);
    else
	    echo json_encode(!empty($row));
?>
