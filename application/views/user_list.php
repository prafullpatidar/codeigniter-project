<?php 
$user_session_data = getSessionData(); 
$add_user = checkLabelByTask('add_user');
?>
<!-- Start page header -->
<div id="tour-11" class="header-content">
    <?php if($add_user){ ?>
  <div class="dt-buttons pull-right" style="margin-top:-5px;">
          <a id="auto_a68" class=" btn btn-success btn-slideright" tabindex="0" href="<?php echo base_url().'user/addedituser';?>"><span>Add New</span></a>
    </div>
    <?php } ?>
  <h2><span class="icon"><i class="fas fa-tools"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
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
<div class="body-content animated fadeIn body-content-flex">    
    
            <form class="h-100 d-flex flex-column flex-no-wrap" id="user_list" name="user_list" method="POST" action="<?php echo base_url(); ?>user/getAlluser">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li class="record">
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
                                    <li class="scompany">
                                        
                                        <label>Shipping Comapany</label><br>
                                        <select  id="shipping_company_id" name="shipping_company_id" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                         <option value="">Select Company</option>
                                               <?php
                                               if ($company) {
                                                   foreach ($company as $row) {
                                                     $selected = ($this->input->post('shipping_company_id') == $row->shipping_company_id ) ? 'selected' : '';
                                                   echo '<option value="'.$row->shipping_company_id.'" '.$selected.'>'.ucwords($row->name).'</option>';    
                                                   }                                       
                                               }
                                               ?>
                                        </select>
                                        
                                    </li>
                                    <li class="role">
                                        
                                        <label>User Role</label><br>
                                        <select  id="role" name="role" onchange="submitTableForm('', 1);"  class="form-control input-sm perPage">
                                         <option value="">Select Role</option>
                                               <?php
                                               if ($roles) {
                                                   foreach ($roles as $role) {
                                                    
                                                   echo '<option value="'.$role->role_id.'" >'.ucwords($role->role_name).'</option>';    
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
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="csvDonwload()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li>
                                    
                                    <li class="pull-right">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch mt-0">
                                            <input id="auto_a72" title="Search Name, Email, Phone or Address" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitTableForm('', 1)"></a>
                                      </div>
                                    </li>

                                    <?php 
                                     if($add_user){
                                    ?>
                                    <li class="pull-right mr-10">
                                        <div>
                                        <label>&nbsp;</label><br>
                                      <a id="auto_a68" class=" btn btn-success btn-slideright" tabindex="0" href="<?php echo base_url().'user/addedituser';?>"><span>Add New</span></a>
                                     </div>
                                    </li>
                                    <?php } 
                                    ?>
                                    
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
                            
                                        <input type="hidden" id="download" name="download" value="0">
                                        <input type="hidden" name="sort_column" id="sort_column" value="" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="" />
                                        <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                                        <table id="pre_req_name_table" class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
             <th style="width:5%" width="5%">
                Profile
            </th>
            <th width="19%" id="name_th" onclick="showOrderBy('Name', 'name_th');" class="rmv_cls sorting sorting_asc">
                <a href="javascript:void(0);">Name</a>
            </th>
            <th width="12%" id="email_th" onclick="showOrderBy('Email', 'email_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Email</a>
            </th>
            <th width="12%" id="phone_th_th" onclick="showOrderBy('Phone', 'phone_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Phone</a>
            </th>
            <th width="12%" id="created_th" onclick="showOrderBy('Created', 'created_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Created On</a>
            </th>
            <th width="12%" id="address_th" onclick="showOrderBy('Address', 'address_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">Address</a>
            </th>
            <th width="12%" id="role_th" onclick="showOrderBy('Role', 'role_th');" class="rmv_cls sorting">
                <a href="javascript:void(0);">User Role</a>
            </th>
            <th width="12%">
                Status
            </th>
            <th width="4%" style="text-align:center;"></th>
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
                            
                    
            </div><!-- /.panel -->
            </form>
            <!--/ End table advanced -->
        
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
        var $data = new FormData($('#user_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'user/getAlluser',
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
                highlightRow();
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
        $("#sort_column").val('Name');
        $("#sort_type").val('DESC');
        showOrderBy('Name', 'name_th');
        $(".customFilter").val('');
        $('#shipping_company_id').val('');
        $('#status').val('A');
         $('#auto_a69').val('25');
         $('#role').val('');
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


 // $('#del_user').on('change',function(){
 //  var d = $('#del_user').val();
 //  if(d=='D'){
 //   $('#status').val('D');
 //   $('.status').hide();
 //  }else{
 //   $('#status').val('A');
 //   $('.status').show();      
 //  }
 //  submitTableForm();
 // })

 $('.table-fixed tbody').niceScroll({
        cursorwidth: '10px',
        cursorborder: '0px'
    });

  function csvDonwload(){
   $('#download').val('1');
   $("#user_list").submit();
   $("#download").val('0');
  }


  jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});  
</script>
