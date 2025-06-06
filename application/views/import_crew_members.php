<?php
?>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditBasicDetails" enctype="multipart/form-data" id="addEditBasicDetails" method="post">
                    <div class="form-body">
                        <div class="row">
                          <div class="form-group col-sm-12 file">
                           <label class="col-sm-12">Date <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" name="entry_date" id="img" class="form-control datePicker_editPro" />
                                 <?php echo form_error('entry_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                            </div>
                          <div class="form-group col-sm-12 file">
                           <label class="col-sm-12">Upload <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="file" name="img" id="img" class="form-control" />
                                 <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                            </div> 
                            <div class="form-group col-sm-12 file">
                           <label class="col-sm-12">&nbsp;</label>
                            <div class="col-sm-12">
                               <a class="btn btn-success btn-slideright mr-5" style="margin-top: 5px;" type="button" href="<?php echo  base_url().'shipping/downloadCrewSampleXlsx/'.$dataArr['id'];?>">Download Sample</a></button>
                            </div>
                            </div>
                          <input type="hidden" name="id" id="type" value="<?php echo $dataArr['id'];?>">
                          <input type="hidden" name="second_id" value="<?php echo $dataArr['second_id'];?>">
                          <input type="hidden" name="actionType" value="save">
                           <div class="clearfix"></div>
                       <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitFormDetails()" class="btn btn-success btn-slideright mr-5">Ok</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                    </div>
                </form>
            </div>
        </div>

<script type="text/javascript">

function submitFormDetails(){
    var $data = new FormData($('#addEditBasicDetails')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'crew/import_crew_members',
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
                    // $(".modal-dialog").css("width", '70%');
                }
                else{
                        showAjaxModel('Preview Crew Members','crew/preview_crew_members','<?php echo $dataArr['id'];?>','','98%',' full-width-model');
                }
            }
        });

} 


 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        changeYear: true,
    });
}); 

</script>
