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


/*
** ------------------
** UI
** ------------------
*/

function plaatsign_manual_form() {

	/* output */
	global $page;
	global $title;
	
	$title = strtoupper(t('LINK_HELP')).' - '.t('MANUAL_TITLE');
	
	$page .= '<div id="content">';	
 	$page .= '<h1>'.$title.'</h1>';

	$page .= '<p>';		
	$page .= t('MANUAL_CONTENT');
	$page .= '</p>';
	$page .= '</div>';
	
	$page .= '<div id="column">';
   $page .= '<img class="imgr" src="images/help.svg" width="256" height="256" alt="" />';
	$page .= '</div>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_manual() {

	/* input */
	global $sid;
		
	switch ($sid) {

		case PAGE_MANUAL: 
					plaatsign_manual_form();
					break;
	}
}

/*
** ------------------
** The End
** ------------------
*/

