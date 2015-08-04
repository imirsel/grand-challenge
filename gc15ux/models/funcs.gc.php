<?php

require_once('JSON.php');

function gcStartsWith($haystack, $needle)
{
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
}



function logGCEvent($user, $task, $query, $cand, $action, $value) 
{
	global $db,$db_table_prefix;
	if ($user != NULL) 
	{
		$sql = "INSERT INTO
					".$db_table_prefix."GC_Event_Log
				SET
					event_Time = NOW(),
					event_Username = '".$db->sql_escape($user->clean_username)."',
					event_Task = '".$db->sql_escape($task)."',
					event_Query = '".$db->sql_escape($query)."',
					event_Candidate = '".$db->sql_escape($cand)."',
					event_Action = '".$db->sql_escape($action)."',
					event_Value = '".$db->sql_escape($value)."'";
		$db->sql_query($sql);

##		error_log("**** logging sql = $sql\n");
	}
}

function updateGCRelevance($user, $assign_ID, $query, $cand, $val) 
{
	global $db,$db_table_prefix;

	if ($user != NULL) {
			$sql = "UPDATE 
						".$db_table_prefix."GC_Results r
					SET 
						r.result_$cand = $val,
						r.result_Grader = '".$db->sql_escape($user->clean_username)."',
						r.result_Timestamp = now()
                                        WHERE
						r.result_ID = $assign_ID";

			$db->sql_query($sql);
	}
}

function updateGCOpenText($user, $assign_ID, $open_text) 
{
	global $db,$db_table_prefix;

	if ($user != NULL) {
			$sql = "UPDATE 
						".$db_table_prefix."GC_Results r
					SET 
						r.result_ot = '".$db->sql_escape($open_text)."',
						r.result_Grader = '".$db->sql_escape($user->clean_username)."',
						r.result_Timestamp = now()
                                        WHERE
						r.result_ID = $assign_ID";

			$db->sql_query($sql);
	}
}





function getGCSubmissionTasks()
{

  $submission_tasks = getSubmissionTasks(); 
  $gc_tasks = array();

  foreach ($submission_tasks as $task) {
    echo $task['name'];
    if (gcStartsWith($task['name'],"Grand Challenge")) {
      $gc_tasks[] = $task;
    }
  }
  
  return $gc_tasks;
}



function getGCSubmissions($loggedInUser)
{
  # 1. Get a list of all the tasks, and reduce down to just the ones that start "Grand Challenge"
  # 2. Then get a list of all the submissions for this user
  # 3. Finally work out which of the submissions are GC ones

  # 1.
  $submission_tasks = getSubmissionTasks(); 
  $gc_task_id = array();
  
  foreach ($submission_tasks as $task) {
    if (gcStartsWith($task['name'],"Grand Challenge")) {
      $gc_task_id[$task['id']] = 1;
    }
  }
  
  #2.
  $submissions = getSubmissions($loggedInUser);

  #3. Filtered user submissions
  $filtered_user_submissions = array();

  foreach ($submissions as $sub) {
    $sub_Task = $sub['sub_Task'];
    if (isset($gc_task_id[$sub_Task])) {
      $filtered_user_submissions[] = $sub;
    }
  }

  return $filtered_user_submissions;
}



function createGCSubmission($user, $sub, $contributors) 
{
	global $db,$db_table_prefix;
	
	if ($user != NULL) {
		$sql = "INSERT INTO ".$db_table_prefix."SubID
				SET sub_Hashprefix = '".$db->sql_escape($sub['hash'])."'";
		$db->sql_query($sql);

		$hashcode = $sub['hash'] . $db->sql_nextid();
		$hashcode_checksum = generateHashChecksum($hashcode);

		$sql = "INSERT INTO ".$db_table_prefix."Submissions
				SET sub_Username = '".$db->sql_escape($user->clean_username)."',
					sub_Hashcode = '".$db->sql_escape($hashcode)."',
					sub_Hashchecksum = '".$db->sql_escape($hashcode_checksum)."',
					sub_Readme 	 = '".$db->sql_escape($sub['readme'])."',
					sub_Name	 = '".$db->sql_escape($sub['name'])."',
					sub_Task	 = '".$db->sql_escape($sub['task'])."',
					sub_Status	 = 0,
					sub_Machine  = 'not assigned',
					sub_MIREX_Handler = '',
					sub_Path	 = '".$db->sql_escape($sub['url'])."',
					sub_PubNotes = '',
					sub_PrivNotes = '',
					sub_Updated	 = now(),
					sub_Created  = now()";
		$db->sql_query($sql);
		$subID = $db->sql_nextid();
		
		$sql = "INSERT INTO ".$db_table_prefix."Submission_Contributors (sub_ID, sub_ContributorID, sub_Rank) VALUES ";
		foreach ($contributors as $c) {
			$csql[] = "(".$subID.",".$db->sql_escape($c[1]).",".$db->sql_escape($c[0]).")";
		}
		$sql .= join(",", $csql);
		$db->sql_query($sql);
		
		return $hashcode;
	}		
}


#-------#



# Consider moving this to funcs.mirex.php ??

function getSubmissionByID($sub_id)
{
	global $db,$db_table_prefix;

		$sql = "SELECT 
					*
				FROM
					".$db_table_prefix."Submissions
				WHERE
					sub_ID='".$db->sql_escape($sub_id)."'";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row;

}



function userGiveGCConsent($user)
{
	global $db,$db_table_prefix;
	if ($user != NULL)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		$sql = "INSERT IGNORE INTO
					".$db_table_prefix."GC_Consent
				SET
					consent_Username='".$db->sql_escape($user->clean_username)."',
					consent_Status='Y',
					consent_Date = NOW(),
					consent_IP = '".$db->sql_escape($ip)."',
					consent_UserAgent = '".$db->sql_escape($ua)."'";

		$db->sql_query($sql);
	}
}

function userHasGivenGCConsent($user)
{
	global $db,$db_table_prefix;
	if ($user != NULL)
	{		
		$sql = "SELECT 
					*
				FROM
					".$db_table_prefix."GC_Consent
				WHERE
					consent_Username='".$db->sql_escape($user->clean_username)."'
				AND
					consent_Status='Y'";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row;
	}
	return false;
}


function getGCEvaluationTasks() 
{
	global $db,$db_table_prefix; 
	$tasks = array();

	$sql = "SELECT 
					t.task_ID,
					t.task_Name,
					t.task_Assignment_Size,
					t.sub_Task,
					t.task_MP3,
					t.task_Instructions
			FROM 
					".$db_table_prefix."GC_Tasks t
			";

	$result = $db->sql_query($sql);
	while (($row = $db->sql_fetchrow($result)) != null) {
		$tasks[$row['task_ID']] = $row;
	}
	return $tasks;	
}


function userGetGCAssignments($user, $task) 
{
	global $db,$db_table_prefix; 
	$assignments = array();

	if ($user != NULL) {
		$sql = "SELECT 
						*
				FROM 
						".$db_table_prefix."GC_Assignments
				WHERE
						assign_Task		= '".$db->sql_escape($task)."'
				AND
						assign_Grader 	= '".$db->sql_escape($user->clean_username)."'				
				";

		$result = $db->sql_query($sql);
		while (($row = $db->sql_fetchrow($result)) != null) {
		  $assignments[] = $row;
		}
	}
	return $assignments;
}



function countAvailableGCAssignments($tid) 
{
  # For GC, we are always interested in having further 
  # assignments given out

  # Change this to retrieve the given task's task_Assignment_Size

  $task = getGCEvaluationTask($tid);
  $lim = $task['task_Assignment_Size'];

##  error_log("*** Count available Assignments lim = $lim");

  return $lim;
}



function getGCEvaluationTask($id) 
{
	global $db,$db_table_prefix; 
	$tasks = array();  #### !!!! is this used?

	$sql = "SELECT 
					t.task_ID,
					t.task_Name,
					t.task_MP3,
					t.task_Assignment_Size,
					t.sub_Task,
					t.task_Instructions
			FROM 
					".$db_table_prefix."GC_Tasks t
			WHERE
					t.task_ID = '".$id."'";

	$result = $db->sql_query($sql);
	return $db->sql_fetchrow($result);
}


function userAssignGCQueries($user, $tid)
{
	global $db,$db_table_prefix; 
	
	if ($user != NULL) 
	{
		$task = getGCEvaluationTask($tid);
		$lim = $task['task_Assignment_Size'];

		$sub_Task_id = $task['sub_Task'];
		# Now retrieve all the entries in GC_Submissions that has this sub_Task_id

		$gc_subs = getSubmissionSubTasks($sub_Task_id);
		
		# Now randomly shuffle them
		shuffle($gc_subs);

		$sql = "INSERT INTO ".$db_table_prefix."GC_Assignments (assign_Task, sub_ID, assign_Query, assign_Grader, assign_Timestamp) VALUES";
		$csql = array();

		# And pick the top $lim
		for ($i=0; $i<$lim; $i++) {
		  $gc_sub = $gc_subs[$i];
		  $gc_sub_url = $gc_sub['path'];
		  $sub_id = $gc_sub['id'];


		  $csql[] = " (".$db->sql_escape($tid).", ".$db->sql_escape($sub_id).", '".$db->sql_escape($gc_sub_url)."', '".$db->sql_escape($user->clean_username)."', NOW() )" ;

		}

		$sql .= join(",", $csql);

		$db->sql_query($sql);
	}
}



function userInitGCResults($user, $tid)
{
	global $db,$db_table_prefix; 

	$assign_results = array();
	$assign_ids = array();

	//check if user is mirex organizer
	if ($user != NULL)
	{
		$sql = "SELECT *
				FROM
					".$db_table_prefix."GC_Assignments a				
				WHERE
					a.assign_Grader = '".$db->sql_escape($user->clean_username)."'
                                AND
					a.assign_Task = '".$db->sql_escape($tid)."'
				";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null)
		{
			$assign_results[] = $row;

			$assign_ids[] = "(".$row['assign_ID'].",".$row['sub_ID'].")";

		}

		# Init rows in the Results table
		$sql = "INSERT INTO ".$db_table_prefix."GC_Results (result_ID, sub_ID) VALUES ";
		$sql .= join(",", $assign_ids);
		$result = $db->sql_query($sql);

	}

	return $assign_results;
}



function userGetGCAssignmentStatus($user, $assign_id)
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if ($user != NULL)
	{
		$sql = "SELECT *
				FROM
					".$db_table_prefix."GC_Results
				WHERE
					result_ID = '".$db->sql_escape($assign_id)."'
				AND
					result_Active = 1
				";
	       
		$result = $db->sql_query($sql);

		$cols   = array("os", "la", "rn", "af", "pt");

		$status = array( "total" => 0, "completed" => 0);

		$row = $db->sql_fetchrow($result);
		foreach ($cols as $c) {
		  $status['total']++;
		  if ($row['result_'.$c]>0) {
		    $status['completed']++;
		  }
		}

		return $status;
	}

	return NULL;
}





function userGetGCRelevance($user, $assign_id) 
{
	global $db,$db_table_prefix;

	if ($user != NULL) {

			$sql = "SELECT *
					FROM ".$db_table_prefix."GC_Results r
                                        WHERE
						r.result_ID = $assign_id";

			## error_log("*** rel sql = $sql");

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			return $row;

	}
	return false;
}




?>
