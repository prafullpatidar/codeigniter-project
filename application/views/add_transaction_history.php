<?php
$flag = true;
 if(strtolower($dataArr['status'])=='partially paid' || $dataArr['status']==3){   
   $flag = false;
   $val = 'partially_pay';
 }
 elseif (strtolower($dataArr['status'])=='advance partially paid' || $dataArr['status']==5) {
   $flag = false;
   $val = 'advance_partially';
 }
?>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="add_invoice_amount" enctype="multipart/form-data" id="add_invoice_amount" method="post">
                    <div class="form-body pt-0 pb-0">
                        <div class="row">
                        <?php
                         if($flag){
                        ?>    
                        <div class="form-group col-sm-12">
                           <label class="col-sm-12">Payment Type <span>*</span></label>
                            <div class="col-sm-12">
                             <div class="pull-left mr-10">
                                <label class="radio-inline">
                                <input type="radio" name="transaction_type" value="partially_pay" id="partially" <?php echo ($dataArr['transaction_type']=='partially_pay') ? 'checked="checked"' : '';?> >
                                   Partially Payment
                                </label>
                                </div>
                                   <div class="pull-left mr-10">
                                   <label class="radio-inline">
                                    <input <?php echo (!empty($dataArr['received_amount'])) ? 'disabled' : '';?> type="radio" name="transaction_type" value="full_pay" id="full" <?php echo ($dataArr['transaction_type']=='full_pay') ? 'checked="checked"' : '';?> >
                                        Full Payment & Mark as Paid
                                    </label>
                                  </div>
                                  <div class="pull-left mr-10">
                                   <label class="radio-inline">
                                    <input <?php echo (!empty($dataArr['received_amount'])) ? 'disabled' : '';?> type="radio" name="transaction_type" value="advance_partially" id="advance_partially" <?php echo ($dataArr['transaction_type']=='advance_partially') ? 'checked="checked"' : '';?> >
                                        Advance Partially Payment
                                    </label>
                                  </div>
                                  <div class="pull-left mr-10">
                                   <label class="radio-inline">
                                    <input <?php echo (!empty($dataArr['received_amount'])) ? 'disabled' : '';?> type="radio" name="transaction_type" value="advance_full" id="advance_full" <?php echo ($dataArr['transaction_type']=='advance_full') ? 'checked="checked"' : '';?> >
                                        Advance Full Payment
                                    </label>
                                  </div>
                            </div>
                            <?php echo form_error('transaction_type','<p class="error" style="display: inline;">','</p>'); ?>         
                           </div>
                           <?php
                            }
                            else{
                              ?>  
                              <input type="hidden" name="transaction_type" value="<?php echo $val;?>">
                             <?php }
                           ?>
                       </div>
                       <div class="row">
                           <div class="form-group col-sm-12 amount">
                           <label class="col-sm-12">Amount ($) <span>*</span></label>
                             <div class="col-sm-12">
                                <input type="number" name="t_amount" id="t_amount" class="form-control"  value="<?php echo $dataArr['t_amount'];?>">
                              <?php echo form_error('t_amount','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                           </div>

                           <div class="form-group col-sm-12">
                           <label class="col-sm-12">Description</label>
                             <div class="col-sm-12">
                                <textarea class="form-control" name="description" id="description"><?php echo $dataArr['description'];?></textarea>
                            </div>
                           </div>

                           <div class="form-group col-sm-12">
                           <label class="col-sm-12">Document</label>
                             <div class="col-sm-12">
                                <input type="file" name="img" id="img" class="form-control">
                            </div>
                           </div> 
                         
                        </div>
                    </div>
                     <input type="hidden" name="status" value="<?php echo $dataArr['status'];?>">
                    <input type="hidden" name="id" value="<?php echo $invoice_id;?>">
                    <input type="hidden" name="second_id" value="<?php echo $invoice_type;?>">
                    <input type="hidden" name="actionType" value="save">
                     </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('add_invoice_amount','report/add_transaction_history','80%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
</div> 
<script type="text/javascript">
   // var invoice_amount = '<?php /*if($dataArr['company_invoice_id']){ echo ($dataArr['total_price'] - $dataArr['received_amount']); } else{ echo ($dataArr['amount'] - $dataArr['received_amount']); } */?>';
   // $('#amount').on('keyup',function(){
   //  $('#remain_amount').html('');
   //  var amount = (parseFloat(invoice_amount) - parseFloat($(this).val())); 
   //  if(!isNaN(amount)){    
   //  $('#remain_amount').html('Invoice Remaining Amount - $'+amount);
   //  } 
   // })

 // $(document).ready(function(){
 //  var enType = $('input[name="transaction_type"]:checked').val();
 //  if(enType=='partially_pay'){
 //      $('.amount').removeClass('hide');  
 //    }else{
 //      $('.amount').addClass('hide');    
 //    }
 // }) 

$('input[name="transaction_type"]').change(function(){
  var type = $('input[name="transaction_type"]:checked').val();
    if(type=='partially_pay' || type=='advance_partially'){
      $('.amount').removeClass('hide');  
    }else{
      $('.amount').addClass('hide');    
  }
})    


</script>