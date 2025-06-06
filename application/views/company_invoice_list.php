<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker-custom.js"></script>
<link href="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.css" rel="stylesheet">
<script src="<?php echo base_url().'assets/js/select2.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/select2.css'?>">
<style type="text/css">
    .select2-selection__clear{
    color: black;
}

.item_search {
    z-index:99999;
}
span.select2-container  { line-height:19px; margin-top:5px;}
span.select2-container .select2-selection {border-radius:0;min-height: 34px;}
body .new-layout ul.multiselect-container.dropdown-menu li.cntryBold a label{font-weight: bold;}
body .select2-dropdown { min-width:200px;}


    .select2-container {
      /*min-width: 100%;*/
    }

    .modal-primary span.select2-container{
        margin: 0px;
    }

    .select2-results__option {
      padding-right: 20px;
      vertical-align: middle;
    }
    .select2-results__option[aria-selected=true]:before,.select2-results__option[aria-selected=false]:before {
      content: "";
      display: inline-block;
      position: relative;
      height: 20px;
      width: 20px;
      border: 2px solid #e9e9e9;
      border-radius: 4px;
      background-color: #fff;
      margin-right: 20px;
      vertical-align: middle;
    }
    .select2-results__option[aria-selected=true]:before {
      font-family:fontAwesome;
      content: "\f00c";
      color: #fff;
      background-color: #f77750;
      border: 0;
      display: inline-block;
      padding-left: 3px;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }
    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }
    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #f77750;
        border-width: 2px;
    }
    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }
    .select2-container--open .select2-dropdown--below {
        
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);

    }
    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }
    
    .Select2Custom .select2-selection__choice{display: none!important;}

</style>
<?php
$user_session_data = getSessionData();
// print_r($user_session_data);die;
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<div id="showSuccMessage"></div>
<!-- /.header-content --> 

<!-- Start body content -->
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
<div class="body-content animated fadeIn body-content-flex vendor-page">   
    
            <form id="company_invoice_list" class="h-100 d-flex flex-column flex-no-wrap" action="<?php echo base_url().'report/getCompanyInvoiceList';?>" name="company_invoice_list" method="POST">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li class="record">
                                        <label class="pull-left">
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <label>Invoice Status</label><br>
                                        <select  id="status" name="status" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="">Select</option>
                                            <option value="created">Created</option>
                                            <option value="cn pending">CN Pending</option>
                                            <option value="incorrect invoice">Incorrect Invoice</option>
                                            <option value="resolved">Resolved</option>
                                            <option value="Partially Paid">Partially Paid</option>
                                            <option value="Advance Partially Paid">Advance Partially Paid</option>
                                            <option value="Advance Paid">Advance Paid</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                        </label>
                                    </li>
                                    <?php
                                    if(empty($user_session_data->shipping_company_id)){ 
                                    ?>
                                    <li class="scompany">
                                       
                                        <label>Shipping Company</label><br>
                                        <select class="form-control customFilter" name="shipping_company_id" id="shipping_company_id" class="customFilter" onchange="submitTableForm('', 1);">
                                                <option value="">Select Company</option>
                                                <?php
                                                   if($company){
                                                       foreach ($company as $row) {
                                                         //$selected = ($row->is_default==1) ? 'selected' : '';
                                                       echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                                       }                                       
                                                    }
                                                 ?>  
                                          </select> 
                                         
                                    </li>
                                <?php } 
                                if($user_session_data->code !='captain' && $user_session_data->code !='cook'){ 
                                ?>
                                     <li class="vessel">
                                        <label class="pull-left">
                                        <label>Vessel</label><br>
                                          <div class="inputCover Select2Custom Select2CustomVessel">
                                        <select  id="ship_id" name="ship_id[]" onchange="submitTableForm('', 1);"  class="form-control" multiple="multiple">
                                            <?php 
                                             if(!empty($ships)){
                                                foreach ($ships as $row) {
                                                 ?>
                                               <option value="<?php echo $row->ship_id;?>"><?php echo ucwords($row->ship_name)?></option>
                                                <?php 
                                                }
                                             }
                                            ?>
                                           
                                        </select>
                                    </div>
                                        </label>
                                    </li>
                                    <li >
                                     <label class="pull-left">
                                     <label>Vendors</label><br>
                                      <div class="inputCover Select2Custom Select2CustomVendor">
                                       <select  id="vendor_id" name="vendor_id[]" onchange="submitTableForm('', 1);" class="form-control" multiple="multiple">
                                        <?php
                                         if(!empty($vendors)){
                                           foreach($vendors as $row){
                                            ?>
                                            <option value="<?php echo $row->vendor_id;?>"><?php echo ucwords($row->vendor_name);?></option>
                                           <?php } 
                                         }
                                        ?>
                                        </select>
                                        </div>
                                      </label>
                                      </li>
                                  <?php } ?>
                                      <li>
                                        <label class="pull-left">
                                        <label>Date Range</label><br>
                                        <input onchange="submitTableForm('', 1);" type="text" name="created_on" id="created_date" class="form-control customFilter date-range-picker-clearbtn">
                                        </label> 
                                    </li>
                                    <li>
                                        <label class="pull-left">
                                        <label>Due Date</label><br>
                                        <input onchange="submitTableForm('', 1);" type="text" name="due_date" id="due_date" class="form-control customFilter datePicker_editPro">
                                        </label> 
                                    </li>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                     <li>
                                        <div class="pull-right">
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="csvDonwload()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);" title="Search by Invoice No, PO No or Created By">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
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
                        <input type="hidden" name="page" value="" id="inv_page" />
                        
                        <div class="d-flex flex-no-wrap flex-column h-100 p-10 panel pt-0 mb-0">
                                        <input type="hidden" name="download" id="download" value="0">
                                        <input type="hidden" name="sort_column" id="sort_column" value="Created On" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="DESC" />
                                        <div class="table-responsive-new-two vendor-invoice-list">
                                        <table id="pre_req_name_table" class="largedt white-space-nowrap table-text-ellipsis no-border table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                 <th id="company_name_th" onclick="showOrderBy('Company Name', 'company_name_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Company Name</a>
                                                </th>    
                                                <th id="ship_name_th" onclick="showOrderBy('Ship Name', 'ship_name_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Vessel Name</a>
                                                </th>
                                                <th id="inv_no_th" onclick="showOrderBy('Invoice No', 'inv_no_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Invoice No.</a>
                                                </th>
                                                 <th id="po_no_th" onclick="showOrderBy('PO No', 'po_no_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">PO No.</a>
                                                </th>
                                                <th id="vendor_th" onclick="showOrderBy('Vendor Name', 'vendor_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vendor</a>
                                                </th>
                                                <th id="total_amount_th" onclick="showOrderBy('Total Amount', 'total_amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Amount ($)</a>
                                                </th>
                                                <th id="due_date_th" onclick="showOrderBy('Due Date', 'due_date_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Due Date</a>
                                                </th>
                                                <th id="rc_amount_th" onclick="showOrderBy('Received Amount', 'rc_amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Received Amount($)</a>
                                                </th>
                                                <th id="pn_amount_th" onclick="showOrderBy('Pending Amount', 'pn_amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Pending Amount($)</a>
                                                </th>
                                                <th id="added_on_th" onclick="showOrderBy('Created On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th id="added_by_th" onclick="showOrderBy('Created By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                 <th id="status_th" onclick="showOrderBy('Status', 'status_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Invoice Status</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="invoice_list_data">
                                            </tbody>
                                        </table>
                                        </div>
                                    
                                    <div class="new-style-pagination mt-auto">
                                    <div class="total_entries total_entries_inv"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="new_pagination inv_new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                       
           
            </form>
            <!--/ End table advanced -->
        
</div>
<script type='text/javascript'>

function submitTableForm(pageId)
{    
       $('#inv_page').val(pageId);
       var $data = new FormData($('#company_invoice_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'report/getCompanyInvoiceList',
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
            }
        });
        return false;
    }
  
      $(document).ready(function(){
        submitTableForm();
      })

    $(".customFilter").keypress(function(event){
    if (event.which == 13) {
      submitTableForm();
      return false;
     }
    })
    
    function submitPagination(pageId)
    {
       submitTableForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_column").val('Created On');
        $("#sort_type").val('ASC');
        $(".customFilter").val('');
        $('#status').val('');
        $('#ship_id').val('');
        $('#auto_a69').val('25');
        $('#vendor_id').val(''); 
        $('.selected_vessel_counter').html('Select Vessel');
        $('.selected_vendor_counter').html('Select Vendor');   
        showOrderBy('Created On', 'added_on_th');

    }

    function showOrderBy(head_title, th_id)
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

        submitTableForm();
    }

  $(document).on("mouseover", '.resetbtn', function (event) {
    $(this).focus();
  });


 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        endDate: '-1d'
    });
 });


function updateInvoiceStatus(id) {
      var msg = 'Are you sure you want to mark as paid?';
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
                            beforeSend: function(){
                                $("#customLoader").show();
                            },
                            type: "POST",
                            url: base_url + 'vendor/changeInvoiceStatus',
                            cache: false,
                            data: {'id': id, 'status': 2},
                            success: function (msg) {
                                $("#customLoader").hide();
                                var obj = jQuery.parseJSON(msg);
                                $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                                submitTableForm();
                            }
                        });
                    }
                }

            }
        });
    }


$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

$(document).ready(function(){
    $('#ship_id').select2({
        ajax: {
        url: base_url + 'shipping/getVesselBySearch',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            var query = {
                search: params.term,
                page: params.page || 1,
                company_id : $('#shipping_company_id').val()                         
            }
            return query;
        },
        
        processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: data.results,
                pagination: {
                    more: (params.page * 100) < data.total_count
                }
            };
        },
            cache: false
          },
          allowHtml: true,
          allowClear: true,
          placeholder: 'Select Vessel',
          closeOnSelect: false,
      }).on("change", function(e) {
        $('.selected_vessel_counter').remove();
        var counter = $(".Select2CustomVessel .select2-selection__choice").length;
        showSelectedShipCount(counter);
     })


    $('#vendor_id').select2({
                ajax: {
                url: base_url + 'shipping/getVendorBySearch',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1                        
                    }
                    return query;
                },
                
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 100) < data.total_count
                        }
                    };
                },
                    cache: false
                  },
                  allowHtml: true,
                  allowClear: true,
                  placeholder: 'Select Vendor',
                  closeOnSelect: false,
              }).on("change", function(e) {
                $('.selected_vendor_counter').remove();
                var counter = $(".Select2CustomVendor .select2-selection__choice").length;
                showSelectedVendorCount(counter);
        })  

   })

 function showSelectedShipCount(totalShipSelectedMsg)
 {
      if(totalShipSelectedMsg>0){
          totalShipSelectedMsg += ' Vessel Selected';
          $('.Select2CustomVessel .select2-search__field').val('').attr('placeholder','');
        $('.Select2CustomVessel .select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter selected_vessel_counter">'+totalShipSelectedMsg+'</div>');
      }
 }

 function csvDonwload(){
   $('#download').val('1');
   $("#company_invoice_list").submit();
   $("#download").val('0');
  }

 function showSelectedVendorCount(totalVendorSelectedMsg)
 {

   if(totalVendorSelectedMsg>0){
      totalVendorSelectedMsg += ' Vendor Selected';
      $('.Select2CustomVendor .select2-search__field').val('').attr('placeholder','');
    $('.Select2CustomVendor .select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter selected_vendor_counter">'+totalVendorSelectedMsg+'</div>');

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