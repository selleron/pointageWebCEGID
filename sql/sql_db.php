<?php

// fonctionnalites de base
// echo "include sql_db.php<br>";
$SQL_DB_PHP = "loaded";

include_once (dirname ( __FILE__ ) . "/basic.php");
include_once (dirname ( __FILE__ ) . "/param_table_db.php");
include_once (dirname ( __FILE__ ) . "/../configuration/form_db_config.php");


// key info
// $param[$KEY_INFO][$KEY_INFO_TYPE, $KEY_INFO_TYPE_SIZE][Colonne]

class KEY_INFO{
	const KEY_INFO              = "array key info";
	const KEY_INFO_FIELD        = "array key info flag";
	const KEY_INFO_TYPE         = "array key info type";
	//const KEY_INFO_TYPE_SIZE  = "array key info type size";
	const KEY_INFO_TYPE_SUFFIX  = "SUFFIX";
	const KEY_INFO_TYPE_SIZE    = "SIZE";
	const KEY_INFO_TYPE_TD      = "TD";
	const KEY_INFO_TYPE_TD_EVAL = "TD_EVAL";
	const KEY_INFO_TYPE_FORMAT  = "FORMAT";
	const KEY_INFO_STATUS       = "STATUS";
	const KEY_INFO_STYLE        = "array key info style";
}

$KEY_INFO           = KEY_INFO::KEY_INFO;
$KEY_INFO_FIELD     = KEY_INFO::KEY_INFO_FIELD;
$KEY_INFO_TYPE      = KEY_INFO::KEY_INFO_TYPE;
$KEY_INFO_TYPE_SIZE = KEY_INFO::KEY_INFO_TYPE_SIZE;
$KEY_INFO_STATUS    = KEY_INFO::KEY_INFO_STATUS;



function IsSqlColIsNamed($col){
    $res =  strpos("!".$col," as ")>0;
    //echoTD("test is named : $col => $res");
    return $res;
}


function getNameSqlCol($col){
    if (IsSqlColIsNamed($col)){
        $idx = strpos($col," as ");
        return substr($col,$idx+4);
    }
    else{
        return $col;
    }
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
		$nbRes = mysqli_num_rows ( $Resultat );
	}
	return $nbRes;
}

/**
 * mysqlRowsCount
 * @param Sql result $Resultat
 * @return number
 */
function mysqlRowsCount($Resultat){
    return mysqlNumrows($Resultat);
}

/**
 * mysqlBeginTransaction
 * @return request
 */
function mysqlBeginTransaction(){
    $request = "START TRANSACTION";
    showSQLAction($request);
    return mysqlQuery($request);
}

/**
 * mysqlCommit
 * @return request
 */
function mysqlCommit(){
    $request = "Commit";
    showSQLAction($request);
    return mysqlQuery($request);
}

/**
 * mysqlRollback
 * @return request
 */
function mysqlRollback(){
    $request = "Rollback";
    showSQLAction($request);
    return mysqlQuery($request);
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
				    $txt = $defaultValue;
				}
				else{
					$txt = "$txt".mysqlResult($res, 0, 0, getErrorMessage("not found on request $txt")); 
					//showError($txt);
				}
			}
			
		} else {
			$keys = arrayKeys ( $Resultat );
			$txt = getErrorMessage("result key not found : resultat[$c][$cpt] <br> ".arrayToString ( $keys ));
		}
		return $txt;
	} else {
		if (isset ( $defaultValue )) {
			if ($cpt >= mysqlNumrows ( $Resultat )) {
				return $defaultValue;
			}
		}
		return mysqli_result2 ( $Resultat, $cpt, $c );
	}
}

/**
 * mysqlClose
 * @return unknown
 */
function mysqlClose(){
    global $CONNECTION_ID;
    return mysqli_close($CONNECTION_ID);
}

function mysqli_result2($result,$row,$field=0) {
    if ($result===false) return false;
    if ($row>=mysqli_num_rows($result)) return false;
    if (is_string($field) && !(strpos($field,".")===false)) {
        $t_field=explode(".",$field);
        $field=-1;
        $t_fields=mysqli_fetch_fields($result);
        for ($id=0;$id<mysqli_num_fields($result);$id++) {
            if ($t_fields[$id]->table==$t_field[0] && $t_fields[$id]->name==$t_field[1]) {
                $field=$id;
                break;
            }
        }
        if ($field==-1) return false;
    }
    mysqli_data_seek($result,$row);
    $line=mysqli_fetch_array($result);
    return isset($line[$field])?$line[$field]:false;
}


/**
 * mysqlResultExist
 * @param sql result $Resultat
 * @param column $c
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
			return mysqlResult ( $Resultat, $cpt, $c );
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
        $res = mysqli_num_fields ( $resultat );
    }
    
    return $res;
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
        $name = mysqli_field_name ( $resultat, $cpt );
    }
    return $name;
}


function mysqli_field_name($result, $field_nr){
    //debug_print_backtrace();
    return mysqli_fetch_field_direct($result, $field_nr)->name;
}

function mysqli_tablename($sqlResult){
    $field = mysqli_fetch_fields($sqlResult);
    if (isset($field["table"])){
        $name = $field["table"];
    }
    else{
        $name = "undefine table";
    }
    return $name;
}

function mysqli_field_flags($result, $field_nr){
    return mysqli_fetch_field_direct($result, $field_nr)->flags;
}

function mysqli_field_type($result, $field_nr){
    $type =  mysqli_fetch_field_direct($result, $field_nr)->type;
    $type2 = $type;
    switch ($type){
        case SQL_TYPE_CODE::SQL_INT : $type2 = SQL_TYPE::SQL_INT;
        break;
        case SQL_TYPE_CODE::SQL_REAL : $type2 = SQL_TYPE::SQL_REAL;
        break;
        case SQL_TYPE_CODE::SQL_DOUBLE : $type2 = SQL_TYPE::SQL_REAL;
        break;
        case SQL_TYPE_CODE::SQL_DATE : $type2 = SQL_TYPE::SQL_DATE;
        break;
        case SQL_TYPE_CODE::SQL_STRING : $type2 = SQL_TYPE::SQL_STRING;
        break;
        case SQL_TYPE_CODE::SQL_TEXT : $type2 = SQL_TYPE::SQL_TEXT;
        break;
        case SQL_TYPE_CODE::SQL_BLOB : $type2 = SQL_TYPE::SQL_BLOB;
        break;
        case SQL_TYPE_CODE::SQL_REQUEST : $type2 = SQL_TYPE::SQL_REQUEST;
        break;
    }
    //$name = mysqli_field_name($result, $field_nr);
    //echoTD(" $name - type : $type == $type2");
    return $type2;
}

function mysqli_field_len($result, $field_nr){
    return mysqli_fetch_field_direct($result, $field_nr)->length;
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
		return mysqli_field_type ( $Resultat, $idx );
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
function mysqlFieldTypeSize($Resultat, $idx, $param=NULL) {
	if (is_array ( $Resultat )) {
		global $KEY_INFO_TYPE_SIZE;
		return mysqlFieldValueByKey ( $Resultat, $idx, KEY_INFO::KEY_INFO_TYPE_SIZE, "" );
	} else {
		return mysqli_field_len ( $Resultat, $idx );
	}
}

/**
 * getFormStyleSize
 * @param array $Resultat request result
 * @param array $param parameter sql
 * @param int|String $idx column index or column name
 * @return int|NULL
 */
function getFormStyleSize($Resultat, $param, $idx){
    if (isset($param)){
        return getFormStyleKey($Resultat, $param, $idx, KEY_INFO::KEY_INFO_TYPE_SIZE);
    }
    return NULL;
}

function getFormStyleSuffix($Resultat, $param, $idx){
    if (isset($param)){
        return getFormStyleKey($Resultat, $param, $idx, KEY_INFO::KEY_INFO_TYPE_SUFFIX);
    }
    return NULL;
}

/**
 * getFormStyleTD
 * 
 * @param unknown $Resultat
 * @param unknown $param
 * @param unknown $idx
 * @return unknown|NULL
 */
function getFormStyleTD($Resultat, $param, $idx){
    if (isset($param)){
        $format = getFormStyleKey($Resultat, $param, $idx, KEY_INFO::KEY_INFO_TYPE_TD);
        return $format; 
    }
    return NULL;
}

/**
 * getFormStyleTDEval
 * 
 * @param unknown $Resultat
 * @param unknown $param
 * @param unknown $idx
 * @param unknown $res
 * @return NULL|unknown|NULL
 */
function getFormStyleTDEval($Resultat, $param, $idx, $res){
    if (isset($param)){
        $format = getFormStyleKey($Resultat, $param, $idx, KEY_INFO::KEY_INFO_TYPE_TD_EVAL);
        if ($format != ""){
            eval( $format );
        }
        return $format;
    }
    return NULL;
}

/**
 * getFormStyleFormat
 * 
 * @param unknown $Resultat
 * @param unknown $param
 * @param unknown $idx
 * @param unknown $res
 * @return unknown
 */
function getFormStyleFormat($Resultat, $param, $idx, $res){
     if (isset($param)){
         $format =  getFormStyleKey($Resultat, $param, $idx, KEY_INFO::KEY_INFO_TYPE_FORMAT);
         if ($format != ""){
             eval( $format );
         }
     }
    return $res;
}

function numberFormat( $number , $decimals = 0 ,  $dec_point = "." ,  $thousands_sep = "," ){
    if (is_numeric($number)){
        return number_format( $number ,  $decimals ,  $dec_point  , $thousands_sep  );
    }
    else{
        return $number;
    }
}

/**
 * getFormStyleStatus
 * @param array $Resultat request result
 * @param array $param parameter sql
 * @param int|String $idxField column index or column name
 * @return NULL
 */
function getFormStyleStatus($Resultat, $param, $idxField){
    $status=NULL;
    if (isset($param)){
        $status = getFormStyleKey($Resultat, $param, $idxField, KEY_INFO::KEY_INFO_STATUS);
    }
    if (!isset($status)){
        $status = mysqlFieldStatus($Resultat, $idxField, $param);
    }
    return $status;
}


 function getFormStyleKey ($Resultat, $param, $idx, $key) {
     global $SHOW_FORM_VARIABLE_STYLE;
    $style = getFormStyleArray($Resultat, $param, $idx);
    if ($SHOW_FORM_VARIABLE_STYLE=="yes"){
        echoTD("[\"$key\"]");
    }
    if (isset($style)){
        if(isset($style[$key])){
            $value = $style[$key];
            return $value;
        }
    }
     return NULL;
 }
    

/**
 * getFormStyleArray
 * @param array $Resultat request result
 * @param array $param parameter sql
 * @param int|String $idx column index or column name
 * @return array
 */
function getFormStyleArray($Resultat, $param, $idx){
    if (isset($param)){
        $form="";
        if (isset($param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT])){
            $form = $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];
        }
        $variable = mysqlFieldName($Resultat, $idx);
        //$variable = mysqli_field_name($Resultat, $idx);
        global $SHOW_FORM_VARIABLE_STYLE;
        if ($SHOW_FORM_VARIABLE_STYLE=="yes"){
            echoTD("getFormStyle() search \$FORM_STYLE[\"$form\"][\"$variable\"]");
        }
        global $FORM_STYLE;
        //$value = $FORM_STYLE[$form][$variable][KEY_INFO::KEY_INFO_TYPE_SIZE];
        if (isset($FORM_STYLE) && isset($FORM_STYLE[$form]) && isset($FORM_STYLE[$form][$variable])){
            return $FORM_STYLE[$form][$variable];
            
        }
    }
    return NULL;
}

/**
 * getFormStyleSize2
 * 
 * @param unknown $size
 * @param unknown $form
 * @param unknown $col
 * @param string $ind
 * @return unknown
 */
function getFormStyleSize2($size, $form, $col, $ind=""){
    global $TRACE_FORM_FIELD_STYLE;
    showActionVariable("Field : $form - $col", $TRACE_FORM_FIELD_STYLE);
    global $FORM_STYLE;
    if (isset($FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_SIZE])){
        return $FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_SIZE];
    }
    return $size;
}

/**
 * getFormStyleTD2
 * 
 * @param unknown $td
 * @param unknown $form
 * @param unknown $col
 * @param string $ind
 * @return unknown
 */
function getFormStyleTD2($td, $form, $col, $ind=""){
    global $TRACE_FORM_FIELD_STYLE;
    //showActionVariable("Field : $form - $col", $TRACE_FORM_FIELD_STYLE);
    global $FORM_STYLE;
    if (isset($FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_TD])){
        return $FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_TD];
    }
    return $td;
}

/**
 * getFormStyleSuffix2
 * @param unknown $suffix
 * @param unknown $form
 * @param unknown $col
 * @param string $ind
 * @return string
 */
function getFormStyleSuffix2($suffix, $form, $col, $ind=""){
    global $TRACE_FORM_FIELD_STYLE;
    global $FORM_STYLE;
    if (isset($FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_SUFFIX])){
        return $FORM_STYLE[$form][$col][KEY_INFO::KEY_INFO_TYPE_SUFFIX];
    }
    return $suffix;
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
	    $col = $idxCol;
	    if (is_numeric($idxCol)){
	        $col = mysqlFieldName($Resultat, $idxCol);
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
		$flags = mysqli_field_flags ( $Resultat, $idx );
	}
	return $flags;
}


/**
 * mysqlQuery
 * generer une requete Sql a partir d'une String SQL
 *
 * @param
 *        	txt sql $request
 * @return request result
 */
function mysqlQuery($request) {
    global $CONNECTION_ID;
    $resultat = mysqli_query ($CONNECTION_ID, $request );
	showSQLError ( "", $request . "<br><br>" );
	//var_dump($resultat);
	return $resultat;
}


function mysqlAffectedRows(){
    global $CONNECTION_ID;
    return mysqli_affected_rows($CONNECTION_ID);    
}

function mysqlInsertId(){
    global $CONNECTION_ID;
    return mysqli_insert_id($CONNECTION_ID);
}

/**
 * mysqlQueries
 * 
 * @param array $requests
 * @return request result or null
 */
function mysqlQueries($requests){
    if (is_array($requests)){
        foreach ($requests as $req){
            //showSQLAction($req);
            mysqlQuery($req);
        }
    }
    else{
        return mysqlQuery($requests);
    }
}


function mySqlError(){
    global $CONNECTION_ID;
    return mysqli_error($CONNECTION_ID);
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