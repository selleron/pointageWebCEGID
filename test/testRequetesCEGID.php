<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Requests </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
    include_once("../sql/tool_db.php");
    include_once("../sql/requetes_db.php");
    include_once("../sql/pointage_cegid_db.php");
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : CEGID Requests</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Requests CEGID");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<br/>";

	echo "<p>CA Actuel Clos<br/></p>";
	showTracePOST();
	
	echo"<p>";
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,LabelAction::ActionExportCSV/*export*/,"no"/*user*/, "no"/*previous*/, "no"/*next*/);
	echo"<br/></p>";
	
	
	
	//ici on change la table par defaut requetes => requetes_cegid
	global $SQL_TABLE_REQUETES;
	$SQL_TABLE_REQUETES="cegid_requetes";
	
	//traitement des actions
	//createHeaderBaliseDiv("ActionRequeteCEGID","<h3>Resultat requete</h3>");
	actionRequete();
	//endHeaderBaliseDiv("ActionRequeteCEGID");
	echo "<br/><br/><br/>";
	//echo "<p>Requetes enregistr&eacute;es : <br/></p>";
	showFormulaireRequete();
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