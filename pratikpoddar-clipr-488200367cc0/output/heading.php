<?php
  require_once 'utils.php';
?>

  <h3 style="text-align:center">
  <?php 

    if ($_GET['page'] == "tagged") {
      if (isset($_GET['tag']) && !(is_null($_GET['tag']))){
        echo "Tagged as \"".ucwords(str_replace("_", " ", $_GET['tag']))."\"";
      }
    };

    if ($_GET['page'] == "trending" && $user != 0) {
      echo "Trending Products of Your Interests";
    };

    if ($_GET['page'] == "trending" && $user == 0) {
      echo "Trending Products";
    };

    if ($_GET['page'] == "clips" && $userID != 0) {
      echo "Clips by $userName";
      echo " - ".getClipPoints($con, $userID)." "."<i class='icon-paper-clip' style='font-size:120%'></i>";
    };

    if ($_GET['page'] == "friendsuggestion") {
      echo "Gift Suggestions";
    };    

  ?>  
  </h3>  
