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
  <h1>Serveur Web Pointage : Validation Pointage depuis ETAP (Bac a Sable)</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Validation Pointage depuis ETAP( Bac a Sable)");
	?>
  


<div class="article">
<div class="section">


<?php
    showTracePOST();
    
    echo "<p>
            Gestion Pointage Import CEGID provenant de ETAP (Bac a Sable).<br/><br/>
            [update] va mettre à jour la table de pointage à partir de la table d'import du bac à sable.<br/>
            le bac à sable est alimenté par Autre\Pointage Import.<br/>
    </p>";
	

	$multiselection = blockCondition("multiselection_pointage", "<h4>multi-selection [<value>]</h4>", false);
	$exec = applyNextPreviousSelectPointage();
	
	
	echo"<p>";
	global $SQL_TABLE_CEGID_POINTAGE;
	global $urlPointage;
	global $urlImportPointage;
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    LabelAction::ActionExport.",pointage;formaction='$urlPointage'",
	    "yes"/*user*/, "yes"/*previous*/, "yes"/*next*/,
	    $multiselection);
 	echo"<br/></p>";

 	
 	
 	$idBalise = "import_ETAP";
 	createHeaderBaliseDiv($idBalise, "<h3>Import depuis ETAP </h3>");
 	{
 	    global $SQL_TABLE_CEGID_POINTAGE_IMPORT;
 	$FORM_NAME_LOAD="form_load_ETAP";
 	$formName = getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT);
 	$res=0;
 	if ($formName == $FORM_NAME_LOAD) {
 	    showError("apply Pointage Brut CEGID table import : $SQL_TABLE_CEGID_POINTAGE_IMPORT");
 	    $res = applyPointageBrutCegid($SQL_TABLE_CEGID_POINTAGE_IMPORT);
 	}

 	$infoformLoad="<button   type=\"button\"><a href=\"#openModalTruncate\">".LabelAction::ActionTruncate."</a></button>";
 	
 	showLoadFile(
 	    ""/*url*/,""/*choose*/,""/*load*/,
 	    //array(LabelAction::ActionImport,"insert_update",LabelAction::ActionTruncate)/*action*/,
 	    array(LabelAction::ActionImport,"insert_update")/*action*/,
 	    $infoformLoad,""/*file size*/,$FORM_NAME_LOAD/*form name*/);
 	}
 	endHeaderBaliseDiv($idBalise);

 	
 	//Modal dialog pour le truncate
 	//attention on precise bien la table du truncate
 	$infoformTruncate = streamFormHidden(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT,$FORM_NAME_LOAD);
 	echoComment("$ infoformTruncate : $infoformTruncate" );
 	echo"
 	<div id=\"openModalTruncate\" class=\"modalDialog\">
 	<div>
 	<a href=\"#close\" title=\"Close\" class=\"close\">X</a>
 	<h2>Validation</h2>
 	<p>Est vous sure de vouloir vider la table d'import?</p>
 	<div align=center > <button type=\"button\">";
 	showMiniForm(""/*url*/, "form_validation_ETAP_truncate"/*form name*/, 
 	    LabelAction::ActionTruncate/*action*/, LabelAction::ActionTruncate/*label action*/, 
 	    ""/*id form*/, "no"/*useTD*/,$infoformTruncate/*infoForm*/);
 	echo" </button></div>
 	</div>
 	</div>";
 	
 	
 	//actions
 	if ($res<=0){
 	    $res = applyGestionOneProject();
 	}
 	if ($res<=0){
 		$res = applyGestionCoutOneProjectForm();
 	}
  	if ($res <=0){
  	    $res = applyGestionTablePointageProjetCegid($SQL_TABLE_CEGID_POINTAGE);
  	}
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
 	//showInsertTablePointageCegid(); 
 	//pour la resynchronisation
 	//showSynchronizePrevisionnel();
 	echo"<br>";
	
 	//legend
 	$txt =   "Legende : <br>
 	  - import sur vide                     : couleur bleu <br>
 	  - import valeur existante differente  : couleur rouge [pointage/import]<br>
 	  - import valeur equivalente           : couleur verte <br>
 	  - valeur hors import                  : fond gris <br>"
 	;
 	echo "".getActionMessage($txt,"#FFFFFF");
 	
 	
 	//show tableau fusion pointage & import
 	showTableImportPointageCegid();
	
 	$txt="[update] va mettre à jour la table de pointage à partir de la table d'import du bac à sable";
 	echo "".getActionMessage($txt,"#FFBBBB");
 	
	
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