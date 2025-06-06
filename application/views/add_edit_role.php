<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="addEditRole" enctype="multipart/form-data" id="addEditRole" method="post">
                    <div class="form-body  pt-0 pb-0">
                        <div class="row">
                        <div class="form-group col-sm-6 <?php echo (form_error('role_name')) ? 'has-error':'';?>" >
                            <label>Role Name <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="role_name" id="role_name" value="<?php if(!empty($dataArr['role_name'])){echo set_value('role_name',$dataArr['role_name']);}?>">
                                <?php echo form_error('role_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    </div>  
                    <input type="hidden" value="<?php echo $role_id;?>" name="id">
                    <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditRole','user/add_edit_role','50%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>