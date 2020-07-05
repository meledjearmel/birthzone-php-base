<?php

function bissextile($value)
{
	$bissextile = NULL;
	if ($value%4 == 0) {
		if ($value%100 == 0) {
			if ($value%400 == 0) {
				$bissextile = true;
			} else {
				$bissextile = false;
			}
		} else {
			$bissextile = true;
		}
	} else {
		$bissextile = false;
	}

	return $bissextile;
}

function jour($value)
{
	if ($value == 1) {
		$JOUR = 'lundi';
	} elseif ($value == 2) {
		$JOUR = 'mardi';
	} elseif ($value == 3) {
		$JOUR = 'mercredi';
	} elseif ($value == 4) {
		$JOUR = 'jeudi';
	} elseif ($value == 5) {
		$JOUR = 'vendredi';
	} else {
		$JOUR = NULL;
	}

	return $JOUR;
}