<?php

  function isNewEntry($con, $pid){
    $sql = "SELECT * from clipsTable where productid = $pid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    if(empty($row))
      return true;
    return false;
  }

  function addContestEntry($con, $uid, $pid, $tagline){
    $tagline = mysql_escape_string($tagline);
    $sql = "DELETE from contestEntry where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $sql = "INSERT into contestEntry(userid, productid,tagline) values($uid,$pid,'$tagline')";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
  }

  function hasEntry($con, $uid){
    if($uid == 0)
      return false;
    $sql = "SELECT * from contestEntry where userid = $uid";
    $result = mysql_query($sql,$con);
    if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . ' '. mysql_error());};     
    $row = mysql_fetch_array($result);
    if(empty($row))
      return false;
    return true;
  }

  function getFbUserName($id){
    return (json_decode(file_get_contents('http://graph.facebook.com/'.$id))->username);
  }

  function isContestEntry(){
    return (isset($_GET['from']) && $_GET['from'] == "contest");
  }

  $logString = "Trying_to_add_a_Product, productid: NA";
  if (isset($_GET['link']) && isset($_GET['userId'])) {
    require_once 'outpututils.php';

    $uid = $_GET['userId'];
    if( !is_numeric($uid) ){
      die('wrong input');
    }
    $command = "python ../crawlers/addProduct.py --link=\"\"\"".$_GET['link']."\"\"\" --userid=".$uid."";
    // Run the python file to add file
    $pid = exec($command);

    if ($pid > 0) {
      increaseForAdd($con, $pid);

      $logString = "Added_a_Product, productid: ".$pid;
      $action = "added a product";

      if(isContestEntry() && !isNewEntry($con, $pid))
        header( 'Location: ./add/failcontest/'.$pid ) ;
      else {


        // Clip the product
        exec("php clip.php ".$uid." ".$pid);

        if(isContestEntry())
        {
          addContestEntry($con, $_GET['userId'], $pid,$_GET['tagline']);
          $action = "entered the contest with a product";
        }

        $sql = "INSERT INTO activityTable (userid, action, objproductid)  VALUES (".$uid.", '".$action."', ".$pid.")";
        $result = mysql_query($sql,$con);
        if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};     

        mysql_close($con);

        if(isContestEntry())
          header( 'Location: ./contest/init/'.getFbUserName($_GET['userId']).'/') ;
        else
          header( 'Location: ./product/'.$pid ) ;// Go to Product
      }
    }
    else {
      require_once 'utils.php';
      $GLOBALS['log']->logInfo("".$pid."  ".$command);
      if(isContestEntry())
        header( 'Location: ./add/failcontest' ) ;
      else
        header( 'Location: ./add/fail' ) ;
    }
  }
  else 
  {
    require_once 'utils.php';
    $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: ".$logString.", userid: ".$userID.", sessionid: ".getSessionId().", loggeduserid: ".getLoggedUserId()); 
?>

<style type="text/css">
  .companyimg {
    max-width: 80%;
    max-height: 80px;
    padding-bottom: 10px;

  }
</style>



<script type="text/javascript">
  
    function addProduct() {
      var link = encodeURIComponent($("#link").val());
      var userId = <?php echo $loggedUserID; ?>;
      var tagline = encodeURIComponent($("#tagline").val());
      <?php
        if(isContestEntry()){
          echo 'mixpanel.track("enter contest");';
          echo '_gaq.push(["_trackEvent", "Auto-Action", "enter-contest"]);';
          echo 'document.location.href="addproductbase?link="+link+"&userId="+userId+"&from=contest&tagline="+tagline;';
        }
        else{
          echo 'mixpanel.track("add product");';
          echo '_gaq.push(["_trackEvent", "Auto-Action", "add-product"]);';
          echo 'document.location.href="addproductbase?link="+link+"&userId="+userId;';
        }
      ?>
    }
  
</script>

  <div class="row-fluid">
<?php 
  if (isContestEntry()){
    // Handle error. When a product added already exists
    if (isset($_GET['status']) && $_GET['status'] == "fail")
    {
      if(isset($_GET['id']))
        echo "
          <div class='alert alert-block alert-error fade in'>
            <button type='button' class='close' data-dismiss='alert'>×</button>
            <h4 class='alert-heading'>Seems like this product is already on Clipr!</h4>
            <p>This product is already on Clipr(Check it out <a class='ajax-link' href='product/".$_GET['id']."' style='text-decoration:underline;color:#df382c'>here</a>) and has some clips, which makes this an invalid entry.</p>
            <p>Please add another product for a valid contest entry.</p>
          </div>
          ";
      else
        echo "
          <div class='alert alert-block alert-error fade in'>
            <button type='button' class='close' data-dismiss='alert'>×</button>
            <h4 class='alert-heading'>Oh snap! Something is not right here!</h4>
            <p>Please try adding other products from the following websites.</p>
          </div>
          ";
    }
    if($loggedUserID == 0)
      echo "
      <div class='alert alert-block alert-error fade in'>
        <button type='button' class='close' data-dismiss='alert'>×</button>
        <h4 class='alert-heading'>Login to enter contest!</h4>
        <p>Login. Add a new product. Gather Clips. Get the product for free - As simple as that*</p>
      </div>
      ";
    else if(hasEntry($con,$loggedUserID))
      echo "
      <div class='alert alert-block alert-error fade in'>
        <button type='button' class='close' data-dismiss='alert'>×</button>
        <h4 class='alert-heading'>Replace your entry?</h4>
        <p>Seems like you have already entered the contest. Are you sure you want to replace your old entry?</p>
      </div>
      ";
    echo "
      <div class='well'>
        <div class='row-fluid'>
          <div class='span9'>
            <div align='left' style='margin-left: 5px;'>
              <span style='font-size:150%'>Add a product to enter the contest!</span><br/>
              <span style='font-size:120%'>Add a new product. Gather Clips. Get the product for free - As simple as that* </span>
            </div>
          </div>
          <div class='span3'>
            <a style='margin:2px' class='btn pull-right ajax-link' href='contest' >See other entries</a>
          </div>
        </div>
      </div>";
  }
  else
  {
    if (isset($_GET['status']) && $_GET['status'] == "fail")
      echo "
        <div class='alert alert-block alert-error fade in'>
          <button type='button' class='close' data-dismiss='alert'>×</button>
          <h4 class='alert-heading'>Oh snap! Something is not right here!</h4>
          <p>Please try adding other products from the following websites.</p>
        </div>
        <br/>";
    else
      echo "<h4>Did not find the product you want to clip?</h4>
      <h4>You can add products by adding the product link from the following websites:</h4><br/>";
  }

?>

  </div>

<?php 
  if ($loggedUserID != 0) {
    $additionalField = "";
    if(isContestEntry())
      $additionalField = '<br><br><textarea rows="4" class="span8" id = "tagline" placeholder="Enter a Tagline for the Product"></textarea>';
    echo "<div class='row-fluid' align='center'>
        <div class='well'>";

    if(!isContestEntry())
      echo "<h3>Add Product in less than 5 seconds</h3>";

    echo "<h4>Enter the link of the product page from the seller website and click on 'Add Product'. Done!</h4>
        <input type='text' id='link' placeholder='Enter Link..' class='span6' style='height:30px'>
        </input>".$additionalField."<br/>
        <span onClick='addProduct()' class='btn btn-primary'>Add Product</span>
        </div></div>";
  }
?>

  <div class="row-fluid" style="background-color: #fff;">
    <div class="span12">
      <h2 style="text-align: center;">Supported websites</h2>
    </div>
  </div>
  <div class="row-fluid" style="background-color: #fff;">
    <span class="span3" style="text-align:center;">
      <a target="_blank" href="cliprtransfer?link=http://www.myntra.com">
        <img class="companyimg" src="../companylogos/myntra.png" /><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.afday.com">
        <img class="companyimg" src="../companylogos/afday.png" /><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.adventure18.com">
        <img class="companyimg" src="../companylogos/adventure18.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.bluestone.com">
        <img class="companyimg" src="../companylogos/bluestone.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.itsourstudio.com">
        <img class="companyimg" src="../companylogos/itsourstudio.png?ver=1.2"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.styletag.com">
        <img class="companyimg" src="../companylogos/styletag.png" /><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.thechemicallocha.com">
        <img class="companyimg" src="../companylogos/chemicallocha.jpg" /><br/>
      </a>
    </span>
    <span class="span3" style="text-align:center;">  
      <a target="_blank" href="cliprtransfer?link=http://www.postergully.com">
        <img class="companyimg" src="../companylogos/postergully.png" /><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.craftsvilla.com">
        <img class="companyimg" src="../companylogos/craftsvilla.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.fabfurnish.com">
        <img class="companyimg" src="../companylogos/fabfurnish.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.flipkart.com">
        <img class="companyimg" src="../companylogos/flipkart.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.bewakoof.com">
        <img class="companyimg" src="../companylogos/bewakoof.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.shortcircuit.in">
        <img class="companyimg" src="../companylogos/shortcircuit.gif"/><br/>
      </a>
    </span>
    <span class="span3" style="text-align:center;">
      <a target="_blank" href="cliprtransfer?link=http://www.fetise.com">
        <img class="companyimg" src="../companylogos/fetise.gif"/><br/> 
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.happilyunmarried.com">
        <img class="companyimg" src="../companylogos/happilyunmarried.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.rangiru.com">
        <img class="companyimg" src="../companylogos/rangiru.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.roomstory.com">
        <img class="companyimg" src="../companylogos/roomstory.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.hitplay.in">
        <img class="companyimg" src="../companylogos/hitplay.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://shop.inonit.in">
        <img class="companyimg" src="../companylogos/shopinonit.jpg"/><br/>
      </a>
    </span>
    <span class="span3" style="text-align:center;">
      <a target="_blank" href="cliprtransfer?link=http://www.shaze.in">
        <img class="companyimg" src="../companylogos/shaze.jpg"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.villcart.com">
        <img class="companyimg" src="../companylogos/villcart.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.fashionara.com">
        <img class="companyimg" src="../companylogos/fashionara.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.chumbak.com">
        <img class="companyimg" src="../companylogos/chumbak.png"><br>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.cbazaar.com">
        <img class="companyimg" src="../companylogos/cbazaar.png"/><br/>
      </a>
      <a target="_blank" href="cliprtransfer?link=http://www.bluegape.com">
        <img class="companyimg" src="../companylogos/bluegape.png"/><br/>
      </a>
    </span>
  </div>
  <br/><br/><br/><br/>
  <div class="row-fluid well" align="center" style="display:none">
    <h3>Clip Items from web with just a mouse click</h3>
     <h5 style="text-align:left">
      <ol>
        <li>Enable Bookmarks Toolbar on your Browser (View->Toolbars->Bookmarks Toolbar)</li>
        <li>Drag and Drop the button <a class="btn btn-primary btn-small" href="javascript:(function(){_my_script=document.createElement('script');_my_script.type='text/javascript';_my_script.src='<?php echo getAbsoluteUrl("../addon/bookmarklet.js"); ?>';%20document.getElementsByTagName('head')[0].appendChild(_my_script);})();">Clip</a> to Bookmarks Toolbar</li>
        <li>Go to any of our partner websites and click on Clip Button. That's it</li>
     </ol>
    </h5>
  </div>

<?php 
  }
?>