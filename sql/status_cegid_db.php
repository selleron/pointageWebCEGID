<?PHP
$STATUS_CEGID_DB_PHP = "loaded";

$SQL_TABLE_STATUS_CEGID         = "cegid_status_cegid";
$FORM_TABLE_CEGID_STATUS_CEGID  = "form_table_status_cegid";
$SQL_COL_ID_STATUS_CEGID        = "ID";
$SQL_COL_NAME_STATUS_CEGID      = "NAME";
$SQL_COL_ORDRE_STATUS_CEGID     = "ORDRE";

$SQL_SHOW_COL_STATUS_CEGID = "$SQL_COL_ID_STATUS_CEGID, $SQL_COL_NAME_STATUS_CEGID, $SQL_COL_ORDRE_STATUS_CEGID";

include_once 'table_db.php';

/**
 * application des actions sur la page status project
 */
function applyGestionStatusCegid() {
    global $SQL_SHOW_COL_STATUS_CEGID;
    global $SQL_TABLE_STATUS_CEGID;
    global $FORM_TABLE_CEGID_STATUS_CEGID;
    $form_name = $FORM_TABLE_CEGID_STATUS_CEGID."_update";
	
    applyGestionTable($SQL_TABLE_STATUS_CEGID, $SQL_SHOW_COL_STATUS_CEGID, $form_name);
}


 /**
  * affiche les versions des elements du STATUS_CEGID
  * (description)
  */
 function showTableStatusCegid() {
     global $SQL_SHOW_COL_STATUS_CEGID;
     global $SQL_TABLE_STATUS_CEGID;
     global $FORM_TABLE_CEGID_STATUS_CEGID;
     $form_name = $FORM_TABLE_CEGID_STATUS_CEGID."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_STATUS_CEGID, $SQL_SHOW_COL_STATUS_CEGID, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>