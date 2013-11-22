<?php
	include 'util.php';
	include 'config.php';

	session_start();
	if(!isset($_SESSION['UserId']))
		// User not logged in.
		header('Location: login.php');
	else
	{
		if(!isset($_GET['round']))
			// Round does not exists.
			header('Location: index.php');
		else
		{
			$RoundId = $_GET['round'];
			$UserId = $_SESSION['UserId'];
		
			mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME);
			
			$query = "select RoundName, RoundBase, CodingStart, BurningStart, RoundEnd, RedUserId, Red.UserName as RedName, Blue.UserName as BlueName from `Match` natural join `Round` join User as Red join User as Blue on Red.UserId = RedUserId and Blue.UserId = BlueUserId where RoundId = $RoundId and (RedUserId = $UserId or BlueUserId = $UserId)";
			$result = mysql_query($query);
			
			if(mysql_num_rows($result) == 0)
				// User not matched in this round.
				header('Location: index.php');
			else
			{
				$row = mysql_fetch_array($result);
				mysql_free_result($result);
				
				// Get both users name.
				$UserName = $row['RedName'];
				$RivalName = $row['BlueName'];
				if($row['RedUserId'] != $UserId)
					list($UserName, $RivalName) = array($RivalName, $UserName);
				
				// Get round information.
				$RoundName = $row['RoundName'];
				$RoundBase = $row['RoundBase'];
				$CodingStart = strtotime($row['CodingStart']);
				$BurningStart = strtotime($row['BurningStart']);
				$RoundEnd = strtotime($row['RoundEnd']);
				
				// Get actual stage information.
				list($StageName, $StageMessage, $StageRemaining) = StageInfo($CodingStart, $BurningStart, $RoundEnd);
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8"/>
		<title>CodeBurst! Arena</title>
	</head>
	<body>
		<script src = "jquery.js"></script>
		<div id = "roundname"><?php echo $RoundName; ?></div>
		<div id = "stagename"><?php echo $StageName; ?></div>
		<div id = "stagemessage"><?php echo $StageMessage; ?></div>
		<div id = "stageremaining"><?php echo $StageRemaining; ?></div>
		<div id = "username"><?php echo $UserName; ?></div>
		<div id = "rivalname"><?php echo $RivalName; ?></div>
		<?php
			include 'forms.php';
			if($StageName == CODE_STAGE)
			{
				FileSubmit('code');
				
				$query = "select * from Code where RoundId = $RoundId and UserId = $UserId";
				$result = mysql_query($query);
				if(mysql_num_rows($result) > 0)
				{
					$row = mysql_fetch_array($result);
					mysql_free_result($result);
					
					echo '<p>Último enviado: ' . $row['Submission'];
				}
			}
			if($StageName == BURN_STAGE)
			{
				FileSubmit('burn');
			}
		?>
	</body>
</html>
