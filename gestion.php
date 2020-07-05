<?php 
  $title = 'Operation effectuee';
  require 'inc/nav.php';
  if (empty($_SESSION['auth'])) {
    $_SESSION['alert'] = [
      'type' => 'danger',
      'message' => 'Veuillez vous connectez'
    ];
    header('Location: adminer.php');
    
  }


  require_once 'functions.php';
  require_once 'inc/functions.php';
  require_once 'db/db.php';
?>

<div class="clear"></div>

<div class="container mainbg">
<br><a class="return" href="index.php"><i class="glyphicon glyphicon-arrow-left"></i> Retour</a>

    <h1 class="h1_title">Opération(s) effectuée(s)</h1>
    <hr> <br>


    <div class="clear"></div>
    <div class="row col-md-10 col-md-offset-1">

<ul class="bgwhite nav nav-tabs text-capitalize" role="tablist" style="background-color:#fff; text-justify:!important; color:#FFF;">

        <li class="nav-item active">
          <a class="nav-link active" data-toggle="tab" href="#declaration" role="tab">Les declarations effectuees</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#restitution" role="tab">Les restitutions effectuees</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#operation" role="tab">Toutes les operations</a>
        </li>
        
</ul>
  <!------------------------------------les tab 1------------------------------------------------------------------------->    
 <div class="tab-content" role="tablist">
        

      <!--------------------------------------------Declarations---------------------------------------------------------------------->
         <div class="tab-pane active" id="declaration" role="tabpanel">
           <br>
           <br>
           <form>
              <div class="input-group">
                  <button class="button btn input-group-addon"><i class="glyphicon glyphicon-search"></i></button>
                  <input name="searching" type="text" placeholder="Rechercher..." class="form-control validate[required]">
                  </button>
               </div><br>
        </form>
        
          <table class="table table-striped table-bordered">
            <tbody>
              <tr class="tr-table">
                <th>Nom</th>
                <th>Prenom</th>
                <th>Sexe</th>
                <th>Date de naissance</th>
                <th>N° registre</th>
                <th>Date du registre</th>
                <th>Date de l'operation</th>
              </tr>
              <?php 
                $declare = "Declaration";
                $req = $pdo->prepare("SELECT * FROM gestions WHERE type = ? ORDER BY date_gestion DESC LIMIT 0, 50");
                $req->execute([$declare]);
                $declaration = $req->fetchAll();
                

                foreach ($declaration as $key => $value) {
                  $req = $pdo->prepare("SELECT * FROM children WHERE idchildren = ?");
                  $req->execute([$declaration[$key]->idchildren]);
                  $child = $req->fetch();

                  $req = $pdo->prepare("SELECT * FROM registers WHERE idregisters = ?");
                  $req->execute([$child->idregisters, ]);
                  $register = $req->fetch();

                ?>
                
                <tr>
                  <td><?=$child->name?></td>
                  <td><?=$child->lastname?></td>
                  <td><?php $sex = ($child->sex == 'M') ? 'Masculin' : 'Feminin' ; echo $sex; ?></td>
                  <td><?=$child->birthday?></td>
                  <td><?=$register->registers_number?></td>
                  <td><?=$register->registers_date?></td>
                 
                  <td><?=$declaration[$key]->date_gestion?></td>
                </tr>

                <?php

                }
                
               ?>
            </tbody>
        </table>
        </div>

         <!--------------------------------------------Restitutions---------------------------------------------------------------------->
         <div class="tab-pane" id="restitution" role="tabpanel">
            <br>
            <br>
            <form>
              <div class="input-group">
                  <button class="button btn input-group-addon"><i class="glyphicon glyphicon-search"></i></button>
                  <input name="search_gestion" type="text" placeholder="Rechercher..." class="form-control validate[required]">
               </div><br>
            </form>
          <table class="table table-striped table-bordered">
            <tbody><tr class="tr-table">
              <th>Nom</th>
              <th>Prenom</th>
              <th>Sexe</th>
              <th>Date de naissance</th>
              <th>N° registre</th>
              <th>Date du registre</th>
              <th>Date de l'operation</th>
            </tr>
            <?php 
                $restaure = "Restauration";
                $req = $pdo->prepare("SELECT * FROM gestions WHERE type = ?  ORDER BY date_gestion DESC LIMIT 0, 50");
                $req->execute([$restaure]);
                $restauration = $req->fetchAll();

                foreach ($restauration as $key => $value) {
                  $req = $pdo->prepare("SELECT * FROM children WHERE idchildren = ?");
                  $req->execute([$restauration[$key]->idchildren]);
                  $child = $req->fetch();

                  $req = $pdo->prepare("SELECT * FROM registers WHERE idregisters = ?");
                  $req->execute([$child->idregisters, ]);
                  $register = $req->fetch();

                ?>
                
                <tr>
                  <td><?=$child->name?></td>
                  <td><?=$child->lastname?></td>
                  <td><?php $sex = ($child->sex == 'M') ? 'Masculin' : 'Feminin' ; echo $sex; ?></td>
                  <td><?=$child->birthday?></td>
                  <td><?=$register->registers_number?></td>
                  <td><?=$register->registers_date?></td>
                 
                  <td><?=$restauration[$key]->date_gestion?></td>
                </tr>

                <?php

                }
                
               ?>
          </tbody>
        </table>
        </div>
 <!--------------------------------------Toutes operattion-------------------------------------------------------------->
        <div class="tab-pane" id="operation" role="tabpanel">
          <br>
          <br>
          <form>
            <div class="input-group">
                <button class="button btn input-group-addon"><i class="glyphicon glyphicon-search"></i></button>
                <input name="examen" type="text" placeholder="Rechercher..." class="form-control validate[required]">
             </div><br>
          </form>
        
          <table class="table table-striped table-bordered">
            <tbody>
              <tr class="tr-table">
                <th>Nom</th>
                <th>Prenom</th>
                <th>Sexe</th>
                <th>Date de naissance</th>
                <th>N° registre</th>
                <th>Date du registre</th>
                <th colspan="2">Operation</th>
              </tr>
              <?php 
                $req = $pdo->query("SELECT * FROM gestions ORDER BY date_gestion DESC LIMIT 0, 50");
                $all = $req->fetchAll();
                

                foreach ($all as $key => $value) {
                  $req = $pdo->prepare("SELECT * FROM children WHERE idchildren = ?");
                  $req->execute([$all[$key]->idchildren]);
                  $child = $req->fetch();

                  $req = $pdo->prepare("SELECT * FROM registers WHERE idregisters = ?");
                  $req->execute([$child->idregisters, ]);
                  $register = $req->fetch();

                ?>
                
                <tr>
                  <td><?=$child->name?></td>
                  <td><?=$child->lastname?></td>
                  <td><?php $sex = ($child->sex == 'M') ? 'Masculin' : 'Feminin' ; echo $sex; ?></td>
                  <td><?=$child->birthday?></td>
                  <td><?=$register->registers_number?></td>
                  <td><?=$register->registers_date?></td>
                 
                  <td><?=$all[$key]->type?></td>
                </tr>

                <?php

                }
                
               ?>
          </tbody>
        </table>
        </div>
        
     </div>
   </div>
    
        <!--------------------------------------------------- fin tab---------------------------------------------------------------->
<div class="clear"></div>
 <br>
        

</div>  
        
<?php require 'inc/footer.php'?>

 <script src="js/bootstrap.min.js"></script>          
<script src="js/popper.min.js"></script>
<script src="js/jquery-slim.min.js"></script>
<script src="js/tab.js"></script>
<script src="js/util.js"></script>


  

</body></html>