<?php

/***

{
		"id":1007947,
		"id_string":"as8vLnTMHtfcFjNfSktVLH",
		"title":"Damage Assessment Form",
		"description":"Damage Assessment",
		"url":"https://kc.kobotoolbox.org/api/v1/data/1007947.json"
	},
	{
		"id":948783,
		"id_string":"ay8JWi7MuXMXsqtKoqPrEL",
		"title":"Master List of Household - Lambunao",
		"description":"Master List of Household - San Joaquin",
		"url":"https://kc.kobotoolbox.org/api/v1/data/948783.json"
	},
	{
		"id":1044349,
		"id_string":"aZ9YxMmETWsrKUE5H7Dhi7",
		"title":"Rapid Needs Assessment",
		"description":"Rapid Needs Assessment",
		"url":"https://kc.kobotoolbox.org/api/v1/data/1044349.json"
	}

***/

define("DAMAGE_ASSESSMENT_API", "https://kc.kobotoolbox.org/api/v1/data/1007947"); #API for Damage Assessment Form
define("MASTER_LIST_API", "https://kc.kobotoolbox.org/api/v1/data/948783"); #API for Master List of Household
define("RAPID_NEEDS_ASSESSMENT_API", "https://kc.kobotoolbox.org/api/v1/data/1044349"); #API for Rapid Needs Assessment Form

class KOBO_API {

	public function test() {
		return "foo";
	}

	public function cleanDateTime($data) {

		$dateTime = new DateTime($data);
		$cleanDate = $dateTime->format('M j, Y h:i:A'); // 15th Apr 2010

		return $cleanDate;	

	}

	public function RemoveSpecialChar($str) {

		// Using str_replace() function
	    // to replace the word
		$res = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), ' ', $str);

		// Returning the result
		return strtolower($res);

	}

	public function sortRawData($array, $args = null) {

		$sortArray = [];
		$sortArray2 = [];
		foreach ($array as $key => $res):

		    $tempArray = [];

		    $type_of_disaster = strtolower($res['Disaster_Event']). "-" .strtolower($res['Type_of_Disaster']);
		    #$type_of_disaster = $kobo_api->RemoveSpecialChar($res['Type_of_Disaster']);

		    if (!in_array($type_of_disaster, $sortArray2)):
		        array_push($sortArray2, $type_of_disaster);
		        array_push($tempArray, $res);
		        $sortArray[$type_of_disaster] = $tempArray;
		    else:
		        array_push($sortArray[$type_of_disaster], $res);
		    endif;

		endforeach;

		return $sortArray;
	}

	public function grouped_types($array, $args = null) {

		$grouped_types = array();
		$i = 0;
		foreach($array as $type){
		  $grouped_types[@$type[$args]][] = $type;
		  ++$i;
		}

		return $grouped_types;
	}

	public function getDisasterNameFromSortedArray($args, $offset) {

		$firstKey = array_keys($args)[$offset];
        #var_dump($firstKey);

        return $firstKey;

	}

	public function getDataByAPI($api) {
		$headers = [
		    "Example",
		    "Authorization: Token 2a8bf685a9cc56a485723accc660d047e22305d2"
		];

		$ch = curl_init($api);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		curl_close($ch);

		$data = json_decode($response, true);

		return $data;
	}

	public function getImageFromKobo($url) {

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Token 2a8bf685a9cc56a485723accc660d047e22305d2",
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	public function getDataByEventType($event_type, $array) {

		return $array[$event_type];

	}

	public function recursive_change_key($arr, $set) {
        if (is_array($arr) && is_array($set)) {
    		$newArr = array();
    		foreach ($arr as $k => $v) {

    		    $key = array_key_exists( $k, $set) ? $set[$k] : $k;
    			$newArr[$key] = is_array($v) ? $this->recursive_change_key($v, $set) : $v;

    		}
    		return $newArr;
    	}
    	return $arr;    
    }

}