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
			//TODO: implement GAMESS interfacing here	
			//If there is currently a process running	
			if (TRUE) {
				$calculated_file = 7;
				echo "This process has already been completed! <br >";
				echo "View the results for this file <a href=\"..\database\efp_files\$calculated_file> here</a><br />";
			} else {
				//create the process
				$command = "meow" . "meow_param";
				exec($command);
				
				//TODO:  once done, needs to update database
			}
			
		} else {
			echo "Sorry, this file doesn't exist.";
		}
		
	?>
	</p>
</div>
<?php require('../includes/footer.html'); ?>