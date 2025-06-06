<?php 
$user_session_data = getSessionData();
$create_victualing_report = checkLabelByTask('create_victualing_report');
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
?>    
<div class="">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="summary_report" name="summary_report" method="POST" action="<?php echo base_url().'shipping/getAllSummaryReports'?>">
            <!--Filter head start -->
            <div class="panel panel-default shadow no-overflow mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        <label class="pull-left">
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitSummaryForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        </label>
                                    </li>
                                    <li class="ship">                                       
                                        <label>Month</label><br>
                                         <select class="form-control customFilter" name="month" id="month" onchange="submitSummaryForm()">
                                           <option value="">Select</option> 
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
                                        <select class="customFilter form-control" name="year" id="year" onchange="submitSummaryForm()">
                                            <option value="">Select</option>
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
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitSummaryForm('', 1);">
                                    </label>
                                    </li> 
                                   <?php
                                    if($create_victualing_report){ 
                                     if(!empty($opening_stock)){
                                        ?>
                                     <li>
                                        <label>&nbsp;</label><br>
                                          <a id="alert_box" onclick="showAjaxModel('Generate Victualing Summary Report','report/report_config/summary_report','<?php echo $ship_id;?>','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
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

                                     <li class="pull-right filter-s">       
                                      <div class="contactSearch">
                                            <input title="Search by Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitSummaryForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitSummaryForm('', 1)"></a>
                                      </div>
                                    </li>
                                    <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
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
                         <input type="hidden" name="page" value="" id="vs_page" />
                        
                            <div class="d-flex flex-column h-100 p-0 panel no-border">
                                   
                                <input type="hidden" name="sort_columnvs" id="sort_columnvs" value="" />
                                <input type="hidden" name="sort_typevs" id="sort_typevs" value="" />
                                <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                <input type="hidden" name="prefix_label" id="prefix_label" value="summary" />
                                  <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                       
                                        <table id="extra_meals_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th  width='10%' style="width:10%" id="month_th" onclick="showOrderByVs('Month', 'month_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Month</a>
                                                </th>
                                                <th  width='10%' id="year_th" onclick="showOrderByVs('Year', 'year_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Year</a>
                                                </th>
                                                 <th width='10%' id="added_on_th" onclick="showOrderByVs('Added On', 'added_on_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="showOrderByVs('Added By', 'added_by_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                <th width='10%' id="status_th"  class="">
                                                    Status
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="report_data">
                                            </tbody>
                                        </table>
                                    
                                    <div class="new-style-pagination">
                                            <div class="total_entries"></div>
                                            <ul class="pagination pagination-sm">
                                                <li class="new_pagination"></li>
                                            </ul>
                                        </div>

                                </div>
                            
            </div><!-- /.panel -->
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
 </div>
  </span>  
<script type='text/javascript'>

function submitSummaryForm(pageId)
{      
        if(pageId){
            $("#vs_page").val(pageId);
        }else{ $("#vs_page").val('');}
        var $data = new FormData($('#summary_report')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllSummaryReports',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.report_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }

  $(document).ready(function(){
     submitSummaryForm();
  })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitSummaryForm();
          return false;
        }
    })
    
    function summarysubmitPagination(pageId)
    {
       submitSummaryForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_columnvs").val('');
        $("#sort_typevs").val('');
        $(".customFilter").val('');        
        $('#auto_a69').val('25');
        $('#month').val('');
        $('#year').val('');
        showOrderByVs();
    }   

    function showOrderByVs(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_columnvs = $("#sort_columnvs").val();
        var sort_typevs = $("#sort_typevs").val();
        if(sort_columnvs == '')
        {
            $("#sort_columnvs").val(head_title);
            $("#sort_typevs").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(sort_columnvs == head_title)
        {
            $("#sort_columnvs").val(head_title);
            if(sort_typevs == 'ASC')
            {
                $("#sort_typevs").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_typevs").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_columnvs").val(head_title);
            $("#sort_typevs").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_sort_columnvs = $("#sort_columnvs").val();
        var final_sort_typevs = $("#sort_typevs").val();

        submitSummaryForm();
    }

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

function showAlert(){
      var data = '<span><strong>Please Add Opening Stock First !.</strong></span>';
      $('#modal-view-datatable').modal('show');
      $('#pop_heading').html('Alert');
      $('#modal_content').html(data);
  }


 function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#summary_report')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllSummaryReports',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Victualing Summary",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#summary_report").submit();
    $("#download").val('0');
 }   


 function update_vsr_status(id) {
        bootbox.dialog({
            message: 'Are you sure want to submit victualing summary report ?',
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
                            url: base_url + 'shipping/update_vsr_status',
                            cache: false,
                            data: {'id': id},
                            success: function (msg) {
                              var obj = jQuery.parseJSON(msg);
                              if(obj.status==200){
                                 $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                               submitSummaryForm();
                              }
                            }
                        });
                    }
                }

            }
        });
}
</script>
