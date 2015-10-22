


<?php
$action = "CREATE TABLE MyTable(Occurence INT(128), Decription TEXT, 
		   Molecule VARCHAR, Geometry TEXT, Fragment TEXT),
		   ";
//ALREADY RUN, NEVER NEED TO RUN THIS AGAIN
  //. "AND WHERE Parameter1=param";
/*Other commands
= "DELETE FROM main
	where description='something unwanted'";
= "UPDATE main 
   SET column1=value1, column2=value2
   where some_column=some_value"   //careful, can accidentallly update entire database
= "INSERT INTO main
   (column1, column2, columnn)
   VALUES (value1, vlaue2, valuen)"//add specific values
 */

 /*
$conn = mysqli_connect(ini_get("mysql.default.host"),
ini_get("mysql.default.user"),
ini_get("mysql.default.password"),
ini_get("mysql.default.database"))*/

//echo $_ENV['MYSQL_USER'] . "<br>";   //doesn't work..
/*
echo getenv('MYSQL_USER') . "<br>";
echo getenv('MYSQL_HOST') . "<br>";
echo getenv('MYSQL_PASSWORD') . "<br>";
*/


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