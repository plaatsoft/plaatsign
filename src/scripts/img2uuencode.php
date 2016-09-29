<?php

$filename = 'solar.png';
$imagedata = file_get_contents($filename);
 
echo base64_encode($imagedata);

?>