<div id="tour-11" class="header-content">
    <h2><i class="fa fa-cog"></i> <span class="oblc">/</span> <?php echo $heading;?></h2>
    <div class="pull-right" style="margin-top: -28px;">
      <a data-dismiss="modal" class="textbutton" tabindex="0" href="<?php echo base_url('RoleTask/index/'.$zee_id);?>"><span>Back to Role List</span></a>
      </div>
</div>
<?php 
$succMsg = $this->session->flashdata('succMsg');
if(isset($succMsg) && !empty($succMsg)){ ?>
<div class="custom_alert alert alert-success">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <?php echo $succMsg;?></div>
<?php }
?>

<div class="body-content animated fadeIn">
  <div class="panel-body-new">
    <div class = "row">
        <div class = "col-md-12 mb-10">
            <div class="pull-right" >
                <div class="ckbox ckbox-success pull-left mt-10 mr-10" style="margin:0 !important;">
                    <input id='check_all' type="checkbox" name="check_all" value="check_all" class="check_all">
                    <label for="check_all"><strong>Check All</strong></label>
                </div>
            </div>
        </div>
         <div class = "col-md-12">
            <form class="form-horizontal" name="addEditFranchisor" id="addEditFranchisor" method="post" enctype="multipart/form-data">
              <div class="service-notes">
                <?php 
                   $tmp_md_id = '';
                   $tmp_ts_id = '';
                   $tmp_tsk_sctn_id = 0;
                   $i=0;
                   foreach ($roleTasks as $task) { 
                      if(empty($tmp_md_id) || $tmp_md_id !=$task->module_id){
                          echo '<div style="background-color:#49a8df;padding:10px;color:white;">'.$task->module_name.'</div><div style="padding:20px;border: 1px solid #49a8df;margin-bottom:10px;">';
                              $tmp_md_id = $task->module_id;
                        }

                    if(empty($tmp_ts_id) || $tmp_ts_id !=$task->sub_module_id){
                        $tmp_tsk_sctn_id++;
                     ?>
                      <div class="panel rounded shadow panel-teal">
                       <div class="panel-heading">
                        <div class="pull-left">
                         <h3 class="panel-title"><?php echo ucwords(trim($task->sub_module_name)); ?></h3>
                          </div>
                        <div class="pull-right">
                       <div class="ckbox ckbox-success pull-left">
                        <input id='check_all_<?php echo $tmp_tsk_sctn_id.$task->sub_module_id;?>' type="checkbox" name="check_all" value="check_all" task_section_id="<?php echo $tmp_tsk_sctn_id.$task->sub_module_id;?>" class="check_sectionwise">
                        <label for="check_all_<?php echo $tmp_tsk_sctn_id.$task->sub_module_id;?>">Check All</label>
                      </div>
                         <button type="button" class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse" data-original-title="" title=""><i class="fa fa-angle-up"></i></button>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        <div class="panel-body">
                        <?php 
                        $tmp_ts_id = $task->sub_module_id;
                      }
                    $checked = (!empty($task->assigned_task_id) && $task->assigned_task_id == $task->task_id)?'checked':'';        
                  ?>
                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" >
                    <div class="ckbox ckbox-success pull-left">
                      <input class="single_task single_task_<?php echo $tmp_tsk_sctn_id.$task->sub_module_id;?>" <?php echo $checked;?> id='<?php echo $task->task_id;?>' type="checkbox" name="roleTask[]" value="<?php echo $task->task_id;?>">
                                    <label for="<?php echo $task->task_id;?>"><?php echo ucwords($task->name);?></label>
                                </div>
                              </div>
                              <?php if($roleTasks[$i]->sub_module_id != $roleTasks[$i+1]->sub_module_id){
                                ?>
                            </div>
                            </div>
                          <?php }
                          if($roleTasks[$i]->module_id != $roleTasks[$i+1]->module_id) {
                           echo '</div>';
                        }

                        $i++;
                     }
                  ?>
              </div>
               <div class="form-footer" style="padding: 10px 0; background: none; border-top: none;">
                    <div class=""> 
                        <button type="submit" name="save" id="save_btn" class="btn btn-success btn-slideright" value="Save">Save</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.form-footer -->
            </form>


        </div>
    </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.check_all').on('click',function(){
            if(this.checked){
                //$('.single_task:not(:checked)').trigger('click');
                $('.single_task').prop('checked', true);
                $('.check_sectionwise').prop('checked', true);

            } else {
                //$('.single_task:checked').trigger('click');
                $('.single_task').prop('checked', false);
                $('.check_sectionwise').prop('checked', false);
            }
        });

        $('.check_sectionwise').on('click',function(){
            var task_section_id = $(this).attr('task_section_id');
            if(this.checked){
                //$('.single_task:not(:checked)').trigger('click');
                $('.single_task_'+task_section_id).prop('checked', true);

            } else {
                //$('.single_task:checked').trigger('click');
                $('.single_task_'+task_section_id).prop('checked', false);
            }
        });
    });
    
</script>