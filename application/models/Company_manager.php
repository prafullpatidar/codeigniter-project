<?php
class Company_manager extends CI_Model{
 
  function getAllshippingCompany($where='',$opt='C',$perPage='',$offset='',$order_by=''){
        $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
        if($order_by == ''){
          $order_by = ' ORDER BY c.name ASC ';
        }
     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllCompany;    
    }else{
    $query = $this->qb->QuerygetAllCompany;    
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

   function getAllShips($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
       $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllships;    
    }else{
    $query = $this->qb->QuerygetAllships;    
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
  
  function addcompany($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('shipping_company',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  } 

  function editcompany($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('shipping_company');
    $this->db->trans_complete();  
  }
  
  function changestatus($table,$status,$where){
    $this->db->trans_start();
    $this->db->set('status',$status);
    $this->db->where($where);
    $this->db->update($table);
    $this->db->trans_complete();  
  }

  function deleteCompany($table, $where){
       $this->db->trans_start();
      // $this->db->where($where);
       $this->db->set('is_deleted', 1);
        $this->db->where($where);
        $this->db->update($table);
       //$this->db->delete($table); 
       $this->db->trans_complete();  
  }

  function getAllComapnyById($where){
   $this->db->trans_start();
    $query = $this->qb->QuerygetAllCompany;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }
  
  function getAllVendorById($where){
    $this->db->trans_start();
    $query = $this->qb->getAllvendors;    
    $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,'',''), $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;   
  }

  function addShips($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('ships',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
     return $insert_id;
  } 

  function editShips($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('ships');
    $this->db->trans_complete();  
  }

  function getAllShipsById($where){
   $this->db->trans_start();
    $query = $this->qb->QuerygetAllships;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }

  function getNewShipById($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetShipDetails;    
    $query = str_replace('##WHERE##',$where, $query);
    // echo $query;die;
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }

  function add_ship_port($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('ship_ports',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  } 

  function edit_ship_port($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('ship_ports');
    $this->db->trans_complete();  
  }
  
 function getNextPort($ship_id =''){
   $this->db->trans_start();
    $query = $this->qb->queryGetNextPortByID;    
    $query = $this->db->query($query,$ship_id);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }  


  function getAllPortList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
        $limit = '';
        
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }

        if($order_by == ''){
          $order_by = ' ORDER BY sp.port_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->count_ports;    
    }else{
    $query = $this->qb->get_all_port;    
    }
    $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,$order_by,$limit), $query);
    // echo $query;die;
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

  function getAllportById($where){
   $this->db->trans_start();
    $query = $this->qb->get_all_port;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }

  function add_ship_stock($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('ship_stock',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

  function edit_ship_stock($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('ship_stock');
    $this->db->trans_complete();  
  } 

   function add_ship_order_stock($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('ship_order',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

  function edit_ship_order_stock($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('ship_order');
    $this->db->trans_complete();  
  }

  function getAllshipStockOrder($where='',$opt='C',$perPage='',$offset='',$order_by=''){
        $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
      }
    
      if($order_by == ''){
          $order_by = ' ORDER BY so.ship_order_id DESC ';
      }
     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllShipStockOrder;    
    }else{
    $query = $this->qb->QuerygetAllShipStockOrder;    
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
   

  // function add_ship_current_stock($data){
  //   $insert_id = '';
  //   $this->db->trans_start();
  //   $this->db->insert('current_stock_details',$data);
  //   $insert_id = $this->db->insert_id();
  //   $this->db->trans_complete();
  //   return $insert_id;
  // }



  // function edit_ship_current_stock($data,$where){
  //   $this->db->trans_start();
  //   $this->db->set($data);
  //   $this->db->where($where);
  //   $this->db->update('current_stock_details');
  //   $this->db->trans_complete();  
  // } 

  function add_month_stock_details($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('monthly_stock_details',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }  


  function edit_month_stock_details($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('monthly_stock_details');
    $this->db->trans_complete();  
  } 



  function getShipStockById($ship_id='',$where=''){
    $this->db->trans_start();
    $query = $this->db->query(str_replace('#WHERE#',$where,$this->qb->queryGetShipStock),$ship_id);
     if($query->num_rows()>0){
      $result = $query->result();    
     }

    $this->db->trans_complete();
    return $result;    
  }
   
  
  function current_stock_details($where){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetconsumedstock);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }

  function monthly_stock_details($where){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetmonthlyStockDetails);
    // echo $query;die;
    $query = $this->db->query($query);
     if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }



  function add_consumed_stock($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('consumed_stock',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

  function edit_consumed_stock($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('consumed_stock');
    $this->db->trans_complete();  
  } 

  function current_stock_item($ship_id='',$product_id=''){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetCurrentStockDetails,array($ship_id,$product_id));
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;
  }


  function getAllProductForOrderStock($where){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetProductForOrderStock);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }
  

  // function getRrqItemsByID($where=''){
  //   $this->db->trans_start();
  //   $query = str_replace('##WHERE##',$where,$this->qb->querygetRfqItem);
  //   $query = $this->db->query($query,$ship_id);
  //   if($query->num_rows()>0){
  //     $result = $query->result();    
  //    }
  //   $this->db->trans_complete();
  //   return $result;
  // }

  function getRrqItemsByID($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetRfqItem);
    $query = $this->db->query($query,$ship_id);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;
  }

  function add_po_stock($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('work_order',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
  
 function edit_po_stock($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('work_order');
    $this->db->trans_complete();  
  } 
  
  function getAllWorkOrders($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY wo.work_order_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllworkOrders;    
    }else{
    $query = $this->qb->querygetAllworkOrders;    
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


function getWorkOrderByID($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetworkOrdersByID);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }
 

 function add_vendor_quote($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('vendor_quotation',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
  
  function edit_vendor_quote($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('vendor_quotation');
    $this->db->trans_complete();  
  } 
  
  // function getVendorQuoteByID($where=''){
  //   $this->db->trans_start();
  //   $query = str_replace('##WHERE##',$where,$this->qb->querygetAllVendorQuoteByID);
  //   $query = $this->db->query($query);
  //   if ($query->num_rows()>0){
  //     $result = $query->row();       
  //   }
  //   $this->db->trans_complete();
  //   return $result;  
  // }


  function getOrderProduct($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->getOrderProduct);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function getQuotedVendor($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetQuoteVendor);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;   
  }
  
 function getQuotedDetails($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetQuoteDetails);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;   
  }

  function getWorkDetailsByID($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetPODetails);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;
  }


  
  function getDeliveryNoteNo($ship_id=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->getDeliveryNoteNo);
    $query = $this->db->query($query,$ship_id);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function getDeliveyNoteData($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetDeliveryNoteDetail);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }
  
  function get_current_stock($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetCurrentStock);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }

   function add_vendor_quote_app($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('vendor_quote_approvals',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

   function add_delivery_note($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('delivery_note',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
   }


  function edit_delivery_note($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('delivery_note');
    $this->db->trans_complete();  
  }

  function add_tmp_delivery_note($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('tmp_delivery_note',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
   }


  function edit_tmp_delivery_note($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('tmp_delivery_note');
    $this->db->trans_complete();  
  }

  function add_delivery_feedback($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('feedback',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
   }
  

  function getAllDeliveryNote($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY dn.delivery_note_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countDeliveryNotes;    
    }else{
    $query = $this->qb->querygetDeliveryNotes;    
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

  function getAllInvoiceList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY inv.company_invoice_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countInvoices;    
    }else{
    $query = $this->qb->querygetInvoices;    
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

  function getAllStockList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY st.ship_stock_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countStocks;    
    }else{
    $query = $this->qb->querygetStocksNew;    
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

  function getAllConsumedStockList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY ct.consumed_stock_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countConsumedStocks;    
    }else{
    $query = $this->qb->querygetConsumedStocksNew;    
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
  
   function add_extra_meals($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('extra_meals',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

  function edit_extra_meals($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('extra_meals');
    $this->db->trans_complete();  
  }


  function getDeliveyReceipt($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetDeliveryNoteDetailPDF);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function getExtraMealDetails($where = ''){
     $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetExtraMealsDT);
    $query = $this->db->query($query);
      if ($query->num_rows()>0){
        $result = $query->result();       
      }
      $this->db->trans_complete();
      return $result; 
  }

  function getTmpdeliveryReceipt($tmp_delivery_receipt_id){
     $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetTmpDeliveryReceipt,$tmp_delivery_receipt_id);
      if ($query->num_rows()>0){
        $result = $query->row();       
      }
      $this->db->trans_complete();
      return $result; 
  }

 function getAllExtraMeal($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
      
       if($order_by == ''){
          $order_by = ' ORDER BY em.extra_meal_id DESC';
        }

    $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllExtraMeals;    
    }else{
    $query = $this->qb->queryGetExtraMeals;    
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

  function checkDeliveryNoteCreated($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryDNByWorkOrderId);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function getInvoiceDetailById($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetInvoiceDetailById);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }
  
  function edit_invoice($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('company_invoice');
    $this->db->trans_complete();  
  } 

  function getQuotedDetailsForMaster($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryForMasterQuoteReview);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;   
  }

  
  function add_ship_product_category($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('shipwise_category',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
  
  function getProductCategoriesByShipId($where){
   $this->db->trans_start();
    $query = $this->qb->QuerygetProductCategoriesByShipId;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;    
  }

  function getVendorQuotation($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->queryGetVendorQuotation);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->result();       
    }
    $this->db->trans_complete();
    return $result;   
  }

  // function getWorkOrderData($where=''){
  //   $this->db->trans_start();
  //   $query = str_replace('##WHERE##',$where,$this->qb->querygetWorkOrderData);
  //   $query = $this->db->query($query);
  //   if($query->num_rows()>0){
  //     $result = $query->row();    
  //    }
  //   $this->db->trans_complete();
  //   return $result;
  // }

  function getVendorDataByWorkOrder($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetVendorDataByWorkOrder);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;
  }

  function getSelectedQuote($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetSelectedQuote);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result; 
  }


  function getallCrewlist($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->countQuerygetAllCrew;    
      }else{          
      $query = $this->qb->querygetAllCrew;
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


  function getEminvoiceData($extra_meal_id=''){
    $this->db->trans_start();
    $query = $this->qb->querygetEminvoiceData;
    $query = $this->db->query($query,$extra_meal_id);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result; 
  }   


  function getEmInvoiceById($extra_meal_id=''){
    $this->db->trans_start();
    $query = $this->qb->querygetEmInvoiceById;
    $query = $this->db->query($query,$extra_meal_id);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result; 
  }

  function addMonthInvoice($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('company_month_invoice',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }


  function getCaptainAndCook($where=''){
    $this->db->trans_start();
    $query = str_replace('##WHERE##',$where,$this->qb->querygetCookAndCaptain);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }  

  function getallFoodHabitlist($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->countQuerygetAllFoodHabits;    
      }else{          
      $query = $this->qb->querygetAllFoodHabits;
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

   function getallCrewEntrieslist($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->countQuerygetAllCrewEntries;    
      }else{          
      $query = $this->qb->querygetAllCrewEntries;
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
  
  function add_report_data($data,$table){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert($table,$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
  
  function edit_report_data($data,$where,$table){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update($table);
    $this->db->trans_complete();  
  }
 
  function getAllCondemnedStockReportData($where='',$opt='C',$offset='',$perPage='',$order=''){
       $limit = '';
       if($offset!='' || ($perPage)!=''){
        $limit .= "LIMIT ".$offset.",".$perPage;   
       }
       $this->db->trans_start();
      if($opt=='C'){
      $query = $this->qb->countQuerygetAllCondemnedStockReportData;    
      }else{          
      $query = $this->qb->queryAllCondemnedStockReportData;
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

  function getCondemnedStockReportById($where){
    $this->db->trans_start();
    $query = $this->qb->queryAllCondemnedStockReportData;    
    $query = str_replace(array('##WHERE##','##ORDER##','##LIMIT##'),array($where,'',''), $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;   
  }
  
  function getAssignedShiptoCaptain($user_id=''){
   $this->db->trans_start();
   $query = $this->db->query($this->qb->queryGetAssignedShiptoCaptain,array($user_id));
   if($query->num_rows() > 0){
    $result = $query->row();   
   }
   $this->db->trans_complete();
   return $result;   
   }

  function getAssignedShiptoCook($user_id){
   $this->db->trans_start();
   $query = $this->db->query($this->qb->queryGetAssignedShiptoCook,array($user_id));
   if($query->num_rows() > 0){
    $result = $query->row();   
   }
   $this->db->trans_complete();
   return $result;   
   }



  function getAllvSummaryReport($where='',$opt='C',$perPage='',$offset='',$order_by=''){
     $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
        if($order_by == ''){
          $order_by = ' ORDER BY vs.summary_report_id DESC';
        }

    $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllSummaryReport;    
    }else{
    $query = $this->qb->querygetAllSummaryReport;    
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
  

  function add_summary_report($dataArr){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('victualing_summary',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }

 function getSummaryReportById($where){
    $this->db->trans_start();
    $query = $this->qb->querygetAllSummaryReport;    
    $query = str_replace(array('##WHERE##'),array($where), $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;   
  } 

 function add_transaction_history($dataArr){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('invoice_transaction',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
 
  function getAllTransactionList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
        if($perPage != '' || $offset != ''){
           $limit= "LIMIT $offset,$perPage"; 
        }
        
        if($order_by == ''){
          $order_by = ' ORDER BY t.invoice_transaction_id DESC ';
        }

     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countAllInvoiceTransaction;    
    }else{
    $query = $this->qb->queryGetAllInvoiceTransaction;    
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

  function invoiceTransHistoryById($where=''){
    $this->db->trans_start();
    $query = $this->qb->getInvoiceTransHistoryById;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();   
     }
    $this->db->trans_complete();
    return $result;    
  }      
  
  function add_log_activity($dataArr=''){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('log_activity',$dataArr);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }
  

  function getLogActivity($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
      if ($perPage != '' || $offset != '') 
      {
         $limit= "LIMIT $offset,$perPage"; 
      }
      if($order_by == ''){
        $order_by = ' ORDER BY l.added_on desc ';
      }
     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->querygetCountLogActivity;    
    }else{
    $query = $this->qb->querygetLogActivity;    
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


  function getLogActivityByID($log_id=''){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->getqueryLogActivityByID,$log_id);
    if($query->num_rows()>0){
      $result = $query->row();   
     }
    $this->db->trans_complete();
    return $result;     
  }

  function getAllPortAgents($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
      if ($perPage != '' || $offset != '') 
      {
         $limit= "LIMIT $offset,$perPage"; 
      }
      if($order_by == ''){
        $order_by = ' ORDER BY pa.name ASC ';
      }
    $this->db->trans_start();
      if($opt=='C'){
       $query = $this->qb->CountPortAgents;    
      }else{
       $query = $this->qb->querygetPortAgents;    
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
  
  function deleteVendorQuoteDetails($ship_order_id=''){
    $this->db->trans_start();
    $query = $this->qb->querydeleteVendorQuoteDetails;    
    $query = $this->db->query($query,$ship_order_id);
    $this->db->trans_complete();
  }


  function victualing_transaction($where=''){
    $this->db->trans_start();
    $query = $this->qb->querygetVictualingTrans;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();   
     }
    $this->db->trans_complete();
    return $result;    
  }


  function avalibleStockByGroup($where=''){
    $this->db->trans_start();
    $query = $this->qb->querygetstockbygroup;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->result();   
     }
    $this->db->trans_complete();
    return $result;    
  }


  // function monthly_stock($where=''){
  //   $this->db->trans_start();
  //   $query = $this->qb->querygetMonthStockValue;    
  //   $query = str_replace('##WHERE##',$where, $query);
  //   $query = $this->db->query($query);
  //   if($query->num_rows()>0){
  //     $result = $query->row();   
  //    }
  //   $this->db->trans_complete();
  //   return $result;    
  // }
  

   function getAllMeatReport($where='',$opt='C',$perPage='',$offset='',$order_by=''){
      $limit = '';
      if ($perPage != '' || $offset != '') 
      {
         $limit= "LIMIT $offset,$perPage"; 
      }
     $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->querygetCountMonthMeatReport;    
    }else{
    $query = $this->qb->querygetMonthMeatReport;    
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


 // function update_meat_stock($data,$where){
 //    $this->db->trans_start();
 //    $this->db->set($data);
 //    $this->db->where($where);
 //    $this->db->update('month_stock_value');
 //    $this->db->trans_complete();
 // }  



 function add_month_stock($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('month_stock',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  }  


 function stock_years($ship_id=''){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetStockYear,$ship_id);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  } 

  function stock_month($ship_id='',$year=''){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetStockMonth,array($ship_id,$year));
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }

  function stock_month_value($ship_id='',$month='',$year=''){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetStockValue,array($ship_id,$month,$year));
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;
  } 

function deleteTmpDeliveryReceipt($tmp_delivery_receipt_id) {
    $this->db->trans_start();
    $this->db->query($this->qb->deleteTmpDeliveryReceipt,$tmp_delivery_receipt_id);
    $this->db->trans_complete();
    return true;
}

  function getfeedbackByID($delivery_note_id){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->queryGetfeedbackByID,$delivery_note_id);
    if($query->num_rows()>0){
      $result = $query->row();    
     }

    $this->db->trans_complete();
    return $result;
    
  }


  function nutrition_report_data($month_stock_id){
    $this->db->trans_start();
    $query = $this->db->query($this->qb->querygetNutritionById,$month_stock_id);
    if($query->num_rows()>0){
      $result = $query->result();    
     }
    $this->db->trans_complete();
    return $result;
  }


  function deleteCrewMemberFoodHabit($crew_members_id) {
    $this->db->trans_start();
    $this->db->query($this->qb->deleteFoodHabit,$crew_members_id);
    $this->db->trans_complete();
    return true;
}

}
?>