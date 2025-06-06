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
     $total_price = 0;
     foreach($data as $v){
         $productArr[$v->parent_category_name][$v->category_name][] = $v;
         $total_price = $total_price+($v->delivery_price);
        }
     $currency = $productArr[$v->parent_category_name][$v->category_name][0]->currency;
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
?>
<body style="background: red;font-family: Arial, Helvetica, sans-serif;">
        <div class="page-header text-blue-d2">
            <img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="100" height="100">
        </div>
        <div class="text-center" style="text-align:center">
            <h2 style="color:blue">ONE NORTH SHIPS</h2>
            <p>OFFICE: - PO Box 79998, Dubai UAE <br/>
                +971-509 176 955/ +91-9810143870 <br/>
                Email: info@onenorthships.com/purchase@onenorthships.com <br/>
            </p>
        </div>
        <h1 style="text-align:center">Invoice</h1>

        <table width="100%" border="1">
          <tr>
            <td colspan="4">Invoice To:</td>
            <td colspan="3" rowspan="2">INVOICE</td>
          </tr>
          <tr>
            <td colspan="4" rowspan="3"><?php echo $productArr[$v->parent_category_name][$v->category_name][0]->vendor_name;?></td>
          </tr>
          <tr>
            <td>Invoice Number</td>
            <td>Invoice Date </td>
            <td>Payment Due On</td>
          </tr>
          <tr>
            <td><?php echo $productArr[$v->parent_category_name][$v->category_name][0]->invoice_no;?></td>
            <td><?php echo date('d-m-Y',strtotime($productArr[$v->parent_category_name][$v->category_name][0]->created_at))?> </td>
            <td>-</td>
          </tr>
          <tr>
            <td>PO DATE</td>
            <td>Vessel</td>
            <td>Port of<br />
            Delivery</td>
            <td>Type of<br />
            Requisition</td>
            <td colspan="2">&nbsp;</td>
            <td>Remarks</td>
          </tr>
          <tr>
            <td><?php echo date('d-m-Y',strtotime($productArr[$v->parent_category_name][$v->category_name][0]->order_date))?></td>
            <td><?php echo $productArr[$v->parent_category_name][$v->category_name][0]->ship_name;?></td>
            <td><?php echo $productArr[$v->parent_category_name][$v->category_name][0]->port;?></td>
            <td>Provisions</td>
            <td colspan="2">As per below description</td>
            <td>PROVISIONS SUPPLY FOR<br />
            VESSEL USE.</td>
          </tr>
          <tr>
            <td>S.no.</td>
            <td colspan="3">Description</td>
            <td>Quantity</td>
            <td>Unit Price (<?php echo $curr;?>)</td>
            <td>Price (<?php echo $curr;?>)</td>
          </tr>
          <?php 
          foreach($productArr as $parent => $rows){
            foreach($rows as $category => $products){
                $i=1;
               foreach($products as $product){?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td colspan="3"><?php echo $product->product_name;?></td>
                    <td><?php echo $product->quantity;?></td>
                    <td><?php echo $product->delivery_price/$product->quantity;?></td>
                    <td colspan="2"><?php echo $product->delivery_price;?></td>
                    <td><?php echo $i;?></td>
                  </tr>
          <?php } $i++;} }?>
          <tr>
              <td colspan="6">Discount %</td>
              <td colspan="6"><?php echo $productArr[$v->parent_category_name][$v->category_name][0]->invoice_discount;?>%</td>
          </tr>
          <tr>
            <td colspan="4">Amount in words</td>
            <td colspan="2" rowspan="2">Net total amount in</td>
            <td rowspan="2"><?php echo ($total_price - ($total_price*$productArr[$v->parent_category_name][$v->category_name][0]->invoice_discount/100));?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo numberTowords(($total_price - ($total_price*$productArr[$v->parent_category_name][$v->category_name][0]->invoice_discount/100)),$curr).' Only';?></td>
            <!-- <td>&nbsp;</td> -->
          </tr>
        </table><br/><br/>
        <table width="100%" style="width: 100%;">
            <tr>
               <td style="width:80%">_______________</td> 
               <td style="width:30%">_______________</td> 
            </tr>
            <tr>
               <td style="width:80%">Supplier's Signature</td> 
               <td style="width:30%">For ONE NORTH SHIPS</td> 
            </tr>
            <p>“This document is computer generated and does not require any signature or the Company’s stamp in order to be considered valid”. </p>
        </table>
</body>
</html>