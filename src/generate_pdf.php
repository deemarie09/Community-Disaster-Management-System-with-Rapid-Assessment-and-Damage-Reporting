<?php
//require('WriteHTML.php');
require('html_table.php');

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
$finalArr = $kobo_api->grouped_types($data_array, 'Disaster_Event');

$report_arr = $finalArr[$_GET['disaster_name']];


$disaster_split = explode('-',trim($_GET['disaster']));

$persons_affected = 0;

foreach ($report_arr as $key => $result):
    if (@count($result['Missing_Person'])) $persons_affected += @count($result['Missing_Person']);
    if (@count($result['Injured_Person'])) $persons_affected += @count($result['Injured_Person']);
    if (@count($result['Dead_Person'])) $persons_affected += @count($result['Dead_Person']);   
endforeach;

$evacuated_families = 0;

foreach ($report_arr as $key => $result):
    if (strtolower(@$result['Does_the_household_have_evacuated']) == "yes"):
        if (@count($result['Does_the_household_have_evacuated'])) $evacuated_families += @count($result['Does_the_household_have_evacuated']);
    endif;
endforeach;

$household_affected = 0;

foreach ($report_arr as $result):
    //echo @count($result['Household_Number']);
    if (@count($result['Household_Head'])) $household_affected += @count($result['Household_Head']);
endforeach;

$family_affected = 0;

foreach ($report_arr as $result):
    //echo @count($result['Household_Number']);
    if (@count($result['Household_Head'])) $family_affected += @count($result['Household_Head']);
endforeach;

$missing_person = 0;

foreach ($report_arr as $key => $result):
    if (@count($result['Missing_Person'])) $missing_person += @count($result['Missing_Person']); 
endforeach; 

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

$injured_persons = 0;

foreach ($report_arr as $key => $result):
    if (@count($result['Injured_Person'])) $injured_persons += @count($result['Injured_Person']);  
endforeach;

$injured_children = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Injured_Person']):
        
        foreach ($result['Injured_Person'] as $k => $res):
            if ($res['Injured_Person/Injured_Age'] <= 18):
                $injured_children++;
            endif;
        endforeach;

    endif;
endforeach;

$injured_adults = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Injured_Person']):
        
        foreach ($result['Injured_Person'] as $k => $res):
            if ($res['Injured_Person/Injured_Age'] <= 60 || $res['Injured_Person/Injured_Age'] > 18):
                $injured_adults++;
            endif;
        endforeach;

    endif;
endforeach;

$injured_dap = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Injured_Person']):
        
        foreach ($result['Injured_Person'] as $k => $res):
            if (@$res['Injured_Person/Injured_Part_of_DAP_S']) :
                if (strtolower($res['Injured_Person/Injured_Part_of_DAP_S']) == "yes"):
                    $injured_dap++;
                endif;
            endif;
        endforeach;

    endif;
endforeach;

$injured_senior = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Injured_Person']):
        
        foreach ($result['Injured_Person'] as $k => $res):
            if ($res['Injured_Person/Injured_Age'] > 60):
                $injured_senior++;
            endif;
        endforeach;

    endif;
endforeach;

$dead_persons = 0;

foreach ($report_arr as $key => $result):
    if (@count($result['Dead_Person'])) $dead_persons += @count($result['Dead_Person']);   
endforeach;

$dead_children = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Dead_Person']):
        
        foreach ($result['Dead_Person'] as $k => $res):
            if ($res['Dead_Person/Dead_Age'] <= 18):
                $dead_children++;
            endif;
        endforeach;

    endif;
endforeach;

$dead_adults = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Dead_Person']):
        
        foreach ($result['Dead_Person'] as $k => $res):
            if ($res['Dead_Person/Dead_Age'] <= 60 || $res['Dead_Person/Dead_Age'] > 18):
                $dead_adults++;
            endif;
        endforeach;

    endif;
endforeach;

$dead_dap = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Dead_Person']):
        
        foreach ($result['Dead_Person'] as $k => $res):
            if (@$res['Dead_Person/Dead_Part_of_DAP_S']) :
                if (strtolower($res['Dead_Person/Dead_Part_of_DAP_S']) == "yes"):
                    $dead_dap++;
                endif;
            endif;
        endforeach;

    endif;
endforeach;

$dead_senior = 0;

foreach ($report_arr as $key => $result):
    if (@$result['Dead_Person']):
        
        foreach ($result['Dead_Person'] as $k => $res):
            if ($res['Dead_Person/Dead_Age'] > 60):
                $dead_senior++;
            endif;
        endforeach;

    endif;
endforeach;

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

$total_house = $partially_house + $totally_house;
$total_bridge = $partially_bridge + $totally_bridge;
$total_roads = $partially_roads + $totally_roads;
$total_school = $partially_school + $totally_school;
$total_gym = $partially_gym + $totally_gym;
$total_bh = $partially_bh + $totally_bh;
$total_elines = $partially_elines + $totally_elines;

$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',15);
$html='<table border="0">
			<tr>
				<td width="200" height="30"><b>RAPID DAMAGE ASSESSMENT AND NEEDS ANALYSIS (Barangay Report)</b></td>
			</tr>
			<tr>
				<hr><br>
			<tr>
		</table>';
$pdf->WriteHTML($html);

$pdf->SetFont('Arial','B',11);
$calamity='<br><table border="0">
					<tr>
						<td width="200" height="30"><b>Proof of Calamity</b></td>
					</tr>
					<br>
					<tr>
						<td>Type of Disaster:</td>
						<td width="175">'.ucwords(str_replace("_", " ", $disaster_split[1])).'</td>
						<td>Affected Barangay:</td>
						<td>Brgy. Bonbon, Lambunao, Iloilo</td>
					</tr>
					<br>
					<tr>
						
						<td>Disaster Event:</td>
						<td>'.ucwords(str_replace("_", " ", $disaster_split[0])).'</td>
					</tr>
					<tr>
						<br><hr><br>
					<tr>
				</table>';
$pdf->WriteHTML($calamity);

$pdf->SetFont('Arial','B',11);
$population='<br><table border="0">
					<tr>
						<td width="200" height="30"><b>Population</b></td>
					</tr>
					<br>
					<tr>
						<td width="210">Number of Affected Persons:</td>
						<td width="100">'.$persons_affected.'</td>
						<td width="300">Number of Families in Evacuation Center:</td>
						<td>'.$evacuated_families.'</td>
					</tr>
					<br>
					<tr>
						<td width="230">Number of Affected Households:</td>
						<td width="100">'.$household_affected.'</td>
					</tr>
					<br>
					<tr>
						<td width="150">Number of Families:</td>
						<td width="100">'.$family_affected.'</td>
					</tr>
				</table>
				<br>
				<table border="0">
					<br>
					<tr>
						<td width="275">Missing Person:</td>
						<td width="275">Injured Person:</td>
						<td width="275">Dead Person:</td>
					</tr>
					<br>
					<tr>
						<td width="140">Children: '.$children.'</td>
						<td width="140">Adults: '.$adults.'</td>
						<td width="140">Children: '.$injured_children.'</td>
						<td width="140">Adults: '.$injured_adults.'</td>
						<td width="100">Children: '.$dead_children.'</td>
						<td width="140">Adults: '.$dead_adults.'</td>
					</tr>
					<br>
					<tr>
						<td width="140">DAPs: '.$dap_missing.'</td>
						<td width="140">Senior Citizen: '.$senior.'</td>
						<td width="140">DAPs: '.$injured_dap.'</td>
						<td width="140">Senior Citizen: '.$injured_senior.'</td>
						<td width="100">DAPs: '.$dead_dap.'</td>
						<td width="120">Senior Citizen: '.$dead_senior.'</td>
					</tr>
					<tr>
						<br><hr><br>
					<tr>
				</table>
';
$pdf->WriteHTML($population);

$pdf->SetFont('Arial','B',11);
$damage='<br><table border="0">
					<tr>
						<td width="200" height="30"><b>Damage Report</b></td>
					</tr>
					<br>
					<tr>
						<td width="70">---</td>
						<td width="100">Houses</td>
						<td width="100">Bridges</td>
						<td width="100">Roads</td>
						<td width="100">School</td>
						<td width="100">Gym</td>
						<td width="100">Brgy. Hall</td>
						<td width="100">Elec. Lines</td>
					</tr>
					<br>
					<tr>
						<td width="70">Partially</td>
						<td width="100">'.$partially_house.'</td>
						<td width="100">'.$partially_bridge.'</td>
						<td width="100">'.$partially_roads.'</td>
						<td width="100">'.$partially_school.'</td>
						<td width="100">'.$partially_gym.'</td>
						<td width="100">'.$partially_bh.'</td>
						<td width="100">'.$partially_elines.'</td>
					</tr>
					<tr>
						<td width="70">Totally</td>
						<td width="100">'.$totally_house.'</td>
						<td width="100">'.$totally_bridge.'</td>
						<td width="100">'.$totally_roads.'</td>
						<td width="100">'.$totally_school.'</td>
						<td width="100">'.$totally_gym.'</td>
						<td width="100">'.$totally_bh.'</td>
						<td width="100">'.$totally_elines.'</td>
					</tr>
					<tr>
						<td width="70">Total</td>
						<td width="100">'.$total_house.'</td>
						<td width="100">'.$total_bridge.'</td>
						<td width="100">'.$total_roads.'</td>
						<td width="100">'.$total_school.'</td>
						<td width="100">'.$total_gym.'</td>
						<td width="100">'.$total_bh.'</td>
						<td width="100">'.$total_elines.'</td>
					</tr>
				</table>';
$pdf->WriteHTML($damage);

// $pdf->WriteHTML('
// 				<p align="center"><b>RAPID DAMAGE ASSESSMENT AND NEEDS ANALYSIS (Barangay Report)</b></p><br>
// 				<p align="left"><b>Proof of Calamity</b></p><br><br>
// 				<p align="left"><b>Type of Disaster: </b></p><i>'.ucwords(str_replace("_", " ", $disaster_split[1])).'</i>
// 				<p align="right"><b>Affected Barangay: </b></p><i>Brgy. Bonbon, Lambunao, Iloilo</i><br><br>
// 				<p align="right"><b>Name of Disaster: </b></p><i>'.$_GET['disaster_name'].'</i>
// 				<p align="right"><b>Disaster Event: </b></p><i>'.ucwords(str_replace("_", " ", $disaster_split[0])).'</i><br><br><hr>
// 				');

// $pdf->WriteHTML('
// 				<br><br><p align="left"><b>Population</b></p><br><br>
// 				<p align="left"><b>Number of Affected Persons: </b></p><i>'.$persons_affected.'</i>
// 				<p align="right"><b>Number of Families in Evacuation Center: </b></p><i>'.$evacuated_families.'</i><br><br>
// 				<p align="right"><b>Number of Affected Households: </b></p><i>'.$household_affected.'</i><br><br>
// 				<p align="right"><b>Number of Families: </b></p><i>'.$household_affected.'</i><br><br><br>

// 				<p align="right"><b>Number of Missing Person: </b></p><i>'.$missing_person.'</i><br>
// 				<p align="right"><b>Children: </b></p><i>'.$children.'</i>
// 				<p align="right"><b>Adults: </b></p><i>'.$adults.'</i><br>
// 				<p align="right"><b>DAPs: </b></p><i>'.$dap_missing.'</i>
// 				<p align="right"><b>Senior Citizen: </b></p><i>'.$senior.'</i><br><br><br>

// 				<p align="right"><b>Number of Injured Person: </b></p><i>'.$injured_persons.'</i><br>
// 				<p align="right"><b>Children: </b></p><i>'.$injured_children.'</i>
// 				<p align="right"><b>Adults: </b></p><i>'.$injured_adults.'</i><br>
// 				<p align="right"><b>DAPs: </b></p><i>'.$injured_dap_missing.'</i>
// 				<p align="right"><b>Senior Citizen: </b></p><i>'.$injured_senior.'</i><br><br><br>

// 				<p align="right"><b>Number of Dead Person: </b></p><i>'.$dead_persons.'</i><br>
// 				<p align="right"><b>Children: </b></p><i>'.$dead_children.'</i>
// 				<p align="right"><b>Adults: </b></p><i>'.$dead_adults.'</i><br>
// 				<p align="right"><b>DAPs: </b></p><i>'.$dead_dap_missing.'</i>
// 				<p align="right"><b>Senior Citizen: </b></p><i>'.$dead_senior.'</i><br><br><hr>
// 				');

// $pdf->WriteHTML('
// 				<br><br><p align="left"><b>Damage Report</b></p><br><br>
// 				<br><p align="left"><b>(Houses)</b></p><br>
// 				<p align="left">Partially: </p><i>'.$partially_house.'</i><br>
// 				<p align="left">Totally: </p><i>'.$totally_house.'</i><br>
				
// 				<br><p align="left"><b>(Bridges)</b></p><br>
// 				<p align="left">Partially: </p><i>'.$partially_bridge.'</i><br>
// 				<p align="left">Totally: </p><i>'.$totally_bridge.'</i>
// 				');

$pdf->Output();
?>