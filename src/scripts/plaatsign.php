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

include "./../general.php";
include "./../config.php";
include "./../database.php";

define( 'LOCK_FILE', "/tmp/".basename( $argv[0], ".php" ).".lock" ); 
if( plaatsign_islocked() ) die( "Already running.\n" ); 

$time_start = microtime(true);

/*
** ---------------------
** START script
** 
** Create vi /home/pi/.bash_profile
** Enter next two lines
**
** cd /var/www/html/plaatsign/script;
** /usr/bin/php plaatsign.php
** ---------------------
*/

plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);

while (TRUE) {			

	$delay = plaatsign_db_config_get("slide_show_delay");
 
    system('setterm -cursor off');
	system('clear');
	
    $files = scandir('../'.plaatsign_content_path(TYPE_MOVIE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {			

			list($cid, $ext) = explode(".", $file);	

			$query = 'select enabled from content where cid='.$cid;
			$result = plaatsign_db_query($query);
			$data = plaatsign_db_fetch_object($result);
			
			if (isset($data->enabled) && ($data->enabled==1)) {

				$data = '../'.plaatsign_content_path(TYPE_MOVIE).$file;
	
				# Video player	
				$command = '/usr/bin/omxplayer '.$data;	
				
				if (DEBUG==1) {
				
					echo $command."\n\r";
					
				} else {			
					exec($command);		
					system('setterm -cursor off');
					system('clear');
				}
			}
		}
	}
	
	$image_files = "";
	$files = scandir('../'.plaatsign_content_path(TYPE_SCRIPT));
	foreach ($files as $file) {
	
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {
		
			list($cid, $ext) = explode(".", $file);	
			
			if (in_array($ext, array("jpg","jpeg","png","gif"))) {

				$query = 'select enabled from content where cid='.$cid;
				$result = plaatsign_db_query($query);
				$data = plaatsign_db_fetch_object($result);
			
				if (isset($data->enabled) && ($data->enabled==1)) {
						
					if (strlen($image_files)>0) {					
						$image_files .= ' ';
					}
					$image_files .= '../'.plaatsign_content_path(TYPE_SCRIPT).$file;				
				}
			}
		}
	}
	
	$files = scandir('../'.plaatsign_content_path(TYPE_IMAGE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {
		
			list($cid, $ext) = explode(".", $file);	
			
			if (in_array($ext, array("jpg","jpeg","png","gif"))) {

				$query = 'select enabled from content where cid='.$cid;
				$result = plaatsign_db_query($query);
				$data = plaatsign_db_fetch_object($result);
			
				if (isset($data->enabled) && ($data->enabled==1)) {
						
					if (strlen($image_files)>0) {					
						$image_files .= ' ';
					}
					$image_files .= '../'.plaatsign_content_path(TYPE_IMAGE).$file;
				}
			}
		}
	}
	
	if (strlen($image_files)>0) {
		
		# Image player
		$command = '/usr/bin/fbi -d /dev/fb0 -1 -t '.$delay.' -noverbose -a '.$image_files.' > /dev/null 2>&1' ;
	
		if (DEBUG==1) {
				
			echo $command."\n\r";
			
		} else {			
			exec($command);
			system('clear');
		}
	}	
	
	if (DEBUG==1) {
				
		echo "-------";
		sleep ( 5 );
	}
}

plaatsign_db_close();

unlink( LOCK_FILE ); 
exit(0); 

?>
