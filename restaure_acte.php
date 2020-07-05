<?php 

    $title = "Restitution d'actes";
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

    <h1 class="h1_title">Restitution d'actes de naissances</h1>
    <hr> <br>


    <div class="clear"></div>
    <div class="row col-md-10 col-md-offset-1">
 
 <div class="tab-content" role="tablist">
        

      <!--------------------------------------------Demandes de restitution---------------------------------------------------------------------->
             <br>
             <form>
                <div class="input-group">
                    <button class="button btn input-group-addon"><i class="glyphicon glyphicon-search"></i></button>
                    <input name="search_demande" type="text" placeholder="Rechercher une demande" class="form-control validate[required]">
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
              <th>Nombre de copie</th>
              <th>Date de demande</th>
              <th colspan="2">Operation</th>
            </tr>


            <?php
              $req = $pdo->query("SELECT name, lastname, sex, demand_at, demand_copie, birthday, idregisters FROM children WHERE demand_at IS NOT NULL ORDER BY demand_at DESC LIMIT 0, 50");
              $childs = $req->fetchAll();
              $children[] = $childs;
              
              foreach ($children as $key => $child) {
                foreach ($child as $k => $value) {
                $req = $pdo->prepare("SELECT registers_number, registers_date FROM registers WHERE idregisters = ?");
                $req->execute([$child[$k]->idregisters]);
                $id_r = $req->fetch();
              ?>
                <tr style="vertical-align: middle;">
                  <td><?=$child[$k]->name?></td>
                  <td><?=$child[$k]->lastname?></td>
                  <td><?php $sex = ($child[$k]->sex == 'M') ? "Masculin" : "Feminin" ; echo $sex; ?></td>
                  <td><?=$child[$k]->birthday?></td>
                  <td><?=$id_r->registers_number?></td>
                  <td><?=$id_r->registers_date?></td>
                  <td><?=$child[$k]->demand_copie?></td>
                  <td><?=$child[$k]->demand_at?></td>
                  <td><a href="inc/redirect.php?name=<?=$child[$k]->name?>&lastname=<?=$child[$k]->lastname?>&sex=<?=$child[$k]->sex?>&birthday=<?=$child[$k]->birthday?>&register_num=<?=$id_r->registers_number?>&register_date=<?=$id_r->registers_date?>&copie=<?=$child[$k]->demand_copie?>" target="blank"><span class="btn btn-primary" alt=""><i class="glyphicon glyphicon-print large" style="font-size:18px; color:#FFF"></i>  Imprimer</span></a></td> 
                
                </tr>
                <?php
                
                }
              } 
              ?>
             
        </tbody>
      </table>
    </div>
  <div class="clear"></div>
   <br>
          

  </div>
</div> 
        
<?php require 'inc/footer.php'?>

 <script src="js/bootstrap.min.js"></script>          
<script src="js/popper.min.js"></script>
<script src="js/jquery-slim.min.js"></script>
<script src="js/tab.js"></script>
<script src="js/util.js"></script>


  

</body></html>