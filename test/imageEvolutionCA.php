<?php

/* CAT:Area Chart */

/* pChart library inclusions */
include_once(dirname ( __FILE__ ) . "/toolGraphPChart.php");
include_once (dirname ( __FILE__ ) . "/toolDataCA.php");
include_once (dirname ( __FILE__ ) . "/../sql/form_project_db.php");

 use pChart\pColor;
 use pChart\pDraw;
 use pChart\pCharts;

 
 global $TRACE_GENERATION_IMAGE;
     
     
     
 
 $yearURL = getURLYear();
 if ($TRACE_GENERATION_IMAGE=="yes"){
     echo " TRACE_GENERATION_IMAGE : $TRACE_GENERATION_IMAGE <br>";
     echo " year url detected : [$yearURL] <br>";
 }
 
 $year=2019;
 $year=$yearURL;
 
 //$year= 2020;
 //$infoForm = streamFormHidden($YEAR_SELECTION, $year);

 /* Create the pChart object */
//$myPicture = new pDraw(700,230);
$myPicture = createGraph(1000,500);

$dataCAReel2 = computeCAReel($year,"no"/*cumul*/, $TRACE_GENERATION_IMAGE /*trace*/);
$dataCAReelCumul = computeCAReel($year,"yes"/*cumul*/, $TRACE_GENERATION_IMAGE /*trace*/ );
$dataCAPrevCumul = computeCAPrevisionnel($year,"yes"/*cumul*/, $TRACE_GENERATION_IMAGE /*trace*/);

/* Populate the pData object */
$myPicture->myData->addPoints($dataCAPrevCumul[1],"CA Prev.");
$myPicture->myData->addPoints($dataCAReelCumul[1],"CA Reel");
//$myPicture->myData->addPoints($dataCAReel2[1],"CA mois");

//courbe avec des pointilles et epaisseur
$myPicture->myData->setSerieTicks("CA Prev.",4);
$myPicture->myData->setSerieWeight("CA Prev.",2);
//$myPicture->myData->setSerieTicks("CA mois",4);


$myPicture->myData->setAxisName(0,"CA - Euro");
$myPicture->myData->addPoints(["Jan","Fev","Mar","Avr","Mai","Jui","Juil","Aou","Sep","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

//init graph
$myPicture = initGraph($myPicture, "Pau - CA $year", "no" /*draw legend*/);

/* Draw the area chart */
$pCharts = new pCharts($myPicture);

$pCharts->drawAreaChart();
//$pCharts->myPicture->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);



//====add curve =============
$dataCAPreviousCumul = computeCAReel($year-1,"yes");
$myPicture->myData->addPoints($dataCAPreviousCumul[1],"CA ".($year-1));

//draw line and point curves
$pCharts->drawLineChart();
$pCharts->drawPlotChart(["PlotBorder"=>TRUE,"PlotSize"=>3,"BorderSize"=>1,"Surrounding"=>-60,"BorderColor"=>new pColor(50,50,50,80)]);



drawLegend($myPicture);


/* Render the picture (choose the best way) */
//$myPicture->autoOutput("temp/example.drawAreaChart.simple.png");
if ($TRACE_GENERATION_IMAGE=="yes"){
    //nothing to do
}
else{
     $myPicture->Stroke();
}
?>
