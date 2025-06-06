<?php  $user_session_data = getSessionData();
$add_company = checkLabelByTask('add_ship');
?>
<style>
body{
    overflow: hidden;
}
</style>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <div class="dt-buttons pull-right" style="margin-top:-5px;">
        <?php if(!empty($checkEditPermission)){ ?>
        <a id="auto_a68" onclick="showAjaxModel('Add Shipping Company','shipping/add_edit_company','','','')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
        <?php } ?>
    </div>
  <h2><span class="icon"><i class="fas fa-user"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
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
            <form class="h-100 d-flex flex-column flex-no-wrap" id="company_list" name="company_list" method="POST" action="<?php echo base_url(); ?>shipping/getAllshippingCompany">
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
 
                                    <li class="status">
                                       
                                        <label>Status</label><br>
                                        <select  id="status" name="status" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="A" selected="selected">Active</option>
                                            <option value="D">Inactive</option>
                                        </select>
                                       
                                    </li>
                                    <li>
                                    <label class="pull-left">
                                     <label>Created On</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                    </label>
                                    </li>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <?php
                                     if($add_company){
                                    ?>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                             <a id="auto_a68" onclick="showAjaxModel('Add Shipping Company','shipping/add_edit_company','','','70%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                        </div>
                                    </li>
                                    <?php } 
                                    ?>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" title="Search by Name, Customer ID, Country, State, City, Zipcode or Address" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
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
                <div class="clearfix"></div>
                </div>
            </div>
            <!-- filter head end -->
            <!-- Start table advanced -->
            
                        
                        <div class="flex-content mb-10">
                        <input type="hidden" name="page" value="" id="page" />
                            
                                <div class="d-flex flex-column h-100 p-10 panel pt-0">
                                    
                                        <input type="hidden" name="sort_column" id="sort_column" value="Customer" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        <input type="hidden" name="download" id="download" value="0">
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                  <th width="5%" id="entity_type_th" >
                                                        Logo
                                                    </th>
                                                    <th width="20%" id="customer_th" onclick="showOrderBy('Customer', 'customer_th');" class="rmv_cls sorting sorting_asc">
                                                        <a href="javascript:void(0);">Name</a>
                                                    </th>
                                                      <th width="10%" id="customer_th" onclick="showOrderBy('Customer Id', 'customer_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">Customer ID</a>
                                                    </th>  
                                                    <th width="10%" id="country_th" onclick="showOrderBy('Country', 'country_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">Country</a>
                                                    </th>            
                                                    <th width="10%" id="state_th" onclick="showOrderBy('State', 'state_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">State</a>
                                                    </th>
                                                     <th width="10%" id="city_th" onclick="showOrderBy('City', 'city_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">City</a>
                                                    </th>
                                                     <th width="10%" id="zip_th" onclick="showOrderBy('Zip', 'zip_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">Zipcode</a>
                                                    </th>
                                                    <th width="15%" id="address_th" onclick="showOrderBy('Address', 'address_th');" class="rmv_cls sorting">
                                                        <a href="javascript:void(0);">Address</a>
                                                    </th>
                                                    <th width="8%">
                                                        Status
                                                    </th>
                                                    <th width="2%" style="text-align:center;"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="user_list_data">
                                            </tbody>
                                        </table>
                                    <div class="new-style-pagination">
                                    <div class="total_entries"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            
                        </div><!-- /.panel-body -->
                    
            </form>
            
</div>
<script type='text/javascript'>

function submitTableForm(pageId, empty_sess=0)
{    
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        if(empty_sess && empty_sess == 1){
            $("#empty_sess").val(empty_sess);
        }else{ $("#empty_sess").val(0); }
        var $data = new FormData($('#company_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getAllshippingCompany',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.user_list_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
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
        $("#sort_column").val('Customer');
        $("#sort_type").val('DESC');
        showOrderBy('Customer', 'customer_th');
        $(".customFilter").val('');
        $('#status').val('A');
        $('#auto_a69').val('25');
        submitTableForm('', 1);
    }
    jQuery(document).ready(function () {
        submitPagination();
    });

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
function showAjaxModel(model_head,page_url,id,second_id,customWidth)
{ 
    $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + page_url,
        cache:false,
        data: {'id':id,'second_id':second_id},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            if(obj.status=='100'){
                $('#modal-view-datatable').modal('show');
                $('#pop_heading').html(model_head);
                $('#modal_content').html(obj.data);
                if(customWidth){
                    $(".modal-dialog").css("width", customWidth);
                }else{
                    $(".modal-dialog").css("width", "");
                }
            }else{
                location.reload();
            }
        }
    });
}

 $(document).on("mouseover", '.resetbtn', function (event) {
        $(this).focus();
});


$('.table-fixed tbody').niceScroll({
    cursorwidth: '10px',
    cursorborder: '0px'
});


function csvDonwload(){
   $('#download').val('1');
   $("#company_list").submit();
   $("#download").val('0');
  }

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});  
</script>
