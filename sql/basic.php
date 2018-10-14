<?php

/**
 * suppressCommentMatrice
 * @param array $tableau[row][col]
 */
function suppressCommentMatrice($tableau,$comment="#"){
	$nbRow = count( $tableau );
	$array = array();
	$line = 0;
	for($r=0;$r<$nbRow;$r++){
		$nbCol = count( $tableau[$r] );
		//echo "!!! count $nbCol  :  ".arrayToString($tableau[$r])."<br>";
		if ($nbCol>0){
			$cell = $tableau[$r][0];
			//echo "1 : [$cell] - [$comment] : ".strpos($cell , $comment) ." <br>";
			if (false === strpos($cell , $comment) ){
				$array[$line]=$tableau[$r];
				$line++;
			}
			else{
				echo "comment found : $cell <br>";
				//comment found
				//nothing to do
			}
		}
	}

	//printMatrice($array);
	return $array;
}


	/**
	 * transform array[x][y] to array[y][x]
	 * @param array $tableau[x][y]
	 * @return array $array[y][x]
	 */
function inverseArray2D( $tableau ){
	$nb1 = count( $tableau );
	$nb2 = count( $tableau[0] );

	for($x=0;$x<$nb1;$x++){
		for($y=0;$y<$nb2;$y++){
			$array[$y][$x]=$tableau[$x][$y];
			//print_r($array[$y][$x]);
			//echo " - ";
		}
		//echo "<br>";
	}

	// 	for($x=0;$x<$nb1;$x++){
	// 		for($y=0;$y<$nb2;$y++){
	// 			echo "$array[$y][$x]";
	// 			echo " + ";
	// 		}
	// 		echo "<br>";
	return $array;
}

/**
 * array[col 0..n][row]
 * @param array $tableau
 * @return array with col has key
 */
function indexToKeyArray1D( $tableau, $col="" ){
	$nbCol = count( $tableau );
	$nbRow = count( $tableau[0] );

	if ($col==""){
		$col=$tableau[0];
	}

	//printArray($col,"index to key array 1D");
	$array = array();

	for($c=0;$c<$nbCol;$c++){
		for($r=0;$r<$nbRow;$r++){
			$k=$col[$c];
			//echo "key $k <<<<<<<<<<<<";
			$array[$k][$r]=$tableau[$c][$r];
		}
	}

	//printMatrice($array);
	return $array;
}


/**
 *  * String to array, separator ","
 *  
 * @param string $columnsTxt
 * @param string $separator ","
 * @return array
 */
function stringToArray($columnsTxt,$separator=",") {
    
    if (is_array($columnsTxt)){
        debug_print_backtrace();
    }
    
	//$columnsTxt = str_replace ( " ", "", $columnsTxt );
	$column = explode ( "$separator", $columnsTxt );
	$column= trimArray($column);
	return $column;
}

/**
 * trimArray
 * @param array of string $array
 * @return array of string
 */
function trimArray($array){
    $keys = array_keys($array);
    foreach ($keys as $k){
        $array[$k] = trim($array[$k]);
    }
    return $array;
}

/***
 * arrayToString
 * 
* @param array of string $array
* @return string or NULL (unset)
*/
function arrayToString( $array, $separator=","){
	foreach ($array as $a){
		if (isset($txt)){
			$txt="$txt$separator$a";
		}
		else{
			$txt=$a;
		}
	}
	return $txt;
}

/**
 * printArray
 * show keys   : k1,k2,...
 * show values : v1,v2,...
 * @param array[key=>value] $array
 * @param string $prefix
 */
function printArray($array, $prefix=""){
	$keys = array_keys($array);
	echo $prefix."keys   : ".arrayToString($keys)." <br>";
	echo $prefix."values : ".arrayToString($array)." <br>";

}

/**
 * printMatrice
 * use printArray()
 * @param array $array[x][y]
 */
function printMatrice($array){
	$keys = array_keys($array);
	echo "matrice keys   : ".arrayToString($keys)." <br>";
	foreach ($array as $a){
		printArray($a,">>");
	}
}

/**
 * show begin table header
 * @param string $colorBG color Background "RRGGBB"
 * par defaut on va cherche global $COLOR_TABLE_HEADER;
 */
function beginTableHeader($colorBG=""){
    global $COLOR_TABLE_HEADER;
    
    if ($colorBG == ""){
        $colorBG= "#AAAAAA";
        if (isset($COLOR_TABLE_HEADER)){
            $colorBG=$COLOR_TABLE_HEADER;
        }
    }
	//echo "<thead><tr bgcolor=$colorBG>";// a mettre avec les croll
	echo "<tr bgcolor=$colorBG>";// a mettre avec les croll
}

/**
 * show end table header
 */
function endTableHeader(){
	//echo "</tr></thead>";// a mettre avec les croll
	echo "</tr>";
}


/**
 * show begin table row
 * @param string $rowParam (html)
 * @param integer $rownum permet dalterner les couleurs de ligne
 */
function beginTableRow($rowParam="", $rownum=""){
    global $COLOR_TABLE_ROW_0;
    global $COLOR_TABLE_ROW_1;
    $bgColor = "";
    
     //gestion des couleur de la ligne
     if (is_numeric($rownum)){
         $reste = $rownum % 2;
         if (($reste == 1) && isset($COLOR_TABLE_ROW_1)){
             $bgColor = "bgcolor=$COLOR_TABLE_ROW_1";
         }
         if (($reste == 0) && isset($COLOR_TABLE_ROW_0)){
             $bgColor = "bgcolor=$COLOR_TABLE_ROW_0";
         }
     }
 
    echo "<tr $bgColor $rowParam>";
}

/**
 * show end table row
 */
function endTableRow(){
	echo "</tr>";
}

/**
 * show end table cell
 */
function beginTableCell($cellParam=""){
	echo "<td $cellParam>";
}

/**
 * show end table cell
 */
function endTableCell(){
	echo "</td>";
}

function getBeginTableHeaderCell($style=""){
	//return "<th>";// a mettre avec les croll
	return "<td $style >";
}

function getEndTableHeaderCell(){
	//return "</th>"; // a mettre avec les croll
	return "</td>";
}

/**
 * begin table
 */
function beginTable($tableParam=""){
	echo "<table $tableParam>";
}

/**
 * end table
 */
function endTable(){
	echo "</table>";
}

function beginTableBody(){
	echo "<tbody>";
	//echo "<tbody class='scroll200'>";
}


function endTableBody(){
	//echo"</div>";
	echo "</tbody>"; 
}

/**
 * getVAlign
 * get vertical align
 * @param string $value : top | middle | bottom | baseline
 * @return string
 */
function getVAlign($value="top"){
	return "valign=\"$value\"";
}

/**
 * getAlign
 * get horizontal align
 * @param string $value : right | left | center | justify | char
 * @return string
 */
function getAlign($value="right"){
	return "align=\"$value\"";
}


/**
 * show HTML space
 */
/**
 * echoSpace
 * 
 * @param number $count nb space to insert
 */
function echoSpace($count=1){
	for($i=0;$i<$count;$i++){
		echo " &nbsp;";
	}
}

/**
 * echoTD
 *
 * @param string $txt
 *        	texte to show
 * @param string for boolean $useTD
 *        	use balise <td> yes/no
 */
function echoTD($txt, $useTD="yes") {
    if ($useTD == "yes") {
        echo "<td>";
    }
    
    echo "$txt";
    
    if ($useTD == "yes") {
        echo "</td>";
    }
}

/**
 * getHtmlUrl
 * @param string url $url
 * @param string $titre
 * @return string html link
 */
function getHtmlUrl($url, $titre , $suffix){
  $link="<a href=\"$url\" > $titre $suffix </a>";
  
  return $link;
}

/**
 * echo comment html
 * @param String $txt
 */
function echoComment($txt) {   
    echo "<!-- ";
    echo "$txt";
    echo " -->";
}


/**
 *
 * @param array $array
 * @param value in array to search $value
 * @return number position
 */
function indexOfValueInArray( $array, $value){
	$cpt=0;
	if (!is_array($array)){
		return -2;
	}
	foreach ($array as $a){
		if ($value == $a){
			return $cpt;
		}
		$cpt++;
	}
	return -1;
}

/**
 * show get parameters
 */
function showGet(){
    showGetOrPost("GET", $_GET);
//     echo "<p><table><tr><td>GET :</td></tr>";
// 	foreach ($_GET as $key => $value){
// 		echo "<tr><td>".$key.'</td><td>=</td><td>'.$value.'</td></tr>';
// 	}
// 	echo"</table></p>";
}

/**
 * show post parameters
 */
function showPost(){
    showGetOrPost("POST", $_POST);
}
    
/**
 * show post parameters
 */
    function showGetOrPost($name, $GET_OR_POST){
        echo "<p><table><tr><td>$name :</td></tr>";
	
        $size = strlen(serialize($GET_OR_POST));
	echo '<tr><td>Post Size</td><td>=</td><td>'.$size.'</td></tr>';
	 
	
	foreach ($GET_OR_POST as $key => $value){
	    if (is_array($value)){
	        $count = count($value);
	        $val="";
	        if (($count == 1) && (isset($value[0])) ){
	            $val=" : $value[0]";
	        }
	        echo "<tr><td>".$key.'</td><td>=</td><td>array['.$count.']'.$val.'</td></tr>';
	    }
	    else{
		   echo "<tr><td>".$key.'</td><td>=</td><td>'.$value.'</td></tr>';
	    }
	}
	echo"</table></p>";
}


/**
 * egin script java
 */
function beginScriptJava(){
	echo "<script type=\"text/javascript\">";
}

/**
 * end script
 */
function endScript(){
	echo "</script>";	
}

?>