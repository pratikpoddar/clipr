<?php

  require_once 'utils.php';

  function getContestEntries($con){
    $sql = "SELECT * from contestEntry group by userid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $entries = array();
    while($row = mysql_fetch_array($result))
    {
      array_push($entries, $row);
    }
    return $entries;
  }

  function hasEntry($con, $uid){
    if($uid == 0)
      return false;
    $sql = "SELECT * from contestEntry where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    if(empty($row))
      return false;
    return true;
  }

  function getContestEntryHtml($con, $entry){
    $prod = getProductInfo($con, $entry['productid']);
    $clipbtn = getClipButton($con, $GLOBALS['loggedUserID'], $prod['productid'], "product", "right","cliprproduct",getAbsoluteUrl("contest"));
    $user = getUserInfo($con, $entry['userid']);
    $numclips = getClippersCount($con, $entry['productid']);
    $listofimages = explode('$$$', $prod['image']);
    echo '
    <div class="well">
      <div class="row-fluid" style="">
        <span class="span4">
          <a class="ajax-link" href="product/'.$entry['productid'].'">
            <img src="'.getSmallImage($listofimages[0]).'">
          </a>
        </span>
        <span class="span8">
          <table style="width: 100%;">
            <tbody>
              <tr>
                <td width="65%">
                  <h3>
                    <a class="ajax-link" href="contest/'.$user['fbname'].'">'.$prod['title'].'</a>
                  </h3>
                </td>
                <td>
                  '.$clipbtn.'
                </td>
              </tr>
              <tr>
                <td>
                  <h5>
                    by <a class="ajax-link" href="contest/'.$user['fbname'].'">'.$user['firstname'].' '.$user['lastname'].'</a>
                  </h5>
                </td>
              </tr>
              <tr>
                <td width="65%">
                  <span class="tagline" style="padding-right:10px">
                    '.htmlentities($entry['tagline']).'
                  </span>
                </td>
                <td>
                  <span>
                    <span id="contestclipper'.$prod['productid'].'">'.getClippersStringWithoutTable($con, $prod).'
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </span>
      </div>
    </div>';
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
<?php 
  if(hasEntry($con, $loggedUserID) && false)
    echo "#makeawish {
      display: none;
    }";
?>
</style>
<?php

?>
<div id = "makeawish" class="well">
  <div class="row-fluid">
    <div class="span8">
      <div align="left" style="margin-left: 5px;">
        <span style="font-size:150%">Clipr Make a wish contest </span><br/>
        <span style="font-size:120%">Simply add a product you like, gather the most number of clips, and take it away for free* </span>
      </div>
    </div>
    <div class="span2">
      <a style="margin:2px" class="btn pull-right ajax-link" href="contest/details" >Details</a>
    </div>
    <div class="span2">
      <a style="margin:2px" class="btn pull-right ajax-link" href="add/contest" >Enter contest</a>
    </div>
  </div>
</div>

<div class="row-fluid">
  <?php 
    $contestEntries = getContestEntries($con); 
    echo '<h3 style="text-align:center">Pick your favourite contest entry and help them win!</h3>';
    foreach ($contestEntries as $contestEntry)
      echo getContestEntryHtml($con, $contestEntry);
  ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
  <?php 
    foreach ($contestEntries as $contestEntry)
      if(getClipUnclip($con, $contestEntry['productid'], $loggedUserID) == "Clip" ) 
        echo "changeToUnclipped('product',".$contestEntry['productid'].");";
      else if(getClipUnclip($con, $contestEntry['productid'], $loggedUserID) == "Unclip" ) 
        echo "changeToClipped('product',".$contestEntry['productid'].");";
  ?>
});
</script>