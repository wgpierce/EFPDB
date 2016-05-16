<!-- The purpose of this file is to show all uploaded files and allow user to calculate -->

<?php
require ('../includes/head.html');
require ('../includes/header.html');
?>

<div id='site_content'>
	<p>
		Click <a href='upload.php'>here</a> to return to the previous page to try again or submit another molecule
	</p>
	<br>
	<p>
	<?php
	include_once ('../includes/php_functions.php');
	include_once ('../includes/check_input.php');

	//TODO: put in field for email to be sent when job is complete?

	if (isset($_POST['submit'])) {

		$tmp_file = $_FILES['fileToUpload']['tmp_name'];
		$upload_name = $_FILES['fileToUpload']['name'];
		$fileinfo = pathinfo($_FILES['fileToUpload']['name']);
		$target_dir = '../database/tmp_files/';
		$hash_name = md5_file($tmp_file);
		$target_file = $target_dir . $hash_name;
		
		$non_existing_occurrence = 0;

		$uploadOk = check_input($chem_formula);

		if ($uploadOk) {
			if (move_uploaded_file($tmp_file, $target_file)) {
				echo "File Uploaded<br>";
			}

			echo "
				<p style='font-size: 2em'>
					Select the file you wish to use and options for it:
				</p>";
			echo "
				<form action='run_GAMESS.php' method='POST' enctype='multipart/form-data'>
			";

			echo "
				<input type='radio' name='select_mol' value='" . basename($target_file) . "' checked>
					Current file you have uploaded
				<br>
				<div>
					<label>Write a description about this file: <br>
						<textarea name='descrip' rows='2' cols='80' maxlength='250' placeholder='Type your description here!'></textarea>
					</label>
				</div>
			";
			echo "
				<p style='font-size:2em; color:green'>
					<string>
						OR
					<string>
				</p>
				";

			$conn = makeConn();

			//Identify molecules with the same chemical structure and run the rmsd python script on them
			$mysql_query = $conn -> prepare(
						"SELECT Occurrence, EFPterms, BasisSet, Geometry, Geometry_Hash, Fragment, Description FROM main WHERE Molecule=?");
			$mysql_query -> bind_param('s', $chem_formula);

			if ($mysql_query -> execute()) {
				$mysql_query -> bind_result(
					$curr_occurrence, $curr_EFP_terms, $curr_basis_set, $curr_geometry, $curr_geometry_hash, $curr_fragment, $curr_description);
				
				echo "Existing files with RMSD < .5: <br>";
				echo '<table style=\"width:100%\">
								<tr>
									<th>Use this?</th>
									<th>RMSD Similarity</th>
									<th>Occurrence</th>
									<th>Polarization/Dispersion/Charge Transfer/Ex-Rep</th>
									<th>Basis Set</th>
									<th>Geometry</th>
									<th>Fragment</th>
									<th>Description</th>
								</tr>';
								
				while ($row = $mysql_query->fetch()) {
					$non_existing_occurrence = $curr_occurrence;
					
					$rmsd_similarity = exec('python ../python/rmsd.py ' . escapeshellarg($target_file) . 
																	' ../database/xyz_files/' . $curr_geometry_hash);
					//TODO make sure to check that rmsd_similarity return valid number
					// i.e. make sure that the two mulecules have the same general structure, and not just chem_formula
					
					if ($rmsd_similarity < .5) { //this does properly interpret e notation output
						echo "
						<tr>
							<td><input type='radio' name='select_mol' value='$curr_geometry_hash'></td>
							<td>$rmsd_similarity</td>
							<td>$curr_occurrence</td>
							<td>$curr_EFP_terms</td>
							<td>$curr_basis_set</td>
							<td><a href = 'view_mol.php?select_mol=$curr_geometry_hash'>$curr_geometry</a></td>
							<td><a href = 'view_mol.php?select_mol=$curr_fragment'>Fragment</a></td>
							<td>$curr_description</td>
						</tr>";
					}
				}
				echo "</table>";

				if ($non_existing_occurrence == 0) {
					echo "There are no already existing molecules of this file in the database.<br>";
				}
				$non_existing_occurrence += 1;

			} else {
				//else the query fails, not necessarily file doesn't exist
				echo "Failed query with database";
				$file_exists = FALSE;
			}
			
			$mysql_query -> close();
			$conn -> close();

			echo "<input type='hidden' name='non_existing_occurrence' value='$non_existing_occurrence'>";
			echo "<input type='hidden' name='chem_formula' value='$chem_formula'>";
			echo "<input type='hidden' name='geometry_name' value='$upload_name'>";
		
			include ('../includes/basis_set_options.html');

			//TODO: Make widgit (javascript) to show current basis set

			
		} else {
			echo "Sorry, the file $upload_name was not accepted or uploaded.<br />";
		}
	}
?>

</p>
</div>
<?php
	require ('../includes/footer.html');
 ?>
