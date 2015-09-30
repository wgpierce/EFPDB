<?php
	//dynamically add our header and footer
	require('../includes/head.html');
	require('../includes/header.html'); 
?>
<div id="main">
	<table style="width:100%"
		<tr>
			<th>Filename</th>
			<th>Other Attributes</th>
		</tr>
	<?php
		$data_dir = "../database/efp_files/";
		$dir = new DirectoryIterator($data_dir);
		foreach ($dir as $fileinfo) {
			if(!$fileinfo->isDot()) {
				//var_dump($fileinfo->getFileName());
				//create a table element
				echo "<tr>
							<td>" .	
							 $file_contents = file_get_contents($data_dir.$fileinfo) .
							 //Iterate through this and file info we need
							"</td>
					 </tr>";
			}
		}
	?>
	</table>
	
</div>
		
<?php require('../includes/footer.html'); ?>	

