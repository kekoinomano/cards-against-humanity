<?php
/**
 * functions
 * 
 * @package Sngine
 * @author Zamblek
 */


/* ------------------------------- */
/* Core */
/* ------------------------------- */

/**
 * check_system_requirements
 * 
 * @return void
 */
function check_system_requirements() {
    /* set required php version*/
    $required_php_version = '5.5';
    /* check php version */
    $php_version = phpversion();
    if(version_compare( $required_php_version, $php_version, '>')) {
        _error("Installation Error", sprintf('<p class="text-center">Your server is running PHP version %1$s but Sngine requires at least %3$s.</p>', $php_version, $required_php_version));
    }
    /* check if mysqli enabled */
    if(!extension_loaded('mysqli')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "mysqli" extension which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    /* check if curl enabled */
    if(!extension_loaded('curl')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "cURL" extension which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    /* check if intl enabled */
    if(!extension_loaded('intl')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "intl" extension which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    /* check if json_decode enabled */
    if(!function_exists('json_decode')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "json_decode()" function which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    /* check if base64_decode enabled */
    if(!function_exists('base64_decode')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "base64_decode()" function which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    /* check if mail enabled */
    if(!function_exists('mail')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "mail()" function which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
    if(!function_exists('getimagesize')) {
        _error("Installation Error", '<p class="text-center">Your PHP installation appears to be missing the "getimagesize()" function which is required by Sngine.</p><small>Back to your server admin or hosting provider to enable it for you</small>');
    }
}


/**
 * get_licence_key
 * 
 * @param string $code
 * @return string
 */
//function get_licence_key($code) {
    /*$url = 'http://www.sngine.com/licence/v2/verify.php';
    $data = "code=".$code."&domain=".$_SERVER['HTTP_HOST'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0 Firefox/5.0');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $contents = curl_exec($curl);
    $status = curl_getinfo($curl);
    curl_close($curl);
    if($status['http_code'] == 200) {
        $contents = json_decode($contents, true);
        if($contents['error']) {
            throw new Exception($contents['error']['message'].' Error Code #'.$contents['error']['code']);
        }
        return $contents['licence_key'];
    } else {
        throw new Exception("Error Processing Request");
    }*/
    function get_licence_key($code) {
   return "";
}



/**
 * get_system_protocol
 * 
 * @return string
 */
function get_system_protocol() {
    $is_secure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $is_secure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
        $is_secure = true;
    }
    return $is_secure ? 'https' : 'http';
}


/**
 * get_system_url
 * 
 * @return string
 */
function get_system_url() {
    $protocol = get_system_protocol();
    $system_url =  $protocol."://".$_SERVER['HTTP_HOST'].BASEPATH;
    return rtrim($system_url,'/');
}


/**
 * check_system_url
 * 
 * @return void
 */
function check_system_url() {
    $protocol = get_system_protocol();
    $parsed_url = parse_url(SYS_URL);
    if( ($parsed_url['scheme'] != $protocol) || ($parsed_url['host'] != $_SERVER['HTTP_HOST']) ) {
        header('Location: '.SYS_URL);
    }
}


/**
 * redirect
 * 
 * @param string $url
 * @return void
 */
function redirect($url = '') {
    if($url) {
        header('Location: '.SYS_URL.$url);
    } else {
        header('Location: '.SYS_URL);
    }
    exit;
}



/* ------------------------------- */
/* Security */
/* ------------------------------- */

/**
 * secure
 * 
 * @param string $value
 * @param string $type
 * @param boolean $quoted
 * @return string
 */
function secure($value, $type = "", $quoted = true) {
    global $db;
    if($value !== 'null') {
        // [1] Sanitize
        /* Escape all (single-quote, double quote, backslash, NULs) */
        if(get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        /* Convert all applicable characters to HTML entities */
        $value = htmlentities($value, ENT_QUOTES, 'utf-8');
        // [2] Safe SQL
        $value = $db->real_escape_string($value);
        switch ($type) {
            case 'int':
                $value = ($quoted)? "'".intval($value)."'" : intval($value);
                break;
            case 'datetime':
                $value = ($quoted)? "'".set_datetime($value)."'" : set_datetime($value);
                break;
            case 'search':
                if($quoted) {
                    $value = (!is_empty($value))? "'%".$value."%'" : "''";
                } else {
                    $value = (!is_empty($value))? "'%%".$value."%%'" : "''";
                }
                break;
            default:
                $value = (!is_empty($value))? "'".$value."'" : "''";
                break;
        }
    }
    return $value;
}


/**
 * session_hash
 * 
 * @param string $hash
 * @return array
 */
function session_hash($hash) {
    /*$hash_tokens = explode('-', $hash);
    if(count($hash_tokens) != 6) {
        _error(__("Error"), __("Your session hash has been broken, Please contact Sngine's support!"));
    }
    $position = array_rand($hash_tokens);
    $token = $hash_tokens[$position];
    return array('token' => $token, 'position' => $position+1);*/
}


/**
 * _password_hash
 * 
 * @param string $password
 * @return string
 */
function _password_hash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


/**
 * get_hash_key
 * 
 * @param integer $length
 * @return string
 */
function get_hash_key($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    return $result;
}


/**
 * get_hash_token
 * 
 * @return string
 */
function get_hash_token() {
    return md5(time()*rand(1, 9999));
}



/* ------------------------------- */
/* Validation */
/* ------------------------------- */

/**
 * is_ajax
 * 
 * @return void
 */
function is_ajax() {
    if( !isset($_SERVER['HTTP_X_REQUESTED_WITH']) || ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') ) {
        redirect();
    }
}


/**
 * is_empty
 * 
 * @param string $value
 * @return boolean
 */
function is_empty($value) {
    if(strlen(trim(preg_replace('/\xc2\xa0/',' ',$value))) == 0) {
        return true;
    } else {
        return false;
    }
}



/* ------------------------------- */
/* Date */
/* ------------------------------- */

/**
 * set_datetime
 * 
 * @param string $date
 * @return string
 */
function set_datetime($date) {
    return date("Y-m-d H:i:s", strtotime($date));
}


/**
 * get_datetime
 * 
 * @param string $date
 * @return string
 */
function get_datetime($date) {
    return date("m/d/Y g:i A", strtotime($date));
}



/* ------------------------------- */
/* JSON */
/* ------------------------------- */

/**
 * _json_decode
 * 
 * @param string $string
 * @return string
 */
function _json_decode($string) {
    if(get_magic_quotes_gpc()) $string = stripslashes($string);
    return json_decode($string);
}


/**
 * return_json
 * 
 * @param array $response
 * @return json
 */
function return_json($response = array()) {
    header('Content-Type: application/json');
    exit(json_encode($response));
}



/* ------------------------------- */
/* Error */
/* ------------------------------- */

/**
 * _error
 * 
 * @return void
 */
function _error() {
    $args = func_get_args();
    if(count($args) > 1) {
        $title = $args[0];
        $message = $args[1];
    } else {
        switch ($args[0]) {
            case 'DB_ERROR':
                $title = "Database Error";
                $message = "<div class='text-left'><h1>"."Error establishing a database connection"."</h1>
                            <p>"."This either means that the username and password information in your config.php file is incorrect or we can't contact the database server at localhost. This could mean your host's database server is down."."</p>
                            <ul>
                                <li>"."Are you sure you have the correct username and password?"."</li>
                                <li>"."Are you sure that you have typed the correct hostname?"."</li>
                                <li>"."Are you sure that the database server is running?"."</li>
                            </ul>
                            <p>"."If you're unsure what these terms mean you should probably contact your host. If you still need help you can always visit the"." <a href='http://support.zamblek.com'>"."Sngine Support".".</a></p>
                            </div>";
                break;

            case 'SQL_ERROR':
                $title = __("Database Error");
                $message = __("An error occurred while writing to database. Please try again later");
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    $message .= "<br><br><small>This error function was called from line $line in file $file</small>";
                }
                break;

            case 'SQL_ERROR_THROWEN':
                $message = __("An error occurred while writing to database. Please try again later");
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    $message .= "<br><br><small>This error function was called from line $line in file $file</small>";
                }
                throw new Exception($message);
                break;

            case '404':
                header('HTTP/1.0 404 Not Found');
                $title = __("404 Not Found");
                $message = __("The requested URL was not found on this server. That's all we know");
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    $message .= "<br><br><small>This error function was called from line $line in file $file</small>";
                }
                break;

            case '400':
                header('HTTP/1.0 400 Bad Request');
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    exit("This error function was called from line $line in file $file");
                }
                exit;

            case '403':
                header('HTTP/1.0 403 Access Denied');
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    exit("This error function was called from line $line in file $file");
                }
                exit;
            
            default:
                $title = __("Error");
                $message = __("There is some thing went wrong");
                if(DEBUGGING) {
                    $backtrace = debug_backtrace();
                    $line=$backtrace[0]['line'];
                    $file=$backtrace[0]['file'];
                    $message .= "<br><br>"."<small>This error function was called from line $line in file $file</small>";
                }
                break;
        }
    }
    echo '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>'.$title.'</title>
                <style type="text/css">
                    html {
                        background: #f1f1f1;
                    }
                    body {
                        color: #555;
                        font-family: "Open Sans", Arial,sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .error-title {
                        background: #ce3426;
                        color: #fff;
                        text-align: center;
                        font-size: 34px;
                        font-weight: 100;
                        line-height: 50px;
                        padding: 60px 0;
                    }
                    .error-message {
                        margin: 1em auto;
                        padding: 1em 2em;
                        max-width: 600px;
                        font-size: 1em;
                        line-height: 1.8em;
                        text-align: center;
                    }
                    .error-message .code,
                    .error-message p {
                        margin-top: 0;
                        margin-bottom: 1.3em;
                    }
                    .error-message .code {
                        font-family: Consolas, Monaco, monospace;
                        background: rgba(0, 0, 0, 0.7);
                        padding: 10px;
                        color: rgba(255, 255, 255, 0.7);
                        word-break: break-all;
                        border-radius: 2px;
                    }
                    h1 {
                        font-size: 1.2em;
                    }
                    
                    ul li {
                        margin-bottom: 1em;
                        font-size: 0.9em;
                    }
                    a {
                        color: #ce3426;
                        text-decoration: none;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                    .button {
                        background: #f7f7f7;
                        border: 1px solid #cccccc;
                        color: #555;
                        display: inline-block;
                        text-decoration: none;
                        margin: 0;
                        padding: 5px 10px;
                        cursor: pointer;
                        -webkit-border-radius: 3px;
                        -webkit-appearance: none;
                        border-radius: 3px;
                        white-space: nowrap;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing:    border-box;
                        box-sizing:         border-box;

                        -webkit-box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
                        box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0,0,0,.08);
                        vertical-align: top;
                    }

                    .button.button-large {
                        height: 29px;
                        line-height: 28px;
                        padding: 0 12px;
                    }

                    .button:hover,
                    .button:focus {
                        background: #fafafa;
                        border-color: #999;
                        color: #222;
                        text-decoration: none;
                    }

                    .button:focus  {
                        -webkit-box-shadow: 1px 1px 1px rgba(0,0,0,.2);
                        box-shadow: 1px 1px 1px rgba(0,0,0,.2);
                    }

                    .button:active {
                        background: #eee;
                        border-color: #999;
                        color: #333;
                        -webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
                        box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
                    }
                    .text-left {
                        text-align: left;
                    }
                    .text-center {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="error-title">'.$title.'</div>
                <div class="error-message">'.$message.'</div>
            </body>
            </html>';
    exit;
}







/* ------------------------------- */
/* Modal */
/* ------------------------------- */

/**
 * modal
 * 
 * @return json
 */
function modal() {
    $args = func_get_args();
    switch ($args[0]) {
        case 'LOGIN':
            return_json( array("callback" => "modal('#modal-login')") );
            break;
        case 'MESSAGE':
            return_json( array("callback" => "modal('#modal-message', {title: '".$args[1]."', message: '".addslashes($args[2])."'})") );
            break;
        case 'ERROR':
            return_json( array("callback" => "modal('#modal-error', {title: '".$args[1]."', message: '".addslashes($args[2])."'})") );
            break;
        case 'SUCCESS':
            return_json( array("callback" => "modal('#modal-success', {title: '".$args[1]."', message: '".addslashes($args[2])."'})") );
            break;
        default:
            if(isset($args[1])) {
                return_json( array("callback" => "modal('".$args[0]."', ".$args[1].")") );
            } else {
                return_json( array("callback" => "modal('".$args[0]."')") );
            }
            break;
    }
}




/* ------------------------------- */
/* Text */
/* ------------------------------- */

/**
 * decode_urls
 * 
 * @param string $text
 * @return string
 */
function decode_urls($text) {
    $text = preg_replace('/(https?:\/\/[^\s]+)/', "<a target='_blank' href=\"$1\">$1</a>", $text);
    return $text;
}



/**
 * decode_text
 * 
 * @param string $string
 * @return string
 */
function decode_text($string) { 
    return base64_decode($string);
}





function crear_sala($jugador1,$jugador2,$jugador3,$jugador4,$jugador5) {
    global $db;
    $token= rand ( 1000000 , 9999999 );

    $db->query(sprintf("INSERT INTO meme_partida (meme_token, jugador1, jugador2, jugador3, jugador4, jugador5) VALUES (%s, %s, %s,%s, %s, %s)", secure($token, 'int'), secure($jugador1), secure($jugador2), secure($jugador3), secure($jugador4), secure($jugador5) )) or _error(SQL_ERROR_THROWEN);

    //$stmt->execute();
    return $token;
}
function crearsala($jugador1) {
    global $db;
    $repetido=true;
    while($repetido==true){
        $token= rand ( 1000 , 9999 );
        $repetido=comprobar_sala($token);
    }
    
    $db->query(sprintf("INSERT INTO meme_partida (meme_token, jugador1) VALUES (%s, %s)", secure($token, 'int'), secure($jugador1) )) or _error(SQL_ERROR_THROWEN);

    //$stmt->execute();
    return $token;
}
function comprobarelcodigo($codigo) {
    global $db;
    $followings=array();
    $unico="error";
    $get_followings = $db->query(sprintf("SELECT meme_token FROM meme_partida WHERE meme_token = %s", secure($codigo))) or _error(SQL_ERROR_THROWEN);
    if($get_followings->num_rows > 0) {
        while($following = $get_followings->fetch_assoc()) {
            $followings[] = $following['meme_token'];
            $unico=$following['meme_token'];
        }
    }
    else{
        $followings[] = 0;
    }
    return $followings;
}
function comprobar_sala($codigo) {
    global $db;
    $salas = $db->query(sprintf("SELECT meme_token FROM meme_partida WHERE meme_token = %s", secure($codigo))) or _error(SQL_ERROR_THROWEN);
    if($salas->num_rows > 0) {
        return true;
    }
    else{
        return false;
    }
}
 
function crear_tabla() {
    global $db;
    $db->query("CREATE TABLE 'meme_online' ('id' TEXT PRIMARY KEY NOT NULL, 'page_title' TEXT, 'page_url' TEXT, 'last_activity' INTEGER)") or _error($db->error);
}


function get_nombre($id, $sala) {
    global $db;
    $elnombre="";
    $nombre = $db->query(sprintf("SELECT nombre FROM meme_online WHERE id_online = %s AND page_url = %s", secure($id), secure($sala))) or _error(SQL_ERROR_THROWEN);
    if($nombre->num_rows > 0) {
        while($nombres = $nombre->fetch_assoc()) {
            $elnombre = $nombres['nombre'];
        }
    }
    return $elnombre;
}
function subir_memes($path){
    global $db;   
    $db->query(sprintf("INSERT INTO meme_respuestas (src) VALUES (%s)", secure($path) )) or _error(SQL_ERROR_THROWEN);
}
function get_imagenes($id, $token) {
    global $db;
    $src=array();
    $ids=array();
    $imagenes = $db->query(sprintf("SELECT src, nombre, iddelmeme FROM memes_jugadores_memes WHERE id_jugador = %s AND token = %s AND votada=0 AND antigua=0 ORDER BY RAND() LIMIT 4", secure($id), secure($token))) or _error(SQL_ERROR_THROWEN);
    if($imagenes->num_rows > 0) {
        while($nombres = $imagenes->fetch_assoc()) {
            $src[] = htmlspecialchars_decode($nombres['src']);
            $ids[] = htmlspecialchars_decode($nombres['iddelmeme']);
        }
    }
    $arraygordo=[$src,$ids];
    return $arraygordo;
}
function resetear_valores($id, $token) {
    global $db;
    $db->query(sprintf("DELETE FROM meme_votos WHERE id_voto = %s AND partida=%s", secure($id), secure($token))) or _error(SQL_ERROR_THROWEN);
    $db->query(sprintf("UPDATE memes_jugadores_memes SET antigua = 1 WHERE id_jugador = %s AND token=%s AND votada=1", secure($id), secure($token))) or _error(SQL_ERROR_THROWEN);
    
}
function versireset($id, $token) {
    global $db;
    $resetid=array();
    $get_reset=$db->query(sprintf("SELECT id_jugador FROM meme_jugadores_reset WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    $mismojugador=0;
    if($get_reset->num_rows > 0) {
            while($reset = $get_reset->fetch_assoc()) {
                $resetid[] = $reset['id_jugador'];
                if($id==$reset['id_jugador']){
                        $mismojugador++;
                }
            }
    }
    if($mismojugador==0){
         $db->query(sprintf("INSERT INTO meme_jugadores_reset (id_jugador,token) VALUES (%s,%s)", secure($id), secure($token) )) or _error(SQL_ERROR_THROWEN);
    }
    
}
function resetear_imagenes($token) {
    global $db;
    $nuevoprop=array();
    $get_id_meme = $db->query(sprintf("SELECT id_jugador FROM memes_jugadores_memes WHERE token = %s ORDER BY RAND()", secure($token))) or _error(SQL_ERROR_THROWEN);
        if($get_id_meme->num_rows > 0) {
            while($elpropietario = $get_id_meme->fetch_assoc()) {
                $nuevoprop[] = $elpropietario['id_jugador'];
            }
        }
    $get_id_meme = $db->query(sprintf("SELECT iddelmeme FROM memes_jugadores_memes WHERE token = %s ORDER BY RAND()", secure($token))) or _error(SQL_ERROR_THROWEN);
        if($get_id_meme->num_rows > 0) {
            $i=0;
            while($laid = $get_id_meme->fetch_assoc()) {
                $queid = $laid['iddelmeme'];
                $db->query(sprintf("UPDATE memes_jugadores_memes SET antigua = 0, votada = 0, id_jugador = %s WHERE iddelmeme = %s AND token=%s", secure($nuevoprop[$i]), secure($queid), secure($token))) or _error(SQL_ERROR_THROWEN);
                $i++;
            }
        }
    
}
function resetear_imagenes2($token) {
    global $db;
    $ids=array();
    $nombres=array();
    $jugadores_a_repartir=$db->query(sprintf("SELECT id_online, nombre FROM meme_online WHERE page_url = %s ORDER BY RAND()", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($jugadores_a_repartir->num_rows > 0) {
            $i=0;
            while($laid = $jugadores_a_repartir->fetch_assoc()) {
                $ids[] = $laid['id_online'];
                $nombres[] = $laid['nombre'];
            }
    }
    $path=array();
    $suid=array();
    $get_fotos=$db->query("SELECT src FROM meme_respuestas ORDER BY RAND()") or _error(SQL_ERROR_THROWEN);
    if($get_fotos->num_rows > 0) {
            while($fotos = $get_fotos->fetch_assoc()) {
                $path[] = $fotos['src'];
            }
    }
    $db->query(sprintf("DELETE FROM memes_jugadores_memes WHERE token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);

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
function subir_situacion($frase) {
    global $db;
    global $db;   
    $db->query(sprintf("INSERT INTO meme_preguntas (frase) VALUES (%s)", secure($frase) )) or _error(SQL_ERROR_THROWEN);
}
function subir_pregunta($frase) {
    global $db;
    global $db;   
    $db->query(sprintf("INSERT INTO meme_preguntas (frase) VALUES (%s)", secure($frase) )) or _error(SQL_ERROR_THROWEN);
}
function subir_respuesta($frase) {
    global $db;
    global $db;   
    $db->query(sprintf("INSERT INTO meme_respuestas (src) VALUES (%s)", secure($frase) )) or _error(SQL_ERROR_THROWEN);
}
function get_situacion($token) {
    global $db;
    global $db; 
    $situacion ="No hay más situaciones";  
    $get_activa = $db->query(sprintf("SELECT frase FROM memes_jugadores_frases WHERE activa = 1 AND usada = 0 AND token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_activa->num_rows > 0) {
        while($activas = $get_activa->fetch_assoc()) {
            $situacion = $activas['frase'];
        }
    }
    return $situacion;
}

function get_situacion1($token) {
    global $db;
    $get_activa = $db->query(sprintf("SELECT id FROM memes_jugadores_frases WHERE token = %s AND activa = 1 AND usada = 0",secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_activa->num_rows > 0) {
        while($activas = $get_activa->fetch_assoc()) {
            $situacion = $activas['id'];
        }
    }
    return $situacion;
}
function get_situacionprimera($token) {
    global $db;
    $situacion ="No hay más situaciones";  
    $get_activa = $db->query(sprintf("SELECT frase FROM memes_jugadores_frases WHERE activa = 1 AND usada = 0 AND token = %s", secure($token))) or _error(SQL_ERROR_THROWEN);
    if($get_activa->num_rows > 0) {
        while($activas = $get_activa->fetch_assoc()) {
            $situacion = $activas['frase'];
        }
    }else{
        $get_frase = $db->query(sprintf("SELECT frase, id FROM memes_jugadores_frases WHERE activa = 0  AND usada = 0 AND token = %s ORDER BY RAND() LIMIT 1", secure($token))) or _error(SQL_ERROR_THROWEN);
        if($get_frase->num_rows > 0) {
            while($frases = $get_frase->fetch_assoc()) {
                $situacion = $frases['frase'];
                $id_escogida=$frases['id'];
            }
            $db->query(sprintf("UPDATE memes_jugadores_frases SET activa = 1 WHERE id = %s AND token=%s", secure($id_escogida), secure($token))) or _error(SQL_ERROR_THROWEN);
        }
    }
    return $situacion;
}


?>

