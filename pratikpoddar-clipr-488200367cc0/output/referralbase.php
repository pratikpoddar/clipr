<div class="hero-unit">

  <div class="row-fluid" align="center">
      <h3>Clipr - Refer 'n Win Program </h3>
  </div>
  <br/>
  <div class="row-fluid">
    <div class="span6" align="center">
        <div style="font-size:80px;color:#151515"><i class="icon-link"></i></div><br/><br/>
        <p>
          <b><span style="font-size:120%">Refer</span></b><br/>
          Refer Clipr.in to 10 People<br/>( Share Link: <span style="color:#002c40;"><u><i>http://clipr.in/?ref=<?php echo $userID ?></i></u></span> )<br/><br/>
          <span id = "postReferral" class="btn btn-primary" onClick="postWithDialog('Referring Clipr.in - Because Shopping is fun with friends', 'http://clipr.in/?ref=<?php echo $userID ?>','http://clipr.in/output/img/fbogtag.png', 'Clipr - Because Shopping is fun with Friends', 'Clipr.in is a personalized magazine of awesome products for window shopping and gift recommendations.')">Refer on Facebook</span>
        </p>
    </div>
    <div class="span6" align="center">
      <div style="font-size:80px;color:#151515"><i class="icon-trophy"></i></div><br/><br/>
      <p>
        <b><span style="font-size:120%">Win</span></b><br/>
        Get a free <a href="cliprtransfer?link=http://www.bewakoof.com" target='_blank'>Bewakoof</a> Designer T-Shirt worth Rs. 500
      </p>
    </div>
  </div>

  <br/>
  <hr style="border-color:#222"/>
  <br/>


  <?php

    $referredIds = getReferredIds($con, $userID);
    $countrefs = count($referredIds);
  ?>

  <div class="row-fluid" align="center">
  <?php
    if ($countrefs > 0) {
      echo "<b>People referred by you (".$countrefs."):</b>";
  
    }
  ?>
  <br/>
  </div><br/>

  <?php
    $count = 0;
    echo "<div class='row-fluid'>";
    foreach ($referredIds as $referredId) {
        if ($count % 2 == 0 && $count > 0)
        {
          echo "</div><div class='row-fluid'>";
        }

        echo "<span class='span6'>";
        echo "<p><a href='clips/".$referredId."'>";
        $referredinfo = getUserInfo($con, $referredId);
        echo "<img src='http://graph.facebook.com/".$referredId."/picture?type=square' style='padding-right:30px'>";
        echo $referredinfo['firstname']." ".$referredinfo['lastname'];
        echo "</a></p>";
        echo "</span>";
        $count++;  
    }
    echo "</div>";
    echo "<br/>";
    echo "<div class='row-fluid' style='padding:10px;text-align:center;font-size:90%'>";
    
    if ($countrefs == 0)
    {
      echo "Start referring friends to win free T-shirt. Share the link <span style='color:#002c40'><u><i>http://clipr.in/?ref=".$userID."</i></u></span> to get your friends on board";
    }

    else if ($countrefs < 10)
    {
      echo 10-$countrefs." more to win free T-shirt. Share the link <span style='color:#002c40'><u><i>http://clipr.in/?ref=".$userID."</i></u></span> to get your friends on board";
    }

    else
    {
      echo "You are a winner! You get a free T-shirt! Please wait for our congratulatory email if you have not recieved one.";
    }
    echo "</div>";

  ?>
  
</div>
