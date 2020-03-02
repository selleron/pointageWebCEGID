<?PHP
$COMMANDE_PRESTA_DB_PHP = "loaded";


include_once 'project_db.php';
include_once 'user_cegid_db.php';
include_once 'time.php';
//include_once 'pointage_cegid_db.php';

$SQL_TABLE_COMMANDE_PRESTA        = "cegid_commande_prestataire";
$SQL_TABLE_COMMANDE_PRESTA2       = "cegid_commande_prestataire as cp,  cegid_user as u";
$FORM_TABLE_COMMANDE_PRESTA       = "form_table_cegid_commande_prestataire";

$SQL_COL_ID_COMMANDE_PRESTA             = "ID";
$SQL_COL_USER_ID_COMMANDE_PRESTA        = "USER_ID";
$SQL_COL_SOCIETE_COMMANDE_PRESTA        = "SOCIETE";
$SQL_COL_UO_COMMANDE_PRESTA             = "GROUPE";
$SQL_COL_STATUS_COMMANDE_PRESTA         = "STATUS";
$SQL_COL_COMMANDE_COMMANDE_PRESTA       = "COMMANDE";
$SQL_COL_DEBUT_COMMANDE_PRESTA          = "DEBUT";
$SQL_COL_FIN_COMMANDE_PRESTA            = "FIN";
$SQL_COL_ACHAT_COMMANDE_PRESTA          = "TARIF_ACHAT";
$SQL_COL_VENTE_COMMANDE_PRESTA          = "TARIF_VENTE";
$SQL_COL_UO_COMMANDE_PRESTA             = "UO";
$SQL_COL_COUT_COMMANDE_PRESTA           = "COUT";
$SQL_COL_VISIBLE_COMMANDE_PRESTA        = "VISIBLE";
$SQL_COL_COMMENTAIRE_COMMANDE_PRESTA    = "COMMENTAIRE";


$SQL_SHOW_UPDATE_COMMANDE     = "   $SQL_COL_ID_COMMANDE_PRESTA,  $SQL_COL_USER_ID_COMMANDE_PRESTA, $SQL_COL_SOCIETE_COMMANDE_PRESTA ,  GROUPE,  $SQL_COL_STATUS_COMMANDE_PRESTA,  $SQL_COL_COMMANDE_COMMANDE_PRESTA, $SQL_COL_DEBUT_COMMANDE_PRESTA,  $SQL_COL_FIN_COMMANDE_PRESTA,  $SQL_COL_ACHAT_COMMANDE_PRESTA, $SQL_COL_VENTE_COMMANDE_PRESTA,  $SQL_COL_UO_COMMANDE_PRESTA,  $SQL_COL_COUT_COMMANDE_PRESTA, $SQL_COL_VISIBLE_COMMANDE_PRESTA, $SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";

//$SQL_SHOW_UPDATE_COMMANDE_USER= "   $SQL_COL_ID_COMMANDE_PRESTA,  $SQL_COL_USER_ID_COMMANDE_PRESTA, NAME, NOM, PRENOM, $SQL_COL_SOCIETE_COMMANDE_PRESTA ,  GROUPE,  $SQL_COL_STATUS_COMMANDE_PRESTA,  $SQL_COL_COMMANDE_COMMANDE_PRESTA, $SQL_COL_DEBUT_COMMANDE_PRESTA,  $SQL_COL_FIN_COMMANDE_PRESTA,  $SQL_COL_ACHAT_COMMANDE_PRESTA, $SQL_COL_VENTE_COMMANDE_PRESTA,  $SQL_COL_UO_COMMANDE_PRESTA,  $SQL_COL_COUT_COMMANDE_PRESTA, $SQL_COL_VISIBLE_COMMANDE_PRESTA, $SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";
//$SQL_SELECT_UPDATE_COMMANDE_USER= "   cp.$SQL_COL_ID_COMMANDE_PRESTA,  cp.$SQL_COL_USER_ID_COMMANDE_PRESTA, u.NAME, u.NOM, u.PRENOM, cp.$SQL_COL_SOCIETE_COMMANDE_PRESTA ,  cp.GROUPE,  cp.$SQL_COL_STATUS_COMMANDE_PRESTA,  cp.$SQL_COL_COMMANDE_COMMANDE_PRESTA, cp.$SQL_COL_DEBUT_COMMANDE_PRESTA,  cp.$SQL_COL_FIN_COMMANDE_PRESTA,  cp.$SQL_COL_ACHAT_COMMANDE_PRESTA, cp.$SQL_COL_VENTE_COMMANDE_PRESTA,  cp.$SQL_COL_UO_COMMANDE_PRESTA,  cp.$SQL_COL_COUT_COMMANDE_PRESTA, cp.$SQL_COL_VISIBLE_COMMANDE_PRESTA, cp.$SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";
$SQL_SHOW_UPDATE_COMMANDE_USER= "   $SQL_COL_ID_COMMANDE_PRESTA,  NAME, NOM, PRENOM, $SQL_COL_SOCIETE_COMMANDE_PRESTA ,  GROUPE,  $SQL_COL_STATUS_COMMANDE_PRESTA,  $SQL_COL_COMMANDE_COMMANDE_PRESTA, $SQL_COL_DEBUT_COMMANDE_PRESTA,  $SQL_COL_FIN_COMMANDE_PRESTA,  $SQL_COL_ACHAT_COMMANDE_PRESTA, $SQL_COL_VENTE_COMMANDE_PRESTA,  $SQL_COL_UO_COMMANDE_PRESTA,  $SQL_COL_COUT_COMMANDE_PRESTA, $SQL_COL_VISIBLE_COMMANDE_PRESTA, $SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";
$SQL_SELECT_UPDATE_COMMANDE_USER= "   cp.$SQL_COL_ID_COMMANDE_PRESTA,  u.NAME, u.NOM, u.PRENOM, cp.$SQL_COL_SOCIETE_COMMANDE_PRESTA ,  cp.GROUPE,  cp.$SQL_COL_STATUS_COMMANDE_PRESTA,  cp.$SQL_COL_COMMANDE_COMMANDE_PRESTA, cp.$SQL_COL_DEBUT_COMMANDE_PRESTA,  cp.$SQL_COL_FIN_COMMANDE_PRESTA,  cp.$SQL_COL_ACHAT_COMMANDE_PRESTA, cp.$SQL_COL_VENTE_COMMANDE_PRESTA,  cp.$SQL_COL_UO_COMMANDE_PRESTA,  cp.$SQL_COL_COUT_COMMANDE_PRESTA, cp.$SQL_COL_VISIBLE_COMMANDE_PRESTA, cp.$SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";

$SQL_SELECT_COL_COMMANDE_USER = "cp.$SQL_COL_ID_COMMANDE_PRESTA as ID, u.NOM, u.PRENOM, cp.$SQL_COL_SOCIETE_COMMANDE_PRESTA , cp.GROUPE, cp.$SQL_COL_STATUS_COMMANDE_PRESTA, cp.$SQL_COL_DEBUT_COMMANDE_PRESTA, cp.$SQL_COL_FIN_COMMANDE_PRESTA, cp.$SQL_COL_ACHAT_COMMANDE_PRESTA, cp.$SQL_COL_VENTE_COMMANDE_PRESTA, cp.$SQL_COL_UO_COMMANDE_PRESTA, cp.$SQL_COL_COUT_COMMANDE_PRESTA, cp.$SQL_COL_VISIBLE_COMMANDE_PRESTA, cp.$SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";
$SQL_SHOW_COL_COMMANDE_USER   = "   $SQL_COL_ID_COMMANDE_PRESTA,         NOM,   PRENOM,    $SQL_COL_SOCIETE_COMMANDE_PRESTA ,    GROUPE,    $SQL_COL_STATUS_COMMANDE_PRESTA,    $SQL_COL_DEBUT_COMMANDE_PRESTA,    $SQL_COL_FIN_COMMANDE_PRESTA,    $SQL_COL_ACHAT_COMMANDE_PRESTA,    $SQL_COL_VENTE_COMMANDE_PRESTA,    $SQL_COL_UO_COMMANDE_PRESTA,    $SQL_COL_COUT_COMMANDE_PRESTA, $SQL_COL_VISIBLE_COMMANDE_PRESTA,    $SQL_COL_COMMENTAIRE_COMMANDE_PRESTA ";

$SQL_SHOW_WHERE_COMMANDE_USER = "cp.$SQL_COL_USER_ID_COMMANDE_PRESTA = u.ID";
$SQL_SHOW_INSERT_COL_COMMANDE = "NAME, $SQL_COL_SOCIETE_COMMANDE_PRESTA, $SQL_COL_DEBUT_COMMANDE_PRESTA,    $SQL_COL_FIN_COMMANDE_PRESTA,    $SQL_COL_ACHAT_COMMANDE_PRESTA,    $SQL_COL_VENTE_COMMANDE_PRESTA,  $SQL_COL_UO_COMMANDE_PRESTA";


function getStyleDateDebutCommandePrestataire( $date1 ) {
    $format = "";
    
    //showAction("getStyleDateCommandePrestataire ( \"$date1\" ) ");
    
    if (time() < sqlDateToTime($date1)){
        $format ="bgcolor='#AAAAFF'";
    }
    else  {
        $format ="bgcolor='#AAFFAA'";
    }

    return $format;
}

function getStyleDateFinCommandePrestataire( $date1 ) {
    $timeDelai = 3600*30;
    $format = "";
    
    //showAction("getStyleDateCommandePrestataire ( \"$date1\" ) ");
    
    if (time() < sqlDateToTime($date1)){
        $format ="bgcolor='#AAFFAA'";
    }
    if (  (time()+$timeDelai) > sqlDateToTime($date1)){
        $format ="bgcolor='#FFAAFF'";
    }
    if (time() > sqlDateToTime($date1)  ){
            $format ="bgcolor='#FFAAAA'";
    }
            
    //showAction(" return getStyleDateCommandePrestataire() : $format ");
    return $format;
}

/**
 * computeCout
 * @param double ou array $vente
 * @param double ou array $uo
 * @return NULL|number|number[]
 */
function computeCout($vente, $uo){
    $cout = NULL;
    if (isset($vente) && isset($uo)){
        if (is_array($vente) && is_array($uo)){
            $cout = array();
            $nb = count($vente);
            for($i=0; $i<$nb;$i++){
                $cout[$i] = $vente[$i]*$cout[$i];
            }
        }
        else if (is_numeric($vente)&& is_numeric($uo)){
            $cout = $vente * $uo;
        }
    }
    return $cout;
}
/**
 * computeUO
 * compute UO from variable url debut & fin ou variable url uo
 * @return string| integer |NULL
 */
function computeUO(){
    global $SQL_COL_UO_COMMANDE_PRESTA;
    global $SQL_COL_DEBUT_COMMANDE_PRESTA;
    global $SQL_COL_FIN_COMMANDE_PRESTA;
    $uo = getURLVariable($SQL_COL_UO_COMMANDE_PRESTA);
    
    if (is_numeric($uo)){
        if ($uo>0) return $uo;
    }
    
    $debut = getURLVariable($SQL_COL_DEBUT_COMMANDE_PRESTA);
    $fin = getURLVariable($SQL_COL_FIN_COMMANDE_PRESTA);
    
    if (isSqlDate($debut) && isSqlDate($fin) ){
        $time1 = sqlDateToTime($debut);
        $time2 = sqlDateToTime($fin);
        $duree = ($time2 - $time1)/3600/24;
        $duree = intval($duree*4/7);
        //showSQLAction("times : $time1 -  $time2  ||  $duree");
        return $duree;
    }
    
    return NULL;
}

/**
 * applyGestionCommandePrestataire
 * @return number
 */
function applyGestionCommandePrestataire() {
    global $SQL_SHOW_UPDATE_COMMANDE;
    global $SQL_SHOW_UPDATE_COMMANDE_USER;
    global $SQL_SELECT_UPDATE_COMMANDE_USER;
    global $SQL_SELECT_COL_COMMANDE_USER;
    global $SQL_SHOW_COL_COMMANDE_USER;
    global $SQL_COL_COUT_COMMANDE_PRESTA;
    global $SQL_COL_UO_COMMANDE_PRESTA;
    global $SQL_COL_ACHAT_COMMANDE_PRESTA;
    global $SQL_COL_UO_COMMANDE_PRESTA;
    global $SQL_TABLE_COMMANDE_PRESTA;
    global $SQL_TABLE_COMMANDE_PRESTA2;
    global $FORM_TABLE_COMMANDE_PRESTA;
    global $SQL_SHOW_WHERE_COMMANDE_USER;
    $table = $SQL_TABLE_COMMANDE_PRESTA;
    $col = $SQL_SHOW_UPDATE_COMMANDE;
    $colFilter = "";
    $condition = "";
    $param = NULL;
    $form_name = $FORM_TABLE_COMMANDE_PRESTA . "_update";
    $res=-1;

    $action = getActionGet();
    $groupe = getURLVariable("GROUPE");
    //showAction("action : $action");
    showAction("action groupe : $groupe");
    
    if ($action == LabelAction::ActionImport  ){
        $table =   $SQL_TABLE_COMMANDE_PRESTA;
        //showAction("action import : $action");
    }
    
    if ($action == LabelAction::ActionInserer || $action == LabelAction::ActionInsert || $action == LabelAction::ActionUpdate  ){
        //on force le recalcul du cout
        $vente = getURLVariable($SQL_COL_ACHAT_COMMANDE_PRESTA);
        $uo = computeUO();      
        $cout = computeCout($vente, $uo);
        setURLVariable($SQL_COL_COUT_COMMANDE_PRESTA, $cout);
        setURLVariable($SQL_COL_UO_COMMANDE_PRESTA, $uo);
        //showTracePOST();
    }

    if ($action == LabelAction::ActionUpdate ){
        //update
        //showSQLAction("commande prestataire : $action update ...");
        $param = createDefaultParamSql($table, $col, $condition);
        $param = updateTableParamSql ( $param, $form_name, $colFilter );
        $param = updateTableParamType ( $param, $table, $col, $form_name );
        updateTableByGet($param, "no");
        //on doit fait l'edit apres
    }
        
    if ($action == LabelAction::ActionUpdate ||  $action == LabelAction::ActionEdit ){
            //edit & reedit
        //showSQLAction("commande prestataire : $action edit ...");
        $table = $SQL_TABLE_COMMANDE_PRESTA2;
        $col = $SQL_SHOW_UPDATE_COMMANDE_USER;
        $colFilter = $SQL_SELECT_UPDATE_COMMANDE_USER;
        $condition = $SQL_SHOW_WHERE_COMMANDE_USER;
        $param = createDefaultParamSql($table, $col, $condition);
        $param = updateTableParamSql ( $param, $form_name, $colFilter );
        $param = updateTableParamType ( $param, $table, $col, $form_name );
        $res = editTable2($param);
    }
    
    if ($action == LabelAction::ActionExportCSV || $action == LabelAction::ActionExport2|| $action == LabelAction::ActionExport3){
         //showSQLAction("commande prestataire : $action export ...");
         $condition = createConditionCommandePrestataire();
         $col = $SQL_SHOW_UPDATE_COMMANDE_USER;
         $colFilter = $SQL_SELECT_UPDATE_COMMANDE_USER;
         $table = $SQL_TABLE_COMMANDE_PRESTA2;
         $param = createDefaultParamSql($table, $col, $condition);
         $param = updateTableParamSql ( $param, $form_name, $colFilter );
         $param = updateTableParamType ( $param, $table, $col, $form_name );

         $request = createRequeteTableData($param);
         $table = $request;
         $param = NULL;
     }
          
    // cas classique : edit, export, ...
    if ($res <= 0) {
        $res = applyGestionTable($table, $col, $form_name, $colFilter, $param);
    }
    return $res;
}



/**
 * table pour l'insert du user
 * (description)
 */
function showOnlyInsertTableCommandePrestataire($condition="") {
    global $SQL_TABLE_COMMANDE_PRESTA;
    global $SQL_SHOW_INSERT_COL_COMMANDE;
    global $FORM_TABLE_COMMANDE_PRESTA;
    $form_name = $FORM_TABLE_COMMANDE_PRESTA."_insert";
    
    //showTable($SQL_TABLE_CEGID_USER, $SQL_SHOW_COL_CEGID_USER, $form_name);
    $param = prepareshowTable($SQL_TABLE_COMMANDE_PRESTA, $SQL_SHOW_INSERT_COL_COMMANDE, $form_name, $condition);
    $param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "no";
    $infoForm = getInfoFormProjectSelection();
    $param  = setInfoForm($param, $infoForm);
    
    // pour avoir les types des variables
    $Resultat = requeteTableData($param);
    
    $selectedValue= array();
    $userName =  getURLVariable(FORM_COMBOX_BOX_KEY::USER_SELECTION);
    $selectedValue["NAME"] = "$userName";
    
    showOnlyInsertTableByParam("", $Resultat, $param, $selectedValue);
    

}


/**
 * showTableCommandePresta
 *
 * show table Commande Prestaire
 */
function showTableCommandePresta( $conditionVisible="") {   
    global $TRACE_INFO_PROJECT;
    
    global $FORM_TABLE_COMMANDE_PRESTA;
    global $SQL_TABLE_COMMANDE_PRESTA;
    global $SQL_TABLE_COMMANDE_PRESTA2;
    global $SQL_SELECT_COL_COMMANDE_USER;
    global $SQL_SHOW_COL_COMMANDE_USER;
    global $SQL_SHOW_WHERE_COMMANDE_USER;
    global $SQL_COL_DEBUT_COMMANDE_PRESTA;
    $form_name = $FORM_TABLE_COMMANDE_PRESTA/*."_one_update"*/;
       
    $condition = createConditionCommandePrestataire($conditionVisible);
     $infoForm = getInfoFormProjectSelection();
    
    $param = prepareshowTable ( $SQL_TABLE_COMMANDE_PRESTA2, $SQL_SHOW_COL_COMMANDE_USER, $form_name, $condition );
    $param [PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
    $param [PARAM_TABLE_ACTION::TABLE_INSERT] = "No";
    $param = updateParamSqlWithDistinct ( $param );
    $param = updateParamSqlColumnFilter ( $param, $SQL_SELECT_COL_COMMANDE_USER );
    $param  = setInfoForm($param, $infoForm);
    $param[PARAM_TABLE_TABLE::TABLE_SIZE] = 2000;       // taille du tableau agrandi
    $param[PARAM_TABLE_ACTION::ACTIONS_AT_LEFT]="yes";  // affiche aussi les actions à gauche
    
    //trace
    $req = createRequeteTableData ( $param );
    showActionVariable( $req, $TRACE_INFO_PROJECT );
    //end trace
    
 //   //export complexe
 //   $param[PARAM_TABLE_COMMAND::EXPORT_COLUMNS]=getParamColumns($param);
 //   $param[PARAM_TABLE_ACTION::TABLE_UPDATE] = "yes";
    
    //show table
    showTableByParam($param);
}

/**
 * createConditionCommandePrestataire
 * recherche les variables rl pour creer les contition pour la commande prestataire
 * @param string $conditionVisible
 * @return string
 */
function createConditionCommandePrestataire($conditionVisible=""){
//     global $FORM_TABLE_COMMANDE_PRESTA;
//     global $SQL_TABLE_COMMANDE_PRESTA;
//     global $SQL_TABLE_COMMANDE_PRESTA2;
//     global $SQL_SELECT_COL_COMMANDE_USER;
//     global $SQL_SHOW_COL_COMMANDE_USER;
    global $SQL_SHOW_WHERE_COMMANDE_USER;
    global $SQL_COL_DEBUT_COMMANDE_PRESTA;
    
    $condition=$SQL_SHOW_WHERE_COMMANDE_USER;
    
    // info formulaire year et user name
    $userName =  getURLVariable(FORM_COMBOX_BOX_KEY::USER_SELECTION);
    $year = getURLYear(FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION);
    
    //$year
    if (is_numeric($year)){
        $condition = "$condition AND year(cp.$SQL_COL_DEBUT_COMMANDE_PRESTA)=\"$year\"";
    }
    
    
    if ($userName=="" || $userName==FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION){
        //nothing to do
    }
    else{
        $condition = "$condition AND u.NAME=\"$userName\"";
    }
    
    if ($conditionVisible!=""){
        $condition = "$condition AND $conditionVisible";
    }
    
    showSQLAction("condition : $condition");
    return $condition;   
}


?>