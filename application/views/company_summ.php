<style type="text/css">
  tr.products td:first-child {
padding-left:40px !important
}
</style>
<?php
$sessionData = getSessionData();
$edit_ship = checkLabelByTask('edit_ship');

$adjust_inventory = checkLabelByTask('adjust_inventory');

if(!$edit_ship){
   $shipNameDisable = 'disabled';
   $imoDisable = 'disabled';
   $crewDisable = 'disabled';
   $captainDisable = 'disabled';
   $cookDisable = 'disabled';
   $c_nationalityDisable = 'disabled';
   $cook_nationalityDisable = 'disabled';
   $tradingAreaDisable = 'disabled';
   $agreedDisable = 'disabled';
}

 ?>
       <!-- <div class="row fl-h100" > -->
       <div class="row " >
          <div class="col-md-8">
            <div class="card">
              <div class="card-header pt-0 pb-0">
                  <div class="row d-flex align-center">
                  <div class="col-md-4">Available Stock <?php echo ($adjust_inventory) ? '<a title="Adjust Stock"  href="javascript:void(0)" style="color: white;margin-left:7px;" onclick="showAjaxModel(\'Adjust Current Stock\',\'shipping/adjustCurrentStock\',\'\',\'\',\'98%\',\'full-width-model\')"><i class="fa fa-pencil-square-o"></i></a>' : '' ?></div>
                    <div class="col-md-8">
                    <form name="current_stock_form" method="post" id="current_stock_form" action="<?php echo base_url().'shipping/getShipCurrentStock'?>">
                  <div class="pull-right">
                    <ul class="leadHeader hideBottomLine d-flex align-center">
                     <li><a href="javascript:void(0)" onclick="download_cs()" style="color: white;" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a></li>
                     <li>
                      <li><a href="javascript:void(0)" onclick="resetcs()" style="color: white;" title="Reset"><i class="fa fa-refresh" aria-hidden="true"></i></a></li>
                      <li>
                       <select class="form-control" name="year" id="cs_year" onchange="get_months()">
                        <option value="">Year</option>
                             <?php
                               foreach ($years as $row) {
                                $selected = (date('Y')==$row->year) ? 'selected' : '';
                              ?>
                               <option <?php echo $selected;?> value="<?php echo $row->year;?>"><?php echo $row->year;?></option>
                             <?php 
                             }
                           ?>  
                       </select>
                     </li>
                     <li>
                      <select class="form-control" name="month" id="cs_month" onchange="current_stock()">
                        <option value="">Month</option>
                      </select>
                     </li>
                     <li>
                       <select class="form-control" name="category_id" id="cs_category_id" onchange="current_stock()">
                        <option value="">Select Category</option>
                          <?php
                              if(!empty($products_category)){
                                    foreach ($products_category as $pc){
                                   $selected = (!empty($dataArr['product_category_id']) && $dataArr['product_category_id'] == $pc->product_category_id)?'selected':'';
                                    echo '<option '.$selected.' value="'.$pc->product_category_id.'">'.$pc->category_name.'</option>';    
                                    }   
                                }
                            ?>  
                       </select>
                     </li>
                    <li><input type="text" name="keyword" id="cs_keyword" placeholder="Search" onchange="current_stock()" class="form-control" title="Search By Product Name" autocomplete="off"></li>
                    <input type="hidden" name="download" id="cs_download" value="0">
                    </ul>
                  </div>
                  </form>
                    </div>
                  </div>
                </div>
              <div class="card-body">
                <div class="row">
                  
                  <div class="col-md-12">
                  <div class="table-responsive tr-table">
                  <table class="table flex-table">
                      <thead>
                        <tr>
                          <th style="width:50%;flex:0 0 50%">Description</th>
                          <th width="25%" >Unit</th>
                          <th width="25%">QTY</th>
                          <th width="25%">Unit Price(AVG)</th>                    
                        </tr>
                      </thead>
                      <tbody class="current_stock_items">
                        
                      </tbody>
                  </table>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-0">
          
              <h5 class="card-header has-right-icons d-flex align-center">Vessel Details &nbsp;
                <!-- <a href=""><i class="fa fa-pencil" aria-hidden="true"></i> 
              </a>-->
              <!-- <div class="icons">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <i class="far fa-bell" aria-hidden="true"></i>

              </div> -->
              </h5>
            
                
              
        <div class="card-body table-responsive tr-table" id="ship_details">
        <?php 
        if(!empty($ship_details)){
        ?>
        <form id="ship_details_form" name="ship_details_form" method="POST">
          <div class="form-group row">
            <label class="col-sm-5 col-form-label">Name <span>*</span></label>
            <div class="col-sm-7">
            <input class="form-control" type="text" name="ship_name" id="ship_name" value="<?php echo ucfirst($ship_details['ship_name'])?>" <?php echo $shipNameDisable;?>>
            <span id="name"></span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-5 col-form-label">IMO <span>*</span></label>
            <div class="col-sm-7">
            <input class="form-control" type="number" name="imo_no" id="imo_no" value="<?php echo ucfirst($ship_details['imo_no'])?>" <?php echo $imoDisable;?>>
            <span id="imo"></span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-5 col-form-label">Captain name </label>
            <div class="col-sm-7"> 
              <select class="form-control" name="captain_user_id" id="captain_user_id" <?php echo $captainDisable;?> onchange="getNationality(this.value,'captain')">
                  <option value="">Select</option>
                  <?php 
                      if(!empty($captain)){
                        foreach ($captain as $row) {
                            $select = ($ship_details['captain_user_id'] == $row->user_id) ? 'selected="selected"' : '';
                           // $captain_disabled = (in_array($row->user_id,$assignedCaptains))?'disabled':'';

                           echo  '<option '.$select.' value="'.$row->user_id.' "'.$captain_disabled.'>'.ucwords($row->name).'</option>';
                         } 
                      }
                  ?>
              </select>  
            <span id="captain_id"></span>

            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-5 col-form-label">Captain Nationality</label>
            <div class="col-sm-7"> 
             <input class="form-control" type="text" name="captain_nationality" id="captain_nationality" value="<?php echo ucfirst($ship_details['captain_nationality'])?>" <?php echo $c_nationalityDisable;?>>        
            </div>
            <span id="ct_nationality"></span>

          </div>
            <div class="form-group row">
            <label class="col-sm-5 col-form-label">Cook Name</label>
            <div class="col-sm-7"> 
               <select class="form-control" name="cook_user_id" id="cook_user_id" <?php echo $cookDisable;?> onchange="getNationality(this.value,'cook')">
                  <option value="">Select</option>
                  <?php
                   if(!empty($cook)){
                        foreach ($cook as $row1) {
                            $selected = ($ship_details['cook_user_id'] == $row1->user_id) ? 'selected="selected"' : '';
                            // $cook_disabled = (in_array($row1->user_id,$assignedCooks))?'disabled':'';
                            echo  '<option '.$selected.' value="'.$row1->user_id.' "'.$cook_disabled.'>'.ucwords($row1->name).'</option>';
                         } 
                      } 
                  ?>
              </select>  
            <span id="cook_id"></span>
            </div>
          </div>
            <div class="form-group row">
            <label class="col-sm-5 col-form-label">Cook Nationality</label>
            <div class="col-sm-7"> 
            <input class="form-control" type="text" name="cook_nationality" id="cook_nationality" value="<?php echo ucfirst($ship_details['cook_nationality'])?>" <?php echo $cook_nationalityDisable;?>>
            <span id="c_nationality"></span>

            </div>
          </div>
            <div class="form-group row">
            <label class="col-sm-5 col-form-label">Number of Crew members <span>*</span></label>
            <div class="col-sm-7"> 
            <input class="form-control" type="number" name="total_members" id="total_members" value="<?php echo ucfirst($ship_details['total_members'])?>" <?php echo $crewDisable;?>>  
            <span id="members"></span>

            </div>
          </div>
           <div class="form-group row">
            <label class="col-sm-5 col-form-label">Trading area <span>*</span></label>
            <div class="col-sm-7"> 
            <input class="form-control" type="text" name="trading_area" id="trading_area" value="<?php echo ucfirst($ship_details['trading_area'])?>" <?php echo $tradingAreaDisable;?>>  
            <span id="area"></span>

            </div>
          </div>
            <input type="hidden" name="unlink" id="unlink" value="No">          
           <div class="form-group row">
            <label class="col-sm-5 col-form-label">Agreed Victualling rate <span>*</span></label>
            <div class="col-sm-7"> 
            <input class="form-control" type="number" name="victualling_rate" id="victualling_rate" value="<?php echo ucfirst($ship_details['victualling_rate'])?>" <?php echo $agreedDisable;?>>  
            <span id="rate"></span>

            </div>
          </div>
          <?php if($edit_ship){?>
          <div class="form-group row">
                  <div class="col-md-12">
                  <label class="col-sm-5 col-form-label"></label>
                  <div class="col-sm-7">
                     <button type="button" onclick="submitShipDetailForm()" class="btn btn-success border-5 btn-sm">Submit</button>
              </div>
            </div>
            </div>
          <?php } ?>
            </form>
          <?php }else{
           ?>
           <div class="form_no_data">
             <span><strong>No Data Available !!</strong><span>
           </div>
          <?php 
           } 
          ?>
          </div>
        </div>
   </div>
 </div>
   <div class="col-md-12">
     <div class="card">
              <div class="card-header pt-0 pb-0">
                  <div class="row d-flex align-center">
                  <div class="col-md-4">Available Stock In Group</div>
                    <div class="col-md-8">
                    </div>
                      <div class="card-body">
                <div class="row">
                  
                  <div class="col-md-12">
                  <div class="table-responsive tr-table">
                  <table class="table flex-table">
                      <thead>
                        <tr>
                          <th width="25%">Group</th>
                          <th width="25%" >Unit</th>
                          <th width="25%">Per Man Per Day</th>
                          <th width="25%">Qty</th>
                        </tr>
                      </thead>
                      <tbody class="">
                        <?php
                         if(!empty($group_products)){
                           foreach ($group_products as $row) {
                            $qty = 0;
                             if($row->unit == 1){
                                $unit = "KG"; 
                              }else if($row->unit == 2){
                                $unit = "Liter"; 
                              }
                            ?>
                            <tr>
                             <td width="25%"><?php echo  ucfirst($row->name);?></td>
                             <td width="25%"><?php echo $unit;?></td>
                             <td width="25%"><?php echo $row->consumed_qty;?></td>
                             <td width="25%"><?php echo $row->qty;?></td>
                           </tr>
                           <?php }
                         }
                         else{
                          ?>
                          <td width="100%">No Data Available</td>
                         <?php }
                        ?>
                     </tbody>
                  </table>
                  </div>
                  </div>
                </div>
              </div>
            </div>
   </div>
   </div>

            
<script type="text/javascript">
  
  function submitShipDetailForm(){
     var $data = new FormData($('#ship_details_form')[0]);
     var shipping_company_id = $('#shipping_company_id').val();
     var ship_id = $('#active_ship_id').val();
     var page_id = $('#page').val();
         $data.append('ship_id',ship_id);
         $data.append('shipping_company_id',shipping_company_id);
         $data.append('actionType','save');
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/update_ship_details',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='100'){
                  $('#name').html(obj.validation_msg.name);
                  $('#imo').html(obj.validation_msg.imo_no);
                  $('#members').html(obj.validation_msg.total_members);
                  $('#captain_id').html(obj.validation_msg.captain_user_id);
                  $('#ct_nationality').html(obj.validation_msg.captain_nationality);
                  $('#cook_id').html(obj.validation_msg.cook_user_id);
                  $('#c_nationality').html(obj.validation_msg.cook_nationality);
                  $('#area').html(obj.validation_msg.trading_area);
                  $('#rate').html(obj.validation_msg.victualling_rate);
                }
                else if(obj.status==200){
                 var html = '<div class="confirm_msg">'
                  if(typeof(obj.captainmsg)!='undefined'){
                   html+='<p class="text-center"><strong>Note:</strong></p><span><strong>'+obj.captainmsg+'.</strong></span><br>';  
                  }
                  if(typeof(obj.cookmsg)!='undefined'){
                   html+='<span><strong>'+obj.cookmsg+'.</strong></span><br>';  
                  }        
                   html+='<p class="mt-15">Are you sure you want to unlink from the current ship and link to this ship ?</p></div>';       
                    bootbox.dialog({
                        message: html,
                        title: "Confirmation",
                        className: "modal-primary",
                        buttons: {
                            danger: {
                                label: "No",
                                className: "btn-danger btn-slideright mLeft",
                                callback: function () {}
                            },
                            success: {
                                label: "Yes",
                                className: "btn-success btn-slideright",
                                callback: function () {
                                  $('#unlink').val('Yes');  
                                  submitShipDetailForm();
                                }
                            }

                        }
                    });  
                }
                else{
                  $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                  submitGetAllShipsform(page_id);
                  setTimeout(function(){
                         $('.custom_alert').remove();
                    },3000)
               }
            }
        });
  }

$(document).ready(function(){
  get_months();

$("#cs_keyword").keypress(function(event){
     if (event.which == 13) {
     current_stock();
      return false;
     }
  })
})

function resetcs(){
 $("#cs_keyword").val('');
 $("#cs_category_id").val('');
 current_stock();
}

 function current_stock()
   {   
       var $data = new FormData($('#current_stock_form')[0]);  
       var ship_id = $('#active_ship_id').val();
        $data.append('ship_id',ship_id);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/getShipCurrentStock',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            // data : {'ship_id':ship_id,'keyword':$('#cs_keyword').val(),'category_id':$('#cs_category_id').val(),'download':$('#cs_download').val()},
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.current_stock_items').html(obj.dataArr);
            }
        });
        return false;
    }

 function download_cs(){
  $('#cs_download').val('1');
  $('#current_stock_form').submit();
  $('#cs_download').val('0');
 }   


  

function getNationality(user_id,type){
   if(user_id){
     $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'user/getNationality',
        data: {'user_id':user_id},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            if(type=='captain'){
              $('#captain_nationality').val(obj.name);
            }
            else if(type=='cook'){           
              $('#cook_nationality').val(obj.name);
            }
        }
     });  
   }
   else{
      if(type=='captain'){
         $('#captain_nationality').val('');
        }
      else if(type=='cook'){           
          $('#cook_nationality').val('');
        }
   }
 }   


 function get_months(){
  var year = $('#cs_year').val();
  var ship_id = $('#active_ship_id').val();
   if(year){
    $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'shipping/stock_month',
        data: {'year':year,'ship_id':ship_id},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            $('#cs_month').html(obj.data);
            current_stock();
        }
     });
   }
   else{
     $('.current_stock_items').html('<tr class="no-data"><td colspan="3" align="center">No Data Available</td></tr>');
   }
 }
</script>          
        
         