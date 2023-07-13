<?php 
include 'config.php';
include('api.config.php');

session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
}

$kobo_api = new KOBO_API();

$data_damage = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$data_master = $kobo_api->getDataByAPI(MASTER_LIST_API);
$data_rapid = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
#$sortedArray = $kobo_api->sortRawData($data);

$new_data_rapid = $kobo_api->recursive_change_key($data_rapid, array(
                                                                    'Household_Information/Household_Number' => 'Household_Number',
                                                                    'Household_Information/Household_Head' => 'Household_Head',
                                                                    'Date_and_Time_of_Occurrence' => 'Date_and_Time_of_Disaster_Occurrence',
                                                                    'Household_Information/Does_the_household_have_evacuated' => 'Does_the_household_have_evacuated',
                                                                    'Missing_Person/Gender' => 'Missing_Gender',
                                                                    'Injured_Person/Injured_Gender' => 'Injured_Gender',
                                                                    'Dead_Person/Dead_Gender' => 'Dead_Gender',
                                                                    ));

$arr = array_merge($data_damage, $new_data_rapid);
$sortedArray = $kobo_api->sortRawData($arr);

$persons_affected = 0;
$male_affected = 0;
$female_affected = 0;

foreach ($sortedArray as $key => $res):

    foreach ($res as $result):
        if (@count($result['Missing_Person'])) $persons_affected += @count($result['Missing_Person']);
        if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
        if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);
    endforeach;   
endforeach;

foreach ($sortedArray as $key => $result):
    foreach ($result as $v):
        if (is_array(@$v['Missing_Person'])): 
            foreach ($v['Missing_Person'] as $res):
                if (strtolower($res['Missing_Gender']) == "female") $female_affected++;
                if (strtolower($res['Missing_Gender']) == "male") $male_affected++;
            endforeach;
        endif;

        if (is_array(@$v['Injured_Person'])): 
            foreach ($v['Injured_Person'] as $res):
                if (strtolower($res['Injured_Gender']) == "female") $female_affected++;
                if (strtolower($res['Injured_Gender']) == "male") $male_affected++;
            endforeach;
        endif;

        if (is_array(@$v['Dead_Person'])): 
            foreach ($v['Dead_Person'] as $res):
                if (strtolower($res['Dead_Gender']) == "female") $female_affected++;
                if (strtolower($res['Dead_Gender']) == "male") $male_affected++;
            endforeach;
        endif;
    endforeach;
endforeach;

#echo $female_affected;

$household_affected = 0;

foreach ($sortedArray as $res):
    foreach ($res as $result):
        if (@count($result['Household_Number'])) $household_affected += @count($result['Household_Number']);
    endforeach;
endforeach;

$evacuated_families = 0;

foreach ($sortedArray as $key => $res):
    foreach ($res as $result):
        if (strtolower(@$result['Does_the_household_have_evacuated']) == "yes"):
            if (@count($result['Does_the_household_have_evacuated'])) $evacuated_families += @count($result['Does_the_household_have_evacuated']);
        endif;
    endforeach;
endforeach;

$damaged_facilities = 0;

foreach ($sortedArray as $key => $res):
    foreach ($res as $result):
        if (@count($result['Type_of_Infrastructure'])):
            $damaged_facilities++;
        endif;
    endforeach;
endforeach;

// echo "<pre>";
// print_r($sortedArray);
    
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

    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    

</head>

    

<title>
    CDMS
</title>


<body>

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
            <li><a href="distribution.php"><i class="fas fa-map-marker"></i> Distribution</a></li>
           <!--  <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
       
    </div>



        <div class="main_content">
            <div id="header-image-menu">
            <div class="container">

   <div class="profile">
      <?php
         $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select) > 0){
            $fetch = mysqli_fetch_assoc($select);
         }
         if($fetch['image'] == ''){
            echo '<img src="images/default-avatar.png">';
         }else{
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }
      ?>
      <h3 style="float: left; margin-left: 20px;"> Welcome, <?php echo $fetch['name']; ?></h3>
     <!--  <a href="update_profile.php" class="btn">update profile</a> -->
      
   </div>

</div>
 
      
        <img src="images/bg.jpg" style="margin-right: 5px; margin-left: 7px; margin-bottom: 20px; margin-top: 10px; width: 99%; height: 40vh;">
        <div>

            <div class="col-md-12 text-center">
                <form id="filter_field" method="GET" action="#" role="form" onchange="update_dashboard(this); return false;">
                    <label>Filter By:</label>
                    <select class="form-control" id="select_filter" name="select_filter" style="width: 30%;margin: 0 auto;">
                        <option value="-">Select Disaster</option>
                        <?php
                            foreach ($sortedArray as $key => $res):
                                echo "<option value='".$key."'>".$key."</option>";
                            endforeach;

                        ?>
                    </select>
                </form>
            </div>

            <div class="row">

              <div class="column">
                <div class="card">
                  <p><i class="fa fa-user-friends"></i></p>
                  <h3 class="persons_affected" style="color: #000;"> <?=$persons_affected?> </h3>
                  <p style="color: #000;">Affected Persons</p>
                </div>
              </div>

              <div class="column">
                <div class="card">
                  <p><i class="fa fa-people-carry"></i></p>
                  <h3 class="evacuated_families" style="color: #000;"> <?=$evacuated_families?> </h3>
                  <p style="color: #000;">Families Evacuated</p>
                </div>
              </div>

              <div class="column">
                <div class="card">
                  <p><i class="fa fa-house-damage"></i></p>
                  <h3 class="damaged_facilities" style="color: #000;"> <?=$damaged_facilities?> </h3>
                  <p style="color: #000;">Damaged Facilities</p>
                </div>
              </div>
            </div>

            <div class="row" style="margin-top: 50px;">
                <div class="column" style="width: 50%;">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>

                <div class="column" style="width: 50%;">
                    <div id="chartContainerPyramid" style="height: 370px; width: 100%;"></div>
                </div>
            </div>
        </div>

<!-- <div>
   <h1> Chart js</h1> 
</div>
 -->
  <div>
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

</main>

<script type="text/javascript">
    
    $(document).ready(function(){
        console.log("ready");


        update_dashboard = function(e) {
            console.log('update_dashboard.php?disaster='+$("#select_filter").val());

            $.ajax({
                url: 'update_dashboard.php?disaster='+$("#select_filter").val(),
                type: 'GET',
                //data: $("#select_filter").val(),
                dataType: "JSON",
                success: function(d) {
                    console.log(d);

                    $(".persons_affected").html(d.persons_affected);
                    $(".evacuated_families").html(d.evacuated_families);
                    $(".damaged_facilities").html(d.damaged_facilities);

                    update_barGraph(d.female_affected, d.male_affected);
                    update_pyramidGraph(d.female_affected, d.male_affected);
                }
            });
        }

        update_barGraph = function(female, male) {

            //Better to construct options first and then pass it as a parameter
            var options = {
                animationEnabled: true,
                title: {
                    text: "Affected Male / Female",                
                    fontColor: "Peru"
                },  
                axisY: {
                    tickThickness: 0,
                    lineThickness: 0,
                    valueFormatString: " ",
                    includeZero: true,
                    gridThickness: 0                    
                },
                axisX: {
                    tickThickness: 0,
                    lineThickness: 0,
                    labelFontSize: 18,
                    labelFontColor: "Peru"              
                },
                data: [{
                    indexLabelFontSize: 26,
                    toolTipContent: "<span style=\"color:#62C9C3\">{indexLabel}:</span> <span style=\"color:#CD853F\"><strong>{y}</strong></span>",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontWeight: 600,
                    indexLabelFontFamily: "Verdana",
                    color: "#62C9C3",
                    type: "bar",
                    dataPoints: [
                        { y: male, label:" ", indexLabel: "Male - "+male },
                        { y: female, label:" ", indexLabel: "Female - "+female },
                    ]
                }]
            };

            $("#chartContainer").CanvasJSChart(options);

        }

        update_pyramidGraph = function(female, male) {

            var options_pyramid = {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: " Barangay Population"
                },
                data: [{
                    type: "pyramid",
                    indexLabelFontSize: 18,
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    toolTipContent: "<b>{indexLabel}:</b> {y}%",
                    dataPoints: [
                        { y: male, indexLabel: "Male - "+male },
                        { y: female, indexLabel: "Female - "+female },
                    ]
                }]
            };
            $("#chartContainerPyramid").CanvasJSChart(options_pyramid);

        }

        window.onload = function () {

            //Better to construct options first and then pass it as a parameter
            var options = {
                animationEnabled: true,
                title: {
                    text: "Affected Male / Female",                
                    fontColor: "Peru"
                },  
                axisY: {
                    tickThickness: 0,
                    lineThickness: 0,
                    valueFormatString: " ",
                    includeZero: true,
                    gridThickness: 0                    
                },
                axisX: {
                    tickThickness: 0,
                    lineThickness: 0,
                    labelFontSize: 18,
                    labelFontColor: "Peru"              
                },
                data: [{
                    indexLabelFontSize: 26,
                    toolTipContent: "<span style=\"color:#62C9C3\">{indexLabel}:</span> <span style=\"color:#CD853F\"><strong>{y}</strong></span>",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontWeight: 600,
                    indexLabelFontFamily: "Verdana",
                    color: "#62C9C3",
                    type: "bar",
                    dataPoints: [
                        { y: <?=$male_affected?>, label:" ", indexLabel: "Male - "+<?=$male_affected?> },
                        { y: <?=$female_affected?>, label:" ", indexLabel: "Female - "+<?=$female_affected?> },
                    ]
                }]
            };

            $("#chartContainer").CanvasJSChart(options);

            var options_pyramid = {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Barangay Population"
                },
                data: [{
                    type: "pyramid",
                    indexLabelFontSize: 18,
                    showInLegend: true,
                    legendText: "{indexLabel}",
                    toolTipContent: "<b>{indexLabel}:</b> {y}%",
                    dataPoints: [
                        { y: <?=$male_affected?>, indexLabel: "Male - "+<?=$male_affected?> },
                        { y: <?=$female_affected?>, indexLabel: "Female - "+<?=$female_affected?> },
                    ]
                }]
            };
            $("#chartContainerPyramid").CanvasJSChart(options_pyramid);
        }

    });

</script>
       
</body>

</html>

