<style type="text/css">

.line-with-label {
  text-align: center;
  position: relative;
  margin: 30px 0;
  font-weight: bold;
  color: #444;
}

.line-with-label::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  height: 1px;
  background: #ccc;
  z-index: 0;
}

</style>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditProduct" enctype="multipart/form-data" id="addEditProduct" method="post">
                    <div class="form-body">
                        <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('product_name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Name<span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="product_name" id="product_name" value="<?php if(!empty($dataArr['product_name'])){echo set_value('product_name',$dataArr['product_name']);}?>">
                                <?php echo form_error('product_name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('item_no')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Item No<span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="item_no" id="item_no" value="<?php if(!empty($dataArr['item_no'])){echo set_value('item_no',$dataArr['item_no']);}?>">
                                <?php echo form_error('item_no','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Unit<span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="unit" id="unit" value="<?php if(!empty($dataArr['unit'])){echo set_value('unit',$dataArr['unit']);}?>">
                                <?php echo form_error('unit','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        </div>
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('product_category_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 ">Product Category <span>*</span></label>
                            <div class="col-sm-12">
                                <select name='product_category_id' class="form-control" id='product_category_id'>
                                    <option value="">Select Category</option>  
                                    <?php
                                    if(!empty($products_category)){
                                        foreach ($products_category as $pc){
                                       $selected = (!empty($dataArr['product_category_id']) && $dataArr['product_category_id'] == $pc->product_category_id)?'selected':'';
                                        echo '<option '.$selected.' value="'.$pc->product_category_id.'">'.$pc->category_name.'</option>';    
                                        }   
                                    }
                                    ?>
                                </select>
                         <?php echo form_error('product_category_id','<p class="error" style="display: inline;">','</p>'); ?> 
                            </div>
                          
                        </div>  
                        </div>
                        
                        <div class="line-with-label" >* Nutritional Fields *</div>
                        
                        <div class="row">    
                          <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Calories(G)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="calories" id="calories" value="<?php if(!empty($dataArr['calories'])){echo set_value('calories',$dataArr['calories']);}?>">
                            </div>
                        </div>
                         <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Protein(G)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="protein" id="protein" value="<?php if(!empty($dataArr['protein'])){echo set_value('protein',$dataArr['protein']);}?>">
                            </div>
                        </div>
                         <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Calcium(MG)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="calcium" id="calcium" value="<?php if(!empty($dataArr['calcium'])){echo set_value('calcium',$dataArr['calcium']);}?>">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Total Fat(G)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="fat" id="fat" value="<?php if(!empty($dataArr['fat'])){echo set_value('fat',$dataArr['fat']);}?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Saturated Fat(G)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="saturated_fat" id="saturated_fat" value="<?php if(!empty($dataArr['saturated_fat'])){echo set_value('saturated_fat',$dataArr['saturated_fat']);}?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Cholesterol(MG)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="cholesterol" id="cholesterol" value="<?php if(!empty($dataArr['cholesterol'])){echo set_value('cholesterol',$dataArr['cholesterol']);}?>">
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Sodium(MG)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="sodium" id="sodium" value="<?php if(!empty($dataArr['sodium'])){echo set_value('sodium',$dataArr['sodium']);}?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Potassium(MG)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="potassium" id="potassium" value="<?php if(!empty($dataArr['potassium'])){echo set_value('potassium',$dataArr['potassium']);}?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Carbohydrates(G)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="carbohydrates" id="carbohydrates" value="<?php if(!empty($dataArr['carbohydrates'])){echo set_value('carbohydrates',$dataArr['carbohydrates']);}?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('unit')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Iron(MG)</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="iron" id="iron" value="<?php if(!empty($dataArr['iron'])){echo set_value('iron',$dataArr['iron']);}?>">
                            </div>
                        </div>
                    </div>
                        <input type="hidden" value="<?php echo (isset($dataArr['product_id'])) ? $dataArr['product_id'] : '';?>" name="id">
                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditProduct','product/addnewproduct','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>