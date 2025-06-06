<?php
 class User_manager extends CI_Model 
 {
  function login($user_name,$password,$codes){
   $this->db->trans_start();
    $codes = explode(",", $codes);
    $quotedItems = array_map(function($item) {
        return "'" . trim($item) . "'";
    }, $codes);

    $output = implode(",", $quotedItems);

   $where = ' AND r.code IN ('.$output.')';
   $query = $this->db->query(str_replace('#WHERE#',$where,$this->qb->user),array($user_name,$password));
   if($query->num_rows() > 0){
    $result = $query->row();   
   }
   $this->db->trans_complete();
   return $result;   
   }

   function getalluserlist($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->Countqueryforgetalluser;    
      }else{          
      $query = $this->qb->queryforgetalluser;
            }
      $query = str_replace(array('##WHERE##','##ORDERBY##','##LIMIT##'),array($where,$order,$limit),$query);      
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
          if($opt=='R'){
       $result = $query->result();
       }else{
        $result = $query->row();
        $result = $result->count;
       }
      }
      $this->db->trans_complete();
      return $result;
   }

   function getuserdatabyid($where,$orderby='',$limit=''){
       $this->db->trans_start();
       $query = $this->qb->queryforgetalluser;
       $query = str_replace(array('##WHERE##','##ORDERBY##','##LIMIT##'),array($where,$orderby,$limit),$query);    
       $query =  $this->db->query($query);
       if($query->num_rows()>0){
       $result = $query->row();
      }
       $this->db->trans_complete();
             return $result;
   }

  // function getCaptainAndCook($where,$orderby='',$limit=''){
  //      $this->db->trans_start();
  //      $query = $this->qb->queryforgetalluser;
  //      $query = str_replace(array('##WHERE##','##ORDERBY##','##LIMIT##'),array($where,$orderby,$limit),$query);      
  //      $query = $this->db->query($query);
  //      if($query->num_rows()>0){
  //       $result = $query->result();
  //       //var_dump($result);die;
  //      }
  //      $this->db->trans_complete();
  //      return $result;
  //  }

   function getUserRole($where=''){
    $this->db->trans_start();
       $query = str_replace('##WHERE##',$where,$this->qb->queryforgetalluserRoles);
       $query =  $this->db->query($query);
       if($query->num_rows()>0){
       $result = $query->result();
      }
     $this->db->trans_complete();
       return $result;   
   }

   function getuserRolebyCode($code=''){
    $this->db->trans_start();
       $query = $this->qb->queryforgetUserRolesByCode;
       $query =  $this->db->query($query,$code);
       if($query->num_rows()>0){
       $result = $query->row();
      }
     $this->db->trans_complete();
       return $result;   
   }

   function getuserRolebyId($id=''){
    $this->db->trans_start();
       $query = $this->qb->queryforgetUserRolesById;
       $query =  $this->db->query($query,$id);
       if($query->num_rows()>0){
       $result = $query->row();
      }
     $this->db->trans_complete();
       return $result;   
   }

   function addedituser($table,$dataArr){
    $this->db->trans_start();
    $this->db->insert($table,$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
   }

   
  function addVendor($dataArr){
    $this->db->trans_start();
    $this->db->insert('vendor',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
   }   

  function editVendor($dataArr,$where){
    $this->db->trans_start();
    $this->db->where($where);
    $this->db->update('vendor',$dataArr);
    $this->db->trans_complete();
   }   
   
   function addUserRole($table,$dataArr){
    $this->db->trans_start();
    $this->db->insert($table,$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;       
   }
   
   function updateStatus($id,$status){
    $this->db->trans_start();
    $this->db->set('status',$status);
    $this->db->where('user_id',$id);
    $this->db->update('user');
    $this->db->trans_complete();
   }

   function updateuser($data,$user_id){
         $this->db->trans_start();
         $this->db->set($data);
         $this->db->where('user_id',$user_id);
         $this->db->update('user');
         $this->db->trans_complete();
   }
   function updateCrew($data,$crew_member_id){
         $this->db->trans_start();
         $this->db->set($data);
         $this->db->where('crew_members_id',$crew_member_id);
         $this->db->update('ship_crew_members');
         $this->db->trans_complete();
   }
   function updateCrewFoodHabits($data,$crew_member_id){
         $this->db->trans_start();
         $this->db->set($data);
         $this->db->where('crew_member_id',$crew_member_id);
         $this->db->update('crew_food_habits');
         $this->db->trans_complete();
   }
   function updateCrewEntry($data,$crew_member_entries_id){
         $this->db->trans_start();
         $this->db->set($data);
         $this->db->where('crew_member_entries_id',$crew_member_entries_id);
         $this->db->update('crew_member_entries');
         $this->db->trans_complete();
   }

   function check_email($user_id){
       if(!empty($user_id)){
        $where = " AND u.user_id !=".$user_id;   
       }
        $this->db->trans_start();
        $query = $this->qb->queryforgetalluser;
       $query = str_replace('##WHERE##',$where,$query);      
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
     $this->db->trans_complete();
     return $result;
   }

  function check_passport($user_id){
       if(!empty($user_id)){
        $where = " AND u.user_id !=".$user_id;   
       }
      $this->db->trans_start();
      $query = $this->qb->queryforgetalluser;
       $query = str_replace('##WHERE##',$where,$query);      
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
     $this->db->trans_complete();
     return $result;
   }

   function deleteUser($user_id,$delete,$status){
         $this->db->trans_start();
         $set = array('is_deleted'=>$delete,'status'=>$status);
         $this->db->set($set);
         $this->db->where('user_id',$user_id);
         $this->db->update('user');
         $this->db->trans_complete();
   }

   function removeUser($id){
    $this->db->trans_start();
    $this->db->where('user_id',$id);
    $this->db->delete('user');
    $this->db->trans_complete();   
   }

   function deleteRoleTask($where){
    $this->db->trans_start();
    $this->db->where($where);
    $this->db->delete('role_task');
    $this->db->trans_complete();
    return $result;     
   }


function getallVendor($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
      if ($perPage != '' || $offset != ''){
        $limit= "LIMIT $offset,$perPage"; 
      }

      $this->db->trans_start();
      if($opt=='C'){
        $query = $this->qb->CountgetAllvendors;    
      }else{
        $query = $this->qb->getAllvendors;    
      }
      $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,$order_by,$limit), $query);
      $query = $this->db->query($query);
    if ($query->num_rows()>0){
     if($opt=='R'){
      $result = $query->result();    
     }else{
         $result = $query->row();
         $result = $result->count;
     }   
    }
    $this->db->trans_complete();
    return $result;         
   }

   function sendMail($to,$subject,$message,$attachment=''){
        $fromEmail= $this->config->item('from_email');
        $fromName = $this->config->item('from_name');
        $is_sent=0;
        $is_error=0;
        $error_content='';
        if($this->config->item('email_send_by')==1 || $this->config->item('email_send_by')==2){
            if($this->config->item('email_send_by')==1){
                $config = Array(
                      'protocol' => 'smtp',
                      'smtp_host' => $this->config->item('smtp_host'),
                      'smtp_port' => $this->config->item('smtp_port'),
                      'smtp_user' => $this->config->item('smtp_user'),
                      'smtp_pass' => $this->config->item('smtp_pass'),
                      'mailtype'  => 'html',
                      // 'charset'   => 'UTF-8'
                      'SMTPCrypto' => 'tls',             // Use TLS encryption
    'mailType' => 'html',
    'charset'  => 'UTF-8',
    'wordWrap' => true,
    'validate' => true,
                    );
                    $this->load->library('email', $config);
                }else{
                  $this->load->library('email');
                  $this->email->initialize($config);
                }

                $this->email->set_newline("\r\n");
                $this->email->set_mailtype('html');
                $this->email->set_crlf("\r\n");
                $this->email->from($fromEmail, $fromName);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                if(!empty($attachment)){
                    foreach($attachment as $a){
                     $this->email->attach($a);
                    }
                }
                $result = $this->email->send();
                
                if(!$result){
                    $is_error= 1;
                 return $this->email->print_debugger();
                }else{
                  $this->email->clear(TRUE);
                  return 'email send';
                }

            }else{
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= 'From: '.$fromName . "\r\n" .
                  'Reply-To: '.$fromEmail . "\r\n" .
                  'X-Mailer: PHP/' . phpversion();
                $result = mail($to,$subject,$message, $headers);
                if($result){
                return  'email send';
                }else{
                     $is_error= 1;
                    return 'There was a problem sending your emailsss.';
                }
            }
    }
    
    function getEmailTemplateByCode($where){
       $this->db->trans_start();
       $query = $this->qb->querygetEmailTemplateByCode;
       $query = str_replace('##WHERE##',$where,$query);      
       $query =  $this->db->query($query);
       if($query->num_rows()>0){
       $result = $query->row();
      }
       $this->db->trans_complete();
             return $result;
   }

  function getNotifyTemplateByCode($where){
       $this->db->trans_start();
       $query = $this->qb->querygetNotifyTemplateByCode;
       $query = str_replace('##WHERE##',$where,$query);      
       $query =  $this->db->query($query);
       if($query->num_rows()>0){
       $result = $query->row();
      }
       $this->db->trans_complete();
             return $result;
   }
  
  function getUserRoleTask($user_id=''){
    $this->db->trans_start();
    $query = $this->qb->getUserRoleTask;
    $query =  $this->db->query($query,$user_id);
    if($query->num_rows()>0){
       $result = $query->result();
      }
   $this->db->trans_complete();
    return $result;
  }
  
  function getCompanyPendingAmount($where='',$group_by){
       $this->db->trans_start();
       $query = $this->qb->querygetCompanyPendingAmount;
       $query = str_replace(array('##WHERE##','##GROUP##'),array($where,$group_by),$query);      
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
      $this->db->trans_complete();
      return $result;
  } 


  function getCompanyReceivedAmount($where='',$group_by=''){
       $this->db->trans_start();
       $query = $this->qb->querygetCompanyReceivedAmount;
       $query = str_replace(array('##WHERE##','##GROUP##'),array($where,$group_by),$query);  
       // echo  $query;die;   
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
      $this->db->trans_complete();
      return $result;
  }


  function getVendorDueAmount($where='',$group_by){
    $this->db->trans_start();
    $query = $this->qb->querygetVendorDueAmount;
     $query = str_replace(array('##WHERE##','##GROUP##'),array($where,$group_by),$query);     
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
      $this->db->trans_complete();
      return $result; 
  }  

  function getVendorPaidAmount($where='',$group_by){
    $this->db->trans_start();
    $query = $this->qb->querygetVendorPaidAmount;
    $query = str_replace(array('##WHERE##','##GROUP##'),array($where,$group_by),$query);  
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
       $result = $query->result();
      }
      $this->db->trans_complete();
      return $result; 
  }  

  function getNotificationList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $user_session_data = getSessionData();
     $this->db->trans_start();
     $limit = '';
      if ($perPage != '' || $offset != ''){
        $limit= "LIMIT $offset,$perPage"; 
      }
      $this->db->trans_start();

      if($opt=='C'){
        $query = $this->qb->queryCountNotification;    
      }else{
        $query = $this->qb->querygetNotification;    
      }

     $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,$order_by,$limit), $query);
      // echo $query;die;
      $query = $this->db->query($query);
     if($query->num_rows()>0){
      if($opt=='R'){
       $result = $query->result();    
       }else{
         $result = $query->row();
         $result = $result->count;
      }   
     }
      $this->db->trans_complete();
     return $result; 
  }  
  
  function addAgent($dataArr){
    $this->db->trans_start();
    $this->db->insert('port_agent',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }


  function getAgentDetailsById($where=''){
    $this->db->trans_start();
    $query = $this->qb->querygetPortAgents;
    $query = str_replace('##WHERE##',$where,$query); 
    $query = $this->db->query($query);
    if($query->num_rows() > 0){
     $result = $query->row();   
    }
    $this->db->trans_complete();
    return $result;    
  }

  function updateAgent($dataArr,$where){
     $this->db->trans_start();
    $this->db->where($where);
    $this->db->update('port_agent',$dataArr);
    $this->db->trans_complete();
  }


 function getAllCountryCode(){
   $this->db->trans_start();
   $query = $this->db->query($this->qb->querygetAllCounty);
   if($query->num_rows() > 0){
    $result = $query->result();   
   }
   $this->db->trans_complete();
   return $result;   
 } 


  function add_notify($dataArr){
    $this->db->trans_start();
    $this->db->insert('notification',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
 }

 function getShipDetailsByUserId($user_id = ''){
  $this->db->trans_start();
   $query = $this->db->query($this->qb->querygetShipDetailsByUserId,$user_id);
   if($query->num_rows() > 0){
    $result = $query->row();   
   }
   $this->db->trans_complete();
   return $result;    
 }

 function getNotifyById($where=''){ 
    $this->db->trans_start();
    $query = $this->qb->querygetNotification;
    $query = str_replace('##WHERE##',$where,$query); 
    $query = $this->db->query($query);
    if($query->num_rows() > 0){
     $result = $query->row();   
    }
    $this->db->trans_complete();
    return $result;    
 }


  function getallUserRole($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->queryforCountuserRoles;    
      }else{          
      $query = $this->qb->queryforgetalluserRoles;
            }
      $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,$order,$limit),$query);      
      $query =  $this->db->query($query);
      if($query->num_rows()>0){
          if($opt=='R'){
       $result = $query->result();
       }else{
        $result = $query->row();
        $result = $result->count;
       }
      }
      $this->db->trans_complete();
      return $result;
   }

  function editRole($dataArr,$where){
    $this->db->trans_start();
    $this->db->where($where);
    $this->db->update('role',$dataArr);
    $this->db->trans_complete();
   }

  function getAllTaskByGroup($where,$role_id='',$orderby=''){
    $this->db->trans_start();
    $query = $this->qb->queryGetAllTaskByGroup;
    $query = str_replace(array('##WHERE##','#ORDERBY#'),array($where,$orderby),$query); 
    $query = $this->db->query($query,$role_id);
    if($query->num_rows() > 0){
     $result = $query->result();   
    }
    $this->db->trans_complete();
    return $result;    
  } 


  function getreportofVc($where=''){
    $this->db->trans_start();
    $query = $this->qb->querygetreportvc;
    $query = str_replace('##WHERE##',$where,$query); 
    $query = $this->db->query($query);
    if($query->num_rows() > 0){
     $result = $query->result();   
    }
    $this->db->trans_complete();
    return $result;    
  }    

 }
 ?>