<?PHP

//on affiche le menu en mode PC et tablette
//en mode tablette on affiche par dessus
//en mode pc on affiche le menu a gauche
$plateform = getPlateformClient(); //fonction dans tool_db.php
if ($plateform==COOKIE__KEY::COOKIE_PLATEFORM_VALUE_PC){
	//include_once($_SERVER ['DOCUMENT_ROOT']. "/menu_pc.php");
	include_once(dirname(__FILE__). "/menu_pc.php");
}
else {
	//include_once($_SERVER ['DOCUMENT_ROOT']. "/menu_pc.php");
	include_once(dirname(__FILE__). "/menu_pc.php");
}

	

?>
