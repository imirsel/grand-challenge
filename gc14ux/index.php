<?php 
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX::Grand Challenge</title>
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
            <h1>Welcome to the MIREX Grand Challenge</h1>
            
              <p>Welcome to the MIREX Grand Challenge <?php echo $MIREX_year;?> web site. 
	         Through this web site you can:
              </p>
	      <ul>
	        <li>
	          <a href="#about">Learn more about this year's 
		  usability experience challenge (GC14UX)</a>
	        </li>

		<li>
	          <a href="#evaluate">Sign up as one of our evaluators</a>
	        </li>

	        <li>
	          <a href="#submit">Register and submit an entry for the challenge</a>
	        </li>
		
	      </ul>
	      
	      <a name="about" />
	      <h2>GC14UX</h2>

	    <p>
	      For the inaugural year of the MIREX Grand Challenge the
	      focus is the user experience of a complete MIR system
	      designed. For our sample task, we will evaluate people attempting to find music for a backing
	      track to some video footage.
	    </p>
    
            <p>
              For more details see the <a href="/mirex/wiki/2014:GC14UX">Call
              for Participation (CfP) announcement</>.
            </p>
	


           <a name="evaluate" />		  
           <h2>Become an Evaluator, Today!</h2>

	   <p>
               In order to become an evaluator for MIREX GC14UX, there are
	       two steps to complete: first 
	       <a href="register.php">register</a>
               (if you do not already have an account at the MIREX site)
	       then proceed to 
	       <a href="login.php?type=evaluator">login as an evaluator </a>
               where you can access the MIR systems assigned to you to evaluate.
	   </p>



	   <h3>Evaluation Task Overview</h3>
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
	       After signing up as an evaluator for GC14UX you will be
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


	   <a name="data" />
	   <h2>Get the Data</h2>
	   <p>
		All participating GC14UX systems must use the GC14UX song dataset.
		The dataset can be downloaded once you are logged-in as a submitter under the "Download dataset" link.
	   </p>

           <a name="submit" />		  
           <h2>Submit an entry</h2>
        
           <p>
               In order to submit an entry into MIREX GC14UX, there are
	       two steps to complete: first 
	       <a href="register.php">register</a>
               (if you do not already have an account at the MIREX site)
	       then proceed to 
	       <a href="login.php?type=submitter">login as a submitter</a>.
	       The link to submit an entry only appears once you
	       are registered and logged in.  A registered
	       user can submit more than one entry if they wish (see below);
	       the key information we gather for each entry is
	       the URL to the main page of the system, and
	       some README text from the submitter providing
	       some details about the submission.

           </p>

	   <p>The registration process for GC14UX is based on the one developed for MIREX.
  
		Once you have registered with a login and password,
		you'll need to create an identity which includes your
		name and institutional affiliation. You can create
		multiple identities if you have multiple affiliations,
		or if your affiliation changes. However, in order to
		preserve the integrity of our historical data, your
		identities cannot be edited, changed, or deleted by
		you.
	   </p>
			
	   <p>
	        After you have completed your identity profiles, you
	        can start creating submissions to the Usability
	        Experience challenge. When creating a submission you
	        will be asked to identify each person that has
	        contributed to the submission so that they maybe
	        credited in the results (in much the same way that you
	        would identify authors of a conference/journal
	        paper). If a person has already signed up to the
	        submission system or been added to another submission
	        they may be found and selected using the search box,
	        alternatively they may be added to the system).
	   </p>
	
	    <p>
	      Next enter the URL to the &quot;home&quot; page of the
	      system you are entering into GC14UX.  For example, ...

	    </p>		

	    <p>
	        Finally, you will be asked to enter the contents of a
	        README file into the submission
	        form. The entered information should give some 
		background to MIR being used ...
	    </p>



            <div class="clear"></div>
        </div>

   </div>
</div>
</body>
</html>


