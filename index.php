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
        
        $UserId = $_SESSION['UserId'];

        mysql_connect(DB_HOST, DB_USER, DB_PASS);
        mysql_select_db(DB_NAME);
        
        $query = "select RoundId, RoundName, RoundBase, CodingStart, BurningStart, RoundEnd, RedUserId, Red.UserName as RedName, Blue.UserName as BlueName from `Match` natural join `Round` join User as Red join User as Blue on Red.UserId = RedUserId and Blue.UserId = BlueUserId where (RedUserId = $UserId or BlueUserId = $UserId)";
        $result = mysql_query($query);
        
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
        </head>
        <body>
                <script src = "jquery.js"></script>
				<?php foreach($Matches as $match){ ?>
					<div class = "round">
						<div id = "roundname"><?php echo $match["RoundName"]; ?></div>
						<div id = "username"><?php echo $match["UserName"]; ?></div>
						<div id = "rivalname"><?php echo $match["RivalName"]; ?></div>
						<div id = "startdate"><?php echo date("d/M/Y H:i:s", $match["CodingStart"]); ?></div>
						<div id = "enter"><a href="arena.php?round=<?php echo $match["RoundId"];?>">Enter to the contest</a></div>
					</div>
				<?php } ?>
        </body>
</html>