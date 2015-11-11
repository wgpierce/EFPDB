<!-- The purpose of this file is to show all uploaded files and allow user to calculate -->

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
            $tmp_file = $_FILES['fileToUpload']['tmp_name'];
			$upload_name = $_FILES['fileToUpload']['name'];
			$fileinfo = pathinfo($_FILES['fileToUpload']['name']); 
			$target_dir = "../database/tmp_files/";
			$target_file = $target_dir . basename($_FILES['fileToUpload']['name'], ".xyz") . ".tmp"; 
			//TODO later: if two people upload the same names file, this will break
			$uploadOk = TRUE;
			
			// Check if file size is less than 20MB
			if ($_FILES['fileToUpload']['size'] > 20000000) {
			    echo "Sorry, your file size cannnot exceed 20MB.<br />";
			    $uploadOk = FALSE;
			}
			
			// Check if file name is < 255 characters
			if (mb_strlen($upload_name) > 225) {
				echo "This file name is too long<br>";
				$uploadOk = FALSE;
			}
			
			if (!preg_match("`^[-0-9A-Z_\.]+$`i", $upload_name)) {
				echo "This file name has illegal characters or is empty<br>";
				$uploadOK = FALSE;
			}
			
			// Check if file is an xyz file 
			if($fileinfo['extension'] != "xyz"){
			    echo "The file to upload is: <br \>".basename($_FILES['fileToUpload']['name'])."<br />";
			    echo "Sorry, only .xyz files are allowed.<br />";
			    $uploadOk = FALSE;
			}
			
			//pur other safety checks like from php move-uploaded-file page
			
			//Now ensure that it is a proper .xyz file by obtaining its chemical formula
			$return_array;
			$chem_formula = exec("python ../python/create_formula.py " . escapeshellarg($tmp_file), $return_array);
			//echo $tmp_file;
			//$chem_formula = $return_array[0]; //temp fix
			
			//echo "Chemical Formula is: " . $chem_formula . "<br>";
			//TODO: Make sure formula actually makes sense - make regexp to check form
			
			//check to see that we actually have a chemical formula / is nonzero
			//if $chem_formula is 0, then all database entries will be returned
			if (!$chem_formula) {
				echo "The file <br \>".basename($_FILES['fileToUpload']['name'])." is not a valid 
						.xyz-formatted file<br>";
				echo "<br>Nice try, hackers<br>";
				$uploadOk = 0;
			}
			
			if($uploadOk) {
				if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
					echo "File Uploaded to tmp<br>";
				}
				
				//Compare to previous files
				echo "The molecule is $chem_formula.<br>";
				echo "<p style='font-size: 2em'>Select the file you wish to use and options for it:</p>";
				echo '<form action="run_GAMESS.php" method="POST" enctype="multipart/form-data">';
							
				echo '<input type="radio" name="select_mol" value="' . basename($target_file) . '" checked>Current file you have uploaded<br>
				<label>Write a description about this file:<br>
				<textarea name="descrip" rows="2" cols="40" maxlength="250" placeholder="Type your description here!"></textarea></label> <br />';
				echo "<p style='font-size:2em; color:magenta'><string>OR<string><p>";
				
				//METHOD 2 - mySQL Database querying - much better
				$non_existing_occurrence=0;
				
				//Database queries - secured with environmental variables and against injection
				$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
				if ($conn->connect_error) {
					echo "Failed connection with database";
					die("Connection failed: " . $conn->connect_error);
				}
				
				
				
				
				//Identify molecules with the same chemical structure and run the rmsd python script on them
				$mysql_query = $conn->prepare("SELECT Occurance, Description, EFPterms, BasisSet, Geometry, Fragment FROM main WHERE Molecule=?");
				$mysql_query->bind_param('s', $chem_formula);
				
				
				if($mysql_query->execute()) {
					$mysql_query->bind_result($curr_occurance, $curr_description, $curr_EFP_terms, $curr_basis_set, $curr_geometry, $curr_fragment);
					echo "Existing files with RMSD < .5:<br>";
						echo "<table style=\"width:100%\">
							<tr>
								<th>Use this?</th>
								<th>RMSD Similarity</th>
								<th>Occurance</th>
								<th>Polarization/Dispersion/Charge Transfer/Ex-Rep</th>
								<th>Basis Set</th>
								<th>Geometry</th>
								<th>Fragment</th>
								<th>Description</th>
							</tr>";
					//convert results from query
					while($row = $mysql_query->fetch()) {
						$non_existing_occurrence = $curr_occurance;
						$rmsd_similarity = exec("python ../python/rmsd.py " . escapeshellarg($target_file) . " ../database/xyz_files/" . $curr_geometry, $return_array);
						//echo "RMSD: $rmsd_similarity<br>";
						//echo "Curr_geometry: $curr_geometry<br>";
						//$rmsd_similarity = "5e-5";
						if($rmsd_similarity < .5) { ///this does properly interpret e notation output
							echo "
								<tr>
									<td><input type='radio' name='select_mol' value='$curr_geometry'></td>
									<td>$rmsd_similarity</td>
									<td>$curr_occurance</td>
									<td>$curr_EFP_terms</td>
									<td>$curr_basis_set</td>
									<td><a href = 'view_mol.php?select_mol=$curr_geometry'>$curr_geometry</a></td>
									<td><a href = 'view_mol.php?select_mol=$curr_fragment'>$curr_fragment</a></td>
									<td>$curr_description</td>
								 </tr>";	
						}
					}
					echo "</table>";
					if($non_existing_occurrence == 0) {
						echo "There are no already existing molecules of this file.<br>";
					}
					$non_existing_occurrence += 1;
						
				} else {
					//else the query fails, not necessarily file doesn't exist
					echo "Failed query with database";
					$file_exists=FALSE;
				}
				$mysql_query->close();
				$conn->close();
				
				
				/*
				for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
				}
				*/
				
				/*	*/					
				
				//crete an invisible filed to store the non_existing_occurrance
				echo "<input type='hidden' name='non_existing_occurrence' value='$non_existing_occurrence'>";
				echo "<input type='hidden' name='chem_formula' value='$chem_formula'>";
				
				echo '
				<br><br>
				<p>Charges: (between -20 and 20): </p>
				    <p><input type="number" name="charge" min="-20" max="20" value ="charge"></p>
				    <br />
				    <p>Click on the buttons corresponding to the calculations you want:</p>
					<label><input type="radio" name="EFP_terms" value="EP" checked>Electrostatics and Polarization</label><br>
					<label><input type="radio" name="EFP_terms" value="EPD">Electrostatics and Polarization, and Dispersion</label><br>
					<label><input type="radio" name="EFP_terms" value="EPDCE">All five EFP Terms</label><br><br>
			
					<!--Advanced Options-->
			
					<label><input type="checkbox" id="custom_basis" name="custom_basis" value="yes">Use Custom Basis Set</label><br />
					
					<fieldset id="custom_basis_options">
						<label><input type="radio" id="Dunning" name="basis_set_type" value="Dunning" checked>Dunning</label><br>
						<fieldset id="Dunning_fields">
							
							<label>Aug
							<select name="Aug">
								<option value="ACC" checked>Yes</option>
								<option value="CC">No</option>
							</select></label>
							<br>
							<label>Zetas
							<select name="D_Zetas">
								<option value="D" checked>cc-pVDZ</option>
								<option value="T">cc-pVTZ</option>
								<option value="Q">cc-pVQZ</option>
								<option value="5-pVDZ">cc-pV5Z</option>
							</select></label>
						</fieldset>
						<label><input type="radio" id="Pople" name="basis_set_type" value="Pople">Pople</label><br>
						<fieldset id="Pople_fields">
							<label>Zetas
							<select name="P_Zetas">
								<option value="N31" checked>Double</option>
								<option value="N311">Triple</option>
							</select></label>
							<br>
							<label>Diffuse
							<select name="diffuse">
								<option value="Yes(++)" checked>Yes(++)</option>
								<option value="Yes(+)">Yes(+)</option>
								<option value="No">No</option>
							</select></label>
							<br>
							Pol. Functions:<br>
							<label>d
							<select name="d">
								<option value="0" checked>0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
							</select></label>
							<label>p
							<select name="p">
								<option value="0" checked>0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
							</select></label>
							<label>f
							<select name="f">
								<option value="0" checked>0</option>
								<option value="1">1</option>
								<option value="2">2</option>
							</select></label>							
						</fieldset>
					</fieldset>
					<!--End Advanced Options-->
			
					<br>
					<br>
					<br>
				    <input type="submit" value="Calculate EFP!" name="submit">
			
			    </form>
				<br>';
				
				//TODO: Make widgit to show current basis set
				
				
			} else {
			    echo "Sorry, the file $upload_name was not accepted or uploaded.<br />";
			}
		}
		?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>
