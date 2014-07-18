<?php 
  require_once 'utils.php';
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

.fb-comments, .fb-comments span, .fb-comments iframe[style] {width: 100% !important;}

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

  $(function(){
    $("#productbaseImage").preloader();
  });

  $(function(){
    $(".similar").preloader();
  });

</script>

<!-- End Code for preloading -->

<?php
  function getUidFromFbName($con, $fbname){
    $sql = "SELECT userid from fbdata where fbname is not null and fbname = '$fbname'";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);
    if(empty($row))
      return 0;
    else
      return $row['userid'];
  }

  $uid = getUidFromFbName($con, $_GET['name']);
  $invalidEntry = false;
  if($uid == 0 )
    $invalidEntry = true;
  else{
    $userentry = getEntry($con,$uid);
    if(empty($userentry))
      $invalidEntry = true;
    else{
      $userprod = getProductInfo($con, $userentry['productid']);
      $prodImages = explode('$$$', $userprod['image']);
    }
  }
?>

<?php 
  if($loggedUserID != $uid){
    if($uid != 0){
      $heading = $userentry['name'].'\'s entry for the contest!';
      $subtext = 'Clip this entry to your clipboard and help '.$userentry['firstname'].' win';
    }
    else{
      $heading = 'No contest entry found';
      $subtext = 'Whoops, Seems like a bad link. Don\'t worry, You can take part in the contest yourself.';
    }
    $button2 = "Enter Contest";
  }
  else{
    $heading = "Your entry for the contest!";
    $numclips = getClippersCount($con, $userentry['productid']);
    if($numclips < 5){
      if($numclips == 1)
        $subtext = "Whoa, only 1 clip! You need to get some more clips! Share away";
      else
        $subtext = "Well, thats a decent start. But you better not slack off.";
    }
    else
      $subtext = "Well, You seem to be doing great. But watch out, people are catching up.";
    $button2 = "Change Entry";
  }
  echo '
    <div class="well">
      <div class="row-fluid">
          <div class="span8">
            <div align="left" style="margin-left: 5px;">
              <span style="font-size:150%">'.$heading.' </span><br/>
              <span style="font-size:120%">'.$subtext.' </span>
            </div>
          </div>
          <div class="span2">
            <a style="margin:2px" class="btn pull-right ajax-link" href="contest" >See Entries</a>
          </div>
          <div class="span2">
            <a style="margin:2px" class="btn pull-right ajax-link" href="add/contest" >'.$button2.'</a>
          </div>
      </div>
    </div>';

  //  First time info on adding a new entry
  if($loggedUserID == $uid && $uid != 0){
    if(isset($_GET['init']) && $_GET['init']=='yes'){
      $rand = rand(0,1);
      if($rand==0)
        $funtext = "May the force be with you!";
      else
        $funtext = "May the odds be in your favour!";
      echo "
        <div style='color:#444' class='alert alert-block alert-warning fade in'>
          <button type='button' class='close' data-dismiss='alert'>×</button>
          <div class='alert-heading' style='font-weight:normal;font-size:110%'>Hey, Thats great! You have entered the contest. <br/><br/>Now share this link <b>http://clipr.in/output/contest/".$_GET['name']." </b> with your friends and get maximum clips before the deadline.
          <br/><br/>
          ".$funtext."</div>
        </div>
        <br/>";
      }
    }
?>
<?php 
  if(!$invalidEntry){
?>
  <div class="well">

  <div class="row">
    <span class="span12"><h3><?php echo $userprod['title']; ?></h3></span>  
  </div>

  <div class="row-fluid" id="productbaseImage">

  <span class="span8">
    <div class="carousel slide prodimage" id="cliprCarousel">
      <div class="carousel-inner">
        <?php
          foreach ($prodImages as $prodImage) {
        ?>
            <div class="item" id="cliprImage" align="center" style="background-color:transparent;min-height:200px">
              <img src="<?php echo getOrigImage($prodImage); ?>" alt="<?php echo $userprod['title']; ?>"/>
            </div>   
        <?php
          }
        ?>
      </div>
    </div>

    <div class="row=fluid" align="center">
      <ul id="mycarousel" class="jcarousel-skin-tango">
      <?php
          $carouselCounter = 0;
          foreach ($prodImages as $prodImage) {
        ?>
            <li>
              <img style="cursor:pointer;height:60px;" class="prodimage" onclick='$("#cliprCarousel").carousel(<?php echo $carouselCounter; ?>);' src="<?php echo getSmallImage($prodImage); ?>" alt="<?php echo $userprod['title']; ?>"/>
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
      <div id = "fb-comments-widget" class="fb-comments" data-href="<?php echo getAbsoluteUrl('product/'.$userprod['productid']); ?>" data-num-posts="3" style="margin:auto;text-align:center"></div>
    
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
      var config = {scroll: 1, visible: 4, size: <?php echo count($prodImages); ?> };
      console.log(config);
      setTimeout(function() {$('#mycarousel').jcarousel(config);},500);
      $('#mycarousel').jcarousel(config);
    });
    $("#cliprCarousel").ready(function() {
        $('#cliprCarousel').carousel();
        $('#cliprCarousel #cliprImage:first').addClass("active");
    });

    // Hack to ensure that if product description has tables, its displayed correctly
    // and to ensure that we have a scroll in description
  $(".description").ready(function() {
    $('.description').attr('style','margin-top:10px;'); 
    $("#mydescription").condense(
      {
        condensedLength: 500,
        moreSpeed: 'fast',
        lessSpeed: 'slow',
        moreText: '[show more]',
        lessText: '[show less]'
      });
  });
    </script>
    
  </span>

  <span class="span4">

    <div class="row-fluid">
      <h4><?php echo $userprod['title']; ?></h4>
    </div>

    <div class="row-fluid">
      <span class="cliprproduct price">Rs. <?php echo $userprod['price']; ?>
      <?php if (($userprod['markprice'] != "") && ($userprod['markprice'] != $userprod['price'])) { ?>
        <span style="text-decoration:line-through">(Rs. <?php echo $userprod['markprice']; ?>)</span>
      <?php } 
      ?>
      </span>
    </div><br/>

    <?php 
    if ($loggedUserID != 0)
      echo "<div class='row-fluid'><span class='cliprprodhead'>Seller:</span><span class=\"cliprproduct seller\"> <a href=\"cliprtransfer?link=".($userprod['link'])."\" target='_blank'><img src=\"".getSeller($con, $userprod['siteId'])."\" alt=\"Seller Logo\" class=\"companylogo\"/></a></span></div><br/>";
    ?>


    <div class="row-fluid">
      <?php 

        $helpTextClip = "You Clip what you Like";
        $helpTextBuy = "Go to the seller's website";
        $helpTextReport = "Report to ask administrator to remove the product - because of poor description or image quality, duplicate entry or explicit sexual or offensive content";
        $helpTextMessage = "Share this on Facebook to Get more clips";

        $clipbtn = getClipButton ($con, $loggedUserID, $userprod['productid'], "product", "right","cliprproduct");
        echo $clipbtn;

        if ($loggedUserID > 0) {
          echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextBuy\" class='cliprproduct btn' href=\"cliprtransfer?link=".($userprod['link'])."\" target='_blank'><i class='icon-shopping-cart'></i>Go to Seller Page</a>";
          if (($loggedUserID == 717323242) && ($loggedUserID == 729783320))
            echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextReport\" class='cliprproduct btn btn-danger' href='javascript:void(0)' onClick='reportAjax(".$userprod['productid'].",\"the product\", this);'><i class='icon-remove'></i> Report</a>";
        }
        else {
          $absProdUrl = getAbsoluteUrl("../output/contest/".$_GET['name']);
          echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextBuy\" class='cliprproduct btn' href='javascript:void(0)' onClick='showLoginError(\"buy\", \"".$absProdUrl."\");'><i class='icon-shopping-cart'></i>Go to Seller Page</a>";
        }
        echo "<a rel=\"tooltip\" data-placement=\"right\" data-original-title=\"$helpTextMessage\" class='cliprproduct btn' href='javascript:void(0)' onClick='fbPublish(\"I like this entry in Clipr Make a Wish contest. Clip it to help this entry win :)\",\"".getAbsoluteUrl("contest/".$_GET['name'])."\",\"".getBigImage($prodImages[0])."\",\"".js_string_escape("Clipr Make a Wish Contest: Entry by ".$userentry['name']." - ".$userprod['title'])."\",\"Discover amazing products. Help your friends win. Get a changce to win your favourite product for free\")'><i class='icon-facebook-sign'></i> Share </a>";

      ?>
    </div><br/>      

    <div class="row-fluid">
        
<!--       <div class='row-fluid'><span class='cliprprodhead'>Group:</span><span class="cliprproduct group"> <?php echo getProductGroupHtml($con, $userprod['productid']); ?></span></div><br/>
      <div class='row-fluid'><span class='cliprprodhead'>Tag:</span><span class="cliprproduct tag"> <?php echo getProductTagHtml($con, $userprod['productid']); ?></span></div><br/>
 -->
      <?php   
      if (trim(getProductUserTag($con, $userprod['productid'], $loggedUserID)) != "") {
        echo "<div class='row-fluid'><span class='cliprprodhead'>User Tag:&nbsp;</span><span class=\"cliprproduct usertag\">".getProductUserTag($con, $userprod['productid'], $loggedUserID)."</span></div><br/>";
      }
      ?>
      <?php   
      if (trim(getClippersList($con, $userprod['productid'])) != "") {
        echo "<div class='row-fluid'><span class='cliprprodhead'>Clipped By:</span><br/><span class=\"cliprproduct clipper\">".getClippersList($con, $userprod['productid'])."</span></div><br/>";
      }
      ?>
    </table>
    </div>

    <div class="row-fluid">
      <div class="cliprproduct" align="justify"><span class='cliprprodhead'>Description:</span><div class="description"><div id="mydescription"> <?php echo implodeDescription( explodeDescription( $userprod['description']."<br/>".$userprod['description2'] ) ); ?></div></div></div>
    </div>
  </span>

  </div>  

  </div>
<?php 
  }
?>
<script type="text/javascript">
$(document).ready(function(){
  <?php 
    $msg = "<a href = \'home\' class=\'ajax-link\' style=\'color:#ccc\'> Explore more</a> or <a href = \'".$loginUrl."\' style=\'color:#ccc\'> Login </a> for a better experience ";
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
    if(isset($_GET['init']) && $_GET['init'] == "yes" && $loggedUserID > 0){
      $absProdUrl = getAbsoluteUrl("../output/contest/".$_GET['name']);
      $postToWallUrl = "https://www.facebook.com/dialog/feed?
        app_id=143944345745133&
        link=".urlencode($absProdUrl)."&
        picture=".urlencode(getSmallImage($prodImages[0]))."&
        name=".urldecode("I Just entered Clipr Make a Wish Contest")."&
        caption=".urlencode("I added my entry \"".$userprod['title']."\". Clip it and help me win")."&
        description=".urlencode( implodeDescription( explodeDescription( $userprod['description'] ) ) )."&
        redirect_uri=".urlencode(getAbsoluteUrl('../output/closePage'))."&display=popup";
      echo "var contestPostLink = ".json_encode($postToWallUrl).";";
      echo "openPopup(contestPostLink);";
    }
  ?>
  var clipstatus= <?php echo json_encode(getClipUnclip($con, $userentry['productid'], $loggedUserID)); ?>;
  var pid = <?php echo $userentry['productid'];?>;
  if( clipstatus == "Clip")
    changeToUnclipped("product",pid);
  else if( clipstatus == "Unclip")
    changeToClipped("product",pid);
});
</script>
