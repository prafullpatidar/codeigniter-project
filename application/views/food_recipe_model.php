          <div class="body-content animated fadeIn body-content-flex">      
            <form class="h-100 d-flex flex-column flex-no-wrap" id="recipe_list" name="recipe_list" method="POST">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine"> 
                                    <li style="margin-left:10px;">
                                      <label>&nbsp;</label>
                                      <div class="contactSearch">
                                            <input id="auto_a72" title="Search by Recipe Name" name="keyword" class="form-control customFilter searchInput ui-autocomplete-input ui-autocomplete-loading" placeholder="Search" type="text" value="" autocomplete="off" onchange="submitTableForm('', 1);">
                                      </div>
                                    </li>
                                    <li >
                                        <div class="pull-left ">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-success btn-slideright resetbtn" onclick="resetFilter();" href="#">Search</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="pull-left ">
                                        <label>&nbsp;</label><br>
                                        <a id="auto_a71" class="btn btn-mini btn-danger btn-slideright resetbtn" onclick="resetFilter();" href="#">Reset</a>
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
                <div class="flex-content mb-10">
                        <input type="hidden" name="page" value="" id="page" />
                        <div class="d-flex flex-column h-100 p-10 panel pt-0">
                                        <input type="hidden" name="sort_column" id="sort_column" value="" />
                                        <input type="hidden" name="sort_type" id="sort_type" value="" />
                                        <table class="header-fixed-new table-text-ellipsis table-layout-fixed table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                                        <thead class="t-header">
                                                <tr>
                                                  <th width="90%" style="width: 90%" id="name_th" onclick="showOrderBy('Name', 'name_th');" class="rmv_cls sorting">
                                                      <a href="javascript:void(0);">Recipe Name</a>
                                                  </th>
                                                  <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="recipe_list_data">
                                            </tbody>
                                        </table>
                                    <div class="new-style-pagination">
                                    <!-- <div class="total_entries"></div> -->
                                    <ul class="pagination pagination-sm">
                                        <li class="new_pagination"></li>
                                    </ul>
                                    </div>                               
                        </div><!-- /.panel-body -->
                    </div>
           
            </form>
            <!--/ End table advanced -->     
    
<script type='text/javascript'>

function submitTableForm(pageId, empty_sess=0)
{    

        if(pageId){
            $("#page").val(pageId);
        }else{ $("#page").val('');}
        var $data = new FormData($('#recipe_list')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'food_menu/modelRecipeList',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.recipe_list_data').html(obj.dataArr);
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
        $("#sort_column").val('Name');
        $("#sort_type").val('ASC');
        $(".customFilter").val('');
        showOrderBy('Name', 'name_th');
        
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
</script>
