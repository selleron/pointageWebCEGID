<?PHP

$RESERVATION_DB="RESERVATION_DB.PHP";

if (!isset($FORM_CSTE_PHP)){
	include_once("../form.cste.php");
}
if (!isset($TOOL_DB_PHP)){
	include_once("./tool_db.php");
}

if (!isset($TIME_PHP)){
	include_once("time.php");    
}

$SQL_TABLE_RESERVATION="reservation";
$SQL_TABLE_RESERVATION_HISTORY="reservation_history";

$SQL_COL_RESERVATION_ID=0;
$SQL_COL_RESERVATION_DATE_ARRIVE=13;

//POST for formulaire reservation
//Array ( [formmail_submit] => Y [field_0] => Monsieur [field_1] => qqqqqqqqq [field_2] => [field_3] =>
//[field_4] => [field_5] => [field_6] => [field_7] => [field_8] => [field_9] => [field_10_DD] =>
//[field_10_MM] => [field_10_YYYY] => 2013 [field_11_DD] => [field_11_MM] => [field_11_YYYY] => 2013
//[field_12] => [field_13] => [field_14] => [field_15] => [field_16] => [field_17_DD] =>
//[field_17_MM] => [field_17_YYYY] => [field_18] => [field_19] => [field_20_DD] => [field_20_MM] =>
//[field_20_YYYY] => [field_21] => [field_22] => [field_23_DD] => [field_23_MM] => [field_23_YYYY] =>
//[field_24] => [field_25] => [field_26_DD] => [field_26_MM] => [field_26_YYYY] => [field_27] => [field_28] =>
//[field_29_DD] => [field_29_MM] => [field_29_YYYY] => [field_30] => [field_31] => [field_32_DD] => [field_32_MM] =>
//[field_32_YYYY] => [field_33] => [fmgCaptchCode] => [field_10] => [field_11] => [field_17] => [field_20] =>
//[field_23] => [field_26] => [field_29] => [field_32] => )

//POST for formulaire reservation
$POST_COL_RESERVATION=array(
//Array ( [formmail_submit] => Y
"RecordID",  "Date",  "IP",
"field_0", "field_1", "field_2", "field_3", "field_4", "field_5", "field_6",
"field_7", "field_8", "field_9",
"field_10", "field_11" ,
//field_10_DD] =>//[field_10_MM] => [field_10_YYYY] => 2013 [field_11_DD] => [field_11_MM] => [field_11_YYYY] => 2013
"field_12", "field_13", "field_14",
//Nom 1
"field_15", "field_16", "field_17", // [field_17_DD] => [field_17_MM] => [field_17_YYYY] => 
"field_18", "field_19", "field_20", // [field_20_DD] => [field_20_MM] => [field_20_YYYY] => 
"field_21", "field_22", "field_23", // [field_23_DD] => [field_23_MM] => [field_23_YYYY] => 
"field_24", "field_25", "field_26", // [field_26_DD] => [field_26_MM] => [field_26_YYYY] => 
"field_27", "field_28", "field_29", // [field_29_DD] => [field_29_MM] => [field_29_YYYY] => 
"field_30", "field_31", "field_32", // [field_32_DD] => [field_32_MM] => [field_32_YYYY] =>
//remarques
"field_33"
//[fmgCaptchCode] =>
);


$SQL_COL_RESERVATION=array(
  "RecordID",  "Date",  "IP",
  "Civilite",  "Nom",  "Prenom",  "Adresse",  "CodePostal",  "Ville",  "Pays",
  "Telephone",  "Portable",  "Email",
  "DateArrivee",  "DateDepart",
  "NbPersonne",  "moins18ans" ,  "NbVehicules",  
  "Nom1",  "Prenom1",  "DateNaissance1",
  "Nom2",  "Prenom2",  "DateNaissance2",
  "Nom3",   "Prenom3",  "DateNaissance3",
  "Nom4",  "Prenom4",  "DateNaissance4",
  "Nom5",  "Prenom5",  "DateNaissance5",
  "Nom6",  "Prenom6",  "DateNaissance6",
  "Remarques");

$SQL_TABLE_RESERVATION_PROPERTY="reservation_property";

$SQL_COL_RESERVATION_PROPERTY_ID="ID";
$SQL_COL_RESERVATION_PROPERTY_NAME="NAME";
$SQL_COL_RESERVATION_PROPERTY_SUMMARY="SUMMARY";
$SQL_COL_RESERVATION_PROPERTY_SUMMARY_ORDER="SUMMARY_ORDER";
$SQL_COL_RESERVATION_PROPERTY_CALENDAR="CALENDAR";
$SQL_COL_RESERVATION_PROPERTY_FORMULAR="FORMULAR";
$SQL_COL_RESERVATION_PROPERTY_FORMULAR_FORMAT="FORMULAR_FORMAT";

if (!isset($MEMBER_DB_PHP)){
	include("member_db.php");
}


function applyActionReservation(){
	$action=getActionGet();
	if ($action=="showLogReservation"){
		show_log_reservation();
	}
	if ($action=="showAllLogReservation"){
		show_all_log_reservation();
	}
	if ($action=="importLogReservation"){
		import_log_reservation();
	}
	if ($action=="showSummarySQLReservation"){
		show_summary_sql_reservation();
	}
	if ($action=="showCalendarSQLReservation"){
		show_calendar_sql_reservation();
	}
	if ($action=="showSQLReservationDeleteOneElement"){
		$doc = getDocumentName();
		delete_sql_reservation($doc);
	}

	if ($action=="showSQLReservationShowOneElement"){
		$doc = getDocumentName();
		$html = currentPageURL();
		show_sql_formulaire($html, $doc);
	}

	if ($action=="addCalendarSQLReservation"){
		add_calendar_sql_reservation();
	}

	if ($action=="editSQLReservation"){
		//echo "<br> action : $action <br>";
		//$doc = getDocumentName();
		//$html = currentPageURL();
		edit_sql_reservation();
	}

}

/**
 * delete_sql_reservation
 * supprime une reservation
 * @param $doc
 */
function delete_sql_reservation($doc){
	global $SQL_TABLE_RESERVATION;
	global $SQL_COL_RESERVATION;
	global $SQL_COL_RESERVATION_ID;

	$doc = mysql_real_escape_string($doc);
	$sql = "DELETE FROM $SQL_TABLE_RESERVATION WHERE `$SQL_COL_RESERVATION[$SQL_COL_RESERVATION_ID]`='$doc'";
	showSQLAction($sql);
	$Resultat = mysql_query($sql);
	showSQLError("");
	show_summary_sql_reservation();
}

/**
 * import_log_reservation
 * importe le fichier des reservations
 */
function import_log_reservation(){
	$parser = new phpReservationDataManager();
	$parser->importFile();
	show_summary_sql_reservation();
	//$parser->initTableReservationProperty();
}

function initTableReservationProperty(){
	$parser = new phpReservationDataManager();
	$parser->initTableReservationProperty();
}

function show_log_reservation(){
	$parser = new phpReservationDataManager();
	$parser->displaySummaryRecords();
}

function show_all_log_reservation(){
	$parser = new phpReservationDataManager();
	$parser->displayRecords();
}

/**
 * createParamSqlReservation
 * parametres pour les reservation
 *  - order
 *  - show col count
 *  - properties
 *  - columns_summary
 */
function createParamSqlReservation(){
	global $SQL_COL_RESERVATION_PROPERTY_SUMMARY;
	global $TABLE_SIZE;
	$param = createDefaultParamSql();

	$param['properties']=$SQL_COL_RESERVATION_PROPERTY_SUMMARY;
	$param['columns_summary']="";
	$param[$TABLE_SIZE]=1000;


	return $param;
}


/**
 * show_all_sql_reservation
 * Affiche la table des reservations
 */
function show_summary_sql_reservation(){
	global $SQL_COL_RESERVATION_PROPERTY_SUMMARY;
	$param = createParamSqlReservation();
	show_sql_reservation($param);
}



/**
 * show_calendar_sql_reservation
 * Affiche la table des reservations en mode calendar
 */
function show_calendar_sql_reservation(){
	global $ORDER_GET;
	global $SHOW_COL_COUNT;
	global $SQL_COL_RESERVATION_PROPERTY_CALENDAR;
	global $SQL_COL_RESERVATION;
	global $SQL_COL_RESERVATION_DATE_ARRIVE;
	$param = createParamSqlReservation();
	$param['properties']=$SQL_COL_RESERVATION_PROPERTY_CALENDAR;
	if ($param[$ORDER_GET]=="") $param[$ORDER_GET] = $SQL_COL_RESERVATION[$SQL_COL_RESERVATION_DATE_ARRIVE];
	show_sql_reservation($param);
}

/**
 * add_calendar_sql_reservation
 * Ajoute une reservation sans envoie d'email
 * Utilise une redirection pour renvoyer vers la page reservation.php
 */
function add_calendar_sql_reservation(){
	$URL="/reservation.php";
	$ARG=propagateArguments("yes");
	header("Location:$URL$ARG");
}

/**
 * edit_sql_reservation
 * Ajoute une reservation sans envoie d'email
 * Utilise une iframe pour renvoyer vers la page reservation.php
 */
function edit_sql_reservation(){
	//$URL="/reservation.php";
	$URL="/admin/form_reedit.php";
	$ARG=propagateArguments("yes");
	//header("Location:$URL$ARG");
	//echo"<br> zzzzzz POST xxxxxx<br>";
	//print_r($_POST);

	echo "<iframe src='$URL$ARG' width='780', height='990'></iframe>";
}


/**
 * show_sql_reservation
 * Affiche la table des reservations suivant la colonneproperty
 * @param $property
 */
function show_sql_reservation($param){
	global $SQL_TABLE_RESERVATION;
	global $SQL_COL_RESERVATION_DATE_ARRIVE;
	global $SQL_COL_RESERVATION;
	global $SQL_TABLE_RESERVATION_PROPERTY;
	global $SQL_COL_RESERVATION_PROPERTY_ID;
	global $ORDER_GET;
	global $SHOW_COL_COUNT;
	global $TABLE_SIZE;
	global $COLUMNS_SUMMARY;

	$annee=getAnneeCalendrierGet("Toutes dates");
	showMenuChoixCalendrier($annee, "true");

	$order = $param[$ORDER_GET];
	$property = $param['properties'];

	$requestCol = "select * from $SQL_TABLE_RESERVATION_PROPERTY where $property='1' order by $property"."_ORDER";
	$columnSummary = sqlRequestToArray($requestCol, $SQL_COL_RESERVATION_PROPERTY_ID);
	$param[$COLUMNS_SUMMARY]=$columnSummary;

	$request = "select * from $SQL_TABLE_RESERVATION";
	if ($annee=="" || $annee=="Toutes dates"){
		//nothing to do
	}
	else{
		$request = $request." WHERE Year(".$SQL_COL_RESERVATION[$SQL_COL_RESERVATION_DATE_ARRIVE].")=$annee";
	}
	if ($order!=""){
		$request = $request." order by ".$order;
	}
	showSQLAction($request);
	$Resultat = mysql_query($request);
	showSQLError("");
	$nbRes = mysql_numrows($Resultat);

	$html = currentPageURL();

	//creation du header
	//voir pour utiliser showTableHeader()
	showTableHeader($param, $html);

	//affichage de chaque ligne
	for( $cpt=0 ; $cpt<$nbRes ; $cpt++){
		show_all_sql_reservation_on_elt($html, $Resultat , $cpt , $param);
	}
	echo "</table>";
}

/**
 * show_all_sql_reservation_on_elt
 *
 * @param unknown_type $html        la page
 * @param unknown_type $Resultat    le resultat de la requete
 * @param unknown_type $cpt         le row a lire
 * @param unknown_type $columns     la colonne a lire
 */
function show_all_sql_reservation_on_elt($html, $Resultat, $cpt, $param){
	$columns = $param['columns_summary'];
	echo "<tr>";
	if ($param['show_col_count']=="yes") echo "<td>$cpt</td>";
	foreach ($columns as $c){
		$res = mysqlResult($Resultat , $cpt , $c);
		echo "<td  $c > ".$res."</td>";
	}

	$id = mysqlResult($Resultat , $cpt , $columns[0]);
	showSQLReservationElementAction($html, $id);
	echo "</tr>";
}

/**
 *
 * show_sql_formulaire
 * @param  $html
 * @param  $id id de la reservation a afficher
 */
function show_sql_formulaire($html, $id){
	global $SQL_TABLE_RESERVATION;
	global $SQL_TABLE_RESERVATION_PROPERTY;
	global $SQL_COL_RESERVATION_PROPERTY_SUMMARY;
	global $SQL_COL_RESERVATION_PROPERTY_SUMMARY_ORDER;
	global $SQL_COL_RESERVATION_PROPERTY_ID;
	global $SQL_COL_RESERVATION;
	global $SQL_COL_RESERVATION_ID;
	global $SQL_COL_RESERVATION_PROPERTY_FORMULAR;
	global $SQL_COL_RESERVATION_PROPERTY_FORMULAR_FORMAT;
	global $SQL_COL_RESERVATION_PROPERTY_NAME;

	$property = $SQL_COL_RESERVATION_PROPERTY_FORMULAR;
	$requestCol = "select * from $SQL_TABLE_RESERVATION_PROPERTY where $property='1' order by $property"."_ORDER";
	$param[0]=$SQL_COL_RESERVATION_PROPERTY_ID;
	$param[1]=$SQL_COL_RESERVATION_PROPERTY_FORMULAR_FORMAT;
	$param[2]=$SQL_COL_RESERVATION_PROPERTY_NAME;
	$columnSummary = sqlRequestToArray2($requestCol, $param);

	$request = "select * from $SQL_TABLE_RESERVATION where `$SQL_COL_RESERVATION[$SQL_COL_RESERVATION_ID]`='$id'";
	showSQLAction($request);
	$Resultat = mysql_query($request);
	//$nbRes = mysql_numrows($Resultat);
	//echo "nbCol : $nbRes";
	showSQLError("");

	show_sql_formulaire_action($html, $id);
	show_formulaire_one_elt($html, $Resultat, 0, $columnSummary);
	show_sql_formulaire_action($html, $id);
}

/**
 * affiche les action possible sur le show d'un formulaire (consultation)
 * @param unknown_type $html la page a appeler sur l'action
 * @param unknown_type $id : id du formulaire (reservation)
 */
function show_sql_formulaire_action($html, $id){
	echo "<div ALIGN='right'>";
	echo "<table> <tr>";
	showSQLReservationElementAction($html, $id, "false", "true", "true");
	echo "</tr> </table>";
	echo "</div>";
}

/**
 *
 * update_post_for_reservation
 * remplit la variable POST pour reediter un formulaire de reservation
 * @param  $id id de la reservation a afficher
 */
function update_post_for_reservation($id){
	if ($_POST[ "formmail_submit" ] == "Y"){
		//le post est deja rempli
		//on ne fait rien de plus
	}
	else{
		global $SQL_TABLE_RESERVATION;
		global $SQL_COL_RESERVATION;
		global $POST_COL_RESERVATION;
		global $SQL_COL_RESERVATION_ID;

		$request = "select * from $SQL_TABLE_RESERVATION where `$SQL_COL_RESERVATION[$SQL_COL_RESERVATION_ID]`='$id'";
		showSQLAction($request);
		$Resultat = mysql_query($request);
		//$nbRes = mysql_numrows($Resultat);
		//echo "nbCol : $nbRes";
		showSQLError("");
		$row = 0;
		$cpt = 0;
		foreach ($SQL_COL_RESERVATION as $c){
			$res = mysqlResult($Resultat , $row , $c);
			$idx = $POST_COL_RESERVATION[$cpt];
			$_POST[ "$idx" ] = $res;
			//			echo ">> $idx : $_POST[$idx] <br>";
			if (isSqlDate($res)){
				$_POST[ "$idx"."_DD" ] = dayFromSqlDate($res);
				$_POST[ "$idx"."_MM" ] = monthFromSqlDate($res);
				$_POST[ "$idx"."_YYYY" ] = yearFromSqlDate($res);
				//				echo "-->> $idx"."_DD : ".dayFromSqlDate($res)." <br>";
				//				echo "-->> $idx"."_MM : ".monthFromSqlDate($res)." <br>";
				//				echo "-->> $idx"."_YYYY : ".yearFromSqlDate($res)." <br>";
			}
			$cpt++;
		}
		$_POST[ "formmail_submit" ] = "Y";
		$_POST[ "formmail_reedit" ] = "Y";
	}
}


function show_formulaire_one_elt($html, $Resultat, $cpt, $columns){
	echo "<table><tr>";
	foreach ($columns as $c){
		$res = mysqlResult($Resultat , $cpt , $c[0]);
		$nom = $c[2];
		$format = $c[1];
		if ($format=="<br>"){
			$format="</tr><tr>";
		}
		//		echo " 0 : $c[1]<br>";
		//		echo " 1 : $c[1]<br>";
		//      echo " 2 : $c[2]<br>";
		//		echo"format : $c[1] ";
		//		echo"value  : $res<br>";
		echo $format."<td>$nom</td><td> : </td><td>".$res."</td><td width=\"10\"></td> ";
	}
	echo "</tr></table>";
}

/**
 * showSQLReservationElementAction
 * Affiche un formulaire pour pouvoir lancer le show d'une reservation
 * boutons : show, edit , delete
 * @param $html  nom de la page html
 * @param $id          id de la reservation a afficher
 * @param $showShow     true par defaut
 * @param $showDelete   true par defaut
 * @param $showEdit     true par defaut
 */
function showSQLReservationElementAction($html, $id, $showShow="true", $showDelete="true", $showEdit="true"){
	if ($showShow=="true"){
		echo"<td><form method=\"get\" action=\"$html\"> ";
		showFormAction("showSQLReservationShowOneElement");
		showFormIDElement();
		showFormDocumentElementValue($id);
		echo " <input type=\"submit\"  value=\"voir\" >	</form></td>";
	}

	if ($showDelete=="true"){
		echo"<td><form method=\"get\" action=\"$html\"> ";
		showFormAction("editSQLReservation");
		showFormIDElement();
		showFormDocumentElementValue($id);
		echo " <input type=\"submit\"  value=\"edit\" >	</form></td>";
	}


	if ($showEdit=="true"){
		echo"<td><form method=\"get\" action=\"$html\"> ";
		showFormAction("showSQLReservationDeleteOneElement");
		showFormIDElement();
		showFormDocumentElementValue($id);
		echo " <input type=\"submit\"  value=\"delete\" > </form></td>";
	}
}



/**
 * menuActionReservation
 * - showSummarySQLReservation
 * - showLogReservation
 * - ...
 * @param URL $html
 */
function menuActionReservation($html){
	$id_member=getMemberID();
	global $ID_GET;
	global $SQL_VALUE_GROUP_MEMBER_ADMIN;

	echo "<table><tr bgcolor=#AAAAAA ><td>table</td><td>action</td><td width=\"200\"></td><td>log</td><td>action</td></tr>";
	//$bgcolor="bgcolor=#20FF20";
	$bgcolor="";
	

	echo "<tr $bgcolor >";
	//-------- show Summary Reservation ------------
	echo "
		<form method=\"get\" action=\"$html\">
		  <td>
			r&eacute;sum&eacute; reservations
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showSummarySQLReservation\">";
	showFormIDElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>";

	echo "<br>";

	//-------- showLogReservation ------------
	echo "<td></td>";
	echo "
		<form method=\"get\" action=\"$html\">
		  <td>
			show log reservation
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showLogReservation\">";
	showFormIDElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>";

	echo "</tr>";

	//===================================

	echo "<tr $bgcolor >";
	//-------- show Summary Reservation ------------
	echo "
		<form method=\"get\" action=\"$html\">
		  <td>
			calendrier reservations
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showCalendarSQLReservation\">";
	showFormIDElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>";

	echo "<br>";

	//-------- showAllLogReservation ------------
	echo "<td></td>";
	echo "
		<form method=\"get\" action=\"$html\">
		  <td>
			show all log reservation
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showAllLogReservation\">";
	showFormIDElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>";

	echo "</tr>";

	//===================================

	echo "<tr $bgcolor >";
	//-------- show Summary Reservation ------------
	if (isMemberGroup($SQL_VALUE_GROUP_MEMBER_ADMIN)){
		echo "
			<form method=\"get\" action=\"$html\">
			  <td>
				ajouter une reservations
			    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"addCalendarSQLReservation\">";
		showFormIDElement();
		echo"</td>
			  <td>
				<input type=\"submit\"  value=\"add\" >
			</td>
			</form>";
	}
	//-------- importLogReservation ------------
	echo "<td></td>";
	if (isMemberGroup($SQL_VALUE_GROUP_MEMBER_ADMIN)){
		echo "
		<form method=\"get\" action=\"$html\">
		  <td>
			import log reservation
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"importLogReservation\">";
		showFormIDElement();
		echo"</td>
		  <td>
			<input type=\"submit\"  value=\"importer\" >
		</td>
		</form>";
	}

	echo "</tr>";

	//========================================
	echo "</table>";

}


class phpReservationDataManager
{
	var $dataFile = '';
	var $columns = '';
	var $records = '';

	function phpReservationDataManager(){
		$this->dataFile = PHPFMG_SAVE_FILE;
	}

	function parseFile(){
		$fp = @fopen($this->dataFile, 'rb');
		if( !$fp ) return false;

		showAction("open ".$this->dataFile);

		$i = 0 ;
		$phpExitLine = 1; // first line is php code
		$colsLine = 2 ; // second line is column headers
		$this->columns = array();
		$this->records = array();
		$sep = chr(0x09);
		while( !feof($fp) ) {
			$line = fgets($fp);
			$line = trim($line);
			if( empty($line) ) continue;
			$line = $this->line2display($line);
			$i ++ ;

			//showAction("read $i $line");

			switch( $i ){
				case $phpExitLine:
					continue;
					break;
				case $colsLine :
					$this->columns = explode($sep,$line);
					break;
				default:
					$this->records[] = explode( $sep, $this->data2record( $line, false ) );
			};
		};
		fclose ($fp);
	}

	function toRecord( $line, $sep=""){
		if ($sep==""){
			$sep=chr(0x09);
		}

		$this->records = array();
		$line = $this->line2display($line);
		$this->records[] = explode( $sep, $this->data2record( $line, false ) );
		return $this->records[0];
	}

	function insertReservationInDataBase($line){
		$aRecord = $this->toRecord($line);
		$this->importRecord($aRecord,"");
	}

	function displayRecords(){
		$this->parseFile();
		echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
		echo "<tr bgcolor=#AAAAAA><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
		$i = 1;
		foreach( $this->records as $r ){
			echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
			$i++;
		};
		echo "</table>\n";
	}

	function displaySummaryRecords($maxCol=6){
		$this->parseFile();
		$i = 1;
		foreach( $this->columns as $r ){
			$i++;
		}

		echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
		echo "<tr bgcolor=#AAAAAA><td>&nbsp;</td><td><b>" . $this->join( "</b></td><td>&nbsp;<b>", $this->columns, $maxCol ) . "</b></td></tr>\n";
		$i = 1;
		foreach( $this->records as $r ){
			echo "<tr><td align=right>{$i}&nbsp;</td><td>" . $this->join( "</td><td>&nbsp;", $r, $maxCol ) . "</td></tr>\n";
			$i++;
		};
		echo "</table>\n";
	}

	function join($txt, $array, $max){
		$i=0;
		$result="";
		foreach($array as $r){
			$i++;
			if ($i>$max) break;
			$result=$result.$r.$txt;
		}
		return $result;
	}

	function line2display( $line ){
		$line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
		$line = substr( $line, 1, -1 ); // chop first " and last "
		return $line;
	}

	function data2record( $s, $b=true ){
		$from = array( "\r", "\n");
		$to   = array( "\\r", "\\n" );
		return $b ? str_replace( $from, $to, $s ) : str_replace( $to, $from, $s ) ;
	}

	function initTableReservationProperty(){
		global $SQL_TABLE_RESERVATION_PROPERTY;
		global $SQL_COL_RESERVATION_PROPERTY_ID;
		global $SQL_COL_RESERVATION_PROPERTY_NAME;
		global $SQL_COL_RESERVATION_PROPERTY_SUMMARY;
		global $SQL_COL_RESERVATION_PROPERTY_SUMMARY_ORDER;
		global $SQL_COL_RESERVATION;

		$i=0;
		foreach( $SQL_COL_RESERVATION as $r ){
			$col = mysql_real_escape_string($r);

			$sql="INSERT INTO `$SQL_TABLE_RESERVATION_PROPERTY` ( `$SQL_COL_RESERVATION_PROPERTY_ID`,
			  `$SQL_COL_RESERVATION_PROPERTY_NAME`, 
			  `$SQL_COL_RESERVATION_PROPERTY_SUMMARY`, 
			  `$SQL_COL_RESERVATION_PROPERTY_SUMMARY_ORDER` 
			  ";
			$sql=$sql.") VALUES (";
			$sql=$sql."\"$col\", \"$col\" ";
			$res=$i<=6?"1":"0";
			$sql=$sql.", $res , $i";
			$sql=$sql.")";

			showSQLAction($sql);
			$txt = "sql result : ".mysql_query($sql);
			showSQLError($txt);

			$i++;
		}
	}

	/**
	 * importFile
	 * insert dans la table reservation le fichier log des formulaires
	 */
	function importFile(){
		$this->parseFile();

		global $SQL_TABLE_RESERVATION;
		global $SQL_COL_RESERVATION;
		global $SQL_TABLE_RESERVATION_HISTORY;
		global $SQL_COL_RESERVATION_ID;

		foreach( $this->records as $r ){
			$this->importRecord($r, "1");
		}
	}


	/**
	 * import Record
	 * insert dans la table reservation l'enregistrement du formulaire
	 * @param $r     array of data see $SQL_COL_RESERVATION for liste of data name
	 * @param $show  affiche la requete Sql executee
	 * @param $update 0 for insert 1 for update
	 */
	function importRecord($r, $show="1"){
		global $SQL_TABLE_RESERVATION;
		global $SQL_COL_RESERVATION;
		global $SQL_TABLE_RESERVATION_HISTORY;
		global $SQL_COL_RESERVATION_ID;

		$show="";

		$document2 = mysql_real_escape_string($document);
		$condition="`$SQL_COL_RESERVATION[$SQL_COL_RESERVATION_ID]`='$r[0]'";

		//test pour insert or update
		$exist_reservation = mysql_query("select * from `$SQL_TABLE_RESERVATION` where $condition");
		showSQLError("", "$exist_reservation");
		$update = mysql_num_rows($exist_reservation);

		if ($update=="1"){
			$id = $_POST['RecordID'];
			if ($show=="1") showAction("update formulaire : $condition - $id ");
			$i=0;
			foreach( $r as $v ){
				$val[$i] = $this->format($i, $v);
				$i++;
			}
				
			$sql=createSqlUpdate($SQL_TABLE_RESERVATION, $SQL_COL_RESERVATION, $val, $condition, "false" );
			//showSQLAction($sql);
		}
		else{
			$sql="INSERT INTO `$SQL_TABLE_RESERVATION` ( ";
			if ($show=="1") showAction("insert formulaire : $condition ");

			$i=0;
			foreach( $SQL_COL_RESERVATION as $c ){
				if ($i>0){
					$sql=$sql." ,";
				}
				$sql=$sql."`$c`";
				$i++;
			}

			$i=0;
			$sql=$sql.") VALUES (";
			foreach( $r as $v ){
				if ($i>0){
					$sql=$sql." ,";
				}
				$v = $this->format($i, $v);
				$sql=$sql.$v;
				$i++;
			}
			$sql=$sql.")";
		}
		if($show=="1") showSQLAction($sql);

		$txt = "sql result : ".mysql_query($sql);
		showSQLError("",$sql);

		historisationTable($SQL_TABLE_RESERVATION, $SQL_TABLE_RESERVATION_HISTORY, $SQL_COL_RESERVATION, $condition, $show);
	}


	/**
	 * format les valeurs a inserer dans la table reservation
	 * @param $index : index de la colonne $SQL_COL_RESERVATION
	 * @param $value : valeur a mettre dans la colonne
	 */
	function format($index, $value){
		global $SQL_COL_RESERVATION;
		$value = mysql_real_escape_string($value);

		if (sizeof($SQL_COL_RESERVATION)> $index){

			$res = strpos($SQL_COL_RESERVATION[$index], "Date");
			if ($res===FALSE){
				// le === evite le cast car "0" egal FALSE
				// on quote la string
				$value="\"$value\"";
			}
			else if($res>=0){
				if ($value==""){
					$value="NULL";
				}
				else{
					// on quote la date
					//  $value = strtotime($value);
					$value="\"$value\"";
				}
			}
		}
		return $value;
	}


}
# end of class




?>