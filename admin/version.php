<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Web Version </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/version_db.php");
	include_once("../sql/member_db.php");// lien crois� avec tool_db.php
	?>
</head>


<body>
<div id="header">
  <h1>Serveur Web Pointage : Version</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Version Pointage");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Version des &eacute;l&eacute;ments du site Web.<br/></p>";
	showTracePOST();
	
	showSummaryTableVersion();
	echo "<br/><br/><br/>";
	echo "<p>D&eacute;tails<br/></p>";
	showTableVersion();
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