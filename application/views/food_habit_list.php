<!-- Start page header -->
<div id="tour-11" class="header-content">
      <div class="dt-buttons pull-right" style="margin-top:-5px;">
    </div>
  <h2><span class="icon"><i class="fa fa-fish"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content --> 

<!-- Start body content -->
<div class="body-content animated fadeIn">
<h2><span class="icon"><i class="fas fa-hamburger"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg))
{
    ?><div class="custom_alert alert alert-success" id="showSuccMessage"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg))
{
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
?>    
<div class="si-card">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="ship_list" name="ship_list" method="POST">
            <!--Filter head start -->
            <div class="panel panel-default shadow no-overflow">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li class="record">
                                        <label class="pull-left">
                                        <label>Records Per Page</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        </label>
                                    </li>
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-success btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                     
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch">
                                            <input id="auto_a72" title="Search by Crew Member Name or Rank" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
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
                        <input type="hidden" name="page" value="1" id="page" />
                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>" id="ship_id" />
                        <div class="panel-body no-padding">
                            <div class="panel panel-default panel-table no-margin">
                                <div class="panel-body no-padding">
                                    <div class="table-responsive">
                                        <input type="hidden" name="sort_column" id="sort_column" value="Customer" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        <table id="pre_req_name_table" class="table white-space-nowrap table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead>
                                        <tr>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>
                                        	<th></th>                                        	
                                        	<th colspan="12">Food Groups</th>
                                        	
                                        </tr>
                                        <tr>
                                        	<th id="ships_th" onclick="" class="">
						                      <a href="javascript:void(0);">S.No.</a>
						                    </th>
								            <th id="ships_th" onclick="" class="">
								                <a href="javascript:void(0);">Crew Member Name</a>
								            </th>
								            <th id="code_th" onclick="" class="">
								                <a href="javascript:void(0);">Rank</a>
								            </th>
								            <th id="company_th" onclick="" class="">
								                <a href="javascript:void(0);">Age</a>
								            </th>
								            <th id="added_by_th" onclick="" class="">
								                <a href="javascript:void(0);">Gender</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Nationality</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Meat</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Pork</a>
								            </th>
								             <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Beef</a>
								            </th>
								             <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Fish / Sea Food</a>
								            </th>
								             <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Mutton</a>
								            </th>

								             <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Chicken</a>
								            </th>
								             <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Egg</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Cereals</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Dairy Products</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Vegetables</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Fruits</a>
								            </th>
								            <th id="created_date_th" onclick="" class="">
								                <a href="javascript:void(0);">Sweets</a>
								            </th>
								           
                                                </tr>
                                            </thead>
                                            <tbody class="food_list_data">
                                            </tbody>
                                        </table></div>
                                    <div class="col-md-3 total_entries" style=" padding-top: 20px;"></div>
                                    <ul class="pagination pagination-sm pull-right push-down-20 push-up-20">
                                        <li class="new_pagination"></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="crew_member_entries_id" value="<?php echo $crew_member_entries_id;?>">
                            </div>
                        </div><!-- /.panel-body -->
                    </div>
            </div><!-- /.panel -->
            </form>
            <!--/ End table advanced -->
        </div><!-- /.col-md-12 -->
    </div>
</div>
</div>
<script type='text/javascript'>
function exportTable(){
        $("#download").val('1');
        $("#ship_list").submit();
        $("#download").val('0');
    }

function submitTableForm(pageId, empty_sess=0)
{    
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        if(empty_sess && empty_sess == 1){
            $("#empty_sess").val(empty_sess);
        }else{ $("#empty_sess").val(0); }
        var $data = new FormData($('#ship_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'crew/getallFoodHabitList',
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
        $('#auto_a69').val('25');
        $('#auto_a72').val('');
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

</script>
