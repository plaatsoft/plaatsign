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
** -----------------
** GENERAL
** ----------------- 
*/

/**
 * connect to database
 * @param $dbhost database hostname
 * @param $dbuser database username
 * @param $dbpass database password
 * @param $dbname database name
 * @return connect result (true = successfull connected | false = connection failed)
 */
function plaatsign_db_connect($dbhost, $dbuser, $dbpass, $dbname) {

	global $db;

   $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);	
	if (mysqli_connect_errno()) {
		plaatsign_db_error();
		return false;		
	}
	return true;
}

/**
 * Disconnect from database  
 * @return disconnect result
 */
function plaatsign_db_close() {

	global $db;

	mysqli_close($db);

	return true;
}

/**
 * Show SQL error 
 * @return HTML formatted SQL error
 */
function plaatsign_db_error() {

	if (DEBUG == 1) {
		echo mysqli_connect_error(). "<br/>\n\r";
	}
}

/**
 * Count queries 
 * @return queries count
 */
$query_count=0;
function plaatsign_db_count() {

	global $query_count;
	return $query_count;
}

/**
 * Execute database multi query
 */
function plaatsign_db_multi_query($queries) {

	$tokens = @preg_split("/;/", $queries);
	foreach ($tokens as $token) {
	
		$token=trim($token);
		if (strlen($token)>3) {
			plaatsign_db_query($token);		
		}
	}
}

/**
 * Execute database query
 * @param $query SQL query with will be executed.
 * @return Database result
 */
function plaatsign_db_query($query) {
			
	global $query_count;
	global $db;
	
	$query_count++;

	if (DEBUG == 1) {
		echo $query."<br/>\r\n";
	}

	$result = mysqli_query($db, $query);

	if (!$result) {
		plaatsign_db_error();		
	}
	
	return $result;
}

/**
 * escap database string
 * @param $data  input.
 * @return $data escaped
 */
function plaatsign_db_escape($data) {

	global $db;
	
	return mysqli_real_escape_string($db, $data);
}

/**
 * Fetch query result 
 * @return mysql data set if any
 */
function plaatsign_db_fetch_object($result) {
	
	$row = $result->fetch_object();
	return $row;
}

/**
 * Return number of rows
 * @return number of row in dataset
 */
function plaatsign_db_num_rows($result) {
	
	return mysqli_num_rows($result);
}

/*
** -----------------
** MEMBER
** -----------------
*/

function plaatscrum_db_member_id($username, $password) {

	$member_id=0;

	$query  = 'select member_id from member where username="'.$username.'" and password="'.md5($password).'" and deleted=0';	
		
	$result = plaatscrum_db_query($query);
	$data = plaatscrum_db_fetch_object($result);
	if (isset ($data->member_id)) {	
		$member_id = $data->member_id;
	}	
	
	return $member_id;
}

function plaatscrum_db_member_insert($username, $password) {

	$query  = 'insert into member (username, password) ';
	$query .= 'values ("'.plaatscrum_db_escape($username).'","'.md5($password).'")';
	plaatscrum_db_query($query);
		
	/* user_id = member_id */
	$member_id = plaatscrum_db_member_id($username, $password);	

	$query  = 'update member set user_id='.$member_id.' where member_id='.$member_id; 
	plaatscrum_db_query($query);
				
	return $member_id;
}

function plaatscrum_db_member_update2($username, $password, $member_id) {
		
	$query  = 'update member set '; 
	$query .= 'username="'.plaatscrum_db_escape($username).'" ';
	
	if (strlen($password)>0) {
		$query .= ', password="'.md5($password).'" ';
	}

	$query .= 'where member_id='.$member_id; 
	
	plaatscrum_db_query($query);
}
	
function plaatscrum_db_member_update($data) {
		
	$query  = 'update member set '; 
	$query .= 'deleted='.$data->deleted.', ';
	$query .= 'requests='.$data->requests.', ';	
	$query .= 'last_activity="'.$data->last_activity.'", ';
	$query .= 'last_login="'.$data->last_login.'" ';
	$query .= 'where member_id='.$data->member_id; 
	
	plaatscrum_db_query($query);
}

function plaatscrum_db_member_username($username) {
	
	$query  = 'select member_id from member where username="'.$username.'"';	
	$result = plaatscrum_db_query($query);
	$data = plaatscrum_db_fetch_object($result);
	
	$member_id=0;
	if (isset($data->member_id)) {
		$member_id = $data->member_id;
	}
	return $member_id;
}

function plaatscrum_db_member($member_id) {
	
	$query  = 'select member_id, user_id, username, requests, last_login, last_activity, deleted ';
	$query .= 'from member where member_id='.$member_id;	
		
	$result = plaatscrum_db_query($query);
	$data = plaatscrum_db_fetch_object($result);
	
	return $data;
}

/*
** ---------------------
** SESSION
** ---------------------
*/

function plaatscrum_db_session_add($member_id) {
		
	/* First delete all old session */
	$query  = 'delete from session where member_id='.$member_id;	
	plaatscrum_db_query($query);  
		
	/* Create new session */
	$query  = 'insert into session (date, member_id) values ("'.date("Y-m-d H:i:s").'",'.$member_id.')';	
	plaatscrum_db_query($query);
	
	/* Return new session entry */
	$query  = 'select session_id from session where member_id='.$member_id;
	$result = plaatscrum_db_query($query);
	$data = plaatscrum_db_fetch_object($result);
	
	/* created unique session id */
	$tmp = md5($data->session_id);
	
	/* Update session state */
	$query  = 'update session set session = "'.$tmp.'" where session_id='.$data->session_id; 
	plaatscrum_db_query($query);
	
	return $tmp;
}

function plaatscrum_db_session_valid( $session ) {
	
	/* Session expires after 351 days of inactivity */
	$expired_days = 351;
	
	if (strlen($session)==0) {
		return 0;
	}
	
	$query  = 'select session_id, member_id, date from session where session="'.$session.'"';
	$result = plaatscrum_db_query($query);
	
	if ($data=plaatscrum_db_fetch_object($result)) {
		
		$expired = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-$expired_days, date("Y"));
		if ($data->date < date("Y-m-d H:i:s",$expired)) {
				
			plaatscrum_db_session_delete($data->session);
			return 0;
		}
	
		/* Update session state */
		$query  = 'update session set date = "'.date("Y-m-d H:i:s").'" where session="'.$session.'"'; 
		plaatscrum_db_query($query);
		
		return $data->member_id;
	}
	return 0;
}

function plaatscrum_db_session_delete($session) {
	
	$query = 'delete from session where session="'.$session.'"';
	
	plaatscrum_db_query($query); 
}

/**
 * hack a player session for debug reasons (Admin functionality)
 */
function plaatscrum_db_session_hack($member_id) {

	$query  = 'select session from session where member_id='.$member_id;
	$result = plaatscrum_db_query($query);
	
	if ($data = plaatscrum_db_fetch_object($result)) {
		return $data->session;
	} else {
		return plaatscrum_db_session_add($member_id);
	}
}

/*
** -----------------
** ROLE
** -----------------
*/

function plaatscrum_db_role($role_id) {
	
	$query  = 'select role_id, ';
	$query .= 'project_edit, story_add, story_edit, story_delete, story_import, story_export ';
	$query .= 'from role where role_id='.$role_id;	
		
	$result = plaatscrum_db_query($query);
	$data = plaatscrum_db_fetch_object($result);
	
	return $data;
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>