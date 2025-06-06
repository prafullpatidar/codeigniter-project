<style>
td, th { padding:5px;}
</style>
<body>
<form class="form-horizontal form-bordered" name="invoice_form" enctype="multipart/form-data" id="invoice_form" method="post">

<div class="new-invoice-header">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
<td valign="top" width="110" height="100"><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="110" height="110"></td>
<td width="770" align="center" valign="bottom"><br><br><br><br><span style="color:#00afef; font-size:35px; font-weight:bold; margin-bottom:0; padding-bottom:0;">ONE NORTH SHIPS</span><br>
<span>PO BOX 79998, DUBAI UAE</span><br>
<!-- <span>Tel.: +91-9810143870</span><br> -->
<span>Email: info@onenorthships.com/catering@onenorthships.com</span><br><br>
<span style="color:#ffc000; font-size:26px; font-weight:bold; margin-bottom:0; padding-bottom:0;">INVOICE</span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="100%" border="1" cellpadding="5" cellpadding="0">
<!--<tr>
<td colspan="2" style="text-align: center;"><img src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="100" height="100"></td>
<td colspan="5" style="text-align: center;"><h2> ONE NORTH SHIPS</h2>
<p>PO BOX 79998, DUBAI UAE<br />
Tel.: +91-9810143870<br />
Email: info@onenorthships.com/catering </p></td>
</tr>-->
<tr>
<td colspan="4" width="44%"><strong>Invoice To:</strong></td>
<td colspan="3" rowspan="2" width="56%" height="100" align="center" valign="middle"><strong>INVOICE</strong></td>
</tr>
<tr>
<td valign="top" colspan="4" rowspan="3">The Owner/Master (<strong><?php echo ucwords($dataArr['ship_name']);?></strong>)<br />
<strong>C/O <?php echo ucwords($dataArr['company_name']);?>,</strong><br/>
<?php echo ucwords($dataArr['company_address']);?><br>
Tel: <?php echo $dataArr['company_phone'];?></td>
</tr>
<tr>
<td align="center"><strong>Invoice Number</strong></td>
<td align="center"><strong>Invoice Date</strong></td>
<td align="center"><strong>Payment Due On</strong></td>
</tr>
<tr>
<td align="center" valign="middle"><span style="font-size: 10px; line-height: normal; display: inline-block;">INV/ONS/CAT/<?php echo date('m/Y')?>/<?php echo ucwords($dataArr['ship_name']);?></span></td>
<?php if($type=="view"){?>
<td align="center" valign="middle"><?php echo ConvertDate($dataArr['added_on'],'','d-m-Y');?></td>
<td align="center" valign="middle"><?php echo ConvertDate($dataArr['due_date'],'','d-m-Y');?></td>

<?php }else{
?>
<td align="center" valign="middle"><?php echo date('d-m-Y')?></td>
<td align="center" valign="middle"><?php echo date('d-m-Y', strtotime("+30 days"));
?></td>
<?php }
?>
</tr>
<tr>
<td align="center" valign="middle"><strong>Billed Month</strong></td>
<td align="center" valign="middle"><strong>Vessel</strong></td>
<td align="center" valign="middle">-</td>
<td align="center" valign="middle"><strong>Type of Requisition</strong></td>
<td align="center" valign="middle" colspan="2"></td>
<td align="center" valign="middle"><strong>Remarks</strong></td>
</tr>
<tr>
<?php if($type=="view"){?>
<td><?php echo ConvertDate($dataArr['month'],'','M Y');?></td>
<?php }else{
	?>
<td align="center"><?php echo date('M Y');?></td>
<?php } ?>	
<td><?php echo ucwords($dataArr['ship_name']);?></td>
<td>-</td>
<td align="center"><strong>Provisions</strong></td>
<td colspan="2" align="center">As per below description</td>
<td align="center">PROVISIONS SUPPLY FOR VESSEL USE.</td>
</tr>
<tr>
<td align="center"><strong>S.no.</strong></td>
<td align="center" colspan="3"><strong>Description</strong></td>
<td align="center"><strong>Quantity</strong></td>
<td align="center"><strong>Unit Price (USD)</strong></td>
<td align="center"><strong>Price (USD)</strong></td>
</tr>
<tr>
<td align="center">1</td>
<td colspan="3"><?php echo ucwords($dataArr['ship_name']);?> Monthly Victualling Cost<br><?php echo $dataArr['victualling_rate'];?> USD per person per day as per Contract</td>
<td colspan="2" align="center"><?php echo $dataArr['total_man_days'];?> Man Days @ <?php echo $dataArr['victualling_rate'];?> USD</td>
<td align="center" valign="middle"><?php echo ($dataArr['total_man_days'] * $dataArr['victualling_rate'])?></td>
</tr>

<tr>
<td colspan="4" align="center"><strong>Amount in words</strong></td>
<td colspan="2" rowspan="2" align="center"><strong>Net total amount in USD</strong></td>
<td rowspan="2" align="center"><p><strong>$ <?php echo ($dataArr['total_man_days'] * $dataArr['victualling_rate'])?></strong></p></td>
</tr>
<tr>
<td colspan="4" align="center">US DOLLARS <?php echo numberTowords($dataArr['total_man_days'] * $dataArr['victualling_rate'],'USD');?> ONLY</td>
</tr>

 </table>
</td>
</tr>
<tr>
<td>
<table cellpadding="20" cellspacing="0" border="0" width="100%">
<tr>
               <td align="left">....................................................<br><span style="color:#868686; font-size:12px;">Supplier's Signature</span></td> 
               <td align="right">....................................................<br><span style="color:#868686; font-size:12px;">For ONE NORTH SHIPS</span></td> 
</tr>
</table>
</td>               
            </tr>
            <tr><td colspan="2"><p>“This document is computer generated and does not require any signature or the Company’s stamp in order to be considered valid”. </p></td></tr>	
</table>
 
 </div>
 </div>
            <input type="hidden" name="actionType" value="save">
            <input type="hidden" name="id" value="<?php echo $dataArr['extra_meal_id'];?>">

            <div class="text-right">
            <?php if($type=="view"){?>
            	<a href="<?php echo base_url().'/shipping/printMonthInvoicePdf/'.base64_encode($dataArr['extra_meal_id']);?>" target="_blank" class="btn btn-success btn-slideright mr-5">Print PDF</a>
            <?php } else{
            	?>
                <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
               <button type="button" onClick="submitAjax360Form('invoice_form','shipping/extra_meals_invoice','98%','extra_meals_html');" class="btn btn-success btn-slideright mr-5">Submit</button>
             <?php
            }
            ?>
           </div>

            </div>
          </div>
      </div>
</body>
</html>
