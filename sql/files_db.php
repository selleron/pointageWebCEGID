<?PHP

$FILES_DB_PHP="loaded";

$SQL_TABLE_FILE="files";
$SQL_TABLE_FILE_HISTORY="files_history";

$SQL_COL_ID_FILE="id";
$SQL_COL_TITLE_FILE="title";
$SQL_COL_NAME_FILE="name";
$SQL_COL_DATA_FILE="data";
$SQL_COL_MIME_FILE="mime";
$SQL_COL_LINK_FILE="LINK";
$SQL_COL_VERSION_FILE="version";
$SQL_COL_SIZEBLOB_FILE="sizeBlob";

$SQL_SHOW_COL_FILE="$SQL_COL_ID_FILE, $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_MIME_FILE, $SQL_COL_LINK_FILE, $SQL_COL_VERSION_FILE, $SQL_COL_SIZEBLOB_FILE";
$SQL_ALL_COL_FILE="$SQL_COL_ID_FILE, $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_DATA_FILE, $SQL_COL_MIME_FILE, $SQL_COL_LINK_FILE, $SQL_COL_VERSION_FILE, $SQL_COL_SIZEBLOB_FILE";
global $SQL_ALL_COL_FILE;

$SQL_ERROR_SIZE_BLOB="MySQL server has gone away";

include_once 'connection_db.php';
include_once 'tool_db.php';
include_once 'files.php';
include_once 'counter_db.php';


global $DIR_DEPOT_FROM;
global $DIR_DEPOT;

/**
 * actionStockFiles
 * stockes les fichiers sous la table files
 * @param $path ou se trouveles fichiers de depart
 * l'arrive est dans ../stockage
 */
function actionStockFiles($path="", $newpath=""){
	global $DIR_DEPOT_FROM;
	global $DIR_DEPOT;
	global $TRACE_FILE;
	
	if ($path==""){
		$path=$DIR_DEPOT_FROM;
	}
	if ($newpath==""){
		$newpath=$DIR_DEPOT;
	}
	
	showActionVariable("real path : ".realpath($path), $TRACE_FILE);
	$param = createParamShowDirectory($path);
	$param = prepareShowDirectoryWithParam($param);
	$contenu=$param["contenu"];

	if (!file_exists($newpath)){
		showError("directory $newpath n'existe pas. Tentative de création ....");
		mkdir($newpath);
	}
	if (!file_exists($newpath)){
		showError("directory $newpath ne peut pas etre créé");
		return false;
	}
	
	foreach ($contenu as $aFile) {
		showActionVariable("- move $aFile", $TRACE_FILE);
		actionStockOneFile($aFile, $path, $newpath);
		showActionVariable("- move ok", $TRACE_FILE);
	}
}
/**
 * actionStockTemporaryFile
 * stock un fichier temporaire dans le depot
 * @param unknown_type $oldpath
 * @param unknown_type $newpath
 */
function actionStockTemporaryFile( $oldpath="../depot/", $newpath="../stockage/"){
	if(actionLoadFile($oldpath)){
		//echo "load file ok ...<br>";
		$aFileName = getLoadFileName();
		actionStockOneFile($aFileName,$oldpath,$newpath);
	}	
}


/**
 * actionStockFiles
 * stockes les fichiers sous la table files
 * @param $path
 */
function actionStockOneFile( $aFileName, $oldpath="../depot/", $newpath="../stockage/"){
	global $TRACE_FILE;
	showActionVariable("actionStockOneFile() ->  $aFileName - $oldpath - $newpath ", $TRACE_FILE);
	
	$oldname="$oldpath$aFileName";

	//showSQLAction(" getCounterValue() : $aFileName => $newname");
	$counter = getCounterValue("stockage");
	//showSQLAction(" getCounterValue() : $aFileName => $counter");
	$extension = getFileExtension($oldname);
	$newFileName="file_$counter.$extension";
	$newname="$newpath$newFileName";

	//showSQLAction("counter   : $counter");
	//showSQLAction("extension : $extension ");
	//showSQLAction("oldname   : $oldname");
	//showSQLAction("newname   : $newname");

	//showSQLAction("rename ... : $aFileName => $newname");
	if(rename($oldname, $newname)){
		actionWithHistoryLinkOneFile( $aFileName, $newname);
	}
	else{
		showError("rename impossible : $aFileName => $newname");
	}
}

function actionWithHistoryLinkOneFile($aFile, $link, $table="", $sizeblobmax=""){
	global $SQL_TABLE_FILE;
	global $SQL_TABLE_FILE_HISTORY;
	global $SQL_COL_ID_FILE;
	global $SQL_ALL_COL_FILE;

	$id = actionLinkOneFile( $aFile, $link, $SQL_TABLE_FILE);
	
	//historisation
	//$condition = "$SQL_COL_ID_FILE='$id'";
	//historisationTable($SQL_TABLE_FILE, $SQL_TABLE_FILE_HISTORY, $SQL_ALL_COL_FILE, $condition);
}


function actionLinkOneFile($aFile, $link, $table="", $sizeblobmax=""){
	$txt="file on depot : [$aFile] -- link [$link]  <br>";
	showSQLAction($txt);

	global $SQL_TABLE_FILE;
	global $SQL_COL_ID_FILE;
	global $SQL_COL_TITLE_FILE;
	global $SQL_COL_NAME_FILE;
	global $SQL_COL_DATA_FILE;
	global $SQL_COL_MIME_FILE;
	global $SQL_COL_SIZEBLOB_FILE;
	global $SQL_COL_LINK_FILE;
	global $SQL_COL_VERSION_FILE;
	global $SQL_COL_SIZEMAX_BLOB;
	global $SQL_ERROR_SIZE_BLOB;

	if ($table==""){
		$table=$SQL_TABLE_FILE;
	}
	if ($sizeblobmax==""){
		$sizeblobmax = $SQL_COL_SIZEMAX_BLOB;
	}

	$title = $aFile;
	$name = $title;
	$fichier = $link;
	//$mime = 'image/gif';
	$mime= getMimeForFile($fichier);
	echo "<p>fichier to load [$fichier] <br>";
	echo "loading: wait... <br>";
	$size=filesize($fichier);
	if ($size > $sizeblobmax){
		echo "detected : file too long for blob.<br>";
		$req= "INSERT INTO ".$table.
			" ( $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_LINK_FILE,  $SQL_COL_MIME_FILE, $SQL_COL_SIZEBLOB_FILE )".
			" VALUES ('$title','$name', '$link', '$mime', $size)";
		showSQLAction($req);
		$res = mysqlQuery($req);
		showSQLError("");
		echo "fin de mise en base de donnees.<br>";
	}
	else{
		if ($r=@fopen($fichier, "r")){
			echo "fichier charge. taille : $size .<br>";
			echo "mise en base de donnees : wait ...<br>";
			$data = addslashes(fread($r, $size));
			$req= "INSERT INTO ".$table.
			" ( $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_LINK_FILE, $SQL_COL_DATA_FILE, $SQL_COL_MIME_FILE, $SQL_COL_SIZEBLOB_FILE )".
			" VALUES ('$title','$name', '$link', '$data','$mime', $size)";
			showSQLAction($req);
			$res = mysqlQuery($req);
			if (mySqlError()==$SQL_ERROR_SIZE_BLOB){
				echo "error : file too long for blob : $size.";
				actionStockOneFile( $aFile, $link, $table, 0);
			}
			else{
				showSQLError("");
				echo "fin de mise en base de donnees.<br>";
			}
		}
		else{
			showSQLError("Erreur d'acc&egrave;s au fichier ".$fichier."\n");
		}
	}
	echo "</p>";
	
	$id= mysqlInsertId();
	return $id;
}


?>
