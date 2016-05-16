<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="site_content">
	<!-- Upload PHP content -->
	<p class="emph">Upload your file</p>
    <form action="upload_action.php" method="POST" enctype="multipart/form-data">
	    <p>Select file to upload:</p>
	    <input type="file" name="fileToUpload" id="fileToUpload">
	    <input type="submit" value="Submit" name="submit">
    </form>
	<br>
	<!--Hyperlink to the coordinates changing site-->   
	<p>If your file is not in xyz coordinates, <a href="http://www.webqc.org/molecularformatsconverter.php">click here</a>
		 to convert its geometry to xyz format
	</p>
	<p>If you are having trouble uploading your file, <a href="http://en.wikipedia.org/wiki/EXY_file_format#Example">click here</a>
		to see an example of a correctly formatted xyz file
	</p>
	<p>If you are looking to upload proteins with our system, <a href="http://bioefp.org">click here</a>
		to go to our other site
	</p>
	
</div>

<?php require('../includes/footer.html'); ?>