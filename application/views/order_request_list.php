<?php
$user_session_data = getSessionData();
$add_rfq = checkLabelByTask('add_rfq');
?>
<div class="">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="rfq_form" name="rfq_form" method="POST" action="<?php echo base_url().'shipping/getAllRFQList';?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitOrderRequestForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <select  id="status" name="rfq_status" onchange="submitOrderRequestForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
                                            <option value="">Select Status</option>
                                            <option value="1">Created</option>
                                            <option value="2">Submitted</option>
                                            <option value="3">Verified</option>
                                             <?php
                                              if($user_session_data->code=='super_admin'){
                                             ?>
                                            <option value="4">Send For Quotation</option>
                                            <option value="5">Quotation Received</option>
                                            <option value="6">Quotation Approved</option>
                                            <option value="7">Send For Review</option>
                                            <option value="8">Request Approved</option>
                                            <option value="9">PO Created</option>
                                        <?php }else{
                                            ?>
                                            <option value="In">Inprogess</option>
                                            <option value="7">Received Review Request</option>
                                            <option value="RA">Request Approved</option> 
                                         <?php } ?>
                                        </select>
                                        </label>
                                    </li>
                                    <li>
                                       <label class="pull-left">
                                        <label>Requisition Type</label><br>
                                         <select name="type" id="type" onchange="submitOrderRequestForm('', 1);"  class="form-control input-sm">
                                            <option value="">Select Type</option>
                                            <option value="provision">Provision</option>
                                            <option value="bonded_store">Bonded Store</option>
                                            <option value="stores">Stores</option>
                                        </select>
                                        </label>  
                                    </li>

                                    <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitOrderRequestForm('', 1);">
                                    </label>
                                    </li> 
                                               
                                    <li class="pull-right filter-s">
                                      <div class="contactSearch">
                                            <input title="Search by RFQ NO or Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitOrderRequestForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitOrderRequestForm('', 1)"></a>
                                      </div>
                                    </li>
                                    <li class="pull-right add-new-f">
                                        
<!--                                          <a id="auto_a68" onclick="showAjaxModel('Create RFQ','shipping/add_rfq_details','','','98%','full-width-model')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a> -->
                                        <?php if($add_rfq){
                                          if(!empty($opening_stock)){  
                                            ?>
                                         <a id="auto_a68" onclick="showAjaxModel('Confirmation','shipping/stock_config','rfq','','')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                        <?php }
                                          else{
                                            ?>
                                            <a id="auto_a68" onclick="showAlert()" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>

                                         <?php }
                                        }
                                        ?>
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
                    <div class="panel-body no-padding">
                        <input type="hidden" name="page" value="" id="rfq_page" />
                       
                            <div class="d-flex flex-column h-100 p-0 panel no-border">
                                    
                                        <input type="hidden" name="sort_c" id="sort_c" value="" />
                                        <input type="hidden" name="sort_t" id="sort_t" value="" />
                                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="rfq" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
                                                <th style="width:11%" width='11%' id="rfq_th" onclick="RFQshowOrderBy('RFQ No', 'rfq_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">RFQ NO</a>
                                                </th>
                                                <th width='10%' id="type_th" onclick="RFQshowOrderBy('Type', 'type_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Requisition Type</a>
                                                </th>
                                                <th width='10%' id="port_th" onclick="RFQshowOrderBy('Port', 'port_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Port</a>
                                                </th>
                                                <th width='10%' id="lead_time_th" onclick="RFQshowOrderBy('Lead Time', 'lead_time_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Lead Time</a>
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="RFQshowOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                 <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="RFQshowOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting">
                                                 <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                 <th width='10%' id="status_th" onclick="RFQshowOrderBy('Status', 'po_no_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="rfq_list_data">
                                            </tbody>
                                        </table>


                                        <div class="new-style-pagination">
                                    <div class="rfq_total_entries total_entries_static"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="rfq_new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            
                    </div>
            </div><!-- /.panel -->
            </form>
            <!--/ End table advanced -->
        </div><!-- /.col-md-12 -->
    </div>

<span id="downloadOPtion" style="display: none;">
                    <div class="exportBox"> 
                    <div class="row mb-10 mt-10">
                       <div class="col-md-4 mb-5"> <label>Updated RFQ</label> <input type="radio" checked  name="entity" class="export_cls" value="latest_rfq" /></div>
                       <div class="col-md-4 mb-5"> <label>Quotation Format</label> <input type="radio" name="entity" class="export_cls" value="for_quote" /></div>
                       </div>
                        <input type="hidden" name="ship_order_id" id="ship_order_id" value="">  
                        <div class="mt-30">
                        <a class="runner btn btn-mini btn-success btn-slideright" tabindex="0" onclick="$('.close').click();exportRFQbyID();"><span>Download</span></a></div>
                        </div>
    </span>


<div id="downloadList" style="display: none;">
        <div class="exportBox" style="padding:10px;">
         <div class="row"> 
         <div class="mt-12" id="downloadPagesId"></div>   
        <div style="text-align:right" class="mt-10">
         <a class="runner btn btn-mini btn-success btn-slideright" tabindex="0" onclick="$('.close').click();exportTable();"><span>Download</span></a>
      </div>
  </div>
     </div>
     </div>
  </div>          
</div>
<script type='text/javascript'>

function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#rfq_form')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllRFQList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download RFQ List",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}    

function submitOrderRequestForm(pageId)
{     
       $('#rfq_page').val(pageId);
       var $data = new FormData($('#rfq_form')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllRFQList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.rfq_list_data').html(obj.dataArr);
                $('.rfq_new_pagination').html(obj.pagination);
                $('.rfq_total_entries').html(obj.total_entries);
                highlightRow();
            }
        });
        return false;
    }


   $(document).ready(function(){
        submitOrderRequestForm();
       })

    $(".customFilter").keypress(function(event){
    if (event.which == 13) {
      submitOrderRequestForm();
      return false;
    }
    })
    
    function rfqsubmitPagination(pageId)
    {
       submitOrderRequestForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_c").val('Added On');
        $("#sort_t").val('ASC');
        RFQshowOrderBy('Added On', 'added_on_th');
        $(".customFilter").val('');  
        $('#auto_a69').val('25');
        $('#status').val('');
        $('#type').val('');
        submitOrderRequestForm('', 1);
    }

    function RFQshowOrderBy(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_c = $("#sort_c").val();
        var sort_t = $("#sort_t").val();
        if(sort_c == '')
        {
            $("#sort_c").val(head_title);
            $("#sort_t").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(sort_c == head_title)
        {
            $("#sort_c").val(head_title);
            if(sort_t == 'ASC')
            {
                $("#sort_t").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_t").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_c").val(head_title);
            $("#sort_t").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_sort_c = $("#sort_c").val();
        var final_sort_t = $("#sort_t").val();

        submitOrderRequestForm();
    }


  function showAlert(){
      var data = '<span><strong>Please Add / Submit Opening Stock.</strong></span>';
      $('#modal-view-datatable').modal('show');
      $('#pop_heading').html('Alert');
      $('#modal_content').html(data);
  }


  function downloadRfq($ship_order_id=''){
    $('#ship_order_id').val($ship_order_id);
     var msg =$('#downloadOPtion').html();
     bootbox.dialog({
            message: msg,
            title  : "Download",
            className: "modal-primary",
            backdrop:false,
           onEscape: false
    });
  }

 function exportRFQbyID(){
   var ship_order_id = $('#ship_order_id').val();
   var enType = $('input[name="entity"]:checked').val();
   window.location = base_url + 'shipping/download_for_quote_xls/'+ship_order_id+'/'+enType;
 } 

 $(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});


    

 function exportTable(){
    $("#download").val('1');
    $("#rfq_form").submit();
    $("#download").val('0');
 } 
</script>
