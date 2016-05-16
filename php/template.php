<?php
	//dynamically add our header and footer
	require("../includes/head.html");
	require("../includes/header.html"); 
?>
	 <div id="site_content">
<!-- body elements-->

	</div>

  
<?php require("../includes/footer.html"); 
basename($_FILES['fileToUpload']['name'], '.xyz') . '.tmp';
			/*
			 for ($i = 0; $i < count($return_array); $i++) {
			 echo $return_array[$i] . "<br />";
			 }
			 */
			 
		if ($file_info['extension'] == "tmp") {
				//we have a new file
				//to disallow files from being names the same thing
				$target_file = $target_dir . basename($_POST['select_mol'], ".tmp") . $non_existing_occurrence . ".xyz";
				rename("../database/tmp_files/" . $_POST['select_mol'], $target_file);
			} else {
				$target_file = $target_dir . $_POST['select_mol'];
			}
			 
			 
			 
							
						//Version 2 - Vertical
							echo "<tr>";
								echo "<td>Occurrence</td>";				
								echo "";
							echo "</tr>";
							echo "</tr>";	
								echo "<td>EFP Terms</td>";
								echo "<td>$curr_EFP_terms</td>";
							echo "<tr>";
							echo "</tr>";
								echo "<td>Basis Set</td>";
								echo "<td>$curr_basis_set</td>";
							echo "<tr>";
							echo "</tr>";
								echo "<td>Fragment</td>";
								echo "<td><a href=\"../database/efp_files/$curr_fragment\">$curr_fragment</a></td>";
							echo "<tr>";
							echo "<tr>";
								echo "<td>Description</td>";
								echo "<td>".$row['Description']."</td>";
							echo "<tr>";
			/*
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

?>
