<?php     
 $user_session_data = getSessionData();
 $change_password = checkLabelByTask('change_password');
 ?>
<ul class="nav nav-tabs nav-pills">
        <li class="nav-border nav-border-top-primary <?php echo ($active=='edit_profile') ? 'active' : '';?>">
            <a href="<?php echo base_url() . 'user/updateProfile/'.base64_encode($user_session_data->user_id); ?>" aria-expanded="true" <?php echo ($active=='edit_profile') ? 'data-toggle="tab"' : '';?>>
                
                <div>
<!--                    <span>User Profile</span>-->
                    <span>Profile</span> <i class="fa fa-angle-right" aria-hidden="true"></i>

                </div>
            </a>
        </li>
        <?php 
         if($change_password){
        ?>
         <li class="nav-border nav-border-top-primary <?php echo ($active=='change_password') ? 'active' : '';?>">
            <a href="<?php echo base_url() . 'user/changePassword'; ?>" aria-expanded="true" <?php echo ($active=='change_password') ? 'data-toggle="tab"' : '';?>>
                
                <div>
<!--                    <span>User Profile</span>-->
                    <span>Change Password</span> <i class="fa fa-angle-right" aria-hidden="true"></i>

                </div>
            </a>
        </li>
    <?php } ?>
</ul>