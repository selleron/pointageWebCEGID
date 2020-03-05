<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Gestion projets </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/project_db.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
	testMemberGroup(2);
	include_once("../sql/commande_presta_db.php");
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date
	?>
</head>


<body>
<div id="header">
  <h1>Serveur Web Pointage : Commandes Prestataires</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Commandes Prestataires");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion des Commandes Prestataires.<br/></p>";
	showTracePOST();
	
	global $CONDITION_FROM_CEGID_COMMANDE;
	global $CONDITION_USER_FROM_CEGID_COMMANDE;
	global $CONDITION_CMD_FROM_CEGID_COMMANDE;
	//$exec = applyNextPreviousSelectPointage();
	//synchoTableIdProject();
	
	echo"<p>";
    
	//global $urlPointage;
	//global $urlPrevision;
	
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	LabelAction::ActionExportCSV  /*export*/,
	"yes"/*user*/, "no"/*previous*/, "no"/*next*/);
	echo"<br/></p>";

	echo "<p>";
	$conditionVisible="";
	if (blockCondition("only_commande_visible", " [<value>] only commande visible<br/>")){
	    $conditionVisible = $CONDITION_FROM_CEGID_COMMANDE;
	}
	
	if (blockCondition("only_user_actif", " [<value>] only active user <br/>")){
	    if ($conditionVisible==""){
	        //nothing to do
	    }
	    else{
	        $conditionVisible = $conditionVisible.' AND ';
	    }
	    $conditionVisible = $conditionVisible.$CONDITION_USER_FROM_CEGID_COMMANDE;
	}
	
	if (blockCondition("only_cmd_active", " [<value>] only active commande <br/>")){
	    if ($conditionVisible==""){
	        //nothing to do
	    }
	    else{
	        $conditionVisible = $conditionVisible.' AND ';
	    }
	    $conditionVisible = $conditionVisible.$CONDITION_CMD_FROM_CEGID_COMMANDE;
	}
	echo "<br/></p>";
	
	
	applyGestionCommandePrestataire();

	echo"<p>";
    	showOnlyInsertTableCommandePrestataire();
	echo"<br/></p>";
	
	
	
	//gestion de l'import fichier
	echo "<p>";
	showLoadFile("","","",LabelAction::ActionImport );
	echo "<br/></p>";
	
	showTableCommandePresta( $conditionVisible );
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