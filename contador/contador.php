<?php

require_once 'counter-config.php';
require '../database.php';
require '../funciones/functiones.php';


$currentTime = time();

$gracePeriod = $currentTime - $secondsToConsiderOffline;


$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
$page_title = (isset($_REQUEST['page_title'])) ? $_REQUEST['page_title'] : '';

$page_url = (isset($_REQUEST['page_url'])) ? $_REQUEST['page_url'] : '';
$token = (isset($_REQUEST['token'])) ? $_REQUEST['token'] : '';
$nombre = (isset($_REQUEST['nombre'])) ? $_REQUEST['nombre'] : '';
$ready = (isset($_REQUEST['ready'])) ? $_REQUEST['ready'] : '';



$db->query(sprintf("DELETE FROM meme_online WHERE last_activity < %s OR id_online=%s", secure($gracePeriod), secure($id))) or _error(SQL_ERROR_THROWEN);

$db->query(sprintf("INSERT INTO meme_online (id_online, page_title, page_url, last_activity, nombre) VALUES(%s,%s,%s,%s,%s)", secure($id), secure($page_title), secure($token), secure($currentTime), secure($nombre))) or _error(SQL_ERROR_THROWEN);


$get_nombres=$db->query(sprintf("SELECT nombre, id_online, admin FROM meme_online WHERE page_url = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
$nombres=array();
$ids=array();
$eladmin=0;
$administrador="";
$n_nombres=0;
if($get_nombres->num_rows > 0) {
        while($elnombre = $get_nombres->fetch_assoc()) {
            $nombres[] = $elnombre['nombre'];
            $ids[] = $elnombre['id_online'];
            if($elnombre['admin']==1){
                $eladmin = $elnombre['id_online'];
                $administrador = $elnombre['nombre'];
            }
            $n_nombres++;
        }
}
if ($eladmin==0){
    $db->query(sprintf("UPDATE meme_online SET admin =1 WHERE id_online = %s", secure($id))) or _error(SQL_ERROR_THROWEN);
     $eladmin = $id;
     $administrador = $nombre;
}
if ($ready==1){
    $db->query(sprintf("UPDATE meme_partida SET ready =1 WHERE meme_token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);

    //Vemos si ya se han repartido los memes
    $get_memes=$db->query(sprintf("SELECT token FROM memes_jugadores_memes WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_memes->num_rows == 0) {
        $path=array();
        $suid=array();
        $get_fotos=$db->query("SELECT src FROM meme_respuestas ORDER BY RAND()") or _error(SQL_ERROR_THROWEN);
        if($get_fotos->num_rows > 0) {
            while($fotos = $get_fotos->fetch_assoc()) {
                $path[] = $fotos['src'];
            }
        }
        $nfotos=count($path);
        $njug=count($ids);
        $chunk=$nfotos/$njug;
        $chunk=floor($chunk);
        $fragmento=array_chunk($path, $chunk);
        $i=0;
        while($i<$njug){
            $laid=$ids[$i];
            $elnombre=$nombres[$i];
            $j=0;
            while($j<count($fragmento[$i])){
                $db->query(sprintf("INSERT INTO memes_jugadores_memes (token, id_jugador, nombre, src) VALUES(%s,%s,%s,%s)", secure($token), secure($laid), secure($elnombre), secure($fragmento[$i][$j]))) or _error(SQL_ERROR_THROWEN);
                $j++;
            }
            $i++;
        }

    }
    //Vemos si ya se han repartido los memes
    $get_repartidas=$db->query(sprintf("SELECT token FROM memes_jugadores_frases WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_repartidas->num_rows == 0) {
        $get_situaciones=$db->query("SELECT frase FROM meme_preguntas ORDER BY RAND()") or _error(SQL_ERROR_THROWEN);
        if($get_situaciones->num_rows > 0) {
                while($frases = $get_situaciones->fetch_assoc()) {
                    $limpia=html_entity_decode($frases['frase']);
                    $db->query(sprintf("INSERT INTO memes_jugadores_frases (token, frase) VALUES(%s,%s)", secure($token), secure($limpia))) or _error(SQL_ERROR_THROWEN);
                }
        }
    }
}

$get_ifready=$db->query(sprintf("SELECT meme_token FROM meme_partida WHERE meme_token = %s AND ready= 1", secure($token))) or _error(SQL_ERROR_THROWEN);

if($get_ifready->num_rows > 0) {
        $empezar=1;
}else{
     $empezar=0;
}



$counter= $db->query(sprintf('SELECT COUNT(*) AS visitors, COUNT(page_url) AS pages FROM meme_online WHERE page_url = %s', secure($page_url))) or _error(SQL_ERROR_THROWEN);


$count = $counter->fetch_assoc();

if ($count['visitors'] <= 1)
{
    $visitors = 1;
    $visitorWord = $visitorSingular;
}
else
{
    $visitors = $count['visitors'];
    $visitorWord = $visitorPlural;
}

if ($count['pages'] <= 1)
{
    $pages = 1;
    $pageWord = $pageSingular;
}
else
{
    $pages = $count['pages'];
    $pageWord = $pagePlural;
}
$nombres = array('nombres' => $nombres,'lasids' => $ids, 'sesion' => $id, 'visitantes' => $n_nombres, 'admin' => $eladmin, 'nombreadmin' => $administrador, 'listo' => $empezar , 'token' => $token , 'n_memes' => $fragmento);
echo return_json($nombres);



?>