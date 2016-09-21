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
** USER
** -----------------
*/

function plaatsign_db_user_id($username, $password) {

	$uid=0;

	//$query  = 'select uid from user where username="'.$username.'" and password="'.md5($password).'"';	
	$query  = 'select uid from user where username="'.$username.'" and password="'.$password.'"';	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	if (isset ($data->uid)) {	
		$uid = $data->uid;
	}	
	
	return $uid;
}

function plaatsign_db_user_username($username) {
	
	$query  = 'select uid from user where username="'.$username.'"';	
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	$member_id=0;
	if (isset($data->member_id)) {
		$member_id = $data->member_id;
	}
	return $member_id;
}

function plaatsign_db_user($uid) {
	
	$query  = 'select uid, username, name, email, language, created, last_login ';
	$query .= 'from user where uid='.$uid;	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data;
}

function plaatsign_db_user_insert($username, $password) {

	$query  = 'insert into user (username, password, created) ';
	$query .= 'values ("'.plaatsign_db_escape($username).'","'.md5($password).'","'.date("Y-m-d H:i:s").'")';
	plaatsign_db_query($query);
		
	$uid = plaatsign_db_user_id($username, $password);	
				
	return $uid;
}

function plaatsign_db_user_update($data) {
		
	$query  = 'update user set '; 
	$query .= 'name="'.$data->name.'", ';
	$query .= 'email="'.$data->email.'", ';
	$query .= 'language="'.$data->language.'", ';
	$query .= 'last_login="'.$data->last_login.'" ';
	$query .= 'where uid='.$data->uid; 
	
	plaatsign_db_query($query);
}

/*
** ---------------------
** SESSION
** ---------------------
*/

function plaatsign_db_session_add($uid) {
		
	/* First delete all old session */
	$query  = 'delete from session where uid='.$uid;	
	plaatsign_db_query($query);  
		
	/* Create new session */
	$query  = 'insert into session (date, uid) values ("'.date("Y-m-d H:i:s").'",'.$uid.')';	
	plaatsign_db_query($query);
	
	/* Return new session entry */
	$query  = 'select sid from session where uid='.$uid;
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	/* created unique session id */
	$tmp = md5($data->sid);
	
	/* Update session state */
	$query  = 'update session set session = "'.$tmp.'" where sid='.$data->sid; 
	plaatsign_db_query($query);
	
	return $tmp;
}

function plaatsign_db_session_valid( $session ) {
	
	/* Session expires after 1 day of inactivity */
	$expired_days = 1;
	
	if (strlen($session)==0) {
		return 0;
	}
	
	$query  = 'select session_id, member_id, date from session where session="'.$session.'"';
	$result = plaatsign_db_query($query);
	
	if ($data=plaatsign_db_fetch_object($result)) {
		
		$expired = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-$expired_days, date("Y"));
		if ($data->date < date("Y-m-d H:i:s",$expired)) {
				
			plaatsign_db_session_delete($data->session);
			return 0;
		}
	
		/* Update session state */
		$query  = 'update session set date = "'.date("Y-m-d H:i:s").'" where session="'.$session.'"'; 
		plaatsign_db_query($query);
		
		return $data->member_id;
	}
	return 0;
}

function plaatsign_db_session_delete($session) {
	
	$query = 'delete from session where session="'.$session.'"';
	
	plaatsign_db_query($query); 
}

/*
** -----------------
** ROLE
** -----------------
*/

function plaatsign_db_role($role_id) {
	
	$query  = 'select role_id, ';
	$query .= 'project_edit, story_add, story_edit, story_delete, story_import, story_export ';
	$query .= 'from role where role_id='.$role_id;	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data;
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>