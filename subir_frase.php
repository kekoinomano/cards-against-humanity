<?php
header('Content-Type: text/html; charset=utf-8');
require 'database.php';
require  'funciones/functiones.php';
if(isset($_POST["pregunta"])) {
  subir_pregunta($_POST["pregunta"]);
  echo "¡Subida!";
  echo "<a href='https://risas.wakeapp.org/nuevomeme.html'>Subir otra</a>";
  header("Location: https://risas.wakeapp.org/meter");
}
if(isset($_POST["respuesta"])) {
  subir_respuesta($_POST["respuesta"]);
  echo "¡Subida!";
  echo "<a href='https://risas.wakeapp.org/nuevomeme.html'>Subir otra</a>";
  header("Location: https://risas.wakeapp.org/meter");
}

?>