var BlankonFormPicker = function () {

    return {

        // =========================================================================
        // CONSTRUCTOR APP
        // =========================================================================
        init: function () {
            BlankonFormPicker.bootstrapDatepicker();
          
        },

        // =========================================================================
        // BOOTSTRAP DATEPICKER
        // =========================================================================
        bootstrapDatepicker: function () {
                // Default datepicker (options)
                $('#datePicker').datepicker({
                    format: 'mm-dd-yyyy',
                    todayBtn: 'linked',
                    
                });
                
                $('.datePicker').datepicker({
                    format: 'mm-dd-yyyy',
                    todayBtn: 'linked',
					endDate: '+0d',
                    autoclose: true,
                });
				
				$('.datePicker1').datepicker({
                    format: 'mm-dd-yyyy',
                    todayBtn: 'linked',
					orientation: "top auto",
                                        autoclose: true,
                });
				
				$(".datepickerMonth").datepicker({
					format: "mm-yyyy",
					startView: "months", 
					minViewMode: "months"
				});
				/************ For DOB**************/
				$('.datePicker5').datepicker({
                    format: 'mm-dd-yyyy',
        			endDate: '+0d',
                    
                });
				$('.datePicker6').datepicker({
                    format: 'mm-dd-yyyy',
        			startDate: '+0d',
                    
                });
        }

    };

}();

// Call main app init
BlankonFormPicker.init();