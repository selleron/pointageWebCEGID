<?PHP
$PROJECT_DB_PHP = "loaded";

$SQL_TABLE_PROJECT = "cegid_project";
$FORM_TABLE_CEGID_PROJECT = "form_table_cegid_project";

$SQL_COL_ID_PROJECT = "CEGID";
$SQL_COL_NAME_PROJECT = "NAME";
$SQL_COL_DESCRIPTION_PROJECT = "description";
$SQL_COL_DEBUT_PROJECT = "DEBUT";
$SQL_COL_FIN_PROJECT = "FIN";
$SQL_COL_FIN_GARANTIE = "FIN_GARANTIE";
$SQL_COL_PRIX_VENTE_PROJECT = "PRIX_VENTE";
$SQL_COL_STATUS_PROJECT = "STATUS";
$SQL_COL_TYPE_PROJECT = "TYPE";
$SQL_COL_GROUPE_PROJECT = "GROUPE";
$SQL_COL_COMMENTAIRE_PROJECT = "COMMENTAIRE";

$SQL_LABEL_PROJECT_NAME = "PROJECT";

$SQL_SHOW_COL_PROJECT = "$SQL_COL_ID_PROJECT, $SQL_COL_NAME_PROJECT, $SQL_COL_PRIX_VENTE_PROJECT, $SQL_COL_DEBUT_PROJECT, $SQL_COL_FIN_PROJECT, $SQL_COL_FIN_GARANTIE";
$SQL_SHOW_ALL_COL_PROJECT = $SQL_SHOW_COL_PROJECT . ", $SQL_COL_STATUS_PROJECT,  $SQL_COL_TYPE_PROJECT, $SQL_COL_GROUPE_PROJECT, $SQL_COL_COMMENTAIRE_PROJECT";

// include_once 'connection_db.php';
// include_once 'tool_db.php';
include_once (dirname(__FILE__) . "/../configuration/labelAction.php");
include_once (dirname(__FILE__) . "/table_db.php");

function getProjectNameFromID($idProject)
{
    global $SQL_COL_ID_PROJECT;
    global $SQL_COL_NAME_PROJECT;
    global $SQL_TABLE_PROJECT;
    
    // search project name
    $condition = createSqlWhere($SQL_COL_ID_PROJECT, $idProject);
    $param = createDefaultParamSql($SQL_TABLE_PROJECT, $SQL_COL_NAME_PROJECT, $condition);
    $requete = $request = createRequeteTableData($param);
    showSQLAction($requete);
    $Resultat = requeteTableData($param);
    $name = mysqlResult($Resultat, 0, $SQL_COL_NAME_PROJECT);
    
    return $name;
}

/**
 * application des actions sur la page one projet
 * 
 * @return number 0 si pas d'action faite
 */
function applyGestionOneProject()
{
    global $TABLE_FORM_NAME_INSERT;
    global $FORM_TABLE_CEGID_PROJECT;
    
    $formName = getURLVariable($TABLE_FORM_NAME_INSERT);
    if ($formName == $FORM_TABLE_CEGID_PROJECT) {
        return applyGestionProject();
    }
    
    return 0;
}

/**
 * showGestionOneProject
 */
function showGestionOneProject()
{
    $idBalise = "gestionon_project";
    createHeaderBaliseDiv($idBalise, "<h3>Infomation projet </h3>");
    {
        global $TRACE_INFO_PROJECT;
        global $SQL_SHOW_ALL_COL_PROJECT;
        $colProject = $SQL_SHOW_ALL_COL_PROJECT;
        global $FORM_TABLE_CEGID_PROJECT;
        global $TABLE_FORM_NAME_INSERT;
        $form_name = $FORM_TABLE_CEGID_PROJECT/*."_one_update"*/;
        $project_found = "yes";
        
        // selection table de stockage si pas definie
        global $SQL_TABLE_PROJECT;
        $tableProject = $SQL_TABLE_PROJECT;
        
        // recuperation project name
        global $PROJECT_SELECTION;
        // global $ITEM_COMBOBOX_SELECTION;
        global $SQL_COL_NAME_PROJECT;
        $projectName = getURLVariable($PROJECT_SELECTION);
        if ($projectName == FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION || $projectName == "") {
            showActionVariable("No project Selected...", $TRACE_INFO_PROJECT);
            $projectName = "no project";
            $project_found = "no";
        }
        if ($projectName == FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_ALL) {
            showActionVariable("All projects Selected...", $TRACE_INFO_PROJECT);
            $projectName = "no project";
            $project_found = "no";
        }
        
        // info formulaire year et project name
        global $YEAR_SELECTION;
        $year = getURLYear();
        $infoForm = streamFormHidden($YEAR_SELECTION, $year);
        $infoForm = $infoForm . streamFormHidden($PROJECT_SELECTION, $projectName);
        $infoForm = $infoForm . streamFormHidden($TABLE_FORM_NAME_INSERT, $form_name);
        
        // $projectId = getURLVariableSQLForm($variables, $form)
        $condition = createSqlWhere($SQL_COL_NAME_PROJECT, $projectName);
        $subParam = "";
        $subParam = updateParamSqlCondition($subParam, $condition);
        $subParam = setInfoForm($subParam, $infoForm);
        if ($project_found == "no") {
            // echo "project not found....<br>";
            $subParam = setSQLFlagStatus($subParam, stringToArray($colProject), "disabled");
        }
        
        editTable($tableProject, $colProject, "", $form_name, $subParam);
        // applyGestionOneProjectTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name);
    }
    endHeaderBaliseDiv($idBalise);
}

/**
 * application des actions sur la page projet
 * not used
 */
function applyGestionOneProjectTable($table, $cols, $form_name)
{
    // trace
    $url = getCurrentURL();
    $action = getActionGet();
    showAction("action : [$action]  $url");
    // end trace
    
    // insertInTableByGet ( $table, $cols, $form_name );
    editTable2($table, $cols, $form_name);
    // updateTableByGet ( $table, $cols, $form_name );
    // exportCSVTableByGet ( $table, $cols, $cols, $form_name );
    // importCSVTableByGet ( $table, $cols, $form_name );
    // // insertOrupdateTableByGet ($table, $cols, $form_name);
    // applyDeleteTableByGet ( $table, $cols, $form_name, "" );
}

/**
 * application des actions sur la page projet
 *
 * @return number 0 nothing 1 action trait√©e
 */
function applyGestionProject()
{
    global $SQL_SHOW_COL_PROJECT;
    global $SQL_SHOW_ALL_COL_PROJECT;
    $colProject = $SQL_SHOW_ALL_COL_PROJECT;
    global $SQL_TABLE_PROJECT;
    global $FORM_TABLE_CEGID_PROJECT;
    $form_name = $FORM_TABLE_CEGID_PROJECT . "_update";
    
    $condition = "";
    $colFilter = NULL;
    $param = createDefaultParamSql($SQL_TABLE_PROJECT, $colProject, $condition);
    $param = updateTableParamSql($param, $form_name, $colFilter);
    
    // traitement du update
    $res = updateTableByGet (/*$SQL_TABLE_PROJECT, $colProject, $form_name,*/ $param, "no")/**
     * re-edit
     */
    ;
    
    // cas classique : edit, export, ...
    if ($res <= 0) {
        $res = applyGestionTable($SQL_TABLE_PROJECT, $colProject, $form_name);
    }
    return $res;
}

/**
 * affiche les versions des elements du projet
 * (description)
 */
function showTableProject()
{
    global $SQL_SHOW_COL_PROJECT;
    global $SQL_TABLE_PROJECT;
    global $FORM_TABLE_CEGID_PROJECT;
    global $TABLE_EXPORT_CSV;
    $form_name = $FORM_TABLE_CEGID_PROJECT . "_insert";
    $condition = "";
    
    // showSQLAction("showTableProject - ...");
    
    // showTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name);
    $param = prepareshowTable($SQL_TABLE_PROJECT, $SQL_SHOW_COL_PROJECT, $form_name, $condition);
    // par defaut on a edit & delete
    
    // ajout export CSV
    $param[$TABLE_EXPORT_CSV] = "yes";
    
    // ajout edit pointage et previsionnel
    global $URL_ROOT_POINTAGE;
    $urlPointage = "$URL_ROOT_POINTAGE/user/one_project_cegid.php";
    $urlPrevision = "$URL_ROOT_POINTAGE/user/pointage_prevision_cegid.php";
    // showSQLAction("showTableProject - addParamActionCommand()");
    $param = addParamActionCommand($param, $urlPointage, "pointage!", LabelAction::ACTION_POINTAGE);
    $param = addParamActionCommand($param, $urlPrevision, "prevision!", LabelAction::ACTION_POINTAGE);
    // showSQLAction("showTableProject - addParamActionCommand() retour");
    
    // showSQLAction("showTableProject - showTableByParam()");
    showTableByParam($param);
}

/**
 * project => projectId
 * update project url if needed
 * @param string $project
 * @return string|data idProject
 */
function projectIDFromURL($project = "")
{
    global $SQL_TABLE_PROJECT;
    global $SQL_COL_ID_PROJECT;
    global $SQL_COL_NAME_PROJECT;
    global $SQL_SHOW_ALL_COL_PROJECT;
    
    $idProject = "";
    
    if ($project == "") {
        $project = getURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION);
    }
    
    if (($project != "") && ($project != FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION) && ($project != FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_ALL)) {
        // est ce un nom
        $condition = createSqlWhere($SQL_COL_NAME_PROJECT, $project);
        $param = createDefaultParamSql($SQL_TABLE_PROJECT, $SQL_SHOW_ALL_COL_PROJECT, $condition);
        $requete = createRequeteTableData($param);
        //showSQLAction($requete);
        $Resultat = requeteTableData($param);
        $idProject = mysqlResult($Resultat, 0, $SQL_COL_ID_PROJECT);
        if ($idProject) {
            setURLVariable($SQL_COL_NAME_PROJECT, $project);
            setURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION, $project);
        } else {
            // pas de nom trouvÈ
            // est ce un id de projet
            $condition = createSqlWhere($SQL_COL_ID_PROJECT, $project);
            $param = createDefaultParamSql($SQL_TABLE_PROJECT, $SQL_SHOW_ALL_COL_PROJECT, $condition);
            $requete = createRequeteTableData($param);
            //showSQLAction($requete);
            $Resultat = requeteTableData($param);
            $projectName = mysqlResult($Resultat, 0, $SQL_COL_NAME_PROJECT);
            if ($projectName) {
                setURLVariable($SQL_COL_NAME_PROJECT, $projectName);
                setURLVariable(FORM_COMBOX_BOX_KEY::PROJECT_SELECTION, $projectName);
                $idProject = $project;
            }
        }
    }
    return $idProject;
}

 function synchoTableIdProject(){
     $idTable = getURLVariable(URL_VARIABLE__KEY::ID_TABLE_GET);
     if ($idTable != ""){
         projectIDFromURL($idTable);
     }
     else {
         $idTable = projectIDFromURL();
         if ($idTable != ""){
             //showSQLAction("id Table : $idTable");
             setURLVariable(URL_VARIABLE__KEY::ID_TABLE_GET, $idTable);
             setURLVariable(LabelAction::ACTION_GET,LabelAction::ActionEdit);
         }
     }
}

?>