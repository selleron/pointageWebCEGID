<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web Pointage. Contact</title>
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
	showBandeauHeaderPage("Contact");
	?>
		
<div class="article">
 
   <br/><br/>
 
	<?PHP
	global $CONTACT_EMAIL;
	
	echo"
 	<table>
	<tr>
	<td><img src=\"$URL_IMAGES/menu_courrier.png\"  > Email </td>
	<td>
	  : <a 
		title=\"Pour vos questions et r&eacute;servation\" 
		href=\"mailto:$CONTACT_EMAIL?subject=questions et commentaires\">  
		$CONTACT_EMAIL
		</a> 
	</td>
	</tr>
	<tr>
	<td><img src=\"$URL_IMAGES/telephone.png\"  > T&eacute;lephone </td>
	<td>: -- -- -- -- --</td>
	</tr>
	<tr>
	<td><img src=\"$URL_IMAGES/menu_mobile.png\"  > Mobile </td>
	<td>: -- -- -- -- --</td>
	</tr>
	</table>  
    ";
	?>
 
   <br/><br/>
 
 </div> <!-- article -->


</div> <!-- contenu -->

  <?PHP include("./menu.php"); ?> 
  <?PHP include("./footer.php"); ?> 

</body>
</html>














