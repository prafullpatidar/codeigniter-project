<?php 
class Cron extends CI_Controller{
 function __construct(){
   parent::__construct();
    
    $this->load->model('Company_manager');
    $this->cm = $this->Company_manager;

    $this->load->model('user_manager');
    $this->um = $this->user_manager;

    $this->load->model('email_manager');
    $this->em = $this->email_manager;    

    $this->load->library('querybundel');
    $this->qb = $this->querybundel;


 }

 function rfqSubmit_notify(){
   if(isset($_REQUEST['log']))
    {
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
    }

   $rfqs = $this->cm->getAllshipStockOrder(' AND so.status = 1 and date(so.notify_date) < "'.date('Y-m-d').'"','R',10,0);
   if(!empty($rfqs)){
       $whereEm = ' AND em.template_code = "rfq_submission_pending"';
       $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
       $subject = $emailTemplateData->email_subject;
     foreach ($rfqs as $row) {
       $last_notify_date = $row->notify_date;
       $nextDayDateTime = new DateTime($last_notify_date);
       $nextDayDateTime->modify('+1 day');
       $nextDayFormatted = $nextDayDateTime->format('Y-m-d H:i:s');
       if(date('Y-m-d H:i:s') >= $nextDayFormatted){
           $shipArr = (array) $this->cm->getNewShipById(' AND s.ship_id = '.$row->ship_id);

            if(!empty($shipArr['capt_user_name']) && !empty($shipArr['capt_email'])){

             $body = str_replace(array('##username##','##rfq_no##'),array($shipArr['capt_user_name'],$row->rfq_no) ,$emailTemplateData->email_body); 
             $this->um->sendMail($shipArr['capt_email'],$subject,$body);
             $this->db->update('ship_order',array('notify_date'=>date('Y-m-d H:i:s')),array('ship_order_id'=>$row->ship_order_id));
               $dataArr['date'] = date('Y-m-d H:i:s');
               $dataArr['title'] = $subject;
               $dataArr['long_desc'] = 'Dear '.$shipArr['capt_user_name'].'<br> RFQ ('.$row->rfq_no.') submission is pending, kindly submit to head office admin.';
               $dataArr['is_for_master'] = 1;
               $dataArr['ship_id'] = $row->ship_id;
               $this->um->add_notify($dataArr); 
 
           }
        }
     }
   }

   
  echo 'Cron Run Success';
 }

 
 function send_email(){
    if(isset($_REQUEST['log']))
    {
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
    }

   $emails = $this->em->getAllEmailLogs(' and el.is_send = 0',10,0);
    if(!empty($emails)){
      $dlattach = array();
      foreach ($emails as $row) {
        $subject = $row->subject;
        $body = $row->body;
        $email = $row->email;
        $filePath = '';
        $attachment = array();
        if(!empty($row->attechment)){ 
         $files = explode(',',$row->attechment);
          for ($i=0; $i < count($files); $i++) { 
            $filePath = FCPATH . "uploads/work_order_pdfs/".$files[$i].".pdf";
            $attachment[] = $filePath;
            $dlattach[] = $filePath;
          }
        }
        
        $this->um->sendMail($email,$subject,$body,$attachment);
        $this->db->update('email_logs',array('is_send'=>1,'send_time'=>date('Y-m-d H:i:s')),array('email_log_id'=>$row->email_log_id)); 
      }

      if($dlattach){
        for ($k=0; $k < count($dlattach); $k++) {
            unlink($dlattach[$k]);
        }
      }
      
   }
   echo 'Cron Run Success';
  }

  function delete_email_logs(){
    if(isset($_REQUEST['log'])){
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
    }

    $emails = $this->em->getAllEmailLogs(' and el.is_send = 1');
    if(!empty($emails)){
      foreach ($emails as $row) {
         $date = new DateTime($row->send_time);
         $date->modify('+30 day');
         $newDate = $date->format('Y-m-d');
         if(date('Y-m-d') >= $newDate){
          $this->db->delete('email_logs',array('email_log_id'=>$row->email_log_id));
         }
       }
    }
   echo 'Cron Run Success'; 
 } 

 function delete_notification(){
    if(isset($_REQUEST['log'])){
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
    }
    $all_notify = $this->um->getNotificationList('','R');
    if(!empty($all_notify)){
      foreach ($all_notify as $row) {
         $date = new DateTime($row->date);
         $date->modify('+30 day');
         $newDate = $date->format('Y-m-d');
         if(date('Y-m-d') >= $newDate){
           $this->db->delete('notification',array('notification_id'=>$row->notification_id));           
         }
      }
    }

   echo 'Cron Run Success'; 
 }

 function monthEndNotify(){
  if(isset($_REQUEST['log'])){
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
   }
  $summary_report = $this->cm->getAllvSummaryReport(' and vs.status = 0','R');
  if(!empty($summary_report)){
    foreach ($summary_report as $row){
    $start_date = "01-".$row->month."-".$row->year;
    $start_time = strtotime($start_date);
    $end_time = strtotime("+28 day", $start_time);
    if(date('Y-m-d')>=date('Y-m-d',$end_time)){
       $monthNum  = $row->month;
       $dateObj   = DateTime::createFromFormat('!m', $monthNum);
       $monthName = $dateObj->format('F'); // March
        $dataArr['date'] = date('Y-m-d H:i:s');
        $dataArr['title'] = 'Victualling report not submitted';
        $dataArr['long_desc'] = 'Victualing report is pending of '.$monthName.' '.$row->year.'.';
        $dataArr['is_for_master'] = 1;
        $dataArr['ship_id'] = $row->ship_id;
        $this->um->add_notify($dataArr); 
    }
   }
  }
  
  $condemnedStockReportData = $this->cm->getAllCondemnedStockReportData('AND cr.`status`= 1','R');

  if(!empty($condemnedStockReportData)){
   foreach ($condemnedStockReportData as $row) {
      $start_date = "01-".$row->month."-".$row->year;
      $start_time = strtotime($start_date);
      $end_time = strtotime("+28 day", $start_time);
      if(date('Y-m-d')>=date('Y-m-d',$end_time)){
       $monthNum  = $row->month;
       $dateObj   = DateTime::createFromFormat('!m', $monthNum);
       $monthName = $dateObj->format('F'); // March
        $dataArr['date'] = date('Y-m-d H:i:s');
        $dataArr['title'] = 'Condemned stock not Submitted';
        $dataArr['long_desc'] = ' Condenmed report is pending of '.$monthName.' '.$row->year.'.';
        $dataArr['is_for_master'] = 1;
        $dataArr['ship_id'] = $row->ship_id;
        $this->um->add_notify($dataArr);  
      } 
    } 
  } 
  echo 'Cron Run Success'; 
 }


 function opening_stock_value_by_month(){
   if(isset($_REQUEST['log'])){
      $this->output->enable_profiler(TRUE);
      echo date('Y-m-d H:i:s').'<br>';
   }

 }
  
}
?>