<?php 
  require_once 'utils.php';
?>

<?php

  function getSimilarStringFromProd($con, $prodinfo){
    $listofimages = explode('$$$', $prodinfo['image']);
    $string = "
    <li class='span2 similar'>
      <a class='prodlink ajax-link' href='product/".$prodinfo['similar']."' >
        <img src='".getSmallImage($listofimages[0])."' class='img-rounded center'>
        <div class='center'>".trim($prodinfo['title'])."</div>
      </a>
    </li>";
    return $string ;
  }

  function getTopSimilarProducts($con, $pid){
    $retarr = array();
    if ($pid != 0)
    {
      exec('cd ..; python userProductMappings.py --productid='.$pid.'; cd output');
      $sql = "SELECT distinct similar,image,title from productTopSimilar as pts join productDetail as pd on pts.similar = pd.productid where pts.productid =$pid and pts.similar != pts.productid limit 6";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
      while($row = mysql_fetch_array($result))
      {
        $row['title'] = trim($row['title']);
        array_push($retarr, $row);
      }
    }
    return $retarr;
  }

  function getTopSimilarClips($con, $pid){
    $retarr = array();
    if ($pid != 0)
    {
      $sql = "SELECT ct2.productid as similar,image,title, count(*) as num from clipsTable as ct1 join clipsTable as ct2 join productDetail as pd on ct1.userid = ct2.userid and ct2.productid = pd.productid where ct1.productid != ct2.productid and ct1.productid = $pid group by ct2.productid order by num desc limit 6";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
      while($row = mysql_fetch_array($result))
      {
        $row['title'] = trim($row['title']);
        array_push($retarr, $row);
      }
    }
    return $retarr;
  }

  function insertIntoViewTable($con, $pid, $uid){
    if ($pid != 0 )
    {
      $sql = "INSERT into productView(userid, productid, views) VALUES($uid, $pid, 1) ON DUPLICATE KEY UPDATE views = views+1";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
      increaseForView($con, $pid);
    }
  }

  insertIntoViewTable($con, $_GET['id'], $user);
  $prod = getProductInfo($con, $_GET['id']);
  $prodImages = explode('$$$', $prod['image']);
  
?>

<style type="text/css">

.prodimage{
  background-color: #eee;
  padding: 4px;
  margin: 5px 5px;
  box-shadow: 2px 2px 2px #aaa;
}

.btn.cliprproduct {
  width: 50%;
  padding: 6px;
  font-size: 90%;
  margin-bottom: 10px;
}

td.cliprproduct {
}

div.cliprproduct {
  padding-top: 10px;
  padding-bottom: 10px;
}

.companylogo {
  max-height : 40px;
  max-width : 50%;
}

.fb-comments, .fb-comments span, .fb-comments iframe[style] {width: 100% !important; position:relative;}

.prodLink .img-rounded {
  height: 100px;
  padding: 5px;
  background: #EEE;
}

.similar {
  text-align: center;
}

.cliprprodhead {
  color: #002640;
  font-weight: bolder;
}

.price {
  font-size:110%;
}
</style>

<!-- Code for preloading -->

<script type="text/javascript">

</script>

<!-- End Code for preloading -->

<div class="well">

<div class="row">
  <span class="span12"><h3><?php echo $prod['title']; ?></h3></span>  
</div>

<div class="row-fluid" id="productbaseImage">

<span class="span8">
  <div class="carousel slide prodimage" id="cliprCarousel">
    <div class="carousel-inner">
      <?php
        foreach ($prodImages as $prodImage) {
      ?>
          <div class="item" id="cliprImage" align="center" style="background-color:transparent;min-height:200px">
            <img src="<?php echo getOrigImage($prodImage); ?>" style="min-width:30px;"alt="<?php echo $prod['title']; ?>"/>
          </div>   
      <?php
        }
      ?>
    </div>
  </div>

  <div class="row=fluid" align="center">
    <ul id="mycarousel" class="jcarousel-skin-tango" style="list-style-type:none;">
    <?php
        $carouselCounter = 0;
        foreach ($prodImages as $prodImage) {
      ?>
          <li>
            <img style="cursor:pointer;height:60px;" class="prodimage" onclick='$("#cliprCarousel").carousel(<?php echo $carouselCounter; ?>);' src="<?php echo getSmallImage($prodImage); ?>" alt="<?php echo $prod['title']; ?>"/>
          </li>
      <?php
          $carouselCounter++;
        }
      ?>
    </ul>
  </div>

  <div class="row-fluid">

    <div id="fb-root" class="clearfix"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=170592559743815";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div id = "fb-comments-widget" class="fb-comments" data-href="<?php echo getAbsoluteUrl('product/'.$_GET['id']); ?>" data-num-posts="3" style="margin:auto;text-align:center"></div>
  
  </div>

  <script type="text/javascript">
  $("#cliprCarousel").ready(function() {
      $('#cliprCarousel').carousel();
      $('#cliprCarousel #cliprImage:first').addClass("active");
  });

  // Hack to ensure that if product description has tables, its displayed correctly
  // and to ensure that we have a scroll in description
  // Enabling truncation on long description
  $(".description").ready(function() {
    if( hasSomeText($('#mydescription').text() ) ){
      $('.description').attr('style','margin-top:10px;'); 
      $("#mydescription").condense(
        {
          condensedLength: 500,
          moreSpeed: 'fast',
          lessSpeed: 'slow',
          moreText: '[show more]',
          lessText: '[show less]'
        });
    }
    else
      $("#mydescription").html('<span style="font-weight:lighter;font-style:italic">No Description Available</span>');
  });

  </script>
  
</span>

<span class="span4">

  <div class="row-fluid">
    <h4><?php echo $prod['title']; ?></h4>
  </div>

  <div class="row-fluid">
    <span class="cliprproduct price">Rs. <?php echo $prod['price']; ?>
    <?php if (($prod['markprice'] != "") && ($prod['markprice'] != $prod['price'])) { ?>
      <span style="text-decoration:line-through">(Rs. <?php echo $prod['markprice']; ?>)</span>
    <?php } 
    ?>
    </span>
  </div><br/>

  <?php 
    echo "<div class='row-fluid'><span class='cliprprodhead'>Seller:</span><span class=\"cliprproduct seller\"> <a href=\"cliprtransfer?link=".($prod['link'])."\" target='_blank'><img src=\"".getSeller($con, $prod['siteId'])."\" alt=\"Seller Logo\" class=\"companylogo\"/></a></span></div>";
    if(isProductRemoved($con, $_GET['id']))
      echo '<span class="cliprproduct available">(Product Removed from Seller Website)</span><br />';
    echo "<br/>";
  ?>

  <div class="row-fluid">
    <?php 

      $helpTextClip = "You Clip what you Like";
      $helpTextBuy = "Go to the seller's website";
      $helpTextReport = "Report to ask administrator to remove the product - because of poor description or image quality, duplicate entry or explicit sexual or offensive content";
      $helpTextMessage = "Tell a friend about this product";

      $clipbtn = getClipButton ($con, $loggedUserID, $_GET['id'], "product", "right","cliprproduct");
      echo $clipbtn;
      echo "<a id='product-tag-btn' rel=\"tooltip\" data-placement=\"right\" data-original-title=\"Tag a friend\" data-productid=".$prod['productid']." class='cliprproduct btn btn-primary tag-btn' href='javascript:void(0)'>Tag</a>";
      echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextBuy\" class='cliprproduct btn' href=\"cliprtransfer?link=".($prod['link'])."\" target='_blank'><i class='icon-shopping-cart'></i>Buy at Seller Page</a>";
      if (($loggedUserID == 717323242) && ($loggedUserID == 729783320))
        echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextReport\" class='cliprproduct btn btn-danger' href='javascript:void(0)' onClick='reportAjax(".$_GET['id'].",\"the product\", this);'><i class='icon-remove'></i> Report</a>";
    ?>
  </div><br/>      

  <div class="row-fluid">
    <?php 
      $grouphtml = getProductGroupHtml($con, $_GET['id']);
      $taghtml = getProductTagHtml($con, $_GET['id']);
      if($grouphtml != "")
        echo "<div class='row-fluid'><span class='cliprprodhead'>Group: </span><span class='cliprproduct group'>".$grouphtml."</span></div><br/>";
      if($taghtml != "")
        echo "<div class='row-fluid'><span class='cliprprodhead'>Tag: </span><span class='cliprproduct tag'>".$taghtml."</span></div><br/>";
    ?>
    <?php   
    if (trim(getProductUserTag($con, $_GET['id'], $loggedUserID)) != "") {
      echo "<div class='row-fluid'><span class='cliprprodhead'>User Tag: </span><span class=\"cliprproduct usertag\">".getProductUserTag($con, $_GET['id'], $loggedUserID)."</span></div><br/>";
    }
    ?>
    <?php
      $totalViews = getTotalViews($con, $_GET['id']);
      if($totalViews > 5)
        echo '<div class="row-fluid"><span class="cliprprodhead">Views: </span><span class="cliprproduct views"> '.$totalViews.' </span></div><br/>';
    ?>
    <?php
      $clippersList = trim(getClippersList($con, $_GET['id']));
      if ($clippersList != "") 
        echo "<div class='row-fluid'><span class='cliprprodhead'>Clipped By: </span><br/><span class=\"cliprproduct clipper\">".$clippersList."</span></div><br/>";
    ?>
    <?php 
      //echo "<i class=\"icon-bar-chart pull-right\" style=\"font-size:200%;cursor:pointer\" onClick=\"window.open('priceHistory?id=".$_GET['id']."', 'Price History', '')\"></i>";
    ?>
    <?php
      if ($prod['availability'] && $prod['availability'] != "") 
        echo "<div class='row-fluid'><span class='cliprprodhead'>Availability: </span><span class=\"cliprproduct\" style=\"text-transform:capitalize\">".$prod['availability']."</span></div><br/>";
      if ($prod['delivery'] &&  $prod['delivery'] != "") 
        echo "<div class='row-fluid'><span class='cliprprodhead'>Delivery: </span><span class=\"cliprproduct\" style=\"text-transform:capitalize\">".$prod['delivery']."</span></div><br/>";
      if ($prod['shippingcost'] &&  $prod['shippingcost'] != "") 
        echo "<div class='row-fluid'><span class='cliprprodhead'>Shipping Cost: </span><span class=\"cliprproduct\" style=\"text-transform:capitalize\">".$prod['shippingcost']."</span></div><br/>";
    ?>
  </div>
  <div class="row-fluid">
    <div class="cliprproduct" align="justify"><span class='cliprprodhead'>Description:</span><div class="description" id = "mydescription"> <?php echo implodeDescription( explodeDescription( $prod['description']."<br/>".$prod['description2'] ) ); ?></div></div>
  </div>
</span>
</div>  
</div>

<?php
    $similarProds = getTopSimilarProducts($con, $_GET['id']);
    if (count($similarProds) > 0) {
      echo "<div class='well'>";
      echo "<div class='row-fluid'>";
        echo "<span class='span12'>";
          echo "<h3>Similar products</h3>";
        echo "</span>";
      echo "</div>";
      echo "<div class='row-fluid'>";
        echo "<span class='span12'>";
          echo "<ul class='inline unstyled'>";
            foreach ($similarProds as $similarProd) 
              echo getSimilarStringFromProd($con, $similarProd);
          echo "</ul>";
        echo "</span>";
      echo "</div>";
      echo "</div>";
  }
?>

<?php 
    $similarClips = getTopSimilarClips($con, $_GET['id']);
    if (count($similarClips) > 0) {
      echo "<div class='well'>";
      echo "<div class='row-fluid'>";
        echo "<span class='span12'>";
          echo "<h3>People who clipped this also clipped</h3>";
        echo "</span>";
      echo "</div>";
      echo "<div class='row-fluid'>";
        echo "<span class='span12'>";
          echo "<ul class='inline unstyled'>";
            foreach ($similarClips as $similarClip) 
              echo getSimilarStringFromProd($con, $similarClip);
          echo "</ul>";
        echo "</span>";
      echo "</div>";
      echo "</div>";
  }
?>

<script type="text/javascript">
$(document).ready(function(){

  $("#productbaseImage").preloader();
  $(".similar").preloader();
  var numimages = <?php echo count($prodImages); ?>;
  visibleimages = 4;
  if(numimages <= 4)
    visibleimages = numimages;

  var config = {scroll: 1, visible: visibleimages, size: <?php echo count($prodImages); ?> , itemFallbackDimension:70};
  console.log(config);
  setTimeout(function() {
    $('#mycarousel').jcarousel(config);
    resetbindings();
    FB.XFBML.parse();
  },500);
  $('#mycarousel').jcarousel(config);
  <?php
    $msg = "<a href = \'home\' class=\'ajax-link\' style=\'color:#ccc\'> Explore more</a> or <a href = \'javascript:void(0);\' id=\'gritterLoginLink\' class=\'loginlink\' style=\'color:#ccc\'> Login </a> for a better experience ";
    if ($loggedUserID == 0 && $loginUrl != "" ){ 
      echo "
        setTimeout(function() {
        $.gritter.add({
          title: 'Explore Clipr',
          text: '".$msg."',
          image: 'img/clipr.png',
          sticky: true,
          time: ''
        });},500);";
    }
  ?>
  var clipstatus= <?php echo json_encode(getClipUnclip($con, $_GET['id'], $loggedUserID)); ?>;
  var pid = <?php echo $_GET['id'];?>;
  if( clipstatus == "Clip")
    changeToUnclipped("product",pid);
  else if( clipstatus == "Unclip")
    changeToClipped("product",pid);
});
</script>
