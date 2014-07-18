<?php
  require_once 'adminutils.php';
  require_once 'utils.php';
  if ($loggedUserID == 0)
    header( 'Location: ./index' );

  function getAdminProductIds($con, $uid) {
      // Hack to get Product I want
      if (isset($_GET['id'])) {
        $retarr = array();
        array_push($retarr, $_GET['id']);
        return $retarr;         
      }
      // Get Product whose grouptag or cliprtag has not been done
      $sql = "SELECT productid from productDetail where productid not in (select distinct productid from productGender) and productid in (select productid from productGroup where groupid = 1 or groupid = 3) limit 100";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};     

      $retarr = array();
      $count = 0;
      while(($row = mysql_fetch_array($result)) )
      {
        array_push($retarr, $row['productid']);
        $count++;
      }       
      return $retarr;
  }
  $productIds = getAdminProductIds($con, $userID);
?>

<style type="text/css">
  .companylogo {
    max-height : 30px;
    max-width : 100%;
  }

  .iclassUnclip {
    font-size: 30;
    margin-left: -20;
  }

  .iclassClip {
    font-size: 0;
  }

  .iclassLogClip {
    font-size: 0;
  }

  .productInfo .right {
    text-align: right;

  }

  .productInfo .left {
    text-align: left;
  }

  .font12 {
    font-size: 12;
  }

  .font13 {
    font-size: 13;
  }

  .font15 {
    font-size: 15;
  }

</style>
<script type="text/javascript">
  function addcliprsextag(pid) {    
    $.ajax(
    { type: "GET",
      url: "cliprsextag",
      data: { tag: $('[name="cliprsextag'+pid+'"]').val(), productId: pid }
    }).done(function( msg ) {
      $('[name="cliprsextag'+pid+'"]').hide();
    });
  }

  function submitall() {
	<?php 
		foreach ($productIds as $productId) {
		      echo "addclipradmintag(".$productId.");";
		}
	?>
  }
</script>

<button onClick="javascript:submitall()" class="btn btn-primary">Submit All</button>

<ul class="thumbnails">
  <li class="span3" id="col1">
  </li>
  <li class="span3" id="col2">
  </li>  
  <li class="span3" id="col3">  
  </li>  
  <li class="span3" id="col4">  
  </li>  
</ul>
  
<script language="JavaScript">
    $(".nav").addClass("active");
    function addAdminProductInfo(docid, pid, src, alt, phtml, clipunclip) {
      $("#"+docid).append($('<i class="icon-paper-clip iclass'+clipunclip+'"></i><a class="thumbnail ajax-link"  href="product/'+pid+'" ><img id="img'+pid+'" style="background:url(../image/loadingcircle.gif) no-repeat center;min-height:200px" alt="'+alt+'"/></a><p>'+phtml+'</p><div>'+
            <?php 
              echo "'";
              echo "<select name=\"cliprsextag'+pid+'\">";
              echo "<option value=\"\"></option>";
              echo "<option value=0>male</option>";
              echo "<option value=1>female</option>";
              echo "<option value=2>unisex</option>";
              echo "</select>";
              echo "<button onClick=\"javascript:addcliprsextag('+pid+')\" class=\"btn btn-primary\">Gender Submit</button></div>";
              echo "'";
            ?>
            +''));
      var img = new Image();
      $(img).load(function(){
        $("#img"+pid).attr('src',src);
      });
      img.src = src;
    }
    <?php
      if (count($productIds) > 0) {
        $i = 1;
        foreach ($productIds as $pid) {
          addAdminProduct($con, $pid, "col".$i, $loggedUserID);
          $i=($i%4)+1;
        }
      }
    ?>
</script>
