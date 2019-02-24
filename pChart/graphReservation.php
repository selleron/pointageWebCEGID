<?php   
include("../sql/connection_db.php"); 
include("../sql/time.php"); 

 


 // Standard inclusions      
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");

include("pChart/class/pScatter.class.php");
 
/* Create the pData object */
$myData = new pData();  
 
/* Build the query that will returns the data to graph */
$Requete = array(
"SELECT Date, Prenom, nom FROM  `reservation` 
	WHERE  `DateArrivee` BETWEEN  '2011-01-01 00:00:00' AND '2012-01-01 00:00:00'
	ORDER BY `reservation`.`Date` ",
	
"SELECT Date, Prenom, nom FROM  `reservation` 
	WHERE  `DateArrivee` BETWEEN  '2012-01-01 00:00:00' AND '2013-01-01 00:00:00'
	ORDER BY `reservation`.`Date` ",
	
"SELECT Date, Prenom, nom FROM  `reservation`
	WHERE  `DateArrivee` BETWEEN  '2013-01-01 00:00:00' AND '2014-01-01 00:00:00'
	ORDER BY `reservation`.`Date` ",
		
"SELECT Date, Prenom, nom FROM  `reservation`
	WHERE  `DateArrivee` BETWEEN  '2014-01-01 00:00:00' AND '2015-01-01 00:00:00'
	ORDER BY `reservation`.`Date` "
);

$dateDebut  = array( 
		timeFromDDMMYY(01, 01, 2010), 
		timeFromDDMMYY(01, 01, 2011), 
		timeFromDDMMYY(01, 01, 2012),
		timeFromDDMMYY(01, 01, 2013),
);
$dateLabel  = array("date2011", "date2012", "date2013", "date2014");
$dataLabel  = array("data2011", "data2012", "data2013", "data2014");
$dataY      = array( 2011, 2012, 2013, 2014);

for ($i=0;$i<4;$i++){
	$Result  = mysql_query($Requete[$i]);
	while($row = mysql_fetch_array($Result)) {
		/* Get the data from the query result */
		$timestamp   =  sqlDateToTime($row["Date"]) - $dateDebut[$i];
		$data = $row["Date"];
	
		/* Save the data in the pData array */
		$myData->addPoints( $timestamp, "$dateLabel[$i]");
		$myData->addPoints( $dataY[$i], "$dataLabel[$i]");
	}
}

/* Create the X axis and the binded series */
$myData->setAxisName(0,"Dates de reservation"); 
$myData->setAxisXY(0,AXIS_X); 
//$myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"i:s"); 
//$myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"d.m.y"); 
$myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"d.m"); 
$myData->setAxisPosition(0,AXIS_POSITION_BOTTOM); 
 
/* Create the Y axis and the binded series */
$myData->setSerieOnAxis("data2014",1);
$myData->setSerieOnAxis("data2013",1);
$myData->setSerieOnAxis("data2012",1);
$myData->setSerieOnAxis("data2011",1);
$myData->setAxisName(1,"Location");
$myData->setAxisXY(1,AXIS_Y);
$myData->setAxisUnit(1,"");
$myData->setAxisPosition(1,AXIS_POSITION_LEFT);
 
/* Create the 1st scatter chart binding */
$myData->setScatterSerie("date2011","data2011",0);
$myData->setScatterSerieDescription(0,"2011");
$myData->setScatterSerieColor(0,array("R"=>100,"G"=>20,"B"=>20));
 
/* Create the 2nd scatter chart binding */
$myData->setScatterSerie("date2012","data2012",1);
$myData->setScatterSerieDescription(1, "2012");
$myData->setScatterSerieColor(1,array("R"=>20,"G"=>100,"B"=>20));

/* Create the 3eme scatter chart binding */
$myData->setScatterSerie("date2013","data2013", 2);
$myData->setScatterSerieDescription(2, "2013");
$myData->setScatterSerieColor(2, array("R"=>20,"G"=>20,"B"=>100));

/* Create the 4eme scatter chart binding */
$myData->setScatterSerie("date2014","data2014", 3);
$myData->setScatterSerieDescription(3, "2014");
$myData->setScatterSerieColor(3, array("R"=>100,"G"=>0,"B"=>200));


 
/* Create the pChart object */
$sizePixelX=900;
$myPicture = new pImage($sizePixelX, 400,$myData);
 
/* Draw the background */
$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
$myPicture->drawFilledRectangle(0,0, $sizePixelX, 400,$Settings);
 
/* Overlay with a gradient */
$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
$myPicture->drawGradientArea(0,0, $sizePixelX, 400,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0, $sizePixelX, 20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));
 
/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Silkscreen.ttf","FontSize"=>6));
$myPicture->drawText(10,13,"Dates de reservation",array("R"=>255,"G"=>255,"B"=>255));
 
/* Add a border to the picture */
$myPicture->drawRectangle(0,0, $sizePixelX-1, 399,array("R"=>0,"G"=>0,"B"=>0));
 
/* Set the default font */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));
 
/* Set the graph area */
$myPicture->setGraphArea(50,50, $sizePixelX-50, 350);
 
/* Create the Scatter chart object */
$myScatter = new pScatter($myPicture,$myData);
 
/* Draw the scale */
$myScatter->drawScatterScale();
 
/* Turn on shadow computing */
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 
/* Draw a scatter plot chart */
$myScatter->drawScatterPlotChart();
 
/* Draw the legend */
$myScatter->drawScatterLegend(260,075,array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));
 
/* Render the picture (choose the best way) */
//$myPicture->autoOutput("pictures/example.drawScatterPlotChart.png"); 
$myPicture->Stroke();
  

?>