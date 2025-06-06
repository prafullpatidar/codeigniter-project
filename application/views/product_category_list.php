<?php 
$user_session_data = getSessionData();
$add_product_category = checkLabelByTask('add_product_category');
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <div class="dt-buttons pull-right" style="margin-top:-5px;">
    <?php if($checkEditPermission){ ?>
    <a id="auto_a68" onclick="showAjaxModel('Add Product Category','product/addEditproductCategory','','','70%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
    <?php } ?>
    </div>
  <h2><span class="icon"><i class="fas fa-leaf"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content --> 
<style>
body{
    overflow: hidden;
}
</style>
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
 ?>
    <div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
?>    
<div class="body-content animated fadeIn body-content-flex">  
    
            <form class="h-100 d-flex flex-column flex-no-wrap" id="producat_category_list" name="producat_category_list" method="POST" action="<?php echo base_url(); ?>product/getAllproductCategory">
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
                                        
                                        <label>Product Group</label><br>
                                        <select style="width:150px" id="product_group_id" name="product_group_id" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="">Select Group</option>
                                               <?php
                                                if(!empty($products_group)){
                                                    foreach ($products_group as $pg){
                                                    echo '<option '.$selected.' value="'.$pg->product_group_id.'">'.$pg->name.'</option>';    
                                                    }   
                                                }
                                            ?>
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
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#"> Reset</a>
                                        </div>
                                    </li>
                                    <?php 
                                    if($add_product_category){
                                        ?>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a68" onclick="showAjaxModel('Add Product Category','product/addEditproductCategory','','','50%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                        </div>
                                    </li>
                                    <?php }
                                    ?>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch">
                                            <input title="Search by Category or Created By" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
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
                   
                        <input type="hidden" name="page" value="" id="page" />
                        <div class="d-flex flex-column h-100 p-10 panel pt-0">
                           
                                    
                                        <input type="hidden" name="sort_column" id="sort_column" value="Sequence" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                         <input type="hidden" name="download" id="download" value="0">
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
             <th style="width:7%" width="3%" id="sequence_th" onclick="showOrderBy('Sequence', 'sequence_th');" class="rmv_cls sorting sorting_asc">
                <a href="javascript:void(0);">Sequence</a>
            </th>
            <th width="10%" id="category_th" onclick="showOrderBy('Category', 'category_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Category</a>
            </th>
             <th width='10%' id="parent_category_th" onclick="showOrderBy('Group', 'parent_category_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Group</a>
            </th>
            <th width="10%" id="created_date_th" onclick="showOrderBy('CreatedDate', 'created_date_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Created On</a>
            </th>
            <th width="10%" id="created_by_th" onclick="showOrderBy('CreatedBy', 'created_by_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Created By</a>
            </th>

            <th width="10%">
                <a href="javascript:void(0);">Status</a>
            </th>
            <th width="3%" style="text-align:center;"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="pc_list_data">
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
        </div><!-- /.col-md-12 -->
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
        var $data = new FormData($('#producat_category_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'product/getAllproductCategory',
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
        $("#sort_column").val('Category');
        $("#sort_type").val('DESC');
        showOrderBy('Category', 'category_th');
        $(".customFilter").val('');
        $('#auto_a69').val('25');        
        $('#status').val('A');        
        $('#product_group_id').val('');        
       setTimeout(function(){ 
        submitTableForm('', 1);
        }, 30);     
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

jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

function csvDonwload(){
   $('#download').val('1');
   $("#producat_category_list").submit();
   $("#download").val('0');
  }    
</script>
