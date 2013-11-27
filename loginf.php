<?php
	include_once 'forms.php';

	session_start();
	// If already logged in
	if(isset($_SESSION['UserId'])) {
		header('Location: index.php');
		die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>CodeBurst! Login</title>
	</head>
	<body>
		<script src = "js/jquery.js"></script>
		<?php LoginForm(); ?>
	</body>
</html>