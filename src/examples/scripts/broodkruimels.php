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
**  All copyrights reserved (c) 2008-2018 PlaatSoft
*/

include "./../../draw.php";

header('Content-Type: image/png');

$im = imagecreatetruecolor($width, $height);

$url = "https://www.dagelijksebroodkruimels.nl/feed";
$xml = simplexml_load_file($url);

for($i = 0; $i<1; $i++) {
    $url = $xml->channel->item[$i]->children('media', True)->content->attributes();
	$url = str_replace("-400x400", "",$url);

 	drawUrlImage($im, 0, 0, $url, 960, 540);	
}

imagepng($im);
imagedestroy($im);

?>