<?php
	include_once('../includes/php_functions.php');
	$conn = makeConn();
	
	$mysql_query = $conn->prepare("SELECT isRunning, JobID FROM main WHERE isRunning='T'");
	
	if($mysql_query->execute()) {
	
		$mysql_query->bind_result($currjobID);
		//if there is a row at all
		while($row = $mysql_query->fetch()) {
			$qstatReturn = exec('sudo -u efpdb-user qstat -f $currjobID.dhcpa211 2>&1');
			$pos = strpos($qstatReturn, 'Unknown Job Id');
			if ($pos !== FALSE) {
				//then it is done!
				$mysql_query2 = $conn->prepare("UPDATE main SET isRunning='F' WHERE JobID=?");
				$mysql_query2->bind_param('s', $currjobID);
				if($mysql_query2->execute()) echo 'Done'; else echo 'Not Done';				
				$mysql_query2->close();
			}
			//else the job is running and we leave it
		}
	
	}
	$mysql_query->close();
	$conn->close();
?>
