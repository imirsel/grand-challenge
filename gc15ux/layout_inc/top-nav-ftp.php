
            <div id="build" style="float: left;">
                <a href="/mirex/gc15ux/index.php"><span>MIREX</span></a>
            </div>
		<?php if(!isUserLoggedIn()) { ?>
            <ul style="float: left; padding-top: 70x">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../login.php">Login</a></li>
                <li><a href="../register.php">Register</a></li>
                <li><a href="../forgot-password.php">Forgot Password</a></li>
                <li><a href="../resend-activation.php">Resend Activation Email</a></li>
            </ul>
       <?php } else { ?>
       		<ul style="float: left; padding-top: 70px">
            	  <li><a href="../logout.php">Logout</a></li>
            	  <li><a href="../account.php">Account Info</a></li>
       			<li><a href="../identities.manage.php">Manage Identities</a></li>
       			<li><a href="../submissions.view.php">My GC Submissions</a></li>
       			<li><a href="../change-password.php">Change password</a></li>
                  <li><a href="../update-email-address.php">Update email address</a></li>
       		</ul>
       <?php 
				if ($loggedInUser->isGroupMember(2)) {
		?>
			<ul>
				<li>ADMIN</li>
				<li><a href="../submissions.admin.php">Submissions Admin</a></li>
				<li><a href="../admin/phpMyAdmin/">phpMyAdmin</a></li>
			</ul>
		<?php
				}
       		}
		?>
       
