function initiateDateRangePickerClearbtn(){
    $('.date-range-picker-clearbtn').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        "showDropdowns": true
    });

    $('.date-range-picker-clearbtn').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY')).trigger('change');
    });

    $('.date-range-picker-clearbtn').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('').trigger('change');
    });
}

$(document).ready(function(){
   initiateDateRangePickerClearbtn(); 
});