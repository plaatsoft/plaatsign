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

$lang['RELEASENOTES_CONTENT']  = '

<h2>29-09-2016 Version 0.3</h2>
<ul>
<li>The following improvements were made after the first demo to the "end user":</li>
<li>Added script content for dynamic content support.</li>
<li>Added movie content for stream video/audio support.</li>
<li>Improve file upload. File is now directly uploaded after selection.</li>
<li>Improve automatic database patching algoritm.</li>
<li>Remove jquery libraries. Speedup page loading.</li>
<li>Added fatal warning when config.php is not found!</li>
<li>Bug fix: File size detection is now working correctly.</li>
<li>Bug fix: Remove some typos in the text.</li>
<li>Bug fix: Filename with uppercase extension is now correctly processed.</li>
</ul>

<h2>25-09-2016 Version 0.2</h2>
<ul>
<li>Added automatic content feature (automatic uploaded by external resource).</li>
<li>Upload the same content (filename) twice is now prohibit.</li>
<li>Timezone can now be configured on setting page. Default timezone is Europe/Amsterdam</li>
<li>Improve password hash algoritme. PlaatSign is now using the lastest and most secure algoritm.</li>
<li>User is automatic logout after 10 minutes idleness.</li>
<li>Added automatic database creation and patching.</li>
</ul>

<h2>24-09-2016 Version 0.1</h2>
<ul>
<li>Added basic content page with CRUD actions</li>
<li>Added role base access</li>
<li>Added basic settings page</li>
<li>Added basic login/logout functionality</li>
<li>Added basic user setting page with CRUD actions</li>
<li>Added basic help page</li>
<li>Added basic release notes page</li>
<li>Added basic credits page</li>
<li>Added basic donate page</li>
<li>Added basic about page</li>
<li>Added basic unix photo slide script for Raspberry Pi</li>
</ul>';

function plaatsign_releasenotes_form() {

	/* output */
	global $page;
	global $title;
	
	/* input */
	global $releasenotes;
	
	/* output */
	global $page;
	global $title;
	
	$title = strtoupper(t('LINK_HELP')).' - '.t('RELEASENOTES_TITLE');
	
	$page .= '<div id="content">';	
 	$page .= '<h1>'.$title.'</h1>';			
	$page .= t('RELEASENOTES_CONTENT');
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


