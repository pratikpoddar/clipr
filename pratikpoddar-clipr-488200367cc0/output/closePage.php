<?php
		require_once '../output/utils.php';

		if (isset($_GET['to']) && isset($_GET['request'])) {
			foreach($_GET['to'] as $friendid)
				sendInvite($con, getLoggedUserId(), $friendid, $_GET['request']);	
		}

		$logMessage = "";
		if (isset($_GET['type']))
		{
			$logMessage = $logMessage."type: ".$_GET['type'].", ";
		}

		if (isset($_GET['closelink'])) {
			$logMessage = $logMessage."link: ".$_GET['closelink'];
		}

		$GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: closePage, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId().", ".$logMessage); 
?>

<html>
	<head>
		<script type="text/javascript">
			function ready() {
				window.close();
				self.close();
			}
		</script>
	</head>
	<body onload="ready();">
	</body>
</html>