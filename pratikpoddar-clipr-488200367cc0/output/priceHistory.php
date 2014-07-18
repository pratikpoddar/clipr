<?php

  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);
  
  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: ' . $sql . ' '. mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);
  mysql_query("set names 'utf8'");

  function sanitize ($input) {
    return mysql_real_escape_string(stripslashes($input));
  }

  function getProductTitle($con, $productId) {

      $sql = "SELECT title from productDetail where productid ='$productId'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);
        $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: priceHistory, productid: ".$productId.", user: NA, sessionid: ".$_COOKIE['sessionid'].", loggeduserid: ".$_COOKIE['loggedUserId']); 
      return $row;
  }

  function getProductHistory($con, $productId) {

      $sql = "SELECT history from prodPriceHistory where productid ='$productId'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      return $row;    
  }

  $prodInfo = getProductTitle($con, $_GET['id']);
  $title = trim($prodInfo['title']);

  // $priceDateList = array();
  // array_push($priceDateList, array('2004', 1000));
  // array_push($priceDateList, array('2005', 1070));
  // array_push($priceDateList, array('2006', 100));
  // array_push($priceDateList, array('2007', 180));

  $json = getProductHistory($con, $_GET['id']);
  $priceDateList = json_decode($json['history']);

?>


<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Date', 'Price'],
          <?php
            $count = 0;
            foreach ($priceDateList as $priceDate) {
              if ($count>0) {
                echo ",";
              }
              echo "['".$priceDate[0]."',".$priceDate[1]."]";
              $count++;
            }

          ?>
        ]);

        var options = {
          title: 'Price History of "<?php echo $title ?>"',
          backgroundColor: '#fec',
          titlePosition: 'out',
          hAxis: {title: 'Date of Observation', gridlines: {color: '#333', count: 10}},
          vAxis: {title: 'Price (in INR)', gridlines: {color: '#333', count: 10}},
          legend: {position: 'none'}

        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 100%; height: 100%;"></div>
  </body>
</html>


<?php
  mysql_close($con);
?>


