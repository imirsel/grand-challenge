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
			$errors[] = "Your first name is required";
		}
		if (!isset($profile['lname']) || ($profile['lname'] == '')) {
			$errors[] = "Your last name is required";
		}
		if (!isset($profile['org']) || ($profile['org'] == '')) {
			$errors[] = "The name of the organization is required";
		}
		if (!isset($profile['url']) || ($profile['url'] == '') || (strlen(trim($profile['url'])) < 10)) {
			$errors[] = "A URL for the organization is required";
		}
		if (!isset($profile['title']) || ($profile['title'] == '')) {
			$errors[] = "The title of your position at this organizaton is required";
		}
		if (!isset($profile['start']) || !preg_match("/^[0-9]{4}$/", $profile['start'])) {
			$errors[] = "The four digit year your affiliation began is required";
		}
		if (!preg_match("/^[0-9]{4}$/", $profile['end'])) {
			if ($profile['end'] != "Now") {
				$errors[] = "The end of your affiliation should be a four digit year";
			}
		}
		
		if (count($errors) == 0) {		
			createIdentity($loggedInUser, $profile);
			header("Location: identities.manage.php?added");
			die();			
		}		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Identities :: <?php echo $loggedInUser->display_username; ?></title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" 
		src="http://yui.yahooapis.com/3.1.1/build/yui/yui-min.js"></script>

<script type="text/javascript"> 
/* <![CDATA[ */
YUI().use('node','event','node-base', function(Y) {
	Y.on("click", function (e) {
		var q = e.target._node.id;
		var id = q.substring(1);
		var sURL = "identities.claim.php?callback=claimed&id="+id;
		Y.Get.script(sURL, null);
	},"#foundprofiles input");
	
});
claimed = function(id, message) {
	if (id > 0) {
		var button = document.getElementById("b"+id);
		button.parentNode.removeChild(button);
	
		var profile = document.getElementById('profile'+id);
		profile.parentNode.removeChild(profile);
		
		document.getElementById("identity-list").appendChild(profile);	

		var noid = document.getElementById("noid");
		if (noid) { noid.parentNode.removeChild(noid); }

	}
	else {
		alert(message);
	}
}
</script>
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
        		$maybes = findPossibleMatchingProfiles($loggedInUser);
				if (count($maybes) > 0) {
				?>
					<div class="alert" id="foundprofiles">
					<h1>Is this you?</h1>
					<p>We found the following identity profiles which were created
					by other users as contributors on submissions. If any of these are you,
					claim them below. It will save you having to create a new identity profile,
					gives you access to view the status of your existing submission, and will make
					it easier later to uniquely and correctly identify authors in MIREX.</p>
					<?php
					foreach ($maybes as $maybe) {
						?>
						<div class="id" id="profile<?php echo $maybe['profile_ID'];?>">
							<div class="id-date"><?php echo $maybe['profile_Start'], " - ", ($maybe['profile_End'] == '' ? "Present" : $maybe['profile_End']);?><br/>
												 <input type="button" value="This is me" id="b<?php echo $maybe['profile_ID'];?>"/></div>
							<div class="id-info">
								<strong><?php echo $maybe['profile_Fname'], " ", $maybe['profile_Lname'];?></strong> <br/>
								<?php echo $maybe['profile_Organization'];?> <?php if (!isempty($maybe['profile_URL'])) { echo "(", $maybe['profile_URL'], ")"; }?> <br/>
								<p class="id-details">
								<?php echo $maybe['profile_Title'];?><br/>
								<?php if (!isempty($maybe['profile_Department'])) { echo $maybe['profile_Department'], "<br/>"; }?>
								<?php if (!isempty($maybe['profile_Unit'])) { echo $maybe['profile_Unit'], "<br/>" ;}?>
								<?php if (!isempty($maybe['profile_Addr_Street_1'])) { echo $maybe['profile_Addr_Street_1'], "<br/>"; }?>
								<?php if (!isempty($maybe['profile_Addr_Street_2'])) { echo $maybe['profile_Addr_Street_2'], "<br/>"; }?>
								<?php if (!isempty($maybe['profile_Addr_Street_3'])) { echo $maybe['profile_Addr_Street_3'], "<br/>"; }?>
								<?php if (!isempty($maybe['profile_Addr_City'])) { echo $maybe['profile_Addr_City'], ", ", $maybe['profile_Addr_Region'], " ", $maybe['profile_Addr_Post']; }?>
								<?php if (!isempty($maybe['profile_Addr_Country'])) { echo $maybe['profile_Addr_Country'], "<br/>"; }?>
								</p>
							</div>
							<div class="clear"></div>
						</div>
						<?php
					}
				?>
				</div>
				<?php
				}
        	?>
        	<h1>Identities for <?php echo $loggedInUser->display_username;?>	</h1>
            <?php 
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
            elseif (isset($_GET['added'])) 
            {
            ?>
            <div id="success">
                <p>Identity Added.</p>            
            </div>
            <?php 
            }
			?>
            	<div id="identity-list">
			<?php
			$identities = getIdentities($loggedInUser);
            if (count($identities) == 0) {
            ?>
            	<p id='noid'>You have no recorded identities. You need to complete at least one identity profile
            	if you are contributing to MIREX.</p>
            <?php
            }
            else {
            	?>
            	<?php
            	foreach ($identities as $id) {
            		?>
            		<div class="id">
						<div class="id-date"><?php echo $id['profile_Start'], " - ", ($id['profile_End'] == '' ? "Present" : $id['profile_End']);?></div>
						<div class="id-info">
							<strong><?php echo $id['profile_Fname'], " ", $id['profile_Lname'];?></strong> <br/>
							<?php echo $id['profile_Organization'];?> <?php if (!isempty($id['profile_URL'])) { echo "(", $id['profile_URL'], ")"; }?> <br/>
							<p class="id-details">
							<?php echo $id['profile_Title'];?><br/>
							<?php if (!isempty($id['profile_Department'])) { echo $id['profile_Department'], "<br/>"; }?>
							<?php if (!isempty($id['profile_Unit'])) { echo $id['profile_Unit'], "<br/>" ;}?>
							<?php if (!isempty($id['profile_Addr_Street_1'])) { echo $id['profile_Addr_Street_1'], "<br/>"; }?>
							<?php if (!isempty($id['profile_Addr_Street_2'])) { echo $id['profile_Addr_Street_2'], "<br/>"; }?>
							<?php if (!isempty($id['profile_Addr_Street_3'])) { echo $id['profile_Addr_Street_3'], "<br/>"; }?>
							<?php if (!isempty($id['profile_Addr_City'])) { echo $id['profile_Addr_City'], ", ", $id['profile_Addr_Region'], " ", $id['profile_Addr_Post']; }?>
							<?php if (!isempty($id['profile_Addr_Country'])) { echo $id['profile_Addr_Country'], "<br/>"; }?>
							</p>
						</div>
			            <div class="clear"></div>
            		</div>
            		<?php
            	}
            }
            	?>
            </div>
        
        	<h1>Add new identity</h1>

			<form id="identity-form" action="identities.manage.php" method="post">
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
                Identity data is preserved for historical purposes and cannot be edited or
                deleted after the record is created. Please double check that all information
                in the above form is correct before submitting.
                
                If you wish to correct an error in your identity data, please contact the IMIRSEL team.
                </p>
                
				<p>
					<label>&nbsp;</label>
					<input type="submit" value="Submit"/>
				</p>
			</form>
  		</div>
  
	</div>
</div>
</body>
</html>

