<?php
$session = getCustomSession('ship_details');
$dataArr['delivery_port'] = ($dataArr['delivery_port']) ? $dataArr['delivery_port'] : $rfq_data['porrt_name'];
$dataArr['country'] = ($dataArr['country']) ? $dataArr['country'] : $rfq_data['country'];
$dataArr['agent_name'] = ($dataArr['agent_name']) ? $dataArr['agent_name'] : $rfq_data['agent_name'];
$dataArr['agent_email'] = ($dataArr['agent_email']) ? $dataArr['agent_email'] : $rfq_data['email'];
$dataArr['agent_phone'] = ($dataArr['agent_phone']) ? $dataArr['agent_phone'] : $rfq_data['phone'];
$dataArr['agent_country'] = ($dataArr['agent_country']) ? $dataArr['agent_country'] : $rfq_data['agent_country'];
$dataArr['lead_time'] = ($dataArr['lead_time']) ? $dataArr['lead_time'] : $rfq_data['lead_time'];
$ship_name = mb_substr($session['ship_name'], 0, 3);
?>
<!-- <script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/moment.js"></script>
<script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/datetimepicker.js"></script> -->
<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="panel-body">
        <form class="form-horizontal form-bordered" name="addEditOrderstock" enctype="multipart/form-data" id="addEditOrderstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div>
                <div class="row">
                    <div class="form-group col-sm-6 <?php echo (form_error('order_id')) ? 'has-error':'';?>">
                        <label class="col-sm-12">Order ID <span>*</span></label>
                            <div class="col-sm-12">
                                <?php 
                                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $randomString = '';
                                for ($i = 0; $i < 4; $i++) {
                                    $randomString .= $characters[rand(0, strlen($characters) - 1)];
                                }

                                $dataArr['order_id'] = ($dataArr['order_id']) ? : date('ymd').'-'. $randomString;?>

                                <input type="text"  class="form-control" name="order_id" id="order_id" value="<?php if(!empty($dataArr['order_id'])){echo set_value('order_id',$dataArr['order_id']);}?>">
                                <?php echo form_error('order_id','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12">PO No. <span>*</span></label>
                            <div class="col-sm-12">
                                <?php 
                                  if($session['ship_type']==1){
                                    $sn = getSerialNum(1,'work_order');
                                    $po = 'ONS-'.date('Y').'-'.$sn.'-GA/'.strtoupper($ship_name);
                                  }
                                  else{
                                    $sn = getSerialNum(2,'work_order');
                                    // $po = 'ONS/'.date('Y').'/'.mt_rand(0,9999).'/NGA';
                                    $po = 'ONS-'.date('Y').'-'.$sn.'-NGA/'.$ship_name;
                                   
                                  }

                                  $dataArr['po_no'] = ($dataArr['po_no']) ? : $po;

                                  ?>
                                <input type="text" class="form-control" name="po_no" id="po_no" value="<?php if(!empty($dataArr['po_no'])){echo set_value('po_no',$dataArr['po_no']);}?>">
                                <?php echo form_error('po_no','<p class="error" style="display: inline;">','</p>'); ?>

                            </div>
                    </div>

                    </div>

                      <div class="row">
                        <div class="form-group col-sm-6">
                        <label class="col-sm-12">Delivery Port <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="delivery_port" id="delivery_port" value="<?php if(!empty($dataArr['delivery_port'])){echo set_value('delivery_port',$dataArr['delivery_port']);}?>">
                            <?php echo form_error('delivery_port','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>

                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12">Delivery Country <span>*</span></label>
                            <div  class="col-sm-12">
                                <input  type="text" class="form-control" name="country" id="country" value="<?php if(!empty($dataArr['country'])){echo set_value('country',$dataArr['country']);}?>">
                            <?php echo form_error('country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>

                    </div>
                                  </div>


                <div class="row">
                    
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12">Delivery Date <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control datePicker_editPro" name="delivery_date" id="delivery_date" value="<?php if(!empty($dataArr['delivery_date'])){echo set_value('delivery_date',$dataArr['delivery_date']);}?>">
                            <?php echo form_error('delivery_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                    <div class="form-group col-sm-6 <?php echo (form_error('agent_name')) ? 'has-error':'';?>">
                        <label class="col-sm-12">Agent Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input  type="text" class="form-control" name="agent_name" id="agent_name" value="<?php if(!empty($dataArr['agent_name'])){echo set_value('agent_name',$dataArr['agent_name']);}?>">
                                <?php echo form_error('agent_name','<p class="error" style="display: inline;">','</p>'); ?>

                            </div>
                    </div>
                     </div>
                <div class="row">
                    <div class="form-group col-sm-6 <?php echo (form_error('agent_email')) ? 'has-error':'';?>">
                        <label class="col-sm-12">Agent Email <span>*</span></label>
                            <div class="col-sm-12">
                                <input  type="text" class="form-control" name="agent_email" id="agent_email" value="<?php if(!empty($dataArr['agent_email'])){echo set_value('agent_email',$dataArr['agent_email']);}?>">
                                <?php echo form_error('agent_email','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                    <div class="form-group col-sm-6 <?php echo (form_error('agent_phone')) ? 'has-error':'';?>">
                        <label class="col-sm-12">Agent Phone <span>*</span></label>
                            <div class="col-sm-12">
                                <input  type="text" class="form-control" name="agent_phone" id="agent_phone" value="<?php if(!empty($dataArr['agent_phone'])){echo set_value('agent_phone',$dataArr['agent_phone']);}?>">
                                <?php echo form_error('agent_phone','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>   
                    
                     </div>

                     <div class="row">
                     <div class="form-group col-sm-6 <?php echo (form_error('agent_country')) ? 'has-error':'';?>">
                        <label class="col-sm-12">Agent Country <span>*</span></label>
                            <div class="col-sm-12">
                                <input  type="text" class="form-control" name="agent_country"  value="<?php if(!empty($dataArr['agent_country'])){echo set_value('agent_country',$dataArr['agent_country']);}?>">
                                <?php echo form_error('agent_country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12">Lead Time (Days)<span>*</span></label>
                            <div class="col-sm-12">
                                <input type="number" min="0" max="100" class="form-control" name="lead_time" id="datetimepicker" value="<?php if(!empty($dataArr['lead_time'])){echo set_value('lead_time',$dataArr['lead_time']);}?>">
                                <?php echo form_error('lead_time','<p class="error" style="display: inline;">','</p>'); ?>                               
                            </div>
                    </div>
                     </div>


                    <div class="row">
                    
                     <div class="form-group col-sm-6">
                        <label class="col-sm-12">Delivery Note No <span>*</span><!-- &nbsp;<a href="javascript:void(0)" title="This is a system-generated Delivery Note Number. Should you proceed to generate an delivery note for this particular Purchase Order, the delivery note Number will remain consistent and correspond to the aforementioned sequence."><i class="fa fa-info-circle"></i></a> --> </label>
                            <?php 
                             if($session['ship_type']==1){
                                    $sn1 = getSerialNum(1,'delivey_note');
                                    $dn = 'ONS-'.date('Y').'-'.$sn1.'-GA/'.strtoupper($ship_name);
                                  }
                                  else{
                                    $sn1 = getSerialNum(2,'delivey_note');
                                    $dn = 'ONS-'.date('Y').'-'.$sn1.'-NGA/'.$ship_name;
                                   
                             }
                            // $dataArr['note_no'] = ($dataArr['note_no']) ? : 'ONS/DEL/'.date('Y').'/'.mt_rand(0,9999);
                            $dataArr['note_no'] = ($dataArr['note_no']) ? : $dn;

                            ?>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="note_no" id="note_no" value="<?php if(!empty($dataArr['note_no'])){echo set_value('note_no',$dataArr['note_no']);}?>">
                                <?php echo form_error('note_no','<p class="error" style="display: inline;">','</p>'); ?>

                            </div>
                     </div>
                     <div class="form-group col-sm-6">
                        <label class="col-sm-12">Invoice No <span>*</span> <!-- &nbsp;<a href="javascript:void(0)" title="This is a system-generated Invoice Number. Should you proceed to generate an invoice for this particular Purchase Order, the Invoice Number will remain consistent and correspond to the aforementioned sequence."><i class="fa fa-info-circle"></i></a> --></label>
                            <?php 
                            if($session['ship_type']==1){
                             $sn2 = getSerialNum(1,'invoice');
                             $inv_no = 'INV/ONS/'.date('Y').'/'.$sn2;
                              // $inv_no = 'INV/ONS/CAT/'.date('m').'/'.date('Y').'/'.mt_rand(0,9999);
                            }else{
                             $sn2 = getSerialNum(2,'invoice');
                             $inv_no = 'INV/ONS/'.date('Y').'/'.$sn2; 
                               // $inv_no = 'INV/ONS/'.date('Y').'/'.mt_rand(0,9999);
                            }

                            $dataArr['invoice_no'] = ($dataArr['invoice_no']) ? : $inv_no;
                            ?>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="<?php if(!empty($dataArr['invoice_no'])){echo set_value('invoice_no',$dataArr['invoice_no']);}?>">
                                <?php echo form_error('invoice_no','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>

                    </div>
                      </div>
                  <div class="row mb-20">
                     <div class="form-group col-sm-6">
                        <label class="col-sm-12">Reqsn Date <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control datePicker_editPro" name="reqsn_date" id="reqsn_date" value="<?php if(!empty($dataArr['reqsn_date'])){echo set_value('reqsn_date',$dataArr['reqsn_date']);}?>">
                                 <?php echo form_error('reqsn_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                     <div class="form-group col-sm-6">
                        <label class="col-sm-12">Payment Due Date  <span>*</span> </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control datePicker_editPro" name="due_date" id="due_date" value="<?php if(!empty($dataArr['due_date'])){echo set_value('due_date',$dataArr['due_date']);}?>">
                                 <?php echo form_error('due_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-12">Remark</label>
                            <div class="col-sm-12">
                              <textarea class="form-control" name="remark" id="remark"><?php if(!empty($dataArr['remark'])){echo set_value('remark',$dataArr['remark']);}?></textarea>
                            </div>
                    </div>
                </div>        
            </div>
                <input type="hidden" name="actionType" id="actionType" value="save">
                 <!-- vendor_quote_id  -->
                <input type="hidden" name="id" value="<?php echo $dataArr['id'];?>"> 
                 <!-- ship_order_id  -->
             <!--    <input type="hidden" name="second_id" id="second_id" value="<?php //echo $dataArr['second_id'];?>"> -->
                </div>
             </div>   
    </div>      
</form>
                 <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                             <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal" onclick="deleteSession();">Cancel</a>
                            <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitOrderBasicDetails();">Save & Next</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->

 <script type="text/javascript">
  function submitOrderBasicDetails(){
       var $data = new FormData($('#addEditOrderstock')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/order_basic_details',
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
                    $(".modal-dialog").css("width", '98%');
                }else{
                   showAjaxModel('Create Purchase Order','shipping/order_addition_details','<?php echo $dataArr['id'];?>','','98%',' full-width-model');
                }
            }
        });
} 

 function deleteSession(){
   $.ajax({
        beforeSend: function(){
          $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'shipping/deletePoSession',
        data : {'id':'<?php echo $dataArr['id'];?>'},
        success: function(msg){
          $("#customLoader").hide();
         } 
        });       
 }


 $('.close').click(function(){
    deleteSession();
 })

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
    });
});

$(window).on('beforeunload', function(){
    deleteSession();
});

</script>                   