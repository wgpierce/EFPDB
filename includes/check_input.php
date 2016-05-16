<?php

function check_input( &$chem_formula ) {
	//Upload file info and authentication
	
	$tmp_file = $_FILES['fileToUpload']['tmp_name'];
	$upload_name = $_FILES['fileToUpload']['name'];
	$file_info = pathinfo($_FILES['fileToUpload']['name']);

	$uploadOk = TRUE;

	echo "The file to upload is " . basename($upload_name) . "<br />";

	if ($_FILES['fileToUpload']['size'] > 20000000) {
		// Check if file size is less than 20MB
		echo "Sorry, your file size cannnot exceed 20MB.<br />";
		$uploadOk = FALSE;
	}
	
	if ($_FILES['fileToUpload']['size'] == 0) {
		// Check if file size is less than 20MB
		echo "Something went wrong, and your file failed to upload, or your file is empty.<br />";
		$uploadOk = FALSE;
	}
	
	if (mb_strlen($upload_name) > 225) {
		// Check if file name is < 255 characters
		echo "This file name is too long<br>";
		$uploadOk = FALSE;
	}

	if (!preg_match("`^[-0-9A-Z_\.]+$`i", $upload_name)) {
		echo "This file name has illegal characters or is empty<br>";
		$uploadOK = FALSE;
	}

	if ($file_info['extension'] != "xyz") {
		// Check if file is an xyz file
		echo "Sorry, only .xyz files are allowed.<br />";
		$uploadOk = FALSE;
	}
	
	$chem_formula = exec("python ../python/create_formula.py " . escapeshellarg($tmp_file));

	if ($chem_formula) {
		//TODO: Make sure formula actually makes sense - make regexp to check form
		//check to see that we actually have a chemical formula / is nonzero
		//if $chem_formula is 0, then all database entries will be returned
		echo "This is a valid molecule.<br>";
		echo "The molecule is $chem_formula.<br>";
			
	} else {
		echo "This is not a valid .xyz-formatted file<br>";
		echo "<br>Nice try, hackers<br>";
		$uploadOk = FALSE;
	}
	
	return $uploadOk;
}

?>