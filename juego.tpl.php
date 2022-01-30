<html>
  <head>
    <title>MeMeme</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación.">
  <meta name="author" content="Sergio Alvarez" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="https://risas.wakeapp.org/src/ruleta.png" />
    <link href='https://fonts.googleapis.com/css?family=Share Tech Mono' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Space Mono' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" media="screen" href="../../src/styles.css">
    <meta property="og:site_name" content="Churrileta">
<meta property="og:title" content="Churrileta" />
<meta property="og:description" content="Juego realizado por Eugenia y Sergio. Amantes de corazón y fetoneros de vocación." />
<meta property="og:image" itemprop="image" content="https://churrileta.wakeapp.org/src/ruleta.png">
<meta property="og:type" content="website" />
<meta property="og:updated_time" content="1440432930" />
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  </head>

  <body>

    <script type="text/javascript">
        var contador = 0;
    </script> 
<?php
 if ( !$sala_existe ) { ?> 
    <a href="https://risas.wakeapp.org">No existe la sala <?php echo $sala;?>, prueba de nuevo</a>
<?php }
else{
 ?>

    <div style="display: none;" id="havotado">Ha votao</div>
    <div style="display: none;" id="hadado">Ha dado</div>

<?php } ?>
<!--
<div class="round-time-bar" id="barra" data-style="smooth" style="--duration: 60;">
  <div class="tiempo" id="tiempo"></div>
</div>
-->

<div class="frase" id="situacion"><?php echo $situacion;?></div>
<div class="ranking" id="ranking"><i class="fas fa-trophy" onclick="mostrarresultados()" style="cursor: pointer;"></i></div>
<div class="nombre" id ="esperaronda" style="display: none;">Espera a la siguiente ronda</div>

    <div style="display: none;">Jugadores: <span id="jugadores">1</span></div>
    <div style="display: none;">Número de jugadores: <span id="numero"></span></div>

    <div id="faltanporelegirdiv" style="margin-left: 10px;">Esperando a: <span id="faltanporelegir"></span></div>
    <div id="faltanporvotardiv" style="display: none; margin-left: 10px;">Esperando a: <span id="faltanporvotar"></span></div>

    <div style="display: none;">Número de imagenes seleccionadas: <span id="numeroseleccion"></span></div>
    <div style="display: none;">Número de Votos: <span id="numerovotos"></span></div>


<div class="container2" id="elegirdiv" style="display: none;">
<div class="textito" style="display: none; text-align: center;" id="selecimg">Selecciona tu meme</div>
<div id="misimagenes"></div>
<div class="seleccionar" id="seleccionar">
    <div class="voto">Usar?<br>
        <span id="lasrc" style="display: none;"></span>
        <button class="comenzar2" onclick="si(document.getElementById('lasrc').innerHTML)">Si</button>
    </div>
</div>
</div>
<div class="container2" id="votardiv" style="display: none;">
<div class="textito" id ="votacion">Elige tu favorita</div>
<div class="imagenesparavotar" id="imagenesparavotar"></div>
<div class="votar" id="votar">
    <div class="voto">Votar?<br>

        <button class="comenzar2" onclick="updateVotos(this)" id="botonbotar">Si</button>
    </div>
</div>
</div>
<div id="ganadores" class="container2" style="display: none;">
<div id="espera" class="espera">Esperando a los demas jugadores</div>
<div id="listadelosganadores" style="text-align: center;"></div>
<div id="nuevaronda" class="nuevaronda"><button id="botonnuevaronda" onclick="nuevaronda(this)" class="botonnuevaronda">Nueva ronda</button></div>
</div>
<div class="mostrarresultados" id="mostrarresultados">
    <div class="contenedor"> 
        <div class="nombre" style="text-align: center; padding: 20px;">Ranking</div>
        <div id="losjugadores" style="text-align: center;"></div>
    <button class="botonfrase" style="margin: 15px auto; height: 55px;" onclick="cerrarresultados()">Cerrar</button>
    </div>
</div>
  </body>



<script type="text/javascript">
    var tiempo=10;
	var ready=0;
    var pulsado=0;
    var n_votos=[];
    var propietarios = [];
    var memes = <?php echo json_encode($memes); ?>;
    var idmemes = <?php echo json_encode($id_memes); ?>;
    nuevomeme =[];
    var idsituacion = <?php echo json_encode($idsituacion); ?>;;
    console.log(idmemes);
    function cargar_imagenes(x, id){
        console.log(x.length);
    var elementExists = document.getElementById("imagenes-propias");
    if(!elementExists && x.length>0){
        document.getElementById("elegirdiv").style.display="block"; 
        divdeimagenes = document.createElement('div');
        divdeimagenes.id="imagenes-propias";
        divdeimagenes.setAttribute("class","container2");
            document.getElementById("esperaronda").style.display="none";
            for (let i = 0; i < x.length; i++) {
                imagen= document.createElement('div');
                imagen.setAttribute("class","imagen");
                imagen.setAttribute("data-id",id[i]);
                imagen.setAttribute("onclick","seleccionar(this)"); 
                imagen.innerHTML=x[i];        
                divdeimagenes.appendChild(imagen);
            }
        nuevomeme=x;
        document.getElementById("selecimg").style.display="block";
        document.getElementById("misimagenes").appendChild(divdeimagenes);
        
    }
    if(x.length==0){
            document.getElementById("esperaronda").style.display="block";    
    }

    }
    window.onload = cargar_imagenes(memes, idmemes);

    function seleccionar(x){
        src=x.innerHTML;
        id=x.dataset.id;
        array= document.getElementsByClassName("imagen");
        i=0;
        while (i<array.length){
            array[i].style.background="white";
            array[i].style.color="black";
            i++;
        }
        x.style.background="black";
        x.style.color="white";
        //document.getElementById("seleccionar").style.display="block";
        document.getElementById("lasrc").innerHTML=src;
        updateSeleccion(id);
    }
    function si(x){
        updateSeleccion(x);
    }

    function updateSeleccion(x)
{
    var id = <?php echo json_encode($id); ?>;
    var nombre = <?php echo json_encode($nombre); ?>;
    var token = <?php echo json_encode($sala); ?>;
    var path = x;

    var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            const votadas = JSON.parse(xmlhttp.responseText);
            //console.log(votadas);

        }
    };

    xmlhttp.open('POST', '../funciones/actualizarseleccion.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('id=' + id + '&' + 'token=' + token+ '&' + 'nombre=' + nombre+ '&' + 'src=' + path);

}
var borrar_reseteo = 0;
var fallo=0;
    function contarjugadores()
{
    var id = <?php echo json_encode($id); ?>;
    var nombre = <?php echo json_encode($nombre); ?>;
    var token = <?php echo json_encode($sala); ?>;
    tiempo=tiempo-2;
    //document.getElementById('lasala').innerHTML = token;
    //document.getElementById('jugadores').innerHTML = nombre;

    var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            //console.log(nuevomeme);
            
            console.log(idmemes);
            const obj = JSON.parse(xmlhttp.responseText);
            console.log(obj);
            document.getElementById('jugadores').innerHTML=obj.nombres;
            document.getElementById('numero').innerHTML=obj.numero;
            document.getElementById('numeroseleccion').innerHTML=obj.numero_seleccion;
            document.getElementById('numerovotos').innerHTML=obj.numero_votos;
            
            //Si está en la sala de votacion y ya tiene una id en reseteo, la borro
            borrar_reseteo=0;

            
            //Lista de los que faltan por participar
            faltan =document.getElementById('faltanporelegir');
            var numero=0;
            let porelegir =[];
            for (let i = 0; i < obj.nombres.length; i++) {
                voto=0;
                for (let j = 0; j < obj.quien.length; j++) {
                    if(obj.quien[j]==obj.nombres[i]){
                        //Ya ha votao
                        voto=1;
                    }
                }
                if(voto==0){
                    porelegir[numero]=obj.nombres[i];
                    numero++;
                }
            }
            faltan.innerHTML= porelegir;
            //Lista de los que faltan por votar
            faltan =document.getElementById('faltanporvotar');
            var numero=0;
            let porvotar =[];
            for (let i = 0; i < obj.nombres.length; i++) {
                voto=0;
                for (let j = 0; j < obj.quienvoto.length; j++) {
                    if(obj.quienvoto[j]==obj.nombres[i]){
                        //Ya ha votao
                        voto=1;
                    }
                }
                if(voto==0){
                    porvotar[numero]=obj.nombres[i];
                    numero++;
                }
            }
            faltan.innerHTML= porvotar;
            //alert(tiempo);
            console.log(idsituacion);


            //Trofeos
            var elementExists = document.getElementById("lista-jugadores");
            if(elementExists){
                elementExists.remove();
            }
            //document.getElementById('lista-jugadores').remove();
            lista=document.createElement('div');
            lista.id="lista-jugadores";
            for (let i = 0; i < obj.ranking_nombre.length; i++) {
            document.getElementById('ranking').style.display="block";
            jugador= document.createElement('div');
            jugador.setAttribute("class","jugador");
            jugador.setAttribute("style","margin:10px auto;");
            jugador.innerHTML=obj.ranking_nombre[i] + ": " + obj.ranking_voto[i];
            lista.appendChild(jugador);
            }
            document.getElementById('losjugadores').appendChild(lista);
            //console.log(tiempo);
            if((obj.numero==obj.numero_seleccion && obj.n_delosmemes>0)){
                tiempo=10;
                //renovartiempo();
                empezar_votacion(obj.votadas, obj.quien, obj. quienid, obj.iddelmeme);
                borrar_reseteo=1;
            }
            if(obj.numero_votos==obj.numero){
                tiempo=10;
                //renovartiempo();
                mostrar_ganadores(obj.frases_ganadoras, obj.votos, obj.propietarios);
                boton=document.getElementById("botonnuevaronda");
                boton.setAttribute("data-votos",obj.votos);
                boton.setAttribute("data-nombres",obj.propietarios);
            }
            if(obj.si==0){
                cargar_imagenes(obj.imagenes, obj.idimagen);
            }
            /*if(obj.recargar==1){
                cargar_imagenes(obj.imagenes, obj.idimagen);
                document.getElementById("situacion").innerHTML=obj.situacion;
                idsituacion=obj.idsituacion;

            }*/



        }
    };

    xmlhttp.open('POST', '../contador/jugadores.php', true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('id=' + id + '&' + 'token=' + token+ '&' + 'nombre=' + nombre+ '&' + 'memes=' + nuevomeme.length+ '&' + 'borrar_reseteo=' + borrar_reseteo+ '&' + 'fallo=' + fallo+ '&' + 'idsituacion=' + idsituacion+ '&' + 'idmemes=' + idmemes);
    setTimeout(contarjugadores, 2000);

}
contarjugadores();


function empezar_votacion(x, nombre, id, idmeme){
    //console.log(x);
    //document.body.scrollTop = document.documentElement.scrollTop = 0;
    document.getElementById("faltanporelegirdiv").style.display="none";
    document.getElementById("elegirdiv").style.display="none";
    document.getElementById("votardiv").style.display="flex";
    document.getElementById("faltanporvotardiv").style.display="block";
    var elementExists = document.getElementById("imagenes-votacion");
    if(!elementExists){
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        document.getElementById("votacion").style.display="block";
        document.getElementById("seleccionar").style.display="none";
        document.getElementById("misimagenes").style.display="none";
        divdeimagenes = document.createElement('div');
        divdeimagenes.id="imagenes-votacion";
        divdeimagenes.setAttribute("class","container2");
        for (let i = 0; i < x.length; i++) {
            imagen= document.createElement('div');
            imagen.setAttribute("class","imagen");
            imagen.innerHTML=x[i];
            imagen.setAttribute("data-nombre",nombre[i]);
            imagen.setAttribute("data-id",id[i]);
            imagen.setAttribute("data-idmeme",idmeme[i]);
            imagen.setAttribute("onclick","votarimagen(this)");          
            divdeimagenes.appendChild(imagen);
        }
        document.getElementById("imagenesparavotar").appendChild(divdeimagenes);
    }

}
    function votarimagen(x){
        array= document.getElementsByClassName("imagen");
        i=0;
        while (i<array.length){
            array[i].style.background="white";
            array[i].style.color="black";
            i++;
        }
        x.style.background="black";
        x.style.color="white";
        //document.getElementById("votar").style.display="block";
        boton=document.getElementById("botonbotar");
        boton.setAttribute("data-nombre",x.dataset.nombre);
        boton.setAttribute("data-id",x.dataset.id);
        boton.setAttribute("data-idmeme",x.dataset.idmeme);
        boton.setAttribute("data-src",x.innerHTML); 
        updateVotos(boton);
    }
function updateVotos(x){

        var propietario = x.dataset.nombre;
        var path = x.dataset.src;
        var idmeme = x.dataset.idmeme;
        var id = <?php echo json_encode($id); ?>;
        var nombre = <?php echo json_encode($nombre); ?>;
        var token = <?php echo json_encode($sala); ?>;
        var havotado = document.getElementById("havotado");
        //if (havotado.innerHTML!=1){
        var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                const votadas = JSON.parse(xmlhttp.responseText);
                //console.log(votadas);
                havotado.innerHTML=votadas.havotado;
                espera();

            }
        };

        xmlhttp.open('POST', '../funciones/actualizarvotos.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send('id=' + id + '&' +'propietario=' + propietario + '&' + 'token=' + token+ '&' + 'nombre=' + nombre+ '&' + 'src=' + path+ '&' + 'idmeme=' + idmeme);

}
function mostrar_ganadores(src, n_votos, propietarios){
	//contarjugadores();
    document.getElementById("ganadores").style.display="flex";
    document.getElementById("faltanporvotardiv").style.display="none";
    document.getElementById("votardiv").style.display="none";
    var elementExists = document.getElementById("lista-ganadores");
    if(!elementExists){
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        listaganadores= document.createElement('div');
        listaganadores.id="lista-ganadores";
    for (let i = 0; i < src.length; i++) {
            console.log(n_votos[i] + " para " + propietarios[i] + " por " + src[i]);
            ganadores= document.createElement('div');
            if(n_votos[i]>1){
                ganadores.innerHTML="<div class='nombre'>" + n_votos[i] + " votos para " + propietarios[i] + " por:</div>";
            }else{
                ganadores.innerHTML="<div class='nombre'>" +n_votos[i] + " voto para " + propietarios[i] + " por:</div>";
            }
            imagen = document.createElement('div');
            imagen.innerHTML=src[i];
            imagen.setAttribute("class","imagen");
            ganadores.appendChild(imagen);
            listaganadores.appendChild(ganadores);

        }
    
    document.getElementById("listadelosganadores").appendChild(listaganadores);
    //listaganadores.appendChild(ganadores);
    }
    var token =<?php echo json_encode($sala); ?>;
    document.getElementById("nuevaronda").style.display="block";

    var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                const votadas = JSON.parse(xmlhttp.responseText);
                console.log(votadas);
                

            }
        };

        xmlhttp.open('POST', '../funciones/actualizarvotosglobales.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send('votos=' + n_votos + '&' +'propietarios=' + propietarios + '&' +'token=' + token);
    //window.location.href = "https://risas.wakeapp.org/juego/" + <?php echo json_encode($sala); ?>;

}
var listos=0;
function nuevaronda(x){
        pulsado=1;
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        var id = <?php echo json_encode($id); ?>;
        var frase = <?php echo json_encode($situacion); ?>;
        var token = <?php echo json_encode($sala); ?>;
        var hadado = document.getElementById("hadado");
        var situacion = document.getElementById("situacion");
        var xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                const votadas = JSON.parse(xmlhttp.responseText);
                console.log(votadas);
                hadado.innerHTML=votadas.partida;
                situacion.innerHTML=votadas.situacion;
                idsituacion=votadas.situacionid;
                idmemes=votadas.idimagen;
                ganadores= document.getElementById("lista-ganadores");
                propias= document.getElementById("imagenes-propias");
                imagenes= document.getElementById("imagenes-votacion");
                ganadores.remove();
                if(propias){
                    propias.remove()
                }
                if(imagenes){
                    imagenes.remove()
                }
                
                cargar_imagenes(votadas.imagenes, votadas.idimagen);
                document.getElementById("nuevaronda").style.display="none";
                document.getElementById("votacion").style.display="block";
                document.getElementById("faltanporelegirdiv").style.display="block";
                document.getElementById("seleccionar").style.display="none";
                document.getElementById("misimagenes").style.display="block";
                document.getElementById("elegirdiv").style.display="block";
                document.getElementById("ganadores").style.display="none";
                document.getElementById("votar").style.display="none";

            }
        };

        xmlhttp.open('POST', '../funciones/nuevaronda.php', true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send('n_votos=' + x.dataset.votos + '&' + 'propietarios=' + x.dataset.nombres+ '&' +'id=' + id + '&' + 'token=' + token+ '&' + 'frase=' + frase);
        //setTimeout(nuevaronda, 5000);
        //contarjugadores();

}

//nueva_ronda();
function espera(){
    //document.getElementById("espera").style.display="block";
}
function mostrarresultados(){
    document.getElementById("mostrarresultados").style.display="block";
}
function cerrarresultados(){
    document.getElementById("mostrarresultados").style.display="none";
}
function renovartiempo(){
    barra=document.getElementById("tiempo");
    barra.style.animation="none";
    barra.style.animation="roundtime calc(10 * 1s) linear forwards";

}



</script>
</html>