<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Cloture</title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/ca_previsionel_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	include_once("../sql/cegid_file_db.php");// lien croisé avec tool_db.php
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<?PHP 		testMemberGroup(3); ?>


<body>
<div id="header">
  <h1>Serveur Web Pointage : Cloture</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Cloture");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Cloture<br/></p>";
	showTracePOST();
	
	echo"<p>";
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,LabelAction::ActionExportCSV.",cloture"/*export*/,"no"/*user*/, "no"/*previous*/, "no"/*next*/);
	echo"<br/></p>";
	
	//action
	applyGestionCloture();
	
	//boutons des actions
	$url="";
	$formname="cloture";
	createForm ( $url, $formname );
	showFormHidden(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT, $formname);
	showFormSubmit(LabelAction::ActionExportCSV, LabelAction::ACTION_GET);
	showFormSubmit("sauvegarde cout",LabelAction::ACTION_GET);
	showFormSubmit("restore cout",LabelAction::ACTION_GET);
	endForm();
	
	//UO reportable
	echo "<br><br>";
	UOReportable();
	
	//Check Prix de Vente
	global $ID_REQUETE_SQL_CHECK_PRIX_VENTE;
	echo "<br><br>";
	createHeaderBaliseDiv("CheckPrixVente","<h3>Check Prix de Vente et Cout</h3>");
	showDescriptionRequeteCEGID($ID_REQUETE_SQL_CHECK_PRIX_VENTE);
	showTableRequeteCEGID( $ID_REQUETE_SQL_CHECK_PRIX_VENTE );
	endHeaderBaliseDiv("CheckPrixVente");

	
	// PRIX de Vente
	global $ID_REQUETE_SQL_PRIX_VENTE;
	echo "<br><br>";
	createHeaderBaliseDiv("PrixVente","<h3>Prix de Vente et CA prévisionnel</h3>");
	showDescriptionRequeteCEGID($ID_REQUETE_SQL_PRIX_VENTE);
	showTableRequeteCEGID( $ID_REQUETE_SQL_PRIX_VENTE );
	endHeaderBaliseDiv("PrixVente");
	
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