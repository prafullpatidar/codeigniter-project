<?php 
$user_session_data = getSessionData();
$add_condemned_stock = checkLabelByTask('add_condemned_stock');
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
<div id="showSuccMessage"></div>
<div class="body-content animated fadeIn body-content-flex">  
    
            <form class="h-100 d-flex flex-column flex-no-wrap" id="csr_list" name="csr_list" method="POST" action="<?php echo base_url().'Report/getAllCondemnedStockReportData'?>">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        
                                        <label>Records Per Page</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitCSRTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                       
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
                                    <?php
                                    if(empty($user_session_data->shipping_company_id)){ 
                                    ?>
                                     <li class="scompany">
                                        
                                        <label>Shipping Company</label><br>
                                        <select class="form-control customFilter" name="shipping_company_id" id="company_id" class="customFilter" onchange="getAllShipsById(this.value);">
                                                <option value="">Select Company</option>
                                                <?php
                                                   if($company){
                                                       foreach ($company as $row) {
                                                        // $selected = ($row->is_default==1) ? 'selected' : '';
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
                                   <select class="form-control customFilter" name="ship_ids" id="ship_idss" onchange="submitCSRTableForm();">
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
                                   <?php }?>
                                    <li class="ship">
                                        
                                        <label>Month</label><br>
                                         <select class="form-control customFilter" name="month" id="month" onchange="submitCSRTableForm()">
                                           <option value="">Select Month</option> 
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
                                            <option value="">Select Year</option>
                                            <?php
                                                for ($i = 0; $i <= 4; $i++) {
                                                   $year= date('Y', strtotime("-$i year"));
                                                   $selected = ($dataArr['year'] == $year)?'selected':'';
                                                   echo '<option value="'.$year.' "'.$selected.'>'.$year.'</option>';
                                                 }?>
                                          </select>
                                          
                                    </li>
                                    <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitSummaryForm('', 1);">
                                    </label>
                                    </li>
                                    <?php
                                    if($add_condemned_stock && $visible){ 
                                    ?>
                                   <!--  <li>
                                        <label>&nbsp;</label><br>
                                         <a id="alert_box" onclick="showAjaxModel('Generate Condemned Stock Report','report/report_config/codemned_report','','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>generate report</span></a>
                                    </li> -->
                                <?php } ?>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <li>
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input name="keyword" id="auto_a72" title="Search by Created By" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitCSRTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitCSRTableForm('', 1)"></a>
                                      </div>
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
                    
                        <input type="hidden" name="page" value="1" id="page" />

                        <div class="d-flex flex-column h-100 p-10 panel pt-0">
                            
                                        <input type="hidden" name="sort_column" id="sort_column" value="Date" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="DESC" />
                                        <input type="hidden" name="ship_id" value="<?php echo $shipId;?>">
                                        <input type="hidden" name="prefix_label" id="prefix_label" value="" />
                                        <input type="hidden" value="0" name="download" id="download" />
                                         <input type="hidden" value="0" name="downloadPagination" id="downloadPagination" />
                                         <input type="hidden" name="exportPageNo" id="exportPageNo" />
                                        <input type="hidden" name="totalExportPages" id="totalExportPages" />
                                        <table id="csr_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
                                                <th style="width:10%" width='1%' id="ship_name_th" onclick="showOrderBy('Ship Name', 'ship_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Vessel Name</a>
                                                </th>
                                                <th style="width:10%" width='10%' id="month_th" onclick="showOrderBy('Month', 'month_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Month</a>
                                                </th>
                                                <th  width='10%' id="year_th" onclick="showOrderBy('Year', 'year_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Year</a>
                                                </th>
                                                <th  width='10%' id="total_th" onclick="showOrderBy('Total', 'total_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Total Amount($)</a>
                                                </th>
                                                 <th width='10%' id="created_on_th" onclick="showOrderBy('Created On', 'created_on_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created On</a>
                                                </th>
                                                <th width='10%' id="created_by_th" onclick="showOrderBy('Created By', 'created_by_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Created By</a>
                                                </th>
                                                <th width='10%' id="status_th" onclick="showOrderBy('Status', 'status_th');" class="rmv_cls sorting">
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
                                
                        </div><!-- /.panel-body -->
                    
            </div><!-- /.panel -->
            </form>
            <!--/ End table advanced -->

         <span id="downloadOPtion" style="display: none;">
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
 $(".customFilter").change(function(event){
      submitCSRTableForm();
    })

function submitCSRTableForm(pageId)
{      
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val(1);}
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
                $('#ship_idss').html(obj.data);
            }
        });   
   } 

function resetFilter()
    {
       
        $(".customFilter").val('');
        $('#status').val('');
        $('#auto_a69').val('25');
        submitCSRTableForm('', 1);
    }
    
    function portsubmitPagination(pageId)
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
                                 $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                               submitCSRTableForm();
                              }
                            }
                        });
                    }
                }

            }
        });
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

        submitCSRTableForm();
    }

     $(".customFilter").keypress(function(event){
        if (event.which == 13) {
          submitCSRTableForm();
          return false;
        }
    })

  function submitGetAllShipsform(){
   submitCSRTableForm(); 
  }    

  
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
                var msg =$('#downloadOPtion').html();
                bootbox.dialog({
                    message: msg,
                    title: "Download",
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

  jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

</script>
