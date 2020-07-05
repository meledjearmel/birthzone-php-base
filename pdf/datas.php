<?php

	$child    = $_SESSION['print']['child'];
	$father   = $_SESSION['print']['father'];
	$mother	  = $_SESSION['print']['mother'];
	$register = $_SESSION['print']['register'];
	$commune  = $_SESSION['print']['commune'];

	function month($m)
	{
		if ($m == 1) {
		$mois = 'janvier';
		} elseif ($m == 2) {
			$mois = 'fevrier';
		} elseif ($m == 3) {
			$mois = 'mars';
		} elseif ($m == 4) {
			$mois = 'avril';
		} elseif ($m == 5) {
			$mois = 'mai';
		} elseif ($m == 6) {
			$mois = 'juin';
		} elseif ($m == 7){
			$mois = 'juillet';
		} elseif ($m == 8) {
			$mois = 'aout';
		} elseif ($m == 9) {
			$mois = 'septembre';
		} elseif ($m == 10) {
			$mois = 'octobre';
		} elseif ($m == 11) {
			$mois = 'novembre';
		} elseif ($m == 12) {
			$mois = 'decembre';
		}

		return $mois;
	}