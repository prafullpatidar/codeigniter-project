function highlightRow(){
        const params = new URLSearchParams(window.location.search);
        const highlightId = params.get("highlight_id");
        if (highlightId) {
            const $row = $("#row-" + highlightId);
            if ($row.length) {
                $row.addClass("highlight");
                $('html, body').animate({
                    scrollTop: $row.offset().top - 100
                }, 800);
                setTimeout(() => $row.removeClass('highlight'), 3000);
            }
        }
}

function showAjaxModel(model_head,page_url,id,second_id,customWidth,customClass)
{ 
    $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + page_url,
        cache:false,
        data: {'id':id,'second_id':second_id},
        success: function(msg){
            $("#customLoader").hide();
            var obj = jQuery.parseJSON(msg);
            if(obj.status=='100'){
                $('#modal-view-datatable').modal({backdrop: 'static', keyboard: false,show:true});
                $('#modal-view-datatable').modal('show');
                $('#pop_heading').html(model_head);
                $('#modal_content').html(obj.data);
                if(customWidth){
                    $(".modal-dialog").css("width", customWidth);
                }else{
                    $(".modal-dialog").css("width", "");
                }

                if(customClass){
                    $("#modal-view-datatable").addClass(customClass);
                }
                else{
                    $("#modal-view-datatable").removeClass('full-width-model');
                }
            }else{
                location.reload();
            }
        }
    });
}

function submitMoldelForm(form_id,page_url,customWidth){
    if(form_id!=''){
        var $data = new FormData($('#'+form_id)[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + page_url,
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
                    if(customWidth){
                        $(".modal-dialog").css("width", customWidth);
                    }else{
                            $(".modal-dialog").css("width", "");
                    }
                }else{
                    location.reload();
                }
            }
        });
    }
}

function updateStatusBoxDelete(id, status, title, changeStatusPath,changeStatusFor) {
    if (id != '' && title != '' && changeStatusPath != '') {
      var msg = 'Are you sure you want to Delete "' + title +'"?';
        bootbox.dialog({
            message: msg,
            title: "Confirmation",
            className: "modal-primary",
            buttons: {
                danger: {
                    label: "No",
                    className: "btn-danger btn-slideright mLeft",
                    callback: function () {}
                },
                success: {
                    label: "Yes",
                    className: "btn-success btn-slideright",
                    callback: function () {
                        $.ajax({
                            type: "POST",
                            url: base_url + changeStatusPath,
                            cache: false,
                            data: {'id': id, 'status': status},
                            success: function (msg) {
                                var obj = jQuery.parseJSON(msg);
                               $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                               submitPortTableForm();
                            }
                        });
                    }
                }

            }
        });
    }
}

function Delete(id,status,title,changeStatusPath){
   if (id != '' && title != '' && changeStatusPath != '') {
        bootbox.dialog({
            message: 'Are you sure you want to Delete "' + title +'"?',
            title: "Confirmation",
            className: "modal-primary",
            buttons: {
                danger: {
                    label: "No",
                    className: "btn-danger btn-slideright mLeft",
                    callback: function () {}
                },
                success: {
                    label: "Yes",
                    className: "btn-success btn-slideright",
                    callback: function () {
                        $.ajax({
                            type: "POST",
                            url: base_url + changeStatusPath,
                            cache: false,
                            data: {'id': id, 'status': status},
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                }

            }
        });
    }
}

function submitAjax360Form(form_id,page_url,customWidth,stringFunction){
        var $data = new FormData($('#'+form_id)[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + page_url,
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
                   if(customWidth){
                    $(".modal-dialog").css("width", customWidth);
                    }else{
                        $(".modal-dialog").css("width", "");
                    }
                }else{
                   $('#modal-view-datatable').modal('hide');
                   $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                   //submitGetAllShipsform('1',stringFunction);
                    setTimeout(function(){
                         $('.custom_alert').remove();
                    },3000)

                   evaluateFunction(stringFunction);
                }
            }
        });
}


function evaluateFunction(stringFunction){
      if(stringFunction){
        param ='';
        window[stringFunction](param);
      }
  }

function submitAjax360FormList(form_id,page_url,customWidth,stringFunction){
        var $data = new FormData($('#'+form_id)[0]);
         $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + page_url,
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
                   if(customWidth){
                    $(".modal-dialog").css("width", customWidth);
                    }else{
                        $(".modal-dialog").css("width", "");
                    }
                }else{
                   $('#modal-view-datatable').modal('hide');
                   $('#showSuccMessage').html("<div class='custom_alert alert alert-success'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>"+obj.returnMsg+"</div>");
                    setTimeout(function(){
                         $('.custom_alert').remove();
                    },3000)
                }
            }
        });
}
