<?php 
	require_once("models/config.php");

	if (isset($_GET['q']) && (strlen($_GET['q']) > 2) && isset($_GET['callback'])) 
	{
		$q = $_GET['q'];
		$r = findParticipants($q);
		$c = $_GET['callback'];
		echo $c, "(\"", $q, "\",", json_encode($r), ")";
	}
?>