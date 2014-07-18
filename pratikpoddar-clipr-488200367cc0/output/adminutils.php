<?php
  function addAdminProductInfo($con, $docid, $productid, $image, $alt, $phtml, $loggedUserId ) {
    $listofimages = explode('$$$', $image);
    echo "addAdminProductInfo(\"".sanitize($docid)."\", \"".sanitize($productid)."\", \"".sanitize(getSmallImage($listofimages[0]))."\", \"".sanitize($alt)."\", \"".sanitize($phtml)."\", \"".getClipUnclip($con, $productid, $loggedUserId)."\");";
  }

  function getAdminProdMinInfo ($con, $prod) {
    $str = "<table class='productInfo table table-condensed'>";
    $str = $str."<colgroup><col width='10%'><col width='40%'><col width='50%'></colgroup>";
    $str = $str."<tr><td class='left font13' colspan='2'><b><a href='product/".$prod['productid']."'>".$prod['title']."</a></u></b></td>";
    $str = $str."<td class='right font13' colspan='1'>Rs. ".$prod['price']."  ";
    $str = $str."</td></tr>";    
    $str = $str."<tr><td class='left' colspan='2'>";
    $str = $str."</td>";
    $str = $str."<td class='right' colspan='1'><a href='cliprtransfer?link=".$prod['link']."'><img src='".getSeller($con, $prod['siteId'])."' alt='Seller Logo' class='companylogo'/></a></td></tr>";
    $str = $str."</table>";

    return $str;
  }

  function addAdminProduct($con, $productId, $col, $loggedUserId) {
    $row = getProductInfo($con, $productId);
    addAdminProductInfo($con, $col, $productId, $row['image'], $row['title'], getAdminProdMinInfo($con, $row), $loggedUserId);
  }
?>