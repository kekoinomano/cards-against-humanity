
<html>
  <head>
    <title>MeMeme</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación.">
  <meta name="author" content="Sergio Alvarez" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="shortcut icon" href="https://risas.wakeapp.org/src/ruleta.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" media="screen" href="../../src/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta property="og:site_name" content="Churrileta">
<meta property="og:title" content="Churrileta" />
<meta property="og:description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación." />
<meta property="og:image" itemprop="image" content="https://churrileta.wakeapp.org/src/ruleta.png">
<meta property="og:type" content="website" />
<meta property="og:updated_time" content="1440432930" />
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  </head>

  <body>

<?php
 if ( !$sala_existe ) { ?> 
    <a href="https://risas.wakeapp.org">No existe la sala <?php echo $sala;?>, prueba de nuevo</a>
<?php }
else{
 ?>
    <div class="frase">Sala: <?php echo $sala;?></div>
    <div class="container2" style="margin-top: 50px;">
    <div class="nombre">Número de jugadores: <span id="elnumero">1</span></div>
    <div id="losjugadores"></div>
    <div id="admin" class="admin"><button onclick="comenzar()" class="comenzar">Comenzar juego</button></div>
<div id="esperando"></div>
</div>
<?php } ?>
  </body>



<script type="text/javascript">
	var ready=0;
var onlineVisitorsCounterScriptPath = function ()
{
    var scripts = document.getElementsByTagName('script');
    var path = '';
    if (scripts && scripts.length > 0)
    {
        for (var i in scripts)
        {
            if (scripts[i].src && scripts[i].src.match(/\/counter\.js$/))
            {
                path = scripts[i].src.replace(/(.*)\/counter\.js$/, '$1');
                break;
            }
        }
    }
    return path;
};
function comenzar(){
	ready=1;
}
function updateOnlineVisitorsCounter()
{
	var id = <?php echo json_encode($laid); ?>;
    var nombre = <?php echo json_encode($nombre); ?>;
    var token = <?php echo json_encode($sala); ?>;
    //document.getElementById('lasala').innerHTML = token;
    //document.getElementById('elnumero').innerHTML = nombre;
    var page_title = window.document.title;
    var page_url = window.location.href;
    var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            const obj = JSON.parse(xmlhttp.responseText);
            console.log(obj);
            console.log(obj.n_memes);
            document.getElementById('elnumero').innerHTML = obj.visitantes;
            //document.getElementById('losjugadores').innerHTML = obj.nombres;
            var elementExists = document.getElementById("lista-jugadores");
            if(elementExists){
                elementExists.remove();
            }
            //document.getElementById('lista-jugadores').remove();
            lista=document.createElement('div');
            lista.id="lista-jugadores";
            for (let i = 0; i < obj.nombres.length; i++) {
            jugador= document.createElement('div');
            jugador.setAttribute("class","jugador");
            if (obj.lasids[i]==obj.admin){
                jugador.innerHTML=obj.nombres[i] + " " + " <i class='fas fa-crown' style='color: white;'></i>"; 
            }else{
                jugador.innerHTML=obj.nombres[i];
            }          
            lista.appendChild(jugador);
            }
            document.getElementById('losjugadores').appendChild(lista);
            if(obj.admin==obj.sesion){
                document.getElementById('admin').style.display="block";
            }
            if(obj.listo==1){
            	window.location.href ="https://risas.wakeapp.org/juego/" + obj.token;
            }
            if(obj.sesion!=obj.admin){
                console.log("e");
                var elementExists = document.getElementById("esperando-admin");
                if(!elementExists){
                console.log("u");
                esperandoadmin = document.createElement('div');
                esperandoadmin.id="esperando-admin";
                esperandoadmin.innerHTML="Esperando a que " + obj.nombreadmin + " empiece";
                document.getElementById("esperando").appendChild(esperandoadmin);
                }
            }
        }
    };

    xmlhttp.open('POST', onlineVisitorsCounterScriptPath() +  '/contador/contador.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('id=' + id + '&' +'page_title=' + encodeURIComponent(page_title) + '&' + 'page_url=' + encodeURIComponent(page_url)+ '&' + 'token=' + encodeURIComponent(token)+ '&' + 'nombre=' + encodeURIComponent(nombre)+ '&' + 'ready=' + ready);
    setTimeout(updateOnlineVisitorsCounter, 3000);

}

updateOnlineVisitorsCounter();




</script>
</html>