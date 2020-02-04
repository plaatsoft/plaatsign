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


$url2 = "https://dagelijksebroodkruimels.nl/feed/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$content = curl_exec( $ch );
$content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
curl_close ( $ch );
$xml = simplexml_load_string($content);


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
