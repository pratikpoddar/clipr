<?php
	
	require_once 'utils.php';

  	function printHTML( $heading, $value ) {

  		$str = null;
  		if ($value != "" && $value!= null) {
  			$str = "<span class='profilehead'>".$heading."</span><div>";
  			$str = $str.implode("<br/>", explode('---', $value));
  			$str = $str."</div><br>";
  		}
  		return $str;
  	}

  	if ($loggedUserID != 0) {
	  	$userinfo = getUserInfo($con, $loggedUserID);
	}
	else
	{
		die ('User Not Logged in');
	}
   
	$GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: viewing_profile, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
?>



<div class="row-fluid">
	<span class="span3 well" style="text-align:center">
	<?php 
		
		echo "<img style='max-width:200px' id='profilepic' src='http://graph.facebook.com/".$loggedUserID."/picture?type=large'></img>";
		echo "<h3>".$userinfo['firstname']." ".$userinfo['lastname']."</h3>";
        echo "<br/><a href=\"clips\" style=\"width:80%\" class=\"btn btn-large btn-primary ajax-link\" >";
        echo "Go to Clip Board";
        echo "</a>";
		echo "<br/><br/><div>";
        echo getUserStatString("Followers", getFollowers($con,$loggedUserID), 'follower');
        echo getUserStatString("Following", getFollowing($con,$loggedUserID), 'following');
        echo getUserStatString("Clips", getClipPoints($con,$loggedUserID), 'clip');
        echo getUserStatString("Product Adds", getProductsAdded($con,$loggedUserID), 'add');
		echo getUserStatString("Rewards", getTotalRewards($con,$userID), 'rewards');
        echo "</div>";
		echo "<div id='userinfo' style='display:none'></div>";
	?>
	  	
	</span>

	<span class="span5 well" style='font-size:100%'>
	<?php 
		echo printHTML("Name: ", $userinfo['firstname']." ".$userinfo['lastname']);
		echo printHTML("Email: ", $userinfo['email']);
		echo printHTML("Birthday: ", $userinfo['birthday']);
		echo printHTML("Hometown: ", $userinfo['hometown']);
		echo printHTML("Location: ", $userinfo['location']);
		echo printHTML("Gender: ", ucfirst(strtolower($userinfo['gender'])));
		echo printHTML("Education: ", $userinfo['education']);
		echo printHTML("Work: ", $userinfo['work']);
	?>
	</span>
	

	<span style="display:none" id = "hiddenuserid"><?php echo $loggedUserID; ?></span>
	<span class="span4">
	<div class="well">
		<span class="profilehead">Communication from Clipr:</span><br/><br/>
		<label class="checkbox">
			<input id="emailgift" type="checkbox">&nbsp;&nbsp;Reminder and gift recommendations for friends
		</label>
		<label class="checkbox">
			<input id="emaildeal" type="checkbox">&nbsp;&nbsp;Personalised exclusive deals from clipr partners (once a month)
		</label>
		<label class="checkbox">
			<input id="emailbulk" type="checkbox">&nbsp;&nbsp;New products and deals from clipr partners (once a week)
		</label>
		<label class="checkbox">
			<input id="fbactivitypost" type="checkbox">&nbsp;&nbsp;Posting on FB as Clipr activity. No spam! Just activity stream
		</label>
	</div>
			
	<div class="well">
		<span class="profilehead">Interests</span><a href='interests' class='ajax-link' style='font-size:95%;float:right'>Edit</a><br/><br/>
		
		<?php 		
			$interests = getInterests($con, $loggedUserID, $loggedUserID);
			echo "<b>(".count($interests).") Interests Submitted</b><br/>";
			foreach ($interests as $interest) {
				echo ucwords(str_replace("_", " ", $interest))."<br/>";
			}
		?>		
	</div>

	</span>

</div>

<script type="text/javascript">


	function changeEmailPrefs (col) {

        $.ajax({
          type: "GET",
          url: "changeEmailPrefs",
          data: { col: col, userId: <?php echo $loggedUserID ?> }
        }).done(function( msg ) {
	        $.gritter.add({
              title: 'Clipr Communication Preferences',
              text: msg,
              image: 'img/clipr.png',
              sticky: false,
              time: ''
        	});
        });
	}


	$(document).ready(function() {
		<?php 

			$sql = "SELECT * from emailCommunication where userid=$loggedUserID";
			$result = mysql_query($sql,$con);
			if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};			
			$emailCommunication = mysql_fetch_array($result);

			if ($emailCommunication['emailgift'] == 1) 
				echo "$('#emailgift').prop('checked', true);";
			if ($emailCommunication['emaildeal'] == 1) 
				echo "$('#emaildeal').prop('checked', true);";
			if ($emailCommunication['emailbulk'] == 1) 
				echo "$('#emailbulk').prop('checked', true);";
			if ($emailCommunication['fbactivitypost'] == 1) 
				echo "$('#fbactivitypost').prop('checked', true);";
			
			echo "$('#emailgift').click(function() {changeEmailPrefs(\"emailgift\")});";
			echo "$('#emaildeal').click(function() {changeEmailPrefs(\"emaildeal\")});";
			echo "$('#emailbulk').click(function() {changeEmailPrefs(\"emailbulk\")});";
			echo "$('#fbactivitypost').click(function() {changeEmailPrefs(\"fbactivitypost\")});";

		?>

	});
</script>

<style type="text/css">

.profilehead {
	font-weight: bold;
	color: #002640;

}

</style>