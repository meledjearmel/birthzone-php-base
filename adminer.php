<?php

 session_start();
 require_once 'functions.php';
 require_once 'db/db.php';

 if (!empty($_POST['auth']) && !empty($_POST['password'])) {
 	$req = $pdo->prepare("SELECT * FROM auth WHERE login = ?");
 	$req->execute([$_POST['auth']]);
 	$user = $req->fetch();
 	if ($user) {
 		if (password_verify($_POST['password'], $user->password)) {
 			$_SESSION['auth'] = $user;
 			header('Location: /birthzone/');
 		} else {
 			$_SESSION['alert'] = [
 				'message' => "Identifiant ou mot de passe incorrecte",
 				'type' => 'danger'
 			];
 		}
 		
 	} else {
 		$_SESSION['alert'] = [
 				'message' => "Identifiant ou mot de passe incorrecte",
 				'type' => 'danger'
 		];
 	}
 	
 }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Se connecter</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class="bloc">
		<div class="login">
			<h1>Indetifiez vous</h1>
			<form action="" method="post">
				<img class="img-ident" src="images/img-01.png">
				<br>
				<input type="text" name="auth" placeholder="Nom d'utilisateur" class="input-adminer"><br>
				<input type="password" name="password" placeholder="Mot de passe" class="input-adminer">
				<br>
				<button class="btn-adminer">Se connecter</button>
			</form>
		</div>
	</div>
</body>
</html>