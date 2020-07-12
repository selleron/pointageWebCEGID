<?PHP
$PROFILS_DB_PHP = "loaded";

$SQL_TABLE_PROFILS       = "cegid_profil";
$FORM_TABLE_CEGID_PROFIL = "form_table_profils";

$SQL_COL_ID_PROFIL       = "ID";
$SQL_COL_NAME_PROFIL     = "NAME";
$SQL_COL_VISIBLE_PROFIL  = "VISIBLE";

$SQL_SHOW_COL_PROFIL = "$SQL_COL_ID_PROFIL, $SQL_COL_NAME_PROFIL, $SQL_COL_VISIBLE_PROFIL";

include_once 'table_db.php';

/**
 * application des actions sur la page projet
 */
function applyGestionProfils() {
	global $SQL_SHOW_COL_PROFIL;
	global $SQL_TABLE_PROFILS;
	global $FORM_TABLE_CEGID_PROFIL;
	$form_name = $FORM_TABLE_CEGID_PROFIL."_update";
	
	applyGestionTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
}


 /**
  * affiche les versions des elements du projet
  * (description)
  */
 function showTableProfils() {
 	global $SQL_SHOW_COL_PROFIL;
 	global $SQL_TABLE_PROFILS;
 	global $FORM_TABLE_CEGID_PROFIL;
 	$form_name = $FORM_TABLE_CEGID_PROFIL."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
	$param = prepareshowTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>