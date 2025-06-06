<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditUser" enctype="multipart/form-data" id="addEditUser" method="post">
                    <div class="form-body">
                        <div class="row">
                            <div class="form-group col-sm-4 <?php echo (form_error('first_name')) ? 'has-error':'';?>" >
                                <label class="col-sm-12">First Name <span>*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php if(!empty($dataArr['first_name'])){echo set_value('first_name',$dataArr['first_name']);}?>">
                                    <?php echo form_error('first_name','<p class="error" style="display: inline;">','</p>'); ?>
                                </div>
                            </div>
                            <div style="border-top:none;"  class="form-group col-sm-4 <?php echo (form_error('last_name')) ? 'has-error':'';?>" >
                                <label class="col-sm-12 ">Last Name <span>*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="<?php if(!empty($dataArr['last_name'])){echo set_value('last_name',$dataArr['last_name']);}?>">
                                    <?php echo form_error('last_name','<p class="error" style="display: inline;">','</p>'); ?>
                                </div>
                            </div>
                              <div class="form-group col-sm-4 <?php echo (form_error('passport_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Passport No <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="passport_id" id="passport_id" value="<?php if(!empty($dataArr['passport_id'])){echo set_value('passport_id',$dataArr['passport_id']);}?>">
                                <?php echo form_error('passport_id'); ?>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4 <?php echo (form_error('user_name')) ? 'has-error':'';?>" >
                                <label class="col-sm-12 ">User Name <span>*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" readonly name="user_name" id="user_name" value="<?php if(!empty($dataArr['user_name'])){echo set_value('user_name',$dataArr['user_name']);}?>">
                                    <?php echo form_error('user_name','<p class="error" style="display: inline;">','</p>'); ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4 <?php echo (form_error('email')) ? 'has-error':'';?>" >
                                <label class="col-sm-12 ">Email <span>*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($dataArr['email'])){echo set_value('email',$dataArr['email']);}?>">
                                    <?php echo form_error('email','<p class="error" style="display: inline;">','</p>'); ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4" >
                                <label class="col-sm-12 ">Phone</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" maxlength="13" name="phone" id="ios3_code" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                     <div class="form-group col-sm-4">
                            <label class="col-sm-12">City</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="city"  value="<?php if(!empty($dataArr['city'])){ echo $dataArr['city']; }?>" />
                            </div>
                        </div>
                        </div>
                        <div class="row">
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12">Zipcode</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="zipcode"  value="<?php if(!empty($dataArr['zipcode'])){ echo $dataArr['zipcode']; }?>" />
                            </div>
                        </div>
                         <div class="form-group col-sm-4 " >
                            <label class="col-sm-12 ">Address</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="address"><?php if(!empty($dataArr['address'])){ echo set_value('address',$dataArr['address']);}?></textarea>
                                </div>
                            </div>
                             <div class="form-group col-sm-4">
                            <label class="col-sm-12">Upload Photo</label>
                            <div class="col-sm-12">
                               <input type="file" class="form-control" name="img" id="photo" accept="image/*">
                            </div>
                        </div>  
                        </div>
                        <div class="row">
                            <?php 
                             if($dataArr['role']=='Ship Captain' || $dataArr['role']=='Ship Cook'){
                                ?>
                            <div class="form-group col-sm-4 <?php echo (form_error('shipping_company_id')) ? 'has-error' : ''; ?>">
                               <label class="col-sm-12">Shipping Company <span>*</span></label>
                                 <div class="col-sm-12">
                                <select class="form-control" name="shipping_company_id" id="shipping_company_id">
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
                                <?php echo form_error('shipping_company_id'); ?> </div>
                                </div>
                             <?php }

                             if($dataArr['role']=='Vendor'){
                                ?>
                                <div class="form-group col-sm-4  <?php echo (form_error('currency')) ? 'has-error':'';?>">
                                    <label class="col-sm-12">Currency</label>
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
                            <div class="form-group col-sm-4 pdfUpload">
                            <label class="col-sm-12">Upload Vendor PDF</label>
                            <div class="col-sm-12">
                                <input accept="application/pdf" type="file" class="form-control" name="vendor_pdf" id="file" value="">
                              </div>
                             </div>
                             <?php }
                            ?>
                            <div class="form-group col-sm-4">
                            <label class="col-sm-12 ">Profile Picture</label>
                            <div class="col-sm-12">
                                <?php 
                                 if(!empty($dataArr['img_url'])){
                                  ?>
                                  <img id="imgPreview" src="<?php echo base_url().'uploads/user/'.$dataArr['img_url']?>" height="50" width="50" alt="No Profile Picture"> 
                                 <?php
                                  }
                                  else{
                                    ?>
                                    <img id="imgPreview" src="http://localhost/shipinventorymanagement/uploads/customer.png" height="50" width="50" alt="No Profile Picture"> 
                                 <?php }
                                ?>
                                 </div>
                               </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $dataArr['user_id'];?>" >
                         <input type="hidden" value="save" name="actionType">
                         <input type="hidden" name="role" value="<?php echo $dataArr['role'];?>">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditUser','user/edituser','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
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