<!doctype html>
<html lang="en">
	<head>
	    	<title>EFPDB Server</title>
	    	<!--meta info-->
	        <meta charset="utf-8">
	        <meta http-equiv="x-ua-compatible" content="ie=edge">
	        <meta http-equiv="Content-Script-Type" content="text/javascript" />
	        
	        <meta name="keywords" content="Effective fragment potential EFP xyz file ">
	        <meta name="description" content="Effective Fragment Potential (EFP) online generation tool and collection database">
	        <meta name="author" content="William Pierce">
	        <meta name="viewport" content="width=device-width, initial-scale=1">
	        
			<!-- favicon -->
	        <link rel="icon" type="image/ico" href="faviconCH4.ico">
			<!--CSS -->
	        <!--   <link rel="stylesheet" href="css/normalize.css">   -->
	        <link rel="stylesheet" href="css/main.css">
	        <!--JQuery-->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	        <!--javascript-->
	        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
			<!--The rest of our javascript  -->
		    <script src="js/plugins.js"></script>
		    <script src="js/main.js"></script>
	</head>
	
	<body>
		<div id="header">
		<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        	<p> &lt; we can put any picture we want here &gt; <br/>like <strong>EFPDB.org</strong></p>
		</div>
		<nav> <!--align="center"-->
				<ul>
					<!-- Separators are just "|" here - can easily be vamped up-->
					<a href="index.php"><li>Home</li></a>	
					<li class="sep">|</li>		
					<a href="php/about.php"><li>About</li></a>
					<li class="sep">|</li>
					<a href="php/upload.php"><li>Upload</li></a>
					<li class="sep">|</li>
					<a href="php/database_list.php"><li>Database</li></a>
				</ul>
		</nav>

		

	<div id="main">
		<p>Welcome to EFPDB.org!</p>
		<p>In this site, we host resultant calculations from EFP potentials</p>
    	<p>To upload a file, go to <a href="php/upload.php">uploads</a>.</p>
    </div>	

	
<!-- not sure of this	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>	
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/{{JQUERY_VERSION}}/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-{{JQUERY_VERSION}}.min.js"><\/script>')</script>
       -->        
        
<!-- Insert Google Analytics later if desired
	
 <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
-->
    <footer align="center">
		<ul>
			<li>Web Design Contact:</li>
			<li><a href="mailto:wgpierce01 at purdue.edu">wgpierce at purdue.edu</a></li>
			<li><a href="https://github.com/wgpierce">github.com/wgpierce</a></li>
		</ul>
		<p>© Lyudmila V. Slipchenko, Pradeep K Gurunathan, William Pierce</p>
		<p>Purdue University</p>
	</footer>
</body>   
</html>