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

/* Embed image data in php source code */
$filename = 'image.png';

$imagedata = file_get_contents($filename);
$code = base64_encode($imagedata);

echo "<pre>";
echo '$image = base64_decode("'."\r\n";
echo wordwrap($code, 120, "\r\n", true);
echo '");'."\r\n";
echo "</pre>";

?>