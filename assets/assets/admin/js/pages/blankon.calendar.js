var BlankonCalendar = function () {

    // =========================================================================
    // SETTINGS APP
    // =========================================================================
    var globalPluginsPath = base_url+'assets/assets/global/plugins/bower_components';
    return {

        // =========================================================================
        // CONSTRUCTOR APP
        // =========================================================================
        init: function (calendar_contact_id) {
            BlankonCalendar.calendar(calendar_contact_id);
            BlankonCalendar.chosenSelect();
        },

        // =========================================================================
        // CALENDAR
        // =========================================================================
        calendar: function (calendar_contact_id) {
            "use strict";
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
            if(dd<10) { dd='0'+dd; } 
            if(mm<10) { mm='0'+mm; } 
            today = yyyy+'-'+mm+'-'+dd;
            var options = {
                //events_source: globalPluginsPath+'/bootstrap-calendar/calendar-events-sample.json',
                events_source: base_url+'calendar/event_list',
                calendar_contact_id:calendar_contact_id,
                view: 'month',
                tmpl_path: globalPluginsPath+'/bootstrap-calendar/tmpls/',
                tmpl_cache: false,
                day: today,
                onAfterEventsLoad: function(events) {
                    if(!events) {
                        return;
                    }
                    var list = $('#eventlist');
                    list.html('');

                    $.each(events, function(key, val) {
                        $(document.createElement('li'))
                            .html('<a href="' + val.url + '"><i class="fa fa-calendar mr-10"></i> ' + val.title + '</a>')
                            .appendTo(list);
                    });
                },
                onAfterViewLoad: function(view) {
                    //console.log(this);
                    $('.page-header h4').text(this.getTitle());
                    $('button').removeClass('active');
                    $('.calendar-menu-mobile ul li').removeClass('active');
                    $('button[data-calendar-view="' + view + '"]').addClass('active');
                    $('a[data-calendar-view="' + view + '"]').parent('li').addClass('active');
                },
                classes: {
                    months: {
                        general: 'label'
                    }
                }
            };

            var calendar = $('#calendar').calendar(options);

            $('[data-calendar-nav]').each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.navigate($this.data('calendar-nav'));
                });
            });

            $('[data-calendar-view]').each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.view($this.data('calendar-view'));
                });
            });

            $('#language').change(function(){
                calendar.setLanguage($(this).val());
                calendar.view();
            });

            $('#events-in-modal').change(function(){
                var val = $(this).is(':checked') ? $(this).val() : null;
                calendar.setOptions({modal: val});
            });
            $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
                //e.preventDefault();
                //e.stopPropagation();
            });
        },

        // =========================================================================
        // CHOSEN SELECT
        // =========================================================================
        chosenSelect: function () {
            $('.chosen-select').chosen();
        }

    };

}();

// Call main app init
//BlankonCalendar.init();
