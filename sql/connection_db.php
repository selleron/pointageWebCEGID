<?PHP
	$CONNECTION_DB_PHP="loaded";
	//echo "connection_db.php executed.<br>";
	
	if( ! isset($CONNECTION_DB)){
		//echo "no data baseconnection found.<br>.Default selected.<br>";
		include_once(dirname(__FILE__). "/user_consultation_db.php");
	}
	else{
		//echo "connection info found.<br>";
	}

	//echo "my_sql_connect : db host[".$SQL_HOST."], db user[".$SQL_USER."]<br>";
	$CONNECTION_ID = mysql_connect($SQL_HOST, $SQL_USER, $SQL_PWD);
	//echo "connection ID [CONNECTION_ID]<br>";
	
	if ($CONNECTION_ID == ""){
		echo "Impossible de se connecter &agrave; la base de données : host : $SQL_HOST {".gethostbyname($SQL_HOST)."}- user : $SQL_USER -<br>";
		echo "[".mysql_error()."]<br>";
	}
	//or die("Impossible de se connecter &agrave; la base de données : host : $SQL_HOST {".gethostbyname($SQL_HOST)."}- user : $SQL_USER -[".mysql_error()."]");
	@mysql_select_db($SQL_DATABASE)
	or die("Impossible de selectionner $SQL_DATABASE");
	//echo "db connexion.<br>";
?>