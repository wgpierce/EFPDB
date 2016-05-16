<?php

function makeConn () {
		$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DATABASE'));
		if ($conn->connect_error) {echo "Failed connection with database"; die("Connection failed: " . $conn->connect_error);}
		return $conn;
}

?>