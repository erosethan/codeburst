<?php
	include_once 'config.php';

	session_start();

	// If already logged in
	if(isset($_SESSION['UserId'])) {
		header('Location: index.php');
		die();
	}

	// Start connection
	$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$connection) {
		die('Fatal error: ' . mysql_error());
	}
	
	mysql_select_db(DB_NAME);

	$result = mysql_query(
			"select UserId, UserName from `User` where " .
			"ucase(UserName) = ucase('" . mysql_real_escape_string($_POST['username']) . "') and " .
			"UserPass = '" . mysql_real_escape_string($_POST['password']) . "';"
			);
	// Incorrect user and password
	if (mysql_num_rows($result) == 0) {
		header('Location: loginf.php?err');
		die();
	}
	$userInfo = mysql_fetch_array($result);
	$_SESSION['UserId'] = $userInfo['UserId'];
	$_SESSION['UserName'] = $userInfo['UserName'];

	// Redirect
	header('Location: index.php');