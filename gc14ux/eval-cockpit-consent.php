<?php 
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php?type=evaluator"); die(); }
	if (!userHasGivenGCConsent($loggedInUser)) { header("Location: consent.php"); die(); }

        if (!isset($_GET['task']) || !isset($_GET['assign_id']) || !isset($_GET['sub_id']) || !isset($_GET['query']))
	{
		header("Location: assignment.list.php"); die();
	}
	
	$tid       = $_GET['task'];
	$assign_id = $_GET['assign_id'];
	$sub_id    = $_GET['sub_id'];
	$query     = $_GET['query'];

        $sub = getSubmissionByID($sub_id);

	$task = getTask($tid);

        $candidates = userGetGCRelevance($loggedInUser, $assign_id) ;

        $state = array( 'os' => -1, 'la' => -1, 'rn' => -1, 'af' => -1, 'pt' => -1 );

	foreach ($state as $skey => $sval) {
	  $res_skey = 'result_'.$skey;
	  if (array_key_exists($res_skey,$candidates)) {
	    $val = $candidates[$res_skey];
	    if ($val>=0) {
	      $state[$skey] = $val;
	    }
	  }
	}


	## $candidates = userGetGCCandidates($loggedInUser, $tid, $query);	

	#foreach ($candidates as $c) {
	#  $state[$c] = $c['val'];
	#}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>MIREX Grand Challenge 2014 Usability Experience</title>

    <link rel="stylesheet" type="text/css"
	  href="http://fonts.googleapis.com/css?family=Archivo+Narrow:700,200"  />

    <link rel="stylesheet" type="text/css"
	  href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:700,200" />

    <link rel="stylesheet" type="text/css"
	  href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" />

    <link href='http://fonts.googleapis.com/css?family=Cabin+Condensed' rel='stylesheet' type='text/css'>

    <!-- also Open Sans Condensed -->


    <link rel="stylesheet" type="text/css"
	  href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>



        <!-- liteAccordion css -->
        <link href="css/liteaccordion.css" rel="stylesheet" />

        <!-- jQuery -->
<!--
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
-->
        <!-- easing -->
        <script src="js/jquery.easing.1.3.js"></script>

        <!-- liteAccordion js -->
        <script src="js/liteaccordion.jquery.js"></script>

 
   <script src="eval-cockpit/iframe-loader.js"></script>

    <style type="text/css">

/*
	.slide > h2.selected { background: red; }
*/
      html {
        padding: 0px;
        margin: 0px;
        border: 0px;
        background-color: #eeeeee;
      }

      body {
        padding: 5px;
        margin: 0 auto;
        border: 0px;
        width: 1200px;
        height: 850px;
        background-color: white;
      }

      #header {
        width: 100%;
      }

      #footer {
        width: 100%;
        padding: 8px;
        text-align: center;
        color: #3d85c6;      
        font-family: 'Bodoni', Arial, sans-serif;
        font-style: italic;
      }

      #eval-system-div {
        width: 690px; 
        height: 725px; 
 
        /* http://localhost:6969/greenstone3/interfaces/therin/img/bg.png */
        backgroundXX: url('/gc14ux-ex/interfaces/therin/img/bg.png') repeat scroll 0% 0% #A8C8CA;

      }

      #eval-system-iframe {
        width: 100%; 
        height: 100%; 
        margin: 0; 
        border: 0; 
        padding: 0;
      }

      .radio-label-combo {
        display: inline-block;
        text-align:center;
      }

      .radio-label-builtin-fonts {
        display: block;
        width: 100px; /* used to be 65px */
        font-family: 'Arial Narrow', sans-serif;
        XXfont-family: 'Cabin Condensed', sans-serif;
        XXfont-family: 'Open Sans', sans-serif;
        XXfont-weight: 300;
        XXfont-size: 8pt;
        font-size: 100%; /* used to be 55% */
        XXfont-size: 65%;
      }

      .radio-label {
         cursor: pointer;
      }

      .radio-label-combo input[type="radio"] {
         cursor: pointer;
      }

      .radio-label {
        display: block;
        width: 90px; /* 
used to be 63px */
        font-family: 'Cabin Condensed', sans-serif;
        font-size: 100%; /* used to be 65% */
      }

      .GC-Title {
        font-family: 'Bodoni', Arial, sans-serif;
        font-weight: bold;
        padding-right: 30px;
        color: #cfe2f3;
      }

      .GC-Title button {
       padding: 6px;
       cursor: pointer;
      }

      .GC-Entry {
        background-color: #cfe2f3; 
        padding: 5px;
      }

      .GC-Grader {
        border: 2px solid #cfe2f3;
        padding: 5px;
      }

      .GC-Task {
        width: 100%;
        background-color: #cfe2f3;
        color: white;
        padding: 5px;
	 margin: 4px;
      }

      .GC-Heading {
        font-family: 'Bodoni', Arial, sans-serif;
        font-weight: bold;
        color: #3d85c6;
        margin: 5px 5px 5px 10px;
      }

      #comments-area {
        width: 100%; 
        height: 120px;
        min-height: 80px;
        resize: vertical;        
      }

      .h1, h2, h3, button {
        font-family: Arial;
      }


      label {
        color: black;
      }

      fieldset {
        color: #3d85c6;      
      }


      legend {
        font-family: Arial, sans-serif;
        border: 1px solid;
      }

      #os-legend {
        font-size: 120%;
      }
      
      textarea {
        background-color: #cfe2f3;
        border: 1px solid #3d85c6;      
      }

      button {
        background-color: #cfe2f3;
        border: 1px solid #3d85c6;      
        font-size: 110%;
        font-weight: bold;
        padding: 8px;
      }

      .legend-icon-info { display: inline-block; }
      .legend-icon-info:hover { background-color: #999999; }



      .dialog {
	  font-size: 90%;
      }

      .ui-dialog-title {
	  font-size: 90%;
      }

      .ui-dialog {
	width: 500px;
      }

    </style>





<script type="text/javascript">
var state = <?php echo json_encode($state);?>

colorRow = function(candidate) 
{

	if ((state[candidate] != "")) {
	  $("#row-"+candidate).css("background-color", "#CFE2F3"); // Used to be #9c6
	}
}

updateGCRelevance = function(candidate, val) 
{
	var t = '<?php echo $tid; ?>';
	var a = '<?php echo $assign_id; ?>';
	var q = '<?php echo $query; ?>';

	var request = $.ajax({
	  url: "relevance.update.php",
	      data: { t   : t,
		      a   : a,
	     	      q   : q,
		      c   : escape(candidate),
	    	      v   : escape(val) }
	      });

	request.done(function( msg ) {
	    colorRow(candidate);
	  });
 
	request.fail(function( jqXHR, textStatus ) {
	    alert("updateGCRelevance() Request failed: " + textStatus );
	  });

	state[candidate]= val;

	logGCEvent(val, "judgment", candidate);
}



updateGCOpenText = function(open_text) 
{
	var t = '<?php echo $tid; ?>';
	var a = '<?php echo $assign_id; ?>';

	var request = $.ajax({
	      url: "opentext.update.php",
	      async: false,
              type: "POST",
	      data: { t   : t,
		      a   : a,
		      ot  : open_text }
	      });

	request.done(function( msg ) {
	    //console.log("Done ajax call: " + msg);
	    //logGCEventThenLoadURL("message length: " + open_text.length, "open-text-comment", "text", 'assignment.list.php');
            logGCEvent("message length: " + open_text.length, "open-text-comment", "text");
	  });

	request.fail(function( jqXHR, textStatus ) {
	    alert( "updateGCOpenText() Request failed: " + textStatus );
	  });


}



logGCEvent = function(candidate, action, value) 
{
	var t = '<?php echo $tid; ?>';
	var a = '<?php echo $assign_id; ?>';
	var q = '<?php echo $query; ?>';


	var request = $.ajax({
    	      url  : "log.update.php",
	      async: false,
	      data : { t   : a,
	     	       q   : q,
                       c   : candidate,
	     	       a   : action,
	     	       v   : value }
	      });


	request.done(function( msg ) {
	    //console.log("Logging: Done ajax call: " + msg);
	  });

	request.fail(function( jqXHR, textStatus ) {
	    alert( "logGCEvent() Request failed: " + textStatus );
	  });
}



logGCEventThenLoadURL = function(candidate, action, value, load_url) 
{
	var t = '<?php echo $tid; ?>';
	var a = '<?php echo $assign_id; ?>';
	var q = '<?php echo $query; ?>';


	var request = $.ajax({
	      url  : "log.update.php",
	      async: false,
	      data : { t   : a,
	     	       q   : q,
                       c   : candidate,
	     	       a   : action,
	     	       v   : value }
	      });


	request.done(function( msg ) {
	    //console.log("Logging: Done ajax call: " + msg);
            window.location.href = load_url;
	  });

	request.fail(function( jqXHR, textStatus ) {
	    alert( "logGCEventThenLoad() Request failed: " + textStatus );
	  });
}



JSON = {
  encode : function(input) {
    if (!input) return '""'
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

function saveComments()
{
  //console.log("*** save comments");

  //var open_text = $("#comments-area").val();
  //console.log("*** open text = " + open_text);

  var open_text = $("#comments-area").val();
  updateGCOpenText(open_text);

}


function unloadPage()
{
  // Make sure the open text comment is saved before unloading
  var open_text = $("#comments-area").val();
  updateGCOpenText(open_text);

  logGCEvent("", "unload", "page");
}


</script>



  </head>

  <body>



      <div id="main-content">
        <div id="side-by-side">
            <ol>
                <li>
                    <h2><span>Grand Challenge MIR System</span></h2>
                    <div id="eval-system-div" >

		      <iframe src="<?php echo $_GET['query'];?>" 
			      id="eval-system-iframe" ></iframe>

                    </div>
                </li>
                <li>
	<h2><span title="Click to Open/Close">Evaluation Form: Click Here</span></h2>
		    <div id="eval-slide">
                      <div style="padding: 15px; margin: 15px; background: white;">



    <div id="header">
      <div style="padding: 0px; vertical-align: middle;">
	<div style="float: left" ><h2 class="GC-Title">GC14UX</h2></div>
	<div style="float: left" ><h3 class="GC-Entry">Submission: <?php echo $sub['sub_Name']?></h3></div>
	<div style="float: right"><h3 class="GC-Grader">Grader ID: <?php echo $loggedInUser->display_username; ?></h3></div>
	
	<div style="float: left; width: 98%">
	  <h3 class="GC-Task" style="color: black;">
	    Task: Discover songs for your personal video
	      <span id="in-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	  </h3>
	</div>
      </div>
    </div>


    <div style="clear: both">
    </div>


<div id="in-dialog" title="Task Overview" class="dialog legend-info-dialog">
  <p> 
  Please focus on evaluating the interaction and experience with the
  system as a whole, and not just the results you get. Please be aware
  that these systems are using an open-source test dataset that is a
  collection of copyright free music, and therefore the results may
  not include popular music that many of us are familiar with.
  </p>
</div>

<div id="os-dialog" title="Overall Satisfaction" class="dialog legend-info-dialog">
  <p> 
      Overall satisfaction: How would you rate your overall satisfaction with the system?
  </p>
</div>


<div id="la-dialog" title="Learnability" class="dialog legend-info-dialog">
  <p> 
      Learnability: How easy was it to figure out how to use the system?
  </p>
</div>



<div id="rn-dialog" title="Robustness" class="dialog legend-info-dialog">
  <p> 
  Robustness:  How good is the system's ability to warn you when you're about to make a mistake, allow you to recover, or retrace your step?
  </p>
</div>



<div id="af-dialog" title="Affordance" class="dialog legend-info-dialog">
  <p> 
  Affordances: How well does the system allow you to perform what you want to do?
  </p>
</div>



<div id="pt-dialog" title="Feedback" class="dialog legend-info-dialog">
  <p> 
      Feedback: How well does the system communicate what’s going on? 
  </p>
</div>

<div id="ot-dialog" title="Open Text Feedback" class="dialog legend-info-dialog">
  <p> 
    Open Text Feedback: Grateful to have your feedback to the system in free text. Thanks!
  </p>
</div>
											    

<?php


$likert_order = array( "os", "la", "rn", "af", "pt" );


$likert_hash = 
  array(
    "os" => 
    array( "legend" => "Overall Satisfaction",
	   "values" => array( "Extremely unsatisfactory", "Unsatisfactory", "Slightly unsatisfactory", 
			      "Neutral", 
			      "Slightly satisfactory", "Satisfactory", "Extremely satisfactory" )
	   ),

    "la" =>
    array( "legend" => "Learnability",
	   "values" => array( "Very difficult", "Difficult", "Slightly difficult", 
			      "Neutral", 
			      "Slightly easy", "Easy", "Very easy" )
	   ),

    "rn" =>
    array( "legend" => "Robustness",
	   "values" => array( "Very poor", "Poor", "Slightly poor", 
			      "Neutral", 
			      "Slightly good", "Good", "Excellent" )
	   ),


    "af" =>
    array( "legend" => "Affordance",
	   "values" => array( "Very poor", "Poor", "Slightly poor", 
			      "Neutral", 
			      "Slightly good", "Good", "Excellent" )
	   ),

    "pt" =>
    array( "legend" => "Feedback",
	   "values" => array( "Very poor", "Poor", "Slightly poor", 
			      "Neutral", 
			      "Slightly good", "Good", "Excellent" )
	   )
	);
?>
	
<script>
<?php
    foreach ($likert_order as $lk) { 
      error_log($loggedInUser->display_username . " :: Simple radio category test: '$lk'");
?>
      console.log("Simple radio category test: '<?php echo $lk?>'");
<?php
    }
?>

</script>

<?php


    foreach ($likert_order as $lk) { # likert_key
      error_log($loggedInUser->display_username . " :: Inline building radio category: '$lk'");

?>
<script>
      console.log("Inline building radio category: '<?php echo $lk?>'");
</script>

<?php
      $lr = $likert_hash[$lk]; # likert_rec

      $default_vi = -1;
      if (array_key_exists($lk,$state) && $state[$lk]>=0) {
	$default_vi = $state[$lk];
      }

?>
	
	<form>
	  <fieldset style="border: 1px solid black; margin-bottom: 4px; <?php if ($default_vi>=0) { echo 'background-color: #CFE2F3;';}?>" 
	            id="row-<?php echo $lk?>">
	    <legend id="<?php echo $lk?>-legend">
	      <?php echo $lr['legend']?>
	      <span id="<?php echo $lk?>-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>


	    <div id="<?php echo $lk?>-radio" class="radio-div">

	     <?php
	       $vi = 1;
	       foreach ($lr['values'] as $lv) { # likert_value 
		 error_log("++++ ".$loggedInUser->display_username . " :: Inline building radio value: '$lv'");
	     ?>

<script>
      console.log("    Inline building radio val: '<?php echo $lv?>'");
</script>


	      <div class="radio-label-combo">
		<label for="<?php echo $lk?>-likert-<?php echo $vi?>" class="radio-label">
                  <?php echo $lv?>
		</label>
		<div>

		  <input type="radio" id="<?php echo $lk?>-likert-<?php echo $vi?>" name="<?php echo $lk?>-likert"
<?php 
        # 'check' his radio input if it matches the value retrieved from GC_Results
	if ($default_vi>=0 && ($default_vi==$vi)) {
	  echo 'checked="checked"';
	}
?>								       
		      onclick="updateGCRelevance('<?php echo $lk?>', '<?php echo $vi?>')" >
					   
		</div>
	      </div>

             <?php
		 $vi++;
               }         
	     ?>							    
	    </div>

	  </fieldset>
	</form>

<?php
    }
?>	
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	<!--
	   <script>
	     $( ".radio" ).buttonset();
	   </script>
	   -->



	<div style="padding: 4px;">
	  <h3 class="GC-Heading">
              Open Text Comments
              <span id="ot-info" class="ui-icon ui-icon-info legend-icon-info"></span>
          </h3>
	    <textarea id="comments-area" style="border: 1px solid black; height: 100px;"><?php echo $candidates['result_ot']?></textarea>
	</div>

	<div style="text-align: center; padding 10px 5px 5px 5px;">
	  <a href="assignment.list.php" class="GC-Title"><button>Return to Assignments</button></a>
	</div>



<script>
$( ".legend-info-dialog" ).dialog({ autoOpen:false, dialogClass: 'test', width: '500px' });


$('.legend-icon-info').click(function() {
  var icon_id = this.id
  var prefix = icon_id.substring(0,2);
  var dialog_id = prefix + '-dialog';
  $('#' + dialog_id).dialog('open');
});

</script>


		<script type="text/javascript">
			$(document).ready(function() {

			logGCEvent("", "load", "page");
			//$( ".ui-dialog" ).dialog({ width: 500 });

			$(window).unload(function() { unloadPage() });

			});
		</script>



	              </div>
                    </div> <!-- evaluation div -->
                </li>
            </ol>
            <noscript>
                <p>Please enable JavaScript to get the full experience.</p>
            </noscript>
        </div> <!-- side-by-side -->
    </div><!-- main-content -->


        <script>
            (function($, d) {
                // please don't copy and paste this page
                // it breaks my analytics!

                $('#side-by-side').liteAccordion(
	                             { containerWidth  : 1180,
	                               containerHeight : 730,
	                               theme: 'light',
                                       rounded : true
	                             } );

            })(jQuery, document);

        </script>

    <div id="footer" style="clear: both">
      Copyright IMIRSEL 2014
    </div>
  </body>
</html>
