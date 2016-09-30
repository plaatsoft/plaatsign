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

include "general.php";

$time_start = microtime(true);

/*
** ---------------------
** CRON
** ---------------------
*/

$base_src = getcwd().'/uploads/scripts/';
$base_des = getcwd().'/uploads/images/';

$dir = scandir($base_src); 

foreach ($dir as $key => $value) { 

   if (!in_array($value, array(".","..","index.php"))) { 
 
		if (is_file($base_src.$value))  { 
		
         $filename_src = $value; 
			list($name, $extension) = explode(".", $filename_src);			
			
			if ($extension=="php")  { 
			
				$filename_des = $name.'.png';
				
				unlink($base_des.$filename_des);			
				$command = 'cd '.$base_src.' && php '.$filename_src.' > '.$base_des.$filename_des;			
				
				if (DEBUG==1) {
					echo $command."\n\r";
				}
				
				exec($command);
			}
      } 
   } 
}

// Calculate to page render time
$time_end = microtime(true);
$time = $time_end - $time_start;

if (DEBUG==1) {
	echo "cron took ".round($time,2)." secs";
}

?>