<?php

require '../lib/facebook-php-sdk/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '143944345745133',
  'secret' => 'ea707d171512a76fce376cdcef53cd62',
  'cookie' => true,
));

$user = $facebook->getUser();

if ($user == 0) {
	die ('Login Problem');
}

if ($user != $_COOKIE['loggedUserId']) {
	die ('Login Problem');
}

echo "Success!";

?>