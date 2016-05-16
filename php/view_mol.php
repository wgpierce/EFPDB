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
			$select_mol = $_GET['select_mol'];
			$curr_geometry = $select_mol;
			$file_info = pathinfo($select_mol);

			$conn = makeConn();
			$mysql_query;

			echo '<table style=\"width:100%\">
						<tr>
							<th>Occurrence</th>
							<th>Polarization/Dispersion/Charge Transfer/Ex-Rep</th>
							<th>Basis Set</th>
							<th>Geometry</th>
							<th>Fragment</th>
							<th>Description</th>
						</tr>';

			if ($file_info['extension'] == "efp") {
				echo "Fragment: <a href=\"../database/efp_files/$select_mol\">$select_mol</a></td>";
				$mysql_query = $conn -> prepare("SELECT Occurrence, EFPterms, BasisSet, Geometry, Geometry_Hash, 
																			 Fragment, Description FROM main WHERE Fragment=?");
				$mysql_query -> bind_param('s', $select_mol);

				if ($mysql_query -> execute()) {
					$mysql_query -> bind_result($curr_occurrence, $curr_EFP_terms, $curr_basis_set, $curr_geometry, 
																			$curr_geometry_hash, $curr_fragment, $curr_description);
				}
			} else if ($file_info['extension'] == "") {
				$curr_geometry_hash = $curr_geometry;
				echo "Geometry: <a href='../database/xyz_files/$select_mol'>$select_mol</a></td>";
				$mysql_query = $conn -> prepare("SELECT Occurrence, EFPterms, BasisSet, Geometry, 
																				Fragment, Description FROM main WHERE Geometry_Hash=?");
				$mysql_query -> bind_param('s', $select_mol);

				if ($mysql_query -> execute()) {

					$mysql_query -> bind_result($curr_occurrence, $curr_EFP_terms, $curr_basis_set, $curr_geometry, 
																			$curr_fragment, $curr_description);

				}
			}

			while ($row = $mysql_query -> fetch()) {
				echo "
				<tr>
					<td>$curr_occurrence</td>
					<td>$curr_EFP_terms</td>
					<td>$curr_basis_set</td>
					<td><a href = 'view_mol.php?select_mol=$curr_geometry_hash'>$curr_geometry</a></td>
					<td><a href = 'view_mol.php?select_mol=$curr_fragment'>Fragment</a></td>
					<td>$curr_description</td>
				</tr>";
			}

			echo "</table>";

			
			$mysql_query -> close();
			$conn -> close();

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

