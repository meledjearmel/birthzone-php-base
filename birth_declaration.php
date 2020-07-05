

<?php


$title = 'Declaration de naissance';

require 'inc/nav.php';

if (empty($_SESSION['auth'])) {
    $_SESSION['alert'] = [
      'type' => 'danger',
      'message' => 'Veuillez vous connectez'
    ];
    header('Location: adminer.php');
    
  }


  /********************************************************************************************************************************************************************************************************************************************************/

  require_once 'functions.php';
  require_once 'inc/functions.php';

  // VErification des erreurs

  if (!empty($_POST)) {
    $errors = [];

    if (empty($_POST['child_name']) || strlen($_POST['child_name']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['child_name'])) {
      $errors[] = "Le nom de l'enfant est invalide";
    }

    if (empty($_POST['child_lastname']) || strlen($_POST['child_lastname']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['child_lastname'])) {
       $errors[] = "Le prenom de l'enfant est invalide";
    }

    if ($_POST['child_sex'] == 'none') {
      $errors[] = "Le sexe de l'enfant est invalide";
    }

    if (empty($_POST['child_birthday']) || !preg_match("#[0-9]{4}-[0-9]{2}-[0-9]{2}#",$_POST['child_birthday'])) {
      $errors[] = "La date de naissance est invalide JJ-MM-YYYY";
    } else {
      $parts = explode('-', $_POST['child_birthday']);
      $day = $parts[2];
      $month = $parts[1];
      $year = $parts[0];
      if ($year > date("Y") || $month > 12 || $day > 31) {
        $errors[] = "La date de naissance est invalide";
      } else {
        if (!bissextile($year)) {
          if ($month == 2) {
            if ($day > 29) {
              $errors[] = "La date de naissance est invalide";
            }
          }
        } else {
          if ($month == 2) {
            if ($day > 28) {
              $errors[] = "La date de naissance est invalide";
            }
          }
        
          }
        }
    }

    if (empty($_POST['birth_adr']) || $_POST['birth_adr'] == 'none') {
        $errors[] = "Le lieu de naissance est invalide";
    }

    if (empty($_POST['father_name']) || strlen($_POST['father_name']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['father_name'])) {
      $errors[] = "Le nom du pere est invalide";
    }

    if (empty($_POST['father_lastname']) || strlen($_POST['father_lastname']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['father_lastname'])) {
      $errors[] = "Le prenom du pere est invalide";
    }

    if (empty($_POST['father_nat']) || strlen($_POST['father_nat']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['father_nat'])) {
      $errors[] = "La nationalite du pere est invalide";
    }

    if (empty($_POST['father_job']) || strlen($_POST['father_job']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['father_job'])) {
      $errors[] = "Le travail du pere est invalide";
    }

    if (empty($_POST['father_adr']) || $_POST['father_adr'] == 'none') {
      $errors[] = "Le domicile du pere est invalide";
    }

    if (empty($_POST['mother_name']) || strlen($_POST['mother_name']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['mother_name'])) {
      $errors[] = "Le nom de la mere est invalide";
    }

    if (empty($_POST['mother_lastname']) || strlen($_POST['mother_lastname']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['mother_lastname'])) {
      $errors[] = "Le prenom de la mere est invalide";
    }

    if (empty($_POST['mother_nat']) || strlen($_POST['mother_nat']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['mother_nat'])) {
      $errors[] = "La nationalite de la mere est invalide";
    }

    if (empty($_POST['mother_job']) || strlen($_POST['mother_job']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['mother_job'])) {
      $errors[] = "Le travail de la mere est invalide";
    }

    if (empty($_POST['mother_adr']) || $_POST['mother_adr'] == 'none') {
      $errors[] = "Le domicile de la mere est invalide";
    }
    if (isset($parts)) {
      $year = $parts[0];
      $month = $parts[1];
      if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8  || $month == 10  || $month == 12) {
        $day = 31;
      } elseif ($month == 2) {
        if (bissextile($year)) {
          $day = 29;
        } else {
          $day = 28;
        }
      } else {
        $day = 30;
      }

      $register_date = "$year-$month-$day";
    }

    require_once 'alert/alert.php';

    /**************************** Insertion en base de donnees ************************************/

    if (empty($errors)) {
      require_once 'db/db.php';
      
      $reg = $pdo->prepare("SELECT registers_number FROM registers WHERE registers_date = ? ORDER BY registers_number DESC LIMIT 1");
      $reg->execute([$register_date]);
      $register = $reg->fetch();
      if (!empty($register)) {
        $register_number = $register->registers_number + 1;
      } else {
        $register_number = 1;
      }
      
      // Enregistrement du pere

      $ask_f = $pdo->prepare("SELECT * FROM fathers WHERE name = ? AND lastname = ? AND nationalite = ? AND adress = ? AND job = ?");
      $ask_f->execute([ucwords(strtolower($_POST['father_name'])), ucwords(strtolower($_POST['father_lastname'])), ucwords(strtolower($_POST['father_nat'])), ucwords(strtolower($_POST['father_adr'])), ucwords(strtolower($_POST['father_job']))]);
      $father = $ask_f->fetch();
      if (!empty($father)) {
        $fatherid = $father->idfathers;
      } else {
        $req_father = $pdo->prepare("INSERT INTO fathers SET name = ?, lastname = ?, nationalite = ?, adress = ?, job = ?");
        $req_father->execute([ucwords(strtolower($_POST['father_name'])), ucwords(strtolower($_POST['father_lastname'])), ucwords(strtolower($_POST['father_nat'])), ucwords(strtolower($_POST['father_adr'])), ucwords(strtolower($_POST['father_job']))]);
      
        $req = $pdo->query("SELECT * FROM fathers ORDER BY idfathers DESC LIMIT 1");
        $father = $req->fetch();
        $fatherid = $father->idfathers;
      }

      // Enregistrement de la mere

      $ask_m = $pdo->prepare("SELECT * FROM mothers WHERE name = ? AND lastname = ? AND nationalite = ? AND adress = ? AND job = ?");
      $ask_m->execute([ucwords(strtolower($_POST['mother_name'])), ucwords(strtolower($_POST['mother_lastname'])), ucwords(strtolower($_POST['mother_nat'])), ucwords(strtolower($_POST['mother_adr'])), ucwords(strtolower($_POST['mother_job']))]);
      $mother = $ask_m->fetch();
      if (!empty($mother)) {
        $motherid = $mother->idmothers;
      } else {
        $req_mother = $pdo->prepare("INSERT INTO mothers SET name = ?, lastname = ?, nationalite = ?, adress = ?, job = ?");
        $req_mother->execute([ucwords(strtolower($_POST['mother_name'])), ucwords(strtolower($_POST['mother_lastname'])), ucwords(strtolower($_POST['mother_nat'])), ucwords(strtolower($_POST['mother_adr'])), ucwords(strtolower($_POST['mother_job']))]);

        $req = $pdo->query("SELECT * FROM mothers ORDER BY idmothers DESC LIMIT 1");
        $mother = $req->fetch();
        $motherid = $mother->idmothers;
      }

      // Enregistrement de la commune

      $ask_c = $pdo->prepare("SELECT * FROM commune WHERE commune = ?");
      $ask_c->execute([$_POST['birth_adr']]);
      $commune = $ask_c->fetch();
      if (!empty($commune)) {
        $communeid = $commune->idcommune;
      } else {
        $req_birth_adr = $pdo->prepare("INSERT INTO commune SET commune = ?");
        $req_birth_adr->execute([$_POST['birth_adr']]);

        $req = $pdo->query("SELECT * FROM commune ORDER BY idcommune DESC LIMIT 1");
        $commune = $req->fetch();
        $communeid = $commune->idcommune;
      }

      // Enregistrement de l'enfant

      $ask_e = $pdo->prepare("SELECT * FROM children WHERE name = ? AND lastname = ? AND birthday= ? AND sex = ? AND idfathers = ? AND idmothers = ?");
      $ask_e->execute([ucwords($_POST['child_name']),ucwords($_POST['child_lastname']),$_POST['child_birthday'],$_POST['child_sex'], $fatherid, $motherid]);
      $child = $ask_e->fetch();
      if (!empty($child)) {
        unset($_SESSION['print']);
        $errors[] = "Cet enfant a deja ete declare";
      } else {
        $req_reg = $pdo->prepare("INSERT INTO registers SET registers_number = ?, registers_date = ?, idcommune = ?");
        $req_reg->execute([$register_number, $register_date, $communeid]);

        $req = $pdo->query("SELECT * FROM registers ORDER BY idregisters DESC LIMIT 1");
        $registers = $req->fetch();

        $req_child = $pdo->prepare("INSERT INTO children SET name = ?, lastname = ?, birthday= ?, sex = ?, idfathers = ?, idmothers = ?, idregisters = ?");
        $req_child->execute([ucwords(strtolower($_POST['child_name'])),ucwords(strtolower($_POST['child_lastname'])),$_POST['child_birthday'],$_POST['child_sex'], $fatherid, $motherid, $registers->idregisters]);


        $req = $pdo->prepare("SELECT * FROM children WHERE name = ? AND lastname = ? AND birthday= ? AND sex = ? AND idfathers = ? AND idmothers = ? AND idregisters = ?");
        $req->execute([ucwords(strtolower($_POST['child_name'])),ucwords(strtolower($_POST['child_lastname'])),$_POST['child_birthday'],$_POST['child_sex'], $fatherid, $motherid, $registers->idregisters]);
        $children = $req->fetch();

        /* Conservation des donnee pour l'impression*/

        $_SESSION['print']['father'] = $father;
        $_SESSION['print']['mother'] = $mother;
        $_SESSION['print']['commune'] = $commune;
        $_SESSION['print']['register'] = $registers;
        $_SESSION['print']['child'] = $children;

        $a = date("Y");
        $m = date("m");
        $d = date("d");
        $date = "$a-$m-$d";

        $req_c = $pdo->prepare("SELECT * FROM onload WHERE onload_date = ? AND idauth = ?");
        $req_c->execute([$date, $_SESSION['auth']->idauth]);
        $verify = $req_c->fetch();
        if ($verify) {

          $counted = $verify->declared + 1;
          $count = $pdo->prepare("UPDATE onload SET declared = ? WHERE onload_date = ? AND idauth = ?");
          $count->execute([$counted, $date, $_SESSION['auth']->idauth]);

        } else {
          
          $counted =  1;
          $count = $pdo->prepare("INSERT INTO onload SET idauth = ?, declared = ?, onload_date = ?");
          $count->execute([$_SESSION['auth']->idauth, $counted, $date]);

        }

        $type = "Declaration";
        $req_g = $pdo->query("SELECT idchildren FROM children ORDER BY idchildren DESC LIMIT 1");
        $this_child = $req_g->fetch();
        $req = $pdo->prepare("INSERT INTO gestions SET type = ?, idauth = ?, idchildren = ?, date_gestion = NOW()");
        $req->execute([$type, $_SESSION['auth']->idauth, $this_child->idchildren]);

        header('Location: pdf/');

      }


    }

  }

?>

<div class="clear"></div>

<div class="container mainbg">
<br><a class="return" href="index.php"><i class="glyphicon glyphicon-arrow-left"></i> Retour</a>

    <h1 class="h1_title">Extrait de naissance</h1>
    <hr> <br>


    <div class="clear"></div>
    <div class="row col-md-10 col-md-offset-1">

      <form id="formID" action="" method="post">
          
              <label class="">Nom de l'enfant : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="child_name" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Prenom(s) de l'enfant : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="child_lastname" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Sexe de l'enfant : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                  <select name="child_sex" class="form-control">
                    <option value="none" selected>Selectionner</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                  </select>
              </div><br>

              <label class="">Date de naissance : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  <input name="child_birthday" type="date" placeholder="" class="form-control validate[required]">
              </div>

              <label class="">Lieu de naissance : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>

                  <?php 
                  require 'inc/communes.php';
                  ?>
                  <select name="birth_adr" class="form-control validate[required]">
                    <option value="none" selected>Communes</option>
                  <?php
                  foreach ($communes as  $commune) {
                    ?>
                    <option value="<?=$commune ?>"><?=$commune ?></option>
                    <?php
                  }?>
                  </select>
              </div><br>

              <label class="">Nom du père : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="father_name" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Prenom(s) du père : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="father_lastname" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Nationalité du père : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="father_nat" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Fonction du père : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                  <input name="father_job" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Domicile du père<span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                   <?php 
                  require 'inc/communes.php';
                  ?>
                  <select name="father_adr" class="form-control validate[required]">
                    <option value="none" selected>Communes</option>
                  <?php
                  foreach ($communes as  $commune) {
                    ?>
                    <option value="<?=$commune ?>"><?=$commune ?></option>
                    <?php
                  }?>
                  </select>
              </div><br>

              <label class="">Nom de la mère : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="mother_name" type="text" placeholder="" class="form-control validate[required]">
              </div><br>
    
              <label class="">Prenom(s) de la mère : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="mother_lastname" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Nationalité de la mère : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="mother_nat" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Fonction de la mère : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                  <input name="mother_job" type="text" placeholder="" class="form-control validate[required]">
              </div><br>

              <label class="">Domicile de la mère : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                   <?php 
                  require 'inc/communes.php';
                  ?>
                  <select name="mother_adr" class="form-control validate[required]">
                  <option value="none" selected>Communes</option>
                  <?php
                  foreach ($communes as  $commune) {
                    ?>
                    <option value="<?=$commune ?>"><?=$commune ?></option>
                    <?php
                  }?>
                  </select>
              </div><br>

          <button type="submit" id="submit" class="mybtn mybtn-success">Imprimer <i class="glyphicon glyphicon-print"></i></button>     

          <hr id="success">

      </form>
  
  </div>

<div class="clear"></div>

      <br>
     
</div>  
  
 <?php require 'inc/footer.php';?>

</body>
</html>