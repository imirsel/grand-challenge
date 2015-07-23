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
	if (isset($_POST['t']) &&
	    isset($_POST['a']) &&
	    isset($_POST['ot']) )
	{
		$t = $_POST['t']; # not currently used
		$a = $_POST['a'];
		$ot = $_POST['ot'];

		updateGCOpenText($loggedInUser, $a, $ot);
		echo "OK";
	}
        else {
	  echo "Error: Insufficient CGI arguments";
	  die();
        }
?>

 </body>
</html>
