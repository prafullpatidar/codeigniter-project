<?php
 class Shipping extends CI_Controller{
  function __construct(){
      parent::__construct();

      $this->load->model('Company_manager');
      $this->cm = $this->Company_manager;
      
      $this->load->library('querybundel');
      $this->qb = $this->querybundel;

      $this->load->model('manage_product');
      $this->mp = $this->manage_product;
      
      $this->load->model('User_manager');
      $this->um = $this->User_manager;
  }
  
  function index(){
    checkUserSession();
    $user_session_data = getSessionData();    
    $vars['user_session_data'] = $user_session_data;   
    $vars['active'] = 'CL';
    $vars['heading'] = 'Shipping Company';
    $vars['content_view'] = 'company_list';
    $this->load->view('layout',$vars);   
  }

  function getAllshippingCompany(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   
    if(!empty($keyword)){
     $where .= " AND ( c.name like '%".trim($keyword)."%' or  c.customer_id like '%".trim($keyword)."%' or c.address like '%".trim($keyword)."%' or c.country like '%".trim($keyword)."%' or c.state like '%".trim($keyword)."%' or c.city like '%".trim($keyword)."%' or c.zip like '%".trim($keyword)."%') ";   
    }

    if($created_on){
     $where .= ' AND (date(c.added_on) = "'.convertDate($created_on,'','Y-m-d').'") '; 
     } 

    if(!empty($status)){
            $status = trim($status);
            if ($status == 'A')
            {
                $where .= " AND c.`status`= 1 ";
            }
            elseif ($status == 'D')
            {
                $where .= " AND c.`status`= 0 ";              
            } 
        }

        if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Customer'){
                $order_by = 'ORDER BY c.name '.$sort_type;
            }
            elseif($sort_column == 'Country'){
                $order_by = 'ORDER BY c.country '.$sort_type;
            }
            elseif($sort_column == 'State'){
                $order_by = 'ORDER BY c.state '.$sort_type;
            }
            elseif($sort_column == 'City'){
                $order_by = 'ORDER BY c.city '.$sort_type;
            }
            elseif($sort_column == 'Zip'){
                $order_by = 'ORDER BY c.zip '.$sort_type;
            }
            elseif($sort_column == 'Address'){
                $order_by = 'ORDER BY c.address '.$sort_type;
            }
            elseif($sort_column == 'Customer Id'){
                $order_by = 'ORDER BY c.customer_id '.$sort_type;
            }
            elseif($sort_column == 'Date'){
                $order_by = 'ORDER BY c.added_on '.$sort_type;
            }
            elseif($sort_column == 'Phone'){
                $order_by = 'ORDER BY c.phone '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY c.name ASC';
        }

   if($download==1){
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'CompanyList.xlsx';
           $arrayHeaderData= array('Name','Customer ID','Country','State','City','Zipcode','Address','Status');
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
                    ),'cellArray'=>array('A7:H7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
       $k = 7;
       $company = $this->cm->getAllshippingCompany($where,'R','','',$order_by);
       if($company){
            foreach ($company as $row) {
               $k++;
               $status = ($row->status==1) ? 'Activate' : 'Deactivate';   
               $arrayData[] = array(ucwords($row->name),$row->customer_id,ucwords($row->country),ucwords($row->state),ucwords($row->city),ucwords($row->zip),$row->address,$status); 
            }
       }
      $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN')) 
              );   
       $arrayBundleData['listColumn'] = $listColumn;
       $arrayBundleData['arrayData'] = $arrayData;
       $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'CompanyList');
       readfile(FCPATH.'uploads/sheets/'.$fileName);
       unlink(FCPATH.'uploads/sheets/'.$fileName);
       exit;     
   }     

   $countdata = $this->cm->getAllshippingCompany($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page);
   $customer = $this->cm->getAllshippingCompany($where,'R',$perPage,$offset,$order_by);
   //echo $this->db->last_query(); die;
   if($customer){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($customer)).' of '.$countdata.' entries';
         foreach ($customer as $row){
            $edit_company = checkLabelByTask('edit_company');
            $company_360_view = checkLabelByTask('company_360_view');
            if($edit_company){
             if($row->status == 0){
                $Status = '<a onclick="updateStatusBox('.$row->shipping_company_id.','.$row->status.',\''.$row->name.'\',\'shipping/changestatuscompany\')" href="javascript:void(0)">Activate</a>';   
             }else{
               $Status = '<a onclick="updateStatusBox('.$row->shipping_company_id.','.$row->status.',\''.$row->name.'\',\'shipping/changestatuscompany\')" href="javascript:void(0)">Deactivate</a>';
               $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Shipping Company\',\'shipping/add_edit_company\','.$row->shipping_company_id.',\'\',\'70%\');" >Edit</a>';       
             }
                  
             
            }
            
            if($company_360_view && $row->status == 1){ 
              $view = '<a href="'.base_url().'shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'">360 View</a>';
            }

           // $delete = '<a href="javascript:void(0);" onclick="updateStatusBoxDelete('.$row->shipping_company_id.',\'0\',\''.$row->name.'\',\'shipping/deleteCompany\');" >Delete</a>';
            
            $image =  (!empty($row->logo)) ? "<img class='list-img' src=".base_url()."uploads/company/".$row->logo." width='50px' height='50px'>": "<img class='list-img' src=".base_url("uploads/customer.png")." width='50px' height='50px'>" ;
            $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
            $returnArr .= "<tr><td width='5%'>".$image."</td>
                              <td  width='20%'>".(($row->status==1 && $company_360_view) ? "<a style='text-decoration:none;' href='".base_url().'shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id)."''><strong>".ucfirst($row->name)."</strong></a>" : "<a style='text-decoration:none;' href='javascript:void(0)'>".ucfirst($row->name)."</a>")."</td>
                              <td width='10%'>".$row->customer_id."</td>
                              <td width='10%'>".ucfirst($row->country)."</td>
                              <td width='10%'>".ucfirst($row->state)."</td>
                              <td width='10%'>".ucfirst($row->city)."</td>
                              <td width='10%'>".ucfirst($row->zip)."</td>
                              <td width='15%'>".ucfirst($row->address)."</td>
                              <td width='8%'>".$status."</td>";
                $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li>'.$view.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                 <li>'.$Status.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }
         if($countdata <= 5){
            $returnArr .= "<tr><td width='5%'><br></td><td width='23%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='2%'></td></tr>";
            $returnArr .= "<tr><td width='5%'><br></td><td width='23%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='2%'></td></tr>";
            $returnArr .= "<tr><td width='5%'><br></td><td width='23%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='2%'></td></tr>";
            $returnArr .= "<tr><td width='5%'><br></td><td width='23%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='2%'></td></tr>";
            $returnArr .= "<tr><td width='5%'><br></td><td width='23%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='2%'></td></tr>";

         }  
         $pagination = $pages->get_links();
     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="10" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));    
  }

  function manageShips(){
   checkUserSession();
   $user_session_data = getSessionData();    
   $vars['user_session_data'] = $user_session_data;   
   $vars['active'] = 'SP';
   $vars['heading'] = 'Ships';
   $vars['content_view'] = 'ships_list';
   $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status =1 ','R');

   $this->load->view('layout',$vars);   
  }

  function getallshipList(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = '';$order_by='';
   $returnArr = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    
      if(!empty($status)){
           if($status == 'A'){
             $where .= " AND ( s.status = 1)";
              }elseif($status == 'D'){
                 $where .= " AND ( s.status = 0)";
              }
         }

         if($type){
          $where .= ' AND (s.ship_type = '.$type.')';  
         }

      if(!empty($shipping_company_id)){
           $where .= ' AND (s.shipping_company_id='.$shipping_company_id.')';
         }

       if($created_on){
         $where .= ' AND (date(s.added_on) = "'.convertDate($created_on,'','Y-m-d').'") '; 
        } 

        if(!empty($keyword)){
          $where .= " AND (s.ship_name like '%".trim($keyword)."%' or s.imo_no like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";   
        } 
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Ship Name')
            {
                $order_by = 'ORDER BY s.ship_name '.$sort_type;
            }
            elseif($sort_column == 'Ship Code')
            {
                $order_by = 'ORDER BY s.imo_no '.$sort_type;
            }
            elseif($sort_column == 'CreatedDate')
            {
                $order_by = 'ORDER BY s.added_on '.$sort_type;
            }
            elseif($sort_column == 'Shipping Company')
            {
                $order_by = 'ORDER BY sc.name '.$sort_type;
            }
            elseif($sort_column == 'Type')
            {
                $order_by = 'ORDER BY s.ship_type '.$sort_type;
            }
            elseif($sort_column == 'AddedBy')
            {
                $order_by = 'ORDER BY user_name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY s.ship_name ASC';
        }

        if($download){
            $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'VesselList.xlsx';
           $arrayHeaderData= array('Vessel Name','IMO No','Shipping Company','Type','Created On','Created By','Status');
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
                    ),'cellArray'=>array('A7:G7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData; 
           $ships = $this->cm->getAllShips($where,'R','','',$order_by);
           $k = 7; 
           if(!empty($ships)){
                foreach ($ships as $row) {
                   $k++;
                    $type = ($row->ship_type=='1') ? 'Contracted' : 'Non Contracted';
                    $arrayData[] = array(ucwords($row->ship_name),$row->imo_no,ucwords($row->shipping_company_name),$type,ConvertDate($row->added_on,'','d-m-Y | h:i A'),ucwords($row->user),(($row->status==1) ? 'Activate' : 'Deactivate'));  
            }
          }
          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:G'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'VesselList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
        }

        $countdata = $this->cm->getAllShips($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $ships = $this->cm->getAllShips($where,'R',$perPage,$offset,$order_by);
        if($ships){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($ships)).' of '.$countdata.' entries';
           $edit_ship = checkLabelByTask('edit_ship');
           $manage_crew_member = checkLabelByTask('manage_crew_member'); 
         foreach ($ships as $row){
           if($edit_ship){ 
                 if($row->status == 0){
                  $Status = '<a onclick="updateStatusBox('.$row->ship_id.','.$row->status.',\''.$row->ship_name.'\',\'shipping/changestatusShips\')" href="javascript:void(0)">Activate</a>';   
                 }else{
                  $Status = '<a onclick="updateStatusBox('.$row->ship_id.','.$row->status.',\''.$row->ship_name.'\',\'shipping/changestatusShips\')" href="javascript:void(0)">Deactivate</a>';      
                 }
                  
              $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Vessel\',\'shipping/add_edit_ships\','.$row->ship_id.','.$row->shipping_company_id.',\'70%\');" >Edit</a>';
              // $import_crew_members = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Import Crew Members\',\'shipping/import_crew_members\','.$row->ship_id.',\'\',\'70%\');" >Import Crew Members</a>';
              
              // $view_crew_food_habits = '<a href="'.base_url().'shipping/crewFoodHabitList/'.$row->ship_id.'" target="_blank">View Food Habits</a>';
            }

            if($manage_crew_member){
                // $crew_members = '<a href="'.base_url().'shipping/crewEnteriesList/'.base64_encode($row->ship_id).'" target="_blank">Crew Members List</a>';

                $crew_members = '<a href="'.base_url().'crew/index/'.base64_encode($row->ship_id).'" target="_blank">Crew Members List</a>';
            }

            $type = ($row->ship_type=='1') ? 'Contracted' : 'Non Contracted';


    
              $returnArr .= "<tr>
                   <td width='10%'>".ucfirst($row->ship_name)."</td><td width='10%'>".$row->imo_no."</td><td width='10%'>".ucwords($row->shipping_company_name)."</td><td width='10%'>".$type."</td><td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | H:i A')."</td><td width='10%'>".ucfirst($row->user)."</td><td width='10%'>".(($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>')."</td>";  
             $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$crew_members.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }  
         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));    
  }


  function add_edit_company(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');

    if($action=='save'){
    if($this->validate_company()){
      extract($this->input->post());
      $date = date('Y-m-d H:i:s');
      $dataArr = array('name'=>$name,'country'=>$country,'state'=>$state,'city'=>$city,'zip'=>$zip,'address'=>$address,'email'=>$email,'phone'=>$phone,'payment_term'=>$payment_term,'customer_id'=>$customer_id);
      if(!empty($_FILES['img']['name'])){
          $file_name = $_FILES['img']['name'];
          $upload_data = doc_upload($file_name,'company');
          // print_r($upload_data);die;
         $dataArr['logo'] = $upload_data['file_name'];  
      }
      if($id == ''){
       // $count_company = $this->cm->getAllshippingCompany('','C');
       // $dataArr['customer_id'] = (340364 + $count_company);
       $dataArr['added_on'] = $date;
       $dataArr['added_by'] = $user_session_data->user_id;
       $this->cm->addcompany($dataArr);
       $this->session->set_flashdata('succMsg','New Company Added successfully.');
       $returnArr['status'] = '101';
      }else{
       $where = 'shipping_company_id = '.$id;    
       $this->cm->editcompany($dataArr,$where);
       $this->session->set_flashdata('succMsg','Company updated successfully.');
       $returnArr['status'] = '102';
      }
     }
    }

    if(!empty($id)){
     $where = "And c.shipping_company_id = ".$id;
     $data = $this->cm->getAllComapnyById($where);
     $vars['dataArr'] = get_object_vars($data);
    }else{
     $vars['dataArr'] = $this->input->post();
    }

    $data = $this->load->view('add_edit_company',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);             
  }

  function validate_company(){
     $this->form_validation->set_rules('name', 'Company Name', 'trim|required');
     $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
     $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
     $this->form_validation->set_rules('state', 'State', 'trim|required');
     $this->form_validation->set_rules('country', 'Country', 'trim|required');
     $this->form_validation->set_rules('state', 'State', 'trim|required');
     $this->form_validation->set_rules('city', 'City', 'trim|required');
     $this->form_validation->set_rules('zip', 'Zipcode', 'trim|required');
     $this->form_validation->set_rules('address', 'Address', 'trim|required');
     $this->form_validation->set_rules('payment_term','Payment Term','trim|required');
    if(empty($this->input->post('id'))){ 
     $this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required|is_unique[shipping_company.customer_id]');
     }
     else{
       $this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required|callback_check_custom_id');
       $this->form_validation->set_message('check_custom_id','The Customer ID field must contain a unique value.');
     }
     return $this->form_validation->run();   
  }

  function check_custom_id(){
    $customer_id = trim($this->input->post('customer_id'));
    $shipping_company_id = trim($this->input->post('id'));
    $shipping_company = $this->cm->getAllshippingCompany(' And c.shipping_company_id !='.$shipping_company_id,'R');
    $flag = true;
    if(!empty($shipping_company)){
      foreach ($shipping_company as $row) {
         if($row->customer_id==$customer_id){
           $flag = false;  
         }
      }
    }
    return $flag;
  }

    function changestatuscompany(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $where = 'shipping_company_id ='.$id;
        $status = ($status== '1' )? '0' :'1';
        $this->cm->changestatus('shipping_company',$status,$where);
        $this->session->set_flashdata('succMsg','Company status changed successfully.');  
    }

    function changestatusShips(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $where = 'ship_id ='.$id;
        $status = ($status== '1' )? '0' :'1';
        $this->cm->changestatus('ships',$status,$where);
        $this->session->set_flashdata('succMsg','Ship status changed successfully.');  
    }

    function deleteCompany(){
        $id = $this->input->post('id');
        $where = 'shipping_company_id ='.$id;
        $result = $this->cm->deleteCompany('shipping_company',$where);
        $this->session->set_flashdata('succMsg','Company Deleted successfully.');
    }
   
  function add_edit_ships(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    $second_id = $this->input->post('second_id');
    extract($this->input->post());
    if($action=='save'){
       if($this->validate_ship()){
         $error = true;
         $cook_id = trim($this->input->post('cook_user_id'));
         $captain_id = trim($this->input->post('captain_user_id'));        
      
        if(!empty($cook_id) || !empty($captain_id)){  
            if($this->input->post('unlink')!='No'){
               if($id==''){
                  $data = $this->cm->getCaptainAndCook();   
               }
               else{
                  $data = $this->cm->getCaptainAndCook(' AND s.ship_id !='.$id);   
               }

                $assignedCaptains = array();
                $assignedCooks = array();
                if(!empty($data)){
                    foreach($data as $row){
                        if(!empty($row->captain_user_id)){    
                          $assignedCaptains[] = $row->captain_user_id;
                        }
                        if(!empty($row->cook_user_id)){    
                           $assignedCooks[] = $row->cook_user_id;
                        }
                    }
                 }

               if(in_array($cook_id,$assignedCooks)){
                 $cook_data = $this->cm->getCaptainAndCook(' AND s.cook_user_id ='.$cook_id);
                 $error = false;
                 $returnArr['cookmsg'] = 'The '.ucwords($cook_data[0]->cook_name).'(Cook) Link With The Vessel '.ucwords($cook_data[0]->ship_name);   
               }
            
               if(in_array($captain_id,$assignedCaptains)){
                 $captain_data = $this->cm->getCaptainAndCook(' AND s.captain_user_id ='.$captain_id);
                 $error = false;
                 $returnArr['captainmsg'] = 'The '.ucwords($captain_data[0]->captain_name).'(Captain) Link With The Vessel '.ucwords($captain_data[0]->ship_name);    
                }
            }
     }

      if($error){
        $date = date('Y-m-d H:i:s');

        

        $dataArr = array('ship_name'=>$ship_name,'imo_no'=>$imo_no,'shipping_company_id'=>$shipping_company_id,'captain_nationality'=>$captain_nationality,'cook_nationality'=>$cook_nationality,'total_members'=>$total_members,'trading_area'=>$trading_area,'victualling_rate'=>$victualling_rate,'ship_type'=>$ship_type);

          if(!empty($captain_user_id)){
           $dataArr['captain_user_id'] = $captain_user_id;
          }

          if(!empty($cook_user_id)){
           $dataArr['cook_user_id'] = $cook_user_id;
          }

         if($this->input->post('unlink')=='No'){
            $this->db->update('ships',array('cook_user_id'=>null),array('cook_user_id'=>$cook_user_id));
            $this->db->update('ships',array('captain_user_id'=>null),array('captain_user_id'=>$captain_user_id));
         }
          
         $batch = array(); 
         $batch2 = array(); 

         if($id == ''){
                $dataArr['added_on'] = $date;
                $dataArr['added_by'] = $user_session_data->user_id;
                $last_ship_id = $this->cm->addShips($dataArr);
                // echo $this->db->last_query();die;
                 /*Add Product Category Mapping to Ship*/
                 if(!empty($product_category)){
                    // $this->db->delete('shipwise_category',array('ship_id'=>$last_ship_id));
                    foreach($product_category as $cat){
                        $batch[] = array('ship_id'=>$last_ship_id,'product_category_id'=>$cat);
                    }
                 }

                 if(!empty($product)){
                    foreach($product as $pro){
                        $batch2[] = array('ship_id'=>$last_ship_id,'product_id'=>$pro);
                    }
                 }
                 $this->db->insert_batch('shipwise_category',$batch);
                 /*Add Product Category Mapping to Ship*/
                 $this->db->insert_batch('shipwise_misc_product',$batch2);
                 
                 $this->session->set_flashdata('succMsg','New Ship Added successfully.');
                 $returnArr['status'] = '101';
              }else{
                $where = 'ship_id = '.$id;    
                $this->cm->editShips($dataArr,$where);
                $this->session->set_flashdata('succMsg','Ship updated successfully.');
                /*Add Product Category Mapping to Ship*/
                  $this->db->delete('shipwise_category',array('ship_id'=>$id));
                  $this->db->delete('shipwise_misc_product',array('ship_id'=>$id));
                 if(!empty($product_category)){ 
                    foreach($product_category as $cat){
                        $batch[] = array('ship_id'=>$id,'product_category_id'=>$cat);
                    }
                 }

                 if(!empty($product)){
                    foreach($product as $pro){
                        $batch2[] = array('ship_id'=>$id,'product_id'=>$pro);
                    }
                 }
                  
                 //  echo '<pre>';
                 // print_r($batch2);die;  
                 $this->db->insert_batch('shipwise_category',$batch);
                 /*Add Product Category Mapping to Ship*/
                 $this->db->insert_batch('shipwise_misc_product',$batch2);
                
                $returnArr['status'] = '102';
              }
          }
          else{
          $returnArr['status'] = 200;
         }
       }
     }

    if(!empty($id)){
      $where = " And s.ship_id = ".$id;
      $data = $this->cm->getAllShipsById($where);
      // echo $this->db->last_query();die;
    }
        
    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : get_object_vars($data);
    $vars['ship_id'] = $id;
    $vars['second_id'] = $second_id;

    $shipping_company_id = ($this->input->post('shipping_company_id')) ? $this->input->post('shipping_company_id') : $second_id;  
   
    if(!empty($shipping_company_id)){         
      $vars['captain'] = $this->um->getalluserlist(' AND r.code = "captain" AND u.shipping_company_id ='.$shipping_company_id,'R');
      $vars['cook'] = $this->um->getalluserlist(' AND r.code = "cook" AND u.shipping_company_id ='.$shipping_company_id,'R');
    }  

    $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
    $vars['misc_items'] = $this->mp->getAllProduct(' and p.status = 1 and pc.code="misc_items"','R');
    $vars['product_categories'] = $this->mp->getAllProductCategory(' AND pc.parent_category_id IS NOT NULL AND pc.status =1 ','R');
    $data = $this->load->view('add_edit_ships',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);             
  }
  
  function validate_ship(){
     $this->form_validation->set_rules('ship_name', 'Vessel Name', 'trim|required');
     if(empty($this->input->post('id'))){
       $this->form_validation->set_rules('imo_no', 'Imo No', 'trim|required|is_unique[ships.imo_no]');
     }
     else{
       $this->form_validation->set_rules('imo_no', 'Imo No', 'trim|required|callback_check_imo');
     }

     $this->form_validation->set_rules('shipping_company_id', 'Shipping Company', 'trim|required');
     
    // $this->form_validation->set_rules('captain_user_id', 'Ship Captain', 'trim|required');
    if(!empty($this->input->post('captain_user_id'))){
      $this->form_validation->set_rules('captain_nationality', 'Captain Nationality', 'trim|required');
    }

    if(!empty($this->input->post('cook_user_id'))){
      $this->form_validation->set_rules('cook_nationality', 'Cook Nationality', 'trim|required');
    }

     // $this->form_validation->set_rules('cook_user_id', 'Ship Cook', 'trim|required');
     
     $this->form_validation->set_rules('total_members', 'Total Members', 'trim|required');
     $this->form_validation->set_rules('victualling_rate', 'Victualling Rate', 'trim|required');
     $this->form_validation->set_rules('trading_area', 'Trading Area', 'trim|required');
     $this->form_validation->set_rules('ship_type', 'Vessel type', 'trim|required');
     $this->form_validation->set_rules('product_category[]', 'Product Category', 'trim|required');
     if($this->input->post('product_validation')){
       $this->form_validation->set_rules('product[]', 'Product', 'trim|required');
     }
     return $this->form_validation->run();

  }
  

   function check_imo(){
     $id =  ($this->input->post('id')) ? trim($this->input->post('id')) :trim($this->input->post('ship_id'));
     $imo_no = trim($this->input->post('imo_no'));
     $ships = $this->cm->getAllShips(' AND s.ship_id !='.$id,'R');
     $validation = true;
     if(!empty($ships)){
       foreach ($ships as $row) {
         if($row->imo_no==$imo_no){
          $validation = false;
           $this->form_validation->set_message('check_imo','This imo no already exists in system');  
         }
        }  
     }
    return $validation;    
   }


   function shippingCompanyDetails($shipping_company_id=''){
      checkUserSession();
      $user_session_data = getSessionData();
      // if($user_session_data->code=='captain' || $user_session_data->code=='cook' || $user_session_data->code=='shipping_company'){
      //   $vars['active'] = 'DASH';
      // }
      // else{
        $vars['active'] = '360C';        
      // }
      $vars['shipping_company'] = $shipping_company = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
      if(!empty($shipping_company_id)){
          $vars['companyData'] = (array) $this->cm->getAllComapnyById(' And c.shipping_company_id = '.base64_decode($shipping_company_id));
          $vars['shipping_company_id'] = $shipping_company_id = base64_decode($shipping_company_id);     
      }
     
      if($user_session_data->code=='captain'){
         $where = 'AND s.shipping_company_id ='.$shipping_company_id. ' and s.captain_user_id = '.$user_session_data->user_id;
      }
      elseif($user_session_data->code=='cook'){
         $where = 'AND s.shipping_company_id ='.$shipping_company_id. ' and s.cook_user_id = '.$user_session_data->user_id;
      }
      else{
          $where = ' and s.shipping_company_id = '.$shipping_company_id;
      }  

      if(!empty($shipping_company_id)){
       $shipData = $this->cm->getAllShips(' AND s.status = 1 '.$where,'R');
      }

      $vars['shipId'] = $shipData[0]->ship_id;     
      $vars['heading'] = '360 View of Shipping Company';
      $vars['content_view'] = '360_view_company';
      $this->load->view('layout',$vars);

   }
    
   function getAllShips360($shipping_company_id=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $shipping_company_id = base64_decode($shipping_company_id);
     $returnArr = '';
     $keyword = $this->input->post('keyword');
     $page = $this->input->post('page');
     if(!empty($shipping_company_id)){
       $where = ' And (s.shipping_company_id = '.$shipping_company_id.') AND (s.status = 1)';
        
      if(!empty($keyword)){
        $where .= ' AND (s.ship_name like "%'.trim($keyword).'%" or s.imo_no like "%'.trim($keyword).'%" )';
      }
      $shipwhere='';
      if($user_session_data->code == 'captain' || $user_session_data->code == 'cook'){
         $where .= ' and s.shipping_company_id = '.$user_session_data->shipping_company_id.' AND (s.captain_user_id = '.$user_session_data->user_id.' OR s.cook_user_id = '.$user_session_data->user_id.')';
       }
      
      $cur_page = ($page) ? $page : 1 ;
      $perPage = 25;
      $countdata = $this->cm->getAllShips($where,'C');
      $offset = ($cur_page * $perPage) - $perPage;
      $pages = new Paginator($countdata,$perPage,$cur_page);
      $ships = $this->cm->getAllShips($where,'R',$perPage,$offset);
      // echo $this->db->last_query();die;
      $default_ship_id = $ships[0]->ship_id;
       if(!empty($ships)){
        $i = 0;

         foreach ($ships as $key=> $row) {
           $i++;
           $countOD = '';
           
           $next_port = (array) $this->cm->getNextPort($row->ship_id); 
           
           $rfq_count = $this->cm->getAllshipStockOrder(' AND so.status = 2 AND so.ship_id='.$row->ship_id,'C');
           
           if($rfq_count>0){
           $countOD = '<span class="counts"><span>+'.$rfq_count.'</span></span>'; 
           }

           
           $returnArr .='<a id="ship_'.$row->ship_id.'" onclick="getShipAllDetailsByID('.$row->ship_id.')" href="javascript:void(0)" class="list-group-item list-group-item-action py-3 lh-tight ships_link " aria-current="true">
                      <div class="d-flex flex-column w-100 align-items-center justify-content-between">'.$countOD.'
                        <strong>'.ucfirst($row->ship_name).'</strong><p><small>IMO No:- '.$row->imo_no.'</small></p>';
          if(!empty($next_port)){
            $returnArr .='  <p>Next port '.ucfirst($next_port['name']).'</p><span> Date '.ConvertDate($next_port['date'],'','d-m-Y').'</span>';
          }
          else{
            $returnArr .='  <p>Information not available</p>';

          }
           $returnArr .=  '</div></a>';
         }

         $pages = $pages->get_links();
       }
       else{
         $pages = '';
        $returnArr .= '<a href="javascript:void(0)" class="list-group-item list-group-item-action py-3 lh-tight active ships_link" aria-current="true">
                      <div class="d-flex flex-column w-100 align-items-center justify-content-between">
                        <strong>No Data Available</strong>
                      </div>
                    </a>';
       }
 
     }
     else{
        $pages = '';
        $returnArr .= '<a href="javascript:void(0)" class="list-group-item list-group-item-action py-3 lh-tight active ships_link" aria-current="true">
                      <div class="d-flex flex-column w-100 align-items-center justify-content-between">
                        <strong>No Data Available</strong>
                      </div>
                    </a>';
     }
    echo json_encode(array('data'=>$returnArr,'pagination'=>$pages,'default_ship_id'=>$default_ship_id));
   } 


  function getShipCurrentStock(){
    checkUserSession();
    $user_session_data = getSessionData();
    $ship_details = getCustomSession('ship_details');
    extract($this->input->post());
    $ship_id = ($ship_id) ? $ship_id : $ship_details['ship_id']; 
    $where = ' AND ms.ship_id = '.$ship_id;
     
    if($category_id){
     $where .= ' AND p.product_category_id = '.$category_id;   
    } 

    if($year){
     $where .= ' AND ms.year = '.$year;   
    }

    if($month){
     $where .= ' AND ms.month = '.$month;   
    }  

    if($keyword){
     $where .= ' AND p.product_name like "%'.trim($keyword).'%"';
    }

    $productArr = [];
    
    if(!empty($year) && !empty($month)){
      // $month_price = (array) $this->cm->stock_month_value($ship_id,$month,$year);  

      $data = $this->cm->monthly_stock_details($where);  
      foreach ($data as $v) {
           $productArr[$v->product_category_id][] = $v ; 
       }
    }

    // $stock_value = ($month_price) ?  'Opening - $'.$month_price['opening_price'].'<br> Closing - $'.$month_price['closing_price'] : 'Opening - $0 <br> Closing - $0';

    if($download==1){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'AvalibleStock.xlsx';
           $arrayHeaderData= array('Description','Unit','Qty');
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
                    ),'cellArray'=>array('A7:C7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7; 
           if(!empty($productArr)){
             foreach($productArr as $category => $products){
               $child_category = (array) $this->mp->getAllProductCategorybyid(' AND pc.product_category_id='.$category);
               $k++;
               $arrayData[] = array($child_category['category_name']);
               foreach($products as $product){
                $k++;
                $arrayData[] = array(ucfirst($product->product_name),strtoupper($product->unit),$product->available_stock);
               }
             }
           }

           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:C'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'AvalibleStock');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
    } 
     $grand_total = 0;
     if(!empty($productArr)){
             foreach($productArr as $category => $products){
               $child_category = (array) $this->mp->getAllProductCategorybyid(' AND pc.product_category_id='.$category);
                $returnArr .= '<tr class="main_category"><td colspan="4">'.$child_category['category_name'].'</td></tr>';
                $total = 0; 
                foreach($products as $product){
                    $total += $product->available_stock;
                    $returnArr .= '<tr class="products">
                        <td style="width:50%;flex:0 0 50%">'.ucfirst($product->product_name).'</td>
                        <td>'.strtoupper($product->unit).'</td>
                        <td>'.$product->available_stock.'</td>
                        <td>'.number_format($product->unit_price,2).'</td>
                        </tr>';
                }
                $grand_total += $total;
                $returnArr .= '<tr class="main_category"><td colspan="4" width="30%">Total</td><td>'.$total.'</td></tr>';

            }

            $returnArr .= '<tr class="main_category"><td colspan="4" width="30%">Grand Total</td><td>'.$grand_total.'</td></tr>';


     }
     else{
        $returnArr .= '<tr class="no-data"><td colspan="3" align="center">No Data Available</td></tr>';
     }
    echo json_encode(array('dataArr'=>$returnArr,'stock_value'=>$stock_value)); 
  }

  function adjustCurrentStock(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $ship_details = getCustomSession('ship_details');
    extract($this->input->post());
    $ship_id = ($ship_id) ? $ship_id : $ship_details['ship_id']; 
    $where = ' AND ms.ship_id = '.$ship_id.' AND ms.month ='.date('m').' AND ms.year ='.date('Y');

    $data = $this->cm->monthly_stock_details($where);
    // $data = $this->cm->current_stock_details($where);


    $update_flag = $this->input->post('update_flag');
     // echo $this->db->last_query();die;
    $total_available_qty = 0;
     $productArr = []; 
     foreach ($data as $v) {
       $productArr[$v->product_category_id.'|'.$v->category_name][] = $v ;   
     }

    if($action=='save'){
     foreach ($data as $v) {
       $type = $this->input->post('type_'.$v->product_id);
       // if(!empty($type) && ($type=='positive' || $type=='negative')){ 
       if(!empty($type)){
          if($type=='negative'){
            $this->form_validation->set_rules('qty_'.$v->product_id,'Qty','trim|required|numeric|less_than_equal_to['.$v->available_stock.']'); 
          }
          else{
           $this->form_validation->set_rules('qty_'.$v->product_id,'Qty','trim|required|numeric');            
          }  
           $this->form_validation->set_rules('reason_'.$v->product_id,'Reason','trim|required');
        }
      }

      if($this->form_validation->run()){
        $returnArr['status'] = 200;
        if($update_flag==1){
         $dataArr['added_by'] = $user_session_data->user_id;
         $dataArr['added_on'] = date('Y-m-d H:i:s');
         $dataArr['ship_id'] = $ship_id;
         $dataArr['entity_type'] = 'adjust_inventory';
         $json = array();
         foreach ($data as $v) {
          $type = $this->input->post('type_'.$v->product_id);
          $qty = $this->input->post('qty_'.$v->product_id);
          $reason = $this->input->post('reason_'.$v->product_id);
           if(!empty($type)){
            $json[] = array('product_id'=>$v->product_id,'type'=>$type,'past_qty'=>$v->available_stock,'qty'=>$qty,'reason'=>$reason);
              if($type=='positive'){
                 $dataArr1['last_total_stock'] = $v->total_stock;
                 $dataArr1['total_stock'] = ($v->available_stock + $qty);
                 $dataArr1['last_available_stock'] = $v->available_stock;
                 $dataArr1['available_stock'] = ($v->available_stock + $qty);
                 // $this->db->update('current_stock_details',$dataArr1,array('ship_id'=>$ship_id,'product_id'=>$v->product_id));

                 $this->db->update('monthly_stock_details',$dataArr1,array('monthly_stock_detail_id'=>$v->monthly_stock_detail_id));

              }
              elseif($type=='negative'){
                $dataArr1['last_total_stock'] = $v->total_stock;
                $dataArr1['total_stock'] = ($v->available_stock - $qty);
                $dataArr1['last_available_stock'] = $v->available_stock;
                $dataArr1['available_stock'] = ($v->available_stock - $qty);
                // $this->db->update('current_stock_details',$dataArr1,array('ship_id'=>$ship_id,'product_id'=>$v->product_id));

               $this->db->update('monthly_stock_details',$dataArr1,array('monthly_stock_detail_id'=>$v->monthly_stock_detail_id));

              }
            }
          }

          $dataArr['json_data'] = serialize($json);
          $this->cm->add_log_activity($dataArr);
          $returnArr['status'] = 300;
          $latest_data = $this->cm->monthly_stock_details($where);
          if(!empty($latest_data)){
            foreach ($latest_data as $ld) {
               if($ld->group_name=='Meat'){
                 $total_adjusted_qty += $ld->available_stock;
               }  
            }
          }
          // $mArr = array('closing_meat_qty'=>$total_adjusted_qty);

          $this->db->update('month_stock',array('closing_meat_qty'=>$total_adjusted_qty),array('month_stock_id'=>$latest_data[0]->month_stock_id));

          // $wArr = array('ship_id'=>$ship_id,'month'=>date('m'),'year'=>date('Y'));
          // $this->cm->update_meat_stock($mArr,$wArr); 
          $returnArr['returnMsg'] = 'Current stock adjusted successfully';
        }
      } 
    }
    $vars['dataArr'] = $this->input->post();
    $vars['productArr'] = $productArr;
    $data = $this->load->view('edit_current_stock',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }
  

 function add_edit_port($agent_id=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    extract($this->input->post());
    if($action=='save'){
     $this->form_validation->set_rules('name', 'Next Port Name', 'trim|required');
     $this->form_validation->set_rules('date', 'Date', 'trim|required|callback_check_next_port_date');
     $this->form_validation->set_message('check_next_port_date','Please select valid date');
     // $this->form_validation->set_rules('agent_id', 'Agent', 'trim|required');
     $this->form_validation->set_rules('country', 'Next Port Country', 'trim|required');
     if(!empty($departure_date)){
        $this->form_validation->set_rules('departure_date', 'Departure Date', 'trim|callback_check_prev_port_date');
        $this->form_validation->set_message('check_prev_port_date','Please select valid date');
     }
    if($this->form_validation->run()){
      // $date = date("Y-m-d", strtotime($date));
      $dataArr = array('name'=>$name,'date'=>ConvertDate($date,'','Y-m-d'),'departure_date'=>ConvertDate($departure_date,'','Y-m-d'),'prev_port'=>$prev_port,'agent_id'=>$agent_id,'country'=>$country,'prev_country'=>$prev_country);
      if($id == ''){
        $dataArr['ship_id'] = $second_id;
        $dataArr['added_on'] =  date('Y-m-d H:i:s');;
        $dataArr['added_by'] = $user_session_data->user_id;
        $this->cm->add_ship_port($dataArr);
        $returnArr['returnMsg'] = 'New port added successfully.';
         $returnArr['status'] = '101';
      }else{
        $where = 'port_id = '.$id;    
        $this->cm->edit_ship_port($dataArr,$where);
        $returnArr['returnMsg'] = 'Port updated successfully.';
        $returnArr['status'] = '102';
      }
     }
   }

    if(!empty($id)){
     $where = "And sp.port_id = ".$id;
     $vars['dataArr'] = $data = (array) $this->cm->getAllportById($where);
      
    }else{
     $vars['dataArr'] = $this->input->post();
    }
    
    $vars['agent_list'] = $this->cm->getAllPortAgents(' and pa.status = 1 AND FIND_IN_SET("'.(($data['name']) ? $data['name'] : trim($this->input->post('name'))).'",pa.port_name) > 0','R');
    $vars['agent_id'] = $agent_id;
    $data = $this->load->view('add_edit_port',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);  
  }

  function check_next_port_date(){
    $date = trim($this->input->post('date'));
    $user_date_obj = DateTime::createFromFormat('d-m-Y', $date);
    $today = new DateTime();
    if($user_date_obj >= $today) {
      return true;
    }else {
        return false;
    }
  }

  function check_prev_port_date(){
    $date = $this->input->post('departure_date');
    $user_date_obj = DateTime::createFromFormat('d-m-Y', $date);
    $today = new DateTime();
    if($user_date_obj <= $today) {
      return true;
    }else {
        return false;
    }
  } 
    
 function getallshipportlist(){
   checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   

    if(!empty($ship_id)){
      $where .= ' AND sp.ship_id = '.$ship_id;
    }

    if($arriving_date){
      $where .= ' AND sp.date ="'.convertDate($arriving_date,'','Y-m-d').'"';
    }

    if($departure_date){
      $where .= ' AND sp.departure_date ="'.convertDate($departure_date,'','Y-m-d').'"';

    }

    if($added_on){
      $where .= ' AND date(sp.added_on) ="'.convertDate($added_on,'','Y-m-d').'"';

    }

    if(!empty($agent_id)){
      $where .= ' AND sp.agent_id = '.$agent_id;  
    }

    if(!empty($keyword)){
       $where .= " AND ( sp.name like '%".trim($keyword)."%' or sp.country like '%".trim($keyword)."%' or sp.prev_port like '%".trim($keyword)."%' or sp.prev_country like '%".trim($keyword)."%') ";   
    }

      if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Port Name'){
                $order_by = 'ORDER BY sp.name '.$sort_type;
            }
            elseif($sort_column == 'Port Country'){
                $order_by = 'ORDER BY sp.country '.$sort_type;
            }
            elseif($sort_column == 'Date'){
                $order_by = 'ORDER BY sp.date '.$sort_type;
            }
            elseif($sort_column == 'Agent Name'){
                $order_by = 'ORDER BY pa.name '.$sort_type;
            }
            elseif($sort_column == 'Previous Country Port'){
                $order_by = 'ORDER BY sp.prev_country '.$sort_type;
            }
            elseif($sort_column == 'Previous Port'){
                $order_by = 'ORDER BY sp.prev_port '.$sort_type;
            }
            elseif($sort_column == 'Departure Date'){
                $order_by = 'ORDER BY sp.departure_date '.$sort_type;
            }
            elseif($sort_column == 'Added On'){
                $order_by = 'ORDER BY sp.added_on '.$sort_type;
            }
            elseif($sort_column == 'Added By'){
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY sp.port_id DESC';
        }
   
     if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllPortList($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }

   if($download==1){
      $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'NextPort';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Next Port Name','Next Port Country','Next Port Arriving Date','Agent Name','Previous Port Name','Previous Port Country','Previous Port Departure Date','Created On','Created By');
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
                    ),'cellArray'=>array('A7:I7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7;
           $ports = $this->cm->getAllPortList($where,'R',$perPage,$offset,$order_by);
           if($ports){
             foreach ($ports as $row) {
                 $k++;
                 $arrayData[] = array(ucfirst($row->name),ucwords($row->country),ucwords($row->agent_name),ConvertDate($row->date,'','d-m-Y'),ucfirst($row->prev_port),ucfirst($row->prev_country),ConvertDate($row->departure_date,'','d-m-Y'),ConvertDate($row->added_on,'','d-m-Y'),ucfirst($row->added_by));
             }
           }
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:I'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
   }

   $countdata = $this->cm->getAllPortList($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
   $ports = $this->cm->getAllPortList($where,'R',$perPage,$offset,$order_by);
   $edit_next_port = checkLabelByTask('edit_next_port');
   $delete_next_port = checkLabelByTask('delete_next_port');
   $edit ='';$delete ='';
   if($ports){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($ports)).' of '.$countdata.' entries';
         foreach ($ports as $row){
          $edit = '';
          if($edit_next_port) {     
             $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Port\',\'shipping/add_edit_port\',\''.$row->port_id.'\',\''.$row->ship_id.'\',\'\');" >Edit</a>';
            } 
            // if($delete_next_port) {
            //   $delete = '<a onclick="updateStatusBoxDelete('.$row->port_id.',\'0\',\''.$row->name.'\',\'shipping/deletePort\')" href="javascript:void(0)">Delete</a>'; 
            // }

            $returnArr .= "<tr>
                              <td width='10%'>".ucfirst($row->name)."</td>
                              <td width='10%'>".ucwords($row->country)."</td>
                              <td width='10%'>".ConvertDate($row->date,'','d-m-Y')."</td>
                              <td width='10%'>".ucfirst($row->agent_name)."</td>
                              <td width='10%'>".ucfirst($row->prev_port)."</td>
                              <td width='10%'>".ucwords($row->prev_country)."</td>
                              <td width='10%'>".ConvertDate($row->departure_date,'','d-m-Y')."</td>
                              <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y')."</td>
                              <td width='10%'>".ucfirst($row->added_by)."</td>";
            if($row->date>=date('Y-m-d')){
            $returnArr .=     '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$delete.'</li>
                                </ul>
                                </div></td>';
            }
            else{
                $returnArr .= '<td width="2%" class="action-td"></td>';
            }
            $returnArr .= '</tr>';
           }
         $pagination = $pages->get_links();
      }
      else
        {
            $pagination = '';
            $returnArr = '<tr><td colspan="6" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }   

     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));   
    }
   
   function getPortListhtml($ship_id=''){
     checkUserSession();
     $user_session_data = getSessionData();
     $ship_id = base64_decode($ship_id);
     $returnArr = '';
     if($ship_id){
      $vars['ship_id'] = $ship_id;
      $vars['agent_list'] = $this->cm->getAllPortAgents(' and pa.status = 1','R');
      $data = $this->load->view('ship_port_list',$vars,true);
      $returnArr = $data;  
     }
     echo json_encode(array('data'=>$returnArr));
   }

  function add_stock_details($mode='add'){
     checkUserSession();
     $user_session_data = getSessionData();
     $returnArr['status'] = '100';
     $action = $this->input->post('actionType');
     $ship_details = getCustomSession('ship_details');
     $ship_id = $ship_details['ship_id'];
     $ship_stock_id = $this->input->post('id');
     $post_data = getCustomSession('post_data_'.$ship_id);

     if($mode=='second_opening_stock'){
        $n_date = new DateTime($post_data['stock_date']);
        $n_date->modify('-1 month');
        $nn_date = $n_date->format('Y-m-d');
         $month = convertDate($nn_date,'','m');
         $year = convertDate($nn_date,'','Y');
         $n_products = (array) $this->cm->monthly_stock_details(' AND ms.ship_id = '.$ship_id.' AND ms.month ='.$month.' AND ms.year ='.$year);
         $import_data = array();
         if(!empty($n_products)){
           foreach ($n_products as $val) {
               $product_id = $val->product_id;
               $import_data['qty_'.$product_id] = $val->available_stock;
               $import_data['unit_price_'.$product_id] = $val->unit_price; 
            } 
         }

     }
     elseif($mode == 'edit'){
         $where = ' AND sst.ship_stock_id='.$ship_stock_id;
         $data = (array) $this->mp->getStockDetail($where);
         $json_data = unserialize($data['json_data']); 
         $import_data = array();
         $import_data['stock_date'] = $data['stock_date'];
         if(!empty($json_data)){
          for ($j=0; $j < count($json_data); $j++) { 
              $product_id = $json_data[$j]['product_id'];
              $import_data['qty_'.$product_id] = $json_data[$j]['quantity'];
              $import_data['unit_price_'.$product_id] = $json_data[$j]['unit_price'];
              $import_data['remark_'.$product_id] = $json_data[$j]['remark']; 
            }  
        }
     }
     else{
       $import_data = getImportData('opening_stock_data_'.$ship_id); 
     }

    if($action=='save'){
        $this->session->unset_userdata('opening_stock_data_'.$ship_id);    
        $import_data = '';
       if($this->validate_stock_entiries()){ 
         $products = $this->mp->getAllProduct( ' AND  p.`status`=1 ' ,'R');
         $meat = 0; 
         if(!empty($ship_stock_id)){
          $this->db->delete('ship_stock_details',array('ship_stock_id'=>$ship_stock_id));  
          if(!empty($products)){
           $grand_total = 0;
           $opening_value = 0;
            foreach ($products as $row) {
               $quantity = trim($this->input->post('qty_'.$row->product_id));
               $unit_price = trim($this->input->post('unit_price_'.$row->product_id));
               $remark = trim($this->input->post('remark_'.$row->product_id));
               $total_price = ($quantity * $unit_price);
               $grand_total += $total_price;
               if(!empty($quantity)){
                  $batch[] = array('ship_stock_id'=>$ship_stock_id,'product_id'=>$row->product_id,'quantity'=>$quantity,'total_price'=>$total_price,'unit_price'=>$unit_price,'remark'=>$remark);
                }
              }
            }
             $this->db->insert_batch('ship_stock_details',$batch);
             $json_data = serialize($batch);
             $returnArr['status'] = 200;
             $this->cm->edit_ship_stock(array('stock_date'=>convertDate(trim($this->input->post('stock_date')),'','Y-m-d'),'json_data'=>$json_data,'total_price'=>$grand_total),' ship_stock_id ='.$ship_stock_id);
             $returnArr['returnMsg'] = 'Opening Stock Updated successfully.';
          }
         else{
             $dataArr['ship_id'] = $ship_id;
             $dataArr['created_on'] = date('Y-m-d H:i:s');
             $dataArr['created_by'] = $user_session_data->user_id;
             $dataArr['stock_date'] = convertDate($post_data['stock_date'],'','Y-m-d');
             $ship_stock_id = $this->cm->add_ship_stock($dataArr);
             if(!empty($products)){
                $grand_total = 0;
                $opening_value = 0;
                foreach ($products as $row) {
                   $quantity = trim($this->input->post('qty_'.$row->product_id));
                   $unit_price = trim($this->input->post('unit_price_'.$row->product_id));
                   $remark = trim($this->input->post('remark_'.$row->product_id));
                   $total_price = ($quantity * $unit_price);
                   $grand_total += $total_price;
                   if(!empty($quantity)){
                      $batch[] = array('ship_stock_id'=>$ship_stock_id,'product_id'=>$row->product_id,'quantity'=>$quantity,'total_price'=>$total_price,'unit_price'=>$unit_price,'remark'=>$remark);
                   }
                }
             }

             $this->db->insert_batch('ship_stock_details',$batch);
             $json_data = serialize($batch);
             $returnArr['status'] = 200;
             $this->cm->edit_ship_stock(array('json_data'=>$json_data,'total_price'=>$grand_total),' ship_stock_id ='.$ship_stock_id);
             $returnArr['returnMsg'] = 'Opening Stock Added successfully.';
        }

       }
    }

    if($mode=='second_opening_stock'){
       $products_category = array();
       foreach ($n_products as $row) {
         $products_category[$row->category_name][] = $row;    
       } 
       $productArr = array();
       if(!empty($products_category)){
        foreach ($products_category  as $category => $products) {  
            $productArr[$category][]  = $products;   
        }
       }
    }
    else{
        $products_category = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);
        $productArr = array();
        foreach ($products_category as $category) {  
          if($category->code=='misc_items'){
            $products = $this->mp->getAllShipWiseProduct(' AND p.status = 1 AND smp.ship_id ='.$ship_id);
            $productArr[$category->category_name][]  = $products;   
          }
          else{
           $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_category_id = '.$category->product_category_id,'R');
           $productArr[$category->category_name][]  = $products;        
          }
        }
    }
    
    $vars['productArr'] = $productArr;
    if(!empty($import_data)){
     $vars['dataArr'] = $import_data;
    }else{
     $vars['dataArr'] = $this->input->post();
    }

    $vars['mode'] = $mode;
    $vars['stock'] = $stock;
    $vars['ship_stock_id'] = $ship_stock_id;
    $vars['group_products'] = $this->mp->getAllProductGroup('','R');
    $data = $this->load->view('add_stock_details',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);  
   }

  /*
   function getAllProductForOpeningStock(){
    checkUserSession();
    $user_session_data = getSessionData();
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $returnArr = '';
    $import_data = getImportData('opening_stock_data_'.$ship_id);
         $products_category = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);
          if($products_category){
          foreach ($products_category as $row) {
             $returnArr .= '<tr class="parent_row">
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$row->category_name.'</td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                 $returnArr .= '</tr>';
                $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_category_id = '.$row->product_category_id,'R');
                $child_row_total_value = array();
               foreach ($products as $row1) {
                 $returnArr .= '<tr class="child_row">';
                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$row1->item_no.'</td>';
                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($row1->product_name).'</td>';
                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($row1->unit).'</td>';
                $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                <input type="text" id="product_qnty_'.$row1->product_id.'" data-id="'.$row1->product_id.'" class="link quentity" data-quantity="1" name="qty_product_'.$row1->product_id.'" value="'.$import_data['qty_product_'.$row1->product_id].'">
                            </td>'; 
                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                <input type="text" class="unit_price" data-quantity="1" name="val_product_'.$row1->product_id.'" id="val_product_'.$row1->product_id.'" value="'.$import_data['val_product_'.$row1->product_id].'">
                            '.form_error('val_product_'.$row1->product_id,'<p class="new_error">','</p>').'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="total_'.$row1->product_id.'" class="line_total">0</td>';          
                  $returnArr .= '</tr>';            
                 }

              }     
            $returnArr .= '<tr class="parent_row_count">
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_total">0</td>';
             $returnArr .= '</tr>'; 
           }
            else{
           $returnArr .='<tr><td colspan="5"><strong>No Data Available</strong></td></tr>';
          }

     echo json_encode(array('dataArr'=>$returnArr));
   }

*/


   function order_request_list($ship_id=''){
    checkUserSession();
    $ship_id = base64_decode($ship_id);
    $user_session_data = getSessionData();
    $vars['ship_id'] = $ship_id;
    $vars['opening_stock'] = $this->cm->getShipStockById($ship_id,'AND st.is_submit=1');    
    $data = $this->load->view('order_request_list',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }

  function getAllRFQList(){
        checkUserSession();
        $user_session_data = getSessionData();
        // print_r($user_session_data);die;
        $where = '';$order_by='';
        $returnArr = '';
        extract($this->input->post());
        $cur_page   = $page ? $page : 1;
        $perPage    = $perPage ? $perPage : 5;   
        // $ship_details = getCustomSession('ship_details');
        // $ship_id = ($this->input->post('ship_id')) ? $this->input->post('ship_id') : $ship_details['ship_id'];
        
        $ship_id = $this->input->post('ship_id');

        if(!empty($ship_id)){
           $where .= ' AND so.ship_id ='.$ship_id; 
        }

        if($user_session_data->code == 'cook' || $user_session_data->code == 'captain'){
        }
        else{
           $where .= ' AND so.status != 1';
        }

        if(!empty($rfq_status)){
           if($rfq_status=='In'){
             $where .= ' AND so.status > 3 AND  so.status < 6'; 
           }
           elseif($rfq_status=='RA'){
             $where .= ' AND so.status = 8 OR so.status = 9'; 
           }
           else{
             $where .= ' AND so.status ='.$rfq_status; 
           } 
        }

        if(!empty($type)){
           $where .= ' AND so.requisition_type ="'.$type.'"'; 
        }

        if(!empty($keyword)){
         $where .= ' AND (so.rfq_no like "%'.trim($keyword).'%" OR u.first_name like "%'.trim($keyword).'%")';   
        }

        if(!empty($created_on)){
         $where .= ' AND date(so.created_on) ="'.convertDate($created_on,'','Y-m-d').'"';
        }

         if((!empty($sort_c)) && (!empty($sort_t))){
            if($sort_c == 'RFQ No'){
                $order_by = 'ORDER BY so.rfq_no '.$sort_t;
            }
            elseif($sort_c == 'Type'){
                $order_by = 'ORDER BY so.requisition_type '.$sort_t;
            }
            elseif($sort_c == 'Added On'){
                $order_by = 'ORDER BY so.created_on '.$sort_t;
            }
            elseif($sort_c == 'Added By'){
                $order_by = 'ORDER BY u.first_name '.$sort_t;
            }
            elseif($sort_c == 'Status'){
                $order_by = 'ORDER BY so.status '.$sort_t;
            }
            elseif($sort_c == 'Lead Time'){
                $order_by = 'ORDER BY so.lead_time '.$sort_t;
            }
        }else{
            $order_by = 'ORDER BY so.created_on DESC';
        }
       

    if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllshipStockOrder($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }  


   if($download==1){
     $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'RFQ';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('RFQ No','Requisition Type','Lead Time','Created On','Created By','Status');
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
          $order = $this->cm->getAllshipStockOrder($where,'R',$perPage,$offset,$order_by);

          if($order){
            foreach ($order as $row) {
                $k++; 
                if($row->status==1){
              $status = 'Created';  
            }
            elseif($row->status==2){
              $status = 'Submitted';  
            }
            elseif($row->status==3){
              $status = 'RFQ Verified';  
            }
            elseif($row->status==4){
              if($user_session_data->code == 'super_admin'){
                $status = 'Send For Quotation';  
              }else{
                 $status = 'RFQ Verified (In Progress)';
              }
            }
            elseif($row->status==5){
             if($user_session_data->code == 'super_admin'){
               $status = 'Quotation Received';
              }else{
                $status = 'RFQ Verified (In Progress)';
              } 
            }
            elseif($row->status==6){
             if($user_session_data->code == 'super_admin'){  
               $status = 'Quotation Approved';
             }else{
                $status = 'RFQ Verified (In Progress)'; 
             }  
            }
            elseif($row->status==7){
              if($user_session_data->code=='super_admin'){
                $status = 'Send For Review';  
              }
              else{
                $status = 'Received Review Request';  
              }  
            }
            elseif($row->status==8){
              $status = 'Request Approved';    
            }
            elseif($row->status==9){
              if($user_session_data->code=='super_admin'){    
                $status = 'Purchase Order Created';
              }
              else{
                $status = 'Request Approved';    
              }  
            }
                $arrayData[] = array($row->rfq_no,ucwords(str_replace('_',' ',$row->requisition_type)),(($row->lead_time) ? $row->lead_time.' Days' : ''),ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucfirst($row->user_name),$status);
            }
          }

          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
   }
 
       $countdata = $this->cm->getAllshipStockOrder($where,'C');
       $offset = ($cur_page * $perPage) - $perPage;
       $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
       $order = $this->cm->getAllshipStockOrder($where,'R',$perPage,$offset,$order_by);

       $edit_rfq = checkLabelByTask('edit_rfq');
       $update_work_flow = checkLabelByTask('update_work_flow');
       $review_rfq_label = checkLabelByTask('review_rfq');
       $send_to_vendor_label = checkLabelByTask('send_to_vendor');
       $import_quotation = checkLabelByTask('import_quotation');
       $view_received_quotation = checkLabelByTask('view_received_quotation');
       $approved_quotation = checkLabelByTask('approved_quotation');
       $send_for_review_label = checkLabelByTask('send_for_review');
       $view_edit_request = checkLabelByTask('view_edit_request');
       $view_review_rfq = checkLabelByTask('view_review_rfq');
       $add_work_order = checkLabelByTask('add_work_order');
       $download_rfq_quote = checkLabelByTask('download_rfq_quote');
       $view_approved_request = checkLabelByTask('view_approved_request');
       $admin_staff = true;
       if($user_session_data->code=='cook' || $user_session_data->code=='captain'){
        $admin_staff = false;
       }

      if($order){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($order)).' of '.$countdata.' entries';
         foreach ($order as $row){
            $status = $preview = $edit = $review_rfq = $send_to_vendor = $download = $import =  $quote = $cQuote = $send_for_review = $po = $update_st = '';
            if($row->status==1){
              $status = '<span class="">Created</span>';  
            }
            elseif($row->status==2){
              $status = '<span class="">Submitted</span>';  
            }
            elseif($row->status==3){
              $status = '<span class="">RFQ Verified</span>';  
            }
            elseif($row->status==4){
              if($admin_staff){
                $status = '<span class="">Send For Quotation</span>';  
              }else{
                 $status = 'RFQ Verified (In Progress)';
              }
            }
            elseif($row->status==5){
             if($admin_staff){
               $status = '<span class="">Quotation Received</span>';
              }else{
                $status = 'RFQ Verified (In Progress)';
              } 
            }
            elseif($row->status==6){
             if($admin_staff){  
               $status = '<span class="">Quotation Approved</span>';
             }else{
                $status = 'RFQ Verified (In Progress)'; 
             }  
            }
            elseif($row->status==7){
              if($admin_staff){
                $status = '<span class="">Send For Review</span>';  
              }
              else{
                $status = '<span class="">Received Review Request</span>';  
              }  
            }
            elseif($row->status==8){
              $status = '<span class="">Request Approved</span>';    
            }
            elseif($row->status==9){
              if($admin_staff){    
                $status = '<span class="">Purchase Order Created</span>';
              }
              else{
                $status = '<span class="">Request Approved</span>';    
              }  
            }
           
           $download = '<a href="'.base_url().'shipping/download_rfq_xls/'.base64_encode($row->ship_order_id).'">Download RFQ</a>';

           if($row->status>=4 && $view_received_quotation){
             $quote = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Vendor Quotation\',\'shipping/vendor_quatation\',\''.$row->ship_order_id.'\',\'compere\',\'98%\',\'full-width-model\')">Vendor Quotation</a>';
            }

            if($row->status==1){
              if($edit_rfq){ 
                $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Edit RFQ\',\'shipping/add_rfq_details\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Edit RFQ</a>';
              }
            }
            elseif($row->status==2){
              if($admin_staff){
                if($row->created_by == $user_session_data->user_id){
                  $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Edit RFQ\',\'shipping/add_rfq_details\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Edit RFQ</a>';   
                }
                else{
                  if($review_rfq_label){
                    $review_rfq = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Review RFQ\',\'shipping/review_rfq\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Review RFQ</a>';
                  }  
                }
              }  
           //    // if($user_session_data->code == 'super_admin' && $row->created_by == $user_session_data->user_id){
           //    //   $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Edit RFQ\',\'shipping/add_rfq_details\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Edit RFQ</a>'; 
           //    // }
           //    // elseif($user_session_data->code == 'super_admin' && $row->created_by != $user_session_data->user_id){
           //    //   $review_rfq = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Review RFQ\',\'shipping/review_rfq\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Review RFQ</a>';
           //    // }  
            }

          elseif($row->status==3){
           //   if($manage_vendor_quation){
               if($send_to_vendor_label){  
                   $send_to_vendor = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Send RFQ To Vendor\',\'shipping/send_order_request\',\''.$row->ship_order_id.'\',\''.$row->rfq_no.'\',\'50%\')">Send To Vendor</a>';
               }
               if($download_rfq_quote){
                  $download = '<a href="javascript:void(0)" onclick="downloadRfq('.$row->ship_order_id.')">Download (RFQ / Quotation)</a>';
               }
               else{
                  $download = '<a href="'.base_url().'shipping/download_rfq_xls/'.base64_encode($row->ship_order_id).'">Download RFQ</a>';
               }   
            }
            elseif($row->status==4){
             if($import_quotation){
                $import = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Import Quotation\',\'shipping/import_vendor_quatation\',\''.$row->ship_order_id.'\',\'\',\'\')">Import Quotation</a>';

           //      $download = '<a href="javascript:void(0)" onclick="downloadRfq('.$row->ship_order_id.')">Download (RFQ / Quotation)</a>';

               }                  
            }

            elseif($row->status==5){
              if($approved_quotation){
                $quote = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Vendor Quotation\',\'shipping/vendor_quatation\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Vendor Quotation(Approve/Compare)</a>';
             }
            }
            elseif($row->status==6){    
             if($admin_staff && $send_for_review_label){
                $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Send For Review\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Send For Review</a>';  
                }              
            }
            elseif($row->status==7){
             if($admin_staff && $view_edit_request){
                $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View/Edit Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">View/Edit Request</a>';  
             }
             else{
               if($view_review_rfq){ 
                  $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Reviewed Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">View Reviewed Request</a>';
                }
           //      else{
           //      $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Approved Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'view\',\'98%\',\'full-width-model\')">View Reviewed Request</a>';   
           //      }
             }   
            }
            elseif($row->status==8 ){
               if($add_work_order){
                  $po = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Create Purchase Order\',\'shipping/order_basic_details\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Create Purchase Order</a>';  
                 }
               if($edit_approved_request){ 
                  $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Reviewed Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'\',\'98%\',\'full-width-model\')">Edit Approved Request</a>';
                }
           //      else{
           //       $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Approved Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'view\',\'98%\',\'full-width-model\')">View Approved Request</a>'; 
           //      } 
            }
            elseif($row->status==9){
             if($view_approved_request){
               $send_for_review = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Approved Request\',\'shipping/send_to_master\',\''.$row->ship_order_id.'\',\'view\',\'98%\',\'full-width-model\')">View Approved Request</a>';  
             }
           }  
          
           

           if($update_work_flow && $row->status<8){
             $fn_name = ($user_session_data->code=='cook' || $user_session_data->code=='captain') ? 'staffchangeRFQStatus' : 'changeRFQStatus';
             $update_st = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Update WorkFlow Steps\',\'shipping/'.$fn_name.'\',\''.$row->ship_order_id.'\',\'\',\'80%\',\'\')">Update WorkFlow</a>';
           } 
    
           // if($user_session_data->code=='super_admin' && $row->status<8){
           //    $update_st = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Update WorkFlow Steps\',\'shipping/changeRFQStatus\',\''.$row->ship_order_id.'\',\'\',\'80%\',\'\')">Update WorkFlow</a>';
           // }
           // elseif($user_session_data->code!='super_admin' && ($row->status==1 || $row->status==7)){
           //   if($submit_rfq_to_head){
           //     $update_st = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Update WorkFlow Steps\',\'shipping/changeRFQStatus\',\''.$row->ship_order_id.'\',\'\',\'80%\',\'\')">Update WorkFlow</a>';
           //   }
           // } 


           // if($row->status>6){
           //   $download = '<a href="'.base_url().'shipping/download_updated_rfq_xls/'.base64_encode($row->ship_order_id).'">Download RFQ</a>';   
           // }
                      
          $returnArr .= "<tr id='row-".$row->ship_order_id."'><td width='11%'>".$row->rfq_no."</td>
                             <td width='10%'>".ucwords(str_replace('_',' ',$row->requisition_type))."</td>
                               <td width='10%'>".ucwords($row->port_name).' | '.convertDate($row->arrive_date,'','d-m-Y')."</td>
                             <td width='10%'>".(($row->lead_time) ? $row->lead_time.' Days' : '-')."</td>
                              <td  width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</a></td>
                              <td width='10%'>".ucfirst($row->user_name)."</td>
                              <td width='10%'>".$status."</td>"
                               ;
            // if($row->agent_status==1){
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                <ul class="dropdown-menu pull-right">';
               $returnArr .= '<li>'.$download.'</li>  
                              <li>'.$edit.'</li>
                              <li>'.$review_rfq.'</li>
                              <li>'.$send_to_vendor.'</li>
                              <li>'.$import.'</li>
                              <li>'.$quote.'</li>
                              <li>'.$send_for_review.'</li>
                              <li>'.$po.'</li>
                              <li>'.$update_st.'</li></ul>
                </div></td>';
             // }
             // else{
             //  $returnArr .='<td width="3%" class="action-td"><a href="javascrip:void(0)" onclick="alert(\'Port Agent Deactivate\')"><i class="fa fa-info-circle" aria-hidden="true"></i></a></td>';  
             // }

               $returnArr .='</tr>'; 
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

  function download_for_quote($ship_order_id='',$type=''){
    checkUserSession();
     $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
     $dataArr = unserialize($data['json_data']);
     $file_name = ($type=='latest_rfq') ? 'updated'.$data['rfq_no'].'.csv' : 'forQuote'.$data['rfq_no'].'.csv';

     $productArr = []; 
     if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['quantity'],'remark'=>$dataArr[$i]['remark'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
       }

        ksort($productArr);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$file_name);
        $fp = fopen('php://output', 'w');

      if($type=='for_quote'){
        $field_array = $this->config->item('quote_import_fields');
        fputcsv($fp, $field_array);     
          $k =0;
          if(!empty($productArr)){
            foreach ($productArr as $key => $rows) {
             foreach($rows as $category => $products){
                 $field_array = array('',$category);  
                 fputcsv($fp, $field_array);  
                  for ($i=0; $i <count($products) ; $i++) { 
                    $k++;
                    $field_array = array($k,ucfirst($products[$i]['product_name']),$products[$i]['item_no'],$products[$i]['quantity'],strtoupper($products[$i]['unit']),'','',$products[$i]['remark']);
                     fputcsv($fp, $field_array); 
                 }
              }
            }
          }
     }
     else{
        $field_array = array('Item No.','Description','Unit','QTY','Remark');
        fputcsv($fp, $field_array);     
          if(!empty($productArr)){
            foreach ($productArr as $key => $rows) {
             foreach($rows as $category => $products){
                 $field_array = array('',$category);  
                 fputcsv($fp, $field_array);
                  for ($i=0; $i <count($products) ; $i++) { 
                    $field_array = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity'],$products[$i]['remark']);
                     fputcsv($fp, $field_array); 
                 }
              }
            }
          } 
     }
     fclose($fp);
     exit;    
  }

  function download_for_quote_xls($ship_order_id='',$type=''){
    checkUserSession();
     $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
     $dataArr = unserialize($data['json_data']);
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = ($type=='latest_rfq') ? 'updated'.$data['rfq_no'].'.xlsx' : 'forQuote'.$data['rfq_no'].'.xlsx';
     $listColumn     = array();
     $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
    if($type=='for_quote'){
     $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                  ) 
            ),'cellArray'=>array('A7:H7'));
    }else{ 
     $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                  ) 
            ),'cellArray'=>array('A7:E7'));
     }              
     $productArr = []; 
     if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['quantity'],'remark'=>$dataArr[$i]['remark'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
       }

      $arrayData   = array();
      $k=7;
      ksort($productArr);
      if($type=='for_quote'){
        $arrayHeaderData = $this->config->item('quote_import_fields'); 
        $arrayData[2] = array('','One North Ships','','','');
        $arrayData[7] = $arrayHeaderData;   
                           $j = 0;
          if(!empty($productArr)){
            foreach ($productArr as $key => $rows) {
             foreach($rows as $category => $products){
                 $arrayData[] = array('',$category);  
                 $k++;
                  for ($i=0; $i <count($products) ; $i++) { 
                    $k++;
                    $j++;
                    $arrayData[] = array($j,ucfirst($products[$i]['product_name']),$products[$i]['item_no'],'',strtoupper($products[$i]['unit']),'','',$products[$i]['remark']);
                 }
              }
            }
          }
     }
     else{
        $arrayHeaderData= array('Item No.','Description','Unit','Quantity','Remark');
        $arrayData[2] = array('','One North Ships','','','');
        $arrayData[7] = $arrayHeaderData;    
          
          if(!empty($productArr)){
            foreach ($productArr as $key => $rows) {
             foreach($rows as $category => $products){
                 $k++;
                 $arrayData[] = array('',$category);  
                  for ($i=0; $i <count($products) ; $i++) { 
                     $k++;
                    $arrayData[] = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity'],$products[$i]['remark']);
                 }
              }
            }
          } 
     }
     
     if($type=='for_quote'){
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN'
         ))
       );       
     }else{
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN'
        ))
       );  
     }
         
     $arrayBundleData['listColumn'] = $listColumn;
     $arrayBundleData['arrayData'] = $arrayData;
     $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'RFQ');
     readfile(FCPATH.'uploads/sheets/'.$fileName);
     unlink(FCPATH.'uploads/sheets/'.$fileName);
     exit;     
  } 

  function staffchangeRFQStatus(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $submit_rfq_to_head = checkLabelByTask('submit_rfq_to_head');
    $approved_request = checkLabelByTask('approved_request');
    $ship_order_id = $this->input->post('id');
    if(!empty($ship_order_id)){
     $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
    }
    

    $buttonDis = 'disabled';
    if($submit_rfq_to_head){
      $buttonDis = '';   
    }
    elseif($approved_request){
     $buttonDis = '';   
    } 

    if($data['status']==1){
      $step1 = $step7 = 'disabled';
      $step1class = 'done';
      $step2 = 'checked';
    }
    if($data['status']==2 || ($data['status']>2 && $data['status']<7)){
      $step1 = $step2 = $step7 = 'disabled="disabled"';   
      $step1class = $step2class = 'done';
      $buttonDis = 'disabled';   
    }

    if($data['status']==7){
      $step1 = $step2 = $step7 = 'disabled="disabled"';     
      $step1class = $step2class = $step7class = 'done';
      $step8 = 'checked'; 
    }


    if(!empty($ship_order_id)){
        $returnArr = '
        <div class="animated fadeIn" id="stock_form">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="update_rfq_status" enctype="multipart/form-data" id="update_rfq_status" method="post">
        <div class="no-padding rounded-bottom">';

        $returnArr .= '<div class="form-body">
                        <div class="row1">
                        <div class="form-group col-sm-12">
                        <label class="col-sm-12">WorkFlow Steps</label>
                        <div class="col-sm-12"><div class="popup-progressbar">';
        $returnArr .= '<label class="radio-inline '.$step1class.'">
                        <input type="radio" name="status" '.$step1.' value="1"><span>Created</span></label>';
        $returnArr .='<label class="radio-inline '.$step2class.'">
                        <input type="radio" name="status" '.$step2.' value="2"><span>Submitted To Head Office</span></label>';
        $returnArr .='<label class="radio-inline '.$step7class.'">
                        <input type="radio" '.$step7.' name="status" value="7"><span>Received Review Request</span></label>';  
        $returnArr .='<label class="radio-inline">
                        <input type="radio" name="status" '.$step8.' value="8"><span>Approved Request</span></label>';

        $returnArr .= '</div></div></div></div>
         <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="id" id="ship_order_id" value="'.$ship_order_id.'">    
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" '.$buttonDis.' class="btn btn-success btn-slideright mr-5" onclick=submitAjax360Form("update_rfq_status","shipping/updateRfqStatus","","order_request_list")>Update Step</button>
           </div>
         </div>';              
      }

    echo json_encode(array('data'=>$returnArr,'status' => 100));  
  }

    function changeRFQStatus(){
    checkUserSession();
    $user_session_data = getSessionData();
    $verify_rfq = checkLabelByTask('verify_rfq');
    $send_to_quotation = checkLabelByTask('send_to_quotation');
    $quotation_received = checkLabelByTask('quotation_received');
    $approved_request = checkLabelByTask('approved_request');
    $returnArr = '';
    $ship_order_id = $this->input->post('id');
    $import_quote =  $this->cm->getVendorQuotation(' AND vq.ship_order_id = '.$ship_order_id);
    if(!empty($ship_order_id)){
      $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
    }

    $actionType = $this->input->post('actionType');
    $step4 = $step5 = $step6 = 'disabled';
    
    $buttonDis = 'disabled';
 

    if($verify_rfq){
     $buttonDis = '';
    }
    elseif($send_to_quotation){
     $buttonDis = '';   
    }
    elseif($quotation_received){
     $buttonDis = '';   
    }
    elseif($approved_request){
     $buttonDis = '';   
    } 
    
    if($data['status']==2){
      $step3= 'checked';  
    }
    if($data['status']==3){
      $step3= 'disabled="disabled"';
      $step3class = 'done';
      $step4 = 'checked';  
    }
    if($data['status']==4){
      $step1 = $step2 = $step3= $step4 = 'disabled="disabled"';
      $step1class = $step2class = $step3class = $step4class = 'done';
      $step5 = 'checked';
      if(empty($import_quote)){
        $msg = '<span style="color:red">Please Import atleast one vendor quotation</span>';
         $buttonDis = 'disabled';
      }   
    }
    if($data['status']==5){   
      $step1 = $step2 = $step3= $step4 = $step5 ='disabled="disabled"';
      $step1class = $step2class = $step3class = $step4class = $step5class = 'done';
      $msg = '<span style="color:red">You are not able to manually update the workflow step. Please approve the vendor quote, then the workflow step will automatically update</span>';
      $buttonDis = 'disabled';

    }
    if($data['status']==6){   
      $step1 = $step2 = $step3= $step4 = $step5 = 'disabled="disabled"';
      $step1class = $step2class = $step3class = $step4class = $step5class = $step6class = 'done';
     if($user_session_data->code=='cook' || $user_session_data->code=='captain'){ 
      $msg = '<span style="color:red">You will not received a review request.</span>';  
      $buttonDis = 'disabled';
     }
     else{
      $step7 = 'checked';

      $msg = '<span style="color:red">Before updating the status, make sure you have checked everything.</span>';
     }
      // $buttonDis = 'disabled';
    }
    if($data['status']==7){
      $step1 = $step2 = $step3= $step4 = $step5 = 'disabled="disabled"';
      $step1class = $step2class = $step3class = $step4class = $step5class = $step6class = $step7class = 'done'; 
      $step6 = 'checked';
    }



    if(!empty($ship_order_id)){
        $returnArr = '
        <div class="animated fadeIn" id="stock_form">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="update_rfq_status" enctype="multipart/form-data" id="update_rfq_status" method="post">
        <div class="no-padding rounded-bottom">';

        $returnArr .= '<div class="form-body">
                        <div class="row1">
                        <div class="form-group col-sm-12">
                        <label class="col-sm-12">WorkFlow Steps</label>
                        <div class="col-sm-12"><div class="popup-progressbar">';
        $returnArr .= '<label class="radio-inline done">
                        <input type="radio" name="status" disabled value="1"><span>Created</span></label>';
        $returnArr .='<label class="radio-inline done ">
                        <input type="radio" name="status" disabled value="2"><span>Submitted To Head Office</span></label>';
              
        $returnArr .='<label class="radio-inline '.$step3class.'">
                        <input type="radio" name="status" '.$step3.' value="3"><span>RFQ Verified</span></label>';
        
        $returnArr .='<label class="radio-inline '.$step4class.'">
                        <input type="radio" name="status" '.$step4.' value="4"><span>Send For Quotation</span></label>';                       
        $returnArr .='<label class="radio-inline '.$step5class.'">
                        <input type="radio" name="status" '.$step5.' value="5"><span>Quotation Received</span></label>';
         
        $returnArr .='<label class="radio-inline '.$step6class.'">
                        <input type="radio" name="status" disabled value="6"><span>Quotation Approved</span></label>';
 
        $returnArr .='<label class="radio-inline '.$step7class.'">
                        <input type="radio" '.$step7.' name="status" value="7"><span>Send For Review</span></label>';
        
        $returnArr .='<label class="radio-inline">
                        <input type="radio" name="status" '.$step6.' value="8"><span>Approved Request</span></label>';  
 
        $returnArr .= '</div></div>'.$msg.'</div></div>

         <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="id" id="ship_order_id" value="'.$ship_order_id.'">    
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" '.$buttonDis.' class="btn btn-success btn-slideright mr-5" onclick=submitAjax360Form("update_rfq_status","shipping/updateRfqStatus","","order_request_list")>Update Step</button>
           </div>
         </div>';
      }
    echo json_encode(array('data'=>$returnArr,'status' => 100));
   } 

  // function changeRFQStatus(){
  //   checkUserSession();
  //   $user_session_data = getSessionData();
  //   // $manage_vendor_quation = checkLabelByTask('manage_vendor_quation');
  //   $submit_rfq_to_head = checkLabelByTask('submit_rfq_to_head');
  //   // $verify_rfq = checkLabelByTask('verify_rfq');
  //   $returnArr = '';
  //   $ship_order_id = $this->input->post('id');
  //   $import_quote =  $this->cm->getVendorQuotation(' AND vq.ship_order_id = '.$ship_order_id);
  //   if(!empty($ship_order_id)){
  //        $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
  //        //$orderDetails = $this->cm->getAllshipStockOrder(' and so.ship_order_id = '.$ship_order_id,'R');
  //   }
  //   $actionType = $this->input->post('actionType');
  //   $step1 = $step2 = $step3 = $step4 = $step5 = $step6 = 'disabled'; 
  //   if($data['status']==1){
  //     $step1 = 'disabled';
  //     $step1class = 'done';
  //     $step2 = 'checked';
  //   }
  //   if($data['status']==2){
  //     $step1 = $step2 ='disabled="disabled"';   
  //     $step1class = $step2class = 'done';
  //     $step3='checked';
  //   }
  //   if($data['status']==3){
  //     $step1 = $step2 = $step3= 'disabled="disabled"';
  //     $step1class = $step2class = $step3class = 'done';
  //     $step4 = 'checked';  
  //   }
  //   if($data['status']==4){
  //     $step1 = $step2 = $step3= $step4 = 'disabled="disabled"';
  //     $step1class = $step2class = $step3class = $step4class = 'done';
  //     $step5 = 'checked';
  //     if(empty($import_quote)){
  //       $msg = '<span style="color:red">Please Import atleast one vendor quotation</span>';
  //        $buttonDis = 'disabled';
  //     }   
  //   }
  //   if($data['status']==5){   
  //     $step1 = $step2 = $step3= $step4 = $step5 ='disabled="disabled"';
  //     $step1class = $step2class = $step3class = $step4class = $step5class = 'done';
  //     $msg = '<span style="color:red">You are not able to manually update the workflow step. Please approve the vendor quote, then the workflow step will automatically update</span>';
  //     $buttonDis = 'disabled';

  //   }
  //   if($data['status']==6){   
  //     $step1 = $step2 = $step3= $step4 = $step5 = 'disabled="disabled"';
  //     $step1class = $step2class = $step3class = $step4class = $step5class = $step6class = 'done';
  //    if($user_session_data->code=='super_admin'){ 
  //     $msg = '<span style="color:red">You are not able to update the workflow step. Please send RFQ for review, then the workflow step will automatically update</span>';
  //    }
  //    else{
  //     $msg = '<span style="color:red">You will not received a review request.</span>';  
  //    }
  //     $buttonDis = 'disabled';
  //   }
  //   if($data['status']==7){
  //     $step1 = $step2 = $step3= $step4 = $step5 = 'disabled="disabled"';
  //     $step1class = $step2class = $step3class = $step4class = $step5class = $step6class = $step7class = 'done'; 
  //     $step6 = 'checked';
  //   }



  //   if(!empty($ship_order_id)){
  //       $returnArr = '
  //       <div class="animated fadeIn" id="stock_form">
  //       <div class="row">
  //       <div class="col-md-12">
  //       <form class="form-horizontal form-bordered" name="update_rfq_status" enctype="multipart/form-data" id="update_rfq_status" method="post">
  //       <div class="no-padding rounded-bottom">
  //           <strong>NOTE: Please make sure before updating the workflow step.
  //         Once the step is updated you will not be able to go to the previous step.</strong>';

  //       $returnArr .= '<div class="form-body">
  //                       <div class="row1">
  //                       <div class="form-group col-sm-12">
  //                       <label class="col-sm-12">WorkFlow Steps</label>
  //                       <div class="col-sm-12"><div class="popup-progressbar">';
  //       // if($submit_rfq_to_head){
  //          $returnArr .= '<label class="radio-inline '.$step1class.'">
  //                       <input type="radio" name="status" '.$step1.' value="1"><span>Created</span></label>';
  //          $returnArr .='<label class="radio-inline '.$step2class.'">
  //                       <input type="radio" name="status" '.$step2.' value="2"><span>Submitted To Head Office</span></label>';
  //       // }
  //       // if($verify_rfq){

  //       if($user_session_data->code=='cook' || $user_session_data->code=='captain'){

  //       }else{                 
  //        $returnArr .='<label class="radio-inline '.$step3class.'">
  //                       <input type="radio" name="status" '.$step3.' value="3"><span>RFQ Verified</span></label>';
  //       // }
  //       //  if($manage_vendor_quation){
  //          $returnArr .='<label class="radio-inline '.$step4class.'">
  //                       <input type="radio" name="status" '.$step4.' value="4"><span>Send For Quotation</span></label>';                       
  //          $returnArr .='<label class="radio-inline '.$step5class.'">
  //                       <input type="radio" name="status" '.$step5.' value="5"><span>Quotation Received</span></label>';
         
  //          $returnArr .='<label class="radio-inline '.$step6class.'">
  //                       <input type="radio" name="status" disabled value="6"><span>Quotation Approved</span></label>';
  //        }

  //         $lable = ($user_session_data->code=='cook' || $user_session_data->code=='captain') ? 'Received Review Request' :  'Send For Review ' ; 
  //         $returnArr .='<label class="radio-inline '.$step7class.'">
  //                       <input type="radio" disabled name="status" value="7"><span>'.$lable.'</span></label>';
        
  //         $returnArr .='<label class="radio-inline">
  //                       <input type="radio" name="status" '.$step6.' value="8"><span>Approved Request</span></label>';  
  //         $returnArr .= '</div></div>'.$msg.'</div></div>
  //        <input type="hidden" name="actionType" id="actionType" value="save">
  //        <input type="hidden" name="id" id="ship_order_id" value="'.$ship_order_id.'">    
  //        </form>
  //        </div>
  //        <div class="form-footer">
  //          <div class="pull-right">
  //               <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
  //              <button type="button" '.$buttonDis.' class="btn btn-success btn-slideright mr-5" onclick=submitAjax360Form("update_rfq_status","shipping/updateRfqStatus","","order_request_list")>Update Step</button>
  //          </div>
  //        </div>';
  //     }
  //   echo json_encode(array('data'=>$returnArr,'status' => 100));
  //  } 

  function updateRfqStatus(){
   checkUserSession();
   $user_session_data = getSessionData();
   $this->load->model('email_manager');
   $this->em = $this->email_manager;
   $status = $this->input->post('status');
   $actionType = $this->input->post('actionType');
   $ship_order_id = $this->input->post('id');
   $orderDetails = $this->cm->getAllshipStockOrder(' and so.ship_order_id = '.$ship_order_id,'R');
   $returnArr['status'] = 100;
   $rfq_no = $orderDetails[0]->rfq_no;
   $ship_details = getCustomSession('ship_details');

   if($actionType == 'save'){ 
     $dataArr['status'] = ($status == 0)?$orderDetails[0]->status:$status;

     if($dataArr['status']==2){
       // $this->db->update('notification',array('is_read'=>1),array('entity_type'=>"RFQ",'entity_id'=>$ship_order_id));
        $whereEm = ' AND nt.code = "rfq_submit"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
          $cnoteArr['date'] = date('Y-m-d H:i:s');
          $cnoteArr['is_for_master'] = 1;
          $cnoteArr['row_id'] = $ship_order_id;
          $cnoteArr['entity'] = 'rfq';
          $cnoteArr['ship_id'] = $ship_details['ship_id'];
          $cnoteArr['title'] = $templateData->title;
          $cnoteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body); 
         $this->um->add_notify($cnoteArr);

         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['row_id'] = $ship_order_id;
                   $noteArr['entity'] = 'rfq';
                   $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }

     }


     if($dataArr['status']==3){
        $whereEm = ' AND nt.code = "rfq_verify"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
            $noteArr['is_for_master'] = 1;
            $noteArr['date'] = date('Y-m-d H:i:s');
            $noteArr['ship_id'] = $ship_details['ship_id'];
            $noteArr['row_id'] = $ship_order_id;
            $noteArr['entity'] = 'rfq';
            $noteArr['title'] = $templateData->title;
            $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body);       
            $this->um->add_notify($noteArr);
        } 
     }

     if($user_session_data->code=='captain'){
       if($dataArr['status']==8){ 
        $whereEm = ' AND nt.code = "request_approved"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){ 
        $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['row_id'] = $ship_order_id;
                   $noteArr['entity'] = 'rfq';
                   $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##','##captain_name##'),array($rfq_no,ucwords($ship_details['ship_name']),ucwords($user_session_data->first_name.' '.$user_session_data->last_name)),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }
       }
     }
 }
 
    //$dataArr['status'] = $status;
     $this->db->update('ship_order',$dataArr,array('ship_order_id'=>$ship_order_id));
     $returnArr['returnMsg'] = 'RFQ WorkFlow Step updated successfully.'; 
     $returnArr['status'] = 200;
   }
   echo json_encode($returnArr);
 } 

  
  function add_rfq_details(){
      checkUserSession();
      $user_session_data = getSessionData(); 
      $returnArr['status'] = 100;
      $this->load->model('email_manager');
      $this->em = $this->email_manager; 
      $ship_details = getCustomSession('ship_details');
      $ship_id = $ship_details['ship_id'];
      $actionType = $this->input->post('actionType');
      $ship_order_id = $this->input->post('id');
      $store_product_arr = array();
      $store_product_data = array();
      if(!empty($ship_order_id)){
         $rfq_data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
         // print_r($rfq_data);die;
         $dataA = unserialize($rfq_data['json_data']);
         $stockArr = array();
         $stockArr['ship_order_id'] = $rfq_data['ship_order_id'];
         if(!empty($dataA)){
           for ($i=0; $i <count($dataA) ; $i++) { 
             $stockArr['qty_product_'.$dataA[$i]['product_id']] = $dataA[$i]['quantity'];
             $stockArr['remark_'.$dataA[$i]['product_id']] = $dataA[$i]['remark'];
             $store_product_arr[] = $dataA[$i]['product_id'];
            } 
         }
         $stockArr['ship_order_id'] = $ship_order_id;
         $stockArr['no_of_day'] = $rfq_data['no_of_day'];
         $stockArr['no_of_people'] = $rfq_data['no_of_people'];
         $stockArr['requisition_type'] = $rfq_data['requisition_type'];
         $stockArr['port_id'] = $rfq_data['port_id'];

         if(!empty($store_product_arr)){
            $store_product_data = $this->mp->getAllProduct( ' and p.product_id IN('.implode(',',$store_product_arr).') AND  p.`status`=1 ' ,'R');
         }
       }
 
     if($actionType=='save'){
        if($this->rfq_validation()){

         $products = $this->mp->getAllProduct( ' AND  p.`status`=1 ' ,'R');    
         $no_of_day = trim($this->input->post('no_of_day'));
         $no_of_people = trim($this->input->post('no_of_people'));
         $dataArr['no_of_day'] = (!empty($no_of_day)) ? $no_of_day:0;
         $dataArr['no_of_people'] = (!empty($no_of_people)) ? $no_of_people:0;
         $dataArr['requisition_type'] = trim($this->input->post('requisition_type'));
         $dataArr['port_id'] = trim($this->input->post('port_id'));
         if($user_session_data->code == 'cook' || $user_session_data->code == 'captain'){
           $dataArr['status'] = 1;
         }
         else{
            $dataArr['status'] = 2;
         }

         if(!empty($ship_order_id)){
          $requisition_type = $this->input->post('requisition_type');
          $dataArr['updated_by'] = $user_session_data->user_id;
          $dataArr['updated_on'] = date('Y-m-d H:i:s'); 
          $this->db->delete('ship_order_details',' ship_order_id ='.$ship_order_id);  
              if($requisition_type=='provision'){
                  $batch = array();
                  if(!empty($products)){
                      foreach ($products as $row) {
                        $quantity = ($this->input->post('qty_product_'.$row->product_id)) ? $this->input->post('qty_product_'.$row->product_id) : 0; 
                        $remark = $this->input->post('remark_'.$row->product_id);
                        if(!empty($quantity)){
                             $batch[] = array('ship_order_id'=>$ship_order_id,'product_id'=>$row->product_id,'quantity'=>$quantity,'remark'=>$remark);
                        }
                    }
                 }
             }else{
                $batch = array();
                $ttl_prdct = $this->input->post('ttl_prdct');
                $item_name = $this->input->post('item_name');
                $item_unit = $this->input->post('item_unit');
                $item_qty = $this->input->post('item_qty');
                $item_remark = $this->input->post('item_remark');
                if(!empty($ttl_prdct)){
                    for($i=0;$i<$ttl_prdct;$i++){
                        if(!empty($item_name[$i]) && !empty($item_unit[$i]) && !empty($item_qty[$i])){
                            $pdata = $this->mp->getAllProductbyid(" And p.product_name = '".$item_name[$i]."'");
                            if(!empty($pdata)){
                                $batch[] = array('ship_order_id'=>$ship_order_id,'product_id'=>$pdata->product_id,'quantity'=>$item_qty[$i],'remark'=>$item_remark[$i]);
                            }else{
                                $p_c = $this->mp->getAllProductCategory(' AND pc.code = "custom_product"','R','','','ORDER BY pc.sequence ASC');
                                $p_l = $this->mp->getAllProduct(' and p.status=1','R',1,0,'ORDER BY p.item_no DESC');
                                $p_data = array('product_name'=>$item_name[$i],'product_category_id'=>$p_c[0]->product_category_id,'item_no'=>($p_l[0]->item_no+1),'unit'=>strtoupper($item_unit[$i]),'added_on'=>date('Y-m-d H:i:s'),'is_custom_product'=>1);
                                $product_id = $this->mp->addproduct('product',$p_data);
                                $batch[] = array('ship_order_id'=>$ship_order_id,'product_id'=>$product_id,'quantity'=>$item_qty[$i],'remark'=>$item_remark[$i]);
                            }
                        }
                    }
                }
                $store_product_ids = $this->input->post('store_product_ids');
                if(!empty($store_product_ids)){
                    foreach ($store_product_ids as $spi) {
                        $quantity = $this->input->post('qty_product_'.$spi);
                        $remark = $this->input->post('remark_'.$spi);
                        if(!empty($quantity)){
                             $batch[] = array('ship_order_id'=>$ship_order_id,'product_id'=>$spi,'quantity'=>$quantity,'remark'=>$remark);
                        }
                    }
                }
             }
              $this->db->insert_batch('ship_order_details',$batch);     
              $json_data = serialize($batch);
              $dataArr['json_data'] = $json_data;

              $this->cm->edit_ship_order_stock($dataArr,' ship_order_id ='.$ship_order_id);
              $returnArr['status'] = 200;  
               $whereEm = ' AND nt.code = "rfq_update"';
               $templateData = $this->um->getNotifyTemplateByCode($whereEm);
              if($user_session_data->code != 'cook' || $user_session_data->code != 'captain'){
                $noteArr['is_for_master'] = 1;
                $noteArr['date'] = date('Y-m-d H:i:s');
                $noteArr['ship_id'] = $ship_id;
                $noteArr['title'] = $templateData->title;
                $noteArr['row_id'] = $ship_order_id;
                $noteArr['entity'] = 'rfq';
                $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_data['rfq_no'],ucwords($ship_details['ship_name'])),$templateData->body);       
                $this->um->add_notify($noteArr);
                $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
                if(!empty($roles)){
                  foreach ($roles as $row) {
                   $user_data = $this->em->getUserByRoleID($row->role_id);
                     if(!empty($user_data)){
                       foreach ($user_data as $val) {
                        $notArr['date'] = date('Y-m-d H:i:s');
                        $notArr['ship_id'] = $ship_id;
                        $notArr['title'] = $templateData->title;
                        $notArr['row_id'] = $ship_order_id;
                        $notArr['entity'] = 'rfq';
                        $notArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_data['rfq_no'],ucwords($ship_details['ship_name'])),$templateData->body);  
                        $notArr['user_id'] = $val->user_id;     
                        $this->um->add_notify($notArr);
                       }
                      }
                    }
                 }
             }

              $returnArr['returnMsg'] = 'RFQ Updated successfully.';
         }
         else
         {         
            $dataArr['ship_id'] = $ship_id;    
            $dataArr['created_on'] = date('Y-m-d H:i:s');
            $dataArr['created_by'] = $user_session_data->user_id;
            $dataArr['notify_date'] = date('Y-m-d H:i:s');
             $order_id = $this->cm->add_ship_order_stock($dataArr);
             $requisition_type = $this->input->post('requisition_type');
            if($requisition_type=='provision'){
                $batch = array();
                if(!empty($products)){
                  foreach ($products as $row) {
                   
                    $quantity = ($this->input->post('qty_product_'.$row->product_id)) ? $this->input->post('qty_product_'.$row->product_id) : 0; 
                    $remark = $this->input->post('remark_'.$row->product_id);
                    if(!empty($quantity)){
                         $batch[] = array('ship_order_id'=>$order_id,'product_id'=>$row->product_id,'quantity'=>$quantity,'remark'=>$remark);
                    }
                  }
                }
            }else{
                $batch = array();
                $ttl_prdct = $this->input->post('ttl_prdct');
                $item_name = $this->input->post('item_name');
                $item_unit = $this->input->post('item_unit');
                $item_qty = $this->input->post('item_qty');
                $item_remark = $this->input->post('item_remark');
                if(!empty($ttl_prdct)){
                    for($i=0;$i<$ttl_prdct;$i++){
                        
                        if(!empty($item_name[$i]) && !empty($item_unit[$i]) && !empty($item_qty[$i])){
                            $pdata = $this->mp->getAllProductbyid(" And p.product_name = '".$item_name[$i]."'");
                            if(!empty($pdata)){
                                $batch[] = array('ship_order_id'=>$order_id,'product_id'=>$pdata->product_id,'quantity'=>$item_qty[$i],'remark'=>$item_remark[$i]);
                            }else{
                                $p_c = $this->mp->getAllProductCategory(' AND pc.code = "custom_product"','R','','','ORDER BY pc.sequence ASC');
                                $p_l = $this->mp->getAllProduct(' and p.status=1','R',1,0,'ORDER BY p.item_no DESC');
                                $p_data = array('product_name'=>$item_name[$i],'product_category_id'=>$p_c[0]->product_category_id,'item_no'=>($p_l[0]->item_no+1),'unit'=>strtoupper($item_unit[$i]),'added_on'=>date('Y-m-d H:i:s'),'is_custom_product'=>1);
                                $product_id = $this->mp->addproduct('product',$p_data);
                                $batch[] = array('ship_order_id'=>$order_id,'product_id'=>$product_id,'quantity'=>$item_qty[$i],'remark'=>$item_remark[$i]);
                            }
                        }
                    }
                }
            }

             
            if($ship_details['ship_type']==1){ 
             if($requisition_type=='provision'){
               $sn = getSerialNum(1,'rfq','provision')+1; 
               if($sn>9 && $sn<99){
                $rfq_no = 'ONS-'.date('Y').'-GA-0'.$sn;
               }
               elseif($sn<9){
                $rfq_no = 'ONS-'.date('Y').'-GA-00'.$sn;
               }
               else{
                $rfq_no = 'ONS-'.date('Y').'-GA-'.$sn;
               }
               updateSerialNum(1,'rfq',$sn,'provision');
             }
             else{
               $sn = getSerialNum(1,'rfq','bonded_store')+1;  
               if($sn>9 && $sn<99){
                $rfq_no = 'ONS-'.date('Y').'-BGA-0'.$sn;
               }
               elseif($sn<9){
                $rfq_no = 'ONS-'.date('Y').'-BGA-00'.$sn;
               }
               else{
                $rfq_no = 'ONS-'.date('Y').'-BGA-'.$sn;
               }
               updateSerialNum(1,'rfq',$sn,'bonded_store');
             }
            }
            else{
              $sn = getSerialNum(2,'rfq')+1;
               if($sn>9 && $sn<99){
                $rfq_no = 'ONS-'.date('Y').'-NGA-0'.$sn;
               }
               elseif($sn<9){
                $rfq_no = 'ONS-'.date('Y').'-NGA-00'.$sn;
               }
               else{
                $rfq_no = 'ONS-'.date('Y').'-BGA-'.$sn;
               }
              updateSerialNum(2,'rfq',$sn);
            }

              $this->db->insert_batch('ship_order_details',$batch);         
              $json_data = serialize($batch);
              $this->cm->edit_ship_order_stock(array('rfq_no'=>$rfq_no,'json_data'=>$json_data),' ship_order_id ='.$order_id);
              $this->session->unset_userdata('rfq_data_'.$ship_id);
              $returnArr['status'] = 200;  
              $returnArr['returnMsg'] = 'RFQ generated successfully.';
             
               $whereEm = ' AND nt.code = "rfq_created"';
               $templateData = $this->um->getNotifyTemplateByCode($whereEm);  
               // if($user_session_data->code != 'cook' || $user_session_data->code != 'captain'){
                $noteArr['is_for_master'] = 1;
                $noteArr['date'] = date('Y-m-d H:i:s');
                $noteArr['ship_id'] = $ship_id;
                $noteArr['title'] = $templateData->title;
                $noteArr['row_id'] = $order_id;
                $noteArr['entity'] = 'rfq';
                $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body);       
                $this->um->add_notify($noteArr);
                $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
                if(!empty($roles)){
                  foreach ($roles as $row) {
                   $user_data = $this->em->getUserByRoleID($row->role_id);
                     if(!empty($user_data)){
                       foreach ($user_data as $val) {
                        $notArr['date'] = date('Y-m-d H:i:s');
                        $notArr['ship_id'] = $ship_id;
                        $notArr['title'] = $templateData->title;
                        $notArr['row_id'] = $order_id;
                        $notArr['entity'] = 'rfq';
                        $notArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body);  
                        $notArr['user_id'] = $val->user_id;     
                        $this->um->add_notify($notArr);
                       }
                      }
                    }
                 }
            //  }
            // else{
            //     $noteArr['is_for_master'] = 1;
            //     // $noteArr['is_for_cook'] = 1;
            //     $noteArr['date'] = date('Y-m-d H:i:s');
            //     $noteArr['ship_id'] = $ship_id;
            //     $noteArr['title'] = $templateData->title;
            //     $noteArr['long_desc'] = str_replace(array('##rfq_no##','##ship_name##'),array($rfq_no,ucwords($ship_details['ship_name'])),$templateData->body);         
            //     $this->um->add_notify($noteArr);
            // }
          
           }   
        }
      }

     // $dataArr = $this->cm->get_current_stock(' AND cs.ship_id ='.$ship_id);
     $dataArr = $this->cm->monthly_stock_details(' AND ms.ship_id ='.$ship_id.' AND ms.month = '.date('m').' AND ms.year ='.date('Y')); 
     // $stock_used = array();
        if(!empty($dataArr)){
           foreach ($dataArr as $dt) {
              $stock_used[$dt->product_id] = array('total_stock'=>$dt->total_stock,'used_stock'=>$dt->used_stock,'unit_price'=>$dt->unit_price,'available_stock'=>$dt->available_stock);   
           }  
        } 

     //$products_category = $this->mp->getAllProductCategory('And pc.status=1 ','R','','',' ORDER BY pc.sequence ASC');
     $products_category = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);
     $productArr = [];
     if(!empty($products_category)){
         foreach ($products_category as $row) {
           if($row->code=='misc_items'){
            $products = $this->mp->getAllShipWiseProduct(' AND p.status = 1 AND smp.ship_id = '.$ship_id);
            $productArr[$row->category_name][] = $products;             
           }
           else{
            $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_category_id = '.$row->product_category_id,'R','','',' ORDER BY p.item_no ASC');  
            $productArr[$row->category_name][] = $products;             
           }

         } 
      }

     $vars['stock_used'] = $stock_used;
     // echo '<pre>';
     // print_r($stock_used);die;
     $vars['productArr'] = $productArr;
     // $d = getImportData('rfq_data_'.$ship_id);
     // print_r($d);die;
     // $vars['all_ports'] = $this->cm->getAllPortList(' and pa.status = 1 and sp.ship_id='.$ship_id.' and sp.date >="'.date('Y-m-d').'"','R');
     $vars['all_ports'] = $this->cm->getAllPortList(' and sp.ship_id='.$ship_id.' and sp.date >="'.date('Y-m-d').'"','R');
     // echo $this->db->last_query();die;
     $stockArr = ($stockArr) ? $stockArr : getImportData('rfq_data_'.$ship_id); 
     $vars['dataArr'] = ($actionType=='save') ? $this->input->post():$stockArr ; 
     if($actionType=='save' || !empty($ship_order_id)){
     }else{
        $vars['dataArr']['requisition_type']  = getCustomSession('requisition_type'); 
     }
     $vars['store_product_data'] = $store_product_data;
     $vars['ship_order_id'] = $ship_order_id;
     $vars['group_products'] = $this->mp->getAllProductGroup($where,'R');
     $data = $this->load->view('add_rfq_details',$vars,true);
     $returnArr['data'] = $data;  
     echo json_encode($returnArr);
   }

   function rfq_validation(){
    
    $this->form_validation->set_rules('requisition_type','Requisition Type','trim|required');
    $this->form_validation->set_rules('port_id','Port','trim|required');
    $requisition_type = $this->input->post('requisition_type');
    
    if($requisition_type=='provision'){
        $this->form_validation->set_rules('no_of_day','No of Day','trim|required|is_natural_no_zero');
    $this->form_validation->set_rules('no_of_people','No of People','trim|required|is_natural_no_zero');
        $products = $this->mp->getAllProduct( ' AND  p.`status`=1 ' ,'R');
         $i = 0;
         if($products){ 
           foreach ($products as $row) {
             $quantity = $this->input->post('qty_product_'.$row->product_id); 
               if(!empty($quantity)){
                $i++;         
               }  
           }
         } 

         if($i<=0){
             $this->form_validation->set_rules('product_id','Product','trim|required',array('required'=>'Please enter atleast one product quantity.'));
           return  $this->form_validation->run();
         }
    }else{
        $ttl_prdct = $this->input->post('ttl_prdct');
        $store_product_ids = $this->input->post('store_product_ids');
        $item_name = $this->input->post('item_name');
        $item_unit = $this->input->post('item_unit');
        $item_qty = $this->input->post('item_qty');
        $item_remark = $this->input->post('item_remark');
        $prdct_err_exist = true;
        if(!empty($ttl_prdct)){
            for($i=0;$i<$ttl_prdct;$i++){
                
                if(!empty($item_name[$i]) || !empty($item_unit[$i]) || !empty($item_qty[$i])){
                    $this->form_validation->set_rules('item_name['.$i.']','item description.','trim|required');
                    $this->form_validation->set_rules('item_unit['.$i.']','item unit.','trim|required');
                    $this->form_validation->set_rules('item_qty['.$i.']','item quantity','trim|required');
                    $prdct_err_exist = false;
                }
            }
        }
        if(!empty($store_product_ids)){
            foreach ($store_product_ids as $spi) {
                $quantity = $this->input->post('qty_product_'.$spi);
                $remark = $this->input->post('remark_'.$spi);
                if(!empty($quantity)){
                    $prdct_err_exist = false;
                }
            }
        }

        if($prdct_err_exist){
           $this->form_validation->set_rules('cst_product_id','Product','trim|required',array('required'=>'Please enter atleast one item detail.'));
           return  $this->form_validation->run();  
        }
    }
    return $this->form_validation->run();
   }

 function review_rfq(){
    checkUserSession();
     $user_session_data = getSessionData();
     $ship_details = getCustomSession('ship_details');
     $returnArr['status'] = 100;
     $actionType = $this->input->post('actionType');
     $ship_order_id = $this->input->post('id');
     if(!empty($ship_order_id)){
      $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
      $arrData =  unserialize($data['json_data']);
         $stockArr = array();
         $stockArr['ship_order_id'] = $data['ship_order_id'];
         if(!empty($arrData)){
           for ($i=0; $i <count($arrData) ; $i++) { 
              $stockArr['qty_'.$arrData[$i]['product_id']] = $arrData[$i]['quantity'];
              $stockArr['remark_'.$arrData[$i]['product_id']] = $arrData[$i]['remark'];
            } 
         }
         $stockArr['no_of_day'] = $data['no_of_day'];
         $stockArr['no_of_people'] = $data['no_of_people'];
         $stockArr['requisition_type'] = $data['requisition_type'];
         $stockArr['port_id'] = $data['port_id'];

     }

    if($actionType=='save'){
      if($this->review_rfq_validation()){ 
        if(!empty($ship_order_id)){
                $no_of_day = trim($this->input->post('no_of_day'));
                $no_of_people = trim($this->input->post('no_of_people'));
                $port_id = trim($this->input->post('port_id'));
                $json_data = array();
                $this->db->delete('ship_order_details',array('ship_order_id'=>$ship_order_id));
                for($i=0; $i<count($arrData); $i++) { 
                      $quantity = $this->input->post('qty_'.$arrData[$i]['product_id']);  
                      $remark = $this->input->post('remark_'.$arrData[$i]['product_id']);  
                      $json_data[] = array('ship_order_id'=>$ship_order_id,'product_id'=>$arrData[$i]['product_id'],'quantity'=>$quantity,'remark'=>$remark);
                }   
               $this->db->insert_batch('ship_order_details',$json_data);
               $this->db->update('ship_order',array('json_data'=>serialize($json_data),'updated_on'=>date('Y-m-d H:i:s'),'updated_by'=>$user_session_data->user_id,'no_of_day'=>$no_of_day,'no_of_people'=>$no_of_people,'port_id'=>$port_id),array('ship_order_id'=>$ship_order_id));          
         }

        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'RFQ reviewed successfully'; 

          $whereEm = ' AND em.template_code = "rfq_updated"';
          $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
          //echo $this->db->last_query();die;
          if(!empty($emailTemplateData)){
            $whereUser = ' and r.code = "super_admin"';
            $superAdminData = $this->um->getuserdatabyid($whereUser);
            $captainData = $this->um->getuserdatabyid(' and u.user_id = '.$ship_details['captain_user_id']);
            $cookData = $this->um->getuserdatabyid(' and u.user_id = '.$ship_details['cook_user_id']);
            if(!empty($superAdminData)){
                $subject = $emailTemplateData->email_subject;
                $body = str_replace(array('##username##'),array($superAdminData->user_name),$emailTemplateData->email_body);
                $to =  $superAdminData->email;
                $this->um->sendMail($to,$subject,$body);
            }
            if(!empty($captainData)){
                $subject = $emailTemplateData->email_subject;
                $body = str_replace(array('##username##'),array($captainData->user_name),$emailTemplateData->email_body);
                $to =  $captainData->email;
                $this->um->sendMail($to,$subject,$body);
            }
            if(!empty($cookData)){
                $subject = $emailTemplateData->email_subject;
                $body = str_replace(array('##username##'),array($cookData->user_name),$emailTemplateData->email_body);
                $to =  $cookData->email;
                $this->um->sendMail($to,$subject,$body);
            }
          }
       }
     }

     $curArr = $this->cm->get_current_stock(' AND cs.ship_id ='.$ship_details['ship_id']);
     $stock_used = array();
        if(!empty($curArr)){
           foreach ($curArr as $dt) {
              $stock_used[$dt->product_id] = array('total_stock'=>$dt->total_stock,'used_stock'=>$dt->used_stock,'unit_price'=>$dt->unit_price);   
           }  
        } 
     
     if(!empty($arrData)){
        $productArr = []; 
        if(!empty($arrData)){
                for ($i=0; $i <count($arrData) ; $i++) {
                   $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$arrData[$i]['product_id']); 
                   $avalible_stock =  ($stock_used[$product['product_id']]['total_stock'] - $stock_used[$product['product_id']]['used_stock']);
                   $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],
                      'product_category_id'=>$product['product_category_id'],
                      'product_name'=>$product['product_name'],
                      'product_id'=>$product['product_id'],
                      'quantity'=>$arrData[$i]['quantity'],
                      'remark'=>$arrData[$i]['remark'],
                      'unit'=>$product['unit'],
                      'item_no'=>$product['item_no'],
                      'sequence'=>$arrData['sequence'],
                      'last_count_qty'=>$avalible_stock,
                      'group_name'=>$product['group_name']
                   );           
                }
        }
      ksort($productArr);
     }
    //print_r($productArr);die;
     $vars['productArr'] = $productArr;
      $vars['all_ports'] = $this->cm->getAllPortList(' and sp.ship_id='.$ship_details['ship_id'].' and sp.date>="'.date('Y-m-d').'"','R');
     $vars['dataArr'] = ($stockArr) ? $stockArr : $this->input->post();
     $vars['dataArr']['ship_order_id'] = $data['ship_order_id'];
     $vars['group_products'] = $this->mp->getAllProductGroup($where,'R');
     $data = $this->load->view('review_rfq',$vars,true);
     $returnArr['data'] = $data;
     echo json_encode($returnArr);
  } 

  function review_rfq_validation(){
    $ship_order_id = $this->input->post('id');
    $this->form_validation->set_rules('no_of_day','No Of Days','trim|required');
    $this->form_validation->set_rules('no_of_people','No Of People','trim|required');
    $this->form_validation->set_rules('port_id','Port','trim|required');

    if(!empty($ship_order_id)){
        $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
        $arrData =  unserialize($data['json_data']);
       for ($i=0; $i <count($arrData) ; $i++) { 
          $this->form_validation->set_rules('qty_'.$arrData[$i]['product_id'],'QTY','trim|required|greater_than[0]');       
       }        
    }
    return $this->form_validation->run();
  }


  function download_updated_rfq($ship_order_id=''){
     checkUserSession();
     $ship_order_id = base64_decode($ship_order_id);
     $data = (array) $this->cm->getQuotedDetails(' AND vqa.ship_order_id = '.$ship_order_id);
     $dataArr = unserialize($data['quote_json']);

     $file_name = 'updated'.$data['rfq_no'].".csv";
     $productArr = []; 
     if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['revised_qty'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
       } 
    ksort($productArr);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$file_name);
        $fp = fopen('php://output', 'w');
      $field_array = array('Item No.','Description','Unit','QTY');
       fputcsv($fp, $field_array);     
      if(!empty($productArr)){
        foreach ($productArr as $key => $rows) {
         foreach($rows as $category => $products){
             $field_array = array('',$category);  
             fputcsv($fp, $field_array);
              for ($i=0; $i <count($products) ; $i++) { 
                $field_array = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity']);
                 fputcsv($fp, $field_array); 
             }
          }
        }
      }
     fclose($fp);
     exit;
  }

  function download_updated_rfq_xls($ship_order_id=''){
     checkUserSession();
     $ship_order_id = base64_decode($ship_order_id);
     $data = (array) $this->cm->getQuotedDetails(' AND vqa.ship_order_id = '.$ship_order_id);
     $dataArr = unserialize($data['quote_json']);
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = 'updated'.$data['rfq_no'].".xlsx";
     $productArr = []; 
     if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['revised_qty'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
    } 
    
    $listColumn     = array();
    // $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);
    // $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'A1:A1','font'=>array(),'alignment'=>$align)));
    // $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:25','B:25','C:30','D:18','E:18','F:18','G:18','H:18','I:18','J:18','K:25','L:25'));
    // $listColumn[] = array('addImage'=>'1','coordinates'=>'A1','height'=>80);
    // $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
    //                 'color' => array('rgb' => '5B0A91'),
    //                 'size'  => 46,
    //                 'name'  => 'Calibri')
    //                 ),'cellArray'=>array('B1'));

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
                      ) 
                    ),'cellArray'=>array('A7:E7'));

    $arrayHeaderData = array('Item No.','Description','Unit','QTY');
    $arrayData   = array();
    $arrayData[2] = array('','One North Ships');
    $arrayData[7] = $arrayHeaderData;
     $k = 7;
    ksort($productArr);
    if(!empty($productArr)){
        foreach ($productArr as $key => $rows) {
         foreach($rows as $category => $products){
             $arrayData[] = array('',$category);  
              $k++;
              for ($i=0; $i <count($products) ; $i++) { 
                $k++;
                $arrayData[] = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity']);
             }
          }
        }
    }
    $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN'))
     );       
    $arrayBundleData['listColumn'] = $listColumn;
    $arrayBundleData['arrayData'] = $arrayData;

    $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'RFQ');
    readfile(FCPATH.'uploads/sheets/'.$fileName);
    unlink(FCPATH.'uploads/sheets/'.$fileName);
    exit; 
  }

  function download_rfq($ship_order_id=''){
     checkUserSession();
     $ship_order_id = base64_decode($ship_order_id);
     $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
     $dataArr = unserialize($data['json_data']); 
     $file_name = $data['rfq_no'].".csv";
     $productArr = [];
      if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['quantity'],'remark'=>$dataArr[$i]['remark'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
       } 
      ksort($productArr);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$file_name);
        $fp = fopen('php://output', 'w');
      $field_array = array('Item No.','Description','Unit','QTY','Remark');
       fputcsv($fp, $field_array);     
      if(!empty($productArr)){
        foreach ($productArr as $key => $rows) {
         foreach($rows as $category => $products){
             $field_array = array('',$category);  
             fputcsv($fp, $field_array);
              for ($i=0; $i <count($products) ; $i++) { 
                $field_array = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity'],$products[$i]['remark']);
                 fputcsv($fp, $field_array); 
             }
          }
        }
      }
     fclose($fp);
     exit;  
  } 

  function download_rfq_xls($ship_order_id=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $ship_order_id = base64_decode($ship_order_id);
    $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
    $dataArr = unserialize($data['json_data']); 
    $this->load->library('Excelreader');
    $excel  = new Excelreader();
    $fileName = $data['rfq_no'].'.xlsx';
    if($user_session_data->code=='super_admin'){
     $arrayHeaderData = array('Item No.','Description','Unit','Quantity','Remark');
    }
    else{
     $arrayHeaderData = array('Item No.','Description','Unit','Quantity');   
    }
    $productArr = [];
      if(!empty($dataArr)){
         $productArr = array();
         for ($i=0; $i <count($dataArr) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
            $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['quantity'],'remark'=>$dataArr[$i]['remark'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
         }
       } 
      
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
        if($user_session_data->code=='super_admin'){
          $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:E7'));
        }
        else{
           $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:D7'));   
        }
                     
        $arrayData   = array();
        $arrayData[2] = array('','One North Ships');
        $arrayData[7] = $arrayHeaderData;
        ksort($productArr);
        $k = 7;
        if(!empty($productArr)){
        foreach ($productArr as $key => $rows) {
         foreach($rows as $category => $products){
           $arrayData[] = array('',$category);    
            $k++;
              for ($i=0; $i <count($products) ; $i++) {
              $k++; 
                if($user_session_data->code=='super_admin'){
                  $arrayData[] = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity'],$products[$i]['remark']);
                }
                else{
                  $arrayData[] = array($products[$i]['item_no'],ucfirst($products[$i]['product_name']),strtoupper($products[$i]['unit']),$products[$i]['quantity']);
                }
             }
          }
        }
      }
     
     if($user_session_data->code=='super_admin'){
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN'))
       );    
     }
     else{
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:D'.$k,'border'=>'THIN'))
     );    
   }
           
     //print_r($arrayData);die;
    
     $arrayBundleData['listColumn'] = $listColumn;
     $arrayBundleData['arrayData'] = $arrayData;

     $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'RFQ');
     readfile(FCPATH.'uploads/sheets/'.$fileName);
     unlink(FCPATH.'uploads/sheets/'.$fileName);
     //print_r($objWriter);die;
     exit;  
  }  
  
 function import_vendor_quatation(){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('user_manager');
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
     $this->form_validation->set_rules('vendor_id','Vendor','trim|required|callback_check_already_quote');
     $this->form_validation->set_rules('img','','callback_file_check');   
      if($this->form_validation->run()){
        $vendor_id = trim($this->input->post('vendor_id'));
        $ship_order_id = trim($this->input->post('id'));
        $mime = get_mime_by_extension($_FILES['img']['name']);
        //echo $mime;die;
         if(!empty($_FILES['img']['name'])) {
           $file = $_FILES['img']['name'];
           $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
           $upload_data = doc_upload($file, 'sheets');
         }

        $dataArr['vendor_id'] = $vendor_id;
        $dataArr['ship_order_id'] = $ship_order_id;   
        $full_path = FCPATH.'uploads/sheets/'.$upload_data['file_name'];
         $tmpArr = array();
        $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
         $arrData = unserialize($data['json_data']);
         //print_r($arrData);die;
         if(!empty($arrData)){
         $product_id_arr = array();
           for ($i=0; $i <count($arrData) ; $i++) { 
             $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$arrData[$i]['product_id']);
              // $product_name = str_replace(array(' ',',','/','(',')','-','.','%','&'),array('_','','','','','','',''),trim($product['product_name']));
              //   $product_name = strtolower($product_name);
               $product_id_arr[$product['item_no']] = $product['product_id'];
           }
        }

        if($mime=='text/x-comma-separated-values'){ 
        $csvArr = array(); 
        $file = fopen($full_path,"r");
         $i = 0;
         while(!feof($file)){
            $csvArr[$i]=fgetcsv($file);
                $i++;
            }
          
          fclose($file);
          unset($csvArr[0]);
          $k =0;
          for ($i=0; $i <count($csvArr) ; $i++){
            $k++;
             $name = str_replace(array(' ',',','/','(',')','-','.','%','&'),array('_','','','','','','',''),trim($csvArr[$k][1]));
             $name = strtolower($name);
             $qty = str_replace(array('$'),array(''),$csvArr[$k][3]);
             $unit_price = str_replace(array('$'),array(''),$csvArr[$k][5]);
             $product_id =  $product_id_arr[$name];
               if(!empty($product_id) && !empty($qty) && !empty($unit_price)){
                   $tmpArr[$product_id] = array('quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty * $unit_price),'remark'=>$csvArr[$k][7]); 
                }
           } 

      }
      elseif($mime=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
         
          $this->load->library('Excelreader');
          $excel = new Excelreader();
          $objWriter = $excel->readExcel($full_path,'xlsx');
          unset($objWriter[1]);
          $objWriter = array_values($objWriter);
          for ($i=0; $i <count($objWriter) ; $i++){
              
               // $name = str_replace(array(' ',',','/','(',')','-','.','%','&'),array('_','','','','','','',''),trim($objWriter[$i]['B']));
               // $name = strtolower($name);
              $product_id = $product_id_arr[$objWriter[$i]['C']];
              $qty = $objWriter[$i]['D'];
              $unit_price = str_replace(array('$'),array(''),$objWriter[$i]['F']);
              if(!empty($product_id) && !empty($qty) && !empty($unit_price)){ 
                $tmpArr[$product_id] = array('quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty * $unit_price),'remark'=>$objWriter[$i]['H']); 
              }
              //print_r($product_id_arr);die;
          }
      }
      elseif($mime=='application/vnd.ms-excel'){
           $this->load->library('Excelreader');
          $excel = new Excelreader();
          $objWriter = $excel->readExcel($full_path,'xls');
          unset($objWriter[1]);
          $objWriter = array_values($objWriter);
          for ($i=0; $i <count($objWriter) ; $i++){
              // $name = strtolower(str_replace(array(' ',','),array('_',''),trim($objWriter[$i]['B'])));
             if(!empty($objWriter[$i]['C'])){
              // $name = str_replace(array(' ',',','/','(',')','-','.','%','&'),array('_','','','','','','',''),trim($objWriter[$i]['B']));
              // $name = strtolower($name);
              $product_id = $product_id_arr[$objWriter[$i]['C']];
              $qty = $objWriter[$i]['D'];
              $unit_price = str_replace(array('$','/'),array('',''),$objWriter[$i]['F']);
              $tmpArr[$product_id] = array('quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty * $unit_price),'remark'=>$objWriter[$i]['H']); 
             }
          }
      }

       setImportSession('vendor_quatation',array('basic'=>$dataArr,'dataArr'=>$tmpArr));
        unlink($full_path);
        $returnArr['status'] = 200;
      }   
    }
    $vars['vendors'] = $this->user_manager->getallVendor(' AND u.status= 1','R');
    $vars['dataArr'] = $this->input->post();
    $data = $this->load->view('import_vendor_quote',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr); 
  }

  function preview_import_quote($type=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $import_data = getImportData('vendor_quatation');
    $this->load->model('email_manager');
    $this->em  = $this->email_manager;
    // echo '<pre>';print_r($import_data);die;
    $ship_order_id = $import_data['basic']['ship_order_id'];
    $vendor_id = $import_data['basic']['vendor_id'];
    $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
    $arrData = unserialize($data['json_data']);
    $actionType = $this->input->post('actionType');
    $returnArr['status'] = 100;
     if($actionType=='save'){
        if($this->validate_preview_import_quote($ship_order_id)){
         $lead_time = trim($this->input->post('lead_time'));
          
          if(empty($import_data['basic']['type'])){
             $dataArr['vendor_id'] = $vendor_id;
             $dataArr['ship_order_id'] = $ship_order_id;
             $dataArr['added_by'] = $user_session_data->user_id;
             $dataArr['added_on'] = date('Y-m-d h:i:s');
            // $dataArr['lead_time'] = convertDate($this->input->post('lead_time'),'','Y-m-d H:i:s');
             $vendor_quote_id = $this->cm->add_vendor_quote($dataArr);

            }
            else{
             $vendor_quote_id = $import_data['basic']['vendor_quote_id'];           
            }
            $tmpArr = array(); 
            if(!empty($arrData)){
              for ($i=0; $i <count($arrData) ; $i++) {
               $qty = $this->input->post('qty_'.$arrData[$i]['product_id']);
               $unit_price = $this->input->post('unit_price_'.$arrData[$i]['product_id']);
               $remark = $this->input->post('remark_'.$arrData[$i]['product_id']);
               if(is_numeric($qty) && is_numeric($unit_price)){
                  $tmpArr[] = array('vendor_quote_id'=>$vendor_quote_id,'product_id'=>$arrData[$i]['product_id'],'quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty*$unit_price),'remark'=>$remark);
               }

              }  
            }

            $this->db->update('vendor_quotation',array('json_data'=>serialize($tmpArr),'status'=>2,'lead_time'=>$lead_time),array('vendor_quote_id'=>$vendor_quote_id)); 
            $this->db->insert_batch('vendor_quotation_details',$tmpArr);
            
           if($user_session_data->code!='vendor'){
            if(!empty($vendor_id)){
               $vendor_data = $this->um->getallVendor(' AND v.vendor_id = '.$vendor_id.' AND u.status= 1','R');
               $vendor_data = (array) $vendor_data[0];
              if($vendor_data['email']){
                $to = $vendor_data['email'];
                $subject = 'Your quotation for vessel '.ucwords($data['ship_name']).' - RFQ-NO '.$data['rfq_no'].' is kindly requested';
                $message = 'Dear '.ucwords($vendor_data['vendor_name']).'<br/> We kindly ask your assistance in providing a price quote for '.$data['rfq_no'].' for our vessel '.ucwords($data['ship_name']).' at port of '.ucwords($data['porrt_name']).' in country '.ucwords($data['country']).'.<br>You may access our online quote system by clicking on below URL.<br>'.base_url().'<br>Best Regards<br>'.ucfirst($user_session_data->first_name).' '.ucfirst($user_session_data->last_name).'<br/>'.$user_session_data->phone.'<br>As agent only<br>Team One North';
                $this->um->sendMail($to,$subject,$message);
               }
             }
           }
           else{
             $whereEm = ' AND em.template_code = "quote_received"';
             $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
             if(!empty($emailTemplateData)){
             $subject = str_replace(array('##ship_name##','##rfq_no##'),array(ucwords($data['ship_name']),$data['rfq_no']),$emailTemplateData->email_subject);
             $body = str_replace(array('##user_name##','##phone##','##address##','##email##'),array(ucfirst($user_session_data->first_name).' '.ucfirst($user_session_data->last_name),$user_session_data->phone,$user_session_data->address,$user_session_data->email),$emailTemplateData->email_body);
             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
             if(!empty($email_roles)){
               foreach ($email_roles as $row) {
                  $user_list = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_list)){
                       foreach ($user_list as $val) {
                           $emArr['user_id'] = $val->user_id;
                           $emArr['subject'] = $subject;
                           $emArr['body'] = $body;
                           $this->em->add_email_log($emArr);
                       }
                    }
                  } 
               }
            }
         }
            $this->session->unset_userdata('vendor_quatation');
            $returnArr['status'] = 200;
            $returnArr['returnMsg'] = 'Vendor quotation added successfully.'; 
         }
    }

      $a =[];
     if(!empty($arrData)){
         for ($i=0; $i <count($arrData) ; $i++) { 
          $a[$arrData[$i]['product_id']] = $arrData[$i];     
         }
       }

    $productArr = array();
    if(!empty($a)){
      $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R','','',' ORDER BY p.item_no ASC');    
      foreach($products as $row){
        if($actionType=='save'){
          $vendor_qty = $this->input->post('qty_'.$row->product_id);
          $unit_price = $this->input->post('unit_price_'.$row->product_id);
        }else{
          $vendor_qty= $import_data['dataArr'][$row->product_id]['quantity'];
          $unit_price=$import_data['dataArr'][$row->product_id]['unit_price'];
        }
        $productArr[$row->sequence][$row->category_name][] = array(
          'category_name'=>$row->category_name,
          'product_category_id'=>$row->product_category_id,
          'product_name'=>$row->product_name,
          'product_id'=>$row->product_id,
          'quantity'=>$a[$row->product_id]['quantity'],
          'remark'=>$a[$row->product_id]['remark'],
          'unit'=>$row->unit,
          'item_no'=>$row->item_no,
          'sequence'=>$row->sequence,
          'vendor_qty'=>$vendor_qty,
          'unit_price'=>$unit_price,
          'vendor_remark'=>$import_data['dataArr'][$row->product_id]['remark']
        );   
      }
    }   
        
    ksort($productArr);
    $vars['port_name'] = $data['port_name'];
    $vars['lead_time'] = $this->input->post('lead_time');
    $vars['productArr'] = $productArr;
    $data = $this->load->view('preview_import_quote',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }

  function validate_preview_import_quote($ship_order_id){
        $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
        $arrData = unserialize($data['json_data']);
        $all_fld_emty = true;
        $this->form_validation->set_rules('actionType','actionType','trim|required');
        $this->form_validation->set_rules('lead_time','Lead Time','trim|required');
        if(!empty($arrData)){
          for ($i=0; $i <count($arrData) ; $i++) {
           $qty = $this->input->post('qty_'.$arrData[$i]['product_id']);
           $unit_price = $this->input->post('unit_price_'.$arrData[$i]['product_id']);
                if(!empty($qty) && !empty($unit_price)){
                      $all_fld_emty = false;
                }
                elseif(!empty($qty) && empty($unit_price)){
                      $all_fld_emty = false;
                      $this->form_validation->set_rules('unit_price_'.$arrData[$i]['product_id'], 'unit_price', 'trim|required');
                }
                elseif(empty($qty)  && !empty($unit_price)){
                     $all_fld_emty = false;
                    $this->form_validation->set_rules('qty_'.$arrData[$i]['product_id'], 'quantity', 'trim|required');
                }
            }
         }

        if($all_fld_emty){
           $this->form_validation->set_rules('qt_product_id','Product','trim|required',array('required'=>'Please enter atleast one item detail.'));
        }
       
     return $this->form_validation->run();  
  }

  function check_already_quote(){
    $ship_order_id = trim($this->input->post('id'));
    $vendor_id = trim($this->input->post('vendor_id'));
    $data = $this->cm->getQuotedVendor(' AND vo.ship_order_id = '.$ship_order_id);
    $vendor_ids = array();
     foreach ($data as $v) {
      $vendor_ids[] =  $v->vendor_id;
     }
    $vendor_ids = array_unique($vendor_ids);
    if(in_array($vendor_id,$vendor_ids)){
      $this->form_validation->set_message('check_already_quote', 'Quotation already imported for this vendor.');              
        return false;
    }else{
        return true;
    }

  }

  function getCompanySummury($ship_id=''){
    checkUserSession();
     $this->load->model('user_manager');
     $this->um = $this->user_manager;
     $user_session_data = getSessionData();
     $ship_id = base64_decode($ship_id);
     $shipping_company_id = $this->input->post('shipping_company_id');
     if(!empty($ship_id)){
       $vars['ship_details'] = (array) $this->cm->getAllShipsById("And s.ship_id = ".$ship_id);
       $vars['years'] = $this->cm->stock_years($ship_id);
     }

     if(!empty($shipping_company_id)){
      $vars['captain'] = $this->um->getalluserlist(' AND r.code = "captain" AND u.shipping_company_id ='.$shipping_company_id,'R');
      $vars['cook'] = $this->um->getalluserlist(' AND r.code = "cook" AND u.shipping_company_id ='.$shipping_company_id,'R');
     //  $shipData = $this->cm->getAllShips(' And s.shipping_company_id = '.$shipping_company_id,'R');
     //    $assignedCaptains = array();
     //    $assignedCooks = array();
     //    if(!empty($shipData)){
     //        foreach($shipData as $ship){
     //            $assignedCaptains[] = $ship->captain_user_id;
     //            $assignedCooks[] = $ship->cook_user_id;
     //        }
     //    }
     //    $vars['assignedCaptains'] = $assignedCaptains;
     //    $vars['assignedCooks'] = $assignedCooks;

     }

     //$vars['group_products'] = $this->cm->avalibleStockByGroup(' AND c.ship_id = '.$ship_id);
     $vars['products_category'] = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);
     $data = $this->load->view('company_summ',$vars,true);
     $dataArr = $data;
     echo json_encode(array('data'=>$dataArr,'string'=>$string));
   }


  function update_ship_details(){
     checkUserSession();
     $actionType = $this->input->post('actionType');
     extract($this->input->post());
     if($actionType=='save'){
      if($this->validate_360_ship()){
          $error = true;
          $cook_id = trim($this->input->post('cook_user_id'));
          $captain_id = trim($this->input->post('captain_user_id')); 
          if(!empty($cook_id) || !empty($captain_id)){ 
           if($this->input->post('unlink')!='No'){
            $data = $this->cm->getCaptainAndCook(' AND s.ship_id !='.$ship_id);
            $assignedCaptains = array();
                $assignedCooks = array();
                if(!empty($data)){
                    foreach($data as $row){
                        if(!empty($row->captain_user_id)){    
                          $assignedCaptains[] = $row->captain_user_id;
                        }
                        if(!empty($row->cook_user_id)){    
                           $assignedCooks[] = $row->cook_user_id;
                        }
                    }
                 }

              if(in_array($cook_id,$assignedCooks)){
                 $cook_data = $this->cm->getCaptainAndCook(' AND s.cook_user_id ='.$cook_id);
                 $error = false;
                 $returnArr['cookmsg'] = 'The '.ucwords($cook_data[0]->cook_name).'(Cook) Link With The Vessel '.ucwords($cook_data[0]->ship_name);   
               }
            
               if(in_array($captain_id,$assignedCaptains)){
                 $captain_data = $this->cm->getCaptainAndCook(' AND s.captain_user_id ='.$captain_id);
                 $error = false;
                 $returnArr['captainmsg'] = 'The '.ucwords($captain_data[0]->captain_name).'(Captain) Link With The Vessel '.ucwords($captain_data[0]->ship_name);    
               }
             }
         } 

        if($error){
          
          if($this->input->post('unlink')=='No'){
             $this->db->update('ships',array('cook_user_id'=>null),array('cook_user_id'=>$cook_user_id));
              $this->db->update('ships',array('captain_user_id'=>null),array('captain_user_id'=>$captain_user_id));
          }

         $dataArr = array('ship_name'=>$ship_name,'imo_no'=>$imo_no,'captain_user_id'=>$captain_user_id,'captain_nationality'=>$captain_nationality,'cook_user_id'=>$cook_user_id,'cook_nationality'=>$cook_nationality,'total_members'=>$total_members,'trading_area'=>$trading_area,'victualling_rate'=>$victualling_rate);    
            $this->cm->editShips($dataArr,array('ship_id'=>$ship_id));
           $returnArr['returnMsg'] = 'Ship Details Updated successfully';
         }
         else{
            $returnArr['status'] = 200;
         }
      }
      else{
        $returnArr['status'] = 100;
        $returnArr['validation_msg']['name'] = form_error('ship_name','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['imo_no'] = form_error('imo_no','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['total_members'] = form_error('total_members','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['captain_user_id'] = form_error('captain_user_id','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['captain_nationality'] = form_error('captain_nationality','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['cook_user_id'] = form_error('cook_user_id','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['cook_nationality'] = form_error('cook_nationality','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['trading_area'] = form_error('trading_area','<p class="error" style="display: inline;">','</p>');
        $returnArr['validation_msg']['victualling_rate'] = form_error('victualling_rate','<p class="error" style="display: inline;">','</p>');
      }
     }    
     echo json_encode($returnArr);
   }


 function validate_360_ship(){
     $this->form_validation->set_rules('ship_name', 'Ship Name', 'trim|required');
     $this->form_validation->set_rules('imo_no', 'Imo No', 'trim|required|callback_check_imo');
     
     // $this->form_validation->set_rules('captain_user_id', 'Ship Captain', 'trim|required');
     if(!empty($this->input->post('cook_user_id'))){
       $this->form_validation->set_rules('cook_nationality', 'Cook Nationality', 'trim|required');
     }
     
     if(!empty($this->input->post('captain_user_id'))){
      $this->form_validation->set_rules('captain_nationality', 'Captain Nationality', 'trim|required');
     }

     // $this->form_validation->set_rules('cook_user_id', 'Ship Cook', 'trim|required');
     
     $this->form_validation->set_rules('total_members', 'Total Members', 'trim|required');
     $this->form_validation->set_rules('victualling_rate', 'Victualling Rate', 'trim|required');
     $this->form_validation->set_rules('trading_area', 'Trading Area', 'trim|required');
     return $this->form_validation->run();   
  }

   function get_port_steps($ship_id=''){
    checkUserSession();
    $ship_id = base64_decode($ship_id);
    $returnArr = '';
    if($ship_id){
    $all_steps = $this->cm->getAllPortList(' AND sp.ship_id = '.$ship_id,'R');
    $dateArr = array();
    $cur_date = date('Y-m-d');
      foreach ($all_steps as $row) {
           $dateArr[] = $row->date;
        }
      
     $prev_date = array();
      for ($i=0; $i < count($dateArr); $i++) {   
        if($dateArr[$i]<=$cur_date){
          $prev_date[] = $dateArr[$i];
        }   
      }   
     
     $prev1 = max($prev_date);
     $prev1key = array_search($prev1,$prev_date); 
     unset($prev_date[$prev1key]);

     $prev2 = max($prev_date);
     $prev2key = array_search($prev2,$prev_date);
     unset($prev_date[$prev2key]);
    
     $prev3 = max($prev_date);
     $prev3key = array_search($prev3,$prev_date);
     unset($prev_date[$prev3key]);

     $next_date = array();
      for ($j=0; $j < count($dateArr); $j++) {   
        if($dateArr[$j] > $cur_date){
          $next_date[] = $dateArr[$j];
        }   
      } 

     $nxt1 = min($next_date);
     $nxt1key = array_search($nxt1,$next_date);
     unset($next_date[$nxt1key]);        

     $nxt2 = min($next_date);
     $nxt2key = array_search($nxt2,$next_date);
     unset($next_date[$nxt2key]);

     $newdateArr = array($prev1,$prev2,$prev3,$nxt1,$nxt2);
     sort($newdateArr);

     // print_r($newdateArr);die;
     if(!empty($newdateArr)){
       $returnArr .= '<ul id="progressbar" >';
       $k =0;    
       for ($l=0; $l <count($newdateArr) ; $l++) { 
          if(!empty($newdateArr[$l])){
           $data = (array) $this->cm->getAllportById(' AND sp.ship_id = '.$ship_id.' AND sp.date ="'.$newdateArr[$l].'"');
           if(!empty($data)){
             $k++;
              $active = ($data['date'] ==  $cur_date) ? 'text-muted' : '';
              $done = ($data['date'] <  $cur_date) ? 'active' : '';
              // $returnArr .= '<div class="step '.$active.' '.$done.'" data-desc="'.ucfirst($data['name']).'"><span>'.ConvertDate($data['date'],'','d-m-Y').'</span>&nbsp;</div>';
              $returnArr .= '<li class="step0 '.$active.' text-center '.$done.'" id="step'.$k.'"><span class="blueSpan">'.ucfirst($data['name']).'</span><span>('.ConvertDate($data['date'],'','d-m-Y').')</span></li>';

           }
          }
        } 
       $returnArr .= '</ul>';
      }
    }    
    echo json_encode(array('data'=>$returnArr));
   }
  

 // function import_vendor_quatation(){
 //    checkUserSession();
 //    $user_session_data = getSessionData();
 //    $this->load->model('user_manager');
 //    $returnArr['status'] = 100;
 //    $actionType = $this->input->post('actionType');
 //    if($actionType=='save'){
 //     $this->form_validation->set_rules('vendor_id','Vendor','trim|required');
 //     $this->form_validation->set_rules('img','','callback_file_check');   
 //      if($this->form_validation->run()){
 //        $vendor_id = trim($this->input->post('vendor_id'));
 //        $ship_order_id =  trim($this->input->post('id'));
 //         if(!empty($_FILES['img']['name'])) {
 //           $file = $_FILES['img']['name'];
 //           $upload_data = doc_upload($file, 'sheets');
 //         }

 //        $dataArr['vendor_id'] = $vendor_id;
 //        $dataArr['ship_order_id'] = $ship_order_id;
 //        $dataArr['added_by'] = $user_session_data->user_id;
 //        $dataArr['added_on'] = date('Y-m-d H:i:s');
        
 //        $vendor_order_id = $this->cm->add_vendor_order($dataArr);        
 //        $full_path = FCPATH.'uploads/sheets/'.$upload_data['file_name'];
         
 //         $this->load->library('Excelreader');
 //         $excel  = new Excelreader();
 //         $objWriter = $excel->readExcel($full_path,'xlsx');
 //         unset($objWriter[1]);
         
 //         $products = $this->mp->getAllProduct(' AND p.status =1 ','R');
               
 //         $product_id_arr = array();
 //               if(!empty($products)){
 //                foreach ($products as $key => $row) {
 //                  $product_name = str_replace(array(' ',',',),array('_',''),$row->product_name);
 //                  $product_name = strtolower($product_name);
 //                  $product_id_arr[$product_name] = $row->product_id; 
 //                }
 //            }

 //         $tmpArr = array();
 //         for ($i=0; $i <count($objWriter) ; $i++){
 //           if(!empty($objWriter[$i]['D'])){
 //             $name = strtolower(str_replace(array(' ',','),array('_',''),trim($objWriter[$i]['D'])));
 //             $product_id =  $product_id_arr[$name];
 //               if(!empty($objWriter[$i]['H']) && !empty($objWriter[$i]['I'])){
 //                   $tmpArr[] = array('vendor_order_id'=>$vendor_order_id,'product_id'=>$product_id,'quantity'=>$objWriter[$i]['H'],'price'=>$objWriter[$i]['I'],'remark'=>$objWriter[$i]['J']); 
 //                }
 //           }  
 //         } 
          

 //        $this->db->insert_batch('vendor_order_detail',$tmpArr);
 //        $returnArr['status'] = 200;
 //        $returnArr['returnMsg'] = 'Quatation added successfully.';            
 //      }   
 //    }
 //    $vars['vendors'] = $this->user_manager->getallVendor(' AND u.status= 1','R');
 //    $vars['dataArr'] = $this->input->post();
 //    $data = $this->load->view('import_vendor_quote',$vars,true);
 //    $returnArr['data'] = $data;
 //    echo json_encode($returnArr); 
 //  }
   
  function file_check($str){
        $allowed_mime_type_arr = array('text/x-comma-separated-values','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel');
        $mime = get_mime_by_extension($_FILES['img']['name']);
        if(isset($_FILES['img']['name']) && $_FILES['img']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please choose xlsx, xls or csv file.');              
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return false;
        }
    }
  
 
 function vendor_quatation(){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('user_manager');
    $ship_order_id = $this->input->post('id');
    $vendor_quote_id = $this->input->post('vendor_quote_id');
    $ship_details = getCustomSession('ship_details');
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
      $this->form_validation->set_rules('vendor_quote_id','','trim|required',array('required'=>'Please choose atleast one vendor quote'));
      if($this->form_validation->run()){
        $dataArr['ship_order_id'] = $ship_order_id;
        $dataArr['vendor_quote_id'] = $vendor_quote_id;
        // $this->db->delete('vendor_quote_approvals',array('vendor_quote_id'=>$vendor_quote_id,'ship_order_id'=>$ship_order_id));
        $this->cm->add_vendor_quote_app($dataArr);
       $this->db->update('ship_order',array('status'=>6),array('ship_order_id'=>$ship_order_id));
        // setCustomSession('selected_quote',$dataArr);
        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'Quotation approved for RFQ successfully.';
      }  
    }
    $vars['dataArr'] = $this->input->post();     
    $data = $this->load->view('vendor_quatation',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }

function vendor_quote_list_old(){
     checkUserSession();
        $user_session_data = getSessionData();
        $returnArr = '';
        $header = '';
        $thArr = ''; 
        $ship_order_id = $this->input->post('id');
        $second_id = $this->input->post('second_id');
        $data = $this->cm->getOrderProduct(' And so.ship_order_id = '.$ship_order_id);
        $data2 = $this->cm->getQuotedVendor(' AND vo.ship_order_id = '.$ship_order_id);
        $selectedQuote = (array) $this->cm->getSelectedQuote(' AND vqa.ship_order_id = '.$ship_order_id);
        $productArr = [];
        $quoteArr = [];
        $totalProductArr = [];
         foreach ($data as $v) {
            $productArr[$v->product_category_id.'|'.$v->category_name][] = $v;
            $totalProductArr[] = $v->product_id;
         }
         
         $totalQuote = [];
         $totalPrice = [];
         foreach ($data2 as $q) {
            $quoteArr[$q->vendor_quote_id.'|'.$q->vendor_name][$q->product_id][] = array('qty'=>$q->quantity,'unit_price'=>$q->unit_price);
           $totalQuote[$q->vendor_quote_id][] = $q->quantity;
           $totalPrice[$q->vendor_quote_id][] = $q->price; 

         }
        
         foreach ($quoteArr as $vendor_id => $j) {
            $vendor_id = explode('|',$vendor_id);
            $selected = ($selectedQuote['vendor_quote_id'] == $vendor_id[0]) ? '<i style="color:green" class="fa fa-check"></i>' : '';
            $header .= ' <th colspan="2">&nbsp;'.ucwords($vendor_id[1]).' '.$selected.'&nbsp;';
            if(empty($second_id)){
             $header .= '<input type="radio" name="vendor_quote_id" id="vendor_quote_id" value="'.$vendor_id[0].'">';
            }
            $header .= '</th>';
            $thArr .='<th width="10%">Vendor QTY</th><th width="10%">Unit Price</th>';
                 
         }    

         if(!empty($productArr) && !empty($quoteArr)){      
               foreach($productArr as $category => $products){
                    $category = explode('|',$category);
                     $returnArr .= '<tr class="parent_row">
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                         <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category[1].'</td>
                         <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                         <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                        foreach ($quoteArr as $vendor_id => $j) {
                          $returnArr .= '
                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          ';
                         } 
                         $returnArr .= '</tr>';
                         foreach($products as $product){
                          $returnArr .= '<tr class="child_row">
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->item_no.'</td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->product_name.'</td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($product->unit).'</td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->quantity.'</td>';
                         foreach ($quoteArr as $vendor_id => $row2) {
                            $returnArr .= '
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$quoteArr[$vendor_id][$product->product_id][0]['qty'].'</td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$quoteArr[$vendor_id][$product->product_id][0]['unit_price'].'</td>';
                             }
                             $returnArr .= '</tr>';
                         }
              }
            $totalProductArr = array_unique($totalProductArr);
            $totalProduct = count($totalProductArr);
            $returnArr .= '<tr class="parent_row_count">
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                    foreach ($quoteArr as $vendor_id => $j) {
                        $vendor_id = explode('|',$vendor_id);
                        $totalqt = $totalQuote[$vendor_id[0]];
                        $price =  $totalPrice[$vendor_id[0]];
                        $returnArr .= '
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.count($totalqt).' items quote received out of '.$totalProduct.'</td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">';  
                    }
             $returnArr .= '</tr>';  
        }
      else{
           $returnArr = '<tr><td colspan="5" align="center" style="font-weight:bold;font-size:12px;">No Data Available</td></tr>';
      }    
      
        echo json_encode(array('dataArr'=>$returnArr,'header'=>$header,'thArr'=>$thArr));
   }

    function vendor_quote_list(){
     checkUserSession();
        $user_session_data = getSessionData();
        $returnArr = '';
        $header = '<th colspan="4"></th>';
        $thArr = ''; 
        $ship_order_id = $this->input->post('id');
        $second_id = $this->input->post('second_id');
        $order_data = $this->cm->getRrqItemsByID(' and so.ship_order_id='.$ship_order_id);
        $vendor_data = $this->cm->getVendorQuotation(' and vq.status=2 and vq.ship_order_id='.$ship_order_id);
        $totalProductArr = array();
        $productArr = [];
        $productQty = [];
        if(!empty($order_data)){
            $dataA = unserialize($order_data->json_data);
            if(!empty($dataA)){
                for ($i=0; $i <count($dataA) ; $i++) { 
                    $totalProductArr[] = $dataA[$i]['product_id'];
                    $productQty[$dataA[$i]['product_id']] = $dataA[$i]['quantity'];
                }
            }
        }
        if(!empty($totalProductArr)){
            $products = $this->mp->getAllProduct(' AND p.status =1  and p.product_id IN('.implode(',', $totalProductArr).')','R');
            if(!empty($products)){
                foreach ($products as $p) {
                    $productArr[$p->sequence][$p->product_category_id.'|'.$p->category_name][] = $p;
                // $productArr[$p->product_category_id.'|'.$p->category_name][] = $p;

                }
            }
        }

        $quoteArr = [];
        $totalQuote = [];
        if(!empty($vendor_data)){
            foreach ($vendor_data as $vd) {
                $vdata = $this->cm->getAllVendorById(' and v.vendor_id='.$vd->vendor_id);
                $dataV = unserialize($vd->json_data);
                if(!empty($dataV)){
                    for ($i=0; $i <count($dataV) ; $i++) { 
                        $quoteArr[$vd->vendor_quote_id.'|'.$vdata->vendor_name.'|'.$vd->lead_time][$dataV[$i]['product_id']][] = array('qty'=>$dataV[$i]['quantity'],'unit_price'=>$dataV[$i]['unit_price'],'attechment'=>$dataV[$i]['attechment'],'remark'=>$dataV[$i]['remark']);
                       $totalQuote[$vd->vendor_quote_id][] = $dataV[$i]['quantity']; 
                    }
                }
            }
        }

        $selectedQuote = (array) $this->cm->getSelectedQuote(' AND vqa.ship_order_id = '.$ship_order_id);
       
         foreach ($quoteArr as $vendor_id => $j) {
            $vendor_id = explode('|',$vendor_id);
            $selected = ($selectedQuote['vendor_quote_id'] == $vendor_id[0]) ? '<i style="color:green" class="fa fa-check"></i>' : '';
            $header .= '<th colspan="4">&nbsp;'.ucwords($vendor_id[1]).''.$selected.'&nbsp;<br>Lead Time - '.$vendor_id[2].' Days&nbsp;';
            if(empty($second_id)){
             $header .= '<input type="radio" name="vendor_quote_id" id="vendor_quote_id" value="'.$vendor_id[0].'">';
            }
            $header .= '</th>';
            $thArr .='<th width="10%">Vendor QTY</th><th width="15%">Unit Price($)</th><th class="row_total">Total Price($)</th><th width="150">Remark/File</th>';
                 
         }
         $highlightQuote = array();    
          ksort($productArr);
         if(!empty($productArr) && !empty($quoteArr)){      
            $rfq_total = 0;
            foreach ($productArr as $key => $list) {
               foreach($list as $category => $products){
                    $category = explode('|',$category);
                     $returnArr .= '<tr class="parent_row">
                        <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                         <td width="40%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category[1].'</td>
                         <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                         <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                        foreach ($quoteArr as $vendor_id => $j) {
                          $returnArr .= '
                          <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';

                         } 
                         $returnArr .= '</tr>';
                         foreach($products as $product){
                            $rfq_total += $productQty[$product->product_id];
                          $returnArr .= '<tr class="child_row">
                             <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->item_no.'</td>
                             <td width="40%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->product_name.'</td>
                             <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($product->unit).'</td>
                             <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($productQty[$product->product_id],2).'</td>';
                         $hightotal = array();
                         $highunit = array();
                         foreach ($quoteArr as $vendor_id => $row2) {
                            $highunit[] = $quoteArr[$vendor_id][$product->product_id][0]['unit_price'];
                            $hightotal[] = ($quoteArr[$vendor_id][$product->product_id][0]['qty'] * $quoteArr[$vendor_id][$product->product_id][0]['unit_price']);
                         }

                         $high = min($hightotal); 
                         // $low = min($hightotal);
                         $highun = min($highunit);

                           
                         foreach ($quoteArr as $vendor_id => $row2) {
                           
                           $qty = ($quoteArr[$vendor_id][$product->product_id][0]['qty']) ? number_format($quoteArr[$vendor_id][$product->product_id][0]['qty'],2) : '';
                           
                           $unit_price = ($quoteArr[$vendor_id][$product->product_id][0]['unit_price']) ? number_format($quoteArr[$vendor_id][$product->product_id][0]['unit_price'],2) : '';
                           $total = ($quoteArr[$vendor_id][$product->product_id][0]['qty'] * $quoteArr[$vendor_id][$product->product_id][0]['unit_price']);

                           $img = ($quoteArr[$vendor_id][$product->product_id][0]['attechment'] || $quoteArr[$vendor_id][$product->product_id][0]['remark']) ? '<a title='.$quoteArr[$vendor_id][$product->product_id][0]['attechment'].' target="_blank" style="
                           display: inline-block; max-width: 150px;overflow: hidden;text-overflow: ellipsis;"  href="'.base_url().'uploads/vendor_quote/'.$quoteArr[$vendor_id][$product->product_id][0]['attechment'].'">'.$quoteArr[$vendor_id][$product->product_id][0]['attechment'].'</a><br/>'.$quoteArr[$vendor_id][$product->product_id][0]['remark'] : '';

                           // $img = ($quoteArr[$vendor_id][$product->product_id][0]['attechment']) ? '<div id="thumbnail-container"><img id="imgPreview_'.$products[$i]['product_id'].'"  class="thumbnail" alt="Sample Image" src="'.base_url().'uploads/vendor_quote/'.$quoteArr[$vendor_id][$product->product_id][0]['attechment'].'"></div>' : '';
                            $highttl = ($total==$high) ? 'green' : '';
                            $highunn = ($unit_price==$highun) ? 'green' : '';
                            $checkqty = ($qty>number_format($productQty[$product->product_id],2) || ($qty<number_format($productQty[$product->product_id],2))) ? 'red' : '';
                            
                            // $highlight = ($total==$low) ? 'green' : '';
                            
                            $returnArr .= '
                             <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="color:'.$checkqty.';">'.$qty.'</td>
                             <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="color:'.$highunn.';">'.$unit_price.'</td>
                            <td width="15%" role="gridcell" class="com_total" tabindex="-1" aria-describedby="f2_key" style="color:'.$highttl.';">'.$total.'</td>

                             <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$img.'</td>';
                             }
                             $returnArr .= '</tr>';
                             // $highlightQuote[] = $hightotal;
                         }
                 }
          }
            $totalProductArr = array_unique($totalProductArr);
            $totalProduct = count($totalProductArr);
            $returnArr .= '<tr class="parent_row_count">
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                    foreach ($quoteArr as $vendor_id => $j) {
                        $vendor_id = explode('|',$vendor_id);
                        $totalqt = $totalQuote[$vendor_id[0]];
                        $price =  $totalPrice[$vendor_id[0]];
                        $returnArr .= '
                        <td colspan="4" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.count($totalqt).' items quote received out of '.$totalProduct.'</td>';  
                    }
             $returnArr .= '</tr>';  

             $returnArr .= '<tr class="parent_row_count">
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$rfq_total.'</td>';
                        
                    foreach ($quoteArr as $vendor_id => $data) {
                        $total_qty = 0;
                        $tl_price = 0;
                        foreach ($data as $key => $items) {
                          foreach ($items as $item) {
                            $total_qty += $item['qty'];
                            $tl_price += ($item['qty']*$item['unit_price']);
                          }
                        }
                        $returnArr .= '
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$total_qty.'</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td><td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$tl_price.'</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';  
                    }
             $returnArr .= '</tr>';  
        }
      else{
           $returnArr = '<tr><td colspan="5" align="center" style="font-weight:bold;font-size:12px;">No Data Available</td></tr>';
      }    
      
        echo json_encode(array('dataArr'=>$returnArr,'header'=>$header,'thArr'=>$thArr,'port_name'=>'<strong>Port : '.ucwords($order_data->port_name).'</strong>'));
   }

 
  function set_ship_details($ship_id=''){
    checkUserSession();
    $ship_id = base64_decode($ship_id);
    if(!empty($ship_id)){
     $dataArr = (array) $this->cm->getNewShipById(' AND s.ship_id = '.$ship_id);
     setCustomSession('ship_details',$dataArr);
     $returnArr['status'] = ($dataArr['ship_type']==1) ? 200 : 100;
    }
    echo json_encode($returnArr);
  } 

  
 function order_basic_details(){
      checkUserSession();
      $user_session_data = getSessionData();
      $returnArr['status'] = 100;
      $ship_order_id = $this->input->post('id');
      $rfq_data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id ='.$ship_order_id);
      $ship_details = getCustomSession('ship_details');
      $ship_id = $ship_details['ship_id'];
      $actionType = $this->input->post('actionType');
      if($actionType=='save'){
        if($this->validate_basic_order()){  
             $this->session->unset_userdata('order_basic_details_'.$ship_id);
             setCustomSession('order_basic_details_'.$ship_order_id,$this->input->post());
             $returnArr['status'] = 200;
         }
      }
      $savedData = getCustomSession('order_basic_details_'.$ship_order_id);
      $vars['rfq_data'] = $rfq_data;
      $vars['dataArr'] = (!empty($savedData)) ? $savedData : $this->input->post();
      $data = $this->load->view('basic_order_stock',$vars,true);
      $returnArr['data'] = $data;  
      echo json_encode($returnArr);
  } 

  function validate_basic_order(){   
     $this->form_validation->set_rules('order_id','Order ID','trim|required|callback_check_order_id');
     $this->form_validation->set_message('check_order_id','This order id already exists');
     $this->form_validation->set_rules('po_no','PO No','trim|required|callback_check_po_no');
     $this->form_validation->set_message('check_po_no','This PO No already exists');
     $this->form_validation->set_rules('note_no','Delivery Note No','trim|required|callback_check_dn_no');
     $this->form_validation->set_message('check_dn_no','This delivery note no already exists');
     $this->form_validation->set_rules('invoice_no','Invoice No','trim|required|callback_check_invoice_no');
     $this->form_validation->set_message('check_invoice_no','This invoice no already exists');
     $this->form_validation->set_rules('delivery_date','Delivery Date','trim|required');
     $this->form_validation->set_rules('reqsn_date','Reqsn Date','trim|required');
     $this->form_validation->set_rules('due_date','Due Date','trim|required');
     $this->form_validation->set_rules('lead_time','Lead Time','trim|required|is_natural_no_zero');
    
     return $this->form_validation->run();
  }

  function check_order_id(){
    checkUserSession();
    $order_id = trim($this->input->post('order_id'));
    $work_orders = $this->cm->getAllWorkOrders(' AND wo.status != 5','R');
    $status = true;
    if(!empty($work_orders)){
      foreach ($work_orders as $row) {
          if($row->order_id==$order_id){
            $status = false;
          }
        }  
     }

     return $status;
  }


  function check_po_no(){
    checkUserSession();
    $po_no = trim($this->input->post('po_no'));
    $work_orders = $this->cm->getAllWorkOrders(' AND wo.status != 5','R');
    $status = true;
    if(!empty($work_orders)){
      foreach ($work_orders as $row) {
          if($row->po_no==$po_no){
            $status = false;
          }
        }  
     }

     return $status;
  }

  function check_dn_no(){
    checkUserSession();
    $note_no = trim($this->input->post('note_no'));
    $work_orders = $this->cm->getAllWorkOrders(' AND wo.status != 5','R');
    $status = true;
    if(!empty($work_orders)){
      foreach ($work_orders as $row) {
          if($row->note_no==$note_no){
            $status = false;
          }
        }  
     }

     return $status;
  }

  function check_invoice_no(){
    checkUserSession();
    $invoice_no = trim($this->input->post('invoice_no'));
    $work_orders = $this->cm->getAllWorkOrders(' AND wo.status != 5','R');
    $status = true;
    if(!empty($work_orders)){
      foreach ($work_orders as $row) {
          if($row->invoice_no==$invoice_no){
            $status = false;
          }
        }  
     }

     return $status;
  }


  function order_addition_details(){
      checkUserSession();
      $user_session_data = getSessionData(); 
      $returnArr['status'] = 100;
      $ship_details = getCustomSession('ship_details');
      $ship_id = $ship_details['ship_id'];
      $this->load->model('email_manager');
      $this->em = $this->email_manager;
      $ship_order_id = trim($this->input->post('id'));
      // $basic_details = getCustomSession('order_basic_details_'.$ship_details['ship_id']);
      $basic_details = getCustomSession('order_basic_details_'.$ship_order_id);
      // $ship_order_id = $basic_details['id'];
      $actionType = $this->input->post('actionType');
      if(!empty($ship_order_id)){
         $vendor_quote = (array) $this->cm->getQuotedDetails(' AND vqa.ship_order_id = '.$ship_order_id);
         $json_arr = unserialize($vendor_quote['quote_json']);
      }
      
      // echo '<pre>';
      // print_r($json_arr);die;
      $a = [];
      $productArr = []; 
       if(!empty($json_arr)){
         for ($i=0; $i <count($json_arr) ; $i++) {
           $a[$json_arr[$i]['product_id']] = $json_arr[$i];
         }
      }  

      if(!empty($a)){
         $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
          if(!empty($products)){
           foreach ($products as $row) {
              $productArr[$row->sequence][$row->category_name][] = array(
                'category_name'=>$row->category_name,
                'product_category_id'=>$row->product_category_id,
                'product_name'=>$row->product_name,
                'product_id'=>$row->product_id,
                'quantity'=>(($a[$row->product_id]['revised_qty']) ? $a[$row->product_id]['revised_qty'] : $a[$row->product_id]['quantity']),
                'remark'=>$a[$row->product_id]['remark'],
                'unit'=>$row->unit,
                'item_no'=>$row->item_no,
                'sequence'=>$row->sequence,
                'unit_price'=>$a[$row->product_id]['unit_price']
             ); 
            } 
         }
      }
      ksort($productArr); 

      if($actionType=='save'){
        $basicDataArr = array(
            'order_id'=>$basic_details['order_id'],
            'po_no'=>$basic_details['po_no'],
            // 'delivery_port'=>$basic_details['delivery_port'],
            // 'country'=>$basic_details['country'],
            'delivery_date'=>ConvertDate($basic_details['delivery_date'],'','Y-m-d'),
            // 'agent_name'=>$basic_details['agent_name'],
            // 'agent_email'=>$basic_details['agent_email'],
            // 'agent_phone'=>$basic_details['agent_phone'],
            // 'agent_country'=>$basic_details['agent_country'],
            // 'next_port_id'=>$basic_details['next_port_id'],
            'note_no'=>$basic_details['note_no'],
            'invoice_no'=>$basic_details['invoice_no'],
            'remark'=>$basic_details['remark'],
            'ship_id'=>$ship_details['ship_id'],
            'ship_order_id'=>$ship_order_id,
            'reqsn_date'=>ConvertDate($basic_details['reqsn_date'],'','Y-m-d'),
            'due_date'=>ConvertDate($basic_details['due_date'],'','Y-m-d'),
            'vendor_id'=>$vendor_quote['vendor_id'],
            'lead_time'=>$basic_details['lead_time']
        );

         $basicDataArr['created_on'] = date('Y-m-d H:i:s');
         $basicDataArr['created_by'] = $user_session_data->user_id;
         $basicDataArr['order_date'] = date('Y-m-d H:i:s');
         $total_price = 0;
         // for ($k=0; $k < 500; $k++) { 
         $work_order_id = $this->cm->add_po_stock($basicDataArr);
         $batch = array();
         if(!empty($json_arr)){
              for ($i=0; $i <count($json_arr) ; $i++) { 
                $total_price += ($json_arr[$i]['revised_qty'] * $json_arr[$i]['unit_price']); 
                $batch[] = array('work_order_id'=>$work_order_id,'product_id'=>$json_arr[$i]['product_id'],'qty'=>(($json_arr[$i]['revised_qty']) ? $json_arr[$i]['revised_qty'] : $json_arr[$i]['quantity']),'unit_price'=>$json_arr[$i]['unit_price'],'sc_unit_price'=>$json_arr[$i]['revised_unit_price']);
               }
         }

       $this->db->insert_batch('work_order_details',$batch);
       $json_data = serialize($batch);
       $this->cm->edit_po_stock(array('json_data'=>$json_data,'total_price'=>$total_price),' work_order_id ='.$work_order_id);
       $this->db->update('ship_order',array('status'=>9),array('ship_order_id'=>$ship_order_id));
      // }

       if($ship_details['ship_type']==1){
        $sn = getSerialNum(1,'work_order');
        $sn1 = getSerialNum(1,'delivey_note');
        $sn2 = getSerialNum(1,'invoice'); 
        updateSerialNum(1,'work_order',$sn+1);
        updateSerialNum(1,'delivey_note',$sn1+1);
        updateSerialNum(1,'invoice',$sn2+1);
       }
       else{
        $sn = getSerialNum(2,'work_order');
        $sn1 = getSerialNum(2,'delivey_note');
        $sn2 = getSerialNum(2,'invoice');
        updateSerialNum(2,'work_order',$sn+1);
        updateSerialNum(2,'delivey_note',$sn1+1);
        updateSerialNum(2,'invoice',$sn2+1);

       }

       deleteCustomSession('order_basic_details_'.$ship_order_id);
       $returnArr['status'] = 200;  
       $returnArr['returnMsg'] = 'Work Order added successfully.';

       /*PO Created Email Notification to Admin and Vendor*/
          $whereEm = ' AND em.template_code = "po_created"';
          $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
          if(!empty($emailTemplateData)){
             require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
             $po_data = (array) $this->cm->getWorkOrderByID(' and wo.work_order_id ='.$work_order_id);
             $subject = str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivey_port##','##delivery_date##','##req_type##'),array(ucwords($po_data['ship_name']),$po_data['po_no'],$po_data['imo_no'],$po_data['delivery_port'],convertDate($po_data['delivery_date'],'','d-m-Y'),ucwords(str_replace(array('_'),array(' '),$po_data['requisition_type']))),$emailTemplateData->email_subject);

             $pdf_vars['data'] = $po_data;
             $pdf_vars['view_file'] = 'purchase_order_pdf';
             $pdf_vars['title'] = 'Purchase Order';
             $html = $this->load->view('purchase_order_pdf',$pdf_vars,TRUE);
             $file = str_replace('/','--','PO-'.$po_data['po_no']);
             $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
             $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
             $pdf->AddPage('L');
             $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                 ob_clean();
                 ob_end_clean();
                 ob_flush();
             $pdfData = $pdf->Output('', 'S');
             $filePath = $pdfFilePath;
             write_file($filePath, $pdfData);             
              $emailArr = array();
              if(!empty($json_arr)){
               for ($e=0; $e <count($json_arr) ; $e++) { 
                   $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_arr[$e]['product_id']);
                   $product_id = $product['product_id'];
                   $emailArr[$product['sequence']][] = array(
                     'category_name'=>$product['category_name'],
                     'product_category_id'=>$product['product_category_id'],
                     'product_name'=>$product['product_name'],
                     'product_id'=>$product_id,
                     'unit'=>$product['unit'],
                     'item_no'=>$product['item_no'],
                     'sequence'=>$json_arr['sequence'],
                     'quantity'=>$json_arr[$e]['revised_qty'],
                     'unit_price'=> $json_arr[$e]['unit_price'],
                  );   
                } 
              } 
             ksort($emailArr); 
             $pdf_vars2['productArr'] = $emailArr; 
             $pdf_vars2['data'] = $po_data;
             $pdf_vars2['view_file'] = 'purchase_order_detailed_pdf';
             $pdf_vars2['title'] = 'Detailed PO';
             $html2 = $this->load->view('purchase_order_detailed_pdf',$pdf_vars2,TRUE);
             $file2 = str_replace('/','--','DetailedPO-'.$po_data['po_no']);
             $pdfFilePath2 = FCPATH . "uploads/work_order_pdfs/".$file2.".pdf";
             $pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             $pdf2->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
             $pdf2->AddPage('L');
             $pdf2->WriteHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);
             ob_clean();ob_end_clean();ob_flush();
             $pdfData2 = $pdf2->Output('', 'S');
             $filePath2 = $pdfFilePath2;
             write_file($filePath2, $pdfData2);

             $attachmentArray = array($file,$file2);

              if(!empty($po_data['vendor_user_id'])){
                // $vendorData = (array) $this->cm->getAllVendorById(' and v.vendor_id = '.$vendor_quote['vendor_id']);
                 $emArr['user_id'] = $po_data['vendor_user_id'];
                 $emArr['subject'] = $subject;
                 $emArr['body'] = str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivey_port##','##delivery_date##','##req_type##','##user_name##','##agent_name##','##agent_email##','##agent_phone##','##send_by##','##degination##','##send_email##','##mobile_no##'),array(ucwords($po_data['ship_name']),$po_data['po_no'],$po_data['imo_no'],$po_data['delivery_port'],$po_data['delivery_date'],ucwords(str_replace(array('_'),array(' '),$po_data['requisition_type'])),$po_data['vendor_name'],ucwords($po_data['agent_name']),$po_data['agent_email'],$po_data['agent_phone'],$user_session_data->first_name.' '.$user_session_data->last_name,$user_session_data->role_name,$user_session_data->email,$user_session_data->phone),$emailTemplateData->email_body); 
                     $emArr['attechment'] = implode(',', $attachmentArray); 
                    $this->em->add_email_log($emArr);

                  // $this->um->sendMail($vendorData['email'],$subject,$body,$attachmentArray);
              }

             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
             if(!empty($email_roles)){
               foreach ($email_roles as $row) {
                  $user_list = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_list)){
                       foreach ($user_list as $val) {
                           $emArr['user_id'] = $val->user_id;
                           $emArr['subject'] = $subject;
                           $emArr['body'] = str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivey_port##','##delivery_date##','##req_type##','##user_name##','##agent_name##','##agent_email##','##agent_phone##','##send_by##','##degination##','##send_email##','##mobile_no##'),array(ucwords($po_data['ship_name']),$po_data['po_no'],$po_data['imo_no'],$po_data['delivery_port'],$po_data['delivery_date'],ucwords(str_replace(array('_'),array(' '),$po_data['requisition_type'])),ucwords($val->user_name),ucwords($po_data['agent_name']),$po_data['agent_email'],$po_data['agent_phone'],$user_session_data->first_name.' '.$user_session_data->last_name,$user_session_data->role_name,$user_session_data->email,$user_session_data->phone),$emailTemplateData->email_body);
                           $emArr['attechment'] = implode(',', $attachmentArray);
                           $this->em->add_email_log($emArr);
                       }
                    }
                  }
                } 
                 
          }
          /*PO Created Email Notification to Admin and Vendor*/
      }
     $vars['productArr'] = $productArr;
     $vars['dataArr'] = $this->input->post();
     $data = $this->load->view('additional_order_stock',$vars,true);
     $returnArr['data'] = $data;  
     echo json_encode($returnArr);
   }

   function deletePoSession(){
     $ship_order_id = trim($this->input->post('id'));
     deleteCustomSession('order_basic_details_'.$ship_order_id);
     $returnArr['status'] = 200;
     echo json_encode($returnArr);
   }

 function validate_stock_entiries(){
    $products = $this->mp->getAllProduct( ' AND  p.`status`=1 ' ,'R');
    $count = 0;    
    $validation = 0;
     if($products){ 
       foreach ($products as $row) {
          $quantity = $this->input->post('qty_'.$row->product_id); 
          $price = $this->input->post('unit_price_'.$row->product_id);  
           if(!empty($quantity)){
            $count++;
            
             if(empty($price)){
               $this->form_validation->set_rules('unit_price_'.$row->product_id,'','trim|required',array('required'=>'Unit Price field is required'));
               $validation++;
             }   

           }

       }
     } 

   $ship_stock_id = trim($this->input->post('id'));
    if(!empty($ship_stock_id)){
       $this->form_validation->set_rules('stock_date','Stock Date','trim|required|callback_check_already_stock');  
      $validation++;        

    }  

   if(empty($count)){
     $this->form_validation->set_rules('product_id','Product','trim|required',array('required'=>'Please enter atleast one product quantity.'));
      $validation++;        
    }

    if($validation){
        return $this->form_validation->run(); 
    }
    else{
        return true;
    }   

  }

  function check_already_stock(){
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $ship_stock_id = trim($this->input->post('id'));
    $date = trim($this->input->post('stock_date'));
    $monthYear = convertDate($date,'','m Y');
    $stock = $this->cm->getShipStockById($ship_id,' AND st.ship_stock_id !='.$ship_stock_id);
     $count = 0; 
     if(!empty($stock)){
        foreach ($stock as $row) {
          $dbmonthYear = $row->month.' '.$row->year;
           if($dbmonthYear==$monthYear){
             $count++; 
           } 
        }
     }

     if($count){
       return false; 
     }
     else{
       return true; 
     }
  }

 function work_order_list($ship_id=''){
    checkUserSession();
    $ship_id = base64_decode($ship_id);
    $user_session_data = getSessionData();
    $vars['ship_id'] = $ship_id;
    $vars['vendors'] = $this->um->getallVendor(' AND u.status = 1','R','','',' ORDER BY u.first_name ASC');
    $data = $this->load->view('work_order_list',$vars,true);
    // echo $this->db->last_query();die;
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  } 

 function getAllWorkOrderList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    $ship_id = $this->input->post('ship_id');
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   
     
    if(!empty($ship_id)){
      $where .= ' AND wo.ship_id='.$ship_id;  
    }

    if(!empty($rfq_status)){
      $where .= ' AND wo.status='.$rfq_status;  
    }

    if(!empty($vendor_id)){
     $where .= ' AND wo.vendor_id in('.implode(',',$vendor_id).')';   
    }

    if($created_on){
     $where .= ' AND date(wo.created_on) = "'.convertDate($created_on,'','Y-m-d').'"';      
    }

    if(!empty($keyword)){
     $where .= " AND ( wo.po_no like '%".trim($keyword)."%' OR so.rfq_no like '%".trim($keyword)."%' OR wo.order_id like '%".trim($keyword)."%' )";   
    }


    if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Po N0'){
                $order_by = 'ORDER BY wo.po_no '.$sort_type;
            }
            elseif($sort_column == 'Order ID'){
                $order_by = 'ORDER BY wo.order_id '.$sort_type;
            }
            elseif($sort_column == 'RFQ No'){
                $order_by = 'ORDER BY so.rfq_no '.$sort_type;
            }
            elseif($sort_column == 'Vendor Name'){
                $order_by = 'ORDER BY vu.first_name '.$sort_type;
            }
            elseif($sort_column == 'Added On'){
                $order_by = 'ORDER BY wo.created_on '.$sort_type;
            }
            elseif($sort_column == 'Added By'){
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
            elseif($sort_column == 'Status'){
                $order_by = 'ORDER BY wo.status '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY wo.created_on DESC';
        }

      if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllWorkOrders($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }    

    if($download==1){
        $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'WorkOrder';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
       }
 
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Po No','Order ID','RFQ No','Vendor Name','Remark','Created On','Created By','Stage');
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
                    ),'cellArray'=>array('A7:H7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7; 
           $work_orders = $this->cm->getAllWorkOrders($where,'R',$perPage,$offset,$order_by);
          if($work_orders){
            foreach ($work_orders as $row) {
                $k++;
                 if($row->status==1){
                 $stage = 'Raised';
                }
                elseif($row->status==2){
                 $stage = 'Accepted by vendor';
                }
                elseif($row->status==3){
                 $stage = 'DN Created';                
                }
                elseif($row->status==4){
                 $stage = 'Invoice Uploaded';                
                }
                elseif($row->status==5){
                 $stage = 'Temprory Cancel';                
                }
                elseif($row->status==6){
                 $stage = 'Permanent Cancel';                
                }
                $arrayData[] = array($row->po_no,$row->order_id,$row->rfq_no,ucfirst($row->vendor_name),$row->remark,ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucfirst($row->created_by),$stage);
            }
          } 

          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   

    }    

   $countdata = $this->cm->getAllWorkOrders($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
   $work_orders = $this->cm->getAllWorkOrders($where,'R',$perPage,$offset,$order_by);
   $update_stage = checkLabelByTask('update_stage');
   $add_delivery_note = checkLabelByTask('add_delivery_note');
   // $sign_delivery_note = checkLabelByTask('sign_delivery_note');
   // $manage_work_order = checkLabelByTask('manage_work_order');
   $cancel_po_lable = checkLabelByTask('cancel_po');
  // echo $this->db->last_query();die;
   if($work_orders){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($work_orders)).' of '.$countdata.' entries';
         foreach ($work_orders as $row){
          $vIn = $us = $cancel_po = $dn = '' ;
           if($row->status<2){
                if($update_stage){
                 $us = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Purchase Order Stage\',\'shipping/changePOStatus\',\''.$row->work_order_id.'\',\'\',\'60%\',\'\')">Update Stage</a>';
                 }
            }

           if($row->status==2){
             if($add_delivery_note){
                 $dn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Create Delivery Note\',\'shipping/add_delivery_note\',\''.$row->work_order_id.'\',\'\',\'98%\',\'full-width-model\')">Create Delivery Note</a>';
              }
           } 

           if(($row->status==1 || $row->status==2 || $row->status==3)  && $row->is_dn_sign==0){
             if($cancel_po_lable){
                  $cancel_po = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Cancel Purchase Order\',\'shipping/cancelPO\',\''.$row->work_order_id.'\',\''.$row->ship_order_id.'\',\'50%\',\'\')">Cancel Purchase Order</a>';
              }
           }

            $poIn = '<a href="'.base_url().'shipping/printPurchaseOrderPdf/'.$row->work_order_id.'" target="_blank">Download PO</a>';
            $podIn = '<a href="'.base_url().'shipping/printPurchaseOrderDetailedPdf/'.$row->work_order_id.'" target="_blank">Download Details PO</a>';
           // $download_rfq = '<a href="'.base_url().'shipping/download_updated_rfq_xls/'.base64_encode($row->ship_order_id).'">Download RFQ</a>';    
        
            if($row->status==1){
             $stage = '<span class="badge badge-danger">Raised</span>';
            }
            elseif($row->status==2){
             $stage = '<span class="badge badge-success">Accepted by vendor</span>';
            }
            elseif($row->status==3){
             $stage = '<span class="badge badge-primary">DN Created</span>';                
            }
            elseif($row->status==4){
             $stage = '<span class="badge badge-primary">Invoice Uploaded</span>';                
            }
            elseif($row->status==5){
             $stage = '<span class="badge badge-warning">Temprory Cancel</span>';    
            }
            elseif($row->status==6){
             $stage = '<span class="badge badge-dark">Permanent Cancel</span>';    
            }

            $returnArr .= "<tr id='row-".$row->work_order_id."'>
                              <td width='10%' data-toggle='tooltip' data-placement='top' title='$row->po_no'>".$row->po_no."</td>
                              <td width='10%'>".$row->order_id."</td>
                              <td width='10%'>".$row->rfq_no."</td>
                              <td width='10%'>".ucfirst($row->vendor_name)."</td>
                              <td width='10%'>".$row->remark."</td>
                              <td width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</td>
                              <td width='10%'>".ucfirst($row->created_by)."</td>
                              <td width='10%'>".$stage."</td>
                              ";
                $returnArr .= '<td width="3%" class="action-td" style="text-align:center;"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$cancel_po.'</li>
                                <li>'.$dn.'</li>
                                <li>'.$vIn.'</li>
                                <li>'.$us.'</li>
                                <li>'.$download_rfq.'</li> 
                                <li>'.$poIn.'</li>
                                <li>'.$podIn.'</li>

                               </ul>
                                </div></td> </tr>'; 
         }

         if($countdata <= 5){
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='3%'></td></tr>";
           $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
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
    
 function add_delivery_note(){
     checkUserSession();
     $user_session_data = getSessionData();
     $this->load->model('email_manager');
     $this->em = $this->email_manager;
     $returnArr['status'] = '100';
     $ship_details = getCustomSession('ship_details');
     $actionType = $this->input->post('actionType');
     $work_order_id = $this->input->post('id');
     $data = (array) $this->cm->getWorkDetailsByID(' AND w.work_order_id = '.$work_order_id);
     $json_data = unserialize($data['json_data']);
     
     $productArr = [];
     $a = [];
     if(!empty($json_data)){
       for ($i=0; $i <count($json_data) ; $i++) {
            $a[$json_data[$i]['product_id']] = $json_data[$i];
       } 
     }

     if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
            $productArr[$row->sequence][$row->category_name][] = array(
             'category_name'=>$row->category_name,
             'product_category_id'=>$row->product_category_id,
             'product_name'=>$row->product_name,
             'product_id'=>$row->product_id,
             'unit'=>$row->unit,
             'item_no'=>$row->item_no,
             'sequence'=>$row->sequence,
             'qty'=>$a[$row->product_id]['qty'],
             'unit_price'=>(($a[$row->product_id]['sc_unit_price']) ? $a[$row->product_id]['sc_unit_price'] : $a[$row->product_id]['unit_price'])
             ); 
          } 
        }
     } 

   if($actionType=='save'){

       $this->form_validation->set_rules('date','Date','trim|required');

       if($this->form_validation->run()){
        $dataArr['ship_id'] = $ship_details['ship_id'];
        $dataArr['work_order_id'] = $data['work_order_id'];
        $dataArr['added_on'] = date('Y-m-d H:i:s');
        $dataArr['date'] = ConvertDate(trim($this->input->post('date')),'','Y-m-d');
        $dataArr['added_by'] = $user_session_data->user_id;
        $dataArr['note_no'] = $data['note_no'];    
        // for ($p=0; $p < 500; $p++) { 
        $delivery_note_id = $this->cm->add_delivery_note($dataArr);
        $batch = array();
         if(!empty($json_data)){
           for ($k=0; $k <count($json_data); $k++) { 
            $product_id = $json_data[$k]['product_id'];
             $remark = $this->input->post('remark_'.$product_id);
             $batch[] = array('delivery_note_id'=>$delivery_note_id,'product_id'=>$product_id,'quantity'=>$json_data[$k]['qty'],'unit_price'=>(($json_data[$k]['sc_unit_price']) ? $json_data[$k]['sc_unit_price']: $json_data[$k]['unit_price'] ),'remark'=>$remark,'price'=>($json_data[$k]['qty'] * (($json_data[$k]['sc_unit_price']) ? $json_data[$k]['sc_unit_price'] : $json_data[$k]['unit_price'])));
            }
         }

        $this->db->insert_batch('delivery_note_details',$batch);
        $new_json_data = serialize($batch);
        $this->cm->edit_delivery_note(array('json_data'=>$new_json_data),' delivery_note_id ='.$delivery_note_id);
        $this->db->update('work_order',array('status'=>3),array('work_order_id'=>$work_order_id)); 
        // }

        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'Delivery Note Added successfully.';

         /*Delivery Note Created Email Notification to Admin and Vendor*/
        
          $whereEm = ' AND em.template_code = "dn_created"';
          $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
          if(!empty($emailTemplateData)){
           
            require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
            $emArr['subject'] = $emArr1['subject'] =  str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivey_port##','##delivery_date##','##req_type##'),array(ucwords($data['ship_name']),$data['po_no'],$data['imo_no'],$data['delivery_port'],convertDate($data['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$data['requisition_type']))),$emailTemplateData->email_subject);
            $emArr['body'] =  $emArr1['body'] = $emailTemplateData->email_body; 
            $data2 = (array) $this->cm->getDeliveyNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
            $recept_data = unserialize($data2['json_data']);
            $proPdfArr = array();
            if(!empty($recept_data)){
               for ($i=0; $i <count($recept_data) ; $i++) {
                    $recept_data[$i] = (array) $recept_data[$i];
                         $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$recept_data[$i]['product_id']);
                       $product_id = $product['product_id'];
                       $proPdfArr[$product['sequence']][$product['category_name']][] = array(
                         'category_name'=>$product['category_name'],
                         'product_category_id'=>$product['product_category_id'],
                         'product_name'=>$product['product_name'],
                         'product_id'=>$product_id,
                         'unit'=>$product['unit'],
                         'item_no'=>$product['item_no'],
                         'sequence'=>$product['sequence'],
                         'quantity'=>$recept_data[$i]['quantity'],
                         'unit_price'=> $recept_data[$i]['unit_price'],
                         'remark'=> $recept_data[$i]['remark']
                      );   
                    } 
               }  
              $pdf_vars['productArr'] = $proPdfArr;
              $pdf_vars['dataArr'] = $data2;
              $pdf_vars['view_file'] = 'delivery_note_pdf_email';
              $pdf_vars['title'] = 'Delivery Note';
              $html = $this->load->view('delivery_note_pdf_email',$pdf_vars,TRUE);
              $file = str_replace('/','--','DN-'.$data['note_no']);
              $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
              $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
              $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
              $pdf->AddPage('L');
              $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                ob_clean();ob_end_clean();ob_flush();
              $pdfData = $pdf->Output('', 'S');
              $filePath = $pdfFilePath;
              write_file($filePath, $pdfData);
              $emArr['attechment'] = $emArr1['attechment'] = $file;

            if($data['captain_user_id']){
                $emArr['user_id'] = $data['captain_user_id'];
                $this->em->add_email_log($emArr);
            }

             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
             if(!empty($email_roles)){
               foreach ($email_roles as $row) {
                  $user_list = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_list)){
                       foreach ($user_list as $val) {
                           $emArr1['user_id'] = $val->user_id;
                           $this->em->add_email_log($emArr1);
                       }
                    }
                  } 
               }
         }
                
        /*Delivery Note Created Email Notification to Admin and Vendor*/
       }
     }
     $vars['productArr'] = $productArr;
     $vars['delivery_note_no'] = $data['note_no'];
     $vars['dataArr'] = $this->input->post();
     $data = $this->load->view('add_delivery_note',$vars,true);    
     $returnArr['data'] = $data;
     echo json_encode($returnArr); 
  }   


  // function add_delivery_note(){
  //    checkUserSession();
  //    $user_session_data = getSessionData();
  //    $returnArr['status'] = '100';
  //    $ship_details = getCustomSession('ship_details');
  //    $actionType = $this->input->post('actionType');
  //    $work_order_id = $this->input->post('id');
  //    $data = (array) $this->cm->getWorkDetailsByID(' AND w.work_order_id = '.$work_order_id);
  //    $json_data = unserialize($data['json_data']);
     
  //    $productArr = [];
  //    $a = [];
  //    if(!empty($json_data)){
  //      for ($i=0; $i <count($json_data) ; $i++) {
  //        $a[$json_data[$i]['product_id']] = $json_data[$i];
  //      } 
  //    }

  //    if(!empty($a)){
  //       $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
  //       if(!empty($products)){
  //          foreach ($products as $row) {
  //           $productArr[$row->sequence][$row->category_name][] = array(
  //            'category_name'=>$row->category_name,
  //            'product_category_id'=>$row->product_category_id,
  //            'product_name'=>$row->product_name,
  //            'product_id'=>$row->product_id,
  //            'unit'=>$row->unit,
  //            'item_no'=>$row->item_no,
  //            'sequence'=>$row->sequence,
  //            'qty'=>$a[$row->product_id]['qty'],
  //            'unit_price'=>$a[$row->product_id]['sc_unit_price']
  //            ); 
  //         } 
  //       }
  //    } 

  //  if($actionType=='save'){
  //    $this->form_validation->set_rules('date','Date','trim|required');
  //      if($this->form_validation->run()){
  //       $dataArr['ship_id'] = $ship_details['ship_id'];
  //       $dataArr['work_order_id'] = $data['work_order_id'];
  //       $dataArr['added_on'] = date('Y-m-d H:i:s');
  //       $dataArr['date'] = ConvertDate(trim($this->input->post('date')),'','Y-m-d');
  //       $dataArr['added_by'] = $user_session_data->user_id;
  //       $dataArr['note_no'] = $data['note_no'];    
  //       $delivery_note_id = $this->cm->add_tmp_delivery_note($dataArr);
  //       $batch = array();
  //        if(!empty($json_data)){
  //          for ($k=0; $k <count($json_data); $k++) { 
  //           $product_id = $json_data[$k]['product_id'];
  //            $remark = $this->input->post('remark_'.$product_id);
  //            $batch[] = array('delivery_note_id'=>$delivery_note_id,'product_id'=>$product_id,'quantity'=>$json_data[$k]['qty'],'unit_price'=>$json_data[$k]['sc_unit_price'],'remark'=>$remark,'price'=>($json_data[$k]['qty'] * $json_data[$k]['sc_unit_price']));
  //           }
  //        }

  //       $new_json_data = serialize($batch);
  //       $this->cm->edit_tmp_delivery_note(array('json_data'=>$new_json_data),' delivery_note_id ='.$delivery_note_id);
  //       $returnArr['status'] = 200;
  //       $returnArr['delivery_note_id'] = $delivery_note_id;
  //      }

  //    }
  //    $vars['productArr'] = $productArr;
  //    $vars['delivery_note_no'] = $data['note_no'];
  //    $vars['dataArr'] = $this->input->post();
  //    $data = $this->load->view('add_delivery_note',$vars,true);    
  //    $returnArr['data'] = $data;
  //    echo json_encode($returnArr); 
  // }   


  // function delivery_feedback(){
  //    checkUserSession();
  //    $user_session_data = getSessionData();
  //    $this->load->model('email_manager');
  //    $this->em = $this->email_manager;
  //    $returnArr['status'] = '100';
  //    $actionType = trim($this->input->post('actionType'));
  //    $tmp_delivery_note_id = $this->input->post('id');

  //    if($tmp_delivery_note_id){
  //      $tmp_data = (array) $this->cm->getTmpdeliverynote($tmp_delivery_note_id);
  //      $data = (array) $this->cm->getWorkDetailsByID(' AND w.work_order_id = '.$tmp_data['work_order_id']);
  //    }

     
  //    $fresh_provision = trim($this->input->post('fresh_provision'));
  //    $dry_provision = trim($this->input->post('dry_provision'));
  //    $marking_provision = trim($this->input->post('marking_provision'));
  //    $supplier_onboard = trim($this->input->post('supplier_onboard'));


  //    if($actionType=='save'){
  //       $this->form_validation->set_rules('fresh_provision','Rating','trim|required');
  //       $this->form_validation->set_rules('dry_provision','Rating','trim|required');
  //       $this->form_validation->set_rules('marking_provision','Rating','trim|required');
  //       $this->form_validation->set_rules('supplier_onboard','Rating','trim|required');
  //        $this->form_validation->set_rules('overall_performance','Rating','trim|required');

  //       $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
  //       if(!empty($fresh_provision) && $fresh_provision<=3){
  //          $this->form_validation->set_rules('fp_comment','Comment','trim|required');  
  //          if (empty($_FILES['fp_img']['name'])) {
  //               $this->form_validation->set_rules('fp_img', 'Image', 'trim|required');
  //           } 
  //           else {
  //               $fpmimeType = mime_content_type($_FILES['fp_img']['tmp_name']);
  //               if (!in_array($fpmimeType, $allowedMimeTypes)) {
  //                   $this->form_validation->set_rules('fp_img', 'Image', 'trim|required');
  //                   $this->form_validation->set_message('fp_img', 'The file is not a valid image.');
  //               }

  //           }
  //       }

  //       if(!empty($dry_provision) && $dry_provision<=3){
  //           $this->form_validation->set_rules('dp_comment','Comment','trim|required');  
  //            if (empty($_FILES['dp_img']['name'])) {
  //               $this->form_validation->set_rules('dp_img','Image','trim|required');
  //           }
  //           else {
  //               $dpmimeType = mime_content_type($_FILES['dp_img']['tmp_name']);
  //               if (!in_array($dpmimeType, $allowedMimeTypes)) {
  //                   $this->form_validation->set_rules('dp_img', 'Image', 'trim|required');
  //                   $this->form_validation->set_message('dp_img', 'The file is not a valid image.');
  //               }

  //           }
  //       }

  //       if(!empty($marking_provision) && $marking_provision<=3){
  //           $this->form_validation->set_rules('mp_comment','Comment','trim|required'); 
  //            if (empty($_FILES['mp_img']['name'])) {
  //               $this->form_validation->set_rules('mp_img','Image','trim|required');
  //           }
  //           else {
  //               $mpmimeType = mime_content_type($_FILES['mp_img']['tmp_name']);
  //               if (!in_array($mpmimeType, $allowedMimeTypes)) {
  //                   $this->form_validation->set_rules('mp_img', 'Image', 'trim|required');
  //                   $this->form_validation->set_message('mp_img', 'The file is not a valid image.');
  //               }

  //           }
  //       }

  //      if($this->form_validation->run()){

  //           if($tmp_data){
  //               $noteArr['ship_id'] = $tmp_data['ship_id'];
  //               $noteArr['work_order_id'] = $work_order_id = $tmp_data['work_order_id'];
  //               $noteArr['added_on'] =$tmp_data['added_on'];
  //               $noteArr['date'] = $tmp_data['date'];
  //               $noteArr['added_by'] = $tmp_data['added_by'];
  //               $noteArr['note_no'] = $tmp_data['note_no'];
  //               $noteArr['json_data'] = $json_data = $tmp_data['json_data'];
  //                $delivery_note_id = $this->cm->add_delivery_note($noteArr);
  //               $batch = array();
  //                if(!empty($json_data)){
  //                  for ($k=0; $k <count($json_data); $k++) { 
  //                     $product_id = $json_data[$k]['product_id'];
  //                     $remark = $this->input->post('remark_'.$product_id);
  //                     $batch[] = array('delivery_note_id'=>$delivery_note_id,'product_id'=>$product_id,'quantity'=>$json_data[$k]['qty'],'unit_price'=>$json_data[$k]['sc_unit_price'],'remark'=>$remark,'price'=>($json_data[$k]['qty'] * $json_data[$k]['sc_unit_price']));
  //                   }
  //                }

  //                $this->db->insert_batch('delivery_note_details',$batch);
  //                $this->db->update('work_order',array('status'=>3),array('work_order_id'=>$work_order_id)); 

  //                /*Delivery Note Created Email Notification to Admin and Vendor*/
        
  //                 $whereEm = ' AND em.template_code = "dn_created"';
  //                 $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
  //                 if(!empty($emailTemplateData)){
                   
  //                   require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
  //                   $emArr['subject'] = $emArr1['subject'] =  str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivey_port##','##delivery_date##','##req_type##'),array(ucwords($data['ship_name']),$data['po_no'],$data['imo_no'],$data['delivery_port'],convertDate($data['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$data['requisition_type']))),$emailTemplateData->email_subject);
  //                   $emArr['body'] =  $emArr1['body'] = $emailTemplateData->email_body; 
  //                   $data2 = (array) $this->cm->getDeliveyNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
  //                   $recept_data = unserialize($data2['json_data']);
  //                   $proPdfArr = array();
  //                   if(!empty($recept_data)){
  //                      for ($i=0; $i <count($recept_data) ; $i++) {
  //                           $recept_data[$i] = (array) $recept_data[$i];
  //                                $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$recept_data[$i]['product_id']);
  //                              $product_id = $product['product_id'];
  //                              $proPdfArr[$product['sequence']][$product['category_name']][] = array(
  //                                'category_name'=>$product['category_name'],
  //                                'product_category_id'=>$product['product_category_id'],
  //                                'product_name'=>$product['product_name'],
  //                                'product_id'=>$product_id,
  //                                'unit'=>$product['unit'],
  //                                'item_no'=>$product['item_no'],
  //                                'sequence'=>$product['sequence'],
  //                                'quantity'=>$recept_data[$i]['quantity'],
  //                                'unit_price'=> $recept_data[$i]['unit_price'],
  //                                'remark'=> $recept_data[$i]['remark']
  //                             );   
  //                           } 
  //                      }  
  //                     $pdf_vars['productArr'] = $proPdfArr;
  //                     $pdf_vars['dataArr'] = $data2;
  //                     $pdf_vars['view_file'] = 'delivery_note_pdf_email';
  //                     $pdf_vars['title'] = 'Delivery Note';
  //                     $html = $this->load->view('delivery_note_pdf_email',$pdf_vars,TRUE);
  //                     $file = str_replace('/','--','DN-'.$data['note_no']);
  //                     $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
  //                     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  //                     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  //                     $pdf->AddPage('L');
  //                     $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  //                       ob_clean();ob_end_clean();ob_flush();
  //                     $pdfData = $pdf->Output('', 'S');
  //                     $filePath = $pdfFilePath;
  //                     write_file($filePath, $pdfData);

  //                           //feedback pdf 
  //                     $feedback_vars['data'] = (array) $this->cm->getfeedbackByID($delivery_note_id);
  //                     $feedback_vars['view_file'] = 'feedback_pdf';
  //                     $feedback_vars['title'] = 'Feedback';
  //                     $html = $this->load->view('feedback_pdf',$feedback_vars,TRUE);
  //                     $feedback_file = str_replace('/','--','feedback-'.$data['note_no']);
  //                     $feedbackpdfFilePath = FCPATH . "uploads/work_order_pdfs/".$feedback_file.".pdf";
  //                     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  //                     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  //                     $pdf->AddPage('L');
  //                     $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  //                       ob_clean();ob_end_clean();ob_flush();
  //                     $pdfData = $pdf->Output('', 'S');
  //                     $filePath = $feedbackpdfFilePath;
  //                     write_file($filePath, $pdfData);
  //                     $attechmentArr = array($file,$feedback_file);
  //                     $emArr['attechment'] = $emArr1['attechment'] = implode(',',$attechmentArr);

  //                   if($data['captain_user_id']){
  //                       $emArr['user_id'] = $data['captain_user_id'];
  //                       $this->em->add_email_log($emArr);
  //                   }

  //                    $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
  //                    if(!empty($email_roles)){
  //                      foreach ($email_roles as $row) {
  //                         $user_list = $this->em->getUserByRoleID($row->role_id);
  //                          if(!empty($user_list)){
  //                              foreach ($user_list as $val) {
  //                                  $emArr1['user_id'] = $val->user_id;
  //                                  $this->em->add_email_log($emArr1);
  //                              }
  //                           }
  //                         } 
  //                      }
  //                }
                
  //                /*Delivery Note Created Email Notification to Admin and Vendor*/
  //           }

  //           $dataArr['delivery_note_id'] = $delivery_note_id;
  //           $dataArr['fresh_provision'] = $fresh_provision;
  //           $dataArr['dry_provision'] = $dry_provision;
  //           $dataArr['marking_provision'] = $marking_provision;
  //           $dataArr['supplier_onboard'] = $supplier_onboard;
  //           $dataArr['overall_performance'] = trim($this->input->post('overall_performance'));
  //           $dataArr['comment'] = trim($this->input->post('comment'));
  //           $dataArr['dp_comment'] = trim($this->input->post('dp_comment'));
  //           $dataArr['fp_comment'] = trim($this->input->post('fp_comment'));
  //           $dataArr['mp_comment'] = trim($this->input->post('mp_comment'));
  //           $dataArr['added_on'] = date('Y-m-d H:i:s');
  //           $dataArr['added_by'] = $user_session_data->user_id;

  //           $config['upload_path'] = FCPATH.'uploads/user_rating'; 
  //           $config['allowed_types'] = 'jpg|png|jpeg|gif|pdf'; 
  //           $config['max_size'] = 2048;
  //           $this->load->library('upload', $config);
  //           $this->upload->initialize($config);

  //           if($this->upload->do_upload('fp_img')) {
  //               $fpimgdata = $this->upload->data();
  //               $dataArr['fp_img'] = $fpimgdata['file_name'];
  //           }

  //           if($this->upload->do_upload('dp_img')) {
  //               $dpimgdata = $this->upload->data();
  //               $dataArr['dp_img'] = $dpimgdata['file_name'];
  //           }

  //           if($this->upload->do_upload('mp_img')) {
  //               $mpimgdata = $this->upload->data();
  //               $dataArr['mp_img'] = $mpimgdata['file_name'];
  //           }
  
  //           $this->cm->add_delivery_feedback($dataArr);
  //           $this->cm->deleteTmpDeliveryNote($tmp_delivery_note_id);
  //           $returnArr['status'] = 101;
  //           $returnArr['returnMsg'] = 'The delivery note has been added successfully.';
  //      }    

  //   }

  //    $vars['data'] = $data;   
  //    $vars['dataArr'] = $this->input->post();
  //    $data = $this->load->view('delivery_feedback_form',$vars,true);    
  //    $returnArr['data'] = $data;
  //    echo json_encode($returnArr);
  // }


    function delivery_feedback(){
     checkUserSession();
     $user_session_data = getSessionData();
     $this->load->model('email_manager');
     $this->em = $this->email_manager;
     $returnArr['status'] = '100';
     $actionType = trim($this->input->post('actionType'));
     $tmp_delivery_receipt_id = $this->input->post('id');

     if($tmp_delivery_receipt_id){
       $data = (array) $this->cm->getTmpdeliveryReceipt($tmp_delivery_receipt_id);
     }
     
     $fresh_provision = trim($this->input->post('fresh_provision'));
     $dry_provision = trim($this->input->post('dry_provision'));
     $marking_provision = trim($this->input->post('marking_provision'));
     $supplier_onboard = trim($this->input->post('supplier_onboard'));


     if($actionType=='save'){
        $this->form_validation->set_rules('fresh_provision','Rating','trim|required');
        $this->form_validation->set_rules('dry_provision','Rating','trim|required');
        $this->form_validation->set_rules('marking_provision','Rating','trim|required');
        $this->form_validation->set_rules('supplier_onboard','Rating','trim|required');
         $this->form_validation->set_rules('overall_performance','Rating','trim|required');

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if(!empty($fresh_provision) && $fresh_provision<=3){
           $this->form_validation->set_rules('fp_comment','Comment','trim|required');  
           if (empty($_FILES['fp_img']['name'])) {
                $this->form_validation->set_rules('fp_img', 'Image', 'trim|required');
            } 
            else {
                $fpmimeType = mime_content_type($_FILES['fp_img']['tmp_name']);
                if (!in_array($fpmimeType, $allowedMimeTypes)) {
                    $this->form_validation->set_rules('fp_img', 'Image', 'trim|required');
                    $this->form_validation->set_message('fp_img', 'The file is not a valid image.');
                }

            }
        }

        if(!empty($dry_provision) && $dry_provision<=3){
            $this->form_validation->set_rules('dp_comment','Comment','trim|required');  
             if (empty($_FILES['dp_img']['name'])) {
                $this->form_validation->set_rules('dp_img','Image','trim|required');
            }
            else {
                $dpmimeType = mime_content_type($_FILES['dp_img']['tmp_name']);
                if (!in_array($dpmimeType, $allowedMimeTypes)) {
                    $this->form_validation->set_rules('dp_img', 'Image', 'trim|required');
                    $this->form_validation->set_message('dp_img', 'The file is not a valid image.');
                }

            }
        }

        if(!empty($marking_provision) && $marking_provision<=3){
            $this->form_validation->set_rules('mp_comment','Comment','trim|required'); 
             if (empty($_FILES['mp_img']['name'])) {
                $this->form_validation->set_rules('mp_img','Image','trim|required');
            }
            else {
                $mpmimeType = mime_content_type($_FILES['mp_img']['tmp_name']);
                if (!in_array($mpmimeType, $allowedMimeTypes)) {
                    $this->form_validation->set_rules('mp_img', 'Image', 'trim|required');
                    $this->form_validation->set_message('mp_img', 'The file is not a valid image.');
                }

            }
        }

       if($this->form_validation->run()){
            $recept_data['delivery_note_id'] = $delivery_note_id = $data['delivery_note_id'];
            $recept_data['added_on'] = date('Y-m-d H:i:s');
            $recept_data['added_by'] = $user_session_data->user_id;
            $recept_data['e_sign'] = $data['e_sign'];
            $recept_data['json_data'] = $data['json_data'];
             $this->db->insert('delivery_receipt',$recept_data);
            
            if($data['is_cn_required']==1){
                $this->db->update('company_invoice',array('status'=>'CN pending'),array('delivery_note_id'=>$delivery_note_id));
            }

             $this->db->update('delivery_note',array('is_cn_required'=>$data['is_cn_required'],'status'=>2),array('delivery_note_id'=>$delivery_note_id));

            $this->db->update('work_order',array('is_dn_sign'=>1),array('work_order_id'=>$data['work_order_id']));

             $returnArr['status'] = 200;
             $returnArr['returnMsg'] = 'Delivery Receipt Signed successfully'; 

             /*DN Signed Email Notification to Admin*/
              $whereEm = ' AND em.template_code = "dn_sign"';
              $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
              if(!empty($emailTemplateData)){
                $recdata = (array) $this->cm->getDeliveyReceipt(' AND dn.delivery_note_id ='.$delivery_note_id);
            
             $subject = str_replace(array('##ship_name##','##note_no##','##imo_no##','##port_name##','##delivery_date##','##req_type##'),array(ucwords($recdata['ship_name']),$recdata['note_no'],$recdata['imo_no'],ucwords($recdata['delivery_port']),convertDate($recdata['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$recdata['requisition_type']))),$emailTemplateData->email_subject);
                
              $body = str_replace(array('##port_name##','##delivery_date##','##ship_name##','##company_name##','##master_name##'),array(ucwords($recdata['delivery_port']),convertDate($recdata['delivery_date'],'','d-m-Y'),ucwords($recdata['ship_name']),ucwords($recdata['company_name']),ucfirst($user_session_data->first_name.' '.$user_session_data->last_name)),$emailTemplateData->email_body);


                require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
                $pdf_vars['dataArr'] = $recdata;
                $recept_data = unserialize($recdata['json_data']);
                $sign = array(); 
                if(!empty($recept_data)){
                  for ($r=0; $r < count($recept_data); $r++) { 
                     $sign[$recept_data[$r]['product_id']] = array('type'=>$recept_data[$r]['type'],'img_url'=>$recept_data[$r]['img_url'],'supply_qty'=>$recept_data[$r]['supply_qty'],'comment'=>$recept_data[$r]['comment']);  
                    }  
                }

                $mailArr = array();
                if(!empty($json_data)){
                 for ($i=0; $i <count($json_data) ; $i++) { 
                  $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
                   $product_id = $product['product_id'];
                   $mailArr[$product['sequence']][$product['category_name']][] = array(
                     'category_name'=>$product['category_name'],
                     'product_category_id'=>$product['product_category_id'],
                     'product_name'=>$product['product_name'],
                     'product_id'=>$product_id,
                     'unit'=>$product['unit'],
                     'item_no'=>$product['item_no'],
                     'sequence'=>$product['sequence'],
                     'quantity'=>$json_data[$i]['quantity'],
                     'unit_price'=> $json_data[$i]['unit_price'],
                     'type' => $sign[$product_id]['type'],
                     'img_url'=> $sign[$product_id]['img_url'],
                     'supply_qty'=> $sign[$product_id]['supply_qty'],
                     'comment'=> $sign[$product_id]['comment']
                   );   
                  } 
                } 
                  $pdf_vars['productArr'] = $mailArr;
                  $pdf_vars['title'] = 'Delivery Note Receipt';
                  $html = $this->load->view('delivery_note_pdf',$pdf_vars,TRUE);
                  $file = str_replace('/','--','DNR-'.$data['note_no']);
                  $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
                  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                  $pdf->AddPage('L');
                  $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                  ob_clean();ob_end_clean();ob_flush();
                  $pdfData = $pdf->Output('', 'S');
                  $filePath = $pdfFilePath;
                  write_file($filePath, $pdfData);

                  $feedback_vars['data'] = (array) $this->cm->getfeedbackByID($delivery_note_id);
                  $feedback_vars['view_file'] = 'feedback_pdf';
                  $feedback_vars['title'] = 'Feedback';
                  $html = $this->load->view('feedback_pdf',$feedback_vars,TRUE);
                  $feedback_file = str_replace('/','--','feedback-'.$data['note_no']);
                  $feedbackpdfFilePath = FCPATH . "uploads/work_order_pdfs/".$feedback_file.".pdf";
                  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                  $pdf->AddPage('L');
                  $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                    ob_clean();ob_end_clean();ob_flush();
                  $pdfData = $pdf->Output('', 'S');
                  $filePath = $feedbackpdfFilePath;
                  write_file($filePath, $pdfData);
                  $attechmentArr = array($file,$feedback_file);
                  $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
                          if(!empty($email_roles)){
                           foreach ($email_roles as $row) {
                              $user_list = $this->em->getUserByRoleID($row->role_id);
                               if(!empty($user_list)){
                                   foreach ($user_list as $val) {
                                       $emArr['user_id'] = $val->user_id;
                                       $emArr['subject'] = $subject;
                                       $emArr['body'] = $body;
                                       $emArr['attechment'] = implode(',',$attechmentArr);;
                                       $this->em->add_email_log($emArr);
                                   }
                                }           
                        }
                    }
            }

         if($user_session_data->code=='captain'){
           $wherenn = ' AND nt.code = "receipt_sign"';
           $templateData = $this->um->getNotifyTemplateByCode($wherenn);
           if(!empty($templateData)){
            $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
             if(!empty($roles)){
              foreach ($roles as $row) {
                 $user_data = $this->em->getUserByRoleID($row->role_id);
                  if(!empty($user_data)){
                    foreach ($user_data as $val) {
                      $noteArr['date'] = date('Y-m-d H:i:s');
                      $noteArr['user_id'] = $val->user_id;
                      $noteArr['title'] = $templateData->title;
                      $noteArr['row_id'] = $delivery_note_id;
                      $noteArr['entity'] = 'delivery_note';
                      $noteArr['ship_id'] = $data['ship_id'];
                      $noteArr['long_desc'] = str_replace(array(' ##captain_name##.','##ship_name##'),array(ucfirst($user_session_data->first_name.' '.$user_session_data->last_name),ucwords($ship_details['ship_name'])),$templateData->body); 
                       $this->um->add_notify($noteArr);  
                   }
                  }
                }
              }
            }
          }

          /*DN SIgned Email Notification to Admin*/

            $dataArr['delivery_note_id'] = $delivery_note_id;
            $dataArr['fresh_provision'] = $fresh_provision;
            $dataArr['dry_provision'] = $dry_provision;
            $dataArr['marking_provision'] = $marking_provision;
            $dataArr['supplier_onboard'] = $supplier_onboard;
            $dataArr['overall_performance'] = trim($this->input->post('overall_performance'));
            $dataArr['comment'] = trim($this->input->post('comment'));
            $dataArr['dp_comment'] = trim($this->input->post('dp_comment'));
            $dataArr['fp_comment'] = trim($this->input->post('fp_comment'));
            $dataArr['mp_comment'] = trim($this->input->post('mp_comment'));
            $dataArr['added_on'] = date('Y-m-d H:i:s');
            $dataArr['added_by'] = $user_session_data->user_id;

            $config['upload_path'] = FCPATH.'uploads/work_order_pdfs'; 
            $config['allowed_types'] = 'jpg|png|jpeg|gif|pdf'; 
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('fp_img')) {
                $fpimgdata = $this->upload->data();
                $dataArr['fp_img'] = $fpimgdata['file_name'];
            }

            if($this->upload->do_upload('dp_img')) {
                $dpimgdata = $this->upload->data();
                $dataArr['dp_img'] = $dpimgdata['file_name'];
            }

            if($this->upload->do_upload('mp_img')) {
                $mpimgdata = $this->upload->data();
                $dataArr['mp_img'] = $mpimgdata['file_name'];
            }
  
            $this->cm->add_delivery_feedback($dataArr);
            $this->cm->deleteTmpDeliveryReceipt($tmp_delivery_receipt_id);
            $returnArr['status'] = 101;
            $returnArr['returnMsg'] = 'The delivery receipt has been signed successfully.';
       }    

    }

     $vars['data'] = $data;   
     $vars['dataArr'] = $this->input->post();
     $data = $this->load->view('delivery_feedback_form',$vars,true);    
     $returnArr['data'] = $data;
     echo json_encode($returnArr);
  }

  function delivery_notes($ship_id=''){
        checkUserSession();
        $ship_id = base64_decode($ship_id);
        $user_session_data = getSessionData();
        $vars['ship_id'] = $ship_id;
        $data = $this->load->view('delivery_note_list',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

  function getAllDeliveryNoteList(){
            checkUserSession();
            $user_session_data = getSessionData();
            $where = '';
            $returnArr = '';
            extract($this->input->post());
            $cur_page = $page ? $page : 1;
            $perPage = $perPage ? $perPage : 25;
            // $ship_details = getCustomSession('ship_details');
            // $ship_id = $ship_details['ship_id'];

            if(!empty($ship_id)){
               $where .= ' AND dn.ship_id='.$ship_id;
            }

            if(!empty($keyword)){
             $where .= " AND ( dn.note_no like '%".trim($keyword)."%' or wo.po_no like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";
            }

            if($status){
              $where .= ' AND dn.status = '.$status;  
            }

            if($invoice_status){
              if($invoice_status=='Y'){
               $where .= ' AND dn.is_invoice_created = 1';    
              }
              elseif($invoice_status=='N'){
               $where .= ' AND dn.is_invoice_created = 0';    
              }
            }

            if($created_on){
              $where .= ' AND date(dn.added_on) = "'.convertDate($created_on,'','Y-m-d').'"';   
            }


            if((!empty($sort_cl)) && (!empty($sort_tyype))){
               if($sort_cl == 'Note No'){
                 $order_by = 'ORDER BY dn.note_no '.$sort_tyype;
                }
                elseif($sort_cl == 'Po No'){
                 $order_by = 'ORDER BY wo.po_no '.$sort_tyype;
                }
                elseif($sort_cl == 'Added On'){
                 $order_by = 'ORDER BY dn.added_on '.$sort_tyype;
                }
                elseif($sort_cl == 'Added By'){
                 $order_by = 'ORDER BY u.first_name '.$sort_tyype;
                }
                elseif($sort_cl == 'Invoice Created'){
                 $order_by = 'ORDER BY dn.is_invoice_created '.$sort_tyype;   
                }
                elseif($sort_cl == 'Status'){
                 $order_by = 'ORDER BY dn.status '.$sort_tyype;
                }
            }
            else{
             $order_by = 'ORDER BY dn.added_on DESC';
            }


  if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllDeliveryNote($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }

   if($download==1){
       $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'DeliveyNote';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Note No','PO No','Created On','Created By','Invoice Created','Status');
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
           $delivery_note = $this->cm->getAllDeliveryNote($where,'R',$perPage,$offset,$order_by);
           if($delivery_note){
            foreach ($delivery_note as $row) {
                $invoice_created = 'No';
                if($row->is_invoice_created==1){
                    $invoice_created = 'No';
                }
                if($row->status==1){
                 $status = 'Created';
                }
                elseif($row->status==2){
                 $status = 'Receipt Signed';
                }

                $k++;
                $arrayData[] = array($row->note_no,$row->po_no,ConvertDate($row->added_on,'','d-m-Y | h:i A'),ucfirst($row->user_name),$invoice_created,$status);
            }
           }

          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
    }

            $countdata = $this->cm->getAllDeliveryNote($where,'C');
            $offset = ($cur_page * $perPage) - $perPage;
            $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
            $delivery_note = $this->cm->getAllDeliveryNote($where,'R',$perPage,$offset,$order_by);
            $sign_delivery_note = checkLabelByTask('sign_delivery_note');
            $view_delivery_note = checkLabelByTask('view_delivery_note');
            $add_invoice = checkLabelByTask('add_invoice');
            $view_feedback = checkLabelByTask('view_feedback');
            if($delivery_note){
            $total_entries = 'Showing '.($offset+1).' to '.($offset+count($delivery_note)).' of '.$countdata.' entries';
            foreach ($delivery_note as $row){
            $dn = $vIn = $dnv = '';
            if($row->status==1){
              if($sign_delivery_note){
                $dn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Delivery Receipt\',\'shipping/delivery_receipt\',\''.$row->delivery_note_id.'\',\'\',\'98%\',\'full-width-model\')">Receipt</a>';
               }
             }
            
             
            if($row->is_invoice_created==0){
              if($add_invoice){
               // $vIn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Create Vendor Invoice\',\'shipping/create_invoice\',\''.$row->delivery_note_id.'\',\'\',\'98%\',\'full-width-model\')">Create Invoice</a>';
               // }

              $vIn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Shipping Company Invoice\',\'shipping/create_invoice\',\''.$row->delivery_note_id.'\',\'\',\'98%\',\'full-width-model\')">Create Invoice</a>';
            }
         }
            if($row->status==1){
              $status = '<span class="label label-success">Created</span>';
            }
            elseif($row->status==2){
              if($view_delivery_note){
                  $dn .= '<a href="'.base_url().'shipping/printDeliveryReceiptPdf/'.$row->delivery_note_id.'" target="_blank">Download Receipt</a>';
                  $dnv = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Delivery Receipt\',\'shipping/view_receipt\',\''.$row->delivery_note_id.'\',\'\',\'98%\',\'full-width-model\')">View Receipt</a>';

               }

              if($view_feedback){
                $feedback = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Feedback\',\'shipping/view_delivery_feedback\',\''.$row->delivery_note_id.'\',\'\',\'98%\',\'full-width-model\')">View Feedback</a>';
              } 
              $status = '<span style="color:green">Receipt Signed</span>';

            }

            $invoice_created = 'No';

            if($row->is_invoice_created==1){
                $invoice_created = 'No';
            }

            $download_dn = '<a href="'.base_url().'shipping/printDeliveryNotePdf/'.$row->delivery_note_id.'" target="_blank">Download Note</a>';

            $returnArr .= "<tr id='row-".$row->delivery_note_id."'>
            <td width='10%'  data-toggle='tooltip' data-placement='top' title='$row->note_no'>".$row->note_no."</td>
            <td width='10%'  data-toggle='tooltip' data-placement='top' title='$row->po_no'>".$row->po_no."</td>
            <td width='10%'>".ConvertDate($row->date,'','M Y')."</td>
            <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            <td width='10%'>".$invoice_created."</td>
            <td width='10%'>".$status."</td>
            ";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
            <li>'.$download_dn.'</li>
            <li>'.$feedback.'</li>
            <li>'.$dn.'</li>
            <li>'.$dnv.'</li>
            <li>'.$vIn.'</li>
            </ul>
            </div></td> </tr>';
            }

            $pagination = $pages->get_links();    


                 // if($countdata <= 5){
                 //    $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
                 //   $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
                 //   $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
                 //   $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
                 //   $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>"; 
                 //  }
            }
            else
            {
            $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
            }
            echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));

      }

  function view_receipt(){
    checkUserSession();
      $returnArr['status'] = 100;
      $actionType = $this->input->post('actionType');
      $delivery_note_id = $this->input->post('id');
      $data = (array) $this->cm->getDeliveyReceipt(' AND dn.delivery_note_id ='.$delivery_note_id);
       $recept_data = unserialize($data['json_data']);
       $rData = array();
       if(!empty($recept_data)){
        for ($j=0; $j <count($recept_data) ; $j++) { 
             $product_id = $recept_data[$j]['product_id'];
             $rData[$product_id] = $recept_data[$j];
           }   
       }
       $json_data = unserialize($data['line_data']);
       $productArr = array();
       $a = [];
       if(!empty($json_data)){
           for ($i=0; $i <count($json_data) ; $i++) { 
              $a[$json_data[$i]['product_id']] = $json_data[$i];
            } 
         } 

       if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
               $productArr[$row->sequence][$row->category_name][] = array(
                 'category_name'=>$row->category_name,
                 'product_category_id'=>$row->product_category_id,
                 'product_name'=>$row->product_name,
                 'product_id'=>$row->product_id,
                 'unit'=>$row->unit,
                 'item_no'=>$row->item_no,
                 'sequence'=>$row->sequence,
                 'quantity'=>$a[$row->product_id]['quantity'],
                 'unit_price'=> $a[$row->product_id]['unit_price'],
                 'type' => $rData[$row->product_id]['type'],
                 'img_url'=> $rData[$row->product_id]['img_url'],
                 'supply_qty'=> $rData[$row->product_id]['supply_qty'],
                 'comment'=> $rData[$row->product_id]['comment']
              );     
            } 
        }
     }   
      $vars['dataArr'] = $data;
      $vars['productArr'] = $productArr;           
      $data = $this->load->view('view_receipt',$vars,true);
      $returnArr['data'] = $data;
      echo json_encode($returnArr);   
  }   


  function view_delivery_feedback(){
    checkUserSession();
    $returnArr['status'] = 100;
    $delivery_note_id = trim($this->input->post('id'));
    $data = (array) $this->cm->getfeedbackByID($delivery_note_id);
    $vars['data'] = $data;       
    $data = $this->load->view('view_feedback',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  } 

  function stock_config(){
    checkUserSession();
    $user_session_data = getSessionData();
    $edit_stock = checkLabelByTask('edit_stock');
    $returnArr['status'] =100;

    $actionType = $this->input->post('actionType');
    $ship_details = getCustomSession('ship_details');
    
    $ship_id = $ship_details['ship_id'];
    $vars['ship_id'] = $ship_id;
    
    $delivery_note_id = trim($this->input->post('delivery_note_id'));
    
    $type = $this->input->post('id');
    $vars['opening_stock'] = $stock = $this->cm->getShipStockById($ship_id);
    $inventory_type = trim($this->input->post('inventory_type'));
    $shipData = $this->cm->getAllShipsById(' AND s.ship_id = '.$ship_id);   
     if($actionType=='save'){
       $entry_type = $this->input->post('entry_type');
       $requisition_type = $this->input->post('requisition_type');
       setCustomSession('requisition_type',$requisition_type);
         $this->form_validation->set_rules('entry_type','Data Entry Type','trim|required');
         if($type=='consumed_stock'){
             $this->form_validation->set_rules('month','Month','trim|required');
             $this->form_validation->set_rules('year','Year','trim|required');
          }
          elseif($type=='add_stock'){
            if($inventory_type=='opening_stock'){
                 $this->form_validation->set_rules('stock_date','Date','trim|required|callback_check_stock_date');
                 $this->form_validation->set_message('check_stock_date','Stock already exists for this month.');                
            }
            else{
               $this->form_validation->set_rules('delivery_note_id','Delivery Note No','trim|required|callback_month_stock_check');
                $this->form_validation->set_message('month_stock_check','Please add opening stock first for this month');  
            }
         }

         if($entry_type=='import'){
            $validation = false;
            $this->form_validation->set_rules('img','','callback_xlsx_file_check');  
          }  

         if($this->form_validation->run()){ 
           $validation = true;
           setCustomSession('post_data_'.$ship_id,$this->input->post()); 
           $file_name = $_FILES['img']['name'];
           $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
           setCustomSession('post_data_'.$ship_id,$this->input->post()); 

           if(!empty($file_name)){
            $upload_data = doc_upload($file_name, 'sheets');
            $this->load->library('Excelreader');
            $excel = new Excelreader();
            $fileName = FCPATH.'uploads/sheets/'.$upload_data['file_name'];
            $objWriter = $excel->readExcel($fileName,$ext);
                $products = $this->mp->getAllProduct(' AND p.status =1 ','R');
                $product_id_arr = array();
                $session_arr = array();
                if(!empty($products)){
                  foreach ($products as $key => $row) {
                        $product_id_arr[$row->item_no] = $row->product_id;
                    }
                }
            
            if($type=='consumed_stock'){
              for ($i=0; $i <count($objWriter) ; $i++){
                 if(!empty($objWriter[$i]['B'])){
                     $product_id = $product_id_arr[$objWriter[$i]['A']];
                     $session_arr['qty_product_'.$product_id] = $objWriter[$i]['D'];
                }
              }
             setImportSession('consumed_stock_data_'.$ship_id,$session_arr);        
           }
           elseif($type=='add_stock'){
               for ($j=0; $j < 10; $j++) {
                unset($objWriter[$j]);
               }
              $objWriter = array_values($objWriter);

              for ($i=0; $i <count($objWriter) ; $i++){
                 if(!empty($objWriter[$i]['B'])){
                     $product_id = $product_id_arr[$objWriter[$i]['A']];
                     $session_arr['qty_'.$product_id] = $objWriter[$i]['D'];
                     $session_arr['unit_price_'.$product_id] = $objWriter[$i]['E'];
                     $session_arr['line_total_'.$product_id] = $objWriter[$i]['F'];
                     $session_arr['remark_'.$product_id] = $objWriter[$i]['G'];
                }
              }
             setImportSession('opening_stock_data_'.$ship_id,$session_arr);
           }
           else
           {
             if($requisition_type=='provision'){
               for ($j=0; $j < 21; $j++) {
                unset($objWriter[$j]);
               }
              $objWriter = array_values($objWriter);
               
               for ($i=0; $i <count($objWriter) ; $i++){
                         if(!empty($objWriter[$i]['B'])){
                             $product_id = $product_id_arr[$objWriter[$i]['A']];
                             $session_arr['qty_product_'.$product_id] = $objWriter[$i]['E'];
                             $session_arr['remark_'.$product_id] = $objWriter[$i]['H'];
                        }
                      }
                }else{
                   unset($objWriter[1]);
                    $objWriter = array_values($objWriter);
                    $ttl_r = 0;
                    for ($i=0; $i <count($objWriter) ; $i++){
                         if(!empty($objWriter[$i]['B'])){
                             $session_arr['item_name'][$i] = trim(str_replace(array("'",'"'),array('',''),$objWriter[$i]['B']));
                             $session_arr['item_unit'][$i] = $objWriter[$i]['D'];
                             $session_arr['item_qty'][$i] = $objWriter[$i]['C'];
                             $session_arr['item_remark'][$i] = $objWriter[$i]['G'];
                             $ttl_r++;
                        }
                      }
                      $session_arr['ttl_prdct'] = $ttl_r;
                }
              setImportSession('rfq_data_'.$ship_id,$session_arr);
            }
           unlink($fileName);     
         }
           
         if($type=='add_stock'){
                if($inventory_type=='opening_stock'){
                    if($stock){
                        $returnArr['type'] = 'second_opening_stock';
                        $returnArr['status'] = 500;
                    }
                    else{
                        $returnArr['type'] = 'opening_stock';
                        $returnArr['status'] = 300;                    
                    }  
                }
                else{
                  if($edit_stock){  
                      $returnArr['delivery_note_id'] = $delivery_note_id;
                      $returnArr['type'] = 'add_inventory';
                      $returnArr['status'] = 200;
                  }
                  else{
                        $returnArr['type'] = 'add_inventory';
                        $returnArr['status'] = 204;
                  }
                } 
          }
          elseif($type=='consumed_stock'){
                if(empty($shipData->total_members) || empty($shipData->captain_user_id) || empty($shipData->cook_user_id) || empty($shipData->trading_area) || empty($shipData->victualling_rate) || empty($shipData->imo_no)){
                    $returnArr['type'] = 'consumed_stock';
                    $returnArr['status'] = 403;
               }else{
                    $returnArr['consumed_type'] = trim($this->input->post('consumed_type'));
                    $returnArr['type'] = 'consumed_stock';
                    $returnArr['status'] = 400;
               }
          }
          else{
               if(empty($shipData->total_members) || empty($shipData->captain_user_id) || empty($shipData->cook_user_id) || empty($shipData->trading_area) || empty($shipData->victualling_rate) || empty($shipData->imo_no)){
                  $returnArr['type'] = 'rfq';
                  $returnArr['status'] = 403;
                }
                else{
                 $returnArr['type'] = 'rfq';
                 $returnArr['status'] = 400;
                }
          }
       }
    }
    $vars['delivery_notes'] = $this->cm->getDeliveryNoteNo($ship_id);
    $vars['years'] = $this->cm->stock_years($ship_id);
    $vars['dataArr'] = $this->input->post();
    $data = $this->load->view('stock_config',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
   }

  function check_stock_date(){
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $date = trim($this->input->post('stock_date'));
    $monthYear = convertDate($date,'','m Y');
    $stock = $this->cm->getShipStockById($ship_id);
     $count = 0; 
     if(!empty($stock)){
        foreach ($stock as $row) {
          $dbmonthYear = ($row->month>9) ? '' : '0'.$row->month.' '.$row->year;
           if($dbmonthYear==$monthYear){
             $count++; 
           } 
        }
     }

     if($count){
       return false; 
     }
     else{
       return true; 
     }   
  } 


  function month_stock_check(){
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $delivery_note_id = trim($this->input->post('delivery_note_id'));
    if(!empty($delivery_note_id)){
     $data = (array) $this->mp->getCompanyInvoice(' AND ci.delivery_note_id ='.$delivery_note_id);
     $current_stock = $this->cm->monthly_stock_details(' AND ms.ship_id='.$ship_id.' AND ms.month = '.$data['month'].' AND ms.year = '.$data['year']);
        if(empty($current_stock)){
          return false;
        }
        else{
          return true;
        }
    }

  } 

  function xlsx_file_check(){
       $allowed_mime_type_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel');
        $mime = get_mime_by_extension($_FILES['img']['name']);
        if(isset($_FILES['img']['name']) && $_FILES['img']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('xlsx_file_check', 'Please choose only .xlsx file.');              
                return false;
            }
        }else{
            $this->form_validation->set_message('xlsx_file_check', 'Please choose a file to upload.');
            return false;
        }
  } 


  function map_columns($type = ''){
     checkUserSession();
      
      if(!empty($_FILES['img']['name'])) {
          $file = $_FILES['img']['name'];
          $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
         $upload_data = doc_upload($file, 'sheets');
         $column_row = $this->input->post('column_row');

        $this->load->library('Excelreader');
        $excel  = new Excelreader();
        $fileName = FCPATH.'uploads/sheets/'.$file;
        $objWriter = $excel->readExcel($fileName,$ext);
        $columns = $objWriter[$column_row];
      }

     $html = '';
     $options = '';
     $form_type = $this->input->post('form_type');
     
     if($form_type=='add_stock'){
       $label = array('product','quantity','value');
     }
     else{
      $label = array('product','quantity');  
     }
     
     $session_column = array();
     if(!empty($columns)){
      foreach ($columns as $key => $row) {
         if(!empty($row)){
             $session_column[$key] = $row;
             $options .= '<option value="'.$key.'">'.$row.'</option>';             
         }
       }
     }
     
     setCustomSession('sheets_column',$session_column);

     for ($i=0; $i <count($label) ; $i++) { 
      $html .= '<tr>';
      $html .= '<td><label>'.ucfirst($label[$i]).'</label></td>';
      $html .= '<td><select class="form-control" name="'.$label[$i].'_map" id="'.$label[$i].'"><option value="">Select Column</option>';
      $html .= $options;
      $html .='</select></td>';
      $html .= '</tr>';       
     }
     $returnArr['file_name'] = $upload_data['file_name'];
     $returnArr['returnMsg'] = 'File upload successfully';
     $returnArr['html'] = $html;
     echo json_encode($returnArr);

   }
   
  function testExcel(){
    $this->load->library('Excelreader');
    $excel  = new Excelreader();
    
    $fileName = FCPATH.'uploads/sheets/newdata.xlsx';
     $objWriter = $excel->readExcel($fileName,'xlsx');
    
     $products = $this->mp->getAllProduct(' AND p.status =1 ','R');
           
       $product_id_arr = array();
       if(!empty($products)){
        foreach ($products as $key => $row) {
          $product_name = str_replace(array(' ',',',),array('_',''),$row->product_name);
          $product_name = strtolower($product_name);
          $product_id_arr[$product_name] = $row->product_id; 
        }
       }

     $session_arr = array();
     unset($objWriter[1]);
     for ($i=0; $i <count($objWriter) ; $i++) { 
      if(!empty($objWriter[$i]['A'])){
          $name = strtolower(str_replace(array(' ',','),array('_',''),trim($objWriter[$i]['A'])));
          $procut_id =  $product_id_arr[$name];
         $session_arr['qty_product_'.$procut_id] = $objWriter[$i]['C'];
         $session_arr['val_product_'.$procut_id] = $objWriter[$i]['D']; 
       }
     }
    print_r($session_arr);die; 
  }

 function create_invoice(){
   checkUserSession();
   $user_session_data = getSessionData();
   $this->load->model('email_manager');
   $this->em = $this->email_manager;
   $invoice_discount_label = checkLabelByTask('invoice_discount');
   $actionType = $this->input->post('actionType');
   $returnArr['status'] = 100;    
   $delivery_note_id = $this->input->post('id');
    $where = ' AND dn.delivery_note_id='.$delivery_note_id;
    $data = (array) $this->mp->getDeliveryNoteData($where); 
    $json_data = unserialize($data['json_data']);
     $arrData = array();
     $a = []; 
     if(!empty($json_data)){
       for ($i=0; $i <count($json_data) ; $i++) { 
          $a[$json_data[$i]['product_id']] = $json_data[$i];
        } 
     }

     if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
               $arrData[$row->sequence][$row->category_name][] = array(
             'category_name'=>$row->category_name,
             'product_category_id'=>$row->product_category_id,
             'product_name'=>$row->product_name,
             'product_id'=>$row->product_id,
             'unit'=>$row->unit,
             'item_no'=>$row->item_no,
             'sequence'=>$row->sequence,
             'quantity'=>$a[$row->product_id]['quantity'],
             'unit_price'=> $a[$row->product_id]['unit_price'],
             'total_price'=>$a[$row->product_id]['price']
          );  
        } 
     }
  }   

   $actionType = $this->input->post('actionType');

   if($actionType=='save'){
    if($invoice_discount_label){
      $this->form_validation->set_rules('invoice_discount','Discount','trim|required|numeric');
     }

    $this->form_validation->set_rules('invoice_date','Date','trim|required');

    if($this->form_validation->run()){
       $invoice_discount = $this->input->post('invoice_discount');
       $invArr['invoice_no'] = $data['invoice_no'];
       $invArr['delivery_note_id'] = $data['delivery_note_id'];
       $invArr['created_at'] = date('Y-m-d H:i:s');
       $invArr['created_by'] = $user_session_data->user_id;
       $invArr['invoice_discount'] =  $invoice_discount;
       $invArr['invoice_date'] = $invoice_date = convertDate(trim($this->input->post('invoice_date')),'','Y-m-d');
       $invArr['due_date'] = date('Y-m-d', strtotime($invoice_date . ' + 30 days'));
       $invArr['ship_id'] = $data['ship_id'];
       if($data['is_cn_required']==1){
         $invArr['status'] = 'CN pending';   
       }
       
       // for ($p=0; $p < 500; $p++) { 
         $this->db->insert('company_invoice',$invArr);   
        $company_invoice_id = $this->db->insert_id();
         $batch = array();
            if(!empty($json_data)){
              $price = 0;  
              for ($i=0; $i <count($json_data) ; $i++) { 
                 $line_price = ($json_data[$i]['quantity'] * $json_data[$i]['unit_price']);
                 $price += $line_price;
                 $batch[] = array('company_invoice_id'=>$company_invoice_id,'product_id'=>$json_data[$i]['product_id'],'qty'=>$json_data[$i]['quantity'],'unit_price'=>$json_data[$i]['unit_price'],'price'=>$line_price);  
                }  
            }
      $this->db->insert_batch('company_invoice_details',$batch);
      $this->db->update('delivery_note',array('is_invoice_created'=>1),array('delivery_note_id'=>$data['delivery_note_id']));  
      // $this->db->update('work_order',array('status'=>4),array('work_order_id'=>$data['work_order_id']));  
      
      $discount_amount = ($price*$invoice_discount) / 100;
      $total_price =  $price - $discount_amount;
      $this->db->update('company_invoice',array('json_data'=>serialize($batch),'price'=>$price,'total_price'=>$total_price),array('company_invoice_id'=>$company_invoice_id));
       // }

      if(!empty($company_invoice_id)){
        $invoice_data = (array) $this->mp->getCompanyInvoice(' AND ci.company_invoice_id='.$company_invoice_id);
        $template_data = $this->um->getEmailTemplateByCode(' and em.template_code = "invoice_created"');
        if(!empty($template_data)){
         require_once(APPPATH.'libraries/tcpdf/Tcpdf.php'); 
          $subject = str_replace(array('##ship_name##','##invoice_no##','##imo_no##','##port_name##','##delivery_date##','##req_type##'),array(ucwords($invoice_data['ship_name']),$invoice_data['invoice_no'],$invoice_data['imo_no'],$invoice_data['delivery_port'],convertDate($invoice_data['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$invoice_data['requisition_type']))),$template_data->email_subject);
          $ship_type = ($invoice_data['ship_type']==1) ? '' : '//Non Contracted';          
          $body = str_replace(array('##invoice_no##','##delivery_date##','##ship_name##','##name##','##desg##','##email##'),array($invoice_data['invoice_no'],convertDate($invoice_data['delivery_date'],'','d-m-Y'),ucwords($invoice_data['ship_name']),ucwords($user_session_data->first_name.' '.$user_session_data->last_name),$user_session_data->role_name,$user_session_data->email),$template_data->email_body); 

          $json_data = unserialize($invoice_data['json_data']);
          $arrIData = array(); 
          if(!empty($json_data)){
               for ($i=0; $i <count($json_data) ; $i++) { 
               $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
                $arrIData[$product['sequence']][$product['category_name']][] = array(
                 'category_name'=>$product['category_name'],
                 'product_category_id'=>$product['product_category_id'],
                 'product_name'=>$product['product_name'],
                 'product_id'=>$product['product_id'],
                 'unit'=>$product['unit'],
                 'item_no'=>$product['item_no'],
                 'sequence'=>$product['sequence'],
                 'qty'=>$json_data[$i]['qty'],
                 'unit_price'=> $json_data[$i]['unit_price'],
                 'total_price'=>$json_data[$i]['price']
              );
            } 
          }

          if($data['currency'] == 1){
            $curr = 'EURO';
            $currSymbol = '€';
          }else if($data['currency'] == 2){
            $curr = 'USD';
            $currSymbol = '$';
          }else{
            $curr = 'SGD';
            $currSymbol = 'S$';
          }

        $pdf_vars['dataArr'] = $invoice_data;
        $pdf_vars['dataArr']['currency'] = $curr; 
        $pdf_vars['arrData'] = $arrIData; 
        $pdf_vars['data'] = $data;
        $pdf_vars['title'] = 'Invoice';
        $html = $this->load->view('print_invoice',$pdf_vars,TRUE);
        $file = str_replace('/','--','Invoice-'.$invoice_data['invoice_no']);
        $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage('L');
        $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        ob_clean();ob_end_clean();ob_flush();
        $pdfData = $pdf->Output('', 'S');
        $filePath = $pdfFilePath;
        write_file($filePath, $pdfData);
       
       if(!empty($invoice_data['captain_user_id'])){
            $emArr['user_id'] = $invoice_data['captain_user_id'];
            $emArr['subject'] = $subject;
            $emArr['body'] = $body;
            $emArr['attechment'] = $file;
            $this->em->add_email_log($emArr);
       }

        $email_roles = $this->em->getEmailRoles($template_data->email_template_id);
             if(!empty($email_roles)){
               foreach ($email_roles as $row) {
              $user_list = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_list)){
                    foreach ($user_list as $val) {
                        $emArr['user_id'] = $val->user_id;
                        $emArr['subject'] = $subject;
                        $emArr['body'] = $body;
                        $emArr['attechment'] = $file;
                        $this->em->add_email_log($emArr);
               }
             }
          }
       }
   }
 }

      

      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Invoice Created successfully';
   }  
}


   $dataArr = $data;
   $vars['dataArr'] = $dataArr; 
   $vars['arrData'] = $arrData;
   $data = $this->load->view('company_invoice',$vars,true);
   $returnArr['data'] = $data;
   echo json_encode($returnArr);
  }  
  
 function edit_invoice(){
   checkUserSession();
   $user_session_data = getSessionData();
   $this->load->model('email_manager');
   $this->em = $this->email_manager; 
   $invoice_discount_label = checkLabelByTask('invoice_discount');
   $actionType = $this->input->post('actionType');
   $returnArr['status'] = 100;    
   $invoice_id = $this->input->post('id');
   $where = ' AND ci.company_invoice_id='.$invoice_id;
   $data = (array) $this->mp->getCompanyInvoice($where); 
   $json_data = unserialize($data['json_data']);
     $arrData = array(); 
     $postArr = array(); 
     $a = [];
     if(!empty($json_data)){
       for ($i=0; $i <count($json_data) ; $i++) { 
          $a[$json_data[$i]['product_id']] = $json_data[$i];
        } 
     }

    if(!empty($a)){
     $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
            $postArr['qty_'.$row->product_id] = $a[$row->product_id]['qty'];
            $postArr['price_'.$row->product_id] = $a[$row->product_id]['unit_price'];
            $arrData[$row->sequence][$row->category_name][] = array(
             'category_name'=>$row->category_name,
             'product_category_id'=>$row->product_category_id,
             'product_name'=>$row->product_name,
             'product_id'=>$row->product_id,
             'unit'=>$row->unit,
             'item_no'=>$row->item_no,
             'sequence'=>$row->sequence,
             'quantity'=>$a[$row->product_id]['qty'],
             'unit_price'=> $a[$row->product_id]['unit_price'],
             'total_price'=>$a[$row->product_id]['price']
          );
           } 
        }   
    } 

    ksort($arrData);
   
   $actionType = $this->input->post('actionType');
   if($actionType=='save'){
    $this->form_validation->set_rules('reason','Reason','trim|required');
    if($invoice_discount_label){
      $this->form_validation->set_rules('invoice_discount','Discount','trim|required|numeric');
    }
    for ($i=0; $i <count($json_data) ; $i++) {
      $this->form_validation->set_rules('qty_'.$json_data[$i]['product_id'],'Qty','trim|required');  
    }
    if($this->form_validation->run()){
      $dataArr['updated_on'] = date('Y-m-d H:i:s');
      $dataArr['updated_by'] = $user_session_data->user_id;
      $dataArr['invoice_discount'] = $this->input->post('invoice_discount');
      $dataArr['reason'] = trim($this->input->post('reason'));
      $total_amount = 0;
      $batch = array();
       for($i=0; $i <count($json_data) ; $i++) {
        $qty = $this->input->post('qty_'.$json_data[$i]['product_id']);
        $price = $this->input->post('price_'.$json_data[$i]['product_id']);
        $batch[] = array('company_invoice_id'=>$json_data[$i]['company_invoice_id'],'product_id'=>$json_data[$i]['product_id'],'qty'=>$qty,'unit_price'=>$price,'price'=>($qty * $price));
        $total_amount += ($qty * $price);
       }
      $this->db->delete('company_invoice_details',array('company_invoice_id'=>$invoice_id));
      $this->db->insert_batch('company_invoice_details',$batch);
      $dataArr['json_data'] = serialize($batch);
      $discount_amount = ($total_amount*$dataArr['invoice_discount']) / 100;
      $net_amount = $total_amount - $discount_amount;
      $dataArr['total_price'] = $net_amount; 
      $this->cm->edit_invoice($dataArr,array('company_invoice_id'=>$invoice_id)); 
      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Invoice Updated successfully';

        $whereEm = ' AND nt.code = "edit_invoice"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##invoice_no##','##user_name##'),array($data['invoice_no'],ucwords($user_session_data->first_name.' '.$user_session_data->last_name)),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }
    }
   }
   // $arrData = ksort($arrData);
   $postArr['reason'] = $data['reason'];
   $postArr['invoice_discount'] = $data['invoice_discount'];
   $vars['postArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() :  $postArr;  
   $vars['postArr']['company_invoice_id'] = $data['company_invoice_id'];  
   $vars['dataArr'] = $data;  
   $vars['arrData'] = $arrData; 
   $data = $this->load->view('edit_invoice',$vars,true);
   $returnArr['data'] = $data;
   echo json_encode($returnArr);
  }


  function send_order_request(){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('user_manager');
    $this->load->model('email_manager');

    $ship_order_id = $this->input->post('id');
    $rfq_no = $this->input->post('second_id');
    $vendors =  $this->cm->getQuotedVendor(' AND vo.ship_order_id = '.$ship_order_id);
    $vendor_ids = array();
    if(!empty($vendors)){
        foreach ($vendors as $row) {
          $vendor_ids[] = $row->vendor_id;  
        }
    }

    if(!empty($ship_order_id)){
      $rData = (array) $this->cm->getRrqItemsByID(' And so.ship_order_id ='.$ship_order_id);
    }

    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    
    if($actionType=='save'){
     $this->form_validation->set_rules('vendor_id[]','Vendor','trim|required'); 
     $this->form_validation->set_rules('expire_date','Expire Date','trim|required');    
      if($this->form_validation->run()){
        $vendors = $this->input->post('vendor_id');
         $whereEm = ' AND em.template_code = "send_quote"';
         $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
         $dataArr = array();
         for ($i=0; $i <count($vendors) ; $i++) { 
            $dataArr[] = array('ship_order_id'=>$ship_order_id,'added_by'=>$user_session_data->user_id,'added_on'=>date('Y-m-d H:i:s'),'expire_date'=>convertDate($this->input->post('expire_date'),'','Y-m-d'),'vendor_id'=>$vendors[$i]);
              if(!empty($emailTemplateData)){
                $subject = str_replace(array('##ship_name##','##rfq_no##'),array(ucwords($rData['ship_name']),$rfq_no),$emailTemplateData->email_subject);     
                $vendor_data = $this->user_manager->getallVendor(' AND v.vendor_id = '.$vendors[$i].' AND u.status= 1','R');
                $vendor_data = (array) $vendor_data[0]; 
                 // if($vendor_data['email']){
                   $body = str_replace(array('##name##','##rfq_no##','##ship_name##','##port_name##','##county##','##url##','##user_name##','##phone##'),array(ucwords($vendor_data['vendor_name']),$rfq_no,ucwords($rData['ship_name']),ucwords($rData['porrt_name']),ucwords($rData['country']),base_url(),ucfirst($user_session_data->first_name).' '.ucfirst($user_session_data->last_name),$user_session_data->phone),$emailTemplateData->email_body);
                   $emailArr['user_id'] = $vendor_data['user_id'];
                   $emailArr['subject'] = $subject;
                   $emailArr['body'] = $body;

                   $this->email_manager->add_email_log($emailArr);   
                   // $this->user_manager->sendMail($vendor_data['email'],$subject,$body);
                 // }     
             }
          }
        // $this->db->update('ship_order',array('status'=>4),array('ship_order_id'=>$ship_order_id));
        $this->db->insert_batch('vendor_quotation',$dataArr);
        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'Quotation Request Send successfully';
      } 

    }

    if(!empty($vendor_ids)){
     $where = ' AND u.status= 1 And v.vendor_id not in('.implode(',',$vendor_ids).')';
    }
    else{
     $where = ' AND u.status= 1';   
    }


    $vars['dataArr'] = $this->input->post();     
    $vars['vendors'] = $this->um->getallVendor($where,'R');
    $vars['ship_order_id'] = $ship_order_id;
    $vars['rfq_no'] = $rfq_no;
    $data = $this->load->view('send_order_request',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }
  
  function add_consumed_stock(){
        checkUserSession();
        $user_session_data = getSessionData();
        $returnArr['status'] = 100;
        $ship_details = getCustomSession('ship_details');
        $ship_id = $ship_details['ship_id'];
        $actionType = $this->input->post('actionType');
        $post_data = getCustomSession('post_data_'.$ship_id);
        // print_r($post_data);die;
        $consumed_stock_type = trim($this->input->post('id'));
        
        // $products = $this->cm->current_stock_details(' AND c.ship_id = '.$ship_id);

        $products = $this->cm->monthly_stock_details(' AND ms.ship_id ='.$ship_id.' AND ms.month = '.$post_data['month'].' AND ms.year='.$post_data['year']);

        $vars['extra_meals'] = $extra_meals = $this->cm->getExtraMealDetails(' AND em.status = 1 AND em.ship_id = '.$ship_id.' AND em.month = '.$post_data['month'].' AND em.year = '.$post_data['year']); 

        $vars['condemned_stock'] = $condemned_stock = $this->cm->getAllCondemnedStockReportData(' AND cr.ship_id = '.$ship_id.' AND cr.month ='.$post_data['month'].' AND cr.year ='.$post_data['year'],'R'); 

        // echo $this->db->last_query();die;

        $month_stock_id = $products[0]->month_stock_id;

        if($actionType=='save'){
           if($this->validate_consumed_stock()){            
            // $this->update_month_stock($ship_id);
                $dataArr['ship_id'] = $ship_id;
                $dataArr['added_on'] = date('Y-m-d H:i:s');
                $dataArr['added_by'] = $user_session_data->user_id;
                $dataArr['month'] = $post_data['month'];
                $dataArr['year'] = $post_data['year'];
                $dataArr['type'] = $consumed_stock_type;
                $consumed_stock_id = $this->cm->add_consumed_stock($dataArr);
                 $batch = [];
                 $current_stock_details = [];
                 $total_price = 0;
                 $meat = 0;
                 
                 if(!empty($products)){
                    foreach ($products as $row) {
                      $stock_qty = ($consumed_stock_type=='stock_used') ? $this->input->post('used_qty_'.$row->product_id) : $this->input->post('closing_qty_'.$row->product_id);  
                    if(is_numeric($stock_qty)) {                    
                      $batch[] = array('consumed_stock_id'=>$consumed_stock_id,'product_id'=>$row->product_id,'quantity'=>$stock_qty,'value'=>($stock_qty*$row->unit_price));

                      if($consumed_stock_type=='stock_used'){
                         
                         $current_stock_details['last_used_stock'] = $row->used_stock;
                         $current_stock_details['consumed'] = $stock_qty;
                         $current_stock_details['last_consumed'] = $row->consumed;
                         $current_stock_details['used_stock'] = ($row->used_stock+$stock_qty);
                         $current_stock_details['last_available_stock'] = $row->available_stock;
                         $available_stock = ($row->available_stock - $stock_qty);
                         $current_stock_details['available_stock'] = $available_stock; 
                         $current_stock_details['updated_on'] = date('Y-m-d H:i:s'); 
                         $current_stock_details['updated_by'] = $user_session_data->user_id;

                          $total_price += $available_stock*$row->unit_price;

                          if($row->group_name=='Meat'){
                            $meat += $available_stock;
                          }

                         $this->cm->edit_month_stock_details($current_stock_details,array('monthly_stock_detail_id'=>$row->monthly_stock_detail_id));

                        // echo $this->db->last_query();die;


                         // $this->cm->edit_ship_current_stock($current_stock_details,array('stock_detail_id'=>$row->stock_detail_id));
                      }
                      else{
                          $current_stock_details['available_stock'] = $stock_qty;
                          $current_stock_details['last_available_stock'] = $row->available_stock;
                          $current_stock_details['used_stock'] = ($row->total_stock - $stock_qty);
                           $current_stock_details['updated_on'] = date('Y-m-d H:i:s'); 
                         $current_stock_details['updated_by'] = $user_session_data->user_id;

                         $total_price += $stock_qty * $row->unit_price;
                         if($row->group_name=='Meat'){
                              $meat += $stock_qty;
                         }

                         // $this->cm->edit_ship_current_stock($current_stock_details,array('stock_detail_id'=>$row->stock_detail_id));
                         $this->cm->edit_month_stock_details($current_stock_details,array('monthly_stock_detail_id'=>$row->monthly_stock_detail_id));


                       }
                     }
                   }
                 }
                
                 $this->db->insert_batch('consumed_stock_details',$batch);
                 $json_data = serialize($batch);
                 $this->cm->edit_consumed_stock(array('json_data'=>$json_data),' consumed_stock_id ='.$consumed_stock_id);

                 $this->db->update('month_stock',array('closing_price'=>$total_price,'closing_meat_qty'=>$meat),array('month_stock_id'=>$month_stock_id));
                  
                  // $this->update_month_stock($ship_id);
                 $this->session->unset_userdata('consumed_stock_data_'.$ship_id);
                 $returnArr['status'] = 200;
                 $returnArr['returnMsg'] = 'Consumed Stock Added successfully.';
            }
          }
 
       $consumed_stock_data = getImportData('consumed_stock_data_'.$ship_id); 
       $prdct_qnty = array();
       if(!empty($products)){
        foreach($products as $p){
            if(is_numeric($consumed_stock_data['qty_product_'.$p->product_id])){
                if($consumed_stock_type=='stock_used'){
                    $prdct_qnty['used_qty_'.$p->product_id] = $consumed_stock_data['qty_product_'.$p->product_id];
                }else if($consumed_stock_type=='closing_stock'){
                    $prdct_qnty['closing_qty_'.$p->product_id] = $consumed_stock_data['qty_product_'.$p->product_id];
                }
            }
        }
       }
       
       $vars['group_products'] = $this->cm->avalibleStockByGroup(' AND ms.ship_id ='.$ship_id.' AND ms.month = '.$post_data['month'].' AND ms.year = '.$post_data['year'],'R'); 


       $vars['data'] = $products;     
       $vars['dataArr'] = ($actionType=='save')?$this->input->post():$prdct_qnty;
       $vars['dataArr']['id'] =  trim($this->input->post('id')); 
       if(!empty($consumed_stock_data) && $actionType!='save'){
            $vars['is_import_data'] = 1;
       } 

       if(empty($extra_meals)){
            $data = '<div style="text-align:center; font-weight:bold; font-size:20px;">Please generate the extra meal report first.</div>';
       }
       elseif(empty($condemned_stock)){
            $data = '<div style="text-align:center; font-weight:bold; font-size:20px;">Please generate the condemned stock report first.</div>';

       }
       else{
         $data = $this->load->view('add_consumed_stock',$vars,true);
       }
       
       $returnArr['data'] = $data;  
       echo json_encode($returnArr);   
    }


  function validate_consumed_stock(){
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $post_data = getCustomSession('post_data_'.$ship_id);
    // $data = $this->cm->current_stock_details(' AND c.ship_id = '.$ship_id);
    $data = $this->cm->monthly_stock_details(' AND ms.ship_id ='.$ship_id.' AND ms.month = '.$post_data['month'].' AND ms.year='.$post_data['year']); 

    $consumed_stock_type = trim($this->input->post('id'));
    $count = 0;
    $validation = 0;
    if(!empty($data)){
     foreach ($data as $row) {
        $stock_qty = ($consumed_stock_type=='stock_used') ? $this->input->post('used_qty_'.$row->product_id) : $this->input->post('closing_qty_'.$row->product_id);
        if(is_numeric($stock_qty))
        {
         $count++;
          if($consumed_stock_type=='stock_used'){
               $this->form_validation->set_rules('used_qty_'.$row->product_id,'','trim|numeric|less_than_equal_to['.$row->available_stock.']');
              $validation++;  
          }
          else{
              $avalible_stock = ($row->total_stock - $row->used_stock);
               $this->form_validation->set_rules('closing_qty_'.$row->product_id,'','trim|numeric|less_than_equal_to['.$avalible_stock.']');
                $validation++;      
          }
        }   
      }
    }

    if(empty($count)){
       $this->form_validation->set_rules('product_id','Product','trim|required',array('required'=>'Please enter atleast one product quantity.'));
      $validation++;        
    }

    if($validation){
        return $this->form_validation->run(); 
    }
    else{
        return true;
    }
    
  } 

 function invoice_list($ship_id=''){
        checkUserSession();
        $ship_id = base64_decode($ship_id);
        $user_session_data = getSessionData();
        $vars['ship_id'] = $ship_id;
        $vars['vendors'] = $this->um->getallVendor(' AND u.status= 1','R','','',' ORDER By u.first_name ASC');
        $data = $this->load->view('invoice_list',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }


  function getAllInvoiceList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $order_by = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;

      if(!empty($ship_id)){
       $where .= 'AND dn.ship_id = '.$ship_id;
      }

      if($status){
        $where .= ' AND inv.status = "'.$status.'"';
      }     

      if($vendor_id){
        $where .= ' AND vq.vendor_id in('.implode(',',$vendor_id).')';
      }

      if($created_on){
       $where .= ' AND date(inv.created_at) = "'.convertDate($created_on,'','Y-m-d').'"';   
       }  

      if($keyword){
        $where .= ' AND ( inv.invoice_no like "%'.trim($keyword).'%" or wo.po_no like "%'.trim($keyword).'%" or dn.note_no like "%'.trim($keyword).'%"  or concat(u.first_name," ",u.last_name) like "%'.trim($keyword).'%" )';  
      } 

      if((!empty($sort_columnn)) && (!empty($sort_typee))){
            if($sort_columnn == 'Invoice No'){
             $order_by = 'ORDER BY inv.invoice_no '.$sort_typee;
            }
            elseif($sort_columnn == 'Po No'){
             $order_by = 'ORDER BY wo.po_no '.$sort_typee;
            }
            elseif($sort_columnn == 'Note No'){
             $order_by = 'ORDER BY dn.note_no '.$sort_typee;
            }
            elseif($sort_columnn == 'Vendor Name'){
             $order_by = 'ORDER BY u1.first_name '.$sort_typee;
            }
            elseif($sort_columnn == 'Invoice Amount'){
             $order_by = 'ORDER BY inv.total_price '.$sort_typee;
            }
            elseif($sort_columnn == 'Invoice Date'){
             $order_by = 'ORDER BY inv.invoice_date '.$sort_typee;
            }
            elseif($sort_columnn == 'Due Date'){
             $order_by = 'ORDER BY inv.due_date '.$sort_typee;
            }
            elseif($sort_columnn == 'Added On'){
             $order_by = 'ORDER BY inv.created_at '.$sort_typee;
            }
            elseif($sort_columnn == 'Added By'){
             $order_by = 'ORDER BY u.first_name '.$sort_typee;
            }
            elseif($sort_columnn == 'Status'){
             $order_by = 'ORDER BY inv.status '.$sort_typee;
            }
        }
        else{
         $order_by = 'ORDER BY inv.created_at DESC';
        }

    if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllInvoiceList($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
 
    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }    

   if($download==1){
      $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'Invoice';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Invoice No','PO No','Note No','Vendor','Amount($)','Created On','Created By','Status');
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
                      ) 
                    ),'cellArray'=>array('A7:H7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
           $k = 7;
           $invoice_list = $this->cm->getAllInvoiceList($where,'R',$perPage,$offset,$order_by);
           if($invoice_list){
            foreach ($invoice_list as $row) {
               $k++;
               $arrayData[] = array($row->invoice_no,$row->po_no,$row->note_no,ucfirst($row->vendor_name),$row->total_price,ConvertDate($row->created_at,'','d-m-Y | h:i A'),ucfirst($row->user_name),$row->status);
            }
           }

        $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
   }

    $countdata = $this->cm->getAllInvoiceList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
    $invoice_list = $this->cm->getAllInvoiceList($where,'R',$perPage,$offset,$order_by);
    $edit_invoice = checkLabelByTask('edit_invoice');
    $view_invoice = checkLabelByTask('view_invoice');
    $update_invoice_status = checkLabelByTask('update_invoice_status');
    $discount_label = checkLabelByTask('show_invoice_discount');
        if($invoice_list){
            $total_entries = 'Showing '.($offset+1).' to '.($offset+count($invoice_list)).' of '.$countdata.' entries';
            foreach ($invoice_list as $row){
             $dn = $us = $edit = $vir = '';
             if($view_invoice){
               $dn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Invoice\',\'shipping/viewInvoice\',\''.$row->company_invoice_id.'\',\'\',\'98%\',\'full-width-model\')">View Invoice</a>';
               $opi = '<a target="_blank" href="'.base_url().'shipping/download_OPI/'.base64_encode($row->company_invoice_id).'" >One Pager Invoice</a>';
             }
              $cstatus =  strtolower(str_replace(' ','_',$row->status));

                if($update_invoice_status){ 
                  if($cstatus=='cn_pending' || $cstatus == 'incorrect_invoice'){
                   $us = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Update Invoice Status\',\'shipping/changeInvoiceStatusCustom\',\''.$row->company_invoice_id.'\',\'\',\'\',\'\')">Update Invoice Status</a>';
                  }
               }

                 if(!empty($row->invoice_remark) || !empty($row->document_url)){
                    if($view_support_doc){
                    $vir = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Related Documents\',\'shipping/viewInvoiceRemark\',\''.$row->company_invoice_id.'\',\'\',\'40%\',\'\')">Related Document</a>';
                    }                
                   }

            // if($cstatus=='resolved'){
                if($edit_invoice){
                  $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Edit Invoice\',\'shipping/edit_invoice\',\''.$row->company_invoice_id.'\',\'\',\'98%\',\'full-width-model\')">Edit Invoice</a>'; 
                }
           // }

           if($row->status=='Created'){
            $status = '<span class="badge badge-primary">'.$row->status.'</span>';
           }
           elseif($row->status=='CN Pending'){
            $status = '<span class="badge badge-warning">'.$row->status.'</span>';
            
           }
           elseif($row->status=='Incorrect Invoice'){
            $status = '<span class="badge badge-danger">'.$row->status.'</span>';
            
           }
           elseif($row->status=='Resolved'){
            $status = '<span class="badge badge-success">'.$row->status.'</span>';
            
           }
           elseif($row->status=='Partially Paid'){
            $status = '<span class="badge badge-info">'.$row->status.'</span>';
            
           }
           elseif($row->status=='Paid'){
            $status = '<span class="badge badge-info">'.$row->status.'</span>';
            
           }

            $returnArr .= "<tr id='row-".$row->company_invoice_id."'>
            <td width='10%' data-toggle='tooltip' data-placement='top' title='$row->invoice_no'>".$row->invoice_no."</td>
            <td width='10%'  data-toggle='tooltip' data-placement='top' title='$row->po_no'>".$row->po_no."</td>
            <td width='10%' data-toggle='tooltip' data-placement='top' title='$row->note_no'>".$row->note_no."</td>
            <td width='10%'>".ucfirst($row->vendor_name)."</td>
            <td width='10%'>".$currency.' '.(($discount_label) ? number_format($row->total_price,2) : number_format($row->price,2))."</td>
            <td width='10%'>".ConvertDate($row->invoice_date,'','d-m-Y')."</td>
            <td width='10%'>".ConvertDate($row->due_date,'','d-m-Y')."</td>
            <td width='10%'>".ConvertDate($row->created_at,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            <td width='10%'>".$status."</td>
            ";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
            <li>'.$dn.'</li>
            <li>'.$opi.'</li>
            <li>'.$edit.'</li>
            <li>'.$us.'</li>
            <li>'.$vir.'</li>
            </ul>
            </div></td> </tr>';
            }

          if($countdata <= 5){
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";

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

  function changeInvoiceStatus(){
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $status = ($status == 2) ? 'Paid' : 'Created';
    $where = 'company_invoice_id ='.$id;
    $this->cm->changestatus('company_invoice',$status,$where);
    $this->session->set_flashdata('succMsg','Invoice status changed successfully.');  
}

 function viewInvoice(){
   $user_session_data = getSessionData();
   $actionType = $this->input->post('actionType');
   $company_invoice_id = $this->input->post('id');
    $returnArr['status'] = 100;    
    $where = ' AND ci.company_invoice_id ='.$company_invoice_id;
    $data = (array) $this->mp->getCompanyInvoice($where);
    // print_r($data);die;
     $json_data = unserialize($data['json_data']);
     $arrData = array(); 
     $a = [];
     if(!empty($json_data)){
       for ($i=0; $i <count($json_data) ; $i++) { 
          $a[$json_data[$i]['product_id']] = $json_data[$i];
        } 
     }

     if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
           $arrData[$row->sequence][$row->category_name][] = array(
             'category_name'=>$row->category_name,
             'product_category_id'=>$row->product_category_id,
             'product_name'=>$row->product_name,
             'product_id'=>$row->product_id,
             'unit'=>$row->unit,
             'item_no'=>$row->item_no,
             'sequence'=>$row->sequence,
             'qty'=>$a[$row->product_id]['qty'],
             'unit_price'=>$a[$row->product_id]['unit_price'],
             'total_price'=>$a[$row->product_id]['price']
          ); 
        } 
    }
 } 
   if($data['currency'] == 1){
        $curr = 'EURO';
        $currSymbol = '€';
     }else if($data['currency'] == 2){
        $curr = 'USD';
        $currSymbol = '$';
     }else{
        $curr = 'SGD';
        $currSymbol = 'S$';
     }
   $dataArr['payment_term'] = $data['payment_term'];     
   $dataArr['customer_id'] = $data['customer_id'];
   $dataArr['ship_name'] = $data['ship_name'];
   $dataArr['imo_no'] = $data['imo_no'];
   $dataArr['company_name'] = $data['company_name'];     
   $dataArr['company_address'] = $data['company_address'];
   $dataArr['invoice_no'] = $data['invoice_no'];
   $dataArr['reqsn_date'] = $data['reqsn_date'];     
   $dataArr['delivery_port'] = $data['delivery_port'];
   $dataArr['po_no'] = $data['po_no'];
   $dataArr['currency'] = $curr;
   $dataArr['company_invoice_id'] = $company_invoice_id;
   $dataArr['invoice_date'] = $data['invoice_date'];
   $dataArr['invoice_discount'] = $data['invoice_discount'];
   $dataArr['requisition_type'] = $data['requisition_type'];   
   $dataArr['reason'] = $data['reason'];   

   $vars['dataArr'] = $dataArr; 
   $vars['arrData'] = $arrData;
   $data = $this->load->view('view_invoice',$vars,true); 
   $returnArr['data'] = $data;
   echo json_encode($returnArr);
 }
  
  // function delivery_receipt(){
  //     checkUserSession();
  //     $this->load->model('email_manager');
  //     $this->em  = $this->email_manager;
  //     $user_session_data = getSessionData();
  //     $ship_details = getCustomSession('ship_details');
  //     $returnArr['status'] = 100;
  //     $actionType = $this->input->post('actionType');
  //     $delivery_note_id = $this->input->post('id');
  //     $data = (array) $this->mp->getDeliveryNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
  //     $json_data = unserialize($data['json_data']);
  //     $vars['headData'] = $data;
  //     $arrData = array();
  //     $a = [];       
  //        if(!empty($json_data)){
  //          for ($i=0; $i <count($json_data) ; $i++) { 
  //             $a[$json_data[$i]['product_id']] = $json_data[$i];
  //           } 
  //        } 

  //     if(!empty($a)){
  //       $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
  //       if(!empty($products)){
  //          foreach ($products as $row) {
  //            $arrData[$row->sequence][$row->category_name][] = array(
  //                'category_name'=>$row->category_name,
  //                'product_category_id'=>$row->product_category_id,
  //                'product_name'=>$row->product_name,
  //                'product_id'=>$row->product_id,
  //                'unit'=>$row->unit,
  //                'item_no'=>$row->item_no,
  //                'sequence'=>$row->sequence,
  //                'quantity'=>$a[$row->product_id]['quantity'],
  //                'unit_price'=> $a[$row->product_id]['unit_price']
  //             );   
  //           } 
  //        }
  //     } 

  //     if($actionType=='save'){
  //       // $this->form_validation->set_rules('jsignature','Signature','trim|required');
  //       $this->form_validation->set_rules('blob_url','Signature','trim|required');

  //       for ($i=0; $i <count($json_data) ; $i++) { 
  //          // $this->form_validation->set_rules('qty_'.$json_data[$i]['product_id'],'QTY','trim|required');
  //           $type = $this->input->post('type_'.$json_data[$i]['product_id']);
  //           if($type == 'short_supply' || $type == 'wrong_supply'){
  //             $this->form_validation->set_rules('supply_qty_'.$json_data[$i]['product_id'],'Supply','trim|required');
  //           }   
  //           elseif($type == 'poor_quality' || $type == 'damange_and_spoil'){
  //             // $this->form_validation->set_rules('img_'.$json_data[$i]['product_id'],'','callback_del_file_check['.$json_data[$i]['product_id'].']');
  //           }
  //           elseif($type == 'other'){
  //             $this->form_validation->set_rules('comment_'.$json_data[$i]['product_id'],'Comment','trim|required');
  //           }
  //       }

  //      if($this->form_validation->run()){
  //        $jsignature =  $this->input->post('blob_url');
  //         $dataArr['delivery_note_id'] = $delivery_note_id;
  //         $dataArr['added_on'] = date('Y-m-d H:i:s');
  //         $dataArr['added_by'] = $user_session_data->user_id;
  //        if(!empty($jsignature)){
  //           $jsignature = base64_decode($jsignature);
  //           $upload_path = getcwd().'/uploads/e_signature/';
  //           $output_file = $upload_path.'e_signature'.date('ymdhis').'.png';
  //           $output_jpeg = base64_to_jpeg($jsignature,$output_file);
  //           if(file_exists($output_jpeg)){
  //              $dataArr['e_sign'] = 'e_signature'.date('ymdhis').'.png';           
  //           }
  //        }
  //        $batch = array();
  //           $is_cn_required = 0; 
  //            if(!empty($json_data)){
  //            for($j=0; $j <count($json_data) ; $j++) {
  //             $supply_qty = '';
  //             $img_url = '';
  //             $type = '';
  //             $other = '';
  //             $product_id = '';
  //             $product_id =  $json_data[$j]['product_id'];
  //             $type = $this->input->post('type_'.$product_id);
  //             $qty = $this->input->post('qty_'.$product_id);
  //              if($type=='damange_and_spoil' || $type=='poor_quality'){
  //                  $file_name = $_FILES['img_'.$product_id]['name'];
  //                   $config['upload_path'] = FCPATH.'uploads/delivery_receipt/';
  //                   assert(file_exists($config['upload_path']) === TRUE);
  //                   $config['allowed_types'] = 'gif|jpg|jpeg|png';
  //                   $config['max_size'] = 2000;
  //                   $config['file_name'] = $file_name;
  //                   $this->load->library('upload', $config);
  //                   $this->upload->initialize($config);
  //                   if($this->upload->do_upload('img_'.$product_id)){
  //                    $upload_data = $this->upload->data();   
  //                   }
  //                  $img_url = $upload_data['file_name'];
  //                  $is_cn_required = 1;      
  //               }
  //               elseif($type=='short_supply' || $type=='wrong_supply'){
  //                  $supply_qty = $this->input->post('supply_qty_'.$product_id);
  //                  $is_cn_required = 1;
  //               }
  //               elseif($type=='other'){
  //                  $other = $this->input->post('comment_'.$product_id);
  //                  $is_cn_required = 1;
  //               }

  //               $batch[] = array('product_id'=>$product_id,'type'=>$type,'img_url'=>$img_url,'supply_qty'=>$supply_qty,'comment'=>$other);        
  //             }
  //         }

  //        $dataArr['json_data'] = serialize($batch);
         
  //        $this->db->insert('delivery_receipt',$dataArr); 
         
  //        if($is_cn_required==1){
  //           $this->db->update('company_invoice',array('status'=>'CN pending'),array('delivery_note_id'=>$delivery_note_id));
  //        }
         
  //       $this->db->update('delivery_note',array('is_cn_required'=>$is_cn_required,'status'=>2),array('delivery_note_id'=>$delivery_note_id));
  //       $this->db->update('work_order',array('is_dn_sign'=>1),array('work_order_id'=>$data['work_order_id'])); 
  //        $returnArr['status'] = 200;
  //        $returnArr['returnMsg'] = 'Delivery Receipt Signed successfully'; 
  //         DN Signed Email Notification to Admin
  //         $whereEm = ' AND em.template_code = "dn_sign"';
  //         $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
  //         if(!empty($emailTemplateData)){
  //           $recdata = (array) $this->cm->getDeliveyReceipt(' AND dn.delivery_note_id ='.$delivery_note_id);
  //          $subject = str_replace(array('##ship_name##','##note_no##','##imo_no##','##port_name##','##delivery_date##','##req_type##'),array(ucwords($recdata['ship_name']),$recdata['note_no'],$recdata['imo_no'],ucwords($recdata['delivery_port']),convertDate($recdata['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$recdata['requisition_type']))),$emailTemplateData->email_subject);
  //          $body = str_replace(array('##port_name##','##delivery_date##','##ship_name##','##company_name##','##master_name##'),array(ucwords($recdata['delivery_port']),convertDate($recdata['delivery_date'],'','d-m-Y'),ucwords($recdata['ship_name']),ucwords($recdata['company_name']),ucfirst($user_session_data->first_name.' '.$user_session_data->last_name)),$emailTemplateData->email_body);


  //               require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
  //               $pdf_vars['dataArr'] = $recdata;
  //               $recept_data = unserialize($recdata['json_data']);
  //               $sign = array(); 
  //               if(!empty($recept_data)){
  //                 for ($r=0; $r < count($recept_data); $r++) { 
  //                    $sign[$recept_data[$r]['product_id']] = array('type'=>$recept_data[$r]['type'],'img_url'=>$recept_data[$r]['img_url'],'supply_qty'=>$recept_data[$r]['supply_qty'],'comment'=>$recept_data[$r]['comment']);  
  //                   }  
  //               }

  //               $mailArr = array();
  //               if(!empty($json_data)){
  //                for ($i=0; $i <count($json_data) ; $i++) { 
  //                 $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
  //                  $product_id = $product['product_id'];
  //                  $mailArr[$product['sequence']][$product['category_name']][] = array(
  //                    'category_name'=>$product['category_name'],
  //                    'product_category_id'=>$product['product_category_id'],
  //                    'product_name'=>$product['product_name'],
  //                    'product_id'=>$product_id,
  //                    'unit'=>$product['unit'],
  //                    'item_no'=>$product['item_no'],
  //                    'sequence'=>$product['sequence'],
  //                    'quantity'=>$json_data[$i]['quantity'],
  //                    'unit_price'=> $json_data[$i]['unit_price'],
  //                    'type' => $sign[$product_id]['type'],
  //                    'img_url'=> $sign[$product_id]['img_url'],
  //                    'supply_qty'=> $sign[$product_id]['supply_qty'],
  //                    'comment'=> $sign[$product_id]['comment']
  //                  );   
  //                 } 
  //               } 
  //                 $pdf_vars['productArr'] = $mailArr;
  //                 $pdf_vars['title'] = 'Delivery Note Receipt';
  //                 $html = $this->load->view('delivery_note_pdf',$pdf_vars,TRUE);
  //                 $file = str_replace('/','--','DNR-'.$data['note_no']);
  //                 $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
  //                 $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  //                 $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  //                 $pdf->AddPage('L');
  //                 $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
  //                 ob_clean();ob_end_clean();ob_flush();
  //                 $pdfData = $pdf->Output('', 'S');
  //                 $filePath = $pdfFilePath;
  //                 write_file($filePath, $pdfData);
  //             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
  //             if(!empty($email_roles)){
  //              foreach ($email_roles as $row) {
  //                 $user_list = $this->em->getUserByRoleID($row->role_id);
  //                  if(!empty($user_list)){
  //                      foreach ($user_list as $val) {
  //                          $emArr['user_id'] = $val->user_id;
  //                          $emArr['subject'] = $subject;
  //                          $emArr['body'] = $body;
  //                          $emArr['attechment'] = $file;
  //                          $this->em->add_email_log($emArr);
  //                      }
  //                   }           
  //           }
  //       }
  //   }

  //        if($user_session_data->code=='captain'){
  //          $wherenn = ' AND nt.code = "receipt_sign"';
  //          $templateData = $this->um->getNotifyTemplateByCode($wherenn);
  //          if(!empty($templateData)){
  //           $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
  //            if(!empty($roles)){
  //             foreach ($roles as $row) {
  //                $user_data = $this->em->getUserByRoleID($row->role_id);
  //                 if(!empty($user_data)){
  //                   foreach ($user_data as $val) {
  //                     $noteArr['date'] = date('Y-m-d H:i:s');
  //                     $noteArr['user_id'] = $val->user_id;
  //                     $noteArr['title'] = $templateData->title;
  //                     $noteArr['long_desc'] = str_replace(array(' ##captain_name##.','##ship_name##'),array(ucfirst($user_session_data->first_name.' '.$user_session_data->last_name),ucwords($ship_details['ship_name'])),$templateData->body); 
  //                      $this->um->add_notify($noteArr);  
  //                  }
  //                 }
  //               }
  //             }
  //           }
  //         }

  //         /*DN SIgned Email Notification to Admin*/
  //        }
  //     }

  //     $postArr['id'] = $data['delivery_note_id'];
  //     $vars['productArr'] = $arrData;
  //     $vars['dataArr'] = $this->input->post();
  //     $data = $this->load->view('delivery_receipt',$vars,true);
  //     $returnArr['data'] = $data;
  //     echo json_encode($returnArr); 
  //  }


    function delivery_receipt(){
      checkUserSession();
      $this->load->model('email_manager');
      $this->em  = $this->email_manager;
      $user_session_data = getSessionData();
      $ship_details = getCustomSession('ship_details');
      $returnArr['status'] = 100;
      $actionType = $this->input->post('actionType');
      $delivery_note_id = $this->input->post('id');
      $data = (array) $this->mp->getDeliveryNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
      $json_data = unserialize($data['json_data']);
      $vars['headData'] = $data;
      $arrData = array();
      $a = [];       
         if(!empty($json_data)){
           for ($i=0; $i <count($json_data) ; $i++) { 
              $a[$json_data[$i]['product_id']] = $json_data[$i];
            } 
         } 

      if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
             $arrData[$row->sequence][$row->category_name][] = array(
                 'category_name'=>$row->category_name,
                 'product_category_id'=>$row->product_category_id,
                 'product_name'=>$row->product_name,
                 'product_id'=>$row->product_id,
                 'unit'=>$row->unit,
                 'item_no'=>$row->item_no,
                 'sequence'=>$row->sequence,
                 'quantity'=>$a[$row->product_id]['quantity'],
                 'unit_price'=> $a[$row->product_id]['unit_price']
              );   
            } 
         }
      } 

      if($actionType=='save'){
        $this->form_validation->set_rules('blob_url','Signature','trim|required');

        for ($i=0; $i <count($json_data) ; $i++) { 
            $type = $this->input->post('type_'.$json_data[$i]['product_id']);
            if($type == 'short_supply' || $type == 'wrong_supply'){
              $this->form_validation->set_rules('supply_qty_'.$json_data[$i]['product_id'],'Supply','trim|required');
            }   
            elseif($type == 'poor_quality' || $type == 'damange_and_spoil'){
              // $this->form_validation->set_rules('img_'.$json_data[$i]['product_id'],'','callback_del_file_check['.$json_data[$i]['product_id'].']');
            }
            elseif($type == 'other'){
              $this->form_validation->set_rules('comment_'.$json_data[$i]['product_id'],'Comment','trim|required');
            }
        }

       if($this->form_validation->run()){
         $jsignature =  $this->input->post('blob_url');
          $dataArr['delivery_note_id'] = $delivery_note_id;
          $dataArr['added_on'] = date('Y-m-d H:i:s');
          $dataArr['added_by'] = $user_session_data->user_id;
         if(!empty($jsignature)){
            $jsignature = base64_decode($jsignature);
            $upload_path = getcwd().'/uploads/e_signature/';
            $output_file = $upload_path.'e_signature'.date('ymdhis').'.png';
            $output_jpeg = base64_to_jpeg($jsignature,$output_file);
            if(file_exists($output_jpeg)){
               $dataArr['e_sign'] = 'e_signature'.date('ymdhis').'.png';           
            }
         }
         $batch = array();
            $is_cn_required = 0; 
             if(!empty($json_data)){
             for($j=0; $j <count($json_data) ; $j++) {
              $supply_qty = '';
              $img_url = '';
              $type = '';
              $other = '';
              $product_id = '';
              $product_id =  $json_data[$j]['product_id'];
              $type = $this->input->post('type_'.$product_id);
              $qty = $this->input->post('qty_'.$product_id);
               if($type=='damange_and_spoil' || $type=='poor_quality'){
                   $file_name = $_FILES['img_'.$product_id]['name'];
                    $config['upload_path'] = FCPATH.'uploads/delivery_receipt/';
                    assert(file_exists($config['upload_path']) === TRUE);
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['max_size'] = 2000;
                    $config['file_name'] = $file_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('img_'.$product_id)){
                     $upload_data = $this->upload->data();   
                    }
                   $img_url = $upload_data['file_name'];
                   $is_cn_required = 1;      
                }
                elseif($type=='short_supply' || $type=='wrong_supply'){
                   $supply_qty = $this->input->post('supply_qty_'.$product_id);
                   $is_cn_required = 1;
                }
                elseif($type=='other'){
                   $other = $this->input->post('comment_'.$product_id);
                   $is_cn_required = 1;
                }

                $batch[] = array('product_id'=>$product_id,'type'=>$type,'img_url'=>$img_url,'supply_qty'=>$supply_qty,'comment'=>$other);        
              }
          }

         $dataArr['json_data'] = serialize($batch);
         $dataArr['is_cn_required'] = $is_cn_required;

         $this->db->insert('tmp_delivery_receipt',$dataArr); 
         $tmp_delivery_receipt_id = $this->db->insert_id();
         $returnArr['status'] = 200;
         $returnArr['tmp_delivery_receipt_id'] = $tmp_delivery_receipt_id;
      }
  }

      $postArr['id'] = $data['delivery_note_id'];
      $vars['productArr'] = $arrData;
      $vars['dataArr'] = $this->input->post();
      $data = $this->load->view('delivery_receipt',$vars,true);
      $returnArr['data'] = $data;
      echo json_encode($returnArr); 
   }


  function stock_list($ship_id=''){
        checkUserSession();
        $ship_id = base64_decode($ship_id);
        $user_session_data = getSessionData();
        $vars['ship_id'] = $ship_id;
        $data = $this->load->view('stock_list',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }


 function getAllStockList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $order_by = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
  
    $ship_id = $this->input->post('ship_id');
    if(!empty($ship_id)){
      $where .= 'AND st.ship_id = '.$ship_id;
    }    
   
    if(!empty($type)){
      if($type=='OS'){
        $where .= ' AND st.delivery_note_id IS NULL';
      }
      elseif($type=='IU'){
        $where .= ' AND st.delivery_note_id IS NOT NULL';
      }
    }

    if($keyword){
      $where .= ' AND (u.first_name like "%'.trim($keyword).'%"  or u.last_name like "%'.trim($keyword).'%" or concat(u.first_name," ",u.last_name) like "%'.trim($keyword).'%")';
    }

    if($created_on){
      $where .= ' AND date(st.created_on) = "'.convertDate($created_on,'','Y-m-d').'"';   
    }

    if(!empty($sortt_column) && !empty($sortt_type)){
      if($sortt_column=='Type'){
        $order_by = ' ORDER BY st.delivery_note_id '.$sortt_type;  
      }
      elseif($sortt_column=='Total Price'){
        $order_by = ' ORDER BY st.total_price '.$sortt_type;  
      }
      elseif($sortt_column=='Note No'){
        $order_by = ' ORDER BY dn.note_no '.$sortt_type;  
      }
      elseif($sortt_column=='Delivery Date'){
        $order_by = ' ORDER BY wo.delivery_date '.$sortt_type;  
      }
      elseif($sortt_column=='Created On'){
        $order_by = ' ORDER BY st.created_on '.$sortt_type;  
      }
     elseif($sortt_column=='Created By'){
        $order_by = ' ORDER BY u.first_name '.$sortt_type;  
      }
    }
    else{
      $order_by = ' ORDER BY st.created_on DESC ';  
    }

    if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllStockList($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }

   if($download==1){
     $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'Inventory';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Type','Total Price($)','Created On','Created By');
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

            $stock_list = $this->cm->getAllStockList($where,'R',$perPage,$offset,$order_by);

            if($stock_list){
                foreach ($stock_list as $row) {
                    $k++;
                    $type = ($row->delivery_note_id == '')?'Opening Stock':'Inventory Update';
                    $arrayData[] = array($type,$row->total_price,ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucfirst($row->user_name));
                }
            }

           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:D'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit; 
   }

    $countdata = $this->cm->getAllStockList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
    $stock_list = $this->cm->getAllStockList($where,'R',$perPage,$offset,$order_by);
    $edit_stock = checkLabelByTask('edit_stock'); 
    $submit_opening_stock = checkLabelByTask('submit_opening_stock');
    $discount_label = checkLabelByTask('show_invoice_discount');
    if($stock_list){
     $total_entries = 'Showing '.($offset+1).' to '.($offset+count($stock_list)).' of '.$countdata.' entries';
     foreach ($stock_list as $row){

         $type = $dn =  $edit = $submit= '';
         $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Stock Details\',\'shipping/viewStockDetails\',\''.$row->ship_stock_id.'\',\'\',\'98%\',\'full-width-model\')">View Stock Details</a>'; 
         $type = ($row->delivery_note_id == '') ? 'Opening Stock':'Inventory Update';

         $monthName = ($row->delivery_note_id == '') ? convertDate($row->stock_date,'','M Y') : convertDate($row->delivery_date,'','M Y');


         if(empty($row->delivery_note_id) && $row->is_submit==0){
          if($edit_stock){
            $edit = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Edit Opening Stock\',\'shipping/add_stock_details/edit\',\''.$row->ship_stock_id.'\',\'\',\'98%\',\'full-width-model\')">Edit Stock</a>';
           }
          if($submit_opening_stock){
             $submit = '<a href="javascript:void(0)" onclick="submitStock('.$row->ship_stock_id.')">Submit Stock</a>';
            }
         }
        $returnArr .= "<tr>
            <td width='10%'>".$type."</td>
            <td width='10%'>".$row->note_no."</td>
            <td width='10%'>".$monthName."</td>";
          if(empty($row->delivery_note_id)){
            $returnArr .=  "<td width='10%'>".number_format($row->total_price,2)."</td>";

          }else{
          $returnArr .=  "<td width='10%'>".(($discount_label) ?  number_format($row->total_price,2) : number_format($row->invoice_actual_price,2))."</td>";

          }  
         $returnArr .=    "<td width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            ";
        $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
            <li>'.$edit.'</li>
            <li>'.$submit.'</li>
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

  function add_inventory(){
      checkUserSession();
      $user_session_data = getSessionData();
      $returnArr['status'] = 100;
      $actionType = $this->input->post('actionType');
      $ship_details = getCustomSession('ship_details'); 
      $ship_id = $ship_details['ship_id'];
      $delivery_note_id = $this->input->post('id');
      $data = (array) $this->mp->getCompanyInvoice(' AND ci.delivery_note_id ='.$delivery_note_id);
      
      if(!empty($data['month']) && !empty($data['year'])){
         $current_stock = $this->cm->monthly_stock_details(' AND ms.ship_id='.$ship_id.' AND ms.month = '.$data['month'].' AND ms.year = '.$data['year']);
         $current_stock_arr = array();
         if(!empty($current_stock)){
            foreach ($current_stock as $val) {
              $current_stock_arr[$val->product_id] = (array) $val;
            }            
         }
      }

     $month_stock_id = $current_stock[0]->month_stock_id;
     $received_meat_qty = $current_stock[0]->received_meat_qty;
     $closing_meat_qty = $current_stock[0]->closing_meat_qty;     



      // $current_stock = $this->cm->get_current_stock(' AND cs.ship_id = '.$ship_id);
      // $current_stock_arr = array();
      // if(!empty($current_stock)){
      //   foreach ($current_stock as $val) {
      //    $current_stock_arr[$val->product_id] = (array) $val;
      //   }
      // }

      // $month_stock = (array) $this->cm->monthly_stock(' AND msv.ship_id = '.$ship_id.' AND msv.month ='.date('m').' AND msv.year ='.date('Y'));
      // $received_meat_qty = $month_stock['received_meat_qty'];
      // $closing_meat_qty = $month_stock['closing_meat_qty'];
      // if(empty($month_stock)){
      //    $this->update_month_stock($ship_id); 
      // }

      $json_data = unserialize($data['json_data']);

      $arrData = array();
      $a = []; 
      if(!empty($json_data)){
        for ($i=0; $i <count($json_data) ; $i++) { 
          $a[$json_data[$i]['product_id']] = $json_data[$i];
        } 
     }

     if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
           foreach ($products as $row) {
             $arrData[$row->sequence][$row->category_name][] = array(
                 'category_name'=>$row->category_name,
                 'product_category_id'=>$row->product_category_id,
                 'product_name'=>$row->product_name,
                 'product_id'=>$row->product_id,
                 'unit'=>$row->unit,
                 'item_no'=>$row->item_no,
                 'sequence'=>$row->sequence,
                 'quantity'=>$a[$row->product_id]['qty'],
                 'group_name'=>$row->group_name,
                 'last_quantity'=>$current_stock_arr[$row->product_id]['available_stock'],
                 'unit_price' => $a[$row->product_id]['unit_price']

              );  
            } 
        }
      }  

      if($actionType=='save'){
           $dataArr['ship_id'] = $ship_id;
           $dataArr['created_by'] = $user_session_data->user_id;
           $dataArr['created_on'] = date('Y-m-d H:i:s');
           $dataArr['delivery_note_id'] = $delivery_note_id;
           $ship_stock_id = $this->cm->add_ship_stock($dataArr);
           $batch = array();
           $total_price = 0;
           $meat = 0;
           if(!empty($json_data)){
                for ($j=0; $j <count($json_data); $j++) { 
                    $product_id = $json_data[$j]['product_id'];
                    $qty = trim($this->input->post('qty_'.$product_id));
                    $remark = trim($this->input->post('remark_'.$product_id));
                    $unit_price = trim($this->input->post('price_'.$product_id));
                    $group_name = trim($this->input->post('group_'.$product_id));
                    $batch[] = array('ship_stock_id'=>$ship_stock_id,'product_id'=>$product_id,'quantity'=>$qty,'unit_price'=>$unit_price,'total_price'=>($qty * $unit_price),'remark'=>$remark);

                     if($group_name=='Meat'){
                        $meat+= $qty;
                     }

                    // $current_stock_id = $current_stock_arr[$product_id]['stock_detail_id'];

                    $monthly_stock_detail_id = $current_stock_arr[$product_id]['monthly_stock_detail_id'];                  
                    $total_stock = $current_stock_arr[$product_id]['total_stock'];
                    $used_stock = $current_stock_arr[$product_id]['used_stock'];
                    $available_stock = $current_stock_arr[$product_id]['available_stock'];
                    $cunit_price =  $current_stock_arr[$product_id]['unit_price'];
                    
                    if(!empty($data['invoice_discount'])){
                        $dis_unit_price = ($unit_price * $data['invoice_discount']) / 100;
                        $net_unit_price = $unit_price - $dis_unit_price;                        
                    }
                    else{
                       $net_unit_price = $unit_price;  
                    }   

                    $total_price += ($qty * $net_unit_price);
                    
                    $csArr = array();   
                    if(!empty($monthly_stock_detail_id)){
                        $csArr['last_total_stock'] = $total_stock;
                        $csArr['total_stock'] = ($available_stock + $qty);
                        $csArr['last_available_stock'] = $available_stock;
                        $csArr['available_stock'] = ($available_stock + $qty);
                        $csArr['last_used_stock'] = $used_stock; 
                        $csArr['used_stock'] = 0;
                        $csArr['last_unit_price'] =  $cunit_price;
                        $avarge_unit_price = ( (($available_stock * $cunit_price) + ($qty * $net_unit_price) ) ) / ($available_stock + $qty); 
                        $csArr['unit_price'] = $avarge_unit_price; 
                        $csArr['updated_on'] = date('Y-m-d H:i:s');
                        $csArr['updated_by'] = $user_session_data->user_id;
                        $where = array('monthly_stock_detail_id'=>$monthly_stock_detail_id);
                       // $this->cm->edit_ship_current_stock($csArr,$where);
                       $this->cm->edit_month_stock_details($csArr,$where);

                    // echo $this->db->last_query();die;
                    
                    }
                    else{
                       // $csArr['ship_id'] = $ship_id;
                       $csArr['product_id'] = $product_id;
                       $csArr['total_stock'] = $qty;
                       $csArr['unit_price'] = $net_unit_price;
                       $csArr['month_stock_id'] = $month_stock_id;

                       // $csArr['added_on'] = date('Y-m-d H:i:s');
                       // $csArr['added_by'] = $user_session_data->user_id;
                       $csArr['available_stock'] = $qty;
                      // $this->cm->add_ship_current_stock($csArr);
                       $this->cm->add_month_stock_details($csArr);

                       // echo $this->db->last_query();die;

                    }
                }
           } 

          $closing_ss = ($closing_meat_qty) ? $closing_meat_qty+$meat : 0; 
          $mArr = array('received_meat_qty'=>$received_meat_qty+$meat,'closing_meat_qty'=>$closing_ss);

          $this->db->update('month_stock',$mArr,array('month_stock_id'=>$month_stock_id));

          // $wArr = array('ship_id'=>$ship_id,'month'=>date('m'),'year'=>date('Y'));
          // // $this->cm->update_meat_stock($mArr,$wArr); 
          
          $this->db->update('delivery_note',array('is_used'=>1),array('delivery_note_id'=>$delivery_note_id));   
          $this->db->insert_batch('ship_stock_details',$batch);
          $this->db->update('ship_stock',array('json_data'=>serialize($batch),'total_price'=>$total_price),array('ship_stock_id'=>$ship_stock_id));
          $returnArr['status'] = 200;
          $returnArr['returnMsg'] = 'Ship Stock Updated successfully';   
        
        //  /*Stock Updated Email Notification to Admin*/
          $whereEm = ' AND em.template_code = "STOCK_UPDATE"';
          $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
          if(!empty($emailTemplateData)){
            $whereUser = ' and r.code = "super_admin"';
            $superAdminData = $this->um->getuserdatabyid($whereUser);
            if(!empty($superAdminData)){
                $subject = $emailTemplateData->email_subject;
                $body = str_replace(array('##username##'),array($superAdminData->user_name),$emailTemplateData->email_body);
                $to =  $superAdminData->email;
                $this->um->sendMail($to,$subject,$body);
            }
            
          }
          /*Stock Updated Email Notification to Admin*/
      } 

      ksort($arrData);
      $vars['current_stock'] = $current_stock;
      $vars['month'] = $data['month'];
      $vars['year'] = $data['year'];
      $vars['discount'] = $data['invoice_discount'];
      $vars['productArr'] = $arrData;
      $vars['dataArr'] = $this->input->post();
      $data = $this->load->view('add_inventory',$vars,true);
      $returnArr['data'] = $data;
      echo json_encode($returnArr);
  }  
  
  function viewStockDetails(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $discount_label = checkLabelByTask('show_invoice_discount');
    $stockId = $this->input->post('id');
    $where = ' AND sst.ship_stock_id='.$stockId;
    $data = (array) $this->mp->getStockDetail($where); 
    $group_products = $this->mp->getAllProductGroup('','R');   
    $dataArr = unserialize($data['json_data']);
    $a = [];
    if(!empty($dataArr)){

     for ($i=0; $i <count($dataArr) ; $i++) { 
       $a[$dataArr[$i]['product_id']] = $dataArr[$i];  
     }

      $productArr = array();
     if(!empty($a)){
       $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in('.implode(',',array_keys($a)).')','R');
       if(!empty($products)){
         foreach ($products as $row) {
             $productArr[$row->sequence][$row->category_name][] = array('category_name'=>$row->category_name,'product_category_id'=>$row->product_category_id,'product_name'=>$row->product_name,'product_id'=>$row->product_id,'quantity'=>$a[$row->product_id]['quantity'],'value'=>$a[$row->product_id]['value'],'unit_price'=>$a[$row->product_id]['unit_price'],'unit'=>$row->unit,'item_no'=>$row->item_no,'sequence'=>$row->sequence,'remark'=>$a[$row->product_id]['remark'],'group'=>strtolower(str_replace(array('&',' '),array('_',''),$row->group_name)));
         }
       }
     } 

    ksort($productArr);
        
        $gd_tl_pc = 0;
        $gd_tl_qt = 0;

        $meat = 0;
        $rice = 0;
        $fruit = 0;
        if(!empty($productArr)){
            $returnArr = '
            <div class="animated fadeIn" id="stock_form">
            <div class="row">
            <div class="col-md-12">
            <form class="form-horizontal form-bordered" name="store_vendor_invoice" enctype="multipart/form-data" id="store_vendor_invoice" method="post">
                    <div class="no-padding rounded-bottom">
                    <div class="form-body">
                    <div id="abc" class="sip-table" role="grid">
            <table class="table header-fixed-new table-text-ellipsis table-layout-fixed" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
            <thead class="t-header">
            <tr>
              <th width="10%">Item No.</th>
              <th width="30%">Description</th>
              <th width="10%">Unit</th>
              <th width="10%">Qty</th>
              <th width="10%">Unit Price($)</th>
              <th width="10%">Total Price($)</th>
              <th width="20%">Remark</th>
              
              </tr>
            </thead><tbody>';

            foreach($productArr as $parent => $rows){
             foreach($rows as $category => $products){
                 $total_qty = 0;
                 $total_price = 0; 
                  $returnArr .= '<tr class="parent_row">
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    </tr>';

                    for ($i=0; $i <count($products) ; $i++) {
                        if($products[$i]['group']=='meat'){
                             $meat += $products[$i]['quantity'];
                        } 
                        elseif($products[$i]['group']=='fruit_vegetables'){
                            $fruit +=$products[$i]['quantity'];
                        }
                        elseif($products[$i]['group']=='rice_flour'){
                            $rice += $products[$i]['quantity'];
                        }
                       $total_qty += $products[$i]['quantity']; 
                       $total_price += ($products[$i]['unit_price'] * $products[$i]['quantity']);
                          $returnArr .= '<tr class="child_row">';
                          $returnArr .= '<td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                          $returnArr .= '<td  width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                          $returnArr .= '<td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                          $returnArr .= '<td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                          $returnArr .= '<td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'</td>';
                          $returnArr .= '<td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'] * $products[$i]['quantity'],2).'</td>';
                          $returnArr .= '<td  width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['remark']).'</td>';
                          $returnArr .= '</tr>';
                    }

                  $returnArr .= '<tr class="child_row_count"><td></td><td style="text-align: right;
  font-weight: bold;
  font-size: 11px;">Total</td><td></td><td>'.$total_qty.'</td><td></td><td>'.$total_price.'</td><td></td></tr>';
                         $gd_tl_qt += $total_qty;
                       $gd_tl_pc += $total_price;                       
                }            
            }

            $returnArr .= '<tr class="child_row">';  
            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;
  font-weight: bold;
  font-size: 11px;" colspan="2">Grand Total</td>';                
            $returnArr .= '<td></td><td>'.$gd_tl_qt.'</td><td></td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;
  font-weight: bold;
  font-size: 11px;">$'.number_format($gd_tl_pc,2).'</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td></tr>';
      if($discount_label){
         $returnArr .= '<tr class="child_row">';  
        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;
         font-weight: bold;
       font-size: 11px;" colspan="2">Discount(%)</td><td></td><td></td><td></td>';                
                $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;
      font-weight: bold;
      font-size: 11px;">'.number_format($data['invoice_discount'],2).'%</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td></tr>';
       $discount_amount = ($gd_tl_pc*$data['invoice_discount']) / 100;
       $net_amount = $gd_tl_pc - $discount_amount;
       $returnArr .= '<tr class="child_row">';  
       $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;
         font-weight: bold;
       font-size: 11px;" colspan="2">Net Amount</td><td></td><td></td><td></td>';                
        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;
      font-weight: bold;
      font-size: 11px;">$ '.number_format($net_amount,2).'</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td></tr>';    
      }
     }
        $returnArr .= '</tbody></table></div>';
        $returnArr .='<br><div class="sip-table mb-15" role="grid">
            <table class="table predefineProTbl">
                <thead>
                <tr>
                  <th width="25%">Group</th>
                  <th width="12%">Unit</th>
                  <th width="12%">Per Man Per Day</th>
                  <th width="15%">Total QTY</th>
                </tr>
                </thead>
                <tbody class="group_data">';
                 if(!empty($group_products)){
                   foreach ($group_products as $row) {
                    $group_name = strtolower(str_replace(array('&',' '),array('_',''), $row->name));
                       if($row->unit == 1){
                          $unit = "KG"; 
                        }else if($row->unit == 2){
                              $unit = "Liter"; 
                       }
                       
                  $returnArr .= '<tr>
                    <td width="25%">'.ucfirst($row->name).'</td>
                    <td width="12%">'.$unit.'</td>
                    <td width="12%">'.$row->consumed_qty.'</td>';
                    if($group_name=='meat'){
                      $returnArr .='<td width="15%">'.$meat.'</td>';
                    } 
                    elseif($group_name=='fruit_vegetables'){
                      $returnArr .='<td width="15%">'.$fruit.'</td>';
                    }
                    elseif($group_name=='rice_flour'){
                      $returnArr .='<td width="15%">'.$rice.'</td>';
                    }
                    $returnArr .='</tr>';   
                 }
               }
      $returnArr .= '</tbody></table></div>';
      $returnArr .='</div></div></form></div>';
    }
    echo json_encode(array('data'=>$returnArr,'status' => 100));
  }

  function consumed_stock_list($ship_id=''){
        checkUserSession();
        $ship_id = base64_decode($ship_id);
        $user_session_data = getSessionData();
        $vars['ship_id'] = $ship_id;
        $data = $this->load->view('consumed_stock_list',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }


function getAllConsumedStockList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $order_by = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
    // $ship_details = getCustomSession('ship_details');
    // if(!empty($ship_details['ship_id'])){
    //   $where .= 'AND ct.ship_id = '.$ship_details['ship_id'];
    // }

    if(!empty($ship_id)){
      $where .= 'AND ct.ship_id = '.$ship_id;
    }
    
    if($keyword){
      $where .= ' AND (u.first_name like "%'.trim($keyword).'%"  or u.last_name like "%'.trim($keyword).'%" or concat(u.first_name," ",u.last_name) like "%'.trim($keyword).'%")';
    }

    if($created_on){
      $where .= ' AND date(ct.added_on) = "'.convertDate($created_on,'','Y-m-d').'"';   
    }

    if($type){
      $where .= ' AND ct.type = "'.$type.'"';  
    }

   if(!empty($soort_column) && !empty($soort_type)){
      if($soort_column=='Type'){
        $order_by = ' ORDER BY ct.type '.$soort_type;  
      }
      elseif($soort_column=='Total Price'){
        $order_by = ' ORDER BY ct.total_price '.$soort_type;  
      }
      elseif($soort_column=='Created On'){
        $order_by = ' ORDER BY ct.added_on '.$soort_type;  
      }
     elseif($soort_column=='Created By'){
        $order_by = ' ORDER BY u.first_name '.$soort_type;  
      }
    }
    else{
      $order_by = ' ORDER BY ct.added_on DESC ';  
    }

    if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllConsumedStockList($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }

   if($download==1){
     $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'StockControl';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Type','Total Price($)','Created On','Created By');
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
           $consumed_stock_list = $this->cm->getAllConsumedStockList($where,'R',$perPage,$offset,$order_by);
           
           if($consumed_stock_list){
            foreach ($consumed_stock_list as $row) {
               $k++;
                $jsonArray = unserialize($row->json_data);
                $total_items = 0;
                $total_amount = 0;
                  foreach($jsonArray as $single_product){
                     //$total_items += 1;
                     $total_items = $total_items+$single_product['quantity'];
                     $total_amount = $total_amount +$single_product['value'];
                  }
               $type = ($row->type=='closing_stock') ? 'Closing Stock' : 'Used Stock';
               $arrayData[] = array($type,number_format($total_amount,2),ConvertDate($row->added_on,'','d-m-Y | h:i A'),ucfirst($row->user_name));
            }
           }

           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:D'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit; 
   }
 

    $countdata = $this->cm->getAllConsumedStockList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
    $consumed_stock_list = $this->cm->getAllConsumedStockList($where,'R',$perPage,$offset,$order_by);
        //print_r($consumed_stock_list);die;
            if($consumed_stock_list){
            $total_entries = 'Showing '.($offset+1).' to '.($offset+count($consumed_stock_list)).' of '.$countdata.' entries';
            foreach ($consumed_stock_list as $row){
               
              $jsonArray = unserialize($row->json_data);
              $total_items = 0;
              $total_amount = 0;
              foreach($jsonArray as $single_product){
                 //$total_items += 1;
                 $total_items = $total_items+$single_product['quantity'];
                 $total_amount = $total_amount +$single_product['value'];
              }
              //$checkEditPermission = checkPermissionByRole($user_session_data->code, $row->added_on);
              $label_details = ($row->type=='closing_stock') ? 'View Closing Stock Details' : 'View Used Stock Details';

              $dn = '<a href="javascript:void(0)" onclick="showAjaxModel(\''.$label_details.'\',\'shipping/viewConsumedStockDetailsNew\',\''.$row->consumed_stock_id.'\',\'\',\'98%\',\'full-width-model\')">'.$label_details.'</a>';
         
            $type = ($row->type=='closing_stock') ? 'Closing Stock' : 'Used Stock';
            $monthName = '-';
            if(!empty($row->month)){
                $monthNum  = $row->month;
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
                $monthName = $monthName.' '.$row->year;                
            }


            $returnArr .= "<tr>
            <td width='10%'>".$type."</td>
            <td width='10%'>".number_format($total_amount,2)."</td>
            <td width='10%'>".$monthName."</td>
            <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            ";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
            <li>'.$dn.'</li>
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

 function viewConsumedStockDetails(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $consumedstockId = $this->input->post('id');
    $where = ' AND ct.consumed_stock_id='.$consumedstockId;
    $data = $this->mp->getConsumedStockDetail($where);
    $data2 = unserialize($data[0]->json_data);
    $productArr = [];
    $total_price = 0;
    foreach($data as $v){
      $productArr[$v->category_name][] = $v;
      $total_price += $v->price*$v->quantity;
    }

     //echo '<pre>'; print_r($productArr); die;

    if(!empty($productArr)){
        $returnArr = '
        <div class="animated fadeIn" id="stock_form">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="store_vendor_invoice" enctype="multipart/form-data" id="store_vendor_invoice" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="abc" class="sip-table full-h" role="grid">
        <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
        <thead>
        <tr>
          <th>Item No.</th>
          <th>Description</th>
          <th>Unit</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Total Price</th>
          </tr>
        </thead><tbody>';

            foreach($productArr as $category => $products){
              $returnArr .= '<tr class="child_parent_row">
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                </tr>';

                foreach($products as $product){
                  $returnArr .= '<tr class="child_row">';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->item_no.'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($product->product_name).'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($product->unit).'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->quantity.'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->price.'</td>';
                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->price*$product->quantity.'</td>';
                  $returnArr .= '</tr>';
                }
            }            
        $returnArr .= '<tr class="child_row">';  
        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align:center" colspan="5">Total Amount</td>';                
        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align:left">'.$total_price.'</td>';
    }
    $returnArr .= '</tbody></table></div>
    </div>
    </div>
</form>
</div>';
  
    echo json_encode(array('data'=>$returnArr,'status' => 100));

}

  function viewConsumedStockDetailsNew(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $consumedstockId = $this->input->post('id');
    $where = ' AND ct.consumed_stock_id='.$consumedstockId;
    $data = (array) $this->mp->getConsumedStockDetail($where);    
    $dataArr = unserialize($data[0]->json_data);
    $productArr = array();
    $a = []; 
     if(!empty($dataArr)){
         for ($i=0; $i <count($dataArr) ; $i++) { 
              $a[$dataArr[$i]['product_id']] = $dataArr[$i];
          // $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$dataArr[$i]['product_id']);
          // $productArr[$product['sequence']][$product['category_name']][] = array('category_name'=>$product['category_name'],'product_category_id'=>$product['product_category_id'],'product_name'=>$product['product_name'],'product_id'=>$product['product_id'],'quantity'=>$dataArr[$i]['quantity'],'value'=>$dataArr[$i]['value'],'unit_price'=>$dataArr[$i]['unit_price'],'unit'=>$product['unit'],'item_no'=>$product['item_no'],'sequence'=>$product['sequence']); 
      }

       if($a){
         $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
            foreach ($products as $row) {
               $productArr[$row->sequence][$row->category_name][] = array(
                'category_name'=>$row->category_name,
                'product_category_id'=>$row->product_category_id,
                'product_name'=>$row->product_name,
                'product_id'=>$row->product_id,
                'quantity'=>$a[$row->product_id]['quantity'],
                'value'=>$a[$row->product_id]['value'],
                'unit_price'=>$a[$row->product_id]['unit_price'],
                'unit'=>$row->unit,
                'item_no'=>$row->item_no,
                'sequence'=>$row->sequence
             ); 
            } 
        }
       } 

        ksort($productArr);
        //print_r($productArr);die;
        $total_price = 0;
        if(!empty($productArr)){
            $returnArr = '
            <div class="animated fadeIn b-p-15" id="stock_form">
            <div class="row">
            <div class="col-md-12">
            <form class="form-horizontal form-bordered" name="store_vendor_invoice" enctype="multipart/form-data" id="store_vendor_invoice" method="post">
                    <div class="no-padding rounded-bottom">
                    <div class="form-body no-padding">
                    <div id="abc" class="sip-table" role="grid">
            <table class="table header-fixed-new table-text-ellipsis table-layout-fixed">
            <thead class="t-header">
            <tr>
              <th width="15%">Item No.</th>
              <th width="25%">Description</th>
              <th width="15%">Unit</th>
              <th width="15%">Qty</th>
              <th width="15%">Unit Price($)</th>
              <th width="15%">Total Price($)</th>
              
              </tr>
            </thead><tbody>';

            foreach($productArr as $parent => $rows){
             foreach($rows as $category => $products){
                  $returnArr .= '<tr class="parent_row">
                    <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td  width="25%"role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                    <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    </tr>';
                    for ($i=0; $i <count($products) ; $i++) {             
                       $total_price = $total_price+($products[$i]['value']);
                          $returnArr .= '<tr class="child_row">';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.(($products[$i]['value']) ? number_format($products[$i]['value']/$products[$i]['quantity'],2) : '-' ).'</td>';
                          $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['value'],2).'</td>';
                          $returnArr .= '</tr>';
                    }                     
                }            
            }
            $returnArr .= '<tr class="child_row">';  
            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align:right; font-size:11px;" colspan="5"><strong>Total Amount</strong></td>';                
            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align:left; font-size:11px; font-weight:bold;">$'.number_format($total_price,2).'</td></tr>';
        }
        $returnArr .= '</tbody></table></div>
        </div>
        </div>
        </form>
        </div>';
    }
    echo json_encode(array('data'=>$returnArr,'status' => 100));
  }
 
 public function printPdf($company_invoice_id){
    require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
      $where = ' AND ci.company_invoice_id ='.$company_invoice_id;
    $data = (array) $this->mp->getCompanyInvoice($where);
     $json_data = unserialize($data['json_data']);
     $arrData = array(); 
     if(!empty($json_data)){
       for ($i=0; $i <count($json_data) ; $i++) { 
           $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
           $arrData[$product['sequence']][$product['category_name']][] = array(
             'category_name'=>$product['category_name'],
             'product_category_id'=>$product['product_category_id'],
             'product_name'=>$product['product_name'],
             'product_id'=>$product['product_id'],
             'unit'=>$product['unit'],
             'item_no'=>$product['item_no'],
             'sequence'=>$product['sequence'],
             'qty'=>$json_data[$i]['qty'],
             'unit_price'=> $json_data[$i]['unit_price'],
             'total_price'=>$json_data[$i]['price']
          );
        } 
     }
      if($data['currency'] == 1){
        $curr = 'EURO';
        $currSymbol = '€';
     }else if($data['currency'] == 2){
        $curr = 'USD';
        $currSymbol = '$';
     }else{
        $curr = 'SGD';
        $currSymbol = 'S$';
     }
       $dataArr['payment_term'] = $data['payment_term'];     
       $dataArr['customer_id'] = $data['customer_id'];
       $dataArr['ship_name'] = $data['ship_name'];
       $dataArr['imo_no'] = $data['imo_no'];
       $dataArr['company_name'] = $data['company_name'];     
       $dataArr['company_address'] = $data['company_address'];
       $dataArr['invoice_no'] = $data['invoice_no'];
       $dataArr['reqsn_date'] = $data['reqsn_date'];     
       $dataArr['delivery_port'] = $data['delivery_port'];
       $dataArr['po_no'] = $data['po_no'];
       $dataArr['currency'] = $curr;
       $dataArr['company_invoice_id'] = $company_invoice_id;
       $dataArr['invoice_date'] = $data['invoice_date'];
       $dataArr['invoice_discount'] = $data['invoice_discount'];
       $dataArr['requisition_type'] = $data['requisition_type']; 
       $dataArr['reason'] = $data['reason']; 
       $dataArr['type'] = 'print';  
       $vars['dataArr'] = $dataArr; 
       $vars['arrData'] = $arrData; 
        $vars['data'] = $data;
        $vars['view_file'] = 'print_invoice';
        $vars['title'] = 'Invoice';
        $this->load->view('downloadPdf',$vars);
    }

  function add_extra_meals(){
    checkUserSession();
    $user_session_data = getSessionData();
    $actionType = $this->input->post('actionType');
    $returnArr['status'] = 100;

    $report_config = getCustomSession('report_config');
    $ship_id = ($report_config['id']) ? $report_config['id'] : $report_config['ship_id'];

    $extra_meal_id = $this->input->post('id');
    
    if(!empty($extra_meal_id)){
     $data = $this->cm->getExtraMealDetails(' AND em.extra_meal_id='.$extra_meal_id);
     $month = $data[0]->month;
     $year = $data[0]->year;     
    }
    else{
      $month = $report_config['month'];   
      $year = $report_config['year'];   
    }

    $vars['totalDays'] = $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    if($actionType=='save'){
     if($this->extra_meals_validation()){  
      $dataArr['full_compliment'] = trim($this->input->post('full_compliment'));
      $dataArr['extra_meals'] = trim($this->input->post('extra_meals'));
      $dataArr['total_man_days'] = trim($this->input->post('total_man_days'));
      $dataArr['master'] = trim($this->input->post('master'));
      if(empty($extra_meal_id)){
        $dataArr['ship_id'] = $ship_id;
        $dataArr['year'] = $year;
        $dataArr['month'] = $month; 
        $dataArr['added_on'] = date('Y-m-d H:i:s');
        $dataArr['added_by'] = $user_session_data->user_id;
        $returnArr['returnMsg'] = 'Extra meals details added successfully.';  
        //for ($p=0; $p < 500; $p++) { 
        $extra_meal_id = $this->cm->add_extra_meals($dataArr);
      }
      else{
          $dataArr['updated_on'] = date('Y-m-d H:i:s');
          $dataArr['updated_by'] = $user_session_data->user_id;
          $this->cm->edit_extra_meals($dataArr,array('extra_meal_id'=>$extra_meal_id));
          $returnArr['returnMsg'] = 'Extra meals details updated successfully.';  
      } 

      $this->db->delete('extra_meal_details',array('extra_meal_id'=>$extra_meal_id));
       $batch = array();
        $k =0;
        for ($i=0; $i < $totalDays; $i++) { 
          $k++;
          if(!empty($this->input->post('ship_port['.$k.']')) && (!empty($this->input->post('ship_crew['.$k.']')))){
           $batch[] = array(
               'extra_meal_id' => $extra_meal_id,
               'day' => $k,
               'ship_port' => trim($this->input->post('ship_port['.$k.']')),
               'ship_crew' => trim($this->input->post('ship_crew['.$k.']')), 
               'sing_b' => trim($this->input->post('sing_b['.$k.']')),   
               'sing_l' => trim($this->input->post('sing_l['.$k.']')),   
               'sing_d' => trim($this->input->post('sing_d['.$k.']')),   
               'numery_b' => trim($this->input->post('numery_b['.$k.']')),   
               'numery_l' => trim($this->input->post('numery_l['.$k.']')),   
               'numery_d' => trim($this->input->post('numery_d['.$k.']')),    
               'officials_b' => trim($this->input->post('officials_b['.$k.']')),    
               'officials_l' => trim($this->input->post('officials_l['.$k.']')),    
               'officials_d' => trim($this->input->post('officials_d['.$k.']')),    
               'superintendent_b' => trim($this->input->post('superintendent_b['.$k.']')),    
               'superintendent_l' => trim($this->input->post('superintendent_l['.$k.']')),    
               'superintendent_d' => trim($this->input->post('superintendent_d['.$k.']')),
               'owners_b' => trim($this->input->post('owners_b['.$k.']')), 
               'owners_l' => trim($this->input->post('owners_l['.$k.']')), 
               'owners_d' => trim($this->input->post('owners_d['.$k.']')), 
               'charterers_b' => trim($this->input->post('charterers_b['.$k.']')), 
               'charterers_l' => trim($this->input->post('charterers_l['.$k.']')), 
               'charterers_d' => trim($this->input->post('charterers_d['.$k.']')), 
               'other_b' => trim($this->input->post('other_b['.$k.']')), 
               'other_l' => trim($this->input->post('other_l['.$k.']')), 
               'other_d' => trim($this->input->post('other_d['.$k.']')), 
              ); 
           }
         }
        deleteCustomSession('report_config');
        $this->db->insert_batch('extra_meal_details',$batch);

        $returnArr['status'] = 200;
       }
     }
    
    if($actionType!='save'){
     $postArr = array(); 
      if(!empty($data)){
        $j = 0;
        foreach ($data as $key => $val) {
         $j++;
         $postArr['ship_port'][$j] = $val->ship_port;
         $postArr['ship_crew'][$j] = $val->ship_crew;
         $postArr['sing_b'][$j] = $val->sing_b;
         $postArr['sing_l'][$j] = $val->sing_l;
         $postArr['sing_d'][$j] = $val->sing_d;
         $postArr['numery_b'][$j] = $val->numery_b;
         $postArr['numery_l'][$j] = $val->numery_l;
         $postArr['numery_d'][$j] = $val->numery_d;
         $postArr['officials_b'][$j] = $val->officials_b;
         $postArr['officials_l'][$j] = $val->officials_l;
         $postArr['officials_d'][$j] = $val->officials_d;
         $postArr['superintendent_b'][$j] = $val->superintendent_b;
         $postArr['superintendent_l'][$j] = $val->superintendent_l;
         $postArr['superintendent_d'][$j] = $val->superintendent_d;
         $postArr['owners_b'][$j] = $val->owners_b;
         $postArr['owners_l'][$j] = $val->owners_l;
         $postArr['owners_d'][$j] = $val->owners_d;
         $postArr['charterers_b'][$j] = $val->charterers_b;
         $postArr['charterers_l'][$j] = $val->charterers_l;
         $postArr['charterers_d'][$j] = $val->charterers_d;
         $postArr['other_b'][$j] = $val->other_b;
         $postArr['other_l'][$j] = $val->other_l;
         $postArr['other_d'][$j] = $val->other_d;
        }

        $postArr['full_compliment'] = $data[0]->full_compliment;
        $postArr['extra_meals'] = $data[0]->extra_meals; 
        $postArr['total_man_days'] = $data[0]->total_man_days;
        $postArr['master'] = $data[0]->master; 
        // $postArr['is_submitted'] = $data[0]->is_submitted;
        $postArr['status'] = $data[0]->status;
         
      } 
     $vars['dataArr'] = $postArr;
     $vars['dataArr']['extra_meal_id'] = $data[0]->extra_meal_id; 
    }
    else{
      $vars['dataArr'] = $this->input->post();
      $vars['dataArr']['extra_meal_id'] = $extra_meal_id;
    }

    $data = $this->load->view('add_extra_meals',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  
  }

  function extra_meals_validation(){
     $month = date('m');   
     $year = date('Y');   
     $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
      $val = 0;
      $k = 0;
      for($i=0; $i <$totalDays ; $i++) { 
       $k++;
       if(!empty($this->input->post('ship_port['.$k.']')) && (!empty($this->input->post('ship_crew['.$k.']')))){
        $val++;
       }
      }
     
      if($val==0){
        $this->form_validation->set_rules('days','','trim|required',array('required'=>'Please enter at least one-day ship position and ship crew.'));
        return $this->form_validation->run();
      }
      else{
        return true;
      }
  }

 function downloadSampleXlsx($type='',$ship_id=''){
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = $type.'.xlsx';
     if($type=='consumed_stock'){
       $arrayHeaderData = $this->config->item('consumed_sample_fields');  
     }
     elseif($type=='add_stock'){
       $arrayHeaderData = $this->config->item('opening_sample_fields');    
     }else{
       $arrayHeaderData = $this->config->item('rfq_sample_fields');      
     }

      $listColumn = array();
      $arrayData  = array();
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
        if($type == 'add_stock'){
          $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                      ) 
            ),'cellArray'=>array('A7:G7'));
          $arrayData[2] = array('','One North Ships');
          $arrayData[7] = $arrayHeaderData;
        }
        else{
          $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                      ) 
            ),'cellArray'=>array('A7:F7'));
          $arrayData[2] = array('','One North Ships');
          $arrayData[7] = $arrayHeaderData;
        }

        if($type=='consumed_stock'){ 
            $exl_title = 'Consumed Stock';           
        }
        elseif($type=='add_stock'){
            $exl_title = 'Add Stock';
        }
        else{ 
            $exl_title = 'RFQ';
        }
        
        if($type=='rfq'){
            $dataArr = $this->cm->monthly_stock_details(' AND ms.ship_id ='.$ship_id.' AND ms.month = '.date('m').' AND ms.year ='.date('Y'));
             $stock_used = array();
                if(!empty($dataArr)){
                   foreach ($dataArr as $dt) {
                      $stock_used[$dt->product_id] = array('total_stock'=>$dt->total_stock,'used_stock'=>$dt->used_stock,'unit_price'=>$dt->unit_price);   
                   }  
                } 
            $products_category = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);

            $k=7;
            if(!empty($products_category)){
                foreach ($products_category as $row) {
                    $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_category_id = '.$row->product_category_id,'R');
                    $k++;  
                    $arrayData[] = array('',$row->category_name); 
                    if(!empty($products)){
                        foreach($products as $p){
                           $k++;                           
                            $arrayData[] = array($p->item_no,$p->product_name,$p->unit,($stock_used[$p->product_id]['total_stock'] - $stock_used[$p->product_id]['used_stock']),'',''); 
                        }
                    }
                } 
             }
         $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:G'.$k,'border'=>'THIN'))
             );      
        }else{
        $k=7;
        $products_category = $this->mp->getAllProductCategoryNew('And pc.status=1 AND swc.ship_id = '.$ship_id);
        if(!empty($products_category)){
            foreach ($products_category as $row) {
                $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_category_id = '.$row->product_category_id,'R');  
                 $k++;
                $arrayData[] = array('',$row->category_name); 
                if(!empty($products)){
                    foreach($products as $p){
                    $k++;
                        $arrayData[] = array($p->item_no,$p->product_name,$p->unit,'',''); 
                    }
                }
            } 
         }
        $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) );   
       } 
        
     $arrayBundleData['listColumn'] = $listColumn;
     $arrayBundleData['arrayData'] = $arrayData;
     $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$exl_title);
     readfile(FCPATH.'uploads/sheets/'.$fileName);
     unlink(FCPATH.'uploads/sheets/'.$fileName);
     exit;  
  }

  function downloadCustomProductSampleXlsx($type=''){
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = 'CustomRFQ.xlsx';
     $arrayHeaderData = array('Description','Stock Unit','Last Count QTY','Order QTY','Remark');

      $listColumn = array();
      $arrayData  = array();
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
                      ) 
            ),'cellArray'=>array('A7:E7'));
          $arrayData[2] = array('','One North Ships');
          $arrayData[7] = $arrayHeaderData;
        // $arrayData   = array();
        // array_shift($arrayHeaderData);
        // $arrayData[1] = $arrayHeaderData;
        
        $arrayData[] = array('PORK BELLIES','KG','10','','R1');   
        

        $arrayBundleData['listColumn'] = $listColumn;
        $arrayBundleData['arrayData'] = $arrayData;

        $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E2','border'=>'THIN')) );   


    $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Custom RFQ');
    readfile(FCPATH.'uploads/sheets/'.$fileName);
    unlink(FCPATH.'uploads/sheets/'.$fileName);
    exit;  
  }

  function printDeliveryReceiptPdf($delivery_note_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getDeliveyReceipt(' AND dn.delivery_note_id ='.$delivery_note_id);
        $vars['dataArr'] = $data;
       $recept_data = unserialize($data['json_data']);
       $rData = array();
       if(!empty($recept_data)){
        for ($j=0; $j <count($recept_data) ; $j++) { 
             $product_id = $recept_data[$j]['product_id'];
             $rData[$product_id] = $recept_data[$j];
           }   
       }
       $json_data = unserialize($data['line_data']);
       $productArr = array();
       if(!empty($json_data)){
           for ($i=0; $i <count($json_data) ; $i++) { 
               $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
               $product_id = $product['product_id'];
               $productArr[$product['sequence']][$product['category_name']][] = array(
                 'category_name'=>$product['category_name'],
                 'product_category_id'=>$product['product_category_id'],
                 'product_name'=>$product['product_name'],
                 'product_id'=>$product_id,
                 'unit'=>$product['unit'],
                 'item_no'=>$product['item_no'],
                 'sequence'=>$product['sequence'],
                 'quantity'=>$json_data[$i]['quantity'],
                 'unit_price'=> $json_data[$i]['unit_price'],
                 'type' => $rData[$product_id]['type'],
                 'img_url'=> $rData[$product_id]['img_url'],
                 'supply_qty'=> $rData[$product_id]['supply_qty'],
                 'comment'=> $rData[$product_id]['comment']
              );   
            } 
         } 
        $vars['productArr'] = $productArr;
        $vars['view_file'] = 'delivery_note_pdf';
        $vars['title'] = 'Delivery Note Receipt';
        $this->load->view('downloadPdf',$vars);
  }


  function printPurchaseOrderPdf($work_order_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getWorkOrderByID(' AND wo.work_order_id ='.$work_order_id);
        // echo $this->db->last_query();die;
        // print_r($data);die;
        $vars['data'] = $data;
        $vars['view_file'] = 'purchase_order_pdf';
        $vars['title'] = 'Purchase Order';
        $this->load->view('downloadPdf',$vars);
  }

  function printPurchaseOrderDetailedPdf($work_order_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
       $data = (array) $this->cm->getWorkOrderByID(' AND wo.work_order_id ='.$work_order_id);
       $json_data = unserialize($data['json_data']);
       $productArr = array();
       if(!empty($json_data)){
         $total_price = 0;
           for ($i=0; $i <count($json_data) ; $i++) { 
            $total_price += ($json_data[$i]['unit_price'] * $json_data[$i]['qty']);
               $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
               $product_id = $product['product_id'];
               $productArr[$product['sequence']][] = array(
                 'category_name'=>$product['category_name'],
                 'product_category_id'=>$product['product_category_id'],
                 'product_name'=>$product['product_name'],
                 'product_id'=>$product_id,
                 'unit'=>$product['unit'],
                 'item_no'=>$product['item_no'],
                 'sequence'=>$product['sequence'],
                 'quantity'=>$json_data[$i]['qty'],
                 'unit_price'=> $json_data[$i]['unit_price'],
              );   
            } 
         } 
        ksort($productArr); 
        $vars['productArr'] = $productArr; 
        $vars['data'] = $data;
        $vars['data']['total_price'] = $total_price;
        $vars['view_file'] = 'purchase_order_detailed_pdf';
        $vars['title'] = 'Detailed PO';
        $this->load->view('downloadPdf',$vars);
  }
  
  function deletePort(){
        $id = $this->input->post('id');
        $where = 'port_id ='.$id;
        $result = $this->cm->deleteCompany('ship_ports',$where);
        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'Port Deleted successfully.';
        // $this->session->set_flashdata('succMsg','Port Deleted successfully.');
        echo json_encode($returnArr);
    }

  function groupDeletePort(){
        $ids=trim($_POST['ids']);
        $ids=base64_decode($ids);print_r($ids);die;
        $ids=explode(',',$ids);
        $status=trim($_POST['status']);
        $dataArr = array('status' => $status);
        foreach ($ids as $id) {
            $where = 'port_id ='.$id;
            $this->cm->deleteCompany('ship_ports',$where);
            echo $this->db->last_query();
        }
        $this->session->set_flashdata('succMsg','Status has been Update Successfully');
        echo '1';
}

   function changePOStatus(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $workOrderId = $this->input->post('id');
    $actionType = $this->input->post('actionType');
    $where = ' AND w.work_order_id= '.$workOrderId;
    $data = (array) $this->cm->getWorkDetailsByID($where);
    $selected1 = $selected2 = $selected3 = 'disabled'; 
     
    if($data['status']==1){
         $selected1 = 'checked';
         $selected2 = $selected3 = 'disabled';
         $step1class = 'done';
    }
    elseif($data['status']==2){
         $step1class = $step2class =  'done';
         $msg = '<span style="color:red">You are not able to manually update the Stage. Please create delivery note first, then the stage will automatically update</span>';
    }
    elseif($data['status']==3){
         $step1class = $step2class = $step3class =  'done';
          $msg = '<span style="color:red">You are not able to manually update the Stage. Please contact Vendor.</span>';
    }
    elseif($data['status']==4){
         $step1class = $step2class = $step3class = $step4class =  'done';
         $selected1 = $selected3 = 'disabled';
         $selected2 = 'checked';
    }
    elseif($data['status']==5){
         $step1class = $step2class = $step3class = $step4class = $step5class =  'done';
         $selected1 = $selected2 = 'checked disabled';
         $selected3 = 'checked';   
    } 

    if(!empty($workOrderId)){
        $returnArr = '
        <div class="animated fadeIn">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="update_work_order_status" enctype="multipart/form-data" id="update_work_order_status" method="post">
        <div class="no-padding rounded-bottom">';
//         $returnArr .= '<strong>NOTE: Please make sure before updating the PO Stage.
// Once the stage is updated you will not be able to go to the previous stage.</strong>
//         ';
        $returnArr .= '<div class="form-body">
                        <div class="row1">
                        <div class="form-group col-sm-12">
                        <label class="col-sm-12">PO Stage:</label>
                        <div class="col-sm-12"><div class="popup-progressbar">
                        <label class="radio-inline '.$step1class.'">
                        <input type="radio" name="po_status" '.$selected1.' value="1" ><span>Raised</span></label>
                        <label class="radio-inline '.$step2class.'">
                        <input type="radio" name="po_status" '.$selected1.' value="2" ><span>Accepted by Vendor</span></label>
                        <label class="radio-inline '.$step3class.'">
                        <input type="radio" name="po_status" value="3" disabled><span>DN Created</span></label>
                        <label class="radio-inline '.$step4class.'">
                        <input type="radio" name="po_status" value="4" disabled ><span>Vendor Invoice Uploaded</span></label>';
              // $returnArr .= '<label class="radio-inline '.$step5class.'">
              //           <input type="radio" name="po_status" value="5" '.$selected2.'><span>Delivered</span></label>
              //           <label class="radio-inline '.$step6class.'">
              //           <input type="radio" name="po_status" value="6" '.$selected3.'><span>Paid</span></label>';
                   $returnArr .='</div>
                        </div>'.$msg.'</div></div>
         <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="work_order_id" id="work_order_id" value="'.$workOrderId.'">    
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" class="btn btn-success btn-slideright mr-5" onclick=submitAjax360Form("update_work_order_status","shipping/updatePOStatus","","work_order")>Update Stage</button>
           </div>
         </div>';
  }
 echo json_encode(array('data'=>$returnArr,'status' => 100));
}

function updatePOStatus(){
  checkUserSession();
  $user_session_data = getSessionData();
  $po_status = $this->input->post('po_status');
  $actionType = $this->input->post('actionType');
  $work_order_id = $this->input->post('work_order_id');
  $this->load->model('email_manager');
  $this->em = $this->email_manager;
   if(!empty($work_order_id)){
     $where = ' AND w.work_order_id= '.$work_order_id;
     $data = (array) $this->cm->getWorkDetailsByID($where);
   }
   
  $returnArr['status'] = 100;
  if($actionType == 'save'){
    if(!empty($po_status)){
        $where = 'work_order_id = '.$work_order_id;
        $this->cm->changestatus('work_order',$po_status,$where); 

        if($user_session_data->code=='vendor' && $po_status==2){
            $whereEm = ' AND nt.code = "po_accepted"';
            $templateData = $this->um->getNotifyTemplateByCode($whereEm);
            if(!empty($templateData)){
             $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
             if(!empty($roles)){
               foreach ($roles as $row) {
                   $user_data = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_data)){
                     foreach ($user_data as $val) {
                       $noteArr['date'] = date('Y-m-d H:i:s');
                       $noteArr['user_id'] = $val->user_id;
                       $noteArr['title'] = $templateData->title;
                       $noteArr['row_id'] = $work_order_id;
                       $noteArr['ship_id'] = $data['ship_id'];
                       $noteArr['entity'] = 'purchase_order';
                       $noteArr['long_desc'] = str_replace(array('##po_no##','##ship_name##','##vendor_name##'),array($data['po_no'],ucwords($data['ship_name']),ucwords($user_session_data->first_name.' '.$user_session_data->last_name)),$templateData->body); 
                        $this->um->add_notify($noteArr);   
                     }
                   } 
                } 
             }
           }
        }

        $returnArr['returnMsg'] = 'Work Order Status updated successfully.'; 
        $returnArr['status'] = 200;
    }
    else{
         $returnArr['returnMsg'] = 'Something Went Wrong.'; 
        $returnArr['status'] = 200;   
    }
 }
 echo json_encode($returnArr);
}


 function cancelPO(){
     checkUserSession();
     $this->load->model('email_manager');
     $this->em = $this->email_manager;
     $user_session_data = getSessionData();
     $workOrderId = $this->input->post('id');
     $ship_order_id = $this->input->post('second_id');
     $actionType = $this->input->post('actionType');
     $confirmed = $this->input->post('confirmed');
     $returnArr['status'] = 100; 
      if(!empty($workOrderId)){
        $where = ' AND w.work_order_id= '.$workOrderId;
        $data = (array) $this->cm->getWorkDetailsByID($where);
      }

     if($actionType=='save'){
       $this->form_validation->set_rules('cancel_remark','Remark','trim|required'); 
       if($this->form_validation->run()){
         $status = trim($this->input->post('status'));
          if($confirmed==1){
           $remark = trim($this->input->post('cancel_remark'));
           if($status==5){
             $this->db->update('ship_order',array('status'=>2,'lead_time'=>null),array('ship_order_id'=>$ship_order_id));
             $this->cm->deleteVendorQuoteDetails($ship_order_id);
             $this->db->delete('vendor_quotation',array('ship_order_id'=>$ship_order_id));
             $this->db->delete('vendor_quote_approvals',array('ship_order_id'=>$ship_order_id));
           }
           else{
             $this->db->update('ship_order',array('lead_time'=>null),array('ship_order_id'=>$ship_order_id));
           }
           $this->db->update('work_order',array('status'=>$status,'remark'=>$remark),array('work_order_id'=>$workOrderId));
           $this->db->delete('delivery_note',array('work_order_id'=>$workOrderId)); $returnArr['status'] = 200;
           $returnArr['returnMsg'] = 'Work Order Status updated successfully.';

          $whereEm = ' AND em.template_code = "po_cancel"';
          $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
          if($emailTemplateData){
            $subject = str_replace(array('##ship_name##','##po_no##','##imo_no##','##delivery_port##','##delivery_date##','##req_type##'),array(ucwords($data['ship_name']),$data['po_no'],$data['imo_no'],ucwords($data['delivery_port']),convertDate($data['delivery_date'],'','d-m-Y'),strtoupper(str_replace('_',' ',$data['requisition_type']))),$emailTemplateData->email_subject);
            $body =  str_replace(array('##vendor_name##','##po_no##'),array(ucwords($data['vendor_name']),$data['po_no']),$emailTemplateData->email_body);

             if(!empty($data['vendor_user_id'])){
              $emArr['user_id'] = $data['vendor_user_id'];
              $emArr['subject'] = $subject;
              $emArr['body'] = $body;
              $this->em->add_email_log($emArr);
             }

             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
              if(!empty($email_roles)){
                foreach ($email_roles as $row) {
                  $user_list = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_list)){
                       foreach ($user_list as $val) {
                        $emArr['user_id'] = $val->user_id;
                        $emArr['subject'] = $subject;
                        $emArr['body'] = $body;
                        $this->em->add_email_log($emArr);
                       }
                   } 
                 }
              }

          } 

          $whereN = ' AND nt.code = "po_cancel"';
          $templateData = $this->um->getNotifyTemplateByCode($whereN);
           $notArr['date'] = date('Y-m-d H:i:s');
           $notArr['user_id'] = $data['vendor_user_id'];
           $notArr['title'] = $templateData->title;
           $notArr['long_desc'] = str_replace(array('##po_no##'),array($data['po_no']),$templateData->body); 
            $this->um->add_notify($notArr);  
          if(!empty($templateData)){
             $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
             if(!empty($roles)){
               foreach ($roles as $row) {
                   $user_data = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_data)){
                     foreach ($user_data as $val) {
                       $noteArr['date'] = date('Y-m-d H:i:s');
                       $noteArr['user_id'] = $val->user_id;
                       $noteArr['title'] = $templateData->title;
                       $noteArr['long_desc'] = str_replace(array('##po_no##'),array($data['po_no']),$templateData->body); 
                        $this->um->add_notify($noteArr);   
                     }
                   } 
                } 
             }

            }
         }
         else{
           $returnArr['status'] = 300; 
         } 
       }
     } 
   $returnArr['code'] =  $status;
   $vars['ship_order_id'] = $ship_order_id;
   $vars['work_order_id'] = $workOrderId;
   $data = $this->load->view('cancel_po',$vars,true);
   $returnArr['data'] = $data;
   echo json_encode($returnArr);
 }

function changeInvoiceStatusCustom(){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('email_manager');
    $this->em = $this->email_manager;
    $invoiceId = $this->input->post('id');
    $actionType = $this->input->post('actionType');
    $inv_status = $this->input->post('inv_status');
    $invoice_remark = $this->input->post('invoice_remark');
    if($invoiceId){
       $where = ' AND inv.company_invoice_id='.$invoiceId;
       $data = (array) $this->cm->getInvoiceDetailById($where);
    }
    $returnArr['status'] = 100;
    if($actionType == 'save'){
     $this->form_validation->set_rules('inv_status','Status','trim|required');
     if($inv_status=='Resolved'){
       $this->form_validation->set_rules('img','','callback_cnc_document');
     }
      if($this->form_validation->run()){
          $where = 'company_invoice_id = '.$invoiceId;
          $dataArr['status'] = $inv_status; 
              if(!empty($_FILES['img']['name'])){
               $file_name = $_FILES['img']['name'];
               $upload_data = doc_upload($file_name,'cn_document');
               $dataArr['document_url'] = $upload_data['file_name'];
              } 
          $dataArr['invoice_remark'] = $invoice_remark;  
          $this->cm->edit_invoice($dataArr,$where); 
          $returnArr['returnMsg'] = 'Invoice Status updated successfully.'; 
          $returnArr['status'] = 200;
      if($inv_status=='Resolved'){
        $whereEm = ' AND nt.code = "resolved_invoice"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);  
      }
      else{
       $whereEm = ' AND nt.code = "incorrect_invoice"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);  
      }

      if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##invoice_no##','##ship_name##'),array($data['invoice_no'],ucwords($data['ship_name'])),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }
     }

        }    
        $data ='<div class="animated fadeIn" id="stock_form">
        <form class="form-horizontal form-bordered" name="update_invoice_status" enctype="multipart/form-data" id="update_invoice_status" method="post">
        <div class="no-padding rounded-bottom">';
        $postSt = $this->input->post('inv_status');
        $data .= '<div class="form-body">
                        <div class="row">
                        <div class="form-group col-sm-12">
                        <label>Invoice Status <span>*</span> </label><div>
                         <label class="radio-inline">
                          <input type="radio" name="inv_status" '.(($postSt=='Incorrect Invoice') ? ' checked' : '').' value="Incorrect Invoice" >Incorrect invoice
                          </label>
                         <label class="radio-inline">
                         <input type="radio" name="inv_status" '.(($postSt=='Resolved') ? ' checked' : '').' value="Resolved" >Resolved
                         </label>
                         '.form_error('inv_status','<p class="error">','</p>').'
                           </div><br/>
                           </div>
                           <div class="form-group col-sm-12">
                              <div>
                              <label>CN Document <span id="mandate"></span></label>
                               <div>
                                  <input type="file" name="img" id="img">                               
                               </div>
                               '.form_error('img','<p class="error1">','</p>').'
                             </div>                             
                              </div>
                              <div class="form-group col-sm-12">
                              <label>Remark</label>
                              <div>
                              <textarea style="height:80px" name="invoice_remark" class="form-control" >'.$this->input->post('invoice_remark').'</textarea>
                              </div>
                              </div>
                              </div>
                              </div>
         <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="id" id="invoice_id" value="'.$invoiceId.'">    
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" class="btn btn-success btn-slideright mr-5" onclick=submitAjax360Form("update_invoice_status","shipping/changeInvoiceStatusCustom","","invoice_list")>Save</button>
           </div>
         </div>
         <script>
            $(document).ready(function(){
                $(\'input[name="inv_status"]\').click(function(){
                    var val = $(this).val();
                    if(val=="Resolved"){
                     $("#mandate").html("*");   
                    }
                    else{
                     $("#mandate").html("");      
                    }
                })
             }) 
         </script>';
  
  $returnArr['data'] = $data;
  echo json_encode($returnArr);
}

 function cnc_document(){
    $allowed_mime_type_arr = array('application/pdf');
     $mime = get_mime_by_extension($_FILES['img']['name']);
        if(isset($_FILES['img']['name']) && $_FILES['img']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('cnc_document', 'Please choose only pdf file.');              
                return false;
            }
        }else{
            $this->form_validation->set_message('cnc_document', 'Please choose a file to upload.');
            return false;
        }
  }

  function viewInvoiceRemark(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $company_invoice_id = $this->input->post('id');
    $where = ' AND inv.company_invoice_id='.$company_invoice_id;
    $data = (array) $this->cm->getInvoiceDetailById($where);
    if(!empty($data)){
        $returnArr = '
        <div class="animated fadeIn" id="stock_form">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="store_vendor_invoice" enctype="multipart/form-data" id="store_vendor_invoice" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="abc" class="sip-table full-h" role="grid">';
                if(!empty($data['invoice_remark']) || !empty($data['document_url'])){
                 $returnArr .= '<p>'.$data['invoice_remark'].'</p><br/>';
                 $returnArr .= '<a target="_blank" href="'.base_url().'uploads/cn_document/'.$data['document_url'].'">'.$data['document_url'].'</a>'; 
                }
                else{
                $returnArr .= '<p>No Document Available</p>';
                }
    $returnArr .= '</div>
    </div>
    </div>
    </form>
     </div></div></div>';
   } 
    echo json_encode(array('data'=>$returnArr,'status' => 100));
}


 function send_to_master(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    $ship_order_id = $this->input->post('id');
    $data = (array) $this->cm->getQuotedDetailsForMaster(' AND so.ship_order_id ='.$ship_order_id);
    $ship_details = getCustomSession('ship_details');
    $second_id = $this->input->post('second_id');
    $vendor_quote_id = $data['vendor_quote_id'];
    $arrData = unserialize($data['ship_order_json']);
    $venData = unserialize($data['json_data']);
    
    $a= [];


    $rfq_data = array();    
    if(!empty($arrData)){
          for ($j=0; $j < count($arrData); $j++) { 
              $rfq_data[$arrData[$j]['product_id']] = array(
                'order_qty'=>$arrData[$j]['quantity'],
                'order_remark'=>$arrData[$j]['remark']
            );
          }
       }

   $dataArr = $this->cm->get_current_stock(' AND cs.ship_id ='.$ship_details['ship_id']);
     $stock_used = array();
        if(!empty($dataArr)){
           foreach ($dataArr as $dt) {
              $stock_used[$dt->product_id] = array('total_stock'=>$dt->total_stock,'used_stock'=>$dt->used_stock,'unit_price'=>$dt->unit_price,'available_stock'=>$dt->available_stock);   
           }  
    }      

      $productArr = [];
      if(!empty($venData)){
         for ($i=0; $i <count($venData) ; $i++) {
            $a[$venData[$i]['product_id']] = $venData[$i];          
          }
      }

     if(!empty($a)){
        $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.implode(',',array_keys($a)).')','R');
        if(!empty($products)){
            foreach ($products as $row) {
               $productArr[$row->sequence][$row->category_name][] = array(
                'category_name'=>$row->category_name,
                'product_category_id'=>$row->product_category_id,
                'product_name'=>$row->product_name,
                'product_id'=>$row->product_id,
                'order_qty'=>$rfq_data[$row->product_id]['order_qty'],
                'order_remark'=>$rfq_data[$row->product_id]['order_remark'],
                'unit'=>$row->unit,
                'item_no'=>$row->item_no,
                'sequence'=>$row->sequence,
                'vendor_qty'=>($a[$row->product_id]['revised_qty']) ? $a[$row->product_id]['revised_qty'] : $a[$row->product_id]['quantity'],
                'unit_price'=>$a[$row->product_id]['unit_price'],
                'vendor_remark'=>$a[$row->product_id]['remark'],
                'attechment'=>$a[$row->product_id]['attechment'],
                'group_name'=>$row->group_name
             ); 
            } 
        }
     } 



    if($actionType=='save'){
      $inc_price = ($this->input->post('inc_price')) ? trim($this->input->post('inc_price')) : null;
      $dec_price = ($this->input->post('dec_price')) ? trim($this->input->post('dec_price')) : null;
      $price_remark = trim($this->input->post('price_remark'));
      
      if(!empty($inc_price) || !empty($dec_price)){
         $this->form_validation->set_rules('price_remark','Price Remark','trim|required');
      }


      if($data['requisition_type']=='provision'){
        $this->form_validation->set_rules('no_of_day','No Of Days','trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('no_of_people','No Of People','trim|required|is_natural_no_zero');
      }
        
      if(!empty($venData)){
         for ($i=0; $i <count($venData) ; $i++) {
            $this->form_validation->set_rules('revised_qty_'.$venData[$i]['product_id'],'QTY','trim|required');
           // $this->form_validation->set_rules('company_unit_price_'.$arrData[$i]['product_id'],'Unit Price','trim|required');
         }
      }

      if($this->form_validation->run()){  
        $json_data = array();
         $this->db->delete('vendor_quotation_details',' vendor_quote_id ='.$vendor_quote_id); 

         $no_of_day = trim($this->input->post('no_of_day'));
         $no_of_people = trim($this->input->post('no_of_people'));
         $lead_time = trim($this->input->post('lead_time'));
         
        for ($i=0; $i <count($venData); $i++) { 
          $revised_qty = trim($this->input->post('revised_qty_'.$venData[$i]['product_id']));
          // $unit_price = trim($this->input->post('unit_price_'.$venData[$i]['product_id'])); 
          $unit_price = $venData[$i]['unit_price']; 
          if(!empty($inc_price)){
            $inc_by = ($inc_price/100);
            $new_value = $unit_price*$inc_by;
            $unit_price = $unit_price+$new_value;
          }
          elseif(!empty($dec_price)){
           $dec_by = ($dec_price/100);
           $new_value = $unit_price*$dec_by;
           $unit_price = $unit_price-$new_value;
         }


          if(!empty($revised_qty)){
             $json_data[] = array('vendor_quote_id'=>$vendor_quote_id,'product_id'=>$venData[$i]['product_id'],'quantity'=>$venData[$i]['quantity'],'remark'=>$venData[$i]['remark'],'revised_qty'=>$revised_qty,'unit_price'=>$venData[$i]['unit_price'],'price'=>($venData[$i]['quantity']*$venData[$i]['unit_price']),'revised_unit_price'=>$unit_price,'attechment'=>$venData[$i]['attechment']); 
          }
        }

       $this->db->insert_batch('vendor_quotation_details',$json_data);
       $this->db->update('vendor_quotation',array('json_data'=>serialize($json_data),'inc_price'=>$inc_price,'dec_price'=>$dec_price,'price_remark'=>$price_remark),array('vendor_quote_id'=>$vendor_quote_id));

       // $this->db->update('ship_order',array('no_of_day'=>$no_of_day,'no_of_people'=>$no_of_people,'status'=>7,'is_send_review'=>1,'lead_time'=>$lead_time),array('ship_order_id'=>$ship_order_id));

      $this->db->update('ship_order',array('no_of_day'=>$no_of_day,'no_of_people'=>$no_of_people,'lead_time'=>$lead_time),array('ship_order_id'=>$ship_order_id));
       
       if($user_session_data->code=='super_admin'){
         $returnArr['returnMsg'] = 'Request send for reviewed successfully.';  
       }
       else{
         $returnArr['returnMsg'] = 'Request reviewed successfully.';  

       }

      $returnArr['status'] = 200;
     }
    }
    
    ksort($productArr);
    
    $vars['productArr'] = $productArr;  
    $vars['stock_used'] = $stock_used;
    
    if($actionType=='save'){
       $vars['dataArr'] = $this->input->post();
    }
    else{
        $vars['dataArr']['inc_price'] = $data['inc_price'];
        $vars['dataArr']['dec_price']= $data['dec_price'];
        $vars['dataArr']['price_remark']= $data['price_remark'];        
    }

    $vars['dataArr']['second_id'] = $second_id;
    $vars['dataArr']['ship_order_id'] = $data['ship_order_id'];
    $vars['dataArr']['no_of_day'] = $data['no_of_day']; 
    $vars['dataArr']['no_of_people'] = $data['no_of_people'];
    $vars['dataArr']['port_name'] = $data['port_name'];
    $vars['dataArr']['lead_time'] = $data['lead_time'];
    $vars['dataArr']['requisition_type'] = $data['requisition_type'];

    $vars['group_products'] = $this->mp->getAllProductGroup($where,'R');
    $data = $this->load->view('send_to_master',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);  
  }
  
  function extra_meals_list($ship_id){
     checkUserSession();
     $user_session_data = getSessionData();
     $ship_id = base64_decode($ship_id);
     $returnArr = '';
     if($ship_id){
      $vars['opening_stock'] = $this->cm->getShipStockById($ship_id);
     }
     $vars['ship_id'] = $ship_id;
     $data = $this->load->view('extra_meals_list',$vars,true);
     $returnArr = $data;  
     echo json_encode(array('data'=>$returnArr));  
  }

  function getAllExtraMealsList($type=''){
   checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    
    if(!empty($keyword)){
     $where .= " AND ( s.ship_name like '%".trim($keyword)."%' or u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";   
    }

    if(!empty($shipping_company_id)){
        $where .= ' AND s.shipping_company_id = '.$shipping_company_id; 
    }         

    if(!empty($ship_id)){
       $where .= ' AND em.ship_id = '.$ship_id; 
    }

     if($user_session_data->code=='captain' || $user_session_data->code=='cook'){
      $where .= ' AND em.ship_id = '.$user_session_data->ship_id; 
     } 
     elseif($user_session_data->code=='shipping_company'){
       $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id; 
     }


    if(!empty($month)){
        $where .= ' AND em.month = '.$month; 
    }
    if(!empty($year)){
        $where .= ' AND em.year = '.$year; 
    }

    if($status){
      if($status=='C'){
        $where .= ' AND em.status = 0'; 

      }
      elseif($status=='S'){
        $where .= ' AND em.status = 1'; 
      }
      elseif($status=='I'){
        $where .= ' AND em.status = 2'; 
      }  
    }


   if($created_on){
     $where .= ' AND date(em.added_on) = "'.convertDate($created_on,'','Y-m-d').'"'; 
   } 

   if(!empty($eMsort_column) && !empty($eMsort_type)){
    if($eMsort_column=='Month'){
      $order_by = ' ORDER BY em.month '.$eMsort_type;
    }
    elseif($eMsort_column=='Year'){
      $order_by = ' ORDER BY em.year '.$eMsort_type;
    }
    elseif($eMsort_column=='Added On'){
      $order_by = ' ORDER BY em.added_on '.$eMsort_type;
    }
    elseif($eMsort_column=='Added By'){
      $order_by = ' ORDER BY u.first_name '.$eMsort_type;
    }
    // elseif($eMsort_column=='Invoice'){
    //   $order_by = ' ORDER BY em.is_invoice_created '.$eMsort_type;
    // }
    elseif($eMsort_column=='Ship Name'){
      $order_by = ' ORDER BY s.ship_name '.$eMsort_type;
    }
    elseif($eMsort_column=='Status'){
      $order_by = ' ORDER BY em.status '.$eMsort_type;
    }
   }
   else{
    $order_by = ' ORDER BY em.extra_meal_id DESC';
   }


   if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllExtraMeal($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }
   
   if($download==1){
        $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'ExtraMeals';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

        $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Vessel Name','Month','Year','Created On','Created By','Status');
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
           $extra_meals = $this->cm->getAllExtraMeal($where,'R',$perPage,$offset,$order_by);
           if($extra_meals){
             foreach ($extra_meals as $row) {
                $k++;
                 $monthNum  = $row->month;
                 $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                 $monthName = $dateObj->format('F'); // March
                 // $statusC = ($row->is_submitted==1) ? 'Submitted' : 'Created';
                 if($row->status==0){
                   $statusC = 'Created';
                 }
                 elseif($row->status==1){
                   $statusC = 'Submitted';                    
                 }
                 elseif($row->status==2){
                   $statusC = 'Invoice Created';
                 }

                 $arrayData[] = array(ucwords($row->ship_name),ucfirst($monthName),$row->year,ConvertDate($row->added_on,'','d-m-Y | h:i A'),$row->user_name,$statusC);   
             }
           }
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:G'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   
   }

   $countdata = $this->cm->getAllExtraMeal($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
   $extra_meals = $this->cm->getAllExtraMeal($where,'R',$perPage,$offset,$order_by);

   $edit_extra_meal = checkLabelByTask('edit_extra_meals');
   $month_end_invoice = checkLabelByTask('month_end_invoice');
   $submit_extra_meal = checkLabelByTask('submit_extra_meal');
   $view_month_end_invoice = checkLabelByTask('view_month_end_invoice');
   if($extra_meals){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($extra_meals)).' of '.$countdata.' entries';
         foreach ($extra_meals as $row){   
            $monthNum  = $row->month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March
            $edit = $invoice ='';
          //   $edit = $label = $invoice = $status = '';
          //   $label = ($row->is_submitted==0) ? 'Edit' : 'View Details';
            // $statusC = ($row->is_submitted==1) ? '<span class="badge badge-warning">Submitted</span>' : '<span class="badge badge-success">Created</span>';

          if($row->status==0){
             $statusC = '<span class="badge badge-success">Created</span>';              
          }
          elseif($row->status==1){
           $statusC = '<span class="badge badge-warning">Submitted</span>';
          }
          elseif($row->status==2){
             $statusC = '<span class="badge badge-info">Invoice Created</span>';               
          }  

          if($row->status==0){
            if($edit_extra_meal){
              $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Extra Meals\',\'shipping/add_extra_meals\','.$row->extra_meal_id.',\'\',\'98%\',\'full-width-model\');" >Edit</a>';
            } 
            if($submit_extra_meal){
              $status = '<a onclick="update_em_status('.$row->extra_meal_id.')" style="cursor:pointer">Submit Report</a>';
            }   
          }
          elseif($row->status==1){
           // if($user_session_data->code=='super_admin'){
           //    $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Extra Meals\',\'shipping/add_extra_meals\','.$row->extra_meal_id.',\'\',\'98%\',\'full-width-model\');" >Edit</a>';
           // }
           // else{
           $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Extra Meals\',\'shipping/add_extra_meals\','.$row->extra_meal_id.',\'view_only\',\'98%\',\'full-width-model\');" >View Details</a>';
           // }

           if($month_end_invoice){
             $invoice = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Month End Invoice\',\'shipping/extra_meals_invoice\','.$row->extra_meal_id.',\'\',\'70%\',\'full-width-model\');">Create Invoice</a>';
           }
          }
          else{
             $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Extra Meals\',\'shipping/add_extra_meals\','.$row->extra_meal_id.',\'view_only\',\'98%\',\'full-width-model\');" >View Details</a>';
             if($view_month_end_invoice){
               $invoice = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Month End Invoice\',\'shipping/view_em_invoice\','.$row->extra_meal_id.',\'\',\'98%\',\'full-width-model\');">View Invoice</a>';
              }
          }
            
          //   if($edit_extra_meal){
          //     $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Extra Meals\',\'shipping/add_extra_meals\','.$row->extra_meal_id.',\'\',\'98%\',\'full-width-model\');" >'.$label.'</a>';
          //   }
            
          //   if($month_end_invoice){
          //     if(empty($row->is_invoice_created) && ($row->is_submitted==1)){
          //      $invoice = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Month End Invoice\',\'shipping/extra_meals_invoice\','.$row->extra_meal_id.',\'\',\'70%\',\'full-width-model\');">Create Invoice</a>';                
          //     }
          //   }   

          //   if(empty($row->is_submitted) && $edit_extra_meal){
          //     $status = '<a onclick="update_em_status('.$row->extra_meal_id.')" style="cursor:pointer">Submit Report</a>';
          //   }
            
          //   if($manage_extra_meals){ 
          //    if(empty($row->is_submitted)){
          //         $status = '<a onclick="update_em_status('.$row->extra_meal_id.')" style="cursor:pointer">Submit Report</a>';
          //    }

            
          // } 

          //  if(!empty($row->is_invoice_created)){
          //       $invoice = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Month End Invoice\',\'shipping/view_em_invoice\','.$row->extra_meal_id.',\'\',\'98%\',\'full-width-model\');">View Invoice</a>';                
          //   }

         

            $returnArr .= "<tr>";
                    if(!empty($type)){
                      $returnArr .= "<td width='10%'>".ucwords($row->ship_name)."</td>";  
                    }  

             $returnArr .="<td width='10%'>".ucfirst($monthName)."</td><td width='10%'>".$row->year."</td>
                              <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | h:i A')."</td>
                              <td width='10%'>".ucfirst($row->user_name)."</td>";
                              // <td width='10%'>".(($row->is_invoice_created==1) ? '<span class="badge badge-success">No</span>' : '<span class="badge badge-danger">No</span>')."</td>
                          $returnArr .= "<td width='10%'>".$statusC."</td>
                              ";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li>'.$status.'</li>
                                <li>'.$invoice.'</li>                      
                                </ul>
                                </div></td></td></tr>'; 
         }

         $pagination = $pages->get_links();
     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="7" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));    

  } 

  function update_em_status(){
    checkUserSession();
    $extra_meal_id = trim($this->input->post('id'));
     $returnArr['status'] = 100;
     $this->load->model('email_manager');
     $this->em = $this->email_manager;
     $ship_details = getCustomSession('ship_details');
    if(!empty($extra_meal_id)){
      // $this->db->update('extra_meals',array('is_submitted'=>1),array('extra_meal_id'=>$extra_meal_id));
      $this->db->update('extra_meals',array('status'=>1),array('extra_meal_id'=>$extra_meal_id));
      $returnArr['status'] =200;
      $returnArr['returnMsg'] = 'Report submitted successfully';
        $whereEm = ' AND nt.code = "meal_report_submit"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##ship_name##'),array(ucwords($ship_details['ship_name'])),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }  
    } 
   echo json_encode($returnArr); 
  }

  function import_crew_members(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] =100;
    $actionType = $this->input->post('actionType');
    $ship_id = $this->input->post('id');
    $ship_details = (array) $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R');
    $ship_name = $ship_details[0]->ship_name;
    $ship_imo = $ship_details[0]->imo_no;
    $type = $this->input->post('id');
    $validation = true;
     if($actionType=='save'){        
         $validation = false;
         $this->form_validation->set_rules('img','','callback_xlsx_file_check');  
         if($this->form_validation->run()){ 
           $validation = true;
           $file_name = $_FILES['img']['name'];
           $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            if(!empty($file_name)){
            $upload_data = doc_upload($file_name, 'sheets');
            $this->load->library('Excelreader');
            $excel = new Excelreader();
            $fileName = FCPATH.'uploads/sheets/'.$upload_data['file_name'];         
            $objWriter = $excel->readExcel($fileName,$ext);    
            $session_arr = array();
            $objWriter = array_values($objWriter);
            if($objWriter[2]['D'] != ''){
             
              $date_of_arrival_or_departure_new = $this->date_formating($objWriter[2]['D'],'d-m-Y').PHP_EOL;

            }else{
                $date_of_arrival_or_departure_new = '-';
            }

            // echo $date_of_arrival_or_departure_new;die; 
            
            $session_arr['arrival_or_departure'] = (!empty($objWriter[0]['F'])?$objWriter[0]['F']:'arrival');
             $session_arr['ship_id'] = $type;
             $session_arr['ship_name'] = $ship_name;
             $session_arr['ship_imo'] = $ship_imo;
             $session_arr['call_sign'] = $objWriter[1]['F'];
             $session_arr['voyage_number'] = $objWriter[1]['H'];
             $session_arr['port_of_arrival_or_departure'] = $objWriter[2]['B'];
             $session_arr['date_of_arrival_or_departure'] = $date_of_arrival_or_departure_new;
             $session_arr['flag_state_of_ship'] = $objWriter[2]['F'];
             $session_arr['last_port_of_call'] = $objWriter[2]['H'];
             //$session_arr['date_and_sign'] = $objWriter[2]['J'];
             $k=1;
              for ($i=4; $i <count($objWriter) ; $i++){
                if(!empty($objWriter[$i]['C']) && !empty($objWriter[$i]['D']) && !empty($objWriter[$i]['J'])){                  
                  
                  if($objWriter[$i]['F'] != ''){                      
                      $date_of_birth_new = $this->date_formating($objWriter[$i]['F'],'d-m-Y').PHP_EOL;

                  }else{
                    $date_of_birth_new ='-';
                  }
                  
                  if($objWriter[$i]['L'] != ''){
                      $expiry_date_of_identity_new = $this->date_formating($objWriter[$i]['L'],'d-m-Y').PHP_EOL;
                  }else{
                    $expiry_date_of_identity_new = '-';
                  }

                 $session_arr['crew'][$k] = array();
                 $session_arr['crew'][$k]['family_name'] = $objWriter[$i]['B'];
                 $session_arr['crew'][$k]['given_name'] = $objWriter[$i]['C'];
                 $session_arr['crew'][$k]['rank'] = $objWriter[$i]['D'];
                 $session_arr['crew'][$k]['nationality'] = $objWriter[$i]['E'];
                 $session_arr['crew'][$k]['date_of_birth'] = $date_of_birth_new;
                 $session_arr['crew'][$k]['place_of_birth'] = $objWriter[$i]['G'];
                 $session_arr['crew'][$k]['gender'] = $objWriter[$i]['H'];
                 $session_arr['crew'][$k]['nature_of_identity'] = $objWriter[$i]['I'];
                 $session_arr['crew'][$k]['number_of_identity'] = $objWriter[$i]['J'];
                 $session_arr['crew'][$k]['issuing_state_of_identity'] = $objWriter[$i]['K'];
                 $session_arr['crew'][$k]['expiry_date_of_identity'] = $expiry_date_of_identity_new;
                 $k++;
                }
              }

             //print_r($session_arr);die;
             setImportSession('crew_data',$session_arr);
             unlink($fileName); 
             $returnArr['status'] = 200;    
        }
     }
  }
        $vars['dataArr'] = $this->input->post();
        $data = $this->load->view('import_crew_members',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
}

function date_extract_format( $d, $null = '' ) {
    // check Day -> (0[1-9]|[1-2][0-9]|3[0-1])
    // check Month -> (0[1-9]|1[0-2])
    // check Year -> [0-9]{4} or \d{4}
    $patterns = array(
        '/\b\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.\d{3,8}Z\b/' => 'Y-m-d\TH:i:s.u\Z', // format DATE ISO 8601
        '/\b\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y-m-d',
        '/\b\d{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])\b/' => 'Y-d-m',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}\b/' => 'd-m-Y',
        '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{4}\b/' => 'm-d-Y',
        '/\b(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-\d{2}\b/' => 'm-d-y',

        '/\b\d{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\b/' => 'Y/d/m',
        '/\b\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y/m/d',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}\b/' => 'd/m/Y',
        '/\b(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/\d{4}\b/' => 'm/d/Y',

        '/\b\d{4}\.(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\b/' => 'Y.m.d',
        '/\b\d{4}\.(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\b/' => 'Y.d.m',
        '/\b(0[1-9]|[1-2][0-9]|3[0-1])\.(0[1-9]|1[0-2])\.\d{4}\b/' => 'd.m.Y',
        '/\b(0[1-9]|1[0-2])\.(0[1-9]|[1-2][0-9]|3[0-1])\.\d{4}\b/' => 'm.d.Y',

        // for 24-hour | hours seconds
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'H:i:s.u',
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'H:i:s',
        '/\b(?:2[0-3]|[01][0-9]):[0-5][0-9]\b/' => 'H:i',

        // for 12-hour | hours seconds
        '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\.\d{3,6}\b/' => 'h:i:s.u',
        '/\b(?:1[012]|0[0-9]):[0-5][0-9](:[0-5][0-9])\b/' => 'h:i:s',
        '/\b(?:1[012]|0[0-9]):[0-5][0-9]\b/' => 'h:i',

        '/\.\d{3}\b/' => '.v'
    );
    //$d = preg_replace('/\b\d{2}:\d{2}\b/', 'H:i',$d);
    $d = preg_replace( array_keys( $patterns ), array_values( $patterns ), $d );

    return preg_match( '/\d/', $d ) ? $null : $d;
}


function date_formating($date, $format = 'd/m/Y H:i', $in_format = false, $f = '' ) {
    $isformat = $this->date_extract_format($date);
    $d = DateTime::createFromFormat( $isformat, $date );
    $format = $in_format ? $isformat : $format;
    if ( $format ) {
        if (in_array($format, [ 'Y-m-d\TH:i:s.u\Z', 'DATE_ISO8601', 'ISO8601' ]))
         {
            $f = $d ? $d->format( 'Y-m-d\TH:i:s.' ) . substr( $d->format( 'u' ), 0, 3 ) . 'Z': '';
        } else {
            $f = $d ? $d->format($format) : '';
        }
    }
    return $f;
} // end function


  function downloadCrewSampleXlsx($type=''){
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = 'CrewMembers.xlsx';
     $arrayHeaderData = $this->config->item('crew_sample_fields');     
     $ship_details = (array) $this->cm->getAllShips(' and s.ship_id = '.$type,'R');
     $ship_name = $ship_details[0]->ship_name;
     $ship_imo = $ship_details[0]->imo_no;
     $listColumn     = array();
     $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'A1:A1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'B1:B1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'C1:C1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'D1:D1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'E1:E1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'F1:F1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'G1:G1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'H1:H1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'I1:I1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'J1:J1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'K1:K1','font'=>array(),'alignment'=>$align)));
        $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'L1:L1','font'=>array(),'alignment'=>$align)));

        $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:25','B:25','C:30','D:18','E:38','F:18','G:18','H:18','I:18','J:18','K:25','L:25'));
        $alignFormat  = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $listColumn[] = array('format'=>'cellAlign','cellArray'=>array(array('cell'=>'H4:H35','alignment'=>$alignFormat)));
        $listColumn[] = array('format'=>'wraptext','cellArray'=>array(array('cell'=>'K1:K35')));

        $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A1:M100','border'=>'THIN'))); 
        $arrayData   = array();
        // $arrayData[2] = array('','One North Ships');  
        $arrayData[1] = array('','','','',$arrayHeaderData[0],'','','','','','');
        $arrayData[2] = array($arrayHeaderData[1],$ship_name,$arrayHeaderData[2],$ship_imo,$arrayHeaderData[3],'',$arrayHeaderData[4],'');
        $arrayData[3] = array($arrayHeaderData[5],'',$arrayHeaderData[6],date('d-m-Y'),$arrayHeaderData[7],'',$arrayHeaderData[8],'',$arrayHeaderData[9],'');
        $newColArr = array();
        for($c = 10;$c<22;$c++){
            $newColArr[] = $arrayHeaderData[$c];
        } 
        $arrayData[4] = $newColArr;
        $arrayData[5] = array(1,'Sample','Sample','Pump Man','Sample',date('d-m-Y'),'Sample','Male','Sample','123','Sample',date('d-m-Y'));
        
        $arrayBundleData['listColumn'] = $listColumn;
        $arrayBundleData['arrayData'] = $arrayData;

        $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Crew Members');
        readfile(FCPATH.'uploads/sheets/'.$fileName);
        unlink(FCPATH.'uploads/sheets/'.$fileName);
        exit;  
  }

  function preview_crew_members(){
    checkUserSession();
    $user_session_data = getSessionData();
    $import_data = getImportData('crew_data');
    $ship_id = $import_data['ship_id'];
    //$ship_id = $this->input->post('id');
    $ship_details = (array) $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R');
    $shipping_company_id = $ship_details[0]->shipping_company_id;
    $actionType = $this->input->post('actionType');
    $returnArr['status'] = 100;
     if($actionType=='save'){
        $jsignature = $this->input->post('jsignature');
        $dataArr['ship_id'] = $ship_id;  
        $arrData = $import_data['crew'];
        $tmpArr = array(); 
        $tmpArr2 = array(); 
        if(!empty($arrData)){
            if(!empty($jsignature)){
                $jsignature = base64_decode($jsignature);
                $upload_path = getcwd().'/uploads/e_signature/';
                $output_file = $upload_path.'e_signature'.date('ymdhis').'.jpeg';
                $output_jpeg = base64_to_jpeg($jsignature,$output_file);
                if(file_exists($output_jpeg)){
                  $e_sign = 'e_signature'.date('ymdhis').'.jpeg';
                }
             }
             $type = ($import_data['arrival_or_departure']=='D')?'departure':'arrival';

            /*Make an Entry in Crew Member Entry Table*/
             $crew_entry_arr = array('ship_id' => $ship_id,
                'call_sign' => $import_data['call_sign'],
                'voyage_number' => $import_data['voyage_number'],
                'port_of_arrival_or_departure' => $import_data['port_of_arrival_or_departure'],
                'date_of_arrival_or_departure' => date("Y-m-d H:i:s",strtotime($import_data['date_of_arrival_or_departure'])),
                'flag_state_of_ship' => $import_data['flag_state_of_ship'],
                'last_port_of_call' => $import_data['last_port_of_call'],
                'arrival_or_departure' => $type,
                'created_date'=>date("Y-m-d H:i:s"),
                'created_by'=>$user_session_data->user_id,
                'e_sign'=>$e_sign
                );

               //print_r($crew_entry_arr);die;
                $isEntryExist = checkDuplicateCrewEntry($ship_id,$type);
                if(empty($isEntryExist)){
                    $this->db->insert('crew_member_entries',$crew_entry_arr);
                    $crew_member_entries_id = $this->db->insert_id();
                }else{
                    $crew_member_entries_id = $isEntryExist->crew_member_entries_id;
                    $updateArr = array('created_date'=>date("Y-m-d H:i:s"),'created_by'=>$user_session_data->user_id,'e_sign'=>$e_sign);
                    $this->um->updateCrewEntry($updateArr,$crew_member_entries_id);
                }

                //echo $this->db->last_query();die;
            /*Make an Entry in Crew Member Entry Table*/
          for ($i=1; $i <=count($arrData) ; $i++) {
           if((!empty($arrData[$i]['family_name']) && !empty($arrData[$i]['given_name']) && !empty($arrData[$i]['rank']))){ 

              $unique_id = $arrData[$i]['number_of_identity'];
              $role = $arrData[$i]['rank'];
              $tmpArr2 = $tmpArr[] = array(
                'ship_id' => $ship_id,
                'crew_member_entries_id' => $crew_member_entries_id,
                'family_name' => $arrData[$i]['family_name'],
                'given_name' => $arrData[$i]['given_name'],
                'rank' => $arrData[$i]['rank'],
                'nationality' => $arrData[$i]['nationality'],
                'date_of_birth' => date('Y-m-d',strtotime($arrData[$i]['date_of_birth'])),
                'place_of_birth' => $arrData[$i]['place_of_birth'],
                'gender' => $arrData[$i]['gender'],
                'nature_of_identity' => $arrData[$i]['nature_of_identity'],
                'identity_number' => $arrData[$i]['number_of_identity'],
                'issuing_state_of_identity' => $arrData[$i]['issuing_state_of_identity'],
                'expiry_date_of_identity' => date('Y-m-d',strtotime($arrData[$i]['expiry_date_of_identity']))
                             
              );
           }
          
          $user_exist = checkUserExistByPassportId($unique_id);
            if(empty($user_exist)){
              if($role == 'Master' || $role == 'Chief Cook'){
                $role_code = (strtolower($role)=='master')?'captain':'cook';
                $userArr = array();
                $userArr['first_name'] = explode(" ",$arrData[$i]['given_name'])[0];
                $userArr['last_name'] = explode(" ",$arrData[$i]['given_name'])[1];
                $userArr['created_date'] = date('Y-m-d');
                $userArr['img_url'] = 'profile.png';
                $userArr['is_deleted'] = '0';
                $userArr['shipping_company_id'] = $shipping_company_id;
                $userArr['passport_id'] = $unique_id;
                $this->db->insert('user',$userArr);
                $userId = $this->db->insert_id();
                $roleData = $this->um->getuserRolebyCode($role_code);
                $roleId = $roleData->role_id;
                $userRoleArr = array('user_id'=>$userId,'role_id'=>$roleId);
                $this->db->insert('user_role',$userRoleArr);
              }
            }else{
              $userId = $user_exist->user_id;
            }
            $crew_exist = checkCrewMemberExistByPassportId($unique_id,$ship_id);
            // if(!empty($jsignature)){
            //     $jsignature = base64_decode($jsignature);
            //     $upload_path = getcwd().'/uploads/e_signature/';
            //     $output_file = $upload_path.'e_signature'.date('ymdhis').'.jpeg';
            //     $output_jpeg = base64_to_jpeg($jsignature,$output_file);
            //     if(file_exists($output_jpeg)){
            //       $tmpArr2['e_sign'] = 'e_signature'.date('ymdhis').'.jpeg';
            //       $tmpArr2['sign_date'] = date('Y-m-d');
            //     }
            //  }
             if(empty($crew_exist)){
                $this->db->insert('ship_crew_members',$tmpArr2);
             }else{
                $this->um->updateCrew($tmpArr2,$crew_exist->crew_members_id);
                 //echo $this->db->last_query();
             }
           // -- To Update Cook and captain user id in ship table if needed
          }
          //die;  
        }

        $this->session->unset_userdata('crew_data');
        $returnArr['status'] = 200;
        $this->session->set_flashdata('succMsg','Crew Members added successfully.');
        $returnArr['returnMsg'] = 'Crew Members added successfully.'; 
     }
                     
    if(!empty($import_data)){
         $crewArr = array();
         $crewArr['ship_id'] = $import_data['ship_id'];
         $crewArr['ship_name'] = $import_data['ship_name'];
         $crewArr['ship_imo'] = $import_data['ship_imo'];
         $crewArr['arrival_or_departure'] = $import_data['arrival_or_departure'];
         $crewArr['call_sign'] = $import_data['call_sign'];
         $crewArr['voyage_number'] = $import_data['voyage_number'];
         $crewArr['port_of_arrival_or_departure'] = $import_data['port_of_arrival_or_departure'];
         $crewArr['date_of_arrival_or_departure'] = $import_data['date_of_arrival_or_departure'];
         $crewArr['flag_state_of_ship'] = $import_data['flag_state_of_ship'];
         $crewArr['last_port_of_call'] = $import_data['last_port_of_call'];
         //$crewArr['date_and_sign'] = $import_data['date_and_sign'];
         for ($i=1; $i <=count($import_data['crew']); $i++) {
              $unique_id = $import_data['crew'][$i]['number_of_identity'];
              $role = $import_data['crew'][$i]['rank']; 
              if($role == 'Master' || $role == 'Chief Cook'){
                if(!empty($unique_id)){
                  $user_exist = checkUserExistByPassportId($unique_id);
                }
              }
              else{
                if(!empty($unique_id)){
                  $user_exist = checkCrewMemberExistByPassportId($unique_id,$ship_id);
                }                
              }
             

              $already_exist = (empty($user_exist)) ? '' : '1';

              $crewArr['crew'][] = array(
                'family_name'=>$import_data['crew'][$i]['family_name'],
                'given_name'=>$import_data['crew'][$i]['given_name'],
                'rank'=>$import_data['crew'][$i]['rank'],
                'nationality'=>$import_data['crew'][$i]['nationality'],
                'date_of_birth'=>$import_data['crew'][$i]['date_of_birth'],
                'place_of_birth'=>$import_data['crew'][$i]['place_of_birth'],
                'gender'=>$import_data['crew'][$i]['gender'],
                'nature_of_identity'=>$import_data['crew'][$i]['nature_of_identity'],
                'number_of_identity'=>$import_data['crew'][$i]['number_of_identity'],
                'issuing_state_of_identity'=>$import_data['crew'][$i]['issuing_state_of_identity'],
                'expiry_date_of_identity'=>$import_data['crew'][$i]['expiry_date_of_identity'],'already_exist'=>$already_exist
              ); 
         }
       } 

    $vars['ship_id'] = $ship_id;
    $vars['crewArr'] = $crewArr;
    $data = $this->load->view('preview_crew_members',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }
 
 function crewMembersList($crew_member_entries_id=''){
   checkUserSession();
   $user_session_data = getSessionData(); 
   $crew_member_entries_id = base64_decode($crew_member_entries_id);
   $vars['user_session_data'] = $user_session_data;  
   $vars['shipCrewData'] = $shipCrewData = $this->cm->getallCrewEntrieslist(' and cme.crew_member_entries_id = '.$crew_member_entries_id,'R');
   $shipDetails = $this->cm->getAllShips(' and s.ship_id = '.$shipCrewData[0]->ship_id,'R');
   $vars['ship_id'] = $shipCrewData[0]->ship_id; 
   $vars['heading'] = 'Crew Members Of - '.$shipDetails[0]->ship_name;
   $vars['content_view'] = 'crew_members_list';
   $vars['company'] = $this->cm->getAllshippingCompany(' And c.status = 1','R');    
   $this->load->view('layout',$vars);   
  }

  function getallshipCrewMembersList(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = '';$order_by='';
   $returnArr = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
       if(!empty($ship_id)){
           $where .= ' AND (s.ship_id='.$ship_id.')';
        }

        if(!empty($keyword)){
         $where .= " AND (scm.family_name like '%".trim($keyword)."%' or scm.given_name like '%".trim($keyword)."%' or scm.rank like '%".trim($keyword)."%' or scm.identity_number like '%".trim($keyword)."%' )";   
        }
        
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Ship Name')
            {
                $order_by = 'ORDER BY s.ship_name '.$sort_type;
            }
            elseif($sort_column == 'Ship Code')
            {
                $order_by = 'ORDER BY s.ship_code '.$sort_type;
            }
            elseif($sort_column == 'CreatedDate')
            {
                $order_by = 'ORDER BY s.added_on '.$sort_type;
            }
            elseif($sort_column == 'Shipping Company')
            {
                $order_by = 'ORDER BY sc.name '.$sort_type;
            }
            elseif($sort_column == 'AddedBy')
            {
                $order_by = 'ORDER BY user_name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY s.ship_name ASC';
        }
        
        // if($download == '1'){
        //     $user_session_data  = getUserSession(); 
        //     $allAditionalContactList = $this->acm->getAllAditionalContactListNew($where,'R','','',$order_by);
        //        $file_name = "LeadAdditionalContact.csv";
        //         $field_array = array('Name','Relationship','Phone','Email','Created On','Created By');
        //         header('Content-type: application/csv');
        //         header('Content-Disposition: attachment; filename='.$file_name);
        //         $fp = fopen('php://output', 'w');
        //         fputcsv($fp, $field_array);
        //         if(!empty($allAditionalContactList))
        //         {   

        //             foreach($allAditionalContactList as $row)
        //             {   
        //                 $row_arr =array(ucfirst($row->first_name.' '.$row->last_name), 
        //                                        ucfirst($row->relationship),
        //                                        $row->phone,
        //                                        $row->email,
        //                                         ConvertDate($row->created_on, $user_session_data->timezone,'m-d-Y | h:i A'),
                                                
        //                                         ucfirst($row->user_name)
                                                
        //                                     );
        //                 fputcsv($fp, $row_arr);
        //             }
        //         }
        //         fclose($fp);
        //         exit;
        //         redirect(base_url()."CrewMembersList/".$ship_id);
        //         die;
        // }

        $countdata = $this->cm->getallCrewlist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $ships = $this->cm->getallCrewlist($where,'R',$offset,$perPage,$order_by);
        $import_food_habits = checkLabelByTask('import_food_habits');
        //echo $this->db->last_query();die;
        if($ships){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($ships)).' of '.$countdata.' entries'; 
         foreach ($ships as $row){
                 if($row->status == 0){
                  $Status = '<a onclick="updateStatusBox('.$row->ship_id.','.$row->status.',\''.$row->ship_name.'\',\'shipping/changestatusShips\')" href="javascript:void(0)">Activate</a>';   
                 }else{
                  $Status = '<a onclick="updateStatusBox('.$row->ship_id.','.$row->status.',\''.$row->ship_name.'\',\'shipping/changestatusShips\')" href="javascript:void(0)">Deactivate</a>';      
                 }
                 
                 if($import_food_habits){
                   $import_food_habits = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Import Food Habits\',\'shipping/import_crew_food_habits\','.$row->crew_members_id.',\'\',\'70%\');" >Import Food Habits</a>';
                 }

            if($row->date_of_birth == '1970-01-01' || $row->date_of_birth == ''){
              $date_of_birth = '-';
            }else{
              $date_of_birth =ConvertDate($row->date_of_birth,'','d-m-Y');
            }
            if($row->expiry_date_of_identity == '1970-01-01' || $row->expiry_date_of_identity == ''){
              $expiry_date_of_identity = '-';
            }else{
              $expiry_date_of_identity =ConvertDate($row->expiry_date_of_identity,'','d-m-Y');
            }
    
              $returnArr .= "<tr>
                             <td>".ucfirst($row->family_name)."</td>
                             <td >".ucfirst($row->given_name)."</td>
                             <td align='center'>".$row->rank."</td>
                             <td align='center'>".ucwords($row->nationality)."</td>
                             <td align='center'>".$date_of_birth."</td>
                             <td align='center'>".ucfirst($row->place_of_birth)."</td> 
                             <td align='center'>".ucfirst($row->gender)."</td>  
                             <td align='center'>".ucfirst($row->nature_of_identity)."</td>  
                             <td align='center'>".$row->identity_number."</td> 
                             <td align='center'>".$row->issuing_state_of_identity."</td>  
                             <td align='center'>".$expiry_date_of_identity."</td>";  
             $returnArr .= '<td align="center" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$import_food_habits.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }
         $returnArr .= '<tr><td width="50%" colspan="6" style="
         vertical-align: middle;"><strong>Date and Signature by Master, Authorized Agent or Officer</strong></td><td width="50%" colspan="6"><img class="cm-sign-img" src="'.base_url().'uploads/e_signature/'.$row->e_sign.'" width="150">'.date('d-m-Y',strtotime($row->created_date)).'</td></tr>';
         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="15" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));    
  }
 
  function extra_meals_invoice(){
      checkUserSession();
      $user_session_data = getSessionData();
      $this->load->model('email_manager');
      $this->em = $this->email_manager;
      $returnArr['status'] = 100;
      $extra_meal_id = $this->input->post('id');
      $ship_details = getCustomSession('ship_details');
      $invoice_data = (array) $this->cm->getEminvoiceData($extra_meal_id);
      $actionType = $this->input->post('actionType');
      if($actionType=='save'){
        $dataArr['ship_id'] = $invoice_data['ship_id'];
        $dataArr['added_on'] = date('Y-m-d H:i:s');
        $dataArr['added_by'] = $user_session_data->user_id;
        $dataArr['total_man_days'] = $invoice_data['total_man_days'];
        $dataArr['victualling_rate'] = $invoice_data['victualling_rate'];
        $dataArr['total_price'] = ($invoice_data['total_man_days'] * $invoice_data['victualling_rate']);
        $dataArr['month'] = $invoice_data['year'].'-'.$invoice_data['month'];
        //$dataArr['due_date'] = date('y-m-d',strtotime('+30 days',strtotime(date('y-m-d')))) . PHP_EOL;
        $dataArr['due_date'] = date('Y-m-d', strtotime("+30 days"));
        $dataArr['extra_meal_id'] = $invoice_data['extra_meal_id'];

        // print_r($dataArr);die;
        $this->cm->addMonthInvoice($dataArr);
        // $this->db->update('extra_meals',array('is_invoice_created'=>1),array('extra_meal_id'=>$extra_meal_id));

        $this->db->update('extra_meals',array('status'=>2),array('extra_meal_id'=>$extra_meal_id));

        $whereEm = ' AND nt.code = "month_end_invoice_submit"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##date##','##ship_name##'),array(ConvertDate($dataArr['month'],'','M Y'),ucwords($ship_details['ship_name'])),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }
        $returnArr['returnMsg'] = 'Invoice generated successfully.';
        $returnArr['status']= 200;
      }
      $vars['dataArr'] = $invoice_data;
      $data = $this->load->view('create_monthly_inovice',$vars,true);
      $returnArr['data'] = $data;
      echo json_encode($returnArr);
  }
  
  function view_em_invoice(){
      checkUserSession();
      $user_session_data = getSessionData();
      $returnArr['status'] = 100;
      $extra_meal_id = $this->input->post('id');
      $invoice_data = (array) $this->cm->getEmInvoiceById($extra_meal_id);
      $vars['dataArr'] = $invoice_data;
      $vars['type'] = 'view';
      $data = $this->load->view('create_monthly_inovice',$vars,true);
      $returnArr['data'] = $data;
      echo json_encode($returnArr);
  }  
  
 function printMonthInvoicePdf($extra_meal_id=''){
      checkUserSession();
     require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
      $invoice_data = (array) $this->cm->getEmInvoiceById(base64_decode($extra_meal_id));
      $vars['dataArr'] = $invoice_data;
      $vars['view_file'] = 'print_em_invoice';
      $vars['title'] = 'Month End Invoice';
      $this->load->view('downloadPdf',$vars);
 } 

function import_crew_food_habits(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] =100;
    $actionType = $this->input->post('actionType');
    $crew_members_id = $this->input->post('id');
    $crew_details = (array) $this->cm->getallCrewlist(' and scm.crew_members_id = '.$crew_members_id,'R');
    $crew_name = $crew_details[0]->given_name;
    $crew_rank = $crew_details[0]->rank;
    $crew_nationality = $crew_details[0]->nationality;
    $crew_gender= $crew_details[0]->gender;
    $crew_dob= $crew_details[0]->date_of_birth;
    $crew_passportId= $crew_details[0]->identity_number;
    $validation = true;
     if($actionType=='save'){
        
         $validation = false;
         $this->form_validation->set_rules('img','','callback_xlsx_file_check');  
         if($this->form_validation->run()){ 
            $validation = true;
            $file_name = $_FILES['img']['name'];
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            if(!empty($file_name)){
             $this->cm->deleteCrewMemberFoodHabit($crew_members_id);  
              
             $upload_data = doc_upload($file_name, 'sheets');
             $this->load->library('Excelreader');
             $excel = new Excelreader();
             $fileName = FCPATH.'uploads/sheets/'.$upload_data['file_name'];         
             $objWriter = $excel->readExcel($fileName,$ext);    
             $session_arr = array();
             $objWriter = array_values($objWriter);
             //print_r($objWriter);die;
             $session_arr['crew_member_id'] = $crew_members_id;
             $session_arr['crew_name'] = $crew_name;
             $session_arr['crew_rank'] = $crew_rank;
             $session_arr['crew_dob'] = $crew_dob;
             $session_arr['crew_gender'] = $crew_gender;
             $session_arr['crew_nationality'] = $crew_nationality;
             $session_arr['crew_passportId'] = $crew_passportId;
             $session_arr['food_habits'] = array();
             $session_arr['food_habits']['meat_never'] = ($objWriter[12]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['meat_daily'] = ($objWriter[12]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['meat_2'] = ($objWriter[12]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['meat_3'] = ($objWriter[12]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['meat_4'] = ($objWriter[12]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['meat_allergies'] = ($objWriter[12]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_never'] = ($objWriter[13]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_daily'] = ($objWriter[13]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_2'] = ($objWriter[13]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_3'] = ($objWriter[13]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_4'] = ($objWriter[13]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['pork_allergies'] = ($objWriter[13]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_never'] = ($objWriter[14]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_daily'] = ($objWriter[14]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_2'] = ($objWriter[14]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_3'] = ($objWriter[14]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_4'] = ($objWriter[14]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['beef_allergies'] = ($objWriter[14]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_never'] = ($objWriter[15]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_daily'] = ($objWriter[15]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_2'] = ($objWriter[15]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_3'] = ($objWriter[15]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_4'] = ($objWriter[15]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['fish_allergies'] = ($objWriter[15]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_never'] = ($objWriter[16]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_daily'] = ($objWriter[16]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_2'] = ($objWriter[16]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_3'] = ($objWriter[16]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_4'] = ($objWriter[16]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['mutton_allergies'] = ($objWriter[16]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_never'] = ($objWriter[17]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_daily'] = ($objWriter[17]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_2'] = ($objWriter[17]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_3'] = ($objWriter[17]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_4'] = ($objWriter[17]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['chicken_allergies'] = ($objWriter[17]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_never'] = ($objWriter[18]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_daily'] = ($objWriter[18]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_2'] = ($objWriter[18]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_3'] = ($objWriter[18]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_4'] = ($objWriter[18]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['egg_allergies'] = ($objWriter[18]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_never'] = ($objWriter[19]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_daily'] = ($objWriter[19]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_2'] = ($objWriter[19]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_3'] = ($objWriter[19]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_4'] = ($objWriter[19]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['cereals_allergies'] = ($objWriter[19]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_never'] = ($objWriter[20]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_daily'] = ($objWriter[20]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_2'] = ($objWriter[20]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_3'] = ($objWriter[20]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_4'] = ($objWriter[20]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['dairy_allergies'] = ($objWriter[20]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_never'] = ($objWriter[21]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_daily'] = ($objWriter[21]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_2'] = ($objWriter[21]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_3'] = ($objWriter[21]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_4'] = ($objWriter[21]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['veg_allergies'] = ($objWriter[21]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_never'] = ($objWriter[22]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_daily'] = ($objWriter[22]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_2'] = ($objWriter[22]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_3'] = ($objWriter[22]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_4'] = ($objWriter[22]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['fruits_allergies'] = ($objWriter[22]['G']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_never'] = ($objWriter[23]['B']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_daily'] = ($objWriter[23]['C']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_2'] = ($objWriter[23]['D']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_3'] = ($objWriter[23]['E']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_4'] = ($objWriter[23]['F']=='Y')?'Y':'N';
             $session_arr['food_habits']['sweets_allergies'] = ($objWriter[23]['G']=='Y')?'Y':'N';
             //print_r($session_arr);die;
             setImportSession('crew_food_data',$session_arr);
             unlink($fileName); 
             $returnArr['status'] = 200;    
        }
      }
    }
        $vars['dataArr'] = $this->input->post();
        $data = $this->load->view('import_crew_food_habits',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
}

  function downloadCrewFoodHabitsSampleXlsx($type=''){
     $this->load->library('Excelreader');
     $excel  = new Excelreader();
     $fileName = 'CrewFoodHabits.xlsx';
     $arrayHeaderData = $this->config->item('crew_food_habits_sample_fields');     
     $crewData = $this->cm->getallCrewlist(' and scm.crew_members_id = '.$type,'R');
     $crewMember = $crewData[0];
     $listColumn     = array();
      $align = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $listColumn[] = array('format'=>'mergeRow','mergeRow'=>array(array('cell'=>'H1:H1','font'=>array(),'alignment'=>$align)));
    
        $listColumn[] = array('format'=>'cellwidth','cellwidth'=>array('A:45','B:25','C:30','D:18','E:18','F:18','G:18','H:18','I:18','J:20','K:25','L:25'));
        $alignFormat2  = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $listColumn[] = array('format'=>'cellAlign','cellArray'=>array(array('cell'=>'A4','alignment'=>$alignFormat2)));
        $listColumn[] = array('format'=>'wraptext','cellArray'=>array(array('cell'=>'K1:K35')));

        $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A1:M100','border'=>'THIN'))); 
        $listColumn[] = array('format'=>'rowheight','row'=>'1','col'=>'A','rowheight'=>'120','colwidth'=>'30');
        $listColumn[] = array('addImage'=>'1','coordinates'=>'A1'); 
        $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                    'color' => array('rgb' => '49A8DF'),
                    'size'  => 12,
                    'name'  => 'Trebuchet MS')),'cellArray'=>array('A2','H1','H2'));
        $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                    'color' => array('rgb' => '000000'),
                    'size'  => 18,
                    'name'  => 'Trebuchet MS',
                    'bold' => true,
                    'italic' => true,
                    'underline' => true)),'cellArray'=>array('E3'));
        $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                    'color' => array('rgb' => '000000'),
                    'size'  => 14,
                    'name'  => 'Trebuchet MS'
                    )),'cellArray'=>array('A4','A6','C6','E6','A8','C8','E8','A10'));
        $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font'  => array(
                    'color' => array('rgb' => '000000'),
                    'size'  => 14,
                    'name'  => 'Trebuchet MS',
                    'bold' => true,
                    'italic' => true
                    )),'cellArray'=>array('A12','B12','C12','D12','E12','F12','G12','A13','A14','A15','A16','A17','A18','A19','A20','A21','A21','A22','A23','A24'));

        $arrayData   = array();
        $arrayData[1] = array('','','','','','','','One North Ships
            PO Box 79998, 
            Dubai UAE.
            info@onenorthships.com','');
        $arrayData[2] = array('www.onenorthships.com','','','','','','','Form No. ONS-A11/Rev. No. 01/23');
        $arrayData[3] = array('','','','','FOOD HABITS DECLARATION','','','','');
        $arrayData[4] = array($arrayHeaderData[0],'','','','','','','','');
        $arrayData[6] = array($arrayHeaderData[1],$crewMember->given_name,$arrayHeaderData[2],$crewMember->rank,$arrayHeaderData[3],$crewMember->date_of_birth,'','');
        $arrayData[8] = array($arrayHeaderData[4],$crewMember->gender,$arrayHeaderData[5],$crewMember->nationality,$arrayHeaderData[6],$crewMember->identity_number,'','');
        $arrayData[10] = array($arrayHeaderData[7]);
        $arrayData[12] = array($arrayHeaderData[8].'(Type Y against each option and for No, leave it blank)',$arrayHeaderData[9],$arrayHeaderData[10],$arrayHeaderData[11],$arrayHeaderData[12],$arrayHeaderData[13],$arrayHeaderData[14]);
        $arrayData[13] = array($arrayHeaderData[15],'','','','','','');
        $arrayData[14] = array($arrayHeaderData[16],'','','','','','');
        $arrayData[15] = array($arrayHeaderData[17],'','','','','','');
        $arrayData[16] = array($arrayHeaderData[18],'','','','','','');
        $arrayData[17] = array($arrayHeaderData[19],'','','','','','');
        $arrayData[18] = array($arrayHeaderData[20],'','','','','','');
        $arrayData[19] = array($arrayHeaderData[21],'','','','','','');
        $arrayData[20] = array($arrayHeaderData[22],'','','','','','');
        $arrayData[21] = array($arrayHeaderData[23],'','','','','','');
        $arrayData[22] = array($arrayHeaderData[24],'','','','','','');
        $arrayData[23] = array($arrayHeaderData[25],'','','','','','');
        $arrayData[24] = array($arrayHeaderData[26],'','','','','','');
        $arrayData[26] = array('*For internal use only.','','','','','','');
        $arrayData[28] = array('','','','','Registered  oﬃces:    • India   •   UAE   • USA  •   Canada','','','','');

        $arrayBundleData['listColumn'] = $listColumn;
        $arrayBundleData['arrayData'] = $arrayData;

        $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Crew Food Habits');
        readfile(FCPATH.'uploads/sheets/'.$fileName);
        unlink(FCPATH.'uploads/sheets/'.$fileName);
        exit;  
  }

  function preview_crew_food_habits(){
    checkUserSession();
    $user_session_data = getSessionData();
    $import_data = getImportData('crew_food_data');
    $crew_member_id = $import_data['crew_member_id'];
    $crew_details = (array) $this->cm->getallCrewlist(' and scm.crew_members_id = '.$crew_member_id,'R');
    $actionType = $this->input->post('actionType');
    $returnArr['status'] = 100;
     if($actionType=='save'){
        $arrData = $import_data['food_habits'];
        // print_r($arrData);die;
        $tempArr = array(); 
         if(!empty($arrData)){
           $tempArr['crew_member_id'] = $crew_member_id;
           $tempArr['meat_never'] = ($arrData['meat_never']=='Y')?'Yes':'No';
           $tempArr['meat_daily'] = ($arrData['meat_daily']=='Y')?'Yes':'No';
           $tempArr['meat_2_week'] = ($arrData['meat_2']=='Y')?'Yes':'No';
           $tempArr['meat_3_week'] = ($arrData['meat_3']=='Y')?'Yes':'No';
           $tempArr['meat_4_week'] = ($arrData['meat_4']=='Y')?'Yes':'No';
           $tempArr['meat_allergies'] = ($arrData['meat_allergies']=='Y')?'Yes':'No';
           $tempArr['pork_never'] = ($arrData['pork_never']=='Y')?'Yes':'No';
           $tempArr['pork_daily'] = ($arrData['pork_daily']=='Y')?'Yes':'No';
           $tempArr['pork_2_week'] = ($arrData['pork_2']=='Y')?'Yes':'No';
           $tempArr['pork_3_week'] = ($arrData['pork_3']=='Y')?'Yes':'No';
           $tempArr['pork_4_week'] = ($arrData['pork_4']=='Y')?'Yes':'No';
           $tempArr['pork_allergies'] = ($arrData['pork_allergies']=='Y')?'Yes':'No';
           $tempArr['beef_never'] = ($arrData['beef_never']=='Y')?'Yes':'No';
           $tempArr['beef_daily'] = ($arrData['beef_daily']=='Y')?'Yes':'No';
           $tempArr['beef_2_week'] = ($arrData['beef_2']=='Y')?'Yes':'No';
           $tempArr['beef_3_week'] = ($arrData['beef_3']=='Y')?'Yes':'No';
           $tempArr['beef_4_week'] = ($arrData['beef_4']=='Y')?'Yes':'No';
           $tempArr['beef_allergies'] = ($arrData['beef_allergies']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_never'] = ($arrData['fish_never']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_daily'] = ($arrData['fish_daily']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_2_week'] = ($arrData['fish_2']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_3_week'] = ($arrData['fish_3']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_4_week'] = ($arrData['fish_4']=='Y')?'Yes':'No';
           $tempArr['fish_sea_food_allergies'] = ($arrData['fish_allergies']=='Y')?'Yes':'No';
           $tempArr['mutton_never'] = ($arrData['mutton_never']=='Y')?'Yes':'No';
           $tempArr['mutton_daily'] = ($arrData['mutton_daily']=='Y')?'Yes':'No';
           $tempArr['mutton_2_week'] = ($arrData['mutton_2']=='Y')?'Yes':'No';
           $tempArr['mutton_3_week'] = ($arrData['mutton_3']=='Y')?'Yes':'No';
           $tempArr['mutton_4_week'] = ($arrData['mutton_4']=='Y')?'Yes':'No';
           $tempArr['mutton_allergies'] = ($arrData['mutton_allergies']=='Y')?'Yes':'No';
           $tempArr['chicken_never'] = ($arrData['chicken_never']=='Y')?'Yes':'No';
           $tempArr['chicken_daily'] = ($arrData['chicken_daily']=='Y')?'Yes':'No';
           $tempArr['chicken_2_week'] = ($arrData['chicken_2']=='Y')?'Yes':'No';
           $tempArr['chicken_3_week'] = ($arrData['chicken_3']=='Y')?'Yes':'No';
           $tempArr['chicken_4_week'] = ($arrData['chicken_4']=='Y')?'Yes':'No';
           $tempArr['chicken_allergies'] = ($arrData['chicken_allergies']=='Y')?'Yes':'No';
           $tempArr['egg_never'] = ($arrData['egg_never']=='Y')?'Yes':'No';
           $tempArr['egg_daily'] = ($arrData['egg_daily']=='Y')?'Yes':'No';
           $tempArr['egg_2_week'] = ($arrData['egg_2']=='Y')?'Yes':'No';
           $tempArr['egg_3_week'] = ($arrData['egg_3']=='Y')?'Yes':'No';
           $tempArr['egg_4_week'] = ($arrData['egg_4']=='Y')?'Yes':'No';
           $tempArr['egg_allergies'] = ($arrData['egg_allergies']=='Y')?'Yes':'No';
           $tempArr['cereals_never'] = ($arrData['cereals_never']=='Y')?'Yes':'No';
           $tempArr['cereals_daily'] = ($arrData['cereals_daily']=='Y')?'Yes':'No';
           $tempArr['cereals_2_week'] = ($arrData['cereals_2']=='Y')?'Yes':'No';
           $tempArr['cereals_3_week'] = ($arrData['cereals_3']=='Y')?'Yes':'No';
           $tempArr['cereals_4_week'] = ($arrData['cereals_4']=='Y')?'Yes':'No';
           $tempArr['cereal_allergies'] = ($arrData['cereals_allergies']=='Y')?'Yes':'No';
           $tempArr['dairy_products_never'] = ($arrData['dairy_never']=='Y')?'Yes':'No';
           $tempArr['dairy_products_daily'] = ($arrData['dairy_daily']=='Y')?'Yes':'No';
           $tempArr['dairy_products_2_week'] = ($arrData['dairy_2']=='Y')?'Yes':'No';
           $tempArr['dairy_products_3_week'] = ($arrData['dairy_3']=='Y')?'Yes':'No';
           $tempArr['dairy_products_4_week'] = ($arrData['dairy_4']=='Y')?'Yes':'No';
           $tempArr['dairy_products_allergies'] = ($arrData['dairy_allergies']=='Y')?'Yes':'No';
           $tempArr['vegetables_never'] = ($arrData['veg_never']=='Y')?'Yes':'No';
           $tempArr['vegetables_daily'] = ($arrData['veg_daily']=='Y')?'Yes':'No';
           $tempArr['vegetables_2_week'] = ($arrData['veg_2']=='Y')?'Yes':'No';
           $tempArr['vegetables_3_week'] = ($arrData['veg_3']=='Y')?'Yes':'No';
           $tempArr['vegetables_4_week'] = ($arrData['veg_4']=='Y')?'Yes':'No';
           $tempArr['vegetables_allergies'] = ($arrData['veg_allergies']=='Y')?'Yes':'No';
           $tempArr['fruits_never'] = ($arrData['fruits_never']=='Y')?'Yes':'No';
           $tempArr['fruits_daily'] = ($arrData['fruits_daily']=='Y')?'Yes':'No';
           $tempArr['fruits_2_week'] = ($arrData['fruits_2']=='Y')?'Yes':'No';
           $tempArr['fruits_3_week'] = ($arrData['fruits_3']=='Y')?'Yes':'No';
           $tempArr['fruits_4_week'] = ($arrData['fruits_4']=='Y')?'Yes':'No';
           $tempArr['fruits_allergies'] = ($arrData['fruits_allergies']=='Y')?'Yes':'No';
           $tempArr['sweets_never'] = ($arrData['sweets_never']=='Y')?'Yes':'No';
           $tempArr['sweets_daily'] = ($arrData['sweets_daily']=='Y')?'Yes':'No';
           $tempArr['sweets_2_week'] = ($arrData['sweets_2']=='Y')?'Yes':'No';
           $tempArr['sweets_3_week'] = ($arrData['sweets_3']=='Y')?'Yes':'No';
           $tempArr['sweets_4_week'] = ($arrData['sweets_4']=='Y')?'Yes':'No';
           $tempArr['sweets_allergies'] = ($arrData['sweets_allergies']=='Y')?'Yes':'No';
         }
          $foodHabitExist = checkCrewFoodHabitExistByCrewMemberId($crew_member_id);
          if(empty($foodHabitExist)){
            $this->db->insert('crew_food_habits',$tempArr);
          }else{
            $this->um->updateCrewFoodHabits($tempArr,$foodHabitExist->crew_member_id);
          }

          $this->session->unset_userdata('crew_food_data');
          $returnArr['status'] = 200;
          $this->session->set_flashdata('succMsg','Crew Food Habits added successfully.');
          $returnArr['returnMsg'] = 'Crew Food Habits added successfully.'; 
     }
    //print_r($import_data);die;             
    if(!empty($import_data)){
         $crewArr = array();
         $crewArr['crew_member_id'] = $import_data['crew_member_id'];
         $crewArr['crew_name'] = $import_data['crew_name'];
         $crewArr['crew_rank'] = $import_data['crew_rank'];
         $crewArr['crew_dob'] = $import_data['crew_dob'];
         $crewArr['crew_gender'] = $import_data['crew_gender'];
         $crewArr['crew_nationality'] = $import_data['crew_nationality'];
         $crewArr['crew_passportId'] = $import_data['crew_passportId'];
         
          $crewArr['food_habits'][] = array(
            'meat_never'=>$import_data['food_habits']['meat_never'],
            'meat_daily'=>$import_data['food_habits']['meat_daily'],
            'meat_2'=>$import_data['food_habits']['meat_2'],
            'meat_3'=>$import_data['food_habits']['meat_3'],
            'meat_4'=>$import_data['food_habits']['meat_4'],
            'meat_allergies'=>$import_data['food_habits']['meat_allergies'],
            'pork_never'=>$import_data['food_habits']['pork_never'],
            'pork_daily'=>$import_data['food_habits']['pork_daily'],
            'pork_2'=>$import_data['food_habits']['pork_2'],
            'pork_3'=>$import_data['food_habits']['pork_3'],
            'pork_4'=>$import_data['food_habits']['pork_4'],
            'pork_allergies'=>$import_data['food_habits']['pork_allergies'],
            'beef_never'=>$import_data['food_habits']['beef_never'],
            'beef_daily'=>$import_data['food_habits']['beef_daily'],
            'beef_2'=>$import_data['food_habits']['beef_2'],
            'beef_3'=>$import_data['food_habits']['beef_3'],
            'beef_4'=>$import_data['food_habits']['beef_4'],
            'beef_allergies'=>$import_data['food_habits']['beef_allergies'],
            'fish_never'=>$import_data['food_habits']['fish_never'],
            'fish_daily'=>$import_data['food_habits']['fish_daily'],
            'fish_2'=>$import_data['food_habits']['fish_2'],
            'fish_3'=>$import_data['food_habits']['fish_3'],
            'fish_4'=>$import_data['food_habits']['fish_4'],
            'fish_allergies'=>$import_data['food_habits']['fish_allergies'],
            'mutton_never'=>$import_data['food_habits']['mutton_never'],
            'mutton_daily'=>$import_data['food_habits']['mutton_daily'],
            'mutton_2'=>$import_data['food_habits']['mutton_2'],
            'mutton_3'=>$import_data['food_habits']['mutton_3'],
            'mutton_4'=>$import_data['food_habits']['mutton_4'],
            'mutton_allergies'=>$import_data['food_habits']['mutton_allergies'],
            'chicken_never'=>$import_data['food_habits']['chicken_never'],
            'chicken_daily'=>$import_data['food_habits']['chicken_daily'],
            'chicken_2'=>$import_data['food_habits']['chicken_2'],
            'chicken_3'=>$import_data['food_habits']['chicken_3'],
            'chicken_4'=>$import_data['food_habits']['chicken_4'],
            'chicken_allergies'=>$import_data['food_habits']['chicken_allergies'],
            'egg_never'=>$import_data['food_habits']['egg_never'],
            'egg_daily'=>$import_data['food_habits']['egg_daily'],
            'egg_2'=>$import_data['food_habits']['egg_2'],
            'egg_3'=>$import_data['food_habits']['egg_3'],
            'egg_4'=>$import_data['food_habits']['egg_4'],
            'egg_allergies'=>$import_data['food_habits']['egg_allergies'],
            'cereals_never'=>$import_data['food_habits']['cereals_never'],
            'cereals_daily'=>$import_data['food_habits']['cereals_daily'],
            'cereals_2'=>$import_data['food_habits']['cereals_2'],
            'cereals_3'=>$import_data['food_habits']['cereals_3'],
            'cereals_4'=>$import_data['food_habits']['cereals_4'],
            'cereals_allergies'=>$import_data['food_habits']['cereals_allergies'],
            'dairy_never'=>$import_data['food_habits']['dairy_never'],
            'dairy_daily'=>$import_data['food_habits']['dairy_daily'],
            'dairy_2'=>$import_data['food_habits']['dairy_2'],
            'dairy_3'=>$import_data['food_habits']['dairy_3'],
            'dairy_4'=>$import_data['food_habits']['dairy_4'],
            'dairy_allergies'=>$import_data['food_habits']['dairy_allergies'],
            'veg_never'=>$import_data['food_habits']['veg_never'],
            'veg_daily'=>$import_data['food_habits']['veg_daily'],
            'veg_2'=>$import_data['food_habits']['veg_2'],
            'veg_3'=>$import_data['food_habits']['veg_3'],
            'veg_4'=>$import_data['food_habits']['veg_4'],
            'veg_allergies'=>$import_data['food_habits']['veg_allergies'],
            'fruits_never'=>$import_data['food_habits']['fruits_never'],
            'fruits_daily'=>$import_data['food_habits']['fruits_daily'],
            'fruits_2'=>$import_data['food_habits']['fruits_2'],
            'fruits_3'=>$import_data['food_habits']['fruits_3'],
            'fruits_4'=>$import_data['food_habits']['fruits_4'],
            'fruits_allergies'=>$import_data['food_habits']['fruits_allergies'],
            'sweets_never'=>$import_data['food_habits']['sweets_never'],
            'sweets_daily'=>$import_data['food_habits']['sweets_daily'],
            'sweets_2'=>$import_data['food_habits']['sweets_2'],
            'sweets_3'=>$import_data['food_habits']['sweets_3'],
            'sweets_4'=>$import_data['food_habits']['sweets_4'],
            'sweets_allergies'=>$import_data['food_habits']['sweets_allergies']
          ); 
         
       } 
    $vars['crew_id'] = $crew_member_id;
    $vars['crewArr'] = $crewArr;
    $data = $this->load->view('preview_crew_food_habits',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }
  
  function download_OPI($invoice_id=''){
    checkUserSession();
    require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
    $invoice_id = base64_decode($invoice_id);
    $where = ' AND ci.company_invoice_id='.$invoice_id;
    $data = (array) $this->mp->getCompanyInvoice($where);
    // echo $this->db->last_query();die;
    $vars['dataArr'] = $data;
    $vars['view_file'] = 'one_pager_invoice';
    $vars['title'] = 'One Pager Invoice';
    $this->load->view('downloadPdf',$vars);  
  }
  
  function foodHabitList($ship_id=''){
   checkUserSession();
   $user_session_data = getSessionData(); 
   $ship_id = base64_decode($ship_id);
   $shipDetails = $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R'); 
   $vars['user_session_data'] = $user_session_data;   
   $vars['ship_id'] = $ship_id;
   $vars['heading'] = 'Food Habits Of - '.$shipDetails[0]->ship_name;
   $vars['content_view'] = 'food_habit_list';
   $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
   $this->load->view('layout',$vars);   
  }
  
  function getallFoodHabitList(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = '';$order_by='';
   $returnArr = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
        if(!empty($keyword)){
         $where .= " AND scm.family_name like '%".trim($keyword)."%' or scm.given_name like '%".trim($keyword)."%' or scm.rank like '%".trim($keyword)."%' or scm.identity_number like '%".trim($keyword)."%' ";   
        }

         if(!empty($ship_id)){
           $where .= ' AND scm.ship_id='.$ship_id;
         }
        
       /* if($download == '1'){
            $user_session_data  = getUserSession(); 
            $allAditionalContactList = $this->acm->getAllAditionalContactListNew($where,'R','','',$order_by);
               $file_name = "LeadAdditionalContact.csv";
                $field_array = array('Name','Relationship','Phone','Email','Created On','Created By');
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename='.$file_name);
                $fp = fopen('php://output', 'w');
                fputcsv($fp, $field_array);
                if(!empty($allAditionalContactList))
                {   

                    foreach($allAditionalContactList as $row)
                    {   
                        $row_arr =array(ucfirst($row->first_name.' '.$row->last_name), 
                                               ucfirst($row->relationship),
                                               $row->phone,
                                               $row->email,
                                                ConvertDate($row->created_on, $user_session_data->timezone,'m-d-Y | h:i A'),
                                                
                                                ucfirst($row->user_name)
                                                
                                            );
                        fputcsv($fp, $row_arr);
                    }
                }
                fclose($fp);
                exit;
                redirect(base_url()."CrewMembersList/".$ship_id);
                die;
        }*/

        $countdata = $this->cm->getallFoodHabitlist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $habits = $this->cm->getallFoodHabitlist($where,'R',$offset,$perPage,$order_by);
        //echo $this->db->last_query();die;
        if($habits){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($habits)).' of '.$countdata.' entries';
          $i=1;
         foreach ($habits as $row){
              $dob = new DateTime($row->date_of_birth);
              $today   = new DateTime('today');
              $age = $dob->diff($today)->y; 
              $meatHabitArr = array();
              $meatHabitArr[] = $row->meat_never;
              $meatHabitArr[] = $row->meat_daily;
              $meatHabitArr[] = $row->meat_2_week;
              $meatHabitArr[] = $row->meat_3_week;
              $meatHabitArr[] = $row->meat_4_week;
              $meatHabitArr[] = $row->meat_allergies;
              
              $meat_habit_index = array_search('No',$meatHabitArr);
              
                if($meat_habit_index !== '' && $meat_habit_index === 0){
                    $meat_habit ='Never';
                }elseif($meat_habit_index === 1){
                    $meat_habit ='Daily';
                }elseif($meat_habit_index == 2){
                    $meat_habit ='2/Week';
                }elseif($meat_habit_index == 3){
                    $meat_habit ='3/Week';
                }elseif($meat_habit_index == 4){
                    $meat_habit ='4/Week';
                }elseif($meat_habit_index == 5){
                    $meat_habit ='Allergies';
                }else{
                    $meat_habit = '-';
                }
              $porkHabitArr = array();
              $porkHabitArr[] = $row->pork_never;
              $porkHabitArr[] = $row->pork_daily;
              $porkHabitArr[] = $row->pork_2_week;
              $porkHabitArr[] = $row->pork_3_week;
              $porkHabitArr[] = $row->pork_4_week;
              $porkHabitArr[] = $row->pork_allergies;
              $pork_habit_index = array_search('No',$porkHabitArr);
              
              
                if($pork_habit_index !== '' && $pork_habit_index === 0){
                    $pork_habit ='Never';
                }elseif($pork_habit_index == 1){
                    $pork_habit ='Daily';
                }elseif($pork_habit_index == 2){
                    $pork_habit ='2/Week';
                }elseif($pork_habit_index == 3){
                    $pork_habit ='3/Week';
                }elseif($pork_habit_index == 4){
                    $pork_habit ='4/Week';
                }elseif($pork_habit_index == 5){
                    $pork_habit ='Allergies';
                }else{
                    $pork_habit = '-';
                }
              
              $beefHabitArr = array();
              $beefHabitArr[] = $row->beef_never;
              $beefHabitArr[] = $row->beef_daily;
              $beefHabitArr[] = $row->beef_2_week;
              $beefHabitArr[] = $row->beef_3_week;
              $beefHabitArr[] = $row->beef_4_week;
              $beefHabitArr[] = $row->beef_allergies;
              $beef_habit_index = array_search('No',$beefHabitArr);
              
                if($beef_habit_index !== '' && $beef_habit_index === 0){
                    $beef_habit ='Never';
                }elseif($beef_habit_index == 1){
                    $beef_habit ='Daily';
                }elseif($beef_habit_index == 2){
                    $beef_habit ='2/Week';
                }elseif($beef_habit_index == 3){
                    $beef_habit ='3/Week';
                }elseif($beef_habit_index == 4){
                    $beef_habit ='4/Week';
                }elseif($beef_habit_index == 5){
                    $beef_habit ='Allergies';
                }else{
                   $beef_habit = '-'; 
                }
              
              $fishHabitArr = array();
              $fishHabitArr[] = $row->fish_sea_food_never;
              $fishHabitArr[] = $row->fish_sea_food_daily;
              $fishHabitArr[] = $row->fish_sea_food_2_week;
              $fishHabitArr[] = $row->fish_sea_food_3_week;
              $fishHabitArr[] = $row->fish_sea_food_4_week;
              $fishHabitArr[] = $row->fish_sea_food_allergies;
              $fish_habit_index = array_search('No',$fishHabitArr);
              
                if($fish_habit_index !== '' && $fish_habit_index === 0){
                    $fish_habit ='Never';
                }elseif($fish_habit_index == 1){
                    $fish_habit ='Daily';
                }elseif($fish_habit_index == 2){
                    $fish_habit ='2/Week';
                }elseif($fish_habit_index == 3){
                    $fish_habit ='3/Week';
                }elseif($fish_habit_index == 4){
                    $fish_habit ='4/Week';
                }elseif($fish_habit_index == 5){
                    $fish_habit ='Allergies';
                }else{
                   $fish_habit = '-'; 
                }
              
              $muttonHabitArr = array();
              $muttonHabitArr[] = $row->mutton_never;
              $muttonHabitArr[] = $row->mutton_daily;
              $muttonHabitArr[] = $row->mutton_2_week;
              $muttonHabitArr[] = $row->mutton_3_week;
              $muttonHabitArr[] = $row->mutton_4_week;
              $muttonHabitArr[] = $row->mutton_allergies;
              $mutton_habit_index = array_search('No',$muttonHabitArr);
              
                if($mutton_habit_index !== '' && $mutton_habit_index === 0){
                    $mutton_habit ='Never';
                }elseif($mutton_habit_index == 1){
                    $mutton_habit ='Daily';
                }elseif($mutton_habit_index == 2){
                    $mutton_habit ='2/Week';
                }elseif($mutton_habit_index == 3){
                    $mutton_habit ='3/Week';
                }elseif($mutton_habit_index == 4){
                    $mutton_habit ='4/Week';
                }elseif($mutton_habit_index == 5){
                    $mutton_habit ='Allergies';
                }else{
                   $mutton_habit = '-'; 
                }
              
              $chickenHabitArr = array();
              $chickenHabitArr[] = $row->chicken_never;
              $chickenHabitArr[] = $row->chicken_daily;
              $chickenHabitArr[] = $row->chicken_2_week;
              $chickenHabitArr[] = $row->chicken_3_week;
              $chickenHabitArr[] = $row->chicken_4_week;
              $chickenHabitArr[] = $row->chicken_allergies;
              $chicken_habit_index = array_search('No',$chickenHabitArr);
              
                if($chicken_habit_index !== '' && $chicken_habit_index === 0){
                    $chicken_habit ='Never';
                }elseif($chicken_habit_index == 1){
                    $chicken_habit ='Daily';
                }elseif($chicken_habit_index == 2){
                    $chicken_habit ='2/Week';
                }elseif($chicken_habit_index == 3){
                    $chicken_habit ='3/Week';
                }elseif($chicken_habit_index == 4){
                    $chicken_habit ='4/Week';
                }elseif($chicken_habit_index == 5){
                    $chicken_habit ='Allergies';
                }else{
                    $chicken_habit = '-';
                }
              
              $eggHabitArr = array();
              $eggHabitArr[] = $row->egg_never;
              $eggHabitArr[] = $row->egg_daily;
              $eggHabitArr[] = $row->egg_2_week;
              $eggHabitArr[] = $row->egg_3_week;
              $eggHabitArr[] = $row->egg_4_week;
              $eggHabitArr[] = $row->egg_allergies;
              $egg_habit_index = array_search('No',$eggHabitArr);
              
                if($egg_habit_index !== '' && $egg_habit_index === 0){
                    $egg_habit ='Never';
                }elseif($egg_habit_index == 1){
                    $egg_habit ='Daily';
                }elseif($egg_habit_index == 2){
                    $egg_habit ='2/Week';
                }elseif($egg_habit_index == 3){
                    $egg_habit ='3/Week';
                }elseif($egg_habit_index == 4){
                    $egg_habit ='4/Week';
                }elseif($egg_habit_index == 5){
                    $egg_habit ='Allergies';
                }else{
                    $egg_habit = '-';
                }
              
              $cerealsHabitArr = array();
              $cerealsHabitArr[] = $row->cereals_never;
              $cerealsHabitArr[] = $row->cereals_daily;
              $cerealsHabitArr[] = $row->cereals_2_week;
              $cerealsHabitArr[] = $row->cereals_3_week;
              $cerealsHabitArr[] = $row->cereals_4_week;
              $cerealsHabitArr[] = $row->cereal_allergies;
              $cereals_habit_index = array_search('No',$cerealsHabitArr);
                if($cereals_habit_index !== '' && $cereals_habit_index === 0){
                    $cereals_habit ='Never';
                }elseif($cereals_habit_index == 1){
                    $cereals_habit ='Daily';
                }elseif($cereals_habit_index == 2){
                    $cereals_habit ='2/Week';
                }elseif($cereals_habit_index == 3){
                    $cereals_habit ='3/Week';
                }elseif($cereals_habit_index == 4){
                    $cereals_habit ='4/Week';
                }elseif($cereals_habit_index == 5){
                    $cereals_habit ='Allergies';
                }else{
                    $cereals_habit ='-';
                }
              
              $dairyHabitArr = array();
              $dairyHabitArr[] = $row->dairy_products_never;
              $dairyHabitArr[] = $row->dairy_products_daily;
              $dairyHabitArr[] = $row->dairy_products_2_week;
              $dairyHabitArr[] = $row->dairy_products_3_week;
              $dairyHabitArr[] = $row->dairy_products_4_week;
              $dairyHabitArr[] = $row->dairy_products_allergies;
              $dairy_habit_index = array_search('No',$dairyHabitArr);
              
                if($dairy_habit_index !== '' && $dairy_habit_index === 0){
                    $dairy_habit ='Never';
                }elseif($dairy_habit_index == 1){
                    $dairy_habit ='Daily';
                }elseif($dairy_habit_index == 2){
                    $dairy_habit ='2/Week';
                }elseif($dairy_habit_index == 3){
                    $dairy_habit ='3/Week';
                }elseif($dairy_habit_index == 4){
                    $dairy_habit ='4/Week';
                }elseif($dairy_habit_index == 5){
                    $dairy_habit ='Allergies';
                }else{
                    $dairy_habit = '-';
                }
              
              $vegHabitArr = array();
              $vegHabitArr[] = $row->vegetables_never;
              $vegHabitArr[] = $row->vegetables_daily;
              $vegHabitArr[] = $row->vegetables_2_week;
              $vegHabitArr[] = $row->vegetables_3_week;
              $vegHabitArr[] = $row->vegetables_4_week;
              $vegHabitArr[] = $row->vegetables_allergies;
              $veg_habit_index = array_search('No',$vegHabitArr);
              
                if($veg_habit_index !== '' && $veg_habit_index === 0){
                    $veg_habit ='Never';
                }elseif($veg_habit_index == 1){
                    $veg_habit ='Daily';
                }elseif($veg_habit_index == 2){
                    $veg_habit ='2/Week';
                }elseif($veg_habit_index == 3){
                    $veg_habit ='3/Week';
                }elseif($veg_habit_index == 4){
                    $veg_habit ='4/Week';
                }elseif($veg_habit_index == 5){
                    $veg_habit ='Allergies';
                }else{
                    $veg_habit = '-';
                }
              
              $fruitsHabitArr = array();
              $fruitsHabitArr[] = $row->fruits_never;
              $fruitsHabitArr[] = $row->fruits_daily;
              $fruitsHabitArr[] = $row->fruits_2_week;
              $fruitsHabitArr[] = $row->fruits_3_week;
              $fruitsHabitArr[] = $row->fruits_4_week;
              $fruitsHabitArr[] = $row->fruits_allergies;
              $fruits_habit_index = array_search('No',$fruitsHabitArr);
              
                if($fruits_habit_index !== '' && $fruits_habit_index === 0){
                    $fruits_habit ='Never';
                }elseif($fruits_habit_index == 1){
                    $fruits_habit ='Daily';
                }elseif($fruits_habit_index == 2){
                    $fruits_habit ='2/Week';
                }elseif($fruits_habit_index == 3){
                    $fruits_habit ='3/Week';
                }elseif($fruits_habit_index == 4){
                    $fruits_habit ='4/Week';
                }elseif($fruits_habit_index == 5){
                    $fruits_habit ='Allergies';
                }else{
                   $fruits_habit = '-'; 
                }
              
              $sweetsHabitArr = array();
              $sweetsHabitArr[] = $row->sweets_never;
              $sweetsHabitArr[] = $row->sweets_daily;
              $sweetsHabitArr[] = $row->sweets_2_week;
              $sweetsHabitArr[] = $row->sweets_3_week;
              $sweetsHabitArr[] = $row->sweets_4_week;
              $sweetsHabitArr[] = $row->sweets_allergies;
              $sweets_habit_index = array_search('No',$sweetsHabitArr);
                if($sweets_habit_index !== '' && $sweets_habit_index === 0){
                    $sweets_habit ='Never';
                }elseif($sweets_habit_index == 1){
                    $sweets_habit ='Daily';
                }elseif($sweets_habit_index == 2){
                    $sweets_habit ='2/Week';
                }elseif($sweets_habit_index == 3){
                    $sweets_habit ='3/Week';
                }elseif($sweets_habit_index == 4){
                    $sweets_habit ='4/Week';
                }elseif($sweets_habit_index == 5){
                    $sweets_habit ='Allergies';
                }else{
                    $sweets_habit ='-';
                }
              

              $returnArr .= "<tr>
                             <td>".$i."</td>
                             <td>".ucfirst($row->given_name)."</td>
                             <td>".$row->rank."</td>
                             <td>".($age)." Y</td>
                             <td>".ucfirst($row->gender)."</td>  
                             <td>".ucwords($row->nationality)."</td>
                             <td>".$meat_habit."</td>  
                             <td>".$pork_habit."</td>
                             <td>".$beef_habit."</td>
                             <td>".$fish_habit."</td>
                             <td>".$mutton_habit."</td>
                             <td>".$chicken_habit."</td>
                             <td>".$egg_habit."</td>
                             <td>".$cereals_habit."</td>
                             <td>".$dairy_habit."</td>
                             <td>".$veg_habit."</td>
                             <td>".$fruits_habit."</td>
                             <td>".$sweets_habit."</td>
                             ";  
             $returnArr .= '</tr>';
             $i++; 
         } 
         //die; 
         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="18" align="center" style="font-weight:bold;font-size:15px;text-align:center">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));    
  }

  function crewEnteriesList($ship_id=''){
   checkUserSession();
   $ship_id = base64_decode($ship_id);
   if(empty($ship_id)){
    $data = getCustomSession('ship_details');
    $ship_id = $data['ship_id'];
   }
   $user_session_data = getSessionData(); 
   $shipDetails = $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R'); 
   $vars['user_session_data'] = $user_session_data;   
   $vars['ship_id'] = $ship_id;
   $vars['heading'] = 'Crew Member Entries Of - '.$shipDetails[0]->ship_name;
   $vars['active'] = "CML";
   $vars['content_view'] = 'crew_entries_list';
   $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
   $vars['shipCrewData'] = $this->cm->getallCrewlist(' and scm.ship_id = '.$ship_id,'R');
   $this->load->view('layout',$vars);   
  }

  function getallCrewEntriesList(){
   checkUserSession();
   $user_session_data = getSessionData();
   $where = '';$order_by='';
   $returnArr = '';
   extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
   
     if(!empty($ship_id)){
       $where .= ' AND (s.ship_id='.$ship_id.')';
     }
   
     if(!empty($keyword)){
         // $where .= " AND scm.family_name like '%".trim($keyword)."%' or scm.given_name like '%".trim($keyword)."%' or scm.rank like '%".trim($keyword)."%' or scm.identity_number like '%".trim($keyword)."%' ";
          $where .= " AND (u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";   
        }
     


     if($created_on){
       $where .= ' AND (date(cme.created_date) = "'.convertDate($created_on,'','Y-m-d').'" )'; 
     }
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Date and Time')
            {
                $order_by = 'ORDER BY date(cme.created_date) '.$sort_type;
            }
            elseif($sort_column == 'Type')
            {
                $order_by = 'ORDER BY cme.arrival_or_departure '.$sort_type;
            }
            elseif($sort_column == 'Imported By')
            {
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
            
        }
        else{
            $order_by = 'ORDER BY cme.created_date DESC';
        }
        
        
        $countdata = $this->cm->getallCrewEntrieslist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $ships = $this->cm->getallCrewEntrieslist($where,'R',$offset,$perPage,$order_by);
        //echo $this->db->last_query();die;
        if($ships){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($ships)).' of '.$countdata.' entries';
         $edit_ship = checkLabelByTask('edit_ship'); 
         foreach ($ships as $row){
                $crew_member_list = "<a href=".base_url()."shipping/CrewMembersList/".base64_encode($row->crew_member_entries_id)." target='_blank'>View List</a>";
                $type = ($row->arrival_or_departure == 'arrival')?'Arrival':'Departure';
              $returnArr .= "<tr>
                             <td width='15%'>".$type."</td> 
                             <td width='21%'>".ConvertDate($row->created_date,'','d-m-Y')."</td>  
                             <td width='27%'>".ucfirst($row->imported_by)."</td> 
                             <td width='21%' >";
                $returnArr .= (!empty($row->e_sign))? "<img src='".base_url()."uploads/e_signature/".$row->e_sign."' height='20' width='150'>":'-';
                $returnArr .= "</td><td width='10%' class='action-td' align='center'><div class='btn-group'>
                                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-ellipsis-v'></i>
                                </button>
                                <ul class='dropdown-menu pull-right'>
                                <li>".$crew_member_list."</li>
                                </ul>
                                </div></td> </tr>"; 
         }
        
         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="15" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));    
  }

 function testPdf($work_order_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
                    $data2 = (array) $this->cm->getDeliveyNoteData(' AND dn.delivery_note_id =15');
                    $vars['dataArr'] = $data2;

         $recept_data = unserialize($data2['json_data']);
            $proPdfArr = array();
            if(!empty($recept_data)){
               for ($i=0; $i <count($recept_data) ; $i++) {
                    $recept_data[$i] = (array) $recept_data[$i];
                         $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$recept_data[$i]['product_id']);
                       $product_id = $product['product_id'];
                       $proPdfArr[$product['sequence']][$product['category_name']][] = array(
                         'category_name'=>$product['category_name'],
                         'product_category_id'=>$product['product_category_id'],
                         'product_name'=>$product['product_name'],
                         'product_id'=>$product_id,
                         'unit'=>$product['unit'],
                         'item_no'=>$product['item_no'],
                         'sequence'=>$product['sequence'],
                         'quantity'=>$recept_data[$i]['quantity'],
                         'unit_price'=> $recept_data[$i]['unit_price'],
                         'remark'=> $recept_data[$i]['remark']
                      );   
                    } 
              } 
              $vars['productArr'] =  $proPdfArr;
        $this->load->view('delivery_note_pdf_email',$vars);
  }

  function victualling_summary($ship_id=''){
     checkUserSession();
     $user_session_data = getSessionData();
     $ship_id = base64_decode($ship_id);
     $vars['ship_id'] = $ship_id;
     if(!empty($ship_id)){
     //  $month = date('m');
     //  $year = date('Y'); 
     //  $vars['valid_button'] = $this->cm->getAllvSummaryReport(' AND vs.ship_id = '.$ship_id.' AND vs.month ='.$month.' AND vs.year ='.$year,'R');
      $vars['opening_stock'] = $this->cm->getShipStockById($ship_id);
     
      }
     $data = $this->load->view('summary_report_list',$vars,true);
     $returnArr = $data;  
     echo json_encode(array('data'=>$returnArr));        

  }

  function getAllSummaryReports($type=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   
    
    if(!empty($shipping_company_id)){
      $where .= ' AND s.shipping_company_id = '.$shipping_company_id;    
      }

     if(!empty($ship_id)){
      $where .= ' AND vs.ship_id = '.$ship_id; 
     }

     if($user_session_data->code=='captain' || $user_session_data->code=='cook'){
      $where .= ' AND vs.ship_id ='.$user_session_data->ship_id; 
     } 
     elseif($user_session_data->code=='shipping_company'){
       $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id; 
     }

    if(!empty($month)){
      $where .= ' AND vs.month = '.$month; 

    }

    if(!empty($year)){
      $where .= ' AND vs.year = '.$year; 

    }

    if(!empty($keyword)){
     $where .= " AND ( s.ship_name like '%".trim($keyword)."%' or u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%') ";   
    }

    if($created_on){
      $where .= ' AND date(vs.created_on) = "'.convertDate($created_on,'','Y-m-d').'"'; 
     }

    if($status){
      if($status=='C'){
        $where .= " AND vs.`status`= 0";         
        
      }
      elseif($status=='S'){
        $where .= " AND vs.`status`= 1";         
      }  
    }

    if(!empty($sort_columnvs) && !empty($sort_typevs)){
    if($sort_columnvs=='Month'){
      $order_by = ' ORDER BY vs.month '.$sort_typevs;
    }
    elseif($sort_columnvs=='Year'){
      $order_by = ' ORDER BY vs.year '.$sort_typevs;
    }
    elseif($sort_columnvs=='Added On'){
      $order_by = ' ORDER BY vs.created_on '.$sort_typevs;
    }
    elseif($sort_columnvs=='Added By'){
      $order_by = ' ORDER BY u.first_name '.$sort_typevs;
    }
    elseif($sort_columnvs=='Ship Name'){
      $order_by = ' ORDER BY s.ship_name '.$sort_typevs;
    }
   }
   else{
    $order_by = ' ORDER BY vs.summary_report_id DESC';
   } 


   if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllvSummaryReport($where,'C');
     $pages = new paginator($countdata, $perPage, $cur_page,$form_label,$form_id);
     $returnData = '';
     // if($pages->tot_pages>1){
            $returnData .= '<div class=""><div class="export_info"><select name="exportPageNoPopUp" id="exportPageNoPopUp" class="form-control" onchange="$(\'#exportPageNo\').val(this.value)">';
            for($i=1;$i<=$pages->tot_pages;$i++){
                $from = ($i * $perPage) - $perPage;
                $to = intval($from) + intval($perPage);
                $from += 1; 
                $to = ($countdata > $to) ? $to : $countdata;
                $returnData .= '<option value="'.$i.'">Export Records From '.$from.' To '.$to.'</option>';
            }
            $returnData .= '</select></div></div>';
      // }

    echo json_encode(array('htmlData'=>$returnData,'countdata'=>$countdata));
    exit;
   }


   if($download==1){
     $cur_page = (isset($exportPageNo) && $exportPageNo>0) ? $exportPageNo : 1;
        $perPage = 500;
        $offset = ($cur_page * $perPage) - $perPage;
        $records_file_name = 'VictuallingSummary';  
        if(isset($exportPageNo) && $exportPageNo>0){
            $from = ($exportPageNo * $perPage) - $perPage;
            $to = intval($from) + intval($perPage);
            $from += 1;
            $to = ($totalExportPages > $to) ? $to : $totalExportPages;
            $records_file_name .= '-Records_From_'.$from.'_To_'.$to;
        }

       $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = $records_file_name.'.xlsx';
           $arrayHeaderData= array('Vessel Name','Month','Year','Created On','Created By');
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
            $summary_report = $this->cm->getAllvSummaryReport($where,'R',$perPage,$offset,$order_by);

            if($summary_report){
              foreach ($summary_report as $row) {
                    $k++;
                    $monthNum  = $row->month;
                    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                    $monthName = $dateObj->format('F');
                    $arrayData[] = array(ucfirst($row->ship_name),ucfirst($monthName),$row->year,ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucfirst($row->user_name));

                 }   
            }
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$records_file_name);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
   }


   $countdata = $this->cm->getAllvSummaryReport($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page);
   $summary_report = $this->cm->getAllvSummaryReport($where,'R',$perPage,$offset,$order_by);
   $submit_victualing_report = checkLabelByTask('submit_victualing_report');
   if($summary_report){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($summary_report)).' of '.$countdata.' entries';
         foreach ($summary_report as $row){ 
            $st = '';  
            $monthNum  = $row->month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March

           $report = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Victualling Summary Report\',\'shipping/add_victualing_report\',\'x\','.$row->summary_report_id.',\'98%\',\'full-width-model\');" >View Report</a>';

           if($row->status === '0'){
            if($submit_victualing_report){
               $st = '<a onclick="update_vsr_status('.$row->summary_report_id.')" style="cursor:pointer">Submit Report</a>';
            }
           }
    
            $returnArr .= "<tr>";
             if($type=='x'){
              $returnArr .="<td width='10%'>".ucfirst($row->ship_name)."</td>";    
             }
             $start_date = "01-".$row->month."-".$row->year;
             $start_time = strtotime($start_date);
             $end_time = strtotime("+28 day", $start_time);
           
            $color = (date('Y-m-d')>=date('Y-m-d',$end_time)) ? 'class="badge badge-danger"' : 'class="badge badge-info"' ; 
            $returnArr .="<td width='10%'>".ucfirst($monthName)."</td>
                              <td width='10%'>".$row->year."</td>
                              <td width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</td>
                              <td width='10%'>".ucfirst($row->user_name)."</td>
                              <td width='10%'>".(($row->status==1) ? '<span class="badge badge-success">Submitted</span>' : '<span '.$color.'>Created</span>')."</td>";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$report.'</li>
                                <li>'.$st.'</li>                                           
                                </ul>
                                </div></td></td></tr>'; 
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

 function add_victualing_report(){
     checkUserSession();
     $user_session_data = getSessionData();
     $summary_report_id = $this->input->post('second_id');
     $ship_details = getCustomSession('ship_details');
     $this->load->model('email_manager');
     $this->em = $this->email_manager;
     if(!empty($summary_report_id)){
       $data = (array) $this->cm->getSummaryReportById(' AND vs.summary_report_id='.$summary_report_id);
       $month =  $data['month'];
       $year = $data['year'];
       $ship_id = $data['ship_id'];        
     }
     else{
        $report_config = getCustomSession('report_config');
        $ship_id = ($report_config['id']) ? $report_config['id'] : $report_config['ship_id'];
        $month = $report_config['month'];   
        $year = $report_config['year'];  
         // $month = date('m');
         // $year = date('Y'); 
         // $ship_id = $this->input->post('id');
     }

     // $this->update_month_stock($ship_id,$month,$year);
     $actionType = $this->input->post('actionType');
     $returnArr['status'] = 100;
     if($actionType=='save'){
       $dataArr['ship_id'] = $ship_id;
       $dataArr['month'] = $month;
       $dataArr['year'] = $year;
       $dataArr['opening_stock'] = trim($this->input->post('opening_stock'));
       $dataArr['received_provision'] = trim($this->input->post('received_provision'));
       $dataArr['condemned'] = trim($this->input->post('condemned'));
       $dataArr['total_man_days'] = trim($this->input->post('total_man_days'));
       $dataArr['daily_rate_per_man'] = trim($this->input->post('daily_rate_per_man'));
       $dataArr['ship_crew'] = trim($this->input->post('ship_crew'));
       $dataArr['overlap'] = trim($this->input->post('overlap'));
       $dataArr['supernumeries'] = trim($this->input->post('supernumeries'));
       $dataArr['owners'] = trim($this->input->post('owners'));   
       $dataArr['charterers'] = trim($this->input->post('charterers'));
       $dataArr['official'] = trim($this->input->post('official'));
       $dataArr['superintendents'] = trim($this->input->post('superintendents'));
       $dataArr['others'] = trim($this->input->post('others'));
       $dataArr['final_man_days'] = trim($this->input->post('final_man_days'));
       $dataArr['victualling_rate'] = trim($this->input->post('victualling_rate'));
       $dataArr['ramaing_on_board'] = trim($this->input->post('ramaing_on_board'));
       $dataArr['consumed'] = trim($this->input->post('consumed'));
       $dataArr['created_on'] = date('Y-m-d H:i:s');
       $dataArr['created_by'] = $user_session_data->user_id;
       // for ($p=0; $p <500 ; $p++) {  
        $this->cm->add_summary_report($dataArr);
       // }

        $whereEm = ' AND nt.code = "vc_report_submit"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
             $noteArr1['date'] = date('Y-m-d H:i:s');
             $noteArr1['title'] = $templateData->title;
             $noteArr1['is_for_master'] = 1;
             $noteArr1['ship_id'] = $ship_details['ship_id'];
             $noteArr1['long_desc'] = str_replace(array('##date##','##ship_name##'),array(ConvertDate($dataArr['year'].'-'.$dataArr['month'],'','M Y'),ucwords($ship_details['ship_name'])),$templateData->body); 
            $this->um->add_notify($noteArr1);
         if(!empty($roles)){
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##date##','##ship_name##'),array(ConvertDate($dataArr['year'].'-'.$dataArr['month'],'','M Y'),ucwords($ship_details['ship_name'])),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }
       $returnArr['status'] = 200;
       $returnArr['returnMsg'] = 'Victualling summary report added successfully';
     }
     
     $vars['condemned_stock'] = $this->cm->getAllCondemnedStockReportData(' AND cr.ship_id = '.$ship_id.' AND cr.month ='.$month.' AND cr.year ='.$year,'R');
          
     $vars['extra_meals'] = $this->cm->getExtraMealDetails(' AND em.ship_id = '.$ship_id.' AND em.month = '.$month.' AND em.year = '.$year);
     
     $vars['ship_stock'] = $this->cm->victualing_transaction(' AND st.ship_id = '.$ship_id.' AND month(dn.date) = '.$month.' AND year(dn.date) = '.$year.' And st.delivery_note_id is not null','R');

     // $vars['stock_value'] = (array) $this->cm->monthly_stock(' AND msv.ship_id = '.$ship_id.' AND msv.month ='.$month.' AND msv.year ='.$year); 

     $vars['stock_value'] = (array) $this->cm->stock_month_value($ship_id,$month,$year); 

     // echo $this->db->last_query();die;
     $vars['ship_id'] = $ship_id;
     $vars['dataArr'] = $this->input->post();
     $data = $this->load->view('add_edit_victualing_report',$vars,true);
     $returnArr['data'] = $data;
     echo json_encode($returnArr); 
  }
  

  function victualling_summary_report(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'VCR';
     if(!empty($user_session_data->shipping_company_id)){
       $swh .= ' AND s.shipping_company_id ='.$user_session_data->shipping_company_id;
     }
     $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
     $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
     $vars['content_view'] = 'vcsummary_report_list'; 
     $this->load->view('layout',$vars);        
  }

function condemned_stock_list($ship_id=''){
     checkUserSession();
     $user_session_data = getSessionData();
     $ship_id = base64_decode($ship_id);
     $vars['ship_id'] = $ship_id;
     if(!empty($ship_id)){
         $vars['opening_stock'] = $this->cm->getShipStockById($ship_id);
      }
     $data = $this->load->view('codemned_stock_list',$vars,true);
     $returnArr = $data;  
     echo json_encode(array('data'=>$returnArr));        

  }

  function extra_meals_report(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'EM';
     $vars['company'] = $this->cm->getAllshippingCompany(' And c.status = 1','R');
     if(!empty($user_session_data->shipping_company_id)){
        $swh = ' AND s.shipping_company_id ='.$user_session_data->shipping_company_id;
     }
     $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
     $vars['content_view'] = 'extra_meals_report'; 
     $this->load->view('layout',$vars);        
  }


  function printDNPdf($delivery_note_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getDeliveyNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
        //print_r($data);die;
        $vars['dataArr'] = $data;
        $vars['view_file'] = 'delivery_note_pdf_email';
        $vars['title'] = 'DN';
        $this->load->view('downloadPdf',$vars);
  }


 function getVesselBySearch(){
    checkUserSession();
    $user_session_data = getSessionData();
    $search = $_REQUEST['search'];
    $company_id = $_REQUEST['company_id'];
    $where = ' and s.status = 1';
    if(!empty($user_session_data->shipping_company_id)){
        $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id;
     }
     
    if(!empty($search)){
        $where .= ' and s.ship_name like "%'.$search.'%"';
    }

    if(!empty($company_id)){
       $where .= ' AND s.shipping_company_id = '.$company_id; 
    }

    $shipData = $this->cm->getAllShips($where,'R','','','order by s.ship_name ASC');
    $total_count = $this->cm->getAllShips($where,'C');
    $shipArr = array();
    if(!empty($shipData)){
        foreach($shipData as $row){
        $shipArr[] = array('id'=>$row->ship_id,'title'=>ucwords($row->ship_name).'('.$row->imo_no.')','text'=>ucwords($row->ship_name).'('.$row->imo_no.')');
        }

    $return= json_encode(array('results'=>$shipArr,'pagination'=>array("more"=> true),'total_count'=>$total_count));             
    }
    echo $return;
}


 function getVendorBySearch(){
    checkUserSession();
    $user_session_data = getSessionData();
    $search = $_REQUEST['search'];
    $where = ' and u.status = 1';

    if(!empty($search)){
        $where .= ' and u.first_name like "%'.$search.'%" or  u.last_name like "%'.$search.'%" or concat(u.first_name," ",u.last_name) like "%'.$search.'%"';
    }

    $vendor = $this->um->getallVendor(' AND u.status = 1','R','','',' ORDER BY u.first_name ASC');
    $total_count = $this->um->getallVendor(' AND u.status = 1','C');
    $vendorArr = array();
    if(!empty($vendor)){
        foreach($vendor as $row){
        $vendorArr[] = array('id'=>$row->vendor_id,'title'=>ucwords($row->vendor_name),'text'=>ucwords($row->vendor_name));
        }

    $return= json_encode(array('results'=>$vendorArr,'pagination'=>array("more"=> true),'total_count'=>$total_count));             
    }
    echo $return;
  }

  function logActivityHtml($ship_id=''){
    checkUserSession();
     $user_session_data = getSessionData();
     $actionType = $this->input->post('actionType');
     $returnArr['status'] = 100;
     $vars['ship_id'] = $ship_id;
     $data = $this->load->view('log_activity',$vars,true);
     $returnArr['data'] = $data;
     echo json_encode($returnArr);

  }

  function getLogActivity(){
    checkUserSession();
    $ship_details = getCustomSession('ship_details');
    $ship_id = $ship_details['ship_id'];
    $where = ' AND l.ship_id ='.$ship_id;
    $log_activity = $this->cm->getLogActivity($where,'R');
    if(!empty($log_activity)){
       foreach ($log_activity as $row) {
         if($row->entity_type=='adjust_inventory'){
          $returnArr .= '<div class="logs"><a href="javascript:void(0)" onclick="showAjaxModel(\'View Details\',\'shipping/stock_update_log\',\''.$row->log_id.'\',\'\',\'98%\',\'full-width-model\')">
           '.(($row->img_url) ? '<img width="50px" height="50px" src="'.base_url().'/uploads/user/'.$row->img_url.'">' : '<img width="50px" height="50px" src="'.base_url().'/uploads/customer.png">' ).'
           <h6>Current Stock Update</h6><br>
           <span>'.ConvertDate($row->added_on,'','d-m-Y | h:i a').' current stock update by '.ucfirst($row->user_name).'<span>
           </a></div>';
          }
       }
    }
    else{
      $returnArr .= '<div align="center" class="col-sm-12 font12">No Data Available</div>';
    }
   echo json_encode(array('dataArr'=>$returnArr)); 
  }

  function stock_update_log(){
   checkUserSession();
   $user_session_data = getSessionData();
   $log_id = $this->input->post('id');
   $returnArr['status'] = 100;
   $data = (array) $this->cm->getLogActivityByID($log_id);
   $productArr = array();
   if(!empty($data)){
     $json_data = unserialize($data['json_data']);
     if(!empty($json_data)){
        for ($i=0; $i < count($json_data); $i++) { 
         $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$json_data[$i]['product_id']);
         $product_id = $product['product_id']; 
         $productArr[$product['sequence']][$product['category_name']][] = array(
              'category_name'=>$product['category_name'],
              'product_category_id'=>$product['product_category_id'],
              'product_name'=>$product['product_name'],
              'product_id'=>$product_id,
              'unit'=>$product['unit'],
              'item_no'=>$product['item_no'],
              'sequence'=>$product['sequence'],
              'type' =>$json_data[$i]['type'],
              'reason' =>$json_data[$i]['reason'],
              'past_qty' => $json_data[$i]['past_qty'],
              'qty' =>$json_data[$i]['qty']
         );
        }
     }
   }
    $vars['productArr'] = $productArr;
    $data = $this->load->view('current_stock_log',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }
  

  function update_vsr_status(){
    checkUserSession();
    $report_id = trim($this->input->post('id'));
    $returnArr['status'] = 100;
    if(!empty($report_id)){
      $this->db->update('victualing_summary',array('status'=>1),array('summary_report_id'=>$report_id));
      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Victualing summary report submitted successfully'; 
     }

    echo json_encode($returnArr); 
  }


 function submit_open_stock(){
   checkUserSession();
   $user_session_data = getSessionData();
   $ship_details = getCustomSession('ship_details');
   $ship_stock_id = trim($this->input->post('id'));
   $ship_id = $ship_details['ship_id'];
   $returnArr['status'] = 100;
    if(!empty($ship_stock_id)){ 
        $where = ' AND sst.ship_stock_id='.$ship_stock_id;
        $data = (array) $this->mp->getStockDetail($where);
        $json_data = unserialize($data['json_data']);
        $a = array();
         for ($j=0; $j < count($json_data); $j++){
          $a[$json_data[$j]['product_id']] = $json_data[$j];  
         }

        $product_ids = array_keys($a); 
        $product_ids = implode(',',$product_ids);
        $dataArr['month']  = $data['month'];
        $dataArr['year']  = $data['year'];
        $dataArr['ship_id']  = $ship_id;
        $month_stock_id = $this->cm->add_month_stock($dataArr);
        // echo $this->db->last_query();die;
        $current_stock_details = array();
         if($product_ids){
            $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.$product_ids.')','R');
             $opening_meat = 0;
             $opening = 0; 
             if(!empty($products)){
              foreach ($products as $row) {
                  $opening += $a[$row->product_id]['quantity'] * $a[$row->product_id]['unit_price'];

                 if($row->group_name == 'Meat'){
                     $opening_meat += $a[$row->product_id]['quantity'];  
                 }

                $current_stock_details[] = array('product_id'=>$row->product_id,'total_stock'=>$a[$row->product_id]['quantity'],'unit_price'=>$a[$row->product_id]['unit_price'],'available_stock'=>$a[$row->product_id]['quantity'],'month_stock_id'=>$month_stock_id);

             }
          } 
      }

      $this->db->insert_batch('monthly_stock_details',$current_stock_details);
      $this->db->update('ship_stock',array('is_submit'=>1),array('ship_stock_id'=>$ship_stock_id));
      $this->db->update('month_stock',array('opening_price'=>$opening,'opening_meat_qty'=>$opening_meat),array('month_stock_id'=>$month_stock_id));
      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Opening stock submitted successfully';
    }
    echo json_encode($returnArr);
  } 

  function stock_month(){
    checkUserSession();
    $year = $this->input->post('year');
    $ship_id = $this->input->post('ship_id');
    $returnArr = '<option value="">Month</option>';
    if(!empty($year) && !empty($ship_id)){
      $months = $this->cm->stock_month($ship_id,$year);
       foreach ($months as $row) {
         $monthNum  = $row->month;
         $dateObj   = DateTime::createFromFormat('!m', $monthNum);
         $monthName = $dateObj->format('F');
         $selected = (date('m')==$row->month) ? 'selected' : '';
         $returnArr .= '<option '.$selected.' value="'.$row->month.'">'.$monthName.'</option>';  
       }
    }
    echo json_encode(array('data'=>$returnArr));
  }

  function download_feedback_pdf($delivery_note_id){
         require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getfeedbackByID(base64_decode($delivery_note_id));
        $vars['data'] = $data;
        $vars['view_file'] = 'feedback_pdf';
        $vars['title'] = 'Feedback';
        $this->load->view('downloadPdf',$vars);
  }

   // ------------------------- only for live data update ----------------
   function add_month_stock(){
     $stock_list = $this->cm->getAllStockList(' AND st.delivery_note_id IS NULL','R');
     if(!empty($stock_list)){
        foreach ($stock_list as $row) {
              $dataArr['month']  = convertDate($row->created_on,'','m');
              $dataArr['year']  = convertDate($row->created_on,'','Y');
              $dataArr['ship_id']  = $row->ship_id;
              
              $month_stock_id = $this->cm->add_month_stock($dataArr);

              $total_price = 0;
              $json_data = unserialize($row->json_data);

              $a = array();
              for ($j=0; $j < count($json_data); $j++){
                  $a[$json_data[$j]['product_id']] = $json_data[$j];  
              }

              $product_ids = array_keys($a); 
              $product_ids = implode(',',$product_ids);
               
              $arrData = array(); 
              if($product_ids){
                $products = $this->mp->getAllProduct(' AND p.status = 1 AND p.product_id in ('.$product_ids.')','R');
                 $opening_meat = 0;
                 $opening = 0; 
                 if(!empty($products)){
                  foreach ($products as $row) {
                    $opening += $a[$row->product_id]['quantity'] * $a[$row->product_id]['unit_price'];

                     if($row->group_name == 'Meat'){
                         $opening_meat += $a[$row->product_id]['quantity'];  
                     }

                    $arrData[] = array('month_stock_id'=>$month_stock_id,'product_id'=>$row->product_id,'total_stock'=>$a[$row->product_id]['quantity'],'unit_price'=>$a[$row->product_id]['unit_price'],'available_stock'=>$a[$row->product_id]['quantity']);     
                   }
                }
            }

           $this->db->insert_batch('monthly_stock_details',$arrData);  
           $this->db->update('ship_stock',array('stock_date'=>convertDate($row->created_on,'','Y-m-d'),'is_submit'=>1),array('ship_stock_id'=>$row->ship_stock_id));
           $this->db->update('month_stock',array('opening_price'=>$opening,'opening_meat_qty'=>$opening_meat),array('month_stock_id'=>$month_stock_id));

        } 
     }

     $this->db->query('DELETE FROM ship_stock WHERE delivery_note_id IS NOT NULL');
     $this->db->query('DELETE FROM victualing_summary');
     $this->db->query('DELETE FROM consumed_stock');
     $this->db->query('DELETE FROM consumed_stock_details');
     
     $delivery_note = $this->cm->getAllDeliveryNote('','R');
      if(!empty($delivery_note)){
        foreach ($delivery_note as $val) {
          $this->db->update('delivery_note',array('date'=>convertDate($val->added_on,'','Y-m-d')),array('delivery_note_id'=>$val->delivery_note_id)); 
        }  
      }

     
    $invoice_list = $this->cm->getAllInvoiceList('','R');
    
    if(!empty($invoice_list)){
        foreach ($invoice_list as $vall) {
          $this->db->update('company_invoice',array('invoice_date'=>convertDate($vall->created_at,'','Y-m-d')),array('company_invoice_id'=>$vall->company_invoice_id)); 
        }  
    }         
      
    
     echo 'Opening Stock Added !';
   } 
 // ------------------------- only for live data update ----------------

    function printDeliveryNotePdf($delivery_note_id){
        require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getDeliveyNoteData(' AND dn.delivery_note_id ='.$delivery_note_id);
            $vars['dataArr'] = $data;
         $recept_data = unserialize($data['json_data']);
         $productArr = array();
            if(!empty($recept_data)){
               for ($i=0; $i <count($recept_data) ; $i++) {
                    $recept_data[$i] = (array) $recept_data[$i];
                         $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$recept_data[$i]['product_id']);
                       $product_id = $product['product_id'];
                       $productArr[$product['sequence']][$product['category_name']][] = array(
                         'category_name'=>$product['category_name'],
                         'product_category_id'=>$product['product_category_id'],
                         'product_name'=>$product['product_name'],
                         'product_id'=>$product_id,
                         'unit'=>$product['unit'],
                         'item_no'=>$product['item_no'],
                         'sequence'=>$product['sequence'],
                         'quantity'=>$recept_data[$i]['quantity'],
                         'unit_price'=> $recept_data[$i]['unit_price'],
                         'remark'=> $recept_data[$i]['remark']
                      );   
                    } 
               }  
        $vars['productArr'] = $productArr;
        $vars['view_file'] = 'delivery_note_pdf_email';
        $vars['title'] = 'Delivery Note';
        $this->load->view('downloadPdf',$vars);
  }


    }
?>