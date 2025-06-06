<div id="tour-11" class="header-content">
    <?php 
    $user_session_data = getSessionData();
    $pageHead = 'Home';?>
    <h2><img src="<?php echo base_url();?>assets/images/home-blue-icon.png" alt="Home Icon" /> <span class="oblc">/</span> <?php echo $pageHead;?> <span></span></h2>
</div>
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg)) { ?>
    <div class="custom_alert alert alert-success">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?php echo $succMsg; ?></div>
<?php } ?>
<div class="col-md-12 child_div" dashboard-element="recent_email">
  <form id="email_list_frm" name="email_list_frm" method="POST" action="<?php echo base_url('user/getAllUserEmailLogs'); ?>">
        <div class="panel" style="background:none;">
            <div class="panel-heading no-border groupheading  mb-13 move">
            <h4 class="panel-title subtitle">
                <span>Raw Material</span>
            </h4>
            </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="clearfix"></div>
            <div class="panel panel-body-new panel-scrollable-default panel-height no-padding" style="overflow:hidden;">
                <div class="panel-body no-padding recentmails " style="max-height:540px; height:auto;">
                       <div class="table-responsive">
                                <table id="send_from_table" class="table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                    <thead>
                                        <tr>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Vendor</a>
                                  </th>
                                   <th id="unit_th">
                                     <a href="javascript:void(0);">Order No</a>
                                  </th>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Raw Material</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Quantity</a>
                                  </th>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Order Date</a>
                                  </th>
                                     </tr>
                                    </thead>
                                    <tbody class="email_list_tbody">
                                        <tr><td></td></tr>
                                    </tbody>
                                </table></div>
                     </div>           
            </div>
            <!--end panel-->
               <div class="panel" style="background:none;">
            <div class="panel-heading no-border groupheading  mb-13 move">
            <h4 class="panel-title subtitle">
                <span>Raw Material Left</span>
            </h4>
            </div>
                <div class="clearfix"></div>
            </div>
           <!-- /.panel-heading -->
            <div class="clearfix"></div>
            <div class="panel panel-body-new panel-scrollable-default panel-height no-padding" style="overflow:hidden;">
                <div class="panel-body no-padding recentmails " style="max-height:540px; height:auto;">
                       <div class="table-responsive">
                                <table id="send_from_table" class="table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                    <thead>
                                        <tr>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Raw Material</a>
                                  </th>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Category</a>
                                  </th> 
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Quantity</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Unit</a>
                                  </th>
                                     </tr>
                                    </thead>
                                    <tbody class="email_list_tbody">
                                        <tr><td></td></tr>
                                    </tbody>
                                </table></div>
                     </div>           
            </div>
        <!--end panel-->
               <div class="panel" style="background:none;">
            <div class="panel-heading no-border groupheading  mb-13 move">
            <h4 class="panel-title subtitle">
                <span>All Products</span>
            </h4>
            </div>
                <div class="clearfix"></div>
            </div>
           <!-- /.panel-heading -->
            <div class="clearfix"></div>
            <div class="panel panel-body-new panel-scrollable-default panel-height no-padding" style="overflow:hidden;">
                <div class="panel-body no-padding recentmails " style="max-height:540px; height:auto;">
                       <div class="table-responsive">
                                <table id="send_from_table" class="table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                    <thead>
                                        <tr>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Product</a>
                                  </th>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Category</a>
                                  </th> 
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Quantity</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Unit</a>
                                  </th>
                                     </tr>
                                    </thead>
                                    <tbody class="email_list_tbody">
                                        <tr><td></td></tr>
                                    </tbody>
                                </table></div>
                     </div>           
            </div>
         <!--end panel-->
               <div class="panel" style="background:none;">
            <div class="panel-heading no-border groupheading  mb-13 move">
            <h4 class="panel-title subtitle">
                <span>Products Left</span>
            </h4>
            </div>
                <div class="clearfix"></div>
            </div>
           <!-- /.panel-heading -->
            <div class="clearfix"></div>
            <div class="panel panel-body-new panel-scrollable-default panel-height no-padding" style="overflow:hidden;">
                <div class="panel-body no-padding recentmails " style="max-height:540px; height:auto;">
                       <div class="table-responsive">
                                <table id="send_from_table" class="table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                    <thead>
                                        <tr>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Customer</a>
                                  </th>
                                  <th id="unit_th">
                                     <a href="javascript:void(0);">Order No</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Product</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Quantity</a>
                                  </th>
                                 <th id="unit_th">
                                     <a href="javascript:void(0);">Order Date</a>
                                  </th>
                                     </tr>
                                    </thead>
                                    <tbody class="email_list_tbody">
                                        <tr><td>name</td></tr>
                                    </tbody>
                                </table></div>
                     </div>           
            </div>   