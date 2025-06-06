<style type="text/css">
    span.field-icon {
    /*float: right;*/
    /*position: absolute;*/
    /*right: 10px;*/
    /*top: 10px;*/
    cursor: pointer;
    z-index: 2;
}
</style>
<script type='text/javascript'>
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	console.log(img.src);
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	//img.src = <?php echo base_url(); ?>.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
<script>
$(document).ready(function() {
  $('.loading').removeClass('redBG').addClass('loading_done');
});
</script>
<style>
.loading {transform: scale(0);transition: all .5s ease-in-out; -webkit-transition:all .5s ease-in-out;
-moz-transition:all .5s ease-in-out; -o-transition:all .5s ease-in-out; }
.loading_done {transform: scale(1);}

/*.xp-login ul li.btn { background:#f7a51d; border:#f7a51d  thin solid;}*/
</style>
<!-- Login form -->

<form class="sign-in form-horizontal shadow rounded no-overflow loading" action="" method="post">
    <div class="sign-header">
        <div class="form-group">
            <div class="sign-text">
                <span id="heading"><?php echo $heading; ?></span>
            </div>
        </div><!-- /.form-group -->
    </div><!-- /.sign-header -->    
    <div id="login_div" <?php if($this->input->post('forgot_btn')!='' || $this->input->post('forgot_username_btn')!='') { ?>style="display:none"<?php } ?>>
        <div class="sign-body">
            <div class="form-group">
                <div class="input-group input-group-lg rounded no-overflow">
                    <input type="text" class="form-control input-sm" placeholder="Username" name="username" value="<?php echo (!empty($user_info->user_name))?$user_info->user_name:'';?>">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                </div>
                <div class="errorMsg"><?php echo form_error('username')?></div>
            </div><!-- /.form-group -->
            <div class="form-group has-error">
                <div class="input-group input-group-lg rounded no-overflow">
                    <input type="password" class="form-control input-sm" placeholder="Password" name="password" id='password'>
                    <span toggle="#password" class="input-group-addon field-icon toggle-password "><i class="fa fa-eye" aria-hidden="true"></i></span>
                    <!-- <span class="input-group-addon"><i class="fa fa-lock"></i></span> -->
                   
                </div> 
                <div class="errorMsg"><?php echo form_error('password')?></div>
            </div><!-- /.form-group -->
        </div><!-- /.sign-body -->
        <div class="sign-footer">
            <div class="form-group">
                <button type="submit" name="login_btn" value="login_btn" class="btn btn-success btn-slideright btn-lg btn-block no-margin rounded" id="login-btn">Sign In</button>
            </div>
        </div><!-- /.sign-footer -->
    </div>
</form>
<div>


</span></div>
<!-- /.form-horizontal -->
<!--/ Login form -->
<script type="text/javascript">
    var clicked = 0;

  $(".toggle-password").click(function (e) {
     e.preventDefault();

    $(this).toggleClass("toggle-password");
      if (clicked == 0) {
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
         clicked = 1;
      } else {
         $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
          clicked = 0;
       }

    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
       input.attr("type", "text");
    } else {
       input.attr("type", "password");
    }
});
</script>