<?PHP
$TABLE_DB_PHP = "loaded";

include_once 'connection_db.php';
include_once 'tool_db.php';

/**
 * application des actions sur la page projet
 */
function applyGestionTable($table, $cols, $form_name, $colFilter = "") {
	// trace
	$url = getCurrentURL ();
	$action = getActionGet ();
	showAction ( "action : [$action]  $url" );
	// end trace
	
	$res = insertInTableByGet ( $table, $cols, $form_name );
	if ($res <= 0) {
		// showSQLAction("col filter : $colFilter");
		$res = editTableByGet ( $table, $cols, $form_name, $colFilter );
	}
	if ($res <= 0) {
		$res = updateTableByGet ( $table, $cols, $form_name );
	}
	if ($res <= 0) {
		$res = exportCSVTableByGet ( $table, $cols, $cols, $form_name );
	}
	if ($res <= 0) {
		$res = importCSVTableByGet ( $table, $cols, $form_name );
	}
	// insertOrupdateTableByGet ($table, $cols, $form_name);
	if ($res <= 0) {
		$res = applyDeleteTableByGet ( $table, $cols, $form_name, "" );
	}
	
	return $res;
}

/**
 * import from file
 *
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $form_name        	
 */
function importCSVTableByGet($table, $cols, $form_name) {
	if ((getActionGet () == "import")) {
		$array = actionImportCSV ();
		
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
 * @param unknown $table        	
 * @param unknown $colsSet        	
 * @param unknown $colsSetExport        	
 * @param matrice $matrice
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
 * @param unknown $table        	
 * @param unknown $colsSet        	
 * @param unknown $form_name        	
 * @return number 1 si ok 0 si nothing
 */
function exportCSVTableByGet($table, $colsSet, $colsSetExport, $form_name) {
	if ((getActionGet () == "export CSV") || (getActionGet () == "exportCSV")) {
		
		// trace
		$url = getCurrentURL ();
		$action = getActionGet ();
		showAction ( "action : [$action]  $url" );
		showAction ( "CSV : $table - [$colsSet] => [$colsSetExport]" );
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
		$fileName = $table . uniqid () . ".csv";
		$path = $PATH_UPLOAD_DIRECTORY . '/' . $fileName;
		$url = $URL_UPLOAD_DIRECTORY . '/' . $fileName;
		showSQLAction ( "table : $table <br>" . "colsSet : $colsSet<br>" . "use data from url : " . is_array ( $colID ) . "<br>" . "nb columns : $nbCol - nb row : $nbRow<br>" . "current directory : $dir<br>" . "directory upload : $PATH_UPLOAD_DIRECTORY<br>" . "url upload : $URL_UPLOAD_DIRECTORY<br>" . "file name : $fileName<br>" . "file path : $path<br>" );
		
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
 * @param array[row][col] $matrice        	
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
 * @param unknown $matrice        	
 * @param unknown $infoName        	
 * @param string $default        	
 * @param string $comment        	
 * @return unknown
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
 * @param unknown $handle        	
 * @param unknown $key        	
 * @param unknown $value        	
 * @param string $comment        	
 */
function exportCSVKeyValue($handle, $key, $value, $comment = "#") {
	$col [0] = "$comment" . $key;
	$col [1] = $value;
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
 * @param
 *        	file handler $handle
 * @param string $table
 *        	: table name
 */
function exportCSVDataURL($handle, $table) {
	$sql = "select * from $table";
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
 * @param unknown $table        	
 * @param unknown $cols        	
 */
function applyDeleteTableByGet($table, $cols, $form_name) {
	if (getActionGet () == "delete") {
		deleteTableByGet ( $table, $cols, $form_name );
		return 1;
	}
	return 0;
}

/**
 * deleteTableByGet
 * si idTable=="", on delete avec toutes les colonnes
 *
 * @param unknown $table        	
 * @param unknown $cols        	
 */
function deleteTableByGet($table, $cols, $form_name, $row=NULL, $trace = "no") {
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
 * deleteInTable
 *
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $idTable        	
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
 * deleteInTable
 *
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $idTable        	
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
function editTableByGet($table, $cols, $form_name, $subParam = "") {
	if (getActionGet () == "edit") {
		$res = editTable2 ( $table, $cols, $form_name, $subParam );
	} else {
		$res = 0;
	}
	return $res;
}



/**
 * editTable2
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
function editTable2($table, $cols, $form_name, $subParam = null) {
	if ($subParam == "") {
		$subParam = null;
	} 
	
	global $ID_TABLE_GET;
	$idTable = getURLVariable ( $ID_TABLE_GET );
	if ($idTable == "") {
		$columns = stringToArray ( $cols );
		$values = getURLVariableArray ( $columns );
		$condition = createSqlWhere ( $columns, $values );
		$subParam = updateParamSqlCondition ( $subParam, $condition );
		editTable ( $table, $cols, "", $form_name, $subParam );
	} else {
		// echo "idTable : $idTable <br>";
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
function updateTableByGet($table, $cols, $form_name, $reedit = "yes") {
	if (getActionGet () == "update") {
		return updateTableByGet2($table, $cols, $form_name, $reedit);
// 		// on execute le update
// 		$sql = createSqlUpdateByIdAndCondition ( $table, $cols, $form_name );
// 		showSQLAction ( "update action : $sql" );
// 		mysqlQuery ( $sql );
// 		// $txt = "sql result : " . mysqlQuery ( $sql ) . " " . mySqlError ();
// 		// showAction ( $txt );
		
// 		// on reaffiche les information de l'update
// 		if ($reedit == "yes") {
// 			global $ID_TABLE_GET;
// 			$idTable = getURLVariable ( $ID_TABLE_GET );
// 			if ($idTable) {
// 				editTable ( $table, $cols, $idTable, $form_name );
// 			} else {
// 				$columns = stringToArray ( $cols );
// 				$arrayValues = getURLVariableArray ( $columns );
// 				$condition2 = createSqlWhereArray ( $columns, $arrayValues );
// 				$subParam = updateParamSqlCondition ( $subParam, $condition2 );
// 				editTable ( $table, $cols, $idTable, $form_name, $subParam );
// 			}
// 		}
// 		return 1;
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
 * @return 1
 */
function updateTableByGet2($table, $cols, $form_name, $reedit = "yes") {
        // on execute le update
        $sql = createSqlUpdateByIdAndCondition ( $table, $cols, $form_name, null );
        //showSQLAction ( "update action : $sql" );
        mysqlQuery ( $sql );
        // $txt = "sql result : " . mysqlQuery ( $sql ) . " " . mySqlError ();
        // showAction ( $txt );
        
        // on reaffiche les information de l'update
        if ($reedit == "yes") {
            global $ID_TABLE_GET;
            $idTable = getURLVariable ( $ID_TABLE_GET );
            if ($idTable) {
                editTable ( $table, $cols, $idTable, $form_name );
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
 * @param unknown $table        	
 * @param unknown $columnsString        	
 * @param unknown $form_name        	
 * @param string $cpt        	
 * @param unknown $trace        	
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
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $form        	
 */
function insertInTableByGet($table, $cols, $form) {
	if (getActionGet () == "inserer") {
		$url = getCurrentURL ();
		insertInTable ( $table, $cols, $form );
		
		return 1;
	}
	return 0;
}

/**
 * editTable
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
	global $SQL_SHOW_COL_PROFIL;
	global $SQL_TABLE_PROFILS;
	global $TABLE_SIZE;
	global $COLUMNS_SUMMARY;
	
	$param = createDefaultParamSql ( $table, $cols/*, $sqlCondition*/ );
	$param = modifierTableParamSql ( $param, $form_name );
		
	// traitement id
	if ($idTable == "") {
		// nothing to do
	} else {
		$idKey = $param [$COLUMNS_SUMMARY] [0];
		$param = updateParamSqlWhereId ( $param, $idKey, $idTable );
	}
	
	$param [$TABLE_SIZE] = 740;
	$html = getCurrentURL ();
	
	// set sub param
	$param = updateParamSqlWithSubParam ( $param, $subParam );
	
	// trace
	global $SHOW_SQL_EDIT;
	if ($SHOW_SQL_EDIT == "yes") {
		$request = createRequeteTableData ( $param );
		showSQLAction ( $request );
	}
	
	// affichage
	$Resultat = requeteTableData ( $param );
	echo "<table>";
	editTableOneData ( $html, $Resultat, 0, $param, $idTable );
	echo "</table>";
	
	// showTableOneData($html, $Resultat, 0, $param);
}

/**
 * insertInTable
 *
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $form        	
 */
function insertInTable($table, $cols, $form) {
	// echo "search columns : [$cols]";
	$columns = stringToArray ( $cols );
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
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $form_name        	
 * @param string $condition        	
 * @return structure
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
 * @param unknown $table        	
 * @param unknown $cols        	
 * @param unknown $form_name        	
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
 * @param url $html
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