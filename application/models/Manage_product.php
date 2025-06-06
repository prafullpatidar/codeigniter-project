<?php
 class Manage_product extends CI_Model{
 
  function getAllProductCategory($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }

      $this->db->trans_start();
    if($opt=='C'){
      $query = $this->qb->CountgetAllProductCategory;    
    }else{
      $query = $this->qb->getAllProductCategory;    
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
  
  function changePCstatus($where,$status,$id,$table){
    $this->db->trans_start();
    $this->db->set('status',$status);
    $this->db->where($where);
    $this->db->update($table);
    $this->db->trans_complete();
  }

  function getAllProduct($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
    if ($perPage != '' || $offset != '') {
       $limit= "LIMIT $offset,$perPage"; 
    }
    if($order_by == ''){
        $order_by = " ORDER BY p.item_no ASC ";
      }
    $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllProduct;    
    }else{
    $query = $this->qb->getAllProduct;    
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
  function addproduct($table,$data){
     $this->db->trans_start();
     $this->db->insert($table,$data);
     $insert_id = $this->db->insert_id();
     $this->db->trans_complete();
     return $insert_id;
  }
  function editproduct($table,$data,$id){
     $this->db->trans_start();
     $this->db->set($data);
     $this->db->where('product_id',$id);
     $this->db->update($table);
     $this->db->trans_complete();
  }
  function getAllProductbyid($where){
    $this->db->trans_start();
    $query = $this->qb->getAllProduct;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;      
  }
  function deleteproductById($id){
    $this->db->trans_start();
    $this->db->where('product_id',$id);
    $this->db->delete('product');
    $this->db->trans_complete();
  }
  function addproductCategory($table,$data){
       $this->db->trans_start();
       $this->db->insert($table,$data);
       $this->db->trans_complete();
  }
   function getAllProductCategorybyid($where){
    $this->db->trans_start();
    $query = $this->qb->getAllProductCategory;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;      
  }
  function deleteproductByCategory($id){
    $this->db->trans_start();
    $this->db->where('product_category_id',$id);
    $query = $this->db->delete('product_category');
    if($query == true){
        $result = 0;
    }else{
        $result = 1;   
    } 
   $this->db->trans_complete();
   return  $result;
  }
  
  function editproductCategory($table,$data,$id){
   $this->db->trans_start();
     $this->db->set($data);
     $this->db->where('product_category_id',$id);
     $this->db->update($table);
     $this->db->trans_complete();   
  }

  function getAllProductInventory($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
      $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllProductInventory;    
    }else{
    $query = $this->qb->getAllProductInventory;    
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
    function addproductionline($table,$dataArr){
     $this->db->trans_start();
     $this->db->insert($table,$dataArr);
     $insert_id = $this->db->insert_id();
     $this->db->trans_complete();
     return $insert_id;
    } 
    function addproductionInventory($table,$dataArr){
     $this->db->trans_start();
     $this->db->insert($table,$dataArr);
     $this->db->trans_complete();
    }
    function getAllProductLine($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
        $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->CountgetAllProductline;    
    }else{
    $query = $this->qb->getAllProductline;    
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
  function addProductInventory($table,$dataArr){
       $this->db->trans_start();
       $this->db->insert($table,$dataArr); 
       $this->db->trans_complete();
  }
  function addCustomerOrder($table,$dataArr){
       $this->db->trans_start();
       $this->db->insert($table,$dataArr);
       $insert_id = $this->db->insert_id(); 
       $this->db->trans_complete();
       return $insert_id; 
  }

  function updateCustomerOrder($table_name, $dataArr, $condition) {
    $this->db->trans_start();
    $this->db->update($table_name, $dataArr, $condition);
    $this->db->trans_complete();
    return true;
  }

  function deleteData($table_name, $condition){
    $this->db->trans_start();
    if(!empty($condition)){
      $this->db->where($condition);
      $this->db->delete($table_name);
    }
    $this->db->trans_complete();
  }

  function deleteInventory($id,$type){
    $this->db->trans_start();
    if($type == 'Customer'){
      $inventoryData = $this->getAllProductInventory(" AND pi.product_inventory_id = '$id'", "R");
      $customer_order_id = $inventoryData[0]->customer_order_id;
      
      $this->db->where('product_inventory_id',$id);
      $this->db->delete('product_inventory');

      $checkCustomerOrder = $this->getAllProductInventory(" AND pi.customer_order_id = '$customer_order_id'", "R");

      if(empty($checkCustomerOrder)){
        $this->db->where('customer_order_id',$customer_order_id);
        $this->db->delete('customer_order');        
      }

     //$query = "DELETE pi.*,co.* FROM product_inventory pi LEFT JOIN customer_order co on co.customer_order_id = pi.customer_order_id WHERE pi.product_inventory_id = ".$id;   
      //$this->db->query($query);
    }elseif($type == 'Product' || $type == 'Adjust'){
     $this->db->where('product_inventory_id',$id);
     $this->db->delete('product_inventory');
    }
     $this->db->trans_complete(); 
    } 
   function getinventoryById($where){
    $this->db->trans_start();
    $query = $this->qb->getAllProductInventory;      
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();
    }
    $this->db->trans_complete();
    return $result;    
   }
   function editproductInventory($table,$data,$where,$type){
    $this->db->trans_start();
    if($type=='In'){
      $this->db->set($data);
      $this->db->where($where);
      $this->db->update($table);
    }else{
      $query = 'Update product_inventory p left join customer_order c on c.customer_order_id = p.customer_order_id SET'.$data.' '.$where;
      $this->db->query($query);        
    }
    $this->db->trans_complete();
   }

  function deleteProductInventoryByAdjustmentId($id){
    $this->db->trans_start();
    $this->db->where('adjustment_id',$id);
    $query = $this->db->delete('product_inventory');
    if($query == true){
      $result = 0;
    }else{
      $result = 1;   
    } 
    $this->db->trans_complete();
    return  $result;
  }

  function getAllProductGroup($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
    if ($perPage != '' || $offset != ''){
      $limit= "LIMIT $offset,$perPage"; 
    }

    $this->db->trans_start();
    if($opt=='C'){
      $query = $this->qb->queryGetAllGroupCount;    
    }else{
      $query = $this->qb->queryGetGroup;    
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

  function getGroupDetailsByID($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetGroup;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if ($query->num_rows()>0){
      $result = $query->row();       
    }
    $this->db->trans_complete();
    return $result;  
  }

  function editproductGroup($table,$data,$id){
   $this->db->trans_start();
     $this->db->set($data);
     $this->db->where('product_group_id',$id);
     $this->db->update($table);
     $this->db->trans_complete();   
  }

  function getCompanyInvoice($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetCompanyInvoice; 
        
    $query = str_replace('##WHERE##',$where, $query);

    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->row();
    }
    $this->db->trans_complete();
    return $result;
 }

 // function getStockDetail($where){
 //    $this->db->trans_start();
 //    $query = $this->qb->queryGetStockDetail; 
        
 //    $query = str_replace('##WHERE##',$where, $query);

 //    $query = $this->db->query($query);
    
 //    if ($query->num_rows()>0){
 //      $result = $query->result();
 //    }
 //    $this->db->trans_complete();
 //    return $result;
 // }

  function getStockDetail($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetStockDetail; 
        
    $query = str_replace('##WHERE##',$where, $query);

    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->row();
    }
    $this->db->trans_complete();
    return $result;
 }

 function getConsumedStockDetail($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetConsumedStockDetailNew; 
        
    $query = str_replace('##WHERE##',$where, $query);

    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->result();
    }
    $this->db->trans_complete();
    return $result;
 }

 function getDeliveryNoteData($where){
    $this->db->trans_start();
    $query = $this->qb->queryGetDeliveryNoteData; 
        
    $query = str_replace('##WHERE##',$where, $query);

    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->row();
    }
    $this->db->trans_complete();
    return $result;
 }
 
 function getAllProductCategoryNew($where){
    $this->db->trans_start();
    $query = $this->qb->getAllProductCategoryNew; 
        
    $query = str_replace('##WHERE##',$where, $query);

    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->result();
    }
    $this->db->trans_complete();
    return $result;
 }


 function getAllShipWiseProduct($where){
    $this->db->trans_start();
    $query = $this->qb->querygetShipwiseProduct; 
        
    $query = str_replace('##WHERE##',$where, $query);
    // echo $query;die;
    $query = $this->db->query($query);
    
    if ($query->num_rows()>0){
      $result = $query->result();
    }
    $this->db->trans_complete();
    return $result;
 }
   
 }
 ?>