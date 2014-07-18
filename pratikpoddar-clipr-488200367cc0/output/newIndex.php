<?php 
	
	require_once "utils.php";
?>
<?php
	if($_GET['pagetype'] != "addproductbase"  && $_GET['pagetype'] != "contestdetails" && $_GET['pagetype'] != "contestentries" && $_GET['pagetype'] != "contestbase")
	{
?>
		<div id="infowell" class="well container" >
		    <div class="row-fluid">
		        <div class="span8">
		          <div align="left" style="margin-left: 5px;">
			            <span style="font-size:150%">Welcome to Clipr!</span><br/>
			            <span style="font-size:120%">Discover awesome products, Collect things you love &amp; Share with friends</span>
		          </div>
		        </div>
		        <div class="span4">
		            <a style="margin:2px" class="btn pull-right ajax-link" href="help">Learn More</a>
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
		              <a style="margin:2px" class="btn pull-right" href="javascript:void(0)" onClick="openPopup('<?php echo getFBRequestUrl() ?>')">Invite Friends</a>
		            <?php
		        	}
		            ?>
		        </div>
		    </div>
		</div>
<?php
	}
	require ''.$_GET['pagetype'].'.php';
  	require 'basecss.php';
  	require 'basejs.php';
?>
<input id = "pagename" type="hidden"></input>
<!-- common javascript here -->
<?php 
  require_once 'sqlcloser.php';
?>
