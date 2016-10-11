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

	$data = '';
	$max = 0;
	
	$files = scandir(plaatsign_content_path(TYPE_MOVIE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {			
			if (strlen($data)>0) {					
				$data .= ',';
			}
			$max++;
			$data .= '"'.plaatsign_content_path(TYPE_MOVIE).$file.'"';
		}
	};
		
	$files = scandir(plaatsign_content_path(TYPE_IMAGE));
	foreach ($files as $file) {
		if (($file!='.') && ($file!='..') && ($file!='index.php')) {
			if (strlen($data)>0) {					
				$data .= ',';
			}
			$max++;
			$data .= '"'.plaatsign_content_path(TYPE_IMAGE).$file.'"';
		}
	};
		
	$page .= '<script>';		
	$page .= 'var id = 0; ';
	$page .= 'var files = ['.$data.'];';
	
	$page .= 'function select_image(filename) { ';
	$page .= '   console.log("image filename="+filename);';
	$page .= '	 var image = document.getElementById("image0");';	
	$page .= '   image.style.display = "block"; ';
	$page .= '	 var video = document.getElementById("video0");';	
	$page .= '   video.style.display = "none"; ';
	$page .= '   document.getElementById("image0").src = filename; ';	
	$page .= '} ';	
	
	$page .= 'function select_video(filename) { ';
	$page .= '   console.log("video filename="+filename);';
	$page .= '	 var image = document.getElementById("image0");';	
	$page .= '   image.style.display = "none"; ';
	$page .= '	 var video = document.getElementById("video0");';	
	$page .= '   video.style.display = "block"; ';
	$page .= '	 video.src = filename;';
	$page .= '   video.play();';
	$page .= '   video.addEventListener("ended", slideshow, false); ';	
	$page .= '} ';	

	$page .= 'function slideshow() { ';
	$page .= '   function f() {';
	$page .= '	    filename = files[id];';
	$page .= '      var part  = filename.split("."); ';
	$page .= '      if (part[1]=="mp4") { ';
	$page .= '          select_video(filename); ';
	$page .= '      } else { ';	
	$page .= '          select_image(filename); ';
	$page .= '          setTimeout(f, '.(plaatsign_db_config_get("slide_show_delay")*1000).'); ';
	$page .= '      } ';
	$page .= '      if (id<(files.length-1)) {';
	$page .= '         id=id+1; ';
   $page .= '      } else { ';
	$page .= '         id = 0 ;';
   $page .= '      } '; 
	$page .= '   } ';
	$page .= '  f(); ';
	$page .= '}';
	$page .= '</script>';
	
	$page .= '<br/>';

	$page .= '<video width="960" height="540" id="video0" style="display:none;border:1px;border-style:solid;border-color:lightgray" >';
	$page .= '<source src="" type="video/mp4"  />';
	$page .= 'Browser do not support MP4 play back';
	$page .= '</video>';	

	$page .= '<img width="960" height="540" id="image0" src="" style="display:none;border:1px;border-style:solid;border-color:lightgray" alt="" />';	

	$page .= '<script>slideshow()</script>';	
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
