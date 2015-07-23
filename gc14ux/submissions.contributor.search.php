<?php
	require_once("models/config.php");
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Add Contributor :: <?php echo $loggedInUser->display_username; ?></title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" 
		src="http://yui.yahooapis.com/3.1.1/build/yui/yui-min.js"></script>

<script type="text/javascript"> 
/* <![CDATA[ */
YUI().use('node','event','node-base', function(Y) {
	var last = "";

	Y.on("focus", function (e) { e.target._node.value = ''; },"#contrib-search");

	Y.on("keyup", function (e) {
		var q = e.target._node.value;
		if ((q.length >= 3) && (q != last)) {
			last = q;
 
			var sURL = "identities.search.php?callback=loadResults&q="+escape(q);
			Y.Get.script(sURL, null);
		}
	},"#contrib-search");

	Y.on("domready", function (e) {
		var q = document.getElementById("contrib-search").value;
		if ((q.length >= 3) && (q != last)) {
			last = q;
 
			var sURL = "identities.search.php?callback=loadResults&q="+escape(q);
			Y.Get.script(sURL, null);
		}	
	});
	
	loadResults = function(query, results) {
		if (results.length > 0) {

			var list = document.getElementById("search-results");
			list.style.display = "block";
	
			//clean out old search results
			var divs = list.childNodes;
			for (i = divs.length - 1; i >= 0; i--) {
				list.removeChild(divs[i]);
			}
	
			for (r in results) {
				var p = results[r];
				var d = document.createElement("div");
				d.setAttribute("class", "profile");

				var info = Y.DOM.create("<div style='float:left;'><strong>"+p.fname+" "+p.lname+"</strong><br/>"+p.org+"<br/>"+p.title+"</div>");
				d.appendChild(info);

				var add = document.createElement("div");
				
				var plus = document.createElement("img");
				plus.setAttribute("src", "layout/add.png");
				plus.setAttribute("style", "float:right;font-size:2.5em;");
				add.appendChild(plus);
				d.appendChild(add);
				

				d.setAttribute("onclick", "addContributor("  + p.id + 
														",'" + p.fname + "'" +
														",'" + p.lname + "'" +
														",'" + p.org   + "'" +
														",'" + p.title + "');");

				d.appendChild(Y.DOM.create("<div class='clear'></div>"));
				list.appendChild(d);
			}
		}
	}
	
	addContributor = function(id, fname, lname, org, title) {
		if (window.opener && !window.opener.closed) {
			window.opener.addContributor(id,fname,lname);
		}
	}
});
/* ]]> */
</script>
</head>
<body>
<div style="padding:10px">
<h2>Search</h2>
<input type="text" id="contrib-search" value="search for contributors"/>
<div id="search-results"></div>
<p>Can't find the person you're looking for? <a href="submissions.contributor.create.php">Create a new Contributor Profile</a> for them.</p>
<input type="button" onclick="window.close()" value="Close Window"/>
</div>
</body>
</html>

