<?php
/**
 * version 1.1 18/03/2012
 * version 1.2 29/07/2012
 */
// echo "include files.php<br>";
$FILES_PHP = "loaded";


function numberToByte($num){
    $num = str_replace("K", "000", $num);
    $num = str_replace("M", "000000", $num);
    $num = str_replace("G", "000000000", $num);
    $num = str_replace(" ", "", $num);
    return $num;
}

/**
 * showLoadFile
 * affiche le champ de selection d'un fichier a uploader
 * 
 * @param $url url
 *        	lorsque l'on appuie sur load
 *        	voir http://phpcodeur.net/articles/php/upload
 */
function showLoadFile($url = "", $choose = "", $load = "", $action = "", $infoForm="", $MAX_FILE_SIZE="") {
	if (! $action) {
		$action = "load";
	}
	if (! $choose) {
		$choose = "importer le fichier";
	}
	if (! $load) {
		$load = "Importer le fichier";
	}
	if (! $url) {
		$url = currentPageURL ();
		// echo $html."<br>";
	}
	global $ACTION_GET;
	$url = suppressPageURL ( $url, $ACTION_GET );
	
	if ($MAX_FILE_SIZE==""){
	    $MAX_FILE_SIZE = numberToByte(ini_get('upload_max_filesize'));
	}
	
	echo "
	<!-- Le type d\'encodage des donnees, enctype, DOIT etre specifie comme ce qui suit: -->

	<form enctype=\"multipart/form-data\" action=\"$url\" method=\"post\">
	<!-- MAX_FILE_SIZE doit preceder le champ input de type file -->
	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$MAX_FILE_SIZE\" />
	<!-- Le nom de l\'element input determine le nom dans le tableau -->
	$choose : <input name=\"userfile\" type=\"file\" />";
	showFormAction ( $action );
	showFormIDElement ();
	echo "$infoForm";
	echo "
	<input type=\"submit\" value=\"$load\" />
	</form>
	";
}

/**
 * actionGestionFile
 * load, delete
 * 
 * @see actionGestionFile()
 * @param
 *        	$destination
 */
function actionGestionImage($destination = "../photos/") {
	actionGestionFile ( $destination );
}

/**
 * actionGestionFile
 * - load : upload file ( repertoire de destination )
 * - delete : delete file getDocumentName
 * 
 * @param
 *        	$destination
 */
function actionGestionFile($destination = "../photos/") {
	global $ACTION_GET;
	$action = getURLVariable ( $ACTION_GET );
	if ($action == "load") {
		actionLoadFile ( $destination );
	} else if ($action == "delete") {
		$file = getDocumentName ();
		deleteFile ( $file );
	}
}

/**
 * actionLoadFile
 * upload file ( repertoire de destination )
 * 
 * @param
 *        	$destination
 * @return TRUE or FALSE
 */
function actionLoadFile($destination = "../photos/") {
	$order = getURLVariable ( ORDER_ENUM::ORDER_GET );
	global $SHOW_FILE_ACTION;
	showActionVariable ( "upload temporary file[ ".getTemporaryFile()." ]", $SHOW_FILE_ACTION );
	if (hasTemporaryFile () == "yes") {
	    showActionVariable ( "temporary file found.<br>", $SHOW_FILE_ACTION );
		// $destination = getcwd()."/";
		// $destination = "../photos/";
		return uploadFile ( $destination );
	} else {
	    showActionVariable ( " no file found at $destination.<br>", $SHOW_FILE_ACTION );
	    showActionVariable ( getTemporaryFileError (), $SHOW_FILE_ACTION );
		return FALSE;
	}
}

/**
 * actionImportCSV
 * 
 * @return multitype: array|boolean
 */
function actionImportCSV($firstline = 0) {
	// 
	// $order = getURLVariable(ORDER_ENUM::ORDER_GET);
	global $SHOW_SQL_ACTION;
	// showActionVariable("test import csv ......", $SHOW_SQL_ACTION);
	if (hasTemporaryFile () == "yes") {
		// showActionVariable("ok.<br>", $SHOW_SQL_ACTION);
		return importCSV ( "yes", $firstline );
	} else {
		showActionVariable ( " no file.<br>", $SHOW_SQL_ACTION );
		showActionVariable ( getTemporaryFileError (), $SHOW_SQL_ACTION );
		return FALSE;
	}
}

/**
 * Retourne le repertoire courant
 */
function repertoireCourant() {
	return getcwd ();
}

/**
 *
 * Creation d'un repertire si besoin
 * 
 * @param
 *        	$path
 */
function createDirectoryIfNeeded($path) {
	if (is_dir ( $path )) {
		return true;
	} else {
		if (mkdir ( $path )) {
			return true;
		} else {
			return false;
		}
		;
	}
	;
}

/**
 * suppression d'un fichier
 * 
 * @param
 *        	$file
 */
function deleteFile($file) {
	global $SHOW_SQL_ACTION;
	showActionVariable ( "suppression fichier : $file", $SHOW_SQL_ACTION );
	if (! unlink ( $file )) {
		getActionMessage ( "suppression impossible : $file" );
	}
}

/**
 * getTemporaryFile
 * retourne le nom du fichier temporaire (path compris)
 */
function getTemporaryFile() {
	$tmpFile = $_FILES ['userfile'] ['tmp_name'];
	return $tmpFile;
}

/**
 * getTemporaryFileError
 * retourne l'erreur de load du fichier temporaire
 */
function getTemporaryFileError() {
	$error = $_FILES ['userfile'] ['error'];
	if ($error == 0)
		return "no error";
	if ($error == 1)
		return "Le fichier exc&egrave;de le poids autorisé par la directive upload_max_filesize";
	if ($error == 2)
		return "Le fichier exc&egrave;de le poids autorisé par le champ MAX_FILE_SIZE du formulaire s'il a été donné";
	if ($error == 3)
		return "Le fichier n'a été uploadé que partiellement";
	if ($error == 4)
		return "Aucun fichier n'a été uploadé";
	return "erreur inconnue $error";
}

/**
 * hasTemporaryFile
 */
function hasTemporaryFile() {
	$tmpFile = getTemporaryFile ();
	if ($tmpFile == "")
		return "no";
	return "yes";
}

/**
 * uploadFile
 * copy file from $FILE to $uploaddir
 * 
 * @param $uploaddir repertoire
 *        	d'arrivee
 * @return TRUE or FALSE
 */
function uploadFile($uploaddir) {
	createDirectoryIfNeeded ( $uploaddir );
	if ($uploaddir==""){
	    showError("no upload dir defined");
	}
	$tmpFile = getTemporaryFile ();
	$uploadfile = $uploaddir . basename ( $_FILES ['userfile'] ['name'] );
	
	global $SHOW_FILE_ACTION;
	showActionVariable ( "upload temporary file : $tmpFile -> $uploadfile ", $SHOW_FILE_ACTION );
	
	return moveFile ( $tmpFile, $uploadfile );
}

/**
 * put in csv file
 * 
 * @param
 *        	filer handler $handle
 * @param array[] $aRow
 *        	string array[]
 * @param string $delimiter
 *        	default ;
 */
function myfputcsv($handle, $aRow, $delimiter = "") {
	global $EXPORT_DELIMITER;
	global $EXPORT_DECIMAL;
	
	// showAction("delimiter : $EXPORT_DELIMITER");
	// showAction("decimal : $EXPORT_DECIMAL");
	// showAction("go export line : ".arrayToString($aRow) );
	
	// positionnement du deliier CSV
	if ($delimiter == "") {
		$delimiter = $EXPORT_DELIMITER;
	}
	
	// positionnement decimal
	$nbElt = count ( $aRow );
	for($i = 0; $i < $nbElt; $i ++) {
		if (is_numeric ( $aRow [$i] )) {
			// showAction("numeric detected : $aRow[$i]");
			$aRow [$i] = str_replace ( ".", $EXPORT_DECIMAL, $aRow [$i] );
		}
	}
	
	// todo pour les dates
	
	return fputcsv ( $handle, $aRow, $delimiter );
}

/**
 * myfgetcsv
 * 
 * @param
 *        	file handler $handle
 * @param unknown $length
 *        	(can be null)
 */
function myfgetcsv($handle, $length = null) {
	$delimiter = ";";
	return fgetcsv ( $handle, $length, $delimiter );
}

/**
 * importCSV
 * 
 * @param string $rowFirst
 *        	"yes"
 * @return array [row][col] or [col][row]
 */
function importCSV($rowFirst = "yes", $firstline = 0) {
	$tmpFile = getTemporaryFile ();
	// $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	
	$handle = fopen ( $tmpFile, "r" );
	$cpt = 0;
	$row = 0;
	while ( ($data = myfgetcsv ( $handle, 1000 )) !== FALSE ) {
		if ($cpt >= $firstline) {
			$nb = count ( $data );
			if ($rowFirst == "yes") {
				$array [$row] = $data;
				// for($x=0;$x<$nb;$x++){
				// $array[$cpt][$x] = $data[$x];
				// }
			} else {
				for($x = 0; $x < $nb; $x ++) {
					$array [$x] [$row] = $data [$x];
				}
			}
			// printArray($data);
			$row ++;
		}
		$cpt ++;
	}
	
	// echo "<br>CSV file<br>";
	// print_r($array);
	// echo "<br>";
	
	return $array;
	// return moveFile($tmpFile, $uploadfile);
}

/**
 * getLoadFileName
 * Retourne le nom du fichier load� (sans le path)
 */
function getLoadFileName() {
    //$uploadfile = $uploaddir . basename ( $_FILES ['userfile'] ['name'] );
    $uploadfile = basename ( $_FILES ['userfile'] ['name'] );
    return $uploadfile;
}

/**
 * moveFile
 *
 * @param unknown_type $tmpFile        	
 * @param unknown_type $uploadfile        	
 * @return TRUE or FALSE
 */
function moveFile($tmpFile, $uploadfile) {
    global $SHOW_SQL_ACTION;
    
	showSQLAction ( "file loaded : $tmpFile   <br>" );
	showSQLAction ( "move to     : $uploadfile   <br>" );
	showSQLAction ( "current dir : " . getcwd () . " <br>" );
	
	if (move_uploaded_file ( $tmpFile, $uploadfile )) {
        showActionVariable (  "Le fichier est valide, et a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute;
				avec succ&egrave;s.<br>", $SHOW_SQL_ACTION );
		return TRUE;
	} else {
	    showActionVariable (  "Attaque potentielle par t&eacute;l&eacute;chargement de fichiers.<b>", $SHOW_SQL_ACTION );
		return FALSE;
	}
	
	// echo 'Voici quelques informations de d&eacute;bogage :';
	// print_r($_FILES);
}

/**
 *
 * Enter description here ...
 * 
 * @param $dossier dossier
 *        	a afficher
 * @param $showSubDir "yes"
 *        	pour voir les repertoires
 * @param $showActionDelete "yes"
 *        	pour pouvoir supprimer un fichier
 */
function showDirectory($dossier = "./", $showSubDir = "yes", $showActionDelete = "no") {
	$param = createParamShowDirectory ( $dossier, $showSubDir, $showActionDelete );
	showDirectoryWithParam ( $param );
}

/**
 * createParamShowDirectory
 * 
 * @param unknown_type $dossier        	
 * @param unknown_type $showSubDir        	
 * @param unknown_type $showActionDelete        	
 */
function createParamShowDirectory($dossier = "./", $showSubDir = "yes", $showActionDelete = "no") {
	$param ["dossier"] = "$dossier";
	$param ["contenu"] = "$dossier";
	$param ["showSubDir"] = "$showSubDir";
	$param ["col_dir"] = 3;
	$param ["col_file"] = 3;
	$param ["actionFile"] = $showActionDelete;
	return $param;
}

/**
 * showDirectoryWithParam
 * visualisation d'un repertoire
 * 
 * @param $param -
 *        	dossier : le dossier dossier
 *        	- showSubDir : yes/no
 *        	- contenu :array of file
 */
function showDirectoryWithParam($param) {
	$param = prepareShowDirectoryWithParam ( $param );
	$dossier = $param ["dossier"];
	$showSubDir = $param ["showSubDir"];
	$contenu = $param ["contenu"];
	
	// Affichage + traitement
	echo '<h2>Contenu de ' . $dossier . '</h2>';
	
	// les repertoires
	if ($showSubDir == "yes") {
		showDirectoryList ( $param );
	}
	echo '<p></p><p></p>';
	
	// les fichiers
	$count = 0;
	$cpt=0;
	$col_file = $param ["col_file"];
	echo '<table>';
	foreach ( $contenu as $aFile ) {
		echo $aFile;
		if (($cpt + 1) < $col_file) {
			$cpt = $cpt + 1;
		} else {
			$cpt = 0;
			echo "</tr><tr>";
		}
		showFile ( $param, $aFile );
	}
	echo '</table>';
}
function prepareShowDirectoryWithParam($param) {
	$dossier = $param ["dossier"];
	$showSubDir = $param ["showSubDir"];
	
	// Configuration
	// $dossier = 'UserFiles/';
	$ouverture = opendir ( $dossier );
	
	// Stockage des variables
	$contenu = array ();
	$dirs = array ();
	$files = array ();
	
	while ( $fichiers = readdir ( $ouverture ) ) {
		$files [] = $dossier . $fichiers;
		if (is_file ( $dossier . $fichiers )) {
			$contenu [] = $fichiers;
		}
		if (is_dir ( $dossier . $fichiers )) {
			$dirs [] = $fichiers;
		}
	}
	closedir ( $ouverture );
	$param ["dirs"] = $dirs;
	$param ["contenu"] = $contenu;
	$param ["files"] = $files;
	
	$param ["link"] = insertScriptToolTipImage ( $files );
	return $param;
}
function showVignette($param, $code) {
	$dossier = $param ["dossier"];
	$link = $param ["link"];
	$alt = $param ["alt"];
	
	$aFile = "image_" . $code . ".jpg";
	$aVignette = "vignette_" . $code . ".jpg";
	// On r�cup�re les icons � afficher
	$type = getImageForType ( $aFile );
	$aImage = $dossier . $aFile;
	$aImageVignette = $dossier . $aVignette;
	$key = $aImage;
	
	echo " <td>";
	echo " <a href=\"$aImage\"";
	echo " class=\"showTip " . $link [$key] . "\"";
	echo " target=\"_self\">";
	echo " <img  alt=\"$alt\" ";
	echo " src=\"$aImageVignette\" ";
	echo " border=\"0\"></a>";
	echo " </td>";
}

/**
 * showFile
 * 
 * @param
 *        	$param
 * @param $aFile :
 *        	filesans le path complet
 *        	- dossier
 *        	- link
 */
function showFile($param, $aFile) {
	$dossier = $param ["dossier"];
	$link = $param ["link"];
	
	// On recupere les icons a afficher
	$type = getImageForType ( $aFile );
	$aImage = $dossier . $aFile;
	$key = $aImage;
	echo '
			<td> <img src="' . $type . '"></td>
					<td><a href="' . $aImage . '"  class="showTip ' . $link [$key] . '" >' . $aFile . '</a></td>';
	showActionFile ( $param, $aImage );
	
	echo '	<td width=30>';
}

/**
 * showActionFile
 * 
 * @param
 *        	$param
 * @param $file -
 *        	actionFile=yes/non
 */
function showActionFile($param, $file) {
	$actionFile = $param ["actionFile"];
	if ($actionFile == "yes") {
		
		echo "<td>
		<form enctype=\"multipart/form-data\" action=\"$url\" method=\"post\">";
		showFormIDElement ();
		showFormDocumentElementValue ( $file );
		showFormAction ( "delete" );
		echo ' <input type="submit" value="x" />
				</form></td>';
	}
}

/**
 * getFileExtension
 * retourne l'extension d'un fichier
 * @param string $aFile
 * @return string
 */
function getFileExtension($aFile) {
	// $fichier = explode('.', $aFile);
	// $extension = $fichier[1];
	$extension = pathinfo ( $aFile, PATHINFO_EXTENSION );
	return $extension;
}


/**
 * getBaseName
 * retourne le nom du fichier sans extension, ni path
 * @param string $aFile
 * @return string
 */
function getBaseName($aFile){
    $baseName = pathinfo ( $aFile, PATHINFO_BASENAME );
    return $baseName;
}


/**
 * retourne le nom du fichier (baseName&extension)
 * @param string $aFile
 * @return string
 */
function getFileName($aFile){
    $fileName = pathinfo ( $aFile, PATHINFO_FILENAME );
    return $fileName;
}

/**
 * getDirFile
 * retourne le path du fichier
 * @param string $aFile
 * @return string
 */
function getDirName($aFile){
    $dirName = pathinfo ( $aFile, PATHINFO_DIRNAME );
    return $dirName;
}

/**
 * getRealPath
 * get Real Path (resolve .. & symbolic link)
 * @param string $aPath
 * @return string
 */
function getRealPath($aPath){
    return realpath($aPath);
}

/**
 * getIFileExtension
 * retourne l'extension d'un fichier (toujours en minuscule)
 * 
 * @param path $aFile        	
 */
function getIFileExtension($aFile) {
	$extension = getFileExtension ( $aFile );
	return strtolower ( $extension );
}

/**
 * getMimeForFile
 * retourne leMime pour un fichier
 * 
 * @param
 *        	le fichier avec son extension
 */
function getMimeForFile($aFile) {
	$extension = getFileExtension ( $aFile );
	return "mime/$extension";
}

/**
 * getImageForType
 * retourne l'image a afficher pour un fichier
 * Se base sur l'extension
 * 
 * @param path $aFile        	
 */
function getImageForType($aFile) {
	// On recupere les icons a afficher
	global $URL_IMAGES;
	$path = "$URL_IMAGES//extension//";
	$extension = getIFileExtension ( $aFile );
	
	// traite le cas de png car autoremplacement dans str_ireplace
	if (strcasecmp ( $extension, 'png' ) == 0)
		return $path . 'image16.png';
		
		// On attribut les type de fichiers et d'icones en fonction de leur extension (images personalisables)
	$in = array (
			'zip',
			'pdf',
			'7z',
			'rar',
			'jpg',
			'gif',
			'bmp',
			'exe',
			'dll',
			'avi',
			'mov',
			'mkv',
			'mp4',
			'mp3',
			'php',
			'iso',
			'asc',
			'kdbx',
			'ini',
			'ods',
			'odt',
			'xls',
			'doc'
	);
	
	$out = array (
			$path . 'zip.png',
			$path . 'pdf.png',
			$path . '7z.png',
			$path . 'rar.png',
			$path . 'image16.png',
			$path . 'image16.png',
			$path . 'image16.png',
			$path . 'exe.png',
			$path . 'dll.png',
			$path . 'video.png',
			$path . 'video.png',
			$path . 'video.png',
			$path . 'video.png',
			$path . 'audio.png',
			$path . 'php.png',
			$path . 'iso.png',
			$path . 'asc.png',
			$path . 'kdbx.png',
			$path . 'ini.png',
			$path . 'ods.png',
			$path . 'odt.png',
			$path . 'xls.png',
			$path . 'doc.png'
			
	);
	// On les remplaces
	if (sizeof ( $in ) != sizeof ( $out ))
		echo "array replace size in != size out";
	$type = str_replace ( $in, $out, $extension );
	if ($type == $extension)
		return $path . 'unknown.png';
	return $type;
}

/**
 * showDirectoryList
 * affiche la liste des repertoires
 * 
 * @param array $param
 *        	(dossier, col_dir, dirs)
 */
function showDirectoryList($param) {
	global $URL_IMAGES;
	$count = 0;
	$dossier = $param ["dossier"];
	$col_dir = $param ["col_dir"];
	$dirs = $param ["dirs"];
	
	echo '<table><tr>';
	foreach ( $dirs as $fichier ) {
		if ($count >= $col_dir) {
			$count = 0;
			echo '</tr><tr>';
		}
		
		echo '	<td><img src="'.$URL_IMAGES.'/extension/directory16.png"> </td>
				<td><a href="' . $dossier . $fichier . '">' . $fichier . '</a></td>
						<td width="30"><td>
						';
		$count = $count + 1;
	}
	echo '</tr></table>';
}
function insertScriptToolTipImage($img) {
	echo '
			<script src="/js/dw_event.js" type="text/javascript"></script>
			<script src="/js/dw_viewport.js" type="text/javascript"></script>
			<script src="/js/dw_tooltip.js" type="text/javascript"></script>
			<script src="/js/dw_tooltip_aux.js" type="text/javascript"></script>
			<script type="text/javascript">
			';
	echo '
			dw_Tooltip.defaultProps = {
			wrapFn: dw_Tooltip.wrapImageToWidth
}
			';
	
	echo '	dw_Tooltip.content_vars = { ';
	$link = array ();
	$cpt = 0;
	foreach ( $img as $aImage ) {
		if ($cpt > 0) {
			echo ',';
		}
		$cpt = $cpt + 1;
		$link [$aImage] = "L" . $cpt;
		echo "
		$link[$aImage]: {
		img: '$aImage'
	}";
	}
	echo '}
			</script>
			';
	return $link;
}

// echo "include files.php end<br>";
?>
