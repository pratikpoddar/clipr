<?php

	function fetchCollageByUser( $con, $uid ){
		$sql =  "SELECT * from collages where userid = $uid";
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

		$collages = array();
		while($row = mysql_fetch_array($result)) 
			array_push($collages, $row);

		return $collages;
	}

	function fetchCollageById( $con, $cid/* not of Daya fame :P*/ ){
		$sql =  "SELECT * from collages where id = $cid";
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
		$row = mysql_fetch_array($result);
		return $row;
	}

	function getImageMagickCommand($collage, $name, $temp=false){
		$images = array();
		foreach ($collage as $collageElem) {
			$path = explode( "prodImage/", $collageElem['src'] );
			$path = $path[1];
			$left = filter_var($collageElem['leftOffset'], FILTER_SANITIZE_NUMBER_INT);
			$top = filter_var($collageElem['topOffset'], FILTER_SANITIZE_NUMBER_INT);
			$height = $collageElem['height'];
			$width = $collageElem['aspectRatio'] * $collageElem['height'];
			$angle = $collageElem['rotation'];
			$zindex = $collageElem['zindex'];
			$drawString = " -draw \" translate ".($width/2+$left)." , ".($height/2+$top)." rotate ".$angle." image over  -".($width/2).", -".($height/2)."  ".$width.",".$height."  '../prodImage/".$path."' \"";
			$images[$zindex] = $drawString;
		}

		ksort($images);
		$command = "convert -size 500x500 xc:white ";
		foreach ($images as $key => $value) 
			$command = $command.$value;
		if($temp)
			$imgpath = " ../tempCollage/".$name.".png";
		else
			$imgpath = " ../collageImages/".$name.".png";
		$command = $command.$imgpath;
		return $command;
	}
?>