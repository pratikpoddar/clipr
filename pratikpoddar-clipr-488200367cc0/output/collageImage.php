<?php
	require_once "../output/utils.php";

	echo "<div class='row-fluid'>";

	function getCollages($con, $uid){
		$retarr = array();

		$sql = "SELECT * from collages where userid = $uid";
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
		while($row = mysql_fetch_array($result))
		{
			array_push($retarr, $row);
		}
		return $retarr;
	}

	$collageImages = getCollages($con, $userID);

	foreach ($collageImages as $collageImage) {
		echo "<span class='span6'><img class = 'collageDisplayImage' src='../collageImages/".$collageImage['id'].".png'></span>";
	}
	echo "</div>";
?>