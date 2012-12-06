<?php
wp_enqueue_script( 'jquery' );
function load_jquery2() {

    // only use this method is we're not in wp-admin

        // deregister the original version of jQuery
 //       wp_deregister_script('jquery');

        // discover the correct protocol to use
        $protocol='http:';
        if($_SERVER['HTTPS']=='on') {
            $protocol='https:';

        // register the Google CDN version
//        wp_register_script('jquery', $protocol.'//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js', false, '1.5.2');

        // add it back into the queue
        wp_enqueue_script('jquery');

    }

}

add_action('wp_enqueue_scripts', 'load_jquery2');


//add_action('init', 'mapasdevista_save_map');

function mapasdevista_save_mapB() {

    if (isset($_POST['submit_map'])) {

        $error = false;

        // check if there is already another map associated with this page
        if ($_POST['original_page_id'] != $_POST['page_id'] && get_post_meta($_POST['page_id'], '_mapasdevista', true)) {
            $error = __('There is another map already in this page. Please choose another page.', 'mapasdevista');
        }

        if (!$error) {

            //remove association with other page
            if (is_numeric($_POST['original_page_id'])) {
                delete_post_meta('_mapasdevista', $_POST['original_page_id']);
            }

            update_post_meta($_POST['page_id'], '_mapasdevista', $_POST['map']);

            wp_redirect(add_query_arg(array('action' => '', 'message' => 'save_success')));

        } else {

            add_action('all_admin_notices', 'mapasdevista_save_map_error_notice');

            function mapasdevista_save_map_error_notice() {
                echo '<div class="error"><p>';
                _e('There is another map already in this page. Please choose another page.', 'mapasdevista');
                echo '</p></div>';

            }

        }

    }

}

echo "AA";

function mapasdevista_maps_pageB() {
    global $wp_post_types, $wp_taxonomies;
    ?>


    <div class="wrap">
        <h2><?php _e('Maps', 'mapasdevista'); ?></h2>
        
        
        <?php if (isset($_SESSION['Lat'])) : ?>

            <?php

echo "BB";

            if (!is_array($map))
                $map = array();
            if (!(isset($map['post_types']) && is_array($map['post_types'])))
                $map['post_types'] = array();
            if (!(isset($map['taxonomies']) && is_array($map['taxonomies'])))
                $map['taxonomies'] = array();
            if (!(isset($map['filters']) && is_array($map['filters'])))
                $map['filters'] = array();

            ?>
            <pre>
            <?php //print_r($map); ?>
            </pre>
            <form method="POST">
            <?php do_action('mapasdevista_maps_settings_top',$map); ?>
            <h3><?php _e('Select the page that will be the placeholder for this map', 'mapasdevista'); ?></h3>
            <?php 
           if($_SESSION['Zoom']){ 
echo "BB2";
    $defaults = array(
            'post_type'             => 'page',
            'post_author'           => 1,
            'post_title'    => 'Título do post',
            'post_status'   => 'publish',
            );
$page_id = wp_insert_post( $defaults , $wp_error);
            wp_dropdown_pages( "selected=$page_id" );
$_SESSION['PageNumb']=$page_id;
           }
           else{
            wp_dropdown_pages( 'selected=' . $_GET['page_id'] );

           } ?>

            <input type="hidden" name="original_page_id" value="<?php echo $_GET['page_id']; ?>" />

            <h3><?php _e('Map Name', 'mapasdevista'); ?></h3>
            <input type="text" name="map[name]" value="<?php echo $map['name']; ?>">

            <h3><?php _e('Map API', 'mapasdevista'); ?></h3>
            <ul id="mpv_map_api">
                <li>
                    <input type="radio" name="map[api]" id="mpv_api_googlev3" value="googlev3"<?php if ($map['api'] == 'googlev3') echo ' checked'; ?>>
                    <label for="mpv_api_googlev3">Google Maps</label>
                </li>
                <li>
                    <input type="radio" name="map[api]" id="mpv_api_openlayers" value="openlayers"<?php if ($map['api'] == 'openlayers') echo ' checked'; ?>>
                    <label for="mpv_api_openlayers">OpenLayers</label>
                </li>
                <li>
                    <input type="radio" name="map[api]" id="mpv_api_image" value="image"<?php if ($map['api'] == 'image') echo ' checked'; ?>>
                    <label for="mpv_api_image"><?php _e('Image as map', 'mapasdevista'); ?></label>
                </li>
            </ul>

            <fieldset id="mpv_map_fields">
                <h3><?php _e('Map initial state', 'mapasdevista'); ?></h3>
                
                <div id="mpv_canvas_googlev3" class="mpv_canvas" style="display:none"></div>
                <div id="mpv_canvas_openlayers" class="mpv_canvas" style="display:none"></div>
                
                <table class="map_config">
                    <tr>
                        <td>
                        <label><?php _e('Map type', 'mapasdevista'); ?></label>
                            <ul id="mpv_map_type">
                                <li>
                                    <input type="radio" name="map[type]" id="mpv_map_type_road" value="road"<?php echo $map['type']=='road'?' checked="checked"':'';?>/>
                                    <label for="mpv_map_type_road" class="small"><?php _e('Road', 'mapasdevista');?>:</label>
                                </li>
                                <li>
                                    <input type="radio" name="map[type]" id="mpv_map_type_satellite" value="satellite"<?php echo $map['type']=='satellite'?' checked="checked"':'';?>/>
                                    <label for="mpv_map_type_satellite" class="small"><?php _e('Satellite', 'mapasdevista');?>:</label>
                                </li>
                                <li>
                                    <input type="radio" name="map[type]" id="mpv_map_type_hybrid" value="hybrid"<?php echo $map['type']=='hybrid'?' checked="checked"':'';?>/>
                                    <label for="mpv_map_type_hybrid" class="small"><?php _e('Hybrid', 'mapasdevista');?>:</label>
                                </li>
                            </ul>
                        </td>
                        <td>
                        <label><?php _e('Initial position', 'mapasdevista'); ?>:</label>
                        <ul id="mpv_map_status">
                            <li>
                                <label for="mpv_lat" class="small"><?php _e('Latitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[coord][lat]" id="mpv_lat" value="<?php echo $map['coord']['lat'];?>"/>
                            </li>
                            <li>
                                <label for="mpv_lon" class="small"><?php _e('Longitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[coord][lng]" id="mpv_lng" value="<?php echo $map['coord']['lng'];?>"/>
                            </li>
                            <li>
                                <label for="mpv_zoom" class="small">Zoom level:</label>
                                <input type="text" class="small-field" name="map[zoom]" id="mpv_zoom" value="<?php echo $map['zoom'];?>"/>
                            </li>
                            <li><input type="button" id="mapbutton" value="Center map"/></li>
                        </ul>
                        </td>
                        <td>
                        <label><?php _e('North East limit position', 'mapasdevista'); ?>:</label>
                        <p><?php _e('Empty for no limit.', 'mapasdevista'); ?> <input type="button" value="<?php _e('Capture current position', 'mapasdevista'); ?>" id="mpv_capture_position_ne" /></p>
                        <ul id="mpv_map_status">
                            <li>
                                <label for="north_east_max_lat" class="small"><?php _e('Latitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[north_east][lat]" id="north_east_max_lat" value="<?php echo $map['north_east']['lat'];?>"/>
                            </li>
                            <li>
                                <label for="north_east_max_lon" class="small"><?php _e('Longitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[north_east][lng]" id="north_east_max_lng" value="<?php echo $map['north_east']['lng'];?>"/>
                            </li>
                            
                        </ul>
                        </td>
                        <td>
                        <label><?php _e('South West limit position', 'mapasdevista'); ?>:</label>
                        <p><?php _e('Empty for no limit.', 'mapasdevista'); ?> <input type="button" value="<?php _e('Capture current position', 'mapasdevista'); ?>" id="mpv_capture_position_sw" /></p>
                        <ul id="mpv_map_status">
                            <li>
                                <label for="south_west_max_lat" class="small"><?php _e('Latitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[south_west][lat]" id="south_west_max_lat" value="<?php echo $map['south_west']['lat'];?>"/>
                            </li>
                            <li>
                                <label for="south_west_max_lon" class="small"><?php _e('Longitude', 'mapasdevista');?>:</label>
                                <input type="text" class="small-field" name="map[south_west][lng]" id="south_west_max_lng" value="<?php echo $map['south_west']['lng'];?>"/>
                            </li>
                        </ul>
                        </td>
                        <td>
                        <label><?php _e('Zoom limit', 'mapasdevista'); ?>:</label>
                        <p><?php _e('Empty for no limit.', 'mapasdevista'); ?> </p>
                        <ul id="mpv_map_status">
                             <li>
                                <label for="mpv_min_zoom" class="small">Zoom out level:</label>
                                <input type="text" class="small-field" name="map[min_zoom]" id="mpv_min_zoom" value="<?php echo $map['min_zoom'];?>"/>
                                <input type="button" value="<?php _e('Capture current level', 'mapasdevista'); ?>" id="mpv_capture_min_zoom" />
                            </li>
                             <li>
                                <label for="mpv_max_zoom" class="small">Zoom in level:</label>
                                <input type="text" class="small-field" name="map[max_zoom]" id="mpv_max_zoom" value="<?php echo $map['max_zoom'];?>"/>
                                <input type="button" value="<?php _e('Capture current level', 'mapasdevista'); ?>" id="mpv_capture_max_zoom" />
                            </li>
                        </ul>
                        </td>
                        
                    </tr>
                </table>
            </fieldset>

<? echo "BB3"; ?>
            <h3><?php _e("Show controls");?></h3>
            <ul>
                <li><?php _e("Zoom");?>:
                        <ul style="padding-left: 30px">
                            <li><input type="radio" name="map[control][zoom]"<?php if($map['control']['zoom'] == 'large'){ echo ' checked';}?> id="mpv_control_large_zoom" value="large"/> <label for="mpv_control_large_zoom"><?php _e("Large");?></label></li>
                            <li><input type="radio" name="map[control][zoom]"<?php if($map['control']['zoom'] == 'small'){ echo ' checked';}?> id="mpv_control_small_zoom" value="small"/> <label for="mpv_control_small_zoom"><?php _e("Small");?></label></li>
                            <li><input type="radio" name="map[control][zoom]"<?php if($map['control']['zoom'] == 'none') { echo ' checked';}?> id="mpv_control_no_zoom" value="none"/> <label for="mpv_control_no_zoom"><?php _e("None");?> </label></li>
                        </ul>
                </li>
                <li><input type="checkbox" id="mpv_control_pan"<?php if(isset($map['control']['pan'])){ echo ' checked';}?> name="map[control][pan]" /> <label for="mpv_control_pan"><?php _e("Pan");?></label></li>
                <li><input type="checkbox" id="mpv_control_map_type"<?php if(isset($map['control']['map_type'])){ echo ' checked';}?> name="map[control][map_type]" /> <label for="mpv_control_map_type"><?php _e("Map type");?></label></li>
            </ul>            

<? echo "BB4";

wp_enqueue_script('jquery');
wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js', false, '1.5.2');


 ?>
            <script type="text/javascript">
            (function($) {
                function fill_fields(lat, lng, zoom) {
                    $("#mpv_lat").val(lat);
                    $("#mpv_lng").val(lng);
                    $("#mpv_zoom").val(zoom);
                }

                // center map on <input/> coords. if coords
                // is invalid, center the map at hacklab ;)
                function centerMapAndZoom() {
                    try {
                        var point = new mxn.LatLonPoint(
                                        parseFloat($("#mpv_lat").val()),
                                        parseFloat($("#mpv_lng").val())
                                    );
                        mapstraction.setCenterAndZoom(point, parseInt($("#mpv_zoom").val()));
                    } catch(e) {
                        if(console && console.log) console.log(e);
                        mapstraction.setCenterAndZoom(new mxn.LatLonPoint(-23.531095, -46.673999), 16);
                        fill_fields(mapstraction.getCenter().lat, mapstraction.getCenter().lon, mapstraction.getZoom());
                    }
                }

                // set default api
                var api = 'openlayers';
                if($('#mpv_map_api input:checked').val()) {
                    api = $('#mpv_map_api input:checked').val();
                } else {
                    $('#mpv_map_api input[value='+api+']').attr('checked','checked');
                }

                $('#mpv_map_fields').attr('class',api);

                if(api === 'image') {
                    mapstraction = new mxn.Mapstraction('mpv_canvas_openlayers', 'openlayers');
                } else {
                    $('#mpv_canvas_'+api).show();
                    mapstraction = new mxn.Mapstraction('mpv_canvas_'+api, api);
                }
                centerMapAndZoom();

                mapstraction.addControls({
                    'pan': true,
                    'map_type': true,
                    'zoom': 'large'
                });

                // fill #mpv_zoom when zoom changes
                mapstraction.changeZoom.addHandler(function(n,s,a) {
                    $("#mpv_zoom").val(s.getZoom());
                });
                // fill #mpv_lat, #mpv_lon and #mpv_zoom after drag the map
                mapstraction.endPan.addHandler(function(n,s,a) {
                        fill_fields(s.getCenter().lat, s.getCenter().lon, s.getZoom());
                });
                // center map on #mpv_lat, #mpv_lon coords
                $("#mpv_map_status input[type=button]").click(function(e){centerMapAndZoom();});

                // set the default map-type. should be (road|satellite|hybrid)
                var map_type = 'road';
                try{
                    if($('#mpv_map_type input:checked').length == 1) {
                        map_type = $('#mpv_map_type input:checked').val();
                    }
                    var mxn_map_type = map_type.toUpperCase();
                    if(mxn.Mapstraction[mxn_map_type])
                        mapstraction.setMapType(mxn.Mapstraction[mxn_map_type]);
                } catch(e) { // happens when map_type=='road' and api=='openlayers'
                    $('input#mpv_map_type_'+map_type).attr('checked','checked');
                    $('#mpv_map_type input[value!='+map_type+']').attr('disabled','disabled');
                }

                // event to switch map type (road|satellite|hybrid))
                $('#mpv_map_type input').change(function(e) {
                    mxn_map_type = $(this).val().toUpperCase();
                    mapstraction.setMapType(mxn.Mapstraction[mxn_map_type]);
                });

                // event to switch api (googlev3|openlayers)
                $('#mpv_map_api input').click(function(e) {
                    if($(this).val()) {
                        api = $(this).val();
                    }
                    $('#mpv_map_fields').attr('class',api);

                    if(api === 'openlayers'){
                        mapstraction.swap('mpv_canvas_'+api, api);
                        $('input#mpv_map_type_road').attr('checked','checked');
                        $('#mpv_map_type input[value!=road]').attr('disabled','disabled');
                    } else if(api === 'googlev3') {
                        mapstraction.swap('mpv_canvas_'+api, api);
                        $('#mpv_map_type input[value!=road]').attr('disabled',false);
                    }
                    map_type = ['road','satellite','hybrid'][mapstraction.getMapType()-1];
                    $('input#mpv_map_type_'+map_type).attr('checked','checked');
                });
                
                $('#mpv_capture_position_ne').click(function() {
                    var coords = mapstraction.getCenter();
                    $('#north_east_max_lat').val(coords.lat);
                    $('#north_east_max_lng').val(coords.lng || coords.lon);
                });
                $('#mpv_capture_position_sw').click(function() {
                    var coords = mapstraction.getCenter();
                    $('#south_west_max_lat').val(coords.lat);
                    $('#south_west_max_lng').val(coords.lng || coords.lon);
                });
                $('#mpv_min_zoom').change(function() {
                    if($(this).val().match(/^[0-9]+$/) && $('#mpv_max_zoom').val().match(/^[0-9]+$/)) {
                        var cc = $('#mpv_max_zoom').css('background-color');
                        var max = parseInt($('#mpv_max_zoom').val());
                        var min = parseInt($('#mpv_min_zoom').val());
                        if(min > max) {
                            max = min;
                            $('#mpv_max_zoom').animate({backgroundColor:'#ff7f7f'},300,'linear',function(){$(this).animate({backgroundColor:cc})});
                        }
                        $('#mpv_max_zoom').val(max);
                    }
                }).blur(function(){$(this).change();});

                $('#mpv_max_zoom').change(function() {
                    if($(this).val().match(/^[0-9]+$/) && $('#mpv_min_zoom').val().match(/^[0-9]+$/)) {
                        var cc = $('#mpv_min_zoom').css('background-color');
                        var max = parseInt($('#mpv_max_zoom').val());
                        var min = parseInt($('#mpv_min_zoom').val());
                        if(min > max) {
                            min = max;
                            $('#mpv_min_zoom').animate({backgroundColor:'#ff7f7f'},300,'linear',function(){$(this).animate({backgroundColor:cc})});
                        }
                        $('#mpv_min_zoom').val(min);
                    }
                }).blur(function(){$(this).change();});

                $('#mpv_capture_min_zoom').click(function() {
                    var zoom = mapstraction.getZoom();
                    $('#mpv_min_zoom').val(zoom).change();
                });
                $('#mpv_capture_max_zoom').click(function() {
                    var zoom = mapstraction.getZoom();
                    $('#mpv_max_zoom').val(zoom).change();
                });
               
               <? if($_SESSION['Zoom']){ ?>
    foo=document.getElementById("mpv_api_googlev3");
    foo.click();
    alert("AA");


var point11 = new mxn.LatLonPoint(
                                        parseFloat(<? echo $_SESSION['Lat']; ?>),
                                        parseFloat(<? echo $_SESSION['Lng']; ?>)
                                    );
                        mapstraction.setCenterAndZoom(point11, parseInt(<? echo $_SESSION['Zoom']; ?>));

<?    } ?>
 
                
                
            });
    foo=document.getElementById("mpv_api_googlev3");
    foo.click();
    foo=document.getElementById("mpv_lat");
    foo.value=parseFloat(<? echo $_SESSION['Lat']; ?>);
    foo=document.getElementById("mpv_lng");
    foo.value=parseFloat(<? echo $_SESSION['Lng']; ?>);
    foo=document.getElementById("mpv_zoom");
    foo.value=parseFloat(<? echo $_SESSION['Zoom']; ?>);
//    alert("AA");

var point11 = new mxn.LatLonPoint(
                                        parseFloat(<? echo $_SESSION['Lat']; ?>),
                                        parseFloat(<? echo $_SESSION['Lng']; ?>)
                                    );
                        mapstraction.setCenterAndZoom(point11, parseInt(<? echo $_SESSION['Zoom']; ?>));





            </script>
            <? echo "CC";?>   




            <h3><?php _e('Logical operator', 'mapasdevista'); ?></h3>
            
            <p>
                <?php _e('How the map should handle multiple filters?', 'mapasdevista'); ?>
            </p>
            
            <label><input type="radio" name="map[logical_operator]" value="AND" <?php if($map['logical_operator'] == 'AND'){ echo ' checked';}?>/> <?php _e('Intersection - Displays posts that match all the filters', 'mapasdevista'); ?></label>
            <label><input type="radio" name="map[logical_operator]" value="OR" <?php if($map['logical_operator'] == 'OR'){ echo ' checked';}?>/> <?php _e('Union - Displays posts that match at least one of the filters', 'mapasdevista'); ?></label>
            <label><input type="radio" name="map[logical_operator]" value="" <?php if($map['logical_operator'] == ''){ echo ' checked';}?>/> <?php _e('Let the visitor decide', 'mapasdevista'); ?></label>
            <br/>
            
            <h3><?php _e('What kind of posts may appear on the map?', 'mapasdevista'); ?></h3>
            
            <?php foreach ($wp_post_types as $type_name => $type) : ?>

                <?php if ($type_name == 'attachment' || $type_name == 'revision' || $type_name == 'nav_menu_item' ) continue; ?>
                <input type="checkbox" name="map[post_types][]" value="<?php echo $type_name; ?>" <?php if (in_array($type_name, $map['post_types'])) echo 'checked'; ?> > <?php echo $type->label; ?> <br />

            <?php endforeach; ?>

            <h3><?php _e('What filters should be visible to the visitors?', 'mapasdevista'); ?></h3>

            <input type="checkbox" name="map[filters][]" value="new" <?php if (in_array('new', $map['filters'])) echo 'checked'; ?> > <?php _e('Filter by new posts (displays 10 most recent posts)', 'mapasdevista'); ?> <br />
            <input type="checkbox" name="map[filters][]" value="post_types" <?php if (in_array('post_types', $map['filters'])) echo 'checked'; ?> > <?php _e('Filter by post types', 'mapasdevista'); ?> <br />
            <input type="checkbox" name="map[filters][]" value="author" <?php if (in_array('author', $map['filters'])) echo 'checked'; ?> > <?php _e('Filter by Authors', 'mapasdevista'); ?> <br />
            <br/>
            <?php _e('Filter by the following taxonomies', 'mapasdevista'); ?>
            <br/>
            <?php foreach ($wp_taxonomies as $type_name => $type) : ?>

                <?php if ($type_name == 'link_category' || $type_name == 'nav_menu') continue; ?>
                <input type="checkbox" name="map[taxonomies][]" value="<?php echo $type_name; ?>" <?php if (in_array($type_name, $map['taxonomies'])) echo 'checked'; ?> > <?php echo $type->label; ?> <br />

            <?php endforeach; ?>

            <?php do_action('mapasdevista_maps_settings_bottom',$map); ?>
            
            <input type="submit" id='submitBttn' name="submit_map" value="<?php _e('Save Changes', 'mapasdevista'); ?>" />

            </form>
<?php
if($_SESSION['MdVauto']){
    ?>
    <script>
       window.setTimeout("document.getElementById('submitBttn').click()",1000);
       document.getElementById("p<?=$_SESSION['PageNumb']-1;?>").click();
    </script>
<?php } ?>

        <?php endif; ?>
    </div>


    <?php
?>
<?php
}

mapasdevista_maps_pageB();
