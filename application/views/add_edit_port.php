<div class="body-content animated fadeIn">
    <div class="row">
    <div class="col-md-12">
        <div class="">
                <div class="panel-body no-padding rounded-bottom">
                    <form class="form-horizontal form-bordered" name="addEditPort" enctype="multipart/form-data" id="addEditPort" method="post">
                    <div class="form-body">
                        <div class="row">
                        <div class="form-group col-sm-12 <?php echo (form_error('name')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Next Port Name <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="name" id="port_name" onchange="getPortandCountryName()" value="<?php if(!empty($dataArr['name'])){echo set_value('name',$dataArr['name']);}?>">
                                <?php echo form_error('name','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('country')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Next Port Country <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="country" id="country" value="<?php if(!empty($dataArr['country'])){echo set_value('country',$dataArr['country']);}?>">
                                <?php echo form_error('country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('agent_id')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Agent  <span>*</span></label>
                            <div class="col-sm-12">
                               <select class="form-control" name="agent_id" id="agent_ids">
                                 <option value="">Select Agent</option>
                                 <?php
                                  if(!empty($agent_list)){
                                    foreach ($agent_list as $row) {
                                      ?>
                                      <option <?php echo ($dataArr['agent_id']==$row->agent_id) ? 'selected' : '';?> value="<?php echo $row->agent_id;?>"><?php echo ucwords($row->name);?></option>
                                    <?php }
                                  }
                                 ?>  
                               </select>
                                <?php echo form_error('agent_id','<p class="error" style="display: inline;">','</p>'); ?>

                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('date')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Next Port Arriving Date <span>*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control datePicker_editPro" name="date" id="date" value="<?php if(!empty($dataArr['date'])){echo convertDate($dataArr['date'],'','d-m-Y');}?>">
                                <?php echo form_error('date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                          <div class="form-group col-sm-12 <?php echo (form_error('prev_port')) ? 'has-error':'';?>" >
                            <!-- <label class="col-sm-12">Previous Port <span>*</span></label> -->
                            <label class="col-sm-12">Previous Port Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="prev_port" id="prev_port" value="<?php if(!empty($dataArr['prev_port'])){echo set_value('prev_port',$dataArr['prev_port']);}?>">
                                <?php echo form_error('prev_port','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 <?php echo (form_error('prev_country')) ? 'has-error':'';?>" >
                            <label class="col-sm-12">Previous Port Country </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="prev_country" id="prev_country" value="<?php if(!empty($dataArr['prev_country'])){echo set_value('prev_country',$dataArr['prev_country']);}?>">
                                <?php echo form_error('prev_country','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 mb-20 <?php echo (form_error('departure_date')) ? 'has-error':'';?>" >
                            <!-- <label class="col-sm-12">Departure Date <span>*</span></label> -->
                             <label class="col-sm-12">Previous Port Departure Date</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control datePicker_editPro" name="departure_date" id="dep_date" value="<?php if(!empty($dataArr['departure_date'])){echo convertDate($dataArr['departure_date'],'','d-m-Y');}?>">
                                <?php echo form_error('departure_date','<p class="error" style="display: inline;">','</p>'); ?>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" value="<?php echo (isset($dataArr['port_id'])) ? $dataArr['port_id'] : '' ;?>" name="id">
                        <input type="hidden" name="second_id" value="<?php echo (isset($dataArr['second_id'])) ? $dataArr['second_id'] : '' ;?>" id="second_id">
                        <input type="hidden" value="save" name="actionType">
 
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                            <button type="button" onclick="submitAjax360Form('addEditPort','shipping/add_edit_port','50%','ports_list')" class="btn btn-success btn-slideright mr-5">Submit</button>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>
<script>
jQuery(document).ready(function(){

    <?php 
        if($agent_id){
            ?>
            var savedData = JSON.parse(sessionStorage.getItem("agentData"));
                $('#port_name').val(savedData[0]);
                $('#country').val(savedData[1]);
                $('#date').val(savedData[2]);
                $('#prev_port').val(savedData[3]);
                $('#prev_country').val(savedData[4]);
                $('#dep_date').val(savedData[5]);
                $('#second_id').val(savedData[6]);
                getPortandCountryName();
        <?php }
    ?>

    $('#date').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
        changeYear:true,
        yearRange: "c-100:c+3"
    });

 $('#dep_date').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: 0,
    });

});

function getPortandCountryName(){
    var keyword = $('#port_name').val();
    $.ajax({
       beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'user/getAgentDetailsByID',
        cache:false,
        data: {'keyword':keyword},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            $('#agent_ids').html(obj.data);
            <?php
             if($agent_id){
                ?>
                $('#agent_ids').val('<?php echo $agent_id ;?>')
             <?php } 
            ?>
            // $('#port_name').val(obj.port_name);
            // $('#country').val(obj.country);
        }
    })
}

$('#agent_ids').change(function(){
    var event = $(this).val();
    if(event == 'add_new'){
        var inputData = [$('#port_name').val(),$('#country').val(),$('#date').val(),$('#prev_port').val(),$('#prev_country').val(),$('#dep_date').val(),$('#second_id').val()];  
        sessionStorage.setItem("agentData", JSON.stringify(inputData)); 
        showAjaxModel('Add Agent','user/addeditagent','','add_port','50%');   
    }
})



</script>