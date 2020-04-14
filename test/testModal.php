<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Tests Tableaux </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
    include_once("../sql/requetes_db.php");
    
	include_once("../js/form_db.js");   
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Modal</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Dialog Modal");
	?>
  


<div class="article">
<div class="section">


<br/><br/><br/>

<button  type="button">
<a href="#openModal">Open Modal (1) par [href]</a>
</button>

<div id="openModal" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h2>Modal Box</h2>
		<p>This is a sample modal box that can be created using the powers of CSS3.</p>
		<p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>
	</div>
</div>


<br/><br/><br/>

<button  type="button">
<a href="#openModal2">Open Modal (2) par [href]</a>
</button>

<div id="openModal2" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h2>Modal Box Two</h2>
		<p>This is a sample modal box that can be created using the powers of CSS3.</p>
		<p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>
		<div align=center ><a href="#close" ">Close</a></div>
	</div>
</div>

<button   type="button">
<a href="#openModal3">Go to Tests Tableaux</a>
</button>

<div id="openModal3" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h2>Modal Box Two</h2>
		<p>This is a sample modal box that can be created using the powers of CSS3.</p>
		<p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>
		<div align=center > <button type="button">
		<a href="testTableaux.php">Close</a>
		</button></div>

	</div>
</div>

<br/><br/><br/>
<?php

echo("ici code PHP <br>");


?>

</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>