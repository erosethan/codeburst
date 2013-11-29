<?php
        include_once 'util.php';
        include_once 'config.php';

        session_start();
        
        // Redirect if not logged in
        if(!isset($_SESSION['UserId']))
        {
                header('Location: loginf.php');
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
        
        $query = "select * from round where RoundId = $RoundId";
		$result = mysql_query($query);
        // Check if there is any match for this user
        if(mysql_num_rows($result) == 0)
        {
			header('Location: index.php');
			die();
		}
        else
		{
			$row = mysql_fetch_array($result);
			
			// Get round information.
			$RoundName = $row['RoundName'];
			$RoundBase = $row['RoundBase'];
			$CodingStart = strtotime($row['CodingStart']);
			$BurningStart = strtotime($row['BurningStart']);
			$RoundEnd = strtotime($row['RoundEnd']);

			// Get actual stage information.
			$Stage = StageInfo($CodingStart, $BurningStart, $RoundEnd);

			mysql_free_result($result);
		}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<title>CodeBurst! Arena</title>
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
			
				<h2 id="content-title">Scoreboard</h2>
				<hr class="noscreen" />				
				<div id="content-in">
					<?php
						if($Stage['StageName'] == WAIT_STAGE)
						{
					?>
						<table class="nomb table-style01">
							<tr>
								<th>Lugar</th>
								<th>Nombre de Usuario</th>
								<th>Resultado</th>
							</tr>
						</table>
					<?php
						}
						if($Stage['StageName'] == CODE_STAGE || $Stage['StageName'] == BURN_STAGE)
						{	
							$place = 1;
					?>
						<table class="nomb table-style01">
							<tr>
								<th>Lugar</th>
								<th>Nombre de Usuario</th>
								<th>Resultado</th>
							</tr>
					<?php
							$query = "select * from codingstagescore where RoundId = $RoundId and Submission Is NOT NULL order by Submission ";
							$result = mysql_query($query);
							
							while ($row = mysql_fetch_array($result)) {
					?>
							<tr>
								<td style="text-align:center;"> <?php echo $place ?></td>
								<td> <?php echo $row["UserName"]?></td>
								<td> <img src="design/check.png" width="30" height="30" style="display:block; margin-left: auto; margin-right: auto;"> </img> </td>
							</tr>
					<?php
								$place++;
							}
							$query = "select * from codingstagescore where RoundId = $RoundId and Submission Is NULL ";
							$result = mysql_query($query);
							
							while ($row = mysql_fetch_array($result)) {
					?>
							<tr>
								<td style="text-align:center;"> <?php echo $place ?></td>
								<td> <?php echo $row["UserName"]?></td>
								<td> </td>
							</tr>
					<?php
							}
					?>
						</table>
					<?php
						}
						if($Stage['StageName'] == CLOS_STAGE)
						{
								$place = 1;
					?>
						<table class="nomb table-style01">
							<tr>
								<th>Lugar</th>
								<th>Nombre de Usuario</th>
								<th>Score</th>
								<th>Tiempo</th>
								<th>Resultado</th>
							</tr>
					<?php
							$query = "select * from finalscore where RoundId = $RoundId and Submission Is NOT NULL order by Score DESC, Submission ASC";
							$result = mysql_query($query);
							
							while ($row = mysql_fetch_array($result)) {
					?>
							<tr <?php if ($row["Winner"] == "1") { echo "class=\"bg\"";}?> >
								<td style="text-align:center;"> <?php echo $place ?></td>
								<td> <?php echo $row["UserName"]?> </td>
								<td style="text-align:right;"> <?php echo $row["Score"]?> </td>
								<td style="text-align:right;"> <?php echo $row["Submission"]?> </td>
								<td> <img src="design/<?php echo ($row["Winner"] == "0") ? "loser" : "winner"; ?>.png" width="30" height="30" style="display:block; margin-left: auto; margin-right: auto;"> </img> </td>
							</tr>
					<?php
								$place++;
							}
							$query = "select * from finalscore where RoundId = $RoundId and Submission Is NULL order by Score DESC, Submission ASC";
							$result = mysql_query($query);
							
							while ($row = mysql_fetch_array($result)) {
					?>
							<tr <?php if ($row["Winner"] == "1") { echo "class=\"bg\"";}?> >
								<td style="text-align:center;"> <?php echo $place ?></td>
								<td> <?php echo $row["UserName"]?> </td>
								<td style="text-align:right;"> <?php echo $row["Score"]?> </td>
								<td style="text-align:right;"> - </td>
								<td> <img src="design/<?php echo ($row["Winner"] == "0") ? "loser" : "winner"; ?>.png" width="30" height="30" style="display:block; margin-left: auto; margin-right: auto;"> </img> </td>
							</tr>
					<?php
							}
					?>
						</table>
					<?php
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
