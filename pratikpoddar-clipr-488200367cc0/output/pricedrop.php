<style type="text/css">
  .pricedropPrice{
    margin-top: 20px;
  }

  .clipdiv{
    margin-top: 2px;
  }

  .discountButton{
    width: 70%;
  }

  .discountHeading{
    padding: 0px;
    margin: 0px;
    line-height: 24.5px;
  }
</style>
<?php

  require_once 'utils.php';

  function getTopDrops($con, $hardcoded = true){
    if($hardcoded){
      $sql = "SELECT * from topDrops where productid in (25428, 25969, 23812, 25035, 25518, 25469, 25444, 25262, 25536, 25420, 25033, 25296, 25206, 25223, 24762, 25283, 24805) order by pricedrop desc";
    }
    else
      $sql = "SELECT * from topDrops where numdays < 30 order by pricedrop desc";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $drops = array();
    while($row = mysql_fetch_array($result))
      array_push($drops, $row);
    return $drops;
  }

  function getPriceDropHtml($con, $topprod){
    $prod = getProductInfo($con, $topprod['productid']);
    $clipbtn = getClipButton($con, $GLOBALS['loggedUserID'], $prod['productid'], "product", "right","discountButton",getAbsoluteUrl("contest"));
    $numclips = getClippersCount($con, $topprod['productid']);
    $listofimages = explode('$$$', $prod['image']);
    $description = js_string_escape(strip_tags( implodeDescription( explodeDescription( $prod['description']))));
    if ($GLOBALS['loggedUserID'] > 0) 
      $buybutton = "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Go to the seller's website\" class='btn discountButton' href=\"cliprtransfer?link=".($prod['link'])."\" target='_blank'><i class='icon-shopping-cart'></i>Buy</a>";
    else {
      $absProdUrl = getAbsoluteUrl("../output/product/".$prod['productid']);
      $buybutton = "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Go to the seller's website\" class='btn discountButton' href='javascript:void(0)' onClick='showLoginError(\"buy\", \"".$absProdUrl."\")'><i class='icon-shopping-cart'></i>Buy</a>";
    }
    $clippersString = getClippersList($con, $prod['productid']);
    $oldprice = number_format($prod['price']/( 1 - $topprod['pricedrop']/100.0 ),0);
    $sharebutton = "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Like the discount? Share it\" class='discountButton btn' href='javascript:void(0)' onClick='fbPublish(\"Humpty Dumpty price falls by Clipr\",\"http://clipr.in/output/pricedrop\",\"".getBigImage($listofimages[0])."\",\"Amazing products at Amazing prices - ".js_string_escape($prod['title'])."\",\"".$description."\")' ><i class='icon-facebook-sign'></i> Share </a>";
    if($clippersString == "")
      $clipperText = '';
    else
      $clipperText = '<b>Clipped By:</b><br> '.$clippersString;

    echo '
    <div class="well">
      <div class="row-fluid" style="">
        <span class="span4">
          <a class="ajax-link" href="product/'.$prod['productid'].'">
            <img style="max-height:300px;" src="'.getBigImage($listofimages[0]).'">
          </a>
        </span>
        <span class="span8">
      <h3 class="discountHeading">
        <a class="ajax-link" href="product/'.$prod['productid'].'">'.$prod['title'].'</a>
      </h3>
      <h4 class="pricedropPrice">
        <div class="row-fluid">
        <span class="span8" style="color: #0a2;"><i class="icon-arrow-down"></i> '.number_format($topprod['pricedrop'],0).'% in '.$topprod['numdays'].' days!</span>
        <span class="span4"><span style="text-decoration:line-through">Rs. '.$oldprice.'</span> Rs. '.$prod['price'].'</span>
        </div>
      </h4>
        <div class="row-fluid">
          <span class="span8">
            <div class="dropDescription">
            '.$description.'
            </div>
          </span>
        <span class="span4">
          '.$clipbtn.'<br/>
          '.$buybutton.'<br/><br/>
          '.$sharebutton.'<br/><br/>
          '.$clipperText.'
          </span>
        </div>
        </span>
      </div>
    </div>';
  }
  
  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: view_rewards, productid: NA, user: ".$loggedUserID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
?>

<?php

?>
<div class="row-fluid">
  <?php 
    $topDrops = getTopDrops($con);
    echo '<h3 style="text-align:center">Irresistable products now at irresistable prices!</h3>';
    foreach ($topDrops as $topDrop)
      echo getPriceDropHtml($con, $topDrop);
  ?>
</div>
<script type="text/javascript">

$(document).ready(function() {
  <?php 
    foreach ($topDrops as $topDrop){
      if(getClipUnclip($con, $topDrop['productid'], $loggedUserID) == "Clip" )
        echo "changeToUnclipped('product',".$topDrop['productid'].");";
      else if(getClipUnclip($con, $topDrop['productid'], $loggedUserID) == "Unclip" ) 
        echo "changeToClipped('product',".$topDrop['productid'].");";


    }
    echo'
      $(".dropDescription").condense(
      {
        condensedLength: 350,
        moreSpeed: \'fast\',
        lessSpeed: \'slow\',
        moreText: \'\',
        lessText: \'\'
      });';
  ?>
});
</script>
