<?php

include('api.config.php');

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

if ($_GET['disaster'] != "-") :
	$data_array = $sortedArray[$_GET['disaster']];

	//echo "<pre>";
	//print_r($data_array);

	$persons_affected = 0;
	$male_affected = 0;
	$female_affected = 0;

	foreach ($data_array as $key => $res):  

	    if (is_array(@$res['Missing_Person'])): 
			$persons_affected += count($res['Missing_Person']);
		endif;

		if (is_array(@$res['Injured_Person'])): 
			$persons_affected += count($res['Injured_Person']);
		endif;

		if (is_array(@$res['Dead_Person'])): 
			$persons_affected += count($res['Dead_Person']);
		endif;

	endforeach;

	foreach ($data_array as $key => $result):

		if (is_array(@$result['Missing_Person'])): 
			foreach ($result['Missing_Person'] as $res):
                if (strtolower($res['Missing_Gender']) == "female") $female_affected++;
                if (strtolower($res['Missing_Gender']) == "male") $male_affected++;
            endforeach;
		endif;

		if (is_array(@$result['Injured_Person'])): 
			foreach ($result['Injured_Person'] as $res):
                if (strtolower($res['Injured_Gender']) == "female") $female_affected++;
                if (strtolower($res['Injured_Gender']) == "male") $male_affected++;
            endforeach;
		endif;

		if (is_array(@$result['Dead_Person'])): 
			foreach ($result['Dead_Person'] as $res):
                if (strtolower($res['Dead_Gender']) == "female") $female_affected++;
                if (strtolower($res['Dead_Gender']) == "male") $male_affected++;
            endforeach;
		endif;

	endforeach;

	$household_affected = 0;

	foreach ($data_array as $result):
		if (@count($result['Household_Number'])) $household_affected += @count($result['Household_Number']);
	endforeach;

	$evacuated_families = 0;

	foreach ($data_array as $key => $result):
		if (strtolower(@$result['Does_the_household_have_evacuated']) == "yes"):
	        if (@count($result['Does_the_household_have_evacuated'])) $evacuated_families += @count($result['Does_the_household_have_evacuated']);
	    endif;
	endforeach;

	$damaged_facilities = 0;

	foreach ($data_array as $key => $result):
		if (@count($result['Type_of_Infrastructure'])):
	        $damaged_facilities++;
	    endif;
	endforeach;

else :
	$data_array = $sortedArray;

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

endif;

// echo "<pre>";
// print_r($data_array);


echo json_encode(array(
						"persons_affected" => $persons_affected, 
						"evacuated_families" => $evacuated_families, 
						"damaged_facilities" => $damaged_facilities, 
						"female_affected" => $female_affected,
						"male_affected" => $male_affected,
					));