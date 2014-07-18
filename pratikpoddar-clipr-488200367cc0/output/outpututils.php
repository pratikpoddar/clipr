<?php
  require_once "resolveurl.php";
  require_once "globalboards.php";
  require_once "loggedoutRestrictedPages.php";

  // logging framework being used
  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);

  // make an sql connection for all files that include me
  $con = mysql_connect("localhost", "root", "12345678");
  if (!$con) { error_log(mysql_error()); die('Could not connect: '. mysql_error());};
  $dbconfig = parse_ini_file ( "../../db.ini");
  $dbname = $dbconfig['db_name'];
  mysql_select_db($dbname, $con);
  mysql_query("set names 'utf8'");

  // log the url request at this central point, as this is included almost everywhere
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  function getSessionId(){
    if(isset($_COOKIE['sessionid']))
      return $_COOKIE['sessionid'];
    return $GLOBALS['sessionid'];
  }
  function getEntry($con, $uid){
    $sql = "SELECT contestEntry.userid,productid, firstname, lastname, CONCAT(firstname, ' ', lastname) as name, tagline from contestEntry join fbdata on contestEntry.userid = fbdata.userid where fbdata.userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);
    return $row;
  }

  function deniedFacebookPost($con,$uid){
    $sql = "SELECT * from emailCommunication where userid=$uid and fbstreampost=0";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);
    return !empty($row);
  }

  function getLoggedUserId(){
    if(isset($_COOKIE['loggedUserId']) && $_COOKIE['loggedUserId'] != '')
      return $_COOKIE['loggedUserId'];
    return $GLOBALS['loggedUserID'];
  }

  function tourShown($con, $uid){
      $sql = "SELECT tourshown from fbdata where userid=$uid";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      if (!empty($row) && $row['tourshown'] == 1)
        return true;
      return false;
  }

  function updateTourShown($con, $uid){
    $sql = "UPDATE fbdata set tourshown = 1 where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   
  }

  function magShown($con, $uid){
      $sql = "SELECT magshown from fbdata where userid=$uid";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      if (!empty($row) && $row['magshown'] == 1)
        return true;
      return false;
  }

  function updateMagShown($con, $uid){
    $sql = "UPDATE fbdata set magshown = 1 where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   
  }

  function referShown($con, $uid){
      $sql = "SELECT refershown from fbdata where userid=$uid";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      if (!empty($row) && $row['refershown'] == 1)
        return true;
      return false;
  }

  function updateReferShown($con, $uid){
    $sql = "UPDATE fbdata set refershown = 1 where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   
  }

  function getFBRequestUrl(){
    $message = 'I like Clipr.in and I think you might like it too';
    $url = $GLOBALS['fbDialogUrl']."apprequests?app_id=143944345745133&message=".$message."&redirect_uri=".urlencode(getAbsoluteUrl('../output/closePage?type=inviteFriend')).'&display=popup';
    return $url;
  }

  function getSendMessageUrl($description, $link, $imagelink){
    $url = $GLOBALS['fbDialogUrl']."send?app_id=143944345745133&name=".$description." - via Clipr.in&link=".$link."&redirect_uri=".urlencode(getAbsoluteUrl('../output/closePage?type=sendMessage&closelink='.urlencode($link))).'&picture='.$imagelink.'&display=popup';
    return $url;
  }

  function getFBWallPostTransferUrl($name, $description, $link, $picture, $transferLink){
    //$url = $GLOBALS['fbDialogUrl']."feed?app_id=143944345745133&name=".$name."&link=".$link."&picture=".$picture."&caption=I want to buy this product&description=".$description."&redirect_uri=".$transferLink.'&display=popup';
    //return $url;
    return $transferLink;
  }

  function getFBShareUrl($link, $text){
    $url = $GLOBALS['fbDialogUrl']."feed?app_id=143944345745133&name=".$text."&link=".$link."&picture=".getAbsoluteUrl("../image/clipr_app_logo.png")."&caption=Have a look at what I think are super awesome products!&description=Shopping is fun with Friends. That is why I share with you some great products that I think my friends would like to shop. Happy Clipping! Cheers!&redirect_uri=".urlencode(getAbsoluteUrl('../output/closePage?type=fbshare&closelink='.urlencode($link))).'&display=popup';
    return $url;
  }
  /*
  Utility functions begin here
  */

  function sanitize ($input) {
    return mysql_real_escape_string(stripslashes($input));
  }

  function getSmallImage($image) {
    $hash = md5($image);
    return getAbsoluteUrl("../prodImage/".$hash."-small.jpg") ;
  }

  function getBigImage($image) {
    $hash = md5($image);
    return getAbsoluteUrl("../prodImage/".$hash."-big.jpg") ;
  }

  function getCroppedImage($image) {
    $hash = md5($image);
    return getAbsoluteUrl("../prodImage/".$hash."-crop.jpg") ;
  }

  function getOrigImage($image) {
    $hash = md5($image);
    return getAbsoluteUrl("../prodImage/".$hash."-orig.jpg") ;
  }

  function startsWith($haystack, $needle)
  {
      $length = strlen($needle);
      return (substr($haystack, 0, $length) === $needle);
  }

  function implodeDescription( $arr )
  {
    $trimmed = trim( $arr );
    if( startsWith( $trimmed, "<p>" ) )
      return $arr;
    else
      return "<p>".$arr."</p>";
  }

  function explodeDescription( $xml,$ignoreTable=false )
  {
    require_once( "../lib/htmlpurifier-4.4.0/library/HTMLPurifier.auto.php");
    $config = HTMLPurifier_Config::createDefault();
    $xml = str_replace('<a href="#">top</a>', '', $xml);
    $config->set( 'HTML.ForbiddenAttributes', array( '*@class', '*@id', '*@name' ) ) ;
    if($ignoreTable)
      $config->set( 'HTML.ForbiddenElements', array( 'a', 'script', 'embed', 'h1', 'h2', 'h3','table','tr','td','b','strong','i','u','img' ) );
    else
      $config->set( 'HTML.ForbiddenElements', array( 'a', 'script', 'embed', 'h1', 'h2', 'h3' ,'img') );
    $config->set( 'AutoFormat.RemoveEmpty', true );
    if($ignoreTable)
      $config->set( 'CSS.ForbiddenProperties', array( 'font-family','font-size','font-weight','color','background-color','text-decoration','float'));
    else
      $config->set( 'CSS.ForbiddenProperties', array( 'font-size', 'font-family','color','background-color','float'));
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($xml);
  }

  function getProductInfo($con, $productId,$small = false) {
      $sql = "SELECT * from productDetail where productid ='$productId'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      if(empty($row)){
        $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: get_prod_info_error, productid: ".$productId.", user: NA, sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId());
        return $row;
      }

      // TODO: Hack to ensure all links are properly built for ciprtransfer
      $row['link'] = urlencode($row['link']);

      // TODO: Hack to ensure all titles are phrases
      $row['title'] = trim($row['title']);

      $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: get_prod_info, productid: ".$productId.", user: NA, sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 

      return $row;
  }

  function getUserInfo($con, $uid) {

      $sql = "SELECT * from fbdata where userid=$uid";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};   

      $row = mysql_fetch_array($result);
      return $row;
  }

  function getBoardProducts($con, $uid, $board){
    $sql = "SELECT * from clipsTable where userid = $uid and clipTag = '$board'";

    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

    $products = array();
    while($row = mysql_fetch_array($result)) {
       array_push($products, $row['productid']);
    }
    return $products;
  }

  function getInterests($con, $uid, $source) {
      if ($uid==0)
        return array();
      $sql = "SELECT allTags.name as name from interestsTable, allTags where allTags.id = interestsTable.interest and interestsTable.userid = $uid and interestsTable.source = $source";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

      $interests = array();
      while($row = mysql_fetch_array($result)) {
         array_push($interests, $row['name']);
      }
      return $interests;
  }

  function getAllInterests($con) {
      $sql = "SELECT distinct name from allTags";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
      $interests = array();
      while($row = mysql_fetch_array($result)) 
         array_push($interests, $row['name']);
      return $interests;
  }

  function getReferredIds($con, $uid) {
    if ($uid==0)
        return array();

    $sql = "SELECT userid from  inviteStats where referrer = $uid";

    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

    $referredIds = array();
    while($row = mysql_fetch_array($result)) {
       array_push($referredIds, $row['userid']);
    }
    return $referredIds;

  }
  

  function getBoards($con, $uid){
    $sql = "SELECT distinct clipTag from clipsTable where userid = $uid and clipTag != 'NoTag'";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

    $boards=array();

    while($row = mysql_fetch_array($result)) {
       array_push($boards, $row['clipTag']);
    }
    foreach ($GLOBALS['globalboards'] as $gboard) {
      if (!in_array($gboard, $boards))
        array_push($boards, $gboard);
    }
    return $boards;
  }
  function getSeller($con, $seller) {

      $sql = "SELECT logo from companyinfo where siteId ='$seller'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      return '../companylogos/'.$row['logo'];
  }

  function getClipUnclip($con, $pid, $uid) {
      
      if ($uid == 0) {
        return "LogClip";
      }

      $sql = "SELECT * from clipsTable where productid ='$pid' and userid=$uid";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      if (empty($row)) {
        return "Clip";
      }
      else {
        return "Unclip";
      }
  }

  function getClippersList($con, $productid) {
    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from clipsTable as ct join fbdata as fd on fd.userid = ct.userid where ct.productid =".$productid." group by ct.userid";
    $res = getClippersStringHelper($con, $productid, 0, $sql);
    return $res['string'];
  }

  function getShortClippersList($con, $productid, $lim) {
    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from clipsTable as ct join fbdata as fd on fd.userid = ct.userid where ct.productid =".$productid." and ct.userid = ".$GLOBALS['loggedUserID']."  group by userid";
    $rescum = getClippersStringHelper($con, $productid, $lim, $sql);

    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from clipsTable as ct join fbdata as fd on fd.userid = ct.userid where ct.productid =".$productid." and ct.userid in (select friendid from fbfriend where userid = ".$GLOBALS['loggedUserID']." and follow = 1) and ct.userid != ".$GLOBALS['loggedUserID']." group by ct.userid";
    $res1 = getClippersStringHelper($con, $productid, $lim - $rescum['count'], $sql);
    
    $rescum = array('count' => ($rescum['count'] + $res1['count']), 'string'=> ($rescum['string'].$res1['string']));

    if($rescum['count'] >= $lim)
      return $rescum;

    $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from clipsTable as ct join fbdata as fd on fd.userid = ct.userid where ct.productid =".$productid." and ct.userid not in (select friendid from fbfriend where userid = ".$GLOBALS['loggedUserID']." and follow = 1) and ct.userid != ".$GLOBALS['loggedUserID']." group by ct.userid ";
    $res2 = getClippersStringHelper($con, $productid, $lim - $rescum['count'], $sql);

    $rescum = array('count' => ($rescum['count'] + $res2['count']), 'string'=> ($rescum['string'].$res2['string']));
    return $rescum;
  }

  function getClippersStringHelper($con, $productid, $lim, $sql){
    $suffix="";
    if ($lim > 0)
      $suffix = " limit ".$lim;
    $sql = $sql.$suffix;
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

    $retarr = "";
    $count = 0;
    while(($row = mysql_fetch_array($result)))
    {
      $uid =  $row['userid'];
      $uname = $row['name'];
      $retarr = $retarr."<a rel=\"tooltip\" data-placement=\"top\" data-original-title=\"".$uname."\" href='clips/".$uid."' style='font-size:29px;' class='ajax-link'><img style='max-width:27px;padding-right:2px;padding-bottom:2px;' src='http://graph.facebook.com/".$uid."/picture?type=square'></a>";
      $count = $count+1;
    }
    $res = array('count' => $count, 'string'=> $retarr);
    return $res;
  }

  function getClippersCount($con, $productId) {

      $sql = "SELECT count(distinct userid) as num from clipsTable where productid ='$productId'";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
      
      $row = mysql_fetch_array($result);

      return $row['num'];
  }

  function getFriendsList ($con, $uid) {
      
      $sql = "SELECT fbfriend.friendid as id, fbdata.firstname as fn, fbdata.lastname as ln from fbdata, fbfriend where fbfriend.userid = $uid and fbfriend.friendid = fbdata.userid and fbfriend.follow = 1";
      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $retarr = array();
      while($row = mysql_fetch_array($result))
      {
        $friendarray = array(
            'id' => $row['id'],
            'name' => $row['fn'].' '.$row['ln']
          );
        array_push($retarr, $friendarray);
      }      
      return $retarr;
  }

  function getProductCategory ($con, $productId) {
        
      $sql = "SELECT pt.prodTag from productDetail as pd, productConceptualize as pc,conceptFilter as cf, prodTags as pt where pd.productid = pc.productid and pc.concept = cf.concept and cf.tagId = pt.id and pd.productid = $productId UNION (SELECT pt.prodTag from productDetail as pd, productTitle ,conceptFilter as cf, prodTags as pt where pd.productid = productTitle.productid and productTitle.title = cf.concept and cf.tagId = pt.id and pd.productid =  $productId) UNION (SELECT pt.prodTag from productDetail as pd, productBreadcrumb as pb, prodTags as pt where pd.productid = pb.productid and pb.breadcrumb = pt.id and pd.productid = $productId)";  

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $retarr = "";
      while($row = mysql_fetch_array($result))
      {
        $tagname=$row['prodTag'];
        $retarr = $retarr."<a href='tag/".urlencode($tagname)."' class='ajax-link'>".($tagname)."</a>,&nbsp;";
      }      

      $retarr = substr_replace($retarr ,"",-7);     
      return $retarr;
  }

  function getProductGroupHtml ($con, $productId) {

      $grouprow = getProductGroup($con, $productId);
      $retarr = "";

      if(!empty($grouprow))
        $retarr = $retarr."<a href='tag/".urlencode($grouprow['groupname'])."' class='ajax-link'>".ucwords(str_replace("_", " ", $grouprow['groupname']))."</a>,&nbsp;";
      
      $retarr = substr_replace($retarr ,"",-7);           
      return $retarr;
  }

  function getProductGroup ($con, $productId) {
      $sql = "SELECT groupname from productGroup join groupTable on groupTable.id = productGroup.groupid where productid = $productId";  
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);
      return $row;
  }

  function getProductTag ($con, $productId) {
        
      $sql = "SELECT name from cliprTagTable join allTags on allTags.id = cliprTagTable.cliprTag where productid = $productId";  

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $retarr = array();
      while($row = mysql_fetch_array($result))
        array_push($retarr, $row['name']);
      return $retarr;
  }

  function getProductTagHtml ($con, $productId) {
      $tags = getProductTag($con, $productId);

      $retstr = "";
      foreach ($tags as $tagname ) 
        $retstr = $retstr."<a href='tag/".urlencode($tagname)."' class='ajax-link'>".ucwords(str_replace("_", " ", $tagname))."</a>,&nbsp;";

      return substr_replace($retstr ,"",-7);           
  }
   
  function getProductUserTag ($con, $productId, $uid) {
      $sql = "SELECT clipTag from clipsTable where productid = $productId and userid = $uid";  
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $retarr = "";
      while($row = mysql_fetch_array($result))
      {
        $tagname=$row['clipTag'];
        if ($tagname != "NoTag") {
          $retarr = $retarr."".ucwords(str_replace("_", " ", $tagname)).",&nbsp;";
        }
      }      

      $retarr = substr_replace($retarr ,"",-7);           
      return $retarr;
  }

  function getFollowers($con, $uid) {
    $sql = "SELECT count(*) as followers from fbfriend as ff join fbdata as fd on ff.friendid = fd.userid where fd.accessToken != '' and ff.follow = 1 and ff.friendid = ".$uid." ";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    return $row['followers'];
  }

  function getFollowing($con, $uid) {
    $sql = "SELECT count(*) as following from fbfriend as ff join fbdata on ff.friendid = fbdata.userid  where ff.follow = 1 and ff.userid = ".$uid." and fbdata.accessToken != \"\"";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    return $row['following'];
  }

  function getProductsAdded($con, $uid) {
    $sql = "SELECT count(distinct productid ) as products from addedProduct where userid = ".$uid."";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    return $row['products'];
  }

  function getClipPoints ($con, $uid) {
      $points = 0;
      $sql = "SELECT count(distinct productid) as npoints from clipsTable where userid =$uid";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
      $row = mysql_fetch_array($result);
      $points += (int)$row['npoints'];
      return $points;
  }

  function getFollowButton ($con, $uid1, $uid2) {
      $sql = "SELECT * from fbfriend where userid = $uid1 and friendid=$uid2 and fbfriend.follow = 1";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $row = mysql_fetch_array($result);

      $str = "";
      if($uid1 == 0){
        $absUserUrl = getAbsoluteUrl("../output/clips/".$uid2);
        $str = $str."<button style=\"width:80%\" class=\"btn btn-primary\" onclick=\"showLoginError('follow', '".$absUserUrl."')\">";
        $str = $str."Follow";
        $str = $str."</button>";
      }
      else if (empty($row)) {
        $str = $str."<button style=\"width:80%\" class=\"btn btn-primary\" onclick=\"following(".$uid1.",".$uid2.",'follow')\">";
        $str = $str."Follow";
        $str = $str."</button>";
      }
      else {
        $str = $str."<button style=\"width:80%\" class=\"btn\" onclick=\"following(".$uid1.",".$uid2.",'unfollow')\">";
        $str = $str."Unfollow";
        $str = $str."</button>";
      }

      if ($uid1 == $uid2) {
        $str = "";
      }

      return $str;
  }

  function js_string_escape($data)
  {
      $safe = "";
      for($i = 0; $i < strlen($data); $i++)
      {

          if (($data[$i] == "'") || ($data[$i] == "\""))
              $safe = $safe;
          else if (ctype_print($data[$i]) )
              $safe .= $data[$i];
          else
              $safe = $safe; //$safe .= sprintf("\\x%02X", ord($data[$i]));
      }
      return $safe;
  }

  function getClipButton ($con, $uid, $pid, $prefix, $data_placement = "top", $extraclass = "clip-btn btn-small", $absProdUrl="") {
    if($absProdUrl == "")
      $absProdUrl = getAbsoluteUrl("../output/product/".$pid);
    if ($uid == 0)
      return "<div class='clipdiv'>
                <span rel='tooltip' data-placement='".$data_placement."' data-original-title='You \"Clip\" what you \"Like\"' > 
                  <a href='javascript:void(0);' id='".$prefix."btn".$pid."' class='btn ".$extraclass." btn-primary' type='button' onClick='showLoginError(\"clip\", \"".$absProdUrl."\")'>Clip</a>
                </span>
              </div>";
    else
      return "<div class='clipdiv dropdown' id='".$prefix."btngroup".$pid."'>
                <a rel='tooltip' data-placement='".$data_placement."' data-original-title='' 
                  href='javascript:void(0);' id='".$prefix."btn".$pid."' class='btn ".$extraclass." btn-primary dropdown' type='button'>
                  Clip
                </a><ul class='dropdown-menu'></ul></div>";
  }

  function getFollowerFollowing( $con, $userid, $flag){
    if($flag == "follower"){
      $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from fbfriend as ff join fbdata as fd on ff.userid = fd.userid where ff.follow = 1 and ff.friendid = ".$userid."  ";
      $modalid = "modalFollower";
    }
    else if ($flag == "following"){
      $sql = "SELECT fd.userid as userid, CONCAT(fd.firstname, ' ', fd.lastname) as name from fbfriend as ff join fbdata as fd on ff.friendid = fd.userid  where ff.follow = 1 and fd.accessToken != \"\" and ff.userid = ".$userid." "; 
      $modalid = "modalFollowing";
    }
    return getUsersForModal( $con, $sql, $modalid);
  }

  function getTotalRewards( $con, $userid ){
    $CLIP_ON_ADD_POINTS = 2;
    $ADD_POINTS = 10;
    $CLIP_POINTS = 1;
    $INVITE_POINTS = 5;

    $sql = "select count(*) as adds, coalesce( sum(clips),0) as cliponadds from (SELECT count(ct.productid) as clips from addedProduct as ap left outer join clipsTable as ct on ct.productid = ap.productid and ct.userid != ap.userid where repeatadd = 0 and ap.userid = ".$userid." group by ap.productid) as clipAdd";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);

    $addPoints =  $row['adds']*$ADD_POINTS + $row['cliponadds']* $CLIP_ON_ADD_POINTS;

    $sql = "select count(distinct productid) as clips from clipsTable where userid =".$userid."";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);

    $clipPoints =  $row['clips']*$CLIP_POINTS;

    $sql = "SELECT count(distinct friendid) as invites from inviteFriend where userid = ".$userid."";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $row = mysql_fetch_array($result);

    $invitePoints =  $row['invites']*$INVITE_POINTS;

    return ($clipPoints + $addPoints + $invitePoints);
  }

  function getNewActivities($con, $userid){
      $sql = "SELECT notification_clear_time from notificationTime where userid = ".$userid;

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      $row = mysql_fetch_array($result);
      $dt = new DateTime("@0"); // epoch time
      $lastTime = $dt->format('Y-m-d H:i:s');
      if ( !empty($row) ) 
        $lastTime = $row['notification_clear_time'];

      $sql = "SELECT count(*) as newactivity FROM (SELECT * from activityTable where userid in (SELECT friendid from fbfriend where userid = $userid) and time > cast( '$lastTime' as DATETIME) )as acttab";

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

      $row = mysql_fetch_array($result);
      if(empty($row))
        return 0;
      else
        return $row['newactivity'];
  }

  function getSelectedBoards( $con, $uid, $pid ){
    $sql = "SELECT clipTag from clipsTable where productid = $pid and userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $ret = array();
    while ($row = mysql_fetch_array($result)) 
      array_push($ret, $row['clipTag']);
    return $ret;
  }

  function getUsersForModal($con, $sql, $modalid){
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $followstring = "";
    while($row = mysql_fetch_array($result))
    {
      $followstring = $followstring."
      <div style='margin: 10px;padding: 10px;'>
        <img class='pull-left' src='http://graph.facebook.com/".$row['userid']."/picture?type=square' style='max-height:40px;padding: 2px 10px;'>
        <span class='pull-left' style='vertical-align: baseline;padding: 2px 10px;word-wrap:break-word;word-break:break-all;'>
          <a href='clips/".$row['userid']."' class='ajax-link' onclick='closeModal(\"".$modalid."\");' >".$row['name']."</a>
        </span>";
        if ($GLOBALS['loggedUserID'] != 0) {
          $followstring = $followstring."
            <span class='pull-right' style='width:120px;margin-right: 20px;'>".
              getFollowButton($con, $GLOBALS['loggedUserID'], $row['userid'])."
            </span>";
      }
      $followstring = $followstring."</div>
      <br>";
    }
    return $followstring;
  }

  function getClippedAdded( $con, $userid, $flag, $modalid)
  {
    if($flag == "clip")
      $sql = "SELECT distinct clipsTable.productid, productDetail.title, productDetail.image from productDetail join clipsTable on clipsTable.productid = productDetail.productid where clipsTable.userid = ".$userid;
    else if ($flag == "add")
      $sql = "SELECT distinct addedProduct.productid, productDetail.title, productDetail.image from productDetail join addedProduct on addedProduct.productid = productDetail.productid where addedProduct.userid = ".$userid;

    return getProductsForModal( $con, $sql, $flag, $modalid);      
  }

  function getUserStatString($stat, $value, $prop){
      $modalstart = "";
      $modalend = "";
      if($value > 0 || $prop=="rewards")
      {
        $modalstart = "<a id='userinfo".$prop."' style='text-decoration:none' class='modalbox' href='#userinfo' data-href='' onClick='dispatchModalAction(\"".$prop."\");' >";
        $modalend = "</a>";
      }
      $ret = "
        <span>
        ".$modalstart."
          <div class='sidebarlabel clearfix' style='margin-bottom:2px;text-align:left'>
            <span class='pull-left' style='color: #555;font-size:120%;font-weight:bold'>
            ".$value."
            </span>
            <span class='pull-right' style='color: #002640;'>
            ".$stat."
            </span>
          </div>
          ".$modalend."
        </span>";
      return $ret;
  }

  function endsWith($haystack, $needle)
  {
      $length = strlen($needle);
      if ($length == 0) {
          return true;
      }

      return (substr($haystack, -$length) === $needle);
  }

  function addProductInfo($con, $docid, $productid, $image, $alt, $clippersString, $loggedUserId, $clipcount, $relevanttag, $price,$description, $link ) {
    $listofimages = explode('$$$', $image);
    $img = sanitize(getBigImage($listofimages[0]));
    $hash = md5($listofimages[0]);
    return "addProductInfo(\"".sanitize($docid)."\", \"".sanitize($productid)."\", \"".$img."\", \"".sanitize($alt)."\", \"".getClipUnclip($con, $productid, $loggedUserId)."\", ".$clipcount.", \"".ucwords(str_replace("_", " ", $relevanttag))."\", ".$price.", \"".js_string_escape($description)."\",\"".$link."\",\"".sanitize($clippersString)."\" );";
  }

  function getProductIds($con, $page, $uid, $pagination, $luid) {

    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: get_prods_for_user, productid: NA, user: ".$uid.", sessionid: ".getSessionId().", loggeduserid: ".$luid.", page: ".$page);
    $sql = "";

    /////////////////////////////////////
    // Getting Product Ids for Clips Page
    /////////////////////////////////////
    if ($page == "clips") {
      $sql = "SELECT distinct productid from clipsTable where userid = $uid order by id desc";
    }

    ////////////////////////////////////////
    // Getting Product Ids for Trending Page
    ////////////////////////////////////////
    if ($page == "trending") {

      if ($uid != 0)
      {
        exec('cd ..; python userProductMappings.py --userid='.$uid.'; cd output');
        $sql = "SELECT distinct pd.productid from userTopProducts join productDetail as pd on pd.productid = userTopProducts.productid where imageQuality = 1 and userid =$uid and requester = $uid order by id desc";
      }
      else
      {
        exec('cd ..; python userProductMappings.py --userid='.$uid.'; cd output');
        $sql = "SELECT distinct pd.productid from userTopProducts join productDetail as pd on pd.productid = userTopProducts.productid where imageQuality = 1 and userid =0 and requester = $uid order by rand(".getSessionId().") desc";
        // show from amongst 100 popular products on logged out feed
        $sql = "SELECT distinct productid from (select ct.productid, count(distinct userid) as np from clipsTable as ct join productDetail as pd on pd.productid = ct.productid where imageQuality = 1 group by ct.productid order by np desc limit 100) as tmp  order by rand(".getSessionId().") desc";
      }
    }

    //////////////////////////////////////
    // Getting Product Ids for Tagged Page
    //////////////////////////////////////
    if ($page == "tagged") {

      $tag = $_GET['tag'];

      $sql = "SELECT productid from ((SELECT distinct productGroup.productid as productid from productGroup, groupTable, productDetail where productDetail.productid = productGroup.productid and productGroup.groupid = groupTable.id and imageQuality = 1 and groupTable.groupname like '$tag' )  
                UNION (SELECT distinct cliprTagTable.productid as productid from cliprTagTable, allTags,productDetail as pd  where pd.productid = cliprTagTable.productid and allTags.id = cliprTagTable.cliprTag and allTags.name like '$tag' and imageQuality = 1 )) 
                as result order by rand(".getSessionId().") desc";
    }
    //////////////////////////////////////////////////
    // Getting Product Ids for suggestions for friends
    //////////////////////////////////////////////////
    if ($page == "friendsuggestion") {
      if(!isset($_GET['friendid']))
        die('Friend not Selected');
      $friendid = $_GET['friendid'];
      if ($uid != 0 && $friendid != 0)
      {
        exec('cd ..; python userProductMappings.py --userid='.$friendid.' --source='.$uid.'; cd output');
        $sql = "SELECT distinct pd.productid from userTopProducts as ut join productDetail as pd on ut.productid = pd.productid where userid =$friendid and requester = $uid and imageQuality = 1 order by ut.score desc";
      }
      else{
         die('Permission Error'); 
      }
    }

    if ($sql != "" ) {

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     

      $retarr = array();
      while($row = mysql_fetch_array($result))
      {
        array_push($retarr, $row['productid']);
      }
      if (count($retarr) > $pagination)      
      { return array_slice($retarr, $pagination, $GLOBALS['PAGINATION_SIZE']); }
    }

    return null;
  }

  function reduceLength ($str, $length) {
    if (strlen($str) > $length) {
      return substr($str, 0, $length-4)." ...";
    }
    else
      return $str;
  }

  function getClippersString($con, $prod) {
    $numclips = getClippersCount($con, $prod['productid']);
    $str = getClippersStringWithoutTable($con, $prod);
    return $str;
  }

  function getClippersStringWithoutTable($con, $prod) {
    $MAX_CLIPS = 7;
    $numclips = getClippersCount($con, $prod['productid']);
    if ($numclips > 0)
    {
      $str = "";
      $clippers = getShortClippersList($con, $prod['productid'], $MAX_CLIPS);
      $str = $str.$clippers['string']."";
      if($numclips > $MAX_CLIPS){
          $str = $str.'<span style="font-size:13px;vertical-align:top;"> and <a class="modalbox" href = "#userinfo" onClick="populateClippers('.$prod['productid'].')" style="font-size:13px;vertical-align:top;" > '.($numclips-$MAX_CLIPS).' more</a> clipped it</span>' ;
      }
      else
        $str = $str."<span style='font-size:13px;vertical-align:top;'> clipped it</span>" ;
      return $str;
    }
    else
      return "";
  }

  function getProdMinInfo ($con, $prod) {
    $str = "<table id = 'prodtable".$prod['productid']."' style='table-layout:fixed; margin:0px' class='productInfo table table-condensed'>";
    $str = $str."<colgroup><col width='50%'> <col width='15%'><col width='35%'></colgroup>";
    $str = $str."<tr>";
    $str = $str."<td style='vertical-align:middle;padding-left:0px;padding-right:0px;' class='right' colspan='1'>";
    $str = $str.getTrendingClipButton ($con, $GLOBALS['loggedUserID'], $prod['productid'], "prodlist");
    $str = $str."</td></tr>";
    $clippersString = getClippersString($con, $prod);
    $str = $str.$clippersString;
    $str = $str."</table>";
    return $str;
  }

  function getProductPrice($con, $productId) {
    $sql = "SELECT productid, price from productDetail where productid ='$productId'";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    return $row['price'];
  }

  function addProduct($con, $productId, $col, $loggedUserId) {
    $row = getProductInfo($con, $productId);
    $clipcount = getClippersCount($con, $productId);
    $relevanttag = getRelevantTag($con, $loggedUserId,$loggedUserId, $productId);
    if($_GET['page']=="friendsuggestion"){
      if(!isset($_GET['friendid']))
        die('friend not chosen');
      $friendid = $_GET['friendid'];
      $relevanttag = getRelevantTag($con, $friendid, $loggedUserId, $productId);
    }
    return addProductInfo($con, $col, $productId, $row['image'], $row['title'], getClippersString($con, $row), $loggedUserId, $clipcount, $relevanttag, $row['price'],implodeDescription(explodeDescription($row['description'],true)), $row['link']);
  }

  function getBoardName($con, $pid, $uid){  
    $sql = "SELECT clipTag from clipsTable where userid = $uid and productid = $pid ";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    while($row = mysql_fetch_array($result)) {
      if ($row['clipTag'] != "NoTag")
        return $row['clipTag'];
      else
        return "wishlist";
    }
  }

  function getGroupName($con, $productid) {
    return "clipr_product";       
  }

  function getRelevantTag($con, $uid, $source, $productid) {
      if ($uid == 0)
        return "clipr_product";
      $sql = "SELECT name as tag from cliprTagTable join allTags join interestsTable on interestsTable.interest = allTags.id and allTags.id = cliprTagTable.cliprTag where productid = $productid and interestsTable.userid= $uid and interestsTable.source=$source"; 

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

      $tagNames = array();
      while($row = mysql_fetch_array($result)) {
         array_push($tagNames, $row['tag']);  
      }
      $retstr = implode(", ", $tagNames);
      if (strlen($retstr) > 0)
        return $retstr;      
      return "clipr_product";
  }

  function getAllGroups($con) {
      
      $sql = "SELECT distinct groupname as tag from productGroup join groupTable on groupTable.id = productGroup.groupid order by tag;"; 

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

      $groupNames = array();
      while($row = mysql_fetch_array($result)) {
         array_push($groupNames, $row['tag']);  
      }

      return $groupNames;   
  }

  function getFriends($con, $uid) {

      $sql = "SELECT distinct fbdata.userid, CONCAT(fbdata.firstname, ' ', fbdata.lastname) as name from fbdata join fbfriend where fbfriend.userid=$uid and fbfriend.friendid = fbdata.userid and fbfriend.follow = 1 and fbdata.accessToken != '' order by name asc"; 

      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

      $friends = array();
      while($row = mysql_fetch_array($result)) {
         array_push($friends, $row);
      }

      return $friends;   
  }

  function getTrendsetters ($con) {

      $sql = "SELECT uid as userid, name, sum(num) as numpoints from 
                      (
                        SELECT friendid as uid, CONCAT(fd.firstname, ' ', fd.lastname) as name, count(*) as num from fbfriend as ff join fbdata as fd on fd.userid = ff.friendid where follow = 1  and fd.accessToken != '' group by friendid 
                        UNION
                        (SELECT fd.userid as uid, CONCAT(fd.firstname, ' ', fd.lastname) as name, 4*count(*) as num from clipsTable as ct join fbdata as fd on fd.userid = ct.userid group by fd.userid)
                      ) as temptab
                    group by uid order by numpoints desc limit 7
              ";

      
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};

      $trendsetters = array();
      while($row = mysql_fetch_array($result)) {
         array_push($trendsetters, $row);
      }

      return $trendsetters;   
  }

  function getProdInfoForUser($con,$pid,$uid,$small=false)
  {
    $helpTextClip = "You Clip what you Like";
    $helpTextBuy = "Go to the seller's website";
    $helpTextMessage = "Tell a friend about this product";

    $pinfo = getProductInfo($con, $pid, $small);

    $grouprow = getProductGroup( $con, $pid );
    $listofimages = explode('$$$', $pinfo['image']);

    $pinfo['displayimage'] = sanitize(getBigImage($listofimages[0]));
    $pinfo['allimages'] = $listofimages;
    $pinfo['clipunclip'] = getClipUnclip($con, $pid, $uid);
    $pinfo['tags']=getProductTag( $con, $pid );
    $pinfo['group']= $grouprow['groupname'];
    $pinfo['clipcount'] = getClippersCount($con, $pid);
    $pinfo['clipperslist'] = trim(getClippersList($con, $pid));
    $pinfo['description'] = implodeDescription( explodeDescription( $pinfo['description']));
    $pinfo['views'] = getTotalViews($con,$pid);
    $pinfo['removed'] = isProductRemoved($con,$pid);
    $pinfo['seller'] = getSeller($con, $pinfo['siteId']);
    return $pinfo;
  }

  function isProductRemoved($con, $pid){
    $daybefore  = mktime(0, 0, 0, date("m") , date("d")-2, date("Y"));
    $daybeforeFormatted = date("Y-m-d H:i:s",$daybefore);
    $sql = "select pd.productid from productDetail as pd where pd.productid = $pid and pd.productid in (select productid from deletedProducts where time > '".$daybeforeFormatted."')";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};    
    $row = mysql_fetch_array($result);
    if($row)
      return true;
    else
      return false;
  }

function getTotalViews($con, $pid){
  if ($pid != 0)
  {
    $sql = "SELECT coalesce(sum(views),0) as numviews from productView where productid = $pid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};    
    $row = mysql_fetch_array($result);
    return $row['numviews'];
  }
  return 0;
}

function sendInvite($con, $userid, $friendid, $requestid) {

    $sql = "INSERT INTO inviteFriend (userid, friendid, requestid) VALUES ($userid, $friendid, $requestid)";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: send_friend_invite, productid: NA, user: ".$friendid.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
}

function increaseForView($con, $pid) {
  increaseProductScore($con, $pid, $GLOBALS['VIEW_VOTE'] );
}

function increaseForAdd($con, $pid) {
  increaseProductScore($con, $pid, $GLOBALS['ADD_VOTE'] );
}

function increaseForClip($con, $pid) {
  increaseProductScore($con, $pid, $GLOBALS['CLIP_VOTE'] );
}

function increaseForTag($con,$pid){
  increaseProductScore($con, $pid, $GLOBALS['TAG_VOTE'] );
}

function increaseProductScore($con, $pid, $amount) {
    $sql = "UPDATE productDetail set score = score + ".$amount." where productid = $pid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
}


function commaAppend($left, $right){
  return $left.",".$right;
}

function getTrending($con, $uid, $source){
    $genderMap = array(
      'male' => 1, 
      'female' => 0,
      '' => -1
    );

    $sql = "SELECT gender from fbdata where userid = ".$uid;
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};    
    $row = mysql_fetch_array($result);
    $gender = "";
    if($row)
      $gender = $row[0];
    // 
    $interests = array();
    $sql = "SELECT interest from interestsTable where userid = $uid and source = $source";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};    
    while($row = mysql_fetch_array($result)) 
      array_push($interests, $row['interest']);
    $queryPart = "";
    if(!empty($interests))
      $queryPart = array_reduce($interests, "commaAppend");
    $sql = sprintf(
      "SELECT distinct productid from cliprTagTable as ctt join productGender as pg join productDetail as pd on ctt.productid = pg.productid and ctt.productid = pd.productid where ctt.cliprTag in ( %s ) and pg.gender != %s order by score desc",
      mysql_real_escape_string($queryPart),
      mysql_real_escape_string($genderMap[$gender])
    );
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};
    $productIds = array();
    while($row = mysql_fetch_array($result)) 
      array_push($productIds, $row['productid']);
    return $productIds;
}

?>
