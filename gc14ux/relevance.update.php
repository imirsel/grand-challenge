<?php 
	require_once("models/config.php");
##	if(!isUserLoggedIn()) { die(); }
?>


<html>
 <head>
  <title>Relevance</title>
 </head>
 <body>


<?php
	if (isset($_GET['t']) &&
	    isset($_GET['a']) &&
	    isset($_GET['q']) && 
	    isset($_GET['c']) &&
	    isset($_GET['v']) )
	{
		$t = $_GET['t']; # not currently used
		$a = $_GET['a'];
		$q = $_GET['q']; # not currently used inside updateGCRelevance
		$c = $_GET['c'];
		$v = $_GET['v'];


		updateGCRelevance($loggedInUser, $a, $q, $c, $v);
		echo "OK";
	}
        else {
	  echo "Error: Insufficient CGI arguments";
	  die();
        }
?>

 </body>
</html>
