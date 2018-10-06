<?PHP
$FILE_CODE_DB_PHP = "loaded";

$SQL_TABLE_FILE_CODE         = "cegid_file_code";
$FORM_TABLE_CEGID_FILE_CODE  = "form_table_file_code";
$SQL_COL_ID_FILE_CODE    = "ID";
$SQL_COL_NAME_FILE_CODE  = "NAME";
$SQL_COL_ORDRE_FILE_CODE = "ORDRE";

$SQL_SHOW_COL_FILE_CODE = "$SQL_COL_ID_FILE_CODE, $SQL_COL_NAME_FILE_CODE, $SQL_COL_ORDRE_FILE_CODE";

include_once 'table_db.php';

/**
 * application des actions sur la page status project
 */
function applyGestionFileCode() {
    global $SQL_SHOW_COL_FILE_CODE;
    global $SQL_TABLE_FILE_CODE;
    global $FORM_TABLE_CEGID_FILE_CODE;
    $form_name = $FORM_TABLE_CEGID_FILE_CODE."_update";
	
    applyGestionTable($SQL_TABLE_FILE_CODE, $SQL_SHOW_COL_FILE_CODE, $form_name);
}


 /**
  * affiche les versions des elements du FILE_CODE
  * (description)
  */
 function showTableFileCode() {
     global $SQL_SHOW_COL_FILE_CODE;
     global $SQL_TABLE_FILE_CODE;
     global $FORM_TABLE_CEGID_FILE_CODE;
     $form_name = $FORM_TABLE_CEGID_FILE_CODE."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_FILE_CODE, $SQL_SHOW_COL_FILE_CODE, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>