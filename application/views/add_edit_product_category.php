<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditCategory" enctype="multipart/form-data" id="addEditCategory" method="post">
                    <div class="form-body">
                        <div class="row1">
                        <div class="form-group col-sm-6 <?php echo (form_error('category_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Category Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="category_name" id="category_name" value="<?php if(!empty($dataArr['category_name'])){echo set_value('category_name',$dataArr['category_name']);}?>">
                                <?php echo form_error('category_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 <?php echo (form_error('sequence')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Sequence</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="sequence" id="sequence" value="<?php if(!empty($dataArr['sequence'])){echo set_value('sequence',$dataArr['sequence']);}?>">
                                <?php echo form_error('sequence','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row1">
                       <div class="form-group col-sm-6 <?php echo (form_error('product_group_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 ">Product Group</label>
                            <div class="col-sm-12">
                                <select name='product_group_id' class="form-control" id='product_group_id'>
                                    <option value="">Select Group</option>  
                                    <?php
                                    if(!empty($products_group)){
                                        foreach ($products_group as $pg){
                                       $selected = (!empty($dataArr['product_group_id']) && $dataArr['product_group_id'] == $pg->product_group_id)?'selected':'';
                                        echo '<option '.$selected.' value="'.$pg->product_group_id.'">'.$pg->name.'</option>';    
                                        }   
                                    }
                                    ?>
                                </select>
                         <?php echo form_error('product_group_id','<p class="error" style="display: inline;">','</p>'); ?> 
                            </div>
                    </div>
                        <input type="hidden" value="<?php echo (isset($dataArr['product_category_id'])) ? $dataArr['product_category_id'] : '';?>" name="id">
                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditCategory','product/addEditproductCategory','50%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>