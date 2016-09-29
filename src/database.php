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

	@$result = mysqli_query($db, $query);

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
	
	$row="";
	
	if (isset($result)) {
		$row = $result->fetch_object();
	}
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
** ------------------------
** CREATED / PATCH DATABASE
** ------------------------
*/

function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * Execute SQL script
 * @param $version Version of sql patch file
 */
function plaatsign_db_execute_sql_file($version) {

    $filename = 'database/patch-'.$version.'.sql';

    $commands = file_get_contents($filename);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--') ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";\n", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)){
	         $success += (@plaatsign_db_query($command)==false ? 0 : 1);
            $total += 1;
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}

/**
 * Check db version and upgrade if needed!
 */
function plaatsign_db_check_version() {

   // Create database if needed	
   $sql = "select 1 FROM config limit 1" ;
   $result = plaatsign_db_query($sql);
   if (!$result) {
		$version="0.1";
      plaatsign_db_execute_sql_file($version);
   }

   // Path database if needed	
	$version = plaatsign_db_config_get("database_version");
   if ($version=="0.1") {
		$version="0.2";
      plaatsign_db_execute_sql_file($version);		
   }
	
	// Path database if needed	
   if ($version=="0.2") {
		$version="0.3";
      plaatsign_db_execute_sql_file($version);
   }
}

/*
** -----------------
** USER
** -----------------
*/

function plaatsign_db_user_id($username, $password) {

	$uid=0;

	$query  = 'select uid, password from user where username="'.$username.'"' ;	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	if (isset($data->uid)) {	
	
		if (plaatsign_password_verify($password, $data->password)) {
			$uid = $data->uid;
		}
	}	
	
	return $uid;
}

function plaatsign_db_user_username($username) {
	
	$query  = 'select uid from user where username="'.$username.'"';	
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	$uid=0;
	if (isset($data->uid)) {
		$uid = $data->uid;
	}
	return $uid;
}

function plaatsign_db_user($uid) {
	
	$query  = 'select uid, username, name, email, language, created, last_activity, role, requests ';
	$query .= 'from user where uid='.$uid;	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data;
}

function plaatsign_db_user_insert($username, $password) {

	$query  = 'insert into user (username, password, language, created, requests, role) ';
	$query .= 'values ("'.plaatsign_db_escape($username).'","'.plaatsign_password_hash($password).'",0,"'.date("Y-m-d H:i:s").'", 0, '.ROLE_USER.')';
	plaatsign_db_query($query);
		
	$uid = plaatsign_db_user_id($username, $password);	
				
	return $uid;
}

function plaatsign_db_user_update($data) {
		
	$query  = 'update user set '; 
	$query .= 'name="'.$data->name.'", ';
	$query .= 'email="'.$data->email.'", ';
	$query .= 'language='.$data->language.', ';
	$query .= 'last_activity="'.$data->last_activity.'", ';
	$query .= 'role='.$data->role.', ';
	$query .= 'requests='.$data->requests.' ';
	$query .= 'where uid='.$data->uid; 
	
	plaatsign_db_query($query);
}

function plaatsign_db_user_update2($username, $password, $uid) {
		
	$query  = 'update user set '; 
	$query .= 'username="'.$username.'", ';
	$query .= 'password="'.plaatsign_password_hash($password).'" ';
	$query .= 'where uid='.$uid; 
	
	plaatsign_db_query($query);
}

function plaatsign_db_user_remove($data) {
		
	$query  = 'delete from user where uid='.$data->uid;	
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
	
	/* Session expires after 10 minutes */
	$expired = 600;
	
	if (strlen($session)==0) {
		return 0;
	}
	
	$query  = 'select sid, uid, date, session from session where session="'.$session.'"';
	$result = plaatsign_db_query($query);
	
	if ($data=plaatsign_db_fetch_object($result)) {
		
		if (((time()-strtotime($data->date))>$expired)) {
				
			plaatsign_db_session_delete($data->session);
			
		} else {
	
			/* Update session state */
			$query  = 'update session set date = "'.date("Y-m-d H:i:s").'" where session="'.$session.'"'; 
			plaatsign_db_query($query);
		
			return $data->uid;
		}
	}
	return 0;
}

function plaatsign_db_session_delete($session) {
	
	$query = 'delete from session where session="'.$session.'"';
	
	plaatsign_db_query($query); 
}

/*
** -----------------
** CONTENT
** -----------------
*/

function plaatsign_db_content_filename($filename) {
	
	$query  = 'select cid from content where filename="'.$filename.'"';	
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	$cid=0;
	if (isset($data->cid)) {
		$cid = $data->cid;
	}
	return $cid;
}

function plaatsign_db_content($cid) {
	
	$query  = 'select cid, filename, filesize, enabled, created, uid, tid from content where cid='.$cid;	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data;	
}

function plaatsign_db_content_insert($filename, $filesize, $enabled, $uid, $tid) {

	$query  = 'insert into content (filename, filesize, enabled, created, uid, tid) ';
	$query .= 'values ("'.$filename.'",'.$filesize.','.$enabled.',"'.date("Y-m-d H:i:s").'",'.$uid.','.$tid.')';
	plaatsign_db_query($query);
		
	$query  = 'select cid from content where filename="'.$filename.'" and filesize='.$filesize;	
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	$cid=0;
	if (isset($data->cid)) {
		$cid = $data->cid;
	}
	return $cid;
}

function plaatsign_db_content_update($data) {
		
	$query  = 'update content set '; 
	$query .= 'filename="'.$data->filename.'", ';
	$query .= 'filesize='.$data->filesize.', ';
	$query .= 'enabled='.$data->enabled.', ';
	$query .= 'created="'.$data->created.'" ';
	$query .= 'uid="'.$data->uid.'", ';
	$query .= 'tid="'.$data->tid.'" ';
	$query .= 'where cid='.$data->cid; 
	
	plaatsign_db_query($query);
}

function plaatsign_db_content_delete($cid) {
	
	$query = 'delete from content where cid='.$cid;
	
	plaatsign_db_query($query); 
}

/*
** -----------------
** CONFIG
** -----------------
*/

function plaatsign_db_config($token) {
	
	$query  = 'select id, token, category, value, options, last_update, readonly from config where token="'.$token.'"';	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data;	
}

function plaatsign_db_config_get($token) {
	
	$query  = 'select value from config where token="'.$token.'"';	
		
	$result = plaatsign_db_query($query);
	$data = plaatsign_db_fetch_object($result);
	
	return $data->value;	
}

function plaatsign_db_config_update($data) {
		
	$query  = 'update config set '; 
	$query .= 'value="'.$data->value.'", ';
	$query .= 'last_update="'.date("Y-m-d H:i:s").'" ';
	$query .= 'where id='.$data->id; 
	
	plaatsign_db_query($query);
}

/*
** ---------------------
** THE END
** ---------------------
*/

?>