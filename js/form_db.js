<script type="text/javascript">

/**
 * getParentHTMLTableElement
 * obj : node de depart
 * return an objet type HTMLTableElement or null
 */
function getParentHTMLTableElement(obj){
	return getParentHTMLElement(obj, HTMLTableElement );
}

/**
 * getParentHTMLElement
 * obj      : node de depart
 * theClass : class recherchée ex : HTMLTableElement
 * return an objet type theClass or null
 */
function getParentHTMLElement(obj, theClass){
	while (!(obj instanceof theClass)){
		//alert(obj);
		if (obj == null){
			return obj;
		}
		obj = obj.parentNode;
	}
	//alert(obj);
	return obj;
}

/**
 * getCellSumColId
 * retourne le nom de la cellule ou mettre la somme a partir du nom de la colonne de sommation
 */
function getCellSumColId(obj, name){
	return 	sommeCellId = "sum_col_"+name;
}

/**
 * getCellId get cell id
 * return "name[row]"
 */
function getCellId(name, row){
	return 	name+"["+row+"]";
}

/**
 * sommeColonneRowHTMLTable
 * sommation tableau vertical et horizontal
 * obj               : objet de depart. En general une HTMLTableElement
 * nameForColSum     : noms des colonnes ou il y aura une sommation
 * nameForRowSum     : noms des colonnes dans lesquelles on fera une sommation des elts d'une ligne
 * nameColsForRowSum : noms des colonnes sur laquelle a lieu la sommation d'une ligne
 */
function sommeColonneRowHTMLTable( obj, nameForColSum, nameForRowSum, nameColsForRowSum, operation="sum") {
	//test();
	sommeRowsHTMLTable(obj, nameForRowSum, nameColsForRowSum,operation);
	if (nameForColSum!=""){
		sommeColonneHTMLTable(obj, nameForColSum, operation);
	}
}	

/**
 * fait la somme pour chaque ligne
 * obj  : la table
 * name : name of the cell somme sans indice
 * cols : liste des colonnes à sommer
 */
function sommeRowsHTMLTable( obj, name, cols, operation="sum") {
	//alert("operation name: "+name+" - cols: "+cols+" op: "+ operation);
	table = getParentHTMLTableElement(obj);
	if (table == null){
		alert("function sommeRowsHTMLTable().\n  no table found for "+obj);
	}

	var nbLignes = table.rows.length;
	var showAlert = false;
	for (row = 0 ; row < nbLignes ; row++){
		sommeRowHTMLTable( obj, name, cols, row, showAlert, operation);			
	}
}

/**
 * sommeColonneHTMLTable
 * sommation sur une colonne : somme de l'indice 0 à n-1
 * object owner obj (HTMLInputElement en general)
 * variable name a sommer
 */
function sommeColonneHTMLTable( obj, name, operation="sum") {

	var nameArray = name.split(",");
	if (nameArray.length > 1){
		//alert("function sommeColonneHTMLTable.\n  summation " +operation +" on "+name);
		for(var i= 0; i < nameArray.length; i++){
			sommeColonneHTMLTable(obj, nameArray[i], operation);
		}
	}
	else{
		somme = 0;
		countSum=0;
		table = getParentHTMLTableElement(obj);
		if (table == null) alert("function sommeColonneHTMLTable.\n  no table found for "+obj);
		//var nbLignes = document.getElementById(name).rows.length;
		var nbLignes = table.rows.length;
		sommeCellId = getCellSumColId(obj, name);
		sommeElement = document.getElementById(sommeCellId);
		if (sommeElement == null) {
			alert("function sommeColonneHTMLTable.\n  no cell found for id : "+sommeCellId);
		}

		for (i=0;i<nbLignes;i++){
			//idCell = name+"["+i+"]";
			idCell = getCellId(name,i);
			cell = document.getElementById(idCell);
			if (cell!=null){
				//alert("cell value :" + cell.value+" for : "+idCell);
				if (cell.value){
					value = parseFloat(cell.value.replace(" ",""));
					if (!isNaN(value)){
						countSum+=1;
						if (operation=="sum"){
							somme += value; 
						}
						if (operation=="mult"){
							if (countSum==1){
								somme=1;
							}
							somme *= value; 
						}
					}
				}
				else{
					if (cell.textContent){
						value = parseFloat(cell.textContent.replace(" ",""));
						if (!isNaN(value)){
							countSum+=1;
							if (operation=="sum"){
								somme += value; 
							}
							if (operation=="mult"){
								if (countSum==1){
									somme=1;
								}
								somme *= value; 
							}
						}
					}
				}
			}
			else{
				//alert("no cell found for id : "+idCell);
			}
		}
		if (countSum==1 && operation=="mult"){
			somme="";
		}
		sommeElement.value = formatNumber(somme);
	}
}

function formatNumber(num){
	num = num.toLocaleString("en");
	num = num.replace(","," ");
	return num;
}

/**
 * sommeRowHTMLTable
 * object owner obj (HTMLInputElement en general)
 * variable name a sommer
 */
function sommeRowHTMLTable( obj, name, cols, row, showAlert=false, operation="sum") {
	//alert("sommeRowHTMLTable name: "+name+" - row : "+row+" - cols: "+cols+" op: "+ operation);
	
	var nameArray = name.split(",");
	if (nameArray.length > 1){
		for(var i= 0; i < nameArray.length; i++){
			sommeRowHTMLTable(obj, nameArray[i], cols, row, showAlert, operation);
		}
	}
	else{
		var colArray = cols.split(",");
		somme = 0;
		countSum=0;
		//table = getParentHTMLTableElement(obj);
		table = getParentHTMLElement(obj, HTMLTableElement);
		if (table == null) alert("function sommeRowHTMLTable.\n  no table found for "+obj);
		//var nbLignes = document.getElementById(name).rows.length;
		sommeCellId = getCellId(name, row);
		sommeElement = document.getElementById(sommeCellId);
		//alert("sum elt id: "+sommeCellId+" classs : "+sommeElement);
		if ( sommeElement == null ){
			if (showAlert) alert("function sommeRowHTMLTable.\n  no cell found for id : "+sommeCellId);
			return;
		}

		for (i=0;i<colArray.length;i++){
			//idCell = colArray[i]+"["+row+"]";
			idCell = getCellId(colArray[i], row);
			cell = document.getElementById(idCell);
			if (cell!=null){
				if (cell.value){
				  value = parseFloat(cell.value.replace(" ",""));
				}
				else if (cell.textContent){
					value = parseFloat(cell.textContent.replace(" ",""));
				}
				else{
					value=NaN;
				}
				//alert("sommeRowHTMLTable "+name+" cell : "+cell+" id : "+idCell+" value :" + value);
				if (!isNaN(value)){
					countSum+=1;
					if (operation=="sum"){
						somme += value; 
					}
					if (operation=="mult"){
						if (countSum==1){
							somme=1;
						}
						somme *= value;
					}
				}
			}
			else{
				//alert("no cell found for id : "+idCell);
			}
		}
		if (countSum==1 && operation=="mult"){
			somme="";
		}
		suffix="";
		if (sommeElement.value){
			//alert("sommeRowHTMLTable sommeElement.value :" + sommeElement.value);
			if (sommeElement.value.indexOf("€") !== -1){
				suffix = " €";
			}
			sommeElement.value = formatNumber(somme)+suffix;
		}
		else{
			//alert("sommeRowHTMLTable sommeElement.textCalue :" + sommeElement.textContent);
			if (sommeElement.textContent.indexOf("€") !== -1){
				suffix = " €";
			}
			sommeElement.textContent = formatNumber(somme)+suffix;			
		}
		//alert("sommeRowHTMLTable name :"+name+"  "+sommeElement+" somme : "+somme);
		
	}
}


/**
 * petite fonction de test
 */
function test() {
	alert("Test");
}

</script>