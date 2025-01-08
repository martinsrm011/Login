<?php

include('CommonReference/date_picker_link.php');
require_once __DIR__ . '/../DbConnection/dbConnect.php';
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$state = $_REQUEST['state'];


$sql_user = mysqli_query($readConnection,"select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysqli_fetch_array($sql_user);
$login_regoin_exp = explode(',', $res_user['region']);
$client_prev = $res_user['client_id'];
?>

<style type="text/css">
.wrap_point {
	word-wrap: break-word;
	width: 200px;
}
</style>
<link rel="stylesheet" type="text/css" href="css/bootstrap-multiselect.css" />
<script type="text/javascript" src="js/bootstrap-multiselect.js" ></script>
<div class="container">
  <div class="row" id="shop_aprove_id">
    <div class="col-md-12 col-sm-11">
	        <h3 class="portlet-title"> <u>RCE Point Master </u> </h3>

      <div class="portlet" <?php if($id!='') { ?> >
        <div class="clear"></div>
        <div id="load_lod_shop1" style="display:none;float:left; width:100%; text-align:center; padding:3px;"  class="alert alert-danger"></div>
        
        <div id="load_lod_shop" style="display:none;float:left; width:100%;"  class="alert"></div>
        
        <?php if($nav!='') { ?>
        <div class="message_cu">
          <div style="padding: 7px;" class="alert <?php if($nav==2) { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
            <?php
                  $status_cu = array(1=>'New Point Details Successfully Added', 2=>'Sorry, Please Try Again', 3=>'Select Point Details Updated', 4=>'Given Point Details Already Avilable. Please Search And Update');
                  echo $status_cu[$nav];
                  ?>
            </b> </div>
        </div>
        <?php }
		$sel_day = array();
			  if($id!='') {
					$sql_shop = mysqli_query($readConnection,"SELECT * FROM shop_details WHERE shop_id='".$id."'");
					$res_shop = mysqli_fetch_object($sql_shop);
					$sel_beat=$res_shop->selected_beat_days;
					$sel_day=explode(",",$sel_beat);
			  }
			  
		      
			   ?>
        <div class="clear"></div>
        <form id="shop_approve" action="<?php echo 'CommonReference/add_details.php?pid='.$pid; ?>" method="post" data-validate="parsley" class="form parsley-form" >
          <div >
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Customer Name
              </label>
			  <?php
			   $sql_cust = mysqli_query($readConnection,"SELECT a.client_name, b.cust_id, b.cust_name FROM client_details AS a JOIN cust_details AS b ON a.client_id=b.client_id WHERE b.status='Y' AND b.status='Y' and cust_id=".$res_shop->cust_id." ORDER BY a.client_name, b.cust_name");
				  $res_cus = mysqli_fetch_object($sql_cust);
							//echo 	$res_shop->cust_id."==".$res_cus->cust_id;	
					if($res_shop->cust_id==$res_cus->cust_id) 					
					$cust_name=$res_cus->client_name.' - '.$res_cus->cust_name; 
					?>
              <input type="text" id="cust_id" name="cust_id" class="form-control parsley-validated" tabindex="1" value="<?php echo $cust_name;?>" readonly="readonly" >
               
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Division Name
              </label>
              <select id="cust_div" name="cust_div" class="form-control parsley-validated chosen-select"  tabindex="2" readonly="readonly">
                <option value="NIL">NIL</option>
              </select>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point ID
              </label>
              <input type="text" id="shop_id1" name="shop_id1" class="form-control parsley-validated" value="<?php  echo $id;?>" placeholder="Customer Name" tabindex="3" readonly>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point State Name
              </label>
               <?php
                     // $sqls2 = mysqli_query($readConnection,"SELECT DISTINCT(a.state) FROM location_master AS a INNER JOIN radiant_location AS b ON a.loc_id=b.location_id
 // WHERE b.region_id IN (".$login_regoin.") AND a.`status`='Y' AND b.`status`='Y'  GROUP BY state");
                    // $res2 = mysqli_fetch_object($sqls2);
                        // if($state==$res2->state) 
							// ?>
                        <input type="text" id="state" name="state" class="form-control parsley-validated" tabindex="4" value="<?php echo $res_shop->state;?>" readonly="readonly"> 
                     
                  
            </div>
            <div class="clear"></div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point PinCode
              </label>
              <input type="text" id="pincode" name="pincode" class="form-control parsley-validated"  placeholder="Point PinCode" value="<?php echo $res_shop->pincode; ?>" tabindex="6" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point Location
              </label>
			  <?php 
			  $sqls1 = mysqli_query($readConnection,"SELECT b.loc_id, b.location, reg.region_name FROM radiant_location AS a JOIN location_master AS b ON a.location_id=b.loc_id INNER JOIN region_master as reg ON reg.region_id = a.region_id WHERE b.loc_id=".$res_shop->location." AND a.status='Y' AND b.status='Y'  ORDER BY b.location ");
				$ress1 = mysqli_fetch_object($sqls1);
				 //IF($ress1->location_id == $res_shop->location) 
				
				?>
              <input type="text" id="location" name="location" class="form-control parsley-validated" value="<?php echo $ress1->location."-".$ress1->region_name;?>" tabindex="6" readonly="readonly">
                
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Radiant LOI ID
              </label>
              <input type="text" id="loi_id" name="loi_id" class="form-control parsley-validated"  placeholder="Radiant LOI ID" value="<?php echo $res_shop->loi_id; ?>" tabindex="7" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;*</label>
              LOI Date
              </label>
              <input  type="text"  autocomplete = "off"  name="loi_date" class="form-control parsley-validated"  placeholder="LOI Date" value="<?php if($res_shop->loi_date!='0000-00-00' && $res_shop->loi_date!='') {  echo date('d-m-Y', strtotime($res_shop->loi_date)); } ?>" tabindex="8" readonly="readonly">
            </div>
            <div class="clear"></div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Customer Code
              </label>
              <textarea class="form-control parsley-validated" rows="2" cols="10" id="cust_code" name="cust_code" tabindex="9" placeholder="Customer Code" readonly="readonly"><?php echo $res_shop->customer_code; ?></textarea>
             
            </div>
            
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Customer&acute;s Point Code
              </label>
              <textarea class="form-control parsley-validated" rows="2" cols="10" id="shop_code" name="shop_code" tabindex="10" placeholder="Customer&acute;s Point Code" readonly="readonly" ><?php echo $res_shop->shop_code; ?></textarea>
              
              <span class="load_errorsss" style="color:red; display:none;">Please enter at least 10 characters.</span>
              
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point Location Code
              </label>
              <textarea class="form-control parsley-validated" rows="2" cols="10" id="loc_code" name="loc_code" tabindex="11" placeholder="Point Location Code" readonly="readonly"><?php echo $res_shop->loc_code; ?></textarea>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
                <label class="compulsory">*</label>
              Point Name
              </label>
               <textarea class="form-control parsley-validated" rows="2" cols="10" id="shop_name" name="shop_name" tabindex="12" placeholder="Point Name" readonly="readonly"><?php echo $res_shop->shop_name; ?></textarea>
             
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point Address
              </label>
              <textarea class="form-control parsley-validated" rows="2" cols="10" id="address" name="address" tabindex="13" placeholder="Point Address" readonly="readonly"><?php echo $res_shop->address; ?></textarea>
            </div>
            
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Hierarchy Code
              </label>
              <input type="text" id="hier_code" name="hier_code" class="form-control parsley-validated"  placeholder="Hierarchy Code" value="<?php echo $res_shop->hierarchy_code; ?>" tabindex="14" readonly="readonly">
            </div>
            
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Sub Customer Code
              </label>
              <input type="text" id="subcust_code" name="subcust_code" class="form-control parsley-validated"  placeholder="Sub Customer Code" value="<?php echo $res_shop->subcustomer_code; ?>" tabindex="15" readonly="readonly">
            </div>
            
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Sol ID
              </label>
              <input type="text" id="sol_id" name="sol_id" class="form-control parsley-validated"  placeholder="Sol ID" value="<?php echo $res_shop->sol_id; ?>" tabindex="16" readonly="readonly">
            </div>
            <div class="clear"></div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Division Code
              </label>
              <input type="text" id="div_code" name="div_code" class="form-control parsley-validated"  placeholder="Division Code" value="<?php echo $res_shop->div_code; ?>" tabindex="17" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point Phone No
              </label>
              <input type="text" id="phone" name="phone" class="form-control parsley-validated"  placeholder="Point Phone No" value="<?php echo $res_shop->phone; ?>" tabindex="18" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Contact Name
              </label>
              <input type="text" id="contact_name" name="contact_name" class="form-control parsley-validated"  placeholder="Contact Name" value="<?php echo $res_shop->contact_name; ?>" tabindex="19" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Contact Mobile No
              </label>
              <input type="text" id="contact_no" name="contact_no" class="form-control parsley-validated"  placeholder="Contact Mobile No" value="<?php echo $res_shop->contact_no; ?>" tabindex="20" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Email ID
              </label>
              <input type="text" id="email_id" name="email_id" class="form-control parsley-validated"  placeholder="Email ID" value="<?php echo $res_shop->email_id; ?>" tabindex="21" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Service Type
              </label>
              <input type="text" id="service_type" name="service_type" class="form-control parsley-validated" value="<?php echo $res_shop->service_type;?>"  tabindex="22" readonly="readonly">
              <br />
               <div id="mbc_types" style=" <?php if($res_shop->service_type!="MBC") { echo 'display:none;'; } ?>">
               <label class="compulsory">&nbsp;</label>
              Cash
             
              </label>
              <input type="checkbox" data-mincheck="2" name="mbc_type" <?php if($res_shop->mbc_type=='1') { echo 'checked'; } ?> class="parsley-validated" value="1" tabindex="22">
              <label class="compulsory">&nbsp;</label>
              Cheque
              </label>
              <input type="checkbox" data-mincheck="2" name="mbc_type" class="parsley-validated"  <?php if($res_shop->mbc_type=='2') { echo 'checked'; } ?> value="2" tabindex="22">
              <label class="compulsory">&nbsp;</label>
              Attendance
              </label>
              <input type="checkbox" data-mincheck="2" name="mbc_type" class="parsley-validated"  <?php if($res_shop->mbc_type=='3') { echo 'checked'; } ?> value="3" tabindex="22">
              </div>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Process
              </label>
              <input type="text" id="process" name="process" class="form-control parsley-validated"  placeholder="Process" value="<?php echo $res_shop->process; ?>" tabindex="22" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Cash Max. Limit
              </label>
              <input type="text" id="cash_limit" name="cash_limit" class="form-control parsley-validated"  placeholder="Cash Max. Limit" value="<?php echo $res_shop->cash_limit; ?>" tabindex="22" readonly="readonly">
            </div>
            <div class="clear"></div>
             
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Deposit Bank Type
              </label>
              <input type="text" id="bank_type" name="bank_type" class="form-control parsley-validated" value="<?php echo $res_shop->dep_bank;?>"  tabindex="23" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Account No.
              </label>
              <input type="text" id="account_no" name="account_no" class="form-control parsley-validated"  placeholder="Account No." value="<?php echo $res_shop->acc_id; ?>" tabindex="25" readonly="readonly">
            </div>
            
           
           
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Pickup Type
              </label>
              <input type="text" id="pickup_type" name="pickup_type" class="form-control parsley-validated" value="<?php echo $res_shop->pickup_type;?>"  tabindex="27" readonly="readonly">
                
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Feasibility Check
              </label>
              
              <input type="text" id="feasibility" name="feasibility" class="form-control parsley-validated" value="<?php echo $res_shop->feasibility;?>"  tabindex="28" readonly="readonly">
            </div>
            <div class="clear"></div>

            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">*</label>
              Point Type
              </label>
              <input type="text" id="point_type" name="point_type" class="form-control parsley-validated" value="<?php echo $res_shop->point_type;?>" tabindex="29" readonly="readonly">
             
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Remarks
              </label>
              <textarea class="form-control parsley-validated" rows="2" cols="10" id="remarks" placeholder="Remarks" name="remarks"  tabindex="30" readonly="readonly"><?php echo $res_shop->remarks; ?></textarea>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Point Activation Date
              </label>
              <input readonly="readonly" type="text"  name="sact_date" class="form-control parsley-validated"  placeholder="Point Activation Date" value="<?php if($res_shop->sact_date!='0000-00-00' && $res_shop->sact_date!='') { echo date('d-m-Y', strtotime($res_shop->sact_date)); } ?>" tabindex="31" readonly="readonly">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Point Deactivation Date
              </label>
              <input readonly="readonly" type="text" name="sdeact_date" class="form-control parsley-validated"  placeholder="Point Deactivation Date" value="<?php if($res_shop->sdeact_date!='0000-00-00' && $res_shop->sdeact_date!='') { echo date('d-m-Y', strtotime($res_shop->sdeact_date)); } ?>" tabindex="32" readonly="readonly">
            </div>
            <div class="clear"></div>

            <div class="clear"></div>
           
          </div>
        </form>
      </div>
      <!-- /.portlet-body --> 
       <?php } ?>
      <div class="clear"></div>
    
      <div class="portlet">
        <h3 class="portlet-title"> <u>Customize Search</u> </h3>
        <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Selected Point Details Deleted</b> </div>
        <form id="demo-validation" action="#" data-validate="parsley" class="form parsley-form">
          <div class="form-group col-sm-2">
              <label class="compulsory">*</label>
            <label for="name">Search Criteria </label>
            <select id="search" name="search" class="form-control parsley-validated chosen-select" data-required="true">
              <option value="">Select Option</option>
              <option value="All">All Points</option>
              <option value="Shop ID">Point ID</option>
              <option value="Shop Code">Point Location Code</option>
              <option value="Shop Name">Point Name</option>
              <option value="Point Address">Point Address</option>
              <option value="point type">Point Type</option>
              <option value="Customer Name">Customer Name</option>
              <option value="Customer Code">Customer Code</option>
              <option value="Customer Point Code">Customer Point Code</option>
              <option value="Client Name">Client Name</option>
              <option value="Point Location">Point Location</option>
              <option value="Region Name">Branch Name</option>
              <option value="unmap">Unmapped Shops</option>
              <option value="sol id">Sol Id</option>
              <option value="pincode">Pincode</option>
              
            </select>
          </div>
          <div class="form-group col-sm-2">
            <label for="name">Enter Keyword</label>
            <input type="text" id="keyword" name="keyword" class="form-control parsley-validated" data-required="true" placeholder="Enter Keyword">
          </div>
          <div class="form-group  col-sm-3">
            <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;"  onclick="search_key('1', '0')">Search Points</button>
          </div>
        </form>
        <div class="clear"></div>
        <div id="view_status"></div>
        <br />
        <?php //include("search_field.php"); ?>
        <div class="clear"></div>
        <div id="view_details_indu"></div>
      </div>
    </div>
    <!-- /.portlet --> 
    
  </div>
  <!-- /.col --> 
  
</div>
<!-- /.row -->

</div>
<!-- /.container -->
<style type="text/css">	#cust_id-error,#location-error,#state-error,#location-error,#service_type-error,#bank_type-error,#pickup_type-error,#feasibility-error,#point_type-error,#popupDatepicker-error{
	  left: 10px;
	  top: 65px;
	  position: absolute;
  }
</style>
<script type="text/javascript">

	function search_key (search_type, page_start) {
	  if($('#keyword').val()!='' || $('#search').val()=='All' || $('#search').val()=='unmap') {	  	
			$('#keyword').removeClass('error_dispaly');
			tbl_search = '';

			$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",		
				data: 'pgn=1&start_limit='+page_start+'&tbl_search='+tbl_search+'&per_page='+$('#per_page').val()+'&end_limit=10&types=4&pid=rce_point_master&search='+$('#search').val()+'&keyword='+$('#keyword').val(),
				beforeSend: function(){				
					$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
				},
				success: function(msg){
					$('#view_details_indu').html(msg);
					$('.search_field').css('display', '');

          
          $("#to_load_rce_point_data").DataTable({ ordering: false});
				}
			});
		}
		else {
			$('#keyword').addClass('error_dispaly');
		}
	}
	
</script>