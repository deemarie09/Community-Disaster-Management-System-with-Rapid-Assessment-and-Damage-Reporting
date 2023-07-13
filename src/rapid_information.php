<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
$sortedArray = $kobo_api->sortRawData($data);

// echo "<pre>";
// print_r($sortedArray[$_GET['disaster']][$_GET['key']]);

$data_array = $sortedArray[$_GET['disaster']][$_GET['key']];

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
         <h2> <img src="images/1.png" style="width: 200px; height: 150px" ></h2>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
           <!--  <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li> -->
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="distribution_route.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
            <!-- <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

        <div class="main_content" >
            <div class="header">Rapid Needs Assessment</div> 
                <main> 
                    <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
                    <div class="container">
                        <a href="rapid_details.php?disaster=<?=$_GET['disaster']?>" class="btn btn-info btn-md pull-right">Back</a>
                        <br />
                        <figure>
                            <?php
                                if (!empty(@count($sortedArray[$_GET['disaster']][$_GET['key']]['Missing_Person']))):
                                $i = 0;
                            ?>
                                <h3>Missing Persons</h3>
                                <table id="missing-table" class="table table-bordered table-striped table-hovered text-center">
                                    <thead>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Part of DAP</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sortedArray[$_GET['disaster']][$_GET['key']]['Missing_Person'] as $key => $result): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $kobo_img_url = "https://kc.kobotoolbox.org/media/original?media_file=deemarie_solvero09/attachments/";
                                                        $form_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['formhub/uuid'];
                                                        $data_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['_uuid'];
                                                        $img_url = $sortedArray[$_GET['disaster']][$_GET['key']]['Missing_Person'][$i]['Missing_Person/Picture'];

                                                        $img_url = str_replace(array("(",")"), "", $img_url);
                                                        $img_url = str_replace(" ", "_", $img_url);

                                                        $final_img_url = $kobo_img_url.$form_uuid."/".$data_uuid."/".$img_url;

                                                        // if (!file_exists('uploaded_img/'.$img_url)) :
                                                        //     file_put_contents('uploaded_img/', file_get_contents($final_img_url));
                                                        // else:
                                                        //     echo "error";
                                                        // endif;

                                                        //$image = $kobo_api->getImageFromKobo($final_img_url);

                                                        // print_r($image);

                                                        #echo $image;
                                                    ?>
                                                    <img src="<?=$final_img_url?>" alt="" width="150" loading="lazy">
                                                </td>
                                                <td><?=ucwords($result['Missing_Person/Last_Name'])?>, <?=ucwords($result['Missing_Person/First_Name'])?></td>
                                                <td><?=ucwords($result['Missing_Person/Age'])?></td>
                                                <td><?=ucwords($result['Missing_Person/Gender'])?></td>
                                                 <td><?=ucwords($result['Missing_Person/Missing_Part_of_DAP_S'])?></td>
                                            </tr>
                                        <?php ++$i; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php
                                endif;
                            ?>

                            <?php
                                if (!empty(@count($sortedArray[$_GET['disaster']][$_GET['key']]['Injured_Person']))):
                                $i = 0;
                            ?>
                                <h3>Injured Persons</h3>
                                <table id="missing-table" class="table table-bordered table-striped table-hovered text-center">
                                    <thead>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Part of DAP</th>
                                        <th>Type of Injury</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sortedArray[$_GET['disaster']][$_GET['key']]['Injured_Person'] as $key => $result): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $kobo_img_url = "https://kc.kobotoolbox.org/media/original?media_file=deemarie_solvero09/attachments/";
                                                        $form_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['formhub/uuid'];
                                                        $data_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['_uuid'];
                                                        $img_url = $sortedArray[$_GET['disaster']][$_GET['key']]['Injured_Person'][$i]['Injured_Person/Injured_Picture'];

                                                        $img_url = str_replace(array("(",")"), "", $img_url);
                                                        $img_url = str_replace(" ", "_", $img_url);

                                                        $final_img_url = $kobo_img_url.$form_uuid."/".$data_uuid."/".$img_url;

                                                        // if (!file_exists('uploaded_img/'.$img_url)) :
                                                        //     file_put_contents('uploaded_img/', file_get_contents($final_img_url));
                                                        // else:
                                                        //     echo "error";
                                                        // endif;

                                                        #$image = $kobo_api->getImageFromKobo($final_img_url);

                                                        #print_r($image);
                                                        #echo $final_img_url;
                                                    ?>
                                                    <img src="<?=$final_img_url?>" alt="" width="150" loading="lazy">
                                                </td>
                                                <td><?=ucwords($result['Injured_Person/Injured_Last_Name'])?>, <?=ucwords($result['Injured_Person/Injured_First_Name'])?></td>
                                                <td><?=ucwords($result['Injured_Person/Injured_Age'])?></td>
                                                <td><?=ucwords($result['Injured_Person/Injured_Gender'])?></td>
                                                <td><?=(ucwords(@$result['Injured_Person/Injured_Part_of_DAP_S']) ? ucwords(@$result['Injured_Person/Injured_Part_of_DAP_S']) : 'N/A')?></td>
                                                <td><?=ucwords($result['Injured_Person/Injured_Type_of_Injury'])?></td>
                                            </tr>
                                        <?php ++$i; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php
                                endif;
                            ?>

                            <?php
                                if (!empty(@count($sortedArray[$_GET['disaster']][$_GET['key']]['Dead_Person']))):
                                $i = 0;
                            ?>
                                <h3>Dead Persons</h3>
                                <table id="missing-table" class="table table-bordered table-striped table-hovered text-center">
                                    <thead>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Part of DAP</th>
                                        <th>Cause of Death</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sortedArray[$_GET['disaster']][$_GET['key']]['Dead_Person'] as $key => $result): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $kobo_img_url = "https://kc.kobotoolbox.org/media/original?media_file=deemarie_solvero09/attachments/";
                                                        $form_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['formhub/uuid'];
                                                        $data_uuid = $sortedArray[$_GET['disaster']][$_GET['key']]['_uuid'];
                                                        $img_url = $sortedArray[$_GET['disaster']][$_GET['key']]['Dead_Person'][$i]['Dead_Person/Dead_Picture'];

                                                        $img_url = str_replace(array("(",")"), "", $img_url);
                                                        $img_url = str_replace(" ", "_", $img_url);

                                                        $final_img_url = $kobo_img_url.$form_uuid."/".$data_uuid."/".$img_url;

                                                        // if (!file_exists('uploaded_img/'.$img_url)) :
                                                        //     file_put_contents('uploaded_img/', file_get_contents($final_img_url));
                                                        // else:
                                                        //     echo "error";
                                                        // endif;

                                                        //$image = $kobo_api->getImageFromKobo($final_img_url);

                                                        // print_r($image);
                                                    ?>
                                                    <img src="<?=$final_img_url?>" alt="" width="150" loading="lazy">
                                                </td>
                                                <td><?=ucwords($result['Dead_Person/Dead_Last_Name'])?>, <?=ucwords($result['Dead_Person/Dead_First_Name'])?></td>
                                                <td><?=ucwords($result['Dead_Person/Dead_Age'])?></td>
                                                <td><?=ucwords($result['Dead_Person/Dead_Gender'])?></td>
                                                <td><?=(ucwords(@$result['Dead_Person/Injured_Part_of_DAP_S']) ? ucwords(@$result['Dead_Person/Injured_Part_of_DAP_S']) : 'N/A')?></td>
                                                <td><?=ucwords($result['Dead_Person/Dead_Cause_of_Death'])?></td>
                                            </tr>
                                        <?php ++$i; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            <?php
                                endif;
                            ?>
                        </figure>
                    </div>
                </main>
            </div>
        </div>

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    // $(document).ready(function () {
    //     $('#table').DataTable();
    // });
</script>
</body>

</html>