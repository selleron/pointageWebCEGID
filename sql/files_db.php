<?PHP

$FILES_DB_PHP="loaded";

$SQL_TABLE_FILE="files";
$SQL_TABLE_FILE_HISTORY="files_history";

$SQL_COL_ID_FILE="ID";
$SQL_COL_TITLE_FILE="title";
$SQL_COL_NAME_FILE="name";
$SQL_COL_DATA_FILE="data";
$SQL_COL_MIME_FILE="mime";
$SQL_COL_LINK_FILE="link";
$SQL_COL_VERSION_FILE="version";
$SQL_COL_SIZEBLOB_FILE="sizeBlob";
$SQL_COL_DATE_FILE="date";

$SQL_SHOW_COL_FILE ="$SQL_COL_ID_FILE, $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_MIME_FILE,                     $SQL_COL_LINK_FILE, $SQL_COL_VERSION_FILE, $SQL_COL_SIZEBLOB_FILE, $SQL_COL_DATE_FILE";
$SQL_ALL_COL_FILE  ="$SQL_COL_ID_FILE, $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_DATA_FILE, $SQL_COL_MIME_FILE, $SQL_COL_LINK_FILE, $SQL_COL_VERSION_FILE, $SQL_COL_SIZEBLOB_FILE, $SQL_COL_DATE_FILE";
global $SQL_ALL_COL_FILE;

$SQL_ERROR_SIZE_BLOB="MySQL server has gone away";

include_once 'connection_db.php';
include_once 'tool_db.php';
include_once 'files.php';
include_once 'counter_db.php';


global $DIR_DEPOT_FROM;
global $DIR_DEPOT;


/**
 * applyGestionDepot
 */
function applyGestionDepot(){
    global $DIR_DEPOT_FROM;
    global $DIR_DEPOT;
    global $TRACE_FILE;
    global $SQL_TABLE_FILE;
    global $SQL_SHOW_COL_FILE;
    
        
    $action = getActionGet();
    showActionVariable( "traitement de l'action : $action <br>",$TRACE_FILE);
    

    $form_name=$SQL_TABLE_FILE."_edit";
    $res =  applyGestionTable($SQL_TABLE_FILE, $SQL_SHOW_COL_FILE, $form_name, $SQL_SHOW_COL_FILE);
    
    if ($res==0){
        if ($action == "depot"){
            showActionVariable(  "actionStockFiles() ",$TRACE_FILE);
            actionStockFiles();
        }
        if ($action == "showDepot"){
            showActionVariable(  "show depot [$DIR_DEPOT_FROM]... ",$TRACE_FILE);
            showDirectory($DIR_DEPOT_FROM);
        }
        
        if ($action == "load"){
            showActionVariable(  "action load: actionStockTemporaryFile() ...",$TRACE_FILE);
            actionStockTemporaryFile();
        }
        
        if ($action == "showStockage"){
            showActionVariable(  "show stockage [$DIR_DEPOT]... ",$TRACE_FILE);
            showDirectory($DIR_DEPOT);
        }
        if ($action == "showTableStockage"){
            showActionVariable(  "show table stockage ... ",$TRACE_FILE);
            //global $SQL_TABLE_FILE;
            //global $SQL_SHOW_COL_FILE;
            $condition="";
            $form_name=$SQL_TABLE_FILE."_insert";
            
            //$param = createDefaultParamSql("files", $SQL_SHOW_COL_FILE);
            $param = prepareshowTable($SQL_TABLE_FILE, $SQL_SHOW_COL_FILE, $form_name, $condition);
            //par defaut on a edit & delete
            // 	$param[PARAM_TABLE_ACTION::TABLE_EDIT] = "no";
            // 	$param[PARAM_TABLE_ACTION::TABLE_DELETE] = "no";
            // 	$param[PARAM_TABLE_ACTION::TABLE_EDIT_BY_ROW] = "yes";
            // 	$param[PARAM_TABLE_ACTION::TABLE_DELETE_BY_ROW] = "yes";
            
            //ajout export CSV
            $param[PARAM_TABLE_ACTION::TABLE_EXPORT_CSV] = "yes";
            $param[PARAM_TABLE_ACTION::TABLE_INSERT] = "no";
            
            //affichage par paquet
            $param = updateParamSpqlWithLimit($param);
            showLimitBar($param);
            
            
            
            
            $request=createRequeteTableData($param);
            showSQLAction($request);
            
            $param[PARAM_TABLE::TABLE_SIZE]=2000;
            showTableHeader($param);
            showTableData($param);
        }
     }
}

/**
 * actionStockFiles
 * stockes les fichiers sous la table files
 * @param String $path ou se trouve les fichiers de depart (FROM) souvent repertoire tmp
 * @param String $newpath ou on veut mettre les fichiers  (Depot
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
		showActionVariable("- move finished", $TRACE_FILE);
	}
}
/**
 * actionStockTemporaryFile
 * stock un fichier temporaire dans le depot
 * @param string $oldpath  directory
 * @param string $newpath  directory
 */
function actionStockTemporaryFile( $oldpath="", $newpath=""){
    global $DIR_DEPOT_FROM;
    global $DIR_DEPOT;
    global $TRACE_FILE;
    
    if ($oldpath==""){
        $oldpath=$DIR_DEPOT_FROM;
    }
    if ($newpath==""){
        $newpath=$DIR_DEPOT;
    }
    
    
    showActionVariable("actionStockTemporaryFile()", $TRACE_FILE);
    if(actionLoadFile($oldpath)){	    
	    //echo "load file ok ...<br>";
		$aFileName = getLoadFileName();
		return actionStockOneFile($aFileName,$oldpath,$newpath);
	}	
	return -1;
}

/**
 * pathToURL
 * @param String $path
 * @return string URL
 */
function pathToURL($path){
    global $PATH_ROOT_DIRECTORY;
    global $URL_ROOT_POINTAGE;
    $url = str_replace($PATH_ROOT_DIRECTORY, $URL_ROOT_POINTAGE, $path);
    
    return $url;
}

/**
 * getUrlTelechargement
 * @param String path $link
 * @param String $titre
 * @return String HTML
 */
function getUrlTelechargement($link, $titre){
    global $URL_IMAGES;
    $img = "<img src=\"$URL_IMAGES/menu_telechargement.png\"";
    $url = pathToURL($link);
    $result = getHtmlUrl($url,$titre, $img);

    return $result;
}

/**
 * actionStockFiles
 * stockes les fichiers sous la table files
 * @param $path
 */
function actionStockOneFile( $aFileName, $oldpath="../depot/", $newpath="../stockage/"){
    global $SHOW_FILE_ACTION;
    showActionVariable("actionStockOneFile() ->  $aFileName - $oldpath - $newpath ", $SHOW_FILE_ACTION);
	
	$oldname="$oldpath$aFileName";
	$oldFileName = getBaseName($oldname);
	$counter = getCounterValue("stockage");
	$extension = getFileExtension($oldname);

	//$newFileName="file_$counter.$extension";
	$newFileName=$counter."_".$oldFileName;
	
	
	$newname="$newpath$newFileName";

	showActionVariable("counter   : $counter"    , $SHOW_FILE_ACTION);
	showActionVariable("oldname   : $oldname "   , $SHOW_FILE_ACTION);
	showActionVariable("extension : $extension " , $SHOW_FILE_ACTION);
	showActionVariable("newname   : $newname"    , $SHOW_FILE_ACTION);

	//showSQLAction("rename ... : $aFileName => $newname");
	if(rename($oldname, $newname)){
		return actionWithHistoryLinkOneFile( $aFileName, $newname);
	}
	else{
		showError("rename impossible : $aFileName => $newname");
	}
	return -1;
}

function actionWithHistoryLinkOneFile($aFile, $link, $table="", $sizeblobmax=""){
	global $SQL_TABLE_FILE;
	global $SQL_TABLE_FILE_HISTORY;
	global $SQL_COL_ID_FILE;
	global $SQL_ALL_COL_FILE;

	$id = actionLinkOneFile( $aFile, $link, $SQL_TABLE_FILE);
	return $id;
	//historisation
	//$condition = "$SQL_COL_ID_FILE='$id'";
	//historisationTable($SQL_TABLE_FILE, $SQL_TABLE_FILE_HISTORY, $SQL_ALL_COL_FILE, $condition);
}

/**
 * actionLinkOneFile
 * @param string  $aFile file name (no path)
 * @param string $link  (link for upload : path)
 * @param string $table  (table for link ) peut etre vide
 * @param number $sizeblobmax : taille max blob (en octets) : peut etre vide
 * @return number : id in table
 */
function actionLinkOneFile($aFile, $link, $table="", $sizeblobmax=""){
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
	
	$txt="file on depot : [$aFile] -- link [$link] -- blob size [$sizeblobmax / $SQL_COL_SIZEMAX_BLOB (octets) ] <br>";
	showSQLAction($txt);
	

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
		echo "detected : file too long for blob ($size / $sizeblobmax) .<br>";
		$req= "INSERT INTO ".$table.
			" ( $SQL_COL_TITLE_FILE, $SQL_COL_NAME_FILE, $SQL_COL_LINK_FILE,  $SQL_COL_MIME_FILE, $SQL_COL_SIZEBLOB_FILE )".
			" VALUES ('$title','$name', '$link', '$mime', $size)";
		//showSQLAction($req);
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
			//showSQLAction($req);
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
