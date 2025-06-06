<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function set_session($userinfo){
   $CI = & get_instance();
   $CI->load->model('user_manager');
   $CI->um = $CI->user_manager;
   if($userinfo){ 
       $CI->session->set_userdata('user_info',$userinfo);
       $user_task = $CI->um->getUserRoleTask($userinfo->user_id);
   }
  setUserTask($user_task); 
  return true;
}

 function setUserTask($data){
    $task_data = array();
    foreach($data as $d)
    {
        $task_data[] = $d->code;
    }
    $ci = & get_instance();
    $ci->session->set_userdata('user_task',serialize($task_data)); 
    return true;
  }

  function getUserTask(){
    $CI = & get_instance();
    $session_data = $CI->session->userdata('user_task');
    return unserialize($session_data);
  }

  function checkLabelByTask($task){
    $sessionData = getSessionData();
    $user_task = getUserTask();
    $menu_show = false;
    if($sessionData->code=='super_admin'){
      $menu_show = true;
    }
    elseif(in_array($task,$user_task)){
        $menu_show = true;
    }
    return $menu_show;
  }

  function checkUserSession(){
     $CI = & get_instance();
       if(empty($CI->session->userdata('user_info'))){
           redirect(base_url());
           die;
       }
    }

 function getSessionData(){
  $CI = & get_instance();
  $session_data = $CI->session->userdata('user_info');
  return $session_data;
 }  
 
 function user_logout(){
  $CI = & get_instance();
  deleteCustomSession('ship_details');
  $CI->session->sess_destroy();
  redirect(base_url(),'refresh');
  die;
  }
  
 function doc_upload($file_name, $type=''){ 
      $CI = & get_instance();
        $file_name = str_replace(' ', '', $file_name);
        if($type != ''){
          $config['upload_path']          =  FCPATH.'uploads/'.$type.'/';
        }else{
          $config['upload_path']          =  FCPATH.'uploads/';
        }
        assert(file_exists($config['upload_path']) === TRUE);
        $config['allowed_types']        = 'gif|jpg|png|jpeg|xlsx|xls|csv|pdf';
        $config['max_size']             = 100000;
        $config['file_name']            = $file_name;
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if($CI->upload->do_upload('img')){
          $data = $CI->upload->data();
          return $data;                  
        }else{
          $error = array('error' => $CI->upload->display_errors());
          return $error;

        }
  }

  function pic_upload($file_name, $type=''){ 
      $CI = & get_instance();
        $file_name = str_replace(' ', '', $file_name);
        if($type != ''){
          $config['upload_path']          =  FCPATH.'uploads/'.$type.'/';
        }else{
          $config['upload_path']          =  FCPATH.'uploads/';
        }
        assert(file_exists($config['upload_path']) === TRUE);
        $config['allowed_types']        = 'jpg|png|jpeg';
        $config['max_size']             = 1000;
        $config['file_name']            = $file_name;
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if($CI->upload->do_upload('img')){
          $data = $CI->upload->data();
          return $data;                  
        }else{
          $error = array('error' => $CI->upload->display_errors());
          return $error;

        }
  }
 

function setCustomSession($session_name,$value) {
    $ci = & get_instance();
    $custom_cookie= array('name'=>$session_name,'value'  => serialize($value),'expire' => '28800');
    $ci->input->set_cookie($custom_cookie);
 }

 function getCustomSession($session_name) {
    $ci = & get_instance();
    $custom_cookie = $ci->input->cookie($session_name,true);
    return unserialize($custom_cookie);
 }

 function deleteCustomSession($session_name){
    $ci = & get_instance();
    $user_cookie= array('name' =>$session_name,'value'  =>'','expire' => time() - 3600);
    $ci->input->set_cookie($user_cookie);
 }


 function setImportSession($session_name,$value){
  $CI = & get_instance();
   if($value){    
   $CI->session->set_userdata($session_name,$value);
   return true;
   }else{
       return false;
   }
 }


 function getImportData($session_name){
  $CI = & get_instance();
  $session_data = $CI->session->userdata($session_name);
  return $session_data;
 }

 function base64_to_jpeg($base64_string,$output_file) {
    $ifp = fopen($output_file,'wb'); 
    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[1]) );
    fclose( $ifp ); 
    return $output_file; 
  }   
  
 function numberTowords(float $amount,$currency='')
{
 if($currency == 'USD'){
    $curr1 = 'Dollars';
    $curr2 = 'cents';
 }else if($currency == 'EURO'){
    $curr1 = 'Euro';
    $curr2 = 'cents';
 }
 else if($currency == 'SGD'){
    $curr1 = 'Dollars';
    $curr2 = 'cents';
 }
 // else{
 //    $curr1 = 'Rupees';
 //    $curr2 = 'paise';
 // }
$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
  $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
  while( $x < $count_length ) {
       $get_divider = ($x == 2) ? 10 : 100;
       $amount = floor($num % $get_divider);
       $num = floor($num / $get_divider);
       $x += $get_divider == 10 ? 1 : 2;
       if ($amount) {
         $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
         $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
         $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
         '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
         '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
         }else $string[] = null;
       }
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? " And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) .' '. $curr2 : '';
   return ($implode_to_Rupees ? $implode_to_Rupees .' '. $curr1 : '') . $get_paise;
}

function pdf_upload($file_name, $type=''){ 
      $CI = & get_instance();
        $file_name = str_replace(' ', '', $file_name);
        if($type != ''){
          $config['upload_path']          =  FCPATH.'uploads/'.$type.'/';
        }else{
          $config['upload_path']          =  FCPATH.'uploads/';
        }
        assert(file_exists($config['upload_path']) === TRUE);
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 1000;
        $config['file_name']            = $file_name;
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if($CI->upload->do_upload('vendor_pdf')){
          $data = $CI->upload->data();
          return $data;                  
        }else{
          $error = array('error' => $CI->upload->display_errors());
          return $error;

        }
  }

function checkUserExistByPassportId($passport_id){
   $CI = & get_instance();
   $CI->db->trans_start();
   $query = $CI->db->query($CI->qb->getUserByPassportId,array($passport_id));
   if($query->num_rows() > 0){
    $result = $query->row();   
   }
   $CI->db->trans_complete();
   return $result;  
  }
function checkCrewMemberExistByPassportId($passport_id,$ship_id,$date){
    $CI = & get_instance();
    $CI->db->trans_start();
    
    $date = explode('-',$date);
    $month = $date[1];
    $year = $date[0];
    
    $query = $CI->db->query($CI->qb->getCrewByPassportId,array($passport_id,$ship_id,$month,$year));
    
    if($query->num_rows() > 0){
     $result = $query->row();   
    }

    $CI->db->trans_complete();
    return $result;  
}

function checkCrewFoodHabitExistByCrewMemberId($crew_member_id){
    $CI = & get_instance();
    $CI->db->trans_start();
    $query = $CI->db->query($CI->qb->getFoodHabitsByCrewMemberId,array($crew_member_id));
    if($query->num_rows() > 0){
    $result = $query->row();   
    }
    $CI->db->trans_complete();
    return $result;  
}

function checkDuplicateCrewEntry($ship_id,$date){
    $CI = & get_instance();
    $CI->db->trans_start();
    $date = explode('-',$date);
    $month = $date[1];
    $year = $date[0];
    $query = $CI->db->query($CI->qb->getDuplicateCrewEntry,array($ship_id,$month,$year));
    if($query->num_rows() > 0){
    $result = $query->row();   
    }
    $CI->db->trans_complete();
    return $result;  
}

 function getNotificationCount($user_id = ''){
    $CI = & get_instance();
    $CI->db->trans_start();
    $CI->load->model('user_manager');
    $CI->um = $CI->user_manager;
    $user_session_data = getSessionData();
   if(!empty($user_session_data->ship_id) && $user_session_data->code=='captain'){
          $where .= ' AND n.is_read = 0 and ( n.is_for_master = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;
    }
    elseif(!empty($user_session_data->ship_id) && $user_session_data->code=='cook'){
          $where .= ' AND n.is_read = 0 and (n.is_for_cook = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;  
    }
    else{
      $where = ' AND n.is_read = 0 AND n.user_id = '.$user_session_data->user_id.' OR n.is_for_all = 1';  
    }

    $query = str_replace('##WHERE##',$where,$CI->qb->queryCountNotification);
    $query = $CI->db->query($query);
    if($query->num_rows() > 0){
     $result = $query->row(); 
     $result = $result->count;  
    }
    $CI->db->trans_complete();
    return $result;   
 }


 function getSerialNum($ship_type='',$entity_type='',$entity_id=''){
   $CI = & get_instance();
    $CI->db->trans_start();
    $where = '';
    
    if($ship_type){
      $where .= ' and sn.ship_type = '.$ship_type; 
    }

    if($entity_type){
      $where .= ' and sn.entity_type = "'.$entity_type.'"'; 
    }

    if($entity_id){
      $where .= ' and sn.entity_id = "'.$entity_id.'"'; 
    }

    $query = str_replace('##WHERE##',$where,$CI->qb->queryGetSerialNo);
    $query = $CI->db->query($query);
    if($query->num_rows() > 0){
     $result = $query->row(); 
     $result = $result->serial_number;  
    }
    $CI->db->trans_complete();
    return $result;      
 }  

 function updateSerialNum($ship_type='',$entity_type='',$sn='',$entity_id=''){
    $CI = & get_instance();
    $CI->db->trans_start();
    $CI->db->set(array('serial_number'=>$sn));
    $CI->db->where(array('ship_type'=>$ship_type,'entity_type'=>$entity_type,'entity_id'=>$entity_id));
    $CI->db->update('serial_number');
    $CI->db->trans_complete(); 
 }


 function add_email_log($user_id='',$subject='',$body='',$attachment=''){
    $CI = & get_instance();
    $dataArr['user_id'] = $user_id;
    $dataArr['subject'] = $subject;
    $dataArr['body'] = $body;
    $dataArr['attachment'] = $attachment;
    $CI->db->trans_start();
    $CI->db->insert('email_logs',$dataArr);
    $CI->db->trans_complete(); 
 }


 function getTotalValue($val=0,$val1=0){
  $cleanValue1 = (float) str_replace(',', '', $val);
  $cleanValue2 = (float) str_replace(',', '', $val1); 
  // Perform calculation
  $result = $cleanValue1 * $cleanValue2;
  // Format the result
  $formattedResult = number_format($result, 2);
  return $formattedResult; 
 }


 function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>