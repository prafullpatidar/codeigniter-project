<?php
// require 'vendor/autoload.php';
// use Minishlink\WebPush\WebPush;
// use Minishlink\WebPush\Subscription;

// $auth = [
//     'VAPID' => [
//         'subject' => 'mailto:your@email.com',
//         'publicKey' => 'YOUR_PUBLIC_KEY',
//         'privateKey' => 'YOUR_PRIVATE_KEY',
//     ],
// ];

// $webPush = new WebPush($auth);


// $keys = VAPID::createVapidKeys();
// print_r($keys);

class User extends CI_Controller
{
   function __construct(){
      parent::__construct();
      $this->load->library('querybundel');
      $this->qb = $this->querybundel;
      $this->load->model('user_manager');
      $this->um = $this->user_manager;
      $this->load->model('manage_vendor');
      $this->vm = $this->manage_vendor;
      $this->load->model('Company_manager');
      $this->cm = $this->Company_manager;
       $this->load->model('News_manager');
      $this->nm = $this->News_manager;
   } 

    function index(){
        $sessionData = getSessionData();
        if(!empty($sessionData)) {
                redirect(base_url().'user/user_dashboard','refresh');
        }  
        if($this->login_validation()){
                redirect(base_url().'user/user_dashboard','refresh');   
        }   
        // $vars['content_view'] = 'login';
        // $this->load->view('login_layout',$vars);

         $this->load->view('home',$vars);
   } 

   function new_login(){
    $role_code = $this->input->post('role_code');
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
       if($this->login_validation()){
            $returnArr['status'] = 200;
       }
    }
    $vars['role_code'] = $role_code;
    $data = $this->load->view('login_new',$vars,true);
    $returnArr['data'] = $data;
     echo json_encode($returnArr);  
  }


   
   function user_dashboard($shipping_company_id= ''){
     checkUserSession();
     $vars['user_session_data'] = $sessionData =  getSessionData();
     $vars['active'] = 'DASH';
     $vars['title'] = 'Dashboard';
     $vars['heading'] = 'Dashboard';

     $vars['shipping_company_id'] = base64_decode($shipping_company_id);
   
     if(!empty($sessionData->shipping_company_id)){
       $cwh = ' AND c.shipping_company_id = '.$sessionData->shipping_company_id;
       $swh = ' AND s.shipping_company_id = '.$sessionData->shipping_company_id;
       $uwh = ' AND u.shipping_company_id ='.$sessionData->shipping_company_id;

     }

      $vars['company'] = $company = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
      $vars['count_companies'] = count($company);

     // $vars['count_companies'] = $this->cm->getAllshippingCompany($cwh." AND c.status= 1 ",'C');
      
      $vars['ships'] = $ships = $this->cm->getAllShips($swh." AND s.status= 1",'R');
      $vars['ship_count'] = count($ships);
      // $vars['ship_count'] = $this->cm->getAllShips($swh." AND s.status= 1",'C');
      $vars['all_vendors'] = $vendor = $this->um->getallVendor(' AND u.status= 1','R');
      $vars['vendors'] = count($vendor);

      // $vars['vendors'] = $this->um->getallVendor(" AND u.status= 1 ",'C');
      
      $vars['users'] = $this->um->getalluserlist($uwh." AND u.status= 1 ",'C');

      $vars['company_invoice'] = $this->cm->getAllInvoiceList($swh,'C');
     
     if(!empty($sessionData->vendor_id)){
       $vars['total_rfq'] = $this->vm->getallVendorOrder(' AND vq.vendor_id = '.$sessionData->vendor_id,'C');
       $vars['total_po']= $this->cm->getAllWorkOrders(' And wo.vendor_id = '.$sessionData->vendor_id,'C');
       $vars['total_invoice'] = $this->vm->getVendorInvoiceList(' AND vi.vendor_id = '.$sessionData->vendor_id,'C'); 
     }
     else{
       $vars['total_rfq'] = $this->vm->getallVendorOrder($swh,'C');
       $vars['total_po']= $this->cm->getAllWorkOrders($swh.' AND month(wo.created_on)='.date('m'),'C');
       $vars['total_invoice'] = $this->vm->getVendorInvoiceList('','C');   
     }

     $vars['bulletins'] = $this->nm->getAllnewsList(' AND n.status = 0','R',10,0,' ORDER BY n.added_on DESC');
     
     $vars['content_view'] = 'user_dashboard';
     $this->load->view('layout',$vars);
   }
   
   function login_validation(){
        $this->form_validation->set_rules('username','User Name','trim|required|callback_authenticate');
        $this->form_validation->set_rules('password','Password','trim|required');
        $this->form_validation->set_message('authenticate', 'Oops! something went wrong. Please check your credentials & try again');
        return $this->form_validation->run();
    }

    function authenticate(){
        if(!empty($this->input->post('username')) && (!empty($this->input->post('password')))){
            $user_name = $this->input->post('username');
            $password = $this->input->post('password');
            $codes = $this->input->post('role_code');
            $userinfo = $this->um->login($user_name, md5($password),$codes);
            // echo $this->db->last_query();die;
            if(!empty($userinfo)){
                $remeberme = $this->input->post('rememberme');
                if(isset($remeberme) && ($remeberme) == 'on'){
                $cookie = array(
                  'name' =>'user_id',
                  'value'=>$userinfo->user_id,
                  'expire'=>86500 * 30
                );
                $this->input->set_cookie($userinfo);  
                }else{
                    delete_cookie('user_id');
                }
                set_session($userinfo);
                return true;   
            }else{
                return false;die;
            }
        }
    } 

   function setDebuging($debug='N'){
        $time = 0;
        if($debug=='Y'){
            $time = 3600;
        }
        setcookie('debug', 'Yes', time() + ($time), "/");
    }

    function user_list($user_id=''){
        checkUserSession();
        $vars['user_session_data'] = getSessionData();
        $vars['heading'] = 'User List';
        $vars['content_view'] = 'user_list';
        $vars['active'] = 'UM';
        $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
        $vars['roles'] = $this->um->getUserRole();
        $this->load->view('layout',$vars);
    } 

    function getAlluser(){
     checkUserSession();
     $user_session_data = getSessionData();
     $where = '';
     extract($this->input->post());
     $returnArr = '';
     $cur_page   = $page ? $page : 1;
     $perPage    = $perPage ? $perPage : 25;
     if(!empty($keyword)){
      $where .= " AND u.first_name like '%".trim($keyword)."%' OR u.last_name like '%".trim($keyword)."%' OR concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' OR u.email like '%".trim($keyword)."%' OR u.phone like '%".trim($keyword)."%' OR u.address like '%".trim($keyword)."%' ";    
     }

     if(!empty($status)){
         if($status == 'A'){
         $where .= " AND ( u.status = 1)";
      }elseif($status == 'D'){
         $where .= " AND ( u.status = 0)";
      }
     }

     if($created_on){
     $where .= ' AND (date(u.created_date) = "'.convertDate($created_on,'','Y-m-d').'") '; 
     } 

     if(!empty($shipping_company_id)){
       $where .= ' AND u.shipping_company_id='.$shipping_company_id;
     }
     if(!empty($role)){
       $where .= ' AND r.role_id='.$role;
     }

     if((!empty($sort_column)) && (!empty($sort_type))){
        if($sort_column == 'Name'){
            $order_by = 'ORDER BY name '.$sort_type;
        }elseif($sort_column == 'Dob'){
            $order_by = 'ORDER BY u.dob '.$sort_type;
        }elseif($sort_column == 'Address'){
            $order_by = 'ORDER BY u.address '.$sort_type;
        }elseif($sort_column == 'Created'){
            $order_by = 'ORDER BY u.created_date '.$sort_type;
        }elseif($sort_column == 'Role'){
            $order_by = 'ORDER BY role '.$sort_type;
        }elseif($sort_column == 'Email'){
            $order_by = 'ORDER BY u.email '.$sort_type;
        }elseif($sort_column == 'Phone'){
            $order_by = 'ORDER BY u.phone '.$sort_type;
        }
    }else{
            $order_by = 'ORDER BY u.created_date DESC';
    }

    if($download){
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'UserList.xlsx';
           $arrayHeaderData= array('Name','Email','Phone','Added Date','Address','User Role','Status');
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
           $userdata = $this->um->getalluserlist($where,'R','','',$order_by);
           $k = 7; 
           if(!empty($userdata)){
                foreach ($userdata as $row) {
                   $k++;
                   $status = ($row->status==1) ? 'Activate' : 'Deactivate'; 
                  $arrayData[] = array(ucwords($row->name),$row->email,$row->phone,ConvertDate($row->created_date,'','d-m-Y'),$row->address,ucwords($row->role),$status);   
                }
          } 
         $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:G'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'UserList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;   

    }

    $countdata = $this->um->getalluserlist($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $userdata = $this->um->getalluserlist($where,'R',$offset,$perPage,$order_by);
    $edit_user = checkLabelByTask('edit_user');
    $change_password = checkLabelByTask('change_password');
    if($userdata){
        $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($userdata)).' of '.$countdata.' entries';
        foreach ($userdata as $row){
         if($edit_user){
            if($row->status == 0){
                $Status = '<a onclick="updateStatusBox('.$row->user_id.','.$row->status.',\''.$row->name.'\',\'user/changestatus\')" href="javascript:void(0)">Activate</a>';   
             }else{
                $Status = '<a onclick="updateStatusBox('.$row->user_id.','.$row->status.',\''.$row->name.'\',\'user/changestatus\')" href="javascript:void(0)">Deactivate</a>';      
            } 

             $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'User\',\'user/edituser\','.$row->user_id.',\'\',\'70%\');" >Edit</a>';        
          }

        if($change_password){
          $changePassword = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Change Password\',\'user/changeUserPassword\','.$row->user_id.');" >Change Password</a>';
        }  
          
        $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';   
        $image =  ($row->img_url !='') ? "<img class='list-img' src=".base_url()."uploads/user/".$row->img_url." width='50px' height='50px'>": "<img class='list-img' src=".base_url("uploads/customer.png")." width='50px' height='50px'>" ;  
      
        $returnArr .= "<tr id='row-".$row->user_id."' >
                        <td width='5%'>".$image."</td>
                        <td width='19%'>".ucfirst($row->name)."</td>
                        <td width='12%'>".ucfirst($row->email)."</td>
                        <td width='12%'>".ucfirst($row->phone)."</td>
                        <td width='12%'>".ConvertDate($row->created_date,'','d-m-Y')."</td>
                        <td width='12%'>".ucfirst($row->address)."</td>
                        <td width='12%'>".ucfirst($row->role)."</td>
                        <td width='12%'>".$status."</td>";  

        // if($user_session_data->code == 'super_admin'){  
            $returnArr .= '<td width="4%" class="action-td"><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$changePassword.'</li>
                                <li role="separator" class="divider"></li>
                                </ul>
                                </div></td></tr>'; 
            // }else{
            //     $returnArr .= '<td  width="4%" style="text-align:center;"></td></tr>'; 
            // }
         }
         if($countdata <= 5){
            $returnArr .= "<tr><td width='5%'><br /></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='4%'></td></tr>";
            $returnArr .= "<tr><td width='5%'><br /></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='4%'></td></tr>";
           $returnArr .= "<tr><td width='5%'><br /></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='4%'></td></tr>";
           $returnArr .= "<tr><td width='5%'><br /></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='4%'></td></tr>";
           $returnArr .= "<tr><td width='5%'><br /></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='12%'></td><td width='4%'></td></tr>"; 
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
    
    function changestatus(){
      $id = $this->input->post('id');
      $status = $this->input->post('status');
      $status = ($status==1) ? 0 : 1;
      $this->um->updateStatus($id,$status);    
     $this->session->set_flashdata('succMsg','User Status changed successfully.');
    }

 function addedituser(){
     checkUserSession();
     $usersessiondata = getSessionData();
      extract($this->input->post());
      $this->load->model('email_manager');
      $this->em = $this->email_manager;
     if($this->user_validation()){
     $pass = md5($c_password);
     $dataArr = array('first_name'=>$first_name,'last_name'=>$last_name,'user_name'=>$user_name,'email'=>$email,'password'=>$pass,'phone'=>$phone,'address'=>$address,'created_by'=>$usersessiondata->user_id,'created_date'=>date('Y-m-d H:i:s'),'country'=>$country,'state'=>$state,'city'=>$city,'zipcode'=>$zipcode,'shipping_company_id'=>$shipping_company_id,'passport_id'=>$passport_id);
     if (!empty($_FILES['img']['name'])) {
        $file = $_FILES['img']['name'];
        $upload_data =  doc_upload($file, 'user');
        $dataArr['img_url'] =  $upload_data['file_name'];  
     }
    

     $getuserRoleByID = $this->um->getuserRolebyId($user_role);     
     if($getuserRoleByID->code == 'vendor'){
        $dataArr['is_vendor'] = 1;
     }
     
     $user_id = $this->um->addedituser('user',$dataArr); 
     
     
     if($getuserRoleByID->code == 'vendor'){  
      if(!empty($_FILES['vendor_pdf']['name'])){
           $pdf_file_name = $_FILES['vendor_pdf']['name'];
           $pdf_data = pdf_upload($pdf_file_name, 'vendor_pdf');
           $venArr['vendor_pdf'] = $pdf_data['file_name'];
      }    
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
     }

        $whereEm = ' AND nt.code = "add_user"';
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
                   $noteArr['row_id'] = $user_id;
                   $noteArr['entity'] = 'user';          
                   $noteArr['long_desc'] = str_replace(array('##first_name##','##last_name##','##ship_name##','##code##','##user_name##'),array($first_name,$last_name,'',$getuserRoleByID->code,ucfirst($usersessiondata->first_name.' '.$usersessiondata->last_name)),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }

     if(!empty($user_id)){
       $roleData = array('user_id'=>$user_id,'role_id'=>$user_role); 
       $this->um->addUserRole('user_role',$roleData);
     }

     $this->session->set_flashdata('succMsg','New User added successfully.');
     redirect(base_url().'user/user_list','refresh');    
     }


     $vars['company'] = $this->cm->getAllshippingCompany(' and c.status = 1','R');
     $vars['user_role'] = $this->um->getUserRole(' and r.code !="super_admin" ');
     $vars['active'] = 'UM';
     $vars['dataArr'] = $this->input->post();
     $vars['heading'] = 'Add User';
     $vars['content_view'] ='addedituser';
     $this->load->view('layout',$vars);

    }

  function user_validation(){
     $user_role = $this->input->post('user_role');
     $getuserRoleByID = $this->um->getuserRolebyId($user_role);
     $this->form_validation->set_rules('first_name','First Name','trim|required');
     $this->form_validation->set_rules('user_role','User Role','trim|required');
     $this->form_validation->set_rules('last_name','Last Name','trim|required');
     $this->form_validation->set_rules('user_name','User Name','trim|required|is_unique[user.user_name]');    
     $this->form_validation->set_rules('password','Password','trim|required');    
     $this->form_validation->set_rules('c_password','Confirm Password','trim|required|matches[password]');    
        
     if($getuserRoleByID->code=='captain' || $getuserRoleByID->code=='cook'){
       $this->form_validation->set_rules('shipping_company_id','Shipping Company','trim|required');    
       $this->form_validation->set_rules('email','Email','trim|required|valid_email');
       $this->form_validation->set_rules('passport_id','Passport No','trim|required|is_unique[user.passport_id]');
     }
     elseif($getuserRoleByID->code=='vendor'){
       $this->form_validation->set_rules('currency','Currency','trim|required');    
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
       $this->form_validation->set_rules('payment_term','Payment Term','trim|required');
       $this->form_validation->set_rules('vendor_pdf','Vendor PDF','callback_pdf_check');    
     }
     elseif($getuserRoleByID->code=='shipping_company') {
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
       $this->form_validation->set_rules('shipping_company_id','Shipping Company','trim|required'); 
     }
     else{
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
     }

     $this->form_validation->set_error_delimiters('<p class="error" style="display: inline;">','</p>');
     return  $this->form_validation->run();
    }

    function edituser(){
        checkUserSession();
        $user_session_data = getSessionData();
        $user_id = $this->input->post('id');
        $where = ' AND u.user_id = '.$user_id;
        $user = (array) $this->um->getuserdatabyid($where);
        $returnArr['status'] = '100';
        $actionType = $this->input->post('actionType');
        if($actionType == 'save'){
            if($this->new_user_validation()){
             extract($this->input->post());
             $dataArr = array('first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'phone'=>$phone,'address'=>$address,'country'=>$country,'state'=>$state,'city'=>$city,'zipcode'=>$zipcode,'shipping_company_id'=>$shipping_company_id,'passport_id'=>$passport_id);

              if (!empty($_FILES['img']['name'])) {
                   $file = $_FILES['img']['name'];
                   $upload_data = doc_upload($file, 'user');
                   $dataArr['img_url'] =  $upload_data['file_name'];
                   unlink(FCPATH.'uploads/user/'.$user['img_url']);  
               }
             
               if($user['role']=='Vendor'){
                  if(!empty($_FILES['vendor_pdf']['name'])){
                    $pdf_file_name = $_FILES['vendor_pdf']['name'];
                    $pdf_data = pdf_upload($pdf_file_name, 'vendor_pdf');
                    $venArr['vendor_pdf'] = $pdf_data['file_name'];
                    unlink(FCPATH.'uploads/vendor_pdf/'.$user['vendor_pdf']);  
                  }                                     
               $venArr['currency'] = $currency;
               $venArr['payment_term'] = $payment_term;
               $this->um->editVendor($venArr,array('user_id'=>$user_id)); 
             }

            $dataArr['updated_by'] = $user_session_data->user_id;
            $dataArr['updated_on'] = date('Y-m-d H:i:s');
            $this->um->updateuser($dataArr,$user_id);
            $this->session->set_flashdata('succMsg','User updated successfully.');
            $returnArr['status'] = '101';
          }

     }

        $vars['dataArr'] = ($this->input->post('actionType'))? $this->input->post() : $user;
        $vars['dataArr']['user_id'] = $user['user_id']; 
        $vars['company'] = $this->cm->getAllshippingCompany(' and c.status =1','R');
        $data = $this->load->view('edituser',$vars,true);    
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }
    
    function changeUserPassword(){
        $user_id = $this->input->post('id');
        $where = ' AND u.user_id = '.$user_id;
        $returnArr['status'] = '100';
        $actionType = $this->input->post('actionType');
        if($actionType == 'save'){
            if($this->change_password_validation()){
            extract($this->input->post());
            $data = array('password'=>md5($password));
            $this->um->updateuser($data,$user_id);
            $this->session->set_flashdata('succMsg','User Password updated successfully.');
            $returnArr['status'] = '101';
            }
        }
        $user = get_object_vars($user);
        $vars['user_id'] = $user_id;
        $data = $this->load->view('change_password',$vars,true);    
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    function change_password_validation(){
        $this->form_validation->set_rules('password','Password','trim|required');    
        $this->form_validation->set_rules('c_password','Confirm Password','trim|required|matches[password]');    
        $this->form_validation->set_error_delimiters('<p class="error" style="display: inline;">','</p>');
        return  $this->form_validation->run();   
    }

    function logout(){
       user_logout();   
    }

    function new_user_validation(){
     $user_id = $this->input->post('id');
     $where = ' AND u.user_id = '.$user_id;
     $user = (array) $this->um->getuserdatabyid($where);
     $this->form_validation->set_rules('first_name','First Name','trim|required');
     $this->form_validation->set_rules('last_name','Last Name','trim|required');
      
     if($user['role']=='Ship Captain' || $user['role']=='Ship Cook'){
       $this->form_validation->set_rules('shipping_company_id','Shipping Company','trim|required');  
       $this->form_validation->set_rules('email','Email','trim|required|valid_email');
       $this->form_validation->set_rules('passport_id','Passport No','trim|required|callback_passpord_auth');
     }
     elseif($user['role']=='Vendor'){
       $this->form_validation->set_rules('currency','Currency','trim|required');    
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_email_auth');
       $this->form_validation->set_rules('payment_term','Payment Term','trim|required');   
     }
     else{
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_email_auth');
     }

     $this->form_validation->set_message('passpord_auth','sorry ! this Passport ID already exists');
     $this->form_validation->set_message('email_auth','sorry ! this Email already exists');
     $this->form_validation->set_error_delimiters('<p class="error" style="display: inline;">','</p>');
     return  $this->form_validation->run();
    }

  function new_user_validation_2(){
     $user_id = $this->input->post('id');
     $where = ' AND u.user_id = '.$user_id;
     $user = (array) $this->um->getuserdatabyid($where);
     $this->form_validation->set_rules('first_name','First Name','trim|required');
     $this->form_validation->set_rules('last_name','Last Name','trim|required');
      
     if($user['role']=='Ship Captain' || $user['role']=='Ship Cook'){
          $this->form_validation->set_rules('email','Email','trim|required|valid_email');
         // $this->form_validation->set_rules('passport_id','Passport No','trim|required|callback_passpord_auth');
     }
     elseif($user['role']=='Vendor'){
      // $this->form_validation->set_rules('currency','Currency','trim|required');    
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_email_auth');
      // $this->form_validation->set_rules('payment_term','Payment Term','trim|required');   
     }
     else{
       $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_email_auth');
     }

     //$this->form_validation->set_message('passpord_auth','sorry ! this Passport ID already exists');
     $this->form_validation->set_message('email_auth','sorry ! this Email already exists');
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
          return false;
          die;
         }
     }    
    }

   function passpord_auth(){
     $user_id = $this->input->post('id');
     $passport_id = trim($this->input->post('passport_id'));
     
     $data = $this->um->check_passport($user_id);
     $i = 0;
     foreach ($data as $row){
         if($row->passport_id == $passport_id){
            $i=1;
         }else{
            $i=0;
         }
     }
     if($i>0){
        return false;
     }

     return true;
   } 

    function deleteUser(){
     $id = $this->input->post('id');
     $delete = $this->input->post('status');
     $delete = ($delete==0) ? 1 : 0;
      if($delete == 0){
        $status = 1;        
     }else{
         $status = 0;
     }
     $this->um->deleteUser($id,$delete,$status);
     $msg = ($delete==0) ? 'User Restored successfully'  : 'User deleted successfully.' ;
     $this->session->set_flashdata('succMsg',$msg); 
    }

   function removeUser(){
     $id = $this->input->post('id');
     $role = $this->deleteRole($id);  
     if($role == 'Go'){
     $this->um->removeUser($id);
     $this->session->set_flashdata('succMsg','User Removed successfully.');
     }else{
     $this->session->set_flashdata('errMsg','Sorry ! Something Went Wrong.');    
     }
   }

    function deleteRole($id){
    $validate = $this->um->deleteUserRole($id);
    return $validate ;
    } 

    function updateProfile($user_id){
        checkUserSession();
        $user_session_data = getSessionData();
        $actionType = $this->input->post('actionType');
       if($actionType=='save'){
        if($this->new_user_validation_2()){
        extract($this->input->post());
        $data = array('first_name'=>$first_name,'last_name'=>$last_name,'email'=>$email,'phone'=>$phone,'address'=>$address,'country'=>$country,'state'=>$state,'city'=>$city,'zipcode'=>$zipcode);
        $data['updated_by'] = $user_session_data->user_id;
        $data['updated_on'] = date('Y-m-d H:i:s'); 
        //print_r($_FILES);die;
        if(!empty($_FILES['img']['name'])) {
            $file = $_FILES['img']['name'];
           $upload_data =  doc_upload($file, 'user');
            $data['img_url'] =  $upload_data['file_name'];  
        }

        if($user_session_data->code=='vendor'){
          $venArr['bank_name'] = $bank_name;
          $venArr['holder_name'] = $holder_name;
          $venArr['ac_number'] =$ac_number;
          $venArr['ibn_number'] = $ibn_number;
          $venArr['swift_code'] = $swift_code;
          $venArr['bank_address'] = $bank_address;
          
          $this->um->editVendor($venArr,array('user_id'=>base64_decode($user_id)));
        }

        $this->um->updateuser($data,base64_decode($user_id));
        $userinfo = $this->um->login($user_name, $user_session_data->password);
        set_session($userinfo);
        $this->session->set_flashdata('succMsg','User updated successfully.');  
        redirect(base_url().'user/updateProfile/'.($user_id));
       }
     }
    
     $vars['active'] = 'edit_profile';
     $vars['heading'] = 'Manage Profile';
     $where = ' AND u.user_id = '.base64_decode($user_id);
     $user = $this->um->getuserdatabyid($where);
     $vars['dataArr'] = (!empty($this->input->post())) ? $this->input->post() : get_object_vars($user);
     $vars['dataArr']['user_id'] = $user->user_id; 
  
     $vars['content_view'] = 'manageprofile.php';
     $this->load->view('layout',$vars);    
    }
    
    function changePassword($user_id=''){
     checkUserSession();
     $us = getSessionData();
     if($this->password_validation()){
      $new_password = $this->input->post('new_password');
      $data = array('password'=>md5($new_password));
      $this->um->updateuser($data,base64_decode($user_id));
      $this->session->set_flashdata('succMsg','User Password updated successfully.');
      
      $userinfo = $this->um->login($us->user_name, md5($new_password));
      set_session($userinfo);
     }
     $vars['user_session_data'] = getSessionData();
     $vars['active'] = 'change_password';
     $vars['heading'] = 'Manage Profile';  
     $vars['content_view'] = 'manageprofile.php';
     $vars['dataArr'] = $this->input->post();
     $this->load->view('layout',$vars);      
    }


    function password_validation(){
    $this->form_validation->set_rules('password','Password','trim|required|callback_password_check');
    $this->form_validation->set_rules('new_password','New Password','trim|required');    
    $this->form_validation->set_rules('c_password','Confrim Password','trim|required|matches[new_password]');    
    $this->form_validation->set_message('password_check', 'Current Password is Wrong !');
    $this->form_validation->set_error_delimiters('<p class="error" style="display: inline;">','</p>');
    return $this->form_validation->run();
    }

    function password_check(){
     $us = getSessionData();
     $password = $this->input->post('password');
     if(!empty($password)){
     if(md5($password) == $us->password){
       return true;  
     }else{
       return false;  
     }
     }
    }

   function getUserByCompanyId(){
     checkUserSession();
     $shipping_company_id = $this->input->post('shipping_company_id');
     $ship_id = $this->input->post('ship_id');
     
     $c_returnArr = '<option value="">Select</option>';
     $ct_returnArr = '<option value="">Select</option>';
     if($shipping_company_id){
      $captain = $this->um->getalluserlist(' AND r.code = "captain" AND u.shipping_company_id ='.$shipping_company_id,'R');
      $cook = $this->um->getalluserlist(' AND r.code = "cook" AND u.shipping_company_id ='.$shipping_company_id,'R');
      // if(!empty($ship_id)){
      //   $shipData = $this->cm->getAllShips(' And s.ship_id = '.$ship_id,'R');
      //   $captain_id = $shipData[0]->captain_user_id;
      //   $cook_id = $shipData[0]->cook_user_id;
      // }
      //   $shipData = $this->cm->getAllShips(' And s.shipping_company_id = '.$shipping_company_id,'R');
      //   $assignedCaptains = array();
      //   $assignedCooks = array();
      //   if(!empty($shipData)){
      //       foreach($shipData as $ship){
      //           $assignedCaptains[] = $ship->captain_user_id;
      //           $assignedCooks[] = $ship->cook_user_id;
      //       }
      //   }
      
      // $captain_selected = ''; $cook_selected = '';$captain_disabled='';$cook_disabled='';
      if(!empty($captain)){
        foreach ($captain as $row) {
            // $captain_selected = ($row->user_id == $captain_id)?'selected':'';
            // $captain_disabled = (in_array($row->user_id,$assignedCaptains))?'disabled':'';
            $c_returnArr .= '<option value="'.$row->user_id.'"'.$captain_selected.' '.$captain_disabled.'>'.ucwords($row->name).'</option>';
         } 
      }
      if(!empty($cook)){
        foreach ($cook as $row1) {
            // $cook_selected = ($row1->user_id == $cook_id)?'selected':'';
            // $cook_disabled = (in_array($row1->user_id,$assignedCooks))?'disabled':'';
            $ct_returnArr .= '<option value="'.$row1->user_id.'"'.$cook_selected.' '.$cook_disabled.'>'.ucwords($row1->name).'</option>';
         } 
      } 

     }
    echo json_encode(array('c_data'=>$c_returnArr,'ct_data'=>$ct_returnArr));
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
        }else{
            if(empty($id)){
                $this->form_validation->set_message('pdf_check', 'Please choose a file to upload.');
                return false;                
            }
        }
    }
  
  function getShipsByCompanyId(){
    checkUserSession();
     $shipping_company_id = $this->input->post('shipping_company_id');
     $ship_id = $this->input->post('ship_id');
     $returnArr = '<option value="">Select Vessel</option>';
     if(!empty($shipping_company_id)){
      $where = ' and s.status = 1 AND s.shipping_company_id = '.$shipping_company_id;
     }
     else{
       $where = ' and s.status = 1';
     }
     $data = $this->cm->getAllShips($where,'R');
         if(!empty($data)){
           foreach ($data as $key => $val) {
             $returnArr .= '<option value="'.$val->ship_id.'" '.$selected.'>'.ucwords($val->ship_name).'</option>';  
           }
        } 
    echo json_encode(array('data'=>$returnArr));
  } 
  

function getShipsForDashboard(){
    checkUserSession();
     $shipping_company_id = $this->input->post('shipping_company_id');
     $ship_id = $this->input->post('ship_id');
     $returnArr = '<option value="">Select Vessel</option>';
     if(!empty($shipping_company_id)){
      $where = ' and s.status = 1 AND s.shipping_company_id = '.$shipping_company_id;
     $data = $this->cm->getAllShips($where,'R');
         if(!empty($data)){
           foreach ($data as $key => $val) {
             // if(empty($ship_id)){
             //   $selected = ($val->is_default==1) ? 'selected' : '';        
             // }else{
             //    $selected = ($val->ship_id==$ship_id) ? 'selected' : '';      
             // }
             $returnArr .= '<option value="'.$val->ship_id.'" '.$selected.'>'.ucwords($val->ship_name).'</option>';  
           }
        } 
    }
    echo json_encode(array('data'=>$returnArr));
  } 

 function getExtraMealsData(){
    checkUserSession();
    $shipping_company_id = trim($this->input->post('shipping_company_id')); 
    $ship_id = trim($this->input->post('ship_id'));
    $year = trim($this->input->post('year'));
    $month = trim($this->input->post('month'));
    $returnArr = '';                            
     $ship_crew = $sing_b = $sing_l = $sing_d = $numery_b = $numery_l = $numery_d = $officials_b = $officials_l = $officials_d = $superintendent_b = $superintendent_l = $superintendent_d = $owners_b = $owners_l = $owners_d = $charterers_b = $charterers_l = $charterers_d = $other_b = $other_l = $other_d =  0;
   if((!empty($shipping_company_id)) && (!empty($ship_id)) ){  
    $where = ' AND em.ship_id = '.$ship_id.' AND em.month ='.$month.' AND em.year ='.$year; 
    $data = $this->cm->getExtraMealDetails($where); 
    if(!empty($data)){
    foreach ($data as $row) {
      $ship_crew = $ship_crew + $row->ship_crew;
      $sing_b = $sing_b + $row->sing_b;
      $sing_l = $sing_l + $row->sing_l;
      $sing_d = $sing_d + $row->sing_d;
      $numery_b = $numery_b + $row->numery_b;
      $numery_l = $numery_l + $row->numery_l;
      $numery_d = $numery_d + $row->numery_d;
      $officials_b = $officials_b + $row->officials_b;
      $officials_l = $officials_l + $row->officials_l;
      $officials_d = $officials_d + $row->officials_d;
      $superintendent_b = $superintendent_b + $row->superintendent_b;
      $superintendent_l = $superintendent_l + $row->superintendent_l;
      $superintendent_d = $superintendent_d + $row->superintendent_d;
      $owners_b =$owners_b + $row->owners_b;
      $owners_l =$owners_l + $row->owners_l;
      $owners_d =$owners_d + $row->owners_d;
      $charterers_b = $charterers_b + $row->charterers_b;
      $charterers_l = $charterers_l + $row->charterers_l;
      $charterers_d = $charterers_d + $row->charterers_d;
      $other_b = $other_b + $row->other_b;
      $other_l = $other_l + $row->other_l;
      $other_d = $other_d + $row->other_d;
      $returnArr .=' <tr>
            <td>'.$row->day.'</td>
            <td>'.ucwords($row->ship_port).'</td>
            <td>'.$row->ship_crew.'</td>            
            <td>'.$row->sing_b.'</td>            
            <td>'.$row->sing_l.'</td>            
            <td>'.$row->sing_d.'</td>            
            <td>'.$row->numery_b.'</td>            
            <td>'.$row->numery_l.'</td>            
            <td>'.$row->numery_d.'</td>
            <td>'.$row->officials_b.'</td>            
            <td>'.$row->officials_l.'</td>            
            <td>'.$row->officials_d.'</td>
            <td>'.$row->superintendent_b.'</td>            
            <td>'.$row->superintendent_l.'</td>            
            <td>'.$row->superintendent_d.'</td>
            <td>'.$row->owners_b.'</td>            
            <td>'.$row->owners_l.'</td>            
            <td>'.$row->owners_d.'</td>
            <td>'.$row->charterers_b.'</td>            
            <td>'.$row->charterers_l.'</td>            
            <td>'.$row->charterers_d.'</td>
            <td>'.$row->other_b.'</td>            
            <td>'.$row->other_l.'</td>            
            <td>'.$row->other_d.'</td>
            </tr>';
       }
     $returnArr .= '<tr>
        <td>Total:</td>
        <td></td>
        <td id="ship_crew">'.(($ship_crew) ? $ship_crew : 0).'</td>
        <td class="E37" id="sing_b">'.(($sing_b) ? $sing_b : 0).'</td>
        <td class="E37" id="sing_l">'.(($sing_l) ? $sing_l : 0).'</td>
        <td class="E37" id="sing_d">'.(($sing_d) ? $sing_d : 0).'</td>
        <td class="E37" id="numery_b">'.(($numery_b) ? $numery_b : 0).'</td>
        <td class="E37" id="numery_l">'.(($numery_l) ? $numery_l : 0).'</td>
        <td class="E37" id="numery_d">'.(($numery_d) ? $numery_d : 0).'</td>
        <td class="E37" id="officials_b">'.(($officials_b) ? $officials_b : 0).'</td>
        <td class="E37" id="officials_l">'.(($officials_l) ? $officials_l : 0).'</td>
        <td class="E37" id="officials_d">'.(($officials_d) ? $officials_d : 0).'</td>
        <td class="E37" id="superintendent_b">'.(($superintendent_b) ? $superintendent_b : 0).'</td>
        <td class="E37" id="superintendent_l">'.(($superintendent_l) ? $superintendent_l : 0).'</td>
        <td class="E37" id="superintendent_d">'.(($superintendent_d) ? $superintendent_d : 0).'</td>
        <td class="E37" id="owners_b">'.(($owners_b) ? $owners_b : 0).'</td>
        <td class="E37" id="owners_l">'.(($owners_l) ? $owners_l : 0).'</td>
        <td class="E37" id="owners_d">'.(($owners_d) ? $owners_d : 0).'</td>
        <td class="E37" id="charterers_b">'.(($charterers_b) ? $charterers_b : 0).'</td>
        <td class="E37" id="charterers_l">'.(($charterers_l) ? $charterers_l : 0).'</td>
        <td class="E37" id="charterers_d">'.(($charterers_d) ? $charterers_d : 0).'</td>
        <td class="E37" id="other_b">'.(($other_b) ? $other_b : 0).'</td>
        <td class="E37" id="other_l">'.(($other_l) ? $other_l : 0).'</td>
        <td class="E37" id="other_d">'.(($other_d) ? $other_d : 0).'</td>
                    </tr>';

     $returnArr1 = '<div class="col-sm-3"><label>Full Compliment : '.$data[0]->full_compliment.'</label></div>
        <div class="col-sm-3"><label>Extra Meals : '.$data[0]->extra_meals.'</label></div>
         <div class="col-sm-3"><label>Total Man/Days : '.$data[0]->total_man_days.'</label></div>';                               
     }
     else{
       $returnArr .='<tr><td colspan="24" align="center"><strong>No Data Available</strong></td></tr>' ;
     }
   }
   else{
       $returnArr .='<tr><td colspan="24" align="center"><strong>No Data Available</strong></td></tr>' ;
     }
     echo json_encode(array('data'=>$returnArr,'data1'=>$returnArr1));    
    }

   function setAsDefault(){
     checkUserSession();
     $shipping_company_id = trim($this->input->post('shipping_company_id'));
     $ship_id = trim($this->input->post('ship_id'));
     $returnArr['status'] = 100;  
     if(!empty($shipping_company_id) && !empty($ship_id)){
       $this->db->update('shipping_company',array('is_default'=>0));
       $this->db->update('ships',array('is_default'=>0));
       
       $this->db->update('shipping_company',array('is_default'=>1),array('shipping_company_id'=>$shipping_company_id));
       $this->db->update('ships',array('is_default'=>1),array('ship_id'=>$ship_id));

       $this->session->set_flashdata('succMsg','Default setting updated successfully.');  
     }
     echo json_encode($returnArr);
    }  
    
   function testEmail(){
     $to = 'prafull.patidar@wsisrdev.com';
     $subject = 'SMTP Test';
     $message = 'user smtp running successfully';
     $retunrMsg = $this->um->sendMail($to,$subject,$message);
     echo $retunrMsg;die;
   } 
   
  function getNationality(){
    checkUserSession();
    extract($this->input->post()); 
     if(!empty($user_id)){
     $data = (array) $this->um->getuserdatabyid(' AND u.user_id = '.$user_id);
     $returnArr['name'] = $data['country'];
    }
    echo json_encode($returnArr);
   }

  function getCompanyPendingAmount(){
    checkUserSession();
    extract($this->input->post()); 
     $where = '';
       if($date != ''){
          $date_range = explode(' - ', $date);
          $cnvrtd_end_date = $cnvrtd_end_date = '';
          $strt_dt = $date_range[0];
          $end_dt = $date_range[1];
          $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
          $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
          $where .= " AND date(ci.invoice_date) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') ";              
        }

     if($due_date){
       $where .= ' and ci.due_date = "'.convertDate($due_date,'','Y-m-d').'"'; 
     }   

     if(!empty($company_id) && !empty($ship_id)){
       $where .= ' AND sc.shipping_company_id ='.$company_id.' and s.ship_id = '.$ship_id;
       $group_by = ' group by s.ship_id';
     }
     elseif(!empty($company_id)){
        $where .= ' AND sc.shipping_company_id ='.$company_id;
        $group_by = ' group by s.ship_id';
     }
     else{
       $group_by = " group by sc.shipping_company_id "; 
     }


    $data = $this->um->getCompanyPendingAmount($where,$group_by);
    $chart_series = array();
    $chart_column = array();
    if($data){
      foreach ($data as $row) {
          if(!empty($company_id)){
             $chart_column[] = ucfirst($row->ship_name);
             $chart_series[] = round($row->amount);     
          }
          else{
             $chart_column[] = ucfirst($row->company_name);
             $chart_series[] = round($row->amount);            
           }   
        }  
    }

   echo json_encode(array('chart_type' =>$chart_type,'series'=>$chart_series,'columns' =>$chart_column,'xtitle' =>'Amount($)'));
  }  
  
  function getCompanyReceivedAmount(){
    checkUserSession();
    extract($this->input->post());
    $where = '';

    if($date != ''){
      $date_range = explode(' - ', $date);
      $cnvrtd_end_date = $cnvrtd_end_date = '';
      $strt_dt = $date_range[0];
      $end_dt = $date_range[1];
      $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
      $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
      $where .= " AND date(t.created_on) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') ";              
    }

    if(!empty($company_id) && !empty($ship_id)){
       $where .= ' AND sc.shipping_company_id ='.$company_id.' AND s.ship_id = '.$ship_id;
       $group_by = ' group by s.ship_id ';
     }
     elseif(!empty($company_id)){
        $where .= ' AND sc.shipping_company_id ='.$company_id;
        $group_by = ' group by s.ship_id';
     }
     else{
       $group_by = " group by sc.shipping_company_id "; 
     } 

    $data = $this->um->getCompanyReceivedAmount($where,$group_by);
   // echo $this->db->last_query();die;
    $chart_series = array();
    $chart_column = array();
    if($data){
      foreach ($data as $row) {
         
         if(!empty($company_id)){
             $chart_column[] = ucfirst($row->ship_name);
             $chart_series[] = round($row->amount);     
         }
         else{
             $chart_column[] = ucfirst($row->company_name);
             $chart_series[] = round($row->amount);            
         }

      }  
    }
    echo json_encode(array('chart_type' => $chart_type,'series'=>$chart_series,'columns' =>$chart_column,'xtitle' =>'Amount($)'));
  }

  function getVendorDueAmount(){
    checkUserSession();
    extract($this->input->post());
       if(!empty($company_id)){
         $where .= ' AND s.shipping_company_id ='.$company_id;
         $group_by = ' group by s.ship_id ';
       }
       else{
         $group_by = ' group by s.shipping_company_id ';
       }

    // if(!empty($company_id)){
    //     $where .= ' AND s.shipping_company_id ='.$company_id;
    //      if($column=='company'){
    //        $group_by = ' group by s.ship_id ';
    //      }
    //      else{
    //        $group_by = 'group by v.vendor_id';
    //      }
    //    }
    //    else{
    //      if($column=='company'){
    //        $group_by = ' group by s.shipping_company_id ';
    //      }
    //      else{
    //        $group_by = 'group by v.vendor_id';
    //      } 
    //    }

     if(!empty($ship_id)){
        $where .= ' AND s.ship_id = '.$ship_id;
     }

     if(!empty($vendor_id)){
        $where .= ' AND v.vendor_id = '.$vendor_id.' ';  
     }   
     
     if($date != ''){
          $date_range = explode(' - ', $date);
          $cnvrtd_end_date = $cnvrtd_end_date = '';
          $strt_dt = $date_range[0];
          $end_dt = $date_range[1];
          $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
          $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
          $where .= " AND date(vi.created_at) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') ";              
        }

    if($due_date){
       $where .= ' and vi.due_date = "'.convertDate($due_date,'','Y-m-d').'"'; 
     }      
     
    $data = $this->um->getVendorDueAmount($where,$group_by);

    // echo $this->db->last_query();die;
    $chart_series = array();
    $chart_column = array();
    if($data){
      foreach ($data as $row) {
        // if($column=='company'){
           
           if(!empty($company_id)){
              $chart_column[] = ucfirst($row->ship_name);
              $chart_series[] = round($row->due_amount);
           }
           else{
              $chart_column[] = ucfirst($row->company_name);
              $chart_series[] = round($row->due_amount);                
           }

        // }
        // else{
        //    $chart_column[] = ucfirst($row->vendor_name);
        //    $chart_series[] = round($row->due_amount);
        // }   
      }  
    }
    echo json_encode(array('chart_type' => $chart_type,'series'=>$chart_series,'columns' =>$chart_column,'xtitle' =>'Amount($)'));
  }


  function getVendorPaidAmount(){
    checkUserSession();
    extract($this->input->post());

     if(!empty($company_id)){
         $where .= ' AND s.shipping_company_id ='.$company_id;
         $group_by = ' group by s.ship_id ';
      }
       else{
         $group_by = ' group by s.shipping_company_id ';
      }    
   
    // if($column=='company'){
      // if(!empty($company_id)){
      //   $where .= ' AND s.shipping_company_id ='.$company_id;
      //    if($column=='company'){
      //      $group_by = ' group by s.ship_id ';
      //    }
      //    else{
      //      $group_by = 'group by vi.vendor_id';
      //    }
      //  }
      //  else{
      //    if($column=='company'){
      //      $group_by = ' group by s.shipping_company_id ';
      //    }
      //    else{
      //      $group_by = 'group by vi.vendor_id';
      //    } 
      //  }

      // $where .= ' AND s.shipping_company_id ='.$company_id;
      // $group_by = ' group by s.shipping_company_id ';

      if($date != ''){
           $date_range = explode(' - ', $date);
           $cnvrtd_end_date = $cnvrtd_end_date = '';
           $strt_dt = $date_range[0];
           $end_dt = $date_range[1];
           $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
           $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
           $where .= " AND date(t.created_on) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') ";              
      } 

       if(!empty($ship_id)){
        $where .= ' AND s.ship_id = '.$ship_id;
       }

       if(!empty($vendor_id)){
        $where .= ' AND vi.vendor_id = '.$vendor_id.' ';  
       }


 
    $data = $this->um->getVendorPaidAmount($where,$group_by);
    $chart_series = array();
    $chart_column = array();
    if($data){
      foreach ($data as $row) {        
        // if($column=='company'){
           if(!empty($company_id)){
              $chart_column[] = ucfirst($row->ship_name);
              $chart_series[] = round($row->paid_amount);
           }
           else{
              $chart_column[] = ucfirst($row->company_name);
              $chart_series[] = round($row->paid_amount);                
           }

        // }
        // else{
        //   $chart_column[] = ucfirst($row->vendor_name);
        //   $chart_series[] = round($row->paid_amount);
        // }
      }
    }
    echo json_encode(array('chart_type' => $chart_type,'series'=>$chart_series,'columns' =>$chart_column,'xtitle' =>'Amount($)'));
  }

  function notificationList(){
    checkUserSession();
    $vars['heading'] = 'Notification List';
    $vars['content_view'] = 'notification_list';
    $this->load->view('layout',$vars);   
  }

 function getAllnotificationList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    
     if(!empty($user_session_data->ship_id) && $user_session_data->code=='captain'){
          $where .= ' and ( n.is_for_master = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;
    }
    elseif(!empty($user_session_data->ship_id) && $user_session_data->code=='cook'){
          $where .= ' and (n.is_for_cook = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;  
    }
    else{
      $where = ' AND n.user_id = '.$user_session_data->user_id.' OR n.is_for_all = 1';  
    }

    if(!empty($status)){
     if($status=='R'){
      $where .= ' AND n.is_read = 1';  

     } 
     elseif($status=='U'){
      $where .= ' AND n.is_read = 0';  
     }  
    }

    if(!empty($created_on)){
      $where .= ' AND n.date ="'.convertDate($created_on,'','Y-m-d').'"';    
    }

    if(!empty($keyword)){
      $where .= ' AND n.title like "%'.trim($keyword).'%"';    
    }

    if((!empty($sort_column)) && (!empty($sort_type))){
       if($sort_column == 'Title'){
         $order_by = 'ORDER BY n.title '.$sort_type;
       }
       elseif($sort_column == 'Description'){
         $order_by = 'ORDER BY n.long_desc '.$sort_type;
       }
       elseif($sort_column == 'Date'){
         $order_by = 'ORDER BY n.date '.$sort_type;
       }
     }else{
            $order_by = 'ORDER BY n.date DESC';
    }
      

    if($download){
              $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'notification.xlsx';
           $arrayHeaderData= array('Title','Description','Date');
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
           $note = $this->um->getNotificationList($where,'R','','',$order_by);
           if(!empty($note)){
            foreach ($note as $row) {
               $k++;  
               $arrayData[] = array($row->title,$row->long_desc,time_elapsed_string($row->date)); 
            }
           } 
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:C'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Notification');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit; 
    }

    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    $countdata = $this->um->getNotificationList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $note = $this->um->getNotificationList($where,'R',$perPage,$offset,$order_by);
    // echo $this->db->last_query();die;
    if($note){
      $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($note)).' of '.$countdata.' entries'; 
     foreach ($note as $row) {
        $style = ($row->is_read==1) ? '' : 'style="font-weight:bold;"';
        $returnArr .= '<tr>
              <td width="3%"><input type="checkbox" class="check_singel" name="check_singel" id="'.$row->notification_id.'" value="'.$row->notification_id.'"></td>
              <td width="10%" '.$style.'>'.$row->title.'</td>';
         if($row->entity=='user'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('user/user_list?highlight_id='.$row->row_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';
         }
         elseif($row->entity=='news'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('news/index?highlight_id='.$row->row_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
        elseif($row->entity=='rfq'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id.'&entity=rfq&ship_id='.$row->ship_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
         elseif($row->entity=='purchase_order'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id.'&entity=purchase_order&ship_id='.$row->ship_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
        elseif($row->entity=='delivery_note'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id.'&entity=delivery_note&ship_id='.$row->ship_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
        elseif($row->entity=='company_invoice'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id.'&entity=company_invoice&ship_id='.$row->ship_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
         elseif($row->entity=='extra_meal'){
            $returnArr .= '<td width="40%" '.$style.'><a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id.'&entity=extra_meal&ship_id='.$row->ship_id).'" onclick="update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';

         }
         else{
            $returnArr .= '<td width="40%" '.$style.'><a href="javascript:void(0)" onclick="showAjaxModel(\'Notification Details\',\'user/view_notification\','.$row->notification_id.',\'\',\'50%\');update_notification_list('.$row->notification_id.');">'.$row->long_desc.'</a></td>';
         }

            $returnArr .=  '<td width="10%" '.$style.'>'.time_elapsed_string($row->date).'</td>';
        $returnArr .='</tr>'; 
      }
     $pagination = $pages->get_links();     
   }
   else{
        $pagination = '';
        $returnArr = '<tr><td colspan="5" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
   }
  echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination)); 
 } 

  function view_notification(){
   checkUserSession();
   $user_session_data = getSessionData();
   $returnArr['status'] = 100;
   extract($this->input->post());
   if(!empty($id)){
    $where = ' AND n.notification_id = '.$id;
    $data = (array) $this->um->getNotifyById($where);
   }
   $returnArr['data'] = '<div class="row">
    <div class="col-sm-12"><label class="col-sm-12">Title</label>
    '.$data['title'].'
    </div>
    </div>
    <div class="row">
    <div class="col-sm-12"><label class="col-sm-12">Description</label>
    '.$data['long_desc'].'
    </div>
    </div>
    <div class="row">
    <div class="col-sm-12"><label class="col-sm-12">Date</label>
    '.convertDate($data['date'],"","d-m-Y | h:i A").'
    </div>
   </div>';
   echo json_encode($returnArr);
  }

  function toggleNotificationList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $user_id = $user_session_data->user_id;
    $cur_page = $this->input->post('cur_page');
    $perPage = 10;
    $offset = ($cur_page * $perPage) - $perPage;
    if(!empty($user_session_data->ship_id) && $user_session_data->code=='captain'){
          $where .= ' and ( n.is_for_master = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;
    }
    elseif(!empty($user_session_data->ship_id) && $user_session_data->code=='cook'){
          $where .= ' and (n.is_for_cook = 1 OR n.is_for_all = 1 ) and n.ship_id ='.$user_session_data->ship_id;  
    }
    else{
      $where = ' AND n.user_id = '.$user_session_data->user_id.' OR n.is_for_all = 1';  
    }

    $all_notify = $this->um->getNotificationList($where,'R',$perPage,$offset,' ORDER BY n.notification_id DESC,n.is_read ASC ');


    if($all_notify){
     foreach ($all_notify as $row){
      $wind_string = $row->long_desc;
       if (strlen($wind_string) > 80)
        {
         $stringCut = substr($wind_string, 0, 80);
         $wind_string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
        }

        if($row->entity=='user'){
            $html.= '<a href="'.base_url('user/user_list?highlight_id='.$row->row_id).'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='news'){
            $html.= '<a href="'.base_url('news/index?highlight_id='.$row->row_id).'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='rfq'){
            $html.= '<a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id).'&entity=rfq&ship_id='.$row->ship_id.'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='purchase_order'){
            $html.= '<a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id).'&entity=purchase_order&ship_id='.$row->ship_id.'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='delivery_note'){
            $html.= '<a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id).'&entity=delivery_note&ship_id='.$row->ship_id.'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='company_invoice'){
            $html.= '<a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id).'&entity=company_invoice&ship_id='.$row->ship_id.'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        elseif($row->entity=='extra_meal'){
            $html.= '<a href="'.base_url('shipping/shippingCompanyDetails/'.base64_encode($row->shipping_company_id).'?highlight_id='.$row->row_id).'&entity=extra_meal&ship_id='.$row->ship_id.'" class="noteRow" onclick=";update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
        else{        
            $html.= '<a class="noteRow" onclick="showAjaxModel(\'Notification Details\',\'user/view_notification\','.$row->notification_id.',\'\',\'50%\');update_notification('.$row->notification_id.')"><div class="release_sec customRelease count_'.$row->notification_id.' '.(($row->is_read==1) ? 'notification-read' : 'notification-unread').'"><div class="media-heading"><div class="nt-heading">'.$row->title.'</div>
                </div><div class="media-text nt-msg">'.$wind_string.'</div><span class="nt-date">'.time_elapsed_string($row->date).'</span></div></a>';
        }
     }
     $data['html'] = $html;
    }
    echo json_encode($data);
  }
  
  function agent_list(){
    checkUserSession();
     $vars['active'] = 'AG';
     $vars['heading'] =  'Agent List';
     $vars['content_view'] = 'agentList';
     $this->load->view('layout',$vars); 
  }


  function getAllagents(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());

    if(!empty($keyword)){
     $where .= " AND ( pa.name like '%".trim($keyword)."%' or  pa.email like '%".trim($keyword)."%' or pa.phone like '%".trim($keyword)."%' or concat(pa.country_code,'',pa.phone) like '%".trim($keyword)."%' or pa.country like '%".trim($keyword)."%' ) ";   
    }

    if($created_on){
          $where .= ' AND date(pa.added_on) = "'.convertDate($created_on,'','Y-m-d').'"'; 
    } 


     if(!empty($status)){
        $status = trim($status);
         if ($status == 'A')
          {
            $where .= " AND pa.`status`= 1 ";
          }
          elseif ($status == 'D')
          {
            $where .= " AND pa.`status`= 0 ";              
          } 
     }

     if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Name')
            {
                $order_by = 'ORDER BY pa.name '.$sort_type;
            }
            elseif($sort_column == 'Agency')
            {
                $order_by = 'ORDER BY pa.agency '.$sort_type;
            }
            elseif($sort_column == 'Email')
            {
                $order_by = 'ORDER BY pa.email '.$sort_type;
            }
            elseif($sort_column == 'Incharge')
            {
                $order_by = 'ORDER BY pa.incharge_name '.$sort_type;
            }
            elseif($sort_column == 'Port Name')
            {
                $order_by = 'ORDER BY pa.port_name '.$sort_type;
            }
            elseif($sort_column == 'Country')
            {
                $order_by = 'ORDER BY pa.country '.$sort_type;
            }
            elseif($sort_column == 'Phone')
            {
                $order_by = 'ORDER BY pa.phone '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY pa.name ASC';
        }

    if($download==1){
      $this->load->library('Excelreader');
      $excel  = new Excelreader();
      $fileName = 'AgentList.xlsx';
           $arrayHeaderData= array('Name','Agency','Email','Incharge Name','Port Name','Country','Phone','Status');
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
           $agent_data = $this->cm->getAllPortAgents($where,'R','','',$order_by);
           if(!empty($agent_data)){
             foreach ($agent_data as $row) {
                $k++;
                $status = ($row->status==1) ? 'Activate' : 'Deactivate';
                $arrayData[] = array(ucfirst($row->name),ucwords($row->agency),$row->email,$row->incharge_name,$row->port_name,$row->country,$row->phone,$status);
             }
           }
           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:H'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'AgentList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
    }    

    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    $countdata = $this->cm->getAllPortAgents($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $agent_data = $this->cm->getAllPortAgents($where,'R',$perPage,$offset,$order_by);
    if($agent_data){
       $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($agent_data)).' of '.$countdata.' entries';
       foreach ($agent_data as $row) {
        $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';
        $edit_agent = checkLabelByTask('edit_agent');
        $edit = $update_status = '';
        if($edit_agent && $row->status==1){
          $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Agent\',\'user/addeditagent\','.$row->agent_id.',\'\',\'70%\');" >Edit</a>';
        }

        if($edit_agent){
           if($row->status == 0){
                $update_status = '<a onclick="updateStatusBox('.$row->agent_id.','.$row->status.',\''.$row->name.'\',\'user/changeStatusAgent\')" href="javascript:void(0)">Activate</a>';   
             }else{
               $update_status = '<a onclick="updateStatusBox('.$row->agent_id.','.$row->status.',\''.$row->name.'\',\'user/changeStatusAgent\')" href="javascript:void(0)">Deactivate</a>';      
             }
        }

        $returnArr .= "<tr>
                          <td  width='10%'>".ucfirst($row->name)."</td>
                          <td width='10%'>".ucwords($row->agency)."</td>
                          <td width='10%'>".$row->email."</td>
                          <td width='13%'>".ucfirst($row->incharge_name)."</td>
                          <td width='10%'>".$row->port_name."</td>
                          <td width='10%'>".ucfirst($row->country)."</td>
                          <td width='10%'>".$row->new_phone."</td>
                          <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y')."</td>
                          <td width='10%'>".ucfirst($row->user_name)."</td>
                          <td width='7%'>".$status."</td>";
        $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                            <li>'.$edit.'</li>
                            <li role="separator" class="divider"></li>
                             <li>'.$update_status.'</li>
                            </ul>
                        </div></td> </tr>';
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
  
  function addeditagent(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = 100;
    $actionType = $this->input->post('actionType');
    $agent_id = $this->input->post('id');
    $second_id = $this->input->post('second_id');
    if(!empty($agent_id)){
      $data = (array) $this->um->getAgentDetailsById(' And pa.agent_id='.$agent_id);
    }

    if($actionType=='save'){
     if($this->validate_agent()){
         $dataArr['name'] = trim($this->input->post('name'));
         $dataArr['email'] = trim($this->input->post('email'));
         $dataArr['phone'] = trim($this->input->post('phone'));
         $dataArr['country_code'] = trim($this->input->post('country_code'));
         $dataArr['agency'] = trim($this->input->post('agency'));
         $dataArr['incharge_name'] = trim($this->input->post('incharge_name'));
         $dataArr['port_name'] = trim($this->input->post('port_name'));
         $dataArr['country'] = trim($this->input->post('country'));
       if(empty($agent_id)){
           $dataArr['added_on'] = date('Y-m-d H:i:s');
           $dataArr['added_by'] = $user_session_data->user_id;
           $agent_id = $this->um->addAgent($dataArr);
            $returnArr['agent_id'] = $agent_id;

           $returnArr['status'] = 200;
           $this->session->set_flashdata('succMsg','Agent added successfully');
       }
       else{
          $this->um->updateAgent($dataArr,array('agent_id'=>$agent_id));
          $returnArr['status'] = 200;
          $this->session->set_flashdata('succMsg','Agent updated successfully'); 
       }
     }
    }
    
    $vars['country'] = $this->um->getAllCountryCode();
    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : $data;
    $vars['agent_id'] = $agent_id;
    $vars['second_id'] = $second_id;
    $data = $this->load->view('add_edit_agent',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }

  function validate_agent(){
    $this->form_validation->set_rules('name','Name','trim|required');
    // if(empty($this->input->post('id'))){
    //   $this->form_validation->set_rules('email','Email','trim|required|is_unique[port_agent.email]');
    // }
    // else{
    //   $this->form_validation->set_rules('email','Email','trim|required|callback_check_agent_email');  
    //   $this->form_validation->set_message('check_agent_email','Email id already exists');
    // }

    $this->form_validation->set_rules('email','Email','trim|required|valid_email');
    $this->form_validation->set_rules('phone','Phone','trim|required|numeric');
    $this->form_validation->set_rules('agency','Agency','trim|required');
    $this->form_validation->set_rules('incharge_name','Incharge Name','trim|required');
    $this->form_validation->set_rules('port_name','Port Name','trim|required');
    $this->form_validation->set_rules('country','Country','trim|required');
    
    return $this->form_validation->run();
  }

  function check_agent_email(){
    $agent_id = $this->input->post('id');
    $email = trim($this->input->post('email'));
    $agent_data = $this->cm->getAllPortAgents(' AND pa.agent_id !='.$agent_id,'R');
    $flag = true;
    if(!empty($agent_data)){
      foreach ($agent_data as $row) {
       if($row->email==$email){
        $flag = false;
       }    
      }  
    }
    return $flag;
  }


   function changeStatusAgent(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $where = 'agent_id ='.$id;
        $status = ($status== '1' )? '0' :'1';
        $this->cm->changestatus('port_agent',$status,$where);
        $this->session->set_flashdata('succMsg','Agent status changed successfully.'); 
    }
   
   function getAgentDetailsByID(){
      checkUserSession();
      $keyword = trim($this->input->post('keyword'));
      $where = ' and pa.status = 1';
      $add_agent = checkLabelByTask('add_agent');
      if($keyword){
        $where .= ' AND FIND_IN_SET("'.$keyword.'",pa.port_name) > 0 ';
      }

      $agent_data = $this->cm->getAllPortAgents($where,'R');
      $data .= '<option value="">Select Agent</option>';
      if(!empty($agent_data)){
        foreach ($agent_data as $row) {
          $data .= '<option value="'.$row->agent_id.'">'.ucwords($row->name).'</option>';  
        }
      }
        if($add_agent){
            $data .= '<option value="add_new">Add New Agent</option>';
        }

      $returnArr['data'] = $data;
      echo json_encode($returnArr);
   }

  function getNotificationCount(){
     checkUserSession();
     $user_session_data = getSessionData();
     $count = getNotificationCount($user_session_data->user_id);
     $returnArr['count'] = $count;
     $returnArr['status'] = 200;
     echo json_encode($returnArr);
  }

  function update_notification(){
   checkUserSession();
   $user_session_data = getSessionData();
   $notification_id = trim($this->input->post('notification_id'));
   if($notification_id){
    $this->db->update('notification',array('is_read'=>1),array('notification_id'=>$notification_id));
   } 
   $returnArr['status'] = 200;
   echo json_encode($returnArr);  
  } 


 function getSessionInfo(){
    $user_session_data = getSessionData();
    echo '<pre>';
    print_r($user_session_data).'<br>';
    $ship_details = getCustomSession('ship_details');
    echo '<pre>';
    print_r($ship_details);
    die;
 }


 function groupUpdateNotify(){
   checkUserSession();
   $type = $this->input->post('type');
   $id =  $this->input->post('ids');
   $returnArr['status'] = 100;
   if(!empty($id)){
     $ids = explode(',',$id);
      for ($i=0; $i < count($ids); $i++) { 
         if($type==2){
           $this->db->update('notification',array('is_read'=>1),array('notification_id'=>$ids[$i]));
         } 
         elseif($type==1){
           $this->db->delete('notification',array('notification_id'=>$ids[$i]));
         }
      }
      
      if($type==2){
       $returnArr['status'] = 200;
       $this->session->set_flashdata('succMsg','Notification updated successfully.');
      } 
      else{
       $returnArr['status'] = 200;
        $this->session->set_flashdata('succMsg','Notification deleted successfully.');
      }

   }
   echo json_encode($returnArr);
 }

  function uploadSing(){
    checkUserSession();
    $returnArr['status'] = 100;
    if(isset($_POST["img"])){
     $allowed_extension = array("jpg", "png", "jpeg", "gif");
     $url_array = explode("/", $_POST["img"]);
     $image_name = end($url_array);
     $image_array = explode(".",$image_name);
     $extension = end($image_array);   
     $image_data = file_get_contents($_POST["img"]);
     $new_image_path = "uploads/delivery_receipt/" . rand() . "." . $extension;
     file_put_contents($new_image_path, $image_data);
     $returnArr['img'] = $new_image_path;  
     $returnArr['status'] = 200;
     $returnArr['msg'] = 'Image upload';
    }
    else
    {
      $returnArr['msg'] = "Image not found";
    }
    echo json_encode($returnArr);
  }


 function role_manager(){
  checkUserSession();   
  $vars['active'] = 'RM';
  $vars['heading'] = 'Role Managment';
  $vars['content_view'] = 'role_list';
  $this->load->view('layout',$vars);   
 } 

 function getAllroleList(){
  checkUserSession();
    $where = ' and r.code not in("super_admin","vendor")  ';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25; 

    if(!empty($keyword)){
     $where .= " AND ( r.role_name like '%".trim($keyword)."%' or  r.code like '%".trim($keyword)."%' or u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name)  like '%".trim($keyword)."%') ";   
    }

    if(!empty($status)){
            $status = trim($status);
            if ($status == 'A')
            {
                $where .= " AND r.`status`= 1 ";
            }
            elseif ($status == 'D')
            {
                $where .= " AND r.`status`= 0 ";              
            } 
    }

    if($created_on){
     $where .= ' AND (date(r.created_on) = "'.convertDate($created_on,'','Y-m-d').'") '; 
    }

    if((!empty($sort_column)) && (!empty($sort_type))){
        if($sort_column == 'Role'){
            $order_by = 'ORDER BY r.role_name '.$sort_type;
         }
         elseif($sort_column == 'Code'){
            $order_by = 'ORDER BY r.code '.$sort_type;
         }
         elseif($sort_column == 'Created On'){
          $order_by = 'ORDER BY r.created_on '.$sort_type;
         }
         elseif($sort_column == 'Created By'){
          $order_by = 'ORDER BY u.first_name '.$sort_type;
         }
    } 

   if($download==1){
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'RoleList.xlsx';
           $arrayHeaderData= array('Role','Code','Created On','Created By','Status');
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
           $roles = $this->um->getallUserRole($where,'R','','',$order_by);
           if($roles){
             foreach ($roles as $row) {
                $k++;
               // $status = ($row->status==1) ? 'Activate' : 'Deactivate';   
               $arrayData[] = array(ucwords($row->role_name),$row->code,(($row->created_on) ? convertDate($row->created_on,'','d-m-Y') : '-'),ucwords($row->user_name)); 
             }
           } 

       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:E'.$k,'border'=>'THIN')) 
              );   
       $arrayBundleData['listColumn'] = $listColumn;
       $arrayBundleData['arrayData'] = $arrayData;
       $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'RoleList');
       readfile(FCPATH.'uploads/sheets/'.$fileName);
       unlink(FCPATH.'uploads/sheets/'.$fileName);
       exit;  

       } 

   $countdata = $this->um->getallUserRole($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page);
   $roles = $this->um->getallUserRole($where,'R',$offset,$perPage,$order_by);
   $edit_role = checkLabelByTask('edit_role');
   $manage_permission = checkLabelByTask('manage_permission');   
   if($roles){
    $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($roles)).' of '.$countdata.' entries';
     foreach ($roles as $row) {
        // $status = ($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>';
        if($edit_role){
          // if($row->status==1){
           // $change_status = '<a onclick="updateStatusBox('.$row->role_id.','.$row->status.',\''.$row->role_name.'\',\'user/changeRoleStatus\')" href="javascript:void(0)">Deactivate</a>';
           $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Role\',\'user/add_edit_role\','.$row->role_id.',\'\',\'50%\');" >Edit</a>';
           
          // }
          // else{
          //  $change_status = '<a onclick="updateStatusBox('.$row->role_id.','.$row->status.',\''.$row->role_name.'\',\'user/changeRoleStatus\')" href="javascript:void(0)">Activate</a>';
          // }  
        }   

        if($manage_permission){
            $permission = '<a target="_blank" href="'.base_url().'user/manage_permission/'.base64_encode($row->role_id).'">Manage Permissions</a>';
        } 
       $returnArr .= "<tr>
                       <td width='10%'>".ucwords($row->role_name)."</td>
                       <td width='10%'>".$row->code."</td>
                       <td width='10%'>".(($row->created_on) ? convertDate($row->created_on,'','d-m-Y') : '-')."</td>
                       <td width='10%'>".ucwords($row->user_name)."</td>";
                       // "<td width='10%'>".$status."</td>";
                $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li>'.$edit.'</li>
                                <li>'.$permission.'</li>     
                                <li>'.$change_status.'</li>
                                </ul>
                                </div></td> </tr>';
         $pagination = $pages->get_links();

     }
   }
   else{
    $pagination = '';
    $returnArr = '<tr><td colspan="6" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
   } 
 
  echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
 }


  function changeRoleStatus(){
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $where = 'role_id ='.$id;
    $status = ($status== '1' )? '0' :'1';
    $this->cm->changestatus('role',$status,$where);
    $this->session->set_flashdata('succMsg','Role status changed successfully.');  
  }

  function add_edit_role(){
   checkUserSession(); 
   $user_session_data = getSessionData();
   $role_id = $this->input->post('id');
   if(!empty($role_id)){
     $data = (array) $this->um->getuserRolebyId($role_id);
   }
   // echo $this->db->last_query();die;
   $returnArr['status'] = 100;
   $actionType = $this->input->post('actionType');
   if($actionType=='save'){
       if($this->role_validation()){
         $dataArr['role_name'] = trim($this->input->post('role_name')); 
          if(empty($role_id)){
            $dataArr['code'] = strtolower(str_replace(' ','_',$dataArr['role_name'])); 
            $dataArr['created_on'] = date('Y-m-d H:i:s');
            $dataArr['created_by'] = $user_session_data->user_id;
            $this->um->addUserRole('role',$dataArr);
            $returnArr['status'] = 200;
            $this->session->set_flashdata('succMsg','Role added successfully.');
          }
          else{
            $dataArr['updated_on'] = date('Y-m-d H:i:s');
            $dataArr['updated_by'] = $user_session_data->user_id;
            $this->um->editRole($dataArr,array('role_id'=>$role_id));
            $returnArr['status'] = 200;
            $this->session->set_flashdata('succMsg','Role updated successfully.');
          }
       }
   }

   $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : $data;
   $vars['role_id'] = $role_id;
   $data = $this->load->view('add_edit_role',$vars,true);
   $returnArr['data'] = $data;
   echo json_encode($returnArr);  
  }

  function role_validation(){
    $role_id = $this->input->post('id');
    if(empty($role_id)){
     $this->form_validation->set_rules('role_name','Role Name','trim|required|is_unique[role.role_name]');
    }
    else{
     $this->form_validation->set_rules('role_name','Role Name','trim|required|callback_check_role_name'); 
     $this->form_validation->set_message('check_role_name','Role name already exists');  
    }
    return $this->form_validation->run();
  }

  function check_role_name(){
    $role_id = $this->input->post('id');
    $name = trim($this->input->post('role_name'));
    $data = $this->um->getallUserRole(' AND r.role_id !='.$role_id,'R');
    foreach ($data as $row){
         if($name==$row->role_name){
             $return = false;
             break;
         }
         else{
             $return = true;  
         }
     }
   return $return; 
  }

  function manage_permission($role_id=''){
    checkUserSession();
    $role_id = base64_decode($role_id);
    if($this->input->post('save')){
       $this->um->deleteRoleTask('role_id='.$role_id);
            $roleTask = $this->input->post('roleTask');
            if(!empty($roleTask)){
                $i=0;
                $insertArr = array();
                foreach ($roleTask as $task) {
                    $insertArr[$i]['role_id'] = $role_id;
                    $insertArr[$i]['task_id'] = $task;
                    $i++;
                }
                $this->session->set_flashdata('succMsg', 'Role Tasks updated successfully.');
                if(!empty($insertArr)){
                    $this->db->insert_batch('role_task', $insertArr);
                }
            }
    }   
    $vars['active'] = 'RM';
    $vars['heading'] = 'Role Managment';
    $vars['roleTasks'] = $this->um->getAllTaskByGroup('',$role_id);
    $vars['content_view'] = 'manageRoleTask';
    $this->load->view('layout',$vars);
  }

   
  function getVictuallingReportGraph(){
    checkUserSession();
    extract($this->input->post());
    $where = ' and vs.status = 1';

    if(!empty($shipping_company_id)){
      $where .= ' AND s.shipping_company_id = '.$shipping_company_id;    
    }

    if(!empty($ship_id)){
      $where .= ' AND vs.ship_id = '.$ship_id;  
    }


    if($date != ''){
      $date_range = explode(' - ', $date);
      $strt_dt = $date_range[0];
      $end_dt = $date_range[1];              

        $startDate = DateTime::createFromFormat('m/d/Y', $strt_dt);
        $endDate   = DateTime::createFromFormat('m/d/Y', $end_dt);
        $months = [];
        $periodStart = clone $startDate;
        while ($periodStart <= $endDate) {
            $months[] = $periodStart->format('m-Y');
            $periodStart->modify('first day of next month');
        }

    }

    $month_where = '';

    if(!empty($months)){
        $where .= ' AND ';
        foreach ($months as $row) {
            $row = explode('-',$row);
            $month = $row[0];
            $year = $row[1];
            if(empty($month_where)){
              $month_where .= ' (vs.month = '.$month.' AND vs.year = '.$year.')';
            }
            else{
              $month_where .= ' OR (vs.month = '.$month.' AND vs.year = '.$year.')';  
            }
        }
    }

    // if(!empty($time)){
    //    if($time=='month_3'){
    //     $where .= ' AND vs.month BETWEEN '.date("m",strtotime("-3 Months")).' AND '.date('m').' AND vs.year = '.date('Y'); 
    //    }
    //    elseif($time=='month_6') {
    //      $where .= ' AND vs.month BETWEEN '.date("m",strtotime("-6 Months")).' AND '.date('m').' AND vs.year = '.date('Y'); 
    //    }
    //    elseif($time=='c_year') {
    //      $where .= ' AND vs.year = '.date('Y'); 
    //    }
    //    elseif ($time=='last_two') {
    //     $where .= ' AND vs.year BETWEEN '.date("Y",strtotime("-1 year")).' AND '.date('Y');
    //    }
    // }
    
    if(!empty($month_where)){
       $where = $where.'('.$month_where.')'; 
    }

    $data = $this->um->getreportofVc($where);
    
    // $groupedData = [];
    // if($group_by=='month'){
        $dataArr = array();
        $s = array();
        $m = array();

        if(!empty($data)){
            foreach ($data as $row) {
             $monthNum  = $row->month;
             $dateObj   = DateTime::createFromFormat('!m', $monthNum);
             $monthName = $dateObj->format('F');
             $m[] = $k = $monthName.' '.$row->year;
             $s[] = $id = $row->ship_name;
             $dataArr[$k][$id] = $row->daily_rate_per_man; 
            }
        }
        $m = $months = array_values(array_unique($m));
        $s = array_values(array_unique($s)); 

   // }
   // elseif($group_by=='quarter'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $quarter = ceil($month / 3); 
   //      $quarterKey = 'Q'.$quarter.'-'.$year;
   //      if (!isset($groupedData[$row->ship_name][$quarterKey])) {
   //          $groupedData[$row->ship_name][$quarterKey] = [];
   //      }
   //      $groupedData[$row->ship_name][$quarterKey][] = $row->daily_rate_per_man;
   //   }
   // }
   // elseif($group_by=='half_year'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $halfYear = ($month <= 6) ? 'H1' : 'H2';
   //      $halfYearKey = $halfYear . '-' . $year; // Half-year key
   //      if (!isset($groupedData[$row->ship_name][$halfYearKey])) {
   //          $groupedData[$row->ship_name][$halfYearKey] = [];
   //      }
   //      $groupedData[$row->ship_name][$halfYearKey][] = $row->daily_rate_per_man;  
   //   }
   // }
   // elseif($group_by=='year'){
   //  foreach ($data as $row) {
   //      $groupedData[$row->ship_name][$row->year][] = $row->daily_rate_per_man;
   //   }
   // }

    $seriesData = [];
    // if($group_by=='month'){
      for ($i=0; $i <count($m);$i++) { 
          $mk = $m[$i]; 
           for ($j=0; $j<count($s);$j++) {
             $sk = $s[$j];
             $averageRate = isset($dataArr[$mk][$sk]) ? $dataArr[$mk][$sk] : 0;;
             $seriesData[$sk][] = floatval($averageRate);; 
            }
         }
    // }
    // else{
        // $shipNames = array_keys($groupedData);
        // $month_wise_data = [];
        //  foreach ($shipNames as $shipName) {
        //     foreach ($groupedData[$shipName] as $month => $rates) {
        //         $averageRate = array_sum($rates) / count($rates);
        //         $seriesData[$shipName][] = $averageRate;
        //     }
        //   }
        // $months = array_keys(reset($groupedData));
    // }
   echo json_encode(array('seriesData'=>$seriesData,'months'=>$months));
  }



  function condemned_stock_graph(){
    checkUserSession();
    extract($this->input->post());
    $where = ' and cr.status = 2';

    if(!empty($shipping_company_id)){
      $where .= ' AND s.shipping_company_id = '.$shipping_company_id;    
    }

    if(!empty($ship_id)){
      $where .= ' AND cr.ship_id = '.$ship_id;  
    }

     if($date != ''){
      $date_range = explode(' - ', $date);
      $strt_dt = $date_range[0];
      $end_dt = $date_range[1];              

        $startDate = DateTime::createFromFormat('m/d/Y', $strt_dt);
        $endDate   = DateTime::createFromFormat('m/d/Y', $end_dt);
        $months = [];
        $periodStart = clone $startDate;
        while ($periodStart <= $endDate) {
            $months[] = $periodStart->format('m-Y');
            $periodStart->modify('first day of next month');
        }

    }

    $month_where = '';

    if(!empty($months)){
        $where .= ' AND ';
        foreach ($months as $row) {
            $row = explode('-',$row);
            $month = $row[0];
            $year = $row[1];
            if(empty($month_where)){
              $month_where .= ' (cr.month = '.$month.' AND cr.year = '.$year.')';
            }
            else{
              $month_where .= ' OR (cr.month = '.$month.' AND cr.year = '.$year.')';  
            }
        }
    }

    if(!empty($month_where)){
       $where = $where.'('.$month_where.')'; 
    }

    // if(!empty($time)){
    //    if($time=='month_3'){
    //     $where .= ' AND cr.month BETWEEN '.date("m",strtotime("-3 Months")).' AND '.date('m').' AND cr.year = '.date('Y'); 
    //    }
    //    elseif($time=='month_6') {
    //      $where .= ' AND cr.month BETWEEN '.date("m",strtotime("-6 Months")).' AND '.date('m').' AND cr.year = '.date('Y'); 
    //    }
    //    elseif($time=='c_year') {
    //      $where .= ' AND cr.year = '.date('Y'); 
    //    }
    //    elseif ($time=='last_two') {
    //     $where .= ' AND cr.year BETWEEN '.date("Y",strtotime("-1 year")).' AND '.date('Y');
    //    }
    // }
    
   

    $data = $this->cm->getAllCondemnedStockReportData($where,'R','','','Order By cr.year ASC,cr.month ASC');
    
    $groupedData = [];
    // if($group_by=='month'){
        $dataArr = array();
        $s = array();
        $m = array();

        if(!empty($data)){
            foreach ($data as $row) {
             $monthNum  = $row->month;
             $dateObj   = DateTime::createFromFormat('!m', $monthNum);
             $monthName = $dateObj->format('F');
             $m[] = $k = $monthName.' '.$row->year;
             $s[] = $id = $row->ship_name;
             $dataArr[$k][$id] = $row->total_amount; 
            }
        }
        $m = $months = array_values(array_unique($m));
        $s = array_values(array_unique($s)); 
   // }
   // elseif($group_by=='quarter'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $quarter = ceil($month / 3); 
   //      $quarterKey = 'Q'.$quarter.'-'.$year;
   //      if (!isset($groupedData[$row->ship_name][$quarterKey])) {
   //          $groupedData[$row->ship_name][$quarterKey] = [];
   //      }
   //      $groupedData[$row->ship_name][$quarterKey][] = $row->total_amount;
   //   }
   // }
   // elseif($group_by=='half_year'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $halfYear = ($month <= 6) ? 'H1' : 'H2';
   //      $halfYearKey = $halfYear . '-' . $year; // Half-year key
   //      if (!isset($groupedData[$row->ship_name][$halfYearKey])) {
   //          $groupedData[$row->ship_name][$halfYearKey] = [];
   //      }
   //      $groupedData[$row->ship_name][$halfYearKey][] = $row->total_amount;  
   //   }
   // }
   // elseif($group_by=='year'){
   //  foreach ($data as $row) {
   //      $groupedData[$row->ship_name][$row->year][] = $row->total_amount;
   //   }
   // }

    $seriesData = [];
    // if($group_by=='month'){
      for ($i=0; $i <count($m);$i++) { 
          $mk = $m[$i]; 
           for ($j=0; $j<count($s);$j++) {
             $sk = $s[$j];
             $averageRate = isset($dataArr[$mk][$sk]) ? $dataArr[$mk][$sk] : 0;;
             $seriesData[$sk][] = floatval($averageRate);; 
            }
         }
    // }
    // else{
    //     $shipNames = array_keys($groupedData);
    //     $month_wise_data = [];
    //      foreach ($shipNames as $shipName) {
    //         foreach ($groupedData[$shipName] as $month => $rates) {
    //             $averageRate = array_sum($rates) / count($rates);
    //             $seriesData[$shipName][] = $averageRate;
    //         }
    //       }
    //     $months = array_keys(reset($groupedData));
    // }
   echo json_encode(array('seriesData'=>$seriesData,'months'=>$months));
  }

  function getmonthByYear() {
    checkUserSession();
    $returnArr = '';
    $year = trim($this->input->post('year'));
    $ship_id = trim($this->input->post('ship_id'));
    if(!empty($year) && !empty($ship_id)){
     $month = $this->cm->getAllMeatReport(' AND m.ship_id ='.$ship_id.' AND m.year='.$year,'R','','',' ORDER BY m.month ASC');
    }
    $months = array();
     if(!empty($month)){
       foreach ($month as $row) {
         $months[] = $row->month;
       }
     }
     $months = array_unique($months);
     $returnArr .= '<option value="">Select Month</option>';
     if(!empty($months)){
       for ($i=0; $i < count($months); $i++) { 
            $monthNum  = $months[$i];
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $returnArr .= '<option value="'.$months[$i].'">'.$monthName.'</option>';
        } 
     }
    echo json_encode(array('data'=>$returnArr));      
 }


 function getMeatReportGraph(){
    checkUserSession();
    extract($this->input->post());

    // if(!empty($time)){
    //    if($time=='month_3'){
    //     $where .= ' AND ms.month BETWEEN '.date("m",strtotime("-3 Months")).' AND '.date('m').' AND ms.year = '.date('Y'); 
    //    }
    //    elseif($time=='month_6') {
    //      $where .= ' AND ms.month BETWEEN '.date("m",strtotime("-6 Months")).' AND '.date('m').' AND ms.year = '.date('Y'); 
    //    }
    //    elseif($time=='c_year') {
    //      $where .= ' AND ms.year = '.date('Y'); 
    //    }
    //    elseif ($time=='last_two') {
    //     $where .= ' AND ms.year BETWEEN '.date("Y",strtotime("-1 year")).' AND '.date('Y');
    //    }
    // }
    
    if(!empty($shipping_company_id)){
      $where .= ' AND s.shipping_company_id = '.$shipping_company_id;    
    }

    if(!empty($ship_id)){
      $where .= ' AND ms.ship_id = '.$ship_id;  
    }


    if($date != ''){
      $date_range = explode(' - ', $date);
      $strt_dt = $date_range[0];
      $end_dt = $date_range[1];              

        $startDate = DateTime::createFromFormat('m/d/Y', $strt_dt);
        $endDate   = DateTime::createFromFormat('m/d/Y', $end_dt);
        $months = [];
        $periodStart = clone $startDate;
        while ($periodStart <= $endDate) {
            $months[] = $periodStart->format('m-Y');
            $periodStart->modify('first day of next month');
        }

    }

    $month_where = '';

    if(!empty($months)){
        $where .= ' AND ';
        foreach ($months as $row) {
            $row = explode('-',$row);
            $month = $row[0];
            $year = $row[1];
            if(empty($month_where)){
              $month_where .= ' (ms.month = '.$month.' AND ms.year = '.$year.')';
            }
            else{
              $month_where .= ' OR (ms.month = '.$month.' AND ms.year = '.$year.')';  
            }
        }
    }

     if(!empty($month_where)){
       $where = $where.'('.$month_where.')'; 
    }


    $data = $this->cm->getAllMeatReport($where,'R','','','Order By ms.year ASC,ms.month ASC');

    // echo $this->db->last_query();die;
    
    $groupedData = [];
    // if($group_by=='month'){
        $dataArr = array();
        $s = array();
        $m = array();

        if(!empty($data)){
            foreach ($data as $row) {
             $monthNum  = $row->month;
             $dateObj   = DateTime::createFromFormat('!m', $monthNum);
             $monthName = $dateObj->format('F');
             $m[] = $k = $monthName.' '.$row->year;
             $s[] = $id = $row->ship_name;
             $qty = ($row->closing_meat_qty) ? ($row->opening_meat_qty+$row->received_meat_qty) - $row->closing_meat_qty : 0;
             $dataArr[$k][$id] = $qty; 
            }
        }
        $m = $months = array_values(array_unique($m));
        $s = array_values(array_unique($s)); 
   // }
   // elseif($group_by=='quarter'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $quarter = ceil($month / 3); 
   //      $quarterKey = 'Q'.$quarter.'-'.$year;
   //      if (!isset($groupedData[$row->ship_name][$quarterKey])) {
   //          $groupedData[$row->ship_name][$quarterKey] = [];
   //      }
   //     $qty = ($row->closing_meat_qty) ? ($row->opening_meat_qty+$row->received_meat_qty) - $row->closing_meat_qty : 0;
   //      $groupedData[$row->ship_name][$quarterKey][] = $qty;
   //   }
   // }
   // elseif($group_by=='half_year'){
   //   foreach ($data as $row) {
   //      $month = $row->month;
   //      $year = $row->year;
   //      $halfYear = ($month <= 6) ? 'H1' : 'H2';
   //      $halfYearKey = $halfYear . '-' . $year; // Half-year key
   //      if (!isset($groupedData[$row->ship_name][$halfYearKey])) {
   //          $groupedData[$row->ship_name][$halfYearKey] = [];
   //      }
   //      $qty = ($row->closing_meat_qty) ? ($row->opening_meat_qty+$row->received_meat_qty) - $row->closing_meat_qty : 0;
   //      $groupedData[$row->ship_name][$halfYearKey][] = $qty;  
   //   }
   // }
   // elseif($group_by=='year'){
   //  foreach ($data as $row) {
   //      $qty = ($row->closing_meat_qty) ? ($row->opening_meat_qty+$row->received_meat_qty) - $row->closing_meat_qty : 0;
   //      $groupedData[$row->ship_name][$row->year][] = $qty;
   //   }
   // }

    $seriesData = [];
    // if($group_by=='month'){
      for ($i=0; $i <count($m);$i++) { 
          $mk = $m[$i]; 
           for ($j=0; $j<count($s);$j++) {
             $sk = $s[$j];
             $averageRate = isset($dataArr[$mk][$sk]) ? $dataArr[$mk][$sk] : 0;;
             $seriesData[$sk][] = floatval($averageRate);; 
            }
         }
    // }
    // else{
    //     $shipNames = array_keys($groupedData);
    //     $month_wise_data = [];
    //      foreach ($shipNames as $shipName) {
    //         foreach ($groupedData[$shipName] as $month => $rates) {
    //             $averageRate = array_sum($rates) / count($rates);
    //             $seriesData[$shipName][] = $averageRate;
    //         }
    //       }
    //     $months = array_keys(reset($groupedData));
    // }
   echo json_encode(array('seriesData'=>$seriesData,'months'=>$months)); 
 }

}
?>