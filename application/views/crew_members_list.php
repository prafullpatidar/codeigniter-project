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

    <div class="body-content animated fadeIn body-content-flex has-info-box"> 
        <div class="page-breadcrumb">
            <h2><span class="icon"><i class="fa fa-ship"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
        </div>
            
            <form id="ship_list" class="h-100 d-flex flex-column flex-no-wrap" name="ship_list" method="POST">
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
                                        <div class="pull-left">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <label></label>
                                        <div><a class="btn btn-mini btn-success btn-slideright" href="<?php echo base_url();?>crew/foodHabitList/<?php echo base64_encode($crew_member_entries_id);?>" class="mt-3" title="View Food Habits" style="display:inline-block;" target="_blank">View All Crew's Food Habits</a>
                                        <input type="hidden" value="0" name="download" id="download" />
                                        </div>
                                    </li>
                                    <li>
                                        <label></label>
                                        <div><a class="btn btn-mini btn-danger btn-slideright" href="<?php echo base_url();?>shipping/crewEnteriesList/<?php echo $ship_id;?>" class="mt-3" title="View Crew Entries" style="display:none;">Back to List</a>
                                        </div>
                                    </li>
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" title="Search by Family Name, Given Name, Rank/Role or Identity Number / Passport Id" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
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
                        <input type="hidden" name="crew_member_entries_id" value="<?php echo $crew_member_entries_id;?>"
                        >

                        <div class="panel panel-default shadow no-overflow mt-10 mb-10 p-5">
                                        <table class="leadListmod table table-layout-fixed no-border table-copy last-tr-th-noborder">
                                        <thead>
                                          <tr>
                                              <th colspan="12">Arrival/Departure: <span style="color:green !important;font-weight:400"><?php echo ucfirst($shipCrewData[0]->arrival_or_departure);?></span></th>
                                              
                                          </tr>
                                          <tr>
                                              <th colspan="3">Name of Ship: <span style="color:green !important;font-weight:400"><?php echo ucfirst($shipCrewData[0]->ship_name);?></span></th>
                                              
                                              <th colspan="3">IMO Number: <span style="color:green !important;font-weight:400"><?php echo ucfirst($shipCrewData[0]->imo_no);?></span></th>
                                              
                                              <th colspan="3">Call Sign: <span style="color:green !important;font-weight:400"><?php echo ($shipCrewData[0]->call_sign);?></span></th>
                                             
                                              <th colspan="3">Voyage Number: <span style="color:green !important;font-weight:400"><?php echo ($shipCrewData[0]->voyage_number);?></span></th>
                                              
                                          </tr>
                                          <tr>
                                              <th colspan="3">Port of Arrival/Departure: <span style="color:green !important;font-weight:400"><?php echo ucfirst($shipCrewData[0]->port_of_arrival_or_departure);?></span></th>
                                              
                                              <th colspan="3">Date of Arrival/Departure: <span style="color:green !important;font-weight:400"><?php echo date('d-m-Y',strtotime($shipCrewData[0]->date_of_arrival_or_departure));?></span></th>
                                              
                                              <th colspan="3">Flag State of Ship: <span style="color:green !important;font-weight:400"><?php echo ($shipCrewData[0]->flag_state_of_ship);?></span></th>

                                              <th colspan="3">Last Port of Call: <span style="color:green !important;font-weight:400"><?php echo ($shipCrewData[0]->last_port_of_call);?></span></th>
                                          </tr>
</thead>
                                        </table>
                                </div> 
                                            
                                    <div class="d-flex flex-column p-10 panel pt-0 mb-0" >
                                        <input type="hidden" name="sort_column" id="sort_column" value="Customer" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        
                                        
    <div class="cm-list-table">
                                    <table id="pre_req_name_table" class="table">
                                        <thead>
                                          <tr>
                                            <th style="width:10%" width="10%" id="ships_th" onclick="showOrderBy('Ship Name', 'ships_th');" class="">
                                                <a href="javascript:void(0);">Family Name</a>
                                            </th>
                                            <th width="10%" id="code_th" onclick="showOrderBy('Ship Code', 'code_th');" class="">
                                                <a href="javascript:void(0);">Given Name</a>
                                            </th>
                                            <th width="10%" id="company_th" onclick="showOrderBy('Shipping Company', 'company_th');" class="">
                                                <a href="javascript:void(0);">Rank or Rating</a>
                                            </th>
                                            <th width="10%" id="created_date_th" onclick="showOrderBy('CreatedDate', 'created_date_th');" class="">
                                                <a href="javascript:void(0);">Nationality</a>
                                            </th>
                                            <th  width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Date Of Birth</a>
                                            </th>
                                            <th style="text-align:center" width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Place Of Birth</a>
                                            </th>
                                            <th width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Gender</a>
                                            </th>
                                            <th width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Nature of Identity / Passport</a>
                                            </th>
                                            <th width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Identity Number / Passport Id</a>
                                            </th>
                                            <th width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Issuing State of Identity</a>
                                            </th>
                                            <th width="10%" id="added_by_th" onclick="showOrderBy('AddedBy', 'added_by_th');" class="">
                                                <a href="javascript:void(0);">Expiry Date of Identity</a>
                                            </th>
                                            <th width="2%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="crew_member_list_data">
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
                                    
            </div>    
                                
                  
            
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
            url: base_url + 'crew/getallshipCrewMembersList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.crew_member_list_data').html(obj.dataArr);
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
        // $(".rmv_cls").removeClass('sorting_asc sorting_desc');
        // var sort_column = $("#sort_column").val();
        // var sort_type = $("#sort_type").val();
        // if(sort_column == '')
        // {
        //     $("#sort_column").val(head_title);
        //     $("#sort_type").val('ASC');
        //     $("#"+th_id).addClass('sorting_asc');
        // }
        // else if(sort_column == head_title)
        // {
        //     $("#sort_column").val(head_title);
        //     if(sort_type == 'ASC')
        //     {
        //         $("#sort_type").val('DESC');
        //         $("#"+th_id).addClass('sorting_desc');
        //     }
        //     else 
        //     {
        //         $("#sort_type").val('ASC'); 
        //         $("#"+th_id).addClass('sorting_asc');  
        //     }
        // }
        // else 
        // {
        //     $("#sort_column").val(head_title);
        //     $("#sort_type").val('ASC');
        //     $("#"+th_id).addClass('sorting_asc');
        // }
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
