<?php

// fonctionnalites de base
// echo "include sql_db.php<br>";
$SQL_DB_PHP = "loaded";

include_once (dirname ( __FILE__ ) . "/basic.php");

// key info
// $param[$KEY_INFO][$KEY_INFO_TYPE, $KEY_INFO_TYPE_SIZE][Colonne]
$KEY_INFO = "array key info";
$KEY_INFO_FIELD = "array key info flag";
$KEY_INFO_TYPE = "array key info type";
$KEY_INFO_TYPE_SIZE = "array key info type size";
$KEY_INFO_STATUS = "array key info status";

class KEY_INFO{
	const KEY_INFO = "array key info";
	const KEY_INFO_FIELD = "array key info flag";
	const KEY_INFO_TYPE = "array key info type";
	const KEY_INFO_TYPE_SIZE = "array key info type size";
	const KEY_INFO_STATUS = "array key info status";
	const KEY_INFO_STYLE = "array key info style";
}

/**
 * mysqlNumrows
 *
 * @param
 *        	array ou sql request result $Resultat
 * @return number rows count
 */
function mysqlNumrows($Resultat) {
	if (is_array ( $Resultat )) {
		$keys = arrayKeys ( $Resultat );
		$nbCol = count ( $keys );
		if ($nbCol == 0) {
			$nbRes = 0;
		} else {
			$nbRes = count ( $Resultat [$keys [0]] );
		}
	} else {
		$nbRes = mysql_numrows ( $Resultat );
	}
	return $nbRes;
}

/**
 * mysqlResult
 * @param
 *        	array ou sql request result $Resultat
 * @param integer $cpt
 *        	row line
 * @param
 *        	integer index position or name $c colum index
 * @return data : result
 */
function mysqlResult($Resultat, $cpt, $c, $defaultValue = "") {
	if (is_array ( $Resultat )) {
		global  $SHOW_SQL_TYPE_REQUEST_REQUEST;
		if (array_key_exists ( $c, $Resultat )) {
			if (array_key_exists ( $cpt, $Resultat [$c] )) {
				$txt = $Resultat [$c] [$cpt];
			} else {
				$keys = arrayKeys ( $Resultat [$c] );
				$txt = arrayToString ( $keys ) . " [$c]";
			}
		
			if (mysqlFieldType($Resultat, $c) == SQL_TYPE::SQL_REQUEST){
				$request = $txt;
				$res = mysqlQuery($request);
				if ($SHOW_SQL_TYPE_REQUEST_REQUEST == "yes"){
					$txt = "$request : ";
				}else{
					$txt="";
				}
				if( mysqlNumrows($res) < 1){
					$txt = "$txt-";
				}
				else{
					$txt = "$txt".mysqlResult($res, 0, 0,"not found on request $txt");
				}
			}
			
		} else {
			$keys = arrayKeys ( $Resultat );
			$txt = "result key not found : resultat[$c][$cpt] <br> ".arrayToString ( $keys );
		}
		return $txt;
	} else {
		if (isset ( $defaultValue )) {
			if ($cpt >= mysql_numrows ( $Resultat )) {
				return $defaultValue;
			}
		}
		return mysql_result ( $Resultat, $cpt, $c );
	}
}

/**
 * mysqlResultExist
 * @param unknown $Resultat
 * @param unknown $c
 * @return boolean|unknown
 */
function mysqlResultExist($Resultat, $c) {
	if (is_array ( $Resultat )) {
		if (array_key_exists ( $c, $Resultat )) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		if (is_numeric ( $c )) {
			return TRUE;
		} else {
			return mysql_result ( $Resultat, $cpt, $c );
		}
	}
}

/**
 *
 * @param unknown $resultat        	
 */
function arrayKeys($resultat) {
	global $KEY_INFO;
	$keys = array_keys ( $resultat );
	// var_dump($keys);
	// $idx = array_search ( $KEY_INFO, $resultat );
	$idx2 = indexOfValueInArray ( $keys, $KEY_INFO );
	if ($idx2 >= 0) {
		unset ( $keys [$idx2] );
	}
	
	$keys = array_values ( $keys );
	// var_dump($keys);
	return $keys;
}

/**
 *
 * @param unknown $resultat        	
 * @param unknown $row        	
 * @return number
 */
function mysqlNumFields($resultat) {
	if (is_array ( $resultat )) {
		$keys = arrayKeys ( $resultat );
		$res = count ( $keys );
	} else {
		$res = mysql_num_fields ( $resultat );
	}
	
	return $res;
}

/**
 *
 * @param
 *        	Array[column][row] or Sql result $Resultat
 *        	$Resultat[KEY_INFO][$key][column name] = info
 *        	column name = idx or array_keys($Resultat)[idx]
 * @param
 *        	numeric or string $idx : index column or column name
 * @param string $key
 *        	info name
 * @return string
 */
function mysqlFieldValueByKey($Resultat, $idx, $key, $default = "???") {
	if (is_numeric ( $idx )) {
		// if ($idx<0){
		// debug_print_backtrace();
		// }
		$keys = arrayKeys ( $Resultat );
		if (isset ( $keys [$idx] )) {
			$idx2 = $keys [$idx];
		} else {
			return $default;
		}
	} else {
		$idx2 = $idx;
	}
	
	global $KEY_INFO;
	if ($key == KEY_INFO::KEY_INFO_STYLE){
		//echoTD("search for Resultat [$KEY_INFO] [$key] [$idx / $idx2] : ".$Resultat [$KEY_INFO] [$key] [$idx2]."<br>");
		//var_dump($Resultat [$KEY_INFO]);
	}
	if (isset ( $Resultat [$KEY_INFO] )) {
		if (isset ( $Resultat [$KEY_INFO] [$key] )) {
			if (isset ( $Resultat [$KEY_INFO] [$key] [$idx2] )) {
				return $Resultat [$KEY_INFO] [$key] [$idx2];
			}
		}
	}
	return $default;
}

/**
 * mysqlFieldValueByKeyAndRow
 * 
 * @param sql result or param array $Resultat
 * @param index or name $idx column of result
 * @param String $key1 key of search
 * @param int $row  row in result
 * @param string $default default value
 * @return string
 */
function mysqlFieldValueByKeyAndRow($Resultat, $idx, $key1, $row, $default = "???") {
	$res = mysqlFieldValueByKey($Resultat, $idx, $key1);
	
	if (is_array($res) && isset($row)){
		if (isset($res[$row])){
			return $res[$row];
		}
	}
	return $res;
}



/**
 * mysqlFieldType
 *
 * @param
 *        	array sql $Resultat
 * @param string $idx        	
 * @return string SQL_XXX
 */
function mysqlFieldType($Resultat, $idx) {
	if (is_array ( $Resultat )) {
		global $KEY_INFO_TYPE;
		return mysqlFieldValueByKey ( $Resultat, $idx, $KEY_INFO_TYPE );
	} else {
		return mysql_field_type ( $Resultat, $idx );
	}
}

/**
 * mysqlFieldTypeSize
 *
 * @param
 *        	array sql $Resultat
 * @param string $idx        	
 * @return int size
 */
function mysqlFieldTypeSize($Resultat, $idx) {
	if (is_array ( $Resultat )) {
		global $KEY_INFO_TYPE_SIZE;
		return mysqlFieldValueByKey ( $Resultat, $idx, $KEY_INFO_TYPE_SIZE, "" );
	} else {
		return mysql_field_len ( $Resultat, $idx );
	}
}

/**
 * mysqlFieldStatus
 *
 * @param
 *        	array sql $Resultat
 * @param string $idx        	
 * @return string "enabled" | "disabled"
 */
function mysqlFieldStatus($Resultat, $idx, $param = "") {
	if (is_array ( $Resultat )) {
		global $KEY_INFO_STATUS;
		//$Resultat [$KEY_INFO] [$KEY_INFO_STATUS] [$column_name]
		return mysqlFieldValueByKey ( $Resultat, $idx, $KEY_INFO_STATUS, "enabled" );
	} else if ($param == null) {
		return "enabled";
	} else if ($param == "") {
		return "enabled";
	} else {
		$col = $idx;
		if (is_numeric($idx)){
			$col = mysqlFieldName($Resultat, $idx);
		}
		//echoTD("mysqlFieldStatus() parameter found. Search for idx : $idx / $col ...");
		return mysqlFieldStatus($param, $col,null);
	}
}

/**
 * mysqlFieldStyle
 * @param unknown $Resultat

 * @return string|unknown
 */
/**
 * 
 * @param array[][] ou sql result $Resultat
 * @param numeric or string $idx : index column or column name * @param string $param
 * @param request array param $param
 * @return string|unknown
 */
function mysqlFieldStyle($Resultat, $idxCol, $idxRow , $param = "") {
	if (is_array ( $Resultat )) {
		//$Resultat [$KEY_INFO] [$KEY_INFO_STYLE] [$column_name][$idxRow]
		return mysqlFieldValueByKeyAndRow ( $Resultat, $idxCol, KEY_INFO::KEY_INFO_STYLE, $idxRow, "" );
	} else if ($param == null) {
		return "";
	} else if ($param == "") {
		return "";
	} else {
		$col = $idx;
		if (is_numeric($idx)){
			$col = mysqlFieldName($Resultat, $idx);
		}
		//echoTD("mysqlFieldStatus() parameter found. Search for idx : $idx / $col ...");
		return mysqlFieldStyle($param, $col,null);
	}
}


/**
 * set value
 *
 * @param unknown $Resultat        	
 * @param unknown $col        	
 * @param unknown $row        	
 * @param unknown $value        	
 * @return unknown
 */
function setSQLValue($Resultat, $col, $row, $value) {
	$Resultat [$col] [$row] = $value;
	return $Resultat;
}
function unsetSQLRow($Resultat, $row) {
	$column = array_keys ( $Resultat );
	foreach ( $column as $col ) {
		echo "col del row : $row - col $col";
		unset ( $Resultat [$col] [$row] );
		// $Resultat= setSQLValue($Resultat, $col, $row, "");
	}
	
	return $Resultat;
}

/**
 *
 * @param unknown $Resultat        	
 * @param unknown $key
 *        	: column name
 * @param unknown $type        	
 */
function setSQLFlagType($Resultat, $key, $type) {
	global $KEY_INFO;
	global $KEY_INFO_TYPE;

	$Resultat [$KEY_INFO] [$KEY_INFO_TYPE] [$key] = $type;
	return $Resultat;
}

/**
 * 
 * @param array result $Resultat
 * @param unknown $key : column name
 * @param String $field equivalent sql flag result
 * @return $Resultat
 */
function setSQLFlagField($Resultat, $key, $field) {
	global $KEY_INFO;
	global $KEY_INFO_FIELD;

	$Resultat [$KEY_INFO] [$KEY_INFO_FIELD] [$key] = $field;
	return $Resultat;
}

/**
 * setSQLFlagTypeSize
 * specifie la taille d'un camp sql pour l'affichage
 *
 * @param
 *        	array or SQl Result $Resultat
 * @param String $key
 *        	colonne key
 * @param integer $size
 *        	taille en caractere du champ pour l'affichage
 * @return $Resultat modifié
 */
function setSQLFlagTypeSize($Resultat, $key, $size) {
	global $KEY_INFO;
	global $KEY_INFO_TYPE_SIZE;
	
	$Resultat [$KEY_INFO] [$KEY_INFO_TYPE_SIZE] [$key] = $size;
	return $Resultat;
}

/**
 * setSQLFlagStatus
 * specifie le status (enabled| disable) pour une colonne SQL
 *
 * @param
 *        	array sql $Resultat
 * @param
 *        	string or array $key
 * @param string $status
 *        	enabled | disabled
 * @return array
 */
function setSQLFlagStatus($Resultat, $key, $status = "enabled") {
	global $KEY_INFO;
	global $KEY_INFO_STATUS;
	
	if (is_array ( $key )) {
		foreach ( $key as $k ) {
			$Resultat = setSQLFlagStatus ( $Resultat, $k, $status );
		}
	} else {
		//echoTD("insert Resultat [$KEY_INFO] [$KEY_INFO_STATUS] [$key] = $status <br>");
		$Resultat [$KEY_INFO] [$KEY_INFO_STATUS] [$key] = $status;
	}
	return $Resultat;
}

/**
 * setSQLFlagStyle
 * @param unknown $Resultat
 * @param unknown $key
 * @param unknown $row
 * @param string $status
 * @return unknown
 */
function setSQLFlagStyle($Resultat, $key, $row, $style = "") {
	if (is_array ( $key )) {
		foreach ( $key as $k ) {
			$Resultat = setSQLFlagStyle ( $Resultat, $k, $row, $style );
		}
	} else {
		//echoTD("insert Resultat [".KEY_INFO::KEY_INFO."] [".KEY_INFO::KEY_INFO_STYLE."] [$key][$row] = $style <br>");
		$Resultat [KEY_INFO::KEY_INFO] [KEY_INFO::KEY_INFO_STYLE] [$key][$row] = $style;
	}
	return $Resultat;
}


/**
 * mysqlFieldFlags
 * retourne les flags d'une colonne d'un resultat SQL
 *
 * @param
 *        	array sql or request sql result $Resultat
 * @param
 *        	interger or String $idx column index or colonne name
 * @return String (primary key, ....
 */
function mysqlFieldFlags($Resultat, $idx) {
	if (is_array ( $Resultat )) {
		global $KEY_INFO_FIELD;	
		$flags = mysqlFieldValueByKey($Resultat, $idx, $KEY_INFO_FIELD); 
	} else {
		$flags = mysql_field_flags ( $Resultat, $idx );
	}
	return $flags;
}

/**
 * mysqlFieldName
 * retourne le nom de la colonne du resultat SQL
 *
 * @param
 *        	array sql or request sql result $resultat
 * @param
 *        	interger or String $cpt column index or colonne name
 * @return String column name
 */
function mysqlFieldName($resultat, $cpt) {
	if (is_array ( $resultat )) {
		$keys = arrayKeys ( $resultat );
		if (isset ( $keys [$cpt] )) {
			$name = $keys [$cpt];
		} else {
			echo "not found index $cpt in keys " . arrayToString ( $keys ) . " <br> ";
			return "!!!";
		}
	} else {
		$name = mysql_field_name ( $resultat, $cpt );
	}
	return $name;
}

/**
 * mysqlQuery
 * gérère une requete Sql a partir d'une String SQL
 *
 * @param
 *        	txt sql $request
 * @return request result
 */
function mysqlQuery($request) {
	$resultat = mysql_query ( $request );
	showSQLError ( "", $request . "<br><br>" );
	return $resultat;
}

/**
 * formatSqlDate
 * formate la date pour une sortie SQL
 *
 * @param integer $day
 *        	1-31
 * @param integer $month
 *        	1-12
 * @param integer $year
 *        	1900 - ...
 * @return string year-month-day
 */
function formatSqlDate($day, $month, $year) {
	$date = "$year-$month-$day";
	return $date;
}

/**
 * formatCSVDate
 * formate la date pour une sortie CVS
 *
 * @param integer $day
 *        	1-31
 * @param integer $month
 *        	1-12
 * @param integer $year
 *        	1900 - ...
 * @return string year-month-day
 */
function formatCSVDate($day, $month, $year) {
	$date = "$day/$month/$year";
	return $date;
}

?>