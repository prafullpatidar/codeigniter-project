function daterangeto()
{
    var sdate = document.getElementById("start_date").value;
    var edate = document.getElementById("end_date").value;
    sdate = sdate.replace(/-/gi, '/');
    edate = edate.replace(/-/gi, '/');
    var d1 = new Date(sdate);
    var d2 = new Date(edate);

    if ((d1.getTime() > d2.getTime()) && (sdate != '') && (edate != ''))
    {
        alert("Start date is greater than end date.");
        document.getElementById("start_date").value = '';
    }
}

function daterangefrom()
{
    var sdate = document.getElementById("start_date").value;
    var edate = document.getElementById("end_date").value;
    sdate = sdate.replace(/-/gi, '/');
    edate = edate.replace(/-/gi, '/');
    var d1 = new Date(sdate);
    var d2 = new Date(edate);
    
    if ((d1.getTime() > d2.getTime()) && (sdate != '') && (edate != ''))
    {
        alert("Start date is greater than end date.");
        document.getElementById("end_date").value = '';
    }
}

function updateStatusBox(id, status, title, changeStatusPath,changeStatusFor) {

    /*try {
        title = atob(title);
    } catch(e) {
       title = title;		    
    }*/
    //title = title.toLowerCase();
    if (id != '' && title != '' && changeStatusPath != '') {
        if (status == 1) {
            var wd = (changeStatusFor == 'event' || changeStatusFor == 'task')?'delete':'deactivate';
            var msg = 'Are you sure you want to '+wd+' "' + title +'"?';
        }else if (status == 2) {
            var msg = 'Are you sure you want to Update "' + title +'" ?';
        } else if (status == 3) {
            var msg = 'Are you sure you want to Delete "' + title + '" order?<br /> If you delete this order then it will be deleted permanently.';
        }else if (status == 4) {
            var msg = 'Are you sure you want to Delete "' + title + '" customer order?<br /> If you delete this customer order then it will be deleted permanently.';
        } else if(status==5){
            var msg = 'Are you sure you want to remove "' + title +'"?';
        } else if(status==6){
            var msg = 'Are you sure you want to deactivate "' + title +'"?';
        } else if(status==7){
            var msg = 'Are you sure you want to activate "' + title +'"?';
        } else {
            var msg = 'Are you sure you want to activate "' + title +'"?';
        }
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

$(document).ready(function($){

	$(document).on('keyup',".phone",function() {
        text = $(this).val().replace(/(\d{3})(\d{3})(\d{4})/, "($1)-$2-$3");
        $(this).val(text);
    });
});


/////////remove search field content when any option is selected//////
$(document).on('click','.select2-results__option',function(){
    $('.select2-search__field').val('');
});
/////////////////////////////////////////////////////////////////////

//////message string counter/////////
function countMessageChar(tag) {
    var len = tag.value.length;
    if (len >= 150) {
        tag.value = tag.value.substring(0, 150);
        $('.msgCharCont').text('');
    } else {
        $('.msgCharCont').text('Remaining Characters: '+(150 - len));
    }
}

function calculateDiscount(perc){
  console.log(perc);
}
//////////////////////////////////////

function updateGroupStatusBoxNew(ids, status, changeStatusPath,admin_ids=''){

    if (ids != '' && changeStatusPath != '') {
        if (status == 2) {
            var msg = 'Are you sure you want to activate selected entities?';
        }else if(status==1) {
            var msg = 'Are you sure you want to delete selected entities?';
        }else if(status==3) {
            var msg = 'Are you sure you want to send invoice email to selected entities?';
        }else {
            var msg = 'Are you sure you want to deactivate selected entities?';
        }
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
                            data: {'ids': ids, 'status': status,'admin_ids':admin_ids},
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

(function($) {

    $.fn.prepFixedHeader = function () {
     return this.each( function() {
      $(this).wrap('<div class="fixed-table-new"><div class="table-content"></div></div>');
     });
    };
    
    $.fn.fixedHeader = function () {
     return this.each( function() {
      var o = $(this),
          nhead = o.closest('.fixed-table-new'),
          $head = $('thead.t-header', o);
      
      $(document.createElement('table'))
        .addClass(o.attr('class')+' table-copy no-border').removeClass('header-fixed-new')
        .appendTo(nhead)
        .html($head.clone().removeClass('t-header').addClass('header-copy'));
      var ww = [];
      o.find('thead.t-header > tr:first > th').each(function (i, h){
        //ww.push($(h).width());
      });
      $.each(ww, function (i, w){
        nhead.find('thead.t-header > tr > th:eq('+i+'), thead.header-copy > tr > th:eq('+i+')').css({width: w});
      });
    
     //nhead.find('thead.header-copy').css({ margin:'0 auto', width: o.width()});
    
     var fixedHeaderHeight = $('.header-copy').height();
    //console.log (fixedHeaderHeight);
    $('.fixed-table-new .table-content').css({ paddingTop: fixedHeaderHeight-2});
    $('.ui-tabs-panel .fixed-table-new .table-content').css({ paddingTop: 0});
    
     });
    };
    
    })(jQuery);
        
    $(document).ready(function () {
        $('.header-fixed-new').prepFixedHeader().fixedHeader();
        // $('#modal-view-datatable').on('shown.bs.modal', function () {
        //     $('#modal-view-datatable .header-fixed-new').prepFixedHeader().fixedHeader();
        //     $(".b-p-15").parent().css({"padding-bottom": "15px",});
        // });
        // $('#modal-view-datatable').on('hidden.bs.modal', function () {
        //     $('#modal-view-datatable .header-fixed-new').removeAttr('style').removeClass('header-fixed-new');
        //     $(".b-p-15").parent().removeAttr('style');
        // }); 
    });

 function getcleanedValue(val=0){    
  if(val){
   var cleanValue = parseFloat(val.replace(/,/g, ''));
  }
  return cleanValue;
 }   
