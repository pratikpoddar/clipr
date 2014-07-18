<?php 
  require_once 'prodlistutils.php';
  require_once 'utils.php';
?>

<?php 

  if (($pagination >= $GLOBALS['PAGINATION_SIZE']) || (count($productIds) >= $GLOBALS['PAGINATION_SIZE'])) {
    echo "<div class='row-fluid' id='paginationDiv'>";
    echo "<hr style='border-color:#ddd'/>";
    echo "<ul class='pager'>";
  }

  if (count($productIds) >= $GLOBALS['PAGINATION_SIZE']) {
    if (!isset($_GET['tag']) && !isset($_GET['friendid']) )
      echo "<li class=\"next\"><a href='getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE'])."'"; 
    else if( !isset($_GET['friendid']) )
      echo "<li class=\"next\"><a href='getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE'])."&tag=".$_GET['tag']."'";
    else 
      echo "<li class=\"next\"><a href='getpagination?page=".$_GET['page']."&userId=".$userID."&pagination=".($pagination+$GLOBALS['PAGINATION_SIZE'])."&friendid=".$_GET['friendid']."'";
    echo ">&larr; ( ".($pagination+$GLOBALS['PAGINATION_SIZE']+1)." - ".($pagination+2*$GLOBALS['PAGINATION_SIZE'])." ) &rarr;</a></li>"; 
  }

  if (($pagination >= $GLOBALS['PAGINATION_SIZE']) || (count($productIds) >= $GLOBALS['PAGINATION_SIZE'])) {
    echo "</ul>";
    echo "</div>";
  }
?>
