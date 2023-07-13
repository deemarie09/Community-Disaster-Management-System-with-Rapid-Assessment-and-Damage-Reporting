<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$data_damage = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$data_master = $kobo_api->getDataByAPI(MASTER_LIST_API);
$data_rapid = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
#$sortedArray = $kobo_api->sortRawData($data);

$new_data_rapid = $kobo_api->recursive_change_key($data_rapid, array('Household_Information/Household_Number' => 'Household_Number', 'Household_Information/Household_Head' => 'Household_Head'));

$arr = array_merge($data_damage, $new_data_rapid);

$sortedArray = $kobo_api->sortRawData($arr);

// echo "<pre>";
// print_r($sortedArray);


/******************
#damage form
[0] => Array
  (
      [_id] => 168300939
      [start] => 2022-07-07T16:04:52.747+08:00
      [end] => 2022-07-07T19:33:39.236+08:00
      [Disaster_Event] => strong_wind
      [Type_of_Disaster] => Typhoon
      [Name_of_Type_of_Disaster] => Danny
      [Date_and_Time_of_Disaster_Occurrence] => 2022-06-30T04:00:00+08:00
      [Interviewer] => giljane lamparero
      [Household_Number] => 158
      [Name_of_Household_Head] => curt gerard copia
      [Type_of_Infrastructure] => house
      [Take_a_picture_of_th_amage_infrastructure] => inbound7505406120187141777-16_6_0.jpg
      [Type_of_Damage] => partially
      [Location_of_the_Damaged_Facility] => 11.085595 122.48289 0 0
      [meta/instanceID] => uuid:a41075a8-954a-4909-845c-e5642e61894c
      [meta/deprecatedID] => uuid:5c896d2b-f882-42f3-9972-67c5f3a9f6fb
      [formhub/uuid] => 62703c320c2d445daf28f87324196c94
      [Name_of_the_Type_of_Disaster] => odette
      [__version__] => vEB5QrZSKPAACfPumyVa6Z
      [_xform_id_string] => as8vLnTMHtfcFjNfSktVLH
      [_uuid] => a41075a8-954a-4909-845c-e5642e61894c
      [_attachments] => Array
          (
              [0] => Array
                  (
                      [download_url] => https://kc.kobotoolbox.org/media/original?media_file=deemarie_solvero09%2Fattachments%2F62703c320c2d445daf28f87324196c94%2F5c896d2b-f882-42f3-9972-67c5f3a9f6fb%2Finbound7505406120187141777-16_6_0.jpg
                      [download_large_url] => https://kc.kobotoolbox.org/media/large?media_file=deemarie_solvero09%2Fattachments%2F62703c320c2d445daf28f87324196c94%2F5c896d2b-f882-42f3-9972-67c5f3a9f6fb%2Finbound7505406120187141777-16_6_0.jpg
                      [download_medium_url] => https://kc.kobotoolbox.org/media/medium?media_file=deemarie_solvero09%2Fattachments%2F62703c320c2d445daf28f87324196c94%2F5c896d2b-f882-42f3-9972-67c5f3a9f6fb%2Finbound7505406120187141777-16_6_0.jpg
                      [download_small_url] => https://kc.kobotoolbox.org/media/small?media_file=deemarie_solvero09%2Fattachments%2F62703c320c2d445daf28f87324196c94%2F5c896d2b-f882-42f3-9972-67c5f3a9f6fb%2Finbound7505406120187141777-16_6_0.jpg
                      [mimetype] => image/jpeg
                      [filename] => deemarie_solvero09/attachments/62703c320c2d445daf28f87324196c94/5c896d2b-f882-42f3-9972-67c5f3a9f6fb/inbound7505406120187141777-16_6_0.jpg
                      [instance] => 168300939
                      [xform] => 1007947
                      [id] => 66905261
                  )

          )

      [_status] => submitted_via_web
      [_geolocation] => Array
          (
              [0] => 11.085595
              [1] => 122.48289
          )

      [_submission_time] => 2022-07-07T08:06:19
      [_tags] => Array
          (
          )

      [_notes] => Array
          (
          )

      [_validation_status] => Array
          (
          )

      [_submitted_by] => 
  )

**********************/

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
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@^3.0.8/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.0.0/dist/esri-leaflet-vector.js"></script>

    <!-- Load Leaflet Geoman from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

    <style> 
    .header {text-align: center;
        font-size: 30px;
    } 
    </style>
</head>
<title>
    CDMS
</title>


<body >

<div class="wrapper">
    <div class="sidebar">
         <h2> <img src="images/1212.png"></h2>
          <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li>
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
            <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
    </div>

    <div class="main_content">
        <div class="header">Reports</div>
        <main>
          <table id="example" class="table table-bordered table-striped display nowrap" style="width: 95%; margin-left: auto; margin-right: auto; margin-top: 50px;">
              <thead>
                  <tr>
                      <th>Disaster Event</th>
                      <th></th>
                      
                     <!--  <th>DAP</th> -->
                  </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                  foreach ($sortedArray as $key => $res):
                ?>

                    <tr>
                      <td>
                        <?php

                          $disaster = array_keys($sortedArray)[$i];
                          $disaster_split = explode('-',trim($disaster));

                          echo ucwords(str_replace("_", " ", $disaster_split[0]))." - ".ucwords(str_replace("_", " ", $disaster_split[1]));
                          // echo $res['Household_Number']." - ".$res['Type_of_Disaster']." - ";

                          // if (!empty($res['Name_of_Type_of_Disaster'])) echo $res['Name_of_Type_of_Disaster'];
                          // else echo "N/A";

                        ?>
                      </td>
                      <td><a class="btn btn-info btn-sm" href="report_details.php?disaster=<?=$disaster?>">View Details</a></td>
                    </tr>

                <?php
                  ++$i;
                  endforeach;
                ?>
              </tbody>
          </table>
        </main>
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

    </script>

</body>

</html>