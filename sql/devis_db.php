<?PHP
$DEVIS_DB_PHP = "loaded";

$SQL_TABLE_DEVIS         = "cegid_devis_project";
$FORM_TABLE_CEGID_DEVIS  = "form_table_cegid_devis";

$SQL_COL_ID_DEVIS           = "ID";
$SQL_COL_NAME_DEVIS         = "NAME";
$SQL_COL_VERSION_DEVIS      = "VERSION";
$SQL_COL_STATUS_DEVIS       = "STATUS";
$SQL_COL_COMMENTAIRE_DEVIS  = "COMMENTAIRE";

$SQL_LABEL_DEVIS_NAME = "DEVIS";


$SQL_SHOW_COL_DEVIS     = "$SQL_COL_ID_DEVIS, $SQL_COL_NAME_DEVIS, $SQL_COL_VERSION_DEVIS, $SQL_COL_STATUS_DEVIS";
$SQL_SHOW_ALL_COL_DEVIS = $SQL_SHOW_COL_DEVIS.",  $SQL_COL_COMMENTAIRE_DEVIS";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");




/**
 * application des actions sur la page projet
 * 
 * @return number 0 nothing 1 action traitée
 */
function applyGestionDevis() {
    global $SQL_SHOW_COL_DEVIS;
    global $SQL_SHOW_ALL_COL_DEVIS;
    $colDEVIS = $SQL_SHOW_ALL_COL_DEVIS;
    global $SQL_TABLE_DEVIS;
	global $FORM_TABLE_CEGID_DEVIS;
	$form_name = $FORM_TABLE_CEGID_DEVIS."_update";

	//traitement du update
	$res = updateTableByGet ($SQL_TABLE_DEVIS, $colDEVIS, $form_name, "no"/** re-edit */ );
	
	//cas classique : edit, export, ...
	if ($res<=0){
	    $res =  applyGestionTable($SQL_TABLE_DEVIS, $colDEVIS, $form_name);
	}
	return $res;
}


/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableDEVIS() {
	global $SQL_SHOW_COL_DEVIS;
	global $SQL_TABLE_DEVIS;
	global $FORM_TABLE_CEGID_DEVIS;
	global $TABLE_EXPORT_CSV;
	$form_name = $FORM_TABLE_CEGID_DEVIS."_insert";
	$condition="";
	
	//showSQLAction("showTableDEVIS - ...");
	
	//showTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name);
	$param = prepareshowTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name, $condition);
	//par defaut on a edit & delete
	
	//ajout export CSV
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	//ajout edit pointage
	global $URL_ROOT_POINTAGE;
	$url = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
	//showSQLAction("showTableProject - addParamActionCommand()");
	$param = addParamActionCommand($param, $url, "pointage!", LabelAction::ACTION_POINTAGE );
	//showSQLAction("showTableProject - addParamActionCommand() retour");
	
	
	//showSQLAction("showTableProject - showTableByParam()");
	showTableByParam($param);
	
}



?>