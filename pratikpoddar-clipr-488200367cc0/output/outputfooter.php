<?php 
	require_once('../output/utils.php');

	// print a hidden div for login which can be activated when desired in case of logged out browsing of home page
	if($_GET['pagetype']='prodlistbase' && isset($_GET['page']) && ($_GET['page'] == 'trending' || $_GET['page'] == 'tagged') && $userID == 0 && $loggedUserID == 0){
		echo '<div id = "trendingLoginDiv" class="row-fluid" style="display:none;text-align:center;font-size: 20px;">
				<a class="loginlink" href="javascript:void(0);"><img src="./img/facebook-connect-large.png"></a>&nbsp;&nbsp; to see more products<br>
			</div><br/><br/><br/>';
	}
?>
<style type="text/css">
#make-collage{
	z-index: 2000;
	display: none;
}
</style>
<ul id="addProductScripts" style='text-align:center;' >
  <li style='display:none;'>1</li>
</ul>
<footer class="well" style="margin-top: 100px;">
	<div class="row-fluid">
		<div class="span1">	</div>
		<div class="span3">
			<h4>Clipr</h4>
			<p><a href="about" class="ajax-link" target="_blank">About us</a></p>
			<p><a href="contact" class="ajax-link" target="_blank">Contact</a></p>
			<p><a href="terms" class="ajax-link" target="_blank">Terms and Conditions</a></p>
			<p><a href="help" class="ajax-link" target="_blank">How to use Clipr?</a></p>
			<p><a href="faq" class="ajax-link" target="_blank">FAQ</a></p>
			<br/><br/>
		</div>
		<div class="span4">
			<h4>Follow us On</h4><br/>
			<p style="font-size: 32px;">
  			<span><a style="text-decoration:none" href="https://twitter.com/clipr_in" target="_blank"><i id="ticon" class="icon-twitter-sign"></i></a></span>
  			<span style="padding:10px"><a style="text-decoration:none" href="http://www.linkedin.com/company/clipr" target="_blank"><i id="licon" class="icon-linkedin-sign"></i></a></span>
  			<span style="padding:10px"><a style="text-decoration:none" href="https://plus.google.com/100547954237986855189" target="_blank"><i id="gicon" class="icon-google-plus-sign"></i></a></span>
  			<span style="padding:10px"><a style="text-decoration:none" href="https://www.facebook.com/Clipr.in" target="_blank"><i id="ficon" class="icon-facebook-sign"></i></a></span>
  			<br/><br/><br/>
		</div>
		<div class="span3">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
  				var js, fjs = d.getElementsByTagName(s)[0];
  				if (d.getElementById(id)) return;
  				js = d.createElement(s); js.id = id;
  				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=298391503600517";
  				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like-box" data-href="http://www.facebook.com/Clipr.in" data-width="292" data-show-faces="true" data-stream="false" data-header="false"></div>
			<br/><br/>
		</div>
		<div class="span1">	</div>
	</div>		
</footer>

<!-- End of Div id = Container Tag -->
</div>

<span id="back-top" style="padding-bottom:10px">
	<a href="javascript:void(0);" onClick="$('html, body').animate({scrollTop:0}, 'medium');">Back to Top</a>
</span>
<!-- <span id="make-collage" >
	<a href="javascript:void(0);">Make Collage</a>
</span> -->
 <script type="text/javascript">
// 	$("#ticon").hover(function() {$("#ticon").addClass('icon-twitter').removeClass('icon-twitter-sign');}, function() {$("#ticon").addClass('icon-twitter-sign').removeClass('icon-twitter');} );
// 	$("#licon").hover(function() {$("#licon").addClass('icon-linkedin').removeClass('icon-linkedin-sign');}, function() {$("#licon").addClass('icon-linkedin-sign').removeClass('icon-linkedin');} );
// 	$("#gicon").hover(function() {$("#gicon").addClass('icon-google-plus').removeClass('icon-google-plus-sign');}, function() {$("#gicon").addClass('icon-google-plus-sign').removeClass('icon-google-plus');} );
// 	$("#ficon").hover(function() {$("#ficon").addClass('icon-facebook').removeClass('icon-facebook-sign');}, function() {$("#ficon").addClass('icon-facebook-sign').removeClass('icon-facebook');} );
	
 </script>

<script type="text/javascript" src="js/renderProduct-0.0.2.js?v=<?php echo rand(100000,1000000); ?>"></script>
<!--<script type="text/javascript" src="js/collageMaker.js?v=<?php //echo rand(100000,1000000); ?>"></script>-->

<?php
  require 'sqlcloser.php';
?>

