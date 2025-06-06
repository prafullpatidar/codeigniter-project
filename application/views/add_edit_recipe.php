    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="addEditFoodRecipe" enctype="multipart/form-data" id="addEditFoodRecipe" method="post">
                    <div class="form-body  pt-0 pb-0">
                        <div class="row">

                        <div class="form-group col-sm-6 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label>Recipe Title <span>*</span></label>
                            <input type="text" class="form-control" name="name" value="<?php echo ($dataArr['name']) ? $dataArr['name']  : '' ?>">
                            <div>
                            <?php echo form_error('name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-6 <?php echo (form_error('file')) ? 'has-error':'';?>" >
                            <label>Upload <span>*</span></label>
                            <div class="custom-file-upload">
                                <label for="photo">Select File</label>
                               <input type="file" class="form-control cfu-file" name="img" id="photo" value="" onchange="displayFileName()">
                               <div id="fileName" class="file-name"><?php echo (!empty($dataArr['file'])) ? 'Selected File:'.$dataArr['file'] : ''?></div>
                            </div>
                             <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                        </div>
                        </div>        
                        <input type="hidden" value="<?php echo $food_recipe_id; ?>" name="id">
                        <input type="hidden" value="save" name="actionType">

                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditFoodRecipe','food_menu/add_edit_recipe','70%')" class="btn btn-success btn-slideright mr-5">Submit</button>
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
    function displayFileName() {
        const fileInput = document.getElementById('photo');
        const fileNameDisplay = document.getElementById('fileName');

        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = `Selected File: ${fileInput.files[0].name}`;
        } else {
            fileNameDisplay.textContent = '';
        }
    }
</script>