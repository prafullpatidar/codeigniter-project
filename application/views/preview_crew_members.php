<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="pre_import_form" enctype="multipart/form-data" id="pre_import_form" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                  <h5 style="text-align:center;"><strong>Ship Details</strong></h5>
                  <div class="table-responsive-new">
                  <table class="table table-bordered pcm_table">
                    <thead>
                      <tr>
                        <th>Arrival / Departure</th>
                        <th>Name of Ship</th>
                        <th>IMO number</th>
                        <th>Call Sign</th>
                        <th>Voyage Number</th>
                        <th>Port of Arrival / Departure</th>
                        <th>Date of Arrival / Departure</th>
                        <th>Flag State of Ship</th>
                        <th>Last Port of Call</th>
                        <th>Date and signature <br> <small>(by master, authorized agent or officer)</small></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      //print_r($crewArr);die;
                      if(!empty($crewArr)){?>
                            <tr class="child_row">
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo ($crewArr['arrival_or_departure']=='A')?'Arrival':'Departure';?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr["ship_name"];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr["ship_imo"];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['call_sign'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['voyage_number'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['port_of_arrival_or_departure'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo ($crewArr['date_of_arrival_or_departure']== '' || $crewArr['date_of_arrival_or_departure']=='01-01-1970')?'':date('d-m-Y',strtotime($crewArr['date_of_arrival_or_departure']));?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['flag_state_of_ship'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['last_port_of_call'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key">
                               <div class="form-group preview-sign">
                                  <div id="signatureparent">
<!--                                     <div  id="jsignature" style="border: 1px dashed black;margin-bottom:25px"></div>
                                   </div> 
                                   -->
                                  <input type="file" id="inputImage" name="file" accept=".jpg,.jpeg,.png">
                                  <div class="croppedImg" style="height:70px;width: 40px;"></div>
                                  <input type="hidden" name="jsignature" id="blob_url" value="">
                                </div>
                             </td>

                            </tr>
                        
                        <?php }
                       ?>
                    </tbody>
                  </table>
                  </div>
                <hr/>
                <h5 style="text-align:center;"><strong>Crew Members</strong></h5>
              <div id="abc" class="sip-table" role="grid">
              <div class="table-responsive-new">
              <table class="table table-bordered pcm_table">
               <thead class="t-header">
               <tr>
                  <th width="4%">S.No.</th>
                  <th width="10%">Family Name</th>
                  <th width="10%">Given Name</th>
                  <th width="8%">Rank or Rating </th>
                  <th width="7%">Nationality</th>
                  <th width="8%" style="text-align: center;">Date of Birth</th>
                  <th width="7%" style="text-align: center;">Place Of Birth</th>
                  <th width="5%" style="text-align: center;">Gender</th>
                  <th width="7%" style="text-align: center;">Nature of Identity</th>
                  <th width="7%" style="text-align: center;">Number of Identity</th>
                  <th width="13%" style="text-align: center;">Issuing State of Identity</th>
                  <th style="text-align: center;" width="14%">Expiry Date of Identity</th>
                  </tr>
                </thead>
                    <tbody class="item_data" id="crew_data">
                        <?php
                         ($crewArr['arrival_or_departure']=='A')?'Arrival':'Departure';
                        //print_r($crewArr);die;
                         if(!empty($crewArr['crew'])){
                          $i=1;$error = false;
                          foreach($crewArr['crew'] as $crew){
                            $color = ($crewArr['arrival_or_departure']=='A') ? 'green' : 'red';
                            $already_exist = ($crew['already_exist']==1) ? 'style="color:'.$color.';text-align: center;"' : ' style="text-align: center;"';
                            $identity_label = (($crew['nature_of_identity']=='')?"Passport No":$crew['nature_of_identity']);
                            $returnArr .= '<tr class="child_row">';
                            $returnArr .= '<td width="4%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$i.'</td>';
                            $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['family_name'].'</td>';
                            $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['given_name'].'</td>';
                            $returnArr .= '<td width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['rank'].'</td>';
                            $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['nationality'].'</td>';

                            $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['date_of_birth'].'</td>';
                            $returnArr .= '<td width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['place_of_birth'].'</td>';
                            $returnArr .= '<td width="5%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['gender'].'</td>';
                            $returnArr .= '<td width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$identity_label.'</td>';
                            $returnArr .= '<td width="7%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['number_of_identity'].'</td>';
                            $returnArr .= '<td width="13%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['issuing_state_of_identity'].'</td>';
                            $returnArr .= '<td width="14%" role="gridcell" tabindex="-1" aria-describedby="f2_key" '.$already_exist.'>'.$crew['expiry_date_of_identity'].'</td>';
                            $returnArr .= '</tr>'; 
                            $i++;
                          }
                         }else{
                          $error=true;
                            $returnArr .= '<tr class="child_row">';
                            $returnArr .= '<td width="100%" role="gridcell" tabindex="-1" aria-describedby="f2_key" colspan="12"><p style="color:red;text-align:center">Something Wrong with Passport Id , Given Name or Rank. Please Check these three feilds in your Import Sheet!</p></td>';
                            $returnArr .= '</tr>'; 
                         }
                                
                           echo $returnArr;    
                      ?>
                    </tbody>
                </table>
         </div>
         </div>
           </div>
         </div>
            <input type="hidden" name="actionType" id="actionType" value="save">  
            <input type="hidden" name="shipId" id="shipId" value="<?php echo $ship_id; ?>">  
       </form>
     </div>
   </div>
 </div>
<div class="clearfix"></div>
  <div class="form-footer">
          <div class="pull-right">
           <a class="btn btn-danger btn-slideright mr-5" href="javascript:void(0)" onclick="showAjaxModel('Import Crew Members','shipping/import_crew_members','<?php echo $ship_id;?>','','');">Back to Import</a>
           <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
           <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitImport()">Save</button>
          </div>
<div class="clearfix"></div>
</div><!-- /.form-footer -->        
<!-- <script src="<?php echo base_url()?>/assets/js/jSignature.min.js"></script> -->
<link rel="stylesheet" href="<?php echo base_url().'assets/js/cropper/css/cropper.css';?> ">
<link rel="stylesheet" href="<?php echo base_url().'assets/js/cropper/css/main.css';?>">
<script src="<?php echo base_url().'assets/js/cropper/js/cropper.js';?>"></script>
  <script src="<?php echo base_url().'assets/js/cropper/js/main.js';?>"></script>



    <!-- Show the cropped image in modal -->
     <div class="modal" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" onclick="closeCrop();" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title pop_heading"  id="getCroppedCanvasTitle">Cropped</h4>
              </div>
              <div class="modal-body">
                 <div class="img-container">
                   <img id="image" src="images/picture.jpg" alt="Picture">
                  </div> 
              </div>
              <div class="modal-footer docs-buttons">
                <a class="btn btn-success" id="download" href="javascript:void(0);" data-method="getCroppedCanvas">Save</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal -->


<script type="text/javascript">
    $(document).ready(function(){
       convertToExcel();        
     })


   function closeCrop(){
    $('#blob_url').val('');
    $('#inputImage').val('');
    $('#getCroppedCanvasModal').modal('toggle');
  }

    $(document).ready(function() {
 //  var $sigdiv = $("#jsignature").jSignature({
 //    'UndoButton':true,
 //    'background-color': 'transparent',
 //    'decor-color': 'transparent',

 // })

 // $("#jsignature").bind('change', function(e){ 
 //     if( $sigdiv.jSignature('getData', 'native').length == 0) {
 //      $("#jsignatureimageUrl").val('');  
 //     }
 //     else{
 //        var signature = $sigdiv.find(".jSignature").jSignature("getData", 'image');
 //        $("#jsignatureimageUrl").val(btoa(signature));
 //     }
 //    })
 })


   function submitImport(){
     var crewData = $('#crew_data').html();
     var error = '<?php echo $error;?>';
     crewData = crewData.trim();
     if(error == true){
       alert('Crew Data is Empty!');return false;
     }else{
      var sign = $('#blob_url').val();
      if(sign){   
        submitMoldelForm('pre_import_form','crew/preview_crew_members','98%');
      }else{
        alert('You need to Sign the Import Data to submit it!')
      }
     }
   }
   function convertToExcel(){
      var tr,td,cell;
      td=$("td").length;
      tr=$("tr").length;
      cell=td/(tr-1);//one tr have that much of td
      //alert(cell);
      $("td").keydown(function(e)
      {
        switch(e.keyCode)
        {
          case 37 : var first_cell = $(this).index();
                if(first_cell==0)
                {
                $(this).parent().prev().children("td:last-child").focus();
                }else{
                $(this).prev("td").focus();break;//left arrow
                              }
          case 39 : var last_cell=$(this).index();
                if(last_cell==cell-1)
                {
                $(this).parent().next().children("td").eq(0).focus();
                }
                $(this).next("td").focus();break;//right arrow
          case 40 : var child_cell = $(this).index(); 
                $(this).parent().next().children("td").eq(child_cell).focus();break;//down arrow
          case 38 : var parent_cell = $(this).index();
                $(this).parent().prev().children("td").eq(parent_cell).focus();break;//up arrow
        }
        if(e.keyCode==113)
        {
          $(this).children().focus();
        }
      });

      $("td").focusin(function()
      {
        $(this).css("outline","solid steelblue 3px");//animate({'borderWidth': '3px','borderColor': '#f37736'},100);
        //console.log("Hello world!");
        var qnt = $(this).children('input.quentity').data('quantity');
        if(qnt==1){
          $(this).children('input.quentity').focus();
        }
      });

      $("td").focusout(function()
      {
        $(this).css("outline","none");//.animate({'borderWidth': '1px','borderColor': 'none'},500);
      });

      $(".quentity").focusin(function()
      {
        //$(".quentity").value('Amittttt');
                //alert('asdas');
      });
    };
    (function($) {

$.fn.prepFixedHeader = function () {
 return this.each( function() {
  $(this).wrap('<div class="fixed-table-new"><div class="table-content"></div></div>');
 });
};

$.fn.fixedHeader = function () {
 return this.each( function() {
  var o = $(this),
      nhead = o.closest('.fixed-table-new'),
      $head = $('thead.t-header', o);
  
  $(document.createElement('table'))
    .addClass(o.attr('class')+' table-copy no-border').removeClass('header-fixed-new')
    .appendTo(nhead)
    .html($head.clone().removeClass('t-header').addClass('header-copy'));
  var ww = [];
  o.find('thead.t-header > tr:first > th').each(function (i, h){
    //ww.push($(h).width());
  });
  $.each(ww, function (i, w){
    nhead.find('thead.t-header > tr > th:eq('+i+'), thead.header-copy > tr > th:eq('+i+')').css({width: w});
  });

 //nhead.find('thead.header-copy').css({ margin:'0 auto', width: o.width()});

 var fixedHeaderHeight = $('.header-copy').height();

$('.fixed-table-new .table-content').css({ paddingTop: 47});

 });
};

})(jQuery);
    
$(document).ready(function () {
    // $('#modal-view-datatable .header-fixed-new').prepFixedHeader().fixedHeader();

});
    </script>