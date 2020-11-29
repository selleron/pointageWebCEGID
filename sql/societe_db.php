<?PHP
$SOCIETE_DB_PHP = "loaded";

$SQL_TABLE_SOCIETE_FOURNISSEUR         = "cegid_societe_fournisseur";
$SQL_TABLE_SOCIETE_CLIENT              = "cegid_societe_client";
$FORM_TABLE_SOCIETE_FOURNISSEUR        = "form_cegid_societe_fournisseur";
$FORM_TABLE_SOCIETE_CLIENT             = "form_cegid_societe_client";

$SQL_COL_ID_SOCIETE       = "ID";
$SQL_COL_NAME_SOCIETE     = "NAME";
$SQL_COL_ORDRE_SOCIETE    = "ORDRE";
$SQL_COL_ADRESSE_SOCIETE  = "ADRESSE";


$SQL_SHOW_INSERT_COL_SOCIETE   = "$SQL_COL_ID_SOCIETE, $SQL_COL_NAME_SOCIETE, $SQL_COL_ORDRE_SOCIETE";
$SQL_SHOW_COL_SOCIETE          = "$SQL_COL_ID_SOCIETE, $SQL_COL_NAME_SOCIETE, $SQL_COL_ADRESSE_SOCIETE, $SQL_COL_ORDRE_SOCIETE";

include_once 'table_db.php';



function applyGestionSocieteFournisseur() {
    global $SQL_SHOW_COL_SOCIETE;
    global $SQL_TABLE_SOCIETE_FOURNISSEUR;
    global $FORM_TABLE_SOCIETE_FOURNISSEUR;
    $form_name = $FORM_TABLE_SOCIETE_FOURNISSEUR."_update";
    
    applyGestionTable($SQL_TABLE_SOCIETE_FOURNISSEUR, $SQL_SHOW_COL_SOCIETE, $form_name);
}

function applyGestionSocieteClient() {
    global $SQL_SHOW_COL_SOCIETE;
    global $SQL_TABLE_SOCIETE_CLIENT;
    global $FORM_TABLE_SOCIETE_CLIENT;
    $form_name = $FORM_TABLE_SOCIETE_CLIENT."_update";
    
    applyGestionTable($SQL_TABLE_SOCIETE_CLIENT, $SQL_SHOW_COL_SOCIETE, $form_name);
}


 function showTableSocieteFournisseur() {
     global $SQL_SHOW_COL_SOCIETE;
     global $SQL_TABLE_SOCIETE_FOURNISSEUR;
     global $FORM_TABLE_SOCIETE_FOURNISSEUR;
     $form_name = $FORM_TABLE_SOCIETE_FOURNISSEUR."_insert";
     $condition="";
 	global $TABLE_EXPORT_CSV;
 	
 	//showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
 	$param = prepareshowTable($SQL_TABLE_SOCIETE_FOURNISSEUR, $SQL_SHOW_COL_SOCIETE, $form_name, $condition);
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	showTableByParam($param);
 }

 
 function showTableSocieteClient() {
     global $SQL_SHOW_COL_SOCIETE;
     global $SQL_TABLE_SOCIETE_CLIENT;
     global $FORM_TABLE_SOCIETE_CLIENT;
     $form_name = $FORM_TABLE_SOCIETE_CLIENT."_insert";
     $condition="";
     global $TABLE_EXPORT_CSV;
     
     //showTable($SQL_TABLE_PROFILS, $SQL_SHOW_COL_PROFIL, $form_name);
     $param = prepareshowTable($SQL_TABLE_SOCIETE_CLIENT, $SQL_SHOW_COL_SOCIETE, $form_name, $condition);
     $param[$TABLE_EXPORT_CSV] = "yes";
     
     showTableByParam($param);
 }
 
 
?>