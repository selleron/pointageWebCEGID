<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/project_db.php");
	include_once("../sql/cout_project_db.php");
	include_once("../sql/previsionnel_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	include_once("../js/form_db.js");   // affichage calebdrier pour saisie date 
  	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Gestion Pointage Import (Bac a Sable)</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Pointage Import  (Bac a Sable)");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion Pointage Import CEGID  (Bac a Sable).<br/></p>";
	
	showTracePOST();

	$exec = applyNextPreviousSelectPointage();
	
	
	echo"<p>";
	//global $URL_ROOT_POINTAGE;
	global $urlPointage;
	global $urlImportPointage;
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
 	LabelAction::ActionExport.",pointage;formaction='$urlPointage'",
 	"yes"/*user*/, "yes"/*previous*/, "yes"/*next*/);
 	echo"<br/></p>";

 	
 	//actions
 	$res = applyGestionOneProject();
 	if ($res<=0){
 		$res = applyGestionCoutOneProjectForm();
 	}
//  	if ($res <=0){
//  		$res = applyGestionPrevisionnelProjetCegid();
//  	}
//  	if ($res <=0){
//  		$res = applySynchronizePrevisionnel();
//  	}
 	
  	beginTable();
  	beginTableRow( getVAlign("top")  );
	  	beginTableCell();
	  	showGestionOneProject();
	  	endTableCell();
	  	beginTableCell();
			//showTableCoutOneProject();
			showTableCoutOneProjectImport();
	  	endTableCell();
  	endTableRow();
   	endTable();
 	
	
	//permet d'ajout un pointage pour un utilisateur
 	showInsertTablePointageCegid(); 
 	//pour la resynchronisation
 	showSynchronizePrevisionnel();
 	echo"<br>";
	
 	//show tableau fusion pointage & import
 	showTableImportPointageCegid();
	
	
	
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