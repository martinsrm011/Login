<?php
include('CommonReference/date_picker_link.php');
require_once __DIR__ . '/../DbConnection/dbConnect.php';

$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
function getvendor_id ($readConnection) {
	$sql5 = mysqli_query($readConnection,"SELECT vendor_code FROM vendor_details ORDER BY vendor_code DESC LIMIT 0,1");
	$row_ccode = mysqli_num_rows($sql5);
	if($row_ccode>0) {
		$res_ccode = mysqli_fetch_object($sql5);
		$lvendor_id1=substr($res_ccode->vendor_code,4,5)+1;
		
		$digot1 = abs(strlen($lvendor_id1)-3);
		if(strlen($lvendor_id1)<=2) {
			$s = sprintf("%0".$digot1."d", '0').$lvendor_id1;
		}
		else {
			$s = 	$lvendor_id1;
		}
		$pvendor_id="RVC".$s;
	}
	else {
		$pvendor_id = "RVC001";
	}
	return $pvendor_id;
}
?>
<div class="container">

      <div class="row">

        <div class="col-md-12 col-sm-11">

          <div class="portlet">	    

            <h3 class="portlet-title">
              <u>Radiant Vendor Master</u>
            </h3>
            <?php if($nav!='') { ?>
               <div class="message_cu">
              <div style="padding: 7px;" class="alert <?php if($nav==2) { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center">
                  <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
                  <b><?php
                  $status_cu = array(1=>'New Vendor Successfully Added', 2=>'Sorry, Please Try Again', 3=>'Selected Vendor Details Updated');
                  echo $status_cu[$nav];
                  ?> </b>
              </div>
              </div>
              <?php }
			  if($id!='') {
				  $sql1 = mysqli_query($readConnection,"SELECT * FROM vendor_details WHERE vendor_id='$id' AND status='Y'");
				  $res_clie = mysqli_fetch_object($sql1);
			  }
			   ?>
			<div class="clear"></div>
			<form id="demo-validation" action="<?php echo 'RCE/CommonReference/add_update_vendor.php?pid=add_update_vendor'; ?>" method="post" data-validate="parsley" class="form parsley-form">
				<div >
	            	<div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Radiant Vendor Code</label><br /><b><?php if($id!='') echo $res_clie->vendor_code; else echo getvendor_id($readConnection); ?></b><input type="hidden" name="vendor_code" value="<?php if($id==1) echo $res_clie->vendor_code; else echo getvendor_id($readConnection); ?>" />
	              </div>
	              <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Vendor Name </label>
	                   <input type="text" id="name" name="name" class="form-control parsley-validated" data-required="true" placeholder="Vendor Name" value="<?php echo $res_clie->vendor_name; ?>" tabindex="4">
	                  
	               </div>
	               <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Vendor Phone No.</label>
	                   <input type="text" id="phone" name="phone" class="form-control parsley-validated" data-required="true" placeholder="Vendor Phone No"  value="<?php echo $res_clie->vendor_phone; ?>" tabindex="4">
	                  
	               </div>
	               <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Vendor Email ID</label>
	                  <input type="text" name="email" class="form-control parsley-validated" data-required="true" placeholder="Vendor Email ID" tabindex="4" value="<?php echo $res_clie->vendor_email; ?>">
	                  
	               </div>
	               
	               <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Start Date</label>
	                  <input type="text" id="popupDatepicker" name="sdate" class="form-control parsley-validated" data-required="true" placeholder="Start Date" tabindex="5" value="<?php if($res_clie->start_date!='0000-00-00' && $id!='')  echo date('d-m-Y', strtotime($res_clie->start_date)); ?>">
	                  
	               </div>
	               
	               <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Vendor Address</label>
	                  <textarea class="form-control parsley-validated" rows="2" cols="10" id="address" name="address" data-minlength="5" placeholder="Vendor Address" data-required="true"><?php echo $res_clie->vendor_address; ?></textarea>
	               </div>
	               
	               
	               <div class="clear"></div>
	               <div class="form-group col-sm-2">
                   <?php if($id!='') {
						?>
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<?php  
					  }?>
	                  <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn">Save Vendor Details</button>
					  <?php   if($per=='Admin') {
				echo $res_clie->update_by.'-'.$res_clie->update_date;	
			    } ?>
	               </div>
				
	         </div>
			 
	        </form>
			
				
            </div> <!-- /.portlet-body -->
            <div class="clear"></div>
            
            <div class="portlet">	    
            
            <div class="clear"></div>
				<h3 class="portlet-title">
              <u>Current Vendors</u>
            </h3>
             
<div id="loadRCEVendor"></div>
            
            </div>	
            
                    
          </div> <!-- /.portlet -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->

    </div> <!-- /.container -->
	
<script type="text/javascript">
	$(document).ready(function() {
		
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });		
		$.validator.addMethod("phoneUS", function (phone, element) {
        phone = phone.replace(/\s+/g, "");
        return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
    }, "Enter valid phone number.");
	
	$.validator.addMethod(
		"multiemail",
		function(value, element) {
			if (this.optional(element)) // return true on optional element 
			return true;
			var emails = value.split(/[,]+/); // split element by , and ;
			valid = true;
			for (var i in emails) {
				value = emails[i];
				valid = valid &&
				$.validator.methods.email.call(this, $.trim(value), element);
			}
			return valid;
		},
		"Enter correct email format"
		);
		
		
		
		
	$("#demo-validation").validate({
		rules:{
			name:{
				required:true
			},
			phone:{
				required:true,
				phoneUS:true
			},
			email:{
				required:true,
				multiemail:true
			},
			sdate:{
				required:true
			},
			address:{
				required:true
			}
		},
		messages:{
			name:{
				required:'Enter Vendor Name.'
			},
			phone:{
				required:'Enter Vendor Phone No.'
			},
			email:{
				required:'Enter Vendor Email ID.'
			},
			sdate:{
				required:'Enter Start Date.'
			},			
			address:{
				required:'Enter Vendor Address.'				
			}
		}
	});		
});

function delete_data_rce(vendor_id){
	if(vendor_id !="" && confirm('Are You Sure!')){
		$.ajax({
          type: "POST",
          url: "RCE/AjaxReference/rceLoadData.php",
          data: 'type=delete_rce_data&vendorID='+vendor_id,
          success: function(msg) {
			alert(msg);
			if(msg == "Success"){
				loadVendorData();
			}
          }
        });
	}
}
loadVendorData();
function loadVendorData(){
		$.ajax({
          type: "POST",
          url: "RCE/AjaxReference/rceLoadData.php",
          data: 'pid=loadRCEVendorData',
          success: function(msg) {
			$("#loadRCEVendor").html(msg);
		$("#loadRCEVendor table").DataTable({ ordering: false});

          }
        });
}
</script>