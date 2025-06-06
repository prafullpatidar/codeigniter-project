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
<?php 
$show_payment_term = checkLabelByTask('show_payment_term');
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
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="viewRcpt">
<form class="form-horizontal form-bordered" name="invoice_form" enctype="multipart/form-data" id="invoice_form" method="post">

<div class="new-invoice-header">
<table width="100%" class="invoice-table">
  <tr>
    <td colspan="2" width="40%" style="border:none"><img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="40"></td>
    <td colspan="4" width="60%" style="border:none"><h2 style="margin-top:0"><strong>Invoice</strong></h2></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3">
            <h3>ONE NORTH SHIPS</h3>
            <p>Connecting the World<br>
            info@onenorthships.com / catering@onenorthships.com</p>
       
    </td>
    <td><strong>Date:</strong></td>
    <td colspan="2"><?php
     // $dataArr['created_at'] = ($dataArr['created_at']) ? $dataArr['created_at'] : date('d-m-Y');
     // echo $dataArr['created_at'];
     ?>
     <input type="text" readonly class="form-control datePicker_editPro" name="invoice_date" id="invoice_date" value="<?php if(!empty($dataArr['date'])){echo convertDate($dataArr['invoice_date'],'','d-m-Y');}?>">
      <?php echo form_error('invoice_date','<p class="error" style="display: inline;">','</p>'); ?>  
     </td>
  </tr>
  <tr>
    <td><strong>Invoice :</strong></td>
    <td colspan="2"><?php echo $dataArr['invoice_no'];?></td>
  </tr>
  <tr>
    <td><p><strong>Customer ID:</strong></p>
    </td>
    <td colspan="2"><p><?php echo $dataArr['customer_id'];?></p>
    </td>
  </tr>
  <tr>
    <td colspan="3"><strong>To:</strong></td>
    <td><strong>Ship To:</strong></td>
    <td colspan="2"><strong>Vessel</strong></td>
  </tr>
   <tr>
    <td colspan="3">
     <p>To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></p>
     <p>C/O <?php echo ucwords($dataArr['company_name']);?></p>
     <p><?php echo ucwords($dataArr['company_address']);?></p>
     </td>
   <td colspan="3"><p><?php echo ucwords($dataArr['ship_name']);?>, IMO No. <?php echo $dataArr['imo_no'];?></p>
    <p>FOB <?php echo ucwords($dataArr['delivery_port']);?></p>
    <p><?php echo $dataArr['po_no'];?></p></td>
  </tr>
    <tr><td <?php echo ($show_payment_term) ? '' : 'colspan="2"'?>>Reqsn Date</td><td colspan="2">Delivery Port</td>
        <?php
        if($show_payment_term){ 
        ?>
        <td colspan="2">Payment Terms</td>
        <?php } ?>
        <td>Currency</td></tr>
       <tr>
     <td <?php echo ($show_payment_term) ? '' : 'colspan="2"'?>><?php echo ConvertDate($dataArr['reqsn_date'],'','d-m-y');?></td><td colspan="2">FOB <?php echo ucwords($dataArr['delivery_port']);?></td>
        <?php
        if($show_payment_term){ 
        ?>
     <td colspan="2"><?php echo $dataArr['payment_term'];?></td>
        <?php } ?>
     
     <td><?php echo $curr;?></td></tr>
        
</table> 
        
            
        </div>

        <h4 style="text-align:center"><?php echo strtoupper(str_replace('_',' ',$dataArr['requisition_type']));?></h4>
      <div id="abc" class="sip-table double-table" role="grid">
       <table class="table header-fixed-new table-text-ellipsis table-layout-fixed">
        <thead class="t-header">
          <th width="10%">Item No.</th>
          <th width="30%">Description</th>
          <th width="10%">Quantity</th>
          <th width="10%">Unit</th>
          <th width="10%">Unit Price ($)</th>
          <th width="10%">Line Total ($)</th>
        </thead>
        <tbody>
       <?php
        $total_price = 0;
          if(!empty($arrData)){
            foreach ($arrData as $key => $productArr) {
                 foreach($productArr as $category => $products){
                    $returnArr .= '<tr class="parent_row">
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                </tr>';
                         for ($i=0; $i <count($products) ; $i++) { 
                            $total_price = $total_price+$products[$i]['total_price'];
                                   $returnArr .= '<tr class="child_row">';
                                   $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                   $returnArr .= '<td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                  $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                                   $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                   $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'</td>';
                                   $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['total_price'],2).'</td>';
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
                <td><strong>Total ($)</strong></td>
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
                <td><strong>Discount (%)</strong></td>
                <td><input type="number" class="form-control discount" id="discount" name="invoice_discount" max="100" min="-100" value="0" onkeyup="calculateDiscount(this.value)" onclick="calculateDiscount(this.value)">
                 <?php echo form_error('invoice_discount','<p class="error" style="display:inline";>','<p>')?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Final Total ($)</strong></td>
                <td id="create_invoice_net_amount"><?php echo number_format($total_price,2);?></td>
            </tr>
        <?php } ?>
        </tbody>   
       </table>
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
            <input type="hidden" name="id" value="<?php echo $dataArr['delivery_note_id'];?>">
            <div class="pull-right">
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" onclick="submitAjax360Form('invoice_form','shipping/create_invoice','98%','delivery_note_list');" class="btn btn-success btn-slideright">Submit</button>
           </div>

            </div>
          </div>
      </div>
      </div>
</body>
</html>
<!-- <script type="text/javascript">
     $('.discount').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });
</script> -->
<script type="text/javascript">
        jQuery(document).ready(function(){
   $('.datePicker_editPro').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
         changeYear:true,
        yearRange: "c-4:c+3"
    });
    });
   
</script>