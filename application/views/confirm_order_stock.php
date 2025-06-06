<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="addEditOrderstock" enctype="multipart/form-data" id="addEditOrderstock" method="post">
           <div id="abc" class="sip-table full-h" role="grid">

               <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Group</th>
                  <th>Unit</th>
                  <th>Per Man Per Day</th>
                  <th>Last Count Stock QTY </th>
                  <th>Ordered QTY</th>
                  <th>Recom QTY</th>
                  <th>Difference QTY</th>
                </tr>
                </thead>
                    <tbody class="group_data">
                        <?php
                         if(!empty($group_products)){
                            foreach ($group_products as $row) {
                            ?>
                         <tr>
                             <td><?php echo  ucfirst($row->name);?></td>
                             <td>
                                 <?php 
                                   if($row->unit == 1){
                                      $unit = "KG"; 
                                    }else if($row->unit == 2){
                                      $unit = "Liter"; 
                                    }
                                   echo $unit; 
                                 ?>
                             </td>
                             <td><?php echo $row->consumed_qty;?></td>
                             <td>0</td>
                             <td>0</td>
                             <td>0</td>
                             <td>0</td>
                             
                         </tr>
                         <?php
                            } 
                         }
                        ?>
                    </tbody> 
               </table>
               <input type="hidden" name="actionType" value="save">
            </div> 
    </div>
        </form>

</div>
</div>
</div>
 <div class="clearfix"></div>
              <div class="form-footer">
                  <div class="pull-right">
                       <button type="button" id="first_prev" class="btn btn-success btn-slideright mr-5" onclick="showAjaxModel('Create Purchase Order','shipping/order_addition_details','','','98%','full-width-model');">Preview</button>
                       <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                      <button type="button" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360Form('addEditOrderstock','shipping/insert_stock_order','98%','order_request_list')">submit</button>
                  </div>
               <div class="clearfix"></div>
            </div><!-- /.form-footer -->
<!--<script type="text/javascript">
  
  function submitFinalOrderDetails(){
      var $data = new FormData($('#addEditOrderstock')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/insert_stock_order',
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
                   
                   $('#modal-view-datatable').modal('hide');
                   $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                }
            }
        });
    }
</script>