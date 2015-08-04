<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
	if(!$loggedInUser->isGroupMember(2)) { header("Location: login.php"); die(); }
	$licenses = getLicenses();
	if (!empty($_POST) && isset($_POST['sub_ID'])) {
	
		$sub = array();
		$sub['sub_ID'] = $_POST['sub_ID'];
		$sub['sub_Hashcode'] = $_POST['sub_Hashcode'];
		$sub['sub_Status'] = $_POST['sub_Status'];
		$sub['sub_PubNotes'] = $_POST['sub_PubNotes'];
		$sub['sub_PrivNotes'] = $_POST['sub_PrivNotes'];
		$sub['sub_Machine'] = $_POST['sub_Machine'];
		$sub['sub_Path'] = $_POST['sub_Path'];
		$sub['sub_MIREX_Handler'] = $loggedInUser->clean_username;
		updateSubmissionAdmin($sub);
		
		header("Location: submissions.admin.php?updated"); die;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Submissions Admin :: <?php echo $loggedInUser->display_username; ?></title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" 
		src="http://yui.yahooapis.com/3.1.1/build/yui/yui-min.js"></script>

<script type="text/javascript"> 
/* <![CDATA[ */
YUI().use('event','node-base', function(Y) {
	Y.on("change", function (e) {
		var q = e.target._node.value;
		var sURL = "submissions.admin.php?filter=task&v="+escape(q);
		window.location.href=sURL;
	},"#taskfilter");
	Y.on("change", function (e) {
		var q = e.target._node.value;
		var sURL = "submissions.admin.php?filter=status&v="+escape(q);
		window.location.href=sURL;
	},"#statusfilter");
	Y.on("change", function (e) {
		var q = e.target._node.value;
		var sURL = "submissions.admin.php?filter=machine&v="+escape(q);
		window.location.href=sURL;
	},"#machinefilter");
	
});

/* ]]> */

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
			<h1>Filter Submissions:</h1>
			<div>
				<select id="taskfilter"><option>by Task</option><?php 
				$tasks = getSubmissionTasks();
				foreach ($tasks as $task) {	?><option value='<?php echo $task['id'];?>'><?php echo $task['name'];?></option><? }
				?></select>
				<br/>
				<select id="statusfilter"><option>by Status</option><?php 
				$stats = getStatuses();
				foreach ($stats as $id=>$stat) { ?><option value='<?php echo $id;?>'><?php echo $stat;?></option><? }
				?></select>
				<select id="machinefilter"><option>by Machine</option><?php 
				$machs = getMachineNames();
				foreach ($machs as $mach) { ?><option value='<?php echo $mach?>'><?php echo $mach;?></option><? }
				?></select>
			</div>
				<div><a href="submissions.admin.php">Clear Filter</a></div>
        	<?php if (isset($_GET['updated'])) {
            ?>
            <div id="success">
                <p>Submission Updated</p>            
            </div>
            <?php        	
        	}
			?>        
			<h1>Submissions List:</h1>
				<div class="sub">
					<div class="sub-shortcode"><strong>Shortcode</strong></div>
					<div class="sub-info"><strong>Submission</strong></div>
					<div class="clear" style="height:0px;"></div>
				</div>
            	<?php

				switch ($_GET['filter']) {
					case "task" : $submissions = getSubmissionsByTask($_GET['v']); break;
					case "status" : $submissions = getSubmissionsByStatus($_GET['v']); break;
					case "machine" : $submissions = getSubmissionsByMachine($_GET['v']); break;
					default: $submissions = getAllSubmissions();
				}
            	if(count($submissions) > 0) {
					foreach ($submissions as $sub) {
            		?>
            		<div class="sub">
						<div class="sub-shortcode"><span style="font-size:1.8em"><?php echo $sub['sub_Hashcode'];?></span></div>
						<div class="sub-info">
						<form action="submissions.admin.php" method="post">
							<input type="hidden" name="sub_Hashcode" value="<?php echo $sub['sub_Hashcode'];?>"/>
							<input type="hidden" name="sub_ID" value="<?php echo $sub['sub_ID'];?>"/>
							<strong><?php echo stripslashes($sub['sub_Name']);?></strong> (<select name="sub_Status"><?php 
							foreach ($stats as $id=>$stat) { ?><option value='<?php echo $id;?>'<?php if ($id == $sub['sub_Status']) { echo "selected='selected'"; }?>><?php echo $stat;?></option><? }
							?></select>)<br/>
							<div style="font-size:0.8em;">
							Date Created: <?php echo $sub['sub_Created'];?> |
							Last Updated: <?php echo $sub['sub_Updated'];?>
							</div>
							<div>Task: <em><?php echo stripslashes($sub['task_Name']);?></em></div>
							<div>Contributors (<a href="mailto:<?php echo generateEmailList($sub['sub_ID']);?>">Send email</a>): <em><?php echo join(", ",$sub['contributors']);?></em></div>
							<div style="font-size:0.8em"><a href="#" onclick="document.getElementById('sub-details-<?php echo $sub['sub_Hashcode'];?>').style.display='block';this.style.display='none';">expand details</a></div>
							<div id="sub-details-<?php echo $sub['sub_Hashcode'];?>" style="display:none;">
								<div>Abstract: <?php echo ((file_exists($MIREX_absdir . $sub['sub_Hashcode'] . ".pdf")) ? "<a href='/mirex/abstracts/" . $MIREX_year . "/".$sub['sub_Hashcode'].".pdf'>View</a>"  : "Not uploaded")?></div>
								<div>License: <?php echo ($licenses[$sub['sub_License_Type']]);?></div>
								<div>README:</div>
								<textarea disabled=disabled name="readme" style="width:375px;height:100px;"><?php echo stripslashes($sub['sub_Readme']);?></textarea>
								<div>Public Notes (visible to the submitter):</div>
								<textarea name="sub_PubNotes" style="width:375px;height:100px;border:2px solid #cfc;"><?php echo stripslashes($sub['sub_PubNotes']);?></textarea>
								<div>Private Notes (visible only to IMIRSEL):</div>
								<textarea name="sub_PrivNotes" style="width:375px;height:100px;border:2px solid #fcc;"><?php echo stripslashes($sub['sub_PrivNotes']);?></textarea>
								<div>Machine:
								<select name="sub_Machine"><option value=''>unassigned</option><?php 
								foreach ($machs as $mach) { ?><option value='<?php echo $mach;?>' <?php if ($mach == $sub['sub_Machine']) { echo "selected='selected'"; }?>><?php echo $mach;?></option><? }
								?></select></div>
								<div>Path: <input type="text" name="sub_Path" value="<?php echo $sub['sub_Path'];?>" size="45"/></div>
							</div>
							<div>Last Handled By: <?php echo ($sub['sub_MIREX_Handler'] ? $sub['sub_MIREX_Handler'] : "no one");?></div>
							<div>Update this Submission <input type="submit" value="Update Submission"/>
						</div>
			            <div class="clear"></div>
			            </form>
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

