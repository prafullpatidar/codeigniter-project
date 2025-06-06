<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditGroup" enctype="multipart/form-data" id="addEditGroup" method="post">
                    <div class="form-body">
                        <div class="row1">
                        <div class="form-group col-sm-4 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Group Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="name" id="name" value="<?php if(!empty($dataArr['name'])){echo set_value('name',$dataArr['name']);}?>">
                                <?php echo form_error('name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('code')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Group Code <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="code" id="code" value="<?php if(!empty($dataArr['code'])){echo set_value('code',$dataArr['code']);}?>">
                                <?php echo form_error('code','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                            <div class="form-group col-sm-4 <?php echo (form_error('consumed_qty')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Consumed Quantity (per parson)</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="consumed_qty" id="consumed_qty" value="<?php if(!empty($dataArr['consumed_qty'])){echo set_value('consumed_qty',$dataArr['consumed_qty']);}?>">
                                <?php echo form_error('consumed_qty','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                     <input type="hidden" value="<?php echo (isset($dataArr['product_group_id'])) ? $dataArr['product_group_id'] : '';?>" name="id">
                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditGroup','product/addEditproductGroup','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>