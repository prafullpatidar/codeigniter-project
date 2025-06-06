<?php //print_r($dataArr);die; ?>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditvendor" enctype="multipart/form-data" id="addEditvendor" method="post">
                    <div class="form-body pt-0 pb-0">
                        <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('first_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">First Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php if(!empty($dataArr['first_name'])){echo set_value('first_name',$dataArr['first_name']);}?>">
                                <?php echo form_error('first_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('last_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Last Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php if(!empty($dataArr['last_name'])){echo set_value('last_name',$dataArr['last_name']);}?>">
                                <?php echo form_error('last_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('email')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Email <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($dataArr['email'])){echo set_value('email',$dataArr['email']);}?>">
                                <?php echo form_error('email','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                   if(empty($dataArr['user_id'])){
                    ?>
                    <div class="row">
                         <div class="form-group col-sm-4 <?php echo (form_error('user_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">User Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="user_name" id="user_name" value="<?php if(!empty($dataArr['user_name'])){echo set_value('user_name',$dataArr['user_name']);}?>">
                                <?php echo form_error('user_name'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4 <?php echo (form_error('password')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Password <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="password" id="password" value="<?php if(!empty($dataArr['password'])){echo set_value('password',$dataArr['password']);}?>">
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('c_password')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Confirm Password <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="c_password" id="c_password" value="<?php if(!empty($dataArr['c_password'])){echo set_value('c_password',$dataArr['c_password']);}?>">
                                <?php echo form_error('c_password'); ?>
                            </div>
                        </div>
                       </div>
                       <?php 
                        }
                       ?>
                        <div class="row">
                         <div class="form-group col-sm-4 <?php echo (form_error('phone')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Phone</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="phone" id="phone" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                <?php echo form_error('phone','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12">Country</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="country"  value="<?php if(!empty($dataArr['country'])){ echo $dataArr['country']; }?>" />
                            </div>
                         </div>
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12">State</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="state"  value="<?php if(!empty($dataArr['state'])){ echo $dataArr['state']; }?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12">City</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="city"  value="<?php if(!empty($dataArr['city'])){ echo $dataArr['city']; }?>" />
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12">Zipcode</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="zipcode"  value="<?php if(!empty($dataArr['zipcode'])){ echo $dataArr['zipcode']; }?>" />
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('address')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 ">Address</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="address" ><?php if(!empty($dataArr['address'])){echo set_value('date',$dataArr['address']);}?></textarea>
                                <?php echo form_error('address','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('currency')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 ">Currency <span>*</span></label>
                            <div class="col-sm-12">
                            <select name="currency" id="currency" class="form-control">
                                <option value="">Select Currency</option>
                                <!-- <option value="1" <?php echo ($dataArr['currency']==1) ? 'selected' : '';?>>Euro</option> -->
                                <option value="2" <?php echo ($dataArr['currency']==2) ? 'selected' : '';?>>USD</option>
                                <!-- <option value="3" <?php echo ($dataArr['currency']==3) ? 'selected' : '';?>>SGD</option> -->
                            </select>
                                <?php echo form_error('currency','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                             <div class="form-group col-sm-4 <?php echo (form_error('payment_term')) ? 'has-error':'';?>">
                            <label class="col-sm-12">Payment Term <span>*</span></label>
                            <div class="col-sm-12">
                             <input type="text" name="payment_term" id="payment_term" class="form-control" value="<?php if(!empty($dataArr['payment_term'])){ echo set_value('payment_term',$dataArr['payment_term']);}?>">
                                <?php echo form_error('payment_term','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                            <div class="form-group col-sm-4" >
                            <label class="col-sm-12 ">Profile</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" name="img" id="photo" accept="image/*">
                                <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        </div>
                        <div class="row">
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
                        </div>
                       <div class="row">
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
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12">Bank Address</label>
                            <div class="col-sm-12">
                             <input type="text" name="bank_address" id="bank_address" class="form-control" value="<?php if(!empty($dataArr['bank_address'])){ echo set_value('bank_address',$dataArr['bank_address']);}?>">
                        
                            </div>
                        </div>
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12 ">Upload a PDF <span>*</span></label>
                            <div class="col-sm-12">
                                <input accept="application/pdf" type="file" class="form-control" name="vendor_pdf" id="photo" value="">
                                <?php echo form_error('vendor_pdf','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div> 
                        <div class="form-group col-sm-4" >
                            <label class="col-sm-12 ">Profile Picture</label>
                            <div class="col-sm-12">
                                <?php echo (!empty($dataArr['img_url'])?'<img id="imgPreview" src="'. base_url().'uploads/user/'.$dataArr["img_url"].'" height="50" width="50">':'<img  id="imgPreview" src="'.base_url().'uploads/customer.png" height="50" width="50" alt="No Profile Picture">');?>
                            </div>
                        </div> 
                </div>
                    <input type="hidden" value="<?php echo (isset($dataArr['user_id'])) ? $dataArr['user_id'] : '' ;?>" name="id">
                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditvendor','vendor/add_edit_vendor','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
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