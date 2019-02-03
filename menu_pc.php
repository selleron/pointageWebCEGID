<?PHP
//pour compacter / decompacter les menus
//include_once($_SERVER ['DOCUMENT_ROOT']. "/js/menu.js");
include_once(dirname(__FILE__). "/js/menu.js");

if (!isset($MEMBER_DB_PHP)){
	include_once("sql/member_db.php");
}
if (!isset($ACCESS_SITE_PHP)){
	include_once("sql/access_site.php");
}

global $URL_ROOT_POINTAGE;
global $ACTION_GET;
global $ANNEE_RESERVATION;
global $ANNEE_MAX;
global $URL_IMAGES;

$id=getMemberID();

//gestion des arguments par defaut
$argument=propagateArguments();
// if (! strpos ( $html, "?" )) {
// 	//$argument = propagateArguments ( "yes" );
// 	$argument = propagateArguments ( "" );
// 	$html = $html . $argument;
// }


trace_access_history();
updateCounterAccessAndEmail();

$plateform = getPlateformClient();
if ($plateform==$COOKIE_PLATEFORM_VALUE_PC){
	$menuIconSize="";
}
else{
	$menuIconSize="width=\"50\" height=\"50\"";
}


//pas defaut sur les mobiles, le menu n'est pas visible
$plateform = getPlateformClient(); //fonction dans tool_db.php
if ($plateform==$COOKIE_PLATEFORM_VALUE_PC){
	createHeaderBaliseDivVisibility("block_menu","");
}
else{
	createHeaderBaliseDivVisibility("block_menu","none");
}

echo "<div id=\"menu\">";
//creation balise <id>
createHeaderBaliseDiv("menuprincipal","<h2>Menu</h2>");

echo"<ul>
<li><a title=\"Accueil\"    href=\"$URL_ROOT_POINTAGE/default.php$argument\">      <img src=\"$URL_IMAGES/menu_accueil.png\"  $menuIconSize > Accueil</a></li>";
if (isMemberGroup(2)){
	$urlpointage  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/pointage_cegid.php$argument");
	echo "<li><a title=\"Gestion Pointage\" href=\"$urlpointage\"> <img src=\"$URL_IMAGES/menu_planning.png\" $menuIconSize > Pointage</a></li>";

	$urloneproject  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/one_project_cegid.php$argument");
	echo "<li><a title=\"Gestion par projet\" href=\"$urloneproject\"> <img src=\"$URL_IMAGES/menu_projets.png\" $menuIconSize > Projet</a></li>";
	
	$urlprevision  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/pointage_prevision_cegid.php$argument");
	echo "<li><a title=\"Prevision projet\" href=\"$urlprevision\"> <img src=\"$URL_IMAGES/menu_prevision.png\" $menuIconSize > Previsionnel</a></li>";

	$urlprevisionuser  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/pointage_prevision_user_cegid.php$argument");
	echo "<li><a title=\"Prevision Collaborateur\" href=\"$urlprevisionuser\"> <img src=\"$URL_IMAGES/menu_prevision.png\" $menuIconSize > Prev. collaborateur</a></li>";

	$urlprevisionuser2  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/pointage_prevision_user2_cegid.php$argument");
	echo "<li><a title=\"Prevision par Collaborateur\" href=\"$urlprevisionuser2\"> <img src=\"$URL_IMAGES/menu_prevision.png\" $menuIconSize > Prev. par collab.</a></li>";

	$urlimport  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/user/pointage_import_cegid.php$argument");
	echo "<li><a title=\"Import projet\" href=\"$urlimport\"> <img src=\"$URL_IMAGES/menu_import.png\" $menuIconSize > Import</a></li>";
	
	
}
else{
	$date = $ANNEE_RESERVATION;
	echo "<li><a title=\"Planning/Tarifs $date\"	href=\"$URL_ROOT_POINTAGE/planning".$date.".php$argument\"> 	<img src=\"$URL_IMAGES/menu_planning.png\" $menuIconSize > Planning $date</a></li>";
}

if ($plateform==$COOKIE_PLATEFORM_VALUE_PC){
echo "";
}
else{
echo "";
}
echo "
  <li><a title=\"Pour vos questions et r&eacute;servation\"     
  href=\"$URL_ROOT_POINTAGE/contact.php$argument\"> 
  <img src=\"$URL_IMAGES/menu_courrier.png\" $menuIconSize > Contacts</a></li>
  ";

if ($plateform==$COOKIE_PLATEFORM_VALUE_PC){
	echo 	"<li><a title=\"Pour affichage sur tablette ou mobile\" 
			href=\"$URL_ROOT_POINTAGE/default.php$argument\" 
			onclick=\"javascript:setPlateformMobile(); return true;  \">
			<img src=\"$URL_IMAGES/menu_mobile.png\" $menuIconSize >      Version Mobile</a></li>
	";
}
else{
	echo 	"<li><a title=\"Pour affichage sur PC\"
	href=\"$URL_ROOT_POINTAGE/default.php$argument\"
	onclick=\"javascript:setPlateformPC(); return true;  \">
	<img src=\"$URL_IMAGES/menu_pc.png\" $menuIconSize >      Version PC</a></li>
	";
}

echo "</ul>";


if ($id==""){
	echo "
	<ul>
	<li><a title=\"Login\" href=\"$URL_ROOT_POINTAGE/login.php$argument\"> <img src=\"$URL_IMAGES/connexion_user.png\" $menuIconSize > Connexion</a></li>
	</ul>";
	//end div Menu
	echo"</div>";
}
else{
	//end div Menu
	echo "</div>";
	
	//creation balise <edition>
	createHeaderBaliseDiv("id_div_edition","<h2>Edition</h2>");
	if (isMemberGroup(2)){
	    $urlproject        = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_project.php$argument");
	    $urldevis          = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_devis.php$argument");
	    $urlprofils        = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_profils.php$argument");
	    $urluser           = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_user_cegid.php$argument");

	    $urlstatusdevis    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_devis.php$argument");
	    $urlstatuscegid    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_cegid.php$argument");
	    $urlstatusvisible  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_visible.php$argument");
	    $urlstatuscommande = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_commande.php$argument");
	    $urlstatusproject  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_project.php$argument");
	    $urltypeproject    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_type_project.php$argument");
	    
	    createHeaderBaliseDiv("id_div_etat","<h3>Gestion Etat</h3>");
	    echo "
		<ul>
		<li><ul>
        <li><a title=\"Gestion Status Generique\" href=\"$urlstatuscegid\"> 	 <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Etat Generique</a></li>
		<li><a title=\"Gestion Status Devis\"     href=\"$urlstatusdevis\"> 	 <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Etat Devis</a></li>
		<li><a title=\"Gestion Status Project\"   href=\"$urlstatusproject\">    <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Etat Projet</a></li>
		<li><a title=\"Gestion Type   Project\"   href=\"$urltypeproject\">      <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Type Projet</a></li>
		<li><a title=\"Gestion Status Commande\"  href=\"$urlstatuscommande\">   <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Etat Commande</a></li>
		<li><a title=\"Gestion Status Visible\"   href=\"$urlstatusvisible\">    <img src=\"$URL_IMAGES/jumelle.png\"       $menuIconSize> Etat Visible</a></li>
        </ul></li>
		</ul>";
	    echo "</div>";
	    
	    
	    $urlclient        = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_societe_client.php$argument");
	    $urlfournisseur   = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_societe_fournisseur.php$argument");
	    createHeaderBaliseDiv("id_div_societe","<h3>Gestion Societes</h3>");
	    echo "
		<ul>
		<li><ul>
        <li><a title=\"Gestion Fournisseurs\" href=\"$urlfournisseur\"> 	 <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Fournisseurs</a></li>
		<li><a title=\"Gestion Clients\"      href=\"$urlclient\">       	 <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Clients</a></li>
		</ul></li>
		</ul>";
	    echo "</div>";
	    
	    
	    echo"
		<ul>
		<li><a title=\"Gestion Profils\" href=\"$urlprofils\"> 	             <img src=\"$URL_IMAGES/menu_plan.png\"     $menuIconSize> Gestion Profils</a></li>
		<li><a title=\"Gestion Users\" href=\"$urluser\"> 	                 <img src=\"$URL_IMAGES/modify_user.png\"   $menuIconSize > Gestion Users</a></li>
		<li><a title=\"Gestion Devis\" href=\"$urldevis\"> 	                 <img src=\"$URL_IMAGES/menu_projets.png\"  $menuIconSize > Gestion Devis</a></li>
		<li><a title=\"Gestion Projet\" href=\"$urlproject\"> 	             <img src=\"$URL_IMAGES/menu_projets.png\"  $menuIconSize > Gestion Projets</a></li>
		</ul>";
	}
	echo "</div>";
	
	//autre
	if (isMemberGroup(3)){
	    createHeaderBaliseDiv("id_div_CA","<h2>CA</h2>");
	 
	    $urlRespAffaires   = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/resp_affaires.php$argument");
	    $urlUODiff     = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/uo_diff_cegid.php$argument");
	    $urlCAActuel   = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/ca_actuel.php$argument");
	    $urlCAActuel2  = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/ca_actuel2.php$argument");
	    $urlCAPrev     = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/ca_previsionel.php$argument");
	    $urlCAPrev2    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/ca_previsionel2.php$argument");
	    $urlCloture    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/cloture.php$argument");
	    $urlArchives   = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/archives.php$argument");
	    $urlSuiviDevis = replacePageURL("order", "", "$URL_ROOT_POINTAGE/ca/suivi_propositions.php$argument");
	    
    	echo"
    		<ul>
    		 <li><a title=\"Responsable affaires\"   href=\"$urlRespAffaires\">  <img src=\"$URL_IMAGES/ca_actuel.png\"         $menuIconSize > Resp. affaires</a></li>
    		 <li><a title=\"Suivi Devis\"            href=\"$urlSuiviDevis\">    <img src=\"$URL_IMAGES/ca_actuel.png\"         $menuIconSize > Suivi propositions</a></li>
    		 <li><a title=\"CA Actuel clos\"         href=\"$urlCAActuel2\">     <img src=\"$URL_IMAGES/ca_actuel.png\"         $menuIconSize > CA actuel clos...</a></li>
    		</ul>";
    	createHeaderBaliseDiv("id_div_CA_Suite","<h3>CA autres</h3>");
    	echo"
    		<ul>
    		 <li><a title=\"CA Actuel\"              href=\"$urlCAActuel\">      <img src=\"$URL_IMAGES/ca_actuel.png\"         $menuIconSize > CA actuel...</a></li>
    		 <li><a title=\"CA Previsionnel\"        href=\"$urlCAPrev\">        <img src=\"$URL_IMAGES/ca_previsionel.png\"    $menuIconSize > CA previsionnel</a></li>
    		 <li><a title=\"CA Previsionnel clos\"   href=\"$urlCAPrev2\">       <img src=\"$URL_IMAGES/ca_previsionel.png\"    $menuIconSize > CA prev. clos</a></li>
    		 <li><a title=\"UO Differentiel\"        href=\"$urlUODiff\">        <img src=\"$URL_IMAGES/ca_actuel.png\"         $menuIconSize > UO Differentiel</a></li>
    		</ul>";
    	endHeaderBaliseDiv("id_div_CA_Suite");
    	echo"
    		<ul>
    	     <li><a title=\"Cloture\"                href=\"$urlCloture\">       <img src=\"$URL_IMAGES/menu_planning.png\"      $menuIconSize > Cloture</a></li>
    	     <li><a title=\"Archives\"               href=\"$urlArchives\">      <img src=\"$URL_IMAGES/menu_planning.png\"      $menuIconSize > Archives</a></li>
    		</ul>";
    	endHeaderBaliseDiv("id_div_CA");
	}
	
	
	//autre
	createHeaderBaliseDiv("id_div_edition_brut","<h2>Autres</h2>");
	if (isMemberGroup(2)){
	    $urlproject2         = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/project_cegid.php$argument");
	    $urlstatusproject    = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_status_project.php$argument");
	    $urlfilecode         = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_file_code.php$argument");
	    $urlcout             = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_cout_project_cegid.php$argument");
	    $urlfraismission     = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_frais_mission_cegid.php$argument");
	    $urlpointage         = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_pointage_cegid.php$argument");
	    $urlpointagebrut     = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/pointage_simple_cegid.php$argument");
	    $urlpointageimport   = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/import_simple_cegid.php$argument");
	    $urlprevisionnelbrut = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/previsionnel_simple_cegid.php$argument");
	    $urlcegidfile        = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_cegid_file.php$argument");
	    $urldepot            = replacePageURL("order", "", "$URL_ROOT_POINTAGE/admin/gestion_depot_file.php$argument");
	    
	    echo"
		<ul>
		<li><a title=\"Gestion File CEGID\"     href=\"$urlcegidfile\">         <img src=\"$URL_IMAGES/menu_historique.png\"      $menuIconSize > Gestion Ref Files</a></li>
		<li><a title=\"Gestion File Code\"      href=\"$urlfilecode\">          <img src=\"$URL_IMAGES/menu_historique.png\"      $menuIconSize > Gestion File Code</a></li>
		<li><a title=\"Depot de fichiers\"      href=\"$urldepot\">             <img src=\"$URL_IMAGES/menu_courrier.png\"        $menuIconSize > Depot de fichiers</a></li>
		<li><a title=\"Gestion Projet\"         href=\"$urlcout\"> 	   	        <img src=\"$URL_IMAGES/menu_cout.png\"            $menuIconSize > Gestion Cout</a></li>
		<li><a title=\"Gestion Frais Mission\"  href=\"$urlfraismission\">      <img src=\"$URL_IMAGES/menu_frais_mission.png\"   $menuIconSize > Gestion Frais Mis.</a></li>
		<li><a title=\"Projet\"                 href=\"$urlproject2\">          <img src=\"$URL_IMAGES/menu_projets.png\"         $menuIconSize > Projet</a></li>
		<li><a title=\"Gestion Pointage\"       href=\"$urlpointage\">          <img src=\"$URL_IMAGES/menu_planning.png\"        $menuIconSize > Gestion Pointage</a></li>
		<li><a title=\"Pointage Brut\"          href=\"$urlpointagebrut\">      <img src=\"$URL_IMAGES/menu_telechargement.png\"  $menuIconSize > Pointage brut</a></li>
		<li><a title=\"Previsionnel Brut\"      href=\"$urlprevisionnelbrut\">  <img src=\"$URL_IMAGES/menu_telechargement.png\"  $menuIconSize > Previsionnel brut</a></li>
		<li><a title=\"Pointage Import\"        href=\"$urlpointageimport\">    <img src=\"$URL_IMAGES/menu_telechargement.png\"  $menuIconSize > Pointage Import</a></li>
		<li><a title=\"Historique\"             href=\"$URL_ROOT_POINTAGE/admin/administration_planning.php$argument\"> <img src=\"$URL_IMAGES/menu_historique.png\" $menuIconSize > Historique</a></li>
		</ul>";
	}

	    
	
	echo "</div>";
	
	echo"
	<ul>
	<li><a title=\"Logout\" href=\"$URL_ROOT_POINTAGE/admin/logout.php$argument\"> <img src=\"$URL_IMAGES/connexion_user.png\" $menuIconSize > D&eacute;connexion</a></li>
	</ul>";
	
	//Admin
	if (isMemberGroup(3) || isMemberGroup(2)){
		//creation balise <id>
		createHeaderBaliseDiv("id_div_admin","<h2>Adm. Site</h2>");
		echo "<ul>";
		if (isMemberGroup(3)){
			echo "<li><a title=\"Add User\"             href=\"$URL_ROOT_POINTAGE/admin/gestion_user.php$argument&$ACTION_GET=addUserSelection\">      <img src=\"$URL_IMAGES/add_user.png\" $menuIconSize >      add User      </a></li>
			<li><a title=\"Modification User\"          href=\"$URL_ROOT_POINTAGE/admin/gestion_user.php$argument&$ACTION_GET=modifyUserSelection\">   <img src=\"$URL_IMAGES/modify_user.png\" $menuIconSize >   modif. User   </a></li>
			<li><a title=\"Configuration PHP\"          href=\"$URL_ROOT_POINTAGE/phpinfo.php$argument\">                           					 <img src=\"$URL_IMAGES/menu_courrier.png\" $menuIconSize > Conf. PHP     </a></li>
			<li><a title=\"Configuration \"             href=\"$URL_ROOT_POINTAGE/admin/configuration_db.php$argument\">                         		 <img src=\"$URL_IMAGES/menu_courrier.png\" $menuIconSize > Configuration </a></li>
			";
		}
		echo "
			<li><a title=\"Dernier Acces\"          href=\"$URL_ROOT_POINTAGE/admin/acces.php$argument\">             <img src=\"$URL_IMAGES/last_acces.png\" $menuIconSize > Derniers Acc&egrave;s         </a></li>
			<li><a title=\"Historique Acces\"       href=\"$URL_ROOT_POINTAGE/admin/historique_acces.php$argument\">  <img src=\"$URL_IMAGES/history_acces.png\" $menuIconSize > Historique Acc&egrave;s       </a></li>
			<li><a title=\"Versions\"          		href=\"$URL_ROOT_POINTAGE/admin/version.php$argument&order=order\">  <img src=\"$URL_IMAGES/version.png\" $menuIconSize >Versions</a></li>
			</ul>
			</div>";
	}
	
	//Developpement
	if (isMemberGroup(2) || isMemberGroup(3)){
		//creation balise <id>
		createHeaderBaliseDiv("id_div_dev","<h2>Dev.</h2>");
	}
		
	if (isMemberGroup(2)){
		echo"
		<ul>
		<li><a title=\"Tests graphiques\"  href=\"$URL_ROOT_POINTAGE/pChart/default.php$argument\">  <img src=\"$URL_IMAGES/histogram.png\" $menuIconSize >Tests Graphiques</a></li>
		<li><a title=\"Table Requetes\"          href=\"$URL_ROOT_POINTAGE/test/testRequetes.php$argument&order=order\">       <img src=\"$URL_IMAGES/version.png\" $menuIconSize >Requetes Generique</a></li>
		<li><a title=\"Requetes CEGID\"          href=\"$URL_ROOT_POINTAGE/test/testRequetesCEGID.php$argument&order=order\">  <img src=\"$URL_IMAGES/version.png\" $menuIconSize >Requetes CEGID</a></li>
		<li><a title=\"Tableaux\"          href=\"$URL_ROOT_POINTAGE/test/testTableaux.php$argument&order=order\">  <img src=\"$URL_IMAGES/histogram.png\" $menuIconSize >Tableaux</a></li>
		</ul>
		";
	}
	
	if (isMemberGroup(2) || isMemberGroup(3)){
		echo"</div>";
	}
} // end else id==""
 
echo "</div>"; // end div menu
endHeaderBaliseDiv("block_menu");


?>
