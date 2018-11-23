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
**  All copyrights reserved (c) 1996-2018 PlaatSoft
*/

/*
** ------------------
** UI
** ------------------
*/

$lang['RELEASENOTES_CONTENT']  = '

<h2>22-11-2018 Version 1.1</h2>
<ul>
<li>Added parameter field to script content form.</li>
<li>The parameter will used during execution of script</li>
<li>Uploading script more the ones is now alowed again</li>
<li>Added KerkinGouda demo script</li>
<li>Added Family demo script</li>
<li>Added PlaatSoft demo script</li>
<li>Update copyright banners</li>
<li>More is coming soon</li>
</ul>

<h2>09-11-2018 Version 1.0</h2>
<ul>
<li>First launching customer "Oostpoort"</li>
<li>Added energy day consumption demo script</li>
<li>Added energy month consumption demo script</li>
<li>Added energy year consumption demo script</li>
<li>Added gouda weatherstation demo script</li>
<li>Added broodkruimels demo script</li>
<li>Improve file use on disk</li>
<li>Added feature to enable / disable content</li>
<li>Added plaatenergy database settings to settings page</li>
<li>Updated copyrights banner</li>
<li>Added new version check</li>
</ul>

<h2>22-10-2016 Version 0.5</h2>
<ul>
<li>PlaatSign can now display more then one video.</li>
<li>Improve video slide show preview.</li>
<li>Improve login page.</li>
<li>Slide show delay value is now validated.</li>
<li>Protect image upload directory against direct URL access.</li>
<li>Make HTML code compliant with Microsoft Explorer 10 and higher.</li>
</ul>

<h2>08-10-2016 Version 0.4</h2>
<ul>
<li>Added home page with slide show preview.</li>
<li>Image format is now based on HD dimensions.</li>
<li>Improve solar, news and weather script examples.</li>
<li>Added icons for apple, android and window devices.</li>
<li>Added refresh setting (in minute) for script content.</li>
<li>Cron job is now only starting script when refresh timer is expired.</li>
<li>Bugfix: Start script is now working after reboot.</li>
<li>Bugfix: Script output is now directlty showed after upload.</li>
</ul>

<h2>02-10-2016 Version 0.3</h2>
<ul>
<li>The following improvements were made after the first demo to the "end user":</li>
<li>Added script content for dynamic content support.</li>
<li>Added video content support.</li>
<li>Improve file upload. File is now directly uploaded after selection.</li>
<li>Improve automatic database patching algoritm.</li>
<li>Remove jquery libraries. Speedup page loading.</li>
<li>Added fatal warning when config.php is not found.</li>
<li>Added fatal warning when database connection fails.</li>
<li>Added example content scripts: Clock, News, Weather, etc..</li>
<li>Bug fix: Improve php cron job now no output is generated anymore.</li>
<li>Bug fix: File size detection is now working correctly.</li>
<li>Bug fix: Remove some typos in the text.</li>
<li>Bug fix: Filename with uppercase extension is now correctly processed.</li>
</ul>

<h2>25-09-2016 Version 0.2</h2>
<ul>
<li>Upload the same content (filename) twice is now prohibit.</li>
<li>Timezone can now be configured on setting page. Default timezone is Europe/Amsterdam</li>
<li>Improve password hash algoritm. PlaatSign is now using the lastest and most secure algoritm.</li>
<li>User is automatic logout after 10 minutes idleness.</li>
<li>Added automatic database creation and patching.</li>
</ul>

<h2>24-09-2016 Version 0.1</h2>
<ul>
<li>Added content page with CRUD actions</li>
<li>Added role base access</li>
<li>Added settings page</li>
<li>Added login/logout functionality</li>
<li>Added user setting page with CRUD actions</li>
<li>Added help page</li>
<li>Added release notes page</li>
<li>Added credits page</li>
<li>Added donate page</li>
<li>Added about page</li>
<li>Added unix photo slide script for Raspberry Pi</li>
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

?>