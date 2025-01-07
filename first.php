<?php 
include("dbconnection.php");
if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];//Unfilled
if(isset($_REQUEST['aid'])) $aid = $_REQUEST['aid']; // Filled
include('date_picker_link.php');
//echo "hho";
$banks1=array();$banks2 = array();
$bank_sql1 = mysql_query("SELECT bm.acc_id,bm.bank_name,bm.account_no,bm.branch_name FROM bank_master bm join radiant_location rl on rl.location_id = bm.location WHERE bm.status='Y' and rl.status='Y' and region_id in (".$_SESSION['region'].") and bm.location!='0'");
while($row1 = mysql_fetch_assoc($bank_sql1)){
	$banks1[$row1['acc_id']] = $row1['bank_name']."/".$row1['branch_name']."/".$row1['account_no'];
}

$bank_sql2 = mysql_query("SELECT bm.acc_id,bm.bank_name,bm.account_no FROM bank_master bm WHERE bm.status='Y' and bm.location='0' ");
while($row2 = mysql_fetch_assoc($bank_sql2)){
	$banks2[$row2['acc_id']] = $row2['bank_name']."/".$row2['branch_name']."/".$row2['account_no'];
}
if(isset($_REQUEST['id'])){
	$sql_query ="Select vd.*,cvs.service_type,cld.client_name,cld.client_id,cvs.service_name,cvs.service_code,cvs.service_id,cvs.working_hours from vantrans_details vd join cashvan_services cvs on cvs.service_id = vd.service_id join client_details cld on cld.client_id = cvs.client_id where vd.sid='".$id."'";
	//echo $sql_query;
	$result = mysql_query($sql_query); 		
	$row = mysql_fetch_assoc($result);	
	//print_r($row['service_id']);
	//Get Custom Fields
	//echo "Select * from cashvan_dynamic_custom where FIND_IN_SET(".$row['service_id'].",cust_id)>0 and status='Y' ";
	$get_fld = mysql_query("Select * from cashvan_dynamic_custom where FIND_IN_SET(".$row['service_id'].",cust_id)>0 and  client_id = ".$row['client_id']." and status='Y' ");
	//echo $get_fld;
	$fields = mysql_fetch_assoc($get_fld);
	//print_r($fields);
	
}

function tdiff($x,$y){
	$time_one = new DateTime($x);
	$time_two = new DateTime($y);
	$difference = $time_one->diff($time_two);
	return($difference->format('%d days %h hours %i minutes %s seconds'));
}

?>
<link rel="stylesheet" type="text/css" href="css/bootstrap-multiselect.css" />
<script type="text/javascript" src="js/bootstrap-multiselect.js" ></script>
<script type="text/javascript">
function AddRow(obj){
	var id=$(obj).attr('id')+"1";	
	var data = "<tr>"+$("#"+id+" tbody tr:first").html()+"</tr>";
	$("#"+id+" tbody").append(data);
	
	
	//alert(selects);
	clear_row(id);
}
var i = 1;
function clear_row(id){
	$("#"+id+" tbody tr:last").find('input[type=text]').attr('value','');
	$("#"+id+" tbody tr:last").find('.hidden').attr('value','');//class="hide"
	$("#"+id+" tbody tr:last").find('.hide').attr('value','');
	$("#"+id+" tbody tr:last").find('select').val('');
	$("#"+id+" tbody tr:last").find('select').trigger("chosen:updated");
	$("#"+id+" tbody tr:last").find('input[type=checkbox]').attr('value','0');
	$("#"+id+" tbody tr:last").find('input[type=checkbox]').removeAttr("disabled");
	$("#"+id+" tbody tr:last").find('.disableClick').removeClass('disableClick');	
	$("#"+id+" tbody tr:last").find('textarea').val('');
	
	//$("#"+id+" tbody tr:last").find('td:last a').attr('rel','0%0');	
	//$("#"+id+" tbody tr:last").find('td:last a').attr('onclick','delete_data(0,0)');
	
	var selects='<select data-required="true" class="form-control parsley-validated chosen-select1" name="dcv_client_acc[]" id="dcv_client_acc" tabindex="26" style="width:200px" ><option value="" >Select </option>';
<?php	foreach($banks1 as $key1 => $accts1){ 	?>
	selects+='<option value="<?php echo $key1;?>"><?php echo $accts1; ?></option>';
<?php 	}  ?>
<?php	foreach($banks2 as $key2 => $accts2){ 	?>
	selects+='<option value="<?php echo $key2;?>"><?php echo $accts2; ?></option>';
<?php 	}  ?>
selects+='</select>';

	$("#"+id+" tbody tr:last").find('#acc_sel').html('');
	$("#"+id+" tbody tr:last").find('#acc_sel').append(selects);
	$("#"+id+" tbody tr:last").find('#acc_sel').trigger("chosen:updated");
} 
function delete_datas(obj){
	var len_table = $('#acc_table1 tbody tr').length;
	if(len_table > 1){
		$(obj).closest('tr').remove();
		/*$.ajax({
			type: "POST",
			url: "ajax/get_all_detail.php",
			data: 'do=delete&pid=View_Logs&del_id=',
			success: function(msg){
				$('#view_status').html(msg);
			}
		});*/
	}
}

function load_loc(){
	var id = $('#id').val();
	$.ajax({
		type:"POST",
		data:{pid:"View_Logs",location:"location",id:id},
		url:"ajax/get_all_detail.php",
		success: function(res){
			$('#from_location').html(res);
			$('#from_location').trigger("chosen:updated");
		}
	});
}

</script>
<?php
//if ($per != "Admin") {
	   $tdate=date('F-Y', strtotime($row['trans_date']));

             $sqlc1 = "select * from cashvan_client_complete where trans_date='" . $tdate . "' and  region_id in (".$_SESSION['region'].") and find_in_set('" . $row['service_id'] . "',service_id) > 0  and comp_status=1 and status='Y' ";
            $quc1 = mysql_query($sqlc1);
            $nc1 = mysql_num_rows($quc1);
  //  }
  if ($nc1 > 0) {
    ?>
						<div class="alert-danger" align="center"> <b>
								<?php
                echo "Sorry, selected client report already completed, please contact our DMT Team";
    ?>
							</b> </div>
					<?php  }
?>
<div class="container">
	<div class="row">	
		<div class="col-md-12 col-sm-11">
			<div class="portlet">	    
				<h3 class="portlet-title"><u>Edit Transaction Logs</u></h3>
            </div>
		</div>     		
        <form action="#" method="POST"  id="demo-validation" name="demo-validation" data-validate="parsley" class="form parsley-form">
                <input type="hidden" id="id" name="id" value="<?php if(isset($id)) echo $id; ?>" /> 
                <input type="hidden" id="service_id" name="service_id" value="<?php if(isset($id)) echo $row['service_id']; ?>" />
				<input type="hidden" id="clientid" name ="clientid" value ="<?php echo $row['client_id']; ?>" />
				<input type="hidden" id="working_hours" name ="working_hours" value ="<?php echo $row['working_hours']; ?>" />

		
                <div class="form-group col-sm-12" align="center" >
				<table width="100%">
    <tr valign="top">
        <td width="50%" class="move_to_le">
            <table>
                <tr>
                    <td width="50%"><label for="name">Service ID</label></td>
                    <td width="2%">:</td>
                    <td width="48%"><label for="name"><?php echo $row['service_code']; ?></label></td>
                </tr>
                <tr>
                    <td width="50%"><label for="name">Client Name</label></td>
                    <td width="2%">:</td>
                    <td width="48%"><label for="name"><?php echo $row['client_name']; ?></label></td>
                </tr>
            </table>
        </td>
        <td width="50%" class="move_to_ri">
            <table >
                <tr>
                    <td width="48%"><label for="name">Service Name</label></td>
                    <td width="2%">:</td>
                    <td width="50%"><label for="name"><?php if(isset($id)) echo $row['service_name']; if(isset($aid)) echo $row['CashVan']; ?></label></td>
                </tr>
                <tr>
                    <td width="48%"><label for="name">Cash Van Type</label></td>
                    <td width="2%">:</td>
                    <td width="50%"><label for="name"><?php if(isset($id)) echo $row['service_type']; if(isset($aid)) echo $row['Cash_Type']; ?></label></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

                       
                </div>
                <div class="clear"></div>
                <!--Location-->
				<div class="warning_box_gray">
				<?php if($fields['from_location']!='' && $fields['mandate']!= 'Indusind DCV'){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['from_location']!='' && $fields['from_location']!='0'){ echo $fields['from_location']; }else {?> Location From<?php } ?></label>
					<input type="text" id="from_location" name="from_location" class="form-control parsley-validated" data-required="true" value="<?php echo $row['location_from'] ?>" placeholder="Location To" tabindex="2">	 
                    <span id="from_location_err" name="from_location_err"></span>  
                    <!--<select data-required="true" class="form-control parsley-validated chosen-select" name="from_location" id="from_location"  tabindex="1">
                        <option value="">Select Location </option>
                    </select> -->
                   <!-- <span id="from_location_err" name="from_location_err"></span>  -->     
                </div>
                <?php } ?>
                 <?php if($fields['to_location']!=''){?>
                 <!--<div class="clear"></div>-->
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['to_location']!='' && $fields['to_location']!='0'){ echo $fields['to_location']; }else {?> Location To<?php } ?></label>
                    <input type="text" id="to_location" name="to_location" class="form-control parsley-validated" data-required="true" value="<?php echo $row['location_to'] ?>" placeholder="Location To" tabindex="2">	 
                    <span id="to_location_err" name="to_location_err"></span>                 		
                </div>	
                 <?php } ?>
               
                <!--Date-->
                 <?php //if($fields['from_date']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['from_date']!='' && $fields['from_date']!='0'){ echo $fields['from_date']; }else {?> From Date<?php } ?></label>
                    <input type="text" id="popupDatepicker" name="from_date" class="form-control parsley-validated" data-required="true" placeholder="Date" value="<?php  echo date('d-m-Y',strtotime($row['trans_date'])); ?>" tabindex="3" readonly>
                     <span id="from_date_err" name="from_date_err"></span>
                </div>

				<?php  if($fields['extra_ot']!='') { ?>
	            <div class="form-group col-sm-12">
                <div class ="col-sm-3"></div>
                <div class = "col-sm-3">  
                <label for="name"> Extra OT </label> &nbsp;
	            <input name="extra_ot" type="checkbox" id="extra_ot"  value="1"  <?php if($row['extra_ot'] == "1") echo "checked='checked'" ?>>
                </div> 
                </div>
                <?php
	              }	
	            ?>
                <!-- <div class="clear"></div> -->
                <!-- extra ot -->
                
				<!-- extra ot -->
	            <div class="clear"></div>
                <div class="form-group col-sm-3">
                 <?php if($fields['time_in']!=''){?>
                    <div class="form-group col-sm-6">
                        <label for="name"><label class="compulsory">*</label><?php if($fields['time_in']!='' && $fields['time_in']!='0'){ echo $fields['time_in']; }else {?> Start Time<?php } ?></label>
                        <input type="text" id="time_in" name="time_in" class="form-control parsley-validated" data-required="true" value="<?php echo $row['start_time'] ?>" placeholder="10:15" tabindex="5" >
                         <span id="time_in_err" name="time_in_err"></span>
                    </div>
                     <?php } ?>
                     <?php if($fields['time_out']!=''){?>
                    <div class="form-group col-sm-6">
                        <label for="name"><label class="compulsory">*</label><?php if($fields['time_out']!='' && $fields['time_out']!='0'){ echo $fields['time_out']; }else {?> End Time<?php } ?></label>
                        <input type="text" id="time_out" name="time_out" class="form-control parsley-validated" data-required="true" value="<?php echo $row['end_time'] ?>" placeholder="22:15" tabindex="6" onblur="diffs();">
                        <span id="time_out_err" name="time_out_err"></span>
                	</div>
                     <?php } ?>
                </div>
               	<div class="form-group col-sm-3">
                	 <?php if($fields['total_time']!=''){?>
                    <div class="form-group col-sm-6">
                        <label for="name"><label class="compulsory">*</label><?php if($fields['total_time']!='' && $fields['total_time']!='0'){ echo $fields['total_time']; }else {?> Total Time<?php } ?> </label>
                        <input type="text" id="total_time" name="total_time" class="form-control parsley-validated" data-required="true" placeholder="00:00" value="<?php echo $row['total_time'] ?>" readonly="readonly">
                     <span id="total_time_err" name="total_time_err"></span>
                    </div>
                     <?php } ?>
                     <?php if($fields['ot_hours']!=''){?>
                    <div class="form-group col-sm-6">
                        <label for="name"><label class="compulsory">*</label><?php if($fields['ot_hours']!='' && $fields['ot_hours']!='0'){ echo $fields['ot_hours']; }else {?> OT Hours<?php } ?></label>
                        <input type="text" id="ot_hours" name="ot_hours" class="form-control parsley-validated" data-required="true" value="<?php echo $row['ot_hours']; ?>" placeholder="Total OT Hours" readonly="readonly">
                         <span id="ot_hours_err" name="ot_hours_err"></span>	                  
                    </div>
                     <?php } ?>
                </div>
                <div class="form-group col-sm-6"> 
                 <?php if($fields['start_kms']!=''){?>
                <div class="form-group col-sm-4">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['start_kms']!='' && $fields['start_kms']!='0'){ echo $fields['start_kms']; }else {?> Starting KM Reading<?php } ?></label>
                    <input type="text" id="start_kms" name="start_kms" class="form-control parsley-validated" data-required="true" value="<?php echo $row['start_kms'] ?>" placeholder="Starting KM Reading" tabindex="9" onkeypress="return isNumber(event)">     
                    <span id="start_kms_err" name="start_kms_err"></span>              
                </div>
                 <?php } ?>
                <!--Mobile 1-->
                 <?php if($fields['end_kms']!=''){?>
                <div class="form-group col-sm-4">
                    <label for="name"><label class="compulsory">*</label> <?php if($fields['end_kms']!='' && $fields['end_kms']!='0'){ echo $fields['end_kms']; }else {?> Ending KM Reading<?php } ?></label>
                    <input type="text" id="end_kms" name="end_kms" class="form-control parsley-validated" data-required="true" value="<?php echo $row['end_kms'] ?>" placeholder="Ending KM Reading" tabindex="10">    
                    <span id="end_kms_err" name="end_kms_err"></span>             
                </div>
                 <?php } ?>
                 <?php if($fields['total_kms']!=''){?>
                <div class="form-group col-sm-4">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['total_kms']!='' && $fields['total_kms']!='0'){ echo $fields['total_kms']; }else {?> Total KM / Day<?php } ?></label>
                    <input type="text" id="total_kms" name="total_kms" class="form-control parsley-validated" data-required="true" value="<?php echo $row['total_kms'] ?>" placeholder="Total KM / Day"  readonly="readonly">  
                    <span id="total_kms_err" name="total_kms_err"></span>                   
                </div>
                 <?php } ?>
                </div>
                <div class="clear"></div>
                 <?php if($fields['service_category']!='' && $fields['mandate']!= 'Indusind DCV'){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['service_category']!='' && $fields['service_category']!='0'){ echo $fields['service_category']; }else {?> Service Category<?php } ?></label>
                    <input type="text" id="service_category" name="service_category" class="form-control parsley-validated" data-required="true" value="<?php echo $row[' 	service_category'] ?>" placeholder="Pickup/Delivery" tabindex="12" >
                    <span id="service_category_err" name="service_category_err"></span>    
                </div>
                 <?php } ?>
                 <?php if($fields['no_trips']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['no_trips']!='' && $fields['no_trips']!='0'){ echo $fields['no_trips']; }else {?> No.of Trips<?php } ?> </label>
                    <input type="text" id="no_trips" name="no_trips" class="form-control parsley-validated" data-required="true" placeholder="No.of Trips" value="<?php echo $row['no_trips'] ?>" tabindex="13" >
                    <span id="no_trips_err" name="no_trips_err"></span>    
                </div>
                 <?php } ?>
                 <?php if($fields['pickup_amount']!='' && $fields['mandate']!= 'Indusind DCV'){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['pickup_amount']!='' && $fields['pickup_amount']!='0'){ echo $fields['pickup_amount']; }else {?> Total Amount Pickedup<?php } ?></label>
                    <input type="text" id="pickup_amount" name="pickup_amount" class="form-control parsley-validated" data-required="true" value="<?php echo $row['pickup_amount'] ?>" placeholder="Total Amount Pickedup" tabindex="14" >     
                    <span id="pickup_amount_err" name="pickup_amount_err"></span>           
                </div>
                 <?php } ?>
                 <?php if($fields['delivery_amount']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['delivery_amount']!='' && $fields['delivery_amount']!='0'){ echo $fields['delivery_amount']; }else {?> Total Amount Delivered<?php } ?></label>
                    <input type="text" id="delivery_amount" name="delivery_amount" class="form-control parsley-validated" data-required="true" placeholder="Delivery Amount" value="<?php echo $row['delivery_amount'] ?>"  tabindex="15" onkeypress="return isNumber(event)" >
                    <span id="delivery_amount_err" name="delivery_amount_err"></span>
                </div> 
                 <?php } ?>
                 <?php if($fields['no_cheques']!='' && $fields['mandate']!= 'Indusind DCV'){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['no_cheques']!='' && $fields['no_cheques']!='0'){ echo $fields['no_cheques']; }else {?> No of Cheque Pickups<?php } ?></label>
                    <input type="text" id="no_cheques" name="no_cheques" class="form-control parsley-validated" data-required="true" value="<?php echo $row['no_cheques'] ?>" placeholder="No of Cheque Pickups" tabindex="16" >
                    <span id="no_cheques_err" name="no_cheques_err"></span>
                </div>
                 <?php } ?>
                 <?php if($fields['toll_tax']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['toll_tax']!='' && $fields['toll_tax']!='0'){ echo $fields['toll_tax']; }else {?> Toll Tax Paid<?php } ?></label>
                    <input type="text" id="toll_tax" name="toll_tax" class="form-control parsley-validated" data-required="true" placeholder="Toll Tax Paid" value="<?php echo $row['toll_paid'] ?>"  tabindex="17" onkeypress="return isNumber(event)" >
                	<span id="toll_tax_err" name="toll_tax_err"></span>    
                </div>
                 <?php } ?>
               <!-- <div class="clear"></div>-->
                <!--Address-->
                 <?php if($fields['permit_paid']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['permit_paid']!='' && $fields['permit_paid']!='0'){ echo $fields['permit_paid']; }else {?> Permit Paid<?php } ?></label>
                    <input type="text" id="permit_paid" name="permit_paid" class="form-control parsley-validated" data-required="true" value="<?php echo $row['permit_paid'] ?>" placeholder="Permit Paid" tabindex="18" onkeypress="return isNumber(event)" >       
                    <span id="permit_paid_err" name="permit_paid_err"></span>          
                </div>
                 <?php } ?>
                 <?php if($fields['parking_charges']!=''){?>
                 <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['parking_charges']!='' && $fields['parking_charges']!='0'){ echo $fields['parking_charges']; }else {?> Parking Charges<?php } ?></label>
                    <input type="text" id="parking_charges" name="parking_charges" class="form-control parsley-validated" data-required="true" value="<?php echo $row['parking_charge'] ?>" placeholder="Parking Charges" tabindex="19">
                    <span id="parking_charges_err" name="parking_charges_err"></span>  	                  
                </div>
                 <?php } ?>

				 <?php if($fields['additional_charges']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['additional_charges']!='' && $fields['additional_charges']!='0'){ echo $fields['additional_charges']; }else {?>Additional Charges incurred if any<?php } ?></label>
                    <input type="text" id="additional_charges" name="additional_charges" class="form-control parsley-validated" data-required="true" value="<?php echo $row['additional_charges'] ?>" placeholder="Additional Charges incurred if any" tabindex="21" >        
                    <span id="additional_charges_err" name="additional_charges_err"></span>            
                </div>
                 <?php } ?>
               <!-- <div class="clear"></div>-->
                <?php if($fields['tele_charge']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['tele_charge']!='' && $fields['tele_charge']!='0'){ echo $fields['tele_charge']; }else {?>Telephone Charges<?php } ?></label>
                    <input type="text" id="tele_charge" name="tele_charge" class="form-control parsley-validated" data-required="true" value="<?php echo $row['telephone_charge'] ?>" placeholder="Telephone Charges" tabindex="22" >  
                    <span id="tele_charge_err" name="tele_charge_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['service_type']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['service_type']!='' && $fields['service_type']!='0'){ echo $fields['service_type']; }else {?>Telephone Charges<?php } ?></label>
                    <input type="text" id="service_type_cu" name="service_type_cu" class="form-control parsley-validated" data-required="true" value="<?php echo $row['service_type'] ?>" placeholder="Service Type" tabindex="22" >  
                    <span id="service_type_err" name="service_type_err"></span>                 
                </div><div class="clear"></div>
                
                 <?php } ?>

				 <!-- extrafields --> 
				

				<?php if($fields['intercity_intracity']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['intercity_intracity']!='' && $fields['intercity_intracity']!='0'){ echo $fields['intercity_intracity']; }else {?>Intercity/Intracity<?php } ?></label>
					<select data-required="true" class="form-control parsley-validated chosen-select" name="intercity" id="intercity" tabindex="20">
                        <option value="" >Select Intercity/Intracity</option>
                        <option value="Intercity" <?php if($row['intercity_intracity'] =='Intercity') echo 'selected="selected"'; ?> >Intercity</option>
                        <option value="Intracity" <?php if($row['intercity_intracity'] =='Intracity') echo 'selected="selected"'; ?>>Intracity</option>
                	</select>
               	 	
                    <!--<input type="text" id="intercity" name="intercity" class="form-control parsley-validated" data-required="true" value="<?php echo $row['intercity_intracity'] ?>" placeholder="Intercity/Intracity" tabindex="22" >-->  
                    <span id="intercity_err" name="intercity_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['location']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['location']!='' && $fields['location']!='0'){ echo $fields['location']; }else {?>Location<?php } ?></label>
                    <input type="text" id="location" name="location" class="form-control parsley-validated" data-required="true" value="<?php echo $row['location'] ?>" placeholder="Location" tabindex="22" >  
                    <span id="location_err" name="location_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['from_branchcode']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['from_branchcode']!='' && $fields['from_branchcode']!='0'){ echo $fields['from_branchcode']; }else {?>From Branch Code<?php } ?></label>
                    <input type="text" id="from_branchcode" name="from_branchcode" class="form-control parsley-validated" data-required="true" value="<?php echo $row['from_branchcode'] ?>" placeholder="From Branch Code" tabindex="22" >  
                    <span id="from_branchcode_err" name="from_branchcode_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['to_branchcode']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['to_branchcode']!='' && $fields['to_branchcode']!='0'){ echo $fields['to_branchcode']; }else {?>To Branch Code<?php } ?></label>
                    <input type="text" id="to_branchcode" name="to_branchcode" class="form-control parsley-validated" data-required="true" value="<?php echo $row['to_branchcode'] ?>" placeholder="To Branch Code" tabindex="22" >  
                    <span id="to_branchcode_err" name="to_branchcode_err"></span>                 
                </div>
                
                 <?php } ?>
				 
				<?php if($fields['oneway_twoway']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['oneway_twoway']!='' && $fields['oneway_twoway']!='0'){ echo $fields['oneway_twoway']; }else {?>One way/Two way<?php } ?></label>
					<select data-required="true" class="form-control parsley-validated chosen-select" name="oneway_twoway" id="oneway_twoway" tabindex="20">
                        <option value="" >Select One way/Two way</option>
                        <option value="One Way" <?php if($row['oneway_twoway'] == 'One Way') echo 'selected="selected"'; ?> >One Way</option>
                        <option value="Two Way" <?php if($row['oneway_twoway'] == 'Two Way') echo 'selected="selected"'; ?>>Two Way</option>
                	</select>
               	 	
                   <!-- <input type="text" id="oneway_twoway" name="oneway_twoway" class="form-control parsley-validated" data-required="true" value="<?php echo $row['oneway_twoway'] ?>" placeholder="One way/Two way" tabindex="22" > -->

                    <span id="oneway_twoway_err" name="oneway_twoway_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['airport_parkingcharges']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['airport_parkingcharges']!='' && $fields['airport_parkingcharges']!='0'){ echo $fields['airport_parkingcharges']; }else {?>Airport Parking Charges<?php } ?></label>
                    <input type="text" id="airport_parkingcharges" name="airport_parkingcharges" class="form-control parsley-validated" data-required="true" value="<?php echo $row['airport_parkingcharges'] ?>" placeholder="Airport Parking Charges" tabindex="22" >  
                    <span id="airport_parkingcharges_err" name="airport_parkingcharges_err"></span>                 
                </div>
                
                 <?php } ?>
				 <?php if($fields['costofoperation']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['costofoperation']!='' && $fields['costofoperation']!='0'){ echo $fields['costofoperation']; }else {?>Cost Of Operation<?php } ?></label>
                    <input type="text" id="costofoperation" name="costofoperation" class="form-control parsley-validated" data-required="true" value="<?php echo $row['costofoperation'] ?>" placeholder="Cost of Operation" tabindex="22" >  
                    <span id="costofoperation_err" name="costofoperation_err"></span>                 
                </div>
                
                 <?php } ?>
				<?php if($fields['category']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['category']!='' && $fields['category']!='0'){ echo $fields['category']; }else {?>Category<?php } ?></label>
                    <input type="text" id="category" name="category" class="form-control parsley-validated" data-required="true" value="<?php echo $row['category'] ?>" placeholder="Category" tabindex="22" >  
                    <span id="category_err" name="category_err"></span>                 
                </div>
				<div class ="clear"></div>

                 <?php }?>
				 <?php if($fields['additional_loaders']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['additional_loaders']!='' && $fields['additional_loaders']!='0'){ echo $fields['additional_loaders']; }else {?>Additional Loaders<?php } ?></label>
					<select data-required="true" class="form-control parsley-validated chosen-select" name="additional_loaders" id="additional_loaders" tabindex="20">
                        <option value="" >Select Additional Loaders</option>
                        <option value="Yes" <?php if($row['additional_loaders'] == 'Yes') echo 'selected="selected"'; ?> >Yes</option>
                        <option value="No" <?php if($row['additional_loaders'] == 'No') echo 'selected="selected"'; ?>>No</option>
                	</select>
               	 	
                    <span id="additional_loaders_err" name="additional_loaders_err"></span>                 
                </div>
                
                 <?php } ?> 
				 
				<div class = "clear"></div>
				<!-- extrafields -->
				
				</div>
				 <!---1st phase----------->
                <!--Mobile 2-->
                
                <!--<div class="clear"></div>	-->
                <!--Mobile 2-->
					
                
                 <!--contact details -->
				 <div class="second_sty">
				<?php if($fields['contact_person']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['contact_person']!='' && $fields['contact_person']!='0'){ echo $fields['contact_person']; }else {?> Contact Person<?php } ?></label>
					<input type="text" id="contact_person" name="contact_person" class="form-control parsley-validated" data-required="true" value="<?php echo $row['contact_person'] ?>" placeholder="contact person" tabindex="19">

                    <span id="contactperson_err" name="contactperson_err"></span>  
                </div>
                 <?php } ?>
                 <?php if($fields['contact_number']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['contact_number']!='' && $fields['contact_number']!='0')
					{ echo $fields['contact_number']; }else {?> Contact Number<?php } ?></label>
					<input type="text" id="contact_number" name="contact_number" class="form-control parsley-validated" data-required="true" value="<?php echo $row['contact_number'] ?>" placeholder="contact number" tabindex="19">

                  	<span id="contactnumber_err" name="contactnumber_err"></span>  
                </div>
                 <?php } ?>
                 <?php if($fields['emailid']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['emailid']!='' && $fields['contact_number']!='0'){ echo $fields['emailid']; }else {?> Emailid<?php } ?></label>
					<input type="text" id="emailid" name="emailid" class="form-control parsley-validated" data-required="true" value="<?php echo $row['emailid'] ?>" placeholder="emailid" tabindex="19">

                    	<span id="emailid_err" name="emailid_err"></span>  
                </div>
                 <?php } ?>
				<!-- field1 --> 
				<?php if($fields['field1']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['field1']!='' && $fields['fields1']!='0')
					{ echo $fields['field1']; }else {?> Field1<?php } ?></label>
					<input type="text" id="field1" name="field1" class="form-control parsley-validated" data-required="true" value="<?php echo $row['field1'] ?>" placeholder="field1" tabindex="19">

                    <span id="field1_err" name="field1_err"></span>  
                </div>
                <?php } ?>
                <!--field1 -->
                 <!--icici bank 22,iob 246,ybl 24,bom 247-->

				<?php 
				//field2 //
				if($fields['client_id'] =='22' || $fields['client_id'] =='24' || $fields['client_id'] =='246' || $fields['client_id'] =='247')

				//if($fields['client_id'] =='22')
				{
                 if($fields['field2']!=''){?>
                
				<div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['field2']!='' && $fields['field2']!='0'){ echo $fields['field2']; } else{ ?>
					Additional Loaders(Yes/No)	
					<?php } ?></label>
                    <select data-required="true" class="form-control parsley-validated chosen-select" name="field2" id="field2" tabindex="20">
                        <option value="" >Select Additional Loaders</option>
                        <option value="Yes" <?php if($row['field2'] == 'Yes') echo 'selected="selected"'; ?> >Yes</option>
                        <option value="No" <?php if($row['field2'] == 'No') echo 'selected="selected"'; ?>>No</option>
                	</select>
               	 	<span id="field2_err" name="field2_err"></span>  
                </div>
				<?php } } 
                //icici end
				else {
				 if($fields['field2']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory">*</label><?php if($fields['field2']!='' && $fields['field2']!='0')
					{ echo $fields['field2']; }else {?> Field2<?php } ?></label>
					<input type="text" id="field2" name="field2" class="form-control parsley-validated" data-required="true" value="<?php echo $row['field2'] ?>" placeholder="field2" tabindex="19">

                    	<span id="field2_err" name="field2_err"></span>  
                </div>
                 <?php } 

				}
			
				//field2 end 
				?>

                <div class="clear"></div>
			<!-- </div> --></div>

                 
                

				<!-- contact details -->

               

				<!-- additional info --> 

				<div class="second_st1">
    <?php if($fields['bill_branch']!=''){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['bill_branch']!='' && $fields['bill_branch']!='0'){ echo $fields['bill_branch']; }else {?> Billing Branch<?php } ?></label>
        <input type="text" id="bill_branch" name="bill_branch" class="form-control parsley-validated" data-required="true" value="<?php echo $row['bill_branch'] ?>" placeholder="<?php if($fields['bill_branch']!='' && $fields['bill_branch']!='0'){ echo $fields['bill_branch']; }else {?> Billing Branch<?php } ?>" tabindex="23">
        <span id="bill_branch_err" name="bill_branch_err"></span>
    </div>
    <?php } ?>
    
    <?php if($fields['insurance']!='' && $fields['mandate']!= 'Indusind DCV'){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['insurance']!='' && $fields['insurance']!='0'){ echo $fields['insurance']; }else { ?>Insurance <?php } ?></label>
        <input type="text" id="insurance" name="insurance" class="form-control parsley-validated" data-required="true" value="<?php echo $row['insurance'] ?>" placeholder="" tabindex="24" >	
        <span id="insurance_err" name="insurance_err"></span>	                  
    </div>
    <?php } ?>
    
    <?php if($fields['vault_status']!='' && $fields['mandate']!= 'Indusind DCV'){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['vault_status']!='' && $fields['vault_status']!='0'){ echo $fields['vault_status']; }else {?> Amount Vaulted( Yes / No )<?php } ?></label>
        <select data-required="true" class="form-control parsley-validated chosen-select" name="vault_status" id="vault_status" tabindex="25">
            <option value="" >Select </option>
            <option value="Yes" <?php if($row['vault_status'] == 'Yes') echo 'selected="selected"'; ?> >Yes</option>
            <option value="No" <?php if($row['vault_status'] == 'No') echo 'selected="selected"'; ?> >No</option>
        </select>
        <span id="vault_status_err" name="vault_status_err" class="compulsory" ></span>	                  
    </div>
    <?php } ?>
    
    <?php if($fields['night_stay']!=''){?>
    <div class="form-group col-sm-3">
        <label for="name" class="st_nig"><label class="compulsory">*</label><?php if($fields['night_stay']!='' && $fields['night_stay']!='0'){ echo $fields['night_stay']; }else {?> Night Stay ( Yes / No )<?php } ?></label>
        <select data-required="true" class="form-control parsley-validated chosen-select" name="night_stay" id="night_stay" tabindex="20">
            <option value="" >Select Stay </option>
            <option value="Yes" <?php if($row['night_stay'] == 'Yes') echo 'selected="selected"'; ?> >Yes</option>
            <option value="No" <?php if($row['night_stay'] == 'No') echo 'selected="selected"'; ?>>No</option>
        </select>
        <span id="night_stay_err" name="night_stay_err"></span>  
    </div>
    <?php } ?>
    
    <?php if($fields['client_acc_no']!='' && $fields['mandate']!= 'Indusind DCV'){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['client_acc_no']!='' && $fields['client_acc_no']!='0'){ echo $fields['client_acc_no']; }else {?> Client Account No<?php } ?></label>
        <select data-required="true" class="form-control parsley-validated chosen-select" name="client_acc_no" id="client_acc_no" tabindex="26">
            <option value="" >Select </option>
            <?php foreach($banks1 as $key1 => $accts1){ ?>
                <option value="<?php echo $key1;?>" <?php if($row['client_acc_no'] == $key1) echo 'selected="selected"';	?>><?php echo $accts1; ?></option>
            <?php } ?>
            <?php foreach($banks2 as $key2 => $accts2){ ?>
                <option value="<?php echo $key2;?>" <?php if($row['client_acc_no'] == $key2) echo 'selected="selected"';	?>><?php echo $accts2; ?></option>
            <?php } ?>
        </select>	 
        <span id="client_acc_no_err" name="client_acc_no_err"></span>	                 
    </div>
    <?php } ?>
    
    <?php if($fields['additional_crew']!='' && $fields['mandate']!= 'Indusind DCV'){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['additional_crew']!='' && $fields['additional_crew']!='0'){ echo $fields['additional_crew']; }else {?> Additional Crew<?php } ?></label>
        <input type="text" id="additional_crew" name="additional_crew" class="form-control parsley-validated" data-required="true" value="<?php echo $row['additional_crew'] ?>" placeholder="" tabindex="27">
        <span id="additional_crew_err" name="additional_crew_err"></span>		                  
    </div>
    <?php } ?>
    
    <?php if($fields['other_remarks']!=''){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory">*</label><?php if($fields['other_remarks']!='' && $fields['other_remarks']!='0'){ echo $fields['other_remarks']; }else {?> Other Remarks<?php } ?></label>
        <select data-required="true" class="form-control parsley-validated chosen-select" name="other_remarks" id="other_remarks" tabindex="20">
            <option value="" >Select remarks</option>
            <option value="SUNDAY" <?php if($row['other_remarks'] == 'SUNDAY') echo 'selected="selected"'; ?> >SUNDAY</option>
            <option value="BANK HOLIDAY" <?php if($row['other_remarks'] == 'BANK HOLIDAY') echo 'selected="selected"'; ?>>BANK HOLIDAY</option>
            <option value="HOLIDAY SERVICE DONE" <?php if($row['other_remarks'] == 'HOLIDAY SERVICE DONE') echo 'selected="selected"'; ?>>HOLIDAY SERVICE DONE</option>
            <option value="REGULAR CASH VAN CHANGED" <?php if($row['other_remarks'] == 'REGULAR CASH VAN CHANGED') echo 'selected="selected"'; ?>>REGULAR CASH VAN CHANGED</option>
            <option value="CASH VAN HAS BEEN CHANGED FOR THIS TRIP" <?php if($row['other_remarks'] == 'CASH VAN HAS BEEN CHANGED FOR THIS TRIP') echo 'selected="selected"'; ?>>CASH VAN HAS BEEN CHANGED FOR THIS TRIP</option>
            <option value="CASH VAN DONE" <?php if($row['other_remarks'] == 'CASH VAN DONE') echo 'selected="selected"'; ?>>CASH VAN DONE</option>
            <option value="CASH VAN NOT RUNNING" <?php if($row['other_remarks'] == 'CASH VAN NOT RUNNING') echo 'selected="selected"'; ?>>CASH VAN NOT RUNNING</option>
        </select>
        <span id="other_remarks_err" name="other_remarks_err"></span>  
    </div>
    <?php } ?>
    
    <?php if($fields['remarks']!='' ){?>
    <div class="form-group col-sm-3">
        <label for="name"><label class="compulsory"></label><?php if($fields['remarks']!='' && $fields['remarks']!='0'){ echo $fields['remarks']; }else {?>Remarks<?php } ?></label>
        <textarea id="remarks" class="form-control parsley-validated " placeholder="remarks" tabindex="28" name="remarks" cols="10" rows="2" aria-required="true" aria-invalid="true"><?php echo $row['remarks']; ?></textarea> 
        <span id="remarks_err" name="remarks_err"></span>                 
    </div>
    <?php } ?>
	
	 <?php if($fields['rbi_guide']!=''){?>
                <div class="form-group col-sm-3">
                    <label for="name"><label class="compulsory"></label><?php if($fields['rbi_guide']!='' && $fields['rbi_guide']!='0'){ echo $fields['rbi_guide']; }else {?>RBI Guidelines<?php } ?></label>
					<select data-required="true" class="form-control parsley-validated chosen-select" name="rbi_guide" id="rbi_guide" tabindex="20">
                        <option value="" >Select RBI Guide</option>
                        <option value="Yes" <?php if($row['rbi_guide'] == 'Yes') echo 'selected="selected"'; ?> >Yes</option>
                        <option value="No" <?php if($row['rbi_guide'] == 'No') echo 'selected="selected"'; ?>>No</option>
                	</select>
               	 	
                    <span id="rbi_guide_err" name="rbi_guide_err"></span>                 
                </div>
                
                 <?php } ?> 
	
</div>

                

				 	<!-----New for remarks dropdown----->
				
				
				</div>
				 <!------------------------->
                 <br /><br />



                <!--<div class="clear"></div>-->
                <?php if($fields['mandate'] == 'Indusind DCV' && $row['insurance'] ==''){ 	?>
                <div class="form-group col-sm-12">
                	<button class="btn btn-success btn-sm btn-sm" id="acc_table" onclick="AddRow(this);" type="button">Add Row</button>
                    <table width="100%" class="table table-hover table-nomargin table-striped table-bordered " id="acc_table1">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Client Name</th>
                                <th>Client Account No.</th>
                                <th>Location</th>
                                <th>Type of Service</th>
                                <th>Total Pickup amount</th>
                                <th>No. of Cheques<br /> Pickup done</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><div align="center"><a href="#acc_table1" onClick="delete_datas(this);" id="acc_tables"><i class="fa fa-times-circle"></i></a></div></td>
                                <td>
                                    <input type="text" id="dcv_clients" name="dcv_clients[]" class="form-control" placeholder="Client Name" tabindex="29">
                                </td>
                                <td id="acc_sel">
                                    <select data-required="true" class="form-control parsley-validated chosen-select" name="dcv_client_acc[]" id="dcv_client_acc" tabindex="30" style="width:200px" >
                                        <option value="" >Select </option>
                                <?php	foreach($banks1 as $key1 => $accts1){ 	?>
                                        <option value="<?php echo $key1;?>" ><?php echo $accts1; ?></option>
                                <?php 	}  ?>
                                <?php	foreach($banks2 as $key2 => $accts2){ 	?>
                                        <option value="<?php echo $key2;?>"><?php echo $accts2; ?></option>
                                <?php 	}  ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="dcv_location" name="dcv_location[]" class="form-control parsley-validated" data-required="true" placeholder="Location" tabindex="31">
                                </td>
                                <td>
                                    <input type="text" id="dcv_service_category" name="dcv_service_category[]" class="form-control parsley-validated" data-required="true"  tabindex="32">
                                </td>
                                <td>
                                    <input type="text" id="dcv_pickup_amount" name="dcv_pickup_amount[]" class="form-control parsley-validated" data-required="true"  tabindex="33">
                                </td>
                                <td>
                                    <input type="text" id="dcv_no_cheques" name="dcv_no_cheques[]" class="form-control parsley-validated" data-required="true" tabindex="34">
                                </td>
                                <td>
                                	<textarea id="dcv_remarks" class="form-control parsley-validated " placeholder="remarks" tabindex="35" name="dcv_remarks[]" cols="5" rows="2" aria-required="true" aria-invalid="true"></textarea> 
                                </td>
                            </tr>
                        </tbody>
                   </table>
                </div>
                <?php }else if($fields['mandate'] == 'Indusind DCV' && $row['insurance']!=''){ ?>
                <div class="form-group col-sm-12">
                	<button class="btn btn-success btn-sm btn-sm" id="acc_table" onclick="AddRow(this);" type="button">Add Row</button>
                    <table width="100%" class="table table-hover table-nomargin table-striped table-bordered " id="acc_table1">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Client Name</th>
                                <th>Client Account No.</th>
                                <th>Location</th>
                                <th>Type of Service</th>
                                <th>Total Pickup amount</th>
                                <th>No. of Cheques<br /> Pickup done</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
						$clts = explode(",",$row['insurance']);
						$accs = explode(",",$row['client_acc_no']);
						$locy = explode(",",$row['from_location']);
						$catey = explode(",",$row['service_category']);
						$picky = explode(",",$row['pickup_amount']);
						$cheqy = explode(",",$row['no_cheques']);
						$remy = explode("$",$row['additional_crew']);
						foreach($clts as $no => $clt_name){	?>
                          	<tr>
                                <td><div align="center"><a href="#acc_table1" onClick="delete_datas(this);" id="acc_tables"><i class="fa fa-times-circle"></i></a></div></td>
                                <td>
                                    <input type="text" id="dcv_clients" name="dcv_clients[]" class="form-control" placeholder="Client Name" tabindex="36" value="<?php echo $clt_name;?>">
                                </td>
                                <td id="acc_sel">
                                    <select data-required="true" class="form-control parsley-validated chosen-select" name="dcv_client_acc[]" id="dcv_client_acc" tabindex="36" style="width:200px" >
                                        <option value="" >Select </option>
                                <?php	foreach($banks1 as $key1 => $accts1){ 	?>
                                        <option value="<?php echo $key1;?>" <?php if($accs[$no]==$key1)echo 'selected="selected"';?>><?php echo $accts1; ?></option>
                                <?php 	}  ?>
                                <?php	foreach($banks2 as $key2 => $accts2){ 	?>
                                        <option value="<?php echo $key2;?>" <?php if($accs[$no]==$key1)echo 'selected="selected"';?>><?php echo $accts2; ?></option>
                                <?php 	}  ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="dcv_location" name="dcv_location[]" class="form-control parsley-validated" data-required="true" placeholder="Location" tabindex="36" value="<?php echo $locy[$no]; ?>">
                                </td>
                                <td>
                                    <input type="text" id="dcv_service_category" name="dcv_service_category[]" class="form-control parsley-validated" data-required="true"  tabindex="36" value="<?php echo $catey[$no];?>">
                                </td>
                                <td>
                                    <input type="text" id="dcv_pickup_amount" name="dcv_pickup_amount[]" class="form-control parsley-validated" data-required="true"  tabindex="36" value="<?php echo $picky[$no];?>">
                                </td>
                                <td>
                                    <input type="text" id="dcv_no_cheques" name="dcv_no_cheques[]" class="form-control parsley-validated" data-required="true" tabindex="36" value="<?php echo $cheqy[$no];?>">
                                </td>
                                <td>
                                	<textarea id="dcv_remarks" class="form-control parsley-validated " placeholder="remarks" tabindex="36" name="dcv_remarks[]" cols="5" rows="2" aria-required="true" aria-invalid="true"><?php echo $remy[$no];?></textarea> 
                                </td>
                            </tr>
                         <?php } ?>
                        </tbody>
                   </table>
                </div>
                <?php } ?>
                <div class="clear"></div>
				<?php if($row['service_type']!='DCV'){?>
				<div class="second_st2">
                <h3 class="portlet-title"><u>Crew List Details</u></h3>
               	<div class="form-group col-sm-12">
                	<?PHP
					$sel_crw = mysql_query("Select desig,name from daily_cashvan_crew where trans_id = '".$id."' and status='Y'");

					if(mysql_num_rows($sel_crw)>0){
						while($cr_rw = mysql_fetch_object($sel_crw)){
                            
							if($cr_rw->name!='null')
							{
								$crew_arr[$cr_rw->desig] = explode(",",$cr_rw->name);
								if($cr_rw->desig == 'gunman') $count_gunman = count($crew_arr[$cr_rw->desig]);
								if($cr_rw->desig == 'driver') $count_driver = count($crew_arr[$cr_rw->desig]);
								if($cr_rw->desig == 'loader') $count_loader = count($crew_arr[$cr_rw->desig]);
								if($cr_rw->desig == 'ce') $count_ce = count($crew_arr[$cr_rw->desig]);
							}
							

							//echo json_encode($crew_arr); exit;

							
						}
					}
					else
					{
						$sel_crw1= mysql_query("Select dcv_crew.desig,dcv_crew.name from dcv_crew JOIN vantrans_details ON vantrans_details.service_id=dcv_crew.service_id where vantrans_details.sid = '".$id."' and vantrans_details.status='Y' and dcv_crew.status='Y'");

						if(mysql_num_rows($sel_crw1)>0){
							while($cr_rw_m = mysql_fetch_object($sel_crw1)){
								$crew_arr[$cr_rw_m->desig] = explode(",",$cr_rw_m->name);

								//echo json_encode($crew_arr); exit;
								if($cr_rw_m->desig == 'gunman') $count_gunman = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'driver') $count_driver = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'loader') $count_loader = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'ce') $count_ce = count($crew_arr[$cr_rw_m->desig]);
								
							}
						}

						//echo $count_gunman;exit;

					}
					
					//echo '<pre>';print_r($crew_arr);echo '</pre>';
					//Cashvan
					$sel_van = mysql_query("Select registration_no,vec_no from cash_van cv where cv.status='Y' ");
					$region = $_SESSION['region'];
						$str_arr = explode (",", $region); 
						 if(in_array(31,$str_arr))
						 {
							 $sel_gun1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Gunman%'  and rc.status='Y' and  rc.loc_id=0");
							 
							  $sel_drive1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Driver%'  and rc.status='Y' and  rc.loc_id=0");
							  
							   $sel_load1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Loader%'  and rc.status='Y' and  rc.loc_id=0");
							   
								$sel_ce1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where and (
							rc.design LIKE '%Cash Van Custodian%'
							OR rc.design LIKE '%Supervisor%'
							OR rc.design LIKE '%Cashier%'
							OR rc.design LIKE '%Cash Executive%'
							OR rc.design LIKE '%CE%'
							)  and rc.status='Y' and  rc.loc_id=0");
							 
							 
						 }
					if($row['service_type']!='DCV'){
						$sql1="select states from region_master rm join cashvan_request cvr on cvr.region = rm.region_name where rm.status='Y' and cvr.status='Y' and cvr.date='".date('Y-m-d',strtotime($row['trans_date']))."' and cvr.service_code='".$row['service_code']."' and FIND_IN_SET(rm.region_id,'".$_SESSION['region']."')  ";
						$st_exc = mysql_query($sql1);
						while($st_rw = mysql_fetch_object($st_exc)){
							$state1 = explode(",",$st_rw->states);
							$state2 = implode("','",$state1);
							$state.= "'".$state2."',";
						}
						$state = substr($state,0,-1); 
						//GUN
						$sel_gun = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Gunman%' and rc.status='Y' ");
						//DRIVER
						$sel_drive = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Driver%' and rc.status='Y'");
						//Loader
						$sel_load = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Loader%'and rc.status='Y' ");
						//CE
						$sel_ce = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Cash Van Custodian%' OR design LIKE '%Supervisor%' OR design Like '%Cashier%' OR design LIKE '%Cash Executive%' OR design LIKE '%CE%' and rc.status='Y'");
					
					}else{
						$sel_gun = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Gunman%' and status='Y' and  rc.loc_id!=0");
						//DRIVER
						$sel_drive = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Driver%' and status='Y' and  rc.loc_id!=0");
						//Loader
						$sel_load = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Loader%' and status='Y' and  rc.loc_id!=0");
						//CE
						$sel_ce = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Cash Van Custodian%' OR design LIKE '%Supervisor%' OR design Like '%Cashier%' OR design LIKE '%Cash Executive%' OR design LIKE '%CE%' and status='Y' and  rc.loc_id!=0");
					}	?>
                    <div class="table-responsive">
                        <table class="table table-hover table-nomargin table-striped table-bordered " width="100%" id="table1">
                            <thead>
                                <tr>
                                    <th colspan="10"><span align="left"> Vehicle Reg No </span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
						<!------------ NEW-------------------->
								<td>
							<label for="name"><label class="compulsory">*</label>Select Vehicle Type</label>
                    		 <select id="vehicle_type_tra" name="vehicle_type_tra" class="form-control parsley-validated chosen-select" style="margin-top:10px" tabindex="18" >
                            	<!-- <option value="">Select Vehicle Type</option> -->
                                <option value="Radiant" <?php if($row['cashvan_type']=='Radiant'||$row['cashvan_type']==''){ echo 'selected="selected"';} ?>>Radiant</option>
                                <option value="Hired" <?php if($row['cashvan_type']=='Hired'){ echo 'selected="selected"';} ?> >Hired</option>
                            </select> 
	                        </td>
							<td class="vehicle_regni_type" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?> >

							<div>
                    		<label for="name"><label class="compulsory"></label>Vehicle RegNo </label>
                    		<input type="text" id="vec__reg_no_asi" name="vec__reg_no_asi" class="form-control parsley-validated" data-required="true"  value="<?php if($row['cashvan_type']=='Hired'){echo $row['vehicle_no']; }else{}?>" placeholder="Vehicle RegNo" tabindex="4" />
							<span id="vec__reg_no_asi" class="to_hide_vhrno" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;display:none;">Enter Vehicle RegNo</span> 	
                  		   </div>
						</td>
                        <td class="vehicle_model_asv" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
                            <div>
                    		<label for="name"><label class="compulsory"></label>Vehicle Model</label>
                    		<input type="text" id="vec_model_sv" name="vec_model_sv" class="form-control parsley-validated" data-required="true" value="<?php if($row['cashvan_type']=='Hired'){echo $row['vehicle_model']; }else{}?>" placeholder="Vehicle Model" tabindex="5" />
							<span id="vec_model_sv" class="to_hide_pri_vehmodel" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px; display:none;">Enter Vehicle Model</span> 	
                  		   </div>
	                    </td>

						<!--<td class="cashvan_rbi" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
						<div>
								<label for="name">
									<label class="compulsory"></label>RBI Guidelines
								</label>
								<select id="guide_rbi" name="guide_rbi" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18">
									<option value="">Select RBI Guidelines</option>
									<option value="YES" <?php if($row['rbi_guide']=='YES'){ echo 'selected="selected"';}?>>YES</option>
									<option value="NO" <?php if($row['rbi_guide']=='NO'){ echo 'selected="selected"';}?>>NO</option>
								</select>
							</div>
	                        </td>-->

							<td class="cashvan_ac_asig" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
							<div>
							<label for="name"><label class="compulsory"></label>CashVan Type</label>
                    		 <select id="cashvan_ac" name="cashvan_ac" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18" >
                            	<option value="">Select CashVan Type</option>
                                <option value="AC" <?php if($row['ac_or_nonac']=='AC'){ echo 'selected="selected"';}?>>AC</option>
                                <option value="NON-AC" <?php if($row['ac_or_nonac']=='NON-AC'){ echo 'selected="selected"';}?>>NON-AC</option>
                            </select> 
	                       </div>
	                        </td>
		<!--------------------------- END --------------------------------------------->
                                    <td class="to_hide_vehc_num" <?php if($row['cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>> 
                                    	<input type="hidden" id="cv" name="cv" value="<?php echo $row['vehicle_no']; ?>" />
                                        <div id="cv_div" align="left">
                                            <select name="vehicle_no" id="vehicle_no" class="form-control parsley-validated chosen-select" data-required="true" onchange="get_van_details(this);" tabindex="41">
                                                    <option value="">Select Van</option>
                                                    <?php
                                                    while($van_row = mysql_fetch_assoc($sel_van)){	?>
                                                        <option value="<?php echo $van_row['registration_no'];?>" <?php if($row['vehicle_no']==$van_row['registration_no']) echo 'selected="selected"'; ?> ><?php echo $van_row['vec_no'];?></option>
                                                    <?php }
                                                    ?>
                                            </select>
											<!--<span id ="vehicleerr" name = "vehicleerr" style="display:none">Select van</span>-->
                                        </div>
                                    </td>
                                    <td colspan="3" id="to_hide_van_rad">
                                        <div id="cv_details">
                                            
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gunman - <span id="gunman_no" style="color:#09F"><?php if(isset($count_gunman)) echo $count_gunman; else echo '0'; ?></span></th>
                                    <th>Driver - <span id="driver_no" style="color:#09F"><?php if(isset($count_driver)) echo $count_driver; else echo '0';?></span></th>
                                    <th>Loader - <span id="loader_no" style="color:#09F"><?php if(isset($count_loader)) echo $count_loader; else echo '0'; ?></span></th>
                                    <th>CE - <span id="ce_no" style="color:#09F"><?php if(isset($count_ce)) echo $count_ce; else echo '0'; ?></span></th>
                                </tr>
                                <tr>
                                    <td width="10%">
                                    	<div id="gunman" style="font-size:11px">
                                            <select multiple="multiple" class="multisel checks checks_gunman" name="gunman[]" id="gunman" rel="check_gunman" onchange="count_man(this);" style="width:200px;height:90px" tabindex="42">
                                               <?php
                                                while($gun_row = mysql_fetch_assoc($sel_gun)){	?>
                                                    <option value="<?php echo $gun_row['ce_id'] ;?>" <?php if(in_array($gun_row['ce_id'],$crew_arr['gunman'])) echo 'selected="selected"'; ?> ><?php  echo $gun_row['ce_id']."/".$gun_row['ce_name'];?></option>
                                                <?php }
												while($gun_row1 = mysql_fetch_assoc($sel_gun1)){	?>
                                                    <option value="<?php echo $gun_row1['ce_id'] ;?>" <?php if(in_array($gun_row1['ce_id'],$crew_arr['gunman'])) echo 'selected="selected"'; ?> ><?php  echo $gun_row1['ce_id']."/".$gun_row1['ce_name'];?></option>
                                                <?php }	?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="driver" style="font-size:11px">
                                            <select name="driver[]" id="driver" class="multisel checks checks_driver"multiple="multiple"  onchange="count_man(this);" style="width:200px;height:90px"  rel="check_driver" tabindex="43">
                                               <?php
                                                while($drv_row = mysql_fetch_assoc($sel_drive)){	?>
                                                    <option value="<?php echo $drv_row['ce_id'];?>" <?php if(in_array($drv_row['ce_id'],$crew_arr['driver'])) echo 'selected="selected"';?> ><?php echo $drv_row['ce_id']."/".$drv_row['ce_name'];?></option>
                                                <?php } 
												while($drv_row1 = mysql_fetch_assoc($sel_drive1)){	?>
                                                    <option value="<?php echo $drv_row1['ce_id'];?>" <?php if(in_array($drv_row1['ce_id'],$crew_arr['driver'])) echo 'selected="selected"';?> ><?php echo $drv_row1['ce_id']."/".$drv_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="loader" style="font-size:11px">
                                            <select class="multisel checks checks_loader" name="loader[]" id="loader" rel="check_loader" style="width:200px;height:90px" multiple="multiple" onchange="count_man(this);" tabindex="44">
                                               <?php
                                                while($load_row = mysql_fetch_assoc($sel_load)){	?>
                                                    <option value="<?php echo $load_row['ce_id'];?>" <?php if(in_array($load_row['ce_id'],$crew_arr['loader'])) echo 'selected="selected"';?> ><?php echo $load_row['ce_id']."/".$load_row['ce_name'];?></option>
                                                <?php } 
												while($load_row1 = mysql_fetch_assoc($sel_load1)){	?>
                                                    <option value="<?php echo $load_row1['ce_id'];?>" <?php if(in_array($load_row1['ce_id'],$crew_arr['loader'])) echo 'selected="selected"';?> ><?php echo $load_row1['ce_id']."/".$load_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="ce" style="font-size:11px">
                                            <select class="multisel checks checks_ce" name="ce[]" id="ce" rel="check_ce" style="width:200px;height:90px" multiple="multiple"  onchange="count_man(this);" tabindex="45">
                                                <?php
                                                while($ce_row = mysql_fetch_assoc($sel_ce)){	?>
                                                    <option value="<?php echo $ce_row['ce_id'];?>" <?php if(in_array($ce_row['ce_id'],$crew_arr['ce'])) echo 'selected="selected"';?> ><?php echo $ce_row['ce_id']."/".$ce_row['ce_name'];?></option>
                                                <?php } 
												while($ce_row1 = mysql_fetch_assoc($sel_ce1)){	?>
                                                    <option value="<?php echo $ce_row1['ce_id'];?>" <?php if(in_array($ce_row1['ce_id'],$crew_arr['ce'])) echo 'selected="selected"';?> ><?php echo $ce_row1['ce_id']."/".$ce_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					</div>
                    <!--Alternate cashvan --> 
					<?php
						$secsel_van = mysql_query("Select registration_no,vec_no from cash_van cv where cv.status='Y' ");
                        ?>
					    <br><br>				  
                    <div class = "table-responsive">   
					<label style = "font-size:20px;color:#444">Alternate Cashvan Details</label>
					&nbsp;&nbsp;
					<input type = "checkbox" name = "altcheck" style="height:15px;width:15px;"  
					<?php if(!empty($row['sec_vehicleno']))  echo "checked='checked'"  ?> id = "altcheck" onchange = "altcashvan()">
					<table class="table table-hover table-nomargin table-striped table-bordered " width="100%" id="table2">
                            <thead>
                                <tr>
                                    <th colspan="10"><span align="left"> Vehicle Reg No </span></th>
                                </tr>
                            </thead>
                            <tbody>
							<tr>
														<!------------ NEW-------------------->
														<td>
															<div id="to_add_select">
							<label for="name"><label class="compulsory">*</label>Select Vehicle Type</label>
                    		 <select id="vehicle_type_tra_second" name="vehicle_type_tra_second" class="form-control" style="margin-top:-1px" tabindex="18" >
                            	<option value="">Select Vehicle Type</option>
                                <option value="Radiant" <?php if($row['second_cashvan_type']=='Radiant'||$row['second_cashvan_type']==''){ echo 'selected="selected"';} ?>>Radiant</option>
                                <option value="Hired" <?php if($row['second_cashvan_type']=='Hired'){ echo 'selected="selected"';} ?> >Hired</option>
                            </select> </div>
	                        </td>
							<td class="vehicle_regni_type_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?> >

							<div>
                    		<label for="name"><label class="compulsory"></label>Vehicle RegNo </label>
                    		<input type="text" id="vec__reg_no_asi_second" name="vec__reg_no_asi_second" class="form-control parsley-validated" data-required="true"  value="<?php if($row['second_cashvan_type']=='Hired'){echo $row['sec_vehicleno']; }else{}?>" placeholder="Vehicle RegNo" tabindex="4" />
							<span id="vec__reg_no_asi_second" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;"></span> 	
                  		   </div>
						</td>
                        <td class="vehicle_model_asv_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
                            <div>
                    		<label for="name"><label class="compulsory"></label>Vehicle Model</label>
                    		<input type="text" id="vec_model_sv_second" name="vec_model_sv_second" class="form-control parsley-validated" data-required="true" value="<?php if($row['second_cashvan_type']=='Hired'){echo $row['second_vehicle_model']; }else{}?>" placeholder="Vehicle Model" tabindex="5" />
							<span id="vec_model_sv_second" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;"></span> 	
                  		   </div>
	                    </td>

						<td class="cashvan_rbi_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
						<div>
								<label for="name">
									<label class="compulsory"></label>RBI Guidelines
								</label>
								<select id="guide_rbi_second" name="guide_rbi_second" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18">
									<option value="">Select RBI Guidelines</option>
									<option value="YES" <?php if($row['second_rbi_guide']=='YES'){ echo 'selected="selected"';}?>>YES</option>
									<option value="NO" <?php if($row['second_rbi_guide']=='NO'){ echo 'selected="selected"';}?>>NO</option>
								</select>
							</div>
	                        </td>

							<td class="cashvan_ac_asig_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
							<div>
							<label for="name"><label class="compulsory"></label>CashVan Type</label>
                    		 <select id="cashvan_ac_second" name="cashvan_ac_second" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18" >
                            	<option value="">Select CashVan Type</option>
                                <option value="AC" <?php if($row['secoond_ac_or_nonac']=='AC'){ echo 'selected="selected"';}?>>AC</option>
                                <option value="NON-AC" <?php if($row['secoond_ac_or_nonac']=='NON-AC'){ echo 'selected="selected"';}?>>NON-AC</option>
                            </select> 
	                       </div>
	                        </td>
		<!--------------------------- END --------------------------------------------->
							<td class="to_hide_vehc_num_second" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>> 


                                    	<input type="hidden" id="seccv" name="seccv" value="<?php echo $row['sec_vehicleno'] ?>" />
                                        <div id="cv_div" class="hide_secondary_vehc_alt" align="left" style="margin-top: 23px;">
                                            <select name="secvehicle_no" id="secvehicle_no" class="form-control parsley-validated  chosen-select" data-required="true" onchange="get_secvan_details(this);" tabindex="41">
                                                    <option value="">Select Van</option>
                                                    <?php
                                                    while($secvan_row = mysql_fetch_assoc($secsel_van)){	?>
                                                        <option value="<?php echo $secvan_row['registration_no'];?>"<?php if($row['sec_vehicleno']==$secvan_row['registration_no']) echo 'selected="selected"'; ?>><?php echo $secvan_row['vec_no'];?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                        </div>
                            </td>
							<td colspan="5" id="to_hide_secondary_van_det" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>>
							<?php
										/* updated cashvan */
										if(!empty($row['sec_vehicleno']))
										{     
											$sel_van = mysql_query("Select registration_no,vec_model,engine_no,hypo_with,vec_no from cash_van where registration_no='".$row['sec_vehicleno']."' and status='Y'");
											if(mysql_num_rows($sel_van) > 0){
												$row_van = mysql_fetch_assoc($sel_van);
												?>
                                                <div id = "altvan">
												<p style="font-size:15px"><span style="color:#6C6">	Cashvan No : </span><?php echo $row_van['registration_no']; ?>&emsp;&emsp;&emsp;&emsp; <span style="color:#6C6">Vechicle Model : </span><?php echo $row_van['vec_model']; ?> &emsp;&emsp;&emsp;<span style="color:#6C6">Vehicle Reg No : </span><?php echo $row_van['vec_no'];?></p>
												<p  style="font-size:15px"><span style="color:#6C6"> Engine No : </span><?php echo $row_van['engine_no']; ?>&emsp;&emsp;&emsp;&emsp; <span style="color:#6C6">Hypothecation with : </span><?php echo $row_van['hypo_with']; ?>
												</p>
											    </div>
										<?php		
											}
										} 
										/* updated cash van */
										?>
								<div id="cv_details" class="to_lift_secondvan" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>>
                                </div>
								</td>    
                                <tr>
								    <th>Start Time<span style="color:#09F"></span></th> 	
									<th>Starting Kilometer <span style="color:#09F"></span></th>
									<th>Ending kilometer <span  style="color:#09F"></span></th>
									<th>Total kilometer <span  style="color:#09F"></span></th>
									<th>Remarks <span  style="color:#09F"></span></th>
                                </tr>
                                <tr>
								    <td width ="10%">
									<input type = "text" name = "secstarttime" value = "<?php echo $row['secstart_time'] ?>"  placeholder = "10:15" style = "width:150px" class = "form-control parsley-validated">
									</td>
                                    <td width="10%">
									<input type = "text" name = "altstartkm"  id = "altstartkm"  value = "<?php echo $row['secstartkm'] ?>"  onkeypress="return isNumber(event)" style = "width:150px" class = "form-control parsley-validated" autocomplete="off">
                                    </td>
									<td width ="10%">
									<input type = "text" name = "altendkm"  id ="altendkm" value = "<?php echo $row['secendkm'] ?>"  style = "width:150px" onkeypress="return isNumber(event)" class = "form-control parsley-validated" autocomplete="off">
									</td>
									<td width = "10%">
									<input type = "text" name = "alttotalkm"  id = "alttotalkm"  value = "<?php echo $row['sectotalkm'] ?>" style = "width:150px" class = "form-control parsley-validated" readonly>
									</td>
									<td width = "10%">
									<textarea id="altremarks" class="form-control parsley-validated " placeholder="remarks" tabindex="28" name="altremarks" cols="10" rows="2" aria-required="true" aria-invalid="true"><?php echo $row['secremarks']; ?></textarea> 
                                    </td>
                                </tr>
							</tbody>    
						</table>    
					</div> <?php }else{ ?>

						<div class="second_st2">
                <h3 class="portlet-title"><u>Crew List Details</u></h3>
               	<div class="form-group col-sm-12">
                	<?PHP
					$sel_crw = mysql_query("Select desig,name from daily_cashvan_crew where trans_id = '".$id."' and status='Y'");

					if(mysql_num_rows($sel_crw)>0){
						while($cr_rw = mysql_fetch_object($sel_crw)){
							$crew_arr[$cr_rw->desig] = explode(",",$cr_rw->name);
							if($cr_rw->desig == 'gunman') $count_gunman = count($crew_arr[$cr_rw->desig]);
							if($cr_rw->desig == 'driver') $count_driver = count($crew_arr[$cr_rw->desig]);
							if($cr_rw->desig == 'loader') $count_loader = count($crew_arr[$cr_rw->desig]);
							if($cr_rw->desig == 'ce') $count_ce = count($crew_arr[$cr_rw->desig]);
							
						}
					}
					else
					{
						$sel_crw1= mysql_query("Select dcv_crew.desig,dcv_crew.name from dcv_crew JOIN vantrans_details ON vantrans_details.service_id=dcv_crew.service_id where vantrans_details.sid = '".$id."' and vantrans_details.status='Y' and dcv_crew.status='Y'");

						if(mysql_num_rows($sel_crw1)>0){
							while($cr_rw_m = mysql_fetch_object($sel_crw1)){
								$crew_arr[$cr_rw_m->desig] = explode(",",$cr_rw_m->name);
								if($cr_rw_m->desig == 'gunman') $count_gunman = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'driver') $count_driver = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'loader') $count_loader = count($crew_arr[$cr_rw_m->desig]);
								if($cr_rw_m->desig == 'ce') $count_ce = count($crew_arr[$cr_rw_m->desig]);
								
							}
						}

						//echo $count_gunman;exit;

					}
					
					//echo '<pre>';print_r($crew_arr);echo '</pre>';
					//Cashvan
					$sel_van = mysql_query("Select registration_no,vec_no from cash_van cv where cv.status='Y' ");
					 $region = $_SESSION['region'];
						$str_arr = explode (",", $region); 
						 if(in_array(31,$str_arr))
						 {
							 $sel_gun1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Gunman%'  and rc.status='Y' and  rc.loc_id=0");
							 
							  $sel_drive1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Driver%'  and rc.status='Y' and  rc.loc_id=0");
							  
							   $sel_load1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where rc.design LIKE '%Loader%'  and rc.status='Y' and  rc.loc_id=0");
							   
								$sel_ce1 =  mysql_query("Select distinct(rc.rec_id),rc.ce_id,rc.ce_name from radiant_ce rc  where and (
							rc.design LIKE '%Cash Van Custodian%'
							OR rc.design LIKE '%Supervisor%'
							OR rc.design LIKE '%Cashier%'
							OR rc.design LIKE '%Cash Executive%'
							OR rc.design LIKE '%CE%'
							)  and rc.status='Y' and  rc.loc_id=0");
							 
							 
						 }
					if($row['service_type']!='DCV'){
						$sql1="select states from region_master rm join cashvan_request cvr on cvr.region = rm.region_name where rm.status='Y' and cvr.status='Y' and cvr.date='".date('Y-m-d',strtotime($row['trans_date']))."' and cvr.service_code='".$row['service_code']."' and FIND_IN_SET(rm.region_id,'".$_SESSION['region']."')  ";
						$st_exc = mysql_query($sql1);
						while($st_rw = mysql_fetch_object($st_exc)){
							$state1 = explode(",",$st_rw->states);
							$state2 = implode("','",$state1);
							$state.= "'".$state2."',";
						}
						$state = substr($state,0,-1); 
						//GUN
						$sel_gun = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Gunman%' and rc.status='Y' ");
						//DRIVER
						$sel_drive = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Driver%' and rc.status='Y'");
						//Loader
						$sel_load = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Loader%'and rc.status='Y' ");
						//CE
						$sel_ce = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc join location_master lm on lm.loc_id=rc.loc_id where lm.state in(".$state.") and design LIKE '%Cash Van Custodian%' OR design LIKE '%Supervisor%' OR design Like '%Cashier%' OR design LIKE '%Cash Executive%' OR design LIKE '%CE%' and rc.status='Y'");
					
					}else{
						$sel_gun = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Gunman%' and status='Y' and  rc.loc_id!=0");
						//DRIVER
						$sel_drive = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Driver%' and status='Y' and  rc.loc_id!=0");
						//Loader
						$sel_load = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Loader%' and status='Y' and  rc.loc_id!=0");
						//CE
						$sel_ce = mysql_query("Select rc.ce_id,rc.ce_name,rc.rec_id from radiant_ce rc where design LIKE '%Cash Van Custodian%' OR design LIKE '%Supervisor%' OR design Like '%Cashier%' OR design LIKE '%Cash Executive%' OR design LIKE '%CE%' and status='Y' and  rc.loc_id!=0");
					}	?>
                    <div class="table-responsive">
                        <table class="table table-hover table-nomargin table-striped table-bordered " width="100%" id="table1">
                            <thead>
                                <tr>
                                    <th colspan="4"><span align="left"> Vehicle Reg No </span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
								
								<!------------ NEW-------------------->
								<td>
							<label for="name"><label class="compulsory">*</label>Select Vehicle Type</label>
                    		 <select id="vehicle_type_tra" name="vehicle_type_tra" class="form-control parsley-validated chosen-select" style="margin-top:10px" tabindex="18" >
                            	<!-- <option value="">Select Vehicle Type</option> -->
                                <option value="Radiant" <?php if($row['cashvan_type']=='Radiant'||$row['cashvan_type']==''){ echo 'selected="selected"';} ?>>Radiant</option>
                                <option value="Hired" <?php if($row['cashvan_type']=='Hired'){ echo 'selected="selected"';} ?> >Hired</option>
                            </select> 
	                        </td>
							<td class="vehicle_regni_type" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?> >

							<div>
                    		<label for="name"><label class="compulsory"></label>Vehicle RegNo </label>
                    		<input type="text" id="vec__reg_no_asi" name="vec__reg_no_asi" class="form-control parsley-validated" data-required="true"  value="<?php if($row['cashvan_type']=='Hired'){echo $row['vehicle_no']; }else{}?>" placeholder="Vehicle RegNo" tabindex="4" />
							<span id="vec__reg_no_asi" class="to_hide_vhrno" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;display:none;">Enter Vehicle RegNo</span> 	
                  		   </div>
						</td>
                        <td class="vehicle_model_asv" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
                            <div>
                    		<label for="name"><label class="compulsory"></label>Vehicle Model</label>
                    		<input type="text" id="vec_model_sv" name="vec_model_sv" class="form-control parsley-validated" data-required="true" value="<?php if($row['cashvan_type']=='Hired'){echo $row['vehicle_model']; }else{}?>" placeholder="Vehicle Model" tabindex="5" />
							<span id="vec_model_sv" class="to_hide_pri_vehmodel" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px; display:none;">Enter Vehicle Model</span> 	
                  		   </div>
	                    </td>

						<!--<td class="cashvan_rbi" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
						<div>
								<label for="name">
									<label class="compulsory"></label>RBI Guidelines
								</label>
								<select id="guide_rbi" name="guide_rbi" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18">
									<option value="">Select RBI Guidelines</option>
									<option value="YES" <?php if($row['rbi_guide']=='YES'){ echo 'selected="selected"';}?>>YES</option>
									<option value="NO" <?php if($row['rbi_guide']=='NO'){ echo 'selected="selected"';}?>>NO</option>
								</select>
							</div>
	                        </td>-->

							<td class="cashvan_ac_asig" <?php if($row['cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
							<div>
							<label for="name"><label class="compulsory"></label>CashVan Type</label>
                    		 <select id="cashvan_ac" name="cashvan_ac" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18" >
                            	<option value="">Select CashVan Type</option>
                                <option value="AC" <?php if($row['ac_or_nonac']=='AC'){ echo 'selected="selected"';}?>>AC</option>
                                <option value="NON-AC" <?php if($row['ac_or_nonac']=='NON-AC'){ echo 'selected="selected"';}?>>NON-AC</option>
                            </select> 
	                       </div>
	                        </td>
		<!--------------------------- END --------------------------------------------->
                                    <td class="to_hide_vehc_num" <?php if($row['cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>> 
                                    	<input type="hidden" id="cv" name="cv" value="<?php echo $row['vehicle_no']; ?>" />
                                        <div id="cv_div" align="left">
                                            <select name="vehicle_no" id="vehicle_no" class="form-control parsley-validated chosen-select" data-required="true" onchange="get_van_details(this);" tabindex="41">
                                                    <option value="">Select Van</option>
                                                    <?php
                                                    while($van_row = mysql_fetch_assoc($sel_van)){	?>
                                                        <option value="<?php echo $van_row['registration_no'];?>" <?php if($row['vehicle_no']==$van_row['registration_no']) echo 'selected="selected"'; ?> ><?php echo $van_row['vec_no'];?></option>
                                                    <?php }
                                                    ?>
                                            </select>
											<!--<span id ="vehicleerr" name = "vehicleerr" style="display:none">Select van</span>-->
                                        </div>
                                    </td>
                                    <td colspan="3"  id="to_hide_van_rad">
                                        <div id="cv_details">
                                            
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gunman - <span id="gunman_no" style="color:#09F"><?php if(isset($count_gunman)) echo $count_gunman; else echo '0'; ?></span></th>
                                    <th>Driver - <span id="driver_no" style="color:#09F"><?php if(isset($count_driver)) echo $count_driver; else echo '0';?></span></th>
                                    <th>Loader - <span id="loader_no" style="color:#09F"><?php if(isset($count_loader)) echo $count_loader; else echo '0'; ?></span></th>
                                    <th>CE - <span id="ce_no" style="color:#09F"><?php if(isset($count_ce)) echo $count_ce; else echo '0'; ?></span></th>
                                </tr>
                                <tr>
                                    <td width="10%">
                                    	<div id="gunman" style="font-size:11px">
                                            <select multiple="multiple" class="multisel checks checks_gunman" name="gunman[]" id="gunman" rel="check_gunman" onchange="count_man(this);" style="width:200px;height:90px" tabindex="42">
                                                 <?php
                                                while($gun_row = mysql_fetch_assoc($sel_gun)){	?>
                                                    <option value="<?php echo $gun_row['ce_id'] ;?>" <?php if(in_array($gun_row['ce_id'],$crew_arr['gunman'])) echo 'selected="selected"'; ?> ><?php  echo $gun_row['ce_id']."/".$gun_row['ce_name'];?></option>
                                                <?php } 
												while($gun_row1 = mysql_fetch_assoc($sel_gun1)){	?>
                                                    <option value="<?php echo $gun_row1['ce_id'] ;?>" <?php if(in_array($gun_row1['ce_id'],$crew_arr['gunman'])) echo 'selected="selected"'; ?> ><?php  echo $gun_row1['ce_id']."/".$gun_row1['ce_name'];?></option>
                                                <?php }	?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="driver" style="font-size:11px">
                                            <select name="driver[]" id="driver" class="multisel checks checks_driver"multiple="multiple"  onchange="count_man(this);" style="width:200px;height:90px"  rel="check_driver" tabindex="43">
                                               <?php
                                                while($drv_row = mysql_fetch_assoc($sel_drive)){	?>
                                                    <option value="<?php echo $drv_row['ce_id'];?>" <?php if(in_array($drv_row['ce_id'],$crew_arr['driver'])) echo 'selected="selected"';?> ><?php echo $drv_row['ce_id']."/".$drv_row['ce_name'];?></option>
                                                <?php } 
												while($drv_row1 = mysql_fetch_assoc($sel_drive1)){	?>
                                                    <option value="<?php echo $drv_row1['ce_id'];?>" <?php if(in_array($drv_row1['ce_id'],$crew_arr['driver'])) echo 'selected="selected"';?> ><?php echo $drv_row1['ce_id']."/".$drv_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="loader" style="font-size:11px">
                                            <select class="multisel checks checks_loader" name="loader[]" id="loader" rel="check_loader" style="width:200px;height:90px" multiple="multiple" onchange="count_man(this);" tabindex="44">
                                               <?php
                                                while($load_row = mysql_fetch_assoc($sel_load)){	?>
                                                    <option value="<?php echo $load_row['ce_id'];?>" <?php if(in_array($load_row['ce_id'],$crew_arr['loader'])) echo 'selected="selected"';?> ><?php echo $load_row['ce_id']."/".$load_row['ce_name'];?></option>
                                                <?php } 
												while($load_row1 = mysql_fetch_assoc($sel_load1)){	?>
                                                    <option value="<?php echo $load_row1['ce_id'];?>" <?php if(in_array($load_row1['ce_id'],$crew_arr['loader'])) echo 'selected="selected"';?> ><?php echo $load_row1['ce_id']."/".$load_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div id="ce" style="font-size:11px">
                                            <select class="multisel checks checks_ce" name="ce[]" id="ce" rel="check_ce" style="width:200px;height:90px" multiple="multiple"  onchange="count_man(this);" tabindex="45">
                                                 <?php
                                                while($ce_row = mysql_fetch_assoc($sel_ce)){	?>
                                                    <option value="<?php echo $ce_row['ce_id'];?>" <?php if(in_array($ce_row['ce_id'],$crew_arr['ce'])) echo 'selected="selected"';?> ><?php echo $ce_row['ce_id']."/".$ce_row['ce_name'];?></option>
                                                <?php } 
												while($ce_row1 = mysql_fetch_assoc($sel_ce1)){	?>
                                                    <option value="<?php echo $ce_row1['ce_id'];?>" <?php if(in_array($ce_row1['ce_id'],$crew_arr['ce'])) echo 'selected="selected"';?> ><?php echo $ce_row1['ce_id']."/".$ce_row1['ce_name'];?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					</div>
                    <!--Alternate cashvan --> 
					<?php
						$secsel_van = mysql_query("Select registration_no,vec_no from cash_van cv where cv.status='Y' ");
                        ?>
					    <br><br>				  
                    <div class = "table-responsive">   
					<label style = "font-size:20px;color:#444">Alternate Cashvan Details</label>
					&nbsp;&nbsp;
					<input type = "checkbox" name = "altcheck" style="height:15px;width:15px;"  
					<?php if(!empty($row['sec_vehicleno']))  echo "checked='checked'"  ?> id = "altcheck" onchange = "altcashvan()">
					<table class="table table-hover table-nomargin table-striped table-bordered " width="100%" id="table2">
                            <thead>
                                <tr>
                                    <th colspan="5"><span align="left"> Vehicle Reg No </span></th>
                                </tr>
                            </thead>
                            <tbody>
							<tr>
							<!------------ NEW-------------------->
														<td>
															<div id="to_add_select">
							<label for="name"><label class="compulsory">*</label>Select Vehicle Type</label>
                    		 <select id="vehicle_type_tra_second" name="vehicle_type_tra_second" class="form-control" style="margin-top:-1px" tabindex="18" >
                            	<option value="">Select Vehicle Type</option>
                                <option value="Radiant" <?php if($row['second_cashvan_type']=='Radiant'||$row['second_cashvan_type']==''){ echo 'selected="selected"';} ?>>Radiant</option>
                                <option value="Hired" <?php if($row['second_cashvan_type']=='Hired'){ echo 'selected="selected"';} ?> >Hired</option>
                            </select> </div>
	                        </td>
							<td class="vehicle_regni_type_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?> >

							<div>
                    		<label for="name"><label class="compulsory"></label>Vehicle RegNo </label>
                    		<input type="text" id="vec__reg_no_asi_second" name="vec__reg_no_asi_second" class="form-control parsley-validated" data-required="true"  value="<?php if($row['second_cashvan_type']=='Hired'){echo $row['sec_vehicleno']; }else{}?>" placeholder="Vehicle RegNo" tabindex="4" />
							<span id="vec__reg_no_asi_second" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;"></span> 	
                  		   </div>
						</td>
                        <td class="vehicle_model_asv_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
                            <div>
                    		<label for="name"><label class="compulsory"></label>Vehicle Model</label>
                    		<input type="text" id="vec_model_sv_second" name="vec_model_sv_second" class="form-control parsley-validated" data-required="true" value="<?php if($row['cashvan_type']=='Hired'){echo $row['second_vehicle_model']; }else{}?>" placeholder="Vehicle Model" tabindex="5" />
							<span id="vec_model_sv_second" style="color:#FF0000;font-size:12px; font-family:Verdana, Arial, Helvetica,sans-serif;margin-left:15px;"></span> 	
                  		   </div>
	                    </td>

						<!--<td class="cashvan_rbi_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
						<div>
								<label for="name">
									<label class="compulsory"></label>RBI Guidelines
								</label>
								<select id="guide_rbi_second" name="guide_rbi_second" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18">
									<option value="">Select RBI Guidelines</option>
									<option value="YES" <?php if($row['second_rbi_guide']=='YES'){ echo 'selected="selected"';}?>>YES</option>
									<option value="NO" <?php if($row['second_rbi_guide']=='NO'){ echo 'selected="selected"';}?>>NO</option>
								</select>
							</div>
	                        </td>-->

							<td class="cashvan_ac_asig_second" <?php if($row['second_cashvan_type']=='Hired'){ }else{echo 'style="display:none"';} ?>>
							<div>
							<label for="name"><label class="compulsory"></label>CashVan Type</label>
                    		 <select id="cashvan_ac_second" name="cashvan_ac_second" class="form-control" style="margin-top: 1px;width:200px;height: 29px;" tabindex="18" >
                            	<option value="">Select CashVan Type</option>
                                <option value="AC" <?php if($row['secoond_ac_or_nonac']=='AC'){ echo 'selected="selected"';}?>>AC</option>
                                <option value="NON-AC" <?php if($row['secoond_ac_or_nonac']=='NON-AC'){ echo 'selected="selected"';}?>>NON-AC</option>
                            </select> 
	                       </div>
	                        </td>
						<!--------------------------- END --------------------------------------------->
							<td class="to_hide_vehc_num_second" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>> 
							 
                                    	<input type="hidden" id="seccv" name="seccv" value="<?php echo $row['sec_vehicleno'] ?>" />
                                        <div id="cv_div" align="left">
                                            <select name="secvehicle_no" id="secvehicle_no" class="form-control parsley-validated  chosen-select" data-required="true" onchange="get_secvan_details(this);" tabindex="41">
                                                    <option value="">Select Van</option>
                                                    <?php
                                                    while($secvan_row = mysql_fetch_assoc($secsel_van)){	?>
                                                        <option value="<?php echo $secvan_row['registration_no'];?>"<?php if($row['sec_vehicleno']==$secvan_row['registration_no']) echo 'selected="selected"'; ?>><?php echo $secvan_row['vec_no'];?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                        </div>
                            </td>
							<td colspan="5" id="to_hide_secondary_van_det" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>>
							<?php
										/* updated cashvan */
										if(!empty($row['sec_vehicleno']))
										{     
											$sel_van = mysql_query("Select registration_no,vec_model,engine_no,hypo_with,vec_no from cash_van where registration_no='".$row['sec_vehicleno']."' and status='Y'");
											if(mysql_num_rows($sel_van) > 0){
												$row_van = mysql_fetch_assoc($sel_van);
												?>
                                                <div id = "altvan">
												<p style="font-size:15px"><span style="color:#6C6">	Cashvan No : </span><?php echo $row_van['registration_no']; ?>&emsp;&emsp;&emsp;&emsp; <span style="color:#6C6">Vechicle Model : </span><?php echo $row_van['vec_model']; ?> &emsp;&emsp;&emsp;<span style="color:#6C6">Vehicle Reg No : </span><?php echo $row_van['vec_no'];?></p>
												<p  style="font-size:15px"><span style="color:#6C6"> Engine No : </span><?php echo $row_van['engine_no']; ?>&emsp;&emsp;&emsp;&emsp; <span style="color:#6C6">Hypothecation with : </span><?php echo $row_van['hypo_with']; ?>
												</p>
											    </div>
										<?php		
											}
										} 
										/* updated cash van */
										?>
								<div id="cv_details" class="to_lift_secondvan" <?php if($row['second_cashvan_type']=='Hired'){ echo 'style="display:none"';}else{} ?>>
                                </div>
								</td>    
                                <tr>
								    <th>Start Time<span style="color:#09F"></span></th> 	
									<th>Starting Kilometer <span style="color:#09F"></span></th>
									<th>Ending kilometer <span  style="color:#09F"></span></th>
									<th>Total kilometer <span  style="color:#09F"></span></th>
									<th>Remarks <span  style="color:#09F"></span></th>
                                </tr>
                                <tr>
								    <td width ="10%">
									<input type = "text" name = "secstarttime" value = "<?php echo $row['secstart_time'] ?>"  placeholder = "10:15" style = "width:150px" class = "form-control parsley-validated">
									</td>
                                    <td width="10%">
									<input type = "text" name = "altstartkm"  id = "altstartkm"  value = "<?php echo $row['secstartkm'] ?>"  onkeypress="return isNumber(event)" style = "width:150px" class = "form-control parsley-validated">
                                    </td>
									<td width ="10%">
									<input type = "text" name = "altendkm"  id ="altendkm" value = "<?php echo $row['secendkm'] ?>"  style = "width:150px" onkeypress="return isNumber(event)" class = "form-control parsley-validated">
									</td>
									<td width = "10%">
									<input type = "text" name = "alttotalkm"  id = "alttotalkm"  value = "<?php echo $row['sectotalkm'] ?>" style = "width:150px" class = "form-control parsley-validated" readonly>
									</td>
									<td width = "10%">
									<textarea id="altremarks" class="form-control parsley-validated " placeholder="remarks" tabindex="28" name="altremarks" cols="10" rows="2" aria-required="true" aria-invalid="true"><?php echo $row['secremarks']; ?></textarea> 
                                    </td>
                                </tr>
							</tbody>    
						</table>    
					</div> <?php } ?>





                    <!--Alternate cashvan -->
                </div>
				<?php
				if ($nc1 == 0) {
					?>				
                <div class="form-group col-sm-3">
                    <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn btn_st1" style="margin-top: 10px;" tabindex="50">Update Transaction</button>
                </div>
					<?php } ?>
            </form>
    </div> <!-- /.tab-pane -->
</div> <!-- /.tab-content -->

<div id="basicModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div id="basicModal_view"></div>
            <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    	        <button type="button" class="btn btn-primary" id="save_loa"  data-dismiss="modal">Select Head Name</button>
            </div> <!-- /.modal-footer -->
        </div> <!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<style>
#secvehicle_no_chosen{
	width:281px !important;
}
.st {
    float: left;
    padding-block: 31px;
    margin-bottom: 0px;
}
.col_cs{
	display: inline-block;
    background-color: #6AD8F7;
}
.warning_box_gray {
    background-color: #E7E7E7;
    border-radius: 5px;
    padding: 8px 10px;
    float: left;
    margin: 10px 0;
    width: 97%;
}
.second_sty {
    width: 97%;
    float: left;
    background-color: #FCD194;
    border-color: #FCD194;
    color: #000;
	border-radius: 5px;
    padding: 0px;
    margin-top: -4px;
}
.second_st1 {
	background-color: #6AD8F7;
    border-radius: 5px;
    padding: 8px 10px;
    float: left;
    margin: 10px 0;
    width: 97%;
}
.second_st2 {
    background-color: #E7E7E7;
    border-radius: 5px;
    padding: 8px 26px;
    float: right;
    margin: -16px 13px;
    width: 101%;
}
.btn_st1{

	background-color: #d9534f;
    margin-left: 169%;
}
.move_to_ri {
	position: absolute;
    top: 12px;
    left: 61%;
    }

#client_acc_no-error{

	position: absolute;
    top: 68px;
    left: 16px;
}
#bill_branch-error{
	position: absolute;
}
#bill_branch-error{
	position: absolute;
}
#insurance-error{
	position: absolute;
}
#additional_crew-error
{
	position: absolute;
}
/*#vault_status-error{
	left: 10px;
    top: 65px;
    position: absolute;
}*/

#vault_status-error{
	position: absolute;
	top: 68px;
    left: 16px;
}
#other_remarks-error
{
	position: absolute;
	top: 68px;
    left: 16px;
}
#night_stay-error{
	position: absolute;
	top: 68px;
    left: 16px;
}
#vehicleerr{
	color:#F46666;
	font-weight: bold;
	font-style: italic;
}


/**************************New  */
#guide_rbi_chosen{

	width: 10pc;
}

#cashvan_ac_chosen{
	width: 10pc;
}


</style>




<script type="text/javascript"> 


$(document).ready(function(){   

	if($('#altcheck').prop('checked')==false){
		$('#vehicle_type_tra_second').val('');

	}
	
if($('#vec__reg_no_asi').val()!=undefined)
{
	// document.getElementById('vec__reg_no_asi').addEventListener('input', function (e) {
    //         this.value = this.value.replace(/[^A-Z0-9]/g, '');
    //     });
	document.getElementById('vec__reg_no_asi').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
});




		$("#vec__reg_no_asi").keyup(function(){
  if($('#vec__reg_no_asi').val()==''){$('.to_hide_vhrno').show();}else{$('.to_hide_vhrno').hide();}
});
	
}
	
if($('#vec__reg_no_asi_second').val()!=undefined)
{
	document.getElementById('vec__reg_no_asi_second').addEventListener('input', function (e) {
		this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });

		
}


if($('#vec_model_sv').val()!=undefined)
{
	$("#vec_model_sv").keyup(function(){
          if($('#vec_model_sv').val()==''){$('.to_hide_pri_vehmodel').show();}else{$('.to_hide_pri_vehmodel').hide();}
		});
	
}



		
		

	$("#to_add_select .chosen-container").css('width','200px');
	//$('#vehicle_no').select2();
	//$('.searchable-select').select2();

	$(document).on('change', '#vehicle_type_tra', function() {

		$("#cv_div .chosen-container").css('width','200px');
        console.log("Change event triggered"); 
        var selectedValue = $(this).val();
        console.log("Selected value: " + selectedValue); 

		if($('#vehicle_no').val()=='')
	    {

			$('#cv_details').hide();

			$('#cv_details').html('');

	    }
		else
		{
			$('#cv_details').show();
		}

        if (selectedValue == 'Hired') {
            $('.vehicle_regni_type').show();
            $('.vehicle_model_asv').show();
            $('.cashvan_rbi').show();
            $('.cashvan_ac_asig').show();
			$("#cv_div").hide();
			$("#Second_button").show();
			$("#First_button").hide();
			$("#to_hide_cvdetails").hide();
			$(".to_hide_vehc_num").hide();
			$("#to_hide_van_rad").hide();

			//$('#vehicle_no').val('');
			//$('#vehicle_no').removeAttr('selected');

			$('#vehicle_no option:selected').removeAttr('selected');
            $('#vehicle_no').val('');
            $('#vehicle_no').trigger("chosen:updated");

			


        } else {
            $('.vehicle_regni_type').hide();
            $('.vehicle_model_asv').hide();
            $('.cashvan_rbi').hide();
            $('.cashvan_ac_asig').hide();
			$("#cv_div").show();
			$("#Second_button").hide();
			$("#First_button").show();
			$("#to_hide_cvdetails").show();
			$(".to_hide_vehc_num").show();
			$("#to_hide_van_rad").show();

			$('#vec__reg_no_asi').val('');
			$('#vec_model_sv').val('');
			$('#guide_rbi').val('');
			$('#cashvan_ac').val('');

			$('#cv_details').show();
        }
    });


	$(document).on('change', '#vehicle_type_tra_second', function() {
		
        console.log("Change event triggered"); 
        var selectedValue2 = $(this).val();
        console.log("Selected value: " + selectedValue2); 

		if($('#secvehicle_no').val()=='')
	    {

			$('.to_lift_secondvan').hide();

			$('.to_lift_secondvan').html('');

	    }
		else
		{
			$('.to_lift_secondvan').show();
		}
        if (selectedValue2 == 'Hired') {
            $('.vehicle_regni_type_second').show();
            $('.vehicle_model_asv_second').show();
            $('.cashvan_rbi_second').show();
            $('.cashvan_ac_asig_second').show();
			//$("#cv_div").hide();
			$("#Second_button_second").show();
			$("#First_button_second").hide();
			$("#to_hide_cvdetails_second").hide();
			$(".to_hide_vehc_num_second").hide();
			$("#to_hide_van_rad_second").hide();

			$("#to_hide_secondary_van_det").hide();

			$('#secvehicle_no option:selected').removeAttr('selected');
            $('#secvehicle_no').val('');
            $('#secvehicle_no').trigger("chosen:updated");


			$('.to_lift_secondvan').html('');

			$('.hide_secondary_vehc_alt').hide();


        } else {
            $('.vehicle_regni_type_second').hide();
            $('.vehicle_model_asv_second').hide();
            $('.cashvan_rbi_second').hide();
            $('.cashvan_ac_asig_second').hide();
			//$("#cv_div").show();
			$("#Second_button_second").hide();
			$("#First_button_second").show();
			$("#to_hide_cvdetails_second").show();
			$(".to_hide_vehc_num_second").show();
			$("#to_hide_van_rad_second").show();
			$("#to_hide_secondary_van_det").show();

			$('#vec__reg_no_asi_second').val('');
			$('#vec_model_sv_second').val('');
			$('#guide_rbi_second').val('');
			$('#cashvan_ac_second').val('');

			$('.to_lift_secondvan').show();

			$('.hide_secondary_vehc_alt').show();
        }
    });

	
	$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$.validator.addMethod("phoneUS", function (phone, element) {
        phone = phone.replace(/\s+/g, "");
        return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
    }, "Enter valid phone number.");	
	//load_loc();
	if($('#id').val()!=''){
		$('#vehicle_no').trigger('change');
	}
	
	$(".multisel").multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		buttonWidth:'150px',
		maxHeight: 200,
		inheritClass: true,
		nonSelectedText: 'Select '
	});
	
	$("#start_kms").keyup(function(){
		var start_km_init = $("#start_kms").val();
		var end_km_init= $("#end_kms").val();
		var total_km_init = end_km_init-start_km_init;
		$("#total_kms").val(total_km_init);
		//alert(total_km);
	});



	$("#end_kms").keyup(function(){
		var start_km = $("#start_kms").val();
		var end_km = $("#end_kms").val();
		var total_km = end_km-start_km;
		$("#total_kms").val(total_km);
		//alert(total_km);
	});
	/* alternate  total kms */
	$("#altendkm").keyup(function(){
		var altstartkm = $("#altstartkm").val();
		var altendkm = $("#altendkm").val();
		var alttotalkm = altendkm-altstartkm;
		$("#alttotalkm").val(alttotalkm);
		//alert(total_km);
	});

	$("#altstartkm").keyup(function(){
		var altstartkm_init = $("#altstartkm").val();
		var altendkm_init = $("#altendkm").val();
		var alttotalkm_init = altendkm_init-altstartkm_init;
		$("#alttotalkm").val(alttotalkm_init);
		//alert(total_km);
	});


	
	/* alternate  total kms */
	$(".alert").hide();   
	$('.import').hide();
	$('.manual').hide();
	var id = $('#id').val();
	$('#submit').click(function(){
         
	if($('#vehicle_type_tra').val()==''&&$('#vehicle_type_tra').val()!=undefined)
	{
       alert("Please Select Cashvan Type");
	   return false;
	}

	if($('#vehicle_no').val()==''&&$('#vehicle_no').val()!=undefined&&$('#vehicle_type_tra').val()=='Radiant')
	{
		alert("Please Select Van");
	   return false;
	}

	if($('#vec__reg_no_asi').val()==''&&$('#vec__reg_no_asi').val()!=undefined&&$('#vehicle_type_tra').val()=='Hired')
	{
		//alert("Please Select RegNo");
		$('.to_hide_vhrno').show();
	   return false;
	}

	if($('#vec_model_sv').val()==''&&$('#vec_model_sv').val()!=undefined&&$('#vehicle_type_tra').val()=='Hired')
	{
		// alert("Please Select Vehicle Model");
		$('.to_hide_pri_vehmodel').show();
	   return false;
	}
	

	if($('#altcheck').prop('checked'))
	{
		var startKm_veh = parseFloat($('#altstartkm').val());
		var endKm_veh = parseFloat($('#altendkm').val());

		if (endKm_veh < startKm_veh) {
			alert('Ending kilometer must be greater than or equal to starting kilometer.');
			return false;
		}
	}

       
	   var startKm_veh_mainfi = parseFloat($('#start_kms').val());
		var endKm_veh_mainfi = parseFloat($('#end_kms').val());

		if (endKm_veh_mainfi < startKm_veh_mainfi) {

			$('#start_kms').addClass('error');
			$('#end_kms').addClass('error');
			window.scrollTo(500, 0);
			alert('Ending kilometer must be greater than or equal to starting kilometer.');
			return false;
		}



		$('#demo-validation').prop('action','add_details.php?pid=View_Logs&id='+id);
	});
	
	$('#demo-validation').validate({
			rules:{
				from_location:{
					required:true,
				},
				to_location:{
					required: true,
				},
				from_date:{
					required: true,
				},
				to_date:{
					required: true,
				},
				start_kms:{
					required: true,
				},
				end_kms:{
					required: true,
				},						
				total_kms:{
					required: true,
				},
				time_in:{
					required: true,
				},
				time_out:{
					required: true,
				},
				total_time:{
					required: true,
				},
				ot_hours:{
					required: true,
				},
				toll_tax:{
					required: true,
				},
				service_category:{
					required: true,
				},
				parking_charges:{
					required: true,
				},
				bill_branch:{
					required: true,
				},
				pickup_amount:{
					required: true,
				},
				delivery_amount:{
					required: true,
				},
				vault_status:{
					required: true,
				},
				
				no_trips:{
					required: true,
				},
				other_remarks:{
					required: true,
				},
				client_acc_no:{
					required: true,
				},
				no_cheques:{
					required: true,
				},
				permit_paid:{
					required: true,
				},
				additional_charges:{
					required: true,
				},
				tele_charge:{
					required: true,
				},
				insurance:{
					required: true,
				},
				additional_crew:{
					required: true,
				},
				night_stay:{
					required: true,
				},
				rbi_guide:{
					required: true,
				}

			},
			messages:{
				from_location:{
					required:"Select From Location",
				},
				to_location:{
					required:"Enter To Location"
				},
				from_date:{
					required:"Select Start Date"
				},
				to_date:{
					required:"Select To Date"
				},
				start_kms:{
					required:"Enter Starting KM"
				},			
				end_kms:{
					required:"Enter Ending KM"
				},			
				total_kms:{
					required:"Enter Total KMs "
				},
				time_in:{
					required:"Enter IN Time"
				},
				time_out:{
					required:"Enter Out Time"
				},
				total_time:{
					required:"Enter Total Time"
				},
				ot_hours:{
					required:"Enter OT Hours"
				},
				toll_tax:{
					required:"Enter Toll Tax"
				},
				service_category:{
					required:"Enter Serice Type"
				},
				parking_charges:{
					required:"Enter Parking Charges"
				},
				bill_branch:{
					required:"Enter BranchName to submit Bill"
				},
				pickup_amount:{
					required:"Enter Pickup Amount"
				},
				delivery_amount:{
					required:"Enter Delivery Amount"
				},
				vault_status:{
					required:"Choose Vault Status"
				},
				
				no_trips:{
					required:"Enter No of Trips"
				},
				other_remarks:{
					required:"Select Remarks"
				},
				client_acc_no:{
					required:"Choose Client Account No"
				},
				no_cheques:{
					required:"Enter No of Cheque Pickups"
				},
				permit_paid:{
					required:"Enter Permit Amount"
				},
				additional_charges:{
					required:"Enter Additional Charges"
				},
				tele_charge:{
					required:"Enter Telephone Charge"
				},
				insurance:{
					required:"Enter Insurance"
				},
				additional_crew:{
					required:"Enter additional Crew Details"
				},
				night_stay:{
					required:"Select stay"
				},
				rbi_guide:{
					required:"Select RBI Guide"
				}
			}/*,
			errorPlacement: function(error, element) {
            	if (element.attr("name") == "vault_status" )  error.appendTo('#vault_status_err');
				if (element.attr("name") == "remarks" )  error.appendTo('#remarks_err');
				if (element.attr("name") == "additional_crew" )  error.appendTo('#additional_crew_err');
				if (element.attr("name") == "insurance" )  error.appendTo('#insurance_err');
				if (element.attr("name") == "tele_charge" )  error.appendTo('#tele_charge_err');
				if (element.attr("name") == "additional_charges" )  error.appendTo('#additional_charges_err');
				if (element.attr("name") == "permit_paid" )  error.appendTo('#permit_paid_err');
				if (element.attr("name") == "no_cheques" )  error.appendTo('#no_cheques_err');
				if (element.attr("name") == "client_acc_no" )  error.appendTo('#client_acc_no_err');
				if (element.attr("name") == "no_trips" )  error.appendTo('#no_trips_err');
				if (element.attr("name") == "night_stay" )  error.appendTo('#night_stay_err');
				if (element.attr("name") == "vault_status" )  error.appendTo('#vault_status_err');
				if (element.attr("name") == "delivery_amount" )  error.appendTo('#delivery_amount_err');
				if (element.attr("name") == "pickup_amount" )  error.appendTo('#pickup_amount_err');
				if (element.attr("name") == "bill_branch" )  error.appendTo('#bill_branch_err');
				if (element.attr("name") == "parking_charges" )  error.appendTo('#parking_charges_err');
				if (element.attr("name") == "service_category" )  error.appendTo('#service_category_err');
				if (element.attr("name") == "toll_tax" )  error.appendTo('#toll_tax_err');
				if (element.attr("name") == "ot_hours" )  error.appendTo('#ot_hours_err');
				if (element.attr("name") == "total_time" )  error.appendTo('#total_time_err');
				if (element.attr("name") == "time_out" )  error.appendTo('#time_out_err');
				if (element.attr("name") == "time_in" )  error.appendTo('#time_in_err');
				if (element.attr("name") == "total_kms" )  error.appendTo('#total_kms_err');
				if (element.attr("name") == "end_kms" )  error.appendTo('#end_kms_err');
				if (element.attr("name") == "start_kms" )  error.appendTo('#start_kms_err');
				if (element.attr("name") == "to_date" )  error.appendTo('#to_date_err');
				if (element.attr("name") == "from_date" )  error.appendTo('#from_date_err');
				if (element.attr("name") == "to_location" )  error.appendTo('#to_location_err');
				if (element.attr("name") == "from_location" )  error.appendTo('#from_location_err');
			}*/
		});
});
/* altcheckbox */
 var seccv = $("#seccv").val();
	if(seccv != '')
	{
		$("#table2").show();
		//$('#altcheck').prop('checked') == true;
	}
	else{
	$("#table2").hide();
	}
	function altcashvan()
	{   
	if($('#altcheck').prop('checked')) {

         $('#table2').show();

		
       } else {
         $('#table2').hide();
       }
    }
/* altcheckbox */
 /* alternate cashvan */ 
    function get_secvan_details(obj){
		$("#altvan").hide();
		var id = $('#id').val();
		var secvan_id = $(obj).val();
		$.ajax({
			type: "POST",
			url: "ajax/get_all_detail.php",
			data: 'pid=Service_Map&id='+id+'&secvan_id='+secvan_id,
			success: function(msg){
				$(obj).closest('tr').find('#cv_details').html(msg);
			}
		});
		//$('$cv_detail').html();
	} 
	/* alternate cashvan */
	function get_van_details(obj){
		//$('#vehicleerr').hide();
		var id = $('#id').val();
		var van_id = $(obj).val();
		$.ajax({
			type: "POST",
			url: "ajax/get_all_detail.php",
			data: 'pid=Service_Map&id='+id+'&van_id='+van_id,
			success: function(msg){
				$(obj).closest('tr').find('#cv_details').html(msg);
			}
		});
		//$('$cv_detail').html();
	}
	function count_man(obj){
		cnt=0;
		var type = $(obj).attr('rel');
		var type1 = type.split("_");
		if($(obj).val()!=''){
			cnts= $('.checks_'+type1[1]+' :selected').length;
			$('#'+type1[1]+'_no').text('');
			$('#'+type1[1]+'_no').text(cnts);
		}else{
			$('#'+type1[1]+'_no').text('0');
		}
	}
	
	
	function diffs(){
		
		var start_time=$('#time_in').val();
		var end_time=$('#time_out').val();
		
		if(start_time > end_time )
		{
			alert("End Time should be greater than Start Time");
			$('#time_out').val('00:00:00');
		}
		
		if(end_time > '24:00')
		{
			alert("End Time should not be greater than 23:59");
			$('#time_out').val('00:00:00');
		}
		Date.dateDiff = function(datepart, fromdate, todate) {	
		  datepart = datepart.toLowerCase();	
		  var diff = todate - fromdate;	
		  var divideBy = { w:604800000, 
						   d:86400000, 
						   h:3600000, 
						   n:60000, 
						   s:1000 };	
		  
		  return Math.floor( diff/divideBy[datepart]);
		}
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){	dd='0'+dd	} 
		if(mm<10) {	mm='0'+mm	} 
		
		//today = dd+'-'+mm+'-'+yyyy;
		var st_date = $('#popupDatepicker').val();
		var ed_date = $('#popupDatepicker').val();
		var sd = st_date.split("-");
		var ed = ed_date.split("-");
		
		var start_time = $('#time_in').val();
		var st = start_time.split(":");
		
		var end_time = $('#time_out').val();
		var et = end_time.split(":");
		
		var dates1 = sd[2]+"-"+sd[1]+"-"+sd[0]+" "+start_time;
		var dates2 = ed[2]+"-"+ed[1]+"-"+ed[0]+" "+end_time;
		
		var y2k  = new Date(sd[2], sd[1], sd[0], st[0],st[1]);
		var today = new Date(ed[2],ed[1],ed[0],et[0],et[1]);
		var mins = Date.dateDiff('n', y2k, today);	
		var hrs1 = Math.floor(mins/60);
		var mins1=mins%60;
		if(mins1 ==0) minss = "00";else minss = mins1;
		var totalminutes =  hrs1 * 60 + mins1;
        var work_hr=$('#working_hours').val();
		var wrk=work_hr.split(":");
		var  working_hours= wrk[0];
		$('#total_time').val(hrs1+':'+minss);
		clientid = $('#clientid').val();
		
		if(clientid == '22' || clientid == '20' || clientid == '18' || clientid == '94')
			 {
				if(totalminutes >540){
			//if(hrs1 > 9)
					$('#ot_hours').val((hrs1-working_hours) +":"+minss);
					}else{
						$('#ot_hours').val('00:00');
					}
			 }
			 else
			 {
				 if(totalminutes >480){
			//if(hrs1 > 9)
					$('#ot_hours').val((hrs1-working_hours) +":"+minss);
					}else{
						$('#ot_hours').val('00:00');
					}
			 }
		
		//icici othours indusind,kotak  othours 9
		// if(clientid == '22' || clientid == '20' || clientid == '18' || clientid == '94')
		// {
			// if(totalminutes >540){
	// //if(hrs1 > 9)
			// $('#ot_hours').val((hrs1-9) +":"+minss);
			// }else{
				// $('#ot_hours').val('00:00');
			// }
        // }
        // //icici end
       // else{		
			// if(totalminutes >480){
			// //if(hrs1 > 8)
				// $('#ot_hours').val((hrs1-8) +":"+minss);
			// }else{
				// $('#ot_hours').val('00:00');
			// }
	    // }
}
	
	
	
	//Clear All Table Data
    function resetTable(){
		$('#id').val('');
		$('.sub-table').each(function(){
			id = $(this).attr('id');
			var data = "<tr>"+$("#"+id+" tbody tr:first").html()+"</tr>";
			$("#"+id+" tbody").empty();
			$("#"+id+" tbody").append(data);
			$("#"+id+" tbody tr:last").find('input[type=text]').attr('value','');
			$("#"+id+" tbody tr:last").find('.hidden').attr('value','');
			$("#"+id+" tbody tr:last").find('select').attr('value','');
			$("#"+id+" tbody tr:last").find('input[type=checkbox]').attr('value','0');
			$("#"+id+" tbody tr:last").find('textarea').val('value','');
			if($('#id').val() == ''){
				$("#"+id+" tbody tr td:last ").remove();
			}
		});
	}
    function DeleteRow(obj){
		chk_box = $('.chkbox').is(':checked'); 
		if(chk_box == true){
			var ids=$(obj).attr('id');
			id = ids.substr(0,ids.length-1)+"1";
			con = confirm("Do you want to Delete the Record?"); 
			if(con){
				//alert(id); return false;
				$('#'+id+' tbody tr').find('.chkbox:checked').each(function () {
					var rel = $(this).closest('tr').find('td:last').find('a').attr('rel');
					//alert(rel);
					closest_tr = $(this).closest('tr');
					//alert(closest_tr);
					//alert(rel); return false;
					data = rel.split('%');
					var data_id = data[0];
					var pid = data[1];
					var dtype = data[2];
					if(data_id != 0){
						delete_process(id,data_id,pid,dtype,closest_tr);
					}else{
						//alert(1000); 
						//alert(id);return false;
						var table_tr_len = $('#'+id+' tbody tr').length;
						//alert(table_tr_len); return false;
						if(table_tr_len == 1){
							var data = "<tr>"+$("#"+id+" tbody tr:first").html()+"</tr>";
							$(this).closest('tr').remove();
							$("#"+id+" tbody").append(data);
							clear_row(id);
						}else{
							$(this).closest('tr').remove();
								}
							}
						});
				
						}else{
							return false;
						}
								}
					else{
						alert("Please Select Atleast One Checkbox");	
					}
					
			}


	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
  
  
  
</script>

<?php
mysql_close($conn);
?>
