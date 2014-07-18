<?php 
	require_once "../output/utils.php";
	require_once "../output/collageutils.php";

	$collage = json_decode($_GET['collage'],true);
	$collageName = $_GET['heading'];
	$collageDesc = $_GET['description'];
	function storeCollage($con, $uid, $collage, $heading,$description){
		$sql = sprintf(
			"INSERT into collages(userid, collage, heading, description) VALUES('%s','%s','%s','%s')",
			mysql_real_escape_string($uid),
			mysql_real_escape_string(json_encode($collage)),
			mysql_real_escape_string($heading),
			mysql_real_escape_string($description)
		);
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		$id =  mysql_insert_id();
		foreach ($collage as $collageElem) {
			$sql = sprintf(
				"INSERT into collageProducts(cid, productid) VALUES('%s','%s')",
				mysql_real_escape_string($id),
				mysql_real_escape_string($collageElem['pid'])
			);
			$result = mysql_query($sql,$con);
			if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		}
		return $id;
	}

	function existsRecord($con, $cid, $uid){
		$sql = "SELECT * from collages where id = $cid and userid = $uid";
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		$row = mysql_fetch_array($result);
		return !empty($row);
	}

	function updateCollage($con, $uid, $collage, $heading, $description, $cid){
		$sql = sprintf(
			"UPDATE collages set collage = '%s', heading = '%s', description ='%s' where id = '%s' and userid = '%s'",
			mysql_real_escape_string(json_encode($collage)),
			mysql_real_escape_string($heading),
			mysql_real_escape_string($description),
			mysql_real_escape_string($cid),
			mysql_real_escape_string($uid)
		);
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		if( mysql_affected_rows() > 0 || existsRecord($con, $cid, $uid))
			return $cid;
		else
			die("ERROR: updating non existent id");
	}

	if(!isset($_GET['cid']) || $_GET['cid']==0)
		$id = storeCollage($con, $loggedUserID, $collage, $collageName,$collageDesc);
	else
		$id = updateCollage($con, $loggedUserID, $collage, $collageName,$collageDesc,$_GET['cid']);

	$comm = getImageMagickCommand($collage, $id);//.' > /dev/null &';
	exec($comm);
	if(isset($_GET['redirect']))
		header( 'Location: collage/'.$id );
	else
		echo $id;
	require_once "sqlcloser.php";
?>
