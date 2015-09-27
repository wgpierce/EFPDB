
<?php

/* Not sure what this check is for  */
			if(isset($_POST['submit'])) {
			    $check = filesize($_FILES['fileToUpload']['tmp_name']);
				echo "Check is $check <br \>";
			    if($check) {
			    	//echo $check['mime'];
			        echo "File exists<br />";
			        $uploadOk = 1;
			    } else {
			        echo "File is not valid.<br />";
			        $uploadOk = 0;
			    }
			}
			
			
?>