<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="site_content">
	<p>

    <?php
    	echo "<p class='emph'>All Jobs: </p>";
	    $command = "sudo -u efpdb-user qstat -anl batch";
	    exec($command, $return_array);
		for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
		}
		
		unset($return_array);
		echo "<p class='emph'>Server Status:</p>";
		$command = "pbsnodes -a";
		exec($command, $return_array);
		for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
		}

    ?>
<!--going to need some javascript here to display fancy graphics -->






	</p>
</div>
<?php require('../includes/footer.html'); ?>