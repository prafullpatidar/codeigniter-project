<link rel="stylesheet" href="<?php echo base_url('assets/css/multi-select.css')?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.multi-select.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.quicksearch.js')?>"></script>

<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow pb-10 mb-10 no-border">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="send_request" enctype="multipart/form-data" id="send_request" method="post">
                    <div class="form-body">
                        <div class="">
                        <div class="form-group <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <div class="">
                            <select name="vendor_id[]" class="form-control send_to" id="send_to" multiple>
                            <?php 
                              if(!empty($vendors)){
                               foreach ($vendors as $row) {
                                $selected = (in_array($row->vendor_id,$dataArr['vendor_id'])) ? 'selected' : '';
                                ?>
                                 <option <?php echo $selected;?> value="<?php echo $row->vendor_id;?>"><?php echo ucwords($row->vendor_name);?></option>
                                <?php 
                                } 
                              }
                             ?>
                            </select>
                            <?php echo form_error('vendor_id[]','<p class="error" style="display:inline">','</p>');?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       <div class="form-group col-sm-6 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Expire Date <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" name="expire_date" class="form-control datePicker_editPro" value="<?php echo $dataArr['expire_date'];?>">
                                <?php echo form_error('expire_date','<p class="error" style="display:inline">','</p>');?>
                          </div>
                         </div> 
<!--                         <div class="form-group col-sm-6 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">&nbsp;</label>
                            <div class="col-sm-12">
                              <div class="form-check mt-3">
                                
                                <input name="send_notification"  type="checkbox" class="form-check-input label-left" id="customSwitch1">
                                <label class="form-check-label" for="customSwitch1">
                                
                                    Send Notification</label>
                                
                              </div>
                        </div>
                        </div> -->
                    <input type="hidden" name="id" value="<?php echo $ship_order_id?>">
                    <input type="hidden" name="second_id" value="<?php echo $rfq_no;?>">
                        <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('send_request','shipping/send_order_request','50%','order_request_list')" class="btn btn-success btn-slideright mr-5">Send</button>
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
        // $('.send_to').multiselect({
            // includeSelectAllOption: true,
            // numberDisplayed: -1,
            // nonSelectedText: 'Select Vendor,
            // title: 'Select Vendor,
             // enableClickableOptGroups: true,
    //         enableFiltering: true,
    //         enableCaseInsensitiveFiltering: true,
    //     });
      multiSelection('.send_to','Vendor\' <span>*</span>');
    });

    function multiSelection(selectTag,label){
        $(selectTag).multiSelect({
            selectableHeader: "<label>Select "+label+"</label><input type='text' class='form-control search-input' autocomplete='off' placeholder='Search'>",
            selectionHeader: "<label>Selected "+label+"</label><input type='text' class='form-control search-input' autocomplete='off' placeholder='Search'>",
            afterInit: function(ms){
              var that = this,
                  $selectableSearch = that.$selectableUl.prev(),
                  $selectionSearch = that.$selectionUl.prev(),
                  selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                  selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

              that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
              .on('keydown', function(e){
                if (e.which === 40){
                  that.$selectableUl.focus();
                  return false;
                }
              });

              that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
              .on('keydown', function(e){
                if (e.which == 40){
                  that.$selectionUl.focus();
                  return false;
                }
              });
            },
            afterSelect: function(){
              this.qs1.cache();
              this.qs2.cache();
            },
            afterDeselect: function(){
              this.qs1.cache();
              this.qs2.cache();
            }
        });
    }

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
        changeYear: true,
        yearRange: "c-100:c+3"
    });
});
</script>
