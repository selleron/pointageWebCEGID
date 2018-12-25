<?PHP

$MEMBER_DB_PHP="loaded";

$SQL_TABLE_MEMBER="member";
$SQL_COL_ID_REAL_MEMBER="id_member";
$SQL_COL_ID_SESSION="id";
$SQL_COL_MEMBER_PSEUDO="pseudo";
$SQL_COL_MEMBER_PWD="passe";
$SQL_COL_URL="url";
$SQL_COL_DESTINATION="destination";
$QL_COL_EMAIL="email";

$SQL_TABLE_GROUP_MEMBER="member_group";
$SQL_VALUE_GROUP_MEMBER_USER=1;
$SQL_VALUE_GROUP_MEMBER_SUPER_USER=2;
$SQL_VALUE_GROUP_MEMBER_ADMIN=3;

$SQL_TABLE_RELATION_GROUP_MEMBER="relation_member_member_group";
$SQL_COL_ID_MEMBER_IN_RELATION="id_member";
$SQL_COL_ID_GROUP_IN_RELATION="id_member_group";

$SQL_TABLE_LOGIN_HISTORY="login_history";
$SQL_COL_LOGIN_DATE_HISTORY="DATE_HISTORY";
$SQL_COL_IP="IP";


if( ! isset($TOOL_DB_PHP)){
	include_once("tool_db.php");
}

/**
 *
 * login
 */
function login(){
	$pseudo=getURLVariable("pseudo");
	$passe=getURLVariable("passe");
	global $ID_GET;
	global $URL_ROOT_POINTAGE;
	global $URL_ERREUR;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	global $SQL_COL_MEMBER_PSEUDO;
	global $SQL_COL_MEMBER_PWD;
	global $SQL_COL_ID_REAL_MEMBER;
	global $NO_MSG_CHANGE_HEADER;

	$NO_MSG_CHANGE_HEADER="yes"; //permet de ne pas afficher les msg si le header change

	// a commenter pour ne pas avoir de cryptage du passord
	$pwd = $passe;
	convertPassword();
	$pwd = myCrypt($passe);
	// fin commenter
	$request = "select * from $SQL_TABLE_MEMBER where $SQL_COL_MEMBER_PSEUDO=\"$pseudo\" and $SQL_COL_MEMBER_PWD=\"$pwd\"";
	$Resultat = mysqlQuery($request);
	//echo $Resultat;
	if(mysqlNumrows($Resultat)==0)
	{
		//echo "url : ", $URL_ERREUR;
		header("Location:$URL_ROOT_POINTAGE$URL_ERREUR");
		exit;
	}
	else
	{
		global $URL_ROOT_POINTAGE;
		$destination=mysqlResult($Resultat,0,"destination");
		$destination="$URL_ROOT_POINTAGE"."$destination";
		$id_member=mysqlResult($Resultat,0,"$SQL_COL_ID_REAL_MEMBER");
		$id=generateID();
			
		$requete=mysqlQuery("update $SQL_TABLE_MEMBER set $SQL_COL_ID_SESSION=\"$id\" where $SQL_COL_MEMBER_PSEUDO=\"$pseudo\" and $SQL_COL_MEMBER_PWD=\"$pwd\"");
		updateConnection($id_member);
		header("Location:$destination?$ID_GET=$id");
		//echo "$destination?$ID_GET=$id";
	}
}

/**
 * mise a jour de la connexion
 * @param string $id identifiant reel du login
 */
function updateConnection($id){
	global $SQL_TABLE_LOGIN_HISTORY;
	global $SQL_COL_ID_REAL_MEMBER;
	global $SQL_COL_IP;

	$REMOTE_ADDR= $_SERVER["REMOTE_ADDR"];

	$sql="INSERT INTO `$SQL_TABLE_LOGIN_HISTORY` (
			`$SQL_COL_ID_REAL_MEMBER` , `$SQL_COL_IP`  )
			VALUES ('$id', ' $REMOTE_ADDR')";	

	//showSQLAction("$sql");
	$requete=mysqlQuery($sql);
	if ($requete==FALSE){
		$res=mySqlError();
	}
}

/**
 * convertPassword
 * crypte le password si neccessaire
 */
function convertPassword(){
	$pseudo=getURLVariable("pseudo");
	$passe=getURLVariable("passe");
	global $ID_GET;
	global $URL_ERREUR;
	global $URL_ERREUR_DROITS;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	global $SQL_COL_MEMBER_PSEUDO;
	global $SQL_COL_MEMBER_PWD;

	//echo "convert Password $pseudo => ";
	$pwd = myCrypt($passe);
	if ($pwd == $passe) return;

	//echo " $passe : $pwd ";
	$request = "select * from $SQL_TABLE_MEMBER where $SQL_COL_MEMBER_PSEUDO=\"$pseudo\" and $SQL_COL_MEMBER_PWD=\"$passe\"";
	//echo $request;
	$Resultat = mysqlQuery($request);
	//echo $Resultat;
	if(mysqlNumrows($Resultat)==0)
	{
		//nothing to do
		//bad login or already crypted
	}
	else
	{
		//echo "<br>Crypt password for $pseudo : $pwd";
		$requete=mysqlQuery("update $SQL_TABLE_MEMBER set $SQL_COL_MEMBER_PWD=\"$pwd\" where $SQL_COL_MEMBER_PSEUDO=\"$pseudo\" and $SQL_COL_MEMBER_PWD=\"$passe\"");
		//showSQLError("");
		//echo " ok...<br>";
		header("Location:/admin/cryptLogin.php");
		exit;
	}
}

/**
 * fonction de cryptage
 * @param string $str chaine a crypter
 * @param string $salt seed
 */
function myCrypt ( $str) {
	global $CRYPT_PWD;
	showAction("cryptage a faire : $CRYPT_PWD ???");
	if ($CRYPT_PWD == "yes") {
		showAction("cryptage en cours");
		//showCryptDisponible();

		//utilisation d'un DES standard
		$salt = "ds";
		$result = crypt( $str, $salt);
		//suppression des 2 caracteres du DES
		$result = substr( $result, 2);
		return $result;
	}
	else {
		return $str;
	}
}

/**
 * show crypt disponible
 * Enter description here ...
 */
function showCryptDisponible(){
	if (CRYPT_STD_DES == 1) {
		echo 'DES standard : ' . crypt('rasmuslerdorf', 'rl') . "<br>";
	}
	if (CRYPT_EXT_DES == 1) {
		echo 'DES �tendu : ' . crypt('rasmuslerdorf', '_J9..rasm') . "<br>";
	}
	if (CRYPT_MD5 == 1) {
		echo 'MD5 :          ' . crypt('rasmuslerdorf', '$1$rasmusle$') . "<br>";
	}
	if (CRYPT_BLOWFISH == 1) {
		echo 'Blowfish :     ' . crypt('rasmuslerdorf', '$2a$07$usesomesillystringforsalt$') . "<br>";
	}
	if (CRYPT_SHA256 == 1) {
		echo 'SHA-256 :      ' . crypt('rasmuslerdorf', '$5$rounds=5000$usesomesillystringforsalt$') . "<br>";
	}
	if (CRYPT_SHA512 == 1) {
		echo 'SHA-512 :      ' . crypt('rasmuslerdorf', '$6$rounds=5000$usesomesillystringforsalt$') . "<br>";
	}
}

/**
 * Gestion de la modification d'un user
 * le user est recherch� dans : pseudoToModify
 * @param $url
 */
function applyGestionUser($url){
	global $URL_DEFAULT;
	$action = getActionGet();
	$current_selection=getURLVariable("pseudoToModify");
	$login=getURLVariable("pseudo");
	$passe=getURLVariable("passe");
	$destination=getURLVariable("destination");
	$email=getURLVariable("email");

	global $SQL_TABLE_MEMBER;
	global $SQL_COL_MEMBER_PSEUDO;
	global $SQL_COL_MEMBER_PWD;
	global $SQL_COL_DESTINATION;
	global $QL_COL_EMAIL;

//	echo ">>>>>>>>action : $action >>>>>>>>>> [$login] [$pwd] [$destination] [$email]";
	$pwd = myCrypt($passe);

	if ($action=="addUser"){
		//echo "action : $action ok";
		if ($destination==""){
			$destination="$URL_DEFAULT";
		}

		$sql="INSERT INTO `$SQL_TABLE_MEMBER` (
			`$SQL_COL_MEMBER_PSEUDO` , `$SQL_COL_MEMBER_PWD` , `$SQL_COL_DESTINATION` ,
			`$QL_COL_EMAIL` )
			VALUES ('$login', '$pwd', '$destination', '$email')";	

		showSQLAction("$sql");
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
			showSQLAction($res);
			showAddUserForm($url, $action, $login, $pwd, $destination, $email);
		}
		else{
			showAddUserForm($url, $action, "", "", "", "");
		}
	}
	else if ($action=="modifyUserSelection"){
		$action="modifyUser";
		showModifyUserForm($url, $current_selection);
	}
	else if ($action=="modifyUser"){
		//affiche les infos � modifier
		$sql="SELECT * FROM $SQL_TABLE_MEMBER WHERE $SQL_COL_MEMBER_PSEUDO=\"$current_selection\"";
		showSQLAction("$sql");
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
			showSQLAction($res);
		}
		else{
			$login=$current_selection;
			$passe="";
			$pwd = mysqlResult($requete , 0 , $SQL_COL_MEMBER_PWD);
			$destination = mysqlResult($requete , 0 , $SQL_COL_DESTINATION);
			$email = mysqlResult($requete , 0 , $QL_COL_EMAIL);
			$action="modifyUser";
			showModifyUserForm($url, $current_selection);
			$action="executeModifyUser";
			showAddUserForm($url, $action, $login, $passe, $destination, $email);
		}
	}
	else if ($action=="executeModifyUser"){
		//realise l'update
		//le pwd recu est en claire, on utlise donc pwd
		if ($passe==""){
			//le password n'a pas chang�
			$SET_PWD="";
		}
		else{
			//le password n'a pas chang�
			$SET_PWD=", `$SQL_COL_MEMBER_PWD`=\"$pwd\"";
		}
		$sql="UPDATE  $SQL_TABLE_MEMBER
		SET `$SQL_COL_MEMBER_PSEUDO`=\"$login\" $SET_PWD ,
		    `$SQL_COL_DESTINATION`=\"$destination\" , `$QL_COL_EMAIL`=\"$email\"
		WHERE `$SQL_COL_MEMBER_PSEUDO`=\"$current_selection\"";
		showSQLAction("$sql");
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
			showSQLAction($res);
		}
		$action="modifyUser";
		showModifyUserForm($url, $current_selection);
		$action="executeModifyUser";
		showAddUserForm($url, $action, $login, $passe, $destination, $email);
	}
	else if ($action=="deleteUser"){
		$sql="DELETE FROM $SQL_TABLE_MEMBER WHERE $SQL_COL_MEMBER_PSEUDO=\"$current_selection\"";
		showSQLAction("$sql");
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
			showSQLAction($res);
		}
		showModifyUserForm($url, $current_selection);
	}
	else{
		$action="addUser";
    	//echo ">>>>>>>>action : $action >>>>>>>>>> [$login] [$pwd] [$destination] [$email]";
		showAddUserForm($url, $action, $login, $pwd, $destnation, $email);
	}
}

/**
 *
 * Enter showModifyUserForm
 * @param string $url pour les resultats
 * @param string $current_selection user selectionn�
 */
function showModifyUserForm($url, $current_selection){
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_MEMBER_PSEUDO;
	global $ACTION_GET;

	echo "<form method=\"get\" action=\"$url\">
		 <p></p>
	    <table> <tr><td>";
	showFormComboBox( "pseudoToModify",  "pseudoToModify",   $SQL_TABLE_MEMBER, $SQL_COL_MEMBER_PSEUDO, "yes", $current_selection);
	//showFormComboBox($formName,       $name,              $sql_table,        $sql_col,              $useTD, $current_selection)
	showFormIDElement();
	echo"
		<input type=\"submit\" name=\"$ACTION_GET\" value=\"modifyUser\" class=\"input\">
		<input type=\"submit\" name=\"$ACTION_GET\" value=\"deleteUser\" class=\"input\">
		</td></tr></table>
		</form>";
}

function showAddUserForm($url, $action, $login, $pwd, $destination, $email){
	echo"
		<form method=\"post\" action=\"$url\">
		<p></p>
		<table>
		<tr>
			<td>Login </td>
			<td> *</td>
			<td><input type=\"text\" name=\"pseudo\" value=\"$login\" ></td>
		</tr>
		<tr>
			<td>Mot de passe</td>
			<td> *</td>
			<td><input type=\"password\" name=\"passe\" value=\"$pwd\"></td>
		</tr>
		<tr>
			<td>Url de connexion</td>
			<td></td>
			<td><input type=\"text\" size=\"70\" name=\"destination\" value=\"$destination\"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td></td>
			<td><input type=\"text\" size=\"70\" name=\"email\" value=\"$email\" ></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
		";
		if ($action=="executeModifyUser"){
			echo"<td><input type=\"submit\" name=\"Submit\" value=\"Modifier\" class=\"input\"></td>";
		}
		else{
			echo"<td><input type=\"submit\" name=\"Submit\" value=\"Ajouter\" class=\"input\"></td>";
		}
		echo"
		</tr>
		</table>
		<p></p>
		<INPUT TYPE=\"hidden\"   NAME=\"pseudoToModify\" VALUE=\"$login\">";
	showFormAction("$action");
	showFormIDElement();
	echo"</form>";
}

/**
 * formulaire pour afficher l'historique des connexions
 * @param string $html url de la page � activer
 */
function menuActionShowConnection($html){
	//$id_member=getMemberID();
	//global $ID_GET;

	echo "
		<tr> 
		<form method=\"get\" action=\"$html\">
		  <td>
			Historique des connexions
		    <INPUT TYPE=\"hidden\"   NAME=\"action\" VALUE=\"showHistoryConnexion\">";
	showFormIDElement();
	//showFormDocumentElement();
	echo"</td>
		  <td>
			<input type=\"submit\"  value=\"voir\" >
		</td>
		</form>
		</tr>";
}

/**
 *
 * applyActionShowConnection
 * actions possible:
 *	 - showHistoryConnexion
 *	 - modifyUser
 * @param string $url pour les resultats
 */
function applyActionShowConnection($url){
	$action=getActionGet();
	if ($action=="showHistoryConnexion"){
		$param = createParamSqlConnexionHistory();
		showHistoryConnection($url, $param);
	}
	if ($action=="modifyUser"){
		applyGestionUser("$url");
	}
}


/**
 * createParamSqlReservation
 * parametres pour les reservation
 *  - order
 *  - show col count
 *  - properties
 *  - columns_summary
 */
function createParamSqlConnexionHistory(){
	
	global $CONDITION_GET;

	$param = createDefaultParamSql();
	$param[$CONDITION_GET]="";

	return $param;
}


/**
 * showHistoryConnection
 * affiche l'historique des connexions
 * @param $url  = url pour les actions
 * @param string $condition de filtrage
 */
function showHistoryConnection($url, $param){
	global $SQL_TABLE_MEMBER;
	global $SQL_TABLE_LOGIN_HISTORY;
	global $SQL_COL_ID_REAL_MEMBER;
	global $SQL_COL_LOGIN_DATE_HISTORY;
	global $SQL_COL_MEMBER_PSEUDO;
	global $SQL_COL_IP;

	global $CONDITION_GET;
	
	global $SHOW_COL_COUNT;


	$sql = "SELECT $SQL_COL_LOGIN_DATE_HISTORY, $SQL_TABLE_LOGIN_HISTORY.$SQL_COL_ID_REAL_MEMBER, $SQL_COL_MEMBER_PSEUDO, $SQL_COL_IP
	FROM $SQL_TABLE_MEMBER, $SQL_TABLE_LOGIN_HISTORY
	WHERE $SQL_TABLE_MEMBER.$SQL_COL_ID_REAL_MEMBER = $SQL_TABLE_LOGIN_HISTORY.$SQL_COL_ID_REAL_MEMBER";
	if ($param[$CONDITION_GET] != ""){
		$sql= "$sql AND [$param[$CONDITION_GET]]";
	}
	if ($param[ORDER_ENUM::ORDER_GET] == ""){
		$sql= "$sql ORDER BY $SQL_COL_LOGIN_DATE_HISTORY";
	}
	else{
		$sql= "$sql ORDER BY ".$param[ ORDER_ENUM::ORDER_GET ];
	}

	showSQLAction($sql);
	$Resultat = mysqlQuery($sql);
	showSQLError("");

	$columnSummary[0]=$SQL_COL_LOGIN_DATE_HISTORY;
	$columnSummary[1]=$SQL_COL_ID_REAL_MEMBER;
	$columnSummary[2]=$SQL_COL_MEMBER_PSEUDO;
	$columnSummary[3]=$SQL_COL_IP;
		
	$html = currentPageURL();

	echo"<table>";
echo "<table>";
beginTableHeader();
echo "  <td width=\"170\"><a href=\"$html&".ORDER_ENUM::ORDER_GET."=$SQL_COL_LOGIN_DATE_HISTORY\">Date</a></td>
		<td width=\"20\"><a href=\"$html&".ORDER_ENUM::ORDER_GET."=$SQL_COL_ID_REAL_MEMBER\">ID</a></td>
		<td width=\"200\"><a href=\"$html&".ORDER_ENUM::ORDER_GET."=$SQL_COL_MEMBER_PSEUDO\">Member</a></td>
		<td width=\"100\"><a href=\"$html&".ORDER_ENUM::ORDER_GET."=$SQL_COL_IP\">IP</a></td>
		<td>Action</td>
		<td></td>";
endTableHeader();

	for ($Compteur=0 ; $Compteur<mysqlNumrows($Resultat) ; $Compteur++)
	{
		$Date = mysqlResult($Resultat , $Compteur , $SQL_COL_LOGIN_DATE_HISTORY);
		$id = mysqlResult($Resultat , $Compteur , $SQL_COL_ID_REAL_MEMBER);
		$ip = mysqlResult($Resultat , $Compteur , $SQL_COL_IP);
		$pseudo = mysqlResult($Resultat , $Compteur , $SQL_COL_MEMBER_PSEUDO);
		showHistoryConnectionElementAction($Date, $id, $pseudo, $ip);
	}

	echo"</table>";

}

/*
 * showHistoryDocumentElementAction
 *  - voir          :   showHistoryConnectionOneElement
 */
function showHistoryConnectionElementAction($date, $id, $pseudo, $ip){
	echo"<tr>
		<td>$date</td>
		<td>$id</td>
		<td>$pseudo</td>
		<td>$ip</td>";

	echo"<td><form method=\"get\" action=\"$html\">
		<INPUT TYPE=\"hidden\"   NAME=\"date\" VALUE=\"$date\">
		<INPUT TYPE=\"hidden\"   NAME=\"pseudoToModify\" VALUE=\"$pseudo\">";
	showFormAction("modifyUser");
	showFormIDElement();
	showFormDocumentElementValue($id);
	echo " <input type=\"submit\"  value=\"voir\" >
		  </form></td>";

	//echo "<td><form method=\"get\" action=\"$html\">
	//	    <INPUT TYPE=\"hidden\"   NAME=\"date\" VALUE=\"$date\">";
	//showFormAction("restaurerHistoryDocumentOneElement");
	//showFormIDElement();
	//showFormDocumentElementValue($id);
	//echo "
	//	    <input type=\"submit\"  value=\"restauration\" >
	//	  </form></td>";


	echo "</tr>";
}


/**
 * test si on est membre du site
 */
function testMember(){
	global $URL_LOGIN;
	global $URL_ROOT_POINTAGE;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	$id=getMemberID();

	//echo "select * from $SQL_TABLE_MEMBER where id=\"$id\"";
	$requete=mysqlQuery("select * from $SQL_TABLE_MEMBER where $SQL_COL_ID_SESSION=\"$id\"");
	if(mysqlNumrows($requete)==0)
	{
		//echo "id not found";
		header("Location:$URL_ROOT_POINTAGE$URL_LOGIN");
	}
	//die("Vous n'avez pas les droits d'acc�s.");
}

/**
 *
 * getRealMemberID
 * retourne l'identifiant de l'utilisateur courant
 */
function getRealMemberID(){
	$id = getMemberID();
	global $SHOW_MENU_TRACE;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	global $SQL_COL_ID_REAL_MEMBER;

	$realID="unknown";
	$sql="select $SQL_COL_ID_REAL_MEMBER from $SQL_TABLE_MEMBER where $SQL_COL_ID_SESSION=\"$id\"";
	$requete=mysqlQuery("$sql");
	traceConnectionID();
	showActionVariable("request : $sql", $SHOW_MENU_TRACE);
	$nb = mysqlNumrows($requete);
	//echo "nb real id found : $nb";
	if(mysqlNumrows($requete)>0)
	{
		$realID = mysqlResult($requete , 0 , $SQL_COL_ID_REAL_MEMBER);
	}
	return $realID;
}

/**
 *
 * getRealMemberID
 * retourne l'identifiant de l'utilisateur courant
 */
function getLogin(){
	$id = getMemberID();
	global $SHOW_MENU_TRACE;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	global $SQL_COL_MEMBER_PSEUDO;

	$realID="unknown";
	$sql="select $SQL_COL_MEMBER_PSEUDO from $SQL_TABLE_MEMBER where $SQL_COL_ID_SESSION=\"$id\"";
	$requete=mysqlQuery("$sql");
	traceConnectionID();
	showActionVariable("request : $sql", $SHOW_MENU_TRACE);
	$nb = mysqlNumrows($requete);
	//echo "nb real id found : $nb";
	$login="";
	if(mysqlNumrows($requete)>0)
	{
		$login = mysqlResult($requete , 0 , $SQL_COL_MEMBER_PSEUDO);
	}
	return $login;
}



function testMemberGroup($group_id){
	testMember();
	if (isMemberGroup($group_id)==false){
		global $URL_ERREUR_DROITS;
		global $URL_ROOT_POINTAGE;
		header("Location:$URL_ROOT_POINTAGE$URL_ERREUR_DROITS");
	}
}


function isMemberGroup($group_id){
	global $SQL_TABLE_RELATION_GROUP_MEMBER;
	global $SQL_COL_ID_MEMBER_IN_RELATION;
	global $SQL_COL_ID_GROUP_IN_RELATION;
	global $SHOW_MENU_TRACE;
	$idMember=getRealMemberID();

	$sql=  "SELECT *
			FROM
			$SQL_TABLE_RELATION_GROUP_MEMBER
			WHERE 
			$SQL_COL_ID_MEMBER_IN_RELATION=\"$idMember\"
			AND
			$SQL_COL_ID_GROUP_IN_RELATION=\"$group_id\"";
			$requete=mysqlQuery("$sql");
			showActionVariable("request : $sql", $SHOW_MENU_TRACE);
			$nb=mysqlNumrows($requete);
			//echo "nb relation : $nb <br>";
			if(mysqlNumrows($requete)==0)
			{
				return false;
			}
			return true;
}


function generateID(){
	$taille = 20;
	$lettres = "abcdefghijklmnopqrstuvwxyz0123456789";
	srand(time());
	for ($i=0;$i<$taille;$i++)
	{
		$id.=substr($lettres,(rand()%(strlen($lettres))),1);
	}
	return $id;
}

function logout(){
	global $URL_DEFAULT;
	global $SQL_TABLE_MEMBER;
	global $SQL_COL_ID_SESSION;
	global $URL_ROOT_POINTAGE;
	$id=getMemberID();
	

	//echo "update SQL_TABLE_MEMBER set id=\"$newid\" where id=\"$id\"";
	$requete=mysqlQuery("update SQL_TABLE_MEMBER set $SQL_COL_ID_SESSION=\"$newid\" where $SQL_COL_ID_SESSION=\"$id\"");
	header("Location:$URL_ROOT_POINTAGE$URL_DEFAULT");
}



//show hidden calendar annee
function showFormCalendrierAnnee(){
	global $ANNEE_CALENDRIER_GET;
	$annee=getAnneeCalendrierGet();
	showFormHidden($ANNEE_CALENDRIER_GET, $annee);
}



//show document elt $DOCUMENT_NAME_GET
function showFormDocumentElement(){
	$doc=getDocumentName();
	showFormDocumentElementValue($doc);
}

//show document elt $doc has $DOCUMENT_NAME_GET
function showFormDocumentElementValue($doc){
	global $DOCUMENT_NAME_GET;
	echo "<INPUT TYPE=\"hidden\"   NAME=\"$DOCUMENT_NAME_GET\" VALUE=\"$doc\">";
}

//show document elt $doc has ORDER_ENUM::ORDER_GET
function showFormOrderElementValue($value){
	
	echo "<INPUT TYPE=\"hidden\"   NAME=\"".ORDER_ENUM::ORDER_GET."\" VALUE=\"$value\">";
}






function getDocumentName(){
	global $DOCUMENT_NAME_GET;
	return getURLVariable("$DOCUMENT_NAME_GET");
	return $id;
}

function propagateMemberID(){
	global $ID_GET;
	$id=getMemberID();
	if ($id=="") {
		return "";
	}
	$res="$ID_GET=$id";
	return $res;
}

function propagateDocumentName(){
	global $DOCUMENT_NAME_GET;
	$doc=getDocumentName();
	if ($doc=="") {
		return "";
	}
	$res="$DOCUMENT_NAME_GET=$doc";
	return $res;
}

/**
 * propagateURLVariable
 * construit la chaine $variable=xxxx
 * @param string $variable ou vide
 */
function propagateURLVariable($variable){
	$doc=getURLVariable($variable);
	if ($doc=="") {
		return "";
	}
	$res="$variable=$doc";
	return $res;
}

/**
 * propagateArguments
 * creation des arguments de la page HTML
 * retourne ?id=....&
 * @param $propagateAction "" or "yes"
 */
function propagateArguments($propagateAction=""){
	$argument="?";
	//ID
	$id = propagateMemberID();
	if ($id!=""){
		$argument=$argument.$id;
	}
	//DOCUMENT
	$doc = propagateDocumentName();
	if ($doc!=""){
		if ($argument!=""){
			$argument=$argument."&";
		}
		$argument=$argument.$doc;
	}
//	//ORDER
// 	
// 	$order = propagateURLVariable(ORDER_ENUM::ORDER_GET);
// 	if ($order!=""){
// 		if ($argument!=""){
// 			$argument=$argument."&";
// 		}
// 		$argument=$argument.$order;
// 	}

	//Annee Calendrier
	global $ANNEE_CALENDRIER_GET;
	$calendrier = propagateURLVariable($ANNEE_CALENDRIER_GET);
	if ($calendrier!=""){
		if ($argument!=""){
			$argument=$argument."&";
		}
		$argument=$argument.$calendrier;
	}

	if ($propagateAction){
		//action
		global $ACTION_GET;
		$action = propagateURLVariable($ACTION_GET);
		if ($action!=""){
			if ($argument!=""){
				$argument=$argument."&";
			}
			$argument=$argument.$action;
		}
	}

	return $argument;
}

function echoArguments(){
	$res=propagateArguments();
	echo $res;
}





?>