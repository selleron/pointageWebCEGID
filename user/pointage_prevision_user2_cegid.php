<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
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
	showBandeauHeaderPage("Gestion Pointage Previsionnel Utilisateurs");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion Pointage Previsionnel Utilisateurs CEGID.<br/></p>";
	
	showTracePOST();

	$exec = applyNextPreviousSelectUser();
	
	
	echo"<p>";
	global $URL_ROOT_POINTAGE;
	$urlPointage = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
	$urlPrevision = "$URL_ROOT_POINTAGE/user/pointage_prevision_cegid.php";
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    "pointage;formaction='$urlPointage',previsionel;formaction='$urlPrevision'",
	    "yes"/*user*/, "yes"/*previous*/, "yes"/*next*/);
	echo"<br/></p>";

 	
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
 	
//   	beginTable();
//   	beginTableRow( getVAlign("top")  );
// 	  	beginTableCell();
// 	  	    showGestionOneProject();
// 	  	endTableCell();
// 	  	beginTableCell();
// 			showTableCoutOneProjectPrevisionel();
// 	  	endTableCell();
//   	endTableRow();
//    	endTable();
 	
	
	//permet d'ajout un pointage pour un utilisateur
 	showInsertTablePointageCegid(); 
 	//pour la resynchronisation
 	showSynchronizePrevisionnel();
 	echo"<br>";
	
 	//show prevision
 	showTablePrevisionnelByUserPointageCegid("no");
	
	
	
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