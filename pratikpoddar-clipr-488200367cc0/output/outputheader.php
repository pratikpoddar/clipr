<?php
  require_once '../output/utils.php';
?>

<!-- Collage Maker -->
<!--   <div id = "collagemaker" class="clearfix" style="display:none">
    <div id="collagemaker-topmenu" class="row-fluid">
      <div class="span9">
        <span class="collage-button"><a class="btn btn-primary modalbox collagemaker-topmenu-btn" href="#collageForm" id="publishCollage">Publish</a></span>
        <span class="collage-button"><a class="btn btn-primary collagemaker-topmenu-btn" id="deleteCollage">Delete</a></span>
        <span class="collage-button"><a class="btn btn-primary collagemaker-topmenu-btn" id="clearCanvas">Clear</a></span>
      </div>
      <span class="span2" id="minimize-btn-container">
        <i class="icon-chevron-down minimize-collage"></i>
      </span>
      <span class="span1" id="minimize-btn-container">
        <i class="icon-chevron-right" id="minimize-info"></i>
      </span>
    </div>
    <input id="collage_id" type="hidden" value=""/>
    <div id ="collagemaker-canvas" class="droppable">
    </div>
    <div id="collagemaker-info" style="display:none">
      <div id="collagemaker-info-inner">
      </div>
    </div>
  </div>
 -->
 <!-- Navbar
    ================================================== -->
<div class="navbar navbar-fixed-top" id="topnavBar">
  <div class="navbar-inner" style="padding:0px">
    <div class="container">
      <a class="brand ajax-link" href="home" style="padding:5px;font-size:95%"><img style="width:auto; max-height:38px;"src="./img/clipr_banner.jpg"></a>
      <div class="nav-collapse" id="main-menu">
        <ul id="main-menu-right" class="nav pull-right">
          <li>
              <input id="cliprsearch" type="text" class="span5" placeholder="Search for Product or Group or Tag or User" style="height:30px;margin:3px">
          </li>
          <?php 
            if ($user) {
              echo '
                <li>
                <a rel="tooltip" data-placement="bottom" data-original-title="Add Product" href="add" class="ajax-link top-right-menu" >Add Product</a>
                </li>          
                <li>
                <a rel="tooltip" data-placement="bottom" data-original-title="All your Clips" href="clips" class="ajax-link top-right-menu" >Clip Board</a>
                </li>
                <li>
                <a rel="tooltip" data-placement="bottom" data-original-title="Find Gifts" href="gifts" class="ajax-link top-right-menu" >Gift</a>
                </li>
                <li class="dropdown" id="top-dropdown">
                <a id="loggedUserLink"    href="home" class="ajax-link user-dropdown top-right-menu"><span id="loggedUserName">'.$user_profile['name'].'</span><b style="margin-left:5px;border-top-color:#FFF;" class="caret"></b></a>
                <div id="noti_bubble" style="display:none"><span id="noti_num">'.getNewActivities($con,$user).'</span></div>
                <ul id="user-dropdown" class="dropdown-menu" role="menu">
                  <li><a tabindex="-1" href="profile"  class="ajax-link">Profile</a></li>
                  <li><a tabindex="-1" href="activity" class="ajax-link">Activity&nbsp;(<span id = "clipr-activities">'.getNewActivities($con,$user).'</span>)</a></li>
                  <li><a tabindex="-1" href="rewards"  class="ajax-link">Rewards&nbsp;(<span id = "clipr-reward-points">'.getTotalRewards($con,$user).'</span>)</a></li>
                  <li><a tabindex="-1" href="refer"  class="ajax-link">Refer \'n Win</a></li>
                  <li><a tabindex="-1" href="'.$logoutUrl.'">Logout</a></li>
                  </ul>
                </li>
              ';
            }

            else
            {
              echo '
                <li>
                  <a rel="tooltip" data-placement="bottom" data-original-title="Home" href="home" class="ajax-link top-right-menu">Home</a>
                </li>
                <li>
                  <a rel="tooltip" data-placement="bottom" data-original-title="How to Use Clipr" href="help" class="ajax-link top-right-menu">Help</a>
                </li>
                <li>
                  <a rel="tooltip" data-placement="bottom" data-original-title="About Us" href="about" class="ajax-link top-right-menu">About</a>
                </li>                
                <li class="dropdown">
                <a id="loggedUserLink" class="loginlink top-right-menu" href="javascript:void(0);"><span id="loggedUserName" style="width:170px;max-width:170px;"><img src="./img/facebook-connect.png" style="height:28px;"></span></a>
                </li>
              ';
            }
          ?>
        </ul>
     </div>
   </div>
 </div>
</div>

<!-- <div id="loginBar" class="subnav" align="center" style="padding-top:20px;display:none;z-index:1000;">
  <b>Discover</b> awesome products based on your interests, <b>Share</b> your collections on Clip Boards and <b>Win</b> reward points for your clips.&nbsp;&nbsp; <a href= "javascript:void(0);" class="btn btn-small btn-primary loginlink" style="vertical-align:baseline">Login</a>
</div>

<div id="helpBar" class="subnav" align="center" style="padding-top:20px;display:none;z-index:1000;">
  Need help in using Clipr? Don't worry. Visit - <a target="_blank" href="help" class="ajax-link">How to Use Clipr?</a>
</div>

<div id="rewardsBar" class="subnav" align="center" style="padding-top:20px;display:none;z-index:1000;">
  Get Clipr Rewards which can be redeemed for real products - see your account at '<a target="_blank" class="ajax-link" href="rewards">Rewards</a>'
</div>
 -->
<div class="row-fluid">
  <div align="center" style="margin-left: auto; margin-right:auto;width: 2%;">
    <img src="img/loader24.gif" style="position: fixed; top: 70px; margin-right: auto; margin-left: auto; display: none; z-index:1001;" id="loadingImage">
  </div>
</div>

<div id='userinfo' style='display:none;padding-bottom:30px'></div>
<div class="well" id = 'inlineproduct' style="display:none">
  <div id="prodFancyLoadingImage" style="vertical-align:center;text-align:center;width:800px;height:700px">
    <img src="img/loader-rect.gif">
  </div>
  <div id="fancyProductPage"></div>
</div>
<div id='tagbox' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='modalTagLabel' aria-hidden='true' >
  <div class="row-fluid" id="fancyTagBox" style="text-align: center;">
    <div class='modal-header'>
      <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
      <h3 id='modalTagLabel' align='center'>Tag a friend</h3>
    </div>
    <h5 style="font-weight:normal;">Tag a Clipr or Facebook friend to share this product with them</h5>
    <input class="span12" style="height: 25px; box-shadow: none; -webkit-box-shadow:none; width:140px; margin-bottom: 0px;" placeholder="Enter a friend's name" id="tagfriend" type="text" >
    <br/>
    <textarea id="tagmessage" style="display:none" class="span12" rows="3" placeholder="Add a message(Optional)"></textarea>
    <div id="addTagText">
      <a href="javascript:void(0);" data-state="collapsed">Add a message <i class="icon-arrow-down"></i></a>
    </div>
    <input type="hidden" style="display:none" id="hiddenproductid"></input>
    <input type="hidden" style="display:none" id="hiddentags">
    <a class="btn btn-primary" href="javascript:void(0);" data-loading-text="Tagging..." id="tagsubmit">Tag</a>
    <div id="noTagError">No user has been selected for tagging.</div>
  </div>
</div>

<div class="well" id = 'collageForm' style="display:none">
  <div id="collageFormLoadingImage" style="vertical-align:center;text-align:center;width:800px;height:700px">
    <img src="img/loader-rect.gif">
  </div>
  <div id="fancyCollageForm">
    <div class="row-fluid">
      <span class="span7" style="text-align: center;">
        <img id = "collagePreview" src="" >
      </span>
      <span class="span5" style="text-align: center;" id = "collageFormInner">
        <input id="collageTitle" class="span12" type="text" placeholder="Give Your Collage A Title" >
        <textarea id = "collageDesc" class="span12" rows="8" placeholder="Describe Your Collage..."></textarea>
        <a class="btn btn-primary" id ="submitCollage" href="javascript:void(0);">Publish Collage</a>
      </span>
    </div>
  </div>
</div>

<?php
  $userInterests = getInterests($con, $loggedUserID, $loggedUserID);
  if ($loggedUserID != 0 && empty($userInterests) ) 
  {
    echo "<div class='alert alert-error interest-alert-block' style='max-width: 940px;margin-right: auto;margin-left: auto;'>";
    echo "  <div class='centered'>";
    echo "    <p> You have not entered any interests. Please enter a few interests for a better experience </p>";
    echo "    <a class='btn btn-danger btn-add-interests ajax-link' href='interests'>Add Interests</a>";
    echo "  </div>";
    echo "</div>";
  }
?>
<?php
  require_once '../output/loginModal.php';
  require_once '../output/modalie.php';
?>
<!-- Beginning of Div id = Container Tag -->
<div class="container" id="adjustableContainer">
<script type="text/javascript">

var popstateReady = false;
window.onpopstate = function(event) {
  console.log("pathname: "+location.pathname);
  if($('.fancybox-inner').length>0){
    $.fancybox.close();
  }
  if(popstateReady){
    if(($('#pagename').text()) != location.pathname.substring(8))
      executeupdate(location.pathname.substring(8));// this is hack to remove '/output/'
  }
  else
    return;
};

$(document).ready(function(){
  mixpanel.track("page loaded");
  _gaq.push(['_trackEvent', 'Auto-Action', 'page-load']);


  <?php 
    if($loggedUserID != 0){
      if(tourShown($con, $loggedUserID) && (magShown($con, $loggedUserID) || !startsWith($_SERVER['REQUEST_URI'],"/output/clips") ) && !referShown($con, $loggedUserID) ){
        updateReferShown($con, $loggedUserID);
        echo 'setTimeout(function(){referInfo();},1500);';
      }
    }
  ?>

  <?php 
  if($isSessionStart) 
    echo 'if(document.location.href.indexOf("home") >= 0 ) {
            mixpanel.track("home page entry");
            _gaq.push(["_trackEvent", "Auto-Action", "home-page-entry"]);
          }
          else if(document.location.href.indexOf("/product/") >= 0 ) {
            mixpanel.track("product page entry");
            _gaq.push(["_trackEvent", "Auto-Action", "product-page-entry"]);
          }
          else if(document.location.href.indexOf("/clips/") >= 0 ) {
            mixpanel.track("clips page entry");
            _gaq.push(["_trackEvent", "Auto-Action", "clips-page-entry"]);
          }
    ';
  ?>

  setInterval(function(){$.fancybox.update();},500);

  $('#loadingImage').bind('ajaxStart', function(){
      $(this).show();
  }).bind('ajaxStop', function(){
      $(this).hide();
  });

  <?php
    if($isSessionStart)
      echo "intialModals();";
    if($user != 0 && ($_SERVER['REQUEST_URI'] == "/output/home" || $_SERVER['REQUEST_URI'] == "/") && ! tourShown($con, $user) ){
      updateTourShown($con, $user);
      echo "tour(false,'', true);";
    }
  ?>
});

$(document).ready(function() {
  // hide #back-top first
  $("#back-top").hide();
  
  // fade in #back-top
  $(function () {
    $(window).scroll(function () {
      if ($(this).scrollTop() > 100) {
        $('#back-top').fadeIn();
      } else {
        $('#back-top').fadeOut();
      }
    });

    // scroll body to 0px on click
    $('#back-top a').click(function () {
      gototop(800);
    });
  });

});
</script>

<script type="text/javascript">

function intialModals()
{
  if ( $.browser.msie && parseInt($.browser.version, 10) < 9)
    $('#modalIEWarning').modal('show');
}

function myXOR(a,b) {
  return ( a || b ) && !( a && b );
}

function refreshPage(){
  return myXOR($('#loggedUserName').html() == "<img src=\"./img/facebook-connect.png\">" , $.cookie('loggedUserId') == null || $.cookie('loggedUserId')=='');
}

$(document).ready(function() {
  
  $(function () {
    $(window).scroll(function () {
    });

    $(window).scroll(function () {
      $('#topnavBar').css('left', 0-$(this).scrollLeft());
    });
  });
});
</script>

<script type="text/javascript">

  function preloader() 
  {
     // counter
     var i = 0;
     // create object
     var imageObj = new Image();
     // set image list
     var images = new Array();
     images[0]="img/clipr.png"
     images[1]="img/gritter-light.png"
     images[2]="img/gritter-long.png"
     images[3]="img/gritter.png"
     // start preloading
     for(i=0; i<=3; i++) 
     {
          imageObj.src=images[i];
     }
   }

 $(document).ready(preloader);
 $(document).ready(function() { $("[rel=tooltip]").tooltip(); });

</script>

<script type="text/javascript">


      function addLoginUrls(){
        <?php 
          if ($loginUrl != ""){
            echo "$('.loginlink').unbind('click');";
            echo "setTimeout(function() {
              $('.loginlink').click(function(e) {
                openLoginPopupWithCurrentRedirect(\"".$loginUrl."\");
              });
            },500);";
            echo "$('#modalLoginLink').unbind('click');";
            echo "
              $('#modalLoginLink').click(function(e) {
                e.preventDefault();
                var inferredRedirect = $('#modalLoginLink').attr('href'); 
                if(inferredRedirect != '')
                  openloginpopup(\"".$loginUrl."\", inferredRedirect);
                else
                  openLoginPopupWithCurrentRedirect(\"".$loginUrl."\");
              });
            ";
          }
        ?>
      }
</script>


