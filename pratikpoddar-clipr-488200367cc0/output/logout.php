<?php

	setcookie('loggedUserId', '', time()-36000, '/', 'clipr.in');
	setcookie('sessionid', '', time()-36000, '/', 'clipr.in');
	setcookie('loggedUserName', '', time()-36000, '/', 'clipr.in');
	require 'header.php';
	require 'outputheader.php';
	require 'headerjs.php';
	$facebook->destroySession();
?>

<div class="row-fluid">
  <h3 style="text-align:center">Successfully Logged out!</h3>
</div>

<script>

$(document).ready(function() { document.location.href="home"; })

</script>

<?php

  require 'outputfooter.php';
  require 'footer.php';
?>
