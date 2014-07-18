#!/usr/bin/php
<?php

require '../lib/facebook-php-sdk/src/facebook.php';
Facebook::$CURL_OPTS[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
require_once( "../lib/KLogger.php");

$log = new KLogger('../../logFiles/', KLogger::INFO);
$con = mysql_connect("localhost", "root", "12345678");

if (!$con) { error_log(mysql_error()); die('Could not connect: ' . mysql_error());};
 
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
mysql_query("INSERT INTO likesUniverse (likes, category) VALUES ('null', 'null')",$con); 
mysql_query("INSERT INTO fblike (userid, likeid) VALUES ('null', 'null')",$con); 

if(count($argv) >= 2){
	$sql = "SELECT userid, accessToken FROM  fbdata where userid='".$argv[1]."'";
}
else
	$sql = "SELECT distinct ff.userid, fd.accessToken from fbfriend as ff join fbdata as fd on ff.userid = fd.userid where ff.friendid not in (select userid from fbdata) and ff.userid != 0";
echo $sql;
$result = mysql_query($sql,$con);
if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());}

while ($userrow = mysql_fetch_array($result))
{
	$accessToken = $userrow['accessToken'];
	$facebook = new Facebook(array(
	  'appId'  => '143944345745133',
	  'secret' => 'ea707d171512a76fce376cdcef53cd62',
	  'cookie' => true,  
	));
	echo $accessToken;
	$facebook->setAccessToken($accessToken);
	// Getting User ID
	$userid = $facebook->getUser();
	echo $userid;

	$GLOBALS['log']->logInfo("--------- Running Facebook Batch Data Extractor for : ".$userid); 
	echo "getting friends of userid $userid \n";
	if ($userid) {
	  try {
		// Proceed knowing you have a logged in user who's authenticated.
		
		// Get List of Friends
		$sql = "SELECT friendid from fbfriend where userid=$userid and friendid not in (select distinct userid from fbdata)";
		$result2 = mysql_query($sql, $con);
		if (!$result2) { error_log(mysql_error()); die('Error: ' . mysql_error());}
		
		while ($friends_row = mysql_fetch_array($result2))
		{
				$value = $friends_row['friendid'];
				$friendid = sanitize($value);
				// For each friend, get Profile
				try {
					$profile = $facebook->api('/'.$friendid);
				} catch (Exception $e) {
					echo $e->getType()." ".$e->getMessage();
				}

				$GLOBALS['log']->logInfo("Getting fbdata for ".$friendid." who is a friend of ".$userid); 
				
				if (!isset($profile['id'])) $profile['id']="";
				if (!isset($profile['first_name'])) $profile['first_name']="";
				if (!isset($profile['last_name'])) $profile['last_name']="";	
				if (!isset($profile['birthday'])) $profile['birthday']="";
				if (!isset($profile['hometown'])) $profile['hometown']['name']="";
				if (!isset($profile['location'])) $profile['location']['name']="";	
				if (!isset($profile['username'])) $profile['username']="";
				if (!isset($profile['gender'])) $profile['gender']="";
				if (!isset($profile['education'])) $profile['education']=NULL;
				if (!isset($profile['work'])) $profile['work']=NULL;
		
				$friendid = sanitize($profile['id']);
				$firstname = sanitize($profile['first_name']);
				$lastname = sanitize($profile['last_name']);
				$birthday = sanitize($profile['birthday']);
				$hometown = sanitize($profile['hometown']['name']);
				$location = sanitize($profile['location']['name']);
				$gender = sanitize($profile['gender']);
				$username = sanitize($profile['username']);
				$education = getSanitizedEducation($profile['education']);
				$work = getSanitizedWork($profile['work']);

				$sql="INSERT INTO fbdata (userid, firstname, lastname, birthday, hometown, location, gender, education, work, fbname) 
					VALUES( '$friendid', '$firstname', '$lastname', '$birthday', '$hometown', '$location', '$gender','$education','$work','$username' ) 
						ON DUPLICATE KEY UPDATE 
							firstname = Values(firstname), 
							lastname = Values(lastname), 
							birthday = Values(birthday), 
							hometown = Values(hometown), 
							location = Values(location), 
							gender = Values(gender), 
							education = Values(education), 
							work = Values(work),  
							fbname = Values(fbname)
				";

				if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());}

				$listVal = explode('---', $education);
				foreach ($listVal as $val) {	if ($val != "") { insertIntoLikesUniverseLike ($con, $friendid, $val, 'fbeducation', 2); } }
				$listVal = explode('---', $work);
				foreach ($listVal as $val) { if ($val != "") { insertIntoLikesUniverseLike ($con, $friendid, $val, 'fbwork', 3); } }
		}

		// get users likes	
		$my_likes = $facebook->api('/'.$userid.'/likes');

		$GLOBALS['log']->logInfo("Getting fblike for ".$userid.""); 
				
		if (!empty($my_likes)) {
			foreach ($my_likes['data'] as $key2 => $value2)
			{
				$likename = sanitize($value2['name']);
				$category = sanitize($value2['category']);
				$source = 1;
				insertIntoLikesUniverseLike ($con, $userid, $likename, $category, $source);
			}
		}
		
		$sql = "UPDATE accessTokenTable SET extractionLeft=-3 WHERE accessToken=\"".$accessToken."\"";
		if (!mysql_query($sql,$con)) { error_log(mysql_error()); die('Error: ' . mysql_error());}	

	  } catch (	Exception $e) {
		error_log($e);
	  }
	}
	else {
		echo "expired accesstoken for user:  ".$userrow['userid']." \n";
	}
}
$GLOBALS['log']->logInfo("--------- Completed Facebook Batch Data Extractor for : ".$userid); 

mysql_close($con);

?>
