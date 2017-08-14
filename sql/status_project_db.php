<?PHP
$STATUS_PROJECT_DB_PHP = "loaded";

$SQL_TABLE_STATUS_PROJECT         = "cegid_status_project";
$FORM_TABLE_CEGID_STATUS_PROJECT  = "form_table_status_project";
$SQL_COL_ID_STATUS_PROJECT    = "ID";
$SQL_COL_NAME_STATUS_PROJECT  = "NAME";
$SQL_COL_ORDRE_STATUS_PROJECT = "ORDRE";

$SQL_SHOW_COL_STATUS_PROJECT = "$SQL_COL_ID_STATUS_PROJECT, $SQL_COL_NAME_STATUS_PROJECT, $SQL_COL_ORDRE_STATUS_PROJECT";

include_once 'table_db.php';

/**
 * application des actions sur la page status project
 */
function applyGestionStatusProject() {
    global $SQL_SHOW_COL_STATUS_PROJECT;
    global $SQL_TABLE_STATUS_PROJECT;
    global $FORM_TABLE_CEGID_STATUS_PROJECT;
    $form_name = $FORM_TABLE_CEGID_STATUS_PROJECT."_update";
	
    applyGestionTable($SQL_TABLE_STATUS_PROJECT, $SQL_SHOW_COL_STATUS_PROJECT, $form_name);
}


 /**
  * affiche les versions des elements du STATUS_PROJECT
  * (description)
  */
 function showTableStatusProject() {
     global $SQL_SHOW_COL_STATUS_PROJECT;
     global $SQL_TABLE_STATUS_PROJECT;
     global $FORM_TABLE_CEGID_STATUS_PROJECT;
     $form_name = $FORM_TABLE_CEGID_STATUS_PROJECT."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_STATUS_PROJECT, $SQL_SHOW_COL_STATUS_PROJECT, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>