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
$user_username = plaatsign_post("user_username", "");
$user_password  = plaatsign_post("user_password", "");
$user_role = plaatsign_post("user_role", ROLE_USER);
$user_language = plaatsign_post("user_language", LANGUAGE_ENGLISH);

/*
** ------------------
** ACTIONS
** ------------------
*/

function plaatsign_user_save_do() {
	
	/* input */
	global $id;
	global $user;
	
	global $user_name;
	global $user_email;
	global $user_username;
	global $user_password;
	global $user_role;
	global $user_language;
		
	/* output */
	global $sid;
		
	$data = plaatsign_db_user($id);
	
	if (strlen($user_name)<3) {

		plaatsign_ui_box('warning', t('USER_NAME_TO_SHORT'));
		
	} else if (validate_email($user_email)) {
		
		plaatsign_ui_box('warning', t('USER_EMAIL_INVALID'));
		
	} else if (strlen($user_username)<5) {
		
		plaatsign_ui_box('warning', t('USER_USERNAME_TO_SHORT'));
		
	} else if (($id==0) && (plaatsign_db_user_username($user_username)>0)) {
	
		plaatsign_ui_box('warning', t('USER_USERNAME_EXIST'));

	} else if (isset($data->username) && ($data->username!=$user_username) && (plaatsign_db_user_username($user_username)>0)) {
	
		plaatsign_ui_box('warning', t('USER_USERNAME_EXIST'));
		
	} else if (strlen($user_password)<8) {

		plaatsign_ui_box('warning', t('USER_PASSWORD_TO_SHORT'));
		
	} else {
	
		if ($id>0) {
						
			/* Update user data */	
			plaatsign_db_user_update2($user_username, $user_password, $id);

			$data->email = $user_email;			
			$data->name = $user_name;
			$data->role = $user_role;
			$data->language = $user_language;
			$data->last_activity = date("Y-m-d H:i:s", time());
				
			plaatsign_db_user_update($data);	

			plaatsign_ui_box('info', t('USER_UPDATED'));
			plaatsign_info($user->name.' ['.$user->uid.'] update user ['.$id.']');		
			
			// Overrule current user settings
			if ($id==$user->uid) {
				$user = $data;
			}
		
		} else  {
			
			/* Insert new user */
			$id = plaatsign_db_user_insert($user_username, $user_password);			

			$data = plaatsign_db_user($id);
			
			$data->email = $user_email;			
			$data->name = $user_name;
			$data->role = $user_role;
			$data->language = $user_language;
			$data->last_activity = date("Y-m-d H:i:s", time());
			
			plaatsign_db_user_update($data);		
			
			plaatsign_ui_box('info', t('USER_ADDED'));
			plaatsign_info($user->name.' ['.$user->uid.'] created user ['.$id.']');
		}
				
		/* Data ok, goto to previous page */		
		$sid = PAGE_USERLIST;
	} 	
}

function plaatsign_user_delete_do() {
	
	/* input */
	global $id;
	global $user;
	
	/* output */	
	global $sid;
		
	$data = plaatsign_db_user($id);
	
	if (isset($data->uid)) {

		plaatsign_db_user_remove($data);

		plaatsign_ui_box('info', t('USER_DELETED'));
		plaatsign_info($user->name.' ['.$user->uid.'] delete user ['.$id.']');
		
		$sid = PAGE_USERLIST;
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

	global $user_name;
	global $user_email;
	global $user_username;
	global $user_password;	
	global $user_role;	
	global $user_language;	
	
	/* output */
	global $page;
	global $title;
	
	if ((strlen($user_name)==0) && ($id!=0)) {
	
		$data = plaatsign_db_user($id);		
		
		$user_name = $data->name;
		$user_email = $data->email;
		$user_username = $data->username;
		$user_password = "";
		$user_role = $data->role;
		$user_language = $data->language;
	}
			
	$page .= '<div id="detail">';
				
	$title = t('USER_TITLE');
				
	$page .= '<h1>';
	$page .= $title;
	$page .= '</h1>';
	
	$page .= '<fieldset>' ;
	$page .= '<legend>'.t('USER_GENERAL').'</legend>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_UID').':</label>';
	$page .= plaatsign_ui_input("id", 10, 10, $id, true);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_NAME').': *</label>';
	$page .= plaatsign_ui_input("user_name", 50, 50, $user_name);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_EMAIL').': *</label>';
	$page .= plaatsign_ui_input("user_email", 50, 100, $user_email);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_USERNAME').': *</label>';
	$page .= plaatsign_ui_input("user_username", 20, 15, $user_username);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_PASSWORD').': *</label>';
	$page .= '<input type="password" name="user_password" id="user_password" size="20" maxlength="15" value="'.$user_password.'"/>';
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_LANGUAGE').': *</label>';	
	$page .= plaatsign_ui_language('user_language', $user_language);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_ROLE').': *</label>';	
	$page .= plaatsign_ui_role('user_role', $user_role, $user->role==ROLE_USER);
	$page .= '</p>';
			
	$page .= '<div id="note">';
	$page .= t('GENERAL_REQUIRED_FIELD');
	$page .= '</div>';
	
	$page .= '<br/>';
	
	$page .= '</fieldset>' ;
	
	$page .= '<p>';
	
	$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_SAVE, t('LINK_SAVE'));
	$page .= ' ';
	
	if (($id!=0) && ($id!=$user->uid)) {
		$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_DELETE, t('LINK_DELETE'));
		$page .= ' ';
	}
	$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_USERLIST.'&eid='.EVENT_CANCEL, t('LINK_CANCEL'));
	$page .= ' ';
	
	$page .= '</p>';
	
	$page .= '</div>';
}


function plaatsign_userlist_form() {

	/* input */
	global $mid;
	global $sid;
	global $sort;
	global $user;
	
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
		
	$query  = 'select uid, name, role, last_activity, requests from user ';
		
	switch ($sort) {

		default: $query .= 'order by uid asc';
				   break;
		   		
	   case 2:  $query .= 'order by name asc';
				   break;					

		case 3:  $query .= 'order by role desc';
				   break;		
					
		case 4:  $query .= 'order by last_activity desc';
				   break;
					
		case 5:  $query .= 'order by requests desc';
				   break;				
	}
		
	$result = plaatsign_db_query($query);

	$page .= '<table>';
		
	$page .= '<thead>';
	$page .= '<tr>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=1', t('GENERAL_UID'));		
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=2', t('GENERAL_NAME'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=3', t('GENERAL_ROLE'));
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=4', t('GENERAL_LAST_ACTIVITY'));
	$page .= '</th>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=5', t('GENERAL_REQUESTS'));
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
		if (($user->role==ROLE_ADMIN) || ($user->uid==$data->uid)) {
			$page	.= plaatsign_link('mid='.$mid.'&sid='.PAGE_USER.'&id='.$data->uid, $data->uid);
		} else {
			$page	.= $data->uid;
		}
		$page .= '</td>';
		
		$page .= '<td>';
		$page	.= $data->name;
		$page .= '</td>';
		
		$page .= '<td>';
		$page	.= t('ROLE_'.$data->role); 
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= convert_datetime_php($data->last_activity);
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= $data->requests;
		$page .= '</td>';
		
		$page .= '</tr>';	
	}
	$page .= '</tbody>';
	$page .= '</table>';
	
	if ($user->role==ROLE_ADMIN) {
		$page .= '<p>';
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_USER.'&id=0', t('LINK_ADD'));
		$page .= '</p>';
	}
}

/*
** ------------------
** HANDLERS
** ------------------
*/

function plaatsign_user() {

	/* input */
	global $sid;
	global $eid;
	
	/* Event handler */
	switch ($eid) {
		
		case EVENT_SAVE: 
					plaatsign_user_save_do();
					break;
				  
		case EVENT_DELETE: 
					plaatsign_user_delete_do();
					break;
	}
	
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
