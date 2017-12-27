<?PHP
$DEVIS_DB_PHP = "loaded";

$TABLE_CEGID_REQUETE      = "cegid_requetes";

$ID_REQUETE_SQL_CA_PREVISIONEL          = "Previsionnel CA_projet";
$ID_REQUETE_SQL_CA_PREVISIONEL_CLOS     = "Previsionnel CA_projet_clos";
$ID_REQUETE_SQL_CA_ACTUEL               = "Actuel CA_projet";
$ID_REQUETE_SQL_CA_ACTUEL_CLOS          = "Actuel CA_projet_clos";
$ID_REQUETE_SQL_CA_DIFF                 = "diff CA";
$ID_REQUETE_SQL_CA_RESPONSABLE_AFFAIRES = "Responsable affaires";
$ID_REQUETE_SQL_PRIX_VENTE              = "PRIX_VENTE";
$ID_REQUETE_SQL_CHECK_PRIX_VENTE        = "CHECK_PRIX_VENTE";
$ID_REQUETE_SQL_UO_RESTANT_CLOTURE      = "UO_RESTANT_CLOTURE";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");
include_once (dirname ( __FILE__ ) . "/tool_db.php");
include_once (dirname ( __FILE__ ) . "/historisation_db.php");
include_once (dirname ( __FILE__ ) . "/cout_project_db.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/requetes_db.php");
include_once (dirname ( __FILE__ ) . "/../js/form_db.js");   // affichage des forms et calculs


/**
 * application des actions sur la page cloture du CA
 */
function applyGestionCloture() {
    global $ID_REQUETE_SQL_PRIX_VENTE;
    $form_name="cloture";    
    $col="";
    $idRequest = getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT, $ID_REQUETE_SQL_PRIX_VENTE);    
    $request = getRequeteCAByID($idRequest);
    
    //showSQLAction("action [".getActionGet()." ] detected");
    if (getActionGet () == "sauvegarde cout" ){
        showSQLAction("action [ sauvegarde cout ] detected");
        
        $condition="";
        $res = historisationCout($condition);        
    }
    else if (getActionGet () == "cloture") {
        showSQLAction("action [ cloture ] detected");
        //historisationCout("");
        clotureYear();
        $res=1;
        //$res = editTable2 ( /*$table, $cols, $form_name,*/ $subParam );
    } else {
        $res =  applyGestionTable($request, $col, $form_name);
    }
    return $res;
}

/**
 * clotureYear
 * 
 * @return number
 */
function clotureYear(){
    global $SQL_COL_ID_PROJECT_COUT;
    global $SQL_COL_DATE_PROJECT_COUT;
    global $SQL_COL_PROJECT_ID_COUT_PROJECT;
    global $SQL_COL_PROJECT_NAME_COUT_PROJECT;
    global $SQL_COL_PROJECT_COUT_PROFIL;
    global $SQL_COL_UO_PROJECT_COUT;
    global $SQL_COL_COUT_PROJECT_COUT;
    
    $project = getURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION); 
    $year    = getURLYear();
    $year2   =$year+1;

    global $ID_REQUETE_SQL_UO_RESTANT_CLOTURE;
    $request = getRequeteCAByID($ID_REQUETE_SQL_UO_RESTANT_CLOTURE);
    
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    $nbRes = mysqlNumrows($Resultat);
    
    mysqlQuery("START TRANSACTION");
    
    for($row=0;$row<$nbRes;$row++){
        $ID          = mysqlResult($Resultat, $row, $SQL_COL_ID_PROJECT_COUT );
        $DATE        = mysqlResult($Resultat, $row, $SQL_COL_DATE_PROJECT_COUT );
        $PROJECT_ID  = mysqlResult($Resultat, $row, $SQL_COL_PROJECT_ID_COUT_PROJECT);
        $UO_RESTANT  = mysqlResult($Resultat, $row, "UO_restant");
        $UO_CONSOMME = mysqlResult($Resultat, $row, "UO_consomme");
        $COUT        = mysqlResult($Resultat, $row, $SQL_COL_COUT_PROJECT_COUT);
        $PROFIL_ID   = mysqlResult($Resultat, $row, $SQL_COL_PROJECT_COUT_PROFIL);
        
      
        $requestInsert = "INSERT INTO `cegid_project_cout`";
        $requestInsert = "$requestInsert        (`DATE`, `PROJECT_ID`,   `PROFIL_ID`,  `UO`,   `COUT`)";
        $requestInsert = "$requestInsert VALUES ('$year2-01-01', '$PROJECT_ID', '$PROFIL_ID', '$UO_RESTANT', '$COUT')";    
        //showSQLAction($requestInsert);
        if (mysqlQuery($requestInsert)){     
            $requestUpdate = "UPDATE `cegid_project_cout` ";
            $requestUpdate = "$requestUpdate SET `UO` = '$UO_CONSOMME' ";
            $requestUpdate = "$requestUpdate WHERE `cegid_project_cout`.`ID` = $ID";
            //showSQLAction($requestUpdate);
            mysqlQuery($requestUpdate);
        }
    }
    //end for

    mysqlQuery("ROLLBACK");
    
    
  $res = 1;
  return $res;
}

/**
 * clotureYear
 *
 * @return number
 */
function UOReportable(){
    global $ID_REQUETE_SQL_UO_RESTANT_CLOTURE;
    $request = getRequeteCAByID($ID_REQUETE_SQL_UO_RESTANT_CLOTURE);
    createHeaderBaliseDiv("UO_restant","<h3>UO reportable</h3>");
    
    actionRequeteSql($request, /*$html*/"", /*$subParam*/"", /*$closeTable*/"yes");
    endHeaderBaliseDiv("UO_restant");
    $res = 1;
    return $res;
}


/**
 * historisationCout
 * 
 * @param string $condition
 * @return request
 */
function historisationCout($condition=""){
    global $SQL_SHOW_COL_PROJECT_COUT;
    global $SQL_TABLE_PROJECT_COUT;
    showSQLAction("Historisation table Cout...");
    $res = historisationTable($SQL_TABLE_PROJECT_COUT, "", $SQL_SHOW_COL_PROJECT_COUT, $condition);
    return $res;
}

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
function showTableCAPrevisionel($idRequest="", $formname="", $idTable = "") {
    global $TABLE_EXPORT_CSV;
    $html="";
    
    //recuperation de la requete
    $request = getRequeteCAByID($idRequest);
    if ($request == ""){
        showError("request id not found : $idRequest");
    }
    
    if ($formname == ""){
        $formname = "$idRequest";
    }
    
    if ($idTable == ""){
        $idTable = "$formname";
    }
    
	//ajout table id & export CSV
    $subParam[PARAM_TABLE_TABLE::TABLE_ID] = "$idTable";
	$subParam[$TABLE_EXPORT_CSV] = "yes";
	$subParam[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT] = $formname;
	
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