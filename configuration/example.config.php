<?php 
//Configuration file for Web Server
//echo "Serveur <type> [$SERVER_NAME] connu <br>";


//DataBase configuration
///////////////////////////////////////

//sql user connection
$SQL_USER="root";

//sql user password
$SQL_PWD="mon passord en claire";

//sql host
$SQL_HOST="localhost";

//sql database name
$SQL_DATABASE="ma_data_base";


//use crypted pwd for mobilhome user
//yes or no
$CRYPT_PWD="yes";



// LOG
/////////////////////////////////////
$SHOW_INCLUDE="yes";  						//pas encore utilise
$SHOW_SQL_ACTION="yes";						//trace ou non les actions SQL
$SHOW_MENU_TRACE="false";					//trace dans les menus
$SHOW_CONNECTION_ID="false"; 				//trace ou no l'id de connexion
$SHOW_FUNCION_JAVA="yes";					//trace java script function
$TRACE_POST="yes";							//trace POST
$TRACE_FILE="yes";							//trace File action
$TRACE_INFO_EXPORT="no";					//trace generation export
$TRACE_INFO_POINTAGE="no";					//trace info pointage
$SHOW_FILE_ACTION="yes";                    //trace file operation

$SHOW_SQL_EDIT="yes";						//trace ou non les actions SQL edit
$SHOW_SQL_TYPE_REQUEST_REQUEST="no";		//trace ou non la requete dans une cellule de type SQL_TYPE::SQL_REQUEST
$SHOW_SQL_REPLACE="yes";					//trace ou non les actions SQL replace
$SHOW_SQL_CB_SEARCH="no";					//trace ou non les recherches d'utilisation de cb pour les colonnes sql
$SHOW_FORM_VARIABLE_ATTRIBUT="no";		    //trace les attributs de variable dans form yes | no
$SHOW_FORM_VARIABLE_STYLE="no";		    //trace les styles des variable dans form yes | no
$SHOW_VARIBLE_SUBSTITUTE_SEARCH="quiet";	//trace ou non les recherches de valeurs de variable dans post quiet | verbose | yes
$SHOW_FORM_SUBSTITUTE_SEARCH="quiet";	    //trace ou non les recherches de valeurs de variable dans form quiet | verbose | yes
$SHOW_FORM_TRACE="no";	    				//trace form name parameter yes | no
$SHOW_SYNCHRO_PREVISION_TRACE="no";		//trace synchro previsionnel
$SHOW_REQUEST_TABLE_DATA = "no" ;           //show request in requeteTableData()

// Affichage
/////////////////////////////////////

//permet de ne pas afficher les message showAction
// en mode production mettre sur yes
//yes or no
//peut etre commente
$NO_MSG_CHANGE_HEADER_ACTIVE="yes";
$NO_MSG_CHANGE_HEADER="no";


//CHARSET d'affichage des pages
//a positionner si les accent des textes venant de base de donn�es 
//ne s'affiche pas bien
//UTF8  ISO-8859-1
$CHARSET_SERVER="ISO-8859-1";


//suppression des slash quote dans les variables url
$SUPPRESS_SLASH_QUOTE_URL_VARIABLE="no";


//annee pour les reservations
$ANNEE_RESERVATION=2017;
$ANNEE_MAX=2017;


//root et directory
$PATH_WEB_DIRECTORY   = "/var/www";
$URL_ROOT_POINTAGE    = "/pointage";
$PATH_ROOT_DIRECTORY  = "$PATH_WEB_DIRECTORY"."$URL_ROOT_POINTAGE";
$URL_IMAGES           = $URL_ROOT_POINTAGE."/images";
$PATH_UPLOAD_DIRECTORY= "$PATH_ROOT_DIRECTORY"."/upload";
$URL_UPLOAD_DIRECTORY = "$URL_ROOT_POINTAGE/upload";
$DIR_DEPOT_FROM       = "$PATH_ROOT_DIRECTORY/depot/";
$DIR_DEPOT            = "$PATH_ROOT_DIRECTORY/stockage/";
$SQL_COL_SIZEMAX_BLOB = "10000";


//configuration export
$EXPORT_DELIMITER=";";
$EXPORT_DECIMAL=",";


?>