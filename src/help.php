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

function plaatsign_help_form() {

	/* output */
	global $page;
	global $title;
	
	$title = t('HELP_TITLE');
	
	$page .= '<div id="content">';	
 	$page .= '<h1>'.$title.'</h1>';

	$page .= '<p>';		
	$page .= t('HELP_CONTENT');
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

function plaatsign_help() {

	/* input */
	global $sid;
		
	switch ($sid) {

		case PAGE_HELP: 
					plaatsign_help_form();
					break;
	}
}

/*
** ------------------
** The End
** ------------------
*/

