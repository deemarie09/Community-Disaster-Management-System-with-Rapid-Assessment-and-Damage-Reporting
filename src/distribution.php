<?php
include('config.php');
include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
#$sortedArray = $kobo_api->sortRawData($data);

// echo "<pre>";
// print_r($data);
if (isset($_GET['filter_by'])):
  $filter_by = $_GET['filter_by'];
else:
  $filter_by = "-";
endif;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@^3.0.8/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.0.0/dist/esri-leaflet-vector.js"></script>

    <!-- Load Leaflet Geoman from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <style>
    /*
     * These CSS rules affect the tooltips within maps with the custom-popup
     * class. See the full CSS for all customizable options:
     * https://github.com/mapbox/mapbox.js/blob/001754177f3985c0e6b4a26e3c869b0c66162c99/theme/style.css#L321-L366
    */
    .leaflet-popup-content-wrapper {
      background:#2c3e50;
      color:#fff;
      font-size:16px;
      line-height:24px;
      }
    .leaflet-popup-content-wrapper a {
      color:rgba(255,255,255,0.5);
      }
    .leaflet-popup-tip-container {
      width:30px;
      height:15px;
      }
    .leaflet-popup-tip {
      border-left:15px solid transparent;
      border-right:15px solid transparent;
      border-top:15px solid #2c3e50;
      }
    </style>


</head>
<title>
    CBDMS
</title>


<body >

<div class="wrapper">
    <div class="sidebar">
         <h2> <img src="images/1212.png"></h2>
          <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <!-- <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="#"><i class="fas fa-map-marker"></i> Distribution</a></li>
           <!--  <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
    </div>

    <div class="main_content">
      <div style="margin-top: 20px">
        Filter By: 
        <select style="width: 15%;" onchange="filterMap(this.value);">
          <option value="-" <?=($filter_by == "-" ? "selected" : "")?>>-</option>
          <option value="children" <?=($filter_by == "children" ? "selected" : "")?>>Children</option>
          <option value="senior" <?=($filter_by == "senior" ? "selected" : "")?>>Senior</option>
          <option value="pwd" <?=($filter_by == "pwd" ? "selected" : "")?>>PWD</option>
        </select>

        <div class="pull-right" style="display: inline-flex;flex-direction: row;gap: 10px;">
          <!-- <button type="button" class="btn btn-sm btn-info">Offline</button>
          <button type="button" class="btn btn-sm btn-primary">Direct Link to Kobo</button> -->
        </div>
      </div>
      
      <div id="map" style="width: 95%; height: 100vh; margin-left: 10px;"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered table-striped">
              <thead>
                <th>Household Number</th>
                <th>Household Head</th>
                <th>Contact No</th>
              </thead>

              <tbody class="tbl-tbody-household">
                
              </tbody>

            </table>
          </div>
          <!-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary">Yes</button>
          </div> -->
        </div>
      </div>
    </div>

    <script type="text/javascript">

      function filterMap(filter) {
        window.location.href = "?filter_by="+filter;
      }

      const apiKey = "AAPK6a4821a3043a4866947211f83d3e29aefn4aQxWWkM-G5-wR7vL5iZXFxfG6ga9hI6x9kcyMNh31u45_hKRUPPBePAzBGGZd";
      const basemapEnum = "ArcGIS:Streets"
      const layer = L.esri.Vector.vectorBasemapLayer(basemapEnum, { apiKey: apiKey });
      const map = L.map('map', {
        center: [11.08634, 122.48170]
      });
      map.setView([11.08634, 122.48170], 17); // latitude, longitude, zoom level, scale: 72223.819286
      layer.addTo(map);

        $.ajax({
            url: "household_latlong.php?filter_by=<?php echo $filter_by?>",
            type: "GET",
            dataType: "JSON",
            success: function(d){

                L.circleMarker([11.08634, 122.48170], {color: '#FF0000', weight: 1}).addTo(map)
                .bindPopup('Barangay Hall')
                .openPopup();

                $.map(d, function(elem){
                  //console.log(elem.lat+","+elem.long);
                    L.circleMarker([elem.lat, elem.long]).addTo(map)
                    .bindPopup("<strong>Household #: "+elem.Household_Number+"</strong><br />Household Head: "+elem.Household_Head+"<br />No. Of Families: "+elem.Num_of_families, {maxWidth: 500})
                    .on('click', function(e){
                        console.log(e.latlng.lat);

                        route_to_station(e.latlng.lat, e.latlng.lng, 11.08634, 122.48170);

                        // L.Routing.control({
                        //    waypoints:   [
                        //    L.latLng(11.0849, 122.4809),
                        //    L.latLng(e.latlng.lat, e.latlng.lng)
                        //    ]
                        // })
                        // .addTo(map);

                    });
                });
            }
        });

        var routing ='';
        var been_routed=false;
        function route_to_station(point_lat_coords, point_lng_coords, x1, y1) {
            users_lat_coords = point_lat_coords;
            users_lng_coords = point_lng_coords;
            x = x1;
            y = y1;

            if (x !== '') {
                if (been_routed === true) {
                    routing.spliceWaypoints(0, 1);
                }
                routing = L.Routing.control({
                        waypoints: [L.latLng(users_lat_coords, users_lng_coords), L.latLng(x, y)],
                        lineOptions: {addWaypoints: false},
                        createMarker: function() { return null; }

                    }
                );
                routing.addTo(map);
                been_routed = true;

            }
        }

        L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, Tiles courtesy of <a href="http://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'
        }).addTo(map);

      // Create a feature layer and add it to the map
        var wildfireDistricts = L.esri
        .featureLayer({
          url: "https://sampleserver6.arcgisonline.com/arcgis/rest/services/Wildfire/FeatureServer/2"
        })
        .addTo(map);

    </script>

</body>

</html>