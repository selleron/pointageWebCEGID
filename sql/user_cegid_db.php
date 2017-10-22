<?PHP
$PROFILS_DB_PHP = "loaded";

$SQL_TABLE_CEGID_USER  = "cegid_user";
$FORM_TABLE_CEGID_USER = "form_table_cegid_user";

$SQL_COL_ID_CEGID_USER       = "ID";
$SQL_COL_NAME_CEGID_USER     = "NAME";
$SQL_COL_NOM_CEGID_USER      = "NOM";
$SQL_COL_PRENOM_CEGID_USER   = "PRENOM";
$SQL_COL_PROFIL_CEGID_USER   = "PROFIL";

$SQL_COL_SOCIETE_CEGID_USER  = "SOCIETE";
$SQL_COL_STATUS_CEGID_USER   = "STATUS";
$SQL_COL_ARRIVEE_CEGID_USER  = "ARRIVEE";
$SQL_COL_DEPART_CEGID_USER   = "DEPART";

$SQL_SHOW_SHORT_COL_CEGID_USER   = "$SQL_COL_ID_CEGID_USER, $SQL_COL_NAME_CEGID_USER, $SQL_COL_NOM_CEGID_USER, $SQL_COL_PRENOM_CEGID_USER, $SQL_COL_SOCIETE_CEGID_USER,  $SQL_COL_STATUS_CEGID_USER";
$SQL_SHOW_COL_CEGID_USER         = "$SQL_COL_ID_CEGID_USER, $SQL_COL_NAME_CEGID_USER, $SQL_COL_NOM_CEGID_USER, $SQL_COL_PRENOM_CEGID_USER, $SQL_COL_PROFIL_CEGID_USER, $SQL_COL_SOCIETE_CEGID_USER,  $SQL_COL_STATUS_CEGID_USER, $SQL_COL_ARRIVEE_CEGID_USER, $SQL_COL_DEPART_CEGID_USER";
$SQL_SHOW_INSERT_COL_CEGID_USER  = "$SQL_COL_ID_CEGID_USER, $SQL_COL_NAME_CEGID_USER, $SQL_COL_NOM_CEGID_USER, $SQL_COL_PRENOM_CEGID_USER, $SQL_COL_PROFIL_CEGID_USER, $SQL_COL_SOCIETE_CEGID_USER,  $SQL_COL_STATUS_CEGID_USER";

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
  * affiche les versions des elements du user
  * (description)
  */
 function showTableUserCEGID($condition="") {
 	global $SQL_SHOW_COL_CEGID_USER;
 	global $SQL_TABLE_CEGID_USER;
 	global $FORM_TABLE_CEGID_USER;
 	$form_name = $FORM_TABLE_CEGID_USER."_insert";
 	
 	//showTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
	$param = prepareshowTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name, $condition);
	$param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }
 
 /**
  * table pour l'insert du user
  * (description)
  */
 function showOnlyInsertTableUserCEGID($condition="") {
     global $SQL_SHOW_INSERT_COL_CEGID_USER;
     global $SQL_TABLE_CEGID_USER;
     global $FORM_TABLE_CEGID_USER;
     $form_name = $FORM_TABLE_CEGID_USER."_insert";
     
     //showTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
     $param = prepareshowTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_INSERT_COL_CEGID_USER, $form_name, $condition);
     $param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "no";
     showOnlyInsertTableByParam("","",$param);
 }
 
 /**
  * affiche la table version courte des utilisateurs
  * - pas d'insertion
  * - edition possible
  */
 function showShortTableUserCEGID($condition="") {
     global $SQL_SHOW_SHORT_COL_CEGID_USER;
     global $SQL_TABLE_CEGID_USER;
     global $FORM_TABLE_CEGID_USER;
     $form_name = $FORM_TABLE_CEGID_USER."_short";
     
     
     
     //showTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
     $param = prepareshowTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_SHORT_COL_CEGID_USER, $form_name, $condition);
     $param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
     $param[PARAM_TABLE_ACTION::TABLE_INSERT] = "no";
     
     showTableByParam($param);
 }
 
 
?>