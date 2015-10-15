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
			$target_dir = "../database/xyz_files/";
			$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
			$uploadOk = 1;	
			$fileinfo = pathinfo($target_file);
			
			//Input parameters to be passed to EFPDB	
			$input_charge = intval($_POST['charge']);	//Defaults to 0 if nothing set
			
			$input_polarization = FALSE;
			$input_dispersion = FALSE;
			$input_exrep = FALSE;
			$input_charge_transfer = FALSE;
			$input_description = $_POST['descrip'];
			
			if (isset($_POST['polarization'])) {
				$input_polarization = TRUE;
			}
			if (isset($_POST['dispersion'])) {
				$input_dispersion = TRUE;
			}
			if (isset($_POST['exrep'])) {
				$input_exrep = TRUE;
			}
			if (isset($_POST['charge_transfer'])) {
				$input_charge_transfer = TRUE;
			}
			
			/* //value checking prints
			echo $input_charge ."<br>";
			echo $input_polarization ."<br>";
			echo $input_dispersion ."<br>";
			echo $input_exrep ."<br>";
			echo $input_charge_transfer ."<br>";
			echo $fileinfo['extension'] . "<br>";
			echo $input_description;
			*/
			
			// Check if file size is less than 20MB
			if ($_FILES['fileToUpload']['size'] > 20000000) {
			    echo "Sorry, your file size cannnot exceed 20MB.<br />";
			    $uploadOk = 0;
			}
	
			// Check if file is an xyz file 
			if($fileinfo['extension'] != "xyz"){
			    echo "The file to upload is: <br \>".basename($_FILES['fileToUpload']['name'])."<br />";
			    echo "Sorry, only .xyz file is allowed.<br />";
			    $uploadOk = 0;
			}
			//Note $_FILES['fileToUpload']['name'] isn't actually a file
			
			//Now ensure that it is a proper file by obtaining its chemical formula
			$tmp_file = $_FILES['fileToUpload']['tmp_name'];
			//echo $tmp_file;
			$return_array = array();
			$chem_formula = exec("python ../python/create_formula.py " . escapeshellarg($tmp_file), $return_array);
			$chem_formula = $return_array[0]; //temp fix
			//echo "Chemical Formula is: " . $chem_formula . "<br>";
			//check to see that we actually have a chemical formula
			if (!$chem_formula) {
				echo "The file <br \>".basename($_FILES['fileToUpload']['name'])."<br />is not a valid 
						.xyz-formatted file<br>";
				echo "<br>Nice try, hackers<br>";
				$uploadOk = 0;
			}
			
			if($uploadOk) {
				//Compare to previous files and temp file since not uploaded yet 
				/*
				//METHOD 1 - python text database.txt manipulation
				//echo file_get_contents($tmp_file);
				$file_exists = exec("python ../python/file_exists.py " . escapeshellarg($tmp_file), $return_array);
				*/
				
				//METHOD 2 - mySQL Database querying - much better
				//TODO: port python code to php
				$file_exists = FALSE;
				$existing_file_name = "";
				
				
				//if $chem_formula is 0, then all database entries will be returned
				
				
				//Database queries - secured with environmenttal variables
				//echo $_ENV['MYSQL_USER'] . "<br>";   //doesn't work..
				/*
				echo getenv('MYSQL_USER') . "<br>";
				echo getenv('MYSQL_HOST') . "<br>";
				echo getenv('MYSQL_PASSWORD') . "<br>";
				*/
				
				$conn = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'))
				/*
				$conn = mysqli_connect(ini_get("mysql.default.host"),
										ini_get("mysql.default.user"),
										ini_get("mysql.default.password"),
										ini_get("mysql.default.database"))*/
					or die("Could not connect" . mysql_error());
				//TODO: implement Pradeep's method for identifying molecules
				$mysql_query = "SELECT Fragment FROM main
								WHERE Molecule='" . $chem_formula . "'";  //. "AND WHERE Parameter1=param";
								/*Other commands
								= "DELETE FROM main
									where description='something unwanted'";
								= "UPDATE main 
								   SET column1=value1, column2=value2
								   where some_column=some_value"   //careful, can accidentallly update entire database
								= "INSERT INTO main
								   (column1, column2, columnn)
								   VALUES (value1, vlaue2, valuen)"//add specific values
								 */
				$result = mysqli_query($conn, $mysql_query);
					//or die(mysqli_error() . "The query was:" . $mol_exists_query);
				//get name of current file (.efp)
				if (mysqli_num_rows($result) > 0) {
					//convert results from query
					$row = mysqli_fetch_assoc($result);
					//echo $row['Fragment'];
					$existing_file_name = $row['Fragment'];
					$file_exists=TRUE;
				} else {
					$file_exists=FALSE;
				}
				
				/*
				echo "File exists: " . $file_exists . "<br>";
				echo "Existing file: " . $existing_file_name . "<br>";
				*/
				/*
				for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
				}
				*/
				
				//$file_exists = TRUE;

				if($file_exists) {
					$existing_file_name = basename($_FILES['fileToUpload']['name'], ".xyz") . ".efp";
					//echo "This file already exists <a href=\"../database/efp_files/$existing_file_name\">here!</a>";
					echo "This file already exists <a href=\"mol_info.php?select_mol=$existing_file_name\">here!</a>";
					
				} else if ($file_exists == FALSE){
					if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
						//TODO: secure database so it doesn't have fll write access, possibly move off-server-directory
						//Everthing has succeeded, we allow user to calculate now
						
						//Create inp file since it has been uploaded, TODO: translate to php
						$gamess_input = exec("python ../python/create_inp.py " . escapeshellarg($target_file));
						//This is already a basename
						
						//create new database entry
						$mysql_query = "INSERT INTO main
								   (Occurance, Description, Molecule, Geometry) 
								   VALUES (1, '" . $input_description . " ', '". $chem_formula . "', '" . basename($target_file) . "')";
			  		//echo $mysql_query;
						$result = mysqli_query($conn, $mysql_query);
						if ($result) {
				        	echo "The file ". basename($target_file) . " has been uploaded.<br />";
							echo "Press the link below to submit your file to be processed by GAMESS<br />";
							//We pass variables by GET to allow multiuser access and bookmarking
							//other paramters passable here as well
							echo "<a href = \"GAMESS_running.php?gamess_input=$gamess_input\" style=\"font-size: 3em\">
									Calculate EFP!<a><br />";
						} else {
						 echo "There was trouble putting ". basename($target_file) . " into the database, but it has
								been uploaded.<br>";
						}
						
							    	
					} else {
			        	echo "Sorry, this file doesn't exist, but there was a problem uploading your file";
			    	}
				}
				 mysql_close($conn);
			} else {
			    echo "Sorry, your file was not accepted or uploaded.<br />";
			}
		}
		?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>
