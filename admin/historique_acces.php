<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> Acc&egrave;s </title>
  <?PHP 
	include("../sql/connection_db.php");
	include("../sql/member_db.php");
	testMemberGroup(2);
    include("../header.php");
	include("../sql/access_site.php");
  ?>
</head>

<body>
<div id="header">
  <h1>Serveur Web CEGID : Acc&egrave;s</h1>
</div>


<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Historique Acc&egrave;s Serveur CEGID");
	?>



<div class="article">
<div class="section">


<?php
	echo "<p>Historique acc&egrave;s du site Web.<br/></p>";
	showTableAccess();
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