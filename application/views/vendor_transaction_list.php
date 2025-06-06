<script src="<?php echo base_url().'assets/js/select2.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/select2.css'?>">
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker-custom.js"></script>
<link href="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-daterangepicker/daterangepicker_customize.css" rel="stylesheet">
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

<!-- Start page header -->
<div id="" class="header-content">
  <div class="clr">
  </div>
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
    
            <form id="invoice_transaction_list" class="h-100 d-flex flex-column flex-no-wrap" action="<?php echo base_url().'vendor/getAlltransationList';?>" name="invoice_transaction_list" method="POST">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
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
                                        <select  id="status" name="status" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="">Select</option>
                                            <option value="V">Verified</option>
                                            <option value="P">Pending</option>
                                        </select>
                                        </label>
                                    </li>        
                                     <li class="vessel">
                                        <label class="pull-left">
                                        <label>Vessel</label><br>
                                          <div class="inputCover Select2Custom Select2CustomVessel">
                                        <select  id="ship_id" name="ship_id[]" onchange="submitTableForm('', 1);"  class="form-control input-sm" multiple="multiple">
                                           <!--  <option value="">Select Vessel</option> -->
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
                                 <li>
                                    <label class="pull-left">
                                    <label>Date</label><br>
                                    <input onchange="submitTableForm('', 1);" type="text" name="created_date" id="created_date" class="form-control customFilter date-range-picker-clearbtn">
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
                                            <input id="auto_a72" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);" title="Search by Invoice No, PO No or Total Amount">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
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
                        <input type="hidden" name="page" value="" id="inv_page" />
                        
                        <div class="d-flex flex-no-wrap flex-column h-100 p-10 panel pt-0 mb-0">
                                        <input type="hidden" name="download" id="download" value="0">
                                        <input type="hidden" name="sort_column" id="sort_column" value="" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="" />
                                        <div class="table-responsive-new-two vendor-invoice-list">
                                        <table id="pre_req_name_table" class="largedt white-space-nowrap table-text-ellipsis no-border table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead>
                                                <tr>
                                                 <th id="vessel_name_th" onclick="showOrderBy('Vessel Name', 'vessel_name_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Vessel Name</a>
                                                </th>  
                                                <th id="trans_id_th" onclick="showOrderBy('Trans ID', 'trans_id_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Transaction ID</a>
                                                </th>  
                                                <th id="date_th" onclick="showOrderBy('Date', 'date_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Date</a>
                                                </th>
                                                <th id="inv_no_th" onclick="showOrderBy('Invoice No', 'inv_no_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">Invoice No.</a>
                                                </th>
                                                 <th id="po_no_th" onclick="showOrderBy('PO No', 'po_no_th');" class="rmv_cls sorting " >
                                                    <a href="javascript:void(0);">PO No.</a>
                                                </th>
                                                <th id="total_amount_th" onclick="showOrderBy('Total Amount', 'total_amount_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Amount($)</a>
                                                </th>
                                                <th id="discription_th" onclick="showOrderBy('Discription', 'discription_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Description</a>
                                                </th>
                                                <th id="dc_th" onclick="showOrderBy('Document', 'dc_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Document</a>
                                                </th>
                                                <th id="status_th" onclick="showOrderBy('Status', 'status_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th ></th>
                                                </tr>
                                            </thead>
                                            <tbody class="invoice_transaction_data">
                                            </tbody>
                                        </table>
                                            </div>
                                    <div class="new-style-pagination">
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
       var $data = new FormData($('#invoice_transaction_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'vendor/getAlltransationList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.invoice_transaction_data').html(obj.dataArr);
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
        $("#sort_column").val('');
        $("#sort_type").val('');
        $(".customFilter").val('');
        $('#type').val('');
        $('#auto_a69').val('25');
        $('#ship_id').val(''); 
        $('#status').val(''); 
        $('.selected_vessel_counter').html('Select Vessel');   
        showOrderBy();

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

 $(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

 function csvDonwload(){
   $('#download').val('1');
   $("#invoice_transaction_list").submit();
   $("#download").val('0');
  }

 $(document).ready(function(){
    $('#ship_id').select2({
                ajax: {
                url: base_url + 'shipping/getVesselBySearch',
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
                  placeholder: 'Select Vessel',
                  closeOnSelect: false,
              }).on("change", function(e) {
                $('.selected_vessel_counter').remove();
                var counter = $(".Select2CustomVessel .select2-selection__choice").length;
                showSelectedShipCount(counter);
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

 function transVerify(id){
   if (id != ''){
        bootbox.dialog({
            message: 'Are you sure you want to verify this transaction ?',
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
                            url: base_url + 'vendor/verify_transaction',
                            cache: false,
                            data: {'id': id},
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                }

            }
        });
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