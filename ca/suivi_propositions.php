<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Gestion projets </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/proposition_db.php");
	include_once("../sql/toolNextPrevious.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	testMemberGroup(2);
	include_once("../sql/cegid_file_db.php");// lien croisé avec tool_db.php
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>



<body>
<div id="header">
  <h1>Serveur Web Pointage : Suivi propositions</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Suivi propositions");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Suivi propositions.<br/></p>";
	showTracePOST();

	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    LabelAction::ActionExportCSV.",".LabelAction::ACTION_SYNCHRONIZE.",".LabelAction::ActionTruncate  /*export*/,
	    "no"/*user*/, "no"/*previous*/, "no"/*next*/);
	    echo"<p>";
	      echo"<strong>".LabelAction::ACTION_SYNCHRONIZE."</strong> : recalcule le CA pour l'annee donnee a partir celui de resp. affaires.<br/>";
	      echo"<strong>".LabelAction::ActionTruncate."</strong> : Efface les entr&eacute;s pour l'ann&eacute;e donn&eacute;e. Les entr&eacute;s sont recalcul&eacute;es automatiquement.<br/><br/>";
	    echo"</p>";
	    
	
	applyGestionProposition();
	
	//showLoadFile("","","","import");
	
	showSuiviPropositions();
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