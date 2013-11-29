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
		
		// Redirect if user is not root.
		if($_SESSION['UserId'] != 0)
		{
			header('Location: index.php');
			die();
		}
        
        $UserId = $_SESSION['UserId'];

        mysql_connect(DB_HOST, DB_USER, DB_PASS);
        mysql_select_db(DB_NAME);
        
        $query = "select RoundId, RoundName, RoundBase, CodingStart, BurningStart, RoundEnd, RedUserId, Red.UserName as RedName, Blue.UserName as BlueName from `Match` natural join `Round` join User as Red join User as Blue on Red.UserId = RedUserId and Blue.UserId = BlueUserId order by CodingStart desc";
        $result = mysql_query($query);
        $noMatchFound = false;
        // Check if there is any match for this user
        if(mysql_num_rows($result) == 0)
        {
			// We can do something with this flag to show some kind of message :)
            $noMatchFound = true;
        }
        else
		{
			$lastMatch = 0;
			while( $row = mysql_fetch_array($result) )
			{				
				// Get both users name.
				$UserName = $row['RedName'];
				$RivalName = $row['BlueName'];
				$RoundId = $row['RoundId'];
				if($row['RedUserId'] != $UserId)
						list($UserName, $RivalName) = array($RivalName, $UserName);
				
				// Get round information.
				$RoundName = $row['RoundName'];
				$RoundBase = $row['RoundBase'];
				$CodingStart = strtotime($row['CodingStart']);			
				$RoundEnd = strtotime($row['RoundEnd']);
				
				$Matches[$lastMatch++] = array("UserName" => $UserName, 
									"RivalName" => $RivalName, 
									"RoundName" => $RoundName, 
									"RoundBase" => $RoundBase, 
									"CodingStart" => $CodingStart, 
									"RoundEnd" => $RoundEnd,
									"RoundId" => $RoundId);
			}
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
			
				<h2 id="content-title">Rondas disponibles</h2>
				<hr class="noscreen" />				
				<div id="content-in">
				
					<table class="nomb table-style01">
					<tr>
						<th>Nombre de la ronda</th>
						<th>Usuario 1</th>
						<th>Usuario 2</th>
						<th>Fecha de inicio</th>
						<th></th>
					</tr>
					
					<?php if ($noMatchFound == false ) foreach($Matches as $match){ ?>
					<tr style='background-color:<?php if ($match["RoundEnd"] < time() ) echo "red"; else echo "#7fff00";?>'>
						<td id = "roundname"><?php echo $match["RoundName"]; ?></td>
						<td id = "username"><?php echo $match["UserName"]; ?></td>
						<td id = "rivalname"><?php echo $match["RivalName"]; ?></td>
						<td id = "startdate"><?php echo date("d/M/Y H:i:s", $match["CodingStart"]); ?></td>
						<td id = "enter"><a href="monitor.php?round=<?php echo $match["RoundId"];?>">Entrar</a></td>
					</tr>
					<?php } ?>
					</table>
					

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
