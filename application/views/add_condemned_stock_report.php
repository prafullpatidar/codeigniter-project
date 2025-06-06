<style type="text/css">
    .rounded {
  -webkit-border-radius: 3px !important;
  -moz-border-radius: 3px !important;
  border-radius: 3px !important;
}

.select2-selection__clear{
    color: black;
}

.item_search {
    z-index:99999;
}

.mini-stat {
  padding: 15px;
  margin-bottom: 20px;
}

.mini-stat-icon {
  width: 60px;
  height: 60px;
  display: inline-block;
  line-height: 60px;
  text-align: center;
  font-size: 30px;
  background: none repeat scroll 0% 0% #EEE;
  border-radius: 100%;
  float: left;
  margin-right: 10px;
  color: #FFF;
}

.mini-stat-info {
  font-size: 12px;
  padding-top: 2px;
}

span, p {
  color: white;
}

.mini-stat-info span {
  display: block;
  font-size: 30px;
  font-weight: 600;
  margin-bottom: 5px;
  margin-top: 7px;
}

/* ================ colors =====================*/
.bg-facebook {
  background-color: #3b5998 !important;
  border: 1px solid #3b5998;
  color: white;
}

.fa-truck {
  color: #3b5998 !important;
}

.bg-twitter {
  background-color: #296a7e  !important;
  border: 1px solid #296a7e;
  color: white;
}

.fa-ship {
  color: #00a0d1 !important;
}

.bg-googleplus {
  background-color: #db4a39 !important;
  border: 1px solid #db4a39;
  color: white;
}

.fa-id-badge {
  color: #db4a39 !important;
}

.bg-bitbucket {
  background-color: #205081 !important;
  border: 1px solid #205081;
  color: white;
}

.fa-user {
  color: #205081 !important;
}
.error{
    color: red;
}
</style>
<?php 
$user_session_data = getSessionData();
$role = $user_session_data->code;
if($mode == 'edit'){
    $disable = 'disabled';
}else{
    $disable = '';
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="animated fadeIn" id="stock_form">
 <div class="row">
    <div class="col-md-12">
      <div class="">
        <form class="form-horizontal form-bordered" name="add_rfq" enctype="multipart/form-data" id="add_csr" method="post">
            <div class="no-padding rounded-bottom">
              <div class="form-body">
               <div class="row mt-2">
               <div class="col-sm-3">
                   <p style="font-size: 16px;"> <span style="color:#000;"><strong>Ship - </strong></span><span style="color:green;margin-top:100px"> <?php echo ucfirst($assigned_ship_name);?></span></p>
                   <p style="font-size: 16px;"><span style="color:#000;"><strong>Ship IMO NO -</strong></span><span style="color:green;margin-top:100px"> <?php echo ($assigned_ship_imo);?></span></p>
                    <input type="hidden" name="ship_id" value="<?php echo $assigned_ship_id;?>">
               </div>
            <div class="col-sm-3 mb-10 hide">
                <label>Month</label>
                <div>
                    <select class="form-control customFilter " name="month" id="month" <?php echo $disable;?>>
                        <option value="01" <?php echo ($dataArr['month'] =='01') ? 'selected' : '';?>>January</option>
                        <option value="02" <?php echo ($dataArr['month'] =='02') ? 'selected' : '';?> >February</option>
                        <option value="03" <?php echo ($dataArr['month'] =='03') ? 'selected' : '';?>>March</option>
                        <option value="04" <?php echo ($dataArr['month'] =='04') ? 'selected' : '';?>>April</option>
                        <option value="05" <?php echo ($dataArr['month'] =='05') ? 'selected' : '';?>>May</option>
                        <option value="06" <?php echo ($dataArr['month'] =='06') ? 'selected' : '';?>>June</option>
                        <option value="07" <?php echo ($dataArr['month'] =='07') ? 'selected' : '';?>>July</option>
                        <option value="08" <?php echo ($dataArr['month'] =='08') ? 'selected' : '';?> >August</option>
                        <option value="09" <?php echo ($dataArr['month']=='09') ? 'selected' : '';?>>September</option>
                        <option value="10" <?php echo ($dataArr['month']=='10') ? 'selected' : '';?>>October</option>
                        <option value="11" <?php echo ($dataArr['month']=='11') ? 'selected' : '';?>>November</option>
                        <option value="12" <?php echo ($dataArr['month']=='12') ? 'selected' : '';?>>December</option>  
                     </select> 
                     <p class="error" style="display: inline;" id="month_error"></p>
                     <?php echo form_error('month','<p class="error" style="display: inline;">','</p>'); ?>  
                </div>
            </div>
            <div class="col-sm-3 mb-10 hide">
                  <label>Year</label>
                    <div>
                    <select class="customFilter form-control " name="year" id="year" <?php echo $disable;?>>
                         <!-- <option disabled selected>Years</option> -->
                         <?php
                         for ($i = 0; $i <= 30; $i++) {
                           $year= date('Y', strtotime("+$i year"));
                           $selected = ($dataArr['year'] == $year)?'selected':'';
                           echo '<option value="'.$year.' "'.$selected.'>'.$year.'</option>';
                         }?>
                  </select>
                  <p class="error" style="display: inline;" id="year_error"></p>
                  <?php echo form_error('year','<p class="error" style="display: inline;">','</p>'); ?>
                </div>
            </div>
           
            </div>
            <div class="row">
                <div class="col-sm-12" style="margin-left:0px">
                <p style="color:black;">Please list below any stores condemned during the month, give quantity, item and cost, together with reason.</p>
            </div>
            </div>
            <div id="abc" class="sip-table" role="grid">
               <table class="header-fixed-new table-text-ellipsis table-layout-fixed table table-text-ellipsis table-layout-fixed">
                <thead class="t-header">
                  <tr>
                    <th width="10%">Quantity</th>
                    <th width="30%">Item</th>
                    <th width="20%">Cost in USD (if known)</th>
                    <th width="30%">Reason</th>
                    <th width="10%" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody class="em_data cstmProductTbl" id="mainTable">
                  <?php if(!empty($dataArr['json_data'])){
                      $json_data = unserialize($dataArr['json_data']);
                      if(!empty($json_data)){
                        $i=0;
                        $total_price = 0;
                        foreach($json_data as $r){
                            $total_price += $r['cost'];
                           ?>
                            <tr class="child_row" id="row_<?php echo $i?>">
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;">
                                    <input data-inputimp="1" class="qty_rows link quentity imp" type="number" name="item_qty[]" id="item_qty_<?php echo $i?>" value="<?php echo $r['quantity'];?>" min="1"><?php echo form_error('item_qty[]','<p class="error" style="display: inline;">','</p>'); ?>
                                </td>
                                <td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                    <select placeholder="Select Products" class="item_search imp" id="item_search_<?php echo $i?>" name="item_name[]" data-inputimp="1">
                                    <?php if(!empty($productData)){ 
                                          foreach($productData as $p){
                                           $selected = ($p->product_id == $r['product_id'])?'selected':''; ?> 
                                             <option value="<?php echo $p->product_id;?>" <?php echo $selected;?>><?php echo addslashes($p->product_name);?></option>  
                                        <?php 
                                         }
                                        } 
                                    ?>
                                </select>
                                <?php echo form_error('item_name[]','<p class="error" style="display: inline;">','</p>'); ?>
                                </td>
                                <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                    <input type="text" data-inputimp="1" class="cost imp" id="cost_<?php echo $i?>" name="cost[]" value="<?php echo $r['cost'];?>" onchange="calculateTotal(this.value);">
                                    <?php echo form_error('cost[]','<p class="error" style="display: inline;">','</p>'); ?>
                                </td>
                                <td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                  <input type="text" data-inputimp="1" class="link imp" name="item_reason[]" id="item_reason_<?php echo $i?>" value="<?php echo $r['reason'];?>">
                                  <?php echo form_error('item_reason[]','<p class="error" style="display: inline;">','</p>'); ?>
                                </td>
                                <td width="10%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;"><a href="javascript:void(0)" onclick="removeRow('<?php echo $i?>');"><i class="fas fa-minus-circle"></i></a>

                                </td>
                            </tr>
                            <?php 
                            $i++;
                        }
                      }
                   } ?>
                </tbody>
                <div class="text-right">
                    <a class="btn btn-success btn-slideright mt-5" id="add_new_prdct" href="javascript:void(0)">Add New <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
               </div>
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align:right;font-weight: bold;">Total</td>
                        <td colspan="2" style="text-align:left;font-weight: bold;"><input type="text" name="total_amount" id="total_amount" value="<?php echo (!empty($total_price))?$total_price:0;?>" readonly></td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align:right;font-weight: bold;">Master</td>
                        <td colspan="2" style="text-align:right;font-weight: bold;"><input type="text" name="master" class="form-control" value="<?php echo ($dataArr['captain_user_id'])?$dataArr['captain_user_id']:'';?>" <?php echo $disable;?>></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:right;font-weight: bold;">Cook/Steward</td>
                        <td colspan="2" style="text-align:right;font-weight: bold;"><input type="text" name="cook" class="form-control" value="<?php echo ($dataArr['cook_user_id'])?$dataArr['cook_user_id']:'';?>" <?php echo $disable;?>></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:right;font-weight: bold;">Witness Officer (Rank)</td>
                        <td colspan="2" style="text-align:right;font-weight: bold;"><input type="text" name="witness_officer_rank" class="form-control" value="<?php echo ($dataArr['witness_officer_rank'])?$dataArr['witness_officer_rank']:'';?>" <?php echo $disable;?>></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:right;font-weight: bold;">Date</td>
                        <td colspan="2" style="text-align:right;font-weight: bold;"><input type="text" name="created_date" class="form-control" value="<?php echo date('Y-m-d');?>" readonly></td>
                    </tr>
                </tbody>

               </table>
               <input type="hidden" name="ttl_prdct" id="ttl_prdct" value="<?php echo ($dataArr['ttl_prdct'])?$dataArr['ttl_prdct']:0;?>">
               <input type="hidden" name="actionType" id="actionType" value="save">
                <input type="hidden" name="id" value="<?php echo $condemned_report_id;?>">       
                <input type="hidden" name="mode" value="<?php echo $mode;?>">       

               <!-- <div class="text-right">
                    <a class="btn btn-success btn-slideright mt-5" id="add_new_prdct" href="#">Add New <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
               </div> -->
            </div>
            <div class="form-footer">
              <div class="pull-right">
                   <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                  
                    <!-- <button type="button" class="btn btn-success btn-slideright mr-5" onclick="submitMoldelForm('add_csr','report/add_condemned_stock_report','98%')">Save</button> -->
                    <button type="button" class="btn btn-success btn-slideright mr-5" onclick="submitReport();">Save</button>
              </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </form>
<script src="<?php echo base_url().'assets/js/select2.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/select2.css'?>">

<script type="text/javascript">
function submitReport(){
    var error = false;
    var shipping_company_id = $('#shipping_company_id').val();
    var ship_id = $('#ship_id_a').val();
    var month = $('#month').val();
    var year = $('#year').val();
    var role = '<?php echo $role;?>';
    if(role == 'super_admin'){
        if(shipping_company_id == ''){
          $('#shipping_company_error').text('Shipping Company is required')
          error = true;
        }else{
            $('#shipping_company_error').text('')
            error = false;
        }
        if(ship_id == ''){
          $('#ship_error').text('Ship is required')
          error = true;
        }
        else{
            $('#ship_error').text('')
            error = false;
        }
    }
    if(month == ''){
      $('#month_error').text('Month is required')
      error = true;
    }
    else{
        $('#month_error').text('')
        error = false;
    }
    if(year == '' || year == null){
      $('#year_error').text('Year is required')
      error = true;
    }
    else{
        $('#year_error').text('')
        error = false;
    }
      var total_rows = $('#ttl_prdct').val();
      if(total_rows !== 0){
       for(var i=0;i<total_rows;i++){
         var cost = $('#cost_'+i).val();
         var reason = $('#item_reason_'+i).val();
         cost = (cost == undefined)?'':cost;
         reason = (reason == undefined)?'':reason;
         console.log(cost+'---'+reason);
         if(cost === '' && reason === ''){
            $('#cost_'+i).css('border','1px solid red');
            $('#item_reason_'+i).css('border','1px solid red');
            error = true;
         }else if(cost === '' && reason !== ''){
            $('#cost_'+i).css('border','1px solid red');
            $('#item_reason_'+i).css('border','');
            error = true;
         }
         else if(cost !== '' && reason === ''){
            $('#cost_'+i).css('border','');
            $('#item_reason_'+i).css('border','1px solid red');
            error = true;
         }else{
            $('#cost_'+i).css('border','');
            $('#item_reason_'+i).css('border','');
            error = false;
            if(shipping_company_id == '' || ship_id == ''){
                error = true;
            }
         }
       }
    }else{
        if(total_rows !== 0){
            $('#ttl_prdct').val('0');
        }
        error = true;
    }
    console.log('finalerror--'+error);
    if(error === false){
      //submitMoldelForm('add_csr','report/add_condemned_stock_report','98%') ;
      submitAjax360Form('add_csr','report/add_condemned_stock_report','98%','condemned_stock_list'); 
    }else{
       return false;
     }
}

function getAllShipsById(shipping_company_id){
       $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'user/getShipsByCompanyId',
            data: {'shipping_company_id':shipping_company_id,'ship_id':'<?php echo $dataArr['ship_id'];?>'},
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('#ship_id_a').html(obj.data);
            }
        });   
   } 

var mode = 'add';
    $(document).ready(function(){
       $('#add_new_prdct').on('click',function(){
          var ttl_prdct = $('#ttl_prdct').val();
          var html = '<tr class="child_row " id="row_'+ttl_prdct+'"><td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;"><input class="qty_rows link quentity imp" type="number" name="item_qty[]" id="item_qty_'+ttl_prdct+'" value="1" min="1" data-inputimp="1"><?php echo form_error('item_qty[]','<p class="error" style="display: inline;">','</p>'); ?></td><td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><select placeholder="Select Products" class="item_search imp" data-inputimp="1" id="item_search_'+ttl_prdct+'" name="item_name[]" multiple><?php if(!empty($productData)){ foreach($productData as $p){ ?> <option value="<?php echo $p->product_id;?>"><?php echo addslashes($p->product_name);?></option>  <?php }} ?></select><?php echo form_error('item_name[]','<p class="error" style="display: inline;">','</p>'); ?></td><td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="cost imp" data-inputimp="1" id="cost_'+ttl_prdct+'" name="cost[]" onchange="calculateTotal(this.value);"><?php echo form_error('cost[]','<p class="error" style="display: inline;">','</p>'); ?></td><td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link imp" data-inputimp="1" name="item_reason[]" id="item_reason_'+ttl_prdct+'" value=""><?php echo form_error('item_reason[]','<p class="error" style="display: inline;">','</p>'); ?></td><td width="10%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;"><a href="javascript:void(0)" onclick="removeRow('+ttl_prdct+');"><i class="fas fa-minus-circle"></i></a></td></tr>';
            
            $('.cstmProductTbl').append(html);
            $('#item_search_'+ttl_prdct).select2({
                ajax: {
                url: base_url + 'product/getProductsBySearch',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1                        
                    }
                    return query;
                },
                
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 100) < data.total_count
                        }
                    };
                },
                    cache: false
                  },
                   multiple: false,
                  minimumInputLength: 0,
                  allowHtml: true,
                  allowClear: true,
                  placeholder: 'Select Products',
                  closeOnSelect: true,
                  dropdownParent: $("#modal-view-datatable")
              });
              
              convertToExcel();

              ttl_prdct = parseFloat(ttl_prdct)+1;
              $('#ttl_prdct').val(ttl_prdct); 
       });     
     })  


function calculateTotal(val){
   var sum = 0;
    $('.cost').each(function () {
      sum += parseFloat($(this).val() || 0,2);
    });
    //console.log(sum.toFixed(2));
    $('#total_amount').val(parseFloat(sum,10).toFixed(2));

}
$(document).ready(function(){
    getAllShipsById('<?php echo $dataArr['shipping_company_id'];?>');
    $('.item_search').select2({
                ajax: {
                url: base_url + 'product/getProductsBySearch',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1                        
                    }
                    return query;
                },
                
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 100) < data.total_count
                        }
                    };
                },
                    cache: false
                  },
                  minimumInputLength: 0,
                  allowHtml: true,
                  allowClear: true,
                  placeholder: 'Select Products',
                  closeOnSelect: false,
                  dropdownParent: $("#modal-view-datatable")
              });
    // $(".item_search").addClass("select2-hidden-accessible");

    convertToExcel()
})

function removeRow(id){
  var total_rows = $('#ttl_prdct').val();
  $('#row_'+id).remove();
  total_rows = total_rows-1;
  $('#ttl_prdct').val(total_rows);
  $('.cost').trigger('change');
  $("#mainTable").find('tr').each(function (i){
    var rowId = $(this).attr('id');
    $(this).attr('id', rowId.replace(/\d/, i));
        
    var btnAttr = $(this).find('a').attr('onclick');
    $(this).find('a').attr('onclick', btnAttr.replace(/\d/, i));

    $(this).find('td').each(function(j) {
        var idAttr = $(this).find('input.imp').attr('id');
        if(idAttr !== undefined){
            $(this).find('input.imp').attr('id', idAttr.replace(/\d/, i));
        }
        
    });
  });
}

function convertToExcel(){
      var tr,td,cell;
      td=$("td").length;
      tr=$("tr").length;
      cell=td/(tr-1);//one tr have that much of td
      //alert(cell);
      $("td").keydown(function(e)
      {
        switch(e.keyCode)
        {
          case 37 : var first_cell = $(this).index();
                if(first_cell==0)
                {
                $(this).parent().prev().children("td:last-child").children('input.imp').focus();
                }else{
                $(this).prev("td").children('input.imp').focus();break;//left arrow
                              }
          case 39 : var last_cell=$(this).index();
                if(last_cell==cell-1)
                {
                $(this).parent().next().children("td").eq(0).children('input.imp').focus();
                }
                $(this).next("td").children('input.imp').focus();break;//right arrow
          case 40 : var child_cell = $(this).index(); 
                $(this).parent().next().children("td").eq(child_cell).children('input.imp').focus();break;//down arrow
          case 38 : var parent_cell = $(this).index();
                $(this).parent().prev().children("td").eq(parent_cell).children('input.imp').focus();break;//up arrow
        }
        // if(e.keyCode==113)
        // {
        //   $(this).children().focus();
        // }
      });

      $("td").focusin(function()
      {
        $(this).css("outline","solid steelblue 3px");//animate({'borderWidth': '3px','borderColor': '#f37736'},100);
        //console.log("Hello world!");
        var qnt = $(this).children('input.imp').data('inputimp');
        if(qnt==1){
          $(this).children('input.imp').focus();
        }
      });

      $("td").focusout(function()
      {
        $(this).css("outline","none");
      });

    };
</script>