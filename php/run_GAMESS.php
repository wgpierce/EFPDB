<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
	<?php
		if(file_exists("../database/inp_files/" . $_GET['gamess_input'])) {
			$gamess_input = $_GET['gamess_input'];
			//Database queries - secured with environmental variables and against injection
			$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
			if ($conn->connect_error) {
				echo "failed";
				die("Connection failed: " . $conn->connect_error);
			}
			
			//see if the job is running - no duplicates				
			$mysql_query = $conn->prepare("SELECT Fragment,isRunning,JobID FROM main WHERE InputFile=?");
			$mysql_query->bind_param('s', $gamess_input);
			
			if($mysql_query->execute()) {
				$mysql_query->bind_result($fragment, $is_running, $jobID);
				$row = $mysql_query->fetch(); //should only be one....	
				#echo $fragment;
				#echo $is_running;
				if ($is_running) {
					echo "This file is already running! <br>";
					echo "Click <a href=\"view_job.php?jobID=$jobID\">here</a> to see its progress<br>";
				}
				if(!$is_running && $fragment) { //if the fragment already exists
					echo "This process has already been completed file already exists <a href=\"mol_info.php?select_mol=$Fragment\">here!</a>";	
				}
				if(!$is_running && !$fragment) {
					//if not running and fragment doesn't exist, create fragment
					//RUN GAMESS!!!!!!!!!
					$mysql_query->close();	
					echo "Now running GAMESS on this file...<br />";
	//Disabling action for now	
/*						
					$return_jobID = exec("./../scripts/submissionscript $gamess_input", $return_array);
					
					echo $return_jobID . "<br>";
	/*				//TODO: need to have qsub notify when job is done and then update databse
					//Database adding	
					$mysql_query = $conn->prepare("UPDATE main set isRunning='1',JobID=? WHERE InputFile=?");
					$mysql_query->bind_param('ss', $return_jobID, $gamess_input);
	/**/			
							
					echo "Progress report will be available <a href=\"view_job.php?jobID=$return_jobID\">here</a>";
				}
			
			} else {
				echo "Failed connection with database";
			}
			
			//$mysql_query->close();
			$conn->close();
		} else {
			echo "Sorry, this file doesn't exist.";
		}
		
	?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>


