<style type="text/css">
    .p-category .checkbox-inline {
      margin-bottom: 15px;
      margin-right: 15px;
    }

 .p-category .checkbox-inline input[type=checkbox] {
    margin: 0px 10px 0 0px;
    position: relative;
    top: 2px;
    line-height: normal;
}
</style>

<?php
// print_r($dataArr);die();?>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditships" enctype="multipart/form-data" id="addEditships" method="post">
                    <div class="form-body pt-0 pb-0">
                        <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('ship_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Vessel Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="ship_name" id="ship_name" value="<?php if(!empty($dataArr['ship_name'])){echo set_value('ship_name',$dataArr['ship_name']);}?>">
                                <?php echo form_error('ship_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('imo_no')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Imo Number <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="imo_no" id="imo_no" value="<?php if(!empty($dataArr['imo_no'])){echo set_value('imo_no',$dataArr['imo_no']);}?>">
                                <?php echo form_error('imo_no','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <?php
                        $dataArr['shipping_company_id'] = ($dataArr['shipping_company_id']) ? $dataArr['shipping_company_id'] : $second_id;
                         if(empty($ship_id)){
                           ?>
                        <div class="form-group col-sm-4 <?php echo (form_error('shipping_company_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Shipping Company <span>*</span></label>
                            <div class="col-sm-12">
                                 <select class="form-control" name="shipping_company_id" id="company_id">
                                    <option value="">Select</option>
                                   <?php
                                   if ($company) {
                                       foreach ($company as $row) {
                                         $selected = ($dataArr['shipping_company_id'] == $row->shipping_company_id ) ? 'selected' : '';
                                       echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                       }                                       
                                   }
                                   ?>
                                </select>
                                <?php echo form_error('shipping_company_id','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    <?php }else{
                        ?>
                      <input type="hidden" name="shipping_company_id" value="<?php echo $dataArr['shipping_company_id'];?>">
                       <?php 
                       } 
                    ?>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('captain_user_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Vessel Captain</label>
                            <div class="col-sm-12">
                             <select class="form-control" name="captain_user_id" id="ctuser_id" onchange="getNationality(this.value,'captain');">
                            <option value="">Select</option>
                                    <?php
                                    if(!empty($captain)){
                                       foreach ($captain as $row) {
                                          $selected = ($row->user_id == $dataArr['captain_user_id']) ? 'selected': '';
                                          // $captain_disabled = (in_array($row->user_id,$assignedCaptains))?'disabled':'';
                                            
                                         echo  '<option value="'.$row->user_id.'"  '.$selected.'>'.ucwords($row->name).'</option>';

                                             } 
                                          }
                                    ?>
                                </select>
                                <?php echo form_error('captain_user_id','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                          <div class="form-group col-sm-4 <?php echo (form_error('captain_nationality')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Captain Nationality</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="captain_nationality" id="captain_nationality" value="<?php if(!empty($dataArr['captain_nationality'])){echo set_value('captain_nationality',$dataArr['captain_nationality']);}?>">
                                <?php echo form_error('captain_nationality','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('cook_user_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Vessel Cook</label>
                            <div class="col-sm-12">
                                 <select class="form-control" name="cook_user_id" id="cuser_id" onchange="getNationality(this.value,'cook')">
                                    <option value="">Select</option>
                                    <?php 
                                       foreach ($cook as $row1) {
                                        $selected = ($row1->user_id == $dataArr['cook_user_id'])?'selected':'';
                                        // $cook_disabled = (in_array($row1->user_id,$assignedCooks))?'disabled':'';
                                        echo '<option value="'.$row1->user_id.'" '.$selected.' '.$cook_disabled.'>'.ucwords($row1->name).'</option>';
                                     } 
                                    ?>
                                </select>
                                <?php echo form_error('cook_user_id','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('cook_nationality')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Cook Nationality</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="cook_nationality" id="cook_nationality" value="<?php if(!empty($dataArr['cook_nationality'])){echo set_value('cook_nationality',$dataArr['cook_nationality']);}?>">
                                <?php echo form_error('cook_nationality','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12">Number of Crew members <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="total_members" id="total_members" value="<?php if(!empty($dataArr['total_members'])){echo set_value('total_members',$dataArr['total_members']);}?>">
                                <?php echo form_error('total_members','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12">Trading area <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="trading_area" id="trading_area" value="<?php if(!empty($dataArr['trading_area'])){echo set_value('trading_area',$dataArr['trading_area']);}?>">
                                <?php echo form_error('trading_area','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                          <div class="form-group col-sm-4">
                            <label class="col-sm-12">Agreed Victualling Rate <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="victualling_rate" id="victualling_rate" value="<?php if(!empty($dataArr['victualling_rate'])){echo set_value('victualling_rate',$dataArr['victualling_rate']);}?>">
                                <?php echo form_error('victualling_rate','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12">Type <span>*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="ship_type" id="ship_type">
                                    <option value="">Select Type</option>
                                    <option  <?php echo ($dataArr['ship_type']==1) ? 'selected="selected"' : '';?> value="1" >Contracted</option>
                                    <option <?php echo ($dataArr['ship_type']==2) ? 'selected="selected"' : '';?> value="2" >Non Contracted</option>
                                </select>
                                <?php echo form_error('ship_type','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                          <div class="form-group prdctCat col-sm-12">
                           <div class="col-sm-12"> <h5 class="popTitle col-sm-12 p-0" style="padding:0;color:#296A7E;"><strong>Select Product Categories</strong><span>*</span></h5>
                            <label class="checkbox-inline mb-20">Select All <input type="checkbox" class="" name="check_all_port" id="check_all_port" style="width:auto;margin-left: 10px;"></label></div>
                            
                            <div class="col-sm-12 p-category">
                                <?php 
                                 if(!empty($product_categories)){
                                    foreach($product_categories as $pc){
                                        $checked = '';
                                       if(isset($dataArr['ship_id'])){
                                            if(isset($dataArr['product_categories'])){
                                              $checked = (in_array($pc->product_category_id,explode(',',$dataArr['product_categories'])))?'checked':'';
                                            }else{
                                                //$checked = (in_array($pc->product_category_id,explode(',',$dataArr['product_categories'])))?'checked':'';
                                                $checked = (in_array($pc->product_category_id,$dataArr['product_category']))?'checked':'';
                                            }
                                        }else{
                                            $checked = (isset($dataArr['product_category']) && in_array($pc->product_category_id,$dataArr['product_category']))?'checked':'';
                                        }
                                        ?>
                                        <label class="checkbox-inline"><input type="checkbox" class="<?php echo ($pc->code=='misc_items') ? 'misc_items' : 'check_all_td'?>" name="product_category[]" id="product_category" value="<?php echo $pc->product_category_id; ?>" <?php echo $checked;?> ><?php echo $pc->category_name;?></label>
                                        
                                    <?php }
                                     echo form_error('product_category[]','<p class="error" style="display: inline;">','</p>'); 
                                 }
                                ?>
                            </div>
                        </div>

                        <div class="row misc_product hide">
                          <div class="form-group prdctCat col-sm-12">
                           <div class="col-sm-12"> <h5 class="popTitle col-sm-12 p-0" style="padding:0;color:#296A7E;"><strong>Select Product</strong><span>*</span></h5>
                            <label class="checkbox-inline mb-20">Select All <input type="checkbox" class="" name="check_all_product" id="check_all_product" style="width:auto;margin-left: 10px;"></label></div>
                            
                            <div class="col-sm-12 p-category">
                                <?php                              
                                 $checked = '';
                                 
                                  if(!empty($misc_items)){
                                    foreach($misc_items as $p){
                                         if(isset($dataArr['ship_id'])){
                                            if(isset($dataArr['product_ids'])){
                                              $checked = (in_array($p->product_id,explode(',',$dataArr['product_ids'])))?'checked':'';
                                            }else{
                                                $checked = (in_array($p->product_id,$dataArr['product']))?'checked':'';
                                            }
                                          }else{
                                            $checked = (isset($dataArr['product']) && in_array($p->product_id,$dataArr['product']))?'checked':'';
                                           }
                                        ?>
                                        <label class="checkbox-inline"><input type="checkbox" class="check_singel_product" name="product[]"  id="product" value="<?php echo $p->product_id; ?>" <?php echo $checked;?> ><?php echo ucwords($p->product_name);?></label>
                                        
                                    <?php }
                                     echo form_error('product[]','<p class="error" style="display: inline;">','</p>'); 
                                 }
                                ?>
                            </div>
                        </div>
                         
                    </div>
                        <input type="hidden" name="unlink" id="changeStaff" value="No">
                        <input type="hidden" name="id" value="<?php echo $ship_id;?>" >
                         <input type="hidden" name="second_id" value="<?php echo $second_id;?>" >
                        <input type="hidden" value="save" name="actionType">
                        <input type="hidden" name="product_validation" id="product_validation" value="">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitShipForm()" class="btn btn-success btn-slideright mr-5">Submit</button>
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
//    var shipId = '<?php //echo (isset($dataArr['ship_id'])) ? $dataArr['ship_id'] : '' ;?>';

    $('#company_id').change(function(){
       var shipping_company_id = $('#company_id').val();
        $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'user/getUserByCompanyId',
            data: {'shipping_company_id':shipping_company_id},
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#ctuser_id').html(obj.c_data);
                $('#cuser_id').html(obj.ct_data);
                $('#captain_nationality').val('');
                $('#cook_nationality').val('');
            }
        }); 
    })


  $("#check_all_port").click(function(){
     $("input.check_all_td").prop('checked', $(this).prop('checked'));
  });

  $("#check_all_product").click(function(){
     $("input.check_singel_product").prop('checked', $(this).prop('checked'));
  });

   $(document).ready(()=>{
    if($('.misc_items').prop('checked')){
        $('.misc_product').removeClass('hide');
        $('#product_validation').val('1');
     }
   //   $('.check_all_td:checked').each(function(){
   //      const code = $(this).data('id');
   //      if(code=='misc_items'){
   //        $('.misc_product').removeClass('hide');
   //        $('#product_validation').val('1');                
   //      }
   //   })
   })
   
   $('.misc_items').click(function(e){
     $('.misc_product').addClass('hide');
     $('#product_validation').val('');
     if($('.misc_items').prop('checked')){
        $('.misc_product').removeClass('hide');
        $('#product_validation').val('1');
     }
   })


   // $('.check_all_td').click(()=>{
   //  $('.misc_product').addClass('hide');
   //  $('#product_validation').val('');
   //   $('.check_all_td:checked').each(function(){
   //      const code = $(this).data('id');
   //      if(code=='misc_items'){
   //        $('.misc_product').removeClass('hide');
   //        $('#product_validation').val('1');        
   //      }
   //   })
   // })

 function submitShipForm(){
       var $data = new FormData($('#addEditships')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/add_edit_ships',
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
                    $(".modal-dialog").css("width", "70%");
                }
                else if(obj.status==200){
                 var html = '<div class="confirm_msg">'
                  if(typeof(obj.captainmsg)!='undefined'){
                   html+='<p class="text-center"><strong>Note:</strong></p><span><strong>'+obj.captainmsg+'</strong></span><br>';  
                  }
                  if(typeof(obj.cookmsg)!='undefined'){
                   html+='<span><strong>'+obj.cookmsg+'</strong></span>';  
                  }        
                  html+='<p class="mt-15">Are you sure you want to unlink from the current ship and link to this ship ?</p></div>';       
                 bootbox.dialog({
                        message: html,
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
                                  $('#changeStaff').val('Yes');
                                  submitShipForm();
                                   // submitShipForm('addEditships','shipping/add_edit_ships','70%')
                                }
                            }

                        }
                    });  
                } 
                else{    
                    location.reload();
                }
            }
        });
   }

 // function getCookNationality(cook_user_id){
 //   if(cook_user_id){

 //   }
 //   else{
 //   }
 //  }

 function getNationality(user_id,type){
   if(user_id){
     $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'user/getNationality',
        data: {'user_id':user_id},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            if(type=='captain'){
              $('#captain_nationality').val(obj.name);
            }
            else if(type=='cook'){           
              $('#cook_nationality').val(obj.name);
            }
        }
     });  
   }
   else{
      if(type=='captain'){
         $('#captain_nationality').val('');
        }
      else if(type=='cook'){           
          $('#cook_nationality').val('');
        }
   }
 }
</script>