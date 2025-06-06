<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="edit_current_stock" enctype="multipart/form-data" id="edit_current_stock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="tableResponsive" class="sip-table" role="grid">
               <table class="table"  border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Item No.</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>QTY</th>
                  <th>Type</th>
                  <th>Adjust QTY</th>    
                  <th>Reason</th>    
                  </tr>
                </thead>
                  <tbody class="item_data">
                    <?php
                      $returnArr = ''; 
                     if(!empty($productArr)){
                      foreach($productArr as $category => $products){
                        $category = explode('|',$category);
                        $returnArr .= '<tr class="parent_row">
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category[1].'</td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>'; 
                                                // <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><a href="javascript:void(0)" onclick="addNewProduct(\''.$category[0].'\')"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
                        foreach($products as $row){
                             $returnArr .= '<tr class="child_row">';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$row->item_no.'</td>';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($row->product_name).'</td>';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($row->unit).'</td>';
                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($row->available_stock).'</td>';
                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">';
                            $returnArr .= '<select name="type_'.$row->product_id.'"  id="type_'.$row->product_id.'" class="form-control" onchange="showFields(this.value,\''.$row->product_id.'\')">
                                  <option value="">Select Type</option>
                                  <option '.(($dataArr['type_'.$row->product_id]=='positive') ? 'selected' : '').' value="positive">Positive</option>
                                  <option '.(($dataArr['type_'.$row->product_id]=='negative') ? 'selected' : '').' value="negative">Negative</option>
                                  </select>';
                            $returnArr .='</td>'; 
                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity" data-quantity="1" name="qty_'.$row->product_id.'" id="qty_'.$row->product_id.'" '.((!empty($dataArr['type_'.$row->product_id]) && $dataArr['type_'.$row->product_id]!=='wrong_item') ? '' : 'style="display:none;"').' value="'.$dataArr['qty_'.$row->product_id].'" >'.form_error('qty_'.$row->product_id,'<p class="error">','</p>').'</td>';
                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity" data-quantity="1" id="reason_'.$row->product_id.'" name="reason_'.$row->product_id.'" '.((!empty($dataArr['type_'.$row->product_id])) ? '' : 'style="display:none;"').' value="'.$dataArr['reason_'.$row->product_id].'">'.form_error('reason_'.$row->product_id,'<p class="error">','</p>').'</td></tr>';

                           // $returnArr .='<td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                        } 
                      }
                     }
                     else{
                         $returnArr.='<tr><td colspan="7" align="center">No Data Available</td></tr>';
                     }

                     echo $returnArr;
                    ?>
                  </tbody>
                </table>
                </div>
                <input type="hidden" name="update_flag" id="update_flag" value="0">                 
                <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button disabled="disabled" type="button" id="submitButton" onclick="submitForm();" class="btn btn-success btn-slideright mr-5">Submit</button>
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
    
    function submitForm(){
      var $data = new FormData($('#edit_current_stock')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/adjustCurrentStock',
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
                    $(".modal-dialog").css("width",'98%');
                }else if(obj.status=='200'){

                     bootbox.dialog({
                            message: 'Are you sure want to update your current stock ?',
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
                                         $('#update_flag').val(1);
                                      submitAjax360Form('edit_current_stock','shipping/adjustCurrentStock','98%','getCompanySummury')
                                    }
                                }

                            }
                        });
                }
        }
       });     
    }

   $(document).ready(function () {
    // Store the initial form data
    var initialFormData = $('#edit_current_stock').serialize();

    // Check for changes on form input
    $('#edit_current_stock :input').on('input', function() {
        // If the form data has changed, enable the submit button
        if ($('#edit_current_stock').serialize() !== initialFormData) {
            $('#submitButton').prop('disabled', false);
        } else {
            // If the form data is the same as initial, disable the submit button
            $('#submitButton').prop('disabled', true);
        }
    });
}); 

 function showFields(val,id){
  if(val==''){
    $('#reason_'+id).hide();
    $('#qty_'+id).hide();
    $('#reason_'+id).val('');
    $('#qty_'+id).val('');
    
  }
  else if(val=='wrong_item'){
    $('#reason_'+id).show();
    $('#qty_'+id).hide();
    $('#qty_'+id).val('');
  }
  else{
    $('#reason_'+id).show();
    $('#qty_'+id).show();
  }
 }  

 function addNewProduct(category_id=''){
   $.ajax({
    beforeSend: function(){
        $("#customLoader").show();
    },
    type: "POST",
    url: base_url + 'product/addProductOnInventory',
    cache:false,
    data: {'category_id':category_id},
    success: function(msg){
      $("#customLoader").hide();
      var obj = jQuery.parseJSON(msg);  
      if(obj.status=='100'){
        $('#modal-view-datatable').modal('show');
        $('#modal_content').html(obj.data);
        $(".modal-dialog").css("width",'98%');
      }
      else{

      }  
    }
  });
 } 

</script>