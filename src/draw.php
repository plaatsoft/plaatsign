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

/* Set default timezone */
date_default_timezone_set ( "Europe/Amsterdam" );

$width = 1920/2;
$height = 1080/2;

$font = './../../fonts/arial.ttf';

// -------------------------------------------------------

function drawUrlImage($im, $x, $y, $url, $width1=128, $height1=128) {

	global $width;
	global $height;
		
	$data = file_get_contents($url);
	$src = imagecreatefromstring($data);	
			
	$dst = imagecreatetruecolor($width, $height);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $width1, $height1, imagesx($src), imagesy($src));
	
	// Copy and merge
	imagecopymerge($im, $dst, $x, $y, 0, 0, $width1, $height1, 100);
}

function drawBackgound($im, $background) {

	global $width;
	global $height;
	
	$src = imagecreatefromstring($background);	
	$dst = imagecreatetruecolor($width, $height);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
	
	// Copy and merge
	imagecopymerge($im, $dst, 0, 0, 0, 0, $width, $height, 100);
}

function drawLabel($im, $x, $y, $text, $font_size=28, $color) {
	
	global $font;
	global $width;
	global $height;
	
	// Get Bounding Box Size
	$text_box = imagettfbbox($font_size, 0, $font, $text);

	// Get your Text Width and Height
	$text_width = $text_box[2]-$text_box[0];
	
	// Calculate coordinates of the text
	if ($x==0) {
		$x = ($width/2) - ($text_width/2);
	}

	// Add some shadow to the text
	imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);
	
	return $y + $font_size + 5;
}

function drawTextBox($im, $x, $y, $text, $font_size=28, $color) {

	global $font;
	global $width;
	global $height;
	
	$text = str_replace(  '&nbsp;', '', $text);
	$text = str_replace(  '<em>', '"', $text);
	$text = str_replace(  '</em>', '"', $text);
	$text = str_replace(  '<b>', '', $text);
	$text = str_replace(  '</b>', '', $text);
	$text = str_replace(  '<i>', '', $text);
	$text = str_replace(  '</i>', '', $text);
	$text = str_replace(  '<br>', ' ', $text);
	$text = str_replace(  '<p>', ' ', $text);
	
	$buffer = wordwrap($text, 52, "<br/>", false);		
	$buffer = explode("<br/>", $buffer);
	
	$center = false;	
	if ($x==0) {
		$center = true;
	}
	
	foreach ($buffer as $line) {
		
		$bbox = imageftbbox($font_size, 0, $font, $line);

		if ($center) {
			$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 5;
		}
		
		imagefttext($im, $font_size, 0, $x, $y, $color, $font, $line);
		
		$y+=$font_size+6;
	}
	return $y;
}

function drawDashedLine($im, $x, $y, $width, $color) {
    
	 $dist = 3;
	 	
    $nextX = $dist * 2;

    for ($x1 = $x; $x1 <= $width; $x1 += $nextX) {
        imageline($im, $x1, $y, $x1 + $dist - 1, $y, $color);
    }
}

function drawAxes($im, $x, $y, $data, $color)  {
	
	global $width;
	global $height;
	global $font;
	
	$lines = 5;
	$font_size = 10;
	
	$max = getMax($data);	
	$step = ceil($max / $lines);
	$pixel = ($height-180) / $lines;
	
	$starty = $y+$height-120;
	
	for ($y1=0; $y1<=$lines; $y1++) {
		drawDashedLine($im, $x, $starty-($y1*$pixel), $width-150, $color);
		imagettftext($im, $font_size, 0, $x-50, $starty-($y1*$pixel)+$lines, $color, $font, $step*$y1);
	}
}

function drawBox($im, $x1, $y1, $x2, $y2, $color)  {
	imagefilledrectangle( $im , $x1, $y1 ,$x2, $y2, $color );
}


// -------------------------------------------------------

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

function getTotal($data) {

	$total=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
		$total += $data[$row][1] + $data[$row][2] + $data[$row][3];
	}

	return $total;
}

function getAverage($data) {

	$total=0;
	$count=0;
	
	for ($row=0; $row<sizeof($data); $row++) {
	
		$value = $data[$row][1] + $data[$row][2] + $data[$row][3];
		if ($value>0) {
			$count++;
		}
		$total += $value;
	}
	
	$average=0;
	if (($count>0) && (sizeof($data)>0)) {
		$average = $total / $count;
	}
	
	return $average;
}

// -------------------------------------------------------

?>