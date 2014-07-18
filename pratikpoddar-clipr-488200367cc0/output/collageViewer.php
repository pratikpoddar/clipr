<?php
  require_once 'utils.php';
  require_once 'collageutils.php';

  $paramCollageId = $_GET['cid'];

  function get_snippet( $str, $wordCount = 10 ) 
  {
    $ret = implode('',array_slice(preg_split('/([\s,\.;\?\!]+)/',$str,$wordCount*2+1,PREG_SPLIT_DELIM_CAPTURE),0,$wordCount*2-1));
    if(trim($ret) != "")
      $ret = "   ".$ret."...";
    return $ret;
  }

  function findSimilarCollages($con, $cid){
    $sql = "SELECT cp2.cid,count(*) as overlap from collageProducts as cp1 join collageProducts as cp2 on cp1.productid = cp2.productid where cp1.cid = $cid and cp1.cid != cp2.cid order by overlap limit 4";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

    $collages = array();
    while($row = mysql_fetch_array($result)) 
      array_push($collages, $row);

    return $collages;
  }

?>

<style type="text/css">
.collage-tooltip-container{
  height: 100%;
  width: 100%;
}

.clipr-collage-anchor{
  position: absolute;
}
a.clipr-collage-anchor:hover{
  border-style: solid;
  border-width: 1px;
  border-color: black;
}
#clipr-collage{
  position: relative;
  display: inline-block;
  margin-left: auto;
  margin-right: auto;
  margin-top: 10px;
}

#clipr-collage-img{
  border: 2px solid #444;
  border-radius: 4px;
}
#collage-product-list{
  margin-left: 0px;
  /*max-height: 500px;*/
}
.collage-product{
  overflow: hidden;
  margin-top: 4px;
  height: 121px;
  border: 1px solid #AAA;
  display: table;
}
.collage-product a{
  display: table-cell;
  padding: 5px;
  vertical-align: middle;
}
.similar-collage-img{
  border-width: 2px;
  border-style: solid;
  border-color: #333;
}
.collage-product .product-thumbnail{
  max-height: 110px;
}
.collage-product .product-description{
  border:0px; 
}
.collage-product-first{
  margin-top: 0px;
}
.collage-product .thumbnail-description{
  font-size: 75%;
}
.collage-product .thumbnail-img {
  max-height: 110px; 
}
.collage-product .thumbnail-title {
  font-size: 100%;
  font-weight: bold;
}
#collage-right{
  text-align: center;
  background-color: #F5F5F5;
}

#collage-left{
  margin-left: 1%;
  height: 504px;
}
#similarcollagelist{
  margin-bottom: 0px;
  border:none;
  box-shadow: none;
}
</style>

<div class="row-fluid" id="collage-container">
  <span class="span8" id = "collage-right">
    <div id="clipr-collage" >
      <?php 
        $date = new DateTime();
        echo '<img id= "clipr-collage-img" src="http://beta.clipr.in/collageImages/'.$paramCollageId.'.png?dummy='.$date->getTimestamp().'">';
      ?>
    </div>
  <?php 
    $collages = findSimilarCollages($con, $paramCollageId);
    if(count($collages) > 0){
      echo '
      <div class="well" id="similarcollagelist" style="">    
        <div class="row-fluid">      
          <span class="span12">        
            <h3>Collages with common products</h3>      
          </span>    
        </div>';
      foreach ($collages as $collage) {
        $collageInfo = fetchCollageById($con, $collage['cid']);
        echo '
          <div class="row-fluid">
            <span class="span12">
              <ul class="inline unstyled">
                <li class="span3 similar-collage">
                  <a class="prodlink ajax-link" href="collage/'.$collage['cid'].'">
                    <img  src="../collageImages/'.$collageInfo['id'].'.png" class="img-rounded center similar-collage-img"> 
                    <div class="center" style="text-align:center; font-size:90%">'.$collageInfo['heading'].'
                    </div>
                  </a>
                </li>
              </ul>
            </span>    
          </div>';
      }
      echo '</div>';
    }
  ?>
  </span>
  <span class="span4" id ="collage-left">
    <ul id="collage-product-list" class="">

<?php 
  $collageInfo = fetchCollageById($con, $paramCollageId);
  $collage = json_decode($collageInfo['collage'],true);
  $count = 0;
  foreach ($collage as $collageElem) {
    $prod = getProductInfo($con, $collageElem['pid']);
    $productString = "";
    $elemClasses = " row-fluid collage-product";
    if($count == 0)
      $elemClasses = $elemClasses." collage-product-first";
    else if($count == count($collage))
      $elemClasses = $elemClasses." collage-product-last";
    echo
      '<li class="'.$elemClasses.'">
        <a class = "modalbox collage-product-'.$collageElem['pid'].'" href="#inlineproduct" data-productid="'.$collageElem['pid'].'" >
          <span class="span5 product-thumbnail" >
            <img class="thumbnail-img" src="'.$collageElem['src'].'">
          </span>
          <span class="span7 product-thumbnail" >
            <div class="thumbnail-title">'.reduceLength($prod['title'],50).'</div>
            <div class="thumbnail-description">'.get_snippet(strip_tags($prod['description']),16).'</div>
          </span>
        </a>
      </li>';
    $count = $count +1;
  }
?>
    </ul>
  </span>
</div>
<script type="text/javascript">
  function addAnchorOverlay(collageElem){
    // chose set/collage image as jquery object 
    var collageImgContainer = $('#clipr-collage');
    var anchorTooltip = $('<div class="collage-tooltip-container" rel="tooltip" data-placement="top" data-original-title="'+collageElem.title+'" ></div>');
    // create anchor for this product
    var anchor = $('<a class="clipr-collage-anchor modalbox" href="#inlineproduct" data-productid = "'+collageElem.pid+'"></a>');

    // bind events to the anchor of the product
    anchor.click(function(){
      initializeProduct($(this).data("productid"));
    })

    // position anchor correctly over the set image
    anchor.css('left',collageElem.leftOffset);
    anchor.css('top',collageElem.topOffset);

    anchor.height(collageElem.height);
    anchor.width(collageElem.height*collageElem.aspectRatio);

    // rotate it correctly
    anchor.rotate(collageElem.rotation);

    // set the correct z-index for anchors
    anchor.css('z-index',collageElem.zindex);
    anchor.append(anchorTooltip);
    collageImgContainer.append(anchor);
  }

  $(document).ready(function(){
    var collageInfoRaw =  <?php  
                          echo json_encode($collageInfo);
                        ?>;
    var collageElems = JSON.parse(collageInfoRaw.collage);
    $.each(collageElems, function(i,val){
      addAnchorOverlay(val);
      $('.collage-product-'+val.pid).unbind('click');
      $('.collage-product-'+val.pid).click(function(){
        initializeProduct($(this).data("productid"));
      })
    });
    bindProductFancyBox();
    resetbindings();
    $('#collage-left').jScrollPane({
      // autoReinitialise: true,
      // autoReinitialiseDelay: 1000
      // showArrows: true
    });
    // $('#collage-product-list').jcarousel({vertical: true, size:collageElems.length, visible:4, scroll:2 });
  })
</script>
