<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


	// get database connection
	include_once 'database.php';
	 
	// instantiate model_api object
	include_once 'Model_api.php';

	$database = new Database();
	$db = $database->getConnection();
	
	$model_api = new Model_api($db);
// make sure data is not empty
	if($_POST["token"] != 3){
		//http_response_code(400);
		echo json_encode(
		array(
			"message" => "Wrong token",
			"status" => 400
		));
		exit;
	}
	if($_POST["token"] == ""){
		//http_response_code(400);
		echo json_encode(
		array(
			"message" => "Missing token",
			"satus" => 400
		));
		exit;
	}
	if($_POST["start_date"] == ""){
		echo json_encode(
		array(
			"message" => "Missing start date",
			"status" => 400
		));
		exit;
	}
	if($_POST["end_date"] == ""){
		echo json_encode(
		array(
			"message" => "Missing end date",
			"status" => 400
		));
		exit;
	}
	
	// set model_api property values
	$model_api->token = $_POST["token"];
	$model_api->start_date = $_POST["start_date"];
	//handle end_date
	$check_final = explode(" ", $_POST["end_date"]);
	$end_date = $_POST["end_date"];
	if($check_final[1] == "00:00:00" || $check_final[1] == ""){
			 $end_date = $check_final[0]." "."23:59:00";
		}
	$model_api->end_date = $end_date;
	//----------------
	$model_api->type = $_POST["type"];
	$model_api->session = $_POST["session"];
	$model_api->phone_number = $_POST["phone_number"];
	//check limit 30 days
	$start = strtotime(date('Y-m-d', strtotime($_POST["start_date"]. ' + 30 days')));
	$end = strtotime($end_date);

	//start must less than end time 
	if(strtotime($model_api->start_date) > $end){
		echo json_encode(
			array(
			"message" => "You should select start time less than end time to get the report",
			"status" => 400 ));
			exit;
	}
	if( $end  > $start ){
		echo json_encode(
		array(
		"message" => "You should select time less then 30 days to get the report",
		"status" => 400)
		);
		exit;
	}
	$stmt = $model_api->get_report_api();
	$array_result = array();
	while ($result = mysqli_fetch_assoc($stmt)) {
		//format url link for recording
		$url_recording= "";
		if($result["recording"] != NULL){
				$url_recording = $result["oname"]."/".$result["recording"];
		}
		$temp_array = array(
			"date_time" => $result["date_time"],
			"queue" => $result["queue_id"],
			"agent" => $result["agent_id"],
			"phone" => $result["phone_number"],
			"status" => $result["ename"],
			"wait" => date("H:i:s",$result["wait_time"]),
			"hold" => "00:00:00",
			"talk" => date("H:i:s",$result["talk_time"]),
			"action" => $url_recording,
			"session" => $result["session"]
		);
		array_push($array_result, $temp_array);
	}
	if(count($array_result) > 0){
	
	   echo json_encode(
		   array(
		   "message" => "Sucess",
		   "status" => 200,
		   "data"=> $array_result)
		   );
	   exit;
	} 
	else{
		echo json_encode(
		array(
			"message" => "Error",
			"status" => 400)
		);
		exit;
	}
	
	
		
	
	
?>