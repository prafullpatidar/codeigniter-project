<?php 
$user_session_data = getSessionData();
$add_condemned_stock = checkLabelByTask('add_condemned_stock');
?>
<!-- Start body content -->
<div id="tour-11" class="header-content">
      <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<div class="">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="csr_list" name="csr_list" method="POST" action="<?php echo base_url().'report/getAllCondemnedStockReportData';?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitCSRTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <select  id="status" name="status" onchange="submitCSRTableForm('', 1);"  class="form-control">
                                            <option value="">Select</option>
                                            <option value="C">Created</option>
                                            <option value="S">Submitted</option>
                                        </select>
                                        </label>
                                    </li>

                                    <li class="ship">                                       
                                        <label>Month</label><br>
                                         <select class="form-control customFilter" name="month" id="month" onchange="submitCSRTableForm()">
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
                                        <select class="customFilter form-control" name="year" id="year" onchange="submitCSRTableForm()">
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
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitCSRTableForm('', 1);">
                                    </label>
                                    </li> 
                                   <?php
                                    if($add_condemned_stock){ 
                                     //if(!empty($opening_stock)){
                                        ?>
                                      <li>
                                        <label>&nbsp;</label><br>
                                         <a id="alert_box" onclick="showAjaxModel('Generate Condemned Stock Report','report/report_config/codemned_report','<?php echo $ship_id;?>','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
                                      </li>
                                     <?php 
                                         }
                                         // else{  ?>
                                    <!--  <li>
                                        <label>&nbsp;</label><br>
                                         <a id="alert_box" onclick="showAlert()" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
                                     </li> -->
                                    <?php //}
                                       //}
                                    ?>

                                     <li class="pull-right filter-s">       
                                      <div class="contactSearch">
                                            <input title="Search by Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitCSRTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitCSRTableForm('', 1)"></a>
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
                        <input type="hidden" name="page" value="" id="cpage" />
                        
                            <div class="d-flex flex-column h-100 p-0 panel no-border">
                                    
                                        <input type="hidden" name="sort_column" id="sort_column" value="" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="" />
                                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="condemned" />
                                         <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                         <table id="csr_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='1%' id="ship_name_th" onclick="showOrderByC('Ship Name', 'ship_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vessel Name</a>
                                                </th>
                                                <th width='10%' id="month_th" onclick="showOrderByC('Month', 'month_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Month</a>
                                                </th>
                                                <th  width='10%' id="year_th" onclick="showOrderByC('Year', 'year_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Year</a>
                                                </th>
                                                <th  width='10%' id="total_th" onclick="showOrderByC('Total', 'total_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Total Amount($)</a>
                                                </th>
                                                 <th width='10%' id="created_on_th" onclick="showOrderByC('Created On', 'created_on_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="created_by_th" onclick="showOrderByC('Created By', 'created_by_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                <th width='10%' id="status_th" onclick="showOrderByC('Status', 'status_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="csr_list_data">
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

function submitCSRTableForm(pageId)
{      
        if(pageId){
            $("#cpage").val(pageId);
        }else{ $("#cpage").val(1);}
        var $data = new FormData($('#csr_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'report/getAllCondemnedStockReportData',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.csr_list_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }
  $(document).ready(function(){
     submitCSRTableForm();
  })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitCSRTableForm();
          return false;
        }
    })
    
    function summarysubmitPagination(pageId)
    {
       submitCSRTableForm(pageId);
    }


   function update_csr_status(id) {
        bootbox.dialog({
            message: 'Are you sure want to submit condemned report ?',
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
                            url: base_url + 'report/update_csr_status',
                            cache: false,
                            data: {'id': id},
                            success: function (msg) {
                              var obj = jQuery.parseJSON(msg);
                              if(obj.status==200){
                                 location.reload();
                              }
                            }
                        });
                    }
                }

            }
        });
}


    function showOrderByC(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_column = $("#sort_column").val();
        var sort_type = $("#sort_type").val();
        if(sort_column == '')
        {
            $("#sort_column").val(head_title);
            $("#sort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(sort_column == head_title)
        {
            $("#sort_column").val(head_title);
            if(sort_type == 'ASC')
            {
                $("#sort_type").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_type").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_column").val(head_title);
            $("#sort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_sort_column = $("#sort_column").val();
        var final_sort_type = $("#sort_type").val();

        submitCSRTableForm();
    }

    function resetFilter()
    {
        $("#sort_columnvs").val('');
        $("#sort_typevs").val('');
        $(".customFilter").val('');        
        $('#auto_a69').val('25');
        $('#month').val('');
        $('#year').val('');
        $('#status').val('');    
        showOrderByC();
    }

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

function showAlert(){
      var data = '<span><strong>Please Add Opening Stock First !.</strong></span>';
      $('#modal-view-datatable').modal('show');
      $('#pop_heading').html('Alert');
      $('#modal_content').html(data);
  }

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});  


 function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#csr_list')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'report/getAllCondemnedStockReportData',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Condemned Stock",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#csr_list").submit();
    $("#download").val('0');
 }   
</script>
