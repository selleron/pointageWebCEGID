<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Tests Graph </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Test Grpah</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Graph PHP 7.0 dans webcegid");
	?>
  


<div class="article">
<div class="section">

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

<p>

<img src="imageEvolutionCA.php" alt="image de pChart generee par imageEvolutionCA.php" />

</p>



</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>