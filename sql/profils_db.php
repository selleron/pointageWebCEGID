<?PHP
$PROFILS_DB_PHP = "loaded";

$SQL_TABLE_PROFILS       = "cegid_profil";
$FORM_TABLE_CEGID_PROFIL = "form_table_profils";

$SQL_COL_ID_PROFIL       = "ID";
$SQL_COL_NAME_PROFIL     = "NAME";
$SQL_COL_VISIBLE_PROFIL  = "VISIBLE";

$SQL_SHOW_COL_PROFIL = "$SQL_COL_ID_PROFIL, $SQL_COL_NAME_PROFIL, $SQL_COL_VISIBLE_PROFIL";

$ID_REQUETE_SQL_PROFIL_ARCHIVABLE = "ID_REQUETE_SQL_PROFIL_ARCHIVABLE";

include_once 'table_db.php';
include_once (dirname ( __FILE__ ) . "/requetes_db.php");
include_once (dirname ( __FILE__ ) . "/ca_previsionel_db.php");  //dependance à modifier

/**
 * application des actions sur la page projet
 */
function applyGestionProfils() {
	global $SQL_SHOW_COL_PROFIL;
	global $SQL_TABLE_PROFILS;
	global $FORM_TABLE_CEGID_PROFIL;
	$form_name = $FORM_TABLE_CEGID_PROFIL."_update";
	
	
	if (getActionGet () == "profils archivables"){
	    global $TRACE_PROFIL;
	    global $ID_REQUETE_SQL_PROFIL_ARCHIVABLE;
	    showActionVariable("action [ profils archivables ] detected", $TRACE_PROFIL);
	    showDescriptionRequeteCEGID($ID_REQUETE_SQL_PROFIL_ARCHIVABLE);
	    showTableRequeteCEGID( $ID_REQUETE_SQL_PROFIL_ARCHIVABLE );
	    $res=1;
	}
	else {
	    $res = applyGestionTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
	}
	return $res;
}


 /**
  * affiche les versions des elements du projet
  * (description)
  */
 function showTableProfils($condition="") {
 	global $SQL_SHOW_COL_PROFIL;
 	global $SQL_TABLE_PROFILS;
 	global $FORM_TABLE_CEGID_PROFIL;
 	$form_name = $FORM_TABLE_CEGID_PROFIL."_insert";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
	$param = prepareshowTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

?>