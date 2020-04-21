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
applyGestionDepot();

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