<?php
	$VALID_LANG = Array('c', 'cpp', 'java', 'py');
	$dir = 'codes';
	$subdir = 'test'; // TODO
	$filename = 'coso'; // TODO
	$lang = $_POST['lang'];
	if (!in_array($lang, $VALID_LANG))
		die('ERROR'); // TODO
	$filename .= ".$lang";
	$route = "$dir/$subdir/$filename";
	if ($_FILES["file"]["error"] == 0)
		move_uploaded_file($_FILES["file"]["tmp_name"], $route);
	else
		file_put_contents($route, $_POST['code']);
?>