<div style="max-width: 2480px ;font-family: Open Sans sans-serif;">
 <table style="width: 100%; border-spacing: 0; border-collapse: collapse;    border-bottom: 2px solid #000;
    padding-bottom: 10px;">
        <tbody>
          <tr>
             <td width="60" style="padding: 15px 0"><img class="brand_logo" src="<?php echo base_url().'/assets/images/company_logo.png';?>"
                        alt="brand logo" width="60" ></td>
            <td width="770" style="vertical-align: center; text-align: center;color: #636e7b;padding: 15px 0"><br><br><br>
              <span style=" font-size:18px; font-weight:700;">One North Condemned
                Stock Report</span><br>
                </td>
            </tr>     
        </tbody>
    </table>
    <table width="100%">
        <tbody>
            <tr>
                <td width="50%" style="padding: 15px 0">
                    <strong>Vessel Name: </strong> <span style="color: #0000FF;"><?php echo ucwords($dataArr['ship_name']);?></span>
                </td>
                <td width="50%" style="padding: 15px 0">
                    <strong>Month: </strong> <span style="color: #0000FF;"><?php
                    $dateObj   = DateTime::createFromFormat('!m', $dataArr['month']);
                     $monthName = $dateObj->format('F');
                     echo $monthName;?></span>
                </td>
            </tr>
        </tbody>

    </table>
    <p style="padding-bottom: 25px">
        Please list below any stores condemned during the month, give quantity, item and cost, together with reason.
    </p>
    <table width="100%" border="1" cellpadding="10" style="width: 100%; border-spacing: 0;border-collapse: collapse;">
        <thead>
            <tr>
                <th>Quantity</th>
                <th>Item</th>
                <th>Cost</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
             <?php 
              $total = 0;
              if(!empty($productArr)){
                for ($i=0; $i <count($productArr) ; $i++) { 
                  $total += $productArr[$i]['cost'];
                  ?>
                  <tr>
                      <td><?php echo $productArr[$i]['quantity'];?></td>
                      <td><?php echo ucfirst($productArr[$i]['product_name']);?></td>
                      <td><?php echo number_format($productArr[$i]['cost'],2)?></td>
                      <td><?php echo $productArr[$i]['reason']?></td>
                  </tr>
                  <?php
                }
              }   
             ?>
            <tr class="child_row">
                <td  style="text-align: right;font-weight: 700"
                    colspan="2">Total Amount ($)</td>
                <td  style="text-align: left;font-weight: 700"
                    colspan="2"><span class="text-blue"><?php echo number_format($total,2)?></span>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" style="margin: 15px 0;border: none;">
        <tbody>
            <tr>
                <td width="19%" style="text-align: right;">
                    Master:</td>
                <td width="81%" 
                    style="text-align: left;padding:0 5px"><span class="text-blue"><?php echo ucwords($dataArr['captain_user_id']);?></span></td>
            </tr>
            <tr>
                <td width="19%" style="text-align: right;">
                    Cook/Steward:</td>
                <td width="81%" 
                    style="text-align: left;padding:0 5px"><span class="text-blue"><?php echo ucwords($dataArr['cook_user_id']);?></span> </td>
            </tr>
            <tr>
                <td width="19%" style="text-align: right;">
                    Witness Officer (Rank):</td>
                <td width="81%" 
                    style="text-align: left;padding:0 5px"><span class="text-blue"><?php echo ucwords($dataArr['witness_officer_rank']);?></span></td>
            </tr>
            <tr>
                <td width="19%" style="text-align: right;">Date:
                </td>
                <td width="81%" 
                    style="text-align: left;padding:0 5px"><span class="text-blue"><?php echo convertDate($dataArr['created_date'],'','d-m-y');?></span></td>
            </tr>
        </tbody>
    </table>
</div>