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
	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<?PHP 		testMemberGroup(2); ?>


<body>
<div id="header">
  <h1>Serveur Web Pointage : Project</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Project");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion des projects CEGID.<br/></p>";
	showTracePOST();
	
	$exec = applyNextPreviousSelectPointage();
	synchoTableIdProject();
	
	echo"<p>";
    
	//global $URL_ROOT_POINTAGE;
	global $urlPointage;
	global $urlPrevision;
	global $FORM_TABLE_CEGID_PROJECT;
	$form_name = ""; //$FORM_TABLE_CEGID_PROJECT . "_insert";
	
	showProjectSelection(""/*url*/,"$form_name"/*form*/,"no"/*year*/,
	    "pointage;formaction='$urlPointage',previsionel;formaction='$urlPrevision'"/*export*/,
	    "no"/*user*/, "yes"/*previous*/, "yes"/*next*/);
	echo"<br/></p>";
	
	
	
	applyGestionProject();
		
	echo "<p>";
	showLoadFile("","","","import");
	echo "<br/></p>";
	
	
	showTableProject();
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