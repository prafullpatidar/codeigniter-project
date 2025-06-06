<?php 
$show_payment_term = checkLabelByTask('show_payment_term');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Receipt</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @media print {
        html, body {
    height:100%; 
    margin: 0 !important; 
    padding: 0 !important;
    overflow: hidden;
  }
  
}
</style>
<?php 
 $invoice_discount = checkLabelByTask('invoice_discount');

 if($dataArr['currency']==1){
 $curr ='EURO';
 }
 elseif($dataArr['currency']==2){
 $curr ='USD';
 }
 elseif($dataArr['currency']==3){
 $curr ='SGD';
 }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<form class="form-horizontal form-bordered" name="invoice_form" enctype="multipart/form-data" id="invoice_form" method="post">

<div class="new-invoice-header">
<table width="100%" class="invoice-table">
  <tr>
    <td colspan="2" width="40%" style="border:none"><img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="40" height="45"></td>
    <td colspan="4" width="60%" style="border:none"><h1 style="margin-top:0"><strong>Invoice</strong></h1></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3">
        <div>
            <h2><strong>ONE NORTH SHIPS</strong></h2>
            <h4>Connecting the World</h4>
            <h5>info@onenorthships.com / catering@onenorthships.com</h5>
        </div>
    </td>
    <td><strong>Date:</strong></td>
    <td colspan="2"><?php
     $dataArr['created_at'] = ($dataArr['created_at']) ? $dataArr['created_at'] : date('d-m-Y');
     echo $dataArr['created_at'];?></td>
  </tr>
  <tr>
    <td><strong>Invoice :</strong></td>
    <td colspan="2"><?php echo $dataArr['invoice_no'];?></td>
  </tr>
  <tr>
    <td><p><strong>Customer ID:</strong></p>
    <p>&nbsp;</p></td>
    <td colspan="2"><p><?php echo $dataArr['customer_id'];?></p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td colspan="3"><strong>To:</strong></td>
    <td><strong>Ship To:</strong></td>
    <td colspan="2"><strong>Vessel</strong></td></tr>
   <tr><td colspan="3"><p>To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></p>
     <p>C/O <?php echo ucwords($dataArr['company_name']);?></p>
     <p><?php echo ucwords($dataArr['company_address']);?></p>
     </td>
   <td colspan="3">
    <p><?php echo ucwords($dataArr['ship_name']);?>, IMO No. <?php echo $dataArr['imo_no'];?></p>
    <p>FOB <?php echo ucwords($dataArr['delivery_port']);?></p>
    <p><?php echo $dataArr['po_no'];?></p></td></tr>
    <tr><td <?php echo ($show_payment_term) ? '' : 'colspan="3"'?>>Reqsn Date</td><td colspan="2">Delivery Port</td>
         <?php
        if($show_payment_term){ 
        ?>
        <td colspan="2">Payment Terms</td>
    <?php } ?>
        <td>Currency</td></tr>
       <tr>
     <td <?php echo ($show_payment_term) ? '' : 'colspan="3"'?>><?php echo ConvertDate($dataArr['reqsn_date'],'','d-m-y');?></td><td colspan="2">FOB <?php echo ucwords($dataArr['delivery_port']);?></td>
     <?php
        if($show_payment_term){ 
        ?>
     <td colspan="2"><?php echo $dataArr['payment_term'];?></td>
     <?php } ?>
     <td><?php echo $curr;?></td></tr>
        
</table> 
        
            
        </div>

        <h4 style="text-align:center"><?php echo strtoupper(str_replace('_',' ',$dataArr['requisition_type']));?></h4>
      <div id="abc" class="sip-table" role="grid">
       <table class="table" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
        <thead>
          <th>Item No.</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Unit</th>
          <th>Unit Price</th>
          <th>Line Total</th>
        </thead>
        <tbody>
       <?php
        $total_price = 0;
          if(!empty($arrData)){
            foreach ($arrData as $key => $productArr) {
                 foreach($productArr as $category => $products){
                    $returnArr .= '<tr class="parent_row">
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                </tr>';
                         for ($i=0; $i <count($products) ; $i++) { 
                            $product_id = $products[$i]['product_id'];
                            $total_price = $total_price+($postArr['qty_'.$product_id] * $postArr['price_'.$product_id]);
                                   $returnArr .= '<tr class="child_row">';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input class="qty_rows" data-type="qty" data-quantity="1" data-id="'.$product_id.'" type="text" name="qty_'.$product_id.'" id="qty_'.$product_id.'" value="'.str_replace(',','',number_format($postArr['qty_'.$product_id],2)).'">'.form_error('qty_'.$product_id).'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                   $returnArr .= '<td role="gridcell" class="unit_price" id="unit_price_'.$product_id.'" tabindex="-1" aria-describedby="f2_key"><input class="qty_rows" data-quantity="1" data-id="'.$product_id.'" type="text" data-type="price" name="price_'.$product_id.'" id="price_'.$product_id.'" value="'.str_replace(',','',number_format($postArr['price_'.$product_id],2)).'"></td>';
                                   $returnArr .= '<td class="line_total" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="total_'.$product_id.'">'.str_replace(',','',number_format(($postArr['qty_'.$product_id] * $postArr['price_'.$product_id]),2)).'</td>';
                                    $returnArr .= '</tr>';
                           }   

                 }
              }

       echo $returnArr;       
    }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td data-value="<?php echo str_replace(',','',$total_price);?>" id="create_invoice_total_amount"><?php echo str_replace(',','',number_format($total_price,2));?></td>
            </tr>
            <?php 
             if($invoice_discount){ 
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Discount (%)</td>
                <td><input type="number" max="100" min="-100" class="form-control discount" id="discount" name="invoice_discount" value="<?php echo $postArr['invoice_discount'];?>" onkeyup="calculateDiscount(this.value)" onclick="calculateDiscount(this.value)">
               <?php echo form_error('invoice_discount','<p class="error">','</p>')?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Final Total</td>
                <td id="create_invoice_net_amount"><?php echo number_format($total_price,2);?></td>
            </tr>
        <?php } ?>
        </tbody>   
       </table>
      </div>
      <br>
       <div class="row">
           <div class="col-md-12">
               <label class="col-sm-12">Reason <span>*</span></label>
               <textarea name="reason" id="reason" style="width:50%"><?php echo $postArr['reason'];?></textarea>
               <?php echo form_error('reason','<p class="error">','</p>')?>
           </div>
       </div>
      <div class="invoice-footer">
          <div class="row mt-2">
            <div class="col-md-6">
            <p>
              <strong>
              Thank you for your business! <br>
            One North Ships, PO BOX 79998, Dubai, UAE
              </strong> 
          </p>
            </div>
            <div class="col-md-6">
            <input type="hidden" name="actionType" value="save">
            <input type="hidden" name="id" value="<?php echo $postArr['company_invoice_id'];?>">
            <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" onclick="submitAjax360Form('invoice_form','shipping/edit_invoice','98%','invoice_list');" class="btn btn-success btn-slideright">Submit</button>
           </div>

            </div>
          </div>
      </div>
</body>
</html>
<script type="text/javascript">
 $(document).ready(function(){
       calculateDiscount('<?php echo $postArr['invoice_discount'];?>') 
    
   $('.qty_rows').change(function(){
       var id = $(this).data('id');
       var type = $(this).data('type');
       if(type=='qty'){
        var unit_price = parseFloat($('#price_'+id).val() || 0);
        var total = (parseFloat(unit_price) * parseFloat($(this).val(),10).toFixed(2));
       }
       else{
        var qty = parseFloat($('#qty_'+id).val() || 0);
        var total = (parseFloat(qty) * parseFloat($(this).val(),10).toFixed(2));
       }
       $('#total_'+id).text(parseFloat(total,10).toFixed(2));       
     $('.line_total').trigger('change');
   })
 })
   
   $('.line_total').change(function(){
    var grand_total = 0;
     $('.line_total').each(function(){ 
         var val = parseFloat($(this).text() || 0);
        grand_total = (parseFloat(grand_total) + parseFloat(val));
     })
    $('#create_invoice_total_amount').text(parseFloat(grand_total,10).toFixed(2)); 
      calculateDiscount($('#discount').val());   
      
   })

       $(document).ready(function() {
            $('.qty_rows').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });

            // $('.discount').on('input', function(e) {
            //     // Get the input value
            //     var inputValue = $(this).val();
                
            //     // Remove any non-numeric and non-decimal characters
            //     var numericValue = inputValue.replace(/[^0-9.]/g, '');

            //     // Update the input field value
            //     $(this).val(numericValue);
            // });
    });  

</script>