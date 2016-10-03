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
 
	# Video player
	exec('/usr/bin/omxplayer /var/www/html/plaatsign/uploads/videos/*.mp4');
		
	system('clear');
	
	# Image player
	exec('/usr/bin/fbi -d /dev/fb0 -t '.$delay.' -noverbose -a /var/www/html/plaatsign/uploads/images/* -1');	 	 
		
	system('clear');
}

unlink( LOCK_FILE ); 
exit(0); 

?>