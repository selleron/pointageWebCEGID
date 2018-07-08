<?php

$ACCESS_SITE_PHP="loaded";


$SQL_TABLE_ACCESS_HISTORY="access_history";
$SQL_COL_IP_ACCESS_HISTORY="ip";
$SQL_COL_DATE_ACCESS_HISTORY="date";
$SQL_COL_URL_ACCESS_HISTORY="url";
$SQL_COL_ID_MEMBER_ACCESS_HISTORY="id_member";
$SQL_COL_USER_AGENT_ACCESS_HISTORY="user_agent";
$SQL_SHOW_COL_ACCESS_HISTORY="$SQL_COL_DATE_ACCESS_HISTORY, $SQL_COL_IP_ACCESS_HISTORY, $SQL_COL_ID_MEMBER_ACCESS_HISTORY, $SQL_COL_USER_AGENT_ACCESS_HISTORY, $SQL_COL_URL_ACCESS_HISTORY";


$SQL_TABLE_ACCESS_COUNTER="access_counter";
$SQL_COL_IP_ACCESS_COUNTER="ip";
$SQL_COL_COUNT_ACCESS_COUNTER="counter_access";
$SQL_COL_DATE_LAST_ACCESS_COUNTER="date_last_access";
$SQL_COL_URL_ACCESS_COUNTER="url";
$SQL_COL_ID_MEMBER_ACCESS_COUNTER="id_member";
$SQL_COL_USER_AGENT_ACCESS_COUNTER="user_agent";
$SQL_SHOW_COL_ACCESS_COUNTER="$SQL_COL_DATE_LAST_ACCESS_COUNTER, $SQL_COL_IP_ACCESS_COUNTER, $SQL_COL_ID_MEMBER_ACCESS_COUNTER, $SQL_COL_COUNT_ACCESS_COUNTER, $SQL_COL_USER_AGENT_ACCESS_COUNTER, $SQL_COL_URL_ACCESS_COUNTER ";

//define( 'PHPFMG_SMTP_DEBUG_LEVEL' , '3' ); // empty or 0 - trun off debug

if( ! isset($TOOL_DB_PHP)){
	include_once(dirname(__FILE__)."/tool_db.php");
}
include_once(dirname(__FILE__)."/time.php");
//include_once($_SERVER ['DOCUMENT_ROOT']."/form/phpmailer.php"); 
//include_once($_SERVER ['DOCUMENT_ROOT']."/form/form.cste.php"); 
//include_once($_SERVER ['DOCUMENT_ROOT']."/form/form.lib.php");


/**
 * trace les acces au site
 */
function trace_access_history(){
	global $ACCESS_TRACE;
	
	if ($ACCESS_TRACE=="yes" || $ACCESS_TRACE=="YES"){
		global $ACCESS_FILTRE_TRACE;
		global $SQL_TABLE_ACCESS_HISTORY;
		global $SQL_COL_IP_ACCESS_HISTORY;
		global $SQL_COL_DATE_ACCESS_HISTORY;
		global $SQL_COL_URL_ACCESS_HISTORY;
		global $SQL_COL_ID_MEMBER_ACCESS_HISTORY;
		global $SQL_COL_USER_AGENT_ACCESS_HISTORY;
		global $SHOW_ACCESS_TRACE;
		
		//filte les adresses
		$REMOTE_ADDR= $_SERVER["REMOTE_ADDR"];
		if (isset($ACCESS_FILTRE_TRACE)){
			if (isFiltred($REMOTE_ADDR, $ACCESS_FILTRE_TRACE)){
				showActionVariable("==============> trace adresse filtred : $REMOTE_ADDR", $SHOW_ACCESS_TRACE);
				return;
			}
		}
		
		
		$URL= $_SERVER["REQUEST_URI"];
		$idMember=getLogin();
		
		$AGENT = $_SERVER['HTTP_USER_AGENT'];
			
		$sql="INSERT INTO `$SQL_TABLE_ACCESS_HISTORY` (
		`$SQL_COL_IP_ACCESS_HISTORY` , `$SQL_COL_ID_MEMBER_ACCESS_HISTORY`, 
		`$SQL_COL_URL_ACCESS_HISTORY`, `$SQL_COL_USER_AGENT_ACCESS_HISTORY`  )
		VALUES ('$REMOTE_ADDR', '$idMember', '$URL', '$AGENT')";
	
		showActionVariable("$sql", $SHOW_ACCESS_TRACE );
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
		}
	}
}

/**
 * getCounterAcess
 * @param  $ip ip a rechercher. si ip="" alors utilise l'ip du client
 */
function getCounterAcessRequest($ip=""){
	if ($ip==""){
		$ip = $_SERVER["REMOTE_ADDR"];
	}

	global $SQL_TABLE_ACCESS_COUNTER;
	global $SQL_COL_IP_ACCESS_COUNTER;
	global $SQL_COL_COUNT_ACCESS_COUNTER;
	global $SQL_COL_DATE_LAST_ACCESS_COUNTER;
	
	$date="$SQL_COL_DATE_LAST_ACCESS_COUNTER";
	$request=	"SELECT`$SQL_COL_COUNT_ACCESS_COUNTER`, `$date` 
				FROM `$SQL_TABLE_ACCESS_COUNTER`
				WHERE `$SQL_COL_IP_ACCESS_COUNTER`='$ip';";

	//showSQLAction("$request");
	$Resultat = mysqlQuery($request);
	showSQLError("");
	return $Resultat;
}

/**
 * getCounterAccess
 * @param unknown $Resultat resultat de request
 * @return number 
 */
function getCounterAccess($Resultat){
	global $SQL_COL_COUNT_ACCESS_COUNTER;
	
	$nbRes = mysqlNumrows($Resultat);
	
	if ($nbRes==0){
		return 0;
	}

	$res = mysqlResult($Resultat , 0 , $SQL_COL_COUNT_ACCESS_COUNTER);
	return $res;
}

/**
 * getLastDateCountAccess
 * @param unknown $Resultat
 * @return date
 */
function getLastDateCountAccess($Resultat){
	global $SQL_COL_DATE_LAST_ACCESS_COUNTER;
	
	$nbRes = mysqlNumrows($Resultat);
	
	if ($nbRes==0){
		return 0;
	}

	$res = mysqlResult($Resultat , 0 , $SQL_COL_DATE_LAST_ACCESS_COUNTER);
	return $res;
}


/**
 * updateCounterAccessAndEmail
 * @param string $ip
 */
function updateCounterAccessAndEmail($ip=""){	
	global $ACCESS_EMAIL_COUNTER;
	global $SHOW_ACCESS_TRACE;
	global $ACCESS_EMAIL_TO;
	global $ACCESS_EMAIL_FROM;
	global $ACCESS_EMAIL_BCC;
	global $ACCESS_FILTRE_EMAIL_COUNTER;
	
	
	$count = updateCounterAccess($ip);
	//DEBUG
	//$count = 1;
	//END DEBUG
	if ($count == 1){
		//prevenir par email si besoin
		if ($ACCESS_EMAIL_COUNTER=="yes"){
			if ($ip==""){
				$ip = $_SERVER["REMOTE_ADDR"];
			}
				//filte les adresses
		$REMOTE_ADDR= $_SERVER["REMOTE_ADDR"];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if (isset($ACCESS_FILTRE_EMAIL_COUNTER)){
			if (isFiltred($REMOTE_ADDR, $ACCESS_FILTRE_EMAIL_COUNTER)){
				showActionVariable("==============> trace email adresse filtred : $REMOTE_ADDR", $SHOW_ACCESS_TRACE);
				return;
			}
			if (isFiltred($agent, $ACCESS_FILTRE_EMAIL_COUNTER)){
				showActionVariable("==============> trace email agent filtred : $agent", $SHOW_ACCESS_TRACE);
				return;
			}
		}
			showActionVariable("Envoie Email pour signaler acces de $ip", $SHOW_ACCESS_TRACE);
			
			$url= "http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
			
			//envoie email
			$to = PHPFMG_TO;
			$subject = "Consultation site Pointage";
			$message = "L'adresse ip $ip vient d'acc&eacute;der au site de Pointage.<br>
			User Agent : ". $agent . "<br>
			URL : $url ";
			$from = PHPFMG_TO_WEBMASTER;
			$fromName = $from;
			$bcc = PHPFMG_BCC;
			$fromHeader="";
			$cc="";
			$charset = "UTF-8";
			$type = 'FormMail';
				
			showActionVariable("from $from <br> 
			 	to   $to <br>	
				bcc  $bcc <br>", $SHOW_ACCESS_TRACE);			
			
			$error = mailAttachments( $to , $subject , $message , 
			  $from, $fromName, $fromHeader,  $cc, $bcc,  $charset, $type );
			//$error = mail( $to , $subject , $message, $headers );
			if ($error != ""){
				showActionVariable("erreur envoie email : $error", $debugVariable);
			}
		}
	}
}

/**
 * updateCounterAccess
 * maj de la table ACCESS_COUNTER
 * @param string $ip ip � stocker (si vide prend l'ip du client)
 * @return int  access_counter 
 * 	0 si ne pas prendre en compte
 * 	1 si on doit envoyer un email
 * 	n si l'email a sans doute �t� envoy�
 */
function updateCounterAccess($ip=""){	
	global $SHOW_ACCESS_TRACE;
	global $SQL_TABLE_ACCESS_COUNTER;
	global $SQL_COL_IP_ACCESS_COUNTER;
	global $SQL_COL_COUNT_ACCESS_COUNTER;
	global $SQL_COL_DATE_LAST_ACCESS_COUNTER;
	global $SQL_COL_URL_ACCESS_COUNTER;
	global $SQL_COL_ID_MEMBER_ACCESS_COUNTER;	
	global $ACCESS_TIMEOUT_COUNTER;
	global $SQL_COL_USER_AGENT_ACCESS_COUNTER;
	
	if ( $ACCESS_TIMEOUT_COUNTER <= 0 ){
		return 0;
	}
	
	if ($ip==""){
		$ip = $_SERVER["REMOTE_ADDR"];
	}
	$url= $_SERVER["REQUEST_URI"];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$idMember=getLogin();
	$Resultat = getCounterAcessRequest();
	$counter = getCounterAccess($Resultat);
	$counter+=1;

	$date = getLastDateCountAccess($Resultat);
	$currentTime = time();
	//$currentDate = timeToSqlDateTime($currentTime);
	//showSQLAction("=================> date last access : $date");
	//showSQLAction("=================> current date     : $currentDate");
	$timeSql = sqlDateToTime($date);
	//showSQLAction("=================> date last access : $timeSql");
	//showSQLAction("=================> current date     : $currentTime");
	
	$diff = $currentTime- $timeSql;
	showActionVariable("====================> diff date : $diff - waited $ACCESS_TIMEOUT_COUNTER", $SHOW_ACCESS_TRACE);
	
	if ($diff >= $ACCESS_TIMEOUT_COUNTER){
		$sql = "REPLACE INTO `$SQL_TABLE_ACCESS_COUNTER` 
		(`$SQL_COL_IP_ACCESS_COUNTER`, `$SQL_COL_COUNT_ACCESS_COUNTER`, 
		`$SQL_COL_URL_ACCESS_COUNTER`, `$SQL_COL_ID_MEMBER_ACCESS_COUNTER`,
		`$SQL_COL_USER_AGENT_ACCESS_COUNTER`, `$SQL_COL_DATE_LAST_ACCESS_COUNTER`) 
		VALUES ('$ip', '$counter', '$url', '$idMember', '$agent', NOW() )";
		
		showActionVariable("$sql",  $SHOW_ACCESS_TRACE);
		$requete=mysqlQuery($sql);
		if ($requete==FALSE){
			$res=mySqlError();
		}
		else{
			return $counter;
		}
	}	
	
	return 0;
}

/**
 * affiche la table des acces (access_history)
 * 
 */
function showTableAccess(){
	global $SQL_SHOW_COL_ACCESS_HISTORY;
	global $SQL_TABLE_ACCESS_HISTORY;
	global $TABLE_SIZE;
	global $TABLE_NAME;
	
	//creation requete
	$param = createDefaultParamSql($SQL_TABLE_ACCESS_HISTORY, $SQL_SHOW_COL_ACCESS_HISTORY);
	$param = updateParamSpqlWithLimit($param);
	$param[$TABLE_SIZE]=1100;
	showLimitBar($param);

	//debug
	$sql = createRequeteTableData($param);
	showSQLAction("$sql");
	//end debug
	
	showTableHeader($param);
	showTableData($param);
}

/**
 * affiche la table counter d'acces
 *
 */
function showTableCounterAcces(){
	global $SQL_SHOW_COL_ACCESS_COUNTER;
	global $SQL_TABLE_ACCESS_COUNTER;
	global $TABLE_SIZE;
	$param = createDefaultParamSql($SQL_TABLE_ACCESS_COUNTER, $SQL_SHOW_COL_ACCESS_COUNTER);

	$param[$TABLE_SIZE]=1000;
	showTableHeader($param);
	showTableData($param);
}


?>