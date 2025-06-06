<?php
$user_session_data = getSessionData();
$delete_notification = checkLabelByTask('delete_notification');
?>
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

            <form class="h-100 d-flex flex-column flex-no-wrap" id="notification_form" name="notification_form" method="POST" action="<?php echo base_url().'user/getAllnotificationList';?>">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li>
                                        <label class="pull-left">
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
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
                                        <label>Status</label><br>
                                        <select  id="status" name="status" onchange="submitForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage">
                                            <option value="">Select Status</option>
                                            <option value="R">Read</option>
                                            <option value="U">Unread</option>
                                        </select>
                                        </label>
                                    </li>
                                    <li>
                                    <label class="pull-left">
                                     <label>Date</label><br>
                                      <input  name="created_on" class="form-control customFilter datePicker_editPro" type="text" value="" autocomplete="off" onchange="submitForm('', 1);">
                                    </label>
                                    </li> 
                                               
                                    <li class="pull-right filter-s">
                                      <div class="contactSearch">
                                            <input title="Search by Title" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitForm('', 1)"></a>
                                      </div>
                                    </li>
                                    <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
                                    </li> 
                                     <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="downloadCsv()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li>
                                    <?php 
                                     if($delete_notification){ 
                                    ?>
                                    <li>
                                     <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="groupAction(1)" style="display:none" class="btn btn-mini btn-danger btn-slideright del_btn">Delete</a>
                                        </div>
                                    </li>
                                     <?php }
                                     ?>
                                    <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="groupAction(2)" style="display:none" class="btn btn-mini btn-danger btn-slideright rd_btn">Mark As Read</a>
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
                        <input type="hidden" name="page" value="" id="page" />
                       
                            <div class="d-flex flex-column h-100 p-0 panel no-border">         
                            <input type="hidden" name="sort_column" id="sort_column" value="" />
                            <input type="hidden" name="sort_type" id="sort_type" value="" />
                            <input type="hidden" name="download" id="download" value="0">
                            <input type="hidden" name="note_ids" id="ids" value="">
                            <table id="note_table" class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                              <thead class="t-header">
                                    <tr>
                                    <th style="width:3%" width='3%'>
                                        <input type="checkbox" class="check_all_td" name="check_all" id="check_all_td">
                                    </th>
                                    <th width='10%' id="title_th" onclick="showOrderBy('Title', 'title_th');" class="rmv_cls sorting">
                                        <a href="javascript:void(0);">Title</a>
                                    </th>
                                    <th width='40%' id="dsc_th" onclick="showOrderBy('Description', 'dsc_th');" class="rmv_cls sorting" >
                                        <a href="javascript:void(0);">Description</a>
                                    </th>
                                    <th width='10%' id="date_th" onclick="showOrderBy('Date', 'date_th');" class="rmv_cls sorting sorting_desc">
                                        <a href="javascript:void(0);">Time</a>
                                    </th>
                                    </tr>
                                </thead>
                                <tbody class="notification_data">
                                </tbody>
                                 </table>
                                  <div class="new-style-pagination">
                                    <div class="total_entries total_entries_static"></div>
                                    <ul class="pagination pagination-sm">
                                        <li class="new_pagination"></li>
                                    </ul>
                                    </div>
                                </div>
                            
                    </div>
            </div><!-- /.panel -->
            </form>
            <!--/ End table advanced -->
        </div><!-- /.col-md-12 -->
    </div>
  </div>          
</div>
<script type='text/javascript'>
function submitForm(pageId)
{     
       $('#page').val(pageId);
       var $data = new FormData($('#notification_form')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'user/getAllnotificationList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.notification_data').html(obj.dataArr);
                $('.new_pagination').html(obj.pagination);
                $('.total_entries').html(obj.total_entries);
            }
        });
        return false;
    }


   $(document).ready(function(){
        submitForm();
       })

    $(".customFilter").keypress(function(event){
    if (event.which == 13) {
      submitForm();
      return false;
    }
    })
    
    function submitPagination(pageId)
    {
       submitForm(pageId);
    }

    function resetFilter()
    {
        $("#sort_column").val('Date');
        $("#sort_type").val('ASC');
        showOrderBy('Date', 'date_th');
        $(".customFilter").val('');  
        $('#auto_a69').val('25');
        $('#status').val('');
        submitForm('', 1);
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

        submitForm();
    }


 // $(".ui-tabs-panel .header-fixed-new").prepFixedHeader().fixedHeader();


 jQuery(document).ready(function(){
    $('.datePicker_editPro').datepicker({
        format: 'dd-mm-yyyy',
        changeYear: true,
    });
});

function downloadCsv(){
    $("#download").val('1');
    $("#notification_form").submit();
    $("#download").val('0');
 }

 function update_notification_list(notification_id){
    if(notification_id){
     $.ajax({
      beforeSend : function(){
      },
      type: "POST",
            url: base_url + 'user/update_notification',
            cache: false,
            data: {'notification_id':notification_id},
            dataType : 'JSON',
            success: function (msg){
                $('.count_'+notification_id).removeClass('notification-unread');
               submitForm(); 
               updateNotificationCount();
            }
        });
    }
  }

$(document).ready(function(){
   $(".check_all_td").click(function(){     
   $("input.check_singel").prop('checked', $(this).prop('checked'));
   $('#ids').val('');
    var checked_ids= new Array();
     $('#note_table .check_singel').each(function(){
        if(this.checked){
            $('.del_btn').css('display','');
            $('.rd_btn').css('display','');          
            checked_ids.push($(this).val());
        }
      });
     var str = checked_ids.join();
      $('#ids').val(str);    
        if(this.checked){
          $('.del_btn').css('display','');
          $('.rd_btn').css('display','');
        }else{
            $('.del_btn').css('display','none');
            $('.rd_btn').css('display','none');
        }
  });   
})

$(document).on('click','#note_table tbody .check_singel', function(e){
    $('.del_btn').css('display','none');
    $('.rd_btn').css('display','none');
    $('#ids').val('');
    var checked_ids= new Array();
    $('#note_table .check_singel').each(function(){
        if(this.checked){
            $('.del_btn').css('display','');
            $('.rd_btn').css('display','');          
            checked_ids.push($(this).val());
        }
     });
   var str = checked_ids.join();
  $('#ids').val(str);
});


function groupAction(type){
   var ids = $('#ids').val(); 
    if (type != '' && ids != '') {
        if (type == 1) {
            var msg = 'Are you sure you want to delete selected notification?';
        }else if(type==2) {
            var msg = 'Are you sure you want to mark as read selected notification?';
        }
        bootbox.dialog({
            message: msg,
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
                            url: base_url + 'user/groupUpdateNotify',
                            cache: false,
                            data: {'ids': ids, 'type': type},
                            success: function () {
                              location.reload();
                            }
                        });
                    }
                }

            }
        });
    }
}

</script>
