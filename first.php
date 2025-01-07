
<?php
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
	$sql=mysql_query("select * from vault_other_inout where id='".$id."' and status='Y' ");
	$row=mysql_fetch_array($sql);
	$n2=mysql_num_rows($sql);
	
		
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
	$vault_name=$row['vault_id'];
	
	$curr_date=date('Y-m-d');
	
	//echo "select bal_2000s,bal_500s,bal_200s,bal_100s,bal_50s,bal_20s,bal_10s,bal_5s,bal_coins from vault_daily_trans_new where client_id='".$row['from_client']."' and status='Y' and trans_date <='".$curr_date."' order by trans_date desc limit 0,1"; die;
	
	$sql_deno=mysql_query("select bal_2000s,bal_500s,bal_200s,bal_100s,bal_50s,bal_20s,bal_10s,bal_5s,bal_coins from vault_daily_trans_new where client_id='".$row['from_client']."' and status='Y' and trans_date <='".$curr_date."' order by trans_date desc limit 0,1");
	$rr_deno=mysql_fetch_assoc($sql_deno);
	}
	 
		$rr=explode(",",$region);
	$rr_c=count($rr);
if($user=='satishn' || $user=='rohitr')
{
	$fz=mysql_num_rows(mysql_query("select att_id from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status=1 and vault='Nagpur'"));
}
elseif($user=='bongi' || $user=='maruti')
{
	$fz=mysql_num_rows(mysql_query("select att_id from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status=1 and vault='Pune'"));
}
else{	
$fz=mysql_num_rows(mysql_query("select att_id from vault_frez_status where frze_date='".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status=1"));
}
$prev_date = date('Y-m-d', strtotime($from_date .' -3 day'));
if($user=='satishn' || $user=='rohitr')
{
	$last_fz=mysql_num_rows(mysql_query("select  att_id from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."'  and status='Y' and region in(".$region.") and frz_status=1 and vault='Nagpur'"));
}
elseif($user=='bongi' || $user=='maruti')
{
	
	$last_fz=mysql_num_rows(mysql_query("select  att_id from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status=1 and vault='Pune'"));
	
	
}
else{
$last_fz=mysql_num_rows(mysql_query("select att_id from vault_frez_status where frze_date between '".$prev_date."' and '".date('Y-m-d')."' and status='Y' and region in(".$region.") and frz_status=1"));
}
?>
<style type="text/css">
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
</head><div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-11">
      <div class="portlet">
        <div class="portlet-body">
          <h3 class="portlet-title"><u> Internal Transfer
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
				echo "Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.";
				?>
                </b> </div>
            </div>
            <?php }			?>
          </div>
        </div>
      </div>
      <?php  if($fz==0 || $last_fz ==0) { ?>
      <div class="portlet">
        <form id="demo-validation" action="VaultModule/add_details_vault.php?pid=<?php echo $pid; ?>" data-validate="parsley" enctype="multipart/form-data" class="form parsley-form" method="post">
          <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
          <input type="hidden" name="region" id="region" value="<?php echo $region; ?>" />
          <div>
          
          
          <div class="row">
          <div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
               Date
              </label>
			  <?php if($id!='')
			  { ?>
			  <input type="hidden" name="trans_date" id="trans_date" value="<?php if($id==''){ echo date('d-m-Y');} echo $row['date']; ?>">
			   <input type="text" id="trans_date" name="trans_date" class="form-control parsley-validated" data-required="true" value="<?php if($id==''){ echo date('d-m-Y');} echo $row['date']; ?>" tabindex="1" readonly>
			  <?php } else { ?>
              <input type="text" id="popupDatepicker" name="trans_date" class="form-control parsley-validated" data-required="true" value=" <?php if($id==''){ echo date('d-m-Y');} echo $row['date']; ?>" tabindex="1" readonly>
			  <?php } ?>
            </div>
          
          
           <!--<div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Region
              </label>
              <select id="region_name" name="region_name" class="form-control parsley-validated chosen-select" tabindex="2"  onChange="load_location()" >
                <option value="">Select Region</option>
                <?php
                   $sql_region = mysql_query("SELECT region_id, region_name FROM region_master WHERE status='Y'  AND region_id IN (".$region.") ORDER BY region_name");
				   while($res_region = mysql_fetch_assoc($sql_region)) {
					   ?>
                <option value="<?php echo $res_region['region_id'].'-'.$res_region['region_name'];?>" <?php if($row->region_name==$res_region['region_name'])echo 'selected="selected"'; ?> >
                <?php  echo $res_region['region_name']; ?>
                </option>
                <?php
				   }
                 ?>
              </select>
            </div>-->
          
          
            <!--<div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Vault Name
              </label>
              <select name="vault_name" class="form-control parsley-validated chosen-select " id="vault_name" tabindex="2">
               <?php if($rr_c>1) { ?>
                <option value="">Select Vault Name</option>
                    
                    <?php
			   }
					$sql_ce=mysql_query("select vm.vault_name,vm.vault_id from vault_master vm join region_master rm on rm.region_name=vm.region  where vm.status='Y' and rm.status='Y' and rm.region_id in(".$region.")");
			   while($res_ce = mysql_fetch_assoc($sql_ce)) {
					   ?>
					   
											
                <option value="<?php echo $res_ce['vault_id'];?>" <?php if($row['vault_id']==$res_ce['vault_id'])echo 'selected="selected"'; ?>>
                <?php  echo $res_ce['vault_name']; ?>
                </option>
                <?php
			   }           ?>
                    </select>
            </div>-->
            
            <!--<div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Cash/Bag Type
              </label>
              <select id="vault_type" name="vault_type" class="form-control parsley-validated chosen-select" tabindex="5" onchange="load_account()">
                <option value="">Select</option>
                <option value="Cash" <?php if($row['vault_type']=='Cash')  echo 'selected="selected"';	?> >Cash</option>
                <option value="Bag" <?php if($row['vault_type']=='Bag')  echo 'selected="selected"';	?>>Bag</option>
     
              </select>
            </div>-->
            
             <!--<div class="form-group col-sm-2">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              In Type
              </label>
              <select id="dep_type" name="dep_type" class="form-control parsley-validated chosen-select" tabindex="5" onchange="load_customer();">
              <option value="">Select</option>
                <option value="In" <?php if($row['vault_status']=='In')  echo 'selected="selected"';	?> >In</option>
               <option value="Out" <?php if($row['vault_status']=='Out')  echo 'selected="selected"';	?>>Out</option>
     
              </select>
            </div>-->
            	 <div class="form-group col-sm-3" >
                    <label for="name"><label class="compulsory">*</label>Vault Name</label>
				<?php
			
				$reg=explode(",",$region);
				$reg_count=count($reg);
				
				?>
                    <select id="vault_name" name="vault_name" class="form-control parsley-validated chosen-select" data-required="true"  tabindex="2"  >
					<?php if($reg_count>1){ ?>
                    <option value="">Select Vault Name</option>
					<?php } 
					
					if($user=='bongi' || $user=='maruti')
						{
							?>
							 <option value="Pune" >Pune</option> 
						<?php } else if($user=='satishn' || $user=='rohitr') { ?>
				<option value="Nagpur" >Nagpur</option>
				<?php } else {
					
					$sql_ce=mysql_query("select vm.vault_name,vm.vault_id from vault_master vm  join region_master rm on rm.region_name=vm.region  where vm.status='Y' and rm.region_id in(".$region.") and vm.type1='Branch' order by vault_name");
			   while($res_ce = mysql_fetch_assoc($sql_ce)) {
					   ?>
					   
											
               <option value="<?php echo $res_ce['vault_id'];?>"<?php if($vault_name==$res_ce['vault_id'])echo 'selected="selected"'; ?> ><?php  echo $res_ce['vault_name']; ?></option>
                <?php
				}       }    ?>
			   </select>
				<?php //} else {
					//$sql_ce=mysql_query("select vm.vault_name,vm.vault_id from vault_master vm  join region_master rm on rm.region_name=vm.region  where vm.status='Y' and rm.region_id in(".$region.") and vm.type1='Branch'");
			         $res_ce = mysql_fetch_assoc($sql_ce);
					?>
					 <!--<input type="text" id="vault_name" name="vault_name" class="form-control parsley-validated" value="<?php echo $res_ce['vault_name']; ?>" placeholder="Client Name" tabindex="2" readonly="readonly"  >-->
					<?php
			//	}
				?>
                    <span id="vault_id_err" style="color:#F00;"></span>
                </div>
			
			<div class="form-group col-sm-3">
            <label for="name">Client From</label>
			<?php if($id!='')
			  {
			 $client_sql="select vault_id,client_name from vault_client_master where status='Y' and vault_id='".$row['from_client']."'"; 
			$client_s=mysql_query($client_sql);
			$res_client = mysql_fetch_object($client_s);
			?>
			 <input name="from_client1" type="text" class="form-control not_text" id="from_client1" size="10" value="<?php echo $res_client->client_name;?>" tabindex="2" readonly/>
			<input type="hidden" name="from_client" id="from_client" class="form-control parsley-validated" value="<?php echo $row['from_client']; ?>">
			  <?php } else { ?>
			
            <select id="from_client" name="from_client" class="form-control parsley-validated chosen-select" data-required="true" tabindex="3" onchange="load_deno();">
              <option value="">Select Client Name</option>
             <?php
										//$sql_region = mysql_query("SELECT region_name FROM region_master WHERE status='Y'");
										 $client_sql="select vault_id,client_name,vault_name from vault_client_master where status='Y' and region_name in (".$region.") order by client_name"; 
										$client_s=mysql_query($client_sql);
										while($res_client = mysql_fetch_object($client_s)) {
											?>
                  <option value="<?php echo $res_client->vault_id; ?>" <?php if($row['from_client']==$res_client->vault_id)echo 'selected="selected"'; ?>><?php echo $res_client->client_name."-".$res_client->vault_name; ?></option>
                  <?php	
				
										}
										?>
            </select>
			
			  <?php } ?><span id="client_id_err" style="color:red;"></span>
</div>
			
			<div class="form-group col-sm-3">
            <label for="name">Client To</label>
			<?php if($id!='')
			  {
			 $client_sql="select vault_id,client_name from vault_client_master where status='Y' and vault_id='".$row['client_name']."'"; 
			$client_s=mysql_query($client_sql);
			$res_client = mysql_fetch_object($client_s);
			?>
			 <input name="client" type="text" class="form-control not_text" id="client" size="10" value="<?php echo $res_client->client_name;?>" tabindex="2" readonly/>
			<input type="hidden" name="client1" id="client1" class="form-control parsley-validated" value="<?php echo $row['client_name']; ?>">
			  <?php } else { ?>
            <select id="client1" name="client1" class="form-control parsley-validated chosen-select" data-required="true" tabindex="4" >
              <option value="">Select Client Name</option>
             <?php
										//$sql_region = mysql_query("SELECT region_name FROM region_master WHERE status='Y'");
										 $client_sql="select vault_id,client_name from vault_client_master where status='Y' and region_name in (".$region.") order by client_name";
										$client_s=mysql_query($client_sql);
										while($res_client = mysql_fetch_object($client_s)) {
											?>
                  <option value="<?php echo $res_client->vault_id; ?>" <?php if($row['client_name']==$res_client->vault_id)echo 'selected="selected"'; ?>><?php echo $res_client->client_name; ?></option>
                  <?php	
				
										}
										?>
			  </select><?php } ?>
			 <span id="clnt_id_err" style="color:red;"></span>
</div>
			
			
</div>
<div class="row">
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory">&nbsp;</label>
              Amount
              </label>
             <input type="text" id="amount" name="amount" class="form-control parsley-validated not_text" value="<?php echo $row['dep_amount']; ?>" placeholder="Total Amount" tabindex="5"  onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()">
			 <span id="amt_id" style="color:red;"></span>
            </div>
			
           
                <div class="form-group col-sm-3">
            <label for="name">Remarks</label>
             <textarea cols="4" rows="3" id="ro_remarks" name="ro_remarks" class="form-control parsley-validated" tabindex="6" <?php if($check_id!='') { ?>  readonly="readonly"  <?php } ?>><?php echo $row['other_remarks']; ?></textarea>
          </div>
		  </div>
            
            <div class="clear"></div>
			<span id="deno_id" style="color:red;font-size:15px;"></span>
						 <div class="alert alert-info" style="width: 97%;
							float: left;
							background-color: #0168ad;
							border-color: #0168ad;
							color: white;
							padding: 0px;
						">
 
                    <div align="center"><strong>Currency Denominations </strong></div>
                    <div class="clear"></div>
                    <div class="form-group col-sm-1">
					<input name="2000s" type="text" class="form-control not_text validate_deno" id="bal_2000s" size="10" value="<?php echo $rr_deno['bal_2000s'];?>" readonly /><br>
                      <label for="name">2000 (s) </label>
                      <input name="2000s" type="text" class="form-control not_text validate_deno" id="2000s" size="10" value="<?php if($n2==1) echo $_2000s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="7"/>
                    </div>
                  <!--  <div class="form-group col-sm-1">
                      <label for="name">1000 (s) </label>
                      <input name="1000s" type="text" class="form-control" id="1000s" size="10" value="<?php if($n2==1) echo $_1000s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" />
                    </div>-->
                    <div class="form-group col-sm-1">
					<input name="500s" type="text" class="form-control not_text validate_deno" id="bal_500s" size="10" value="<?php echo $rr_deno['bal_500s'];?>" readonly /><br>
                      <label for="name">500 (s) </label>
                      <input name="500s" type="text" class="form-control not_text validate_deno" id="500s" size="10" value="<?php if($n2==1) echo $_500s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="8"/>
                    </div> 
					<div class="form-group col-sm-1">
					<input name="200s" type="text" class="form-control not_text validate_deno" id="bal_200s" size="10" value="<?php echo $rr_deno['bal_200s'];?>" readonly /><br>
                      <label for="name">200 (s) </label>
                      <input name="200s" type="text" class="form-control not_text validate_deno" id="200s" size="10" value="<?php if($n2==1) echo $_200s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="9"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="100s" type="text" class="form-control not_text validate_deno" id="bal_100s" size="10" value="<?php echo $rr_deno['bal_100s'];?>" readonly /><br>
                      <label for="name">100 (s) </label>
                      <input name="100s" type="text" class="form-control not_text validate_deno" id="100s" size="10" value="<?php if($n2==1) echo $_100s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="10"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="50s" type="text" class="form-control not_text validate_deno" id="bal_50s" size="10" value="<?php echo $rr_deno['bal_50s'];?>" readonly /><br>
                      <label for="name">50 (s)</label>
                      <input name="50s" type="text" class="form-control not_text validate_deno" id="50s" size="10" value="<?php if($n2==1) echo $_50s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="11"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="20s" type="text" class="form-control not_text validate_deno" id="bal_20s" size="10" value="<?php echo $rr_deno['bal_20s'];?>" readonly /><br>
                      <label for="name">20 (s) </label>
                      <input name="20s" type="text" class="form-control not_text validate_deno" id="20s" size="10" value="<?php if($n2==1) echo $_20s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="12"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="10s" type="text" class="form-control not_text validate_deno" id="bal_10s" size="10" value="<?php echo $rr_deno['bal_10s'];?>" readonly /><br>
                      <label for="name">10 (s) </label>
                      <input name="10s" type="text" class="form-control not_text validate_deno" id="10s" size="10" value="<?php if($n2==1) echo $_10s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="13"/>
                    </div>
                    <div class="form-group col-sm-1">
					<input name="5s" type="text" class="form-control not_text validate_deno" id="bal_5s" size="10" value="<?php echo $rr_deno['bal_5s'];?>" readonly /><br>
                      <label for="name">5 (s) </label>
                      <input name="5s" type="text" class="form-control not_text validate_deno" id="5s" size="10" value="<?php if($n2==1) echo $_5s; else echo "0";?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="14"/>
                    </div>
					 
                    <div class="form-group col-sm-1">
					<input name="coins" type="text" class="form-control not_text validate_deno" id="bal_coins" size="10" value="<?php echo $rr_deno['bal_coins'];?>" readonly /><br>
                      <label for="name">Coin (s) </label>
                      <input name="coins" type="text" class="form-control not_text validate_deno" id="coins" size="10" value="<?php if($n2==1) echo $_1s; else echo "0";?>"  onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" tabindex="15"/>
                    </div>
                     
                    <div class="form-group col-sm-1.5" style="margin-top:-10px;"><br><br><br>
                      <label for="name">Total </label>
                      <input name="deno_total" type="text" class="form-control" id="deno_total" size="10" value="<?php if($n2==1) echo $deno_total; else echo "0";?>" readonly />
                    </div>
                    <div class="form-group col-sm-1.5" style="margin-top:-10px;"><br><br><br>
                      <label for="name"> Pickup Difference </label>
                      <input name="deno_diff" type="text" class="form-control" id="deno_diff" size="10" value="<?php if($n2==1) echo $deno_diff; else echo "0";?>" readonly />
                    </div>
                  </div>
                                <div class="clear"></div>
             <?php if($fz>0)
		  {
		  }if($last_fz<=0)
		  {
		  }else{
			  ?>
            <div class="form-group col-sm-3">
              <button type="submit" name="submit" id="save_id" class="btn btn-danger search_btn" style="margin-top: 23px;     padding: 5px 12px" tabindex="16">Save </button>
            </div>
		  <?php } ?>
          </div>
        </form>
        <?php }//mkdir('deposit/tests', 0777, true); ?>
      </div>
      <br>
      <br>
      <div class="clear"></div>
      <div class="portlet">
        <h3 class="portlet-title"> <u>Customize Search</u> </h3>
        <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Selected Point Details Deleted</b> </div>
        <form id="demo-validation" action="#" data-validate="parsley" class="form parsley-form">
          <!--<div class="form-group col-sm-3">
            <label for="name">Search Vault Name</label>
            <select id="client_cust" name="client1" class="form-control parsley-validated chosen-select" data-required="true" tabindex="11" >
              <option value="">Select </option>
              <?php
                   $sql_region11 = mysql_query("select vm.vault_name,vm.vault_id from vault_master vm join region_master rm on rm.region_name=vm.region  where vm.status='Y' and rm.status='Y' and rm.region_id in(".$region.")");
				   while($res_region11 = mysql_fetch_assoc($sql_region11)) {
					   ?>
                <option value="<?php echo $res_region11['vault_id'];?>">
                <?php  echo $res_region11['vault_name']; ?>
                </option>
                <?php
				   }
                 ?>
            </select>
          </div>-->
		   <input type="hidden" name="frz" id="frz" value="<?php echo $fz; ?>">
		   <input type="hidden" name="last_fz" id="last_fz" value="<?php echo $last_fz; ?>">
		    <input type="hidden" name="now1" id="now1" value="<?php echo $now ?>">
          <div class="form-group col-sm-2">
            <label for="name">Date</label>
            <input type="text" id="popupDatepicker1" name="trans_date" class="form-control parsley-validated" data-required="true" value="<?php echo date('d-m-Y'); ?>" tabindex="17" readonly>
          </div>
          <div class="form-group  col-sm-3">
            <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;"  onclick="search_key('1', '0')"  tabindex="18">Search</button> 
          </div>
        </form>
        <div class="clear"></div>
		  <div style="padding: 7px;" class="alert alert-danger"  id="freeze" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a><b>  Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.</b>  </div> 

<div style="padding: 7px;" class="alert alert-danger"  id="freeze1" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a><b>  Dear User<br>
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
<script>
	$(document).ready(function(){
		$('#freeze').hide();
		$('#freeze1').hide();
		$('#frr').hide();
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
			
			from_client:{
				required:true
			},
			client1:{
				required:true
			},
			amount:{
				required:true
			}
			
			
		},
		messages:{
			from_client:{
				required:'Select From Client.'
			},
			client1:{
				required:'Select To Client.'
			},
			amount:{
				required:'Enter Amount.'
			}
		
		},errorPlacement: function(error, element) {
					if (element.attr("name") == "from_client" )
						error.appendTo('#client_id_err');
						else if  (element.attr("name") == "client1" )
						error.appendTo('#clnt_id_err');
					else if  (element.attr("name") == "amount" )
						error.appendTo('#amt_id');
					}
	});
	
	$('#from_id').hide();
	$('#cee_id').hide();
	$('#tc_id').hide();
	
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
			$.getJSON("VaultModule/AjaxReference/load_data_vault.php?pid=deno_check&client="+$("#from_client").val(), function(data){
				$.each(data, function( index, value ) {	
					res[index] = value;
				});
				
			y = 'bal_'+x;
			console.log(res[y]+' , '+val);
			
			if(parseInt(val) > parseInt(res[y])){
				
			$('#deno_id').html('“Warning!  Data Entry Error -Denomination Value Mismatch with the totals, please check and enter the correct values to proceed further” ');
			$('#'+x).val('0');
			$('#save_id').hide();
			}
			else {
				$('#deno_id').html('');
			}
			});
			
			
		});
	
	
	});
	
	function load_customer()
	 {
		$.ajax({
			type : "POST",
		
			url : "VaultModule/AjaxReference/get_all_details_vault.php",
			data : 'pid=vault_other_inout&dep_type='+$('#dep_type').val(),
			success : function(msg) {
				$('#dep_remarks').html(msg);
				$('#dep_remarks').trigger("chosen:updated");
			}
		});
	 }
	
	function search_key(search_type, page_start) {
		//var cc = $('#client1').val()
		$('#view_details_indu').css('display', '');
		 var fz=$('#frz').val(); 
		 var last_fz=$('#last_fz').val();
		  var now1=$('#now1').val();
		if(fz>0)
		 {
			  $('#freeze').show();
		 }if(last_fz<=0)
		 {
			  $('#freeze').show();
		 }
		 else if(( now1 > '12:55:00' && now1 < '13:10:00' ) || ( now1 > '16:55:00' && now1 < '17:10:00' )){
			$('#frr').show();  
		 }
		else if(fz==0 || last_fz==0){
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/load_data_vault.php",
			data : 'pgn=1&start_limit=' + page_start + '&per_page=' + $('#per_page').val() + '&end_limit=10&types=1&pid=<?php echo $pid; ?>&trans_date=' + $('#popupDatepicker1').val()+'&client=' + $('#client_cust').val()+'&region=' + $('#region').val()+'&tbl_search='+$('#tbl_search').val()+'&client_login=<?php echo $client_login; ?>',
			beforeSend : function() {
				$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
			},
			success : function(msg) {
				$('#view_details_indu').html(msg);
				$('.search_field').css('display', '');
				setTimeout(function() {
  initializeDataTable("#to_load_internal_transfer");
}, 500);
			}
		});
		}
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
	 
		 function load_deno()
	{
		
				var d2000s=document.getElementById("deno_total").value=0;
				var d2000s=document.getElementById("deno_diff").value=0;
				var d2000s=document.getElementById("amount").value=0;
				var d2000s=document.getElementById("2000s").value=0;
				var d500s=document.getElementById("500s").value=0;
				var d200s=document.getElementById("200s").value=0;
				var d100s=document.getElementById("100s").value=0;
				var d50s=document.getElementById("50s").value=0;
				var d20s=document.getElementById("20s").value=0;
				var d10s=document.getElementById("10s").value=0;
				var d5s=document.getElementById("5s").value=0;
				var dcoins=document.getElementById("coins").value=0;
				
		var client=$('#from_client').val();
		
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/load_data_vault.php",
			data : 'pid=load_deno&client='+$('#from_client').val(),
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
	
	
	$('#dep_remarks').change(function()
	{
		var dep=$('#dep_remarks').val();
		if(dep=='ITIN')
		{
			$('#from_id').show();
			$('#to_id').show();
			$('#tc_id').show();
			$('#fc_id').hide();
			$('#cee_id').hide();
			
		}
		else if(dep=='CR')
		{
			$('#from_id').hide();
			$('#to_id').show();
			$('#cee_id').show();
			$('#fc_id').show();
			$('#tc_id').hide();
		}
		else if(dep=='CFB')
		{
			$('#from_id').hide();
			$('#to_id').show();
			$('#cee_id').hide();
			$('#fc_id').show();
			$('#tc_id').hide();
		}
	});
</script>

