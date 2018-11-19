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
**  All copyrights reserved (c) 1996-2018 PlaatSoft
*/

include "./../../draw.php";

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$url = "https://www.dagelijksebroodkruimels.nl/feed";
$xml = simplexml_load_file($url);

for($i = 0; $i<1; $i++) {
    $url = $xml->channel->item[$i]->children('media', True)->content->attributes();
	
	$start = strrpos($url,"-");
	$end = strrpos($url,".");
	$token = substr($url, $start, $end-$start);

	$url = str_replace($token, "", $url);

 	drawUrlImage($im, 0, 0, $url, 960, 540);	
}

imagepng($im);
imagedestroy($im);

?>