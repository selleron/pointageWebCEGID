<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">

<head>
  <title> CEGID Tests Tableaux </title>
  <?PHP 
    include_once("../header.php");
	include_once("../sql/member_db.php");// lien croise avec tool_db.php
    include_once("../sql/connection_db.php"); 
    include_once("../sql/requetes_db.php");
    include_once("../sql/commande_presta_db.php");
    
	include_once("../js/form_db.js");   
	testMember();
	?>
</head>

<body>
<div id="header">
  <h1>Serveur Web Pointage : Requests</h1>
</div>


<div id="contenu">

  	<?PHP 
	showBandeauHeaderPage("Tests Tableaux");
	?>
  


<div class="article">
<div class="section">

<?php

 createForm("","maForm");
 echo "<table id='maTable' "; 
 //echo "onchange=\"sommeColonneHTMLTable(this,'name,uo')\"   >"; 
 echo "onchange=\"sommeColonneRowHTMLTable(this,'name,uo,no form', 'sum_row_xxx', 'uo,jours')\"   >"; 
 echo "<tr>";

 
 
 beginTableHeader();
 echo "<td>project</td><td>name</td><td>uo</td><td>jours</td><td>sum_row_xxx</td><td>no form</td>";
 endTableHeader();
 
 echo "<tr>";
 showFormTextElementForVariable("maForm","project[0]","","","P17001");
 showFormTextElementForVariable("maForm","name[0]","","","01");
 showFormTextElementForVariable("maForm","uo[0]","","","30");
 showFormTextElementForVariable("maForm","jours[0]","","","7");
 echoTD("");
 beginTableCell('id="no form[0]"');
 echo "10";
 endTableCell();
 echo"</tr>";
 
 echo "<tr>";
 showFormTextElementForVariable("maForm","project[1]","","","P17002");
 showFormTextElementForVariable("maForm","name[1]","","","01");
 showFormTextElementForVariable("maForm","uo[1]","","","20");
 showFormTextElementForVariable("maForm","jours[1]","","","8");
 
 showFormTextElementForVariable("maForm","sum_row_xxx[1]","no","","","","","disabled");
 beginTableCell('id="no form[1]"');
 echo "20";
 endTableCell();
 echo"</tr>";

 echo "<tr>";
 showFormTextElementForVariable("maForm","project[2]","","","P17003");
 showFormTextElementForVariable("maForm","name[2]","","","01");
 showFormTextElementForVariable("maForm","uo[2]","","","10");
 showFormTextElementForVariable("maForm","jours[2]","","","9");

 showFormTextElementForVariable("maForm","sum_row_xxx[2]","no","","","","","disabled");
 echo"</tr>";

 echo "<tr>";
 showFormTextElementForVariable("maForm","project[3]","","","P17004");
 showFormTextElementForVariable("maForm","name[3]","","","01");
 showFormTextElementForVariable("maForm","uo[3]");
 echo"</tr>";
 
 //ajoute la ligne de sommation
 echo "<tr>";
 echo"<td> somme : </td>";
 showFormTextElementForVariable("maForm","sum_col_name","no","","","","","disabled");
 showFormTextElementForVariable("maForm","sum_col_uo","no","","","","","disabled");
 echoTD("-");
 echoTD("-");
 showFormTextElementForVariable("maForm","sum_col_no form","no","","","","","disabled");
 echo"</tr>";
 
 
 echo"</table>";
  endForm();


  showSommation('maTable','name,uo,no form', 'sum_row_xxx', 'uo,jours', "sum");
  
?>


<br/><br/><br/>


<a href="#openModal">Open Modal</a>

<div id="openModal" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<h2>Modal Box</h2>
		<p>This is a sample modal box that can be created using the powers of CSS3.</p>
		<p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>
	</div>
</div>

<br/><br/><br/>
<?php

$date1 = "2020/01/01";
$res = $date1;
$cmd = "\$res = getStyleDateFinCommandePrestataire(\$res);";
eval("$cmd");
$dateFormatted = $res; 
echo("evaluation result : [ $dateFormatted ] <br>");


beginTable();
beginTableRow("",3);
beginTableCell();    echo"date : "; endTableCell();
beginTableCell($dateFormatted);    echo" $date1"; endTableCell();
endTableRow();
endTable();
?>

</div> <!-- section -->
</div> <!-- article -->

</div> <!-- contenu -->

  <?PHP include("../menu.php"); ?> 
  <?PHP include("../sql/deconnection_db.php"); ?>
  <?PHP include("../footer.php"); ?> 

</body>
</html>