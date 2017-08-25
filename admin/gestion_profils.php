<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> test </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
    include_once("../sql/profils_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Profils</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Profils");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion des profils CEGID.<br/></p>";

	applyGestionProfils(); 

	showLoadFile("","","","import");
		
	showTableProfils();
?>
<br/><br/><br/>

</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>