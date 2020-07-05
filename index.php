


  <?php
  $title = 'Administration';
  require 'inc/nav.php';

  if (!empty($_SESSION['auth'])) {
    require_once 'db/db.php';
    
    $a = date("Y");
    $m = date("m");
    $d = date("d");
    $date = "$a-$m-$d";
    $req = $pdo->prepare("SELECT * FROM onload WHERE idauth = ? AND onload_date = ?");
    $req->execute([$_SESSION['auth']->idauth, $date]);
    $count = $req->fetch();


  }

  ?>

  <div class="clear"></div>


  <div class="container main" style="margin-top: 100px;">



  <div class="row">

  <div class="col-md-12" id="status">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="info-box yellow-bg">
              <i class="fa fa-trademark"></i>
              <div class="count">
                <?php
                if (!empty($_SESSION['auth'])) {
                  if ($count) {
                    if ($count->declared == NULL) {
                      echo "0";
                    } else {
                      echo $count->declared;
                    }
                  } else {
                    echo "0";
                  }
                } else {
                  echo "NULL";
                }
                ?>
              </div>
              <div class="title">Declaration éffectuée sur 24H</div>           
            </div><!--/.info-box-->     
          </div><!--/.col-->
          
          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="info-box orange-bg">
              <i class="fa fa-contao"></i>
              <div class="count">
                <?php
                  if (!empty($_SESSION['auth'])) {
                    if ($count) {
                      if ($count->restaured == NULL) {
                        echo "0";
                      } else {
                        echo $count->restaured;
                      }
                    } else {
                      echo "0";
                    }
                  } else {
                    echo "NULL";
                  }
                ?>
              </div>
              <div class="title">Restitution éffectuée sur 24H</div>            
            </div><!--/.info-box-->     
          </div><!--/.col-->  
          
         
          </div>
           <div class="clear"></div><br>

               <div class="col-md-4">
                <a href="birth_declaration.php">
                    <div class="link">
                      <i class="fa fa-plus"></i>
                      <div class="clear"></div>
                      <span>Déclaration de naissance</span>
                   </div>
                </a>
              </div>
              
               <div class="col-md-4">
                <a href="restaure_acte.php">
                    <div class="link">
                      <i class="fa fa-user"></i>
                      <div class="clear"></div>
                      <span>Restitution d'actes de naissance</span>
                   </div>
                </a>
              </div>
              
               <div class="col-md-4">
                <a href="gestion.php">
                    <div class="link">
                      <i class="fa fa-cog"></i>
                      <div class="clear"></div><span>Donnée(s) Traitée(s)</span>
                   </div>
                </a>
              </div>
              

          </div>
          </div>    
                             

  <?php require 'inc/footer.php';?>               

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>



  


</body></html>