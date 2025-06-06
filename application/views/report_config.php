<?php 
 $years = array(); 
 if(!empty($year)){
   foreach ($year as $key => $row) {
      $years[] = $row->year;
    } 
 }
 $years = array_unique($years);

 if(empty($years)){
   $msg = 'Sorry! but we dont have previous data for this vessel'; 
 }
?>
<div class="body-content animated fadeIn">
<div class="panel-body rounded-bottom">
    <div class="row">
    <div class="col-md-12">
    <form class="form-horizontal form-bordered" name="report_config" id="report_config" method="post">
    <div class="form-body">        
        <div class="row">
               <?php 
                 if(empty($ship_id)){
                  ?>
                  <div class="form-group col-sm-6 " >
                            <label class="col-sm-12">Shipping Company</label>
                            <div class="col-sm-12">
                                <select name="shipping_company_id" id="shipping_company_id" class="form-control" onchange="getAllShipsById(this.value)">
                                <option value="">Select Company</option>
                                  <?php
                                   foreach ($company as $row) {
                                     $selected = ($dataArr['shipping_company_id'] == $row->shipping_company_id) ? 'selected' : '';
                                     ?>
                                     <option <?php echo $selected;?> value="<?php echo $row->shipping_company_id?>"><?php echo ucwords($row->name)?></option>
                                   <?php }
                                  ?>
                                </select>
                            </div>
                        </div>
                  <div class="form-group col-sm-6 <?php echo (form_error('ship_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Vessel Name <span>*</span></label>
                            <div class="col-sm-12">
                                <select name="ship_id" id="ship_ids" class="form-control" >
                                    <option value="">Select Vessel</option>
                                   <?php
                                   foreach ($ships as $row) {
                                     $select = ($dataArr['ship_id'] == $row->ship_id) ? 'selected' : '';
                                     ?>
                                     <option <?php echo $select;?> value="<?php echo $row->ship_id;?>"><?php echo ucwords($row->ship_name);?></option>
                                   <?php }
                                  ?> 
                                </select>
                                <?php echo form_error('ship_id','<p class="error" style="display: inline;">','</p>')?>        
                            </div>
                        </div>
                 <?php } 
               ?>
               <div class="form-group col-sm-6 <?php echo (form_error('month')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Year <span>*</span></label>
                            <div class="col-sm-12">
                             <select name="year" id="years" class="form-control" onchange="getMonths(this.value);">
                              <option value="">Select Year</option>
                                <?php
                                  for ($i=0; $i < count($years); $i++) { 
                                   $selected = ($dataArr['year'] == $years[$i])?'selected':'';
                                   echo '<option value="'.$years[$i].' "'.$selected.'>'.$years[$i].'</option>';
                                  }
                                 ?>
                             </select>
                             <?php echo form_error('year','<p class="error" style="display: inline;">','</p>')?>
                            </div>
                        </div>
               <div class="form-group col-sm-6 <?php echo (form_error('month')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Month <span>*</span></label>
                            <div class="col-sm-12">
                                <select name="month" id="months" class="form-control">
                                         <option value="">Select Month</option>
                               </select>
                                <?php echo form_error('month','<p class="error" style="display: inline;">','</p>')?>        
                            </div>
                        </div>             
        </div>
                 <span align="center" style="font-weight:bold;font-size:12px;color: red"><?php echo $msg;?></span>  
        <input type="hidden" name="actionType" value="save">
        <input type="hidden" name="id" id="month_ship_id" value="<?php echo $ship_id;?>">
        <input type="hidden" name="type" value="<?php echo $type;?>">
       <div class="form-footer">
                <div class="pull-right">
                    <a class="btn btn-danger btn-slideright mr-5" href="javascript:void(0)" data-dismiss="modal">Cancel</a>
                    <button type="button" onclick="submitReportConfigForm()" class="btn btn-success btn-slideright mr-5">Save & Next</button>
              </div>
            <div class="clearfix"></div>
            </div><!-- /.form-footer -->
        </div>
    </form>
    </div>
</div>

<script type="text/javascript">
  function submitReportConfigForm(){
     var type = '<?php echo $type;?>';
     var $data = new FormData($('#report_config')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'report/report_config/<?php echo $type;?>',
            cache:false,
            data: $data,
             processData: false,
            contentType: false,
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='100'){
                    $('#modal-view-datatable').modal('show');
                    $('#modal_content').html(obj.data);
                    $(".modal-dialog").css("width", "50%");
                }else{
                  if(type=='extra_meals'){
                    showAjaxModel('Extra Meals','shipping/add_extra_meals','','','98%','full-width-model');
                  }
                  else if(type=='summary_report'){
                    showAjaxModel('Victualing Summary','shipping/add_victualing_report','','','98%','full-width-model')
                  }
                  else{
                    showAjaxModel('Condemned Stock Report','report/add_condemned_stock_report','','<?php echo $ship_id;?>','98%','full-width-model')
                  }  
                }
            }
        });            
  }  

 function getAllShipsById(shipping_company_id){
       $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'user/getShipsByCompanyId',
            data: {'shipping_company_id':shipping_company_id},
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#ship_ids').html(obj.data);
            }
        });   
   } 

 function getMonths(year){
   var ship_id = $('#month_ship_id').val();  
   $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/stock_month',
            data: {'year':year,'ship_id':ship_id},
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#months').html(obj.data);
            }
        });   
 }  

</script>