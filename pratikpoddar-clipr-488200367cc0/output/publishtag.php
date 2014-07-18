<?php
      require_once 'utils.php';

      function constructMessage($tagstring, $message){
            $tags = explode(",", $tagstring);
            $names = "";
            foreach ($tags as $tag ) {
                  $names = $names."@[".$tag."] ";
            }
            return $names."".$message;
      }

      function getAccessToken($con, $uid) {
            $sql = "SELECT accessToken from fbdata where userid=$uid";

            $result = mysql_query($sql,$con);
            if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

            $row = mysql_fetch_array($result);
            return $row['accessToken'];
      }
      $userId = $argv[1];
      $productId = $argv[2];
      $tagstring = $argv[3];
      $message = $argv[4];

      var_dump($argv);
      $post_data = array();

      $message = constructMessage($tagstring, $message);
      $post_data['message'] = ''.$message.'';
      $url = "https://graph.facebook.com/me/cliprin:comment";

      $post_data['access_token'] = getAccessToken($con, $userId);
      $post_data['product'] = 'http://clipr.in/output/fbproduct?id='.$productId.'';
      $post_data['method'] = "POST";

      $ch = curl_init();
      var_dump($post_data);
       // set URL and other appropriate options
      $url = sprintf("%s?%s", $url, http_build_query($post_data));
      echo $url;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      // grab URL and pass it to the browser
      $output = curl_exec($ch);
      var_dump($output);
      // close cURL resource, and free up system resources
      curl_close($ch);

      require_once 'sqlcloser.php';
?>
