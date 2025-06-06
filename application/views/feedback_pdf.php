<!DOCTYPE html>
<html lang="en">
<head>
    <title>Feedback</title>
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
<body style="background: ;font-family: Arial, Helvetica, sans-serif;">
<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
<tr>
  <td width="10%" style="border:none;"><img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="100" height="100"></td>
  <td width="70%" style="border:none;">
  <h1 style="margin-top:10pt;font-size:24px;text-align:center;color:#4a206a;">Feedback</h1>
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
  </tr>
  </table>
<div style="line-height:0;border-top:2px solid #4a206a"></div>
<div>
Dear Master, Chief steward, or Chief Cook,<br><br>
You have received Provisions from One North Ships.<br>
We would like you to help in increasing the efficiency of our services and products, so kindly access the quality of the items delivered and let us know the areas of improvements.
<br><br>
Vessel: <?= $data['ship_name']?><br>
Imo No. <?= $data['imo_no']?><br>
Date of Supply: <?= convertDate($data['delivery_date'],'','d-m-Y')?><br>
PO No. <?= $data['po_no'] ?><br>
Suppliers: <?= $data['agent_name'] ?><br>
Port of Delivery: <?= $data['delivery_port'] ?><br>
<br>
Kindly deliver your considerable suggestion on following:
</div>
<div style="line-height:20px"></div>
<b>Please indicate below: (5 = Very good, 4 = Good, 3 = Average, 2 = Bad 1 = Very bad)</b>
<br><br>
<div style="line-height:0;border-top:2px solid #4a206a"></div>

<table Cellpadding="5" width="100%" style="table-layout: auto;border-collapse: collapse;">
  <tr>
    <td>Quality of Fresh Provision</td>
    <td><?= $data['fresh_provision'] ?></td>
  </tr>
   <tr>
    <td>Quality of Dry & Frozen Provision</td>
    <td><?= $data['dry_provision'] ?></td>
  </tr>
  <tr>
    <td>Quality of Packing/Marking Provision</td>
    <td><?= $data['marking_provision'] ?></td>
  </tr>
  <tr>
    <td>Suppliers/representatives appearance onboard</td>
    <td><?= $data['supplier_onboard'] ?></td>
  </tr>
  <tr>
    <td>Overall Performance</td>
    <td><?= $data['overall_performance'] ?></td>
  </tr>
</table><br/>
<br>
<label>Comment :</label>
<p><?= $data['comment'] ?></p>
</div>
</body>
</html>