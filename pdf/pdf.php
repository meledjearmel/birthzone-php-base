
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<table class="header" style="width: 100%">
		<tr class="first" style="width: 100%">
			<td  class="logo" style="width: 35%;">
				<div class="head1">
					<h3>COMMUNE <?php $de = (!preg_match("#^[aeiouy]#", $commune->commune)) ? "DE " : "D'";
echo "$de";
echo strtoupper($commune->commune)?></h3>
					<div class="img">
						<img src="embleme.png" width="100%" height="100%">
					</div>
					<h3>MAIRIE <?php $de = (!preg_match("#^[aeiouy]#", $commune->commune)) ? "DE " : "D' ";
echo "$de";
echo strtoupper($commune->commune)?></h3>
					<div class="dashed top"></div>
				</div>
			</td>
			<td style="width: 65%; ">
				<div class="head2">
					<h3>REPUBLIQUE DE COTE D'IVOIRE</h3>
					<p class="foot"><em>Union - Discipline - Travail</em></p>
					<div class="dashed"></div>
					<h1>EXTRAIT</h1>
					<p class="text">
						DU REGISTRE DES ACTES DE L'ETAT CIVIL POUR L'ANNEE: <strong><?php echo $year ?></strong>
					</p>
				</div>
			</td>
		</tr>

	</table>
	<table style="width: 100%">
		<tr class="second" style="width: 100%">
			<td>
				<div class="body1">
					<h1>CENTRE D'ETAT CIVIL</h1>
					<div class="dashed"></div>
					<p class="text">
						<strong>N° </strong>
						<?php echo "$register->registers_number" . " du " . "$registers_date" . "<br> du Registre" ?>
					</p>
					<div class="dashed"></div>
					<br><br>
					<h3>NAISSANCE DE :</h3>
					<p class="text">
						<?php echo strtoupper($child->name) . "<br>" ?>
						<?php echo $child->lastname ?>
					</p>
				</div>
			</td>
			<td width="500px">
				<div class="body2">
					<p class="text">
						<?php $mois = month($m);?>
						Le <?php echo "$jour" . " " . "$mois" . " " . "$annee"; ?> -----
					</p>
					<p class="text">
						<strong>est né(e)</strong> a la maternité <?php $de = (!preg_match("#^[aeiouy]#", $commune->commune)) ? "de " : "d'";
echo "$de";
echo $commune->commune?> -----

						<?php echo "<br>" . "$child->lastname" ?>
					</p>
					<p class="text">
						<?php $sex = ($child->sex == 'M') ? 'Fils' : 'Fille';
echo $sex;?> <strong>de</strong> <?php echo strtoupper($father->name) . " " . "$father->lastname" ?>, ---- <br>
						<?php echo "de nationalite " . "$father->nationalite" . "<br>" . "domicilié à " . "$father->adress"; ?>
						<?php echo "<br>Fonction : " . "$father->job"; ?>
					</p>
					<p class="text">
						<strong> et de</strong> <?php echo strtoupper($mother->name) . " " . "$mother->lastname" ?>, ---- <br>
						<?php echo "de nationalite " . "$mother->nationalite" . "<br>" . "domiciliée à " . "$mother->adress"; ?>
						<?php echo "<br>Fonction : " . "$mother->job"; ?>
					</p>
				</div>
			</td>
		</tr>
	</table>
	<hr style="margin-top: 30px;">
	<hr>
	<div class="forth">
		<h3>MENTIONS (éventuellement)</h3>
		<p class="text" style="margin-top: 30px">* * * * * NEANT * * * * *</p>
	</div>
	<div class="fith">
		<p class="notif">Certifié le présent extrait conforme aux indications portées au registre</p>
	</div>
	<table style="width: 100%">
		<tr class="sixth" style="width: 100%">
			<td>
				<div class="footer1">
					<p class="notif">Coller timbre</p>
				</div>
			</td>
			<td>
				<div class="footer2">
					<p class="foot"><strong>Délivré à <?php echo $commune->commune ?></strong>, le <?php $d = date('d');
$mt = date('m');
$mth = month($mt);
$y = date('Y');
echo "$d" . " " . "$mth" . " " . "$y"?> </p>
					<p class="foot"><strong>L'officier de l'Etat Civil</strong></p>
					<p class="sign"><strong>(signatutre)</strong></p>
				</div>
			</td>
		</tr>
	</table>
</body>
<script>
	window.print()
</script>
</html>
