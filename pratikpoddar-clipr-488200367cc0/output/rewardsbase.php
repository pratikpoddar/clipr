<?php

  require_once 'utils.php';

  $totalPoints = 0;

  function resultToRewardsArray($con, $addedProducts, $userid) {
    $CLIP_ON_ADD_POINTS = 2;
    $ADD_POINTS = 10;
    $CLIP_POINTS = 1;
    $INVITE_POINTS = 5;

    $rewardsArr = array();
    $points = 0;
// 770284783
    while($act = mysql_fetch_array($addedProducts)) {

      if($act['action'] == "add" || $act['action'] == "clip"){
        $prodinfo = getProductInfo($con, $act['object']);
        $numclips = (int)$act['num'];
      }

      if($act['action'] == "add"){

        $clippoints = $numclips*$CLIP_ON_ADD_POINTS;
        $points = $points + $ADD_POINTS + $clippoints;

        array_push($rewardsArr,
            "<div class='reward_class'><span class='pull-left'>You added 
            <a href='product/".$prodinfo['productid']."' class='ajax-link' >".$prodinfo['title']."</a></span><span class='pull-right reward_points'>+ ".$ADD_POINTS."</span><br/>"
        );
        if($act['num'] > 0)
        {
          if($act['num'] > 1)
            $annotation = "people";
          else if($act['num'] == 1)
            $annotation = "person";

          array_push($rewardsArr,
              "<span class='pull-left subpoint'>".$numclips." ".$annotation." clipped 
              <a href='product/".$prodinfo['productid']."' class='ajax-link' >it</a></span><span class='pull-right reward_points' style='padding-top:10px'> + ".$clippoints."</span><br/>"
          ); 
        }
        array_push($rewardsArr,"<br/></div>");
      }
      else if ($act['action'] == "clip")
      {
        $prodinfo = getProductInfo($con, $act['object']);
        $numclips = (int)$act['num'];

        $points = $points + $CLIP_POINTS;
        array_push($rewardsArr,
            "<div class='reward_class'><span class='pull-left'>You clipped 
            <a href='product/".$prodinfo['productid']."' class='ajax-link'>".$prodinfo['title']."</a></span><span class='pull-right reward_points'>+ ".$CLIP_POINTS."</span><br/><br/></div>"
        ); 
      }
      else if ($act['action'] == "invite")
      {
        $uinfo = getUserInfo($con, $act['object']);
        $points = $points + $INVITE_POINTS;
        array_push($rewardsArr,
            "<div class='reward_class'><span class='pull-left'>You invited 
            <a href='clips/".$act['object']."' class='ajax-link'>".$uinfo['firstname']." ".$uinfo['lastname']."</a></span><span class='pull-right reward_points'>+ ".$INVITE_POINTS."</span><br/><br/></div>"
        ); 
      }
    }

    if($points != 0)
      array_push($rewardsArr,
          "<hr style='border-color:#222'><div id='total_rewards'><span class='pull-left'> Your total rewards are </span><span class='pull-right' style='font-size:130%'>".$points." points</span><br/><br/></div>"
      );

    $GLOBALS['totalPoints'] =   $points;
    return $rewardsArr;
  }

  function getProductsAddedForRewards($con, $userid) {
    $sql = "SELECT productid as object, count(distinct productid) as num, 'clip' as action, timestamp as time from clipsTable 
              where   userid =".$userid."  group by productid 
            UNION 
            (
              SELECT ap.productid as product, count(distinct ct.userid) as clips, 'add' as action, time from 
              addedProduct as ap left outer join clipsTable as ct 
              on ct.productid = ap.productid and ct.userid != ap.userid 
              where repeatadd = 0 and ap.userid =".$userid." group by ap.productid 
            ) 
            UNION 
            (
              SELECT friendid as object, 1 as num, 'invite' as action, time from                
              inviteFriend where userid = ".$userid." group by friendid
            ) 
            order by time DESC";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    return $result;
  }

  function getRewards ($con, $userid) {
      $addedProducts = getProductsAddedForRewards($con, $userid);
      $clips = getClipPoints($con, $userid);
      $rewardsArr = array();
      $rewardsArr = resultToRewardsArray($con, $addedProducts, $userid);
      return $rewardsArr;
    }

    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: view_rewards, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
?>

<style type="text/css">

.reward_class {
  padding: 8px;
  margin: 4px;
  background-color: #eee;
}

#total_rewards {
  padding: 20px 8px;
  margin: 4px ;
  font-size: 140%;
  font-weight: bold;

}

.reward_points {
  font-size:140%;
  font-weight:bold;
}

.subpoint {
  padding-left:40px;
  padding-top:10px;
}

</style>

<div class="row-fluid">
  <?php 
    if ($loggedUserID == 0)
      echo '<h3 style="text-align:center"><a href="javascript:void(0);" class="loginlink" id="rewardsLoginLink">Login</a> to earn reward points!</h3>';
    else {
      $rewardsArr = getRewards($con, $loggedUserID); 
      if($totalPoints != 0)
        echo '<h3 style="text-align:center">Your rewards summary for '.$totalPoints.' points!</h3>';
      foreach ($rewardsArr as $reward)
        echo $reward;
      if($totalPoints == 0)
        echo "<h5 style='text-align:center'>You have no rewards! Start now by <a href='add' class='ajax-link'>adding products!</a></h5>";
    }
  ?>
</div>
