<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(MASTER_LIST_API);

// echo "<pre>";
// print_r($data);

//children = hh_family_info/num_hh_children
//children = hh_family_info/num_hh_children
//PWD = hh_member_info/hh_member_info_repeat/hh_member_disability

foreach ($data as $res) :

	if (!empty($res['hh_info/hh_location'])):
	    $loc = explode(" ", $res['hh_info/hh_location']);
	    $arr[] = array(
	    				"Household_Number" => $res['hh_info/hh_household_number'],
	    				"lat" => $loc[0],
	    				"long" => $loc[1]
	    				);
	endif;

	if (!empty($res['hh_info/hh_location'])):
	    $loc = explode(" ", $res['hh_info/hh_location']);
	    if ($res['respondent_info/hh_head'] == "yes"):
	    	$household_name = $res['respondent_info/respondent_name'];
	    else:
	    	$household_name = "No Household Head";
	    endif;

	    if ($_GET['filter_by'] == "children"):
	    	if ($res['hh_family_info/num_hh_children'] != 0):
    		    $arr[] = array(
    				"Household_Number" => $res['hh_info/hh_household_number'],
    				"Num_of_families" => $res['hh_family_info/hh_num_of_families'],
    				"Household_Head" => $household_name,
    				"lat" => $loc[0],
    				"long" => $loc[1]
    				);
	    	endif;
	    elseif ($_GET['filter_by'] == "senior"):
	    	if ($res['hh_member_info/hh_member_info_repeat_count'] != 0):
	    		foreach ($res['hh_member_info/hh_member_info_repeat'] as $senior_data):
	    			if ($senior_data['hh_member_info/hh_member_info_repeat/hh_member_dob_known'] == "no"):
	    				$arr[] = array(
				    				"Household_Number" => $res['hh_info/hh_household_number'],
				    				"Num_of_families" => $res['hh_family_info/hh_num_of_families'],
				    				"Household_Head" => $household_name,
				    				"lat" => $loc[0],
				    				"long" => $loc[1]
				    				);
	    			endif;
	    		endforeach;
	    	endif;
	    elseif ($_GET['filter_by'] == "pwd"):
	    	if ($res['hh_member_info/hh_member_info_repeat_count'] != 0):
	    		foreach ($res['hh_member_info/hh_member_info_repeat'] as $pwd_data):
	    			if ($pwd_data['hh_member_info/hh_member_info_repeat/hh_member_disability'] == "yes"):
	    				$arr[] = array(
				    				"Household_Number" => $res['hh_info/hh_household_number'],
				    				"Num_of_families" => $res['hh_family_info/hh_num_of_families'],
				    				"Household_Head" => $household_name,
				    				"lat" => $loc[0],
				    				"long" => $loc[1]
				    				);
	    			endif;
	    		endforeach;
	    	endif;
	    else:
	    	$arr[] = array(
		    				"Household_Number" => $res['hh_info/hh_household_number'],
		    				"Num_of_families" => $res['hh_family_info/hh_num_of_families'],
		    				"Household_Head" => $household_name,
		    				"lat" => $loc[0],
		    				"long" => $loc[1]
		    				);
		endif; //if ($_GET['filter_by'] == "children"):
	endif;

endforeach;

echo json_encode($arr);

?>