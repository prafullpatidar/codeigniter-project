<?php
$config['session_timeout'] = '60';
$config['title'] = '1North';
$config['upload_path'] = getcwd().'/uploads/';
$config['adjust_type'] = array('4'=>'Negative', '5'=>'Positive', '6'=>'Reproduce');
$config['current_grv_year'] = '2020';
$config['quote_import_fields'] = array('Item No.','Description','Code','QTY','UNIT','Unit Price','Total Price',' Remark');
$config['consumed_sample_fields'] = array('Item No.','Description','Unit','QTY');
$config['opening_sample_fields'] = array('Item No.','Description','Units','QTY','Unit Prices','Line Total','Remarks');
$config['rfq_sample_fields'] = array('Item No.','Description','Stock Unit','Last Count QTY','Order QTY','Remark');
$config['crew_sample_fields'] = array('Arrival Or Departure(Type A/D)','Name of Ship','IMO number','Call Sign','Voyage Number','Port of arrival / departure','Date of arrival / departure','Flag State of Ship','Last Port of call','','S.No.','Family Name','Given Name','Rank or Rating','Nationality','Date of Birth','Place of Birth','Gender','Nature of Identity Document(Passport)','Number of Identity Document(Passport Number)','Issuing State of Identity Document','Expiry Date of Identity Document');

$config['crew_food_habits_sample_fields'] = array('Details of the Crew Member','Name','Rank','Age/DOB','Gender','Nationality','Number of identity document (Passport Number)','2. Eating Preferences. (Type Y as per your habit.)','Food Group','Never','Daily','2/Week','3/Week','4/Week','Allergies','Meat','Pork','Beef','Fish/Sea Food','Mutton','Chicken','Egg','Cereals','Dairy Products','Vegetables','Fruits','Sweets');

//$config['email_send_by']=1;
$config['protocol']='smtp';
$config['email_send_by']=1;
$config['protocol']='smtp';
$config['smtp_host']='ssl://smtp.gmail.com';
$config['smtp_port']='465';
$config['smtp_user']='noreply@wsisrdev.com';
$config['smtp_pass']='BXGr6%V7=7HZ6eL=$$';
$config['from_name']= "One North Ships";
$config['from_email']= "info@onenorthships.com";

?>
