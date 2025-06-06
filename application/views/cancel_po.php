<div class="animated fadeIn">
  <div class="row">
    <div class="col-md-12">
      <form class="form-horizontal form-bordered" name="update_work_order_status" enctype="multipart/form-data" id="update_work_order_status" method="post">
        <div class="no-padding rounded-bottom">
        <div class="form-body mt-2">
           <div class="row">
              <div class="col-md-12">
              <div class="pull-left mr-10">
                <label class="radio-inline">
                 <input checked type="radio" name="status" value="5" id="status">
                  Temprory Cancel
                 </label>
                  </div>
                    <div class="pull-left mr-10">
                       <label class="radio-inline">
                        <input type="radio" name="status" value="6" id="status">
                        Permanent Cancel
                    </label>
                </div>
              </div>
             </div>
           <div class="row">
             <div class="col-md-12  mt-2">
             <div class="form-group">
              <lable>Remark <span class="c-red">*</span></lable>
                <div>
                 <textarea class="form-control" name="cancel_remark" id="cancel_remark"></textarea>
                 <?php echo form_error('cancel_remark','<p class="c-red">','</p>');?>
             </div>
          </div>
             </div>
        </div>
       </div>
       <input type="hidden" name="actionType" id="actionType" value="save">
         <input type="hidden" name="id" value="<?php echo $work_order_id;?>">  
         <input type="hidden" name="second_id" value="<?php echo $ship_order_id;?>"> 
         <input type="hidden" name="confirmed" id="confirmed" value="0">   
         </form>
         </div>
         <div class="form-footer">
           <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" class="btn btn-success btn-slideright mr-5" onclick='submitCancelPoForm()'>Save</button>
           </div>
         </div>
 <script type="text/javascript">
    
   function submitCancelPoForm(){
     var $data = new FormData($('#update_work_order_status')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/cancelPO',
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
                    $(".modal-dialog").css("width",'50%');
                }else if(obj.status=='300'){
                     var msg = '';
                     if(obj.code==5){
                       msg = 'Note: Sure, Do you want to cancel the purchase order? If Temperory cancels the PO, you can further update the RFQ details.'
                     }
                     else{
                       msg = 'Note: Sure, Do you want to cancel the purchase order permanently? In case of Permanent cancellation of PO, all the previous data of RFQ will be lost.'
                     }
                     bootbox.dialog({
                            message: msg,
                            title: "Confirmation",
                            className: "modal-primary",
                            buttons: {
                                danger: {
                                    label: "No",
                                    className: "btn-danger btn-slideright mLeft",
                                    callback: function () {}
                                },
                                success: {
                                    label: "Yes",
                                    className: "btn-success btn-slideright",
                                    callback: function () {
                                         $('#confirmed').val(1);
                                      submitAjax360Form('update_work_order_status','shipping/cancelPO','50%','work_order')
                                    }
                                }

                            }
                        });
                }
            }
       });
   }     
 </script>        