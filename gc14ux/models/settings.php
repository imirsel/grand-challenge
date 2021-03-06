<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/

	//General Settings
	//--------------------------------------------------------------------------
	
	//Database Information
	$dbtype = "mysql"; 
	#$db_host = "nema.lis.uiuc.edu";
	$db_host = "localhost";
	$db_user = "mirexsubs";
	$db_pass = "**changeme-password**";
	$db_name = "mirexsubs";
	$db_port = "";
	$db_table_prefix = "mirex_";

	$langauge = "en";
	
	//Generic website variables
	$websiteName = "MIREX";
	$websiteUrl = "http://music-ir.org/mirex/gc14ux/"; //including trailing slash

	//Do you wish UserCake to send out emails for confirmation of registration?
	//We recommend this be set to true to prevent spam bots.
	//False = instant activation
	//If this variable is falses the resend-activation file not work.
	$emailActivation = true;

	//In hours, how long before UserCake will allow a user to request another account activation email
	//Set to 0 to remove threshold
	$resend_activation_threshold = 1;
	
	//Tagged onto our outgoing emails
	$emailAddress = "mirex@imirsel.org";
	
	//Date format used on email's
        date_default_timezone_set("America/Chicago");
	$emailDate = date("l \\t\h\e jS");
	
	//Directory where txt files are stored for the email templates.
	$mail_templates_dir = "models/mail-templates/";
	
	$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
	$default_replace = array($websiteName,$websiteUrl,$emailDate);
	
	//Display explicit error messages?
	$debug_mode = false;
	
	//---------------------------------------------------------------------------
?>
