<?php

/* 
**  ==========
**  PlaatSign
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

$lang = array();

$time_start = microtime(true);

if (!file_exists( "config.php" )) {
    echo "PlaatSign config.php file not found!";
	 exit;
}

include "config.php";
include "database.php";
include "general.php";
include "menu.php";
include "english.php";

/*
** ---------------------------------------------------------------- 
** Global variables
** ---------------------------------------------------------------- 
*/

plaatsign_debug('-----------------');

$page = "";
$title = "";
$user = "";

$mid = MENU_LOGIN;    // Menu Id
$sid = PAGE_LOGIN;    // Page Id
$eid = 0;             // Event Id
$uid = 0;             // User Id
$tid = 0;             // Type Id

$id = 0;

/* 
** ---------------------------------------------------------------- 
** POST parameters
** ----------------------------------------------------------------
*/	

$session = plaatsign_post("session", "");
$token = plaatsign_post("token", "");
$action = plaatsign_get("action", "");

if (strlen($token)>0) {
	
	/* Decode token */
	$token = gzinflate(base64_decode($token));	
	$tokens = @preg_split("/&/", $token);
	
	foreach ($tokens as $item) {
		$items = preg_split ("/=/", $item);				
		${$items[0]} = $items[1];	
		
		if (DEBUG == 1) {
			echo $items[0].'='.$items[1].'<br>';
		}
	}
}

/*
** ---------------------------------------------------------------- 
** Database
** ---------------------------------------------------------------- 
*/

/* connect to database */
plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);

/* create / patch database if needed */
plaatsign_db_check_version();

/* Set default timezone */
date_default_timezone_set ( plaatsign_db_config_get("timezone" ) );

/*
** ---------------------------------------------------------------- 
** Login check
** ---------------------------------------------------------------- 
*/

$user_id = plaatsign_db_session_valid($session);

if ( $user_id == 0 ) {

	/* Redirect to login page */

	if ($sid!=PAGE_LOGIN) {
		$eid = EVENT_NONE;
	}

	$mid = MENU_LOGIN;
	$sid = PAGE_LOGIN;

} else {

	$user = plaatsign_db_user($user_id);
	
	if ($user->language=="nl") {
		include "nederlands.inc";
	}
}

/*
** ---------------------------------------------------------------- 
** State Machine
** ----------------------------------------------------------------
*/

/* Global Page Handler */
switch ($sid) {
	
	case PAGE_LOGIN: 	
				include "login.php";
				include "content.php";
				plaatsign_login();
				break;
	
	case PAGE_CONTENT:
	case PAGE_CONTENTLIST:
				include "content.php";				
				plaatsign_content();
				break;
				
	case PAGE_HELP:
				include "help.php";				
				plaatsign_help();
				break;
				
	case PAGE_ABOUT:
				include "about.php";				
				plaatsign_about();
				break;
				
	case PAGE_SETTINGS:
				include "settings.php";				
				plaatsign_settings();
				break;
				
	case PAGE_DONATE:
				include "donate.php";				
				plaatsign_donate();
				break;
				
	case PAGE_RELEASE_NOTES:
				include "releasenotes.php";				
				plaatsign_releasenotes();
				break;
				
	case PAGE_CREDITS:
				include "credits.php";				
				plaatsign_credits();
				break;
			
   case PAGE_USERLIST:
	case PAGE_USER:
				include "user.php";				
				plaatsign_user();
				break;
}

/* update member statistics */
if (isset($user->uid)) {

	$user->requests++;
	$user->last_activity = date("Y-m-d H:i:s", time());
	
	plaatsign_db_user_update($user);
}
		
/*
** ---------------------------------------------------------------- 
** Create html response
** ----------------------------------------------------------------
*/

echo plaatsign_ui_header($title);
	
echo plaatsign_ui_banner(plaatsign_menu());
	
echo '<div id="container">'.$page.'</div>';

$time_end = microtime(true);
$time = $time_end - $time_start;

$time = round($time*1000);

echo plaatsign_ui_footer($time, plaatsign_db_count() );

plaatsign_debug('Page render time = '.$time.' ms.');
plaatsign_debug('Amount of queries = '.plaatsign_db_count());

plaatsign_db_close();

/*
** ---------------------------------------------------------------- 
** THE END
** ----------------------------------------------------------------
*/

?>