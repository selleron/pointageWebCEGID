<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web MobilHome200. Erreur</title>
	<?PHP include("./header.php"); ?> 
	<?PHP include("./sql/document_db.php"); ?> 
	<?PHP include("sql/connection_db.php"); ?>
</head>

<body>

<div id="header">
  <h1>Connexion</h1>
</div>

<div id="contenu">
	<?PHP 
	showBandeauHeaderPage("Erreur");
	?>

  <div class="article">

  
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="4"><b><br/>
  <br/>
  </b></font></p>
<table width="85%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#00FFFF">
  <tr>
	<td>
	<?php 
	global $URL_IMAGES;
	echo '<img src="$URL_IMAGES/warning.gif">';
	?>
	</td>
    <td>
      <p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="4">
	  <b>Erreur de connexion,<br> 
	  v&eacute;rifiez votre login et votre mot de passe !</b></font> 
      </p>
      <p></p>
    </td>
  </tr>
</table>
<br>

<p></p>
<p></p>
<p></p>
<p></p>
<p></p>

 </div> <!-- article -->
 </div> <!-- about -->
</div> <!-- contenu -->

  <?PHP include("./menu.php"); ?> 
  <?PHP include("./footer.php"); ?> 

</body>
</html>
