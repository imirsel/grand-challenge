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

	  <h1>2015 Jamendo 10,000 Song Dataset</h1>



	  <p>The dataset prepared for this Grand Challenge 
	  consists of 10,000 tracks selected from the <a href="https://www.jamendo.com">Jamendo</a> web
	  site.  It represents a subset the content
	  available at Jamendo that is published under the terms of the Creative Commons
	  Attribution-Non-Commercial-ShareAlike (by-nc-sa) license.	    
         </p>


	  <p>The dataset is comprised of the MP3 tracks and the metadata the Jamendo
	    site publishes on the respective items (represented in JSON format), retrieved
	    using the site&#39;s 
           <a href="https://developer.jamendo.com/v3.0/tracks">Tracks</a> API call.


	    Specifically we have iterated over the Genre tags provided by the site
	    using the <i>boost</i> attribute of the Track API
	    to concentrate the selected set of 10,000 songs to be the
	    ones that have proved popular over time
	    (<i>boost=popularity_total</i>).  To avoid skewing to
	    any particular artist, we discounted returned tracks
	    by a particular artist once the number went above 20.
	  </p>

	  <p>
	    The dataset is available through the links below.  The JSON information
	    is of a modest size, and can be be downloaded
	    by right clicking
	    on the respective links (option-click on a Mac).  Downloading a copy of the MP3 
	    files, however, 
	    is a more significant undertaking.  We have made the MP3 files
	    available in both ZIP and TAR.gz form  
	    (<strong>you only need one of these</strong>) but 
            at 60+ Gb it is a non-trival size of file
	    to download over the web, and so we suggest you install a 
	    Download Manager extension to your browser if you do not already have one
	    and make use of that.  In a test using the <i>DownThemAll!</i> extension
	   to Firefox, downloading the dataset between University of Illinois at Urbana-Champaign and Waikato University
            in New Zealand took a little under 2 hours.
          </p>	

	  <ul>
	   <li><a href="dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-trackids.json">Track Ids for the 10,000 Jamendo dataset as JSON format array</a> (<?php echo formatBytes('dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-trackids.json') ?>)</li>
	   <li><a href="dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-metadata.json">Metadata records for the 10,000 Jamendo dataset in JSON format</a> (<?php echo formatBytes('dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-metadata.json') ?>)</li>
	    <li><a href="dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.zip">gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.zip</a> (<?php echo formatBytes('dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.zip') ?>)</li>
	    <li><a href="dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.tar.gz">gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.tar.gz</a> (<?php echo formatBytes('dataset/gc15ux-jamendo-popularity-boosted-10000-dataset-mp3.tar.gz') ?>)</li>
<!--
	    <li><a href="http://ccmir-data.cite.hku.hk/gc15ux-jamendo-by-nc-sa-subset.zip">gc15ux-jamendo-by-nc-sa-subset.zip[CHINA MIRROR, expires in Oct 2013]</a></li>
-->

          </ul>
								   
	  <h3>Overview of dataset</h3>
          <p>
	  <a href="csv-data/genre-tags-freq-10000.csv">Genre Frequencies (CSV) of the 10,000 chosen tracks<a> <br/>
	  (note that more than genre tag can be assigned to a individual track)

	  </p>         
            <div class="clear" style="height:25px;"></div>
            
  		</div>
  
	</div>
</div>
</body>
</html>

