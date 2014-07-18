<?php
      require_once 'utils.php';

      function getAccessToken($con, $uid) {
            $sql = "SELECT accessToken from fbdata where userid=$uid";

            $result = mysql_query($sql,$con);
            if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

            $row = mysql_fetch_array($result);
            return $row['accessToken'];
      }
      if (defined('STDIN')) {
            $userId = $argv[1];
            $productId = $argv[2];
      } 
      $post_data = array();

      $post_data['access_token'] = getAccessToken($con, $userId);
      $post_data['product'] = 'http://clipr.in/output/fbproduct?id='.$productId.'&sitename=Clipr&fbrefresh=1';

      $ch = curl_init();

       // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me/cliprin:clip/");
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_POST, true); 
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);   
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      // grab URL and pass it to the browser
      $output = curl_exec($ch);

      // close cURL resource, and free up system resources
      curl_close($ch);

      require_once 'sqlcloser.php';
?>
