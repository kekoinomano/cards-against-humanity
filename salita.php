<?php
require 'database.php';
require  'funciones/functiones.php';
$sala=$_GET['token'];
$sala_existe=comprobar_sala($sala);
session_start();
$laid = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;
$nombre = (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : 0;
//$nombre=get_nombre($laid, $sala);
include "sala.tpl.php";
?>