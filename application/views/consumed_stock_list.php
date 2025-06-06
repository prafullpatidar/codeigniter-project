<?php 
$addstockused = checkLabelByTask('add_stock_used');
$addclosingstock = checkLabelByTask('add_closing_stock');
$user_session_data = getSessionData();
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
            <form id="note_list_1" name="note_list" method="POST" action="<?php echo base_url().'shipping/getAllConsumedStockList'?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitTableConsumedStockForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <label>Type</label><br>
                                        <select  id="type" name="type" onchange="submitTableConsumedStockForm('', 1);" class="form-control input-sm">
                                            <option value="">Select Type</option>
                                            <option value="stock_used">Used Stock</option>
                                            <option value="closing_stock">Closing Stock</option>   
                                            </select>
                                    </label>
                                    </li>  
                                     <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableConsumedStockForm('', 1);">
                                    </label>
                                    </li>     
                                              
                                    <li class="pull-right filter-s">                                       
                                      <div class="contactSearch">
                                            <input title="Search by Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableConsumedStockForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableConsumedStockForm('', 1)"></a>
                                      </div>
                                    </li>
                                    <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                    </li>
                                <?php 
                                  if($addstockused || $addclosingstock){
                                    ?>
                                    <li class="pull-right add-new-f">
                                        <a class="btn btn-success" href="javascript:void(0)" onclick="showAjaxModel('Confirmation','shipping/stock_config','consumed_stock','','98%');">Add New</a>
                                    </li>
                                    <?php
                                     }
                                    ?>
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
                    
                        <input type="hidden" name="page" value="" id="cstock_page" />
                             <div class="d-flex flex-column h-100 p-0 panel no-border">
                                    
                                        <input type="hidden" name="soort_column" id="soort_column" value="Date" />
                                        <input type="hidden" name="soort_type" id="soort_type" value="DESC" />
                                        <input type="hidden" name="ship_id" id="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="cs">
                                        <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='10%' id="type_th" onclick="showControlOrderBy('Type', 'type_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Type</a>
                                                </th>
                                                <th width='10%' id="total_price_th" onclick="showControlOrderBy('Total Price', 'total_price_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Total Price($)</a>
                                                </th>
                                                <th width='10%' id="delivery_date_th">
                                                    Month/Year
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="showControlOrderBy('Created On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="showControlOrderBy('Created By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                
                                                <th width="3%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="consumed_stock_list_data">
                                            </tbody>
                                        </table>


                                        <div class="new-style-pagination">
                                            <div class="total_entries total_entries_cs"></div>
                                            <ul class="pagination pagination-sm">
                                                <li class="new_pagination cstock_new_pagination"></li>
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

function submitTableConsumedStockForm(pageId)
{     
      $('#cstock_page').val(pageId);
       var $data = new FormData($('#note_list_1')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllConsumedStockList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.consumed_stock_list_data').html(obj.dataArr);
                $('.cstock_new_pagination').html(obj.pagination);
                $('.total_entries_cs').html(obj.total_entries);
            }
        });
        return false;
    }
  
  $(document).ready(function(){
    submitTableConsumedStockForm();
  })

  $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitTableConsumedStockForm();
          return false;
        }
    })
    
  function cssubmitPagination(pageId){
       submitTableConsumedStockForm(pageId);
   }

  function resetFilter()
    {
        $("#soort_column").val('Created On');
        $("#soort_type").val('ASC');
        $(".customFilter").val('');        
        $('#auto_a69').val('25');
        $('#type').val('');
        showControlOrderBy('Created On', 'added_on_th');
    }

  function showControlOrderBy(head_title, th_id)
    {
        $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        var soort_column = $("#soort_column").val();
        var soort_type = $("#soort_type").val();
        if(soort_column == '')
        {
            $("#soort_column").val(head_title);
            $("#soort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        else if(soort_column == head_title)
        {
            $("#soort_column").val(head_title);
            if(soort_type == 'ASC')
            {
                $("#soort_type").val('DESC');
                $("#"+th_id).addClass('sorting_desc');
            }
            else 
            {
                $("#soort_type").val('ASC'); 
                $("#"+th_id).addClass('sorting_asc');  
            }
        }
        else 
        {
            $("#soort_column").val(head_title);
            $("#soort_type").val('ASC');
            $("#"+th_id).addClass('sorting_asc');
        }
        var final_soort_column = $("#soort_column").val();
        var final_soort_type = $("#soort_type").val();

        submitTableConsumedStockForm();
    }

$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

 function downloadCsv(){
        $("#downloadPagination").val('1');
        var $data = new FormData($('#note_list_1')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getAllConsumedStockList',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg =$('#downloadList').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Inventory List",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#note_list_1").submit();
    $("#download").val('0');
 }   

</script>
