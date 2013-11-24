<?php
	include_once 'config.php';

	session_start();

	// If already logged in
	if(isset($_SESSION['UserId'])) {
		header('Location: index.php');
		die();
	}

	// Start connection
	// TODO Validate connection maybe
	mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME);

	$result = mysql_query(
			"select UserId, UserName from `User` where " .
			"ucase(UserName) = ucase('" . mysql_real_escape_string($_POST['username']) . "');"
			);
	// Incorrect user
	if (mysql_num_rows($result) == 0) {
		header('Location: loginf.php'); // TODO Send error
		die();
	}
	$userInfo = mysql_fetch_array($result);
	$_SESSION['UserId'] = $userInfo['UserId'];
	$_SESSION['UserName'] = $userInfo['UserName'];

	// Redirect
	header('Location: index.php');