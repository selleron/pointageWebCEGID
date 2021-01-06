<?PHP
$DEVIS_DB_PHP = "loaded";


$ID_REQUETE_SQL_CA_PREVISIONEL          = "Previsionnel CA_projet";
$ID_REQUETE_SQL_CA_PREVISIONEL_CLOS     = "Previsionnel CA_projet_clos";
$ID_REQUETE_SQL_CA_ACTUEL               = "Actuel CA_projet";
$ID_REQUETE_SQL_CA_ACTUEL_CLOS          = "Actuel CA_projet_clos";
$ID_REQUETE_SQL_CA_DIFF                 = "diff CA";
$ID_REQUETE_SQL_CA_RESPONSABLE_AFFAIRES = "Responsable affaires";
$ID_REQUETE_SQL_PRIX_VENTE              = "PRIX_VENTE";
$ID_REQUETE_SQL_CHECK_PRIX_VENTE        = "CHECK_PRIX_VENTE";
$ID_REQUETE_SQL_UO_RESTANT              = "UO_RESTANT";
$ID_REQUETE_SQL_UO_RESTANT_CLOTURE      = "UO_RESTANT_CLOTURE";
$ID_REQUETE_SQL_ALL_CEGID_POINTAGE      = "ALL_CEGID_POINTAGE";
$ID_REQUETE_SQL_PROJECTS                = "REQUETE_PROJECTS";
$ID_REQUETE_SQL_ARCHIVE_PROJECTS        = "ARCHIVE_PROJECTS";
$ID_REQUETE_SQL_UNARCHIVE_PROJECTS      = "UNARCHIVE_PROJECTS";
$ID_REQUETE_SQL_DEVIS                   = "REQUETE_DEVIS";
$ID_REQUETE_SQL_ARCHIVE_DEVIS           = "ARCHIVE_DEVIS";
$ID_REQUETE_SQL_UNARCHIVE_DEVIS         = "UNARCHIVE_DEVIS";
$ID_REQUETE_SQL_USERS                   = "REQUETE_USERS";
$ID_REQUETE_SQL_ARCHIVE_USERS           = "ARCHIVE_USERS";
$ID_REQUETE_SQL_UNARCHIVE_USERS         = "UNARCHIVE_USERS";
$ID_REQUETE_SQL_HISTORIQUE_COUT         = "HISTORIQUE_COUT";
$ID_REQUETE_SQL_HISTORIQUE_PREVISIONNEL = "HISTORIQUE_PREVISIONNEL";
$ID_REQUETE_SQL_ARCHIVE_CMD_PRESTATAIRES   = "ARCHIVE_CMD_PRESTATAIRES";
$ID_REQUETE_SQL_UNARCHIVE_CMD_PRESTATAIRES ="UNARCHIVE_CMD_PRESTATAIRES";

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
    global $TRACE_CLOTURE;
    global $ID_REQUETE_SQL_PRIX_VENTE;
    global $ID_REQUETE_SQL_HISTORIQUE_COUT;
    global $ID_REQUETE_SQL_HISTORIQUE_PREVISIONNEL;
    
    $form_name="cloture";    
    $col="";
    $idRequest = getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT, $ID_REQUETE_SQL_PRIX_VENTE);    
    $request = getRequeteCAByID($idRequest);
    
    //showSQLAction("action [".getActionGet()." ] detected");
    if (getActionGet () == "restore cout" ){
        showActionVariable("action [ restore cout ] detected", $TRACE_CLOTURE);
        
        $condition="";
        $res = restoreCout($condition);
    }
    else if (getActionGet () == "sauvegarde cout" ){
        showActionVariable("action [ sauvegarde cout ] detected", $TRACE_CLOTURE );
        
        $condition="";
        $res = historisationCout($condition);
    }
    else if (getActionGet () == "cloture") {
        showActionVariable("action [ cloture ] detected", $TRACE_CLOTURE);
        //historisationCout("");
        $res = clotureYear();
        //$res = editTable2 ( /*$table, $cols, $form_name,*/ $subParam );
    } 
    else if (getActionGet () == "historique cout"){
        showActionVariable("action [ historique cout ] detected", $TRACE_CLOTURE);
        showDescriptionRequeteCEGID($ID_REQUETE_SQL_HISTORIQUE_COUT);
        showTableRequeteCEGID( $ID_REQUETE_SQL_HISTORIQUE_COUT , "" /* forname */ , "" /* id table */ ,  "no" /*limit*/ );
        $res=1;
    }
    else if (getActionGet () == "historique previsionnel"){
        showActionVariable("action [ historique pointage previsionnel ] detected", $TRACE_CLOTURE);
        showDescriptionRequeteCEGID($ID_REQUETE_SQL_HISTORIQUE_PREVISIONNEL);
        showTableRequeteCEGID( $ID_REQUETE_SQL_HISTORIQUE_PREVISIONNEL );
        $res=1;
    }
    else {
        if ($request==""){
            showActionVariable("action [ applyGestionCloture ] detected : request not found for id : $idRequest. use id [$ID_REQUETE_SQL_PRIX_VENTE]", $TRACE_CLOTURE);
            $request = getRequeteCAByID($ID_REQUETE_SQL_PRIX_VENTE);
        }
        $res =  applyGestionTable($request, $col, $form_name);
        //$res = -1;
    }
    return $res;
}

/**
 * applyGestionArchives
 * Action gestion Archivage
 * 
 *  archivage users, projets, devis, commandes prestataires
 * 
 * @return number  0 pas d'action 1 si action faite
 */
function applyGestionArchives() {
    global $TRACE_CLOTURE;
    global $ID_REQUETE_SQL_PROJECTS;
    global $ID_REQUETE_SQL_ARCHIVE_PROJECTS;
    global $ID_REQUETE_SQL_UNARCHIVE_PROJECTS;
    global $ID_REQUETE_SQL_ARCHIVE_DEVIS;
    global $ID_REQUETE_SQL_UNARCHIVE_DEVIS;
    global $ID_REQUETE_SQL_ARCHIVE_USERS;
    global $ID_REQUETE_SQL_UNARCHIVE_USERS;
    global $ID_REQUETE_SQL_ARCHIVE_CMD_PRESTATAIRES;
    global $ID_REQUETE_SQL_UNARCHIVE_CMD_PRESTATAIRES;
    
    $res=0;
    $form_name="archives";
    $col="";
    $idRequest = getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT, $ID_REQUETE_SQL_PROJECTS);
    $request = getRequeteCAByID($idRequest);
    
    
    //showSQLAction("action [".getActionGet()." ] detected");
    if (getActionGet () == "Archive Projects" ){
        showActionVariable("action [ Archive Projects ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_ARCHIVE_PROJECTS);
    }
    else if (getActionGet () == "Unarchive Projects" ){
        showActionVariable("action [ Unarchive Projects ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_UNARCHIVE_PROJECTS);
    }
    else if (getActionGet () == "Archive Devis" ){
        showActionVariable("action [ Archive Devis ] detected", $TRACE_CLOTURE );
        $res = executeRequeteCEGID($ID_REQUETE_SQL_ARCHIVE_DEVIS);
    }
    else if (getActionGet () == "Unarchive Devis" ){
        showActionVariable("action [ Unarchive Devis ] detected", $TRACE_CLOTURE );
        $res = executeRequeteCEGID($ID_REQUETE_SQL_UNARCHIVE_DEVIS);
    }
    else if (getActionGet () == "Archive Users") {
        showActionVariable("action [ Archive Users ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_ARCHIVE_USERS);
    }
    else if (getActionGet () == "Unarchive Users") {
        showActionVariable("action [ Unarchive Users ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_UNARCHIVE_USERS);
    }
    else if (getActionGet () == "Archive Cmd Prestataires") {
        showActionVariable("action [ Archive Cmd Prestataires ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_ARCHIVE_CMD_PRESTATAIRES);
    }
    else if (getActionGet () == "Unarchive Cmd Prestataires") {
        showActionVariable("action [ Unarchive Cmd Prestataires ] detected", $TRACE_CLOTURE);
        $res = executeRequeteCEGID($ID_REQUETE_SQL_UNARCHIVE_CMD_PRESTATAIRES);
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
    global $TRACE_CLOTURE;
    
    global $SQL_COL_ID_PROJECT_COUT;
    global $SQL_COL_DATE_PROJECT_COUT;
    global $SQL_COL_PROJECT_ID_COUT_PROJECT;
    global $SQL_COL_PROJECT_NAME_COUT_PROJECT;
    global $SQL_COL_PROJECT_COUT_PROFIL;
    global $SQL_COL_UO_PROJECT_COUT;
    global $SQL_COL_COUT_PROJECT_COUT;
    global $SQL_COL_COMMENTAIRE_PROJECT_COUT;
    
    $project = getURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION); 
    $year    = getURLYear();
    $year2   =$year+1;

    //recherche requete
    global $ID_REQUETE_SQL_UO_RESTANT_CLOTURE;
    $request = getRequeteCAByID($ID_REQUETE_SQL_UO_RESTANT_CLOTURE);
    
    //prise en compte project
    if ( ($project != FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION) &&
        ($project != FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_ALL) &&
        ($project != "") ){
        $request = "select * from ($request) cloture2 where NAME='$project'";
    }
    //execution recherche row cout pour cloture
    showActionVariable("$request", $TRACE_CLOTURE);
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    $nbRes = mysqlNumrows($Resultat);
    showActionVariable("report cloture a faire : $nbRes", $TRACE_CLOTURE);
    $res = 0;
    
    //demarrage transaction
    mysqlBeginTransaction();
    
    //transfert des UO vers year + 1
    for($row=0;$row<$nbRes;$row++){
        $ID          = mysqlResult($Resultat, $row, $SQL_COL_ID_PROJECT_COUT );
        $DATE        = mysqlResult($Resultat, $row, $SQL_COL_DATE_PROJECT_COUT );
        $PROJECT_ID  = mysqlResult($Resultat, $row, $SQL_COL_PROJECT_ID_COUT_PROJECT);
        $UO_RESTANT  = mysqlResult($Resultat, $row, "UO_restant");
        $UO_CONSOMME = mysqlResult($Resultat, $row, "UO_consomme");
        $COUT        = mysqlResult($Resultat, $row, $SQL_COL_COUT_PROJECT_COUT);
        $PROFIL_ID   = mysqlResult($Resultat, $row, $SQL_COL_PROJECT_COUT_PROFIL);
        $PROFIL_ID   = mysqlResult($Resultat, $row, $SQL_COL_PROJECT_COUT_PROFIL);
        $COMMENTAIRE = mysqlResult($Resultat, $row, $SQL_COL_COMMENTAIRE_PROJECT_COUT);
         
        
      
        $requestInsert = "INSERT INTO `cegid_project_cout`";
        $requestInsert = "$requestInsert        (`DATE`, `PROJECT_ID`,   `PROFIL_ID`,  `UO`,   `COUT`, `$SQL_COL_COMMENTAIRE_PROJECT_COUT`)";
        $requestInsert = "$requestInsert VALUES ('$year2-01-01', '$PROJECT_ID', '$PROFIL_ID', '$UO_RESTANT', '$COUT', \"$COMMENTAIRE\")";    
        showActionVariable($requestInsert, $TRACE_CLOTURE);
        if (mysqlQuery($requestInsert)){     
            $requestUpdate = "UPDATE `cegid_project_cout` ";
            $requestUpdate = "$requestUpdate SET `UO` = '$UO_CONSOMME' ";
            $requestUpdate = "$requestUpdate WHERE `cegid_project_cout`.`ID` = $ID";
            showActionVariable($requestUpdate, $TRACE_CLOTURE);
            $res2 = mysqlQuery($requestUpdate);
            $res = $res || $res2;
        }
    }
    //end for
    if ($res){
      mysqlCommit();  
    }
    else{
        mysqlRollback();
    }
    
    
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
    createHeaderBaliseDiv("UO_restant_cloture","<h3>UO reportable</h3>");
    
    showDescriptionRequeteCEGID($ID_REQUETE_SQL_UO_RESTANT_CLOTURE);
    
    $subParam[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT] = $ID_REQUETE_SQL_UO_RESTANT_CLOTURE;
    
    actionRequeteSql($request, /*$html*/"", $subParam, /*$closeTable*/"yes");
    endHeaderBaliseDiv("UO_restant_cloture");
    $res = 1;
    return $res;
}


/**
 * historisationCout
 * 
 * @param string $condition
 * @return boolean| object sql result
 */
function historisationCout($condition=""){
    global $TRACE_CLOTURE;
    
    global $SQL_SHOW_COL_PROJECT_COUT;
    global $SQL_TABLE_PROJECT_COUT;
    showActionVariable("Historisation table Cout...", $TRACE_CLOTURE);
    $res = historisationTable($SQL_TABLE_PROJECT_COUT, "", $SQL_SHOW_COL_PROJECT_COUT, $condition);
    return $res;
}

/**
 * restoreCout
 * restoration à la derniere date ou suivant la condition
 * @param string $condition
 * @return object sql result or boolean
 */
function restoreCout($condition=""){
    global $TRACE_CLOTURE;
    
    global $SQL_SHOW_COL_PROJECT_COUT;
    global $SQL_TABLE_PROJECT_COUT;
    showActionVariable("Historisation table Cout...", $TRACE_CLOTURE);
    $res = restoreTable($SQL_TABLE_PROJECT_COUT, "", $SQL_SHOW_COL_PROJECT_COUT, $condition);
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
 * affiche les versions des elements du projet
 * (description)
 */
function showTableCAPrevisionel($idRequest="", $formname="", $idTable = "") {
    return showTableRequeteCEGID($idRequest, $formname, $idTable);
    
}



// /**
//  * affiche une requete stockee dans la table des requetes CEGID
//  * @param string $idRequest
//  * @param string $formname
//  * @param string $idTable
//  */
// function showDescriptionRequeteCEGID($idRequest="", $formname="", $idTable = "") {
//     global $TABLE_CEGID_REQUETE;
//    
//     $html="";
//    
//     if ($idTable == ""){
//         $idTable = $TABLE_CEGID_REQUETE;
//     }
//    
//     //recuperation de la requete
//     $description = getDescriptionRequeteByID($idRequest, $idTable);
//     echo "<p>$description</p>";
//    
// }


// /**
//  *
//  * @param string $idRequest
//  * @return string request sql from $TABLE_CEGID_REQUETE
//  */
// function getRequeteCAByID($idRequest){
//     global $TABLE_CEGID_REQUETE;
//     global $ID_REQUETE_SQL_CA_PREVISIONEL;
//     if ($idRequest==""){
//         $idRequest=$ID_REQUETE_SQL_CA_PREVISIONEL;
//     }
//     //positionnement $year
//     $year = getURLYear();
//     setURLYear($year); 
//     $request = getRequeteByID($idRequest, $TABLE_CEGID_REQUETE);
//     return $request;
// }



/**
 * affiche une requete stockee dans la table des requetes CEGID
 * @param string $idRequest
 * @param string $formname
 * @param string $idTable
 * @param string $useLimite "yes|no" default yes
 */
function showTableRequeteCEGID($idRequest="", $formname="", $idTable = "",  $useLimit="yes") {
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
	$param2 = actionRequeteSql($request,$html, $subParam, $closeTable, $useLimit);
	
	
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

/**
 * Execute une requete stockée dans la table des requetes CEGID
 * @param string $idRequest
 * @param string $formname
 * @param string $idTable
 */
function executeRequeteCEGID($idRequest="", $formname="", $idTable = "") {
    global $TABLE_EXPORT_CSV;
    global $TRACE_REQUEST_CEGID;
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
     
    
    showActionVariable("Cegid request [ $idRequest ]: $request", $TRACE_REQUEST_CEGID);
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    
    return  $Resultat;
}


?>