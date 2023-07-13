<?php
include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
$sortedArray = $kobo_api->sortRawData($data);

// echo "<pre>";
// print_r($sortedArray[$_GET['disaster']][0]['Missing_Person']);

$data_array = $sortedArray[$_GET['disaster']];

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />

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
    </style>
</head>

<body >

<div class="wrapper">
    <div class="sidebar">
         <h2> <img src="images/1.png" style="width: 200px; height: 150px" ></h2>        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <!-- <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution_route.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
           <!--  <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

        <div class="main_content" >
            <div class="header">Rapid Needs Assessment</div> 
                <main> 
                    <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
                    <div class="container">
                        <a href="rapid.php" class="btn btn-info btn-md pull-right">Back</a>
                        <br />
                        <figure>
                            <table id="table" class="table table-bordered table-striped table-hovered text-center">
                                <thead>
                                    <tr>
                                        
                                        <th>Date & Time of Occurence </th>
                                        <th>Household Number</th>
                                        <th>Household Head</th>
                                        <th>Families in Evacuation Center</th>
                                        <th>Missing Persons</th>
                                        <th>Injured Persons</th>
                                        <th>Dead Persons</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 0;
                                    ?>
                                    <?php foreach ($data_array as $key => $result): ?>
                                        <tr>
                                            
                                            <td>
                                                <?=date("F j, Y h:i A", strtotime($result['Date_and_Time_of_Occurrence']))?>
                                            </td>
                                            <td><?=$result['Household_Information/Household_Number']?></td>
                                            <td><?=$result['Household_Information/Household_Head']?></td>
                                            <td><?=ucwords($result['Household_Information/Does_the_household_have_evacuated'])?></td>
                                            <td>
                                                <?=(@$result['Missing_Person'] ? @count($result['Missing_Person']) : '0')?>
                                            </td>
                                            <td><?=(@$result['Injured_Person'] ? @count($result['Injured_Person']) : '0')?></td>
                                            <td><?=(@$result['Dead_Person'] ? @count($result['Dead_Person']) : '0')?></td>
                                            <td>
                                                <?php
                                                    if (!empty(@count($result['Missing_Person'])) || !empty(@count($result['Injured_Person'])) || !empty(@count($result['Dead_Person']))):
                                                        echo '<a href="rapid_information.php?disaster='.$_GET['disaster'].'&key='.$i.'" class="btn btn-primary btn-xs">View Information</a>';
                                                    endif;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php ++$i; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </figure>
                    </div>
                </main>
            </div>
        </div>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>
</body>

</html>
