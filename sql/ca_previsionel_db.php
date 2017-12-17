<?PHP
$DEVIS_DB_PHP = "loaded";

$TABLE_CEGID_REQUETE      = "cegid_requetes";

$ID_REQUETE_SQL_CA_PREVISIONEL          = "Previsionnel CA_projet";
$ID_REQUETE_SQL_CA_PREVISIONEL_CLOS     = "Previsionnel CA_projet_clos";
$ID_REQUETE_SQL_CA_ACTUEL               = "Actuel CA_projet";
$ID_REQUETE_SQL_CA_ACTUEL_CLOS          = "Actuel CA_projet_clos";
$ID_REQUETE_SQL_CA_DIFF                 = "diff CA";
$ID_REQUETE_SQL_CA_RESPONSABLE_AFFAIRES = "Responsable affaires";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");
include_once (dirname ( __FILE__ ) . "/tool_db.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/requetes_db.php");
include_once (dirname ( __FILE__ ) . "/../js/form_db.js");   // affichage des forms et calculs


/**
 * application des actions sur la page status project
 */
function applyGestionCAPrevisionel($idRequest="") {
    $request = getRequeteCAByID($idRequest);
    $col="";
    $form_name="form_ca_previsionel";    
    
    applyGestionTable($request, $col, $form_name);
}

/**
 * 
 * @param string $idRequest
 * @return string request sql from $TABLE_CEGID_REQUETE
 */
function getRequeteCAByID($idRequest){
    global $TABLE_CEGID_REQUETE;
    global $ID_REQUETE_SQL_CA_PREVISIONEL;
    
    if ($idRequest==""){
        $idRequest=$ID_REQUETE_SQL_CA_PREVISIONEL;
    }
    
    //positionnement $year
    $year = getURLYear();
    setURLYear($year);
    
    $request = getRequeteByID($idRequest, $TABLE_CEGID_REQUETE);
    return $request;
}


/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableCAPrevisionel($idRequest="") {
    global $TABLE_EXPORT_CSV;
    $html="";
    
    //recuperation de la requete
    $request = getRequeteCAByID($idRequest);
    if ($request == ""){
        showError("request id not found : $idRequest");
    }
    
	//ajout table id & export CSV
    $subParam[PARAM_TABLE_TABLE::TABLE_ID]="table_ca_prev";
	$subParam[$TABLE_EXPORT_CSV] = "yes";
	
	
	$closeTable="false";
	$param2 = actionRequeteSql($request,$html, $subParam, $closeTable);
	
	
	showTableHeader($param2, "", "no");
	$colsAll= arrayToString($param2[PARAM_TABLE_SQL::COLUMNS_SUMMARY]);
	$colsFromSummation= $colsAll;
	
	// show sum row
	$colSumSize="10";
	showTablelineSummation($param2, $colsAll, $colsFromSummation, $colSumSize);
	
	// close table
 	endTableRow();
 	endForm();
 	endTable();
	
 	// lance les calcules de sommation
	$table_id = $param2[PARAM_TABLE_TABLE::TABLE_ID];
 	showSommation($table_id, $colsFromSummation, "", "");
	
	
}



?>