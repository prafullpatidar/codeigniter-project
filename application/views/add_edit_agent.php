<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditAgent" enctype="multipart/form-data" id="addEditAgent" method="post">
                    <div class="form-body">
                        <div class="row">
                        <div class="form-group col-sm-12 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Name of Agent <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="name" id="name" value="<?php if(!empty($dataArr['name'])){echo set_value('name',$dataArr['name']);}?>">
                                <?php echo form_error('name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('email')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Agent Email <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control " name="email" id="email" value="<?php if(!empty($dataArr['email'])){ echo set_value('email',$dataArr['email']);;}?>">
                                <?php echo form_error('email','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                          <div class="form-group col-sm-12 <?php echo (form_error('phone')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Agent Phone <span>*</span></label>
                            <div class="col-sm-12">
                                <div class="country-dropdown">
                                <select id="country_code" name="country_code">
                                 <?php 
                                  // $country_code = $this->config->item('phone_code');
                                  // for ($i=0; $i < count($country_code); $i++) { 
                                   ?>
                                   <!-- <option <?php //echo ($dataArr['country_code']==$country_code[$i]['code']) ? 'selected' : '';?> value="<?php //echo $country_code[$i]['code'];?>"><?php //echo $country_code[$i]['lable'];?></option> -->
                                   <?php 
                                     // }

                                   if(!empty($country)){
                                    foreach ($country as $row) {
                                      ?>
                                      <option <?php echo ($dataArr['country_code']==$row->phone_code) ? 'selected' : '';?> value="<?php echo $row->phone_code;?>"><?php echo ucfirst($row->name).' ('.$row->phone_code.')'?></option>
                                     <?php }
                                   }
                                 ?>   
                                </select>
                                <input type="number" class="form-control" name="phone" id="phone" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                    </div>
                                <?php echo form_error('phone','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                           <div class="form-group col-sm-12 <?php echo (form_error('agency')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Agency Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="agency" id="agency" value="<?php if(!empty($dataArr['agency'])){echo set_value('agency',$dataArr['agency']);}?>">
                                <?php echo form_error('agency','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('incharge_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Person Incharge Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="incharge_name" id="incharge_name" value="<?php if(!empty($dataArr['incharge_name'])){echo set_value('incharge_name',$dataArr['incharge_name']);}?>">
                                <?php echo form_error('incharge_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('port_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Port Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="port_name" id="port_name" value="<?php if(!empty($dataArr['port_name'])){echo set_value('port_name',$dataArr['port_name']);}?>">
                                <?php echo form_error('port_name','<p class="error" style="display: inline;">','</p>'); ?>
                             <strong>use comma(,) for multiple ports</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 <?php echo (form_error('country')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Country <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="country" id="country" value="<?php if(!empty($dataArr['country'])){echo set_value('country',$dataArr['country']);}?>">
                                <?php echo form_error('country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" value="<?php echo $agent_id;?>" name="id">

                        <input type="hidden" value="<?php echo $second_id;?>" name="second_id">
                        
                        <input type="hidden" value="save" name="actionType">
 
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <?php
                            if(empty($second_id)){
                                ?>
                                    <button type="button" onclick="submitMoldelForm('addEditAgent','user/addeditagent','50%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                            <?php } 
                            else{
                                ?>
                                    <button type="button" onclick="submitForm()" class="btn btn-success btn-slideright mr-5">Submit</button>

                            <?php }
                            ?>
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
        var $data = new FormData($('#addEditAgent')[0]);
        $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'user/addeditagent',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='100'){
                    $('#modal-view-datatable').modal('show');
                    $('#modal_content').html(obj.data);
                    $(".modal-dialog").css("width", '50%');
                }else{
                    showAjaxModel('Add Next Port','shipping/add_edit_port/'+obj.agent_id,'','','50%')
                }
            }
        });
        return false;
    }

</script>