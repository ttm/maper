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
    zoom: 4,
    center: new google.maps.LatLng(-12.039653176087215, -49.68006573388681),
    
    mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.SATELLITE],
        position: google.maps.ControlPosition.RIGHT_CENTER
    },
    mapTypeId: google.maps.MapTypeId.HYBRID,
    mapTypeControl: true,
    disableDefaultUI: true,

    
  }
  map = new google.maps.Map(document.getElementById("map_canvas"),
      myOptions);
  refresh();

google.maps.event.addListener(map, 'click', function(event) {
refresh();
        });

google.maps.event.addListener(map, 'center_changed', function(event) {
refresh();
        });

google.maps.event.addListener(map, 'zoom_changed', function(event) {
refresh();
        });



function placeMarker(location) {
      var marker = new google.maps.Marker({
                position: location,
                      map: map
                        });
}



}

function refresh(){

    center=map.getCenter();
    lat=document.getElementById("lat");
    lng=document.getElementById("lng");
    lat.innerHTML=center.$a;
    lng.innerHTML=center.ab;

    zoom=map.getZoom();
    zoom_=document.getElementById("zoom");
    zoom_.innerHTML=zoom;

    front=map.getBounds();
    front_=document.getElementById("fronteiras");
    front_.innerHTML=front;

    //oFormObject = document.forms['mapForm'];
    //oFormObject.elements["inLat"].value =center.$a;
    //oFormObject.elements["inLng"].value =center.ab;
    //oFormObject.elements["inZoom"].value =zoom;
    //
    //oFormObject2 = document.forms['mdvform'];
    //oFormObject2.elements["mpv_lat"].value =center.$a;
    //oFormObject2.elements["mpv_lng"].value =center.ab;
    //oFormObject2.elements["mpv_zoom"].value =zoom;


    oFormObject = document.forms['mapForm'];
    oFormObject.elements["inLat"].value =center.Ya;
    oFormObject.elements["inLng"].value =center.Za;
    oFormObject.elements["inZoom"].value =zoom;
    
    oFormObject2 = document.forms['mdvform'];
    oFormObject2.elements["mpv_lat"].value =center.Ya;
    oFormObject2.elements["mpv_lng"].value =center.Za;
    oFormObject2.elements["mpv_zoom"].value =zoom;

}

function manda(){
       tipo=map.getMapTypeId();
       if(tipo=='roadmap'){
           document.getElementById("mpv_map_type_road").click();
       }
       else if(tipo == 'hybrid'){
           document.getElementById("mpv_map_type_hybrid").click();
       }
       else if (tipo == 'satellite'){
           document.getElementById("mpv_map_type_satellite").click();
       }
       document.getElementById('fase').value='titulo';
       document.getElementById('mapname').value='mapa ' + String(Math.floor(Math.random()*1000000));
       document.getElementById('submitBttn').click();
}

</script>
<body onload="initialize()" style="overflow:hidden">
<? $url=plugins_url("maper/"); ?>
<div id="logo" style="position:absolute;margin:10px 0 0 10px;z-index:150;background: white;opacity:.83">
    <img src="<? echo $url ?>themefiles/figs/logo.png" style="width:65px;height:130px;">
</div>


<div style="position: absolute;">
<? 
$plugindir = dirname( __FILE__ );
require $plugindir . "/formMdV.php";
?>
</div>
  <div id="map_canvas" style="width:100%; height:100%; z-index:1; position:relative; float:left"></div>
    <form id="mapForm" method="post" action="<?=$_SERVER['PHP_SELF'];?>">
        <input id="inLat" hidden modifiable="0" name="inLat" style="position:absolute;left:5%;bottom:4%;z-index:20;width:500px;opacity:0.7">
        <input id="inLng" hidden modifiable="0" name="inLng" style="position:absolute;left:5%;bottom:0%;z-index:20;width:500px;opacity:0.7">
        <input id="inZoom" hidden modifiable="0" name="inZoom" style="position:absolute;left:5%;bottom:5%;z-index:20;width:500px;opacity:0.7">
    </form>

        <input id="continuar" value="Colocar título" name="titulo" style="position:absolute;left:50%;bottom:15%;z-index:20;height:100px;width:200px;opacity:0.7; border-style:groove;" onmouseover="this.style.cursor='default'"  onclick="manda()" type="button"/>

  <nav>
  <li>
  <a href="#">0.</a>
  </li>
  <li class="active">
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

<div style="padding:15px;left:140px;top:80px;z-index:80;position:absolute;background:white;">
Primeiro, escolha a latitude, longitude e o zoom do mapa. Basta clicar no mapa e arrastar, 2 cliques para dar zoom in ou 2 cliques com o botão esquerdo para zoom out.<br />Escolha também o tipo: estrada/rios, terreno e híbrido. APERTE F11 para melhor utilizacao (tela cheia).
</div>

<div style="left:50%;margin-left:-35%;bottom:1%;z-index:60;background:white;position:absolute; padding:15px">
<b>Centro (lat,log)</b>=<span id='lat'></span>, <span id='lng'></span><br />
<b>Zoom</b>=<span id='zoom'></span><br />
<b>Fronteiras</b>=<span id='fronteiras'></span><br />
</div>


</body>


</html>
