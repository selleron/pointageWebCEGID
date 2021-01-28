<?php   
/* CAT:Area Chart */

/* pChart library inclusions */
#require_once("bootstrap.php");
include_once("../pChart/pColor.php");
include_once("../pChart/pColorGradient.php");
include_once("../pChart/pException.php");
include_once("../pChart/pDraw.php");
include_once("../pChart/pData.php");
include_once("../pChart/pCharts.php");

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;



/**
 * createGraph
 * @return \pChart\pDraw
 */
function createGraph($xSize=700, $ySize=230){
    /* Create the pChart object */
    $myPicture = new pDraw($xSize, $ySize);
    
    return $myPicture;
}

/**
 * initGraph
 * @param \pChart\pDraw $myPicture
 * @param string $title
 * @return $myPicture
 */
function initGraph( $myPicture, $title = "TITRE", $drawLegend="yes"){
    /* Turn off Anti-aliasing */
    $myPicture->Antialias = FALSE;
    
    /* Add a border to the picture */
    $xsize = $myPicture->XSize;
    $ysize = $myPicture->YSize;
    //$myPicture->drawFilledRectangle(0,0,$xsize-1,$ysize-1,["Color"=>new pColor(170,183,87), "Dash"=>TRUE, "DashColor"=>new pColor(190,203,107)]);
    $myPicture->drawRectangle(0,0,$xsize-1,$ysize-1,["Color"=>new pColor(0)]);
    
    /* Write the chart title */
    $myPicture->setFontProperties(["FontName"=>"../pChart/fonts/Forgotte.ttf","FontSize"=>11]);
    $myPicture->drawText(150,35,$title,["FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE]);
    
    /* Set the default font */
    $myPicture->setFontProperties(["FontName"=>"../pChart/fonts/pf_arma_five.ttf","FontSize"=>6]);
    
    /* Define the chart area */
    $myPicture->setGraphArea(60,40,$xsize-50,$ysize-30);
    
    /* Draw the scale */
    $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridColor"=>new pColor(200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
    $myPicture->drawScale($scaleSettings);
    
    /* Write the chart legend */
    if ($drawLegend == "yes"){
        $myPicture->drawLegend(540,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);
    }
    
    /* Turn on Anti-aliasing */
    $myPicture->Antialias = TRUE;
 
    
    return $myPicture;
}

function drawLegend($myPicture){
    /* Write the chart legend */
    $myPicture->drawLegend(540,20,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);
}

?>
