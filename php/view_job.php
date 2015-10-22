<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>

    <?php
    $command = "../scripts/serverstatus";
    exec($command, $return_array);
	
	//format return array as requested


    ?>
<!--going to need some javascript here to display fancy graphics -->






	</p>
</div>
<?php require('../includes/footer.html'); ?>