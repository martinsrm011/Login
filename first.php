<?php

 //$inter1=round(17/21,0);
 //echo $inter1; 
session_start();
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$state = $_REQUEST['state'];
$aemp_id = $_REQUEST['aemp_id'];
$name1 = $_REQUEST['ce_name'];
$url = $_REQUEST['url'];
$ace_id = $_REQUEST['ace_id'];
$ce_id = $_REQUEST['ce_id'];
include('CommonReference/date_picker_link.php');

$client_login = $_REQUEST['cli'];
$id = $_REQUEST['id'];
if($id!=''){
	//echo "select * from deposit_slip where id='".$id."' and status='Y'";
	$sql=mysqli_query($readConnection,"select * from vault_out where id='".$id."' and status='Y' ");
	$row=mysqli_fetch_array($sql);
	$n2=mysqli_num_rows($sql);
	
	$trans_date=$row['trans_date'];	
	$cash_out_type=$row['cash_out_type'];	
	$_2000s=$row['2000s'];
	$_1000s=$row['1000s'];
	$_500s=$row['500s'];
	$_200s=$row['200s'];
	$_100s=$row['100s'];
	$_50s=$row['50s'];
	$_20s=$row['20s'];
	$_10s=$row['10s'];
	$_5s=$row['5s'];
	$_1s=$row['coins'];
	$deno_total=$row['deno_total'];
	$deno_diff=$row['deno_diff'];
	$id=$row['id'];
	$remarks=$row['ro_remarks'];
	$client_id=$row['client_id'];
	
	$curr_date=date('Y-m-d');
	
	$sql_deno=mysqli_query($readConnection,"select bal_2000s,bal_500s,bal_200s,bal_100s,bal_50s,bal_20s,bal_10s,bal_5s,bal_coins from closing_balance where client_id='".$client_id."' and status='Y' and trans_date <='".$curr_date."' order by trans_date desc limit 0,1");
	$rr_deno=mysqli_fetch_assoc($sql_deno);
	}
	 
	$rr=explode(",",$region);
	$rr_c=count($rr);


$fz = 0;
// IF($region !='' ){
	
// if($user=='satishn' || $user=='rohitr')
// {
// 	$fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status='1' and vault='Nagpur'"));
// }
// elseif($user=='bongi' || $user=='maruti')
// {
// 	$fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status='1'  and vault='Pune'"));
// }
// else{
//     $fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status='1' "));
// }

// }

if ($region != '') {
    $vault = '';

    if ($user == 'satishn' || $user == 'rohitr') {
        $vault = 'Nagpur';
    } elseif ($user == 'bongi' || $user == 'maruti') {
        $vault = 'Pune';
    }

    $query = "SELECT att_id FROM vault_frez_status WHERE frze_date = '" . date('Y-m-d') . "' AND status = 'Y' AND region IN (" . $region . ") AND frz_status = '1'";
              
    if ($vault != '') {
        $query .= " AND vault = '$vault'";
    }

    $fz = mysqli_num_rows(mysqli_query($readConnection, $query));
}


$prev_date = date('Y-m-d', strtotime($from_date .' -3 day'));


$last_fz = 0;
// IF($region !='' ){
// if($user=='satishn' || $user=='rohitr')
// {
// 	$last_fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."'  and status='Y' and region in(".$region.") and frz_status='1' and vault='Nagpur'"));
// }
// elseif($user=='bongi' || $user=='maruti')
// {
// 	$last_fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status='1' and vault='Pune'"));
// }
// else{
// 	$last_fz=mysqli_num_rows(mysqli_query($readConnection,"select * from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status='1'"));
// }

// }
if ($region != '') {
    $vault2 = '';

    if ($user == 'satishn' || $user == 'rohitr') {
        $vault2 = 'Nagpur';
    } elseif ($user == 'bongi' || $user == 'maruti') {
        $vault2 = 'Pune';
    }

    $query2 = "SELECT att_id FROM vault_frez_status WHERE frze_date BETWEEN '$prev_date' AND '" . date('Y-m-d') . "' AND status = 'Y' AND region IN ($region) AND frz_status = '1'";
              
    if ($vault2 != '') {
        $query2 .= " AND vault = '$vault2'";
    }

    $last_fz = mysqli_num_rows(mysqli_query($readConnection, $query2));
}


?>
<style type="text/css">
#id_proof_chosen, #bank_acc_chosen, #ce_id_chosen, #point_chosen, #type_deposit_chosen {
	width: 100% !important;
}
.style1 {
	font-weight: bold
	100;
	alignment-adjust:auto;
	animation-delay:decline;
	background-attachment:!important;
	clear:<br />

}
#branch_name-error, #state-error, #location-error, #designation-error, #gender-error, #popupDatepicker-error, #popupDatepicker1-error {
	left: 10px;
	top: 77px;
	position: absolute;
}
</style>
<script type="text/javascript">

 
	 function printDiv(divName) 
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
</head><div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-11">
      <div class="portlet">
        <div class="portlet-body">
          <h3 class="portlet-title"><u>Cash Out Entry
           </u></h3>
          <div align="center">
		  
		  		   <?php
				    $now = date('H:i:s');
		if(( $now > '12:55:00' && $now < '13:10:00' ) || ( $now > '16:55:00' && $now < '17:10:00' )){
			
			?>
			<div style="text-align:justify;">Dear Team,<br>

You were not allowed to enter the transactions on the following times.<br>

Here below mentioned Timing’s for reference<br>


1.     12.55  to 1.10 PM<br>

2.     4.55  to 5.10 PM</div>
			
		<?php } ?>
		  
		  
            <?php if($fz>0)
		  {
		   ?>
		  <div style="padding: 7px;" class="alert alert-danger"  align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>  Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.</b> </div>
		  
		  <?php } if($last_fz <=0)
		  {
		   ?>
		  <div style="padding: 7px;" class="alert alert-danger"  align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
		  Dear User<br>
“Dear User you can update the transactions in Vault only for 72 hours i.e. transactions of 3 days including the current date”<br>

However if you wish to enter transactions for the 4th date you need to close the EOD for the previous dates in a sequential date order.<br>

Refer User manual for your doubts/ queries on this. </b> </div>
		  
		  <?php }
						  $nav=$_REQUEST['nav'];
						  if($nav!="")
						  { ?>
            <div class="message_cu">
              <div style="padding: 7px;" class="alert <?php if($nav=='2_2' || $nav=='2_4' || $nav=='2_6' || $nav=='2_7' || $nav=='2_8') { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center" > <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
                <?php
				if($nav=="2_1")
				echo "New Details Successfully Added";
				elseif($nav=="2_2")
				echo "Sorry, Please Try Again";
				elseif($nav=="2_3")
				echo "Sucessfuly Delete the Details";
				elseif($nav=="2_6")
				echo "Select Details Can't deposit In ERP";
				elseif($nav=="2_8")
				echo "Dear Team,

 You were not allowed to enter the transactions on the following times.

Here below mentioned Timing’s for reference

1.     12.55  to 1.10 PM

2.     4.55  to 5.10 PM";
				elseif($nav=="2_7")
				echo " Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.";
				?>
                </b> </div>
            </div>
            <?php }			?>
          </div>
        </div>
      </div>
       <?php if($fz==0 || $last_fz ==0)
	   {
	   ?>
      <div class="portlet">
        <form id="demo-validation" action="VaultModule/add_details_vault_new.php?pid=<?php echo $pid; ?>" data-validate="parsley" enctype="multipart/form-data" class="form parsley-form" method="post">
          <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
          <div>
          
          
          
          <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
               Date
              </label>
			  <?php if($id!='')
			  { ?>
			  <input type="hidden" name="trans_date" id="trans_date" value="<?php if($id==''){ echo date('d-m-Y');} echo $trans_date; ?>">
			   <input type="text" id="trans_date" name="trans_date" class="form-control parsley-validated" data-required="true" value="<?php if($id==''){ echo date('d-m-Y');} echo $trans_date; ?>" tabindex="1" readonly>
			  <?php } else { ?>
              <input type="text" id="popupDatepicker" name="trans_date" class="form-control parsley-validated" data-required="true" value="<?php if($id==''){ echo date('d-m-Y');} echo $trans_date; ?>" tabindex="1" readonly>
			  <?php } ?>
            </div>
           <div class="form-group col-sm-2" >
                    <label for="name"><label class="compulsory">*</label>Vault Name</label>
                    <select id="vault_name" name="vault_name" onchange="vault_change()" class="form-control parsley-validated chosen-select" data-required="true"  tabindex="2"  >
                    
                    <?php if($rr_c>1) { ?>
                   <option value="">Select Vault Name</option>
                    
                    <?php
					}
					
					if($user=='bongi' || $user=='maruti')
						{
							?>
							 <option value="24" >Pune</option> 
						<?php } else if($user=='satishn' || $user=='rohitr') { ?>
				<option value="22" >Nagpur</option>
				<?php } else {
					

					IF($region !='' ){
					$sql_ce=mysqli_query($readConnection,"select vm.vault_name,vm.vault_id from vault_master vm join region_master rm on rm.region_name=vm.region  where vm.status='Y' and rm.status='Y' and rm.region_id in(".$region.") and vm.type1='Branch' order by vault_name");
					IF(mysqli_num_rows($sql_ce) > 0){
					while($res_ce = mysqli_fetch_assoc($sql_ce)) { ?>
					<option value="<?php echo $res_ce['vault_id'];?>"  <?php if($row['vault_id']==$res_ce['vault_id'])echo 'selected="selected"'; ?>><?php echo $res_ce['vault_name'];?> </option>
					<?php
					}
					}
					}


				}          ?>
                    </select><span id="vault_id_err" ></span>
                </div>
				
				
           <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Cash Out type
              </label>
              <select id="cash_type" name="cash_type" class="form-control parsley-validated chosen-select" tabindex="3" >
                <option value="">Select</option>
                
                <option value="Delivery"<?php if($cash_out_type=='Delivery')  echo 'selected="selected"';	?>>Delivery</option>
				<option value="Deposit"<?php if($cash_out_type=='Deposit')  echo 'selected="selected"';	?>>Deposit</option>
				<option value="Discrepancy"<?php if($cash_out_type=='Discrepancy')  echo 'selected="selected"';	?>>Discrepancy</option> 
				<option value="shortages"<?php if($cash_out_type=='others')  echo 'selected="selected"';	?>>Shortages</option>
                 <option value="others"<?php if($cash_out_type=='others')  echo 'selected="selected"';	?>>Others</option>
                </select><span id="cash_id_err" style="color:red;"></span>
            </div>
			
			 <div class="form-group col-sm-2" id="type_dep_id">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
             Type of Deposits
              </label>
              <select id="type_deposit" name="type_deposit" class="form-control parsley-validated chosen-select" tabindex="5" >
                <option value="">Select</option>
                <option value="Bag"<?php if($cash_out_type=='Bag')  echo 'selected="selected"';	?>>Bag</option>
				<option value="Cash"<?php if($cash_out_type=='Cash')  echo 'selected="selected"';	?>>Cash</option>
               
                </select>
            </div>
			
			<div class="form-group col-sm-2" id="bar_id">
            <label for="name">Barcode Id</label>
            <input type="text" id="bar_code" name="bar_code" class="form-control parsley-validated" value=""  onblur="load_bar();">
            <span id="bar_span" style="color:red;"></span>
          </div>
		 
		   <div class="form-group col-sm-2" id="ce_bar">
            <label for="name">CE-ID/Name</label>
            <input type="text" id="ce_id_bag" name="ce_id_bag" class="form-control parsley-validated" value="" readonly >
          </div>
		  
		    <div class="form-group col-sm-2" id="clnt_bar">
            <label for="name">Client Name</label>
            <input type="text" id="client_bag" name="client_bag" class="form-control parsley-validated" value="" readonly >
          </div>
		   <div class="form-group col-sm-2" id="cust_bar">
            <label for="name">Customer Name</label>
            <input type="text" id="cust_bag" name="cust_bag" class="form-control parsley-validated" value="" readonly >
          </div>
		  
		   <div class="form-group col-sm-2" id="point_bar">
            <label for="name">Point</label>
            <input type="text" id="point_bag" name="point_bag" class="form-control parsley-validated" value="" readonly >
          </div>
			
			
			
            <div class="form-group col-sm-2 " id="cee_id">
			
                    <label for="name"><label class="compulsory">*</label>CE-ID/Name</label>
					<div class="ce_name_id">
                    <select id="ce_id" name="ce_id" class="form-control parsley-validated chosen-select" data-required="true"  tabindex="1" onchange="load_collection()" >
                    <option value="">Select </option>
                   
                    <?php
					
				IF($region !='' ){
				$sql_ce=mysqli_query($readConnection,"select rc.ce_id,rc.ce_name from radiant_ce  rc inner join shop_cemap sc on sc.pri_ce=rc.ce_id inner join radiant_location rl  on rc.loc_id=rl.location_id where rl.region_id in(".$region.") and rl.status='Y' and rc.status='Y' and sc.status='Y'  and rc.ce_id!=''  group by rc.ce_id");
				IF(mysqli_num_rows($sql_ce) > 0){
				while($res_ce = mysqli_fetch_assoc($sql_ce)) { ?>
				<option value="<?php echo $res_ce['ce_id'];?>" <?php if($row['ce_id']==$res_ce['ce_id'])echo 'selected="selected"'; ?> ><?php  echo $res_ce['ce_id'].' / '.$res_ce['ce_name']; ?></option>
				<?php
				}
				}
				}

			   ?>
                    </select>
					</div>
                </div>
				</div>
           	<div class="form-group col-sm-2" id="client_id2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Client From
              </label>
              <select id="to_client" name="to_client" class="form-control parsley-validated chosen-select" tabindex="5">
               <option value="">Select Client Name</option>
             <?php
						IF($region !='' ){
						$client_sql="select vault_id,vault_name,client_name from vault_client_master where status='Y' and region_name in (".$region.") order by client_name"; 
						$client_s=mysqli_query($readConnection,$client_sql);
						IF(mysqli_num_rows($client_s) > 0){
						while($res_client = mysqli_fetch_object($client_s)) {
						?>
						<option value="<?php echo $res_client->vault_id; ?>"  <?php if($client_name==$res_client->vault_id)echo 'selected="selected"'; ?>><?php echo $res_client->client_name."-".$res_client->vault_name; ?></option>
						<?php	

						}
						}
						}
										
						?>

            </select>
            </div>
          
            <div class="form-group col-sm-3" id="client_id">
              <div id="fc_id"><label for="name">
              <label class="compulsory">&nbsp;</label>
              Client Name
              </label></div>
			  <div id="tc_id"><label for="name">
              <label class="compulsory">&nbsp;</label>
              Client To
              </label></div>
			  
			  <?php if($id!='')
			  {
			$client_sql="select vault_id,client_name from vault_client_master where status='Y' and vault_id='".$row['client_id']."'";  
			$client_s=mysqli_query($readConnection,$client_sql);
			$res_client = mysqli_fetch_object($client_s);
			?>
			 <input name="client" type="text" class="form-control not_text" id="client" size="10" value="<?php echo $res_client->client_name;?>" tabindex="2" readonly />
			<input type="hidden" name="client1" id="client1" class="form-control parsley-validated" value="<?php echo $row['client_id']; ?>">
			  <?php } else { ?>
              <select id="client1" name="client1" class="form-control parsley-validated chosen-select" tabindex="5" onchange="load_deno();">
               <option value="">Select Client Name</option>
             <?php
					IF($region !='' ){
					$client_sql="select vault_id,vault_name,client_name from vault_client_master where status='Y' and region_name in (".$region.") order by client_name"; 
					$client_s=mysqli_query($readConnection,$client_sql);
					IF(mysqli_num_rows($client_s) > 0){
					while($res_client = mysqli_fetch_object($client_s)) {
					?>
					<option value="<?php echo $res_client->vault_id; ?>"  <?php if($row['client_id']==$res_client->vault_id)echo 'selected="selected"'; ?>><?php echo $res_client->client_name."-".$res_client->vault_name; ?></option>
					<?php	

					}
					}
					}
									
					?>

            </select> 
			
				  <?php } ?>
			<span id="client_id_err" style="color:#F00;"></span>
            </div>
			
		<input type="hidden" name="region" id="region" value="<?php echo $region; ?>">
            
              <div class="form-group col-sm-3" id="customer_id">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Customer  Name
              </label>
              <select id="cust_name" name="cust_name" class="form-control parsley-validated chosen-select" tabindex="5">
               <option value="">Select</option>
			    <?php
										//$sql_region = mysql_query("SELECT region_name FROM region_master WHERE status='Y'");
										 $client_sql="select TRIM(cust_name) as   cust_name from cust_details where status='Y'  order by cust_name"; 
										$client_s=mysqli_query($readConnection,$client_sql);
										while($res_client = mysqli_fetch_object($client_s)) {
											?>
                  <option value="<?php echo $res_client->cust_name; ?>"   <?php if($row['customer_name']==$res_client->cust_name)echo 'selected="selected"'; ?>><?php echo $res_client->cust_name; ?></option>
                  <?php	
				
										}
										?>
            </select>
            </div>
			  <div class="form-group col-sm-2" id="point_id">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
               Point 
              </label>
              <select name="point" class="form-control parsley-validated chosen-select " id="point" tabindex="4" onChange="load_point_old()">
              <option value="">Select</option>
			  <?php
			  $sql=mysqli_query($readConnection,"SELECT lm.loc_id,lm.location FROM radiant_location rl  join location_master lm on lm.loc_id=rl.location_id  WHERE rl.status='Y' and lm.status='Y' and rl.region_id in(".$region.") order by location");
			   while($res_loc = mysqli_fetch_assoc($sql)) {
				   ?>
			   <option value="<?php echo $res_loc['loc_id'];?>"  <?php if($row['point']==$res_loc['loc_id'])echo 'selected="selected"'; ?>>
                <?php  echo $res_loc['location']; ?>
                </option>
                <?php
				   }
                 ?>
            </select>
            </div>
			 <div id="delivery_id">
            <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Transaction Id
              </label>
             <input type="text" id="trans_id" name="trans_id" class="form-control parsley-validated" value="<?php echo $row['trans_id']; ?>" placeholder="Transaction ID" tabindex="2"  >
            </div>
			</div>
			<div id="deposit_id">
			  <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Bank  Name
              </label>
              <select id="bank_name" name="bank_name" class="form-control parsley-validated chosen-select" tabindex="5">
                <option value="">Select</option>
									 <?php
									 $sql=mysqli_query($readConnection,"select * from bank_master where status='Y'");
									 while($res=mysqli_fetch_array($sql))
									 {
										?>
										<option value="<?php echo $res['acc_id'];?>"  <?php if($row['bank_name']==$res['acc_id'])echo 'selected="selected"'; ?>><?php echo $res['bank_name']." "."-"." ".$res['branch_name']." "."-"." ".$res['account_no'];?></option>
 <?php } ?>
     
              </select>
            </div>
			 <!-- <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Branch Name
              </label>
              <select id="branch_name" name="branch_name" class="form-control parsley-validated chosen-select" tabindex="5">
                <option value="">Select</option>
									 <?php
									 $sql=mysqli_query($readConnection,"select * from bank_master where status='Y'");
									 while($res=mysqli_fetch_array($sql))
									 {
										?>
										<option value="<?php echo $res['acc_id'];?>" <?php if($row['branch_name']==$res['acc_id'])echo 'selected="selected"'; ?>><?php echo $res['branch_name'];?></option>
 <?php } ?>
     
              </select>
            </div>-->
		
			
			
			 <div class="form-group col-sm-1">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Slip No
              </label>
             <input type="text" id="slip_no" name="slip_no" class="form-control parsley-validated" value="<?php echo $row['slip_no']; ?>" placeholder="Dep Slip No" tabindex="9"  >
			 <span id="slip_id" style="color:red;"></span>
            </div>
			</div>
			

 <div class="form-group col-sm-3">
            <label for="name">Remarks</label>
             <textarea cols="4" rows="1" id="remarks" name="remarks" class="form-control parsley-validated" tabindex="10"> <?php  echo $row['remarks']; ?></textarea>
			 
			 <span id="remarks_id"></span>
          </div>
		 
            <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Amount
              </label>
             <input type="text" id="amount" name="amount" class="form-control parsley-validated not_text" value="<?php echo $row['amount']; ?>" placeholder="Total Amount" tabindex="11"  onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()"><span id="amount_id_err"></span>
            </div>
			 
            
             <div class="clear"></div>
            
            <div class="clear"></div>
			<span id="deno_id" style="color:red;font-size:15px;"></span>
				<div class="alert alert-info" style="    width: 97%;
														float: left;
														background-color: #0067ac;
														border-color: #0067ac;
														color: #eee;
														padding: 0px;">
                    <div align="center"  style="padding-top:10px;"><strong>Currency Denominations </strong></div>
                    <div class="clear"></div>
                    <div class="form-group col-sm-1">
					<input name="2000s" type="text" class="form-control not_text validate_deno" id="bal_2000s" size="10" value="<?php echo $rr_deno['bal_2000s'];?>" readonly /><br>
                      <label for="name">2000 (s) </label>
					 
                      <input name="2000s" type="text" class="form-control not_text validate_deno" id="2000s" size="10" value="<?php if($n2==1) echo $_2000s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="12"/><br> 
					  
                    </div>
                  <!--  <div class="form-group col-sm-1">
                      <label for="name">1000 (s) </label>
                      <input name="1000s" type="text" class="form-control" id="1000s" size="10" value="<?php if($n2==1) echo $_1000s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" />
                    </div>-->
                    <div class="form-group col-sm-1">
					<input name="500s" type="text" class="form-control not_text validate_deno" id="bal_500s" size="10" value="<?php echo $rr_deno['bal_500s'];?>" readonly /><br> 
                      <label for="name">500 (s) </label>
					  
                      <input name="500s" type="text" class="form-control not_text validate_deno" id="500s" size="10" value="<?php if($n2==1) echo $_500s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="13"/>
                    </div>
					<div class="form-group col-sm-1"> 
					<input name="200s" type="text" class="form-control not_text validate_deno" id="bal_200s" size="10" value="<?php echo $rr_deno['bal_200s'];?>" readonly /><br>
                      <label for="name">200 (s) </label>
					 
                      <input name="200s" type="text" class="form-control not_text validate_deno" id="200s" size="10" value="<?php if($n2==1) echo $_200s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="14"/> 
                    </div>
                    <div class="form-group col-sm-1">
					<input name="100s" type="text" class="form-control not_text validate_deno" id="bal_100s" size="10" value="<?php echo $rr_deno['bal_100s'];?>" readonly /> <br>
                      <label for="name">100 (s) </label>
					 
                      <input name="100s" type="text" class="form-control not_text validate_deno" id="100s" size="10" value="<?php if($n2==1) echo $_100s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="15"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="50s" type="text" class="form-control not_text validate_deno" id="bal_50s" size="10" value="<?php echo $rr_deno['bal_50s'];?>"  readonly /><br>
                      <label for="name">50 (s)</label>
					 
                      <input name="50s" type="text" class="form-control not_text validate_deno" id="50s" size="10" value="<?php if($n2==1) echo $_50s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="16"/> 
                    </div>
                    <div class="form-group col-sm-1">
					<input name="20s" type="text" class="form-control not_text validate_deno" id="bal_20s" size="10" value="<?php echo $rr_deno['bal_20s'];?>" readonly /><br>
                      <label for="name">20 (s) </label>
					  
                      <input name="20s" type="text" class="form-control not_text validate_deno" id="20s" size="10" value="<?php if($n2==1) echo $_20s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="17"/> 
                    </div>
                    <div class="form-group col-sm-1">
					<input name="10s" type="text" class="form-control not_text validate_deno" id="bal_10s" size="10" value="<?php echo $rr_deno['bal_10s'];?>" readonly /><br>
                      <label for="name">10 (s) </label>
					  
                      <input name="10s" type="text" class="form-control not_text validate_deno" id="10s" size="10" value="<?php if($n2==1) echo $_10s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="18"/> 
                    </div>
                    <div class="form-group col-sm-1">
					<input name="5s" type="text" class="form-control not_text validate_deno" id="bal_5s" size="10" value="<?php echo $rr_deno['bal_5s'];?>"  readonly /><br>
                      <label for="name">5 (s) </label>
					   
                      <input name="5s" type="text" class="form-control not_text validate_deno" id="5s" size="10" value="<?php if($n2==1) echo $_5s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="19"/>
					  
                    </div>
                    <div class="form-group col-sm-1">
					<input name="coins" type="text" class="form-control not_text validate_deno" id="bal_coins" size="10" value="<?php echo $rr_deno['bal_coins'];?>" readonly /><br> 
                      <label for="name">Coin (s) </label>
					
                      <input name="coins" type="text" class="form-control not_text validate_deno" id="coins" size="10" value="<?php if($n2==1) echo $_1s; else echo "0";?>"  onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="20"/>  
                    </div>
                     
                    <div class="form-group col-sm-1.5" style="margin-top:-10px;">
					<br><br><br>
                      <label for="name">Total </label>
					  <br>
                      <input name="deno_total" type="text" class="form-control not_text" id="deno_total" size="10" value="<?php if($n2==1) echo $deno_total; else echo "0";?>" readonly />
                    </div>
                    <div class="form-group col-sm-1.5" style="margin-top:-10px;"><br><br><br>
                      <label for="name"> Pickup Difference </label>
					  <br>
                      <input name="deno_diff" type="text" class="form-control not_text" id="deno_diff" size="10" value="<?php if($n2==1) echo $deno_diff; else echo "0";?>" readonly />
                    </div>
                  </div>
                                <div class="clear"></div>
                                
                                <div class="form-group col-sm-2">
            <label for="name">Remarks</label>
             <textarea cols="4" rows="1" id="ro_remarks" name="ro_remarks" class="form-control parsley-validated" tabindex="21" <?php if($check_id!='') { ?>  readonly="readonly"  <?php } ?>><?php echo $remarks; ?></textarea>
          </div>
           <div class="clear"></div>
             <?php if($fz>0)
		  {
		  }
		  if($last_fz<=0)
		  {
		  }
		  else{
			  ?>
            <div class="form-group col-sm-3">
				<button type="submit" tabindex="22" name="submit" onclick="return form_sub()" id="save_id" class="btn btn-danger search_btn" style="margin-top: 23px;     padding: 5px 12px">Save </button>
            </div>
		  <?php } ?>
          </div>
        </form>
      
	   <?php } ?>	  
      </div>
      <br>
      <br>
      <div class="clear"></div>
      <div class="portlet">
        <h3 class="portlet-title"> <u>Customize Search</u> </h3>
        <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Selected Point Details Deleted</b> </div>
       
          <div class="form-group col-sm-3">
            <label for="name"><label class="compulsory">*</label>Search Vault Name</label>
            <select id="search" name="client_name" class="form-control parsley-validated chosen-select" data-required="true" tabindex="23" >
              <option value="">Select </option>
              <!--<?php
                   $sql_region = mysqli_query($readConnection,"SELECT vmn.vault_id, vm.vault_name,vmn.vaultmandate_name FROM vault_master_new vmn join vault_master vm on vm.vault_id=vmn.vault_name join region_master rm on vm.region=rm.region_name WHERE vmn.status='Y'  AND rm.region_id IN (".$region.") ORDER BY vault_name");
				   while($res_region = mysqli_fetch_assoc($sql_region)) {
					   ?>
                <option value="<?php echo $res_region['vault_id'];?>" <?php if($row->vault_name==$res_region['vault_name'])echo 'selected="selected"'; ?> >
                <?php  echo $res_region['vault_name']."-".$res_region['vaultmandate_name']; ?>
                </option>
                <?php
				   }
                 ?>-->
				 <option value="all">All</option>
              <option value="client_name">Client Name</option>
              <option value="cust_name">Customer Name</option>

            </select>
          </div>
		    <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              From Date
              </label>
              <input type="text" id="popupDatepicker1" name="from_date" class="form-control parsley-validated" data-required="true" value="" tabindex="24" readonly>
            </div>
			 <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              To Date
              </label>
              <input type="text" id="popupDatepicker2" name="to_date" class="form-control parsley-validated" data-required="true" value="" tabindex="25" readonly>
            </div>
           <div class="form-group col-sm-2">
            <label for="name"><label class="compulsory to_hide_star">*</label>Enter Keyword</label>
            <input type="text" id="keyword" name="keyword" class="form-control parsley-validated" data-required="true" placeholder="Enter Keyword" tabindex="26">
          </div>
		   <input type="hidden" name="frz" id="frz" value="<?php echo $fz; ?>">
		   <input type="hidden" name="last_fz" id="last_fz" value="<?php echo $last_fz; ?>">
		   <input type="hidden" name="now1" id="now1" value="<?php echo $now ?>">
          <div class="form-group  col-sm-3">
            <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;"  onclick="search_key('1', '0')"  tabindex="27">Search</button> 
          </div>
       
        <div class="clear"></div>
		  <div style="padding: 7px;" class="alert alert-danger"  id="freeze" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a><b>  Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.</b>  </div>

 <div style="padding: 7px;" class="alert alert-danger"  id="freeze1" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a><b> Dear User<br>
“Dear User you can update the transactions in Vault only for 72 hours i.e. transactions of 3 days including the current date”<br>

However if you wish to enter transactions for the 4th date you need to close the EOD for the previous dates in a sequential date order.<br>

Refer User manual for your doubts/ queries on this. </b>  </div>

<div style="text-align:justify;" id="frr">Dear Team,<br>

You were not allowed to enter the transactions on the following times.<br>

Here below mentioned Timing’s for reference<br>


1.     12.55  to 1.10 PM<br>

2.     4.55  to 5.10 PM</div>

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
</body>
</html>
<?php //include ('dataTableScript.php'); ?>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="Stylesheet" type="text/css" />-->
<script>

	$(document).ready(function(){

        $("#search").change(function(){
        
			if($("#search").val()=='all'){

				$('.to_hide_star').hide();
			}
			else if($("#search").val()=='client_name'){
				$('.to_hide_star').show();
			}
			else if($("#search").val()=='cust_name'){
				$('.to_hide_star').show();
			}
}); 
          		
		$('#deposit_id').hide();
		$('#delivery_id').hide();
		$('#cee_id').hide();
		$('#shortage_id').hide();
		$('#other_slip_id').hide();
		$('#type_dep_id').hide();
		$('#client_id2').hide();
		$('#tc_id').hide();
		$('#point_id').hide();
		$('#bar_id').hide();
		$('#ce_bar').hide();
		$('#clnt_bar').hide();
		$('#cust_bar').hide();
		$('#point_bar').hide();
		
		$('#freeze').hide();
		$('#freeze1').hide();
		$('#frr').hide();
			$('#deposit_id').show();
		$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});	
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		//search_key(1, 0);
		//load_up(this);
		//load_customer()
		
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$("#demo-validation").validate({
		rules:{
			cash_type:{
				required:true
			},
			client1:{
				//required:true
			},
			amount:{
				required:true
			},
			remarks:{
				//required:true
			},
			
			
			
		},
		messages:{
		cash_type:{
				required:'Select Cash Out Type.'
			},	
			client1:{
				//required:'Select Client Name.'
			},	
		amount:{
				required:'Enter The Amount.'
			},	
			remarks:{
				//required:'Enter The Remarks.'
			}
			
			
			
		},errorPlacement: function(error, element) {
					
					 if (element.attr("name") == "cash_type" )
						error.appendTo('#cash_id_err'); 
					else if (element.attr("name") == "client1" )
						error.appendTo('#client_id_err');
					else if  (element.attr("name") == "amount" )
						error.appendTo('#amount_id_err');
					else if  (element.attr("name") == "remarks" )
						error.appendTo('#remarks_id');
					
					
					
					}
	});
	
	$(".not_text").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                //$("#errmsg").html("Digits Only").show().fadeOut("slow");
                      return false;
            }
        });
		
		$('.validate_deno').on('blur',function(){
			var x = $(this).attr('id');
			var val = $(this).val();
			var res = new Array();
			$.getJSON("VaultModule/AjaxReference/load_data_vault.php?pid=deno_check&client="+$("#client1").val(), function(data){
				$.each(data, function( index, value ) {	
					res[index] = value;
				});
				
			y = 'bal_'+x;
			console.log(res[y]+' , '+val);
			
			
			if(parseInt(val) > parseInt(res[y])){
				
			/*var r = confirm("Amount Exceeds From actual "+x);
			if (r == true) {
			$(x).val(0);
			return;
			}*/
			$('#deno_id').html('“Warning!  Data Entry Error -Denomination Value Mismatch with the totals, please check and enter the correct values to proceed further” ');
			$('#'+x).val('0');
			$('#save_id').hide();
			//return false;
			}
			else {
				$('#deno_id').html('');
			}
			});
			
			
		});
		$("#popupDatepicker1").datepicker({
            dateFormat: "dd-mm-yy",
			minDate:'-1m',
            maxDate: 0,
            onSelect: function (date) {
                var dt2 = $('#popupDatepicker2');
                var startDate = $(this).datepicker('getDate');
                var minDate = $(this).datepicker('getDate');
                dt2.datepicker('setDate', minDate);
                startDate.setDate(startDate.getDate() + 6);
                //sets dt2 maxDate to the last day of 30 days window
                dt2.datepicker('option', 'maxDate', startDate);
                dt2.datepicker('option', 'minDate', minDate);
               // $(this).datepicker('option', 'minDate', minDate);
            }
        });
        $('#popupDatepicker2').datepicker({
            dateFormat: "dd-mm-yy",
			minDate:'-1m',
            maxDate: 0,
        });
		
		$('#popupDatepicker').datepicker({
            dateFormat: "dd-mm-yy"
        });
	
	});
	
	
	
	function search_key(search_type, page_start) {
		$('#view_details_indu').css('display', '');
		 var fz=$('#frz').val();
		  var last_fz=$('#last_fz').val();
		  var now1=$('#now1').val();
        
		if($('#search').val()==''){
			alert('Please Select Search Criteria');
			return false;
		}
		else if($('#search').val()=='client_name'&&$('#keyword').val()==''){
			alert('Please Enter Keyword');
			return false;
		}
		else if($('#search').val()=='cust_name'&&$('#keyword').val()=='')
		{
			alert('Please Enter Keyword');
			return false;
		}

		if(fz>0)
		 {
			  $('#freeze').show();
		 }
		 if(last_fz<=0)
		 {
			  $('#freeze1').show();
		 }
		  else if(( now1 > '12:55:00' && now1 < '13:10:00' ) || ( now1 > '16:55:00' && now1 < '17:10:00' )){
			$('#frr').show();  
		 }
		else{
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/load_data_vault_new.php",
			data : 'pgn=1&start_limit=' + page_start + '&per_page=' + $('#per_page').val() + '&end_limit=10&types=1&pid=<?php echo $pid; ?>&from_date=' + $('#popupDatepicker1').val()+'&to_date=' + $('#popupDatepicker2').val()+'&region=' + $('#region').val()+'&search=' + $('#search').val()+'&tbl_search='+$('#tbl_search').val()+'&keyword='+$('#keyword').val(),
			beforeSend : function() {
				$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
			},
			success : function(msg) {
				$('#view_details_indu').html(msg);
				$('.search_field').css('display', '');


$("#load_cash_out_new").DataTable({ ordering: false});

			}
		});
		}
	}
	
	
	function load_deno()
	{
		var client=$('#client1').val();
		
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/load_data_vault.php",
			data : 'pid=load_deno&client='+$('#client1').val(),
			success : function(msg) {
				var res = $.parseJSON(msg);
				//alert(res['bal_2000s']);
				$('#bal_2000s').val(res['bal_2000s']);
				$('#bal_500s').val(res['bal_500s']);
				$('#bal_200s').val(res['bal_200s']);
				$('#bal_100s').val(res['bal_100s']);
				$('#bal_50s').val(res['bal_50s']);
				$('#bal_20s').val(res['bal_20s']);
				$('#bal_10s').val(res['bal_10s']);
				$('#bal_5s').val(res['bal_5s']);
				$('#bal_coins').val(res['bal_coins']);
				
			}
		});
	}
	
	function load_location()
	 {
		$.ajax({
			type : "POST",
		
			url : "VaultModule/AjaxReference/VaultLoadData.php",
			data : 'types=2&pid=dep_slip&region_name='+$('#region_name').val()+'&id='+$('#id').val(),
			success : function(msg) {
				$('#location').html(msg);
				$('#location').trigger("chosen:updated");
			}
		});
	 }
	function load_account() {
		$.ajax({
			type : "POST",	
			url : "VaultModule/AjaxReference/VaultLoadData.php",
			data : 'types=3&pid=dep_slip&dep_type='+$('#dep_type').val()+'&id='+$('#id').val(),
			success : function(msg) {
				$('#acc_id').html(msg);
				$('#acc_id').trigger("chosen:updated");
			}
		});
	}
	
	
		function load_ce() {
		
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/VaultLoadData.php",
			data : 'types=4&pid=dep_slip&location='+$('#location').val()+'&id='+$('#id').val(),
			success : function(msg) {
				$('#ce_name').html(msg);
				$('#ce_name').trigger("chosen:updated");
			}
		});
	}
	
	 
	// new
function cal_deno()
{
	
	
	
	

var pick_amount=document.getElementById("amount").value;
//var pick_amount=0;
var d2000s=document.getElementById("2000s").value;
//var d1000s=document.getElementById("1000s").value;
var d500s=document.getElementById("500s").value;
var d200s=document.getElementById("200s").value;
var d100s=document.getElementById("100s").value;
var d50s=document.getElementById("50s").value;
var d20s=document.getElementById("20s").value;
var d10s=document.getElementById("10s").value;
var d5s=document.getElementById("5s").value;
var dcoins=document.getElementById("coins").value;
var tot=document.getElementById("deno_total").value;

//var od100s=document.getElementById("o100s").value;
document.getElementById("deno_total").value=(d2000s*2000)+(d500s*500)+(d200s*200)+(d100s*100)+(d50s*50)+(d20s*20)+(d10s*10)+(d5s*5)+(dcoins*1);
document.getElementById("deno_diff").value=document.getElementById("deno_total").value-pick_amount;

if(pick_amount==tot)
{
	$('#save_id').show();
}
else{
	$('#save_id').hide();
}





}


	function load_bar()
	{
		$('#ce_bar').show();
		$('#clnt_bar').show();
		$('#cust_bar').show();
		$('#point_bar').show();
		var bar_code=$('#bar_code').val();
		var vault_id=$('#vault_name').val();
		
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/load_data_vault_new.php",
			data : 'pid=bar_code&bar_code='+$('#bar_code').val()+'&vault_id='+vault_id,
			success : function(msg) {
				
				var res = $.parseJSON(msg);
				//alert(res['client1'])
				$('#ce_id_bag').val(res['ce_id']);
				$('#amount').val(res['amount']);
				$('#cust_bag').val(res['customer_name']);
				$('#client_bag').val(res['client_name']);
				$('#client_idn').val(res['client1']);
				$('#point_bag').val(res['point']);
				$('#2000s').val(res['2000s']);
				$('#500s').val(res['500s']);
				$('#200s').val(res['200s']);
				$('#100s').val(res['100s']);
				$('#50s').val(res['50s']);
				$('#20s').val(res['20s']);
				$('#10s').val(res['10s']);
				$('#5s').val(res['5s']);
				$('#coins').val(res['coins']);
				$('#deno_total').val(res['deno_total']);
				$('#deno_diff').val(res['deno_diff']);
				
				
			}
		});
	}
	
	
	function load_up(id) {
		//alert(id);
	//	var id=$('');;
		$.ajax({
			type : "POST",	
			url : "VaultModule/AjaxReference/VaultLoadData.php",
			data : 'pid=checked'+'&id='+id,
			success: function(msg){
			if(msg=="succ"){search_key(1, 0);}
				}

		});
	}
		function load_delete(id,file_name) {
		var x=confirm("Are you sure you want to delete?");
		 
		 if(x)
		 {
			
		//var id=$(obj).attr('rel');
		$.ajax({
			type : "POST",	
			url : "delete.php",
			data : 'pid=move_file'+'&id='+id+'&file_name='+file_name,
			success: function(msg){
				
				if(msg=="succ"){search_key(1, 0);
				
				
		
				}
				}

		});
		
		 }
		 else
		 { 
		 
			 return false;
		 }
		
	}
	
	$('#cash_type').change(function()
	{
		var type=$('#cash_type').val();
		if(type=='Delivery')
		{
			$('#delivery_id').show();
			$('#deposit_id').hide();
			$('#shortage_id').hide();
			$('#cee_id').show();
			$('#other_slip_id').hide();
			$('#type_dep_id').hide();
			$('#client_id').show();
			$('#customer_id').show();
			$('#point_id').show();
			$('#client_id2').hide();
			$('#tc_id').hide();
			$('#fc_id').show();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
		}
		else if(type=='Deposit')
		{
			$('#type_dep_id').show();
			$('#client_id').show();
			$('#client_id2').hide();
			$('#tc_id').hide();
			$('#fc_id').show();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
		}	
		else if(type=='internal_short')
		{
			$('#delivery_id').hide();
			$('#deposit_id').hide();
			$('#cee_id').hide();
			$('#other_slip_id').hide();
			$('#shortage_id').show();
			$('#type_dep_id').hide();
			$('#point_id').hide();
			$('#customer_id').hide();
			$('#client_id').show();
			$('#client_id2').show();
			$('#tc_id').show();
			$('#fc_id').hide();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
			
		}
		else if(type=='shortages')
		{
			$('#delivery_id').hide();
			$('#deposit_id').hide();
			$('#shortage_id').hide();
			$('#other_slip_id').hide();
			$('#cee_id').hide();
			$('#type_dep_id').hide();
			$('#customer_id').hide();
			$('#point_id').hide();
			$('#client_id').show();
			$('#client_id2').hide();
			$('#tc_id').hide();
			$('#fc_id').show();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
		}
		else if(type=='Discrepancy')
		{
			$('#delivery_id').hide();
			$('#deposit_id').hide();
			$('#shortage_id').hide();
			$('#other_slip_id').hide();
			$('#cee_id').hide();
			$('#type_dep_id').hide();
			$('#customer_id').hide();
			$('#point_id').hide();
			$('#client_id').show();
			$('#client_id2').hide();
			$('#tc_id').hide();
			$('#fc_id').show();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
		}
		
		else if(type=='others')
		{
			$('#delivery_id').hide();
			$('#deposit_id').hide();
			$('#shortage_id').hide();
			$('#cee_id').hide();
			$('#type_dep_id').hide();
			$('#client_id').show();
			$('#customer_id').hide();
			$('#client_id2').hide();
			$('#tc_id').hide();
			$('#fc_id').show();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
			$('#bar_id').hide();
		}
		
	});
	
	$('#type_deposit').change(function()
	{
		var dep=$('#type_deposit').val();
		if(dep=='Bag')
		{
			$('#delivery_id').hide();
			$('#shortage_id').hide();
			$('#deposit_id').show();
			$('#cee_id').show();
			$('#other_slip_id').hide();
			$('#customer_id').hide();
			$('#client_id').hide();
			$('#client_id2').hide();
			$('#point_id').hide();
			$('#tc_id').hide();
			$('#bar_id').show();
			
		}
		else if(dep=='Cash')
		{
			$('#delivery_id').hide();
			$('#shortage_id').hide();
			$('#deposit_id').show();
			$('#cee_id').show();
			$('#other_slip_id').hide();
			$('#customer_id').hide();
			$('#client_id').show();
			$('#client_id2').hide();
			$('#point_id').hide();
			$('#tc_id').hide();
			$('#bar_id').hide();
			$('#ce_bar').hide();
			$('#clnt_bar').hide();
			$('#cust_bar').hide();
			$('#point_bar').hide();
		}
	});
	
	function vault_change(){
				var bal_d2000s=document.getElementById("bal_2000s").value='';
				var bal_d500s=document.getElementById("bal_500s").value='';
				var bal_d200s=document.getElementById("bal_200s").value='';
				var bal_d100s=document.getElementById("bal_100s").value='';
				var bal_d50s=document.getElementById("bal_50s").value='';
				var bal_d20s=document.getElementById("bal_20s").value='';
				var bal_d10s=document.getElementById("bal_10s").value='';
				var bal_d5s=document.getElementById("bal_5s").value='';
				var bal_dcoins=document.getElementById("bal_coins").value='';
				var bal_tot=document.getElementById("deno_total").value='';

				var d2000s=document.getElementById("2000s").value='0';
				var d500s=document.getElementById("500s").value='0';
				var d200s=document.getElementById("200s").value='0';
				var d100s=document.getElementById("100s").value='0';
				var d50s=document.getElementById("50s").value='0';
				var d20s=document.getElementById("20s").value='0';
				var d10s=document.getElementById("10s").value='0';
				var d5s=document.getElementById("5s").value='0';
				var dcoins=document.getElementById("coins").value='0';
				var deno_diff=document.getElementById("deno_diff").value='0';
				

				
	}	
	// function form_sub(){
				// var bal_d2000s=document.getElementById("bal_2000s").value;
				// var bal_d500s=document.getElementById("bal_500s").value;
				// var bal_d200s=document.getElementById("bal_200s").value;
				// var bal_d100s=document.getElementById("bal_100s").value;
				// var bal_d50s=document.getElementById("bal_50s").value;
				// var bal_d20s=document.getElementById("bal_20s").value;
				// var bal_d10s=document.getElementById("bal_10s").value;
				// var bal_d5s=document.getElementById("bal_5s").value;
				// var bal_dcoins=document.getElementById("bal_coins").value;
				// var bal_tot=document.getElementById("deno_total").value;

				// var d2000s=document.getElementById("2000s").value;
				// var d500s=document.getElementById("500s").value;
				// var d200s=document.getElementById("200s").value;
				// var d100s=document.getElementById("100s").value;
				// var d50s=document.getElementById("50s").value;
				// var d20s=document.getElementById("20s").value;
				// var d10s=document.getElementById("10s").value;
				// var d5s=document.getElementById("5s").value;
				// var dcoins=document.getElementById("coins").value;
				

				// if(d2000s <= bal_d2000s && d500s <= bal_d500s && d200s <= bal_d200s && d100s <= bal_d100s && d50s <= bal_d50s && d20s <= bal_d20s && d10s <= bal_d10s && d10s <= bal_d10s && d5s <= bal_d5s && dcoins <= bal_dcoins)
				// {
				// //$("#myForm_v").submit();
				
				    // return true;
				// }
				// else{
					// //alert();
					// $('#save_id').hide();
					// return false;
				// }
	// }	
</script>

