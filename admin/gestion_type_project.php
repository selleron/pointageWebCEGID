<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Status Commande </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
    include_once("../sql/status_cegid_db.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<?PHP 		testMemberGroup(2); ?>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Gestion Type Projet </h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Type Projet");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion Type de Projets CEGID.<br/></p>";
	showTracePOST();
	
	applyGestionTypeProject(); 

	showLoadFile("","","","import");
		
	showTableTypeProject();
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