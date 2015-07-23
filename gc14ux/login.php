<?php
	/*
		UserCake
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is already logged in
	if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>
<?php
	/* 
		Below is a very simple example of how to process a login request.
		Some simple validation (ideally more is needed).
	*/

if(isset($_POST["type"])) {
  $login_type = $_POST["type"];
  unset($_POST ["type"]);
}

if(isset($_GET["type"])) {
  # Allow a GET value to override a POST value
  $login_type = $_GET["type"];
}

$login_type_label = "";
if (isset($login_type)) {
    if ($login_type == "submitter") {
      $login_type_label = "Submitter";
    }
    else if ($login_type == "evaluator") {
      $login_type_label = "Evaluator";
    }
}


//Forms posted
if(!empty($_POST))
{
		$errors = array();
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
	
		//Perform some validation
		//Feel free to edit / change as required
		if($username == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
		}
		if($password == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
		}
		
		//End data validation
		if(count($errors) == 0)
		{
			//A security note here, never tell the user which credential was incorrect
			if(!usernameExists($username))
			{
				$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
			}
			else
			{
				$userdetails = fetchUserDetails($username);
			
				//See if the user's account is activation
				if($userdetails["Active"]==0)
				{
					$errors[] = lang("ACCOUNT_INACTIVE");
				}
				else
				{
					//Hash the password and use the salt from the database to compare the password.
					$entered_pass = generateHash($password,$userdetails["Password"]);

					if($entered_pass != $userdetails["Password"])
					{
						//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
						$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
					}
					else
					{
						//Passwords match! we're good to go'
						
						//Construct a new logged in user object
						//Transfer some db data to the session object
						$loggedInUser = new loggedInUser();
						$loggedInUser->email = $userdetails["Email"];
						$loggedInUser->user_id = $userdetails["User_ID"];
						$loggedInUser->hash_pw = $userdetails["Password"];
						$loggedInUser->display_username = $userdetails["Username"];
						$loggedInUser->clean_username = $userdetails["Username_Clean"];
						
						//Update last sign in
						$loggedInUser->updateLastSignIn();
		
						$_SESSION["userCakeUser"] = $loggedInUser;
						$_SESSION["loginType"] = $login_type;

						//Redirect to user account page
						header("Location: account.php");
						die();
					}
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $login_type_label ?> Login</title>
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
        
        <h1><?php echo $login_type_label ?> Login</h1>
        
        <?php
        if(!empty($_POST))
        {
        ?>
        <?php
        if(count($errors) > 0)
        {
        ?>
          <div id="errors">
          <?php errorBlock($errors); ?>
          </div>     
        <?php
        } }
        ?> 

        <?php
        if(isset($login_type) && ($login_type == "submitter") || ($login_type == "evaluator"))
        {
        ?>
        
            <div id="regbox">
                <form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                  <input type="hidden" name="type" value="<?php echo $login_type ?>" />
                  <p>
                    <label>Username:</label>
                    <input type="text" name="username" />
                  <p>
                     <label>Password:</label>
                     <input type="password" name="password" />
                  </p>
                
                  <p>
                    <label>&nbsp;</label>
                    <input type="submit" value="Login" class="submit" />
                  </p>

                </form>
                
            </div>
        <?php
        }
        else {
        ?> 

	  <!-- Choose which type of login you need to do -->
	    
      
          <p style="margin-top: 20px;">
	     Choose the login type that matches your role: either a submitter of a MIR system to
	     be entered into the Grand Challenge, or else as an evaluator.
	  </p>
          <div id="regbox" style="width: 50%; margin: 0 auto;">

	     <div style="width:400px;">
	       <div style="float: left; width: 130px"> 
                 <form name="newSubmitterUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	            <input type="hidden" name="type" value="submitter" />
                    <input type="submit" value="Submitter Login" />
                 </form>
	       </div>

	       <div style="float: right; width: 225px"> 
                 <form name="newEvaluatorUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	            <input type="hidden" name="type" value="evaluator" />
                    <input type="submit" value="Evaluator Login" />
                 </form>
	       </div>
	     </div>

            </div> <!-- regbox -->
  
        <?php
        }
        ?> 

        </div><!-- main -->
        
        <div class="clear"></div>
      </div>
    </div>
  </body>
</html>


