<?php

/**
 * suppressCommentMatrice
 * @param unknown $tableau[row][col]
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
	 * @param unknown $tableau
	 * @return unknown
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
 * String to array, separator ","
 *
 * @param unknown $columnsTxt
 * @return string
 */
function stringToArray($columnsTxt) {
	$columnsTxt = str_replace ( " ", "", $columnsTxt );
	$column = explode ( ",", $columnsTxt );
	return $column;
}

/***
 *
* @param unknown $array
* @return string|unknown
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
 * @param matrice[x][y] $array
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
 */
function beginTableHeader($colorBG="#AAAAAA"){
	echo "<thead><tr bgcolor=$colorBG>";
}

/**
 * show end table header
 */
function endTableHeader(){
	echo "</tr></thead>";
}

/**
 * show end table row
 */
function beginTableRow($rowParam=""){
	echo "<tr $rowParam>";
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

function getBeginTableHeaderCell(){
	return "<th>";
}

function getEndTableHeaderCell(){
	return "</th>";
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
 * @param unknown $useTD
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
	echo "<p><table><tr><td>GET :</td></tr>";
	foreach ($_GET as $key => $value){
		echo "<tr><td>".$key.'</td><td>=</td><td>'.$value.'</td></tr>';
	}
	echo"</table></p>";
}

/**
 * show post parameters
 */
function showPost(){
	echo "<p><table><tr><td>POST :</td></tr>";
	
	$size = strlen(serialize($_POST));
	echo '<tr><td>Post Size</td><td>=</td><td>'.$size.'</td></tr>';
	 
	
	foreach ($_POST as $key => $value){
		echo "<tr><td>".$key.'</td><td>=</td><td>'.$value.'</td></tr>';
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