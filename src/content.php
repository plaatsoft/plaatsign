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

$filename = plaatsign_post("filename", "");
$enabled = plaatsign_post_radio("enabled", 1);

/*
** ------------------
** ACTIONS
** ------------------
*/

function plaatsign_content_save_do() {
	
	/* input */
	global $id;
	global $user;
	global $tid;
	
	global $filename;
	global $enabled;
				
	$data = plaatsign_db_content($id);

	$filesize = filesize($_FILES['filename']['tmp_name']);
	$filename = basename($_FILES["filename"]["name"]);
	$filetype = pathinfo($filename, PATHINFO_EXTENSION);

	if (strlen($filesize)==0) {
	
		plaatsign_ui_box('warning', t('CONTENT_FILE_NOT_FOUND'));
	
	} else if (plaatsign_db_content_filename($filename)>0) {
		
		plaatsign_ui_box('warning', t('CONTENT_ALREADY_EXIST'));
	
	} else if ($filetype!="jpg" && $filetype!="png" && $filetype!="jpeg" && $filetype!="gif") {
			  			  
		plaatsign_ui_box('warning', t('CONTENT_TYPE_NOT_SUPPORTED'));
		
	} else if ($filesize>(2048000)) {
	
		plaatsign_ui_box('warning', t('CONTENT_TO_LARGE', '2MB'));
		
	} else {
	
		if ($id>0) {
					
			/* Update content data */					
			$data->filename = $filename;
			$data->filesize = $filesize;
			$data->enabled = $enabled;		
			$data->uid = $user->uid;
			$data->created = date("Y-m-d H:i:s", time());
				
			plaatsign_db_content_update($data);	
			plaatsign_ui_box('info', t('CONTENT_UPDATED'));
			plaatsign_info($user->name.' ['.$user->uid.'] update content ['.$id.']');		
			
		} else  {
			
			/* Insert new content */
			$id = plaatsign_db_content_insert($filename, $filesize, $enabled, $user->uid, $tid);			
		
			plaatsign_ui_box('info', t('CONTENT_ADDED'));
			plaatsign_info($user->name.' ['.$user->uid.'] created content ['.$id.']');
		}
		
		$cache_filename = $id.'.'.pathinfo($filename, PATHINFO_EXTENSION);			
		move_uploaded_file($_FILES["filename"]["tmp_name"], "uploads/".$cache_filename);
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

		$cache_filename = $data->cid.'.'.pathinfo($data->filename, PATHINFO_EXTENSION);		
		@unlink("uploads/".$cache_filename);
		
		plaatsign_db_content_delete($id);

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
	global $tid;
	global $id;
	global $user;

	global $filename;
	global $enabled;
	$filesize = 0;
	$created = "";
	$owner = "";
	
	/* output */
	global $page;
	global $title;
	
	if ($id!=0) {
	
		$data = plaatsign_db_content($id);		
		if (isset($data->cid)) {
		
			$filename = $data->filename;
			$filesize = $data->filesize;
			$enabled = $data->enabled;
			$created = $data->created;
			
			$tmp = plaatsign_db_user($data->uid);
			if (isset($tmp->uid)) {	
				$owner = $tmp->name;
			}
		} else {
			$id=0;
		}
	}
			
	$page .= '<div id="detail">';	
				
	$title = t('CONTENT_TITLE');
				
	$page .= '<h1>';
	$page .= $title;
	$page .= '</h1>';
	
	$page .= '<fieldset>' ;
	$page .= '<legend>'.t('USER_GENERAL').'</legend>';
	
	if ($id>0) {
		$cache_filename = $data->cid.'.'.pathinfo($data->filename, PATHINFO_EXTENSION);		
		$page	.= '<image class="imgl" src="uploads/'.$cache_filename.'" width="480" height="360" />';
	} else {
		$page	.= '<image class="imgl" src="images/unknown.jpg" width="480" height="360" />';
	}
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_CID').':</label>';
	$page .= plaatsign_ui_input("id", 10, 10, $id, true);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_FILENAME').': *</label>';
	if ($id==0) {
		$page .= plaatsign_ui_file("filename", $filename);
	} else {
		$page .= plaatsign_ui_input("filename", 30, 30, $filename, true);
	}
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_FILESIZE').': </label>';
	$page .= plaatsign_ui_input("size", 10, 10, plaatsign_filesize($filesize,1), true);
	$page .= '</p>';
			
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_CREATED').': </label>';
	$page .= plaatsign_ui_input("created", 20, 20, convert_datetime_php($created), true);
	$page .= '</p>';
	
	$page .= '<p>';
	$page .= '<label>'.t('GENERAL_OWNER').': </label>';
	$page .= plaatsign_ui_input("owner", 30, 30, $owner, true);
	$page .= '</p>';
	
	$page .= '<div id="note">';
	$page .= t('CONTENT_REMARK',ini_get('upload_max_filesize').'B');
	$page .= '</div>';
	
	$page .= '</fieldset>' ;
		
	if ($id==0) {
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENTLIST.'&eid='.EVENT_NONE.'&tid='.$tid, t('LINK_CANCEL'));
		$page .= ' ';
		$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_SAVE.'&tid='.$tid, t('LINK_OK'));
	}
	
	if ($id!=0) {
		if (($tid==TYPE_MANUAL) || (($tid==TYPE_AUTOMATIC) && ($user->role==ROLE_ADMIN))) {
			$page .= plaatsign_link('mid='.$mid.'&sid='.$sid.'&id='.$id.'&eid='.EVENT_DELETE.'&tid='.$tid, t('LINK_DELETE'));
			$page .= ' ';
		}
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENTLIST.'&eid='.EVENT_NONE.'&tid='.$tid, t('LINK_OK'));
	}
	
	$page .= '</div>';
}

function plaatsign_contentlist_form() {

	/* input */
	global $mid;
	global $sid;
	global $tid;
	global $sort;
	global $user;

	/* output */
	global $page;
	global $title;
				
	if ($tid==TYPE_MANUAL) {
		$title = t('CONTENT_SUBTITLE1');
	} else {
		$title = t('CONTENT_SUBTITLE2');
	}

	$page .= '<h1>';	
	$page .= $title;
	$page .= '</h1>';

	$page .= '<p>';
	$page .= t('CONTENT_NOTE');
	$page .= '</p>';
		
	$query  = 'select cid, filename, filesize, enabled, created, uid from content where type='.$tid.' ';
		
	switch ($sort) {

		default: $query .= 'order by cid asc';
				   break;
					
		case 2:  $query .= 'order by filename asc';
				   break;
		   		
	   case 3:  $query .= 'order by filesize asc';
				   break;					
					
		case 4:  $query .= 'order by created asc';
				   break;
					
		case 5:  $query .= 'order by uid asc';
				   break;				
	}
		
	$result = plaatsign_db_query($query);

	$page .= '<table>';
		
	$page .= '<thead>';
	$page .= '<tr>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&tid='.$tid.'&sort=1', t('GENERAL_CID'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= t('GENERAL_IMAGE');	
	$page .= '</th>';
		
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&tid='.$tid.'&sort=2', t('GENERAL_FILENAME'));
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&tid='.$tid.'&sort=3', t('GENERAL_FILESIZE'));	
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&tid='.$tid.'&sort=4', t('GENERAL_CREATED'));
	$page .= '</th>';
	
	$page .= '<th>';
	$page	.= plaatsign_link('mid='.$mid.'&sid='.$sid.'&tid='.$tid.'&sort=5', t('GENERAL_OWNER'));	
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
		$page	.= plaatsign_link('mid='.$mid.'&tid='.$tid.'&sid='.PAGE_CONTENT.'&id='.$data->cid, $data->cid);
		$page .= '</td>';
		
		$page .= '<td>';
		$cache_filename = $data->cid.'.'.pathinfo($data->filename, PATHINFO_EXTENSION);	
		$page	.= plaatsign_link('mid='.$mid.'&tid='.$tid.'&sid='.PAGE_CONTENT.'&id='.$data->cid,'<image src="uploads/'.$cache_filename.'" width="128" height="80" />');
		$page .= '</td>';
			
		$page .= '<td>';
		$page	.= $data->filename;
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= plaatsign_filesize($data->filesize,1);
		$page .= '</td>';
				
		$page .= '<td>';
		$page	.= convert_datetime_php($data->created);
		$page .= '</td>';
				
		$page .= '<td>';
		$owner = plaatsign_db_user($data->uid);
		if (isset($owner->uid)) {
			$page	.= $owner->name;
		}
		$page .= '</td>';
		
		$page .= '</tr>';	
	}
	$page .= '</tbody>';
	$page .= '</table>';
	
	if (($tid==TYPE_MANUAL) || (($tid==TYPE_AUTOMATIC) && ($user->role==ROLE_ADMIN))) {
		$page .= '<p>';
		$page .= plaatsign_link('mid='.$mid.'&sid='.PAGE_CONTENT.'&eid='.EVENT_ADD.'&tid='.$tid, t('LINK_ADD'));
		$page .= '</p>';
	}
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
					
		case EVENT_DELETE: 
					plaatsign_content_delete_do();
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

