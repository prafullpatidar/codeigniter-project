<?php 
 $show_payment_term = checkLabelByTask('show_payment_term');
?>
<style type="text/css">
body {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
}

#image-container {
    position: relative;
}

#image-container img {
    max-width: 100%;
    max-height: 100%;
}

#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 999;
}

#thumbnail-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 0px;
}

.thumbnail {
    max-width: 100px;
    cursor: pointer;
    margin: 0 10px;
}
</style>
<div class="viewRcpt double-table">
<div class="row flex-row">
<div><img class="" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="50" style="margin-left:10px;"></div>
<div style="width:100%" class="text-center"><h2 class="rcptTitle text-center">Delivery Note </h2></div>

    <div id="overlay" align="center">
      <div id="image-container">
        <img src="sample.jpg" alt="Sample Image">
    </div>
    </div>

  </div>

<table width="100%" border="1">
  <tr>
    <td colspan="3" rowspan="3">
      <h3>One North Ships</h3><p>
      Connecting the World <br />
      info@onenorthships.com / catering@onenorthships.com</p></td>
    <td><strong>Date:</strong></td>
    <td colspan="2"><?php echo ConvertDate($dataArr['date'],'','d-m-y');?></td>
  </tr>
  <tr>
    <td><strong>Delivery Note No #:</strong></td>
    <td colspan="2"><?php echo $dataArr['note_no'];?></td>
  </tr>
  <tr>
    <td><strong>Customer ID:</strong></td>
    <td colspan="2"><?php echo $dataArr['customer_id'];?></td>
  </tr>
  <tr>
    <td colspan="3">To: To Owner/Master of <?php echo ucwords($dataArr['ship_name']);?></td>
    <td><strong>Ship to:</strong></td>
    <td colspan="2"><strong>Vessel</strong><br />
      <?php echo ucwords($dataArr['ship_name']).', IMO NO. '.$dataArr['imo_no'];?><br />
      FOB <?php echo $dataArr['delivery_port'];?><br />
      PO : <?php echo $dataArr['po_no'];?></td>
  </tr>

</table>
<br />
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
<table width="100%" border="1">
 <tr style="background:#f7f7f7;">
    <td><strong>Reqsn Date</strong></td>
    <td><strong>Delivery Port</strong></td>
    <?php
    if($show_payment_term){ 
    ?>
    <td><strong>Payment Terms</strong>
    </td>
  <?php } ?>
    <td><strong>Currency</strong></td>
  </tr>
  <tr>
    <td><?php echo ConvertDate($dataArr['reqsn_date'],'','d-m-Y');?></td>
    <td><?php echo $dataArr['delivery_port'];?></td>
    <?php
    if($show_payment_term){ 
    ?>
    <td><?php echo $dataArr['payment_term'];?></td>
  <?php } ?>  
    <td><?php echo $curr;?></td>
  </tr>
  </table>

<br />
<table class="table">
<tr>
    <td colspan="7" align="center"><strong><?php echo ucwords(str_replace('_',' ',$dataArr['requisition_type']));?></strong></td>
  </tr>
</table>
<div class="sip-table">
<table class="header-fixed-new table-text-ellipsis table-layout-fixed table">
 <thead class="t-header">
  <tr>
    <th width="10%"><strong>Item No</strong></th>
    <th width="40%"><strong>Description</strong></th>
    <th width="10%"><strong>Quantity</strong></th>
    <th width="10%"><strong>Unit</strong></th>
    <th width="20%"><strong>Comment</strong></th>
    <th width="10%">&nbsp;</th>
  </tr>
</thead>
  <?php 
   if(!empty($productArr)){
                  foreach ($productArr as $parent => $rows) {
                    foreach($rows as $category => $products){
                       $returnArr .= '<tr class="parent_row">
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="40%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>';
                     for ($i=0; $i <count($products) ; $i++) { 
                        $product_id = $products[$i]['product_id'];
                       $returnArr .= '<tr class="child_row">';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                       $returnArr .= '<td width="40%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                       $returnArr .= '<td width="10" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                       
                       $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucwords(str_replace('_',' ', $products[$i]['type'])).'</td>';
                        
                       if($products[$i]['type']=='short_supply' || $products[$i]['type']=='wrong_supply'){
                         $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.((is_int($products[$i]['supply_qty'])) ? number_format($products[$i]['supply_qty'],2) : $products[$i]['supply_qty']).'</td>';
                       }
                       else if($products[$i]['type']=='damange_and_spoil' || $products[$i]['type']=='poor_quality'){
                         $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><div id="thumbnail-container"><img class="thumbnail" alt="Sample Image" src="'.base_url().'uploads/delivery_receipt/'.$products[$i]['img_url'].'"></div></td>';
                       }
                       else if($products[$i]['type']=='other'){
                         $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['comment'].'</td>';

                       }
                       else{
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                       }
                        $returnArr .='</tr>'; 
                     }
                  }
                }
            }
            echo $returnArr;
  ?>
</table>
<p>Signature:  <img src="<?php echo base_url()?>uploads/e_signature/<?php echo $dataArr['e_sign'];?>" height="40" width="40"></p>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    // Function to show the full image
    function showImage(src) {
        $('#image-container img').attr('src', src);
        $('#overlay').fadeIn();
    }

    // Close the full image view
    $('#overlay').click(function() {
        $('#overlay').fadeOut();
    });

    // Click event for each thumbnail
    $('.thumbnail').click(function() {
        var src = $(this).attr('src');
        showImage(src);
    });
});

</script>
