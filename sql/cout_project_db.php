<?php
$COUT_PROJECT_DB_PHP = "loaded";

include_once 'project_db.php';
include_once 'pointage_cegid_db.php';

$SQL_TABLE_PROJECT_COUT        = "cegid_project_cout";
$SQL_TABLE_PROJECT_COUT2       = "cegid_project_cout as pc,  cegid_project as pj";
$FORM_TABLE_CEGID_PROJECT_COUT = "form_table_cegid_project_cout";

$SQL_COL_ID_PROJECT_COUT           = "ID";
$SQL_COL_DATE_PROJECT_COUT         = "DATE";
$SQL_COL_PROJECT_ID_COUT_PROJECT   = "PROJECT_ID";
$SQL_COL_PROJECT_NAME_COUT_PROJECT = "PROJECT";
$SQL_COL_PROJECT_COUT_PROFIL       = "PROFIL_ID";
$SQL_COL_UO_PROJECT_COUT           = "UO";
$SQL_COL_COUT_PROJECT_COUT         = "COUT";
$SQL_COL_COMMENTAIRE_PROJECT_COUT  = "COMMENTAIRE";

//utilisé???
$SQL_SHOW_COL_PROJECT_COUT   = "   $SQL_COL_ID_PROJECT_COUT,                                $SQL_COL_DATE_PROJECT_COUT,                                  $SQL_COL_PROJECT_ID_COUT_PROJECT,                                $SQL_COL_PROJECT_COUT_PROFIL,                                     $SQL_COL_COMMENTAIRE_PROJECT_COUT,    $SQL_COL_COUT_PROJECT_COUT,                                  $SQL_COL_UO_PROJECT_COUT";
//utilisé : oui
$SQL_SHOW_COL_PROJECT_COUT2  = "   $SQL_COL_ID_PROJECT_COUT,                                $SQL_COL_DATE_PROJECT_COUT,                                  $SQL_COL_PROJECT_NAME_COUT_PROJECT,                              $SQL_COL_PROJECT_COUT_PROFIL,                                     $SQL_COL_COMMENTAIRE_PROJECT_COUT,    $SQL_COL_COUT_PROJECT_COUT,                                  $SQL_COL_UO_PROJECT_COUT";
$SQL_SELECT_COL_COUT_PROJECT = "pc.$SQL_COL_ID_PROJECT_COUT as $SQL_COL_ID_PROJECT_COUT, pc.$SQL_COL_DATE_PROJECT_COUT as $SQL_COL_DATE_PROJECT_COUT, pj.$SQL_COL_NAME_PROJECT as $SQL_COL_PROJECT_NAME_COUT_PROJECT,  pc.$SQL_COL_PROJECT_COUT_PROFIL as $SQL_COL_PROJECT_COUT_PROFIL,  pc.$SQL_COL_COMMENTAIRE_PROJECT_COUT, pc.$SQL_COL_COUT_PROJECT_COUT as $SQL_COL_COUT_PROJECT_COUT, pc.$SQL_COL_UO_PROJECT_COUT as $SQL_COL_UO_PROJECT_COUT";
$SQL_SHOW_WHERE_COUT_PROJECT = "`$SQL_COL_ID_PROJECT` = `$SQL_COL_PROJECT_ID_COUT_PROJECT`	";

$SQL_COL_TOTAL_PROJECT_COUT      = "Total.COUT";
$SQL_COL_TOTAL_REEL_PROJECT_COUT = "Reel.COUT";
$SQL_COL_POINTAGE_PROJECT_COUT   = "U.O.Pointage";

// include_once 'connection_db.php';
// include_once 'tool_db.php';
include_once 'table_db.php';

/**
 * application des actions sur la page projet cout
 *
 * @param string $subParam
 * @return number 0 nothing 1 action traitée
 */
function applyGestionCoutProject($subParam="") {
    global $SQL_SHOW_COL_PROJECT_COUT;
    global $SQL_SHOW_COL_PROJECT_COUT2;
    global $SQL_SELECT_COL_COUT_PROJECT;
    global $SQL_SHOW_WHERE_COUT_PROJECT;
    global $SQL_TABLE_PROJECT_COUT;
    global $SQL_TABLE_PROJECT_COUT2;
    global $TABLE_FORM_NAME_INSERT;
    global $FORM_TABLE_CEGID_PROJECT_COUT;
    $form_name = $FORM_TABLE_CEGID_PROJECT_COUT . "_update";
    $condition="";
    
    //showSQLAction("applyGestionCoutProject() col filter : $SQL_SELECT_COL_COUT_PROJECT");
    
    
    //execution du edit
    $subParam = createDefaultParamSql ( $SQL_TABLE_PROJECT_COUT2, $SQL_SHOW_COL_PROJECT_COUT2, $condition );
    $subParam = updateTableParamSql ( $subParam, $form_name, $SQL_SELECT_COL_COUT_PROJECT );
    $subParam = updateParamSqlColumnFilter ( $subParam, $SQL_SELECT_COL_COUT_PROJECT );
    $subParam = updateParamSqlCondition($subParam, $SQL_SHOW_WHERE_COUT_PROJECT); //pour le join avec project_cegid
    $subParam = updateParamSqlConditionForUpdate($subParam, "1"); //pour ne pas tenir compte de la condition edit pour le update
    
    
    $res = editTableByGet ( /*$SQL_TABLE_PROJECT_COUT2, $SQL_SHOW_COL_PROJECT_COUT2, $form_name,*/ $subParam );
    
    if ($res <= 0) {
        //execution du update
        $condition="";
        $colFilter=NULL;
        $paramUpdate = createDefaultParamSql ( $SQL_TABLE_PROJECT_COUT, $SQL_SHOW_COL_PROJECT_COUT, $condition );
        $paramUpdate = updateTableParamSql ( $paramUpdate, $form_name, $colFilter );
        
        $res = updateTableByGet (/* $SQL_TABLE_PROJECT_COUT, $SQL_SHOW_COL_PROJECT_COUT, $form_name,*/ $paramUpdate, "no" /**re-edit */);
        //on re-affiche la table du edit
        if ($res) editTable2 ( /*$SQL_TABLE_PROJECT_COUT2, $SQL_SHOW_COL_PROJECT_COUT2, $form_name,*/ $subParam );
    }
    
    // cas classique : edit, export, ...
    if ($res <= 0) {
        $res = applyGestionTable ( $SQL_TABLE_PROJECT_COUT, $SQL_SHOW_COL_PROJECT_COUT, $form_name );
    }
    return $res;
}


/**
 * application des actions sur la page cout projet
 * on test en premier lieu par rapport au nom de la forme pour eviter
 * les actions concurrente sur les update, edit, insert,...
 * @return number 0 si pas d'action faite
 */
function applyGestionCoutProjectForm($subParam="") {
    global $TABLE_FORM_NAME_INSERT;
    global $FORM_TABLE_CEGID_PROJECT_COUT;
    $formI = $FORM_TABLE_CEGID_PROJECT_COUT . "_insert";
    $formU = $FORM_TABLE_CEGID_PROJECT_COUT . "_update";
    
    $formName = getURLVariable($TABLE_FORM_NAME_INSERT);
    showAction("applyGestionCoutProjectForm - form name found : $formName");
    if (($formName == $FORM_TABLE_CEGID_PROJECT_COUT) || ($formName == $formI)  || ($formName == $formU)){
        return applyGestionCoutProject($subParam);
    }
    
    return 0;
}

/**
 * applyGestionCoutOneProjectForm
 * prise en compte year, project user
 */
function applyGestionCoutOneProjectForm($subParam=""){
    
    //info form
    // ajout selection project et year
    $infoForm = getInfoForm($subParam);
    $infoForm = getInfoFormProjectSelection($infoForm);
    $subParam  = setInfoForm($subParam, $infoForm);
    
    return applyGestionCoutProjectForm($subParam);
}


/**
 * showTableCoutOneProject
 *
 * show table Cout project
 * prend en compte la selection du projet et de la date
 */
function showTableCoutOneProject($tablePointage="", $showOnlyOneProject = "yes") {
    $idBalise="tablePointage_$tablePointage";
    createHeaderBaliseDiv($idBalise,"<h3>Table des coûts</h3>");
    
    global $TRACE_INFO_PROJECT;
    //global $SQL_TABLE_PROJECT_COUT2;
    global $FORM_TABLE_CEGID_PROJECT_COUT;
    global $TABLE_FORM_NAME_INSERT;
    global $SQL_SHOW_COL_PROJECT;
    $form_name = $FORM_TABLE_CEGID_PROJECT_COUT/*."_one_update"*/;
    $project_found = "yes";
    
    
    //recuperation project name
    global $PROJECT_SELECTION;
    //global $ITEM_COMBOBOX_SELECTION;
    $projectName = getURLVariable ( $PROJECT_SELECTION );
    if ($projectName == FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION || $projectName == "") {
        showActionVariable( "No project Selected...", $TRACE_INFO_PROJECT );
        if ($showOnlyOneProject=="yes"){
            $projectName = "no project";
        }
        else{
            $projectName = "";
        }
        $project_found = "no";
    }
    if ($projectName == FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_ALL) {
        showActionVariable("[all] project detected for table cout, use name=''", $TRACE_INFO_PROJECT);
        $projectName = "";
        $project_found = "no";
    }
    
    // info formulaire year et project name
    $condition="";
    global $YEAR_SELECTION;
    $year = getURLYear(FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION);
    $infoForm = streamFormHidden ( $YEAR_SELECTION, $year );
    $infoForm = $infoForm . streamFormHidden ( $PROJECT_SELECTION, $projectName );
    $infoForm = $infoForm . streamFormHidden ( $TABLE_FORM_NAME_INSERT, $form_name );
    if (is_numeric($year)){
        global $SQL_COL_DATE_CEGID_POINTAGE;
        $condition = "year(pc.$SQL_COL_DATE_CEGID_POINTAGE)=\"$year\"";
    }
    
    
    //$projectId = getURLVariableSQLForm($variables, $form)
    global $SQL_COL_NAME_PROJECT;
    $keyProjectName="pj.$SQL_COL_NAME_PROJECT";
    if ($projectName==""){
        //nothing to do
    }
    else{
        $condition = createSqlWhere($keyProjectName, $projectName, $condition);
    }
    
    //echoTD(">>>> $condition");
    $subParam = "";
    $subParam  = updateParamSqlCondition($subParam, $condition);
    $subParam  = setInfoForm($subParam, $infoForm);
    if ($project_found == "no"){
        //echo "project not found....<br>";
        $subParam = setSQLFlagStatus($subParam, stringToArray($SQL_SHOW_COL_PROJECT), "disabled");
    }
    
    
    
    
    $param = prepareParamShowTableCoutProject ($subParam);
    
    //trace
    $req = createRequeteTableData ( $param );
    showSQLAction ( $req );
    //end trace
    
    showTableCoutProject($param, $tablePointage);
    endHeaderBaliseDiv($idBalise);
}



/**
 * showTableCoutProject
 * affiche les element de la table project_cout
 *
 * @param request param $param can be ""
 */
function showTableCoutProject($param="", $tablePointage = "") {
    if ($param == ""){
        $param = prepareParamShowTableCoutProject ();
        $req = createRequeteTableData ( $param );
        showSQLAction ( $req );
    }
    
    //ajouter 3 colonnes
    global $COLUMNS_SUMMARY;
    global $SQL_COL_TOTAL_PROJECT_COUT;
    global $SQL_COL_TOTAL_REEL_PROJECT_COUT;
    global $SQL_COL_POINTAGE_PROJECT_COUT;
    
    $colTotal = $SQL_COL_TOTAL_PROJECT_COUT;
    $param2 = addParamSqlColumn($param, $colTotal);
    $colpointage = $SQL_COL_POINTAGE_PROJECT_COUT;
    $param2 = addParamSqlColumn($param2, $colpointage);
    $colTotalReel = $SQL_COL_TOTAL_REEL_PROJECT_COUT;
    $param2 = addParamSqlColumn($param2, $colTotalReel);
    
    $param2[PARAM_TABLE_TABLE::TABLE_SIZE]="1400px";
    
    //header
    showTableHeader ( $param2 );
    //data
    //$res = showTableData($param, "", "", "no" /** close table*/);
    
    //test 20170408
    $result = sqlParamToArrayResult($param);
    $nbRes = mysqlNumrows ( $result );
    
    //printMatrice($result);
    
    //ajout colonne
    $result = setSQLFlagType ( $result, $colTotal, SQL_TYPE::SQL_REAL );
    //$result = setSQLFlagTypeSize ( $result, $colTotal, 5 );
    $result = setSQLFlagType ( $result, $colTotalReel, SQL_TYPE::SQL_REAL );
    //$result = setSQLFlagTypeSize ( $result, $colTotalReel, 5 );
    $result = setSQLFlagType ( $result, $colpointage, SQL_TYPE::SQL_REQUEST );
    //$result = setSQLFlagTypeSize ( $result, $colpointage, 3 );
    
    if ($tablePointage == ""){
        global $SQL_TABLE_CEGID_POINTAGE;
        $tablePointage = $SQL_TABLE_CEGID_POINTAGE;
    }
    
    //showSQLAction("showTableCoutProject() tablePointage '$tablePointage'");
    
    for($cpt = 0; $cpt < $nbRes; $cpt ++) {
        $result[$colpointage] [$cpt] = "select sum(UO) from $tablePointage ".
            " where PROFIL   ='". mysqlResult ( $result, $cpt, "PROFIL_ID" )."'".
            " AND year(DATE) = year('". mysqlResult ( $result, $cpt, 'DATE' )."')".
            " AND PROJECT_ID in (select CEGID from cegid_project where NAME = '". mysqlResult($result, $cpt, 'PROJECT') ."')".
            " group by PROFIL";
        $result[$colTotal] [$cpt] = "" ;
        $result[$colTotalReel] [$cpt] = "" ;
    }
    
    //end ajout colonne
    beginTableBody();
    $res = showTableData($param2,"",$result,"no");
    
    
    
    //ajout row supplementaire
    global $SQL_COL_UO_PROJECT_COUT;
    beginTableRow ();
    endTableRow ();
    $colsFromSummation="";
    showTablelineSummation($param2, "" /** liste des colonnes */, "$SQL_COL_UO_PROJECT_COUT, $colTotal, $colpointage, $colTotalReel",3);
    
    
    
    //end table
    global $TABLE_EXPORT_CSV;
    global $TABLE_INSERT;
    $param [$TABLE_EXPORT_CSV] = "yes";
    $param [$TABLE_INSERT] = "yes";
    showTableLineExportCSV($param);
    showTableLineInsert($param,"",$res);
    
    
    //close table
    endTableBody();
    endTable();
    
    //activation sommation
    global $TABLE_ID;
    global $SQL_COL_COUT_PROJECT_COUT;
    
    showSommation($param[$TABLE_ID], "",    "$SQL_COL_TOTAL_PROJECT_COUT", "$SQL_COL_COUT_PROJECT_COUT,$SQL_COL_UO_PROJECT_COUT","mult");
    showSommation($param[$TABLE_ID], "",    "$SQL_COL_TOTAL_REEL_PROJECT_COUT", "$SQL_COL_COUT_PROJECT_COUT,$colpointage","mult");
    showSommation($param[$TABLE_ID],"$SQL_COL_UO_PROJECT_COUT,$SQL_COL_TOTAL_PROJECT_COUT,$colpointage,$SQL_COL_TOTAL_REEL_PROJECT_COUT", "","");
    
    
    //printParam($param2);
    return $param2;
}

/**
 * prepareParamShowTableCoutProject
 */
function prepareParamShowTableCoutProject($subParam="") {
    global $SQL_SHOW_COL_PROJECT_COUT2;
    global $SQL_TABLE_PROJECT_COUT2;
    global $FORM_TABLE_CEGID_PROJECT_COUT;
    global $TABLE_EXPORT_CSV;
    global $TABLE_INSERT;
    global $SQL_SHOW_WHERE_COUT_PROJECT;
    global $SQL_SELECT_COL_COUT_PROJECT;
    
    $form_name = $FORM_TABLE_CEGID_PROJECT_COUT . "_insert";
    $condition = $SQL_SHOW_WHERE_COUT_PROJECT;
    
    //$subParam = updateParamSqlCondition($subParam, $SQL_SHOW_WHERE_COUT_PROJECT); //pour le join avec project_cegid
    
    
    $param = prepareshowTable ( $SQL_TABLE_PROJECT_COUT2, $SQL_SHOW_COL_PROJECT_COUT2, $form_name, $condition );
    $param [$TABLE_EXPORT_CSV] = "no";
    $param [$TABLE_INSERT] = "no";
    $param = updateParamSqlWithDistinct ( $param );
    $param = updateParamSqlColumnFilter ( $param, $SQL_SELECT_COL_COUT_PROJECT );
    
    //$req = createRequeteTableData ( $param );
    //showSQLAction ( "1. ".$req );
    
    
    // set sub param
    $param = updateParamSqlWithSubParam ( $param, $subParam );
    
    //$req = createRequeteTableData ( $param );
    //showSQLAction ( "2. ".$req );
    
    return $param;
}

?>