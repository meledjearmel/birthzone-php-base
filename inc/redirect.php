<?php
session_start();
if (empty($_SESSION['auth'])) {
    $_SESSION['alert'] = [
      'type' => 'danger',
      'message' => 'Veuillez vous connectez'
    ];
    header('Location: adminer.php');
    
  }
unset($_SESSION['print']);

require_once 'functions.php';
require_once '../functions.php';

require_once '../db/db.php';



$req = $pdo->prepare("SELECT * FROM children WHERE name = ? AND lastname = ? AND birthday= ? AND sex = ?");
$req->execute([$_GET['name'], $_GET['lastname'], $_GET['birthday'], $_GET['sex'], ]);
$child = $req->fetch();


$req = $pdo->prepare("SELECT * FROM fathers WHERE idfathers = ?");
$req->execute([$child->idfathers]);
$father = $req->fetch();


$req = $pdo->prepare("SELECT * FROM mothers WHERE idmothers = ?");
$req->execute([$child->idmothers]);
$mother = $req->fetch();


$req = $pdo->prepare("SELECT * FROM registers WHERE idregisters = ?");
$req->execute([$child->idregisters]);
$register = $req->fetch();


$req = $pdo->prepare("SELECT * FROM commune WHERE idcommune = ?");
$req->execute([$register->idcommune]);
$commune = $req->fetch();

$a = date("Y");
$m = date("m");
$d = date("d");
$date = "$a-$m-$d";

$req_c = $pdo->prepare("SELECT * FROM onload WHERE onload_date = ? AND idauth = ?");
$req_c->execute([$date, $_SESSION['auth']->idauth]);
$verify = $req_c->fetch();
if ($verify) {

  $counted = $verify->restaured + 1;
  $count = $pdo->prepare("UPDATE onload SET restaured = ? WHERE onload_date = ? AND idauth = ?");
  $count->execute([$counted, $date, $_SESSION['auth']->idauth]);

} else {
  
  $counted =  1;
  $count = $pdo->prepare("INSERT INTO onload SET idauth = ?, restaured = ?, onload_date = ?");
  $count->execute([$_SESSION['auth']->idauth, $counted, $date]);

}


$_SESSION['print']['child'] = $child;
$_SESSION['print']['father'] = $father;
$_SESSION['print']['mother'] = $mother;
$_SESSION['print']['register'] = $register;
$_SESSION['print']['commune'] = $commune;

$req = $pdo->prepare("SELECT demand_copie FROM children WHERE idchildren = ?");
$req->execute([$child->idchildren]);
$copie = $req->fetch();
debug($copie);
if ($copie->demand_copie >= 1) {
  $copie_rest = $copie->demand_copie - 1;


  if ($copie_rest == 0) {

    $type = 'Restauration';
    $req = $pdo->prepare("INSERT INTO gestions SET type = ?, idauth = ?, idchildren = ?, date_gestion = NOW()");
    $req->execute([$type, $_SESSION['auth']->idauth, $child->idchildren]);


    if ($copie->demand_copie == 1) {
      $message = "La copie de $child->name $child->lastname a été tirée";
    } else {
      $message = "Les copies de $child->name $child->lastname ont toutes été tirées";
    }
    

    $req = $pdo->prepare("UPDATE children SET demand_at = NULL, demand_copie = NULL WHERE idchildren = ?");
    $req->execute([$child->idchildren]);

    $_SESSION['flash'] = [
      'message' => $message,
      'type' => 'success'
    ];

  } else {
    $req = $pdo->prepare("UPDATE children SET demand_copie = ? WHERE idchildren = ?");
    $req->execute([$copie_rest, $child->idchildren]);

    if ($copie_rest == 1) {
      $message = "Il reste encore une copie à tirer pour $child->name $child->lastname";
    } else {
      $message = "Il reste encore $copie_rest copies à tirer pour $child->name $child->lastname";
    }

    $_SESSION['flash'] = [
      'message' => $message,
      'type' => 'info'
    ];
  }

}

header('Location: ../pdf');