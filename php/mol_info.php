<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
		<?php
			$select_mol = $_GET['select_mol'];
			$fileinfo = pathinfo($target_file);
			
			//just convert the name to inp...
			if($fileinfo['extension'] == "xyz") {
				$select_mol = basename($_FILES['fileToUpload']['name'], ".xyz") . "inp";	
			} else if ($fileinfo['extension'] == "efp") {
				$select_mol = basename($_FILES['fileToUpload']['name'], ".xyz") . "inp";
			}
			//create fancy stuff based on that molecule

			//dump database file based on it
			$conn = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
									getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'))
				or die("Could not connect" . mysql_error());
			
			$mysql_query = "SELECT * FROM main
							WHERE Fragment='" . $select_mol . "'";
							
			$result = mysqli_query($conn, $mysql_query);
				//or die(mysqli_error() . "The query was:" . $mol_exists_query);
			//get name of current file (.efp)
			if (mysqli_num_rows($result) > 0) {
				//convert results from query
				$row = mysqli_fetch_assoc($result);
				//could put /n in to make the htlm look nice
				echo "<table>";
				//Version 2 - Vertical
					echo "<tr>";
						echo "<td>Occurence</td>";				
						echo "<td>".$row['Occurence']."</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>Description</td>";
						echo "<td>".$row['Description']."</td>";
					echo "<tr>";
					echo "</tr>";	
						echo "<td>Molecule</td>";
						echo "<td>".$row['Molecule']."</td>";
					echo "<tr>";
					echo "</tr>";
						echo "<td>Geometry</td>";
						echo "<td><a href=\"../database/xyz_files/".$row['Geometry']."\">".$row['Geometry']."</a></td>";
					echo "<tr>";
					echo "</tr>";
						echo "<td>Fragment</td>";
						echo "<td><a href=\"../database/efp_files/$select_mol\">".$row['Fragment']."</a></td>";
					echo "<tr>";
					echo "</tr>";
						echo "<td>Parameter1</td>";
						echo "<td>".$row['Parameter1']."</td>";
					echo "</tr>";
				
				/*Version 1 - Horizontal
				 * 				
					echo "<tr>";
						echo "<td>Occurence</td>";				
						echo "<td>Description</td>";
						echo "<td>Molecule</td>";
						echo "<td>Geometry</td>";
						echo "<td>Fragment</td>";
						echo "<td>Parameter1</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>".$row['Occurence']."</td>";				
						echo "<td>".$row['Description']."</td>";
						echo "<td>".$row['Molecule']."</td>";
						echo "<td>".$row['Geometry']."</td>";
						echo "<td>".$row['Fragment']."</td>";
						echo "<td>".$row['Parameter1']."</td>";
					echo "</tr>";
				echo "</table>";
				 */
				 
				 echo "</table>";
				
			} else {
				echo "There is no database entry for $select_mol";
			}
		
		
		
		?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>
		
