<?php 
$show_payment_term = checkLabelByTask('show_payment_term');
$invoice_discount = checkLabelByTask('show_invoice_discount');
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="viewRcpt">
<div class="new-invoice-header">





<!------------------->


<table width="100%" class="invoice-table">
  <tr>
    <td colspan="2" width="40%" style="border:none"><img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="40"></td>
    <td colspan="4" width="60%" style="border:none"><h2>Invoice</h2></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3">
        
            <h3>ONE NORTH SHIPS</h3>
            <p>Connecting the World<br>
            info@onenorthships.com / catering@onenorthships.com</p>
        
    </td>
    <td><strong>Date:</strong></td>
    <td colspan="2"><?php
     echo ConvertDate($dataArr['invoice_date'],'','d-m-Y');?></td>
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
    <td colspan="2"><strong>Vessel</strong></td></tr>
   <tr><td colspan="3">
    <p>To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></p>
     <p>C/O <?php echo ucwords($dataArr['company_name']);?></p>
     <p><?php echo ucwords($dataArr['company_address']);?></p>
     </td>
   <td colspan="3" style="line-height:1.8">
    <p><?php echo ucwords($dataArr['ship_name']);?>, IMO No. <?php echo $dataArr['imo_no'];?></p>
    <p>FOB <?php echo ucwords($dataArr['delivery_port']);?></p>
    <p><?php echo $dataArr['po_no'];?></p></td></tr>
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
     <td><?php echo $dataArr['currency'];?></td></tr>
        
</table> 
        
            
        </div>

        <h4 style="text-align:center"><?php echo strtoupper(str_replace('_',' ',$dataArr['requisition_type']));?></h4>
      <div id="abc" class="sip-table" role="grid">
       <table class="table header-fixed-new table-text-ellipsis table-layout-fixed" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
        <thead class="t-header">
          <th width="8%">Item No.</th>
          <th width="30%">Description</th>
          <th width="8%">Quantity</th>
          <th width="8%">Unit</th>
          <th width="8%">Unit Price($)</th>
          <th width="8%">Line Total($) </th>
        </thead>
        <tbody>
       <?php
        $total_price = 0;
          if(!empty($arrData)){
            foreach ($arrData as $key => $productArr) {
                 foreach($productArr as $category => $products){
                    $returnArr .= '<tr class="parent_row">
                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="30%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong>'.$category.'</strong></td>
                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                </tr>';
                         for ($i=0; $i <count($products) ; $i++) { 
                            $total_price = $total_price+$products[$i]['total_price'];
                                   $returnArr .= '<tr class="child_row">';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                  $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['qty'],2).'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['unit_price'],2).'</td>';
                                   $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['total_price'],2).'</td>';
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
                <td><strong>Total($)</strong></td>
                <td data-value="<?php echo $total_price;?>" id="create_invoice_total_amount"><?php echo number_format($total_price,2);?> </td>
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
                <td><?php echo $dataArr['invoice_discount'];?>%</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Final Total ($)</strong></td>
                <td id="create_invoice_net_amount"><?php
                 $discount_amount = ($total_price*$dataArr['invoice_discount']) / 100;
                 $net_amount = $total_price - $discount_amount;
                 echo number_format($net_amount,2);
             ?></td>
            </tr>
        <?php 
          }
        ?>
        </tbody>   
       </table>
      </div>
      <?php if($dataArr['reason']){?>
       <br>
       <div class="row">
           <div class="col-md-12">
               <label class="col-sm-12">Reason</label>
               <p><?php echo $dataArr['reason'];?></p>
           </div>
       </div>
   <?php } ?>
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
            <div class="pull-right">
                <a href="<?php echo base_url().'/shipping/printPdf/'.$dataArr['company_invoice_id'];?>" target="_blank" class="btn btn-success btn-slideright">Print PDF</a>
           </div>
            </div>
          </div>
      </div>
  </div>    
</body>
</html>