<?php
include_once 'pointage_cegid_db.php';

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
 * @param index $cpt        	
 * @param index $cptP        	
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
 * @param index $cptP        	
 * @return number|unknown index trouvé dans $tableauPrev
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
	
	showTablePointageOneProjetCegid ( $tableau );
}

?>