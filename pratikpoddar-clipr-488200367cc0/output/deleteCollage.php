<?php 
	require_once "../output/utils.php";
	if(!isset($_GET['cid']) || $_GET['cid'] == 0)
		die("Collage Id absent or invalid");

	function deleteCollage($con, $cid, $uid){
		$sql = "DELETE from collages where id = $cid and userid = $uid";
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		if(mysql_affected_rows() == 0)
			return false;
		else
			return true;
	}

	echo deleteCollage($con, $_GET['cid'], $loggedUserID);
?>