<?PHP
$TABLE_DB_PHP = "loaded";

include_once 'connection_db.php';
include_once 'tool_db.php';
include_once '../configuration/labelAction.php';



/**
 * application des actions sur la page projet
 * 
 * @param string $table
 * @param string $cols
 * @param string $form_name
 * @param string $colFilter
 * @param array $param
 * @return number
 */
 function applyGestionTable($table, $cols, $form_name, $colFilter = "", $param=NULL) {
     if (!isset($param)){
        $condition="";
        $param = createDefaultParamSql ( $table, $cols, $condition );
        $param = updateTableParamSql ( $param, $form_name, $colFilter );
     }
     
     // trace
    global $TRACE_INFO_ACTION;
	$url = getCurrentURL ();
	$action = getActionGet ();
	showActionVariable( "action : [$action]  $url", $TRACE_INFO_ACTION );
	// end trace

	$res = insertInTableByGet ( $param );
	if ($res <= 0) {
		$res = editTableByGet ( /*$table, $cols, $form_name,*/ $param );
	}
	if ($res <= 0) {
		$res = updateTableByGet ( /*$table, $cols, $form_name,*/ $param );
	}
	if ($res <= 0) {
		$res = exportCSVTableByGet ( $table, $cols, $cols, $form_name );
	}
	if ($res <= 0) {
	    $res = importCSVTableByGet ( /*$table, $cols, $form_name,*/ $param );
	}
	// insertOrupdateTableByGet ($table, $cols, $form_name);
	if ($res <= 0) {
		$res = applyDeleteTableByGet ( /*$table, $cols, $form_name,*/$param, "" );
	}
	
	return $res;
}

/**
 * import from file
 * @param array param ($table , $cols, $form_name)
 */
function importCSVTableByGet( /*$table, $cols, $form_name,*/ $param  ) {
	if ((getActionGet () == "import")) {
		$array = actionImportCSV ();
		
		if (isset($param[PARAM_TABLE_SQL::TABLE_NAME_INSERT])){
		    $table = $param[PARAM_TABLE_SQL::TABLE_NAME_INSERT];
		    //echoTD("found PARAM_TABLE_SQL::TABLE_NAME_INSERT : $table");
		}
		else{
		    $table = $param[PARAM_TABLE_SQL::TABLE_NAME];
		    //echoTD("found PARAM_TABLE_SQL::TABLE_NAME : $table");
		}
		if (isset($param[PARAM_TABLE_SQL::COLUMNS_INSERT])){
		    $cols = $param[PARAM_TABLE_SQL::COLUMNS_INSERT];
		}
		else{
		    $cols = $param[PARAM_TABLE_SQL::COLUMNS_SUMMARY];
		}
		$cols = arrayToString($cols);
		$form_name = $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];
		
		
		
		$valueTable = getCSVValueFromMatrice ( $array, "table", $table );
		if ($valueTable != $table) {
			echo "error table to load : $table != $valueTable <br>";
			return;
		}
		
		$columns = getCSVTableColumn ( $array, $cols );
		
		$data = suppressCommentMatrice ( $array );
		foreach ( $data as $values ) {
			// $sql = createSqlReplace ( $table, $columns, $values );
			$key = $columns [0];
			$idTable = $values [0];
			$condition = createSqlWhereID ( $key, $idTable );
			$sql = createSqlInsert ( $table, $columns, $values );
			showSQLAction ( "table_db.importCSVTableByGet() insert action : $sql" );
			$res_query = mysqlQuery ( $sql );
			$nbRow = mysqlAffectedRows ();
			$res_error = mySqlError ();
			showSQLError ( "# $nbRow", $txt );
			if ($nbRow < 1) {
				$sql = createSqlUpdate ( $table, $columns, $values, $condition );
				
				showSQLAction ( "table_db.importCSVTableByGet() update action : $sql" );
				$res_query = mysqlQuery ( $sql );
				$nbRow = mysqlAffectedRows();
				$res_error = mySqlError ();
				
				$txt = "table_db.importCSVTableByGet() sql result : " . $res_query . " " . $res_error;
				showSQLError ( "# $nbRow", $txt );
			}
		}
		return 1;
	} else {
		return 0;
	}
}

/**
 * exportCSVArray
 *
 * @param string $table        	
 * @param string $colsSet (separator ,)       	
 * @param string $colsSetExport    (separator ,)    	
 * @param array $matrice
 *        	[col][row]
 * @return number 1 si ok 0 si nothing
 */
function exportCSVArray($table, $colsSet, $colsSetExport, $matrice) {    
   
	$colList = stringToArray ( $colsSet );
	$colListHeader = stringToArray ( $colsSetExport );
	$nbCol = count ( $matrice );
	if (is_array ( $matrice [$colList [0]] )) {
		$nbRow = count ( $matrice [$colList [0]] );
	} else {
		$nbRow = - 1;
	}
	
	global $PATH_UPLOAD_DIRECTORY;
	global $URL_UPLOAD_DIRECTORY;
	global $URL_IMAGES;
	
	$dir = getcwd ();
	$fileName = $table . uniqid () . ".csv";
	$path = $PATH_UPLOAD_DIRECTORY . '/' . $fileName;
	$url = $URL_UPLOAD_DIRECTORY . '/' . $fileName;
	showSQLAction ( "table : $table <br>" . "colsSet : $colsSet<br>" . "use data from url : <br>" . "nb columns : $nbCol - nb row : $nbRow<br>" . "current directory : $dir<br>" . "directory upload : $PATH_UPLOAD_DIRECTORY<br>" . "url upload : $URL_UPLOAD_DIRECTORY<br>" . "file name : $fileName<br>" . "file path : $path<br>" );
	
	// begin export
	$handle = fopen ( $path, 'w+' );
	// fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
	
	exportCSVKeyValue ( $handle, "table", $table );
	
	// export header
	// fputcsv($handle, $colListHeader);
	exportCSVTableColumn ( $handle, $colListHeader );
	
	// show data
	for($idxRow = 0; $idxRow < $nbRow; $idxRow ++) {
		for($idxCol = 0; $idxCol < $nbCol; $idxCol ++) {
		    if (isset($colList [$idxCol]) && isset( $matrice [$colList [$idxCol]])){
		        $colValue = $matrice [$colList [$idxCol]];
		    }
		    else{
		        $colValue = "";
		    }
		    if (isset($colValue [$idxRow])){
			$aRow [$idxCol] = $colValue [$idxRow];
		    }
		    else{
		        $aRow [$idxCol] = "";
		    }
		}
		myfputcsv ( $handle, $aRow );
	}
	
	// end export
	fclose ( $handle );
	
	$menuIconSize = "";
	echo "
		<ul>
		<li><a title=\"export\"    href=\"$url\">      <img src=\"$URL_IMAGES/menu_plan.png\"  $menuIconSize > $fileName</a></li>
		</ul>
		<br>";
	
	return 1;
}

/**
 * exportCSVTableByGet
 *
 * @param string $table  table name      	
 * @param string $colsSet  (separator ,)      	
 * @param string $form_name        	
 * @return number 1 si ok 0 si nothing
 */
function exportCSVTableByGet($table, $colsSet, $colsSetExport, $form_name) {
	if ((getActionGet () == "export CSV") || (getActionGet () == "exportCSV")) {
	    global $TRACE_INFO_EXPORT;
	    //debug_print_backtrace();
		// trace
		$url = getCurrentURL ();
		$action = getActionGet ();
		showActionVariable( "exportCSVTableByGet() : [$action]  $url -- CSV : $table - [$colsSet] => [$colsSetExport]", $TRACE_INFO_EXPORT );
		// end trace
		
		$colList = stringToArray ( $colsSet );
		$colListHeader = stringToArray ( $colsSetExport );
		$nbCol = count ( $colList );
		$colID = getURLVariable ( $colList [0] );
		if (is_array ( $colID )) {
			$nbRow = count ( $colID );
		} else {
			$nbRow = - 1;
		}
		
		global $PATH_UPLOAD_DIRECTORY;
		global $URL_UPLOAD_DIRECTORY;
		global $URL_IMAGES;
		
		$dir = getcwd ();
		//ici on regarde si la table n'est pas plutôt une requete
		$pos = strpos(strtolower($table), "select");
		if ($pos === FALSE){
		    $fileName = $table . uniqid () . ".csv";
		}
		else{
		    $fileName = "request_" . uniqid () . ".csv";
		}
		
		
		$path = $PATH_UPLOAD_DIRECTORY . '/' . $fileName;
		$url = $URL_UPLOAD_DIRECTORY . '/' . $fileName;
		showActionVariable ( "table : $table <br>" . "colsSet : $colsSet<br>" . "use data from url : " . is_array ( $colID ) . "<br>" . "nb columns : $nbCol - nb row : $nbRow<br>" . "current directory : $dir<br>" . "directory upload : $PATH_UPLOAD_DIRECTORY<br>" . "url upload : $URL_UPLOAD_DIRECTORY<br>" . "file name : $fileName<br>" . "file path : $path<br>", $TRACE_INFO_EXPORT );
		
		// begin export
		$handle = fopen ( $path, 'w+' );
		// fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
		
		exportCSVKeyValue ( $handle, "table", $table );
		
		if ($nbRow < 0) {
			// cas ou on doit utiliser une requete sql
			exportCSVDataURL ( $handle, $table );
		} else {
			// export header
			// fputcsv($handle, $colListHeader);
			exportCSVTableColumn ( $handle, $colListHeader );
			
			// show data
			for($idxRow = 0; $idxRow < $nbRow; $idxRow ++) {
				for($idxCol = 0; $idxCol < $nbCol; $idxCol ++) {
// 				    $colValues = getURLVariable ( $colList [$idxCol] );
// 				    if (isset($colValues [$idxRow])){
// 				        $aRow [$idxCol] = $colValues [$idxRow];
// 				    }
// 				    else{
// 				        $aRow [$idxCol] = "";
// 				    }
				    
 				    //$aValue = getURLVariableForRow( $colList [$idxCol], $idxRow );
				    //$aValue = getURLVariableSQLForm($colList [$idxCol], $form_name, "", "verbose", $idxRow);
				    $aValue = getURLVariableSQLForm($colList [$idxCol], $form_name, "", "quiet", $idxRow);
				    //echo ("CSV [  $idxCol  ][ $idxRow ] = $aValue <br>");
 				    if (isset($aValue)){
 				        $aRow [$idxCol] = $aValue;
 				    }
 				    else{
 				        $aRow [$idxCol] = "";
 				    }
				}
				myfputcsv ( $handle, $aRow );
			}
		}
		
		// end export
		fclose ( $handle );
		
		$menuIconSize = "";
		echo "
		<ul>
		<li><a title=\"export\"    href=\"$url\">      <img src=\"$URL_IMAGES/menu_plan.png\"  $menuIconSize > $fileName</a></li>
		</ul>
		<br>";
		
		return 1;
	}
	return 0;
}

/**
 * exportCSVColumn
 *
 * @param
 *        	file handler $handle
 * @param string[] $col
 *        	colonne liste
 * @param string $comment        	
 */
function exportCSVTableColumn($handle, $col, $comment = "#") {
	exportCSVKeyValue ( $handle, "colonne", "" );
	$col [0] = "$comment" . $col [0];
	myfputcsv ( $handle, $col );
}

/**
 * getCSVTableColumn
 *
 * @param array[$row][$col] $matrice        	
 * @param string $default        	
 * @param string $comment        	
 * @return array[] colmuns names
 */
function getCSVTableColumn($matrice, $default = "", $comment = "#") {
	return getCSVTableInfo ( $matrice, "colonne", $default, $comment );
}

/**
 * getCSVTableInfo
 *
 * @param array[$row][$col] $matrice        	
 * @param string $infoName        	
 * @param string $default        	
 * @param string $comment        	
 * @return array
 */
function getCSVTableInfo($matrice, $infoName, $default = "", $comment = "#") {
	$idx = getCSVIndexFromMatrice ( $matrice, $infoName );
	if ($idx >= 0) {
		$row = $matrice [$idx + 1];
		$row [0] = str_replace ( $comment, "", $row [0] );
		return $row;
	} else {
		showAction ( "CVS column not found : use $default" );
		return stringToArray ( $default );
	}
}

/**
 * exportCSVKeyValue
 *
 * @param file handler $handle  file handler      	
 * @param string $key        	
 * @param string $value        	
 * @param string $comment        	
 */
function exportCSVKeyValue($handle, $key, $value, $comment = "#") {
	//la cle est prefixée par #
    $col [0] = "$comment" . $key;
	
	//la valeur est remise sur une seule ligne
	$value = str_replace("\n", "#", $value);
	$col [1] = $value;
	
	//enregistrement dans le fichier
	myfputcsv ( $handle, $col );
}

/**
 * getCSVValueFromMatrice
 *
 * @param string[][] $matrice
 *        	matrice[row][col]
 * @param string $key
 *        	key to search
 * @param string $default
 *        	default value
 * @param string $comment
 *        	or prefix for key
 * @return string
 */
function getCSVValueFromMatrice($matrice, $key, $default = "", $comment = "#") {
	$key2 = "" . $comment . "$key";
	foreach ( $matrice as $array ) {
		$nbCol = count ( $array );
		if ($nbCol > 0) {
			$data = $array [0];
			if ($key2 == $data) {
				return $array [1];
			}
		}
	}
	
	return $default;
}

/**
 * getCSVIndexFromMatrice
 * @param array $matrice
 * @param string $key
 * @param string $comment
 * @return number
 */
function getCSVIndexFromMatrice($matrice, $key, $comment = "#") {
	$key2 = "" . $comment . "$key";
	$idx = 0;
	foreach ( $matrice as $array ) {
		$nbCol = count ( $array );
		if ($nbCol > 0) {
			$data = $array [0];
			if ($key2 == $data) {
				return $idx;
			}
		}
		$idx ++;
	}
	return - 1;
}

/**
 * exportCVSDataURL
 *
 * @param 	file handler $handle
 * @param string $table : table name
 * @param array or object : sql result
 */
function exportCSVDataURL($handle, $table) {
    //debug_print_backtrace();
    
    //ici on regarde si la table n'est pas plutôt une requete
    $pos = strpos(strtolower($table), "select");
    if ($pos === FALSE){
        $sql = "select * from $table";
    }
    else{
        $sql = $table;
    }
    
   $Resultat = mysqlQuery ( $sql );
	
	$nbRow = mysqlNumrows ( $Resultat );
	$nbCol = mysqlNumFields ( $Resultat );
	
	for($idxCol = 0; $idxCol < $nbCol; $idxCol ++) {
		$data = mysqlFieldName ( $Resultat, $idxCol );
		$aRow [$idxCol] = $data;
	}
	// fmyputcsv($handle, $aRow);
	exportCSVTableColumn ( $handle, $aRow );
	
	// show data
	for($idxRow = 0; $idxRow < $nbRow; $idxRow ++) {
		for($idxCol = 0; $idxCol < $nbCol; $idxCol ++) {
			$data = mysqlResult ( $Resultat, $idxRow, $idxCol );
			$aRow [$idxCol] = $data;
		}
		myfputcsv ( $handle, $aRow );
	}
}

/**
 * applyDeleteTableByGet
 *
 * @param string $table        	
 * @param string $cols  (separator ,)        	
 */
function applyDeleteTableByGet(/*$table, $cols, $form_name,*/ $param="") {
	if (getActionGet () == "delete") {
		deleteTableByGet ( /*$table, $cols, $form_name,*/ $param );
		return 1;
	}
	return 0;
}

/**
 * deleteTableByGet
 * si idTable=="", on delete avec toutes les colonnes
 *
 * @param string $table        	
 * @param string $cols  (separator ,)        	
 */
function deleteTableByGet(/*$table, $cols, $form_name,*/  $param, $row=NULL, $trace = "no") {
    if (isset($param[PARAM_TABLE_SQL::TABLE_NAME_INSERT])){
        $table = $param[PARAM_TABLE_SQL::TABLE_NAME_INSERT];
        //echoTD("found PARAM_TABLE_SQL::TABLE_NAME_INSERT : $table");
    }
    else{
        $table = $param[PARAM_TABLE_SQL::TABLE_NAME];
        //echoTD("found PARAM_TABLE_SQL::TABLE_NAME : $table");
    }
    if (isset($param[PARAM_TABLE_SQL::COLUMNS_INSERT])){
        $cols = $param[PARAM_TABLE_SQL::COLUMNS_INSERT];
    }
    else{
        $cols = $param[PARAM_TABLE_SQL::COLUMNS_SUMMARY];
    }
    $cols = arrayToString($cols);
    $form_name = $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];
    
    
    
    
	global $ID_TABLE_GET;
	$idTable = getURLVariable ( $ID_TABLE_GET );
	if ($idTable == "") {
		$columns = stringToArray ( $cols );
		$values = getURLVariableArray ( $columns, $row );
		deleteInTableByWhere ( $table, $columns, $values, $trace );
	} else {
		$columns = stringToArray ( $cols );
		deleteInTableByID ( $table, $columns [0], $idTable, $trace );
	}
}

/**
 * deleteInTableByWhere
 *
 * @param string $table        	
 * @param string $cols  (separator ,)        	
 * @param string key of table  $idTable        	
 */
function deleteInTableByWhere($table, $cols, $values, $trace = "no") {
	$sql = createSqlDelete ( $table, $cols, $values );
	
	if ($trace == "yes") {
		showSQLAction ( $sql );
	}
	$txt = "sql delete : " . mysqlQuery ( $sql ) . "   " . mySqlError ();
	if ($trace == "yes") {
		showAction ( $txt );
	}
	
	// historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
}

/**
 * deleteInTableByID
 *
 * @param string $table        	
 * @param string $cols  (separator ,)        	
 * @param string key of table  $idTable        	
 */
function deleteInTableByID($table, $colIdx, $idTable, $trace = "no") {
	$sql = createSqlDelete ( $table, $colIdx, $idTable );
	
	if ($trace == "yes") {
		showSQLAction ( $sql );
	}
	$txt = "sql delete : " . mysqlQuery ( $sql ) . "   " . mySqlError ();
	if ($trace == "yes") {
		showAction ( $txt );
	}
	
	// historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
}

/**
 * editTableByGet
 * test action name at first
 *
 * @param string $table
 *        	table name
 * @param string $cols
 *        	column name list
 * @param string $form_name
 *        	form name
 * @param string $colFilter
 *        	column name list in select
 * @return number 0 nothing 1 excuted
 */
function editTableByGet(/*$table, $cols, $form_name,*/ $subParam = "") {
	if (getActionGet () == LabelAction::ActionEdit) {
		$res = editTable2 ( /*$table, $cols, $form_name,*/ $subParam );
	} else {
		$res = 0;
	}
	return $res;
}



/**
 * editTable2
 *Affiche les champs de la table pour pouvoir les editer dans des textfields
 *
* @param string $table
 *        	table name
 * @param string $cols
 *        	column name list
 * @param string $form_name
 *        	form name
 * @param string $colFilter
 *        	column name list in select
 * @return number 0 nothing 1 excuted
 */
function editTable2(/*$table, $cols, $form_name,*/ $subParam = null) {
    //showSQLAction("editTable2( $table; $cols ; $form_name ) ");
    
    $table = $subParam[PARAM_TABLE_SQL::TABLE_NAME];
    $cols = arrayToString($subParam[PARAM_TABLE_SQL::COLUMNS_SUMMARY]);
    $form_name = $subParam[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];
    
	if ($subParam == "") {
		$subParam = null;
	} 
	
	global $ID_TABLE_GET;
	$idTable = getURLVariable ( $ID_TABLE_GET );
	if ($idTable == "") {
	    //ici par de cle primaire pour la selection de la donnee a editer
		//$columns = stringToArray ( $cols );
	    $columns = $subParam[PARAM_TABLE_SQL::COLUMNS_SUMMARY];
	    $columnsWhere = $columns;
	    if (isset($subParam[PARAM_TABLE_SQL::COLUMNS_FILTER]) && $subParam[PARAM_TABLE_SQL::COLUMNS_FILTER]!=""){
	        $columnsWhere = stringToArray($subParam[PARAM_TABLE_SQL::COLUMNS_FILTER]);
	    }
		$values = getURLVariableArray ( $columns );
		$condition = createSqlWhere ( $columnsWhere, $values );
		//echoTD("editTable2() no ID, where : $condition");
		$subParam = updateParamSqlCondition ( $subParam, $condition );
		editTable ( $table, $cols, "", $form_name, $subParam );
	} else {
		 //echo "editTable2() idTable : $idTable <br>";
		// var_dump($subParam);
		editTable ( $table, $cols, $idTable, $form_name, $subParam );
	}
	return 1;
}

/**
 * updateProjectByGet
 * use url to found information
 *
 * @param string $table        	
 * @param string $cols        	
 * @param string $form_name        	
 * @param string $reedit
 *        	yes|no
 * @return number 0 si pas d'action faite
 */
function updateTableByGet(/*$table, $cols, $form_name,*/ $param, $reedit = "yes") {
	if (getActionGet () == "update") {
		return updateTableByGet2(/*$table, $cols, $form_name,*/ $param, $reedit);
 	} else {
 		return 0;
 	}
}

/**
 * 
 * updateTableByGet2
 * realise l'update (ne test pas $action)
 * use url to found information
 *
 * @param string $table        	
 * @param string $cols        	
 * @param string $form_name        	
 * @param string $reedit   	yes|no
 * @return integer 1
 */
function updateTableByGet2(/*$table, $cols, $form_name,*/ $param, $reedit = "yes") {
    //if (isset($param) && $param!=""){
        if (isset($param[PARAM_TABLE_SQL::TABLE_NAME_INSERT])){
            $table = $param[PARAM_TABLE_SQL::TABLE_NAME_INSERT];
            //echoTD("found PARAM_TABLE_SQL::TABLE_NAME_INSERT : $table");
        }
        else{
            $table = $param[PARAM_TABLE_SQL::TABLE_NAME];
            //echoTD("found PARAM_TABLE_SQL::TABLE_NAME : $table");
        }
        if (isset($param[PARAM_TABLE_SQL::COLUMNS_INSERT])){
            $cols = $param[PARAM_TABLE_SQL::COLUMNS_INSERT];
        }
        else{
            $cols = $param[PARAM_TABLE_SQL::COLUMNS_SUMMARY];
        }
        $cols = arrayToString($cols);
        $form_name = $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];
    //}
    
    
    
        // on execute le update
        $sql = createSqlUpdateByIdAndCondition ( $table, $cols, $form_name, null );
        global $SHOW_SQL_UPDATE;
        //showAction ( "\$SHOW_SQL_UPDATE : $SHOW_SQL_UPDATE" );
        showActionVariable($sql, $SHOW_SQL_UPDATE);
        mysqlQuery ( $sql );
        //$txt = "sql result : " . mysqlQuery ( $sql ) . " " . mySqlError ();
        //showAction ( $txt );
        
        // on reaffiche les information de l'update
        if ($reedit == "yes") {
            
            
            
            global $ID_TABLE_GET;
            $idTable = getURLVariable ( $ID_TABLE_GET );
            if ($idTable) {
                editTable ( $table, $cols, $idTable, $form_name, $param );
            } else {
                $columns = stringToArray ( $cols );
                $arrayValues = getURLVariableArray ( $columns );
                $condition2 = createSqlWhereArray ( $columns, $arrayValues );
                $subParam = updateParamSqlCondition ( $subParam, $condition2 );
                editTable ( $table, $cols, $idTable, $form_name, $subParam );
            }
        }
        return 1;
}

function multiUpdateTableByGet2($table, $cols, $form_name) {
    showAction ( "multiUpdateTableByGet2()" );
    // on execute le update
    $sql = createMultiSqlUpdateByIdAndCondition ( $table, $cols, $form_name );
    mysqlQueries ( $sql );
    
    return 1;
}

function multiReplaceTableByGet2($table, $cols, $form_name) {
    showAction ( "multiReplaceTableByGet2()" );
    // on execute le replace
    $sql = createMultiSqlReplace ( $table, $cols, $form_name );
    mysqlQueries ( $sql );
    
    return 1;
}



// function insertOrUpdateTableByGet($table, $colsSet, $form_name, $cpt ) {
// if (getActionGet () == "insertorupdate") {
// $sql = createSqlInsertUpdate ( $table, $cols, $cols, $cpt );

// showSQLAction ( "update action : $sql" );
// //$txt = "sql result : " . mysqlQuery ( $sql ) . " " . mySqlError ();
// //showAction ( $txt );
// return 1;
// }
// return 0;
// }

/**
 * replaceTableByGet
 *
 * @param string $table        	
 * @param string $columnsString  (separator ,)        	
 * @param string $form_name        	
 * @param string $cpt        	
 * @param string $trace ("yes"/"no")        	
 * @return number
 */
function replaceTableByGet($table, $columnsString, $form_name, $cpt = "", $trace = "yes") {
	if (getActionGet () == "replace" || getActionGet () == "update") {
		$columns = stringToArray ( $columnsString );
		$values = getURLVariableArray ( $columns, $cpt );
		// $values = getURLVariableArraySQLForm ( $columns, $form );
		
		$sql = createSqlReplace ( $table, $columns, $values );
		
		if ($trace == "yes") {
			showSQLAction ( "table_db.replaceTableByGet() replace action : $sql" );
		}
		$txt = "table_db.replaceTableByGet() sql result : " . mysqlQuery ( $sql ) . " " . mySqlError ();
		// showAction ( $txt );
		return 1;
	} else {
		showSQLAction ( "table_db.replaceTableByGet() unkown action : " . getActionGet () );
	}
	return 0;
}

/**
 * insertInTableByGet
 *
 * @param array $param : sql parameters
 * name, columns & form are nedeed        	
 */
function insertInTableByGet($param) {
	if (getActionGet () == "inserer") {
		//$url = getCurrentURL ();
		insertInTable ( $param );
		
		return 1;
	}
	return 0;
}

/**
 * editTable
 *Affiche les champs de la table pour pouvoir les editer dans des textfields
 *
 * @param string $table
 *        	nom de la table
 * @param string $cols
 *        	liste des colonnes
 * @param string $idTable
 *        	value id de la table
 * @param string $form_name
 *        	nom du formulaire
 * @param string $sqlCondition
 *        	condition suplémentaire
 * @param string $subParam        	
 */
function editTable($table, $cols, $idTable, $form_name, $subParam = "") {
    //showSQLAction("editTable( $table; $cols ; $idTable; $form_name ) ");
    
    global $SQL_SHOW_COL_PROFIL;
	global $SQL_TABLE_PROFILS;
	global $TABLE_SIZE;
	global $COLUMNS_SUMMARY;
	
	$param = createDefaultParamSql ( $table, $cols/*, $sqlCondition*/ );
	$param = modifierTableParamSql ( $param, $form_name );
	$param [$TABLE_SIZE] = 740;
	$param = updateParamSqlWithSubParam ( $param, $subParam );
	
	// traitement id
	if ($idTable == "") {
		// nothing to do
	} else {
	    if (isset($param[PARAM_TABLE_SQL::COLUMNS_FILTER])&&($param[PARAM_TABLE_SQL::COLUMNS_FILTER]!="")){
	        $idKey = stringToArray($param[PARAM_TABLE_SQL::COLUMNS_FILTER]) [0];
	        $idKey = getNameSqlCol($idKey);
	    }
	    else {
		  $idKey = $param [$COLUMNS_SUMMARY] [0];
	    }
		$param = updateParamSqlWhereId ( $param, $idKey, $idTable );
	}
	
	$html = getCurrentURL ();
	
	
	// trace
	global $SHOW_SQL_EDIT;
	if ($SHOW_SQL_EDIT == "yes") {
		$request = createRequeteTableData ( $param );
		showSQLAction ( "[SHOW_SQL_EDIT] editTable() : $request" );
	}
	
	// affichage
	$Resultat = requeteTableData ( $param );
	echo "<table>";
	// -1 car on a qu'une valeur : equi. a 0
	editTableOneData ( $html, $Resultat, -1, $param, $idTable );
	//editTableOneData ( $html, $Resultat, 0, $param, $idTable );
	echo "</table>";
	
}

/**
 * insertInTable
 *
 * @param array $param : sql parameters
 * name, columns & form are nedeed        	
 */
function insertInTable($param) {
    showSQLAction("insertInTable()");
    
    //table name
    if (isset($param[PARAM_TABLE_SQL::TABLE_NAME_INSERT])){
        $table = $param[PARAM_TABLE_SQL::TABLE_NAME_INSERT];
    }
    else{
        $table = $param[PARAM_TABLE_SQL::TABLE_NAME];
    }
    //columns
    if (isset($param[PARAM_TABLE_SQL::COLUMNS_INSERT])){
        $columns = $param[PARAM_TABLE_SQL::COLUMNS_INSERT];
    }
    else{
        $columns = $param[PARAM_TABLE_SQL::COLUMNS_SUMMARY];
    }
    //forme
    $form = $param[PARAM_TABLE_FORM::TABLE_FORM_NAME_INSERT];

    showSQLAction("insertInTable( $table ; ".arrayToString($columns)." ; $form)");
    
	// echo "search columns : [$cols]";
	//$columns = stringToArray ( $cols );
	$values = getURLVariableArraySQLForm ( $columns, $form );
	
	// showAction($document2);
	$sql = createSqlInsert ( $table, $columns, $values );
	showSQLAction ( $sql );
	$txt = "sql result : " . mysqlQuery ( $sql ) . "   " . mySqlError ();
	showAction ( $txt );
	
	// historisationDocument("`$SQL_COL_DOCUMENT_NAME` = \"$documentName\"");
}

/**
 * prepareshowTable
 *
 * @param string $table        	
 * @param string $cols  (separator ,)        	
 * @param string $form_name        	
 * @param string $condition        	
 * @return array multi dimension structure
 */
function prepareshowTable($table, $cols, $form_name, $condition = "") {
	$param = createDefaultParamSql ( $table, $cols, $condition );
	$param = modifierTableParamSql ( $param, $form_name );                              
	
	//global $TABLE_FORM_NAME_INSERT;
	//$infoForm =  streamFormHidden ( $TABLE_FORM_NAME_INSERT, $form_name );
	//$param  = setInfoForm($param, $infoForm);
	
	
	// global $TABLE_SIZE;
	// $param [$TABLE_SIZE] = 740;
	return $param;
}

/**
 * showTable   
 * call
 * prepareshowTable
 * showTableByParam
 *
 * @param String $table        	
 * @param string $cols  (separator ,)        	
 * @param string $form_name        	
 * @param string $condition        	
 */
function showTable($table, $cols, $form_name, $condition = "") {
	$param = prepareshowTable ( $table, $cols, $form_name, $condition );
	showTableByParam ( $param );
}

/**
 * showTableByParam
 *
 * @param array[] $param
 *        	request param
 */
function showTableByParam($param) {
	showTableHeader ( $param );
	showTableData ( $param );
}

/**
 * showOnlyInsertTableByParam
 *
 * @param unknown $param        	
 */
/**
 * showOnlyInsertTableByParam
 * 
 * @param string url $html
 * @param array result or sql result $Resultat
 * @param sql parameter $param
 * @param array[col name] $value default value
 */
function showOnlyInsertTableByParam($html, $Resultat, $param, $value="") {
	showTableHeader ( $param );
	// showTableData ( $param );
	echo "<tr>";
	insertTableOneData ( $html, $Resultat, /*$cpt,*/ $param, $value );
	echo "</tr>";
	echo "</table>";
}

?>