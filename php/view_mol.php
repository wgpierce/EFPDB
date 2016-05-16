<?php
//dynamically add our header and footer
require ('../includes/head.html');
require ('../includes/header.html');
?>
<div id="site_content">
	<p>
		<?php
		include_once ('../includes/php_functions.php');

		if (isset($_GET['select_mol'])) {
			$select_file = $_GET['select_mol'];
			$file_info = pathinfo($select_file);

			$conn = makeConn();
			$mysql_query;

			if ($file_info['extension'] == "efp") {
				$mysql_query = $conn -> prepare("SELECT Occurrence, EFPterms, BasisSet, Geometry, Geometry_Hash, 
																			 Fragment, Description FROM main WHERE Fragment=?");

				$mysql_query -> bind_param('s', $select_file);

				if ($mysql_query -> execute()) {
					$mysql_query -> bind_result($curr_occurrence, $curr_EFP_terms, $curr_basis_set, $curr_geometry, $curr_geometry_hash, $curr_fragment, $curr_description);
					$row = $mysql_query -> fetch();

					if ($curr_occurrence != 0) {

						//copy over current file to tmp directory with concatentated geometry, efp terms, and basis set
						//for easy reading of user
						$curr_file = "../database/efp_files/$select_file";
						$tmp_file = "../database/tmp_files/" . basename($curr_geometry, ".xyz") . "_" . "$curr_EFP_terms" . "_" . "$curr_basis_set" . ".efp";

						copy($curr_file, $tmp_file);
						echo "Fragment: <a href=\"$tmp_file\">" . basename($tmp_file) . "</a></td>";
					}
				}

			} else if ($file_info['extension'] == "") {

				$curr_geometry_hash = $select_file;
				$mysql_query = $conn -> prepare("SELECT Occurrence, EFPterms, BasisSet, Geometry, 
																				Fragment, Description FROM main WHERE Geometry_Hash=?");
				$mysql_query -> bind_param('s', $select_file);

				if ($mysql_query -> execute()) {
					$mysql_query -> bind_result($curr_occurrence, $curr_EFP_terms, $curr_basis_set, $curr_geometry, $curr_fragment, $curr_description);
					$row = $mysql_query -> fetch();

					if ($curr_occurrence != 0) {

						//copy over current file to tmp directory with concatentated geometry, efp terms, and basis set
						//for easy reading of user
						//XXX: this means that if the same file is entered into the system under different names,
						//only the  first alphabetical name will be used
						$curr_file = "../database/xyz_files/$select_file";
						$tmp_file = "../database/tmp_files/$curr_geometry";

						copy($curr_file, $tmp_file);
						echo "Geometry: <a href=\"$tmp_file\">" . basename($tmp_file) . "</a></td>";
					}
				}

			}

			if ($curr_occurrence != 0) {
				echo '<table style=\"width:100%\">
						<tr>
							<th>Occurrence</th>
							<th>Polarization/Dispersion/Charge Transfer/Ex-Rep</th>
							<th>Basis Set</th>
							<th>Geometry</th>
							<th>Fragment</th>
							<th>Description</th>
						</tr>';

				do {
					echo "
				<tr>
					<td>$curr_occurrence</td>
					<td>$curr_EFP_terms</td>
					<td>$curr_basis_set</td>
					<td><a href = 'view_mol.php?select_mol=$curr_geometry_hash'>$curr_geometry</a></td>
					<td><a href = 'view_mol.php?select_mol=$curr_fragment'>Fragment</a></td>
					<td>$curr_description</td>
				</tr>";
				} while ($row = $mysql_query -> fetch());

				echo "</table>";

				$mysql_query -> close();
				$conn -> close();
			} else {
				echo "Invalid filename<br>";
			}

		} else {
			echo '<form action="view_mol.php" method="GET">
						<label>Select Molecule (.xyz or .efp): 
							<input type="text" name="select_mol">
						</label><br>
						<input type="submit"><br>
					</form>';
		}
		?>
	</p>
</div>
<?php
require ('../includes/footer.html');
?>

