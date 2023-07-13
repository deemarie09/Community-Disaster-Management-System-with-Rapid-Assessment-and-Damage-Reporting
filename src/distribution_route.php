<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
#$sortedArray = $kobo_api->sortRawData($data);

// echo "<pre>";
// print_r($data);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
      crossorigin="anonymous">
      <link 
      rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/leaflet@0.7.7/dist/leaflet.css"
      />
    <!-- Custom styles for this template -->
    <link href="dijkstra/style/justified-nav.css" rel="stylesheet">

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
            <li><a href="#"><i class="fas fa-house-user"></i> Household</a></li>
            <li><a href="#"><i class="fas fa-inbox"></i> Messages</a></li>
            <li><a href="rapid.php"><i class="fas fa-file-alt"></i> Rapid Needs Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Damage Assessment</a></li>
            <li><a href="Damage.php"><i class="fas fa-file-alt"></i> Distribution</a></li>
            <!-- <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li> -->
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul> 
    </div>

    <div class="main_content" style="margin-left: 225px; padding-right: 25px; overflow: hidden;">
        <!-- <div id="map" style="width: 95%; height: 100vh; margin-left: 10px;"></div> -->
      <div class="row">
        <div class="col-lg-12">
          <form>
            <div class="form-row align-items-center">
              <div class="col-auto">
                <label class="mr-sm-2" for="from">From</label>
                <select id="from-starting" class="custom-select mb-2 mr-sm-2 mb-sm-0"></select>
              </div>
              <div class="col-auto">
                <label class="mr-sm-2" for="to">To</label>
                <select id="to-end" class="custom-select mb-2 mr-sm-2 mb-sm-0"></select>
              </div>

              <div class="col-auto">
                <button type="button" id="clearmap" class="btn btn-danger">Clear Map</button>
              </div>
              <div class="col-auto">
                <button type="button" id="getshortestroute" class="btn btn-success" title="find shortest path between nodes"> Start Route</button>
              </div>
              <!--div class="col-auto">
                <label class="sr-only" for="inlineFormInput">Latitude</label>
                <input type="text" class="form-control mb-2 mb-sm-0" id="latitude" placeholder="Enter Latitude">
              </div>
              <div class="col-auto">
                <label class="sr-only" for="inlineFormInput">Longitude</label>
                <input type="text" class="form-control mb-2 mb-sm-0" id="longitude" placeholder="Enter Longitude">
              </div>
              
              <div class="col-auto">
                <button type="button" id= "getmethere"class="btn btn-primary">Get Me There</button>
              </div>
             
              <div class="col-auto" style="margin-top: 5px;">

                <label class="mr-sm-2" for="inlineFormCustomSelect">Load Examples</label>
                <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="setexample">
                  <option value="0">Ex:</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>          
                </select>
              </div-->
            </div>
          </form>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div id="svg-map" style="width: 100%; height: 800px" class="card"></div>
          <!-- <form style="margin-top:5px;">
            <div class="form-row align-items-center">
              <div class="col-auto">
                <button type="button" class="btn btn-success" id="data-export"> Export Data</button>
              </div>
              <div class="col-auto">
                <label class="custom-file">
                  <input type="file" id="data-import" class="custom-file-input">
                  <span class="custom-file-control">Import Data</span>
                </label>
              </div>
            </div>
          </form> -->
        </div>
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

    <!-- Bootstrap core JavaScript
    ================================================== -->

    <!-- <script type="text/javascript" src="js/d3.v3.min.js"></script> Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
      crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3@3.3.0/d3.min.js"></script>
      
    
    <script src="https://cdn.jsdelivr.net/npm/leaflet@0.7.7/dist/leaflet.js">
    </script>

    <script src="dijkstra/js/main.js?<?=mt_rand()?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="dijkstra/js/ie10-viewport-bug-workaround.js?<?=mt_rand()?>"></script>

</body>

</html>