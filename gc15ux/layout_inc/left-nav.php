
            <div id="build">
                <a href="/mirex/gc15ux/index.php"><span>MIREX</span></a>
            </div>
		<?php if(!isUserLoggedIn()) { ?>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php?type=evaluator">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="forgot-password.php">Forgot Password</a></li>
                <li><a href="resend-activation.php">Resend Activation Email</a></li>
            </ul>
       <?php } else { ?>
       		<ul>

<?php
		 if (isset($login_type) && ($login_type == "evaluator")) {
?>
       			<li><a href="consent.php">Informed Consent</a></li>
       			<li><a href="assignment.list.php">My GC Assignments</a></li>
<!--
       			<li><a href="eval-cockpit.php">Demo Evaluation</a></li>
-->
		        <hr />
<?php
		 }
?>

<?php
		 if (isset($login_type) && ($login_type == "submitter")) {
?>
       			<li><a href="dataset.php">Download Dataset</a></li>
       			<li><a href="identities.manage.php">Manage Identities</a></li>
       			<li><a href="submissions.view.php">My GC Submissions</a></li>
		        <hr />
<?php
		 }
?>

            	<li><a href="account.php">Account Info</a></li>


       			<li><a href="change-password.php">Change password</a></li>
                <li><a href="update-email-address.php">Update email address</a></li>

            	<li><a href="logout.php">Logout
		 <?php
		 if ($login_type_label != "") {
		   echo "as $login_type_label";
		 }
		 ?></a></li>

       		</ul>
       <?php 
				if ($loggedInUser->isGroupMember(2)) {
		?>
			<ul>
				<li>ADMIN</li>
				<li><a href="submissions.admin.php">Submissions Admin</a></li>
				<li><a href="admin/phpMyAdmin/">phpMyAdmin</a></li>
			</ul>
		<?php
				}
       		}
		?>
       
