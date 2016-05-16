<?php
//dynamically add our header and footer
require ('../includes/head.html');
require ('../includes/header.html');
?>
<div id="site_content">
	<p>
		<?php
		include_once ('../includes/php_functions.php');

		if (isset($_GET['jobID'])) {

			$jobID = $_GET['jobID'];
			$return_array;
			
			$qstatReturn = exec("sudo -u efpdb-user qstat -f $jobID.dhcpa211 2>&1", $return_array);
			$pos = strpos($qstatReturn, "Unknown Job Id");
			$conn = makeConn();
			
			$mysql_query = $conn->prepare("SELECT Fragment FROM main WHERE JobID=?");
			$mysql_query->bind_param('s', $jobID);

			if ($pos !== FALSE) {
				if ($mysql_query -> execute()) {
					$mysql_query -> bind_result($currFragment);
					//this should only return one query
					if ($row = $mysql_query -> fetch()) {
						//echo $currFragment;
						echo "This job is already done! <br>";
						echo "The results are available <a href=\"view_mol.php?select_mol=$currFragment\">here</a>!";
					} else {
						echo "This job does not exist! <br>";
					}
				}
			} else {
				//job is still running
				echo "<strong>qstat output:</strong><br>";
				for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
				}
				//get the output file that this is and show log output
				echo "<strong>Log Output (last 100 lines):</strong><br>";
				if ($mysql_query -> execute()) {
					$mysql_query -> bind_result($currFragment);
					while ($row = $mysql_query -> fetch()) {
						unset($return_array);
						exec("tail -100 ../database/log_files/" . basename($currFragment, ".efp") . ".inp.log", $return_array);
						for ($i = 0; $i < count($return_array); $i++) {
							echo $return_array[$i] . "<br />";
						}
					}
				} else
					echo "failed";
			}
			$mysql_query -> close();
			$conn -> close();
		} else {
			//if no input provided, show prompt
			echo '<form action="view_job.php" method="GET">
							<label>Input Job ID:
								<input type="text" name="jobID">
							</label><br>
							<input type="submit"><br>
						</form>';
		}
	?>
<!--going to need some javascript here to display fancy graphics -->

	</p>
</div>
<?php
	require ('../includes/footer.html');
?>