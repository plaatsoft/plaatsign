<?php

/* 
**  ==========
**  plaatsign
**  ==========
**
**  Created by wplaat
**
**  For more information visit the following website.
**  Website : www.plaatsoft.nl 
**
**  Or send an email to the following address.
**  Email   : info@plaatsoft.nl
**
**  All copyrights reserved (c) 2008-2016 PlaatSoft
*/

// -------------------------------------------------------

define('DEBUG', 0);

// Database credentials
$dbhost = "127.0.0.1";
$dbname = "plaatenergy";
$dbuser = "plaatenergy";
$dbpass = "plaatenergy";

$database = "./../../database.php";
if (!file_exists($database)) {
	$database = "./../database.php";
}
include $database;

plaatsign_db_connect($dbhost, $dbuser, $dbpass, $dbname);

$month=date('m');
$year=date('Y');

$data = array();

for($d=1; $d<=31; $d++) {
		
	$time=mktime(12, 0, 0, $month, $d, $year);  
        
	if (date('m', $time)==$month) {
		$timestamp1=date('Y-m-d 00:00:00', $time);
		$timestamp2=date('Y-m-d 23:59:59', $time);
		
		$sql  = 'select sum(low_delivered) as low_delivered, sum(normal_delivered) as normal_delivered, ';
		$sql .= 'sum(solar_delivered) as solar_delivered from energy_summary ';
		$sql .= 'where date>="'.$timestamp1.'" and date<="'.$timestamp2.'"';

		$result = plaatsign_db_query($sql);
		$row = plaatsign_db_fetch_object($result);
		
		$low_delivered = 0;
		$normal_delivered = 0;
		$solar_delivered = 0;
		
		if (isset($row->low_delivered)) {
			$low_delivered = $row->low_delivered;
			$normal_delivered = $row->normal_delivered;
			$solar_delivered = $row->solar_delivered;
		}
		
		$locale_delivered = $solar_delivered- $low_delivered - $normal_delivered;
		$data[] = array(date('d-m', $time), $low_delivered, $normal_delivered, $locale_delivered);
	}
}
				

// -------------------------------------------------------

function getTotal($data) {

	$total=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
		$total += $data[$row][1] + $data[$row][2] + $data[$row][3];
	}

	return number_format($total,2);
}

function getAverage($data) {

	$total=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
		$total += $data[$row][1] + $data[$row][2] + $data[$row][3];
	}
	
	$average =0;
	if (sizeof($data)>0) {
		$average = $total / sizeof($data);
	}
	
	return number_format($average,2);
}

function getMax($data) {

	$max=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
		$value = $data[$row][1] + $data[$row][2] + $data[$row][3];
		if ($value>$max) {
			$max = $value;
		}
	}
	return $max;
}
 
function drawLabel($im, $y, $text, $font_size=28, $color) {
	
	global $font;
	global $width;
	global $height;
	
	// Get Bounding Box Size
	$text_box = imagettfbbox($font_size, 0, $font, $text);

	// Get your Text Width and Height
	$text_width = $text_box[2]-$text_box[0];
	
	// Calculate coordinates of the text
	$x = ($width/2) - ($text_width/2);

	// Add some shadow to the text
	imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);
}

function drawLegend($im, $text1, $text2, $text3, $text4)  {

	global $width;
	global $height;
	global $font;
	
	global $black;
	global $blue1;
	global $blue2;
	global $blue3;
	global $red;
	
	$size = 10;
	$font_size = 13;
	
	imagefilledrectangle( $im, 200, $height-80 , 200+$size , $height-80+$size, $blue1 );
	imagettftext($im, $font_size, 0, 220, $height-70, $black, $font, $text1);
	
	imagefilledrectangle( $im, 330, $height-80 , 330+$size , $height-80+$size, $blue2 );
	imagettftext($im, $font_size, 0, 350, $height-70, $black, $font, $text2);
	
	imagefilledrectangle( $im, $width-480, $height-80 , $width-480+$size , $height-80+$size, $blue3 );
	imagettftext($im, $font_size, 0, $width-460, $height-70, $black, $font, $text3);
	
	imagefilledrectangle( $im, $width-350, $height-80 , $width-350+$size , $height-80+$size, $red );
	imagettftext($im, $font_size, 0, $width-330, $height-70, $black, $font, $text4);
}

function imagelinedashed($im, $offset, $y, $dist, $col) {
    $width = imagesx($im);
    $nextX = $dist * 2;

    for ($x = $offset; $x <= $width; $x += $nextX) {
        imageline($im, $x, $y, $x + $dist - 1, $y, $col);
    }
}

function drawAxes($im, $data, $color)  {
	
	global $width;
	global $height;
	global $offset;
	global $arial;
	global $font;
	
	global $gray;

	$lines = 5;
	$font_size = 10;
	
	$max = getMax($data);	
	$step = ceil($max / $lines);
	$pixel = ($height-180) / $lines;
	
	$starty = $height-120;
	
	for ($y=0; $y<=$lines; $y++) {
		imagelinedashed($im, $offset+10, $starty-($y*$pixel), 3, $color);
		imagettftext($im, $font_size, 0, 10, $starty-($y*$pixel)+$lines, $color, $font, $step*$y);
	}
}

function drawForcast($im, $data, $value, $color)  {
	
	global $width;
	global $height;
	global $offset;
	global $arial;
	global $font;
	
	global $gray;

	$lines = 5;
	$font_size = 10;
	$pixel = ($height-180) / $lines;
	
	$max = getMax($data);	
	$step = ceil($max / $lines);
	
	$y = $height-120 - ($value / $step) * $pixel;
		
	imagefilledrectangle( $im, $offset+10, $y , $width-5, $y+1, $color );
}

function drawBars($im, $data)  {

	global $width;
	global $height;
	global $offset;
	global $arial;
	global $font;
	
	global $gray;
	global $white;
	global $blue1;
	global $blue2;
	global $blue3;
	
	$lines = 5;
	$font_size = 9;
	
	$max = getMax($data);
	$step = ceil($max / $lines);
	$pixel = ($height-180) / $lines;
	
	$amount = sizeof($data);
	$bar_width = ($width-(2*($offset+90))) / sizeof($data)+2;
	
	$starty = $height - 120;	
	$startx = $offset + 18;
		
	$count=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
			
		$bar_height1 = ($data[$row][1] / $step) * $pixel;		
		$bar_start1 = $starty;
		$bar_end1 = $bar_start1 - $bar_height1;
		if ($data[$row][1]>0) {
			imagefilledrectangle( $im, $startx, $bar_start1 , ($startx+$bar_width) , $bar_end1, $blue1 );		
			if ($data[$row][1]>1) {		
				imagettftext($im, $font_size-2, 0, $startx+5, $bar_end1+16, $white, $font, number_format($data[$row][1],1) );
			}
		}
				
		$bar_height2 = ($data[$row][2] / $step) * $pixel;
		$bar_start2 = $bar_end1;
		$bar_end2 = $bar_start2 - $bar_height2;
			
		if ($data[$row][2]>0) {
			imagefilledrectangle( $im, $startx, $bar_start2 , ($startx+$bar_width) , $bar_end2, $blue2 );
			if ($data[$row][2]>1) {	
				imagettftext($im, $font_size-2, 0, $startx+5, $bar_end2+16, $white, $font, number_format($data[$row][2],1) );
			}
		}
		
		$bar_height3 = ($data[$row][3] / $step) * $pixel;
		$bar_start3 = $bar_end2;
		$bar_end3 = $bar_start3 - $bar_height3;
			
		if ($data[$row][3]>0) {			
			imagefilledrectangle( $im, $startx, $bar_start3 , ($startx+$bar_width) , $bar_end3, $blue3 );
			if ($data[$row][3]>1) {	
				imagettftext($im, $font_size-2, 0, $startx+5, $bar_end3+16, $white, $font, number_format($data[$row][3],1) );
			}
		}
		
		if ($count%2==0) {
			imagettftext($im, $font_size, 0, $startx-5, $starty+20, $gray, $font, $data[$row][0] );
		}

		$startx += $bar_width+4;		
		$count++;	
	}
}

function drawLogo($im) {

	global $width;

	$logo = './../../images/plaatenergy.png';
	if (!file_exists($logo)) {
		$logo = './../images/plaatenergy.png';
	}
	$src= imagecreatefrompng($logo);

	// Copy and merge
	imagecopymerge($im, $src, 150, 12, 0, 0, 32, 32, 100);
	imagecopymerge($im, $src, $width-180, 12, 0, 0, 32, 32, 100);
}

// -------------------------------------------------------

$width = 1920/2;
$height = 1080/2;
$offset = 20;

$font = './../../fonts/arial.ttf';
if (!file_exists($font)) {
	$font = './../fonts/arial.ttf';
}

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
$blue1 = imagecolorallocate($im, 0x00, 0x66, 0xcc);
$blue2 = imagecolorallocate($im, 0x48, 0x82, 0xbc);
$blue3 = imagecolorallocate($im, 0xaa, 0xcc, 0xee);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
$gray = imagecolorallocate($im, 0x85, 0x85, 0x85);
$red = imagecolorallocate($im, 0xff, 0x00, 0x00);

imagefilledrectangle($im, 0, 0, $width, $height, $white);

drawLabel($im, 38, 'Geleverde Electriciteit '.$month.'-'.$year, 24, $black);
drawLogo($im);
drawLegend($im, "Laag (kWh)", "Normaal (kWh)", "Lokaal (kWh)", 'Gemiddeld (kWh)');
drawAxes($im, $data, $gray);
drawForcast($im, $data, getAverage($data), $red);
drawBars($im, $data);
drawLabel($im, $height-38, 'Gemiddeld per dag '.getAverage($data).' kWh [Totaal = '.getTotal($data).' kWh]', 18, $black);
drawLabel($im, $height-10, 'PlaatSoft 2008-2016 - All Copyright Reserved - PlaatEnergy', 12, $gray);

imagepng($im);
imagedestroy($im);

// -------------------------------------------------------

?>