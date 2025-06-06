<!-- Start body content -->
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg))
{
    ?><div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg))
{
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}

$sessionData = getSessionData();
$shippingCompanyDisable = '';

if($sessionData->code =='cook' || $sessionData->code =='captain' || $sessionData->code =='shipping_company'){
 $shippingCompanyDisable = 'disabled';  
}

$view_stock_summary = checkLabelByTask('view_stock_summary');
$view_next_port = checkLabelByTask('view_next_port');
$view_rfq = checkLabelByTask('view_rfq');
$manage_delivery_note = checkLabelByTask('view_delivery_note');
$manage_stock = checkLabelByTask('view_stock');
$manage_consumed_stock = checkLabelByTask('view_consumed_stock');
$manage_invoice = checkLabelByTask('view_invoice');
$manage_work_order = checkLabelByTask('view_work_order');
$manage_extra_meal = checkLabelByTask('view_extra_meal');
$victualing_report = checkLabelByTask('view_victualing_report');
$manage_condemned_stock = checkLabelByTask('view_condemned_stock');
//$view_activity_tab = checkLabelByTask('view_activity_tab');
$view_company_details = checkLabelByTask('view_company_details');
$next_port_widget = checkLabelByTask('next_port_widget');
$edit_company = checkLabelByTask('edit_company');
$add_ship = checkLabelByTask('add_ship');
$show_payment_term = checkLabelByTask('show_payment_term');
?> 
<div id="showSuccMessage">
</div>

<div id="tour-11" class="header-content">
  <h2><span class="icon"><i class="fas fa-tools"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<head>
  <style>
    .card {
      box-shadow: 0px 0px 5px #bababa;
      border-radius: 3px;
    }
    .card-body {
      padding: 10px;
    }
    .list-group {
      margin-bottom: 0; 
    }
    .list-group-item {
      border-radius: 0 !important;
    }
  .mb-5 {margin-bottom: 5rem;}
  .mt-5 {margin-top: 5rem;}
  .mt-4 {margin-top: 4rem;}
  .mb-4 {margin-bottom: 4rem;}
  .mt-3 {margin-top: 3rem;}
  .mb-3 {margin-bottom: 3rem;}
  .mt-2 {margin-top: 2rem;}
  .mb-2 {margin-bottom: 2rem;}
  .mt-1 {margin-top: 1rem;}
  .mb-1 {margin-bottom: 1rem;}
  .navbar {
    min-height: auto;
    margin-bottom: 0;
}
.card-header {
  padding:10px;
  margin-bottom: 0;
  background-color: rgba(0,0,0,.03);
  border-bottom: 1px solid rgba(0,0,0,.125);
  margin-top: 0;
}
.panel-body {
  padding: 0;
}

/********************/


  </style>
</head>

<body>

  <div class="content-area pl-10"> 
    <div class="row">
      <div class="col-md-2 pr-0 s-sidebar mt-10">
        <div class="si-card si-blue-card">
        <h4 class="panel-title">Shipping Company</h4>
        <select name="shipping_company_id"  id="shipping_company_id" class="form-control" <?php echo $shippingCompanyDisable;?>>
          <option value="">Select Company</option>
          <?php 
          if(!empty($shipping_company)){
            foreach ($shipping_company as $row) {
             ?>
             <option <?php echo ($shipping_company_id == $row->shipping_company_id) ? 'selected="selected"' : '';?> value="<?php echo $row->shipping_company_id;?>"><?php echo ucfirst($row->name);?></option>
            <?php 
            }
          }
          ?>
        </select>
        </div>
        <div class="si-card si-lighht-card mt-0">
        <div class="sidebar-title"> 
          <div class="d-flex align-center flex-row input-with-icon">

        <?php if($sessionData->code == 'cook' || $sessionData->code == 'captain'){ 
          }else{ ?>       
          <input type="text" class="form-control" id="shipKeyword" placeholder="Search Vessel.." title="Search by Vessel Name or Imo No." onChange="submitGetAllShipsform();">
         <?php
          }
         if($add_ship){ 
         ?> 
          <a onClick="showAjaxModel('Add Vessel','shipping/add_edit_ships/360_view','','<?php echo $companyData['shipping_company_id'];?>','70%')" class="p-icon" tabindex="0" href="javascript:void(0);" title="Add Vessel"><span><i class="fas fa-plus-circle"></i></span></a>
        <?php }?>
          </div>  
             </div>
   
              <div class="sidebar-body">
                <div>
                  <div class="list-group list-group-flush scrollarea" id="ships_data">
                   <!-- ship pist -->
                  </div>
                </div>
              </div>
            
          
        
        <div id="ships_pagination">
          <!-- ships list pagination -->
        </div>
        </div>
      </div>

      <!-- company details -->
      <div class="col-md-10">
        <div class="row">
<!--           <div class="col-md-12 <?php echo ($sessionData->code=='super_admin' || $sessionData->code=='captain' || $sessionData->code=='cook') ? '' : 'staff-area' ;?>" style="overflow:auto; height:92.3vh; padding-bottom:0"> -->
            <div class="col-md-12" style="overflow:auto; height:92.3vh; padding-bottom:0">
             <?php if($next_port_widget){?>
             <div class="OrderShift" id="steps">
  			    
             </div>
           <?php } ?>
            <div class="si-card <?php echo ($view_company_details) ? '' :'hide';?>">

            <div class="panel-group sc-details mb-0" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default ">
    <div class="panel-heading mb-0" role="tab" id="headingOne">
    
    <div class="ReportBorder clearfix">
      


        <h4><?php echo ucwords($companyData['name'])?>&nbsp;
          <?php if($edit_company){ ?><span style="cursor:pointer;" onClick="showAjaxModel('Edit Shipping Company','shipping/add_edit_company','<?php echo $companyData['shipping_company_id'];?>','','70%');"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <?php } ?> <a class="" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-angle-down" aria-hidden="true"></i><i class="fa fa-angle-right" aria-hidden="true"></i></a>
        </h4>
    

    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      <div class="row">
        <?php if(!empty($companyData)){?>
                <div class="col-sm-2">


                  <label class="control-label"><strong>Customer ID:</strong></label><br>
                  <?php echo $companyData['customer_id'];?>


                </div>
                <div class="col-sm-5">
                  <label class="control-label"><strong>Email:</strong></label><br>
                  <?php echo $companyData['email'];?>
                </div>
                <?php
                 if($show_payment_term){ 
                 ?>  
                  <div class="col-sm-3">
                  <label class="control-label"><strong>Payment Terms:</strong></label><br>
                  <?php echo $companyData['payment_term'];?>
                </div>
                 <?php }
                  ?>
                   <div class="col-sm-2">
                  <label class="control-label"><strong>Phone:</strong></label><br>
                  <?php echo $companyData['phone'];?>
                </div>
              <?php }
               else{
                ?>
                   <div class="col-sm-12">
                  <label class="control-label"><strong>No Data Available</strong></label>
                </div>
               <?php }
              ?>
             
            </div>
            </div>
    </div>
      </div>
    </div>
    </div>
  </div>
</div>


           <div class="">
           <!--<div id="steps">
           
           </div>-->
          </div>
          <?php
           // if($sessionData->code == 'super_admin' || !empty($shipId))?>

          <?php if(!empty($shipId))  {?>   
            <div class="si-card right-content mt-10 <?php echo ($sessionData->code=='super_admin') ? '' : 'staff-area-right-content' ;?>">
              <div class="inner-tabs">
                  <div id="top-tabs" class="flex-column">
                    <div class="d-flex flex-wrap" id="details-tab">
                    <a href="#" class="scroll-arrow scroll-left" onClick="sLeft()"><i class="fas fa-angle-left"></i></a>
                    <ul id="tab-list" class="nav nav-pills" style="background:none !important;">
                      <?php if($view_stock_summary){?>
                      <li><a href="#tabs-1" onclick="getCompanySummury()">Summary</a></li>                    
                    <?php } ?>
                    <?php if($view_next_port){?>
                      <li><a href="#tabs-2" onClick="ports_list();">Next Port</a></li>
                    <?php } 
                     if($view_rfq){?>
                      <li><a href="#tabs-3" onClick="order_request_list();">RFQ</a></li>
                      
                      <?php 
                       } 
                       if($manage_work_order){?>
                      <li><a href="#tabs-4" onClick="work_order();">Purchase Order</a></li>
                    <?php } 
                       if($manage_delivery_note){
                      ?>
                      <li><a href="#tabs-5" onClick="delivery_note_list();">Delivery Note</a></li>
                      <?php }
                       if($manage_invoice){?>
                       <li><a href="#tabs-6" onClick="invoice_list();">Invoice</a></li>
                      <?php } 
                      if($manage_stock){?>
                      <li><a href="#tabs-7" onClick="stock_list();">Inventory</a></li>
                      <?php } 
                      if($manage_consumed_stock){?>
                      <li><a href="#tabs-8" onClick="consumed_stock_list();">Stock Control</a></li>
                      <?php }
                       if($manage_extra_meal){?>
                      <li>
                        <a href="#tabs-9" class="em_tabs" onClick="extra_meals_html()">Extra Meals</a>
                      </li>
                      <?php } ?>
                      <?php if($victualing_report){?>
                       <li>
                        <a href="#tabs-10" class="vs_tabs" onclick="victualling_summary();">Victualing Summary</a>
                      </li>
                      <?php } 
                      if($manage_condemned_stock){?>
                      <li>
                        <a href="#tabs-11" onclick="condemned_stock_list();">Condemned Stock</a>
                      </li>
                      <?php 
                         }
                       if($view_activity_tab){  
                       ?>
                      <li><a href="#tabs-12" onclick="log_activity();">Backend Activity</a></li>
                      <?php }
                      ?> 
                    </ul>
                    <a href="#" class="scroll-arrow scroll-right" onClick="sRight()"><i class="fas fa-angle-right"></i></a>
                    <div id="stock_managment_links">
                   </div>
                  </div>
                  <?php if($view_stock_summary){?>
                    <div id="tabs-1">
                      <div id="company_summury"></div>
                      <?php //$this->load->view('company_summ');?>
                    </div> 
                  <?php } if($view_next_port){?>
                    <div id="tabs-2">
                      <?php //$this->load->view('ship_tour_list');?>
                      <div id="ships_tour_list_html"></div>
                    </div>
                  <?php } 
                  if($view_rfq){?>
                    <div id="tabs-3">
                      <div id="rfq_list"></div>
                    </div> 
                   <?php } 
                    if($manage_work_order){?>
                    <div id="tabs-4">
                      <div id="work_order_html"></div>
                    </div>  
                  <?php } 
                    if($manage_delivery_note){
                   ?>
                      <div id="tabs-5">
                      <div id="delivery_note_list"></div>
                    </div>
                  <?php }
                  if($manage_invoice){?>
                     <div id="tabs-6">
                      <div id="invoice_list"></div>
                    </div>
                  <?php }
                  if($manage_stock){?>
                    <div id="tabs-7">
                      <div id="stock_list"></div>
                    </div>
                   <?php } 
                   if($manage_consumed_stock){?> 
                    <div id="tabs-8">
                      <div id="consumed_stock_list"></div>
                    </div>
                    <?php } 
                    if($manage_extra_meal){?>        
                      <div id="tabs-9">
                       <div id="extra_meals_form">            
                       </div>
                    </div>
                    <?php }
                    if($victualing_report){?>
                    <div id="tabs-10">
                      <div id="victualling_summary_report"></div>
                    </div> 
                    <?php }
                    if($manage_condemned_stock){?>
                    <div id="tabs-11">
                      <div id="condemned_stock_report"></div>
                    </div> 
                  <?php }
                  if($view_activity_tab){?>
                    <div id="tabs-12">
                      <div id="log_activity"></div>
                    </div>
                   <?php }?>        
              </div>
            
            </div>
          <?php }else{?>
             <div class="si-card right-content"><p style="text-align:center;"> NO DATA AVAILABLE</p></div>
          <?php } ?>
            <input type="hidden" name="page" id="page" value="1">
            <input type="hidden" id="active_ship_id" value="<?php echo $shipId ?>">
            <input type="hidden" name="shipping_company_id" id="shipping_company_id" value="<?php echo $companyData['shipping_company_id']?>">
          </div>
  </div>
</div>
</body>
<script type="text/javascript">
 //var $shipId = $('#active_ship_id').val();console.log($shipId);
 $('#shipping_company_id').change(function(){
   var shipping_company_id =  $('#shipping_company_id option:selected').val();
   window.location.href = base_url+'shipping/shippingCompanyDetails/'+btoa(shipping_company_id);
 })


 $(document).ready(function(){
    submitGetAllShipsform();
  })
  
 function submitGetAllShipsform(pageId,stringFunction){
   var keyword = $('#shipKeyword').val();
   var shipping_company_id = $('#shipping_company_id').val();
   var active_ship_id = $('#active_ship_id').val();
   $('#page').val(pageId);
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'shipping/getAllShips360/'+btoa(shipping_company_id),
        data: {'keyword':keyword,'page':pageId},
        cache:false,
        async: false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#ships_data').html(obj.data);
          $('#ships_pagination').html(obj.pagination);
           // $('.ships_link').removeClass('active');
           if(active_ship_id){
             // $('#ship_'+active_ship_id).addClass('active');
             getShipAllDetailsByID(active_ship_id);
           }
           else{
             // $('#active_ship_id').val(obj.default_active_ship_id);
             $('#ship_'+obj.default_ship_id).addClass('active');
             getShipAllDetailsByID(default_ship_id);   
           }
           // set_ship_data();
           // evaluateFunction(stringFunction);
        }
    })
 }

  function clearAllTabs(){
    $('#ships_tour_list_html').html('');
    $('#rfq_list').html('');
    $('#work_order_html').html('');
    $('#delivery_note_list').html('');
    $('#invoice_list').html('');
    $('#stock_list').html('');
    $('#consumed_stock_list').html('');
    $('#extra_meals_form').html('');
    $('#victualling_summary_report').html('');
    $('#condemned_stock_report').html('');
  }

 // function evaluateFunction(stringFunction){
 //      if(stringFunction){
 //        param ='';
 //        window[stringFunction](param);
 //      }
 //  }

  function submitPagination(pageId){
       $('#active_ship_id').val('');
       submitGetAllShipsform(pageId);
  }

  $( function() {
    $( "#top-tabs" ).tabs();
  } );

 
 function getCompanySummury(){
    var active_ship_id = $('#active_ship_id').val();
    var shipping_company_id = $('#shipping_company_id').val();
     $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            console.log('company_summury :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/getCompanySummury/'+btoa(active_ship_id),
        data : {'shipping_company_id':shipping_company_id},
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#company_summury').html(obj.data);
        }
    })
  }
 
  function getShipAllDetailsByID(active_ship_id){
     $( "#top-tabs" ).tabs({
       active : 0
     });
     $('#active_ship_id').val(active_ship_id);
     var page_id = $('#page').val();
     $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            $('.ships_link').removeClass('active');
        },
        type: "POST",
        url: base_url + 'shipping/set_ship_details/'+btoa(active_ship_id),
        cache:false,
        async: false,
         success: function(msg){
           $("#customLoader").hide();
           console.log('input - '+$('#active_ship_id').val()+' click-'+active_ship_id);
           var obj = jQuery.parseJSON(msg);
             $('#ship_'+active_ship_id).addClass('active');

           // submitGetAllShipsform(page_id);
           if(obj.status==100){
             $('.em_tabs').hide();
             $('.vs_tabs').hide();

           }
           else{
             $('.em_tabs').show();
             $('.vs_tabs').show();

           }
            getCompanySummury();
            getAllsteps();
         }
      })           
   }

   function getAllsteps(){
    var active_ship_id = $('#active_ship_id').val();
       $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
             console.log('next_port_bar :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/get_port_steps/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#steps').html(obj.data);
        }
     })
   }

  function ports_list(){
    var active_ship_id = $('#active_ship_id').val();
       $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
             console.log('next_port_list :'+active_ship_id);
             clearAllTabs();
        },
        type: "POST",
        url: base_url + 'shipping/getPortListhtml/'+btoa(active_ship_id),
        cache:false,
        async : false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#ships_tour_list_html').html(obj.data);
          getAllsteps();  
        }
    })
  } 

 function work_order(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
             console.log('work_order_list :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/work_order_list/'+btoa(active_ship_id),
        cache:false,
        async : false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#work_order_html').html(obj.data);
        }
    })
  }
  
  function order_request_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('rfq_list :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/order_request_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#rfq_list').html(obj.data);
          updateNotificationCount();
        }
    })
  }


  // function updateNotificationCount(){
  //    $.ajax({
  //      beforeSend: function(){
  //           $("#customLoader").show();
  //       },
  //       type: "POST",
  //       url: base_url + 'user/getNotificationCount',
  //       cache:false,
  //       success: function(msg){
  //         $("#customLoader").hide();
  //          var obj = jQuery.parseJSON(msg);
  //          if(obj.count>0){
  //          $('.spn_digit').text(obj.count);
  //          }
  //          else{
  //          $('.spn_digit').text(''); 
  //          }
  //       }
  //     })  
  // }
  
  // var callAjax = false; 
  // function set_ship_data(){
  //   var active_ship_id = $('#active_ship_id').val();
  //    $.ajax({
  //      beforeSend: function(){
  //           $("#customLoader").show();
  //       },
  //       type: "POST",
  //       url: base_url + 'shipping/set_ship_details/'+btoa(active_ship_id),
  //       cache:false,
  //       success: function(msg){
  //         $("#customLoader").hide();
  //          var obj = jQuery.parseJSON(msg);
  //          callAjax = true; 
  //          if(obj.status==100){
  //            $('.em_tabs').hide();
  //            $('.vs_tabs').hide();

  //          }
  //          else{
  //            $('.em_tabs').show();
  //            $('.vs_tabs').show();

  //          }
  //          if(callAjax){
  //            getCompanySummury();
  //            getAllsteps();
  //          }
  //       }
  //   })
  // }

 function delivery_note_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('delivery_note_list :'+active_ship_id);

        },
        type: "POST",
        url: base_url + 'shipping/delivery_notes/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#delivery_note_list').html(obj.data);
        }
    })
  } 

  function invoice_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('invoice_list :'+active_ship_id);
        },

        type: "POST",
        url: base_url + 'shipping/invoice_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#invoice_list').html(obj.data);
        }
    })
  } 


  function stock_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('stock_list :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/stock_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#stock_list').html(obj.data);
        }
    })
  }

  function consumed_stock_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('consumed_stock_list :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/consumed_stock_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#consumed_stock_list').html(obj.data);
        }
    })
  } 


function calculateDiscount(perc){
  var total_amount = parseFloat($('#create_invoice_total_amount').text());
  if(perc==''){
    var original_amout = $('#create_invoice_total_amount').data('value');
    $('#create_invoice_net_amount').text(original_amout)

  }
  else{
    var discounted_amount = parseFloat(total_amount)*parseFloat(perc)/100;
    var net_amount = total_amount - discounted_amount;
    $('#create_invoice_net_amount').text(parseFloat(net_amount,10).toFixed(2)+'/-');       
  }

}

  
  function extra_meals_html(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('extra_meals_list :'+active_ship_id);
        },

        type: "POST",
        url: base_url + 'shipping/extra_meals_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#extra_meals_form').html(obj.data);
        }
    })
  }  

 function victualling_summary(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('victualling_summary :'+active_ship_id);
        },
        type: "POST",
        url: base_url + 'shipping/victualling_summary/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#victualling_summary_report').html(obj.data);
        }
    })
   
  }

  function condemned_stock_list(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('condemned_stock_list :'+active_ship_id);    
        },
        type: "POST",
        url: base_url + 'shipping/condemned_stock_list/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#condemned_stock_report').html(obj.data);
        }
    })
   
  }

  function log_activity(){
   var active_ship_id = $('#active_ship_id').val();
   $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
            clearAllTabs();
            console.log('log_activity :'+active_ship_id);    
        },
        type: "POST",
        url: base_url + 'shipping/logActivityHtml/'+btoa(active_ship_id),
        cache:false,
        success: function(msg){
          $("#customLoader").hide();
          var obj = jQuery.parseJSON(msg);
          $('#log_activity').html(obj.data);
        }
    })
   
  }  
</script>
 <script>
    function sLeft() {
      document.getElementById('tab-list').scrollLeft -= 100;
    }
    function sRight() {
      document.getElementById('tab-list').scrollLeft += 100;
    }
</script>



<?php
$active_tab = trim($this->input->get('entity'));

if(!empty($active_tab)){
    if($active_tab=='rfq'){
      ?>
      <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-3"]').click();
        };
      </script>
      <?php
    }
    elseif($active_tab=='purchase_order'){
      ?>
      <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-4"]').click();
        };
      </script>
    <?php }
    elseif($active_tab=='delivery_note'){
      ?>
        <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-5"]').click();
        };
      </script>
      <?php }
    elseif($active_tab=='company_invoice'){
      ?>
        <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-6"]').click();
        };
      </script>
    <?php }
     elseif($active_tab=='extra_meal'){
      ?>
        <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-9"]').click();
        };
      </script>
    <?php }
    elseif($active_tab=='victualing'){
      ?>
        <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-10"]').click();
        };
      </script>
    <?php }
     elseif($active_tab=='condemned'){
      ?>
        <script type="text/javascript">
        $('#active_ship_id').val('<?php echo $this->input->get('ship_id')?>');
         window.onload = function() {
             document.querySelector('a[href="#tabs-11"]').click();
        };
      </script>
    <?php }
  ?>
<?php }
?>