<?php


include_once 'table_db.php';
include_once 'project_db.php';
include_once 'time.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once 'pointage_cegid_db.php';

$SQL_TABLE_CEGID_POINTAGE_VOULU = "cegid_pointage_voulu";
$FORM_TABLE_CEGID_POINTAGE_VOULU = "form_table_cegid_pointage_voulu";

global $SQL_COL_PROJECT_ID_CEGID_POINTAGE;// = "PROJECT_ID";
global $SQL_COL_DATE_CEGID_POINTAGE;// = "DATE";
global $SQL_COL_USER_CEGID_POINTAGE;// = "USER_ID";
global $SQL_COL_PROFIL_CEGID_POINTAGE;// = "PROFIL";
global $SQL_COL_UO_CEGID_POINTAGE;// = "UO";
$SQL_COL_UO_CEGID_POINTAGE_VOULU = "UO";
$SQL_LABEL_UO_CEGID_POINTAGE_VOULU = "UO.voulu";

$SQL_SHOW_COL_CEGID_POINTAGE = "$SQL_COL_PROJECT_ID_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, $SQL_COL_USER_CEGID_POINTAGE, $SQL_COL_UO_VOULU_CEGID_POINTAGE";


/**
 * addPrevisionnelVoulu
 * ajoute la colonne U.O.prevu au tableau
 * @param array sql result $tableau
 * @return array
 */
function addPrevisionnelVoulu($tableau){
    showAction("function pointage_voulu_cegid_db.addPrevisionnelVoulu()");
    
    global $SQL_COL_PROJECT_ID_CEGID_POINTAGE;// = "PROJECT_ID";
    global $SQL_COL_DATE_CEGID_POINTAGE;// = "DATE";
    global $SQL_COL_USER_CEGID_POINTAGE;// = "USER_ID";
    global $SQL_COL_PROFIL_CEGID_POINTAGE;// = "PROFIL";
    global $SQL_COL_UO_CEGID_POINTAGE;// = "UO";
    
    $year = getURLYear ();
    
    
    global $SQL_COL_UO_CEGID_POINTAGE_VOULU;
    global $SQL_LABEL_UO_CEGID_POINTAGE_VOULU;
    global $SQL_TABLE_CEGID_POINTAGE_VOULU;
    $colUOVoulu = $SQL_LABEL_UO_CEGID_POINTAGE_VOULU;
    $result = $tableau;
    $result = setSQLFlagType ( $result, $colUOVoulu, SQL_TYPE::SQL_REQUEST );
    //$result = setSQLFlagType ( $result, $colUOVoulu, SQL_TYPE::SQL_STRING );
    $result = setSQLFlagTypeSize ( $result, $colUOVoulu, 3 );
    $result = setSQLFlagStatus ( $result, $colUOVoulu, "enable" );
    
    
    $nbRes = mysqlNumrows ( $tableau );
    
    //showSQLAction("showTableCoutProject() tablePointage '$tablePointage'");
    
    for($cpt = 0; $cpt < $nbRes; $cpt ++) {
        $resultUser = mysqlResult ( $tableau, $cpt, "$SQL_COL_USER_CEGID_POINTAGE");
        
        $txt = "select $SQL_COL_UO_CEGID_POINTAGE_VOULU from $SQL_TABLE_CEGID_POINTAGE_VOULU ".
            " where $SQL_COL_PROFIL_CEGID_POINTAGE   ='". mysqlResult ( $tableau, $cpt, "$SQL_COL_PROFIL_CEGID_POINTAGE" )."'".
            " AND year($SQL_COL_DATE_CEGID_POINTAGE) = '".$year."'".
            " AND $SQL_COL_USER_CEGID_POINTAGE = '". $resultUser."'".
            " AND $SQL_COL_PROJECT_ID_CEGID_POINTAGE ='". mysqlResult($tableau, $cpt, "$SQL_COL_PROJECT_ID_CEGID_POINTAGE") ."'" ;
        $result[$colUOVoulu] [$cpt] = $txt;
        //$result[$colUOVoulu] [$cpt] = "ok";
        //showSQLAction("request [$colUOVoulu] [$cpt] =  $txt");
    }
    return $result;
}


?>