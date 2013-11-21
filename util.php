<?php
	if(!defined('UTIL_INCLUDE'))
	{
		define('UTIL_INCLUDE', true);

		define('WAIT_STAGE', 'Espera');
		define('CODE_STAGE', 'Coding');
		define('BURN_STAGE', 'Burning');
		define('CLOS_STAGE', 'Closure');
		
		function StageInfo($CodingStart, $BurningStart, $RoundEnd){
			$ActualTime = time();
			if($ActualTime < $CodingStart)
			{
				// Round has not started yet.
				$StageName = WAIT_STAGE;
				$StageMessage = 'Ronda empieza en';
				$StageRemaining = $CodingStart - $ActualTime;
			}
			elseif($ActualTime < $BurningStart)
			{
				// Round is at coding stage.
				$StageName = CODE_STAGE;
				$StageMessage = 'Coding termina en';
				$StageRemaining = $BurningStart - $ActualTime;
			}
			elseif($ActualTime < $RoundEnd)
			{
				// Round is at burning stage.
				$StageName = BURN_STAGE;
				$StageMessage = 'Ronda termina en';
				$StageRemaining = $RoundEnd - $ActualTime;
			}
			else
			{
				// Round has ended.
				$StageName = CLOS_STAGE;
				$StageMessage = 'Ronda finalizada';
				$StageRemaining = 0;
			}
			return array($StageName, $StageMessage, $StageRemaining);
		}
	}
?>