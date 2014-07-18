<?php
  require_once '../output/utils.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb='https://www.facebook.com/2008/fbml'>

<head>

<base href="http://<?php echo $_SERVER['HTTP_HOST']?>/output/">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php 
if (isset($_GET['pagetype']) && ($_GET['pagetype'] == "productbase") ) {
  $prod = getProductInfo($con, $_GET['id']);
  if(!empty($prod)){
    $prodImages = explode('$$$', $prod['image']);
    echo '<meta property="og:title"  content="'.htmlentities($prod['title']).'" /> ';
    echo '<meta property="og:image"  content="'.getCroppedImage($prodImages[0]).'" /> ';
    echo '<meta property="og:description"  content="'.htmlentities(strip_tags($prod['description'])).'" /> ';
  }
}
else if (isset($_GET['pagetype']) && ($_GET['pagetype'] == "magazineclipr") && $userID > 0){
  echo '<meta property="og:title"  content="'.htmlentities($userinfo['firstname']).'\'s Clipboard" /> ';
  echo '<meta property="og:image"  content="http://graph.facebook.com/'.$userID.'/picture?type=large" /> ';
  echo '<meta property="og:url"  content="http://clipr.in/output/clips/'.$userID.'" /> ';
  echo '<meta property="og:description"  content="'.htmlentities(strip_tags("Explore ".$userName."'s clipboard and discover amazing stuff")).'" /> ';
}

else if (isset($_GET['pagetype']) && ($_GET['pagetype'] == "pricedrop") ){
  echo '<meta property="og:title"  content="Humpty Dumpty price falls by Clipr - Because shopping is fun with friends" /> ';
  echo '<meta property="og:image" content="'.getAbsoluteUrl("../image/clipr_fb_logo.png").'"/>';
  echo '<meta property="og:url"  content="http://clipr.in/output/discounts" /> ';
  echo '<meta property="og:description"  content="Find amazing stuff now at the right time with our compilation of the most amazing products with highest price drops." /> ';
}
else if (isset($_GET['pagetype']) && ($_GET['pagetype'] == "contestbase") ){
  $uid = intval(json_decode(file_get_contents('http://graph.facebook.com/'.$_GET['name']))->id);
  $userentry = getEntry($con,$uid);
  $userprod = getProductInfo($con, $userentry['productid']);
  $prodImages = explode('$$$', $userprod['image']);
  $heading = $userentry['name'].'\'s entry for the contest!';
  echo '<meta property="og:title"  content="'.htmlentities($heading).'" /> ';
  echo '<meta property="og:image"  content="'.getBigImage($prodImages[0]).'" /> ';
  echo '<meta property="og:description"  content="'.htmlentities(strip_tags($userentry['tagline'])).'" /> ';
}
else if (isset($_GET['pagetype']) && ($_GET['pagetype'] == "contestdetails" || $_GET['pagetype'] == "contestentries")){
  echo '<meta property="og:title"  content="Clipr Make a Wish Contest" /> ';
  echo '<meta property="og:image" content="'.getAbsoluteUrl("../image/clipr_fb_logo.png").'"/>';
  echo '<meta property="og:url"  content="http://clipr.in/output/contest" /> ';
  echo '<meta property="og:description"  content="Just choose any product you like from accross 20 e-commerce websites. Add it on clipr. Get maximum clips and get the product for free" /> ';
}
else {
  echo '<meta property="og:image" content="'.getAbsoluteUrl("../image/clipr_fb_logo.png").'"/>';
  echo '<meta property="og:title"  content="Clipr - Because Shopping is fun with friends" />'; 
  echo '<meta property="og:description"  content="Clipr.in is a personalized magazine of awesome products for window shopping and gift recommendations" /> ';
}
?>

<?php 
  $loggedoutTourNav = '<div class="navbar-inner" style="padding:0px">
      <div class="container">
        <a class="brand" href="javascript:void(0);" style="padding:5px;font-size:95%"><img style="width: auto;max-height:38px;" src="./img/clipr_banner.jpg"></a>
        <div class="nav-collapse" id="main-menu">
          <ul id="main-menu-right" class="nav pull-right">
            <li>
                <input id="cliprsearch" type="text" class="span5" placeholder="Search for Product or Group or Tag or User" style="height:30px;margin:3px">
            </li>
            <li>
              <a rel="tooltip" data-placement="bottom" data-original-title="Add Product" href="javascript:void(0);" class="ajax-link" style="padding-top:13px">Add Product</a>
            </li>          
            <li>
              <a rel="tooltip" data-placement="bottom" data-original-title="All your Clips" href="javascript:void(0);" style="padding-top:13px">Clip Board</a>
            </li>
            <li>
              <a rel="tooltip" data-placement="bottom" data-original-title="Find Gifts" href="javascript:void(0);" style="padding-top:13px">Gift</a>
            </li>
            <li class="dropdown" id="top-dropdown" >
              <a id="loggedUserLink" style="padding-top:13px" href="javascript:void(0);" ><span id="loggedUserName">Rajnikanth<b style="margin-left:5px;border-top-color:#FFF;" class="caret"></b></span></a>
              <ul id="user-dropdown" class="dropdown-menu" role="menu" style="">
                <li><a tabindex="-1" href="javascript:void(0);" >Profile</a></li>
                <li class=""><a tabindex="-1" href="javascript:void(0);" >Activity&nbsp;(<span id="clipr-activities">20</span>)</a></li>
                <li class=""><a tabindex="-1" href="javascript:void(0);" >Rewards&nbsp;(<span id="clipr-reward-points">999</span>)</a></li>
                <li><a tabindex="-1" href="javascript:void(0);" >Invite Friends</a></li>
                <li><a tabindex="-1" href="javascript:void(0);">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>';
?>

<!-- start Mixpanel -->
<script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,
e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("6f0db0515893f54f3f966caecb860367");</script>
<!-- end Mixpanel -->

</head>

<title>Clipr - Because Shopping is fun with Friends</title>

<!-- <script type="text/javascript" src="//use.typekit.net/lue4uyf.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script> -->

<script type="text/javascript">
function tourloggedout(){
  var topbar = <?php echo json_encode($loggedoutTourNav); ?>;
  var oldhtml = $('#topnavBar').html();
  console.log(oldhtml);
  $('#topnavBar').html(topbar);
  tour(true, oldhtml, false);
}
</script>
<!-- Standard Favicon -->
<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
<!-- For iPhone 4 Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
<!-- For iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
<!-- For iPhone: -->
<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57x57-precomposed.png">


<!-- <link rel="stylesheet" href="css/jquery-ui.css" /> -->
<!-- <link rel="stylesheet" href="css/collageMaker.css?v=<?php echo rand(100000,1000000); ?>" /> -->
<link rel="stylesheet" href="css/bootstrap.min.css?v=1">
<link rel="stylesheet" href="css/font-awesome.css?v=1">
<link rel="stylesheet" href="css/docs.css"> 
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link rel="stylesheet" href="css/magazine-0.0.1.css?v=<?php echo rand(100000,1000000); ?>" />
<link rel="stylesheet" href="css/preloader.css">
<link rel="stylesheet" type="text/css" href="css/jquery-carousel.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-impromptu.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.lozenge.css" />
<link rel="stylesheet" type="text/css" href="css/autoSuggest.css" />

<style type="text/css">
/*hide unclip button if not on my own clipboard*/

html, body, h1, h2, h3, h4, h5, span, div, a, button, p, b {
  font-family: "museo-sans", "Helvetica Neue", "Trebuchet MS", Helvetica, Tahoma, Geneva, Arial, sans-serif;
}

body{
  background-color: #DDD;
  position: relative;
  padding-top: 80px;
}

ul.nav li.dropdown:hover ul.dropdown-menu{
    display: block;
}
ul.nav li.dropdown:hover ul.dropdown-menu li a {
    color:black;/*color turns white if not changed*/
    background: none;           
}
ul.nav li.dropdown:hover ul.dropdown-menu li a:hover {
    color:white;
    background: #002640;           
}

ul.nav ul.dropdown-menu {
 margin-top: 0px;
}

#boards {
  text-transform:capitalize;
  padding-bottom:10px;
}
.btn-small .modalbox{
  width:35px;
  box-shadow:none;
  margin:2px 0px;
} 
/*Styling for product fancybox starts here*/
.fancybox-inner{
  max-height: 800px;
}
.prodimage{
  background-color: #eee;
  padding: 4px;
  margin: 5px 5px;
  box-shadow: 2px 2px 2px #aaa;
}
.top-right-menu{
  line-height: 28px;
  height: 28px;
}
.btn.cliprproduct {
  width: 50%;
  padding: 6px;
  font-size: 90%;
  margin-bottom: 10px;

}

#paginationDiv{
  display: none;
}

.tooltip{
  z-index: 40000;
}
td.cliprproduct {
}

.newProdInfoCount{
  font-size:180%;
  padding-top:4px;
  border-right: 2px solid #888;
}

#newProdInfo>span{
  text-align: center;
}
.carousel .item > img {
  height: 400px;
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

#adjustableContainer{
  text-align: center;
}
#toplevelcontainer{
  text-align: left;
}

#infowell{
  width:940px
}
#maincontent{
  margin: 0px auto;
}
</style>

<style type="text/css">
.top-list-elem{
  text-align: left;
}
.shadow {
  -moz-box-shadow: 1px -1px 1px #AAAAAA;
  -webkit-box-shadow: 1px -1px 1px #AAAAAA;
  box-shadow: 1px -1px 1px #AAAAAA;
  padding-top: 5px;
  padding-bottom: 5px;
  padding-left: 5px;
  padding-right: 10px;
  /* For IE 8 */
  -ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=1, Direction=225, Color='#AAAAAA')";
  /* For IE 5.5 - 7 */
  filter: progid:DXImageTransform.Microsoft.Shadow(Strength=1, Direction=225, Color='#AAAAAA');
}
.gritterText{
  font-size:110%;
}

.gritterText a{
  color: #aaa;
  text-decoration: underline; 
}
.gritter-title a{
  color: #ccc;
  text-decoration: underline; 
}

.pager {
  margin: 0px;
}

</style>

<style type="text/css">

#noti_bubble {
    font-size: 80%;
    height: 18px;
    width: 18px;
    color: #222;
    position:absolute;
    top: 3px;
    right:2px;
    padding:2px;
    background-color: #91daf6;
    border: 1px solid #7cb7cd;
    background: -webkit-linear-gradient(top, #c2ecfa 0%, #71cef3 100%);
    background: -moz-linear-gradient(top, #c2ecfa 0%, #71cef3 100%);
    background: -ms-linear-gradient(top, #c2ecfa 0%, #71cef3 100%);
    background: -o-linear-gradient(top, #c2ecfa 0%, #71cef3 100%);
    background: linear-gradient(top, #c2ecfa 0%, #71cef3 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#c2ecfa', endColorstr='#71cef3',GradientType=0 );
    font-weight:bold;
    border-radius:30px;
    box-shadow:1px 1px 1px gray;
    text-align: center;
}
.prodimage img {
  height: 400px;
}
#noti_num {
  bottom: 0px;
  position: relative;
}

#back-top {
  position: fixed;
  bottom: 0px;
  right: 0px;
}

#back-top a {
  line-height: 60px;
  padding: 5px;
  width: 40px;
  background-color: #111;
  text-align: center;
  text-decoration: none;
  color: #ddd;
}

#back-top a:hover {
  background-color: #222;
}

#make-collage:hover {
  background-color: #222;
}

#make-collage {
  cursor: pointer;
  border-radius: 0px 6px 0px 0px;
  bottom: -10px;
  display: block;
  height: 55px;
  left: 0;
  position: fixed;
  background-color: #111;
  display: inline-block;
}

#make-collage a{
  color: #DDDDDD;
  display: inline-block;
  padding: 5px;
  text-align: center;
  text-decoration: none;
  width: 90px;
  height: 100%;
}
    
.centered {  
    border-radius: 12px;
    padding-top: 10px;
    padding-left: 7px; 
    padding-right: 7px; 
    margin: 0px;
}

.interest-alert-block {  
    text-align: center;  
    margin-right: 15%;
    margin-left: 15%;
    margin-top: 0px;
    margin-bottom: 50px;
    padding-bottom: 10px;
} 

.clipboards{
  margin-left:30px;
  width:160px;
  text-align: left;
  text-transform: capitalize;
}

.btn-add-interests {
    text-align:center; 
    margin:5px;
}
.scroll-pane,
.scroll-pane-arrows
{
  width: 100%;
  height: 200px;
  overflow: auto;
}

.clip-btn.btn-small{
  width: 61px;
}
#collagePreview{
  border: 2px solid #444; 
  border-radius: 4px;
}

#submitCollage{
  margin-top: 10px;
  border-radius: 0px;
  height: 25px;
  padding: 2% 5%;
  vertical-align: middle;
  line-height: 25px;
}

#tagbox{
  top:40%;
  z-index:10000;
  padding: 10px 10px 30px 10px;
  width: 500px;
}

.typeahead_wrapper { display: block; height: 30px; min-width: }
.typeahead_photo { float: left; height: 26px; margin-right: 5px; width: 26px;}
.typeahead_labels { float: left; height: 30px; }
.typeahead_primary { font-weight: bold;margin-left: 10px }

/*prevent input boundaries in autosuggest*/
.as-input{
  box-shadow: none;
  -webkit-box-shadow:none;
}

/*Longer name wraps up with three dots*/
#loggedUserName{
  white-space: nowrap;
  margin-right: 10px;
  max-width: 150px;
  overflow-x: hidden;
  overflow-y: hidden;
  text-overflow: ellipsis;
  display: inline-block;
  line-height: 28px;
  max-height: 28px;
}

/*modal ahead of fancybox*/
.modal-backdrop{
  z-index: 8040;
}

#banner-bg{
    left: 15px;
    position: absolute;
    z-index: -1;
    opacity: 0.5;
    width: inherit;
    height: inherit;
}
</style>

<script type="text/javascript" src="js/jquery.all.js?v=5"></script>
<script type="text/javascript" src="js/jquery.masonry.min.js"></script>
</head>
<body id="top">
<div id='fb-root'></div>
<script type="text/javascript">
  
  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));

  var fbloginStatusConfirmedVar = 0;

  function fbloginStatusConfirmed() {
    fbloginStatusConfirmedVar = 1;  
  }

  function openLink(link) {
    window.location.href=link;
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '143944345745133', // App ID
      xfbml    : true,
      cookie   : true,
      status   : true
    });
    FB.Event.subscribe('auth.login', function(response) {
      if($.cookie('loggedUserId') == "" && areCookiesEnabled())
        $.ajax({
          type: "GET",
          url: window.location.pathname.substr(8),
          data: {login: true}
        }).done(function(msg){
          $('#refreshdiv').html(msg);
          resetbindings();
          bindProductFancyBox();
        });
    });

    FB.Event.subscribe('auth.logout', function(response) {
      window.location.href='../output/logout';
    });

    FB.getLoginStatus(function(response) {
    if (response.status === 'connected') {
      if(!$.cookie('loggedUserId') && areCookiesEnabled()){
        // $('#modalLogging').modal('show');
        $.ajax({
          type: "GET",
          url: '../fbExtractor/getfbToken',
          data: {login: true,origin: window.location.href}
        }).done(function(msg){
          var url = window.location.pathname.substr(8);
          if(window.location.pathname == "/")
            url="/";
          $.ajax({
            type: "GET",
            url: url,
            data: {login: true}
          }).done(function(page){
            $('#refreshdiv').html(page);
            resetbindings();
            bindProductFancyBox();
            // $('#modalLogging').modal('hide');
          });
        });
      }
    } else if (response.status === 'not_authorized') {
      // the user is logged in to Facebook, 
      // but has not authenticated your app
      // do nothing
    } else {
      // the user isn't logged in to Facebook.
      // do nothing
    }
   });
  }

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34166167-1']);
  _gaq.push(['_setDomainName', 'clipr.in']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script id="_webengage_script_tag" type="text/javascript">
  window.webengageWidgetInit = window.webengageWidgetInit || function(){
    webengage.init({
      licenseCode:"~47b66698"
    }).onReady(function(){
      webengage.render();
    });
  };

  (function(d){
    var _we = d.createElement('script');
    _we.type = 'text/javascript';
    _we.async = true;
    _we.src = (d.location.protocol == 'https:' ? "//ssl.widgets.webengage.com" : "//cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-3.0.js";
    var _sNode = d.getElementById('_webengage_script_tag');
    _sNode.parentNode.insertBefore(_we, _sNode);
  })(document);
</script>
<div id="refreshdiv">