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

function plaatsign_credits_form() {

	/* output */
	global $page;
	global $title;
	
	$title = t('CREDITS_TITLE');
	
	$page .= '<div id="content">';
 	$page .= '<h1>'.$title.'</h1>';
	$page .= t('CREDITS_CONTENT');	
	$page .= '</div>';
	
	$page .= '<div id="column">';
   $page .= '<img class="imgr" src="images/info.svg" width="256" height="256" alt="" />';
	$page .= '</div>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_credits() {

	/* input */
	global $sid;
		
	switch ($sid) {

		case PAGE_CREDITS: 
					plaatsign_credits_form();
					break;
	}
}

/*
** ------------------
** The End
** ------------------
*/

