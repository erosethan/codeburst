<?php
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASS', 'n0m3l0s3');
	define('DB_NAME', 'codeburst');
	
	// Timezone setting.
	define('GLOBAL_TIMEZONE', 'America/Mexico_City'); // Refer to http://us2.php.net/manual/en/timezones.php
	$timezone_correct = date_default_timezone_set(GLOBAL_TIMEZONE);
	assert($timezone_correct, "Global timezone incorrectly set.");
	
	echo date("r");
?>

