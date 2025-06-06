<!-- <link href="<?php echo base_url('assets/css/summernote.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/js/summernote.js')?>"></script> -->
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="addEditNews" enctype="multipart/form-data" id="addEditNews" method="post">
                    <div class="form-body  pt-0 pb-0">
                        <div class="row">
                        <div class="form-group col-sm-6 <?php echo (form_error('title')) ? 'has-error':'';?>" >
                            <label>News Title <span>*</span></label>
                            <div>
                                <input type="text" class="form-control" name="title" id="title" value="<?php echo ($dataArr['title']) ? $dataArr['title'] : '' ?>">
                                <?php echo form_error('title','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 <?php echo (form_error('customer_id')) ? 'has-error':'';?>" >
                            <label>Issue Date <span>*</span></label>
                            <div>
                                <input type="text" class="form-control datePicker_editPro" name="publish_on" id="publish_on" value="<?php echo ($dataArr['publish_on']) ? convertDate($dataArr['publish_on'],'','d-m-Y') : '' ?> ">
                                <?php echo form_error('publish_on','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <!-- <div class="form-group col-sm-6 <?php echo (form_error('content')) ? 'has-error':'';?>" >
                            <label>Discription </label>
                            <div>
                                <textarea id="summernote" name="content" class="form-control"><?php echo ($dataArr['content']) ? $dataArr['content'] : '' ?></textarea>
                                <?php echo form_error('content','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div> -->
                        <div class="form-group col-sm-6 <?php echo (form_error('content')) ? 'has-error':'';?>" >
                            <label>Upload <span>*</span></label>
                            <div class="custom-file-upload">
                                <label for="photo">Select File</label>
                               <input type="file" class="form-control cfu-file" name="img" id="photo" value="" onchange="displayFileName()">
                               <div id="fileName" class="file-name"><?php echo (!empty($dataArr['attechment'])) ? 'Selected File:'.$dataArr['attechment'] : ''?></div>
                            </div>
                             <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                        </div>
                        </div>        
                        <input type="hidden" value="<?php echo (isset($dataArr['newsletter_id'])) ? $dataArr['newsletter_id'] : '' ;?>" name="id">
                        <input type="hidden" value="save" name="actionType">

                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditNews','news/add_edit_news','50%')" class="btn btn-success btn-slideright mr-5">Submit</button>
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
    //  $(document).ready(function() {
    //     $('#summernote').summernote({
    //         placeholder: 'Enter Content',
    //         tabsize: 2,
    //         height: 120,
    //         toolbar: [
    //           ['style', ['style']],
    //           ['font', ['bold', 'underline', 'clear']],
    //           ['color', ['color']],
    //           ['para', ['ul', 'ol', 'paragraph']],
    //           ['table', ['table']],
    //           // ['insert', ['link', 'picture', 'video']],
    //           ['view', ['fullscreen', 'codeview', 'help']]
    //         ]
    //     });
    // });

    $(document).ready(function(){
        $('.datePicker_editPro').datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 0,
        })
    });

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