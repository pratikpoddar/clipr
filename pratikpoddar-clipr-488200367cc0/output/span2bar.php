<?php 
  require_once 'prodlistutils.php';
  require_once 'utils.php';
?>

<!-- 
- Get variables expected by this file:
-   compulsary 
-     page
-   optional
-     pagination
-     userid 
-     tag
-     friendid
-->
<?php

if (($loggedUserID == 0) && isset($_GET['page']) && ($_GET['page']=="trending"))
{

?>

<div class="hero-unit container" style="text-align:center;color:black;padding:15px;border:white;background:transparent;box-shadow: none;position:relative;">
  <img id="banner-bg" src="../image/shoppingbanner.jpg?v=7">
  <div class="row-fluid">
      <h3>Clipr - Clip it. Tag it. Trend it!</h3>
  </div>
  <div class="row-fluid">
      <div class="span4">
       <div style="font-size: 36px;padding:10px;margin:auto">
          <i class="icon-search"></i>
          <div style="font-size:13px; padding:10px; text-align:center;"><h4>Discover</h4> products for yourself</div>
        </div>
      </div>
      <div class="span4">        
        <div style="font-size: 36px;padding:10px;margin:auto;">
          <i class="icon-gift"></i>
          <div style="font-size:13px; padding:10px; text-align:center;"><h4>Search</h4> gifts for friends and family</div>
        </div>
      </div>
      <div class="span4">        
        <div style="font-size: 36px;padding:10px;margin:auto;">
          <i class="icon-share"></i>
          <div style="font-size:13px; padding:10px; text-align:center;"><h4>Share</h4> products you like</div>
        </div>
      </div>
  </div>
  <hr style="border-color:#222;margin:10px 0px;"/>
  <div class="row-fluid">
      <h3>What is Clip?</h3>
      <div style="font-size: 36px;padding:10px;margin:auto"><i class="icon-paper-clip"></i> = <i class="icon-thumbs-up"></i> </div>
      You 'Clip' what you 'Like'.
  </div>
  
</div>


<?php

}

?>


<?php 

if ($loggedUserID != 0) {

  echo '<div class="row-fluid" style="text-align:center">
  <ul class="nav nav-tabs" style="display:inline-block;margin-bottom:0px">';

?>

<?php 
 
  // Sidebar Interest List

  $onlyonce = 1;
  if($_GET['page'] == "friendsuggestion")  {
    if(!isset($_GET['friendid']))
      die('friend not chosen');
    $userInterests = getInterests($con, $_GET['friendid'], $loggedUserID);
  }
  $allinterests = getAllInterests($con);
  foreach ($allinterests as $interest)
  {
    if ($onlyonce == 1) {
      echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Browse Interests<b class="caret"></b></a><ul class="dropdown-menu">';
      $onlyonce = 0;
    }
    if(in_array($interest, $userInterests))
      $interestStr = ucwords(str_replace("_", " ", $interest))." *";
    else
      $interestStr = ucwords(str_replace("_", " ", $interest));
    echo "<li class='top-list-elem'><a href='tag/".urlencode($interest)."' class='ajax-link'>".$interestStr."</a></li>";
  }

  if ($onlyonce == 0) { 
    if($_GET['page'] == "friendsuggestion")  {
      echo "</ul></li>"; 
    }
    else
    {
      echo "<a href='interests' class='ajax-link' style='text-decoration:none;font-size:80%;margin-right:10px;float:right;'>Edit</a></ul></li>"; 
    }
  }

?>

<?php

  // Sidebar Group List
  $onlyonce = 1;

  foreach ($globalListOfGroups as $group)
  {
    if ($onlyonce == 1) {
      echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Groups<b class="caret"></b></a><ul class="dropdown-menu">';
      $onlyonce = 0;
    }
    echo "<li class='top-list-elem'><a href='tag/".urlencode($group)."' class='ajax-link'>".ucwords(str_replace("_", " ", $group))."</a></li>";
  }
 
  if ($onlyonce == 0) { echo "</ul></li>"; }

?>

<?php
  
  // Sidebar Friend List
  $onlyonce = 1;

  foreach ($userfriends as $friend)
  {
    if ($onlyonce == 1) {
      echo '<li class="dropdown "><a class="dropdown-toggle" data-toggle="dropdown" href="#">Friends<b class="caret"></b></a><ul class="dropdown-menu">';
      $onlyonce = 0;
    }
    echo "<li class='top-list-elem'><a href='clips/".$friend['userid']."' class='ajax-link'>".$friend['name']."</a></li>";
  }

  if ($onlyonce == 0) {
    echo "</ul></li>";
  }

?>

<?php
  
  // Sidebar Trendsetters List
  $onlyonce = 1;
  foreach ($trendSetters as $trendSetter)
  {
    if ($onlyonce == 1) {
      echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Trendsetters<b class="caret"></b></a><ul class="dropdown-menu">';
      $onlyonce = 0;
    }
   echo "<li class='top-list-elem'><a href='clips/".$trendSetter['userid']."' class='ajax-link' >".$trendSetter['name']."</a></li>";
  }

  if ($onlyonce == 0) {
    echo "</ul></li>";
  }

?>

</ul>      
    
</div>

<?php

  } // The Header should come in only when logged in

?>

<?php
if ($_GET['page'] == "clips") {
  echo '<span style="display:none" id = "hiddenuserid">'.$userID.'</span>';
  echo '<div class="well">';
}
else {
  echo '<span style="display:none" id = "hiddenuserid">'.$userID.'</span>';
  echo '<div class="well" style="display:none">';
}
  echo '<div class="row-fluid">';
  echo '<div class="span3">';
  echo "<img id='profilepic' style='max-height:144px' src='http://graph.facebook.com/".$userID."/picture?type=large'></img>";
  echo '</div>';  
  echo '<div class="span3">';
  if ($loggedUserID != $userID) {
        echo "".getFollowButton($con, $loggedUserID, $userID);
  }

  echo '</div>';
  echo '<div class="span6">';
  echo '<div style="width:50%;margin:auto;">';
  echo getUserStatString("Followers", getFollowers($con,$userID), 'follower');
  echo getUserStatString("Following", getFollowing($con,$userID), 'following');
  echo getUserStatString("Clips", getClipPoints($con,$userID), 'clip');
  echo getUserStatString("Product Adds", getProductsAdded($con,$userID), 'add');
  echo getUserStatString("Rewards", getTotalRewards($con,$userID), 'rewards');
  echo "</div>";
  echo "</div>";
  echo '</div>';
  echo '</div>';


?>