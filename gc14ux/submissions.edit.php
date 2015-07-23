<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	$readme_default = <<<EOF
	Your README should contain exhaustive, detailed, and specific information about how to run your submission for the specified task. It is better to err on the side of providing too much information here than too little.
EOF;
	$readme_default = trim($readme_default);
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
	$edit = false;
	$contribs = array();
	$errors = array();
	
	if (isset($_GET['edit']) && preg_match("/^[0-9]+$/", $_GET['edit'])) {
		// we're editing this i guess.
		$sub = getSubmissionUser($loggedInUser, $_GET['edit']);

		if (!(($sub['status'] == 0) || ($sub['status'] == 1) || ($sub['status'] == 7))) {
			$errors[] = "Your submission cannot be edited. This form will create a new submission. (" . $sub['status'] . ")";
			$edit = false;
			$sub = null;
		}
		else {
			$edit = true;

			$contribs = $sub['contributors'];
		}
	}
	elseif (!empty($_POST)) {
		$sub['name'] 	= trim($_POST["subname"]);
		$sub['task'] 	= trim($_POST["subtask"]);
		$sub['readme'] 	= trim($_POST["subreadme"]);
		$sub['url'] 	= trim($_POST["suburl"]);
		$sub['id'] 		= trim($_POST['subid']);

		$contribs = json_decode(stripslashes($_POST['contributors-list']));

		if (!isset($sub['name']) || (isempty($sub['name']))) {
			$errors[] = "Submission name is required";
		}
		if (!isset($sub['task']) || (isempty($sub['task']))) {
			$errors[] = "Submission task is required";
		}
		if (!isset($sub['readme']) || (isempty($sub['readme'])) || ($sub['readme'] == $readme_default)) {
			$errors[] = "README is required";
		}
		if (!isset($sub['url']) || (isempty($sub['url']))) {
			$errors[] = "URL to MIR system being evaluated is required";
		}
		else {
		  if (!gcStartsWith($sub['url'],"http")) {
			$errors[] = "URL needs to start http:// or https://";
		  }
		}

		if (empty($contribs)) {
			$errors[] = "At least one contributor is required";
		}
		else {
			$initials = array();
			foreach ($contribs as $c) {
				$initials[$c[0]] = substr($c[3],0,1);				
			}
			$lim = min(count($initials), 5);
			if ($lim == 1) {
				$servHash = strtoupper(substr($contribs[0][2],0,1) . substr($contribs[0][3],0,1)	);
			}
			else {
				$servHash = strtoupper(join("", array_slice($initials, 0, $lim)));
			}

			$sub['hash'] = $servHash;
		}
		
		if (count($errors) == 0) {		
			if (isset($_POST['subid'])) {
				$hash = updateSubmissionUser($loggedInUser, $sub, $contribs);
			}
			else {
				$hash = createGCSubmission($loggedInUser, $sub, $contribs);
			}
			header("Location: submissions.edit.php?added=".$hash);
			die();
		}		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>MIREX :: New Submission :: <?php echo $loggedInUser->display_username; ?></title>
  <link href="mirex.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
addContributor = function (id, fname, lname) {
	var x = document.getElementById("contrib-zero");
	if (x) { x.parentNode.removeChild(x); }
	var contributors = eval( document.getElementById("contributors-list").value );

	for (var i = 0; i < contributors.length; i++) {
		if (contributors[i][1] == id) return;
	}

	var pos = contributors.length+1;
	document.getElementById("contributors").appendChild(makeContributorDiv(pos, id, fname, lname));

	contributors.push([pos,id,fname,lname]);
	document.getElementById("contributors-list").value = JSON.encode(contributors)
}

removeContributor = function(id) {
	var contributors = eval( document.getElementById("contributors-list").value );

	var nc = []
	var con = document.getElementById("contributors");
	for (var i = con.childNodes.length-1; i >= 0; i--) {
		if (con.childNodes[i].localName == "div") {
			con.removeChild(con.childNodes[i]);
		}
	}

	var p = 1;
	for (var i = 0; i < contributors.length; i++) {
		var c = contributors[i];
		if (c[1] != id) { 
			c[0] = p;
			nc.push(c);
			con.appendChild(makeContributorDiv(c[0], c[1], c[2], c[3]));
			p++;
		}
	}
	document.getElementById("contributors-list").setAttribute("value", JSON.encode(nc));
}

makeContributorDiv = function(rank, id, fname, lname) {
	var info = document.createElement("div");
	info.appendChild(document.createTextNode(rank + ". " + fname + " " + lname));
	
	var img = document.createElement("img");
	img.setAttribute("src", "layout/delete.png");
	img.setAttribute("onclick", "removeContributor("+id+")");
	info.appendChild(img);
	
	return info;
}

JSON = {
  encode : function(input) {
    if (!input) return 'null'
    switch (input.constructor) {
      case String: return '"' + input + '"'
      case Number: return input.toString()
      case Array :
        var buf = []
        for (i in input)
          buf.push(JSON.encode(input[i]))
            return '[' + buf.join(', ') + ']'
      case Object:
        var buf = []
        for (k in input)
          buf.push(k + ' : ' + JSON.encode(input[k]))
            return '{ ' + buf.join(', ') + '} '
      default:
        return 'null'
    }
  }
}
</script>


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
            <?php 
			if ($_GET['added']) {
			?>
			<h1>Submission created</h1>

			<div id="success">
				<p>Thank you for your submission.  We have entered your MIR system into the set
			          of web systems that will be evaluated by our volunteer testers.</p>
				
				<p>Your submission has been assigned a shortcode identifier based
				on the initials of the contributors and the number of other submissions we've
				received by groups with similar names.</p>
				
				<p style="font-size:1.3em;">Your submission shortcode is: <?php echo $_GET['added'];?></p>


				<p>The shortcode is a useful detail to remember as it quickly identifies a particular
                                submission. If in any doubt, you can always retrieve your submission 
				information including the shortcodes for all your submissions by returning to your
				<a href="submissions.view.php">submissions page</a>. That page also contains a link
				back to this page, so you can return to these instructions at any time. Your
				submission is visible to all listed contributors with MIREX accounts, but only you 
				will be able to edit it.
				</p>
				
<!--
  <script>
	  $(function() {
	      var sub_Status = "<?php echo $_GET['status']?>";
              if (sub_Status == "") {
                sub_Status = 0;
              }
	      var sub_Hashcode = "<?php echo $_GET['added'];?>";
	      resolveSFTPStatus(sub_Status,sub_Hashcode);
	    });
  </script>
-->
			</div>
            <?php
            }
            else {
            	?>
	            <h1><?php echo ($edit ? "Edit" : "Start")?> submission</h1>
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
			<div>
				<dl>
					<dt>One GC14UX system for evaluation per submission record</dt>
					<dd>You must create a separate submission record for each MIR system you intend to 
						submit to. </dd>
					<dt>README</dt>
					<dd>Your readme content should provide some overview information
                                                about how the web site is accessed.</dd>
				</dl>
			</div>

			<form id="submission-form" action="submissions.edit.php" method="post">
			<?php 
				if (isset($sub['id'])) {
			?>
				<input type="hidden" name="subid" value="<?php echo $sub['id'];?>"/>
			<?php
				}
			?>
            	<div>
                    <label>Submission Name:</label>
                    <input type="text" name="subname" value="<?php echo stripslashes($sub['name']);?>" size="30"/>
                </div>

                <div>
                	<label>Task:</label>
                	<select name="subtask">
                	<?php 

                	
                	$tasks = getGCSubmissionTasks();

                	foreach ($tasks as $task) {
                		$selected = '';
                		if ($task['id'] == $sub['task']) {
                			$selected = " selected='true'";
                		}
                		?><option value='<?php echo $task['id'];?>'<?php echo $selected;?>><?php echo $task['name'];?></option><?php
                	}
                	?>
                	</select>
				</div>

                <div>
                	<label>Contributors:</label>
                	<div style="float:left;" id="contributors">
						<input type="button" onclick="window.open('submissions.contributor.search.php', 'searchpop', 'width=475');return false;" value="Add Contributor"/>
						<input type="hidden" name="contributors-list" id="contributors-list" value='<?php echo json_encode($contribs);?>'/>
						<?php
						if (!empty($contribs)) {
							foreach ($contribs as $c) {
								echo "<div>", $c[0], ". ", $c[2], " ", $c[3], "<img src='layout/delete.png' onclick='removeContributor(",$c[1],")'/></div>";
							} 
						}
						else {
						?>
	                		<div id="contrib-zero" class="alert"><strong>Don't forget to add 
	                		yourself as a contributor!</strong><br/>Contributor names will be published 
	                		in the MIREX results and in the MIREX Abstracts digital library
	                		in the order in which you enter them here.</div>
						<?php 
						}
						?>
                	</div>
                	<div class="clear"></div>
                </div>                

            	<div>
                    <label>Readme:</label>
                    <textarea style="height:150px; width:500px;" name="subreadme"
                    ><?php echo (isset($sub['readme']) ? stripslashes($sub['readme']) : $readme_default);?></textarea>
                </div>

            	<div>
                    <label>URL:</label>
                    <input type="text" name="suburl" value="<?php echo $sub['url'];?>" size="80"/>
                </div>


				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Submit"/>
				</div>
			</form>
  		<?php 
  		}
  		?>
		</div>
	</div>
</div>
</body>
</html>

