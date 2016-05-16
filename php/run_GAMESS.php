<?php
//dynamically add our header and footer
require ('../includes/head.html');
require ('../includes/header.html');
?>
<div id="site_content">
	<p>
		<?php
		include_once ('../includes/php_functions.php');
		include_once ('../includes/set_basis.php');
		
		if (isset($_POST['submit'])) {

			$chem_formula = $_POST['chem_formula'];
			$input_charge = intval($_POST['charge']);	//Defaults to 0 if nothing set
			$geometry_name = $_POST['geometry_name'];
			$input_description = $_POST['descrip'];
			$non_existing_occurrence = $_POST['non_existing_occurrence'];
			$EFP_terms = $_POST['EFP_terms'];
			
			$file_info = pathinfo($_POST['select_mol']);
			$target_file = "../database/xyz_files/" . $_POST['select_mol'];
			$tmp_file = '../database/tmp_files/' . $_POST['select_mol'];
			if (file_exists($tmp_file)) rename($tmp_file, $target_file);
			
			$job_already_exists = FALSE;
			$basis_set = '6-31G';
			$basis_set_name = $basis_set;
			$basis_args = 'NGAUSS=6 GBASIS=N31';
			
			$return_jobID;
			$is_runnning = 'T';
			$fragment_name;

			if (isset($_POST['custom_basis'])) {
				//generate basis set
				set_basis($basis_set, $basis_args, $basis_set_name);

				echo "Basis set: $basis_set<br>";
				echo "Basis set name: $basis_set_name<br>";
				echo "Basis_args: $basis_args<br>";
			}

			$conn = makeConn();

			//check to see that there isn't already a file with the same name and attributes
			$mysql_query = $conn -> prepare("SELECT Fragment, isRunning, JobID FROM main WHERE Geometry_Hash=? 
																			 AND EFPterms=? AND BasisSet=?");
			$mysql_query -> bind_param('sss', basename($target_file), $EFP_terms, $basis_set);

			if ($mysql_query -> execute()) {
				$mysql_query -> bind_result($curr_fragment, $is_running, $jobID);
				if ($row = $mysql_query -> fetch()) {
					
					$job_already_exists = TRUE;
					echo "This exact job already exists!<br>";
					
					if ($is_running) {
						echo "This file is currently running! <br>";
						echo "Click <a href=\"view_job.php?jobID=$jobID\">here</a> to see its progress<br>";
					} else {
						echo "This job has completed!<br>";
						echo "Click <a href=\"view_job.php?jobID=$jobID\">here</a> to see its progress (already completed)<br>";
						echo "Click <a href=\"view_job.php?view_mol=$curr_fragment\">here</a> to see its fragment<br>";
					}
					
				}
			} else echo "Previous records query failed";
			
			$mysql_query -> close();

			if (!$job_already_exists) {
				//create input
				$gamess_input = exec("python ../python/create_inp.py " . escapeshellarg($target_file) . " " . escapeshellarg($input_charge) .
														 " $EFP_terms " . escapeshellarg($basis_args) . " " . escapeshellarg('_' . $basis_set_name));
				
				
				$fragment_name = basename($gamess_input, ".inp") . ".efp";

				//And execute qsub to execute GAMESS!
				$return_jobID = exec("../scripts/submissionscript $gamess_input");
				$return_jobID = preg_replace("/.dhcpa211.chem.purdue.edu/", "", $return_jobID);

				echo "JobID: $return_jobID <br>";

				//create new database entry
				$mysql_query = $conn -> prepare("INSERT INTO main
						   (Occurrence, Description, Molecule, EFPterms, BasisSet, Geometry, Geometry_Hash, Fragment,
						    InputFile, isRunning, jobID)
						    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$mysql_query -> bind_param('issssssssss', $non_existing_occurrence, $input_description, $chem_formula, $EFP_terms,
												 $basis_set, $geometry_name, basename($target_file), $fragment_name, $gamess_input, $is_runnning, $return_jobID);

				if ($mysql_query -> execute()) {
					echo "The job has been successfully added to the database.<br>";
					echo "A progress report will be available <a href=\"view_job.php?jobID=$return_jobID\">here</a>.<br>";
				} else {
					echo "There was a problem uploading the file to the database.";
				}
			}

			$mysql_query -> close();
			$conn -> close();

		} else {
			echo "Sorry, this file doesn't exist.";
		}
		?>
	</p>
</div>

<?php
	require ('../includes/footer.html');
 ?>

