<?php
include('api.config.php');

$kobo_api = new KOBO_API();

$data_damage = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$data_rapid = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
//$sortedArray = $kobo_api->sortRawData($data);

$new_data_rapid = $kobo_api->recursive_change_key($data_rapid, array('Household_Information/Household_Number' => 'Household_Number', 'Household_Information/Household_Head' => 'Household_Head', 'Date_and_Time_of_Occurrence' => 'Date_and_Time_of_Disaster_Occurrence'));
$arr = array_merge($data_damage, $new_data_rapid);

$sortedArray = $kobo_api->sortRawData($arr);

$data_array = $sortedArray[$_GET['disaster']];
$finalArr = $kobo_api->grouped_types($data_array, 'Disaster_Event');

//var_dump($grouped_types);

// echo "<pre>";
// print_r($finalArr);



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

    <title>CDMS</title>

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
        <h2> <img src="images/1212.png"></h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li>
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution.php"><i class="fas fa-file-alt"></i> Distribution</a></li>
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

        <div class="main_content" >
            <div class="header">Reports</div> 
                <main> 
                    <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
                    <div class="container">
                        <a href="reports.php" class="btn btn-info btn-md pull-right">Back</a>
                        <br />
                        <figure>
                            <table id="table" class="table table-bordered table-striped table-hovered text-center">
                                <thead>
                                    <tr>
                                        <th>Disaster Name</th>
                                        <!-- <th>Date of Occurence</th> -->
                                        <th></th>
                                        
                                       <!--  <th>DAP</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 0;
                                        foreach ($finalArr as $key => $res):
                                            if (!empty($key)):   
                                    ?>  
                                                <tr>
                                                    <td>
                                                        <?php
                                                            echo $key;
                                                        ?>
                                                    </td>
                                                    <td><a href="generate_pdf.php?disaster=<?=$_GET['disaster']?>&disaster_name=<?=$key?>" target="_blank" class="btn btn-info btn-xs">Generate Report</a></td>
                                                </tr>
                                    <?php
                                            endif;
                                        ++$i;
                                        endforeach;
                                    ?>
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
