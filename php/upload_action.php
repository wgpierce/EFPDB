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
            //Upload file info and authentication
			$target_dir = "../database/xyz_files/";
			$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
			$uploadOk = 1;	
			$fileinfo = pathinfo($target_file);
			
			//Input parameters to be passed to EFPDB	
			$input_charge = intval($_POST['charge']);	//Defaults to 0 if nothing set
			
			$input_polarization = 0;
			$input_dispersion = 0;
			$input_exrep = 0;
			$input_charge_transfer = 0;
			$input_description = $_POST['descrip'];
			
			if (isset($_POST['polarization'])) {
				$input_polarization = 1;
			}
			if (isset($_POST['dispersion'])) {
				$input_dispersion = 1;
			}
			if (isset($_POST['exrep'])) {
				$input_exrep = 1;
			}
			if (isset($_POST['charge_transfer'])) {
				$input_charge_transfer = 1;
			}
			
			/*
			 //value checking prints
			echo "input charge: $input_charge <br>";
			echo "input_polarization $input_polarization <br>";
			echo "input dispersion: $input_dispersion <br>";
			echo "input_dipsersion: $input_exrep <br>";
			echo "input_charge_transfer: $input_charge_transfer <br>";
			echo "extension: " . $fileinfo['extension'] . "<br>";
			echo "input_description: $input_description<br>";
			/**/
			
			// Check if file size is less than 20MB
			if ($_FILES['fileToUpload']['size'] > 20000000) {
			    echo "Sorry, your file size cannnot exceed 20MB.<br />";
			    $uploadOk = 0;
			}
	
			// Check if file is an xyz file 
			if($fileinfo['extension'] != "xyz"){
			    echo "The file to upload is: <br \>".basename($_FILES['fileToUpload']['name'])."<br />";
			    echo "Sorry, only .xyz file is allowed.<br />";
			    $uploadOk = 0;
			}
			//Note $_FILES['fileToUpload']['name'] isn't actually a file
			
			//Now ensure that it is a proper .xyz file by obtaining its chemical formula
			$tmp_file = $_FILES['fileToUpload']['tmp_name'];
			//echo $tmp_file;
			$return_array;
			$chem_formula = exec("python ../python/create_formula.py " . escapeshellarg($tmp_file), $return_array);
			//$chem_formula = $return_array[0]; //temp fix
			//echo "Chemical Formula is: " . $chem_formula . "<br>";
			//check to see that we actually have a chemical formula / is nonzero
			//if $chem_formula is 0, then all database entries will be returned - prevented above
			if (!$chem_formula) {
				echo "The file <br \>".basename($_FILES['fileToUpload']['name'])." is not a valid 
						.xyz-formatted file<br>";
				echo "<br>Nice try, hackers<br>";
				$uploadOk = 0;
			}
			
			if($uploadOk) {
				//Compare to previous files and temp file since not uploaded yet 
				/*
				//METHOD 1 - python text database.txt manipulation
				//echo file_get_contents($tmp_file);
				$file_exists = exec("python ../python/file_exists.py " . escapeshellarg($tmp_file), $return_array);
				*/
				
				//METHOD 2 - mySQL Database querying - much better
				//TODO: port python code to php
				$file_exists = FALSE;
				$existing_file_name = "";
				$non_existing_occurance=0;
				
				//Database queries - secured with environmental variables and against injection
				$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
				if ($conn->connect_error) {
					echo "Failed connection with database";
					die("Connection failed: " . $conn->connect_error);
				}
				
				//Identify molecules with the same chemical structure and run the rms python script on them
				$mysql_query = $conn->prepare("SELECT Occurance,Geometry,InputFile FROM main WHERE Molecule=?");
				$mysql_query->bind_param('s', $chem_formula);
				
				if($mysql_query->execute()) {
					$mysql_query->bind_result($curr_occurance, $curr_geometry, $curr_inp_file);
					//convert results from query
					while($row = $mysql_query->fetch()) {
						//echo $curr_fragment;
						//echo $curr_geometry;
						$non_existing_occurance = $curr_occurance; //keep this in case we find it doesn't exist at the end
						$rmsd_similarity = exec("python ../python/rmsd.py " . escapeshellarg($tmp_file) . " ../database/xyz_files/" . $curr_geometry, $return_array);
						//$rmsd_similarity = "5e-5";
						if($rmsd_similarity < .5) { ///this does properly interpret e notation output
							echo "The RMSD similarity of ".basename($_FILES['fileToUpload']['name'])." is $rmsd_similarity to a similar geometry, given below<br>";
							$existing_inp_name = $curr_inp_file;
							$file_exists=TRUE;		
							break;
						}
						$file_exists=FALSE;	
					}
					$non_existing_occurance += 1;
					
				} else {
					//else the query fails, not necessarily file doesn't exist
					echo "Failed query with database";
					$file_exists=FALSE;
				}
				$mysql_query->close();
				
				/*
				echo "File exists: " . $file_exists . "<br>";
				echo "Existing file: " . $existing_file_name . "<br>";
				*/
				/*
				for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
				}
				*/
				
				//$file_exists = TRUE;

				if($file_exists) {
					#$existing_file_name = basename($_FILES['fileToUpload']['name'], ".xyz") . ".efp";
					//echo "This file already exists <a href=\"../database/efp_files/$existing_file_name\">here!</a>";
					echo "This file already exists <a href=\"mol_info.php?select_mol=$existing_inp_name\">here!</a>";
					
				} else if ($file_exists == FALSE){
					//to disallow files from being names the same thing
					$target_file = $target_dir . basename($_FILES['fileToUpload']['name'], ".xyz") . $non_existing_occurance . ".xyz";
					if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
						//Everthing has succeeded and the file does not exist, we allow user to calculate now
						
						//Create inp file since it has been uploaded, TODO: translate to php
						//return is a basename, pass other args here to input	
						$gamess_input = exec("python ../python/create_inp.py " . escapeshellarg($target_file) . " "
												. escapeshellarg($input_charge) . " ". escapeshellarg($input_polarization) . " "
												. escapeshellarg($input_dispersion) . " ". escapeshellarg($input_exrep) . " "
												. escapeshellarg($input_charge_transfer), $return_array);

/**/						
						//create new database entry
						$mysql_query = $conn->prepare("INSERT INTO main
								   (Occurance, Description, Molecule, Geometry, InputFile, isRunning)
								   VALUES (?, ?, ?, ?, ?, ?)");
						$is_runnning = '0';
						$mysql_query->bind_param('isssss', $non_existing_occurance, $input_description, $chem_formula, basename($target_file), $gamess_input, $is_runnning);
						
						if($mysql_query->execute()) {
				        	echo "The file ". basename($target_file) . " has been uploaded.<br />";
							echo "Press the link below to submit your file to be processed by GAMESS<br />";
							//We pass variables by GET to allow multiuser access and bookmarking of the job
							//other paramters passable here as well
							echo "<a href = \"run_GAMESS.php?gamess_input=$gamess_input\" style=\"font-size: 3em\">
									Calculate EFP!<a><br />";
						} else {
						 echo "There was trouble putting ". basename($target_file) . " into the database, but it has
								been uploaded.<br>";
						}
/*	*/					
							    	
					} else {
			        	echo "Sorry, this file doesn't exist, but there was a problem uploading your file";
			    	}
				}
				 $conn->close();
			} else {
			    echo "Sorry, your file was not accepted or uploaded.<br />";
			}
		}
		?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>
