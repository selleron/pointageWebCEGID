<script type="text/javascript">


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}


function inverseVisibilite(thingId) { 
	var targetElement; 
	targetElement = document.getElementById(thingId) ; 
	if (targetElement.style.display == "none")	{ 
		targetElement.style.display = "" ; 
	} else { 
		targetElement.style.display = "none" ; 
	} 
} 


function getVisibiliteCookie(thingId){
	getVisibiliteCookie(thingId, "");
}


function getVisibiliteCookie(thingId, defaultStatus){
	var targetElement; 
	targetElement = document.getElementById(thingId) ; 
	
	etat = readCookie("menu."+thingId+".style.display");
	if (etat==null){
		targetElement.style.display = defaultStatus;
	}
	else{
		targetElement.style.display = etat;
	}
}

function inverseVisibiliteCookie(thingId){
	inverseVisibilite(thingId);
	var targetElement; 
	etat = document.getElementById(thingId).style.display ; 
	createCookie("menu."+thingId+".style.display", etat, 30);
}

function setPlateformMobile(){
	createCookie("plateform", "mobile", 30);
}

function setPlateformPC(){
	createCookie("plateform", "pc", 30);
}


</script>