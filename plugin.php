<?php
/*
   Plugin Name: Maper
   Plugin URI: http://wiki.nosdigitais.teia.org.br/Maper
   Description: Publish maps as full pages, embbed code and in social networks.
   Author: LabMacambira.sf.net, Ethymos, Hacklab e Cartografáveis
   Version: 0.01-alpha
   Author URI: http://labmacambira.sourceforge.net
 */

//include('functions.php');                                             

//register_activation_hook(__FILE__, 'mapasdevista_activate');

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
    $plugindir = dirname( __FILE__ );
    $templatefilename = "inicial.php";
    if($_SESSION['etapa']=='apr'){
    $templatefilename = "apresentacao.php";
    }
    if($_POST['apresentacao']){
    $templatefilename = "apresentacao.php";
    }
    if($_POST['iniciar']){
    $templatefilename = "oMapa.php";
    }
    if($_POST['titulo']){
    $templatefilename = "titulo.php";
    }

    $return_template = $plugindir . '/themefiles/' . $templatefilename;
    do_theme_redirect($return_template);
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



