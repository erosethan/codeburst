<?php
	function StageInfo($CodingStart, $BurningStart, $RoundEnd){
		$ActualTime = time();
		if($ActualTime < $CodingStart)
		{
			// Round has not started yet.
			$StageName = 'Espera';
			$StageMessage = 'Ronda empieza en';
			$StageRemaining = $CodingStart - $ActualTime;
		}
		elseif($ActualTime < $BurningStart)
		{
			// Round is at coding stage.
			$StageName = 'Coding';
			$StageMessage = 'Coding termina en';
			$StageRemaining = $BurningStart - $ActualTime;
		}
		elseif($ActualTime < $RoundEnd)
		{
			// Round is at burning stage.
			$StageName = 'Burning';
			$StageMessage = 'Ronda termina en';
			$StageRemaining = $RoundEnd - $ActualTime;
		}
		else
		{
			// Round has ended.
			$StageName = 'Closure';
			$StageMessage = 'Ronda finalizada';
			$StageRemaining = 0;
		}
		return array($StageName, $StageMessage, $StageRemaining);
	}
?>