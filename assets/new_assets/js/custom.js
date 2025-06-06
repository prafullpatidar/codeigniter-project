$(document).on('change', '#country_id', function () {
    $.ajax({
        type: 'POST',
        url: base_url+'state/getAllStateByCountryId',
        data: {id: $("#country_id").val(), state_id : $state_id},
        dataType: 'JSON',
        success: function (reponceData) {
            if (reponceData.code == 100) {
                $('#state_id').html(reponceData.data);
                if($state_id!=''){
                    $( "#state_id" ).trigger( "change" );
                }
            }else{
                $( "#state_id,#city_id,#zipcode_id" ).html('<option value=""> Select </option>');
            }
        }
    });
}); 
$(document).on('change', '#state_id', function () { 
    $.ajax({
        type: 'POST',
        url: base_url+'city/getAllCityByStateId',
        data: {id: $("#state_id").val(), city_id : $city_id},
        dataType: 'JSON',
        success: function (reponceData) {
            if (reponceData.code == 100) {
                $('#city_id').html(reponceData.data);
                if($city_id!=''){
                    $( "#city_id" ).trigger( "change" );
                }
            }
        }
    });
});
$(document).on('change', '#city_id', function () { 
    if($city_id == ''){
        $city_id = $("#city_id").val();
    }
    $.ajax({
        beforeSend: function(){
                $("#customLoader").show();
            },
        type: 'POST',
        url: base_url+'zipcode/getAllZipcodeByCityId',
        data: {id: $("#city_id").val(), zipcode_id : $zipcode_id},
        dataType: 'JSON',
        success: function (reponceData) {
            if (reponceData.data) {
                $("#is_zipcode_div").show();
                $('#zipcode_id').html(reponceData.data);
                if($zipcode_id!=''){
                    $( "#zipcode_id" ).trigger( "change" );
                }
                
            }
            $('#customLoader').hide();
        }
    });
});
$(document).on('change','.get_territory', function(){
    $franchisor_lead_id = $(this).val();
    $.ajax({
        type : 'POST',
        url : base_url+'lead/getFranchisorLeadDataById',
        data : {franchisor_lead_id : $franchisor_lead_id, territory_id : $territory_id},
        dataType : 'JSON',
        success : function (reponceData){
            //console.log(reponceData);
            if(reponceData.code == 100){
                var data = reponceData.data;
                $('#franchisor_id').val(data.franchisor_id);
                $('#contact_id').val(data.contact_id);
            }
            $('#territory_id').html(reponceData.territory_list);
        }
      
    });
});
        
function searchByZipcode(zipcode_id)
{ 
    $.ajax({
        beforeSend: function(){
            $("#customLoader").show();
        },
        type: "POST",
        url: base_url + 'zipcode/getCountryStateCityZipcodeByZipcode',
        cache: false,
        dataType: 'json',
        data: {'zipcode_id': zipcode_id},
        success: function (response)
        { 
            $('#customLoader').hide();
            //console.log(response);
            var address_drop_down = $("#address_drop_down").val();
            if(zipcode_id){
                if(address_drop_down == 2){
                    $("#addressDetailsTab1").show();
                    $("#searchby_zip_code").prop('readonly', true);
                    $("#addressDetailsTab1").html('<span style="cursor: pointer; color:#49a8df;text-decoration:underline;" onclick="resetZipCode();";>Click here</span> to reset Zipcode.');
                    
                    var blanck_option = '<option>Select</option>';
                        $state_id = response.state_id;
                        if ($state_id == '')
                            $('#state_id').html(blanck_option);
                        $city_id = response.city_id;
                        if ($city_id == '')
                            $('#city_id').html(blanck_option);
                        $zipcode_id = response.zipcode_id;
                        if ($zipcode_id == '') 
                            $('#zipcode_id').html(blanck_option);

                        $("#country_id").val(response.country_id).trigger("change");
                    
                }else if(address_drop_down == 0){
                    $("#country_id").val(response.country_id);
                    $("#state_id").val(response.state_id);
                    $("#city_id").val(response.city_id);
                    $("#zipcode_id").val(response.zipcode_id);

                    $("#addressDetailsTab1").show();
                    $("#searchby_zip_code").prop('readonly', true);
                    $("#addressDetailsTab1").html('<span style="cursor: pointer; color:#49a8df;text-decoration:underline;" onclick="resetZipCode();";>Click here</span> to reset Zipcode.');

                }else{ 
                    $("#addressDetailsTab1").hide();
                    var blanck_option = '<option>Select</option>';
                    $state_id = response.state_id;
                    if ($state_id == '')
                        $('#state_id').html(blanck_option);
                    $city_id = response.city_id;
                    if ($city_id == '')
                        $('#city_id').html(blanck_option);
                    $zipcode_id = response.zipcode_id;
                    if ($zipcode_id == '') 
                        $('#zipcode_id').html(blanck_option);

                    $("#country_id").val(response.country_id).trigger("change");
                } 
                getLeadOwnerData(response.zipcode_id);
            }else{ 
                $(".is_address_field_dropdown").show();
            }
        }
    });
}
                                    
function getLeadOwnerData()
{ 
    var zipcode_id = $('#zipcode_id').val();
    var lead_source_id = $('#lead_source_id').val();
    if(lead_source_id!='' || zipcode_id!=''){
    $.ajax({
		type: "POST",
		url: base_url + 'lead/getLeadOwnerByLeadSourceFranchisorId',
		cache: false,
		dataType : 'JSON',
		data: {'zipcode_id': zipcode_id,'lead_source_id': lead_source_id,'assigned_to_user_id':assigned_to_user_id,'franchisor_id':franchisor_id},
		success: function (response)
		{
            $('#assigned_to_user_id').html(response.html_containet);
		}
	});
    }else{
        $('#assigned_to_user_id').html('<option value="">Select</option>');
    }
}

                                    
function initiateAutoCompleteForZipCode(prefixfield)
{ 
    $( "#searchby_zip_code" ).autocomplete({ 
      source: function( request, response ) {
         
        if(request.term.length>2){
        $.ajax({
            beforeSend: function(){
                $("#customLoader").show();
            },
          type:"POST",
          url: base_url + "zipcode/getSearchZipCOde/",
		  dataType: "json",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 100,
            name_startsWith: request.term
          },
          success: function( data ) {
            $('#customLoader').hide();
			 if(data != null){
    			$(".ui-helper-hidden-accessible").hide();
                response( $.map( data.results, function( item ) {
    			  return {
                    value: item.zipcode_text,
    	        	realval : item.zipcode_id
                  }
                }));
			}
          }
        });
        }
      },
      minlength: 3,
      select: function( event, ui ) {
       $(".ui-helper-hidden-accessible").hide();
	  //console.log(ui.item);
          //lead detail
          //console.log(prefixfield);
          if(prefixfield == 'lead detail'){
              searchByZipcode_lead(ui.item.realval);
          }else{
              searchByZipcode(ui.item.realval);
          }
          
      },
    });
}

function getBroker($broker_company_id){
    //alert($lead_source);
    $.ajax({
        type : 'POST',
        url : base_url+'broker/getBrokerByLeadSource',
        data : { broker_company_id : $broker_company_id, broker_contact_id : $broker_contact_id, franchisor_id : $franchisor_id},
        dataType : 'JSON',
        success : function (responceData){
            //alert(responceData);
            if(responceData.code == 100){
                $('#broker_container').show();
                $('#broker_value').html(responceData.label);
                $('#is_broker').val(true);
                $('#broker_contact_id').html(responceData.html); 
                $('#responsible_user_id').html(responceData.responsible_user_html);
                $('#responsible_user_container').show();
                
            }else{
                $('#broker_container').hide();
                $('#responsible_user_container').hide();
                $('#is_broker').val(false);
                $('#broker_contact_id').html(responceData.html); 
                $('#responsible_user_id').html(responceData.html); 
            }    
        }
    });
}

function getBrokerCompany($lead_source_id){ 
    $.ajax({
        type : 'POST',
        url : base_url+'broker/getBrokerCompanyByBrokerTypeLeadSource',
        data : { lead_source_id : $lead_source_id, broker_contact_id : $broker_contact_id, franchisor_id : $franchisor_id},
        dataType : 'JSON',
        success : function (responceData){
            //alert(responceData);
            if(responceData.code == 100){
                $('#broker_company_container').show();
                //$('#broker_value').html(responceData.label);
                $('#is_broker').val(true);
                $('#broker_company_id').html(responceData.html); 
                //$('#responsible_user_id').html(responceData.responsible_user_html);
                //$('#responsible_user_container').show();
                
            }else{
                $('#broker_company_container').hide();
                $('#broker_container').hide();
                $('#responsible_user_container').hide();
                $('#is_broker').val(false);
                $('#broker_company_id').html(responceData.html); 
                //$('#responsible_user_id').html(responceData.html); 
            }    
        }
    });
}

$(document).ready(function($){

	$(document).on('keyup',".phone",function() {
        text = $(this).val().replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        $(this).val(text);
    });
});


function getStateByCountry(prefix){
    $.ajax({
        beforeSend: function(){
                $("#customLoader").show();
            },
        type: 'POST',
        url: base_url+'state/getAllStateByCountryId',
        data: {id: $('#'+prefix+'country_id').val(), state_id : $state_id},
        dataType: 'JSON',
        success: function (reponceData){
            $("#customLoader").hide();
            if (reponceData.code == 100){
                $('#'+prefix+'state_id').html(reponceData.data);
                if($state_id!=''){
                    $( '#'+prefix+'state_id').val($state_id);
                    $( '#'+prefix+'state_id').trigger( "change" );
                }else{
                    $( "#"+prefix+"city_id,#"+prefix+"zipcode_id" ).html('<option value="">Select</option>');
                }
            }
        }
    });
}

function getCityByState(prefix){
    $.ajax({
        beforeSend: function(){
                $("#customLoader").show();
            },
        type: 'POST',
        url: base_url+'city/getAllCityByStateId',
        data: {id: $('#'+prefix+'state_id').val(), city_id : $city_id},
        dataType: 'JSON',
        success: function (reponceData) {
            $("#customLoader").hide();
            if (reponceData.code == 100) {
                $('#'+prefix+'city_id').html(reponceData.data);
                if($city_id!=''){
                    $('#'+prefix+'city_id').trigger( "change" );
                }
            }
        }
    });
}

function getZipcodeByCity(prefix){
    $.ajax({
        beforeSend: function(){
                $("#customLoader").show();
            },
        type: 'POST',
        url: base_url+'zipcode/getAllZipcodeByCityId',
        data: {id: $('#'+prefix+'city_id').val(), zipcode_id : $zipcode_id},
        dataType: 'JSON',
        success: function (reponceData) {
            $("#customLoader").hide();
            if (reponceData.data) {
                $('#'+prefix+'zipcode_id').html(reponceData.data);
                if($zipcode_id!=''){
                    $('#'+prefix+'zipcode_id').trigger( "change" );
                }
            }
        }
    });
}
