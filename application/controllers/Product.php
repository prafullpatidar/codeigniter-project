<?php
class Product extends CI_Controller{
   
  function __construct(){
   parent::__construct();
   $this->load->model('manage_product');
   $this->mp = $this->manage_product;
   $this->load->library('querybundel');
   $this->qb = $this->querybundel;
  }

  function product_category(){
   checkUserSession();
   $user_session_data = getSessionData();    
   $vars['user_session_data'] = $user_session_data;   
   $vars['active'] = 'PC';
   $vars['heading'] = 'Product Category';
   $vars['content_view'] = 'product_category_list';
   $vars['products_group'] = $this->mp->getAllProductGroup(' AND pg.status = 1','R');
   $this->load->view('layout',$vars);   
  }

  function getAllproductCategory(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = ' AND pc.parent_category_id is not null';
   $returnArr = '';
   $order_by = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    if(!empty($keyword)){
     $where .= " AND ( pc.category_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";   
    }
    if(!empty($product_group_id)){
          $where .= ' AND (pg.product_group_id ='.$product_group_id.')';
    }

        if(!empty($status)){
            $status = trim($status);
            if ($status == 'A'){
                $where .= " AND pc.`status`='1' ";
            }elseif ($status == 'D'){
                $where .= " AND pc.`status`='0' ";              
            } 
        }

    if($created_on){
       $where .= ' AND date(pc.added_on) = "'.convertDate($created_on,'','Y-m-d').'"'; 
     }    

    if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Category'){
                $order_by = 'ORDER BY pc.category_name '.$sort_type;
            }
            elseif($sort_column == 'Sequence'){
                $order_by = 'ORDER BY pc.sequence '.$sort_type;
            }
            elseif($sort_column == 'CreatedBy'){
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
            elseif($sort_column == 'CreatedDate'){
                $order_by = 'ORDER BY pc.added_on '.$sort_type;
            }
            elseif($sort_column == 'Group'){
                $order_by = 'ORDER BY pg.name '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY pc.sequence ASC';
        }

        if($download==1){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'CategoryList.xlsx';
           $arrayHeaderData= array('Sequence','Category','Group','Created On','Created By','Status');
            $listColumn     = array();
           $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'A1:A1','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'B2:E5','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'E1:E1','font'=>array(),'alignment'=>$align)));

            $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:25','B:25','C:30','D:18','E:18','F:18','G:18','H:18','I:18','J:18','K:25','L:25')); 
           
            $listColumn[] = array('addImage'=>'1','coordinates'=>'A2','height'=>90);
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                        'color' => array('rgb' => '4F2270'),
                        'size'  => 40,
                        'name'  => 'Calibri'
                          )
                        ),'cellArray'=>array('B2'));
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:F7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7;
           $products = $this->mp->getAllProductCategory($where,'R','','',$order_by);
           if($products){
                foreach ($products as $row) {
                  $k++;
                   $status = ($row->status==1) ? 'Activate' : 'Deactivate'; 
                  $arrayData[] = array($row->sequence,$row->category_name,ucwords($row->group_name),convertDate($row->added_on,'','d-m-Y'),ucfirst($row->creator_name),$status);   
                }
           }
            $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) 
                  );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'CategoryList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit; 
        }

         $countdata = $this->mp->getAllProductCategory($where,'C');
         $offset = ($cur_page * $perPage) - $perPage;
         $pages = new Paginator($countdata,$perPage,$cur_page);
         $products = $this->mp->getAllProductCategory($where,'R',$perPage,$offset,$order_by);
         if($products){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($products)).' of '.$countdata.' entries';
          $edit_category = checkLabelByTask('edit_product_category');
         foreach ($products as $row){
        if($edit_category){
          if($row->status == 0){
                $Status = '<a onclick="updateStatusBox('.$row->product_category_id.','.$row->status.',\''.$row->category_name.'\',\'product/changestatusforProductCategory\')" href="javascript:void(0)">Activate</a>';   
             }else{
                $Status = '<a onclick="updateStatusBox('.$row->product_category_id.','.$row->status.',\''.$row->category_name.'\',\'product/changestatusforProductCategory\')" href="javascript:void(0)">Deactivate</a>';  
                $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Product Category\',\'product/addEditproductCategory\','.$row->product_category_id.',\'\',\'70%\');" >Edit</a>';

             }
                                      
       }

        $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
          $returnArr .= "<tr>
                        <td width='7%'>".$row->sequence."</td>
                        <td width='10%'>".ucfirst($row->category_name)."</td>
                        <td width='10%'>".ucfirst($row->group_name)."</td>
                        <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y')."</td>
                        <td width='10%'>".ucfirst($row->creator_name)."</td>
                        <td width='10%'>".$status."</td>";
              $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                </ul>
                                </div></td> </tr>';          
         } 

          if($countdata <=5){
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
        
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";

         }

         $pagination = $pages->get_links();


     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  }

  function changestatusforProductCategory(){
   $id = $this->input->post('id');
   $status = $this->input->post('status');
   $where = 'Product_category_id ='.$id;
   $status = ($status== '1' )? '0' :'1';
   $this->mp->changePCstatus($where,$status,$id,'product_category');
   $this->session->set_flashdata('succMsg','Product Category status changed successfully.');
  }

  function product(){
   checkUserSession();
   $user_session_data = getSessionData();    
   $vars['user_session_data'] = $user_session_data;   
   $vars['active'] = 'PD';
   $vars['heading'] = 'Product List';
   $vars['content_view'] = 'product_list';
   $vars['products_category'] = $this->mp->getAllProductCategory(' and pc.parent_category_id is not null And pc.status=1','R');
   $this->load->view('layout',$vars);   
  } 

  function getallproductlist(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = ' and p.is_custom_product = 0';
   $returnArr = '';
   $order_by = '';
   extract($this->input->post());
    if(!empty($keyword)){
     $where .= " AND ( p.item_no like '%".trim($keyword)."%' or p.product_name like '%".trim($keyword)."%' or p.unit like '%".trim($keyword)."%')";   
    }

     if(!empty($status)){
            $status = trim($status);
            if ($status == 'A'){
                $where .= " AND ( p.`status`='1' ) ";
            }elseif ($status == 'D'){
                $where .= " AND ( p.`status`='0' )";              
            } 
        }    

        if(!empty($product_category_id)){
          $where .= ' AND (p.product_category_id ='.$product_category_id.')';
        }
        
        if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Product')
            {
                $order_by = 'ORDER BY p.product_name '.$sort_type;
            }
            elseif($sort_column == 'Item No')
            {
                $order_by = 'ORDER BY p.item_no '.$sort_type;
            }
            elseif($sort_column == 'Unit')
            {
                $order_by = 'ORDER BY p.unit '.$sort_type;
            }
            elseif($sort_column == 'Category')
            {
                $order_by = 'ORDER BY pc.category_name '.$sort_type;
            }
            elseif($sort_column == 'Sku')
            {
                $order_by = 'ORDER BY p.sku '.$sort_type;
            }
        }
        else
        {
            $order_by = 'ORDER BY p.product_name ASC';
        }
     
     if($download==1){
       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'ProductList.xlsx';
           $arrayHeaderData= array('Item No','Name','Category','Unit','Status');
            $listColumn     = array();
           $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'A1:A1','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'B2:E5','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'E1:E1','font'=>array(),'alignment'=>$align)));

            $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:25','B:25','C:30','D:18','E:18','F:18','G:18','H:18','I:18','J:18','K:25','L:25')); 
           
            $listColumn[] = array('addImage'=>'1','coordinates'=>'A2','height'=>90);
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                        'color' => array('rgb' => '4F2270'),
                        'size'  => 40,
                        'name'  => 'Calibri'
                          )
                        ),'cellArray'=>array('B2'));
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:E7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7; 
           $products = $this->mp->getAllProduct($where,'R','','',$order_by);
           if($products){
             foreach ($products as $row) {
                $k++;
                $status = ($row->status==1) ? 'Activate' : 'Deactivate';
                $arrayData[] = array($row->item_no,ucwords($row->product_name),ucwords($row->category_name),strtoupper($row->unit),$status);
             }
           } 
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN')) 
                  );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'ProductList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   
     } 

    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    
    $countdata = $this->mp->getAllProduct($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $products = $this->mp->getAllProduct($where,'R',$perPage,$offset,$order_by);
    // echo $this->db->last_query();die;
    if($products){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($products)).' of '.$countdata.' entries';
          $edit_product = checkLabelByTask('edit_product');
         foreach ($products as $row){
          if($edit_product){
             if($row->status == 0){
              $Status = '<a onclick="updateStatusBox('.$row->product_id.','.$row->status.',\''.$row->product_name.'\',\'product/changestatusforProduct\')" href="javascript:void(0)">Activate</a>';   
             }else{
               $Status = '<a onclick="updateStatusBox('.$row->product_id.','.$row->status.',\''.$row->product_name.'\',\'product/changestatusforProduct\')" href="javascript:void(0)">Deactivate</a>';   
               
               $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Product\',\'product/addnewproduct\','.$row->product_id.',\'\',\'70%\');" >Edit</a>';

             }
          }
        $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
      
        $returnArr .= "<tr>
                   <td>".$row->item_no."</td>
                   <td>".ucfirst($row->product_name)."</td>
                   <td>".ucfirst($row->category_name)."</td>
                   <td>".strtoupper($row->unit)."</td>
                   <td>".$row->calories."</td>
                   <td>".$row->protein."</td>
                   <td>".$row->fat."</td>
                   <td>".$row->saturated_fat."</td>
                   <td>".$row->cholesterol."</td>
                   <td>".$row->sodium."</td>
                   <td>".$row->potassium."</td>
                   <td>".$row->carbohydrates."</td>
                   <td>".$row->iron."</td>
                   <td>".$row->calcium."</td>
                   <td>".$status."</td>";  
          $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$delete.'</li>
                                </ul>
                                </div></td> </tr>';
          
         }
         if($countdata <=5){
            $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
           $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
           $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
           $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";

           $returnArr .= "<tr><td width='3%'><br/></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";

         }
         $pagination = $pages->get_links();
     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  }
  
  function addnewproduct(){
    checkUserSession();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    if($action=='save'){
    if($this->addproductvalidation()){
      extract($this->input->post());
      $dataArr = array('product_name'=>$product_name,'product_category_id'=>$product_category_id,'item_no'=>$item_no,'unit'=>strtoupper($unit));
      $dataArr2 = array('calories'=>$calories,'protein'=>$protein,'fat'=>$fat,'saturated_fat'=>$saturated_fat,'cholesterol'=>$cholesterol,'sodium'=>$sodium,'potassium'=>$potassium,'carbohydrates'=>$carbohydrates,'iron'=>$iron,'calcium'=>$calcium);
      if($id == ''){
            $dataArr['added_on']=date('Y-m-d H:i:s');
            $product_id = $this->mp->addproduct('product',$dataArr);
            $dataArr2['product_id'] = $product_id;
            $this->mp->addproduct('nutritional',$dataArr2);
            $this->session->set_flashdata('succMsg','Product added successfully.');
            $returnArr['status'] = '101';
      }else{
        $this->mp->editproduct('product',$dataArr,$id);
        $this->mp->editproduct('nutritional',$dataArr2,$id);
        
        $this->session->set_flashdata('succMsg','Product updated successfully.');
        $returnArr['status'] = '102';
       }
     }
    }

   
    $vars['products_category'] = $this->mp->getAllProductCategory(' And pc.status=1 ','R');
    if(!empty($id)){
      $where = "And p.product_id = ".$id;
      $data = $this->mp->getAllProductbyid($where);
      // echo $this->db->last_query();die;
      $vars['dataArr'] = get_object_vars($data);
    }else{
     $vars['dataArr'] = $this->input->post();
    }

    $vars['active'] = 'PD';
    $data = $this->load->view('addProduct',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);   
  }

   function addEditproductCategory(){
     checkUserSession();
     $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    if($action=='save'){
    if($this->addCategoryvalidation()){
      extract($this->input->post());
      $product_group_id = ($product_group_id) ? $product_group_id : null;
      $sequence = ($sequence) ? $sequence : 1;
      $dataArr = array('category_name'=>$category_name,'sequence'=>$sequence,'product_group_id'=>$product_group_id);
      if($id == ''){
        $dataArr['parent_category_id'] = 31; 
        $dataArr['added_on']=date('Y-m-d H:i:s');
        $dataArr['added_by']=$user_session_data->user_id;
          $this->mp->addproductCategory('product_category',$dataArr);
          $this->session->set_flashdata('succMsg','Product Category added successfully.');
          $returnArr['status'] = '101';
      }else{
          $this->mp->editproductCategory('product_category',$dataArr,$id);
          $this->session->set_flashdata('succMsg','Product Category updated successfully.');
          $returnArr['status'] = '102';
      }
    }
    }
    if(!empty($id)){
     $where = "And pc.product_category_id = ".$id;
     $data = $this->mp->getAllProductCategorybyid($where);
     $vars['dataArr'] = get_object_vars($data);
    }else{
     $vars['dataArr'] = $this->input->post();
    }
     $vars['products_group'] = $this->mp->getAllProductGroup(' AND pg.status = 1','R');
    $vars['active'] = 'PC';
    $data = $this->load->view('add_edit_product_category',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);    
   }

   function addCategoryvalidation(){
    $this->form_validation->set_rules('category_name','Category Name','trim|required');
     return $this->form_validation->run();
   }

  function addproductvalidation(){
    $this->form_validation->set_rules('product_name','Product Name','trim|required|callback_checkProductName');
    $this->form_validation->set_rules('unit','Unit','trim|required');
    $this->form_validation->set_rules('item_no','Item No','trim|required');
    $this->form_validation->set_rules('product_category_id','Product Category','trim|required');
     return $this->form_validation->run();
  }

  function checkProductName(){ 
        $where = '';
        $product_id = $this->input->post('id');
        $product_name = $this->input->post('product_name');
        if(!empty($product_id)){
            $where = " AND p.product_id != '".$product_id."' AND p.product_name = '".$product_name."' ";
        }else{
            $where = " AND p.product_name = '".$product_name."' ";
        }
        $data = $this->mp->getAllProduct($where, 'R');
         if (!empty($data)){
            $this->form_validation->set_message('checkProductName', 'Product Name already exist.');
            return false;                  
        }else{
          return true;                
        }
  }


  function deleteproduct(){
    $id = $this->input->post('id');
    $this->mp->deleteproductById($id);
    $this->session->set_flashdata('succMsg','Product deleted successfully.');   
   }


   function changestatusforProduct(){
   $id = $this->input->post('id');
   $status = $this->input->post('status');
   $status = ($status== '1' )? '0' :'1';
   $where = 'product_id ='.$id;
   $this->mp->changePCstatus($where,$status,$id,'product');
   $this->session->set_flashdata('succMsg','Product status changed successfully');     
   }
  
  function getProductCategoryByID($product_category_id=''){
     $id = $this->input->post('id');
      if(!empty($id)){
         $where = ' And pc.status=1 AND pc.parent_category_id='.$id;
         $products_category = $this->mp->getAllProductCategory($where,'R');        
        }
      $data = '<option value="">Select Category</option>';
     if($products_category){
        foreach ($products_category as $row) {
         $selected = ($row->product_category_id==$product_category_id) ? 'selected' :'';   
         $data .= '<option '.$selected.' value="'.$row->product_category_id.'">'.ucfirst($row->category_name).'</option>';
        }
     }
     echo json_encode(array('data'=>$data));
   }

  function productGroup(){
    checkUserSession();
    $user_session_data = getSessionData(); 
    $vars['user_session_data'] = $user_session_data;   
    $vars['active'] = 'PI';
    $vars['heading'] = 'Product Group';
    $vars['content_view'] = 'product_group_list';
    $this->load->view('layout',$vars);        
  }

  function getAllProductGroupList(){
       checkUserSession();
   $user_session_data = getSessionData();
   $where = '';
   $returnArr = '';
   $order_by = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    if(!empty($keyword)){
     $where .= " AND ( pg.name like '%".trim($keyword)."%' or pg.code like '%".trim($keyword)."%')";   
    }
        if(!empty($status)){
            $status = trim($status);
            if ($status == 'A'){
                $where .= " AND pg.`status`='1' ";
            }elseif ($status == 'D'){
                $where .= " AND pg.`status`='0' ";              
            } 
        }

        if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Name'){
                $order_by = 'ORDER BY pg.name '.$sort_type;
            }elseif($sort_column == 'Code'){
                $order_by = 'ORDER BY pg.code '.$sort_type;
            }
            elseif($sort_column == 'Per Man Per Day'){
                $order_by = 'ORDER BY pg.consumed_qty '.$sort_type;
            }
            elseif($sort_column == 'Status'){
                $order_by = 'ORDER BY pg.status '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY pg.group_name ASC';
        }

        if($download==1){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'GroupList.xlsx';
           $arrayHeaderData= array('Group Name','Group Code','Per Man Per Day','Status');
            $listColumn     = array();
           $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'A1:A1','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'B2:E5','font'=>array(),'alignment'=>$align)));
            $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'E1:E1','font'=>array(),'alignment'=>$align)));

            $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:25','B:25','C:30','D:18','E:18','F:18','G:18','H:18','I:18','J:18','K:25','L:25')); 
           
            $listColumn[] = array('addImage'=>'1','coordinates'=>'A2','height'=>90);
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                        'color' => array('rgb' => '4F2270'),
                        'size'  => 40,
                        'name'  => 'Calibri'
                          )
                        ),'cellArray'=>array('B2'));
            $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:D7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7;
           $products = $this->mp->getAllProductGroup($where,'R','','',$order_by);
           if($products){
                foreach ($products as $row) {
                   $k++;
                     $status = ($row->status==1) ? 'Activate' : 'Deactivate';  
                   $arrayData[] = array(ucwords($row->name),$row->code,$row->consumed_qty,$status);
                }
           }

          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:D'.$k,'border'=>'THIN')) 
                  );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'GroupList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   
        }

         $countdata = $this->mp->getAllProductGroup($where,'C');
         $offset = ($cur_page * $perPage) - $perPage;
         $pages = new Paginator($countdata,$perPage,$cur_page);
         $products = $this->mp->getAllProductGroup($where,'R',$perPage,$offset,$order_by);
         if($products){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($products)).' of '.$countdata.' entries';
          $edit_product_group = checkLabelByTask('edit_product_group');
         foreach ($products as $row){
            if($edit_product_group){
             if($row->status == 0){
                $Status = '<a onclick="updateStatusBox('.$row->product_group_id.','.$row->status.',\''.$row->name.'\',\'product/changestatusforGroup\')" href="javascript:void(0)">Activate</a>';   
             }else{
                $Status = '<a onclick="updateStatusBox('.$row->product_group_id.','.$row->status.',\''.$row->name.'\',\'product/changestatusforGroup\')" href="javascript:void(0)">Deactivate</a>';      
             }
                
           
        $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Product Group\',\'product/addEditproductGroup\','.$row->product_group_id.',\'\',\'70%\');" >Edit</a>';
        }
       $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
          $returnArr .= "<tr>
                        <td width='30%'>".ucfirst($row->name)."</td>
                        <td width='30%'>".ucfirst($row->code)."</td>
                        <td width='30%'>".$row->consumed_qty."</td>
                        <td width='10%'>".$status."</td>";
              $returnArr .= '<td style="display:none;" width="4%" style="text-align:center;"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                </ul>
                                </div></td> </tr>';
         } 
         $pagination = $pages->get_links();
     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  }
  
  function changestatusforGroup(){
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $status = ($status== '1' )? '0' :'1';
    $where = 'product_group_id ='.$id;
    $this->mp->changePCstatus($where,$status,$id,'product_group');
     $this->session->set_flashdata('succMsg','Group status changed successfully');     
   }
  
   function addEditproductGroup(){
     checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    if($action=='save'){
    if($this->addGroupvalidation()){
      extract($this->input->post());
      $dataArr = array('name'=>$name,'code'=>$code,'consumed_qty'=>$consumed_qty);
      if($id == ''){
        $dataArr['added_on']=date('Y-m-d H:i:s');
        $dataArr['added_by']=$user_session_data->user_id;
          $this->mp->addproductCategory('product_group',$dataArr);
          $this->session->set_flashdata('succMsg','Product Group added successfully.');
          $returnArr['status'] = '101';
      }else{
          $this->mp->editproductGroup('product_group',$dataArr,$id);
          $this->session->set_flashdata('succMsg','Product Group updated successfully.');
          $returnArr['status'] = '102';
      }
    }
    }
    if(!empty($id)){
     $where = "And pg.product_group_id = ".$id;
     $data = $this->mp->getGroupDetailsByID($where);
     $vars['dataArr'] = get_object_vars($data);
    }else{
     $vars['dataArr'] = $this->input->post();
    }

    $vars['active'] = 'PI';
    $data = $this->load->view('add_edit_product_group',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);  

   }

   function addGroupvalidation(){
      $this->form_validation->set_rules('name','Group Name','trim|required');
      $this->form_validation->set_rules('code','Group Code','trim|required');
      return $this->form_validation->run();
   }

   function getProductsBySearch(){
    checkUserSession();
    $user_session_data = getSessionData();

    $search = $_REQUEST['search'];
    $where = ' and p.is_custom_product = 0 and p.status = 1';
    if(!empty($search)){
        $where .= ' and p.product_name like "%'.$search.'%"';
    }
    $productData = $this->mp->getAllProduct($where,'R','','','order by p.product_name ASC');
    $total_count = $this->mp->getAllProduct($where,'C');
    $productArr = array();
    if(!empty($productData)){
        foreach($productData as $product){
        $productArr[] = array('id'=>$product->product_id,'title'=>$product->product_name.'('.$product->item_no.')','text'=>$product->product_name.'('.$product->item_no.')');
        }

    $return= json_encode(array('results'=>$productArr,'pagination'=>array("more"=> true),'total_count'=>$total_count));             
    }
    echo $return;
}

  function addProductOnInventory(){
     checkUserSession();
     $user_session_data = getSessionData();
     $actionType = $this->input->post('actionType');
     $returnArr['status'] = 100;
     if($actionType=='save'){

     }
     $data = $this->load->view('addProductOnInventory',$vars,true);
     $returnArr['data'] = $data;
     echo json_encode($returnArr);
  }

 }
?>