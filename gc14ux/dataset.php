<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }



        function formatBytes($filename, $precision = 2) {
	  $bytes = filesize($filename);
          $units = array('B', 'KB', 'MB', 'GB', 'TB');

          $bytes = max($bytes, 0);
          $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
          $pow = min($pow, count($units) - 1);

          // Uncomment one of the following alternatives
          $bytes /= pow(1024, $pow);
          // $bytes /= (1 << (10 * $pow));

          return round($bytes, $precision) . ' ' . $units[$pow];
        }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome <?php echo $loggedInUser->display_username; ?></title>
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

	  <h1>Jamendo 10,000 Song Dataset</h1>



	  <p>The dataset prepared for this Grand Challenge 
	  consists of 10,000 tracks selected from the Jamendo web
	  site.  It represents a randomly chosen subset the content
	  available at Jamendo that is published under the terms of the Creative Commons
	  Attribution-Non-Commercial-ShareAlike (by-nc-sa), where user-supplied
	  data has tagged a track with 2 or more genre categories.
	  For more details about usage of this dataset,
          see the LICENSE.txt file contained in the 
	  downloaded files.</p>


	  <p>The dataset contains the MP3 tracks and the metadata the Jamendo
	    site publishes on the respective items (represented in JSON format), retrieved
	    using the site&#39;s API (6th Aug 2014).  The dataset is available both zipped
	    up and as a tar-ball(<strong>you only need one of these</strong>); however, at 60+ Gb it is a non-trival size of file
	    to download over the web, and so we suggest you install a 
	    Download Manager extension to your browser if you do not already have one
	    and make use of that.  In a test using the <i>DownThemAll!</i> extension
	   to Firefox, downloading the dataset between University of Illinois at Urbana-Champaign and Waikato University
            in New Zealand took a little under 2 hours.
          </p>	

	  <ul>
	    <li><a href="dataset/gc14ux-jamendo-by-nc-sa-subset-metadata.csv">Metadata from Jamendo JSON (CSV)</a> (<?php echo formatBytes('dataset/gc14ux-jamendo-by-nc-sa-subset-metadata.csv') ?>)</li>
	    <li><a href="dataset/gc14ux-jamendo-by-nc-sa-subset.zip">gc14ux-jamendo-by-nc-sa-subset.zip</a> (<?php echo formatBytes('dataset/gc14ux-jamendo-by-nc-sa-subset.zip') ?>)</li>
	    <li><a href="dataset/gc14ux-jamendo-by-nc-sa-subset.tar.gz">gc14ux-jamendo-by-nc-sa-subset.tar.gz</a> (<?php echo formatBytes('dataset/gc14ux-jamendo-by-nc-sa-subset.tar.gz') ?>)</li>
	    <li><a href="http://ccmir-data.cite.hku.hk/gc14ux-jamendo-by-nc-sa-subset.zip">gc14ux-jamendo-by-nc-sa-subset.zip[CHINA MIRROR, expires in Oct 2013]</a></li>
          </ul>
								   
            <div class="clear" style="height:25px;"></div>
            
  		</div>
  
	</div>
</div>
</body>
</html>

