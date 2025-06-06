<style>
input.bold-input{
  font-weight: bold !important !important;
}
</style>
<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="add_rfq" enctype="multipart/form-data" id="add_rfq" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body double-table">
                <div class="row mb-25 d-flex align-items-center">
                    <div class="form-group col-sm-3 <?php echo (form_error('no_of_people')) ? 'has-error':'';?>" >
                            <label class="col-sm-12 mb-0 mt-30">Requisition Type : 
                                                       <input type="hidden" name="requisition_type" value="<?php echo $dataArr['requisition_type'];?>">
                                <?php if($dataArr['requisition_type']=='provision'){
                                    echo '<strong>Provisions</strong>';
                                }else if($dataArr['requisition_type']=='bonded_store'){
                                    echo '<strong>Bonded Store</strong>';
                                }else if($dataArr['requisition_type']=='stores'){
                                    echo '<strong>Stores</strong>';
                                }
                                else if($dataArr['requisition_type']=='mineral_water'){
                                    echo '<strong>Mineral Water</strong>';
                                }
                                ?>
                          </label>
                            <!-- <div class="col-sm-12">                             
                                <select name="requisition_type" id="requisition_type" class="form-control" onchange="reqTypeChange(this.value);">
                                   <option value="">Select Type</option>
                                   <option <?php echo ($dataArr['requisition_type']=='provision') ? 'selected="selected"' : '';?> value="provision">Provisions</option>
                                   <option <?php echo ($dataArr['requisition_type']=='bonded_store') ? 'selected="selected"' : '';?> value="bonded_store">Bonded Store</option>
                                   <option <?php echo ($dataArr['requisition_type']=='stores') ? 'selected="selected"' : '';?> value="stores">Stores</option> 
                                </select> 
                                <?php //echo form_error('no_of_people','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>-->
                        </div>
                     <div class="form-group col-sm-3 <?php echo (form_error('port_id')) ? 'has-error':'';?>" >
                         <label class="col-sm-12">Port Name / Arriving date <span class="c-red">*</span></label>
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
                     <?php if($dataArr['requisition_type']=='provision'){ ?>
                     <div class="form-group col-sm-2 <?php echo (form_error('no_of_day')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">No of Days <span class="c-red">*</span></label>
                            <div class="col-sm-12">
                                <input type="number" min="0" onchange="recommQty()" class="form-control" name="no_of_day" id="no_of_day" value="<?php if(!empty($dataArr['no_of_day'])){echo set_value('no_of_day',$dataArr['no_of_day']);}?>">
                                <?php echo form_error('no_of_day','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <div class="form-group col-sm-2 <?php echo (form_error('no_of_people')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">No of People <span class="c-red">*</span></label>
                            <div class="col-sm-12">
                                <input type="number" min="0" onchange="recommQty()" class="form-control" name="no_of_people" id="no_of_people" value="<?php if(!empty($dataArr['no_of_people'])){echo set_value('no_of_people',$dataArr['no_of_people']);}?>">
                                <?php echo form_error('no_of_people','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                         <?php }?>
                </div>
                <div id="abc" class="sip-table" role="grid">
                    <?php if($dataArr['requisition_type']=='bonded_store' || $dataArr['requisition_type']=='stores' || $dataArr['requisition_type']=='mineral_water'){?>
                    <table class="table">
                        <thead>
                        <tr>
                          <th>Item No.</th>
                          <th>Item Name</th>
                          <th>Unit</th>
                          <th>Last Count Qty</th>
                          <th>QTY</th>
                          <th>Remark</th>
                          </tr>
                        </thead>
                        <tbody class="cst_item_data cstmProductTbl">
                            <?php 
                            if(!empty($dataArr['ttl_prdct'])){
                                for($i=0;$i<$dataArr['ttl_prdct'];$i++){
                                    ?>
                                    <tr class="child_row">
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;">-</td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input type="text" name="item_name[<?php echo $i;?>]" value="<?php echo $dataArr['item_name'][$i];?>">
                                            <?php echo form_error("item_name[$i]",'<p class="error" style="color:#E9573F;display: inline;">','</p>');?>
                                        </td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input type="text" name="item_unit[<?php echo $i;?>]" value="<?php echo $dataArr['item_unit'][$i];?>">
                                            <?php echo form_error("item_unit[$i]",'<p class="error" style="color:#E9573F;display: inline;">','</p>');?>
                                        </td>
                                        <td role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="meat" data-value="">-</td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input class="qty_rows link quentity qtyChangePORKANDBACON" type="number" name="item_qty[<?php echo $i;?>]" value="<?php echo $dataArr['item_qty'][$i];?>">
                                            <?php echo form_error("item_qty[$i]",'<p class="error" style="color:#E9573F;display: inline;">','</p>');?>
                                        </td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                            <input type="text" class="link quentity" name="item_remark[<?php echo $i;?>]" value="<?php echo $dataArr['item_remark'][$i];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            if(!empty($store_product_data)){
                                foreach($store_product_data as $spd){
                                    ?>
                                    <tr class="child_row">
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;"><?php echo $spd->item_no;?></td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $spd->product_name;?><input type="hidden" name="store_product_ids[]" value="<?php echo $spd->product_id;?>"></td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $spd->unit;?></td>
                                        <td role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="meat" data-value="">-</td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="qty_rows link quentity qtyChangePORKANDBACON" type="number" name="qty_product_<?php echo $spd->product_id;?>" value="<?php echo $dataArr['qty_product_'.$spd->product_id];?>"></td>
                                        <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity" name="remark_<?php echo $spd->product_id;?>" value="<?php echo $dataArr['remark_'.$spd->product_id];?>"></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <?php echo form_error('cst_product_id','<p class="error" style="color:#E9573F;display: inline;">','</p>')?>
                    </table>
                    <input type="hidden" name="ttl_prdct" id="ttl_prdct" value="<?php echo (!empty($dataArr['ttl_prdct']))?$dataArr['ttl_prdct']:'0';?>"><div class="text-right">
                    <a class="btn btn-success btn-slideright mt-5" id="add_new_prdct" href="javascript:void(0)">Add New <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a></div>

                    <?php }else{ ?>
                    
                 <table class="table header-fixed-new table-text-ellipsisz table-layout-fixed">
                <thead class="t-header">
                <tr>
                  <th width="12%">Item No.</th>
                  <th width="37%">Description</th>
                  <th width="12%">Unit</th>
                  <th width="12%">Last Count Qty</th>
                  <th width="12%">QTY</th>
                  <th width="15%">Remark</th>
                  </tr>
                </thead>
                    <tbody class="item_data">
                     <?php
                     $avalible_stock_grand_total = 0;
                     $order_meat = 0;
                     if(!empty($productArr)){
                        foreach ($productArr as $category => $products) {
                         $catImp = str_replace(array(',',' ','/','&'),array('_','','_','_'),$category);
                         $catImp = trim($catImp);

                         $avalible_stock_total = 0;
                         $returnArr .= '<tr class="parent_row">
                            <td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="37%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'<strong></td>
                            <td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            </tr>';
                          foreach ($products as $key => $p) {
                               foreach ($p as $k => $row1) {     
                                 $group_name = strtolower(str_replace(array('&',' '),array('_',''), $row1->group_name));
                               
                                  if($group_name=='meat'){
                                     $order_meat = $order_meat+$dataArr['qty_product_'.$row1->product_id]; 
                                  }
                                 
                                 // $avalible_stock =  ($stock_used[$row1->product_id]['total_stock'] - $stock_used[$row1->product_id]['used_stock']);

                                  $avalible_stock =  ($stock_used[$row1->product_id]['available_stock']);

                                 $avalible_stock = ($avalible_stock) ? $avalible_stock : 0;
                                 $avalible_stock_total = $avalible_stock_total+$avalible_stock;
                                 $returnArr .= '<tr class="child_row">';
                                 $returnArr .= '<td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$row1->item_no.'</td>';
                                 $returnArr .= '<td width="37%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($row1->product_name).'</td>';
                                 $returnArr .= '<td width="12%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($row1->unit).'</td>';
                                 $returnArr .= '<td width="12%" role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="'.$group_name.'" data-value="'.$avalible_stock.'">'.number_format($avalible_stock,2).'</td>';             
                             $returnArr .= '<td width="12%"  role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                  <input class="qty_rows link quentity qtyChange'.$catImp.'" data-quantity="1" data-group="'.$group_name.'" type="text" name="qty_product_'.$row1->product_id.'" data-value="'.$dataArr['qty_product_'.$row1->product_id].'" value="'.$dataArr['qty_product_'.$row1->product_id].'" id="product_id_'.$row1->product_id.'" onchange="getCategoryTotal(this.value,\''.$catImp.'\');">
                                            </td>';
                               $returnArr .= '<td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                                                <input type="text" class="link quentity" data-quantity="1" name="remark_'.$row1->product_id.'" value="'.$dataArr['remark_'.$row1->product_id].'" id="remark_'.$row1->product_id.'">
                                            </td></tr>';
                              }
                        }
                           $returnArr .= '<tr class="child_row_count">
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Total</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.number_format($avalible_stock_total,2).'</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="total_'.$catImp.'" type="text" disabled name="total" id="total_'.$catImp.'" value="0" style="font-weight: bold;"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          </tr>';
                        $avalible_stock_grand_total = $avalible_stock_grand_total+$avalible_stock_total;  
                    }
                    $returnArr .= '<tr class="parent_row_count">
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Grand Total</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.number_format($avalible_stock_grand_total,2).'</strong></td>
                              <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="bold-input" type="text" disabled name="grand_total" id="grand_total" value="0" style="font-weight: bold;"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                          </tr>';
               
                }else{
                    $returnArr .='<tr><td colspan="6" style="font-weight: bold; font-size: 12px; display: table-cell;" align="center">No Data Available</td></tr>';
                } 

                      echo $returnArr;
                     ?>
                    </tbody> 
                    <?php echo form_error('product_id','<p class="error" style="color:#E9573F;display: inline;">','</p>')?> 
                 </table>
                </div> <br><div class="sip-table mb-15" role="grid">
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
           <?php }?>
             </div>
           </div>
         </div>
            <input type="hidden" name="actionType" id="actionType" value="save">
            <input type="hidden" name="id" value="<?php echo $ship_order_id;?>">       
       </form>
     </div>
   </div>
 </div>
        <div class="clearfix"></div>
          <div class="form-footer">
              <div class="pull-right">
                   <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                   <?php if($dataArr['requisition_type']=='provision'){
                    ?>
                    <button type="button" class="btn btn-success btn-slideright mr-5" onclick="checkExceedRecQty();">Save</button>
                    <?php
                   }else{
                    ?>
                    <button type="button" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360Form('add_rfq','shipping/add_rfq_details','98%','order_request_list');">Save</button>
                    <?php
                   }?>
                  
              </div>

                    <div class="clearfix"></div>
                </div>
<script type="text/javascript">//var groupPrdct = [];</script>
<?php
//if(!empty($group_products)){
  //  foreach ($group_products as $row) {
        $group_name = strtolower(str_replace(array('&',' '),array('_',''), $row->name));
        ?><script type="text/javascript">
            //groupPrdct['diff_qty_<?php echo $group_name;?>'] = '<?php echo $row->name;?>';</script><?php
   // }
//}
?>
<script type="text/javascript">
    
    function checkExceedRecQty(){
        var msg = true;
        $('.ordered_qty').each(function(){
            var qGrp = $(this).data('group');
            var oQty = parseFloat($(this).text());
            var rQty = parseFloat($('#recom_qty_'+qGrp).text());
            if(oQty!= rQty){
                msg=false;
            }
        });
        if(msg){
            submitAjax360Form('add_rfq','shipping/add_rfq_details','98%','order_request_list');
        }else{
            bootbox.dialog({
                message: 'Ordered Quantity is not equal to Recommended Quantity. Kindly adjust the quantity.',
                title: "Confirmation",
                className: "modal-primary",
                buttons: {
                    danger: {
                        label: "No Thanks, Please Proceed",
                        className: "btn-danger btn-slideright mLeft",
                        callback: function () {
                            submitAjax360Form('add_rfq','shipping/add_rfq_details','98%','order_request_list');
                        }
                    },
                    success: {
                        label: "Adjust Quantity",
                        className: "btn-success btn-slideright",
                        callback: function () {
                    
                        }
                    }

                }
            }); 
        }
    }
    
    // function reqTypeChange(val){
    //     if(val=='bonded_store' || val=='stores'){
    //         $('.cstmProductTbl').show();
    //         $('#add_new_prdct').show();
    //         $('.predefineProTbl').hide();
    //     }else{
    //         $('.cst_item_data').html('');
    //         $('.cstmProductTbl').hide();
    //         $('#add_new_prdct').val('0');
    //         $('#add_new_prdct').hide();
    //         $('.predefineProTbl').show();
    //     }
    // }
    var mode = 'add';
    $(document).ready(function(){
       convertToExcel();   

       $('#add_new_prdct').on('click',function(){
            var ttl_prdct = $('#ttl_prdct').val();
            var html = '<tr class="child_row"><td role="gridcell" tabindex="-1" aria-describedby="f2_key" style="outline: none;">-</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" name="item_name['+ttl_prdct+']"></td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" name="item_unit['+ttl_prdct+']"></td><td role="gridcell" class="group_avalible_stock" tabindex="-1" aria-describedby="f2_key" data-group="meat" data-value="">-</td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="qty_rows link quentity qtyChangePORKANDBACON" type="number" name="item_qty['+ttl_prdct+']" value=""></td><td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity" name="item_remark['+ttl_prdct+']" value=""></td></tr>';
            ttl_prdct = parseFloat(ttl_prdct)+1;
            $('#ttl_prdct').val(ttl_prdct);
            $('.cstmProductTbl').append(html);
            convertToExcel();
       });     
     })

    var ship_order_id = '<?php echo $dataArr['ship_order_id'];?>';

    if(ship_order_id!==''){
        mode ='edit';
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
      
      // var order_meat = 0;
      // var order_fruit = 0;
      // var order_rice = 0;

     
  //   if(mode=='edit'){
  //    $('.qty_rows').each(function(){
  //       var group = $(this).data('group');
  //       var value = $(this).data('value');
  //       value = parseFloat(value);
  //       if(!isNaN(value)){
  //           if(group=='meat'){
  //            order_meat = (parseFloat(order_meat) + value);   
  //           }
  //           else if(group=='fruit_vegetables'){
  //            order_fruit = (parseFloat(order_fruit) +value);   
  //           }
  //           else if(group=='rice_flour'){
  //            order_rice = (parseFloat(order_rice) + value);
  //           }
  //     }
  //   })
  //    $('#ordered_qty_meat').html(order_meat);
  //    $('#ordered_qty_meat').data('value',order_meat);
  //    $('#ordered_qty_fruit_vegetables').html(order_fruit);
  //    $('#ordered_qty_fruit_vegetables').data('value',order_fruit);
  //    $('#ordered_qty_rice_flour').html(order_rice);
  //    $('#ordered_qty_rice_flour').data('value',order_rice);
  // }

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

$(document).ready(function(){
  if(mode=='edit'){
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
  }
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

       
    $(document).ready(function() {
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