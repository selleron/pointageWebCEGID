<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Table Requests </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
	include_once("../sql/requetes_db.php");
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Gestion Requêtes Génériques</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Table Requêtes Géneriques");
	?>
  


<div class="article">
<div class="section">


<?php
    showTracePOST();

    global $CONDITION_FROM_CEGID_NO_ARCHIVE;
    if (blockCondition("only_requetes_visible", "<h4>only requetes visible [<value>]</h4>")){
        $CONDITION_REQUEST = $CONDITION_FROM_CEGID_NO_ARCHIVE;
    }
    else{
        $CONDITION_REQUEST="";
    }
    
    
    echo "<br/>";
	actionRequete();
	echo "<br/><br/><br/>";
	echo "<p>Requetes enregistr&eacute;es : <br/></p>";
	showFormulaireRequete(""/*id request*/,""/*url*/,$CONDITION_REQUEST);
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