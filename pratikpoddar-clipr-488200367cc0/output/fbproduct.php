<?php  

  require_once "resolveurl.php";
  function getBigImage($image) {
    $hash = md5($image);
    return getAbsoluteUrl("../prodImage/".$hash."-crop.jpg") ;
  }

  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . $sql . ' '. mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);
  mysql_query("set names 'utf8'");

  function getProductInfo($con, $productId) {

      $sql = "SELECT * from productDetail where productid ='$productId'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      // TODO: Hack to ensure all links are properly built for ciprtransfer
      $row['link'] = urlencode($row['link']);
      return $row;
  }

  $prod = getProductInfo($con, $_GET['id']);
  $prodImages = explode('$$$', $prod['image']);
?>

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
      xmlns:fb="https://www.facebook.com/2008/fbml"> 
<head prefix="og: http://ogp.me/ns# cliprin: 
                  http://ogp.me/ns/apps/cliprin#">

<meta http-equiv="refresh" content="0; url=product/<?php echo $_GET['id']; ?>">
<meta property="fb:app_id" content="143944345745133" /> 
<meta property="og:type"   content="cliprin:product" /> 
<meta property="og:title"  content="<?php echo htmlentities($prod['title']); ?>" /> 
<meta property="og:sitename"  content="Clipr" /> 
<meta property="og:price"  content="<?php echo $prod['price']; ?>" /> 
<meta property="og:image"  content="<?php echo getBigImage($prodImages[0]); ?>" /> 
<meta property="og:description"  content="<?php echo htmlentities(strip_tags($prod['description'])); ?>" /> 

</head>

</html>