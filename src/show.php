<?php

$headers = [
    "Example",
    "Authorization: token 2a8bf685a9cc56a485723accc660d047e22305d2"
];

$ch = curl_init("https://kc.kobotoolbox.org/api/v1/data/1007947");

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$response = curl_exec($ch);

curl_close($ch);


$data = json_decode($response, true);

// foreach ($data as $repository){

//     echo $repository ["interviewer"], " ",
//      $repository ["barangay"], " ",
//      $repository ["purok_sitio"], "<br> ";
// }
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


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>


    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>

    <script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
   <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.classless.min.css">

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
            <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="household.php"><i class="fas fa-house-user"></i> Household</a></li>
            <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li>
            <li><a href="#"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
       
    </div>

        <div class="main_content" >
        <div class="header">Damage Assessment <style> .header {text-align: center;
        font-size: 30px;
        } </style></div> 
        <main>
          
         <!-- <h1>Damage Assessment <style> h1 {text-align: center;} </style></h1> -->
          <div class="container" style=" width: 180vh;">
          <dl>
            
              <dt>Household Number</dt>
              <dd><?= $data["Household_Number"] ?></dd>
              <dt>Household Head</dt>
              <dd><?= htmlspecialchars($data["Household_Head"]) ?></dd>
              
          </dl>   
       
</div>
   
        </main>

</body>

</html>

