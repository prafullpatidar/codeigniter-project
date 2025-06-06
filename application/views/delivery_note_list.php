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
            <form id="note_list" name="note_list" method="POST" action="<?php echo base_url().'shipping/getAllDeliveryNoteList'?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitTableNoteFormD('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <select  id="status" name="status" onchange="submitTableNoteFormD('', 1);" class="form-control input-sm">
                                            <option value="">Select Status</option>
                                            <option value="1">Created</option>
                                            <option value="2">Receipt Signed</option>      
                                            </select>
                                    </label>
                                    </li> 
                                     <li>
                                        <label class="pull-left">
                                        <label>Invoice Created</label><br>
                                        <select  id="invoice_status" name="invoice_status" onchange="submitTableNoteFormD('', 1);" class="form-control input-sm">
                                            <option value="">Select</option>
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>      
                                            </select>
                                    </label>
                                    </li>
                                       <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableNoteFormD('', 1);">
                                    </label>
                                    </li>      

                                    <li class="pull-right filter-s">
                                      <div class="contactSearch">
                                            <input title="Search by Note No, PO No or Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableNoteFormD('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableNoteFormD('', 1)"></a>
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
                        <input type="hidden" name="page" value="" id="dn_page" />
                        
                            <div class="d-flex flex-column h-100 p-0 panel no-border">
                                    
                                
                                        <input type="hidden" name="sort_cl" id="sort_cl" value="" />
                                        <input type="hidden" name="sort_tyype" id="sort_tyype" value="" />
                                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="dn" />
                                          <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="note_no_th" onclick="DNshowOrderBy('Note No', 'note_no_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Note No.</a>
                                                </th>
                                                <th width='10%' id="po_no_th" onclick="DNshowOrderBy('Po No', 'po_no_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Po No.</a>
                                                </th>
                                                <th width='10%' id="month_th">Month/Year</th>
                                                <th width='10%' id="added_on_th" onclick="DNshowOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="DNshowOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                <th width='10%' id="invoice_th" onclick="DNshowOrderBy('Invoice Created', 'invoice_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Invoice Created</a>
                                                </th>
                                                 <th width='10%' id="status_th" onclick="DNshowOrderBy('Status', 'status_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="note_list_data">
                                            </tbody>
                                        </table>
                                        
                                    <div class="new-style-pagination">
                                    <div class="total_entries total_entries_dn"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="dn_new_pagination"></li>
                                    </ul>
                                    </div>







                                </div>
                            
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
     </div>
  </span> 
<script type='text/javascript'>

function submitTableNoteFormD(pageId, empty_sess=0)
{    
      $('#dn_page').val(pageId); 
       var $data = new FormData($('#note_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllDeliveryNoteList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.note_list_data').html(obj.dataArr);
                 $('.dn_new_pagination').html(obj.pagination);
                 $('.total_entries_dn').html(obj.total_entries);
                 highlightRow();
            }
        });
        return false;
    }

   $(document).ready(function(){
     submitTableNoteFormD();
   }) 

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitTableNoteFormD();
          return false;
        }
    })
    
    function dnsubmitPagination(pageId)
    {
       submitTableNoteFormD(pageId);
    }

    function resetFilter()
    {
        $("#sort_cl").val('Added On');
        $("#sort_tyype").val('ASC');
        $(".customFilter").val('');
        $('#auto_a69').val('25');
        $('#status').val('');
        $('#invoice_status').val('');        
        DNshowOrderBy('Added On', 'added_on_th');
    }


    function DNshowOrderBy(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var sort_cl = $("#sort_cl").val();
        var sort_tyype = $("#sort_tyype").val();
        if(sort_cl == '')
        {
            $("#sort_cl").val(head_title);
            $("#sort_tyype").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(sort_cl == head_title)
        {
            $("#sort_cl").val(head_title);
            if(sort_tyype == 'ASC')
            {
                $("#sort_tyype").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#sort_tyype").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#sort_cl").val(head_title);
            $("#sort_tyype").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_sort_cl = $("#sort_cl").val();
        var final_sort_tyype = $("#sort_tyype").val();

        submitTableNoteFormD();
    }
  
 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
}); 

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

 function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#note_list')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllDeliveryNoteList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Delivery Notes",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#note_list").submit();
    $("#download").val('0');
 }   

</script>
