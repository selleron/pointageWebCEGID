<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Gestion projets </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	include_once("../js/form_db.js");   // affichage calebdrier pour saisie date
  	include_once("../sql/frais_mission_db.php");
  	?>
</head>

  <?PHP 
	testMemberGroup(2); 
	?>
	


<body>
<div id="header">
  <h1>Serveur Web Pointage : Frais Missions Projets</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Frais Missions Projets");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion des frais de mission des projets CEGID.<br/></p>";
	showTracePOST();
	
	$exec = applyNextPreviousSelectPointage();
	
	
	echo"<p>";
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,"no"/*export*/,"no"/*user*/, "yes"/*previous*/, "yes"/*next*/);
	echo"<br/></p>";
	
	
	applyGestionFraisMssionForm();
	
	echo "<p>";
	showLoadFile("","","","import");
	echo "<br/></p>";
	
	showTableFraisMissionOneProject("", "no"/*showOnlyOneProject*/);
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