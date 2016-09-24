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

function plaatsign_donate_form() {

	/* output */
	global $page;
	global $title;
	
	$title = t('DONATE_TITLE');
	
	$page .= '<div id="content">';
	
 	$page .= '<h1>'.$title.'</h1>';
	
	$page .= '<h2>'.t('DONATE_SUBTITLE1').'</h2>';
	$page .= '<p>';
	$page .= t('DONATE_CONTENT1');
	$page .= '</p>';
	
	$page .= '<h2>'.t('DONATE_SUBTITLE2').'</h2>';
	$page .= '<p>';
	$page .= t('DONATE_CONTENT2');
	$page .= '</p>';
	
	$page .=	'</form>';
		
	$page .=	'<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
	   
	$page .=	'<input type="hidden" name="cmd" value="_s-xclick">';
	$page .=	'<input type="hidden" name="item_name" value="PlaatSign">';
	$page .=	'<input type="hidden" name="hosted_button_id" value="HYE3BQFZPBDFJ">';
	$page .=	'<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="">';
	$page .=	'<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">';
	$page .=	'</form>';
				
	$page .= '<br/>';
				
	$page .= '</div>';

	$page .= '<div id="column">';
   $page .= '<img class="imgr" src="images/donate.png" width="256" height="256" alt="" />';
	$page .= '</div>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_donate() {

	/* input */
	global $sid;
		
	switch ($sid) {

		case PAGE_DONATE: 
					plaatsign_donate_form();
					break;
	}
}

/*
** ------------------
** The End
** ------------------
*/

