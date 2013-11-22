<?php

	include_once 'config.php';
	include_once 'util.php';

	session_start();
	// Redirect if not logged in
	if (!isset($_SESSION['UserId'])) {
		header('Location: login.php');
		die();
	}

	// Start connection
	// TODO Validate connection maybe
	mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME);

	// Get info
	$userId = (int)$_SESSION['UserId'];
	$roundId = (int)1; // TODO Get from somewhere :P

	// Get round info
	$result = mysql_query(
				"select RoundId, CodingStart, BurningStart, RoundEnd, RedUserId, BlueUserId " .
				"from `Round` natural join `Match` " .
				"where RoundId = $roundId and (RedUserId = $userId or BlueUserId = $userId) " .
				"limit 1;"
			);
	// User not matched in this round or round doesn't exist
	if (mysql_num_rows($result) == 0) {
		die('Round doesnt exist'); // TODO
	}
	$roundInfo = mysql_fetch_array($result);
	$stInfo = StageInfo(
			strtotime($roundInfo['CodingStart']), strtotime($roundInfo['BurningStart']), strtotime($roundInfo['RoundEnd'])
			);

	// Valid languages extensions
	$VALID_LANG = Array('c', 'cpp', 'java', 'py');

	$type = $_POST['type'];

	switch ($type) {

		case 'code':

			// Check if we're in coding stage
			if ($stInfo['StageName'] == WAIT_STAGE) {
				die('Has not started'); // TODO
			} else if ($stInfo['StageName'] != CODE_STAGE) {
				die('Ended stage'); // TODO
			}

			// Check if language is valid
			$lang = $_POST['lang'];
			if (!in_array($lang, $VALID_LANG)) {
				die('ERROR'); // TODO
			}

			// Set file route
			$dir = 'codes';
			$filename = "{$roundId}_{$userId}.{$lang}";

			// Save or update code in DB
			$result = mysql_query(
					"select 1 as res from `Code` " .
					"where UserId = $userId and RoundId = $roundId limit 1;"
					);
			if (mysql_num_rows($result) == 0) {
				mysql_query(
						"insert into `Code`(UserId, RoundId, Submission, CodeLang) " .
						"values ($userId, $roundId, '" . date(DATE_ATOM) . "', '$lang');"
						);
				// TODO Inserted code
			} else {
				mysql_query(
						"update `Code` set Submission = '" . date(DATE_ATOM) . "', CodeLang = '$lang' " .
						"where UserId = $userId and RoundId = $roundId;"
						);
				// TODO New code
			}

			break;

		case 'burn':

			// Check if we're in coding stage
			if ($stInfo['StageName'] == CLOS_STAGE) {
				die('Ended stage'); // TODO
			} else if ($stInfo['StageName'] != BURN_STAGE) {
				die('Has not started'); // TODO
			}

			// Check if user uploaded a code to proceed
			$result = mysql_query(
					"select 1 as res from `Code` " .
					"where UserId = $userId and RoundId = $roundId limit 1;"
					);
			if (mysql_num_rows($result) == 0) {
				die('Not uploaded code'); // TODO
			}
			
			// Set file route
			$dir = 'burns';
			$burnedid = ($roundInfo['RedUserId'] == $userId) ? $roundInfo['BlueUserId'] : $roundInfo['RedUserId'];
			$filename = "{$roundId}_{$burnedid}.txt";

			// Save or update burn in DB
			$result = mysql_query(
					"select 1 as res from `Burn` " .
					"where UserId = $burnedid and RoundId = $roundId limit 1;"
					);
			if (mysql_num_rows($result) == 0) {
				mysql_query(
						"insert into `Burn`(UserId, RoundId, Submission) " .
						"values ($burnedid, $roundId, '" . date(DATE_ATOM) . "');"
						);
				// TODO Inserted burn
			} else {
				mysql_query(
						"update `Burn` set Submission = '" . date(DATE_ATOM) . "' " .
						"where UserId = $burnedid and RoundId = $roundId;"
						);
				// TODO New burn
			}

			break;

		default:
			die('Invalid type'); // TODO

	}

	// Create file
	$subdir = $roundId;
	$dirroute = "$dir/$subdir";

	if (!file_exists($dirroute))
		mkdir($dirroute, 0777, true);

	$route = "$dirroute/$filename";

	// Use uploaded file; if not possible, use input
	if ($_FILES['file']['error'] == 0)
		move_uploaded_file($_FILES['file']['tmp_name'], $route);
	else
		file_put_contents($route, $_POST['dinput']);
?>