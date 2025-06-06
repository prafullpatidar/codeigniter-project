<?php 
 $monthNum  = $stock_value['month'];
 $dateObj   = DateTime::createFromFormat('!m', $monthNum);
 $monthName = $dateObj->format('F');
 $year = $stock_value['year'];

$ship_crew = $sing_overlap = $supernumeries = $owner = $charterers = $official = $superintendents = $other = $total_man_day = 0;

 if(!empty($extra_meals)){
    foreach ($extra_meals as $val) {
       $ship_crew += $val->ship_crew;
       $sing_overlap += $val->sing_b + $val->sing_l + $val->sing_d;
       $supernumeries += $val->numery_b + $val->numery_l + $val->numery_d;
       $owner += $val->owners_b + $val->owners_l + $val->owners_d;
       $charterers += $val->charterers_b + $val->charterers_l + $val->charterers_d;
       $official += $val->officials_b + $val->officials_l + $val->officials_d; 
       $superintendents += $val->superintendent_b + $val->superintendent_l + $val->superintendent_d;
       $other += $val->other_b + $val->other_l + $val->other_d;
    }
 }

 $total_man_day = $ship_crew + ($sing_overlap/3) + ($supernumeries/3) + ($owner/3) + ($charterers/3) + ($official/3) + ($superintendents/3) + ($other/3);

?>  
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div >
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="summary_report_form" enctype="multipart/form-data" id="summary_report_form" method="post">
                    <div class="form-body">
                    <div class="mb-10 row no-gutter" style="border-bottom: 2px solid #000;padding-bottom:10px">
                        <div class="col-sm-2">
                        <img src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="60">
                        </div>
                        <div class="col-sm-8 text-center">
                          <h4 class="mb-0 mt-2"><strong>One North Victualing Summary</strong></h4>
                        </div>
                        <div class="col-sm-2"></div>
                      </div>
                      <p class="mb-25 mt-30">Please enter all relevant purchases, (charged & cash) and ensure copies of invoices are attached.</p>
                     <div id="abc" class="sip-table" role="grid">
                     <table class="table header-fixed-new table-text-ellipsisz table-layout-fixed">
                        <thead class="t-header">
                        <tr>
                            <th width="30%">Port</th>
                            <th width="10%">Date</th>
                            <th width="30%">Supplier</th>
                            <th width="10%">Local Currency</th>
                            <th width="20%">Amount($)</th>
                          </tr>
                          </thead>
                          <tbody>
                           <?php
                            $sopdm = 0;
                            if(!empty($ship_stock)){
                             foreach ($ship_stock as $row) {
                                  ?>  
                              <tr>
                                  <td  width="30%" class='text-blue'><?php echo ucwords($row->delivery_port);?></td>
                                  <td  width="10%"><?php echo convertDate($row->delivery_date,'','d-m-Y')?></td>
                                  <td  width="30%">One North</td>
                                  <td  width="10%">USD</td>
                                  <td  width="20%" class='text-blue'>$ <?php echo number_format($row->total_price,2);?></td>
                              </tr>
                              <?php
                              $sopdm += $row->total_price; 
                              }   
                            }
                            else{
                              ?>
                              <tr><td align="center" style="font-size:12px" colspan="5"><strong>No Data Available</strong></td></tr>
                           <?php }
                           ?>  
                          <tr>
                            <td colspan="4"><h6>Value of Provisions R.O.B Opening Stock </h6><h6 style="color:blue"><?php echo $monthName.' '.$year;?></h6></td>
                            <td class='text-blue'>$ <?php echo number_format($stock_value['opening_price'],2);?></td> 
                            <input type="hidden" name="opening_stock" value="<?php echo $stock_value['opening_price'];?>"> 
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Value of Provisions Received during the month</h6></td>
                            <td class='text-blue'>$ <?php echo number_format($sopdm,2);?></td> 
                             <input type="hidden" name="received_provision" value="<?php echo $sopdm;?>">
                          </tr>
                           <?php 
                             $condemned_value = $condemned_stock[0]->total_amount;
                           ?>
                          <tr>
                            <td colspan="4"><h6>Value of Provision Condemned / Discarded</h6></td>
                            <td class='text-blue'>$ <?php echo number_format($condemned_value,2)?></td>
                            <input type="hidden" name="condemned" value="<?php echo $condemned_value;?>">  
                          </tr> 
                           <tr>
                            <td colspan="4"><h6>Value of Provisions  Remaining on Board as on</h6><h6 style="color:blue"><?php echo $monthName.' '.$year;?></h6></td>
                            <td class='text-blue fw-700'>$ <?php echo number_format($stock_value['closing_price'],2);?></td>  
                            <input type="hidden" name="ramaing_on_board" value="<?php echo $stock_value['closing_price'];?>">
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Value of Provisions consumed this month</h6></td>
                            <?php
                            $consumed_this_month = ($stock_value['opening_price']+$sopdm) - $condemned_value - $stock_value['closing_price']; 
                            ?>
                            <td class='text-blue fw-700'>$ <?php echo number_format($consumed_this_month,2);?></td>  
                            <input type="hidden" name="consumed" value="<?php echo $consumed_this_month;?>">
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Total Man Days (including extra meals)</h6></td>
                            <td class='text-blue'><?php echo number_format($total_man_day,2)?></td> 
                            <input type="hidden" name="total_man_days" value="<?php echo $extra_meals[0]->total_man_days;?>"> 
                          </tr>
                           <tr>
                            <?php 
                              $daily_rate = ($total_man_day) ? $consumed_this_month / $total_man_day : 0;
                            ?>
                            <td colspan="4"><h6>Daily rate per Man</h6></td>
                            <td><?php echo number_format($daily_rate,2);?></td>   <input type="hidden" name="daily_rate_per_man" value="<?php echo $daily_rate;?>">
                          </tr>
                          <tr>
                            <td colspan="4"><h6>CONSUMED FOR</h6></td>
                            <td class="fw-700">MAN-DAYS *</td>  
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Ship's Crew</h6></td>
                            <td><?php echo number_format($ship_crew,2);?></td>
                            <input type="hidden" name="ship_crew" value="<?php echo number_format($ship_crew,2);?>">  
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Sign On/Off Overlap</h6></td>
                            <td><?php echo number_format($sing_overlap/3,2);?></td> 
                             <input type="hidden" name="overlap" value="<?php echo number_format($sing_overlap/3,2);?>"> 
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Supernumeries</h6></td>
                            <td><?php echo number_format($supernumeries/3,2);?></td>  
                             <input type="hidden" name="supernumeries" value="<?php echo number_format($supernumeries/3,2);?>"> 

                          </tr>
                          <tr>
                            <td colspan="4"><h6>Pilots, Stevedores, Port Captains, etc,. - Owners'</h6></td>
                            <td><?php echo number_format($owner/3,2);?></td>  
                             <input type="hidden" name="owners" value="<?php echo number_format($owners/3,2);?>"> 
                          
                          </tr>
                          <tr>
                            <td colspan="4"><h6>Pilots, Stevedores, Port Captains, etc,. - Charterers'</h6></td>
                            <td><?php echo number_format($charterers/3,2);?></td>  
                             <input type="hidden" name="charterers" value="<?php echo number_format($charterers/3,2);?>"> 

                          </tr>
                          <tr>
                            <td colspan="4"><h6>Port Official</h6></td>
                            <td><?php echo number_format($official/3,2);?></td>  
                             <input type="hidden" name="official" value="<?php echo number_format($official/3,2);?>"> 

                          </tr>
                          <tr>
                            <td colspan="4"><h6>Superintendents</h6></td>
                            <td><?php echo number_format($superintendents/3,2);?></td> 
                             <input type="hidden" name="superintendents" value="<?php echo number_format($superintendents/3,2);?>"> 

                          </tr>
                          <tr>
                            <td colspan="4"><h6>Others (* workshop / riding squad / IT engineers)</h6></td>
                            <td><?php echo number_format($other/3,2)?></td>
                             <input type="hidden" name="other" value="<?php echo number_format($other/3,2);?>"> 

                          </tr>
                          <tr>
                            <td colspan="4"><h6>Charterer’s Victualing Rate - <span class='text-blue fw-700'>$</span></h6></td>
                            <td></td>  

                          </tr>
                          <tr>
                           <td colspan="4"><br/></td>
                           <td></td>  
                          </tr>
                          <tr>
                             <td colspan="4" align="center"><h6>Total Man-days</h6></td>
                            <td class='fw-700'><?php echo number_format($total_man_day,2);?></td>
                             <input type="hidden" name="final_man_days" value="<?php echo number_format($total_man_day,2);?>"> 
                          </tr>
                          </tbody>
                      </table>
                  </div>
                  <input type="hidden" name="id" value="<?php echo $ship_id;?>">
                  <input type="hidden" name="actionType" value="save">
                    </div>
                    <?php 
                    if(empty($dataArr['second_id'])){
                    ?>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('summary_report_form','shipping/add_victualing_report','98%','victualling_summary');" class="btn btn-success btn-slideright mr-5">Save</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                  <?php } ?>
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>