<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Recapitulatif Projet </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/previsionnel_user_cegid.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	include_once("../js/form_db.js");   // affichage calebdrier pour saisie date 
  	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Gestion Pointage Previsionnel Utilisateurs</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Pointage Previsionnel Par Projet");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion Pointage Previsionnel Par Projet CEGID.<br/></p>";
	
	showTracePOST();

	$multiselection = blockCondition("multiselection_pointage", "<h4>multi-selection [<value>]</h4>", false);
	//$exec = applyNextPreviousSelectUser();
	$exec = applyNextPreviousSelectPointage();
	
	
	echo"<p>";
	//global $URL_ROOT_POINTAGE;
	global $urlPointage;
	global $urlPrevision;
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    "pointage;formaction='$urlPointage',previsionel;formaction='$urlPrevision',previsionel collaborateurs;formaction='$urlPrevisionCollaborateur'",
	    "yes"/*user*/, "yes"/*previous*/, "yes"/*next*/,
	    $multiselection);
	echo"<br/>Next Previous sur le projet <br/></p>";

 	
 	//actions
 	$res = -1;
 	//showAction("applyGestionOneProject");
 	$res = applyGestionOneProject();
 	if ($res<=0){
 	    //showAction("applyGestionCoutOneProjectForm");
 	    $res = applyGestionCoutOneProjectForm();
 	}
 	if ($res <=0){
 	    //showAction("applyGestionPrevisionnelUserCegid");
 	    //$res = applyGestionPrevisionnelProjetCegid();
 	    $res = applyGestionPrevisionnelUserCegid();
 	}
 	if ($res <=0){
 	    //showAction("applySynchronizePrevisionnel");
 	    $res = applySynchronizePrevisionnel();
 	}
 	
 	
	
	//permet d'ajout un pointage pour un utilisateur
 	//showInsertTablePointageCegid(); 
 	//pour la resynchronisation
 	//showSynchronizePrevisionnel();
 	echo"<br>";
	
 	//show prevision
 	showTablePrevisionnelByProjectCegid("no");
	
	
	
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