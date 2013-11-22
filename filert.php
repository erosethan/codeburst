<?php
	define('CODE_DIR', 'codes');
	define('BURN_DIR', 'burns');
	
	function getCodeDirName($roundId) {
		return CODE_DIR . "/$roundId";
	}
	
	function getBurnDirName($roundId) {
		return BURN_DIR . "/$roundId";
	}
	
	function getCodeFileName($roundId, $userId, $lang) {
		return "{$roundId}_{$userId}.{$lang}";
	}
	
	function getBurnFileName($roundId, $userId) {
		return "{$roundId}_{$userId}.txt";
	}