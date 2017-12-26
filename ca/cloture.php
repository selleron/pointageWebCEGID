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
	
	
	applyGestionCloture();
	global $ID_REQUETE_SQL_CHECK_PRIX_VENTE;
	global $ID_REQUETE_SQL_PRIX_VENTE;
	showTableCAPrevisionel( $ID_REQUETE_SQL_CHECK_PRIX_VENTE );
	showTableCAPrevisionel( $ID_REQUETE_SQL_PRIX_VENTE );
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