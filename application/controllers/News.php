<?php
class News extends CI_Controller
{
	function __construct(){
      parent::__construct();

      $this->load->library('querybundel');
      $this->qb = $this->querybundel;

      $this->load->model('news_manager');
      $this->nm = $this->news_manager;

       $this->load->model('Company_manager');
      $this->cm = $this->Company_manager;

      $this->load->model('user_manager');
      $this->um = $this->user_manager;
    
    }

  function index(){
    checkUserSession();
    $user_session_data = getSessionData();    
    $vars['user_session_data'] = $user_session_data;  
    $vars['active'] = 'NEWS';
    $vars['heading'] = 'New Bulletins';
    $vars['content_view'] = 'news_list';
    $this->load->view('layout',$vars);   
  }

  function getAllNewsList(){
   checkUserSession();
   	$user_session_data = getSessionData();
   	$where = $order_by = $returnArr = '';
   	extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;
    
      if(!empty($status)){
           if($status == 'A'){
            	 $where .= " AND ( n.status = 0)";
            }
            elseif($status == 'D'){
                $where .= " AND ( n.status = 1)";
            }
       }

       if($publish_on){
         $where .= ' AND (date(n.publish_on) = "'.convertDate($publish_on,'','Y-m-d').'") '; 
        } 

        if(!empty($keyword)){
          $where .= " AND n.title like '%".trim($keyword)."%' ";   
        } 
        
        if((!empty($sort_column)) && (!empty($sort_type)))
        {
            if($sort_column == 'Title')
            {
                $order_by = 'ORDER BY n.title '.$sort_type;
            }
            elseif($sort_column == 'Publish On')
            {
                $order_by = 'ORDER BY n.publish_on '.$sort_type;
            }
            elseif($sort_column == 'Added On')
            {
                $order_by = 'ORDER BY n.added_on '.$sort_type;
            }
            elseif($sort_column == 'Added By')
            {
                $order_by = 'ORDER BY u.user_name '.$sort_type;
            }
        }
        else{
            $order_by = 'ORDER BY n.added_on DESC';
        }

        $countdata = $this->nm->getAllnewsList($where,'C');
        $offset = ($cur_page * $perPage) - $perPage;
        $pages = new Paginator($countdata,$perPage,$cur_page);
        $news = $this->nm->getAllnewsList($where,'R',$perPage,$offset,$order_by);

        // echo $this->db->last_query();die;
        if($news){
          	$total_entries  = 'Showing '.($offset+1).' to '.($offset+count($news)).' of '.$countdata.' entries';
           $edit_news = checkLabelByTask('edit_news'); 
         foreach ($news as $row){
           if($edit_news){ 
                 if($row->status == 1){
                  $status = '<a onclick="updateStatusBox('.$row->newsletter_id.','.$row->status.',\''.$row->title.'\',\'news/changestatus\')" href="javascript:void(0)">Activate</a>';   
                 }else{
                  $status = '<a onclick="updateStatusBox('.$row->newsletter_id.','.$row->status.',\''.$row->title.'\',\'news/changestatus\')" href="javascript:void(0)">Deactivate</a>';      
                 }
                  
            	  $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit News\',\'news/add_edit_news\','.$row->newsletter_id.',\'\',\'50%\');" >Edit</a>';
            }
    
          $returnArr .= "<tr id='row-".$row->newsletter_id."'>
                   <td width='30%'>".ucfirst($row->title)."</td><td width='20%'><a href='".base_url('uploads/sheets/'.$row->attechment)."' download='".$row->attechment."''>".$row->attechment."</a></td><td width='20%'>".ConvertDate($row->publish_on,'','d-m-Y')."</td><td width='10%'>".ConvertDate($row->added_on,'','d-m-Y | H:i A')."</td><td width='10%'>".ucfirst($row->user_name)."</td><td width='8%'>".(($row->status==0) ? '<span style="color:green">Activate</span>' : '<span style="color:red">Deactivate</span>')."</td>";  
             $returnArr .= '<td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
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

  function add_edit_news(){
  	checkUserSession();
    $user_session_data = getSessionData();
    $returnArr['status'] = '100';
    $data = array();
    $action = $this->input->post('actionType');
    $id = $this->input->post('id');
    if(!empty($id)){
        $data = $this->nm->getNewsLetterById(' AND n.newsletter_id ='.$id);
    }
    $actionType = $this->input->post('actionType');
    if($actionType=='save'){
        $this->form_validation->set_rules('title','Bulletin Title','trim|required');
        $this->form_validation->set_rules('publish_on','Issue Date','trim|required');
        $this->form_validation->set_rules('img','Upload File','callback_validate_file');
        if($this->form_validation->run()){
            $dataArr['content'] = trim($this->input->post('content'));
            $dataArr['title'] = $title = trim($this->input->post('title'));
            $publish_on = $this->input->post('publish_on');
            $dataArr['publish_on'] = $date = ConvertDate($publish_on,'','Y-m-d');
            if(!empty($_FILES['img']['name'])) {
                $file = $_FILES['img']['name'];
                $upload_data =  doc_upload($file, 'sheets');
                $dataArr['attechment'] =  $upload_data['file_name'];  
            }

            if(empty($id)){
                $dataArr['added_by'] = $user_session_data->user_id;
                $newsletter_id =  $this->nm->addNewsLetter($dataArr);
                $this->session->set_flashdata('succMsg','The newsletter has been added successfully');
                $whereEm = ' AND nt.code = "new_bulletins_added"';
                $templateData = $this->um->getNotifyTemplateByCode($whereEm);
                if(!empty($templateData)){
                  $noteArr['date'] = date('Y-m-d H:i:s');
                  $noteArr['is_for_all'] = 1;
                  $noteArr['row_id'] = $newsletter_id;
                  $noteArr['entity'] = 'news';
                  $noteArr['title'] = $templateData->title;
                  $noteArr['long_desc'] = str_replace(array('##title##','##date##'),array($title,$date),$templateData->body); 
                  $this->um->add_notify($noteArr);
                }    
            } 
            else{
                $where = 'newsletter_id = '.$id;   
                $this->nm->editNewsLetter($dataArr,$where);
                $this->session->set_flashdata('succMsg','The newsletter has been updated successfully');
            }
            $returnArr['status'] = 200;            
        }
    }

    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() : get_object_vars($data);
    $data = $this->load->view('add_edit_news',$vars,true);    
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


  function view_news_html(){
    checkUserSession();
    $returnArr['status'] = '100';
    $id = $this->input->post('id');
    if(!empty($id)){
        $data = (array) $this->nm->getNewsLetterById(' AND n.newsletter_id ='.$id);
    }
    $vars['dataArr'] = $data;
    $data = $this->load->view('view_news_html',$vars,true);    
    $returnArr['data'] = $data;
    echo json_encode($returnArr); 
  }

  function changestatus(){
     $id = $this->input->post('id');
     $status = $this->input->post('status');
     $where = 'newsletter_id ='.$id;
     $status = ($status== '1' )? '0' :'1';
     $this->cm->changestatus('newsletter',$status,$where);
     $this->session->set_flashdata('succMsg','The newsletter status changed successfully.'); 
  }

}
?>