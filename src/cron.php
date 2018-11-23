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

include "general.php";
include "config.php";
include "database.php";

$time_start = microtime(true);

/*
** ---------------------
** CRON
** ---------------------
*/

$base_scripts = getcwd().'/uploads/scripts/';

plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);

/*
** Process scripts 
*/
$query = 'select cid, filename, refresh, parameters from content where tid='.TYPE_SCRIPT.' order by cid';
$result = plaatsign_db_query($query);

while ($data=plaatsign_db_fetch_object($result)) {			

	$filename_src =  $data->cid.'.php';
	$filename_tmp =  $data->cid.'.tmp';
	$filename_des =  $data->cid.'.png';
	
	if (is_file($base_scripts.$filename_src))  { 

		$time_diff = $data->refresh*60;
		if (is_file($base_scripts.$filename_des)) {
			$time_diff = strtotime(date("d-m-Y H:i:s")) - filemtime($base_scripts.$filename_des);
		}

		if (DEBUG==1) {
			echo $base_scripts.$filename_src." ".$time_diff."\n\r";
		}
				
		if ($time_diff>=($data->refresh*60)) {
			
			$command = 'cd '.$base_scripts.' && php '.$filename_src.' '.$data->parameters.' > '.$base_scripts.$filename_tmp;			
				
			if (DEBUG==1) {
				echo $command."\n\r";
			}
			
			exec($command);
			rename($base_scripts.$filename_tmp, $base_scripts.$filename_des);
		}
	}
}

plaatsign_db_close();

// Calculate to page render time
$time_end = microtime(true);
$time = $time_end - $time_start;

if (DEBUG==1) {
	echo "cron took ".round($time,2)." secs";
}

?>