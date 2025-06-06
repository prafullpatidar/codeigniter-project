<style>
.error
{
  border:1px solid red !important;
}
.error_spn{
 color: red;
}

.new_error{
  display: none;
}
</style>
<!-- <script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/moment.js"></script>
<script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/datetimepicker.js"></script> -->
<div class="animated fadeIn" id="stock_form">
  <form class="form-horizontal form-bordered" name="pre_import_form" enctype="multipart/form-data" id="pre_import_form" method="post">
  <div class="row">
            <div class="form-group col-sm-4">
                 <label class="col-sm-12">Port</label>
                        <div class="col-sm-12">
                            <strong><?php echo $port_name;?></strong>
                        </div>
             </div>
             <div class="form-group col-sm-4">
                 <label class="col-sm-12">Lead Time (Days)<span>*</span></label>
                    <div class="col-sm-12">
                      <input type="number" name="lead_time" class="form-control datetimepicker" value="<?php echo $lead_time;?>">
                       <?php echo form_error('lead_time','<p class="error_spn">','</p>')?>        
                    </div>
             </div>
            </div>
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <?php echo form_error('qt_product_id','<p class="error" style="color:#E9573F;display: inline;">','</p>')?>
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="abc" class="sip-table" role="grid">
               <table class="table header-fixed-new table-text-ellipsisz table-layout-fixed">
                <thead class="t-header">
                <tr>
                  <th width="8%">Item No.</th>
                  <th width="22%">Description</th>
                  <th width="7%">Unit</th>
                  <th width="7%">RFQ QTY</th>
                  <th width="15%">RFQ Remark</th>
                  <th width="8%">Vendor QTY</th>
                  <th width="8%">Unit Price ($)</th>
                  <th width="10%">Total Price($)</th>
                  <th width="15%">Vendor Remark</th>
                  </tr>
                </thead>
                    <tbody class="item_data">
                        <?php
                         if(!empty($productArr)){
                             $qty_total = 0;
                            $price_total = 0;
                                foreach ($productArr as $parent => $rows) {
                                    foreach($rows as $category => $products){
                                         $returnArr .= '<tr class="parent_row">
                                                <td  width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td  width="22%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                                                <td  width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td  width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td  width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td  width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td  width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td  width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td  width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>                                                        
                                                </tr>';
                                            for ($i=0; $i <count($products) ; $i++) { 

                                         $qty = (is_numeric($products[$i]['vendor_qty'])) ? str_replace(',','',number_format($products[$i]['vendor_qty'],2)) : '';

                                             $unit_price = (is_numeric($products[$i]['unit_price']))  ? str_replace(',','',number_format($products[$i]['unit_price'],2)) : ''; 

                                              $qty_total+= $qty;
                                             $price_total += ($qty*$unit_price);
                                               
                                                 $returnArr .= '<tr class="child_row">';
                                                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                                  // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['quantity'].'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['remark'].'</td>';
                                                // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity valid_data" data-quantity="1" name="qty_'.$products[$i]['product_id'].'" value="'.$qty.'" id="qty_'.$products[$i]['product_id'].'">'.form_error("qty_".$products[$i]['product_id'],'<p class="error" style="color:#E9573F;display: inline;">','</p>').'</td>';
                                                $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity valid_data count_qty '.(form_error("qty_".$products[$i]['product_id']) ? 'error' : '').'" data-quantity="1" name="qty_'.$products[$i]['product_id'].'" value="'.$qty.'" id="qty_'.$products[$i]['product_id'].'"></td>';
                                                // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity valid_data" data-quantity="1" name="unit_price_'.$products[$i]['product_id'].'" value="'.$unit_price.'" id="unit_price_'.$products[$i]['product_id'].'">'.form_error("unit_price_".$products[$i]['product_id'],'<p class="error" style="color:#E9573F;display: inline;">','</p>').'</td>';

                                               $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity valid_data '.(form_error("unit_price_".$products[$i]['product_id']) ? 'error' : '').'" data-quantity="1" name="unit_price_'.$products[$i]['product_id'].'" value="'.$unit_price.'" id="unit_price_'.$products[$i]['product_id'].'"></td>'; 
                                                $price = (!empty($qty) && !empty($unit_price)) ?  str_replace(',','',number_format(($qty * $unit_price),2)) : '';
                                                $returnArr .= '<td role="gridcell" tabindex="-1" class="total_price" id="calPrice_'.$products[$i]['product_id'].'" aria-describedby="f2_key">'.$price.'</td>';
                                                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input type="text" class="link quentity" data-quantity="1" name="remark_'.$products[$i]['product_id'].'" value="'.$products[$i]['vendor_remark'].'" id="remark_'.$products[$i]['product_id'].'"></td>';
                                                $returnArr .= '</tr>'; 
                                            }
                                       }
                                    }


                                    $returnArr .= '<tr class="parent_row">
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="21%" role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                                                 <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="qty_total">'.$qty_total.'</td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="price_total">'.$price_total.'</td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>                                 
                                                </tr>'; 
                               }
                           echo $returnArr;    
                      ?>
                    </tbody>
                </table>
         </div>
           </div>
         </div>
            <input type="hidden" name="actionType" id="actionType" value="save">  
       </form>
     </div>
   </div>
 </div>
<div class="clearfix"></div>
  <div class="form-footer">
          <div class="pull-right">
           <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
           <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360Form('pre_import_form','shipping/preview_import_quote','98%','order_request_list');">Save</button>
          </div>
<div class="clearfix"></div>
</div><!-- /.form-footer -->        

<script type="text/javascript">
    function qtQtyPrcChange(p_id){
        var unit_price = $('#unit_price_'+p_id).val();
        var qty = $('#qty_'+p_id).val();
        if(parseFloat(unit_price) && parseFloat(qty)){
         $('#calPrice_'+p_id).text(parseFloat(parseFloat(unit_price,10).toFixed(2)*parseFloat(qty,10).toFixed(2),10).toFixed(2));
        }
        else{
         $('#calPrice_'+p_id).text(0);   
        }
    }
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


$('.valid_data').change(function(){
   var qty = 0;
   var total_price = 0;
   $('.count_qty').each(function(){
     if(parseFloat($(this).val())){
       qty+= parseFloat($(this).val());
      }
   })

   $('.total_price').each(function(){
     if(parseFloat($(this).text())){
       total_price+= parseFloat($(this).text());
      }
   })

   $('#qty_total').html(parseFloat(qty).toFixed(2));
   $('#price_total').html(parseFloat(total_price).toFixed(2));
 })

     $(document).ready(function () {       
             $('.valid_data').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });

        $('.error').focusin();        
 });   


// $(document).ready(function(){
//     $('#datetimepicker').datetimepicker({
//         format: 'DD-MM-YYYY hh:mm A'
//     }
//    );
// });  
    </script>