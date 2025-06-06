<style>
.error
{
  border:1px solid red !important;
}
.new_error{
  color: red;
}

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
<!-- <script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/moment.js"></script>
<script src="<?php echo base_url()?>/assets/assets/global/plugins/datetimepicker/datetimepicker.js"></script> -->
    <form class="h-100 d-flex flex-column flex-no-wrap" name="quote_form" enctype="multipart/form-data" id="quote_form" method="post">

 <div class="body-content panel-body animated fadeIn body-content-flex" id="stock_form">
         <div class="row">
            <div class="form-group col-sm-2">
                 <label class="col-sm-12 ">Port Name: <strong><?php echo $port_name;?></strong></label>
                        
             </div>
             <div class="form-group col-sm-4 d-flex align-center">
                 <label class="col-sm-4 mb-0">Lead Time (Days)<span>*</span></label>
                    <div class="col-sm-8">
                      <input type="number" min="0" name="lead_time" id="datetimepicker" class="form-control datetimepicker" value="<?php echo $lead_time;?>">
                       <?php echo form_error('lead_time','<p class="new_error">','</p>')?>        
                    </div>
             </div>
            </div>
             
         </div>
                <?php echo form_error('qt_product_id','<p class="error" style="color:#E9573F;display: inline;">','</p>')?>
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <div id="abc" class="sip-table" role="grid">
               <table class="header-fixed-new table-text-ellipsis table white-space-nowrap" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead class="t-header">
                <tr>
                  <th width="8%">Item No.</th>
                  <th width="21%">Description</th>
                  <th width="8%">Unit</th>
                  <th width="8%">RFQ QTY</th>
                  <th width="15%">RFQ Remark</th>
                  <th width="10%">Vendor QTY</th>
                  <th width="10%">Unit Price ($)</th>
                  <th width="10%">Total Price($)</th>
                  <th width="10%">Vendor Remark</th>
                  <th width="10%">Attached File</th>
                  <th width="10%" id="browse_item">Attachment</th>
                  </tr>
                </thead>
                    <tbody class="item_data">
                        <?php
                         if(!empty($productArr)){
                            $qty_total = 0;
                            $price_total = 0;

                                foreach ($productArr as $parent => $rows) {
                                    foreach($rows as $category => $products){
                                         $returnArr .= '<tr class="parent_row">
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="21%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$category.'</td>
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>                                 
                                                </tr>';
                                            for ($i=0; $i <count($products) ; $i++) { 

                                             // $qty = ($products[$i]['vendor_qty']) ? number_format($products[$i]['vendor_qty'],2) : '';

                                             // $unit_price = ($products[$i]['unit_price'])  ? number_format($products[$i]['unit_price'],2) : ''; 


                                            $qty = $products[$i]['vendor_qty'];
                                            $unit_price = $products[$i]['unit_price'];   

                                             $qty_total+= $qty;
                                             $price_total += ($qty*$unit_price);
                                               
                                                 $returnArr .= '<tr class="child_row">';
                                                 $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['item_no'].'</td>';
                                                  $returnArr .= '<td width="21%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.ucfirst($products[$i]['product_name']).'</td>';
                                                  $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.strtoupper($products[$i]['unit']).'</td>';
                                               $returnArr .= '<td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['quantity'].'</td>';
                                              $returnArr .= '<td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$products[$i]['remark'].'</td>';
                                                $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity count_qty valid_data '.(form_error("qty_".$products[$i]['product_id']) ? 'error' : '').'" data-quantity="1" name="qty_'.$products[$i]['product_id'].'" value="'.$qty.'" id="qty_'.$products[$i]['product_id'].'"></td>';

                                                $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input onkeyup="qtQtyPrcChange('.$products[$i]['product_id'].');" type="text" class="link quentity valid_data '.(form_error("unit_price_".$products[$i]['product_id']) ? 'error' : '').'" data-quantity="1" name="unit_price_'.$products[$i]['product_id'].'" value="'.$unit_price.'" id="unit_price_'.$products[$i]['product_id'].'"></td>';
                                                $price = (!empty($qty) && !empty($unit_price)) ?  number_format(($qty * $unit_price),2) : '';
                                                $returnArr .= '<td width="10%" role="gridcell" class="total_price" tabindex="-1" id="calPrice_'.$products[$i]['product_id'].'" aria-describedby="f2_key">'.$price.'</td>';
                                                 $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input type="text" class="link quentity" data-quantity="1" name="remark_'.$products[$i]['product_id'].'" value="'.$products[$i]['vendor_remark'].'" id="remark_'.$products[$i]['product_id'].'"></td>';
                                                 if(!empty($products[$i]['attechment'])){
                                                   $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"><div id="thumbnail-container"><img id="imgPreview_'.$products[$i]['product_id'].'"  class="thumbnail" alt="Sample Image" src="'.base_url().'uploads/vendor_quote/'.$products[$i]['attechment'].'"></div></td>';
                                                 }else{
                                                  $returnArr .= '<td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"> <img id="imgPreview_'.$products[$i]['product_id'].'" width="50" height="50" style="float:left;display:none;"></td>';
                                                 }
                                                
                                                $returnArr .= '<td class="browse_item" width="20%" role="gridcell" tabindex="-1" aria-describedby="f2_key"> <input accept="image/*" type="file" class="link quentity photo" data-quantity="1" data-id="'.$products[$i]['product_id'].'"  name="img_'.$products[$i]['product_id'].'" id="img_'.$products[$i]['product_id'].'"></td>';
                                                $returnArr .= '</tr>'; 
                                            }
                                       }
                                    }

                                    $returnArr .= '<tr class="parent_row">
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="21%" role="gridcell" tabindex="-1" aria-describedby="f2_key">Grand Total</td>
                                                 <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>
                                                <td width="8%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="qty_total">'.$qty_total.'</td>
                                                <td width="15%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key" id="price_total">'.$price_total.'</td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td> 
                                                <td width="10%" role="gridcell" tabindex="-1" aria-describedby="f2_key"></td>                                 
                                                </tr>'; 
                               }
                           echo $returnArr;    
                      ?>
                    </tbody>
                </table>
         </div>
           </div>
         </div>
            <input type="hidden" name="id" value="<?php echo $vendor_quote_id;?>">
            <input type="hidden" name="actionType" id="actionType" value="save">  
       </form>
     
<div class="clearfix"></div>
<?php
if(empty($second_id)){
?>
  <div class="form-footer">
          <div class="pull-right">
           <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
           <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitMoldelForm('quote_form','vendor/add_edit_quote/save','98%');">Save & Close</button>
           <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitMoldelForm('quote_form','vendor/add_edit_quote/submit','98%');" title="After submit you are not able to edit any item">Submit</button>
          </div>
<div class="clearfix"></div>
<?php }?>
</div><!-- /.form-footer -->        

<script type="text/javascript">


// $(document).ready(function(){
//     $('#datetimepicker').datetimepicker({
//         format: 'DD-MM-YYYY hh:mm A'
//     }
//    );
// });   


 $('.valid_data').change(function(){
   var qty = 0;
   var total_price = 0;
   $('.count_qty').each(function(){
     if(parseFloat($(this).val())){
       qty+= parseFloat($(this).val());
      }
   })

   $('.total_price').each(function(){
     if(parseFloat($(this).text())){
       total_price+= parseFloat($(this).text());
      }
   })

   $('#qty_total').html(qty);
   $('#price_total').html(total_price);

 })


$(document).ready(function(){
    $('.photo').change(function(){
        var fileInput = $(this)[0];
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

        if(!allowedExtensions.exec(filePath)){
            alert('Please upload image files only.');
            fileInput.value = '';
            return false;
        }
    });
}); 


$(document).ready(()=>{
    $('.photo').change(function(){
      let id = $(this).data('id');
        const file = this.files[0];
        console.log(file);
        if (file){
        let reader = new FileReader();
        reader.onload = function(event){
            console.log(event.target.result);
            $('#imgPreview_'+id).attr('src', event.target.result);
        }
        reader.readAsDataURL(file);
        }
        $('#imgPreview_'+id).show();
    });
 });


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

    function qtQtyPrcChange(p_id){
        var unit_price = $('#unit_price_'+p_id).val();
        var qty = $('#qty_'+p_id).val();
        if(parseFloat(unit_price) && parseFloat(qty)){
          $('#calPrice_'+p_id).text(parseFloat(parseFloat(unit_price,10).toFixed(2)*parseFloat(qty,10).toFixed(2),10).toFixed(2));
        }
        else{
          $('#calPrice_'+p_id).text(0);
        } 
    }
    $(document).ready(function(){
       convertToExcel();        
     })
   
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


     $(document).ready(function () {       
             $('.valid_data').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            }); 

       $('.error').focusin();        
 });   
    </script>



<?php
if(!empty($second_id)){
?>
<script type="text/javascript">
    $(document).ready(function() {
    $('#quote_form :input').prop('disabled', true);
    $('#browse_item').hide();
    $('.browse_item').hide(); 
  });
</script>
<?php }?>    
