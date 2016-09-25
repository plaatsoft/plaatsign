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
** MENU
** ------------------
*/

function plaatsign_login_menu() {
	
	/* input */
	global $mid;
	global $sid;
					
	$menu = '<ul>';
	
	if (($sid==PAGE_LOGIN) || ($sid==0)) $menu .= '<li class="active">'; else $menu .= '<li>';
	$menu .= plaatsign_link('mid='.$mid.'&sid='.PAGE_LOGIN, t('LINK_LOGIN'));
	$menu .= '</li>';
	
	$menu .= '</ul>';
		
	return $menu;
}


function plaatsign_main_menu() {
	
	/* input */
	global $mid;
	global $sid;
	global $user;
	global $access;
				
	$menu = '<ul>';
	
	if ($mid==MENU_CONTENT) $menu .= '<li class="active">'; else $menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_CONTENT.'&sid='.PAGE_CONTENTLIST.'&tid='.TYPE_MANUAL, t('LINK_CONTENT'));
	
	$menu .= '<ul>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_SETTINGS.'&sid='.PAGE_CONTENTLIST.'&tid='.TYPE_MANUAL, t('LINK_MANUAL'));
	$menu .= '</li>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_SETTINGS.'&sid='.PAGE_CONTENTLIST.'&tid='.TYPE_AUTOMATIC, t('LINK_AUTOMATIC'));
	$menu .= '</li>';
							
	$menu .= '</ul>';
	
	/* -----------------*/
	
	if ($mid==MENU_SETTINGS) $menu .= '<li class="active">'; else $menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_SETTINGS.'&sid='.PAGE_SETTINGS, t('LINK_SETTINGS'));
	
	$menu .= '<ul>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_SETTINGS.'&sid='.PAGE_SETTINGS, t('LINK_GENERAL'));
	$menu .= '</li>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_SETTINGS.'&sid='.PAGE_USERLIST, t('LINK_USERS'));
	$menu .= '</li>';
							
	$menu .= '</ul>';
	
	/* -----------------*/
		
	if ($mid==MENU_HELP) $menu .= '<li class="active">'; else $menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_HELP.'&sid='.PAGE_HELP, t('LINK_HELP'));
	
	$menu .= '<ul>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_HELP.'&sid='.PAGE_RELEASE_NOTES, t('LINK_RELEASE_NOTES'));
	$menu .= '</li>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_HELP.'&sid='.PAGE_CREDITS, t('LINK_CREDITS'));
	$menu .= '</li>';
	
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_HELP.'&sid='.PAGE_DONATE, t('LINK_DONATE'));
	$menu .= '</li>';
		
	$menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_HELP.'&sid='.PAGE_ABOUT, t('LINK_ABOUT'));
	$menu .= '</li>';
	
	$menu .= '</ul>';

	$menu .= '</li>';

	/* -----------------*/
		
	if ($mid==MENU_LOGIN) $menu .= '<li class="active">'; else $menu .= '<li>';
	$menu .= plaatsign_link('mid='.MENU_LOGIN.'&eid='.EVENT_LOGOUT, t('LINK_LOGOUT'));
	$menu .= '</li>';
	
	$menu .= '</ul>';
		
	return $menu;
}

function plaatsign_menu() {

	/* input */
	global $mid;
	global $menu;
	
	$menu = '<div id="topnav">';
	
	switch ($mid) {
	
		case MENU_LOGIN:
			$menu .= plaatsign_login_menu();
			break;
					
		default: 
			$menu .= plaatsign_main_menu();
			break;
	}
	
	$menu .= '</div>';	
		
	return $menu;
}


?>