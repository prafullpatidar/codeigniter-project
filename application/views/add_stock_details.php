<style>
.error
{
  border:1px solid red !important;
}
.new_error{
  display: none;

}

/*.clone-head-table-wrap {
    top: 100px !important;
  }*/

</style>
<?php
// print_r($dataArr);
?>
<!-- <script src="<?php echo base_url()?>assets/assets/fixedHeaderAssets/freeze-table.js"></script> -->
<div class="animated fadeIn" id="stock_form">
    <form class="form-horizontal form-bordered" name="addEditstock" enctype="multipart/form-data" id="addEditstock" method="post">
    <div class="row">
      <?php
      if(!empty($ship_stock_id)){ 
      ?>
      <div class="form-group col-sm-3 <?php echo (form_error('stock_date')) ? 'has-error':'';?>" >
         <label class="col-sm-12">Stock Date <span>*</span></label>
       <div class="col-sm-12">
              <input type="text" readonly class="form-control datePicker_editPro" name="stock_date" id="stock_date" value="<?php if(!empty($dataArr['stock_date'])){echo convertDate($dataArr['stock_date'],'','d-m-Y');}?>">
              <?php echo form_error('stock_date','<p class="error" style="display: inline;">','</p>'); ?>
          </div>
        </div>
      <?php } ?>

    <div class="col-md-12">
        <div class="">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="tableResponsive" class="sip-table" role="grid">
               <table class="table"  border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Item No.</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>QTY</th>
                  <th>Unit Price($)</th>
                  <th>Total Price($)</th>    
                  <th>Remark</th>    
                  </tr>
                </thead>
                    <tbody class="item_data">
                <?php 
                 if(!empty($productArr)){
                  foreach ($productArr as $category => $products) {
                       $catImp = str_replace(array(',',' ','/','&'),array('_','','_','_'),$category);
                       $catImp = trim($catImp);
                       $returnArr .= '<tr class="parent_row">
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                         $returnArr .= '</tr>';
                          
                        foreach ($products[0] as $key => $row1) {
                          $group_name = strtolower(str_replace(array('&',' '),array('_',''), $row1->group_name));
                             $returnArr .= '<tr class="child_row">';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$row1->item_no.'</td>';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($row1->product_name).'</td>';
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($row1->unit).'</td>';
                            $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input type="text" id="product_qnty_'.$row1->product_id.'" data-id="'.$row1->product_id.'" class="qty_rows link quentity quentity_'.$catImp.'" data-quantity="1" data-group="'.$group_name.'" name="qty_'.$row1->product_id.'" value="'.number_format($dataArr['qty_'.$row1->product_id],2).'" data-category="'.$catImp.'">
                                        </td>'; 
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input type="text" class="link unit_price unit_price_'.$catImp.'" data-quantity="1" name="unit_price_'.$row1->product_id.'" id="unit_price_'.$row1->product_id.'" value="'.number_format($dataArr['unit_price_'.$row1->product_id],2).'" data-category="'.$catImp.'">';
                             $returnArr .= form_error('unit_price_'.$row1->product_id,'<p class="new_error">','</p>');            
                             $returnArr .=    '</td>';
                             $returnArr .= '<td role="gridcell"  tabindex="-1" aria-describedby="f2_key" id="total_'.$row1->product_id.'" class="line_total_'.$catImp.'" data-category="'.$catImp.'">'.number_format(($dataArr['qty_'.$row1->product_id] * $dataArr['unit_price_'.$row1->product_id]),2).'</td>'; 
                             $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                                <input type="text" class="link" data-quantity="1" name="remark_'.$row1->product_id.'" value="'.$dataArr['remark_'.$row1->product_id].'" id="remark_'.$row1->product_id.'">
                                            </td></tr>';


                              $returnArr .= '</tr>';
                           }
                           $returnArr .= '<tr class="parent_row">
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key">Total</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" class="total_qty" id="total_qty_'.$catImp.'" >0</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" class="total_uprice" id="total_uprice_'.$catImp.'" >0</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" class="total_tprice" id="total_tprice_'.$catImp.'" >0</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key">-</td>';
                           $returnArr .= '</tr>'; 
                         }
                         $returnArr .= '<tr class="parent_row">
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_total_qty">0</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_total_uprice">0</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key" id="grand_total_tprice">0</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key" >-</td>';
                         $returnArr .= '</tr>';
                      }else{
                         $returnArr .='<tr><td colspan="6" align="center" ><strong>No Data Available</strong></td></tr>';
                      }
                       echo $returnArr;
                      ?>
                    </tbody>
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
                             <td width="15%" class="last_count" id="last_count_<?php echo $group_name;?>">0</td>
                         </tr>
                         <?php
                            } 
                         }
                        ?>
                    </tbody> 
               </table>
              </div>
          
               <input type="hidden" name="id" value="<?php echo $ship_stock_id;?>">                  
               <input type="hidden" value="save" name="actionType">              
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <!-- <button type="button" onclick="submitAjax360Form('addEditstock','shipping/add_stock_details','98%','stock_list')" class="btn btn-success btn-slideright mr-5">Submit</button> -->
                             <button type="button" onclick="submitForm();" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
  </div>
 <script>

  $(document).ready(function () { 
    // $("#tableResponsive").freezeTable(
    // {top : '10px'});
});

  

  function submitForm(){
    var flag=true;
    var flagArr = [];
    $('.quentity').each(function () {
      if($(this).val() !== ''){
        var product_id = $(this).attr('id');
        product_id = product_id.split('_')[2];
        var unit_price = $('#unit_price_'+product_id).val();
        if(unit_price === ''){
          flag =  false;
          flagArr.push(flag);
          console.log('add class');
          $('#unit_price_'+product_id).addClass('error');
          $('#unit_price_'+product_id).focus();
        }else{
          flag = true;
          flagArr.push(flag);
          console.log('remove class');
          $('#unit_price_'+product_id).removeClass('error');
        }
      }
    });

    $('.unit_price').each(function () {
      if($(this).val() !== ''){
        var product_id = $(this).attr('id');
        product_id = product_id.split('_')[2];
        var qty = $('#product_qnty_'+product_id).val();
        if(qty === ''){
           flag = false;
           flagArr.push(flag);
          $('#product_qnty_'+product_id).addClass('error');
          $('#product_qnty_'+product_id).focus();
        }else{
          flag = true;
          flagArr.push(flag);
          $('#product_qnty_'+product_id).removeClass('error');
        }
      }
    });
    
    if($.inArray(false,flagArr) !== -1){
      return false;
    }else{
      submitAjax360Form('addEditstock','shipping/add_stock_details/<?php echo $mode;?>','98%','stock_list');
    }
   
  }

  convertToExcel();
  
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

  $(document).ready(function(){
  $('.unit_price').change(function(){
    var id = $(this).attr('id');
    var cat = $(this).attr('data-category');
    id = id.split('_')[2];
    var grand_total_uprice = 0;
    var grand_total_tprice = 0;
    var qty = parseFloat($('#product_qnty_'+id).val() || 0);
    var total1 = qty * parseFloat($(this).val() || 0);
    $('#total_'+id).text(parseFloat(total1,10).toFixed(2));

    var total_uprice = 0;
    $('.unit_price_'+cat).each(function () {
      total_uprice += parseFloat($(this).val() || 0);
    });
    $("#total_uprice_"+cat).text(parseFloat(total_uprice,10).toFixed(2));
    
    var total_tprice = 0;
    $('.line_total_'+cat).each(function () {
      total_tprice += parseFloat($(this).text() || 0);
    });
    $("#total_tprice_"+cat).text(parseFloat(total_tprice,10).toFixed(2));

    
    $('.total_uprice').each(function () {
      grand_total_uprice += parseFloat($(this).text() || 0);
    });
    $("#grand_total_uprice").text(parseFloat(grand_total_uprice,10).toFixed(2));

    $('.total_tprice').each(function () {
      grand_total_tprice += parseFloat($(this).text() || 0);
    });
    $("#grand_total_tprice").text(parseFloat(grand_total_tprice,10).toFixed(2));

  })

   $('.quentity').change(function(){
    var id = $(this).attr('id');
    var cat = $(this).attr('data-category');
    id = id.split('_')[2];
    var grand_total_tprice = 0;
    var grand_total_qty = 0;
    var unit_price = parseFloat($('#unit_price_'+id).val() || 0);
    var total12 = unit_price * parseFloat($(this).val());
    $('#total_'+id).text(parseFloat(total12,10).toFixed(2));

    var total_qty = 0;
    $('.quentity_'+cat).each(function () {
      total_qty += parseFloat($(this).val() || 0);
    });
    $("#total_qty_"+cat).text(parseFloat(total_qty,10).toFixed(2));

    var total_tprice = 0;
    $('.line_total_'+cat).each(function () {
      total_tprice += parseFloat($(this).text() || 0);
    });
    $("#total_tprice_"+cat).text(parseFloat(total_tprice,10).toFixed(2));
    
    $('.total_qty').each(function () {
      grand_total_qty += parseFloat($(this).text() || 0);
    });
    $("#grand_total_qty").text(parseFloat(grand_total_qty,10).toFixed(2));

    $('.total_tprice').each(function () {
      grand_total_tprice += parseFloat($(this).text() || 0);
    });
    $("#grand_total_tprice").text(parseFloat(grand_total_tprice,10).toFixed(2));;

  })

  

   $('.quentity').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');
                // Update the input field value
                $(this).val(numericValue);
    }); 

    $('.quentity').trigger('change'); 
    $('.unit_price').trigger('change'); 
})


  $(document).ready(function(){
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

        $('#last_count_meat').html(parseFloat(order_meat,10).toFixed(2));
        // $('#ordered_qty_meat').data('value',order_meat);
        $('#last_count_fruit_vegetables').html(parseFloat(order_fruit,10).toFixed(2));
        // $('#ordered_qty_fruit_vegetables').data('value',order_fruit);
        $('#last_count_rice_flour').html(parseFloat(order_rice,10).toFixed(2));
        // $('#ordered_qty_rice_flour').data('value',order_rice);
    })

   })

  jQuery(document).ready(function(){
   $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
         changeYear:true,
        yearRange: "c-4:c+3"
    });

});

</script>