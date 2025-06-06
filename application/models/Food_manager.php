<?php

class Food_manager extends CI_Model
{

  function getAllFoodMenuList($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
       $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllFoodMenu;    
    }else{
    $query = $this->qb->querygetAllFoodMenu;    
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

  function addFoodMenu($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('food_menu',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  } 

  function editFoodMenu($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('food_menu');
    $this->db->trans_complete();  
  }

  function getFoodMenuById($where){
   $this->db->trans_start();
    $query = $this->qb->querygetAllFoodMenu;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }


  function addFoodRecipe($data){
    $insert_id = '';
    $this->db->trans_start();
    $this->db->insert('food_recipe',$data);
    $insert_id = $this->db->insert_id();
    $this->db->trans_complete();
    return $insert_id;
  } 

  function editFoodRecipe($data,$where){
    $this->db->trans_start();
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update('food_recipe');
    $this->db->trans_complete();  
  }

  function getFoodRecipeById($where){
   $this->db->trans_start();
    $query = $this->qb->querygetAllFoodRecipe;    
    $query = str_replace('##WHERE##',$where, $query);
    $query = $this->db->query($query);
    if($query->num_rows()>0){
      $result = $query->row();    
     }
    $this->db->trans_complete();
    return $result;    
  }


  function getAllRecipe($where='',$opt='C',$perPage='',$offset='',$order_by=''){
    $limit = '';
        if ($perPage != '' || $offset != '') 
        {
           $limit= "LIMIT $offset,$perPage"; 
        }
       $this->db->trans_start();
    if($opt=='C'){
    $query = $this->qb->countgetAllFoodRecipe;    
    }else{
    $query = $this->qb->querygetAllFoodRecipe;    
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