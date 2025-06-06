<?php
$import_crew_member = checkLabelByTask('import_crew_member');
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
  <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content --> 

<!-- Start body content -->

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

<div class="body-content animated fadeIn body-content-flex"> 
<div class="page-breadcrumb">
<h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
</div>
            <form id="ship_list" class="h-100 d-flex flex-column flex-no-wrap" name="ship_list" method="POST" action="<?php echo base_url(); ?>shipping/getAllshipList">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
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
                                    <label class="pull-left">
                                     <label>Date</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                    </label>
                                    </li> 
                                    <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <?php
                                    if($import_crew_member){ 
                                    ?>
                                     <li>
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                      <a id="auto_a71" class="btn btn-mini btn-success btn-slideright resetbtn" onclick="showAjaxModel('Import Crew Members','crew/import_crew_members','<?php echo $ship_id;?>','','')" href="#">Import Crew Members</a>
                                        </div>
                                    </li>
                                 <?php }?>
                                    
                                    <li class="pull-right">
                                      <label>&nbsp;</label><br />
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" title="Search by Imported By Name" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
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
            <div class="flex-content mb-10">
                        <input type="hidden" name="page" value="1" id="page" />
                        <input type="hidden" name="ship_id" value="<?php echo $ship_id;?>" id="ship_id" />
                        <div class="d-flex flex-column h-100 p-10 panel pt-0 mb-0">
                                        <input type="hidden" name="sort_column" id="sort_column" value="Customer" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">                                          
                                          <tr>
                                            <th width="30%" id="ships_th" onclick="showOrderBy('Date and Time', 'date_th');" class="rmv_cls sorting">
                                                <a href="javascript:void(0);">Month</a>
                                            </th>
                                            <th width="30%" id="code_th" onclick="showOrderBy('Imported By', 'imported_by_th');" class="rmv_cls sorting">
                                                <a href="javascript:void(0);">Imported By</a>
                                            </th>
                                            <th width="30%" id="company_th" class="">
                                                <a href="javascript:void(0);">Signature</a>
                                            </th>                                            
                                            <th width="10%" style="text-align:center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="crew_list_data">
                                            </tbody>
                                        </table>
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
            url: base_url + 'crew/getallCrewEntriesList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.crew_list_data').html(obj.dataArr);
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
        $('.customFilter').val('');
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

  jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
}); 

</script>
