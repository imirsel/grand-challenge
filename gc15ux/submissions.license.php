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
	$licenses = getLicenses();

	if (isset($_GET['sub'])) {
		if (!preg_match("/^[0-9]+$/", $_GET['sub'])) {
			$errors[] = "Submission ID required";
		}
		else {
			// are we the owner of this submission?
			$sub = getSubmissionLicense($loggedInUser, $_GET['sub']);
			if ($sub == null) {
				$errors[] = "You are not allowed to modify the licensing terms for this submission.";
			}
		}
	}
	elseif (!empty($_POST)) {
	
		$sub['sub'] = $_POST['sub'];
		$sub['sublic'] = $_POST['sublic'];
		$sub['sublictext'] = $_POST['sublictext'];

		if (!preg_match("/^[0-9]+$/", $sub['sub'])) {
			$errors[] = "Submission ID required";
		}
		
		if (!isset($sub['sublic']) || (!preg_match("/^[0-9]{1,2}$/", $sub['sublic']))) {
			$errors[] = "Please specify a license type";
		}

		if (count($errors) == 0) {
			$subid = $sub['sub'];
			$type = $sub['sublic'];
			$text = $sub['sublictext'];

			if ($type != 0) {
				$text = $licenses[$type];
			}
				
			if (updateSubmissionLicense($loggedInUser, $subid, $type, $text)) {
				header("Location: submissions.license.php?saved");
				die();
			} else {
				$errors[] = "Error recording license information.";
			}
		}
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Submission Licensing :: <?php echo $loggedInUser->display_username; ?></title>
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
			if (isset($_GET['saved'])) {
			?>
			<h1>License recorded</h1>

			<div id="success">
				<p>We have documented the license underwhich you are submitting your software.</p>
			</div>
            <?php
            }
            else {
            	?>
	            <h1>Software License for Submission <?php echo $sub['hash'];?></h1>
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
				?>
			<form action="submissions.license.php" method="POST">
				<input type="hidden" name="sub" value="<?php echo $sub['id'];?>"/>
				My submission is distributed under the following license: 
				<div>
				<select id="sublictype" name="sublic" onchange="toggletext(this)">
					<?php
					foreach ($licenses as $lid=>$lic) {
					?>					
					<option value="<?php echo $lid;?>" <?php echo ($sub['sublic'] == $lid ? "selected='true'": '');?>><?php echo $lic;?></option>
					<?php
					}
					?>
				</select>
				</div>
				<div id="sublictextdiv" style="opacity:<?php echo ($sub['sublic'] == 0 ? '1' : '0.5')?>;">
				<h3>License Text</h3>
				<textarea <?php echo ($sub['sublic'] == 0 ? "" : "disabled='true'")?> name="sublictext" id="sublictext" style="width:375px; height:300px;"><?php
				echo stripslashes($sub['sublictext']);
				?></textarea>
				</div>
				<div>
					<input type="submit" value="Save"/>
				</div>
				<script type="text/javascript" charset="utf-8" src="http://yui.yahooapis.com/3.1.1/build/yui/yui-min.js"></script>
				<script type="text/javascript">
					YUI().use('anim-base', function(Y) {
						var hide = new Y.Anim({
							node: '#sublictextdiv',
							to: { opacity: 0.5 }
						});
						var show = new Y.Anim({
							node: '#sublictextdiv',
							to: { opacity: 1 }
						});							
					 
						var toggle = function(e) {
							e.preventDefault();
							var select = document.getElementById('sublictype');
							if (select.value == 0) {
								show.run();
								document.getElementById("sublictext").disabled=false;
							}
							else {
								hide.run();
								document.getElementById("sublictext").disabled=true;
							}
						};
					 
						Y.one('#sublictype').on('change', toggle);
					});				
				
				</script>
			</form>
			<?php 
			}
	  		?>
		</div>
	</div>
</div>
</body>
</html>

