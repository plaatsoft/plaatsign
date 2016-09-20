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
$access = "";

$mid = 0;
$sid = 0;
$eid = 0;
$uid = 0;
$pid = 0;
$id = 0;

/* 
** ---------------------------------------------------------------- 
** POST parameters
** ----------------------------------------------------------------
*/	

$session = plaatsign_post("session", "");
$search = plaatsign_post("search", "");
$token = plaatsign_post("token", "");
$action = plaatsign_get("action", "");

if (strlen($token)>0) {
	
	/* Decode token */
	$token = gzinflate(base64_decode($token));	
	$tokens = @preg_split("/&/", $token);
	
	foreach ($tokens as $item) {
		$items = preg_split ("/=/", $item);				
		$($items[0]) = $items[1];	
	}
}

/*
** ---------------------------------------------------------------- 
** Database
** ---------------------------------------------------------------- 
*/

/* connect to database */
plaatsign_db_connect($config["dbhost"], $config["dbuser"], $config["dbpass"], $config["dbname"]);

/*
** ---------------------------------------------------------------- 
** Login check
** ---------------------------------------------------------------- 
*/

$user_id = plaatsign_db_session_valid($session);

if ( $user_id == 0 ) {

	/* Redirect to login page */
	$mid = MENU_LOGIN;
				
} else {

	$user = plaatsign_db_user($user_id);
	$data = plaatsign_db_project_user($user->project_id, $user_id);
	if (isset($data->role_id)) {
		$access = plaatsign_db_role($data->role_id);
	} else {
		$access = plaatsign_db_role(ROLE_GUEST);
	}
	
	if ($user->language==1) {
		include "nederlands.inc";
	}
}

/*
** ---------------------------------------------------------------- 
** State Machine
** ----------------------------------------------------------------
*/

/* Validated email address */
$tmp = preg_split('/-/', $action);

switch ($tmp[0]) {

	case EVENT_EMAIL_CONFIRM:

			$data = plaatsign_db_user($tmp[1]);
		
			if (isset($data->valid) && ($data->valid==0) && (md5($data->email)==$tmp[2])) {
			
				$data->valid=1;
				plaatsign_db_user_update($data);
				plaatsign_ui_box('info', t('USER_EMAIL_VALID1'));
			}
			break;
}
				
/* Global Event Handler */
switch ($eid) {

	case EVENT_SEARCH: 			
			if ((strlen($search)>0) && ($search!=t('HELP')) && (isset($user->project_id))) {
			
				if (strstr($search,"#")) { 
					$search = str_replace('#', '', $search); 
					$id = plaatsign_db_story_number_check($search, $user->project_id);
					if ($id>0) {
						$mid = MENU_BACKLOG;	
						$eid = EVENT_STORY_LOAD;
						$sid = PAGE_STORY;
					}
					
				} else {
						
					/* Clear sprint filter */
					$user->sprint_id = 0;
					plaatsign_db_user_update($user);
	
					$mid = MENU_BACKLOG;
					$sid = PAGE_BACKLOG_FORM;
				}
			}
			break;
}

/* Global Page Handler */
switch ($mid) {
	
	case MENU_LOGIN: 	
				include "login.php";
				include "home.php";
				plaatsign_login();
				break;
	
	case MENU_HOME:
				include "home.php";				
				plaatsign_home();
				break;
}

/* update member statistics */
if (isset($user->user_id)) {

	/* member_id = user_id */
	$member = plaatsign_db_member($user->user_id);
	
	$member->requests++;
	$member->last_activity = date("Y-m-d H:i:s", time());
	
	plaatsign_db_member_update($member);
}
		
/*
** ---------------------------------------------------------------- 
** Process cron jobs
** ----------------------------------------------------------------
*/

plaatsign_cron();

/*
** ---------------------------------------------------------------- 
** Create html response
** ----------------------------------------------------------------
*/

if ($eid!=EVENT_EXPORT) {

	echo plaatsign_ui_header($title);
	
	echo plaatsign_ui_banner(plaatsign_menu());
	
	echo '<div id="container">'.$page.'</div>';

	$time_end = microtime(true);
	$time = $time_end - $time_start;

	$time = round($time*1000);

	echo plaatsign_ui_footer($time, plaatsign_db_count() );
	
	plaatsign_debug('Page render time = '.$time.' ms.');
	plaatsign_debug('Amount of queries = '.plaatsign_db_count());
}

plaatsign_db_close();

/*
** ---------------------------------------------------------------- 
** THE END
** ----------------------------------------------------------------
*/

?>