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

while (TRUE) {			

	plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);
	$delay = plaatsign_db_config_get("slide_show_delay");
 
    system('setterm -cursor off');
	system('clear');

	
    $files = scandir('../'.plaatsign_content_path(TYPE_MOVIE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {			

			$data = '../'.plaatsign_content_path(TYPE_MOVIE).$file;
	
			# Video player	
			$command = '/usr/bin/omxplayer '.$data;	
			
			exec($command);		
        		system('setterm -cursor off');
			system('clear');
		}
	};
	
	$data = "";
	$files = scandir('../'.plaatsign_content_path(TYPE_IMAGE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {
			if (strlen($data)>0) {					
				$data .= ' ';
			}
			$data .= '../'.plaatsign_content_path(TYPE_IMAGE).$file;
		}
	};
	
	# Image player
	$command = '/usr/bin/fbi -d /dev/fb0 -1 -t '.$delay.' -noverbose -a '.$data.' > /dev/null 2>&1' ;
	
	exec($command);
	system('clear');
}

unlink( LOCK_FILE ); 
exit(0); 

?>
