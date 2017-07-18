<?php

include_once("connection_db.php");

$COUNTER_DB="loader";
$TABLE_COUNTER="table_counter";

/**
 * getCounterValue
 * @param String $counter
 * @return integer
 */
function getCounterValue($counter){
	global $TABLE_COUNTER;
	
	$query="SELECT COUNT(*) FROM $TABLE_COUNTER WHERE $TABLE_COUNTER.name = '$counter'";
	$result = mysqlQuery($query);
	showSQLError("",$query);
	if ($result==""){
		createCounterTable();
		$query="SELECT COUNT(*) FROM $TABLE_COUNTER WHERE $TABLE_COUNTER.name = '$counter'";
		$result = mysqlQuery($query);
	}
	$v = mysqlResult($result, 0);
	showSQLError("",$query);
	if ( $v=="0" || $v==""){
		$query="INSERT INTO $TABLE_COUNTER ($TABLE_COUNTER.name, $TABLE_COUNTER.value) VALUES ('$counter', 0)";
		$result = mysqlQuery($query);
		showSQLError("",$query);
	}
	$query="UPDATE $TABLE_COUNTER SET $TABLE_COUNTER.value = $TABLE_COUNTER.value + 1 WHERE $TABLE_COUNTER.name = '$counter'";
	$result = mysqlQuery($query);
	showSQLError("",$query);

	$query="SELECT $TABLE_COUNTER.value FROM $TABLE_COUNTER WHERE $TABLE_COUNTER.name = '$counter'";
	$result = mysqlQuery($query);
	showSQLError("",$query);
	$v = mysqlResult($result, 0);

	return $v;
}

function dropCounter(){
	global $TABLE_COUNTER;
	
	$query = "DROP TABLE IF EXISTS `$TABLE_COUNTER`";
	mysqlQuery($query);
	showSQLError("");
	$query = "DROP FUNCTION IF EXISTS `GetCounterValue`";
	mysqlQuery($query);
	showSQLError("");
}

/**
 * create counter Table
 */
function createCounterTable(){
	global $TABLE_COUNTER;
	
	echo "create table $TABLE_COUNTER <br>";
	$query = "CREATE TABLE IF NOT EXISTS `$TABLE_COUNTER` (
		  `name` varchar(100) NOT NULL,
		  `value` bigint(20) NOT NULL,
		  PRIMARY KEY (`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	mysqlQuery($query);
	showSQLError("");
}


?>