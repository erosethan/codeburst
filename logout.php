<?php
	session_start();
	unset($_SESSION['UserId']);
	header('Location: index.php');