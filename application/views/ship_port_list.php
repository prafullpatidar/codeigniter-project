<?php 
$user_session_data = getSessionData();
$add_next_port = checkLabelByTask('add_next_port');
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
            <form id="port_list" name="port_list" method="POST" action="<?php echo base_url().'shipping/getallshipportlist';?>">
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
                                        <select  id="auto_a69" name="perPage" onchange="submitPortTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <label>Arriving Date</label><br>
                                        <input type="text" name="arriving_date" id="arriving_date" class="form-control customFilter datePicker_editPro" onchange="submitPortTableForm('', 1);">
                                         </label>
                                    </li>
                                    <li>
                                         <label class="pull-left">
                                        <label>Departure Date</label><br>
                                        <input type="text" name="departure_date" id="departure_date" class="form-control customFilter datePicker_editPro" onchange="submitPortTableForm('', 1);">
                                         </label>
                                    </li> 
                                     <li>
                                      <label class="pull-left">
                                        <label>Agent Name</label><br>
                                        <select class="form-control" id="agent_id" name="agent_id" onchange="submitPortTableForm('', 1);">
                                            <option value="">Select Agent</option>
                                             <?php
                                              if(!empty($agent_list)){
                                                foreach ($agent_list as $row) {
                                                  ?>
                                                  <option value="<?php echo $row->agent_id;?>"><?php echo ucwords($row->name);?></option>
                                                <?php }
                                              }
                                             ?>  
                                        </select>
                                        </label>
                                     </li>            
                                    <li class="pull-right filter-s">
                                     
                                      <div class="contactSearch">
                                            <input title="Search by Next Port Name, Next Port Country, Previous Port Name or Previous Port Country " name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitPortTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitPortTableForm('', 1)"></a>
                                      </div>
                                    </li>
                                     <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="downloadCsv()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li>

                                    <?php if($add_next_port){?>
                                    <li class="pull-right add-new-f">
                                        
                                         <a id="auto_a68" onclick="showAjaxModel('Add Next Port','shipping/add_edit_port','','<?php echo $ship_id;?>','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                    </li>
                                <?php } ?>
                                <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
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
                        
                        <div class="d-flex flex-no-wrap flex-column h-100 p-0 panel no-border">
                                    
                                        <input type="hidden" name="sort_column" id="sort_column" value="Date" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="DESC" />
                                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="port" />
                                        <input type="hidden" name="deleted_ids" id="del_ids" value="" />
                                        <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                    <div class="table-responsive-new-two vendor-invoice-list" style="overflow-y: scroll;">
                                        <table id="port_table" class="largedt white-space-nowrap table-text-ellipsis no-border table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead>
                                                <tr>
                                                <!-- <th width='5%'>
                                                    <input type="checkbox" class="form-control" name="check_all_port" id="check_all_port">
                                                </th>    -->
                                                <th width='10%' style="width:10%" id="port_name_th" onclick="showOrderBy('Port Name', 'port_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Next Port Name</a>
                                                </th>
                                                <th width='10%' id="port_country_th" onclick="showOrderBy('Port Country', 'port_country_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Next Port Country</a>
                                                </th>
                                                <th  width='10%' id="date_th" onclick="showOrderBy('Date', 'date_th');" class="rmv_cls sorting sorting_desc">
                                                    <a href="javascript:void(0);">Next Port Arriving Date</a>
                                                </th>
                                                <th  width='10%' id="agent_name_th" onclick="showOrderBy('Agent Name', 'agent_name_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Agent Name</a>
                                                </th>
                                                 <th width='10%' id="prev_port_th" onclick="showOrderBy('Previous Port', 'prev_port_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Previous Port Name</a>
                                                </th>
                                                <th width='10%' id="prev_port_country_th" onclick="showOrderBy('Previous Country Port', 'prev_port_country_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Previous Port Country</a>
                                                </th>
                                                <th width='10%' id="dep_date_th" onclick="showOrderBy('Departure Date', 'dep_date_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Previous Port Departure Date</a>
                                                </th>
                                                <th width='10%' id="added_on_th" onclick="showOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="added_by_th" onclick="showOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                <th width="2%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="pc_list_data">
                                            </tbody>
                                        </table>
                                    </div>
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

  <span id="downloadOPtion" style="display: none;">
                    <div class="exportBox" style="padding:10px;">
                     <div class="row"> 
                     <div class="mt-12" id="downloadPagesId"></div>   
      <div style="text-align:right" class="mt-10">
      <a class="runner btn btn-mini btn-success btn-slideright" tabindex="0" onclick="$('.close').click();exportTable();"><span>Download</span></a>
      </div>
     </div>
  </span>   
</div>
<script type='text/javascript'>

function submitPortTableForm(pageId)
{      
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        var $data = new FormData($('#port_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getallshipportlist',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.pc_list_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }

   $(document).ready(function(){
     submitPortTableForm();
   })

    $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitPortTableForm();
          return false;
        }
    })
    
    function portsubmitPagination(pageId)
    {
       submitPortTableForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_column").val('Date');
        $("#sort_type").val('ASC');
        showOrderBy('Date', 'date_th');
        $(".customFilter").val('');
        $('#auto_a69').val('25');  
        $('#agent_id').val('');      
        submitPortTableForm('', 1);
    }

    // jQuery(document).ready(function () {
    //     submitPaginationport();
    // });

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

        submitPortTableForm();
    }


$(".ui-tabs-panel .header-fixed-new #check_all_port").click(function(){
     
     $("input.check_all_td").prop('checked', $(this).prop('checked'));
        if(this.checked){
          $('#delete_all').css('display','none');
        }else{
            $('#delete_all').css('display','none');
        }

});





$(document).on('click','#port_table tbody .check_all_td', function(e){
    $('#delete_all').css('display','none');
    var checked_ids= new Array();
    $('#port_table .check_all_td').each(function(){
        if(this.checked){
            $('#delete_all').css('display','none');
            checked_ids.push($(this).val());
        }
     });

var str = checked_ids.join();
  $('#del_ids').val(str);
  //console.log(deleted_ids);
});

console.log($('#del_ids').attr('value'));
function updateGroupStatusBoxNew(ids, status, changeStatusPath,admin_ids=''){

    if (ids != '' && changeStatusPath != '') {
        if (status == 2) {
            var msg = 'Are you sure you want to activate selected entities?';
        }else if(status==1) {
            var msg = 'Are you sure you want to delete selected entities?';
        }else if(status==3) {
            var msg = 'Are you sure you want to send invoice email to selected entities?';
        }else {
            var msg = 'Are you sure you want to deactivate selected entities?';
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
                            data: {'ids': ids, 'status': status,'admin_ids':admin_ids},
                            success: function () {
                             //location.reload();
                            }
                        });
                    }
                }

            }
        });
    }
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
        var $data = new FormData($('#port_list')[0]);
        $("#downloadPagination").val('0');
        $.ajax({
            type: "POST",
            cache: false,
            data: $data,
            dataType : 'JSON',
            processData: false,
            contentType: false,
            url: base_url + 'shipping/getallshipportlist',
            beforeSend: function(){$("#customLoader1").show();},
            success: function (resData) {
                $('#exportPageNo').val(1);
                $("#customLoader1").hide();
                $('#downloadPagesId').html(resData.htmlData);
                $('#totalExportPages').val(resData.countdata);
                var msg = $('#downloadOPtion').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download Next Port",
                    className: "modal-primary",
                    backdrop:false,
                    onEscape: false
                });
            }
        });
}

 function exportTable(){
    $("#download").val('1');
    $("#port_list").submit();
    $("#download").val('0');
 }

</script>
