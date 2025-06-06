<?php
   $productArr = []; 
         foreach ($data as $v) {
           $productArr[$v->category_name][] = $v ; 
         } 
?>

<style>
.fielserror
{
  border:1px solid red !important;
}
.new_error{
  display: none;
}

.centered-div {
    padding: 20px 40px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
    font-size: 20px;
    font-weight: bold;
  }

</style>
<div class="animated fadeIn" id="stock_form">
        <div class="">
        <form class="form-horizontal form-bordered" name="addEditConsumedstock" enctype="multipart/form-data" id="addEditConsumedstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                  <div class="centered-div">
                    <p style="margin-left:40%" id="daily_rate_per_man_value">Daily Rate Per Man : 0.00 </p>
                  </div>
                  <br>
                <div class="sip-table">
                <table class="table">
                  <thead>
                    <tr>
                      <th width="40%">Stock Details</th>
                      <th width="6%">Total</th>
                      <th width="6%">Price($)</th>
                      <th width="17%">Stock Used</th>
                      <th width="8%">Closing Stock</th>
                    </tr>
                  </thead>
                </table>
                 <table class="table fixed-header table-bordered">
                  <thead>
                    <tr>
                      <th width="10%">Item No.</th>
                      <th width="34%">Description</th>
                      <th width="8%">Unit</th>
                      <th width="8%">Stock</th>
                      <th width="8%">Unit</th>
                      <th width="8%">Qty.</th>
                      <th width="8%">Price($)</th>
                      <th width="8%">Qty.</th>
                      <th width="8%">Price($)</th>
                    </tr>
                  </thead>
                  <tbody class="consumed_data">
           <?php 
                if(!empty($productArr)){
                      $grand_stock_total = 0;
                      $grand_stock_unit_price = 0;
                      $grand_used_stock_total = 0;
                      $grand_used_stock_total_price = 0;
                      $grand_closing_stock_total = 0;
                      $grand_closing_stock_total_price = 0;
                      $gis = 0;
                      $gip = 0;
                      $gcis = 0;
                      $gcip = 0;
                      $total_opening_price = 0;
                  foreach($productArr as $category => $products){
                      $cat = strtolower(str_replace(array(' ',',','/','&'),array('_','_','_','_'),$category));
                      $stock_total = 0;
                      $stock_unit_price = 0;
                      $used_stock_total = 0;
                      $used_stock_total_price = 0;
                      $closing_stock_total = 0;
                      $closing_stock_total_price = 0;
                      $is = 0;
                      $ip = 0;
                      $cis =0;
                      $cip = 0;
                      $returnArr .= '<tr class="parent_row">
                      <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="34%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      <td width="8%" class="total_price" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                      </tr>';

                     foreach($products as $product){
                      
                      $group_name = strtolower(str_replace(array('&',' '),array('_',''), $product->group_name));

                       $stock_total += $product->total_stock;
                       $stock_unit_price += $product->unit_price; 
                       $total_opening_price += $product->total_stock * $product->unit_price ;
                       // $used_stock_total += $product->used_stock;
                       // $used_stock_total_price += ($product->used_stock * $product->unit_price);
                       $closing_stock_total += ($product->available_stock -$dataArr['used_qty_'.$product->product_id]);
                       $closing_stock_total_price += (($product->available_stock - $dataArr['used_qty_'.$product->product_id])* $product->unit_price);   
                      $returnArr .= '<tr class="child_row">';
                      $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$product->item_no.'</td>';
                      $returnArr .= '<td <td width="34%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($product->product_name).'</td>';
                      $returnArr .= '<td <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($product->unit).'</td>';
                      $returnArr .= '<td <td width="8%" role="gridcell" tabindex="-1" data-value="'.$product->total_stock.'" id="total_stock_'.$product->product_id.'" aria-describedby="f2_key">'.str_replace(',','',number_format($product->total_stock,2)).'</td>';
                      $returnArr .= '<td <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="unit_price_'.$product->product_id.'" data-price="'.$product->unit_price.'">'.str_replace(',','',number_format($product->unit_price,2)).'</td>';
                      if($dataArr['id']=='stock_used'){
                        $is += $dataArr['used_qty_'.$product->product_id];
                        $ip +=  ($dataArr['used_qty_'.$product->product_id] * $product->unit_price);
                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="">
                        <input type="text" class="link quentity '.(form_error('used_qty_'.$product->product_id) ? 'fielserror' : " " ).'" data-quantity="1" data-category="'.$cat.'" data-id="'.$product->product_id.'"  name="used_qty_'.$product->product_id.'" value="'.$dataArr['used_qty_'.$product->product_id].'"></td>';                        
                        $returnArr .= '
                        <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="line_'.$cat.'" id="total_'.$product->product_id.'">'.(($dataArr['used_qty_'.$product->product_id]) ? str_replace(',','',number_format(($dataArr['used_qty_'.$product->product_id] * $product->unit_price),2)) : 0).'</td>';                     
                      }
                      else{
                        $ud = $product->total_stock - ((is_numeric($dataArr['closing_qty_'.$product->product_id])) ? $dataArr['closing_qty_'.$product->product_id] : $product->available_stock);
                        $used_stock_total += $ud;
                        $used_stock_total_price += ($ud * $product->unit_price);
                        $returnArr .= '<td width="8%" role="gridcell" class="line4_'.$cat.' group_count" tabindex="-1" aria-describedby="f2_key" data-group="'.$group_name.'" data-value="'.$product->used_stock.'" id="used_stock_'.$product->product_id.'">'.$ud.'</td>';
                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" class="line5_'.$cat.'" data-value="'.$ud.'" id="used_price_'.$product->product_id.'" aria-describedby="f2_key">'.str_replace(',','',number_format(($ud * $product->unit_price),2)).'</td>';
                        

                      }
                      

                      if($dataArr['id']=='closing_stock'){
                       $cis += $dataArr['closing_qty_'.$product->product_id];
                       $cip += ($dataArr['closing_qty_'.$product->product_id] * $product->unit_price); 
                       $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                       <input type="text" class="link quentity '.(form_error('closing_qty_'.$product->product_id) ? 'fielserror' : " " ).'"   data-quantity="1" name="closing_qty_'.$product->product_id.'" data-value="'.$product->available_stock.'" value="'.$dataArr['closing_qty_'.$product->product_id].'" data-category="'.$cat.'" data-id="'.$product->product_id.'"></td>'; 
                       $returnArr .= '<td  width="8%"role="gridcell" tabindex="-1" aria-describedby="f2_key" class="line_'.$cat.'" id="total_'.$product->product_id.'">'.str_replace(',','',number_format(($dataArr['closing_qty_'.$product->product_id] * $product->unit_price),2)).'</td>';  
                      }
                      else{
                        
                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" class="line2_'.$cat.' group_count" data-value="'.$product->available_stock.'" data-group="'.$group_name.'" aria-describedby="f2_key" id="avalible_stock_'.$product->product_id.'">'.str_replace(',','',number_format(($product->available_stock - $dataArr['used_qty_'.$product->product_id]),2)).'</td>';  
                        $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" class="line3_'.$cat.'" aria-describedby="f2_key" id="avalible_stock_price_'.$product->product_id.'">'.str_replace(',','',number_format((($product->available_stock - $dataArr['used_qty_'.$product->product_id]) * $product->unit_price),2)).'</td>';
                      } 
                   
                   }

                    $returnArr .= '<tr class="child_parent_row_count">
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="34%" role="gridcell" tabindex="-1" aria-describedby="f2_key">Total</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="8%" role="gridcell"  tabindex="-1" aria-describedby="f2_key">'.str_replace(',','',number_format($stock_total,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.str_replace(',','',number_format($stock_unit_price,2)).'</td>';
                   if($dataArr['id']=='stock_used'){ 
                    $returnArr .= '
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="used_total" id="used_total_'.$cat.'">'.str_replace(',','',number_format($is,2)).'</td>       
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="used_price" id="used_price_'.$cat.'">'.str_replace(',','',number_format($ip,2)).'</td>
                     <td width="8%" class="closing_total" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="avalible_total_'.$cat.'">'.str_replace(',','',number_format($closing_stock_total,2)).'</td>
                     <td width="8%" class="closing_price" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="avalible_price_'.$cat.'">'.number_format($closing_stock_total_price,2).'</td>';
                   }
                   else{
                    $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="used_total" id="used_total_'.$cat.'">'.str_replace(',','',number_format($used_stock_total,2)).'</td>       
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="used_price" id="used_price_'.$cat.'">'.str_replace(',','',number_format($used_stock_total_price,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="closing_total" id="closing_total_'.$cat.'">'.str_replace(',','',number_format($cis,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="closing_price" id="closing_price_'.$cat.'">'.str_replace(',','',number_format($cip,2)).'</td>';
                   }
                    
                 $returnArr .= '</tr>';  
                      

                    $grand_stock_total += $stock_total;
                    $grand_stock_unit_price += $stock_unit_price;
                    $grand_used_stock_total += $used_stock_total;
                    $grand_used_stock_total_price += $used_stock_total_price;
                    $grand_closing_stock_total += $closing_stock_total;
                    $grand_closing_stock_total_price += $closing_stock_total_price;
                    $gis += $is;
                    $gip += $ip;
                    $gcis += $cis;
                    $gcip += $cip;          
                 }

                  $returnArr .= '<tr class="child_parent_row_count">
                    <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="34%" role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                    <td width="8%" role="gridcell"  tabindex="-1" aria-describedby="f2_key">'.str_replace(',','',number_format($grand_stock_total,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.str_replace(',','',number_format($grand_stock_unit_price,2)).'</td>';
                   if($dataArr['id']=='stock_used'){ 
                    $returnArr .= '
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_used_total">'.str_replace(',','',number_format($gis,2)).'</td>       
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_used_price">'.str_replace(',','',number_format($gip,2)).'</td>
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_closing_total">'.str_replace(',','',number_format($grand_closing_stock_total,2)).'</td>
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"  class="grand_closing_price">'.str_replace(',','',number_format($grand_closing_stock_total_price,2)).'</td>';
                   }
                   else{
                    $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_used_total">'.str_replace(',','',number_format($grand_used_stock_total,2)).'</td>       
                     <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_used_price">'.str_replace(',','',number_format($grand_used_stock_total_price,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_closing_total">'.str_replace(',','',number_format($gcis,2)).'</td>
                    <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" class="grand_closing_price">'.str_replace(',','',number_format($gcip,2)).'</td>';
                   }
                    
                 $returnArr .= '</tr>';  
               }
              else{
               $returnArr .= '<tr><td colspan="9" style="font-weight: bold; font-size: 13px; display: table-cell;" align="center">No Data Available</td></tr>';
              }
             echo $returnArr;

             $daily_rate = ($total_opening_price - ($gcip - $condemned_stock[0]->total_amount) ) / $extra_meals[0]->total_man_days;
            ?>

        </tbody >
         <?php echo form_error('product_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
          </table>
            </div>
            <br><div class="sip-table mb-15" role="grid">
            <table class="table predefineProTbl">
                <thead>
                <tr>
                  <th width="25%">Group</th>
                  <th width="12%">Unit</th>
                  <th width="12%">Per Man Per Day</th>
                  <th width="15%">Total QTY</th>
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
                             <td width="15%" class="last_count" id="last_count_<?php echo $group_name;?>"><?php echo $row->qty;?></td>       
                         </tr>
                         <?php
                            } 
                         }
                        ?>
                    </tbody> 
               </table>
             </div>
              </div>
            </div>
            <input type="hidden" name="ship_id" value="<?php echo $dataArr['ship_id'];?>">
            <input type="hidden" name="id" value="<?php echo $dataArr['id'];?>">
            <input type="hidden" name="actionType" value="save">
          </form>
        </div>
                <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('addEditConsumedstock','shipping/add_consumed_stock','98%','consumed_stock_list')" class="btn btn-success btn-slideright mr-5">Save</button>
                        </div>
                    <div class="clearfix"></div>
                </div><!-- /.form-footer -->
</div>
<script type="text/javascript">
  $(document).ready(function(){

    var dr = '<?php echo $daily_rate ;?>';
    $('#daily_rate_per_man_value').text('Daily Rate Per Man : '+ parseFloat(dr,10).toFixed(2));

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

 function getGrandTotol(){
      var closing_total_stock = 0;
      var closing_total_price = 0;
      var used_total = 0;
      var used_price = 0;
      
      var total_opening_stock = '<?php echo ($total_opening_price > 0) ? $total_opening_price : 0  ?>';
      var condemned_stock = '<?php echo ($condemned_stock[0]->total_amounts > 0) ? $condemned_stock[0]->total_amounts : 0 ?>';
      var total_man_day = '<?php echo ($extra_meals[0]->total_man_days > 0) ? $extra_meals[0]->total_man_days : 0 ?>';

      $('.closing_total').each(function(){
          closing_total_stock += parseFloat($(this).text() || 0);
      })

      $('.closing_price').each(function(){
          closing_total_price += parseFloat($(this).text() || 0);
      })

      $('.used_total').each(function(){
          used_total += parseFloat($(this).text() || 0);
      })

      $('.used_price').each(function(){
          used_price += parseFloat($(this).text() || 0);
      })

      var c = closing_total_price - condemned_stock;
      var f = total_opening_stock - c
      var drate =  f / total_man_day;

      $('.grand_closing_total').text(parseFloat(closing_total_stock,10).toFixed(2));
      $('.grand_closing_price').text(parseFloat(closing_total_price,10).toFixed(2));
      $('.grand_used_total').text(parseFloat(used_total,10).toFixed(2));
      $('.grand_used_price').text(parseFloat(used_price,10).toFixed(2));
      
      $('#daily_rate_per_man_value').text('Daily Rate Per Man : '+ parseFloat(drate,10).toFixed(2));
  }
  
  $(document).ready(function(){

    $('.quentity').change(function(){
      var type = '<?php echo $dataArr['id'];?>' 
      var product_id = $(this).data('id');
      var category = $(this).data('category');
      var unit_price = parseFloat($('#unit_price_'+product_id).data('price'));
      var val = $(this).val();
      var total = unit_price * parseFloat(val || 0);
      $('#total_'+product_id).text(parseFloat(total,10).toFixed(2));

      if(type=='stock_used'){
        var avalible_stock =  $('#avalible_stock_'+product_id).data('value');
        var new_avalible_stock = (avalible_stock-val);
        $('#avalible_stock_'+product_id).text(parseFloat(new_avalible_stock,10).toFixed(2)); 
        $('#avalible_stock_price_'+product_id).text(parseFloat(new_avalible_stock*unit_price,10).toFixed(2)); 
      }
      else{
        var used_stock =  $('#total_stock_'+product_id).data('value');
        if(val !==''){
          var new_used_stock = parseFloat(used_stock) - parseFloat(val); 
        }
        else{
          var new_used_stock = used_stock;
        }        
        // else{
        // var new_used_stock = 0; 
        // }
        $('#used_stock_'+product_id).text(parseFloat(new_used_stock,10).toFixed(2));
        $('#used_price_'+product_id).text(parseFloat(new_used_stock*unit_price,10).toFixed(2));
      }

      var total_qty = 0;
      var total_qty1 = 0;
      var total_qty2 = 0;
      var total_price = 0;
      var total_price1 = 0;
      var total_price2 = 0;

      $('.quentity').each(function(){
        if($(this).data('category')==category){
          total_qty += parseFloat($(this).val() || 0);
        }
      })

      $('.line_'+category).each(function(){
          total_price += parseFloat($(this).text() || 0);
      })

      $('.line2_'+category).each(function(){
          total_qty1 += parseFloat($(this).text() || 0);
      })

      $('.line3_'+category).each(function(){
          total_price1 += parseFloat($(this).text() || 0);
      })

      $('.line4_'+category).each(function(){
          total_qty2 += parseFloat($(this).text() || 0);
      })

      $('.line5_'+category).each(function(){
          total_price2 += parseFloat($(this).text() || 0);
      })

      if(type=='stock_used'){  
       $('#used_total_'+category).text(parseFloat(total_qty,10).toFixed(2));
       $('#used_price_'+category).text(parseFloat(total_price,10).toFixed(2));
       $('#avalible_total_'+category).text(parseFloat(total_qty1,10).toFixed(2));
       $('#avalible_price_'+category).text(parseFloat(total_price1,10).toFixed(2));
     }
     else{
        $('#used_total_'+category).text(parseFloat(total_qty2,10).toFixed(2));
        $('#used_price_'+category).text(parseFloat(total_price2,10).toFixed(2));
        $('#closing_total_'+category).text(parseFloat(total_qty,10).toFixed(2));
        $('#closing_price_'+category).text(parseFloat(total_price,10).toFixed(2));
      }

       setTimeout(function(){
        getGrandTotol();      
        groupCount();
       },1000);

    })
     
  })

  function groupCount(){
     var meat = 0;
     var fruit = 0;
     var rice = 0;
  
    $('.group_count').each(function(){
      var group = $(this).data('group');
      if(group=='meat'){
         meat += parseFloat($(this).text() || 0);
      }
      else if(group=='fruit_vegetables'){
         fruit += parseFloat($(this).text() || 0);
      }
      else if(group=='rice_flour'){
         rice += parseFloat($(this).text() || 0);
      }
    }) 

    $('#last_count_meat').html(parseFloat(meat,10).toFixed(2));
    $('#last_count_fruit_vegetables').html(parseFloat(fruit,10).toFixed(2));
    $('#last_count_rice_flour').html(parseFloat(rice,10).toFixed(2));
 
  }
  

   $(document).ready(function() {
          $('.quentity').on('input', function(e) {
              // Get the input value
              var inputValue = $(this).val();
              
              // Remove any non-numeric and non-decimal characters
              var numericValue = inputValue.replace(/[^0-9.]/g, '');

              // Update the input field value
              $(this).val(numericValue);
          });
    $('.fielserror').focus();       
  });   

   groupCount();
 </script>                                   
 <?php if(!empty($is_import_data)){?>
  <!-- <script>$('.quentity').trigger('change');</script>  -->
  <?php
  }?>

 <?php
    if(!empty($dataArr)){?>
     <!-- <script>$('.quentity').trigger('change')</script>  -->
     <?php 
    }
  ?>  