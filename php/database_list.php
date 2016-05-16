<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>

	<?php
		include_once('../includes/php_functions.php');
		//Database queries - secured with environmental variables and against injection
		
		$conn = makeConn();
		
		$mysql_query = $conn->prepare("SELECT Occurrence, Description, Molecule, EFPterms, BasisSet, Geometry, Fragment FROM main");
		
		if($mysql_query->execute()) {
			$mysql_query->bind_result($curr_occurrence, $curr_description, $curr_mol, $curr_efp_terms, $curr_basis_set, $curr_geometry, $curr_fragment);
				echo "<table style='width:100%\'>
					<tr>
						<th>Occurrence</th>
						<th>Molecule</th>
						<th>Polarization/Dispersion/Charge Transfer/Ex-Rep</th>
						<th>Basis Set</th>
						<th>Geometry</th>
						<th>Fragment</th>
						<th>Description</th>
					</tr>";
			//convert results from query
			while($row = $mysql_query->fetch()) {
					echo "
						<tr>
							<td>$curr_occurrence</td>
							<td>$curr_mol</td>
							<td>$curr_efp_terms</td>
							<td>$curr_basis_set</td>
							<td><a href = 'view_mol.php?select_mol=$curr_geometry'>$curr_geometry</a></td>
							<td><a href = 'view_mol.php?select_mol=$curr_fragment'>$curr_fragment</a></td>
							<td>$curr_description</td>
						 </tr>";
			}
			echo "</table>";
				
		} else {
			//else the query fails, not necessarily file doesn't exist
			echo "Failed query with database";
		}
		$mysql_query->close();
		$conn->close();

	?>
		
<?php require('../includes/footer.html'); ?>	

