<!-- <script src="<?php echo base_url()?>/assets/js/jSignature.min.js"></script> -->
<?php 
$show_payment_term = checkLabelByTask('show_payment_term');
?>
<style type="text/css">
    .ReceiptTitle{
        font-size:8px;
        text-decoration: underline;
        line-height: 6px;
    }
    .ReceiptHead {
    font-size: 18px;
    line-height: 1;
    margin: 0;
}
    .ReceiptAddress{
        font-size:7px;
        line-height: 6px;
    }
    .ReceiptInfo{
        font-size:7 px;
    }
    .ReceiptInfo p{
        padding:1mm;
    }

    <style>
#sign-wrapper { width: auto; margin: 0px auto; padding-top: 5%; height: 100%;    position: relative;  display: inline-block; max-width: 80%;min-width: 45%; text-align: left;}
    #sign-wrapper.fdd_doc_frm { width:750px; padding-top: 5px;}
    #sign-wrapper.fdd_doc_frm label { margin-top: 5px; }
    #sign-wrapper.fdd_doc_frm .brand { margin-bottom: 0; }
    #sign-wrapper.fdd_doc_frm .sign-header { padding:10px 15px; }
    #sign-wrapper.fdd_doc_frm .btn-theme {
    
    /*border-color: #2b8ac0;*/
    color: white;
    width: auto;
    font-size: 14px;
    float: right;
    font-weight: bold;

}

#signatureparent {
        color:darkblue;
       /*/ background-color:darkgrey;*/
        /*max-width:600px;*/
        /*padding:20px;*/
    }
#signatureparent canvas { background:#ffffff;}  
#signatureparent canvas+div { margin-top:0 !important;}
#signatureparent input[type="button"]{font-size: 12px; text-transform: uppercase; font-weight: 500;letter-spacing: 0.2px;  padding: 9px 10px; border-radius: 5px;    min-width: 132px; text-align: center; line-height: 20px; background: #5c5c5c; color:#fff;
    border: #5c5c5c thin solid;} 
#signatureparent input[type="button"]:hover{ background:#333;}
#signatureparent canvas  {max-width: 100%; display: inline-block; vertical-align: middle;}
#signatureparent canvas+div {max-width: 30%; display: inline-block;vertical-align: middle; position:relative;}
#signatureparent canvas+div input { position:static !important;}
#signatureparent { margin-bottom:15px;}
.jSignature{

}
#jsignature canvas { max-height:55px;}
#jsignature input[type="button"] { border-radius:0 !important;}
</style>
</style>
<link rel="stylesheet" href="<?php echo base_url().'assets/js/cropper/css/cropper.css';?> ">
<link rel="stylesheet" href="<?php echo base_url().'assets/js/cropper/css/main.css';?>">
<script src="<?php echo base_url().'assets/js/cropper/js/cropper.js';?>"></script>
  <script src="<?php echo base_url().'assets/js/cropper/js/main.js';?>"></script>
<div class="viewRcpt">
<div class="new-invoice-header">

<table width="100%" class="invoice-table">
  <tr>
    <td colspan="2" width="40%" style="border:none"><img  src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo" width="40"></td>
    <td colspan="4" width="60%" style="border:none"><h2><strong>Delivery Note</h2></td>
  </tr>

  <tr>
    <td colspan="3" rowspan="3">
        
            <h3>ONE NORTH SHIPS</h3>
            <p>Connecting the World<br />
            info@onenorthships.com / catering@onenorthships.com</p>
        
    </td>
    <td><strong>Date:</strong></td>
    <td colspan="2"><?php echo ConvertDate($headData['date'],'','d-m-Y');?></td>
  </tr>


  <tr>
    <td><strong>Delivery Note No #:</strong></td>
    <td colspan="2"><?php echo $headData['note_no'];?></td>
  </tr>
  <tr>
  <td><p><strong>Customer ID:</strong></p>
    <td colspan="2"><?php echo $headData['customer_id'];?></td>
  </tr>





  <tr>
    <td colspan="3"><strong>To:</strong></td>
    <td><strong>Ship To:</strong></td>
    <td colspan="2"><strong>Vessel</strong></td>
    
  </tr>

  <tr>
    <td colspan="3">To Owner/Master of <?php echo ucwords($headData['ship_name']);?></td>
    <td colspan="3">

      <p><?php echo ucwords($headData['ship_name']).', IMO NO. '.$headData['imo_no'];?></p>
      <p>FOB <?php echo $headData['delivery_port'];?></p>
      <p>PO : <?php echo $headData['po_no'];?></p>
    </td>
  </tr>

<?php 
 if($headData['currency']==1){
 $curr ='EURO';
 }
 elseif($headData['currency']==2){
 $curr ='USD';
 }
 elseif($headData['currency']==3){
 $curr ='SGD';
 }
?>

<?php $colspan = ($show_payment_term) ? 2 : 3;?>
 <tr>
    <td>Reqsn Date</td>
    <td colspan="<?php echo $colspan?>">Delivery Port</td>
    <?php
    if($show_payment_term){ 
    ?>
    <td colspan="2">Payment Terms</td>
   <?php } ?>
    <td>Currency</td>
  </tr>
  <tr>
    <td><?php echo ConvertDate($headData['reqsn_date'],'','d-m-Y');?></td>
    <td colspan="<?php echo $colspan?>"><?php echo $headData['delivery_port'];?></td>
    <?php
    if($show_payment_term){ 
    ?>
    <td colspan="<?php echo $colspan?>"><?php echo $headData['payment_term'];?></td>
    <?php } ?>
    <td><?php echo $curr;?></td>
  </tr>
</table>

</div>  
        <form class="form-horizontal form-bordered dr-one" name="delivery_recept" enctype="multipart/form-data" id="delivery_recept" method="post">
                <div class="no-padding rounded-bottom double-table">
                <div class="mt-20">
                <div id="abc" class="sip-table" role="grid">
               <table class="header-fixed-new table-text-ellipsis table-layout-fixed shipping-t table table-default table-middle table-striped table-bordered table-condensed leadListmod">
                <thead class="t-header">
                <tr>
                  <th width="10%">Item No.</th>
                  <th width="25%">Description</th>
                  <th width="10%">Unit</th>
                  <th width="10%">QTY</th>
<!--                   <th width="5%">Unit Price</th>  
                  <th width="5%">Total Price</th>   -->
                  <th width="20%">Comment</th>
                  <th width="15%"></th>
                </tr>
                </thead>
                  <tbody class="item_data">
                   <?php 
                   if(!empty($productArr)){
                  foreach ($productArr as $parent => $rows) {
                    foreach($rows as $category => $products){
                       $returnArr .= '<tr class="parent_row">
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="25%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                        </tr>';
                     for ($i=0; $i <count($products) ; $i++) { 
                        $product_id = $products[$i]['product_id'];
                       $returnArr .= '<tr class="child_row">';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                       $returnArr .= '<td width="25%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                       $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.number_format($products[$i]['quantity'],2).'</td>';
                        // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity" name="qty_'.$product_id.'" id="qty_'.$product_id.'" value="'.$dataArr['qty_'.$product_id].'">'.form_error('qty_'.$product_id,'<p class="error" style="display: inline;">','</p>').'</td>';
                        // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit_price']).'</td>';
                        // $returnArr .= '<td role="gridcell" tabindex="-1" aria-describedby="f2_key">0</td>';
                        $returnArr .= '<td width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key">
                               <select class="link quentity comment" onchange="showFields(this.value,'.$product_id.')" data-quantity="1" name="type_'.$product_id.'" id="type_'.$product_id.'">
                               <option value="">Select</option>
                               <option '.(($dataArr['type_'.$product_id] == 'short_supply') ? 'selected' : '').' value="short_supply">Short supply</option>
                               <option '.(($dataArr['type_'.$product_id] == 'damange_and_spoil') ? 'selected' : '').' value="damange_and_spoil">Damage and spoil</option>     
                               <option '.(($dataArr['type_'.$product_id] == 'poor_quality') ? 'selected' : '').' value="poor_quality">Poor quality</option>     
                               <option '.(($dataArr['type_'.$product_id] == 'wrong_supply') ? 'selected' : '').' value="wrong_supply">Wrong supply</option>     
                               <option '.(($dataArr['type_'.$product_id] == 'other') ? 'selected' : '').' value="other">Other</option>     

                               </select>
                             </td>';  
                       if($dataArr['type_'.$product_id] == 'short_supply' || $dataArr['type_'.$product_id] == 'wrong_supply'){
                        $returnArr .= '<td width="15%" role="gridcell" id="issue_item_'.$product_id.'" tabindex="-1" aria-describedby="f2_key"><input type="text" class="link quentity product_img" name="supply_qty_'.$product_id.'" id="supply_qty_'.$product_id.'" value="'.$dataArr['supply_qty_'.$product_id].'">'.form_error('supply_qty_'.$product_id,'<p class="error" style="display: inline;">','</p>').'</td>';
                      
                       }
                       elseif($dataArr['type_'.$product_id] == 'poor_quality' || $dataArr['type_'.$product_id] == 'damange_and_spoil'){
                         $returnArr .= '<td width="15%" role="gridcell" id="issue_item_'.$product_id.'" tabindex="-1" aria-describedby="f2_key"><input type="file" class="link quentity product_img" accept="image/*" name="img_'.$product_id.'" id="img_'.$product_id.'">'.form_error('img_'.$product_id,'<p class="error" style="display: inline;">','</p>').'</td>';
                       }
                       elseif($dataArr['type_'.$product_id] == 'other'){
                         $returnArr .= '<td width="15%" role="gridcell" id="issue_item_'.$product_id.'" tabindex="-1" aria-describedby="f2_key">
                        <textarea class="link quentity" name="comment_'.$product_id.'" id="comment_'.$product_id.'">'.$dataArr['comment_'.$product_id].'</textarea>'.form_error('comment_'.$product_id,'<p class="error" style="display: inline;">','</p>').'
                         </td>';
                       }
                       else{
                        $returnArr .= '<td width="15%" role="gridcell" id="issue_item_'.$product_id.'" tabindex="-1" aria-describedby="f2_key"></td>';
                       }
                       $returnArr .= '</tr>';      
                     }
                   }
               }
           }

           echo $returnArr;
       ?>    
       </tbody>
        <?php echo form_error('product_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
       </table>

              
      </div>
      <div class="row">
                <div class="form-group col-sm-4 mt-1">
                        <label class="col-sm-3">Signature <span>*</span></label>
<!--                             <div class="col-sm-9">
                            <div id="signatureparent">
                             <div  id="jsignature" style="border: 1px solid #dedede;"></div>
                             </div> -->
                              <?php //echo form_error('jsignature','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 
                         <!--    </div> -->
                            <div class="col-sm-9">
                           <input type="file" id="inputImage" name="file" accept=".jpg,.jpeg,.png">
                            <?php echo form_error('blob_url','<p class="error" style="color:#ff0000;display: inline;">','</p>')?>
                           </div>
                             <div class="croppedImg"></div>
                           </div>
            </div>
          </div> 
             <input type="hidden" name="id" value="<?php echo $dataArr['id'];?>">       
             <input type="hidden" name="blob_url" id="blob_url" value="">            
                <input type="hidden" value="save" name="actionType">
                <input type="hidden" name="jsignature" id="jsignatureimageUrl" value="">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <!-- <button type="button" onclick="submitAjax360Form('delivery_recept','shipping/delivery_receipt','98%','delivery_note_list');" class="btn btn-success btn-slideright mr-5">Submit</button> -->

                            <button type="button" onclick="saveDeliveryReceipt()" class="btn btn-success btn-slideright mr-5">Save & Next</button>

                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            
 </div>
</div>
</div>
</div>
</div>
<script>


   function saveDeliveryReceipt(){
       var $data = new FormData($('#delivery_recept')[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'shipping/delivery_receipt',
            cache:false,
            data: $data,
             processData: false,
            contentType: false,
            success: function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='100'){
                    $('#modal-view-datatable').modal('show');
                    $('#modal_content').html(obj.data);
                    $(".modal-dialog").css("width", '98%');
                }else{
                   showAjaxModel('Feedback','shipping/delivery_feedback',obj.tmp_delivery_receipt_id,'','98%',' full-width-model');
                }
            }
        });
} 



// $(document).ready(function() {
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
 // })

 $(document).ready(function(){
    convertToExcel();
 })  

$(document).ready(function(){
 checkImage() 
}); 


function checkImage(){
    $('.product_img').change(function(){
        var fileInput = $(this)[0];
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

        if(!allowedExtensions.exec(filePath)){
            alert('Please upload image files only.');
            fileInput.value = '';
            return false;
        }
    });    
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
    

    function showFields(value,id){
      html = '';
      if(value==''){
       html = ''; 
      }
      else if(value=='short_supply' || value=='wrong_supply'){
        html += '<input type="text" class="link quentity " name="supply_qty_'+id+'" id="supply_qty_'+id+'">';
      }
      else if(value=='other'){
        html += '<textarea class="link quentity" name="comment_'+id+'" id="comment_'+id+'"></textarea>';
      }
      else{
        html += '<input  accept="image/*" type="file" name="img_'+id+'" id="img_'+id+'" class="link quentity product_img">';
      }  

      checkImage();
     $('#issue_item_'+id).html(html);  
    }

  // $(document).ready(function(){
  //   $('#inputImg').change(function(){
  //     var URL = window.URL || window.webkitURL;
  //     var $image = $('#image');
  //     var originalImageURL = $image.attr('src');
  //     var uploadedImageName = 'cropped.jpg';
  //     var uploadedImageType = 'image/jpeg';
  //     var uploadedImageURL;
  //     var files = this.files;
  //     var options = {
  //   aspectRatio: 16 / 9,
  //   preview: '.img-preview',
  //   crop: function (e) {
  //     $dataX.val(Math.round(e.detail.x));
  //     $dataY.val(Math.round(e.detail.y));
  //     $dataHeight.val(Math.round(e.detail.height));
  //     $dataWidth.val(Math.round(e.detail.width));
  //     $dataRotate.val(e.detail.rotate);
  //     $dataScaleX.val(e.detail.scaleX);
  //     $dataScaleY.val(e.detail.scaleY);
  //   }
  // };
  //     var file;
  //     if (files && files.length) {
  //       file = files[0];
  //       if (/^image\/\w+$/.test(file.type)) {
  //         uploadedImageName = file.name;
  //         uploadedImageType = file.type;

  //         if (uploadedImageURL) {
  //           URL.revokeObjectURL(uploadedImageURL);
  //         }

  //         uploadedImageURL = URL.createObjectURL(file);
  //         $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
  //         $inputImage.val('');
  //       } else {
  //         window.alert('Please choose an image file.');
  //       }
  //     }
  //   })

  //   $('#saveImg').click(function(){
  //   if (uploadedImageType === 'image/jpeg') {
  //       if (!data.option) {
  //         data.option = {};
  //       }

  //       data.option.fillColor = '#fff';
  //     }

  //   })

  // })

  function closeCrop(){
    $('#blob_url').val('');
    $('#inputImage').val('');
    $('#getCroppedCanvasModal').modal('toggle');
  }

</script>


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