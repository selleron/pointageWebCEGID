<?php 
//echo "include requetes_db.php<br>";
$REQUETES_DB_PHP="loaded";

if( ! isset($TOOL_DB_PHP)){
	include_once("tool_db.php");
}

$SQL_TABLE_REQUETES="requetes";

$SQL_COL_REQUETES_ID="ID";
$SQL_COL_REQUETES_NAME="NAME";
$SQL_COL_REQUETES_DESCRIPTION="DESCRIPTION";
$SQL_COL_REQUETES_SQL_REQUEST="SQL_REQUEST";



function actionRequete( $html=""){
	$action = getActionGet();
	//echo "action : $action ...................<br>";
	if ($action=="executeRequest"){
		$idRequete = getDocumentName();
		actionExecuteRequeteParID($idRequete, $html);
	}
	else if ($action=="testRequest"){
		if(isset($_POST['testRequestExecute'])){
			actionTestRequest($html);
		}
		else if(isset($_POST['saveRequestExecute'])){
			actionSauverRequest($html);
		}
	}
	else if ($action=="editRequest"){
		$idRequete = getDocumentName();
		actionEditRequeteParID($idRequete, $html);
	}
	else if ($action=="deleteRequest"){
		$idRequete = getDocumentName();
		actionDeleteRequeteParID($idRequete, $html);
	}
	else if ($action==""){
		//nothing to do
	}
	else {
		echo "unknown action : $action <br>";
	}
}

/**
 * actionTestRequest
 * @param string $html
 */
function actionTestRequest($html=""){
	global $SQL_COL_REQUETES_SQL_REQUEST;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;
	
	//recuperation des param�tres
	$idRequete = getDocumentName();
	$name = getURLVariable("$SQL_COL_REQUETES_NAME");
	$description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
	$sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");
		
	//execute la requete
	actionRequeteSql($sqlTxt);
	
	echo"<br><br>";
	
	//reaffiche l'edition
	showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html);
}


/**
 * actionSauverRequest
 * @param string $html
 */
function actionSauverRequest($html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;

	//recuperation des param�tres
	$idRequete = getDocumentName();
	$name = getURLVariable("$SQL_COL_REQUETES_NAME");
	$description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
	$sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");

	//faire la sauvegarde 
	//echo "action to do saveRequestExecute <br>";
	$request = "REPLACE INTO `$SQL_TABLE_REQUETES` (`$SQL_COL_REQUETES_ID`, `$SQL_COL_REQUETES_NAME`, 
	`$SQL_COL_REQUETES_DESCRIPTION`, `$SQL_COL_REQUETES_SQL_REQUEST`) VALUES
	('$idRequete', '$name', '$description', '$sqlTxt')";
	
	showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);
	
	echo "<br><br>";

	//reaffiche l'edition
	showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html);
}


/**
 * getRequeteByID
 * @param string $idRequete
 * @return string SQL Request
 */
function getRequeteByID($idRequete, $table=""){
    global $SQL_COL_REQUETES_ID;
    global $SQL_COL_REQUETES_SQL_REQUEST;
    
    
    if ($table == ""){
        global $SQL_TABLE_REQUETES;
        $table = $SQL_TABLE_REQUETES;
    }
    
    $request = "SELECT $SQL_COL_REQUETES_ID,  $SQL_COL_REQUETES_SQL_REQUEST
	FROM `$table`
	WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
    
    //showSQLAction($request);
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    
    $Compteur=0;
    $sql = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
    
    return $sql;
}



/**
 * actionExecuteRequeteParID
 * @param string $idRequete
 * @param string $html
 */
function actionExecuteRequeteParID($idRequete, $html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	global $TABLE_EXPORT_CSV;
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID,  $SQL_COL_REQUETES_SQL_REQUEST
	FROM `$SQL_TABLE_REQUETES`
	WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	
	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);
	//$subParam[$TABLE_EXPORT_CSV] = "yes";
	
	for ($Compteur=0 ; $Compteur<mysqlNumrows($Resultat) ; $Compteur++){
		$sql = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
		actionRequeteSql($sql, $html, $subParam);
	}
}



/**
 * Execute la requete contenu dans une requete
 * @param string        $request	sql request
 * @param string URL	$html		page de lien
 * @param sql param     $subParam   sub param à utiliser
 * @param string        $closeTable "yes|no" default yes
 * @return String[]     sql param
 */

function actionRequeteSql($request, $html="", $subParam="", $closeTable=""){
	//construction parameters
	$param = createDefaultParamSql();
	$param = updateParamSqlWithOrder($param);
	$param = updateParamSqlWithLimit($param);
	
	$param = updateParamSqlWithSubParam($param, $subParam);
	
	$request = createRequestOrderByWithParam($request, $param);
	
	//execute request
	showSQLAction($request);
	echo"<br><br>";
 	$Resultat = mysqlQuery($request);
 	showSQLError("", $request);
 	$param = updateParamSqlWithResult($param, $Resultat);
 	
 	//show result
	//$param[$TABLE_SIZE]=1100;
	showLimitBar($param);
	showTableHeader($param);
	showTableData($param, $html, $Resultat, $closeTable);
	
	return $param;
 	}




/**
 * showFormulaireRequete
 * Affiche un formulaire pour executer la requete design�e par son id
 * une requete Sql est execut�e
 * @param String $idRequete  sql id de la table requetes : peut etre � ""
 * @param URL $html : peut etre a "" 
 */
function showFormulaireRequete($idRequete="", $html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_NAME;
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID, $SQL_COL_REQUETES_NAME 
	FROM `$SQL_TABLE_REQUETES`";

	if ($idRequete){
		$request = $request."WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	}
	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);

	$num=mysqlNumrows($Resultat);
	//if ($num>1){
		echo "<table>";
	//}
	for ($Compteur=0 ; $Compteur<$num ; $Compteur++){
		$id = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_ID);
		$name = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_NAME);
		showFormulaireRequeteByName($id, $name, $html);
	}
	//if ($num>1){
		echo "</table>";
	//}
}
	
/**
 * showFormulaireRequete
 * Affiche un formulaire pour executer la requete design�e par son id et son id
 * <name> [execute]
 * @param unknown $idRequete
 * @param unknown $name
 * @param unknown $html   peut etre ""
 */
function showFormulaireRequeteByName($idRequete, $name, $html=""){
	if (!isset($html) || $html==""){
		$html=getCurrentPageName();
	}
	
	echo"<tr>";
	//execute
	echo "
		<form method=\"get\" action=\"$html\">
		<td>[$name]</td>";
	showFormAction("executeRequest");
	showFormIDElement();
	showFormDocumentElementValue($idRequete);
	echo"
		<td>	
		<input type=\"submit\"  value=\"execute\" >
  		</td>
		</form>";
	//edit
	echo "
		<form method=\"get\" action=\"$html\">";
	showFormAction("editRequest");
	showFormIDElement();
	showFormDocumentElementValue($idRequete);
	echo"
		<td>	
		<input type=\"submit\"  value=\"editer\" >
  		</td>
		</form>";
	//delete
	echo "
		<form method=\"get\" action=\"$html\">";
	showFormAction("deleteRequest");
	showFormIDElement();
	showFormDocumentElementValue($idRequete);
	echo"
		<td>	
		<input type=\"submit\"  value=\"supprimer\" >
  		</td>
		</form>";
	echo"</tr>";
}


/**
 * showFormulaireEditRequete
 * @param string $idRequete 
 * @param string $name
 * @param string $description
 * @param string $sqlTxt
 * @param string $html
 */
function showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html=""){
	if (!isset($html) || $html==""){
		$html=getCurrentPageName();
	}

	global $ID_GET;
	global $DOCUMENT_NAME_GET;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	
	
	echo"<table>";
	//execute
	echo "<form method=\"post\" action=\"$html\">";
	
	echo"
	<tr>
	<td>identifiant</td>
	<td><INPUT type=\"text\" size=\"50\" name=\"$DOCUMENT_NAME_GET\" value=\"$idRequete\" >  </td>
	</tr>
	
	<tr>
	<td>nom</td>
	<td><INPUT type=\"text\" size=\"50\" name=\"$SQL_COL_REQUETES_NAME\" value=\"$name\" > </td>
	</tr>
		
	<tr>
	<td>description</td>
	<td><TEXTAREA rows=\"2\" cols=\"70\" name=\"$SQL_COL_REQUETES_DESCRIPTION\" >$description</TEXTAREA></td>
	</tr>
		
	<tr>
	<td>requete</td>
	<td><TEXTAREA rows=\"7\" cols=\"70\" name=\"$SQL_COL_REQUETES_SQL_REQUEST\"  >$sqlTxt</TEXTAREA></td>
	</tr>
		
	";
	
	echo "
	<tr>
	<td></td>
	<td>	
		<input type=\"submit\"  name=\"testRequestExecute\" value=\"execute\" > 
		<input type=\"submit\"  name=\"saveRequestExecute\" value=\"sauver\" > 
	</td>
	</tr>";
	showFormIDElement();
	showFormAction("testRequest");
	echo"</form>";
	echo"</table>";
}

/**
 * actionEditRequeteParID : affiche le formulaire d'edition d'une requete
 * @param string $idRequete
 * @param string $html
 */
function actionEditRequeteParID($idRequete, $html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	
	if ($idRequete== ""){
		showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
		return;
	}
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID, $SQL_COL_REQUETES_NAME, $SQL_COL_REQUETES_DESCRIPTION, $SQL_COL_REQUETES_SQL_REQUEST
	  FROM `$SQL_TABLE_REQUETES`
	  WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	
	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);

	$num=mysqlNumrows($Resultat);
	if($num>0){
		$Compteur=0;
		$id = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_ID);
		$name = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_NAME);
		$description = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_DESCRIPTION);
		$sqlTxt = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
		showFormulaireEditRequete($id, $name, $description, $sqlTxt, $html);
	}
}

/**
 * actionDeleteRequeteParID : suppression d'une requete
 * @param string $idRequete
 * @param string $html
 */
function actionDeleteRequeteParID($idRequete, $html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;


	if ($idRequete== ""){
		showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
		return;
	}


	$request = "DELETE 	FROM `$SQL_TABLE_REQUETES`
	WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";

	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);
}


?>