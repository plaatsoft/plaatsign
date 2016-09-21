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

/*
** ------------------
** POST PARAMETERS
** ------------------
*/

/*
** ------------------
** ACTIONS
** ------------------
*/


/*
** ------------------
** UI
** ------------------
*/


function plaatsign_home_form() {

	/* input */
	global $mid;
	global $sid;
	global $user;	
	global $access;	
	global $sort;

	/* output */
	global $page;
	global $title;
				
	$title = t('HOME_TITLE');	
			
	$page .= '<h1>';	
	$page .= $title;
	$page .= '</h1>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_home() {

	/* input */
	global $mid;
	global $sid;
						
	/* Page handler */
	switch ($sid) {
	
		case PAGE_HOME: 
					plaatsign_home_form();	
					break;
					
	}
}


/*
** ------------------
** The End
** ------------------
*/

