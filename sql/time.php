<?php
//echo "include time.php<br>";
$TIME_PHP="timePHP";


### French Version
$time_calendar_txt['french']['monthes'] 	    = array('', 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirct', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$time_calendar_txt['french']['days']		    = array('Lundi', 'Mardi', 'Mercredi','Jeudi', 'Vendredi', 'Samedi',	'Dimanche');

/**
 * month in texte
 * @param int $month 01..12
 * @param langue 'french', 'english'
 * @return string month en lettre janvier, fevrier, ...
*/
function monthInTexte($month, $langue="french"){
	$idxMonth = (int) $month;
	global $time_calendar_txt;
	return $time_calendar_txt[$langue]['monthes'][$idxMonth];
}


/**
 * formatCalendar
 * format YYYYMMDD
 * @param integer timestamp $time
 */
function formatCalendar($time){
	$date = date("Ymd",$time);
	return $date;
}


/**
 *
 * calendarDateToTime
 * retourne un timestamp
 * @param string $date YYYYMMDD
 */
function calendarDateToTime($date){
	$month 		= substr($date, 4 ,2);
	$day 		= substr($date, 6, 2);
	$year		= substr($date, 0 ,4);
	//echo "year $year month $month day $day ";
	$timestamp 	= mktime(0, 0, 0, $month, $day, $year);
	return $timestamp;
}

/**
 * getYearFromCalendar
 * return une ann&eacute;e YYYY
 * @param string $date YYYYMMDD
 */
function getYearFromCalendar($date){
	$year		= substr($date, 0 ,4);
	return $year;
}

/**
 * sqlDateToTime
 * return timestamp
 * @param string $date YYYY-MM-DD
 */
function sqlDateToTime($date){
	$hour=0;
	$minute=0;
	$second=0;
	$month 		= substr($date, 5 ,2);
	$day 		= substr($date, 8, 2);
	$year		= substr($date, 0 ,4);

	if (strlen($date)>10){
		$hour = substr($date,11,2);
		$minute = substr($date,14,2);
		$second = substr($date,17,2);
	}

	//echo "year $year month $month day $day $hour:$minute:$second";
	$timestamp 	= mktime($hour, $minute, $second, $month, $day, $year);
	return $timestamp;
}

/**
 * a on une sqlDate YYYY-MM-DD
 * @param $date String
 */
function isSqlDate($date){
	$month 		= substr($date, 5 ,2);
	$s2 		= substr($date, 7 ,1);
	$day 		= substr($date, 8, 2);
	$s1 		= substr($date, 4 ,1);
	$year		= substr($date, 0 ,4);

	return $s1=="-" && $s2=="-";
}

/**
 * day from Sql Date
 * @param string $date YYYY-MM-DD
 * @return string day (DD)
 */
function dayFromSqlDate($date){
	$day 		= substr($date, 8, 2);
	return $day;
}

/**
 * month from Sql Date
 * @param string $date YYYY-MM-DD
 * @return string month (MM)
 */
function monthFromSqlDate($date){
	$month 		= substr($date, 5 ,2);
	return $month;
}

/**
 * year from Sql Date
 * @param string $date YYYY-MM-DD
 * @return string year (YYYY)
 */
function yearFromSqlDate($date){
	$year		= substr($date, 0 ,4);
	return $year;
}


/**
 * retourne une YYYY-MM-DD depuis un timestamp
 * @param integer $time1 timestamp
 */
function timeToSqlDate($time1){
	$day = date("d", $time1);
	$month = date("m", $time1);
	$year = date("Y", $time1);
	$out= "$year-$month-$day";
	return $out;
}

/**
 * retourne une YYYY-MM-DD HH:MM:SSdepuis un timestamp
 * @param string $time1 timestamp
 */
function timeToSqlDateTime($time1){
	$day = date("d", $time1);
	$month = date("m", $time1);
	$year = date("Y", $time1);
	$hour = date("H",$time1);
	$minute = date("i",$time1);
	$second = date("s",$time1);
	$out= "$year-$month-$day $hour:$minute:$second";
	return $out;
}

/**
 * timeFromDDMMYY
 * return timestamp
 * @param int $day
 * @param int $month
 * @param int $year
 */
function timeFromDDMMYY( $day,  $month,  $year){
	$timestamp 	= mktime(0, 0, 0, $month, $day, $year);
	return $timestamp;
}

/**
 * sqlDateFromDDMMYY
 * return timestamp
 * @param int $day
 * @param int $month
 * @param int $year
 */
function sqlDateFromDDMMYY( $day,  $month,  $year){
    //echoTD("time : -$day-$month-$year-");
    $timestamp 	= mktime(0, 0, 0, intval($month), intval($day), intval($year));
	return timeToSqlDate($timestamp);
}

/**
 * incrementDate
 * increment une date de n jours
 * @return integer timestamp $time1+ ($inc day)
 * @param integer timestamp $time1
 * @param integer $inc increment day
 */
function incrementDate($time1, $inc=1){
	$day = date("d", $time1);
	$month = date("m", $time1);
	$year = date("Y", $time1);
	$day = $day+$inc;
	return mktime(0,0,0,$month,$day,$year);
}

/**
 * incremente une date
 * @param string sqlDate $date1
 * @param integer $incDay
 * @return string sqlDate YYYY-MM-DD
 */
function incrementDateSql($date1, $incDay=1){
	$time1=sqlDateToTime($date1);
	$time2 = incrementDate($time1, $incDay);

	return timeToSqlDateTime($time2);
}


/**
 * createDateInterval
 * creation de date espac&eacute;e de 1 jour
 * return array of time
 * @param integer timestamp $time1
 * @param integer timestamp $time2
 */
function createDateInterval($time1, $time2, $excludeT2){
	//echo "date1 $time1 - date2 $time2 <br>";
	$array = array();

	$time=$time1;
	$timeEnd = $time2;
	if ($excludeT2){
		$timeEnd--;
	}
	$cpt=0;
	while ( $time <= $timeEnd ){
		$array[$cpt]=$time;
		$cpt++;
		$time = incrementDate($time);
		//echo "[".formatCalendar($time)."]  ";
	}

	return $array;
}

/**
 *
 * createTimeIntervalFromSqlDate
 * return array of time
 * @param $sqlDate1 YYYY-MM-DD
 * @param $sqlDate2 YYYY-MM-DD
 */
function createTimeIntervalFromSqlDate($sqlDate1, $sqlDate2, $excludeT2=1){
	//echo "date1 $sqlDate1 - date2 $sqlDdate2 <br>";
	$time1 = sqlDateToTime($sqlDate1);
	$time2 = sqlDateToTime($sqlDate2);
	return createDateInterval($time1, $time2, $excludeT2);
}

/**universal
 * Fonction qui retourne le numéro de la semaine par rapport a une date - Norme
ISO-8601
* Exemple: "echo numero_semaine(12,3,2004);" -> 11
*
* @param jour:Int Jour
* @param mois:Int Mois
* @param annee:Int Année
* @return integer Numéro de semaine
*
* @author Thomas Pequet
* @url http://www.memotoo.com
* @version 1.0
*/
function numeroSemaine($jour,$mois,$annee){
	/*
	 * Norme ISO-8601:
	* - La semaine 1 de toute année est celle qui contient le 4 janvier ou que
	la semaine 1 de toute année est celle qui contient le 1er jeudi de janvier.
	* - La majorité des années ont 52 semaines mais les années qui commence un
	jeudi et les années bissextiles commençant un mercredi en possède 53.
	* - Le 1er jour de la semaine est le Lundi
	*/

	// Définition du Jeudi de la semaine
	if (date("w",mktime(12,0,0,$mois,$jour,$annee))==0) // Dimanche
		$jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-3*24*60*60;
	else if (date("w",mktime(12,0,0,$mois,$jour,$annee))<4) // du Lundi au Mercredi
		$jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)+(4-date("w",mktime(12,
				0,0,$mois,$jour,$annee)))*24*60*60;
	else if (date("w",mktime(12,0,0,$mois,$jour,$annee))>4) // du Vendredi au Samedi
		$jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee)-(date("w",mktime(12,0,
				0,$mois,$jour,$annee))-4)*24*60*60;
	else // Jeudi
		$jeudiSemaine = mktime(12,0,0,$mois,$jour,$annee);

	// Définition du premier Jeudi de l'année
	if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==0) // Dimanche
	{
		$premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+4*24*60*
		60;
	}
	else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))<4) // du Lundi au Mercredi
	{
		$premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(4-date(
				"w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine))))*24*60*60;
	}
	else if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))>4) // du Vendredi au Samedi
	{
		$premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine))+(7-(date
				("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))-4))*24*60*60;
	}
	else // Jeudi
	{
		$premierJeudiAnnee = mktime(12,0,0,1,1,date("Y",$jeudiSemaine));
	}

	// Définition du numéro de semaine: nb de jours entre "premier Jeudi de l'année" et "Jeudi de la semaine";
	$numeroSemaine =     (	(	date("z",mktime(12,0,0,date("m",$jeudiSemaine),date("d", $jeudiSemaine),date("Y",$jeudiSemaine))) -
			date("z",mktime(12,0,0,date("m",$premierJeudiAnnee),date ("d",$premierJeudiAnnee),date("Y",$premierJeudiAnnee)))
	) / 7 ) + 1;

	// Cas particulier de la semaine 53
	if ($numeroSemaine==53)
	{
		// Les années qui commence un Jeudi et les années bissextiles commençant un Mercredi en possède 53
		if (date("w",mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==4 || (date("w"
				,mktime(12,0,0,1,1,date("Y",$jeudiSemaine)))==3 && date("z",mktime(12,0,0,12,31,date("Y",$jeudiSemaine)))==365))
		{
			$numeroSemaine = 53;
		}
		else
		{
			$numeroSemaine = 1;
		}
	}

	//echo $jour."-".$mois."-".$annee." (".date("d-m-Y",$premierJeudiAnnee)." - ".date("d-m-Y",$jeudiSemaine).") -> ".$numeroSemaine."<BR>";

	return sprintf("%02d",$numeroSemaine);
}


?>