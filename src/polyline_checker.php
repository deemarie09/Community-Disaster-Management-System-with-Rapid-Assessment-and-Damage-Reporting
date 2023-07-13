<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$points_to_check = $kobo_api->getDataByAPI(MASTER_LIST_API);

#echo "<pre>";
#print_r($points_to_check);

$data = json_decode($_POST["info"], TRUE);

foreach ($data as $points):

    $vertices_x[] = $points['lat'];
    $vertices_y[] = $points['lng'];

endforeach;

// //$vertices_x = array(11.08820718436736 , 11.082732285536572, 11.076078039769667, 11.081974214543582, 11.08980752004785, 11.091323619452883);  //latitude points of polygon
// //$vertices_y = array(122.47592926025392, 122.47241020202638, 122.47824668884279, 122.49189376831056, 122.4913787841797, 122.48193740844728);   //longitude points of polygon
// $latitude =  11.084357; //latitude of point to be checked
// $longitude =  122.479199; //longitude of point to be checked

$points_polygon = count($data);

foreach ($points_to_check as $res) :

    if (!empty($res['hh_info/hh_location'])):
        $loc = explode(" ", $res['hh_info/hh_location']);

        if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $loc[0], $loc[1])){
            #echo "Is in polygon! - ".$res['hh_info/hh_household_number']."<br>";
            $arr[] = array(
                            "household_number" => $res['hh_info/hh_household_number'],
                            "lat" => $loc[0],
                            "long" => $loc[1],
                            "primary_number" => $res['respondent_info/primary_contact_number'],
                            "household_head" => $res['respondent_info/respondent_name']
                            );
        }
        
    endif;

endforeach;

echo json_encode($arr);

function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
    $i = $j = $c = 0;

    for ($i = 0, $j = $points_polygon-1; $i < $points_polygon; $j = $i++) {
        if (($vertices_y[$i] >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
            $c = !$c;
        }
    }

    return $c;
}