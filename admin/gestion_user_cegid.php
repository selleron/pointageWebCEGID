<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> test </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/files.php");
    include_once("../sql/user_cegid_db.php");
    include_once("../sql/toolNextPrevious.php");
    include_once("../sql/member_db.php");// lien croisé avec tool_db.php
    testMemberGroup(2);
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
	showTracePOST();
	
	//gestion Next - previous
	global $SQL_TABLE_CEGID_USER;
	global $SQL_COL_ID_CEGID_USER;
	global $SQL_COL_NAME_CEGID_USER;
	global $CONDITION_FROM_CEGID_USER;      //dans form_db_config.php
	
 	if (blockCondition("only_user_visible", "<h4>only user visible [<value>]</h4>")){
 	    //nothing to do
 	}
 	else{
 	    $CONDITION_FROM_CEGID_USER="";
 	}
	
	$exec = applyNextPreviousSelectTable("$SQL_TABLE_CEGID_USER"/*$table*/, 	$SQL_COL_ID_CEGID_USER, "$SQL_COL_NAME_CEGID_USER"/*$colName*/,$CONDITION_FROM_CEGID_USER/*condition*/);
	
	echo"<p>";
	showTableSelection(""/*$url*/, "$SQL_TABLE_CEGID_USER"/*$table*/, "$SQL_COL_NAME_CEGID_USER"/*$colName*/, ""/*$formName*/, "no"/*$yearVisible = "yes"*/, "no"/*$export*/, "no"/*$userVisible*/, "yes"/*$previousVisible*/, "yes"/*$nextVisible*/);
	echo"<br/></p>";
	activeEditFromNextPrevious($SQL_COL_NAME_CEGID_USER);
	
	
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

	if (blockCondition("show_user_team", "<h4>show team [<value>]</h4>")){
	    global $SQL_SHOW_BEGIN_COL_CEGID_USER;  //dans user_cegid_db.php
	    global $SQL_COL_TEAM_CEGID_USER;        //dans user_cegid_db.php
	    global $SQL_COL_STATUS_CEGID_USER;       //dans user_cegid_db.php
	    $colsShowed=$SQL_SHOW_BEGIN_COL_CEGID_USER.", $SQL_COL_TEAM_CEGID_USER, $SQL_COL_STATUS_CEGID_USER";
	}
	else{
	    $colsShowed="";
	}
	showShortTableUserCEGID($CONDITION_FROM_CEGID_USER, $colsShowed);
	endHeaderBaliseDiv($idBalise);
	
	
    //details users
	$idBalise="user_detail";
	createHeaderBaliseDiv($idBalise,"<h3>Detail des users.</h3>");
	showLoadFile("","","","import");
	if (blockCondition("user_expert_insert", "<h4>user mode expert[<value>]</h4>")){
	    showTableUserCEGID($CONDITION_FROM_CEGID_USER);
	}
	else{
	    showTableMediumUserCEGID($CONDITION_FROM_CEGID_USER);
	}
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