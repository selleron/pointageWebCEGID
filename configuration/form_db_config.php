<?php

$FORM_VALUE_POSSIBLE ["<formulaire>"]["<variable>"]="ma requete sql";    //requete pour une liste de choix
$FORM_VALUE_DEFAULT  ["<formulaire>"]["<variable>"]="ma requete sql";    //requete pour une valeur par defaut
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["SQL"]="ma requete sql avec WHERE <variable2>=\"???\"";
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["VARIABLE"]="<variable3>";  
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["DEFAULT"]="une string";    // valeur par defaut 
$FORM_VALUE_INSERT   ["<formulaire>"]["<variable>"]["TYPE"]="xxxx";             // type de la colonne sql SQL_TYPE::XXX
$FORM_STYLE          ["<formulaire>"]["<variable>"]["SIZE"]="<size cellule>";
$FORM_STYLE          ["<formulaire>"]["<variable>"]["STATUS"]= "disabled" | "enabled";
//$FORM_STYLE          ["<formulaire>"]["<variable>"]["TYPE"]= "string" | "number"  | "url";
//$FORM_STYLE          ["<formulaire>"]["<variable>"]["SUFFIX"]= "<le suffix>";

//condition selection archivage
$CONDITION_FROM_CEGID_NO_ARCHIVE = "VISIBLE LIKE 'Visible'";
$CONDITION_FROM_CEGID_PROJECT = $CONDITION_FROM_CEGID_NO_ARCHIVE;
$CONDITION_FROM_CEGID_DEVIS   = $CONDITION_FROM_CEGID_NO_ARCHIVE;
$CONDITION_FROM_CEGID_USER    = $CONDITION_FROM_CEGID_NO_ARCHIVE;

//selection
$SELECT_NAME_FROM_CEGID_USER_NO_FILTRED     = "select NAME from cegid_user";
$SELECT_NAME_FROM_CEGID_USER                = "$SELECT_NAME_FROM_CEGID_USER_NO_FILTRED WHERE $CONDITION_FROM_CEGID_USER";
$SELECT_NAME_FROM_CEGID_PROJECT_NO_FILTRED  = "select NAME from cegid_project";
$SELECT_NAME_FROM_CEGID_PROJECT             = "select NAME from cegid_project WHERE $CONDITION_FROM_CEGID_PROJECT";
$SELECT_NAME_AND_ALL_FROM_CEGID_PROJECT     = "select '[all]' as NAME union ($SELECT_NAME_FROM_CEGID_PROJECT)";
$SELECT_NAME_FROM_CEGID_SOCIETE_CLIENT      = "select NAME from cegid_societe_client order by NAME";
$SELECT_NAME_FROM_CEGID_SOCIETE_FOURNISSEUR = "select NAME from cegid_societe_fournisseur order by NAME";
$SELECT_ID_FROM_CEGID_STATUS_VISIBLE        = "select ID from cegid_status_visible";

//formatage
//$ALIGN_RIGHT = " align='right' ";
$ALIGN_LEFT  = " align='left' ";
$ALIGN_RIGHT = "style=\"text-align:right;\"";

$NUMBER_NEGATIF_ROUGE = "if (\$res<0){\$format =\"bgcolor='#FFAAAA'\";}else{\$format=\"\";} ";
$FORMAT_UO     = "\$res=numberFormat(\$res,1,'.','');";
$FORMAT_CA     = "\$res=numberFormat(\$res,0,'.',' ');";
$FORMAT_TARIF  = "\$res=numberFormat(\$res,2,'.',' ');";
$SIZE_UO    =  5;
$SIZE_COUT  =  7;
$SIZE_COUT_EURO  =  10;
$WIDTH_CA_EURO = 50; //size du td ca + €
$SIZE_COUT_SUM = 8; //plus petit que $SIZE_COUT pour pouvoir ajouter €
$SIZE_CA    = 10;

//select project Tool Bar
$FORM_VALUE_POSSIBLE["form_select_project_pointage"]["NAME"] = $SELECT_NAME_AND_ALL_FROM_CEGID_PROJECT;
$FORM_VALUE_POSSIBLE["form_select_project_pointage"]["year"] = "select distinct year(DATE) from cegid_pointage_previsionnel order by DATE";
$FORM_VALUE_POSSIBLE["form_select_project_pointage"]["user"] = "$SELECT_NAME_FROM_CEGID_USER order by NAME";
$FORM_VALUE_POSSIBLE["form_select_table_cegid_devis_project"]["NAME"]="select NAME from cegid_devis_project WHERE $CONDITION_FROM_CEGID_DEVIS";
$FORM_VALUE_POSSIBLE["form_select_table_cegid_user"]["NAME"]="$SELECT_NAME_FROM_CEGID_USER";


//primary
$PRIMARY_TABLE [ "cegid_pointage_voulu" ] = "PROJECT_ID,DATE,USER_ID,PROFIL";
$PRIMARY_TABLE [ "cegid_proposition" ] = "ID";

//table cegid_file
$FORM_STYLE["form_table_cegid_file_insert"]["REFERENCE"]["SIZE"]=30;
$FORM_STYLE["form_table_cegid_file_update"]["REFERENCE"]["SIZE"]=30;
$FORM_STYLE["form_table_cegid_file_insert"]["CODE"]["SIZE"]=15;
$FORM_VALUE_POSSIBLE["form_table_cegid_file_insert"]["CODE"]="select ID from cegid_file_code order by ORDRE";
$FORM_STYLE["form_table_cegid_file_update"]["CODE"]["SIZE"]=15;
$FORM_VALUE_POSSIBLE["form_table_cegid_file_update"]["CODE"]="select ID from cegid_file_code order by ORDRE";
$FORM_STYLE["form_table_cegid_file_insert"]["title"]["SIZE"]=120;
$FORM_VALUE_POSSIBLE["form_table_cegid_file_insert"]["title"]="select TITLE from files";
$FORM_STYLE["form_table_cegid_file_update"]["title"]["SIZE"]=120;
$FORM_VALUE_POSSIBLE["form_table_cegid_file_update"]["title"]="select TITLE from files";
$FORM_STYLE["form_table_cegid_file_insert"]["COMMENTAIRE"]["SIZE"]=40;
$FORM_STYLE["form_table_cegid_file_update"]["COMMENTAIRE"]["SIZE"]=40;
$FORM_VALUE_POSSIBLE["form_table_cegid_file_insert"]["FILE"]="select ID from files";
$FORM_VALUE_POSSIBLE["form_table_cegid_file_update"]["FILE"]="select ID from files";
$FORM_VALUE_INSERT ["form_table_cegid_file_update"]["FILE"]["SQL"]="select ID from files where title=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_file_update"]["FILE"]["VARIABLE"]="title";
$FORM_STYLE["form_table_cegid_file_insert"]["FILE"]["STATUS"]="disabled";
$FORM_STYLE["form_table_cegid_file_update"]["FILE"]["STATUS"]="disabled";
$FORM_STYLE["form_table_cegid_file_update"]["link"]["SIZE"]=100;

$FORM_STYLE["files_insert"]["id"]["SIZE"]=4;
$FORM_STYLE["files_insert"]["title"]["SIZE"]=70;
$FORM_STYLE["files_insert"]["name"]["SIZE"]=70;
$FORM_STYLE["files_insert"]["mime"]["SIZE"]=17;
$FORM_STYLE["files_insert"]["link"]["SIZE"]=80;


//table cegid_user
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["PROFIL"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["PROFIL"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["STATUS"]="select ID from cegid_status_cegid order by ORDRE";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["STATUS"]="select ID from cegid_status_cegid order by ORDRE";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["GROUPE"]="select distinct GROUPE from cegid_user order by GROUPE";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["GROUPE"]=$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["GROUPE"];
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["TEAM"]="select distinct TEAM from cegid_user order by TEAM";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["TEAM"]=$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["TEAM"];
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["LOCALISATION"]="select distinct LOCALISATION from cegid_user order by LOCALISATION";
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["LOCALISATION"]=$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["LOCALISATION"];
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["SOCIETE"]=$SELECT_NAME_FROM_CEGID_SOCIETE_FOURNISSEUR;
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["SOCIETE"]=$SELECT_NAME_FROM_CEGID_SOCIETE_FOURNISSEUR;
$FORM_VALUE_POSSIBLE["form_table_cegid_user_insert"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_POSSIBLE["form_table_cegid_user_update"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_INSERT ["form_table_cegid_user_insert"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_VALUE_INSERT ["form_table_cegid_user_update"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_VALUE_INSERT ["form_table_cegid_user_update"]["DEPART"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_user_insert"]["DEPART"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_user_update"]["ARRIVEE"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_user_insert"]["ARRIVEE"]["TYPE"]="string_null";


$FORM_STYLE["form_table_cegid_user_insert"]["GROUPE"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["GROUPE"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_insert"]["GROUPE"]["CB_NO_SELECTION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["GROUPE"]["CB_NO_SELECTION"]="yes";
$FORM_STYLE["form_table_cegid_user_insert"]["TEAM"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["TEAM"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_insert"]["TEAM"]["CB_NO_SELECTION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["TEAM"]["CB_NO_SELECTION"]="yes";
$FORM_STYLE["form_table_cegid_user_insert"]["LOCALISATION"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["LOCALISATION"]["CB_COMPLETION"]="yes";
$FORM_STYLE["form_table_cegid_user_insert"]["LOCALISATION"]["CB_NO_SELECTION"]="yes";
$FORM_STYLE["form_table_cegid_user_update"]["LOCALISATION"]["CB_NO_SELECTION"]="yes";


$FORM_STYLE["form_table_cegid_user_update"]["NAME"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["NOM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["PRENOM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["EMAIL1"]["SIZE"] = "60";
$FORM_STYLE["form_table_cegid_user_update"]["EMAIL2"]["SIZE"] = "60";
$FORM_STYLE["form_table_cegid_user_update"]["TEL1"]["SIZE"] = "20";
$FORM_STYLE["form_table_cegid_user_update"]["TEL2"]["SIZE"] = "20";
$FORM_STYLE["form_table_cegid_user_update"]["GROUPE"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["TEAM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["LOCALISATION"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_update"]["CEGID_ID"]["SIZE"] = "30";

$FORM_STYLE["form_table_cegid_user_insert"]["NAME"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["NOM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["PRENOM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["EMAIL1"]["SIZE"] = "60";
$FORM_STYLE["form_table_cegid_user_insert"]["EMAIL2"]["SIZE"] = "60";
$FORM_STYLE["form_table_cegid_user_insert"]["TEL1"]["SIZE"] = "20";
$FORM_STYLE["form_table_cegid_user_insert"]["TEL2"]["SIZE"] = "20";
$FORM_STYLE["form_table_cegid_user_insert"]["GROUPE"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["TEAM"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["LOCALISATION"]["SIZE"] = "30";
$FORM_STYLE["form_table_cegid_user_insert"]["CEGID_ID"]["SIZE"] = "30";


//table cegid_devis
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["CEGID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["STATUS_DEVIS"]="select ID from cegid_status_devis order by ORDRE";
$FORM_VALUE_DEFAULT ["form_table_cegid_devis_insert"]["STATUS_DEVIS"]="select \"A faire\"";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["STATUS_CEGID"]="select ID from cegid_status_cegid order by ORDRE";
$FORM_VALUE_DEFAULT ["form_table_cegid_devis_insert"]["STATUS_CEGID"]="select \"Neant\"";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["STATUS_COMMANDE"]="select ID from cegid_status_commande order by ORDRE";
$FORM_VALUE_DEFAULT ["form_table_cegid_devis_insert"]["STATUS_COMMANDE"]="select \"Neant\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_devis_insert"]["VERSION"]="select \"1.0\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_devis_insert"]["MODIFICATION"]="select \"0\"";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_INSERT ["form_table_cegid_devis_insert"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_insert"]["SOCIETE"]=$SELECT_NAME_FROM_CEGID_SOCIETE_CLIENT;


$FORM_STYLE         ["form_table_cegid_devis_insert"]["NAME"]["SIZE"]=50;
$FORM_STYLE         ["form_table_cegid_devis_insert"]["COMMANDE"]["SIZE"]=35;
$FORM_STYLE         ["form_table_cegid_devis_insert"]["MODIFICATION"]["SIZE"]=5;
$FORM_STYLE         ["form_table_cegid_devis_insert"]["VERSION"]["SIZE"]=5;

$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["CEGID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["STATUS_DEVIS"]="select ID from cegid_status_devis order by ORDRE";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["STATUS_CEGID"]="select ID from cegid_status_cegid order by ORDRE";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["STATUS_COMMANDE"]="select ID from cegid_status_commande order by ORDRE";
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_POSSIBLE["form_table_cegid_devis_update"]["SOCIETE"]=$SELECT_NAME_FROM_CEGID_SOCIETE_CLIENT;

$FORM_VALUE_INSERT ["form_table_cegid_devis_update"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_STYLE        ["form_table_cegid_devis_update"]["NAME"]["SIZE"]=50;
$FORM_STYLE        ["form_table_cegid_devis_update"]["NUXEO"]["SIZE"]=100;
$FORM_STYLE        ["form_table_cegid_devis_update"]["CEGID"]["CB_COMPLETION"]="yes";

//table cegid project
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["STATUS"]["DEFAULT"]="Prevision";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["TYPE"]["DEFAULT"]="Undefined";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["COMMENTAIRE"]["DEFAULT"]="";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["STATUS"]["DEFAULT"]="Prevision";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["TYPE"]["DEFAULT"]="Undefined";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["COMMENTAIRE"]["DEFAULT"]="";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["VISIBLE"]["DEFAULT"]="Visible";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["FIN_GARANTIE"]["TYPE"]="date";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["DEBUT"]["TYPE"]="date";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["FIN"]["TYPE"]="date";
$FORM_VALUE_INSERT ["form_table_cegid_project_update"]["PRIX_VENTE"]["TYPE"]="real";

$FORM_VALUE_POSSIBLE["form_table_cegid_project_insert"]["STATUS"]="select ID from cegid_status_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_update"]["STATUS"]="select ID from cegid_status_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project"]["STATUS"]="select ID from cegid_status_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_insert"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_POSSIBLE["form_table_cegid_project_update"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_POSSIBLE["form_table_cegid_project"]["VISIBLE"]=$SELECT_ID_FROM_CEGID_STATUS_VISIBLE;
$FORM_VALUE_POSSIBLE["form_table_cegid_project_insert"]["TYPE"]="select ID from cegid_type_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_update"]["TYPE"]="select ID from cegid_type_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project"]["TYPE"]="select ID from cegid_type_project";
$FORM_STYLE         ["form_table_cegid_project_insert"]["PRIX_VENTE"]["SIZE"]=10;
$FORM_STYLE         ["form_table_cegid_project_insert"]["NAME"]["SIZE"]=55;
$FORM_STYLE         ["form_table_cegid_project_update"]["NAME"]["SIZE"]=55;
$FORM_STYLE         ["form_table_cegid_project_insert"]["CEGID"]["SIZE"]=10;
$FORM_STYLE         ["form_table_cegid_project_update"]["CEGID"]["SIZE"]=10;
$FORM_STYLE         ["form_table_cegid_project_insert"]["GROUPE"]["SIZE"]=20;
$FORM_STYLE         ["form_table_cegid_project_update"]["GROUPE"]["SIZE"]=55;


//table project cout
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROFIL_ID"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"]["PROFIL"]="select NAME from cegid_profil";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT_ID"]["VARIABLE"]="project";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT"]["SQL"]="select NAME from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_project_cout_insert"]["PROJECT"]["VARIABLE"]="project";

$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["VARIABLE"]="PROJECT";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["ID"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["DATE"]["TYPE"]="date";


$FORM_STYLE["form_table_cegid_project_cout_insert"]["ID"]["SIZE"] = 5;
//ne marche pas : $FORM_STYLE["form_table_cegid_project_cout_insert"]["DATE"]["WIDTH"] = 10;
//$FORM_STYLE["form_table_cegid_project_cout_insert"]["DATE"]["SIZE"] = 5;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COMMENTAIRE"]["SIZE"] = 40;
$FORM_STYLE["form_table_cegid_project_cout_update"]["COMMENTAIRE"]["SIZE"] = 40;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["UO"]["SIZE"] = $SIZE_UO;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["UO"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["UO"]["FORMAT"] = $FORMAT_UO;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["SIZE"] = $SIZE_COUT;
//$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["SIZE_FIELD"] = $SIZE_COUT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["SUFFIX_FIELD"] = "";
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["COUT"]["FORMAT"] = $FORMAT_TARIF;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["U.O.Pointage"]["SIZE"] = $SIZE_CA;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["U.O.Pointage"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Total.COUT"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Total.COUT"]["SIZE"] = $SIZE_COUT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Total.COUT"]["WITDH"] = $WIDTH_CA_EURO;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Total.COUT"]["FORMAT"] = $FORMAT_TARIF;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["U.O.Pointage"]["SUFFIX"] = " &euro;"; //bug inversion
//$FORM_STYLE["form_table_cegid_project_cout_insert"]["Total.COUT"]["SUFFIX"] = " &euro;"; //bug inversion
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Reel.COUT"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Reel.COUT"]["SIZE"] = $SIZE_COUT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Reel.COUT"]["WITDH"] = $WIDTH_CA_EURO;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["Reel.COUT"]["SUFFIX"] = " &euro;";

$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_UO"]["SIZE"]           = 6;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Total.COUT"]["SIZE"]   = $SIZE_COUT_SUM;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_U.O.Pointage"]["SIZE"] = 8;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Reel.COUT"]["SIZE"]    = $SIZE_COUT_SUM  ;

$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_UO"]["TD"]           = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Total.COUT"]["TD"]   = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_U.O.Pointage"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Reel.COUT"]["TD"]    = $ALIGN_RIGHT;

$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Total.COUT"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_project_cout_insert"]["sum_col_Reel.COUT"]["SUFFIX"]  = " &euro;";


//table frais mission
$FORM_VALUE_POSSIBLE["form_table_cegid_frais_mission_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_DEFAULT ["form_table_cegid_frais_mission_insert"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_frais_mission_insert"]["PROJECT_ID"]["VARIABLE"]="project";

$FORM_VALUE_POSSIBLE["form_table_cegid_frais_mission_insert"]["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_POSSIBLE["form_table_cegid_frais_mission_update"]["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_DEFAULT ["form_table_cegid_frais_mission_insert"]["PROJECT"]["SQL"]="select NAME from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_DEFAULT ["form_table_cegid_frais_mission_insert"]["PROJECT"]["VARIABLE"]="project";
$FORM_VALUE_DEFAULT ["form_table_cegid_frais_mission_insert"]["DATE"]="select now()";

$FORM_VALUE_INSERT ["form_table_cegid_frais_mission_update"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_frais_mission_update"]["PROJECT_ID"]["VARIABLE"]="PROJECT";
$FORM_VALUE_INSERT ["form_table_cegid_frais_mission_update"]["ID"]["SQL"]="select concat(\"FM_\", p.CEGID, \"_\", fm.c) from (select CEGID from cegid_project where NAME=\"???\") p, (select count(ID) as c from cegid_frais_mission) fm";
$FORM_VALUE_INSERT ["form_table_cegid_frais_mission_update"]["ID"]["VARIABLE"]="PROJECT";



$FORM_STYLE["form_table_cegid_frais_mission_insert"]["TITRE"]["SIZE"] = 40;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS_EN_LOCAL"]["SIZE"] = 10;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["COMMENTAIRE"]["SIZE"] = 60;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["ID"]["SIZE"] = 15;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["SIZE"] = $SIZE_COUT;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["WIDTH"] = $WIDTH_CA_EURO;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["SUFFIX_FIELD"] = " &euro;";
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["FRAIS"]["FORMAT"] = $FORMAT_TARIF;

$FORM_STYLE["form_table_cegid_frais_mission_insert"]["sum_col_FRAIS"]["SIZE"] = $SIZE_COUT_SUM;
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["sum_col_FRAIS"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_frais_mission_insert"]["sum_col_FRAIS"]["TD"] = $ALIGN_RIGHT;




$FORM_STYLE["form_table_cegid_frais_mission_update"]["TITRE"]["SIZE"] = 40;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS_EN_LOCAL"]["SIZE"] = 10;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["ID"]["SIZE"] = 15;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["PROJECT"]["SIZE"] = 50;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["COMMENTAIRE"]["SIZE"] = 60;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS"]["SIZE"] = $SIZE_COUT;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS"]["SUFFIX_FIELD"] = " &euro;";
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_frais_mission_update"]["FRAIS"]["FORMAT"] = $FORMAT_TARIF;



//table cegid_pointage
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_insert"]["PROFIL"]="select ID from cegid_profil";

$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["NAME"]="select NAME from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage_update"]["PROFIL"]="select ID from cegid_profil";

$FORM_VALUE_DEFAULT["form_table_cegid_pointage_insert"]["DATE"]="select now()";

$FORM_VALUE_INSERT["form_table_cegid_pointage_update"]["USER_ID"]["SQL"]="select ID from cegid_user WHERE NAME=\"???\"";
$FORM_VALUE_INSERT["form_table_cegid_pointage_update"]["USER_ID"]["VARIABLE"]="NAME";

//table cegid_pointage2
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["USER_ID"]="select ID from cegid_user";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["PROFIL"]="select ID from cegid_profil";
$FORM_VALUE_POSSIBLE["form_table_cegid_pointage2_insert"]["NAME"]=$SELECT_NAME_FROM_CEGID_USER;

$FORM_VALUE_DEFAULT["form_table_cegid_pointage2_insert"]["DATE"]="select now()";
$FORM_VALUE_DEFAULT["form_table_cegid_pointage2_insert"]["DATE"]="select now()";



$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["USER_ID"]["SQL"]="select ID from cegid_user  WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["USER_ID"]["VARIABLE"]="NAME";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project  WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT_ID"]["VARIABLE"]="PROJECT";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT"]["SQL"]="select NAME from cegid_project  WHERE CEGID=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["PROJECT"]["VARIABLE"]="PROJECT_ID";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["NAME"]["SQL"]="select NAME from cegid_user  WHERE ID=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_replace"]["NAME"]["VARIABLE"]="USER_ID";


//form project cout
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_insert"] ["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;

$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"]["PROFIL_ID"]="select ID from cegid_profil";
//$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"] ["PROJECT_ID"]="select CEGID from cegid_project";
$FORM_VALUE_POSSIBLE["form_table_cegid_project_cout_update"] ["PROJECT"]=$SELECT_NAME_FROM_CEGID_PROJECT;
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["SQL"]="select CEGID from cegid_project WHERE NAME=\"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_project_cout_update"]["PROJECT_ID"]["VARIABLE"]="PROJECT";

//form_table_cegid_pointage_voulu
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["DATE"]["SQL"]="select \"???-01-01\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["DATE"]["VARIABLE"]="year";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO"]["SQL"]="select \"???\"";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO"]["VARIABLE"]="UO_voulu";
$FORM_VALUE_INSERT ["form_table_cegid_pointage_voulu"]["UO_voulu"]["DEFAULT"]="NULL";



$FORM_STYLE["UO_RESTANT_CLOTURE"]["COUT"]["SUFFIX"] = " &euro;";
$FORM_STYLE["UO_RESTANT_CLOTURE"]["COUT"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["COUT"]["FORMAT"] = $FORMAT_TARIF;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_possible"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_possible"]["FORMAT"] = $FORMAT_UO;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_consomme"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_consomme"]["FORMAT"] = $FORMAT_UO;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_restant"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_restant"]["FORMAT"] = $FORMAT_UO;
$FORM_STYLE["UO_RESTANT_CLOTURE"]["UO_restant"]["TD_EVAL"] = "$NUMBER_NEGATIF_ROUGE";

$FORM_STYLE["CHECK_PRIX_VENTE"]["CA"]["SUFFIX"] = " &euro;";
$FORM_STYLE["CHECK_PRIX_VENTE"]["CA"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["CHECK_PRIX_VENTE"]["CA"]["FORMAT"] = $FORMAT_CA;
$FORM_STYLE["CHECK_PRIX_VENTE"]["PRIX_VENTE"]["SUFFIX"] = " &euro;";
$FORM_STYLE["CHECK_PRIX_VENTE"]["PRIX_VENTE"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["CHECK_PRIX_VENTE"]["PRIX_VENTE"]["FORMAT"] = $FORMAT_CA;
$FORM_STYLE["CHECK_PRIX_VENTE"]["DIFF"]["SUFFIX"] = " &euro;";
$FORM_STYLE["CHECK_PRIX_VENTE"]["DIFF"]["TD"] = " align='right' bgcolor='#FFAAAA' ";
$FORM_STYLE["CHECK_PRIX_VENTE"]["DIFF"]["FORMAT"] = $FORMAT_TARIF;


$FORM_STYLE["PRIX_VENTE"]["CA"]["SUFFIX"] = " &euro;";
$FORM_STYLE["PRIX_VENTE"]["CA"]["TD"]     = $ALIGN_RIGHT;
$FORM_STYLE["PRIX_VENTE"]["CA"]["FORMAT"] = $FORMAT_CA;
$FORM_STYLE["PRIX_VENTE"]["PRIX_VENTE"]["SUFFIX"] = " &euro;";
$FORM_STYLE["PRIX_VENTE"]["PRIX_VENTE"]["TD"]     = $ALIGN_RIGHT;
$FORM_STYLE["PRIX_VENTE"]["PRIX_VENTE"]["FORMAT"] = $FORMAT_CA;
$FORM_STYLE["PRIX_VENTE"]["UO_possible"]["TD"]     = $ALIGN_RIGHT;
$FORM_STYLE["PRIX_VENTE"]["UO_possible"]["FORMAT"] = $FORMAT_UO;


$FORM_STYLE ["form_table_file_code_insert"]["ID"]["SIZE"]=15;
$FORM_STYLE ["form_table_file_code_insert"]["NAME"]["SIZE"]=50;


//societe client
$FORM_STYLE["form_cegid_societe_client_insert"]["ID"]["WIDTH"]   = "30";
$FORM_STYLE["form_cegid_societe_client_insert"]["NAME"]["WIDTH"] = "70";
$FORM_STYLE["form_cegid_societe_client_insert"]["ID"]["SIZE"]   = "30";
$FORM_STYLE["form_cegid_societe_client_insert"]["NAME"]["SIZE"] = "70";

$FORM_STYLE["form_cegid_societe_client_update"]["ID"]["SIZE"]   = "30";
$FORM_STYLE["form_cegid_societe_client_update"]["NAME"]["SIZE"] = "70";


//societe fournisseur
$FORM_STYLE["form_cegid_societe_fournisseur_insert"]["ID"]["WIDTH"]   = "30";
$FORM_STYLE["form_cegid_societe_fournisseur_insert"]["NAME"]["WIDTH"] = "70";
$FORM_STYLE["form_cegid_societe_fournisseur_insert"]["ID"]["SIZE"]   = "30";
$FORM_STYLE["form_cegid_societe_fournisseur_insert"]["NAME"]["SIZE"] = "70";

$FORM_STYLE["form_cegid_societe_fournisseur_update"]["ID"]["SIZE"]   = "30";
$FORM_STYLE["form_cegid_societe_fournisseur_update"]["NAME"]["SIZE"] = "70";




//suivi devis
$FORM_STYLE["form_table_cegid_devis"]["PRIX_VENTE"]["SUFFIX"] = " &euro;";
$FORM_STYLE["form_table_cegid_devis"]["PRIX_VENTE"]["SUFFIX_FIELD"] = "";
$FORM_STYLE["form_table_cegid_devis"]["PRIX_VENTE"]["TD"] = $ALIGN_RIGHT;
$FORM_STYLE["form_table_cegid_devis"]["PRIX_VENTE"]["FORMAT"] = $FORMAT_TARIF;

//suivi proposition
$FORM_VALUE_INSERT ["form_table_cegid_proposition_update"]["PRIX_VENTE"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_proposition_update"]["REUSSITE"]["TYPE"]="string_null";
$FORM_VALUE_INSERT ["form_table_cegid_proposition_annee_update"]["PRIX_VENTE"]["TYPE"]="string_null";


?>