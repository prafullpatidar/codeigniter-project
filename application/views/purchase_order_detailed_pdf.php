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
  td, th {
    font-size: 12px;
  }
  
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php 
     // $total_price = 0;
     // foreach($data as $v){
     //     $productArr[$v->parent_group][$v->category_name][] = $v;
     //     $total_price = $total_price+($v->price);
     //    }
     //  $productArr2 = unserialize($data[0]->json_data);
     // $currency = $productArr[$v->parent_group][$v->category_name][0]->currency;
     if($data['currency'] == 1){
        $curr = 'EURO';
        $currSymbol = '€';
     }else if($data['currency'] == 2){
        $curr = 'USD';
        $currSymbol = '$';
     }else{
        $curr = 'SGD';
        $currSymbol = 'S$';
     }
?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
<td valign="middle" width="110"><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="100" height="100"></td>
<td width="770" align="center" valign="bottom"><br><h2 style="color:#4a206a; font-size:30px; font-weight:bold;">Purchase Order</h2></td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellpadding="5" style=" border-bottom-width:3px; border-bottom-style:solid; border-bottom-color:#4a206a;  border-top-width:3px; border-top-style:solid; border-top-color:#4a206a;">
  <tr>
    <td valign="top" style="padding-top:10px;padding-bottom:10px;text-align:left;" align="left">
      <p style="line-height:6px;margin-top:0;margin-bottom:0; padding-top:0; font-size:23px; font-weight:bold; color:#4a206a; margin-bottom:20px; padding-bottom:10px; display:block;">One North Ships</p>
      <p style="line-height:0;color:#868686; display:inline-block; font-size:14px;">Connecting the World</p>
      <p style="line-height:3px;color:#868686; display:inline-block; font-size:14px;">info@onenorthships.com/catering@onenorthships.com</p></td>
    <td valign="top"><table cellpadding="5" cellspacing="0">
    <tr>  
    <td style="color:#4a206a; font-weight:bold;">Date:</td>
    <td align="center"><?php echo date('d-m-Y',strtotime($data['order_date']));?></td>
    </tr>
    <tr>
    <td style="color:#4a206a; font-weight:bold;">Invoice #:</td>
    <td align="center"><?php echo $data['invoice_no'];?></td>
  </tr>
  <tr>
    <td style="color:#4a206a; font-weight:bold;">Customer ID:</td>
    <td align="center"><?php echo $data['customer_id'];?></td>
  </tr>
    </table>
    </td>
  </tr>
</table>
</td>

</tr>
<tr>
<td>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>
<table cellpadding="5" cellspacing="0" height="100%" width="100%">
<tr>
<td width="100" align="right">To:</td>
<td width="370" height="130"><?php echo $data['vendor_name'];?><br />
      <?php echo $data['vendor_address'];?><br />
      <?php echo $data['vendor_phone'];?></td>
</tr>
</table>
</td>
<td>
<table cellpadding="5" cellspacing="0" width="100%">
<tr>
<td width="100" style="color:#4a206a; font-weight:bold; line-height:30px">Ship to:</td>
<td width="350" style="line-height:18px;"><strong>Vessel</strong><br />
      <?php echo $data['ship_name'];?>, IMO NO. <?php echo $data['imo_no'];?><br />
      FOB <?php echo $data['delivery_port'];?><br />
      PO : <?php echo $data['po_no'];?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table cellpadding="5" cellspacing="0" width="100%" style="border-style:solid; border-width:2px; border-color:#4a206a;">
<tr>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;">Reqsn Date</td><td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;">Delivery Port</td>
<?php 
   if($show_payment_term){
?>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;">Payment Terms</td>
<?php 
}
?>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;">Currency</td>
</tr>
<tr>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;"><?php echo date('d-m-Y',strtotime($data['reqsn_date']));?></td><td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;"><?php echo $data['delivery_port'];?></td>
<?php 
 if($show_payment_term){
?>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;"><?php echo $data['payment_term'];?></td>
<?php }?>
<td align="center" style="border-bottom-style:solid; border-bottom-width:2px; border-bottom-color:#4a206a;border-left-style:solid; border-left-width:2px; border-left-color:#4a206a;border-right-style:solid; border-right-width:2px; border-right-color:#4a206a;color:#5c5757; font-weight:bold; display:inline-block; font-size:12px;"><?php echo $curr;?></td>
</tr>

</table>
</td>
</tr>
<tr><td align="center" style="color:#5c5757; display:inline-block; font-size:14px;"><?php echo strtoupper(str_replace('_',' ',$data['requisition_type']));?></td></tr>
<tr>
<td>
<table width="100%" border="0" border-collapse:collapse; cellpadding="5">
  <tr>
    <th align="left" style="color:#4a206a; font-weight:bold;" width="10%">Item No</th>
    <th align="left" style="color:#4a206a; font-weight:bold;" width="32.5%">Description</th>
    <th align="left" style="color:#4a206a; font-weight:bold;">Quantity</th>
    <th align="left" style="color:#4a206a; font-weight:bold;">Unit</th>
    <th align="left" style="color:#4a206a; font-weight:bold;" width="12%">Unit Price ($)</th>
    <th align="left" style="color:#4a206a; font-weight:bold;" width="12%">Line Total ($)</th>
  </tr>
  <tbody>
  <?php 
    // $k = 0;
   if(!empty($productArr)){
    foreach ($productArr as $sequence => $products) {
       for ($i=0; $i < count($products); $i++) { 
       // foreach($rows as $category => $products){
      ?>
        <tr >
        <td style="border: 1px solid #5c5757;"><?php echo $products[$i]['item_no']?></td>
        <td style="border: 1px solid #5c5757;"><?php echo ucwords($products[$i]['product_name']);?></td>
        <td style="border: 1px solid #5c5757;"><?php echo number_format($products[$i]['quantity'],2);?></td>
        <td style="border: 1px solid #5c5757;"><?php echo strtoupper($products[$i]['unit']);?></td>
        <td style="border: 1px solid #5c5757;"><?php echo number_format($products[$i]['unit_price'],2);?></td>
        <td style="border: 1px solid #5c5757;"><?php echo number_format($products[$i]['quantity'] * $products[$i]['unit_price'],2);?></td>
      </tr> 
    <?php
        } 
       }
      // /}
      ?>
     <tr>
     <td colspan="4"></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u>Total</u></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u><?php echo number_format($data['total_price'],2);?></u></td>
     </tr>
     <tr>
     <td colspan="4"></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u>Grand Total</u></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u><?php echo number_format($data['total_price'],2);?></u></td>
     </tr> 
     <!-- <tr>
     <td colspan="4"></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u>Grand Total</u></td>
       <td style="font-weight:bold; font-size:12px;text-decoration:underline" align="center"><u>732.6</u></td>
     </tr> -->
  <?php }
   ?>
   </tbody>
</table>
</td>
</tr>
</table>
</div>
</body>
</html>