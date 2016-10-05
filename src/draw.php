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

/* For now picture size is 4 by 3 ratio */
$height = 720;

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

function drawDashedLine($im, $offset, $y, $dist, $col) {
    $width = imagesx($im);
    $nextX = $dist * 2;

    for ($x = $offset; $x <= $width; $x += $nextX) {
        imageline($im, $x, $y, $x + $dist - 1, $y, $col);
    }
}

// -------------------------------------------------------

?>