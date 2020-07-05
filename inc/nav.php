<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
date_default_timezone_set('UTC');

require_once 'functions.php';
?>

<!DOCTYPE html>

<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.png">

    <title><?php if(isset($title)){echo $title;}else{echo "BirthZone";}?></title>

   <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme 
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">-->
  
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/Normalize.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">

    <script src="js/jquery-1.11.3.min.js"></script>
    <style type="text/css">
      .input-group-addon{
        padding-right: 24px;
      }
      .input-group{
        display: flex;
      }
      .button{
        outline: none;
      }
    </style>
    <?php
    if ($title == 'Administration' || $title == "Demandes d'extrait") {
      echo "<style>
            #footer{margin-top: 207px;}
            </style>
      ";
    } else {
      echo "<style>
            #footer{margin-top: 80px;}
            </style>
      ";
    }
    ?> 
        
  </head>
<body>

<div class="nav">

  <div class="navbar navbar-default navbar-fixed-top">
      
        <div class="container">
          
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">    
                        <span class="sr-only">Toggle navigation</span>      
                        <span class="icon-bar"></span>  
                        <span class="icon-bar"></span>  
                        <span class="icon-bar"></span>          
                </button> 

                <h2 class="h1_title"><a href="index.php"><i class="glyphicon glyphicon-home large"></i>
                  <?php 
                  if ($title == "Demandes d'extrait") {
                    echo 'BirthZone';
                  } else {
                    echo 'BirthZone';
                    if (!empty($_SESSION['auth'])) {
                    echo " (".$_SESSION['auth']->login.")";
                    }
                  } ?>
                </a></h2>

           </div>

            <div class="collapse navbar-collapse nav_right">
                                        
        <div class="btn-group">
        <h3 class='adminer' style="margin-top: 10px";>
          <?php
            if ($title != "Demandes d'extrait") {
              if (!empty($_SESSION['auth'])) {
              echo "<a href='logout.php'>Se deconnecter</a>";
              } else {
                echo "<a href='adminer.php'><i class='glyphicon glyphicon-user'></i> Se connecter</a>";
              }
            }
           ?>
        </h3
        >
        </div>

        

             </div>

        </div>

    </div>

</div>