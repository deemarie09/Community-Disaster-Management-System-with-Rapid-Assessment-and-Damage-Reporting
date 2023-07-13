<?php

include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(DAMAGE_ASSESSMENT_API);

// echo "<pre>";
// print_r($data);

foreach ($data as $res) :

	if (!empty($res['Location_of_the_Damaged_Facility'])):

		$loc = explode(" ", $res['Location_of_the_Damaged_Facility']);

		if ($_GET['filter_by'] == "partially"):
			if ($res['Type_of_Damage'] == "partially"):
    		    $arr[] = array(
	    				"Household_Number" => $res['Household_Number'],
	    				"Household_Head" => $res['Name_of_Household_Head'],
	    				"lat" => $loc[0],
	    				"long" => $loc[1]
	    				);
	    	endif;
	    elseif ($_GET['filter_by'] == "totally"):
	    	if ($res['Type_of_Damage'] == "totally"):
    		    $arr[] = array(
	    				"Household_Number" => $res['Household_Number'],
	    				"Household_Head" => $res['Name_of_Household_Head'],
	    				"lat" => $loc[0],
	    				"long" => $loc[1]
	    				);
	    	endif;
	    else:
	    	$arr[] = array(
	    				"Household_Number" => $res['Household_Number'],
	    				"Household_Head" => $res['Name_of_Household_Head'],
	    				"lat" => $loc[0],
	    				"long" => $loc[1]
	    				);
		endif;	    
	endif;

endforeach;

echo json_encode($arr);

?>