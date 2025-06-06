<style type="text/css">
     .log {
        padding: 0;
    }
    .log-entry {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .log-entry:last-child {
        border-bottom: none;
    }
</style>
<div class="">  
    <div class = "row">
        <div class = "col-md-12">
            <form id="log_list" name="log_list" method="POST" action="<?php echo base_url().'shipping/getallshipportlist';?>">
            <!--Filter head start -->
            <div class="panel panel-default shadow no-overflow mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <!-- <li>
                                        <label class="pull-left">
                                        <label>Records</label><br>
                                        <select  id="auto_a69" name="perPage" onchange="submitPortTableForm('', 1);" aria-controls="alc_list_table" class="form-control input-sm perPage width-auto">
                                            <option value="25" selected="selected">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                        </label>
                                    </li>              -->   
                                    <li class="pull-right filter-s">
                                     
                                      <div class="contactSearch">
                                            <input title="Search by Port Name" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitPortTableForm('', 1);">
                                            <a href="#" title="Search" class="searchIcon"><img src="<?php echo base_url(); ?>assets/images/searchInputIcon.png" alt="Search Icon" onclick="submitPortTableForm('', 1)"></a>
                                      </div>
                                    </li>
<!--                                      <li>
                                        <div>
                                        <label>&nbsp;</label><br>
                                          <a href="javascript:void(0)" onclick="downloadCsv()" class="excel-download">Download <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                        </div>
                                    </li> -->
                                     <!-- <li>
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
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
                    <input type="hidden" name="ship_id" id="ship_id" value="<?php echo  $ship_id;?>">
                 <div class="panel-body no-padding no-bg">
                 <div class="col-sm-12 no-padding">
                  <div>
                    <div class="clearfix"></div>
                   <div class="note_data" style="overflow-y: scroll;">      
                   </div>
                 </div>
        <!-- Add more log entries here -->
    </div>
                  </div>
              </div>
            </div>
        </form>
    </div>
</div>
</div>
<script type="text/javascript">
    function submitLogActivityForm(pageId)
{      
        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        var $data = new FormData($('#log_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getLogActivity',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.note_data').html(obj.dataArr);
            }
        });
        return false;
    }

   $(document).ready(function(){
     submitLogActivityForm();
   })
</script>