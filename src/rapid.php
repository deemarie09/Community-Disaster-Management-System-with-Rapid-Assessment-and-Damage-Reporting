<?php
include('config.php');
include('api.config.php');

if (!is_connected()) :
    $kobo_api = new KOBO_API();

    $data = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
    $sortedArray = $kobo_api->sortRawData($data);
else:

    $kobo_api = new KOBO_API();

    $rapid_data = "SELECT *, GROUP_CONCAT(' ', id) AS ids FROM `rapid_needs` GROUP BY `Disaster_Event`";
    $rapid_data_sql = mysqli_query($conn, $rapid_data);

    //$sortedArray = mysqli_fetch_array($rapid_data_sql);
    //$sortedArray = $kobo_api->sortRawData($result);
    
    // echo "<pre>";
    // print_r($result);

endif;

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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/datatables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 
    <script type="text/javascript" src="js/datatables.min.js"></script>


    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script> -->

    <title>CBDMS</title>

    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>

    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.fluid.classless.min.css">

    <style> 
    .header {text-align: center;
        font-size: 30px;
    }
    .sync-loader {
        position: absolute;
        width: 100%;
        height: 100vh;
        left: 0;
        background: #fff;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0.9;
        z-index: 999999;
    }
    </style>
</head>

<body >

<div class="wrapper">
    <div class="sidebar">
        <h2> <img src="images/1.png" style="width: 200px; height: 150px" ></h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <!-- <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution_route.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
            <!-- <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

        <div class="main_content" >
            <div class="sync-loader">
                <img src="images/loading.gif">
            </div> 
            <div class="header">Rapid Needs Assessment</div> 
                <main>
                   <!--  <div class="form-group col-md-1 form-group pull-right" style="margin-top: 20px;"> -->
                        <button style="width: 20%;" onclick="window.location.href='https://ee.kobotoolbox.org/x/Iv6awdMf';">
      Create New Disaster Event
    </button>
                    <!-- </div> -->
                    <div class="clearfix"></div> 
                    <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
                    <div class="container" style=" width: 180vh;" >
                        <figure>
                            <?php if (!is_connected()): ?>
                                <table class="table table-bordered table-striped table-hovered text-center">
                                    <thead>
                                        <tr>
                                            <!--th>Date & Time </th-->
                                            <th>Type of Disaster </th>
                                            <th>Disaster Event </th>
                                            <!--th>Name of Disaster </th-->
                                            <!--th>Date & Time of Occurence </th-->
                                            <th>Persons Affected</th>
                                            <th>Household Affected</th>
                                            <th></th>
                                            <!--th>Families Affected</th>
                                            <th>Injured</th>
                                            <th>Missing</th>
                                            <th>Casualties</th>
                                            <th>Evacuees</th>
                                            <th>Families in Evacuation Center</th>
                                            <th>Families in Safe House</th-->
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                # Count affected person for each disaster including Missing, Injured and Dead
                                                # ex. Danny has 3 Missing, 2 Injured and 2 Dead persons. Should be counted as 7 persons in total.

                                                # We need to use foreach function again to loop through the second nested data from the sorted array above ($sortedArray).

                                                # We added "@" in these variables to make them "silent" and don't show Notice errors as some of the forms might not have the same
                                                # informations such as Missing persons, Injured persons or Dead persons.

                                                $persons_affected = 0;
                                                $household_affected = 0;
                                                foreach ($response as $key => $result):
                                                    if (@count($result['Missing_Person'])) $persons_affected += @count($result['Missing_Person']);
                                                    if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                                    if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                                                endforeach;
                                                
                                            ?>

                                            <td><?=$persons_affected?></td>

                                            <?php
                                                # Count affected household for each disaster
                                                # ex. Danny has a total of 2 household affected

                                                # We need to use foreach function again to loop through the second nested data from the sorted array above ($sortedArray).

                                                foreach ($response as $result):
                                                    if (@count($result['Household_Information/Household_Number'])) $household_affected += @count($result['Household_Information/Household_Number']);
                                                endforeach;   
                                            ?>

                                            <td><?=$household_affected?></td>
                                            <td><a href="rapid_details.php?disaster=<?=$disaster?>" class="btn btn-info btn-sm">View Details</a></td>
                                        </tr>
                                        <?php
                                                
                                            $i++;
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>

                            <?php else: ?>

                                <table class="table table-bordered table-striped table-hovered text-center">
                                    <thead>
                                        <tr>
                                            <!--th>Date & Time </th-->
                                            <th>Type of Disaster </th>
                                            <th>Disaster Event </th>
                                            <!--th>Name of Disaster </th-->
                                            <!--th>Date & Time of Occurence </th-->
                                            <th>Persons Affected</th>
                                            <th>Household Affected</th>
                                            <th></th>
                                            <!--th>Families Affected</th>
                                            <th>Injured</th>
                                            <th>Missing</th>
                                            <th>Casualties</th>
                                            <th>Evacuees</th>
                                            <th>Families in Evacuation Center</th>
                                            <th>Families in Safe House</th-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $i = 0;
                                            while ($response = mysqli_fetch_array($rapid_data_sql)):

                                        ?>
                                        <tr>
                                            <td><?=(@$response['Type_of_Disaster'] ? @$response['Type_of_Disaster'] : 'NA')?></td>
                                            <td><?=(@$response['Disaster_Event'] ? @$response['Disaster_Event'] : 'NA')?></td>

                                            <?php

                                                $get_count_persons = "SELECT COUNT(id) as persons_affected FROM rapid_persons WHERE rapid_id IN (".$response['ids'].")";
                                                $get_count_persons_sql = mysqli_query($conn, $get_count_persons);

                                                $persons_affected = mysqli_fetch_assoc($get_count_persons_sql);

                                                echo "<td>".$persons_affected['persons_affected']."</td>";

                                                $get_count_household = "SELECT *, COUNT(id) as household_affected FROM rapid_needs WHERE id IN (".$response['ids'].")";
                                                $get_count_household_sql = mysqli_query($conn, $get_count_household);

                                                $household_affected = mysqli_fetch_array($get_count_household_sql);

                                                echo "<td>".$household_affected['household_affected']."</td>";

                                                // $persons_affected = 0;
                                                // $household_affected = 0;
                                                // foreach ($response as $key => $result):
                                                //     if (@count($result['Missing_Person'])) $persons_affected += @count($result['Missing_Person']);
                                                //     if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                                //     if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                                                // endforeach;

                                            ?>
                                            <td><a href="rapid_details.php?disaster=<?=strtolower($response['Disaster_Event'])?>-<?=strtolower($response['Type_of_Disaster'])?>" class="btn btn-info btn-sm">View Details</a></td>
                                        </tr>
                                        <?php
                                                
                                            $i++;
                                            endwhile;
                                        ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </figure>
                    </div>
                </main>
            </div>
        </div>

<script>
    $(document).ready(function(){

        $("#example").DataTable();

        $(document).on("click",".btn-sync-rapid", function(){
            //alert("clicked");

            $.ajax({
                url: 'sync_rapid.php',
                dataType: "JSON",
                beforeSend: function() {
                    $(".sync-loader").css("display","flex").show();
                },
                success: function(d) {
                    console.log(d);
                    $(".sync-loader").hide();
                }
            });

        })

    });
</script>
</body>

</html>