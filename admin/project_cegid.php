<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	//include_once("../sql/project_cout_db.php");// lien croisé avec tool_db.php
	include_once("../sql/cout_project_db.php");// lien croisé avec tool_db.php
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Project</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Projet");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Projet CEGID.<br/></p>";
	showTracePOST();
	
	showProjectSelection(""/*url*/,""/*form*/,"no"/*year*/);

	//gestion cout
	$res = applyGestionCoutOneProjectForm();
	showTableCoutOneProject();
	
	//pointage
	if ($res <= 0){
		$res = applyGestionPointageProjetCegid();
	}
	//showTableProjectCoutCegid();
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