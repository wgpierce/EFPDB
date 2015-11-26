<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
   <?php
  	//TODO: qstat returns refuese to show anything...
  	
  	if(isset($_GET['jobID'])) {
  		
		$jobID = $_GET['jobID'];
		$return_array;
	$qstatReturn = exec("sudo -u efpdb-user qstat -f $jobID.dhcpa211 2>&1", $return_array);
	$pos = strpos($qstatReturn, "Unknown Job Id");
	//echo $qstatReturn;
	
	//Database queries - secured with environmental variables and against injection
	$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
	if ($conn->connect_error) {echo "failed"; die("Connection failed: " . $conn->connect_error);}
	$mysql_query = $conn->prepare("SELECT Fragment FROM main WHERE JobID=?");
	$mysql_query->bind_param('s', $jobID);
	
	if ($pos !== FALSE) {
		echo "This job is already done! <br>";
		if($mysql_query->execute()) {
			$mysql_query->bind_result($currFragment);
			while($row = $mysql_query->fetch()) {
				echo $currFragment;
				echo "The results are available <a href=\"view_job.php?view_mol=$currFragment\">here</a>!";
			}
		}
	} else {
		echo "<strong>qstat output:</strong><br>";
		for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
		}
		//get the output file that this is and show log output
		echo "<strong>Log Output (last 100 lines):</strong><br>";
		if($mysql_query->execute()) {
			$mysql_query->bind_result($currFragment);
			while($row = $mysql_query->fetch()) {
				unset($return_array);
				exec("tail -100 ../database/log_files/" . basename($currFragment, ".efp") . ".inp.log", $return_array);
				for ($i = 0; $i < count($return_array); $i++) {
						echo $return_array[$i] . "<br />";
				}
			}
		} else echo "failed";
	}
	$mysql_query->close();
	$conn->close();
	}
?>
<!--going to need some javascript here to display fancy graphics -->






	</p>
</div>
<?php require('../includes/footer.html'); ?>