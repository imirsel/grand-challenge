<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php?type=evaluator"); die(); }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: GC :: <?php echo $loggedInUser->display_username; ?></title>
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
			<h2>Assignments for <?php echo $loggedInUser->display_username;?></h2>

<!-- The following should really come from the database and be part of a displayed task -->



	     <p>
	        To evaluate the asigned MIR system we ask you to
                imagine you have a personal video that you are
                editing, and you wish to find a suitable audio track
                to add to the video.
	     </p>

	     <p> 
	       In performing this task, please focus on evaluating the
	       interaction and experience with the system as a whole,
	       and not just the results you get. Please be aware that
	       these systems are using an open-source test dataset
	       (Jamendo) that is a collection of copyright free music, and
	       therefore the results may not include popular music
	       that many of us are familiar with.
	     </p>
	   
	     <p>
	       After signing up as an evaluator for GC15UX you will be
	       assigned a number of MIR systems to evaluate.  The five
	       questions we ask you to consider are:

	       <ul>
		 <li> 
		   <b>Overall satisfaction.</b> How would you rate your overall satisfaction with the system?
		 </li>

		 <li> 
		   <b>Learnability.</b> How easy was it to figure out how to use the system?
		 </li>

		 <li> 
		   <b>Robustness.</b>  How good is the system's ability to warn you when you're about to make a mistake, allow you to recover, or retrace your step?
		 </li>

		 <li> 
		   <b>Affordances.</b> How well does the system allow you to perform what you want to do?
		 </li>

		 <li> 
		   <b>Feedback.</b> How well does the system communicate what’s going on? 
		 </li>
	       </ul>

	     <p> 
	       There will also be the opportunity to enter general comments in a free-form text box.
	     </p>


 <p>
  Once you have completed all the questions for a given MIR system
  there is the opportunity to return to it and alter any of the
  ratings you have given it.  In our analysis of the results we
  will take the final set of ratings that were saved.
 </p>

<p>
  <b>
    <i>
  In addition to simply rating the systems, we highly encourage
  evaluators to provide their feedback in free text. Your comments
  will not only help us understand important factors affecting user
  experience, but also help the participants to improve the design of
  their systems.
    </i>
  </b>
</p>
            <?php
			$tasks = getGCEvaluationTasks();
			foreach ($tasks as $tid=>$task) {
				
				?>
				<h3>Task: <?php echo stripslashes($task['task_Name']);?></h3>
				<div style="margin-bottom:40px;padding-bottom:15px;border-bottom:1px solid lightgray">
				<?php

	            $assignments = userGetGCAssignments($loggedInUser, $tid); 
	            if (count($assignments) == 0) 
	            {
	            	?>
	            	<p>You have no assignments for this task.</p>
	            	
	            	<?php 
	            		$avail = countAvailableGCAssignments($tid);
	            		
	            		if ($avail > 0)
	            		{	
	            	?>
	            	<div>
						<form action="assignment.get.php" method="post">
							<input type="hidden" name="task" value="<?php echo $tid;?>"/>
							<span class="alert">
<!--
			  	                          Be sure you're signing up for the correct task!! 
-->
                                                          <input type="submit" value="Get Assignment">  
                                                        </span>
						</form>
		            </div>
	            	<?php
		            	}
		            	else
		            	{
					?>
					<div>
						There are no queries available to assign.
					</div>
					<?php
		            	}	            		
	            }
	            else 
	            {
					?>
					<div class="sub">
<!--
						<div class="sub-shortcode"><strong>Assignment</strong></div>
-->
						<div class="sub-info"><strong>Status</strong></div>
						<div class="clear" style="height:0px;"></div>
					</div>
					<?php
					foreach ($assignments as $assignment) 
					{
                                                $query = $assignment['assign_Query'];
                                                $mirurl = $query;

                                                $assign_task = $assignment['assign_Task'];
                                                $assign_id   = $assignment['assign_ID'];


                                                $sub_id = $assignment['sub_ID'];
                                                $sub = getSubmissionByID($sub_id);

						$status = userGetGCAssignmentStatus($loggedInUser, $assign_id);
						$sc = $status['completed'];
						$st = $status['total'];
						?>
						<div class="sub">
<!--
							<div class="sub-shortcode">
								MIR ID: <?php echo enhash($query);?>

								<div>
									<div style="float:left;height:10px;width:<?php echo floor(75 * $sc/$st);?>px;background:#0c0;border-width:1px 0px 1px 1px;border-color:gray;border-style:solid;"></div><div style="float:left;height:10px;width:<?php echo ceil(75 * (($st-$sc)/$st));?>px;background:#c00;border-width:1px 1px 1px 0px;border-color:gray;border-style:solid;"></div>
								</div>

							</div>
-->
							<div class="sub-info">
								<span><?php echo $sc, ' of ', $st;?> questions answered: </span>
<!--
								<input type="button" onclick="window.open('eval-cockpit-consent.php?task=<?php echo $assign_task?>&assign_id=<?php echo $assign_id?>&sub_id=<?php echo $sub_id?>&query=<?php echo $mirurl;?>')" value="Evaluate MIR System" /> <i><?php echo $sub['sub_Name'];?></i>
-->

								<input type="button" onclick="window.location.href='eval-cockpit-consent.php?task=<?php echo $assign_task?>&assign_id=<?php echo $assign_id?>&sub_id=<?php echo $sub_id?>&query=<?php echo $mirurl;?>'" value="Evaluate MIR System" /> <i><?php echo $sub['sub_Name'];?></i>

							</div>
							<div class="clear" style="height:0px;"></div>
						</div>
						<?php
					}
	            }
	            ?>
	            </div>
	            <?php
			}
   			?>
   			
  		</div>  
	</div>
</div>
</body>
</html>

