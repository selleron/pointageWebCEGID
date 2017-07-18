<?PHP

$SQL_TABLE_DOCUMENT="document";
$SQL_TABLE_DOCUMENT_HISTORY="document_history";

$SQL_COL_DOCUMENT_ID="ID";
$SQL_COL_DOCUMENT_NAME="NAME";
$SQL_COL_DOCUMENT_TEXT="TEXT";

$SQL_ALL_COL_DOCUMENT="$SQL_COL_DOCUMENT_ID , $SQL_COL_DOCUMENT_NAME, $SQL_COL_DOCUMENT_TEXT";

$SQL_COL_PLANNING_DATE_HISTORY="DATE_HISTORY";

if( ! isset($MEMBER_DB_PHP)){
	include_once("member_db.php");
}

function getDocumentAccueil(){
	return getDocumentForID(1);
}

function getDocumentTextePlanning2012(){
	return getDocumentForID(2);
}

function getDocumentLegendePlanning2012(){
	return getDocumentForID(3);
}

function getDocumentSituation(){
	return getDocumentForID(4);
}

function getDocumentTexteEditCalendrierReservation(){
	return getDocumentForID(5);
}

function getDocumentTextePlanning(){
	return getDocumentForID(6);
}


function getDocumentTextePlanning2013(){
	return getDocumentForID(6);
}


function getDocumentLegendePlanning2013(){
	return getDocumentForID(7);
}

function getDocumentLegendePlanning(){
	return getDocumentForID(7);
}





function getCurrentDocument(){
	global $ITEM_COMBOBOX_SELECTION;
	$name=getDocumentName();
	if ($name=="" || $name=="$ITEM_COMBOBOX_SELECTION"){
		return getActionMessage("Pas de document s&eacute;l&eacute;ctionn&eacute;.");
	}

	return getDocumentForName($name);
}


function getDocumentForID( $id ){
	//echo "id : $id <br>";
	global $SQL_COL_DOCUMENT_ID;
	if ($id==""){
		$condition="";
	}
	else{
		$condition="$SQL_COL_DOCUMENT_ID=\"$id\"";
	}
	return getDocumentForCondition($condition);
}

function getDocumentForName( $name){
	global $SQL_COL_DOCUMENT_NAME;
	if ($name==""){
		$condition="";
	}
	else{
		$condition="$SQL_COL_DOCUMENT_NAME=\"$name\"";
	}
	return getDocumentForCondition($condition);
}


function getDocumentForCondition( $condition){
	global $SQL_TABLE_DOCUMENT;
	global $SQL_ALL_COL_DOCUMENT;
	$subQuery=
	  "	SELECT $SQL_ALL_COL_DOCUMENT
		FROM 
		$SQL_TABLE_DOCUMENT
	";
		if ($condition==""){
		}
		else{
			$subQuery=$subQuery."WHERE $condition";
		}
		//echo "condition : $condition <br>";
		//echo "subQuery : $subQuery   <br>";
		return getDocumentForQuery( $subQuery );
}

function getDocumentForQuery( $Request ){
	//echo "$Request   <br>";
	global $SQL_COL_DOCUMENT_TEXT;

	$Resultat = mysqlQuery($Request);
	$nbRes = mysqlNumrows($Resultat);
	//echo "nb resultat :$nbRes <br>";
	$res="No Data";
	if ($nbRes>0){
		$res = mysqlResult($Resultat , 0 , $SQL_COL_DOCUMENT_TEXT);
		//echo "res : $res";
	}
	return $res ;
}

/**
 * Afficher la combo de selection d'un identifiant de document
 * Si on a les droits, on a aussi un textfield pour creer un nouveau document
 * @param  $html url de la page qui traitera l'action
 */
function showSelectionDocument($html){
	$id_member=getMemberID();
	global $ID_GET;
	global $DOCUMENT_NAME_GET;
	
	echo "<table><tr>";
	
	echo "<td><form method=\"get\" action=\"$html\">";
	showComboBoxDocument($html);
	showFormAction("selectionDocument");
	showFormIDElement();
	echo "<input type=\"submit\"  value=\"valider\" >
    </form></td>";
	
	if (isMemberGroup(2)){
		echo "<td width=\"150\"></td>";
		echo "<td><form method=\"get\" action=\"$html\">
		<input type=\"text\" name=\"$DOCUMENT_NAME_GET\" value=\"$login\" >";
		showFormIDElement();
		getDocumentName();
		showFormAction("creationDocument");
		echo "<input type=\"submit\"  value=\"cr&eacute;er\" >
    </form></td>";
		
		
		
	echo "</tr></table>";
		
	}
}


function showComboBoxDocument($html){
	global $DOCUMENT_NAME_GET;
	global $SQL_COL_DOCUMENT_NAME;
	global $SQL_TABLE_DOCUMENT;

	$document=getDocumentName();
	$Request="SELECT $SQL_COL_DOCUMENT_NAME FROM $SQL_TABLE_DOCUMENT";
	//echo "$Request <br>";
	$Resultat = mysqlQuery($Request);
	$nbRes = mysqlNumrows($Resultat);

	echo "<SELECT name=\"$DOCUMENT_NAME_GET\"  onchange=\"this.submit()\" >";
	echo "<OPTION> [selection]";
	for( $cpt=0 ; $cpt<$nbRes ; $cpt++){
		$res = mysqlResult($Resultat , $cpt , $SQL_DOCUMENT_NAME);
		if ($res==$document){
			$selected="selected";
		}
		else{
			$selected="";
		}
		echo "<OPTION $selected>$res";
	}
	echo "</SELECT>";
}

/**
 * updateDocumentByGet
 *  use getDocumentName() & getURLVariable("myDocument");
 *  on met a jour le document en base si l'action courante est "editDocument";
 *  call updateDocument(a,b)
 */
function updateDocumentByGet(){
	if (getActionGet()=="editDocument"){
		//echo "action editDocument";
		$documentName=getDocumentName();
		if ($documentName==""){
			echo "pas de document";
			return;
		}
		$document = getURLVariable("myDocument");
		updateDocument($documentName, $document);
	}
}

/**
 * createDocumentByGet
 * creation d'un document dans la base de donn�es si l'action est 
 */
function createDocumentByGet(){
	if (getActionGet()=="creationDocument"){
		//echo "action creationDocument";
		$documentName=getDocumentName();
		if ($documentName==""){
			echo "pas de document";
			return;
		}
		createDocument($documentName);
	}
}

/**
 * createDocument
 * creation d'un nouveau document en base de donn�es
 * @param unknown $documentName
 */
function createDocument($documentName){
	global $SQL_TABLE_DOCUMENT;
	global $SQL_COL_DOCUMENT_TEXT;
	global $SQL_COL_DOCUMENT_NAME;

	$sql = "INSERT INTO `$SQL_TABLE_DOCUMENT` 
	( `$SQL_COL_DOCUMENT_NAME` , $SQL_COL_DOCUMENT_TEXT ) 
	VALUES (\"$documentName\" , \"\" ) ";

		
		
	$txt="creation document $documentName";
	showAction($txt);
	//$txt="$sql";
	showSQLAction($sql);

	$txt = "sql result : ".mysqlQuery($sql)."   ".mySqlError();
	showAction($txt);

	historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
}


/**
 * updateDocument
 * 	 - $documentName
 *   - $document
 *   
 *   update table document 
 *   update historisation table
 */
function updateDocument($documentName, $document){
		global $SQL_TABLE_DOCUMENT;
		global $SQL_COL_DOCUMENT_TEXT;
		global $SQL_COL_DOCUMENT_NAME;

		//showAction($document);
		//correction quote sous le serveur de Free
		$document = str_replace( "\\'", "'",$document);
		//showAction($document);
		$document2 = mysql_real_escape_string($document);
		//showAction($document);
		//showAction($document2);
		$sql = "UPDATE `$SQL_TABLE_DOCUMENT`
			SET `$SQL_COL_DOCUMENT_TEXT` = \"$document2\"
			WHERE `$SQL_COL_DOCUMENT_NAME`=\"$documentName\" ";

			
			
		$txt="update document $documentName";
		showAction($txt);
		//$txt="$sql";
		showSQLAction($sql);

		$txt = "sql result : ".mysqlQuery($sql)."   ".mySqlError();
		showAction($txt);

		historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
}


function menuAdminDocument($html){
	menuActionHistorisationDocument($html);
}


function menuActionHistorisationDocument($html){
	$id_member=getMemberID();
	global $ID_GET;
	
	echo "
		<tr $bgcolor > 
		<form method=\"get\" action=\"$html\">
		  <td>
			Historisation des articles
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"saveDocuments\">";
	showFormIDElement();
	showFormDocumentElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"sauvegarder\" >
		</td>
		</form>
		</tr>";
}

function menuActionShowHistorisationDocument($html){
	$id_member=getMemberID();
	global $ID_GET;

	echo "
		<tr $bgcolor > 
		<form method=\"get\" action=\"$html\">
		  <td>
			Historique des articles
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showHistoryDocument\">";
	showFormIDElement();
	showFormDocumentElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>
		</tr>";
}


function applyActionDocument(){
	$action=getActionGet();
	if ($action=="saveDocuments"){
		historisationDocument("");
	}
}


/**
 * 	- voir all      :   showHistoryDocument
 *  - voir          :   showHistoryDocumentOneElement
 *  - restauration  :   restaurerHistoryDocumentOneElement
 */
function applyActionShowHistoryDocument(){
	$action=getActionGet();
	if ($action=="showHistoryDocument"){
		showHistoryDocument("");
	}
	if ($action=="showHistoryDocumentOneElement"){
		$doc = getDocumentName();
		$date =getURLVariable("date");
		showHistoryDocumentElement($date, $doc);
	}
	if ($action=="restaurerHistoryDocumentOneElement"){
		$docName = getDocumentName();
		$date =getURLVariable("date");
		restaurerHistoryDocumentOneElement($date, $docName);
	}
}

/**
 * restaurerHistoryDocumentOneElement
 * restaurer ledocument depuis une date don�e
 * @param unknown_type $date
 * @param unknown_type $docName
 */
function restaurerHistoryDocumentOneElement($date, $docName){
		global $SQL_TABLE_DOCUMENT_HISTORY;
		$condition = createSQLConditionHistory($date, $docName);
		$sql = "SELECT * FROM $SQL_TABLE_DOCUMENT_HISTORY WHERE $condition";
		showSQLAction($sql);
		$txt = getDocumentForQuery($sql);
		updateDocument($docName, $txt);
		showHistoryDocumentElement($date, $docName);
}


function showHistoryDocument($condition=""){
	global $SQL_TABLE_DOCUMENT_HISTORY;
	global $SQL_COL_DOCUMENT_NAME;;
	global $SQL_COL_PLANNING_DATE_HISTORY;

	$sql = "SELECT * FROM $SQL_TABLE_DOCUMENT_HISTORY";
	if ($condition!==""){
		$sql= "$sql WHERE $condition";
	}

	showSQLAction($sql);
	$Resultat = mysqlQuery($sql);

	echo"<table>";
	echo "<table>";
	beginTableHeader();
	echo " 
		<td width=\"170\">Date</td>
		<td width=\"220\">Section</td>
		<td>Action</td>
		<td></td>";
	endTableHeader();

	for ($Compteur=0 ; $Compteur<mysqlNumrows($Resultat) ; $Compteur++)
	{
		$Date = mysqlResult($Resultat , $Compteur , $SQL_COL_PLANNING_DATE_HISTORY);
		$doc = mysqlResult($Resultat , $Compteur , $SQL_COL_DOCUMENT_NAME);

		showHistoryDocumentElementAction($Date, $doc);
	}

	echo"</table>";

}

/*
 * showHistoryDocumentElementAction
 *  - voir          :   showHistoryDocumentOneElement
 *  - restauration  :   restaurerHistoryDocumentOneElement
 */
function showHistoryDocumentElementAction($date, $doc){
	echo"<tr>
		<td>$date</td>
		<td>$doc</td>";

	echo"<td><form method=\"get\" action=\"$html\">
		    <INPUT TYPE=\"hidden\"   NAME=\"date\" VALUE=\"$date\">";
	showFormAction("showHistoryDocumentOneElement");
	showFormIDElement();
	showFormDocumentElementValue($doc);
	echo " <input type=\"submit\"  value=\"voir\" >
		  </form></td>";

	echo "<td><form method=\"get\" action=\"$html\">
		    <INPUT TYPE=\"hidden\"   NAME=\"date\" VALUE=\"$date\">";
	showFormAction("restaurerHistoryDocumentOneElement");
	showFormIDElement();
	showFormDocumentElementValue($doc);
	echo "
		    <input type=\"submit\"  value=\"restauration\" >
		  </form></td>";


	echo "</tr>";
}

function createSQLConditionHistory($date, $doc){
	global  $SQL_COL_PLANNING_DATE_HISTORY;
	global $SQL_COL_DOCUMENT_NAME;

	$condition=" `$SQL_COL_DOCUMENT_NAME`=\"$doc\"	AND  `$SQL_COL_PLANNING_DATE_HISTORY`=\"$date\"	";
	return $condition;
}

function showHistoryDocumentElement($date, $doc){
	global $SQL_TABLE_DOCUMENT_HISTORY;
	global  $SQL_COL_PLANNING_DATE_HISTORY;
	global $SQL_COL_DOCUMENT_NAME;

	$condition = createSQLConditionHistory($date,$doc);
	showHistoryDocument($condition);
	echo "<br>";


	$sql = "SELECT * FROM $SQL_TABLE_DOCUMENT_HISTORY WHERE $condition";
	$txt = getDocumentForQuery($sql);
	echo " <div class=\"section\"> $txt 	 </div>";

	showHistoryDocument("");

}


/**
 * historisationDocument
 * @param unknown_type $condition
 */
function historisationDocument($condition){
	global $SQL_TABLE_DOCUMENT;
	global $SQL_TABLE_DOCUMENT_HISTORY;
	global $SQL_ALL_COL_DOCUMENT;
	
	return historisationTable($SQL_TABLE_DOCUMENT, $SQL_TABLE_DOCUMENT_HISTORY, $SQL_ALL_COL_DOCUMENT, $condition );
}




