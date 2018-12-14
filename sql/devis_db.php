<?PHP
$DEVIS_DB_PHP = "loaded";

$SQL_TABLE_DEVIS         = "cegid_devis_project";
$FORM_TABLE_CEGID_DEVIS  = "form_table_cegid_devis";


$SQL_COL_ID_DEVIS             = "ID";
$SQL_COL_NAME_DEVIS           = "NAME";
$SQL_COL_VERSION_DEVIS        = "VERSION";
$SQL_COL_CLIENT_DEVIS         = "CLIENT";
$SQL_COL_SOCIETE_DEVIS        = "SOCIETE";
$SQL_COL_STATUS_DEVIS         = "STATUS_DEVIS";
$SQL_COL_STATUS_CEGID_DEVIS   = "STATUS_CEGID";
$SQL_COL_STATUS_COMMANDE_DEVIS= "STATUS_COMMANDE";
$SQL_COL_COMMANDE_DEVIS       = "COMMANDE";
$SQL_COL_MODIFICATION_DEVIS   = "MODIFICATION";
$SQL_COL_CEGID_DEVIS          = "CEGID";
$SQL_COL_NUXEO_DEVIS          = "NUXEO";
$SQL_COL_COMMENTAIRE_DEVIS    = "COMMENTAIRE";
$SQL_COL_VISIBLE_DEVIS        = "VISIBLE";

$SQL_LABEL_DEVIS_NAME = "DEVIS";


$SQL_SHOW_COL_DEVIS     = "$SQL_COL_ID_DEVIS, $SQL_COL_NAME_DEVIS, $SQL_COL_VERSION_DEVIS, $SQL_COL_STATUS_DEVIS, $SQL_COL_COMMANDE_DEVIS, $SQL_COL_MODIFICATION_DEVIS, $SQL_COL_STATUS_COMMANDE_DEVIS, $SQL_COL_CEGID_DEVIS, $SQL_COL_STATUS_CEGID_DEVIS";
$SQL_SHOW_ALL_COL_DEVIS = "$SQL_COL_ID_DEVIS, $SQL_COL_NAME_DEVIS, $SQL_COL_VERSION_DEVIS, $SQL_COL_CLIENT_DEVIS, $SQL_COL_SOCIETE_DEVIS, $SQL_COL_STATUS_DEVIS, $SQL_COL_COMMANDE_DEVIS, $SQL_COL_MODIFICATION_DEVIS, $SQL_COL_STATUS_COMMANDE_DEVIS, $SQL_COL_CEGID_DEVIS, $SQL_COL_STATUS_CEGID_DEVIS, $SQL_COL_NUXEO_DEVIS, $SQL_COL_COMMENTAIRE_DEVIS, $SQL_COL_VISIBLE_DEVIS";


$SQL_TABLE_STATUS_EVOLUTION  = "cegid_status_evolution";

$SQL_COL_REFERENCE_STATUS_EVOLUTION = "REFERENCE";
$SQL_COL_DATE_STATUS_EVOLUTION = "DATE";
$SQL_COL_STATUS_STATUS_EVOLUTION = "STATUS";
$SQL_COL_ORIGIN_STATUS_EVOLUTION = "ORIGIN";

$SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION = "'cegid_devis_project.status_devis'";



//include_once 'connection_db.php';
//include_once 'tool_db.php';
include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
include_once (dirname ( __FILE__ ) . "/table_db.php");
include_once (dirname ( __FILE__ ) . "/tool_db.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");

/**
 * convertDevisToProjectIfNeeded
 * transforme un devis en projet si necessaire
 */
function convertDevisToProjectIfNeeded(){
    //global $PROJECT_SELECTION;
    global $SQL_COL_NAME_PROJECT;
    
     //verification si on vient pas de devis
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
            //showSQLAction("project name found $projectName for $idProject => FORM_COMBOX_BOX_KEY::PROJECT_SELECTION || $SQL_COL_NAME_PROJECT");
            if ($projectName){
                setURLVariable($SQL_COL_NAME_PROJECT, $projectName);
                setURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION, $projectName);
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
	global $TRACE_INFO_ACTION;
	$form_name = $FORM_TABLE_CEGID_DEVIS."_update";

	$condition="";
	$colFilter=NULL;
	$param = createDefaultParamSql ( $SQL_TABLE_DEVIS, $colDEVIS, $condition );
	$param = updateTableParamSql ( $param, $form_name, $colFilter );
	
	$res = -1;
	
	$formURL ="!".getURLVariable(PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT);
	showActionVariable("cegid_devis form [$formURL]", $TRACE_INFO_ACTION);
	$pos = strpos( $formURL, $FORM_TABLE_CEGID_DEVIS);
	showSQLAction("test $FORM_TABLE_CEGID_DEVIS for $formURL : [$pos]");
	if  ($pos>=1){
	    //nothing to do
	    showActionVariable("action for cegid_devis form [$FORM_TABLE_CEGID_DEVIS] accepted", $TRACE_INFO_ACTION);
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
	    
	    global $TRACE_INFO_ACTION;
    $action = getActionGet ();
    showActionVariable("action : [$action]  on applyGestionDevis() status [$res] before applyGestionTable()", $TRACE_INFO_ACTION);
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
        //$colFilter = NULL;
        $colFilter= "$colDEVIS";
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
// 	global $SQL_SHOW_COL_DEVIS;
// 	global $SQL_TABLE_DEVIS;
// 	global $CONDITION_FROM_CEGID_DEVIS;
// 	global $FORM_TABLE_CEGID_DEVIS;
// 	$form_name = $FORM_TABLE_CEGID_DEVIS."_insert";
// 	$condition="";
// 	$condition=$CONDITION_FROM_CEGID_DEVIS;
	
	
// 	$param = prepareshowTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name, $condition);
// 	//par defaut on a edit & delete
	
	$param = prepareParamShowTableDevis();
	//par defaut on a edit & delete
	
	//ajout export CSV
	$param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
	
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

function prepareParamShowTableDevis($columns=""){
        global $SQL_SHOW_COL_DEVIS;
        global $SQL_TABLE_DEVIS;
        global $CONDITION_FROM_CEGID_DEVIS;
        global $FORM_TABLE_CEGID_DEVIS;
        $form_name = $FORM_TABLE_CEGID_DEVIS."_insert";
        $condition="";
        $condition=$CONDITION_FROM_CEGID_DEVIS;
        
        //showSQLAction("showTableDEVIS - ...");
        if ($columns == ""){
            $columns = $SQL_SHOW_COL_DEVIS;
        }
        
        //showTable($SQL_TABLE_DEVIS, $SQL_SHOW_COL_DEVIS, $form_name);
        $param = prepareshowTable($SQL_TABLE_DEVIS, $columns, $form_name, $condition);
        $param = updateParamSqlColumnFilter($param, $columns);
        //par defaut on a edit & delete

        
        return $param;
}


 /**
  * application des actions sur la page de suivi des propositions
  */
 function applySuiviPropositions() {
//     $request = getRequeteCAByID($idRequest);
    $request ="suivi_proposition";
    $col= getURLVariable(PARAM_TABLE_COMMAND::EXPORT_COLUMNS);
     $form_name="form_suivi_proposition";
     applyGestionTable($request, $col, $form_name);
     //applyGestionDevis();
 }


function showSuiviPropositions() {
    global $FORM_TABLE_CEGID_DEVIS;
    
    global $SQL_COL_ID_DEVIS        ;
    global $SQL_COL_NAME_DEVIS      ;
    global $SQL_COL_VERSION_DEVIS   ;
    global $SQL_COL_CLIENT_DEVIS    ;
    global $SQL_COL_SOCIETE_DEVIS   ;
    global $SQL_COL_STATUS_DEVIS     ;
    global $SQL_COL_STATUS_CEGID_DEVIS ;
    global $SQL_COL_STATUS_COMMANDE_DEVIS;
    global $SQL_COL_COMMANDE_DEVIS       ;
    global $SQL_COL_MODIFICATION_DEVIS   ;
    global $SQL_COL_COMMANDE_DEVIS          ;
    global $SQL_COL_NUXEO_DEVIS          ;
    global $SQL_COL_COMMENTAIRE_DEVIS    ;
    global $SQL_COL_VISIBLE_DEVIS        ;
    global $SQL_COL_CEGID_DEVIS;
    
    
    global $SQL_TABLE_STATUS_EVOLUTION;
    
    global $SQL_COL_REFERENCE_STATUS_EVOLUTION;
    global $SQL_COL_DATE_STATUS_EVOLUTION;
    global $SQL_COL_STATUS_STATUS_EVOLUTION;
    global $SQL_COL_ORIGIN_STATUS_EVOLUTION;
    global $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION;
    
    
    global $SQL_TABLE_PROJECT;
    global $SQL_COL_DEBUT_PROJECT;
    global $SQL_COL_FIN_PROJECT;
    global $SQL_COL_FIN_GARANTIE;
    global $SQL_COL_ID_PROJECT;
    global $SQL_COL_PRIX_VENTE_PROJECT;
    
    
    
    $columns1 = " $SQL_COL_SOCIETE_DEVIS, $SQL_COL_NAME_DEVIS, $SQL_COL_ID_DEVIS, $SQL_COL_STATUS_DEVIS, $SQL_COL_NUXEO_DEVIS, $SQL_COL_CEGID_DEVIS,  $SQL_COL_COMMANDE_DEVIS, $SQL_COL_COMMANDE_DEVIS "; 
    
    $param = prepareParamShowTableDevis ($columns1);
    $param = modifierTableParamSql($param, $FORM_TABLE_CEGID_DEVIS, /*$insert*/"no", /*$edit*/"no", /*$delete*/"no", /*$exportCSV*/"yes");
    $req = createRequeteTableData ( $param );
    showSQLAction ( $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT]." : $req" );

     $result = sqlParamToArrayResult($param);
     $nbRes = mysqlNumrows ( $result );
     
     $COL_DDE = "Demande";
     $COL_VALIDE = "Valide";
     $COL_ENVOYE = "Envoye";
     $COL_ACCEPTE = "Accepte";
     $COL_DEBUT = "Accepte";
     
     $param2 = $param;
     $param2[PARAM_TABLE_TABLE::TABLE_SIZE]="1400px";
     
     //ajout colonne
     //$param2 = removeParamColumn($param2, $SQL_COL_NUXEO_DEVIS);
     $param2 = removeParamColumn($param2, $SQL_COL_COMMANDE_DEVIS);
     $param2 = addParamSqlColumn($param2, $COL_DDE);
     $param2 = addParamSqlColumn($param2, $COL_VALIDE);
     $param2 = addParamSqlColumn($param2, $COL_ENVOYE);
     $param2 = addParamSqlColumn($param2, $COL_ACCEPTE);
     $param2 = addParamSqlColumn($param2, $SQL_COL_PRIX_VENTE_PROJECT);
     $param2 = addParamSqlColumn($param2, $SQL_COL_DEBUT_PROJECT);
     $param2 = addParamSqlColumn($param2, $SQL_COL_FIN_PROJECT);
     $param2 = addParamSqlColumn($param2, $SQL_COL_FIN_GARANTIE);
     $param2 = addParamSqlColumn($param2, $SQL_COL_COMMANDE_DEVIS);
     
     //echoTD("<<<>>>".arrayToString($param2[PARAM_TABLE::COLUMNS_SUMMARY]));
     $param2[PARAM_TABLE_COMMAND::EXPORT_COLUMNS]=getParamColumns($param2);
     
     
     $result = setSQLFlagType ( $result, $COL_DDE, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $COL_VALIDE, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $COL_ENVOYE, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $COL_ACCEPTE, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $SQL_COL_PRIX_VENTE_PROJECT, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $SQL_COL_DEBUT_PROJECT, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $SQL_COL_FIN_PROJECT, SQL_TYPE::SQL_REQUEST );
     $result = setSQLFlagType ( $result, $SQL_COL_FIN_GARANTIE, SQL_TYPE::SQL_REQUEST );
     //$result = setSQLFlagTypeSize ( $result, $colpointage, 3 );
     //$tableau = setSQLFlagStatus($tableau, $c, "enabled");
     

     for($cpt = 0; $cpt < $nbRes; $cpt ++) {
         //accepte
         $result[$COL_ACCEPTE] [$cpt] = "select date($SQL_COL_DATE_STATUS_EVOLUTION) from $SQL_TABLE_STATUS_EVOLUTION ".
             " where $SQL_COL_REFERENCE_STATUS_EVOLUTION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'".
             " and $SQL_COL_STATUS_STATUS_EVOLUTION='Accepte'".
         "and $SQL_COL_ORIGIN_STATUS_EVOLUTION = $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION";
         //envoy�
         $result[$COL_ENVOYE] [$cpt] = "select date($SQL_COL_DATE_STATUS_EVOLUTION) from $SQL_TABLE_STATUS_EVOLUTION ".
             " where $SQL_COL_REFERENCE_STATUS_EVOLUTION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'".
             " and $SQL_COL_STATUS_STATUS_EVOLUTION='envoye'".
             "and $SQL_COL_ORIGIN_STATUS_EVOLUTION = $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION";
         //valide
         $result[$COL_VALIDE] [$cpt] = "select date($SQL_COL_DATE_STATUS_EVOLUTION) from $SQL_TABLE_STATUS_EVOLUTION ".
             " where $SQL_COL_REFERENCE_STATUS_EVOLUTION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'".
             " and $SQL_COL_STATUS_STATUS_EVOLUTION='Valide'".
         "and $SQL_COL_ORIGIN_STATUS_EVOLUTION = $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION";
         
         $result[$COL_VALIDE][$cpt] = $result[$COL_VALIDE][$cpt]." union ".$result[$COL_ENVOYE][$cpt];
         
         //demande
         $result[$COL_DDE] [$cpt] = "select date($SQL_COL_DATE_STATUS_EVOLUTION) from $SQL_TABLE_STATUS_EVOLUTION ".
             " where $SQL_COL_REFERENCE_STATUS_EVOLUTION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'".
             " and $SQL_COL_STATUS_STATUS_EVOLUTION='new'";
         //prix vente
         $result[$SQL_COL_PRIX_VENTE_PROJECT] [$cpt] = "select $SQL_COL_PRIX_VENTE_PROJECT from $SQL_TABLE_PROJECT ".
             " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
         //debut
         $result[$SQL_COL_DEBUT_PROJECT] [$cpt] = "select date($SQL_COL_DEBUT_PROJECT) from $SQL_TABLE_PROJECT ".
             " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
         //fin
         $result[$SQL_COL_FIN_PROJECT] [$cpt] = "select date($SQL_COL_FIN_PROJECT) from $SQL_TABLE_PROJECT ".
             " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
         //garantie
         $result[$SQL_COL_FIN_GARANTIE] [$cpt] = "select date($SQL_COL_FIN_GARANTIE) from $SQL_TABLE_PROJECT ".
             " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
     }
     
     
     //remet dans l'ordre les resultat
     //a faire car on a fait des removes de colonnes
     $columns = $param2[PARAM_TABLE::COLUMNS_SUMMARY];
     foreach ( $columns as $col){
         $result2[$col] = $result[$col];
         $result2 = setSQLFlagType($result2,$col, mysqlFieldType($result,$col));
         $result2 = setSQLFlagStatus($result2, $col, "no");
         //$result2 = setSQLFlagStatus($result2, $col, "yes");
     }
     
     

      //preparation pour l'export pour le apply
      //on met dans les variables url les valeurs
      $nbRes = mysqlNumrows($result2);
      foreach ( $columns as $col){
          for ($cpt = 0; $cpt < $nbRes; $cpt ++) {
                 $value[$cpt] = mysqlResult($result2, $cpt, $col);
          }
          //setURLVariable($col, $result2[$col]);
          setURLVariable($col, $value);
      }
     //gestion du apply
     applySuiviPropositions();
     
     //header
     showTableHeader ( $param2 );
     //data
     
     beginTableBody();
     //$res = showTableData($param2,"",$result2,"no");
     showEditTableData($param2,"",$result2);
     endTableBody();
     endTable();
     
     
     
}




?>