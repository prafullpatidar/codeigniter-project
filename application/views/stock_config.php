<?php
 // $title = '';
 // if($dataArr['id']=='add_stock'){
 //  $title = 'Opening Stock';
 //  if(!empty($opening_stock)){
 //   $title = 'Update Inventory'; 
 //  }
 // }
 // elseif ($dataArr['id']=='consumed_stock'){
 //  //$title = 'Consumed Stock';
 // }
 // elseif ($dataArr['id']=='rfq'){
 //  $title = 'RFQ';
 // }
 // $edit_stock = checkLabelByTask('edit_stock');
 
 $add_stock_used = checkLabelByTask('add_stock_used');
 $add_closing_stock = checkLabelByTask('add_closing_stock');
?>
<div class="body-content animated fadeIn">
<div class="panel-body rounded-bottom">
    <div class="row">
    <div class="col-md-12">
                    <form class="form-horizontal" name="addEditBasicDetails" enctype="multipart/form-data" id="addEditBasicDetails" method="post">
                    <div class="">
                     <div class="">
                      <?php 
                       if($dataArr['id']=='add_stock'){
                        $dataArr['inventory_type'] = ($dataArr['inventory_type']=='inventory_update') ? 'inventory_update' : 'opening_stock';
                        ?>
                         <div class="form-group" >
                          <div>
                           <div class="pull-left mr-10">
                            <label class="radio-inline">
                             <input checked type="radio" name="inventory_type" value="opening_stock" id="inventory_type" <?php echo ($dataArr['inventory_type'] == 'opening_stock') ? 'checked' : '';?>>Opening Stock
                        </label>
                        </div>
                            <div class="pull-left mr-10">
                               <label class="radio-inline">
                                <input  type="radio" name="inventory_type" value="inventory_update" <?php echo ($dataArr['inventory_type'] == 'inventory_update') ? 'checked' : '';?> id="inventory_type">
                                  Inventory Update
                            </label>
                            </div>
                         </div>
                     </div>
                          <div class="form-group mb-20 <?php echo ($dataArr['inventory_type'] == 'inventory_update') ? 'hide' : ''; ?>" id="stock_dateee">   
                             <label><strong>Date</strong> <span>*</span></label>
                              <div class="col-sm-12">
                               <input type="text" readonly class="form-control datePicker_editPro" name="stock_date" id="stock_date" value="<?php if(!empty($dataArr['stock_date'])){echo convertDate($dataArr['stock_date'],'','d-m-Y');}?>">
                               <?php echo form_error('stock_date','<p class="error" style="display: inline;">','</p>'); ?>
                              </div>
                            </div>

                            <div class="form-group mb-20 <?php echo  ($dataArr['inventory_type'] == 'opening_stock') ? 'hide' : ''; ?>" id="select_delivery">   
                            <label><strong>Delivery Note No.</strong> <span>*</span></label>
                            <select class="form-control" name="delivery_note_id" id="delivery_note_id">
                                <option value="">Select Note No.</option>
                                <?php 
                                 if(!empty($delivery_notes)){
                                    foreach ($delivery_notes as $key => $row) {
                                     ?>
                                     <option <?php echo ($dataArr['delivery_note_id'] == $row->delivery_note_id) ? 'selected="selected"' : '';?> value="<?php echo $row->delivery_note_id;?>"><?php echo $row->note_no;?></option>
                                    <?php 
                                    }
                                 }
                                ?>
                            </select>
                             <?php echo form_error('delivery_note_id','<p class="error" style="color:#ff0000;display: inline;">','</p>');?> 
                             </div>

                           <?php echo (empty($opening_stock)) ? '' : '<span style="font-weight:bold;font-size:12px;">Note: Please ensure all previous inventory/purchase is added before adding the opening stock of the current month.</span>';?>   
                           <div class="form-group mb-10 <?php echo (empty($opening_stock)) ? '' : 'hide'?> " >
                            <label>Data Entry Type <span>*</span></label>
                            <div>
                                <?php $dataArr['entry_type'] = ($dataArr['entry_type']) ? $dataArr['entry_type'] : 'manual';?>
                                 <div class="pull-left mr-10" >
                                    <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="import" id="import" <?php echo ($dataArr['entry_type']=='import') ? 'checked="checked"' : '';?>>
                                        Import
                                    </label>
                                    </div>
                                   <div class="pull-left mr-10" >
                                   <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="manual" id="manual" <?php echo ($dataArr['entry_type']=='manual') ? 'checked="checked"' : '';?>>
                                        Manual
                                    </label>
                                    </div>
                            </div>
                           </div>

                           <div class="row">
                           <div class="form-group hide file">
                           <label class="col-sm-12">Upload <span>*</span></label>
                            <div class="col-sm-12">        
                                <input type="file" name="img" id="img"/>
                                 <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                            </div>
                           </div>
                            <?php
                            }
                            elseif($dataArr['id']=='rfq'){
                             ?>   
                              <div class="form-group mb-20 <?php echo (form_error('no_of_people')) ? 'has-error':'';?>" >
                                <label>Requisition Type <span>*</span></label>
                                <div>
                                    <select name="requisition_type" id="requisition_type" class="form-control">
                                       <option selected <?php echo ($dataArr['requisition_type']=='provisions') ? 'selected="selected"' : '';?> value="provision">Provisions</option>
                                       <option <?php echo ($dataArr['requisition_type']=='bonded_store') ? 'selected="selected"' : '';?> value="bonded_store">Bonded Store</option>
                                       <option <?php echo ($dataArr['requisition_type']=='stores') ? 'selected="selected"' : '';?> value="stores">Stores</option> 
                                       <option <?php echo ($dataArr['requisition_type']=='mineral_water') ? 'selected="selected"' : '';?> value="mineral_water">Mineral Water</option> 
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-10" >
                            <label>Data Entry Type <span>*</span></label>
                            <div>
                                <?php $dataArr['entry_type'] = ($dataArr['entry_type']) ? $dataArr['entry_type'] : 'manual';?>
                                 <div class="pull-left mr-10" >
                                    <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="import" id="import" <?php echo ($dataArr['entry_type']=='import') ? 'checked="checked"' : '';?>>
                                        Import
                                    </label>
                                    </div>
                                   <div class="pull-left mr-10" >
                                   <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="manual" id="manual" <?php echo ($dataArr['entry_type']=='manual') ? 'checked="checked"' : '';?>>
                                        Manual
                                    </label>
                                    </div>
                            </div>
                           </div>

                           <div class="row">
                           <div class="form-group hide file">
                           <label class="col-sm-12">Upload <span>*</span></label>
                            <div class="col-sm-12">        
                                <input type="file" name="img" id="img"/>
                                 <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                            </div>
                           </div>

                          <?php 
                            }
                            elseif($dataArr['id']=='consumed_stock'){
                             ?>   
                               <div class="form-group" >
                                <label class="mb-1"><strong>Stock Type</strong> <span>*</span></label>
                               <div>
                                <?php
                                 if($add_stock_used && $add_closing_stock){ 
                                 ?>           
                                     <div class="pull-left mr-10">
                                       <label class="radio-inline">
                                        <input checked type="radio" name="consumed_type" value="stock_used" id="stock_type">
                                         Stock Used
                                    </label>
                                    </div>
                                    <div class="pull-left mr-10">
                                       <label class="radio-inline">
                                        <input  type="radio" name="consumed_type" value="closing_stock" id="stock_type">
                                        Closing Stock
                                    </label>
                                    </div>
                               <?php }
                               elseif($add_stock_used){
                                ?>
                                    <div class="pull-left mr-10">
                                       <label class="radio-inline">
                                        <input checked type="radio" name="consumed_type" value="stock_used" id="stock_type">
                                         Stock Used
                                    </label>
                                    </div>
                              <?php }
                               elseif($add_closing_stock){
                                ?>  
                                    <div class="pull-left mr-10">
                                       <label class="radio-inline">
                                        <input checked type="radio" name="consumed_type" value="closing_stock" id="stock_type">
                                        Closing Stock
                                    </label>
                                    </div>
                               <?php 
                                }
                               ?>
                             </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3 <?php echo (form_error('year')) ? 'has-error':'';?>" >
                                 <label class="col-sm-12">Year <span>*</span></label>
                                   <div class="col-sm-12">
                                   <select class="form-control" name="year" id="stock_year" onchange="get_months()">
                                    <option value="">Year</option>
                                         <?php
                                           foreach ($years as $row) {
                                            $selected = (date('Y')==$row->year) ? 'selected' : '';
                                          ?>
                                           <option <?php echo $selected;?> value="<?php echo $row->year;?>"><?php echo $row->year;?></option>
                                         <?php 
                                         }
                                       ?>  
                                   </select>
                                      <?php echo form_error('year','<p class="error" style="display: inline;">','</p>'); ?>
                                  </div>
                               </div>

                               <div class="form-group col-sm-3 <?php echo (form_error('stock_date')) ? 'has-error':'';?>" >
                                 <label class="col-sm-12">Month <span>*</span></label>
                                   <div class="col-sm-12">
                                     <select class="form-control" name="month" id="month">
                                    <option value="">Month</option>
                                  </select>
                                  <?php echo form_error('month','<p class="error" style="display: inline;">','</p>'); ?>
                                </div>
                              </div>
                              </div>
                            <div class="form-group mb-10" >
                            <label>Data Entry Type <span>*</span></label>
                            <div>
                                <?php $dataArr['entry_type'] = ($dataArr['entry_type']) ? $dataArr['entry_type'] : 'manual';?>
                                 <div class="pull-left mr-10" >
                                    <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="import" id="import" <?php echo ($dataArr['entry_type']=='import') ? 'checked="checked"' : '';?>>
                                        Import
                                    </label>
                                    </div>
                                   <div class="pull-left mr-10" >
                                   <label class="radio-inline">
                                        <input type="radio" name="entry_type" value="manual" id="manual" <?php echo ($dataArr['entry_type']=='manual') ? 'checked="checked"' : '';?>>
                                        Manual
                                    </label>
                                    </div>
                            </div>
                           </div>

                           <div class="row">
                           <div class="form-group hide file">
                           <label class="col-sm-12">Upload <span>*</span></label>
                            <div class="col-sm-12">        
                                <input type="file" name="img" id="img"/>
                                 <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                            </div>
                           </div>
                            <?php }
                          ?>
                          <div class="form-group col-sm-4 hide file">
                           <label class="col-sm-12">&nbsp;</label>
                            <div class="">
                               <a class="pre_prdct_sample btn btn-success btn-slideright mr-5" type="button" href="<?php echo  base_url().'shipping/downloadSampleXlsx/'.$dataArr['id'].'/'.$ship_id;?>">Download Sample</a>
                               <a style="display: none;" class="cstm_prdct_sample btn btn-success btn-slideright mr-5" type="button" href="<?php echo  base_url().'shipping/downloadCustomProductSampleXlsx/'.$dataArr['id'];?>">Download Sample</a>
                           </button>
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
  $(document).ready(function(){
     get_months();

     $('#requisition_type').change(function(){
        if($(this).val() == 'bonded_store' || $(this).val() == 'stores' || $(this).val() == 'mineral_water'){
           $('.cstm_prdct_sample').show();
           $('.pre_prdct_sample').hide();
        }
        else{
           $('.pre_prdct_sample').show(); 
           $('.cstm_prdct_sample').hide(); 
        }
     })
  }) 

 function submitFormDetails(){
    var $data = new FormData($('#addEditBasicDetails')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/stock_config',
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
                    $(".modal-dialog").css("width", '');
                }
                else{
                    if(obj.type=='opening_stock'){
                        showAjaxModel('OPENING STOCK','shipping/add_stock_details','','','98%',' full-width-model');
                    }
                    else if(obj.type=='second_opening_stock'){
                        showAjaxModel('OPENING STOCK','shipping/add_stock_details/second_opening_stock','','','98%',' full-width-model');
                    }
                    else if(obj.type=='add_inventory'){
                        if(obj.status == 204){
                           alert('You are unauthorized to edit Stock');
                        }
                        else{
                            showAjaxModel('UPDATE INVENTORY','shipping/add_inventory',obj.delivery_note_id,'','98%',' full-width-model');
                        }
                    }
                    else if(obj.type=='consumed_stock'){
                        if(obj.status == '403'){
                            alert('You cannot control stock as your Ship Details are Incomplete! Please Complete it first.');
                        }else{
                         showAjaxModel('Stock Control','shipping/add_consumed_stock',obj.consumed_type,'','98%',' full-width-model');
                         }                        
                    }
                    else if(obj.type=='rfq'){
                        if(obj.status == '403'){
                            alert('You cannot create RFQ as your Ship Details are Incomplete! Please Complete it first.');
                        }else{
                         showAjaxModel('Create RFQ','shipping/add_rfq_details','','','98%',' full-width-model');                        
                        }
                    }
                }
            }
        });

} 


$('input[name="inventory_type"]').change(function(){
 var sttype = $('input[name="inventory_type"]:checked').val();
    if(sttype=='inventory_update'){
      $('#stock_dateee').addClass('hide');  
      $('#select_delivery').removeClass('hide');  
    }else{
      $('#stock_dateee').removeClass('hide');    
      $('#select_delivery').addClass('hide');    
    }
})  


$('input[name="entry_type"]').change(function(){
 var type = $('input[name="entry_type"]:checked').val();
    if(type=='import'){
      $('.file').removeClass('hide');  
    }else{
      $('.file').addClass('hide');    
    }
})  


$(document).ready(function(){
  var enType = $('input[name="entry_type"]:checked').val();
  if(enType=='import'){
      $('.file').removeClass('hide');  
    }else{
      $('.file').addClass('hide');    
    }
})


function get_months(){
  var year = $('#stock_year').val();
  var ship_id = '<?php echo $ship_id;?>';
   if(year){
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
            $('#month').html(obj.data);
        }
     });
   }
 }


 jQuery(document).ready(function(){
   $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
         changeYear:true,
        yearRange: "c-4:c+3"
    });
  });

</script>



