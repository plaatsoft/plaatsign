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

function drawBackgound($im, $background) {

	global $width;
	global $height;
	
	$src = imagecreatefromstring($background);	
	$dst = imagecreatetruecolor($width, $height);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));
	
	// Copy and merge
	imagecopymerge($im, $dst, 0, 0, 0, 0, $width, $height, 100);
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
	
	return $y + $font_size + 5;
}

function drawTextBox($im, $y, $text, $font_size=28, $color) {

	global $font;
	global $width;
	global $height;
	
	$text = str_replace(  '&nbsp;', '', $text);
	$text = str_replace(  '<em>', '"', $text);
	$text = str_replace(  '</em>', '"', $text);
	
	$buffer = wordwrap($text, 60, "<br/>", false);		
	$buffer = explode("<br/>", $buffer);
	
	foreach ($buffer as $line) {
	
		//echo $line.'+';
		
		// First we create our bounding box
		$bbox = imageftbbox($font_size, 0, $font, $line);

		// This is our cordinates for X and Y
		$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 5;
		
		imagefttext($im, $font_size, 0, $x, $y, $color, $font, $line);
		
		$y+=$font_size+5;
	}
	return $y;
}

// -------------------------------------------------------

?>