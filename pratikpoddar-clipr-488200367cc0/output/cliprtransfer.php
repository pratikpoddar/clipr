<?php

  require_once( "../lib/KLogger.php");
  $log = new KLogger('../../logFiles/', KLogger::INFO);
  $loggedUserID = 0;
  if (isset($_COOKIE['loggedUserId']))
    $loggedUserID = $_COOKIE['loggedUserId'];
  // else
  // 	die('Transfer without login');
  
  function getAffValue($site){
    if (stripos($site, "flipkart") != FALSE)
      return "infoclipri";
    if (stripos($site, "infibeam") != FALSE)
      return "clip";
    if (stripos($site, "healthkart") != FALSE)
      return "CliprL3C2ATRJ1P";
    if (stripos($site, "itsourstudio") != FALSE)
      return "clipr";
    if (stripos($site, "shortcircuit") != FALSE)
      return "clipr";
    if (stripos($site, "bewakoof") != FALSE)
      return "clipr";
    return "clipr";
  }
  function getAffParam($site){
    if (stripos($site, "flipkart") != FALSE)
      return "affid";
    if (stripos($site, "infibeam") != FALSE)
      return "trackId";    
    if (stripos($site, "healthkart") != FALSE)
      return "affid";    
    if (stripos($site, "itsourstudio") != FALSE)
      return "cliprRefer";
    if (stripos($site, "shortcircuit") != FALSE)
      return "cliprRefer";
    if (stripos($site, "bewakoof") != FALSE)
      return "cliprRefer";    
    return "referrer";
  }
  function urlFromParsedUrl($parsedurl){
    if(isset($parsedurl['path']))
      return $parsedurl['scheme']."://".$parsedurl['host']."".$parsedurl['path']."?".$parsedurl['query'];
    else
      return $parsedurl['scheme']."://".$parsedurl['host']."?".$parsedurl['query'];
  }
  function affiliatize($link){
    $parsedurl['host'] = "";
    $parsedurl['path'] = "";
    $parsedurl = parse_url($link);
    if($parsedurl === false)die('Malformed url');
    $site = $parsedurl['host'];
    if(isset($parsedurl['query']))
      $query = $parsedurl['query'];
    else 
      $query = "";

    $querylist = explode('&',$query);
    $queryarray = array();
    if($query != ""){
      foreach ($querylist as $elem) {
        $param = explode('=',$elem);
        $queryarray[$param[0]] = $param[1];
      }
    }
    $queryarray[getAffParam($site)] = getAffValue($site);
    $parsedurl['query'] = http_build_query($queryarray);
    return urlFromParsedUrl($parsedurl);
  }

  $GLOBALS['log']->logInfo("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} : "."action: transfer_to_website, productid: INLINK, user: ".$loggedUserID); 
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="0; url=<?php echo affiliatize($_GET['link']); ?>">
    <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34166167-1']);
	  _gaq.push(['_setDomainName', 'clipr.in']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

    <title>Clipr</title>
    Taking you to seller website
  </head>
 </html>
