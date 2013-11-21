<?php

	$VALID_LANG = Array('c', 'cpp', 'java', 'py');
	$type = $_POST['type'];
	$roundid = 0; // TODO

	switch ($type) {

		case 'code':
			$dir = 'codes';
			$lang = $_POST['lang'];
			$userid = 0; // TODO
			$filename = "{$roundid}_{$userid}";
			if (!in_array($lang, $VALID_LANG))
				die('ERROR'); // TODO
			$filename .= ".$lang";
			break;

		case 'burn':
			$dir = 'burns';
			$burnedid = 0; // TODO
			$filename = "{$roundid}_{$burnedid}.txt";
			break;

		default:
			die('ERROR'); // TODO

	}

	$subdir = $roundid;
	$dirroute = "$dir/$subdir";

	if (!file_exists($dirroute))
		mkdir($dirroute, 0777, true);

	$route = "$dirroute/$filename";

	if ($_FILES['file']['error'] == 0)
		move_uploaded_file($_FILES['file']['tmp_name'], $route);
	else
		file_put_contents($route, $_POST['dinput']);
?>