<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['token'])){
	$token=$_REQUEST['token'];
	$propietarios=$_REQUEST['propietarios'];
	$votos=$_REQUEST['votos'];
	global $db;

	$avg = array('votos' => $votos,'propietarios' => $propietarios,'token' => $token);
	echo return_json($avg);
}
else{
	$avg = array('token' => 0,'nombre' => 0,'id' => 0);
	echo return_json($avg);
}
?>