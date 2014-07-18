<?php

session_start();

if (empty($_POST))
{
	$sourcefile = 'tolmolkebol-begum.php';
	include 'welcometmkb.php';
	exit("");
}

$_SESSION['lastanswer']=$_POST['lans'];
$_SESSION['lastprob']='problem'.$_POST['lpid'];
$_SESSION['currprob']='problem'.$_POST['cpid'];
$_SESSION['cpid']=$_POST['cpid'];
$_SESSION['lpid']=$_POST['lpid'];


switch ($_SESSION['cpid']) {
    case 1:
        $_SESSION['pname']="The Rhyah Pendant - Gold pendant with Green stone";
		$_SESSION['plink']="http://www.bluestone.com/pendants/the-rhyah-pendant~838.html";
		$_SESSION['pimg']="http://img1.bluestone.com/7806/the-rhyah-pendant.jpg";
		$_SESSION['poption1']="~1,500 INR";
		$_SESSION['poption2']="~3,000 INR";
		$_SESSION['poption3']="~5,000 INR";
		$_SESSION['poption4']="~7,000 INR";
        break;
    case 2:
        $_SESSION['pname']="Funky Butterfly Earring";
		$_SESSION['plink']="http://www.craftsvilla.com/catalog/product/view/id/88217/s/funky-earring/category/34/";
		$_SESSION['pimg']="http://d1g63s1o9fthro.cloudfront.net/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/3/_/3_126_1.jpg";
		$_SESSION['poption1']="~200 INR";
		$_SESSION['poption2']="~300 INR";
		$_SESSION['poption3']="~400 INR";
		$_SESSION['poption4']="~500 INR";
        break;
    case 3:
        $_SESSION['pname']="Beautiful Long Color Beads Stone Earring";
		$_SESSION['plink']="http://www.craftsvilla.com/beautiful-long-light-weight-silver-alloy-oxidized-color-beads-stone-earring-dsc04620.html";
		$_SESSION['pimg']="http://d1g63s1o9fthro.cloudfront.net/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/d/s/dsc04620_1.jpg";
		$_SESSION['poption1']="~50 INR";
		$_SESSION['poption2']="~100 INR";
		$_SESSION['poption3']="~200 INR";
		$_SESSION['poption4']="~300 INR";
        break;
    case 4:
        $_SESSION['pname']="Gangs of Wasseypur Womaniya - Girls T-Shirt";
		$_SESSION['plink']="http://bewakoof.com/wasseypurworld/womaniya-gangs-of-wasseypur-girls-t-shirt2";
		$_SESSION['pimg']="http://bewakoof.com/image/cache/data/test/tshirtimages/13443365347116957-480x540.jpg";
		$_SESSION['poption1']="~200 INR";
		$_SESSION['poption2']="~300 INR";
		$_SESSION['poption3']="~400 INR";
		$_SESSION['poption4']="~500 INR";
        break;
    case 5:
        $_SESSION['pname']="Bombay Dyeing Bed Sheet Set";
		$_SESSION['plink']="http://www.fabfurnish.com/Bombay-Dyeing--Bed-Sheet-Set-15870.html";
		$_SESSION['pimg']="http://static.fabfurnish.com/p/bombay-dyeing-6481-07851-1-zoom.jpg";
		$_SESSION['poption1']="~900 INR";
		$_SESSION['poption2']="~1100 INR";
		$_SESSION['poption3']="~1300 INR";
		$_SESSION['poption4']="~1500 INR";
        break;	
    case 6:
        $_SESSION['pname']="Denim Short Skirt";
		$_SESSION['plink']="http://www.fashionara.com/ladies/shorts-skirts/denim-short-skirt.html";
		$_SESSION['pimg']="http://content.fashionara.com/catalog/product/cache/1/image/1500x2000/9df78eab33525d08d6e5fb8d27136e95/2/1/212119-april2nd-1jq0232126_.jpg";
		$_SESSION['poption1']="~900 INR";
		$_SESSION['poption2']="~1100 INR";
		$_SESSION['poption3']="~1400 INR";
		$_SESSION['poption4']="~1600 INR";
        break;
    case 7:
        $_SESSION['pname']="Red stilettos - Peppy Peep Toe Pumps";
		$_SESSION['plink']="http://www.fashionara.com/footwear/ladies/peppy-peep-toe-pumps-1.html";
		$_SESSION['pimg']="http://content.fashionara.com/catalog/product/cache/1/image/1500x2000/9df78eab33525d08d6e5fb8d27136e95/c/l/cll-2445-s2fcltfsho031rd139_10232.jpg";
		$_SESSION['poption1']="~2000 INR";
		$_SESSION['poption2']="~3000 INR";
		$_SESSION['poption3']="~4000 INR";
		$_SESSION['poption4']="~5000 INR";
        break;
    case 8:
        $_SESSION['pname']="Burberry Brit Fragrance (for women)";
		$_SESSION['plink']="http://www.flipkart.com/burberry-brit-edt-100-ml/p/itmd9utrutwkegha?affid=pratikphod";
		$_SESSION['pimg']="http://img5.flixcart.com/image/perfume/h/k/j/eau-de-toilette-women-burberry-100-brit-400x400-imad9yftzh7z5xhd.jpeg";
		$_SESSION['poption1']="~2900 INR";
		$_SESSION['poption2']="~3300 INR";
		$_SESSION['poption3']="~3700 INR";
		$_SESSION['poption4']="~4100 INR";
        break;
    default:
		$_SESSION['pname']="";
		$_SESSION['plink']="";
		$_SESSION['pimg']="";
		$_SESSION['poption1']="";
		$_SESSION['poption2']="";
		$_SESSION['poption3']="";
		$_SESSION['poption4']="";
        break;
				
}

$_SESSION['pans1'] = 3;
$_SESSION['pans2'] = 2;
$_SESSION['pans3'] = 2;
$_SESSION['pans4'] = 3;
$_SESSION['pans5'] = 3;
$_SESSION['pans6'] = 2;
$_SESSION['pans7'] = 2;
$_SESSION['pans8'] = 2;


?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">

<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	
	<meta property="og:title" content="Tol Mol ke Bol - Begum @ Clipr.in"/>
	<meta property="og:type" content="company"/>
	<meta property="og:url" content="http://clipr.in/games/tolmolkebol-begum.php"/>
	<meta property="og:image" content="http://www.clipr.in/image/clipr_app_logo.png"/>
	<meta property="og:site_name" content="Tol Mol ke Bol - Begum @ Clipr.in"/>
	<meta property="og:description"
		content="Shopping Redefined!"/>
	  
	<title>Tol Mol ke Bol - Begum</title>

	<!-- TODO: Facebook Like not working -->
	<!-- TODO: Facebook post not working at the end sometimes -->
	
	<!-- Some css/js code by www.thefancy.com . We love your design and your code. Thank You -->
	<link rel="stylesheet" media="all" type="text/css" href="./tolmolkebol/main.new.css">
	<link rel="stylesheet" media="all" type="text/css" href="./tolmolkebol/main.css">	
	<link rel="stylesheet" type="text/css" media="all" href="./tolmolkebol/photo.css">

	<style type="text/css">
	.cliprpower
	{
		border:2px solid;
		border-top-right-radius:20px;
		border-bottom-right-radius:20px;
		-moz-border-top-right-radius:20px;
		-moz-border-bottom-right-radius:20px;
		z-index: 10;
		position: absolute;
		position: fixed;
		left: 0;
		top: 30%;
		padding-left: 1em;
		background-color:#FFFFFF;
	} 
	
	.tmkbbutton big 
	{
	   border-top: 1px solid #8c8c8c;
	   background: #000000;
	   background: -webkit-gradient(linear, left top, left bottom, from(#6b6b6b), to(#000000));
	   background: -webkit-linear-gradient(top, #6b6b6b, #000000);
	   background: -moz-linear-gradient(top, #6b6b6b, #000000);
	   background: -ms-linear-gradient(top, #6b6b6b, #000000);
	   background: -o-linear-gradient(top, #6b6b6b, #000000);
	   padding: 5.5px 11px;
	   -webkit-border-radius: 5px;
	   -moz-border-radius: 5px;
	   border-radius: 5px;
	   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   box-shadow: rgba(0,0,0,1) 0 1px 0;
	   text-shadow: rgba(0,0,0,.4) 0 1px 0;
	   color: #ffffff;
	   font-size: 15px;
	   font-family: Helvetica, Arial, Sans-Serif;
	   text-decoration: none;
	   vertical-align: middle;
	}
	.tmkbbutton:hover big 
	{
	   border-top-color: #1b1e1f;
	   background: #1b1e1f;
	   color: #ffffff;
	}
	.tmkbbutton:active big 
	{
	   border-top-color: #000000;
	   background: #000000;
	}
   
    .wrapper-content 
	{
		box-shadow: 1px 1px 5px #AAA;
	}
	
	.page-home .container {
		padding-top: 0px;
	}
	
	</style>



</head>

<body class="lang-en no-subnav page-home" style="overflow: inherit; ">

	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=170592559743815";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	

	<div class="cliprpower" align="center">
		<h2>Powered by <br/><a href="http://clipr.in" target="_blank"><img alt="Clipr.in" width="150px" src="http://clipr.in/image/clipr_fullname_logo_small.jpg"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<br/>Launching in </h2><span style="font-size:160%">Nov 2012&nbsp;&nbsp;</span><br/><br/>
		<h2><div class="fb-like" data-href="https://www.facebook.com/Clipr.in" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true" data-font="arial"></div></h2>
	</div>

	<div class="container ">

		<div id="content">
			
			<div class="teaser" align="center">
			
				<h1 style="color:#306790; font-weight:bolder; font-family: Verdana, Arial, sans-serif;">Tol Mol ke Bol - Begum !</h1> 
				<p style="color:#306790">Guess the price, show your shopping wit and discover amazing stuff!</p>
				<br/>
				<h2 style="width:350px">
				<?php
				// store answer submitted in session data
				$_SESSION[$_SESSION['lastprob']]=$_SESSION['lastanswer'];
				if ($_SESSION['lpid']>0)
				{
					if ($_SESSION['lastanswer']==$_SESSION['pans'.$_SESSION['lpid']])
					{	
						$_SESSION['CORRECT']++;
						echo "<font color=\"#027339\">";
						echo "Correct Answer! Score: ".$_SESSION['CORRECT']."<br/> Price of ".$_SESSION['lastpname']." was indeed ".$_SESSION['lastpans'];
						echo "! <a href=\"".$_SESSION['lastplink']."\" target=\"_blank\">Want to Buy?</a></font>";
					}
					else
					{	
						echo "<font color=\"#A61414\">";
						echo "Wrong Answer! Score: ".$_SESSION['CORRECT']."<br/> Price of ".$_SESSION['lastpname']." was actually ".$_SESSION['lastpans'];
						echo "! <a href=\"".$_SESSION['lastplink']."\" target=\"_blank\">Want to Buy?</a></font>";
					}	
					
				}
				
				else
				{
						$_SESSION['CORRECT']=0;
						echo "<font color=\"#233138\">";
						echo "Let the game begin!<br/>";
						echo "Hover on the image to see your options!<br/>";
						echo "Best of Luck!";
						echo "</font>";
				}

				?>
				</h2>
			</div>
			<!-- / teaser -->

			<script src="http://connect.facebook.net/en_US/all.js"></script>
			<div id="fb-root"></div>

				
			<script type="text/javascript">// <![CDATA[
				window.fbAsyncInit = function() {
				
				FB.init({appId:	170592559743815,
						status: true,
						cookie: true,  
						xfbml: true});
				
						};  
				
				var e = document.createElement('script'); 
				e.async = true;
				e.src = document.location.protocol +
					'//connect.facebook.net/en_US/all.js';
				document.getElementById('fb-root').appendChild(e);				
				
				function publish_to_wall() {
					FB.ui({
						method: 'stream.publish',
						name: 'Tol Mol ke Bol - Begum @ Clipr.in',
						picture: 'http://clipr.in/image/clipr_app_logo.png',
						link: 'http://clipr.in/games/tolmolkebol-begum.php',
						caption: '<?php echo "{*actor*} just enjoyed the game at Clipr.in. {*actor*} got ".$_SESSION['CORRECT']." out of 8 correct!";?>',
						description: 'Shopping will be redefined in November 2012. Clipr.in coming soon!'
						},
						function(response) {
							if (response && response.post_id) {} else {}
						}
					);
				}
					
			// ]]></script>
			
			
			<div class="wrapper-content rounded-4 box-shadow-1">
	
				<!-- / customize -->

				<ol class="stream" loc="_home_">
					<li>
						<div class="figure-row">
							<div class="figure-product figure-640">
								<a class="anchor"></a>
								
									<script src="./tolmolkebol/jquery-1.7.1.min.js"></script>
									<figure>
										<span class="wrapper-fig-image">
											<span class="fig-image">
												<?php if ($_SESSION['lpid']<8) echo "<img src=\"".$_SESSION['pimg']."\">"; ?>
												<?php if ($_SESSION['lpid']==8) {
												
													echo "<h2> Thanks for playing the game. We hope you enjoyed it.</h2>";
													echo "<h2> You got ".$_SESSION['CORRECT']." out of 8 correct!</h2>";
													echo "<h2> <a href=\"http://clipr.in/team.html\" target=\"_blank\">Clipr Team</a> looks forward to help you shop online from Nov 2012. Take Care!</h2>";	
													echo "<h2> To get a free gift coupon and participate in the lucky draw, please register <a href=\"http://clipr.in/\" target=\"_blank\">here</a></h2>";	
													echo "<script type=\"text/javascript\">$(document).ready(publish_to_wall());</script>";
												} ?>												
												
												<span class="timeline">
												<sup></sup>
												<table width="100%"><tr>
												<form action="tolmolkebol-begum.php" id="submitform" method="post" style="display: none;">
													<input type="hidden" id="cpid" name="cpid" value="<?php echo ($_SESSION['cpid']+1); ?>" />
													<input type="hidden" id="lpid" name="lpid" value="<?php echo ($_SESSION['cpid']); ?>"/>
													<input type="hidden" id="lans" name="lans" value=""/>
												</form>
												<td class="tmkbbutton" style="cursor: pointer" width="25%" align="center" onclick="javascript: $('#lans').val(1); document.getElementById('submitform').submit()"><big><b><?php echo $_SESSION['poption1']; ?></b></big></td>
												<td class="tmkbbutton" style="cursor: pointer" width="25%" align="center" onclick="javascript: $('#lans').val(2); document.getElementById('submitform').submit()"><big><b><?php echo $_SESSION['poption2']; ?></b></big></td>
												<td class="tmkbbutton" style="cursor: pointer" width="25%" align="center" onclick="javascript: $('#lans').val(3); document.getElementById('submitform').submit()"><big><b><?php echo $_SESSION['poption3']; ?></b></big></td>
												<td class="tmkbbutton" style="cursor: pointer" width="25%" align="center" onclick="javascript: $('#lans').val(4); document.getElementById('submitform').submit()"><big><b><?php echo $_SESSION['poption4']; ?></b></big></td>
												</tr></table>
												</span>
											</span>
										</span>
										<figcaption><?php if ($_SESSION['lpid']<8) echo "(Product #".$_SESSION['cpid'].") "; ?><h2><?php if ($_SESSION['lpid']<8) echo $_SESSION['pname']; ?></h2></figcaption>
									</figure>
							</div>
						</div>
					</li>
				</ol>
				
			</div>
			
		</div>
		<!-- / content -->


	</div>
	<!-- / container -->

	<!-- Some css/js code by www.thefancy.com . We love your design and your code. Thank You -->
	<script src="./tolmolkebol/jquery-1.7.1.min.js"></script>
	<script src="./tolmolkebol/jquery-ui-1.8.12.custom.min.js"></script>
	<script src="./tolmolkebol/jquery.fullscreen.js"></script>
	<script src="./tolmolkebol/jquery.fancybox-1.3.4.pack.js"></script>
	<script src="./tolmolkebol/jquery.hoverIntent.minified.js"></script>
	<script src="./tolmolkebol/jquery.tipsy.js"></script>
	<script src="./tolmolkebol/browser_detect.js"></script>
	<script src="./tolmolkebol/main.js"></script>
	

<?php

$_SESSION['lastpname']=$_SESSION['pname']; 
$_SESSION['lastplink']=$_SESSION['plink']; 
$_SESSION['lastpans']=$_SESSION['poption'.$_SESSION['pans'.$_SESSION['cpid']]]; 

if ($_SESSION['lpid']==8) {
	session_destroy();
	}
	
?>

</body>
<script type="text/javascript">

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

</html>
