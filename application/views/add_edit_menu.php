<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker-custom.js"></script>
<link href="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.css" rel="stylesheet">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal" name="addEditFoodMenu" enctype="multipart/form-data" id="addEditFoodMenu" method="post">
                    <div class="form-body  pt-0 pb-0">
                        <div class="row">

                        <div class="form-group col-sm-4 <?php echo (form_error('month')) ? 'has-error':'';?>" >
                            <label>Date Range <span>*</span></label>
                            <input type="text" class="form-control date-range-picker-clearbtn" name="date_range" value="<?php echo ($dataArr['date_range']) ? $dataArr['date_range']  : '' ?>">
                            <div>
                            <?php echo form_error('date_range','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4 hide <?php echo (form_error('month')) ? 'has-error':'';?>" >
                            <label>Month <span>*</span></label>
                            <div>
                                <select id="month" name="month" class="form-control">
                                  <option value="">Select</option>
                                  <option <?= ($dataArr['month'] == 'january') ? 'selected' : ''?> value="january">January</option>
                                  <option <?= ($dataArr['month'] == 'february') ? 'selected' : ''?> value="february">February</option>
                                  <option  <?= ($dataArr['month'] == 'march') ? 'selected' : ''?> value="march" >March</option>
                                  <option <?= ($dataArr['month'] == 'april') ? 'selected' : ''?> value="april">April</option>
                                    <option <?= ($dataArr['month'] == 'may') ? 'selected' : ''?> value="may">May</option>
                                  <option  <?= ($dataArr['month'] == 'june') ? 'selected' : ''?> value="june" >June</option>
                                  <option <?= ($dataArr['month'] == 'july') ? 'selected' : ''?> value="july" >July</option>
                                  <option <?= ($dataArr['month'] == 'august') ? 'selected' : ''?> value="august" >August</option>
                                    <option <?= ($dataArr['month'] == 'september') ? 'selected' : ''?> value="september">September</option>
                                  <option <?= ($dataArr['month'] == 'october') ? 'selected' : ''?> value="october">October</option>
                                  <option <?= ($dataArr['month'] == 'november') ? 'selected' : ''?> value="november">November</option>
                                  <option <?= ($dataArr['month'] == 'december') ? 'selected' : ''?> value="december">December</option>
                                </select>
                                <?php echo form_error('month','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('religion')) ? 'has-error':'';?>" >
                            <label>Religion <span>*</span></label>
                            <div>
                                 <select id="religion" name="religion" class="form-control">
                                            <option value="">Select</option>
                                            <option <?= ($dataArr['religion'] == 'indian_asian') ? 'selected' : ''?> value="indian_asian">INDIAN / ASIAN</option>
                                            <option <?= ($dataArr['religion'] == 'east_europe') ? 'selected' : ''?> value="east_europe">EAST EUROPE</option>
                                            <option <?= ($dataArr['religion'] == 'phili_indo') ? 'selected' : ''?> value="phili_indo">PHILI / INDO</option>
                                            <option <?= ($dataArr['religion'] == 'chines') ? 'selected' : ''?> value="chines">CHINES</option>
                                    </select>   
                                <?php echo form_error('religion','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 <?php echo (form_error('issue_date')) ? 'has-error':'';?>" >
                            <label>Issue Date <span>*</span></label>
                            <div>
                                <input type="text" class="form-control datePicker_editPro" name="issue_date" id="issue_date" value="<?php echo ($dataArr['issue_date']) ? convertDate($dataArr['issue_date'],'','d-m-Y') : '' ?> ">
                                <?php echo form_error('issue_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-4 <?php echo (form_error('file')) ? 'has-error':'';?>" >
                            <label>Upload <span>*</span></label>
                            <div class="custom-file-upload">
                                <label for="photo">Select File</label>
                               <input type="file" class="form-control cfu-file" name="img" id="photo" value="" onchange="displayFileName()">
                               <div id="fileName" class="file-name"><?php echo (!empty($dataArr['file'])) ? 'Selected File:'.$dataArr['file'] : ''?></div>
                            </div>
                             <?php echo form_error('img','<p class="error" style="display: inline;">','</p>'); ?>
                        </div>
                        </div>        
                        <input type="hidden" value="<?php echo (isset($dataArr['food_menu_id'])) ? $dataArr['food_menu_id'] : '' ;?>" name="id">
                        <input type="hidden" value="save" name="actionType">

                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitMoldelForm('addEditFoodMenu','food_menu/add_edit_menu','50%')" class="btn btn-success btn-slideright mr-5">Submit</button>
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


    $(document).on('click','.cust_start_date',function(){
    $(this).addClass('current-datRange');
    $(this).closest('.daterangepicker').find('.cust_end_date').removeClass('current-datRange');
    $(this).closest('.daterangepicker').find('.cust_cal_right').hide();
    $(this).closest('.daterangepicker').find('.cust_cal_left').show();
  });

  $(document).on('click','.cust_end_date',function(){
   $(this).addClass('current-datRange');
   $(this).closest('.daterangepicker').find('.cust_start_date').removeClass('current-datRange');
   $(this).closest('.daterangepicker').find('.cust_cal_left').hide();
   $(this).closest('.daterangepicker').find('.cust_cal_right').show();
  });



</script>