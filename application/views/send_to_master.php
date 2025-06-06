<?php
$user_session_data = getSessionData();
?>
    <form class="form-horizontal form-bordered" name="addEditstock" enctype="multipart/form-data" id="addEditstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                  <div class="row mb-30"><div class="col-md-12">
                    <div class="inc-dec-price">
                   <?php 
                    if($dataArr['requisition_type']=='provision'){
                   ?>
                   
                    <div>
                        <label>No Of Days <span>*</span></label>
                        <div>
                          <input <?php echo (!empty($dataArr['second_id'])) ? 'readonly' : '';?> type="number" min="0" max="100" name="no_of_day" id="no_of_day" class="form-control" value="<?php echo $dataArr['no_of_day'];?>">
                           <?php echo  form_error('no_of_day','<p class="error">','</p>')?>
                        </div>
                    </div>
                    <div>
                        <label>No Of People <span>*</span></label>
                        <div>
                          <input <?php echo (!empty($dataArr['second_id'])) ? 'readonly' : '';?> type="number" min="0" max="100" name="no_of_people" class="form-control" id="no_of_people" value="<?php echo $dataArr['no_of_people'];?>">
                           <?php echo  form_error('no_of_people','<p class="error">','</p>')?>
                        </div>
                    </div>
                  <?php 
                   }
                   ?>
                    <div>
                        <label>Delivery Port</label>
                        <div>
                          <?php echo $dataArr['port_name'];?>
                        </div>
                    </div>
                    <div>
                        <label>Lead Time</label>
                        <div>
                          <?php echo $dataArr['lead_time'].' Days';?>
                          <?php //echo ConvertDate($dataArr['lead_time'],'','d-m-Y h:i A'); ?>
                          <input type="hidden" name="lead_time" id="lead_time" value="<?php echo $dataArr['lead_time'];?>">
                        </div>
                    </div>
                     <?php 
                  // $unitPriceDis ='';
                  if($user_session_data->code != 'captain' && $user_session_data->code != 'cook'){
                    // $unitPriceDis = 'disabled';
                   ?>
                    <div>
                        <label>Decrease Price</label>
                        <div>
                          <input <?php echo (!empty($dataArr['second_id'])) ? 'readonly' : '';?> style="width: 150px" type="text" name="dec_price"  id="dec_price" class="form-control parcentage" value="<?php echo $dataArr['dec_price']?>">&nbsp;<strong>%</strong>
                        </div>
                    </div>
                    <div>
                        <label>Increase Price</label>
                        <div>
                          <input <?php echo (!empty($dataArr['second_id'])) ? 'readonly' : '';?> style="width: 150px" type="text" class="form-control parcentage" name="inc_price" id="inc_price" value="<?php echo $dataArr['inc_price']?>">&nbsp;<strong>%</strong>
                        </div>
                    </div>
                    <div>
                        <label>Price Remark <span>*</span></label>
                        <div>
                          <textarea <?php echo (!empty($dataArr['second_id'])) ? 'readonly' : '';?> name="price_remark" id="price_remark" class="form-control"><?php echo $dataArr['price_remark'];?></textarea>
                          <?php echo  form_error('price_remark','<p class="error">','</p>')?>
                        </div>
                    </div>
                  </div>
                    
                    </div>
                  </div>
                  <?php }
                  else{
                    ?>
                   <input type="hidden" name="dec_price" value="<?php echo $dataArr['dec_price']?>">
                   <input type="hidden" name="inc_price" value="<?php echo $dataArr['inc_price']?>">
                   <input type="hidden" name="price_remark" value="<?php echo $dataArr['price_remark']?>">
                   
                   <?php 
                    }
                   ?>
                <div id="abc" class="sip-table" role="grid">
               <table class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                <thead class="t-header">
                <tr>
                  <th width="8%">Item No.</th>
                  <th width="17%">Description</th>
                  <th width="7%">Unit</th>
                  <th width="8%">Last Count Qty</th>
                  <th width="8%">RFQ QTY</th>
                  <th width="15%">RFQ Remark</th>
                  <th width="13%">Available Quoted QTY</th>
                  <th width="8%">Unit Price($)</th>
                  <th width="8%">Total Price($)</th>
                  <?php //if($user_session_data->code == 'super_admin'){?>
                    <th width="10%">Vendor Remark</th>  
                    <?php //} ?>
                  <!-- <th>Attachment</th>         -->
                  </tr>
                </thead>
                    <tbody class="item_data">
                      <?php 
                       if(!empty($productArr)){
                           $grand_rfq_qty = 0;
                           $grand_avali_qty = 0;
                           $grand_total = 0;
                           foreach ($productArr as $parent => $rows) {
                                foreach($rows as $category => $products){
                                  $catImp = str_replace(array(',',' ','/','&'),array('_','','_','_'),$category);
                                  $catImp = trim($catImp);
                                 $returnArr .= '<tr class="parent_row">
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td  width="17%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                      <td width="13%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                                      // if($user_session_data->code == 'super_admin'){
                                      $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                                      // }            
                                      $returnArr .= '</tr>';
                                      $cat_rfq_qty = 0;
                                      $cat_avali_qty =0;
                                      $total = 0;
                                     for ($i=0; $i <count($products) ; $i++) {
                                        $cat_rfq_qty += $products[$i]['order_qty'];
                                        $cat_avali_qty += $products[$i]['vendor_qty'];

                                      $group_name = strtolower(str_replace(array('&',' '),array('_',''), $products[$i]['group_name']));  

                                     //  $av_stock = $stock_used[$products[$i]['product_id']]['total_stock'];
                                     //  $ud_stock = ($stock_used[$products[$i]['product_id']]['used_stock']) ? $stock_used[$products[$i]['product_id']]['used_stock'] : 0 ;                            
                                     // $avalible_stock =  ($av_stock - $ud_stock);
                                     $avalible_stock = ($row->available_stock) ? $row->available_stock : 0;
                                     $avalible_stock_total = $avalible_stock_total+$avalible_stock; 

                                        $returnArr .= '<tr class="child_row">';
                                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                        $returnArr .= '<td width="17%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                        $returnArr .= '<td width="12%" role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="'.$group_name.'" data-value="'.$avalible_stock.'">'.number_format($avalible_stock,2).'</td>';
                                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['order_qty'],2).'</td>';
                                        $returnArr .= '<td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['order_remark'].'</td>';
      if(empty($dataArr['second_id'])){
         $returnArr .= '<td width="13%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
        <input '.$unitPriceDis.' type="text" onchange="getCategoryTotal(this.value,\''.$catImp.'\');" class="link quentity qty_rows qtyChange'.$catImp.'" data-quantity="1" data-group="'.$group_name.'" name="revised_qty_'.$products[$i]['product_id'].'" value="'.str_replace(',','',number_format($products[$i]['vendor_qty'],2)).'" id="revised_qty_'.$products[$i]['product_id'].'">'.form_error('revised_qty_'.$products[$i]['product_id']).'</td>';  
       }
       else{
        $returnArr .= '<td width="13%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="hidden"  class="link quentity qty_rows qtyChange'.$catImp.'" data-quantity="1" data-group="'.$group_name.'" name="revised_qty_'.$products[$i]['product_id'].'" value="'.str_replace(',','',number_format($products[$i]['vendor_qty'],2)).'" id="revised_qty_'.$products[$i]['product_id'].'">'.number_format($products[$i]['vendor_qty'],2).'</td>'; 
       }

       $value = $products[$i]['unit_price'];

       if(!empty($dataArr['inc_price'])){
         $inc_by = ($dataArr['inc_price']/100);
         $new_value = $value*$inc_by;
         $value = $value+$new_value;
       }
       elseif(!empty($dataArr['dec_price'])){
         $dec_by = ($dataArr['dec_price']/100);
         $new_value = $value*$dec_by;
         $value = $value-$new_value;
       }

         $total += ($products[$i]['vendor_qty']*$value);

 
 $returnArr .= '<td width="8%" role="gridcell" id="unit_price_'.$products[$i]['product_id'].'" class="unit_price" data-category ='.$catImp.' tabindex="-1" data-id="'.$products[$i]['product_id'].'" aria-describedby="f2_key" data-value="'.$products[$i]['unit_price'].'">'.str_replace(',','',number_format($value,2)).'</td>';
  
  $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="total_'.$products[$i]['product_id'].'" class="priceChange'.$catImp.'">'.number_format(($products[$i]['vendor_qty']*$value),2).'</td>';
      // if($user_session_data->code == 'super_admin'){
          $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['vendor_remark'].'</td>'; 
      // }       
                       
                                        $returnArr .= '</tr>'; 
                         }
                       }
              $returnArr .= '<tr class="child_row_count">
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Total</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.number_format($cat_rfq_qty,2).'</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key" class="cat_total" data-id="qty" id="total_'.$catImp.'">'.number_format($cat_avali_qty,2).'</td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" class="cat_total" data-id="price" tabindex="-1" aria-describedby="f2_key"  id="priceChange'.$catImp.'">'.$total.'</td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          </tr>';
                          $grand_rfq_qty += $cat_rfq_qty;
                          $grand_avali_qty += $cat_avali_qty;
                          $grand_total += $total; 
                             }
                                $returnArr .= '<tr class="parent_row_count">
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Grand Total</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.number_format($grand_rfq_qty,2).'</strong></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_total">'.number_format($grand_avali_qty,2).'</td>
                               <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_price">'.number_format($grand_total,2).'</td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          </tr>';   
                          }
                          echo $returnArr;
                      ?>
                    </tbody>
                    <?php echo form_error('product_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
               </table>
                </div> 
                <?php 
                    if($dataArr['requisition_type']=='provision'){
                   ?>
                <br><div class="sip-table mb-15 w-100" role="grid">
            <table class="table predefineProTbl">
                <thead>
                <tr>
                  <th width="25%">Group</th>
                  <th width="12%">Unit</th>
                  <th width="12%">Per Man Per Day</th>
                  <th width="15%">Last Count Stock QTY </th>
                  <th width="12%">Ordered QTY</th>
                  <th width="12%">Recom QTY</th>
                  <th width="12%">Difference QTY</th>
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
                             <td width="25%"><?php echo  ucfirst($row->name);?></td>
                             <td width="12%"><?php echo $unit;?></td>
                             <td width="12%"><?php echo $row->consumed_qty;?></td>
                             <td width="15%" class="last_count" id="last_count_<?php echo $group_name;?>">0</td>
                             <td width="12%" class="ordered_qty" data-group="<?php echo $group_name;?>" id="ordered_qty_<?php echo $group_name;?>">0</td>
                             <td width="12%" class="recom_qty" id="recom_qty_<?php echo $group_name;?>" data-group="<?php echo $group_name;?>" data-value="<?php echo $row->consumed_qty;?>" data-qty="0">0</td>
                             <td width="12%" class="diff_qty" id="diff_qty_<?php echo $group_name;?>">0</td>                           
                         </tr>
                         <?php
                            } 
                         }
                        ?>
                    </tbody> 
               </table>
             </div>
           <?php }
           ?>
           </div>
          </div>
                <input type="hidden" name="id" value="<?php echo $dataArr['ship_order_id'];?>">                  
                <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <?php
                     if(empty($dataArr['second_id'])){
                    ?>
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('addEditstock','shipping/send_to_master','98%','order_request_list')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                      <?php } ?>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        
    
<script type="text/javascript">

$(document).ready(function(){
    convertToExcel();
})  

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
    };


  $('#dec_price').keyup(function(){
     var parcent = parseFloat($(this).val() || 0);
     var inc_price = $('#inc_price').val();
     var new_par = 0;
     var dec_by = 0;
     var cat = [];
    if(inc_price == ''){
     $('.unit_price').each(function(){
       var id = $(this).attr('id');
       var val = $(this).data('value');
       var product_id = $(this).data('id');
       var qty = $('#revised_qty_'+product_id).val();
       var category = $(this).data('category');
       cat.push(category); 
       if(parcent){
          new_par = (parcent/100);
          dec_by = (val * new_par);
          var final_val = val - dec_by;
          $('#'+id).text(parseFloat(final_val,10).toFixed(2));
          $('#total_'+product_id).text(parseFloat(( qty * final_val),10).toFixed(2)) 
       }
       else{
         $('#'+id).text(val);
         $('#total_'+product_id).text(parseFloat(( qty * val),10).toFixed(2)); 
       } 
   //     $('.unit_price').trigger('change');
     })
     totalChange(cat);
   }else{
    alert('You can\'t apply both Increase and Decrease Operation simultaneously! Please choose only one operation.');
    $('#dec_price').val('');

   }
 })

 function totalChange(cat){
    var uniqueArray = Array.from(new Set(cat));
    for (var i = 0; i < uniqueArray.length; i++) {
      var total = 0;
      $('.priceChange'+uniqueArray[i]).each(function(){
         total += parseFloat($(this).text() || 0);
      })
     $('#priceChange'+uniqueArray[i]).text(parseFloat(total,10).toFixed(2));  
    }

    var grandPricetotal = 0;
    $('.cat_total').each(function () {
        var data_id = $(this).data('id');
        if(data_id=='qty'){
        }
        else{
         grandPricetotal+= parseFloat($(this).text() || 0);
        }
    });
    $("#grand_price").text(parseFloat(grandPricetotal,10).toFixed(2));    
 }

$('#inc_price').keyup(function(){
     var parcent = parseFloat($(this).val() || 0);
     var dec_price = $('#dec_price').val();
     var new_par = 0;
     var inc_by = 0;
     var cat = [];
    if(dec_price == ''){
     $('.unit_price').each(function(){
       var id = $(this).attr('id');
       var val = $(this).data('value');
       var product_id = $(this).data('id');
       var qty = $('#revised_qty_'+product_id).val();
       var category = $(this).data('category');
       cat.push(category); 
       if(parcent){
          new_par = (parcent/100);
          inc_by = (val * new_par);
          var final_val = (parseFloat(val) + parseFloat(inc_by));
          $('#'+id).text(parseFloat(final_val,10).toFixed(2));
          $('#total_'+product_id).text(parseFloat(( qty * final_val),10).toFixed(2)) 
       }
       else{
         $('#'+id).text(val);
         $('#total_'+product_id).text(parseFloat(( qty * val),10).toFixed(2)); 

       } 
   //     $('.unit_price').trigger('change');
     })
     totalChange(cat);
   }else{
    alert('You can\'t apply both Increase and Decrease Operation simultaneously! Please choose only one operation.');
    $('#dec_price').val('');

   }
 })  

function getCategoryTotal(qty,category){ 
   var sum = 0;
   var price = 0; 
    $('.qtyChange'+category).each(function () {
      sum += parseFloat($(this).val() || 0);
      var id = $(this).attr('id');
      id = id.split('_')[2];
      var unit_price = getcleanedValue($('#unit_price_'+id).text());
      var total = (unit_price * getcleanedValue($(this).val()));
      $('#total_'+id).text(parseFloat(total,10).toFixed(2));
      price+=total;
    });
    $("#total_"+category).text(parseFloat(sum,10).toFixed(2));
    $("#priceChange"+category).text(parseFloat(price,10).toFixed(2));


    var grandQtytotal = 0;
    var grandPricetotal = 0;
    $('.cat_total').each(function () {
        var data_id = $(this).data('id');
        if(data_id=='qty'){
         grandQtytotal+= parseFloat($(this).text() || 0);
        }
        else{
         grandPricetotal+= parseFloat($(this).text() || 0);
        }
    });
    $("#grand_total").text(parseFloat(grandQtytotal,10).toFixed(2));
    $("#grand_price").text(parseFloat(grandPricetotal,10).toFixed(2));
 }  

$(document).ready(function () {       
            $('.quentity').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });
             $('.parcentage').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });              
 });   


 $(document).ready(function(){
     var last_meat = 0;
     var last_fruit = 0;
     var last_rice = 0;
     $('.group_avalible_stock').each(function(){
        var group = $(this).data('group');
        var value = $(this).data('value');
        if(group=='meat'){
         last_meat = parseFloat(last_meat)+parseFloat(value || 0);   
        }
        else if(group=='fruit_vegetables'){
         last_fruit = parseFloat(last_fruit)+parseFloat(value || 0);   
   
        }
        else if(group=='rice_flour'){   
         last_rice = parseFloat(last_rice)+parseFloat(value || 0);   

        }
     })

    $('#last_count_meat').html(parseFloat(last_meat,10).toFixed(2));
    $('#last_count_fruit_vegetables').html(parseFloat(last_fruit,10).toFixed(2));
    $('#last_count_rice_flour').html(parseFloat(last_rice,10).toFixed(2));
      

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
        recommQty();
    })
    
    $('.qty_rows').trigger('change');
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
          order_qty = parseFloat(order_qty,10).toFixed(2);
          if(!isNaN(order_qty)){
           var diff_qty = (recomm_qty - order_qty);
           $('#diff_qty_'+group).html(parseFloat(diff_qty,10).toFixed(2));
          }
       })    
    }
 } 
</script>