<?php
	// Stages name constants.
	define('WAIT_STAGE', 'Espera');
	define('CODE_STAGE', 'Coding');
	define('BURN_STAGE', 'Burning');
	define('CLOS_STAGE', 'Closure');
	
	// Stages message constants.
	define('WAIT_MSG', 'Ronda comienza en');
	define('CODE_MSG', 'Coding termina en');
	define('BURN_MSG', 'Ronda termina en');
	define('CLOS_MSG', 'Ronda finalizada');
	
	function StageInfo($CodingStart, $BurningStart, $RoundEnd){
		$ActualTime = time();
		if($ActualTime < $CodingStart)
		{
			// Round has not started yet.
			$StageName = WAIT_STAGE;
			$StageMessage = WAIT_MSG;
			$StageRemaining = $CodingStart - $ActualTime;
		}
		elseif($ActualTime < $BurningStart)
		{
			// Round is at coding stage.
			$StageName = CODE_STAGE;
			$StageMessage = CODE_MSG;
			$StageRemaining = $BurningStart - $ActualTime;
		}
		elseif($ActualTime < $RoundEnd)
		{
			// Round is at burning stage.
			$StageName = BURN_STAGE;
			$StageMessage = BURN_MSG;
			$StageRemaining = $RoundEnd - $ActualTime;
		}
		else
		{
			// Round has ended.
			$StageName = CLOS_STAGE;
			$StageMessage = CLOS_MSG;
			$StageRemaining = 0;
		}
		return array('StageName' => $StageName, 'StageMessage' => $StageMessage, 'StageRemaining' => $StageRemaining);
	}
?>