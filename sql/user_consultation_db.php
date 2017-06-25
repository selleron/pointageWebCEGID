<?PHP
	//echo "include user_consultation_db.php<br>";
	$USER_CONSULTATION_DB_PHP="loaded";
	
	//SERVEUR
	$SERVER_NAME = getenv("SERVER_NAME");
	$SQL_USER="not_configured";
	$SQL_COL_SIZEMAX_BLOB="1000000";
	
	//creation si besoin du fichier de configuration
	$CONFIGURATION_FILE=dirname(__FILE__)."/../configuration/$SERVER_NAME.config.php";
	//$CONFIGURATION_FILE=$_SERVER ['DOCUMENT_ROOT']."/configuration/$SERVER_NAME.config.php";
	//echo "search file [$CONFIGURATION_FILE]  <br>";
	if (!file_exists($CONFIGURATION_FILE)){
		echo "Configuration file [$CONFIGURATION_FILE] not exist.<br>";
		$file = $_SERVER ['DOCUMENT_ROOT']."/configuration/example.config.php";
		$newfile = $CONFIGURATION_FILE;

		if (!copy($file, $newfile)) {
 			echo "Error on copy $file to $newfile<br>";
		}
		else{
			echo "$CONFIGURATION_FILE created.<br> Please adapte configuration file.<br><br>";
		}
	}
	else{
		include ($CONFIGURATION_FILE);
	}
	

	
	if ($SQL_USER=="not_configured"){
		echo "Serveur name [$SERVER_NAME] inconnu <br>";
		echo "Please adapte configuration file [$CONFIGURATION_FILE].<br><br>";
		die();
	}

	$CONNECTION_DB=$SQL_USER;
	$CONNECTION_ID=false;
	
	//echo "SQL_HOST : $SQL_HOST<br>";
	
	//URL
	$URL_ERREUR="/erreur.php";
	$URL_ERREUR_DROITS="/erreur_droits.php";
	$URL_DEFAULT="/default.php";
	$URL_LOGIN="/login.php";
	
		
	//ACCESS
	$ACCESS_TRACE="yes"; 									//met en base les acces aux pages
	$SHOW_ACCESS_TRACE="false";  							//show trace de access
	//$ACCESS_FILTRE_TRACE= array( "/127.0.0.*/" ); 		//filtre les adresses � ne pas filtrer
	$ACCESS_TIMEOUT_COUNTER=1200; 							//time en seconde
	$ACCESS_EMAIL_COUNTER= "yes";							// doit on prevenir par email qu'une nouvelle IP c'est connect�e						 	
	//filtre les adresses � ne pas filtrer
	$ACCESS_FILTRE_EMAIL_COUNTER= array( 
			"/127.0.0.*/",
	 		"/bot/i" ); 	//filtre les adresses a ne pas filtrer
?>