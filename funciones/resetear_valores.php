<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['token'])){
	$token=$_REQUEST['token'];
	global $db;

	$db->query(sprintf("DELETE FROM meme_jugadores_reset WHERE token=%s", secure($token))) or _error(SQL_ERROR_THROWEN);

	$avg = array('reset' => 1);
	echo return_json($avg);
}
else{
	$avg = array('token' => 0,'nombre' => 0,'id' => 0);
	echo return_json($avg);
}
?>