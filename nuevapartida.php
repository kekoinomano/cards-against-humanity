<?php
require 'database.php';
require __DIR__ . '/funciones/functiones.php';
$jugadores=array();
if(isset($_POST["jugador1"])){
  $jugadores[0]=$_POST["jugador1"];
}else{
  $jugadores[0]=0;
}
if(isset($_POST["jugador2"])){
  $jugadores[1]=$_POST["jugador2"];
}else{
  $jugadores[1]=0;
}
if(isset($_POST["jugador3"])){
  $jugadores[2]=$_POST["jugador3"];
}else{
  $jugadores[2]=0;
}
if(isset($_POST["jugador4"])){
  $jugadores[3]=$_POST["jugador4"];
}else{
  $jugadores[3]=0;
}
if(isset($_POST["jugador5"])){
  $jugadores[4]=$_POST["jugador5"];
}else{
  $jugadores[4]=0;
}
$token=crear_sala($jugadores[0],$jugadores[1],$jugadores[2],$jugadores[3],$jugadores[4]);
$url="Location: https://risas.wakeapp.org/sala/" . $token . "/";
header($url);

//$followers=comprobarelcodigo('8495961');
//echo $followers;

?>