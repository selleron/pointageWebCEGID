<?PHP

	require_once( dirname(__FILE__).'/sql/tool_db.php' );
	require_once( dirname(__FILE__).'/sql/user_consultation_db.php' );
	
	global $CHARSET_SERVER;
 	//echo '$CHARSET_SERVER'."=[$CHARSET_SERVER]";
	//$CHARSET_SERVER provient de configuration/<server>.config.php
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$CHARSET_SERVER\" />";
?>

<!--   <meta name="google-site-verification" content="2x-vGGJmAabuIje6cOr0K-TyfI9Fi5bhjwsSRxkUIUM" /> -->
<!--   <meta name="Keywords" CONTENT="pointage, kyoto-fr"> -->
  
  <style type="text/css"> 
    /* Choses a charger meme quand aucune feuille n'est selectionnee */
    
    #sf-logo {border: none; height: 31px; width: 88px;}
    .warning { border: medium dotted black; padding: 0.5em; background-color: rgb(255, 255, 180); }
    .warning strong { background-color: rgb(255, 204, 51); }
	pre {border: 1px dashed gray; margin-left:2em; margin-right:1em; overflow:auto;}
	h3 {clear:left;}
	.feature {float:left; width: 200px;	margin: 0 1em 10px 2em; padding: 15px 15px 0 15px; text-align:center; border: 1px solid lightgray;}
	.feature p {font-size:80%; text-align:center; margin: 5px;}
	a img {border:0;}
  </style>
  
  <?PHP
  	selectCSS(); 
  
  	//global $URL_ROOT_POINTAGE;
  	//$URL_IMAGES=$URL_ROOT_POINTAGE."/images";
  	global $URL_IMAGES;
  	 
  echo '<link rel="home" href="../" />';
  echo '<link rel="icon" href="'.$URL_IMAGES.'/webicon.png" type="image/png" />';
?>
