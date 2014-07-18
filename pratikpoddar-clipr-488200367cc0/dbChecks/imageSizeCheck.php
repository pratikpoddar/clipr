<?php

function biggerImage($file1, $file2) {
	list($width1, $height1, $type, $attr) = getimagesize($file1);
	list($width2, $height2, $type, $attr) = getimagesize($file2);

	var_dump($width1, $width2, $height1, $height2);
	if (($width1 > $width2) && ($height1 > $height2))
		return true;
	else
		return false;
}

 var_dump(biggerImage($_GET['file1'], $_GET['file2']));

?>