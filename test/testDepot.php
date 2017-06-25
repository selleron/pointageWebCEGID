<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Requests </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
    require_once("../sql/files.php");
    require_once("../sql/files_db.php");
    include_once("../sql/requetes_db.php");
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Depôt</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Depôt de fichiers CEGID");
	?>
  


<div class="article">
<div class="section">

<?php
 showPost();
?>

<p>Transfert les fichiers de /depot vers /stockage.<br>
Les fichiers sont renom&eacute;s et inscrits dans la table [files]<br> 
</p>

<table>
<tr>
  <td colspan="2"><?php  showLoadFile("testDepot.php") ?></td>
</tr>

<tr>
  <td>[/depot] vers [/stockage]</td>
  <td>
    <form enctype="multipart/form-data" action="testDepot.php" method="post">
      <?php   showFormAction("depot"); ?>
      <?php   showFormIDElement(); ?>
      <input type="submit" value="Traiter les fichiers" />
    </form>
  </td>
</tr>

<tr>
  <td>voir les fichiers &agrave; stocker [../depot]</td>
  <td>
    <form enctype="multipart/form-data" action="testDepot.php" method="post">
      <?php   showFormAction("showDepot"); ?>
      <?php   showFormIDElement(); ?>
      <input type="submit" value="Voir" />
    </form>
  </td>
</tr>

<tr>
  <td>voir les fichiers stock&eacute;s [../stockage]</td>
  <td>
    <form enctype="multipart/form-data" action="testDepot.php" method="post">
      <?php   showFormAction("showStockage"); ?>
      <?php   showFormIDElement(); ?>
      <input type="submit" value="Voir" />
    </form>
  </td>
</tr>

<tr>
  <td>voir table des fichiers</td>
  <td>
    <form enctype="multipart/form-data" action="testDepot.php" method="post">
      <?php   showFormAction("showTableStockage"); ?>
      <?php   showFormIDElement(); ?>
      <input type="submit" value="Voir" />
    </form>
  </td>
</tr>

</table>

<br>

<p>
Manuel PHP : 
<a href="http://www-g.oca.eu/galilee/sigo/articles/060630version-stockage_fichiers_mysql_php.pdf">
 http://www-g.oca.eu/galilee/sigo/articles/060630version-stockage_fichiers_mysql_php.pdf
 </a>
 <br>
</p>

<p><br></p>

<?php
$action = getActionGet();
echo "traitement de l'action : $action <br>";
if ($action == "depot"){
	echo "actionStockFiles() <br><br>";
	actionStockFiles();
} 
if ($action == "showDepot"){
	echo "show depot [$DIR_DEPOT_FROM]... <br><br>";
	showDirectory($DIR_DEPOT_FROM);
} 

if ($action == "load"){
	echo "actionStockTemporaryFile() ... <br><br>";
	actionStockTemporaryFile();
} 

if ($action == "showStockage"){
	echo "show stockage [$DIR_DEPOT]... <br><br>";
	showDirectory($DIR_DEPOT);
} 
if ($action == "showTableStockage"){
	echo "show table stockage ... <br><br>";
	global $COLUMNS_SUMMARY;
	global $SQL_SHOW_COL_FILE;
	global $TABLE_NAME;
	global $TABLE_SIZE;
	global $ORDER_GET;
	$param = createDefaultParamSql("files", $SQL_SHOW_COL_FILE);
	
	$param[$TABLE_SIZE]=1200;
	showTableHeader($param);
	showTableData($param);
} 
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