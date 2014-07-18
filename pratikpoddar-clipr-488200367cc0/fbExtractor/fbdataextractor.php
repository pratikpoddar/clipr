#!/usr/bin/php
<?php

require '../lib/facebook-php-sdk/src/facebook.php';
require_once( "../lib/KLogger.php");
require_once '../lib/mailchimp/MCAPI.class.php';
require_once '../lib/mailchimp/config.inc.php'; //contains apikey
Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;

$log = new KLogger('../../logFiles/', KLogger::INFO);

// TODO: Add error handling - and time out
// TODO: Use MySQLi instead of MySQL and prepared statements instead of sanitize
$con = mysql_connect("localhost", "root", "12345678");

if 	(!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
 
function sanitize ($input) {
	return mysql_real_escape_string(stripslashes($input));
}

// Source: 1 for fblike, 2 for fbeducation, 3 for fbwork
function insertIntoLikesUniverseLike ($con, $userid, $likename, $category, $source) {
	$sql = "INSERT IGNORE INTO likesUniverse (likes, category, source) VALUES ('$likename','$category','$source')";
	if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};

	$sql = "SELECT id from likesUniverse where likes = '$likename' and category='$category' and source='$source'";
	$result = mysql_query($sql,$con);
	if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};			

	$row = mysql_fetch_array($result);
	$likeid = $row['id'];

	$sql="INSERT IGNORE INTO fblike (userid, likeid) VALUES ('$userid','$likeid')";
	if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());};
}

function getSanitizedEducation ($educationProfile) {
	$education = "";
	if ($educationProfile != NULL) {
		foreach ($educationProfile as $key2 => $value2) {
		 	if (isset($value2['school']))
			{
				if (isset($value2['school']['name'])) $education = $education.$value2['school']['name'].'---';
			}
			if (isset($value2['concentration']))
			{
				foreach ($value2['concentration'] as $key3 => $value3) $education = $education.$value3['name'].'---';
			}
		}
	}
	return sanitize($education);
}

function getSanitizedWork ($workProfile) {
	$work = "";
	if ($workProfile != NULL) {
		foreach ($workProfile as $key => $value) {
			if (isset($value['employer']))
			{
				if (isset($value['employer']['name'])) $work = $work.$value['employer']['name'].'---';
			}
		}
	}
	return sanitize($work);
}


$dbconfig = parse_ini_file ( "../../db.ini");
$dbname = $dbconfig['db_name'];
mysql_select_db($dbname, $con);

// Table LikesUniverse needs to have a size of atleast one so that unique updating can be done in the code
// mysql_query("INSERT INTO likesUniverse (likes, category) VALUES ('null', 'null')",$con); 
// mysql_query("INSERT INTO fblike (userid, likeid) VALUES ('null', 'null')",$con); 

$facebook = new Facebook(array(
  'appId'  => '143944345745133',
  'secret' => 'ea707d171512a76fce376cdcef53cd62',
  // 'cookie' => true,
));

$accessToken = $argv[1];

$facebook->setAccessToken($accessToken);

// Getting User ID
$user = $facebook->getUser();

$userid = $user;

$GLOBALS['log']->logInfo("--------- Running Facebook Data Extractor for : ".$userid); 
// Update info if received a new accessToken.
	if ($user) {
		echo $user." found";
	  try {
		// Proceed knowing you have a logged in user who's authenticated.
		echo "starting";
	  	$profile = $facebook->api('/'.$userid);
	  	var_dump($profile);
		if (!isset($profile['id'])) $profile['id']="";
		if (!isset($profile['email'])) $profile['email']="";
		if (!isset($profile['first_name'])) $profile['first_name']="";
		if (!isset($profile['last_name'])) $profile['last_name']="";	
		if (!isset($profile['birthday'])) $profile['birthday']="";
		if (!isset($profile['hometown'])) $profile['hometown']['name']="";
		if (!isset($profile['location'])) $profile['location']['name']="";	
		if (!isset($profile['username'])) $profile['username']=$userid;
		if (!isset($profile['gender'])) $profile['gender']="";
		if (!isset($profile['education'])) $profile['education']=NULL;
		if (!isset($profile['work'])) $profile['work']=NULL;

		$userid =  sanitize($profile['id']);
		$email =  sanitize($profile['email']);
		$firstname = sanitize($profile['first_name']);
		$lastname = sanitize($profile['last_name']);
		$birthday = sanitize($profile['birthday']);
		$hometown = sanitize($profile['hometown']['name']);
		$location = sanitize($profile['location']['name']);
		$gender = sanitize($profile['gender']);
		$username = sanitize($profile['username']);
		$education = getSanitizedEducation($profile['education']);
		$work = getSanitizedWork($profile['work']);

		$oldEmailSql = "SELECT * from fbdata where email = '".$email."' and userid = ".$userid;
		$oldEmailResult = mysql_query($oldEmailSql,$con); 
		if (!$oldEmailResult) { error_log(mysql_error()); die('Error: ' . mysql_error());};
		$res = mysql_fetch_array($oldEmailResult);

		if (empty($res)) {
			$api = new MCAPI($mcapikey);
			$merge_vars = array('FNAME'=>$firstname, 'LNAME'=>$lastname);
			$retval = $api->listSubscribe( $mclistId, $email, $merge_vars );
			if ($api->errorCode){
				$GLOBALS['log']->logInfo("action: subscribe_fail, code:".$api->errorCode.", message: ".$api->errorMessage.", userid: ".$userid); 
			} else {
				$GLOBALS['log']->logInfo("action: subscribe_success, userid: ".$userid); 
			}
		}
		$is_active=1;
		$sql="INSERT INTO fbdata (userid, email, accessToken, firstname, lastname, birthday, hometown, location, gender, education, work, fbname, is_active) 
			VALUES( '$userid', '$email', '$accessToken', '$firstname', '$lastname', '$birthday', '$hometown', '$location', '$gender','$education','$work','$username','$is_active' ) 
				ON DUPLICATE KEY UPDATE 
					email = Values(email), 
					accessToken = Values(accessToken), 
					firstname = Values(firstname), 
					lastname = Values(lastname), 
					birthday = Values(birthday), 
					hometown = Values(hometown), 
					location = Values(location), 
					gender = Values(gender), 
					education = Values(education), 
					work = Values(work), 
					fbname = Values(fbname),
					is_active=Values(is_active)
			";

		if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());}

		$sql="INSERT IGNORE INTO emailCommunication (userid) VALUES('$userid')";
		if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());}		

		$GLOBALS['log']->logInfo("User Data added for : ".$userid); 

		// Get Likes of the Logged in User
		$my_likes = $facebook->api('/'.$userid.'/likes');
				
		if (!empty($my_likes)) {
			foreach ($my_likes['data'] as $key => $value)
			{
				$likename = sanitize($value['name']);
				$category = sanitize($value['category']);
				$source = 1;
				insertIntoLikesUniverseLike ($con, $userid, $likename, $category, $source);
			}
		}

		$listVal = explode('---', $education);
		foreach ($listVal as $val) {	if ($val != "") { insertIntoLikesUniverseLike ($con, $userid, $val, 'fbeducation', 2); } }
		$listVal = explode('---',$work);
		foreach ($listVal as $val) { if ($val != "") { insertIntoLikesUniverseLike ($con, $userid, $val, 'fbwork', 3); } }

		$GLOBALS['log']->logInfo("Education and Work Data added for : ".$userid); 

		// Get List of Friends
		$friends_list = $facebook->api('/'.$userid.'/friends');

		echo "inserting friends";
		foreach ($friends_list['data'] as $key => $value)
		{
			// For each friend, record friendship	
			$friendid = sanitize($value['id']);
			$sql="INSERT IGNORE INTO fbfriend (userid, friendid, follow) VALUES( '$userid','$friendid', 1 )";
			if ( !mysql_query($sql,$con) ) { error_log(mysql_error()); die('Error: ' . mysql_error()); };
		}

		echo "inserted friends";
		$GLOBALS['log']->logInfo("Friendship Data added for : ".$userid); 
		
	  } catch (	Exception $e) {
		error_log($e);
	  }
	
	  $sql = "UPDATE accessTokenTable SET extractionLeft=-1 WHERE accessToken=\"".$accessToken."\"";
	  if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());}		
	}
	$command = "php fbdataextractorBatch.php ".$userid." >  /dev/null &";
	exec($command);
	mysql_close($con);

?>


