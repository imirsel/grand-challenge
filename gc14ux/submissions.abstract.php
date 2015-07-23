<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

	$errors = array();

	if (isset($_GET['sub'])) {
		if (!preg_match("/^[0-9]+$/", $_GET['sub'])) {
			$errors[] = "Submission ID required";
		}
		else {
			// are we the owner of this submission?
			$sub = getSubmissionUser($loggedInUser, $_GET['sub']);
			if (!isset($sub['hash']) || ($sub['hash'] == '')) {
				$errors[] = "You are not allowed to upload abstracts for this submission.";
			}
		}
	}
	elseif (!empty($_POST)) {

		// are we the owner of this submission?
		$sub = getSubmissionUser($loggedInUser, $_POST['sub']);

		if (!preg_match("/^[0-9]+$/", $_POST['sub'])) {
			$errors[] = "Submission ID required";
		}
		
		$target = $MIREX_absdir . $sub['hash'] . ".pdf";

		if (strtolower(substr($_FILES['abstract']['name'], -3)) != "pdf") {
			$errors[] = "Only pdf files allowed";
		}

		if (count($errors) == 0) {	
			if (move_uploaded_file($_FILES['abstract']['tmp_name'], $target)) {
				header("Location: submissions.abstract.php?uploaded");
				die();
			} else {
				$errors[] = "Error uploading file. This is probably a server configuration issue ($target, " . $_FILES['abstract']['tmp_name'] . ")";
			}
		}
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Upload Abstract :: <?php echo $loggedInUser->display_username; ?></title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">

	<div id="content">
    
        <div id="left-nav">
        <?php include("layout_inc/left-nav.php"); ?>
            <div class="clear"></div>
        </div>
        
        
        <div id="main">
            <?php 
			if (isset($_GET['uploaded'])) {
			?>
			<h1>Abstract uploaded</h1>

			<div id="success">
				<p>Abstract uploaded!</p>
			</div>
            <?php
            }
            else {
            	?>
	            <h1>Upload Abstract for Submission <?php echo $sub['hash'];?></h1>
	            <?php
            
				if (count($errors) > 0) {
				?>
				<div id="errors">
					<?php
						foreach ($errors as $err) {
							echo "<div>", $err, "</div>";
						}
					?>
				</div>
				<?php             
				} 
				if (isset($sub['hash']) && file_exists($MIREX_absdir . $sub['hash'] . ".pdf")) {
					?>
					<div id="errors">This will overwrite your existing abstract!</div>
					<?php
				}
				
				?>
			<form enctype="multipart/form-data" action="submissions.abstract.php" method="POST">
				<input type="hidden" name="sub" value="<?php echo $sub['id'];?>"/>
				Abstract PDF: <input name="abstract" type="file" />
				<input type="submit" value="Upload" />
			</form>
			<?php 
			}
	  		?>
		</div>
	</div>
</div>
</body>
</html>

