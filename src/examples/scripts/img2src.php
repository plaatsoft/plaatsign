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

/* Generate embedbed image data in php source code */
$imagedata = file_get_contents($_GET["filename"]);
$code = base64_encode(gzdeflate($imagedata));

echo "size= ".strlen($code)."\r\n";

echo "<pre>";
echo '$image = gzinflate(base64_decode("'."\r\n";
echo wordwrap($code, 120, "\r\n", true);
echo '"));'."\r\n";
echo "</pre>";



?>