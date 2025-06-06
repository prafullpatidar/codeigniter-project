<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="pre_import_form" enctype="multipart/form-data" id="pre_import_form" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                  <h4 style="text-align:center;"><strong>Crew Member Details</strong></h4>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Rank</th>
                        <th>DOB/Age</th>
                        <th>Gender</th>
                        <th>Nationality</th>
                        <th>Passport Id</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      
                      if(!empty($crewArr)){?>
                            <tr class="child_row">
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo ucfirst($crewArr['crew_name']);?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr["crew_rank"];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo ConvertDate($crewArr["crew_dob"],'','d-m-Y');?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['crew_gender'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['crew_nationality'];?></td>
                             <td role="gridcell" tabindex="-1" aria-describedby="f2_key"><?php echo $crewArr['crew_passportId'];?></td>
                            </tr>
                        
                        <?php }
                       ?>
                    </tbody>
                  </table>
                <hr/>
                <h5 style="text-align:center;"><strong>Food Habits</strong></h5>
              <div id="abc" class="sip-table mb-15" role="grid">
               <table class="table header-fixed-new table-text-ellipsis table-layout-fixed">
                <thead class="t-header">
                <tr>
                  <th width="28%">Food Group</th>
                  <th class="text-center" width="12%">Never</th>
                  <th class="text-center" width="12%">Daily</th>
                  <th class="text-center" width="12%">2/Week</th>
                  <th class="text-center" width="12%">3/Week</th>
                  <th class="text-center" width="12%">4/Week</th>
                  <th class="text-center" width="12%">Allergies</th>
                  </tr>
                </thead>
                    <tbody class="item_data">
                        <?php
                         if(!empty($crewArr['food_habits'][0])){
                          $label ='';
                          for($i=1;$i<=12;$i++){
                            $habit = (object) $crewArr['food_habits'][0];
                           //echo $i;
                            if($i== 1){
                              $label = 'Meat';
                              $item_never = $habit->meat_never;
                              $item_daily = $habit->meat_daily;
                              $item_2 = $habit->meat_2;
                              $item_3 = $habit->meat_3;
                              $item_4 = $habit->meat_4;
                              $item_allergies = $habit->meat_allergies;

                            }else if($i== 2){
                              $label = 'Pork';
                              $item_never = $habit->pork_never;
                              $item_daily = $habit->pork_daily;
                              $item_2 = $habit->pork_2;
                              $item_3 = $habit->pork_3;
                              $item_4 = $habit->pork_4;
                              $item_allergies = $habit->pork_allergies;
                            }else if($i== 3){
                              $label = 'Beef';
                              $item_never = $habit->beef_never;
                              $item_daily = $habit->beef_daily;
                              $item_2 = $habit->beef_2;
                              $item_3 = $habit->beef_3;
                              $item_4 = $habit->beef_4;
                              $item_allergies = $habit->beef_allergies;
                            }else if($i== 4){
                              $label = 'Fish / Sea Food';
                              $item_never = $habit->fish_never;
                              $item_daily = $habit->fish_daily;
                              $item_2 = $habit->fish_2;
                              $item_3 = $habit->fish_3;
                              $item_4 = $habit->fish_4;
                              $item_allergies = $habit->fish_allergies;
                            }else if($i== 5){
                              $label = 'Mutton';
                              $item_never = $habit->mutton_never;
                              $item_daily = $habit->mutton_daily;
                              $item_2 = $habit->mutton_2;
                              $item_3 = $habit->mutton_3;
                              $item_4 = $habit->mutton_4;
                              $item_allergies = $habit->mutton_allergies;
                            }else if($i== 6){
                              $label = 'Chicken';
                              $item_never = $habit->chicken_never;
                              $item_daily = $habit->chicken_daily;
                              $item_2 = $habit->chicken_2;
                              $item_3 = $habit->chicken_3;
                              $item_4 = $habit->chicken_4;
                              $item_allergies = $habit->chicken_allergies;
                            }else if($i== 7){
                              $label = 'Egg';
                              $item_never = $habit->egg_never;
                              $item_daily = $habit->egg_daily;
                              $item_2 = $habit->egg_2;
                              $item_3 = $habit->egg_3;
                              $item_4 = $habit->egg_4;
                              $item_allergies = $habit->egg_allergies;
                            }else if($i== 8){
                              $label = 'Cereals';
                              $item_never = $habit->cereals_never;
                              $item_daily = $habit->cereals_daily;
                              $item_2 = $habit->cereals_2;
                              $item_3 = $habit->cereals_3;
                              $item_4 = $habit->cereals_4;
                              $item_allergies = $habit->cereals_allergies;
                            }else if($i== 9){
                              $label = 'Dairy Products';
                              $item_never = $habit->dairy_never;
                              $item_daily = $habit->dairy_daily;
                              $item_2 = $habit->dairy_2;
                              $item_3 = $habit->dairy_3;
                              $item_4 = $habit->dairy_4;
                              $item_allergies = $habit->dairy_allergies;
                            }else if($i== 10){
                              $label = 'Vegetables';
                              $item_never = $habit->veg_never;
                              $item_daily = $habit->veg_daily;
                              $item_2 = $habit->veg_2;
                              $item_3 = $habit->veg_3;
                              $item_4 = $habit->veg_4;
                              $item_allergies = $habit->veg_allergies;
                            }else if($i== 11){
                              $label = 'Fruits';
                              $item_never = $habit->fruits_never;
                              $item_daily = $habit->fruits_daily;
                              $item_2 = $habit->fruits_2;
                              $item_3 = $habit->fruits_3;
                              $item_4 = $habit->fruits_4;
                              $item_allergies = $habit->fruits_allergies;
                            }else if($i== 12){
                              $label = 'Sweets';
                              $item_never = $habit->sweets_never;
                              $item_daily = $habit->sweets_daily;
                              $item_2 = $habit->sweets_2;
                              $item_3 = $habit->sweets_3;
                              $item_4 = $habit->sweets_4;
                              $item_allergies = $habit->sweets_allergies;
                            }
                            $foodArr = array($item_never,$item_daily,$item_2,$item_3,$item_4,$item_allergies);
                            $habitCount = array_count_values($foodArr);
                            $yesCount = $habitCount['Y'];
                            $error = 0;
                            $returnArr .= '<tr class="child_row">';
                            $returnArr .= '<td width="28%" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$label.'</td>';
                            if($yesCount>1){
                              $error = 1;
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key" colspan="6"><p style="color:red;text-align:center">All habits can\'t be "Yes" at a time. Check your Import.</p</td>';
                            }else{
                              if($item_never == "Y"){
                                $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_never.'</strong></td>';
                              }else{
                                $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_never.'</td>';
                              }
                            //}
                              
                            if($item_daily == "Y"){
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_daily.'</strong></td>';
                            }else{
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_daily.'</td>';
                            }
                            if($item_2 == "Y"){
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_2.'</strong></td>';
                            }else{
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_2.'</td>';
                            }
                            if($item_3 == "Y"){
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_3.'</strong></td>';
                            }else{
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_3.'</td>';
                            }
                            if($item_4 == "Y"){
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_4.'</strong></td>';
                            }else{
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_4.'</td>';
                            }
                            if($item_allergies == "Y"){
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key"><strong style="color:green">'.$item_allergies.'</strong></td>';
                            }else{
                              $returnArr .= '<td width="12%" align="center" role="gridcell" tabindex="-1" aria-describedby="f2_key">'.$item_allergies.'</td>';
                            }
                          }
                            $returnArr .= '</tr>'; 
                            
                          }
                         }
                                
                           echo $returnArr;    
                      ?>
                    </tbody>
                </table>
         </div>
           </div>
         </div>
            <input type="hidden" name="actionType" id="actionType" value="save">  
       </form>
     </div>
   </div>
 </div>
<div class="clearfix"></div>
  <div class="form-footer">
          <div class="pull-right">
           <a class="btn btn-danger btn-slideright mr-5" href="javascript:void(0)" onclick="showAjaxModel('Import Food Habits','shipping/import_crew_food_habits','<?php echo $crew_id;?>','','70%');">Back to Import</a>
           <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
           <!-- <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitAjax360FormList('pre_import_form','shipping/preview_crew_food_habits','98%','');">Save</button> -->
           <button type="button" id="first_next" class="btn btn-success btn-slideright mr-5" onclick="submitImport();">Save</button>
          </div>
<div class="clearfix"></div>
</div><!-- /.form-footer -->        

<script type="text/javascript">
  var error = '<?php echo $error;?>';
  console.log(error);
    $(document).ready(function(){
       convertToExcel();        
     })

    function submitImport(){
      if(error === 1){
       alert('Remove the Error first');
      }else{
      //return false;
        submitMoldelForm('pre_import_form','shipping/preview_crew_food_habits','98%');
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
    </script>