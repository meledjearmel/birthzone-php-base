

  <?php
 
    $title = "Demandes d'extrait";
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
    date_default_timezone_set('UTC');

    require_once 'functions.php';
    require_once 'inc/functions.php';

    $year = $month = $day = $annee = $mois = $jour = NULL;

    if (!empty($_POST)) {
      if (empty($_POST['name']) || strlen($_POST['name']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['name'])) {
      $errors[] = "Le nom est invalide";
      }

      if (empty($_POST['lastname']) || strlen($_POST['lastname']) < 3 || !preg_match("#[^0-9<>\*\+%,\{\}\[\]\|\(\)\/!\.]#", $_POST['lastname'])) {
         $errors[] = "Le prenom est invalide";
      }

      if (empty($_POST['birthday']) || !preg_match("#[0-9]{4}-[0-9]{2}-[0-9]{2}#",$_POST['birthday'])) {
        $errors[] = "La date de naissance est invalide";
      } else {
        $parts = explode('-', $_POST['birthday']);
        $jour = $parts[2];
        $mois = $parts[1];
        $annee = $parts[0];
        if ($annee > date("Y") || $mois > 12 || $jour > 31) {
          $errors[] = "La date de naissance est invalide";
        } else {
          if (!bissextile($annee)) {
            if ($mois == 2) {
              if ($jour > 29) {
                $errors[] = "La date de naissance est invalide";
              }
            }
          } else {
            if ($mois == 2) {
              if ($jour > 28) {
                $errors[] = "La date de naissance est invalide";
              }
            }
          
            }
          }
        }

      

      if (empty($_POST['register_number']) || !preg_match("#^[0-9]+$#", $_POST['register_number'])) {
        $errors[] = "Le numero du registre est invalide";
      }

      if (empty($_POST['nbr_copie']) || !preg_match("#[1-9]+$#", $_POST['register_number'])) {
        $errors[] = "Notifiez le nombre de copie";
      }

      if (empty($_POST['register_date']) || !preg_match("#[0-9]{4}-[0-9]{2}-[0-9]{2}#",$_POST['register_date'])) {
        $errors[] = "La date du registre est invalide";
      } else {
        $parts = explode('-', $_POST['register_date']);
        $day = $parts[2];
        $month = $parts[1];
        $year = $parts[0];
        if ($year > date("Y") || $month > 12 || $day > 31) {
          $errors[] = "La date du registre est invalide";
        } else {
          if (!bissextile($year)) {
            if ($month == 2) {
              if ($day > 29) {
                $errors[] = "La date du registre est invalide";
              }
            }
          } else {
            if ($month == 2) {
              if ($day > 28) {
                $errors[] = "La date du registre est invalide";
              }
            }
          
            }
          }
      }


      require_once 'db/db.php';
      
      if ($year != NULL && $month != NULL && $day != NULL && $annee != NULL && $mois != NULL && $jour != NULL) {
        $r_date = "$year-$month-$day";
        $b_date = "$annee-$mois-$jour";

        $req = $pdo->prepare("SELECT * FROM children WHERE name = ? AND lastname = ? AND birthday = ?");
        $req->execute([ucwords($_POST['name']), ucwords($_POST['lastname']), $b_date]);
        $child = $req->fetch();

        if (!$child) {
          $errors[] = "Vous n'etes pas encore déclaré(e) rendez vous a la mairie de votre commune";
        } else {
          $req = $pdo->prepare("SELECT * FROM registers WHERE registers_number = ? AND registers_date = ?");
          $req->execute([$_POST['register_number'], $r_date]);
          $registre = $req->fetch();
          if (!$registre) {
            $errors[] = "Vos informations sont éronnées";
          }
        }
      }
      
      if (empty($errors)) {

        if (!empty($registre)) {

          if (!empty($child)) {
            $y = date('Y');
            $m = date('m');
            $d = date('d');
            $date = "$y-$m-$d";

            if ($child->demand_copie == NULL) {

              $req = $pdo->prepare("UPDATE children SET demand_at = ?, demand_copie = ? WHERE idchildren = ?");
              $req->execute([$date, $_POST['nbr_copie'], $child->idchildren]);

            } else {

              $copie = $child->demand_copie + $_POST['nbr_copie'];
              $req = $pdo->prepare("UPDATE children SET demand_at = ?, demand_copie = ? WHERE idchildren = ?");
              $req->execute([$date, $copie, $child->idchildren]);

            }

          }
          $t_jour = date('d');
          $t_mois = date('m');
          $t_annee = date('Y');
          $n_jour = date('N');
          $n_hour = date('G');
          $today_date = "$t_annee-$t_mois-$t_jour";
          if ($n_jour <= 5) {

            if ($n_hour <= 13) {
              $message = "Votre demande a été pris en compte veuillez passer à <strong>16h</strong> pour le retrait";
            } else {
              $the_day = jour($n_jour+1);
              $message = "Votre demande a été pris en compte veuillez passer ce $the_day à <strong>10h</strong> pour le retrait";
            }
            
          } elseif ($n_jour == 6) {
              
              if ($n_hour <= 9) {
              $message = "Votre demande a été pris en compte veuillez passer à <strong>10h</strong> pour le retrait";
            } else {
              $date = strtotime("+2 day", $strtotime($today_date));
              $parties = explode("-", $date);
              $up_month = $parties[1];
              $up_day   = $parties[2];

              $message = "Votre demande a été pris en compte veuillez passer ce lundi $up_day $up_month à <strong>8h</strong> pour le retrait";
            }

          } else {
            $date = strtotime("+1 day", $strtotime($today_date));
            $parties = explode("-", $date);
            $up_month = $parties[1];
            $up_day   = $parties[2];

            $message = "Votre demande a été pris en compte veuillez passer ce lundi $up_day $up_month à <strong>8h</strong> pour le retrait";
          }
 
          $_SESSION['flash'] = [
            'message' => $message,
            'type' => 'success'
          ];

        }

      }

    }

    
    require 'inc/nav.php';
    require_once 'alert/alert.php';
    
   ?>

  <div class="clear"></div>

  <div class="container mainbg">
    <br><a class="return" href="index.php"><i class="glyphicon glyphicon-arrow-left"></i> Retour</a>

        <h1 class="h1_title">Demande d'actes de naissance</h1>
        <hr> <br>


        <div class="clear"></div>
        <div class="row col-md-10 col-md-offset-1">

          <form id="formID" action="" method="post">
              
                  <label class="">Nom : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input name="name" type="text" placeholder="" class="form-control validate[required]">
                  </div><br>

                   <label class="">Prenom : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                      <input name="lastname" type="text" placeholder="" class="form-control validate[required]">
                  </div><br>
                   
                  <label class="">Date de naissance : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                      <input name="birthday" type="date" placeholder="" class="form-control validate[required]">
                  </div>

                   <label class="">N° du registe : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                  <div class="input-group">
                      <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
                      <input name="register_number" type="text" placeholder="" class="form-control validate[required]">
                  </div><br>

                  <label class="">Date du registre : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input name="register_date" type="date" placeholder="" class="form-control validate[required]">
                </div>
                  <br>

                  <label class="">Nombre de copie : <span style="color:red; font-weight: bold; font-family: Arial, sans-serif ;">(*)</span></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    <input name="nbr_copie" type="number" placeholder="" class="form-control validate[required]">
                </div>
                  <br> 

              <button type="submit" id="submit" name="submit" class="mybtn mybtn-success">Demande</button>     

              <hr id="success">

          </form>
      
    </div>

    <div class="clear"></div>
  </div>
  <?php require 'inc/footer.php';?>
   
  </body>
</html>