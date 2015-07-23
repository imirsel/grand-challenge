<?php 
	require_once("models/config.php");

	if(!isUserLoggedIn()) { echo "['error':'must be logged in']"; die(); }
	
	if (isset($_GET['id']) && preg_match("/^[0-9]+$/", $_GET['id']) && isset($_GET['callback'])) 
	{
		$id = $_GET['id'];
		$c = $_GET['callback'];
		$r = claimIdentity($loggedInUser,$id);

		if ($r) {
			echo $c, "(", $id, ")";
		}
		else {
			echo $c, "(-1, 'Error: Profile belongs to another user')";
		}
	}
?>