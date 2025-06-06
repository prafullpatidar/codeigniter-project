<?php
$startDate = new DateTime('first day of -2 months');
$startDate->setTime(0, 0, 0);
$endDate = new DateTime('last day of this month');
$endDate->setTime(23, 59, 59);
$date_range = $startDate->format('m/d/Y').' - '.$endDate->format('m/d/Y');
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts/funnel.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts/exporting.js"></script>

<script src="<?php echo base_url() ?>assets/js/custom_graph.js"></script>  
</script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker-custom.js"></script>
<link href="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.css" rel="stylesheet">


<style type="text/css">
.loader-container {
  position: relative;
  width: 50px;
  height: 50px;
}

.loader {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  margin: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

	.rounded {
  -webkit-border-radius: 3px !important;
  -moz-border-radius: 3px !important;
  border-radius: 3px !important;
}

.mini-stat {
  padding: 15px;
  margin-bottom: 20px;
}

.mini-stat-icon {
  width: 60px;
  height: 60px;
  display: inline-block;
  line-height: 60px;
  text-align: center;
  font-size: 30px;
  background: none repeat scroll 0% 0% #EEE;
  border-radius: 100%;
  float: left;
  margin-right: 10px;
  color: #FFF;
}

.mini-stat-info {
  font-size: 12px;
  padding-top: 2px;
}
.mini-stat-info span {
  display: block;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 5px;
  margin-top: 7px;
}

/* ================ colors =====================*/
.bg-facebook {
  background-color: #3b5998 !important;
  border: 1px solid #3b5998;
  color: white;
}

.fa-truck {
  color: #3b5998 !important;
}

.fa-newspaper-o{
  color: #3b5998 !important;
}

.fa-lemon{
  color: #3b5998 !important;

}

.fa-quote-left {
  color: #3b5998 !important;
}

.fa-cheese{
  color: #db4a39 !important;
}

.bg-twitter {
  background-color: #296a7e  !important;
  border: 1px solid #296a7e;
  color: white;
}

.fa-ship {
  color: #00a0d1 !important;
}

.dash_task{
  color: #00a0d1 !important;  
}

.bg-googleplus {
  background-color: #db4a39 !important;
  border: 1px solid #db4a39;
  color: white;
}

.fa-id-badge {
  color: #db4a39 !important;
}

.dash_invoice{
  color: #db4a39 !important;
}

.dashc_invoice{
  color: #205081 !important;
}

.bg-bitbucket {
  background-color: #205081 !important;
  border: 1px solid #205081;
  color: white;
}

.fa-user {
  color: #205081 !important;
}


.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(2, 1fr);
  gap: 13px;
}

.grid-item {
    display: flex;
    flex-wrap: wrap;
    border: 2px solid #296a7e;
    font-size: 24px;
    padding: 10px;
    border-radius: 5px;
}


#top-tabs .nav-pills > li > a {
  border-radius: 0;
  margin-right: 10px;
  padding: 12px 20px;
  background-color: #f8f9fa;
  color: #333;
  transition: background-color 0.3s;
}

#top-tabs .nav-pills > li > a:hover {
  background-color: #e7e7e7;
}

#top-tabs .nav-pills > li.active > a {
  background-color: #004B4D;
  color: white;
  font-weight: bold;
}

#top-tabs .tab-content {
  border: 1px solid #ddd;
  border-top: none;
  padding: 20px;
  background-color: #fff;
  border-radius: 0 0 4px 4px;
}

</style>

<?php
$user_session_data = getSessionData();
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg))
{
    ?><div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg))
{
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}

$company_widget = checkLabelByTask('company_widget');
$vessel_widget = checkLabelByTask('vessel_widget');
$vendor_widget = checkLabelByTask('vendor_widget');
$user_widget = checkLabelByTask('user_widget');
$rfq_widget = checkLabelByTask('rfq_widget');
$po_widget = checkLabelByTask('po_widget');
$vendor_invoice_widget = checkLabelByTask('vendor_invoice_widget');
$sales_received_graph = checkLabelByTask('sales_received_graph');
$sales_pending_graph = checkLabelByTask('sales_pending_graph');
$purchase_paid_graph = checkLabelByTask('purchase_paid_graph');
$purchase_due_graph = checkLabelByTask('purchase_due_graph');
$company_invoice_widget = checkLabelByTask('company_invoice_widget');
$purchase_order_list = checkLabelByTask('purchase_order_list');
$victualling_rate_analysis = checkLabelByTask('victualling_rate_analysis');
$condemned_stock_analysis = checkLabelByTask('condemned_stock_analysis');
$meat_consumption = checkLabelByTask('meat_consumption');
$view_newsletter = checkLabelByTask('view_newsletter');
$food_menu_widget = checkLabelByTask('food_menu_widget');
$food_recipe_widget = checkLabelByTask('food_recipe_widget');
$manage_nutrition_report = checkLabelByTask('manage_nutrition_report');

?> 

<script type="text/javascript">
    var shipping_company_id = '<?php echo $shipping_company_id;?>'
    var ship_id = '';
</script>

<div class="header-content">
    <!-- <h2><i class="glyphicon glyphicon-home"></i> <span class="oblc">/</span><?php echo $heading;?></h2> -->
    </div>   
<div class="body-content animated fadeIn"> 
<div class="mt-5 mb-10 pull-right">
<div class="form-group">
  <label for="singleDropdown">Choose a company:</label>
  <select class="form-control" id="singleDropdown" onchange="getReportByCompany(this.value);">
    <option value="">-- Select an Option --</option>
        <?php 
        if($company){
           foreach ($company as $row) {
            if(!empty($user_session_data->shipping_company_id)){
              if($row->shipping_company_id==$user_session_data->shipping_company_id){
                echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';  
              }
             }
             else{
               echo '<option '.(($shipping_company_id == $row->shipping_company_id) ? 'selected' : '').' value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
             }
           }                                       
        }

        ?>
  </select>
</div>
</div> 
    
<div class = "row">
<div class = "col-md-12 short-info">
<div class="">
    <div class="row">
      <?php
         if($company_widget){
          $manage_shipping_company = checkLabelByTask('manage_shipping_company');
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="<?php echo ($manage_shipping_company) ? base_url().'shipping/index?cmi=MzI=' : 'javascript:void(0)';?>">
            	<div class="mini-stat clearfix bg-facebook rounded">
                <span class="mini-stat-icon"><i class="fa fa-truck" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($count_companies,0,'',',');?></span>
                    SHIPPING COMPANY
                </div>
            </div></a>
        </div>
      <?php } 
       if($vessel_widget){
         $manage_ships = checkLabelByTask('manage_ships');   
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="<?php echo ($manage_ships) ? base_url().'shipping/manageShips?cmi=MzI=' : 'javascript:void(0)';?>">
            	<div class="mini-stat clearfix bg-twitter rounded">
                <span class="mini-stat-icon"><i class="fa fa-ship" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($ship_count,0,'',',');?></span>
                    VESSEL
                </div>
            </div>
        </a>
        </div>
        <?php 
         }
         if($vendor_widget && empty($user_session_data->shipping_company_id)){
          $manage_vendor = checkLabelByTask('manage_vendor');
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="<?php echo ($manage_vendor) ? base_url().'vendor/index?cmi=MzI=' : 'javascript:void(0)';?>">
              <div class="mini-stat clearfix bg-googleplus rounded">
                <span class="mini-stat-icon"><i class="fa fa-id-badge" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($vendors,0,'',',');?></span>
                    VENDORS
                </div>
              </div>
          </a>
        </div>
      <?php } 
      if($user_widget){
          $manage_user = checkLabelByTask('manage_user');
        ?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="<?php echo ($manage_user) ? base_url().'/user/user_list/MQ==' : 'javascript:void(0)';?>">
            <div class="mini-stat clearfix bg-bitbucket rounded">
                <span class="mini-stat-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($users,0,'',',');?></span>
                    USERS
                </div>
              
            </div>
            </a>
        </div>
        <?php }
        // else{
        if($rfq_widget){
            ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- <a href="<?php echo base_url().'vendor/vendor_order?cmi=MzI=';?>"> -->
                <a href="javascript:void(0)">
                <div class="mini-stat clearfix bg-facebook rounded">
                <span class="mini-stat-icon"><i class="fa fa-quote-left"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($total_rfq,0,'',',');?></span>
                    TOTAL RFQ
                </div>
            </div></a>
        </div>
      <?php }
      if($po_widget){?>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <?php 
            if($purchase_order_list){
              if(empty($user_session_data->vendor_id)){
                $po_link = base_url().'report/purchase_order_list?cmi=MzI=';
              }
              else{
                $po_link = base_url().'vendor/vendor_po?cmi=MzI=';
              }
            }
            else{
              $po_link = 'javascript:void(0)';  
            }
            ?>
             <a href="<?php echo $po_link;?>">
                <div class="mini-stat clearfix bg-twitter rounded">
                <span class="mini-stat-icon"><i class="fa fa-tasks dash_task"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($total_po,0,'',',');?></span>
                    PURCHASE ORDER IN <?php echo strtoupper(date('M'))?>
                </div>
            </div>
        </a>
        </div>
      <?php }
      if($vendor_invoice_widget && empty($user_session_data->shipping_company_id)){?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
           <!--  <a href="<?php echo base_url().'vendor/vendor_invoice?cmi=MzI=';?>"> -->
                <a href="javascript:void(0)">
              <div class="mini-stat clearfix bg-googleplus rounded">
                <span class="mini-stat-icon"><i class="fa fa-tags dash_invoice" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($total_invoice,0,'',',');?></span>
                    TOTAL VENDOR INVOICES
                </div>
              </div>
          </a>
        </div>
        <?php } 
         if($company_invoice_widget){
          ?>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <!-- <a href="<?php echo base_url().'/user/user_list/MQ==';?>"> -->
                <a href="javascript:void(0)">

            <div class="mini-stat clearfix bg-bitbucket rounded">
                <span class="mini-stat-icon"><i class="fa fa-tags dashc_invoice" aria-hidden="true"></i></span>
                <div class="mini-stat-info">
                    <span><?php echo number_format($company_invoice,0,'',',');?></span>
                    TOTAL COMPANY INVOICES 
                </div>
              
            </div>
            </a>
        </div>
         <?php }
            if($view_newsletter){
            ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="javascript:void(0)" onclick="showBulletins();">
                        <div class="mini-stat clearfix bg-facebook rounded">
                        <span class="mini-stat-icon"><i class="fa fa-newspaper-o" aria-hidden="true"></i></span>
                        <div class="mini-stat-info">
                            <!-- <span></span> -->
                            <h2>NEWS</h2>
                        </div>
                    </div></a>
                </div>
        <?php } 
        if($food_menu_widget){
        ?>
          <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="javascript:void(0)" onclick="showFoodMenu();">
                        <div class="mini-stat clearfix bg-twitter rounded">
                        <span class="mini-stat-icon"><i class="fa fa-lemon"></i></span>
                        <div class="mini-stat-info">
                            <!-- <span></span> -->
                            <h2>FOOD MENU</h2>
                        </div>
                    </div></a>
            </div>
        <?php }
        if($food_recipe_widget){ 
        ?>
          <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="javascript:void(0)" onclick="showAjaxModel('Food Recipe','food_menu/food_recipe_model','','','90%');">
                <div class="mini-stat clearfix bg-googleplus rounded">
                <span class="mini-stat-icon"><i class="fas fa-cheese"></i></span>
                <div class="mini-stat-info">
                    <!-- <span></span> -->
                    <h2>FOOD RECIPE</h2>
                </div>
            </div></a>
            </div>
       <?php } ?>     
	</div>
</div>
</div>
</div>

<div class="panel-group sc-details mb-0" id="food_menu" style="display:none;">
  <div class="panel panel-default ">
    <div class="panel-heading headingBox mb-0" role="tab" id="headingOne">
    <div><h4 class="si-card-head">FOOD MENU <a title="Close Window" href="javascript:void(0)" onclick="closeFoodMenu();" class="pull-right"><i class="fa fa-window-close" aria-hidden="true"></i></a></h4></div>
    </div>
    <div class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="container text-center mt-5">
            <h2>FOOD MENU</h2>
                <button data-id="indian_asian" class="btn btn-mini btn-danger btn-slideright food_menu_button" type="button">
                INDIAN / ASIAN </button>
                <button data-id="east_europe" class="btn btn-mini btn-danger btn-slideright food_menu_button" type="button">
                EAST EUROPE</button>
                <button data-id="phili_indo" class="btn btn-mini btn-danger btn-slideright food_menu_button" type="button">
                PHILI / INDO</button>
                <button data-id="chines" class="btn btn-mini btn-danger btn-slideright food_menu_button" type="button">
                CHINES</button>
        </div>
        <br>
         <div class="food_menu_data">
        </div>
  </div>
  </div> 
</div>
<br>

</div>
<?php

if($view_newsletter){
?>
<div class="panel-group sc-details mb-0" id="bulletins" style="display:none;">
  <div class="panel panel-default ">
    <div class="panel-heading headingBox mb-0" role="tab" id="headingOne">
    <div><h4 class="si-card-head">News <a title="Close Window" href="javascript:void(0)" onclick="closeBulletins();" class="pull-right"><i class="fa fa-window-close" aria-hidden="true"></i></a></h4></div>
    </div>
    <div class="panel-collapse collapse in">
      <div class="panel-body">
         <div class="news_container">
         <table class="table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
             <thead>
                 <tr>
                     <th style="color:#296a7e;">News Title</th>
                     <th style="color:#296a7e;">Issue Date</th>
                     <th style="color:#296a7e;">Added By</th>
                     <th width="2%"></th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                    if(!empty($bulletins)){
                      foreach ($bulletins as $new) {
                     ?>
                        <tr>
                            <td><?php echo ucwords($new->title)?></td>
                            <td><?php echo ConvertDate($new->publish_on,'','d-m-Y');?></td>
                            <td><?php echo ucwords($new->user_name)?></td>
                            <td width="2%" class="action-td"><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="<?php echo base_url('uploads/sheets/'.$new->attechment);?>" download="<?php echo $new->attechment; ?>">Download</a>
                                    </li>
                                </ul>
                                </div>
                            </td>
                        </tr>
                    <?php
                         }
                        }
                    else{
                    ?>
                     <tr><td colspan="4" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>
                    <?php }
                    ?>
             </tbody>
         </table>        
    </div>
  </div>
  </div> 
</div>
</div>
<br>

<?php } 
      if($sales_pending_graph || $sales_received_graph || $purchase_paid_graph || $purchase_due_graph){
           $factive = 'active';
           $ffactive = 'in active';
      }
      elseif($victualling_rate_analysis) {
           $vactive = 'active';
           $vvactive = 'in active';

      }
      elseif($condemned_stock_analysis) {
           $cactive = 'active';
           $ccactive = 'in active';

      }
      elseif($meat_consumption) {
           $mactive = 'active';
           $mmactive = 'in active';
      }
      elseif($nutrition_report) {
           $nactive = 'active';
           $nnactive = 'in active';
      }


if($sales_pending_graph || $sales_received_graph || $purchase_paid_graph || $purchase_due_graph || $victualling_rate_analysis || $condemned_stock_analysis || $meat_consumption){
?>
<div id="top-tabs">
  <!-- Nav tabs -->
  <ul class="nav nav-pills nav-justified" role="tablist">
    <?php
      if($sales_pending_graph || $sales_received_graph || $purchase_paid_graph || $purchase_due_graph){
      ?>
    <li role="presentation" class="<?php echo $factive?>">
      <a href="#financial" aria-controls="financial" role="tab" data-toggle="tab">Financial Transactions</a>
    </li>
    <?php }
    if($victualling_rate_analysis){
    ?>
    <li role="presentation" class="<?php echo $vactive?>">
      <a href="#victualling" aria-controls="victualling" role="tab" data-toggle="tab">Victualling Rate Analysis</a>
    </li>
    <?php }
    if($condemned_stock_analysis){
    ?>
    <li role="presentation" class="<?php echo $cactive?>">
      <a href="#condemned" aria-controls="condemned" role="tab" data-toggle="tab">Condemned Stock Analysis</a>
    </li>
    <?php }
    if($meat_consumption){
    ?>
    <li role="presentation" class="<?php echo $mactive?>">
      <a href="#meat" aria-controls="meat" role="tab" data-toggle="tab">Meat Consumption</a>
    </li>
    <?php } ?>
  </ul>

  <!-- Tab content -->
  <div class="tab-content">
    <!-- finacial transaction start -->
    <?php
      if($sales_pending_graph || $sales_received_graph || $purchase_paid_graph || $purchase_due_graph){
      ?>
    <div role="tabpanel" class="tab-pane fade <?php echo $ffactive;?>" id="financial">
      
        <div class="grid-container">
          <?php 
         if($sales_received_graph){
            ?>
         <div class="grid-item">
          <div class="dashboard-card">
            <div class="dc-heading">
              <h4>Sales Received Amount</h4>
            </div>
            <div class="dashboard-filter">
                 <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Graph Type</label>
                    <div>
                     <select class="form-control customFilter" name="graph_type" id="graph_type_ra" onchange="getCompRecAmount();">
                        <option value="column">Bar Chart</option>
                        <option value="spline" selected>Line Chart</option>
                    </select>
                </div>
              </div>
                  <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Shipping Company</label>
                    <div>
                     <select class="form-control" name="shipping_company_id" id="company_id_ra" class="customFilter" onchange="getCompRecAmount();getAllShipsById(this.value,'ship_id_ra');">
                        <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>  
                     </select>   
                </div>
            </div> -->
             <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Vessel Name</label>
                <div>
                 <select class="form-control customFilter" name="ship_id" id="ship_id_ra" onchange="getCompRecAmount();">
                  <option value="">Select Vessel</option>
                  <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?>  
                 </select>   
            </div>
            </div> -->
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Date Range</label>
                <div>
                 <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_ra" onchange="getCompRecAmount();" value="<?php echo $date_range ;?>">
                </div>
            </div>
             </div>
            </div>
            <div class="graph-box" id="sra">
             
           </div>
           </div>
        </div>
         <?php }
         if($sales_pending_graph){?>

<div class="grid-item">
  <div class="dashboard-card">
    <div class="dc-heading">
      <h4>Sales Pending Amount</h4>
    </div>
    <div class="dashboard-filter">
                  <div class="row">
                  <div class="col-xs-6">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_pa" onchange="getCompanyPendingAmount();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
                <!--     <div class="col-xs-6">
                    <label>Shipping Company</label>
                      <div>
                      <select class="form-control" name="shipping_company_id" id="company_id_pa" class="customFilter" onchange="getCompanyPendingAmount();getAllShipsById(this.value,'ship_id_pa');">
                        <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>
              </select>
            </div>
          </div>
          <div class="col-xs-6">
            <label>Vessel Name</label>
            <div>
              <select class="form-control customFilter" name="ship_id" id="ship_id_pa" onchange="getCompanyPendingAmount();">
                <option value="">Select Vessel</option>
                <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?> 
              </select>
            </div>
          </div> -->
            <div class="col-xs-6">
              <label>Date Range</label>
                <div>
                 <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_pa" onchange="getCompanyPendingAmount();" value="<?php echo $date_range ;?>">
                </div>
            </div>
              <div class="col-sm-3">
              <label>Due Date</label>
                <div>
                 <input type="text" class="form-control customFilter datePicker_editPro" name="" id="due_date_pa" onchange="getCompanyPendingAmount();">
                </div>
            </div>
        </div>
    </div>
    <div class="graph-box" id="sale_pending"></div>
<!-- <div class="loader"></div> -->

    </div>
</div>
<?php } 
 if($purchase_paid_graph){?>
 <div class="grid-item">
 <div class="dashboard-card">
 <div class="dc-heading">
      <h4>Purchase Paid Amount</h4>
    </div>
    <div class="dashboard-filter">
                  <div class="row">
                  <div class="col-xs-4">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_vp" onchange="getVendorPaidAmount();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
<!--                   <div class="col-xs-4">
                    <label>Column View</label>
                      <div>
                      <select class="form-control customFilter" name="column_vp" id="column_vp" onchange="getVendorPaidAmount();">
                          <option value="company" selected="selected">Company & Vessel</option>
                          <option value="vendor">Vendor</option>
                      </select>
                  </div>
                </div> -->
                <!--     <div class="col-xs-4">
                    <label>Shipping Company</label>
                      <div>
                      <select class="form-control" name="shipping_company_id" id="company_id_vp" class="customFilter" onchange="getVendorPaidAmount();getAllShipsById(this.value,'ship_id_vp');">
                          <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <label>Vessel Name</label>
            <div>
              <select class="form-control customFilter" name="ship_id" id="ship_id_vp" onchange="getVendorPaidAmount();">
                <option value="">Select Vessel</option>
                <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?> 
              </select>
            </div>
          </div> -->
              <div class="col-xs-4">
               <label>Vendors</label>
               <div>
                <select id="vendor_id_vp" name="vendor_id[]" onchange="getVendorPaidAmount('', 1);" class="form-control">
                  <option value="">Select Vendor</option>
                  <?php
                   if(!empty($all_vendors)){
                     foreach($all_vendors as $row){
                      ?>
                      <option value="<?php echo $row->vendor_id;?>"><?php echo ucwords($row->vendor_name);?></option>
                       <?php } 
                     }
                    ?>
                    </select>
                </div>
            </div>
          <div class="col-xs-4">
            <label>Date Range</label>
            <div>
              <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_vp"
                onchange="getVendorPaidAmount();" value="<?php echo $date_range ;?>">
            </div>
          </div>
        </div>
    </div>
<div class="graph-box" id="purchase_paid"></div>

</div>

</div>
<?php } 
if($purchase_due_graph){?>
<div class="grid-item" >
<div class="dashboard-card">
<div class="dc-heading">
      <h4>Purchase Due Amount</h4>
    </div>
    <div class="dashboard-filter">
                  <div class="row">
                  <div class="col-xs-4">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_vd" onchange="getVendorDueAmount();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
      <!--             <div class="col-xs-4">
                    <label>Column View</label>
                      <div>
                      <select class="form-control customFilter" name="column_vd" id="column_vd" onchange="getVendorDueAmount();">
                          <option value="company" selected="selected">Company & Vessel</option>
                          <option value="vendor">Vendor</option>
                      </select>
                  </div>
                </div>
                    <div class="col-xs-4">
                    <label>Shipping Company</label>
                      <div>
                      <select class="form-control" name="shipping_company_id" id="company_id_vd" class="customFilter" onchange="getVendorDueAmount();getAllShipsById(this.value,'ship_id_vd');">
                          <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>
              </select>
            </div>
          </div>
          <div class="col-xs-4">
            <label>Vessel Name</label>
            <div>
              <select class="form-control customFilter" name="ship_id" id="ship_id_vd" onchange="getVendorDueAmount();">
                <option value="">Select Vessel</option>
                <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?> 
              </select>
            </div>
          </div> -->
            <div class="col-xs-4">
               <label>Vendors</label>
               <div>
                <select id="vendor_id_vd" name="vendor_id[]" onchange="getVendorDueAmount('', 1);" class="form-control">
                  <option value="">Select Vendor</option>
                  <?php
                   if(!empty($all_vendors)){
                     foreach($all_vendors as $row){
                      ?>
                      <option value="<?php echo $row->vendor_id;?>"><?php echo ucwords($row->vendor_name);?></option>
                       <?php } 
                     }
                    ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-4">
            <label>Date Range</label>
            <div>
              <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_vd"
                onchange="getVendorDueAmount();" value="<?php echo $date_range ;?>">
            </div>
          </div>
            <div class="col-sm-3">
              <label>Due Date</label>
                <div>
                 <input type="text" class="form-control customFilter datePicker_editPro" name="" id="due_date_vd" onchange="getVendorDueAmount();">
                </div>
            </div>
        </div>
    </div>
<div class="graph-box" id="purchase_due"></div>
<!-- <div class="loader"></div> -->
</div>
</div>
<?php }
?>
    </div>


    </div>
    <!-- finacial transaction end -->
<?php } 
    if($victualling_rate_analysis){
    ?>
    <div role="tabpanel" class="tab-pane fade <?php echo $vvactive;?>" id="victualling">
      <!-- victialling rate start -->
      <div class="grid-item">
          <div class="dashboard-card">
            <div class="dc-heading">
              <!-- <h4>Sales Received Amount</h4> -->
            </div>
            <div class="dashboard-filter">
                 <div class="row">
                 <div class="col-xs-4">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_vc" onchange="getVendorDueAmount();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
                 <div class="col-xs-4">
                    <label>Date Range</label>
                    <div>
                      <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_vic" onchange="getVictuallingReport();" value="<?php echo $date_range ;?>">
                    </div>
                  </div>
                 <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Data Limit</label>
                    <div>
                     <select class="form-control customFilter" name="time" id="time_vc" onchange="getVictuallingReport();">
                        <option value="">Select Period</option>
                        <option selected value="month_3">Last 3 Month</option>
                        <option value="month_4">Last 6 Month</option>
                        <option value="c_year">Current Year</option>
                        <option value="last_two">Last 2 Year</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Group By</label>
                    <div>
                     <select class="form-control customFilter" name="group_by" id="group_by" onchange="getVictuallingReport();">
                        <option value="month">Monthly</option>
                        <option value="quarter">Quarterly</option>
                        <option value="half_year">Half Yearly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>
              </div> -->
                  <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Shipping Company</label>
                    <div>
                     <select class="form-control" name="shipping_company_id" id="company_id_vc" class="customFilter" onchange="getAllShipsById(this.value,'ship_id_vc');getVictuallingReport();">
                        <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>
                     </select>   
                </div>
            </div>
             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Vessel Name</label>
                <div>
                 <select class="form-control customFilter" name="ship_id" id="ship_id_vc" onchange="getVictuallingReport();">
                     <option value="">Select Vessel</option> 
                     <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?>  
                 </select>   
            </div>
            </div> -->
             </div>
            </div>
            <div class="graph-box" id="victualling_rate">
             
           </div>
           </div>
        </div>
        <!-- victualing rate end  -->
    </div>
    <?php } 
    if($condemned_stock_analysis){    
    ?>
    <div role="tabpanel" class="tab-pane fade <?php echo $ccactive;?>" id="condemned">
      <!-- condemend start -->

      <div class="grid-item">
          <div class="dashboard-card">
            <div class="dc-heading">
              <!-- <h4>Sales Received Amount</h4> -->
            </div>
            <div class="dashboard-filter">
              <div class="row">
                <div class="col-xs-4">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_con" onchange="getVendorDueAmount();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
                  <div class="col-xs-4">
                    <label>Date Range</label>
                    <div>
                      <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_con" onchange="condemned_stock();" value="<?php echo $date_range ;?>">
                    </div>
                  </div>
              <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Data Limit</label>
                    <div>
                     <select class="form-control customFilter" name="time" id="time_cnd" onchange="condemned_stock();">
                        <option value="">Select Period</option>
                        <option selected value="month_3">Last 3 Month</option>
                        <option value="month_4">Last 6 Month</option>
                        <option value="c_year">Current Year</option>
                        <option value="last_two">Last 2 Year</option>
                    </select>
                </div>
              </div>  
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Group By</label>
                    <div>
                     <select class="form-control customFilter" name="group_by" id="group_by_cnd" onchange="condemned_stock();">
                        <option value="month">Monthly</option>
                        <option value="quarter">Quarterly</option>
                        <option value="half_year">Half Yearly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>
              </div> -->
                  <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Shipping Company</label>
                    <div>
                     <select class="form-control" name="shipping_company_id" id="company_id_cnd" class="customFilter" onchange="getAllShipsById(this.value,'ship_id_cnd');condemned_stock();">
                        <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?>
                     </select>   
                </div>
            </div>
             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Vessel Name</label>
                <div>
                 <select class="form-control customFilter" name="ship_id" id="ship_id_cnd" onchange="condemned_stock();">
                     <option value="">Select Vessel</option>  
                     <?php
                  if(!empty($ships)){
                    foreach ($ships as $row) {
                      ?>
                      <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                   <?php  }
                  }
                  ?> 
                 </select>   
            </div>
            </div> -->
             </div>
            </div>
            <div class="graph-box" id="condemned_stock">
           </div>
           </div>
        </div>

      <!-- condemend end -->
    </div>
    <?php } 
        if($meat_consumption){
    ?>
    <div role="tabpanel" class="tab-pane fade <?php echo $mmactive;?>" id="meat">
      <!-- Meat Start-->
       <div class="grid-item">
          <div class="dashboard-card">
            <div class="dc-heading">
              <!-- <h4>Sales Received Amount</h4> -->
            </div>
            <div class="dashboard-filter">
                 <div class="row">
                  <div class="col-xs-4">
                    <label>Graph Type</label>
                      <div>
                      <select class="form-control customFilter" name="graph_type" id="graph_type_meat" onchange="getMeatReport();">
                          <option value="column">Bar Chart</option>
                          <option value="spline" selected>Line Chart</option>
                      </select>
                  </div>
                </div>
                  <div class="col-xs-4">
                    <label>Date Range</label>
                    <div>
                      <input type="text" class="form-control date-range-picker-clearbtn" name="" id="date_range_meat" onchange="getMeatReport();" value="<?php echo $date_range ;?>">
                    </div>
                  </div>
                <!--  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Data Limit</label>
                    <div>
                     <select class="form-control customFilter" name="time" id="time_meat" onchange="getMeatReport();">
                        <option value="">Select Period</option>
                        <option selected value="month_3">Last 3 Month</option>
                        <option value="month_4">Last 6 Month</option>
                        <option value="c_year">Current Year</option>
                        <option value="last_two">Last 2 Year</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Group By</label>
                    <div>
                     <select class="form-control customFilter" name="group_by" id="group_by_meat" onchange="getMeatReport();">
                        <option value="month">Monthly</option>
                        <option value="quarter">Quarterly</option>
                        <option value="half_year">Half Yearly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>
              </div> -->
                  <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <label>Shipping Company</label>
                    <div>
                     <select class="form-control" name="shipping_company_id" id="company_id_meat" class="customFilter" onchange="getAllShipsById(this.value,'ship_id_meat');getMeatReport();">
                         <?php
                        if(empty($user_session_data->shipping_company_id)){
                          ?>
                          <option value="">Select Company</option>
                         <?php } 
                        ?>
                        <?php
                           if($company){
                               foreach ($company as $row) {
                                if(!empty($user_session_data->shipping_company_id)){
                                  if($row->shipping_company_id==$user_session_data->shipping_company_id){
                                    echo '<option selected value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    

                                  }
                                 }
                                 else{
                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                 }
                               }                                       
                            }
                         ?> 
                     </select>   
                </div>
            </div>
             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Vessel Name</label>
                <div>
                 <select class="form-control customFilter" name="ship_id" id="ship_id_meat" onchange="getMeatReport();">
                     <option value="">Select Vessel</option>  
                     <?php
                      if(!empty($ships)){
                        foreach ($ships as $row) {
                          ?>
                          <option value="<?php echo $row->ship_id;?>"><?php echo  ucwords($row->ship_name);?></option>
                       <?php  }
                      }
                     ?> 
                 </select>   
            </div>
            </div> -->
             </div>
            </div>
            <div class="graph-box" id="meat_report">
             
           </div>
           </div>
        </div>
      <!-- Meat End -->
    </div>
    <?php }
        if($manage_nutrition_report){ 
    ?>
    <!-- Nutrition Report Start -->
   <!--  <div role="tabpanel" class="tab-pane fade <?php echo $nnactive;?>" id="nutrition">
        Coming soon....
    </div> -->
    <!-- Nutrition Report END -->

    <?php } ?>
  </div>
</div>
<?php } ?>
</div>


<script type="text/javascript">
   $(document).ready(function(){
     getCompanyPendingAmount();
     getCompRecAmount();
     getVendorDueAmount();
     getVendorPaidAmount();
     getVictuallingReport();
     condemned_stock();
     getMeatReport();
   })

  function getVendorDueAmount(){
    var $div_id = 'purchase_due';
    let chart_type = $('#graph_type_vd').val();
    let vendor_id = $('#vendor_id_vd').val();
    let date = $('#date_range_vd').val();
    let due_date = $('#due_date_vd').val();
    
     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getVendorDueAmount',
      data: {'company_id':shipping_company_id,'ship_id':ship_id,'vendor_id':vendor_id,'date':date,'due_date':due_date},
      cache:false,
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var $graph = {};
          $graph.chart_type = chart_type; 
          $graph.div_id = $div_id;
         // $graph.title = 'Purchase Due Amount';
          $graph.xAxis_category = obj.columns;
          $graph.xAxis_title = obj.xtitle;
          $graph.series_data = obj.series;
          $counter = obj.counter;
          setLineGraph($graph);
        }
     });    
   }

   function getVendorPaidAmount(){
     var $div_id = 'purchase_paid';
     let chart_type = $('#graph_type_vp').val();
     // let company_id = $('#company_id_vp').val();
     // let ship_id = $('#ship_id_vp').val();
     let vendor_id = $('#vendor_id_vp').val();
     // let column = $('#column_vp').val();
     let date = $('#date_range_vp').val();
     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getVendorPaidAmount',
      cache:false,
      data: {'company_id':shipping_company_id,'ship_id':ship_id,'vendor_id':vendor_id,'date':date},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var $graph = {};
          $graph.chart_type = chart_type; 
          $graph.div_id = $div_id;
          $graph.xAxis_category = obj.columns;
          $graph.xAxis_title = obj.xtitle;
          $graph.series_data = obj.series;
          $counter = obj.counter;
          setLineGraph($graph);
        }
     });    
   }

  function getCompanyPendingAmount(){
     var $div_id = 'sale_pending';
     let chart_type = $('#graph_type_pa').val();

     // let company_id = $('#company_id_pa').val();
     // let ship_id = $('#ship_id_pa').val();  
     
     let date = $('#date_range_pa').val();  
     let due_date = $('#due_date_pa').val();  
    
     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getCompanyPendingAmount',
      cache:false,
      data : {'company_id':shipping_company_id,'ship_id':ship_id,'date':date,'due_date':due_date},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var $graph = {};
          $graph.chart_type = chart_type; 
          $graph.div_id = $div_id;
          $graph.xAxis_category = obj.columns;
          $graph.xAxis_title = obj.xtitle;
          $graph.series_data = obj.series;
          $counter = obj.counter;
          setLineGraph($graph);
        }
     });    
   }
   
 function getCompRecAmount(){
     var $div_id = 'sra';
     let chart_type = $('#graph_type_ra').val();
     
     // let company_id = $('#company_id_ra').val();
     // let ship_id = $('#ship_id_ra').val(); 

     let date = $('#date_range_ra').val();         
     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getCompanyReceivedAmount',
      cache:false,
      data: {'company_id':shipping_company_id,'ship_id':ship_id,'date':date},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var $graph = {};
          $graph.chart_type = chart_type; 
          $graph.div_id = $div_id;
          // $graph.title = 'Sales Received Amount';
          $graph.xAxis_category = obj.columns;
          $graph.xAxis_title = obj.xtitle;
          $graph.series_data = obj.series;
          $counter = obj.counter;
          setLineGraph($graph);
        }
     });    
   } 

   // function getAllShipsById(shipping_company_id,element_id){
   //     $.ajax({
   //          beforeSend: function(){
   //              $("#customLoader").show();
   //          },
   //          type: "POST",
   //          url: base_url + 'user/getShipsForDashboard',
   //          data: {'shipping_company_id':shipping_company_id},
   //          success: function(msg){
   //              $("#customLoader").hide();
   //              var obj = jQuery.parseJSON(msg);
   //              $('#'+element_id).html(obj.data);
   //          }
   //      });   
   // }  

  $(document).on('click','.cust_start_date',function(){
    $(this).addClass('current-datRange');
    $(this).closest('.daterangepicker').find('.cust_end_date').removeClass('current-datRange');
    $(this).closest('.daterangepicker').find('.cust_cal_right').hide();
    $(this).closest('.daterangepicker').find('.cust_cal_left').show();
  });

  $(document).on('click','.cust_end_date',function(){
   $(this).addClass('current-datRange');
   $(this).closest('.daterangepicker').find('.cust_start_date').removeClass('current-datRange');
   $(this).closest('.daterangepicker').find('.cust_cal_left').hide();
   $(this).closest('.daterangepicker').find('.cust_cal_right').show();
  });

  function getVictuallingReport(){
    var $div_id = 'victualling_rate';
    var type = $('#graph_type_vic').val();

    // var group_by = $('#group_by').val();
    // var ship_id = $('#ship_id_vc').val();
    // var shipping_company_id = $('#company_id_vc').val();          
    // var time_vc = $('#time_vc').val();
    
    var date_range_vic = $('#date_range_vic').val();

     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getVictuallingReportGraph',
      cache:false,
      data: {'ship_id':ship_id,'shipping_company_id':shipping_company_id,'date':date_range_vic},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var seriesData = obj.seriesData;
          var months = obj.months;
          var chartSeries = [];
            $.each(seriesData, function(shipName, data) {
                chartSeries.push({
                    name: shipName,
                    data: data
                });
            });

          var $graph = {};
          $graph.div_id = $div_id;
          $graph.title = '';
          $graph.xAxis_category = months;
          $graph.chart_type = type; 
          $graph.yAxis_title = 'Victualing Rate';
          $graph.series_data = chartSeries;
          $counter = obj.counter;
          stackedAndGroupedGraph($graph);
        }
     }); 
  }

  function condemned_stock(){
    var $div_id = 'condemned_stock';
    var type = $('#graph_type_con').val();

    // var graph_type = $('#graph_type_cnd').val();
    // var company_id = $('#company_id_cnd').val();
    // var group_by = $('#group_by_cnd').val();
    // var ship_id = $('#ship_id_cnd').val(); 
    // var time_cnd = $('#time_cnd').val();

    var date_range_con =  $('#date_range_con').val();
     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/condemned_stock_graph',
      cache:false,
      data: {'ship_id':ship_id,'shipping_company_id':shipping_company_id,'date':date_range_con},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var seriesData = obj.seriesData;
          var months = obj.months;
          var type = obj.type;
          var chartSeries = [];
            $.each(seriesData, function(shipName, data) {
                chartSeries.push({
                    name: shipName,
                    data: data
                });
            });
          
          var $graph = {};
          $graph.chart_type = type; 
          $graph.div_id = $div_id;
          $graph.xAxis_category = months;
          $graph.yAxis_title = 'Condemned Stock Value';
          $graph.series_data = chartSeries;
          $counter = obj.counter;
          stackedAndGroupedGraph($graph);
        }
     }); 
  }

 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        endDate: '-1d'
    });
 });

 function getMeatReport(){
    var $div_id = 'meat_report';
    var type = $('#graph_type_meat').val();

    // var group_by = $('#group_by_meat').val();
    // var ship_id = $('#ship_id_meat').val();
    // var shipping_company_id = $('#company_id_meat').val();
    // var time_meat = $('#time_meat').val();  

    var date_range_meat = $('#date_range_meat').val();

     $.ajax({
      beforeSend: function(){
        $('#'+$div_id).html('<div class="loader"></div>');
      },
      type: "POST",
      url: base_url + 'user/getMeatReportGraph',
      cache:false,
      data: {'ship_id':ship_id,'shipping_company_id':shipping_company_id,'date':date_range_meat},
      success: function(msg){
          var obj = jQuery.parseJSON(msg);
          var seriesData = obj.seriesData;
          var months = obj.months;
          var chartSeries = [];
            $.each(seriesData, function(shipName, data) {
                chartSeries.push({
                    name: shipName,
                    data: data
                });
            });

          var $graph = {};
          $graph.div_id = $div_id;
          $graph.title = '';
          $graph.xAxis_category = months;
          $graph.chart_type = type; 
          $graph.yAxis_title = 'Meat Report';
          $graph.series_data = chartSeries;
          $counter = obj.counter;
          stackedAndGroupedGraph($graph);
        }
     }); 
  }

  function closeBulletins(){
    $('#bulletins').hide();
  }

  function showBulletins(){
    $('#bulletins').show();
  }

  $(document).ready(function(){
    $('.food_menu_button').click(function(){
        var religion = $(this).data('id');
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'food_menu/getAllFoodMenuByReligion',
            cache:false,
            data: {'religion':religion},
            success: function(msg)
            {
                $("#customLoader").hide();
                $('.food_menu_data').show();
                var obj = jQuery.parseJSON(msg);
                $('.food_menu_data').html(obj.dataArr);
            }
        });
    })
  })

  function closeFoodMenu(){
    $('#food_menu').hide();
    $('.food_menu_data').hide();
  }

  function showFoodMenu(){
    $('#food_menu').show();
  }


  $(document).ready(function() {
    $('#singleDropdown').select2({
      placeholder: "Select one option",
      allowClear: true,
      // width: '100'
    });
  });

  function getReportByCompany(id){
    window.location.href = base_url + "user/user_dashboard/" + btoa(id);
  }

</script>