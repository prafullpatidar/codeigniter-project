<?php
class Food_menu extends CI_Controller
{
	function __construct(){
		parent::__construct();
		
		$this->load->library('querybundel');
      	$this->qb = $this->querybundel;

      	$this->load->model('Food_manager');
    	$this->fm = $this->Food_manager;
	}

	function index(){
		checkUserSession();
	    $user_session_data = getSessionData();    
	    $vars['user_session_data'] = $user_session_data;  
	    $vars['active'] = 'FM';
	    $vars['heading'] = 'Food Menu';
	    $vars['content_view'] = 'food_menu_list';
	    $this->load->view('layout',$vars);
	}

	function getAllFoodMenuList(){
		checkUserSession();
	   	$user_session_data = getSessionData();
	   	$where = $order_by = $returnArr = '';
	   	extract($this->input->post());
	    $cur_page   = $page ? $page : 1;
	    $perPage    = $perPage ? $perPage : 25;
	    
	      if(!empty($status)){
	           if($status == 'A'){
	            	 $where .= " AND ( fm.status = 1)";
	            }
	            elseif($status == 'D'){
	                $where .= " AND ( fm.status = 0)";
	            }
	       }

	       if($issue_date){
	         $where .= ' AND (date(fm.issue_date) = "'.convertDate($issue_date,'','Y-m-d').'") '; 
	        } 

	        if(!empty($religion)){
                $where .= " AND fm.religion = '".$religion."'";
	       	}

        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Month')
            {
                $order_by = 'ORDER BY fm.month '.$sort_type;
            }
            elseif($sort_column == 'Issue Date')
            {
                $order_by = 'ORDER BY fm.issue_date '.$sort_type;
            }
            elseif($sort_column == 'Added On')
            {
                $order_by = 'ORDER BY fm.added_on '.$sort_type;
            }
            elseif($sort_column == 'Added By')
            {
                $order_by = 'ORDER BY fm.user_name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY fm.added_on DESC';
        }

        $countdata = $this->fm->getAllFoodMenuList($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $food_menu = $this->fm->getAllFoodMenuList($where,'R',$perPage,$offset,$order_by);
        // echo $this->db->last_query();die;
        if($food_menu){

          	$total_entries  = 'Showing '.($offset+1).' to '.($offset+count($food_menu)).' of '.$countdata.' entries';
           $edit_food_menu = checkLabelByTask('edit_food_menu'); 
         foreach ($food_menu as $row){
        	$date_range = explode('-',$row->date_range);

           if($edit_food_menu){ 
                 if($row->status == 0){
                  $status = '<a onclick="updateStatusBox('.$row->food_menu_id.','.$row->status.',\''.ConvertDate($date_range[0],'','d/m/Y')." - ".ConvertDate($date_range[1],'','d/m/Y').'\',\'food_menu/changestatus\')" href="javascript:void(0)">Activate</a>';   
                 }else{
                  $status = '<a onclick="updateStatusBox('.$row->food_menu_id.','.$row->status.',\''.ConvertDate($date_range[0],'','d/m/Y')." - ".ConvertDate($date_range[1],'','d/m/Y').'\',\'food_menu/changestatus\')" href="javascript:void(0)">Deactivate</a>';      
                 }
                  
            	  $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Food Menu\',\'food_menu/add_edit_menu\','.$row->food_menu_id.',\'\',\'50%\');" >Edit</a>';
            }

            $download = '<a href="'.base_url('uploads/sheets/'.$row->file).'" download="'.$row->file.'">Download</a>';
	    

          $returnArr .= "<tr>
                   <td width='30%'>".ConvertDate($date_range[0],'','d/m/Y')." - ".ConvertDate($date_range[1],'','d/m/Y')."</td>
                   <td width='20%'>".(strtoupper(str_replace('_',' / ',$row->religion)))."</td>
                   <td width='20%'>".ConvertDate($row->issue_date,'','d-m-Y')."</td>
                   <td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | H:i A')."</td>
                   <td width='10%'>".ucfirst($row->user_name)."</td>
                   <td width='8%'>".(($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>')."</td>";  
             $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$download.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$status.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }  
         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="7" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));
	}	

	function add_edit_menu(){
		checkUserSession();
	    $user_session_data = getSessionData();
	    $returnArr['status'] = '100';
	    $data = array();
	    $actionType = $this->input->post('actionType');
	    $id = $this->input->post('id');
	    
	    if(!empty($id)){
        	$data = $this->fm->getFoodMenuById(' AND fm.food_menu_id ='.$id);
    	}

	    if($actionType=='save'){
	        // $this->form_validation->set_rules('month','Month','trim|required');
	        $this->form_validation->set_rules('date_range','Date Range','trim|required');
	       
	        $this->form_validation->set_rules('religion','Religion','trim|required');
	        $this->form_validation->set_rules('issue_date','Issue Date','trim|required');
	        $this->form_validation->set_rules('img','Upload File','callback_validate_file');
	        if($this->form_validation->run()){
	            // $dataArr['month'] = trim($this->input->post('month'));
	            $dataArr['date_range'] = trim($this->input->post('date_range'));
           		
           		$dataArr['religion'] = trim($this->input->post('religion'));
           		$issue_date = $this->input->post('issue_date');
           		$dataArr['issue_date'] = $date = ConvertDate($issue_date,'','Y-m-d');
	            if(!empty($_FILES['img']['name'])) {
	                $file = $_FILES['img']['name'];
	                $upload_data =  doc_upload($file, 'sheets');
	                $dataArr['file'] =  $upload_data['file_name'];  
	            }

	             if(empty($id)){
		                $dataArr['added_by'] = $user_session_data->user_id;
		                $food_menu_id =  $this->fm->addFoodMenu($dataArr);
		                $this->session->set_flashdata('succMsg','The food menu has been added successfully');
		            } 
		            else{
		                $where = 'food_menu_id = '.$id;   
		                $this->fm->editFoodMenu($dataArr,$where);
		                $this->session->set_flashdata('succMsg','The food menu has been updated successfully');
		            }
		            $returnArr['status'] = 200;     
	        }
    	}

	    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : get_object_vars($data);
	    $data = $this->load->view('add_edit_menu',$vars,true);    
	    $returnArr['data'] = $data;
	    echo json_encode($returnArr);
	}

	 public function validate_file($file) {
	    if (empty($_FILES['img']['name'])) { // File size check (2MB max)
	        $this->form_validation->set_message('validate_file', 'The upload file is required');
	        return FALSE;
	    }
	    return TRUE;
	}

	 function changestatus(){
	 	 $this->load->model('Company_manager');
     	 $this->cm = $this->Company_manager;
	    
	     $id = $this->input->post('id');
	     $status = $this->input->post('status');
	     $where = 'food_menu_id ='.$id;
	     $status = ($status== '1' )? '0' :'1';
	     $this->cm->changestatus('food_menu',$status,$where);
	     $this->session->set_flashdata('succMsg','The food menu status changed successfully.'); 
 	 }

 	 function getAllFoodMenuByReligion(){
 	 	checkUserSession();
 	 	$religion = trim($this->input->post('religion'));
 	 	$returnArr = '<table class="table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
             <thead>
                 <tr>
                     <th style="color:#296a7e;">Date Range</th>
                     <th style="color:#296a7e;">Issue Date</th>
                     <th style="color:#296a7e;">Added By</th>
                     <th width="2%"></th>
                 </tr>
             </thead>
             <tbody>';
 	 	if($religion){
 	 		$food_menu = $this->fm->getAllFoodMenuList(' AND fm.status = 1 AND fm.religion ="'.$religion.'"','R',100,0,' ORDER BY fm.added_on DESC');

 	 		// echo $this->db->last_query();die;
 	 		if($food_menu){
 	 			foreach($food_menu as $row){
 	 				
 	 				$download = '<a href="'.base_url('uploads/sheets/'.$row->file).'" download="'.$row->file.'">Download</a>';

 	 				$date_range = explode('-',$row->date_range);

		 			$returnArr .= "<tr>
	                   <td>".ConvertDate($date_range[0],'','d/m/Y')." - ".ConvertDate($date_range[1],'','d/m/Y')."</td>
	                   <td>".ConvertDate($row->issue_date,'','d-m-Y')."</td>
	                   <td>".ucfirst($row->user_name)."</td>";  
	            	 $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
	                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
	                                </button>
	                                <ul class="dropdown-menu pull-right">
	                                <li>'.$download.'</li>
	                                </ul>
	                                </div></td> </tr>';
                }
 	 		}
 	 		else{
				$returnArr .= '<tr><td colspan="4" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
 	 		}
 	 	}

 	 	$returnArr .='</tbody></table>';

 	 	echo json_encode(array('dataArr'=>$returnArr));
 	 }


 	 function food_recipe(){
		checkUserSession();
	    $user_session_data = getSessionData();      
	    $vars['active'] = 'FR';
	    $vars['heading'] = 'Food Recipe';
	    $vars['content_view'] = 'food_recipe_list';
	    $this->load->view('layout',$vars);

 	 }

    function getallRecipeList(){
       checkUserSession();
       $user_session_data = getSessionData();
       $where = '';$order_by='';
       $returnArr = '';
       extract($this->input->post());
        $cur_page   = $page ? $page : 1;
        $perPage    = $perPage ? $perPage : 25;
     
        if(!empty($keyword)){
          $where .= " AND (fr.name like '%".trim($keyword)."%' )";   
        } 


        if(!empty($status)){
           if($status == 'A'){
            	 $where .= " AND ( fr.status = 1)";
            }
            elseif($status == 'D'){
                $where .= " AND ( fr.status = 0)";
            }
      	 }
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Name')
            {
                $order_by = 'ORDER BY fr.name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY fr.added_on DESC';
        }

        $countdata = $this->fm->getAllRecipe($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $recipe = $this->fm->getAllRecipe($where,'R',$perPage,$offset,$order_by);
        
        if($recipe){
            $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($recipe)).' of '.$countdata.' entries';
            $edit_recipe = checkLabelByTask('edit_recipe'); 
         
         foreach ($recipe as $row){
           if($edit_recipe){ 
                 if($row->status == 0){
                    $Status = '<a onclick="updateStatusBox('.$row->food_recipe_id.','.$row->status.',\''.$row->name.'\',\'food_menu/changestatusRecipe\')" href="javascript:void(0)">Activate</a>';   
                 }else{
                    $Status = '<a onclick="updateStatusBox('.$row->food_recipe_id.','.$row->status.',\''.$row->name.'\',\'food_menu/changestatusRecipe\')" href="javascript:void(0)">Deactivate</a>';      
                 }
                  
              $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Recipe\',\'food_menu/add_edit_recipe\','.$row->food_recipe_id.',\'\',\'70%\');" >Edit</a>';
            }

            $download = '<a href="'.base_url('uploads/sheets/'.$row->file).'" download="'.$row->file.'">Download</a>';

            $returnArr .= "<tr>";

            $returnArr .= "<td width='10%'>".ucwords($row->name)."</td><td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | H:i A')."</td><td width='10%'>".ucfirst($row->user)."</td><td width='10%'>".(($row->status==1) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>')."</td>"; 

            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$download.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$edit.'</li>
                                <li role="separator" class="divider"></li>
                                <li>'.$Status.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }  

         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="5" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }

     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));          
    }

  function add_edit_recipe(){
		checkUserSession();
	    $user_session_data = getSessionData();
	    $returnArr['status'] = '100';
	    $data = array();
	    $actionType = $this->input->post('actionType');
	    $id = $this->input->post('id');
	    
	    if(!empty($id)){
        	$data = $this->fm->getFoodRecipeById(' AND fr.food_recipe_id ='.$id);
    	}

	    if($actionType=='save'){
	        $this->form_validation->set_rules('name','Recipe Title','trim|required');
	        $this->form_validation->set_rules('img','Upload File','callback_validate_file');
	        if($this->form_validation->run()){
	            $dataArr['name'] = trim($this->input->post('name'));
	            if(!empty($_FILES['img']['name'])) {
	                $file = $_FILES['img']['name'];
	                $upload_data =  doc_upload($file, 'sheets');
	                $dataArr['file'] =  $upload_data['file_name'];  
	            }

	             if(empty($id)){
		                $dataArr['added_by'] = $user_session_data->user_id;
		                $food_recipe_id =  $this->fm->addFoodRecipe($dataArr);
		                $this->session->set_flashdata('succMsg','The food recipe has been added successfully');
		            } 
		            else{
		                $where = 'food_recipe_id = '.$id;   
		                $this->fm->editFoodRecipe($dataArr,$where);
		                $this->session->set_flashdata('succMsg','The food recipe has been updated successfully');
		            }
		            $returnArr['status'] = 200;     
	        }
    	}

    	$vars['food_recipe_id'] = $id;
	    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : get_object_vars($data);
	    $data = $this->load->view('add_edit_recipe',$vars,true);    
	    $returnArr['data'] = $data;
	    echo json_encode($returnArr);  	
    }


    function changestatusRecipe(){
	 	 $this->load->model('Company_manager');
     	 $this->cm = $this->Company_manager;
	     $id = $this->input->post('id');
	     $status = $this->input->post('status');
	     $where = 'food_recipe_id ='.$id;
	     $status = ($status== '1' )? '0' :'1';
	     $this->cm->changestatus('food_recipe',$status,$where);
	     $this->session->set_flashdata('succMsg','The food recipe status changed successfully.'); 
 	 }

 	function food_recipe_model(){
        checkUserSession();
        $returnArr['status'] = 100;
        $data = $this->load->view('food_recipe_model',$vars,true);
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
   }

  function modelRecipeList(){
       checkUserSession();
       $where = '';$order_by='';
       $returnArr = '';
       extract($this->input->post());
        $cur_page   = $page ? $page : 1;
        $perPage    = $perPage ? $perPage : 25;
     
        if(!empty($keyword)){
          $where .= " AND (fr.name like '%".trim($keyword)."%' )";   
        } 
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Name')
            {
                $order_by = 'ORDER BY fr.name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY fr.added_on DESC';
        }

        $countdata = $this->fm->getAllRecipe($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $recipe = $this->fm->getAllRecipe($where,'R',$perPage,$offset,$order_by);
        
        if($recipe){
            $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($recipe)).' of '.$countdata.' entries'; 
         
         foreach ($recipe as $row){
            $download = '<a href="'.base_url('uploads/sheets/'.$row->file).'" download="'.$row->file.'">Download</a>';

            $returnArr .= "<tr>";

            $returnArr .= "<td width='90%'>".ucwords($row->name)."</td>"; 

            $returnArr .= '<td width="10%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$download.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }  

         $pagination = $pages->get_links();
        }else{
          $pagination = '';
            $returnArr = '<tr><td colspan="2" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }

     echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));          
    }
    	 
}
?>