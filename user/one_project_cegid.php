<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/project_db.php");
	include_once("../sql/frais_mission_db.php");
	include_once("../sql/cout_project_db.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	include_once("../js/form_db.js");   // affichage calebdrier pour saisie date 
  	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Gestion d'un projet</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion d'un projet");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion d'un projet CEGID.<br/></p>";
	
	showTracePOST();
	
	$multiselection = blockCondition("multiselection_pointage", "<h4>multi-selection [<value>]</h4>", false);
	$exec = applyNextPreviousSelectPointage();
	
	
	echo"<p>";
	//global $URL_ROOT_POINTAGE;
	global $urlPointage;
	global $urlPrevision;
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    "previsionel;formaction='$urlPrevision', prev. projet;formaction='$urlPrevisionProjet'  ",
	    "yes"/*user*/, "yes"/*previous*/, "yes"/*next*/,
	    $multiselection);
 	echo"<br/></p>";

 	
 	//application des actions
 	//info du projet
 	$res = applyGestionOneProject();
 	if ($res<=0){
 	    //gestion cout projet
 	    $res = applyGestionCoutOneProjectForm();
 	}
 	if ($res<=0){
 	    //gestion frais mission
 	    $res = applyGestionFraisMissionOneProjectForm();
 	}
 	if ($res <=0){
 	    //gestion pointage
 		applyGestionPointageProjetCegid();
 	}
 	
 	//affichages
  	beginTable();
  	beginTableRow( getVAlign("top")  );
	  	beginTableCell();
	  		showGestionOneProject();
	  	endTableCell();
	  	beginTableCell();
			showTableCoutOneProject();
			showTableFraisMissionOneProject();
	  	endTableCell();
  	endTableRow();
   	endTable();
 	
	
// 	echo "<p>";
// 	showLoadFile("","","","import");
// 	echo "<br/></p>";
	
 	echo"<br/></p>";
 	showInsertTablePointageCegid();
 	echo"<br>";
 	
 	
	showTablePointageOneProjetCegid();
	
	
	
	
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