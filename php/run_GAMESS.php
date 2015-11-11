<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
	<?php
		if (isset($_POST['submit'])) {
			//Input parameters to be passed to EFPDB
			$job_already_exists = FALSE;
			$target_dir = "../database/xyz_files/";
			$target_file;
			$chem_formula = $_POST['chem_formula'];
			$input_charge = intval($_POST['charge']);	//Defaults to 0 if nothing set
			$input_description = $_POST['descrip'];
			$non_existing_occurrence = $_POST['non_existing_occurrence'];
			$EFP_terms = $_POST['EFP_terms'];
			$basis_set = '6-31G';
			$basis_set_name = $basis_set;  //for use in the file name
			$basis_args = 'NGAUSS=6 GBASIS=N31';
			//$_POST['basis_set'];
			$return_jobID;
			
			//generate basis set
			if(isset($_POST['custom_basis'])) {
				$basis_set = '';

				if($_POST['basis_set_type'] == 'Dunning') {
					
					if($_POST['Aug'] == 'ACC') {
						$basis_set = 'aug-';
					}
					$basis_set = $basis_set . "cc-pV" . $_POST['D_Zetas'] . "Z";
					$basis_args = 'GBASIS=' . $_POST['Aug'] . $_POST['D_Zetas'];
					$basis_set_name = $basis_set;
				} else if($_POST['basis_set_type'] == 'Pople') {
					//Gauss and Zetas
					$basis_args = " NGAUSS=6 " . "GBASIS=" . $_POST['P_Zetas'] . ' ';
					$basis_set = "6-" . $_POST['P_Zetas'];
					
					//Diffuse
					if($_POST['diffuse'] == "Yes(++)") {
						$basis_set = $basis_set . '++'; 
						$basis_args = $basis_args . 'DIFFSP=.t. DIFFS=.t. ';
					} else if($_POST['diffuse'] == "Yes(+)") {
						$basis_set = $basis_set . '+';
						$basis_args = $basis_args . 'DIFSP=.t. ';
					}
					$basis_set = $basis_set . 'G';
					$basis_set_name = $basis_set;
					
					//Pol. functions
					//TODO: Fix ordering?
					if($_POST['d'] > 0 || $_POST['p'] > 0 || $_POST['f'] > 0) {
						$basis_set = $basis_set . '('; 
						if($_POST['d'] > 0) {
							$basis_set = $basis_set . $_POST['d'] . 'd';
							$basis_set_name = $basis_set_name . $_POST['d'] . 'd'; 
							$basis_args = $basis_args . 'NDFUNC=' . $_POST['d'] . ' ';
							if($_POST['p'] > 0 || $_POST['f'] > 0) {
								$basis_set = $basis_set . ',';
							}
						}
					 	if($_POST['p'] > 0) {
							$basis_set = $basis_set . $_POST['p'] . 'p';
							$basis_set_name = $basis_set_name . $_POST['p'] . 'p'; 
							$basis_args = $basis_args . 'NPFUNC=' . $_POST['p']. ' ';
							if($_POST['f'] > 0) {
								$basis_set = $basis_set . ',';
							}
						}
						if($_POST['f'] > 0) {
							$basis_set = $basis_set . $_POST['f'] . 'f';
							$basis_set_name = $basis_set_name . $_POST['f'] . 'f';
							$basis_args = $basis_args . 'NFFUNC=' . $_POST['f'] . ' ';
						}
						$basis_set = $basis_set . ').';
					}
				}

				echo "Basis set: $basis_set<br>";
				echo "Basis set name: $basis_set_name<br>";
				echo "Basis_args: $basis_args<br>";
			}



			//echo "EFP_terms: $EFP_terms<br>";
			//echo "Non_existing_occurance: $non_existing_occurrence";

			//fix $target_file and description - TODO: maybe
			$file_info = pathinfo($_POST['select_mol']);
			if ($file_info['extension'] == "tmp") {  //we have a new file
				//to disallow files from being names the same thing
				$target_file = $target_dir . basename($_POST['select_mol'], ".tmp") . $non_existing_occurrence . ".xyz";
				//echo $target_file . "<br>";
				//move into main xyz folder
				rename("../database/tmp_files/" . $_POST['select_mol'], $target_file);
			} else { //else it already exists
				$target_file = $target_dir . $_POST['select_mol'];
			}


			//Database queries - secured with environmental variables and against injection
			$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
			if ($conn->connect_error) {
				echo "failed";
				die("Connection failed: " . $conn->connect_error);
			}
			
			//check to see that there isn't already a file with the same name and attributes		
			
			//see if the job is running - no duplicates				
			$mysql_query = $conn->prepare("SELECT Fragment, isRunning, JobID FROM main WHERE Geometry=? AND EFPterms=? AND BasisSet=?");
			$mysql_query->bind_param('sss', basename($target_file), $EFP_terms, $basis_set);
			
			if($mysql_query->execute()) {
				$mysql_query->bind_result($curr_fragment, $is_running, $jobID);
				//if there is a row at all
				if($row = $mysql_query->fetch()) {
					#echo $fragment;
					#echo $is_running;
					
					$job_already_exists = TRUE;
					echo "This exact job already exists!<br>";
					if($is_running) {
						echo "This file is currently running! <br>";
						echo "Click <a href=\"view_job.php?jobID=$jobID\">here</a> to see its progress<br>";
					} else {
						echo "This job has completed!<br>";
						echo "Click <a href=\"view_job.php?jobID=$jobID\">here</a> to see its progress (already completed)<br>";
						echo "Click <a href=\"view_job.php?view_mol=$curr_fragment\">here</a> to see its fragment<br>";
						
					}
				}
			} else {
				echo "Previous records query failed";
			}

	 		if (!$job_already_exists) {
				//create input file!!!!
				//may store job idas jsut he numbe, not the rest
								echo "Hello!";		
				/*
				$gamess_input = exec("python ../python/create_inp.py " . escapeshellarg($target_file) . " "
					. escapeshellarg($input_charge) . " $EFP_terms " . escapeshellarg($basis_args) . " " . 
						escapeshellarg('_' . $basis_set_name));
				
				//And execute GAMESS!!!!!
				$return_jobID = exec("./../scripts/submissionscript $gamess_input", $return_array);
				echo "JobID: $return_jobID <br>";
				*/
				/*				 
				//create new database entry
				$mysql_query = $conn->prepare("INSERT INTO main
						   (Occurance, Description, Molecule, EFPterms, BasisSet, Geometry, InputFile, isRunning, jobID)
						   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
				$is_runnning = '1';
				$mysql_query->bind_param('isssssss', $non_existing_occurance, $input_description, $chem_formula, $EFP_terms, $basis_set, basename($target_file), $gamess_input, $is_runnning, $return_jobID);
				
				if($mysql_query->execute()) {
		        	echo "The file ". basename($target_file) . " has been uploaded.<br />";
	
				} else {
					 echo "There was trouble putting ". basename($target_file) . " into the database, but it has
						been uploaded.<br>";
				}
			}
		
		
		
			
/*					
		
			/*				
					echo "Progress report will be available <a href=\"view_job.php?jobID=$return_jobID\">here</a>";
				}
			
			//$mysql_query->close();
			//$conn->close();
		*/
			}

		} else {
			echo "Sorry, this file doesn't exist.";
		}
		
	?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>


