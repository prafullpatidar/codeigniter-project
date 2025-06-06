<?php
$user_session_data = getSessionData();
$action = '';
if($active=='edit_profile'){
$action .= 'updateProfile';   
}
elseif($active=='change_password'){
$action .= 'changePassword';   
}
//print_r($dataArr);die;
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
    <h2><span class="icon"><i class="fa fa-user" aria-hidden="true"></i></span><span class="oblc">/</span><?php echo $heading; ?></h2>
</div><!-- /.header-content -->
<?php
$succMsg = $this->session->flashdata('succMsg');
if(isset($succMsg) && !empty($succMsg)){ ?> <div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg;?></div><?php }?>
<!-- Start body content -->
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" name="addEditUserProfile" id="addEditUserProfile" method="post" action="<?php echo base_url().'user/'.$action.'/'.base64_encode($user_session_data->user_id);?>" enctype="multipart/form-data">
            
<!--        <div class="panel panel-tab panel-tab-double rounded shadow">-->

            <div class="panel-tab panel-tab-double panel-tab-horizontal row no-margin no-bg manageProfile">
            
<!--            <div class="panel-heading no-padding">-->
                <div class="panel-heading col-md-12">
                    <div class="panel-body-new1 no-padding">
                    <?php $data['active'] = $active;
                    $this->load->view('user_profile_top_menu',$data);?>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="tab-pane fade inner-all active in col-md-12" id="tab1-1">
              
                   <div class="panel-body-new  no-padding pt-20">
                    <?php if($active == 'edit_profile'){?>
                       <div class="col-sm-12">
                        
                        </div> 
                        <div class="col-sm-12">
                        <div class="form-group col-sm-4 <?php echo (form_error('first_name')) ? 'has-error':'';?>" >
                            <label>First Name <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php if(!empty($dataArr['first_name'])){echo set_value('first_name',$dataArr['first_name']);}?>">
                                <?php echo form_error('first_name'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('user_name')) ? 'has-error':'';?>" >
                            <label>User Name <span>*</span></label>
                            <div>
                                <input readonly type="text" class="form-control" name="user_name" id="user_name" value="<?php if(!empty($dataArr['user_name'])){echo set_value('user_name',$dataArr['user_name']);}?>">
                                <?php echo form_error('user_name'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('last_name')) ? 'has-error':'';?>">
                            <label>Last Name <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php if(!empty($dataArr['last_name'])){echo set_value('last_name',$dataArr['last_name']);}?>">
                                <?php echo form_error('last_name'); ?>
                            </div>
                        </div>
                            
                    </div>
                         <div class="col-sm-12">
                        <div class="form-group col-sm-4 <?php echo (form_error('email')) ? 'has-error':'';?>" >
                            <label>Email <span>*</span></label>
                            <div>
                                <input  type="text" class="form-control phone" name="email" id="email" value="<?php if(!empty($dataArr['email'])){echo set_value('email',$dataArr['email']);}?>">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('phone')) ? 'has-error':'';?>">
                            <label>Phone</label>
                            <div>
                                <input type="text" class="form-control phone" name="phone" id="phone" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                <?php echo form_error('phone'); ?>
                            </div>
                        </div>
                             
                    <div class="form-group col-sm-4">
                            <label>Country</label>
                            <div>
                               <input type="text" class="form-control" name="country"  value="<?php if(!empty($dataArr['country'])){ echo $dataArr['country']; }?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                     <div class="form-group col-sm-4">
                            <label>State</label>
                            <div>
                               <input type="text" class="form-control" name="state"  value="<?php if(!empty($dataArr['state'])){ echo $dataArr['state']; }?>" />
                            </div>
                        </div>
                     <div class="form-group col-sm-4">
                            <label>City</label>
                            <div>
                               <input type="text" class="form-control" name="city"  value="<?php if(!empty($dataArr['city'])){ echo $dataArr['city']; }?>" />
                            </div>
                        </div>
                     <div class="form-group col-sm-4">
                            <label>Zipcode</label>
                            <div >
                               <input type="text" class="form-control" name="zipcode"  value="<?php if(!empty($dataArr['zipcode'])){ echo $dataArr['zipcode']; }?>" />
                            </div>
                        </div>
                    </div>                    
                    <div class="col-sm-12">
                           <div class="form-group col-sm-4 <?php echo (form_error('address')) ? 'has-error':'';?>" >
                            <label>Address</label>
                            <div>
                                <input  type="text" class="form-control" name="address" id="address" value="<?php if(!empty($dataArr['address'])){echo set_value('address',$dataArr['address']);}?>">
                                <?php echo form_error('address'); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                     if($user_session_data->code=='vendor'){
                        ?>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Bank Name</label>
                            <div class="col-sm-12">
                             <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?php if(!empty($dataArr['bank_name'])){ echo set_value('bank_name',$dataArr['bank_name']);}?>">
                            <?php //echo form_error('bank_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Account Holder Name</label>
                            <div class="col-sm-12">
                             <input type="text" name="holder_name" id="holder_name" class="form-control" value="<?php if(!empty($dataArr['holder_name'])){ echo set_value('holder_name',$dataArr['holder_name']);}?>">
                                <?php //echo form_error('holder_name','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Account Number</label>
                            <div class="col-sm-12">
                             <input type="text" name="ac_number" id="ac_number" class="form-control" value="<?php if(!empty($dataArr['ac_number'])){ echo set_value('ac_number',$dataArr['ac_number']);}?>">
                                <?php //echo form_error('ac_number','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">IFSC Code</label>
                            <div class="col-sm-12">
                             <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="<?php if(!empty($dataArr['ifsc_code'])){ echo set_value('ifsc_code',$dataArr['ifsc_code']);}?>">
                                <?php //echo form_error('ifsc_code','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                          <div class="form-group col-sm-4" >
                            <label class="col-sm-12">IBN Number</label>
                            <div class="col-sm-12">
                             <input type="text" name="ibn_number" id="ibn_number" class="form-control" value="<?php if(!empty($dataArr['ibn_number'])){ echo set_value('ibn_number',$dataArr['ibn_number']);}?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Swift Code</label>
                            <div class="col-sm-12">
                             <input type="text" name="swift_code" id="swift_code" class="form-control" value="<?php if(!empty($dataArr['swift_code'])){ echo set_value('swift_code',$dataArr['swift_code']);}?>">
                        
                            </div>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Bank Address</label>
                            <div class="col-sm-12">
                             <input type="text" name="bank_address" id="bank_address" class="form-control" value="<?php if(!empty($dataArr['bank_address'])){ echo set_value('bank_address',$dataArr['bank_address']);}?>">
                        
                            </div>
                        </div>
  
                      <?php 
                      }
                    ?>
                        <div class="col-sm-12"> 
                                <div class="form-body no-padding pt-20">
                                <div class="form-group <?php echo (isset($error_logo_image) || form_error('logo')) ? 'has-error':'';?>">
                                    <label class="col-sm-2 control-label">Profile Pic</label>
                                    <div class="col-sm-2 mb-10">
                                        <?php
                                        if(!empty($dataArr['img_url'])){ ?>   
                                          <img id="imgPreview" src="<?php echo base_url().'uploads/user/'.$dataArr['img_url'];?>" width="50" height="50" style="float:left;">
                                            <input type="hidden" name="image" value="<?php echo base_url().'uploads/'.$dataArr['img_url'];?>">
                                        <?php }else{ 
                                            $logo_img_name = base_url()."assets/images/default_franchisor_logo.png";
                                        ?>
                                            <img id="imgPreview" src="<?php echo $logo_img_name;?>" width="50" height="50" style="float:left;">
                                        <?php } 
                                        ?>
                                    </div>
                                 <input accept="image/*" type="file" id="photo" class="form-control" name="img" >
                                </div>
                                    
                                </div>
                        </div>
                    <?php }elseif ($active=='change_password') { ?>
                                <div class="col-sm-12">
                        <div class="form-group col-sm-4 <?php echo (form_error('password')) ? 'has-error':'';?>">
                            <label>Password <span>*</span></label>
                            <div>
                                <input type="password" class="form-control" name="password" id="password" value="<?php if(!empty($dataArr['password'])){echo set_value('password',$dataArr['password']);}?>">
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>
                     <div class="form-group col-sm-4 <?php echo (form_error('new_password')) ? 'has-error':'';?>">
                            <label>New Password <span>*</span></label>
                            <div>
                                <input type="password" class="form-control phone" name="new_password" id="new_password" value="<?php if(!empty($dataArr['new_password'])){echo set_value('new_password',$dataArr['new_password']);}?>">
                                <?php echo form_error('new_password'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('new_password')) ? 'has-error':'';?>">
                            <label>Confirm password <span>*</span></label>
                            <div>
                                <input type="password" class="form-control" name="c_password" id="c_password" value="<?php if(!empty($dataArr['c_password'])){echo set_value('c_password',$dataArr['c_password']);}?>" >
                                <?php echo form_error('c_password'); ?>
                            </div>
                        </div>

                    </div>
                           <?php } ?>
                   <input type="hidden" name="actionType" value="save">
                <div class="form-footer">
                    <div class="pull-right">
                        <a id="auto_174" class="btn-slideright btn btn-danger mr-5 cancel-btn" href="<?php echo base_url();?>">Cancel</a>
                        <button id="auto_175" type="submit" class="btn btn-success btn-slideright">Submit</button>
                        <input type="hidden" name="actionType" value="save">
                        <input type="hidden" name="id" value="<?php echo $dataArr['user_id'];?>">
                    </div>
                    <div class="clearfix"></div>
                </div><!-- /.form-footer -->
        </div>
        </div>
            </form>
    </div>
</div>
    </div>
<script>
jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        endDate: '-1d'
    });
});

 $(document).ready(()=>{
    $('#photo').change(function(){
        const file = this.files[0];
        console.log(file);
        if (file){
        let reader = new FileReader();
        reader.onload = function(event){
            console.log(event.target.result);
            $('#imgPreview').attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
        }
    });
 });

</script>