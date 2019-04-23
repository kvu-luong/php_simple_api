<?php
date_default_timezone_set('UTC');//to use date()
class Model_api {
	
	// constructor with $db as database connection
	private $conn;
	public $token;
	public $type;
	public $start_date;
	public $end_date;
	public $session;
	public $phone_number;
	
    public function __construct($db){
        $this->conn = $db;
    }
	public function get_report_api(){
	
		$query = "SELECT  `c`.`id` ,  `c`.`date_time` ,  `c`.`phone_number` ,  `c`.`wait_time` , `c`.`session`,
					`c`.`recording` ,  `c`.`user_id` ,  `c`.`talk_time` ,  `a`.`agent_id` , `a`.`name` AS `aname`, 
					`q`.`queue_id` ,  `q`.`name` AS  `qname` ,  `e`.`name` AS  `ename` , `o`.`name` AS `oname`
					FROM  `call`  `c` 
					LEFT JOIN  `agent`  `a` ON  `c`.`user_id` =  `a`.`id` 
					LEFT JOIN  `queue_config`  `q` ON  `c`.`queue_id` =  `q`.`queue_id` 
					LEFT JOIN  `events`  `e` ON  `c`.`event_id` =  `e`.`id` 
					LEFT JOIN `organization` `o` ON `a`.`org_id` = `o`.`id`
					WHERE `c`.`date_time` >= '".$this->start_date."' AND `c`.`date_time` <='".$this->end_date."'
					AND `c`.`event_id` IN (1,6,7,14,15)";
		if($this->type != "" or $this->type != NULL){
			$query .= " AND `c`.`type` =".$this->type;
		}
		if($this->phone_number != "" or $this->phone_number != NULL){
			$query .= " AND `c`.`phone_number` =".$this->phone_number;
		}
		if($this->session != "" or $this->session != NULL){
			$query .= " AND  `c`.`session` =".$this->session;
		}
		$result = mysqli_query($this->conn, $query);
		return $result;
	}
}
?>