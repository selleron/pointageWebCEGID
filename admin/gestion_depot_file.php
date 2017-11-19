<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Depot File </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
    require_once("../sql/files.php");
    require_once("../sql/files_db.php");
    include_once("../sql/requetes_db.php");
    include_once("../sql/table_db.php");
    
    testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Depôt</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Depôt de fichiers CEGID");
	?>
  


<div class="article">
<div class="section">

<?php
 showTracePOST();

 $url = getCurrentPageName();
 $beginForm = "<form enctype=\"multipart/form-data\" action=\"$url\"  method=\"post\">";
 
 $idBalise="depot";
 createHeaderBaliseDiv($idBalise,"<h3>Transfert les fichiers de [depot] vers [stockage].</h3>");
?>

 <p>
 Les fichiers sont renom&eacute;s et inscrits dans la table [files]<br>
 </p>


<table>
<tr>
  <td colspan="2"><?php  showLoadFile("$url") ?></td>
</tr>

<tr>
  <td>[/depot] vers [/stockage]</td>
  <td>
      <?php
      echo $beginForm;
      showFormAction("depot"); 
      showFormIDElement(); ?>
      <input type="submit" value="Traiter les fichiers" />
    </form>
  </td>
</tr>

<tr>
  <td>voir les fichiers &agrave; stocker [../depot]</td>
  <td>
          <?php
      echo $beginForm;
      showFormAction("showDepot"); 
  	  showFormIDElement(); ?>
      <input type="submit" value="Voir" />
    </form>
  </td>
</tr>

<tr>
 <?php
      echoTD("voir les fichiers stock&eacute;s [../stockage]" ); 
      beginTableCell();
      echo $beginForm;
      showFormAction("showStockage"); 
      showFormIDElement(); 
 ?>
      <input type="submit" value="Voir" />
    </form>
  </td>
</tr>

<tr>
  <td>voir table des fichiers</td>
  <td>
          <?php
      echo $beginForm;
      showFormAction("showTableStockage"); 
      showFormIDElement(); ?>
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

endHeaderBaliseDiv($idBalise);


global $TRACE_FILE;
$action = getActionGet();
showActionVariable( "traitement de l'action : $action <br>",$TRACE_FILE);
if ($action == "depot"){
    showActionVariable(  "actionStockFiles() ",$TRACE_FILE);
	actionStockFiles();
} 
if ($action == "showDepot"){
    showActionVariable(  "show depot [$DIR_DEPOT_FROM]... ",$TRACE_FILE);
	showDirectory($DIR_DEPOT_FROM);
} 

if ($action == "load"){
    showActionVariable(  "action load: actionStockTemporaryFile() ...",$TRACE_FILE);
	actionStockTemporaryFile();
} 

if ($action == "showStockage"){
    showActionVariable(  "show stockage [$DIR_DEPOT]... ",$TRACE_FILE);
	showDirectory($DIR_DEPOT);
} 
if ($action == "showTableStockage"){
    showActionVariable(  "show table stockage ... ",$TRACE_FILE);
    global $SQL_TABLE_FILE;
	global $SQL_SHOW_COL_FILE;
	$condition="";
	$form_name=$SQL_TABLE_FILE."_insert";

	//$param = createDefaultParamSql("files", $SQL_SHOW_COL_FILE);
	$param = prepareshowTable($SQL_TABLE_FILE, $SQL_SHOW_COL_FILE, $form_name, $condition);
	//par defaut on a edit & delete
	// 	$param[PARAM_TABLE_ACTION::TABLE_EDIT] = "no";
	// 	$param[PARAM_TABLE_ACTION::TABLE_DELETE] = "no";
	// 	$param[PARAM_TABLE_ACTION::TABLE_EDIT_BY_ROW] = "yes";
	// 	$param[PARAM_TABLE_ACTION::TABLE_DELETE_BY_ROW] = "yes";
	
	//ajout export CSV
	$param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
	$param[PARAM_TABLE_ACTION::TABLE_INSERT] = "no";
	
	$request=createRequeteTableData($param);
	showSQLAction($request);
	
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