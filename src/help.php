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

	$page .= '<h2>'.t('HELP_SUBTITLE').'</h2>';
	$page .= '<p>';		
	$page .= t('HELP_CONTENT');
	$page .= '</p>';
	
	$page .= ' 
	
	<h2>To Do List</h2>
<ul>
<li>Automatic database creation</li>
<li>Setting page with rotation delay</li>
<li>Enable / Disable content</li>
<li>Role base access to prevent user accounts actions</li>
<li>Show log files</li>
<li>Automatic logout after 10 minutes idle</li>
<li>Email validation</li>
<li>Password strength check</li>
<li>Double filename check</li>
<li>Create start script for phote slide show</li>
<li>Publish project on plaatsoft.nl</li>
<li>Set timezone</li>
</ul>

<h2>Nice to Have</h2>
<ul>
<li>Add feature to resort content</li>
<li>Add feature to resize picture size</li>
</ul>';


	
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

