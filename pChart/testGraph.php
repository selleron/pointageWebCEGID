<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<title>Serveur Web MobilHome200.</title>
<?PHP include("../header.php"); ?>
<?PHP include("../sql/document_db.php"); ?>
<?PHP include("../sql/connection_db.php"); ?>
</head>

<body>
	<div id="header">
		<h1>Serveur Web MobilHome200</h1>
	</div>

	<div id="contenu">

	<?PHP 
	showBandeauHeaderPage("Test Graphiques Dates....");
	?>
		
		<div class="article">
			<?php 
			$gd_info = gd_info();
			if(!$gd_info){
				echo("<br />La librairie GD n'est pas install&eacute;e ! <br />
				Ligne &agrave; ajouter dans le php.ini<br /> <strong>extension=php_gd2.dll</strong> <br />");
			}
			?>
		</div>
		<!-- article -->

		<div class="article">
			
			<img src="graphTest.php" alt="" />
			
		</div>
		<!-- article -->
	</div>
	<!-- contenu -->

	<?PHP include("../menu.php"); ?>
	<?PHP include("../sql/deconnection_db.php"); ?>
	<?PHP include("../footer.php"); ?>

</body>
</html>



