<?php

$FORM_VALUE_POSSIBLE ["<formulaire>"]["<variable>"]="ma requete sql";   //requete pour une liste de choix
$FORM_VALUE_DEFAULT  ["<formulaire>"]["<variable>"]="ma requete sql";    //requete pour une valeur par defaut
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["SQL"]="ma requete sql avec WHERE <variable2>=\"???\"";
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["VARIABLE"]="<variable3>";
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["DEFAULT"]="une string";


//primary
$PRIMARY_TABLE [ "cegid_pointage_voulu" ] = "PROJECT_ID,DATE,USER_ID,PROFIL";


//table cegid_user
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["PROFIL"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["PROFIL"]="select ID from cegid_profil";

//table cegid_project
$FORM_VALUE_POSSIBLE["form_table_cegid_project_insert"]["STATUS"]="select ID from cegid_status_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_update"]["STATUS"]="select ID from cegid_status_project";

 

//table project cout
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROJECT"]="select name from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROFIL_ID"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROFIL"]="select NAME from cegid_profil";
//$FORM_VALUE_DEFAULT["form_table_cegid_project_cout_insert"]["PROJECT_ID"]="select P002";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT_ID"]["VARIABLE"]="project";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT"]["SQL"]="select NAME from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT"]["VARIABLE"]="project";

$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["VARIABLE"]="PROJECT";


//table cegid_pointage
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["PROFIL"]="select ID from cegid_profil";

$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROJECT"]="select NAME from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["NAME"]="select NAME from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROFIL"]="select ID from cegid_profil";

$FORM_VALUE_DEFAULT["form_table_cegid_pointage_insert"]["DATE"]="select now()";

$FORM_VALUE_INSERT["form_table_cegid_pointage_update"]["USER_ID"]["SQL"]="select ID from cegid_user WHERE NAME=\"???\"";
$FORM_VALUE_INSERT["form_table_cegid_pointage_update"]["USER_ID"]["VARIABLE"]="NAME";

//table cegid_pointage2
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROJECT"]="select NAME from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROFIL"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["NAME"]="select NAME from cegid_user";

$FORM_VALUE_DEFAULT["form_table_cegid_pointage2_insert"]["DATE"]="select now()";



$FORM_VALUE_DEFAULT["form_table_cegid_pointage2_insert"]["DATE"]="select now()";

//form_select_project_pointage
$FORM_VALUE_POSSIBLE["form_select_project_pointage"]["year"]="select distinct year(DATE) from cegid_pointage order by DATE";
$FORM_VALUE_POSSIBLE["form_select_project_pointage"]["user"]="select NAME from cegid_user order by NAME";


$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["USER_ID"]["SQL"]="select ID from cegid_user  WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["USER_ID"]["VARIABLE"]="NAME";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project  WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT_ID"]["VARIABLE"]="PROJECT";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT"]["SQL"]="select NAME from cegid_project  WHERE CEGID=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT"]["VARIABLE"]="PROJECT_ID";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["NAME"]["SQL"]="select NAME from cegid_user  WHERE ID=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["NAME"]["VARIABLE"]="USER_ID";
//form project cout
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"] ["PROJECT"]="select NAME from cegid_project";

$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"]["PROFIL_ID"]="select ID from cegid_profil";
//$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"] ["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"] ["PROJECT"]="select NAME from cegid_project";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["VARIABLE"]="PROJECT";

//form_table_cegid_pointage_voulu
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["DATE"]["SQL"]="select \"???-01-01\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["DATE"]["VARIABLE"]="year";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO"]["SQL"]="select \"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO"]["VARIABLE"]="UO_voulu";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO_voulu"]["DEFAULT"]="NULL";



?>