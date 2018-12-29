<?PHP
$PROPOSITION_DB_PHP = "loaded";

$SQL_TABLE_CEGID_PROPOSITION   = "cegid_proposition";
$FORM_TABLE_CEGID_PROPOSITION  = "form_table_cegid_proposition";


$SQL_COL_ID_PROPOSITION       = "ID";
$SQL_COL_PRIX_VENTE           = "PRIX_VENTE";
$SQL_COL_REUSSITE             = "REUSSITE";



$SQL_SHOW_COL_PROPOSITION   = "$SQL_COL_ID_PROPOSITION, $SQL_COL_PRIX_VENTE, $SQL_COL_REUSSITE";


// include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
// include_once (dirname ( __FILE__ ) . "/table_db.php");
// include_once (dirname ( __FILE__ ) . "/tool_db.php");
// include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/devis_db.php");





/**
 * application des actions sur la page de suivi des propositions
 */
function applySuiviPropositions() {
    if ((getActionGet () == "export CSV") || (getActionGet () == "exportCSV")) {
        $request ="suivi_proposition";
        $col= getURLVariable(PARAM_TABLE_COMMAND::EXPORT_COLUMNS);
        if ($col==""){
            showSQLAction("URL variable ".PARAM_TABLE_COMMAND::EXPORT_COLUMNS." not found");
        }
        $form_name="form_suivi_proposition";
        $res = applyGestionTable($request, $col, $form_name);
    }
    else{
       $res = -1;
    }
    
    return $res;
}

/**
 * applyGestionProposition
 * @return number
 */
function applyGestionProposition() {
    $res=-1;
    if ((getActionGet () == "export CSV") || (getActionGet () == "exportCSV")) {
        $res=1;
    }
    
    if ($res<=0){
        global $SQL_SHOW_COL_PROPOSITION;
        global $SQL_TABLE_CEGID_PROPOSITION;
        global $FORM_TABLE_CEGID_PROPOSITION;
        global $TRACE_INFO_ACTION;
        $form_name = $FORM_TABLE_CEGID_PROPOSITION."_update";
           
        $res =  applyGestionTable($SQL_TABLE_CEGID_PROPOSITION, $SQL_SHOW_COL_PROPOSITION, $form_name);
    }
    return $res;
}


/**
 * showSuiviPropositions
 */
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
        setURLVariable($col, $value);
        setURLVariable(PARAM_TABLE_COMMAND::EXPORT_COLUMNS, arrayToString($columns) );
        
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