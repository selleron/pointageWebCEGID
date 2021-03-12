<?php 
//echo "include requetes_db.php<br>";
$REQUETES_DB_PHP="loaded";



include_once("basic.php");
include_once("tool_db.php");
include_once("table_db.php");
include_once("../configuration/labelAction.php");

$SQL_TABLE_REQUETES="requetes";
$TABLE_CEGID_REQUETE      = "cegid_requetes";


$SQL_COL_REQUETES_ID="ID";
$SQL_COL_REQUETES_NAME="NAME";
$SQL_COL_REQUETES_VISIBLE="VISIBLE";
$SQL_COL_REQUETES_DESCRIPTION="DESCRIPTION";
$SQL_COL_REQUETES_SQL_REQUEST="SQL_REQUEST";
$SQL_COL_REQUETES_PARAM_AEREA="REQUEST_PARAM";


/**
 * actionRequete
 * action sur les requetes : saveRequestExecute, testRequest, ...
 * @param string $html
 * @param string $warningOnUnknownAction
 */
function actionRequete( $html="", $warningOnUnknownAction = "yes"){
	$action = getActionGet();
	//echo "action : $action ...................<br>";
	if ($action=="executeRequest"){
		$idRequete = getDocumentName();
		$description = getDescriptionRequeteByID($idRequete);
		echo "<p>Description : $description</p>";
		actionExecuteRequeteParID($idRequete, $html);
	}
	else if ($action=="testRequest"){
		if(isset($_POST['testRequestExecute'])){
			actionTestRequest($html);
			//a methode ci dessous ne fonctionne pas avec les scripts
			//actionExecScriptRequest($html);
		}
		else if(isset($_POST['saveRequestExecute'])){
		    actionSauverRequest($html);
		}
		else if(isset($_POST[LabelAction::ActionExport])){
		    actionExportRequest($html);
		}
	}
	else if ($action=="editRequest"){
		$idRequete = getDocumentName();
		$description = getDescriptionRequeteByID($idRequete);
		echo "<p>Description : $description</p>";
		actionEditRequeteParID($idRequete, $html);
	}
	else if ($action=="deleteRequest"){
	    $idRequete = getDocumentName();
	    actionDeleteRequeteParID($idRequete, $html);
	}
	else if ($action==LabelAction::ActionArchive){
	    $idRequete = getDocumentName();
	    actionArchiveRequeteParID($idRequete, $html);
	}
	else if ($action==LabelAction::ActionVisible){
	    $idRequete = getDocumentName();
	    actionVisibleRequeteParID($idRequete, $html);
	}
	else if ($action==""){
		//nothing to do
	}
	else {
	    if ($warningOnUnknownAction == "yes"){
		   echo "unknown action : $action <br>";
	    }
	}
}

/**
 * actionTestRequest
 *  exec request
 *     +
 *  show request parameters
 * @param string $html
 */
function actionTestRequest($html=""){
    //execute request
    $sqlTxt2 = getSqlTestRequest($html);
	actionRequeteSql($sqlTxt2);
	
    echo"<br>";
	
	actionshowFormulaireEditRequete($html);
}

/**
 * actionshowFormulaireEditRequete
 * affiche les parametre de la requete
 * @param string $html
 */
function actionshowFormulaireEditRequete($html=""){
    //reaffiche l'edition
    global $SQL_COL_REQUETES_SQL_REQUEST;
    global $SQL_COL_REQUETES_NAME;
    global $SQL_COL_REQUETES_DESCRIPTION;
    global $SQL_TABLE_REQUETES;
    global $SQL_COL_REQUETES_PARAM_AEREA;
    //recuperation des parametres
    $idRequete = getDocumentName();
    $name = getURLVariable("$SQL_COL_REQUETES_NAME");
    $description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
    $sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");
    $paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
    showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html, $paramFormulaire);
}


/**
 * getSqlTestRequest
 * retourne la requet a executer avec les subtitutions faites 
 * @param string $html
 * @return $txt
 */
function getSqlTestRequest($html=""){
    global $SQL_COL_REQUETES_SQL_REQUEST;
    global $SQL_COL_REQUETES_NAME;
    global $SQL_COL_REQUETES_DESCRIPTION;
    global $SQL_TABLE_REQUETES;
    global $SQL_COL_REQUETES_PARAM_AEREA;
    
    //recuperation des parametres
    $idRequete = getDocumentName();
    $name = getURLVariable("$SQL_COL_REQUETES_NAME");
    $description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
    $sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");
    $paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
    
    //fait les remplacement ${XXX} pr le getURL(XXX)
    $othersKeyValue = getArrayRequete($SQL_TABLE_REQUETES);
    $sqlTxt2 = replaceVariableURLByGet($sqlTxt, $othersKeyValue);
    
    
    //global $TRACE_INFO_GESTION_REQUEST;
    //showActionVariable("getSqlTestRequest() : ".$sqlTxt2, $TRACE_INFO_GESTION_REQUEST);
    
    return $sqlTxt2;
}

/**
 * actionExportRequest
 * @param string $html can be empty
 */
function actionExportRequest($html=""){
    //showSQLAction("actionExportRequest()");
    
    //preparation requete export
    $form_name = getDocumentName();
    $sqlTxt2 = getSqlTestRequest($html);
    $param = createDefaultParamSql ( $sqlTxt2 );
    $param = updateTableParamSql ( $param, $form_name );
    $oldValue = setActionGet(LabelAction::ActionExport);
    
    //execute request
    exportCSVTableByGet($param);
    
    
    //affichage request
    actionTestRequest($html);
}



// /**
//  * actionExecScriptRequest
//  * @param string $html
//  */
// function actionExecScriptRequest($html=""){
//     global $SQL_COL_REQUETES_SQL_REQUEST;
//     global $SQL_COL_REQUETES_NAME;
//     global $SQL_COL_REQUETES_DESCRIPTION;
//     global $SQL_TABLE_REQUETES;
//     global $SQL_COL_REQUETES_PARAM_AEREA;
    
//     //recuperation des parametres
//     $idRequete = getDocumentName();
//     $name = getURLVariable("$SQL_COL_REQUETES_NAME");
//     $description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
//     $sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");
//     $paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
    
//     //fait les remplacement ${XXX} pr le getURL(XXX)
//     $othersKeyValue = getArrayRequete($SQL_TABLE_REQUETES);
//     $sqlTxt2 = replaceVariableURLByGet($sqlTxt, $othersKeyValue);
    
    
//     //execute la requete
//     showSQLAction($sqlTxt2);
//     $stmt = mysqlPrepare($sqlTxt2);
//     mysqlExecute($stmt);
//     showSQLError("requetes_db.actionExecScriptRequest() cette methode ne fonctionne pas<br>.", $request);
    
//     echo"<br><br>";
    
//     //reaffiche l'edition
//     showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html, $paramFormulaire);
// }



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
	global $SQL_COL_REQUETES_PARAM_AEREA;

	//recuperation des param�tres
	$idRequete = getDocumentName();
	$name = getURLVariable("$SQL_COL_REQUETES_NAME");
	$description = getURLVariable("$SQL_COL_REQUETES_DESCRIPTION");
	$sqlTxt = getURLVariable("$SQL_COL_REQUETES_SQL_REQUEST");
	$paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
	
	//on va utilise des quotes, il faut donc doubler les quotes de la requete
	$sqlTxt2 = formatStringWithQuote($sqlTxt);
	$description2 = formatStringWithQuote($description);
	$name2 = formatStringWithQuote($name);
	
	//faire la sauvegarde 
	//echo "action to do saveRequestExecute <br>";
	$request = "REPLACE INTO `$SQL_TABLE_REQUETES` (`$SQL_COL_REQUETES_ID`, `$SQL_COL_REQUETES_NAME`, 
	`$SQL_COL_REQUETES_DESCRIPTION`, `$SQL_COL_REQUETES_SQL_REQUEST`, `$SQL_COL_REQUETES_PARAM_AEREA`) VALUES
	('$idRequete', '$name2', '$description2', '$sqlTxt2', '$paramFormulaire')";
	
	showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);
	
	echo "<br><br>";

	//reaffiche l'edition
	showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html, $paramFormulaire);
}




/**
 * affiche une requete stockee dans la table des requetes CEGID
 * @param string $idRequest
 * @param string $formname
 * @param string $idTable
 */
function showDescriptionRequeteCEGID($idRequest="", $formname="", $idTable = "") {
    global $TABLE_CEGID_REQUETE;
    
    if ($idTable == ""){
        $idTable = $TABLE_CEGID_REQUETE;
    }
    
    //recuperation de la requete
    $description = getDescriptionRequeteByID($idRequest, $idTable);
    echo "<p>$description</p>";
    
}


/**
 *
 * @param string $idRequest
 * @return string request sql from $TABLE_CEGID_REQUETE
 */
function getRequeteCAByID($idRequest){
    global $TABLE_CEGID_REQUETE;
    global $ID_REQUETE_SQL_CA_PREVISIONEL;
    
    if ($idRequest==""){
        $idRequest=$ID_REQUETE_SQL_CA_PREVISIONEL;
    }
    
    //positionnement $year
    $year = getURLYear();
    setURLYear($year);
    
    $request = getRequeteByID($idRequest, $TABLE_CEGID_REQUETE);
    return $request;
}





/**
 * getDescriptionRequeteByID
 * @param string $idRequete
 * @return string SQL Request
 */
function getDescriptionRequeteByID($idRequete, $table=""){
    global $SQL_COL_REQUETES_ID;
    global $SQL_COL_REQUETES_DESCRIPTION;
    
    
    if ($table == ""){
        global $SQL_TABLE_REQUETES;
        $table = $SQL_TABLE_REQUETES;
    }
    
    $request = "SELECT $SQL_COL_REQUETES_ID,  $SQL_COL_REQUETES_DESCRIPTION
	FROM `$table`
	WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
    
    //showSQLAction($request);
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    
    $Compteur=0;
    $sql = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_DESCRIPTION);
    

    //fait les remplacement ${XXX} pr le getURL(XXX)
    $othersKeyValue = getArrayRequete($table);
    $sql = replaceVariableURLByGet($sql, $othersKeyValue);
    
    
    $sql = repair_accent($sql);
    //retourne la description de la  requete
    return $sql;
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
    
    
    //fait les remplacement ${XXX} pr le getURL(XXX)
    $othersKeyValue = getArrayRequete($table);
    $sql = replaceVariableURLByGet($sql, $othersKeyValue);
    
    //retourne la requete
    return $sql;
}


/**
 * getArrayRequete
 * cree un tableau (cle , valeur) de la table des requetes
 * @param String $table request table (not NULL)
 * @return array data[SQL_COL_REQUETES_ID => SQL_COL_REQUETES_SQL_REQUEST ]
 */
function getArrayRequete($table){
    global $SQL_COL_REQUETES_ID;
    global $SQL_COL_REQUETES_SQL_REQUEST;
    
    $result = array();
    
    $request = "SELECT $SQL_COL_REQUETES_ID,  $SQL_COL_REQUETES_SQL_REQUEST
	FROM `$table`";
    
    //showSQLAction($request);
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    
    $nb = mysqlNumrows($Resultat);
    for($Compteur=0; $Compteur<$nb ; $Compteur++){
        $sql = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
        $key = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_ID);
        $result[$key]=$sql;
    }
    
    //printArray($result,"getArrayRequete - $table  - ");
    return $result;
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
	
	$subParam="";
	$request = "SELECT $SQL_COL_REQUETES_ID,  $SQL_COL_REQUETES_SQL_REQUEST
	FROM `$SQL_TABLE_REQUETES`
	WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	
	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);
	//$subParam[$TABLE_EXPORT_CSV] = "yes";
	
	//fait les remplacement ${XXX} pr le getURL(XXX)
	$othersKeyValue = getArrayRequete($SQL_TABLE_REQUETES);
	
	
	for ($Compteur=0 ; $Compteur<mysqlNumrows($Resultat) ; $Compteur++){
		$sql = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
		//showSQLAction("actionExecuteRequeteParID -1- $sql");
		$sql2 = replaceVariableURLByGet($sql, $othersKeyValue);
		//showSQLAction("actionExecuteRequeteParID -2- $sql2");
		
		actionRequeteSql($sql2, $html, $subParam);
	}
}



/**
 * Execute la requete contenu dans une requete
 * 
 * prise en compte de order
 * prise en compte de limit
 * 
 * @param string        $request	sql request
 * @param string URL	$html		page de lien
 * @param sql param     $subParam   sub param à utiliser
 * @param string        $closeTable "yes|no" default yes
 * @param string        $useLimit   "yes|no" default yes
 * @return String[]     sql param
 * 
 */

function actionRequeteSql($request, $html="", $subParam="", $closeTable="yes", $useLimit="yes"){
	//construction parameters
	$param = createDefaultParamSql();
	$param = updateParamSqlWithOrder($param);
	if ( $useLimit == "yes" ){
	  $param = updateParamSqlWithLimit($param);
	}
	
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
 	if ( $useLimit == "yes" ){
 	    showLimitBar($param);
 	}
	showTableHeader($param);
	showTableData($param, $html, $Resultat, $closeTable);
	
	return $param;
 	}




/**
 * showFormulaireRequete
 * Affiche un formulaire pour executer la requete designée par son id
 * une requete Sql est executée
 * @param String $idRequete  sql id de la table requetes : peut etre à ""
 * @param String URL $html : peut etre a "" 
 * @param string $requestCondition : autre condition par exemple pour la visibilité
*/
function showFormulaireRequete($idRequete="", $html="", $requestCondition=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_VISIBLE;
	global $SQL_COL_REQUETES_NAME;
	global $TRACE_INFO_GESTION_REQUEST;
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID, $SQL_COL_REQUETES_NAME, $SQL_COL_REQUETES_VISIBLE 
	FROM `$SQL_TABLE_REQUETES` WHERE 1 ";

	if ($idRequete){
	    $request = $request." AND `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	}
	if ($requestCondition){
	    $request = $request." AND $requestCondition ";
	}
	//showSQLAction($request);
	showActionVariable($request, $TRACE_INFO_GESTION_REQUEST);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);

	$num=mysqlNumrows($Resultat);
	//if ($num>1){
		echo "<table>";
	//}
	for ($Compteur=0 ; $Compteur<$num ; $Compteur++){
		$id = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_ID);
		$name = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_NAME);
		$visible = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_VISIBLE);
		showFormulaireRequeteByName($id, $name, $visible, $html);
	}
	//if ($num>1){
		echo "</table>";
	//}
}
	
/**
 * showFormulaireRequete
 * Affiche un formulaire pour executer la requete designée par son id et son id
 * <name> [execute]
 * @param string $idRequete
 * @param string $name
 * @param string url $html   peut etre ""
 */
function showFormulaireRequeteByName($idRequete, $name, $visible, $html=""){
	if (!isset($html) || $html==""){
		$html=getCurrentPageName();
	}
	
	echo"<tr>";
	
	//execute
	echo "
		<form method=\"get\" action=\"$html\">
        <!-- id : $idRequete -->
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

	global $CONDITION_VALUE_VISIBLE;
	global $CONDITION_VALUE_ARCHIVE;
	
	if ($visible == "$CONDITION_VALUE_VISIBLE"){
	    //delete
	    echo "
		<form method=\"get\" action=\"$html\">";
	    showFormAction(LabelAction::ActionArchive);
	    showFormIDElement();
	    showFormDocumentElementValue($idRequete);
	    echo"
		<td></td><td></td><td></td>
		<td>
		<input type=\"submit\"  value=\"".LabelAction::ActionArchive."\" >
  		</td>
		</form>";
	}
	
	if ($visible == "$CONDITION_VALUE_ARCHIVE"){
	    //delete
	    echo "
        <td></td><td></td>
		<form method=\"get\" action=\"$html\">";
	    showFormAction(LabelAction::ActionVisible);
	    showFormIDElement();
	    showFormDocumentElementValue($idRequete);
	    echo"
		<td>
		<input type=\"submit\"  value=\"".LabelAction::ActionVisible."\" >
  		</td>
		</form>";
	}
	
	
	//fin de ligne
	echo"</tr>";
}


/**
 * showFormulaireEditParamRequete
 *  [execution] [sauvegarde] [export]
 * @param string $idRequete
 * @param string $name
 * @param string $description
 * @param string $sqlTxt
 * @param string $html
 */
function showFormulaireEditParamRequete($idRequete, $sqlTxt, $html="", $paramFormulaire=""){
    if (!isset($html) || $html==""){
        $html=getCurrentPageName();
    }
    
    global $ID_GET;
    global $DOCUMENT_NAME_GET;
    global $SQL_COL_REQUETES_NAME;
    global $SQL_COL_REQUETES_DESCRIPTION;
    global $SQL_COL_REQUETES_SQL_REQUEST;
    global $SQL_COL_REQUETES_PARAM_AEREA;
    
    if ($paramFormulaire==""){
        $paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
    }
    
    echo"<table>";
    //execute
    echo "<form method=\"post\" action=\"$html\">";
    showFormHidden($DOCUMENT_NAME_GET, $idRequete);
    
//     echo"
// 	<tr>
// 	<td>nom</td>
// 	<td><INPUT type=\"text\" size=\"50\" name=\"$SQL_COL_REQUETES_NAME\" value=\"$name\" > </td>
// 	</tr>
//     ";
    
//    echo"
//	<tr>
//	<td>description</td>
//	<td><TEXTAREA rows=\"2\" cols=\"70\" name=\"$SQL_COL_REQUETES_DESCRIPTION\" >$description</TEXTAREA></td>
//	</tr>
//    ";
    
    //champ parameters

//    echo"
//	<tr>
//	<td>parametres</td>
//	<td><TEXTAREA rows=\"2\" cols=\"70\" name=\"$SQL_COL_REQUETES_PARAM_AEREA\"  >$paramFormulaire</TEXTAREA></td>
//	</tr>
//    ";
    
    //ajout des elements de formulaires dynamiques
    echo "$paramFormulaire";
    
    //champ requete
    //gere automatiquement les probl�mes de double-quote dans la requete.
        echo"
    	<tr>
    	<td>requete</td>
    	<td><TEXTAREA rows=\"1\" cols=\"70\" name=\"$SQL_COL_REQUETES_SQL_REQUEST\"  >$sqlTxt</TEXTAREA></td>
    	</tr>
        ";
    
    //les actions
    beginTableRow();
    beginTableCell();
    endTableCell();
    beginTableCell();
    
    showFormSubmit("execute" , "testRequestExecute");
    //    echoSpace(1);
//    showFormSubmit("sauver"  , "saveRequestExecute");
//    echoSpace(2);
//    showFormSubmit(LabelAction::ActionExport  , LabelAction::ActionExport);
    endTableCell();
    endTableRow();
    
    //fin du formulaire
    showFormIDElement();
    showFormAction("testRequest");
    echo"</form>";
    echo"</table>";
}


/**
 * showFormulaireEditRequete
 *  [execution] [sauvegarde] [export]
 * @param string $idRequete 
 * @param string $name
 * @param string $description
 * @param string $sqlTxt
 * @param string $html
 */
function showFormulaireEditRequete($idRequete, $name, $description, $sqlTxt, $html="", $paramFormulaire=""){
	if (!isset($html) || $html==""){
		$html=getCurrentPageName();
	}

	global $ID_GET;
	global $DOCUMENT_NAME_GET;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	global $SQL_COL_REQUETES_PARAM_AEREA;
	
	if ($paramFormulaire==""){
	    $paramFormulaire = getURLVariable($SQL_COL_REQUETES_PARAM_AEREA);
	}
	
	echo"<table>";
	//execute
	echo "<form method=\"post\" action=\"$html\">";
	
	echo"
	<tr>
	<td>identifiant</td>
	<td><INPUT type=\"text\" size=\"50\" name=\"$DOCUMENT_NAME_GET\" value=\"$idRequete\" >  </td>
	</tr>
    ";
	
	echo"
	<tr>
	<td>nom</td>
	<td><INPUT type=\"text\" size=\"50\" name=\"$SQL_COL_REQUETES_NAME\" value=\"$name\" > </td>
	</tr>
    ";
	
	echo"	
	<tr>
	<td>description</td>
	<td><TEXTAREA rows=\"2\" cols=\"70\" name=\"$SQL_COL_REQUETES_DESCRIPTION\" >$description</TEXTAREA></td>
	</tr>
    ";
	
	//champ parameters
	echo"		
	<tr>
	<td>parametres</td>
	<td><TEXTAREA rows=\"2\" cols=\"70\" name=\"$SQL_COL_REQUETES_PARAM_AEREA\"  >$paramFormulaire</TEXTAREA></td>
	</tr>
    ";
	
	//ajout des elements de formulaires dynamiques
	echo "$paramFormulaire";
	
	//champ requete
	echo"
	<tr>
	<td>requete</td>
	<td><TEXTAREA rows=\"7\" cols=\"70\" name=\"$SQL_COL_REQUETES_SQL_REQUEST\"  >$sqlTxt</TEXTAREA></td>
	</tr>
	";
	
    //les actions	
	beginTableRow();
	beginTableCell();
	endTableCell();
	beginTableCell();
	showFormSubmit("execute" , "testRequestExecute");
	echoSpace(1);
	showFormSubmit("sauver"  , "saveRequestExecute");
	echoSpace(2);
	showFormSubmit(LabelAction::ActionExport  , LabelAction::ActionExport);
	endTableCell();
	endTableRow();
	
	//fin du formulaire
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
	global $SQL_COL_REQUETES_PARAM_AEREA;
	
	if ($idRequete== ""){
		showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
		return;
	}
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID, $SQL_COL_REQUETES_NAME, $SQL_COL_REQUETES_DESCRIPTION, $SQL_COL_REQUETES_SQL_REQUEST, $SQL_COL_REQUETES_PARAM_AEREA
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
		$sqlParam = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_PARAM_AEREA);
		
		showFormulaireEditRequete($id, $name, $description, $sqlTxt, $html, $sqlParam);
	}
}

/**
 * /**
 * actionEditParamRequeteParID : affiche le formulaire d'edition des param�tres d'une requete
 * @param string $idRequete
 * @param string $html
 */
function actionEditParamRequeteParID($idRequete, $html=""){
	global $SQL_TABLE_REQUETES;
	global $SQL_COL_REQUETES_ID;
	global $SQL_COL_REQUETES_NAME;
	global $SQL_COL_REQUETES_DESCRIPTION;
	global $SQL_COL_REQUETES_SQL_REQUEST;
	global $SQL_COL_REQUETES_PARAM_AEREA;
	
	if ($idRequete== ""){
		showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
		return;
	}
	
	
	$request = "SELECT $SQL_COL_REQUETES_ID, $SQL_COL_REQUETES_NAME, $SQL_COL_REQUETES_DESCRIPTION, $SQL_COL_REQUETES_SQL_REQUEST, $SQL_COL_REQUETES_PARAM_AEREA
	  FROM `$SQL_TABLE_REQUETES`
	  WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
	
	//showSQLAction($request);
	$Resultat = mysqlQuery($request);
	showSQLError("", $request);

	$num=mysqlNumrows($Resultat);
	if($num>0){
		$Compteur=0;
		$id = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_ID);
		//$name = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_NAME);
		//$description = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_DESCRIPTION);
		$sqlTxt = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_SQL_REQUEST);
		$sqlParam = mysqlResult($Resultat , $Compteur , $SQL_COL_REQUETES_PARAM_AEREA);
		
		showFormulaireEditParamRequete($id, $sqlTxt, $html, $sqlParam);
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

/**
 * actionArchiveRequeteParID
 * @param string $idRequete
 * @param string $html can be ""
 */
function actionArchiveRequeteParID($idRequete, $html=""){
    global $SQL_TABLE_REQUETES;
    global $SQL_COL_REQUETES_ID;
    global $SQL_COL_REQUETES_VISIBLE;
    global $CONDITION_VALUE_ARCHIVE;
    global $TRACE_INFO_GESTION_REQUEST;
    
    
    if ($idRequete== ""){
        showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
        return;
    }
       
    $request = "UPDATE 	`$SQL_TABLE_REQUETES` SET $SQL_COL_REQUETES_VISIBLE = '$CONDITION_VALUE_ARCHIVE' WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
    showActionVariable($request, $TRACE_INFO_GESTION_REQUEST );
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
}

/**
 * actionVisibleRequeteParID
 * @param string $idRequete
 * @param string $html can be ""
 */
function actionVisibleRequeteParID($idRequete, $html=""){
    global $SQL_TABLE_REQUETES;
    global $SQL_COL_REQUETES_ID;
    global $SQL_COL_REQUETES_VISIBLE;
    global $CONDITION_VALUE_VISIBLE;
    global $TRACE_INFO_GESTION_REQUEST;
    
    
    if ($idRequete== ""){
        showFormulairshowFormulaireEditRequete("", "<le nom>", "<la description>", "<la requete sql>", $html);
        return;
    }
    
    $request = "UPDATE 	`$SQL_TABLE_REQUETES` SET $SQL_COL_REQUETES_VISIBLE = '$CONDITION_VALUE_VISIBLE' WHERE `$SQL_COL_REQUETES_ID`=\"$idRequete\"";
    showActionVariable($request, $TRACE_INFO_GESTION_REQUEST );
    $Resultat = mysqlQuery($request);
    showSQLError("", $request);
    
    actionEditRequeteParID($idRequete);
    
}


?>