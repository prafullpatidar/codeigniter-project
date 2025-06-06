<?php $user_session_data = getSessionData();?>
<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="panel-body">
        <form class="form-horizontal form-bordered" name="addRevisedstock" enctype="multipart/form-data" id="addRevisedstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                   <div class="row mb-2 <?php echo ($dataArr['requisition_type']=='provision') ? '' : 'hide';?>">            
                     <div class="form-group col-sm-3 <?php echo (form_error('no_of_day')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">No of Days <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" onchange="recommQty()" class="form-control" name="no_of_day" id="no_of_day" value="<?php if(!empty($dataArr['no_of_day'])){echo set_value('no_of_day',$dataArr['no_of_day']);}?>">
                                <?php echo form_error('no_of_day','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-3 <?php echo (form_error('no_of_people')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">No of People <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" onchange="recommQty()" class="form-control" name="no_of_people" id="no_of_people" value="<?php if(!empty($dataArr['no_of_people'])){echo set_value('no_of_people',$dataArr['no_of_people']);}?>">
                                <?php echo form_error('no_of_people','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-3 <?php echo (form_error('no_of_people')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Requisition Type <span>*</span></label>
                            <div class="col-sm-12">
                                <select disabled name="requisition_type" id="requisition_type" class="form-control">
                                   <option value="">Select Type</option>
                                   <option <?php echo ($dataArr['requisition_type']=='provision') ? 'selected="selected"' : '';?> value="provision">Provisions</option>
                                   <option <?php echo ($dataArr['requisition_type']=='bonded_store') ? 'selected="selected"' : '';?> value="bonded_store">Bonded Store</option>
                                   <option <?php echo ($dataArr['requisition_type']=='stores') ? 'selected="selected"' : '';?> value="stores">Stores</option> 
                                </select>
                                <?php echo form_error('no_of_people','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-3 <?php echo (form_error('port_id')) ? 'has-error':'';?>" >
                         <label class="col-sm-12">Port Name / Arriving date <span>*</span></label>
                        <div class="col-sm-12">
                            <select name="port_id" id="port_id" class="form-control">
                                <option value="">Select Port</option>
                                 <?php
                                  if(!empty($all_ports)){
                                    foreach ($all_ports as $row) {
                                      ?>
                                      <option <?php echo ($dataArr['port_id']==$row->port_id) ? 'selected="selected"' : '';?>   value="<?php echo $row->port_id?>"><?php echo ucwords($row->name).' ('.ucwords($row->country).') | '.convertDate($row->date,'','d-m-Y');?></option>
                                    <?php }
                                  }
                                 ?>
                            </select>
                             <?php echo form_error('port_id','<p class="error" style="display: inline;">','</p>'); ?>
                        </div>
                     </div>
                </div>
                <div id="abc" class="sip-table" role="grid">
               <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Item No.</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>Last Count QTY</th>
                  <th>RFQ QTY</th>
                  <th width="50%">Remark</th>
                  <!-- <th>Revised Qty</th> -->
                  </tr>
                </thead>
                    <tbody class="item_data">
                    <?php
                     if(!empty($productArr)){
                            $glc = 0;
                            foreach ($productArr as $parent => $rows) {
                                foreach($rows as $category => $products){
   $catImp = str_replace(array(',',' ','/','&'),array('_','','_','_'),$category);
                                  $catImp = trim($catImp); 
                                  $child_row_count = 0;
                                  $last_count = 0;
                                   $returnArr .= '<tr class="parent_row">
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'<strong></td>
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                    </tr>';
                                for ($i=0; $i <count($products) ; $i++) { 
                                      $product_id = $products[$i]['product_id'];
                                      $group_name = strtolower(str_replace(array('&',' '),array('_',''), $products[$i]['group_name']));
                                      $last_count += $products[$i]['last_count_qty'];

                                      $child_row_count = $child_row_count + $products[$i]['quantity'];
                                      $returnArr .= '<tr class="child_row">';
                                      $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                      $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                      $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                      $returnArr .= '<td role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="'.$group_name.'" data-value="'.$products[$i]['last_count_qty'].'">'.$products[$i]['last_count_qty'].'</td>';
                                      
                                       $returnArr .= '<td  role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="qty_rows link quentity qtyChange'.$catImp.'" data-quantity="1" data-group="'.$group_name.'" type="text" name="qty_'.$product_id.'" data-value="'.$dataArr['qty_'.$product_id].'" value="'.$dataArr['qty_'.$product_id].'" id="product_id_'.$product_id.'" onchange="getCategoryTotal(this.value,\''.$catImp.'\');">';

                                      $returnArr .= form_error('qty_'.$product_id,'<p class="error" style="color:#ff0000;display: inline;">','</p>');

                                      $returnArr .= '</td>'; 
                                      if($user_session_data->code == 'super_admin'){
                                        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="" data-remark="1" data-group="'.$group_name.'" type="text" name="remark_'.$product_id.'" data-value="'.$dataArr['remark_'.$product_id].'" value="'.$dataArr['remark_'.$product_id].'" id="remark_'.$product_id.'"></td>';
                                       }else{
                                        $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['remark'].'</td>';
                                        }
                                        
                                      $returnArr .= '</tr>';
                                    }
                                   $glc += $last_count;
                                   $returnArr .= '<tr class="child_parent_row_count">
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key">Total</td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($last_count,2).'</td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="total_'.$catImp.'" type="text" disabled name="total" id="total_'.$catImp.'" value="'.number_format($child_row_count,2).'" style="font-weight: bold;"></td>
                                     <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                  </tr>';
                                }
                            }
                           $returnArr .= '<tr class="child_parent_row_count">
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td role="gridcell" class="text-left totalLbl" tabindex="-1" aria-describedby="f2_key" align="center">Grand Total</td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($glc,2).'</td>
                                      <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="bold-input" type="text" disabled name="grand_total" id="grand_total" value="0" style="font-weight: bold;"></td>
                                        <td colspan="" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                  </tr>';
                          }
            
                        echo $returnArr;
                    ?>  
                    </tbody> 
                 </table>

                 <br>
              <?php
                if($dataArr['requisition_type']=='provision'){
              ?>   
                <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Group</th>
                  <th>Unit</th>
                  <th>Per Man Per Day</th>
                  <th>Last Count Stock QTY </th>
                  <th>Ordered QTY</th>
                  <th>Recom QTY</th>
                  <th>Difference QTY</th>
                </tr>
                </thead>
                    <tbody class="group_data">
                        <?php
                         if(!empty($group_products)){
                           foreach ($group_products as $row) {
                            $group_name = strtolower(str_replace(array('&',' '),array('_',''), $row->name));
                               if($row->unit == 1){
                                  $unit = "KG"; 
                                }else if($row->unit == 2){
                                      $unit = "Liter"; 
                                }
                            ?>
                         <tr>
                             <td><?php echo  ucfirst($row->name);?></td>
                             <td><?php echo $unit;?></td>
                             <td><?php echo $row->consumed_qty;?></td>
                             <td class="last_count" id="last_count_<?php echo $group_name;?>">0</td>
                             <td class="ordered_qty" data-group="0" id="ordered_qty_<?php echo $group_name;?>">0</td>
                             <td class="recom_qty" id="recom_qty_<?php echo $group_name;?>" data-group="<?php echo $group_name;?>" data-value="<?php echo $row->consumed_qty;?>" data-qty="0">0</td>
                             <td class="diff_qty" id="diff_qty_<?php echo $group_name;?>">0</td>
                             
                         </tr>
                         <?php
                            } 
                         }
                        ?>
                    </tbody> 
               </table>
           <?php } ?>
             </div>
           </div>
         </div>
            <input type="hidden" name="actionType" id="actionType" value="save">
            <input type="hidden" name="id" value="<?php echo $dataArr['ship_order_id'];?>">        
       </form>
     </div>
   </div>
 </div>
        <div class="clearfix"></div>
              <div class="form-footer">
                  <div class="pull-right">
                   <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                  <button type="button" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360Form('addRevisedstock','shipping/review_rfq','98%','order_request_list');">Submit</button>
              </div>
        <div class="clearfix"></div>
    </div><!-- /.form-footer -->
<script type="text/javascript">
 $(document).ready(function(){

     var last_meat = 0;
     var last_fruit = 0;
     var last_rice = 0;
     $('.group_avalible_stock').each(function(){
        var group = $(this).data('group');
        var value = $(this).data('value');
        if(group=='meat'){
         last_meat = last_meat+value;   
        }
        else if(group=='fruit_vegetables'){
         last_fruit = last_fruit+value;   
        }
        else if(group=='rice_flour'){
         last_rice = last_rice+value;
        }
     })

     $('#last_count_meat').html(parseFloat(last_meat,10).toFixed(2));
     $('#last_count_fruit_vegetables').html(parseFloat(last_fruit,10).toFixed(2));
     $('#last_count_rice_flour').html(parseFloat(last_rice,10).toFixed(2));
     


    //  $('.qty_rows').each(function(){
    //     var group = $(this).data('group');
    //     var value = $(this).data('value');
    //     value = parseFloat(value);
    //     if(!isNaN(value)){
    //     if(group=='meat'){
    //      order_meat = (parseFloat(order_meat) + value);   
    //     }
    //     else if(group=='fruit_vegetables'){
    //      order_fruit = (parseFloat(order_fruit) +value);   
    //     }
    //     else if(group=='rice_flour'){
    //      order_rice = (parseFloat(order_rice) + value);
    //     }
    //     }
    //   })

    // $('#ordered_qty_meat').html(order_meat);
    // $('#ordered_qty_meat').data('value',order_meat);
    // $('#ordered_qty_fruit_vegetables').html(order_fruit);
    // $('#ordered_qty_fruit_vegetables').data('value',order_fruit);
    // $('#ordered_qty_rice_flour').html(order_rice);
    // $('#ordered_qty_rice_flour').data('value',order_rice);

   $('.qty_rows').change(function(){
      var order_meat = 0;
      var order_fruit = 0;
      var order_rice = 0;
      $('.qty_rows').each(function(){
         var order_qty = $(this).val();
         if($(this).data('group')=='meat'){    
          order_meat = parseFloat(order_meat)+ parseFloat(order_qty || 0);
         }        
         else if($(this).data('group')=='fruit_vegetables'){
          order_fruit = parseFloat(order_fruit)+parseFloat(order_qty || 0);
          }
         else if($(this).data('group')=='rice_flour'){
         order_rice = parseFloat(order_rice) + parseFloat(order_qty || 0);
         }
      })

        $('#ordered_qty_meat').html(parseFloat(order_meat,10).toFixed(2));
        $('#ordered_qty_meat').data('value',order_meat);
        $('#ordered_qty_fruit_vegetables').html(parseFloat(order_fruit,10).toFixed(2));
        $('#ordered_qty_fruit_vegetables').data('value',order_fruit);
        $('#ordered_qty_rice_flour').html(parseFloat(order_rice,10).toFixed(2));
        $('#ordered_qty_rice_flour').data('value',order_rice);

        // $('#ordered_qty_meat').html(order_meat);
        // $('#ordered_qty_meat').data('value',order_meat);
        // $('#ordered_qty_fruit_vegetables').html(order_fruit);
        // $('#ordered_qty_fruit_vegetables').data('value',order_fruit);
        // $('#ordered_qty_rice_flour').html(order_rice);
        // $('#ordered_qty_rice_flour').data('value',order_rice);
        recommQty();
    })
    
    $('.qty_rows').trigger('change');
  })
   
 $(document).ready(function(){
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
                $(this).parent().prev().children("td:last-child").focus();
                }else{
                $(this).prev("td").focus();break;//left arrow
                              }
          case 39 : var last_cell=$(this).index();
                if(last_cell==cell-1)
                {
                $(this).parent().next().children("td").eq(0).focus();
                }
                $(this).next("td").focus();break;//right arrow
          case 40 : var child_cell = $(this).index(); 
                $(this).parent().next().children("td").eq(child_cell).focus();break;//down arrow
          case 38 : var parent_cell = $(this).index();
                $(this).parent().prev().children("td").eq(parent_cell).focus();break;//up arrow
        }
        if(e.keyCode==113)
        {
          $(this).children().focus();
        }
      });

      $("td").focusin(function()
      {
        $(this).css("outline","solid steelblue 3px");//animate({'borderWidth': '3px','borderColor': '#f37736'},100);
        //console.log("Hello world!");
        var qnt = $(this).children('input.quentity').data('quantity');
        if(qnt==1){
          $(this).children('input.quentity').focus();
        }
      });

      $("td").focusout(function()
      {
        $(this).css("outline","none");//.animate({'borderWidth': '1px','borderColor': 'none'},500);
      });

      $(".quentity").focusin(function()
      {
        //$(".quentity").value('Amittttt');
                //alert('asdas');
      });

  })


  function recommQty(){
    var days = $('#no_of_day').val();
    var people = $('#no_of_people').val();
    days = parseInt(days);
    people = parseInt(people);
    if(!isNaN(days) && !isNaN(people)){
       $('.recom_qty').each(function(){
          var con = $(this).data('value');
          var group = $(this).data('group');
          var recomm_qty = (days*people*con);
          $(this).html(parseFloat(recomm_qty,10).toFixed(2));
          $(this).data('qty',recomm_qty);
          var order_qty = $('#ordered_qty_'+group).data('value');
          order_qty = parseFloat(order_qty);
          if(!isNaN(order_qty)){
           var diff_qty = (recomm_qty - order_qty); 
           $('#diff_qty_'+group).html(parseFloat(diff_qty,10).toFixed(2));
          }
       })    
    }
 } 

 $(document).ready(function(){
    var grandtotal = 0;
    $('.qty_rows').each(function () {
     grandtotal += parseFloat($(this).val() || 0);
     var category = $(this).attr('class');
     categoryArr = category.split(' ');
     category = categoryArr[3];
     var newCatArr = category.split('ge');
     var updatedCategory = newCatArr[1];
     
     var sum = 0;
    $('.qtyChange'+updatedCategory).each(function () {
      sum += parseFloat($(this).val() || 0);
    });

    $("#total_"+updatedCategory).val(parseFloat(sum,10).toFixed(2));
    });
    $("#grand_total").val(parseFloat(grandtotal,10).toFixed(2));
}) 

function getCategoryTotal(qty,category){ 
   var sum = 0;
    $('.qtyChange'+category).each(function () {
      sum += parseFloat($(this).val() || 0);
    });
    $("#total_"+category).val(parseFloat(sum,10).toFixed(2));
    var grandtotal = 0;
    $('.qty_rows').each(function () {
     grandtotal += parseFloat($(this).val() || 0);
    });
    $("#grand_total").val(parseFloat(grandtotal,10).toFixed(2));

 }


  $(document).ready(function () {       
            $('.qty_rows').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });
 });  
</script>