<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>Click <a href="upload.php">here</a> to return to the previous page to try again or submit another molecule</p>
	<br />
	<p>
		<?php
		if(isset($_POST['submit'])) {
			$target_dir = "../database/xyz_files/";
			$input_charge = intval($_POST['charge']);
			$uploadOk = 1;
			
			$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);

			//Get the upload file information
			$fileinfo = pathinfo($target_file);
			
			// Check if file size is less than 20MB
			if ($_FILES['fileToUpload']['size'] > 20000000) {
			    echo "Sorry, your file size cannnot exceed 20MB.<br />";
			    $uploadOk = 0;
			}
	
			// Check if file is an xyz file 
			// TODO: needs to be more robust with security - this also doesn't block pseudo files
			if($fileinfo['extension'] != "xyz"){
			    echo "The uploaded file is: <br \>".basename($_FILES['fileToUpload']['name'])."<br /><br />";
			    echo "Sorry, only .xyz file is allowed.<br />";
			    $uploadOk = 0;
			}
				//Note $_FILES['fileToUpload']['name'] isn't actually a file
			
				
			if($uploadOk) {
				//check to make sure it is actually a valid file
				//TODO: Make validity checks more robust
				
				 
				//Compare to previous files
				//Compare to temp file since not uploaded yet
				$tmp_file = $_FILES['fileToUpload']['tmp_name'];
				$file_exists = exec("python ../python/molecule_exists.py " . escapeshellarg($tmp_file), $return_array);
				
				/*
				echo $file_exists;
				for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
				}
				*/
				
				//$return_array[0] is where the file already exists or to be executed is
				if($file_exists == "True") {
					$existing_file_name = basename($_FILES['fileToUpload']['name'], ".xyz") . ".efp";
					echo "This file already exists <a href=\"../database/efp_files/$existing_file_name\">here!</a>";
										
				} else if ($file_exists == "False"){
					if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
						//Everthing has succeeded, we allow user to calculate now
						
						//Create inp file since it has been uploaded
						$gamess_input = exec("python ../python/create_inp.py " . escapeshellarg($target_file));
						//This is already a basename
						
				        echo "The file ". basename($target_file) . " has been uploaded.<br />";
						echo "Press the link below to submit your file to be processed by GAMESS<br />";
						
						#We pass variables by GET to allow multiuser access
						echo "<a href = \"GAMESS_running.php?gamess_input=$gamess_input\" style=\"font-size: 3em\">
									Calculate EFP!<a><br />";	    	
					 } else {
			        echo "Sorry, this file doesn't exist, but there was a problem uploading your file";
			    	}
				}
				 
			} else {
			    echo "Sorry, your file was not accepted or uploaded.<br />";
			}			
		}
		?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>
