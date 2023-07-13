<?php

include('config.php');
include('api.config.php');

$kobo_api = new KOBO_API();

$data = $kobo_api->getDataByAPI(RAPID_NEEDS_ASSESSMENT_API);
$new_data_rapid = $kobo_api->recursive_change_key($data, array(
																'Household_Information/Household_Number' => 'Household_Number', 
																'Household_Information/Household_Head' => 'Household_Head', 
																'Date_and_Time_of_Disaster_Occurrence' => 'Date_and_Time_of_Occurrence',
																'Household_Information/Does_the_household_have_evacuated' => 'Does_the_household_have_evacuated'
															));

// echo "<pre>";
// print_r($new_data_rapid);

#start insert into DB
$count_inserted = 0;
$count_not_inserted = 0;

$count_inserted_missing = 0;
$count_not_inserted_missing = 0;

$count_inserted_injured = 0;
$count_not_inserted_injured = 0;

$count_inserted_dead = 0;
$count_not_inserted_dead = 0;

foreach ($new_data_rapid as $res):
	$household_number = $res['Household_Number'];
	
	$check_household_number = "SELECT * FROM rapid_needs WHERE Household_Number = '$household_number'";
	$check_household_number_sql = mysqli_query($conn, $check_household_number);

	if (mysqli_num_rows($check_household_number_sql) < 1):
		$insert_into_rapid = "INSERT INTO rapid_needs(`formhub/uuid`, `Disaster_Event`, `Type_of_Disaster`, `Date_and_Time_of_Occurrence`, `Interviewer`, `Household_Number`, `Household_Head`, `Does_the_household_have_evacua`)";
		$insert_into_rapid .= " VALUES ('".$res['formhub/uuid']."', '".$res['Disaster_Event']."', '".$res['Type_of_Disaster']."', '".$res['Date_and_Time_of_Occurrence']."', '".$res['Interviewer']."', '".$res['Household_Number']."', '".$res['Household_Head']."', '".$res['Does_the_household_have_evacuated']."')";

		$insert_into_rapid_sql = mysqli_query($conn, $insert_into_rapid);

		if ($insert_into_rapid_sql) $count_inserted++;
		else $count_not_inserted++;

		$last_rapid_id = mysqli_insert_id($conn);

		if (@$res['Missing_Person']):
        	
        	#echo mysqli_insert_id($conn)."<br />";
        	#echo $res['Household_Number']." - Has Missing Person<br /><br />";
        	foreach ($res['Missing_Person'] as $missing_person):
        		$insert_missing = "INSERT INTO rapid_persons(`Last_Name`, `First_Name`, `Age`, `Gender`, `Part_of_DAP_S`, `Picture`, `Type_of_Person`, `rapid_id`)";
        		$insert_missing .= " VALUES ('".$missing_person['Missing_Person/Last_Name']."', '".$missing_person['Missing_Person/First_Name']."', '".$missing_person['Missing_Person/Age']."', '".$missing_person['Missing_Person/Gender']."', '".$missing_person['Missing_Person/Missing_Part_of_DAP_S']."', '".$missing_person['Missing_Person/Picture']."', 'missing_person', '".$last_rapid_id."')";

        		$insert_missing_sql = mysqli_query($conn, $insert_missing);

        		if ($insert_missing_sql) $count_inserted_missing++;
				else $count_not_inserted_missing++;

        	endforeach;

	    endif;

	    if (@$res['Injured_Person']):
        	
        	#echo mysqli_insert_id($conn)."<br />";
        	#echo $res['Household_Number']." - Has Injured Person";
        	foreach ($res['Injured_Person'] as $injured_person):
        		$insert_injured = "INSERT INTO rapid_persons(`Last_Name`, `First_Name`, `Age`, `Gender`, `Part_of_DAP_S`, `Picture`, `Type_of_Person`, `rapid_id`)";
        		$insert_injured .= " VALUES ('".$injured_person['Injured_Person/Injured_Last_Name']."', '".$injured_person['Injured_Person/Injured_First_Name']."', '".$injured_person['Injured_Person/Injured_Age']."', '".$injured_person['Injured_Person/Injured_Gender']."', '".$injured_person['Injured_Person/Injured_Part_of_DAP_S']."', '".$injured_person['Injured_Person/Injured_Picture']."', 'injured_person', '".$last_rapid_id."')";

        		$insert_injured_sql = mysqli_query($conn, $insert_injured);

        		if ($insert_injured_sql) $count_inserted_injured++;
				else $count_not_inserted_injured++;

        	endforeach;

	    endif;

	    if (@$res['Dead_Person']):
        	
        	#echo mysqli_insert_id($conn)."<br />";
        	#echo $res['Household_Number']." - Has Dead Person<br /><br />";
        	foreach ($res['Dead_Person'] as $dead_person):
        		$insert_dead = "INSERT INTO rapid_persons(`Last_Name`, `First_Name`, `Age`, `Gender`, `Part_of_DAP_S`, `Picture`, `Type_of_Person`, `rapid_id`)";
        		$insert_dead .= " VALUES ('".$dead_person['Dead_Person/Dead_Last_Name']."', '".$dead_person['Dead_Person/Dead_First_Name']."', '".$dead_person['Dead_Person/Dead_Age']."', '".$dead_person['Dead_Person/Dead_Gender']."', '".$dead_person['Dead_Person/Dead_Part_of_DAP_S']."', '".$dead_person['Dead_Person/Dead_Picture']."', 'dead_person', '".$last_rapid_id."')";

        		$insert_dead_sql = mysqli_query($conn, $insert_dead);

        		if ($insert_dead_sql) $count_inserted_dead++;
				else $count_not_inserted_dead++;

        	endforeach;

	    endif;
		
	endif;

endforeach;

echo json_encode(array(
						"rapid_needs_inserted" => $count_inserted,
						"rapid_needs_not_inserted" => $count_not_inserted,
						"rapid_needs_inserted_missing" => $count_inserted_missing,
						"rapid_needs_not_inserted_missing" => $count_not_inserted_missing,
						"rapid_needs_inserted_injured" => $count_inserted_injured,
						"rapid_needs_not_inserted_injured" => $count_not_inserted_injured,
						"rapid_needs_inserted_dead" => $count_inserted_dead,
						"rapid_needs_not_inserted_dead" => $count_not_inserted_dead,
						));

?>