<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Pointage</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Pointage Projet");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Pointage Projet CEGID.<br/></p>";
	
	showTracePOST();
	
	$exec = applyNextPreviousSelectPointage();
	
	applyGestionPointageProjetCegid(); 
	
	//actionGestionFile("../tmp/");
	echo "<p>";
	showLoadFile("","","","import");
	echo "<br/></p>";
	
	
	echo"<p>";
	//showProjectSelection();
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,"yes"/*export*/,"yes"/*user*/, "yes"/*previous*/, "yes"/*next*/);
	echo"<br/></p>";
	showInsertTablePointageCegid();
	echo"<br>";
	showTablePointageProjetCegid();
	
	
	
	
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