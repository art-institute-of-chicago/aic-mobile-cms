<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta charset="utf-8" />
    <style type="text/css">
      html { height: 100% }
      body { font-family: verdana; font-size:70%; height: 100%; margin: 0; padding: 0 }
      #map { height: 100%; width:100%; }
    </style>
    <script async defer src="https://maps.googleapis.com/maps/api/js?v=3&key={{ $apiKey }}&sensor=false"></script>
    <script src="/maps/map.js"></script>
  </head>
  <body onload="initMap({{ $latitude }}, {{ $longitude }}, '{{ $floor }}')">
    <?php if ($apiKey): ?>
        <div id="map" data-bounds="{{ $bounds }}" data-floors="{{ $floors }}"></div>
      <?php else: ?>
        <p>Google Maps API Key not configured</p>
      <?php endif; ?>
  </body>
</html>
