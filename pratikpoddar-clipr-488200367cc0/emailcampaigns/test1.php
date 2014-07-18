<?php 
  require "../output/outpututils.php"; 
  $cool_products = array(27991,26456,24796,23723,23782,23807,28521,26298,24709,23684,25180);
  $female_products = array(24153,28360,28425,25537,29254,24558,23722,24669,26324,23736,28357);
  $valentine_products = array(28376,23854,24760,23733,28384,28451,28481,29257,29258,29259,24830,28375);
  $nationalmargaritaday = array(23736,24213,24280,24277,24215,24359,24287,24372,24389,24743,24819,25357,25451);
  $topprice = array(25296, 25283,  25206,
                    25262, 25536,
                    25428, 25444, 23812, 
                    25035, 25033,
                    25420, 24762, 24805
                    );
  $trending_products = array(24220, 24347,  23623,
                    23667, 23853,
                    24902, 24833, 24908, 
                    24828, 24364,
                    24679, 24687, 24709
                    ); 
  $products = $cool_products;
  if($_GET['target'] == 'female')
    $products = $female_products;
  else if($_GET['target'] == 'valentine')
    $products = $valentine_products;
  else if($_GET['target'] == 'cool')
    $products = $cool_products;
  else if($_GET['target'] == 'nmd')
    $products = $nationalmargaritaday;
  else if($_GET['target'] == 'price')
    $products = $topprice;
  else if($_GET['target'] == 'tp')
    $products = $trending_products;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd"> 
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
    .ExternalClass{
      width:100%;
    }
    .ReadMsgBody{
      width:100%;
    }
    .productcontainer{
    }
    .image {
      outline:none;
      text-decoration:none;
      -ms-interpolation-mode:bicubic;
      border-style:solid;
      border-color:#002640;

    }
    .image3{
      width: 175px;
    }
    .image2{
      width: 215px;
    }
    .prodtd{
      margin:0px;
      padding:0px;
      border:0px;
      border-collapse:collapse; 
      padding-bottom: 20px;  
    }

    .td2{
      width:50%;
    }
    .td3{
      width: 33%;
    }
    
</style>        
<?php 
function getprod($con, $pid, $num){
  if($num==3){
    $imgClass = "image3";
    $tdClass = "td3";
    $width = "175px";
    $tablewidth = "33%";
  }
  else{
    $imgClass = "image2";
    $tdClass = "td2";
    $width = "215px";
    $tablewidth = "50%";
  }
  $pinfo = getProductInfo($con, $pid);
  $listofimages = explode('$$$', $pinfo['image']);
  return '<td class="'.$tdClass.'" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;padding-bottom: 20px;width:'.$tablewidth.'" align="center">
            <div class = "productcontainer">
              <a href="http://clipr.in/output/product/'.$pid.'" target="_blank" style="text-decoration:none;">
                <img class="image '.$imgClass.'" style="width:'.$width.'" alt="'.trim($pinfo['title']).'" src="'.getOrigImage($listofimages[0]).'" title="'.trim($pinfo['title']).'">
                <div style="color: #002640;padding-top:6px;font-family: \'Trebuchet MS\', Helvetica, sans-serif; font-size: 14px;line-height: 17px;height: 35px;  color: #002640;padding-top:6px;">'.reduceLength(trim($pinfo['title']),50).'</div>
              </a>
            </div>
          </td>';
}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script src="http://us2.campaign-archive1.com/js/mailchimp/fancyzoom.mc.js"></script>  
  </head>
    <body style="padding:0;-webkit-text-size-adjust:100%;margin:0;-ms-text-size-adjust:100%;width:100%; background-color:#f0f0f0;"> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
     
     <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
     <title>Logged in Lately?</title> 
     <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" class="email" style="border-spacing:0px;border-spacing:0px;margin:0;padding:0;width:100%;line-height:100%;background-color: #f0f0f0;">
  <tbody>
      <tr>
        <td style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
        <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" id="header" style="border-spacing:0px;min-height:90px;background-color:#dbd8d8;width:600px;border-spacing:0px;min-height:90px;background-color:#dbd8d8;width:600px">
          <tbody>
              <tr style="background-repeat:repeat-y no-repeat;background-position:top center;background-color: #FFFFFF;background-repeat:repeat-y no-repeat;background-position:top center;background-color:#b0b0b0">
              <td valign="top" id="logo" width="320" style="margin:0px;border:0px;text-align:left;padding:20px;background-color:#dbd8d8;border-collapse:collapse;">
                <a href="http://www.clipr.in/" style="color:#139EA8"><img style="outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;border:none;width: 150px;" title="Clipr - Because Shopping is fun with friends" src="http://clipr.in/image/clipr_email_new.png" alt="Clipr.. Because Shopping is fun with friends"></a></td>
                <td id="social" width="100" style="margin:0px;border:0px;font-size:12px;padding:20px;width:280px;color:#fff;font-color:#fff;background-color:#dbd8d8;text-align:center;margin:0px;border:0px;border-collapse:collapse;padding:20px;font-size:12px;width:280px;color:#fff;font-color:#fff;background-color:#dbd8d8;text-align:center">
                <table cellpadding="0" cellspacing="0" border="0" align="center" class="social-text" style="border-spacing:0px;border-spacing:0px">
                    <tbody>
                      <tr style="background-repeat:repeat-y no-repeat;background-position:top center;background-color:#dbd8d8;background-repeat:repeat-y no-repeat;background-position:top center;background-color:#dbd8d8">
                      <td width="100" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;text-align:center;font-weight:bold;color:#444;font-size:14px;padding-bottom:5px;">
                        FOLLOW US</td>
                      </tr>

                      <tr valign="bottom" style="background-repeat:repeat-y no-repeat;background-position:top center;background-color:#dbd8d8;background-repeat:repeat-y no-repeat;background-position:top center;background-color:#dbd8d8">
                      <td width="100" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;text-align:center;padding:0px 0px">
                        <a href="http://twitter.com/clipr_in" style="color:#139EA8"><img alt="Twitter" src="http://clipr.in/output/img/twitter_icon.png" title="Clipr Twitter" style="outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;border:none"></a>
                        <a href="http://www.facebook.com/Clipr.in" style="color:#139EA8"><img alt="Facebook" src="http://clipr.in/output/img/facebook_icon.png" title="Clipr Facebook" style="outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;border:none">
                        </a></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" style="border-spacing:0px;border-spacing:0px;text-align:center; background-color: #ffffff; padding: 15px;">
          <tbody>
              <tr>
                <td style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <h2 style="font-family: 'Trebuchet MS', Helvetica, sans-serif; color:#32332c; background-color:#EEE;margin-bottom: 25px;">Refer Clipr to 10 friends and win a Free T-shirt</h2>
                <a href="http://clipr.in"><img src="http://clipr.in/image/referemail.jpg" style="width:100%;pargin:0px;padding:0px;" alt="Refer 'n Win"></a>
                </td>
                </tr>
            </tbody>
          </table>
          <table cellpadding="0" cellspacing="0" border="0" align="center" id="email-container" style="border-spacing:0px;background-color:#dddddd;text-align:center;border-spacing:0px;background-color:#dddddd;text-align:center; text-align:center; background-color: #ffffff;" width="600">
            <tbody>
              <tr>
                <td style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <p style="font-family: 'Trebuchet MS', Helvetica, sans-serif; font-size: 22px; padding-bottom: 5px;">
                  Trending Products this week in your network</p>
                </td>
              </tr>
            </tbody>
          </table>
          <table cellpadding="0" cellspacing="0" border="0" align="center" id="email-container" class="container-box" style="border-spacing:0px;text-align:center;background-color: #ffffff;margin-bottom: 0px;" width="600">
            <tbody>
              <tr class="" style="padding:10px;padding:10px">
                <td valign="top" width="550" style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-spacing:0px; padding-left: 20px; margin:0px;width: 100%;">
                    <tbody>
                      <tr>
                        <?php 
                          echo getprod($con,$products[0],3);
                          echo getprod($con,$products[1],3);
                          echo getprod($con,$products[2],3);
                        ?>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr class="" style="padding:10px;padding:10px">
                <td valign="top" width="550" style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-spacing:0px;border-spacing:0px;padding-left:20px;margin:0px;width:100%;">
                    <tbody>
                      <tr>
                        <?php 
                          echo getprod($con,$products[3],2);
                          echo getprod($con,$products[4],2);
                        ?>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
             </tbody>
          </table>
          <?php 
            if(count($products)==11)
            {
          ?>
           <table cellpadding="0" cellspacing="0" border="0" align="center" id="email-container" class="container-box" style="border-spacing:0px; text-align:center; background-color: #ffffff; margin-bottom: 50px;" width="600">
            <tbody>
              <tr>
                <?php 
                  echo getprod($con,$products[5],3);
                  echo getprod($con,$products[6],3);
                  echo getprod($con,$products[7],3);
                ?>
              </tr>
              <tr>
                <td style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <p style="margin:1em 0"></p>
                </td>
              </tr>
              <tr>
                <?php 
                  echo getprod($con,$products[8],3);
                  echo getprod($con,$products[9],3);
                  echo getprod($con,$products[10],3);
                ?>
              </tr>
            </tbody>
          </table>
        <?
          }
          else if(count($products)==13)
          {
        ?>
          <table cellpadding="0" cellspacing="0" border="0" align="center" id="email-container" class="container-box" style="border-spacing:0px;background-color:#dddddd;text-align:center;background-color:#dddddd;text-align:center; text-align:center; background-color: #ffffff; margin-bottom: 0px;" width="600">
            <tbody>
              <tr class="" style="padding:10px;padding:10px">
                <td valign="top" width="550" style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-spacing:0px; padding-left: 20px; margin:0px;width: 100%;">
                    <tbody>
                      <tr>
                        <?php 
                          echo getprod($con,$products[5],3);
                          echo getprod($con,$products[6],3);
                          echo getprod($con,$products[7],3);
                        ?>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr class="" style="padding:10px;padding:10px">
                <td valign="top" width="550" style="margin:0px;padding:0px;border:0px;border-collapse:collapse">
                <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-spacing:0px;border-spacing:0px;padding-left:20px;margin:0px;width:100%;">
                    <tbody>
                      <tr>
                        <?php 
                          echo getprod($con,$products[8],2);
                          echo getprod($con,$products[9],2);
                        ?>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
             </tbody>
          </table>
          <table cellpadding="0" cellspacing="0" border="0" align="center" id="email-container" class="container-box" style="border-spacing:0px; text-align:center; background-color: #ffffff; margin-bottom: 50px;" width="600">
            <tbody>
              <tr>
                <?php 
                  echo getprod($con,$products[10],3);
                  echo getprod($con,$products[11],3);
                  echo getprod($con,$products[12],3);
                ?>
              </tr>
            </tbody>
          </table>
        <?php 
          }
        ?>
        </td>
      </tr>
    </tbody>
  </table> 
</body>
</html>
<?php
  require_once '../output/sqlcloser.php';
?>

