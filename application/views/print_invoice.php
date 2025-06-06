<?php 
$show_payment_term = checkLabelByTask('show_payment_term');
$discount_label = checkLabelByTask('show_invoice_discount');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Receipt</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  td {
    padding: 5px;
    border: 1px solid #000000 ;
    color:#000000;
    font-size:12px;
    vertical-align:middle;
  }
  
}
</style>
</head>
<body>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
  <tr>
    <td width="40%" style="border:none"><img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="120"><br></td>
    <td width="60%" style="border:none"><p></p>
    <div style="height:10px;line-height:0"></div><h1 style="margin-top:10pt;font-size:24px;color:#4a206a;"><strong>INVOICE</strong></h1></td>
  </tr>
</table>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
  <tr>
    <td colspan="3" rowspan="3" style="line-height:0">
        <div>
            <h2 style="line-height:0.4;color:#4a206a;"><strong>ONE NORTH SHIPS</strong></h2>
            <h4 style="font-weight:400;font-size:12px;line-height:0.7">Connecting the World</h4>
            <h5 style="font-weight:400;font-size:12px;line-height:0.7">info@onenorthships.com / catering@onenorthships.com</h5>
        </div>
    </td>
    <td style="color:#4a206a;"><strong>Date:</strong></td>
    <td colspan="2"><?php
     echo ConvertDate($dataArr['invoice_date'],'','d-m-Y');?></td>
  </tr>
  <tr>
    <td style="padding-left:10em;color:#4a206a;"><strong>Invoice :</strong></td>
    <td colspan="2"><?php echo $dataArr['invoice_no'];?></td>
  </tr>
  <tr>
    <td><p><strong style="color:#4a206a;">Customer ID:</strong></p></td>
    <td colspan="2"><p><?php echo $dataArr['customer_id'];?></p>
  </td>
  </tr>
  <tr>
    <td colspan="3" style="font-size:10px;color:#4a206a;"><strong>To:</strong></td>
    <td style="font-size:10px;color:#4a206a;"><strong>Ship To:</strong></td>
    <td style="font-size:10px" colspan="2"><strong>Vessel</strong></td></tr>
   <tr>
    <td colspan="3" style="line-height:0">
      <div style="line-height:0"></div>
      <div style="line-height:1;">To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></div>
     <div style="line-height:1">C/O <?php echo ucwords($dataArr['company_name']);?></div>
     <div style="line-height:1"><?php echo ucwords($dataArr['company_address']);?></div>
     <div style="line-height:0.4"></div>
    </td>
    <td colspan="3" style="line-height:0">
    <div style="line-height:0"></div>
    <div style="line-height:1"><?php echo ucwords($dataArr['ship_name']);?>, IMO No. <?php echo $dataArr['imo_no'];?></div>
    <div style="line-height:1">FOB <?php echo ucwords($dataArr['delivery_port']);?></div>
    <div style="line-height:1"><?php echo $dataArr['po_no'];?></div>
    <div style="line-height:0.4"></div>
</td>
</tr>
    <tr><td align="center" <?php echo ($show_payment_term) ? '' : 'colspan="3"'?>>Reqsn Date</td><td align="center" colspan="2">Delivery Port</td>
        <?php
        if($show_payment_term){ 
        ?>
      <td colspan="2" align="center">Payment Terms</td>
    <?php } ?>
      <td align="center">Currency</td></tr>
       <tr>
     <td align="center" <?php echo ($show_payment_term) ? '' : 'colspan="3"'?>><?php echo ConvertDate($dataArr['reqsn_date'],'','d-m-y');?></td><td colspan="2" align="center">FOB <?php echo ucwords($dataArr['delivery_port']);?></td>
        <?php 
        if($show_payment_term){ 
        ?>
     <td colspan="2" align="center"><?php echo $dataArr['payment_term'];?></td>
         <?php } ?>
     <td align="center"><?php echo $dataArr['currency'];?></td></tr>
        
</table> 
      <h4 style="text-align:center;line-height:30em"><?php echo strtoupper(str_replace('_',' ',$dataArr['requisition_type']));?></h4>
      <div>
      <table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
        
          <tr>
            <td style="color:#4a206a;"><strong>Item No.</strong></td>
            <td style="color:#4a206a;"><strong>Description</strong></td>
            <td style="color:#4a206a;"><strong>Quantity</strong></td>
            <td style="color:#4a206a;"><strong>Unit</strong></td>
            <td style="color:#4a206a;"><strong>Unit Price($)</strong></td>
            <td style="color:#4a206a;"><strong>Line Total($)</strong></td>
</tr>
       <?php

        $total_price = 0;
          if(!empty($arrData)){
            foreach ($arrData as $key => $productArr) {
                 foreach($productArr as $category => $products){
                    $returnArr .= '<tr>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
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
                <td>Total($)</td>
                <td data-value="<?php echo $total_price;?>" id="create_invoice_total_amount"><?php echo number_format($total_price,2);?> </td>
            </tr>
            <?php 
             if(!empty($dataArr['invoice_discount']) && $discount_label){ 
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Discount (%)</td>
                <td><?php echo $dataArr['invoice_discount'];?>%</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Final Total($)</td>
                <td id="create_invoice_net_amount"><?php
                 $discount_amount = ($total_price*$dataArr['invoice_discount']) / 100;
                 $net_amount = $total_price - $discount_amount;
                 echo number_format($net_amount,2);
             ?></td>
            </tr>
        <?php 
          }
        ?>
       </table>
      </div>
      <br>
      <?php if($dataArr['reason']){?>
      <div>
          <label>Reason</label>
          <p><?php echo $dataArr['reason'];?></p>
      </div>
  <?php }?>
      <!-- <div>
          <div class="row mt-2">
            <div class="col-md-6">
            <p style="font-size:12px">
              <strong>Thank you for your business! <br>
            One North Ships, PO BOX 79998, Dubai, UAE
              </strong> 
          </p>
            </div>
            </div>
          </div> -->
      </div>
</body>
</html>