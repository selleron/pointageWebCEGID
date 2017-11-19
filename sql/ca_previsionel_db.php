<?PHP
$DEVIS_DB_PHP = "loaded";

$ID_REQUETE_SQL_CA_PREVISIONEL      = "Previsionnel CA_projet";
$ID_REQUETE_SQL_CA_PREVISIONEL_CLOS = "Previsionnel CA_projet_clos";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");
include_once (dirname ( __FILE__ ) . "/tool_db.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/requetes_db.php");


/**
 * application des actions sur la page status project
 */
function applyGestionCAPrevisionel($idRequest="") {
    global $ID_REQUETE_SQL_CA_PREVISIONEL;
    
    if ($idRequest==""){
        $idRequest=$ID_REQUETE_SQL_CA_PREVISIONEL;
    }
    
    $request = getRequeteByID($idRequest);
    $col="";
    $form_name="form_ca_previsionel";    
    
    applyGestionTable($request, $col, $form_name);
}





/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableCAPrevisionel($idRequest="") {
    global $ID_REQUETE_SQL_CA_PREVISIONEL;
    global $TABLE_EXPORT_CSV;
    $html="";
    
    if ($idRequest==""){
        $idRequest=$ID_REQUETE_SQL_CA_PREVISIONEL;
    }

    $request = getRequeteByID($idRequest);
    
	//ajout table id & export CSV
    $subParam[PARAM_TABLE_TABLE::TABLE_ID]="table_ca_prev";
	$subParam[$TABLE_EXPORT_CSV] = "yes";
	
// 	global $URL_ROOT_POINTAGE;
// 	$urlPointage = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
// 	$urlPrevision = "$URL_ROOT_POINTAGE/user/pointage_prevision_cegid.php";
// 	//showSQLAction("showTableProject - addParamActionCommand()");
// 	$param = addParamActionCommand($param, $urlPointage   , "pointage!"   , LabelAction::ACTION_POINTAGE );
// 	$param = addParamActionCommand($param, $urlPrevision  , "prevision!"  , LabelAction::ACTION_POINTAGE );
	
	$closeTable="false";
	$param2 = actionRequeteSql($request,$html, $subParam, $closeTable);
	$colsAll= arrayToString($param2[PARAM_TABLE_SQL::COLUMNS_SUMMARY]);
	$colsFromSummation= $colsAll;
	
	// show sum row
	showTablelineSummation($param2, $colsAll, $colsFromSummation);
	
	// close table
 	endTableRow();
 	endForm();
 	endTable();
	
 	// lance les calcules de sommation
	$table_id = $param2[PARAM_TABLE_TABLE::TABLE_ID];
 	showSommation($table_id, $colsFromSummation, "", "");
	
	
}



?>