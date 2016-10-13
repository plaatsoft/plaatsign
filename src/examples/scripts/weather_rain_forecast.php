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

header('Content-Type: image/gif');

$URL = "http://api.buienradar.nl/image/1.0/RadarMapNL?w=700&h=500";
echo file_get_contents($URL);

?>