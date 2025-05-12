<?php
$server = "postiefs.mysql.database.azure.com";
	$username = "postiefsadmin";
	$pass = "Welc0me@PST";
	$db = "postiefsdb";

	if(in_array($_SERVER['HTTP_HOST'],['localhost'])){
		$server = "localhost";
		$username = "root";
		$pass = null;
		$db = "login-test";
	}

	//create connection 

	$conn = mysqli_connect($server,$username,$pass,$db);

	//check conncetion

	if($conn->connect_error){

		die ("Connection Failed!". $conn->connect_error);
	}

?>