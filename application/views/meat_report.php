<?php 
$user_session_data = getSessionData();
?>
<style>
body{
    overflow: hidden;
}
</style>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <h2><span class="icon"><i class="fas fa-user"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<div id="showSuccMessage"></div>
<!-- /.header-content -->
<!-- Start body content -->
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg)){
    ?><div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg)){
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
?>    
<div class="body-content animated fadeIn body-content-flex">  
            <form id="meat_report" name="meat_report" method="POST" action="<?php echo base_url().'report/getallmeatReport';?>">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        
                                        <label>Records Per Page</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        
                                    </li>
                                    <?php
                                    if(empty($user_session_data->shipping_company_id)){ 
                                    ?>
                                    <li class="scompany">
                                        <label class="pull-left">
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
                                        <label class="pull-left">
                                        <label>Vessel</label><br>
                                         <select class="form-control customFilter" name="ship_id" id="ship_idss" onchange="submitTableForm()">
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
                                          </label>
                                    </li>
                                <?php } 
                                ?>
                                    <li class="ship">
                                        <label class="pull-left">
                                        <label>Month</label><br>
                                         <select class="form-control customFilter" name="month" id="month" onchange="submitTableForm()">
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
                                          </label>
                                    </li>
                                    <li class="ship">
                                        <label class="pull-left">
                                        <label>Year</label><br>
                                         <select class="customFilter form-control" name="year" id="year" onchange="submitTableForm()">
                                            <option value="">Select Year</option>
                                            <?php
                                                for ($i = 0; $i <= 3; $i++) {
                                                   $year= date('Y', strtotime("-$i year"));
                                                   $selected = ($dataArr['year'] == $year)?'selected':'';
                                                   echo '<option value="'.$year.' "'.$selected.'>'.$year.'</option>';
                                                 }?>
                                          </select>
                                          </label>
                                    </li>
                                     <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                      </li>
                                      <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="downloadCsv()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li> 
<!--                                     <li>
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" title="Search by Vessel Name or Created by" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
                                      </div>
                                    </li> -->
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
                                                                
                                        <input type="hidden" name="sort_column" id="sort_column" value="" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="" />
                                        <input type="hidden" value="0" name="download" id="download" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
                                         <th width='10%' style="width:10%" id="ship_name_th" onclick="showOrderBy('Ship Name', 'ship_name_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Ship Name</a>
                                                </th>
                                         <th width='10%' id="month_th" onclick="showOrderBy('Month', 'month_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Month</a>
                                                </th>
                                                <th  width='10%' id="year_th" onclick="showOrderBy('Year', 'year_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Year</a>
                                                </th>
                                                <th  width='10%' id="opning_th" class="">Opening/Received Qty
                                                </th>
<!--                                                  <th width='10%' id="received_th" onclick="showOrderBy('Received Qty', 'received_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Received Qty</a>
                                                </th>
                                                <th width='10%' id="total_th" onclick="showOrderBy('Total Qty', 'total_th');" class="rmv_cls sorting" >
                                                    <a href="javascript:void(0);">Total Qty</a>
                                                </th> -->
                                                <th width='10%'>
                                                    Closing Qty</a>
                                                </th>
                                                <th width='10%'>
                                                  Consumed Qty
                                                </th>   
                                            </thead>
                                            <tbody class="meat_list_data">
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
                    </span>
        
</div>
<script type='text/javascript'>

function submitTableForm(pageId)
{      
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        var $data = new FormData($('#meat_report')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'report/getallmeatReport',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.meat_list_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }

   $(document).ready(function(){
     submitTableForm();
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
        $('#shipping_company_id').val('');
        $('#ship_idss').val('');
        $('#month').val('');
        $('#year').val('');
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


$(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();

 function downloadCsv(){
    $("#download").val('1');
    $("#meat_report").submit();
    $("#download").val('0');
 }

</script>
