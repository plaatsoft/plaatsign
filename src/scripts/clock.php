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

$width = 1920/2;
$height = 1080/2;

$font = './../../fonts/arial.ttf';
if (!file_exists($font)) {
	$font = './../fonts/arial.ttf';
}

/* Set default timezone */
date_default_timezone_set ( "Europe/Amsterdam" );

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

// -------------------------------------------------------

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
$gray = imagecolorallocate($im, 0x85, 0x85, 0x85);

imagefilledrectangle($im, 0, 0, $width, $height, $white);
drawLabel($im, ($height/2)-20, date("d-m-Y ", time()), 128, $black);
drawLabel($im, ($height/2)+140, date("H:i", time()), 128, $black);
drawLabel($im, $height-10, 'PlaatSoft 2008-2016 - All Copyright Reserved - PlaatSign', 12, $gray);

imagepng($im);
imagedestroy($im);

?>