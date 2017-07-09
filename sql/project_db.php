<?PHP
$PROJECT_DB_PHP = "loaded";

$SQL_TABLE_PROJECT = "cegid_project";
$FORM_TABLE_CEGID_PROJECT = "form_table_cegid_project";

$SQL_COL_ID_PROJECT = "CEGID";
$SQL_COL_NAME_PROJECT = "NAME";
$SQL_COL_DESCRIPTION_PROJECT = "description";
$SQL_COL_DEBUT_PROJECT = "DEBUT";
$SQL_COL_FIN_PROJECT = "FIN";
$SQL_COL_FIN_GARANTIE = "FIN_GARANTIE";
$SQL_COL_PRIX_VENTE_PROJECT = "PRIX_VENTE";

$SQL_LABEL_PROJECT_NAME = "PROJECT";


$SQL_SHOW_COL_PROJECT = "$SQL_COL_ID_PROJECT, $SQL_COL_NAME_PROJECT, $SQL_COL_PRIX_VENTE_PROJECT, $SQL_COL_DEBUT_PROJECT, $SQL_COL_FIN_PROJECT, $SQL_COL_FIN_GARANTIE";

// include_once 'connection_db.php';
// include_once 'tool_db.php';
include_once 'table_db.php';



/**
 * application des actions sur la page one projet
 * @return number 0 si pas d'action faite
 */
function applyGestionOneProject() {
	global $TABLE_FORM_NAME_INSERT;
	global $FORM_TABLE_CEGID_PROJECT;
	
	$formName = getURLVariable($TABLE_FORM_NAME_INSERT);
	if ($formName == $FORM_TABLE_CEGID_PROJECT){
		return applyGestionProject();
	}
	
	return 0;
}

/**
 * showGestionOneProject
 */
function showGestionOneProject(){
	global $SQL_SHOW_COL_PROJECT;
	global $FORM_TABLE_CEGID_PROJECT;
	global $TABLE_FORM_NAME_INSERT;
	$form_name = $FORM_TABLE_CEGID_PROJECT/*."_one_update"*/;
	$project_found = "yes";
	
	//selection table de stockage si pas definie
	global $SQL_TABLE_PROJECT;
	$tableProject=$SQL_TABLE_PROJECT;
	
	//recuperation project name
	global $PROJECT_SELECTION;
	global $ITEM_COMBOBOX_SELECTION;
	global $SQL_COL_NAME_PROJECT;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
		showSQLAction ( "No project Selected..." );
		$projectName = "no project";
		$project_found = "no";
	}
	
	// info formulaire year et project name
	global $YEAR_SELECTION;
	$year = getURLYear();
	$infoForm = streamFormHidden ( $YEAR_SELECTION, $year );
	$infoForm = $infoForm . streamFormHidden ( $PROJECT_SELECTION, $projectName );
	$infoForm = $infoForm . streamFormHidden ( $TABLE_FORM_NAME_INSERT, $form_name );
		
		
	//$projectId = getURLVariableSQLForm($variables, $form)
	$condition = createSqlWhere($SQL_COL_NAME_PROJECT, $projectName);
	$subParam  = updateParamSqlCondition($subParam, $condition);
	$subParam  = setInfoForm($subParam, $infoForm);
	if ($project_found == "no"){
		//echo "project not found....<br>";
		$subParam = setSQLFlagStatus($subParam, stringToArray($SQL_SHOW_COL_PROJECT), "disabled");
	}
	
	editTable ( $tableProject, $SQL_SHOW_COL_PROJECT, "", $form_name,  $subParam );
	//applyGestionOneProjectTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name);
}

/**
 * application des actions sur la page projet
 * not used
 */
function applyGestionOneProjectTable($table, $cols, $form_name) {
	// trace
	$url = getCurrentURL ();
	$action = getActionGet ();
	showAction ( "action : [$action]  $url" );
	// end trace

	//insertInTableByGet ( $table, $cols, $form_name );
	editTable2 ( $table, $cols, $form_name );
	//updateTableByGet ( $table, $cols, $form_name );
	//exportCSVTableByGet ( $table, $cols, $cols, $form_name );
	//importCSVTableByGet ( $table, $cols, $form_name );
	//// insertOrupdateTableByGet ($table, $cols, $form_name);
	//applyDeleteTableByGet ( $table, $cols, $form_name, "" );
}


/**
 * application des actions sur la page projet
 * 
 * @return number 0 nothing 1 action traitée
 */
function applyGestionProject() {
	global $SQL_SHOW_COL_PROJECT;
	global $SQL_TABLE_PROJECT;
	global $FORM_TABLE_CEGID_PROJECT;
	$form_name = $FORM_TABLE_CEGID_PROJECT."_update";

	//traitement du update
	$res = updateTableByGet ($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name, "no"/** re-edit */ );
	
	//cas classique : edit, export, ...
	if ($res<=0){
		$res =  applyGestionTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name);
	}
	return $res;
}


/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableProject() {
	global $SQL_SHOW_COL_PROJECT;
	global $SQL_TABLE_PROJECT;
	global $FORM_TABLE_CEGID_PROJECT;
	global $TABLE_EXPORT_CSV;
	$form_name = $FORM_TABLE_CEGID_PROJECT."_insert";
	$condition="";
	
	//showTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name);
	$param = prepareshowTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name, $condition);
	//par defaut on a edit & delete
	
	//ajout export CSV
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	//ajout edit pointage
	global $URL_ROOT_POINTAGE;
	$url = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
	$param = addParamActionCommand($param, $url, "pointage!", LabelAction::ACTION_POINTAGE);
	
	showTableByParam($param);
	
}




// /**
//  * application des actions sur la page projet
//  */
// function applyGestionProject() {
// 	// trace
// 	$url = getCurrentURL ();
// 	$action = getActionGet ();
// 	showAction ( "action : [$action]  $url" );
// 	// end trace
	
// 	insertInProjectByGet ();
// 	editProjectByGet ();
// 	updateProjectByGet ();
// 	deleteProjectByGet ();
// }
// function deleteProjectByGet() {
// 	global $ID_TABLE_GET;
// 	if (getActionGet () == "delete") {
// 		$idTable = getURLVariable ( $ID_TABLE_GET );
// 		deleteInProject ( $idTable );
// 	}
// }
// function deleteInProject($idTable) {
// 	global $SQL_TABLE_PROJECT;
// 	global $SQL_SHOW_COL_PROJECT;
	
// 	$columns = stringToArray ( $SQL_SHOW_COL_PROJECT );
	
// 	$sql = createSqlDelete ( $SQL_TABLE_PROJECT, $columns [0], $idTable );
	
// 	showSQLAction ( $sql );
// 	$txt = "sql delete : " . mysql_query ( $sql ) . "   " . mysql_error ();
// 	showAction ( $txt );
	
// 	// historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
// }
// function editProjectByGet() {
// 	global $ID_TABLE_GET;
// 	if (getActionGet () == "edit") {
// 		$idTable = getURLVariable ( $ID_TABLE_GET );
// 		editProject ( $idTable );
// 	}
// }

// /**
//  * updateProjectByGet
//  * use url to found information
//  */
// function updateProjectByGet() {
// 	if (getActionGet () == "update") {
// 		global $SQL_SHOW_COL_PROJECT;
// 		global $SQL_TABLE_PROJECT;
		
// 		$sql = createSqlUpdateByID ( $SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT );
// 		showSQLAction ( "update action : $sql" );
// 		 $txt = "sql result : " . mysql_query ( $sql ) . " " . mysql_error ();
// 		 showAction ( $txt );
		
// 		global $ID_TABLE_GET;
// 		$idTable = getURLVariable ( $ID_TABLE_GET );
// 		editProject ( $idTable );
// 	}
// }
// function insertInProjectByGet() {
// 	if (getActionGet () == "inserer") {
// 		$url = getCurrentURL ();
// 		insertInProject ();
// 	}
// }
// function editProject($idTable) {
// 	global $SQL_SHOW_COL_PROJECT;
// 	global $SQL_TABLE_PROJECT;
// 	global $TABLE_SIZE;
// 	global $COLUMNS_SUMMARY;
	
// 	$form_name = "form_table_project";
// 	$param = createDefaultParamSql ( $SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT );
// 	$param = modifierTableParamSql ( $param, $form_name );
// 	$idKey = $param [$COLUMNS_SUMMARY] [0];
// 	$param = updateParamSqlWhereId ( $param, $idKey, $idTable );
// 	$param [$TABLE_SIZE] = 740;
	
// 	$html = getCurrentURL ();
// 	// $request = createRequeteTableData($param);
// 	// showSQLAction($request);
// 	$Resultat = requeteTableData ( $param );
// 	echo "<table>";
// 	editTableOneData ( $html, $Resultat, 0, $param, $idTable );
// 	echo "</table>";
	
// 	// showTableOneData($html, $Resultat, 0, $param);
// }
// function insertInProject() {
// 	global $SQL_TABLE_PROJECT;
// 	global $SQL_SHOW_COL_PROJECT;
	
// 	$columns = stringToArray ( $SQL_SHOW_COL_PROJECT );
// 	$values = getURLVariableArray ( $columns );
	
// 	// showAction($document2);
// 	$sql = createSqlInsert ( $SQL_TABLE_PROJECT, $columns, $values );
// 	showSQLAction ( $sql );
// 	$txt = "sql result : " . mysql_query ( $sql ) . "   " . mysql_error ();
// 	showAction ( $txt );
	
// 	// historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
// }

// /**
//  * affiche les versions des elements du projet
//  * (description)
//  */
// function showTableProject() {
// 	global $SQL_SHOW_COL_PROJECT;
// 	global $SQL_TABLE_PROJECT;
// 	global $TABLE_SIZE;
// 	$form_name = "form_table_project";
// 	$param = createDefaultParamSql ( $SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT );
// 	$param = modifierTableParamSql ( $param, $form_name );
// 	$param [$TABLE_SIZE] = 740;
	
// 	showTableHeader ( $param );
// 	showTableData ( $param );
// }

?>