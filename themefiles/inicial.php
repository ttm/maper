<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }

  nav{
display:table;
margin: 30px 50px 50px 50px;
overflow:auto;
         border-left: solid 1px #ccc;
         z-index: 50;
         position: absolute;
         right:50px;
  }

nav li a,
    nav:hover li.active a{
color: #ccc;
       text-decoration:none;
padding: 0 10px;
background: black;
            font-size: 2em;
            text-transform:uppercase;
            font-family: Arial, Verdana, Sans-serif;
border: solid 1px #ccc;
        border-width: 1px 1px 1px 0;
    }

nav li{
float:left;
padding:0;
        list-style:none;
}

nav li.active a,
    nav li a:hover,
    nav:hover li.active a:hover{
background: #ccc;
color:white;
    }

nav li a{ /* Transition-effects */
transition: all 0.4s linear;
            -o-transition: all 0.4s linear;
            -ms-transition: all 0.4s linear;
            -moz-transition: all 0.4s linear;
            -webkit-transition: all 0.4s linear;
}


</style>
<script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
function initialize() {
  var myOptions = {
    zoom: 10,
    center: new google.maps.LatLng(-23.564298867964755, 313.3961061411132),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
//    mapTypeControl: true,
//    mapTypeControlOptions: {
//        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
//        position: google.maps.ControlPosition.BOTTOM_CENTER
//    },
    disableDefaultUI: true
    
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"),
      myOptions);
}
</script>
</head>
<body onload="initialize()" style="overflow:hidden">
<div style="position:absolute;right:20px; margin:80px 10px 0 0; z-index:200; background: gray; background-opacity:.1; padding: 13px;">
<? 
wp_enqueue_script('jquery');
if(get_current_user_id())
{ ?>
    <p>logado. <a href="wp-login.php?action=logout">Sair</a><br />userID = <? echo get_current_user_id(); ?>
<? }
else
{
    jfb_output_facebook_callback();
    jfb_output_facebook_init();
    jfb_output_facebook_btn();
}
 ?>
</div>
<div id="logo" style="position:absolute;margin:50px 0 0 50px;height:200px;width:200px; z-index:50;background: white;opacity:.83">
<p style="position:relative; top:30%;left:30%">LOGO</p></div>
  <div id="map_canvas" style="width:100%; height:100%; z-index:1; position:relative; float:left"></div>
  <div id="transpbox" style="width:100%; height:100%;background-color:#ffffff;opacity:0.4; z-index:10; position: absolute"></div>

  <nav>
  <li class="active">
  <a href="#">Mapa de vista</a>
  </li>
  <li>
  <a href="#">Como funciona</a>
  </li>
  <li>
  <a href="#">Contato</a>
  </li>
  <li>
</nav>


<div style="align:center">
    <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
        <input id="comeceAquiBtn" value="Comece seu mapa aqui" name="apresentacao" style="position:absolute;left:30%;top:15%;z-index:20;height:200px;width:200px;opacity:0.7" type="submit" />
    </form>
    <form method="link" action="colabore.php">
        <input id="colaboreBtn" value="Colabore em algum mapa" style="position:absolute;left:50%;top:15%;z-index:20;height:200px;width:200px;opacity:0.7" type="submit" />
    </form>
    <form method="link" action="contrate.php">
        <input id="contrateBtn" value="Contrate a comunidade" style="position:absolute;left:70%;top:15%;z-index:20;height:200px;width:200px;opacity:0.7" type="submit" />
    </form>
</div>

<div style="position:absolute;left:25%;bottom:0px;background:white;z-index:50;height:350px;width:700px;padding:0">
    <div style="height:40%;background:gray;margin-top:-17px;">
        <p style="position:relative; left:40%; top:50%">Galeria slider</p>
        <p style="position:relative; left:40%; top:50%">( mapa 100% )</p>
    </div>


    <div style="float: left; width: 50%;">
<dl> 
<?php

$url=plugins_url("maper/");
for ($i=0;$i<3;$i++)
{
?>

<dd>
    <img src="<? echo $url ?>themefiles/figs/mapaFoo.png" style="margin-right:10px;">
    mapa <? echo $i; ?>: descrição <? echo $i; ?>
</dd>
<?php
}?>
</dl> 
    </div>
    <div style="float: right; width: 50%;">
<dl> 
<?php
for ($i=3;$i<6;$i++)
{
?>
<dd>
    <img src="<? echo $url ?>themefiles/figs/mapaFoo.png" style="margin-right:10px;">
mapa <? echo $i; ?>: descrição <? echo $i; ?></dd>
<?php
}?>
</dl> 

    </div>
</body>
</html>
