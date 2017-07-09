<?php
include_once (dirname ( __FILE__ ) . "/files.php");
include_once (dirname ( __FILE__ ) . "/project_db.php");
include_once (dirname ( __FILE__ ) . "/cout_project_db.php");
include_once (dirname ( __FILE__ ) . "/previsionnel_cegid_db.php");
include_once (dirname ( __FILE__ ) . "/user_cegid_db.php");

$SQL_TABLE_CEGID_POINTAGE_PREVISIONNEL_USER = "cegid_pointage_previsionnel as p,  cegid_user as u, cegid_project as pj, cegid_project_cout as pc";
$SQL_SHOW_COL_CEGID_POINTAGE_PREVISIONNEL_USER = "    PROJECT_ID,            $SQL_LABEL_PROJECT_NAME,   USER_ID,           NAME,   PROFIL,    COUT";
$SQL_SELECT_COL_CEGID_POINTAGE_PREVISIONNEL_USER = "p.PROJECT_ID, pj.NAME as $SQL_LABEL_PROJECT_NAME, p.USER_ID, u.NAME as NAME, p.PROFIL, pc.COUT";

/**
 * applyPreviousSelectPointage
 *
 * @return number
 */
function applyNextPreviousSelectUser() {
//     if (getActionGet() == LabelAction::ACTION_POINTAGE){
//         global $SQL_COL_NAME_PROJECT;
//         global $PROJECT_SELECTION;;
//         setURLVariable("$PROJECT_SELECTION", getURLVariable("$SQL_COL_NAME_PROJECT"));
//     }
//     else 
     if ((getActionGet () == LabelAction::ACTION_PREVIOUS) || (getActionGet () == LabelAction::ACTION_NEXT)) {
        //showSQLAction("action previous demanded");
        
        global $SQL_TABLE_CEGID_USER;
        global $SQL_COL_ID_CEGID_USER;
        global $SQL_COL_NAME_CEGID_USER;
        
        $col = "";
        $param = createDefaultParamSql ( $SQL_TABLE_CEGID_USER, $col, $condition );
        if (getActionGet () == LabelAction::ACTION_PREVIOUS) {
            $param [ORDER_ENUM::ORDER_GET] = $SQL_COL_ID_CEGID_USER;
            $param [ORDER_ENUM::ORDER_DIRECTION] = ORDER_ENUM::ORDER_DIRECTION_DESC;
        }
        
        $request = createRequeteTableData ( $param );
        //showSQLAction($request);
        
        global $USER_SELECTION;
        global $ITEM_COMBOBOX_SELECTION;
        
        $currentProject = getURLVariable ( $USER_SELECTION );
        
        $Resultat = requeteTableData ( $param );
        $nbRes = mysqlNumrows ( $Resultat );
        //showSQLAction("nb user : $nbRes");
        
        if ($nbRes > 0 && $currentProject == $ITEM_COMBOBOX_SELECTION) {
            $nextUser = mysqlResult ( $Resultat, 0, $SQL_COL_NAME_CEGID_USER );
        }
        if ($nbRes > 0 && $currentProject == "") {
            $nextUser = mysqlResult ( $Resultat, 0, $SQL_COL_NAME_CEGID_USER );
        }
        
        if ($nextUser == "") {
            for($cpt = 0; $cpt < ($nbRes - 1); $cpt ++) {
                $name = mysqlResult ( $Resultat, $cpt, $SQL_COL_NAME_CEGID_USER );
                if ($name == $currentProject) {
                    $nextUser = mysqlResult ( $Resultat, ($cpt + 1), $SQL_COL_NAME_CEGID_USER );
                }
            }
        }
        
        if ($nextUser == "") {
            // nothing to do
        } else {
            setURLVariable ( $USER_SELECTION, $nextUser );
            //showSQLAction("User Selection : $nextUser");
        }
        
        return 1;
    } else {
        return - 1;
    }
}

?>