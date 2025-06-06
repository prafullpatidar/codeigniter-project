<?php
 class Vendor extends CI_Controller {
  function __construct(){
      parent::__construct();
      $this->load->model('company_manager');
      $this->cm = $this->company_manager;
      
      $this->load->model('user_manager');
      $this->um = $this->user_manager;

      $this->load->model('manage_vendor');
      $this->vm = $this->manage_vendor;

      $this->load->model('manage_product');
      $this->mp = $this->manage_product;

      $this->load->library('querybundel');
      $this->qb = $this->querybundel;

  }

    function index(){
       checkUserSession();
       $user_session_data = getSessionData();    
       $vars['user_session_data'] = $user_session_data;   
       $vars['active'] = 'VL';
       $vars['heading'] = 'Vendor List';
       $vars['content_view'] = 'vendor_list';
       $this->load->view('layout',$vars);   
    }
    
    function getallvendor(){
        checkUserSession();
        $user_session_data = getSessionData();
        $where = '';
        $returnArr = '';
        extract($this->input->post());
        $cur_page   = $page ? $page : 1;
        $perPage    = $perPage ? $perPage : 25;
        if(!empty($keyword)){
         $where .= " AND (u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' or u.email like '%".trim($keyword)."%' or u.phone like '%".trim($keyword)."%' or u.address like '%".trim($keyword)."%' or u.country like '%".trim($keyword)."%')";   
        }

        if(!empty($status)){
            $status = trim($status);
            if ($status == 'A'){
                $where .= " AND u.`status`='1' ";
            }elseif ($status == 'D'){
                $where .= " AND u.`status`='0' ";              
            } 
        }

        if($created_on){
          $where .= ' AND date(u.created_date) = "'.convertDate($created_on,'','Y-m-d').'"'; 
        } 

        if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Customer'){
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }elseif($sort_column == 'Address'){
                $order_by = 'ORDER BY u.address '.$sort_type;
            }elseif($sort_column == 'Date'){
                $order_by = 'ORDER BY u.created_date '.$sort_type;
            }elseif($sort_column == 'Email'){
                $order_by = 'ORDER BY u.email '.$sort_type;
            }
            elseif($sort_column == 'Country'){
                $order_by = 'ORDER BY u.country '.$sort_type;
            }
            elseif($sort_column == 'Phone'){
                $order_by = 'ORDER BY u.phone '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY u.first_name ASC';
        }
        
        if($download==1){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'VendorList.xlsx';
           $arrayHeaderData= array('Name','Email','Phone','Address','Country','Created On','Status');
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
          $vendor = $this->um->getallVendor($where,'R','','',$order_by);
          if($vendor){
            foreach ($vendor as $row) {
               $k++;
               $status = ($row->status==1) ? 'Activate' : 'Deactivate';   
               $arrayData[] = array(ucwords($row->vendor_name),$row->email,$row->phone,$row->address,ucwords($row->country),convertDate($row->created_date,'','d-m-Y'),$status); 
            }
           }
          $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN')) 
                  );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'VendorList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   
        }

        $countdata = $this->um->getallVendor($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $vendor = $this->um->getallVendor($where,'R',$perPage,$offset,$order_by);
        // echo $this->db->last_query();die;
        if($vendor){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($vendor)).' of '.$countdata.' entries';
          $edit_vendor = checkLabelByTask('edit_vendor');
         foreach ($vendor as $row){
           
            if($edit_vendor){
             if($row->status == 0){
                $Status = '<a onclick="updateStatusBox('.$row->user_id.','.$row->status.',\''.$row->vendor_name.'\',\'vendor/changestatusvendor\')" href="javascript:void(0)">Activate</a>';   
             }else{
                $Status = '<a onclick="updateStatusBox('.$row->user_id.','.$row->status.',\''.$row->vendor_name.'\',\'vendor/changestatusvendor\')" href="javascript:void(0)">Deactivate</a>';      
             }
             
              $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Vendor\',\'vendor/add_edit_vendor\','.$row->user_id.',\'\',\'70%\');" >Edit</a>';
              
              // $changePassword = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Change Password\',\'user/changeUserPassword\','.$row->user_id.');" >Change Password</a>';
             
              }

              $pdf = '';
              
              if(!empty($row->vendor_pdf)){
                $pdf = '<a href="'. base_url().'uploads/vendor_pdf/'.$row->vendor_pdf.'" target="_blank">Download PDF</a>'; 
              }

              // $delete = '<a href="javascript:void(0);" onclick="updateStatusBoxDelete('.$row->user_id.',\'0\',\''.$row->vendor_name.'\',\'vendor/deletevendor\');" >Delete</a>';
              

              $image =  ($row->img_url !='') ? "<img class='list-img' src=".base_url()."uploads/user/".$row->img_url." width='50px' height='50px'>": "<img class='list-img' src=".base_url("uploads/customer.png")." width='50px' height='50px'>";  
              
              $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
              
              $returnArr .= "<tr><td width='3%'>".$image."</td>
                              <td width='10%'>".ucfirst($row->vendor_name)."</td>
                              <td width='10%'>".($row->email)."</td>
                              <td width='10%'>".ucfirst($row->phone)."</td>
                              <td width='10%'>".ucwords($row->address)."</td>
                              <td width='10%'>".ucfirst($row->country)."</td>
                              <td width='10%'>".ConvertDate($row->created_date,'','d-m-Y')."</td>
                              <td width='5%'>".$status."</td>";
                $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li>'.$Status.'</li>
                                <li>'.$pdf.'</li>
                                <li>'.$changePassword.'</li>
                                </ul>
                                </div></td> </tr>';
         }

        if($countdata <= 5){
           $returnArr .= "<tr><td width='3%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='5%'></td><td width='2%'></td></tr>";
           $returnArr .= "<tr><td width='3%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='5%'></td><td width='2%'></td></tr>"; 
           $returnArr .= "<tr><td width='3%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='5%'></td><td width='2%'></td></tr>";
           $returnArr .= "<tr><td width='3%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='5%'></td><td width='2%'></td></tr>"; 
           $returnArr .= "<tr><td width='3%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='5%'></td><td width='2%'></td></tr>"; 
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

  function add_edit_vendor(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    if(!empty($id)){
      $where = "And u.user_id = ".$id;
      $data = $this->um->getuserdatabyid($where);
       $data = get_object_vars($data);
    }
      extract($this->input->post());

    if($action=='save'){
     
     if($this->validate_vendor()){
      $date = date('Y-m-d H:i:s');
      $dataArr = array();
      if($id == ''){
        $pass = md5($c_password);
        $dataArr = array('first_name'=>$first_name,'last_name'=>$last_name,'user_name'=>$user_name,'email'=>$email,'password'=>$pass,'phone'=>$phone,'address'=>$address,'created_by'=>$user_session_data->user_id,'created_date'=>date('Y-m-d H:i:s'),'country'=>$country,'state'=>$state,'city'=>$city,'zipcode'=>$zipcode,'is_vendor'=>1);
            
            if(!empty($_FILES['img']['name'])) {
              $file = $_FILES['img']['name'];
              $upload_data =  doc_upload($file, 'user');
              $dataArr['img_url'] =  $upload_data['file_name'];

             }

             if(!empty($_FILES['vendor_pdf']['name'])){
                  $pdf_file_name = $_FILES['vendor_pdf']['name'];
                  $pdf_data = pdf_upload($pdf_file_name, 'vendor_pdf');
                  $venArr['vendor_pdf'] = $pdf_data['file_name'];  
              }

           $user_id = $this->um->addedituser('user',$dataArr);
           $venArr['user_id'] = $user_id;
           $venArr['currency'] = $currency;
           $venArr['payment_term'] = $payment_term;
           $venArr['bank_name'] = $bank_name;
           $venArr['holder_name'] = $holder_name;
           $venArr['ac_number'] =$ac_number;
           $venArr['ifsc_code'] = $ifsc_code;
           $venArr['ibn_number'] = $ibn_number;  
           $venArr['swift_code'] = $swift_code;  
           $venArr['bank_address'] = $bank_address;  
           $this->um->addVendor($venArr); 
         $role = $this->um->getuserRolebyCode('vendor');
         $roleArr = array('user_id'=>$user_id,'role_id'=>$role->role_id);

        $this->um->addUserRole('user_role',$roleArr);
        $this->session->set_flashdata('succMsg','New Vendor added successfully.');
        $returnArr['status'] = '101';
      }else{
 
        $dataArr = array('first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'phone'=>$phone,'address'=>$address,'country'=>$country,'state'=>$state,'city'=>$city,'zipcode'=>$zipcode);
         
         if(!empty($_FILES['img']['name'])) {
          $file = $_FILES['img']['name'];
          $upload_data =  doc_upload($file, 'user');
          $dataArr['img_url'] =  $upload_data['file_name'];
          unlink(FCPATH.'uploads/user/'.$data['img_url']);  
         }

         if(!empty($_FILES['vendor_pdf']['name'])){
              $pdf_file_name = $_FILES['vendor_pdf']['name'];
              $pdf_data = pdf_upload($pdf_file_name, 'vendor_pdf');
              $venArr['vendor_pdf'] = $pdf_data['file_name'];
              unlink(FCPATH.'uploads/vendor_pdf/'.$data['vendor_pdf']);  
          }
         $venArr['currency'] = $currency;
         $venArr['payment_term'] = $payment_term;
         $venArr['bank_name'] = $bank_name;
         $venArr['holder_name'] = $holder_name;
         $venArr['ac_number'] =$ac_number;
         $venArr['ifsc_code'] = $ifsc_code;
         $venArr['ibn_number'] = $ibn_number;  
         $venArr['swift_code'] = $swift_code;  
         $venArr['bank_address'] = $bank_address;  
        $this->um->editVendor($venArr,array('user_id'=>$id)); 
        $this->um->updateuser($dataArr,$id);
        $this->session->set_flashdata('succMsg','Vendor updated successfully.');
        $returnArr['status'] = '102';
        
       }
     }
    }

    if(!empty($id)){
     $vars['dataArr'] = $data;
    }else{
     $vars['dataArr'] = $this->input->post();
    }
    $data = $this->load->view('add_edit_vendor',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr);             
  }

  function validate_vendor(){
        $user_id = $this->input->post('id');
         $this->form_validation->set_rules('first_name','First Name','trim|required');
         $this->form_validation->set_rules('last_name','Last Name','trim|required');
         if(empty($user_id)){
         $this->form_validation->set_rules('user_name','User Name','trim|required|is_unique[user.user_name]');    
         $this->form_validation->set_rules('password','Password','trim|required');    
         $this->form_validation->set_rules('c_password','Confirm Password','trim|required|matches[password]');            
         }
         $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_email_auth');
         // $this->form_validation->set_rules('phone','Phone','trim|required');
         // $this->form_validation->set_rules('address','Address','trim|required');      
         $this->form_validation->set_rules('currency','Currency','trim|required');
         $this->form_validation->set_rules('payment_term','Payment Term','trim|required');        
         $this->form_validation->set_rules('vendor_pdf','Vendor PDF','callback_pdf_check');    
         $this->form_validation->set_rules('img','Profile Picture','callback_pic_check');    
         $this->form_validation->set_error_delimiters('<p class="error" style="display: inline;">','</p>');
         return  $this->form_validation->run();  
  }

  function email_auth(){
     $user_id = $this->input->post('id');
     $email = $this->input->post('email');
     $data = $this->um->check_email($user_id);
     foreach ($data as $row){
         if($row->email != $email){
             return true;
         }else{
          $this->form_validation->set_message('email_auth', 'Duplicate email address.');
          return false;
          die;
         }

     }    
    }

  function changestatusvendor(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $status = ($status== '1' )? '0' :'1';
        $this->um->updateuser(array('status'=>$status),$id);
        echo $this->db->last_query();die;
        $this->session->set_flashdata('succMsg','Vendor status changed successfully.');  
    }

    function deletevendor(){
        $id = $this->input->post('id');
        $result = $this->um->updateuser(array('is_deleted'=>1),$id);
        $this->session->set_flashdata('succMsg','Vendor Deleted successfully.');
  }


  function pdf_check($str){
        $id = $this->input->post('id');
        $allowed_mime_type_arr = array('application/pdf');
        $mime = get_mime_by_extension($_FILES['vendor_pdf']['name']);
        if(isset($_FILES['vendor_pdf']['name']) && $_FILES['vendor_pdf']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('pdf_check', 'Only PDF file is accepted.');              
                return false;
            }
        }
        else{
           if(empty($id)){  
              $this->form_validation->set_message('pdf_check', 'Please choose a file to upload.');
              return false;
           }
        }
    }
   
   function pic_check($str){
    if(!empty($_FILES["img"]["name"])){
        $allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "JPEG", "GIF", "PNG");
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $extension = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
        $detectedType = exif_imagetype($_FILES['img']['tmp_name']);
        $type = $_FILES['img']['type'];
        if (!in_array($detectedType, $allowedTypes)) {
            $this->form_validation->set_message('pic_check', 'Invalid Image Content!');
            return FALSE;
        }else{
            return TRUE;
        }
     }
     else{
        return true;
     }   
      
    }
   

   function vendor_order(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'OL';
     $vars['ships'] = $this->cm->getAllShips(" AND s.status= 1",'R');
     $vars['content_view'] = 'vendor_order_list';
     $this->load->view('layout',$vars);
   }


   function getallVendorOrderList(){
     checkUserSession();
     $user_session_data = getSessionData();
     $where = '';
     $returnArr = '';
     extract($this->input->post());
     $cur_page   = $page ? $page : 1;
     $perPage    = $perPage ? $perPage : 25;
     if(!empty($user_session_data->vendor_id)){
       $where = ' AND vq.vendor_id = '.$user_session_data->vendor_id;
     }

     if(!empty($keyword)){
       $where .= " AND ( so.rfq_no like '%".trim($keyword)."%' ) ";   
     }

     if(!empty($status)){
        $status = trim($status);
         if($status == 'A'){
            $where .= " AND vq.`status`= 1 ";
          }
         elseif ($status == 'D'){
            $where .= " AND vq.`status`= 2 ";              
          } 
     }

     if($ship_id){
        $where .= ' AND s.ship_id IN ('.implode(',',$ship_id).')';
     }

     if($created_date){
        $where .= ' AND date(vq.added_on) = "'.convertDate($created_date,'','Y-m-d').'"';
     }

     if($expire_date){
        $where .= ' AND vq.expire_date = "'.convertDate($expire_date,'','Y-m-d').'"';  
     }
     
     if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Ship Name'){
                $order_by = 'ORDER BY s.ship_name '.$sort_type;
            }
            elseif($sort_column == 'RFQ No'){
                $order_by = 'ORDER BY so.rfq_no '.$sort_type;
            }
            elseif($sort_column == 'Created Date'){
                $order_by = 'ORDER BY vq.added_on '.$sort_type;
            }
            elseif($sort_column == 'Expire Date'){
                $order_by = 'ORDER BY vq.expire_date '.$sort_type;
            }
            elseif($sort_column == 'Added By'){
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY vq.added_on Desc';
        }
        
       if($download){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'RFQLIST.xlsx';
           $arrayHeaderData= array('Vessel Name','RFQ No.','Date & Time','Expire Date','Status','Stage');
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
           $order = $this->vm->getallVendorOrder($where,'R','','',$order_by);
           $k = 7; 
           if(!empty($order)){
                foreach ($order as $row) {
                   $k++;
                  $status = $stage = '';
                  $status = ($row->status==1) ? 'Pending' : 'Submitted';
                   if($row->expire_date>=date('Y-m-d') && $row->stage==4){
                         $stage = 'Open';
                      }
                   elseif($row->expire_date>=date('Y-m-d') && $row->stage>4){
                         $stage = 'Close';
                      }
                   elseif($row->expire_date<date('Y-m-d')){
                         $stage = 'Expire';
                      } 
                  $arrayData[] = array(ucwords($row->ship_name),$row->rfq_no,ConvertDate($row->added_on,'','d-m-Y | h:i A'),ConvertDate($row->expire_date,'','d-m-Y'),$status,$stage);   
                }
           }

            $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'RFQLIST');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
       } 
       
       // $where .= ' AND vq.vendor_quote_id = 143';  
      $countdata = $this->vm->getallVendorOrder($where,'C');
      $offset = ($cur_page * $perPage) - $perPage;
      $pages = new Paginator($countdata,$perPage,$cur_page);
      $order = $this->vm->getallVendorOrder($where,'R',$perPage,$offset,$order_by);

       if($order){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($order)).' of '.$countdata.' entries';
         foreach ($order as $row){
          $status = $import = '';
          $add_quote = '';
          $status = ($row->status==1) ? '<label style="color:red">Pending</label>' : '<label style="color:green">Submitted</label>';

          
          if($row->status==1 && $row->expire_date>=date('Y-m-d') && ($row->stage==3||$row->stage==4)){ 
           $add_quote = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Add/Edit Quote\',\'vendor/add_edit_quote\','.$row->vendor_quote_id.',\'\',\'98%\',\'full-width-model\');" >Quote (Add/Edit) </a>';
           $import = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Import Quotation\',\'vendor/import_quote/vendor\','.$row->ship_order_id.','.$row->vendor_quote_id.',\'50%\');" >Import Quote</a>';
          }
          else{
           $add_quote = '<a href="javascript:void(0);" onclick="showAjaxModel(\'View Quote Details\',\'vendor/add_edit_quote\','.$row->vendor_quote_id.',\'view\',\'98%\',\'full-width-model\');" >View Details</a>';
          }

          $stage = '';
          if($row->expire_date>=date('Y-m-d') && ($row->stage==3|| $row->stage==4)){
             $stage = 'Open';
          }
          elseif($row->expire_date>=date('Y-m-d') && $row->stage>4){
             $stage = 'Close';
          }
          elseif($row->expire_date<date('Y-m-d')){
             $stage = 'Expire';
          }

         //$download = '<a href="'.base_url().'vendor/download_rfq/'.base64_encode($row->vendor_quote_id).'">Download RFQ</a>';

        $download = '<a href="'.base_url().'shipping/download_rfq_xls/'.base64_encode($row->ship_order_id).'">Download RFQ</a>';

           $returnArr .= "<tr><td width='10%' style='width:10%'>".ucwords($row->ship_name)."</td><td width='10%'>".$row->rfq_no."</td>"; 
           $returnArr .= "<td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | h:i A')."</td>"; 
           $returnArr .= "<td width='10%'>".ConvertDate($row->expire_date,'','d-m-Y')."</td>";
           $returnArr .= "<td width='10%'>".ucwords($row->user_name)."</td>";  
           $returnArr .= "<td width='10%'>".$status."</td>"; 
           $returnArr .= "<td width='10%'>".$stage."</td>"; 
           $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$download.'</li>
                                <li>'.$add_quote.'</li>
                                <li>'.$import.'</li>
                                </ul>
                                </div></td> </tr>';  
       
         }

         if($countdata <= 5){
            $returnArr .= "<tr><td><br></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            $returnArr .= "<tr><td><br></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            $returnArr .= "<tr><td><br></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            $returnArr .= "<tr><td><br></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            $returnArr .= "<tr><td><br></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

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

   function download_rfq(){
    echo 'under devlopment';die;
   }
  
  function add_edit_quote($type='save'){
   checkUserSession();
   $returnArr['status'] = 100;
   $this->load->model('email_manager');
   $this->em = $this->email_manager; 
   $user_session_data = getSessionData();
   $vendor_quote_id = $this->input->post('id');
   $actionType = $this->input->post('actionType');
   if(!empty($vendor_quote_id)){
     $data = (array) $this->vm->getVendorOrderDetails(' and vq.vendor_quote_id = '.$vendor_quote_id);
     $arrData = unserialize($data['order_data']);
     $vendor_data = unserialize($data['vendor_data']);
     $port_name = $data['port_name']; 
   } 
   
   $vdata = array(); 
   if($vendor_data){
     for ($k=0; $k < count($vendor_data); $k++) { 
      $vdata[$vendor_data[$k]['product_id']] = array('quantity'=>$vendor_data[$k]['quantity'],'unit_price'=>$vendor_data[$k]['unit_price'],'remark'=>$vendor_data[$k]['remark'],'attechment'=>$vendor_data[$k]['attechment']); 
     }
   }
 
    if($actionType=='save'){
       if($this->validate_quote($vendor_quote_id)){
        $dataArr['updated_by'] = $user_session_data->user_id;
        $dataArr['updated_on'] = date('Y-m-d H:i:s');
        $dataArr['status'] = 1;  
        // $dataArr['lead_time'] = convertDate(trim($this->input->post('lead_time')),'','Y-m-d H:i:s'); 
        $dataArr['lead_time'] = trim($this->input->post('lead_time')); 
         
         $tmpArr = array(); 
            if(!empty($arrData)){
              for ($i=0; $i <count($arrData) ; $i++) {
               $qty = $this->input->post('qty_'.$arrData[$i]['product_id']);
               $unit_price = $this->input->post('unit_price_'.$arrData[$i]['product_id']);
               $remark = $this->input->post('remark_'.$arrData[$i]['product_id']);
               $img_url = ''; 
                if(!empty($_FILES['img_'.$arrData[$i]['product_id']]['name'])) {
                    $file_name = $_FILES['img_'.$arrData[$i]['product_id']]['name'];
                    $config['upload_path'] = FCPATH.'uploads/vendor_quote/';
                    assert(file_exists($config['upload_path']) === TRUE);
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['max_size'] = 5120;
                    $config['file_name'] = $file_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if($this->upload->do_upload('img_'.$arrData[$i]['product_id'])){
                     $upload_data = $this->upload->data();   
                    }

                    $img_url = $upload_data['file_name']; 
                 }
                 else{
                  $img_url =  $vdata[$arrData[$i]['product_id']]['attechment'];
                 }

               if(!empty($qty) && !empty($unit_price)){
                  $tmpArr[] = array('vendor_quote_id'=>$vendor_quote_id,'product_id'=>$arrData[$i]['product_id'],'quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty*$unit_price),'remark'=>$remark,'attechment'=>$img_url);
               }

              }  
            }

           if($type=='submit'){
             $dataArr['status'] = 2;
             // $to = $this->config->item('admin_email');
             $whereEm = ' AND em.template_code = "quote_received"';
             $emailTemplateData = $this->um->getEmailTemplateByCode($whereEm);
             if(!empty($emailTemplateData)){
             $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
             if(!empty($email_roles)){
               foreach ($email_roles as $row) {
                  $user_list = $this->em->getUserByRoleID($row->role_id);
                   if(!empty($user_list)){
                       foreach ($user_list as $val) {
                           $emArr['user_id'] = $val->user_id;
                           $emArr['subject'] = str_replace(array('##ship_name##','##rfq_no##'),array(ucwords($data['ship_name']),$data['rfq_no']),$emailTemplateData->email_subject);
                           $emArr['body'] = str_replace(array('##user_name##','##phone##','##address##','##email##'),array(ucfirst($user_session_data->first_name).' '.ucfirst($user_session_data->last_name),$user_session_data->phone,$user_session_data->address,$user_session_data->email),$emailTemplateData->email_body);
                           $this->em->add_email_log($emArr);
                       }
                    }
                  } 
               }
           }


            $whereEm = ' AND nt.code = "quote_received"';
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
                       $noteArr['row_id'] = $data['ship_order_id'];
                       $noteArr['entity'] = 'rfq';
                       $noteArr['long_desc'] = str_replace(array('##rfq_no##','##vendor_name##'),array($data['rfq_no'],ucwords($user_session_data->first_name.' '.$user_session_data->last_name)),$templateData->body); 
                        $this->um->add_notify($noteArr); 
                        echo $this->db->last_query();die;  
                     }
                   } 
                } 
             }
           }



             
             // if(!empty($emailTemplateData) && $this->config->item('send_to_admin')) 
             //     {
             //       $subject = str_replace(array('##ship_name##','##rfq_no##'),array(ucwords($data['ship_name']),$data['rfq_no']),$emailTemplateData->email_subject); 
             //       $body = str_replace(array('##user_name##','##phone##','##address##','##email##'),array(ucfirst($user_session_data->first_name).' '.ucfirst($user_session_data->last_name),$user_session_data->phone,$user_session_data->address,$user_session_data->email),$emailTemplateData->email_body);
             //        $this->user_manager->sendMail($to,$subject,$body);
             //    }
             
           }  

          $dataArr['json_data'] = serialize($tmpArr);
          $this->db->update('vendor_quotation',$dataArr,array('vendor_quote_id'=>$vendor_quote_id));
          $this->db->delete('vendor_quotation_details',array('vendor_quote_id'=>$vendor_quote_id));
          $this->db->insert_batch('vendor_quotation_details',$tmpArr); 
          $returnArr['status'] = 200;
          $returnArr['returnMsg'] = 'Vendor quatation updated successfully.'; 
       }
    }

    if(!empty($arrData)){
         $productArr = array();
         for ($i=0; $i <count($arrData) ; $i++) { 
            $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$arrData[$i]['product_id']);
            if($actionType=='save'){
                $vendor_qty = $this->input->post('qty_'.$product['product_id']);
                $unit_price = $this->input->post('unit_price_'.$product['product_id']);
                $remark = $this->input->post('remark_'.$product['product_id']);
            }else{
                $vendor_qty= $vdata[$product['product_id']]['quantity'];
                $unit_price=$vdata[$product['product_id']]['unit_price'];
                $remark=$vdata[$product['product_id']]['remark'];
                $attechment = $vdata[$product['product_id']]['attechment'];
            }

            $productArr[$product['sequence']][$product['category_name']][] = array(
                'category_name'=>$product['category_name'],
                'product_category_id'=>$product['product_category_id'],
                'product_name'=>$product['product_name'],
                'product_id'=>$product['product_id'],
                'quantity'=>$arrData[$i]['quantity'],
                'remark'=>$arrData[$i]['remark'],
                'unit'=>$product['unit'],
                'item_no'=>$product['item_no'],
                'sequence'=>$product['sequence'],
                'vendor_qty'=>$vendor_qty,
                'unit_price'=>$unit_price,
                'vendor_remark'=>$remark,
                'attechment' =>$attechment
            ); 
         }
       } 
   ksort($productArr);
   // $vars['lead_time'] = ($this->input->post('actionType')=='save') ? $this->input->post('lead_time') : convertDate($data['lead_time'],'','d-m-Y h:i a');
   $vars['lead_time'] = ($this->input->post('actionType')=='save') ? $this->input->post('lead_time') : $data['lead_time'];
   
   $vars['productArr'] = $productArr;
   $vars['vendor_quote_id'] = $vendor_quote_id;
   $vars['port_name'] = $port_name;
   $vars['second_id'] = $this->input->post('second_id');
   $data = $this->load->view('add_edit_quote',$vars,true);
   $returnArr['data'] = $data;
   echo json_encode($returnArr);
  }

  function validate_quote($vendor_quote_id){
     $data = (array) $this->vm->getVendorOrderDetails(' and vq.vendor_quote_id = '.$vendor_quote_id);
        $arrData = unserialize($data['order_data']);
        $all_fld_emty = true;
         $this->form_validation->set_rules('actionType','actionType','trim|required');
         $this->form_validation->set_rules('lead_time','Lead Time','trim|required|is_natural_no_zero');
         
        if(!empty($arrData)){
          for ($i=0; $i <count($arrData) ; $i++) {
           $qty = $this->input->post('qty_'.$arrData[$i]['product_id']);
           $unit_price = $this->input->post('unit_price_'.$arrData[$i]['product_id']);
            if(!empty($qty) && !empty($unit_price)){
                      $all_fld_emty = false;
            }
            elseif(!empty($qty)  && empty($unit_price)){
                $all_fld_emty = false;        
                 $this->form_validation->set_rules('unit_price_'.$arrData[$i]['product_id'], 'unit_price', 'trim|required');
            }
            elseif(!empty($unit_price) && empty($qty)){
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

  function vendor_po(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'PO';
     $vars['ships'] = $this->cm->getAllShips(" AND s.status= 1",'R');
     $vars['content_view'] = 'vendor_po_list';
     $this->load->view('layout',$vars);
  }


 function getVendorWorkOrderList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   

    if(!empty($user_session_data->vendor_id)){
      $where = ' And wo.vendor_id = '.$user_session_data->vendor_id;   
    } 

    if(!empty($status)){
     if($status=='R'){
       $where .= ' AND wo.status = 1';
     }
     elseif($status=='A'){
       $where .= ' AND wo.status = 2';
        
     }
     elseif($status=='D'){
       $where .= ' AND wo.status = 3';
        
     }
     elseif($status=='I'){
       $where .= ' AND wo.status = 4';
     }
     elseif($status=='T'){
       $where .= ' AND wo.status = 5';
     }
     elseif($status=='P'){
       $where .= ' AND wo.status = 6';
     }
    }
    
    if(!empty($ship_id)){
      $where .= ' AND s.ship_id IN ('.implode(',',$ship_id).')';  
    }

    if($created_date){
      $where .=' AND date(wo.created_on) = "'.convertDate($created_date,'','Y-m-d').'"';  
    }

    if(!empty($keyword)){
     $where .= " AND ( wo.po_no like '%".trim($keyword)."%' or wo.order_id like '%".trim($keyword)."%' or so.rfq_no like '%".trim($keyword)."%' )";   
    }


    if((!empty($sort_column)) && (!empty($sort_type))){
        if($sort_column == 'Po N0'){
            $order_by = 'ORDER BY wo.po_no '.$sort_type;
        }
        elseif($sort_column == 'Ship Name'){
            $order_by = 'ORDER BY s.ship_name '.$sort_type;
        }
        elseif($sort_column == 'Order ID'){
            $order_by = 'ORDER BY wo.order_id '.$sort_type;
        }
        elseif($sort_column == 'RFQ No'){
            $order_by = 'ORDER BY so.rfq_no '.$sort_type;
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

   if($download){
           $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'POLIST.xlsx';
           $arrayHeaderData= array('Vessel Name','Po No.','Order ID','RFQ No','Created On','Created By','Stage');
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
           $work_orders = $this->cm->getAllWorkOrders($where,'R','','',$order_by);
           $k = 7;
           if(!empty($work_orders)){
                foreach ($work_orders as $row) {
                  $k++;
                  $stage = '' ;
                  if($row->status==1){
                     $stage = 'Raised';
                    }
                    elseif($row->status==2){
                     $stage = 'Accepted by vendor';
                    }
                    elseif($row->status==3){
                     $stage = 'Inprogress';                
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
                  $arrayData[] = array(ucwords($row->ship_name),$row->po_no,$row->order_id,$row->rfq_no,ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucwords($row->created_by),$stage);   
                }
           }

           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:G'.$k,'border'=>'THIN'))
               );  

           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'POLIST');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
       } 

   $countdata = $this->cm->getAllWorkOrders($where,'C');
   // echo $this->db->last_query();die;
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
   $work_orders = $this->cm->getAllWorkOrders($where,'R',$perPage,$offset,$order_by);
   $add_delivery_note = checkLabelByTask('add_delivery_note');
   $sign_delivery_note = checkLabelByTask('sign_delivery_note');
   $manage_work_order = checkLabelByTask('manage_work_order');
  //echo $this->db->last_query();die;
   if($work_orders){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($work_orders)).' of '.$countdata.' entries';
         foreach ($work_orders as $row){
          $vIn = '';
          $dn = '';
          $us = '';
            if($row->status==3){      
             $vIn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Upload Invoice\',\'vendor/upload_vendor_invoice\',\''.$row->work_order_id.'\',\'\',\'50%\')">Upload Invoice</a>';
            }
                             
            if($row->status<2){
                 $us = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Purchase Order Stage\',\'vendor/changePOStatus\',\''.$row->work_order_id.'\',\'\',\'60%\',\'\')">Update Stage</a>';
            }

            $poIn = '<a href="'.base_url().'shipping/printPurchaseOrderPdf/'.$row->work_order_id.'" target="_blank">Download PO</a>';
            $podIn = '<a href="'.base_url().'shipping/printPurchaseOrderDetailedPdf/'.$row->work_order_id.'" target="_blank">Download Details PO</a>';
            $stage = '';
            if($row->status==1){
             $stage = '<span>Raised</span>';
            }
            elseif($row->status==2){
             $stage = '<span>Accepted by vendor</span>';
            }
            elseif($row->status==3){
             $stage = '<span>Inprogress</span>';                
            }
            elseif($row->status==4){
             $stage = '<span>Invoice Uploaded</span>';                
            }
            elseif($row->status==5){
             $stage = '<span>Temprory Cancel</span>';                
            }
            elseif($row->status==6){
             $stage = '<span>Permanent Cancel</span>';                
            }

         $returnArr .= "<tr>
                              <td width='10%'>".$row->ship_name."</td>
                              <td width='10%'>".$row->po_no."</td>
                              <td width='10%'>".$row->order_id."</td>
                              <td width='10%'>".$row->rfq_no."</td>
                              <td width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</td>
                              <td width='10%'>".ucfirst($row->created_by)."</td>
                              <td width='10%'>".$stage."</td>
                              ";
                $returnArr .= '<td width="3%" class="action-td" style="text-align:center;"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$vIn.'</li>
                                <li>'.$us.'</li>
                                <li>'.$download_rfq.'</li>
                                <li>'.$poIn.'</li>
                                <li>'.$podIn.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }

         if($countdata <= 5){
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
           $returnArr .= "<tr><td width='10%'><br /></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'><td width='3%'></td></tr>";
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
  

  function vendor_invoice(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'VI';
     $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
     $vars['vendors'] = $this->um->getallVendor(' AND u.status = 1','R','','',' ORDER BY u.first_name ASC');
     if(!empty($user_session_data->shipping_company_id)){
        $swh = ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id;
     }
     $vars['ships'] = $this->cm->getAllShips($shw." AND s.status= 1",'R');
     $vars['content_view'] = 'vendor_invoice_list';
     $this->load->view('layout',$vars);
  }

 function getVendorInvoiceList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $order_by = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
    
    $vendor_id = ($vendor_id) ? $vendor_id : $user_session_data->vendor_id; 
    if($user_session_data->code=='cook' || $user_session_data->code=='captain'){
     $where .= ' AND s.ship_id = '.$user_session_data->ship_id;
    }
    elseif(!empty($user_session_data->shipping_company_id)){
        $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id;
    }   
    
   if($user_session_data->code=='super_admin'){
    if($vendor_id){
       $where .= ' AND vi.vendor_id IN ('.implode(',',$vendor_id).')';
     }
   }else{
     if($vendor_id){
        $where .= ' AND vi.vendor_id = '.$vendor_id;
    }
   }  
 

     if($keyword){
       $keyword = trim($keyword);
       $where .= " AND ( vi.invoice_no like '%".trim($keyword)."%' or wo.po_no like '%".trim($keyword)."%' )";   
        
     }  

    if(!empty($status)){
        $where .= ' AND vi.status = '.$status;
    } 

    if($shipping_company_id){
      $where .= ' AND s.shipping_company_id ='.$shipping_company_id;  
    }

    if($ship_id){
      $where .= ' AND s.ship_id IN ('.implode(',',$ship_id).')';  
    }

    if($created_date){
       // $where .=' AND date(vi.created_at) = "'.convertDate($created_date,'','Y-m-d').'"'; 
       $date_range = explode(' - ', $created_date);
       $cnvrtd_end_date = $cnvrtd_end_date = '';
       $strt_dt = $date_range[0];
       $end_dt = $date_range[1];
       $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
       $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
       $where .= " AND date(vi.created_at) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') "; 
    }

    if($due_date){
       $where .=' AND date(vi.due_date) = "'.convertDate($due_date,'','Y-m-d').'"'; 
    }

   
    
    if((!empty($sort_column)) && (!empty($sort_type))){
        if($sort_column == 'Company Name'){
            $order_by = 'ORDER BY sc.name '.$sort_type;
        }
        elseif($sort_column == 'Ship Name'){
            $order_by = 'ORDER BY s.ship_name '.$sort_type;
        }
        elseif($sort_column == 'Invoice No'){
            $order_by = 'ORDER BY vi.invoice_no '.$sort_type;
        }
        elseif($sort_column == 'PO No'){
            $order_by = 'ORDER BY wo.po_no '.$sort_type;
        }
        elseif($sort_column =='Vendor Name'){
            $order_by = 'ORDER BY u.first_name '.$sort_type;  
        }
        elseif($sort_column == 'Amount'){
            $order_by = 'ORDER BY vi.amount '.$sort_type;
        }
        elseif($sort_column == 'Due Date'){
            $order_by = 'ORDER BY vi.due_date '.$sort_type;
        }
        elseif($sort_column == 'Paid Amount'){
            $order_by = 'ORDER BY vi.paid_amount '.$sort_type;
        }
        elseif($sort_column == 'Due Amount'){
            $order_by = 'ORDER BY vi.pending_amount '.$sort_type;
        }
        elseif($sort_column == 'Transaction Value'){
            $order_by = 'ORDER BY vi.transaction_amount '.$sort_type;
        }
        elseif($sort_column == 'Created On'){
            $order_by = 'ORDER BY vi.created_at '.$sort_type;
        }
        elseif($sort_column == 'Created By'){
            $order_by = 'ORDER BY u.first_name '.$sort_type;
        }
        elseif($sort_column == 'Status'){
            $order_by = 'ORDER BY vi.status '.$sort_type;
        }
    }
    else{
        $order_by = 'ORDER BY vi.created_at DESC';
    }

     if($download){
        $this->load->library('Excelreader');
        $excel  = new Excelreader();
        if($user_session_data->code=='super_admin'){
        $fileName = 'PurchaseList.xlsx';
        $sheetName = 'PurchaseList';
        }else{
        $fileName = 'InvoiceList.xlsx';
        $sheetName = 'InvoiceList';
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
                    ),'cellArray'=>array('A7:M7'));
           $arrayHeaderData= array('Company Name','Vessel Name','Invoice No.','Po No','Vendor Name','Amount($)','Due Date','Paid Amount($)','Due Amount($)','Transaction Value','Created On','Created By','Invoice Status');

           }else{
             $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:K7')); 
           $arrayHeaderData= array('Company Name','Vessel Name','Invoice No.','Po No','Total Amount','Due Date','Paid Amount','Due Amount','Created On','Created By','Status');

           }

           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;   
           $invoice_list = $this->vm->getVendorInvoiceList($where,'R','','',$order_by);
           $k = 7;
           if(!empty($invoice_list)){
                foreach ($invoice_list as $row) {
                  $k++;
                  $status = '';
                     if($row->status==1){
                         $status = 'Unpaid';
                       }
                       elseif($row->status==2){
                         $status = 'Pending Amount Verification';
                       }
                       elseif($row->status==3){
                        $status = 'Partially Paid';
                       }
                       elseif($row->status==4){
                        $status = 'Paid';
                       }
                       elseif($row->status==5){
                        $status = 'Advance Partially Paid';
                       }
                       elseif($row->status==6){
                        $status = 'Advance Paid';
                       }

                  if($user_session_data->code=='super_admin'){
                  $arrayData[] = array(ucwords($row->company_name),ucwords($row->ship_name),$row->invoice_no,$row->po_no,ucwords($row->vendor_name),$row->amount,ConvertDate($row->due_date,'','d-m-Y'),$row->paid_amount,$row->pending_amount,$row->transaction_amount,ConvertDate($row->created_at,'','d-m-Y | h:i A'),ucwords($row->user_name),$status);   

                  }else{
                   $arrayData[] = array(ucwords($row->company_name),ucwords($row->ship_name),$row->invoice_no,$row->po_no,$row->amount,ConvertDate($row->due_date,'','d-m-Y'),$row->paid_amount,$row->pending_amount,ConvertDate($row->created_at,'','d-m-Y | h:i A'),ucwords($row->user_name),$status);   

                  }
                }
           }

           if($user_session_data->code=='super_admin'){
            $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:M'.$k,'border'=>'THIN')));    
           }
           else{
            $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:K'.$k,'border'=>'THIN')));    
           }

           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],$sheetName);
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
       } 

    $countdata = $this->vm->getVendorInvoiceList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);

    $invoice_list = $this->vm->getVendorInvoiceList($where,'R',$perPage,$offset,$order_by);
    $add_transaction = checkLabelByTask('add_transaction');
    if($invoice_list){
      $total_entries = 'Showing '.($offset+1).' to '.($offset+count($invoice_list)).' of '.$countdata.' entries';
       foreach ($invoice_list as $row){
         $in = $dn = $transaction ='';
         
         if(!empty($row->document_url)){
           $in = '<a href="'.base_url().'/uploads/vendor_pdf/'.$row->document_url.'" >View Invoice</a>';
         }
         else{
           if($user_session_data->code=='vendor'){
             $in = '<a href="javascript:void(0)" onclick="showAjaxModel(\'Reupload Invoice\',\'vendor/upload_vendor_invoice\',\''.$row->work_order_id.'\',\'reupload\',\'50%\')">View Invoice</a>';
           }
           else{
             $in = '<a href="javascript:void(0)" onclick="alert(\'There was an error on uploaded document. please contact vendor for reupload it.\')">View Invoice</a>';
           } 
         }
        
        if($add_transaction){
         if($row->status=='1' || $row->status=='3' || $row->status=='5'){
           $transaction = '<a onclick="showAjaxModel(\'Add Transaction\',\'report/add_transaction_history\',\''.$row->vendor_invoice_id.'\',\'purchase\',\'80%\')" href="javascript:void(0)">Add Transaction</a>';
         }
        }

         
         // if($row->status==1 && $user_session_data->code=='vendor'){
         //   $dn .= '<a onclick="updateInvoiceStatus('.$row->vendor_invoice_id.')" href="javascript:void(0)">Mark as Paid</a>';            
         // }

         // $status = ($row->status==1) ? 'Created' : "Paid";          
           $status = '';
           if($row->status==1){
             $status = 'Unpaid';
           }
           elseif($row->status==2){
             $status = 'Pending Amount Verification';
           }
           elseif($row->status==3){
            $status = 'Partially Paid';
           }
           elseif($row->status==4){
            $status = 'Paid';
           }
           elseif($row->status==5){
            $status = 'Advance Partially Paid';
           }
           elseif($row->status==6){
            $status = 'Advance Paid';
           }
           $returnArr .= "<tr>
            <td width='10%'><p>".ucwords($row->company_name)."</p></td>
            <td width='10%'>".ucwords($row->ship_name)."</td>
            <td width='10%'>".$row->invoice_no."</td>
            <td width='10%'>".$row->po_no."</td>";
           if($user_session_data->code=='super_admin'){
             $returnArr .="<td width='10%'>".ucfirst($row->vendor_name)."</td>";
           } 
            $returnArr .= "<td width='10%'>".$row->amount."</td>
             <td width='10%'>".ConvertDate($row->due_date,'','d-m-Y')."</td>
             <td width='10%'>".$row->paid_amount."</td>
             <td width='10%'>".$row->pending_amount."</td>";
             if($user_session_data->code=='super_admin'){
              $returnArr .= "<td width='10%'>".$row->transaction_amount."</td>";
             }
            $returnArr .= "<td width='10%'>".ConvertDate($row->created_at,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            <td width='10%'>".$status."</td>
            ";
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
             <ul class="dropdown-menu pull-right">
                <li>'.$transaction.'</li>
                <li>'.$in.'</li>
             </ul>
            </div></td> </tr>';
         }

         if($countdata <= 5){
            if($user_session_data->code=="super_admin"){
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>"; 
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>"; 
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>"; 
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td></tr>";  
           }
           else{
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td></tr>";
           
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td></tr>";

            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td></tr>";

            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td></tr>";

             }  
           }

            $pagination = $pages->get_links();
            }
            else
            {
              $pagination = '';
              $returnArr = '<tr><td colspan="9" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
            }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  }

  function changeInvoiceStatus(){
    checkUserSession();
    $returnArr['status'] = 100;
    $id = $this->input->post('id');
    if(!empty($id)){
        $status = $this->input->post('status');
        $where = 'vendor_invoice_id ='.$id;
        $this->cm->changestatus('vendor_invoice',$status,$where);        
        $returnArr['status'] = 200;
        $returnArr['returnMsg'] = 'Invoice Status Update Successfully';
    }
    else{
    $returnArr['status'] = 300;
    }
    echo json_encode($returnArr);
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
         $msg = '<span style="color:red">You are not able to manually update the Stage. Please contact One North Admin.</span>';
    }
    elseif($data['status']==3){
         $step1class = $step2class = $step3class =  'done';
          $msg = '<span style="color:red">Please Upload invoice first, then the stage will automatically update</span>';
    }
    // elseif($data['status']==4){
    //      $step1class = $step2class = $step3class = $step4class =  'done';
    //      $selected1 = $selected3 = 'disabled';
    //      $selected2 = 'checked';
    // }
    // elseif($data['status']==5){
    //      $step1class = $step2class = $step3class = $step4class = $step5class =  'done';
    //      $selected1 = $selected2 = 'checked disabled';
    //      $selected3 = 'checked';   
    // } 

    if(!empty($workOrderId)){
        $returnArr = '
        <div class="animated fadeIn">
        <div class="row">
        <div class="col-md-12">
        <form class="form-horizontal form-bordered" name="update_work_order_status" enctype="multipart/form-data" id="update_work_order_status" method="post">
        <div class="no-padding rounded-bottom">';
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
                        <input type="radio" name="po_status" value="2" disabled><span>Inprogress</span></label>
                        <label class="radio-inline '.$step4class.'">
                        <input type="radio" name="po_status" value="3" disabled ><span>Vendor Invoice Uploaded</span></label>';
                   $returnArr .='</div>
                        </div>'.$msg.'</div></div>
         <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="work_order_id" id="work_order_id" value="'.$workOrderId.'">    
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" class="btn btn-success btn-slideright mr-5" onclick=submitMoldelForm("update_work_order_status","shipping/updatePOStatus")>Update Stage</button>
           </div>
         </div>';
     }
   echo json_encode(array('data'=>$returnArr,'status' => 100));
 }
  

  function upload_vendor_invoice($type=''){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('user_manager');
    $this->load->model('email_manager');
    $this->em = $this->email_manager;
    $type = ($type) ? $type : trim($this->input->post('type'));
    $work_order_id = ($this->input->post('id')) ? $this->input->post('id') : $this->input->post('work_order_id');
    $second_id = $this->input->post('second_id');
    if($work_order_id){
      $where = ' AND wo.work_order_id='.$work_order_id;
      $data = (array) $this->cm->getWorkOrderByID($where);
      // echo '<pre>';
      // print_r($data);die;        
    }

    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
       if($this->invoice_validation()){
         if(!empty($second_id)){
           $this->db->delete('vendor_invoice',array('work_order_id'=>$work_order_id));
         }
         $dataArr['invoice_no'] = trim($this->input->post('invoice_no')); 
         $dataArr['vendor_id'] = $data['vendor_id'];
         $dataArr['work_order_id'] = $work_order_id;
         $dataArr['created_at'] = date('Y-m-d h:i:s');
         $dataArr['created_by'] = $user_session_data->user_id;
         $dataArr['due_date'] = convertDate($this->input->post('due_date'),'','Y-m-d');
         $dataArr['amount'] = $this->input->post('amount'); 
         if(!empty($_FILES['img']['name'])) {
           $file = $_FILES['img']['name'];
           $upload_data = doc_upload($file, 'vendor_pdf');
           $upload_data = doc_upload($file, 'work_order_pdfs');
         }
         $dataArr['document_url'] = $upload_data['file_name']; 
         $vendor_invoice_id = $this->vm->add_vendor_invoice($dataArr);
         $this->db->update('work_order',array('status'=>4),array('work_order_id'=>$work_order_id));
        
        $wheree = ' and em.template_code = "upload_invoice"';  
        $template_data = $this->um->getEmailTemplateByCode($wheree);
        if(!empty($template_data)){
            $subject = str_replace(array('##ship_name##','##po_no##','##invoice_no##','##imo_no##','##port_name##','##delivery_date##','##req_type##'),array(ucwords($data['ship_name']),$data['po_no'],$dataArr['invoice_no'],$data['imo_no'],$data['delivery_port'],convertDate($data['delivery_date'],'','d-m-Y'),ucwords(str_replace('_',' ',$data['requisition_type']))),$template_data->email_subject); 
            $body = str_replace(array('##invoice_no##','##po_no##','##ship_name##','##delivery_date##','##port_name##','##due_date##','##vendor_name##','##address##'),array($dataArr['invoice_no'],$data['po_no'],ucwords($data['ship_name']),convertDate($data['delivery_date'],'','d-m-Y'),$data['delivery_port'],convertDate($this->input->post('due_date'),'','d-m-Y'),ucwords($data['vendor_name']),$data['vendor_address']),$template_data->email_body);
           
            $email_roles = $this->em->getEmailRoles($template_data->email_template_id);
           if(!empty($email_roles)){
             foreach ($email_roles as $row) {
              $user_list = $this->em->getUserByRoleID($row->role_id);
                    if(!empty($user_list)){
                       foreach ($user_list as $val) {
                           $emArr['user_id'] = $val->user_id;
                           $emArr['subject'] = $subject;
                           $emArr['body'] = $body;
                           $emArr['attechment'] = $upload_data['file_name'];
                           $this->em->add_email_log($emArr);
                        }
                     }
                } 
             } 
          }
         $this->session->set_flashdata('succMsg','Invoice Uploaded successfully');
         $returnArr['status'] = 200; 
       }
    } 

    $vars['work_order'] = $this->cm->getAllWorkOrders(' AND wo.status>2 AND wo.status<4 And wo.vendor_id ='.$user_session_data->vendor_id,'R');
    // echo $this->db->last_query();die;
    $vars['type'] = $type; 
    $vars['dataArr'] = $this->input->post();
    $vars['work_order_id'] = $work_order_id;
    $vars['second_id'] = $second_id;
    $data = $this->load->view('import_vendor_invoice',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);  
  } 

   function invoice_validation(){
      $type = trim($this->input->post('type'));
      if(!empty($type)){
        $this->form_validation->set_rules('work_order_id','Purchase Order','trim|required');   
      }
     $this->form_validation->set_rules('invoice_no','Invoice No','trim|required|is_unique[vendor_invoice.invoice_no]'); 
    $this->form_validation->set_rules('amount','Amount','trim|required');
    $this->form_validation->set_rules('due_date','Due Date','trim|required');  
     $this->form_validation->set_rules('img','','callback_invoice_file_check'); 
     return $this->form_validation->run();
   }

 
 function invoice_file_check(){
    $allowed_mime_type_arr = array('application/pdf');
     $mime = get_mime_by_extension($_FILES['img']['name']);
        if(isset($_FILES['img']['name']) && $_FILES['img']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('invoice_file_check', 'Please choose pdf file.');              
                return false;
            }
           return true; 
        }else{
            $this->form_validation->set_message('invoice_file_check', 'Please choose a file to upload.');
            return false;
        }
   }
  
  function invoice_transaction(){
    checkUserSession();
    $user_session_data = getSessionData();
    $vars['active'] = 'IV';
    $vars['ships'] = $this->cm->getAllShips(" AND s.status= 1",'R');
    $vars['content_view'] = 'vendor_transaction_list';
    $this->load->view('layout',$vars); 
  }

  function getAlltransationList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = $order_by = $returnArr = '';
    extract($this->input->post());
    $where = ' AND ( vi.vendor_id = '.$user_session_data->vendor_id.')';
     
    if($status){
        if($status=='V'){
         $where .= 'AND (t.is_verified = 1 )';
        }
        elseif($status=='P'){
         $where .= 'AND (t.is_verified = 0 )';
        }
    } 

    if($ship_id){
      $where .= ' And (s.ship_id in ('.implode(',',$ship_id).') )';  
    }

    if($created_date){
     $date_range = explode(' - ', $created_date);
     $cnvrtd_end_date = $cnvrtd_end_date = '';
     $strt_dt = $date_range[0];
     $end_dt = $date_range[1];
     $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
     $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
     $where .= " AND (date(t.created_on) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') )"; 
    }
    
    if($keyword){
      $where .= ' AND ( wo.po_no like "%'.trim($keyword).'%" or  vi.invoice_no like "%'.trim($keyword).'%" or t.amount like "%'.trim($keyword).'%")';  
    }

   if((!empty($sort_column)) && (!empty($sort_type))){
     if($sort_column == 'Vessel Name'){
        $order_by = 'ORDER BY s.ship_name '.$sort_type;
      }
      elseif($sort_column == 'Trans ID'){
        $order_by = 'ORDER BY t.invoice_transaction_id '.$sort_type;
      }
      elseif($sort_column == 'Date'){
        $order_by = 'ORDER BY t.created_on '.$sort_type;
      }
      elseif($sort_column == 'Invoice No'){
        $order_by = 'ORDER BY vi.invoice_no '.$sort_type;

      }
      elseif($sort_column == 'PO No'){
        $order_by = 'ORDER BY wo.po_no '.$sort_type;

      }
      elseif($sort_column == 'Total Amount'){
        $order_by = 'ORDER BY t.amount '.$sort_type;
      }
      elseif($sort_column == 'Description'){
        $order_by = 'ORDER BY t.description '.$sort_type;
      }
      elseif($sort_column == 'Document'){
        $order_by = 'ORDER BY t.document '.$sort_type;
      }
      elseif($sort_column == 'Status'){
        $order_by = 'ORDER BY t.is_verified '.$sort_type;
      }

    }
    else{
        $order_by = 'ORDER BY t.invoice_transaction_id DESC';
    }
     
    if($download){
       $this->load->library('Excelreader');
        $excel  = new Excelreader();
        $fileName = 'Transaction.xlsx';
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
       $arrayHeaderData= array('Vessel Name','Transaction ID','Date','Invoice No.','Po No','Amount($)','Description','Status');              
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

        $transaction_list = $this->vm->getVendorTransData($where,'R','','',$order_by);
         $k = 7;
       if(!empty($transaction_list)){
         foreach ($transaction_list as $row) {
            $k++;
            $dn_status = '';
             $dn_status = ($row->is_verified==1) ? 'Verified' : 'Pending';

            $arrayData[] = array(ucwords($row->ship_name),$row->invoice_transaction_id.ConvertDate($row->created_on,'','dmY'),convertDate($row->created_on,'','d-m-Y'),$row->invoice_no,$row->po_no,$row->amount,$row->description,$dn_status);

         }
       }
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN'))); 
        $arrayBundleData['listColumn'] = $listColumn;
        $arrayBundleData['arrayData'] = $arrayData;
        $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Transaction');
        readfile(FCPATH.'uploads/sheets/'.$fileName);
        unlink(FCPATH.'uploads/sheets/'.$fileName);
        exit;     
    } 

    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
    $countdata = $this->vm->getVendorTransData($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $transaction_list = $this->vm->getVendorTransData($where,'R',$perPage,$offset,$order_by);
    if($transaction_list){ 
      $total_entries = 'Showing '.($offset+1).' to '.($offset+count($transaction_list)).' of '.$countdata.' entries';
      $pagination = $pages->get_links();
      foreach ($transaction_list as $row) {
         $status = $confirm = '';
         if($row->is_verified!=1){
           $confirm = '<a onclick="transVerify('.$row->invoice_transaction_id.')" href="javascript:void(0)">Verify</a>'; 
         }
         $status = ($row->is_verified==1) ? 'Verified' : 'Pending';
         $returnArr .= '<tr>
          <td>'.ucwords($row->ship_name).'</td>
          <td>'.$row->invoice_transaction_id.convertDate($row->created_on,'','dmY').'</td>
          <td>'.convertDate($row->created_on,'','d-m-Y').'</td>
          <td>'.$row->invoice_no.'</td>
          <td>'.$row->po_no.'</td>
          <td>'.$row->amount.'</td>
          <td>'.$row->description.'</td>
          <td><a target="_blank" href="'.base_url()."uploads/transaction/".$row->document.'">'.$row->document.'</a></td>
          <td>'.$status.'</td>';
          if($row->is_verified!=1){
             $returnArr .= '<td class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
             <li>'.$confirm.'</li>
            </ul></div></td>';
           }
           else{
            $returnArr .= '<td width="3%" class="action-td"></td>';
           }

          $returnArr .='</tr>';
      }
     }
     else{
      $pagination = '';
       $returnArr = '<tr><td colspan="9" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
     } 

    echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  } 

  function verify_transaction(){
    checkUserSession();
    $returnArr['status'] = 100;
    $id = $this->input->post('id');
    if(!empty($id)){
      $data = ( array) $this->cm->invoiceTransHistoryById(' AND t.invoice_transaction_id ='.$id);
      $where = ' AND vi.vendor_invoice_id ='.$data['vendor_invoice_id'];
      $invoice_data = (array) $this->vm->getVendorInvoiceDataByID($where);
      $total_amount = $invoice_data['amount'];
      $received_amount = $invoice_data['paid_amount'];
      $final_amount = ($total_amount - $received_amount); 

      if($data['trans_type']=='partially_pay'){
        if($data['amount']>=$final_amount){
         $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>4,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
        }
       else{
        $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>3,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
        }
      }
      elseif($data['trans_type']=='full_pay'){
         $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>4,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
      }
      elseif($data['trans_type']=='advance_partially'){
        if($data['amount']>=$final_amount){
         $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>6,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
        }
       else{
        $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>5,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
        }
      }
      elseif($data['trans_type']=='advance_full'){
         $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $data['amount']),'pending_amount'=>$total_amount - ($received_amount + $data['amount']),'status'=>6,'transaction_amount'=>null),array('vendor_invoice_id'=>$data['vendor_invoice_id']));
      }

      $this->db->update('invoice_transaction',array('is_verified'=>1),array('invoice_transaction_id'=>$id));       
      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Transaction amount has been verified';
    }
    echo json_encode($returnArr);
  }
 
  
  function import_quote(){
    checkUserSession();
    $user_session_data = getSessionData();
    $this->load->model('user_manager');
    $ship_order_id = $this->input->post('id');
    $vendor_quote_id = $this->input->post('second_id');
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
     $this->form_validation->set_rules('img','','callback_file_check');   
      if($this->form_validation->run()){
        $mime = get_mime_by_extension($_FILES['img']['name']);
         if(!empty($_FILES['img']['name'])) {
           $file = $_FILES['img']['name'];
           $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
           $upload_data = doc_upload($file, 'sheets');
         }

          $dataArr['vendor_quote_id'] = $vendor_quote_id;
          $dataArr['ship_order_id'] = $ship_order_id; 
          $dataArr['type'] = 'by_vendor'; 


         $full_path = FCPATH.'uploads/sheets/'.$upload_data['file_name'];
         $tmpArr = array();

         $data = (array) $this->cm->getRrqItemsByID(' and so.ship_order_id = '.$ship_order_id);
         $arrData = unserialize($data['json_data']);
         $a = array();
         $product_id_arr = array();
         if(!empty($arrData)){
           for ($i=0; $i <count($arrData) ; $i++) { 
             $a[] = $arrData[$i]['product_id'];
           }
        }

         $pd_id = implode(',',$a);
         $products  = $this->mp->getAllProduct(' AND p.product_id in ('.$pd_id.')','R');
          if(!empty($products)){
            foreach ($products as $row) {
              $product_id_arr[$row->item_no] = $row->product_id; 
            }
          }


        if($mime=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
         
          $this->load->library('Excelreader');
          $excel = new Excelreader();
          $objWriter = $excel->readExcel($full_path,'xlsx');
          unset($objWriter[1]);
          $objWriter = array_values($objWriter);
          for ($i=0; $i <count($objWriter) ; $i++){
              $product_id = $product_id_arr[$objWriter[$i]['C']];
              $qty = $objWriter[$i]['D'];
              $unit_price = str_replace(array('$'),array(''),$objWriter[$i]['F']);
              if(!empty($product_id)){ 
                $tmpArr[$product_id] = array('quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty * $unit_price),'remark'=>$objWriter[$i]['H']); 
              }
          }
      }
      elseif($mime=='application/vnd.ms-excel'){
           $this->load->library('Excelreader');
          $excel = new Excelreader();
          $objWriter = $excel->readExcel($full_path,'xls');
          unset($objWriter[1]);
          $objWriter = array_values($objWriter);
          for ($i=0; $i <count($objWriter) ; $i++){
             if(!empty($objWriter[$i]['C'])){
              $product_id = $product_id_arr[$objWriter[$i]['C']];
              $qty = $objWriter[$i]['D'];
              $unit_price = str_replace(array('$','/'),array('',''),$objWriter[$i]['F']);
              $tmpArr[$product_id] = array('quantity'=>$qty,'unit_price'=>$unit_price,'price'=>($qty * $unit_price),'remark'=>$objWriter[$i]['H']); 
             }
          }
        }

        // print_r($tmpArr);die;
        
        setImportSession('vendor_quatation',array('basic'=>$dataArr,'dataArr'=>$tmpArr));
        unlink($full_path);
        $returnArr['status'] = 200;
      }
    }

    $vars['dataArr'] = $this->input->post();
    $data = $this->load->view('import_quote',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }  

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
 }
 ?>