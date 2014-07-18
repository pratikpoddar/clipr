<?php 

  header("Pragma: public");
  header("Cache-Control: maxage=20");
  header('Expires: ' . date('D, d M Y H:i:s', time() + (20*1)) . ' GMT');
	
  require_once "utils.php";

  $pgrouprow = getProductGroup($con, $_GET['pid']);
  if (empty($pgrouprow))
    $defaultboard = "wishlist";
  else{
    $defaultBoardMap = $GLOBALS['defaultBoardMap'];
    $defaultboard = $defaultBoardMap[$pgrouprow['groupname']];
	}
  $selectedboards = getSelectedBoards( $con, $user, $_GET['pid']);
  if(empty($selectedboards))
    exec("php clip.php ".$user." ".$_GET['pid']." ".$defaultboard);
  $selectedboards = getSelectedBoards( $con, $user, $_GET['pid']);
  $clipperstring = getClippersString($con,getProductInfo($con, $_GET['pid']));
	echo json_encode( array( 'boards' => getBoards( $con, $user), 'selectedboards' => $selectedboards, 'clipperstring' => $clipperstring ) );
	require_once "sqlcloser.php";
?>
