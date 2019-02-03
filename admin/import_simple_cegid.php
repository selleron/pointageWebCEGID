<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Previsionnel </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/previsionnel_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Import (Bac a Sable)</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Pointage Import (bac à sable)");
	?>
  


<div class="article">
<div class="section">


<?php
    showTracePOST();
    echo "<h4>Gestion Pointage CEGID Import.</h4><p>Permet d'importer dans la table de pointage.<br> => validation avant report dans le pointage.<br><br></p>";
	global $SQL_TABLE_CEGID_POINTAGE_IMPORT;
		
	applyPointageBrutCegid($SQL_TABLE_CEGID_POINTAGE_IMPORT); 

	showLoadFile("","","",array("import","insert_update"));
	
	showTablePointageBrutCegid($SQL_TABLE_CEGID_POINTAGE_IMPORT);
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