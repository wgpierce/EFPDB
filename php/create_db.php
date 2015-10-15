


<?php
$action = "CREATE TABLE MyTable(Occurence INT(128), Decription TEXT, 
		   Molecule VARCHAR, Geometry TEXT, Fragment TEXT),
		   Parameter1 VARCHAR(128)";
//ALREADY RUN, NEVER NEED TO RUN THIS AGAIN


$conn = mysqli_connect($host, $user, $password);

$sql_query = "CREATE DATABASE efpdb";
if (mysqli_query($conn, $sql_query)) {
	echo "Database has been created successfully";
} else {
	echo "Error while creating the database " . mysqli_error($conn);
}
mysqpli_close($conn);

$conn = mysqli_connect(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), 
										getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
										
$sql_query = "CREATE TABLE MyTable(Occurence VARCHAR(3), Decription VARCHAR(1000), 
			  Molecule VARCHAR(100), Geometry VARCHAR(100), Fragment VARCHAR(100)),
			  Parameter1 VARCHAR(100)";
			  
if (mysqli_query($conn, $sql_query)) {
	echo "table has been created successfully";
} else {
	echo "Error while creating the table" . mysqli_error($conn);
}

//create autopopulate as well























?>