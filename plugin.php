<?php
/*
   Plugin Name: Maper
   Plugin URI: http://wiki.nosdigitais.teia.org.br/Maper
   Description: Publish maps as full pages, embbed code and in social networks.
   Author: LabMacambira.sf.net, Ethymos, Hacklab e Cartografáveis
   Version: 0.01-alpha
   Author URI: http://labmacambira.sourceforge.net
 */

// load the local copy of jQuery in the footer
// or load the Google API copy in the footer

add_action('init', 'init_sessions', 1);                                
function init_sessions() {                                             
    if (!session_id()) {
        session_start();
    }
}                                                           

add_action('wp_logout', 'myEndSession'); 
add_action('wp_login', 'myEndSession');                                
function myEndSession() {        
            session_destroy ();
}
                 
add_action("template_redirect", 'my_theme_redirect');
function my_theme_redirect() {
    global $wp;
    $pass=1;
    $plugindir = dirname( __FILE__ );
    $templatefilename = "inicial.php";

    if($_POST['apresentacao']){ // inicial.php sends us here
    $templatefilename = "apresentacao.php";
    unset($_SESSION['map']);
    unset($_SESSION['page_id']);
    }
    elseif($_POST['colabore']){  // inicial.php sends us here
    $templatefilename = "colabore.php";
    }
    elseif($_POST['contrate']){ // inicial.php sends us here
    $templatefilename = "contrate.php";
    }

    elseif($_GET['meus_mapas']){ // inicial.php sends us here
    $templatefilename = "meus_mapas.php";
    }

    elseif($_GET['mdv']){ // inicial.php sends us here
    $templatefilename = "mdv.php";
    }
    elseif($_GET['funciona']){ // inicial.php sends us here
    $templatefilename = "funciona.php";
    }
    elseif($_GET['contato']){ // inicial.php sends us here
    $templatefilename = "contato.php";
    }

    elseif($_POST['iniciar']){ // apresentacao.php sends us here
    // DAQUI PARTIMOS PARA uso do $_SESSION 
    // na manutenção dos dados do mapa
    $templatefilename = "oMapa.php";
    }
    // SEMPRE
    // 1) Atualizar o $_SESSION (da pagina do mapa pra frente)
    //elseif($_POST['fase']=='titulo' or $_SESSION['step']=='post'){ // Deprecated
    //error_log( print_r($_POST, true) );
    elseif($_POST['fase']=='titulo' or $_SESSION['step']=='post'){ // Deprecated
        //$templatefilename = "registraMapa.php";
        $_SESSION['map']=$_POST['map'];
        $_SESSION['page_id']=$_POST['page_id'];
        $_SESSION['inCentro']=$_POST['inCentro'];
        $_SESSION['foo']=$_POST;
        $_SESSION['Lat']=$_POST["map"]['coord']['lat'];
        $_SESSION['Lng']=$_POST["map"]['coord']['lng'];
        $_SESSION['MdVauto']=1; // DEPRECATED
        $_SESSION['Zoom']=$_POST["map"]['zoom'];
        $_SESSION['Terrain']=$_POST['map']['type'];
        // Variavel Terrain_ para ativar o mapa
        if($_SESSION['Terrain']=='road'){
            $_SESSION['Terrain_']='roadmap';
        }
        else{
            $_SESSION['Terrain_']=$_SESSION['Terrain'];
        }
    }

    // 2) ATUALIZAR O BD (da pagina do mapa pra frente)
    if(isset($_POST['submit_map'])) // chave para gravar (botao eh sempre acionado por js)
    {
        update_post_meta($_POST['page_id'], '_mapasdevista', $_POST['map']);
        update_post_meta($_POST['page_id'], '_mapasdeusuario', get_current_user_id());
        error_log("YYYYYYYEYYYYY7");
        if($_POST['fase']=='titulo'){
            $_SESSION['step']='post';
            $templatefilename = "titulo.php";
        }
        elseif($_POST['fase']=='postsMarkers' and $_SESSION['step'] == 'post'){
        error_log("YYYYYYYEYYYYY8");
            $location = array();
            $location['lat'] = floatval(sprintf("%f", $_POST['markerLat']));
            $location['lon'] = floatval(sprintf("%f", $_POST['markerLng']));
            $defaults = array(
                    'post_type'             => 'post',
                    'post_author'           => get_current_user_id(),
                    'post_title'    => 'Título do post ' . strval(rand(1,100000)),
                    'post_status'   => 'publish',
                    );
            $post_id = wp_insert_post( $defaults , $wp_error);
            update_post_meta($post_id, "_mpv_inmap", $_POST['page_id']);
            update_post_meta($post_id, '_mpv_location', $location);
            $templatefilename = 'post.php';
        }
        elseif($_SESSION['step']=='post'){
        error_log("YYYYYYYEYYYYY6");
            $templatefilename = 'post.php';
            //$_SESSION['step']='filtros'; // desabilitei para ficar loop no post
        }
    }

    elseif($_GET['page_id']){
        $pass=0;
    }

    if($pass>=1){
        $return_template = $plugindir . '/themefiles/' . $templatefilename;
        do_theme_redirect($return_template);
    }

}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}


//header('?page_id='.$_POST['page_id']);
