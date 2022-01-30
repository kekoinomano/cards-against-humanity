<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['codigo'])){
	$codigo=$_REQUEST['codigo'];
	$nombre=$_REQUEST['nombre'];
	global $db;
	$followings = comprobarelcodigo($codigo);
	//$avg = array('average' => $followings,'nombre' => $nombre,'id' => $id);
	if ($followings[0]!=0){
		$token = $codigo;
		session_start();

		$_SESSION['id'] = (isset($_SESSION['id'])) ? $_SESSION['id'] : uniqid();
		$id=$_SESSION['id'];
		$_SESSION['nombre'] = (isset($_SESSION['nombre'])) ? $nombre : $nombre;
		$nombre=$_SESSION['nombre'];
		$currentTime = time();

		$gracePeriod = $currentTime - $secondsToConsiderOffline;

		$page_title = "Meme";

		$page_url = $token;
		$db->query(sprintf("DELETE FROM meme_online WHERE last_activity < %s OR id_online = %s", secure($gracePeriod), secure($id))) or _error(SQL_ERROR_THROWEN);
		$db->query(sprintf("INSERT INTO meme_online (id_online, page_title, page_url, last_activity, nombre) VALUES (%s,%s,%s,%s,%s)", secure($id), secure($page_title), secure($page_url), secure($currentTime), secure($nombre))) or _error(SQL_ERROR_THROWEN);
		$avg = array('token' => $followings[0],'nombre' => $nombre,'id' => $id);
	}else{
		$avg = array('token' => 0,'nombre' => $nombre,'id' => 0);
	}
	echo return_json($avg);
}
else{
	$avg = array('token' => 0,'nombre' => 0,'id' => 0);
	echo return_json($avg);
}
?>