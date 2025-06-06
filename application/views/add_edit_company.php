<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="addEditCompany" enctype="multipart/form-data" id="addEditCompany" method="post">
                    <div class="form-body  pt-0 pb-0">
                        <div class="row">
                        <div class="form-group col-sm-6 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label>Company Name <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="name" id="name" value="<?php if(!empty($dataArr['name'])){echo set_value('name',$dataArr['name']);}?>">
                                <?php echo form_error('name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 <?php echo (form_error('customer_id')) ? 'has-error':'';?>" >
                            <label>Customer ID <span>*</span></label>
                            <div>
                                <input type="number" class="form-control" name="customer_id" id="customer_id" value="<?php if(!empty($dataArr['customer_id'])){echo set_value('customer_id',$dataArr['customer_id']);}?>">
                                <?php echo form_error('customer_id','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-6" >
                            <label>Email <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($dataArr['email'])){echo set_value('email',$dataArr['email']);}?>">
                                <?php echo form_error('email','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-6" >
                            <label>Phone <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?php if(!empty($dataArr['phone'])){echo set_value('phone',$dataArr['phone']);}?>">
                                <?php echo form_error('phone','<p class="error" style="display: inline;">','</p>'); ?>

                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6 <?php echo (form_error('country')) ? 'has-error':'';?>" >
                            <label>Country <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="country" id="country" value="<?php if(!empty($dataArr['country'])){echo set_value('country',$dataArr['country']);}?>">
                                <?php echo form_error('country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>      
                        <div class="form-group col-sm-6 <?php echo (form_error('state')) ? 'has-error':'';?>" >
                            <label>State <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="state" id="state" value="<?php if(!empty($dataArr['state'])){echo set_value('state',$dataArr['state']);}?>">
                                <?php echo form_error('state','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                               </div>
                        <div class="row">                
                       <div class="form-group col-sm-6 <?php echo (form_error('city')) ? 'has-error':'';?>" >
                            <label>City <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="city" id="city" value="<?php if(!empty($dataArr['city'])){echo set_value('city',$dataArr['city']);}?>">
                                <?php echo form_error('city','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        
                   
                        <div class="form-group col-sm-6 <?php echo (form_error('zip')) ? 'has-error':'';?>" >
                            <label>Zipcode <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="zip" id="zip" value="<?php if(!empty($dataArr['zip'])){echo set_value('zip',$dataArr['zip']);}?>">
                                <?php echo form_error('zip','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                         <div class="form-group col-sm-6 <?php echo (form_error('payment_term')) ? 'has-error':'';?>">
                            <label>Payment Term <span>*</span></label>
                            <div>
                             <input type="text" name="payment_term" id="payment_term" class="form-control" value="<?php if(!empty($dataArr['payment_term'])){ echo set_value('payment_term',$dataArr['payment_term']);}?>">
                                <?php echo form_error('payment_term','<p class="error" style="display: inline;">','</p>'); ?>
                            
                            </div>
                        </div>                     
                   
                    <div class="form-group col-sm-6 <?php echo (form_error('address')) ? 'has-error':'';?>" >
                            <label>Address <span>*</span></label>
                            <div>
                                <textarea class="form-control" name="address" ><?php if(!empty($dataArr['address'])){echo set_value('date',$dataArr['address']);}?></textarea>
                                <?php echo form_error('address','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12" >

                            <label>Company Logo</label>
                            <div>
                                <input accept="image/*" type="file" class="form-control" name="img" id="photo" value="">
                                <!-- <label for="photo"><i class="fas fa-upload"></i><span>Choose a file</span></label> -->
                            </div>
                        </div>
                        <div class="form-group col-sm-12 tex-center" >
                            <label>Uploaded Company Logo</label>
                            <div class="upload-preview">
                                <?php echo (!empty($dataArr['logo']) ? '<img id="imgPreview" src="'. base_url().'uploads/company/'.$dataArr["logo"].'" height="70" width="70">':'<img src="'.base_url().'uploads/customer.png" id="imgPreview" height="70" width="70" alt="No Profile Picture">');?>
                            </div>
                        </div>
                    </div>  
                   
                        <input type="hidden" value="<?php echo (isset($dataArr['shipping_company_id'])) ? $dataArr['shipping_company_id'] : '' ;?>" name="id">
                        <input type="hidden" value="save" name="actionType">

                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditCompany','shipping/add_edit_company','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
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