<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>

    <?php
    	$jobID = $_GET['jobID'];
		
    	exec("qstat -f $jobID", $return_array);
		
		for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
		}
		
/*
		exec("tail -100 ../database/inp_files/")
		for ($i = 0; $i < count($return_array); $i++) {
					echo $return_array[$i] . "<br />";
		}		
 */

//format return array as requested


    ?>
<!--going to need some javascript here to display fancy graphics -->






	</p>
</div>
<?php require('../includes/footer.html'); ?>