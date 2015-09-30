<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<p>
	<?php
		echo "Now running GAMESS on this file...<br />";
		if(file_exists("C:\\WebDev\\www\\EFPDB\\src\\database\\inp_files\\" . $_GET['gamess_input'])) {
			//If there is currently a process running	
			if (TRUE) {
				
			} else {
				//create the process
				$command = "meow" . "meow_param";
				exec($command);
			}
			
		} else {
			echo "Sorry, this file doesn't exist.";
		}
		
	?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>