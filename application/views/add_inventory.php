<?php 
$invoice_discount = checkLabelByTask('show_invoice_discount');
?>
<div class="body-content animated fadeIn body-content-flex" id="stock_form">
        <form class="h-100 d-flex flex-column flex-no-wrap" name="update_stock" enctype="multipart/form-data" id="update_stock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                  <div class="row">
                    <div class="form-group col-sm-3">
                       <label class="col-sm-12">Month</label>
                         <div class="col-sm-12">
                        <?php 
                          $monthNum  = $month;
                          $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                          $monthName = $dateObj->format('F');
                        echo $monthName;?>
                      </div>
                    </div>
                    <div class="form-group col-sm-3">
                       <label class="col-sm-12">Year</label>
                         <div class="col-sm-12">
                        <?php echo $year;?>
                      </div>
                    </div>
                  </div>
                <div id="abc" class="sip-table" role="grid">
               <table class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                <thead class="t-header">
                <tr>
                  <th width="10%">Item No.</th>
                  <th width="20%">Description</th>
                  <th width="10%">Unit</th>
                  <th width="10%">Last Count Qty</th>
                  <th width="10%">QTY</th> 
                  <th width="10%">Unit Price($)</th>
                  <th width="10%">Total Price($)</th>
                  <th width="20%">Remark</th>  
                  </tr>
                </thead>
                    <tbody class="item_data">
                <?php 
                $total = 0;

           if(!empty($productArr)){
                foreach ($productArr as $parent => $rows) {
                $cat_total = 0;

                    foreach($rows as $category => $products){
                       $returnArr .= '<tr class="parent_row">
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%"role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>';
     for ($i=0; $i <count($products) ; $i++) { 
       $returnArr .= '<tr class="child_row">';
       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['last_quantity'].'</td>'; 
       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'<input type="hidden" name="qty_'.$products[$i]['product_id'].'" value="'.$products[$i]['quantity'].'"></td>';
       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'<input type="hidden" name="price_'.$products[$i]['product_id'].'" value="'.$products[$i]['unit_price'].'"></td>';  
       $returnArr .=  '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format(($products[$i]['quantity'] * $products[$i]['unit_price']),2).'</td>'; 
        $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
            <input type="text" class="link quentity" data-quantity="1" name="remark_'.$products[$i]['product_id'].'" value="'.$dataArr['remark_'.$products[$i]['product_id']].'" id="remark_'.$products[$i]['product_id'].'">
           <input type="hidden" name="group_'.$products[$i]['product_id'].'" value="'.$products[$i]['group_name'].'">                     </td>'; 
       $returnArr .= '</tr>';
       $cat_total = $cat_total + ($products[$i]['quantity'] * $products[$i]['unit_price']);
            }
                      
              $returnArr .= '<tr class="child_row_count">
                              <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="20%" role="gridcell" tabindex="-1"  aria-describedby="f2_key">Total($)</td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                             <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($cat_total,2).'</td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>

                            </tr>';
                            }
                  $total = $total+$cat_total;          
                }
              $returnArr .= '<tr class="parent_row_count">
                              <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="20%" role="gridcell" tabindex="-1"  aria-describedby="f2_key">Grand Total($)</td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                           <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($total,2).'</td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>

                            </tr>';
                       if(!empty($discount) && $invoice_discount){                
                         $returnArr .= '<tr class="parent_row_count">
                                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="20%" role="gridcell" tabindex="-1"  aria-describedby="f2_key">Discount(%)</td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($discount,2).'</td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>

                                      </tr>';
                           $discount_amount = ($total*$discount) / 100;
                           $net_amount = $total - $discount_amount;          
                          $returnArr .= '<tr class="parent_row_count">
                                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="20%" role="gridcell" tabindex="-1"  aria-describedby="f2_key">Net Amount($)</td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                     <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                      <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($net_amount,2).'</td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>

                                      </tr>';
                          }
                        }
                        else{
                         $returnArr .= '<tr><td colspan="8" style="font-weight: bold; font-size: 13px; display: table-cell;" align="center">Currently awaiting approval from the administrator.</td></tr>';

                        }

                        echo $returnArr;
                      ?>
                    </tbody>
                    <?php echo form_error('product_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
               </table>
          </div>
                <input type="hidden" name="id" value="<?php echo $dataArr['id'];?>">                  
                <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" data-dismiss="modal" onclick="confirmation();" class="btn btn-success btn-slideright mr-5" >Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    
 <script>
$(document).ready(function(){
    convertToExcel();
})  

// function submitTableForm()
//     {    

//         var $data = new FormData($('#update_stock')[0]);
//         $.ajax({
//             beforeSend: function(){
//                         $("#customLoader").show();
//                     },
//             type: "POST",
//             url: base_url + 'shipping/update_stock_item_list',
//             cache:false,
//             data: $data,
//             processData: false,
//             contentType: false,
//             success: function(msg)
//             {
//                 $("#customLoader").hide();
//                 var obj = jQuery.parseJSON(msg);
//                 $('.item_data').html(obj.dataArr);
//                 convertToExcel();
//             }
//         });
//         return false;
//     }

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

   
   function confirmation(){
     bootbox.dialog({
            message: '<strong>Note: Please make sure all item quantity and unit price is correct before updating.</strong> Are you sure want to update your inventory ?',
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
                      submitAjax360Form('update_stock','shipping/add_inventory','98%','stock_list')
                    }
                }

            }
        });

   }
   $(document).ready(function () {
    $('.header-fixed-new').prepFixedHeader().fixedHeader();

});
</script>
