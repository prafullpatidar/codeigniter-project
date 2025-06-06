<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="addEditstock" enctype="multipart/form-data" id="addEditstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="tableResponsive" class="sip-table" role="grid">
               <table class="table"  border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                <tr>
                  <th>Item No.</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>Past Qty</th>
                  <th>Type</th>
                  <th>Reason</th>    
                  <th>Qty</th>
                  <th>Updated Qty</th>        
                  </tr>
                </thead>
                    <tbody class="item_data">
                         <?php 
                       if(!empty($productArr)){
                         foreach ($productArr as $parent => $rows) {
                          foreach ($rows as $category => $products) {
                           $returnArr .= '<tr class="parent_row">
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                            <td role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>';
                              $returnArr .= '</tr>';
                               for ($i=0; $i <count($products) ; $i++) {
                                  $returnArr .= '<tr class="child_row">';
                                  $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                   $returnArr .= '<td width="17%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                   $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                   $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['past_qty'].'</td>';
                                   $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucwords(str_replace('_',' ',$products[$i]['type'])).'</td>';
                                   $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['reason'].'</td>';
                                   if($products[$i]['type']=='wrong_item'){
                                    $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">-</td>';
                                    $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">-</td>';

                                   }
                                   else{
                                     
                                     if($products[$i]['type']=='positive'){
                                       $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" style="color:green" aria-describedby="f2_key">+'.$products[$i]['qty'].'</td>';
                                       $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.($products[$i]['past_qty']+$products[$i]['qty']).'</td>';
                                    
                                     }
                                     else{
                                       $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" style="color:red" aria-describedby="f2_key">-'.$products[$i]['qty'].'</td>';
                                       $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.($products[$i]['past_qty']-$products[$i]['qty']).'</td>';
                                     }

                                   }

                                   $returnArr .= '</tr>';
                              }
                           }
                          }
                         }
                         echo $returnArr;
                         ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
