<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>

    <?php
    	echo "All Jobs: <br>";
	    $command = "qstat -anl batch";
	    exec($command, $return_array);
		
		
		echo "Server Status: <br>";
		$command = "pbsnodes -a";
		exec($command, $return_array);
		

    ?>
<!--going to need some javascript here to display fancy graphics -->






	</p>
</div>
<?php require('../includes/footer.html'); ?>