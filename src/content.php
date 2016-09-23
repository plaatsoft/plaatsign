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

$content_filename = plaatsign_post("filename", "");
$content_type = plaatsign_post("type", "");
$content_enabled = plaatsign_post("enabled", "");

/*
** ------------------
** ACTIONS
** ------------------
*/

function plaatsign_content_save_do() {
	
	/* input */
	global $id;
	global $user;
	
	global $content_type;
	global $content_filename;
	global $content_enabled;
		
	/* output */
	global $sid;
		
	$data = plaatsign_db_content($id);

	$size = filesize($_FILES['filename']['tmp_name']);
	$filename = basename($_FILES["filename"]["name"]);
	$imageFileType = pathinfo($filename, PATHINFO_EXTENSION);

	if (strlen($_FILES['filename']['tmp_name'])==0) {
	
		plaatsign_ui_box('warning', t('CONTENT_FILE_NOT_FOUND'));
	
	} else if ($imageFileType!="jpg" && $imageFileType!="png" && $imageFileType!="jpeg" && $imageFileType!="gif") {
			  			  
		plaatsign_ui_box('warning', t('CONTENT_TYPE_NOT_SUPPORTED'));
		
			  
	} else {
	
		$file = fopen($_FILES['filename']['tmp_name'], 'r');	
		$content = fread($file, $size);
	
		if ($id>0) {
					
			/* Update content data */	
			$data->type = $content_type;			
			$data->filename = $filename;
			$data->created = date("Y-m-d H:i:s", time());
				
			plaatsign_db_content_update($data);	
			plaatsign_ui_box('info', t('CONTENT_UPDATED'));
			plaatsign_info($user->name.' ['.$user->uid.'] update content ['.$id.']');		
			
		} else  {
			
			/* Insert new content */
			$id = plaatsign_db_content_insert($content_type, $content_filename, $content_enabled);			
		
			plaatsign_ui_box('info', t('USER_ADDED'));
			plaatsign_info($user->name.' ['.$user->uid.'] created content ['.$id.']');
		}

		/* Data ok, goto to previous page */		
		$sid = PAGE_CONTENTLIST;
	}
}

function plaatsign_content_delete_do() {
	
	/* input */
	global $id;
	global $user;
	
	/* output */	
	global $sid;
		
	$data = plaatsign_db_content($id);
	
	if (isset($data->cid)) {

		plaatsign_db_content_remove($data);

		plaatsign_ui_box('info', t('CONTENT_DELETED'));
		plaatsign_info($user->name.' ['.$user->uid.'] delete content ['.$id.']');
		
		$sid = PAGE_CONTENTLIST;
	} 
}

/*
** ------------------
** UI
** ------------------
*/

function plaatsign_content_form() {

	/* input */
	global $mid;
	global $sid;
	global $id;
	global $user;

	global $content_filename;
	global $content_type;
	global $content_created;
	global $content_enabled;
	
	/* output */
	global $page;
	global $title;
	
	if ($id!=0) {
	
		$data = plaatsign_db_content($id);		
		
		$content_filename = $data->filename;
		$content_type = $data->type;
		$content_created = $data->created;
	}
			
	$page .= '<div id="detail">';
				
	$title = t('CONTENT_TITLE');
				
	$page .= '<h1>';
	$page .= $title;
	$page .= '</h1>';
	
	$page .= '<fieldset>' ;
	$page .= '<legend>'.t('USER_GENERAL').'</legend>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_CID').':</label>';
	$page .= plaatsign_ui_input("id", 10, 10, $id, true);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_TYPE').': *</label>';
	$page .= plaatsign_ui_input("type", 2, 2, $content_type);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_FILENAME').': **</label>';
	$page .= plaatsign_ui_file("filename", $content_filename);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_ENABLED').': *</label>';
	$page .= plaatsign_ui_radiobox("created", $content_enabled);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_CREATED').': *</label>';
	$page .= plaatsign_ui_input("created", 20, 20, convert_datetime_php($content_created), true);
	$page .= '</p>';
		
	$page .= '<div id="note">';
	$page .= t('GENERAL_REQUIRED_FIELD');
	$page .= '</div>';
	
	$page .= '<div id="note">';
	$page .= t('CONTENT_REMARK',ini_get('upload_max_filesize').'B');
	$page .= '</div>';
	
	$page .= '<br/>';
	
	$page .= '</fieldset>' ;
	
	$page .= '<p>';
	
	$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_SAVE, t('LINK_SAVE'));
	$page .= ' ';
	
	if (($id!=0) && ($id!=$user->uid)) {
		$page .= plaatsign_link_confirm('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_DELETE, t('LINK_DELETE'), t('USER_DELETE_CONFIRM'));
		$page .= ' ';
	}
	$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENTLIST.'&eid='.EVENT_CANCEL, t('LINK_CANCEL'));
	$page .= ' ';
	
	$page .= '</p>';
	
	$page .= '</div>';
}


function plaatsign_contentlist_form() {

	/* input */
	global $mid;
	global $sid;
	global $sort;

	/* output */
	global $page;
	global $title;
				
	$title = t('CONTENT_TITLE');
			
	$page .= '<h1>';	
	$page .= $title;
	$page .= '</h1>';

	$page .= '<p>';
	$page .= t('CONTENT_NOTE');
	$page .= '</p>';
		
	$query  = 'select cid, type, filename, enabled, created from content ';
		
	switch ($sort) {

		default: $query .= 'order by cid asc';
				   break;
		   		
	   case 2:  $query .= 'order by type asc';
				   break;					

		case 3:  $query .= 'order by filename desc';
				   break;
					
		case 4:  $query .= 'order by enabled desc';
				   break;
					
		case 5:  $query .= 'order by created desc';
				   break;				
	}
		
	$result = plaatsign_db_query($query);

	$page .= '<table>';
		
	$page .= '<thead>';
	$page .= '<tr>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=1', t('GENERAL_CID'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=2', t('GENERAL_TYPE'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=3', t('GENERAL_FILENAME'));
	$page .= '</th>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=4', t('GENERAL_ENABLED'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&sort=5', t('GENERAL_CREATED'));
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
		$page	.= $data->cid;
		$page .= '</td>';
		
		$page .= '<td>';
		$page	.= $data->type;
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= $data->filename;
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= $data->enabled;
		$page .= '</td>';
		
		$page .= '<td>';
		$page	.= convert_datetime_php($data->created);
		$page .= '</td>';
		
		$page .= '<td>';
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENT.'&id='.$data->cid, t('LINK_VIEW'));
		$page .= '</td>';
		
		$page .= '</tr>';	
	}
	$page .= '</tbody>';
	$page .= '</table>';
	
	$page .= '<p>';
	$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENT.'&eid='.EVENT_ADD, t('LINK_ADD'));
	$page .= '</p>';
}

/*
** ------------------
** HANDLER
** ------------------
*/

function plaatsign_content() {

	/* input */
	global $eid;
	global $sid;
	
	/* Event handler */
	switch ($eid) {

		case EVENT_SAVE: 
					plaatsign_content_save_do();
					break;
					
		case EVENT_REMOVE: 
					plaatsign_content_remove_do();
					break;
	}
	
	/* Page handler */
	switch ($sid) {
	
		case PAGE_CONTENTLIST: 
					plaatsign_contentlist_form();	
					break;
					
		case PAGE_CONTENT: 
					plaatsign_content_form();	
					break;
					
	}
}


/*
** ------------------
** The End
** ------------------
*/

