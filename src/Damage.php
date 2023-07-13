<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$sortedArray = $kobo_api->sortRawData($data);

// echo "<pre>";
// print_r($sortedArray);

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
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/datatables.min.js"></script>

    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@^3.0.8/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.0.0/dist/esri-leaflet-vector.js"></script>

    <!-- Load Leaflet Geoman from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

</head>
<title>
    CBDMS
</title>


<body >

<div class="wrapper">
    <div class="sidebar">
          <h2> <img src="images/1.png" style="width: 200px; height: 150px" ></h2>
          <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
           <!--  <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
            <!-- <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
    </div>

    <div class="main_content">
      <div class="header">Damage Assessment 
        <style>
          .header {text-align: center; font-size: 30px; }
        </style>
      </div>

      <div style="margin-top: 20px">
        Filter By: 
        <select style="width: 15%;" onchange="filterMap(this.value);">
          <option value="-" <?=($filter_by == "-" ? "selected" : "")?>>-</option>
          <option value="partially" <?=($filter_by == "partially" ? "selected" : "")?>>Partially</option>
          <option value="totally" <?=($filter_by == "totally" ? "selected" : "")?>>Totally</option>
        </select>

        <div class="pull-right" style="display: inline-flex;flex-direction: row;gap: 10px;">
          <!-- <button type="button" class="btn btn-sm btn-info">Offline</button>
          <button type="button" class="btn btn-sm btn-primary">Direct Link to Kobo</button> -->
          <a href="https://ee.kobotoolbox.org/x/605jP0kX" target="_blank" class="btn btn-sm btn-info">Create New Disaster Event</a>
        </div>
      </div>

        <div id="map" style="width: 100%; height: 70vh; margin-left: 10px;"></div>

        <div style="margin-top: 20px;">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Name of Disaster</th>
                        <th>Type of Disaster</th>
                        <th>Household Affected</th>
                        <!-- <th>Interviewer</th>
                        <th>Household No.</th>
                        <th>Household head </th>
                        <th>Type of Infrastructure</th>
                        <th>Picture </th> 
                        <th>Type of Damage</th> -->
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <?php foreach ($data as $repository) : ?>
                        <tr>
                            
                            <td> <?= $repository["Interviewer"] ?></td>
                            <td> <?= $repository["Date_and_Time_of_Disaster_Occurrence"] ?></td>
                            <td> <?= $repository["Type_of_Disaster"] ?></td>
                            <td>
                                <?php

                                    if (!empty($repository["Name_of_the_Type_of_Disaster"])) :
                                      $disaster = str_replace("_"," ", $repository["Name_of_the_Type_of_Disaster"]);
                                      echo ucwords($disaster);
                                    else:
                                      echo "";
                                    endif;
                                ?>
                            </td>
                            <td> <?= $repository["Household_Number"] ?></td>
                            <td> <?= $repository["Name_of_Household_Head"] ?></td>
                            <td><?= $repository["Type_of_Infrastructure"] ?></td>   
                            <td>
                                <?php
                                    if (is_array($repository["_attachments"])):
                                        //echo 1;

                                        foreach ($repository["_attachments"] as $attachment):
                                            echo "<a class='btn btn-info btn-xs' target='_blank' href='".$attachment['download_url']."' >View Image</a>";
                                        endforeach;

                                    else:
                                        echo 0;
                                    endif;

                                ?>
                            </td>
                            <td> <?= htmlspecialchars($repository["Type_of_Damage"]) ?></td>
                        </tr>
                    <?php endforeach; ?> -->

                    <?php

                        $i = 0;
                        foreach ($sortedArray as $key => $response):

                    ?>
                    <tr>
                        <?php
                            $disaster = array_keys($sortedArray)[$i];
                            $disaster_split = explode('-',trim($disaster));

                        ?>
                            <td><?=ucwords(str_replace("_", " ", $disaster_split[0]))?></td>
                            <td><?=ucwords(str_replace("_", " ", $disaster_split[1]))?></td>
                            <!--td><?=(@$result['Name_of_Type_of_Disaster'] ? @$result['Name_of_Type_of_Disaster'] : 'NA')?></td-->

                        <?php
                            # Count affected household for each disaster
                            # ex. Danny has a total of 2 household affected

                            # We need to use foreach function again to loop through the second nested data from the sorted array above ($sortedArray).
                            $household_affected = 0;
                            foreach ($response as $result):
                                if (@count($result['Household_Number'])) $household_affected += @count($result['Household_Number']);
                            endforeach;   
                        ?>

                        <td><?=$household_affected?></td>
                        <td><a href="damage_details.php?disaster=<?=$disaster?>" class="btn btn-info btn-sm">View Details</a></td>
                    </tr>
                    <?php
                            
                        $i++;
                        endforeach;
                    ?>
                </tbody>
            </table>
        </div>
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

    <script>

      $(document).ready(function () {
          $('#example').DataTable();
      });
      
    </script>

    <script type="text/javascript">

      function filterMap(filter) {
        window.location.href = "?filter_by="+filter;
      }

      const apiKey = "AAPK6a4821a3043a4866947211f83d3e29aefn4aQxWWkM-G5-wR7vL5iZXFxfG6ga9hI6x9kcyMNh31u45_hKRUPPBePAzBGGZd";
      const basemapEnum = "ArcGIS:Streets"
      const layer = L.esri.Vector.vectorBasemapLayer(basemapEnum, { apiKey: apiKey });
      const map = L.map('map', {
        center: [11.0849, 122.4809]
      });
      map.setView([11.0849, 122.4809], 15); // latitude, longitude, zoom level, scale: 72223.819286
      layer.addTo(map);

      $.ajax({
        url: "damage_latlong.php",
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

      $.ajax({
        url: "damage_latlong.php?filter_by=<?php echo $filter_by?>",
        type: "GET",
        dataType: "JSON",
        success: function(d){     
          $.map(d, function(elem){
              //console.log(elem.lat+","+elem.long);
              L.circleMarker([elem.lat, elem.long]).addTo(map)
              .bindPopup("<strong>Household #: "+elem.Household_Number+"</strong><br />Household Head: "+elem.Household_Head, {maxWidth: 500})
              .openPopup();
          });
        }
      });


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