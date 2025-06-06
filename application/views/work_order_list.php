<script src="<?php echo base_url().'assets/js/bootstrap-multiselect.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-multiselect.css'?>">
<?php $user_session_data = getSessionData();
?>
<!-- <script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.multi-select.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.ui.js';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/multi-select.css';?>"> -->

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
            <form class="h-100 d-flex flex-column flex-no-wrap" id="po_order_list" name="po_order_list" method="POST" action="<?php echo base_url().'shipping/getAllWorkOrderList'?>">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitTableWorkForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        
                                    </li>  
                                    <li>
                                        <label class="pull-left">
                                        <label>Status</label><br>
                                        <select  id="status" name="rfq_status" onchange="submitTableWorkForm('', 1);" class="form-control input-sm perPage">
                                            <option value="">Select Status</option>
                                            <option value="1">Raised</option>
                                            <option value="2">Accepted By Vendor</option>
                                            <option value="3">DN Created</option>
                                            <option value="4">Invoice Uploaded</option>
                                            <option value="5">Temprory Cancel</option>
                                            <option value="6">Permanent Cancel</option>
                                        </select>
                                    </label>
                                    </li>  
                                    <li>
                                     <label class="pull-left">
                                     <label>Vendors</label><br>
                                       <select  id="vendor_id" name="vendor_id[]" onchange="submitTableWorkForm('', 1);" class="form-control input-sm" multiple="multiple">
                                        <!-- <option value="">Select Vendor</option> -->
                                        <?php
                                         if(!empty($vendors)){
                                           foreach($vendors as $row){
                                            ?>
                                            <option value="<?php echo $row->vendor_id;?>"><?php echo ucwords($row->vendor_name);?></option>
                                           <?php } 
                                         }
                                        ?>
                                        </select>
                                    </label>
                                </li>
                                     <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableWorkForm('', 1);">
                                    </label>
                                    </li>         
                                    <li class="pull-right filter-s">                                   
                                      <div class="contactSearch">
                                            <input title="Search by PO No, Order ID, RFQ No or Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableWorkForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableWorkForm('', 1)"></a>
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
                        <input type="hidden" name="page" value="" id="wo_page" />
                        
                        <div class="d-flex flex-column h-100 p-0 panel no-border">
                                    
                                        <input type="hidden" name="sort_column" id="sort_col" value="" />
                                        <input type="hidden" name="sort_type" id="sort_tp" value="" />
                                        <input type="hidden" name="ship_id" id="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="wo" />
                                        <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="po_no_th" onclick="showOrderByWo('Po N0', 'po_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">PO No.</a>
                                                </th>
                                                 <th  width='10%' id="order_id_th" onclick="showOrderByWo('Order ID', 'order_id_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Order ID</a>
                                                </th>
                                                 <th  width='10%' id="order_id_th" onclick="showOrderByWo('RFQ No', 'order_id_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">RFQ No</a>
                                                </th>
                                                <th width='10%' id="vendor_name_th" onclick="showOrderByWo('Vendor Name', 'vendor_name_th');" class="rmv_cls sorting">
                                                <a href="javascript:void(0);">Vendor Name</a>
                                                </th>
                                                <th width='10%' id="remark_th" onclick="showOrderByWo('Remark', 'remark_th');" class="rmv_cls sorting">
                                                <a href="javascript:void(0);">Remark</a>
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="showOrderByWo('Added On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="showOrderByWo('Added By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                 <th width='10%' id="status_th" onclick="showOrderByWo('Status', 'po_no_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Stage</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="work_order_lisst">
                                            </tbody>
                                        </table>

                                        <div class="new-style-pagination">
                                    <div class="total_entries_wo total_entries_static"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="wo_new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            
                    </div>
            </form>
            <!--/ End table advanced -->
        </div><!-- /.col-md-12 -->
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
</div>
<script type='text/javascript'>

 function submitTableWorkForm(pageId){ 
       $('#wo_page').val(pageId);  
       var $data = new FormData($('#po_order_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllWorkOrderList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#work_order_lisst').html(obj.dataArr);
                 $('.wo_new_pagination').html(obj.pagination);
                 $('.total_entries_wo').html(obj.total_entries);
                 highlightRow();
            }
        });
        return false;
    }
 
 $(document).ready(function(){
    submitTableWorkForm();
 })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitTableWorkForm();
          return false;
        }
    })
    
    function wosubmitPagination(pageId)
    {
       submitTableWorkForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_column").val('Added On');
        $("#sort_type").val('ASC');
        $(".customFilter").val('');       
        $('#status').val('');
        $('#vendor_id').val('');
        $('#vendor_id').multiselect('rebuild'); 
        showOrderByWo('Added On', 'added_on_th');
    }

    function showOrderByWo(head, th)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_col = $("#sort_col").val();
        var sort_tp = $("#sort_tp").val();
        if(sort_col == '')
        {
            $("#sort_col").val(head);
            $("#sort_tp").val('ASC');
            $("#"+th).addClass('sorting_asc');
        }
        else if(sort_col == head)
        {
            $("#sort_col").val(head);
            if(sort_tp == 'ASC')
            {
                $("#sort_tp").val('DESC');
                $("#"+th).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_tp").val('ASC'); 
                $("#"+th).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_col").val(head);
            $("#sort_tp").val('ASC');
            $("#"+th).addClass('sorting_asc');
        }
        var final_sort_col = $("#sort_col").val();
        var final_sort_t = $("#sort_type").val();

        submitTableWorkForm();
    }

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


// jQuery(document).ready(function(){
//   $('#vendor_id').multiSelect({
//     includeSelectAllOption: true,
//     numberDisplayed:-1,
//   });  
// })

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

  $(document).ready(function(){
    $('#vendor_id').multiselect({
       includeSelectAllOption: true,
        numberDisplayed:-1,
        nonSelectedText: 'Select Vendor',
        title: 'Select Vendor',
        enableClickableOptGroups: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true 
    });
  })

   function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#po_order_list')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllWorkOrderList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Work Order",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#po_order_list").submit();
    $("#download").val('0');
 }

</script>
