<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h2 {margin-bottom:0;color:#fff000}
        @media print {
        html, body {
    height:100%; 
    margin: 0 !important; 
    padding: 0 !important;
    overflow: hidden;
    
  }
  td {
    padding: 3px 4px;
    border: 1px solid #000000 ;
    color:#000000;
    font-size:12px;
    vertical-align:middle;
  }
  h2 {margin-bottom:0;}
  p {margin-top: 0;}
}
</style>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
<tr>
<td width="10%" style="border:none">
<img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="60"></td>
<td width="80%" style="border:none;text-align:center;"><h2 style="line-height:0;font-size:19px;color:#00afef;">ONE NORTH SHIPS</h2>
<p style="margin-top:0;margin-bottom:90px;font-size:12px;line-height:1.6">PO BOX 79998, DUBAI UAE<br />
<!-- Tel.: +91-9810143870<br /> -->
Email: info@onenorthships.com/catering@onenorthships.com</p>
<h3 style="color:#ffc000;line-height:0;font-size:20px;">INVOICE</h3>
<div style="height:0px;line-height:0"></div>
</td>
<td width="10%" style="border:none">&nbsp;</td>
</tr>

</table>

<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">

<tr>
<td colspan="4" Cellpadding="5"><strong>Invoice To:</strong></td>
<td colspan="3" rowspan="2" style="text-align:center"><p>&nbsp;</p><div style="height:20px"></div><strong>INVOICE</strong></td>
</tr>
<tr>
<td colspan="4" rowspan="3">The Owner/Master <?php echo ucwords($dataArr['ship_name']);?><br/>
C/O <?php echo ucwords($dataArr['company_name']);?>,<br/>
<?php echo ucwords($dataArr['company_address']);?><br/>
Tel: <?php echo $dataArr['company_phone'];?></td>
</tr>
<tr>
<td style="text-align:center"><strong>Invoice Number</strong></td>
<td style="text-align:center"><strong>Invoice Date</strong></td>
<td style="text-align:center"><strong>Payment Due On</strong></td>
</tr>
<tr>
<td style="text-align:center"><strong><?php echo $dataArr['invoice_no'];?></strong></td>
<td style="text-align:center"><?php echo ConvertDate($dataArr['invoice_date'],'','d-m-Y');?></td>
<td style="text-align:center"><?php echo ConvertDate($dataArr['due_date'],'','d-m-Y');?></td>
</tr>
<tr>
<td style="text-align:center"><strong>Delivery date</strong></td>
<td style="text-align:center" ><strong>Vessel</strong></td>
<td style="text-align:center"><strong>Port of Delivery</strong></td>
<td style="text-align:center"><strong>Type of Requisition</strong></td>
<td colspan="2" style="text-align:center;line-height:1.5">
<strong>PO No.</strong><br/>
<strong><?php echo $dataArr['po_no'];?></strong>
</td>
<td><strong>Remarks</strong></td>
</tr>
<tr>
<td style="text-align:center"><strong><?php echo ConvertDate($dataArr['delivery_date'],'','d-m-Y');?></strong></td>
<td style="text-align:center"><strong><?php echo ucwords($dataArr['ship_name']);?></strong></td>
<td style="text-align:center"><strong><?php echo $dataArr['delivery_port'];?></strong></td>
<td style="text-align:center"><strong><?php 
$req_type = str_replace('_',' ',$dataArr['requisition_type']);
echo strtoupper($req_type);?> </strong></td>
<td style="text-align:center" colspan="2">As per below description</td>
<td style="text-align:center">STORE SUPPLY FOR VESSEL USE.</td>
</tr>
</table>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;"> 
<tr>
<td style="text-align:center"><strong>S.no.</strong></td>
<td style="text-align:center" colspan="3"><strong>Description</strong></td>
<td style="text-align:center"><strong>Quantity</strong></td>
<td style="text-align:center"><strong>Unit Price (USD)</strong></td>
<td style="text-align:center"><strong>Price (USD)</strong></td>
</tr>
<tr>
<td style="text-align:center">1</td>
<td colspan="3"><?php echo ucwords($dataArr['ship_name']);?>-<?php echo ConvertDate($dataArr['created_at'],'','m-Y');?> <?php $req_type = str_replace('_',' ',$dataArr['requisition_type']);
echo strtoupper($req_type);?></td>
<td style="text-align:center" colspan="2">AS PER THE SUPPLY LIST&nbsp;</td>
<td style="text-align:center"><?php echo number_format($dataArr['total_price'],2);?></td>
</tr>
<tr>
<td style="text-align:center;line-height:1" colspan="4"><strong>Amount in words</strong></td>
<td colspan="2" rowspan="2" style="text-align:center;"><div style="line-height:0;"></div><p><strong>Net total amount in USD</strong></p></td>
<td rowspan="2"><div style="line-height:0;"></div><p><strong>$ <?php echo number_format($dataArr['total_price'],2);?></strong></p></td>
</tr>
<tr>
<td style="text-align:center;line-height:1" colspan="4">US DOLLARS <?php echo numberTowords($dataArr['total_price']);?> ONLY</td>
</tr>
</table>
<br>
<br>
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
<tr>
     <td style="text-align:left;border:none;font-size:10px;">------------------------------<br/>Suppliers Signature</td>
     <td style="text-align:right;border:none;font-size:10px;">------------------------------<br/>For ONE NORTH SHIPS</td>
</tr>
<tr>
<td colspan="2" style="border:none;text-align:center;font-size:12px;">
<div style="height:20px;line-height:1"></div>“This document is computer generated and does not require any signature or the Company’s stamp in order to be considered valid”.
</td>
</tr>
</table>