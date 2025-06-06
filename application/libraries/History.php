<?php
class History {
   	function __construct() {}
   	//Table name - log table name
   	//transaction_type - IN, OUT, ADjust
   	//updated_by - user id
   	//history_data - history data
   	//entity_id - order_id, Production_line_id, product_inventory_id etc.
   	public static function addHistory($table_name,$transaction_type,$updated_by='',$history_data, $entity_id){
		$CI =& get_instance();
       	$CI->load->database();
       	if(!empty($table_name) && !empty($transaction_type) && !empty($history_data)){
	        $dataArr = array();
	        $table_history = json_encode($history_data);
	        $dataArr['transaction_type'] = $transaction_type;
	        $dataArr['updated_by'] = $updated_by;
	        $dataArr['updated_date'] = date('Y-m-d H:i:s');
	        $dataArr['entity_id'] = $entity_id;
	        $dataArr['table_history'] = $table_history;
	        $CI->db->insert($table_name, $dataArr);  
	    }
	}
}
?>