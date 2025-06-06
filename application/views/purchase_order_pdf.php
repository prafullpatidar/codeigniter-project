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
<?php 
     // $total_price = 0;
     // foreach($data as $v){
     //     $productArr[$v->parent_group][$v->category_name][] = $v;
     //     $total_price = $total_price+($v->price);
     //    }
     // $productArr2 = unserialize($data[0]->json_data);
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
<body style="background: red;font-family: Arial, Helvetica, sans-serif;">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
<td valign="middle" width="110"><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="100" height="100"></td>
<td width="770" align="center" valign="bottom"><br><br><br><br><span style="color:#00afef; font-size:35px; font-weight:bold; margin-bottom:0; padding-bottom:0;">ONE NORTH SHIPS</span><br>
<span>PO BOX 79998, DUBAI UAE</span><br>
<!-- <span>Tel.: +91-9810143870</span><br> -->
<span>Email: info@onenorthships.com/catering@onenorthships.com</span><br>
<span style="color:#ffc000; font-size:30px; font-weight:bold; margin-bottom:0; padding-bottom:0;">PURCHASE ORDER</span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="100%" border="1" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="4" width="50%" style="font-size:18px;"><strong>Purchase Order Issued To:</strong> </td>
            <td colspan="3" rowspan="2" width="50%" style="font-size:18px;" valign="middle" align="center" height="110"><br><br><br><strong>INVOICE</strong></td>
          </tr>
          <tr>
            <td colspan="4" rowspan="3"><?php echo $data['vendor_name'];?><br />
      <?php echo $data['vendor_address'];?><br />
      <?php echo $data['vendor_phone'];?>
            </td>
          </tr>
          <tr>
            <td align="center"><strong>PO Number</strong></td>
            <td align="center"><strong>PO Date </strong></td>
            <td align="center"><strong>Payment Due On</strong></td>
          </tr>
          <tr>
            <td align="center"><?php echo $data['po_no'];?></td>
            <td align="center"><?php echo date('d-m-Y',strtotime($data['order_date']))?> </td>
            <td align="center"><?php echo date('d-m-Y',strtotime($data['due_date']))?></td>
          </tr>
          <tr>
            <td align="center"><strong>Delivery<br>Date</strong></td>
            <td align="center"><strong>Vessel</strong></td>
            <td align="center"><strong>Port of<br />
            Delivery</strong></td>
            <td align="center"><strong>Type of<br />
            Requisition</strong></td>
            <td colspan="2" align="center"><strong>PO Details</strong></td>
            <td align="center"><strong>Remarks</strong></td>
          </tr>
          <tr>
            <td align="center"><strong><?php echo date('d-m-Y',strtotime($data['delivery_date']))?></strong> </td>
            <td align="center"><strong><?php echo $data['ship_name'];?></strong> </td>
            <td align="center"><strong><?php echo $data['delivery_port'];?></strong> </td>
            <td align="center"><strong><?php echo strtoupper(str_replace('_',' ',$data['requisition_type']));?></strong></td>
            <td align="center" colspan="2">As per below description</td>
            <td align="center"><?php echo $data['remark'];?></td>
          </tr>
          <tr>
            <td align="center"><strong>S.no.</strong></td>
            <td colspan="3" align="center"><strong>Description</strong></td>
            <td align="center"><strong>Quantity</strong></td>
            <td align="center"><strong>Unit Price<br>(<?php echo $curr;?>)</strong></td>
            <td align="center"><strong>Price (<?php echo $curr;?>)</strong></td>
          </tr>
            <tr>    
            <td align="center"><strong>1</strong></td>
                    <td colspan="3" height="20" style="height:30px"><?php echo $data['ship_name'];?>-<?php echo convertDate($data['created_on'],'','d-m')?>-<?php echo strtoupper(str_replace('_',' ',$data['requisition_type']));?></td>
                    <td align="center" colspan="2">AS PER THE SUPPLY LIST</td>
                    <td align="center"><?php echo $data['total_price'];?></td>
                  </tr>
          <tr>
            <td colspan="4" align="center"><strong>Amount in words</strong></td>
            <td colspan="2" rowspan="2" align="center" valign="middle"><br><br><strong>Net total amount in USD</strong></td>
            <td rowspan="2" align="center" valign="bottom"><br><br><strong>$ <?php echo number_format($data['total_price'],2);?></strong></td>
          </tr>
          <tr>
            <td colspan="4" align="center">US DOLLARS <?php echo numberTowords($data['total_price'],$curr).' ONLY';?></td>
            
            <!-- <td>&nbsp;</td> -->
          </tr>
        </table>
</td>
</tr>
<tr>
<td>
<table cellpadding="20" cellspacing="0">

<tr>
               <td align="left">....................................................<br><span style="color:#868686; font-size:12px;">Supplier's Signature</span></td> 
               <td align="right">....................................................<br><span style="color:#868686; font-size:12px;">For ONE NORTH SHIPS</span></td> 
            </tr>
            <tr><td colspan="2"><p>“This document is computer generated and does not require any signature or the Company’s stamp in order to be considered valid”. </p></td></tr>
</table>
</td>
</tr>
</table>


<!-- 
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td valign="top"><br><br><br><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="100" height="100"></td>
<td valign="top"> <h2 style="color:blue; margin-top:0;">ONE NORTH SHIPS</h2>
            <p>OFFICE: - PO Box 79998, Dubai UAE <br/>
                +971-509 176 955/ +91-9810143870 <br/>
                Email: info@onenorthships.com/purchase@onenorthships.com <br/>
            </p></td>
</tr>
</table>


      -->  <!-- <div class="page-header text-blue-d2">
            <img class="domain_logo" src="<?php //echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="100" height="100">
        </div>-->
       <!-- <div class="text-center" style="text-align:center">
           
        </div>
        <h1 style="text-align:center; margin-top:0;">PURCHASE ORDER</h1>

        <table width="100%" border="1" cellpadding="5" cellspacing="0">
          <tr>
            <td colspan="4">Purchase Order Issued To: </td>
            <td colspan="3" rowspan="2">INVOICE</td>
          </tr>
          <tr>
            <td colspan="4" rowspan="3"><?php echo $productArr[$v->parent_group][$v->category_name][0]->vendor_name;?></td>
          </tr>
          <tr>
            <td>PO Number</td>
            <td>PO Date </td>
            <td>Payment Due On</td>
          </tr>
          <tr>
            <td><?php echo $productArr[$v->parent_group][$v->category_name][0]->po_no;?></td>
            <td><?php echo date('d-m-Y',strtotime($productArr[$v->parent_group][$v->category_name][0]->order_date))?> </td>
            <td><?php echo date('d-m-Y',strtotime($productArr[$v->parent_group][$v->category_name][0]->due_date))?></td>
          </tr>
          <tr>
            <td>Delivery DATE</td>
            <td>Vessel</td>
            <td>Port of<br />
            Delivery</td>
            <td>Type of<br />
            Requisition</td>
            <td colspan="2">PO Details</td>
            <td>Remarks</td>
          </tr>
          <tr>
            <td><?php echo date('d-m-Y',strtotime($productArr[$v->parent_group][$v->category_name][0]->delivery_date))?> </td>
            <td><?php echo $productArr[$v->parent_group][$v->category_name][0]->ship_name;?> </td>
            <td><?php echo $productArr[$v->parent_group][$v->category_name][0]->port;?> </td>
            <td><?php echo strtoupper(str_replace('_',' ',$v->requisition_type));?></td>
            <td colspan="2">As per below description</td>
            <td><?php echo $productArr[$v->parent_group][$v->category_name][0]->remark;?></td>
          </tr>
          <tr>
            <td>S.no.</td>
            <td colspan="3">Description</td>
            <td>Quantity</td>
            <td>Unit Price (<?php echo $curr;?>)</td>
            <td>Price (<?php echo $curr;?>)</td>
          </tr>
          <?php 
            $i=1;
            $total_amount = 0;
            foreach($productArr2 as $rows){
              $getProductData = $this->mp->getAllProductbyid(' and p.product_id = '.$rows['product_id']);
              $total_amount += ($rows['qty'] * $rows['unit_price']);
              ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td colspan="3"><?php echo $getProductData->product_name;?></td>
                    <td><?php echo number_format($rows['qty'],2);?></td>
                    <td><?php echo number_format($rows['unit_price'],2);?></td>
                    <td colspan="2"><?php echo number_format($rows['qty'] * $rows['unit_price'],2);?></td>
                    <td><?php echo $i;?></td>
                  </tr>
          <?php 
            $i++;
           }?>
          <tr>
            <td colspan="4">Amount in words</td>
            <td colspan="2" rowspan="2">Net total amount in ($)</td>
            <td rowspan="2"><?php echo number_format($total_amount,2);?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo numberTowords($total_amount,$curr).' Only';?></td>
         -->   <!-- <td>&nbsp;</td> -->
          <!--</tr>
        </table><br/><br/>
        <table width="100%" style="width: 100%;">
            <tr>
               <td style="width:80%">_______________</td> 
               <td style="width:30%">_______________</td> 
            </tr>
            <tr>
               <td style="width:80%">Supplier's Signature</td> 
               <td style="width:30%">For ONE NORTH GLOBAL LOGISTICS </td> 
            </tr>
            <p>“This document is computer generated and does not require any signature or the Company’s stamp in order to be considered valid”. </p>
        </table> -->
</body>
</html>