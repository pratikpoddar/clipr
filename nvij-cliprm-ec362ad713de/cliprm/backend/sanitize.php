<?php 
	require_once( "lib/htmlpurifier-4.4.0/library/HTMLPurifier.auto.php");

	$con = mysql_connect("localhost", "root", "12345678");
	if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());};
	mysql_select_db("cliprbeta", $con);
	mysql_query("set names 'utf8'");

	$sql = "SELECT description from productDetail where productid = ".$argv[1];
	$result = mysql_query($sql,$con);
	if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
	$row = mysql_fetch_array($result);

	$config = HTMLPurifier_Config::createDefault();
	$xml = $row['description'];
	$xml = str_replace('<a href="#">top</a>', '', $xml);
	$config->set( 'HTML.ForbiddenAttributes', array( '*@class', '*@id', '*@name' ) ) ;
	// if($ignoreTable)
	// 	$config->set( 'HTML.ForbiddenElements', array( 'a', 'script', 'embed', 'h1', 'h2', 'h3','table','tr','td','b','strong','i','u','img' ) );
	// else
	$config->set( 'HTML.ForbiddenElements', array( 'a', 'script', 'embed', 'h1', 'h2', 'h3' ,'img') );
	$config->set( 'AutoFormat.RemoveEmpty', true );
	// if($ignoreTable)
	// 	$config->set( 'CSS.ForbiddenProperties', array( 'font-family','font-size','font-weight','color','background-color','text-decoration','float'));
	// else
	$config->set( 'CSS.ForbiddenProperties', array( 'font-size', 'font-family','color','background-color','float'));
	$purifier = new HTMLPurifier($config);
	echo $purifier->purify($xml);
?>