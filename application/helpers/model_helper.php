<?php
function ConvertDate($original_datetime, $target_timezone = '', $formate = '')
{
    $ci = & get_instance();
    $original_timezone = 'GMT+5:30';
        if($original_datetime!='')
        {            
            $original_timezone = new DateTimeZone($original_timezone);

            // Instantiate the DateTime object, setting it's date, time and time zone.
            $datetime = new DateTime($original_datetime, $original_timezone);

            // Outputs a date/time string based on the time zone you've set on the object.
            $triggerOn = $datetime->format($formate);

            // Print the date/time string.
            return  $triggerOn; // 2013-04-01 08:08:00
        }
}
 function getFormatedDate($date) {
    if($date!=''){
        return date('d-m-Y', strtotime($date));
    }
}

function getFormatedTime($date) {
    return date('H:i:s', strtotime($date));
}

function getFormatedDateTime($date) {
    return date('Y-m-d H:i:s', strtotime($date));
}

function checkPermissionByRole($user_role, $addedDatetime=''){
    if($user_role == 'super_admin'){
        return 1;
    }else if($user_role == 'office_staff'){
        $date1 = date('d-m-Y');
        $date2 = date('d-m-Y', strtotime($addedDatetime));
        if($date1 == $date2){
            return 1;
        }else{
            return 0;
        }
    }else if($user_role == 'operator'){
        return 0;
    }
}

?>