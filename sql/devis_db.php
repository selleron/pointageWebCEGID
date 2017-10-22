<?PHP
$DEVIS_DB_PHP = "loaded";

$SQL_TABLE_DEVIS         = "cegid_devis_project";
$FORM_TABLE_CEGID_DEVIS  = "form_table_cegid_devis";

$SQL_COL_ID_DEVIS             = "ID";
$SQL_COL_NAME_DEVIS           = "NAME";
$SQL_COL_VERSION_DEVIS        = "VERSION";
$SQL_COL_STATUS_DEVIS         = "STATUS_DEVIS";
$SQL_COL_STATUS_CEGID_DEVIS   = "STATUS_CEGID";
$SQL_COL_STATUS_COMMANDE_DEVIS= "STATUS_COMMANDE";
$SQL_COL_COMMANDE_DEVIS       = "COMMANDE";
$SQL_COL_MODIFICATION_DEVIS   = "MODIFICATION";
$SQL_COL_CEGID_DEVIS          = "CEGID";
$SQL_COL_NUXEO_DEVIS          = "NUXEO";
$SQL_COL_COMMENTAIRE_DEVIS    = "COMMENTAIRE";

$SQL_LABEL_DEVIS_NAME = "DEVIS";


$SQL_SHOW_COL_DEVIS     = "$SQL_COL_ID_DEVIS, $SQL_COL_NAME_DEVIS, $SQL_COL_VERSION_DEVIS, $SQL_COL_STATUS_DEVIS, $SQL_COL_COMMANDE_DEVIS, $SQL_COL_MODIFICATION_DEVIS, $SQL_COL_STATUS_COMMANDE_DEVIS, $SQL_COL_CEGID_DEVIS, $SQL_COL_STATUS_CEGID_DEVIS";
$SQL_SHOW_ALL_COL_DEVIS = $SQL_SHOW_COL_DEVIS.", $SQL_COL_NUXEO_DEVIS, $SQL_COL_COMMENTAIRE_DEVIS";

//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");
include_once (dirname ( __FILE__ ) . "/tool_db.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");


function convertDevisToProjectIfNeeded(){
    global $PROJECT_SELECTION;
    global $SQL_COL_NAME_PROJECT;
    
     //verification i on vint pas de devis
     global $FORM_TABLE_CEGID_DEVIS;
    
    $form = getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT);
    $pos = strpos("!".$form,$FORM_TABLE_CEGID_DEVIS)>=1;
    //showSQLAction("form devis : $FORM_TABLE_CEGID_DEVIS - $form - $pos");
    if ($pos>=1){
        //showSQLAction("id devis found, convertion needed");
        
        global $SQL_TABLE_DEVIS;
        global $SQL_COL_CEGID_DEVIS;
        global $SQL_COL_ID_DEVIS;
        
        $idDevis = getURLVariable($SQL_COL_ID_DEVIS);
        
        //search project id
        $condition = createSqlWhere($SQL_COL_ID_DEVIS, $idDevis);
        $param = createDefaultParamSql($SQL_TABLE_DEVIS, $SQL_COL_CEGID_DEVIS, $condition );
        $requete = $request = createRequeteTableData($param);
        showSQLAction($requete);
        $Resultat = requeteTableData($param);
        $idProject = mysqlResult($Resultat, 0, $SQL_COL_CEGID_DEVIS);
        //showSQLAction("id project found $idProject from devis $idDevis");
        if ($idProject){
            $projectName = getProjectNameFromID($idProject);
            //showSQLAction("project name found $projectName for $idProject => $PROJECT_SELECTION || $SQL_COL_NAME_PROJECT");
            if ($projectName){
                setURLVariable($SQL_COL_NAME_PROJECT, $projectName);
                setURLVariable($PROJECT_SELECTION, $projectName);
                //showGet();
            }
        }
        
    }
}



/**
 * application des actions sur la page projet
 * 
 * @return number 0 nothing 1 action traitée
 */
function applyGestionDevis() {
    global $SQL_SHOW_COL_DEVIS;
    global $SQL_SHOW_ALL_COL_DEVIS;
    $colDEVIS = $SQL_SHOW_ALL_COL_DEVIS;
    global $SQL_TABLE_DEVIS;
	global $FORM_TABLE_CEGID_DEVIS;
	$form_name = $FORM_TABLE_CEGID_DEVIS."_update";

	$condition="";
	$colFilter=NULL;
	$param = createDefaultParamSql ( $SQL_TABLE_DEVIS, $colDEVIS, $condition );
	$param = updateTableParamSql ( $param, $form_name, $colFilter );
	
	$res = -1;
	
	$formURL ="!".getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT);
	$pos = strpos( $formURL, $FORM_TABLE_CEGID_DEVIS);
	//showSQLAction("test $FORM_TABLE_CEGID_DEVIS for $formURL :[$pos]");
	if  ($pos>=1){
	    //nothing to do
	    global $SHOW_FORM_TRACE;
	    showActionVariable("action for cegid_devis accepted", $SHOW_FORM_TRACE);
	}
	else{
	    //on ne fait pas de traitement
	    $res=1;
	}
	
	
	if ($res<=0){
	  //traitement du update
 	  $res = updateTableByGet (/*$SQL_TABLE_DEVIS, $colDEVIS, $form_name,*/ $param, "no"/** re-edit */ ); 
	}
	
	//cas classique : edit, export, ...
	if (getActionGet () == "edit") {
	    //nothing to do
	    //sera traité par editGestionDevis()
	}
	else{
	if ($res<=0){
	    $res =  applyGestionTable($SQL_TABLE_DEVIS, $colDEVIS, $form_name);
	}
	}
	return $res;
}


function editGestionDevis()
{
    $idBalise = "gestion_info_devis";
    createHeaderBaliseDiv($idBalise, "<h3>Infomation devis </h3>");
    {
        
        global $SQL_SHOW_COL_DEVIS;
        global $SQL_SHOW_ALL_COL_DEVIS;
        $colDEVIS = $SQL_SHOW_ALL_COL_DEVIS;
        global $SQL_TABLE_DEVIS;
        global $FORM_TABLE_CEGID_DEVIS;
        $form_name = $FORM_TABLE_CEGID_DEVIS . "_update";
        
        $condition = "";
        $colFilter = NULL;
        $param = createDefaultParamSql($SQL_TABLE_DEVIS, $colDEVIS, $condition);
        $param = updateTableParamSql($param, $form_name, $colFilter);
        
        // preparation edition d'un devis
        $idTableDevis="";
        global $ID_TABLE_GET;
        $idTable = getURLVariable($ID_TABLE_GET);
        if ($idTable == "") {
            $param = setSQLFlagStatus($param, stringToArray($colDEVIS), "disabled");
        }
        
         global $FORM_TABLE_CEGID_CEGID_FILE;
         $formURL ="!".getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT);
         $pos = strpos( $formURL, $FORM_TABLE_CEGID_CEGID_FILE);
         ///showSQLAction("position $FORM_TABLE_CEGID_CEGID_FILE || $formURL => [$pos]");
         if ($pos>=1 ){
             //showSQLAction("reference id found. Search devis id");
             global $SQL_TABLE_CEGID_FILE;
             global $SQL_COL_ID_CEGID_FILE;
             global $SQL_COL_REFERENCE_CEGID_FILE;
            
             $conditionSelect = createSqlWhere($SQL_COL_ID_CEGID_FILE, $idTable);
             $paramSelect = createDefaultParamSql($SQL_TABLE_CEGID_FILE, $SQL_COL_REFERENCE_CEGID_FILE,$conditionSelect);
             //$requestSelect = createRequeteTableData($paramSelect);
             //showSQLAction($requestSelect);
             $Resultat = requeteTableData ( $paramSelect );
             $nbRes = mysqlNumrows ( $Resultat );
             if ($nbRes > 0) {
                $idTableDevis = mysqlResult ( $Resultat, 0, $SQL_COL_REFERENCE_CEGID_FILE );
             }
             else{
                $param = setSQLFlagStatus($param, stringToArray($colDEVIS), "disabled");
             }
         }
         else{
             $idTableDevis = $idTable;
         }
        
         
         if ($idTableDevis!=""){
             setURLVariable($ID_TABLE_GET, $idTableDevis);
         }
         
        $res = editTable2 ( /*$table, $cols, $form_name,*/ $param);

        if ($idTableDevis!=""){
            setURLVariable($ID_TABLE_GET, $idTable);   
        }
        
    }
    endHeaderBaliseDiv($idBalise);
    return $res;
}

/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableDEVIS() {
	global $SQL_SHOW_COL_DEVIS;
	global $SQL_TABLE_DEVIS;
	global $FORM_TABLE_CEGID_DEVIS;
	global $TABLE_EXPORT_CSV;
	$form_name = $FORM_TABLE_CEGID_DEVIS."_insert";
	$condition="";
	
	//showSQLAction("showTableDEVIS - ...");
	
	//showTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name);
	$param = prepareshowTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name, $condition);
	//par defaut on a edit & delete
	
	//ajout export CSV
	$param[$TABLE_EXPORT_CSV] = "yes";
	
	//ajout edit pointage
	//ajout edit pointage et previsionnel
	global $URL_ROOT_POINTAGE;
	$urlPointage = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
	$urlPrevision = "$URL_ROOT_POINTAGE/user/pointage_prevision_cegid.php";
	//showSQLAction("showTableProject - addParamActionCommand()");
	$param = addParamActionCommand($param, $urlPointage   , "pointage!"   , LabelAction::ACTION_POINTAGE );
	$param = addParamActionCommand($param, $urlPrevision  , "prevision!"  , LabelAction::ACTION_POINTAGE );
	
	
	//showSQLAction("showTableProject - showTableByParam()");
	showTableByParam($param);
	
}



?>