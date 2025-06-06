<?php 
$user_session_data = getSessionData();
$add_product = checkLabelByTask('add_product');
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <h2><span class="icon"><i class="fas fa-leaf"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content --> 
<style>
body{
    overflow: hidden;
}

.table-scroll {
    overflow-x: auto;
    overflow-y: hidden;
    width: 100%;
}

.table-scroll table {
    width: max-content;
    min-width: 100%;
    border-collapse: collapse;
}

.table-scroll thead th,
.table-scroll tbody td {
    white-space: nowrap; /* Prevent line wrap */
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
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
?>
<div class="body-content animated fadeIn body-content-flex"> 
   
            <form class="h-100 d-flex flex-column flex-no-wrap" id="product_list" name="product_list" method="POST" action="<?php echo base_url(); ?>product/getallproductlist">
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
                                        
                                        <label>Product Category</label><br>
                                        <select style="width:150px" id="product_category_id" name="product_category_id" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                            <option value="">Select Category</option>
                                               <?php
                                                if(!empty($products_category)){
                                                    foreach ($products_category as $pc){
                                                   $selected = (!empty($dataArr['product_category_id']) && $dataArr['product_category_id'] == $pc->product_category_id)?'selected':'';
                                                    echo '<option '.$selected.' value="'.$pc->product_category_id.'">'.$pc->category_name.'</option>';    
                                                    }   
                                                }
                                            ?>
                                        </select>
                                    </li>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <?php
                                    if($add_product){
                                    ?>
                                    <li>    
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                         <a id="auto_a68" onclick="showAjaxModel('Add Product','product/addnewproduct','','','70%')" class=" btn btn-success btn-slideright" tabindex="0" href="javascript:void(0);"><span>Add New</span></a>
                                        </div>
                                        </li>
                                   <?php } ?>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch">
                                            <input title="Search by Item No, Name or Unit" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
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
            <div class="flex-content mb-10 ">
                    
                        <input type="hidden" name="page" value="" id="page" />
                        <div class="">
                           
                                        <input type="hidden" name="sort_column" id="sort_column" value="Product" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        <input type="hidden" name="download" id="download" value="0">
                                        <table id="pre_req_name_table" class="table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
                                                 <th id="item_no_th" onclick="showOrderBy('Item No', 'item_no_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Item No</a>
                                                </th>
                                                <th width="10%"  id="product_th" onclick="showOrderBy('Product', 'product_th');" class="rmv_cls sorting sorting_asc">
                                                    <a href="javascript:void(0);">Name</a>
                                                </th>
                                                <th  id="parent_category_th" onclick="showOrderBy('Category', 'parent_category_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Category</a>
                                                </th>
                                               
                                                <th  id="unit_th" onclick="showOrderBy('Unit', 'unit_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Unit</a>
                                                </th>
                                                 <th  id="cal_th" onclick="showOrderBy('Calories', 'cal_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Calories<br>(Kcal)</a>
                                                </th>
                                                <th  id="pro_th" onclick="showOrderBy('Protein', 'pro_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Protein<br>(G)</a>
                                                </th>
                                                <th  id="fat_th" onclick="showOrderBy('Fat', 'fat_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Total Fat<br>(G)</a>
                                                </th>
                                                <th  id="s_fat_th" onclick="showOrderBy('Saturated Fat', 's_fat_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Saturated Fat<br>(G)</a>
                                                </th>
                                                <th  id="cholesterol_th" onclick="showOrderBy('Cholesterol', 'cholesterol_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Cholesterol<br>(MG)</a>
                                                </th>
                                                <th  id="sodium_th" onclick="showOrderBy('Sodium', 'sodium_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Sodium<br>(MG)</a>
                                                </th>
                                                <th  id="potassium_th" onclick="showOrderBy('Potassium', 'potassium_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Potassium<br>(MG)</a>
                                                </th>
                                                <th  id="carbohydrates_th" onclick="showOrderBy('Carbohydrates', 'carbohydrates_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Carbohydrates<br>(G)</a>
                                                </th>
                                                <th  id="iron_th" onclick="showOrderBy('Iron', 'iron_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Iron<br>(MG)</a>
                                                </th>
                                                <th  id="calcium_th" onclick="showOrderBy('Calcium', 'calcium_th');" class="rmv_cls sorting">
                                                    <a href="javascript:void(0);">Calcium<br>(MG)</a>
                                                </th>
                                                <th >
                                                    <a href="javascript:void(0);">Status</a>
                                                </th>
                                                <th width='3%' style="text-align:center;"></th>
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
        
</div>
<script type='text/javascript'>

$('.table-fixed tbody').niceScroll({
    cursorwidth: '10px',
    cursorborder: '0px'
});

function submitTableForm(pageId, empty_sess=0)
{    
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        
        var $data = new FormData($('#product_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'product/getallproductlist',
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
        $("#sort_column").val('Product');
        $("#sort_type").val('DESC');
        showOrderBy('Product', 'product_th');
        $(".customFilter").val(''); 
        $('#status').val('A');
        $('#auto_a69').val('25');
        $('#product_category_id').val('');       
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


 function csvDonwload(){
   $('#download').val('1');
   $("#product_list").submit();
   $("#download").val('0');
  }    
</script>
