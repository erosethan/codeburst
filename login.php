<?php
	include_once 'config.php';

	session_start();
	$_SESSION['UserId'] = 2;
	/*if(isset($_SESSION['UserId']))
		header('Location: index.php');
	else
	{
		
	}*/
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8"/>
		<title>CodeBurst! Login</title>
	</head>
	<body>
		<script src = "jquery.js"></script>
	</body>
</html>
