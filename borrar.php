<?php
require 'database.php';
require __DIR__ . '/funciones/functiones.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title>La Churrileta</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación.">
  <meta name="author" content="Sergio Alvarez" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" media="screen" href="src/styles.css">
    <link rel="shortcut icon" href="src/ruleta.png" />
    <meta property="og:site_name" content="Churrileta">
<meta property="og:title" content="Churrileta" />
<meta property="og:description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación." />
<meta property="og:image" itemprop="image" content="https://churrileta.wakeapp.org/src/ruleta.png">
<meta property="og:type" content="website" />
<meta property="og:updated_time" content="1440432930" />
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<style>
table {
  width: 100%;
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
  padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = intval($_GET['q']);
echo $q;
//comprobarelcodigo1("8495961");

$token=crear_sala("Juan","Juan","Juan","Juan","Juan");


?>
</body>
</html>