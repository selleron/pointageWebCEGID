<?PHP

// fonctionnalites de base ne dependant de personne
// - gestion d'adresses
// - gestion de traces
//

// echo "include tool_db.php<br>";
$TOOL_DB_PHP = "loaded";

if (! isset ( $USER_CONSULTATION_DB_PHP )) {
	include_once (dirname ( __FILE__ ) . "/user_consultation_db.php");
}
include_once (dirname ( __FILE__ ) . "/basic.php");
include_once (dirname ( __FILE__ ) . "/form_db.php");
include_once (dirname ( __FILE__ ) . "/sql_db.php");
include_once(dirname ( __FILE__ ) . "/param_table_db.php");

// gestion des nom de parametres dans la commande html (post et get)
$ANNEE_CALENDRIER_GET = "anneeCalendrier";
$ID_GET = "id";
$DOCUMENT_NAME_GET = "document";
$DATE1_GET = "date1";
$DATE2_GET = "date2";

// see param_table_db.php
// for param requete table
include_once (dirname ( __FILE__ ) . "/param_table_db.php");

// Gestion de la plateforme
$COOKIE_PLATEFORM_KEY = "plateform";
$COOKIE_PLATEFORM_VALUE_CURRENT = "";
$COOKIE_PLATEFORM_VALUE_PC = "pc";
$COOKIE_PLATEFORM_VALUE_TABLETTE = "tablette";
$COOKIE_PLATEFORM_VALUE_MOBILE = "mobile";

// ///////////////////// html //////////////////////////////////////////
function myHeader($string, $replace = null, $http_response_code = null) {
	return header ( $string, $replace, $http_response_code );
}

/**
 * getMyCookie
 *
 * @param string $name
 *        	name of cookie
 * @param string $defaultValue
 *        	default value for cookie
 *        	
 * @return string value of cookie
 */
function getMyCookie($name, $defaultValue = "") {
	if (isset ( $_COOKIE [$name] )) {
		return $_COOKIE [$name];
	}
	return $defaultValue;
}

/**
 * setMyCookie
 * ecrit un cookie sur le disque
 * doit etre appel� avant tout ecriture de la page
 *
 * @param unknown $name
 *        	cookie name
 * @param string $value
 *        	value of cookie
 * @param int $days
 *        	number of validity days
 */
function setMyCookie($name, $value = "", $days = 10) {
	$expire = time () + $days * 24 * 60 * 60;
	setcookie ( $name, $value, expire );
}

// //////////////// arguments ////////////////////////////////////////

/**
 * getURLVariable
 * retourne la variable stockee dans GETou POST de l'adresse url
 *
 * @param
 *        	$variable
 */
function getURLVariable($variable) {
	$id = "";
	if (isset ( $_GET ["$variable"] )) {
		$id = $_GET ["$variable"];
	}
	if ($id == "") {
		if (isset ( $_POST ["$variable"] )) {
			$id = $_POST ["$variable"];
		}
	}
	
	global $SUPPRESS_SLASH_QUOTE_URL_VARIABLE;
	if (isset ( $SUPPRESS_SLASH_QUOTE_URL_VARIABLE ) && $SUPPRESS_SLASH_QUOTE_URL_VARIABLE == "yes") {
		$id = str_replace ( '\"', '"', $id );
	}
	return $id;
}

/**
 * setURLVariable
 *
 * @param unknown $variable        	
 * @param unknown $value        	
 */
function setURLVariable($variable, $value) {
	if (isset ( $_GET ["$variable"] )) {
		$_GET ["$variable"] = $value;
	} else if (isset ( $_POST ["$variable"] )) {
		$_POST ["$variable"] = $value;
	} else {
		$_GET ["$variable"] = $value;
	}
}

/**
 * getURLVariableArray
 *
 * @param array $variables        	
 * @return array of String
 */
function getURLVariableArray($variables, $idx) {
	$result;
	$i = 0;
	foreach ( $variables as $v ) {
		$val = getURLVariable ( $v );
		// cas d'un tableau
		if (isset ( $idx ) && is_array ( $val )) {
			if (count ( $val ) <= $idx) {
				echo "idx $idx ";
				var_dump ( $val );
			}
			$result [$i] = $val [$idx];
		} else {
			// ici else cas unique
			if (is_array ( $val )) {
				// valeur dans un tableau a un elt
				$result [$i] = $val [0];
			} else {
				$result [$i] = $val;
			}
		}
		$i ++;
	}
	return $result;
}

/**
 * getURLVariableArraySQLForm
 *
 * @param
 *        	string array $variables
 * @param string $form
 *        	form name
 * @return string array of variables values
 */
function getURLVariableArraySQLForm($variables, $form) {
	$result;
	$i = 0;
	foreach ( $variables as $v ) {
		$result [$i] = getURLVariableSQLForm ( $v, $form );
		$i ++;
	}
	return $result;
}

/**
 * getURLVariableSQLForm
 *
 * @param string $variable
 *        	variable name
 * @param unknown $form
 *        	form d'attache de la variable
 * @param string $tableau
 *        	tableau des valeurs (on utilisepas le post)
 * @param string $status
 *        	quiet / verbose
 * @return string|unknown
 */
function getURLVariableSQLForm($variable, $form, $tableau = "", $status = "verbose") {
	if ($status == "") {
		global $SHOW_VARIBLE_SUBSTITUTE_SEARCH;
		$status = getURLVariable ( $$SHOW_VARIBLE_SUBSTITUTE_SEARCH );
	}
	if ($tableau == "") {
		global $FORM_VALUE_INSERT;
		$tableau = $FORM_VALUE_INSERT;
	}
	
	$result = getURLVariable ( $variable );
	if (is_array ( $result )) {
		$result = $result [0];
	}
	if ($result == "") {
		// echo "search variable : [$variable] <br>";
		// search variable
		if (isset ( $tableau [$form] [$variable] ["VARIABLE"] )) {
			$foreignVariable = $tableau [$form] [$variable] ["VARIABLE"];
			// echo "fv : $foreignVariable";
			$replace = getURLVariableSQLForm ( $foreignVariable, $form, $tableau, $status, $searchInURL );
			if (isset ( $tableau [$form] [$variable] ["SQL"] )) {
				$sql = $tableau [$form] [$variable] ["SQL"];
				// echo ">>>> $sql <br>";
				$sql = str_replace ( "???", $replace, $sql );
				// echo "<<<<<< $sql <br>";
				$Resultat = mysqlQuery ( $sql );
				showSQLError ( "" );
				if ($status == "verbose") {
					$result = mysqlResult ( $Resultat, 0, 0 );
				} else {
					if (mysqlNumRows ( $Resultat ) > 0) {
						$result = mysqlResult ( $Resultat, 0, 0 );
					} else {
						$result = "";
					}
				}
			} else {
				if ($status == "verbose") {
					echo "variable $variable not found in url parameters for form [$form] ;;;;<br>";
					echo "configuration not found : \$" . "FORM_VALUE_INSERT [" . $form . "][" . $variable . "][SQL]<br>";
				}
			}
		} else {
			if ($status == "verbose") {
				echo getBeginActionMessage ();
				echo "getURLVariableSQLForm()<br>";
				echoSpace ( 3 );
				echo "status : $status <br>";
				echoSpace ( 3 );
				echo "variable $variable not found in url parameters for form [$form]::::<br>";
				echoSpace ( 3 );
				echo "configuration not found : \$" . "FORM_VALUE_INSERT [" . $form . "][" . $variable . "][SQL]<br>";
				debug_print_backtrace ();
				echo "<br>";
				echo getEndActionMessage ();
			}
		}
	}
	return $result;
}

/**
 * getActionGet
 * retourne l'action a executer : String
 */
function getActionGet() {
	global $ACTION_GET;
	return getURLVariable ( "$ACTION_GET" );
}

/**
 * getAnneeCalendrierGet
 * retourne le calendrier a consulter
 * par defaut 2013
 */
function getAnneeCalendrierGet($defaultDate = "2014") {
	global $ANNEE_CALENDRIER_GET;
	$result = getURLVariable ( "$ANNEE_CALENDRIER_GET" );
	if ($result == "") {
		global $ANNEE_RESERVATION;
		if ($ANNEE_RESERVATION == "") {
			$result = $defaultDate;
		} else {
			$result = $ANNEE_RESERVATION;
		}
	}
	return $result;
}

/**
 * get current URL
 *
 * @return string
 */
function getCurrentURL() {
	return currentPageURL ();
}

/**
 * currentPageURL
 * retourne la page HTML courante
 * attention contient ?action=....
 * pour avoir tous les arguments, l'URL ne doit pas etre en "POST"
 */
function currentPageURL() {
	$pageURL = 'http';
	if (isset ( $_SERVER ["HTTPS"] )) {
		if ($_SERVER ["HTTPS"] == "on") {
			$pageURL .= "s";
		}
	}
	$pageURL .= "://";
	if ($_SERVER ["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
	}
	
	// voir si on ajoute pour traiter POST
	// if (!strpos($html, "?")){
	// $argument=propagateArguments("yes");
	// $html=$html.$argument;
	// }
	
	return $pageURL;
}

/**
 * currentPageName
 * retourne le nom de la page HTML courante
 * attention ne contient pas ?action=....
 */
function getCurrentPageName() {
	return substr ( $_SERVER ["SCRIPT_NAME"], strrpos ( $_SERVER ["SCRIPT_NAME"], "/" ) + 1 );
}

/**
 *
 * suppressPageURL
 *
 * @param $url url
 *        	peut etre ""
 * @param $variable ne
 *        	peut pas etre ""
 * @param $value peut
 *        	etre ""
 */
function suppressPageURL($url, $variable, $value) {
	if ($url == "") {
		$url = currentPageURL ();
	}
	if ($value == "") {
		$value = getURLVariable ( $variable );
	}
	$search = "&$variable=$value";
	$url = str_replace ( $search, "", $url );
	$search = "$variable=$value";
	$url = str_replace ( $search, "", $url );
	return $url;
}

/**
 * replace dans la Page URL
 *
 * @param $variable la
 *        	variable
 * @param $newValue la
 *        	nouvelle valeur
 * @param $url url
 *        	peut etre ""
 * @param $oldValue l'ancienne
 *        	valeur de la variable peut etre ""
 */
function replacePageURL($variable, $newValue, $url = "", $oldValue = "") {
	$url2 = suppressPageURL ( $url, $variable, $oldValue );
	$url2 = $url2 . "&" . "$variable=$newValue";
	return $url2;
}

/**
 *
 * createDefaultParamSql
 * creation d'un tableau avec
 * - $SHOW_COL_COUNT
 * - ORDER_ENUM::ORDER_GET
 * - $TABLE_SIZE
 * - $TABLE_NAME
 * - $
 *
 * @param String $table
 *        	le nom de la table
 * @param String $columns
 *        	la liste des colonnes "a,b,c,d"
 */
function createDefaultParamSql($table = "", $columnsTxt = "", $condition = "") {
	global $SHOW_COL_COUNT;
	global $TABLE_SIZE;
	global $TABLE_ID;
	global $TABLE_OTHER;
	global $TABLE_NAME;
	global $TABLE_CONDITION;
	global $COLUMNS_SUMMARY;
	global $TABLE_WHERE_CONDITION;
	global $TABLE_INFO_FORM;
	
	$param [$SHOW_COL_COUNT] = "yes";
	$param [$TABLE_SIZE] = 0;
	$param [$TABLE_OTHER] = "";
	$param [$TABLE_ID] = "";
	$param [$TABLE_INFO_FORM] = "";
	
	if ($table) {
		$param [$TABLE_ID] = "$table" . "_" . time ();
	}
	// ordonne suivant une colonne
	$param = updateParamSqlWithOrder ( $param );
	
	if ($table) {
		$param [$TABLE_NAME] = $table;
	}
	if ($columnsTxt) {
		$param [$COLUMNS_SUMMARY] = stringToArray ( $columnsTxt );
	}
	if ($condition) {
		// $param [$TABLE_WHERE_CONDITION] = $condition;
		$param = updateParamSqlCondition ( $param, $condition );
	}
	return $param;
}


/**
 * ajoute une colonne en parametre
 * @param unknown $aram
 * @param unknown $columnsTxt
 * @return string
 */
function addParamSqlColumn($param, $columnsTxt){
	global $COLUMNS_SUMMARY;
	
	$col = arrayToString($param [$COLUMNS_SUMMARY]);
	//showSQLAction("col avant add : $col");
	if ($col){
		$col=$col.", ";
	}
	
	if ($columnsTxt) {
		$col = $col."$columnsTxt";
		$param [$COLUMNS_SUMMARY] = stringToArray ( $col );
	}
	
	
	//showSQLAction("col apres add : $col");
	return $param;
}
/**
 *
 * modifierTableParamSql
 *
 * @param unknown $param        	
 * @param string $form
 *        	form name
 * @param string $insert
 *        	button update default "yes"
 * @param string $edit
 *        	button edit default "yes"
 * @return structure $param
 */
function modifierTableParamSql($param, $form = "form_insert_table", $insert = "yes", $edit = "yes", $delete = "yes", $exportCSV = "no") {
	global $TABLE_INSERT;
	global $TABLE_FORM_NAME_INSERT;
	global $TABLE_EDIT;
	global $TABLE_DELETE;
	global $TABLE_EXPORT_CSV;
	global $SHOW_FORM_TRACE;
	
	$param [$TABLE_INSERT] = $insert;
	$param [$TABLE_EDIT] = $edit;
	$param [$TABLE_DELETE] = $delete;
	$param [$TABLE_EXPORT_CSV] = $exportCSV;
	$param [$TABLE_FORM_NAME_INSERT] = $form;
	
	// infoForm par defaut
	$infoForm = getInfoForm ( $param );
	if ($SHOW_FORM_TRACE == "yes") {
		$infoForm = $infoForm . debugStreamFormHidden ( $TABLE_FORM_NAME_INSERT, $form );
	} else {
		$infoForm = $infoForm . streamFormHidden ( $TABLE_FORM_NAME_INSERT, $form );
	}
	$param = setInfoForm ( $param, $infoForm );
	
	showActionVariable ( ">>> modifierTableParamSql() forme name : $form - infoForm : $infoForm <br>", $SHOW_FORM_TRACE );
	
	return $param;
}

/**
 * updateParamSqlCondition
 *
 * @param array $param        	
 * @param string $condition
 *        	x="toto" and y="titi"
 * @return array $param modified
 */
function updateParamSqlCondition($param, $condition) {
	global $TABLE_WHERE_CONDITION;
	$param [$TABLE_WHERE_CONDITION] = $condition;
	
	return $param;
}

/**
 * updateParamSqlConditionForUpdate
 * si le parametre est positionné, le update utilisecette condition et pas celle de $TABLE_WHERE_CONDITION
 *
 * @param unknown $param        	
 * @param unknown $condition        	
 * @return unknown
 */
function updateParamSqlConditionForUpdate($param, $condition) {
	global $TABLE_WHERE_CONDITION_FOR_UPDATE;
	$param [$TABLE_WHERE_CONDITION_FOR_UPDATE] = $condition;
	
	return $param;
}

/**
 * permet d'ajouter distinct au select
 *
 * @param unknown $param        	
 * @return unknown
 */
function updateParamSqlWithDistinct($param) {
	global $TABLE_DISTINCT;
	$param [$TABLE_DISTINCT] = "yes";
	
	return $param;
}

/**
 * permet editer en utilisant tous les paramètres
 *
 * @param unknown $param        	
 * @return unknown
 */
function updateParamSqlWithEditByRow($param) {
	global $TABLE_EDIT_BY_ROW;
	global $TABLE_EDIT;
	$param [$TABLE_EDIT_BY_ROW] = "yes";
	$param [$TABLE_EDIT] = "no";
	
	return $param;
}

/**
 * permet d'ajouter le bouton delete by row
 *
 * @param unknown $param        	
 * @return unknown
 */
function updateParamSqlWithDeleteByRow($param) {
	global $TABLE_DELETE_BY_ROW;
	global $TABLE_DELETE;
	$param [$TABLE_DELETE_BY_ROW] = "yes";
	$param [$TABLE_DELETE] = "no";
	
	return $param;
}

/**
 * updateParamSqlColumnFilter
 *
 * @param unknown $param        	
 * @param unknown $columns        	
 * @return unknown
 */
function updateParamSqlColumnFilter($param, $columns) {
	global $COLUMNS_FILTER;
	$param [$COLUMNS_FILTER] = $columns;
	
	return $param;
}
/**
 * updateParamSqlWhereId
 *
 * @param unknown $param        	
 * @param unknown $id        	
 * @param unknown $value        	
 * @return unknown
 */
function updateParamSqlWhereId($param, $id, $value) {
	global $TABLE_WHERE_ID_KEY;
	global $TABLE_WHERE_ID_VALUE;
	
	$param [$TABLE_WHERE_ID_KEY] = $id;
	$param [$TABLE_WHERE_ID_VALUE] = $value;
	
	return $param;
}

/**
 * createDefaultParamSql
 * update parameter request avec ORDER_ENUM::ORDER_GET
 */
function updateParamSqlWithOrder($param) {
	// ordonne suivant une colonne
	
	$order = getURLVariable ( ORDER_ENUM::ORDER_GET );
	$param [ORDER_ENUM::ORDER_GET] = $order;
	
	// ordonnancement asc ou desc
	
	$direction = getURLVariable ( ORDER_ENUM::ORDER_DIRECTION );
	// showSQLAction("direction : $direction");
	$param [ORDER_ENUM::ORDER_DIRECTION] = $direction;
	
	return $param;
}

/**
 * update $param pour prendre en compte limit pour sql
 * voir createDefaultParamSql
 * a appeler apr�s
 *
 * @param array[String] $param        	
 * @return array[String] $param
 */
function updateParamSpqlWithLimit($param) {
	return updateParamSqlWithLimit ( $param );
}

/**
 * update $param pour prendre en compte limit pour sql
 * voir createDefaultParamSql
 * a appeler apr�s
 *
 * @param array[String] $param        	
 * @return array[String] $param
 */
function updateParamSqlWithLimit($param) {
	global $TABLE_ROW_LIMIT;
	global $TABLE_ROW_FIRST;
	
	$nb = getURLVariable ( $TABLE_ROW_LIMIT );
	if ($nb) {
		$param [$TABLE_ROW_LIMIT] = $nb;
	} else {
		$param [$TABLE_ROW_LIMIT] = 40;
	}
	
	$first = getURLVariable ( $TABLE_ROW_FIRST );
	if ($first) {
		$param [$TABLE_ROW_FIRST] = $first;
	} else {
		$param [$TABLE_ROW_FIRST] = 0;
	}
	
	return $param;
}

/**
 * update $param pour prendre en compte les infos du resultat d'une requete
 * voir createDefaultParamSql
 * a appeler apr�s
 *
 * @param array[String] $param        	
 * @param
 *        	result request $sqlResult
 * @return array[String] $param
 */
function updateParamSqlWithResult($param, $sqlResult) {
	global $COLUMNS_SUMMARY;
	global $TABLE_NAME;
	
	// detection des colonnes
	$param [$COLUMNS_SUMMARY] = array ();
	$num = $numfields = mysql_num_fields ( $sqlResult );
	for($Compteur = 0; $Compteur < $num; $Compteur ++) {
		$name = mysql_field_name ( $sqlResult, $Compteur );
		$param [$COLUMNS_SUMMARY] [$Compteur] = $name;
	}
	
	// detection du nom de la table
	$name = mysql_tablename ( $sqlResult, 0 );
	$param [$TABLE_NAME] = $name;
	
	return $param;
}

/**
 * updateParamSqlWithSubParam
 * update $param with $subparam values
 *
 * @param array $param
 *        	sql param
 * @param array $subParam
 *        	sql sub param
 * @return array $param modified
 */
function updateParamSqlWithSubParam($param, $subParam) {
	if ($subParam == "") {
		// nothing to do
	} else {
		// traitement par ajout
		// info
		$infoForm = checkInfoForm ( $subParam );
		$infoForm = checkInfoForm ( $param, $infoForm );
		$subParam = setInfoForm ( $subParam, $infoForm );
		
		// where
		$condition = mergeSqlWhere ( $subParam, $param );
		$subParam = updateParamSqlCondition ( $subParam, $condition );
		
		// traitement normal
		// var_dump($subParam);
		$keys = array_keys ( $subParam );
		// var_dump($keys);
		foreach ( $keys as $k ) {
			// echo "updateParam -- $k : $subParam[$k] <br>";
			$param [$k] = $subParam [$k];
		}
	}
	return $param;
}

/**
 * print param
 *
 * @param
 *        	parameter array $parm
 */
function printParam($param, $header = "") {
	$keys = array_keys ( $param );
	// var_dump($keys);
	foreach ( $keys as $k ) {
		echo "$header printParam -- $k : $param[$k] <br>";
	}
}

// ///////show action /////////////////////////////////////////////////////////////

/**
 * traceConnectionID
 * trace la connection si $CONNECTION_ID est positionn�
 */
function traceConnectionID() {
	global $SHOW_CONNECTION_ID;
	global $CONNECTION_ID;
	$txt = "---- $CONNECTION_ID -----";
	echo showActionVariable ( $txt, $SHOW_CONNECTION_ID );
}

/**
 * showAction
 * affiche $txt
 *
 * le message n'est pas affich� si $NO_MSG_CHANGE_HEADER_ACTIVE & $NO_MSG_CHANGE_HEADER sont a "yes"
 *
 * @param $txt le
 *        	message � afficher
 */
function showAction($txt) {
	global $NO_MSG_CHANGE_HEADER_ACTIVE;
	global $NO_MSG_CHANGE_HEADER;
	
	if ($NO_MSG_CHANGE_HEADER_ACTIVE == "yes" && $NO_MSG_CHANGE_HEADER == "yes") {
		return;
	}
	echo getActionMessage ( $txt );
}

/**
 *
 * showSQLAction
 * affiche $txt si $SHOW_SQL_ACTION est positionne
 *
 * @param unknown_type $txt        	
 */
function showSQLAction($txt) {
	global $SHOW_SQL_ACTION;
	showActionVariable ( $txt, $SHOW_SQL_ACTION );
}

/**
 * show Trace POST
 */
function showTracePOST() {
	global $TRACE_POST;
	
	if ($TRACE_POST == "yes") {
		echo '<table><tr><td valign="top">';
		showGet ();
		echo '</td><td valign="top" >';
		showPost ();
		echo "</td></tr></table>";
	}
}

/**
 * show SQL error
 * message a afficher $txt
 * si il y a une erreur alors affiche aussi l'erreur sql
 *
 * @param Msg $txt        	
 * @param Msg $txtError
 *        	(visible seulement si il y a une erreur)
 * @return sql error
 */
function showSQLError($txt, $txtError = "") {
	$sqlError = mysql_error ();
	showSQLError2 ( $txt, $sqlError, $txtError );
	return $sqlError;
}

/**
 * showSQLError2
 *
 * @param string $txt
 *        	message toujours affich�
 * @param string $sqlError
 *        	message d'erreur
 */
function showSQLError2($txt, $sqlError, $txtError = "") {
	if ($sqlError == "") {
		if ($txt == "") {
			// nothingto do
		} else {
			showSQLAction ( $txt );
		}
	} else {
		echo getActionMessage ( $txt . "   $txtError <font color=\"red\">  $sqlError </font>" );
	}
}

function showError($txt){
	echo getActionMessage ( "<font color=\"red\">  $txt </font>" );
}

/**
 *
 * showActionVariable
 * affiche le texte si debugVariable est positionn�
 *
 * @param
 *        	$txt
 * @param
 *        	$debugVariable
 */
function showActionVariable($txt, $debugVariable) {
	if ($debugVariable == false)
		return;
	if ($debugVariable == "")
		return;
	if ($debugVariable == "no")
		return;
	if ($debugVariable == "false")
		return;
	
	echo getActionMessage ( $txt );
}

/**
 *
 * getActionMessage
 * retourne le message au format HTML
 *
 * @param
 *        	$txt
 */
function getActionMessage($txt) {
	// return "<table> <tr bgcolor=#FFFFCC>
	// <td>
	// $txt
	// </td>
	// </tr></table>
	// <br>";
	return getBeginActionMessage () . "$txt" . getEndActionMessage ();
}

/**
 * getBeginActionMessage
 *
 * @return string
 */
function getBeginActionMessage() {
	return "<table> <tr bgcolor=#FFFFCC> <td>";
}

/**
 * getEndActionMessage
 *
 * @return string
 */
function getEndActionMessage() {
	return "</td></tr></table>  <br>";
}

// ////////////////// SQL /////////////////////////////////////////////

/**
 * retourne un tableau des valeurs du tableau col pour la requete donnée
 *
 * @param string $request
 *        	la requete a executer
 * @param string $col
 *        	colonne à recupérer
 * @return multitype:string
 * @not used
 */
function sqlRequestToArray($request, $col) {
	$res = array ();
	$Resultat = mysql_query ( $request );
	showSQLError ( "" );
	$nbRes = mysql_numrows ( $Resultat );
	for($cpt = 0; $cpt < $nbRes; $cpt ++) {
		$v = mysql_result ( $Resultat, $cpt, $col );
		$res [$cpt] = $v;
	}
	
	return $res;
}

/**
 * sqlRequestToArray2
 * creerun tableau array[$col=0..n][value] a partie d'une requete sql
 *
 * @param $request :
 *        	la requete
 * @param $col tableau
 *        	de colonnes
 * @not used
 */
function sqlRequestToArray2($request, $col) {
	$res = array ();
	$Resultat = mysql_query ( $request );
	showSQLError ( "" );
	$nbRes = mysql_numrows ( $Resultat );
	// echo "nbCol : $nbRes";
	for($cpt = 0; $cpt < $nbRes; $cpt ++) {
		$i = 0;
		foreach ( $col as $c ) {
			$v = mysql_result ( $Resultat, $cpt, $c );
			// echo" value $v";
			$res [$cpt] [$i] = $v;
			$i ++;
		}
	}
	
	return $res;
}


/**
 * sqlParamToArrayResult
 * @param param $param
 * @return array Result
 */
function sqlParamToArrayResult($param) {

	$request = createRequeteTableData ( $param );
	//showSQLAction ( $request );

	
	// creation tableau resultat
	$Resultat = mysqlQuery ( $request );
	$nbRes = mysqlNumrows ( $Resultat );
	
	global $COLUMNS_SUMMARY;
	$columns = $param[$COLUMNS_SUMMARY];

	// set type des columns depuis le resultat Sql
	$ci = 0;
	$tableau = array ();
	foreach ( $columns as $c ) {
		//type
		$type2 = mysqlFieldType ( $Resultat, $ci );
		$tableau = setSQLFlagType ( $tableau, $c, $type2 );
		
		//flag
		$flag = mysqlFieldFlags( $Resultat, $ci );
		$tableau = setSQLFlagField( $tableau, $c, $flag );
		
		//status
		$tableau = setSQLFlagStatus ( $tableau, $c, "enabled" );
		$ci ++;
	}

	//set values
	for($cpt = 0; $cpt < $nbRes; $cpt ++) {
		foreach ( $columns as $c ) {
			$field_offset = indexOfValueInArray ( $columns, $c );
			$res = mysql_result ( $Resultat, $cpt, $field_offset );
			$tableau [$c] [$cpt] = $res;
		}
	}

	return $tableau;
}


/**
 * yesNoToInt
 * transforme "yes" et "no" en 1 ou 0
 * "yes" -> 1
 * "1" -> 1
 * "no" -> 0
 * <autre> -> 0
 *
 * @param string $var        	
 */
function yesNoToInt($var) {
	if ($var == "yes") {
		return 1;
	} elseif ($var == "1") {
		return 1;
	} else {
		return 0;
	}
}
/**
 * separateWith
 * creation d'une string a partir d'un tableau
 *
 * @param
 *        	$columns
 * @param
 *        	$separator
 */
function separateWith($columns, $separator) {
	$res = "";
	$cpt = 0;
	if (is_array ( $columns )) {
		// echo "-------------------- <br>$columns<br>------------<br>";
		foreach ( $columns as $c ) {
			if ($cpt > 0) {
				$res .= ", ";
			}
			// echo "--$c<br>--";
			$res .= $c;
			$cpt ++;
		}
	} else {
		$res = $columns;
	}
	return $res;
}

/**
 * historisationTable
 *
 * @param $table able
 *        	de depart
 * @param $table_historique table
 *        	a modifier
 * @param $columns colonne
 *        	a copier "cc1, cc2, cc3"
 * @param $condition condition
 *        	derriere le WHERE
 */
function historisationTable($table, $table_historique, $columns, $condition, $show = "1") {
	global $SQL_ALL_COL_DOCUMENT;
	$col = separateWith ( $columns, "," );
	$sql = "INSERT INTO `$table_historique` ( $col )
			SELECT $col
			FROM $table";
	if ($condition != "") {
		$sql = "$sql WHERE $condition";
	}
	
	$txt = "historisation $table $condition";
	if ($show == "1")
		showAction ( $txt );
	$txt = "$sql";
	
	if ($show == "1")
		showSQLAction ( $txt );
	$Resultat = mysql_query ( $sql );
	if ($show == "1")
		showSQLError ( "" );
	return $Resultat;
}

/**
 * createSqlUpdateByID
 *
 * @param unknown $table        	
 * @param unknown $columnsString        	
 * @return string SQL
 */
function createSqlUpdateByID($table, $columnsString) {
	return createSqlUpdateByIdAndCondition ( $table, $columnsString );
}

/**
 * createSqlUpdateByID
 *
 * @param string $table        	
 * @param string $columnsString        	
 * @return string SQL
 */
function createSqlUpdateByIdAndCondition($table, $columnsString, $formName = "") {
	global $ID_TABLE_GET;
	global $CONDITION_GET;
	
	$condition = getURLVariable ( $CONDITION_GET );
	
	// showAction("update to do...");
	// columns & values
	$columns = stringToArray ( $columnsString );
	if ($formName) {
		$arrayValues = getURLVariableArraySQLForm ( $columns, $formName );
	} else {
		$arrayValues = getURLVariableArray ( $columns );
	}
	$key = $columns [0];
	// where
	$idTable = getURLVariable ( $ID_TABLE_GET );
	if ($idTable) {
		$condition = createSqlWhereID ( $key, $idTable, $condition );
	}
	
	if ($condition) {
		// nothing to do
	} else {
		// attention on a pas de condition pour l'update, pas normal
		$condition = "0";
	}
	
	$sql = createSqlUpdate ( $table, $columns, $arrayValues, $condition );
	
	return $sql;
}

// function createSqlInsertUpdate($table, $columnsStringSet, $columnsStringWhere, $idx) {
// // columns & values
// $columnsSet = stringToArray ( $columnsStringSet );
// $arrayValuesSet = getURLVariableArray ( $columnsSet, $idx );

// //where
// $columnsWhere = stringToArray ( $columnsStringWhere );
// $arrayValuesWhere = getURLVariableArray ( $columnsWhere, $idx );
// $condition = createSqlWhereArray ( $columnsWhere, $arrayValuesWhere );

// $sql = createSqlUpdate ( $table, $columnsSet, $arrayValuesSet, $condition );

// return $sql;
// }

/**
 * createSqlUpdate
 * creation requete sql update
 *
 * @param $table la
 *        	table
 * @param $arrayCol le
 *        	tableau des colonnes
 * @param $arrayValue le
 *        	tableau des valeurs
 * @param $condition la
 *        	condition du update (apres le WHERE)
 * @param $quoteValue "true"
 *        	si on doit quoter dans la requete les values
 *        	return sql request
 */
function createSqlUpdate($table, $arrayCol, $arrayValue, $condition, $quoteValue = "true") {
	$sql = "UPDATE `$table` SET ";
	
	$i = 0;
	foreach ( $arrayValue as $v ) {
		$values [$i] = $v;
		$i ++;
	}
	$i = 0;
	foreach ( $arrayCol as $c ) {
		$v = $values [$i];
		if ($i > 0) {
			$sql = $sql . " , ";
		}
		$sql = $sql . "`$c` = ";
		if ($quoteValue == "true")
			$v = "\"$v\"";
		$sql = $sql . $v;
		$i ++;
	}
	//
	$sql = $sql . " WHERE " . $condition;
	return $sql;
}

/**
 * createSqlUpdate
 * creation requete sql insert
 *
 * @param $table la
 *        	table
 * @param $arrayCol le
 *        	tableau des colonnes
 * @param $arrayValue le
 *        	tableau des valeurs
 * @param $quoteValue "true"
 *        	si on doit quoter dans la requete les values
 */
function createSqlInsert($table, $arrayCol, $arrayValue, $quoteValue = "true") {
	$sql = "INSERT INTO `$table` ( ";
	
	$i = 0;
	foreach ( $arrayCol as $v ) {
		if ($i > 0) {
			$sql = $sql . " , ";
		}
		$sql = $sql . "`$v`";
		$i ++;
	}
	
	$sql = $sql . ") VALUES (";
	
	$i = 0;
	foreach ( $arrayValue as $v ) {
		if ($i > 0) {
			$sql = $sql . " , ";
		}
		if ($quoteValue == "true")
			$v = "\"$v\"";
		$sql = $sql . $v;
		$i ++;
	}
	$sql = $sql . ")";
	
	return $sql;
}
function createSqlReplace($table, $arrayCol, $arrayValue, $quoteValue = "true") {
	$sql = "REPLACE INTO `$table` SET ";
	
	$i = 0;
	foreach ( $arrayCol as $c ) {
		if ($i > 0) {
			$sql = $sql . " , ";
		}
		$sql = $sql . "`$c` = ";
		$v = $arrayValue [$i];
		if ($quoteValue == "true") {
			$v = "\"$v\"";
		}
		$sql = $sql . $v;
		$i ++;
	}
	
	return $sql;
}

/**
 * createSqlDelete
 *
 * @param String $table
 *        	table name
 * @param String $key
 *        	key name
 * @param String $value
 *        	value for key
 * @return string Sql request
 */
function createSqlDelete($table, $key, $value) {
	$condition = createSqlWhere ( $key, $value );
	$sql = "Delete from `$table` WHERE $condition";
	return $sql;
}

/**
 * getPrimaryKeyValue
 *
 * @param sql result or array $resultat        	
 * @param interger $row row number        	
 * @return string value for Primary Key
 */
function getPrimaryKeyValue($resultat, $row) {
	$pos = getPrimaryKeyPosition($resultat, $row);
	if ($pos == -1) {
		return "";
	}
	return mysqlResult ( $resultat, $row, $pos );
}

/**
 * getPrimaryKeyPosition
 * 
 * @param sql result or array result $resultat
 * @param integer $row
 * @return number|String column position or column name
 */
function getPrimaryKeyPosition($resultat, $row) {
	$count = mysqlNumFields ( $resultat );
	for($idx = 0; $idx < $count; $idx ++) {
		$flags = mysqlFieldFlags ( $resultat, $idx );
		$pos = strpos ( $flags, "primary_key" );
		//if ($pos == "") return "";
		if ($pos >= 0) {
			if (is_array($resultat)){
				$col = mysqlFieldName($resultat, $idx);
				//echoTD("getPrimaryKeyValue() position : $pos col : $col");
				$idx = $col;
			}
			return $idx;
		}
	}
	
	return -1;
}


// /////////////////////// tools ////////////////////////////////////////

/**
 * isFiltred
 *
 * @param unknown $data
 *        	donnée à tester
 * @param unknown $filtre
 *        	tableau de filtre
 * @return number FALSE si pas filtré
 */
function isFiltred($data, $filtre) {
	foreach ( $filtre as $elt ) {
		if (preg_match ( $elt, $data ) == 1) {
			return TRUE;
		}
	}
	return FALSE;
}

// ///////////////// IHM /////////////////////////////////////////////////////////

/**
 * showLimitBar
 * show navigation bar with [next] / [previous]
 *
 * @param structure $param
 *        	: sql table parameters
 * @param string $html
 *        	: page url
 */
function showLimitBar($param, $html = "") {
	global $TABLE_ROW_FIRST;
	global $TABLE_ROW_LIMIT;
	global $TABLE_NAME;
	global $URL_IMAGES;
	
	$first = $param [$TABLE_ROW_FIRST];
	$nb = $param [$TABLE_ROW_LIMIT];
	$predecesseur = $first - $nb;
	$suivant = $first + $nb;
	
	// echo "first $first <br>";
	// echo "suivant $suivant <br>";
	// echo "nb $nb <br>";
	
	$table = $param [$TABLE_NAME];
	
	if (! $html) {
		$html = currentPageURL ();
		if (! strpos ( $html, "?" )) {
			$argument = propagateArguments ( "yes" );
			$html = $html . $argument;
		}
	}
	// echo "HTML : $html <br>";
	$html = suppressPageURL ( $html, $TABLE_ROW_FIRST, "" );
	$html = suppressPageURL ( $html, $TABLE_ROW_LIMIT, "" );
	// echo "HTML 2 : $html <br>";
	
	echo "<p>";
	if ($first > 0) {
		echo "<a href=\"$html&$TABLE_ROW_FIRST=0&$TABLE_ROW_LIMIT=$nb \">";
		echo " <img src=\"$URL_IMAGES\\first.png\" >";
		echo "</a>";
	}
	
	if ($first > 0) {
		echo " <a href=\"$html&$TABLE_ROW_FIRST=$predecesseur&$TABLE_ROW_LIMIT=$nb \">";
		echo " <img src=\"$URL_IMAGES\\previous.png\" >";
		echo "</a>";
	}
	
	echo " [$first] ";
	
	echo " <a href=\"$html&$TABLE_ROW_FIRST=$suivant&$TABLE_ROW_LIMIT=$nb \">";
	echo " <img src=\"$URL_IMAGES\\next.png\" >";
	echo "</a>";
	
	echo "</p>";
}

/**
 *
 * showTableHeader
 *
 * @param unknown_type $param
 *        	voir createDefaultParamSql();
 * @param string $html
 *        	url
 */
function showTableHeader($param, $html = "") {
	// creation du header
	global $SHOW_COL_COUNT;
	
	global $TABLE_SIZE;
	global $TABLE_ID;
	global $TABLE_OTHER;
	global $COLUMNS_SUMMARY;
	
	
	global $URL_IMAGES;
	
	$columnSummary = $param [$COLUMNS_SUMMARY];
	if (! $html) {
		$html = currentPageURL ();
		if (! strpos ( $html, "?" )) {
			$argument = propagateArguments ( "yes" );
			$html = $html . $argument;
		}
		$html = suppressPageURL ( $html, ORDER_ENUM::ORDER_GET, "" );
		$html = suppressPageURL ( $html, ORDER_ENUM::ORDER_DIRECTION, "" );
	}
	// echo "HTML : $html <br>";
	
	if ($param [$TABLE_ID]) {
		$tableId = "id='$param[$TABLE_ID]'";
	}
	
	if ($param [$TABLE_SIZE]) {
		$tableSize = "width='$param[$TABLE_SIZE]'";
	}
	
	if ($param [$TABLE_OTHER]) {
		$tableOther = $param [$TABLE_OTHER];
		global $SHOW_FUNCION_JAVA;
		showActionVariable ("table action : $tableOther",  $SHOW_FUNCION_JAVA);
	} else {
		$tableOther = "";
	}
	
	echo "<table $tableId $tableSize $tableOther >";
	
	beginTableHeader ();
	
	// ajout colonne count
	if ($param [$SHOW_COL_COUNT] == "yes") {
		echo "<td>#</td>";
	}
	
	// ajout autres colonnes avec organisation asc, desc
	foreach ( $columnSummary as $c ) {
		if (isset ( $param [ORDER_ENUM::ORDER_GET] ) && $param [ORDER_ENUM::ORDER_GET] == $c) {
			if (isset ( $param [ORDER_ENUM::ORDER_DIRECTION] ) && $param [ORDER_ENUM::ORDER_DIRECTION] == ORDER_ENUM::ORDER_DIRECTION_DESC) {
				echo "
					<td> <a 
					   href=\"$html&ORDER_ENUM::ORDER_GET=$c&ORDER_ENUM::ORDER_DIRECTION=ORDER_ENUM::ORDER_DIRECTION_NO \">" . $c . "</a> 
					   <img src=\"$URL_IMAGES/b.png\">
					</td>";
			} else if (isset ( $param [ORDER_ENUM::ORDER_DIRECTION] ) && $param [ORDER_ENUM::ORDER_DIRECTION] == ORDER_ENUM::ORDER_DIRECTION_ASC) {
				echo "
					<td> <a 
					   href=\"$html&ORDER_ENUM::ORDER_GET=$c&ORDER_ENUM::ORDER_DIRECTION=ORDER_ENUM::ORDER_DIRECTION_DESC \">" . $c . "</a> 
					   <img src=\"$URL_IMAGES/h.png\">
					</td>";
			} else if (isset ( $param [ORDER_ENUM::ORDER_DIRECTION] ) && $param [ORDER_ENUM::ORDER_DIRECTION] == ORDER_ENUM::ORDER_DIRECTION_NO) {
				echo "
					<td> <a 
					   href=\"$html&ORDER_ENUM::ORDER_GET=$c&ORDER_ENUM::ORDER_DIRECTION=ORDER_ENUM::ORDER_DIRECTION_ASC \">" . $c . "</a> 
					</td>";
			} else {
				echo "<td> <a href=\"$html&ORDER_ENUM::ORDER_GET=$c&ORDER_ENUM::ORDER_DIRECTION=ORDER_ENUM::ORDER_DIRECTION_DESC  \">" . $c . "</a> 
			    <img src=\"$URL_IMAGES/h.png\">
				</td>";
			}
		} else {
			echo "<td> <a href=\"$html&ORDER_ENUM::ORDER_GET=$c\">" . $c . "</a> </td>";
		}
	}
	
	endTableHeader ();
	// echo "</tr>";
}

/**
 * createRequeteTableData
 * cree le texte de la requete equivalente à $param
 *
 * @param
 *        	sql param $param
 *        	return request txt
 */
function createRequeteTableData($param) {
	global $TABLE_NAME;
	global $TABLE_ROW_LIMIT;
	global $TABLE_ROW_FIRST;
	global $TABLE_FORM_NAME_INSERT;
	global $TABLE_WHERE_ID_KEY;
	global $TABLE_WHERE_ID_VALUE;
	global $TABLE_WHERE_CONDITION;
	global $TABLE_DISTINCT;
	global $COLUMNS_FILTER;
	
	$table = $param [$TABLE_NAME];
	$request = "select ";
	
	// ajout distinct
	if (array_key_exists ( $TABLE_DISTINCT, $param )) {
		if ($param [$TABLE_DISTINCT] == "yes") {
			$request = $request . "distinct ";
		}
	}
	
	// ajout column filter
	if (array_key_exists ( $COLUMNS_FILTER, $param )) {
		$request = $request . " $param[$COLUMNS_FILTER] from $table";
	} else {
		$request = $request . "* from $table";
	}
	
	// ajout Where
	if (array_key_exists ( $TABLE_WHERE_ID_KEY, $param ) || array_key_exists ( $TABLE_WHERE_CONDITION, $param )) {
		$request = $request . " WHERE ";
	}
	
	$cptWhere = 0;
	if (array_key_exists ( $TABLE_WHERE_ID_KEY, $param )) {
		if ($cptWhere > 0) {
			$request = $request . " AND ";
		}
		$request = $request . createSqlWhereID ( $param [$TABLE_WHERE_ID_KEY], $param [$TABLE_WHERE_ID_VALUE] );
		$cptWhere ++;
	}
	if (array_key_exists ( $TABLE_WHERE_CONDITION, $param )) {
		if ($cptWhere > 0) {
			$request = $request . " AND ";
		}
		$request = $request . $param [$TABLE_WHERE_CONDITION];
		$cptWhere ++;
	}
	
	// gestion order by
	// doit venir après le WHERE
	$request = createRequestOrderByWithParam ( $request, $param );
	
	// ajout limit
	// doit venir après le WHERE et order by
	if (array_key_exists ( $TABLE_ROW_LIMIT, $param )) {
		$request = createRequestLimit ( $request, $param [$TABLE_ROW_FIRST], $param [$TABLE_ROW_LIMIT] );
	}
	
	// showSQLAction("<td>request : $request</td></tr><tr>");
	
	return $request;
}

/**
 *
 * requeteTableData d'une table
 *
 * @param $param parametre
 *        	d'affichage (voir createDefaultParamSql() )
 * @param $html fichier
 *        	a appeler si on fait un sorte
 * @param $Resultat resultat
 *        	sql a afficher
 */
function requeteTableData($param) {
	$request = createRequeteTableData ( $param );
	
	$Resultat = mysqlQuery ( $request );
	// showSQLError ( "", $request . "<br><br>" );
	
	return $Resultat;
}

/**
 * setInfoForm
 *
 * @param
 *        	request parametre $param
 * @param string $infoForm        	
 * @return request modified
 */
function setInfoForm($param, $infoForm) {
	global $TABLE_FORM_INFO;
	$param [$TABLE_FORM_INFO] = $infoForm;
	return $param;
}

/**
 * getInfoForm
 *
 * @param
 *        	request parametre $param
 * @param string $default        	
 * @return string
 */
function getInfoForm($param, $default = "") {
	global $TABLE_FORM_INFO;
	
	if (isset ( $param [$TABLE_FORM_INFO] )) {
		return $param [$TABLE_FORM_INFO];
	}
	
	return $default;
}

/**
 * checkInfoForm
 *
 * @param parameters $param        	
 * @param string $infoForm        	
 *
 * @return infoForm (form $param or string)
 */
function checkInfoForm($param, $infoForm = "") {
	// info param
	$infoparam = "";
	
	global $TABLE_FORM_INFO;
	if (isset ( $param [$TABLE_FORM_INFO] )) {
		$infoparam = $param [$TABLE_FORM_INFO];
	}
	
	return $infoparam . "  " . $infoForm;
}

/**
 *
 * Affiche les donnees d'une table
 *
 * @param $param parametre
 *        	d'affichage (voir createDefaultParamSql() )
 * @param $html fichier
 *        	a appeler si on fait un sorte
 * @param $Resultat resultat
 *        	sql a afficher
 */
function showTableData($param, $html = "", $Resultat = "", $closeTable = "yes") {
	if (! $Resultat) {
		$Resultat = requeteTableData ( $param );
	}
	
	$nbRes = mysqlNumrows ( $Resultat );
	
	if (! $html) {
		$html = currentPageURL ();
		// echo $html."<br>";
	}
	
	global $TABLE_UPDATE;
	if (isset ( $param [$TABLE_UPDATE] ) && ($param [$TABLE_UPDATE] == "yes")) {
		showEditTableData ( $param, $html, $Resultat );
		$cpt = 0;
	} else {
		// affiche les rows
		for($cpt = 0; $cpt < $nbRes; $cpt ++) {
			echo "<tr>";
			showTableOneData ( $html, $Resultat, $cpt, $param );
			echo "</tr>";
		}
		showTableLineExportCSV ( $param, $html );
		// beginTableRow ();
		// showLineExportCSV ( $param, $infoForm = "", "yes", $html, "" );
		// endTableRow ();
	}
	
	// action insertion
	showTableLineInsert ( $param, $html, $Resultat );
	// global $TABLE_INSERT;
	// if (isset ( $param [$TABLE_INSERT] ) && ($param [$TABLE_INSERT] == "yes")) {
	// echo "<tr>";
	// insertTableOneData ( $html, $Resultat, /*$cpt,*/ $param );
	// echo "</tr>";
	// }
	
	if ($closeTable == "yes") {
		endTable ();
		// echo "</table>";
		// showAction("close table");
	}
	
	return $Resultat;
}

/**
 * showTablelineSummation
 * affiche une ligne pour les sommations dans un tableau
 *
 * @param
 *        	sql params $param2
 * @param string $allCols
 *        	listes des colonnes
 * @param string $sumCols
 *        	listes des colonnesdes sommations
 */
function showTablelineSummation($param2, $allCols = "", $sumCols = "") {
	global $SHOW_COL_COUNT;
	
	if ($allCols == "") {
		global $COLUMNS_SUMMARY;
		$allCols = $param2 [$COLUMNS_SUMMARY];
	}
	if ($sumCols == "") {
		$sumCols = $allCols;
	}
	
	// show sum row
	beginTableRow ();
	if ($param2 [$SHOW_COL_COUNT] == "yes") {
		// index
		echoTD ( "" );
	}
	
	// show other
	if (is_array ( $allCols )) {
		$arrayAllCols = $allCols;
	} else {
		$arrayAllCols = stringToArray ( $allCols );
	}
	
	if (is_array ( $sumCols )) {
		$arraySumCols = $sumCols;
	} else {
		$arraySumCols = stringToArray ( $sumCols );
	}
	foreach ( $arrayAllCols as $col ) {
		if (in_array ( $col, $arraySumCols )) {
			showFormTextElementForVariable ( "maForm", "sum_col_" . $col, "no", "", "", "", "3", "disabled" );
		} else {
			// col info
			echoTD ( "" );
		}
	}
	
	// close row
	endTableRow ();
}

/**
 * showTableLineExportCSV
 * affiche une line dans le tableau avec le bouton export CSV
 *
 * @param
 *        	parameters sql $param
 * @param string $html
 *        	url
 */
function showTableLineExportCSV($param, $html = "") {
	if (! $html) {
		$html = currentPageURL ();
	}
	
	beginTableRow ();
	showLineExportCSV ( $param, /**
	 * $infoForm
	 */
	"", "yes", /**
	 * form autonome
	 */
	$html, "" );
	endTableRow ();
}

/**
 * showTableInsert
 * affiche le row pour une insertion d'une donnee
 *
 * @param
 *        	parameters sql $param
 * @param string $html
 *        	url
 * @param sql result or array $Resultat
 * @param array[column name] $value 	selected value
 */
function showTableLineInsert($param, $html = "", $Resultat = "", $value = "") {
	if (! $html) {
		$html = currentPageURL ();
	}
	// action insertion
	global $TABLE_INSERT;
	if (isset ( $param [$TABLE_INSERT] ) && ($param [$TABLE_INSERT] == "yes")) {
		echo "<tr>";
		insertTableOneData ( $html, $Resultat, /*$cpt,*/ $param, $value );
		echo "</tr>";
	}
}

/**
 * showEditTableData
 *
 * affiche la table de data
 * affiche le bouton [update] pour un update global
 *
 * @param
 *        	parametre de la requete $param
 * @param string $html        	
 * @param
 *        	resultat de requete $Resultat
 * @param structure $infoForm        	
 */
function showEditTableData($param, $html = "", $Resultat = "") {
	beginFormTable ( $param, $html, $Resultat );
	showEditRowsTableData ( $param, $html, $Resultat );
	showTableRowAction ( $param, $Resultat );
	endForm ();
}

/**
 * beginFormTable
 *
 * @param unknown $param        	
 * @param string $html        	
 * @param string $Resultat        	
 */
function beginFormTable($param, $html = "", $Resultat = "") {
	global $TABLE_FORM_NAME_INSERT;
	
	if (! $html) {
		$html = currentPageURL ();
	}
	
	$formName = $param [$TABLE_FORM_NAME_INSERT];
	createForm ( $html, $formName );
}

/**
 * showEditRowsTableData
 *
 * @param unknown $param        	
 * @param string $html        	
 * @param string $Resultat        	
 * @return string
 */
function showEditRowsTableData($param, $html = "", $Resultat = "") {
	if (! $Resultat) {
		$Resultat = requeteTableData ( $param );
	}
	
	global $TABLE_FORM_NAME_INSERT;
	$formName = $param [$TABLE_FORM_NAME_INSERT];
	
	$nbRes = mysqlNumrows ( $Resultat );
	// echo">>>>> $nbRes <<<<<<<";
	
	// colonne index
	global $COLUMNS_SUMMARY;
	global $TABLE_ROW_FIRST;
	$columns = $param [$COLUMNS_SUMMARY];
	
	// affiche les rows
	for($cpt = 0; $cpt < $nbRes; $cpt ++) {
		echo "<tr>";
		if ($param ['show_col_count'] == "yes") {
			if (array_key_exists ( $TABLE_ROW_FIRST, $param )) {
				$cpt2 = $cpt + $param [$TABLE_ROW_FIRST];
				echo "<td>$cpt2</td>";
			} else {
				echo "<td>$cpt</td>";
			}
		}
		// parcours des colonnes
		$idx = 0;
		foreach ( $columns as $c ) {
			// echo "<td align=\"right\">$c :</td>";
			editSqlRow ( $Resultat, $c, $cpt, $formName, $idx );
			$idx ++;
		}
		echo "</tr>";
	}
	return $Resultat;
}

/**
 * showTableRowAction
 * ajoute la ligne d'action [update][export csv]
 *
 * @param unknown $param        	
 * @param string $html        	
 * @param string $Resultat        	
 * @param string $infoForm        	
 */
function showTableRowAction($param, $html = "", $Resultat = "") {
	$infoForm = checkInfoForm ( $param, $infoForm );
	
	global $ACTION_GET;
	
	echo "<tr>";
	echo "<td>";
	showFormIDElement ();
	echo "$infoForm";
	// showFormAction ( "replace" );
	showFormSubmit ( "update", $ACTION_GET );
	echo "</td>";
	
	showLineExportCSV ( $param, $infoForm );
	echo "</tr>";
}

/**
 * showLineExportCSV
 *
 * @param unknown $param        	
 */
function showLineExportCSV($param, $infoForm = "", $formAutonome = "no", $html = "", $formName = "") {
	global $TABLE_EXPORT_CSV;
	global $ACTION_GET;
	
	if (isset ( $param [$TABLE_EXPORT_CSV] ) && ($param [$TABLE_EXPORT_CSV] == "yes")) {
		if ($formAutonome == "yes") {
			createForm ( $html, $formName );
		}
		
		echo "<td>";
		showFormIDElement ();
		echo "$infoForm";
		// showFormAction ( "multi-action" );
		showFormSubmit ( "export CSV", $ACTION_GET );
		echo "</td>";
	}
	
	if ($formAutonome == "yes") {
		endForm ();
	}
}

/**
 * isFormTable : doit on utiliser une table
 *
 * @param unknown $param        	
 * @return number
 */
function isFormTable($param) {
	// action insertion
	global $TABLE_INSERT;
	if (isset ( $param [$TABLE_INSERT] ) & ($param [$TABLE_INSERT] == "yes")) {
		return 1;
	}
	return 0;
}

/**
 * createRequestOrderByWithParam
 *
 * @param string $request
 *        	debut de la requete
 * @param string $param
 *        	tableau de parametres
 * @return string requete modifiee
 */
function createRequestOrderByWithParam($request, $param) {
	
	
	$request = createRequestOrderBy ( $request, $param [ORDER_ENUM::ORDER_GET], $param [ORDER_ENUM::ORDER_DIRECTION] );
	
	return $request;
}

/**
 * createRequestOrderBy
 *
 * @param string $request
 *        	debut de la requete
 * @param string ORDER_ENUM::ORDER_GET
 *        	colonne du order by
 * @param unknown $order_direction
 *        	asc ou desc ou vide
 * @return string requete modifiee
 */
function createRequestOrderBy($request, $order_get, $order_direction) {
	if ($order_get) {
		if ($order_direction == ORDER_ENUM::ORDER_DIRECTION_NO) {
			// nothing to do
		} else {
			$request = $request . " order by `" .$order_get . "`";
			// showSQLAction("param[direction] : $order_direction");
			if (isset ( $order_direction )) {
				$request = $request . " " . $order_direction;
			}
		}
	}
	return $request;
}

/**
 * createRequestLimit
 *
 * @param string $request
 *        	debut de la requete
 * @param string $first
 *        	first row
 * @param unknown $limit
 *        	nb row
 * @return string requete modifi�e
 */
function createRequestLimit($request, $first, $limit) {
	if (isset ( $limit )) {
		$request = $request . " limit ";
		if ($first) {
			$request = $request . " " . $first . " , ";
		} else {
			$request = $request . " 0, ";
		}
		$request = $request . " " . $limit;
	}
	return $request;
}

/**
 * createSqlWhereID
 *
 * @param unknown $key        	
 * @param unknown $value        	
 * @return string
 */
function createSqlWhereID($key, $value, $condition = "") {
	$res = "$key" . "='" . $value . "'";
	if ($condition) {
		$res = $condition . " AND " . $res;
	}
	return $res;
}

/**
 * createSqlWhereArray
 *
 * @param unknown $keyA
 *        	variable for condition
 * @param unknown $valueA
 *        	value searched
 * @param unknown $condition
 *        	condition to add
 * @return string where request without WHERE
 */
function createSqlWhereArray($keyA, $valueA, $condition) {
	$size = count ( $keyA );
	$txt = $condition;
	for($idx = 0; $idx < $size; $idx ++) {
		if ($txt == "") {
			// nothing to do
		} else {
			$txt = "$txt AND ";
		}
		$txt = "$txt $keyA[$idx]" . "='" . $valueA [$idx] . "'";
	}
	return $txt;
}

/**
 * createSqlWhere
 *
 * @param
 *        	string or array $keyA
 * @param
 *        	sring or array $valueA
 * @return string
 */
function createSqlWhere($keyA, $valueA, $condition = "") {
	if (is_array ( $keyA )) {
		return createSqlWhereArray ( $keyA, $valueA, $condition );
	} else {
		return createSqlWhereID ( $keyA, $valueA, $condition );
	}
}

/**
 * mergeSqlWhere
 * @param string or param $condition1
 * @param string or param $condition2
 */
function mergeSqlWhere($condition1 = "", $condition2 = "") {
	$condition1 = getSqlWhere ( $condition1 );
	$condition2 = getSqlWhere ( $condition2 );
	
	if ($condition1){
		if ($condition2){
			return "$condition1 AND $condition2";
		}
		else{
			return $condition1;
		}
	}
	else{
		return $condition2;
	}
}

/**
 * getSqlWhere
 * 
 * @param
 *        	string or Array $condition
 * @return string
 */
function getSqlWhere($condition = "") {
	global $TABLE_WHERE_CONDITION;
	if (is_array ( $condition )) {
		if (isset ( $condition [$TABLE_WHERE_CONDITION] )) {
			return $condition [$TABLE_WHERE_CONDITION];
		}
		return "";
	}
	return $condition;
}

/**
 * showTableOneData
 *
 * @param URL $html
 *        	la page
 * @param
 *        	SQL result $Resultat le resultat de la requete
 * @param int $cpt
 *        	le row a lire
 * @param structure $param
 *        	structure affichage du row
 */
function showTableOneData($html, $Resultat, $cpt, $param) {
	global $COLUMNS_SUMMARY;
	global $TABLE_ROW_FIRST;
	global $TABLE_DELETE;
	global $TABLE_EDIT;
	global $TABLE_DELETE_BY_ROW;
	global $TABLE_EDIT_BY_ROW;
	
	// colonne index
	$columns = $param [$COLUMNS_SUMMARY];
	if ($param ['show_col_count'] == "yes") {
		if (array_key_exists ( $TABLE_ROW_FIRST, $param )) {
			$cpt2 = $cpt + $param [$TABLE_ROW_FIRST];
			echo "<td>$cpt2</td>";
		} else {
			echo "<td>$cpt</td>";
		}
	}
	
	// // parcours des colonnes
	// $i=0;
	// foreach ( $columns as $c ) {
	// $res = mysql_fieldname($Resultat, $i);
	// echo "<td>" . $res . "</td>";
	// $i++;
	// }
	
	// parcours des colonnes
	$r = 0;
	foreach ( $columns as $c ) {
		$res = mysqlResult ( $Resultat, $cpt, $c );
		echo "<td id='" . $c . "[" . $cpt . "]'>" . $res . "</td>";
		$resArray [$r] = $res;
		$r ++;
	}
	
	// ajouter les boutons d'action
	$idTable = getPrimaryKeyValue ( $Resultat, $cpt );
	//$pos = getPrimaryKeyPosition( $Resultat, $cpt );
	//echoTD("primary key [ $pos ]: $idTable");
	$infoForm = getInfoForm ( $param );
	// echo "<td>info form : $infoForm</td>";
	
	// bouton edit by id
	if (isset ( $param [$TABLE_EDIT] ) & ($param [$TABLE_EDIT] == "yes")) {
		showMiniForm ( $html, "", "edit", "edit", $idTable, "yes", $infoForm );
	}
	// bouton delete by id
	if (isset ( $param [$TABLE_DELETE] ) & ($param [$TABLE_DELETE] == "yes")) {
		showMiniForm ( $html, "", "delete", "delete", $idTable, "yes", $infoForm );
	}
	
	// bouton edit by row
	if (isset ( $param [$TABLE_EDIT_BY_ROW] ) & ($param [$TABLE_EDIT_BY_ROW] == "yes")) {
		showMiniFormArray ( $html, "", "edit", "edit !", $columns, $resArray, "yes", $infoForm );
	}
	
	// bouton delete by row
	if (isset ( $param [$TABLE_DELETE_BY_ROW] ) & ($param [$TABLE_DELETE_BY_ROW] == "yes")) {
		showMiniFormArray ( $html, "", "delete", "delete !", $columns, $resArray, "yes", $infoForm );
	}
}

/**
 * insertTableOneData
 *
 * @param URL $html
 *        	la page
 * @param
 *        	SQL result $Resultat le resultat de la requete
 * @param int $cpt
 *        	le row a lire
 * @param structure $param
 *        	structure affichage du row
 * @param string $idTable
 *        	id de l'element a afficher
 */
function editTableOneData($html, $Resultat, $cpt, $param, $idTable = "") {
	global $COLUMNS_SUMMARY;
	global $TABLE_ROW_FIRST;
	global $TABLE_FORM_NAME_INSERT;
	global $TABLE_WHERE_CONDITION;
	global $TABLE_WHERE_CONDITION_FOR_UPDATE;
	
	$formName = $param [$TABLE_FORM_NAME_INSERT];
	createForm ( $html, $formName );
	showFormIDElement ();
	$columns = $param [$COLUMNS_SUMMARY];
	
	// condition
	if (isset ( $param [$TABLE_WHERE_CONDITION] )) {
		$condition = $param [$TABLE_WHERE_CONDITION];
	} else {
		$condition = "";
	}
	
	// condition specifique pour le update
	// ignore la precedente (souvent du join qui ne passera pas dans du update
	if (isset ( $param [$TABLE_WHERE_CONDITION_FOR_UPDATE] )) {
		$condition = $param [$TABLE_WHERE_CONDITION_FOR_UPDATE];
	}
	
	// parcours des colonnes
	$idx = 0;
	foreach ( $columns as $c ) {
		echo "<tr> ";
		echo "<td align=\"right\">$c :</td>";
		editSqlRow ( $Resultat, $c, $cpt, $formName, $idx, $param );
		echo "</tr>";
		$idx ++;
	}
	
	echo "<tr>";
	echo "<td></td>";
	echo "<td align=\"right\">";
	// show id table in form
	if ($idTable) {
		showFormIdTableElement ( $idTable );
	}
	
	// show condition in form
	if ($condition) {
		global $CONDITION_GET;
		showFormHidden ( $CONDITION_GET, $condition );
	}
	
	global $SHOW_FORM_TRACE;
	if ($SHOW_FORM_TRACE == "yes") {
		$infoForm = checkInfoForm ( $param );
		showActionVariable ( ">>> 2. editTableOneData() form name : $form - infoForm : $infoForm <br>", $SHOW_FORM_TRACE );
	}
	
	// show others info form
	$infoform = checkInfoForm ( $param );
	echo "$infoform";
	
	// bouton action
	showFormAction ( "update" );
	showFormSubmit ( "update" );
	echo "</td></tr>";
	endForm ();
}

/**
 * editSqlRow
 *
 * @param
 *        	array or Sql request $Resultat
 * @param
 *        	integer index position or name $c colum index
 * @param integer $cpt
 *        	row line
 * @param string $formName        	
 * @param unknown $idxField        	
 */
function editSqlRow($Resultat, $c, $cpt, $formName, $idxField, $param = "") {
	if ($idxField < 0) {
		return;
	} else {
		$value = mysqlResult ( $Resultat, $cpt, $c );
		// echoTD( "$c($idxField)/$cpt : $value <br>");
		editSqlRowWithValue ( $Resultat, $c, $cpt, $formName, $idxField, $value, $param );
	}
}

/**
 * editSqlRowWithValue
 *
 * @param
 *        	object or array $Resultat sql result or array result
 * @param string $c
 *        	column name
 * @param integer $cpt
 *        	row positio
 * @param string $formName        	
 * @param
 *        	index or field name $idxField
 * @param string $value
 *        	default value
 */
function editSqlRowWithValue($Resultat, $c, $cpt, $formName, $idxField, $value, $param = "") {
	if ($idxField < 0) {
		$type = SQL_TYPE::SQL_STRING;
		$flags = "????";
		$name = $c;
		$size = "";
		$otherStyle="";
	} else {
		$type = mysqlFieldType ( $Resultat, $idxField );
		$flags = mysqlFieldFlags ( $Resultat, $idxField );
		$name = mysqlFieldName ( $Resultat, $idxField );
		$size = mysqlFieldTypeSize ( $Resultat, $idxField );
		$statusEdit = mysqlFieldStatus ( $Resultat, $idxField, $param );
		$otherStyle = mysqlFieldStyle ( $Resultat, $idxField, $cpt, $param );
		// echo "tool_db.editSqlRowWithValue() idxField: $idxField -- cpt: $cpt -- type: $type -- flag: $flags -- name: $name ";
		// echo "-- size: $size -- value: $value -- status : $statusEdit --<br>";
		
		// if ($cpt > - 1) {
		// // le nom du champ est suffixe par sa position
		// $name = "$name" . "[" . "$cpt" . "]";
		// }
	}
	showFieldForm1 ( $formName, $cpt, $name, $type, $flags, "no", "yes", $value, $size, $statusEdit, $otherStyle );
}

/**
 * insertTableOneData
 *
 * @param URL 				$html 		la page
 * @param SQL result 		$Resultat 	le resultat de la requete
 * @param structure 		$param      structure affichage du row
 * @param array[column name] $value 	selected value
 */
function insertTableOneData($html, $Resultat, /*$cpt,*/ $param, $value="") {
	global $COLUMNS_SUMMARY;
	global $TABLE_ROW_FIRST;
	global $TABLE_FORM_NAME_INSERT;
	
	$formName = $param [$TABLE_FORM_NAME_INSERT];
	createForm ( $html, $formName );
	
	// colonne index
	$columns = $param [$COLUMNS_SUMMARY];
	if ($param ['show_col_count'] == "yes") {
		echo "<td>[+]</td>";
	}
	
	// parcours des colonnes
	foreach ( $columns as $c ) {
		// echo "<td>$c</td>";
		$valueSelected="";
		$idxField = getSQLIndexFromName ( $Resultat, $c );
		
		//printArray($value,"insertTableOneData() value[] >>>>> ");
		if (isset($value[$c])){
			$valueSelected = $value[$c];
		}
		else if (isset($value[$idxField])){
			$valueSelected = $value[$idxField];
		}
		
		editSqlRowWithValue ( $Resultat, $c, - 1/*$cpt*/, $formName, $idxField, $valueSelected );
	}
	
	echo "<td>";
	showFormIDElement ();
	echo "" . getInfoForm ( $param );
	showFormAction ( "inserer" );
	showFormSubmit ( "inserer" );
	echo "</td>";
	endForm ();
}

/**
 *
 * @param unknown $resultat        	
 * @param unknown $colName        	
 * @return number
 */
function getSQLIndexFromName($resultat, $colName) {
	$num = mysqlNumFields ( $resultat );
	
	for($cpt = 0; $cpt < $num; $cpt ++) {
		// $name = mysql_field_name ( $resultat, $cpt );
		$name = mysqlFieldName ( $resultat, $cpt );
		if (strcasecmp ( $colName, $name ) == 0) {
			return $cpt;
		}
	}
	
	// //trace debug
	// showSQLAction("getSQLIndexFromName() column not found : [$colName]");
	// for($cpt = 0; $cpt < $num; $cpt ++) {
	// $name = mysqlFieldName ( $resultat, $cpt );
	// showSQLAction("..... [$cpt][$name]");
	// }
	// //end trace
	return - 1;
}

/**
 * createHeaderBaliseDiv
 * permet de creer une balise avec changement de visibilit�
 * il faut charger le script menu.js pour avoir le changement
 * ne pas oublier de fermer la balise!!! see endHeaderBaliseDiv
 *
 * @param string $idBalise
 *        	id de la balise div � creer
 * @param string $txt
 *        	texte avant la balise qui permet de changer la visibilit� de la balise
 */
function createHeaderBaliseDiv($idBalise, $txt = "") {
	if ($txt) {
		echo "<a href=\"\" onclick=\"javascript:inverseVisibiliteCookie('" . $idBalise . "'); return false;\">$txt</a>";
	}
	$var = "menu_" . $idBalise . "_style_display";
	// echo "$var : $_COOKIE[$var]";
	if (isset ( $_COOKIE [$var] )) {
		$visibility = $_COOKIE [$var];
	} else {
		$visibility = "";
	}
	createHeaderBaliseDivVisibility ( $idBalise, $visibility );
	// echo "<div id=\"$idBalise\" style=\"display:$_COOKIE[$var];\">";
}

/**
 * createHeaderBaliseDivVisibility
 * ne pas oublier de fermer la balise!!! see endHeaderBaliseDiv
 *
 * @param string $idBalise
 *        	balise englobant la visibilite
 * @param string $visibility
 *        	"" ou "none"
 */
function createHeaderBaliseDivVisibility($idBalise, $visibility) {
	echo "<div id=\"$idBalise\" style=\"display:$visibility;\">";
}

/**
 * permet de fermer la balise ouverte avec createHeaderBaliseDiv
 *
 * @param string $idBalise
 *        	basie � fermer
 */
function endHeaderBaliseDiv($idBalise) {
	echo "</div> <!-- close div $idBalise -->";
}

// ///////////////////////////////////// tablette PC /////////////////////////////////////////////////

/**
 * isMobile
 * detection des mobiles via HTTP_USER_AGENT
 *
 * @return boolean
 */
function isMobile() {
	// User agent
	$user_agent = $_SERVER ['HTTP_USER_AGENT'];
	// echo "user agent : $user_agent <br>";
	// test si il s'agit d'un mobile
	return preg_match ( '/iphone/i', $user_agent ) || preg_match ( '/android/i', $user_agent ) || preg_match ( '/blackberry/i', $user_agent );
}

/**
 * getPlateformClient
 *
 * @return string value plateformClient
 * @see $COOKIE_PLATEFORM_VALUE_PC $COOKIE_PLATEFORM_VALUE_MOBILE $COOKIE_PLATEFORM_VALUE_TABLETTE
 */
function getPlateformClient() {
	global $COOKIE_PLATEFORM_KEY;
	global $COOKIE_PLATEFORM_VALUE_CURRENT;
	global $COOKIE_PLATEFORM_VALUE_PC;
	global $COOKIE_PLATEFORM_VALUE_TABLETTE;
	global $COOKIE_PLATEFORM_VALUE_MOBILE;
	
	if (isset ( $COOKIE_PLATEFORM_VALUE_CURRENT ) && $COOKIE_PLATEFORM_VALUE_CURRENT) {
		// echo ">>> plateform : [$plateform]<br>";
		return $COOKIE_PLATEFORM_VALUE_CURRENT;
	}
	// if (isset($_SESSION[$COOKIE_PLATEFORM_KEY])){
	// return $_SESSION[$COOKIE_PLATEFORM_KEY];
	// }
	
	$plateform = getMyCookie ( $COOKIE_PLATEFORM_KEY );
	if (! isset ( $plateform ) || ! $plateform) {
		if (isMobile ()) {
			$plateform = $COOKIE_PLATEFORM_VALUE_MOBILE;
			// echo ">>> plateform mobile: [$plateform]<br>";
		} else {
			$plateform = $COOKIE_PLATEFORM_VALUE_PC;
			// echo ">>> plateform pc: [$plateform]<br>";
		}
	} else {
		// echo ">>> plateform isset: [$plateform]<br>";
	}
	
	// $_SESSION[$COOKIE_PLATEFORM_KEY]=$plateform;
	$COOKIE_PLATEFORM_VALUE_CURRENT = $plateform;
	// echo ">>> plateform : [$plateform]<br>";
	return $plateform;
}

/**
 * SelectCSS
 * selection le css en fonction de la plateform
 * pc.css ou mobile.css
 */
function selectCSS() {
	global $COOKIE_PLATEFORM_VALUE_PC;
	global $URL_ROOT_POINTAGE;
	$plateform = getPlateformClient ();
	if ($plateform == $COOKIE_PLATEFORM_VALUE_PC) {
		echo '<link rel="stylesheet" type="text/css" media="screen,projection" title="css pour fixe" href="' . $URL_ROOT_POINTAGE . '/css/pc.css" />';
	} else {
		echo '<link rel="stylesheet" type="text/css" media="screen,projection" title="css pour mobile" href="' . $URL_ROOT_POINTAGE . '/css/mobile.css" />';
	}
}

/**
 * showBandeauHeaderPage
 * permet d'afficher le bandeau de la page HTML
 *
 * @param string $txtAbout
 *        	texte � afficher dans le about
 */
function showBandeauHeaderPage($txtAbout = "CEGID") {
	global $COOKIE_PLATEFORM_VALUE_PC;
	
	$plateform = getPlateformClient ();
	if ($plateform == $COOKIE_PLATEFORM_VALUE_PC) {
		showBandeauHeaderPagePC ( $txtAbout );
	} else {
		showBandeauHeaderPageMobile ( $txtAbout );
	}
}

/**
 * showBandeauHeaderPagePC
 * permet d'afficher le bandeau de la page HTML pour les PC
 *
 * @param string $txtAbout
 *        	texte � afficher dans le about
 */
function showBandeauHeaderPagePC($txtAbout) {
	// global $URL_ROOT_POINTAGE;
	// $URL_IMAGES=$URL_ROOT_POINTAGE."/images";
	global $URL_IMAGES;
	
	echo "<div id=\"about\">";
	echo " <img id=\"baguette\" src=\"$URL_IMAGES/logoPointage.png\"  alt=\"\" title=\"Logo Serveur Pointage\" />";
	echo "<h2>";
	echo "$txtAbout";
	echo "</h2>";
	echo "</div> <!--  about -->";
}

/**
 * showBandeauMenuPageMobile
 * permet d'afficher l'icon des menu en mode mobile (en haut a droite)
 */
function showBandeauMenuPageMobile() {
	// // le click sur le lien provoque l'affichage de la page "menu_mobile.php"
	// global $ACTION_GET;
	// $argument=propagateArguments();
	
	// echo "<a href=\"/menu_mobile.php$argument\" >";
	// echo " <img id=\"baguette\" src=\"/images/mobile_menu.png\" alt=\"\" title=\"Menu\" />";
	// echo "</a>";
	
	// affiche le bouton de menu
	// celui ci inverse la visibilite du block block_menu => affiche ou non le menu
	$txt = " <img id=\"baguette\" src=\"$URL_IMAGES/mobile_menu.png\" alt=\"\" title=\"Menu\" />";
	echo "<a href=\"\" onclick=\"javascript:inverseVisibilite('block_menu'); return false;\">$txt</a>";
}

/**
 * showBandeauHeaderPagePC
 * permet d'afficher le bandeau de la page HTML pour les PC
 *
 * @param string $txtAbout
 *        	texte � afficher dans le about
 */
function showBandeauHeaderPageMobile($txtAbout) {
	echo "<div id=\"about\">";
	showBandeauMenuPageMobile ();
	echo "<h2>";
	echo "$txtAbout";
	echo "</h2>";
	echo "</div> <!--  about -->";
}

/**
 * showSommation
 *
 * @param string $table
 *        	table id
 * @param string $colsList
 *        	liste colonnes sur laquel a lieu la sommation verticale
 * @param string $colsSumList
 *        	list des colonnes de sommation horizontal
 * @param string $colsForSum
 *        	colonnes sur laquelle ont lieux les sommations horizontale
 */
function showSommation($table, $colsList, $colsSumList, $colsForSum) {
	echo "<script type=\"text/javascript\">";
	// echo "test();";
	echo "sommeColonneRowHTMLTable(document.getElementById('$table'),'$colsList', '$colsSumList', '$colsForSum');";
	echo "</script>";
}

// echo "include tool_db.php end<br>";

?>