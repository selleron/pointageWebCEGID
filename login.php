<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web Pointage. Connexion</title>
  <?PHP 
    include("./header.php");  
    //include("./sql/document_db.php");  
    include("sql/connection_db.php"); 
  ?>
</head>

<body>

<div id="header">
  <h1>Connexion</h1>
</div>

<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Connexion");
	?>
		
  <div class="article">

 
  
	<div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" font-size=13pt >Pour 
		acceder &agrave; cette rubrique, vous devez vous identifier :</font></b> </div>
		<form method="post" action="admin/login.php">
			<div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" font-size=13pt >
			<br/>Pseudo<br/>
			<input type="text" style="font-size:13pt" name="pseudo">
			<br/>
			<br/>
			Mot de passe<br>
			<input type="password" style="font-size:13pt"  name="passe">
			<br/>
			<br/>
			<input type="submit" name="Submit" value="Entrer" class="input">
			</font></b>
			</div>
		</form>
	</div> <!-- center -->
	
	
 </div> <!-- article -->
</div> <!-- contenu -->

  <?PHP include("./menu.php"); ?> 
  <?PHP include("./footer.php"); ?> 

</body>
</html>
