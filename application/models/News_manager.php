<?php

class News_manager extends CI_Model
{

  function getAllnewsList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
       $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllNews;    
    }else{
    $query = $this->qb->querygetAllNews;    
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

   function addNewsLetter($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('newsletter',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  } 

  function editNewsLetter($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('newsletter');
    $this->db->trans_complete();  
  }

  function getNewsLetterById($where){
   $this->db->trans_start();
    $query = $this->qb->querygetAllNews;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }

}
?>