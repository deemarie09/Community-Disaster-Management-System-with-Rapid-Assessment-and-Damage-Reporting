<?php

$c = false; 

$vertices_x = array(11.08820718436736 , 11.082732285536572, 11.076078039769667, 11.081974214543582, 11.08980752004785, 11.091323619452883);  //latitude points of polygon
$vertices_y = array(122.47592926025392, 122.47241020202638, 122.47824668884279, 122.49189376831056, 122.4913787841797, 122.48193740844728);   //longitude points of polygon
$points_polygon = count($vertices_x); 
$latitude =  11.084357; //latitude of point to be checked
$longitude =  122.479199; //longitude of point to be checked

if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude, $longitude)){
    echo "Is in polygon!"."<br>";
}
else { 
    echo "Is not in polygon"; 
}

function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
    $i = $j = $c = 0;

    for ($i = 0, $j = $points_polygon-1; $i < $points_polygon; $j = $i++) {
        if (($vertices_y[$i] >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i])) {
            $c = !$c;
        }
    }

    return $c;
}