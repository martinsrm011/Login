<?php
include('../DbConnection/dbConnect.php');

$id = $_REQUEST['id'];
//echo $id;
$nav = $_REQUEST['nav'];
$pid = $_REQUEST['pid'];
include('CommonReference/date_picker_link.php');
//Auto Inc ID - Service Map ID
$ser_id = "Select service_code from cashvan_services where service_id=(select max(service_id) from cashvan_services)";
$ser_exc = mysql_query($ser_id);
if(mysql_num_rows($ser_exc) > 0){
	$ser_rw = mysql_fetch_object($ser_exc);
	$tran2 = substr($ser_rw->service_code,3);
	$tran1 = substr($ser_rw->service_code,0,-5);
	$trans = str_pad($tran2+1, 5, '0', STR_PAD_LEFT);
	$auto_result = $tran1.$trans; 
}else{
	$auto_result = "RCV00001";
}
//Unmap Cash Van
$cv_id = $_REQUEST['cv_id'];
$sels = mysql_query("Select registration_no from cash_van where id='".$cv_id."' ");
$cv_row = mysql_fetch_object($sels);

//group Names
$group = array();
$sel_grp = mysql_query("Select group_name from cashvan_services where status='Y' group by group_name");
while($gr_rw = mysql_fetch_assoc($sel_grp)){
	$group[] = $gr_rw['group_name'];
}


$get_sql = "Select region_id,client_id,service_code,service_name,service_type,description,start_date,end_date,group_name from cashvan_services where service_id = '".$id."' and status='Y'";
$get_exc = mysql_query($get_sql);
$row = mysql_fetch_assoc($get_exc);


?>
<div class="container">	
	<div class="row">
		<div class="col-md-12 col-sm-11">
			<div class="portlet">	    
				<h3 class="portlet-title"><u>Service Master Details</u></h3>
				<section class="demo-section" style="margin-bottom:0">
					<?php if($nav!='') { ?>
					<div class="message_cu">
						<div style="padding: 7px;" class="alert <?php if($nav=='2_2_1' || $nav=='2_2_2' || $nav=='2_2_3' || $nav=='2_2_6') { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center">
							<a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
							<b><?php
							  $status_cu = array('2_1_1'=>'New Service Created Successfully',
							  					 '2_1_2'=>'Service Updated Successfully',
												 '2_3_6'=>'Selected Employee Contract Details Updated',
												 '2_5'=>'"CE ID: '.$id1.', Already Available, Sorry Please Try Again');
								echo $status_cu[$nav];	?> 
					  		</b>
						</div>
					</div>
              		<?php } ?>			
					 <form id="demo-validation" action="<?php echo 'CommonReference/add_details.php?pid='.$pid; ?>" method="post" data-validate="parsley" class="form parsley-form" >
                    
                    	<input type="hidden" name="service_id" id="service_id" value="<?php if(isset($id)) echo $id; ?>" />
						<div class="form-group col-sm-2">
                        	<label for="name"><label class="compulsory">*</label>Service Code</label><br />
                            <input type="text" name="service_code" id="service_code"  class="form-control parsley-validated" value="<?php if(isset($id)) echo $row['service_code']; else echo $auto_result; ?>"  readonly />
                        </div>
                       <div class="form-group col-sm-2">
                        	<label for="name"><label class="compulsory">*</label>Region</label>
                            <select class="form-control parsley-validated chosen-select" name="region_id" id="region_id" tabindex="1" data-required="true" >
                            	<option value="">Select Region</option>
              <?php
					$sel_reg = mysql_query("Select region_id,region_name from region_master where status='Y' and region_id in(".$_SESSION['region'].") order by region_name ");
								while($reg_row = mysql_fetch_assoc($sel_reg)){	?>
                                <option value="<?php echo $reg_row['region_id'];?>" <?php if($row['region_id']==$reg_row['region_id']) echo 'selected="selected"';?>><?php echo $reg_row['region_name'];?></option>
                                <?php } ?>
                            </select>
                            <span id="region_id_err" name="region_id_err"></span>
                        </div>
                        <div class="form-group col-sm-2">
                        	<label for="name"><label class="compulsory">*</label>Service Type</label>
                            <select class="form-control parsley-validated chosen-select" name="service_type" id="service_type" tabindex="2" data-required="true">
                            	<option value="">Select Service Type</option>
                                <option value="DCV" <?php if($row['service_type'] == 'DCV' ) echo 'selected="selected"'; ?> > DCV </option>
                                <option value="CVR" <?php if($row['service_type'] == 'CVR' ) echo 'selected="selected"'; ?> > CVR </option>
                                <option value="CVO" <?php if($row['service_type'] == 'CVO' ) echo 'selected="selected"'; ?> > CVO </option>
                                <option value="MBC" <?php if($row['service_type'] == 'MBC' ) echo 'selected="selected"'; ?> > MBC </option>
                            </select>
                            <span id="service_type_err" name="service_type_err"></span>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name"><label class="compulsory">*</label>Select Client </label><br />
    <?php			$clsql="Select cld.client_name,cld.client_id from client_details cld where cld.status='Y' order by client_name";
                    $cl_result =mysql_query($clsql);	?>
                            <select class="form-control parsley-validated chosen-select" id="client_id" name="client_id"  tabindex="3" data-required="true">
                                <option value="">Select Client </option>
                            <?php while($cl_row = mysql_fetch_array($cl_result)){	?>
                                <option value="<?php echo $cl_row['client_id'];?>" <?php if($row['client_id']==$cl_row['client_id']) echo 'selected="selected"'; ?> ><?php echo $cl_row['client_name']; ?></option>
                            <?php }	?>
                            </select>
                            <span id="client_id_err" name="client_id_err"></span>
                        </div>
                        <div class="form-group col-sm-3">
                        	<label for="name"><label class="compulsory">*</label>Service Name</label>
                        	<input type="text" name="service_name" id="service_name"  class="form-control parsley-validated" value="<?php  echo $row['service_name'];?>" tabindex="4" data-required="true" />
                             <span id="service_name_err" name="service_name_err"></span>
                        </div>
                        <div class="clear"></div>
                       <div class="form-group col-sm-2">
                        	<label for="name"><label class="compulsory">*</label>Start Date</label>
               				<input type="text" name="start_date" id="popupDatepicker"  class="form-control parsley-validated" value="<?php  if(isset($row['start_date']) && $row['start_date']!='0000-00-00')echo date('d-m-Y',strtotime($row['start_date'])); else echo date('d-m-Y'); ?>" tabindex="5" />             
                        </div>
                        
                      <!--  <div class="clear"></div>-->
                        <div class="form-group col-sm-2">
                        	<label for="name"><label class="compulsory">*</label>End Date</label>
               				<input type="text" name="end_date" id="end_date"  class="form-control parsley-validated" value="<?php  if($row['end_date'] == '0000-00-00')echo "";else echo $row['end_date'];?>" tabindex="6" placeholder="31-12-2016 / Lifetime" />     
                             <span id="end_date_err" name="end_date_err"></span>        
                            
                        </div>
                        <div class="form-group col-sm-3">
                        	<label for="name"><label class="compulsory">&nbsp;</label>Group Name</label>
                            <select class="form-control parsley-validated chosen-select" name="group_name" id="group_name" tabindex="2" onchange="show_group(this);">
                            	<option value="">Select Group Name</option>
                       <?php if(isset($group) && count($group)>0){	
								foreach($group as $valg){ 	?>
                                <option value="<?php echo $valg; ?>" <?php if($row['group_name']==$valg) echo 'selected="selected"';?>><?php echo $valg; ?></option>	
								<?php	
								}
							}?>
                                <option value="others" <?php if($row['other_group'] == 'others' ) echo 'selected="selected"'; ?> > Others </option>
                            </select><span id="group_name_err" name="group_name_err"></span><br /><br />
                        	<input type="text" style="display:none" name="other_group" id="other_group" class="form-control parsley-validated" value="<?php  echo $row['other_group'];?>" tabindex="5" />
                        </div>
                        <div class="form-group col-sm-3">
                        	<label for="name">Description</label>
                            <textarea cols="5" rows="3" id="description" name="description"  class="form-control parsley-validated" tabindex="7"><?php echo $row['description']; ?></textarea>
                        </div>
						<?php if($_SESSION['per']=='Admin') { ?>
                        <div class="form-group col-sm-1">
							<button type="submit" name="submit" id="submit" class="btn btn-danger search_btn"  style="margin-top:30px;"  tabindex="8">Submit </button>
						</div>
						<?php } ?>
					</form>
				</section>
           		<div class="portlet-body"><br />
                	<div id="view_status"></div>
        			<?php //include("search_field.php"); ?><br />
                	<div id="view_details_indu"></div>
        		</div>
      		</div>     
	   </div> 
    </div>
</div>


<script> 
 		
    $(document).ready(function() {      
		$('#demo-validation').submit(function(event) {

           if ($('#region_id').val() === ''){
      
               alert('Please Select Region');
               event.preventDefault();
			  // return false;
			}
			else if($('#service_type').val()===''){
				alert('Please Select Service Type');
                event.preventDefault();
			   //return false;
			}
			else if($('#client_id').val()===''){
				alert('Please Select Client');
                event.preventDefault();
			    //return false;
			}
			else if($('#service_name').val()===''){
				alert('Please Select Service Name');
                 event.preventDefault();
			}
			else if($('#popupDatepicker').val()===''){
				alert('Please Select Start Date');
                event.preventDefault();
			   //return false;
			}
			else if($('#end_date').val()===''){
				alert('Please Select End Date');
                 event.preventDefault();
			  // return false;
			}
			
  });        
       $(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});			
			setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		search_key(1,0);
		// $('#demo-validation').validate({
		// 	rules:{
		// 		region_id:{
		// 			required:true
		// 		},service_type:{
		// 			required: true,
		// 		},client_id:{
		// 			required: true,
		// 		},service_name:{
		// 			required: true,
		// 		},start_date:{
		// 			required: true,
		// 		},end_date:{
		// 			required: true,
		// 		},group_name:{
		// 			required: true,
		// 		}
		// 	},
		// 	messages:{
		// 		region_id:{
		// 			required: "Select Region"
		// 		},service_type:{
		// 			required: "Select Service Type"
		// 		},client_id:{
		// 			required: "Select Client"
		// 		},service_name:{
		// 			required: "Enter Service Name"
		// 		},start_date:{
		// 			required: "Enter Start Date"
		// 		},end_date:{
		// 			required: "Enter End Date"
		// 		},group_name:{
		// 			required: "Select Group Name"
		// 		}
		// 	},errorPlacement: function(error, element){
		// 		if (element.attr("name") == "region_id" )
		// 			error.appendTo('#region_id_err');
		// 		else if (element.attr("name") == "service_type" )
		// 			error.appendTo('#service_type_err');
		// 		else if  (element.attr("name") == "client_id" )
		// 			error.appendTo('#client_id_err');
		// 		else if  (element.attr("name") == "group_name" )
		// 			error.appendTo('#group_name_err');
		// 		else if  (element.attr("name") == "service_name" )
		// 			error.appendTo('#service_name_err');
		// 		else if  (element.attr("name") == "end_date" )
		// 			error.appendTo('#end_date_err');
		// 	}
		// });
	<?php if($id!=''){?>	load_shop(); <?php } ?>
	 if($('#service_id').val() !=''){
		 $('#submit').text('Update');
	 }else{
		$('#submit').text('Submit ');
	 }
	 
	 <?php if(isset($id)){ ?>	load_van(); 	<?php } ?>
    }); 
	function load_van(){
		var id = $('#service_id').val();
		var region = $('#region_id').val();
		$.ajax({
			type:"POST",
			url:"ajax/get_all_detail.php",
			data:{region:region,id:id,pid:"Service_Mapping"},
			success: function(res){
				var res = $.parseJSON(res);
				$('#cashvan').html(res['row_data']);
				$('#cashvan').trigger("chosen:updated");
					
			}
		});
	}
	
	function show_group(obj){
		if($(obj).val()!='' && $(obj).val() == 'others'){
			$('#other_group').css("display","block");
		}else{
			$('#other_group').css("display","none");
		}
		
	}
	function load_shop(){
		var cl_cus = $('#cust_id').val();
		var pid ="Service_Mapping";
		var id = $('#service_id').val();
		$.ajax({
			url:"ajax/get_all_detail.php",
			data:{pid:pid,client_cus:cl_cus,id:id},
			type:"POST",
			success:function(res){
				var res = $.parseJSON(res);
				$('#shop_id').html(res['row_data']);
				$('#shop_id').trigger("chosen:updated");
			}
			
		});
	}
	
	
	function search_key (search_type, page_start) {
	  if($('#keyword').val()!='') {	  	
			$('#keyword').removeClass('error_dispaly');
			tbl_search = '';
			if(search_type==1) {
				$('#tbl_search').val('');
				tbl_search = '';
				$('#tbl_search').val('');
				$.ajax({
					type: "POST",
					url: "CashVan/AjaxReference/CashVanLoadData.php",
					data: 'types=1&pid=Service_Master&search='+$('#search').val()+'&keyword='+$('#keyword').val(),
					success: function(msg){
						$('#view_status').html(msg);



						
					}
				});
			}
			else {
				tbl_search = $('#tbl_search').val();
			}
			$.ajax({
				type: "POST",
				url: "CashVan/AjaxReference/CashVanLoadData.php",		
				data: 'pgn=1&start_limit='+page_start+'&tbl_search='+tbl_search+'&per_page='+$('#per_page').val()+'&end_limit=10&types=2&pid=Service_Master&search='+$('#search').val()+'&keyword='+$('#keyword').val(),
				beforeSend: function(){				
					$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
				},
				success: function(msg){
					$('#view_details_indu').html(msg);
					$('.search_field').css('display', '');
					$("#service_masters_loads").DataTable({ ordering: false});
				}
			});
		}
	}
	
    </script> 

