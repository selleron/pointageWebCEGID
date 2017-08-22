<?PHP
$CEGID_FILE_DB_PHP = "loaded";

include_once 'files_db.php';

$SQL_TABLE_CEGID_FILE         = "cegid_file";
$SQL_TABLE_CEGID_FILE2         = "cegid_file cf, files f";

$FORM_TABLE_CEGID_CEGID_FILE  = "form_table_cegid_file";

$SQL_COL_REFERENCE_CEGID_FILE      = "REFERENCE";
$SQL_COL_FILE_CEGID_FILE           = "FILE";
$SQL_COL_VERSION_CEGID_FILE        = "VERSION";
$SQL_COL_COMMENTAIRE_CEGID_FILE    = "COMMENTAIRE";

global $SQL_COL_ID_FILE;
global $SQL_COL_TITLE_FILE;
global $SQL_COL_VERSION_FILE;


$SQL_SHOW_COL_CEGID_FILE      = "$SQL_COL_REFERENCE_CEGID_FILE, $SQL_COL_FILE_CEGID_FILE, $SQL_COL_VERSION_CEGID_FILE, $SQL_COL_COMMENTAIRE_CEGID_FILE";
$SQL_SHOW_COL_CEGID_FILE2     = "   $SQL_COL_REFERENCE_CEGID_FILE,   $SQL_COL_TITLE_FILE,    $SQL_COL_FILE_CEGID_FILE,    $SQL_COL_VERSION_CEGID_FILE,    $SQL_COL_COMMENTAIRE_CEGID_FILE";
$SQL_SELECT_COL_CEGID_FILE2   = "cf.$SQL_COL_REFERENCE_CEGID_FILE, f.$SQL_COL_TITLE_FILE, cf.$SQL_COL_FILE_CEGID_FILE, cf.$SQL_COL_VERSION_CEGID_FILE, cf.$SQL_COL_COMMENTAIRE_CEGID_FILE";

$SQL_CONDITION_CEGID_FILE2 = "cf.$SQL_COL_FILE_CEGID_FILE=f.$SQL_COL_ID_FILE AND (cf.$SQL_COL_VERSION_CEGID_FILE=0 OR cf.$SQL_COL_VERSION_CEGID_FILE=f.$SQL_COL_VERSION_FILE)";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");





function applyGestionCEGID_FILE() {
    global $SQL_SHOW_COL_CEGID_FILE;
        $colCEGID_FILE = $SQL_SHOW_COL_CEGID_FILE;
    global $SQL_TABLE_CEGID_FILE;
	global $FORM_TABLE_CEGID_CEGID_FILE;
	$form_name = $FORM_TABLE_CEGID_CEGID_FILE."_update";

	$res=0;
	//traitement du update
	$res = updateTableByGet ($SQL_TABLE_CEGID_FILE, $colCEGID_FILE, $form_name, "no"/** re-edit */ );
	
	//cas classique : edit, export, ...
	if ($res<=0){
	    $res =  applyGestionTable($SQL_TABLE_CEGID_FILE, $colCEGID_FILE, $form_name);
	}
	return $res;
}


function showTableCEGID_FILE() {
    global $SQL_TABLE_CEGID_FILE2;
    global $SQL_SHOW_COL_CEGID_FILE2;
    global $SQL_SELECT_COL_CEGID_FILE2;
    global $FORM_TABLE_CEGID_CEGID_FILE;
	global $SQL_CONDITION_CEGID_FILE2;
	$form_name = $FORM_TABLE_CEGID_CEGID_FILE."_insert";
	$condition="$SQL_CONDITION_CEGID_FILE2";
	
	//showSQLAction("showTableCEGID_FILE - ...");
	
	$param = prepareshowTable($SQL_TABLE_CEGID_FILE2, $SQL_SHOW_COL_CEGID_FILE2, $form_name, $condition);
	$param[PARAM_TABLE_SQL::COLUMNS_FILTER] = "$SQL_SELECT_COL_CEGID_FILE2";
	//par defaut on a edit & delete
	
	
	//si on veut que les lignes soient editable
	//$param[PARAM_TABLE_ACTION::TABLE_UPDATE] = "yes";
	
	//ajout export CSV
	$param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
	
	//showSQLAction("showTableProject - showTableByParam()");
	showTableByParam($param);
	
}



?>