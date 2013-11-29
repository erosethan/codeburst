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
	
	// Redirect if user is not root.
	if($_SESSION['UserId'] != 0)
	{
		header('Location: index.php');
		die();
	}
	
	// Redirect if round does not exists.
	if(!isset($_GET['round']))
	{
		header('Location: index.php');
		die();
	}
	
	$RoundId = $_GET['round'];

	mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME);
	
	$query = "select RoundName, RoundBase, CodingStart, BurningStart, RoundEnd, Red.UserName as RedName, Red.UserId as RedId, Blue.UserId as BlueId, Blue.UserName as BlueName from `Match` natural join `Round` join User as Red join User as Blue on Red.UserId = RedUserId and Blue.UserId = BlueUserId where RoundId = $RoundId";
	$result = mysql_query($query);	
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	
	// Get both users name.
	$UserId = $row['RedId'];	
	$UserName = $row['RedName'];
	$RivalName = $row['BlueName'];
	$RivalId = $row['BlueId'];
	
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
							echo '<h3>Esta ronda aún no comienza :)</h3>';
						}
						else
						{
							$query = "select * from Code where RoundId = $RoundId";
							$result = mysql_query($query);
							if(mysql_num_rows($result) > 0)
							{
								while($row = mysql_fetch_array($result))
								{
									if ($row["UserId"] == $RivalId ) $CodeName = $RivalName; else $CodeName = $UserName;
									echo '<hr/><h3>Código enviado por '.$CodeName.'</h3>';
									FileDisplay($RoundId, $row['UserId'], $row['CodeLang']);
									echo '<p>Enviado: ' . $row['Submission'];
								}
							}
							mysql_free_result($result);
							
							if($Stage['StageName'] != CODE_STAGE )
							{								
								$query = "select * from Burn where RoundId = $RoundId";
								$result = mysql_query($query);
								
								while($row = mysql_fetch_array($result))
								{
									if ($row["UserId"] == $RivalId ) $BurnName = $RivalName; else $BurnName = $UserName;
									echo '<hr/><h3>Burn para el código de '.$BurnName.'</h3>';
									FileDisplay($RoundId, $row["UserId"], 'burn');
									echo '<p>Enviado: ' . $row['Submission'];
								}
								mysql_free_result($result);
								
								if($Stage['StageName'] == CLOS_STAGE)
								{
									echo '<h3>La ronda ha finalizado D:</h3>';
								}								
							}
							
						}
					?>
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
