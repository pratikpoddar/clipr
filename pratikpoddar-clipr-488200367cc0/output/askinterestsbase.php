<?php

  require_once 'utils.php';
  require 'img/interestsImage.php';
?>

<style type="text/css">

.interest-btn {
  margin: 7px;
  width: 15%;
  min-width: 130px;
}

.interest-btn.btn-primary svg {
  fill: #eff6ff;
}

.interest-btn.btn-default svg {
  fill: #000;
}

</style>

<script type="text/javascript">

function submitInterests() {

  interestsList = $(".btn-primary.interest-btn");
  interests = [];
  $.each(interestsList, function(key, value) { 
    interests.push(parseInt($(value).val()))
  });

  console.log("submitting " + interests.length + " interests");

  if (interests.length < 5)
  {
    alert("Please select at least 5 interests");
    return;
  }

  $(".interest-btn").addClass('disabled');
  $.ajax({
    type: "GET",
    url: "submitinterests",
    data: { userId:<?php echo $loggedUserID ?>, interests: interests  }
  }).done(function( msg ) {
    <?php 
      if(isset($_GET['origin']))
        echo "$(location).attr('href','".$_GET['origin']."');";
      else 
        echo "getTrending();";
    ?>
  });
  mixpanel.track("submit interests");
  _gaq.push(["_trackEvent", "Auto-Action", "submit-interests"]);
}

</script>
<span align="center">
 
  <h3>Please select a few interests for a better, more personalized experience at Clipr!</h3>
  <div class="well" align="center">          
    <div class="row-fluid" align="center">
      <?php 
      $sql = "SELECT * FROM allTags order by name";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};   
      while ($allTag = mysql_fetch_row($result)) {
        $image = $representativeImage[$allTag[1]];
        echo "<button class='btn interest-btn btn-default' id=interestid".$allTag[0]." value=".$allTag[0]." onClick='if(! $(this).hasClass(\"disabled\") ) $(this).toggleClass(\"btn-primary btn-default\")'><div>".$image."</div>".ucwords(str_replace("_", " ", $allTag[1]))."</button>";
      }
      ?>
    </div>
    <button class="btn btn-large btn-primary" data-loading-text="Submitting..." onClick="$(this).button('loading');javascript:submitInterests()">Submit</button>
  </div>

<!-- toggleInterestButton -->
</span>

<script type="text/javascript">

$(document).ready(function() {
<?php
  
      $sql = "SELECT interest from interestsTable where userid=".$loggedUserID." and source = ".$loggedUserID;
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};
      while($interest = mysql_fetch_row($result)) {
        echo "$(\"#interestid".$interest[0]."\").toggleClass(\"btn-primary btn-default\");";
      }

?>
});
</script>
