<?php  
$user_session_data = getSessionData();
$add_food_menu = checkLabelByTask('add_food_menu');
?>
<style>
body{
    overflow: hidden;
}
</style>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <div class="dt-buttons pull-right" style="margin-top:-5px;">
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
            <form class="h-100 d-flex flex-column flex-no-wrap" id="food_list" name="food_list" method="POST" action="<?php echo base_url(); ?>food_menu/getAllFoodMenuList">
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
                                        <label>Religion</label><br>
                                        <select  id="auto_a69" name="religion" onchange="submitTableForm('', 1);" class="form-control input-sm perPage">
                                            <option value="">Select</option>
                                            <option value="indian_asian">INDIAN / ASIAN</option>
                                            <option value="east_europe">EAST EUROPE</option>
                                            <option value="phili_indo">PHILI / INDO</option>
                                            <option value="chines">CHINES</option>
                                        </select>   
                                    </li>
                                    <li>
                                    <label class="pull-left">
                                     <label>Issue Date</label><br>
                                      <input  name="publish_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                    </label>
                                    </li>

                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <?php
                                     if($add_food_menu){
                                    ?>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                             <a id="auto_a68" onclick="showAjaxModel('Add Food Menu','food_menu/add_edit_menu','','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                        </div>
                                    </li>
                                    <?php } 
                                    ?>
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
                                    
                                        <input type="hidden" name="sort_column" id="sort_column" value="Added On" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="DESC" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                            <thead class="t-header">
                                                <tr>
                                                 <th width="30%">Date Range
                                                </th> 
                                                    <th width="20%" id="religion_th" onclick="showOrderBy('Religion', 'religion_th');" class="rmv_cls sorting">
                                                        <a href="javascript:void(0);">Religion</a>
                                                    </th> 
                                                    <th width="20%" id="issue_date_th" onclick="showOrderBy('Issue Date', 'issue_date_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">Issue Date</a>
                                                    </th>
                                                    <th width="10%" id="added_on_th" onclick="showOrderBy('Added On', 'added_on_th');" class="rmv_cls sorting sorting_desc">
                                                        <a href="javascript:void(0);">Added On</a>
                                                    </th>            
                                                    <th width="10%" id="added_by_th" onclick="showOrderBy('Added By', 'added_by_th');" class="rmv_cls sorting ">
                                                        <a href="javascript:void(0);">Added By</a>
                                                    </th>
                                                    <th width="8%">
                                                        Status
                                                    </th>
                                                    <th width="2%" style="text-align:center;"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="food_list_data">
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
        var $data = new FormData($('#food_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'food_menu/getAllFoodMenuList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.food_list_data').html(obj.dataArr);
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
        $("#sort_column").val('Added On');
        $("#sort_type").val('ASC');
        showOrderBy('Added On', 'added_on_th');
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

 $(document).on("mouseover", '.resetbtn', function (event) {
        $(this).focus();
});


$('.table-fixed tbody').niceScroll({
    cursorwidth: '10px',
    cursorborder: '0px'
});


jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});  

</script>
