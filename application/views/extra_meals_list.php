<?php 
$user_session_data = getSessionData();
$add_extra_meals = checkLabelByTask('add_extra_meals');
?>
<!-- Start body content -->
<div id="tour-11" class="header-content">
      <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg))
{
    ?><div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg))
{
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
$add_extra_meal = checkLabelByTask('add_extra_meals');
?>    
<div class="">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="em_list" name="em_list" method="POST" action="<?php echo base_url().'shipping/getAllExtraMealsList'?>">
            <!--Filter head start -->
            <div class="panel panel-default shadow no-overflow mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row scroll-filter">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        <label class="pull-left">
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitMealsTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        </label>
                                    </li>    
                                    <li class="status">
                                        <label class="pull-left">
                                        <label>Status</label><br>
                                        <select  id="status" name="status" onchange="submitMealsTableForm('', 1);"  class="form-control input-sm">
                                            <option value="">Select</option>
                                            <option value="C">Created</option>
                                            <option value="S">Submitted</option>
                                            <option value="I">Invoice Created</option>
                                        </select>
                                        </label>
                                    </li>                                 
                                    <!-- <li class="status">
                                        <label class="pull-left">
                                        <label>Invoice Created</label><br>
                                        <select  id="is_invoice_created" name="is_invoice_created" onchange="submitMealsTableForm('', 1);"  class="form-control input-sm">
                                            <option value="">Select</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                        </label>
                                    </li> -->
                                    <li class="ship">
                                        
                                        <label>Month</label><br>
                                         <select class="form-control input-sm customFilter" name="month" id="month" onchange="submitMealsTableForm()">
                                           <option value="">Select Month</option> 
                                           <option value="01" <?php echo ($dataArr['month'] =='01') ? 'selected' : '';?>>January</option>
                                            <option value="02" <?php echo ($dataArr['month'] =='02') ? 'selected' : '';?> >February</option>
                                            <option value="03" <?php echo ($dataArr['month'] =='03') ? 'selected' : '';?>>March</option>
                                            <option value="04" <?php echo ($dataArr['month'] =='04') ? 'selected' : '';?>>April</option>
                                            <option value="05" <?php echo ($dataArr['month'] =='05') ? 'selected' : '';?>>May</option>
                                            <option value="06" <?php echo ($dataArr['month'] =='06') ? 'selected' : '';?>>June</option>
                                            <option value="07" <?php echo ($dataArr['month'] =='07') ? 'selected' : '';?>>July</option>
                                            <option value="08" <?php echo ($dataArr['month'] =='08') ? 'selected' : '';?> >August</option>
                                            <option value="09" <?php echo ($dataArr['month']=='09') ? 'selected' : '';?>>September</option>
                                            <option value="10" <?php echo ($dataArr['month']=='10') ? 'selected' : '';?>>October</option>
                                            <option value="11" <?php echo ($dataArr['month']=='11') ? 'selected' : '';?>>November</option>
                                            <option value="12" <?php echo ($dataArr['month']=='12') ? 'selected' : '';?>>December</option> 
                                         </select>
                                        
                                    </li>
                                    <li class="ship">
                                        
                                        <label>Year</label><br>
                                        <select class="customFilter form-control input-sm" name="year" id="year" onchange="submitMealsTableForm()">
                                            <option value="">Select Year</option>
                                            <?php
                                                for ($i = 0; $i <= 3; $i++) {
                                                   $year= date('Y', strtotime("-$i year"));
                                                   $selected = ($dataArr['year'] == $year)?'selected':'';
                                                   echo '<option value="'.$year.' "'.$selected.'>'.$year.'</option>';
                                                 }?>
                                        </select>
                                    </li>
                                      <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro small-picker" type="text" value="" autocomplete="off" onchange="submitMealsTableForm('', 1);">
                                    </label>
                                    </li> 
                                    <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                    </li>
                                    <?php
                                    if($add_extra_meal){ 
                                     if(!empty($opening_stock)){
                                        ?>
                                    <li>
                                        <label>&nbsp;</label><br>
                                         <a id="alert_box" onclick="showAjaxModel('Generate Extra Meals Report','report/report_config/extra_meals','<?php echo $ship_id;?>','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
                                    </li>
                                     <?php 
                                         }
                                         else{  ?>
                                     <li>
                                        <label>&nbsp;</label><br>
                                         <a id="alert_box" onclick="showAlert()" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
                                     </li>
                                    <?php }
                                       }
                                    ?>
                                    
                                 <li class="filter-s extra-meal-filter">       
                                      <div class="contactSearch">
                                            <input title="Search by Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitMealsTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitMealsTableForm('', 1)"></a>
                                      </div>
                                    </li>
                                    <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="downloadCsv()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- filter head end -->
            <!-- Start table advanced -->
            <div class="flex-content mb-10">
                        <input type="hidden" name="page" value="" id="em_page" />
                        <div class="d-flex flex-column h-100 p-0 panel no-border">
                                   
                                        <input type="hidden" name="eMsort_column" id="eMsort_column" value="" />
                                        <input type="hidden" name="eMsort_type" id="eMsort_type" value="" />
                                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="extra_meals" />
                                         <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="extra_meals_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="month_th" onclick="eMshowOrderBy('Month', 'month_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Month</a>
                                                </th>
                                                <th  width='10%' id="year_th" onclick="eMshowOrderBy('Year', 'year_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Year</a>
                                                </th>
                                               <th width='10%' id="added_on_th" onclick="eMshowOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="eMshowOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                               <!--  <th width='10%' id="invoice_th" onclick="eMshowOrderBy('Invoice', 'invoice_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Invoice Created</a>
                                                </th> -->
                                                 <th width="10%" id="status_th" onclick="eMshowOrderBy('Status', 'status_th');" class="rmv_cls sorting ">
                                                            <a href="javascript:void(0);">Status</a>
                                                    </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="em_list_data">
                                            </tbody>
                                        </table>
                                        <div class="new-style-pagination">
                                            <div class="total_entries"></div>
                                            <ul class="pagination pagination-sm">
                                                <li class="new_pagination"></li>
                                            </ul>
                                        </div>                                
                        </div><!-- /.panel-body -->
                    </div>
            
            </form>
            <!--/ End table advanced -->
        </div><!-- /.col-md-12 -->
    </div>
</div>
<span id="downloadList" style="display: none;">
        <div class="exportBox" style="padding:10px;">
         <div class="row"> 
         <div class="mt-12" id="downloadPagesId"></div>   
      <div style="text-align:right" class="mt-10">
      <a class="runner btn btn-mini btn-success btn-slideright" tabindex="0" onclick="$('.close').click();exportTable();"><span>Download</span></a>
      </div>
     </div>
  </span>   
<script type='text/javascript'>

function showAlert(){
      var data = '<span><strong>Please Add Opening Stock First !.</strong></span>';
      $('#modal-view-datatable').modal('show');
      $('#pop_heading').html('Alert');
      $('#modal_content').html(data);
  }

function submitMealsTableForm(pageId)
{      
        if(pageId){
            $("#em_page").val(pageId);
        }else{ $("#em_page").val('');}
        var $data = new FormData($('#em_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllExtraMealsList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.em_list_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }

  $(document).ready(function(){
     submitMealsTableForm();
  })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitMealsTableForm();
          return false;
        }
    })
    
    function extra_mealssubmitPagination(pageId)
    {
       submitMealsTableForm(pageId);
    }

  function resetFilter()
    {
        $("#eMsort_column").val('');
        $("#eMsort_type").val('');
        $(".customFilter").val('');        
        $('#auto_a69').val('25');
        $('#is_invoice_created').val('');
        $('#month').val('');
        $('#year').val('');
        $('#status').val('');
        eMshowOrderBy();
    }   

  function eMshowOrderBy(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var eMsort_column = $("#eMsort_column").val();
        var eMsort_type = $("#eMsort_type").val();
        if(eMsort_column == '')
        {
            $("#eMsort_column").val(head_title);
            $("#eMsort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(eMsort_column == head_title)
        {
            $("#eMsort_column").val(head_title);
            if(eMsort_type == 'ASC')
            {
                $("#eMsort_type").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#eMsort_type").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#eMsort_column").val(head_title);
            $("#eMsort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_eMsort_column = $("#eMsort_column").val();
        var final_eMsort_type = $("#eMsort_type").val();

        submitMealsTableForm();
    }
 
 function update_em_status(id) {
        bootbox.dialog({
            message: 'Are you sure want to submit this report ?',
            title: "Confirmation",
            className: "modal-primary",
            buttons: {
                danger: {
                    label: "No",
                    className: "btn-danger btn-slideright mLeft",
                    callback: function () {}
                },
                success: {
                    label: "Yes",
                    className: "btn-success btn-slideright",
                    callback: function () {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'shipping/update_em_status',
                            cache: false,
                            data: {'id': id},
                            success: function (msg) {
                              var obj = jQuery.parseJSON(msg);
                              if(obj.status==200){
                                 $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                                extra_meals_html();  
                              }
                            }
                        });
                    }
                }

            }
        });
}

//$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});


 function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#em_list')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllExtraMealsList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Extra Meals",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#em_list").submit();
    $("#download").val('0');
 } 
</script>
