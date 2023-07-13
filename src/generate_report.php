<?php
include('api.config.php');

$kobo_api = new KOBO_API();

$data_damage = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);
$data_rapid = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
//$sortedArray = $kobo_api->sortRawData($data);

$new_data_rapid = $kobo_api->recursive_change_key($data_rapid, array(
                                                                    'Household_Information/Household_Number' => 'Household_Number',
                                                                    'Household_Information/Household_Head' => 'Household_Head',
                                                                    'Date_and_Time_of_Occurrence' => 'Date_and_Time_of_Disaster_Occurrence',
                                                                    'Household_Information/Does_the_household_have_evacuated' => 'Does_the_household_have_evacuated',

                                                                    ));
$arr = array_merge($data_damage, $new_data_rapid);

$sortedArray = $kobo_api->sortRawData($arr);

$data_array = $sortedArray[$_GET['disaster']];
$finalArr = $kobo_api->grouped_types($data_array, 'Name_of_Type_of_Disaster');

$report_arr = $finalArr[$_GET['disaster_name']];

// echo "<pre>";
// print_r($report_arr);

if (isset($_POST['submit'])):
    $filename = 'CBDMS Report'.date("Ymdhis").'.html';
    header('Content-disposition: attachment; filename=' . $filename);
    header('Content-type: text/html');
endif;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CBDMS | Report</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style type="text/css">
        th, td {
            border: 0 !important;
        }
        hr {
            border-top: 3px solid #000 !important;
        }
    </style>

</head>
<body>

    <div class="container-fluid">

        <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <input type="submit" name="submit" class="btn btn-info btn-lg pull-right" style="margin-top:20px; margin-right: 40px;" value="Download" />
        </form>
        
        <img class="img-responsive" src="images/1212.png" style="max-width: 300px;display: block; margin: 0 auto;">
        
        <h1 class="text-center"><b>RAPID DAMAGE ASSESSMENT AND NEEDS ANALYSIS (Barangay Report)</b></h1>

        <hr />

        <table class="table table-borderless">
            <thead>
                <tr colspan="4"><th><h4><b>Profile of Calamity</b></h4></th></tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width:250px;"><b>Type of Disaster: </b></td>
                    <td>
                        <?php
                            $disaster_split = explode('-',trim($_GET['disaster']));

                            echo ucwords(str_replace("_", " ", $disaster_split[1]));
                        ?>
                    </td>

                    <td style="width:200px;"><b>Affected Barangay: </b></td>
                    <td>Brgy. Bonbon, Lambunao, Iloilo</td>
                </tr>
                <tr>
                    <!-- <td><b>Name of Disaster: </b></td>
                    <td><?=$_GET['disaster_name']?></td>
 -->
                    <td><b>Disaster Event: </b></td>
                    <td>
                        <?php
                            $disaster_split = explode('-',trim($_GET['disaster']));

                            echo ucwords(str_replace("_", " ", $disaster_split[0]));
                        ?>
                    </td>
                </tr>
                <!-- <tr>
                    <td><b>Date/Time of Occurrence: </b></td>
                    <td><?=$_GET['cleandate']?></td>
                </tr> -->
            </tbody>
        </table>

        <hr />

        <table class="table table-borderless">
            <thead>
                <tr><th colspan="4"><h4><b>Population</b></h4></th></tr>
            </thead>

            <tbody>
                <tr>
                    <td style="width:275px;"><b>Number of Affected Persons: </b></td>
                    <td style="width:250px;">
                        <?php 
                            $persons_affected = 0;

                            foreach ($report_arr as $key => $result):
                                if (@count($result['Missing_Person'])) $persons_affected += @count($result['Missing_Person']);
                                if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                            endforeach;

                        ?>
                        <?=$persons_affected?>
                    </td>

                    <td style="width:300px;"><b>Number of Families in Evacuation Center: </b></td>
                    <td>
                        <?php

                            $evacuated_families = 0;

                            foreach ($report_arr as $key => $result):
                                if (strtolower(@$result['Does_the_household_have_evacuated']) == "yes"):
                                    if (@count($result['Does_the_household_have_evacuated'])) $evacuated_families += @count($result['Does_the_household_have_evacuated']);
                                endif;
                            endforeach;

                        ?>
                        <?=$evacuated_families?>
                    </td>
                </tr>
                <tr>
                    <td><b>Number of Affected Households: </b></td>
                    <td>
                        <?php
                            $household_affected = 0;

                            foreach ($report_arr as $result):
                                //echo @count($result['Household_Number']);
                                if (@count($result['Household_Number'])) $household_affected += @count($result['Household_Number']);
                            endforeach; 
                        ?>
                        <?=$household_affected?>
                    </td>

                </tr>
                <tr>
                    <td><b>Number of Families: </b></td>
                    <td><?=$household_affected?></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless pull-left" style="width: 33.33%;">
            <tbody>
                <tr>
                    <td colspan="2">
                        <b>No. Of Missing Persons: </b>
                        <?php 
                            $missing_person = 0;

                            foreach ($report_arr as $key => $result):
                                if (@count($result['Missing_Person'])) $missing_person += @count($result['Missing_Person']);
                                // if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                // if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                            endforeach;

                        ?>
                        <?=$missing_person?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Children: </b>
                        <?php

                            $children = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Missing_Person']):
                                    
                                    foreach ($result['Missing_Person'] as $k => $res):
                                        if ($res['Missing_Person/Age'] <= 18):
                                            $children++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$children?>
                    </td>
                    <td>
                        <b>Adults: </b>
                        <?php

                            $adults = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Missing_Person']):
                                    
                                    foreach ($result['Missing_Person'] as $k => $res):
                                        if ($res['Missing_Person/Age'] <= 60 || $res['Missing_Person/Age'] > 18):
                                            $adults++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$adults?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>DAP's: </b>
                        <?php

                            $dap_missing = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Missing_Person']):

                                    foreach ($result['Missing_Person'] as $k => $res):
                                        if (@$res['Missing_Person/Missing_Part_of_DAP_S']) :
                                            if (strtolower($res['Missing_Person/Missing_Part_of_DAP_S']) == "yes"):
                                                $dap_missing++;
                                            endif;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$dap_missing?>
                    </td>
                    <td>
                        <b>Senior Citizen: </b>
                        <?php

                            $senior = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Missing_Person']):
                                    
                                    foreach ($result['Missing_Person'] as $k => $res):
                                        if ($res['Missing_Person/Age'] > 60):
                                            $senior++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$senior?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless pull-left" style="width: 33.33%;">
            <tbody>
                <tr>
                    <td colspan="2">
                        <b>No. Of Injured Persons: </b>
                        <?php 
                            $injured_persons = 0;

                            foreach ($report_arr as $key => $result):
                                if (@count($result['Injured_Person'])) $injured_persons += @count($result['Injured_Person']);
                                // if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                // if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                            endforeach;

                        ?>
                        <?=$injured_persons?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Children: </b>
                        <?php

                            $children = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Injured_Person']):
                                    
                                    foreach ($result['Injured_Person'] as $k => $res):
                                        if ($res['Injured_Person/Injured_Age'] <= 18):
                                            $children++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$children?>
                    </td>
                    <td>
                        <b>Adults: </b>
                        <?php

                            $adults = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Injured_Person']):
                                    
                                    foreach ($result['Injured_Person'] as $k => $res):
                                        if ($res['Injured_Person/Injured_Age'] <= 60 || $res['Injured_Person/Injured_Age'] > 18):
                                            $adults++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$adults?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>DAP's: </b>
                        <?php

                            $dap_missing = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Injured_Person']):
                                    
                                    foreach ($result['Injured_Person'] as $k => $res):
                                        if (@$res['Injured_Person/Injured_Part_of_DAP_S']) :
                                            if (strtolower($res['Injured_Person/Injured_Part_of_DAP_S']) == "yes"):
                                                $dap_missing++;
                                            endif;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$dap_missing?>
                    </td>
                    <td>
                        <b>Senior Citizen: </b>
                        <?php

                            $senior = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Injured_Person']):
                                    
                                    foreach ($result['Injured_Person'] as $k => $res):
                                        if ($res['Injured_Person/Injured_Age'] > 60):
                                            $senior++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$senior?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless pull-left" style="width: 33.33%;">
            <tbody>
                <tr>
                    <td colspan="2">
                        <b>No. Of Dead Persons: </b>
                        <?php 
                            $dead_persons = 0;

                            foreach ($report_arr as $key => $result):
                                if (@count($result['Dead_Person'])) $dead_persons += @count($result['Dead_Person']);
                                // if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
                                // if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
                            endforeach;

                        ?>
                        <?=$dead_persons?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Children: </b>
                        <?php

                            $children = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Dead_Person']):
                                    
                                    foreach ($result['Dead_Person'] as $k => $res):
                                        if ($res['Dead_Person/Dead_Age'] <= 18):
                                            $children++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$children?>
                    </td>
                    <td>
                        <b>Adults: </b>
                        <?php

                            $adults = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Dead_Person']):
                                    
                                    foreach ($result['Dead_Person'] as $k => $res):
                                        if ($res['Dead_Person/Dead_Age'] <= 60 || $res['Dead_Person/Dead_Age'] > 18):
                                            $adults++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$adults?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>DAP's: </b>
                        <?php

                            $dap_missing = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Dead_Person']):
                                    
                                    foreach ($result['Dead_Person'] as $k => $res):
                                        if (@$res['Dead_Person/Dead_Part_of_DAP_S']) :
                                            if (strtolower($res['Dead_Person/Dead_Part_of_DAP_S']) == "yes"):
                                                $dap_missing++;
                                            endif;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$dap_missing?>
                    </td>
                    <td>
                        <b>Senior Citizen: </b>
                        <?php

                            $senior = 0;

                            foreach ($report_arr as $key => $result):
                                if (@$result['Dead_Person']):
                                    
                                    foreach ($result['Dead_Person'] as $k => $res):
                                        if ($res['Dead_Person/Dead_Age'] > 60):
                                            $senior++;
                                        endif;
                                    endforeach;

                                endif;
                            endforeach;

                        ?>
                        <?=$senior?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="clearfix"></div>

        <hr />

        <h4><b>Damage Report</b></h4>

        <table class="table table-borderless">
            <thead>
                <tr><th></th></tr>
                <th>Houses</th>
                <th>Bridges</th>
                <th>Roads</th>
                <th>School</th>
                <th>Community Gym</th>
                <th>Barangay Hall</th>
                <th>Electrical Lines</th>
            </thead>

            <tbody>
                <tr>
                    <td style="width:250px;"><b>Partially Damaged</b></td>

                    <?php

                        $partially_house = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "house" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_house++;
                            endif;

                        endforeach;

                        $partially_bridge = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "bridge" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_bridge++;
                            endif;

                        endforeach;

                        $partially_roads = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "roads" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_roads++;
                            endif;

                        endforeach;

                        $partially_school = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "school" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_school++;
                            endif;

                        endforeach;

                        $partially_gym = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "community_gym" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_gym++;
                            endif;

                        endforeach;

                        $partially_bh = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "barangay_hall" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_bh++;
                            endif;

                        endforeach;

                        $partially_elines = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "electrical_lines" && strtolower(@$result['Type_of_Damage']) == "partially"):
                                $partially_elines++;
                            endif;

                        endforeach;

                    ?>

                    <td><?=$partially_house?></td>
                    <td><?=$partially_bridge?></td>
                    <td><?=$partially_roads?></td>
                    <td><?=$partially_school?></td>
                    <td><?=$partially_gym?></td>
                    <td><?=$partially_bh?></td>
                    <td><?=$partially_elines?></td>
                </tr>
                <tr>
                    <td style="width:250px;"><b>Totally Damaged</b></td>

                    <?php

                        $totally_house = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "house" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_house++;
                            endif;

                        endforeach;

                        $totally_bridge = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "bridge" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_bridge++;
                            endif;

                        endforeach;

                        $totally_roads = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "roads" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_roads++;
                            endif;

                        endforeach;

                        $totally_school = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "school" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_school++;
                            endif;

                        endforeach;

                        $totally_gym = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "community_gym" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_gym++;
                            endif;

                        endforeach;

                        $totally_bh = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "barangay_hall" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_bh++;
                            endif;

                        endforeach;

                        $totally_elines = 0;

                        foreach ($report_arr as $key => $result):
                            
                            if (strtolower(@$result['Type_of_Infrastructure']) == "electrical_lines" && strtolower(@$result['Type_of_Damage']) == "totally"):
                                $totally_elines++;
                            endif;

                        endforeach;

                    ?>
                    
                    <td><?=$totally_house?></td>
                    <td><?=$totally_bridge?></td>
                    <td><?=$totally_roads?></td>
                    <td><?=$totally_school?></td>
                    <td><?=$totally_gym?></td>
                    <td><?=$totally_bh?></td>
                    <td><?=$totally_elines?></td>
                </tr>
                <tr>
                    <td style="width:250px;"><b>Total</b></td>

                    <?php

                        $total_house = $partially_house + $totally_house;
                        $total_bridge = $partially_bridge + $totally_bridge;
                        $total_roads = $partially_roads + $totally_roads;
                        $total_school = $partially_school + $totally_school;
                        $total_gym = $partially_gym + $totally_gym;
                        $total_bh = $partially_bh + $totally_bh;
                        $total_elines = $partially_elines + $totally_elines;

                    ?>
                    <td><?=$total_house?></td>
                    <td><?=$total_bridge?></td>
                    <td><?=$total_roads?></td>
                    <td><?=$total_school?></td>
                    <td><?=$total_gym?></td>
                    <td><?=$total_bh?></td>
                    <td><?=$total_elines?></td>
                </tr>
            </tbody>
        </table>

    </div>

</body>
</html>