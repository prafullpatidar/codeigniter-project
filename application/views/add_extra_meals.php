<?php 
$user_session_data = getSessionData();
$view = false;
// print_r($dataArr);die;
?>
<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div >
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="extra_meal" enctype="multipart/form-data" id="em_data_form" method="post">
                    <div class="form-body">
                    
              <div id="abc" class="sip-table" role="grid">
                <?php echo  form_error('days','<p class="error">','</p>');?>
               <table class="table fixed-header" border="0" style="width:100%; padding:15px;" Cellpadding="0" Cellpadding="0">
                <thead>
                  <tr>
                    <th>Day</th>
                    <th style="width:10%">Ship Position</th>
                    <th style="width:10%">Full Compliment</th>
                    <th colspan="3">Sign on/off Overlap</th>
                    <th colspan="3">Supernumery</th>
                    <th colspan="3">Port Officials</th>
                    <th colspan="3">Superintendent</th>
                    <th colspan="3">Owners</th>
                    <th colspan="3">Charterers</th>
                    <th colspan="3">Others<br><small>(workshop / riding / squad / It engineers)</small></th>
                  </tr>
                  <tr>
                      <th></th>
                      <th></th>
                      <th>Ship Crew</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                      <th>B</th>
                      <th>L</th>
                      <th>D</th>
                  </tr>
                </thead>
         <tbody class="item_data">
     <?php    
  $k = $ship_crew = $sing_b = $sing_l = $sing_d = $numery_b = $numery_l = $numery_d = $officials_b = $officials_l = $officials_d = $superintendent_b = $superintendent_l = $superintendent_d = $owners_b = $owners_l = $owners_d = $charterers_b = $charterers_l = $charterers_d = $other_b = $other_l = $other_d = 0;
    for ($i=0; $i < $totalDays; $i++) { 
      $k++;
      $ship_crew = $ship_crew + $dataArr['ship_crew'][$k];
      $sing_b = $sing_b + $dataArr['sing_b'][$k];
      $sing_l = $sing_l + $dataArr['sing_l'][$k];
      $sing_d = $sing_d + $dataArr['sing_d'][$k];
      $numery_b = $numery_b + $dataArr['numery_b'][$k];
      $numery_l = $numery_l + $dataArr['numery_l'][$k];
      $numery_d = $numery_d + $dataArr['numery_d'][$k];
      $officials_b = $officials_b + $dataArr['officials_b'][$k];
      $officials_l = $officials_l + $dataArr['officials_l'][$k];
      $officials_d = $officials_d + $dataArr['officials_d'][$k];
      $superintendent_b = $superintendent_b + $dataArr['superintendent_b'][$k];
      $superintendent_l = $superintendent_l + $dataArr['superintendent_l'][$k];
      $superintendent_d = $superintendent_d + $dataArr['superintendent_d'][$k];
      $owners_b =$owners_b + $dataArr['owners_b'][$k];
      $owners_l =$owners_l + $dataArr['owners_l'][$k];
      $owners_d =$owners_d + $dataArr['owners_d'][$k];
      $charterers_b = $charterers_b + $dataArr['charterers_b'][$k];
      $charterers_l = $charterers_l + $dataArr['charterers_l'][$k];
      $charterers_d = $charterers_d + $dataArr['charterers_d'][$k];
      $other_b = $other_b + $dataArr['other_b'][$k];
      $other_l = $other_l + $dataArr['other_l'][$k];
      $other_d = $other_d + $dataArr['other_d'][$k];

      $returnArr .=' <tr>
        <td>'.$k.'</td>
        <td style="width:10%"><input data-inputem="1" type="text" name="ship_port['.$k.']" class="form-control inputem" value="'.$dataArr['ship_port'][$k].'"></td>
        <td style="width:10%"><input data-inputem="1" type="text" name="ship_crew['.$k.']" class="form-control actioncount ship_crew inputem" data-id="ship_crew" value="'.$dataArr['ship_crew'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="sing_b['.$k.']" class="form-control actioncount sing_b inputem" data-id="sing_b" value="'.$dataArr['sing_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="sing_l['.$k.']" class="form-control actioncount sing_l inputem" data-id="sing_l" value="'.$dataArr['sing_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="sing_d['.$k.']" class="form-control actioncount sing_d inputem" data-id="sing_d" value="'.$dataArr['sing_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="numery_b['.$k.']" class="form-control actioncount numery_b inputem" data-id="numery_b" value="'.$dataArr['numery_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="numery_l['.$k.']" class="form-control actioncount numery_l inputem" data-id="numery_l" value="'.$dataArr['numery_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="numery_d['.$k.']" class="form-control actioncount numery_d inputem" data-id="numery_d" value="'.$dataArr['numery_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="officials_b['.$k.']" class="form-control officials_b actioncount inputem" data-id="officials_b" value="'.$dataArr['officials_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="officials_l['.$k.']" class="form-control actioncount officials_l inputem" data-id="officials_l" value="'.$dataArr['officials_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="officials_d['.$k.']" class="form-control actioncount officials_d inputem" data-id="officials_d" value="'.$dataArr['officials_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="superintendent_b['.$k.']" class="form-control actioncount superintendent_b inputem" data-id="superintendent_b" value="'.$dataArr['superintendent_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="superintendent_l['.$k.']" class="form-control actioncount superintendent_l inputem" data-id="superintendent_l" value="'.$dataArr['superintendent_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="superintendent_d['.$k.']" class="form-control actioncount superintendent_d inputem" data-id="superintendent_d" value="'.$dataArr['superintendent_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="owners_b['.$k.']" class="form-control actioncount owners_b inputem" data-id="owners_b" value="'.$dataArr['owners_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="owners_l['.$k.']" class="form-control actioncount owners_l inputem" data-id="owners_l" value="'.$dataArr['owners_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="owners_d['.$k.']" class="form-control owners_d actioncount inputem" data-id="owners_d" value="'.$dataArr['owners_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="charterers_b['.$k.']" class="form-control actioncount charterers_b inputem" data-id="charterers_b" value="'.$dataArr['charterers_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="charterers_l['.$k.']" class="form-control actioncount charterers_l inputem" data-id="charterers_l" value="'.$dataArr['charterers_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="charterers_d['.$k.']" class="form-control actioncount charterers_d inputem" data-id="charterers_d" value="'.$dataArr['charterers_d'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="other_b['.$k.']" class="form-control actioncount other_b inputem" data-id="other_b" value="'.$dataArr['other_b'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="other_l['.$k.']" class="form-control actioncount other_l inputem" data-id="other_l" value="'.$dataArr['other_l'][$k].'"></td>
        <td><input data-inputem="1" type="text" name="other_d['.$k.']" class="form-control actioncount other_d inputem" data-id="other_d" value="'.$dataArr['other_d'][$k].'"></td>
        </tr>';
        }

     $returnArr .= '<tr><td>Total:</td>
                    <td style="width:10%"></td>
                    <td style="width:10%" id="ship_crew">'.(($ship_crew) ? $ship_crew : 0).'</td>
                    <td class="E37" id="sing_b">'.(($sing_b) ? $sing_b : 0).'</td>
                    <td class="E37" id="sing_l">'.(($sing_l) ? $sing_l : 0).'</td>
                    <td class="E37" id="sing_d">'.(($sing_d) ? $sing_d : 0).'</td>
                    <td class="E37" id="numery_b">'.(($numery_b) ? $numery_b : 0).'</td>
                    <td class="E37" id="numery_l">'.(($numery_l) ? $numery_l : 0).'</td>
                    <td class="E37" id="numery_d">'.(($numery_d) ? $numery_d : 0).'</td>
                    <td class="E37" id="officials_b">'.(($officials_b) ? $officials_b : 0).'</td>
                    <td class="E37" id="officials_l">'.(($officials_l) ? $officials_l : 0).'</td>
                    <td class="E37" id="officials_d">'.(($officials_d) ? $officials_d : 0).'</td>
                    <td class="E37" id="superintendent_b">'.(($superintendent_b) ? $superintendent_b : 0).'</td>
                    <td class="E37" id="superintendent_l">'.(($superintendent_l) ? $superintendent_l : 0).'</td>
                    <td class="E37" id="superintendent_d">'.(($superintendent_d) ? $superintendent_d : 0).'</td>
                    <td class="E37" id="owners_b">'.(($owners_b) ? $owners_b : 0).'</td>
                    <td class="E37" id="owners_l">'.(($owners_l) ? $owners_l : 0).'</td>
                    <td class="E37" id="owners_d">'.(($owners_d) ? $owners_d : 0).'</td>
                    <td class="E37" id="charterers_b">'.(($charterers_b) ? $charterers_b : 0).'</td>
                    <td class="E37" id="charterers_l">'.(($charterers_l) ? $charterers_l : 0).'</td>
                    <td class="E37" id="charterers_d">'.(($charterers_d) ? $charterers_d : 0).'</td>
                    <td class="E37" id="other_b">'.(($other_b) ? $other_b : 0).'</td>
                    <td class="E37" id="other_l">'.(($other_l) ? $other_l : 0).'</td>
                    <td class="E37" id="other_d">'.(($other_d) ? $other_d : 0).'</td>
                    </tr>';
                   echo $returnArr; 
                      ?>
              </tbody>
            </table>
          </div>
          <div class="row">
                       <div class="form-group col-sm-6 mt-2" >
                            <label class="col-sm-3">Full Compliment</label><p class="col-sm-9">Officers/Crew</p>
                            <div class="col-sm-12" id="full_compliment">
                            <?php echo $dataArr['full_compliment'];?>
                            </div>
                        </div>
                    </div>


                  <div class="row">            
                          <div class="form-group col-sm-6">
                            <label class="col-sm-3">Extra meals</label><p class="col-sm-9">Full man/days Total number of meals divided by 3 = full man/day</p>
                            <div class="col-sm-12" id="extra_meals_text">
                                <?php echo $dataArr['extra_meals'];?>
                            </div>
                        </div>
                  </div>
                <div class="row">
                         <div class="form-group col-sm-6 <?php echo (form_error('total_mens')) ? 'has-error':'';?>" >
                            <label class="col-sm-3">Total Man/Days</label><p class="col-sm-9">This figure will be used in the calculations</p>
                            <div class="col-sm-12" id="total_man_day">
                                <?php echo $dataArr['total_man_days'];?>
                            </div>
                        </div>
                          <div class="form-group col-sm-4 <?php echo (form_error('master')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Master</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="master" id="master" value="<?php if(!empty($dataArr['master'])){echo set_value('master',$dataArr['master']);}?>">
                            </div>
                        </div>
                    </div>
                    </div>
                    <input type="hidden" name="id" id="extra_meal_id" value="<?php echo $dataArr['extra_meal_id'];?>">
                    <input type="hidden" value="save" name="actionType">
                    <input type="hidden" class="form-control" name="full_compliment" id="compliment" value="<?php echo $dataArr['full_compliment'];?>">
                    <input type="hidden" class="form-control" name="extra_meals" id="extra_meals" value="<?php echo $dataArr['extra_meals'];?>">
                    <input type="hidden" class="form-control" name="total_man_days" id="total_man_days" value="<?php echo $dataArr['total_man_days'];?>">
                    </div><!-- /.form-body -->
                    <!-- <p>Officers/Crew<br>
                                Full man/days Total number of meals divided by 3 = full man/day<br>
                            This figure will be used in the calculations</p> -->
                    <div class="clearfix"></div>
                    <?php 
                     // if(empty($dataArr['is_submitted'])){
                     if($dataArr['status']==0){
                       $view = true;
                     }
                     elseif($user_session_data->code =='super_admin' && $dataArr['status']==1){
                       $view = true; 
                     }
                     
                     if($view){
                    ?>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('em_data_form','shipping/add_extra_meals','98%','extra_meals_html');" class="btn btn-success btn-slideright mr-5">Submit</button>
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
<script type="text/javascript">
  $(document).ready(function(){
    actionCount();
    convertToExcel(); 
 })


   <?php 
     if($view==false){
      ?>
       $("#em_data_form input").prop("disabled", true);
     <?php }
    ?>


 function actionCount(){
   $('.actioncount').change(function(){ 
     var total = 0;
     var extra_meals = 0;
     var full_compliment = 0;
     var id = $(this).data('id');
     $('.'+id).each(function(){
       var val = $(this).val();
       if(val!==''){
         total = (parseFloat(val)+ parseFloat(total));
       }
     })
     
     if(id=='ship_crew'){
      full_compliment = (parseFloat(full_compliment)+parseFloat(total));
      $('#full_compliment').html(full_compliment);
      $('#compliment').val(full_compliment);
     }
     $('#'+id).html(total);

    $('.E37').each(function(){
      var sub_total = parseFloat($(this).text());
      extra_meals = (parseFloat(sub_total) + parseFloat(extra_meals)); 
     })

     var extra_meals = (parseFloat(extra_meals)/parseInt(3));
     extra_meals = extra_meals.toFixed(2);
     // $('#extra_meals_text').html(Math.round(extra_meals)); 
     //  $('#extra_meals').val(Math.round(extra_meals));
    
     //var total_man_day = parseInt(full_compliment)+parseInt(Math.round(extra_meals));
     $('#extra_meals_text').html(extra_meals); 
      $('#extra_meals').val(extra_meals);
      full_compliment = $('#compliment').val();
      var total_man_day = (parseFloat(full_compliment)+parseFloat(extra_meals));
      $('#total_man_day').html(total_man_day);
      $('#total_man_days').val(total_man_day);

   })    
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
                $(this).parent().prev().children("td:last-child").children('input.inputem').focus();
                }else{
                $(this).prev("td").children('input.inputem').focus();break;//left arrow
                              }
          case 39 : var last_cell=$(this).index();
                if(last_cell==cell-1)
                {
                $(this).parent().next().children("td").eq(0).children('input.inputem').focus();
                }
                $(this).next("td").children('input.inputem').focus();break;//right arrow
          case 40 : var child_cell = $(this).index(); 
                $(this).parent().next().children("td").eq(child_cell).children('input.inputem').focus();break;//down arrow
          case 38 : var parent_cell = $(this).index();
                $(this).parent().prev().children("td").eq(parent_cell).children('input.inputem').focus();break;//up arrow
        }
        // if(e.keyCode==113)
        // {
        //   $(this).children().focus();
        // }
      });

      $("td").focusin(function()
      {
        $(this).css("outline","solid steelblue 3px");//animate({'borderWidth': '3px','borderColor': '#f37736'},100);
        //console.log("Hello world!");
        var qnt = $(this).children('input.inputem').data('inputem');
        if(qnt==1){
          $(this).children('input.inputem').focus();
        }
      });

      $("td").focusout(function()
      {
        $(this).css("outline","none");
      });

    };

       $('.actioncount').on('input', function(e) {
                // Get the input value
                var inputValue = $(this).val();
                
                // Remove any non-numeric and non-decimal characters
                var numericValue = inputValue.replace(/[^0-9.]/g, '');

                // Update the input field value
                $(this).val(numericValue);
            });  
</script>
