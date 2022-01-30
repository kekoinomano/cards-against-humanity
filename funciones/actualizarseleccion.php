<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['src'])){
	$src=$_REQUEST['src'];
	$nombre=$_REQUEST['nombre'];
	$id=$_REQUEST['id'];
	$token=$_REQUEST['token'];
	global $db;
	$db->query(sprintf("UPDATE memes_jugadores_memes SET votada = 0 WHERE id_jugador = %s", secure($id))) or _error(SQL_ERROR_THROWEN);
	$db->query(sprintf("UPDATE memes_jugadores_memes SET votada = 1, antigua = 0 WHERE id_jugador = %s AND iddelmeme = %s", secure($id), secure($src))) or _error(SQL_ERROR_THROWEN);
	$avg = array('src' => $src,'nombre' => $nombre,'id' => $id,'token' => $token);
	echo return_json($avg);
}
else{
	$avg = array('token' => 0,'nombre' => 0,'id' => 0);
	echo return_json($avg);
}
?>