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
        background: url('http://www.music-ir.org/gc14ux-ex/interfaces/therin/img/bg.png') repeat scroll 0% 0% #A8C8CA;

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


    </style>




  </head>

  <body>



      <div id="main-content">
        <div id="side-by-side">
            <ol>
                <li>
                    <h2><span>Grand Challenge MIR System</span></h2>
                    <div id="eval-system-div" >
		      <iframe src="http://www.music-ir.org/gc14ux-ex/thankyou-library/collection/basic-implementation-biased/page/about" 
			      id="eval-system-iframe" ></iframe>
                    </div>
                </li>
                <li>
	<h2><span title="Click to Open/Close">Evaluation Form</span></h2>
		    <div>
                      <div style="padding: 15px; margin: 15px; background: white;">



    <div id="header">
      <div style="padding: 0px; vertical-align: middle;">
	<div style="float: left" ><h2 class="GC-Title">GC14UX</h2></div>
	<div style="float: left" ><h3 class="GC-Entry">Submission: Some_team</h3></div>
	<div style="float: right"><h3 class="GC-Grader">Grader ID: Grading Hero #1</h3></div>
	
	<div style="float: left; width: 98%">
	  <h3 class="GC-Task">Task: Discover songs for your personal video</h3>
	</div>
      </div>
    </div>


    <div style="clear: both">
    </div>



<div id="os-dialog" title="Overall Satisfaction" class="dialog legend-info-dialog">
  <p> 
    Overall, how pleasurable do you find the experience of using this system?
  </p>
</div>


<div id="la-dialog" title="Learnability" class="dialog legend-info-dialog">
  <p> 
How easy was it to figure out how to use the system?
  </p>
</div>



<div id="rn-dialog" title="Robustness" class="dialog legend-info-dialog">
  <p> 
  How good is the system’s ability to warn you when you’re about to make a mistake and allow you to recover?
  </p>
</div>



<div id="ad-dialog" title="Affordance" class="dialog legend-info-dialog">
  <p> 
  How well does the system allow you to perform what you want to do?
  </p>
</div>



<div id="pt-dialog" title="Presentation" class="dialog legend-info-dialog">
  <p> 
      How well does the system communicate what’s going on? 
  </p>
  <ul>
    <li>How well do you feel the system informs you of its status?</li>
    <li>Can you clearly understand the labels and words used in the system?</li>
    <li> How visible are all of your options and menus when you use this system?</li>
  </ul>
</div>

<div id="ot-dialog" title="Open Feedback" class="dialog legend-info-dialog">
  <p> 
    An open-ended question is provided for evaluators to give feedback if they wish to do so.
  </p>
</div>


	<form >
	  <fieldset style="border: 1px solid black; margin-bottom: 4px;">
	    <legend id="os-legend">
	      Overall Satisfaction
	      <span id="os-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>
	    
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	    <div id="os-radio" class="radio-div">
	      <div class="radio-label-combo">
		<label for="os-likert-1" class="radio-label">
		  Extremely unsatisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-1" name="os-likert">
		</div>
	      </div>
	  
	      <div class="radio-label-combo">
		<label for="os-likert-2" class="radio-label">
		  Unsatisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-2" name="os-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="os-likert-3" class="radio-label">
		  Slightly unsatisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-3" name="os-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="os-likert-4" class="radio-label">
		  Neutral
		</label>
		<div>
		  <input type="radio" id="os-likert-4" name="os-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="os-likert-5" class="radio-label">
		  Slightly satisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-5" name="os-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="os-likert-6" class="radio-label">
		  Satisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-6" name="os-likert">
	    </div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="os-likert-7" class="radio-label">
		  Extremely satisfactory
		</label>
		<div>
		  <input type="radio" id="os-likert-7" name="os-likert">
		</div>
	      </div>
	      
	    </div>
	  </fieldset>
	</form>
	
	<!--
	   <script>
	     $( ".radio" ).buttonset();
	   </script>
	   -->

	<form>
	  <fieldset style="border: 1px solid black; margin-bottom: 4px;">
	    <legend>
	      Learnability
	      <span id="la-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>
	    
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	    <div id="la-radio" class="radio-div">
	      <div class="radio-label-combo">
		<label for="la-likert-1" class="radio-label">
		  Very difficult
		</label>
		<div>
		  <input type="radio" id="la-likert-1" name="la-likert">
		</div>
	      </div>
	  
	      <div class="radio-label-combo">
		<label for="la-likert-2" class="radio-label">
		  Difficult
		</label>
		<div>
		  <input type="radio" id="la-likert-2" name="la-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="la-likert-3" class="radio-label">
		  Slightly difficult
		</label>
		<div>
		  <input type="radio" id="la-likert-3" name="la-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="la-likert-4" class="radio-label">
		  Neutral
		</label>
		<div>
		  <input type="radio" id="la-likert-4" name="la-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="la-likert-5" class="radio-label">
		  Slightly easy
		</label>
		<div>
		  <input type="radio" id="la-likert-5" name="la-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="la-likert-6" class="radio-label">
		  Easy
		</label>
		<div>
		  <input type="radio" id="la-likert-6" name="la-likert">
	    </div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="la-likert-7" class="radio-label">
		  Very easy
		</label>
		<div>
		  <input type="radio" id="la-likert-7" name="la-likert">
		</div>
	      </div>
	      
	    </div>
	  </fieldset>
	</form>


	<form>
	  <fieldset style="border: 1px solid black; margin-bottom: 4px;">
	    <legend>
	      Robustness
	      <span id="rn-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>
	    
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	    <div id="rn-radio" class="radio-div">
	      <div class="radio-label-combo">
		<label for="rn-likert-1" class="radio-label">
		  Very poor
		</label>
		<div>
		  <input type="radio" id="rn-likert-1" name="rn-likert">
		</div>
	      </div>
	  
	      <div class="radio-label-combo">
		<label for="rn-likert-2" class="radio-label">
		  Poor
		</label>
		<div>
		  <input type="radio" id="rn-likert-2" name="rn-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="rn-likert-3" class="radio-label">
		  Slightly poor
		</label>
		<div>
		  <input type="radio" id="rn-likert-3" name="rn-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="rn-likert-4" class="radio-label">
		  Neutral
		</label>
		<div>
		  <input type="radio" id="rn-likert-4" name="rn-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="rn-likert-5" class="radio-label">
		  Slightly good
		</label>
		<div>
		  <input type="radio" id="rn-likert-5" name="rn-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="rn-likert-6" class="radio-label">
		  Good
		</label>
		<div>
		  <input type="radio" id="rn-likert-6" name="rn-likert">
	    </div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="rn-likert-7" class="radio-label">
		  Excellent
		</label>
		<div>
		  <input type="radio" id="rn-likert-7" name="rn-likert">
		</div>
	      </div>
	      
	    </div>
	  </fieldset>
	</form>


	<form>
	  <fieldset style="border: 1px solid black; margin-bottom: 4px;">
	    <legend>
	      Affordance
	      <span id="ad-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>
	    
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	    <div id="ad-radio" class="radio-div">
	      <div class="radio-label-combo">
		<label for="ad-likert-1" class="radio-label">
		  Very poor
		</label>
		<div>
		  <input type="radio" id="ad-likert-1" name="ad-likert">
		</div>
	      </div>
	  
	      <div class="radio-label-combo">
		<label for="ad-likert-2" class="radio-label">
		  Poor
		</label>
		<div>
		  <input type="radio" id="ad-likert-2" name="ad-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="ad-likert-3" class="radio-label">
		  Slightly poor
		</label>
		<div>
		  <input type="radio" id="ad-likert-3" name="ad-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="ad-likert-4" class="radio-label">
		  Neutral
		</label>
		<div>
		  <input type="radio" id="ad-likert-4" name="ad-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="ad-likert-5" class="radio-label">
		  Slightly good
		</label>
		<div>
		  <input type="radio" id="ad-likert-5" name="ad-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="ad-likert-6" class="radio-label">
		  Good
		</label>
		<div>
		  <input type="radio" id="ad-likert-6" name="ad-likert">
	    </div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="ad-likert-7" class="radio-label">
		  Excellent
		</label>
		<div>
		  <input type="radio" id="ad-likert-7" name="ad-likert">
		</div>
	      </div>
	      
	    </div>
	  </fieldset>
	</form>


	<form>
	  <fieldset style="border: 1px solid black; margin-bottom: 4px;">
	    <legend>
	      Presentation
	      <span id="pt-info" class="ui-icon ui-icon-info legend-icon-info"></span>
	    </legend>
	    
	    <!--
	       Add the following to an <input> to make it the default
	       checked="checked"
	      -->
	    
	    <div id="pt-radio" class="radio-div">
	      <div class="radio-label-combo">
		<label for="pt-likert-1" class="radio-label">
		  Very poor
		</label>
		<div>
		  <input type="radio" id="pt-likert-1" name="pt-likert">
		</div>
	      </div>
	  
	      <div class="radio-label-combo">
		<label for="pt-likert-2" class="radio-label">
		  Poor
		</label>
		<div>
		  <input type="radio" id="pt-likert-2" name="pt-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="pt-likert-3" class="radio-label">
		  Slightly poor
		</label>
		<div>
		  <input type="radio" id="pt-likert-3" name="pt-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="pt-likert-4" class="radio-label">
		  Neutral
		</label>
		<div>
		  <input type="radio" id="pt-likert-4" name="pt-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="pt-likert-5" class="radio-label">
		  Slightly good
		</label>
		<div>
		  <input type="radio" id="pt-likert-5" name="pt-likert">
		</div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="pt-likert-6" class="radio-label">
		  Good
		</label>
		<div>
		  <input type="radio" id="pt-likert-6" name="pt-likert">
	    </div>
	      </div>
	      
	      <div class="radio-label-combo">
		<label for="pt-likert-7" class="radio-label">
		  Excellent
		</label>
		<div>
		  <input type="radio" id="pt-likert-7" name="pt-likert">
		</div>
	      </div>
	      
	    </div>
	  </fieldset>
	</form>




	<div style="padding: 4px;">
	  <h3 class="GC-Heading">
              Open Text Comments
              <span id="ot-info" class="ui-icon ui-icon-info legend-icon-info"></span>
          </h3>
	    <textarea id="comments-area"></textarea>
	</div>

	<div style="text-align: center; padding 10px 5px 5px 5px;">
	  <a href="index.php" class="GC-Title"><button>Submit and Go to Home</button></a>
	</div>



<script>
$( ".legend-info-dialog" ).dialog({ autoOpen:false, dialogClass: 'test' });


$('.legend-icon-info').click(function() {
  var icon_id = this.id
  var prefix = icon_id.substring(0,2);
  var dialog_id = prefix + '-dialog';
  $('#' + dialog_id).dialog('open');
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
