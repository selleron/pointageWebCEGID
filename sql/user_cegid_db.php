<?PHP
$PROFILS_DB_PHP = "loaded";

$SQL_TABLE_CEGID_USER  = "cegid_user";
$FORM_TABLE_CEGID_USER = "form_table_cegid_user";

$SQL_COL_ID_CEGID_USER = "ID";
$SQL_COL_NAME_CEGID_USER = "NAME";
$SQL_COL_NOM_CEGID_USER = "NOM";
$SQL_COL_PRENOM_CEGID_USER = "PRENOM";
$SQL_COL_PROFIL_CEGID_USER = "PROFIL";

$SQL_SHOW_COL_CEGID_USER = "$SQL_COL_ID_CEGID_USER, $SQL_COL_NAME_CEGID_USER, $SQL_COL_NOM_CEGID_USER, $SQL_COL_PRENOM_CEGID_USER, $SQL_COL_PROFIL_CEGID_USER";

include_once 'table_db.php';

/**
 * application des actions sur la page projet
 */
function applyGestionUserCEGID() {
	global $SQL_SHOW_COL_CEGID_USER;
	global $SQL_TABLE_CEGID_USER;
	global $FORM_TABLE_CEGID_USER;
	$form_name = $FORM_TABLE_CEGID_USER."_update";
	
	applyGestionTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
}


 /**
  * affiche les versions des elements du projet
  * (description)
  */
 function showTableUserCEGID() {
 	global $SQL_SHOW_COL_CEGID_USER;
 	global $SQL_TABLE_CEGID_USER;
 	global $FORM_TABLE_CEGID_USER;
 	$form_name = $FORM_TABLE_CEGID_USER."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
	$param = prepareshowTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>