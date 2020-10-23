<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Archives</title>
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
  <h1>Serveur Web Pointage : Archives</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Archives");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Archives<br/></p>";
	showTracePOST();
	
	//action
	applyGestionArchives();
	
	//boutons des actions
	$url="";
	$formname="archives";
	createForm ( $url, $formname );
	showFormHidden(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT, $formname);
	beginTable();
    	beginTableRow();
        	beginTableCell();
        	showFormSubmit("Archive Projects", LabelAction::ACTION_GET);
        	endTableCell();
            beginTableCell();
        	showFormSubmit("Unarchive Projects", LabelAction::ACTION_GET);
        	endTableCell();
    	endTableRow();
    	beginTableRow();
    	beginTableCell();
    	   showFormSubmit("Archive Devis",LabelAction::ACTION_GET);
        	endTableCell();
     	    beginTableCell();
          	showFormSubmit("Unarchive Devis", LabelAction::ACTION_GET);
        	endTableCell();
    	endTableRow();
    	beginTableRow();
    	beginTableCell();
    	showFormSubmit("Archive Users",LabelAction::ACTION_GET);
    	endTableCell();
    	beginTableCell();
    	showFormSubmit("Unarchive Users", LabelAction::ACTION_GET);
    	endTableCell();
    	endTableRow();
    	beginTableRow();
    	beginTableCell();
    	showFormSubmit("Archive Cmd Prestataires",LabelAction::ACTION_GET);
    	endTableCell();
    	beginTableCell();
    	showFormSubmit("Unarchive Cmd Prestataires", LabelAction::ACTION_GET);
    	endTableCell();
    	endTableRow();
    	endTable();
	endForm();
	
	echo "<br><br>";
	global $ID_REQUETE_SQL_PROJECTS;
	createHeaderBaliseDiv("ArchiveProjects","<h3>Projets</h3>");
	showTableRequeteCEGID( $ID_REQUETE_SQL_PROJECTS );
	endHeaderBaliseDiv("ArchiveProjects");

	echo "<br><br>";
	global $ID_REQUETE_SQL_DEVIS;
	createHeaderBaliseDiv("ArchiveDevis","<h3>Devis</h3>");
	showTableRequeteCEGID( $ID_REQUETE_SQL_DEVIS );
	endHeaderBaliseDiv("ArchiveDevis");
	
	echo "<br><br>";
	global $ID_REQUETE_SQL_USERS;
	createHeaderBaliseDiv("ArchiveUsers","<h3>Users</h3>");
	showTableRequeteCEGID( $ID_REQUETE_SQL_USERS );
	endHeaderBaliseDiv("ArchiveUsers");
	
	
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