<?php
/*
   Plugin Name: Maper
   Plugin URI: http://wiki.nosdigitais.teia.org.br/Maper
   Description: Publish maps as full pages, embbed code and in social networks.
   Author: LabMacambira.sf.net, Ethymos, Hacklab e Cartografáveis
   Version: 0.01-alpha
   Author URI: http://labmacambira.sourceforge.net
 */
wp_enqueue_script('jquery');

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
    if(isset($_POST['map']['zoom'])) // chave para gravar
    {
        update_post_meta($_POST['page_id'], '_mapasdevista', $_POST['map']);
        update_post_meta($_POST['page_id'], '_mapasdeusuario', get_current_user_id());
//        header('?page_id='.$_POST['page_id']);
        $pass=0;
    }

    if($_POST['apresentacao']){
    $templatefilename = "apresentacao.php";
    }
    elseif($_POST['iniciar']){
    $templatefilename = "oMapa.php";
    }
    elseif($_POST['titulo']){
        $templatefilename = "registraMapa.php";
        $_SESSION['inCentro']=$_POST['inCentro'];
        $_SESSION['Lat']=$_POST['inLat'];
        $_SESSION['Lng']=$_POST['inLng'];
        $_SESSION['MdVauto']=1;
        $_SESSION['Zoom']=$_POST['inZoom'];
    }
    elseif($_GET['page_id']){
        $pass=0;
    }

    if($pass){
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



