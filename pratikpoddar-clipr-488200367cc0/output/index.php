<?php
	include '../lib/Mobile_Detect.php';
	$detect = new Mobile_Detect();
	if ( $detect->isMobile() ) {
		$redirect_destination = redirect_to_mobile($_GET);
		$redirect = 'Location: http://m.clipr.in'.$redirect_destination;
		header( $redirect );
	}

	function redirect_to_mobile($params){
		if ($params['pagetype'] == "productbase")
			return "/product/".$params['id'];
		elseif ($params['pagetype'] == "friendsuggestionbase") 
			return "/";
		elseif ($params['pagetype'] == "prodlistbase") 
			return "/";
		elseif ($params['pagetype'] == "magazineclipr"){
			if (isset($params['userId']))
				return "/".$params['userId'];
			else
				return "/clips";
		}
		elseif ($params['pagetype']=="profilebase") 
			return "/clips";
		elseif ($params['pagetype']=="activitybase") 
			return "/feed";
		else 
			return "/";
	}

	if(!isset($_GET['pagetype']))
		header( 'Location: home');
	else {
		if(!isset($_GET['login']))
			require_once 'header.php';
		require_once 'outputheader.php';
		require_once 'headerjs.php';
		echo '<div id = "toplevelcontainer">';
?>
<?php
		if($_GET['pagetype'] != "addproductbase" && $_GET['pagetype'] != "contestdetails" && $_GET['pagetype'] != "contestentries" && $_GET['pagetype'] != "contestbase")
		{
			if (!(($loggedUserID == 0) && isset($_GET['page']) && ($_GET['page']=="trending")))
			{
?>
			<div id="infowell" class="well container">
			    <div class="row-fluid">
			        <div class="span8">
			          <div align="left" style="margin-left: 5px;">
			            <span style="font-size:150%">Welcome to Clipr!</span><br/>
			            <span style="font-size:120%">Discover awesome products, Collect things you love &amp; Share with friends</span>
			          </div>
			        </div>
			        <div class="span4">
			            <a style="margin:2px" class="btn pull-right" href="javascript:void(0)" onClick="openPopup('<?php echo getFBRequestUrl() ?>')">Invite Friends</a>
			            <?php
			              if (($_GET['pagetype']=='prodlistbase') && $loggedUserID == 0) {
			            ?>
			              <a style="margin:2px" class="btn pull-right" href="javascript:void(0)" onClick="tourloggedout()">Take a Tour</a>
			            <?php
			            } else if (($_GET['pagetype']=='prodlistbase') && $loggedUserID != 0) {
			            ?>
			              <a style="margin:2px" class="btn pull-right" href="javascript:void(0)" onClick="tour(false, '', false);">Take a Tour</a>
			            <?php
			            } else {
			            ?>
							<a style="margin:2px" class="btn pull-right ajax-link" href="help">Learn More</a>
			            <?php
			        	}
			            ?>
			        </div>
			    </div>
			</div>
<?php
			}
		}
		require ''.$_GET['pagetype'].'.php';
		require 'basecss.php';
		require 'basejs.php';
		echo '</div>';
		require_once 'outputfooter.php';
		if(!isset($_GET['login']))
			require_once 'footer.php';
	}
?>
