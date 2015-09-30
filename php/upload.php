<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<!-- Upload PHP content -->
	<p style="font-size: 30px">Upload your file</p>
    <form action="upload_action.php" method="POST" enctype="multipart/form-data">
	    <p>Select file to upload:</p>
	    <input type="file" name="fileToUpload" id="fileToUpload">
	    <p>Charges: (between -20 and 20): </p>
	    <p><input type="number" name="charge" min="-20" max="20" value ="charge"></p>
	    <br />
	    <input type="submit" value="Submit" name="submit">
    </form>
	
	<!--Hyperlink to the corrdinates changing site-->   
	<p>If your file is not in xyz coordinates, <a href="http://www.webqc.org/molecularformatsconverter.php">click here</a>
		 to convert its geometry to xyz format
	</p>
	
	
</div>

<?php require('../includes/footer.html'); ?>