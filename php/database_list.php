<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">

	<?php
		//Database queries - secured with environmental variables and against injection
		$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
		if ($conn->connect_error) {
			echo "Failed connection with database";
			die("Connection failed: " . $conn->connect_error);
		}
		
		$mysql_query = $conn->prepare("SELECT Occurance, Description, Attributes, BasisSet, Geometry, Fragment FROM main");
		
		if($mysql_query->execute()) {
			$mysql_query->bind_result($curr_occurance, $curr_description, $curr_attributes, $curr_basis_set, $curr_geometry, $curr_fragment);
				echo "<table style=\"width:100%\">
					<tr>
						<th>Occurance</th>
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
							<td>$curr_occurance</td>
							<td>$curr_attributes</td>
							<td>$curr_basis_set</td>
							<td><a href = 'view_mol.php?select_mol=$curr_geometry'>$curr_geometry</a></td>
							<td><a href = 'view_mol.php?select_mol=$curr_fragment'>$curr_fragment</a></td>
							<td>$curr_description</td>
						 </tr>";	
					$existing_inp_name = $curr_inp_file;
					$file_exists=TRUE;	
			}
			echo "</table>";
				
		} else {
			//else the query fails, not necessarily file doesn't exist
			echo "Failed query with database";
			$file_exists=FALSE;
		}
		$mysql_query->close();
		$conn->close();

	?>
	
	
	
</div>
		
<?php require('../includes/footer.html'); ?>	

