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

function plaatsign_home_form() {

	/* output */
	global $page;
	global $title;
	
	$title = strtoupper(t('HOME_TITLE'));

 	$page .= '<h1>'.$title.'</h1>';
	$page .= t('HOME_CONTENT');	

	$id = 0;
	$tmp = '';
	$filename = '';
	$first = true;
	$files = scandir('uploads/images');
	$max = 0;
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..')) {
			if (strlen($tmp)>0) {	
				$max++;
				$tmp .= ',';
			}
			if ($first==true) {
				$filename = 'uploads/images/'.$file;
				$first = false;
			}
			$tmp .= '"uploads/images/'.$file.'"';
		}
	};
				
	$page .= '<script>';		
	$page .= 'var files = ['.$tmp.'];';
	$page .= 'var id = '.$id.';';
	$page .= 'var max = '.$max.';';
	$page .= 'var step = 1;';
	$page .= 'window.setInterval(function() { if (id<max) id+=step; else id=0; document.getElementById("webcam").src = files[id] }, 1000);';
	$page .= '</script>';

	$page .= '<br/>';
	$page .= '<br/>';
	$page .= '<img class="image" id="webcam" src="'.$filename.'" alt=""  height="540" width="960" />';			
	$page .= '<br/>';
	$page .= '<br/>';

}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_home() {

	/* input */
	global $sid;
		
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