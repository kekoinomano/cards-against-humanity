<?php
require 'database.php';
require  'funciones/functiones.php';
$sala=$_GET['token'];
$sala_existe=comprobar_sala($sala);
session_start();
$id = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;
$nombre = (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : "";
$nombre=$_SESSION['nombre'];
//resetear_valores($id,$sala);
if($nombre=="" || $id==0){
	echo "Sesion caducada, <a href='https://risas.wakeapp.org/'>Volver</a>";
}
versireset($id,$sala);
$situacion=get_situacionprimera($sala);
$idsituacion=get_situacion1($sala);
$array=get_imagenes($id, $sala);
$memes=$array[0];
$id_memes=$array[1];

include "juego.tpl.php";
?>