<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

if (isset($_REQUEST['nombre'])){
	$nombre=$_REQUEST['nombre'];
	global $db;
	$token = crearsala($nombre);
	session_start();
	$_SESSION['nombre'] = $nombre;
	
	$_SESSION['id'] = (isset($_SESSION['id'])) ? $_SESSION['id'] : uniqid();

	$currentTime = time();

	$gracePeriod = $currentTime - $secondsToConsiderOffline;

	$id = $_SESSION['id'];
	$page_title = "Meme";
	$page_url = $token;
	$db->query(sprintf("DELETE FROM meme_online WHERE id_online = %s", secure($id))) or _error(SQL_ERROR_THROWEN);

	$db->query(sprintf("INSERT INTO meme_online (id_online, page_title, page_url, last_activity, nombre, admin) VALUES (%s,%s,%s,%s,%s, 1)", secure($id), secure($page_title), secure($page_url), secure($currentTime), secure($nombre))) or _error(SQL_ERROR_THROWEN);
	


	//Borro info de partidas sin gente online


    $eltoken=array();
    $get_activas = $db->query("SELECT page_url FROM meme_online") or _error(SQL_ERROR_THROWEN);
    if($get_activas->num_rows > 0) {
        while($frases = $get_activas->fetch_assoc()) {

                $eltoken[] = $frases['page_url'];

        }
    }
    $tokenynumero=array_count_values($eltoken);
    arsort($tokenynumero, SORT_NUMERIC);
    $eltoken=array_keys($tokenynumero);
    $i=0;
    $primera=$tokenynumero[$eltoken[0]];
    $cadatoken=array();
    $n_online=array();
    $propietarios=array();
    while($i<count($eltoken)){
        $cadatoken[]=$eltoken[$i];
        $n_online[]=$tokenynumero[$eltoken[$i]];
        $i++;
    }
    $token_online=$cadatoken;

        $token_partidas=array();
        $obsoletas=array();
    $get_partidas = $db->query("SELECT meme_token FROM meme_partida") or _error(SQL_ERROR_THROWEN);
    if($get_partidas->num_rows > 0) {
        while($frases = $get_partidas->fetch_assoc()) {

                $token_partidas[] = $frases['meme_token'];
                $enjuego=0;
                for($i=0;$i<count($token_online);$i++){
                    if($token_online[$i]==$frases['meme_token']){
                        $enjuego=1;
                    }
                }
                if(!$enjuego){
                    $obsoletas[]=$frases['meme_token'];
                    $db->query(sprintf("DELETE FROM meme_partida WHERE meme_token = %s", secure($frases['meme_token']))) or _error(SQL_ERROR_THROWEN);
                    $db->query(sprintf("DELETE FROM memes_jugadores_memes WHERE token = %s", secure($frases['meme_token']))) or _error(SQL_ERROR_THROWEN);
                    $db->query(sprintf("DELETE FROM memes_jugadores_frases WHERE token = %s", secure($frases['meme_token']))) or _error(SQL_ERROR_THROWEN);
                    $db->query(sprintf("DELETE FROM meme_votos WHERE partida = %s", secure($frases['meme_token']))) or _error(SQL_ERROR_THROWEN);
                    $db->query(sprintf("DELETE FROM meme_votos_globales WHERE token = %s", secure($frases['meme_token']))) or _error(SQL_ERROR_THROWEN);

                }

        }
    }
    $url="https://risas.wakeapp.org/sala/" . $token . "/";
	$return = array('url' => $url,'activas' => $eltoken,'obsoletas' => $obsoletas);
	echo return_json($return);

}
?>