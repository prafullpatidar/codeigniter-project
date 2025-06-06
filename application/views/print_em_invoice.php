<table width="100%" border="1">
<tr>
<td colspan="2"><img src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="40"></td>
<td colspan="5"><h2> ONE NORTH SHIPS</h2>
<p>PO BOX 79998, DUBAI UAE<br />
<!-- Tel.: +91-9810143870<br /> -->
Email: info@onenorthships.com/catering@onenorthships.com</p></td>
</tr>
<tr>
<td colspan="4"><strong>Invoice To:</strong></td>
<td colspan="3" rowspan="2"><strong>INVOICE</strong></td>
</tr>
<tr>
<td colspan="4" rowspan="3">The Owner/Master (<strong><?php echo ucwords($dataArr['ship_name']);?></strong>)<br />
<strong>C/O <?php echo ucwords($dataArr['company_name']);?>,</strong><br/>
<?php echo ucwords($dataArr['company_address']);?><br>
Tel: <?php echo $dataArr['company_phone'];?></td>
</tr>
<tr>
<td><strong>Invoice Number</strong></td>
<td><strong>Invoice Date</strong></td>
<td><strong>Payment Due On</strong></td>
</tr>
<tr>
<td><strong>INV/ONS/CAT/<?php echo date('m/Y')?>/<?php echo ucwords($dataArr['ship_name']);?></strong></td>
<td><?php echo convertDate($dataArr['added_on'],'','d-m-Y');?></td>
<td><?php echo convertDate($dataArr['due_date'],'','d-m-Y');?></td>
</tr>
<tr>
<td><strong>Billed Month</strong></td>
<td><strong>Vessel</strong></td>
<td>-</td>
<td><strong>Type of Requisition</strong></td>
<td colspan="2"></td>
<td><strong>Remarks</strong></td>
</tr>
<tr>
<td><strong><?php echo ConvertDate($dataArr['month'],'','M Y');?></strong></td>
<td><strong><?php echo ucwords($dataArr['ship_name']);?></strong></td>
<td>-</td>
<td><strong>Provisions</strong></td>
<td colspan="2">As per below description</td>
<td>PROVISIONS SUPPLY FOR VESSEL USE.</td>
</tr>
<tr>
<td><strong>S.no.</strong></td>
<td colspan="3"><strong>Description</strong></td>
<td><strong>Quantity</strong></td>
<td><strong>Unit Price (USD)</strong></td>
<td><strong>Price (USD)</strong></td>
</tr>
<tr>
<td>1</td>
<td colspan="3"><?php echo ucwords($dataArr['ship_name']);?> Monthly Victualling Cost<br><?php echo $dataArr['victualling_rate'];?> USD per person per day as per Contract</td>
<td colspan="2"><?php echo $dataArr['total_man_days'];?> Man Days @ <?php echo $dataArr['victualling_rate'];?> USD</td>
<td><?php echo ($dataArr['total_man_days'] * $dataArr['victualling_rate'])?></td>
</tr>
</tr>
<tr>
<td colspan="4"><strong>Amount in words</strong></td>
<td colspan="2" rowspan="2"><strong>Net total amount in USD</strong></td>
<td rowspan="2"><p><strong>$ <?php echo ($dataArr['total_man_days'] * $dataArr['victualling_rate'])?></strong></p></td>
</tr>
<tr>
<td colspan="4">US DOLLARS <?php echo numberTowords($dataArr['total_man_days'] * $dataArr['victualling_rate'],'USD');?> ONLY</td>
</tr>
 </table>
 </div>
 </div>
    </div>
  </div>
</div>
</body>
</html>
