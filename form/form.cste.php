<?php
//echo "include form_cste <br>";
$FORM_CSTE_PHP="FORM_CSTE_PHP";


define( 'PHPFMG_ID' , '20101225-1f1c' ); 
define( 'PHPFMG_YEAR_DEBUT_RESERVATION' , '2014' );
define( 'PHPFMG_YEAR_SELECTION_RESERVATION' , '2014' );

//gestion des noms et path de fichiers
define( 'PHPFMG_ROOT_DIR' , dirname(__FILE__) );
define( 'PHPFMG_SAVE_FILE' , PHPFMG_ROOT_DIR . '/form-data-log.php' ); 				// save submitted data to this file
define( 'PHPFMG_EMAILS_LOGFILE' , PHPFMG_ROOT_DIR . '/email-traffics-log.php' ); 	// log email traffics to this file
define( 'PHPFMG_ADMIN_URL' , '/form/admin.php' );
define( 'PHPFMG_REDIRECT_BASE', "/formulaire_envoye.php" ); 


//autre
$GLOBALS['formOnly']=true;  // empty or false - pas d'ajout de tags <html><body>

//pour debug
//define( 'PHPFMG_SMTP_DEBUG_LEVEL' , 'Y' ); 	// empty or 0 - trun off debug
define( 'PHPFMG_TRACE_DEBUG_LEVEL' , '' ); 		// empty or 0 - trun off debug


?>