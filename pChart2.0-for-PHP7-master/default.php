<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
<title>Serveur Web MobilHome200.</title>
<?PHP include("../header.php"); ?>
<?PHP include("../sql/member_db.php"); ?>
<?PHP include("../sql/connection_db.php"); ?>
</head>

<body>
	<div id="header">
		<h1>Serveur Web Cegid</h1>
	</div>

	<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Tests graphiques pour PHP 7...");
	?>
	
		<div class="article">
			<?php   
			echo "Vous avez PHP <a href=\"../phpinfo.php\">".phpversion()." </a>";
			?>

			<p>
				<br /> Si vous n'avez pas d'information sur GD<br /> Ligne &agrave; ajouter
				dans le php.ini<br /> <strong>extension=php_gd2.dll</strong> <br />
			</p>

			<?php 
			$gd_info = gd_info();
			if(!$gd_info){
				echo("<br />La librairie GD n'est pas install&eacute;e !");
			}
			else{
				echo "<br />Vous avez GD {$gd_info['GD Version']}<br/><br/>";
			}
			?>
		</div>
		<!-- article -->

		<div class="article">
		<br />
		<?php 
			global $ACTION_GET;
			$id=getMemberID();
			$argument=propagateArguments();
		
		echo"<a href=\"./examples/index.php$argument\"> 	examples			</a> <br />";
	
		
		
		
		
		
		?>
		</div>
		<!-- article -->
	</div>
	<!-- contenu -->

	<?PHP include("../menu.php"); ?>
	<?PHP include("../sql/deconnection_db.php"); ?>
	<?PHP include("../footer.php"); ?>

</body>
</html>



