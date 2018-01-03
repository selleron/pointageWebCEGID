<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web Pointage.</title>
  <?PHP 
  include("./header.php");  
  //include("./sql/document_db.php");  
  include("sql/connection_db.php"); 
  ?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage</h1>
</div>

<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Web CEGID");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion Web CEGID.<br/></p>";
	showTracePOST();
 	 
     //echo getDocumentAccueil(); 
     echo "<p>to do</p>";
     ?>
<br/><br/><br/>

</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->
     
  <?PHP 
    include("./menu.php"); 
    include("sql/deconnection_db.php"); 
    include("./footer.php"); 
  ?> 

</body>
</html>
