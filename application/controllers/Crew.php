<?php
class Crew extends CI_Controller
{
	function __construct(){
		parent::__construct();

		 $this->load->library('querybundel');
      	 $this->qb = $this->querybundel;

      	 $this->load->model('Company_manager');
     	 $this->cm = $this->Company_manager;

     	 $this->load->model('User_manager');
      	$this->um = $this->User_manager;
	}

	function index($ship_id){
	   checkUserSession();
	   $user_session_data = getSessionData(); 
	   $ship_id = base64_decode($ship_id);
	   $shipDetails = $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R'); 
	   $vars['user_session_data'] = $user_session_data;   
	   $vars['ship_id'] = $ship_id;
	   $vars['heading'] = 'Crew Member Entries Of - '.$shipDetails[0]->ship_name;
	   $vars['active'] = "CML";
	   $vars['content_view'] = 'crew_entries_list';
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
			$where .= " AND (u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%' )";   
		}


		 if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Date and Time')
            {
                $order_by = 'ORDER BY date(cme.entry_date) '.$sort_type;
            }
            elseif($sort_column == 'Imported By')
            {
                $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
            
        }
        else{
            $order_by = 'ORDER BY cme.entry_date DESC';
        }

        $countdata = $this->cm->getallCrewEntrieslist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $crew_entry = $this->cm->getallCrewEntrieslist($where,'R',$offset,$perPage,$order_by);

		if($crew_entry){
			$total_entries  = 'Showing '.($offset+1).' to '.($offset+count($crew_entry)).' of '.$countdata.' entries';
			$edit_ship = checkLabelByTask('edit_ship'); 
			foreach ($crew_entry as $row){
				$crew_member_list = "<a href=".base_url()."crew/CrewMembersList/".base64_encode($row->crew_member_entries_id)." target='_blank'>View List</a>";

				 $returnArr .= "<tr> 
                             <td width='30%'>".ConvertDate($row->entry_date,'','M Y')."</td>  
                             <td width='30%'>".ucfirst($row->imported_by)."</td> 
                             <td width='30%'>";
            	
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

		}
		else{
          $pagination = '';
            $returnArr = '<tr><td colspan="4" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }

     	echo json_encode(array('dataArr'=>$returnArr,'pagination'=>$pagination,'total_entries'=>$total_entries));    

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
		// $type = $this->input->post('id');
		$validation = true;
		if($actionType=='save'){        
			$validation = false;
			$this->form_validation->set_rules('entry_date','Date','trim|required');
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

					}
					else{
						$date_of_arrival_or_departure_new = '-';
					}


				$session_arr['arrival_or_departure'] = (!empty($objWriter[0]['F'])?$objWriter[0]['F']:'arrival');
				
				$session_arr['ship_id'] = $ship_id;
				$session_arr['ship_name'] = $ship_name;
				$session_arr['ship_imo'] = $ship_imo;
				$session_arr['entry_date'] = convertDate($this->input->post('entry_date'),'','Y-m-d');
				$session_arr['call_sign'] = $objWriter[1]['F'];
				$session_arr['voyage_number'] = $objWriter[1]['H'];
				$session_arr['port_of_arrival_or_departure'] = $objWriter[2]['B'];
				$session_arr['date_of_arrival_or_departure'] = $date_of_arrival_or_departure_new;
				$session_arr['flag_state_of_ship'] = $objWriter[2]['F'];
				$session_arr['last_port_of_call'] = $objWriter[2]['H'];
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


   function preview_crew_members(){
    checkUserSession();
    $user_session_data = getSessionData();
    $import_data = getImportData('crew_data');
    $ship_id = $import_data['ship_id'];
    $ship_details = (array) $this->cm->getAllShips(' and s.ship_id = '.$ship_id,'R');
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

             $entry_date = $import_data['entry_date'];

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
                'e_sign'=>$e_sign,
                'entry_date'=> $entry_date
                );

               //print_r($crew_entry_arr);die;
                $isEntryExist = checkDuplicateCrewEntry($ship_id,$entry_date);

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
          
          // $user_exist = checkUserExistByPassportId($unique_id);
          //   if(empty($user_exist)){
          //     if($role == 'Master' || $role == 'Chief Cook'){
          //       $role_code = (strtolower($role)=='master')?'captain':'cook';
          //       $userArr = array();
          //       $userArr['first_name'] = explode(" ",$arrData[$i]['given_name'])[0];
          //       $userArr['last_name'] = explode(" ",$arrData[$i]['given_name'])[1];
          //       $userArr['created_date'] = date('Y-m-d');
          //       $userArr['img_url'] = 'profile.png';
          //       $userArr['is_deleted'] = '0';
          //       $userArr['shipping_company_id'] = $shipping_company_id;
          //       $userArr['passport_id'] = $unique_id;
          //       $this->db->insert('user',$userArr);
          //       $userId = $this->db->insert_id();
          //       $roleData = $this->um->getuserRolebyCode($role_code);
          //       $roleId = $roleData->role_id;
          //       $userRoleArr = array('user_id'=>$userId,'role_id'=>$roleId);
          //       $this->db->insert('user_role',$userRoleArr);
          //     }
            // }else{
            //   $userId = $user_exist->user_id;
            // }

            $crew_exist = checkCrewMemberExistByPassportId($unique_id,$ship_id,$entry_date);
             if(empty($crew_exist)){
                $this->db->insert('ship_crew_members',$tmpArr2);
             }else{
                $this->um->updateCrew($tmpArr2,$crew_exist->crew_members_id);
             }
          }  
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

         for ($i=1; $i <=count($import_data['crew']); $i++) {
              $unique_id = $import_data['crew'][$i]['number_of_identity'];
              $role = $import_data['crew'][$i]['rank']; 

              // if($role == 'Master' || $role == 'Chief Cook'){
              //   if(!empty($unique_id)){
              //     $user_exist = checkUserExistByPassportId($unique_id);
              //   }
              // }
              // else{
              //   if(!empty($unique_id)){
              //     $user_exist = checkCrewMemberExistByPassportId($unique_id,$ship_id);
              //   }                
              // }
             

              // $already_exist = (empty($user_exist)) ? '' : '1';

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
	   $vars['crew_member_entries_id'] = $crew_member_entries_id; 
	   $vars['heading'] = 'Crew Members Of - '.$shipDetails[0]->ship_name;
	   $vars['content_view'] = 'crew_members_list'; 
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

      	 if(!empty($crew_member_entries_id)){
           $where .= ' AND (scm.crew_member_entries_id='.$crew_member_entries_id.')';
        }

        if(!empty($keyword)){
         $where .= " AND (scm.family_name like '%".trim($keyword)."%' or scm.given_name like '%".trim($keyword)."%' or scm.rank like '%".trim($keyword)."%' or scm.identity_number like '%".trim($keyword)."%' )";   
        }
        
        $countdata = $this->cm->getallCrewlist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $crew_member_list = $this->cm->getallCrewlist($where,'R',$offset,$perPage,$order_by);
        $import_food_habits = checkLabelByTask('import_food_habits');
        //echo $this->db->last_query();die;
        if($crew_member_list){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($crew_member_list)).' of '.$countdata.' entries'; 
         foreach ($crew_member_list as $row){
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


   function foodHabitList($crew_member_entries_id){
	   checkUserSession();
	   $user_session_data = getSessionData(); 
	   $crew_member_entries_id = base64_decode($crew_member_entries_id);
	   $vars['user_session_data'] = $user_session_data;   
	   $vars['crew_member_entries_id'] = $crew_member_entries_id;
	   $vars['heading'] = 'Food Habits ';
	   $vars['content_view'] = 'food_habit_list';
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

         if(!empty($crew_member_entries_id)){
           $where .= ' AND scm.crew_member_entries_id='.$crew_member_entries_id;
         }
   

        $countdata = $this->cm->getallFoodHabitlist($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $habits = $this->cm->getallFoodHabitlist($where,'R',$offset,$perPage,$order_by);
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
              
              $meat_habit_index = array_search('Yes',$meatHabitArr);
              
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
              $pork_habit_index = array_search('Yes',$porkHabitArr);
              
              
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
              $beef_habit_index = array_search('Yes',$beefHabitArr);
              
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
              $fish_habit_index = array_search('Yes',$fishHabitArr);
              
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
              $mutton_habit_index = array_search('Yes',$muttonHabitArr);
              
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
              $chicken_habit_index = array_search('Yes',$chickenHabitArr);
              
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
              $egg_habit_index = array_search('Yes',$eggHabitArr);
              
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
              $cereals_habit_index = array_search('Yes',$cerealsHabitArr);
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
              $dairy_habit_index = array_search('Yes',$dairyHabitArr);
              
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
              $veg_habit_index = array_search('Yes',$vegHabitArr);
              
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
              $fruits_habit_index = array_search('Yes',$fruitsHabitArr);
              
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
              $sweets_habit_index = array_search('Yes',$sweetsHabitArr);
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


}

?>