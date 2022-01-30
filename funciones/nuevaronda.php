<?php
require_once '../contador/counter-config.php';
require '../database.php';
require 'functiones.php';

	$token=$_REQUEST['token'];
	$id=$_REQUEST['id'];
	global $db;
	$situacion="Nocambia";

$duerme=rand(0.01,1);
sleep($duerme);

	$get_ifprimera = $db->query(sprintf("SELECT id_jugador FROM meme_jugadores_reset WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    


if($get_ifprimera->num_rows == 0) {  
    
    
	//Ver la actual, actualizar como antigua y coger otra
	$get_activa = $db->query(sprintf("SELECT id, frase FROM memes_jugadores_frases WHERE activa = 1 AND usada=0 AND token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_activa->num_rows > 0) {
        while($activas = $get_activa->fetch_assoc()) {
            $fraseid = $activas['id'];
            $db->query(sprintf("UPDATE memes_jugadores_frases SET activa = 0 , usada=1 WHERE id = %s AND token=%s", secure($fraseid), secure($token))) or _error(SQL_ERROR_THROWEN);
        }
    }
    $get_frase = $db->query(sprintf("SELECT frase, id FROM memes_jugadores_frases WHERE activa = 0  AND usada = 0 AND token = %s ORDER BY RAND() LIMIT 1", secure($token))) or _error(SQL_ERROR_THROWEN);
        if($get_frase->num_rows > 0) {
            while($frases = $get_frase->fetch_assoc()) {
                $situacion = $frases['frase'];
                $id_escogida=$frases['id'];
            }
            $db->query(sprintf("UPDATE memes_jugadores_frases SET activa = 1 WHERE id = %s AND token=%s", secure($id_escogida), secure($token))) or _error(SQL_ERROR_THROWEN);

        }


    $db->query(sprintf("INSERT INTO meme_jugadores_reset (id_jugador,token) VALUES (%s,%s)", secure($id), secure($token) )) or _error(SQL_ERROR_THROWEN);



    $votos=$_REQUEST['n_votos'];
    $propietarios=$_REQUEST['propietarios'];
	$votos = explode (",", $votos);
	$propietarios = explode (",", $propietarios); 
    for( $i = 0; $i < count($votos); $i++){
    	$get_si_existe = $db->query(sprintf("SELECT votos FROM meme_votos_globales WHERE nombre_jugador = %s AND token = %s", secure($propietarios[$i]), secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_si_existe->num_rows > 0) {
    	while($votitos = $get_si_existe->fetch_assoc()) {
                $nuevovoto = $votitos['votos']+ $votos[$i];
            }
        $db->query(sprintf("UPDATE meme_votos_globales SET votos = %s WHERE nombre_jugador = %s AND token=%s", secure($nuevovoto), secure($propietarios[$i]), secure($token))) or _error(SQL_ERROR_THROWEN);

    }else{
    	$db->query(sprintf("INSERT INTO meme_votos_globales (nombre_jugador,votos, token) VALUES (%s,%s,%s)", secure($propietarios[$i]), secure($votos[$i]), secure($token) )) or _error(SQL_ERROR_THROWEN);
    }
    }

    $db->query(sprintf("DELETE FROM meme_votos WHERE partida=%s", secure($token))) or _error(SQL_ERROR_THROWEN);


    $db->query(sprintf("UPDATE memes_jugadores_memes SET antigua = 1, votada = 0 WHERE token=%s AND votada=1", secure($token))) or _error(SQL_ERROR_THROWEN);

    //Si las imagenes son menos de 4--> Vuelvo a repartir random
    resetear_imagenes2($token);
    //$nuevasimagenes=get_imagenes($id,$token);
    

}


else{
    sleep(0.1);
    $get_activa = $db->query(sprintf("SELECT id_jugador FROM meme_jugadores_reset WHERE id_jugador = %s AND token = %s", secure($id), secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_activa->num_rows == 0) {
        $db->query(sprintf("INSERT INTO meme_jugadores_reset (id_jugador,token) VALUES (%s,%s)", secure($id), secure($token) )) or _error(SQL_ERROR_THROWEN);
    }

}

    $situacion=get_situacion($token);
    $situacionid=get_situacion1($token);
	$nuevasimagenes=get_imagenes($id,$token);
    
	$avg = array('partida' => 1, 'situacion' => $situacion, 'situacionid' => $situacionid, 'token' => $token, 'imagenes' => $nuevasimagenes[0], 'idimagen' => $nuevasimagenes[1], 'propiet' => $propietarios, 'votos' => $votos);
	echo return_json($avg);
?>