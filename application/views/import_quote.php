<div class="body-content animated fadeIn">
    <div class="row">
    <div class="">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="quote_form" enctype="multipart/form-data" id="quote_form" method="post">
                    <div class="">
                        <div class="row1">
                           <div class="form-group col-sm-12 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label>Upload <span>*</span></label>
                            <div>
                              <input accept=".csv,.xls,.xlsx" type="file" name="img" id="img" class="form-control"> 
                                <?php echo form_error('img','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                            </div>
                            <br>
                            <div class="sample_button">
                              <a href="<?php echo base_url().'shipping/download_for_quote_xls/'.$dataArr['id'].'/for_quote';?>" class="btn btn-success btn-slideright">Download sample</a>  
                            </div>
                           </div>
                        </div>
                      <input type="hidden" value="<?php echo (isset($dataArr['id'])) ? $dataArr['id'] : '';?>" name="id">
                      <input type="hidden" value="<?php echo (isset($dataArr['second_id'])) ? $dataArr['second_id'] : '';?>" name="second_id">

                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitImportForm('quote_form','shipping/import_vendor_quatation','50%')" class="btn btn-success btn-slideright">Submit</button>
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
   function submitImportForm(){
      var $data = new FormData($('#quote_form')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'vendor/import_quote',
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
                    $(".modal-dialog").css("width", '50%');
                }else{
                   showAjaxModel('Preview Quotation','shipping/preview_import_quote','','','98%','full-width-model');
                }
            }
        });
   } 
</script>
