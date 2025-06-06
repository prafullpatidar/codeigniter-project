<div class="body-content animated fadeIn">
    <div class="row">
    <div class="">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="invoice_form" enctype="multipart/form-data" id="invoice_form" method="post">
                    <div class="">
                        <?php
                         if(!empty($second_id)){
                            ?>
                            <span><strong>Note : There was an error on your uploaded document. please reupload it.</strong></span>
                         <?php }
                        ?>
                        <div class="row1">
                        <?php 
                         if(!empty($type)){
                        ?>    
                        <div class=" form-group col-sm-6 <?php echo (form_error('vendor_id')) ? 'has-error':'';?>" >
                            <label>Purchase Order <span>*</span></label>
                            <div>
                                <select name="work_order_id" id="work_order_id" class="form-control">
                                  <option value="">Select PO</option>  
                                  <?php 
                                      if(!empty($work_order)){
                                       foreach ($work_order as $row) {
                                        ?>
                                         <option <?php echo ($dataArr['work_order_id'] == $row->work_order_id) ? 'selected' : '';?> value="<?php echo $row->work_order_id;?>"><?php echo ucwords($row->po_no).'('.ucwords($row->ship_name).')';?></option>
                                        <?php 
                                        } 
                                      }
                                   ?>
                                </select>
                                <?php echo form_error('work_order_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                            </div>
                          </div>
                      <?php } ?>
                            <div class="form-group col-sm-6 <?php echo (form_error('invoice_no')) ? 'has-error':'';?>" >
                            <label>Invoice No <span>*</span></label>
                            <div>
                             <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="<?php echo $dataArr['invoice_no'];?>">
                              <?php echo form_error('invoice_no','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                            </div>
                           </div>
                             <div class="form-group col-sm-6 <?php echo (form_error('amount')) ? 'has-error':'';?>" >
                            <label>Amount($) <span>*</span></label>
                            <div>
                             <input type="number" name="amount" id="amount" class="form-control" value="<?php echo $dataArr['amount'];?>">
                              <?php echo form_error('amount','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                            </div>
                           </div>
                           <div class="form-group col-sm-6 <?php echo (form_error('due_date')) ? 'has-error':'';?>" >
                            <label>Due Date <span>*</span></label>
                            <div>
                             <input type="text" name="due_date" class="form-control datePicker_editPro due_date" value="<?php echo $dataArr['due_date'];?>">
                              <?php echo form_error('due_date','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                            </div>
                           </div>
                           <div class="form-group col-sm-6 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label>Upload <span>*</span></label>
                            <div>
                              <input type="file" name="img" id="img" class="form-control"> 
                              <?php echo form_error('img','<p class="error" style="color:#ff0000;display: inline;">','</p>')?>
                              <?php 
                                if(!empty($dataArr['document_url'])){
                                    ?>
                                   <a target="_blank" href="<?php echo base_url().'uploads/vendor_pdf/'.$dataArr['document_url']?>"><?php echo $dataArr['document_url'];?></a> ;
                                <?php }
                              ?> 
                            </div>
                           </div>
                        </div>
                     <input type="hidden" name="type" value="<?php echo $type;?>">   
                     <input type="hidden" name="id" value="<?php echo $work_order_id;?>" >
                     <input type="hidden" name="second_id" value="<?php echo $second_id;?>">
                     <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('invoice_form','vendor/upload_vendor_invoice','50%')" class="btn btn-success btn-slideright">Submit</button>
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
    jQuery(document).ready(function(){
    $('.due_date').datepicker({
        format: 'dd-mm-yyyy',
    });
});
</script>