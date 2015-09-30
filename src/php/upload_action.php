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
				
				//Compare to previous files
				echo basename($_FILES['fileToUpload']['tmp_name']) . "<br />";
				$tmp_file = $_FILES['fileToUpload']['tmp_name'];
				$file_exists = exec("python ../python/searchstring.py " . escapeshellarg($tmp_file), $return_array);
				
				//$return_array[0] is where the file already exists or to be executed is
				
				if($file_exists) {
					//link to webpage
					/*
					echo "<form action=\"mol_page.php\" method=\"POST\" enctype=\"multipart/form-data\">
								$file_name = $return_array[0]
								<p>This file already exists at <p> <a href=\" . $return_array[0] . ">this location</a>."
								type=submit, name=submit;
					
								";
					*/
					
				} else {
					if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
				        echo "The file ". basename($_FILES['fileToUpload']['name']). " has been uploaded.<br />";
				        echo "Now running GAMESS on this file...<br />";
						
						
						//execute GAMESS
						//exec()
				        $uploaded_file = $target_file;
						
						echo("GAMESS " . $return_array[0]);
			
								//create dynamic webpage and display results
					
					
						// script to .inp file
						// not in DB - compute similarity in python
						// run qsub job on this file - in the backend - export to supercomputer later
						
						//create dynamic webpage for displaying current job
						
						//create download file and output 
											
						//echo "You can view your job\'s progress at <a href=\"$resultpage\">this page<\a>";
						
				        //echo "Your results will be available at <a href=\"$download_page\">this page<\a>";
				    	
				    	
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
