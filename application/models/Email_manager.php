<?php
 class Email_manager extends CI_Model{
  
  function getEmailRoles($template_id=''){
    $this->db->trans_start();
    $query = $this->qb->querygetAllEmailRoles;    
    $query = $this->db->query($query,$template_id);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;      
  }

  function getNotifyRoles($template_id=''){
    $this->db->trans_start();
    $query = $this->qb->querygetAllNotifyRoles;    
    $query = $this->db->query($query,$template_id);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;      
  }
  

  function add_email_log($dataArr){
    $this->db->trans_start();
    $this->db->insert('email_logs',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }


  function getUserByRoleID($role_id=''){
    $this->db->trans_start();
    $query = $this->qb->querygetUserByRoleID;    
    $query = $this->db->query($query,$role_id);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;     	
  }


  function getAllEmailLogs($where='',$perPage='',$offset=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
    $this->db->trans_start();
    $query = $this->qb->querygetAllEmailLogs; 
    $query = str_replace(array('##WHERE##','##LIMIT##'),array($where,$limit), $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result; 
  }



 }
?>