<?php 
	session_start();
	if (empty($_SESSION['auth'])) {
    $_SESSION['alert'] = [
      'type' => 'danger',
      'message' => 'Veuillez vous connectez'
    ];
    header('Location: ../adminer.php');
    
  	}


	require_once 'datas.php';
	$parts = explode('-', $child->birthday);
	$annee = $parts[0];
	$m  = $parts[1];
	$jour  = $parts[2];
	

	$part = explode('-', $register->registers_date);
	$year = $part[0];
	$month = $part[1];
	$day = $part[2];

	$registers_date = "$day/$month/$year";
	require_once '../functions.php';
  	require_once '../inc/functions.php';


  	require_once 'chiffresEnLettres.php';
	$lettres = new chiffreEnLettre();
	$jour = $lettres->Conversion($jour);
	$annee = $lettres->Conversion($annee);

	require 'pdf.php';
	

