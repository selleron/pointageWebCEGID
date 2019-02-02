<?PHP
$PROINTAGE_DB_PHP = "loaded";

include_once (dirname(__FILE__) . "/devis_db.php");
include_once 'table_db.php';
include_once 'project_db.php';
include_once 'time.php';
include_once (dirname(__FILE__) . "/../configuration/labelAction.php");
include_once 'pointage_voulu_cegid_db.php';
include_once 'form_project_db.php';


/**
 * showTableSelection
 * 
 * @param string $url
 * @param string $table
 * @param string $colName
 * @param string $formName
 * @param string $yearVisible       "yes"/"no"
 * @param string $export            "yes"/"no"
 * @param string $userVisible       "yes"/"no"
 * @param string $previousVisible   "yes"/"no"
 * @param string $nextVisible       "yes"/"no"
 */
function showTableSelection($url = "", $table, $colName="NAME", $formName = "", $yearVisible = "yes", $export = "no", $userVisible = "no", $previousVisible = "no", $nextVisible = "no")
{
    global $FORM_VALUE_POSSIBLE;
    
    $SQL_COL_NAME_TABLE=$colName;
    
    if ($formName == "") {
        $formName = "form_select_table_$table";
    }
    
    global $TRACE_FORM_TABLE_STYLE;
    showActionVariable("form table selection : $formName", $TRACE_FORM_TABLE_STYLE);
    
    if (! $url) {
        $url = currentPageURL();
        // echo $html."<br>";
    }
    
    //$TABLE_ID_SELECTION = URL_VARIABLE__KEY::ID_TABLE_GET;
    $TABLE_ELT_SELECTION  = $SQL_COL_NAME_TABLE;
    $YEAR_SELECTION = FORM_COMBOX_BOX_KEY::YEAR_SELECTION;
    $USER_SELECTION = FORM_COMBOX_BOX_KEY::USER_SELECTION;
    
    $current_selection_projet = getURLVariable($TABLE_ELT_SELECTION);
    $current_selection_year = getURLVariable($YEAR_SELECTION);
    $current_selection_user = getURLVariable($USER_SELECTION);
    
    echo "<table>";
    beginTableHeader();
    echo "<td>$table</td>";
    if ($yearVisible == "yes") {
        echo "<td>year</td>";
    }
    if ($userVisible == "yes") {
        echo "<td>user</td>";
    }
    // echo "<td>actions</td>";
    endTableHeader();
    
    echo "<tr>";
    // combo project
    createForm($url, $formName);
    
    
    if (!isset($FORM_VALUE_POSSIBLE[$formName][$SQL_COL_NAME_TABLE])){
        showError("showTableSelection() not found : $"."FORM_VALUE_POSSIBLE"."["."\"$formName\""."]["."\"$SQL_COL_NAME_TABLE\"]");        
    }
            
    //si une requet existe, on l'utilise (certaines ajoute "[all])"
    global $PROJECT_AUTO_COMPLETION;
    if (isset($FORM_VALUE_POSSIBLE[$formName][$SQL_COL_NAME_TABLE])){
        if ($PROJECT_AUTO_COMPLETION=="yes"){
            showFormComboBoxCompletionSql($formName, $TABLE_ELT_SELECTION, $FORM_VALUE_POSSIBLE[$formName][$SQL_COL_NAME_TABLE], $SQL_COL_NAME_TABLE, "yes", $current_selection_projet);
        }
        else{
            showFormComboBoxSql($formName, $TABLE_ELT_SELECTION, $FORM_VALUE_POSSIBLE[$formName][$SQL_COL_NAME_TABLE], $SQL_COL_NAME_TABLE, "yes", $current_selection_projet);
        }
    }
    else{
        if ($PROJECT_AUTO_COMPLETION=="yes"){
            showFormComboBoxCompletionSql($formName, $TABLE_ELT_SELECTION, $table, $SQL_COL_NAME_TABLE, "yes", $current_selection_projet);
        }
        else{
            showFormComboBoxSql($formName, $TABLE_ELT_SELECTION, $table, $SQL_COL_NAME_TABLE, "yes", $current_selection_projet);
        }
    }
    
    // combo year
    if ($yearVisible == "yes") {
        $request = getSqlListValueFormColumn($formName, $YEAR_SELECTION);
        showFormComboBoxSql($formName, $YEAR_SELECTION, $request, 0, "yes", $current_selection_year);
    }
    // showFormComboBox($formName, $YEAR_SELECTION, $SQL_TABLE_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, "no", $current_selection_year);
    // combo year
    if ($userVisible == "yes") {
        $request = getSqlListValueFormColumn($formName, $USER_SELECTION, "yes");
        // echoTD("request : $request");
        showFormComboBoxSql($formName, $USER_SELECTION, $request, 0, "yes", $current_selection_user);
    }
    // showFormComboBox($formName, $YEAR_SELECTION, $SQL_TABLE_CEGID_POINTAGE, $SQL_COL_DATE_CEGID_POINTAGE, "no", $current_selection_year);
    echo "<td>";
    showFormIDElement();
    
    global $ACTION_GET;
    
    echoSpace();
    // echo " &nbsp;";
    // showFormSubmit ( "select" );
    showFormSubmit(LabelAction::ActionSelect, $ACTION_GET);
    if ($export == "yes") {
        echoSpace();
        // echo " &nbsp;";
        // showFormSubmit ( LabelAction::ActionExport, "exportCSVSelect" );
        showFormSubmit(LabelAction::ActionExport, "$ACTION_GET");
    }
    else if ($export == "no" || $export == "" || $export == NULL) {
        //no export
    }
    else{
        $arrayAction = stringToArray($export);
        foreach ($arrayAction as $itemAction){
            echoSpace();
            $item = stringToArray($itemAction,";");
            if (isset($item[1])){
                showFormSubmit($item[0], "$ACTION_GET", $item[1]);
            }
            else{
                showFormSubmit($item[0], "$ACTION_GET");
            }
        }
    }
    if ($previousVisible == "yes") {
        echoSpace();
        // echo " &nbsp;";
        // showFormSubmit ( "previous >>", "$ACTION_GET" );
        showFormSubmit(LabelAction::ACTION_PREVIOUS, "$ACTION_GET");
    }
    if ($nextVisible == "yes") {
        echoSpace();
        // echo " &nbsp;";
        // showFormSubmit ( "next >>", "$ACTION_GET" );
        showFormSubmit(LabelAction::ACTION_NEXT, "$ACTION_GET");
    }
    echo "</td>";
    endForm();
    
    echo "</tr>";
    echo "</table>";
}




function getNameFromID_Table($SQL_TABLE, $SQL_COL_ID, $id, $SQL_COL_NAME ){
    global $TRACE_NEXT_PREVIOUS;
    // search  name
    $condition = createSqlWhere($SQL_COL_ID, $id);
    $param = createDefaultParamSql($SQL_TABLE, $SQL_COL_NAME, $condition);
    $requete = $request = createRequeteTableData($param);
    showActionVariable($requete, $TRACE_NEXT_PREVIOUS );
    $Resultat = requeteTableData($param);
    $name = mysqlResult($Resultat, 0, $SQL_COL_NAME);
    
    return $name;
}

function getIDFromName_Table($SQL_TABLE, $SQL_COL_NAME, $name, $SQL_COL_ID ){
    global $TRACE_NEXT_PREVIOUS;
    // search id
    $condition = createSqlWhere($SQL_COL_NAME, $name);
    $param = createDefaultParamSql($SQL_TABLE, $SQL_COL_ID, $condition);
    $requete = $request = createRequeteTableData($param);
    showActionVariable($requete, $TRACE_NEXT_PREVIOUS );
    $Resultat = requeteTableData($param);
    $id = mysqlResult($Resultat, 0, $SQL_COL_ID);
    
    return $id;
}


/**
 * convert URL Variable ID to Name If Needed
 */
function convertURLVariableID_to_NAME_IfNeeded($table, $SQL_COL_ID="ID", $SQL_COL_NAME_TABLE="NAME"){
    $TABLE_ID_SELECTION = URL_VARIABLE__KEY::ID_TABLE_GET;
    
    global $TRACE_NEXT_PREVIOUS;
    
    //init si besoin le nom a partir de l'id
    $id = getURLVariable("$TABLE_ID_SELECTION");
    $projectName = getURLVariable("$SQL_COL_NAME_TABLE");
    
    if ($id != "" && $projectName == ""){
        showActionVariable("initialisation name $SQL_COL_NAME_TABLE from id $SQL_COL_ID : $id",$TRACE_NEXT_PREVIOUS);
        $projectName = getNameFromID_Table($table, $SQL_COL_ID, $id, $SQL_COL_NAME_TABLE);
        setURLVariable($SQL_COL_NAME_TABLE, $projectName);
    }
    
    //init name si besoin
    if ($id == "" && $projectName){
        $id = getNameFromID_Table($table, $SQL_COL_NAME_TABLE, $projectName, $SQL_COL_ID);
        if ($id == ""){
            $tmpName = getNameFromID_Table($table, $SQL_COL_ID, $projectName, $SQL_COL_NAME_TABLE);
            //recherche s'il y a inversion name & id
            if ($tmpName != ""){
               $id = $projectName;
               $projectName = $tmpName;
            }
        }
        showActionVariable("initialisation id $TABLE_ID_SELECTION from name $SQL_COL_NAME_TABLE : $TABLE_ID_SELECTION =  $projectName",$TRACE_NEXT_PREVIOUS );
        setURLVariable($TABLE_ID_SELECTION, $id);
        setURLVariable($SQL_COL_NAME_TABLE, $projectName);
    }

    showTracePOST();
}


function applyNextPreviousSelectTable($table, $SQL_COL_ID="ID", $SQL_COL_NAME_TABLE="NAME" , $condition="")
{
    global $TRACE_NEXT_PREVIOUS;
    $TABLE_ID_SELECTION = URL_VARIABLE__KEY::ID_TABLE_GET;
    
    $col = "";
    
    //init si besoin id et name
    convertURLVariableID_to_NAME_IfNeeded($table, $SQL_COL_ID, $SQL_COL_NAME_TABLE);
    
    //traitement action next et previous
    if ((getActionGet() == LabelAction::ACTION_PREVIOUS) || (getActionGet() == LabelAction::ACTION_NEXT)) {
        // showSQLAction("action previous demanded");
        
        
        $param = createDefaultParamSql($table, $col, $condition);
        if (getActionGet() == LabelAction::ACTION_PREVIOUS) {
            $param[ORDER_ENUM::ORDER_GET] = $SQL_COL_ID;
            $param[ORDER_ENUM::ORDER_DIRECTION] = ORDER_ENUM::ORDER_DIRECTION_DESC;
        }
        
        $nextName="";
        $nextTableID="";
        $currentProject = getURLVariable($SQL_COL_NAME_TABLE);
        
        $sql = createRequeteTableData($param);
        showActionVariable("next/previous for  [ \"$currentProject\" ] : $sql", $TRACE_NEXT_PREVIOUS);
        $Resultat = requeteTableData($param);
        $nbRes = mysqlNumrows($Resultat);
        
        if ($nbRes > 0 && $currentProject == FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION) {
            $nextName = mysqlResult($Resultat, 0, $SQL_COL_NAME_TABLE);
        }
        if ($nbRes > 0 && $currentProject == "") {
            $nextName = mysqlResult($Resultat, 0, $SQL_COL_NAME_TABLE);
        }
        
        if ($nextName == "") {
            for ($cpt = 0; $cpt < ($nbRes - 1); $cpt ++) {
                $name = mysqlResult($Resultat, $cpt, $SQL_COL_NAME_TABLE);
                if ($name == $currentProject) {
                    $nextName = mysqlResult($Resultat, ($cpt + 1), $SQL_COL_NAME_TABLE);
                    $nextTableID = mysqlResult($Resultat, ($cpt + 1), $SQL_COL_ID);
                    showActionVariable("next/previous compare ok : $currentProject => next : nextTableID / $nextName ", $TRACE_NEXT_PREVIOUS);
                }
            }
        }
        
        if ($nextName == "") {
            // nothing to do
        } else {
            setURLVariable($TABLE_ID_SELECTION, $nextTableID);
            setURLVariable($SQL_COL_NAME_TABLE, $nextName);
            // showSQLAction("Project Selection : $nextName");
        }
        if ($TRACE_NEXT_PREVIOUS == "yes")     showTracePOST();
        
        return 1;
        // return exportCSVArrayGestionPointageProjetCegid ();
    } else {
        return - 1;
    }
}









?>