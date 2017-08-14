<?PHP
$STATUS_DEVIS_DB_PHP = "loaded";

$SQL_TABLE_STATUS_DEVIS         = "cegid_status_devis";
$FORM_TABLE_CEGID_STATUS_DEVIS  = "form_table_status_devis";
$SQL_COL_ID_STATUS_DEVIS    = "ID";
$SQL_COL_NAME_STATUS_DEVIS  = "NAME";
$SQL_COL_ORDRE_STATUS_DEVIS = "ORDRE";

$SQL_SHOW_COL_STATUS_DEVIS = "$SQL_COL_ID_STATUS_DEVIS, $SQL_COL_NAME_STATUS_DEVIS, $SQL_COL_ORDRE_STATUS_DEVIS";

include_once 'table_db.php';

/**
 * application des actions sur la page status project
 */
function applyGestionStatusDevis() {
    global $SQL_SHOW_COL_STATUS_DEVIS;
    global $SQL_TABLE_STATUS_DEVIS;
    global $FORM_TABLE_CEGID_STATUS_DEVIS;
    $form_name = $FORM_TABLE_CEGID_STATUS_DEVIS."_update";
	
    applyGestionTable($SQL_TABLE_STATUS_DEVIS, $SQL_SHOW_COL_STATUS_DEVIS, $form_name);
}


 /**
  * affiche les versions des elements du STATUS_DEVIS
  * (description)
  */
 function showTableStatusDevis() {
     global $SQL_SHOW_COL_STATUS_DEVIS;
     global $SQL_TABLE_STATUS_DEVIS;
     global $FORM_TABLE_CEGID_STATUS_DEVIS;
     $form_name = $FORM_TABLE_CEGID_STATUS_DEVIS."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_STATUS_DEVIS, $SQL_SHOW_COL_STATUS_DEVIS, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>