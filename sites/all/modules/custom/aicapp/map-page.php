<?php
if(!empty($_GET['coords'])) {
    $coords = explode(',', $_GET['coords']);
    $markerPos = '{lat: '.$coords[0].', lng: '.$coords[1].'}';
} else {
    $markerPos = '{lat: 41.8795425, lng: -87.6235470}'; //center of AIC
}

define('DRUPAL_ROOT', realpath('../../../../..'));
require_once DRUPAL_ROOT .'/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
$gmap_key = variable_get('aicapp_gmap_key');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta charset="utf-8" />
    <style type="text/css">
      html { height: 100% }
      body { font-family: verdana; font-size:70%; height: 100%; margin: 0; padding: 0 }
      #map { height: 100%; width:100%; }
	  #myform_view { margin-bottom:12px; float:left;width:160px; border:1px solid silver; padding:8px; margin-right:10px;}
	  .form_item {margin-bottom:0.6em;}
	  h3 {
		font-family: Helvetica, Arial, sans-serif;
		font-size:1.6em;
		font-weight:normal;
		color:#414042;
		margin-bottom:0.6em;
	  }
	  input.form-submit {
		font-family: Helvetica, Arial, sans-serif;
		font-size:1.5em;
		padding:4px 14px;
		border:1px solid #999;
		cursor:pointer;
		color:#000;
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#eeeeee+0,eeeeee+0,cccccc+14,eeeeee+83 */
        background: #eeeeee; /* Old browsers */
        background: -moz-linear-gradient(top,  #eeeeee 0%, #eeeeee 0%, #cccccc 14%, #eeeeee 83%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  #eeeeee 0%,#eeeeee 0%,#cccccc 14%,#eeeeee 83%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  #eeeeee 0%,#eeeeee 0%,#cccccc 14%,#eeeeee 83%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#eeeeee',GradientType=0 ); /* IE6-9 */

	  }
	  input.form-submit:hover {
		background-color: #316672;
	  }
      .floor_selector {
        background-color:#eee;
        padding:3px;
        position:absolute;
        z-index:10;
      }
        #floorB1 { bottom:100px; }
        #floor0 { bottom:120px; }
        #floor1 { bottom:140px; }
        #floor2 { bottom:160px; }
    </style>
    <script src="/misc/jquery.js"></script>
          <?php if ($gmap_key): ?>
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key=<?php print $gmap_key; ?>&sensor=false"></script>
	<script type="text/javascript">
        var marker;
		var coords;
		var geojson;
		var current_level;

		var level_B1_Overlay;
		var level_B1 = '0002';

		var level_0_Overlay;
		var level_0 = '0003';

		var level_1_Overlay;
		var level_1 = '0004';

        var level_2_Overlay;
		var level_2 = '0005';

        function detectBrowser() {
          	var useragent = navigator.userAgent;
          	var mapdiv = document.getElementById("map");

          	if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
            	mapdiv.style.width = '100%';
            	mapdiv.style.height = '100%';
          	} else {
            	mapdiv.style.width = '650px';
            	mapdiv.style.height = '550px';
          	}
        }

        function initMap() {
            detectBrowser();
            var marker_img = 'icons/point-sm.png';
            var myLatLng = <?php print $markerPos ?>;

            var mapOptions = {
                zoom: 20,
                center: myLatLng,
                disableDefaultUI: true
            }

            var map = new google.maps.Map(document.getElementById("map"), mapOptions);

            var imageBounds = {
                north: 41.88085384238198,
                south: 41.8783542495326,
                east: -87.6208768609257,
                west: -87.62429309521068
              };

            //0 is the default gmap layer
            level_2_Overlay = new google.maps.GroundOverlay('map-level-2.jpg',imageBounds);
            level_1_Overlay = new google.maps.GroundOverlay('map-level-1.jpg',imageBounds);
            level_B1_Overlay = new google.maps.GroundOverlay('map-level-B1.jpg',imageBounds);


            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'Marker',
                draggable:true,
                zIndex:100
            });

            marker.addListener('mouseup', function() {
                //write the coords into the node edit form
                coords = marker.position.toString().slice(1);
                coords = coords.slice(0, coords.indexOf(')'));
                window.parent.document.getElementById('edit-field-location-und-0-value').value = coords;
            });

            var controlDiv = document.createElement('div');

            $.ajax({
                url: '../../../../../sites/default/files/Units.geojson',
             dataType: 'json',
             success: function( json ) {
               //load json data
               geojson = json;
               //default level to start with
               current_level = level_0;
               //load polygon for level
               loadPolys(geojson, current_level, map);
               var floor_switch = new FloorControl(controlDiv, map, geojson);
             },
             error: function( data ) {
               if (data.status == 404) {
                 console.warn('Unit.geojson not found. Map layers not loaded.');
               }
               else {
                 console.log( "ERROR:  ", data );
               }
             }
             });

            controlDiv.index = 1;

            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
        }

		function loadPolys(geojson, current_level, map) {
			//remove all current features/polygons
			map.data.forEach(function(feature) {
				map.data.remove(feature);
			})
			//draw new feature
			for (var i = 0; i < geojson.features.length; i++) {
				var levels = geojson.features[i].properties.LEVEL_ID;
				var coords = geojson.features[i].geometry.coordinates;
				if (levels == current_level) {
					for (var j = 0; j < coords.length; j++) {
						var poly = coords[j];
						var level_poly = [];
						for (var k = 0; k < poly.length; k++) {
							var latLng = new google.maps.LatLng(poly[k][1],poly[k][0]);
							level_poly.push(latLng);
						}
						map.data.add({geometry: new google.maps.Data.Polygon([level_poly])})
						map.data.setStyle({
							fillColor: 'white',
						  	strokeWeight: 1
						})
					}
				}
			}
		}
        /**
        * The FloorControl adds a control that switches to a different floor of the museum on the map.
        * It takes the controlDiv (there are 4, one for each floor) as argument.
        **/
        function FloorControl(controlDiv, map, geojson) {
          // Set CSS for the control border.
          var control_B1_UI = document.createElement('div');
          var control_0_UI = document.createElement('div');
          var control_1_UI = document.createElement('div');
          var control_2_UI = document.createElement('div');
          control_B1_UI.style.backgroundColor = '#fff';
          control_B1_UI.style.border = '2px solid #fff';
          control_B1_UI.style.borderRadius = '3px';
          control_B1_UI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
          control_B1_UI.style.cursor = 'pointer';
          control_B1_UI.style.marginBottom = '4px';
          control_B1_UI.style.marginRight = '10px';
          control_B1_UI.style.textAlign = 'Map Control';
          control_B1_UI.title = 'Floor B1';
          controlDiv.appendChild(control_B1_UI);

          control_0_UI.style.backgroundColor = control_B1_UI.style.backgroundColor;
          control_0_UI.style.border = control_B1_UI.style.border;
          control_0_UI.style.borderRadius = control_B1_UI.style.borderRadius;
          control_0_UI.style.boxShadow = control_B1_UI.style.boxShadow;
          control_0_UI.style.cursor = control_B1_UI.style.cursor;
          control_0_UI.style.marginBottom = control_B1_UI.style.marginBottom;
          control_0_UI.style.marginRight = control_B1_UI.style.marginRight;
          control_0_UI.style.textAlign = control_B1_UI.style.textAlign;
          control_0_UI.title = "Floor 0";
          controlDiv.appendChild(control_0_UI);

          control_1_UI.style.backgroundColor = control_B1_UI.style.backgroundColor;
          control_1_UI.style.border = control_B1_UI.style.border;
          control_1_UI.style.borderRadius = control_B1_UI.style.borderRadius;
          control_1_UI.style.boxShadow = control_B1_UI.style.boxShadow;
          control_1_UI.style.cursor = control_B1_UI.style.cursor;
          control_1_UI.style.marginBottom = control_B1_UI.style.marginBottom;
          control_1_UI.style.marginRight = control_B1_UI.style.marginRight;
          control_1_UI.style.textAlign = control_B1_UI.style.textAlign;
          control_1_UI.title = "Floor 1";
          controlDiv.appendChild(control_1_UI);

          control_2_UI.style.backgroundColor = control_B1_UI.style.backgroundColor;
          control_2_UI.style.border = control_B1_UI.style.border;
          control_2_UI.style.borderRadius = control_B1_UI.style.borderRadius;
          control_2_UI.style.boxShadow = control_B1_UI.style.boxShadow;
          control_2_UI.style.cursor = control_B1_UI.style.cursor;
          control_2_UI.style.marginBottom = control_B1_UI.style.marginBottom;
          control_2_UI.style.marginRight = control_B1_UI.style.marginRight;
          control_2_UI.style.textAlign = control_B1_UI.style.textAlign;
          control_2_UI.title = "Floor 2";
          controlDiv.appendChild(control_2_UI);


          // Set CSS for the control interior.
          var control_B1_Text = document.createElement('div');
          var control_0_Text = document.createElement('div');
          var control_1_Text = document.createElement('div');
          var control_2_Text = document.createElement('div');
          control_B1_Text.style.color = 'rgb(25,25,25)';
          control_B1_Text.style.fontFamily = 'Roboto,Arial,sans-serif';
          control_B1_Text.style.fontSize = '16px';
          control_B1_Text.style.lineHeight = '22px';
          control_B1_Text.style.paddingLeft = '5px';
          control_B1_Text.style.paddingRight = '5px';
          control_B1_Text.innerHTML = 'B1';
          control_B1_UI.appendChild(control_B1_Text);

          control_0_Text.style.color = control_B1_Text.style.color;
          control_0_Text.style.fontFamily = control_B1_Text.style.fontFamily;
          control_0_Text.style.fontSize = control_B1_Text.style.fontSize;
          control_0_Text.style.lineHeight = control_B1_Text.style.lineHeight;
          control_0_Text.style.paddingLeft = control_B1_Text.style.paddingLeft;
          control_0_Text.style.paddingRight = control_B1_Text.style.paddingRight;
          control_0_Text.innerHTML = '0'
          control_0_Text.style.fontWeight = 'bold'; //default
          control_0_UI.appendChild(control_0_Text);

          control_1_Text.style.color = control_B1_Text.style.color;
          control_1_Text.style.fontFamily = control_B1_Text.style.fontFamily;
          control_1_Text.style.fontSize = control_B1_Text.style.fontSize;
          control_1_Text.style.lineHeight = control_B1_Text.style.lineHeight;
          control_1_Text.style.paddingLeft = control_B1_Text.style.paddingLeft;
          control_1_Text.style.paddingRight = control_B1_Text.style.paddingRight;
          control_1_Text.innerHTML = '1'
          control_1_UI.appendChild(control_1_Text);

          control_2_Text.style.color = control_B1_Text.style.color;
          control_2_Text.style.fontFamily = control_B1_Text.style.fontFamily;
          control_2_Text.style.fontSize = control_B1_Text.style.fontSize;
          control_2_Text.style.lineHeight = control_B1_Text.style.lineHeight;
          control_2_Text.style.paddingLeft = control_B1_Text.style.paddingLeft;
          control_2_Text.style.paddingRight = control_B1_Text.style.paddingRight;
          control_2_Text.innerHTML = '2'
          control_2_UI.appendChild(control_2_Text);

          // Setup the click event listeners
          control_B1_UI.addEventListener('click', function() {
            level_B1_Overlay.setMap(map);
            level_1_Overlay.setMap(null);
            level_2_Overlay.setMap(null);
            control_B1_Text.style.fontWeight = 'bold';
            control_0_Text.style.fontWeight = 'normal';
            control_1_Text.style.fontWeight = 'normal';
            control_2_Text.style.fontWeight = 'normal';
			loadPolys(geojson, level_B1, map)
          });
          control_0_UI.addEventListener('click', function() {
            //0 is the default
            level_B1_Overlay.setMap(null);
            level_1_Overlay.setMap(null);
            level_2_Overlay.setMap(null);
            control_B1_Text.style.fontWeight = 'normal';
            control_0_Text.style.fontWeight = 'bold';
            control_1_Text.style.fontWeight = 'normal';
            control_2_Text.style.fontWeight = 'normal';
			loadPolys(geojson, level_0, map)
          });
          control_1_UI.addEventListener('click', function() {
            level_B1_Overlay.setMap(null);
            level_1_Overlay.setMap(map);
            level_2_Overlay.setMap(null);
            control_B1_Text.style.fontWeight = 'normal';
            control_0_Text.style.fontWeight = 'normal';
            control_1_Text.style.fontWeight = 'bold';
            control_2_Text.style.fontWeight = 'normal';
			loadPolys(geojson, level_1, map)
          });
          control_2_UI.addEventListener('click', function() {
            level_B1_Overlay.setMap(null);
            level_1_Overlay.setMap(null);
            level_2_Overlay.setMap(map);
            control_B1_Text.style.fontWeight = 'normal';
            control_0_Text.style.fontWeight = 'normal';
            control_1_Text.style.fontWeight = 'normal';
            control_2_Text.style.fontWeight = 'bold';
			loadPolys(geojson, level_2, map)
          });
        }

        //capture line and marker data into the HTML form for processing
        function capture(){
            //write the coords into the node edit form
            coords = marker.position.toString().slice(1);
            coords = coords.slice(0, coords.indexOf(')'));
            window.parent.document.getElementById('edit-field-location-und-0-value').value = coords;
            return false;
        }
</script>
          <?php endif; ?>
  </head>
  <body <?php if ($gmap_key): ?>
          onload="initMap()"
<?php endif; ?>
          >
    <?php if ($gmap_key): ?>
          <div id="map"></div>
          <!-- this form is not needed, we write to the parent window on marker mouseup event!
           <form id="myform" method="post" target="_parent" onsubmit="return capture()" action="">
            <input type="hidden" id="coords" name="coords" value="" />
            <input type="hidden" id="marker_coords" name="marker_coords" value="" />
            <div class="form_item"><input id="savebtn" class="form-submit" type="submit" name="save" value="Save Pin Position" /></div>
          </form>-->
      <?php else: ?>
        <p>Please enter a Google Maps API Key at /admin/settings/aic-api</p>
      <?php endif; ?>
  </body>
</html>
