<?php
include_once 'pointage_cegid_db.php';
include_once (dirname ( __FILE__ ) .'/../configuration/labelAction.php');
include_once 'historisation_db.php';

//table previsionnel
$SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL = "cegid_pointage_previsionnel";
$SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL2 = "cegid_pointage_previsionnel as p,  cegid_user as u, cegid_project as pj";

//table pointage import (c'est un bac à sable)
$SQL_TABLE_CEGID_POINTAGE_IMPORT= "cegid_pointage_import";
$SQL_TABLE_CEGID_POINTAGE_IMPORT2 = "cegid_pointage_import as p,  cegid_user as u, cegid_project as pj";


//
//les nomn de colonne sont dans pointage_cegid_db.php
//

/**
 * applyGestionPointagePrevisionnelCegid
 */
function applyGestionPrevisionnelProjetCegid() {
    
	global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	$table = $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	
	return applyGestionTablePointageProjetCegid ( $table );
}

/**
 * applyGestionPrevisionnelUserCegid
 * @return number|void|number
 */
function applyGestionPrevisionnelUserCegid() { 
    global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
    global $SQL_SHOW_COL_CEGID_POINTAGE_PREVISIONNEL_BYUSER;
    $table = $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
    $firstCol = $SQL_SHOW_COL_CEGID_POINTAGE_PREVISIONNEL_BYUSER;
    
    $exec = exportCSVTableGestionPointageProjetCegid($table, $firstCol);
    if ($exec > 0) {
        return 1;
    }
    else{
        return applyGestionTablePointageProjetCegid ( $table );
    }
}



/**
 * applySynchronizePrevisionnel
 * @return number  1 si action faite -1 si pas de synchro
 */
function applySynchronizePrevisionnel(){
	global $SHOW_SYNCHRO_PREVISION_TRACE;
	
	showActionVariable("Test action Synchronize", $SHOW_SYNCHRO_PREVISION_TRACE);
	if (getActionGet () == LabelAction::ACTION_SYNCHRONIZE) {
	showActionVariable("Synchronize action demanded ".FORM_VARIABLE::DATE_DEBUT_GET, $SHOW_SYNCHRO_PREVISION_TRACE);
		
		$dateDebut = getURLVariable(FORM_VARIABLE::DATE_DEBUT_GET);
		$dateFin = getURLVariable(FORM_VARIABLE::DATE_FIN_GET);
		$error="no";
		if ($dateDebut == ""){
			$error="yes";
			showError("synchronisation : date de debut not found");
		}
		if ($dateFin == ""){
			$error="yes";
			showError("synchronisation : date de fin not found");
		}
		if ($error == "yes"){
			return 1;
		}
		
		showActionVariable("démarrage synchronisation...", $SHOW_SYNCHRO_PREVISION_TRACE);

		global $SQL_TABLE_CEGID_POINTAGE;
		global $SQL_SHOW_COL_CEGID_POINTAGE;
		global $SQL_COL_DATE_CEGID_POINTAGE;
		global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
		
		$cols = $SQL_SHOW_COL_CEGID_POINTAGE;
		$condition = "$SQL_COL_DATE_CEGID_POINTAGE between '$dateDebut' and '$dateFin'";
		
		
		//historisation
		historisationTable($SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL, /**table historique*/"",$cols, ""/*$condition*/);
		
		
		//clean
		$delete = createSqlDeleteWithcondition($SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL, $condition);
		showSQLAction($delete);
		mysqlQuery($delete);
		showSQLError("");
		
		
		//reconstruction
		$param = createDefaultParamSql($SQL_TABLE_CEGID_POINTAGE,$cols,$condition);
		$param = updateParamSqlColumnFilter($param, $cols);
		
		$sql = createRequeteTableData($param);
		showSQLAction($sql);
		//showTableByParam($param);
		$tableau = sqlParamToArrayResult($param); //array[col][value]
		$count = mysqlNumrows($tableau);
		showActionVariable("synchronize $count entry...", $SHOW_SYNCHRO_PREVISION_TRACE);
		
		for($r=0;$r<$count;$r++){
			$row = sqlRowFromSqlArrayResult($tableau, $r);
			//printArray($row);
			$sqlReplace = createSqlReplace($SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL, $cols, $row);
			mysqlQuery($sqlReplace);
			showSQLError("");
			//showSQLAction($sqlReplace);
		}
				
		return 1;
	} else {
		return - 1;
	}
	
}

/**
 * showSynchronizePrevisionnel
 * @param string $url
 * @param string $formName
 */
function showSynchronizePrevisionnel($url="", $formName=""){
	if ($formName == "") {
		$formName = "form_showSynchronizePrevisionnel";
	}
	
	if (! $url) {
		$url = currentPageURL ();
	}
	
	//calcul des dates par defaut
	global $YEAR_SELECTION;
	$year = getURLVariable ( $YEAR_SELECTION );
	$dateDebut= sqlDateFromDDMMYY(01, 01, $year);
	$time = time();
	$date = timeToSqlDate($time);
	$month = monthFromSqlDate($date)-1;
	$year = yearFromSqlDate($date);
	$dateFin= sqlDateFromDDMMYY(28, $month, $year);
	
	//creation form
	createForm($url, $formName);
	beginTable();
	beginTableHeader();
	echoTD("Date Synchronisation");
	echoTD("");
	endTableHeader();
	beginTableRow();
	showFormDateElementForVariable($formName , FORM_VARIABLE::DATE_DEBUT_GET, /*$showLabel*/  "yes", /*$useTD*/  "yes", $dateDebut,
			/*$size*/ "", /*$statusEdit*/  "enabled", "date début") ;
	showFormDateElementForVariable($formName , FORM_VARIABLE::DATE_FIN_GET	, /*$showLabel*/  "yes", /*$useTD*/  "yes", $dateFin,
			/*$size*/ "", /*$statusEdit*/  "enabled", "date fin") ;
	
	
	beginTableCell();
	showFormIDElement();
	$infoForm = getInfoFormProjectSelection();
	global $SHOW_FORM_TRACE;
	if ($SHOW_FORM_TRACE == "yes") {
		showActionVariable ( ">>> 2. showSynchronizePrevisionnel() form name : $formName - infoForm : $infoForm <br>", $SHOW_FORM_TRACE );
	}
	
	// show others info form
	echo "$infoForm";
	
	showFormAction(LabelAction::ACTION_SYNCHRONIZE);
	showFormSubmit(LabelAction::ACTION_SYNCHRONIZE);
	endTableCell();
	
	endTableRow();
	endTable();
	
	
	
	endForm();
	
}


/**
 * showTableCoutOneProjectPrevisionel
 */
function showTableCoutOneProjectPrevisionel() {
    global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
    
    $tablePointage= $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
    return showTableCoutOneProject($tablePointage, "no"/*showOnlyOneProject*/);
}

/**
 * showTableCoutOneProjectImport
 */
function showTableCoutOneProjectImport() {
    global $SQL_TABLE_CEGID_POINTAGE_IMPORT;
    
    $tablePointage= $SQL_TABLE_CEGID_POINTAGE_IMPORT;
    return showTableCoutOneProject($tablePointage, "no"/*showOnlyOneProject*/);
}

/**
 * getTableauPrevisionnelCegid
 *
 * @param string $projectName        	
 * @param string $showAll        	
 */
function getTableauPrevisionnelCegid($projectName = "", $showAll = "yes") {
	global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	$table_pointage = $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	
	global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL2;
	$table_pointage2 = $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL2;
	
	return getTableauPointageProjetCegid2 ( $projectName, $showAll, $table_pointage, $table_pointage2 );
}

/**
 * isSamePointageRef
 * recherche l'engistrement meme projet, user profil dans $tableauPrev
 * $tableauPrev [$c] [$cpt] == $tableauPointage [$c] [$cptP] => $cpt
 * 
 * @param string $tableauPrev        	
 * @param string $tableauPointage        	
 * @param string $columns        	
 * @param integer $cpt index        	
 * @param integer $cptP  index      	
 * @return number index trouvé dans $tableauPrev
 */
function isSamePointageRef($tableauPrev, $tableauPointage, $columns, $cpt, $cptP) {
	foreach ( $columns as $c ) {
	    if (isset($tableauPrev [$c]) && isset($tableauPointage [$c]) && 
	    ($tableauPrev [$c] [$cpt] == $tableauPointage [$c] [$cptP])) {
			// on continue
		} else {
			return - 1;
		}
	}
	return $cpt;
}

/**
 * findIndexPointage
 * recherche l'engistrement meme projet, user profil dans $tableauPrev
 * $tableauPrev [$c] [$cpt] == $tableauPointage [$c] [$cptP] => $cpt
 *
 * @param string $tableauPrev        	
 * @param string $tableauPointage        	
 * @param string $columns        	
 * @param integer $cptP ndex       	
 * @return number index trouvé dans $tableauPrev, can be -1
 */
function findIndexPointage($tableauPrev, $tableauPointage, $columns, $cptP) {
	$nbResPrevision = mysqlNumrows ( $tableauPrev );
	
	for($cpt = 0; $cpt < $nbResPrevision; $cpt ++) {
		$index = isSamePointageRef ( $tableauPrev, $tableauPointage, $columns, $cpt, $cptP );
		if ($index >= 0) {
// 		    echoTD("found : ".$tableauPointage[$columns[0]][$cptP].
// 		        " - ".$tableauPointage[$columns[1]][$cptP].
// 		        " - ".$tableauPointage[$columns[2]][$cptP].
// 		        " - ".$tableauPointage[$columns[3]][$cptP].
// 		        " - ".$tableauPointage[$columns[4]][$cptP].
// 		        " in previsonnel at index $index  for $cpt / $cptP <br>");
		    return $index;
		}
	}
// 	echoTD("not found : ".$tableauPointage[$columns[0]][$cptP].
// 	    " - ".$tableauPointage[$columns[1]][$cptP].
// 	    " - ".$tableauPointage[$columns[2]][$cptP].
// 	    " - ".$tableauPointage[$columns[3]][$cptP].
// 	    " - ".$tableauPointage[$columns[4]][$cptP].
// 	    " in previsonnel <br>");
	return - 1;
}

/**
 * showTablePrevisionnelPointageCegid
 * Affichage du previsionnel
 * - modification par mois
 * - sommation automatique
 */
function showTablePrevisionnelPointageCegid() {
    global $TRACE_INFO_POINTAGE;
    showActionVariable( "function showTablePrevisionnelPointageCegid()", $TRACE_INFO_POINTAGE );
	
	// condition project
	global $ITEM_COMBOBOX_SELECTION;
	global $PROJECT_SELECTION;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
	    showActionVariable ( "No project Selected...", $TRACE_INFO_POINTAGE );
		// $projectName = "no project";
		$projectName = $ITEM_COMBOBOX_SELECTION;
	}
	
	// create tableau de pointage et previsionnel
	$tableauPointage = getTableauPointageProjetCegid ( $projectName, "no" );
	$tableauPrev = getTableauPrevisionnelCegid ( $projectName, "no" );
	
	$tableau = fusionTableauPointage($tableauPointage, $tableauPrev);
	//printMatrice($tableau[KEY_INFO::KEY_INFO]);
	showTablePointageOneProjetCegid ( $tableau );
}

/**
 * showTableImportPointageCegid
 * affiche la fusion de pointage et import CEGID
 */
function showTableImportPointageCegid() {
    global $TRACE_INFO_POINTAGE;
    showActionVariable( "function showTableImportPointageCegid()", $TRACE_INFO_POINTAGE );
    
    // condition project
    global $ITEM_COMBOBOX_SELECTION;
    global $PROJECT_SELECTION;
    $projectName = getURLVariable ( $PROJECT_SELECTION );
    if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
        showActionVariable ( "No project Selected...", $TRACE_INFO_POINTAGE );
        // $projectName = "no project";
        $projectName = $ITEM_COMBOBOX_SELECTION;
    }
    
    // create tableau de pointage et previsionnel
    $tableauPointage = getTableauPointageProjetCegid ( $projectName, "no" );

    //tableau import
    global $SQL_TABLE_CEGID_POINTAGE_IMPORT;
    global $SQL_TABLE_CEGID_POINTAGE_IMPORT2;   
    $tableauImport = getTableauPointageProjetCegid2($projectName, "no", $SQL_TABLE_CEGID_POINTAGE_IMPORT, $SQL_TABLE_CEGID_POINTAGE_IMPORT2);
    
    //fusion des deux tableaux
    $tableau = fusionTableauPointageImport($tableauPointage, $tableauImport);
    
    
    
    //affichage
    showTablePointageOneProjetCegid ( $tableau );
}



/**
 * fusionTableauPointage
 * 
 * @param array $tableauPointage
 * @param array $tableauPrev
 * @param string $colPointage (a,b,c,...)
 * @return array tableau de pointage fusionne
 */
function fusionTableauPointage($tableauPointage, $tableauPrev, $colPointage="")  {
    global $TRACE_INFO_POINTAGE;
    
    showActionVariable ( "function fusionTableauPointage()", $TRACE_INFO_POINTAGE );
       
    // fusion des deux tableaux
    $tableau = $tableauPrev;
    
    $nbResPointage = mysqlNumrows ( $tableauPointage );
    $nbResPrevision = mysqlNumrows ( $tableauPrev );
    
    global $LIST_COLS_MONTHS;
    $arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
    
    $columns = $colPointage;
    if($columns==""){
        global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
        $columns = stringToArray ( $SQL_SHOW_COL_CEGID_POINTAGE2_2 );
    }
    
    
    // copie pointage
    $tableau = $tableauPointage;
    
    for($cpt = 0; $cpt < $nbResPointage; $cpt ++) {
        // //copie premieres colonnes
        // foreach ( $columns as $c ) {
        // $tableau[$c][$cpt] = $tableauPointage[$c][$cpt];
            // }
            // U.O par date
            foreach ( $arrayMonth as $m ) {
                // $value = $tableauPointage[$m][$cpt];
                $value = $tableau [$m] [$cpt];
                $index = findIndexPointage ( $tableauPrev, $tableauPointage, $columns, $cpt );
                if (isset($tableauPrev [$m])&& isset($tableauPrev [$m] [$index])){
                    $value2 = $tableauPrev [$m] [$index];
                }
                else{
                    $value2="";
                }
                if ($value == "") {
                    $value = $value2;
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style='color: #0000FF;' " );
                    $tableau [$m] [$cpt] = $value;
                } else {
                    if (($value2!="") && ($value2!=$value)){
                        $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " readonly style=\"color: #FF0000; background: #E0E0E0; font-weight:bold\" " );
                    }
                    else{
                        $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " readonly style=\"background: #E0E0E0; font-weight:bold\" " );
                    }
                }
            }
    }

    
    $cpt2 = $nbResPointage;
    showActionVariable( "nb row previsionnel : $nbResPrevision", $TRACE_INFO_POINTAGE );
    //printMatrice($tableauPrev);
    // add previsionnel
    for($cpt = 0; $cpt < $nbResPrevision; $cpt ++) {
        $index = findIndexPointage ( $tableauPointage, $tableauPrev, $columns, $cpt );
        if ($index < 0) {
            // copie premieres colonnes
            foreach ( $columns as $c ) {
                $tableau [$c] [$cpt2] = $tableauPrev [$c] [$cpt];
            }
            // U.O par date
            foreach ( $arrayMonth as $m ) {
                $value = $tableauPrev [$m] [$cpt];
                $tableau = setSQLFlagStyle ( $tableau, $m, $cpt2, " style='color: #0000FF;' " );
                $tableau [$m] [$cpt2] = $value;
            }
            $cpt2 ++;
        }
    }
    
//    printMatrice($tableau);
//     //trace
//     printArray($columns);
//     $keys = arrayKeys ( $tableauPrev );
//     printArray($keys);
//     $keys = arrayKeys ( $tableauPointage );
//     printArray($keys);
//     $keys = arrayKeys ( $tableau );
//     printArray($keys);
//     //end trace
    
    
    return $tableau;
    //showTablePointageOneProjetCegid ( $tableau );
}


/**
 * fusionTableauPointage
 *
 * @param array $tableauPointage
 * @param array $tableauPrev
 * @param string $colPointage (a,b,c,...)
 * @return array tableau de pointage fusionne
 */
function fusionTableauPointageImport($tableauPointage, $tableauPrev, $colPointage="")  {
    global $TRACE_INFO_POINTAGE;
    
    showActionVariable ( "function fusionTableauPointage()", $TRACE_INFO_POINTAGE );
    
    // fusion des deux tableaux
    $tableau = $tableauPrev;
    
    $nbResPointage = mysqlNumrows ( $tableauPointage );
    $nbResPrevision = mysqlNumrows ( $tableauPrev );
    
    global $LIST_COLS_MONTHS;
    $arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
    
    $columns = $colPointage;
    if($columns==""){
        global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
        $columns = stringToArray ( $SQL_SHOW_COL_CEGID_POINTAGE2_2 );
    }
    
    
    // copie pointage
    $tableau = $tableauPointage;
    
    for($cpt = 0; $cpt < $nbResPointage; $cpt ++) {
        foreach ( $arrayMonth as $m ) {
            // $value = $tableauPointage[$m][$cpt];
            $value = $tableau [$m] [$cpt];
            $index = findIndexPointage ( $tableauPrev, $tableauPointage, $columns, $cpt );
            if (isset($tableauPrev [$m])&& isset($tableauPrev [$m] [$index])){
                $value2 = $tableauPrev [$m] [$index];
            }
            else{
                $value2="";
            }
            if ($value == "") {
                //import sur vide => couleur bleu
                $value = $value2;
                $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style='color: #0000FF;' " );
                $tableau [$m] [$cpt] = $value;
            } else {
                if (($value2!="") && ($value2!=$value)){
                    //import sur valeur existante differente => couleur rouge 
                    $tableau [$m] [$cpt] = "$value/$value2";
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style=\"color: #FF0000; font-weight:bold\" " );
                }
                else if ($value2==$value){
                    //value equivalente =>couleur verte
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style=\"color:  #00CC00; background: #E0E0E0; font-weight:bold\" " );
                }
                else{
                    //pas dans import : fond gris
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " readonly style=\"background: #E0E0E0; font-weight:bold\" " );
                }
            }
        }
    }


    $cpt2 = $nbResPointage;
    showActionVariable( "nb row previsionnel : $nbResPrevision", $TRACE_INFO_POINTAGE );
    for($cpt = 0; $cpt < $nbResPrevision; $cpt ++) {
        $index = findIndexPointage ( $tableauPointage, $tableauPrev, $columns, $cpt );
        if ($index < 0) {
            // copie premieres colonnes
            foreach ( $columns as $c ) {
                $tableau [$c] [$cpt2] = $tableauPrev [$c] [$cpt];
            }
            // U.O par date
            foreach ( $arrayMonth as $m ) {
                $value = $tableauPrev [$m] [$cpt];
                $tableau = setSQLFlagStyle ( $tableau, $m, $cpt2, " style='color: #0000FF;' " );
                $tableau [$m] [$cpt2] = $value;
            }
            $cpt2 ++;
        }
    }


    return $tableau;
}


?>