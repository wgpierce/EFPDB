<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
	<?php
		echo "Now running GAMESS on this file...<br />";
		if(file_exists("../database/inp_files/" . $_GET['gamess_input'])) {
			//var/www/html/EFPDB/database/inp_files/"
			$gamess_input = $_GET['gamess_input'];
			
				
			//If there is currently a process running	
			//run command line arguments with exec
			exec();
			
			if (FALSE) {
				$calculated_file = 7;
				echo "This process has already been completed! <br >";
				echo "View the results for this file <a href= \"../database/efp_files/$calculated_file>here</a><br />";
			} else {
				
				//create the process - this is it
				//$command = "./../script/submissionscript $gamess_input";
				exec($command);
				
				
				
				//Database queries
				$mysql_query = "UPDATE main
								SET Fragment=''
								where ";
				$conn = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'))
					or die("Could not connect" . mysql_error());
				$file_added = mysqli_query($conn, $mysql_query);
					//or die(mysqli_error() . "The query was:" . $mol_exists_query);
				
				 
				$mysql_query = "";
				$file_name = mysqli_query($conn, $mysql_query);
				mysql_close($conn);
				
			}
			
		} else {
			echo "Sorry, this file doesn't exist.";
		}
		
	?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>