<script src="<?php echo base_url().'assets/js/bootstrap-multiselect.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-multiselect.css'?>">
<?php $user_session_data = getSessionData();
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
            <form id="note_list_invoice" name="note_list" method="POST" action="<?php echo base_url().'shipping/getAllInvoiceList';?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitTableInvoiveForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        </label>
                                    </li>
                                      <li>
                                        <label class="pull-left">
                                        <label>Status</label><br>
                                        <select  id="status" name="status" onchange="submitTableInvoiveForm('', 1);" class="form-control input-sm">

                                            <option value="">Select Status</option>
                                            <option value="Created">Created</option>
                                            <option value="CN Pending">CN Pending</option>  
                                            <option value="Incorrect Invoice">Incorrect Invoice</option>   
                                            <option value="Resolved">Resolved</option>  
                                            <option value="Partially Paid">Partially Paid</option>
                                            <option value="Paid">Paid</option> 
                                            </select>
                                    </label>
                                    </li>  
                                    <li>
                                     <label class="pull-left">
                                     <label>Vendors</label><br>
                                       <select  id="vendor_id" name="vendor_id[]" onchange="submitTableInvoiveForm('', 1);" class="form-control input-sm" multiple="">
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
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableInvoiveForm('', 1);">
                                    </label>
                                    </li>       
                                    <li class="pull-right filter-s">
                                      <div class="contactSearch">
                                            <input title="Search by Invoice No, Po No, Note No or Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableInvoiveForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableInvoiveForm('', 1)"></a>
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
            <div class="panel panel-default shadow no-overflow no-border">
            <div class="flex-content mb-10">
                        <input type="hidden" name="page" value="" id="inv_page" />
                        
                        <div class="d-flex flex-column h-100 p-0 panel no-border">
                                
                                        <input type="hidden" name="sort_columnn" id="sort_columnn" value="Date" />
                                        <input type="hidden" name="sort_typee" id="sort_typee" value="DESC" />
                                        <input type="hidden" name="ship_id" id="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="inv">
                                          <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="invoice_no_th" onclick="InvshowOrderBy('Invoice No', 'invoice_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Invoice No.</a>
                                                </th>
                                                 <th style="width:10%" width='10%' id="po_no_th" onclick="InvshowOrderBy('Po No', 'po_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">PO No.</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="note_no_th" onclick="InvshowOrderBy('Note No', 'note_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Note No.</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="vendor_th" onclick="InvshowOrderBy('Vendor Name', 'vendor_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vendor</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="amount_th" onclick="InvshowOrderBy('Invoice Amount', 'amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Amount ($)</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="invoice_date_th" onclick="InvshowOrderBy('Invoice Date', 'invoice_date_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Invoice Date</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="due_date_th" onclick="InvshowOrderBy('Due Date', 'due_date_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Due Date</a>
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="InvshowOrderBy('Created On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="InvshowOrderBy('Created By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                 <th width='10%' id="status_th" onclick="InvshowOrderBy('Status', 'status_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="invoice_list_data">
                                            </tbody>
                                        </table>
                                    
                                    <div class="new-style-pagination">
                                    <div class="total_entries total_entries_inv"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="new_pagination inv_new_pagination"></li>
                                    </ul>
                                    </div>




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

function submitTableInvoiveForm(pageId)
{    
       $('#inv_page').val(pageId);
       var $data = new FormData($('#note_list_invoice')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllInvoiceList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.invoice_list_data').html(obj.dataArr);
                $('.inv_new_pagination').html(obj.pagination);
                $('.total_entries_inv').html(obj.total_entries);
                highlightRow();
            }
        });
        return false;
    }
  
  $(document).ready(function(){
    submitTableInvoiveForm();
  })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitTableInvoiveForm();
          return false;
        }
    })
    
    function invsubmitPagination(pageId)
    {
       submitTableInvoiveForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_columnn").val('Created On');
        $("#sort_typee").val('ASC');
        $(".customFilter").val('');  
        $('#auto_a69').val('25');
        $('#status').val('');
        $('#vendor_id').val('');  
        $('#vendor_id').multiselect('rebuild');            
        InvshowOrderBy('Created On', 'added_on_th');
    }

    function InvshowOrderBy(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_columnn = $("#sort_columnn").val();
        var sort_typee = $("#sort_typee").val();
        if(sort_columnn == '')
        {
            $("#sort_columnn").val(head_title);
            $("#sort_typee").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(sort_columnn == head_title)
        {
            $("#sort_columnn").val(head_title);
            if(sort_typee == 'ASC')
            {
                $("#sort_typee").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_typee").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_columnn").val(head_title);
            $("#sort_typee").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_sort_columnn = $("#sort_columnn").val();
        var final_sort_typee = $("#sort_typee").val();

        submitTableInvoiveForm();
    }

function updateStatusBoxI(id, status, title, changeStatusPath,changeStatusFor) {

    if (id != '' && title != '' && changeStatusPath != '') {
        if (status == 1) {
            var wd = (changeStatusFor == 'event' || changeStatusFor == 'task')?'delete':'deactivate';
            var msg = 'Are you sure you want to '+wd+' "' + title +'"?';
        }else if (status == 2) {
            var msg = 'Are you sure you want to Update "' + title +'" ?';
        } else if (status == 3) {
            var msg = 'Are you sure you want to Delete "' + title + '" order?<br /> If you delete this order then it will be deleted permanently.';
        }else if (status == 4) {
            var msg = 'Are you sure you want to Delete "' + title + '" customer order?<br /> If you delete this customer order then it will be deleted permanently.';
        } else if(status==5){
            var msg = 'Are you sure you want to remove "' + title +'"?';
        } else if(status==6){
            var msg = 'Are you sure you want to deactivate "' + title +'"?';
        } else if(status==7){
            var msg = 'Are you sure you want to activate "' + title +'"?';
        } else {
            var msg = 'Are you sure you want to activate "' + title +'"?';
        }
        bootbox.dialog({
            message: msg,
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
                            url: base_url + changeStatusPath,
                            cache: false,
                            data: {'id': id, 'status': status},
                            success: function () {
                                submitTableInvoiveForm();
                            }
                        });
                    }
                }

            }
        });
    }
}

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

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
        var $data = new FormData($('#note_list_invoice')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllInvoiceList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Invoice List",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#note_list_invoice").submit();
    $("#download").val('0');
 }

   
</script>
