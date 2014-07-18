<?php
  require_once 'setGlobalVars.php';
  require_once 'outpututils.php';
  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: process_url, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId());
?>