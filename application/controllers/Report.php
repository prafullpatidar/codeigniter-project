<?php
class Report extends CI_Controller{
  function __construct(){
      parent::__construct();
      $this->load->library('querybundel');
      $this->qb = $this->querybundel;
      $this->load->model('user_manager');
      $this->um = $this->user_manager;
      $this->load->model('Company_manager');
      $this->cm = $this->Company_manager;
      $this->load->model('manage_product');
      $this->mp = $this->manage_product;
      $this->load->model('manage_vendor');
      $this->vm = $this->manage_vendor;
  }

  function condemnedStockReport(){
     checkUserSession();
     $vars['user_session_data'] = $sessionData =  getSessionData();
     $vars['title'] = 'Condemned Stock Report';
     $vars['heading'] = 'Condemned Stock Report';
     $vars['active'] = 'CSR';
     if(!empty($sessionData->shipping_company_id)){
        $swh .= ' AND s.shipping_company_id = '.$sessionData->shipping_company_id;
     }
     $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status= 1','R');
     $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
     $vars['vendors'] = $this->um->getallVendor(" AND u.status= 1 ",'C');
     $vars['content_view'] = 'condemned_stock_report';
     $this->load->view('layout',$vars);
   }
   
 function add_condemned_stock_report(){
      checkUserSession();
      $user_session_data = getSessionData(); 
      $returnArr['status'] = 100;
      $actionType = $this->input->post('actionType');
      $condemned_report_id = $this->input->post('id');
        
      if(empty($condemned_report_id)){
        $report_config = getCustomSession('report_config');  
        $ship_id = ($report_config['id']) ? $report_config['id'] : $report_config['ship_id'];
        $month = $report_config['month'];   
        $year = $report_config['year']; 
      }
      else{
        $shipId = $this->input->post('second_id');
        $ship_id = $this->input->post('ship_id');            
      }

    
      
      $mode = $this->input->post('mode');        
      if($actionType=='save'){
         if($this->csr_validation()){
            $total_products = $this->input->post('ttl_prdct'); 
            
            $dataArr['ship_id'] = $ship_id; 
            $dataArr['month'] = $month; 
            $dataArr['year'] = $year; 
            $dataArr['created_by'] = $user_session_data->user_id; 
            $dataArr['created_date'] = date('Y-m-d H:i:s'); 
            $dataArr['captain_user_id'] = $this->input->post('master'); ; 
            $dataArr['cook_user_id'] = $this->input->post('cook'); ; 
            $dataArr['witness_officer_rank'] = $this->input->post('witness_officer_rank'); ; 
            $dataArr['total_amount'] = $this->input->post('total_amount');
            if(empty($condemned_report_id)){
                  // for ($p=0; $p < 500; $p++) { 
                   
                  
                  $condemned_report_ids = $this->cm->add_report_data($dataArr,'condemned_report'); 
                  $batch = array();
                  for($i=0;$i<$total_products;$i++){
                        $dataArr2 = array();
                        $dataArr2['condemned_report_id'] = $condemned_report_ids;
                        $dataArr2['quantity'] = $this->input->post('item_qty')[$i];
                        $dataArr2['product_id'] = $this->input->post('item_name')[$i];
                        $dataArr2['cost'] = $this->input->post('cost')[$i];
                        $dataArr2['reason'] = $this->input->post('item_reason')[$i];
                        $batch[] = $dataArr2; 
                  }

                  $this->db->insert_batch('condemned_report_details',$batch);         
                  $json_data = serialize($batch);
                  $this->cm->edit_report_data(array('json_data'=>$json_data),' condemned_report_id ='.$condemned_report_ids,'condemned_report');
                 // }
                  $returnArr['status'] = 200;  
                  $this->session->set_flashdata('succMsg','Condemned Stock Report generated successfully.');
                  $returnArr['returnMsg'] = 'Condemned Stock Report generated successfully.';
            }else{
                  $this->db->where('condemned_report_id', $condemned_report_id);
                  $this->db->delete('condemned_report_details');
                  $batch = array();
                  for($i=0;$i<$total_products;$i++){
                        $dataArr2 = array();
                        $dataArr2['condemned_report_id'] = $condemned_report_id;
                        $dataArr2['quantity'] = $this->input->post('item_qty')[$i];
                        $dataArr2['product_id'] = $this->input->post('item_name')[$i];
                        $dataArr2['cost'] = $this->input->post('cost')[$i];
                        $dataArr2['reason'] = $this->input->post('item_reason')[$i];
                        $batch[] = $dataArr2; 
                  }

                  $this->db->insert_batch('condemned_report_details',$batch);         
                  $json_data = serialize($batch);
                  $updateArray['created_date'] = date('Y-m-d H:i:s');
                  $updateArray['json_data'] = $json_data;
                  $updateArray['total_amount'] = $this->input->post('total_amount');
                  $this->cm->edit_report_data($updateArray,' condemned_report_id ='.$condemned_report_id,'condemned_report');
                  $returnArr['status'] = 200;  
                  $this->session->set_flashdata('succMsg','Condemned Stock Report updated successfully.');
                  $returnArr['returnMsg'] = 'Condemned Stock Report updated successfully.';
            }
        }
      }
      $vars['productData'] = $this->mp->getAllProduct(' and is_custom_product = 0 and p.status = 1','R','','','order by p.product_name ASC');
      $vars['company'] = $this->cm->getAllshippingCompany(' and c.status = 1','R');
      $vars['condemned_report_id'] = $condemned_report_id;
      
      $shipId = ($shipId == '') ? $ship_id : $shipId;
      
      $shipData = $this->cm->getAllShipsById(' and s.ship_id = '.$shipId);
      $vars['assigned_ship_name'] = $shipData->ship_name;
      $vars['assigned_ship_imo'] = $shipData->imo_no;
      $vars['assigned_ship_id'] = $shipData->ship_id;
     
     if(!empty($condemned_report_id)){
        $where = " and cr.condemned_report_id = ".$condemned_report_id;
        $cr_data = $this->cm->getCondemnedStockReportById($where);
        $vars['mode'] = 'edit';
        $shipCompany = $this->cm->getAllShipsById(' and s.ship_id = '.$cr_data->ship_id);
        $vars['dataArr'] = get_object_vars($cr_data);
        $ttlArr = unserialize($cr_data->json_data);
        $vars['dataArr']['ttl_prdct'] = count($ttlArr);
        $vars['shipping_company_id'] = $vars['dataArr']['shipping_company_id'] = $shipCompany->shipping_company_id;
     }else{
        $vars['mode'] = 'add';
        $vars['dataArr'] = $this->input->post();
     }
     $data = $this->load->view('add_condemned_stock_report',$vars,true);
     $returnArr['data'] = $data;  
     echo json_encode($returnArr);
}
  
  function csr_validation(){
    $user_session_data = getSessionData();
    $mode = $this->input->post('mode');
    if($mode == 'add'){   
     $qty = $this->input->post('item_qty[]');
     foreach($qty as $q){
      $this->form_validation->set_rules('item_qty[]', 'Item Quantity', 'trim|required');
     }
     $item_name = $this->input->post('item_name[]');
     foreach($item_name as $i){
      $this->form_validation->set_rules('item_name[]', 'Item Name', 'trim|required');
     }
     $cost = $this->input->post('cost[]');
     foreach($cost as $c){
      $this->form_validation->set_rules('cost[]', 'Item Cost', 'trim|required');
     }
     $item_reason = $this->input->post('item_reason[]');
     foreach($item_reason as $r){
      $this->form_validation->set_rules('item_reason[]', 'Item Reason', 'trim|required');
     }
     return $this->form_validation->run(); 
   }else{
      return true;
   }
       
  }
 
 function getAllCondemnedStockReportData(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25; 
        if(!empty($shipping_company_id)){
         $where .= " AND s.`shipping_company_id`= '".$shipping_company_id."'";         
        }
        if(!empty($ship_id)){
            $where .= " AND cr.`ship_id`= '".$ship_id."'";         
        }

        if(!empty($ship_ids)){
            $where .= " AND cr.`ship_id`= '".$ship_ids."'";         
        }

     if($user_session_data->code=='captain' || $user_session_data->code=='cook'){
      $where .= ' AND cr.ship_id = '.$user_session_data->ship_id; 
     } 
     elseif($user_session_data->code=='shipping_company'){
       $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id; 
     }

    if($status){
      if($status=='C'){
        $where .= " AND cr.`status`= 1";         
        
      }
      elseif($status=='S'){
        $where .= " AND cr.`status`= 2";         
      }  
    }
    
   
    if(!empty($month)){
        $where .= " AND cr.`month`= '".$month."'";                
    }
    if(!empty($year)){
        $where .= " AND cr.`year`= '".$year."'";               
    }

    if(!empty($created_on)){
        $where .= " AND date(cr.`created_date`) = '".convertDate($created_on,'','Y-m-d')."'";              
    }

    if(!empty($keyword)){
     $where .= " AND ( u.first_name like '%".trim($keyword)."%' or u.last_name like '%".trim($keyword)."%' or concat(u.first_name,' ',u.last_name) like '%".trim($keyword)."%') ";   
    }

    if((!empty($sort_column)) && (!empty($sort_type))){
          if($sort_column == 'Ship Name'){
                $order_by = 'ORDER BY s.ship_name '.$sort_type;
            }
            elseif($sort_column == 'Month'){
                $order_by = 'ORDER BY cr.month '.$sort_type;
            }
            elseif($sort_column == 'Year'){
                $order_by = 'ORDER BY cr.year '.$sort_type;
            }
            elseif($sort_column == 'Total'){
                $order_by = 'ORDER BY cr.total_amount '.$sort_type;
            }
            elseif($sort_column == 'Created On'){
                $order_by = 'ORDER BY cr.created_date '.$sort_type;
            }
            elseif($sort_column == 'Created By'){
                $order_by = 'ORDER BY concat(u.first_name," " ,u.last_name) '.$sort_type;
            }
            elseif($sort_column == 'Status'){
                $order_by = 'ORDER BY cr.status '.$sort_type;
            }
        }else{
            $order_by = 'ORDER BY cr.condemned_report_id DESC';
        }
   
  if($downloadPagination==1){
     $cur_page = 1;
     $perPage = 500;
     $offset = ($cur_page * $perPage) - $perPage;
     $countdata = $this->cm->getAllCondemnedStockReportData($where,'C');
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
        $records_file_name = 'CondemnedStock';  
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
           $arrayHeaderData= array('Vessel Name','Month','Year','Total Amount($)','Created On','Created By','Status');
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
           $k = 7;

           $condemnedStockReportData = $this->cm->getAllCondemnedStockReportData($where,'R',$offset,$perPage,$order_by);

           if($condemnedStockReportData){
             foreach ($condemnedStockReportData as $row) {
               $k++;
                $monthNum  = $row->month;
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
                $arrayData[] = array(ucwords($row->ship_name),$monthName,$row->year,$row->total_amount,ConvertDate($row->created_date,'','d-m-Y | h:i A'),ucfirst($row->user_name),(($row->status==1) ? 'Created' : 'Submitted'));  
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

    $countdata = $this->cm->getAllCondemnedStockReportData($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $condemnedStockReportData = $this->cm->getAllCondemnedStockReportData($where,'R',$offset,$perPage,$order_by);
    $edit_condemned_stock = checkLabelByTask('edit_condemned_stock');
    $submit_condemned_stock = checkLabelByTask('submit_condemned_stock');
    //echo $this->db->last_query(); die;
    if($condemnedStockReportData){
      $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($condemnedStockReportData)).' of '.$countdata.' entries';
      $i=1;
      foreach($condemnedStockReportData as $row){
            $monthNum  = $row->month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $label = ($row->status==1) ? 'Edit' : 'View Details';
            $status = '';
            $edit = '';
            $view = '';
            if($row->status === '1'){
               if($submit_condemned_stock){
               $status = '<a onclick="update_csr_status('.$row->condemned_report_id.')" style="cursor:pointer">Submit Report</a>';
               }
              if($edit_condemned_stock){ 
               $edit = '<a href="javascript:void(0);" onclick="showAjaxModel(\'Edit Condemned Stock Report\',\'report/add_condemned_stock_report\','.$row->condemned_report_id.','.$row->ship_id.',\'98%\',\'full-width-model\');" >Edit</a>';
              }
            }else{                    
                  $view = '<a href="javascript:void(0);" onclick="showAjaxModel(\'View Condenmed Stock Report \',\'report/viewCondemnedStockReportData\','.$row->condemned_report_id.',\'\',\'98%\',\'full-width-model\');" >View</a>'; 
            }

            $start_date = "01-".$row->month."-".$row->year;
            $start_time = strtotime($start_date);
            $end_time = strtotime("+28 day", $start_time);

            $color = (date('Y-m-d')>=date('Y-m-d',$end_time)) ? ' title="Please submit report" class="badge badge-danger"' : ' title="Report not submitted" class="badge badge-info"';
            

            $returnArr .= "<tr>
                              <td width='10%'>".$row->ship_name."</td>
                              <td width='10%'>".$monthName."</td>
                              <td width='10%'>".$row->year."</td>
                              <td width='10%'>".$row->total_amount."</td>
                              <td width='10%'>".ConvertDate($row->created_date,'','d-m-Y | h:i A')."</td>
                              <td width='10%'>".ucfirst($row->user_name)."</td>
                              <td width='10%'>".(($row->status==1) ? '<span '.$color.'>Created</span>' : '<span class="badge badge-success">Submitted</span>')."</td>";
                              
            $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$edit.'</li>
                                <li>'.$status.'</li>
                                <li>'.$view.'</li>
                                </ul>
                                </div></td></td></tr>'; 
            $i++; 
           }
           if($countdata <= 5){
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";
            $returnArr .= "<tr><td width='10%'><br></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='10%'></td><td width='3%'></tr>";

         }
           $pagination = $pages->get_links();
         
          
     }
     else{
           $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
      }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));    
  }

  function update_csr_status(){
    checkUserSession();
    $condemned_report_id = trim($this->input->post('id'));
    $returnArr['status'] = 100;
    $this->load->model('email_manager');
    $this->em = $this->email_manager;
    if(!empty($condemned_report_id)){
      $this->db->update('condemned_report',array('status'=>2),array('condemned_report_id'=>$condemned_report_id));
      $returnArr['status'] = 200;
      $returnArr['returnMsg'] = 'Condemned Report submitted successfully';  
      $emailTemplateData = $this->um->getEmailTemplateByCode(' AND em.template_code = "condemned_stock"');
      if($emailTemplateData){
       require_once(APPPATH.'libraries/tcpdf/Tcpdf.php');
        $data = (array) $this->cm->getCondemnedStockReportById(' and cr.condemned_report_id = '.$condemned_report_id);
        $json_data = unserialize($data['json_data']);
        $productArr = array();
        if(!empty($json_data)){
          for ($i=0; $i < count($json_data); $i++) { 
               $pdata = $this->mp->getAllProductbyid(" And p.product_id = ".$json_data[$i]['product_id']);
               $productArr[] = array('product_name'=>$pdata->product_name,'cost'=>$json_data[$i]['cost'],'reason'=>$json_data[$i]['reason'],'quantity'=>$json_data[$i]['quantity']);
            }  
        }

            $dateObj   = DateTime::createFromFormat('!m', $data['month']);
            $monthName = $dateObj->format('F');

             $pdf_vars['dataArr'] = $data;
             $pdf_vars['productArr'] = $productArr;
             $html = $this->load->view('condemned_stock_pdf',$pdf_vars,TRUE);
             $file = $monthName.'-'.'condemned_stock';
             $pdfFilePath = FCPATH . "uploads/work_order_pdfs/".$file.".pdf";
             $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
             $pdf->AddPage('L');
             $pdf->WriteHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
              ob_clean();ob_end_clean();ob_flush();
             $pdfData = $pdf->Output('', 'S');
             $filePath = $pdfFilePath;
             write_file($filePath,$pdfData); 
             // $attachmentArray = array($filePath);

         $master = ($data['captain_user_id']) ? ucfirst($data['captain_user_id']).'<br>' : '';
         $master .= ($data['cook_user_id']) ?  ucfirst($data['cook_user_id']).'<br>' : '';
         $master .= ($data['witness_officer_rank']) ? ucfirst($data['witness_officer_rank']) : '';


        $subject = str_replace(array('##ship_name##','##imo_no##','##date##'),array(ucwords($data['ship_name']),$data['imo_no'],date('d-m-Y')),$emailTemplateData->email_subject);
            
        $body = str_replace(array('##month##','##amount##','##ship_name##','##master##'),array($monthName,$data['total_amount'],ucwords($data['ship_name']),$master),$emailTemplateData->email_body);

           $email_roles = $this->em->getEmailRoles($emailTemplateData->email_template_id);
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

       $whereEm = ' AND nt.code = "condemned_stock_submit"';
        $templateData = $this->um->getNotifyTemplateByCode($whereEm);
        if(!empty($templateData)){
         $roles = $this->em->getNotifyRoles($templateData->notification_template_id);
         if(!empty($roles)){
           $noteArr1['date'] = date('Y-m-d H:i:s');
           $noteArr1['is_for_master'] = 1;
           $noteArr1['ship_id'] = $data['ship_id']; 
           $noteArr1['title'] = $templateData->title;
           $noteArr1['long_desc'] = str_replace(array('##date##','##ship_name##'),array(ConvertDate($data['year'].'-'.$data['month'],'','M Y'),$data['ship_name']),$templateData->body); 
            $this->um->add_notify($noteArr1); 
           foreach ($roles as $row) {
               $user_data = $this->em->getUserByRoleID($row->role_id);
               if(!empty($user_data)){
                 foreach ($user_data as $val) {
                   $noteArr['date'] = date('Y-m-d H:i:s');
                   $noteArr['user_id'] = $val->user_id;
                   $noteArr['title'] = $templateData->title;
                   $noteArr['long_desc'] = str_replace(array('##date##','##ship_name##'),array(ConvertDate($data['year'].'-'.$data['month'],'','M Y'),$data['ship_name']),$templateData->body); 
                    $this->um->add_notify($noteArr);   
                 }
               } 
            } 
         }

        }

    } 
   echo json_encode($returnArr); 
  }

function viewCondemnedStockReportData(){
    checkUserSession();
    $user_session_data = getSessionData();
    $returnArr = '';
    $condemned_report_id = $this->input->post('id');
    $where = ' AND sst.ship_stock_id='.$stockId;
    $data = (array) $this->cm->getAllCondemnedStockReportData(' and cr.condemned_report_id = '.$condemned_report_id,'R');    
    $dataArr = unserialize($data[0]->json_data);
    $ship_name = $data[0]->ship_name;
    $month = $data[0]->month;
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');
    $year = $data[0]->year;
    $master = $data[0]->captain_user_id;
    $cook = $data[0]->cook_user_id;
    $witness_officer_rank = $data[0]->witness_officer_rank;
   if(!empty($data)){
        $total_price = 0;
        if(!empty($dataArr)){
         $returnArr = '
            <div class="animated fadeIn b-p-15" id="stock_form">
            <div class="row">
            <div class="col-md-12">
            <form class="form-horizontal form-bordered" name="store_vendor_invoice" enctype="multipart/form-data" id="store_vendor_invoice" method="post">
            <div class="no-padding rounded-bottom">
            <div class="form-body no-padding">
            <div class="mb-15 row no-gutter" style="border-bottom: 2px solid #000;padding-bottom:10px"> 
                        <div class="col-sm-2">
                        <img src="'.base_url().'/assets/images/company_logo.png" alt="brand logo" width="60">
                        </div>
                        <div class="col-sm-8 text-center">
                          <h4 class="mb-0 mt-2"><strong>One North Condemned Stock Report</strong></h4>
                        </div>
                        <div class="col-sm-2"></div>
                      </div>
            <div id="abc" class="sip-table overflow-hidden" role="grid">
            <div class="row mb-15">
             <div class="col-sm-6"><b>Vessel Name: </b><span class="text-blue">'.ucfirst($ship_name).'</span></div>
             <div class="col-sm-6"><b>Month: </b> <span class="text-blue">'.$monthName.''.$year.'</span></div>
            </div>
            <div class="row">
             <div class="col-sm-3 text-blue fw-700"></div>
             <div class="col-sm-3 text-blue fw-700"></div>
            </div>
            <p class="mb-25">Please list below any stores condemned during the month, give quantity, item and cost, together with reason.</p>
            <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0">
              <thead>
                  <tr>
                    <th>Quantity</th>
                    <th>Item</th>
                    <th>Cost</th>
                    <th>Reason</th>              
                    </tr>
               </thead><tbody>';

            foreach($dataArr as $rows){ 
                 $product = (array) $this->mp->getAllProductbyid(' AND p.product_id = '.$rows['product_id']);                      
                 $total_price = $total_price+$rows['cost'];
                    $returnArr .= '<tr class="child_row">';
                    $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$rows['quantity'].'</td>';
                    $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($product['product_name']).'</td>';
                    $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($rows['cost'],2).'</td>';
                    $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$rows['reason'].'</td>';
                    
                    $returnArr .= '</tr>';
                           
            }
            $returnArr .= '<tr class="child_row">';  
            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;font-weight: bold" colspan="2">Total Amount ($)</td>';                
            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;font-weight: bold" colspan="2"><span class="text-blue">'.number_format($total_price,2).'</span> </td></tr></table>';
            $returnArr .= '<table width="100%" style="margin: 15px 0;border: none;"><tr><td width="19%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;">Master:</td>';                
            $returnArr .= '<td width="81%" role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;padding:0 5px"><span class="text-blue">'.($master).'</span></td></tr>';
            $returnArr .= '<tr><td width="19%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;">Cook/Steward:</td>';                
            $returnArr .= '<td width="81%" role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;padding:0 5px"><span class="text-blue">'.($cook).'</span> </td></tr>';
            $returnArr .= '<tr><td width="19%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;">Witness Officer (Rank):</td>';                
            $returnArr .= '<td width="81%" role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;padding:0 5px"><span class="text-blue">'.($witness_officer_rank).'</span></td></tr>';
            $returnArr .= '<tr><td width="19%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="text-align: right;">Date:</td>';                
            $returnArr .= '<td width="81%" role="gridcell" tabindex="-1" aria-describedby="f2_key"  style="text-align: left;padding:0 5px"><span class="text-blue">'.ConvertDate($data[0]->created_date,'','d-m-Y').'</span></td></tr>';
            
        }
        $returnArr .= '</tbody></table></div>
        </div>
        </div>
        </form>
        </div>';
    }
    echo json_encode(array('data'=>$returnArr,'status' => 100));
  }


  function report_config($type=''){
     checkUserSession();
     $user_session_data = getSessionData();
     $ship_id = $this->input->post('id');
     $returnArr['status'] = 100; 
     $actionType = $this->input->post('actionType');
     if($actionType=='save'){ 
       if(empty($ship_id)){
         $this->form_validation->set_rules('ship_id','Vessel Name','trim|required');
       } 
       $this->form_validation->set_rules('month','Month','trim|required');
       $this->form_validation->set_rules('year','Year','trim|required|callback_report_check');    
       if($this->form_validation->run()){
         setCustomSession('report_config',$this->input->post());
         $returnArr['status'] = 200;
       }     
     }

     if(!empty($this->input->post('shipping_company_id'))){
       $vars['ships'] = $this->cm->getAllShips(' and s.status = 1 AND s.shipping_company_id = '.$this->input->post('shipping_company_id'),'R');
     }
     else{
      $vars['ships'] = $this->cm->getAllShips(' and s.status = 1','R');  
     }

     $vars['type'] = $type;
     $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');

     $vars['year'] = $this->cm->stock_years($ship_id);
     $vars['dataArr'] = $this->input->post();
     $vars['ship_id'] = $ship_id;
     $vars['shipping_company_id'] = $shipping_company_id;
     $data = $this->load->view('report_config',$vars,true);
     $returnArr['data'] = $data;  
     echo json_encode($returnArr);           
  } 

  function report_check(){
    $ship_id = ($this->input->post('id')) ? $this->input->post('id') : $this->input->post('ship_id');
    $month = $this->input->post('month');
    $year = $this->input->post('year');
    $type = $this->input->post('type');
    if(!empty($ship_id) && !empty($month) && !empty($year)){
    if($type=='extra_meals'){
      $data = $this->cm->getAllExtraMeal(' AND em.ship_id = '.$ship_id.' AND em.month ='.$month.' AND em.year ='.$year,'R');
    }
    elseif($type=='summary_report'){
      $data = $this->cm->getAllvSummaryReport(' AND vs.ship_id = '.$ship_id.' AND vs.month ='.$month.' AND vs.year ='.$year,'R');
    }
    else{
     $data = $this->cm->getAllCondemnedStockReportData(' AND cr.ship_id = '.$ship_id.' AND cr.month ='.$month.' AND cr.year ='.$year,'R');
    }    

    if(empty($data)){
     return true;
    }
    else{
        if($type=='extra_meals'){
          $this->form_validation->set_message('report_check','You have already generated extra meals report for this month.');
        }
        elseif($type=='summary_report'){
          $this->form_validation->set_message('report_check','You have already generated victualing report for this month.');
        }
        else{
          $this->form_validation->set_message('report_check','You have already generated condemned stock report for this month.');
        }
      return false;
    }
   }
  }
  
   function companyInvoices(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'CI';
     $vars['vendors'] = $this->um->getallVendor(' AND u.status = 1','R','','',' ORDER BY u.first_name ASC');
     if(!empty($user_session_data->shipping_company_id)){
        $swh = ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id;
     }
     $vars['company'] = $this->cm->getAllshippingCompany(' And c.status = 1','R');
     $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
     $vars['content_view'] = 'company_invoice_list';
     $this->load->view('layout',$vars);  
  }
  
  function getCompanyInvoiceList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = $order_by = $returnArr = '';
    extract($this->input->post());
    
    if($user_session_data->code=='cook' || $user_session_data->code=='captain'){
      $where .= ' AND s.ship_id = '.$user_session_data->ship_id;
    }
    elseif(!empty($user_session_data->shipping_company_id)){
      $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id;
    }

    if($status){
      $where .= ' AND inv.status ="'.$status.'"';    
    }

    if($shipping_company_id){
       $where .= ' AND s.shipping_company_id ='.$shipping_company_id;  
    } 

    if($ship_id){
       $where .= ' AND inv.ship_id in('.implode(',',$ship_id).')';  
    }

    if($vendor_id){
      $where .= ' AND vq.vendor_id in('.implode(',',$vendor_id).')';  
    }

    if($created_on){   
        $date_range = explode(' - ', $created_on);
       $cnvrtd_end_date = $cnvrtd_end_date = '';
       $strt_dt = $date_range[0];
       $end_dt = $date_range[1];
       $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
       $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
       $where .= " AND date(inv.created_at) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') ";
    }

    if($due_date){
      $where .= ' AND date(inv.due_date) ="'.convertDate($due_date,'','Y-m-d').'"';  
    }

    if($keyword){
      $where .= ' AND wo.po_no like "%'.trim($keyword).'%" or inv.invoice_no like "%'.trim($keyword).'%" or u.first_name like "%'.trim($keyword).'%" or u.last_name like "%'.trim($keyword).'%" or concat(u.first_name," ",u.last_name) like "%'.trim($keyword).'%"';   
    }

      if((!empty($sort_column)) && (!empty($sort_type))){
            if($sort_column == 'Company Name'){
             $order_by = 'ORDER BY sc.name '.$sort_type;
            }
            elseif($sort_column == 'Ship Name'){
             $order_by = 'ORDER BY s.ship_name '.$sort_type;
            }
            elseif($sort_column == 'Invoice No'){
             $order_by = 'ORDER BY inv.invoice_no '.$sort_type;
            }
            elseif($sort_column == 'PO No'){
             $order_by = 'ORDER BY wo.po_no '.$sort_type;
            }
            elseif($sort_column == 'Vendor Name'){
             $order_by = 'ORDER BY u1.first_name '.$sort_type;
            }
            elseif($sort_column == 'Total Amount'){
             $order_by = 'ORDER BY inv.total_price '.$sort_type;
            }
            elseif($sort_column == 'Due date'){
             $order_by = 'ORDER BY inv.due_date '.$sort_type;
            }
            elseif($sort_column == 'Received Amount'){
             $order_by = 'ORDER BY inv.received_amount '.$sort_type;
            }
            elseif($sort_column == 'Pending Amount'){
             $order_by = 'ORDER BY inv.pending_amount '.$sort_type;
            }
            elseif($sort_column == 'Created On'){
             $order_by = 'ORDER BY inv.created_at '.$sort_type;
            }
            elseif($sort_column == 'Created By'){
             $order_by = 'ORDER BY u.first_name '.$sort_type;
            }
            elseif($sort_column == 'Status'){
             $order_by = 'ORDER BY inv.status '.$sort_type;
            }
        }
        else{
         $order_by = 'ORDER BY inv.created_at DESC';
        }
    
    if($download){
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'SaleList.xlsx';
           $arrayHeaderData= array('Company Name','Vessel Name','Invoice No','PO No','Vendor','Total Amount($)','Due Date','Received Amount($)','Pending Amount($)','Created On','Created By','Invoice Status');
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
                    ),'cellArray'=>array('A7:L7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;  
           $invoice_list = $this->cm->getAllInvoiceList($where,'R','','',$order_by);
           $k = 7;
           if(!empty($invoice_list)){
             foreach ($invoice_list as $row) {
                 $k++;
                 $arrayData[] = array(ucwords($row->company_name),ucwords($row->ship_name),$row->invoice_no,$row->po_no,ucfirst($row->vendor_name),$row->total_price,ConvertDate($row->due_date,'','d-m-Y'),$row->received_amount,$row->pending_amount,ConvertDate($row->created_at,'','d-m-Y | h:i A'),ucfirst($row->user_name),$row->status);  
              } 
           }
         $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:L'.$k,'border'=>'THIN')) 
              );   
           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'SaleList');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;    
    }

    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
    $countdata = $this->cm->getAllInvoiceList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $invoice_list = $this->cm->getAllInvoiceList($where,'R',$perPage,$offset,$order_by);
    $edit_invoice = checkLabelByTask('edit_invoice');
    $manage_invoice = checkLabelByTask('manage_invoice');
    $add_transaction = checkLabelByTask('add_transaction');
    if($invoice_list){ 
      $total_entries = 'Showing '.($offset+1).' to '.($offset+count($invoice_list)).' of '.$countdata.' entries';
      $pagination = $pages->get_links();
      foreach ($invoice_list as $row){
         $transaction = '';
         $dn = '<a href="javascript:void(0)" onclick="showAjaxModel(\'View Invoice\',\'shipping/viewInvoice\',\''.$row->company_invoice_id.'\',\'\',\'98%\',\'full-width-model\')">View Invoice</a>';
         $opi = '<a target="_blank" href="'.base_url().'shipping/download_OPI/'.base64_encode($row->company_invoice_id).'" >One Pager Invoice</a>';
        
       if($add_transaction){ 
            if($row->status=='Created' || $row->status=='Resolved' || $row->status=='Partially Paid' || $row->status=='Advance Partially Paid'){
               $transaction = '<a onclick="showAjaxModel(\'Add Transaction\',\'report/add_transaction_history\',\''.$row->company_invoice_id.'\',\'sale\',\'80%\')" href="javascript:void(0)">Add Transaction</a>';
             }
       }
          
           $returnArr .= "<tr>
            <td width='10%'>".ucwords($row->company_name)."</td>
            <td width='10%'>".ucwords($row->ship_name)."</td>
            <td width='10%'>".$row->invoice_no."</td>
            <td width='10%'>".$row->po_no."</td>
            <td width='10%'>".ucfirst($row->vendor_name)."</td>
            <td width='10%'>".$currency.' '.$row->total_price."</td>
            <td width='10%'>".ConvertDate($row->due_date,'','d-m-Y')."</td>
            <td width='10%'>".$row->received_amount."</td>
            <td width='10%'>".$row->pending_amount."</td>
            <td width='10%'>".ConvertDate($row->created_at,'','d-m-Y | h:i A')."</td>
            <td width='10%'>".ucfirst($row->user_name)."</td>
            <td width='10%'>".$row->status."</td>";
           
           $returnArr .= '<td width="3%" class="action-td"><div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu pull-right">
            <li>'.$transaction.'</li>
            <li>'.$dn.'</li>
            <li>'.$opi.'</li>
            </ul>
            </div></td></tr>';
      } 
    }
    else{
    $pagination = '';
    $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
   }
   echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  
  }
  
  function add_transaction_history(){
    checkUserSession();
    $user_session_data = getSessionData();
    $invoice_id = trim($this->input->post('id'));
    $invoice_type = trim($this->input->post('second_id'));
    if(!empty($invoice_id) && ($invoice_type == 'sale')){
       $where = ' AND ci.company_invoice_id ='.$invoice_id;
       $data = (array) $this->mp->getCompanyInvoice($where);
    }
    elseif(!empty($invoice_id) && ($invoice_type == 'purchase')){
       $where = ' AND vi.vendor_invoice_id ='.$invoice_id;
       $data = (array) $this->vm->getVendorInvoiceDataByID($where);
    }

    $returnArr['status'] = 100;
    $actionType = trim($this->input->post('actionType'));
    if($actionType=='save'){
     
      if($this->transaction_validation()){
        $dataArr['invoice_type'] = $invoice_type;
        $dataArr['created_by'] = $user_session_data->user_id;
        $dataArr['created_on'] = date('Y-m-d H:i:s');     
        $dataArr['description'] = trim($this->input->post('description'));
        $dataArr['trans_type'] = $transaction_type = trim($this->input->post('transaction_type'));
        if (!empty($_FILES['img']['name'])) {
         $file = $_FILES['img']['name'];
         $upload_data =  doc_upload($file, 'transaction');
         $dataArr['document'] =  $upload_data['file_name'];  
        }
        if($invoice_type=='sale'){
           $dataArr['amount'] = $amount = ($transaction_type=='full_pay' || $transaction_type=='advance_full') ? $data['total_price'] : trim($this->input->post('t_amount'));
           $dataArr['company_invoice_id'] = $invoice_id;
           $dataArr['is_verified'] = 1; 
           if($transaction_type=='full_pay'){
             $this->db->update('company_invoice',array('received_amount'=>$amount,'pending_amount'=>0,'status'=>'Paid'),array(' company_invoice_id'=>$invoice_id)); 
           }
           elseif($transaction_type=='advance_full'){
             $this->db->update('company_invoice',array('received_amount'=>$amount,'pending_amount'=>0,'status'=>'Paid'),array(' company_invoice_id'=>$invoice_id));          
           }
           elseif($transaction_type=='advance_partially'){
                $total_amount = $data['total_price'];
                $received_amount = $data['received_amount'];
                $final_amount = ($total_amount - $received_amount);
              if($amount>=$final_amount){
                 $this->db->update('company_invoice',array('received_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>'Advance Paid'),array('company_invoice_id'=>$invoice_id));
              }else{
                 $this->db->update('company_invoice',array('received_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>'Advance Partially Paid'),array('company_invoice_id'=>$invoice_id));
              }
           }
           else{
                $total_amount = $data['total_price'];
                $received_amount = $data['received_amount'];
                $final_amount = ($total_amount - $received_amount);
                if($amount>=$final_amount){
                   $this->db->update('company_invoice',array('received_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>'Paid'),array('company_invoice_id'=>$invoice_id));
                 }else{
                   $this->db->update('company_invoice',array('received_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>'Partially Paid'),array('company_invoice_id'=>$invoice_id));
                }
              }   
           }
           else{
              $dataArr['amount'] = $amount = ($transaction_type=='full_pay' || $transaction_type=='advance_full') ? $data['amount'] : trim($this->input->post('t_amount'));
              $dataArr['vendor_invoice_id'] = $invoice_id;
              if($user_session_data->code=='vendor'){ 
                $dataArr['is_verified'] = 1;
                 if($transaction_type=='full_pay'){
                    $this->db->update('vendor_invoice',array('paid_amount'=>$amount,'pending_amount'=>0,'status'=>4),array(' vendor_invoice_id'=>$invoice_id)); 
                 }
                 elseif($transaction_type=='advance_full'){
                  $this->db->update('vendor_invoice',array('paid_amount'=>$amount,'pending_amount'=>0,'status'=>6),array(' vendor_invoice_id'=>$invoice_id));  
                 }
                 elseif($transaction_type=='advance_partially'){
                    $total_amount = $data['amount'];
                    $received_amount = $data['paid_amount'];
                    $final_amount = ($total_amount - $received_amount);
                    if($amount>=$final_amount){
                     $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>6),array('vendor_invoice_id'=>$invoice_id));
                    }else{
                     $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>5),array('vendor_invoice_id'=>$invoice_id));
                    }
                 }
                 else{
                    $total_amount = $data['amount'];
                    $received_amount = $data['paid_amount'];
                    $final_amount = ($total_amount - $received_amount);
                   if($amount>=$final_amount){
                    $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>4),array('vendor_invoice_id'=>$invoice_id));
                   }else{
                    $this->db->update('vendor_invoice',array('paid_amount'=>($received_amount + $amount),'pending_amount'=>$total_amount - ($received_amount + $amount),'status'=>3),array('vendor_invoice_id'=>$invoice_id));
                   } 
                 }
              }
              else{
                 $this->db->update('vendor_invoice',array('status'=>2,'transaction_amount'=>$amount),array(' vendor_invoice_id'=>$invoice_id)); 
              }    
           }
        $this->cm->add_transaction_history($dataArr);
        $returnArr['status'] = 200;       
        $this->session->set_flashdata('succMsg','Transaction Added successfully');
      }  
    }          

    $vars['dataArr'] = ($this->input->post('actionType')=='save') ? $this->input->post() :  $data;
    $vars['invoice_id'] = $invoice_id;
    $vars['invoice_type'] = $invoice_type;
    $data = $this->load->view('add_transaction_history',$vars,true);
    $returnArr['data'] = $data;
    echo json_encode($returnArr);
  }

  function transaction_validation(){
    $invoice_id = trim($this->input->post('id'));
    $invoice_type = trim($this->input->post('second_id'));  
    if(!empty($invoice_id) && ($invoice_type == 'sale')){
       $where = ' AND ci.company_invoice_id ='.$invoice_id;
       $data = (array) $this->mp->getCompanyInvoice($where);
    }
    elseif(!empty($invoice_id) && ($invoice_type == 'purchase')){
      $where = ' AND vi.vendor_invoice_id ='.$invoice_id;
      $data = (array) $this->vm->getVendorInvoiceDataByID($where);
    }
    
    $transaction_type = trim($this->input->post('transaction_type'));
    $this->form_validation->set_rules('transaction_type','Payment Type','trim|required');
     if($transaction_type=='partially_pay' || $transaction_type=='advance_partially'){
       $amount = ($invoice_type=='sale') ? ($data['total_price']-$data['received_amount']) : ($data['amount']-$data['paid_amount']) ;
       $this->form_validation->set_rules('t_amount','Amount','trim|required|less_than_equal_to['.$amount.']');
    }
    return $this->form_validation->run();
  }

  
  function transaction_history(){
     checkUserSession();
     $user_session_data = getSessionData();
     $vars['active'] = 'TH';
     $vars['vendors'] = $this->um->getallVendor(' AND u.status = 1','R','','',' ORDER BY u.first_name ASC');
     $vars['company'] = $this->cm->getAllshippingCompany(' And c.status = 1','R');
     $vars['transaction_list'] = $this->cm->getAllTransactionList(' AND t.is_verified = 1','R');
     $vars['content_view'] = 'invoice_transation_list';
     $this->load->view('layout',$vars);  
  }  
  
  function getInvoiceTransationList(){
    checkUserSession();
    $user_session_data = getSessionData();
    $where = $order_by = $returnArr = '';
    extract($this->input->post());
    
    if($keyword){
      $where .= ' AND (sci.invoice_no like "%'.trim($keyword).'%" OR  vi.invoice_no like "%'.trim($keyword).'%" OR wo.po_no like "%'.trim($keyword).'%" OR wo1.po_no like "%'.trim($keyword).'%" or  t.amount like "%'.trim($keyword).'%" or t.description like "%'.trim($keyword).'%")';  
    }

    if($type){
       $where .=' AND (t.invoice_type = "'.$type.'")';  
    }

    if($user_session_data->code=='cook' || $user_session_data->code=='captain'){
     $where .= ' AND (s.ship_id = '.$user_session_data->ship_id.')';  
    }
    elseif($user_session_data->shipping_company_id){
       $where .= ' AND (sc.shipping_company_id = '.$user_session_data->shipping_company_id.')';  
    }
    
    if($shipping_company_id){
       $where .= ' AND (sc.shipping_company_id = '.$shipping_company_id.' OR sc1.shipping_company_id = '.$shipping_company_id.')';  
    }

    if($vendor_id){
      $where .= ' AND (vq.vendor_id in('.implode(',',$vendor_id).') OR vq1.vendor_id in('.implode(',',$vendor_id).'))';  
    }

    if($ship_id){
      $where .= ' AND (s.ship_id in('.implode(',',$ship_id).') OR s1.ship_id in('.implode(',',$ship_id).'))';  
    }
    
    if($created_date){
         $date_range = explode(' - ', $created_date);
          $cnvrtd_end_date = $cnvrtd_end_date = '';
          $strt_dt = $date_range[0];
          $end_dt = $date_range[1];
          $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
          $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
          $where .= " AND date(t.created_on) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') "; 
    }

    if((!empty($sort_column)) && (!empty($sort_type))){
      if($sort_column == 'Type'){
        $order_by = 'ORDER BY t.invoice_type '.$sort_type;
      }
      elseif($sort_column == 'Transaction ID'){
        $order_by = 'ORDER BY t.invoice_transaction_id '.$sort_type;
      }
      elseif($sort_column == 'Company Name'){
        $order_by = 'ORDER BY company_name '.$sort_type;

      }
      elseif($sort_column == 'Ship Name'){
        $order_by = 'ORDER BY ship_name '.$sort_type;
      }
      elseif($sort_column == 'Vendor Name'){
        $order_by = 'ORDER BY vendor_name '.$sort_type;
      }
      elseif($sort_column == 'Date'){
        $order_by = 'ORDER BY t.created_on '.$sort_type;
      }
      elseif($sort_column == 'Invoice No'){
        $order_by = 'ORDER BY invoice_no '.$sort_type;

      }
      elseif($sort_column == 'PO No'){
        $order_by = 'ORDER BY po_no '.$sort_type;

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
       $arrayHeaderData= array('Type','Transaction ID','Company Name','Vessel Name','Vendor Name','Date','Invoice No.','Po No','Transaction Amount($)','Description');              

       $listColumn[] = array('format'=>'cellFontStyle','styleArray'=>array('font' => array(
                    'color' => array('rgb' => '4F2270'),
                    'size'  => 11,
                    'name'  => 'Calibri',
                    'bold' => true,
                    // 'underline'=> true,
                      ) 
                    ),'cellArray'=>array('A7:J7'));
       $arrayData = array();
       $arrayData[2] = array('','One North Ships');
       $arrayData[7] = $arrayHeaderData;  
       $transaction_list = $this->cm->getAllTransactionList($where,'R','','',$order_by);
       $k = 7;
       if(!empty($transaction_list)){
         foreach ($transaction_list as $row) {
            $k++;
             $arrayData[] = array(ucfirst($row->invoice_type),$row->invoice_transaction_id.ConvertDate($row->created_on,'','dmY'),ucwords($row->company_name),ucwords($row->ship_name),ucwords($row->vendor_name),convertDate($row->created_on,'','d-m-Y'),$row->invoice_no,$row->po_no,$row->amount,$row->description);
         }
       }
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:J'.$k,'border'=>'THIN'))); 
        $arrayBundleData['listColumn'] = $listColumn;
        $arrayBundleData['arrayData'] = $arrayData;
        $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'Transaction');
        readfile(FCPATH.'uploads/sheets/'.$fileName);
        unlink(FCPATH.'uploads/sheets/'.$fileName);
        exit;     
    }

    $cur_page = $page ? $page : 1;
    $perPage = $perPage ? $perPage : 25;
    $countdata = $this->cm->getAllTransactionList($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $transaction_list = $this->cm->getAllTransactionList($where,'R',$perPage,$offset,$order_by);
    if($transaction_list){ 
      $total_entries = 'Showing '.($offset+1).' to '.($offset+count($transaction_list)).' of '.$countdata.' entries';
      $pagination = $pages->get_links();
          foreach ($transaction_list as $row) {
             $returnArr .= "<tr>
            <td width='10%'>".ucfirst($row->invoice_type)."</td>
            <td width='10%'>".$row->invoice_transaction_id.ConvertDate($row->created_on,'','dmY')."</td>
            <td width='10%'>".ucwords($row->company_name)."</td>
            <td width='10%'>".ucwords($row->ship_name)."</td>
            <td width='10%'>".ucwords($row->vendor_name)."</td>
            <td width='10%'>".ConvertDate($row->created_on,'','d-m-Y')."</td>
            <td width='10%'>".$row->invoice_no."</td>
            <td width='10%'>".$row->po_no."</td>
            <td width='10%'>".$row->amount."</td>
            <td width='10%'>".$row->description."</td>
            <td width='10%'><a target='_blank' href='".base_url()."uploads/transaction/".$row->document."'>".$row->document."</a></td>
            <td width='3%'></td>
            </tr>";
             
          }
     }
    else{
    $pagination = '';
    $returnArr = '<tr><td colspan="9" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
   }
   echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));
  }

  function purchase_order_list(){
    checkUserSession();   
    $user_session_data = getSessionData();
    $vars['active'] = 'POL';
    $vars['heading'] = 'Purchase Order List';
    $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status = 1','R');
    if(!empty($user_session_data->shipping_company_id)){
       $swh = ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id; 
    }
    $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
    $vars['content_view'] = 'purchase_order_list';
    $this->load->view('layout',$vars);     
  }

  function getWorkOrderList(){
   checkUserSession();
    $user_session_data = getSessionData();
    $where = '';
    $returnArr = '';
    extract($this->input->post());
    $cur_page   = $page ? $page : 1;
    $perPage    = $perPage ? $perPage : 25;   

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

    if(!empty($vendor_id)){
      $where .= ' AND wo.vendor_id IN('.implode(',',$vendor_id).')';  
    }

    if(!empty($shipping_company_id)){
      $where .= ' AND s.shipping_company_id ='.$shipping_company_id;  
    }

    if($user_session_data->code =='captain' || $user_session_data->code =='cook'){
      $where .= ' AND s.ship_id ='.$user_session_data->ship_id;    

    }
    elseif($user_session_data->code == 'shipping_company'){
      $where .= ' AND s.shipping_company_id ='.$user_session_data->shipping_company_id;    
    }

    
    if(!empty($ship_id)){
      $where .= ' AND s.ship_id ='.$ship_id;  
    }

    if($created_date){
       $date_range = explode(' - ', $created_date);
       $cnvrtd_end_date = $cnvrtd_end_date = '';
       $strt_dt = $date_range[0];
       $end_dt = $date_range[1];
       $cnvrtd_strt_date = convertDate($strt_dt,'','Y-m-d');
       $cnvrtd_end_date = convertDate($end_dt,'','Y-m-d');
       $where .= " AND date(wo.created_on) BETWEEN ('".$cnvrtd_strt_date."') AND ('".$cnvrtd_end_date."') "; 
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
        elseif($sort_column == 'Amount'){
            $order_by = 'ORDER BY wo.total_price '.$sort_type;
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
           $arrayHeaderData= array('Vendor Name','Vessel Name','Po No.','Order ID','RFQ No','Amount($)','Created On','Created By','Stage');
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
           $work_orders = $this->cm->getAllWorkOrders($where,'R','','',$order_by);
           $k = 7;
           $tl_pc = 0;
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
                  $arrayData[] = array(ucfirst($row->vendor_name),ucwords($row->ship_name),$row->po_no,$row->order_id,$row->rfq_no,number_format($row->total_price,2),ConvertDate($row->created_on,'','d-m-Y | h:i A'),ucwords($row->created_by),$stage);
                 $tl_pc += $row->total_price;    
                }
              $arrayData[] = array('','','','','Total Amount($)',number_format($tl_pc,2));
                  $k++;
              
           }

           $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:I'.$k,'border'=>'THIN'))
               );  

           $arrayBundleData['listColumn'] = $listColumn;
           $arrayBundleData['arrayData'] = $arrayData;
           $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'POLIST');
           readfile(FCPATH.'uploads/sheets/'.$fileName);
           unlink(FCPATH.'uploads/sheets/'.$fileName);
           exit;     
       } 

   $countdata = $this->cm->getAllWorkOrders($where,'C');
   $offset = ($cur_page * $perPage) - $perPage;
   $pages = new Paginator($countdata,$perPage,$cur_page,$prefix_label);
   $work_orders = $this->cm->getAllWorkOrders($where,'R',$perPage,$offset,$order_by);
   if($work_orders){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($work_orders)).' of '.$countdata.' entries';
          $total_amount = 0;
         foreach ($work_orders as $row){
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
             $stage = '<span>DN Created</span>';                
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
          $total_amount += $row->total_price;
         $returnArr .= "<tr>
                      <td width='10%'>".ucfirst($row->vendor_name)."</td>
                      <td width='10%'>".$row->ship_name."</td>
                      <td width='10%'>".$row->po_no."</td>
                      <td width='10%'>".$row->order_id."</td>
                      <td width='10%'>".$row->rfq_no."</td>
                      <td width='10%'>".number_format($row->total_price,2)."</td>
                      <td width='10%'>".ConvertDate($row->created_on,'','d-m-Y | h:i A')."</td>
                      <td width='10%'>".ucfirst($row->created_by)."</td>
                      <td width='10%'>".$stage."</td>";
                $returnArr .= '<td width="3%" class="action-td" style="text-align:center;"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                <li>'.$poIn.'</li>
                                <li>'.$podIn.'</li>
                                </ul>
                                </div></td> </tr>'; 
         }

         $returnArr .= '<tr><td colspan="5" align="center" style="font-weight:bold;font-size:10px;">Total Amount($)</td><td style="font-weight:bold;">'.number_format($total_amount,2).'</td><td colspan="4"></td></tr>';

         $pagination = $pages->get_links();
     }
      else
        {
          $pagination = '';
            $returnArr = '<tr><td colspan="8" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>';
        }
     echo json_encode(array('dataArr'=>$returnArr,'total_entries'=>$total_entries,'pagination'=>$pagination));    
  }

  function meat_report(){
     checkUserSession();
    $user_session_data = getSessionData();       
    $vars['active'] = 'MR';
    $vars['heading'] = 'Meat Report';
     if(!empty($shipping_company_id)){
        $swh .= ' AND s.shipping_company_id = '.$shipping_company_id;
     }
     $vars['company'] = $this->cm->getAllshippingCompany(' AND c.status= 1','R');
     $vars['ships'] = $this->cm->getAllShips($swh." AND s.status= 1",'R');
    $vars['content_view'] = 'meat_report';
    $this->load->view('layout',$vars);   
  }


  function getallmeatReport(){
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
       $where .= ' AND ms.ship_id = '.$ship_id; 
    }

    if($user_session_data->code=='captain' || $user_session_data->code=='cook'){
      $where .= ' AND ms.ship_id = '.$user_session_data->ship_id; 
     } 
     elseif($user_session_data->code=='shipping_company'){
       $where .= ' AND s.shipping_company_id = '.$user_session_data->shipping_company_id; 
     }

    if(!empty($month)){
       $where .= ' AND ms.month = '.$month; 
    }

    if(!empty($year)){
       $where .= ' AND ms.year = '.$year; 
    }

     if((!empty($sort_column)) && (!empty($sort_type))){
        if($sort_column == 'Ship Name'){
          $order_by = 'ORDER BY s.ship_name '.$sort_type;
        }
        elseif($sort_column == 'Month'){
          $order_by = 'ORDER BY ms.month '.$sort_type;
        }
        elseif($sort_column == 'Year'){
          $order_by = 'ORDER BY ms.year '.$sort_type;
        }
     }
     else{
          $order_by = 'ORDER BY ms.year ASC,ms.month ASC'; 
     }

    if($download==1){
      $this->load->library('Excelreader');
           $excel  = new Excelreader();
           $fileName = 'MeatReport.xlsx';
           $arrayHeaderData= array('Ship Name','Month','Year','Opening/Received Qty','Closing Qty','Consumed Qty');
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
                    ),'cellArray'=>array('A7:F7')); 
           $arrayData = array();
           $arrayData[2] = array('','One North Ships');
           $arrayData[7] = $arrayHeaderData;
          $k = 7; 
        $meat_report = $this->cm->getAllMeatReport($where,'R','','',$order_by);
        if($meat_report){
            foreach ($meat_report as $row) {
               $k++;
                $monthNum  = $row->month;
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
               $arrayData[] = array(ucwords($row->ship_name),$monthName,$row->year,($row->opening_meat_qty + $row->received_meat_qty),(($row->closing_meat_qty) ? $row->closing_meat_qty : 0),(($row->closing_meat_qty) ? ($row->opening_meat_qty + $row->received_meat_qty) - $row->closing_meat_qty : 0));
             }
       }  
       $listColumn[] = array('format'=>'cellcolor','cellcolor'=>array(array('cell'=>'A7:F'.$k,'border'=>'THIN')) 
              );   
       $arrayBundleData['listColumn'] = $listColumn;
       $arrayBundleData['arrayData'] = $arrayData;
       $objWriter = $excel->downloadExcel($fileName,$arrayBundleData['arrayData'],$arrayBundleData['listColumn'],'MeatReport');
       readfile(FCPATH.'uploads/sheets/'.$fileName);
       unlink(FCPATH.'uploads/sheets/'.$fileName);
       exit;     
   }     

    $countdata = $this->cm->getAllMeatReport($where,'C');
    $offset = ($cur_page * $perPage) - $perPage;
    $pages = new Paginator($countdata,$perPage,$cur_page);
    $meat_report = $this->cm->getAllMeatReport($where,'R',$perPage,$offset,$order_by);
    if($meat_report){
          $total_entries  = 'Showing '.($offset+1).' to '.($offset+count($meat_report)).' of '.$countdata.' entries';
         foreach ($meat_report as $row){
            $monthNum  = $row->month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $returnArr .= "<tr>
            <td width='10%'>".ucwords($row->ship_name)."</td>
            <td width='10%'>".$monthName."</td>
            <td width='10%'>".$row->year."</td>
            <td width='10%'>".($row->opening_meat_qty + $row->received_meat_qty)."</td>
            <td width='10%'>".(($row->closing_meat_qty) ? $row->closing_meat_qty : 0)."</td>
            <td width='10%'>".(($row->closing_meat_qty) ? ($row->opening_meat_qty + $row->received_meat_qty) - $row->closing_meat_qty : 0)."</td>
            </tr>";
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


  function nutrition_report($ship_id=''){
    checkUserSession();
    $user_session_data = getSessionData();    
    $ship_id = base64_decode($ship_id);
    $vars['user_session_data'] = $user_session_data;   
    $vars['active'] = 'NR';
    $vars['heading'] = 'Nutrition Report';
    if(!empty($ship_id)){
        $vars['report_data'] = $this->cm->getAllMeatReport(' AND ms.ship_id ='.$ship_id,'R');
        $vars['member_list'] = $this->cm->getallFoodHabitlist(' AND scm.ship_id='.$ship_id,'R');
    }
    $vars['ship_id'] = $ship_id;
    $vars['ships'] = $this->cm->getAllShips(" AND s.status= 1",'R');
    $vars['content_view'] = 'nutrition_report';
    $this->load->view('layout',$vars);     
  }
  
  function getNutritionReportData(){
    checkUserSession();
    $returnArr = '';
    $month_stock_id = $this->input->post('month_stock_id');

    $crew_food_habits_id = $this->input->post('crew_food_habits_id');
    
    // $row_id = getCustomSession('row_id');


    if(!empty($month_stock_id)){
             $consumption_data = $this->cm->nutrition_report_data($month_stock_id);
             $con_arr = array();
                if(!empty($consumption_data)){
                    foreach ($consumption_data as $key => $row) {
                        $con_arr[$row->category_name] = $row;
                    }
                }
    }

    $month = $consumption_data[0]->month;
    $year = $consumption_data[0]->year;
    $ship_id = $consumption_data[0]->ship_id ;

    $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $weeksInMonth = $total_days / 7;
  
     if($month && $year && $ship_id){  

        $crew_entry = $this->cm->getallCrewEntrieslist(' AND cme.ship_id = '.$ship_id.' AND month(cme.entry_date) ='.$month.' AND year(cme.entry_date) ='.$year,'R');
    }
    
    // $categorizedItems = ["Meat" => ["COLD MEATS AND PIES","TINNED MEATS"],"Pork" => ["PORK AND BACON"],"Beef" => ["BEEF"],"Fish/Sea Food" => ["FROZEN FISH"],"Mutton" => ["LAMB AND MUTTON"],"Chicken" => ["POULTRY"],"Egg" => ["EGGS , SUGAR"],"Cereals" => ["CEREALS","RICE AND FLOUR","PASTA AND NOODLES"],"Dairy Products" => ["MILK, CHEESE, YOGHURT","BUTTER, OILS"],"Vegetables" => ["FRESH VEGETABLES","FROZEN VEGETABLES/FRUITS","DRIED VEGETABLES / FRUIT","TINNED VEGETABLES"],"Fruits" => ["FRESH FRUIT","TINNED FRUIT","FRUIT JUICE",],"Sweets" => ["CUSTARD, ICE CREAMS, CAKE","BISCUITS"],"Others" => ["BREADS, JAMS, SOUPS","SPICES","SAUCES","TEA-COFFEE","PICKLES, OLIVES & VINEGAR","INDIAN SPECIALS"]];

    $categorizedItems = ["Meat" => ["COLD MEATS AND PIES","TINNED MEATS"],"Pork" => ["PORK AND BACON"],"Beef" => ["BEEF"],"Fish/Sea Food" => ["FROZEN FISH"],"Mutton" => ["LAMB AND MUTTON"],"Chicken" => ["POULTRY"],"Egg" => ["EGGS , SUGAR"],"Cereals" => ["CEREALS","RICE AND FLOUR","PASTA AND NOODLES"],"Dairy Products" => ["MILK, CHEESE, YOGHURT","BUTTER, OILS"],"Vegetables" => ["FRESH VEGETABLES","FROZEN VEGETABLES/FRUITS","DRIED VEGETABLES / FRUIT","TINNED VEGETABLES"],"Fruits" => ["FRESH FRUIT","TINNED FRUIT","FRUIT JUICE",],"Sweets" => ["CUSTARD, ICE CREAMS, CAKE","BISCUITS"]];


    // $meat = $pork = $beef = $fish_sea_food = $mutton = $chicken = $egg = $cereals = $dairy_products = $vegetables = $fruits = $sweets = $others = array();

    $meat = $pork = $beef = $fish_sea_food = $mutton = $chicken = $egg = $cereals = $dairy_products = $vegetables = $fruits = $sweets = array();

    
    if($crew_entry){

        $member = $food_habits = $this->cm->getallFoodHabitlist(' AND scm.crew_member_entries_id='.$crew_entry[0]->crew_member_entries_id,'R');

        // echo $this->db->last_query();die;

        if(!empty($food_habits)){
            foreach ($food_habits as $fh) {
              $meatHabitArr = array();
              $meatHabitArr[] = $fh->meat_never;
              $meatHabitArr[] = $fh->meat_daily;
              $meatHabitArr[] = $fh->meat_2_week;
              $meatHabitArr[] = $fh->meat_3_week;
              $meatHabitArr[] = $fh->meat_4_week;
              
              $meat_habit_index = array_search('Yes',$meatHabitArr);
              
                if($meat_habit_index !== '' && $meat_habit_index === 0){
                    $meat_habit = 0;
                }elseif($meat_habit_index === 1){
                    $meat_habit = $total_days;
                }elseif($meat_habit_index == 2){
                    $meat_habit = floor( 2 * $weeksInMonth);
                }elseif($meat_habit_index == 3){
                    $meat_habit = floor( 3 * $weeksInMonth);
                }elseif($meat_habit_index == 4){
                    $meat_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $meat_habit = 0;
                }

               $meat[$fh->crew_food_habits_id] = $meat_habit;

              $porkHabitArr = array();
              $porkHabitArr[] = $fh->pork_never;
              $porkHabitArr[] = $fh->pork_daily;
              $porkHabitArr[] = $fh->pork_2_week;
              $porkHabitArr[] = $fh->pork_3_week;
              $porkHabitArr[] = $fh->pork_4_week;
              $pork_habit_index = array_search('Yes',$porkHabitArr);
              
              
                if($pork_habit_index !== '' && $pork_habit_index === 0){
                    $pork_habit =0;
                }elseif($pork_habit_index == 1){
                    $pork_habit = $total_days;
                }elseif($pork_habit_index == 2){
                    $pork_habit = floor( 2 * $weeksInMonth);
                }elseif($pork_habit_index == 3){
                    $pork_habit = floor( 3 * $weeksInMonth);
                }elseif($pork_habit_index == 4){
                    $pork_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $pork_habit = 0;
                }

               $pork[$fh->crew_food_habits_id] = $pork_habit;
                
              $beefHabitArr = array();
              $beefHabitArr[] = $fh->beef_never;
              $beefHabitArr[] = $fh->beef_daily;
              $beefHabitArr[] = $fh->beef_2_week;
              $beefHabitArr[] = $fh->beef_3_week;
              $beefHabitArr[] = $fh->beef_4_week;
              $beef_habit_index = array_search('Yes',$beefHabitArr);
              
                if($beef_habit_index !== '' && $beef_habit_index === 0){
                    $beef_habit = 0;
                }elseif($beef_habit_index == 1){
                    $beef_habit = $total_days;
                }elseif($beef_habit_index == 2){
                    $beef_habit = floor( 2 * $weeksInMonth);
                }elseif($beef_habit_index == 3){
                    $beef_habit = floor( 3 * $weeksInMonth);
                }elseif($beef_habit_index == 4){
                    $beef_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $beef_habit = 0;
                }
              
               $beef[$fh->crew_food_habits_id] = $beef_habit;


              $fishHabitArr = array();
              $fishHabitArr[] = $fh->fish_sea_food_never;
              $fishHabitArr[] = $fh->fish_sea_food_daily;
              $fishHabitArr[] = $fh->fish_sea_food_2_week;
              $fishHabitArr[] = $fh->fish_sea_food_3_week;
              $fishHabitArr[] = $fh->fish_sea_food_4_week;
              $fish_habit_index = array_search('Yes',$fishHabitArr);
              
                if($fish_habit_index !== '' && $fish_habit_index === 0){
                    $fish_habit = 0;
                }elseif($fish_habit_index == 1){
                    $fish_habit = $total_days;
                }elseif($fish_habit_index == 2){
                    $fish_habit = floor( 2 * $weeksInMonth);
                }elseif($fish_habit_index == 3){
                    $fish_habit = floor( 3 * $weeksInMonth);
                }elseif($fish_habit_index == 4){
                    $fish_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $fish_habit = 0;
                }

            $fish_sea_food[$fh->crew_food_habits_id] = $fish_habit; 


              
              $muttonHabitArr = array();
              $muttonHabitArr[] = $fh->mutton_never;
              $muttonHabitArr[] = $fh->mutton_daily;
              $muttonHabitArr[] = $fh->mutton_2_week;
              $muttonHabitArr[] = $fh->mutton_3_week;
              $muttonHabitArr[] = $fh->mutton_4_week;
              $mutton_habit_index = array_search('Yes',$muttonHabitArr);
              
                if($mutton_habit_index !== '' && $mutton_habit_index === 0){
                    $mutton_habit = 0;
                }elseif($mutton_habit_index == 1){
                    $mutton_habit = $total_days;
                }elseif($mutton_habit_index == 2){
                    $mutton_habit = floor( 2 * $weeksInMonth);
                }elseif($mutton_habit_index == 3){
                    $mutton_habit = floor( 3 * $weeksInMonth);
                }elseif($mutton_habit_index == 4){
                    $mutton_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $mutton_habit = 0;
                }

              $mutton[$fh->crew_food_habits_id] = $mutton_habit;  
              
              $chickenHabitArr = array();
              $chickenHabitArr[] = $fh->chicken_never;
              $chickenHabitArr[] = $fh->chicken_daily;
              $chickenHabitArr[] = $fh->chicken_2_week;
              $chickenHabitArr[] = $fh->chicken_3_week;
              $chickenHabitArr[] = $fh->chicken_4_week;
              $chicken_habit_index = array_search('Yes',$chickenHabitArr);
              
                if($chicken_habit_index !== '' && $chicken_habit_index === 0){
                    $chicken_habit = 0;
                }elseif($chicken_habit_index == 1){
                    $chicken_habit = $total_days;
                }elseif($chicken_habit_index == 2){
                    $chicken_habit = floor( 2 * $weeksInMonth);
                }elseif($chicken_habit_index == 3){
                    $chicken_habit = floor( 3 * $weeksInMonth);
                }elseif($chicken_habit_index == 4){
                    $chicken_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $chicken_habit = 0;
                }

             $chicken[$fh->crew_food_habits_id] = $chicken_habit;

              $eggHabitArr = array();
              $eggHabitArr[] = $fh->egg_never;
              $eggHabitArr[] = $fh->egg_daily;
              $eggHabitArr[] = $fh->egg_2_week;
              $eggHabitArr[] = $fh->egg_3_week;
              $eggHabitArr[] = $fh->egg_4_week;
              $egg_habit_index = array_search('Yes',$eggHabitArr);
              
                if($egg_habit_index !== '' && $egg_habit_index === 0){
                    $egg_habit = 0;
                }elseif($egg_habit_index == 1){
                    $egg_habit = $total_days;
                }elseif($egg_habit_index == 2){
                    $egg_habit = floor( 2 * $weeksInMonth);
                }elseif($egg_habit_index == 3){
                    $egg_habit = floor( 3 * $weeksInMonth);
                }elseif($egg_habit_index == 4){
                    $egg_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $egg_habit = 0;
                }

              $egg[$fh->crew_food_habits_id] = $egg_habit;  
              
              $cerealsHabitArr = array();
              $cerealsHabitArr[] = $fh->cereals_never;
              $cerealsHabitArr[] = $fh->cereals_daily;
              $cerealsHabitArr[] = $fh->cereals_2_week;
              $cerealsHabitArr[] = $fh->cereals_3_week;
              $cerealsHabitArr[] = $fh->cereals_4_week;
              $cereals_habit_index = array_search('Yes',$cerealsHabitArr);
                if($cereals_habit_index !== '' && $cereals_habit_index === 0){
                    $cereals_habit = 0;
                }elseif($cereals_habit_index == 1){
                    $cereals_habit = $total_days;
                }elseif($cereals_habit_index == 2){
                    $cereals_habit = floor( 2 * $weeksInMonth);
                }elseif($cereals_habit_index == 3){
                    $cereals_habit = floor( 3 * $weeksInMonth);
                }elseif($cereals_habit_index == 4){
                    $cereals_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $cereals_habit = 0;
                }

              $cereals[$fh->crew_food_habits_id] = $cereals_habit;   
              
              $dairyHabitArr = array();
              $dairyHabitArr[] = $fh->dairy_products_never;
              $dairyHabitArr[] = $fh->dairy_products_daily;
              $dairyHabitArr[] = $fh->dairy_products_2_week;
              $dairyHabitArr[] = $fh->dairy_products_3_week;
              $dairyHabitArr[] = $fh->dairy_products_4_week;
              $dairy_habit_index = array_search('Yes',$dairyHabitArr);
              
                if($dairy_habit_index !== '' && $dairy_habit_index === 0){
                    $dairy_habit = 0;
                }elseif($dairy_habit_index == 1){
                    $dairy_habit = $total_days;
                }elseif($dairy_habit_index == 2){
                    $dairy_habit = floor( 2 * $weeksInMonth);
                }elseif($dairy_habit_index == 3){
                    $dairy_habit = floor( 3 * $weeksInMonth);
                }elseif($dairy_habit_index == 4){
                    $dairy_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $dairy_habit = 0;
                }

             $dairy_products[$fh->crew_food_habits_id] = $cereals_habit;   
             

              $vegHabitArr = array();
              $vegHabitArr[] = $fh->vegetables_never;
              $vegHabitArr[] = $fh->vegetables_daily;
              $vegHabitArr[] = $fh->vegetables_2_week;
              $vegHabitArr[] = $fh->vegetables_3_week;
              $vegHabitArr[] = $fh->vegetables_4_week;
              $veg_habit_index = array_search('Yes',$vegHabitArr);
              
                if($veg_habit_index !== '' && $veg_habit_index === 0){
                    $veg_habit = 0;
                }elseif($veg_habit_index == 1){
                    $veg_habit = $total_days;
                }elseif($veg_habit_index == 2){
                    $veg_habit = floor( 2 * $weeksInMonth);
                }elseif($veg_habit_index == 3){
                    $veg_habit = floor( 3 * $weeksInMonth);
                }elseif($veg_habit_index == 4){
                    $veg_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $veg_habit = 0;
                }

              $vegetables[$fh->crew_food_habits_id] = $veg_habit;  
              
              $fruitsHabitArr = array();
              $fruitsHabitArr[] = $fh->fruits_never;
              $fruitsHabitArr[] = $fh->fruits_daily;
              $fruitsHabitArr[] = $fh->fruits_2_week;
              $fruitsHabitArr[] = $fh->fruits_3_week;
              $fruitsHabitArr[] = $fh->fruits_4_week;
              $fruits_habit_index = array_search('Yes',$fruitsHabitArr);
              
                if($fruits_habit_index !== '' && $fruits_habit_index === 0){
                    $fruits_habit = 0;
                }elseif($fruits_habit_index == 1){
                    $fruits_habit = $total_days;
                }elseif($fruits_habit_index == 2){
                    $fruits_habit = floor( 2 * $weeksInMonth);
                }elseif($fruits_habit_index == 3){
                    $fruits_habit = floor( 3 * $weeksInMonth);
                }elseif($fruits_habit_index == 4){
                    $fruits_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $fruits_habit = 0;
                }

             $fruits[$fh->crew_food_habits_id] = $fruits_habit;   
              
              $sweetsHabitArr = array();
              $sweetsHabitArr[] = $fh->sweets_never;
              $sweetsHabitArr[] = $fh->sweets_daily;
              $sweetsHabitArr[] = $fh->sweets_2_week;
              $sweetsHabitArr[] = $fh->sweets_3_week;
              $sweetsHabitArr[] = $fh->sweets_4_week;
              $sweets_habit_index = array_search('Yes',$sweetsHabitArr);
                if($sweets_habit_index !== '' && $sweets_habit_index === 0){
                    $sweets_habit = 0;
                }elseif($sweets_habit_index == 1){
                    $sweets_habit = $total_days;
                }elseif($sweets_habit_index == 2){
                    $sweets_habit = floor( 2 * $weeksInMonth);
                }elseif($sweets_habit_index == 3){
                    $sweets_habit = floor( 3 * $weeksInMonth);
                }elseif($sweets_habit_index == 4){
                    $sweets_habit = floor( 4 * $weeksInMonth);
                }
                else{
                    $sweets_habit = 0;
                }


             $sweets[$fh->crew_food_habits_id] = $sweets_habit;   
              
            }
        }

        $returnArr .= '<table class="table table-bordered nested-table">
                    <thead class="t-header">
                                <tr>
                                    <th width="10%">Category</th>
                                    <th width="10%">Days Eating</th>
                                    <th width="10%">Consumption</th>
                                    <th width="10%">Calories</th>
                                    <th width="10%">Protein</th>
                                    <th width="10%">Fat</th>
                                    <th width="10%">Saturated Fat</th>
                                    <th width="10%">Cholesterol</th>
                                    <th width="10%">Sodium</th>
                                    <th width="10%">Potassium</th>
                                    <th width="10%">Carbohydrates</th>
                                    <th width="10%">Iron</th>
                                    <th width="10%">Calcium</th>
                                </tr>
                     </thead>
                      <tbody class="report-'.$month_stock_id.'">';
        
                    $category = [];
                    foreach($categorizedItems as $main_category => $sub_category){
                            $category[] = $main_category;
                            $var = strtolower(str_replace(array(' ','/'),array('_','_'),$main_category));
                            $total_eating_days = array_sum($$var);
                           
                            $total_consumed = $total_calories = $total_protein = $total_fat = $total_saturated_fat = $total_cholesterol = $total_sodium = $total_potassium = $total_carbohydrates = $total_iron = $total_calcium = 0;

                                foreach ($sub_category as $c) {
                                    $total_consumed += $con_arr[$c]->total_consumed;
                                    $total_calories += $con_arr[$c]->total_calories;
                                    $total_protein += $con_arr[$c]->total_protein;
                                    $total_fat += $con_arr[$c]->total_fat;
                                    $total_saturated_fat += $con_arr[$c]->total_saturated_fat;
                                    $total_cholesterol += $con_arr[$c]->total_cholesterol;
                                    $total_sodium += $con_arr[$c]->total_sodium;
                                    $total_potassium += $con_arr[$c]->total_potassium;
                                    $total_carbohydrates += $con_arr[$c]->total_carbohydrates;
                                    $total_iron += $con_arr[$c]->total_iron;
                                    $total_calcium += $con_arr[$c]->total_calcium;                    
                                }   

                            if(!empty($crew_food_habits_id)){   
                                $share_ratio = $$var[$crew_food_habits_id] / $total_eating_days;
                                
                                $total_eating_days = $$var[$crew_food_habits_id];
                                
                                $total_consumed = ($total_consumed > 0) ? $total_consumed * $share_ratio : 0;                                
                                $total_calories = ($total_calories > 0) ? $total_calories * $share_ratio : 0;
                                $total_protein = ($total_protein > 0) ? $total_protein * $share_ratio : 0;
                                $total_fat = ($total_fat > 0) ? $total_fat * $share_ratio : 0;
                                $total_saturated_fat = ($total_saturated_fat > 0) ? $total_saturated_fat * $share_ratio : 0;
                                $total_sodium = ($total_sodium > 0) ? $total_sodium * $share_ratio : 0;
                                $total_potassium = ($total_potassium > 0) ? $total_potassium * $share_ratio : 0;
                                $total_cholesterol = ($total_cholesterol > 0) ? $total_cholesterol * $share_ratio : 0;
                                $total_carbohydrates = ($total_carbohydrates > 0) ? $total_carbohydrates * $share_ratio : 0;
                                $total_calcium = ($total_calcium > 0) ? $total_calcium * $share_ratio : 0;
                                $total_iron = ($total_iron > 0) ? $total_iron * $share_ratio : 0;
                            }

                  $returnArr .= '<tr>
                                 <td width="10%">'.$main_category.'</td>
                                  <td width="10%">'.round($total_eating_days).'</td>
                                  <td width="10%">'.(($total_consumed > 0) ? round($total_consumed,2 ) : 0 ).'</td>
                                  <td width="10%">'.(($total_calories > 0) ? round($total_calories,2 ) : 0 ).'</td>
                                 <td width="10%" >'.(($total_protein > 0) ? round($total_protein,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_fat > 0) ? round($total_fat,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_saturated_fat > 0) ? round($total_saturated_fat,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_cholesterol > 0) ? round($total_cholesterol,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_sodium > 0) ? round($total_sodium,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_potassium > 0) ? round($total_potassium,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_carbohydrates > 0) ? round($total_carbohydrates,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_iron > 0) ? round($total_iron,2 ) : 0 ).'</td>
                                 <td width="10%">'.(($total_calcium > 0) ? round($total_calcium,2 ) : 0 ).'</td>
                             </tr>'; 


                             /* Chart series start */


                                $seriesData['Calories'][] =  (($total_calories > 0) ? round($total_calories,2 ) : 0 );
                                $seriesData['Protein'][] =  (($total_protein > 0) ? round($total_protein,2 ) : 0 );
                                $seriesData['Fat'][] =  (($total_fat > 0) ? round($total_fat,2 ) : 0 );
                                $seriesData['Saturated Fat'][] =  (($total_saturated_fat > 0) ? round($total_saturated_fat,2 ) : 0 );
                                $seriesData['Cholesterol'][] =  (($total_cholesterol > 0) ? round($total_cholesterol,2 ) : 0 );
                                $seriesData['Sodium'][] =  (($total_sodium > 0) ? round($total_sodium,2 ) : 0 );
                                $seriesData['Potassium'][] =  (($total_potassium > 0) ? round($total_potassium,2 ) : 0 );
                                $seriesData['Carbohydrates'][] =  (($total_carbohydrates > 0) ? round($total_carbohydrates,2 ) : 0 );
                                $seriesData['Iron'][] =  (($total_iron > 0) ? round($total_iron,2 ) : 0 );
                                $seriesData['Calcium'][] =  (($total_calcium > 0) ? round($total_calcium,2 ) : 0 );    

                                /* Chart series End */      
                 } 
                $returnArr .= '</tbody></table>'; 
          }

        echo json_encode(array('status'=>200,'data'=>$returnArr,'row_id'=>$row_id,'categories'=>$category,'seriesData'=>$seriesData));
    }
}
?>