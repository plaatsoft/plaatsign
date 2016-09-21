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
** POST PARAMETERS
** ------------------
*/

$name = plaatsign_post("name", "");
$email = plaatsign_post("email", "");
$username = plaatsign_post("username", "");
$password  = plaatsign_post("password", "");

/*
** ------------------
** ACTIONS
** ------------------
*/

/**
 * Send recover email
 */
function plaatsign_recover_mail($to, $username, $password) {

	/* input */
	global $config;
	
	$subject = 'Password reset of '.$config["applName"];
	
	$body  = 'Your password is reset!'."\r\n\r\n";
	$body .= 'Your username = '.$username."\r\n";
	$body .= 'Your new password = '.$password."\r\n\r\n";
	$body .= 'Visit '.$config["base_url"].' and login to continue working with '.$config["applName"]."\r\n";
		
	$header = 'From: '.$config["applName"]. '<'.$config['from_email'].">\r\n";

	@mail($to, $subject, $body, $header);
	plaatsign_info("Send email [".$to."] Password reset");
}
		

function plaatsign_recover_do() {

	/* input */
	global $email;

	global $mid;
	global $sid;	
	
	if (validate_email($email)) {
		
		plaatsign_ui_box('warning', t('LOGIN_EMAIL_INVALID'));
	
	} else {
	
		$user_id = plaatsign_db_user_email($email);
	
		if ($user_id==0) {
		
			plaatsign_ui_box('warning', t('LOGIN_EMAIL_NOT_FOUND'));
					
		} else {
		
			/* Create random password */
			$password = plaatsign_randomPassword(10);
			
			$member = plaatsign_db_member($user_id);
			plaatsign_db_member_update2($member->username, $password, $user_id);	
						
			plaatsign_recover_mail($email, $member->username, $password);
						
			plaatsign_ui_box('info', t('LOGIN_RECOVER_OK'));
		}
	}
}

/**
 * Send registration welcome email
 */
function plaatsign_register_mail($to, $username, $id) {

	/* input */
	global $config;
	
	$subject = 'Welcome to '.$config["applName"];
	
	$body  = 'Welcome to '.$config["applName"]."\r\n\r\n";
	$body .= 'Thanks you for registrating!'."\r\n\r\n";
	$body .= 'Your username = '.$username."\r\n\r\n";

	$body .= 'Please confirm your email address by clicking the following link'."\r\n";
	
	$body .= $config["base_url"].'?action='.EVENT_EMAIL_CONFIRM.'&key='.$id.'-'.md5($to);
	
	$header = 'From: '.$config["applName"]. '<'.$config['from_email'].">\r\n";

	@mail($to, $subject, $body, $header);
	plaatsign_info("Send email [".$to."] Welcome message");
}

function plaatsign_register_do() {

	/* input */
   global $username;
	global $password;
	global $name;
	global $email;
		
	if (strlen($username)<5) {
		
		plaatsign_ui_box('warning', t('LOGIN_USERNAME_TO_SHORT'));
		
	} else if (plaatsign_db_member_username($username)>0)  {
	
		plaatsign_ui_box('warning', t('LOGIN_USERNAME_EXIST'));
		
	} else if (strlen($password)<5) {

		plaatsign_ui_box('warning', t('LOGIN_PASSWORD_TO_SHORT'));
	
	} else if (strlen($name)<3) {

		plaatsign_ui_box('warning', t('LOGIN_NAME_TO_SHORT'));
	
	} else if (validate_email($email)) {
		
		plaatsign_ui_box('warning', t('LOGIN_EMAIL_INVALID'));
	
	} else {
	
		plaatsign_ui_box('info', t('LOGIN_REGISTER_SUCCESFULL'));
	
		$member_id = plaatsign_db_member_insert($username, $password);
		plaatsign_db_user_insert($member_id, $name, $email, ROLE_GUEST);	
		
		plaatsign_register_mail($email, $name, $member_id);
		
		plaatsign_login_do();
	}
}

function plaatsign_login_do() {

	/* input */
   global $username;
	global $password;
	
	/* output */
	global $mid;
	global $sid;	
	global $user;
	global $access;
	global $access;
	global $session;
	global $page;
					
	$uid = plaatsign_db_user_id($username, $password);	
	
	if ($uid == 0) {
	
		plaatsign_ui_box('warning', t('LOGIN_FAILED'));
		plaatsign_info("Login [".$username."] failed!");
	
	} else { 
		
		$session = plaatsign_db_session_add($uid);
		
		$user = plaatsign_db_user($uid);
		$user->last_login = date("Y-m-d H:i:s", time());
		plaatsign_db_user_update($user);
				
		/* Redirect to home page. */
		$mid = MENU_HOME;			
		$sid = PAGE_HOME;	
		$page = "";
		
		plaatsign_info('Login '.$user->name.' ['.$user->uid.']');
	} 
}

function plaatsign_logout_do() {

	/* output */
	global $session;
	global $user;
	global $access;
	
	plaatsign_info('Logout '.$user->name.' ['.$user->user_id.']');
	
	plaatsign_ui_box('info', t('LOGIN_LOGOUT'));
	
	plaatsign_db_session_delete($session);
	
	/* Destroy user and access information */
	$user="";
	$access="";
}


/*
** ------------------
** UI
** ------------------
*/

function plaatsign_login_footer() {

	$page  = '<br class="clear" />';
		
	$page .= '<div id="footer">';
   	 
   $page .= '<br class="clear"/>';
	$page .= '</div>';
	
	return $page;
}

function plaatsign_recover_form() {

	/* input */
	global $mid;
	global $sid;
	
	global $name;
	global $username;
	global $email;
	
	/* output */
	global $page;
	
   $page .= '<div id="content">';
	
	$page .= '<h1>'.t('LOGIN_WELCOME_TITLE').'</h1>';
	$page .= '<img class="imgl" src="images/plaatsign-taskboard.png" alt="" />';
	$page .= t('LOGIN_WELCOME');
	$page .= '</div>'; 
		
	$page .= '<div id="column">';
   $page .= '<div class="subnav">';
	$page .= '<h2>'.t('LOGIN_RECOVER').'</h2>';
		
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_EMAIL').':</label>';
	$page .= plaatsign_ui_input("email", 50, 100, $email);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= plaatsign_button('mid='.$mid.'&sid='.$sid.'&eid='.EVENT_RECOVER, t('LINK_RECOVER'));
	$page .= '</p>';
			
	$page .= '</div>';
	$page .= '</div>';
	
	$page .= plaatsign_login_footer();
		  		
	return $page;	
}

function plaatsign_register_form() {

	/* input */
	global $mid;
	global $sid;
	
	global $name;
	global $username;
	global $password;
	global $email;
	
	/* output */
	global $page;
	
   $page .= '<div id="content">';
	
	$page .= '<h1>'.t('LOGIN_WELCOME_TITLE').'</h1>';
	$page .= '<img class="imgl" src="images/plaatsign-taskboard.png" alt="" />';
	$page .= t('LOGIN_WELCOME');
	
	$page .= '</div>'; 
		
	$page .= '<div id="column">';
   $page .= '<div class="subnav">';
	$page .= '<h2>'.t('LOGIN_REGISTER').'</h2>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_NAME').':</label>';
	$page .= plaatsign_ui_input("name", 50, 50, $name);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_USERNAME').':</label>';
	$page .= plaatsign_ui_input("username", 20, 15, $username);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_PASSWORD').':</label>';
	$page .= '<input type="password" name="password" id="password" size="20" maxlength="15" value="'.$password.'"/>';
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_EMAIL').':</label>';
	$page .= plaatsign_ui_input("email", 50, 100, $email);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= plaatsign_button('mid='.$mid.'&sid='.$sid.'&eid='.EVENT_REGISTER, t('LINK_REGISTER'));
	$page .= '</p>';
			
	$page .= '</div>';
	$page .= '</div>';
	
	$page .= plaatsign_login_footer();
		  		
	return $page;	
}

function plaatsign_login_form() {

	/* input */
	global $mid;
	
	/* output */
	global $page;
		
   $page .= '<div id="content">';
	
	$page .= '<h1>'.t('LOGIN_WELCOME_TITLE').'</h1>';
	$page .= '<img class="imgl" src="images/plaatsign-taskboard.png" alt="" />';
	$page .= t('LOGIN_WELCOME');
	
	$page .= '</div>'; 
		
	$page .= '<div id="column">';
   $page .= '<div class="subnav">';
   $page .= '<h2>'.t('LOGIN_TITLE').'</h2>';
		  	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_USERNAME').':</label>';
	$page .= '<input type="text" name="username" id="username" value=""/>';
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_PASSWORD').':</label>';
	$page .= '<input type="password" name="password" id="password" value=""/>';
	$page .= '</p>';
	
	$page .= plaatsign_button('mid='.$mid.'&eid='.EVENT_LOGIN, t('LINK_LOGIN'));
			
	$page .= '</div>';
						
	$page .= '</div>';	
	
	$page .= plaatsign_login_footer();
		
	return $page;	
}
    
/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_login() {

	/* input */
	global $eid;
	global $sid;
		
	/* Event handler */
	switch ($eid) {
	
		case EVENT_LOGIN: 	
					plaatsign_login_do();	
					break;
				  
		case EVENT_REGISTER: 	
					plaatsign_register_do();	
					break;
					
		case EVENT_RECOVER: 	
					plaatsign_recover_do();	
					break;
					
		case EVENT_LOGOUT: 	
					plaatsign_logout_do();
					break;
	}
	
	/* Page handler */
	switch ($sid) {
			
		default:
		case PAGE_LOGIN: 
					plaatsign_login_form();	
				   break;
			
		case PAGE_REGISTER:
					plaatsign_register_form();	
				   break;
					
		case PAGE_RECOVER:
					plaatsign_recover_form();	
				   break;
					
		case PAGE_HOME:
					plaatsign_home();
					break;
			
	}
}

/*
** ------------------
** The End
** ------------------
*/
