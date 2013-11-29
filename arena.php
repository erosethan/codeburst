<?php
	include_once 'util.php';
	include_once 'config.php';

	session_start();
	
	// Redirect if not logged in
	if(!isset($_SESSION['UserId']))
	{
		header('Location: login.php');
		die();
	}
	
	// Redirect if round does not exists.
	if(!isset($_GET['round']))
	{
		header('Location: index.php');
		die();
	}
	
	$RoundId = $_GET['round'];
	$UserId = $_SESSION['UserId'];

	mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME);
	
	$query = "select RoundName, RoundBase, CodingStart, BurningStart, RoundEnd, Red.UserName as RedName, Red.UserId as RedId, Blue.UserId as BlueId, Blue.UserName as BlueName from `Match` natural join `Round` join User as Red join User as Blue on Red.UserId = RedUserId and Blue.UserId = BlueUserId where RoundId = $RoundId and (RedUserId = $UserId or BlueUserId = $UserId)";
	$result = mysql_query($query);
	
	// Redirect if user not matched in this round.
	if(mysql_num_rows($result) == 0)
	{
		header('Location: index.php');
		die();
	}
	
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	
	// Get both users name.
	$RivalId = $row['BlueId'];
	$UserName = $row['RedName'];
	$RivalName = $row['BlueName'];
	if($row['RedId'] != $UserId)
	{
		$UserName = $row['BlueName'];
		$RivalName = $row['RedName'];
		$RivalId = $row['RedId'];
	}
	
	// Get round information.
	$RoundName = $row['RoundName'];
	$RoundBase = $row['RoundBase'];
	$CodingStart = strtotime($row['CodingStart']);
	$BurningStart = strtotime($row['BurningStart']);
	$RoundEnd = strtotime($row['RoundEnd']);
	
	// Get actual stage information.
	$Stage = StageInfo($CodingStart, $BurningStart, $RoundEnd);
	
	$CurrentDate = new DateTime();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<title>CodeBurst! Arena</title>
	
	<script src = "js/jquery.js"></script>
	
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/main.css" />
	<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="css/main-msie.css" /><![endif]-->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/scheme.css" />
	<link rel="stylesheet" media="print" type="text/css" href="css/print.css" />
</head>
<body>
	<div id="main">
		<?php include("header.php"); ?>
		<div id="cols" class="box">
			<div id="content">			
				<h2 id="content-title"><?php echo $RoundName; ?></h2>
				<hr class="noscreen" />				
				<div id="content-in">
					<div id = "users" style = "text-align: center;"><h1><?php echo $UserName; ?> vs <?php echo $RivalName; ?></h1></div><hr/>
					<div id = "stagename" style = "text-align: right;"><h2>Etapa: <?php echo $Stage['StageName']; ?></h2></div>
					<div id = "stagemessage" style = "text-align: right;"><?php echo $Stage['StageMessage']; ?>: <?php echo date("H:i:s", $Stage['StageRemaining'] - $CurrentDate->getOffset()); ?></div><br/><hr/>
					<?php
						include_once 'forms.php';
						if($Stage['StageName'] == WAIT_STAGE)
						{
							echo '<h3>¡Prepárate! La ronda está por comenzar :D</h3>';
						}
						if($Stage['StageName'] == CODE_STAGE)
						{
							echo '<h3>Subir una solución</h3>';
							FileSubmit('code', $RoundId);
							
							$query = "select * from Code where RoundId = $RoundId and UserId = $UserId";
							$result = mysql_query($query);
							if(mysql_num_rows($result) > 0)
							{
								$row = mysql_fetch_array($result);
								
								echo '<hr/><h3>Último código subido</h3>';
								FileDisplay($RoundId, $UserId, $row['CodeLang']);
								echo '<p>Enviado: ' . $row['Submission'];
							}
							mysql_free_result($result);
						}
						if($Stage['StageName'] == BURN_STAGE)
						{
							$query = "select * from Code where RoundId = $RoundId and (UserId = $UserId or UserId = $RivalId)";
							$result = mysql_query($query);
							if(mysql_num_rows($result) == 2)
							{
								$row = mysql_fetch_array($result);
								if($row['UserId'] == $UserId)
									$row = mysql_fetch_array($result);
								mysql_free_result($result);
								
								echo '<h3>Código de tu oponente</h3>';
								FileDisplay($RoundId, $RivalId, $row['CodeLang']);
								
								$query = "select * from Burn where RoundId = $RoundId and UserId = $RivalId";
								$result = mysql_query($query);
								if(mysql_num_rows($result) == 0)
								{
									echo '<hr/><h3>Subir un burn</h3>';
									FileSubmit('burn', $RoundId);
								}
								else
								{
									$row = mysql_fetch_array($result);
									
									echo '<hr/><h3>Ya has subido un burn</h3>';
									FileDisplay($RoundId, $RivalId, 'burn');
									echo '<p>Enviado: ' . $row['Submission'];
								}
								mysql_free_result($result);
							}
							else
							{
								echo '<h3>¡Relajate! Ya no hay nada que hacer en esta etapa :)';
								mysql_free_result($result);
							}
						}
						if($Stage['StageName'] == CLOS_STAGE)
						{
							echo '<h3>La ronda ha finalizado, ¡espera los resultados! D:</h3>';
						}
					?>
					<div style = "text-align: right;"> <a href="scoreboard.php?round=<?php echo $RoundId; ?>"> Scoreboard </a></div><br/>
				</div>
			</div>
			<hr class="noscreen" />
			<?php include("sidebar.php"); ?>		
		</div>
		<div id="cols-bottom"></div>
		<hr class="noscreen" />		
		<?php include("footer.php"); ?>
	</div>
	</body>
</html>
