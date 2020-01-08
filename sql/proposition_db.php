<?PHP
$PROPOSITION_DB_PHP = "loaded";

$SQL_TABLE_CEGID_PROPOSITION   = "cegid_proposition";
$FORM_TABLE_CEGID_PROPOSITION  = "form_table_cegid_proposition";


$SQL_COL_ID_PROPOSITION          = "ID";
$SQL_COL_PRIX_VENTE_PROPOSITION  = "PRIX_VENTE";
$SQL_COL_REUSSITE_PROPOSITION    = "REUSSITE";
$SQL_COL_COMMENTAIRE_PROPOSITION = "COMMENTAIRE";

$SQL_SHOW_COL_PROPOSITION   = "$SQL_COL_ID_PROPOSITION, $SQL_COL_PRIX_VENTE_PROPOSITION, $SQL_COL_REUSSITE_PROPOSITION, $SQL_COL_COMMENTAIRE_PROPOSITION";

$SQL_TABLE_CEGID_PROPOSITION_ANNEE   = "cegid_proposition_annee";
$FORM_TABLE_CEGID_PROPOSITION_ANNEE  = "form_table_cegid_proposition_annee";


$SQL_COL_ID_PROPOSITION_ANNEE          = $SQL_COL_ID_PROPOSITION;
$SQL_COL_ANNEE_PROPOSITION_ANNEE       = "ANNEE";
$SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE  = $SQL_COL_PRIX_VENTE_PROPOSITION;
$SQL_COL_COMMENTAIRE_PROPOSITION_ANNEE = $SQL_COL_COMMENTAIRE_PROPOSITION;

$SQL_SHOW_COL_PROPOSITION_ANNEE   = "$SQL_COL_ID_PROPOSITION_ANNEE, $SQL_COL_ANNEE_PROPOSITION_ANNEE, $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE, $SQL_COL_COMMENTAIRE_PROPOSITION_ANNEE";


// include_once (dirname ( __FILE__ ) . "/../configuration/labelAction.php");
// include_once (dirname ( __FILE__ ) . "/table_db.php");
// include_once (dirname ( __FILE__ ) . "/tool_db.php");
// include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/devis_db.php");
include_once (dirname ( __FILE__ ) . "/ca_previsionel_db.php");





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


function createPrixPropositionAnnee($year){
    global $SQL_COL_PRIX_VENTE_PROPOSITION;
    global $SQL_COL_ANNEE_PROPOSITION_ANNEE;
    
    $prixGlobal = getURLVariable($SQL_COL_PRIX_VENTE_PROPOSITION);
    $key="$SQL_COL_ANNEE_PROPOSITION_ANNEE"."_".$year;
    $prixLocal = getURLVariable($key);
    $max = sizeof($prixGlobal);
    for($i=0;$i<$max;$i++){
        if (isset($prixLocal) && isset($prixLocal[$i])){
            //nothing to do
            if ($prixLocal[$i]==""){
                $prixLocal[$i] = $prixGlobal[$i];
            }
        }
        else{
            $prixLocal[$i] = $prixGlobal[$i];
        }
    }
    
    return $prixLocal;
}

/**
 * applyGestionProposition
 * @return number
 */
function applyGestionProposition() {
    global $SQL_TABLE_CEGID_PROPOSITION_ANNEE;
    global $SQL_COL_ID_PROPOSITION_ANNEE;
    global $SQL_COL_ANNEE_PROPOSITION_ANNEE;
    global  $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE;
    
     $res=-1;
    if ((getActionGet () == "export CSV") || (getActionGet () == LabelAction::ActionExportCSV)) {
        $res=1;
    }
    
    if (getActionGet() == LabelAction::ActionTruncate ){
        showSQLAction("applyGestionProposition() - Truncate  ...");
        $year = getURLYear("-1");
        $truncate = "DELETE FROM $SQL_TABLE_CEGID_PROPOSITION_ANNEE WHERE $SQL_COL_ANNEE_PROPOSITION_ANNEE = $year ";
        showSQLAction("requeste : $truncate");
        mysqlQuery($truncate);
        $res=1;
    }
    else if (getActionGet() == LabelAction::ACTION_SYNCHRONIZE ){
        showSQLAction("applyGestionProposition() - compute CA ...");
        global $ID_REQUETE_SQL_CA_RESPONSABLE_AFFAIRES;
        
        $arrayCol = array( $SQL_COL_ID_PROPOSITION_ANNEE, $SQL_COL_ANNEE_PROPOSITION_ANNEE, $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE  );
        $year = getURLYear();
        
        //recuperation de la requete
        $request = getRequeteCAByID($ID_REQUETE_SQL_CA_RESPONSABLE_AFFAIRES); 
        if ($request == ""){
            showError("request id not found : $idRequest");
        }
        else{
            //execute request
            //showSQLAction($request);
            $Resultat = mysqlQuery($request);
            $nbRes = mysqlNumrows($Resultat);
            //showSQLAction("Resultats trouv&eacute;s : $nbRes");
            for ($cpt = 0; $cpt < $nbRes; $cpt ++) {
                //recuperation projet et ca previsionnel
                $projet = mysqlResult($Resultat, $cpt, "project_id");
                $ca = mysqlResult($Resultat, $cpt, "CA_Realise_prev");
                //recuperation du devis
                $requestDevis = "select ID from cegid_devis_project where cegid=\"$projet\"";
                $ResultatDevis = mysqlQuery($requestDevis);
                $nbResDevis = mysqlNumrows($ResultatDevis);
                if ($nbResDevis>0){
                    //showSQLAction("request : $requestDevis - $nbResDevis");
                    $devis = mysqlResult($ResultatDevis, 0, "$SQL_COL_ID_PROPOSITION_ANNEE");
                    if ($ca > 0){
                        $arrayValue = array($devis, $year, $ca);
                        $insert = createSqlReplace($SQL_TABLE_CEGID_PROPOSITION_ANNEE, $arrayCol, $arrayValue);
                        //showSQLAction("request : $insert");
                        mysqlQuery($insert);
                    }
                }
            }
        }
        $res=1;
    }
    
    if ($res<=0){
        //table proposition
        global $SQL_TABLE_CEGID_PROPOSITION;
        global $SQL_SHOW_COL_PROPOSITION;
        global $FORM_TABLE_CEGID_PROPOSITION;
        //table proposition annee
        global $SQL_TABLE_CEGID_PROPOSITION_ANNEE;
        global $SQL_SHOW_COL_PROPOSITION_ANNEE;
        global $FORM_TABLE_CEGID_PROPOSITION_ANNEE;
        global $SQL_COL_ANNEE_PROPOSITION_ANNEE;
        global $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE;
        global $SQL_COL_PRIX_VENTE_PROPOSITION;
        global $YEAR_SELECTION;
        
        global $TRACE_INFO_ACTION;
        
        $table = $SQL_TABLE_CEGID_PROPOSITION;
        $cols = $SQL_SHOW_COL_PROPOSITION;
        $colFilter=NULL;
        $form_name = $FORM_TABLE_CEGID_PROPOSITION."_update";
        
        
        //cas update sans re-edit
        if (getActionGet () == LabelAction::ActionUpdate) {
            //update table proposition
            //$res = updateTableByGet ( $param, "no" );
            $res = multiReplaceTableByGet2($table, $cols, $form_name);
            
            //update table proposition annee
            //1. positionner l'annee  $SQL_COL_ANNEE_PROPOSITION_ANNEE
            //2. positionner le prix  $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE
            //3. update de la table
            
            //1
            $year = getURLVariable($YEAR_SELECTION);
            setURLVariable($SQL_COL_ANNEE_PROPOSITION_ANNEE, $year);
            //2
            $arrayPrix = getURLVariable($SQL_COL_PRIX_VENTE_PROPOSITION);
            $arrayPrix2 = createPrixPropositionAnnee($year);
            setURLVariable($SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE, $arrayPrix2);
            //3
            $res2 = multiReplaceTableByGet2($SQL_TABLE_CEGID_PROPOSITION_ANNEE, $SQL_SHOW_COL_PROPOSITION_ANNEE, $FORM_TABLE_CEGID_PROPOSITION_ANNEE);
            setURLVariable($SQL_COL_PRIX_VENTE_PROPOSITION, $arrayPrix);
        }
            
        if ($res<=0){   
            $res =  applyGestionTable($table, $cols, $form_name);
        }
    }
    return $res;
}


/**
 * showSuiviPropositions
 */
function showSuiviPropositions() {
    //table devis
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
    global $SQL_COL_COMMANDE_DEVIS       ;
    global $SQL_COL_NUXEO_DEVIS          ;
    global $SQL_COL_COMMENTAIRE_DEVIS    ;
    global $SQL_COL_VISIBLE_DEVIS        ;
    global $SQL_COL_CEGID_DEVIS;
    
    
    //Table evolution
    global $SQL_TABLE_STATUS_EVOLUTION;   
    global $SQL_COL_REFERENCE_STATUS_EVOLUTION;
    global $SQL_COL_DATE_STATUS_EVOLUTION;
    global $SQL_COL_STATUS_STATUS_EVOLUTION;
    global $SQL_COL_ORIGIN_STATUS_EVOLUTION;
    global $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION;
    
    //Table project
    global $SQL_TABLE_PROJECT;
    global $SQL_COL_DEBUT_PROJECT;
    global $SQL_COL_FIN_PROJECT;
    global $SQL_COL_FIN_GARANTIE;
    global $SQL_COL_ID_PROJECT;
 
    //table proposition
    global $FORM_TABLE_CEGID_PROPOSITION;
    global $SQL_TABLE_CEGID_PROPOSITION;
    global $SQL_COL_ID_PROPOSITION;
    global $SQL_COL_PRIX_VENTE_PROPOSITION;
    global $SQL_COL_REUSSITE_PROPOSITION;
    global $SQL_COL_COMMENTAIRE_PROPOSITION;
    
    //table proposition_annee
    global $SQL_TABLE_CEGID_PROPOSITION_ANNEE;
    global $SQL_COL_ID_PROPOSITION_ANNEE;
    global $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE;
    global $SQL_COL_ANNEE_PROPOSITION_ANNEE;
    global $SQL_COL_COMMENTAIRE_PROPOSITION_ANNEE;
    
    //mixte
    global $SQL_COL_PRIX_VENTE_PROJECT;
    
    //autre
    global $YEAR_SELECTION;
    global $PROJECT_SELECTION;
    global $TABLE_FORM_NAME_INSERT;
    global $PROJECT_SELECTION;
    global $SQL_COL_NAME_PROJECT;
    
    
    
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
    //$COL_DEBUT = "Accepte";
    
    //condition year
    //on ne s'en sert que si la date est vraiment defini
    //on ne veut pas forcement filtrer pour projet sur plusieurs année
    global $YEAR_SELECTION;
    $year = getURLVariable($YEAR_SELECTION);
    $COL_YEAR="ANNEE_".$year;
    
    
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
    $param2 = addParamSqlColumn($param2, $SQL_COL_REUSSITE_PROPOSITION);
    $param2 = addParamSqlColumn($param2, $SQL_COL_DEBUT_PROJECT);
    $param2 = addParamSqlColumn($param2, $SQL_COL_FIN_PROJECT);
    $param2 = addParamSqlColumn($param2, $SQL_COL_FIN_GARANTIE);
    $param2 = addParamSqlColumn($param2, $SQL_COL_COMMANDE_DEVIS);
    $param2 = addParamSqlColumn($param2, $SQL_COL_COMMENTAIRE_PROPOSITION);

    if (is_numeric($year)){
        $param2 = addParamSqlColumn($param2, $COL_YEAR);
    }
    
    
    //echoTD("<<<>>>".arrayToString($param2[PARAM_TABLE::COLUMNS_SUMMARY]));
    $param2[PARAM_TABLE_COMMAND::EXPORT_COLUMNS]=getParamColumns($param2);
    
    
    $result = setSQLFlagType ( $result, $COL_DDE, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $COL_VALIDE, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $COL_ENVOYE, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $COL_ACCEPTE, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_PRIX_VENTE_PROJECT, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_REUSSITE_PROPOSITION, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_DEBUT_PROJECT, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_FIN_PROJECT, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_FIN_GARANTIE, SQL_TYPE::SQL_REQUEST );
    $result = setSQLFlagType ( $result, $SQL_COL_COMMENTAIRE_PROPOSITION, SQL_TYPE::SQL_REQUEST );
    if (is_numeric($year)){
        $result = setSQLFlagType ( $result, $COL_YEAR, SQL_TYPE::SQL_REQUEST );
        $type = mysqlFieldType($result, $COL_YEAR);
        //echo "<br>col $COL_YEAR : type : $type <br>";
    }
    //$result = setSQLFlagTypeSize ( $result, $colpointage, 3 );
    //$tableau = setSQLFlagStatus($tableau, $c, "enabled");
    
    
    
    
    for($cpt = 0; $cpt < $nbRes; $cpt ++) {
        //accepte
        $result[$COL_ACCEPTE] [$cpt] = "select date($SQL_COL_DATE_STATUS_EVOLUTION) from $SQL_TABLE_STATUS_EVOLUTION ".
            " where $SQL_COL_REFERENCE_STATUS_EVOLUTION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'".
            " and $SQL_COL_STATUS_STATUS_EVOLUTION='Accepte'".
            "and $SQL_COL_ORIGIN_STATUS_EVOLUTION = $SQL_TRIGGER_ORIGIN_STATUS_DEVIS_EVOLUTION";
        //envoye
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
        $result[$SQL_COL_PRIX_VENTE_PROJECT] [$cpt] = $result[$SQL_COL_PRIX_VENTE_PROJECT] [$cpt]." union "."select $SQL_COL_PRIX_VENTE_PROPOSITION from $SQL_TABLE_CEGID_PROPOSITION ".
           " where $SQL_COL_ID_PROPOSITION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'";
        //debut
        $result[$SQL_COL_DEBUT_PROJECT] [$cpt] = "select date($SQL_COL_DEBUT_PROJECT) from $SQL_TABLE_PROJECT ".
            " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
        //fin
        $result[$SQL_COL_FIN_PROJECT] [$cpt] = "select date($SQL_COL_FIN_PROJECT) from $SQL_TABLE_PROJECT ".
            " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";
        //garantie
        $result[$SQL_COL_FIN_GARANTIE] [$cpt] = "select date($SQL_COL_FIN_GARANTIE) from $SQL_TABLE_PROJECT ".
            " where $SQL_COL_ID_PROJECT   ='". mysqlResult ( $result, $cpt, "$SQL_COL_CEGID_DEVIS" )."'";

        $result[$SQL_COL_REUSSITE_PROPOSITION] [$cpt] = "select $SQL_COL_REUSSITE_PROPOSITION from $SQL_TABLE_CEGID_PROPOSITION ".
            " where $SQL_COL_ID_PROPOSITION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'";
        
        $result[$SQL_COL_COMMENTAIRE_PROPOSITION] [$cpt] = "select $SQL_COL_COMMENTAIRE_PROPOSITION from $SQL_TABLE_CEGID_PROPOSITION ".
            " where $SQL_COL_ID_PROPOSITION   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'";
        

        if (is_numeric($year)){
            $result[$COL_YEAR] [$cpt] = "select $SQL_COL_PRIX_VENTE_PROPOSITION_ANNEE from $SQL_TABLE_CEGID_PROPOSITION_ANNEE ".
                " where $SQL_COL_ID_PROPOSITION_ANNEE   ='". mysqlResult ( $result, $cpt, "$SQL_COL_ID_DEVIS" )."'";
        }
        
        //$txt =   $result[$SQL_COL_REUSSITE_PROPOSITION][$cpt];
        //showSQLAction(">>>> $txt");
        
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

    $result2 = setSQLFlagStatus($result2, $SQL_COL_ID_PROPOSITION, "yes");
    $result2 = setSQLFlagStatus($result2, $SQL_COL_PRIX_VENTE_PROPOSITION, "yes");
    $result2 = setSQLFlagStatus($result2, $SQL_COL_REUSSITE_PROPOSITION, "yes");
    $result2 = setSQLFlagStatus($result2, $SQL_COL_COMMENTAIRE_PROPOSITION, "yes");
    if (is_numeric($year)){
        //$result2 = setSQLFlagType ( $result2, $COL_YEAR, SQL_TYPE::SQL_REQUEST );
        $result2 = setSQLFlagStatus($result2, $COL_YEAR, "yes");
    }
        
    
    
    //preparation pour l'export pour le apply
    //on met dans les variables url les valeurs
    $nbRes = mysqlNumrows($result2);
    foreach ( $columns as $col){
//         if ($col==$COL_YEAR){
//             $type = mysqlFieldType($result2, $col);
//             echo "<br>$col : type : $type <br>"; 
//         }
        for ($cpt = 0; $cpt < $nbRes; $cpt ++) {
            $value[$cpt] = mysqlResult($result2, $cpt, $col);
        }
        setURLVariable($col, $value);
        //setURLVariable(PARAM_TABLE_COMMAND::EXPORT_COLUMNS, arrayToString($columns) );       
    }

    setURLVariable(PARAM_TABLE_COMMAND::EXPORT_COLUMNS, arrayToString($columns) );
    $param2[PARAM_TABLE_ACTION::TABLE_UPDATE]="yes";
    
    
    // info formulaire year et project name
    $projectName = getURLVariable($PROJECT_SELECTION);
    $infoForm = streamFormHidden($YEAR_SELECTION, $year);
    $infoForm = $infoForm . streamFormHidden($PROJECT_SELECTION, $projectName);
    $infoForm = $infoForm . streamFormHidden($TABLE_FORM_NAME_INSERT, $FORM_TABLE_CEGID_PROPOSITION);
    $param2 = setInfoForm($param2, $infoForm);
    
    
    
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