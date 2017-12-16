<?php

include_once (dirname(__FILE__) . "/../configuration/labelAction.php");

// key pour les combo box principale
class FORM_COMBOX_BOX_KEY {
    const PROJECT_SELECTION = "project";
    const YEAR_SELECTION = "year";
    const USER_SELECTION = "user";
}

//pour compatibilite
$PROJECT_SELECTION = FORM_COMBOX_BOX_KEY::PROJECT_SELECTION;
$YEAR_SELECTION    = FORM_COMBOX_BOX_KEY::YEAR_SELECTION;
$USER_SELECTION    = FORM_COMBOX_BOX_KEY::USER_SELECTION;







/**
 * getURLYear
 *
 * @param int $defaultValue : l'annee par defaut 
 * @return int par defaut retourne l'année courante
 */
function getURLYear($defaultValue = null)
{
    //showSQLAction("called getURLYear( $defaultValue  )");
    $year = getURLVariable(FORM_COMBOX_BOX_KEY::YEAR_SELECTION);
    if (! is_numeric($year)) {
        if (is_null($defaultValue)) {
            $year = date("Y");
        } else {
            $year = $defaultValue;
        }
    }
    return $year;
}




?>