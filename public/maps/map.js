/**
 * TODO Consider refactoring this into a Vue component.
 */
const UI_STYLE = {
    backgroundColor: '#fff',
    border: '2px solid #fff',
    borderRadius: '3px',
    boxShadow: '0 2px 6px rgba(0,0,0,.3)',
    cursor: 'pointer',
    marginBottom: '4px',
    marginRight: '10px',
    textAlign: 'Map Control',
};
const TEXT_STYLE = {
    color: 'rgb(25,25,25)',
    fontFamily: 'Roboto,Arial,sans-serif',
    fontSize: '16px',
    lineHeight: '22px',
    paddingLeft: '5px',
    paddingRight: '5px',
};
const TWILL_APP = 'TWILL'
const MAP_ELEMENT_ID = 'map'
const DEFAULT_LEVEL = '1'

var marker;
var coords;
var geojson;
var current_level;
var container;
var floors;

window.initMap = function initMap(latitude, longitude, level) {
    container = document.getElementById(MAP_ELEMENT_ID)
    detectBrowser();
    var myLatLng = { 'lat': latitude, 'lng': longitude };
    var mapOptions = {
        zoom: 20,
        center: myLatLng,
        disableDefaultUI: true
    }
    var map = new google.maps.Map(container, mapOptions);
    floors = JSON.parse(container.dataset.floors)
    loadOverlays(floors)
    marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Marker',
        draggable: true,
        zIndex: 100
    });
    marker.addListener('mouseup', function() {
        coords = marker.position.toString().slice(1);
        coords = coords.slice(0, coords.indexOf(')'));
        [latitude, longitude] = coords.split(',').map(coord => parseFloat(coord))
        const form = window.parent[TWILL_APP].STORE.form
        let locationFields = [{name: 'latitude', value: latitude}, {name: 'longitude', value: longitude}]
        locationFields.forEach(field => {
            let index = form.fields.findIndex(f => f.name === field.name)
            if (index !== -1) { // If the field already exists remove it with its old value
                form.fields.splice(index, 1)
            }
            form.fields.push(field)
        });
    });

    var controlDiv = document.createElement('div');

    fetch('/maps/Units.geojson')
        .then(response => response.json())
        .then(geojson => {
            //load polygon for level
            loadPolys(geojson, level, map);
            new FloorControl(controlDiv, map, geojson, level);
        })
        .catch(data => {
            if (data.status == 404) {
                console.warn('Unit.geojson not found. Map layers not loaded.');
            }
            else {
                console.log( "ERROR:  ", data );
            }
        });

    controlDiv.index = 1;

    map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
}

function detectBrowser() {
    var useragent = navigator.userAgent;

    if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
        container.style.width = '100%';
        container.style.height = '100%';
    } else {
        container.style.width = '650px';
        container.style.height = '550px';
    }
}

function loadOverlays(floors) {
    let bounds = JSON.parse(container.dataset.bounds)
    for (const floor of floors) {
        if (floor.level === DEFAULT_LEVEL) { // The default level uses the default gmap overlay
            floor.overlay = null
        } else {
            floor.overlay = new google.maps.GroundOverlay(`/maps/map-level-${floor.level}.jpg`, bounds)
        }
    }
}

function loadPolys(geojson, current_level, map) {
    let current_level_id = floors.filter(floor => floor.level == current_level)[0].geo_id
    //remove all current features/polygons
    map.data.forEach(function(feature) {
        map.data.remove(feature);
    })
    //draw new feature
    for (var i = 0; i < geojson.features.length; i++) {
        var level_id = geojson.features[i].properties.LEVEL_ID;
        var coords = geojson.features[i].geometry.coordinates;
        if (level_id == current_level_id) {
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
function FloorControl(controlDiv, map, geojson, current_level) {
    for (const floor of floors) {
        ui = document.createElement('div')
        Object.assign(ui.style, UI_STYLE)
        ui.title = floor.title
        text = document.createElement('div')
        Object.assign(text.style, TEXT_STYLE)
        if (floor.level === current_level) {
            text.style.fontWeight = 'bold'
        }
        text.innerHTML = floor.level
        floor.control = {ui: ui, text: text}
        ui.addEventListener('click', function() {
            for (otherFloor of floors) {
                if (otherFloor.level === floor.level) {
                    if (floor.overlay !== null) {
                        floor.overlay.setMap(map);
                    }
                    floor.control.text.style.fontWeight = 'bold'
                } else {
                    if (otherFloor.overlay !== null) {
                        otherFloor.overlay.setMap(null);
                    }
                    otherFloor.control.text.style.fontWeight = 'normal'
                }
            }
            loadPolys(geojson, floor.level, map)
        })
        ui.appendChild(floor.control.text)
        controlDiv.appendChild(ui)
    }
}
