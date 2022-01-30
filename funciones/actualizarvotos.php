<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['src'])){
	$src=htmlspecialchars_decode($_REQUEST['src']);
	$propietario=$_REQUEST['propietario'];
	$voto=$_REQUEST['nombre'];
	$idvoto=$_REQUEST['idmeme'];
	$token=$_REQUEST['token'];
	global $db;

	$db->query(sprintf("DELETE FROM meme_votos WHERE voto = %s AND partida=%s", secure($voto), secure($token))) or _error(SQL_ERROR_THROWEN);
	$db->query(sprintf("INSERT INTO meme_votos (propietario, voto, id_voto, partida, src) VALUES(%s,%s,%s,%s,%s)", secure($propietario), secure($voto), secure($idvoto), secure($token), secure($src))) or _error(SQL_ERROR_THROWEN);

	$db->query(sprintf("DELETE FROM meme_jugadores_reset WHERE token=%s", secure($token))) or _error(SQL_ERROR_THROWEN);
	//$db->query(sprintf("UPDATE memes_jugadores_memes SET votos_recibidos = votos_recibidos+1 WHERE src = %s AND token=%s", secure($src), secure($token))) or _error(SQL_ERROR_THROWEN);
	$avg = array('src' => $src,'nombre' => $voto,'id' => $propietario,'token' => $token,'havotado' => 1);
	echo return_json($avg);
}
else{
	$avg = array('token' => 0,'nombre' => 0,'id' => 0);
	echo return_json($avg);
}
?>