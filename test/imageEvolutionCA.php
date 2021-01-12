<?php   
/* CAT:Area Chart */

/* pChart library inclusions */
include_once(dirname ( __FILE__ ) . "/toolGraphPChart.php");
include_once (dirname ( __FILE__ ) . "/toolDataCA.php");

 use pChart\pColor;
 use pChart\pDraw;
 use pChart\pCharts;

 $year=2020;
 $trace="no";
/* Create the pChart object */
//$myPicture = new pDraw(700,230);
$myPicture = createGraph();

$dataCAReel = computeCAReel($year,"yes");
$dataCAPrev = computeCAPrevisionnel($year,"yes");

/* Populate the pData object */
//$myPicture->myData->addPoints([4,2,10,12,8,3],"Probe 1");
$myPicture->myData->addPoints($dataCAPrev[1],"CA Previsionnel");
$myPicture->myData->addPoints($dataCAReel[1],"CA Réel");
//$myPicture->myData->addPoints([3,12,15,8,5,5],"Probe 2");
//$myPicture->myData->addPoints([2,7,5,18,15,22],"Probe 3");

//$myPicture->myData->setSerieTicks("Probe 2",4);

$myPicture->myData->setAxisName(0,"CA - Euro");
$myPicture->myData->addPoints(["Jan","Fev","Mar","Avr","Mai","Jui","Juil","Aou","Sep","Oct","Nov","Dec"],"Labels");
$myPicture->myData->setSerieDescription("Labels","Months");
$myPicture->myData->setAbscissa("Labels");

//init graph
$myPicture = initGraph($myPicture, "Pau - CA $year");

/* Draw the area chart */
(new pCharts($myPicture))->drawAreaChart();

/* Render the picture (choose the best way) */
//$myPicture->autoOutput("temp/example.drawAreaChart.simple.png");
if ($trace == "yes"){
    //nothing to do
}
else{
  $myPicture->Stroke();
}
?>
