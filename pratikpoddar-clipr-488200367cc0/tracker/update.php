<?php 
	$con = mysql_connect("localhost", "root", "12345678");
	if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());};
	$dbconfig = parse_ini_file ( "../../db.ini");
	$dbname = $dbconfig['db_name'];
	mysql_select_db($dbname, $con);
	mysql_query("set names 'utf8'");

	$referUrl =  $_GET['refURL'];
	$referrer = $_GET['cliprRefer'];
	$transactionId = $_GET['transactionId'];
	$state = $_GET['state'];
	$price = $_GET['price'];

	function addAffiliateAction($con, $referUrl, $referrer, $transactionId, $state, $price){
		$sql = sprintf(
			"INSERT into affiliateAction(url, referrer, transaction, state, price) VALUES('%s','%s','%s','%s','%s')", 
			mysql_real_escape_string($referUrl),
			mysql_real_escape_string($referrer),
			mysql_real_escape_string($transactionId),
			mysql_real_escape_string($state),
			mysql_real_escape_string($price)
		);
		$result = mysql_query( $sql, $con );
		if ( !$result ) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error()); };
	}


	if(isset($_SERVER['HTTP_REFERER'] ) ) 
		$referUrl = $_SERVER['HTTP_REFERER'] ;
	addAffiliateAction($con, $referUrl, $referrer, $transactionId, $state, $price);
	mysql_close($con);
?>