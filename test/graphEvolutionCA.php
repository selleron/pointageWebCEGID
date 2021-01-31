<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Tests Graph CA</title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
	testMember();
	include_once("../sql/pointage_cegid_db.php");
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Test Graph CA</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Graph CA avec PHP 7.0 dans webcegid");
	?>
  


<div class="article">
<div class="section">

<?php

  showTracePOST();
  echo "Vous avez PHP <a href=\"../phpinfo.php\">".phpversion()." </a>";
?>

<p>
	<br /> Si vous n'avez pas d'information sur GD<br /> Ligne &agrave; ajouter
	dans le php.ini<br /> <strong>extension=php_gd2.dll</strong> <br />
</p>

<?php 
    $gd_info = gd_info();
    if(!$gd_info){
	   echo("<br />La librairie GD n'est pas install&eacute;e !");
	}
	else{
		echo "<br />Vous avez GD {$gd_info['GD Version']}<br/><br/>";
	}
?>

<?php
	
	echo"<p>";
	showProjectSelection(""/*url*/,""/*form*/,"yes"/*year*/,
	    LabelAction::ActionExportCSV/*export*/,
	    "no"/*user*/, "no"/*previous*/, "no"/*next*/,
	    ""/*multi selection*/, "no"/*project*/);
	echo"<br/></p>";
	
	

    echo"<p>";
	
    //pour debug
    global $TRACE_GENERATION_IMAGE;
    if ($TRACE_GENERATION_IMAGE=="yes"){
        echo "<br>===<br>";
        include_once("imageEvolutionCA.php");
        echo "<br>===<br>";
    }
    else{
        //preparation des paramètres pour l'image
        // $year
        $year = getURLYear();
        $urlParamImage="year=$year";
        
        //affichage de l'image
        echo "<img src=\"imageEvolutionCA.php?$urlParamImage\" alt=\"image de pChart generee par imageEvolutionCA.php\" />";
    }
    
    echo"</p>";  

?>



</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>