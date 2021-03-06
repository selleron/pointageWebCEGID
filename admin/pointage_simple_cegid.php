<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Pointage </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
	include_once("../sql/pointage_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
  	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Profils</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Pointage");
	?>
  


<div class="article">
<div class="section">


<?php
    showTracePOST();
    
    echo "<h4>Gestion Pointage CEGID BRUT.</h4>
    <p>Permet d'importer dans la table de pointage.<br/>
    Si possible utiliser le menu [Validation Import] pour importer des pointage.<br/>
    Le passage par une table intermediaire permet de vérifier les pointages.<br/>
    <br/>
    </p>";
	
	
	applyPointageBrutCegid(); 

	//showLoadFile("","","","import");
	//on veut pouvoir faire un insert ou un replace
	showLoadFile("","","",array("import","insert_update"));
	
	showTablePointageBrutCegid();
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