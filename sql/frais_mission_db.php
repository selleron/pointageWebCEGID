<?php
$FRAIS_MISSION_DB_PHP = "loaded";

include_once 'project_db.php';
include_once 'pointage_cegid_db.php';

$SQL_TABLE_FRAIS_MISSION        = "cegid_frais_mission";
$SQL_TABLE_FRAIS_MISSION2       = "cegid_frais_mission as pc,  cegid_project as pj";
$FORM_TABLE_CEGID_FRAIS_MISSION = "form_table_cegid_frais_mission";

$SQL_COL_ID_FRAIS_MISSION          = "ID";
$SQL_COL_DATE_FRAIS_MISSION        = "DATE";
$SQL_COL_PROJECT_ID_COUT_PROJECT   = "PROJECT_ID";
$SQL_COL_PROJECT_NAME_COUT_PROJECT = "PROJECT";
$SQL_COL_TITRE_FRAIS_MISSION       = "TITRE";
$SQL_COL_FRAIS_MISSION_COMMENT     = "COMMENTAIRE";
$SQL_COL_STATUS_FRAIS_MISSION      = "STATUS_ID";
$SQL_COL_FRAIS_LOCAL_FRAIS_MISSION = "FRAIS_EN_LOCAL";
$SQL_COL_COUT_FRAIS_MISSION        = "FRAIS";

$SQL_SHOW_COL_FRAIS_MISSION   = "   $SQL_COL_ID_FRAIS_MISSION,                                $SQL_COL_DATE_FRAIS_MISSION,                                    $SQL_COL_PROJECT_ID_COUT_PROJECT,                               $SQL_COL_TITRE_FRAIS_MISSION,    $SQL_COL_FRAIS_LOCAL_FRAIS_MISSION,                                          $SQL_COL_COUT_FRAIS_MISSION,                                   $SQL_COL_FRAIS_MISSION_COMMENT,                                      $SQL_COL_STATUS_FRAIS_MISSION";
$SQL_SHOW_COL_FRAIS_MISSION2  = "   $SQL_COL_ID_FRAIS_MISSION,                                $SQL_COL_DATE_FRAIS_MISSION,                                    $SQL_COL_PROJECT_NAME_COUT_PROJECT,                             $SQL_COL_TITRE_FRAIS_MISSION,    $SQL_COL_FRAIS_LOCAL_FRAIS_MISSION,                                          $SQL_COL_COUT_FRAIS_MISSION,                                   $SQL_COL_FRAIS_MISSION_COMMENT,                                      $SQL_COL_STATUS_FRAIS_MISSION";
$SQL_SELECT_COL_FRAIS_MISSION = "pc.$SQL_COL_ID_FRAIS_MISSION as $SQL_COL_ID_FRAIS_MISSION, pc.$SQL_COL_DATE_FRAIS_MISSION as $SQL_COL_DATE_FRAIS_MISSION, pj.$SQL_COL_NAME_PROJECT as $SQL_COL_PROJECT_NAME_COUT_PROJECT, pc.$SQL_COL_TITRE_FRAIS_MISSION, pc.$SQL_COL_FRAIS_LOCAL_FRAIS_MISSION as $SQL_COL_FRAIS_LOCAL_FRAIS_MISSION, pc.$SQL_COL_COUT_FRAIS_MISSION as $SQL_COL_COUT_FRAIS_MISSION, pc.$SQL_COL_FRAIS_MISSION_COMMENT as $SQL_COL_FRAIS_MISSION_COMMENT, pc.$SQL_COL_STATUS_FRAIS_MISSION as $SQL_COL_STATUS_FRAIS_MISSION";
$SQL_SHOW_WHERE_FRAIS_MISSION = "`$SQL_COL_ID_PROJECT` = `$SQL_COL_PROJECT_ID_COUT_PROJECT`	";

// $SQL_COL_TOTAL_FRAIS_MISSION      = "Total.COUT";
// $SQL_COL_TOTAL_REEL_FRAIS_MISSION = "Reel.COUT";
// $SQL_COL_POINTAGE_FRAIS_MISSION   = "U.O.Pointage";

// include_once 'connection_db.php';
// include_once 'tool_db.php';
include_once 'table_db.php';

/**
 * application des actions sur la page projet cout
 *
 * @param string $subParam
 * @return number 0 nothing 1 action traitée
 */
function applyGestionFraisMssion($subParam="") {
	global $SQL_SHOW_COL_FRAIS_MISSION;
	global $SQL_SHOW_COL_FRAIS_MISSION2;
	global $SQL_SELECT_COL_FRAIS_MISSION;
	global $SQL_SHOW_WHERE_FRAIS_MISSION;
	global $SQL_TABLE_FRAIS_MISSION;
	global $SQL_TABLE_FRAIS_MISSION2;
	global $TABLE_FORM_NAME_INSERT;
	global $FORM_TABLE_CEGID_FRAIS_MISSION;
	$form_name = $FORM_TABLE_CEGID_FRAIS_MISSION . "_update";
	$condition="";
	
	//showSQLAction("applyGestionFraisMssion() col filter : $SQL_SELECT_COL_FRAIS_MISSION");
	
	
	//execution du edit
	$subParam = createDefaultParamSql ( $SQL_TABLE_FRAIS_MISSION2, $SQL_SHOW_COL_FRAIS_MISSION2, $condition );
	$subParam = updateTableParamSql ( $subParam, $form_name, $SQL_SELECT_COL_FRAIS_MISSION );
	$subParam = updateParamSqlColumnFilter ( $subParam, $SQL_SELECT_COL_FRAIS_MISSION );
	$subParam = updateParamSqlCondition($subParam, $SQL_SHOW_WHERE_FRAIS_MISSION); //pour le join avec project_cegid
	$subParam = updateParamSqlConditionForUpdate($subParam, "1"); //pour ne pas tenir compte de la condition edit pour le update
	
	
	$res = editTableByGet ( /*$SQL_TABLE_FRAIS_MISSION2, $SQL_SHOW_COL_FRAIS_MISSION2, $form_name,*/ $subParam );
	
	if ($res <= 0) {
		//execution du update
	    $condition="";
	    $colFilter=NULL;
	    $paramUpdate = createDefaultParamSql ( $SQL_TABLE_FRAIS_MISSION, $SQL_SHOW_COL_FRAIS_MISSION, $condition );
	    $paramUpdate = updateTableParamSql ( $paramUpdate, $form_name, $colFilter );
	    
		$res = updateTableByGet (/* $SQL_TABLE_FRAIS_MISSION, $SQL_SHOW_COL_FRAIS_MISSION, $form_name,*/ $paramUpdate, "no" /**re-edit */);
		//on re-affiche la table du edit
		if ($res) editTable2 ( /*$SQL_TABLE_FRAIS_MISSION2, $SQL_SHOW_COL_FRAIS_MISSION2, $form_name,*/ $subParam );
	}
	
	// cas classique : edit, export, ...
	if ($res <= 0) {
		$res = applyGestionTable ( $SQL_TABLE_FRAIS_MISSION, $SQL_SHOW_COL_FRAIS_MISSION, $form_name );
	}
	return $res;
}


/**
 * application des actions sur la page cout projet
 * on test en premier lieu par rapport au nom de la forme pour eviter 
 * les actions concurrente sur les update, edit, insert,...
 * @return number 0 si pas d'action faite
 */
function applyGestionFraisMssionForm($subParam="") {
	global $TABLE_FORM_NAME_INSERT;
	global $FORM_TABLE_CEGID_FRAIS_MISSION;
	$formI = $FORM_TABLE_CEGID_FRAIS_MISSION . "_insert";
	$formU = $FORM_TABLE_CEGID_FRAIS_MISSION . "_update";
	
	$formName = getURLVariable($TABLE_FORM_NAME_INSERT);
	showAction("applyGestionFraisMssionForm - form name found : $formName");
	if (($formName == $FORM_TABLE_CEGID_FRAIS_MISSION) || ($formName == $formI)  || ($formName == $formU)){
		return applyGestionFraisMssion($subParam);
	}

	return 0;
}

/**
 * applyGestionFraisMissionOneProjectForm
 * prise en compte year, project user
 */
function applyGestionFraisMissionOneProjectForm($subParam=""){
	
	//info form
	// ajout selection project et year
	$infoForm = getInfoForm($subParam);
	$infoForm = getInfoFormProjectSelection($infoForm);
	$subParam  = setInfoForm($subParam, $infoForm);
	
	return applyGestionFraisMssionForm($subParam);
}


/**
 * showTableFraisMissionOneProject
 *
 * show table Cout project
 * prend en compte la selection du projet et de la date
 */
function showTableFraisMissionOneProject($tablePointage="", $showOnlyOneProject = "yes") {
    $idBalise="fraisMission_$tablePointage";
    createHeaderBaliseDiv($idBalise,"<h3>Table frais mission</h3>");
    
    global $TRACE_INFO_PROJECT;
	//global $SQL_TABLE_FRAIS_MISSION2;
	global $FORM_TABLE_CEGID_FRAIS_MISSION;
	global $TABLE_FORM_NAME_INSERT;
	global $SQL_SHOW_COL_PROJECT;
	$form_name = $FORM_TABLE_CEGID_FRAIS_MISSION/*."_one_update"*/;
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
	
	
	
	
	$param = prepareParamShowTableFraisMission ($subParam);
	
	//trace
	$req = createRequeteTableData ( $param );
	showSQLAction ( $req );
	//end trace
	
	showTableFraisMission($param);
	endHeaderBaliseDiv($idBalise);
}



/**
 * showTableFraisMission
 * affiche les element de la table FRAIS_MISSION
 * 
 * @param request param $param can be ""
 */
function showTableFraisMission($param="" ) {
	if ($param == ""){
		$param = prepareParamShowTableFraisMission ();
		$req = createRequeteTableData ( $param );
		showSQLAction ( $req );
	}
	

	
	$param2=$param;
	// 	$param2[PARAM_TABLE_TABLE::TABLE_SIZE]="1400px";
	
	//header
	showTableHeader ( $param2 );
	//data
	//$res = showTableData($param, "", "", "no" /** close table*/);
	
	//test 20170408
	$result = sqlParamToArrayResult($param);
	//$nbRes = mysqlNumrows ( $result );
	
	//printMatrice($result);
	
	
//  	if ($tablePointage == ""){
//  		global $SQL_TABLE_CEGID_POINTAGE;
//  		$tablePointage = $SQL_TABLE_CEGID_POINTAGE;
//  	}
 	
 	//showSQLAction("showTableFraisMission() tablePointage '$tablePointage'");
 	
 	
	//end ajout colonne
	beginTableBody();	
	$res = showTableData($param2,"",$result,"no");
	
	
	
	//ajout row supplementaire
	global $SQL_COL_COUT_FRAIS_MISSION;
	beginTableRow ();
	endTableRow ();
	$colsFromSummation="";
	showTablelineSummation($param2, "" /** liste des colonnes */, "$SQL_COL_COUT_FRAIS_MISSION",12);
	
	
	
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
	global $SQL_COL_COUT_FRAIS_MISSION;
	//showSQLAction("showTableFraisMission() table id : $param[$TABLE_ID]");
	showSommation($param[$TABLE_ID],"$SQL_COL_COUT_FRAIS_MISSION", "","");
	
	
	//printParam($param2);
	return $param2;
}

/**
 * prepareParamShowTableFraisMission
 */
function prepareParamShowTableFraisMission($subParam="") {
	global $SQL_SHOW_COL_FRAIS_MISSION2;
	global $SQL_TABLE_FRAIS_MISSION2;
	global $FORM_TABLE_CEGID_FRAIS_MISSION;
	global $TABLE_EXPORT_CSV;
	global $TABLE_INSERT;
	global $SQL_SHOW_WHERE_FRAIS_MISSION;
	global $SQL_SELECT_COL_FRAIS_MISSION;
	
	$form_name = $FORM_TABLE_CEGID_FRAIS_MISSION . "_insert";
	$condition = $SQL_SHOW_WHERE_FRAIS_MISSION;
	
	//$subParam = updateParamSqlCondition($subParam, $SQL_SHOW_WHERE_FRAIS_MISSION); //pour le join avec project_cegid
	
	
	$param = prepareshowTable ( $SQL_TABLE_FRAIS_MISSION2, $SQL_SHOW_COL_FRAIS_MISSION2, $form_name, $condition );
	$param [$TABLE_EXPORT_CSV] = "no";
	$param [$TABLE_INSERT] = "no";
	$param = updateParamSqlWithDistinct ( $param );
	$param = updateParamSqlColumnFilter ( $param, $SQL_SELECT_COL_FRAIS_MISSION );

	//$req = createRequeteTableData ( $param );
	//showSQLAction ( "1. ".$req );
	
	
	// set sub param
	$param = updateParamSqlWithSubParam ( $param, $subParam );

	//$req = createRequeteTableData ( $param );
	//showSQLAction ( "2. ".$req );
	
	return $param;
}

?>