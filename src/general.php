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
** ---------------------------------------------------------------- 
** DEFINES
** ----------------------------------------------------------------
*/

define('DEBUG', 0);

define('TYPE_MANUAL', 0);
define('TYPE_AUTOMATIC', 1);

define('LANGUAGE_ENGLISH', 0);
define('LANGUAGE_DUTCH', 1);

define('ROLE_USER', 0);
define('ROLE_ADMIN', 1);

define("MENU_LOGIN", 100);
define("MENU_CONTENT", 101);
define("MENU_SETTINGS", 102);
define("MENU_HELP", 103);
define("MENU_LOGOUT", 104);

define("PAGE_LOGIN", 200);
define("PAGE_REGISTER", 201);
define("PAGE_RECOVER", 202);
define("PAGE_CONTENTLIST", 203);
define("PAGE_CONTENT", 204);
define("PAGE_USERLIST", 205);
define("PAGE_USER", 206);
define("PAGE_RELEASE_NOTES", 207);
define("PAGE_CREDITS", 208);
define("PAGE_DONATE", 209);
define("PAGE_ABOUT", 210);
define("PAGE_SETTINGS", 211);
define("PAGE_HELP", 212);

define("EVENT_NONE", 300);
define("EVENT_LOGIN", 301);
define("EVENT_REGISTER", 302);
define("EVENT_RECOVER", 303);
define("EVENT_LOGOUT", 304);
define("EVENT_SAVE", 305);
define("EVENT_DELETE", 306);
define("EVENT_CANCEL", 307);
define("EVENT_ADD", 308);

/*
** ---------------------------------------------------------------- 
** PASSWORD
** ---------------------------------------------------------------- 
*/

function plaatsign_password_hash($raw) {

	$options = [
		'cost' => 12,
	];
	return password_hash($raw, PASSWORD_BCRYPT, $options);
}

function plaatsign_password_verify( $password, $hash) {

	return password_verify ( $password, $hash );
}

/*
** ---------------------------------------------------------------- 
** TRANSLATE
** ---------------------------------------------------------------- 
*/

/**
 * Translate text label (multi language support)
 */
function t() {

	global $lang;
	
   $numArgs = func_num_args();

   $temp = $lang[func_get_arg(0)];

   $pos = 0;
   $i = 1;

   while (($pos = strpos($temp, "%s", $pos)) !== false) {
      if ($i >= $numArgs) {
         throw new InvalidArgumentException("Not enough arguments passed.");
		}

      $temp = substr($temp, 0, $pos) . func_get_arg($i) . substr($temp, $pos + 2);
      $pos += strlen(func_get_arg($i));
      $i++;
   }      
	
	$temp = mb_convert_encoding($temp, "UTF-8", "HTML-ENTITIES" ); 
   return $temp; 
}

/*
** ---------------------------------------------------------------- 
** TRACING
** ----------------------------------------------------------------
*/

function udate($format, $utimestamp = null) {
	if (is_null($utimestamp)) {
		$utimestamp = microtime(true);
	}

	$timestamp = floor($utimestamp);
	$milliseconds = round(($utimestamp - $timestamp) * 1000000);

	return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}

function plaatsign_write_file($type, $text) {

	/* input */
	global $player;
	global $other;
	
	$message = udate('d-m-Y H:i:s:u').' ['.$_SERVER["REMOTE_ADDR"];
	
	if (isset($player)) {
		$message .= '|'.$player->pid;
	}
	
	if (isset($other)) {
		$message .= '|'.$other->pid;
	}
	
	$message .= '] '.$type.' '.$text."\r\n";
	$message = str_replace('<br/>', " ", $message); 
	
	$myFile = 'log/plaatsign-'.date('Ymd').'.log';
	$fp = fopen($myFile, 'a');	
	fwrite($fp, $message);
	fclose($fp);		
}

function plaatsign_info($text) {

	plaatsign_write_file('INFO', $text);
}

function plaatsign_error($text) {
	
	plaatsign_write_file('ERROR', $text);	
}

function plaatsign_debug($text) {
	
	if (DEBUG == 1 ) {
		
		plaatsign_write_file('DEBUG', $text); 
	}
}

/*
** ---------------------------------------------------------------- 
** LINKS
** ----------------------------------------------------------------
*/

function plaatsign_button($parameters, $label, $id="") {

	$link  = '<button name="token" value="'.plaatsign_token($parameters).'" class="button" ';
	if (strlen($id)!=0) {
		$link .= ' id="'.strtolower($id).'"';
	}
	$link .= '>'.$label.'</button>';	

	return $link;
}
		
/**
 * Create hidden link 
 */
function plaatsign_link($parameters, $label, $id="") {
   		
	$link  = '<a href="javascript:link(\''.plaatsign_token($parameters).'\');" class="link" ';			
	if (strlen($id)!=0) {
		$link .= ' id="'.strtolower($id).'"';
	}
	$link .= '>'.$label.'</a>';	

	return $link;
}

/**
 * Create hidden link 
 */
function plaatsign_link_hidden($parameters, $label, $id="") {
   		
	$link  = '<a href="javascript:link(\''.plaatsign_token($parameters).'\');" class="hide_link" ';		
	if (strlen($id)!=0) {
		$link .= ' id="'.strtolower($id).'"';
	}
	$link .= '>'.$label.'</a>';
	
	return $link;
}

/**
 * Create hidden link with popup
 */ 
function plaatsign_link_confirm($parameters, $label, $question="") {
   			
	global $link_counter;	
	
	$link_counter++;
	
	$link  = '<a href="javascript:show_confirm(\''.$question.'\',\''.plaatsign_token($parameters).'\');" class="link" ';
	$link .= 'id="link-'.$link_counter.'">'.$label.'</a>';	
		
	return $link;
}

/** 
 * Zip and uuencode token.
 */
function plaatsign_token($token) {
   
	/* Encode token  */
	$token = base64_encode(gzdeflate($token));
	
	return $token;
}

function plaatsign_post_radio($label, $default) {
	
	$value = $default;
	
	if (isset($_POST[$label])) {
		$value =1;
	} 	
	return $value;
}

function plaatsign_post($label, $default) {
	
	$value = $default;
	
	if (isset($_POST[$label])) {
		$value = $_POST[$label];
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
	}
	
	return $value;
}

function plaatsign_get($label, $default) {
	
	$value = $default;
	
	if (isset($_GET[$label])) {
		$value = $_GET[$label];
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
	}
	
	return $value;
}


function plaatsign_multi_post($label, $default) {
	
	$value = $default;
	
	if (isset($_POST[$label])) {
	
		$value = "";
	
		for($i=0; $i<sizeof($_POST[$label]); $i++) {
		
			if (strlen($value)>0) {
				$value .= ",";
			}
			$value .= $_POST[$label][$i];
		}
	}
	
	return $value;
}

function plaatsign_link_store($mid, $sid) {

	/* input */
	global $user;
	
	if (($user->menu_id!=$mid) || ($user->page_id!=$sid)) {
	
		$user->menu_id=$mid;
		$user->page_id=$sid;
	
		plaatsign_db_user_update($user);
		
	}
}

/*
** ---------------------
** UI
** ---------------------
*/

function plaatsign_ui_input($name, $size, $maxlength, $value, $readonly=false) {
	
	$page  = '<input ';
	$page .= 'type="text" ';
	$page .= 'id="'.$name.'" ';
	$page .= 'name="'.$name.'" ';
	$page .= 'value="'.$value.'" ';
	$page .= 'size='.$size.' ';
	$page .= 'maxlength='.$maxlength.' ';
	
	if ($readonly==true) {
		$page .= 'disabled="true" ';
	}
	
	$page .= '/>';

	return $page;
}

function plaatsign_ui_input_hidden($name, $value) {
	
	$page  = '<input ';
	$page .= 'type="hidden" ';
	$page .= 'id="'.$name.'" ';
	$page .= 'name="'.$name.'" ';
	$page .= 'value="'.$value.'" ';
		
	$page .= '/>';

	return $page;
}

function plaatsign_ui_datepicker($name, $size, $maxlength, $value, $readonly=false) {

	$page  = '<script language="JavaScript" type="text/javascript">';
	$page .= '$(function() {';
	$page .= '	$( "#'.$name.'" ).datepicker({ dateFormat: "dd-mm-yy", showWeek: true, firstDay: 1});';
	$page .= '});';
	$page .= '</script>';
		
	$page .= '<input ';
	$page .= 'type="text" ';
	$page .= 'id="'.$name.'" ';
	$page .= 'name="'.$name.'" ';
	$page .= 'value="'.$value.'" ';
	$page .= 'size="'.$size.'" ';
	$page .= 'maxlength="'.$maxlength.'" ';
	
	if ($readonly==true) {
		$page .= 'disabled="true" ';
	}
	
	$page .= '/>';
	
	return $page;	
}

function plaatsign_ui_textarea($name, $rows, $cols, $value, $readonly=false) {
	
	$page ='<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'" ';
	if ($readonly) {
		$page .= 'disabled="true" ';
	}
	$page .= '>'; 
	
	$page.= $value;		
	$page.='</textarea>';
	  
   return $page;
}

function plaatsign_ui_language($tag, $id, $readonly=false) {
			
	$values = array(LANGUAGE_ENGLISH, LANGUAGE_DUTCH);	

	$page ='<select id="'.$tag.'" name="'.$tag.'" ';
	
	if ($readonly) {
		$page .= 'disabled="true" ';
	}
	$page .= '>'; 
	
	foreach ($values as $value) {
	
		$page.='<option value="'.$value.'"';
		
		if ($id == $value) {
			$page .= ' selected="selected"';
		}
		$page .= '>'.t('LANGUAGE_'.$value).'</option>';
	}
		
	$page.='</select>';
		
   return $page;
}

function plaatsign_ui_role($tag, $id, $readonly=false) {
			
	$values = array(ROLE_USER, ROLE_ADMIN);	

	$page ='<select id="'.$tag.'" name="'.$tag.'" ';
	
	if ($readonly) {
		$page .= 'disabled="true" ';
	}
	$page .= '>'; 
	
	foreach ($values as $value) {
	
		$page.='<option value="'.$value.'"';
		
		if ($id == $value) {
			$page .= ' selected="selected"';
		}
		$page .= '>'.t('ROLE_'.$value).'</option>';
	}
		
	$page.='</select>';
		
   return $page;
}

function plaatsign_ui_checkbox($name, $value, $readonly=false) {

	$tmp = '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="1" ';
	
	if ($value==1) {
		$tmp .= ' checked="checked"';
	} 
	
	if ($readonly) {
		$tmp .= ' disabled="true"';
	} 
	
	$tmp .= '/>';
	
	return $tmp;	
}

function plaatsign_ui_file($name, $value, $readonly=false) {

	$tmp = '<input type="file" name="'.$name.'" id="'.$name.'" value="'.$value.'" ';
	
	if ($readonly) {
		$tmp .= ' disabled="true"';
	} 
	
	$tmp .= '/>';
	
	return $tmp;	
}

function plaatsign_ui_radiobox($name, $value, $readonly=false) {

	$tmp = '<input type="radio" name="'.$name.'" value="1" ';
	
	if ($value==1) {
		$tmp .= ' checked="checked"';
	} 
	
	if ($readonly) {
		$tmp .= ' disabled="true"';
	} 
	
	$tmp .= '/>';
	
	return $tmp;	
}
	
function plaatsign_ui_box($title, $message) {

	/* output */
	global $page;
	
	$page .= '<div id="box">';
	
	if ($title=="info") {

		$page .= '<b>'.t('GENERAL_INFO').'</b>: ';
		
	} else if ($title=="warning") {

		$page .= '<span class="warning"><b>'.t('GENERAL_WARNING').'</b></span>: ';
	
	} else if ($title=="error") {
 
		$page .= '<b>'.t('GENERAL_ERROR').'</b>: ';
				
	} else { 
	
		$page .= '<b>'.$title.'</b> ';
	} 
	
	$page .= $message;

	$page .= '</div>';		
}
	
function plaatsign_ui_image($filename, $options="") {

	/*  input */
	global $config;
	
	$image = '<img '.$options.' src="'.$config["content_url"].'images/'.$filename.'" />';
	return $image;
}

function plaatsign_ui_header( $title = "") {
   
	/* input */
	global $mid;
   global $sid;
	global $config;
	global $player;
	global $session;
	
	$page  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$page .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">';
	$page .= '<head profile="http://gmpg.org/xfn/11">';

	$page .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
 
 	if ($mid==MENU_LOGIN) {
		
		$page .= '<meta name="keywords" content="plaatsign,plaatsoft,sign" />';
		$page .= '<link rel="canonical" href="http://sign.plaatsoft.nl" />';
		
		$page .= '<meta name="application-name" content="plaatsign" />';
		$page .= '<meta name="description" content="plaatsign is a scrum development tool" />';
		$page .= '<meta name="application-url" content="http://scrum.plaatsoft.nl" />';		
	}
	
	$page .= '<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />'; 
	$page .= '<link href="css/general.css" rel="stylesheet" type="text/css" />';
	$page .= '<link href="css/jquery.css" rel="stylesheet" type="text/css" />';
						
	/* Add JavaScripts */
	$page .= '<script language="JavaScript" src="js/link.js" type="text/javascript"></script>';
	$page .= '<script language="JavaScript" src="js/jquery.js" type="text/javascript"></script>';
	$page .= '<script language="JavaScript" src="js/jquery-ui.js" type="text/javascript"></script>';	
	$page .= '<script language="JavaScript" src="js/jquery-multi.js" type="text/javascript"></script>';
	
	/* Add HTML Title */
	if ($title=="") {
		$page .= '<title>PlaatSign</title>';
	} else {
		$page .= '<title>PlaatSign - '.strtolower($title).'</title>';
	}
	$page .= "</head>";

	$page .= '<body id="top">';
	
	$page .= '<form id="plaatsign" ';
	if ($sid==PAGE_CONTENT) {
   		$page .= 'enctype="multipart/form-data" ';
	}
	$page .= 'method="POST">';
	
	/* Store session information for next request */	
	$page .= '<input type="hidden" name="session" value="'.$session.'" />';
	
	return $page;
}

function plaatsign_ui_banner($menu) {
	
	/* input */
	global $mid;
	global $sid;
	
	global $user;
	global $config;
	global $access;
	
	$page = '<div class="wrapper">';
	
	$page .= '<div id="header">';
   
	$page .= '<div class="fl_left">';
	
   $page .= '<h1>';
	if ($mid==MENU_LOGIN) { 
		$page .= plaatsign_link('mid='.MENU_LOGIN.'&sid='.PAGE_LOGIN, 'PlaatSign' );
	} else {	
		$page .= plaatsign_link('mid='.MENU_CONTENT.'&sid='.PAGE_CONTENTLIST,'PlaatSign' );
	}
	$page .= '</h1>';
	
	$data = plaatsign_db_config("database_version");	
	if (isset($data->id)) {
		$page .= t('GENERAL_VERSION').' '.$data->value;
	}
	$page .= '</div>';

	$page .= '<div class="fl_right">';
	$page .= $menu;
	$page .= '</div>';	
	$page .= '<br class="clear" />';
   $page .= '</div>';
	$page .= '<p style="float:right">';
	if (isset($user->uid)) {
			
		$page .= $user->name.' ';
	
		$page .= ' [';
		$page .= t('ROLE_'.$user->role);
		$page .= ']';
	}
	$page .= '</p>';
	
	$page .= '<div id="topbar">';
   $page .= '<div class="fl_left">';
		
	$page .= '</div>';
	$page .= '</div>';
	
	return $page;
}

function plaatsign_ui_footer($renderTime, $queryCount) {

	global $config;
	global $player;
	global $mid;
			
	$page = '<br class="clear" />';
				
	$page .= '<div id="copyright">';
	
	$page .= '<p class="fl_left">';
	$page .= t('COPYRIGHT');
	$page .= '</p>';
	
	$page .= '<p class="fl_right">';
	$page .= 'Render time '.$renderTime.'ms - '.$queryCount.' Queries - '.memory_format(memory_get_peak_usage(true)).'';
	$page .= '</p>';
	$page .= '</div>';
	
	$page .= '<br class="clear" />';
	
	$page .= '</div>';
	
	$page .= '<br/>';
	$page .= '<br/>';
			
	$page .= '</form>';
	$page .= "</body>";
	$page .= "</html>";
	
	return $page;
}

/*
** ---------------------
** CONVERTS
** ---------------------
*/

function convert_date_mysql($date) {
	$part = preg_split('/-/', $date);
	return $part[2].'-'.$part[1].'-'.$part[0];
}

function convert_date_php($date) {
	return date("d-m-Y", strtotime($date));
}

function convert_datetime_php($date) {
	return date("d-m-Y H:i:s", strtotime($date));
}

function convert_number($value) {
   
	return number_format($value,0,",",".");
}

/*
** ---------------------
** FORMATTERS
** ---------------------
*/

function memory_format($size) {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

/*
** ---------------------
** VALIDATION
** ---------------------
*/

/**
 * Function valid email address
 * @return true or false
 */
function validate_email($address) {

   return !preg_match("/[A-Za-z0-9_-]+([\.]{1}[A-Za-z0-9_-]+)*@[A-Za-z0-9-]+([\.]{1}[A-Za-z0-9-]+)+/",$address);
}

/** 
 * @mainpage plaatsign Documentation
 *   Welcome to the plaatsign documentation.
 *
 * @section Introduction
 *   plaatsign is a digital content viewer
 *
 * @section Links
 *   Website: http://www.plaatsoft.nl\n
 *   Code: https://github.com/wplaat/plaatsign\n
 *
 * @section Credits
 *   Documentation: wplaat\n
 *
 * @section Licence
 *   <b>Copyright (c) 2008-2016 Plaatsoft</b>
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *   
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *   
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
/*
** ---------------------------------------------------------------- 
** THE END
** ----------------------------------------------------------------
*/

?>