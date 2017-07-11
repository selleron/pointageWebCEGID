<?php
include_once 'pointage_cegid_db.php';
include_once 'labelAction.php';
include_once 'historisation_db.php';

$SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL = "cegid_pointage_previsionnel";
$SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL2 = "cegid_pointage_previsionnel as p,  cegid_user as u, cegid_project as pj";


/**
 * applyGestionPointagePrevisionnelCegid
 */
function applyGestionPrevisionnelProjetCegid() {
	global $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	
	$table = $SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL;
	return applyGestionTablePointageProjetCegid ( $table );
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
 * @return number|unknown index trouvé dans $tableauPrev
 */
function isSamePointageRef($tableauPrev, $tableauPointage, $columns, $cpt, $cptP) {
	foreach ( $columns as $c ) {
		if ($tableauPrev [$c] [$cpt] == $tableauPointage [$c] [$cptP]) {
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
	$nbResPrevision = mysqlNumrows ( $tableauPointage );
	
	for($cpt = 0; $cpt < $nbResPrevision; $cpt ++) {
		$index = isSamePointageRef ( $tableauPrev, $tableauPointage, $columns, $cpt, $cptP );
		if ($index >= 0) {
			return $index;
		}
	}
	return - 1;
}

/**
 * showTablePrevisionnelPointageCegid
 * Affichage du previsionnel
 * - modification par mois
 * - sommation automatique
 */
function showTablePrevisionnelPointageCegid() {
	showAction ( "function showTablePrevisionnelPointageCegid()" );
	
	// condition project
	global $ITEM_COMBOBOX_SELECTION;
	global $PROJECT_SELECTION;
	$projectName = getURLVariable ( $PROJECT_SELECTION );
	if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
		showSQLAction ( "No project Selected..." );
		// $projectName = "no project";
		$projectName = $ITEM_COMBOBOX_SELECTION;
	}
	
	// create tableau de pointage et previsionnel
	$tableauPointage = getTableauPointageProjetCegid ( $projectName, "yes" );
	$tableauPrev = getTableauPrevisionnelCegid ( $projectName, "yes" );
	
// 	// fusion des deux tableaux
// 	$tableau = $tableauPrev;
	
// 	$nbResPointage = mysqlNumrows ( $tableauPointage );
// 	$nbResPrevision = mysqlNumrows ( $tableauPrev );
	
// 	global $LIST_COLS_MONTHS;
// 	$arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
	
// 	global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
// 	$columns = stringToArray ( $SQL_SHOW_COL_CEGID_POINTAGE2_2 );
	
// 	// copie pointage
// 	$tableau = $tableauPointage;
// 	for($cpt = 0; $cpt < $nbResPointage; $cpt ++) {
// 		// //copie premieres colonnes
// 		// foreach ( $columns as $c ) {
// 		// $tableau[$c][$cpt] = $tableauPointage[$c][$cpt];
// 		// }
// 		// U.O par date
// 		foreach ( $arrayMonth as $m ) {
// 			// $value = $tableauPointage[$m][$cpt];
// 			$value = $tableau [$m] [$cpt];
// 			if ($value == "") {
// 				$index = findIndexPointage ( $tableauPrev, $tableauPointage, $columns, $cpt );
// 				$value = $tableauPrev [$m] [$index];
// 				$tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style='color: #0000FF;' " );
// 				$tableau [$m] [$cpt] = $value;
// 			} else {
// 				$tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " readonly style=\"background: #E0E0E0; font-weight:bold\" " );
// 			}
// 		}
// 	}
	
// 	$cpt2 = $nbResPointage;
// 	showAction ( "nb row previsionnel : $nbResPrevision" );
// 	// add previsionnel
// 	for($cpt = 0; $cpt < $nbResPrevision; $cpt ++) {
// 		$index = findIndexPointage ( $tableauPointage, $tableauPrev, $columns, $cpt );
// 		if ($index < 0) {
// 			// copie premieres colonnes
// 			foreach ( $columns as $c ) {
// 				$tableau [$c] [$cpt2] = $tableauPrev [$c] [$cpt];
// 			}
// 			// U.O par date
// 			foreach ( $arrayMonth as $m ) {
// 				$value = $tableauPrev [$m] [$cpt];
// 				$tableau = setSQLFlagStyle ( $tableau, $m, $cpt2, " style='color: #0000FF;' " );
// 				$tableau [$m] [$cpt2] = $value;
// 			}
// 			$cpt2 ++;
// 		}
// 	}
	
	$tableau = fusionTableauPointage($tableauPointage, $tableauPrev);
	showTablePointageOneProjetCegid ( $tableau );
}

/**
* showTablePrevisionnelPointageCegid
* Affichage du previsionnel
* - modification par mois
* - sommation automatique
*/
function fusionTableauPointage($tableauPointage, $tableauPrev)  {
    showAction ( "function showTablePrevisionnelPointageCegid2()" );
    
//     // condition project
//     global $ITEM_COMBOBOX_SELECTION;
//     global $PROJECT_SELECTION;
//     $projectName = getURLVariable ( $PROJECT_SELECTION );
//     if ($projectName == $ITEM_COMBOBOX_SELECTION || $projectName == "") {
//         showSQLAction ( "No project Selected..." );
//         // $projectName = "no project";
//         $projectName = $ITEM_COMBOBOX_SELECTION;
//     }
    
//     // create tableau de pointage et previsionnel
//     $tableauPointage = getTableauPointageProjetCegid ( $projectName, "yes" );
//     $tableauPrev = getTableauPrevisionnelCegid ( $projectName, "yes" );
    
    // fusion des deux tableaux
    $tableau = $tableauPrev;
    
    $nbResPointage = mysqlNumrows ( $tableauPointage );
    $nbResPrevision = mysqlNumrows ( $tableauPrev );
    
    global $LIST_COLS_MONTHS;
    $arrayMonth = stringToArray ( $LIST_COLS_MONTHS );
    
    global $SQL_SHOW_COL_CEGID_POINTAGE2_2;
    $columns = stringToArray ( $SQL_SHOW_COL_CEGID_POINTAGE2_2 );
    
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
                if ($value == "") {
                    $index = findIndexPointage ( $tableauPrev, $tableauPointage, $columns, $cpt );
                    $value = $tableauPrev [$m] [$index];
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " style='color: #0000FF;' " );
                    $tableau [$m] [$cpt] = $value;
                } else {
                    $tableau = setSQLFlagStyle ( $tableau, $m, $cpt, " readonly style=\"background: #E0E0E0; font-weight:bold\" " );
                }
            }
    }
    
    $cpt2 = $nbResPointage;
    showAction ( "nb row previsionnel : $nbResPrevision" );
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
    
    return $tableau;
    //showTablePointageOneProjetCegid ( $tableau );
}

?>