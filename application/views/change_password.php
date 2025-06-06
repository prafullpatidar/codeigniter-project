<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditUser" enctype="multipart/form-data" id="addEditUser" method="post">
                    <div class="form-body">
                        <div class="form-group col-sm-6 <?php echo (form_error('password')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Password <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="password" id="password" value="">
                                <?php echo form_error('password','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div style="border-top:none;"  class="form-group col-sm-6 <?php echo (form_error('c_password')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 ">Confirm Password <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" name="c_password" id="c_password" value="">
                                <?php echo form_error('c_password','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $user_id;?>" name="id">
                         <input type="hidden" value="save" name="actionType">

                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditUser','user/changeUserPassword')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>