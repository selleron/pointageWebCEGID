<?PHP

$VERSION_DB_PHP="loaded";

$SQL_TABLE_VERSION="version";

$SQL_COL_ID_VERSION="id";
$SQL_COL_ORDER_VERSION="order";
$SQL_COL_DESCRIPTION_VERSION="description";
$SQL_COL_DATE_VERSION="DATE";
$SQL_COL_VALEUR_VERSION="value";
$SQL_COL_ORDER_VERSION="`order`";

$SQL_SHOW_COL_VERSION="$SQL_COL_ID_VERSION, $SQL_COL_DATE_VERSION, $SQL_COL_DESCRIPTION_VERSION, $SQL_COL_VALEUR_VERSION";
$SQL_SHOW_COL_SUMMARY_VERSION="$SQL_COL_ID_VERSION, $SQL_COL_DATE_VERSION, $SQL_COL_VALEUR_VERSION";


include_once 'connection_db.php';
include_once 'tool_db.php';


/**
 * affiche les versions des elements du projet
 * (summary)
 */
function showSummaryTableVersion(){
	global $SQL_SHOW_COL_SUMMARY_VERSION;
	global $SQL_TABLE_VERSION;
	global $TABLE_SIZE;
	
	global $SQL_COL_ORDER_VERSION;
	$condition = "$SQL_COL_ORDER_VERSION <= 10";
	
	$param = createDefaultParamSql($SQL_TABLE_VERSION, $SQL_SHOW_COL_SUMMARY_VERSION, $condition);

	//show request
	$sql = createRequeteTableData($param);
	showSQLAction($sql);
	//end show request
	
	$param[$TABLE_SIZE]=740;
	showTableHeader($param);
	showTableData($param);	
}

/**
 * affiche les versions des elements du projet
 * (summary + description)
 */
function showTableVersion(){
	global $SQL_SHOW_COL_VERSION;
	global $SQL_TABLE_VERSION;
	global $TABLE_SIZE;
	$param = createDefaultParamSql($SQL_TABLE_VERSION, $SQL_SHOW_COL_VERSION);

	$param[$TABLE_SIZE]=740;
	showTableHeader($param);
	showTableData($param);
}

?>