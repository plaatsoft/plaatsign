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
include "config.php";
include "database.php";

$time_start = microtime(true);

/*
** ---------------------
** CRON
** ---------------------
*/

$base_src = getcwd().'/uploads/scripts/';
$base_des1 = getcwd().'/uploads/images/';
$base_des2 = getcwd().'/uploads/';

plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);

$query = 'select cid, filename, refresh from content where tid='.TYPE_SCRIPT.' order by cid';
$result = plaatsign_db_query($query);

while ($data=plaatsign_db_fetch_object($result)) {			

	list($name, $extension) = explode(".", $data->filename);	
	$filename_src =  $data->cid.'.php';
	$filename_des =  $data->cid.'.png';
	
	if (is_file($base_src.$filename_src))  { 

		$time_diff = 0;
		if (is_file($base_des1.$filename_des)) {
			$time_diff = strtotime(date("d-m-Y H:i:s")) - filemtime($base_des1.$filename_des);
		}

		if (DEBUG==1) {
			echo $base_src.$filename_src." ".$time_diff."\n\r";
		}
				
		if ($time_diff>($data->refresh*60)) {
			
			$command = 'cd '.$base_src.' && php '.$filename_src.' > '.$base_des2.$filename_des;			
				
			if (DEBUG==1) {
				echo $command."\n\r";
			}
			
			exec($command);
			rename($base_des2.$filename_des, $base_des1.$filename_des);
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