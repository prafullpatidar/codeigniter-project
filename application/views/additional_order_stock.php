<?php 
$session_data = getCustomSession('order_basic_details');
?>
<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="addEditOrderstock" enctype="multipart/form-data" id="addEditOrderstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="abc" class="sip-table full-h" role="grid">
               <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Item No.</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>QTY</th>
                  <th>Unit Price($)</th>
                  <th>Total Price($)</th>
                  </tr>
                </thead>
                    <tbody class="item_data">
                      <?php
                         if(!empty($productArr)){
                            $g_sum = 0;
                                foreach ($productArr as $parent => $rows) {
                                    foreach($rows as $category => $products){
                                         $returnArr .= '<tr class="parent_row">
                                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>        
                                                </tr>';
                                                $t_sum = 0;
                                                
                                            for ($i=0; $i <count($products) ; $i++) {
                                                 $t_sum += $products[$i]['quantity']* $products[$i]['unit_price'];
                                                 
                                                 $returnArr .= '<tr class="child_row">';
                                                 $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'</td>';
                                                $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format(($products[$i]['quantity'] * $products[$i]['unit_price']),2).'</td>';
                                                   $returnArr .= '</tr>'; 
                                            }
                                            $g_sum += $t_sum;
                                         $returnArr .= '<tr class="child_row_count">
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Total</strong></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><b>'.number_format($t_sum,2).'</b></td>
                                          
                                      </tr>';       
                                     }
                                 }
                                 $returnArr .= '<tr class="child_row_count">
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>Grand Total</strong></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                          <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><b>'.number_format($g_sum,2).'</b></td>
                                          
                                      </tr>';

                               }
                           echo $returnArr;    
                      ?>
                    </tbody> 
                    <?php echo form_error('product_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                 </table>
             </div>
           </div>
         </div>
            <input type="hidden" name="id" value="<?php echo $dataArr['id'];?>">
            <input type="hidden" name="actionType" id="actionType" value="save">  
       </form>
     </div>
   </div>
 </div>
            <div class="clearfix"></div>
              <div class="form-footer">
                  <div class="pull-right">
                       <button type="button" id="first_prev" class="btn btn-success btn-slideright mr-5" onclick="showAjaxModel('Create Purchase Order','shipping/order_basic_details','<?php echo $dataArr['id'];?>','','98%');">Preview</button>
                       <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal" onclick="deleteSession();">Cancel</a>
                      <!-- <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitOrderAdditionalDetails();">Save & Next</button> -->
                      <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360Form('addEditOrderstock','shipping/order_addition_details','98%','order_request_list');">Save</button>
                  </div>

                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->

<script type="text/javascript">
    $(document).ready(function(){
       convertToExcel();        
     })

function deleteSession(){
   $.ajax({
        beforeSend: function(){
          $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'shipping/deletePoSession',
        data : {'id':'<?php echo $dataArr['id'];?>'},
        success: function(msg){
          $("#customLoader").hide();
         } 
        });       
  }

 $('.close').click(function(){
    deleteSession();
 })

    // function submitTableForm(pageId, empty_sess=0)
    // {    

    //     var $data = new FormData($('#addEditOrderstock')[0]);
    //     $.ajax({
    //         beforeSend: function(){
    //                     $("#customLoader").show();
    //                 },
    //         type: "POST",
    //         url: base_url + 'shipping/WorkOrderItems',
    //         cache:false,
    //         data: $data,
    //         processData: false,
    //         contentType: false,
    //         success: function(msg)
    //         {
    //             $("#customLoader").hide();
    //             var obj = jQuery.parseJSON(msg);
    //             $('.item_data').html(obj.dataArr);
    //             convertToExcel();
    //         }
    //     });
    //     return false;
    // }
   
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


    $(window).on('beforeunload', function(){
    deleteSession();
});
</script>