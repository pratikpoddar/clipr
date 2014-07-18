<?php
  require_once 'utils.php';

  $pagination = 0;
  if (isset($_GET['pagination']))
  {
    $pagination = $_GET['pagination'];
  }

  $productIds = getProductIds($con, $_GET['page'], $userID, $pagination, $loggedUserID);

  $groupNames = array();
  foreach ($productIds as $productId) 
  {
    array_push($groupNames, getGroupName($con, $productId)); 
  }

  $listOfGroups = array_unique($groupNames);
  $globalListOfGroups = array_unique(getAllGroups($con));
  $userfriends = getFriends($con, $loggedUserID);
  $userInterests = getInterests($con, $loggedUserID, $loggedUserID);
  $trendSetters = getTrendsetters($con);
?>