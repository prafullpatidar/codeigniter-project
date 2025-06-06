<?php
class Manage_vendor extends CI_Model{

  function addVendorOrder($table,$data){
     $this->db->trans_start();
     $this->db->insert($table,$data);
     $insert_id = $this->db->insert_id();
     $this->db->trans_complete();
     return $insert_id;
   }
  
   function getallVendorOrder($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
       $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetallvendorOrder;    
    }else{
    $query = $this->qb->getallvendorOrder;    
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

  function getVendorOrderDetails($where){
    $this->db->trans_start();
    $query = $this->qb->getallvendorOrder;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function getVendorInvoiceList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY vi.vendor_invoice_id DESC ';
        }

    $this->db->trans_start();
    
    if($opt=='C'){
     $query = $this->qb->countVendorInvoices;    
    }else{
     $query = $this->qb->querygetVendorInvoices;    
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
  
  function add_vendor_invoice($data){
     $this->db->trans_start();
     $this->db->insert('vendor_invoice',$data);
     $insert_id = $this->db->insert_id();
     $this->db->trans_complete();
     return $insert_id;
  }
  

  function getVendorInvoiceDataByID($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetVendorInvoiceDetails);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  } 

  function getVendorTransData($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
    $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->getCountVendorTrans;    
    }else{
    $query = $this->qb->getVendorTransData;    
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

 }
?>
