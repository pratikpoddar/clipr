<?php

  require_once 'utils.php';
  $uid = $_GET['uid'];
  $board = $_GET['board'];
  $products = getBoardProducts($con, $uid, $board);
  $len = count($products);

?>
<link rel="stylesheet" href="http://www.turnjs.com/css/default.css"> 
<link rel="stylesheet" href="http://www.turnjs.com/samples/basic/css/basic.css"> 
<style type="text/css">
</style>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="http://www.turnjs.com/lib/turn.min.js"></script>

<div id="toplevelcontainer " style="max-height:1200px;">

	<div id="flipbook" class="centerStart">
		<?php 
			$cnt = 1;
			foreach ($products as $pid) {
				$pinfo = getProductInfo($con, $pid);
				$ptitle = $pinfo['title'];
				$listofimages = explode('$$$', $pinfo['image']);
				echo '<div id="page'.$cnt.'" class="page own-size" style="height:700px;width:400px">';
				echo '	<div class="img'.$cnt.'">';
				echo '		<img src="'.getBigImage($listofimages[0]).'" alt="'.$pinfo['title'].'" />';
				echo '	</div>';
				echo '</div>';
				$cnt = $cnt + 1;
				// echo '<div style="background-image:url('.getBigImage($listofimages[0]).'); background-repeat: no-repeat; background-color: #fff;"></div>';
			}
		?>
	</div>
	<script type="text/javascript">

		$(function(){
			
			var mag = $('#flipbook');
			
			mag.turn();

			mag.bind('turned', function(e, page, pageObj) {
			
				if(page == 1 && $(this).data('done')){
					mag.addClass('centerStart').removeClass('centerEnd');
				}
				else if (page == 32 && $(this).data('done')){
					mag.addClass('centerEnd').removeClass('centerStart');
				}
				else {
					mag.removeClass('centerStart centerEnd');
				}
				
			});

			setTimeout(function(){
				// Leave some time for the plugin to
				// initialize, then show the magazine
				mag.fadeTo(500,1);
			},1000);


			$(window).bind('keydown', function(e){
				
				if (e.keyCode == 37){
					mag.turn('previous');
				}
				else if (e.keyCode==39){
					mag.turn('next');
				}

			});

		});
	</script>
</div>

<?php
  require 'sqlcloser.php';
?>
