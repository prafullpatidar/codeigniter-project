<?php
 checkUserSession();
 $user_session_data = getSessionData();
 $countNotify = getNotificationCount($user_session_data->user_id);
 $view_notification = checkLabelByTask('view_notification');
 $update_profile = checkLabelByTask('update_profile');
?>
<style>

    .header-left .navbar-header .navbar-brand img.logo-image { max-height:100%; max-width: 100%; width: auto; margin-top: 2px;}

    /* Tooltip */

    /* Button */
    .mybox .closeBox { text-align: right; padding: 3px 10px; }
    .mybox .closeBox .btn{background-color: #E9573F; color: #2A2A2A !important; padding: 3px 10px; border: none;}

    /* toggle */
    .mybox
    {
        background: #eeeeef none repeat scroll 0 0;
        border: 1px solid #a29415;
        box-shadow: 0 2px 16px 1px;
        left: auto;
        margin: auto;
        position: absolute;
        right: 0;
        top: 53.2px;
        width: calc(100% - 220px);
        z-index: 1000;
    }
    .mybox-inner
    {
        padding: 0 10px;
    }
    .mybox-inner .release_sec:last-child { margin-bottom: 0 }
    .release_sec
    {
        background: #f9f9f9; border: 1px solid #cedff2; padding: 5px; margin-bottom: 10px;
    }
    .toggle_heading { padding: 5px; background: #9e9e9e; color:#fff;}
    .toggle_body  { padding: 5px 10px; }
    .toggle_body ul { padding: 0 0 0 20px; }


    .datepicker-months .table-condensed,.datepicker-years .table-condensed,.datepicker-decades .table-condensed{
        width: 350px !important;
    }
    .datepicker-months .table-condensed,.datepicker-years .table-condensed{
        width: 250px !important;
    }
    .dt-button-collection,.daterangepicker{ z-index: 999999 !important; }
    .fdzor-select {
        /* margin-top: 25px; margin-left:  7px;   border: none;*/background:  #fff; padding:0; display: inline-block; border-radius: 3px;
    }
    .fdzor-select:focus{ /*border: none;*/ outline: none; }
    .navbar-toolbar .navbar-right .dropdown > .fdzor-select+a {padding: 0;
                                                               background: none;
                                                               line-height: normal;
                                                               height: auto;
                                                               color: #369cd9;
                                                               border-radius: 3px;
                                                               display: block;
                                                               font-size: 10px; margin-top: 2px; font-weight: 500;}
    .navbar-toolbar .navbar-right .dropdown label{    display: block;
                                                      margin-bottom: 0; margin-top: 10px; line-height:normal;font-size: 11px;}
    .navbar-toolbar .navbar-right .dropdown > .fdzor-select+a:hover  { color: #000; }
.glbSrchDeactive{color:#c2c2c2;}
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $(".slide-toggle").click(function () {
            $(".mybox").slideToggle();
        });

        $(document).on('click', ".close_textarea_img_popup", function () {
            $(".textarea_img_popup").modal('hide');
        });

    });
    // $(window).scroll(function(){
    //     if ($(window).scrollTop() >= 270) {
    //         $('table thead').addClass('theadFix');
    //     }
    //     else {
    //         $('table thead').removeClass('theadFix');
    //     }
    // });

    // ;(function($) {
    //     $.fn.fixMe = function() {
    //         return this.each(function() {
    //             var $this = $(this),
    //                 $t_fixed;
    //             function init() {
    //                 $this.wrap('<div class="theadContainer" />');
    //                 $t_fixed = $this.clone();
    //                 $t_fixed.find("tbody").remove().end().addClass("theadFixed").insertBefore($this);
    //                 resizeFixed();
    //             }
    //             function resizeFixed() {
    //                 $t_fixed.find("th").each(function(index) {
    //                 $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
    //                 });
    //             }
    //             function scrollFixed() {
    //                 var offset = $(this).scrollTop(),
    //                 tableOffsetTop = $this.offset().top,
    //                 tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
    //                 if(offset < tableOffsetTop || offset > tableOffsetBottom)
    //                 $t_fixed.hide();
    //                 else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
    //                 $t_fixed.show();
    //             }
    //             $(window).resize(resizeFixed);
    //             $(window).scroll(scrollFixed);
    //             init();
    //         });
    //     };
    //     })(jQuery);

    //     $(document).ready(function(){
    //         $("table").fixMe();
           
    //     });


</script>
<header id="header">
     <div class="header-left">
         <div class="navbar-minimize-mobile left">
            <i class="fa fa-bars"></i>
        </div>
          <div class="navbar-header">
              <a id="tour-1" class="navbar-brand"><img height="50px" src="<?php echo base_url(); ?>assets/images/company_logo.png" /></a>
            </div><!-- /.navbar-header -->
        <!--/ End navbar header -->
            <div class="navbar-minimize-mobile right">
            <i class="fa fa-cog"></i>
        </div>
        <!--/ End offcanvas right -->

        <div class="clearfix"></div>
    </div><!-- /.header-left -->
    <!--/ End header left -->

    <!-- Start header right -->
    <div class="header-right">
        <!-- Start navbar toolbar -->
        <div class="navbar navbar-toolbar">
       <ul class="nav navbar-nav navbar-left">
                <!-- Start sidebar shrink title="Minimize sidebar" -->
                <li id="tour-2" class="navbar-minimize">
                    <a href="javascript:void(0);">
                       <img src="<?php echo base_url(); ?>assets/images/toggle-menu.png" /> <!--<i class="fa fa-bars"></i>
                        -->                                </a>
                </li>
                <!--/ End sidebar shrink -->
            </ul><!-- /.nav navbar-nav navbar-left -->
            <!--/ End left navigation -->
            <!-- Start right navigation -->
      <ul class="nav navbar-nav navbar-right">
 <!--        <li id="tour-4" class="dropdown navbar-message notification  "><a href=""><i class="fas fa-history"></i></a></li> -->
          <?php if($view_notification){?>
          <li id="tour-5" class="dropdown navbar-message notification">
            <a title="Notification" href="#" id="tour-5-toggle" class="dropdown-toggle" data-toggle="dropdown" onclick="notificationList()"><i class="fa fa-bell-o" aria-hidden="true"></i>
                 <span class="count label label-danger rounded userFeedbackNotiCountLabel"><?php echo (($countNotify>0) ? '<span class="spn_digit ">'.$countNotify.'</span>' : '');?></span> 
                         <div class="new-notification userFeedbackNotiCountTooltip">4 New Feedback</div> 
                         </a>
                           <!-- Start dropdown menu -->
                            <div class="dropdown-menu animated flipInX notes-flip" id="release_feedback_dv">
                                <div class="dropdown-header">
                                    <span class="title"><!--User feedback on upcoming Release Notes--> Notification<strong></strong></span><span class="option text-right"></span>
                                </div>
                                <div class="dropdown-body">
                                    <!-- Start message list -->
                                    <div class="media-list niceScroll" id="scrollingDiv">
                                        <div id="notification_window" class="notification-window">
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-footer">
                                    <a  href="<?php echo base_url().'user/notificationList'?>">See All</a>
                                </div>
                            </div>
                            <input type="hidden" name="" id="cur_page" value="1">
                            <!--/ End dropdown menu -->
                        </li>
                    <?php } ?>
                      <li id="tour-6" class="dropdown navbar-profile">
                       <a title="Account" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="meta">
                            
                            <span class="text hidden-xs hidden-sm text-muted"><?php echo (!empty($user_session_data->user_name)) ? ucfirst($user_session_data->user_name) : ucfirst($user_session_data->user_name1); ?></span>
                            <span class="caret"></span>
                            <span class="avatar">
                                    <?php
                                    if (!empty($user_session_data->img_url)) {
                                         echo '<img src="'.base_url('uploads/user/'.$user_session_data->img_url).'" class="img-circle" title="'.$user_session_data->user_name.'">';
                                    } else {
                                        $initial_name = $user_session_data->first_name;
                                        $initial_name = explode(' ', $initial_name);
                                        
                                    ?>
                                    <div class="initialChar">
                                    <?php
                                        echo (!empty($initial_name[0][0])) ? ucfirst($initial_name[0][0]) : '';
                                        echo (!empty($initial_name[1][0])) ? ucfirst($initial_name[1][0]) : '';
                                        ?></div><?php }
                                    ?>
                            </span>
                        </span>
                    </a>
                    <!-- Start dropdown menu -->
                    <ul class="dropdown-menu animated flipInX">
                        <li class="dropdown-header">Account</li>
                        <?php 
                        if($update_profile){?>
                        <li><a class=""  href="<?php echo base_url().'user/updateProfile/'. base64_encode($user_session_data->user_id);?>" ><i class="fa fa-user"  aria-hidden="true"></i>Update profile</a></li>
                       <?php } ?>
                        <li><a id="sign-out" href="#" onClick="logoutUser();"><i class="fa fa-sign-out"></i>Logout</a></li>
                        <label hidden id="sign-in"></label> 
                    </ul>
                    <!--/ End dropdown menu -->
                </li><!-- /.dropdown navbar-profile -->
                <!--/ End profile -->
                 </ul>
            </div><!-- /.navbar-toolbar -->
        <!--/ End navbar toolbar -->
    </div><!-- /.header-right -->
    <!--/ End header left -->
 <!--slider data-->
    <div class="mybox" style="display: none;">
        <div class="closeBox">
            <span class="mysuggestion">

            </span>
            <input type="button" class="btn btn-default slide-toggle"  value="Close">
        </div>

        <div class="mybox-inner"></div>
        <div class="closeBox">
            <input type="button" class="btn btn-default slide-toggle"  value="Close">
        </div>
    </div>
</header> <!-- /#header -->
<!--/ END HEADER -->

<style>
    #customLoader {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.5);
        height: 100%;
        left: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999980;
        text-align: center;
        padding-top: 25%;
    }
</style>
<div id="customLoader" style="display: none;">
    <img src="<?php echo base_url() ?>/assets/images/loader.gif" width="31" height="31" >
</div>
<style>
    #customLoader1 {
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.5);
        height: 100%;
        left: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999980;
        text-align: center;
        padding-top: 25%;
    }
</style>
<div id="customLoader1" style="display: none;">
    <img src="<?php echo base_url() ?>/assets/images/loader.gif" width="31" height="31" >
</div>
<script type="text/javascript">
     function logoutUser()
    {
        $.ajax({
            url: base_url + 'user/logout',
            cache: false,
            success: function () {
                window.location = base_url;
            }
        });
    }
     var feedback_container = 'N';
    $(document).ready(function () {
        $('#release_feedback_dv, #rn_head_dv, #rn_foot_dv').on("click", function () {
            feedback_container = 'Y';})
    })

    $(document).ready(function () {
        
        $('#tour-3,#tour-5,#tour-15-toggle').data('open', false);
        $('#tour-3-toggle,#tour-5-toggle,#tour-15-toggle').click(function () {
            $this = $(this);
            if ($this.attr('id') == 'tour-3-toggle') {
                if ($('#tour-3').data('open')) {
                    $('#tour-3,#tour-15').data('open', false);
                    $('.tglClsTopicon').addClass('activeIcon');
                    $('#tour-3-toggle, #tour-5-toggle, #tour-15-toggle').css('background-color', '#fff;');
                } else {
                    $('#tour-3').data('open', true);
                    $('#tour-5,#tour-15').data('open', false);
                    $('.tglClsTopicon').removeClass('activeIcon');
                    $('#tour-3-toggle').css('background-color', '#eee;');
                    $('#tour-5-toggle, #tour-15-toggle').css('background-color', '#fff;');
                }
            } else if ($this.attr('id') == 'tour-5-toggle') {
                if ($('#tour-5').data('open')) {
                    $('#tour-5').data('open', false);
                    $('.tglClsTopicon').addClass('activeIcon');
                    $('#tour-3-toggle, #tour-5-toggle, #tour-15-toggle').css('background-color', '#fff;');
                } else {
                    $('#tour-5').data('open', true);
                    $('#tour-3,#tour-15').data('open', false);
                    $('.tglClsTopicon').removeClass('activeIcon');
                    $('#tour-5-toggle').css('background-color', '#eee;');
                    $('#tour-3-toggle,#tour-15-toggle').css('background-color', '#fff;');
                }
            } else if ($this.attr('id') == 'tour-15-toggle') {
                if ($('#tour-15').data('open')) {
                    $('#tour-15').data('open', false);
                    $('.tglClsTopicon').addClass('activeIcon');
                    $('#tour-3-toggle, #tour-5-toggle, #tour-15-toggle').css('background-color', '#fff;');
                } else {
                    $('#tour-15').data('open', true);
                    $('#tour-3,#tour-5').data('open', false);
                    $('.tglClsTopicon').removeClass('activeIcon');
                    $('#tour-15-toggle').css('background-color', '#eee;');
                    $('#tour-3-toggle,#tour-5-toggle').css('background-color', '#fff;');
                }
            }
        });

        $(document).click(function () {
            $this = $(this);
            if ($('#tour-3').data('open')) {
                $('#tour-3').data('open', false);
                $('.tglClsTopicon').addClass('activeIcon');
                $('#tour-3-toggle').css('background-color', '#fff');
            }
            if ($('#tour-5').data('open')) {
                $('#tour-5').data('open', false);
                $('.tglClsTopicon').addClass('activeIcon');
                $('#tour-5-toggle').css('background-color', '#fff');
            }
            if($('#tour-15').data('open') && $('#tour-15 .dropdown-menu').css('display')=='none') {
             $('#tour-15').data('open', false);
             $('.tglClsTopicon').addClass('activeIcon');
             $('#tour-15-toggle').css('background-color','#fff');
            }
        });
        
    });


   function notificationList(user_id=''){
     $('.mybox-inner').html('');
     var cur_page = $('#cur_page').val();
        $.ajax({
            type: "POST",
            url: base_url + 'user/toggleNotificationList',
            cache: false,
            data: {'cur_page': cur_page},
            dataType : 'JSON',
            success: function (msg){
                $("#customLoader").hide();
                $('#notification_window').html(msg.html);
            }
        });
        return false;
   } 
  
 // $(document).ready(function() {
 //    $('#scrollingDiv').scroll(function(){
 //      if(parseInt($(this).scrollTop()) + parseInt($(this).innerHeight()) >= parseInt($(this)[0].scrollHeight)-1) {
 //          var cur_page = $('#cur_page').val();
 //           new_cur_page = parseInt(cur_page) + 1;    
 //          $.ajax({
 //            beforeSend : function(){
 //               $('#notification_window').append('<div class="custLoad">Loading...</div>');            },
 //            type: "POST",
 //            url: base_url + 'user/toggleNotificationList',
 //            cache: false,
 //            data: {'cur_page':new_cur_page},
 //            dataType : 'JSON',
 //            success: function (msg){
 //                $('.custLoad').remove();
 //                if(msg.html){
 //                  $('#cur_page').val(new_cur_page);
 //                }
 //                $('#notification_window').append(msg.html);
 //            }
 //        });
 //      }
 //     });
 //   });


  function update_notification(notification_id){
    if(notification_id){
     $.ajax({
      beforeSend : function(){
      },
      type: "POST",
            url: base_url + 'user/update_notification',
            cache: false,
            data: {'notification_id':notification_id},
            dataType : 'JSON',
            success: function (msg){
                $('.count_'+notification_id).removeClass('notification-unread');
               updateNotificationCount();
            }
        });
    }
  } 


function updateNotificationCount(){
     $.ajax({
       beforeSend: function(){
        },
        type: "POST",
        url: base_url + 'user/getNotificationCount',
        cache:false,
        success: function(msg){
           var obj = jQuery.parseJSON(msg);
           if(obj.count>0){
            $('.spn_digit').remove();  
            $('.userFeedbackNotiCountLabel').html('<span class="spn_digit">'+obj.count+'</span>');
           }
           else{
            $('.spn_digit').remove(); 
           }
        }
      })  
  }


  $(document).ready(function(){
    setInterval(function(){
      updateNotificationCount();
    },500000)
  })
   
</script>