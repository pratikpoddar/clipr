<?php
  require_once 'adminutils.php';
  require_once 'utils.php';

  function getAdminProductIds($con, $uid) {
      // Hack to get Product I want
      if (isset($_GET['id'])) {
        $retarr = array();
        array_push($retarr, $_GET['id']);
        return $retarr;         
      }
      // Get Product whose grouptag or cliprtag has not been done
      $sql = "SELECT productid from productDetail where (productid not in (select distinct productid from cliprTagTable) and siteId != 'craftsvilla') or productid not in (select distinct productid from productGroup )";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . mysql_error());};     

      $retarr = array();
      $count = 0;
      while(($row = mysql_fetch_array($result)) && ($count < 100))
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

  function addclipradmingroup(pid) {    
    $.ajax(
    { type: "GET",
      url: "clipradmingroup",
      data: { tag: $('[name="clipradmingroups'+pid+'"]').val(), productId: pid, uid: <?php echo $loggedUserID; ?> }
    }).done(function( msg ) {
      $('[name="clipradmingroups'+pid+'"]').hide();
    });
  }

  function addclipradmintag(pid) {
    arr=[];
    $('[name="clipradmintags'+pid+'"]').each(function(i) { arr.push((this).value) });
    arr = arr.filter(function(val) { if (val == "") { return false; } else {return true;} });

    $.ajax(
    { type: "GET",
      url: "clipradmintag",
      data: { tag: arr, productId: pid, uid: <?php echo $loggedUserID; ?> }
    }).done(function( msg ) {
      $('[name="clipradmintags'+pid+'"]').hide();
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

      $("#"+docid).append($('<i class="icon-paper-clip iclass'+clipunclip+'"></i><a class="thumbnail ajax-link" href="product/'+pid+'" ><img id="img'+pid+'" style="background:url(../image/loadingcircle.gif) no-repeat center;min-height:200px" alt="'+alt+'"/></a><p>'+phtml+'</p><div>'+
            

            <?php 
                
              echo "'";

              echo "<select name=\"clipradmingroups'+pid+'\">";
              $sql = "SELECT * FROM groupTable";
              $result = mysql_query($sql,$con);
              if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};   
              echo "<option value=\"\"></option>";
              while ($allTag = mysql_fetch_row($result)) {
                echo "<option value=".$allTag[0].">".$allTag[1]."</option>";
              }
              echo "</select>";
              echo "<button onClick=\"javascript:addclipradmingroup('+pid+')\" class=\"btn btn-primary\">Group Submit</button><br/><br/>";

              for ($i=0;$i<=4;$i++) {
                      echo "<select name=\"clipradmintags'+pid+'\">";
                $sql = "SELECT * FROM allTags";
                $result = mysql_query($sql,$con);
                if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};   
                            echo "<option value=\"\"></option>";
                while ($allTag = mysql_fetch_row($result)) {
                   echo "<option value=".$allTag[0].">".$allTag[1]."</option>";
                }
              echo "</select>";
                    }
              
              echo "'";
            ?>
            +'<button onClick="javascript:addclipradmintag('+pid+')" class="btn btn-primary">Tag Submit</button></div>'));
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
