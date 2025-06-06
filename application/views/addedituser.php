<!-- Start page header -->
<section class="new-layout">
<?php $user_session_data = getSessionData();?>
<div id="tour-11" class="header-content">
      <h2><span class="icon"><i class="fas fa-tools"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
</div><!-- /.header-content -->
<!-- Start body content -->
<div class="body-content animated fadeIn mt-10">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
            <form class="form-horizontal" name="addEditUser" id="addEditUser" method="post" enctype="multipart/form-data" action="<?php echo base_url().'user/addedituser'?>">
            <div class="panel-heading d-flex justify-between align-center">
                   <h3 class="panel-title" style="font-weight: bold;color: #001F3F;">ADD USER DETAILS</h3>
                <img id="imgPreview" class="ml-auto" src="http://localhost/shipinventorymanagement/uploads/customer.png" width="35" alt="No Profile Picture"> 
                </div>
                <div class="panel-body rounded-bottom">
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('first_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">First Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php if(!empty($dataArr['first_name'])){echo set_value('first_name',$dataArr['first_name']);}?>">
                                <?php echo form_error('first_name'); ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4 <?php echo (form_error('last_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Last Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php if(!empty($dataArr['last_name'])){echo set_value('last_name',$dataArr['last_name']);}?>">
                                <?php echo form_error('last_name'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4 <?php echo (form_error('user_role')) ? 'has-error' : ''; ?>" >
                            <label class="col-sm-12">User Role <span>*</span></label>
                             <div class="col-sm-12">
                                <select class="form-control" name="user_role" id="user_role">
                                    <option value="">Select</option>
                                   <?php
                                   if ($user_role) {
                                       foreach ($user_role as $row) {
                                         $selected = ($this->input->post('user_role') == $row->role_id ) ? 'selected' : '';
                                       echo '<option data-code="'.$row->code.'" value="'.$row->role_id.'" '.$selected.'>'.ucwords($row->role_name).'</option>';    
                                       }                                       
                                   }
                                   ?>
                                </select>
                                <?php echo form_error('user_role'); ?> </div>
                           </div>
                        
                    </div>
                       <div class="row">
                        <div  class="form-group col-sm-4 <?php echo (form_error('email')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Email <span>*</span></label>
                            <div class="col-sm-12">
                                <input  type="email" class="form-control" name="email" id="email" value="<?php if(!empty($dataArr['email'])){echo set_value('email',$dataArr['email']);}?>">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>    
                         <div class="form-group col-sm-4 <?php echo (form_error('user_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">User Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="user_name" id="user_name" value="<?php if(!empty($dataArr['user_name'])){echo set_value('user_name',$dataArr['user_name']);}?>">
                                <?php echo form_error('user_name'); ?>
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
                        <div class="form-group col-sm-4 <?php echo (form_error('phone')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Phone</label>
                            <div class="col-sm-12">
                                <input type="text" maxlength="13" class="form-control" name="phone" id="phone" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                <?php echo form_error('phone'); ?>
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
                    <div class="row" > 
                     <div class="form-group col-sm-4">
                            <label class="col-sm-12">Zipcode</label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" name="zipcode"  value="<?php if(!empty($dataArr['zipcode'])){ echo $dataArr['zipcode']; }?>" />
                            </div>
                        </div> 
                            <div class="form-group col-sm-4 <?php echo (form_error('address')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Address</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="address" id="address"><?php if(!empty($dataArr['address'])){echo set_value('address',$dataArr['address']);}?></textarea>
                                <?php echo form_error('address'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-4">
                            <label class="col-sm-12" for="photo">Upload Photo</label>
                            <div class="col-sm-12">
                            <div class="custom-file-upload">
                                <label for="photo">Select File</label>
                               <input accept="image/*" type="file" class="form-control cfu-file" name="img" id="photo" value="" onchange="displayFileName()">
                               <div id="fileName" class="file-name"></div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                         <div class="form-group col-sm-4 shipping_company_id <?php echo (form_error('shipping_company_id')) ? 'has-error' : ''; ?>" style='display: none;'>
                            <label class="col-sm-12">Shipping Company <span>*</span></label>
                             <div class="col-sm-12">
                                <select class="form-control" name="shipping_company_id" id="shipping_company_id">
                                    <option value="">Select</option>
                                   <?php
                                   if ($company) {
                                       foreach ($company as $row) {
                                         $selected = ($this->input->post('shipping_company_id') == $row->shipping_company_id ) ? 'selected' : '';
                                       echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                       }                                       
                                   }
                                   ?>
                                </select>
                                <?php echo form_error('shipping_company_id'); ?> </div>
                        </div>
                        <div class="form-group col-sm-4 currency_drp <?php echo (form_error('currency')) ? 'has-error':'';?>" style='display: none;'>
                            <label class="col-sm-12">Currency</label>
                            <div class="col-sm-12">
                            <select name="currency" id="currency" class="form-control">
                                <option value="">Select Currency</option>
<!--                                 <option value="1" <?php echo ($dataArr['currency']==1) ? 'selected' : '';?>>Euro</option> -->
                                <option value="2" <?php echo ($dataArr['currency']==2) ? 'selected' : '';?>>USD</option>
<!--                                 <option value="3" <?php echo ($dataArr['currency']==3) ? 'selected' : '';?>>SGD</option> -->
                            </select>
                                <?php echo form_error('currency','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                        <div class="form-group col-sm-4 payTerm <?php echo (form_error('payment_term')) ? 'has-error':'';?>" style='display: none;'>
                            <label class="col-sm-12">Payment Term <span>*</span></label>
                            <div class="col-sm-12">
                             <input type="text" name="payment_term" id="payment_term" class="form-control" value="<?php if(!empty($dataArr['payment_term'])){ echo set_value('payment_term',$dataArr['payment_term']);}?>">
                                <?php echo form_error('payment_term','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>
                         <div class="form-group col-sm-4 pdfUpload" style='display: none;'>
                         <label class="col-sm-12">Upload Vendor PDF</label>
                         <div class="col-sm-12">
                         <div class="custom-file-upload">
                            
                                <label for="file">Select File</label>
                               <input accept="application/pdf" type="file" class="form-control" name="vendor_pdf" id="file" value="" onchange="displayFileName2()">
                               <div id="vendor-pdf-name" class="file-name"></div>
                                <?php echo form_error('vendor_pdf','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            
                        </div>
                        </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="vendor_bank_info" style="display:none">
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
                        </div>

                     </div>

                     <div class="mb-15 mt-15">
                        <div class="text-center">
                            <a class="btn btn-danger btn-slideright mr-5" href="<?php echo base_url().'user/user_list'?>">Cancel</a>
                            <button type="submit" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                  </div>
               </div>            
            </div>
         </div>
            <div class="clearfix"></div>
                   
            </form>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('#user_role').change(function(event){
        $var = $("#user_role option:selected").data('code');
        if($var=='captain'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
          $('.vendor_bank_info').hide();

        }
        else if($var=='cook'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
           $('.payTerm').hide();
          $('.vendor_bank_info').hide();

        }
        else if($var=='vendor'){
          $('.currency_drp').show();
          $('.pdfUpload').show();
          $('.shipping_company_id').hide();
          $('.payTerm').show();
          $('.vendor_bank_info').show();

        }
        else if($var=='shipping_company'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
          $('.vendor_bank_info').hide();
        }
        else{
          $('.shipping_company_id').hide();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
          $('.vendor_bank_info').hide();
        } 
    })

    $(document).ready(function(){
        $var = $("#user_role option:selected").data('code');
          if($var=='captain'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
           $('.vendor_bank_info').hide();

        }
        else if($var=='cook'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
          $('.vendor_bank_info').hide();
        }
        else if($var=='shipping_company'){
          $('.shipping_company_id').show();
          $('.currency_drp').hide();
          $('.pdfUpload').hide();
          $('.payTerm').hide();
          $('.vendor_bank_info').hide();
        }
        else if($var=='vendor'){
          $('.currency_drp').show();
          $('.shipping_company_id').hide();
          $('.pdfUpload').show();
          $('.payTerm').show();
          $('.vendor_bank_info').show();
        }
    })

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
<script>
        function displayFileName() {
            const fileInput = document.getElementById('photo');
            const fileNameDisplay = document.getElementById('fileName');

            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = `Selected File: ${fileInput.files[0].name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        }


        function displayFileName2() {
            const fileInput = document.getElementById('file');
            const fileNameDisplay = document.getElementById('vendor-pdf-name');

            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = `Selected File: ${fileInput.files[0].name}`;
            } else {
                fileNameDisplay.textContent = '';
            }
        }










        
    </script>