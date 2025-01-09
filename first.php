<?php
else if ($pid == "View_Logs") {
	$id = $_POST['id'];
	$sql_trans_check="select service_type from vantrans_details where sid='".$id."' and status='Y'";
	//echo $sql_trans_check; exit;
	$exc_trns=mysqli_query($readConnection,$sql_trans_check);
	while($data_retun=mysqli_fetch_array($exc_trns))
	{
		$service_type_check=$data_retun['service_type'];
	}
	$from_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['from_date'])));
	$to_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['to_date'])));
	$from_location = mysqli_real_escape_string($readConnection, $_POST['from_location']);
	$to_location = mysqli_real_escape_string($readConnection, $_POST['to_location']);
	$time_in = mysqli_real_escape_string($readConnection, $_POST['time_in']);
	$time_out = mysqli_real_escape_string($readConnection, $_POST['time_out']);
	/* --additional info */
	$extra_ot = mysqli_real_escape_string($readConnection, $_POST['extra_ot']);
	$contactperson = mysqli_real_escape_string($readConnection, $_POST['contact_person']);
	$contactnumber = mysqli_real_escape_string($readConnection, $_POST['contact_number']);
	$emailid = mysqli_real_escape_string($readConnection, $_POST['emailid']);
	$field1 = mysqli_real_escape_string($readConnection, $_POST['field1']);
	$field2 = mysqli_real_escape_string($readConnection, $_POST['field2']);
	/* additional info */
	/* extra fields */
	$intercity = mysqli_real_escape_string($readConnection, $_POST['intercity']);
	$location = mysqli_real_escape_string($readConnection, $_POST['location']);
	$frombranchcode = mysqli_real_escape_string($readConnection, $_POST['from_branchcode']);
	$tobranchcode = mysqli_real_escape_string($readConnection, $_POST['to_branchcode']);
	$oneway = mysqli_real_escape_string($readConnection, $_POST['oneway_twoway']);
	$airport_parkingcharges = mysqli_real_escape_string($readConnection, $_POST['airport_parkingcharges']);
	$costofoperation = mysqli_real_escape_string($readConnection, $_POST['costofoperation']);
	$category = mysqli_real_escape_string($readConnection, $_POST['category']);
	$additionalloaders = mysqli_real_escape_string($readConnection, $_POST['additional_loaders']);
	/* extrafields */

	$total_time = mysqli_real_escape_string($readConnection, $_POST['total_time']);
	$ot_hours = mysqli_real_escape_string($readConnection, $_POST['ot_hours']);
	$start_kms = mysqli_real_escape_string($readConnection, $_POST['start_kms']);
	$end_kms = mysqli_real_escape_string($readConnection, $_POST['end_kms']);
	$total_kms = mysqli_real_escape_string($readConnection, $_POST['total_kms']);


	$toll_tax = mysqli_real_escape_string($readConnection, $_POST['toll_tax']);
	$service_category = mysqli_real_escape_string($readConnection, $_POST['service_category']);
	$parking_charges = mysqli_real_escape_string($readConnection, $_POST['parking_charges']);
	$bill_branch = mysqli_real_escape_string($readConnection, $_POST['bill_branch']);
	$pickup_amount = mysqli_real_escape_string($readConnection, $_POST['pickup_amount']);
	$delivery_amount = mysqli_real_escape_string($readConnection, $_POST['delivery_amount']);
	$vault_status = mysqli_real_escape_string($readConnection, $_POST['vault_status']);
	$night_stay = mysqli_real_escape_string($readConnection, $_POST['night_stay']);
	$no_trips = mysqli_real_escape_string($readConnection, $_POST['no_trips']);
	//modified for vantrans
	$other_remarks_vt = mysqli_real_escape_string($readConnection, $_POST['other_remarks']);
	//
	$remarks = mysqli_real_escape_string($readConnection, $_POST['remarks']);
	$client_acc_no = mysqli_real_escape_string($readConnection, $_POST['client_acc_no']);
	$no_cheques = mysqli_real_escape_string($readConnection, $_POST['no_cheques']);
	$permit_paid = mysqli_real_escape_string($readConnection, $_POST['permit_paid']);
	$additional_charges = mysqli_real_escape_string($readConnection, $_POST['additional_charges']);
	$tele_charge = mysqli_real_escape_string($readConnection, $_POST['tele_charge']);
	$insurance = mysqli_real_escape_string($readConnection, $_POST['insurance']);
	$crew_members = mysqli_real_escape_string($readConnection, $_POST['crew_members']);
	$additional_crew = mysqli_real_escape_string($readConnection, $_POST['additional_crew']);
	$clients = '';
	$client_acc = '';
	$locs = '';
	$serv_cat = '';
	$pick_amt = '';
	$cheq_no = '';
	$rems = '';
	if (isset($_POST['dcv_clients'])) {
		foreach ($_POST['dcv_clients'] as $key => $vals) {
			if ($vals != '')	$clients .= $vals . ",";
			if ($_POST['dcv_client_acc'][$key] != '')	$client_acc .= $_POST['dcv_client_acc'][$key] . ",";
			if ($_POST['dcv_location'][$key] != '')	$locs .= $_POST['dcv_location'][$key] . ",";
			if ($_POST['dcv_service_category'][$key] != '')	$serv_cat .= $_POST['dcv_service_category'][$key] . ",";
			if ($_POST['dcv_pickup_amount'][$key] != '')	$pick_amt .= $_POST['dcv_pickup_amount'][$key] . ",";
			if ($_POST['dcv_no_cheques'][$key] != '') $cheq_no .= $_POST['dcv_no_cheques'][$key] . ",";
			if ($_POST['dcv_remarks'][$key] != '')	$rems .= $_POST['dcv_remarks'][$key] . "$";
		}
	}
	$clients = substr($clients, 0, -1);
	$client_acc = substr($client_acc, 0, -1);
	$locs = substr($locs, 0, -1);
	$serv_cat = substr($serv_cat, 0, -1);
	$pick_amt = substr($pick_amt, 0, -1);
	$cheq_no = substr($cheq_no, 0, -1);
	$rems = substr($rems, 0, -1);

	$gun_cnt = 0;
	$drv_cnt = 0;
	$ce_cnt = 0;
	$mbc_cnt = 0;
	$ldr_cnt = 0;
	$cv = $_POST['cv'];
	$ids = '';
	if($service_type_check=='CVR')
	{

		if($_POST['vehicle_no']!='')
		{
			//echo 1; exit;
			$cashvan = $_POST['vehicle_no'];

			
		}
		else
		{
			//echo $_POST['vec__reg_no_asi']; exit;
			$cashvan = strtoupper($_POST['vec__reg_no_asi']);
		}
		
		$seccv = $_POST['seccv'];

		if($_POST['secvehicle_no']!='')
		{
			$seccashvan = $_POST['secvehicle_no'];
		}
		else
		{
			$seccashvan = strtoupper($_POST['vec__reg_no_asi_second']);
		}
	}
	else
	{
		$cashvan = $_POST['vehicle_no'];
		$seccv = $_POST['seccv'];
		$seccashvan = $_POST['secvehicle_no'];
	}


	/* Start time of additional cashvan */
	$secstarttime = $_POST['secstarttime'];
	$secstartkm = $_POST['altstartkm'];
	$secendkm = $_POST['altendkm'];
	$sectotalkm = $_POST['alttotalkm'];
	$secremarks = $_POST['altremarks'];
	/* end time of additional cashvan */


	if($service_type_check=='CVR')
	{

		$process_type=$_POST['vehicle_type_tra'];

		if($process_type=='Hired')
		{
			$vehicle_model_hired=$_POST['vec_model_sv'];
			$rbi_guide_hired=$_POST['guide_rbi'];
			$acornon=$_POST['cashvan_ac'];
		}
		else
		{
			$vehicle_model_hired='';
			$rbi_guide_hired='';
			$acornon='';
		}
		
		$process_type_second=$_POST['vehicle_type_tra_second'];
		if($process_type_second=='Hired')
		{
			$vehicle_model_hired_second=$_POST['vec_model_sv_second'];
			$rbi_guide_hired_second=$_POST['guide_rbi_second'];
			$acornon_second=$_POST['cashvan_ac_second'];
		}
		else
		{
			$vehicle_model_hired_second='';
			$rbi_guide_hired_second='';
			$acornon_second='';
		}

		// for alternate hired cashvan

		
	
	}

	if (isset($_POST['gunman'])) {
		$gun_str = 'G-';
		foreach ($_POST['gunman'] as $num1 => $gunid) {
			if ($gunid != '') $gun_cnt++;
		}
		if ($gun_cnt > 0) $gunny = implode(",", $_POST['gunman']);
		if (isset($gunny)) {
			//Check if already exists
			$sel_gun = mysqli_query($writeConnection, "Select name,crew_id from daily_cashvan_crew where trans_id = '" . $id . "' and vehicle_id='" . $cv . "' and desig='gunman' and status='Y' ");
			if (mysqli_num_rows($sel_gun) > 0) {
				$gun_r = mysqli_fetch_object($sel_gun);
				$gun = "Update daily_cashvan_crew set vehicle_id='" . $cashvan . "',name ='" . $gunny . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $gun_r->crew_id . "' ";
				$ids .= $gun_r->crew_id . ",";
				$gun_exc = mysqli_query($writeConnection, $gun);
			} else {
				$gun = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $id . "','" . $from_date . "','" . $_POST['service_id'] . "','" . $cashvan . "','gunman','" . $gunny . "','Y','" . $user_name . "',now())";
				$gun_exc = mysqli_query($writeConnection, $gun);
				$ids .= mysqli_insert_id($writeConnection) . ",";
			}
		}
		$gunny = '';
	}
	//Driver
	if (isset($_POST['driver'])) {
		$drv_str = 'D-';
		foreach ($_POST['driver'] as $num2 => $drvid) {
			if ($drvid != '') $drv_cnt++;
		}
		if ($drv_cnt > 0) $drivyy =  implode(",", $_POST['driver']);
		if (isset($drivyy)) {
			$sel_dr = mysqli_query($writeConnection, "Select name,crew_id from daily_cashvan_crew where trans_id = '" . $id . "' and vehicle_id='" . $cv . "' and desig='driver' and status='Y' ");
			if (mysqli_num_rows($sel_dr) > 0) {
				$driv_r = mysqli_fetch_object($sel_dr);
				$drive = "Update daily_cashvan_crew set vehicle_id='" . $cashvan . "',name ='" . $drivyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $driv_r->crew_id . "'";
				$ids .= $driv_r->crew_id . ",";
				$drive_exc = mysqli_query($writeConnection, $drive);
			} else {
				$drive = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $id . "','" . $from_date . "','" . $_POST['service_id'] . "','" . $cashvan . "','driver','" . $drivyy . "','Y','" . $user_name . "',now())";
				$drive_exc = mysqli_query($writeConnection, $drive);
				$ids .= mysqli_insert_id($writeConnection) . ",";
			}
		}
		$drivyy = '';
	}
	//Loader
	if (isset($_POST['loader'])) {
		$ldr_str = 'L-';
		foreach ($_POST['loader'] as $num3 => $loadid) {
			if ($loadid != '') $ldr_cnt++;
		}
		if ($ldr_cnt > 0) $loadyy = implode(",", $_POST['loader']);
		if (isset($loadyy)) {
			$sel_ld = mysqli_query($writeConnection, "Select name,crew_id from daily_cashvan_crew where trans_id = '" . $id . "' and vehicle_id='" . $cv . "' and desig='loader' and status='Y'");
			if (mysqli_num_rows($sel_ld) > 0) {
				$ld_r = mysqli_fetch_object($sel_ld);
				$load = "Update daily_cashvan_crew set vehicle_id='" . $cashvan . "',name ='" . $loadyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $ld_r->crew_id . "'";
				$ids .= $ld_r->crew_id . ",";
				$load_exc = mysqli_query($writeConnection, $load);
			} else {
				$load = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $id . "','" . $from_date . "','" . $_POST['service_id'] . "','" . $cashvan . "','loader','" . $loadyy . "','Y','" . $user_name . "',now())";
				$load_exc = mysqli_query($writeConnection, $load);
				$ids .= mysqli_insert_id($writeConnection) . ",";
			}
		}
		$loadyy = '';
	}
	//CE
	if (isset($_POST['ce'])) {
		$ce_str = 'CE-';
		foreach ($_POST['ce'] as $num5 => $ceid) {
			if ($ceid != '') $ce_cnt++;
		}
		if ($ce_cnt > 0) $ceyy = implode(",", $_POST['ce']);
		if (isset($ceyy)) {
			$sel_ce = mysqli_query($writeConnection, "Select name,crew_id from daily_cashvan_crew where trans_id = '" . $id . "' and vehicle_id='" . $cv . "' and desig='ce' and status='Y'");
			if (mysqli_num_rows($sel_ce) > 0) {
				$ce_r = mysqli_fetch_object($sel_ce);
				$ce = "Update daily_cashvan_crew set vehicle_id='" . $cashvan . "',name ='" . $ceyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $ce_r->crew_id . "'";
				$ids .= $ce_r->crew_id . ",";
				$ce_exc = mysqli_query($writeConnection, $ce);
			} else {
				$ce = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $id . "','" . $from_date . "','" . $_POST['service_id'] . "','" . $cashvan . "','ce','" . $ceyy . "','Y','" . $user_name . "',now())";
				$ce_exc = mysqli_query($writeConnection, $ce);
				$ids .= mysqli_insert_id($writeConnection) . ",";
			}
		}
		$ceyy = '';
	}
	$ids1 = substr($ids, 0, -1);
	$del_rest_crew = mysqli_query($writeConnection, "Update daily_cashvan_crew set status='N' where trans_id ='" . $id . "' and crew_id NOT IN(" . $ids1 . ")");
	//Crew
	$crew = '';
	if (isset($gun_str))		$crew .= $gun_str . $gun_cnt . ",";
	if (isset($drv_str))		$crew .= $drv_str . $drv_cnt . ",";
	if (isset($ldr_str))		$crew .= $ldr_str . $ldr_cnt . ",";
	if (isset($mbc_str))		$crew .= $mbc_str . $mbc_cnt . ",";
	if (isset($ce_str))		$crew .= $ce_str . $ce_cnt;
	//Update Services
	if (isset($_POST['dcv_clients'])) {
		// $up_ser = "Update vantrans_details set location_from='" . $locs . "',location_to='" . $to_location . "',to_date='" . $to_date . "',extra_ot='" . $extra_ot . "',contact_person='" . $contactperson . "',contact_number='" . $contactnumber . "',emailid='" . $emailid . "',field1='" . $field1 . "',field2='" . $field2 . "',intercity_intracity='" . $intercity . "',location='" . $location . "',from_branchcode='" . $frombranchcode . "',to_branchcode='" . $tobranchcode . "',oneway_twoway='" . $oneway . "',airport_parkingcharges='" . $airport_parkingcharges . "',costofoperation='" . $costofoperation . "',category='" . $category . "',additional_loaders='" . $additionalloaders . "',start_time='" . $time_in . "',end_time='" . $time_out . "',total_time='" . $total_time . "',ot_hours='" . $ot_hours . "',start_kms='" . $start_kms . "',end_kms='" . $end_kms . "',total_kms='" . $total_kms . "',bill_branch='" . $bill_branch . "',pickup_amount='" . $pick_amt . "',delivery_amount='" . $delivery_amount . "',vault_status='" . $vault_status . "',night_stay='" . $night_stay . "',toll_paid='" . $toll_tax . "',permit_paid='" . $permit_paid . "',parking_charge='" . $parking_charges . "',no_trips='" . $no_trips . "',additional_charges='" . $additional_charges . "',telephone_charge='" . $tele_charge . "',insurance='" . $clients . "',remarks='" . $remarks . "',other_remarks='" . $other_remarks_vt . "',client_acc_no='" . $client_acc . "',no_cheques='" . $cheq_no . "',vehicle_no ='" . $_POST['vehicle_no'] . "',crew_members='" . $crew . "',sec_vehicleno = '" . $_POST['sec_vehicleno'] . "',secstart_time = '" . $secstarttime . "',secstartkm='" . $secstartkm . "',secendkm='" . $secendkm . "',sectotalkm='" . $sectotalkm . "',secremarks='" . $secremarks . "',additional_crew='" . $rems . "',update_by='" . $user_name . "',update_time=now() where sid='" . $id . "' and status='Y'";

		//$up_ser = "Update vantrans_details set location_from='".$locs."',location_to='".$to_location."',to_date='".$to_date."',extra_ot='".$extra_ot."',contact_person='".$contactperson."',contact_number='".$contactnumber."',emailid='".$emailid."',field1='".$field1."',field2='".$field2."',start_time='".$time_in."',end_time='".$time_out."',total_time='".$total_time."',ot_hours='".$ot_hours."',start_kms='".$start_kms."',end_kms='".$end_kms."',total_kms='".$total_kms."',bill_branch='".$bill_branch."',pickup_amount='".$pick_amt."',delivery_amount='".$delivery_amount."',vault_status='".$vault_status."',night_stay='".$night_stay."',toll_paid='".$toll_tax."',permit_paid='".$permit_paid."',parking_charge='".$parking_charges."',no_trips='".$no_trips."',additional_charges='".$additional_charges."',telephone_charge='".$tele_charge."',insurance='".$clients."',remarks='".$remarks."',other_remarks='".$other_remarks_vt."',client_acc_no='".$client_acc."',no_cheques='".$cheq_no."',vehicle_no ='".$_POST['vehicle_no']."',crew_members='".$crew."',sec_vehicleno = '".$_POST['sec_vehicleno']."',secstart_time = '".$secstarttime."',secstartkm='".$secstartkm."',secendkm='".$secendkm."',sectotalkm='".$sectotalkm."',secremarks='".$secremarks."',additional_crew='".$rems."',update_by='".$user_name."',update_time=now() where sid='".$id."' and status='Y'";

		$up_ser = "Update vantrans_details set location_from='".$locs."',location_to='".$to_location."',to_date='".$to_date."',extra_ot='".$extra_ot."',contact_person='".$contactperson."',contact_number='".$contactnumber."',emailid='".$emailid."',field1='".$field1."',field2='".$field2."',intercity_intracity='".$intercity."',location='".$location."',from_branchcode='".$frombranchcode."',to_branchcode='".$tobranchcode."',oneway_twoway='".$oneway."',airport_parkingcharges='".$airport_parkingcharges."',costofoperation='".$costofoperation."',category='".$category."',additional_loaders='".$additionalloaders."',start_time='".$time_in."',end_time='".$time_out."',total_time='".$total_time."',ot_hours='".$ot_hours."',start_kms='".$start_kms."',end_kms='".$end_kms."',total_kms='".$total_kms."',bill_branch='".$bill_branch."',pickup_amount='".$pick_amt."',delivery_amount='".$delivery_amount."',vault_status='".$vault_status."',night_stay='".$night_stay."',toll_paid='".$toll_tax."',permit_paid='".$permit_paid."',parking_charge='".$parking_charges."',no_trips='".$no_trips."',additional_charges='".$additional_charges."',telephone_charge='".$tele_charge."',insurance='".$clients."',remarks='".$remarks."',other_remarks='".$other_remarks_vt."',client_acc_no='".$client_acc."',no_cheques='".$cheq_no."',vehicle_no ='".$cashvan."',crew_members='".$crew."',sec_vehicleno = '".$_POST['sec_vehicleno']."',secstart_time = '".$secstarttime."',secstartkm='".$secstartkm."',secendkm='".$secendkm."',sectotalkm='".$sectotalkm."',secremarks='".$secremarks."',additional_crew='".$rems."',update_by='".$user_name."',cashvan_type='".$process_type."',rbi_guide='".$rbi_guide_hired."',vehicle_model='".$vehicle_model_hired."',ac_or_nonac='".$acornon."',second_cashvan_type='".$process_type_second."',second_rbi_guide='".$rbi_guide_hired_second."',second_vehicle_model='".$vehicle_model_hired_second."',secoond_ac_or_nonac='".$acornon_second."',update_time=now() where sid='".$id."' and status='Y'";

	} else {
		// $up_ser = "Update vantrans_details set location_from='" . $from_location . "',location_to='" . $to_location . "',to_date='" . $to_date . "',extra_ot='" . $extra_ot . "',contact_person='" . $contactperson . "',contact_number='" . $contactnumber . "',emailid='" . $emailid . "',field1='" . $field1 . "',field2='" . $field2 . "',intercity_intracity='" . $intercity . "',location='" . $location . "',from_branchcode='" . $frombranchcode . "',to_branchcode='" . $tobranchcode . "',oneway_twoway='" . $oneway . "',airport_parkingcharges='" . $airport_parkingcharges . "',costofoperation='" . $costofoperation . "',category='" . $category . "',additional_loaders='" . $additionalloaders . "',start_time='" . $time_in . "',end_time='" . $time_out . "',total_time='" . $total_time . "',ot_hours='" . $ot_hours . "',start_kms='" . $start_kms . "',end_kms='" . $end_kms . "',total_kms='" . $total_kms . "',bill_branch='" . $bill_branch . "',pickup_amount='" . $pickup_amount . "',delivery_amount='" . $delivery_amount . "',vault_status='" . $vault_status . "',night_stay='" . $night_stay . "',toll_paid='" . $toll_tax . "',permit_paid='" . $permit_paid . "',parking_charge='" . $parking_charges . "',no_trips='" . $no_trips . "',additional_charges='" . $additional_charges . "',telephone_charge='" . $tele_charge . "',insurance='" . $insurance . "',remarks='" . $remarks . "',other_remarks='" . $other_remarks_vt . "',client_acc_no='" . $client_acc_no . "',no_cheques='" . $no_cheques . "',vehicle_no ='" . $_POST['vehicle_no'] . "',crew_members='" . $crew . "',sec_vehicleno = '" . $seccashvan . "',secstart_time = '" . $secstarttime . "',secstartkm='" . $secstartkm . "',secendkm='" . $secendkm . "',sectotalkm='" . $sectotalkm . "',secremarks='" . $secremarks . "',additional_crew='" . $additional_crew . "',update_by='" . $user_name . "',update_time=now() where sid='" . $id . "' and status='Y'";

		//	$up_ser = "Update vantrans_details set location_from='".$from_location."',location_to='".$to_location."',to_date='".$to_date."',extra_ot='".$extra_ot."',contact_person='".$contactperson."',contact_number='".$contactnumber."',emailid='".$emailid."',field1='".$field1."',field2='".$field2."',start_time='".$time_in."',end_time='".$time_out."',total_time='".$total_time."',ot_hours='".$ot_hours."',start_kms='".$start_kms."',end_kms='".$end_kms."',total_kms='".$total_kms."',bill_branch='".$bill_branch."',pickup_amount='".$pickup_amount."',delivery_amount='".$delivery_amount."',vault_status='".$vault_status."',night_stay='".$night_stay."',toll_paid='".$toll_tax."',permit_paid='".$permit_paid."',parking_charge='".$parking_charges."',no_trips='".$no_trips."',additional_charges='".$additional_charges."',telephone_charge='".$tele_charge."',insurance='".$insurance."',remarks='".$remarks."',other_remarks='".$other_remarks_vt."',client_acc_no='".$client_acc_no."',no_cheques='".$no_cheques."',vehicle_no ='".$_POST['vehicle_no']."',crew_members='".$crew."',sec_vehicleno = '".$seccashvan."',secstart_time = '".$secstarttime."',secstartkm='".$secstartkm."',secendkm='".$secendkm."',sectotalkm='".$sectotalkm."',secremarks='".$secremarks."',additional_crew='".$additional_crew."',update_by='".$user_name."',update_time=now() where sid='".$id."' and status='Y'";

		$up_ser = "Update vantrans_details set location_from='".$from_location."',location_to='".$to_location."',to_date='".$to_date."',extra_ot='".$extra_ot."',contact_person='".$contactperson."',contact_number='".$contactnumber."',emailid='".$emailid."',field1='".$field1."',field2='".$field2."',intercity_intracity='".$intercity."',location='".$location."',from_branchcode='".$frombranchcode."',to_branchcode='".$tobranchcode."',oneway_twoway='".$oneway."',airport_parkingcharges='".$airport_parkingcharges."',costofoperation='".$costofoperation."',category='".$category."',additional_loaders='".$additionalloaders."',start_time='".$time_in."',end_time='".$time_out."',total_time='".$total_time."',ot_hours='".$ot_hours."',start_kms='".$start_kms."',end_kms='".$end_kms."',total_kms='".$total_kms."',bill_branch='".$bill_branch."',pickup_amount='".$pickup_amount."',delivery_amount='".$delivery_amount."',vault_status='".$vault_status."',night_stay='".$night_stay."',toll_paid='".$toll_tax."',permit_paid='".$permit_paid."',parking_charge='".$parking_charges."',no_trips='".$no_trips."',additional_charges='".$additional_charges."',telephone_charge='".$tele_charge."',insurance='".$insurance."',remarks='".$remarks."',other_remarks='".$other_remarks_vt."',client_acc_no='".$client_acc_no."',no_cheques='".$no_cheques."',vehicle_no ='".$cashvan."',crew_members='".$crew."',sec_vehicleno = '".$seccashvan."',secstart_time = '".$secstarttime."',secstartkm='".$secstartkm."',secendkm='".$secendkm."',sectotalkm='".$sectotalkm."',secremarks='".$secremarks."',additional_crew='".$additional_crew."',update_by='".$user_name."',cashvan_type='".$process_type."',rbi_guide='".$rbi_guide_hired."',vehicle_model='".$vehicle_model_hired."',ac_or_nonac='".$acornon."',second_cashvan_type='".$process_type_second."',second_rbi_guide='".$rbi_guide_hired_second."',second_vehicle_model='".$vehicle_model_hired_second."',secoond_ac_or_nonac='".$acornon_second."',update_time=now() where sid='".$id."' and status='Y'"; 
	}
	//echo $up_ser;die;
	$excs = mysqli_query($writeConnection, $up_ser);

	if ($excs) {
		// mysqli_close($conn);
		header("Location:../?pid=View_Transaction_Logs&nav=2_1_2");
	} else {
		// mysqli_close($conn);
		header("Location:../?pid=View_Transaction_Logs&nav=2_2_4");
	}
}
