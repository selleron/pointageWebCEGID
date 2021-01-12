<?php
include_once(dirname  ( __FILE__ ) . "/../sql/connection_db.php");
include_once (dirname ( __FILE__ ) . "/../sql/sql_db.php");
include_once (dirname ( __FILE__ ) . "/../sql/tool_db.php");


function computeCAReel($year, $cumul = "yes", $trace="no"){
    return computeCA("cegid_pointage", $year, $cumul, $trace);
}

function computeCAPrevisionnel($year, $cumul = "yes", $trace="no"){
    return computeCA("cegid_pointage_previsionnel", $year, $cumul, $trace);
}


function computeCA($table="cegid_pointage", $year, $cumul = "yes", $trace="no"){
    $requestBase = "select cp.UO, cpc.COUT, cp.project_id, cp.profil from $table cp, cegid_project_cout cpc 
                    where 
                       cp.project_id=cpc.project_id and 
                       cp.profil=cpc.profil_id and 
                       year(cp.date)=$year and year(cpc.date)=$year";

    for ($month=1 ; $month <= 12; $month++){
        $request = $requestBase . " and month(cp.date) = $month";
    
        if ($trace == "yes") showSQLAction($request);
        $Resultat = mysqlQuery($request);
        if ($trace == "yes") showSQLError("", $request);
      
        $value = 0;
        for ($Compteur=0 ; $Compteur<mysqlNumrows($Resultat) ; $Compteur++){
            $uo = mysqlResult($Resultat , $Compteur , "cp.UO");
            $cout = mysqlResult($Resultat , $Compteur , "cpc.COUT");
            $value = $value + ($uo*$cout); 
        }
        
        $data[0][$month] = $month;
        if ($cumul == "yes" && $month > 1) {
            $data[1][$month] = $value + $data[1][$month-1] ;
        }
        else{
            $data[1][$month] = $value;
        }
     }
    
    
    return $data;
}


?>