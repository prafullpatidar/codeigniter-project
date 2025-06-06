<div class="animated fadeIn" id="stock_form">
  <form class="form-horizontal form-bordered" name="addEditstock" enctype="multipart/form-data" id="addEditstock" method="post">
    <div class="row">
        <div class="form-group col-sm-3 <?php echo (form_error('date')) ? 'has-error':'';?>" >
         <label class="col-sm-12">Date <span>*</span></label>
       <div class="col-sm-12">
              <input type="text" readonly class="form-control datePicker_editPro" name="date" id="date" value="<?php if(!empty($dataArr['date'])){echo convertDate($dataArr['date'],'','d-m-Y');}?>">
              <?php echo form_error('date','<p class="error" style="display: inline;">','</p>'); ?>
          </div>
        </div>
    <div class="col-md-12">
        <div class="">
                <div class="no-padding rounded-bottom">
                <div class="form-body mb-15">
                 <!--<small><strong>Delivery Note No : - <?php //echo $delivery_note_no;?></strong></small>   -->
                <div id="abc" class="sip-table" role="grid">
               <table class="table header-fixed-new table-text-ellipsis table-layout-fixed">
                <thead class="t-header">
                <tr>
                  <th width="10%">Item No.</th>
                  <th width="20%">Description</th>
                  <th width="10%">Unit</th>
                  <th width="10%">Qty</th>
                  <th width="15%">Unit Price ($)</th>
                  <th width="15%">Total Price ($)</th>
                  <th width="20%">Remark</th>  
                </tr>
                </thead>
            <tbody class="item_data">
                      <?php 
           if(!empty($productArr)){
                $grand_total = 0;
                foreach ($productArr as $parent => $rows) {
                    foreach($rows as $category => $products){
                       $returnArr .= '<tr class="parent_row">
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>';
                        $cat_total = 0;
                     for ($i=0; $i <count($products) ; $i++) { 
                        $cat_total += $products[$i]['qty'] * $products[$i]['unit_price'];
                        
                       $returnArr .= '<tr class="child_row">';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['qty'],2).'</td>'; 
                       $returnArr .= '<td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'</td>'; 
                       $returnArr .= '<td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format(($products[$i]['qty'] * $products[$i]['unit_price']),2).'</td>';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                        <input type="text" class="link quentity" data-quantity="1" name="remark_'.$products[$i]['product_id'].'" id="remark_'.$products[$i]['product_id'].'"></td>';   
                       $returnArr .= '</tr>';
                            }
                      $grand_total += $cat_total;
                    $returnArr .= '<tr class="child_parent_row_count">
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1"  aria-describedby="f2_key">Total</td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($cat_total,2).'</td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>

                                  </tr>';
                                      }
                                  }
                    $returnArr .= '<tr class="child_parent_row_count">
                                    <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1"  aria-describedby="f2_key">Grand total</td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($grand_total,2).'</td>
                                 <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                  </tr>';
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
                            <!-- <button type="button" onclick="saveDeliveryNote()" class="btn btn-success btn-slideright mr-5">Save & Next</button> -->
                            <button type="button" onclick="submitAjax360Form('addEditstock','shipping/add_delivery_note','98%','work_order')" class="btn btn-success btn-slideright mr-5">Submit</button>
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

    jQuery(document).ready(function(){
   $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
         changeYear:true,
        yearRange: "c-4:c+3"
    });

});

</script>