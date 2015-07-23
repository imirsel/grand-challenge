<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>MIREX :: Submissions :: <?php echo $loggedInUser->display_username; ?></title>
    <link href="mirex.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>


  <script>
	  function resolveSFTPStatus(sub_Status,sub_Hashcode) {
	      var progressbar = $( "#progressbar-" + sub_Hashcode );
	      var progressLabel = $( "#progress-label-" + sub_Hashcode );
 
	      var currentdate = new Date(); 
	      var secs = currentdate.getSeconds();
	      var wait_secs = 65 - secs; // Allow 5 secs of tolerance for the cronjob which runs every minute

	      var prep_delay = 3000;
	      var wait_msecs = wait_secs * 1000;
	      var update_delay = Math.ceil((wait_msecs - prep_delay) / 100);

	      progressbar.progressbar({
		value: false,
		    change: function() {
		    progressLabel.text( progressbar.progressbar( "value" ) + "%" );
		  },
		    complete: function() {
		    $('#sftp-progress-bar-' + sub_Hashcode).hide();
		    $('#sftp-ready-' + sub_Hashcode).show(1000);
		  }
		});
 
	      function progress() {
		var val = progressbar.progressbar( "value" ) || 0;
 
		progressbar.progressbar( "value", val + 1 );
 
		if ( val < 99 ) {
		  setTimeout( progress, update_delay );
		}
		
	      }
 
	      if (sub_Status==0) {
		// Need time for cronjob to detect new submission in DB
		// => Make progress bar visible
		$('#sftp-progress-bar-'+ sub_Hashcode).show();
		setTimeout( progress, prep_delay );
	      }
	      else if (sub_Status==1) {
		  // SFTP area is ready, but no submission has (as yet) been copied out
		  // => Make link that takes the user to the SFTP area visible
		$('#sftp-ready-'+ sub_Hashcode).show();
	      }

	    };
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
        
        	<h1>Submissions for <?php echo $loggedInUser->display_username;?></h1>
        		<div style="margin:15px"><form action="submissions.edit.php" method="get"><input type="Submit" name="start" value="Start New Submission"/></form></div>
            <?php




	    $submissions = getGCSubmissions($loggedInUser);


            if (count($submissions) == 0) {
            ?>
            	<p>You have no submissions in progress.</p>
            <?php
            }
            else {
            	$licenses = getLicenses();
            	?>
				<div class="sub">
					<div class="sub-shortcode"><strong>Shortcode</strong></div>
					<div class="sub-info"><strong>Submission</strong></div>
					<div class="clear" style="height:0px;"></div>
				</div>
            	<?php
            	foreach ($submissions as $sub) {
            		?>



  <script>
	  $(function() {
	      var sub_Status = <?php echo $sub['sub_Status']?>;
	      var sub_Hashcode = "<?php echo $sub['sub_Hashcode'];?>";
	      resolveSFTPStatus(sub_Status,sub_Hashcode);
	    });
  </script>


            		<div class="sub">
						<div class="sub-shortcode">
							<div style="font-size:1.8em"><?php echo $sub['sub_Hashcode'];?></div>
							<?php 
								if (($sub['sub_Status'] == 0) || ($sub['sub_Status'] == 1) || ($sub['sub_Status'] == 7)) {
							?>
							<input type="button" onclick="window.location.href='submissions.edit.php?edit=<?php echo $sub['sub_ID'];?>';" value="Modify"/>
							<?php
								}
							?>
						</div>
						<div class="sub-info">
							<strong><?php echo stripslashes($sub['sub_Name']);?></strong>
<!--
							<div>Status: <?php echo getSubmissionStatus($sub['sub_Status']);?> | <a href="submissions.edit.php?added=<?php echo $sub['sub_Hashcode']?>&status=<?php echo $sub['sub_Status']?>">Upload Instructions</a></div>
-->
<!--
			                                <div>
			                                  <div id="sftp-progress-bar-<?php echo $sub['sub_Hashcode'];?>" style="display:none;">			         
		 	                                    <div style="padding: 4px;">Preparing SFTP Upload Area:</div>
                                                            <div id="progressbar-<?php echo $sub['sub_Hashcode'];?>"><div id="progress-label-<?php echo $sub['sub_Hashcode'];?>" class="progress-label">Preparing ...</div></div>
			                                  </div>
			                                  <div id="sftp-ready-<?php echo $sub['sub_Hashcode'];?>" style="display: none; padding: 6px; background-color: #aad;">
                                                            <div><a href="ftp-gui/index.php?hash=<?php echo $sub['sub_Hashcode']?>&checksum=<?php echo generateHashChecksum($sub['sub_Hashcode'])?>">Access FTP upload area for submission</a></div>
			                                  </div>
			                                </div>
-->

							<div style="font-size:0.8em;">
							Date Created: <?php echo $sub['sub_Created'];?> |
							Last Updated: <?php echo $sub['sub_Updated'];?>
							</div>
							Task: <em><?php echo stripslashes($sub['task_Name']);?></em><br/>
							Contributors: <em><?php echo stripslashes(join(", ",$sub['contributors']));?></em><br/>
							<div>Abstract: <?php echo ((file_exists($MIREX_absdir . $sub['sub_Hashcode'] . ".pdf")) ? "<a href='/mirex/abstracts/" . $MIREX_year . "/".$sub['sub_Hashcode'].".pdf'>View</a>"  : "Not uploaded")?>
							<?php if ($sub['sub_Username'] == $loggedInUser->clean_username) { ?>
								<input type="button" onclick="window.location.href='submissions.abstract.php?sub=<?php echo $sub['sub_ID'];?>';" value="Upload Abstract"/>
							<?php } ?>
							</div>
<!--
							<div>License: <?php echo ($licenses[$sub['sub_License_Type']]);?> 
								<?php if ($sub['sub_Username'] == $loggedInUser->clean_username) { ?>
									<input type="button" onclick="window.location.href='submissions.license.php?sub=<?php echo $sub['sub_ID'];?>';" value="Update License"/>
								<?php } ?>
							</div>
-->
							<div>README:</div>
							<textarea disabled='disabled' style="width:575px;height:100px;"><?php echo stripslashes($sub['sub_Readme']);?></textarea>
							<div>Notes from IMIRSEL team:</div>
							<textarea disabled='disabled' style="width:575px;height:100px;"><?php echo stripslashes($sub['sub_PubNotes']);?></textarea>
							<div>Your submission was last updated by: <?php echo (isempty($sub['sub_MIREX_Handler']) ? "no one" : $sub['sub_MIREX_Handler']);?></div>
						</div>
			            <div class="clear"></div>
            		</div>
            		<?php
            	}
            }
			?>
  		</div>  
	</div>
</div>
</body>
</html>

