<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> test </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
    include_once("../sql/user_cegid_db.php");
	include_once("../sql/member_db.php");// lien croisé avec tool_db.php
  	include_once("../js/date_calendar.js");   // affichage calebdrier pour saisie date 
	?>
</head>

<?PHP 		testMemberGroup(2); ?>


<body>
<div id="header">
  <h1>Serveur Web Pointage : Users CEGID</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Gestion Users CEGID");
	?>
  


<div class="article">
<div class="section">


<?php
	echo "<p>Gestion des users CEGID.<br/></p>";
	
    //gestion des actions sur les utilisateurs
	applyGestionUserCEGID(); 
	
	//pour forcer l'affichage d'un insert
	$idBalise="CreateUser";
	createHeaderBaliseDiv($idBalise,"<h3>Création User.</h3>");
	showOnlyInsertTableUserCEGID();
	endHeaderBaliseDiv($idBalise);
	echo"<br>";
	
	//short liste utilisateur
	$idBalise="user_short";
	createHeaderBaliseDiv($idBalise,"<h3>Liste des users.</h3>");
	showShortTableUserCEGID();
	endHeaderBaliseDiv($idBalise);
	
	
    //details users
	$idBalise="user_detail";
	createHeaderBaliseDiv($idBalise,"<h3>Detail des users.</h3>");
	showLoadFile("","","","import");
	showTableUserCEGID();
	endHeaderBaliseDiv($idBalise)
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