<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }

  nav{
display:table;
margin: 10px 50px 50px 50px;
overflow:auto;
         border-left: solid 1px #ccc;
         z-index: 50;
         position: absolute;
         margin-left:120px;
         width:100%;
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
        border-width: 1px 1px 1px 30px;
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
    zoom: 13,
    center: new google.maps.LatLng(-23.564298867964755, 313.3961061411132),
    
    mapTypeId: google.maps.MapTypeId.ROADMAP,
//    mapTypeControl: true,
//    mapTypeControlOptions: {
//        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
//        position: google.maps.ControlPosition.BOTTOM_CENTER
//    },
    disableDefaultUI: true
    
  }
  map = new google.maps.Map(document.getElementById("map_canvas"),
      myOptions);
}

function registraMapa(){
    center=map.getCenter();
    lat=center.$a;
    lng=center.ab;

    zoom=map.getZoom();
    window.location="./registraMapa.php?lat=" + lat + "&lng=" + lng + "&zoom="+zoom;
}
</script>
<body onload="initialize()" style="overflow:hidden">
<? $url=plugins_url("maper/"); ?>

<div id="logo" style="position:absolute;margin:10px 0 0 10px;z-index:150;background: white;opacity:.83">
    <img src="<? echo $url ?>themefiles/figs/logo.png" style="width:65px;height:130px;">
</div>

  <div id="map_canvas" style="width:100%; height:100%; z-index:1; position:relative; float:left"></div>
  
    <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
        <input id="continuar" value="Iniciar Mapa" name="iniciar" style="position:absolute;left:50%;bottom:15%;z-index:20;height:100px;width:200px;opacity:0.7" type="submit" />
    </form>
  <nav>
  <li class="active">
  <a href="#">0.</a>
  </li>
  <li>
  <a href="#">1.O mapa</a>
  </li>
  <li>
  <a href="#">2.</a>
  </li>
  <li>
  <a href="#">3.Postagem</a>
  </li>
  <li>
  <a href="#">4.</a>
  </li>
  <li>
  <a href="#">5.Menu</a>
  </li>
  <li>
  <a href="#">6.</a>
  </li>
  <li>
  <a href="#">7.Exportar</a>
</nav>

<div style="height:20%;width:20%;left:100px;top:100px;background:white;z-index:50;position:absolute; padding:5px;">
Este é um passo-a-passo para criação e publicação dos mapas.
Por favor, em caso de quaisquer dificuldades, entre em 
<a href="?contato=1">contato conosco</a>.

</div>
  <div id="transpbox" style="width:100%; height:100%;background-color:#ffffff;opacity:0.4; z-index:10; position: absolute"></div>

</body>
</html>
