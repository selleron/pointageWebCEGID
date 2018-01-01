<?PHP
$STATUS_CEGID_DB_PHP = "loaded";

$SQL_TABLE_STATUS_CEGID         = "cegid_status_cegid";
$FORM_TABLE_CEGID_STATUS_CEGID  = "form_table_status_cegid";
$SQL_COL_ID_STATUS_CEGID        = "ID";
$SQL_COL_NAME_STATUS_CEGID      = "NAME";
$SQL_COL_ORDRE_STATUS_CEGID     = "ORDRE";
$SQL_SHOW_COL_STATUS_CEGID = "$SQL_COL_ID_STATUS_CEGID, $SQL_COL_NAME_STATUS_CEGID, $SQL_COL_ORDRE_STATUS_CEGID";

$SQL_TABLE_STATUS_COMMANDE         = "cegid_status_commande";
$FORM_TABLE_CEGID_STATUS_COMMANDE  = "form_table_status_commande";
#$SQL_SHOW_COL_STATUS_COMMANDE= "$SQL_COL_ID_STATUS_CEGID, $SQL_COL_NAME_STATUS_CEGID, $SQL_COL_ORDRE_STATUS_CEGID";

$SQL_TABLE_TYPE_PROJECT         = "cegid_type_project";
$FORM_TABLE_TYPE_PROJECT        = "form_table_type_project";

$SQL_TABLE_STATUS_VISIBLE          = "cegid_status_visible";
$FORM_TABLE_CEGID_STATUS_VISIBLE   = "form_table_status_visible";



include_once 'table_db.php';

/**
 * application des actions sur la page status generic CEGID
 */
function applyGestionStatusCegid() {
    global $SQL_SHOW_COL_STATUS_CEGID;
    global $SQL_TABLE_STATUS_CEGID;
    global $FORM_TABLE_CEGID_STATUS_CEGID;
    $form_name = $FORM_TABLE_CEGID_STATUS_CEGID."_update";
    
    applyGestionTable($SQL_TABLE_STATUS_CEGID, $SQL_SHOW_COL_STATUS_CEGID, $form_name);
}

function applyGestionStatusVisible() {
    global $SQL_SHOW_COL_STATUS_CEGID;
    global $SQL_TABLE_STATUS_VISIBLE;
    global $FORM_TABLE_CEGID_STATUS_VISIBLE;
    $form_name = $FORM_TABLE_CEGID_STATUS_VISIBLE."_update";
    
    applyGestionTable($SQL_TABLE_STATUS_VISIBLE, $SQL_SHOW_COL_STATUS_CEGID, $form_name);
}

/**
 * application des actions sur la page status commande
 */
function applyGestionStatusCommande() {
    global $SQL_SHOW_COL_STATUS_CEGID;
    global $SQL_TABLE_STATUS_COMMANDE;
    global $FORM_TABLE_CEGID_STATUS_COMMANDE;
    $form_name = $FORM_TABLE_CEGID_STATUS_COMMANDE."_update";
    
    applyGestionTable($SQL_TABLE_STATUS_COMMANDE, $SQL_SHOW_COL_STATUS_CEGID, $form_name);
}

/**
 * application des actions sur la page type project
 */
function applyGestionTypeProject() {
    global $SQL_SHOW_COL_STATUS_CEGID;
    global $SQL_TABLE_TYPE_PROJECT;
    global $FORM_TABLE_TYPE_PROJECT;
    $form_name = $FORM_TABLE_TYPE_PROJECT."_update";
    
    applyGestionTable($SQL_TABLE_TYPE_PROJECT, $SQL_SHOW_COL_STATUS_CEGID, $form_name);
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

 /**
  * affiche les versions des elements du STATUS_CEGID
  * (description)
  */
 function showTableStatusVisible() {
     global $SQL_SHOW_COL_STATUS_CEGID;
     global $SQL_TABLE_STATUS_VISIBLE;
     global $FORM_TABLE_CEGID_STATUS_VISIBLE;
     $form_name = $FORM_TABLE_CEGID_STATUS_VISIBLE."_insert";
 	$condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_STATUS_VISIBLE, $SQL_SHOW_COL_STATUS_CEGID, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }
 
/**
  * affiche les versions des elements du STATUS_COMMANDE
  * (description)
  */
 function showTableStatusCommande() {
     global $SQL_SHOW_COL_STATUS_CEGID;
     global $SQL_TABLE_STATUS_COMMANDE;
     global $FORM_TABLE_CEGID_STATUS_COMMANDE;
     $form_name = $FORM_TABLE_CEGID_STATUS_COMMANDE."_insert";
     $condition="";
     global $TABLE_EXPORT_CSV;
     
     //showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
     $param = prepareshowTable($SQL_TABLE_STATUS_COMMANDE, $SQL_SHOW_COL_STATUS_CEGID, $form_name, $condition);
     $param[$TABLE_EXPORT_CSV] = "yes";
     
     showTableByParam($param);
 }
 
 function showTableTypeProject() {
     global $SQL_SHOW_COL_STATUS_CEGID;
     global $SQL_TABLE_TYPE_PROJECT;
     global $FORM_TABLE_CEGID_STATUS_CEGID;
     $form_name = $FORM_TABLE_CEGID_STATUS_CEGID."_insert";
     $condition="";
     global $TABLE_EXPORT_CSV;
     
     //showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
     $param = prepareshowTable($SQL_TABLE_TYPE_PROJECT, $SQL_SHOW_COL_STATUS_CEGID, $form_name, $condition);
     $param[$TABLE_EXPORT_CSV] = "yes";
     
     showTableByParam($param);
 }
 
?>