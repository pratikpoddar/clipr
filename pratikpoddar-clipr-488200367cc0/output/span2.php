<?php 
  require_once 'prodlistutils.php';
  require_once 'utils.php';
?>
<!-- 
- Get variables expected by this file:
-   compulsary 
-     page
-   optional
-     pagination
-     userid 
-     tag
-     friendid
-->

  <div align="center">

  <?php  
    
    // Share clipboard
    if ($_GET['page'] == "clips" && $loggedUserID != 0) {
      if ($userID == $loggedUserID)
        $clipboardShareText = "My ClipBoard";
      else
        $clipboardShareText = $userName."'s ClipBoard";
      echo "<br/><br/><button style=\"width:80%\" class=\"btn btn-large btn-primary\" onclick=\"openPopup('".
        getFBShareUrl(
          getAbsoluteUrl('clips/'.$userID.''),
          htmlentities(addslashes($clipboardShareText), ENT_QUOTES)
        )."')\">";
      echo "Share ClipBoard";
      echo "</button>";

    }

    // share tagged page
    if ($_GET['page'] == "tagged" && isset($_GET['tag'])  && $loggedUserID != 0) {
      echo "<!-- Tagged page share button -->";
      echo "<br/><br/><button style=\"width:80%\" class=\"btn btn-large btn-primary\" 
            onclick=\"openPopup('".getFBShareUrl(
              getAbsoluteUrl("tag/".$_GET['tag']), 
              "Buy Awesome Products - ".ucwords(str_replace("_", " ", $_GET['tag'])))."')\">";
      echo "Share Page";
      echo "</button>";
    }
  ?>

</div>
  
<!-- Price range Selector start -->
<?php 

  $productPriceMap = array();
  if ($_GET['page'] == "friendsuggestion") {

      foreach ($productIds as $pid) {
        $productPriceMap[$pid] = getProductPrice($con, $pid );
      }

      echo '<br/><br/>
      <div class="shadow">
        <b>Price Range:</b><br/><br/>
        <span id="slider-amount" style="border:0;color:#002640;font-size:90%"></span><br/><br/>
         
        <div id="slider-range" style="max-width:80%"></div><br/>
      </div>';

} ?>
<!-- Price range Selector end -->


<!--  Slider Space for Price Filter -->
<script>
function getProductPriceMap(){
  productPriceMap = <?php echo json_encode($productPriceMap);?>;
  return productPriceMap;
}
function getMinPrice(){
  return <?php 
      $min = 10000000000;
      foreach ($productPriceMap as $pid => $price) {
        if($price < $min )
          $min = $price;
      }
      echo $min;
    ?>;
}
function getMaxPrice(){
  return <?php 
      $max = 0;
      foreach ($productPriceMap as $pid => $price) {
        if($price > $max )
          $max = $price;
      }
      echo $max;
    ?>;
}
$(function() {
    $( "#slider-range" ).slider({
        range: true,
        min: getMinPrice(),
        max: getMaxPrice(),
        values: [ getMinPrice(), getMaxPrice() ],
        slide: function( event, ui ) {
            updatedMin = ui.values[0];
            updatedMax = ui.values[1];
            $( "#slider-amount" ).html( "Rs. " + updatedMin + " - Rs. " + updatedMax );
            productPriceMap = getProductPriceMap();
            filteredProducts=[];
            $.each(productPriceMap, function (pid, price) {
              if (price > updatedMax || price < updatedMin) {
                $("#img"+pid).addClass('fade');
                $("#phtml"+pid).addClass('fade');
              }
              else {
                $("#img"+pid).removeClass('fade');
                $("#phtml"+pid).removeClass('fade');
              }
            });
        }
    });
    $( "#slider-amount" ).html( "Rs. " + getMinPrice() +" - Rs. "+getMaxPrice() );
});
</script>
