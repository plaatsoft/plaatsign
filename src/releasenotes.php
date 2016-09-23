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

$releasenotes = ' 

<h2>23-09-2016 Version 0.1</h2>
<ul>
<li>Added basic content page with CRUD actions</li>
</ul>

<h2>22-09-2016 Version 0.02</h2>
<ul>
<li>Added basic login/logout functionality</li>
<li>Added basic user setting page with CRUD actions</li>
<li>Added basic help page</li>
<li>Added basic release notes page</li>
<li>Added basic credits page</li>
<li>Added basic donate page</li>
<li>Added basic about page</li>
</ul>

<h2>21-09-2016 Version 0.01</h2>
<ul>
<li>Start building.</li>
</ul>';
/*
** ------------------
** UI
** ------------------
*/

function plaatsign_releasenotes_form() {

	/* output */
	global $page;
	global $title;
	
	/* input */
	global $releasenotes;
	
	/* output */
	global $page;
	global $title;
	
	$title = t('RELEASENOTES_TITLE');
	
	$page .= '<div id="content">';
	
 	$page .= '<h1>'.$title.'</h1>';
			
	$page .= $releasenotes;
	
	$page .= '</div>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_releasenotes() {

	/* input */
	global $sid;
		
	switch ($sid) {

		case PAGE_RELEASE_NOTES: 
					plaatsign_releasenotes_form();
					break;
	}
}

/*
** ------------------
** The End
** ------------------
*/


