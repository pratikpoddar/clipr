<?php
	
	$con = mysql_connect("localhost", "root", "12345678");

	if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};

	$dbconfig = parse_ini_file ( "../../db.ini");
	$dbname = $dbconfig['db_name'];
	mysql_select_db($dbname, $con);

	$accessToken = $_POST['accessToken'];

	$sql="INSERT INTO accessTokenTable (accessToken) SELECT DISTINCT '$accessToken' FROM accessTokenTable where not exists (SELECT * from accessTokenTable where accessToken='$accessToken')";
	if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};

	exec("php ../fbExtractor/fbdataextractor.php ".$_POST['accessToken']);   

	if (isset($_COOKIE['loggedUserId']))
	{
		$sql = "SELECT interest from interestsTable where userid=".$_COOKIE['loggedUserId']." and source=".$_COOKIE['loggedUserId'];
		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};
		if (!($interest = mysql_fetch_row($result))) 
		{
			echo "../output/askinterests";
		}
		else
		{
			echo "../output/index";  
		}
	}
	else {
		echo "../output/index";
	}
?>
