<?php
include_once 'sql_db.php';

$SQL_COL_HISTORY = "HISTORY";
$SQL_COL_HISTORY_COMMENT = "HISTORY_COMMENT";

/**
 * historisationTable
 *
 * @param $table able
 *        	de depart
 * @param $table_historique table
 *        	a modifier
 * @param $columns colonne
 *        	a copier "cc1, cc2, cc3"
 * @param $condition condition
 *        	derriere le WHERE
 */
function historisationTable($table, $table_historique = "", $columns, $condition) {
	//showAction("historisationTable() historisation table  ");	
	if ($table_historique == "") {
		$table_historique = $table . "_history";
	}
	
	global $SQL_ALL_COL_DOCUMENT;
	$col = separateWith ( $columns, "," );
	$sql = "INSERT INTO `$table_historique` ( $col )
	SELECT $col
	FROM $table";
	if ($condition != "") {
		$sql = "$sql WHERE $condition";
	}
	
	$txt = "historisation $table $condition";
	//showAction ( $txt );
	
	showSQLAction ( $sql );
	$Resultat = mysqlQuery ( $sql );
	showSQLError ( "" );
	return $Resultat;
}

/**
 * createTableHistorique
 * 
 * @param unknown $table        	
 * @param string $historique        	
 */
function createTableHistorique($table, $historique = "") {
	global $SQL_COL_HISTORY;
	
	if ($table_historique == "") {
		$table_historique = $table . "_history";
	}
	
	$request = "CREATE TABLE $historique LIKE $table";
	mysqlQuery ( $request );
	if (! showSQLError ( "", "creation table historisation impossible" )) {
		$request = "ALTER TABLE `$historique` ADD `$SQL_COL_HISTORY` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP FIRST;";
	}
	mysqlQuery ( $request );
	showSQLError ( "", "alter table impossible" );
}

?>

