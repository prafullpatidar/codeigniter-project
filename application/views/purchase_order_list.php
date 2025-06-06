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
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker-custom.js"></script>
<link href="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.css" rel="stylesheet">

<div id="tour-11" class="header-content">
      <div class="dt-buttons pull-right" style="margin-top:-5px;">
        <a id="auto_a68" onclick="showAjaxModel('Add Ship','shipping/add_edit_ships','','','70%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
    </div>
  <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content --> 

<!-- Start body content -->
<?php
$user_session_data = getSessionData();
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
    
            <form id="po_report" class="h-100 d-flex flex-column flex-no-wrap" action="<?php echo base_url().'report/getWorkOrderList';?>" name="po_report" method="POST">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li class="record">
                                        <label class="pull-left">
                                        <label>Records Per Page</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitTableForm('', 1);" class="form-control input-sm perPage">
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
                                        <label>Stage</label><br>
                                        <select  id="status" name="status" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="">Select Stage</option>
                                            <option value="R">Raised</option>
                                            <option value="A">Accepted By Vendor</option>
                                            <option value="D">DN Created</option>
                                            <option value="I">Invoice Uploaded</option>
                                            <option value="T">Temprory Cancel</option>
                                            <option value="P">Permanent Cancel</option>
                                        </select>
                                        </label>
                                    </li>
                                    <?php 
                                    if(empty($user_session_data->shipping_company_id)){
                                    ?>
                                    <li class="scompany">                            
                                        <label>Shipping Company</label><br>
                                        <select class="form-control customFilter" name="shipping_company_id" id="shipping_company_id" class="customFilter" onchange="getAllShipsById(this.value);submitTableForm();">
                                                <option value="">Select Company</option>
                                                <?php
                                                   if($company){
                                                       foreach ($company as $row) {
                                                       echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                                       }                                       
                                                    }
                                                 ?>  
                                          </select> 
                                         
                                    </li>
                                <?php } 
                                 
                                if($user_session_data->code !='captain' && $user_session_data->code !='cook'){
                                ?>
                                 <li class="ship">                     
                                        <label>Vessel Name</label><br>
                                         <select class="form-control customFilter" name="ship_id" id="ship_id" onchange="submitTableForm()">
                                           <option value="">Select Vessel</option>  
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
                                        
                                    </li>
                                    <?php
                                     } 
                                    ?>
                                    <li>
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
                                      <li>
                                        <label class="pull-left">
                                        <label>Date Range</label><br>
                                        <input onchange="submitTableForm('', 1);" type="text" name="created_date" id="created_date" class="form-control customFilter date-range-picker-clearbtn" value="<?php echo date("m/01/Y").' - '.date("m/t/Y");?>">
                                        </label> 
                                    </li>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);"  title="Search by PO No, Order ID or RFQ No">
                                            <a href="#" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
                                      </div>
                                    </li>
                                     <li>
                                        <div class="pull-right">
                                        <label>&nbsp;</label><br>
                                        <a href="javascript:void(0)" onclick="csvDonwload()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
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
                        <input type="hidden" name="page" value="" id="page" />
                        
                        <div class="d-flex flex-column h-100 p-10 panel pt-0 mb-0">
                                        <input type="hidden" name="download" id="download" value="0"> 
                                        <input type="hidden" name="sort_column" id="sort_column" value="Added On" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="DESC" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="vendor_name_th" onclick="showOrderBy('Vendor Name', 'vendor_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vendor Name</a>
                                                </th>
                                                <th width='10%' id="ship_name_th" onclick="showOrderBy('Ship Name', 'ship_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vessel Name</a>
                                                </th>
                                                <th width='10%' id="po_no_th" onclick="showOrderBy('Po N0', 'po_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">PO No.</a>
                                                </th>
                                                 <th  width='10%' id="order_id_th" onclick="showOrderBy('Order ID', 'order_id_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Order ID</a>
                                                </th>
                                                 <th  width='10%' id="rfq_no_th" onclick="showOrderBy('RFQ No', 'rfq_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">RFQ No</a>
                                                </th>
                                                 <th  width='10%' id="amount_th" onclick="showOrderBy('Amount', 'amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Amount($)</a>
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="showOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="showOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                 <th width='10%' id="status_th" onclick="showOrderBy('Status', 'po_no_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Stage</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="work_order_lisst">
                                            </tbody>
                                        </table>

                                        <div class="new-style-pagination">
                                    <div class="total_entries_wo total_entries_static total_entries"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="wo_new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            
                    </div>
            </form>
</div>
<script type='text/javascript'>

 function submitTableForm(pageId){ 
       $('#page').val(pageId);  
       var $data = new FormData($('#po_report')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'report/getWorkOrderList',
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
            }
        });
        return false;
    }
 
 $(document).ready(function(){
    submitTableForm();
 })

    $(".customFilter").keypress(function(event){
      if(event.which == 13) {
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
        $('#auto_a69').val('25');
        $("#sort_column").val('Added On');
        $("#sort_type").val('ASC');
        $('#ship_id').val('');
        $('#status').val('');
        $('#shipping_company_id').val('');
        $('#created_date').val('');
        showOrderBy('Added On', 'added_on_th'); 
        $('#vendor_id').val(''); 
        $('.selected_vendor_counter').html('Select Vendor');   

        submitTableForm('', 1);
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

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


 function csvDonwload(){
   $('#download').val('1');
   $("#po_report").submit();
   $("#download").val('0');
 } 

function getAllShipsById(shipping_company_id){
       $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'user/getShipsByCompanyId',
            data: {'shipping_company_id':shipping_company_id},
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#ship_id').html(obj.data);
            }
        });   
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


  $(document).ready(function(){
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
                submitTableForm();
        })
        })  
function showSelectedVendorCount(totalVendorSelectedMsg)
 {
  if(totalVendorSelectedMsg>0){
      totalVendorSelectedMsg += ' Vendor Selected';
      $('.Select2CustomVendor .select2-search__field').val('').attr('placeholder','');
      $('.Select2CustomVendor .select2-selection__rendered').after('<div style="line-height: 28px; padding: 5px;" class="counter selected_vendor_counter">'+totalVendorSelectedMsg+'</div>');
  }
 }

</script>
