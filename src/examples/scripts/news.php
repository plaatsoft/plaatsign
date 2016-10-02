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

include "./../../draw.php";

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
$gray = imagecolorallocate($im, 0x85, 0x85, 0x85);

imagefilledrectangle($im, 0, 0, $width, $height, $white);

$y=50;
$y = drawLabel($im, $y, 'Laatste Nieuws', 30, $black);
$y+=25;

$url = "http://www.nu.nl/rss/Algemeen";
$xml = simplexml_load_file($url);

for($i = 0; $i < 3; $i++) {
	$title = $xml->channel->item[$i]->title;
	$description = $xml->channel->item[$i]->description;
	$pubDate = $xml->channel->item[$i]->pubDate;
	$enclosure = $xml->channel->item[$i]->enclosure;
	$filename = $enclosure["url"];
	
	drawLabel($im, $y, $title, 24, $black);	
	$y+=50;	
	$y = drawTextBox($im, $y, $description, 20, $gray );	
	$y+=50;
}

drawLabel($im, $height-10, 'PlaatSoft 2008-2016 - All Copyright Reserved - PlaatSign', 12, $gray);

imagepng($im);
imagedestroy($im);

?>