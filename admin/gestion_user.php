<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title>Serveur Web MobilHome200. Ajout User</title>
  <?PHP include("../header.php"); ?> 
</head>

  <?PHP include("../sql/member_db.php");  ?>
  <?PHP include("../sql/connection_db.php"); ?>

  <?PHP 		testMemberGroup(3); ?>


<body>

<div id="header">
  <h1>Ajout Utilisateur</h1>
</div>

<div id="contenu">

	<?PHP 
	showBandeauHeaderPage("Gestion Utilisateur");
	?>

  <div class="article">
  <?PHP applyGestionUser("gestion_user.php"); ?>
 </div> <!-- article -->
</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>
