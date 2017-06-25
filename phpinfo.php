<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web MobilHome200.</title>
  <?PHP include("./header.php"); ?> 
  <?PHP include("./sql/document_db.php"); ?> 
  <?PHP include("sql/connection_db.php"); ?>
</head>

<body>
<div id="header">
  <h1>Serveur Web MobilHome200</h1>
</div>

<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Information PHP");
	?>


  <div class="article">
	<?PHP
	echo "<p>SERVER_NAME : " . getenv("SERVER_NAME") . "<br></p>";
	?>
	
  	<IFRAME SRC="phpinfo_internal.php" WIDTH=1000 HEIGHT=22000>
	If you can see this, your browser doesn't 
	understand IFRAME.  
	</IFRAME>

  </div> <!-- article -->
</div> <!-- contenu -->

  <?PHP include("./menu.php"); ?> 
  <?PHP include("sql/deconnection_db.php"); ?>
  <?PHP include("./footer.php"); ?> 

</body>
</html>
