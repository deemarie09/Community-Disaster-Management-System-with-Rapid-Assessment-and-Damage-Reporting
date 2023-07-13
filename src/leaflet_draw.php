<html>
  <head>
    <meta charset="utf-8" />
    <title>Draw and edit shapes</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@^3.0.8/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.0.0/dist/esri-leaflet-vector.js"></script>

    <!-- Load Leaflet Geoman from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>


    <style>
      html,
      body,
      #map {
        padding: 0;
        margin: 0;
        height: 100%;
        width: 100%;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #323232;
      }
    </style>
  </head>
  <body>
    <script src="https://unpkg.com/leaflet.path.drag@0.0.6/src/Path.Drag.js"></script>
    <script src="https://unpkg.com/leaflet-editable@1.2.0/src/Leaflet.Editable.js"></script>
    <script src="https://unpkg.com/@mapbox/leaflet-pip@latest/leaflet-pip.js"></script>



    <div id="map"></div>
    <script type="text/javascript">

      const apiKey = "AAPK6a4821a3043a4866947211f83d3e29aefn4aQxWWkM-G5-wR7vL5iZXFxfG6ga9hI6x9kcyMNh31u45_hKRUPPBePAzBGGZd";
      const basemapEnum = "ArcGIS:Streets"
      const layer = L.esri.Vector.vectorBasemapLayer(basemapEnum, { apiKey: apiKey });
      const map = L.map('map', {
        center: [11.0849, 122.4809]
      });
      map.setView([11.0849, 122.4809], 15); // latitude, longitude, zoom level, scale: 72223.819286
      layer.addTo(map);

      $.ajax({
        url: "household_latlong.php",
        type: "GET",
        dataType: "JSON",
        success: function(d){     
          $.map(d, function(elem){
              //console.log(elem.lat+","+elem.long);
              L.circleMarker([elem.lat, elem.long]).addTo(map)
              .bindPopup(elem.Household_Number)
              .openPopup();
          });
        }
      });

      // Create a feature layer and add it to the map
      var wildfireDistricts = L.esri
        .featureLayer({
          url: "https://sampleserver6.arcgisonline.com/arcgis/rest/services/Wildfire/FeatureServer/2"
        })
        .addTo(map);

      // Add drawing tools
      map.pm.addControls(
      {
        editControls: true,
        drawMarker:false,
        drawPolyline:false,
        drawCircle:false,
        drawText:false,
        drawCircleMarker:false
      });

      // when a new feature is drawn using the toolbar,
      // pass the edit to the featureLayer service
      map.on("pm:create", function (e) {
        //console.log(e.layer.getLatLngs());
        info = [];

        $.each(e.layer.getLatLngs(), function(i, obj){
          $.each(obj, function(k, res){
            info.push(res);
          })
        });

        console.log("info", info);

        var jsonString = JSON.stringify(info);

        //console.log(info);
        $.ajax({
          url: "polyline_checker.php",
          type: "POST",
          data: {info: jsonString },
          cache: false,
          //dataType: "JSON",
          success: function(d){
              console.log(d);        
          }
        });
        
        wildfireDistricts.addFeature(e.layer.toGeoJSON(), function (error, response) {
          if (error || !response.success) {
            console.log(error, response);
          }

          e.layer.remove();
        });
      });

    </script>
  </body>
</html>