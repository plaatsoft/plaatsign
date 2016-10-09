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

	$tmp1 = '';
	$filename1 = '';
	$first = true;
	$files = scandir(plaatsign_content_path(TYPE_IMAGE));
	$max1 = 0;
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {
			if (strlen($tmp1)>0) {					
				$tmp1 .= ',';
			}
			if ($first==true) {
				$filename1 = plaatsign_content_path(TYPE_IMAGE).$file;
				$first = false;
			}
			$max1++;
			$tmp1 .= '"'.plaatsign_content_path(TYPE_IMAGE).$file.'"';
		}
	};
	
	$tmp2 = '';
	$max2 = 0;
	$filename2 = '';
	$first = true;
	$files = scandir(plaatsign_content_path(TYPE_MOVIE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {			
			if (strlen($tmp2)>0) {					
				$tmp2 .= ',';
			}
			if ($first==true) {
				$filename2 = plaatsign_content_path(TYPE_MOVIE).$file;
				$first = false;
			}
			$max2++;
			$tmp2 .= '"'.plaatsign_content_path(TYPE_MOVIE).$file.'"';
		}
	};
	
	$delay = plaatsign_db_config_get("slide_show_delay");
	
	$page .= '<script>';		
	$page .= 'var image_files = ['.$tmp1.']; ';
	$page .= 'var video_files = ['.$tmp2.']; ';
	$page .= 'var id = 0; ';
	$page .= 'var max1 = '.$max1.'; ';
	$page .= 'var max2 = '.$max2.'; ';
	$page .= 'var type = 1;';
	$page .= 'window.setInterval(function() {';
	$page .= 'if (type<1) {';
	$page .= '  if (id<(max1-1)) {';
	$page .= '    id=id+1; ';
   $page .= '  } else { ';
	$page .= '    id=0; ';
	$page .= '    if (max2>0) { ';
	$page .= '      type=1; ';
	$page .= '      document.getElementById("image0").style.display = "none"; ';
	$page .= '      document.getElementById("video0").style.display = "initial"; ';
	$page .= '    } ';
	$page .= '  } ';	
	$page .= '  document.getElementById("image0").src = image_files[id]; ';	
	$page .= '} else { ';	
	$page .= '  if (id<(max2-1)) { ';
	$page .= '    id=id+1; ';
   $page .= '  } else { ';
	$page .= '    id=0; ';
	$page .= '    type=0; ';
	$page .= '    document.getElementById("image0").style.display = "initial"; ';
	$page .= '    document.getElementById("video0").style.display = "none"; ';
	$page .= '  }';	
	$page .= '  document.getElementById("video0").src = video_files[id];';
	$page .= '  document.getElementById("video0").load();';
	$page .= '  document.getElementById("video0").play();';
	$page .= '}}, '.($delay*1000).');';
	$page .= '</script>';

	$page .= '<br/>';
	$page .= '<br/>';
	$page .= '<img width="960" height="540" id="image0" style="display:none;border:1px;border-style: solid; border-color: lightgray;" src="'.$filename1.'" alt="" />';	
	$page .= '<video width="960" height="540" id="video0" autoplay loop>';
	$page .= '<source src="'.$filename2.'" type="video/mp4" >';
	$page .= '</video>';	
	
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