<?php 
  header("Pragma: public");
  header("Cache-Control: maxage=20");
  header('Expires: ' . date('D, d M Y H:i:s', time() + (60*1)) . ' GMT');

  require_once "utils.php";

  function getClippedProductsJson($con,$uid, $luid, $boardCond = ""){
    $sql = "select ct.productid,title,image,'Unclip' as clipunclip from clipsTable as ct join productDetail as pd on ct.productid = pd.productid where ct.userid = $uid and ct.productid in (select productid from clipsTable where userid = $luid)
          UNION (select ct.productid,title,image,'Clip' as clipunclip from clipsTable as ct join productDetail as pd on ct.productid = pd.productid where ct.userid = $uid and ct.productid not in (select productid from clipsTable where userid = $luid))";
    return getProductJson($con,$sql,$uid,$luid);
  }

  function getProductJson($con,$sql,$uid,$luid){
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $ret = array();
    while ($row = mysql_fetch_array($result)) {
      $listofimages = explode("$$$", $row['image']);
      $row['image'] = $listofimages[0];
      $row['clipbtn'] = getClipButton ($con, $luid, $row['productid'], "modal");
      array_push($ret, $row);
    }
    return $ret;
  }

  function getAddedProductsJson($con,$uid, $luid){
    $sql = "select ap.productid,title,image,'Unclip' as clipunclip from addedProduct as ap join productDetail as pd on ap.productid = pd.productid where ap.userid = $uid and ap.productid in (select productid from clipsTable where userid = $luid)
          UNION (select ap.productid,title,image,'Clip' as clipunclip from addedProduct as ap join productDetail as pd on ap.productid = pd.productid where ap.userid = $uid and ap.productid not in (select productid from clipsTable where userid = $luid))";
    return getProductJson($con,$sql,$uid,$luid);
  }
  
  if($_GET['action'] == "clip")
    $products = getClippedProductsJson($con,$_GET['uid'], $loggedUserID);
  else if ($_GET['action'] == "add")
    $products = getAddedProductsJson($con,$_GET['uid'], $loggedUserID);

  echo json_encode( array( 'loggedUserId' => $loggedUserID, 'products' => $products) );
  require_once "sqlcloser.php";
?>
