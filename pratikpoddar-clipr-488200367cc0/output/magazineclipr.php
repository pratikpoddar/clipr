<style type="text/css">
	.clipdiv{
		display: inline;
	}
	.magazinebutton{
		width: 30px;
	}
</style>

<div class="row-fluid" id = "sidebar">
  <?php 
    require 'span2bar.php';
  ?>
</div>
<div class="row-fluid" id="heading">
  <?php 
    require 'heading.php';
  ?>
</div>

<?php
	function getClippedProducts($con, $uid){
		$sql = "SELECT productid,clipTag as board from clipsTable where userid =  $uid order by clipTag";

		$result = mysql_query($sql,$con);
		if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

		$products = array();

		while($row = mysql_fetch_array($result))
			array_push($products, $row);
		return $products;
	}

	function newBoardHtml($board, $reqNewPage){
		$html = "";
		if($reqNewPage != 0)
			$html = '<div class="mag-container"><div  class="pageContainer"><div class="mag-shadow oddshadow"><img src="img/magazine-left.png"/>	</div></div></div>';

		$html = $html.'
		<div class="mag-container">
			<div class="pageContainer">
				<div class="mag-shadow evenshadow"><img src="img/magazine-right.png"/>	</div>
				<div class="row-fluid">
					<span class="span12" style="height:60px;top:195px;position:absolute;">
						<h2 style="text-transform:capitalize;">'.$board.'</h2> 
					</span>
				</div>
			</div>
		</div>';
		return $html;
	}
	if(isset($_GET['userId']))
		$uid = $_GET['userId'];
	else if($loggedUserID != 0)
		$uid = $loggedUserID;
	else
		die('Error: Login or specify a user ');	

	$board = "";
	if(isset($_GET['board']))
		$board = $_GET['board'];

	$uinfo = getUserInfo($con,$uid);
	$uname = $uinfo['firstname']." ".$uinfo['lastname'];

	$products = getClippedProducts($con, $uid);

	$pinfos=array();

	foreach ( $products as $product ) {
		$pid = $product['productid'];
		$pinfo = getProdInfoForUser($con, $pid, $loggedUserID);

		$pinfo['board'] = str_replace("_", " ", $product['board']);

		$absProdUrl = getAbsoluteUrl("../output/product/".$pid);

		$listofimages = explode('$$$', $pinfo['image']);
		$buybutton = "<a style=\"text-decoration:none\" rel=\"tooltip\" data-placement=\"top\" data-original-title=\"Go to the seller's website\" href=\"cliprtransfer?link=".($pinfo['link'])."\" target='_blank'><i class='icon-shopping-cart'></i></a>";
		if($loggedUserID == 0){
			$clipbutton = "<a rel=\"tooltip\" data-placement=\"top\" data-original-title=\"You 'Clip' what you 'Like'\" href=\"javascript:void(0);\" style=\"text-decoration:none;\" onClick='showLoginError(\"clip\", \"\")'><i class = \"icon-paper-clip \"></i></a>";
		}else{
			$clipbutton = "<a rel=\"tooltip\" data-placement=\"top\" data-original-title=\"You 'Clip' what you 'Like'\" href=\"javascript:void(0);\" style=\"text-decoration:none;\" onClick='clipMag(\".clipbtn-".$pid."\", ".$pid.")'><i class = \"icon-paper-clip \"></i></a>";
		}
		$pinfo['clipbutton'] = $clipbutton;
		$pinfo['buybutton'] = $buybutton;
		array_push($pinfos, $pinfo);
	}
?>

		<!-- Front Page End  -->
		<?php
			function get_snippet( $str, $wordCount = 10 ) 
			{
				$ret = implode('',array_slice(preg_split('/([\s,\.;\?\!]+)/',$str,$wordCount*2+1,PREG_SPLIT_DELIM_CAPTURE),0,$wordCount*2-1));
				if(trim($ret) != "")
					return "   ".$ret;
				return $ret;
			}
			
			$maxprod=count($pinfos);
				$oldboard = "";

			$boardcount = 0;

			$boardstarts = array();
			$imgpages = array();
			$pagesDone = 1;
			$maghtml = '
			<div id="controllers">
				<div class="pages shadows" id="magazine">
					<!-- Home -->

					<!-- Front Page  -->
					<div>
						<div class="row-fluid" style="margin-top: 50px;">
							<h2 style="text-align: center; padding:5px">'.$uname.'\'s Clipboard</h2>
						</div>
						<div class="row-fluid" style="margin-top: 40px;">
							<span class="span3"></span> 
							<span class="span6" style="text-align:center">
								<img src="http://graph.facebook.com/'.$uid.'/picture?type=large" style="max-height:200px">
							</span>
							<span class="span3"></span>
						</div>
						<div class="row-fluid" style="position: absolute;bottom: 20px;">
							<span class="span4"></span> 
							<span class="span4"><img src="../image/clipr_full_new.png"></span>
							<span class="span4"></span> 
						</div>
					</div>';
			for ($i = 0; $i < $maxprod; $i++)
			{
				if($pinfos[$i]['clipunclip'] == "Unclip")
					$clipclass = "disabled";
				else
					$clipclass = "enabled";

				$pid = $pinfos[$i]['productid'];
				$pinfo = $pinfos[$i];
				$listofimages = explode('$$$', $pinfo['image']);

				if($pinfo['board'] != $oldboard){
					$oldboard = $pinfo['board'];
					$maghtml = $maghtml."".newBoardHtml($pinfo['board'],$pagesDone%2);
					$pagesDone = $pagesDone + 1 + ($pagesDone)%2;
					$boardstart = array("board" => $pinfo['board'] , "start" => $pagesDone,"img" => getSmallImage($listofimages[0]));
					array_push($boardstarts, $boardstart);
				}

				if($pagesDone%2==0){
					$shadowimg = '<div class="mag-shadow evenshadow">
							<img src="img/magazine-right.png"/>	
						</div>';
					$pagetype='even-container';
				}
				else{
					$shadowimg = '<div class="mag-shadow oddshadow">
						<img src="img/magazine-left.png"/>	
					</div>';
					$pagetype='odd-container';
				}

				$imgpage = array("title" => $pinfo['title'], "page" => $pagesDone+1, "img" => getSmallImage($listofimages[0]));
				array_push($imgpages, $imgpage);

				$maghtml = $maghtml.'
				<div class="mag-container">
					<div  class="pageContainer">
						</br>
						<div class="mag-prodimg '.$pagetype.'" style="margin-top:50px;">
							<div class="mag-btns mag-btns-'.$pagetype.'">
								<div class="mag-btn mag-clipbtn clipbtn-'.$pid.'" id="clipbtn-'.$i.'" style="line-height:20px">
									'.$pinfo['clipbutton'].'
								</div>
								<div class="mag-btn mag-clipcount-btn countbtn-"'.$pid.' id="countbtn-'.$i.'" style="line-height:12px;font-size:10px;">
									<a class="modalbox" href = "#userinfo" onClick="populateClippers('.$pinfo['productid'].')" >'.$pinfo['clipcount'].'</a>
								</div>
								<div class="mag-btn mag-buybtn" id="buybtn-'.$i.'">
									'.$pinfo['buybutton'].'
								</div>
								<div class="mag-btn mag-sharebtn" id="sharebtn-'.$i.'">
									<a 
										rel="tooltip" 
										data-placement="top" 
										data-original-title="Share this Clipboard"
										style="text-decoration:none;" 
										href="javascript:void(0);" 
										onClick="fbPublish(\'Checkout this clipboard by '.$uname.'\',\'http://clipr.in/output/clips/'.$uid.'\',\''.getBigImage($listofimages[0]).'\',\'Clipboard of '.$uname.'\',\'Now Explore awesome product collections by fellow clippers in a new fun way. Go build your own collection at Clipr\')" 
									>
										<i class="icon-facebook-sign"></i>
									</a>
								</div>
							</div>
							<a style="text-decoration:none;" class="modalbox" href="#inlineproduct" onClick="initializeProduct('.$pinfos[$i]['productid'].')" >
								<div class="imgcontainer">
									<div class="mag-title"><h1 >'.reduceLength($pinfos[$i]['title'],40).'</h1></div>
									<div class="imgcontainer-inner" id="product-'.$i.'" >
										<div class="mag-price" id="prodprice-'.$i.'">Price: Rs. '.$pinfos[$i]['price'].'</div>
										<img src="'.$pinfos[$i]['displayimage'].'">
										<div class="mag-desc" id="proddesc-'.$i.'">
											'.get_snippet(strip_tags($pinfos[$i]['description']),15).'
										</div>
									</div>
								</div>
							</a>
						</div>

						'.$shadowimg.'
						<!-- <hr color="red" width="200px"/> -->

						<div class="row-fluid mag-footer">
							<span class="span2"> </span>
							<span class="span8" style="text-transform:capitalize"> '.$pinfo['board'].' </span>
							<span class="span2"> '.($i+1).' / '.$maxprod.' </span>
						</div>
					</div>
				</div>';
				$pagesDone = $pagesDone + 1 ;
			}
			if($pagesDone%2 == 0)
				$maghtml = $maghtml.'<div class="mag-container"><div  class="pageContainer"><div class="mag-shadow evenshadow"><img src="img/magazine-right.png"/></div></div></div>';
			else if($pagesDone == 1)
				$maghtml = $maghtml.'<div class="mag-container"><div  class="pageContainer"><div class="mag-shadow oddshadow"><img src="img/magazine-left.png"/></div></div></div>
									<div class="mag-container"><div  class="pageContainer"><div class="mag-shadow evenshadow"><img src="img/magazine-right.png"/></div></div></div>';
			$maghtml = $maghtml.'<div></div></div></div>';
		?>
<!-- Magazine Index -->
<?php 
	if(count($boardstarts) > 1){
?>
	<div style="text-align:center;" class="row-fluid mag-board-index">
	  <ul id="boardindexCarousel" class="nav nav-pills">
		<?php
		  foreach ($boardstarts as $boardstart) 
			echo '
			<li>
				<a href="javascript:void(0);" style="text-transform:capitalize;" onclick="$(\'#magazine\').turn(\'page\','.$boardstart['start'].');">
					'.$boardstart['board'].'
				</a>
			</li>';
		?>
	  </ul>
	</div>
<?php
	}
?>
<!-- print out magazine -->
<?php echo $maghtml; ?>

<div class="row-fluid" align="center">
	<ul id="previewCarousel" class="jcarousel-skin-tango">
<?php
	foreach ($imgpages as $imgpage) {
		echo '
		<li>
			<img style="cursor:pointer;height:60px;min-width:30px" class="prodimage" onclick="$(\'#magazine\').turn(\'page\','.$imgpage['page'].');" src="'.$imgpage['img'].'" alt="'.$imgpage['title'].'"/>
		</li>';
	}
?>
	</ul>
</div>

<script type="text/javascript">

	function selectCorrectIndex(page){
		var boardstarts = <?php echo json_encode($boardstarts); ?>;
		if(page < boardstarts[0].start-1)
			return -1;
		else
			for(var i = 0; i < boardstarts.length ; ++i)
				// special case for right hand side start of new board
				if(page <= boardstarts[i].start-2 && boardstarts[i].start%2 == 1)
					return i-1;
		return boardstarts.length-1;
	}

	function bindBoardsIndex(){
		$("#boardindexCarousel li a").bind("click", function(e){
			$(this).parent().addClass('active'); 
			$(this).parent().siblings().removeClass('active');
		});
	}

	function setControllPos() {

		var view = $('#magazine').turn('view');

		if (view[0]) $('#previous').addClass('visible');
		else $('#previous').removeClass('visible');

		if (view[1]) $('#next').addClass('visible');
		else $('#next').removeClass('visible');
	}

	function rotated() {
		return Math.abs(window.orientation)==90;
	}

	function moveMagazine(page) {

		var that = $('#magazine'),
			rendered = that.data().done,
			width = that.width(),
			pageWidth = width/2,

			options = {duration: (!rendered) ? 0 : 300, complete: function(){ $('#magazine').turn('resize'); }};

			$('#controllers').stop();

			if ((page==1 || page == that.data().totalPages) && !rotated()) {

				var leftc = ($(window).width()-width)/2,
					leftr = ($(window).width()-pageWidth)/2, 
					leftd = (page==1)? leftr - leftc - pageWidth : leftr - leftc;

				$('#controllers').animate({left: leftd}, options);
			} else
				$('#controllers').animate({left: 0}, options);
	}

	function changeBackround(){
		var img1 = new Image();
		var img2 = new Image();
		img1.src= "img/magazine-left.png";
		img2.src= "img/magazine-right.png";
		var image1loaded = 0;
		var image2loaded = 0;
		$(img1).load(function() {
			image1loaded = 1;
			if(image2loaded == 1)
				$('.pageContainer').css('background-color','#999');
		});
		$(img2).load(function() {
			image2loaded = 1;
			if(image1loaded == 1)
				$('.pageContainer').css('background-color','#999');
		});
	}

	function magInfo(){
	  var tourSubmitFunc = function(e,v,m,f){
	    if(v === -1){
	      $.prompt.prevState();
	      return false;
	    }
	    else if(v === 1){
	      $.prompt.nextState();
	      return false;
	    }
		  };
	  var tourStates = [
	    {
	      title: 'Redesigned Clipboard',
	      html: '<span style="font-size:120%"><b>Click on the corners</b> or use the <b>right and left arrow keys</b> to start flipping through the collection of clips in an all new beautiful design.</span>',
	      buttons: { Done: 2 },
	      focus: 1,
	      position: { container: '#controllers', x: 690, y: -10, width: 300, arrow: 'lt' },
	      submit: tourSubmitFunc
	    },
	  ];
	  $.prompt(
	    tourStates,{
	      close: function(){
	      	if($('#magazine').turn('page') == 1)
	      		$('#magazine').turn('next');
	      }
	    }
	  );
	  document.cookie="magshown=1;expires="+getExpirationDateString(30);
	}
	$(document).ready(function() {

		<?php 
			if($loggedUserID != 0){
				if(!magShown($con, $loggedUserID) ){
					updateMagShown($con, $loggedUserID);
					echo 'setTimeout(function(){magInfo();},1000);';
				}
			}
			else {
				echo '
					if($.cookie("magshown") == "")
						setTimeout(function(){magInfo();},1000);';
			}
		?>

		changeBackround();
		/* Bind Jcarousel for preview*/	
		var numimages = <?php echo $maxprod; ?>;
		var visibleimages = 9;
		if (numimages < 9)
			visibleimages = numimages;

		var config = {scroll: 5, visible: visibleimages, size: numimages, itemFallbackDimension:85};
		console.log(config);
		setTimeout(function() {$('#previewCarousel').jcarousel(config);},500);
		
		/* Turn events */
		$('#magazine').
			bind('turning', function(e, page){
				//Let's do something amazing here
				// moveMagazine(page);
			}).

			bind('turned', function(e, page, pageObj) {
				changeBackround();
				// selecting correct index
				if(<?php echo count($boardstarts); ?>  > 1){
					var selectedIndex = selectCorrectIndex(page);
					if(selectedIndex != -1){
						$("#boardindexCarousel").children().eq(selectedIndex).siblings().removeClass('active');
						$("#boardindexCarousel").children().eq(selectedIndex).addClass('active');
					}else{
						$("#boardindexCarousel").children().removeClass('active');
					}
				}

				$('.mag-prodimg').css('z-index',5);

				$('.imgcontainer-inner').hoverIntent({
					over: function(){
						var descOffset = '-5px';
						$(".mag-desc", this).stop().animate({left:descOffset},{queue:false,duration:140});
						var priceOffset = '-5px';
						$(".mag-price", this).stop().animate({right:priceOffset},{queue:false,duration:140});
					},
					out: function() {
						var descOffset = '-500px';
						$(".mag-desc", this).stop().animate({left:descOffset},{queue:false,duration:140});
						var priceOffset = '-200px';
						$(".mag-price", this).stop().animate({right:priceOffset},{queue:false,duration:140});
					},
					timeout:300
				});

				resetbindings();
				bindProductFancyBox();
				var rendered = $(this).data().done;
		 });

		/* Window events */
		$(window).unbind('keydown');
		$(window).bind('keydown', function(e){
			if (e.keyCode==37) 
				$('#magazine').turn('previous');
			else if (e.keyCode==39)
				$('#magazine').turn('next');
		}).resize(function() {
 			$('#magazine').turn('resize');
		});


		$('#next').click(function(e) {
			$('#magazine').turn('next');
			return false;
		});

		$('#previous').click(function(e) {
			$('#magazine').turn('previous');
			return false;
		});

		<?php
			foreach ($pinfos as $pinfo) {
				if($pinfo['clipunclip'] == "Unclip")
					echo "changeToClippedMag('.clipbtn-".$pinfo['productid']."',".$pinfo['productid'].");";
			}
		?>
		/* Create internal instance */
		if ($(window).width()<=1200)
			$('body').addClass('x1024');
		// $("#magazine > div").addClass("fixed")	

		$('#magazine').turn({width: '900px', acceleration: true, duration: 600, autoCenter: true});
		bindBoardsIndex();
	    $("#previewCarousel").preloader();

	});
</script>
