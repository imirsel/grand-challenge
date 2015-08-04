<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
	
	if(!empty($_POST)) {
		$profile['fname'] 	= trim($_POST["identity_fname"]);
		$profile['lname'] 	= trim($_POST["identity_lname"]);
		$profile['org'] 	= trim($_POST["identity_org"]);
		$profile['dept'] 	= trim($_POST["identity_dept"]);
		$profile['unit'] 	= trim($_POST["identity_unit"]);
		$profile['url'] 	= trim($_POST["identity_url"]);
		$profile['title'] 	= trim($_POST["identity_title"]);
		$profile['email'] 	= trim($_POST["identity_email"]);
		$profile['str1'] 	= trim($_POST["identity_street1"]);
		$profile['str2'] 	= trim($_POST["identity_street2"]);
		$profile['str3'] 	= trim($_POST["identity_street3"]);
		$profile['city'] 	= trim($_POST["identity_city"]);
		$profile['reg'] 	= trim($_POST["identity_region"]);
		$profile['post'] 	= trim($_POST["identity_post"]);
		$profile['country'] = trim($_POST["identity_country"]);		
		$profile['start'] 	= trim($_POST["identity_start"]);
		$profile['end'] 	= trim($_POST["identity_end"]);
		
		$errors = array();
		if (!isset($profile['fname']) || ($profile['fname'] == '')) {
			$errors[] = "Your contributor's first name is required";
		}
		if (!isset($profile['lname']) || ($profile['lname'] == '')) {
			$errors[] = "Your contributor's last name is required";
		}
		if (!isset($profile['org']) || ($profile['org'] == '')) {
			$errors[] = "The name of the organization is required";
		}
		if (!isset($profile['url']) || ($profile['url'] == '') || (strlen($profile['url']) < 10)) {
			$errors[] = "A URL for the organization is required";
		}
		if (!isset($profile['title']) || ($profile['title'] == '')) {
			$errors[] = "The title of the position held by your contributor is required";
		}
		if (!isset($profile['start']) || !preg_match("/^[0-9]{4}$/", $profile['start'])) {
			$errors[] = "The four digit year your contributor's affiliation began is required";
		}
		if (!preg_match("/^[0-9]{4}$/", $profile['end'])) {
			if ($profile['end'] != "Now") {
				$errors[] = "The end of your contributor's affiliation should be a four digit year";
			}
		}
		
		if (count($errors) == 0) {		
			$id = createIdentity(NULL, $profile);
			
			header("Location: submissions.contributor.create.php?added=".$id);
			die();
		}		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Create Unregistered Contributor</title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="padding:10px">
<h1>Create New Contributor Identity</h1>
<?php 
if (isset($_GET['added'])) {
?>
<div id="success">
	<p>Contributor record created and added to submission</p> 
	<script type="text/javascript">
		<?php
		$id = getIdentity($_GET['added']);
		?>
		window.opener.addContributor(<?php echo $id['id']?>, 
									'<?php echo $id['fname']?>',
									'<?php echo $id['lname']?>',
									'<?php echo $id['org']?>',
									'<?php echo $id['title']?>');
	</script>
	<input type="button" onclick="window.location.href='submissions.contributor.search.php';" value="Search for other contributors"/>
	<input type="button" onclick="window.location.href='submissions.contributor.create.php';" value="Create a new contributor record"/>
	<input type="button" onclick="window.close()" value="Close Window"/>
</div>
<?php 
}
else {
	if (count($errors) > 0) {
	?>
	<div id="errors">
		<?php
			foreach ($errors as $err) {
				echo "<p>", $err, "</p>";
			}
		?>
	</div>
	<?php             
	}            
	?>
<form id="identity-form" action="submissions.contributor.create.php" method="post">
	<p>
		<label>First name*:</label>
		<input type="text" name="identity_fname" value="<?php echo $_POST['identity_fname'];?>" size="35"/>
	</p>
	<p>
		<label>Last name*:</label>
		<input type="text" name="identity_lname" value="<?php echo $_POST['identity_lname'];?>" size="35"/>
	</p>
	<p>
		<label>Organization*:</label>
		<input type="text" name="identity_org" value="<?php echo $_POST['identity_org'];?>" size="35"/>
	</p>
	<p>
		<label>Department:</label>
		<input type="text" name="identity_dept" value="<?php echo $_POST['identity_dept'];?>" size="35"/>
	</p>
	<p>
		<label>Unit/Lab:</label>
		<input type="text" name="identity_unit" value="<?php echo $_POST['identity_unit'];?>" size="35"/>
	</p>

	<p>
		<label>URL*:</label>
		<input type="text" name="identity_url" value="<?php echo isset($_POST['identity_url']) ? $_POST['identity_url'] : "http://";?>" size="50"/>
	</p>
	
	<p>
		<label>Title*:</label>
		<input type="text" name="identity_title" value="<?php echo $_POST['identity_title'];?>" size="35"/>
	</p>

	<p>
		<label>From (year)*:</label>
		<input type="text" name="identity_start" size="4" value="<?php echo $_POST['identity_start'];?>"/> 
		To: 
		<input type="text" name="identity_end" size="4" value="<?php echo isset($_POST['identity_end']) ? $_POST['identity_end'] : "Now";?>" />
	</p>

	<p>
		<label>Email:</label>
		<input type="text" name="identity_email" value="<?php echo isset($_POST['identity_email']) ? $_POST['identity_email'] : $loggedInUser->email;?>" size="35"/>
	</p>

	<p>
		<label>Street Address:</label>
		<input type="text" name="identity_street1" value="<?php echo $_POST['identity_street1'];?>" size="35"/>
	</p>
	<p>
		<label>Street Address 2:</label>
		<input type="text" name="identity_street2" value="<?php echo $_POST['identity_street2'];?>" size="35"/>
	</p>
	<p>
		<label>Street Address 3:</label>
		<input type="text" name="identity_street3" value="<?php echo $_POST['identity_street3'];?>" size="35"/>
	</p>

	<p>
		<label>City:</label>
		<input type="text" name="identity_city" value="<?php echo $_POST['identity_city'];?>"/>
	</p>

	<p>
		<label>State, Region:</label>
		<input type="text" name="identity_region" value="<?php echo $_POST['identity_region'];?>"/>
	</p>

	<p>
		<label>Postal Code:</label>
		<input type="text" name="identity_post" value="<?php echo $_POST['identity_post'];?>" size="10"/>
	</p>

	<p>
		<label>Country:</label>
		<input type="text" name="identity_country" value="<?php echo $_POST['identity_country'];?>"/>
	</p>
	<p>
		<label>&nbsp;</label>
		<input type="submit" value="Submit"/>
	</p>
</form>
<?php
}
?>
</div>
</body>
</html>

