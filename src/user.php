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

$user_name = plaatsign_post("user_name", "");
$user_email = plaatsign_post("user_email", "");
$user_role = plaatsign_post("user_role", ROLE_USER);
$user_username = plaatsign_post("user_username", "");
$user_password  = plaatsign_post("user_password", "");

/*
** ------------------
** ACTIONS
** ------------------
*/

/**
 * Send recover email
 */
function plaatsign_email_change_email($to, $id) {

	/* input */
	global $config;
	
	$subject = 'Confirm email address for '.$config["applName"];
	
	$body  = 'Please confirm your email address for '.$config["applName"].' ';
	$body .= 'by clicking the following link'."\r\n\r\n";
	
	$body .= $config["base_url"].'?action='.EVENT_EMAIL_CONFIRM.'-'.$id.'-'.md5($to);
		
	$header = 'From: '.$config["applName"]. '<'.$config['from_email'].">\r\n";

	@mail($to, $subject, $body, $header);
	
	plaatsign_ui_box('info', t('USER_EMAIL_CONFIRM_SENT'));
	
	plaatsign_info("Send email [".$to."] email confirmation");
}

function plaatsign_user_email_confirm_do() {

	/* input */
	global $id;
	
	$data = plaatsign_db_user($id);
	
	plaatsign_email_change_email($data->email, $id);
}

function plaatsign_user_hack_do() {

	/* input */
	global $id;
	
	/* output */
	global $session;
	
	$data = plaatsign_db_user($id);
	
	$session = plaatsign_db_session_hack($id);
	
	$message = t('USER_HACK', $data->name, $id);
	plaatsign_ui_box('info', $message);
	
	plaatsign_info('info', $message);
}

function plaatsign_user_save_do() {
	
	/* input */
	global $id;
	global $user;
	
	global $user_name;
	global $user_email;
	global $user_username;
	global $user_password;
	global $user_role;
	
	/* output */
	global $sid;
		
	$member = plaatsign_db_member($id);
	
	if (strlen($user_name)<3) {

		plaatsign_ui_box('warning', t('LOGIN_NAME_TO_SHORT'));
		
	} else if (validate_email($user_email)) {
		
		plaatsign_ui_box('warning', t('LOGIN_EMAIL_INVALID'));
		
	} else if (strlen($user_username)<5) {
		
		plaatsign_ui_box('warning', t('LOGIN_USERNAME_TO_SHORT'));
		
	} else if (($id==0) && (plaatsign_db_member_username($user_username)>0)) {
	
		plaatsign_ui_box('warning', t('LOGIN_USERNAME_EXIST'));

	} else if (isset($member->username) && ($member->username!=$user_username) && (plaatsign_db_member_username($user_username)>0)) {
	
		plaatsign_ui_box('warning', t('LOGIN_USERNAME_EXIST'));
		
	} else if ((strlen($user_password)>0) && (strlen($user_password)<5)) {

		plaatsign_ui_box('warning', t('LOGIN_PASSWORD_TO_SHORT'));
		
	} else if (($id==0) && (strlen($user_password)<5)) {

		plaatsign_ui_box('warning', t('LOGIN_PASSWORD_TO_SHORT'));
				
	} else {
	
		if ($id>0) {
			
			/* Update member data */	
			plaatsign_db_member_update2($user_username, $user_password, $id);
			
			/* Update user data */
			$data = plaatsign_db_user($id);
			
			if ($data->email != $user_email) {
			
				/* Mail validation must be executed again */
				$data->valid=0;
				plaatsign_email_change_email($user_email, $id);
			}
			
			$data->email = $user_email;			
			$data->name = $user_name;
			$data->role_id = $user_role;
			
			plaatsign_db_user_update($data);			
		
		} else  {
			
			/* Insert new member */
			$member_id = plaatsign_db_member_insert($user_username, $user_password);
			
			/* Insert new user */
			plaatsign_db_user_insert($member_id, $user_name, $user_email, $user_role);		
		}
		
		plaatsign_ui_box('info', t('USER_SAVED'));
		plaatsign_info($user->name.' ['.$user->user_id.'] save user settings ['.$id.']');
		
		/* Data ok, goto to previous form */		
		if ($user->role_id==ROLE_ADMINISTRATOR) {
			$sid = PAGE_USERLIST;
		} else {
			$sid = PAGE_GENERAL;
		}	
	} 	
}

function plaatsign_user_cancel_do() {

	/* input */
	global $user;
	
	/* output */
	global $sid;
	
	/* Goto to previous form */		
	if ($user->role_id==ROLE_ADMINISTRATOR) {
	
		$sid = PAGE_USERLIST;
		
	} else {
	
		$sid = PAGE_GENERAL;
	}	
}


function plaatsign_user_delete_do() {
	
	/* input */
	global $id;
	global $user;
	
	/* output */	
	global $sid;
		
	$data = plaatsign_db_member($id);
	
	if (isset($data->user_id)) {

		$data->deleted=1;
		plaatsign_db_member_update($data);

		plaatsign_ui_box('info', t('USER_DELETED'));
		plaatsign_info($user->name.' ['.$user->user_id.'] delete user ['.$id.']');
		
		/* Goto to previous form */		
		if ($user->role_id==ROLE_ADMINISTRATOR) {
	
			$sid = PAGE_USERLIST;
		
		} else {
	
			$sid = PAGE_GENERAL;
		}	
	} 
}

/*
** ------------------
** UI
** ------------------
*/

function plaatsign_user_form() {

	/* input */
	global $mid;
	global $sid;
	
	global $id;
	global $user;
	global $access;

	global $user_name;
	global $user_email;
	global $user_role;	
	global $user_username;
	global $user_password;	
	
	/* output */
	global $page;
	global $title;
			
	$readonly = true;
	if (($id==$user->user_id) || ($user->role_id==ROLE_ADMINISTRATOR)) {
		$readonly = false;
	}
		
	if ((strlen($user_name)==0) && ($id!=0)) {
	
		$data = plaatsign_db_user($id);		
		
		$user_name = $data->name;
		$user_email = $data->email;
		$user_role = $data->role_id;
		$user_valid = $data->valid;
		
		$data = plaatsign_db_member($id);
		
		$user_username = $data->username;
	}
			
	$page .= '<div id="detail">';
				
	$title = t('USER_TITLE');
				
	$page .= '<h1>';
	$page .= $title;
	$page .= '</h1>';
	
	$page .= '<fieldset>' ;
	$page .= '<legend>'.t('PROJECT_GENERAL').'</legend>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_ID').':</label>';
	$page .= plaatsign_ui_input("id", 10, 10, $id, true);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_NAME').': *</label>';
	$page .= plaatsign_ui_input("user_name", 50, 50, $user_name, $readonly);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_EMAIL').': *</label>';
	$page .= plaatsign_ui_input("user_email", 50, 100, $user_email, $readonly);
	
	if (($id!=0) && ($user_valid==0)) {
		
		$link = plaatsign_link('mid='.$mid.'&sid='.$sid.'&eid='.EVENT_EMAIL_CONFIRM.'&id='.$id, t('LINK_HERE'));
		
		$page .= '<span id="tip">';
		$page .= ' '.t('USER_EMAIL_CONFIRM_NEEDED',$link);
		$page .= '</span>';
	}
	
	$page .= '</p>';
	
	if ($user->role_id==ROLE_ADMINISTRATOR) {
		$page .= '<p>';
		$page .= '<label>'.t('GENERAL_ROLE').': *</label>';
		$page .= plaatsign_ui_member_role('user_role', $user_role, $readonly);
		$page .= '</p>';
	}
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_USERNAME').': *</label>';
	$page .= plaatsign_ui_input("user_username", 20, 15, $user_username, $readonly);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_PASSWORD').': *</label>';
	$page .= '<input type="password" name="user_password" id="user_password" size="20" maxlength="15" value="'.$user_password.'"/>';
	$page .= '</p>';
			
	$page .= '<div id="note">';
	$page .= t('GENERAL_REQUIRED_FIELD');
	$page .= '</div>';
	
	$page .= '<br/>';
	
	$page .= '</fieldset>' ;
	
	$page .= '<p>';
	
	if (!$readonly) {
		$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_USER_SAVE, t('LINK_SAVE'));
		$page .= ' ';
	}
	
	if (($id!=0) && ($id!=$user->user_id) && (!$readonly)) {
		$page .= plaatsign_link_confirm('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_USER_DELETE, t('LINK_DELETE'),t('USER_DELETE_CONFIRM'));
		$page .= ' ';
	}
	$page .= plaatsign_link('mid='.$mid.'&eid='.EVENT_USER_CANCEL, t('LINK_CANCEL'));
	$page .= ' ';
	
	if ($user->role_id==ROLE_ADMINISTRATOR) {
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_USERLIST.'&eid='.EVENT_USER_HACK.'&id='.$data->user_id, t('LINK_HACK'));
	}
	
	$page .= '</p>';
	
	$page .= '</div>';
}


function plaatsign_userlist_form() {

	/* input */
	global $mid;
	global $sid;
	global $user;
	global $access;
	global $sort;
	
	/* output */
	global $page;
	global $title;
	
	$title = t('USERS_TITLE');
	
	$page .= '<h1>';
	$page .= $title;
	$page .= '</h1>';

	$page .= '<p>';
	$page .= t('USER_TEXT');
	$page .= '</p>';
		
	$query  = 'select distinct(a.user_id), a.name, a.valid, a.role_id, b.last_activity, b.last_login, b.requests from ';
	$query .= 'tuser a left join member b on a.user_id=b.user_id ';
	$query .= 'left join project_user c on a.user_id=c.user_id ';
	$query .= 'where b.deleted=0 ';	
	
	if ($user->role_id!=ROLE_ADMINISTRATOR) {
		$query .= 'and c.project_id='.$user->project_id.' ';
	}
		
	switch ($sort) {
		   		
	   case 1: $query .= 'order by a.name';
				   break;					
					
		case 2: $query .= 'order by a.role_id';
				   break;
	
		case 3: $query .= 'order by b.last_login desc';
				   break;
					
		default:
		case 4: $query .= 'order by b.last_activity desc';
				   break;
					
		case 5: $query .= 'order by b.requests desc';
				   break;				
	}
		
	$result = plaatsign_db_query($query);

	$page .= '<table>';
		
	$page .= '<thead>';
	$page .= '<tr>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=1', t('GENERAL_NAME'));	
	$page .= '</th>';
	
	if ($user->role_id==ROLE_ADMINISTRATOR) {
		$page .= '<th>';
		$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=2', t('GENERAL_ROLE'));
		$page .= '</th>';
	}
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=3', t('GENERAL_LAST_LOGIN'));
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=4', t('GENERAL_LAST_ACTIVITY'));
	$page .= '</th>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=5', t('GENERAL_REQUESTS'));
	$page .= '</th>';
		
	$page .= '<th>';
	$page	.= t('GENERAL_ACTION');
	$page .= '</th>';
		
	$page .= '</tr>';
	$page .= '</thead>';
		
	$page .= '<tbody>';
		
	$count=0;
	while ($data=plaatsign_db_fetch_object($result)) {				
		
		$page .= '<tr ';
		if ((++$count % 2 ) == 1 ) {
			$page .= 'class="light" ';
		} else {
			$page .= 'class="dark" ';
		} 
		$page .='>';
		
		$page .= '<td>';
		$page	.= $data->name;
		if ($data->valid==1) {
			$page .= plaatsign_ui_image("valid.png",' width="16" heigth="16" title="'.t('USER_EMAIL_VALID2').'"').' ';
		}
		$page .= '</td>';
		
		if ($user->role_id==ROLE_ADMINISTRATOR) {
			$page .= '<td>';
			$page	.= t('ROLE_'.$data->role_id);
			$page .= '</td>';
		}
		
		$page .= '<td>';
		$page	.= convert_datetime_php($data->last_login);
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= convert_datetime_php($data->last_activity);
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= $data->requests;
		$page .= '</td>';
		
		$page .= '<td>';
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_USER.'&id='.$data->user_id, t('LINK_VIEW'));
		$page .= '</td>';
		
		$page .= '</tr>';	
	}
	$page .= '</tbody>';
	$page .= '</table>';
	
	$page .= '<p>';
	
	if ($user->role_id==ROLE_ADMINISTRATOR) {
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_USER.'&id=0', t('LINK_ADD'));
	}
	$page .= '</p>';
}

/*
** ------------------
** HANDLERS
** ------------------
*/

function plaatsign_user_event_handler() {

	/* input */
	global $eid;
	
	/* Event handler */
	switch ($eid) {
		
		case EVENT_USER_SAVE: 
					plaatsign_user_save_do();
					break;
				  
		case EVENT_USER_DELETE: 
					plaatsign_user_delete_do();
					break;

		case EVENT_USER_CANCEL: 
					plaatsign_user_cancel_do();
					break;		

		case EVENT_USER_HACK:
					plaatsign_user_hack_do();
					break;
					
		case EVENT_EMAIL_CONFIRM: 
					plaatsign_user_email_confirm_do();
					break;				
	}
}

function plaatsign_user_page_handler() {

	/* input */
	global $sid;
	
	/* Page handler */
	switch ($sid) {
	
 	   case PAGE_USERLIST: 
					plaatsign_userlist_form();	
					break;	
				  
		case PAGE_USER: 
					plaatsign_user_form();
					break;
	}
}
					
/*
** ------------------
** THE END
** ------------------
*/

?>
