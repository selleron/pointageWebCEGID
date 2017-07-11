<?PHP
$PROINTAGE_DB_PHP = "loaded";

include_once 'table_db.php';
include_once 'project_db.php';
include_once 'time.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");

$SQL_TABLE_CEGID_POINTAGE = "cegid_pointage";
$FORM_TABLE_CEGID_POINTAGE = "form_table_cegid_pointage";

$SQL_COL_PROJECT_ID_CEGID_POINTAGE = "PROJECT_ID";
$SQL_COL_DATE_CEGID_POINTAGE = "DATE";
$SQL_COL_USER_CEGID_POINTAGE = "USER_ID";
$SQL_COL_PROFIL_CEGID_POINTAGE = "PROFIL";
$SQL_COL_UO_CEGID_POINTAGE = "UO";

$SQL_SHOW_COL_CEGID_POINTAGE = "$SQL_COL_PROJECT_ID_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, $SQL_COL_USER_CEGID_POINTAGE, $SQL_COL_PROFIL_CEGID_POINTAGE, $SQL_COL_UO_CEGID_POINTAGE";
$SQL_DEL_COL_CEGID_POINTAGE  = "$SQL_COL_PROJECT_ID_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, $SQL_COL_USER_CEGID_POINTAGE, $SQL_COL_PROFIL_CEGID_POINTAGE";

global $SQL_COL_ID_PROJECT;
global $SQL_LABEL_PROJECT_NAME;

$SQL_TABLE_CEGID_POINTAGE2 = "cegid_pointage as p,  cegid_user as u, cegid_project as pj";
$FORM_TABLE_CEGID_POINTAGE2 = "form_table_cegid_pointage2";
$SQL_SHOW_COL_CEGID_POINTAGE2_2 = "    PROJECT_ID,            $SQL_LABEL_PROJECT_NAME,   USER_ID,           NAME,   PROFIL";
$SQL_SELECT_COL_CEGID_POINTAGE2_2 = "p.PROJECT_ID, pj.NAME as $SQL_LABEL_PROJECT_NAME, p.USER_ID, u.NAME as NAME, p.PROFIL";

// $SQL_SHOW_COL_CEGID_POINTAGE2 = "PROJECT_ID, PROJECT, DATE, NAME, PROFIL, UO";
// $SQL_SELECT_COL_CEGID_POINTAGE2 = "p.PROJECT_ID, pj.NAME as PROJECT, DATE, u.NAME, p.PROFIL, UO";
$SQL_SHOW_COL_CEGID_POINTAGE2 = "             $SQL_LABEL_PROJECT_NAME , DATE,           NAME,   PROFIL, UO";
$SQL_SELECT_COL_CEGID_POINTAGE2 = "pj.NAME as $SQL_LABEL_PROJECT_NAME , DATE, u.NAME as NAME, p.PROFIL, UO";
$SQL_SHOW_WHERE_CEGID_POINTAGE2 = "p.USER_ID = u.ID and pj.$SQL_COL_ID_PROJECT = p.$SQL_COL_PROJECT_ID_CEGID_POINTAGE";

$PROJECT_SELECTION = "project";
$YEAR_SELECTION = "year";
$USER_SELECTION = "user";
$LIST_COLS_MONTHS = "jan,fev,mars,avril,mai,juin,juil,aout,sept,oct,nov,dec";

// SELECT P.PROJECT_ID, P.DATE, p.USER_ID as ID, u.name, p.UO FROM `cegid_pointage` as p , `cegid_user` as u WHERE p.USER_ID = u.ID

/**
 * application des actions sur la page projet
 */
function applyGestionPoinstageCegid() {
	global $SQL_SHOW_COL_CEGID_POINTAGE;
	global $SQL_TABLE_CEGID_POINTAGE;
	global $FORM_TABLE_CEGID_POINTAGE;
	$form_name = $FORM_TABLE_CEGID_POINTAGE . "_update";
	
	applyGestionTable ( $SQL_TABLE_CEGID_POINTAGE, $SQL_SHOW_COL_CEGID_POINTAGE, $form_name );
}

/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTablePointageBrutCegid() {
	global $SQL_SHOW_COL_CEGID_POINTAGE;
	global $SQL_TABLE_CEGID_POINTAGE;
	global $FORM_TABLE_CEGID_POINTAGE;
	global $TABLE_EXPORT_CSV;
	
	$form_name = $FORM_TABLE_CEGID_POINTAGE . "_insert";
	$condition = "";
	
	// prepare request table
	$param = prepareshowTable ( $SQL_TABLE_CEGID_POINTAGE, $SQL_SHOW_COL_CEGID_POINTAGE, $form_name, $condition );
	$param = updateParamSqlWithDeleteByRow ( $param );
	$param = updateParamSqlWithEditByRow ( $param );
	$param [$TABLE_EXPORT_CSV] = "yes";
	// $param = updateParamSqlWithDistinct ( $param );
	
	// show navogation bar
	$param = updateParamSpqlWithLimit ( $param );
	showLimitBar ( $param );
	
	// for debug
	$req = createRequeteTableData ( $param );
	showSQLAction ( $req );
	
	// show table
	showTableByParam ( $param );
}

/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTablePointageCegid() {
	$param = prepareParamShowTablePointageCegid ();
	$req = createRequeteTableData ( $param );
	showSQLAction ( $req );
	showTableByParam ( $param );
}

/**
 * prepareParamShowTablePointageCegid
 *
 * @return $parm
 */
function prepareParamShowTablePointageCegid() {
	global $SQL_SHOW_COL_CEGID_POINTAGE2;
	global $SQL_TABLE_CEGID_POINTAGE2;
	global $FORM_TABLE_CEGID_POINTAGE2;
	global $SQL_SHOW_WHERE_CEGID_POINTAGE2;
	global $SQL_SELECT_COL_CEGID_POINTAGE2;
	
	$form_name2 = $FORM_TABLE_CEGID_POINTAGE2 . "_insert";
	
	$param = prepareshowTable ( $SQL_TABLE_CEGID_POINTAGE2, $SQL_SHOW_COL_CEGID_POINTAGE2, $form_name2, $SQL_SHOW_WHERE_CEGID_POINTAGE2 );
	$param = updateParamSqlWithDistinct ( $param );
	$param = updateParamSqlColumnFilter ( $param, $SQL_SELECT_COL_CEGID_POINTAGE2 );
	
	return $param;
}

/**
 * show insert table pointage
 * project user date type uo [insert]
 */
function showInsertTablePointageCegid() {
	$param = prepareParamShowTablePointageCegid ();
	
	// info formulaire
	global $YEAR_SELECTION;
	$year = getURLVariable ( $YEAR_SELECTION );
	$infoForm = streamFormHidden ( $YEAR_SELECTION, $year );
	global $PROJECT_SELECTION;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	$infoForm = $infoForm . streamFormHidden ( $PROJECT_SELECTION, $projectName );
	$param = setInfoForm ( $param, $infoForm );
	// showSQLAction("showInsertTablePointageCegid - form info : $infoForm");
	
	//pour avoir les types des variables
	$Resultat = requeteTableData ( $param );
	
	//valeurs par defaut
	global $SQL_LABEL_PROJECT_NAME;
	$selectedValue[$SQL_LABEL_PROJECT_NAME]="$projectName";
	global $USER_SELECTION;
	$current_selection_user = getURLVariable ( $USER_SELECTION );
	$selectedValue["NAME"]="$current_selection_user";
	
	//show insert line
	//showSQLAction("go showOnlyInsertTableByParam()");
	showOnlyInsertTableByParam ( "", $Resultat, $param, $selectedValue );
}

/**
 * applyGestionPointageProjetCegid
 */
function applyGestionPointageProjetCegid() {
	global $SQL_TABLE_CEGID_POINTAGE;
	
	$table = $SQL_TABLE_CEGID_POINTAGE;
	return applyGestionTablePointageProjetCegid($table);
}

/**
 * applyGestionPointageProjetCegid
 * @param string $table
 */
function applyGestionTablePointageProjetCegid($table) {
		global $SQL_DEL_COL_CEGID_POINTAGE;
		global $SQL_SHOW_COL_CEGID_POINTAGE;
		global $FORM_TABLE_CEGID_POINTAGE;
		$form_name = $FORM_TABLE_CEGID_POINTAGE . "_replace";
	
	$exec = applyExportCSVSelectPointage ();
	if ($exec > 0) {
		return;
	}
	
	// execution action
	$exec = applyImportCSV ();
	if ($exec > 0) {
		return;
	}
	$exec = replacePointage ( $table, $SQL_SHOW_COL_CEGID_POINTAGE, $SQL_DEL_COL_CEGID_POINTAGE, $form_name );
	if ($exec > 0) {
		return;
	}
	$exec = exportCSVTableGestionPointageProjetCegid ($table);
	if ($exec > 0) {
		return;
	}
	// force la date au 1er
	if (getActionGet () == "inserer") {
		global $SQL_COL_DATE_CEGID_POINTAGE;
		$date = getURLVariable ( $SQL_COL_DATE_CEGID_POINTAGE );
		// echo "date : $date <br>";
		$month = monthFromSqlDate ( $date );
		$year = yearFromSqlDate ( $date );
		$date2 = sqlDateFromDDMMYY ( "01", $month, $year );
		// echo "date2 : $date2 <br>";
		setURLVariable ( $SQL_COL_DATE_CEGID_POINTAGE, $date2 );
	}
	
	// apply classique
	applyGestionTable ( $table, $SQL_SHOW_COL_CEGID_POINTAGE, $form_name );
}

/**
 * applyPointageBrutCegid
 */
function applyPointageBrutCegid() {
	global $SQL_DEL_COL_CEGID_POINTAGE;
	global $SQL_SHOW_COL_CEGID_POINTAGE;
	global $SQL_TABLE_CEGID_POINTAGE;
	global $FORM_TABLE_CEGID_POINTAGE;
	$form_name = $FORM_TABLE_CEGID_POINTAGE . "_replace";
	
	// execution action
	// $exec = applyImportCSV();
	// if ($exec < 1) {
	// apply classique
	applyGestionTable ( $SQL_TABLE_CEGID_POINTAGE, $SQL_SHOW_COL_CEGID_POINTAGE, $form_name );
	// }
	// }
}

/**
 * applyImportCSV
 *
 * @return number
 */
function applyImportCSV() {
	if ((getActionGet () == "import")) {
		$firstline = 0;
		$array = actionImportCSV ( $firstline );
		$array = suppressCommentMatrice ( $array );
		
		global $PROJECT_SELECTION;
		$year = getURLYear ();
		$projectName = getURLVariable ( $PROJECT_SELECTION );
		
		// printMatrice($array);
		$array = inverseArray2D ( $array );
		// printMatrice($array);
		
		global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
		global $LIST_COLS_MONTHS;
		$columnsComplet = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . $LIST_COLS_MONTHS;
		$collist = stringToArray ( $columnsComplet );
		$array = indexToKeyArray1D ( $array, $collist );
		// printMatrice($array);
		
		$ci = 0;
		foreach ( $collist as $c ) {
			// $type2 = mysqlFieldType ( $Resultat, $ci );
			$array = setSQLFlagType ( $array, $c, SQL_TYPE::SQL_STRING );
			$ci ++;
		}
		
		$arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
		foreach ( $arrayMonth as $m ) {
			$array = setSQLFlagType ( $array, $m, SQL_TYPE::SQL_INT );
			$array = setSQLFlagTypeSize ( $array, $m, 3 );
		}
		
		// suppression 1er row = noms des colonnes
		// $array = unsetSQLRow($array, 0);
		
		showArrayPointageProjetCegid ( $array, $projectName, $year );
		
		return 1;
	}
	return 0;
}

/**
 * exportCSVTableGestionPointageProjetCegid
 *
 * @return number
 */
function exportCSVTableGestionPointageProjetCegid( $table) {
	global $SQL_DEL_COL_CEGID_POINTAGE;
	global $SQL_SHOW_COL_CEGID_POINTAGE;
	global $FORM_TABLE_CEGID_POINTAGE;
	$form_name = $FORM_TABLE_CEGID_POINTAGE . "_replace";
	global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
	global $LIST_COLS_MONTHS;

	if ($table == ""){
		global $SQL_TABLE_CEGID_POINTAGE;
		$table = $SQL_TABLE_CEGID_POINTAGE;
	}
	
	$columnsAction = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . $LIST_COLS_MONTHS;
	$columnsExport = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . getExportMonths ();
	$exec = exportCSVTableByGet ( $table, $columnsAction, $columnsExport, $form_name );
	
	return $exec;
}


/**
 * applyPreviousSelectPointage
 *
 * @return number
 */
function applyNextPreviousSelectPointage() {
	//showSQLAction("action ".getActionGet ()." test ".LabelAction::ACTION_PREVIOUS);
	if (getActionGet() == LabelAction::ACTION_POINTAGE){
	    global $SQL_COL_NAME_PROJECT;
	    global $PROJECT_SELECTION;;
	    //showSQLAction("traitement action : ".LabelAction::ACTION_POINTAGE." = $PROJECT_SELECTION - $SQL_COL_NAME_PROJECT");
	    setURLVariable("$PROJECT_SELECTION", getURLVariable("$SQL_COL_NAME_PROJECT"));
	    //showGet();
	    //showPost();
	}
	else if ((getActionGet () == LabelAction::ACTION_PREVIOUS) || (getActionGet () == LabelAction::ACTION_NEXT)) {
		 //showSQLAction("action previous demanded");
		
		global $SQL_TABLE_PROJECT;
		global $SQL_COL_ID_PROJECT;
		global $SQL_COL_NAME_PROJECT;
		
		$col = "";
		$param = createDefaultParamSql ( $SQL_TABLE_PROJECT, $col, $condition );
		if (getActionGet () == LabelAction::ACTION_PREVIOUS) {
		$param [ORDER_ENUM::ORDER_GET] = $SQL_COL_ID_PROJECT;
		$param [ORDER_ENUM::ORDER_DIRECTION] = ORDER_ENUM::ORDER_DIRECTION_DESC;
		}
		
		 //$request = createRequeteTableData ( $param );
		 //showSQLAction($request);
		
		global $PROJECT_SELECTION;
		global $ITEM_COMBOBOX_SELECTION;
		
		$currentProject = getURLVariable ( $PROJECT_SELECTION );
		
		$Resultat = requeteTableData ( $param );
		$nbRes = mysqlNumrows ( $Resultat );
		// showSQLAction("nb project : $nbRes");
		
		if ($nbRes > 0 && $currentProject == $ITEM_COMBOBOX_SELECTION) {
			$nextProject = mysqlResult ( $Resultat, 0, $SQL_COL_NAME_PROJECT );
		}
		if ($nbRes > 0 && $currentProject == "") {
			$nextProject = mysqlResult ( $Resultat, 0, $SQL_COL_NAME_PROJECT );
		}
		
		if ($nextProject == "") {
			for($cpt = 0; $cpt < ($nbRes - 1); $cpt ++) {
				$name = mysqlResult ( $Resultat, $cpt, $SQL_COL_NAME_PROJECT );
				if ($name == $currentProject) {
					$nextProject = mysqlResult ( $Resultat, ($cpt + 1), $SQL_COL_NAME_PROJECT );
				}
			}
		}
		
		if ($nextProject == "") {
			// nothing to do
		} else {
			setURLVariable ( $PROJECT_SELECTION, $nextProject );
			//showSQLAction("Project Selection : $nextProject");
			}
		
		return 1;
		// return exportCSVArrayGestionPointageProjetCegid ();
	} else {
		return - 1;
	}
}

/**
 * applyExportCSVSelectPointage
 *
 * @return number
 */
function applyExportCSVSelectPointage() {
	// if (getURLVariable ( "exportCSVSelect" ) == "") {
	// // showSQLAction("No export action demanded");
	// return - 1;
	// }
	if (getActionGet () == LabelAction::ActionExport) {
		// showSQLAction("Export action demanded");
		return exportCSVArrayGestionPointageProjetCegid ();
	} else {
		return - 1;
	}
}

/**
 * exportCSVArrayGestionPointageProjetCegid
 *
 * @return number
 */
function exportCSVArrayGestionPointageProjetCegid() {
	global $SQL_DEL_COL_CEGID_POINTAGE;
	global $SQL_SHOW_COL_CEGID_POINTAGE;
	global $SQL_TABLE_CEGID_POINTAGE;
	global $FORM_TABLE_CEGID_POINTAGE;
	$form_name = $FORM_TABLE_CEGID_POINTAGE . "_replace";
	global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
	global $LIST_COLS_MONTHS;
	$columnsAction = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . $LIST_COLS_MONTHS;
	$columnsExport = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . getExportMonths ();
	
	$matrice = getTableauPointageProjetCegid ();
	$exec = exportCSVArray ( $SQL_TABLE_CEGID_POINTAGE, $columnsAction, $columnsExport, $matrice );
	
	return $exec;
}

/**
 *
 * @return string liste des months
 */
function getExportMonths() {
	// var_dump ( debug_backtrace () );
	global $YEAR_SELECTION;
	$annee = getURLVariable ( $YEAR_SELECTION );
	// showSQLAction ( "export for annee : $annee" );
	
	for($cptMonth = 0; $cptMonth < 12; $cptMonth ++) {
		$mois = $cptMonth + 1;
		$date = formatCSVDate ( 01, $mois, $annee );
		$resultArray [$cptMonth] = $date;
	}
	
	$result = arrayToString ( $resultArray );
	return $result;
}

/**
 * replacePointage
 * 
 * @param string $table
 *        	table name
 * @param string $colsSet
 *        	column list
 * @param string $form_name
 *        	form name
 * @return number 1 si ok 0 si nothing
 */
function replacePointage($table, $colsSet, $colsDelete, $form_name) {
	if ((getActionGet () == "replace") || (getActionGet () == "update")) {
		
		global $SHOW_SQL_REPLACE;
		$trace = $SHOW_SQL_REPLACE;
		
		$colList = stringToArray ( $colsSet );
		if ($trace == "yes")
			showSQLAction ( "variable $colList[0]" );
		$id = getURLVariable ( $colList [0] );
		$nb = count ( $id );
		if ($trace == "yes")
			showSQLAction ( "nb row for update : $nb" );
		
		global $YEAR_SELECTION;
		$annee = getURLVariable ( $YEAR_SELECTION );
		if ($trace == "yes")
			showSQLAction ( "update for annee : $annee" );
		
		global $SQL_COL_DATE_CEGID_POINTAGE;
		global $SQL_COL_UO_CEGID_POINTAGE;
		global $LIST_COLS_MONTHS;
		$arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
		
		// boucle row
		$nb_delete = 0;
		$trace_delete = $trace;
		$nb_replace = 0;
		$trace_replace = $trace;
		for($cptRow = 0; $cptRow < $nb; $cptRow ++) {
			// boucle sur les mois
			for($cptMonth = 0; $cptMonth < 12; $cptMonth ++) {
				$mois = $cptMonth + 1;
				$date = formatSqlDate ( 01, $mois, $annee );
				// showSQLAction("update for date : $date");
				// injection date
				setURLVariable ( $SQL_COL_DATE_CEGID_POINTAGE, $date );
				
				// injection UO
				$uo = getURLVariable ( $arrayMonth [$cptMonth] );
				setURLVariable ( $SQL_COL_UO_CEGID_POINTAGE, $uo );
				
				if ($uo [$cptRow] == "") {
					if ($nb_delete == 3) {
						$trace_delete = "no";
						if ($trace == "yes")
							showAction ( "trace delete off" );
					}
					
					deleteTableByGet ( $table, $colsDelete, $form_name, $cptRow, $trace_delete );
					$nb_delete ++;
				} else {
					if ($nb_replace == 3) {
						$trace_replace = "no";
						if ($trace == "yes")
							showAction ( "trace replace off x" );
					}
					replaceTableByGet ( $table, $colsSet, $form_name, $cptRow, $trace_replace );
					$nb_replace ++;
				}
			}
		}
		
		return 1;
	}
	return 0;
}

/**
 * showProjectSelection
 *
 * @param string $url
 *        	url
 * @param string $formName
 *        	form name
 * @param string $yearVisible
 *        	is selection year visible "yes/no"
 * @param string $export
 *        	is button export visible "yes/no"
 * @param string $userVisible
 *        	is section user visible "yes/no"
 */
function showProjectSelection($url = "", $formName = "", $yearVisible = "yes", $export = "no", $userVisible = "no", $previousVisible = "no", $nextVisible = "no") {
	global $SQL_TABLE_PROJECT;
	global $SQL_TABLE_CEGID_POINTAGE;
	global $SQL_COL_NAME_PROJECT;
	
	if ($formName == "") {
		$formName = "form_select_project_pointage";
	}
	
	if (! $url) {
		$url = currentPageURL ();
		// echo $html."<br>";
	}
	
	global $PROJECT_SELECTION;
	global $YEAR_SELECTION;
	global $USER_SELECTION;
	global $SQL_COL_DATE_CEGID_POINTAGE;
	$current_selection_projet = getURLVariable ( $PROJECT_SELECTION );
	$current_selection_year = getURLVariable ( $YEAR_SELECTION );
	$current_selection_user = getURLVariable ( $USER_SELECTION );
	
	echo "<table>";
	beginTableHeader ();
	echo "<td>Project</td>";
	if ($yearVisible == "yes") {
		echo "<td>year</td>";
	}
	if ($userVisible == "yes") {
		echo "<td>user</td>";
	}
	// echo "<td>actions</td>";
	endTableHeader ();
	
	echo "<tr>";
	// combo project
	createForm ( $url, $formName );
	showFormComboBox ( $formName, $PROJECT_SELECTION, $SQL_TABLE_PROJECT, $SQL_COL_NAME_PROJECT, "yes", $current_selection_projet );
	
	// combo year
	if ($yearVisible == "yes") {
		$request = getSqlListValueFormColumn ( $formName, $YEAR_SELECTION );
		showFormComboBoxSql ( $formName, $YEAR_SELECTION, $request, 0, "yes", $current_selection_year );
	}
	// showFormComboBox($formName, $YEAR_SELECTION, $SQL_TABLE_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, "no", $current_selection_year);
	// combo year
	if ($userVisible == "yes") {
		$request = getSqlListValueFormColumn ( $formName, $USER_SELECTION, "yes" );
		// echoTD("request : $request");
		showFormComboBoxSql ( $formName, $USER_SELECTION, $request, 0, "yes", $current_selection_user );
	}
	// showFormComboBox($formName, $YEAR_SELECTION, $SQL_TABLE_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, "no", $current_selection_year);
	echo "<td>";
	showFormIDElement ();
	
	global $ACTION_GET;
	
	echoSpace ();
	// echo " &nbsp;";
	// showFormSubmit ( "select" );
	showFormSubmit ( LabelAction::ActionSelect, $ACTION_GET );
	if ($export == "yes") {
		echoSpace ();
		// echo " &nbsp;";
		// showFormSubmit ( LabelAction::ActionExport, "exportCSVSelect" );
		showFormSubmit ( LabelAction::ActionExport, "$ACTION_GET" );
	}
	if ($previousVisible == "yes") {
		echoSpace ();
		// echo " &nbsp;";
		// showFormSubmit ( "previous >>", "$ACTION_GET" );
		showFormSubmit ( LabelAction::ACTION_PREVIOUS, "$ACTION_GET" );
	}
	if ($nextVisible == "yes") {
		echoSpace ();
		// echo " &nbsp;";
		// showFormSubmit ( "next >>", "$ACTION_GET" );
		showFormSubmit ( LabelAction::ACTION_NEXT, "$ACTION_GET" );
	}
	echo "</td>";
	endForm ();
	
	echo "</tr>";
	echo "</table>";
}

/**
 * getInfoFormProjectSelection
 *
 * @param string $infoForm
 *        	infoform to complet
 * @return string infoForm for parameters
 */
function getInfoFormProjectSelection($infoForm = "") {
	global $PROJECT_SELECTION;
	global $YEAR_SELECTION;
	global $USER_SELECTION;
	global $ITEM_COMBOBOX_SELECTION;
	
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	$year = getURLVariable ( $YEAR_SELECTION );
	$user = getURLVariable ( $USER_SELECTION );
	
	$infoForm = $infoForm . streamFormHidden ( $YEAR_SELECTION, $year );
	$infoForm = $infoForm . streamFormHidden ( $PROJECT_SELECTION, $projectName );
	$infoForm = $infoForm . streamFormHidden ( $USER_SELECTION, $user );
	
	return $infoForm;
}

/**
 * getURLYear
 *
 * @return int
 */
function getURLYear($defaultValue = null) {
	global $YEAR_SELECTION;
	$year = getURLVariable ( $YEAR_SELECTION );
	if (! is_numeric ( $year )) {
		if (is_null ( $defaultValue )) {
			$year = date ( "Y" );
		} else {
			$year = $defaultValue;
		}
	}
	return $year;
}

/**
 * showTablePointageOneProjetCegid
 * Affichage tableau de pointage pour un seul projet
 * - modification par mois
 * - sommation automatique
 */
function showTablePointageOneProjetCegid($tableau="", $showColPointage="", $subparam) {
    
	showAction ( "function showTablePointageOneProjetCegid()" );
	// condition project
	global $ITEM_COMBOBOX_SELECTION;
	global $PROJECT_SELECTION;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
		// showSQLAction ( "No project Selected..." );
		// $projectName = "no project";
		$projectName = $ITEM_COMBOBOX_SELECTION;
	}
	
	// condition year
	$year = getURLYear ();
	
	global $USER_SELECTION;
	$userName = getURLVariable($USER_SELECTION);
	showSQLAction("user selected to transmit form  $USER_SELECTION : $userName" );
	
	if (!$tableau){
		// create tableau de pointage
		$tableau = getTableauPointageProjetCegid ( $projectName, "no" );
	}
	
	// ajout colonne total
	$colTotalName = "Total";
	$tableau = setSQLFlagType ( $tableau, $colTotalName, SQL_TYPE::SQL_REAL );
	$tableau = setSQLFlagTypeSize ( $tableau, $colTotalName, 4 );
	$tableau = setSQLFlagStatus ( $tableau, $colTotalName, "disabled" );
	
	$nbRows = mysqlNumrows ( $tableau );
	for($row = 0; $row < $nbRows; $row ++) {
		$tableau = setSQLValue ( $tableau, $colTotalName, $row, "mon total" );
	}
	
	if ($showColPointage==""){
	   global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
	   $showColPointage = $SQL_SHOW_COL_CEGID_POINTAGE2_2;
	}
	global $LIST_COLS_MONTHS;
	global $TABLE_ID;
	global $TABLE_OTHER;
	global $SHOW_COL_COUNT;
	
	// prepare tableau
	$colsFromSummation = $LIST_COLS_MONTHS . "," . $colTotalName;
	$columnsComplet = $showColPointage . "," . $colsFromSummation;
	$param2 = prepareParamShowArrayPointageProjetCegid ( $tableau, $projectName, $year, $columnsComplet, $userName );
	// $param2 [$TABLE_OTHER] = "onchange=\"sommeColonneRowHTMLTable(this,'$LIST_COLS_MONTHS', '$colTotalName', '$LIST_COLS_MONTHS')\"";
	$param2 [$TABLE_OTHER] = "onchange=\"sommeColonneRowHTMLTable(this,'$colsFromSummation', '$colTotalName', '$LIST_COLS_MONTHS')\"";
	
	if($subparam){
	  $param2 = updateParamSqlWithSubParam($param2, $subparam);
	}
	
	//global $TABLE_ID;
	//showAction("table id : $param2[$TABLE_ID]");
	
	// show table header
	showTableHeader ( $param2 );
	
	// show table data
	$html = "";
	beginFormTable ( $param2, $html, $tableau );
	showEditRowsTableData ( $param2, $html, $tableau );
	// showEditTableData($param2, "", $tableau);
	// showTableData ( $param2, "", $tableau , "no" /* closeTable */);
	
	// show sum row
	showTablelineSummation ( $param2, $columnsComplet, $colsFromSummation );
	showTableRowAction ( $param2, $tableau );
	endForm ();
	
	// close table
	// echo "</table>";
	endTable ();
	
	// lance les calcules de sommation
	$table_id = $param2 [$TABLE_ID];
	showSommation ( $table_id, $colsFromSummation, $colTotalName, $LIST_COLS_MONTHS );
}

/**
 * showTablePointageProjetCegid
 */
function showTablePointageProjetCegid() {
	// condition project
	global $PROJECT_SELECTION;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	// condition year
	$year = getURLYear ();
	//selection user
	global $USER_SELECTION;
	$userName = getURLVariable($USER_SELECTION);
	// create tableau de pointage
	$tableau = getTableauPointageProjetCegid ();
	// show tableau
	showArrayPointageProjetCegid ( $tableau, $projectName, $year, ""/*col lis*/, $userName );
}

/**
 * getTableauPointageProjetCegid
 *
 * @param string $projectName
 * @return array pointage
 */
function getTableauPointageProjetCegid($projectName = "", $showAll = "yes") {
	global $SQL_TABLE_CEGID_POINTAGE;
	$table_pointage = $SQL_TABLE_CEGID_POINTAGE;
	
	global $SQL_TABLE_CEGID_POINTAGE2;
	$table_pointage2 = $SQL_TABLE_CEGID_POINTAGE2;
	
	return getTableauPointageProjetCegid2($projectName, $showAll, $table_pointage, $table_pointage2);
}


/**
 * getTableauPointageProjetCegid2
 *
 * @param string $projectName        	
 * @return array pointage
 */
function getTableauPointageProjetCegid2($projectName = "", $showAll = "yes", $table_pointage,  $table_pointage2) {
    global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
    global $SQL_SELECT_COL_CEGID_POINTAGE2_2;
    global $FORM_TABLE_CEGID_POINTAGE2;
    global $SQL_SHOW_WHERE_CEGID_POINTAGE2;
    
    return getTableauPointageProjetCegid3($projectName, $showAll, $table_pointage, $table_pointage2, 
     $SQL_SHOW_COL_CEGID_POINTAGE2_2,
     $SQL_SELECT_COL_CEGID_POINTAGE2_2,
     $FORM_TABLE_CEGID_POINTAGE2,
     $SQL_SHOW_WHERE_CEGID_POINTAGE2
    );
}

// function getTableauPointageProjetCegid2($projectName = "", $showAll = "yes", $table_pointage,  $table_pointage2) {
//     global $SQL_SHOW_COL_CEGID_POINTAGE2_2; 
// 	global $SQL_SELECT_COL_CEGID_POINTAGE2_2;
// 	global $FORM_TABLE_CEGID_POINTAGE2;
// 	global $SQL_SHOW_WHERE_CEGID_POINTAGE2;
// 	$form_name = $FORM_TABLE_CEGID_POINTAGE2 . "_insert";
// 	$condition = $SQL_SHOW_WHERE_CEGID_POINTAGE2;
	
// 	global $LIST_COLS_MONTHS;
// 	global $ITEM_COMBOBOX_SELECTION;
	
// 	// condition project
// 	global $PROJECT_SELECTION;
// 	global $SQL_COL_NAME_PROJECT;
// 	if ($projectName == "") {
// 		$projectName = getURLVariable ( $PROJECT_SELECTION );
// 	}
// 	if ($projectName) {
// 		if ($projectName == "$ITEM_COMBOBOX_SELECTION") {
// 			// nothing to do
// 			// showSQLAction("no project selected");
// 		} else {
// 			$condition = $condition . " AND pj.$SQL_COL_NAME_PROJECT=\"$projectName\"";
// 		}
// 	}
	
// 	// condition user
// 	global $USER_SELECTION;
// 	global $SQL_COL_NAME_CEGID_USER;
// 	$userName = getURLVariable ( $USER_SELECTION );
// 	if ($userName) {
// 		if ($userName == "$ITEM_COMBOBOX_SELECTION") {
// 			// nothing to do
// 			// showSQLAction("no user selected");
// 		} else {
// 			$condition = $condition . " AND u.$SQL_COL_NAME_PROJECT=\"$userName\"";
// 		}
// 	}
	
// 	// showSQLAction ( "show all : $showAll" );
// 	if ($showAll == "yes") {
// 		// nothing to do
// 	} else {
// 		// showSQLAction ( "show project : $projectName user : $userName" );
// 		if (((! $projectName) || ($projectName == "$ITEM_COMBOBOX_SELECTION")) && ((! $userName) || ($userName == "$ITEM_COMBOBOX_SELECTION"))) {
// 			$condition = $condition . " AND 0";
// 		}
// 	}
	
// 	// requete selection User,profil/project
// 	$param = prepareshowTable ( $table_pointage2, $SQL_SHOW_COL_CEGID_POINTAGE2_2, $form_name, $condition );
// 	$param = modifierTableParamSql ( $param, /*$form =*/ $form_name, /*$insert =*/ "no", /*$edit =*/ "no", /*$delete =*/ "no", /*export csv*/ "no" );
// 	$param = updateParamSqlWithDistinct ( $param );
// 	$param = updateParamSqlColumnFilter ( $param, $SQL_SELECT_COL_CEGID_POINTAGE2_2 );
// 	$request = createRequeteTableData ( $param );
// 	showSQLAction ( $request );
// 	// showTableByParam ( $param );
	
// 	// condition year
// 	$year = getURLYear ();
// 	// global $YEAR_SELECTION;
// 	// $year = getURLVariable ( $YEAR_SELECTION );
// 	// if (! is_numeric ( $year )) {
// 	// $year = date ( "Y" );
// 	// }
	
// 	// showSQLAction("year = [$year]");
// 	global $SQL_COL_DATE_CEGID_POINTAGE;
// 	$conditionDateYear = "year(p.$SQL_COL_DATE_CEGID_POINTAGE)=\"$year\"";
// 	// showSQLAction("year = [$conditionDateYear]");
	
// 	global $LIST_COLS_MONTHS;
// 	$arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
	
// 	global $SQL_COL_PROJECT_ID_CEGID_POINTAGE;
// 	// global $SQL_COL_DATE_CEGID_POINTAGE;
// 	global $SQL_COL_USER_CEGID_POINTAGE;
// 	global $SQL_COL_PROFIL_CEGID_POINTAGE;
	
// 	// creation tableau resultat
// 	$Resultat = mysqlQuery ( $request );
// 	// showSQLError ( "", $request . "<br><br>" );
// 	// showSQLAction("columns : ".$SQL_SHOW_COL_CEGID_POINTAGE2_2);
// 	$nbRes = mysqlNumrows ( $Resultat );
// 	$columns = stringToArray ( $SQL_SHOW_COL_CEGID_POINTAGE2_2 );
	
// 	// set type des columns depuis le resultat Sql
// 	$ci = 0;
// 	$tableau = array ();
// 	foreach ( $columns as $c ) {
// 		$type2 = mysqlFieldType ( $Resultat, $ci );
// 		$tableau = setSQLFlagType ( $tableau, $c, $type2 );
// 		if ($c == "$SQL_COL_PROJECT_ID_CEGID_POINTAGE") {
// 			$tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
// 		} else if ($c == "$SQL_COL_USER_CEGID_POINTAGE") {
// 			$tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
// 		} else if ($c == "$SQL_COL_PROFIL_CEGID_POINTAGE") {
// 			$tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
// 		} else {
// 			$tableau = setSQLFlagStatus ( $tableau, $c, "disabled" );
// 			// echo "getTableauPointageProjetCegid() column $c is disabled <br>";
// 		}
// 		$ci ++;
// 	}
	
// 	foreach ( $arrayMonth as $m ) {
// 		$tableau = setSQLFlagType ( $tableau, $m, SQL_TYPE::SQL_REAL );
// 		$tableau = setSQLFlagTypeSize ( $tableau, $m, 3 );
// 	}
	
// 	for($cpt = 0; $cpt < $nbRes; $cpt ++) {
// 		foreach ( $columns as $c ) {
// 			$field_offset = indexOfValueInArray ( $columns, $c );
// 			// $colName = mysql_field_name ( $Resultat , $field_offset );
// 			// showSQLAction("result $cpt - column : $c - position : $field_offset - colName : $colName");
// 			$res = mysql_result ( $Resultat, $cpt, $field_offset );
// 			// $res = mysql_result ( $Resultat, $cpt, $c );
// 			$tableau [$c] [$cpt] = $res;
// 		}
		
// 		// U.O par date
// 		foreach ( $arrayMonth as $m ) {
// 			// valeurs des premieres colonnes
// 			$valueProject = $tableau [$SQL_COL_PROJECT_ID_CEGID_POINTAGE] [$cpt];
// 			$valueUser = $tableau [$SQL_COL_USER_CEGID_POINTAGE] [$cpt];
// 			$valueDate = $tableau [$SQL_COL_PROFIL_CEGID_POINTAGE] [$cpt];
			
// 			$month = array_search ( $m, $arrayMonth ) + 1;
			
// 			$reqDate = "select p.UO from $table_pointage as p WHERE $conditionDateYear ";
// 			$reqDate = $reqDate . " AND  month(p.$SQL_COL_DATE_CEGID_POINTAGE)=\"$month\"";
// 			$reqDate = $reqDate . " AND p.$SQL_COL_PROJECT_ID_CEGID_POINTAGE=\"$valueProject\"  ";
// 			$reqDate = $reqDate . " AND p.$SQL_COL_USER_CEGID_POINTAGE=\"$valueUser\"  ";
// 			$reqDate = $reqDate . " AND p.$SQL_COL_PROFIL_CEGID_POINTAGE=\"$valueDate\"  ";
// 			// showSQLAction($reqDate);
			
// 			$resDate = mysqlQuery ( $reqDate );
// 			$tableau [$m] [$cpt] = mysqlResult ( $resDate, 0, 0, "" );
// 		}
// 	}
	
// 	return $tableau;
// }

/**
 * getTableauPointageProjetCegid3
 *
 * @param string $projectName
 * @return array pointage
 */
function getTableauPointageProjetCegid3($projectName = "", $showAll = "yes", 
     $table_pointage,  $table_pointage2,  //tables
     $showColPointage,   $selectColPointage,  //columns
     $formName,
     $conditionPointage,
     $select="p.UO"){
    $form_name = $formName . "_insert";
    $condition = $conditionPointage;
    
    global $LIST_COLS_MONTHS;
    global $ITEM_COMBOBOX_SELECTION;
    
    // condition project
    global $PROJECT_SELECTION;
    global $SQL_COL_NAME_PROJECT;
    if ($projectName == "") {
        $projectName = getURLVariable ( $PROJECT_SELECTION );
    }
    if ($projectName) {
        if ($projectName == "$ITEM_COMBOBOX_SELECTION") {
            // nothing to do
            // showSQLAction("no project selected");
        } else {
            $condition = $condition . " AND pj.$SQL_COL_NAME_PROJECT=\"$projectName\"";
        }
    }
    
    // condition user
    global $USER_SELECTION;
    global $SQL_COL_NAME_CEGID_USER;
    $userName = getURLVariable ( $USER_SELECTION );
    if ($userName) {
        if ($userName == "$ITEM_COMBOBOX_SELECTION") {
            // nothing to do
            // showSQLAction("no user selected");
        } else {
            $condition = $condition . " AND u.$SQL_COL_NAME_PROJECT=\"$userName\"";
        }
    }
    
    // showSQLAction ( "show all : $showAll" );
    if ($showAll == "yes") {
        // nothing to do
    } else {
        // showSQLAction ( "show project : $projectName user : $userName" );
        if (((! $projectName) || ($projectName == "$ITEM_COMBOBOX_SELECTION")) && ((! $userName) || ($userName == "$ITEM_COMBOBOX_SELECTION"))) {
            $condition = $condition . " AND 0";
        }
    }
    
    // requete selection User,profil/project
    $param = prepareshowTable ( $table_pointage2, $showColPointage, $form_name, $condition );
    $param = modifierTableParamSql ( $param, /*$form =*/ $form_name, /*$insert =*/ "no", /*$edit =*/ "no", /*$delete =*/ "no", /*export csv*/ "no" );
    $param = updateParamSqlWithDistinct ( $param );
    $param = updateParamSqlColumnFilter ( $param, $selectColPointage );
    $request = createRequeteTableData ( $param );
    showSQLAction ( $request );
    // showTableByParam ( $param );
    
    // condition year
    $year = getURLYear ();
    // global $YEAR_SELECTION;
    // $year = getURLVariable ( $YEAR_SELECTION );
    // if (! is_numeric ( $year )) {
    // $year = date ( "Y" );
    // }
    
    // showSQLAction("year = [$year]");
    global $SQL_COL_DATE_CEGID_POINTAGE;
    $conditionDateYear = "year(p.$SQL_COL_DATE_CEGID_POINTAGE)=\"$year\"";
    // showSQLAction("year = [$conditionDateYear]");
    
    global $LIST_COLS_MONTHS;
    $arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
    
    global $SQL_COL_PROJECT_ID_CEGID_POINTAGE;
    // global $SQL_COL_DATE_CEGID_POINTAGE;
    global $SQL_COL_USER_CEGID_POINTAGE;
    global $SQL_COL_PROFIL_CEGID_POINTAGE;
    
    // creation tableau resultat
    $Resultat = mysqlQuery ( $request );
    // showSQLError ( "", $request . "<br><br>" );
    // showSQLAction("columns : ".$SQL_SHOW_COL_CEGID_POINTAGE2_2);
    $nbRes = mysqlNumrows ( $Resultat );
    $columns = stringToArray ( $showColPointage );
    
    // set type des columns depuis le resultat Sql
    $ci = 0;
    $tableau = array ();
    foreach ( $columns as $c ) {
        $type2 = mysqlFieldType ( $Resultat, $ci );
        $tableau = setSQLFlagType ( $tableau, $c, $type2 );
        if ($c == "$SQL_COL_PROJECT_ID_CEGID_POINTAGE") {
            $tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
        } else if ($c == "$SQL_COL_USER_CEGID_POINTAGE") {
            $tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
        } else if ($c == "$SQL_COL_PROFIL_CEGID_POINTAGE") {
            $tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
        } else {
            $tableau = setSQLFlagStatus ( $tableau, $c, "disabled" );
            // echo "getTableauPointageProjetCegid() column $c is disabled <br>";
        }
        $ci ++;
    }
    
    foreach ( $arrayMonth as $m ) {
        $tableau = setSQLFlagType ( $tableau, $m, SQL_TYPE::SQL_REAL );
        $tableau = setSQLFlagTypeSize ( $tableau, $m, 3 );
    }
    
    for($cpt = 0; $cpt < $nbRes; $cpt ++) {
        foreach ( $columns as $c ) {
            $field_offset = indexOfValueInArray ( $columns, $c );
            // $colName = mysql_field_name ( $Resultat , $field_offset );
            // showSQLAction("result $cpt - column : $c - position : $field_offset - colName : $colName");
            $res = mysql_result ( $Resultat, $cpt, $field_offset );
            // $res = mysql_result ( $Resultat, $cpt, $c );
            $tableau [$c] [$cpt] = $res;
        }
        
        // U.O par date
        foreach ( $arrayMonth as $m ) {
            // valeurs des premieres colonnes
            $valueProject = $tableau [$SQL_COL_PROJECT_ID_CEGID_POINTAGE] [$cpt];
            $valueUser = $tableau [$SQL_COL_USER_CEGID_POINTAGE] [$cpt];
            $valueDate = $tableau [$SQL_COL_PROFIL_CEGID_POINTAGE] [$cpt];
            
            $month = array_search ( $m, $arrayMonth ) + 1;
            
            $reqDate = "select $select from $table_pointage as p WHERE $conditionDateYear ";
            $reqDate = $reqDate . " AND  month(p.$SQL_COL_DATE_CEGID_POINTAGE)=\"$month\"";
            if ($valueProject!="") $reqDate = $reqDate . " AND p.$SQL_COL_PROJECT_ID_CEGID_POINTAGE=\"$valueProject\"  ";
            $reqDate = $reqDate . " AND p.$SQL_COL_USER_CEGID_POINTAGE=\"$valueUser\"  ";
            if ($valueDate!="") $reqDate = $reqDate . " AND p.$SQL_COL_PROFIL_CEGID_POINTAGE=\"$valueDate\"  ";
            //showSQLAction($reqDate);
            
            $resDate = mysqlQuery ( $reqDate );
            $tableau [$m] [$cpt] = mysqlResult ( $resDate, 0, 0, "" );
        }
    }
    
    return $tableau;
}


/**
 * prepareParam for ShowArrayPointageProjetCegid
 *
 * @param unknown $tableau        	
 * @param unknown $projectName        	
 * @param unknown $year        	
 */
function prepareParamShowArrayPointageProjetCegid($tableau, $projectName, $year, $columnsComplet = "", $userName="") {
	global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
	global $LIST_COLS_MONTHS;
	global $YEAR_SELECTION;
	global $PROJECT_SELECTION;
	global $USER_SELECTION;
	global $TABLE_FORM_INFO;
	
	if ($columnsComplet == "") {
		$columnsComplet = $SQL_SHOW_COL_CEGID_POINTAGE2_2 . "," . $LIST_COLS_MONTHS;
	}
	if ($tableName==""){
	    $tableName="ma_table_pointage";
	}
	$param2 = prepareshowTable ( "ma_table_pointage", $columnsComplet, "form_table_cegid_pointage_update" );
	// showTableHeader ( $param2 );
	
	global $TABLE_UPDATE;
	global $TABLE_INSERT;
	global $TABLE_EXPORT_CSV;
	$param2 [$TABLE_UPDATE] = "yes";
	$param2 [$TABLE_INSERT] = "no";
	$param2 [$TABLE_EXPORT_CSV] = "yes";
	
	// info formulaire
	$infoForm = streamFormHidden ( $YEAR_SELECTION, $year );
	$infoForm = $infoForm . streamFormHidden ( $PROJECT_SELECTION, $projectName );
	if ($userName!=""){
	    $infoForm = $infoForm . streamFormHidden ( $USER_SELECTION, $userName );
	}
	$param2 [$TABLE_FORM_INFO] = $infoForm;
	
	return $param2;
}

/**
 * showArrayPointageProjetCegid
 *
 * @param unknown $tableau        	
 * @param unknown $projectName        	
 * @param unknown $year        	
 */
function showArrayPointageProjetCegid($tableau, $projectName, $year, $columnsComplet = "", $userName = "" ) {
	$param2 = prepareParamShowArrayPointageProjetCegid ( $tableau, $projectName, $year, $columnsComplet, $userName );
	
	// show
	showTableHeader ( $param2 );
	showTableData ( $param2, "", $tableau );
}

?>