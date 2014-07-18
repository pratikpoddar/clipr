<?php

session_start();

if (empty($_POST))
{
	$sourcefile = 'tolmolkebol-baadshah.php';
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
        $_SESSION['pname']="Roulette Drinking Game Spin and Shot";
		$_SESSION['plink']="https://itsourstudio.com/ProductDescription.php?cat=Party&pid=286";
		$_SESSION['pimg']="https://itsourstudio.com/admin/products_image/ormcjp9m1130954186ROULETTE%20DRINKING%20GAME%20SPIN%20AND%20SHOT.jpg";
		$_SESSION['poption1']="~800 INR";
		$_SESSION['poption2']="~1,200 INR";
		$_SESSION['poption3']="~1,600 INR";
		$_SESSION['poption4']="~2,000 INR";
        break;
    case 2:
        $_SESSION['pname']="Air Guitar (Music by Laser)";
		$_SESSION['plink']="https://itsourstudio.com/ProductDescription.php?cat=Party&pid=278";
		$_SESSION['pimg']="https://itsourstudio.com/admin/products_image/kdbuwjq4188087181Air%20Guitar%202.jpg";
		$_SESSION['poption1']="~700 INR";
		$_SESSION['poption2']="~1,000 INR";
		$_SESSION['poption3']="~1,300 INR";
		$_SESSION['poption4']="~1,600 INR";
        break;
    case 3:
        $_SESSION['pname']="Here is my Gun - Men's T-shirt";
		$_SESSION['plink']="http://bewakoof.com/Mens-T-Shirts/wasseypurworld/i-am-a-hunter-gun-official-gangs-of-wasseypur-t-shirt";
		$_SESSION['pimg']="http://bewakoof.com/image/cache/data/test/tshirtimages/13443189985190626-480x540.jpg";
		$_SESSION['poption1']="~300 INR";
		$_SESSION['poption2']="~400 INR";
		$_SESSION['poption3']="~500 INR";
		$_SESSION['poption4']="~600 INR";
        break;
    case 4:
        $_SESSION['pname']="Mumbai ka Shot Glasses";
		$_SESSION['plink']="http://shop.seventymm.com/Product/5/Buy-Home-and-Lifestyle-Mug-and-Glasses-Shot-Glasses-Mumbai-ka-Shot-Glasses/32584/2453/Show";
		$_SESSION['pimg']="http://images.seventymm.com/Img/Item/ItemPoster/32584.jpg";
		$_SESSION['poption1']="~150 INR";
		$_SESSION['poption2']="~180 INR";
		$_SESSION['poption3']="~220 INR";
		$_SESSION['poption4']="~260 INR";
        break;
    case 5:
        $_SESSION['pname']="Burberry Brit EDT - 100 ml";
		$_SESSION['plink']="http://www.flipkart.com/burberry-brit-edt-100-ml/p/itmd9gegcymct7ve?affid=pratikphod";
		$_SESSION['pimg']="http://img6.flixcart.com/image/perfume/z/h/9/eau-de-toilette-men-burberry-100-brit-400x400-imad9h62stphz5fz.jpeg";
		$_SESSION['poption1']="~3000 INR";
		$_SESSION['poption2']="~3700 INR";
		$_SESSION['poption3']="~4400 INR";
		$_SESSION['poption4']="~5100 INR";
        break;	
    case 6:
        $_SESSION['pname']="Domyos Foam Dumbell 2 Kg";
		$_SESSION['plink']="http://www.snapdeal.com/product/domyos-foam-dumbell-2-kg/216749";
		$_SESSION['pimg']="http://i4.sdlcdn.com/img/product/main/523729_Domyos_Three6_M_1_2x.jpg";
		$_SESSION['poption1']="~600 INR";
		$_SESSION['poption2']="~800 INR";
		$_SESSION['poption3']="~1000 INR";
		$_SESSION['poption4']="~1200 INR";
        break;
    case 7:
        $_SESSION['pname']="Water Wars Game";
		$_SESSION['plink']="https://itsourstudio.com/ProductDescription.php?cat=Outdoor&pid=40";
		$_SESSION['pimg']="https://itsourstudio.com/admin/products_image/gunb9nyy1157079576WATER-WAR-PISTOL-GAME.jpg";
		$_SESSION['poption1']="~900 INR";
		$_SESSION['poption2']="~1200 INR";
		$_SESSION['poption3']="~1500 INR";
		$_SESSION['poption4']="~1800 INR";
        break;
    case 8:
        $_SESSION['pname']="Kamachi Skate Board";
		$_SESSION['plink']="http://www.snapdeal.com/product/kamachi-skate-board-large/186832?storeID=sports-hobbies_fp_186832";
		$_SESSION['pimg']="http://i1.sdlcdn.com/img/product/main/101945_SPORTSHUB_Kamachi_M_1_2x.jpg";
		$_SESSION['poption1']="~650 INR";
		$_SESSION['poption2']="~850 INR";
		$_SESSION['poption3']="~1050 INR";
		$_SESSION['poption4']="~1250 INR";
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

$_SESSION['pans1'] = 2;
$_SESSION['pans2'] = 3;
$_SESSION['pans3'] = 2;
$_SESSION['pans4'] = 3;
$_SESSION['pans5'] = 2;
$_SESSION['pans6'] = 3;
$_SESSION['pans7'] = 2;
$_SESSION['pans8'] = 3;


?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">

<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	
	<meta property="og:title" content="Tol Mol ke Bol - Baadshah @ Clipr.in"/>
	<meta property="og:type" content="company"/>
	<meta property="og:url" content="http://clipr.in/games/tolmolkebol-baadshah.php"/>
	<meta property="og:image" content="http://www.clipr.in/image/clipr_app_logo.png"/>
	<meta property="og:site_name" content="Tol Mol ke Bol - Baadshah @ Clipr.in"/>
	<meta property="og:description"
		content="Shopping Redefined!"/>
	  
	<title>Tol Mol ke Bol - Baadshah</title>
	<!-- TODO: Facebook Like not working -->
	
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
		top: 400;
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
			
				<h1 style="color:#306790; font-weight:bolder; font-family: Verdana, Arial, sans-serif;">Tol Mol ke Bol - Baadshah !</h1> 
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
						xfbml: true});  };  
				(function() {}());
				
					function publish_to_wall() {
					FB.ui({
					  method: 'stream.publish',
					  name: 'Tol Mol ke Bol - Baadshah @ Clipr.in',
					  picture: 'http://clipr.in/image/clipr_app_logo.png',
					  link: 'http://clipr.in/games/tolmolkebol-baadshah.php',
					  caption: '<?php echo "{*actor*} just enjoyed the game at Clipr.in. {*actor*} got ".$_SESSION['CORRECT']." out of 8 correct!";?>',
					  description: 'Shopping will be redefined in November 2012. Clipr.in coming soon!'
					},
					 function(response) {
						if (response && response.post_id) {
						} else {		
						}

					
				}
				);}
					
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
												<form action="tolmolkebol-baadshah.php" id="submitform" method="post" style="display: none;">
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
