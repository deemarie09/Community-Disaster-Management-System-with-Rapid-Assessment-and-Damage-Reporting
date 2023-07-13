<?php
include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$sortedArray = $kobo_api->sortRawData($data);

$data_array = $sortedArray[$_GET['disaster']];

$i = 0;
#echo "<pre>";
#print_r($data_array);
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


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
            <!-- <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
            <!-- <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

        <div class="main_content" >
            <div class="header">Damage Assessment</div>
            <a href="Damage.php" class="btn btn-info btn-md pull-right">Back</a>
                <main> 
                   
                    <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
                    <div class="container" style=" width: 180vh;" >
                        <figure>
                            <table class="table table-bordered table-striped table-hovered text-center">
                                <thead>
                                    <tr>
                                        <th>Interviewer</th>
                                        <th>Household No.</th>
                                        <th>Household Head </th>
                                        <th>Type of Infrastructure</th>
                                        <th>Picture </th> 
                                        <th>Type of Damage</th>
                                        <!--th>Date & Time </th-->
                                        <!-- <th>Interviewer</th>
                                        <th>Household #</th> -->
                                        <!--th>Name of Disaster </th-->
                                        <!-- <th>Date & Time of Occurence </th> -->
                                        <!-- <th>Persons Affected</th>
                                        <th>Household Affected</th> -->
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

                                    <?php foreach($data_array as $key => $res): ?>
                                        <tr>
                                            <td><?=$res['Interviewer']?></td>
                                            <td><?=$res['Household_Number']?></td>
                                            <td><?=$res['Name_of_Household_Head']?></td>
                                            <td><?=$res['Type_of_Infrastructure']?></td>
                                            <td>
                                                <?php
                                                    if (is_array($res["_attachments"])):
                                                        //echo 1;

                                                        foreach ($res["_attachments"] as $attachment):
                                                            echo "<a class='btn btn-info btn-xs' target='_blank' href='".$attachment['download_url']."' >View Image</a>";
                                                        endforeach;

                                                    else:
                                                        echo 0;
                                                    endif;

                                                ?>
                                            </td>
                                            <td><?=$res['Type_of_Damage']?></td>
                                            <!-- <td><a href="damage_information.php?disaster=<?=$_GET['disaster']?>&key=<?=$i?>" class="btn btn-primary btn-xs">View Information</a></td> -->
                                        </tr>
                                    <?php
                                        $i++;
                                        endforeach;
                                    ?>

                                </tbody>
                            </table>
                        </figure>
                    </div>
                </main>
            </div>
        </div>
</body>

</html>