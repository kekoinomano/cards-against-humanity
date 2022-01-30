<?php

require_once 'counter-config.php';
require '../database.php';
require '../funciones/functiones.php';


$currentTime = time();
$secondsToConsiderOffline=10;
$gracePeriod = $currentTime - $secondsToConsiderOffline;


$id = $_REQUEST['id'];
$page_title = (isset($_REQUEST['page_title'])) ? $_REQUEST['page_title'] : '';
$token = (isset($_REQUEST['token'])) ? $_REQUEST['token'] : '';
$nombre = (isset($_REQUEST['nombre'])) ? $_REQUEST['nombre'] : '';
$n_delosmemes=array();
$n_delosmemes=$_REQUEST['memes'];

if (isset($id)){
    $db->query(sprintf("DELETE FROM meme_online WHERE last_activity < %s", secure($gracePeriod))) or _error(SQL_ERROR_THROWEN);

    $db->query(sprintf("UPDATE meme_online SET last_activity = %s WHERE id_online = %s", secure($currentTime), secure($id))) or _error(SQL_ERROR_THROWEN);


    $get_nombres=$db->query(sprintf("SELECT nombre, id_online FROM meme_online WHERE page_url = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    $nombres=array();
    $numero=0;
    if($get_nombres->num_rows > 0) {
            while($nombreq = $get_nombres->fetch_assoc()) {
                $get_sitienecartas=$db->query(sprintf("SELECT id_jugador FROM memes_jugadores_memes WHERE id_jugador = %s AND token = %s", secure($nombreq['id_online']), secure($token))) or _error(SQL_ERROR_THROWEN);
                if($get_sitienecartas->num_rows > 0){
                        $numero++;
                        $nombres[] = $nombreq['nombre'];
                }
            }
    }
    $get_imagenes=$db->query(sprintf("SELECT iddelmeme, nombre, src, id_jugador FROM memes_jugadores_memes WHERE token = %s AND votada = 1 AND antigua = 0", secure($token))) or _error(SQL_ERROR_THROWEN);
    $path=array();
    $quien=array();
    $quienid=array();
    $iddelmeme=array();
    $n_imagenes=0;
    if($get_imagenes->num_rows > 0) {
            while($imagen = $get_imagenes->fetch_assoc()) {
                $path[] = htmlspecialchars_decode($imagen['src']);
                $quien[] = $imagen['nombre'];
                $quienid[] = $imagen['id_jugador'];
                $iddelmeme[] = $imagen['iddelmeme'];
                $n_imagenes++;
            }
    }
    $get_votos=$db->query(sprintf("SELECT * FROM meme_votos WHERE partida = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    $pathvoto=array();
    $pathyvotos=array();
    $nombrevoto=array();
    $nombrepropietario=array();
    $votos_recibidos=array();
    $n_votos=0;
    if($get_votos->num_rows > 0) {
            while($imagen = $get_votos->fetch_assoc()) {
                $pathvoto[] = $imagen['id_voto'];
                $nombrevoto[] = $imagen['voto'];
                $nombrepropietario[] = $imagen['propietario'];
                $n_votos++;
            }
    }
    $pathyvotos=array_count_values($pathvoto);
    arsort($pathyvotos, SORT_NUMERIC);
    $ident=array_keys($pathyvotos);
    $i=0;
    $primera=$pathyvotos[$ident[0]];
    $elpath=array();
    $votos=array();
    $propietarios=array();
    $frases_ganadoras=array();
    while($i<count($ident)){
        $elpath[]=$ident[$i];
        $votos[]=$pathyvotos[$ident[$i]];
        $get_propietario=$db->query(sprintf("SELECT propietario, src FROM meme_votos WHERE id_voto = %s", secure($ident[$i]))) or _error(SQL_ERROR_THROWEN);
        if($get_propietario->num_rows > 0) {
            while($prop = $get_propietario->fetch_assoc()) {
                $unpropietario = $prop['propietario'];
                $frase_ganadora = htmlspecialchars_decode($prop['src']);
            }
        }
        $propietarios[]=$unpropietario;
        $frases_ganadoras[]=$frase_ganadora;
        $i++;
    }


    $resetid=array();
    $get_reset=$db->query(sprintf("SELECT id_jugador FROM meme_jugadores_reset WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    $n_reset=0;
    if($get_reset->num_rows > 0) {
            while($reset = $get_reset->fetch_assoc()) {
                $resetid[] = $reset['id_jugador'];
                $n_reset++;
            }
    }

    $get_ranking=$db->query(sprintf("SELECT nombre_jugador, votos FROM meme_votos_globales WHERE token = %s ORDER BY votos DESC", secure($token))) or _error(SQL_ERROR_THROWEN);
    $ranking_nombre=array();
    $ranking_voto=array();
    if($get_ranking->num_rows > 0) {
            while($dato = $get_ranking->fetch_assoc()) {
                $ranking_nombre[] = $dato['nombre_jugador'];
                $ranking_voto[] = $dato['votos'];
            }
    }
    if($n_reset==$numero || $n_reset>$numero){
        $db->query(sprintf("DELETE FROM meme_jugadores_reset WHERE token=%s", secure($token))) or _error(SQL_ERROR_THROWEN);
    }
    $idmemes=$_REQUEST['idmemes'];
    $idmemes=explode (",", $idmemes);
    $si=0;
    for($i=0;$i<count($idmemes);$i++){
       $get_iduser=$db->query(sprintf("SELECT id_jugador FROM memes_jugadores_memes WHERE token = %s AND iddelmeme = %s", secure($token), secure($idmemes[$i]))) or _error(SQL_ERROR_THROWEN);
        if($get_iduser->num_rows > 0) {
            $si=1;
        }
    }
    if($si==0){
        $nuevasimagenes=get_imagenes($id,$token);
    }
    $get_idsituacion=$db->query(sprintf("SELECT id, frase FROM memes_jugadores_frases WHERE token = %s AND activa = 1", secure($token))) or _error(SQL_ERROR_THROWEN);
        if($get_idsituacion->num_rows > 0) {
            while($lasituacion = $get_idsituacion->fetch_assoc()) {
            $idsituacion=$lasituacion['id'];
            $situacion=$lasituacion['frase'];
            }
        }
    $recargar=0;
    if($_REQUEST['idsituacion']!==$idsituacion){
        $recargar=1;
    }

    $nombres = array('numero' => $numero,'nombres' => $nombres,'iddelmeme' => $iddelmeme,'votadas' => $path,'quien' => $quien,'quienid' => $quienid,'numero_seleccion' => $n_imagenes,'path_votadas' => $elpath,'votos' => $votos,'numero_votos' => $n_votos,'quienvoto' => $nombrevoto,'propietarios' => $propietarios,'n_reset' => $n_reset,'id_reset' => $resetid,'ranking_nombre' => $ranking_nombre,'ranking_voto' => $ranking_voto,'frases_ganadoras' => $frases_ganadoras,'n_delosmemes' => $n_delosmemes,'situacion' => $situacion, 'reseteo' => $_REQUEST['borrar_reseteo'], 'imagenes' => $nuevasimagenes[0], 'idimagen' => $nuevasimagenes[1], 'id_situacion' => $idsituacion, 'fallo' => $fallo, 'si' => $si, 'recargar' => $recargar);
    echo return_json($nombres);
}else{
    $nombres = array('error' => "No id");
    echo return_json($nombres);
}



?>