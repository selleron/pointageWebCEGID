<?php
include_once (dirname ( __FILE__ ) . "/../configuration/form_db_config.php");

class URL_VARIABLE__KEY {
    const ACTION_GET           = "action";
    const DATE_GET             = "date";
    const ID_TABLE_GET         = "idTable";
    const ANNEE_CALENDRIER_GET = "anneeCalendrier";
    const ID_GET               = "id";
    const DOCUMENT_NAME_GET    = "document";
    const DATE1_GET            = "date1";
    const DATE2_GET            = "date2";
}


$ACTION_GET   = URL_VARIABLE__KEY::ACTION_GET;
$DATE_GET     = URL_VARIABLE__KEY::DATE_GET;
$ID_TABLE_GET = URL_VARIABLE__KEY::ID_TABLE_GET;


// variable par defaut d'une form
class FORM_VARIABLE {
	const ACTION_GET     = "action";
	const DATE_GET       = "date";
	const DATE_DEBUT_GET = "date_debut";
	const DATE_FIN_GET   = "date_fin";
	const ID_TABLE_GET   = "idTable";
}

// valeur par defaut pour les combo box
class FORM_COMBOX_BOX_VALUE {
    const ITEM_COMBOBOX_SELECTION = "[selection]";
    const ITEM_COMBOBOX_ALL       = "[all]";
}

$ITEM_COMBOBOX_SELECTION = FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION;

// sql type
// see $KEY_INFO
class SQL_TYPE {
    const  SQL_INT     = "int";
    const  SQL_BLOB    = "blob";
    const  SQL_DATE    = "date";
    const  SQL_STRING  = "string";
    const  SQL_TEXT    = "text";
    const  SQL_REAL    = "real";
    const  SQL_REQUEST = "request";
}

class SQL_TYPE_CODE {
    const  SQL_INT     = "3";
    const  SQL_BLOB    = "blob";
    const  SQL_DATE    = "10";
    const  SQL_STRING  = "253";
    const  SQL_TEXT    = "252";
    const  SQL_REAL    = "4";
    const  SQL_DOUBLE  = "5";
    const  SQL_REQUEST = "request";
}

/**
 * createForm
 *
 * @param string  $url url        	
 * @param string $form_name id        	
 */
function createForm($url, $form_name = "") {
	echo "<form ";
	echo "method=\"post\" ";
	echo "action=\"$url\" ";
	if (isset ( $form_name )) {
		echo " id=\"$form_name\"";
	}
	echo ">";
	// echo"form url : [$url]";
}

/**
 * end Form
 */
function endForm() {
	echo "</form>";
}

/**
 * showMiniForm
 *
 * @param string $url        	
 * @param string $formname        	
 * @param string $action        	
 * @param string $txt        	
 * @param string $id        	
 * @param boolean $useTD
 *        	"yes"/"non" default "yes"
 */
function showMiniForm($url, $formname, $action, $txt, $id, $useTD = "yes", $infoForm="") {
	if ($useTD == "yes") {
		echo "<td>";
	}
	
	createForm ( $url, $formname );
	echo "$infoForm";
	showFormIDElement ();
	showFormIdTableElement ( $id );
	showFormAction ( $action );
	showFormSubmit ( $txt );
	endForm ();
	
	if ($useTD == "yes") {
		echo "</td>";
	}
}

/**
 * showMiniFormArray
 *
 * @param string $url        	
 * @param string $formname        	
 * @param string $action        	
 * @param string $txt        	
 * @param array $arrayKeys        	
 * @param array $arrayValues        	
 * @param string $useTD        	
 */
function showMiniFormArray($url, $formname, $action, $txt, $arrayKeys, $arrayValues, $useTD = "yes", $infoForm) {
	if ($useTD == "yes") {
		echo "<td>";
	}
	
	createForm ( $url, $formname );
	showFormIDElement ();
	echo "$infoForm";
	
	$nb = count ( $arrayKeys );
	for($r = 0; $r < $nb; $r ++) {
		// showFormTxt( $arrayKeys[$r], $arrayValues[$r] );
		showFormHidden ( $arrayKeys [$r], $arrayValues [$r] );
	}
	showFormAction ( $action );
	showFormSubmit ( $txt );
	endForm ();
	
	if ($useTD == "yes") {
		echo "</td>";
	}
}
function showFormTxt($name, $value) {
	echo "	$name <input type=\"text\" name=\"$name\" value=\"$value\"  \"/>";
}


/**
 * show submit button
 * 
 * @param string $txt button name
 * @param string $action 
 * @param string $inputParameters
 */
function showFormSubmit($txt, $action = "", $inputParameters = "") {
	echo "<input type=\"submit\" ";
	if ($action == "") {
		// nothing to do
	} else {
		echo " name=\"$action\" ";
	}
	echo " value=\"$txt\" $inputParameters >";
}

/**
 * showFormAction
 * Affiche dans un formulaire une entre correspondant a l'action a effectuer
 *
 * @param string $action
 *        	nom de l'action
 */
function showFormAction($action) {
	global $ACTION_GET;
	echo "<INPUT TYPE=\"hidden\"   NAME=\"$ACTION_GET\" VALUE=\"$action\">";
}

/**
 * showFormHidden
 *
 * @param string $name        	
 * @param string $value        	
 */
function showFormHidden($name, $value) {
	$txt = streamFormHidden ( $name, $value );
	// $txt = str_replace("<","[", $xt);
	// $txt = str_replace(">","]", $xt);
	echo "$txt";
}

/**
 * streamFormHidden
 *
 * @param string $name        	
 * @param string $value        	
 * @return string html input
 */
function streamFormHidden($name, $value) {
	$txt = "<INPUT TYPE=\"hidden\"   NAME=\"$name\" VALUE=\"$value\">";
	// echo "streamFormHidden($name, $value) <br>";
	return $txt;
}

function debugStreamFormHidden($name, $value) {
	$txt = "<INPUT TYPE=\"hidden\"   NAME=\"$name\" VALUE=\"$value\">";
	$txt = $txt."INPUT TYPE=\"hidden\"   NAME=\"$name\" VALUE=\"$value\"";
	// echo "streamFormHidden($name, $value) <br>";
	return $txt;
}


// show id user $ID_GET
function showFormIDElement() {
	$id_member = getMemberID ();
	global $ID_GET;
	echo "<INPUT TYPE=\"hidden\"   NAME=\"$ID_GET\" VALUE=\"$id_member\">";
}
function getMemberID() {
	global $ID_GET;
	return getURLVariable ( "$ID_GET" );
}
function showFormIdTableElement($id) {
	global $ID_TABLE_GET;
	echo "<INPUT TYPE=\"hidden\"   NAME=\"$ID_TABLE_GET\" VALUE=\"$id\">";
}

/**
 * hasSqlListValueFormColumn
 *
 * @param string $form
 *        	name
 * @param string $name
 *        	of variable
 * @return boolean result of isset
 */
function hasSqlListValueFormColumn($form, $name) {
	global $FORM_VALUE_POSSIBLE;
	global $SHOW_SQL_CB_SEARCH;
	
	$res = isset ( $FORM_VALUE_POSSIBLE [$form] [$name] );
	
	if ($SHOW_SQL_CB_SEARCH == "yes") {
		echoTD ( "hasSqlListValueFormColumn() -- search -- FORM_VALUE_POSSIBLE[$form] [$name] : $res", "yes" );
	}
	
	return $res;
}

/**
 * getSqlListValueFormColumn
 * search request in $FORM_VALUE_POSSIBLE [$form] [$name]
 *
 * @param string $form
 *        	form name
 * @param string $name
 *        	variable name
 * @return string sql request to use
 */
function getSqlListValueFormColumn($form, $name, $showNoFound = "no") {
	global $FORM_VALUE_POSSIBLE;
	if ($showNoFound == "yes") {
		if (! hasSqlListValueFormColumn ( $form, $name )) {
			echoTD ( "search FORM_VALUE_POSSIBLE[$form] [$name] : no found" );
		}
	}
	return $FORM_VALUE_POSSIBLE [$form] [$name];
}

/**
 * hasSqlDefaultValueFormColumn
 * y a il une requete sql pour peupler une CB
 * search request in $FORM_VALUE_POSSIBLE [$form] [$name]
 *
 * @param string $form
 *        	form name
 * @param string $name
 *        	variable name
 * @return boolean
 */
function hasSqlDefaultValueFormColumn($form, $name) {
	global $FORM_VALUE_DEFAULT;

	global $SHOW_SQL_CB_SEARCH;
	if ($SHOW_SQL_CB_SEARCH=="yes"){
	    showSQLAction("hasSqlDefaultValueFormColumn \$FORM_VALUE_DEFAULT[\"$form\"] [\"$name\"]");
	}
	
	return isset ( $FORM_VALUE_DEFAULT [$form] [$name] );
}

/**
 * hasSqlDefaultValueFormVariable
 *
 * @param string $form        	
 * @param string $name        	
 */
function hasSqlDefaultValueFormVariable($form, $name) {
	global $FORM_VALUE_DEFAULT;
	global $SHOW_SQL_CB_SEARCH;
	if ($SHOW_SQL_CB_SEARCH=="yes"){
	    showSQLAction("hasSqlDefaultValueFormVariable \$FORM_VALUE_DEFAULT[\"$form\"] [\"$name\"] [\"VARIABLE\"]");
	}
	return isset ( $FORM_VALUE_DEFAULT [$form] [$name] ["VARIABLE"] );
}

/**
 * getSqlDefaultValueFormColumn
 * retourne la requete sql pour peupler une CB
 *
 * @param String $form        	
 * @param String $name
 *        	column name
 * @return String sql request
 */
function getSqlDefaultValueFormColumn($form, $name) {
	global $FORM_VALUE_DEFAULT;
	return $FORM_VALUE_DEFAULT [$form] [$name];
}

/**
 *
 * @param string $form        	
 * @param string $name        	
 * @return boolean | object result
 */
function getResultSqlDefaultValueFormColumn($form, $name) {
	$request = getSqlDefaultValueFormColumn ( $form, $name );
	
	if (is_array ( $request )) {
		$res = "";
	} else {
		$resultat = mysqlQuery ( $request );
		// $nbRes = mysqlNumrows ( $Resultat );
		$res = mysqlResult ( $resultat, 0, 0 );
	}
	// echo "default value : [$res]";
	
	return $res;
}

/**
 * showFieldForm1
 *
 * @param string $form     	nom de la forme
 * @param string $name     	nom du champs
 * @param string $type    	type du champs (entier,reel,string, date, ...)
 * @param string $flags        	
 * @param string $showLabel        	
 * @param string $useTD        	
 * @param string $value        	
 * @param string $size        	
 * @param string $statusEdit        	
 */
function showFieldForm1($form, $cpt, $nameNoDimension, $type, $flags, $showLabel = "no", $useTD = "no", $value = "", $size = "", $statusEdit = "", $otherStyle="") {
	global $FORM_VALUE_DEFAULT;
	
	// echo "<td>$nameNoDimension : $type : $statusEdit</td>";
	global $SHOW_FORM_VARIABLE_ATTRIBUT;
	//echo "SHOW_FORM_VARIABLE_ATTRIBUT : $SHOW_FORM_VARIABLE_ATTRIBUT<br>";
	if ($SHOW_FORM_VARIABLE_ATTRIBUT == "yes") {
		//beginTableCell();
		beginTable ();
		beginTableRow ();
		// echoTD("type: $type -- flag: $flags -- name: $name <br>size: $size -- value: $value -- status : $statusEdit");
		echoTD ("$form :: $nameNoDimension  type: [" . $type . "] flags: {" . $flags."}", $useTD );
		if (isNotNUllFlag ( $flags )) {
			echoTD ( "*", $useTD );
		}
		// endTableRow();endTable();endTableCell();
	}
	
	$name = $nameNoDimension;
	if ($cpt > - 1) {
		// le nom du champ est suffixe par sa position
		$name = "$nameNoDimension" . "[" . "$cpt" . "]";
	}
	
	// creation valeur par defaut si n'existe pas
	// via elt url
	if ((strlen ( $value ) == 0) && hasSqlDefaultValueFormVariable ( $form, $nameNoDimension )) {
	    //echoTD("default value found ....");
		global $SHOW_FORM_SUBSTITUTE_SEARCH;
		$traceValue = getURLVariable ( $SHOW_FORM_SUBSTITUTE_SEARCH );
		$value = getURLVariableSQLForm ( $nameNoDimension, $form, $FORM_VALUE_DEFAULT, $traceValue );
        //echoTD("default found : { $value }");
	}
	
	// creation valeur par defaut si n'existe pas
	// via sql
	if ((strlen ( $value ) == 0) && hasSqlDefaultValueFormColumn ( $form, $nameNoDimension )) {
		$value = getResultSqlDefaultValueFormColumn ( $form, $nameNoDimension );
	}
	
	// traitement si detection list de choix
	// via sql
	if (hasSqlListValueFormColumn ( $form, $nameNoDimension )) {
		$Request = getSqlListValueFormColumn ( $form, $nameNoDimension );
		showFormComboBoxSql ( $form, $name, $Request, 0/*sql_col*/, $useTD, $value/*current selection*/, $statusEdit );
// 	} else if (isForeignerKeyFlag ( $flags ) && isPrimaryKeyFlag ( $flags ) == false) {
// 		// traitement des cles etrangeres => combo box
// 		// aurait du etre traite au dessus
// 		echoTD("foreigner key & not primary key detected. not CB detected : not found FORM_VALUE_POSSIBLE[$form][$name]");
	} else if (isAutoIncrementFlag ( $flags ) && isPrimaryKeyFlag ( $flags )) {
		// traitement primary key with auto increment
		showFormPrimary ( $form, $name, $showLabel, $useTD, $value );
		// echoTD ( "[auto.]", $useTD );
	} else if ($type == SQL_TYPE::SQL_STRING) {
	    // traitement des type classique
	    showFormTextElementForVariable ( $form, $name, $showLabel, $useTD, $value, $type, $size, $statusEdit, $otherStyle );
	} else if ($type == SQL_TYPE::SQL_TEXT) {
	    // traitement des type classique
	    showFormAreaElementForVariable ( $form, $name, $showLabel, $useTD, $value, $type, $size, $statusEdit, $otherStyle );
	} else if ($type == SQL_TYPE::SQL_BLOB) {
		showFormTextElementForVariable ( $form, $name, $showLabel, $useTD, $value, $type, $size, $statusEdit, $otherStyle );
	} else if ($type == SQL_TYPE::SQL_REAL) {
		showFormRealElementForVariable ( $form, $name, $showLabel, $useTD, $value, $size, $statusEdit, $otherStyle );
	} else if ($type == SQL_TYPE::SQL_INT) {
		// if (isAutoIncrementFlag ( $flags ) && isPrimaryKeyFlag ( $flags )) {
		// showFormPrimaryINT( $form, $name, $showLabel, $useTD, $value );
		// //echoTD ( "[auto.]", $useTD );
		// } else {
		// echo "size : $size";
		showFormINTElementForVariable ( $form, $name, $showLabel, $useTD, $value, $size, $statusEdit );
		// }
	} else if ($type == SQL_TYPE::SQL_DATE) {
	    showFormDateElementForVariable ( $form, $name, $showLabel, $useTD, $value, $size, $statusEdit, $otherStyle );
	} else if ($type == SQL_TYPE::SQL_REQUEST) {
	    showFormTextElementForVariable ( $form, $name, $showLabel, $useTD, $value, $type, $size, $statusEdit, $otherStyle );
	} else {
	    echoTD ( "??".$name . " type : [" . $type . "] flags : " . $flags, $useTD );
	}
	
	// edn trace
	global $SHOW_FORM_SUBSTITUTE_SEARCH;
	if ($SHOW_FORM_SUBSTITUTE_SEARCH == "yes") {
		endTableRow ();
		endTable ();
		//endTableCell();
	}
}

/**
 * showFormPrimary
 *
 * @param string $formName        	
 * @param string $name        	
 * @param string $showType        	
 * @param string $useTD        	
 * @param string $value        	
 * @param string $type        	
 */
function showFormPrimary($formName = "form", $name, $showType = "yes", $useTD = "yes", $value = "", $type = "") {
	if (strlen ( $value ) > 0) {
		// showFormINTElementForVariable ( $formName, $name, $showType, $useTD, $value );
		showFormHidden ( $name, $value );
		echoTD ( "$value", $useTD );
	} else {
		echoTD ( "[auto.]", $useTD );
	}
}

/**
 * isAutoIncrementFlag
 *
 * @param string $flag      	flag SQL
 * @return boolean
 */
function isAutoIncrementFlag($flags) {
	return strpos ( $flags, "auto_increment" ) !== false;
}
function isForeignerKeyFlag($flags) {
	return strpos ( $flags, "multiple_key" ) !== false;
}

/**
 * le chane est il obligatoire
 *
 * @param string $flags        	
 * @return boolean
 */
function isNotNUllFlag($flags) {
	return strpos ( $flags, "not_null" ) !== false;
}

/**
 * isPrimaryKeyFlag
 *
 * @param string $flag
 *        	flag SQL
 * @return boolean
 */
function isPrimaryKeyFlag($flags) {
	return strpos ( $flags, "primary_key" ) !== false;
}

/**
 * show element get date use $DATE_GET
 * affichage :
 * - label date (aaa/mm/jj)
 * - texte field
 * - bouton calendrier
 *
 * @param string $formName nom	de la form
 * @param $showLabel yes/no     	default yes
 */
function showFormDateElement($formName = "form", $showLabel = "yes") {
	global $DATE_GET;
	showFormDateElementForVariable ( $formName, $DATE_GET, $showLabel );
}

/**
 * show element get date use $DATE_GET
 * affichage :
 * - label date (aaa/mm/jj)
 * - texte field
 * - bouton calendrier
 *
 * @param string$formName nom    	de la form
 * @param string $DATE_GET la	variable de stockage
 * @param string $showLabel yes/no        	
 * @param string $useTD yes/no       	default yes
 * @param string $date      	date par defaut
 */
function showFormDateElementForVariable($formName = "form", $DATE_GET, 
		$showLabel = "yes", $useTD = "yes", $date = "", $size = "", $statusEdit = "enabled", $label="date") {
	// $date = getURLVariable($DATE_GET);
	global $URL_IMAGES;
	if ($useTD == "yes") {
		echo "<td>";
	}
	if ($showLabel == "yes") {
		echo "$label (aaa/mm/jj) ";
	}
	
	$statusEdit = prepareFlagStatus ( $statusEdit );
	if ($size == "") {
		$size = 15;
	}
	
	echo "
	<input $statusEdit type=\"text\" name=\"$DATE_GET\" value=\"$date\" size=\"$size\" onclick=\"ds_sh(this);\" >
	<img id=\"calendar\" title=\"calendar\" border=\"0\" src=\"$URL_IMAGES/calendrier.png\"
	onclick=\"ds_sh(document.$formName.$DATE_GET);\"/>";
	
	if ($useTD == "yes") {
		echo "</td>";
	}
}

/**
 * show element get float / double
 *
 * @param string $formName nom
 *        	de la form
 * @param string $name la	variable de stockage
 * @param string $showLabel yes/no        	
 * @param string $useTD yes/no        	default yes
 * @param string$value default     	value
 */
function showFormRealElementForVariable($formName = "form", $name, $showLabel = "yes", $useTD = "yes", $value = "", $size = "", $statusEdit, $other) {
	showFormTextElementForVariable ( $formName, $name, $showLabel, $useTD, $value, "", $size, $statusEdit , $other);
}

/**
 * showFormINTElementForVariable
 *
 * @param string $formName        	
 * @param string $name        	
 * @param string $showLabel        	
 * @param string $useTD        	
 * @param string $value        	
 */
function showFormINTElementForVariable($formName = "form", $name, $showLabel = "yes", $useTD = "yes", $value = "", $size = "", $statusEdit) {
	showFormTextElementForVariable ( $formName, $name, $showLabel, $useTD, $value, "", $size, $statusEdit );
}

/**
 * show element get txt
 *
 *
 * @param string $formName
 *        	nom de la form
 * @param string $name
 *        	nom de la variable
 * @param string $showType
 *        	affiche le type (debug) yes/no
 * @param string $useTD
 *        	utilise le tag <td> yes/no
 * @param string $value
 *        	valeur par defaut
 * @param string $type
 *        	precise le type
 * @param number $size
 *        	taille du champ
 * @param string $enabledStatus
 *        	status du champ yes/no/enabled/disabled
 * @param string $otherCondition
 *        	autre condition du champs
 */
function showFormTextElementForVariable($formName = "form", $name, $showType = "yes", $useTD = "yes", $value = "", $type = "", $size = "", $enabledStatus = "", $otherCondition = "") {
	if ($formName == "")
		$formName = "form";
	if ($name == "")
		$name = "no_name";
	if ($showType == "")
		$showType = "yes";
	if ($useTD == "")
		$useTD = "yes";
		// if ( $value == "" ) $value = "";
		// if ( $type == "" ) $type = "";
		// if ( $size == "" ) $size = "";
	$enabledStatus = prepareFlagStatus ( $enabledStatus );
	// if ( $otherCondition == "" ) $otherCondition = "";
	
	$date = getURLVariable ( $name );
	$size2 = getFormStyleSizeField2("", $formName, $name);
	if ($size2 != ""){
	    $size2 = "width='$size2'";
	}
	if ($useTD == "yes") {
		echo "<td $size2>";
	}
	if ($showType == "yes") {
		if ($type) {
			echo $type;
		} else {
			echo "string ";
		}
	}
	
	$td = getFormStyleTD2("", $formName, $name);
	$size = getFormStyleSize2($size, $formName, $name);
	
	if ($size == "") {
		$sizeTxt = "";
	} else {
		$sizeTxt = "size=\"$size\"";
	}
	echo "	<input $enabledStatus $td id=\"$name\" type=\"text\" name=\"$name\" value=\"$value\" $sizeTxt  $otherCondition />";
	
	
	$suffix = getFormStyleSuffix2("", $formName, $name);
	$suffix2 = getFormStyleSuffixField2($suffix, $formName, $name);
	
	global  $SHOW_AS_COMMENT_FORM_VARIABLE_STYLE;
	    
	if ($SHOW_AS_COMMENT_FORM_VARIABLE_STYLE=="yes"){
	   echoComment("showFormTextElementForVariable - found suffix : $suffix" );
	   echoComment("showFormTextElementForVariable - found suffix : $suffix2" );
	}
	echo "$suffix2";
	
	if ($useTD == "yes") {
		echo "</td>";
	}
}

/**
 * showFormAreaElementForVariable
 * use test area
 * @param string $formName
 * @param string $name
 * @param string $showType
 * @param string $useTD
 * @param string $value
 * @param string $type
 * @param string $size
 * @param string $enabledStatus
 * @param string $otherCondition
 */
function showFormAreaElementForVariable($formName = "form", $name, $showType = "yes", $useTD = "yes", $value = "", $type = "", $size = "", $enabledStatus = "", $otherCondition = "")
{
    if ($formName == "") {
        $formName = "form";
    }
    if ($name == "") {
        $name = "no_name";
    }
    if ($showType == "") {
        $showType = "yes";
    }
    if ($useTD == "") {
        $useTD = "yes";
    }
    // if ( $value == "" ) $value = "";
    // if ( $type == "" ) $type = "";
    // if ( $size == "" ) $size = "";
    $enabledStatus = prepareFlagStatus($enabledStatus);
    // if ( $otherCondition == "" ) $otherCondition = "";
    
    $date = getURLVariable($name);
    if ($useTD == "yes") {
        echo "<td>";
    }
    if ($showType == "yes") {
        if ($type) {
            echo $type;
        } else {
            echo "text area ";
        }
    }
//     if ($size == "") {
//         $sizeTxt = "";
//     } else {
//         $sizeTxt = "size=\"$size\"";
//     }
    echo "<textarea  rows=3 cols=50 $enabledStatus id=\"$name\" type=\"text\" name=\"$name\">";
    echo "$value";
    //$otherCondition />";
    echo "</textarea>";
    
    if ($useTD == "yes") {
        echo "</td>";
    }
}


/**
 * prepare le formatage enable disable pour un imput
 *
 * @param string $enabledStatus        	
 * @return string
 */
function prepareFlagStatus($enabledStatus = "enabled") {
	if ($enabledStatus == "no")
		$enabledStatus = "disabled";
	if ($enabledStatus == "enabled")
		$enabledStatus = "";
	if ($enabledStatus == "yes")
		$enabledStatus = "";
	
	return $enabledStatus;
}

/**
 * showComboBox
 * use showFormComboBoxSql()
 *
 * @param string $formName
 *        	html form name
 * @param string $name
 *        	variable form name
 * @param string $sql_table
 *        	sql table name
 * @param string $sql_col
 *        	sql column name
 * @param string $useTD use     	<td> "yes/no"
 * @param string $current_selection
 *        	current selection in combobox
 */
function showFormComboBox($formName, $name, $sql_table, $sql_col, $useTD, $current_selection) {
	// $document = getDocumentName ();
	$Request = "SELECT $sql_col FROM $sql_table";
	showFormComboBoxSql ( $formName, $name, $Request, $sql_col, $useTD, $current_selection );
}

/**
 *
 * @param string $formName
 *        	html form name
 * @param string $name
 *        	variable form name
 * @param string $Request
 *        	sql request to create combo box list
 * @param string $sql_col
 *        	sql column name
 * @param
 *        	string boolean $useTD use <td> "yes/no"
 * @param string $current_selection
 *        	current selection in combobox
 */
function showFormComboBoxSql($formName, $name, $Request, $sql_col, $useTD, $current_selection, $enabledStatus = "enabled") {
	//global $ITEM_COMBOBOX_SELECTION;
	
	$enabledStatus = prepareFlagStatus ( $enabledStatus );
	$current_selection_found="no";
	
	// showSQLAction ( $Request );
	$Resultat = mysqlQuery ( $Request );
	$nbRes = mysqlNumrows ( $Resultat );
	
	if ($useTD == "yes") {
		echo "<td>";
	}
	echo "<SELECT $enabledStatus name=\"$name\"  onchange=\"this.submit()\" >";
	echo "<OPTION> ".FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION;
	if (FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION == $current_selection) {
	    $current_selection_found="yes";
	}
	 for($cpt = 0; $cpt < $nbRes; $cpt ++) {
		$res = mysqlResult ( $Resultat, $cpt, $sql_col );
		if ($res == $current_selection) {
			$selected = "selected";
			$current_selection_found="yes";
		} else {
			$selected = "";
		}
		echo "<OPTION $selected>$res";
	}
	
 	if ($current_selection_found=="no" && $current_selection){
 	    //permet de traiter le cas des éléments non present dans la liste (archivés???)
 	    echo "<OPTION selected>$current_selection";
 	}
	
	echo "</SELECT>";
	if ($useTD == "yes") {
		echo "</td>";
	}
}

/**
 * showFormComboBoxSql2 combobox avec completion
 * @param string $formName
 * @param string $name
 * @param string $Request
 * @param string $sql_col
 * @param string $useTD  "yes"/"no"
 * @param string $current_selection
 * @param string $enabledStatus
 */
function showFormComboBoxCompletionSql($formName, $name, $Request, $sql_col, $useTD, $current_selection, $enabledStatus = "enabled") {
    //global $ITEM_COMBOBOX_SELECTION;
    
    $enabledStatus = prepareFlagStatus ( $enabledStatus );
    
    global $SHOW_COMPLETION_REQUEST;
    showActionVariable( $Request, $SHOW_COMPLETION_REQUEST);
    $Resultat = mysqlQuery ( $Request );
    $nbRes = mysqlNumrows ( $Resultat );
    
    if ($useTD == "yes") {
        echo "<td>";
    }
    //input option :  placeholder="un texte"
    if ($current_selection==""){
        $current_selection=FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION;
    }
    echo "<input $enabledStatus type=\"text\" list=\"$name\" name=\"$name\" value=\"$current_selection\"  >";
    echo "<datalist $enabledStatus id=\"$name\"  >";
    echo "<OPTION value='".FORM_COMBOX_BOX_VALUE::ITEM_COMBOBOX_SELECTION."' > </OPTION>";
    for($cpt = 0; $cpt < $nbRes; $cpt ++) {
        $res = mysqlResult ( $Resultat, $cpt, $sql_col );
        echo "<OPTION value='$res'> </OPTION>";
    }
    echo "</datalist>";
    echo "</input>";
    if ($useTD == "yes") {
        echo "</td>";
    }
    
    
}


?>