function checkAphabetAndNumber(strg)
{   
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    if(regex.test(strg)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

function checkOnlyAphabets(name_strg)
{   
    var regex = new RegExp("^[a-zA-Z]+$");
    if(regex.test(name_strg)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

function checkOnlyAphabetsAndSpace(name_strg)
{   
    var regex = new RegExp("^[a-zA-Z ]+$");
    if(regex.test(name_strg)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

function validatePhone(txtPhone) 
{
    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    if (filter.test(txtPhone)) 
    {
        return true;
    }
    else 
    {
        return false;
    }
}

function validateEmail($email) 
{
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

    jQuery('#htmlFormWidget').on('submit', function(e){
        e.preventDefault();
                    if(grecaptcha.getResponse() == "")
            {
                alert("Incorrect Captcha!");
                return false;
            }
          
                    var field_val = jQuery("#first_name").val();
                    if(field_val == '')
                    {
                        alert("first name field is required!");
                        return false;
                    }
          
                    var field_val = jQuery("#last_name").val();
                    if(field_val == '')
                    {
                        alert("last name field is required!");
                        return false;
                    }
          
                    var field_val = jQuery("#main_phone").val();
                    if((field_val == '') || (validatePhone(field_val) == false))
                    {
                        alert("main phone field is empty Or invalid!");                
                        return false;
                    }
          
                    var field_val = jQuery("#email").val();
                    if((field_val == '') || ( !validateEmail(field_val)))
                    {
                        alert("email field is empty Or invalid!");                
                        return false;
                    }
          
                    var field_val = jQuery("#city").val();
                    if((field_val == '') || (!checkOnlyAphabetsAndSpace(field_val)))
                    {
                        alert("Only alphabets allowed in city field!");
                        return false;
                    }
          
                    var field_val = jQuery("#state").val();
                    if((field_val == '') || (!checkOnlyAphabetsAndSpace(field_val)))
                    {
                        alert("Only alphabets allowed in state field!");
                        return false;
                    }
          
                    var field_val = jQuery("#zipcode").val();
                    if((field_val == '') || (checkAphabetAndNumber(field_val) == false))
                    {
                        alert("Only alphabets and number allowed in zipcode field!");
                        return false;
                    }
          
                    var field_val = jQuery("#country").val();
                    if((field_val == '') || (!checkOnlyAphabetsAndSpace(field_val)))
                    {
                        alert("Only alphabets allowed in country field!");
                        return false;
                    }
                this.submit();
        jQuery("#loading_dv").css('display','block');
    });

    function isDate(txtDate)
    {
        var currVal = txtDate;
        if(currVal == '')
        {
            return false;
        }
        //var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/; 
        //var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/; 
        var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
        var dtArray = currVal.match(rxDatePattern); 
        if(dtArray == null) 
        {
            return false;
        }

        //Checks for yyyy/mm/dd format.
        // dtYear = dtArray[1];
        // dtMonth = dtArray[3];
        // dtDay= dtArray[5];
        dtYear = dtArray[5];
        dtMonth = dtArray[1];
        dtDay= dtArray[3];
        if(dtMonth < 1 || dtMonth > 12) 
        {
            return false;
        }
        else if(dtDay < 1 || dtDay> 31)
        {
            return false;
        }
        else if((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        {
            return false;
        }
        else if(dtMonth == 2) 
        {
            var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
            if(dtDay> 29 || (dtDay ==29 && !isleap))
            {
                return false;
            }
        }
        return true;
    }

    function isDateTime(txtDateTime)
    {
        var currVal = txtDateTime;
        if(currVal == '')
        {
            return false;
        }
        
        var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2}) ([0-9]|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$/;
        var dtArray = currVal.match(rxDatePattern); 
        if(dtArray == null) 
        {
            return false;
        }
        
        //Checks for yyyy/mm/dd format.
        dtYear = dtArray[1];
        dtMonth = dtArray[3];
        dtDay= dtArray[5];
        dtHour= dtArray[6];
        dtMinute= dtArray[7];
        dtSecond= dtArray[8];
        if(dtMonth < 1 || dtMonth > 12) 
        {
            return false;
        }
        else if(dtDay < 1 || dtDay> 31)
        {
            return false;
        }
        else if((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        {
            return false;
        }
        else if(dtMonth == 2) 
        {
            var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
            if(dtDay> 29 || (dtDay ==29 && !isleap))
            {
                return false;
            }
        }
        else if(dtHour < 0 || dtHour > 23) 
        {
            return false;
        }
        else if(dtMinute < 0 || dtMinute > 59) 
        {
            return false;
        }
        else if(dtSecond < 0 || dtSecond > 59) 
        {
            return false;
        }
        return true;
    }
