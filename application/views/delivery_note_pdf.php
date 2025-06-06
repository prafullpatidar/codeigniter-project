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
  td {
    padding: 5px;
    border: 1px solid #000000 ;
    color:#000000;
    font-size:12px;
    vertical-align:middle;
  }
  
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php 
     $total_price = 0;
     foreach($data as $v){
         $productArr[$v->parent_group][$v->category_name][] = $v;
         $total_price = $total_price+($v->price);
        }
      $productArr2 = unserialize($data[0]->dn_json_data);
      $currency = $productArr[$v->parent_group][$v->category_name][0]->currency;
     if($currency == 1){
        $curr = 'EURO';
        $currSymbol = '€';
     }else if($currency == 2){
        $curr = 'USD';
        $currSymbol = '$';
     }else{
        $curr = 'SGD';
        $currSymbol = 'S$';
     }
     //echo '<pre>';print_r($dataArr);die('sjahgdhas');
?>
<body style="background: red;font-family: Arial, Helvetica, sans-serif;">
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
<tr>
  <td width="10%" style="border:none;"><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="60"></td>
  <td width="70%" style="border:none;">
  <h1 style="margin-top:10pt;font-size:24px;text-align:center;color:#4a206a;">Delivery Note Receipt</h1>
  </td>
  <td width="20%" style="border:none;"></td>
</tr>
</table>
<div style="line-height:0;border-top:2px solid #4a206a"></div>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
  <tr>
    <td valigh="top" width="50%" style="border:none;line-height:1.2;padding-bottom:10px" rowspan="3">
      <h2 style="line-height:0;font-size:20px;color:#4a206a;">One North Ships</h2>
      <h3 style="font-weight:400;line-height:1.5;color:#707070;">Connecting the World</h3>
      <h4 style="font-weight:400;line-height:0;color:#707070;">info@onenorthships.com/catering@onenorthships.com</h4>
      <div style="line-height:0.5;"></div>
    </td>
    <td valigh="top" width="13%" style="line-height:0.7;border:none;" width="18%"><strong style="color:#4a206a;"  >Date:</strong></td>
    <td valigh="top" width="37%" style="line-height:0.7;border:none;"><?php echo ConvertDate($dataArr['date'],'','d-m-y');?>
</td>
  </tr>
  <tr>
    <td style="line-height:0.7;border:none;" width="18%"><strong style="color:#4a206a;">Delivery Note No #:</strong></td>
    <td style="line-height:0.7;border:none;" colspan="2"><?php echo $dataArr['note_no'];?></td>
  </tr>
  <tr>
    <td style="line-height:0.7;border:none;" width="18%"><strong style="color:#4a206a;">Customer ID:</strong></td>
    <td style="border:none;line-height:0.7;border:none;" colspan="2"><?php echo $dataArr['customer_id'];?></td>
  </tr>
  </table>
<div style="line-height:0;border-top:2px solid #4a206a"></div>
<table Cellpadding="5" width="100%">
  <tr>
    <td width="50%" style="border:none;"><strong style="color:#4a206a;">To:</strong> To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></td>
    <td width="13%" style="border:none;"><strong style="color:#4a206a;">Ship to:</strong></td>
    <td width="37%" style="border:none;line-height:18px"><strong>Vessel</strong><br />
      <?php echo ucwords($dataArr['ship_name']).', IMO NO. '.$dataArr['imo_no'];?><br />
      FOB <?php echo $dataArr['delivery_port'];?><br />
      PO : <?php echo $dataArr['po_no'];?></td>
  </tr>
</table>
<div style="line-height:20px"></div>
<table Cellpadding="5" width="100%">
<tr>
  <td style="text-align:center">Reqsn Date</td>
  <td style="text-align:center">Delivery Port</td>
  <?php 
   if($show_payment_term){
  ?>
  <td style="text-align:center">Payment Terms</td>
  <?php 
   } 
   ?>
  <td style="text-align:center">Currency</td>
</tr>
<?php 
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
<tr>
  <td style="text-align:center"><?php echo date('d-m-Y',strtotime($dataArr['reqsn_date']));?></td>
  <td style="text-align:center"><?php echo strtoupper($dataArr['delivery_port']);?></td>
   <?php 
   if($show_payment_term){
  ?>

  <td style="text-align:center"><?php echo $dataArr['payment_term'];?></td>
<?php } ?>
  <td style="text-align:center"><?php echo $curr;?></td>
</tr>
</table>

<!-- <br><br> -->

<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
 <tr>
    <td style="border:none;font-weight:bold;" colspan="5" align="center"><?php echo ucfirst(str_replace('_',' ',$dataArr['requisition_type']));?></td>
  </tr>
  <tr>
    <td style="border:none;color:#4a206a;"><strong>Item No</strong></td>
    <td style="border:none;color:#4a206a;"><strong>Description</strong></td>
    <td style="border:none;color:#4a206a;"><strong>Quantity</strong></td>
    <td style="border:none;color:#4a206a;"><strong>Unit</strong></td>
    <td style="border:none;color:#4a206a;" colspan="2"><strong>Comment</strong></td>
  </tr>
   <?php 
   if(!empty($productArr)){
                  foreach ($productArr as $parent => $rows) {
                    foreach($rows as $category => $products){
                       $returnArr .= '<tr class="parent_row">
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>';
                     for ($i=0; $i <count($products) ; $i++) { 
                        //$products[$i] = (array) $products[$i];
                        $product_id = $products[$i]['product_id'];
                       $returnArr .= '<tr class="child_row">';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                       
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucwords(str_replace('_',' ', $products[$i]['type'])).'</td>';
                       if($products[$i]['type']=='short_supply' || $products[$i]['type']=='wrong_supply'){
                         $img = $products[$i]['supply_qty'];
                       }
                       elseif($products[$i]['type']=='damange_and_spoil' || $products[$i]['type']=='poor_quality'){
                         $img = ($products[$i]['img_url']) ? '<div id="thumbnail-container"><img width="25" class="thumbnail" alt="Sample Image" src="'.base_url().'/uploads/delivery_receipt/'.$products[$i]['img_url'].'"></div>' : '-';
                       }
                       elseif($products[$i]['type']=='other'){
                         $img = $products[$i]['comment'];
                       }

                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$img.'</td>';

                        $returnArr .='</tr>'; 
                     }
                  }
                }
            }
            echo $returnArr;
  ?>
</table>
<p>Signature:
  <img src="<?php echo base_url()?>uploads/e_signature/<?php echo $dataArr['e_sign'];?>" height="40" width="40"/></p>
</div>
</body>
</html>