<?php

include('../logout.php');
include "../DbConnection/dbConnect.php";
include_once('netyfish_test.php');
include_once('../log_data/log.php');

include('../EmailConfiguration.php');
require '../dependencies/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

$pid = $_REQUEST['pid'];


if ($pid == 'get_shop_name') {
	$query = mysqli_query($readConnection, "SELECT sd.shop_id,sd.shop_name FROM shop_details sd INNER JOIN cust_details cd ON cd.cust_id=sd.cust_id INNER JOIN client_details cl ON cl.client_id=cd.client_id WHERE cd.client_id='" . $_POST['client_id'] . "' AND  sd.status='Y' AND sd.point_type='Active' AND  cd.status='Y' AND cl.status='Y'");

	//Count total number of rows
	$rowCount = mysqli_num_rows($query);
	echo '<option value="">Select</option>';
	while ($row = mysqli_fetch_array($query)) {
		echo '<option value="' . $row['shop_id'] . '">' . $row['shop_id'] . '-' . $row['shop_name'] . '</option>';
	}
}



if (isset($_REQUEST['submit'])) {
	$coll_date = date("Y-m-d");
	//$coll_date1 = date("d-m-Y");	
	$update_date = date("Y-m-d");
	$currentTimestamp = time();
	$time_now = strtotime('+5 hours +30 minutes', $currentTimestamp);
	//$time_now = mktime(date('h')+5,date('i')+30,date('s')); 
	$time = date('h:i:s A', $time_now);
	$time1 = date('H:i:s', $time_now);
	$curr_date_time = date("Y-m-d H:i:s");
	$curr_time = date('H:i:s');
	$dep_no_array = range('A', 'Z');

	$id = $_REQUEST['id'];
	if ($pid == "dcollect") {
		$arr_img_name = $_SESSION["img_name"];
		foreach ($arr_img_name as $imgnameval) {
			copy("../slip_image/preview/" . $imgnameval, "../slip_image/" . $imgnameval);
		}
		$pid = "vtranslog";
		$trans_id = $_POST['trans_id'];
		$coll_id = $_POST['coll_id'];
		$amend = $_POST['amend'];
		$t_rec = $_POST['t_rec'];
		$multi_deno = $_POST['multi_deno'];
		// new update trace details
		if ($region == 6) {
			$tt_time = date("h:i:s");
			$tt_time1 = date("d-m-y h:i:s");
			$trr_id = $_POST['trans_id'];
			$up_date = date("Y-m-d h:i:s");

			$tt_trace = mysqli_fetch_array(mysqli_query($writeConnection, "select trans_ids,start_time from trans_updated_tracker where  trans_ids='" . $trr_id . "' and status='Y'"));
			$time2 = strtotime($tt_time);
			$time1 = strtotime($tt_trace['start_time']);
			$tot_time_submit1 = $time2 - $time1;
			$tot_time_submit = date('h:i:s', $tot_time_submit1);


			$tt_sql = mysqli_query($writeConnection, "update trans_updated_tracker set submit_time='" . $tt_time . "',tot_time_submit='" . $tot_time_submit . "',user_name='" . $user . "',staff_id='" . $_SESSION['emp_id'] . "' where trans_ids='" . $trr_id . "' and status='Y'");
		}


		$pisdate_sing = $_POST['pisdate'];
		if ($amend == 1) {
			$pick_amount_amn = $_2000s_amn = $_1000s_amn = $_500s_amn = $_200s_amn = $_100s_amn = $_50s_amn = $_20s_amn = $_10s_amn = $_5s_amn = $_coins_amn = $_o1000s_amn = $_o500s_amn = 0;
			if ($t_rec > 1) {
				if ($multi_deno == 1) {
					for ($x = 1; $x <= $t_rec; $x++) {
						$_2000s_amn += $_POST['2000s'][$x];
						$_1000s_amn += $_POST['1000s'][$x];
						$_500s_amn += $_POST['500s'][$x];
						$_200s_amn += $_POST['200s'][$x];
						$_100s_amn += $_POST['100s'][$x];
						$_50s_amn += $_POST['50s'][$x];
						$_20s_amn += $_POST['20s'][$x];
						$_10s_amn += $_POST['10s'][$x];
						$_5s_amn += $_POST['5s'][$x];
						$_coins_amn += $_POST['coins'][$x];
						$_o1000s_amn += $_POST['o1000s'][$x];
						$_o500s_amn += $_POST['o500s'][$x];
						$pick_amount_amn += $_POST['pick_amount' . $x];
					}
				} else {
					$_2000s_amn = $_POST['2000s'];
					$_1000s_amn = $_POST['1000s'];
					$_500s_amn = $_POST['500s'];
					$_200s_amn = $_POST['200s'];
					$_100s_amn = $_POST['100s'];
					$_50s_amn = $_POST['50s'];
					$_20s_amn = $_POST['20s'];
					$_10s_amn = $_POST['10s'];
					$_5s_amn = $_POST['5s'];
					$_coins_amn = $_POST['coins'];
					$_o1000s_amn = $_POST['o1000s'];
					$_o500s_amn = $_POST['o500s'];
					$pick_amount_amn = $_POST['pick_amount1'];
				}

				$hierarchy_code_amn = $c_code_amn = $point_code_amn = $rec_no_amn = $pis_hcl_no_amn = $hcl_no_amn = $gen_slip_amn = $slip_date_amn = 'MULTI';
				$cust_field_amn1 = $cust_field_amn2 = $cust_field_amn3 = $cust_field_amn4 = $cust_field_amn5 = $reason_for_nil_pickup_amn = $remarks_amn = '';
			} else {
				$hierarchy_code_amn = 	$_POST['hierarchy_code1'];
				$c_code_amn 		= 	$_POST['c_code1'];
				$point_code_amn 	= 	$_POST['point_code1'];
				$rec_no_amn			=	$_POST['rec_no1'];
				$pis_hcl_no_amn		=	$_POST['pis_hcl_no1'];
				$hcl_no_amn			=	$_POST['hcl_no1'];
				$gen_slip_amn		=	$_POST['gen_slip1'];
				$slip_date_amn		=	$_POST['slip_date1'];
				$cust_field_amn1	=	$_POST['cust_field11'];
				$cust_field_amn2	=	$_POST['cust_field21'];
				$cust_field_amn3	=	$_POST['cust_field31'];
				$cust_field_amn4	=	$_POST['cust_field41'];
				$cust_field_amn5	=	$_POST['cust_field51'];
				$reason_for_nil_pickup_amn = $_POST['reason_for_nil_pickup1'];
				$remarks_amn 		= 	$_POST['coll_remarks'];
				$master_remarks_amn = $_POST['mas_coll_remarks'];
				$pisdate_sing = $_POST['pisdate'];
				if ($_POST['other_remarks'] != '') {
					$remarks_amn .= $_POST['other_remarks'];
				}
				$_2000s_amn = $_POST['2000s'];
				$_1000s_amn = $_POST['1000s'];
				$_500s_amn = $_POST['500s'];
				$_200s_amn = $_POST['200s'];
				$_100s_amn = $_POST['100s'];
				$_50s_amn = $_POST['50s'];
				$_20s_amn = $_POST['20s'];
				$_10s_amn = $_POST['10s'];
				$_5s_amn = $_POST['5s'];
				$_coins_amn = $_POST['coins'];
				$_o1000s_amn = $_POST['o1000s'];
				$_o500s_amn = $_POST['o500s'];
				$pick_amount_amn = $_POST['pick_amount1'];
			}
			if ($t_rec > 1) $recipt_status = 'Y';
			else $recipt_status = 'N';

			$diff_amt_amn = $_POST['req_amount2'] - $pick_amount_amn;
			//Bank Master
			$sql_acc_amn = mysqli_query($writeConnection, "SELECT bank_name FROM bank_master WHERE acc_id='" . $_POST['dep_bank2'] . "'");
			$res_acc_amn = mysqli_fetch_assoc($sql_acc_amn);





			$sql_trans_amn = mysqli_query($writeConnection, "SELECT pickup_date FROM daily_trans WHERE trans_id='" . $trans_id . "'");
			$res_trans_amn = mysqli_fetch_assoc($sql_trans_amn);

			//amend
			$sql_que_amn = mysqli_query($writeConnection, "SELECT `trans_id` FROM `daily_amends` WHERE `trans_id`='" . $trans_id . "'");
			if (mysqli_num_rows($sql_que_amn) > 0) {
				$sql_del_amn = mysqli_query($writeConnection, "DELETE FROM `daily_amends` WHERE `trans_id` = '" . $trans_id . "'");
			}


			$fund_amt = 0;
			$fund_amt = $_POST['fund_amount'];
			if ($_POST['fund_date'] != '') {
				$fund_date = date('Y-m-d', strtotime($_POST['fund_date']));
			} else {
				$fund_date = '0000-00-00';
			}
			if ($t_rec > 1) {
				$ms = 1;
				$amd_crt = 0;
				for ($x = 1; $x <= $t_rec; $x++) {
					$amd_crt += $_POST['pick_amount' . $x];
					$que_mul_amn = mysqli_query($writeConnection, "SELECT rec_no, pick_amount FROM daily_collectionmul WHERE trans_id='" . $trans_id . "' AND rec_id='" . $x . "'");
					$res_mul_amn = mysqli_fetch_assoc($que_mul_amn);

					if ($ms == 1) {
						$fund_amt = $_POST['fund_amount'];
					} else {
						$fund_amt = 0;
					}
					$amnt_diff = $_POST['pick_amount' . $x] - $res_mul_amn['pick_amount'];
					$sql_amn_insert = "INSERT INTO `daily_amends` (`trans_id`, `t_rec`, `amend_date`, `prev_rec`, `prev_amount`, `amend_rec`, `amend_amount`, `diff_amount`, `fund_date`, `fund_amount`, `init_by`, `fund_remarks`, `update_by`, `update_date`, `status`) VALUES
('" . $trans_id . "', '" . $x . "', '" . $res_trans_amn['pickup_date'] . "', '" . trim($res_mul_amn['rec_no']) . "', '" . $res_mul_amn['pick_amount'] . "', '" . trim($_POST['rec_no' . $x]) . "', '" . $_POST['pick_amount' . $x] . "', '" . $amnt_diff . "', '" . $fund_date . "', '" . $fund_amt . "', '" . $_POST['init_by'] . "', '" . $_POST['fund_remarks'] . "', '" . $user . "', '" . date('d-M-Y, h:i:s A') . "', 'Y')";
					mysqli_query($writeConnection, $sql_amn_insert);
					$ms++;
				}
			} else {
				$sql_trans_amend = mysqli_query($writeConnection, "SELECT rec_no, pick_amount FROM daily_collection WHERE trans_id='" . $trans_id . "'");
				$res_trans_amend = mysqli_fetch_assoc($sql_trans_amend);
				if (trim($res_trans_amend['rec_no']) != trim($_POST['rec_no'][1]) || $res_trans_amend['pick_amount'] != $_POST['pick_amount1']) {
					$amnt_diff = $_POST['pick_amount1'] - $res_trans_amend['pick_amount'];
					$sql_amn_insert = "INSERT INTO `daily_amends` (`trans_id`, `t_rec`, `amend_date`, `prev_rec`, `prev_amount`, `amend_rec`, `amend_amount`, `diff_amount`, `fund_date`, `fund_amount`, `init_by`, `fund_remarks`, `update_by`, `update_date`, `status`) VALUES
('" . $trans_id . "', '1', '" . $res_trans_amn['pickup_date'] . "', '" . trim($res_trans_amend['rec_no']) . "', '" . $res_trans_amend['pick_amount'] . "', '" . trim($_POST['rec_no1']) . "', '" . $_POST['pick_amount1'] . "', '" . $amnt_diff . "', '" . $fund_date . "', '" . $fund_amt . "', '" . $_POST['init_by'] . "', '" . $_POST['fund_remarks'] . "', '" . $user . "', '" . date('d-M-Y, h:i:s A') . "', 'Y')";
					mysqli_query($writeConnection, $sql_amn_insert);
				}
			}




			$sql_trans_amend = mysqli_query($writeConnection, "SELECT trans_id FROM daily_collection_amend WHERE trans_id='" . $trans_id . "'");
			if (mysqli_num_rows($sql_trans_amend) > 0) {
				$sqltram = "UPDATE `daily_collection_amend` SET `multi_rec`='" . $recipt_status . "', `t_rec`='" . $t_rec . "', `hierarchy_code`='" . $hierarchy_code_amn . "', `c_code`='" . $c_code_amn . "', `point_codes`='" . $point_code_amn . "', `rec_no`='" . $rec_no_amn . "', `pis_hcl_no`='" . $pis_hcl_no_amn . "', `hcl_no`='" . $hcl_no_amn . "', `gen_slip`='" . $gen_slip_amn . "', `cust_field1`='" . $cust_field_amn1 . "', `cust_field2`='" . $cust_field_amn2 . "', `cust_field3`='" . $cust_field_amn3 . "', `cust_field4`='" . $cust_field_amn4 . "', `cust_field5`='" . $cust_field_amn5 . "', `req_amount`='" . $_POST['req_amount2'] . "', `pick_amount`='" . $pick_amount_amn . "', `diff_amount`='" . $diff_amt_amn . "', `2000s`='" . $_2000s_amn . "',`1000s`='" . $_1000s_amn . "', `500s`='" . $_500s_amn . "', `200s`='" . $_200s_amn . "',`100s`='" . $_100s_amn . "', `50s`='" . $_50s_amn . "', `20s`='" . $_20s_amn . "', `10s`='" . $_10s_amn . "', `5s`='" . $_5s_amn . "', `coins`='" . $_coins_amn . "',`o1000s`='" . $_o1000s_amn . "', `o500s`='" . $_o500s_amn . "', `dep_type1`='" . $_POST['dep_type1'] . "', `dep_accid`='" . $_POST['dep_acc1'] . "', `dep_branch`='" . $_POST['dep_branch'] . "', `dep_slip`='" . $_POST['dep_slip'] . "', `dep_amount1`='" . $_POST['dep_amount1'] . "', `coll_remarks`='" . $remarks_amn . "', `pick_time`='" . $_POST['pick_time'] . "', `dep_amount2`='" . $_POST['dep_amount2'] . "', `dep_bank2`='" . $res_acc_amn['bank_name'] . "', `acc_id`='" . $_POST['dep_bank2'] . "', `other_remarks2`='" . $_POST['other_remarks2'] . "', `reason_for_nill`='" . $reason_for_nil_pickup_amn . "', `coll_dt1`='" . date('Y-m-d H:i:s') . "',`pay_slip_date`='" . $slip_date_amn . "',slip_image='" . $_POST["slip_reupload1"] . "', `user_name`='" . $user . "', master_remark = '$master_remarks_amn' WHERE `trans_id`='" . $trans_id . "' AND `status`='Y'";
			} else {
				$sqltram = "INSERT INTO `daily_collection_amend` (`trans_id`, `multi_rec`, `t_rec`, `hierarchy_code`, `c_code`, `point_codes`, `rec_no`, `pis_hcl_no`, `hcl_no`, `gen_slip`, `cust_field1`, `cust_field2`, `cust_field3`, `cust_field4`, `cust_field5`, `req_amount`, `pick_amount`, `diff_amount`, `2000s`,`1000s`, `500s`, `200s`, `100s`, `50s`, `20s`, `10s`, `5s`, `coins`,`o1000s`, `o500s`, `dep_type1`, `dep_accid`, `dep_branch`, `dep_slip`, `dep_amount1`, `coll_remarks`, `pick_time`, `dep_amount2`, `dep_bank2`, `acc_id`, `other_remarks2`, `reason_for_nill`, `coll_date`, `coll_time`, `coll_dt1`,`pay_slip_date`,slip_image, `user_name`, `status`, master_remark) VALUES
('" . $trans_id . "', '" . $recipt_status . "', '" . $t_rec . "', '" . $hierarchy_code_amn . "', '" . $c_code_amn . "', '" . $point_code_amn . "', '" . $rec_no_amn . "', '" . $pis_hcl_no_amn . "', '" . $hcl_no_amn . "', '" . $gen_slip_amn . "', '" . $cust_field_amn1 . "', '" . $cust_field_amn2 . "', '" . $cust_field_amn3 . "', '" . $cust_field_amn4 . "', '" . $cust_field_amn5 . "', '" . $_POST['req_amount2'] . "', '" . $pick_amount_amn . "', '" . $diff_amt_amn . "', '" . $_2000s_amn . "', '" . $_1000s_amn . "', '" . $_500s_amn . "', '" . $_200s_amn . "', '" . $_100s_amn . "', '" . $_50s_amn . "', '" . $_20s_amn . "', '" . $_10s_amn . "', '" . $_5s_amn . "', '" . $_coins_amn . "', '" . $_o1000s_amn . "', '" . $_o500s_amn . "', '" . $_POST['dep_type1'] . "', '" . $_POST['dep_acc1'] . "', '" . $_POST['dep_branch'] . "', '" . $_POST['dep_slip'] . "', '" . $_POST['dep_amount1'] . "', '" . $remarks_amn . "', '" . $_POST['pick_time'] . "', '" . $_POST['dep_amount2'] . "', '" . $res_acc_amn['bank_name'] . "', '" . $_POST['dep_bank2'] . "', '" . $_POST['other_remarks2'] . "', '" . $reason_for_nil_pickup_amn . "', '" . date('Y-m-d') . "', '" . date('h:i:s A') . "', '" . date('Y-m-d H:i:s') . "', '" . $slip_date_amn . "','" . $_POST["slip_reupload1"] . "', '" . $user . "', 'Y', '$master_remarks_amn')";
			}
			$querytamn = mysqli_query($writeConnection, $sqltram);
			$sql_transm_amend = mysqli_query($writeConnection, "SELECT trans_id FROM daily_collectionmul_amend WHERE trans_id='" . $trans_id . "'");
			if (mysqli_num_rows($sql_transm_amend) > 0) {
				$sql_mul_amn = "DELETE FROM daily_collectionmul_amend WHERE trans_id='" . $trans_id . "'";
				mysqli_query($writeConnection, $sql_mul_amn);
			}
			if ($t_rec > 1) {
				for ($x = 1; $x <= $t_rec; $x++) {

					$mul_remarks_amn = $_POST['mul_remarks' . $x];
					$mul_mas_remarks_amn = $_POST['master_rem_mul' . $x];
					if ($_POST['addi_remarks' . $x] != '') {
						$mul_remarks_amn .= '-' . $_POST['addi_remarks' . $x];
					}

					if (isset($_POST["slip_reupload$x"])) {
						$slip_reupload = $_POST["slip_reupload$x"];
					} else {
						$slip_reupload = "";
					}

					$sql_mul_amn = "INSERT INTO `daily_collectionmul_amend` (`trans_id`, `rec_id`, `hierarchy_code`, `c_code`, `point_codes`, `rec_no`, `pis_hcl_no`, `hcl_no`, `cust_field1`, `cust_field2`, `cust_field3`, `cust_field4`, `cust_field5`, `2000s`,`1000s`, `500s`, `200s`, `100s`, `50s`, `20s`, `10s`, `5s`, `coins`,`o1000s`, `o500s`, `gen_slip`, `pick_amount`, `mul_remarks`, `reason_for_nill`, `coll_date`, `coll_time`, `coll_dt1`,`pay_slip_date`,slip_image, `user_name`, `status`, master_remark) VALUES
('" . $trans_id . "', '" . $x . "', '" . $_POST['hierarchy_code' . $x] . "', '" . $_POST['c_code' . $x] . "', '" . $_POST['point_code' . $x] . "', '" . $_POST['rec_no' . $x] . "', '" . $_POST['pis_hcl_no' . $x] . "', '" . $_POST['hcl_no' . $x] . "', '" . $_POST['cust_field1' . $x] . "', '" . $_POST['cust_field2' . $x] . "', '" . $_POST['cust_field3' . $x] . "', '" . $_POST['cust_field4' . $x] . "', '" . $_POST['cust_field5' . $x] . "', '" . $_POST['m2000s'][$x] . "','" . $_POST['m1000s'][$x] . "', '" . $_POST['m500s'][$x] . "', '" . $_POST['m200s'][$x] . "', '" . $_POST['m100s'][$x] . "', '" . $_POST['m50s'][$x] . "', '" . $_POST['m20s'][$x] . "', '" . $_POST['m10s'][$x] . "', '" . $_POST['m5s'][$x] . "', '" . $_POST['mcoins'][$x] . "','" . $_POST['mo1000s'][$x] . "', '" . $_POST['mo500s'][$x] . "', '" . $_POST['gen_slip' . $x] . "', '" . $_POST['pick_amount' . $x] . "', '" . $mul_remarks_amn . "', '" . $_POST['reason_for_nil_pickup' . $x] . "',  '" . date('Y-m-d') . "', '" . date('h:i:s A') . "', '" . date('Y-m-d H:i:s') . "','" . $_POST['slip_date' . $x] . "','" . $slip_reupload . "', '" . $user . "', 'Y', '$mul_mas_remarks_amn')";
					mysqli_query($writeConnection, $sql_mul_amn);
				}
			}



			if ($querytamn) {
				header("Location:../?pid=$pid&act=collect&nav=2_3&id=$trans_id");
			}
		} else {


			$mclass = new sendSms();




			$sql11 = "select daily_trans.pickup_code, daily_trans.trans_no, daily_trans.staff_id, daily_trans.pickup_date, shop_details.email_id, client_id,multi_deno, shop_details.cust_id from daily_trans inner join shop_details on daily_trans.pickup_code=shop_details.shop_id inner join cust_details on shop_details.cust_id=cust_details.cust_id where daily_trans.trans_id='" . $trans_id . "' ";
			$qu11 = mysqli_query($writeConnection, $sql11);
			$r11 = mysqli_fetch_assoc($qu11);
			$client_id = $r11['client_id'];
			$cust_id_emailss = $r11['cust_id'];
			$multi_deno_status = $r11['multi_deno'];
			$staff_idss = $r11['staff_id'];
			$pickup_datess = $r11['pickup_date'];
			$shop_ids = $r11['pickup_code'];
			$trans_no = $r11['trans_no'];
			$email_id = $r11['email_id'];

			$ce_ids_upd = $_POST['ce_ids_upd'];
			$ce_ids_upd_cus = explode(',', $_POST['ce_ids_upd']);

			if ($ce_ids_upd != '' && $shop_ids != '') {
				$ce_ids_upd1 = explode('^', $ce_ids_upd);

				$sql_trans_upd = mysqli_query($writeConnection, "UPDATE daily_trans SET staff_id='" . $ce_ids_upd1[0] . "', mobile1='" . $ce_ids_upd1[1] . "' WHERE trans_id='" . $trans_id . "'");
				$sql_ups = mysqli_query($writeConnection, "SELECT * FROM shop_cemap WHERE shop_id='" . $shop_ids . "' AND status='Y'");
				if (mysqli_num_rows($sql_ups) > 0) {
					$sql_trans_upd = mysqli_query($writeConnection, "UPDATE shop_cemap SET pri_ce='" . $ce_ids_upd1[0] . "' WHERE shop_id='" . $shop_ids . "'");
				} else {
					$sql_trans_upd = mysqli_query($writeConnection, "INSERT INTO shop_cemap (`shop_id`, `pri_ce`, `update_by`, `update_date`, `status`) VALUES('" . $shop_ids . "', '" . $ce_ids_upd1[0] . "', '" . $user . "', '" . date('Y-m-d') . "', 'Y')");
				}
			}


			$t_amount = '0';
			$rec_nos = "";
			$rec_nos2 = "";
			$hci_nos = "";
			$gen_slips = "";
			$pick_amounts = "";
			$mul_remarkss = "";
			$reason_for_nill = '';

			$mail_client = $mail_hcl_no = $mail_pick_amount = $mail_rec_no = '';


			if ($t_rec > 1) {
				$multi_rec = 'Y';
				for ($j = 1; $j <= $t_rec; $j++) {
					$c_code = $_POST["c_code$j"];
					$hierarchy_code = $_POST["hierarchy_code$j"];

					$point_code = $_POST["point_code$j"];
					$rec_no = $_POST["rec_no$j"];
					$pisdate_mul = $_POST["pisdate$j"];
					$pis_hcl_no = $_POST["pis_hcl_no$j"];
					$hcl_no = $_POST["hcl_no$j"];
					$gen_slip = $_POST["gen_slip$j"];
					$slip_date = $_POST["slip_date$j"];
					$depositslip_date = $_POST["depositslip_date$j"];
					$cust_field1 = $_POST["cust_field1$j"];
					$cust_field2 = $_POST["cust_field2$j"];
					$cust_field3 = $_POST["cust_field3$j"];
					$cust_field4 = $_POST["cust_field4$j"];
					$cust_field5 = $_POST["cust_field5$j"];
					$pick_amount = $_POST["pick_amount$j"];
					$mul_remarks = $_POST["mul_remarks$j"];
					$addi_remarks = $_POST["addi_remarks$j"];
					$mul_remarks = $mul_remarks;
					if ($addi_remarks != '') {
						$mul_remarks .= '-' . $addi_remarks;
					}
					$reason_for_nill = $_POST["reason_for_nil_pickup$j"];
					$t_amount = $t_amount + $pick_amount;

					$mail_client .= $c_code . '^';
					$mail_hcl_no .= $hcl_no . '^';
					$mail_pick_amount .= $pick_amount . '^';
					$mail_rec_no .= $rec_no . '^';

					if ($rec_nos2 == "") $rec_nos2 = $_POST["rec_no$j"];
					else $rec_nos2 .= ", " . $_POST["rec_no$j"];
					if ($rec_nos == "") $rec_nos = $_POST["pis_hcl_no$j"];
					else $rec_nos .= ", " . $_POST["pis_hcl_no$j"];
					if ($hci_nos == "") $hci_nos = $_POST["hcl_no$j"];
					else $hci_nos .= ", " . $_POST["hcl_no$j"];
					if ($gen_slips == "") $gen_slips = $_POST["gen_slip$j"];
					else $gen_slips .= ", " . $_POST["gen_slip$j"];
					if ($pick_amounts == "") $pick_amounts = $_POST["pick_amount$j"];
					else $pick_amounts .= ", " . $_POST["pick_amount$j"];
					if ($mul_remarkss == "") $mul_remarkss = $_POST["mul_remarks$j"];
					else $mul_remarkss .= ", " . $_POST["mul_remarks$j"];
					if ($reason_for_nilll == "") $reason_for_nilll = $_POST["reason_for_nill$j"];
					else $reason_for_nilll .= ", " . $_POST["reason_for_nill$j"];
				}
				$hierarchy_code = "MULTI";
				$c_code = "MULTI";
				$point_code = "MULTI";
				$rec_no = "MULTI";
				$pis_hcl_no = "MULTI";
				$hcl_no = "MULTI";
				$gen_slip = "MULTI";
				$slip_date = "MULTI";
				$depositslip_date="MULTI";
			} else {
				$multi_rec = 'N';
				$t_rec = 1;
				$hierarchy_code = $_POST['hierarchy_code1'];
				$c_code = $_POST['c_code1'];
				$point_code = $_POST['point_code1'];
				$rec_no = $_POST['rec_no1'];
				$pis_hcl_no = $_POST['pis_hcl_no1'];
				$hcl_no = $_POST['hcl_no1'];
				$gen_slip = $_POST['gen_slip1'];
				$slip_date = $_POST['slip_date1'];
				$depositslip_date = $_POST["depositslip_date1"];
				$cust_field1 = $_POST["cust_field11"];
				$cust_field2 = $_POST["cust_field21"];
				$cust_field3 = $_POST["cust_field31"];
				$cust_field4 = $_POST["cust_field41"];
				$cust_field5 = $_POST["cust_field51"];
				$pick_amount = $_POST['pick_amount1'];
				$t_amount = $_POST['pick_amount1'];
				$rec_nos = $_POST["pis_hcl_no1"];
				$hci_nos = $_POST["hcl_no1"];
				$rec_nos2 = $_POST['rec_no1'];
				$pick_amounts = $_POST['pick_amount1'];
				$reason_for_nill = $_POST['reason_for_nil_pickup1'];

				$mail_client .= $c_code . '^';
				$mail_hcl_no .= $hcl_no . '^';
				$mail_pick_amount .= $pick_amount . '^';
				$mail_rec_no .= $rec_no . '^';
			}

			$req_amount = $_POST['req_amount'];
			if ($req_amount == "") $req_amount = 0;
			$diff_amount = $req_amount - $t_amount;

			$deno = $_POST['deno'];
			if ($deno == "Y") {
				$_2000s = $_POST['2000s'];
				$_1000s = $_POST['1000s'];
				$_500s = $_POST['500s'];
				$_200s = $_POST['200s'];
				$_100s = $_POST['100s'];
				$_50s = $_POST['50s'];
				$_20s = $_POST['20s'];
				$_10s = $_POST['10s'];
				$_5s = $_POST['5s'];
				$_coins = $_POST['coins'];
				$_o1000s = $_POST['o1000s'];
				$_o500s = $_POST['o500s'];
			} else {
				$_2000s = 0;
				$_1000s = 0;
				$_500s = 0;
				$_200s = 0;
				$_100s = 0;
				$_50s = 0;
				$_20s = 0;
				$_10s = 0;
				$_5s = 0;
				$_coins = 0;
				$_o1000s = 0;
				$_o500s = 0;
			}

			$multi_deno1 = $_POST['multi_deno1'];
			if ($multi_deno1 == 1) {

				$m_2000s = $_POST['m2000s'];
				$m_1000s = $_POST['m1000s'];
				$m_500s = $_POST['m500s'];
				$m_200s = $_POST['m200s'];
				$m_100s = $_POST['m100s'];
				$m_50s = $_POST['m50s'];
				$m_20s = $_POST['m20s'];
				$m_10s = $_POST['m10s'];
				$m_5s = $_POST['m5s'];
				$m_coins = $_POST['mcoins'];
				$om_1000s = $_POST['om1000s'];
				$om_500s = $_POST['om500s'];

				$_2000s = $m_2000s[1];
				$_1000s = $m_1000s[1];
				$_200s = $m_200s[1];
				$_500s = $m_500s[1];
				$_100s = $m_100s[1];
				$_50s = $m_50s[1];
				$_20s = $m_20s[1];
				$_10s = $m_10s[1];
				$_5s = $m_5s[1];
				$_coins = $m_coins[1];
				$_o1000s = $om_1000s[1];
				$_o500s = $om_500s[1];
			}

			$dep_type1 = $_POST['dep_type1'];
			$dep_acc1 = $_POST['dep_acc1'];
			if ($dep_acc1 == "") $dep_acc1 = 0;
			$dep_branch = $_POST['dep_branch'];
			$dep_slip = $_POST['dep_slip'];
			$dep_amount1 = $_POST['dep_amount1'];
			if ($dep_amount1 == "") $dep_amount1 = 0;

			$field1 = $_POST['field1'];
			$field2 = $_POST['field2'];
			$field3 = $_POST['field3'];
			$field4 = $_POST['field4'];

			$coll_remarks = $_POST['coll_remarks'];
			$master_remarks = $_POST['mas_coll_remarks'];
			$other_remarks = $_POST['other_remarks'];
			$final_remark = '';
			$final_remark = $coll_remarks;
			if ($other_remarks != '') {
				$final_remark .= '-' . $other_remarks;
			}
			$pick_time = $_POST['pick_time'];
			$ce_id = $_POST['ce_id'];
			$dep_trans_no = $_POST['trans_no1'];

			$dep_amount2 = $_POST['dep_amount2'];
			if ($dep_amount2 == "") $dep_amount2 = 0;
			$dep_bank2 = $_POST['dep_bank2'];
			$other_remarks2 = $_POST['other_remarks2'];

			$coll_date = date("Y-m-d");
			//$coll_date1=date("d-m-Y");

			$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
			$time = date('h:i:s A', $time_now);
			$time1 = date('H:i:s', $time_now);

			$n1 = 0;
			$sql1 = "select * from daily_collection where trans_id='" . $trans_id . "' and status='Y'";

			$qu1 = mysqli_query($writeConnection, $sql1);
			while ($r1 = mysqli_fetch_array($qu1)) {
				$n1 = 1;
			}
			if ($n1 == 0) {


				if ($dep_bank2 != '') {
					$sql_bank = mysqli_query($readConnection, "SELECT bank_name FROM bank_master WHERE acc_id='" . $dep_bank2 . "'  AND status='Y'");
					$res_bank = mysqli_fetch_assoc($sql_bank);
					$bank_names = $res_bank['bank_name'];
				}

				$upd_date = '';

				$upd_date = date('Y-m-d H:i:s');

				$sql = "insert into daily_collection (trans_id,multi_rec,t_rec, hierarchy_code, c_code,point_codes, rec_no, pis_hcl_no, hcl_no,gen_slip,cust_field1,cust_field2,cust_field3,cust_field4,cust_field5,req_amount,pick_amount,diff_amount,2000s,1000s,500s,200s,100s,50s,20s,10s,5s,coins,o1000s,o500s,dep_type1,dep_accid,dep_branch,dep_slip,dep_amount1,coll_remarks,pick_time,staff_id,dep_amount2,dep_bank2,acc_id,other_remarks2,reason_for_nill,coll_date,coll_time,coll_dt1,user_name,status,master_remark,pay_slip_date,depositslip_date,slip_image,reuploaded_by,reuploaded_date) values (" . $trans_id . ",'" . $multi_rec . "'," . $t_rec . ",'" . $hierarchy_code . "', '" . $c_code . "', '" . $point_code . "', '" . $rec_no . "','" . $pis_hcl_no . "','" . $hcl_no . "','" . $gen_slip . "','" . $cust_field1 . "','" . $cust_field2 . "','" . $cust_field3 . "','" . $cust_field4 . "','" . $cust_field5 . "','" . $req_amount . "','" . $t_amount . "','" . $diff_amount . "','" . $_2000s . "','" . $_1000s . "','" . $_500s . "','" . $_200s . "','" . $_100s . "','" . $_50s . "','" . $_20s . "','" . $_10s . "','" . $_5s . "','" . $_coins . "','" . $_o1000s . "','" . $_o500s . "','" . $dep_type1 . "'," . $dep_acc1 . ",'" . $dep_branch . "','" . $dep_slip . "'," . $dep_amount1 . ",'" . $final_remark . "','" . $pick_time . "','" . $ce_id . "'," . $dep_amount2 . ",'" . $bank_names . "','" . $dep_bank2 . "','" . $other_remarks2 . "', '" . $reason_for_nill . "', '" . $coll_date . "','" . $time . "','" . $upd_date . "', '" . $user . "','Y','" . $master_remarks . "','" . $slip_date . "','".$depositslip_date ."','" . $_POST["slip_reupload1"] . "','" . $_SESSION['emp_id'] . "','" . $upd_date . "')";

				if ($dep_type1 == 'Vault' && $_POST['dep_amount1'] != 0) {
					$sql_vault_trans = mysqli_query($writeConnection, "SELECT client_name, cust_name, pickup_name, pickup_date FROM daily_trans WHERE trans_id='" . $trans_id . "'");
					$res_vault_trans = mysqli_fetch_assoc($sql_vault_trans);
					//$ce_ids_upd_cus
					echo $query_vault = "INSERT INTO `vault_daily_trans` (`vault_id`, `trans_date`, `trans_type`, `status`, `created_by`, `created_date`) VALUES ('" . $_POST['vault_name'] . "', '" . $res_vault_trans['pickup_date'] . "', '" . $_POST['vault_type'] . "', 'Y', '" . $user . "', '" . date('Y-m-d H:i:s') . "')";
					$sql_vault = mysqli_query($writeConnection, $query_vault);
					$vault_id_ins = mysqli_insert_id();
					$diff_vaul = '0';
					$diff_vaul = ($_2000s * 2000) + ($_1000s * 1000) + ($_500s * 500) + ($_100s * 100) + ($_50s * 50) + ($_20s * 20) + ($_10s + 10) + ($_5s * 5) + $_coins + ($_o1000s * 1000) + ($_o500s * 500);
					echo $query_vault_det = "INSERT INTO `vault_ce_entry_mul` (`ce_entry_id`, `vault_name`, `entry_date`, `type`, `vault_type`, `ce_id`, `trans_id`, `clients`, `customers`, `shops`, `receipt_no`, `amount`, `cashier_id`, `2000s`,`1000s`, `500s`, `100s`, `50s`, `20s`, `10s`, `5s`, `1s`,`o1000s`, `o500s`, `difference`, `status`, `created_by`, `created_date`) VALUES('" . $vault_id_ins . "', '" . $_POST['vault_name'] . "', '" . $res_vault_trans['pickup_date'] . "', 'vault', '" . $_POST['vault_type'] . "', '" . $ce_ids_upd_cus[0] . "', '" . $trans_id . "', '" . $res_vault_trans['client_name'] . "', '" . $res_vault_trans['cust_name'] . "', '" . $res_vault_trans['pickup_name'] . "', '" . $_POST['rec_no1'] . "', '" . $_POST['dep_amount1'] . "', '', '" . $_2000s . "','" . $_1000s . "', '" . $_500s . "', '" . $_100s . "', '" . $_50s . "', '" . $_20s . "', '" . $_10s . "', '" . $_5s . "', '" . $_coins . "','" . $_o1000s . "', '" . $_o500s . "', '" . $diff_vaul . "', 'Y', '" . $user . "', '" . date('Y-m-d H:i:s') . "')";
					$sql_vault_det = mysqli_query($writeConnection, $query_vault_det);
				}

				if ($dep_type1 != 'Vault' && $dep_type1 != '' && $_REQUEST['dep_confirm'] == 1) {
					if ($_POST['dep_amount2'] != 0) {
						$dep_amt = $_POST['dep_amount2'];
						$acc_id = $dep_bank2;
					} else {
						$dep_amt = $dep_amount1;
						$acc_id = $dep_acc1;
					}
					if ($dep_amt > 0) {
						$sql_depo = "SELECT * FROM `daily_deposit` WHERE `trans_id`='" . $trans_id . "' AND status='Y'";
						$sql1_depo = mysqli_query($writeConnection, $sql_depo);
						if ($ce_ids_upd != '' && $shop_ids != '') {
							$ce_ids_upd1 = explode('^', $ce_ids_upd);
							$cu_ce_ids = $ce_ids_upd1[0];
						} else {
							$cu_ce_ids = $staff_idss;
						}

						if (mysqli_num_rows($sql1_depo) > 0) {
							$query_depo = mysqli_query($writeConnection, "UPDATE `daily_deposit` SET `dep_type`='" . $dep_type1 . "', `acc_id`='" . $acc_id . "', `dep_amount`='" . $dep_amt . "', `remarks`='" . $other_remarks2 . "',  `staff_id`='" . $cu_ce_ids . "', `dep_date`='" . $pickup_datess . "', `dep_time`='" . $time . "', `user_name`='" . $user . "' WHERE `trans_id`='" . $trans_id . "' AND `status`='Y'");
						} else {
							$query_depo = mysqli_query($writeConnection, "INSERT INTO `daily_deposit` (`trans_id`, `ent_type`, `dep_type`, `acc_id`, `dep_amount`, `remarks`, `staff_id`, `dep_date`, `dep_time`, `user_name`, `status`) VALUES ('" . $trans_id . "', 'Deposit', '" . $dep_type1 . "', '" . $acc_id . "', '" . $dep_amt . "', '" . $other_remarks2 . "', '" . $cu_ce_ids . "', '" . $pickup_datess . "','" . $time . "', '" . $user . "', 'Y' )");
						}
					}
				}

				if (mysqli_query($writeConnection, $sql) == 1) {



					if ($field1 != '' || $field2 != '' || $field3 != '' || $field4 != '') {
						$sql3 = "insert into daily_collectionannex (trans_id, field1, field2, field3, field4, status) values ('" . $trans_id . "', '" . $field1 . "', '" . $field2 . "', '" . $field3 . "', '" . $field4 . "','Y')";
						$qu3 = mysqli_query($writeConnection, $sql3);
					}

					if ($multi_rec == 'Y') {
						$m2000s = $_POST['m2000s'];
						$m1000s = $_POST['m1000s'];
						$m500s = $_POST['m500s'];
						$m200s = $_POST['m200s'];
						$m100s = $_POST['m100s'];
						$m50s = $_POST['m50s'];
						$m20s = $_POST['m20s'];
						$m10s = $_POST['m10s'];
						$m5s = $_POST['m5s'];
						$mcoins = $_POST['mcoins'];
						$om1000s = $_POST['om1000s'];
						$om500s = $_POST['om500s'];
						$mdeno_total = $_POST['mdeno_total'];
						$mdeno_diff = $_POST['mdeno_diff'];
						$req_amounts = $_POST['req_amounts'];
						$denos = $_POST['denos'];

						$t_2000s = $t_1000s = $t_500s = $t_200s = $t_100s = $t_50s = $t_20s = $t_10s = $t_5s = $t_coins = $ot_1000s = $ot_500s = 0;
						$m = 0;
						for ($j = 1; $j <= $t_rec; $j++) {

							$hierarchy_code = $_POST["hierarchy_code$j"];
							$c_code = $_POST["c_code$j"];
							$pisdate_mul = $_POST["pisdate$j"];
							$point_code = $_POST["point_code$j"];
							$rec_no = $_POST["rec_no$j"];
							$pis_hcl_no = $_POST["pis_hcl_no$j"];
							$hcl_no = $_POST["hcl_no$j"];
							$gen_slip = $_POST["gen_slip$j"];
							$slip_date = $_POST["slip_date$j"];
							$depositslip_date = $_POST["depositslip_date$j"];
							$cust_field1 = $_POST["cust_field1$j"];
							$cust_field2 = $_POST["cust_field2$j"];
							$cust_field3 = $_POST["cust_field3$j"];
							$cust_field4 = $_POST["cust_field4$j"];
							$cust_field5 = $_POST["cust_field5$j"];

							$pick_amount = $_POST["pick_amount$j"];
							$mul_remarks = $_POST["mul_remarks$j"];
							$master_mul_remarks = $_POST["master_rem_mul$j"];
							$addi_remarks = $_POST["addi_remarks$j"];
							$dep_trans_no1 = $dep_trans_no . $dep_no_array[$m];
							$mul_remarks = $mul_remarks;
							if ($addi_remarks != '') {
								$mul_remarks .= '-' . $addi_remarks;
							}
							$reason_for_nill = $_POST["reason_for_nil_pickup$j"];

							/*$mail_client.= $c_code.'^';
		$mail_hcl_no.= $hcl_no.'^';
		$mail_pick_amount.= $pick_amount.'^';
		$mail_rec_no.= $rec_no.'^';*/

							$upd_date = '';
							$upd_date = date('Y-m-d H:i:s');
							if (isset($_POST["slip_reupload$j"])) {
								$slip_reupload = $_POST["slip_reupload$j"];
							} else {
								$slip_reupload = "";
							}

							$sql1 = "insert into daily_collectionmul (trans_id,rec_id,hierarchy_code, c_code, point_codes, rec_no,pis_hcl_no,hcl_no,cust_field1,cust_field2,cust_field3,cust_field4,cust_field5, gen_slip, req_amount, diff_amount,2000s, 1000s, 500s,200s, 100s, 50s, 20s, 10s, 5s,coins, o1000s, o500s, pick_amount,mul_remarks,reason_for_nill,staff_id,dep_trans_no,coll_date,coll_time,coll_dt1,user_name,status,master_remark,pay_slip_date,depositslip_date,pisdate,slip_image,reuploaded_by,reuploaded_date) values (" . $trans_id . "," . $j . ",'" . $hierarchy_code . "', '" . $c_code . "', '" . $point_code . "', '" . $rec_no . "','" . $pis_hcl_no . "','" . $hcl_no . "', '" . $cust_field1 . "', '" . $cust_field2 . "', '" . $cust_field3 . "', '" . $cust_field4 . "', '" . $cust_field5 . "', '" . $gen_slip . "','" . $req_amounts[$j] . "','" . $denos[$j] . "','" . $m2000s[$j] . "', '" . $m1000s[$j] . "', '" . $m500s[$j] . "', '" . $m200s[$j] . "', '" . $m100s[$j] . "', '" . $m50s[$j] . "', '" . $m20s[$j] . "', '" . $m10s[$j] . "', '" . $m5s[$j] . "', '" . $mcoins[$j] . "', '" . $om1000s[$j] . "', '" . $om500s[$j] . "', " . $pick_amount . ",'" . $mul_remarks . "', '" . $reason_for_nill . "','" . $ce_id . "','" . $dep_trans_no1 . "','" . $coll_date . "','" . $time . "','" . $upd_date . "', '" . $user . "','Y','" . $master_mul_remarks . "','" . $slip_date . "','".$depositslip_date."','" . $pisdate_mul . "','" . $slip_reupload . "','" . $_SESSION['emp_id'] . "','" . $upd_date . "')";



							$qu1 = mysqli_query($writeConnection, $sql1);
							$t_2000s += $m2000s[$j];
							$t_1000s += $m1000s[$j];
							$t_500s += $m500s[$j];
							$t_200s += $m200s[$j];
							$t_100s += $m100s[$j];
							$t_50s += $m50s[$j];
							$t_20s += $m20s[$j];
							$t_10s += $m10s[$j];

							$t_5s += $m5s[$j];
							$t_coins += $mcoins[$j];
							$ot_1000s += $om1000s[$j];
							$ot_500s += $om500s[$j];
							$m++;
						}
						if ($multi_deno_status == 1) {
							$sql23 = "update daily_collection set 2000s=" . $t_2000s . ",1000s=" . $t_1000s . ", 500s=" . $t_500s . ", 200s=" . $t_200s . ", 100s=" . $t_100s . ", 50s=" . $t_50s . ", 20s=" . $t_20s . ", 10s=" . $t_10s . ", 5s=" . $t_5s . ", coins=" . $t_coins . ",o1000s=" . $ot_1000s . ", o500s=" . $ot_500s . " where trans_id='" . $trans_id . "' ";
							//echo $sql23;
							$sql_deno = mysqli_query($writeConnection, $sql23);
						}
					}

					// Update CIH Details
					$update_date = $coll_date . ", " . $time;
					$sqlc = "select * from ce_cih where ce_id='" . $ce_id . "' and status='Y'";
					$quc = mysqli_query($writeConnection, $sqlc);
					$nc = mysqli_num_rows($quc);

					if ($nc == 1) {
						while ($rc = mysqli_fetch_array($quc)) {
							$prev_cih = $rc['cih_amount'];
						}
						$ce_cih = $prev_cih + $t_amount;

						$quc1 = mysqli_query($writeConnection, "update ce_cih set cih_amount=" . $ce_cih . ", last_update='" . date("Y-m-d") . "',update_by='" . $user . "',update_date='" . $update_date . "' where ce_id='" . $ce_id . "'");
					} else {
						$ce_cih = $t_amount;

						$quc1 = mysqli_query($writeConnection, "insert into ce_cih (ce_id,cih_amount,last_update,update_by,update_date,status) values ('" . $ce_id . "'," . $ce_cih . ",'" . date("Y-m-d") . "','" . $user . "','" . $update_now . "','Y')");
					}


					//Check particular Client ID
					$cust_id1 = "";



					// new line transaction Update

					if ($region == 6) {
						$tt_time1 = date("h:i:s");
						$tt_time11 = date("d-m-y h:i:s");
						$trr_id1 = $_POST['trans_id'];
						$up_date1 = date("Y-m-d h:i:s");
						//$em_id=$_SESSION['emp_id'];
						$tt_trace1 = mysqli_fetch_array(mysqli_query($writeConnection, "select trans_ids,submit_time from trans_updated_tracker where  trans_ids='" . $trr_id1 . "' and status='Y'"));
						$submit_time = $tt_trace1['submit_time'];
						$tot_time_updated = $submit_time - $tt_time1;
						$tt_sql1 = mysqli_query($writeConnection, "update trans_updated_tracker set updated_time='" . $tt_time1 . "',tot_time_updated='" . $tot_time_updated . "',user_name='" . $user . "',staff_id='" . $_SESSION['emp_id'] . "' where trans_ids='" . $trr_id1 . "' and status='Y'");
					}


					header("Location:../?pid=$pid&act=collect&nav=2_1&id=$trans_id");
				} else {
					header("Location:../?pid=$pid&act=collect&nav=2_2&id=$trans_id");
				}
			} elseif ($n1 == 1) {
				if ($_POST['cur_client'] == '22') {
					if ($t_rec == 1) {
						$sql_mod = mysqli_query($writeConnection, "SELECT trans_id FROM daily_collection WHERE hierarchy_code='" . $_POST['hierarchy_code1'] . "' AND pick_amount='" . $_POST['pick_amount1'] . "' AND rec_no='" . $_POST['rec_no1'] . "' AND c_code='" . $_POST['c_code1'] . "' AND status='Y'");
						if (mysqli_num_rows($sql_mod) == 0) {
							$sql_mod1 = mysqli_query($writeConnection, "SELECT * FROM modify_daily_trans WHERE pick_amount='" . $_POST['pick_amount1'] . "' AND deposit_slip='" . $_POST['rec_no1'] . "' AND client_code='" . $_POST['c_code1'] . "' AND hierarchy_code='" . $_POST['hierarchy_code1'] . "' AND status='Y'");
							if (mysqli_num_rows($sql_mod1) == 0) {
								$res_mod2 = mysqli_fetch_assoc($sql_mod);
								$sql_mod3 = mysqli_query($writeConnection, "SELECT trans_id, hierarchy_code, pick_amount, pick_amount, rec_no, c_code, coll_dt1 FROM daily_collection WHERE trans_id='" . $trans_id . "'");
								$res_mod = mysqli_fetch_assoc($sql_mod3);

								$sql_ins_mod = mysqli_query($writeConnection, "INSERT INTO `modify_daily_trans` (`trans_id`, `rec_no`, `old_pick_amount`, `pick_amount`, `old_deposit_slip`, `deposit_slip`, `old_client_code`, `client_code`, `old_hierarchy_code`, `hierarchy_code`, `added_date`, `update_date`, `update_by`, `status`) VALUES
('" . $trans_id . "', '0', '" . $res_mod['pick_amount'] . "', '" . $_POST['pick_amount1'] . "', '" . $res_mod['rec_no'] . "', '" . $_POST['rec_no1'] . "', '" . $res_mod['c_code'] . "', '" . $_POST['c_code1'] . "', '" . $res_mod['hierarchy_code'] . "', '" . $_POST['hierarchy_code1'] . "', '" . $res_mod['coll_dt1'] . "', '" . date('Y-m-d H:i:s') . "', '" . $user . "', 'Y')");
							}
						}
					} else {
						for ($z = 1; $z <= $t_rec; $z++) {
							$sql_mod = mysqli_query($writeConnection, "SELECT trans_id FROM daily_collectionmul WHERE hierarchy_code='" . $_POST['hierarchy_code' . $z] . "' AND pick_amount='" . $_POST['pick_amount' . $z] . "' AND rec_no='" . $_POST['rec_no' . $z] . "' AND c_code='" . $_POST['c_code' . $z] . "' AND rec_id='" . $z . "' AND status='Y'");
							if (mysqli_num_rows($sql_mod) == 0) {
								$sql_mod1 = mysqli_query($writeConnection, "SELECT * FROM modify_daily_trans WHERE pick_amount='" . $_POST['pick_amount' . $z] . "' AND deposit_slip='" . $_POST['rec_no' . $z] . "' AND client_code='" . $_POST['c_code' . $z] . "' AND hierarchy_code='" . $_POST['hierarchy_code' . $z] . "'  AND rec_no='" . $z . "' AND status='Y'");
								if (mysqli_num_rows($sql_mod1) == 0) {
									$res_mod2 = mysqli_fetch_assoc($sql_mod);
									$sql_mod3 = mysqli_query($writeConnection, "SELECT trans_id, hierarchy_code, pick_amount, pick_amount, rec_no, c_code, coll_dt1 FROM daily_collectionmul WHERE trans_id='" . $trans_id . "' AND rec_id='" . $z . "'");
									$res_mod = mysqli_fetch_assoc($sql_mod3);


									$sql_ins_mod = mysqli_query($writeConnection, "INSERT INTO `modify_daily_trans` (`trans_id`, `rec_no`, `old_pick_amount`, `pick_amount`, `old_deposit_slip`, `deposit_slip`, `old_client_code`, `client_code`, `old_hierarchy_code`, `hierarchy_code`, `added_date`, `update_date`, `update_by`, `status`) VALUES
('" . $trans_id . "', '0', '" . $res_mod['pick_amount'] . "', '" . $_POST['pick_amount' . $z] . "', '" . $res_mod['rec_no'] . "', '" . $_POST['rec_no' . $z] . "', '" . $res_mod['c_code'] . "', '" . $_POST['c_code' . $z] . "', '" . $res_mod['hierarchy_code'] . "', '" . $_POST['hierarchy_code' . $z] . "', '" . $res_mod['coll_dt1'] . "', '" . date('Y-m-d H:i:s') . "', '" . $user . "', 'Y')");
								}
							}
						}
					}
				}

				//Get Previous Collection Amount for CIH Amount Change (Corr_amt - Prev_amt)
				$sql3 = "select * from daily_collection where trans_id='" . $trans_id . "' and coll_id='" . $coll_id . "' and status='Y'";
				//echo $sql3;
				$qu3 = mysqli_query($writeConnection, $sql3);
				while ($r3 = mysqli_fetch_array($qu3)) {
					$prev_amt = $r3['pick_amount'];
					$pdep_type = $r3['dep_type1'];
				}
				$adj_amt = $t_amount - $prev_amt;


				if ($dep_bank2 != '') {
					$sql_bank = mysqli_query($writeConnection, "SELECT bank_name FROM bank_master WHERE acc_id='" . $dep_bank2 . "' AND status='Y'");
					$res_bank = mysqli_fetch_assoc($sql_bank);
					$bank_names = $res_bank['bank_name'];
				}

				$upd_date = '';
				$upd_date = date('Y-m-d H:i:s');
				//$final_remark = $coll_remarks.' - '.$other_remarks;
				//,other_remarks='".$other_remarks."'
				$sql = "update daily_collection set multi_rec='" . $multi_rec . "',t_rec=" . $t_rec . ",hierarchy_code='" . $hierarchy_code . "',c_code='" . $c_code . "',point_codes='" . $point_code . "',rec_no='" . $rec_no . "',pis_hcl_no='" . $pis_hcl_no . "',hcl_no='" . $hcl_no . "',gen_slip='" . $gen_slip . "', cust_field1='" . $cust_field1 . "', cust_field1='" . $cust_field1 . "', cust_field2='" . $cust_field2 . "', cust_field3='" . $cust_field3 . "', cust_field4='" . $cust_field4 . "', cust_field5='" . $cust_field5 . "' ,pick_amount='" . $t_amount . "',diff_amount='" . $diff_amount . "',2000s='" . $_2000s . "',1000s='" . $_1000s . "',500s='" . $_500s . "',200s='" . $_200s . "',100s='" . $_100s . "',50s='" . $_50s . "',20s='" . $_20s . "',10s='" . $_10s . "',5s='" . $_5s . "',coins='" . $_coins . "',o1000s='" . $_o1000s . "',o500s='" . $_o500s . "',dep_type1='" . $dep_type1 . "',dep_accid=" . $dep_acc1 . ",dep_branch='" . $dep_branch . "',dep_slip='" . $dep_slip . "',dep_amount1=" . $dep_amount1 . ",coll_remarks='" . $final_remark . "', pick_time='" . $pick_time . "',staff_id='" . $ce_id . "',dep_amount2=" . $dep_amount2 . ",dep_bank2='" . $bank_names . "', acc_id='" . $dep_bank2 . "', other_remarks2='" . $other_remarks2 . "', reason_for_nill='" . $reason_for_nill . "', coll_date='" . $coll_date . "',coll_time='" . $time . "', coll_dt1='" . $upd_date . "', user_name='" . $user . "', master_remark='" . $master_remarks . "',pay_slip_date='" . $slip_date . "',slip_image='" . $_POST["slip_reupload1"] . "',reuploaded_by='" . $_SESSION['emp_id'] . "',reuploaded_date='" . $upd_date . "' where trans_id=" . $trans_id . " and coll_id=" . $coll_id . " and status='Y'";

				if ($dep_type1 != 'Vault' && $dep_type1 != '' && $_REQUEST['dep_confirm'] == 1) {
					if ($_POST['dep_amount2'] != 0) {
						$dep_amt = $_POST['dep_amount2'];
						$acc_id  = $dep_bank2;
					} else {
						$dep_amt = $dep_amount1;
						$acc_id  = $dep_acc1;
					}
					if ($dep_amt > 0) {
						$sql_depo = "SELECT * FROM `daily_deposit` WHERE `trans_id`='" . $trans_id . "' AND status='Y'";
						$sql1_depo = mysqli_query($writeConnection, $sql_depo);
						if ($ce_ids_upd != '' && $shop_ids != '') {
							$ce_ids_upd1 = explode('^', $ce_ids_upd);
							$cu_ce_ids = $ce_ids_upd1[0];
						} else {
							$cu_ce_ids = $staff_idss;
						}

						if (mysqli_num_rows($sql1_depo) > 0) {
							$query_depo = mysqli_query($writeConnection, "UPDATE `daily_deposit` SET  `dep_type`='" . $dep_type1 . "', `acc_id`='" . $acc_id . "', `dep_amount`='" . $dep_amt . "', `remarks`='" . $other_remarks2 . "', `staff_id`='" . $cu_ce_ids . "', `dep_date`='" . $pickup_datess . "', `dep_time`='" . $time . "', `user_name`='" . $user . "' WHERE `trans_id`='" . $trans_id . "' AND `status`='Y'");
						} else {

							$query_depo = mysqli_query($writeConnection, "INSERT INTO `daily_deposit` (`trans_id`, `ent_type`, `dep_type`, `acc_id`, `dep_amount`, `remarks`, `staff_id`, `dep_date`, `dep_time`, `user_name`, `status`) VALUES ('" . $trans_id . "', 'Deposit', '" . $dep_type1 . "', '" . $acc_id . "', '" . $dep_amt . "', '" . $other_remarks2 . "', '" . $cu_ce_ids . "', '" . $pickup_datess . "','" . $time . "', '" . $user . "', 'Y' )");
						}
					}
				}

				//echo $sql;
				if (mysqli_query($writeConnection, $sql) == 1) {

					if ($field1 != '' || $field2 != '' || $field3 != '' || $field4 != '') {

						$sql31 = "select * from daily_collectionannex where trans_id='" . $trans_id . "' and status='Y'";
						$qu31 = mysqli_query($writeConnection, $sql31);
						if (mysqli_num_rows($qu31) == 1)
							$sql3 = "update daily_collectionannex set field1='" . $field1 . "', field2='" . $field2 . "', field3='" . $field3 . "', field4='" . $field4 . "' where trans_id='" . $trans_id . "' ";
						else
							$sql3 = "insert into daily_collectionannex (trans_id, field1, field2, field3, field4, status) values (" . $trans_id . ", '" . $field1 . "', '" . $field2 . "', '" . $field3 . "', '" . $field4 . "','Y')";
						$qu3 = mysqli_query($writeConnection, $sql3);
					}

					$sqld = "delete from daily_collectionmul where trans_id='" . $trans_id . "' ";
					$qud = mysqli_query($writeConnection, $sqld);
					//print'<pre>';print_r($_POST);
					if ($multi_rec == 'Y') {
						$m2000s = $_POST['m2000s'];
						$m1000s = $_POST['m1000s'];
						$m500s = $_POST['m500s'];
						$m200s = $_POST['m200s'];
						$m100s = $_POST['m100s'];
						$m50s = $_POST['m50s'];
						$m20s = $_POST['m20s'];
						$m10s = $_POST['m10s'];
						$m5s = $_POST['m5s'];
						$mcoins = $_POST['mcoins'];
						$om1000s = $_POST['om1000s'];
						$om500s = $_POST['om500s'];
						$mdeno_total = $_POST['mdeno_total'];
						$mdeno_diff = $_POST['mdeno_diff'];
						$req_amounts = $_POST['req_amounts'];
						$denos = $_POST['denos'];
						$t_2000s = $t_1000s = $t_500s = $t_200s =  $t_100s = $t_50s = $t_20s = $t_10s = $t_5s = $t_coins = $ot_1000s = $ot_500s = 0;
						$m = 0;
						for ($j = 1; $j <= $t_rec; $j++) {
							$pisdate_mul = $_POST["pisdate$j"];
							$hierarchy_code = $_POST["hierarchy_code$j"];
							$c_code = $_POST["c_code$j"];
							$point_code = $_POST["point_code$j"];
							$rec_no = $_POST["rec_no$j"];
							$pis_hcl_no = $_POST["pis_hcl_no$j"];
							$hcl_no = $_POST["hcl_no$j"];
							$gen_slip = $_POST["gen_slip$j"];
							$slip_date = $_POST['slip_date' . $j];
							$depositslip_date=$_POST['depositslip_date'.$j];
							$cust_field1 = $_POST['cust_field1' . $j];
							$cust_field2 = $_POST['cust_field2' . $j];
							$cust_field3 = $_POST['cust_field3' . $j];
							$cust_field4 = $_POST['cust_field4' . $j];
							$cust_field5 = $_POST['cust_field5' . $j];
							$dep_trans_no1 = $dep_trans_no . $dep_no_array[$m];

							$pick_amount = $_POST["pick_amount$j"];
							$mul_remarks = $_POST["mul_remarks$j"];
							$master_mul_remarks = $_POST["master_rem_mul$j"];
							$addi_remarks = $_POST["addi_remarks$j"];
							$mul_remarks = $mul_remarks;
							if ($addi_remarks != '') {
								$mul_remarks .= '-' . $addi_remarks;
							}
							$reason_for_nill = $_POST["reason_for_nil_pickup$j"];

							/*$mail_client.= $c_code.'^';
		$mail_hcl_no.= $hcl_no.'^';
		$mail_pick_amount.= $pick_amount.'^';
		$mail_rec_no.= $rec_no.'^';*/

							$upd_date = '';
							$upd_date = date('Y-m-d H:i:s');
							if (isset($_POST["slip_reupload$j"])) {
								$slip_reupload = $_POST["slip_reupload$j"];
							} else {
								$slip_reupload = "";
							}

							$sql1 = "insert into daily_collectionmul (trans_id,rec_id,hierarchy_code,c_code,point_codes, rec_no, pis_hcl_no,hcl_no,cust_field1,cust_field2,cust_field3,cust_field4,cust_field5,gen_slip,req_amount,diff_amount, 2000s,1000s, 500s, 200s, 100s, 50s, 20s, 10s, 5s, coins, o1000s, o500s,pick_amount,mul_remarks,reason_for_nill,staff_id,dep_trans_no,coll_date,coll_time,coll_dt1,user_name,status,master_remark,pay_slip_date,depositslip_date,pisdate,slip_image,reuploaded_by,reuploaded_date) values (" . $trans_id . "," . $j . ",'" . $hierarchy_code . "', '" . $c_code . "', '" . $point_code . "', '" . $rec_no . "','" . $pis_hcl_no . "','" . $hcl_no . "','" . $cust_field1 . "','" . $cust_field2 . "','" . $cust_field3 . "','" . $cust_field4 . "','" . $cust_field5 . "','" . $gen_slip . "','" . $req_amounts[$j] . "','" . $denos[$j] . "', '" . $m2000s[$j] . "','" . $m1000s[$j] . "', '" . $m500s[$j] . "', '" . $m200s[$j] . "','" . $m100s[$j] . "', '" . $m50s[$j] . "', '" . $m20s[$j] . "', '" . $m10s[$j] . "', '" . $m5s[$j] . "', '" . $mcoins[$j] . "','" . $om1000s[$j] . "', '" . $om500s[$j] . "','" . $pick_amount . "','" . $mul_remarks . "','" . $reason_for_nill . "','" . $ce_id . "','" . $dep_trans_no1 . "','" . $coll_date . "','" . $time . "','" . $upd_date . "', '" . $user . "','Y','" . $master_mul_remarks . "','" . $slip_date . "','".$depositslip_date."','" . $pisdate_mul . "','" . $slip_reupload . "','" . $_SESSION['emp_id'] . "','" . $upd_date . "')";
							//echo $sql1.'<br />';
							//$sql1="insert into daily_collectionmul (trans_id,rec_id,c_code,rec_no,pis_hcl_no,hcl_no,gen_slip,pick_amount,staff_id,coll_date,coll_time,user_name,status) values (".$trans_id.",".$j.",'".$c_code."','".$rec_no."','".$pis_hcl_no."','".$hcl_no."','".$gen_slip."',".$pick_amount.",'".$ce_id."','".$coll_date."','".$time."','".$user."','Y')";
							$qu1 = mysqli_query($writeConnection, $sql1);
							$t_2000s += $m2000s[$j];
							$t_1000s += $m1000s[$j];
							$t_500s += $m500s[$j];
							$t_200s += $m200s[$j];
							$t_100s += $m100s[$j];
							$t_50s += $m50s[$j];
							$t_20s += $m20s[$j];
							$t_10s += $m10s[$j];
							$t_5s += $m5s[$j];
							$t_coins += $mcoins[$j];
							$ot_1000s += $om1000s[$j];
							$ot_500s += $om500s[$j];
							$m++;
						}
						if ($multi_deno_status == 1) {
							$sql23 = "update daily_collection set 2000s=" . $t_2000s . ",1000s=" . $t_1000s . ", 500s=" . $t_500s . ", 200s=" . $t_200s . ", 100s=" . $t_100s . ", 50s=" . $t_50s . ", 20s=" . $t_20s . ", 10s=" . $t_10s . ", 5s=" . $t_5s . ", coins=" . $t_coins . ",o1000s=" . $ot_1000s . ", o500s=" . $ot_500s . " where trans_id=" . $trans_id . "";

							$sql_deno = mysqli_query($writeConnection, $sql23);
						}
					}


					// Update CIH Details
					$update_date = $coll_date . ", " . $time;
					$sqlc = "select * from ce_cih where ce_id='" . $ce_id . "' and status='Y'";
					$quc = mysqli_query($writeConnection, $sqlc);
					$nc = mysqli_num_rows($quc);

					if ($nc == 1) {
						while ($rc = mysqli_fetch_array($quc)) {
							$prev_cih = $rc['cih_amount'];
						}

						$ce_cih = $prev_cih + $adj_amt;

						$quc1 = mysqli_query($writeConnection, "update ce_cih set cih_amount=" . $ce_cih . ", last_update='" . date("Y-m-d") . "',update_by='" . $user . "',update_date='" . $update_date . "' where ce_id='" . $ce_id . "'");
					} else {
						$ce_cih = $t_amount;
						$quc1 = mysqli_query($writeConnection, "insert into ce_cih (ce_id,cih_amount,last_update,update_by,update_date,status) values ('" . $ce_id . "'," . $ce_cih . ",'" . date("Y-m-d") . "','" . $user . "','" . $update_now . "','Y')");
					}


					//Check particular Client ID
					$cust_id1 = "";


					if ($region == 6) {
						$tt_time1 = date("h:i:s");
						$tt_time11 = date("d-m-y h:i:s");
						$trr_id1 = $_POST['trans_id'];
						$up_date1 = date("Y-m-d h:i:s");
						//$em_id=$_SESSION['emp_id'];
						$tt_trace1 = mysqli_fetch_array(mysqli_query($writeConnection, "select trans_ids,submit_time from trans_updated_tracker where  trans_ids='" . $trr_id1 . "' and status='Y'"));
						$submit_time = $tt_trace1['submit_time'];
						$tot_time_updated = $submit_time - $tt_time1;
						$tt_sql1 = mysqli_query($writeConnection, "update trans_updated_tracker set updated_time='" . $tt_time1 . "',tot_time_updated='" . $tot_time_updated . "',user_name='" . $user . "',staff_id='" . $_SESSION['emp_id'] . "' where trans_ids='" . $trr_id1 . "' and status='Y'");
					}

					// // mysqli_close($conn);
					header("Location:../?pid=$pid&act=collect&nav=2_3&id=$trans_id");
				} else {
					//  // mysqli_close($conn);
					header("Location:../?pid=$pid&act=collect&nav=2_4&id=$trans_id");
				}
			}
		}

		// $posturl = "https://nrazindbs.radianterp.in/ace_api_rcms_wocron.php?txntype=2&txnid=$trans_id";
		// $postresponse = @file_get_contents($posturl);

	} else if ($pid == 'ddeli') {

		$mclass = new sendSms();
		$pid = "vtranslog";
		$trans_id = $_POST['trans_id'];
		$del_id = $_POST['del_id'];

		$reqrec_date = date("Y-m-d", strtotime($_POST['reqrec_date']));
		$reqrec_time = $_POST['reqrec_time'];
		$del_to = $_POST['del_to'];
		$drec_no = $_POST['drec_no'];
		$del_amount = $_POST['del_amount'];
		$upd_shop_idss = $_POST['upd_shop_idss'];
		$ce_ids_upd = $_POST['ce_ids_upd'];
		$ce_ids_upd2 = explode('^', $_POST['ce_ids_upd']);
		if ($ce_ids_upd != '' && $upd_shop_idss != '') {
			$ce_ids_upd1 = explode('^', $ce_ids_upd);

			$sql_trans_upd = mysqli_query($writeConnection, "UPDATE daily_trans SET staff_id='" . $ce_ids_upd1[0] . "', mobile1='" . $ce_ids_upd1[1] . "' WHERE trans_id='" . $trans_id . "'");
			$sql_ups = mysqli_query($writeConnection, "SELECT * FROM shop_cemap WHERE shop_id='" . $upd_shop_idss . "' AND status='Y'");
			if (mysqli_num_rows($sql_ups) > 0) {
				$sql_trans_upd = mysqli_query($writeConnection, "UPDATE shop_cemap SET pri_ce='" . $ce_ids_upd1[0] . "' WHERE shop_id='" . $upd_shop_idss . "'");
			} else {
				$sql_trans_upd = mysqli_query($writeConnection, "INSERT INTO shop_cemap (`shop_id`, `pri_ce`, `update_by`, `update_date`, `status`) VALUES('" . $upd_shop_idss . "', '" . $ce_ids_upd1[0] . "', '" . $user . "', '" . date('Y-m-d') . "', 'Y')");
			}
		}

		$req_amount = $_POST['req_amount'];
		$diff_amount = $req_amount - $del_amount;
		$_2000s = 0;
		$_1000s = 0;
		$_500s = 0;
		$_200s = 0;
		$_100s = 0;
		$_50s = 0;
		$_20s = 0;
		$_10s = 0;
		$_5s = 0;
		$_coins = 0;
		$_o1000s = 0;
		$_o500s = 0;

		$deno = $_POST['deno'];

		if ($deno == "Y") {
			$_2000s = $_POST['2000s'];
			$_1000s = $_POST['1000s'];
			$_500s = $_POST['500s'];
			$_200s = $_POST['200s'];
			$_100s = $_POST['100s'];
			$_50s = $_POST['50s'];
			$_20s = $_POST['20s'];
			$_10s = $_POST['10s'];
			$_5s = $_POST['5s'];
			$_coins = $_POST['coins'];
			$_o1000s = $_POST['o1000s'];
			$_o500s = $_POST['o500s'];
		} else {
			$_2000s = 0;
			$_1000s = 0;
			$_500s = 0;
			$_200s = 0;
			$_100s = 0;
			$_50s = 0;
			$_20s = 0;
			$_10s = 0;
			$_5s = 0;
			$_coins = 0;
			$_o1000s = 0;
			$_o500s = 0;
		}
		$draw_status = $_POST['draw_status'];
		$del_remarks = $_POST['del_remarks'];
		$other_remarks = $_POST['other_remarks'];
		$rec_status = $_POST['rec_status'];
		$delivery_time = $_POST['delivery_time'];
		$ce_id = $_POST['ce_id'];

		$ref_no = $_POST['ref_no'];
		$visit1 = $_POST['visit1'];
		$visit2 = $_POST['visit2'];
		$visit3 = $_POST['visit3'];
		$cost_of_operation = $_POST['cost_of_operation'];

		$del_date = date("Y-m-d");

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);

		$n1 = 0;
		$sql1 = "select * from daily_delivery where trans_id='" . $trans_id . "' and status='Y'";

		$qu1 = mysqli_query($writeConnection, $sql1);
		while ($r1 = mysqli_fetch_array($qu1)) {
			$n1 = 1;
		}
		if ($n1 == 0) {

			$d_ids = "";
			if ($draw_status == "Yes") {
				//Insert Withdraw entries
				$t_draw = $_POST['t_draw'];
				$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
				$t_amount = 0;
				if ($t_draw > 1) {
					for ($j = 1; $j <= $t_draw; $j++) {

						$acc_id = $_POST["acc_id$j"];
						$cheque_no = $_POST["cheque_no$j"];
						$cheque_amt = $_POST["cheque_amount$j"];
						$draw_time = $_POST["draw_time$j"];
						$deposti_id = $_POST['deposti_id$j'];

						$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
						$ce_id = $_POST['ce_id'];
						$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
						$time = date('h:i:s A', $time_now);
						$dtime = date("d-m-Y") . ", " . $time;
						$sqld = "insert into daily_withdraw (draw_date,dep_type,acc_id,cheque_no,cheque_amt,staff_id,draw_time,entry_by,entry_date,status) values ('" . $draw_date . "','" . $deposti_id . "'," . $acc_id . ",'" . $cheque_no . "','" . $cheque_amt . "','" . $ce_ids_upd2[0] . "','" . $draw_time . "','" . $user . "','" . $dtime . "','Y')";
						$qud = mysqli_query($writeConnection, $sqld);
						if ($d_ids == "") $d_ids = mysqli_insert_id();
						else $d_ids .= "," . mysqli_insert_id();
					}
				} else {
					$acc_id = $_POST["acc_id1"];
					$cheque_no = $_POST["cheque_no1"];
					$cheque_amt = $_POST["cheque_amount1"];
					$draw_time = $_POST["draw_time1"];
					$deposti_id = $_POST['deposti_id1'];
					$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
					$ce_id = $_POST['ce_id'];
					$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
					$time = date('h:i:s A', $time_now);
					$dtime = date("d-m-Y") . ", " . $time;
					$sqld = "insert into daily_withdraw (draw_date,dep_type,acc_id,cheque_no,cheque_amt,staff_id,draw_time,entry_by,entry_date,status) values ('" . $draw_date . "','" . $deposti_id . "','" . $acc_id . "','" . $cheque_no . "','" . $cheque_amt . "','" . $ce_ids_upd2[0] . "','" . $draw_time . "','" . $user . "','" . $dtime . "','Y')";
					$qud = mysqli_query($writeConnection, $sqld);
					$d_ids = mysqli_insert_id();
				}


				if (mysqli_query($writeConnection, $sql) == 1) {
					$update_date = $draw_date . ", " . $time;
					$sqlc = "select * from ce_cih where ce_id='" . $ce_id . "' and status='Y'";
					$quc = mysqli_query($writeConnection, $sqlc);
					$nc = mysqli_num_rows($quc);
					if ($nc == 1) {
						while ($rc = mysqli_fetch_array($quc)) {
							$prev_cih = $rc['cih_amount'];
						}
						$ce_cih = $prev_cih + $cheque_amt;

						$quc1 = mysqli_query($writeConnection, "update ce_cih set cih_amount='" . $ce_cih . "', last_update='" . date("Y-m-d") . "',update_by='" . $user . "',update_date='" . $update_date . "' where ce_id='" . $ce_id . "'");
					}
				}
			}

			$upd_date = '';
			$upd_date = date('Y-m-d H:i:s');

			if ($draw_status = '') {
				$upd_fields = ", delivery_dep_type, delivery_dep_accid ";
				$upd_vals = ",'" . $_POST['deposti_id1'] . "', '" . $_POST['acc_id1'] . "' ";
			}

			$sql = "insert into daily_delivery (trans_id , delivery_dep_type, delivery_dep_accid ,del_to,reqrec_date,reqrec_time,drec_no,req_amount,del_amount,diff_amount,2000s,1000s,500s,200s,100s,50s,20s,10s,5s,coins,o1000s,o500s,staff_id,del_remarks,other_remarks,draw_ids,delivery_time,rec_status,ref_no,visit1,visit2,visit3,cost_of_operation,del_date,del_time,del_dt1,user_name,status) values (" . $trans_id . " ,'" . $_POST['deposti_id1'] . "', '" . $_POST['acc_id1'] . "','" . $del_to . "','" . $reqrec_date . "','" . $reqrec_time . "','" . $drec_no . "','" . $req_amount . "','" . $del_amount . "','" . $diff_amount . "','" . $_2000s . "','" . $_1000s . "','" . $_500s . "','" . $_200s . "','" . $_100s . "','" . $_50s . "','" . $_20s . "','" . $_10s . "','" . $_5s . "','" . $_coins . "','" . $_o1000s . "','" . $_o500s . "','" . $ce_id . "','" . $del_remarks . "','" . $other_remarks . "','" . $d_ids . "','" . $delivery_time . "','" . $rec_status . "','" . $ref_no . "','" . $visit1 . "','" . $visit2 . "','" . $visit3 . "','" . $cost_of_operation . "','" . $del_date . "','" . $time . "', '" . $upd_date . "', '" . $user . "','Y')";

			if (mysqli_query($writeConnection, $sql) == 1) {
				// Update CIH Details
				$update_date = $coll_date . ", " . $time;
				$sqlc = "select * from ce_cih where ce_id='" . $ce_id . "' and status='Y'";
				$quc = mysqli_query($writeConnection, $sqlc);
				$nc = mysqli_num_rows($quc);

				if ($nc == 1) {
					while ($rc = mysqli_fetch_array($quc)) {
						$prev_cih = $rc['cih_amount'];
					}
					$ce_cih = $prev_cih - $del_amount;


					$quc1 = mysqli_query($writeConnection, "update ce_cih set cih_amount='" . $ce_cih . "', last_update='" . date("Y-m-d") . "',update_by='" . $user . "',update_date='" . $update_date . "' where ce_id='" . $ce_id . "'");
				} else {
					$ce_cih = 0 - $del_amount;


					$quc1 = mysqli_query($writeConnection, "insert into ce_cih (ce_id,cih_amount,last_update,update_by,update_date,status) values ('" . $ce_id . "'," . $ce_cih . ",'" . date("Y-m-d") . "','" . $user . "','" . $update_now . "','Y')");
				}


				//SMS Sent to Shop Owner
				//Check particular Client ID

				$cust_id1 = "";
				$sqlm = "select *, daily_trans.location as loc_name from daily_trans inner join shop_details on daily_trans.pickup_code=shop_details.shop_id where daily_trans.trans_id='" . $trans_id . "' and shop_details.cust_id='244' and daily_trans.status='Y' and shop_details.status='Y'";
				$qum = mysqli_query($readConnection, $sqlm);
				$nm = mysqli_num_rows($qum);


				$sqlc = "select * from radiant_ce where ce_id='" . $ce_id . "' and status='Y'";
				$quc = mysqli_query($readConnection, $sqlc);
				while ($rc = mysqli_fetch_array($quc)) {
					$ce_name = $rc['ce_name'];
				}

				if ($nm == 1) {
					while ($rm = mysqli_fetch_array($qum)) {
						$point_name = $rm['shop_name'];
						$point_code = $rm['shop_code'];
						$loc_name = $rm['loc_name'];
						$cust_id1 = $rm['cust_id'];
					}
					$contact_no = $_POST['contact_no'];
					//Multiple contact no
					$mobiles = explode(",", $contact_no);
					foreach ($mobiles as $mobile_no) {
						if ($cust_id1 == 244) {

							$SmsDeliveryTo = !empty($del_to) ? $del_to : '-';
							$SmsShopName = !empty($point_name) ? substr($point_name, 0, 28) : '-';
							$SmsPointCode = !empty($point_code) ? $point_code : '-';
							$SmsLocation = !empty($loc_name) ? $loc_name : '-';
							$SmsAmount = !empty($del_amount) ? $del_amount : '-';
							$SmsReceiptNo = !empty($drec_no) ? $drec_no : '-';
							$SmsCeName = !empty($ce_name) ? $ce_name : '-';
							$SmsDeliveryDate = !empty($del_date) ? $del_date : '-';
							$SmsTime = !empty($time) ? $time : '-';

							$smsmessage = 'Dear Customer, Cash Delivered to Mr. ' . trim($SmsDeliveryTo) . 'at Point Name: ' . trim($SmsShopName) . ' Point Code: ' . trim($SmsPointCode) . ' Location: ' . trim($SmsLocation) . ' Amount:' . trim($SmsAmount) . ' Receipt No: ' . trim($SmsReceiptNo) . 'by CE Name: ' . trim($SmsCeName) . ' on Date: ' . trim($SmsDeliveryDate) . ' Time: ' . trim($SmsTime) . ' - Radiant.';

							//$smsmessage="Dear Customer, Cash Delivered to Mr. ".$del_to." at Point Name: ".$point_name.", Point Code: ".$point_code.", Location: ".$loc_name.", Amount: ".$del_amount.", Receipt No: ".$drec_no." by CE Name: ".$ce_name." on Date: ".$del_date.", Time: ".$time." - Radiant.";

						}
						if ($mobile_no != "") $mclass->sendSmsToUser($smsmessage, $mobile_no, "");
					}
				}

				// mysqli_close($conn);
				header("Location:../?pid=$pid&act=delivery&nav=2_1&id=$trans_id");
			} else {
				// mysqli_close($conn);

				header("Location:../?pid=$pid&act=delivery&nav=2_2&id=$trans_id");
			}
		} elseif ($n1 == 1) {
			//Get Previous Collection Amount for CIH Amount Change (Corr_amt - Prev_amt)
			$sql3 = "select * from daily_delivery where trans_id='" . $trans_id . "' and del_id='" . $del_id . "' and status='Y'";

			$qu3 = mysqli_query($writeConnection, $sql3);
			while ($r3 = mysqli_fetch_array($qu3)) {
				$prev_amt = $r3['del_amount'];
				$draw_ids = $r3['draw_ids'];
			}
			$adj_amt = $del_amount - $prev_amt;

			$d_ids1 = explode(",", $draw_ids);
			foreach ($d_ids1 as $value) {
				$sqlp = "update daily_withdraw set status='N' where draw_id='" . $value . "'";
				$qup = mysqli_query($writeConnection, $sqlp);
			}

			$d_ids = "";
			if ($draw_status == "Yes") {
				//Insert Withdraw entries
				$t_draw = $_POST['t_draw'];
				$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
				$t_amount = 0;
				if ($t_draw > 1) {
					for ($j = 1; $j <= $t_draw; $j++) {
						$acc_id = $_POST["acc_id$j"];
						$cheque_no = $_POST["cheque_no$j"];
						$cheque_amt = $_POST["cheque_amount$j"];
						$draw_time = $_POST["draw_time$j"];
						$deposti_id = $_POST['deposti_id$j'];

						$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
						$ce_id = $_POST['ce_id'];
						$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
						$time = date('h:i:s A', $time_now);
						$dtime = date("d-m-Y") . ", " . $time;
						$sqld = "insert into daily_withdraw (draw_date,dep_type,acc_id,cheque_no,cheque_amt,staff_id,draw_time,entry_by,entry_date,status) values ('" . $draw_date . "', '" . $deposti_id . "', '" . $acc_id . "','" . $cheque_no . "','" . $cheque_amt . "','" . $ce_ids_upd2[0] . "','" . $draw_time . "','" . $user . "','" . $dtime . "','Y')";
						$qud = mysqli_query($writeConnection, $sqld);
						if ($d_ids == "") $d_ids = mysqli_insert_id();
						else $d_ids .= "," . mysqli_insert_id();
					}
				} else {
					print '<pre>';
					print_r($_POST);
					$acc_id = $_POST["acc_id1"];
					$cheque_no = $_POST["cheque_no1"];
					$cheque_amt = $_POST["cheque_amount1"];
					$draw_time = $_POST["draw_time1"];
					$deposti_id = $_POST['deposti_id1'];
					$draw_date = date("Y-m-d", strtotime($_POST['draw_date']));
					$ce_id = $_POST['ce_id'];
					$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
					$time = date('h:i:s A', $time_now);
					$dtime = date("d-m-Y") . ", " . $time;
					$sqld = "insert into daily_withdraw (draw_date,dep_type,acc_id,cheque_no,cheque_amt,staff_id,draw_time,entry_by,entry_date,status) values ('" . $draw_date . "', '" . $deposti_id . "', '" . $acc_id . "','" . $cheque_no . "','" . $cheque_amt . "','" . $ce_ids_upd2[0] . "','" . $draw_time . "','" . $user . "','" . $dtime . "','Y')";
					$qud = mysqli_query($writeConnection, $sqld);
					$d_ids = mysqli_insert_id();
				}
			}

			$upd_date = '';
			$upd_date = date('Y-m-d H:i:s');

			$sql = "update daily_delivery set del_to='" . $del_to . "',reqrec_date='" . $reqrec_date . "', delivery_dep_type='" . $_POST['deposti_id1'] . "', delivery_dep_accid='" . $_POST['acc_id1'] . "', reqrec_time='" . $reqrec_time . "',drec_no='" . $drec_no . "',del_amount='" . $del_amount . "',diff_amount='" . $diff_amount . "',2000s='" . $_2000s . "',1000s='" . $_1000s . "',500s='" . $_500s . "',200s='" . $_200s . "',100s='" . $_100s . "',50s='" . $_50s . "',20s='" . $_20s . "',10s='" . $_10s . "',5s='" . $_5s . "',coins='" . $_coins . "',o1000s='" . $_o1000s . "',o500s='" . $_o500s . "',staff_id='" . $ce_id . "',del_remarks='" . $del_remarks . "',other_remarks='" . $other_remarks . "',draw_ids='" . $d_ids . "',delivery_time='" . $delivery_time . "',rec_status='" . $rec_status . "',ref_no='" . $ref_no . "',visit1='" . $visit1 . "',visit2='" . $visit2 . "',visit3='" . $visit3 . "',cost_of_operation='" . $cost_of_operation . "',del_date='" . $del_date . "',del_time='" . $time . "', del_dt1='" . $upd_date . "', user_name='" . $user . "' where trans_id='" . $trans_id . "' and del_id='" . $del_id . "' and status='Y'";

			if (mysqli_query($writeConnection, $sql) == 1) {

				// Update CIH Details
				$update_date = $coll_date . ", " . $time;
				$sqlc = "select * from ce_cih where ce_id='" . $ce_id . "' and status='Y'";
				$quc = mysqli_query($writeConnection, $sqlc);
				$nc = mysqli_num_rows($quc);

				if ($nc == 1) {
					while ($rc = mysqli_fetch_array($quc)) {
						$prev_cih = $rc['cih_amount'];
					}
					$ce_cih = $prev_cih - $adj_amt;

					$quc1 = mysqli_query($writeConnection, "update ce_cih set cih_amount='" . $ce_cih . "', last_update='" . date("Y-m-d") . "',update_by='" . $user . "',update_date='" . $update_date . "' where ce_id='" . $ce_id . "'");
				} else {
					$ce_cih = $del_amount;
					$quc1 = mysqli_query($writeConnection, "insert into ce_cih (ce_id,cih_amount,last_update,update_by,update_date,status) values ('" . $ce_id . "','" . $ce_cih . "','" . date("Y-m-d") . "','" . $user . "','" . $update_now . "','Y')");
				}


				//SMS Sent to Shop Owner
				//Check particular Client ID

				$cust_id1 = "";
				$sqlm = "select *, daily_trans.location as loc_name from daily_trans inner join shop_details on daily_trans.pickup_code=shop_details.shop_id where daily_trans.trans_id='" . $trans_id . "' and shop_details.cust_id='244' and daily_trans.status='Y' and shop_details.status='Y'";
				$qum = mysqli_query($readConnection, $sqlm);
				$nm = mysqli_num_rows($qum);


				$sqlc = "select * from radiant_ce where ce_id='" . $ce_id . "' and status='Y'";
				$quc = mysqli_query($readConnection, $sqlc);
				while ($rc = mysqli_fetch_array($quc)) {
					$ce_name = $rc['ce_name'];
				}

				if ($nm == 1) {
					while ($rm = mysqli_fetch_array($qum)) {
						$point_name = $rm['shop_name'];
						$point_code = $rm['shop_code'];
						$loc_name = $rm['loc_name'];
						$cust_id1 = $rm['cust_id'];
					}
					$contact_no = $_POST['contact_no'];
					//Multiple contact no
					$mobiles = explode(",", $contact_no);
					foreach ($mobiles as $mobile_no) {
						if ($cust_id1 == 244) {

							$SmsDeliveryTo = !empty($del_to) ? $del_to : '-';
							$SmsShopName = !empty($point_name) ? substr($point_name, 0, 28) : '-';
							$SmsPointCode = !empty($point_code) ? $point_code : '-';
							$SmsLocation = !empty($loc_name) ? $loc_name : '-';
							$SmsAmount = !empty($del_amount) ? $del_amount : '-';
							$SmsReceiptNo = !empty($drec_no) ? $drec_no : '-';
							$SmsCeName = !empty($ce_name) ? $ce_name : '-';
							$SmsDeliveryDate = !empty($del_date) ? $del_date : '-';
							$SmsTime = !empty($time) ? $time : '-';

							$smsmessage = 'Dear Customer, Cash Delivered to Mr. ' . trim($SmsDeliveryTo) . 'at Point Name: ' . trim($SmsShopName) . ' Point Code: ' . trim($SmsPointCode) . ' Location: ' . trim($SmsLocation) . ' Amount:' . trim($SmsAmount) . ' Receipt No: ' . trim($SmsReceiptNo) . 'by CE Name: ' . trim($SmsCeName) . ' on Date: ' . trim($SmsDeliveryDate) . ' Time: ' . trim($SmsTime) . ' - Radiant.';


							//$smsmessage="Dear Customer, Cash Delivered to Mr. ".$del_to." at Point Name: ".$point_name.", Point Code: ".$point_code.", Location: ".$loc_name.", Amount: ".$del_amount.", Receipt No: ".$drec_no." by CE Name: ".$ce_name." on Date: ".$del_date.", Time: ".$time." - Radiant.";

						}
						if ($mobile_no != "") $mclass->sendSmsToUser($smsmessage, $mobile_no, "");
					}
				}

				// mysqli_close($conn);
				header("Location:../?pid=$pid&act=delivery&nav=2_3&id=$trans_id");
			} else {
				// mysqli_close($conn);
				header("Location:../?pid=$pid&act=delivery&nav=2_4&id=$trans_id");
			}
		}
	} elseif ($pid == "chqcollect") {
		$dep_date = date("Y-m-d");

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('d-m-Y h:i:s A', $time_now);

		$trans_id = $_POST['trans_id'];
		$t_rec = $_POST['to_rec'];

		$sql11 = "select daily_trans.pickup_code from daily_trans inner join shop_details on daily_trans.pickup_code=shop_details.shop_id inner join cust_details on shop_details.cust_id=cust_details.cust_id where daily_trans.trans_id='" . $trans_id . "' ";
		$qu11 = mysqli_query($writeConnection, $sql11);
		$r11 = mysqli_fetch_assoc($qu11);

		$shop_ids = $r11['pickup_code'];

		$ce_ids_upd = $_POST['ce_ids_upd'];
		if ($ce_ids_upd != '' && $shop_ids != '') {
			$ce_ids_upd1 = explode('^', $ce_ids_upd);

			$sql_trans_upd = mysqli_query($writeConnection, "UPDATE daily_trans SET staff_id='" . $ce_ids_upd1[0] . "', mobile1='" . $ce_ids_upd1[1] . "' WHERE trans_id='" . $trans_id . "'");
			$sql_ups = mysqli_query($writeConnection, "SELECT * FROM shop_cemap WHERE shop_id='" . $shop_ids . "' AND status='Y'");
			if (mysqli_num_rows($sql_ups) > 0) {
				$sql_trans_upd = mysqli_query($writeConnection, "UPDATE shop_cemap SET pri_ce='" . $ce_ids_upd1[0] . "' WHERE shop_id='" . $shop_ids . "'");
			} else {
				$sql_trans_upd = mysqli_query($writeConnection, "INSERT INTO shop_cemap (`shop_id`, `pri_ce`, `update_by`, `update_date`, `status`) VALUES('" . $shop_ids . "', '" . $ce_ids_upd1[0] . "', '" . $user . "', '" . date('Y-m-d') . "', 'Y')");
			}
		}

		for ($j = 1; $j <= $t_rec; $j++) {
			$sql_cq = mysqli_query($writeConnection, "SELECT trans_id FROM daily_chequepick WHERE trans_id='$trans_id' AND t_rec='$j' AND status='Y'");
			$row_cq = mysqli_num_rows($sql_cq);
			if ($row_cq > 0) {

				$sql_cq = "UPDATE `daily_chequepick` SET `client_code`='" . $_POST['client_code_' . $j] . "', `pickup_point_code`='" . $_POST['pickup_point_code_' . $j] . "', `no_cheque`='" . $_POST['no_cheque_' . $j] . "', `cheque_no`='" . $_POST['cheque_no_' . $j] . "', `rec_no`='" . $_POST['rec_no_' . $j] . "', `hcl_slip_no`='" . $_POST['hcl_slip_no_' . $j] . "', `cheque_amt`='" . $_POST['cheque_amt_' . $j] . "', `deposit_bank`='" . $_POST['deposit_bank_' . $j] . "', `account_no`='" . $_POST['account_no_' . $j] . "', `send_time`='" . $_POST['send_time_' . $j] . "', `destination`='" . $_POST['destination_' . $j] . "', `distance_ctobank`='" . $_POST['distance_ctobank_' . $j] . "', `charges`='" . $_POST['charges_' . $j] . "', `name`='" . $_POST['name_' . $j] . "', `pod_no`='" . $_POST['pod_no_' . $j] . "', `courier_status`='" . $_POST['courier_status_' . $j] . "', `scan_copy`='" . $_POST['scan_copy_' . $j] . "', `remark`='" . $_POST['remark_' . $j] . "', `updated_by`='" . $user . "', `update_date`='" . $time . "' WHERE  trans_id='" . $trans_id . "' AND t_rec='$j' AND status='Y'";
			} else {
				$sql_cq = "INSERT INTO `daily_chequepick` (`trans_id`, `client_code`, `pickup_point_code`, `t_rec`, `no_cheque`, `cheque_no`, `rec_no`, `hcl_slip_no`, `cheque_amt`, `deposit_bank`, `account_no`, `send_time`, `destination`, `distance_ctobank`, `charges`, `name`, `pod_no`, `courier_status`, `scan_copy`, `remark`, `updated_by`, `update_date`, `status`) VALUES
		('" . $_POST['trans_id'] . "', '" . $_POST['client_code_' . $j] . "', '" . $_POST['pickup_point_code_' . $j] . "',  '" . $j . "', '" . $_POST['no_cheque_' . $j] . "', '" . $_POST['cheque_no_' . $j] . "', '" . $_POST['rec_no_' . $j] . "', '" . $_POST['hcl_slip_no_' . $j] . "', '" . $_POST['cheque_amt_' . $j] . "', '" . $_POST['deposit_bank_' . $j] . "', '" . $_POST['account_no_' . $j] . "', '" . $_POST['send_time_' . $j] . "', '" . $_POST['destination_' . $j] . "', '" . $_POST['distance_ctobank_' . $j] . "', '" . $_POST['charges_' . $j] . "', '" . $_POST['name_' . $j] . "', '" . $_POST['pod_no_' . $j] . "', '" . $_POST['courier_status_' . $j] . "', '" . $_POST['scan_copy_' . $j] . "', '" . $_POST['remark_' . $j] . "', '" . $user . "','" . $time . "','Y')";
			}

			$query_cq = mysqli_query($writeConnection, $sql_cq);
		}
		if ($query_cq == 1) {
			if ($coll_id != '') {
				// // mysqli_close($conn);
				header("Location:../?pid=$pid&nav=2_3&id=$trans_id");
			} else {
				// // mysqli_close($conn);
				header("Location:../?pid=$pid&nav=2_1&id=$trans_id");
			}
		} else {
			// // mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_2&id=$trans_id");
		}
	} else if ($pid == 'rad_zone') {
		$region_name = $_POST['region_name'];
		$state = implode(',', $_POST['state']);
		$id = $_POST['id'];
		if ($id != '') {
			$sql = "UPDATE zone_master SET zone_name='" . mysqli_real_escape_string($writeConnection, $region_name) . "',states='" . mysqli_real_escape_string($writeConnection, $state) . "' WHERE zone_id='" . $id . "' AND status='Y'";
			$sql2 = mysqli_query($writeConnection, $sql);
		} else {
			$sql_region1 = mysqli_query($readConnection, "SELECT * FROM zone_master WHERE zone_name='" . mysqli_real_escape_string($readConnection, $region_name) . "' AND status='Y'");
			if (mysqli_num_rows($sql_region1) == 0) {
				$sql = "INSERT INTO zone_master (zone_name,states,status) values ('" . mysqli_real_escape_string($writeConnection, $region_name) . "','" . mysqli_real_escape_string($writeConnection, $state) . "','Y')";
				$sql2 = mysqli_query($writeConnection, $sql);
			} else {
				$sql2 = 1;
			}
		}

		if ($sql2 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}
		//// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'dep_slip') {

		$up_date = date('Y-m-d h:i:s');
		$client = explode('&^', mysqli_real_escape_string($readConnection, $_POST['client']));
		$region_name = explode('-', mysqli_real_escape_string($readConnection, $_POST['region_name']));
		$location = mysqli_real_escape_string($readConnection, $_POST['location']);
		$dep_type = mysqli_real_escape_string($readConnection, $_POST['dep_type']);
		$acc_id = mysqli_real_escape_string($readConnection, $_POST['acc_id']);
		$dep_branch = mysqli_real_escape_string($readConnection, $_POST['dep_branch']);
		$trans_date = !empty($_POST['trans_date']) ? date('Y-m-d', strtotime($_POST['trans_date'])) : "";
		$dep_amount = mysqli_real_escape_string($readConnection, $_POST['dep_amount']);
		$dep_typess = array('Burial' => 'B', 'Client Bank' => 'CB', 'Partner Bank' => 'PB');
		$folder_name = 'deposit';
		$date_folder = date('Y_m_d');
		if (!is_dir($folder_name . '/' . $date_folder)) {
			mkdir($folder_name . '/' . $date_folder, 0777, true);
			chmod($folder_name . '/' . $date_folder, 0777);
		}
		if (!is_dir($folder_name . '/' . $date_folder . '/CB')) {
			mkdir($folder_name . '/' . $date_folder . '/CB', 0777, true);
			chmod($folder_name . '/' . $date_folder . '/CB', 0777);
		}
		if (!is_dir($folder_name . '/' . $date_folder . '/PB')) {
			mkdir($folder_name . '/' . $date_folder . '/PB', 0777, true);
			chmod($folder_name . '/' . $date_folder . '/PB', 0777);
		}
		if (!is_dir($folder_name . '/' . $date_folder . '/B')) {
			mkdir($folder_name . '/' . $date_folder . '/B', 0777, true);
			chmod($folder_name . '/' . $date_folder . '/B', 0777);
		}


		$file = explode('.', basename($_FILES["uploadfile"]["name"]));
		$flie_name = $client[0] . '_' . $dep_typess[$dep_type] . '_' . date('d_m_Y_h_i_s') . '.png';

		$target_file = $folder_name . '/' . $date_folder . '/' . $dep_typess[$dep_type] . '/' . $flie_name;
		move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target_file);
		if ($id != '') {
			echo $sq_dep = "update deposit_slip set client_id='" . $client[1] . "',region_name='" . $region_name[1] . "',location_id='" . $location . "',dep_type='" . $dep_type . "', acc_id='" . $acc_id . "',dep_branch='" . $dep_branch . "',dep_date= '" . $trans_date . "',dep_amount='" . $dep_amount . "',file_name='" . $target_file . "',up_device='Web',update_by='" . $user . "',update_date='" . $up_date . "',status='Y' where id='" . $id . "'";
			die;
			$que = mysqli_query($writeConnection, $sql_dep);
		} else {
			$sql_dep = "INSERT INTO `deposit_slip` (`client_id`, `region_name`, `location_id`, `dep_type`, `acc_id`, `dep_branch`, `dep_date`, `dep_amount`, `file_name`, `up_device`, `update_by`, `update_date`,`status`) VALUES ('" . $client[1] . "', '" . $region_name[1] . "', '" . $location . "', '" . $dep_type . "', '" . $acc_id . "', '" . $dep_branch . "', '" . $trans_date . "', '" . $dep_amount . "', '" . $target_file . "', 'Web', '" . $user . "', '" . $up_date . "', 'Y')";
		}
		$que = mysqli_query($writeConnection, $sql_dep);
		if ($que) {
			// mysqli_close($conn);
			header('Location:index.php?pid=' . $pid . '&nav=2_1');
		} else {
			// mysqli_close($conn);
			header('Location:index.php?pid=' . $pid . '&nav=2_2');
		}
	} else if ($pid == 'move_file') {
		$file_name = $_REQUEST['file_name'];
		$id = $_REQUEST['id'];
		$name = explode("/", $file_name);

		$destination = "deposit/delete/";
		if (copy($file_name, $destination . $name[3])) {
			header('Location:../?pid=dep_slip&nav=2_2');
		} else {
			header('Location:../?pid=dep_slip&nav=2_2');
		}
		unlink($file_name);
		$sql = mysqli_query($writeConnection, "update deposit_slip set status='N' where id='" . $id . "'");
	} else if ($pid == 'checked') {

		$up_date = date('Y-m-d h:i:s');
		$sql = "update deposit_slip set checked_by='" . $user . "',checked_date='" . $up_date . "' where id='" . $id . "' and status='Y' ";
		$que = mysqli_query($writeConnection, $sql);
		if ($que) {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_1');
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_2');
		}
	} else if ($pid == 'dep_ceapproval') {
		$id = $_REQUEST['id'];
		$up_date = date('Y-m-d h:i:s');
		$sql = mysqli_query($writeConnection, "update ce_pinnos set status='Y', approved_by='" . $user . "',approved_date='" . $up_date . "' where s_id='" . $id . "'");
		if ($sql) {

			header('Location:../?pid=' . $pid . '&nav=2_1');
		} else {

			header('Location:../?pid=' . $pid . '&nav=2_2');
		}
	} else if ($pid == 'rad_reg') {
		$id = $_POST['id'];
		$zone_id = $_POST['zone_id'];
		$zone = mysqli_real_escape_string($writeConnection, $_POST['zone']);

		if ($_POST['state'] != '') {
			$state = implode(',', $_POST['state']);
		}

		$name = mysqli_real_escape_string($writeConnection, $_POST['name']);
		$phone = mysqli_real_escape_string($writeConnection, $_POST['phone']);
		$fax = mysqli_real_escape_string($writeConnection, $_POST['fax']);
		$head_name = mysqli_real_escape_string($writeConnection, $_POST['head_name']);
		$design = mysqli_real_escape_string($writeConnection, $_POST['design']);
		$address = mysqli_real_escape_string($writeConnection, $_POST['address']);
		$mobile = mysqli_real_escape_string($writeConnection, $_POST['mobile']);
		$email = mysqli_real_escape_string($writeConnection, $_POST['email']);
		$oam_id = mysqli_real_escape_string($writeConnection, $_POST['oam_id']);
		$dme1_id = mysqli_real_escape_string($writeConnection, $_POST['dme1_id']);
		$dme2_id = mysqli_real_escape_string($writeConnection, $_POST['dme2_id']);
		$remarks = mysqli_real_escape_string($writeConnection, $_POST['remarks']);
		if ($id != '') {
			$sql1 = "UPDATE `region_master` SET `zone_id`=" . $zone_id . ", `region_name`='" . $name . "', `states`='" . $state . "', `address`='" . $address . "', `phone`='" . $phone . "', `fax`='" . $fax . "', `head_name`='" . $head_name . "', `design`='" . $design . "', `mobile`='" . $mobile . "', `email`='" . $email . "', `oam_id`='" . $oam_id . "', `dme1_id`='" . $dme1_id . "', `dme2_id`='" . $dme2_id . "', `remarks`='" . $remarks . "', `updated_by`='" . $user_name . "', `update_date`='" . $update_date . "' WHERE `region_id`='" . $id . "'";
			$sql2 = mysqli_query($writeConnection, $sql1);
		} else {
			$sql_rg_mas = mysqli_query($readConnection, "SELECT * FROM region_master WHERE `region_name`='" . $name . "' AND status='Y'");
			if (mysqli_num_rows($sql_rg_mas) == 0) {
				$sql1 = "INSERT INTO `region_master` (`zone_id`, `region_name`, `states`, `address`, `phone`, `fax`, `head_name`,`head_sign`, `design`, `mobile`, `email`, `oam_id`, `dme1_id`, `dme2_id`, `remarks`, `updated_by`, `update_date`, `status`) VALUES (" . $zone_id . ",'" . $name . "','" . $state . "','" . $address . "','" . $phone . "','" . $fax . "','" . $head_name . "','','" . $design . "','" . $mobile . "','" . $email . "','" . $oam_id . "','" . $dme1_id . "','" . $dme2_id . "','" . $remarks . "', '" . $user_name . "', '" . $update_date . "','Y')";
				$sql2 = mysqli_query($writeConnection, $sql1);
			} else {
				$sql2 = 1;
			}
		}


		if ($sql2 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}

		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	}
	//Radiant Cluster
	else if ($pid == 'clust') {
		$id = $_POST['id'];
		$region_name = explode('|', $_POST['region_name']);
		$cluster = mysqli_real_escape_string($writeConnection, $_POST['cluster']);
		$staff_id = $_POST['staff_id'];
		$upd_dates = date('Y-m-d H:i:s');
		if ($id != '') {
			$sqls = "UPDATE `master_cluster` SET `region_name`='" . $region_name[1] . "', `cluster`='" . $cluster . "', `staff_id`='" . $staff_id . "' WHERE id='" . $id . "'";
		} else {
			$sqls = "INSERT INTO `master_cluster` (`region_name`, `cluster`, `staff_id`, `created_by`, `created_date`, `status`) VALUES ('" . $region_name[1] . "', '" . $cluster . "', '" . $staff_id . "', '" . $user . "', '" . $upd_dates . "', 'Y')";
		}

		if (mysqli_query($writeConnection, $sqls) == 1) {
			if ($id != '') {
				$nav = 3;
			} else {
				$nav = 1;
			}
		} else {
			$nav = 2;
		}

		header("Location:../?pid=$pid&nav=" . $nav);
	}
	//CIH Location
	else if ($pid == 'rad_cih') {
		$id = $_POST['id'];
		$region_name = explode('|', $_POST['region_name']);
		$cluster = $_POST['cluster'];
		$cih_location = mysqli_real_escape_string($writeConnection, $_POST['cih_location']);
		$ce_id = $_POST['ce_id'];
		$upd_dates = date('Y-m-d H:i:s');
		if ($id != '') {
			$sqls = "UPDATE `master_cih_location` SET `branch_name`='" . $region_name[1] . "', `cluster`='" . $cluster . "', `cih_location`='" . $cih_location . "', `ce_id`='" . $ce_id . "' WHERE id='" . $id . "'";
		} else {
			$sqls = "INSERT INTO `master_cih_location` (`branch_name`, `cluster`, `cih_location`, `ce_id`, `created_by`, `created_date`, `status`) VALUES ( '" . $region_name[1] . "', '" . $cluster . "', '" . $cih_location . "', '" . $ce_id . "', '" . $user . "',  '" . $upd_dates . "', 'Y')";
		}

		if (mysqli_query($writeConnection, $sqls) == 1) {
			if ($id != '') {
				$nav = 3;
			} else {
				$nav = 1;
			}
		} else {
			$nav = 2;
		}
		//// mysqli_close($conn);
		header("Location:../?pid=$pid&nav=" . $nav);
	}
	//Cutoff Time
	else if ($pid == "cutof_time") {

		$id = $_POST['id'];
		$client_id = $_POST['client'];

		$ser_type = $_POST['ser_type'];
		$rep_name = $_POST['rep_name'];
		$live_status = $_POST['live_status'];
		$pre_by = $_POST['pre_by'];
		$chek_by = $_POST['chek_by'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$remarks = $_POST['remark'];
		$entry_date = date("Y-m-d");

		if ($id == '') {

			$sql1 = "INSERT INTO report_cutofftime (client_id,service_type,report_name,live_status,prepare_by,checked_by,start_time,last_time,remark,created_by,created_date,status) VALUES (" . $client_id . ",'" . $ser_type . "','" . $rep_name . "','" . $live_status . "','" . $pre_by . "','" . $chek_by . "','" . $start_time . "','" . $end_time . "','" . $remarks . "','" . $user_name . "','" . $entry_date . "','Y')";
			if (mysqli_query($sql1) == TRUE) {
				// mysqli_close($conn);	
				header('Location:index.php?pid=' . $pid . '&nav=2_1');
			} else {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_2');
			}
		} else {
			$sql1 = "update  report_cutofftime set client_id=" . $client_id . ",service_type='" . $ser_type . "',report_name='" . $rep_name . "',live_status='" . $live_status . "',prepare_by='" . $pre_by . "',checked_by='" . $chek_by . "',start_time='" . $start_time . "',last_time='" . $end_time . "',remark='" . $remarks . "',created_by='" . $user_name . "',created_date='" . $entry_date . "',status='Y' where id='" . $id . "'";
			if (mysqli_query($sql1) == TRUE) {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_3');
			} else {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_2');
			}
		}
	}





	//Radiant Location
	elseif ($pid == "rad_loc") {

		$id = $_POST['id'];
		$location = $_POST['location'];
		$region = mysqli_real_escape_string($readConnection, $_POST['region']);
		if ($id != '') {
			$sqll = "select state from location_master where loc_id='" . $location . "' ";
			$qul = mysqli_query($readConnection, $sqll);
			while ($rl = mysqli_fetch_array($qul)) {
				$state = $rl['state'];
			}

			if(is_numeric($location)){
				$sql = "update radiant_location set location_id=" . $location . ",region_id=" . $region . ",state_name='" . $state . "' where radloc_id='" . $id . "' and status='Y'";

				if (mysqli_query($writeConnection, $sql) == TRUE) {
					header("Location:../?pid=$pid&nav=2_3");
				} else {
					header("Location:../?pid=$pid&nav=2_2");
				}
			}
			else{
				header("Location:../?pid=$pid&nav=2_2");
			}
			
		} else {

			$sqll = "select state from location_master where loc_id='" . $location . "' ";
			$qul = mysqli_query($readConnection, $sqll);
			while ($rl = mysqli_fetch_array($qul)) {
				$state = $rl['state'];
			}

			$sql2 = "select region_name from radiant_location inner join region_master on radiant_location.region_id=region_master.region_id where radiant_location.location_id='" . $location . "' and radiant_location.status='Y' and region_master.status='Y'";
			$qu2 = mysqli_query($readConnection, $sql2);
			$n2 = mysqli_num_rows($qu2);
			while ($r2 = mysqli_fetch_array($qu2)) {
				$reg = $r2['region_name'];
			}
			if ($n2 == 0) {
				$entry_date = date("Y-m-d");

				if (is_numeric($location)) {
					$sql = "insert into radiant_location (location_id,region_id,state_name,entry_date,status) values (" . $location . "," . $region . ",'" . $state . "','" . $entry_date . "','Y')";
					echo $sql;
					if (mysqli_query($writeConnection, $sql) == TRUE) {
						header("Location:../?pid=$pid&nav=2_1");
					} else {
						header("Location:../?pid=$pid&nav=2_2");
					}
				} else {
					header("Location:../?pid=$pid&nav=2_2");
				}
			} else {
				header("Location:../?pid=$pid&nav=2_7");
			}
		}
	}


	//Bank Details
	else if ($pid == 'm_bank') {
		$id = $_POST['id'];
		$bank_name = mysqli_real_escape_string($writeConnection, $_POST['bank_name']);
		$branch_name = mysqli_real_escape_string($writeConnection, $_POST['branch_name']);
		$locationc = mysqli_real_escape_string($writeConnection, $_POST['bank_location']);
		$account_no = mysqli_real_escape_string($writeConnection, $_POST['account_no']);
		$ifsc_code = mysqli_real_escape_string($writeConnection, $_POST['ifsc_code']);
		$acc_open_date = date('Y-m-d', strtotime($_POST['acc_open_date']));
		$acc_close_date = date('Y-m-d', strtotime($_POST['acc_close_date']));
		$acc_status = mysqli_real_escape_string($writeConnection, $_POST['acc_status']);
		$trans_type = mysqli_real_escape_string($writeConnection, $_POST['trans_type']);
		$acc_type = mysqli_real_escape_string($writeConnection, $_POST['acc_type']);
		$address = mysqli_real_escape_string($writeConnection, $_POST['address']);
		$contact_number = mysqli_real_escape_string($writeConnection, $_POST['contact_number']);
		$alternate_number = mysqli_real_escape_string($writeConnection, $_POST['alternate_number']);
		$branch_code = mysqli_real_escape_string($writeConnection, $_POST['branch_code']);
		$bank_type = mysqli_real_escape_string($writeConnection, $_POST['bank_type']);
		$remarks = mysqli_real_escape_string($writeConnection, $_POST['remarks']);
		if ($id != '') {
			$query_loca = "UPDATE `bank_master` SET `bank_name`='$bank_name', `branch_name`='$branch_name', `account_no`='$account_no', `ifsc_code`='$ifsc_code', `acc_open_date`='$acc_open_date', `acc_close_date`='$acc_close_date', `acc_status`='$acc_status', `trans_type`='$trans_type', `branch_code`='$branch_code', `bank_type`='$bank_type', `acc_type`='$acc_type',  `address`='$address', `contact_number`='$contact_number', `alternate_number`='$alternate_number', `location`='$locationc', `remarks`='$remarks'  WHERE acc_id='$id'";
		} else {
			$query_loca = "INSERT INTO `bank_master` (`bank_name`,`client_name`, `branch_name`, `account_no`, `ifsc_code`, `acc_open_date`, `acc_close_date`, `acc_status`, `trans_type`, `branch_code`, `bank_type`, `acc_type`, `address`, `contact_number`, `alternate_number`,  `location`, `remarks`, `status`) VALUES ('$bank_name',0,'$branch_name', '$account_no', '$ifsc_code', '$acc_open_date', '$acc_close_date', '$acc_status', '$trans_type', '$branch_code', '$bank_type', '$acc_type', '$address', '$contact_number', '$alternate_number', '$locationc', '$remarks', 'Y')";
		}

		$sql_loca = mysqli_query($writeConnection, $query_loca);
		if ($sql_loca == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}

		//// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'email_setting') {

		$id = $_POST['id'];
		$email_type = $_POST['email_type'];
		$email_id = $_POST['email_id'];
		$peroide = $_POST['peroide'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		$remarks = $_POST['remarks'];
		$update = date("Y-m-d");

		if ($id == '') {
			//Insert
			$query = "insert into email_setting(email_type,email_id,peroide,subject,content,remarks ,created_by,created_date,status) values('" . $email_type . "','" . $email_id . "','" . $peroide . "','" . $subject . "','" . $content . "','" . $remarks . "','" . $user_name . "','" . $update . "','Y')";

			$exc = mysqli_query($writeConnection, $query);

			if ($exc) {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_1_1');
			} else {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_2_1');
			}
		} else {

			$query = "update email_setting set email_type='" . $email_type . "',email_id='" . $email_id . "',peroide='" . $peroide . "',subject='" . $subject . "',content='" . $content . "',remarks='" . $remarks . "',created_by='" . $user_name . "',created_date='" . $update . "',status='Y' where id='" . $id . "' and status='Y'  ";
			$exc = mysqli_query($writeConnection, $query);
			if ($exc) {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_3_1');
			} else {
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_3_1');
			}
		}
	} else if ($pid == 'm_user') {
		$id = $_POST['id'];
		$staff_id = $_POST['staff_id'];
		$user_name = mysqli_real_escape_string($writeConnection, $_POST['user_name']);
		$password = mysqli_real_escape_string($writeConnection, $_POST['password']);
		$permission = mysqli_real_escape_string($writeConnection, $_POST['permission']);
		$status = mysqli_real_escape_string($writeConnection, $_POST['status']);

		if ($_POST['other_permissions'] != '') {
			$other_permissions = implode(',', $_POST['other_permissions']);
		}

		//echo $other_permissions; exit;

		if ($_POST['region'] != '') {
			$region = implode(',', $_POST['region']);
		}

		if ($_POST['page'] != '') {
			$page = 'main,dash,' . implode(',', $_POST['page']);
		}


		if ($_POST['client_id'] != '') {
			$client_id = implode(',', $_POST['client_id']);
		}
		if($_POST['vendor_id']!=''){
			$vendor_id = implode(',', $_POST['vendor_id']);
		}
		
		if ($_POST['pgroup_name'] != '') {
			$pgroup_name = implode(',', $_POST['pgroup_name']);
		}
		if ($_POST['dgroup_name'] != '') {
			$dgroup_name = implode(',', $_POST['dgroup_name']);
		}
		$current_time  = date('Y-m-d H:i:s');

		if ($_POST['page'] != '') {
			if (in_array('mob_pickup', $_POST['page'])) {
				$page .= ',mf_trans';
			}
			if (in_array('amends_rep', $_POST['page'])) {
				$page .= ',amdrep';
			}
			if (in_array('gmisf', $_POST['page'])) {
				$page .= ',fundrep';
			}
			if (in_array('host', $_POST['page'])) {
				$page .= ',securefile';
			}
		}



		if ($id != '') {

			$sql_login = mysqli_query($writeConnection, "UPDATE `login` SET `emp_id`='$staff_id', `password`='$password', `other_permissions`='$other_permissions', `region`='$region', `status`='$status', `per`='$permission', `client_id`='$client_id',`vendor_id`='$vendor_id', `pgroup_name`='$pgroup_name', `dgroup_name`='$dgroup_name', `updated_by`='" . $user . "', `updated_date`='$current_time' WHERE `user_name`='$user_name'");
			$sql_auth = mysqli_query($writeConnection, "UPDATE `auth_t` SET `pid`='$page' WHERE `user_name`='$user_name'");
		} else {

			$sql_entry_check = "select user_name from login where user_name='" . $user_name . "'";
			//echo $sql_entry_check; exit;
			$sql_login_check = mysqli_query($readConnection, $sql_entry_check);
			if (mysqli_num_rows($sql_login_check) > 0) {
				$sql_login == FALSE;
			} else {

				$sql_insert_log = "INSERT INTO `login` (`other_permissions`,`logout_status`,`user_name`, `password`, `emp_id`, `region`, `status`, `per`, `client_id`,`vendor_id`, `pgroup_name`, `dgroup_name`,`login_from`,`login_to`,`isms_per`,`update_status`,`created_by`, `created_date`,`updated_by`,`updated_date`,`deleted_by`,`deleted_date`,`password_changed_at`) VALUES ('$other_permissions',0,'$user_name', '$password', '$staff_id', '$region', '$status', '$permission', '$client_id','$vendor_id','$pgroup_name', '$dgroup_name','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,'" . $user . "','$current_time','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00 00:00:00')";
				//echo $sql_insert_log; exit;
				$sql_login = mysqli_query($writeConnection, $sql_insert_log);
				$sql_auth = mysqli_query($writeConnection, "INSERT INTO `auth_t` (`user_name`,`mid`, `pid`, `status`) VALUES ('$user_name','','$page', 'Y')");
			}


			//echo $sql_login; exit;

		}
		if ($sql_login == TRUE) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}


		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'm_pins') {
		$app_type = $_POST['app_type'];
		$emp_id = $_POST['emp_id'];
		$pin_no = mysqli_real_escape_string($writeConnection, $_POST['pin_no']);
		$up = $_REQUEST['up'];

		$currentTimestamp = time();
		$time_now = strtotime('+5 hours +30 minutes', $currentTimestamp);
		//$time_now=mktime(date('h')+5,date('i')+30,date('s')); 

		$time = date('h:i:s A', $time_now);
		$time1 = date("d-m-Y") . ", " . $time;
		$sql_ce = mysqli_query($readConnection, "SELECT ce_id FROM radiant_ce WHERE ce_id='" . $emp_id . "' AND status='Y'");
		if (mysqli_num_rows($sql_ce) > 0) {
			if ($app_type == "hd")
				$sql11 = mysqli_query($writeConnection, "INSERT INTO hd_login (`ce_id`, `pin_no`, `update_by`, `update_time`, `status`) VALUES ('" . $emp_id . "', '" . $pin_no . "', '" . $user . "', '" . $time1 . "', 'Y')");
			elseif ($app_type == "mf")
				$sql11 = mysqli_query($writeConnection, "INSERT INTO mf_login (`ce_id`, `pin_no`,`imie_no`,`sim_no`,`otp_no`,`device_id`,`deviceid_otp`,`update_by`, `update_time`, `status`) VALUES ('" . $emp_id . "', '" . $pin_no . "','','','','','','" . $user . "', '" . $time1 . "', 'Y')");
			elseif ($app_type == "dc")
				$sql11 = mysqli_query($writeConnection, "INSERT INTO dc_login (`ce_id`, `pin_no`, `update_by`, `update_time`, `status`) VALUES ('" . $emp_id . "', '" . $pin_no . "', '" . $user . "', '" . $time1 . "', 'Y')");

			if ($sql11) {
				$nav = '0_1';
			} else {
				$nav = '0_2';
			}
		} else {
			$nav = '0_5';
		}


		header("Location:../?pid=$pid&nav=" . $nav);
	} else if ($pid == 'check_trans') {

		$deposit_id = $_POST['deposit_id'];
		$check_amount = $_POST['check_amount'];
		$account_idss = $_POST['account_idss'];
		$dep_slipes = $_POST['dep_slipes'];
		$deposit_status = $_POST['deposit_status'];

		foreach ($check_amount as $key => $depid) {
			if ($depid != 0) {
				$chk_trans = mysqli_query($writeConnection, "Select check_id from checked_transactions where status='Y' and acc_id = '" . $account_idss[$key] . "' and check_date='" . date('Y-m-d', strtotime($_POST['check_date'])) . "' and deposit_id='" . $deposit_id[$key] . "' ");
				if (mysqli_num_rows($chk_trans) > 0) {
					$chk_row = mysqli_fetch_assoc($chk_trans);

					$chk = "update checked_transactions set check_amount ='" . $depid . "', deposit_slip = '" . $dep_slipes[$key] . "', remarks='" . $_POST['remarks'][$key] . "',recon_user='" . $user_name . "',recon_time=now() where check_id='" . $chk_row['check_id'] . "' and status='Y' ";
				} else {

					$chk = "insert into checked_transactions(acc_id,check_date,deposit_id,check_amount,deposit_slip,remarks,status,created_by,created_date) values('" . $account_idss[$key] . "','" . date('Y-m-d', strtotime($_POST['check_date'])) . "','" . $deposit_id[$key] . "','" . $depid . "', '" . $dep_slipes[$key] . "', '" . $_POST['remarks'][$key] . "','Y','" . $user_name . "',now())";
				}
				$chk_exc = mysqli_query($writeConnection, $chk);
			}
		}
		if ($chk_exc) {
			// mysqli_close($conn);
			header("Location:../?pid=check_trans&nav=2_1");
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=check_trans&nav=2_5");
		}
	} else if ($pid == 'net_bank') {

		$rep_type = $_POST['rep_type'];

		$check_date = date('Y-m-d', strtotime($_POST['check_date']));
		$dep_types = $_POST['dep_types'];
		$acc_id = $_POST['acc_id'];

		$dep_branch = $_POST['dep_branch'];
		$dep_amount = $_POST['dep_amount'];
		$remarks = $_POST['remarks'];
		$dep_slipno = $_POST['dep_slipno'];
		$bds_trans_id = $_POST['bds_trans_id'];
		if ($rep_type == 1) {
			$rad_ce = $_POST['rad_ce'];
			foreach ($rad_ce as $key => $val) {
				if ($val != '') {
					$sql_insert = "INSERT INTO `daily_deposit` (`ent_type`, `dep_type`, `acc_id`, `dep_amount`, `dep_branch`, `dep_slipno`, `bds_trans_id`, `remarks`, `staff_id`, `dep_date`, `dep_time`, `user_name`, `status`) VALUES
	('Deposit', '" . $dep_types . "', '" . $acc_id . "',  '" . $dep_amount[$key] . "', '" . $dep_branch[$key] . "', '" . $dep_slipno[$key] . "', '" . $bds_trans_id[$key] . "',   '" . $remarks[$key] . "', '" . $val . "', '" . $check_date . "', '" . date('h:i:s A') . "', '" . $user_name . "', 'Y')";
					$query_in = mysqli_query($writeConnection, $sql_insert);
				}
			}
		} else {
			$rad_ce = $_POST['rad_ces'];

			foreach ($dep_amount as $key => $val) {
				if ($val != '' && $val != '0') {
					$sql_insert = "INSERT INTO `daily_deposit` (`ent_type`, `dep_type`, `acc_id`, `dep_amount`, `dep_branch`, `dep_slipno`, `bds_trans_id`, `remarks`, `staff_id`, `dep_date`, `dep_time`, `user_name`, `status`) VALUES
	('Deposit', '" . $dep_types . "', '" . $acc_id . "',  '" . $val . "', '" . $dep_branch[$key] . "', '" . $dep_slipno[$key] . "', '" . $bds_trans_id[$key] . "',   '" . $remarks[$key] . "', '" . $rad_ce . "', '" . $check_date . "', '" . date('h:i:s A') . "', '" . $user_name . "', 'Y')";
					$query_in = mysqli_query($writeConnection, $sql_insert);
				}
			}
		}

		$dep_branch = $_POST['dep_branchu'];
		$dep_amount = $_POST['dep_amountu'];
		$rad_ce = $_POST['rad_ceu'];
		$remarks = $_POST['remarksu'];
		$dep_slipno = $_POST['dep_slipnou'];
		$bds_trans_id = $_POST['bds_trans_idu'];
		$dep_idu = $_POST['dep_idu'];
		if ($rep_type == 1) {
			if (!empty($dep_idu)) {
				foreach ($dep_idu as $key2 => $val2) {
					$sql_insert = "UPDATE `daily_deposit` SET `dep_amount`='" . $dep_amount[$key2] . "', `dep_branch`='" . $dep_branch[$key2] . "', `dep_slipno`='" . $dep_slipno[$key2] . "', `bds_trans_id`='" . $bds_trans_id[$key2] . "', `remarks`='" . $remarks[$key2] . "', `staff_id`='" . $rad_ce[$key2] . "', `user_name`='" . $user_name . "' WHERE dep_id='" . $val2 . "'";

					$query_in = mysqli_query($writeConnection, $sql_insert);
				}
			}
		} else {
			if (!empty($dep_idu)) {
				foreach ($dep_idu as $key2 => $val2) {
					$sql_insert = "UPDATE `daily_deposit` SET `dep_amount`='" . $dep_amount[$key2] . "', `dep_branch`='" . $dep_branch[$key2] . "', `dep_slipno`='" . $dep_slipno[$key2] . "', `bds_trans_id`='" . $bds_trans_id[$key2] . "', `remarks`='" . $remarks[$key2] . "', `user_name`='" . $user_name . "' WHERE dep_id='" . $val2 . "'";

					$query_in = mysqli_query($writeConnection, $sql_insert);
				}
			}
		}

		// mysqli_close($conn);
		header("Location:../?pid=$pid&nav=1");
	} else if ($pid == 'rad_staff') {
		$data = $_REQUEST['data'];


		$branch_name = mysqli_real_escape_string($_POST['branch_name']);
		$state = mysqli_real_escape_string($_POST['state']);
		$location = mysqli_real_escape_string($_POST['location']);
		$staff_id = mysqli_real_escape_string($_POST['staff_id']);
		$name = mysqli_real_escape_string($_POST['name']);
		$gender = mysqli_real_escape_string($_POST['gender']);
		if ($_POST['dob'] != '') {
			$dob = date('Y-m-d', strtotime($_POST['dob']));
		} else {
			$dob = '0000-00-00';
		}
		if ($_POST['join_date'] != '') {
			$join_date = date('Y-m-d', strtotime($_POST['join_date']));
		} else {
			$join_date = '0000-00-00';
		}
		$designation = mysqli_real_escape_string($_POST['designation']);
		$mobile_no1 = mysqli_real_escape_string($_POST['mobile_no1']);
		$mobile_no2 = mysqli_real_escape_string($_POST['mobile_no2']);
		$superior_id = mysqli_real_escape_string($_POST['superior_id']);

		$fname = mysqli_real_escape_string($_POST['fname']);
		$pincode = mysqli_real_escape_string($_POST['pincode']);
		$panno = mysqli_real_escape_string($_POST['panno']);
		$qualification = mysqli_real_escape_string($_POST['qualification']);
		$marital = mysqli_real_escape_string($_POST['marital']);
		$email = mysqli_real_escape_string($_POST['email']);
		$nominee_name = mysqli_real_escape_string($_POST['nominee_name']);
		$nominee_rel = mysqli_real_escape_string($_POST['nominee_rel']);
		$nominee_mobile = mysqli_real_escape_string($_POST['nominee_mobile']);
		$status = mysqli_real_escape_string($_POST['status']);
		$address = mysqli_real_escape_string($_POST['address']);
		$remarks = mysqli_real_escape_string($_POST['remarks']);



		if ($id != '') {
			$sql_staff = "UPDATE `radiant_staff` SET `loc_id`='$location', `emp_id`='$staff_id', `emp_name`='$name', `gender`='$gender', `dob`='$dob', `doj`='$join_date', `design`='$designation', `mobile1`='$mobile_no1', `mobile2`='$mobile_no2', `head_id`='$superior_id', `father_name`='$fname', `address`='$address', `pincode`='$pincode', `panno`='$panno', `qualification`='$qualification', `marital`='$marital', `email_id`='$email', `nominee_name`='$nominee_name', `nominee_rel`='$nominee_rel', `nominee_mobile`='$nominee_mobile', `remarks`='$remarks', `status`='" . $status . "' WHERE `rec_id`='$id'";
		} else {
			$sql_staff = "INSERT INTO `radiant_staff` (`loc_id`, `emp_id`, `emp_name`, `gender`, `dob`, `doj`, `design`, `mobile1`, `mobile2`, `head_id`, `father_name`, `address`, `pincode`, `panno`, `qualification`, `marital`, `email_id`, `nominee_name`, `nominee_rel`, `nominee_mobile`, `remarks`, `entry_by`, `update_date`, `status`) VALUES ('$location', '$staff_id', '$name', '$gender', '$dob', '$join_date', '$designation', '$mobile_no1', '$mobile_no2', '$superior_id', '$fname', '$address', '$pincode', '$panno', '$qualification', '$marital', '$email', '$nominee_name', '$nominee_rel', '$nominee_mobile', '$remarks', '$user', '$coll_date', 'Y')";
		}

		$sql_staff1 = mysqli_query($sql_staff);
		if ($sql_staff1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}

		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'rad_ce') {
		$data = $_REQUEST['data'];
		$id = $_POST['id'];
		$nn = 0;

		$branch_name = mysqli_real_escape_string($_POST['branch_name']);
		$state = explode('^', $_POST['state']);
		$location = $_POST['location'];
		$name = mysqli_real_escape_string($_POST['name']);
		$gender = mysqli_real_escape_string($_POST['gender']);
		if ($_POST['dob'] != '') {
			$dob = date('Y-m-d', strtotime($_POST['dob']));
		} else {
			$dob = '';
		}
		if ($_POST['doj'] != '') {
			$doj = date('Y-m-d', strtotime($_POST['doj']));
		} else {
			$doj = '';
		}
		$designation = mysqli_real_escape_string($_POST['designation']);
		$ce_mobile1 = mysqli_real_escape_string($_POST['ce_mobile1']);
		$ce_mobile2 = mysqli_real_escape_string($_POST['ce_mobile2']);
		$superior_id = mysqli_real_escape_string($_POST['superior_id']);

		$fname = mysqli_real_escape_string($_POST['fname']);
		$pincode = mysqli_real_escape_string($_POST['pincode']);
		$panno = mysqli_real_escape_string($_POST['panno']);
		$qualification = mysqli_real_escape_string($_POST['qualification']);
		$marital = mysqli_real_escape_string($_POST['marital']);
		$email = mysqli_real_escape_string($_POST['email']);
		$nominee_name = mysqli_real_escape_string($_POST['nominee_name']);
		$nominee_rel = mysqli_real_escape_string($_POST['nominee_rel']);
		$nominee_mobile = mysqli_real_escape_string($_POST['nominee_mobile']);
		$status = mysqli_real_escape_string($_POST['status']);
		$address = mysqli_real_escape_string($_POST['address']);
		$remarks = mysqli_real_escape_string($_POST['remarks']);
		$contract_date = $_POST['contract_date'];
		$discontinue_date = $_POST['discontinue_date'];



		$sql2 = mysqli_query("SELECT state_code FROM state_code WHERE state_name='" . $state[1] . "' AND status='Y'");
		$res_ste = mysqli_fetch_object($sql2);
		$state_code = $res_ste->state_code;
		$sql3 = mysqli_query("SELECT ce_id FROM radiant_ce AS a JOIN radiant_location AS b ON a.loc_id=b.location_id WHERE a.ce_id like 'RAD-" . $state_code . "%' AND b.state_name='" . $state[1] . "' AND b.status='Y' AND a.status='Y' ORDER BY rec_id DESC");
		$n3 = mysqli_num_rows($sql3);
		$res_s = mysqli_fetch_assoc($sql3);

		$cu_ce_id = explode('-', $res_s['ce_id']);
		print_r($cu_ce_id);
		$cu_ce_id1 = count($cu_ce_id);

		$n3 = $cu_ce_id[2] + 1;

		$digot1 = abs(strlen($n3) - 4);
		if (strlen($n3) <= 3) {
			$s = sprintf("%0" . $digot1 . "d", '0') . $n3;
		} else {
			$s = 	$n3;
		}
		$am = 0;
		$ce_id = "RAD-" . $state_code . "-" . $s;
		if ($id != '') {

			$sql_ce = "UPDATE `radiant_ce` SET `loc_id`='$location', `ce_name`='$name', `gender`='$gender', `dob`='$dob', `doj`='$doj', `design`='$designation', `mobile1`='$ce_mobile1', `mobile2`='$ce_mobile2', `risk_id`='$superior_id', `father_name`='$fname', `address`='$address', `pincode`='$pincode', `panno`='$panno', `qualification`='$qualification', `marital`='$marital', `email_id`='$email', `nominee_name`='$nominee_name', `nominee_rel`='$nominee_rel', `nominee_mobile`='$nominee_mobile', `wstatus`='$status', `remarks`='$remarks', `contract_date`='$contract_date', `discontinue_date`='$discontinue_date' WHERE `rec_id`='$id'";
		} else {
			$sql1 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE ce_id='" . $ce_id . "' AND wstatus='Active' AND status='Y'");
			if (mysqli_num_rows($sql1) == 0) {

				echo "SELECT ce_id FROM radiant_ce WHERE dob='$dob' AND doj='$doj' AND  pincode='$pincode' AND panno='$panno' AND mobile1='$ce_mobile1' AND status='Y'";


				$sql_already1 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE dob='$dob' AND status='Y'");
				$sql_already2 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE doj='$doj' AND status='Y'");
				$sql_already3 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE pincode='$pincode' AND status='Y'");
				$sql_already4 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE panno='$panno' AND status='Y'");
				$sql_already5 = mysqli_query("SELECT ce_id FROM radiant_ce WHERE mobile1='$ce_mobile1' AND status='Y'");
				$already_ce = 0;
				if (mysqli_num_rows($sql_already1) > 0) $already_ce++;
				if (mysqli_num_rows($sql_already2) > 0) $already_ce++;
				if (mysqli_num_rows($sql_already3) > 0) $already_ce++;
				if (mysqli_num_rows($sql_already4) > 0) $already_ce++;
				if (mysqli_num_rows($sql_already5) > 0) $already_ce++;


				if ($already_ce >= 4) {
					// mysqli_close($conn);
					header("Location:../?pid=$pid&nav=7&id1=$ce_id&url=1");
				} else {
					$sql_ce = "INSERT INTO `radiant_ce` (`loc_id`, `ce_id`, `ce_name`, `gender`, `dob`, `doj`, `design`, `mobile1`, `mobile2`, `risk_id`, `father_name`, `address`, `pincode`, `panno`, `qualification`, `marital`, `email_id`, `nominee_name`, `nominee_rel`, `nominee_mobile`, `wstatus`, `remarks`, `contract_date`, `discontinue_date`, `entry_by`, `update_date`, `status`) VALUES ('$location', '$ce_id', '$name', '$gender', '$dob', '$doj', '$designation', '$ce_mobile1', '$ce_mobile2', '$superior_id', '$fname', '$address', '$pincode', '$panno', '$qualification', '$marital', '$email', '$nominee_name', '$nominee_rel', '$nominee_mobile', '$status', '$remarks', '$contract_date', '$discontinue_date', '$user', '$coll_date', 'Y')";
				}
			} else {
			}
		}
		if ($id != '') {
			if ($status == 'Dormant') {
				$sql_ce_detail = mysqli_query("SELECT ce_id FROM radiant_ce WHERE rec_id='$id'");
				$res_ce_detail = mysqli_fetch_assoc($sql_ce_detail);
				$upd_ce_id = $res_ce_detail['ce_id'];

				$sql_ce_map = mysqli_query("SELECT * FROM shop_cemap WHERE pri_ce='" . $upd_ce_id . "' AND status='Y'");
				$row_ce_map = mysqli_num_rows($sql_ce_map);
				if ($row_ce_map > 0) {
					// mysqli_close($conn);
					header('Location:../?pid=' . $pid . '&nav=6&id=' . $id . '&url=1&ce_name=' . $name . '&total_points=' . $row_ce_map . '&region=' . $branch_name . '&state=' . $state[1]);
					$nn = 1;
				} else {
					$sql_staff1 = mysqli_query($sql_ce);
				}
			} else {
				$sql_staff1 = mysqli_query($sql_ce);
			}
		} else {
			$sql_staff1 = mysqli_query($sql_ce);
		}
		if ($sql_staff1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1&id1=' . $ce_id . '&url=1&ce_name=' . $name;
			}
		} else {
			$redirect = '2';
		}

		if ($nn == 0) {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=' . $redirect);
		}
	} else if ($pid == 'm_client') {
		$id = $_POST['id'];
		$cliden_code = mysqli_real_escape_string($readConnection, $_POST['cliden_code']);
		$name = mysqli_real_escape_string($readConnection, $_POST['name']);
		$phone = mysqli_real_escape_string($readConnection, $_POST['phone']);
		$email = mysqli_real_escape_string($readConnection, $_POST['email']);
		if ($_POST['sdate'] != '') {
			$sdate = date('Y-m-d', strtotime($_POST['sdate']));
		}
		$address = mysqli_real_escape_string($readConnection, $_POST['address']);
		if ($id != '') {
			$check_log = "Successfully Updated";
			$sql_client = "UPDATE `client_details` SET `client_name`='$name', `client_address`='$address', `client_phone`='$phone', `client_email`='$email', `start_date`='$sdate', `update_by`='$user', `update_date`='$coll_date' WHERE `client_id`='$id'";
		} else {
			$check_log = "Successfully Inserted";
			$sql_client = "INSERT INTO `client_details` (`client_code`, `client_name`,`short_name`,`client_address`, `client_phone`, `client_email`, `start_date`, `update_by`, `update_date`, `status`) VALUES ('$cliden_code', '$name','','$address', '$phone', '$email', '$sdate', '$user', '$coll_date', 'Y')";
		}
		$sql_client1 = mysqli_query($writeConnection, $sql_client);

		if (isset($sql_client1)) {
			$log = new Logger("../log_data/log_client_details.txt");
			$log->setTimestamp("D M d 'Y h.i A");
			$log->putLog("Table Name: client_details", "Client Name: " . $name, "Successfully Inserted", "User Name: " . $user);
		}

		if ($sql_client1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}

		//// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'm_cust') {
		$id = $_POST['id'];
		$client_id = mysqli_real_escape_string($readConnection, $_POST['client_name']);
		$cust_code = mysqli_real_escape_string($readConnection, $_POST['cust_code']);
		$name = mysqli_real_escape_string($readConnection, $_POST['name']);
		$phone = mysqli_real_escape_string($readConnection, $_POST['phone']);
		$email = mysqli_real_escape_string($readConnection, $_POST['email']);
		if ($_POST['startup_date'] != '') {
			$startup_date = date('Y-m-d', strtotime($_POST['startup_date']));
		} else {
			$startup_date = '';
		}
		$address = mysqli_real_escape_string($readConnection, $_POST['address']);
		$division = mysqli_real_escape_string($readConnection, $_POST['division']);
		if ($_POST['pickup'] != '') {
			$pickup = $_POST['pickup'];
		} else {
			$pickup = 'N';
		}
		if ($_POST['delivery'] != '') {
			$delivery = $_POST['delivery'];
		} else {
			$delivery = 'N';
		}
		if ($_POST['multi_client_code'] != '') {
			$multi_client_code = '1';
		} else {
			$multi_client_code = '0';
		}
		$service = mysqli_real_escape_string($readConnection, $_POST['service']);
		$classification = mysqli_real_escape_string($readConnection, $_POST['classification']);
		$group_name = mysqli_real_escape_string($readConnection, $_POST['group_name']);
		if ($group_name == 'Others') {
			$group_name = mysqli_real_escape_string($readConnection, $_POST['other_gname']);
		}
		$dgroup = mysqli_real_escape_string($readConnection, $_POST['dgroup']);
		$scbtier = mysqli_real_escape_string($readConnection, $_POST['scbtier']);
		$report_view = $_POST['report_view'];

		if ($dgroup == 'Others') {
			$dgroup = $_POST['dother_gname'];
		}
		if ($_POST['direct_cust'] != '') {
			$direct_cust = $_POST['direct_cust'];
		} else {
			$direct_cust = 'N';
		}


		$cp1 = $_POST['cp1'];
		$cqp1 = $_POST['cqp1'];
		$del1 = $_POST['del1'];
		$dcp1 = $_POST['dcp1'];
		$dcqp1 = $_POST['dcqp1'];

		$cp = $_POST['cp'];
		$cqp = $_POST['cqp'];
		$del = $_POST['del'];
		$dcp = $_POST['dcp'];
		$dcqp = $_POST['dcqp'];

		$unique_cust_name = $_POST['unique_custname'];

		if ($id != '') {
			$check_log = "Successfully Updated";
			$sql_cust = "UPDATE `cust_details` SET `cust_code`='$cust_code', `client_id`='$client_id', `cust_name`='$name', `cust_address`='$address', `cust_phone`='$phone', `cust_email`='$email', `cust_div`='$division', `cust_type`='$service', `fund_day`='$classification',`scbtier`='$scbtier', `group_name`='$group_name', `dgroup_name`='$dgroup', `direct_cust`='$direct_cust', `deno`='$pickup', `d_deno`='$delivery', `multi_deno`='$multi_client_code', `report_view`='$report_view',`unique_cust_name`='$unique_cust_name', `start_date`='$startup_date', `update_by`='$user', `update_date`='$coll_date' WHERE `cust_id`='$id'";
		} else {
			$check_log = "Successfully Inserted";
			$sql_cust = "INSERT INTO `cust_details` (`cust_code`, `client_id`, `cust_name`, `cust_address`, `cust_phone`, `cust_email`, `cust_div`, `cust_type`, `fund_day`,`scbtier`,`group_name`, `dgroup_name`, `direct_cust`, `deno`, `d_deno`, `multi_deno`, 
			`report_view`,`unique_cust_name`, `start_date`, `update_by`, `update_date`, `status`) VALUES ('$cust_code', 
			'$client_id', '$name', '$address', '$phone', '$email', '$division', '$service', '$classification','$scbtier','$group_name', '$dgroup', '$direct_cust', '$pickup', '$delivery', '$multi_client_code', '$report_view','$unique_cust_name','$startup_date', '$user', '$coll_date', 'Y')";
		}

		$sql_client1 = mysqli_query($writeConnection, $sql_cust);
		$lastInsertId = mysqli_insert_id($writeConnection);

		//selva 2021/aug
		$existingCustIds = array();
		if ($sql_client1  && $client_id == '22') {
			//$lastInsertId = mysqli_insert_id();
			if ($lastInsertId == 0) {
				$lastInsertId = '';
			}
			$upqry = "UPDATE `client_login` SET cust_id = ( case ";

			$cltqry = mysqli_query($readConnection, "SELECT * FROM `client_login` WHERE `client_id` = '22' and user_name in('demo','icici','iciciuser')");
			while ($rows = mysqli_fetch_array($cltqry)) {
				$existingCustIds[$rows['user_name']] = $rows['cust_id'];
			}

			foreach ($existingCustIds as $key => $value) {
				$existingCustIds[$key] = $value . $lastInsertId . ",";
				$existingCustIds[$key] = str_replace(",,", ",", "$existingCustIds[$key]");
				$upqry .= " when user_name = '" . $key . "' then '" . $existingCustIds[$key] . "'";
			}

			$upqry .= "end) WHERE user_name in ('demo','icici','iciciuser')";
			$sql_client2 = mysqli_query($writeConnection, $upqry);
		}
		//end 

		//Selva cr83 kmb-gic//
		if ($sql_client1  && $client_id == '18') {
			//$lastInsertId = mysqli_insert_id();

			$new_cust_add = '{"opt":"cust_add","new_cust_id":"' . $lastInsertId . '"}';

			$url = "http://192.168.5.207/BDS/bds_services.php";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $new_cust_add);
			curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Type:application/json');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($result, true);

			$cust_status = $response['status'];
		}

		// log file
		if ((isset($sql_client1) && isset($sql_client2)) || $cust_status == 1) {
			$log = new Logger("../log_data/log_cust_details.txt");
			$log->setTimestamp("D M d 'Y h.i A");
			$log->putLog("Table Name:cust_details", "Cust Name:" . $name, $check_log, "User Name:" . $user);
		}


		if ($id != '') {
			$last_ids = $id;
		} else {
			$last_ids = $lastInsertId;
		}

		//Cash Pickup
		if ($cp != '') {
			$c_pickup = '';
			$sql_cp = mysqli_query($readConnection, "SELECT id FROM pickup_field_custome WHERE Cust_IDS LIKE '%," . $last_ids . ",%' AND status='Y'");
			$res_cp = mysqli_fetch_object($sql_cp);
			if (mysqli_num_rows($sql_cp) > 0) {
				if ($cp != $cp1) {
					$sql_42 = mysqli_query($readConnection, "SELECT Cust_IDS FROM pickup_field_custome WHERE id='$cp1' AND status='Y'");
					$res_42 = mysqli_fetch_object($sql_42);
					$c_pickup1 = str_replace($last_ids . ',', '', $res_42->Cust_IDS);

					$sql_43 = mysqli_query($writeConnection, "UPDATE pickup_field_custome SET Cust_IDS='" . $c_pickup1 . "' WHERE id='" . $cp1 . "'");


					$sql_44 = mysqli_query($readConnection, "SELECT Cust_IDS FROM pickup_field_custome WHERE id='$cp' AND status='Y'");
					$res_44 = mysqli_fetch_object($sql_44);
					$c_pickup = $res_44->Cust_IDS . $last_ids . ',';
				}
			} else {
				$sql_41 = mysqli_query($readConnection, "SELECT Cust_IDS FROM pickup_field_custome WHERE id='$cp' AND status='Y'");
				$res_41 = mysqli_fetch_object($sql_41);
				$c_pickup = $res_41->Cust_IDS . $last_ids . ',';
			}
			if ($c_pickup != '') {

				$sql_45 = mysqli_query($writeConnection, "UPDATE pickup_field_custome SET Cust_IDS='" . $c_pickup . "' WHERE id='" . $cp . "'");
			}
		}

		//Cheque Pickup
		if ($cqp != '') {
			$cq_pickup = '';
			$sql_cqp = mysqli_query($readConnection, "SELECT id FROM pickup_cheque_custom WHERE cust_id LIKE '%," . $last_ids . ",%' AND status='Y'");
			$res_cqp = mysqli_fetch_object($sql_cqp);
			if (mysqli_num_rows($sql_cqp) > 0) {
				if ($cqp != $cqp1) {
					$sql_46 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_custom WHERE id='$cqp1' AND status='Y'");
					$res_46 = mysqli_fetch_object($sql_46);
					$cq_pickup1 = str_replace($last_ids . ',', '', $res_46->cust_id);
					$sql_47 = mysqli_query($writeConnection, "UPDATE pickup_cheque_custom SET cust_id='" . $cq_pickup1 . "' WHERE id='" . $cqp1 . "'");

					$sql_48 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_custom WHERE id='$cqp' AND status='Y'");
					$res_48 = mysqli_fetch_object($sql_48);
					$cq_pickup = $res_48->cust_id . $last_ids . ',';
				}
			} else {
				$sql_49 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_custom WHERE id='$cqp' AND status='Y'");
				$res_49 = mysqli_fetch_object($sql_49);
				$cq_pickup = $res_49->cust_id . $last_ids . ',';
			}
			if ($cq_pickup != '') {
				$sql_50 = mysqli_query($writeConnection, "UPDATE pickup_cheque_custom SET cust_id='" . $cq_pickup . "' WHERE id='" . $cqp . "'");
			}
		}

		//Delivery
		if ($del != '') {
			$del_pickup = '';
			$sql_del = mysqli_query($readConnection, "SELECT id FROM delivery_field_custome WHERE Cust_IDS LIKE '%," . $last_ids . ",%' AND status='Y'");
			$res_del = mysqli_fetch_object($sql_del);
			if (mysqli_num_rows($sql_del) > 0) {
				if ($del != $del1) {
					$sql_51 = mysqli_query($readConnection, "SELECT Cust_IDS FROM delivery_field_custome WHERE id='$del1' AND status='Y'");
					$res_51 = mysqli_fetch_object($sql_51);
					$del_pickup1 = str_replace($last_ids . ',', '', $res_51->Cust_IDS);

					$sql_52 = mysqli_query($writeConnection, "UPDATE delivery_field_custome SET Cust_IDS='" . $del_pickup1 . "' WHERE id='" . $del1 . "'");

					$sql_53 = mysqli_query($readConnection, "SELECT Cust_IDS FROM delivery_field_custome WHERE id='$del' AND status='Y'");
					$res_53 = mysqli_fetch_object($sql_53);
					$del_pickup = $res_53->Cust_IDS . $last_ids . ',';
				}
			} else {

				$sql_54 = mysqli_query($readConnection, "SELECT Cust_IDS FROM delivery_field_custome WHERE id='$del' AND status='Y'");
				$res_54 = mysqli_fetch_object($sql_54);
				$del_pickup = $res_54->Cust_IDS . $last_ids . ',';
			}
			if ($del_pickup != '') {
				$sql_55 = mysqli_query($writeConnection, "UPDATE delivery_field_custome SET Cust_IDS='" . $del_pickup . "' WHERE id='" . $del . "'");
			}
		}

		//Dynamic Cash Pickup
		if ($dcp != '') {
			$dc_pickup = '';
			$sql_dcp = mysqli_query($readConnection, "SELECT id FROM pickup_cash_dynamic_custom WHERE cust_id LIKE '%," . $last_ids . ",%' AND status='Y'");
			$res_dcp = mysqli_fetch_object($sql_dcp);
			if (mysqli_num_rows($sql_dcp) > 0) {
				if ($dcp != $dcp1) {
					$sql_42 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cash_dynamic_custom WHERE id='$dcp1' AND status='Y'");
					$res_42 = mysqli_fetch_object($sql_42);
					$dc_pickup1 = str_replace($last_ids . ',', '', $res_42->cust_id);
					$sql_43 = mysqli_query($writeConnection, "UPDATE pickup_cash_dynamic_custom SET cust_id='" . $dc_pickup1 . "' WHERE id='" . $dcp1 . "'");

					$sql_44 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cash_dynamic_custom WHERE id='$dcp' AND status='Y'");
					$res_44 = mysqli_fetch_object($sql_44);
					$dc_pickup = $res_44->cust_id . $last_ids . ',';
				}
			} else {
				$sql_41 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cash_dynamic_custom WHERE id='$dcp' AND status='Y'");
				$res_41 = mysqli_fetch_object($sql_41);
				$dc_pickup = $res_41->cust_id . $last_ids . ',';
			}
			if ($dc_pickup != '') {
				$sql_45 = mysqli_query($writeConnection, "UPDATE pickup_cash_dynamic_custom SET cust_id='" . $dc_pickup . "' WHERE id='" . $dcp . "'");
			}
		}

		//Dynamic Cheque Pickup
		if ($dcqp != '') {
			$dcq_pickup = '';
			$sql_dcqp = mysqli_query($readConnection, "SELECT id FROM pickup_cheque_dynamic_custom WHERE cust_id LIKE '%," . $last_ids . ",%' AND status='Y'");
			$res_dcqp = mysqli_fetch_object($sql_dcqp);
			if (mysqli_num_rows($sql_dcqp) > 0) {
				if ($dcqp != $dcqp1) {
					$sql_46 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_dynamic_custom WHERE id='$dcqp1' AND status='Y'");
					$res_46 = mysqli_fetch_object($sql_46);
					$dcq_pickup1 = str_replace($last_ids . ',', '', $res_46->cust_id);
					$sql_47 = mysqli_query($writeConnection, "UPDATE pickup_cheque_dynamic_custom SET cust_id='" . $dcq_pickup1 . "' WHERE id='" . $dcqp1 . "'");

					$sql_48 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_dynamic_custom WHERE id='$dcqp' AND status='Y'");
					$res_48 = mysqli_fetch_object($sql_48);
					$dcq_pickup = $res_48->cust_id . $last_ids . ',';
				}
			} else {
				$sql_49 = mysqli_query($readConnection, "SELECT cust_id FROM pickup_cheque_dynamic_custom WHERE id='$dcqp' AND status='Y'");
				$res_49 = mysqli_fetch_object($sql_49);
				$dcq_pickup = $res_49->cust_id . $last_ids . ',';
			}
			if ($dcq_pickup != '') {

				$sql_50 = mysqli_query($writeConnection, "UPDATE pickup_cheque_dynamic_custom SET cust_id='" . $dcq_pickup . "' WHERE id='" . $dcqp . "'");
			}
		}

		if ($sql_client1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}
		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'm_shop_approve') {
		$shop_id1 = mysqli_real_escape_string($writeConnection, $_POST['shop_id1']);
		$sql_shop = "UPDATE `shop_details` SET status='Y',approve_by='" . $user . "' , approve_date=now() WHERE `shop_id`='$shop_id1'";
		$res = mysqli_query($writeConnection, $sql_shop);
		if ($res) {
			$redirect = '1';
		} else {
			$redirect = '2';
		}

		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'm_shop') {
		$mclass = new sendSms();
		$id = $_POST['id'];
		$cust_id = mysqli_real_escape_string($readConnection, $_POST['cust_id']);

		//start lat-long
		$latitude = mysqli_real_escape_string($readConnection, $_POST['latitude']);
		$longitude = mysqli_real_escape_string($readConnection, $_POST['longitude']);
		$latlongflag = mysqli_real_escape_string($readConnection, $_POST['latlongflag']);
		//end lat-long

		$tier_loc = mysqli_real_escape_string($readConnection, $_POST['tier']);
		$cust_div = mysqli_real_escape_string($readConnection, $_POST['cust_div']);
		$shop_id1 = mysqli_real_escape_string($readConnection, $_POST['shop_id1']);
		$state = mysqli_real_escape_string($readConnection, $_POST['state']);
		$location = mysqli_real_escape_string($readConnection, $_POST['location']);
		$city_name = mysqli_real_escape_string($readConnection, $_POST['location2']);
		$loi_id = mysqli_real_escape_string($readConnection, $_POST['loi_id']);
		if ($_POST['loi_date'] != '') {
			$loi_date = date('Y-m-d', strtotime($_POST['loi_date']));
		}
		$cust_code = mysqli_real_escape_string($readConnection, $_POST['cust_code']);
		$shop_code = mysqli_real_escape_string($readConnection, $_POST['shop_code']);
		$loc_code = mysqli_real_escape_string($readConnection, $_POST['loc_code']);
		$address = mysqli_real_escape_string($readConnection, $_POST['address']);
		$pincode = mysqli_real_escape_string($readConnection, $_POST['pincode']);
		$hier_code = mysqli_real_escape_string($readConnection, $_POST['hier_code']);
		$subcust_code = mysqli_real_escape_string($readConnection, $_POST['subcust_code']);
		$shop_name = mysqli_real_escape_string($readConnection, $_POST['shop_name']);
		$sol_id = mysqli_real_escape_string($readConnection, $_POST['sol_id']);
		$div_code = mysqli_real_escape_string($readConnection, $_POST['div_code']);
		$phone = mysqli_real_escape_string($readConnection, $_POST['phone']);
		$contact_name = mysqli_real_escape_string($readConnection, $_POST['contact_name']);
		$contact_no = mysqli_real_escape_string($readConnection, $_POST['contact_no']);
		$service_type = mysqli_real_escape_string($readConnection, $_POST['service_type']);
		$cash_limit = mysqli_real_escape_string($readConnection, $_POST['cash_limit']);
		$bank_type = mysqli_real_escape_string($readConnection, $_POST['bank_type']);
		if ($_POST['uacc_no'] == 'Y') $account_no = mysqli_real_escape_string($readConnection, $_POST['acc_id']);
		else $account_no = mysqli_real_escape_string($readConnection, $_POST['account_no']);
		$pickup_type = mysqli_real_escape_string($readConnection, $_POST['pickup_type']);
		$feasibility = mysqli_real_escape_string($readConnection, $_POST['feasibility']);
		$point_type = mysqli_real_escape_string($readConnection, $_POST['point_type']);
		$remarks = mysqli_real_escape_string($readConnection, $_POST['remarks']);
		$show_slip = mysqli_real_escape_string($readConnection, $_POST['show_slip']);
		$served_location = mysqli_real_escape_string($readConnection, $_POST['served_location']);
		$email_id = mysqli_real_escape_string($readConnection, $_POST['email_id']);
		$pin_number = mysqli_real_escape_string($readConnection, $_POST['pin_number']);
		$mbc_type = mysqli_real_escape_string($readConnection, $_POST['mbc_type']);
		$other_pickup = mysqli_real_escape_string($readConnection, $_POST['other_pickup']);
		$qr_status = mysqli_real_escape_string($readConnection, $_POST['qr_status']);
		$rbicompliance = mysqli_real_escape_string($readConnection, $_POST['rbicompliance']);
		$disposal_location = mysqli_real_escape_string($readConnection, $_POST['disposal_location']);
		$qr_json = mysqli_real_escape_string($readConnection, $_POST['qr_json']);
		$process = mysqli_real_escape_string($readConnection, $_POST['process']);
		$vendor_id = mysqli_real_escape_string($readConnection,$_POST['vendor_id']);
		$depositbranch_code = mysqli_real_escape_string($readConnection,$_POST['depositbranch_code']);
		$cost_centre = mysqli_real_escape_string($readConnection,$_POST['cost_centre']);
		$circle_id = mysqli_real_escape_string($readConnection,$_POST['circle_id']);

		$selected_beat_days = "";
		if (!empty($_POST['seleted_beat'])) {
			$selected_beat_days = implode(',', $_POST['seleted_beat']);
		}


		if ($_POST['inactive_date'] != '') {
			$inactive_date = date('Y-m-d', strtotime(mysqli_real_escape_string($readConnection, $_POST['inactive_date'])));
		}




		if ($_POST['sact_date'] != '') {
			$sact_date = date('Y-m-d', strtotime($_POST['sact_date']));
		}
		if ($_POST['sdeact_date'] != '') {
			$sdeact_date = date('Y-m-d', strtotime($_POST['sdeact_date']));
		}
		$shop_type = "Shop";
		//------------------------------------New Line comme Tracker vignesh 16/11/2016--------------------------//

		$shop_query = mysqli_query($readConnection, "select * from shop_details where shop_id ='$shop_id1'");
		$res = mysqli_fetch_assoc($shop_query);
		if ($pincode == $res['pincode']) {
			$pincode_track = "";
		} else {
			$pincode_track = $pincode;
		}
		if ($loi_id == $res['loi_id']) {
			$loi_id_track = "";
		} else {
			$loi_id_track = $loi_id;
		}
		if ($cust_code == $res['customer_code']) {
			$cust_code_track = "";
		} else {
			$cust_code_track = $cust_code;
		}
		if ($shop_code == $res['shop_code']) {
			$shop_code_track = "";
		} else {
			$shop_code_track = $shop_code;
		}
		if ($loi_date == $res['loi_date']) {
			$shop_type_track = "";
		} else {
			$shop_type_track = $loi_date;
		}
		if ($loc_code == $res['loc_code']) {
			$loc_code_track = "";
		} else {
			$loc_code_track = $loc_code;
		}
		if ($shop_name == $res['shop_name']) {
			$shop_name_track = "";
		} else {
			$shop_name_track = $shop_name;
		}
		if ($address == $res['address']) {
			$address_track = "";
		} else {
			$address_track = $address;
		}
		if ($hier_code == $res['hierarchy_code']) {
			$hier_code_track = "";
		} else {
			$hier_code_track = $hier_code;
		}
		if ($subcust_code == $res['subcust_code']) {
			$subcust_code_track = "";
		} else {
			$subcust_code_track = $subcust_code;
		}
		if ($sol_id == $res['sol_id']) {
			$sol_id_track = "";
		} else {
			$sol_id_track = $sol_id;
		}
		if ($div_code == $res['div_code']) {
			$div_code_track = "";
		} else {
			$div_code_track = $div_code;
		}
		if ($phone == $res['phone']) {
			$phone_track = "";
		} else {
			$phone_track = $phone;
		}
		if ($contact_name == $res['contact_name']) {
			$contact_name_track = "";
		} else {
			$contact_name_track = $contact_name;
		}
		if ($contact_no == $res['contact_no']) {
			$contact_no_track = "";
		} else {
			$contact_no_track = $contact_no;
		}
		if ($email_id == $res['email_id']) {
			$email_id_track = "";
		} else {
			$email_id_track = $email_id;
		}
		if ($cash_limit == $res['cash_limit']) {
			$cash_limit_track = "";
		} else {
			$cash_limit_track = $cash_limit;
		}
		if ($account_no == $res['account_no']) {
			$account_no_track = "";
		} else {
			$account_no_track = $account_no;
		}
		if ($remarks == $res['remarks']) {
			$remarks_track = "";
		} else {
			$remarks_track = $remarks;
		}
		if ($cust_id == $res['cust_id']) {
			$cust_id_track = "";
		} else {
			$cust_id_track = $cust_id;
		}
		if ($cust_div == $res['cust_div']) {
			$cust_div_track = "";
		} else {
			$cust_div_track = $cust_div;
		}
		if ($state == $res['state']) {
			$state_track = "";
		} else {
			$state_track = $state;
		}
		if ($location == $res['location']) {
			$location_track = "";
		} else {
			$location_track = $location;
		}
		if ($service_type == $res['service_type']) {
			$service_type_track = "";
		} else {
			$service_type_track = $service_type;
		}
		if ($bank_type == $res['dep_bank']) {
			$bank_type_track = "";
		} else {
			$bank_type_track = $bank_type;
		}
		if ($pickup_type == $res['pickup_type']) {
			$pickup_type_track = "";
		} else {
			$pickup_type_track = $pickup_type;
		}
		if ($feasibility == $res['feasibility']) {
			$feasibility_track = "";
		} else {
			$feasibility_track = $feasibility;
		}
		if ($point_type == $res['point_type']) {
			$point_type_track = "";
		} else {
			$point_type_track = $point_type;
		}
		if ($served_location == $res['served_location']) {
			$served_location_track = "";
		} else {
			$served_location_track = $served_location;
		}
		if ($other_pickup == $res['other_pickup']) {
			$other_pickup_track = "";
		} else {
			$other_pickup_track = $other_pickup;
		}

		//---------------------------------------comme Track End ------------------------------------------------//


		if ($id != '') {
			$check_log = "Successfully Updated";
			$for_log_shopid = $shop_id1;


			$sql_shop = "UPDATE `shop_details` SET city_name='$city_name',`cust_id`='$cust_id', `cust_div`='$cust_div', `loi_id`='$loi_id', `sact_date`='$sact_date', `sdeact_date`='$sdeact_date', `loi_date`='$loi_date', `shop_type`='$shop_type', `customer_code`='$cust_code', `hierarchy_code`='$hier_code', `subcustomer_code`='$subcust_code', `loc_code`='$loc_code', `shop_code`='$shop_code', `shop_name`='$shop_name', `address`='$address', `pincode`='$pincode', `sol_id`='$sol_id', `div_code`='$div_code',`depositbranch_code`='$depositbranch_code', `phone`='$phone', `location`='$location', `served_location`='$served_location', `state`='$state', `contact_name`='$contact_name', `contact_no`='$contact_no', `service_type`='$service_type',`process`='$process', `mbc_type`='$mbc_type', `cash_limit`='$cash_limit', `dep_bank`='$bank_type', `acc_id`='$account_no', `pickup_type`='$pickup_type', `feasibility`='$feasibility', `point_type`='$point_type', `email_id`='" . $email_id . "', `cpin`='" . $pin_number . "', `remarks`='$remarks', `update_by`='$user', inactive_date='" . $inactive_date . "',other_pickup='" . $other_pickup . "',qr_status='" . $qr_status . "' ,rbicompliance='" . $rbicompliance . "',disposal_location='" . $disposal_location . "',qr_json='" . $qr_json . "',`update_date`='$coll_date',upd_time='$curr_time',selected_beat_days='" . $selected_beat_days . "', tier = '$tier_loc', show_slip = '$show_slip',`latitude`='$latitude',`longitude`='$longitude',latlongflag='$latlongflag',vendor_id='$vendor_id',cost_centre='$cost_centre',circle_id='$circle_id' WHERE `shop_id`='$shop_id1'";


			//------------------------------------New Line comme Tracker vignesh 16/11/2016--------------------------//
			$sql_shop2 = "UPDATE `shop_details1` SET cash_limit='$cash_limit',dep_bank='$bank_type',pickup_type='$pickup_type',customer_code='$cust_code',shop_code='$shop_code',address='$address',point_type='$point_type',loi_date='$loi_date',service_type='$service_type',location='$location',`cust_id`='$cust_id',`track_status`='1',`update_date`='$coll_date',`update_by`='$user' WHERE `shop_id`='$shop_id1'";

			$sql_shop_tracker = "UPDATE `shop_details_track` SET `cust_id`='$cust_id', `cust_div`='$cust_div_track', `loi_id`='$loi_id_track', `shop_type`='$shop_type_track', `customer_code`='$cust_code_track', `hierarchy_code`='$hier_code_track', `subcustomer_code`='$subcust_code_track', `loc_code`='$loc_code_track', `shop_code`='$shop_code_track', `shop_name`='$shop_name_track', `address`='$address_track', `pincode`='$pincode_track', `sol_id`='$sol_id_track', `div_code`='$div_code_track', `phone`='$phone_track', `location`='$location_track', `served_location`='$served_location_track', `state`='$state_track', `contact_name`='$contact_name_track', `contact_no`='$contact_no_track', `service_type`='$service_type_track', `cash_limit`='$cash_limit_track', `dep_bank`='$bank_type_track', `acc_id`='$account_no_track', `pickup_type`='$pickup_type_track', `feasibility`='$feasibility_track', `point_type`='$point_type_track', `email_id`='" . $email_id_track . "',`remarks`='$remarks_track', `update_by`='$user', other_pickup='" . $other_pickup_track . "' , `update_date`='$coll_date',upd_time='$curr_time' WHERE `shop_tracker_id`='$shop_id1'";

			//---------------------------------------------- End-------------------------------------------------------// 



			$sql_shops = mysqli_query($readConnection, "SELECT c.cust_name,  a.shop_name, a.cpin, a.email_id, a.contact_no, a.address, b.location FROM shop_details AS a INNER JOIN location_master AS b ON a.location=b.loc_id INNER JOIN cust_details AS c ON a.cust_id=c.cust_id WHERE a.shop_id='" . $shop_id1 . "' AND a.point_type!='Lost'   AND   a.`status`='Y' AND b.`status`='Y'");


			if (mysqli_num_rows($sql_shops) != 0) {
				$res_shops = mysqli_fetch_assoc($sql_shops);

				if ($res_shops['cpin'] != $_POST['pin_number'] && $_POST['pin_number'] != '' && $_POST['pin_number'] != '0' && $_POST['email_id'] != '' && $_POST['email_id'] != '0') {

					$cpinData = array('email_id' => $_POST['email_id'], 'cust_name' => str_replace(" ", "_", $res_shops['cust_name']), 'shop_name' => str_replace(" ", "_", $res_shops['shop_name']), 'cpin' => $_POST['pin_number'], 'location' => $res_shops['location']);
					$jsonDataEncoded = json_encode($cpinData);
					$ch = curl_init($EmailServerUrl . "email_alert/NotificationForCpin.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
					$result = curl_exec($ch);
					curl_close($ch);
				}
				if ($res_shops['cpin'] != $_POST['pin_number'] && $contact_no != '0'  && $contact_no != '' && $_POST['pin_number'] != '' && $_POST['pin_number'] != '0') {

					$SmsPinNumber = !empty($_POST['pin_number']) ? $_POST['pin_number'] : '-';
					$SmsCustomer = !empty($res_shops['cust_name']) ? $res_shops['cust_name'] : '-';
					$SmsShopName = !empty($res_shops['shop_name']) ? substr($res_shops['shop_name'], 0, 28) : '-';
					//$SmsAddress = !EMPTY($res_shops['address'])?$res_shops['address']:'-';

					$SmsAddress = '-';

					$smsmessage = 'Dear Customer, Your 4 Digit Secured Cpin PIN is ' . trim($SmsPinNumber) . ' Transaction Authentication by using Cpin- is Activated for the below mentioned point. Customer Name: ' . trim($SmsCustomer) . ' Point Name: ' . trim($SmsShopName) . 'Address : ' . trim($SmsAddress);


					if ($contact_no != "") $mclass->sendSmsToUser($smsmessage, $contact_no, "");
				}
			}
		} else {
			function getshop_id($readConnection)
			{

				$sql5 = mysqli_query($readConnection, "SELECT COALESCE( MAX( CAST( SUBSTRING( shop_id, LOCATE(  'C', shop_id ) +1, 10 ) AS UNSIGNED ) ) , 0 ) +1 AS nos
FROM shop_details");
				if (mysqli_num_rows($sql5) > 0) {
					$res5 = mysqli_fetch_object($sql5);

					$lshop_id1 = $res5->nos;

					$digot1 = abs(strlen($lshop_id1) - 5);
					if (strlen($lshop_id1) <= 4) {
						$s = sprintf("%0" . $digot1 . "d", '0') . $lshop_id1;
					} else {
						$s = 	$lshop_id1;
					}
					$pshop_id = "RSC" . $s;
				} else {
					$pshop_id = "RSC00001";
				}
				return $pshop_id;
			}
			$shops_ids = getshop_id($readConnection);
			$check_log = "Successfully Inserted";
			$for_log_shopid = $shops_ids;

			$sql_shop = "INSERT INTO `shop_details` (`city_name`,`shop_id`, `cust_id`, `cust_div`, `loi_id`, `sact_date`, `sdeact_date`, `loi_date`, `shop_type`, `customer_code`, `hierarchy_code`, `subcustomer_code`, `loc_code`, `shop_code`, `shop_name`, `address`, `pincode`, `sol_id`, `div_code`,`depositbranch_code`, `phone`, `location`, `served_location`, `state`, `contact_name`, `contact_no`, `service_type`,`process`, `mbc_type`, `cash_limit`, `dep_bank`, `acc_id`, `pickup_type`, `feasibility`, `point_type`, `email_id`, `cpin`,other_pickup,qr_status,rbicompliance,disposal_location,qr_json,selected_beat_days, `remarks`,create_date ,`update_by`, `update_date`,upd_time, `status`, `tier`, `show_slip`,`latitude`,`longitude`,latlongflag,vendor_id,`cost_centre`,`circle_id`) VALUES ('" . $city_name . "','" . $shops_ids . "', '$cust_id', '$cust_div', '$loi_id', '$sact_date', '$sdeact_date', '$loi_date', '$shop_type', '$cust_code', '$hier_code', '$subcust_code', '$loc_code', '$shop_code', '$shop_name', '$address', '$pincode', '$sol_id', '$div_code','$depositbranch_code', '$phone', '$location', '$served_location', '$state', '$contact_name', '$contact_no', '$service_type','$process', '$mbc_type', '$cash_limit', '$bank_type', '$account_no', '$pickup_type', '$feasibility', '$point_type', '" . $email_id . "', '" . $pin_number . "','" . $other_pickup . "','" . $qr_status . "','" . $rbicompliance . "','" . $disposal_location . "','" . $qr_json . "','" . $selected_beat_days . "', '$remarks','" . $curr_date_time . "', '$user', '$coll_date','$curr_time' ,'R','$tier_loc','$show_slip','$latitude','$longitude','$latlongflag','$vendor_id','$cost_centre','$circle_id')";



			//------------------------------------New Line comme Tracker vignesh 16/11/2016--------------------------//

			$sql_shop2 = "INSERT INTO `shop_details1` (`shop_id`, `cust_id`, `cust_div`, `loi_id`, `sact_date`, `sdeact_date`, `loi_date`, `shop_type`, `customer_code`, `hierarchy_code`, `subcustomer_code`, `loc_code`, `shop_code`, `shop_name`, `address`, `pincode`, `sol_id`, `div_code`, `phone`, `location`, `served_location`, `state`, `contact_name`, `contact_no`, `service_type`, `mbc_type`, `cash_limit`, `dep_bank`, `acc_id`, `pickup_type`, `feasibility`, `point_type`, `email_id`, `cpin`,other_pickup,selected_beat_days, `remarks`, create_date ,`update_by`, `update_date`, `status`, `track_status`) VALUES ('" . $shops_ids . "', '$cust_id', '$cust_div', '$loi_id', '$sact_date', '$sdeact_date', '$loi_date', '$shop_type', '$cust_code', '$hier_code', '$subcust_code', '$loc_code', '$shop_code', '$shop_name', '$address', '$pincode', '$sol_id', '$div_code', '$phone', '$location', '$served_location', '$state', '$contact_name', '$contact_no', '$service_type', '$mbc_type', '$cash_limit', '$bank_type', '$account_no', '$pickup_type', '$feasibility', '$point_type', '" . $email_id . "', '" . $pin_number . "','" . $other_pickup . "','" . $selected_beat_days . "', '$remarks','" . $curr_date_time . "', '$user', '$coll_date', 'Y', '0')";

			$sql_shop_tracker = "INSERT INTO `shop_details_track` (`shop_tracker_id`, `cust_id`, `cust_div`, `loi_id`, `sact_date`, `sdeact_date`, `loi_date`, `shop_type`, `customer_code`, `hierarchy_code`, `subcustomer_code`, `loc_code`, `shop_code`, `shop_name`, `address`, `pincode`, `sol_id`, `div_code`, `phone`, `location`, `served_location`, `state`, `contact_name`, `contact_no`, `service_type`, `mbc_type`, `cash_limit`, `dep_bank`, `acc_id`, `pickup_type`, `feasibility`, `point_type`, `email_id`, `cpin`,other_pickup,selected_beat_days, `remarks`, `update_by`, `update_date`, `status`) VALUES ('" . $shops_ids . "', '$cust_id', '', '', '', '', '$loi_date', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '','','', '', '$user', '$coll_date', 'Y')";


			//-----------------------------END----------------------------------------------------------------------------//



		}

		if ($id == '') {
			$sql_check = mysqli_query($readConnection, "select shop_id from shop_details where status='Y' and cust_id='" . $cust_id . "' and loi_date='" . $loi_date . "' and pincode='" . $pincode . "' and location='" . $location . "' and customer_code='" . $cust_code . "' and loc_code='" . $loc_code . "' and shop_code='" . $shop_code . "' and service_type='" . $service_type . "'");
			$quer_chk = mysqli_num_rows($sql_check);
		} else {
			$quer_chk = 0;
		}
		if ($quer_chk == 0) {
			$sql_shop1 = mysqli_query($writeConnection, $sql_shop);
			$sql_shop3 = mysqli_query($writeConnection, $sql_shop2);
			$sql_shop_tracker1 = mysqli_query($writeConnection, $sql_shop_tracker);

			if (isset($sql_shop1)) {
				$log = new Logger("../log_data/log_shop_details.txt");
				$log->setTimestamp("D M d 'Y h.i A");
				$log->putLog("Table Name: shop_details", "Shop Id:" . $for_log_shopid, $check_log, "User Name:" . $user);
			}


			if ($id == '') {

				$sql_shops = mysqli_query($readConnection, "SELECT c.cust_name,  a.shop_name, a.cpin, a.email_id, a.contact_no, a.address,  b.location FROM shop_details AS a INNER JOIN  location_master AS b ON a.location=b.loc_id INNER JOIN cust_details AS c ON a.cust_id=c.cust_id WHERE a.shop_id='" . $shops_ids . "' AND a.point_type!='Lost'  AND   a.`status`='Y' AND b.`status`='Y'");

				if (mysqli_num_rows($sql_shops) != 0) {
					$res_shops = mysqli_fetch_assoc($sql_shops);
					if ($pin_number != '' && $res_shops['email_id'] != '' && $res_shops['email_id'] != '0' && $res_shops['cpin'] != '' && $res_shops['cpin'] != '0') {


						$cpinData = array('email_id' => $res_shops['email_id'], 'cust_name' => str_replace(" ", "_", $res_shops['cust_name']), 'shop_name' => str_replace(" ", "_", $res_shops['shop_name']), 'cpin' => $_POST['pin_number'], 'location' => $res_shops['location']);
						$jsonDataEncoded = json_encode($cpinData);
						$ch = curl_init($EmailServerUrl . "email_alert/NotificationForCpin.php");
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
						$result = curl_exec($ch);
						curl_close($ch);
					}
					if ($pin_number != '' && $contact_no != '0'  && $contact_no != '' && $res_shops['cpin'] != '' && $res_shops['cpin'] != '0') {

						$SmsPinNumber = !empty($_POST['pin_number']) ? $_POST['pin_number'] : '-';
						$SmsCustomer = !empty($res_shops['cust_name']) ? $res_shops['cust_name'] : '-';
						$SmsShopName = !empty($shop_name) ? substr($shop_name, 0, 28) : '-';
						//$SmsAddress = !EMPTY($res_shops['address'])?$res_shops['address']:'-';
						$SmsAddress = '-';

						$smsmessage = 'Dear Customer, Your 4 Digit Secured Cpin PIN is ' . trim($SmsPinNumber) . ' Transaction Authentication by using Cpin- is Activated for the below mentioned point. Customer Name: ' . trim($SmsCustomer) . ' Point Name: ' . trim($SmsShopName) . 'Address : ' . trim($SmsAddress);


						if ($contact_no != "") $mclass->sendSmsToUser($smsmessage, $contact_no, "");
					}
				}
			}
		} else {
			$redirect = '4';
		}
		if ($sql_shop1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}

		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} elseif ($pid == "m_shop1") {

		$dfd = date("d-m-Y");

		// Upload Excel File
		function findexts($filename)
		{
			$filename = strtolower($filename);
			$exts = explode(".", $filename);
			$n = count($exts) - 1;
			$exts = $exts[$n];
			return $exts;
		}


		$target_path = "../shop/";
		$entry_date = date("Y-m-d");

		$filename = $_FILES['files']['name'];

		if (move_uploaded_file($_FILES['files']['tmp_name'], $target_path . '/' . $filename)) {
			$filePath = $target_path . '/' . $filename;
			$spreadsheet = IOFactory::load($filePath);
			$worksheet = $spreadsheet->getActiveSheet();

			$full_data = "";


			foreach ($worksheet->getRowIterator(2) as $row) {
				$data_line = "";

				foreach ($row->getCellIterator() as $cell) {
					$cellValue = $cell->getValue();

					if (Date::isDateTime($cell)) {

						$date = Date::excelToDateTimeObject($cellValue);
						$cellValue = $date->format('d-m-Y');
					}

					$data_line .= $cellValue . "*%";
				}

				$full_data .= rtrim($data_line, "*%") . "^";
			}

			$line_split = explode("^", rtrim($full_data, "^"));

			//print_r($line_split); exit;


			mysqli_begin_transaction($writeConnection);

			foreach ($line_split as $key => $val) {

				$sql5 = "SELECT COALESCE( MAX( CAST( SUBSTRING( shop_id, LOCATE( 'C', shop_id ) + 1, 10 ) AS UNSIGNED ) ), 0 ) + 1 AS nos FROM shop_details";
				$qu5 = mysqli_query($writeConnection, $sql5);

				if ($qu5 && mysqli_num_rows($qu5) > 0) {
					$r5 = mysqli_fetch_assoc($qu5);
					$lshop_id = $r5['nos'];
					$shop_id = "RSC" . str_pad($lshop_id, 5, "0", STR_PAD_LEFT);
				} else {
					$shop_id = "RSC00001";
				}
				$cuval = explode('*%', $val);

				//print_r($cuval);
				if ($val != '') {

					//echo $shop_id; echo '<br>';
					$sql_cust = mysqli_query($readConnection, "SELECT a.cust_id FROM cust_details as a join client_details AS b on a.client_id=b.client_id where a.cust_name='" . $cuval[1] . "' and b.client_name='" . $cuval[0] . "' and a.status='Y' and b.status='Y'");
					$res_cust = mysqli_fetch_assoc($sql_cust);
					$sql_location = mysqli_query($readConnection, "select a.loc_id from location_master as a inner join radiant_location as b 
 where a.location='" . $cuval[4] . "' AND a.state='" . $cuval[3] . "' AND b.status='Y' AND a.status='Y'");
					$res_location = mysqli_fetch_assoc($sql_location);
					if ($cuval[6] != '' && $cuval[10] != '' && $cuval[11] != '') {


						$sql = mysqli_query($writeConnection, "INSERT INTO `shop_details` (`shop_id`, `cust_id`, `cust_div`, `loi_id`, `sact_date`, `sdeact_date`, `loi_date`, `shop_type`, `customer_code`, `hierarchy_code`, `subcustomer_code`, `loc_code`, `shop_code`, `shop_name`, `address`, `pincode`, `sol_id`, `div_code`, `phone`, `location`, `state`, `contact_name`, `contact_no`, `service_type`, `cash_limit`, `dep_bank`, `acc_id`, `pickup_type`, `feasibility`, `point_type`, `remarks`, `update_by`, `update_date`, `status`,`qr_status`,`rbicompliance`,`disposal_location`,`tier`,`qr_json`,`show_slip`,`process`) VALUES ('" . $shop_id . "', '" . mysqli_real_escape_string($writeConnection, $res_cust['cust_id']) . "',  '" . mysqli_real_escape_string($writeConnection, $cuval[2]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[5]) . "', '0000-00-00', '0000-00-00', '" . date('Y-m-d', strtotime($cuval[6])) . "', 'Shop', '" . mysqli_real_escape_string($writeConnection, $cuval[7]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[13]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[14]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[9]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[8]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[10]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[11]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[12]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[15]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[16]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[17]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[4]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[3]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[18]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[19]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[20]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[21]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[22]) . "', '0', '" . mysqli_real_escape_string($writeConnection, $cuval[24]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[25]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[26]) . "', '" . mysqli_real_escape_string($writeConnection, $cuval[27]) . "','" . $user . "','" . $update_date . "','R','" . mysqli_real_escape_string($writeConnection, $cuval[31]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[32]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[33]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[34]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[35]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[36]) . "','" . mysqli_real_escape_string($writeConnection, $cuval[30]) . "') ");

						// $sql2v = mysqli_query($writeConnection,"INSERT INTO `shop_details1` (`shop_id`, `cust_id`, `cust_div`, `loi_id`, `sact_date`, `sdeact_date`, `loi_date`, `shop_type`, `customer_code`, `hierarchy_code`, `subcustomer_code`, `loc_code`, `shop_code`, `shop_name`, `address`, `pincode`, `sol_id`, `div_code`, `phone`, `location`, `state`, `contact_name`, `contact_no`, `service_type`, `cash_limit`, `dep_bank`, `acc_id`, `pickup_type`, `feasibility`, `point_type`, `remarks`, `update_by`, `update_date`, `status`,`qr_status`,`rbicompliance`,`disposal_location`,`tier`,`qr_json`,`show_slip`) VALUES ('".$shop_id."', '".mysqli_real_escape_string($writeConnection,$res_cust['cust_id'])."',  '".mysqli_real_escape_string($writeConnection,$cuval[2])."', '".mysqli_real_escape_string($writeConnection,$cuval[5])."', '0000-00-00', '0000-00-00', '".date('Y-m-d', strtotime($cuval[6]))."', 'Shop', '".mysqli_real_escape_string($writeConnection,$cuval[7])."', '".mysqli_real_escape_string($writeConnection,$cuval[13])."', '".mysqli_real_escape_string($writeConnection,$cuval[14])."', '".mysqli_real_escape_string($writeConnection,$cuval[9])."', '".mysqli_real_escape_string($writeConnection,$cuval[8])."', '".mysqli_real_escape_string($writeConnection,$cuval[10])."', '".mysqli_real_escape_string($writeConnection,$cuval[11])."', '".mysqli_real_escape_string($writeConnection,$cuval[12])."', '".mysqli_real_escape_string($writeConnection,$cuval[15])."', '".mysqli_real_escape_string($writeConnection,$cuval[16])."', '".mysqli_real_escape_string($writeConnection,$cuval[17])."', '".mysqli_real_escape_string($writeConnection,$cuval[4])."', '".mysqli_real_escape_string($writeConnection,$cuval[3])."', '".mysqli_real_escape_string($writeConnection,$cuval[18])."', '".mysqli_real_escape_string($writeConnection,$cuval[19])."', '".mysqli_real_escape_string($writeConnection,$cuval[20])."', '".mysqli_real_escape_string($writeConnection,$cuval[21])."', '".mysqli_real_escape_string($writeConnection,$cuval[22])."', '0', '".mysqli_real_escape_string($writeConnection,$cuval[24])."', '".mysqli_real_escape_string($writeConnection,$cuval[25])."', '".mysqli_real_escape_string($writeConnection,$cuval[26])."', '".mysqli_real_escape_string($writeConnection,$cuval[27])."','".$user."','".$update_date."','R','".mysqli_real_escape_string($writeConnection,$cuval[30])."','".mysqli_real_escape_string($writeConnection,$cuval[31])."','".mysqli_real_escape_string($writeConnection,$cuval[32])."','".mysqli_real_escape_string($writeConnection,$cuval[33])."','".mysqli_real_escape_string($writeConnection,$cuval[34])."','".mysqli_real_escape_string($writeConnection,$cuval[35])."') ");

						mysqli_commit($writeConnection);
					}
				}
			}
			mysqli_autocommit($writeConnection, true);
		}
		// mysqli_close($conn);
		header("Location:../?pid=m_shop&nav=1");
	}



	//CE Mapping
	else if ($pid == 'ce_map' || $pid=='rce_mapping') {
		$shop_code = mysqli_real_escape_string($writeConnection, $_POST['shop_code']);
		$primary_ce = mysqli_real_escape_string($writeConnection, $_POST['primary_ce']);
		$secondary = mysqli_real_escape_string($writeConnection, $_POST['secondary']);
		$additional = mysqli_real_escape_string($writeConnection, $_POST['additional']);
		if ($id != '') {
			echo "UPDATE `shop_cemap` SET `shop_id`='$shop_code', `pri_ce`='$primary_ce', `sec_ce`='$secondary', `add_ce`='$additional', `update_by`='$user', `update_date`='$coll_date' WHERE map_id='$id'";
			$sql_shop_map = mysqli_query($writeConnection, "UPDATE `shop_cemap` SET `shop_id`='$shop_code', `pri_ce`='$primary_ce', `sec_ce`='$secondary', `add_ce`='$additional', `update_by`='$user', `update_date`='$coll_date' WHERE map_id='$id'");

			$sql_cess = mysqli_query($readConnection, "SELECT mobile1 FROM radiant_ce WHERE ce_id='" . $primary_ce . "' AND wstatus='Active'");
			$res_cess = mysqli_fetch_assoc($sql_cess);

			$trans_upd = mysqli_query($writeConnection, "UPDATE daily_trans SET staff_id='" . $primary_ce . "', mobile1='" . $res_cess['mobile1'] . "' WHERE status='Y' AND pickup_date='" . date('Y-m-d') . "' AND pickup_code='" . $shop_code . "'");

			if ($sql_shop_map == 1) {
				$nav = 3;
			} else {
				$nav = 2;
			}
		} else {
			$sql1 = mysqli_query($readConnection, "SELECT shop_id FROM shop_cemap WHERE shop_id='" . $shop_code . "' AND status='Y'");
			if (mysqli_num_rows($sql1) == 0) {
				if (!empty($primary_ce)) {
					$sql_shop_map = mysqli_query($writeConnection, "INSERT INTO `shop_cemap` (`shop_id`, `pri_ce`, `sec_ce`, `add_ce`, `update_by`, `update_date`, `status`) VALUES ('$shop_code', '$primary_ce', '$secondary', '$additional', '$user', '$coll_date', 'Y')");
				}
				if ($sql_shop_map == 1) {
					$nav = 1;
				} else {
					$nav = 2;
				}
			} else {
				$nav = 4;
			}
		}

		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $nav);
	} else if ($pid == 'ce_unmap' || $pid=='rce_unmap') {
		$sql_shop_map = mysqli_query($writeConnection, "UPDATE `shop_cemap` SET `pri_ce`='', `sec_ce`='', `add_ce`='', `update_by`='$user', `update_date`='$coll_date', status='N' WHERE map_id='$id'");
		if ($sql_shop_map) {
			// mysqli_close($conn);
			header('Location:../?pid=ce_map&nav=5' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=ce_map&nav=6' . $nav);
		}
	}



	//Change Password
	if ($pid == 'change_password') {
		$new_pass = $_POST['new_pass'];
		$cnf_pass = $_POST['cnf_pass'];
		$old_pass = $_POST['old_pass'];
		$sql = mysqli_query("SELECT password from login where user_name='" . $user_name . "' and password='" . $old_pass . "' ");
		if (mysqli_num_rows($sql) > 0) {
			$sql = "UPDATE login set password='" . $new_pass . "' where user_name='" . $user_name . "' and password='" . $old_pass . "' ";
			$sql_qur = mysqli_query($sql);
			// mysqli_close($conn);
			header('Location:index.php?pid=' . $pid . '&nav=2_1_1');
		} else {
			// mysqli_close($conn);
			header('Location:index.php?pid=' . $pid . '&nav=2_2_1');
		}
	}




	//CE Replace
	else if ($pid == 'ce_rep') {
		$current_ce = mysqli_real_escape_string($readConnection, $_POST['current_ce']);
		$replace_ce = mysqli_real_escape_string($readConnection, $_POST['replace_ce']);
		$total_rec = 0;
		$replace_str = "START";
		$typ = array('pri_ce' => 'P', 'sec_ce' => 'S', 'add_ce' => 'A');

		if (!empty($_POST['ce_deatils'])) {
			foreach ($_POST['ce_deatils'] as $key => $val) {
				$ce_deatils = explode('/', $val);
				$shop_map = mysqli_query($readConnection, "SELECT pri_ce, sec_ce, add_ce FROM shop_cemap WHERE shop_id='" . $ce_deatils[0] . "' AND map_id=" . $ce_deatils[1] . " and status='Y'");
				$res_map = mysqli_fetch_object($shop_map);
				$sql_cemap = mysqli_query($writeConnection, "UPDATE shop_cemap SET `" . $ce_deatils[2] . "`='" . $replace_ce . "' WHERE shop_id='" . $ce_deatils[0] . "' AND map_id=" . $ce_deatils[1] . " AND status='Y'");
				$sql_ces = mysqli_query($writeConnection, "SELECT mobile1 FROM radiant_ce WHERE ce_id='" . $replace_ce . "'");
				$res_ces = mysqli_fetch_assoc($sql_ces);

				$trans_upd = mysqli_query($writeConnection, "UPDATE daily_trans SET staff_id='" . $replace_ce . "', mobile1='" . $res_ces['mobile1'] . "' WHERE staff_id='" . $current_ce . "' AND status='Y' AND pickup_date='" . date('Y-m-d') . "' AND pickup_code='" . $ce_deatils['0'] . "'");

				if ($ce_deatils[2] == 'add_ce') {
					$add_ce1 = str_replace($current_ce, $replace_ce, $res_map->$ce_deatils[2]);
				} else {
					$add_ce1 = $replace_ce;
				}

				$replace_str .= "," . $ce_deatils[1] . "(" . $typ[$ce_deatils[2]] . ")=" . $res_map->$ce_deatils[2] . "->" . $add_ce1;
				$total_rec++;
			}
		}

		$replace_str .= ",END";
		$sql_ce_replace = mysqli_query($writeConnection, "INSERT INTO ce_replace (`replace_date`, `replace_by`, `from_ce`, `to_ce`, `replace_str`, `status`) values ('" . $coll_date . "','" . $user . "','" . $current_ce . "','" . $replace_ce . "','" . $replace_str . "','Y')");
		// mysqli_close($conn);
		header("Location:../?pid=$pid&nav=1&cur_ce=$current_ce&rep_ce=$replace_ce&total=$total_rec&con=1");
	} else if ($pid == 'bdraw') {
		if ($_POST['withdraw_date'] != '') {
			$withdraw_date = date('Y-m-d', strtotime($_POST['withdraw_date']));
		}
		$ce_ids = $_POST['ce_ids'];
		$bank_acc = $_POST['bank_acc'];
		$chequ_no = $_POST['chequ_no'];
		$check_amount = $_POST['check_amount'];
		$withdraw_time = $_POST['withdraw_time'];
		if ($id != '') {
			$sql_widthdraw = mysqli_query($writeConnection, "UPDATE daily_withdraw SET draw_date='$withdraw_date',acc_id=" . $bank_acc . ",cheque_no='" . $chequ_no . "',cheque_amt=" . $check_amount . ",staff_id='" . $ce_ids . "',draw_time='" . $withdraw_time . "',entry_by='" . $user . "',entry_date='" . $coll_date . "' WHERE draw_id='$id'");
		} else {
			$sql_widthdraw = mysqli_query($writeConnection, "INSERT INTO `daily_withdraw` (`draw_date`, `acc_id`, `cheque_no`, `cheque_amt`, `staff_id`, `draw_time`, `entry_by`, `entry_date`, `status`) VALUES ('$withdraw_date', '$bank_acc', '$chequ_no', '$check_amount', '$ce_ids', '$withdraw_time', '$user', '$coll_date', 'Y')");
		}
		if ($sql_widthdraw == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}
		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'amends') {
		$trans_id = $_POST['trans_id'];
		$prev_rec = $_POST['prev_rec'];
		$prev_amount = $_POST['prev_amount'];
		if ($_POST['amd_date'] != '') {
			$amd_date = date('Y-m-d', strtotime($_POST['amd_date']));
		}
		$amend_rec = $_POST['amend_rec'];
		$amend_amount = $_POST['amend_amount'];
		$diff_amt = $_POST['diff_amt'];
		if ($_POST['fund_date'] != '') {
			$fund_date = date('Y-m-d', strtotime($_POST['fund_date']));
		}
		$fund_amount = $_POST['fund_amount'];
		$init_by = $_POST['init_by'];
		$fund_remarks = $_POST['fund_remarks'];
		if ($id != '') {
			$sql_amend = "UPDATE `daily_amends` SET `trans_id`='$trans_id', `amend_date`='$amd_date', `prev_rec`='$prev_rec', `prev_amount`='$prev_amount', `amend_rec`='$amend_rec', `amend_amount`='$amend_amount', `diff_amount`='$diff_amt', `fund_date`='$fund_date', `fund_amount`='$fund_amount', `init_by`='$init_by', `fund_remarks`='$fund_remarks', `update_by`='$user', `update_date`='" . date('d-M-Y h:i:s A') . "' WHERE amend_id='$id'";
		} else {
			$sql_amend = "INSERT INTO `daily_amends` (`trans_id`, `amend_date`, `prev_rec`, `prev_amount`, `amend_rec`, `amend_amount`, `diff_amount`, `fund_date`, `fund_amount`, `init_by`, `fund_remarks`, `update_by`, `update_date`, `status`) VALUES ('$trans_id' ,'$amd_date', '$prev_rec', '$prev_amount', '$amend_rec', '$amend_amount', '$diff_amt', '$fund_date', '$fund_amount', '$init_by' ,'$fund_remarks', '$user', '" . date('d-M-Y h:i:s A') . "', 'Y')";
		}
		$sql_amend1 = mysqli_query($writeConnection, $sql_amend);
		if ($sql_amend1 == 1) {
			if ($id != '') {
				$redirect = '3';
			} else {
				$redirect = '1';
			}
		} else {
			$redirect = '2';
		}
		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'fcheque') {
		if ($_POST['trans_date'] != '') {
			$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));
		}
		$fund_cust = explode(': ', $_POST['group_name']);
		$total_fund = $_POST['total_fund'];
		$fund_from = $_POST['fund_from'];
		$fund_to = $_POST['fund_to'];
		if ($_POST['fund_date'] != '') {
			$fund_date = date('Y-m-d', strtotime($_POST['fund_date']));
		}
		$cheque_no = $_POST['cheque_no'];
		$fund_amount = $_POST['fund_amount'];
		$fund_type = $_POST['fund_type'];
		$fund_remarks = $_POST['fund_remarks'];
		if ($id != '') {
			$query_fund_ch = "UPDATE `funding_cheques` SET `fund_from`='$fund_from', `fund_to`='$fund_to', `cheque_date`='$fund_date', `cheque_no`='$cheque_no', `fund_amount`='$fund_amount', `fund_type`='$fund_type', `reasons`='$fund_remarks', `update_time`='" . date('d-M-Y h:i:s A') . "', `update_by`='$user', `status`='Y' WHERE fund_id='$id'";
		} else {
			$query_fund_ch = "INSERT INTO `funding_cheques` (`trans_date`, `fund_cust`, `total_fund`, `fund_from`, `fund_to`, `cheque_date`, `cheque_no`, `fund_amount`, `fund_type`, `reasons`, `update_time`, `update_by`, `status`) VALUES ('$trans_date', '" . $fund_cust[1] . "', '$total_fund', '$fund_from', '$fund_to', '$fund_date', '$cheque_no', '$fund_amount', '$fund_type', '$fund_remarks', '" . date('d-M-Y h:i:s A') . "', '$user', 'Y')";
		}

		$query_fund_ch1 = mysqli_query($writeConnection, $query_fund_ch);
		if ($query_fund_ch1 == 1) {
			if ($id != '') {
				$redirect = '3&id=' . $id;
			} else {
				$last_imsert_id = mysqli_insert_id($writeConnection);
				$redirect = '1&id' . $last_imsert_id;
			}
		} else {
			$redirect = '2';
		}
		// mysqli_close($conn);
		header('Location:../?pid=' . $pid . '&nav=' . $redirect);
	} else if ($pid == 'dedit') {

		$mclass = new sendSms();
		$trans_id = $_POST['trans_id'];
		$pickup_amount = $_POST['pickup_amount'];
		$type = $_POST['type'];
		$ce_id = $_POST['ce_id'];
		$sms_sent = $_POST['sms_sent'];
		$client_code = $_POST['client_code'];
		$trans_ids = $_POST['trans_ids'];
		$total_amt = 0;
		$ssms = "";
		if (isset($client_code)) {
			foreach ($client_code as $key1 => $val1) {
				$final_cc .= $val1 . '-' . $pickup_amount[$key1] . ',';
				$total_amt += $pickup_amount[$key1];
			}
			$final_cc1 = substr($final_cc, 0, -1);
			$sql = "UPDATE daily_trans SET pickup_amount=" . $total_amt . ", type='" . ucwords($type) . "', client_code='" . $final_cc1 . "' WHERE trans_id='" . $trans_ids . "' AND status='Y'";
			$sql1 = mysqli_query($writeConnection, $sql);
		} else {
			foreach ($pickup_amount as $key => $val) {



				$sql = "UPDATE daily_trans SET pickup_amount=" . $val . ", type='" . ucwords($type[$key]) . "' WHERE trans_id='" . $key . "' AND status='Y'";
				$sql1 = mysqli_query($writeConnection, $sql);
			}
		}
		if ($sql1 == 1) {
			if ($sms_sent == "Yes" && $pickup_amount > 0) {
				$sql3 = "select ce_name,mobile1,mobile2 from radiant_ce where ce_id='" . $ce_id . "' and status='Y'";
				$qu3 = mysqli_query($readConnection, $sql3);
				while ($r3 = mysqli_fetch_array($qu3)) {
					$pname = $r3['ce_name'];
					$pmobile1 = $r3['mobile1'];
					$pmobile2 = $r3['mobile2'];
				}

				$sql2 = "select trans_no,location,pickup_code,pickup_name from daily_trans where trans_id='" . $trans_id . "' and status='Y'";
				$qu2 = mysqli_query($readConnection, $sql2);
				while ($r2 = mysqli_fetch_array($qu2)) {
					$trans_no = $r3['trans_no'];
					$location = $r3['location'];
					$pickup_code = $r3['pickup_code'];
					$pickup_name = $r3['pickup_name'];
				}


				$SmsTransNo = !empty($trans_no) ? $trans_no : '-';
				$SmsLocation = !empty($location) ? $location : '-';
				$SmsPointCode = !empty($pickup_code) ? $pickup_code : '-';
				$SmsShopName = !empty($pickup_name) ? substr($pickup_name, 0, 28) : '-';
				$SmsAmount = !empty($pickup_amount) ? $pickup_amount : '-';
				$SmsCeName = !empty($pname) ? $pname : '-';
				$SmsCeId = !empty($ce_id) ? $ce_id : '-';

				$smsmessage = 'Request, Trans ID: ' . trim($SmsTransNo) . ', Location: ' . trim($SmsLocation) . ', Shop Code: ' . trim($SmsPointCode) . ', Shop Name: ' . trim($SmsShopName) . ', Amount: ' . trim($SmsAmount) . ', CE Name: ' . trim($SmsCeName) . ', CE ID: ' . trim($SmsCeId);



				//$smsmessage="Request, Trans ID: ".$trans_no.", Location: ".$location.", Shop Code: ".$pickup_code.", Shop Name: ".$pickup_name.", ".$type." Amount: ".$pickup_amount.", CE Name: ".$pname.", CE ID: ".$ce_id;


				$mclass->sendSmsToUser($smsmessage, $pmobile1, "");
				$ssms = "1";
			}
			// mysqli_close($conn);
			header("Location:../?pid=$pid&ssms=$ssms&nav=1");
		}
	} else if ($pid == 'dedit_m') {

		$mclass = new sendSms();
		$trans_id = $_POST['id'];
		$location = $_POST['location'];
		$client_name = $_POST['client_name'];
		$cust_name = $_POST['cust_name'];
		$crm_code = $_POST['crm_code'];
		$contact_person = $_POST['contact_person'];
		$shop_mobile = $_POST['shop_mobile'];
		$ptp_date = $_POST['ptp_date'];
		$request_date = $_POST['request_date'];
		$pickup_amount = $_POST['pickup_amount'];
		$pickup_type = $_POST['pickup_type'];
		$client_call_center_number = $_POST['client_call_center_number'];
		$client_failure_notice_mobile_number = $_POST['client_failure_notice_mobile_number'];
		$address = $_POST['address'];

		$sql_update = mysqli_query($writeConnection,"UPDATE daily_trans_m SET `location`='" . $location . "', `client_name`='" . $client_name . "', `cust_name`='" . $cust_name . "', `crm_code`='" . $crm_code . "', `contact_person`='" . $contact_person . "', `address`='" . $address . "', `shop_mobile`='" . $shop_mobile . "', `ptp_date`='" . $ptp_date . "', `request_date`='" . $request_date . "', `pickup_amount`='" . $pickup_amount . "', `pickup_type`='" . $pickup_type . "', `client_call_center_number`='" . $client_call_center_number . "', `client_failure_notice_mobile_number`='" . $client_failure_notice_mobile_number . "' WHERE `trans_id`='" . $trans_id . "'");

		if ($sql_update == 1) {
			if ($sms_sent == "Yes" && $pickup_amount > 0) {
				$sql3 = "select ce_name,mobile1,mobile2 from radiant_ce where ce_id='" . $ce_id . "' and status='Y'";
				$qu3 = mysqli_query($writeConnection,$sql3);
				while ($r3 = mysqli_fetch_array($qu3)) {
					$pname = $r3['ce_name'];
					$pmobile1 = $r3['mobile1'];
					$pmobile2 = $r3['mobile2'];
				}

				$sql2 = "select trans_no,location,pickup_code,pickup_name from daily_trans where trans_id='" . $trans_id . "' and status='Y'";
				$qu2 = mysqli_query($writeConnection,$sql2);
				while ($r2 = mysqli_fetch_array($qu2)) {
					$trans_no = $r3['trans_no'];
					$location = $r3['location'];
					$pickup_code = $r3['pickup_code'];
					$pickup_name = $r3['pickup_name'];
				}




				$SmsTransNo = !empty($trans_no) ? $trans_no : '-';
				$SmsLocation = !empty($location) ? $location : '-';
				$SmsPointCode = !empty($pickup_code) ? $pickup_code : '-';
				$SmsShopName = !empty($pickup_name) ? substr($pickup_name, 0, 28) : '-';
				$SmsAmount = !empty($pickup_amount) ? $pickup_amount : '-';
				$SmsCeName = !empty($pname) ? $pname : '-';
				$SmsCeId = !empty($ce_id) ? $ce_id : '-';

				$smsmessage = 'Request, Trans ID: ' . trim($SmsTransNo) . ', Location: ' . trim($SmsLocation) . ', Shop Code: ' . trim($SmsPointCode) . ', Shop Name: ' . trim($SmsShopName) . ', Amount: ' . trim($SmsAmount) . ', CE Name: ' . trim($SmsCeName) . ', CE ID: ' . trim($SmsCeId);


				//$smsmessage="Request, Trans ID: ".$trans_no.", Location: ".$location.", Shop Code: ".$pickup_code.", Shop Name: ".$pickup_name.", ".$type." Amount: ".$pickup_amount.", CE Name: ".$pname.", CE ID: ".$ce_id;

				$mclass->sendSmsToUser($smsmessage, $pmobile1, "");
				$ssms = "1";
			}
			$nav = 1;
		} else {
			$nav = 2;
		}
		// mysqli_close($conn);
		header("Location:../?pid=$pid&ssms=$ssms&nav=" . $nav);
	}


	//Evening Beat Transaction
	elseif ($pid == "evetran") {

		$sele_opt1 = $_POST['sele_opt1'];
		$shop_id = $_POST['shop_ids'];
		$regionss = $_POST['regionss'];
		$entry_date = date("Y-m-d");
		$ddate = date("ymd");

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);

		foreach ($shop_id as $key => $val) {
			$chk_qry = mysqli_query($writeConnection, "SELECT pickup_code from daily_trans where pickup_date='" . $entry_date . "' and pickup_code='" . $key . "' and trans_no like '%-E' and status='Y'");
			if (mysqli_num_rows($chk_qry) == 0) {

				$pri_ce = "";
				$sec_ce = "";
				$sql2 = "SELECT pri_ce, sec_ce FROM shop_cemap WHERE shop_id='" . $key . "' AND status='Y'";
				$qu2 = mysqli_query($writeConnection, $sql2);
				while ($r2 = mysqli_fetch_array($qu2)) {
					$pri_ce = $r2['pri_ce'];
					$sec_ce = $r2['sec_ce'];
				}

				//Get Primary CE Mobile
				$pmobile1 = "";
				$pmobile2 = "";
				$sql3 = "SELECT ce_name, mobile1, mobile2 FROM radiant_ce WHERE ce_id='" . $pri_ce . "' AND status='Y'";
				$qu3 = mysqli_query($writeConnection, $sql3);
				while ($r3 = mysqli_fetch_array($qu3)) {
					$pname = $r3['ce_name'];
					$pmobile1 = $r3['mobile1'];
					$pmobile2 = $r3['mobile2'];
				}

				$sql_shop_details = mysqli_query($writeConnection, "SELECT a.shop_id,a.shop_name, a.address, a.state, b.location, c.cust_name, d.client_name  FROM shop_details AS a JOIN location_master AS b ON a.location=b.loc_id JOIN cust_details AS c ON c.cust_id=a.cust_id JOIN client_details AS d ON c.client_id=d.client_id WHERE a.shop_id='" . $key . "' and a.status='Y' and a.point_type='Active' and b.status='Y' and c.status='Y' and d.status='Y'");
				$res_shop_details = mysqli_fetch_assoc($sql_shop_details);

				$even_date = $ddate . "-E";


				$query_eve = "INSERT INTO daily_trans (`trans_no`, `region`, `location`, `client_name`, `cust_name`, `pickup_code`, `pickup_name`, `type`, `client_code`, staff_id, mobile1, mobile2, sent_by, sms_sent, `pickup_date`, `status`) 
SELECT (CASE 
WHEN (ceil(count(trans_id)) >= '1' && ceil(count(trans_id)) < '9' ) 
THEN concat('0000',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '9' && ceil(count(trans_id)) < '99')
THEN concat('000',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '99' && ceil(count(trans_id)) < '999') 
THEN concat('00',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '999' && ceil(count(trans_id)) < '9999' ) 
THEN concat('0',count(trans_id)+1,'." . $even_date . "')
WHEN (ceil(count(trans_id)) >= '9999' && ceil(count(trans_id)) < '99999' )
THEN concat(count(trans_id)+1,'.','" . $even_date . "')
WHEN (ceil(count(trans_id)) = 0  )
THEN concat('00001','.','" . $even_date . "')
END), '" . $regionss[$key] . "', '" . $res_shop_details['location'] . "', '" . $res_shop_details['client_name'] . "', '" . $res_shop_details['cust_name'] . "', '" . $key . "', '" . $val . "', 'Pickup', 'Beat', '" . $pri_ce . "', '" . $pmobile1 . "', '" . $pmobile2 . "','" . $user . "','" . $time . "', '" . date('Y-m-d') . "', 'Y'
from daily_trans where pickup_date='" . $entry_date . "' ";


				$sql_evetran = mysqli_query($writeConnection, $query_eve);
			}
		}

		header("Location:../?pid=$pid&nav=2_1");
	}



	//Transactions Backup & Restore Details
	elseif ($pid == "backlogs") {
		if ($_POST['submit'] == 'sub') {
			$dt = date("Y-m-d", strtotime($_POST['backup_date']));
			$sql1 = "SELECT * FROM daily_trans WHERE pickup_date='" . $dt . "'";
			$qu1 = mysqli_query($writeConnection, $sql1);
			$n1 = mysqli_num_rows($qu1);
			while ($r1 = mysqli_fetch_array($qu1)) {
				$qu2 = mysqli_query($writeConnection, "INSERT INTO `daily_trans_backup` (`trans_id`, `trans_no`, `region`, `location`, `client_name`, `cust_name`, `pickup_code`, `pickup_name`, `pickup_amount`, `type`, `client_code`, `staff_id`, `mobile1`, `mobile2`, `sent_by`, `sms_sent`, `pickup_date`, `status`) VALUES (" . $r1['trans_id'] . ", '" . $r1['trans_no'] . "', '" . $r1['region'] . "', '" . $r1['location'] . "', '" . $r1['client_name'] . "', '" . $r1['cust_name'] . "', '" . $r1['pickup_code'] . "', '" . $r1['pickup_name'] . "', " . $r1['pickup_amount'] . ", '" . $r1['type'] . "', '" . $r1['client_code'] . "', '" . $r1['staff_id'] . "',  '" . $r1['mobile1'] . "', '" . $r1['mobile2'] . "', '" . $r1['sent_by'] . "', '" . $r1['sms_sent'] . "', '" . $r1['pickup_date'] . "', '" . $r1['status'] . "')");
			}
			$qu3 = mysqli_query($writeConnection, "delete from daily_trans where pickup_date='" . $dt . "'");
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_1&dt=" . $_POST['backup_date'] . "&rec=" . $n1);
		}
		if ($_POST['submit'] == 'sub1') {
			$dt = date("Y-m-d", strtotime($_POST['restore_date']));
			$sql1 = "SELECT * FROM daily_trans_backup WHERE pickup_date='" . $dt . "'";
			$qu1 = mysqli_query($writeConnection, $sql1);
			$n1 = mysqli_num_rows($qu1);
			while ($r1 = mysqli_fetch_array($qu1)) {

				$qu2 = mysqli_query($writeConnection, "INSERT INTO `daily_trans` (`trans_id`, `trans_no`, `region`, `location`, `client_name`, `cust_name`, `pickup_code`, `pickup_name`, `pickup_amount`, `type`, `client_code`, `staff_id`, `mobile1`, `mobile2`, `sent_by`, `sms_sent`, `pickup_date`, `status`) VALUES (" . $r1['trans_id'] . ", '" . $r1['trans_no'] . "', '" . $r1['region'] . "', '" . $r1['location'] . "', '" . $r1['client_name'] . "', '" . $r1['cust_name'] . "', '" . $r1['pickup_code'] . "', '" . $r1['pickup_name'] . "', " . $r1['pickup_amount'] . ", '" . $r1['type'] . "', '" . $r1['client_code'] . "', '" . $r1['staff_id'] . "', '" . $r1['mobile1'] . "', '" . $r1['mobile2'] . "', '" . $r1['sent_by'] . "', '" . $r1['sms_sent'] . "', '" . $r1['pickup_date'] . "', '" . $r1['status'] . "')");
			}
			$qu3 = mysqli_query($writeConnection, "delete from daily_trans_backup where pickup_date='" . $dt . "'");
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_2&dt=" . $_POST['restore_date'] . "&rec=" . $n1);
		}
	} elseif ($_POST['pid'] == 'bsms1') {
		$mclass = new sendSms();
		$type = $_POST['type'];
		$region = $_POST['region'];
		$first = $_POST['first'];
		$second = $_POST['second'];
		$entry_date = date("Y-m-d");
		if ($type == "CE") {
			$no_sms = 0;
			$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
			$time = date('h:i:s A', $time_now);
			$sqls = "INSERT INTO sms_log (sent_type,user_id,sent_date,sent_time,no_sms,status) VALUES ('Personal CE SMS','" . $user . "','" . $entry_date . "','" . $time . "',0,'Y')";
			$qus = mysqli_query($writeConnection, $sqls);
			$sent_id = mysqli_insert_id();

			$location = $_POST['location'];
			$locations = "";
			$count = count($location);
			$ce_count = 0;
			for ($i = 0; $i < $count; $i++) {
				//Collect States
				if ($location[$i] !== "on") {
					$smobile1 = "";
					$smobile2 = "";
					$cmobile1 = "";
					$cmobile2 = "";
					$sqls = "SELECT ce_id, ce_name, mobile1, mobile2 FROM radiant_ce WHERE  loc_id='" . $location[$i] . "' AND status='Y'";
					$qus = mysqli_query($writeConnection, $sqls);
					while ($rs = mysqli_fetch_array($qus)) {
						$ce_name = $rs['ce_name'];
						$ce_id1 = substr($rs['ce_id'], 3, 7);
						$sqlq = "SELECT location FROM location_master WHERE loc_id='" . $location[$i] . "' ";
						$quq = mysqli_query($readConnection, $sqlq);
						while ($rq = mysqli_fetch_array($quq)) {
							$ce_location = $rq['location'];
						}
						$smobile1 = $rs['mobile1'];
						$smobile2 = $rs['mobile2'];

						$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
						$time = date('h:i:s A', $time_now);
						$from1 = date("d-M-Y");
						$from = $from1 . ", " . $time;

						$smsmessage = "CE Name: $ce_name, CE ID: $ce_id1, CE Location: $ce_location, Primary Mobile: $smobile1, Secondary Mobile: $smobile2, Please conform to your DME through phone that your details are correct or incorrect";

						//SMS comments
						if ($smobile1 != "" && $offline == "No" && $first == "ok") {
							$mclass->sendSmsToUser($smsmessage, $smobile1, "");
							$cmobile1 = $smobile1;
							$no_sms++;
						}
						//CE Second Number 
						if ($smobile2 != "" && $offline == "No" && $second == "ok") {
							$mclass->sendSmsToUser($smsmessage, $smobile2, "");
							$cmobile2 = $smobile2;
							$no_sms++;
						}

						$qt1 = mysqli_query($writeConnection, "INSERT INTO sms_trans (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status) VALUES(" . $sent_id . ",'" . $rs['ce_id'] . "','" . $cmobile1 . "','" . $cmobile2 . "','" . $smsmessage . "','" . $time . "','Sent','')");

						$ce_count++;
					}
				}
			}
		} elseif ($type == "Staff") {
			$no_sms = 0;
			$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
			$time = date('h:i:s A', $time_now);
			$sqls = "insert into sms_log (sent_type,user_id,sent_date,sent_time,no_sms,status) values ('Personal Staff SMS','" . $user . "','" . $entry_date . "','" . $time . "',0,'Y')";
			$qus = mysqli_query($writeConnection, $sqls);
			$sent_id = mysqli_insert_id();

			$design = $_POST['design'];
			$designs = "";
			$count = count($design);
			$ce_count = 0;
			for ($i = 0; $i < $count; $i++) {
				//Collect States
				if ($design[$i] !== "on") {
					$smobile1 = "";
					$smobile2 = "";
					$cmobile1 = "";
					$cmobile2 = "";
					$sqls1 = "SELECT emp_id FROM radiant_staff INNER JOIN radiant_location ON radiant_staff.loc_id = radiant_location.location_id INNER JOIN region_master ON radiant_location.region_id = region_master.region_id WHERE region_master.region_id = '" . $region . "' AND radiant_staff.design = '" . $design[$i] . "' AND radiant_staff.status = 'Y' and radiant_location.status='Y'";
					$qus1 = mysqli_query($writeConnection, $sqls1);
					while ($rs1 = mysqli_fetch_array($qus1)) {
						$sqls = "select emp_id, emp_name, mobile1, mobile2, loc_id from radiant_staff where emp_id='" . $rs1['emp_id'] . "' and status='Y'";
						$qus = mysqli_query($writeConnection, $sqls);
						while ($rs = mysqli_fetch_array($qus)) {
							$staff_name = $rs['emp_name'];
							$staff_id = $rs['emp_id'];
							$sqlq = "select location from location_master where loc_id='" . $rs['loc_id'] . "' ";
							$quq = mysqli_query($readConnection, $sqlq);
							while ($rq = mysqli_fetch_array($quq)) {
								$staff_location = $rq['location'];
							}
							$smobile1 = $rs['mobile1'];
							$smobile2 = $rs['mobile2'];

							$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
							$time = date('h:i:s A', $time_now);
							$from1 = date("d-M-Y");
							$from = $from1 . ", " . $time;

							$smsmessage = "Staff Name: $staff_name, Staff ID: $staff_id, Location: $staff_location, Primary Mobile: $smobile1, Secondary Mobile: $smobile2, Please conform to your Regional DME through phone that your details are correct or incorrect";

							//SMS Comments							
							if ($smobile1 != "" && $offline == "No" && $first == "ok") {
								$mclass->sendSmsToUser($smsmessage, $smobile1, "");
								$cmobile1 = $smobile1;
								$no_sms++;
							}
							//CE Second Number 
							if ($smobile2 != "" && $offline == "No" && $second == "ok") {
								$mclass->sendSmsToUser($smsmessage, $smobile2, "");
								$cmobile2 = $smobile2;
								$no_sms++;
							}


							$qt1 = mysqli_query($writeConnection, "insert into sms_trans (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status) values(" . $sent_id . ",'" . $rs1['emp_id'] . "','" . $cmobile1 . "','" . $cmobile2 . "','" . $smsmessage . "','" . $time . "','Sent','')");

							$ce_count++;
						}
					}
				}
			}
		}
		$qus = mysqli_query($writeConnection, "update sms_log set no_sms=" . $no_sms . " where sent_id='" . $sent_id . "' ");
		// mysqli_close($conn);
		header("Location:../?pid=$pid&nav=2_1&stype=$type&nos=$ce_count&region=$region");
	} elseif ($_REQUEST['pid'] == 'bsms') {
	} else if ($pid === 'bdep') {

		$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));
		$ce_id = $_POST['ce_id'];
		$acc_id = $_POST['acc_id'];
		$amount1 = $_POST['amount1'];
		$amount2 = $_POST['amount2'];
		$amount3 = $_POST['amount3'];
		$amount4 = $_POST['amount4'];
		$amount5 = $_POST['amount5'];
		$amount6 = $_POST['amount6'];
		$amount7 = $_POST['amount7'];
		$amount8 = $_POST['amount8'];
		$other_remarks = $_POST['other_remarks'];
		$amount9 = $_POST['amount9'];


		if ($amount1 == "") $amount1 = 0;
		if ($amount2 == "") $amount2 = 0;
		if ($amount3 == "") $amount3 = 0;
		if ($amount4 == "") $amount4 = 0;
		if ($amount5 == "") $amount5 = 0;
		if ($amount6 == "") $amount6 = 0;
		if ($amount7 == "") $amount7 = 0;
		if ($amount8 == "") $amount8 = 0;
		if ($amount9 == "") $amount9 = 0;

		$tot_ded = $amount1 + $amount2 + $amount3 + $amount4 + $amount5 + $amount6 + $amount7 + $amount8 + $amount9;
		$sqld1 = "select tot_amount from ce_deduction where ce_id='" . $ce_id . "' and ded_date='" . $trans_date . "' and status='Y'";
		$qud1 = mysqli_query($sqld1);
		$nd1 = mysqli_num_rows($qud1);
		while ($rd1 = mysqli_fetch_array($qud1)) {
			$ptot_ded = $rd1['tot_amount'];
		}
		if ($nd1 == 0) {
			$sqld = "INSERT INTO `ce_deduction` (`ce_id`, `ded_date`, `amount1`, `amount2`, `amount3`, `amount4`, `amount5`, `amount6`, `amount7`, `amount8`, `other_remarks`, `amount9`, `tot_amount`, `update_by`, `update_time`, `status`) VALUES  ('" . $ce_id . "','" . $trans_date . "'," . $amount1 . "," . $amount2 . "," . $amount3 . "," . $amount4 . "," . $amount5 . "," . $amount6 . "," . $amount7 . "," . $amount8 . ",'" . $other_remarks . "'," . $amount9 . "," . $tot_ded . ",'" . $user . "','" . $time . "','Y')";
			echo $sqld;
			$qud = mysqli_query($sqld);
			$tot_ded1 = $tot_ded;
		} else {
			$sqld = "UPDATE ce_deduction SET amount1=" . $amount1 . ", amount2=" . $amount2 . ", amount3=" . $amount3 . ", amount4=" . $amount4 . ", amount5=" . $amount5 . ", amount6=" . $amount6 . ", amount7=" . $amount7 . ", amount8=" . $amount8 . ",other_remarks='" . $other_remarks . "', amount9=" . $amount9 . ", tot_amount=" . $tot_ded . ", update_by='" . $user . "', update_time='" . $time . "' WHERE ce_id='" . $ce_id . "' AND ded_date='" . $trans_date . "' AND status='Y'";
			$qud = mysqli_query($sqld);
			echo $sqld;
			$tot_ded1 = $tot_ded - $ptot_ded;
		}
		// mysqli_close($conn);
		//header("Location:../?pid=$pid&nav=2_1&no=$tot_nos&ce_id=$ce_id");

	}

	//Custom Receipt View
	else if ($pid == "cust_receipt") {

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);
		$time1 = date("d-m-Y") . ", " . $time;
		$id = $_POST['id'];
		$cust_id = $_POST['cust_id'];
		$client_id = $_POST['client_id'];
		$mandate = $_POST['mandate'];
		if ($_POST['service_type'] == 'Pickup') {
			if ($_POST['hierarchy_code'] == '1') $hierarchy_code = mysqli_real_escape_string($readConnection, $_POST['hierarchy_code1']);
			else $hierarchy_code = '';
			if ($_POST['Receipt_No'] == '1') {
				$Receipt_No = mysqli_real_escape_string($readConnection, $_POST['Receipt_No1']);
				$Receipt_No_val = mysqli_real_escape_string($readConnection, $_POST['Receipt_No_val']);
			} else {
				$Receipt_No = '';
				$Receipt_No_val = '';
			}
			if ($_POST['PIS_Seal_Tag_No'] == '1') {
				$PIS_Seal_Tag_No = mysqli_real_escape_string($readConnection, $_POST['PIS_Seal_Tag_No1']);
				$PIS_Seal_Tag_No_val = $_POST['PIS_Seal_Tag_No_val'];
			} else {
				$PIS_Seal_Tag_No = '';
				$PIS_Seal_Tag_No_val = '';
			}


			if ($_POST['HCI_No_CRM_Code'] == '1') {
				$HCI_No_CRM_Code = mysqli_real_escape_string($readConnection, $_POST['HCI_No_CRM_Code1']);
				$HCI_No_CRM_Code_val = $_POST['HCI_No_CRM_Code_val'];
			} else {
				$HCI_No_CRM_Code = '';
				$HCI_No_CRM_Code_val = '';
			}
			if ($_POST['Generated_Slip_No'] == '1') {
				$Generated_Slip_No = mysqli_real_escape_string($readConnection, $_POST['Generated_Slip_No1']);
				$Generated_Slip_No_val = $_POST['Generated_Slip_No_val'];
			} else {
				$Generated_Slip_No = '';
				$Generated_Slip_No_val = '';
			}

			if ($_POST['number1'] == '1') $number1 = mysqli_real_escape_string($readConnection, $_POST['number11']);
			else $number1 = '';
			if ($_POST['number2'] == '1') $number2 = mysqli_real_escape_string($readConnection, $_POST['number21']);
			else $number2 = '';
			if ($_POST['number3'] == '1') $number3 = mysqli_real_escape_string($readConnection, $_POST['number31']);
			else $number3 = '';
			if ($_POST['number4'] == '1') $number4 = mysqli_real_escape_string($readConnection, $_POST['number41']);
			else $number4 = '';
			if ($_POST['number5'] == '1') $number5 = mysqli_real_escape_string($readConnection, $_POST['number51']);
			else $number5 = '';
			if ($_POST['Pickup_Amount'] == '1') $Pickup_Amount = mysqli_real_escape_string($readConnection, $_POST['Pickup_Amount1']);
			else $Pickup_Amount = '';
			if ($_POST['receipt_remarks'] == '1') $receipt_remarks = mysqli_real_escape_string($readConnection, $_POST['receipt_remarks1']);
			else $receipt_remarks = '';
			if ($_POST['reason_for_nil_pickup'] == '1') $reason_for_nil_pickup = mysqli_real_escape_string($readConnection, $_POST['reason_for_nil_pickup1']);
			else $reason_for_nil_pickup = '';


			if ($_POST['Request_Amount'] == '1') $Request_Amount = mysqli_real_escape_string($readConnection, $_POST['Request_Amount1']);
			else $Request_Amount = '';
			if ($_POST['Difference_Amount'] == '1') $Difference_Amount = mysqli_real_escape_string($readConnection, $_POST['Difference_Amount1']);
			else $Difference_Amount = '';
			if ($_POST['Deposit_Type'] == '1') $Deposit_Type = mysqli_real_escape_string($readConnection, $_POST['Deposit_Type1']);
			else $Deposit_Type = '';
			if ($_POST['Deposit_Account'] == '1') $Deposit_Account = mysqli_real_escape_string($readConnection, $_POST['Deposit_Account1']);
			else $Deposit_Account = '';
			if ($_POST['Deposit_Branch_Name'] == '1') $Deposit_Branch_Name = mysqli_real_escape_string($readConnection, $_POST['Deposit_Branch_Name1']);
			else $Deposit_Branch_Name = '';
			if ($_POST['Bank_Deposit_Slip_No'] == '1') $Bank_Deposit_Slip_No = mysqli_real_escape_string($readConnection, $_POST['Bank_Deposit_Slip_No1']);
			else $Bank_Deposit_Slip_No = '';
			if ($_POST['MIS_Amount'] == '1') $MIS_Amount = mysqli_real_escape_string($readConnection, $_POST['MIS_Amount1']);
			else $MIS_Amount = '';
			if ($_POST['Field1'] == '1') $Field1 = mysqli_real_escape_string($readConnection, $_POST['Field11']);
			else $Field1 = '';
			if ($_POST['Field2'] == '1') $Field2 = mysqli_real_escape_string($readConnection, $_POST['Field21']);
			else $Field2 = '';
			if ($_POST['Field3'] == '1') $Field3 = mysqli_real_escape_string($readConnection, $_POST['Field31']);
			else $Field3 = '';
			if ($_POST['Field4'] == '1') $Field4 = mysqli_real_escape_string($readConnection, $_POST['Field41']);
			else $Field4 = '';
			if ($_POST['Collection_Remarks'] == '1') $Collection_Remarks = mysqli_real_escape_string($readConnection, $_POST['Collection_Remarks1']);
			else $Collection_Remarks = '';
			if ($_POST['Other_Remarks'] == '1') $Other_Remarks = mysqli_real_escape_string($readConnection, $_POST['Other_Remarks1']);
			else $Other_Remarks = '';
			if ($_POST['Pickup_Time'] == '1') $Pickup_Time = mysqli_real_escape_string($readConnection, $_POST['Pickup_Time1']);
			else $Pickup_Time = '';
			if ($_POST['Deposit_Amount'] == '1') $Deposit_Amount = mysqli_real_escape_string($readConnection, $_POST['Deposit_Amount1']);
			else $Deposit_Amount = '';
			if ($_POST['Bank_Name'] == '1') $Bank_Name = mysqli_real_escape_string($readConnection, $_POST['Bank_Name1']);
			else $Bank_Name = '';
			if ($_POST['Remarks_for_Chennai'] == '1') $Remarks_for_Chennai = mysqli_real_escape_string($readConnection, $_POST['Remarks_for_Chennai1']);
			else $Remarks_for_Chennai = '';

			if ($_POST['old_deno'] == '1') $old_deno = mysqli_real_escape_string($readConnection, $_POST['old_deno1']);
			else $old_deno = '';

			if ($_POST['pisdate'] == '1') $pisdate = mysqli_real_escape_string($readConnection, $_POST['pisdate1']);
			else $pisdate = '';

			if ($_POST['Pickup_Date'] == '1') $pickup_date = mysqli_real_escape_string($readConnection, $_POST['Pickup_Date1']);
			else $pickup_date = '';

			if($_POST['depositslip_date']=='1') $depositslip_date = mysqli_real_escape_string($readConnection,$_POST['depositslip_date1']); else $depositslip_date='';

			if ($id != '') {
				$query_cus = "UPDATE `pickup_field_custome` SET `Cust_IDS`='" . $cust_id . "', `Client_Code`='" . $client_id . "', `mandate`='" . $mandate . "', `hierarchy_code`='" . $hierarchy_code . "', `Receipt_No`='" . $Receipt_No . "', `Receipt_No_val`='" . $Receipt_No_val . "', `PIS_Seal_Tag_No`='" . $PIS_Seal_Tag_No . "', `PIS_Seal_Tag_No_val`='" . $PIS_Seal_Tag_No_val . "', `HCI_No_CRM_Code`='" . $HCI_No_CRM_Code . "', `HCI_No_CRM_Code_val`='" . $HCI_No_CRM_Code_val . "', `Seal_Tag_No`='" . $Generated_Slip_No . "', `Seal_Tag_No_val`='" . $Generated_Slip_No_val . "', `Pickup_Amount`='" . $Pickup_Amount . "', `number1`='" . $number1 . "', `number2`='" . $number2 . "', `number3`='" . $number3 . "', `number4`='" . $number4 . "', `number5`='" . $number5 . "', `receipt_remarks`='" . $receipt_remarks . "',  `reason_for_nil_pickup`='" . $reason_for_nil_pickup . "', `Request_Amount`='" . $Request_Amount . "', `Difference_Amount`='" . $Difference_Amount . "', `Deposit_Type`='" . $Deposit_Type . "', `Deposit_Account`='" . $Deposit_Account . "', `Deposit_Branch_Name`='" . $Deposit_Branch_Name . "', `Bank_Deposit_Slip_No`='" . $Bank_Deposit_Slip_No . "', `MIS_Amount`='" . $MIS_Amount . "', `Field1`='" . $Field1 . "', `Field2`='" . $Field2 . "', `Field3`='" . $Field3 . "', `Field4`='" . $Field4 . "', `Collection_Remarks`='" . $Collection_Remarks . "', `Other_Remarks`='" . $Other_Remarks . "', `Pickup_Time`='" . $Pickup_Time . "', `Deposit_Amount`='" . $Deposit_Amount . "', `Bank_Name`='" . $Bank_Name . "', `Remarks_for_Chennai`='" . $Remarks_for_Chennai . "', `old_deno`='" . $old_deno . "', `pisdate`='" . $pisdate . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "',`Pickup_Date`='" . $pickup_date . "',`depositslip_date`='".$depositslip_date."' WHERE id='$id'";
				$nav = '3';
			} else {
				$query_cus = "INSERT INTO `pickup_field_custome` (`Cust_IDS`, `Client_Code`, `mandate`, `hierarchy_code`, `Receipt_No`, `Receipt_No_val`, `PIS_Seal_Tag_No`, `PIS_Seal_Tag_No_val`, `HCI_No_CRM_Code`, `HCI_No_CRM_Code_val`, `Seal_Tag_No`, `Seal_Tag_No_val`, `Pickup_Amount`, `number1`, `number2`, `number3`, `number4`, `number5`, `receipt_remarks`, `reason_for_nil_pickup`, `Request_Amount`, `Difference_Amount`, `Deposit_Type`, `Deposit_Account`, `Deposit_Branch_Name`, `Bank_Deposit_Slip_No`, `MIS_Amount`, `Field1`, `Field2`, `Field3`, `Field4`, `Collection_Remarks`, `Other_Remarks`, `Pickup_Time`,`Pickup_Date`,`depositslip_date`,`Deposit_Amount`, `Bank_Name`, `Remarks_for_Chennai`,old_deno, `update_by`, `update_date`, `status`) VALUES ('" . $cust_id . "', '" . $client_id . "', '" . $mandate . "', '" . $hierarchy_code . "', '" . $Receipt_No . "', '" . $Receipt_No_val . "', '" . $PIS_Seal_Tag_No . "', '" . $PIS_Seal_Tag_No_val . "', '" . $HCI_No_CRM_Code . "', '" . $HCI_No_CRM_Code_val . "', '" . $Generated_Slip_No . "', '" . $Generated_Slip_No_val . "', '" . $Pickup_Amount . "', '" . $number1 . "', '" . $number2 . "', '" . $number3 . "', '" . $number4 . "', '" . $number5 . "', '" . $receipt_remarks . "', '" . $reason_for_nil_pickup . "', '" . $Request_Amount . "', '" . $Difference_Amount . "', '" . $Deposit_Type . "', '" . $Deposit_Account . "', '" . $Deposit_Branch_Name . "', '" . $Bank_Deposit_Slip_No . "', '" . $MIS_Amount . "', '" . $Field1 . "', '" . $Field2 . "', '" . $Field3 . "', '" . $Field4 . "', '" . $Collection_Remarks . "', '" . $Other_Remarks . "', '" . $Pickup_Time . "','" . $pickup_date . "','".$depositslip_date."','" . $Deposit_Amount . "', '" . $Bank_Name . "', '" . $Remarks_for_Chennai . "','" . $old_deno . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '1';
			}
		} else if ($_POST['service_type'] == 'Cheque Pickup') {
			if ($_POST['no_cheque'] == '1') $no_cheque = mysqli_real_escape_string($readConnection, $_POST['no_cheque1']);
			else $no_cheque = '';
			if ($_POST['cheque_no'] == '1') $cheque_no = mysqli_real_escape_string($readConnection, $_POST['cheque_no1']);
			else $cheque_no = '';
			if ($_POST['rec_no'] == '1') $rec_no = mysqli_real_escape_string($readConnection, $_POST['rec_no1']);
			else $rec_no = '';
			if ($_POST['hcl_slip_no'] == '1') $hcl_slip_no = mysqli_real_escape_string($readConnection, $_POST['hcl_slip_no1']);
			else $hcl_slip_no = '';
			if ($_POST['cheque_amt'] == '1') $cheque_amt = mysqli_real_escape_string($readConnection, $_POST['cheque_amt1']);
			else $cheque_amt = '';
			if ($_POST['deposit_bank'] == '1') $deposit_bank = mysqli_real_escape_string($readConnection, $_POST['deposit_bank1']);
			else $deposit_bank = '';
			if ($_POST['account_no'] == '1') $account_no = mysqli_real_escape_string($readConnection, $_POST['account_no1']);
			else $account_no = '';
			if ($_POST['send_time'] == '1') $send_time = mysqli_real_escape_string($readConnection, $_POST['send_time1']);
			else $send_time = '';
			if ($_POST['destination'] == '1') $destination = mysqli_real_escape_string($readConnection, $_POST['destination1']);
			else $destination = '';
			if ($_POST['distance_ctobank'] == '1') $distance_ctobank = mysqli_real_escape_string($readConnection, $_POST['distance_ctobank1']);
			else $distance_ctobank = '';
			if ($_POST['charges'] == '1') $charges = mysqli_real_escape_string($readConnection, $_POST['charges1']);
			else $charges = '';
			if ($_POST['name'] == '1') $name = mysqli_real_escape_string($readConnection, $_POST['name1']);
			else $name = '';
			if ($_POST['pod_no'] == '1') $pod_no = mysqli_real_escape_string($readConnection, $_POST['pod_no1']);
			else $pod_no = '';
			if ($_POST['courier_status'] == '1') $courier_status = mysqli_real_escape_string($readConnection, $_POST['courier_status1']);
			else $courier_status = '';
			if ($_POST['scan_copy'] == '1') $scan_copy = mysqli_real_escape_string($readConnection, $_POST['scan_copy1']);
			else $scan_copy = '';
			if ($_POST['remark'] == '1') $remark = mysqli_real_escape_string($readConnection, $_POST['remark1']);
			else $remark = '';
			if ($_POST['Withdraw_Status'] == '1') $Withdraw_Status = mysqli_real_escape_string($readConnection, $_POST['Withdraw_Status1']);
			else $Withdraw_Status = '';
			if ($id != '') {

				$query_cus = "UPDATE `pickup_cheque_custom` SET `client_id`='" . $client_id . "', `cust_id`='" . $cust_id . "', `mandate`='" . $mandate . "', `no_cheque`='" . $no_cheque . "', `cheque_no`='" . $cheque_no . "', `rec_no`='" . $rec_no . "', `hcl_slip_no`='" . $hcl_slip_no . "', `cheque_amt`='" . $cheque_amt . "', `deposit_bank`='" . $deposit_bank . "', `account_no`='" . $account_no . "', `send_time`='" . $send_time . "', `destination`='" . $destination . "', `distance_ctobank`='" . $distance_ctobank . "', `charges`='" . $charges . "', `name`='" . $name . "', `pod_no`='" . $pod_no . "', `courier_status`='" . $courier_status . "', `scan_copy`='" . $scan_copy . "', `remark`='" . $remark . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "' WHERE id='$id'";
				$nav = '7';
			} else {
				$query_cus = "INSERT INTO `pickup_cheque_custom` (`client_id`, `cust_id`, `mandate`, `no_cheque`, `cheque_no`, `rec_no`, `hcl_slip_no`, `cheque_amt`, `deposit_bank`, `account_no`, `send_time`, `destination`, `distance_ctobank`, `charges`, `name`, `pod_no`, `courier_status`, `scan_copy`, `remark`, `update_by`, `update_date`, `status`) VALUES
('" . $client_id . "','" . $cust_id . "','" . $mandate . "', '" . $no_cheque . "', '" . $cheque_no . "', '" . $rec_no . "', '" . $hcl_slip_no . "', '" . $cheque_amt . "', '" . $deposit_bank . "', '" . $account_no . "', '" . $send_time . "', '" . $destination . "', '" . $distance_ctobank . "', '" . $charges . "', '" . $name . "', '" . $pod_no . "', '" . $courier_status . "', '" . $scan_copy . "', '" . $remark . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '6';
			}
		} else if ($_POST['service_type'] == 'Dynamic Cash Pickup') {
			if ($_POST['client_code'] == '1') $client_code = mysqli_real_escape_string($readConnection, $_POST['client_code1']);
			else $client_code = '';
			if ($_POST['receipt_no'] == '1') $receipt_no = mysqli_real_escape_string($readConnection, $_POST['receipt_no1']);
			else $receipt_no = '';
			if ($_POST['pis_hcl_no'] == '1') $pis_hcl_no = mysqli_real_escape_string($readConnection, $_POST['pis_hcl_no1']);
			else $pis_hcl_no = '';
			if ($_POST['hcl_no'] == '1') $hcl_no = mysqli_real_escape_string($readConnection, $_POST['hcl_no1']);
			else $hcl_no = '';
			if ($_POST['gen_slip'] == '1') $gen_slip = mysqli_real_escape_string($readConnection, $_POST['gen_slip1']);
			else $gen_slip1 = '';
			if ($_POST['pick_amount'] == '1') $pick_amount = mysqli_real_escape_string($readConnection, $_POST['pick_amount1']);
			else $pick_amount = '';
			if ($_POST['dep_type'] == '1') $dep_type = mysqli_real_escape_string($readConnection, $_POST['dep_type1']);
			else $dep_type = '';
			if ($_POST['dep_acc'] == '1') $dep_acc = mysqli_real_escape_string($readConnection, $_POST['dep_acc1']);
			else $dep_acc = '';
			if ($_POST['dep_branch'] == '1') $dep_branch = mysqli_real_escape_string($readConnection, $_POST['dep_branch1']);
			else $dep_branch = '';
			if ($_POST['dep_slip'] == '1') $dep_slip = mysqli_real_escape_string($readConnection, $_POST['dep_slip1']);
			else $dep_slip = '';
			if ($_POST['dep_amount'] == '1') $dep_amount = mysqli_real_escape_string($readConnection, $_POST['dep_amount1']);
			else $dep_amount = '';
			if ($_POST['cl_bcl'] == '1') $cl_bcl = mysqli_real_escape_string($readConnection, $_POST['cl_bcl1']);
			else $cl_bcl = '';
			if ($_POST['field2'] == '1') $field2 = mysqli_real_escape_string($readConnection, $_POST['field21']);
			else $field2 = '';
			if ($_POST['field3'] == '1') $field3 = mysqli_real_escape_string($readConnection, $_POST['field31']);
			else $field3 = '';
			if ($_POST['field4'] == '1') $field4 = mysqli_real_escape_string($readConnection, $_POST['field41']);
			else $field4 = '';
			if ($_POST['coll_remarks'] == '1') $coll_remarks = mysqli_real_escape_string($readConnection, $_POST['coll_remarks1']);
			else $coll_remarks = '';
			if ($_POST['other_remarks'] == '1') $other_remarks = mysqli_real_escape_string($readConnection, $_POST['other_remarks1']);
			else $other_remarks = '';
			if ($_POST['pick_time'] == '1') $pick_time = mysqli_real_escape_string($readConnection, $_POST['pick_time1']);
			else $pick_time = '';
			if ($_POST['ce_id'] == '1') $ce_id = mysqli_real_escape_string($readConnection, $_POST['ce_id1']);
			else $ce_id = '';

			if ($id != '') {

				$query_cus = "UPDATE `pickup_cash_dynamic_custom` SET `cust_id`='" . $cust_id . "', `mandate`='" . $mandate . "', `client_code`='" . $client_code . "', `receipt_no`='" . $receipt_no . "',  `pis_hcl_no`='" . $pis_hcl_no . "', `hcl_no`='" . $hcl_no . "', `gen_slip`='" . $gen_slip . "', `pick_amount`='" . $pick_amount . "', `dep_type`='" . $dep_type . "', `dep_acc`='" . $dep_acc . "', `dep_branch`='" . $dep_branch . "', `dep_slip`='" . $dep_slip . "', `dep_amount`='" . $dep_amount . "', `cl_bcl`='" . $cl_bcl . "', `field2`='" . $field2 . "', `field3`='" . $field3 . "', `field4`='" . $field4 . "', `coll_remarks`='" . $coll_remarks . "', `other_remarks`='" . $other_remarks . "', `pick_time`='" . $pick_time . "', `ce_id`='" . $ce_id . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "' WHERE id='$id'";
				$nav = '9';
			} else {
				$query_cus = "INSERT INTO `pickup_cash_dynamic_custom` (`client_id`, `cust_id`, `mandate`, `client_code`, `receipt_no`, `pis_hcl_no`, `hcl_no`, `gen_slip`, `pick_amount`, `dep_type`, `dep_acc`, `dep_branch`, `dep_slip`, `dep_amount`, `cl_bcl`, `field2`, `field3`, `field4`, `coll_remarks`, `other_remarks`, `pick_time`, `ce_id`, `update_by`, `update_date`, `status`) VALUES
('" . $client_id . "', '" . $cust_id . "', '" . $mandate . "', '" . $client_code . "', '" . $receipt_no . "', '" . $pis_hcl_no . "', '" . $hcl_no . "', '" . $gen_slip . "', '" . $pick_amount . "', '" . $dep_type . "', '" . $dep_acc . "', '" . $dep_branch . "', '" . $dep_slip . "', '" . $dep_amount . "', '" . $cl_bcl . "', '" . $field2 . "', '" . $field3 . "', '" . $field4 . "', '" . $coll_remarks . "', '" . $other_remarks . "', '" . $pick_time . "', '" . $ce_id . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '8';
			}
		} else if ($_POST['service_type'] == 'Dynamic Cheque Pickup') {

			if ($_POST['client_code'] == '1') $client_code = mysqli_real_escape_string($readConnection, $_POST['client_code1']);
			else $client_code = '';
			if ($_POST['receipt_no'] == '1') $receipt_no = mysqli_real_escape_string($readConnection, $_POST['receipt_no1']);
			else $receipt_no = '';
			if ($_POST['deposition_date'] == '1') $deposition_date = mysqli_real_escape_string($readConnection, $_POST['deposition_date1']);
			else $deposition_date = '';
			if ($_POST['no_cheque'] == '1') $no_cheque = mysqli_real_escape_string($readConnection, $_POST['no_cheque1']);
			else $no_cheque = '';
			if ($_POST['chq_number'] == '1') $chq_number = mysqli_real_escape_string($readConnection, $_POST['chq_number1']);
			else $chq_number = '';
			if ($_POST['chq_date'] == '1') $chq_date = mysqli_real_escape_string($readConnection, $_POST['chq_date1']);
			else $chq_date = '';
			if ($_POST['cheque_amt'] == '1') $cheque_amt = mysqli_real_escape_string($readConnection, $_POST['cheque_amt1']);
			else $cheque_amt = '';
			if ($_POST['deposit_bank'] == '1') $deposit_bank = mysqli_real_escape_string($readConnection, $_POST['deposit_bank1']);
			else $deposit_bank = '';
			if ($_POST['account_no'] == '1') $account_no = mysqli_real_escape_string($readConnection, $_POST['account_no1']);
			else $account_no = '';

			if ($_POST['dispatch_date'] == '1') $dispatch_date = mysqli_real_escape_string($readConnection, $_POST['dispatch_date1']);
			else $dispatch_date = '';
			if ($_POST['courier_desc'] == '1') $courier_desc = mysqli_real_escape_string($readConnection, $_POST['courier_desc1']);
			else $courier_desc = '';
			if ($_POST['distance_cust_bank'] == '1') $distance_cust_bank = mysqli_real_escape_string($readConnection, $_POST['distance_cust_bank1']);
			else $distance_cust_bank = '';
			if ($_POST['courier_amount'] == '1') $courier_amount = mysqli_real_escape_string($readConnection, $_POST['courier_amount1']);
			else $courier_amount = '';
			if ($_POST['courier_name'] == '1') $courier_name = mysqli_real_escape_string($readConnection, $_POST['courier_name1']);
			else $courier_name = '';
			if ($_POST['pod_no'] == '1') $pod_no = mysqli_real_escape_string($readConnection, $_POST['pod_no1']);
			else $pod_no = '';
			if ($_POST['courier_status'] == '1') $courier_status = mysqli_real_escape_string($readConnection, $_POST['courier_status1']);
			else $courier_status = '';
			if ($_POST['scan_copy'] == '1') $scan_copy = mysqli_real_escape_string($readConnection, $_POST['scan_copy1']);
			else $scan_copy = '';
			if ($_POST['remarks'] == '1') $remarks = mysqli_real_escape_string($readConnection, $_POST['remarks1']);
			else $remarks = '';


			if ($id != '') {

				$query_cus = "UPDATE `pickup_cheque_dynamic_custom` SET `cust_id`='" . $cust_id . "', `mandate`='" . $mandate . "', `client_code`='" . $client_code . "', `receipt_no`='" . $receipt_no . "', `deposition_date`='" . $deposition_date . "', `no_cheque`='" . $no_cheque . "', `chq_number`='" . $chq_number . "', `chq_date`='" . $chq_date . "', `cheque_amt`='" . $cheque_amt . "', `deposit_bank`='" . $deposit_bank . "', `account_no`='" . $account_no . "', `dispatch_date`='" . $dispatch_date . "', `courier_desc`='" . $courier_desc . "', `distance_cust_bank`='" . $distance_cust_bank . "', `courier_amount`='" . $courier_amount . "', `courier_name`='" . $courier_name . "', `pod_no`='" . $pod_no . "', `courier_status`='" . $courier_status . "', `scan_copy`='" . $scan_copy . "', `remarks`='" . $remarks . "', `update_by`='" . $user . "', `update_time`='" . $time1 . "' WHERE id='$id'";
				$nav = '11';
			} else {
				$query_cus = "INSERT INTO `pickup_cheque_dynamic_custom` (`client_id`, `cust_id`, `mandate`, `client_code`, `receipt_no`, `deposition_date`, `no_cheque`, `chq_number`, `chq_date`, `cheque_amt`, `deposit_bank`, `account_no`, `dispatch_date`, `courier_desc`, `distance_cust_bank`, `courier_amount`, `courier_name`, `pod_no`, `courier_status`, `scan_copy`, `remarks`, `update_by`, `update_time`, `status`) VALUES
('" . $client_id . "', '" . $cust_id . "', '" . $mandate . "', '" . $client_code . "', '" . $receipt_no . "',  '" . $deposition_date . "', '" . $no_cheque . "', '" . $chq_number . "', '" . $chq_date . "', '" . $cheque_amt . "', '" . $deposit_bank . "', '" . $account_no . "', '" . $dispatch_date . "', '" . $courier_desc . "', '" . $distance_cust_bank . "', '" . $courier_amount . "', '" . $courier_name . "', '" . $pod_no . "', '" . $courier_status . "', '" . $scan_copy . "', '" . $remarks . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '10';
			}
		} else if ($_POST['service_type'] == 'Coin Pickup') {
			if ($_POST['hierarchy_code'] == '1') $hierarchy_code = mysqli_real_escape_string($readConnection, $_POST['hierarchy_code1']);
			else $hierarchy_code = '';
			if ($_POST['Receipt_No'] == '1') {
				$Receipt_No = mysqli_real_escape_string($readConnection, $_POST['Receipt_No1']);
				$Receipt_No_val = mysqli_real_escape_string($readConnection, $_POST['Receipt_No_val']);
			} else {
				$Receipt_No = '';
				$Receipt_No_val = '';
			}
			if ($_POST['PIS_Seal_Tag_No'] == '1') {
				$PIS_Seal_Tag_No = mysqli_real_escape_string($readConnection, $_POST['PIS_Seal_Tag_No1']);
				$PIS_Seal_Tag_No_val = $_POST['PIS_Seal_Tag_No_val'];
			} else {
				$PIS_Seal_Tag_No = '';
				$PIS_Seal_Tag_No_val = '';
			}


			if ($_POST['HCI_No_CRM_Code'] == '1') {
				$HCI_No_CRM_Code = mysqli_real_escape_string($readConnection, $_POST['HCI_No_CRM_Code1']);
				$HCI_No_CRM_Code_val = $_POST['HCI_No_CRM_Code_val'];
			} else {
				$HCI_No_CRM_Code = '';
				$HCI_No_CRM_Code_val = '';
			}
			if ($_POST['Generated_Slip_No'] == '1') {
				$Generated_Slip_No = mysqli_real_escape_string($readConnection, $_POST['Generated_Slip_No1']);
				$Generated_Slip_No_val = $_POST['Generated_Slip_No_val'];
			} else {
				$Generated_Slip_No = '';
				$Generated_Slip_No_val = '';
			}

			if ($_POST['number1'] == '1') $number1 = mysqli_real_escape_string($readConnection, $_POST['number11']);
			else $number1 = '';
			if ($_POST['number2'] == '1') $number2 = mysqli_real_escape_string($readConnection, $_POST['number21']);
			else $number2 = '';
			if ($_POST['number3'] == '1') $number3 = mysqli_real_escape_string($readConnection, $_POST['number31']);
			else $number3 = '';
			if ($_POST['number4'] == '1') $number4 = mysqli_real_escape_string($readConnection, $_POST['number41']);
			else $number4 = '';
			if ($_POST['number5'] == '1') $number5 = mysqli_real_escape_string($readConnection, $_POST['number51']);
			else $number5 = '';
			if ($_POST['Pickup_Amount'] == '1') $Pickup_Amount = mysqli_real_escape_string($readConnection, $_POST['Pickup_Amount1']);
			else $Pickup_Amount = '';
			if ($_POST['receipt_remarks'] == '1') $receipt_remarks = mysqli_real_escape_string($readConnection, $_POST['receipt_remarks1']);
			else $receipt_remarks = '';
			if ($_POST['reason_for_nil_pickup'] == '1') $reason_for_nil_pickup = mysqli_real_escape_string($readConnection, $_POST['reason_for_nil_pickup1']);
			else $reason_for_nil_pickup = '';


			if ($_POST['Request_Amount'] == '1') $Request_Amount = mysqli_real_escape_string($readConnection, $_POST['Request_Amount1']);
			else $Request_Amount = '';
			if ($_POST['Difference_Amount'] == '1') $Difference_Amount = mysqli_real_escape_string($readConnection, $_POST['Difference_Amount1']);
			else $Difference_Amount = '';
			if ($_POST['Deposit_Type'] == '1') $Deposit_Type = mysqli_real_escape_string($readConnection, $_POST['Deposit_Type1']);
			else $Deposit_Type = '';
			if ($_POST['Deposit_Account'] == '1') $Deposit_Account = mysqli_real_escape_string($readConnection, $_POST['Deposit_Account1']);
			else $Deposit_Account = '';
			if ($_POST['Deposit_Branch_Name'] == '1') $Deposit_Branch_Name = mysqli_real_escape_string($readConnection, $_POST['Deposit_Branch_Name1']);
			else $Deposit_Branch_Name = '';
			if ($_POST['Bank_Deposit_Slip_No'] == '1') $Bank_Deposit_Slip_No = mysqli_real_escape_string($readConnection, $_POST['Bank_Deposit_Slip_No1']);
			else $Bank_Deposit_Slip_No = '';
			if ($_POST['MIS_Amount'] == '1') $MIS_Amount = mysqli_real_escape_string($readConnection, $_POST['MIS_Amount1']);
			else $MIS_Amount = '';
			if ($_POST['Field1'] == '1') $Field1 = mysqli_real_escape_string($readConnection, $_POST['Field11']);
			else $Field1 = '';
			if ($_POST['Field2'] == '1') $Field2 = mysqli_real_escape_string($readConnection, $_POST['Field21']);
			else $Field2 = '';
			if ($_POST['Field3'] == '1') $Field3 = mysqli_real_escape_string($readConnection, $_POST['Field31']);
			else $Field3 = '';
			if ($_POST['Field4'] == '1') $Field4 = mysqli_real_escape_string($readConnection, $_POST['Field41']);
			else $Field4 = '';
			if ($_POST['Collection_Remarks'] == '1') $Collection_Remarks = mysqli_real_escape_string($readConnection, $_POST['Collection_Remarks1']);
			else $Collection_Remarks = '';
			if ($_POST['Other_Remarks'] == '1') $Other_Remarks = mysqli_real_escape_string($readConnection, $_POST['Other_Remarks1']);
			else $Other_Remarks = '';
			if ($_POST['Pickup_Time'] == '1') $Pickup_Time = mysqli_real_escape_string($readConnection, $_POST['Pickup_Time1']);
			else $Pickup_Time = '';
			if ($_POST['Deposit_Amount'] == '1') $Deposit_Amount = mysqli_real_escape_string($readConnection, $_POST['Deposit_Amount1']);
			else $Deposit_Amount = '';
			if ($_POST['Bank_Name'] == '1') $Bank_Name = mysqli_real_escape_string($readConnection, $_POST['Bank_Name1']);
			else $Bank_Name = '';
			if ($_POST['Remarks_for_Chennai'] == '1') $Remarks_for_Chennai = mysqli_real_escape_string($readConnection, $_POST['Remarks_for_Chennai1']);
			else $Remarks_for_Chennai = '';

			if ($_POST['old_deno'] == '1') $old_deno = mysqli_real_escape_string($readConnection, $_POST['old_deno1']);
			else $old_deno = '';
			if ($id != '') {
				$query_cus = "UPDATE `coin_pickup_field_custome` SET `Cust_IDS`='" . $cust_id . "', `Client_Code`='" . $client_id . "', `mandate`='" . $mandate . "', `hierarchy_code`='" . $hierarchy_code . "', `Receipt_No`='" . $Receipt_No . "', `Receipt_No_val`='" . $Receipt_No_val . "', `PIS_Seal_Tag_No`='" . $PIS_Seal_Tag_No . "', `PIS_Seal_Tag_No_val`='" . $PIS_Seal_Tag_No_val . "', `HCI_No_CRM_Code`='" . $HCI_No_CRM_Code . "', `HCI_No_CRM_Code_val`='" . $HCI_No_CRM_Code_val . "', `Seal_Tag_No`='" . $Generated_Slip_No . "', `Seal_Tag_No_val`='" . $Generated_Slip_No_val . "', `Pickup_Amount`='" . $Pickup_Amount . "', `number1`='" . $number1 . "', `number2`='" . $number2 . "', `number3`='" . $number3 . "', `number4`='" . $number4 . "', `number5`='" . $number5 . "', `receipt_remarks`='" . $receipt_remarks . "',  `reason_for_nil_pickup`='" . $reason_for_nil_pickup . "', `Request_Amount`='" . $Request_Amount . "', `Difference_Amount`='" . $Difference_Amount . "', `Deposit_Type`='" . $Deposit_Type . "', `Deposit_Account`='" . $Deposit_Account . "', `Deposit_Branch_Name`='" . $Deposit_Branch_Name . "', `Bank_Deposit_Slip_No`='" . $Bank_Deposit_Slip_No . "', `MIS_Amount`='" . $MIS_Amount . "', `Field1`='" . $Field1 . "', `Field2`='" . $Field2 . "', `Field3`='" . $Field3 . "', `Field4`='" . $Field4 . "', `Collection_Remarks`='" . $Collection_Remarks . "', `Other_Remarks`='" . $Other_Remarks . "', `Pickup_Time`='" . $Pickup_Time . "', `Deposit_Amount`='" . $Deposit_Amount . "', `Bank_Name`='" . $Bank_Name . "', `Remarks_for_Chennai`='" . $Remarks_for_Chennai . "', `old_deno`='" . $old_deno . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "' WHERE id='$id'";
				$nav = '3';
			} else {
				$query_cus = "INSERT INTO `coin_pickup_field_custome` (`Cust_IDS`, `Client_Code`, `mandate`, `hierarchy_code`, `Receipt_No`, `Receipt_No_val`, `PIS_Seal_Tag_No`, `PIS_Seal_Tag_No_val`, `HCI_No_CRM_Code`, `HCI_No_CRM_Code_val`, `Seal_Tag_No`, `Seal_Tag_No_val`, `Pickup_Amount`, `number1`, `number2`, `number3`, `number4`, `number5`, `receipt_remarks`, `reason_for_nil_pickup`, `Request_Amount`, `Difference_Amount`, `Deposit_Type`, `Deposit_Account`, `Deposit_Branch_Name`, `Bank_Deposit_Slip_No`, `MIS_Amount`, `Field1`, `Field2`, `Field3`, `Field4`, `Collection_Remarks`, `Other_Remarks`, `Pickup_Time`, `Deposit_Amount`, `Bank_Name`, `Remarks_for_Chennai`,old_deno, `update_by`, `update_date`, `status`) VALUES ('" . $cust_id . "', '" . $client_id . "', '" . $mandate . "', '" . $hierarchy_code . "', '" . $Receipt_No . "', '" . $Receipt_No_val . "', '" . $PIS_Seal_Tag_No . "', '" . $PIS_Seal_Tag_No_val . "', '" . $HCI_No_CRM_Code . "', '" . $HCI_No_CRM_Code_val . "', '" . $Generated_Slip_No . "', '" . $Generated_Slip_No_val . "', '" . $Pickup_Amount . "', '" . $number1 . "', '" . $number2 . "', '" . $number3 . "', '" . $number4 . "', '" . $number5 . "', '" . $receipt_remarks . "', '" . $reason_for_nil_pickup . "', '" . $Request_Amount . "', '" . $Difference_Amount . "', '" . $Deposit_Type . "', '" . $Deposit_Account . "', '" . $Deposit_Branch_Name . "', '" . $Bank_Deposit_Slip_No . "', '" . $MIS_Amount . "', '" . $Field1 . "', '" . $Field2 . "', '" . $Field3 . "', '" . $Field4 . "', '" . $Collection_Remarks . "', '" . $Other_Remarks . "', '" . $Pickup_Time . "', '" . $Deposit_Amount . "', '" . $Bank_Name . "', '" . $Remarks_for_Chennai . "','" . $old_deno . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '1';
			}
		} else if ($_POST['service_type'] == 'Delivery') {

			if ($_POST['Withdraw_Status'] == '1') $Withdraw_Status = mysqli_real_escape_string($readConnection, $_POST['Withdraw_Status1']);
			else $Withdraw_Status = '';
			if ($_POST['Cheque_Counts'] == '1') $Cheque_Counts = mysqli_real_escape_string($readConnection, $_POST['Cheque_Counts1']);
			else $Cheque_Counts = '';
			if ($_POST['Bank_Account_No'] == '1') $Bank_Account_No = mysqli_real_escape_string($readConnection, $_POST['Bank_Account_No1']);
			else $Bank_Account_No = '';
			if ($_POST['Cheque_No'] == '1') $Cheque_No = mysqli_real_escape_string($readConnection, $_POST['Cheque_No1']);
			else $Cheque_No = '';
			if ($_POST['Cheque_Amount'] == '1') $Cheque_Amount = mysqli_real_escape_string($readConnection, $_POST['Cheque_Amount1']);
			else $Cheque_Amount = '';

			if ($_POST['Withdraw_Time'] == '1') $Withdraw_Time = mysqli_real_escape_string($readConnection, $_POST['Withdraw_Time1']);
			else $Withdraw_Time = '';
			if ($_POST['Request_Received_On'] == '1') $Request_Received_On = mysqli_real_escape_string($readConnection, $_POST['Request_Received_On1']);
			else $Request_Received_On = '';
			if ($_POST['Request_Received_Time'] == '1') $Request_Received_Time = mysqli_real_escape_string($readConnection, $_POST['Request_Received_Time1']);
			else $Request_Received_Time = '';
			if ($_POST['Delivery_To'] == '1') $Delivery_To = mysqli_real_escape_string($readConnection, $_POST['Delivery_To1']);
			else $Delivery_To = '';
			if ($_POST['Cash_Receipt_No'] == '1') $Cash_Receipt_No = mysqli_real_escape_string($readConnection, $_POST['Cash_Receipt_No1']);
			else $Cash_Receipt_No = '';
			if ($_POST['Delivery_Amount'] == '1') $Delivery_Amount = mysqli_real_escape_string($readConnection, $_POST['Delivery_Amount1']);
			else $Delivery_Amount = '';
			if ($_POST['Request_Amount'] == '1') $Request_Amount = mysqli_real_escape_string($readConnection, $_POST['Request_Amount1']);
			else $Request_Amount = '';
			if ($_POST['Difference_Amount'] == '1') $Difference_Amount = mysqli_real_escape_string($readConnection, $_POST['Difference_Amount1']);
			else $Difference_Amount = '';
			if ($_POST['Delivery_Remarks'] == '1') $Delivery_Remarks = mysqli_real_escape_string($readConnection, $_POST['Delivery_Remarks1']);
			else $Delivery_Remarks = '';
			if ($_POST['Other_Remarks'] == '1') $Other_Remarks = mysqli_real_escape_string($readConnection, $_POST['Other_Remarks1']);
			else $Other_Remarks = '';
			if ($_POST['Delivery_Time'] == '1') $Delivery_Time = mysqli_real_escape_string($readConnection, $_POST['Delivery_Time1']);
			else $Delivery_Time = '';
			if ($_POST['Receipt_Status'] == '1') $Receipt_Status = mysqli_real_escape_string($readConnection, $_POST['Receipt_Status1']);
			else $Receipt_Status = '';
			if ($_POST['Executive_Name'] == '1') $Executive_Name = mysqli_real_escape_string($readConnection, $_POST['Executive_Name1']);
			else $Executive_Name = '';
			if ($_POST['Ref_No'] == '1') $Ref_No = mysqli_real_escape_string($readConnection, $_POST['Ref_No1']);
			else $Ref_No = '';
			if ($_POST['1st_Visit'] == '1') $st_Visit1 = mysqli_real_escape_string($readConnection, $_POST['1st_Visit1']);
			else $st_Visit1 = '';
			if ($_POST['2nd_Visit'] == '1') $st_Visit2 = mysqli_real_escape_string($readConnection, $_POST['2nd_Visit1']);
			else $st_Visit2 = '';
			if ($_POST['3rd_Visit'] == '1') $st_Visit3 = mysqli_real_escape_string($readConnection, $_POST['3rd_Visit1']);
			else $st_Visit3 = '';
			if ($_POST['d_old_deno'] == '1') $d_old_deno = mysqli_real_escape_string($readConnection, $_POST['d_old_deno1']);
			else $st_Visit3 = '';
			if ($_POST['Cost_Of_Operation'] == '1') $Cost_Of_Operation = mysqli_real_escape_string($readConnection, $_POST['Cost_Of_Operation1']);
			else $Cost_Of_Operation = '';

			if ($id != '') {

				$query_cus = "UPDATE `delivery_field_custome` SET `Cust_IDS`='" . $cust_id . "', `mandate`='" . $mandate . "', `Withdraw_Status`='" . $Withdraw_Status . "', `Cheque_Counts`='" . $Cheque_Counts . "', `Bank_Account_No`='" . $Bank_Account_No . "', `Cheque_No`='" . $Cheque_No . "', `Cheque_Amount`='" . $Cheque_Amount . "', `Withdraw_Time`='" . $Withdraw_Time . "', `Request_Received_On`='" . $Request_Received_On . "', `Request_Received_Time`='" . $Request_Received_Time . "', `Delivery_To`='" . $Delivery_To . "', `Cash_Receipt_No`='" . $Cash_Receipt_No . "', `Delivery_Amount`='" . $Delivery_Amount . "', `Request_Amount`='" . $Request_Amount . "', `Difference_Amount`='" . $Difference_Amount . "', `Delivery_Remarks`='" . $Delivery_Remarks . "', `Other_Remarks`='" . $Other_Remarks . "', `Delivery_Time`='" . $Delivery_Time . "', `Receipt_Status`='" . $Receipt_Status . "', `Executive_Name`='" . $Executive_Name . "', `Ref_No`='" . $Ref_No . "', `1st_Visit`='" . $st_Visit1 . "', `2nd_Visit`='" . $st_Visit2 . "', `3rd_Visit`='" . $st_Visit3 . "',`d_old_deno`='" . $d_old_deno . "',`Cost_Of_Operation`='" . $Cost_Of_Operation . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "' WHERE id='$id'";
				$nav = '5';
			} else {
				$query_cus = "INSERT INTO `delivery_field_custome` (`Cust_IDS`, `Client_Code`, `mandate`, `Withdraw_Status`, `Cheque_Counts`, `Bank_Account_No`, `Cheque_No`, `Cheque_Amount`, `Withdraw_Time`, `Request_Received_On`, `Request_Received_Time`, `Delivery_To`, `Cash_Receipt_No`, `Delivery_Amount`, `Request_Amount`, `Difference_Amount`, `Delivery_Remarks`, `Other_Remarks`, `Delivery_Time`, `Receipt_Status`, `Executive_Name`, `Ref_No`, `1st_Visit`, `2nd_Visit`, `3rd_Visit`,d_old_deno, Cost_Of_Operation, `update_by`, `update_date`, `status`) VALUES('" . $cust_id . "','" . $client_id . "','" . $mandate . "','" . $Withdraw_Status . "','" . $Cheque_Counts . "','" . $Bank_Account_No . "','" . $Cheque_No . "','" . $Cheque_Amount . "','" . $Withdraw_Time . "','" . $Request_Received_On . "','" . $Request_Received_Time . "','" . $Delivery_To . "','" . $Cash_Receipt_No . "','" . $Delivery_Amount . "','" . $Request_Amount . "','" . $Difference_Amount . "','" . $Delivery_Remarks . "','" . $Other_Remarks . "','" . $Delivery_Time . "','" . $Receipt_Status . "','" . $Executive_Name . "','" . $Ref_No . "','" . $st_Visit1 . "','" . $st_Visit2 . "','" . $st_Visit3 . "', '" . $d_old_deno . "', '" . $Cost_Of_Operation . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '4';
			}
		} else if ($_POST['service_type'] == 'DD Delivery') {

			if ($_POST['Withdraw_Status'] == '1') $Withdraw_Status = mysqli_real_escape_string($readConnection, $_POST['Withdraw_Status1']);
			else $Withdraw_Status = '';
			if ($_POST['Cheque_Counts'] == '1') $Cheque_Counts = mysqli_real_escape_string($readConnection, $_POST['Cheque_Counts1']);
			else $Cheque_Counts = '';
			if ($_POST['Bank_Account_No'] == '1') $Bank_Account_No = mysqli_real_escape_string($readConnection, $_POST['Bank_Account_No1']);
			else $Bank_Account_No = '';
			if ($_POST['Cheque_No'] == '1') $Cheque_No = mysqli_real_escape_string($readConnection, $_POST['Cheque_No1']);
			else $Cheque_No = '';
			if ($_POST['Cheque_Amount'] == '1') $Cheque_Amount = mysqli_real_escape_string($readConnection, $_POST['Cheque_Amount1']);
			else $Cheque_Amount = '';

			if ($_POST['Withdraw_Time'] == '1') $Withdraw_Time = mysqli_real_escape_string($readConnection, $_POST['Withdraw_Time1']);
			else $Withdraw_Time = '';
			if ($_POST['Request_Received_On'] == '1') $Request_Received_On = mysqli_real_escape_string($readConnection, $_POST['Request_Received_On1']);
			else $Request_Received_On = '';
			if ($_POST['Request_Received_Time'] == '1') $Request_Received_Time = mysqli_real_escape_string($readConnection, $_POST['Request_Received_Time1']);
			else $Request_Received_Time = '';
			if ($_POST['Dd_Delivery_To'] == '1') $Dd_Delivery_To = mysqli_real_escape_string($readConnection, $_POST['Dd_Delivery_To1']);
			else $Dd_Delivery_To = '';
			if ($_POST['Cash_Receipt_No'] == '1') $Cash_Receipt_No = mysqli_real_escape_string($readConnection, $_POST['Cash_Receipt_No1']);
			else $Cash_Receipt_No = '';
			if ($_POST['Dd_Delivery_Amount'] == '1') $Dd_Delivery_Amount = mysqli_real_escape_string($readConnection, $_POST['Dd_Delivery_Amount1']);
			else $Dd_Delivery_Amount = '';
			if ($_POST['Request_Amount'] == '1') $Request_Amount = mysqli_real_escape_string($readConnection, $_POST['Request_Amount1']);
			else $Request_Amount = '';
			if ($_POST['Difference_Amount'] == '1') $Difference_Amount = mysqli_real_escape_string($readConnection, $_POST['Difference_Amount1']);
			else $Difference_Amount = '';
			if ($_POST['Dd_Delivery_Remarks'] == '1') $Dd_Delivery_Remarks = mysqli_real_escape_string($readConnection, $_POST['Dd_Delivery_Remarks1']);
			else $Dd_Delivery_Remarks = '';
			if ($_POST['Other_Remarks'] == '1') $Other_Remarks = mysqli_real_escape_string($readConnection, $_POST['Other_Remarks1']);
			else $Other_Remarks = '';
			if ($_POST['Dd_Delivery_Time'] == '1') $Dd_Delivery_Time = mysqli_real_escape_string($readConnection, $_POST['Dd_Delivery_Time1']);
			else $Dd_Delivery_Time = '';
			if ($_POST['Receipt_Status'] == '1') $Receipt_Status = mysqli_real_escape_string($readConnection, $_POST['Receipt_Status1']);
			else $Receipt_Status = '';
			if ($_POST['Executive_Name'] == '1') $Executive_Name = mysqli_real_escape_string($readConnection, $_POST['Executive_Name1']);
			else $Executive_Name = '';
			if ($_POST['Ref_No'] == '1') $Ref_No = mysqli_real_escape_string($readConnection, $_POST['Ref_No1']);
			else $Ref_No = '';
			if ($_POST['1st_Visit'] == '1') $st_Visit1 = mysqli_real_escape_string($readConnection, $_POST['1st_Visit1']);
			else $st_Visit1 = '';
			if ($_POST['2nd_Visit'] == '1') $st_Visit2 = mysqli_real_escape_string($readConnection, $_POST['2nd_Visit1']);
			else $st_Visit2 = '';
			if ($_POST['3rd_Visit'] == '1') $st_Visit3 = mysqli_real_escape_string($readConnection, $_POST['3rd_Visit1']);
			else $st_Visit3 = '';
			if ($_POST['d_old_deno'] == '1') $d_old_deno = mysqli_real_escape_string($readConnection, $_POST['d_old_deno1']);
			else $st_Visit3 = '';

			if ($id != '') {

				$query_cus = "UPDATE `dd_delivery_field_custome` SET `Cust_IDS`='" . $cust_id . "', `mandate`='" . $mandate . "', `Withdraw_Status`='" . $Withdraw_Status . "', `Cheque_Counts`='" . $Cheque_Counts . "', `Bank_Account_No`='" . $Bank_Account_No . "', `Cheque_No`='" . $Cheque_No . "', `Cheque_Amount`='" . $Cheque_Amount . "', `Withdraw_Time`='" . $Withdraw_Time . "', `Request_Received_On`='" . $Request_Received_On . "', `Request_Received_Time`='" . $Request_Received_Time . "', `Dd_Delivery_To`='" . $Dd_Delivery_To . "', `Cash_Receipt_No`='" . $Cash_Receipt_No . "', `Dd_Delivery_Amount`='" . $Dd_Delivery_Amount . "', `Request_Amount`='" . $Request_Amount . "', `Difference_Amount`='" . $Difference_Amount . "', `Dd_Delivery_Remarks`='" . $Dd_Delivery_Remarks . "', `Other_Remarks`='" . $Other_Remarks . "', `Dd_Delivery_Time`='" . $Dd_Delivery_Time . "', `Receipt_Status`='" . $Receipt_Status . "', `Executive_Name`='" . $Executive_Name . "', `Ref_No`='" . $Ref_No . "', `1st_Visit`='" . $st_Visit1 . "', `2nd_Visit`='" . $st_Visit2 . "', `3rd_Visit`='" . $st_Visit3 . "',`d_old_deno`='" . $d_old_deno . "', `update_by`='" . $user . "', `update_date`='" . $time1 . "' WHERE id='$id'";
				$nav = '5';
			} else {
				$query_cus = "INSERT INTO `dd_delivery_field_custome` (`Cust_IDS`, `Client_Code`, `mandate`, `Withdraw_Status`, `Cheque_Counts`, `Bank_Account_No`, `Cheque_No`, `Cheque_Amount`, `Withdraw_Time`, `Request_Received_On`, `Request_Received_Time`, `Dd_Delivery_To`, `Cash_Receipt_No`, `Dd_Delivery_Amount`, `Request_Amount`, `Difference_Amount`, `Dd_Delivery_Remarks`, `Other_Remarks`, `Dd_Delivery_Time`, `Receipt_Status`, `Executive_Name`, `Ref_No`, `1st_Visit`, `2nd_Visit`, `3rd_Visit`,d_old_deno, `update_by`, `update_date`, `status`) VALUES('" . $cust_id . "','" . $client_id . "','" . $mandate . "','" . $Withdraw_Status . "','" . $Cheque_Counts . "','" . $Bank_Account_No . "','" . $Cheque_No . "','" . $Cheque_Amount . "','" . $Withdraw_Time . "','" . $Request_Received_On . "','" . $Request_Received_Time . "','" . $Dd_Delivery_To . "','" . $Cash_Receipt_No . "','" . $Dd_Delivery_Amount . "','" . $Request_Amount . "','" . $Difference_Amount . "','" . $Dd_Delivery_Remarks . "','" . $Other_Remarks . "','" . $Dd_Delivery_Time . "','" . $Receipt_Status . "','" . $Executive_Name . "','" . $Ref_No . "','" . $st_Visit1 . "','" . $st_Visit2 . "','" . $st_Visit3 . "', '" . $d_old_deno . "', '" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '4';
			}
		} else if ($_POST['service_type'] == 'Cashvan') {
			if ($_POST['from_location'] == '1') $from_location = mysqli_real_escape_string($readConnection, $_POST['from_location1']);
			else $from_location = '';
			if ($_POST['to_location'] == '1') $to_location = mysqli_real_escape_string($readConnection, $_POST['to_location1']);
			else $to_location = '';
			if ($_POST['from_date'] == '1') $from_date = mysqli_real_escape_string($readConnection, $_POST['from_date1']);
			else $from_date = '';
			if ($_POST['to_date'] == '1') $to_date = mysqli_real_escape_string($readConnection, $_POST['to_date1']);
			else $to_date = '';
			if ($_POST['start_kms'] == '1') $start_kms = mysqli_real_escape_string($readConnection, $_POST['start_kms1']);
			else $start_kms = '';
			if ($_POST['end_kms'] == '1') $end_kms = mysqli_real_escape_string($readConnection, $_POST['end_kms1']);
			else $end_kms = '';
			if ($_POST['total_kms'] == '1') $total_kms = mysqli_real_escape_string($readConnection, $_POST['total_kms1']);
			else $total_kms = '';
			/* additional fields */
			if ($_POST['extra_ot'] == '1') $extra_ot = mysqli_real_escape_string($readConnection, $_POST['extra_ot1']);
			else $extra_ot = '';

			if ($_POST['contact_person'] == '1') $contact_person = mysqli_real_escape_string($readConnection, $_POST['contact_person1']);
			else $contact_person = '';
			if ($_POST['contact_number'] == '1') $contact_number = mysqli_real_escape_string($readConnection, $_POST['contact_number1']);
			else $contact_number = '';
			if ($_POST['emailid'] == '1') $emailid = mysqli_real_escape_string($readConnection, $_POST['emailid1']);
			else $emailid = '';
			if ($_POST['field1'] == '1')  $field1 = mysqli_real_escape_string($readConnection, $_POST['field11']);
			else $field1 = '';
			if ($_POST['field2'] == '1')  $field2 = mysqli_real_escape_string($readConnection, $_POST['field21']);
			else $field2 = '';

			/* additional fields */
			if ($_POST['time_in'] == '1') $time_in = mysqli_real_escape_string($readConnection, $_POST['time_in1']);
			else $time_in = '';
			if ($_POST['time_out'] == '1') $time_out = mysqli_real_escape_string($readConnection, $_POST['time_out1']);
			else $time_out = '';
			if ($_POST['total_time'] == '1') $total_time = mysqli_real_escape_string($readConnection, $_POST['total_time1']);
			else $total_time = '';
			if ($_POST['ot_hours'] == '1') $ot_hours = mysqli_real_escape_string($readConnection, $_POST['ot_hours1']);
			else $ot_hours = '';
			if ($_POST['toll_tax'] == '1') $toll_tax = mysqli_real_escape_string($readConnection, $_POST['toll_tax1']);
			else $toll_tax = '';
			if ($_POST['service_category'] == '1') $additional_crew = mysqli_real_escape_string($readConnection, $_POST['service_category1']);
			else $service_category = '';
			if ($_POST['parking_charges'] == '1') $parking_charges = mysqli_real_escape_string($readConnection, $_POST['parking_charges1']);
			else $parking_charges = '';
			if ($_POST['bill_branch'] == '1') $bill_branch = mysqli_real_escape_string($readConnection, $_POST['bill_branch1']);
			else $bill_branch = '';
			if ($_POST['pickup_amount'] == '1') $pickup_amount = mysqli_real_escape_string($readConnection, $_POST['pickup_amount1']);
			else $pickup_amount = '';
			if ($_POST['delivery_amount'] == '1') $delivery_amount = mysqli_real_escape_string($readConnection, $_POST['delivery_amount1']);
			else $delivery_amount = '';
			if ($_POST['vault_status'] == '1') $vault_status = mysqli_real_escape_string($readConnection, $_POST['vault_status1']);
			else $vault_status = '';
			if ($_POST['night_stay'] == '1') $night_stay = mysqli_real_escape_string($readConnection, $_POST['night_stay1']);
			else $night_stay = '';
			if ($_POST['no_trips'] == '1') $no_trips = mysqli_real_escape_string($readConnection, $_POST['no_trips1']);
			else $no_trips = '';
			if ($_POST['remarks'] == '1') $remarks = mysqli_real_escape_string($readConnection, $_POST['remarks1']);
			else $remarks = '';
			//new
			if ($_POST['other_remarks'] == '1') $other_remark = mysqli_real_escape_string($readConnection, $_POST['other_remarks1']);
			else $other_remark = '';
			//			
			if ($_POST['client_acc_no'] == '1') $client_acc_no = mysqli_real_escape_string($readConnection, $_POST['client_acc_no1']);
			else $client_acc_no = '';
			if ($_POST['service_type2'] == '1') $service_type = mysqli_real_escape_string($readConnection, $_POST['service_type1']);
			else $service_type = '';
			if ($_POST['no_cheques'] == '1') $no_cheques = mysqli_real_escape_string($readConnection, $_POST['no_cheques1']);
			else $no_cheques = '';
			if ($_POST['permit_paid'] == '1') $permit_paid = mysqli_real_escape_string($readConnection, $_POST['permit_paid1']);
			else $permit_paid = '';
			if ($_POST['additional_charges'] == '1') $additional_charges = mysqli_real_escape_string($readConnection, $_POST['additional_charges1']);
			else $additional_charges = '';
			if ($_POST['tele_charge'] == '1') $tele_charge = mysqli_real_escape_string($readConnection, $_POST['tele_charge1']);
			else $tele_charge = '';
			if ($_POST['insurance'] == '1') $insurance = mysqli_real_escape_string($readConnection, $_POST['insurance1']);
			else $insurance = '';
			if ($_POST['crew_members'] == '1') $crew_members = mysqli_real_escape_string($readConnection, $_POST['crew_members1']);
			else $crew_members = '';
			if ($_POST['vehicle_no'] == '1') $vehicle_no = mysqli_real_escape_string($readConnection, $_POST['vehicle_no1']);
			else $vehicle_no = '';
			if ($_POST['additional_crew'] == '1') $additional_crew = mysqli_real_escape_string($readConnection, $_POST['additional_crew1']);
			else $additional_crew = '';
			// extra fields //
			if ($_POST['intercity_intracity'] == '1')
				$intercity = mysqli_real_escape_string($readConnection, $_POST['intercity_intracity1']);
			else $intercity = '';

			if ($_POST['location'] == '1') $location = mysqli_real_escape_string($readConnection, $_POST['location1']);
			else $location = '';
			if ($_POST['from_branchcode'] == '1') $frombranchcode = mysqli_real_escape_string($readConnection, $_POST['from_branchcode1']);
			else $frombranchcode = '';
			if ($_POST['to_branchcode'] == '1') $tobranchcode = mysqli_real_escape_string($readConnection, $_POST['to_branchcode1']);
			else $tobranchcode = '';
			if ($_POST['oneway_twoway'] == '1') $oneway = mysqli_real_escape_string($readConnection, $_POST['oneway_twoway1']);
			else $oneway = '';
			if ($_POST['airport_parkingcharges'] == '1') $airportcharge = mysqli_real_escape_string($readConnection, $_POST['airport_parkingcharges1']);
			else $airportcharge = '';
			if ($_POST['costofoperation'] == '1') $costofoperation = mysqli_real_escape_string($readConnection, $_POST['costofoperation1']);
			else $costofoperation = '';
			if ($_POST['category'] == '1') $category = mysqli_real_escape_string($readConnection, $_POST['category1']);
			else $category = '';
			if ($_POST['additional_loaders'] == '1') $additionalloaders = mysqli_real_escape_string($readConnection, $_POST['additional_loaders1']);
			else $additionalloaders = '';

			// extra fields //



			if ($id != '') {
				$query_cus = "UPDATE cashvan_dynamic_custom SET cust_id='" . $cust_id . "',mandate='" . $mandate . "',from_location='" . $from_location . "',to_location='" . $to_location . "',from_date='" . $from_date . "',to_date='" . $to_date . "',start_kms='" . $start_kms . "',end_kms='" . $end_kms . "',total_kms='" . $total_kms . "',time_in='" . $time_in . "',time_out='" . $time_out . "',total_time='" . $total_time . "',ot_hours='" . $ot_hours . "',toll_tax='" . $toll_tax . "',parking_charges='" . $parking_charges . "',bill_branch='" . $bill_branch . "',pickup_amount='" . $pickup_amount . "',delivery_amount='" . $delivery_amount . "',vault_status='" . $vault_status . "',night_stay='" . $night_stay . "',no_trips='" . $no_trips . "',remarks='" . $remarks . "',client_acc_no='" . $client_acc_no . "',service_type='" . $service_type . "',no_cheques='" . $no_cheques . "',permit_paid='" . $permit_paid . "',additional_charges='" . $additional_charges . "',tele_charge='" . $tele_charge . "',insurance='" . $insurance . "',crew_members='" . $crew_members . "',vehicle_no='" . $vehicle_no . "',extra_ot = '" . $extra_ot . "',contact_person = '" . $contact_person . "',contact_number = '" . $contact_number . "',emailid = '" . $emailid . "',field1 = '" . $field1 . "',field2 = '" . $field2 . "',additional_crew='" . $additional_crew . "',intercity_intracity='" . $intercity . "',location='" . $location . "',from_branchcode='" . $frombranchcode . "',to_branchcode='" . $tobranchcode . "',oneway_twoway='" . $oneway . "',airport_parkingcharges='" . $airportcharge . "',costofoperation='" . $costofoperation . "',category='" . $category . "',additional_loaders='" . $additionalloaders . "',updated_by='" . $user . "',updated_date='" . $time1 . "',other_remarks='" . $other_remark . "' WHERE id='$id' and status='Y' ";
				$nav = '12';
			} else {
				$query_cus = "INSERT INTO cashvan_dynamic_custom(client_id,cust_id,mandate,from_location,to_location,from_date,to_date,start_kms,end_kms,total_kms,extra_ot,contact_person,contact_number,emailid,field1,field2,time_in,time_out,total_time,ot_hours,toll_tax,parking_charges,bill_branch,pickup_amount,delivery_amount,vault_status,night_stay,no_trips,remarks,other_remarks,client_acc_no,service_type,no_cheques,permit_paid,additional_charges,tele_charge,insurance,crew_members,vehicle_no,intercity_intracity,location,from_branchcode,to_branchcode,oneway_twoway,airport_parkingcharges,costofoperation,category,additional_loaders,additional_crew,created_by,created_date,status) VALUES('" . $client_id . "','" . $cust_id . "','" . $mandate . "','" . $from_location . "','" . $to_location . "','" . $from_date . "','" . $to_date . "','" . $start_kms . "','" . $end_kms . "','" . $total_kms . "','" . $extra_ot . "','" . $contact_person . "','" . $contact_number . "','" . $emailid . "','" . $field1 . "','" . $field2 . "','" . $time_in . "','" . $time_out . "','" . $total_time . "','" . $ot_hours . "','" . $toll_tax . "','" . $parking_charges . "','" . $bill_branch . "','" . $pickup_amount . "','" . $delivery_amount . "','" . $vault_status . "','" . $night_stay . "','" . $no_trips . "','" . $remarks . "','" . $other_remark . "','" . $client_acc_no . "','" . $service_type . "','" . $no_cheques . "','" . $permit_paid . "','" . $additional_charges . "','" . $tele_charge . "','" . $insurance . "','" . $crew_members . "','" . $vehicle_no . "','" . $additional_crew . "','" . $intercity . "','" . $location . "','" . $frombranchcode . "','" . $tobranchcode . "','" . $oneway . "','" . $airportcharge . "','" . $costofoperation . "','" . $category . "','" . $additionalloaders . "','" . $user . "', '" . $time1 . "', 'Y')";
				$nav = '11';
			}
		} else if ($_POST['service_type'] == 'MBC') {
			if ($_POST['in_time'] == '1') $in_time = mysqli_real_escape_string($readConnection, $_POST['in_time1']);
			else $in_time = '';
			if ($_POST['out_time'] == '1') $out_time = mysqli_real_escape_string($readConnection, $_POST['out_time1']);
			else $out_time = '';
			if ($_POST['total_time'] == '1') $total_time = mysqli_real_escape_string($readConnection, $_POST['total_time1']);
			else $total_time = '';
			if ($_POST['over_time'] == '1') $over_time = mysqli_real_escape_string($readConnection, $_POST['over_time1']);
			else $over_time = '';
			if ($_POST['deposit_slip_no'] == '1') $deposit_slip_no = mysqli_real_escape_string($readConnection, $_POST['deposit_slip_no1']);
			else $deposit_slip_no = '';
			if ($_POST['seal_tag'] == '1') $seal_tag = mysqli_real_escape_string($readConnection, $_POST['seal_tag1']);
			else $seal_tag = '';
			if ($_POST['hic_slip_no'] == '1') $hic_slip_no = mysqli_real_escape_string($readConnection, $_POST['hic_slip_no1']);
			else $hic_slip_no = '';
			if ($_POST['cust_gene_no'] == '1') $cust_gene_no = mysqli_real_escape_string($readConnection, $_POST['cust_gene_no1']);
			else $cust_gene_no = '';
			if ($_POST['pick_amt'] == '1') $pick_amt = mysqli_real_escape_string($readConnection, $_POST['pick_amt1']);
			else $pick_amt = '';
			if ($_POST['currency_denomi'] == '1') $currency_denomi = mysqli_real_escape_string($readConnection, $_POST['currency_denomi1']);
			else $currency_denomi = '';
			if ($_POST['deposit_type'] == '1') $deposit_type = mysqli_real_escape_string($readConnection, $_POST['deposit_type1']);
			else $deposit_type = '';
			if ($_POST['acc_no'] == '1') $acc_no = mysqli_real_escape_string($readConnection, $_POST['acc_no1']);
			else $acc_no = '';
			if ($_POST['cheque_no'] == '1') $cheque_no = mysqli_real_escape_string($readConnection, $_POST['cheque_no1']);
			else $cheque_no = '';
			if ($_POST['receipt_no'] == '1') $receipt_no = mysqli_real_escape_string($readConnection, $_POST['receipt_no1']);
			else $receipt_no = '';
			if ($_POST['hcl_slip_no'] == '1') $hcl_slip_no = mysqli_real_escape_string($readConnection, $_POST['hcl_slip_no1']);
			else $hcl_slip_no = '';
			if ($_POST['cheque_amt'] == '1') $cheque_amt = mysqli_real_escape_string($readConnection, $_POST['cheque_amt1']);
			else $cheque_amt = '';
			if ($_POST['bank_name'] == '1') $bank_name = mysqli_real_escape_string($readConnection, $_POST['bank_name1']);
			else $bank_name = '';
			if ($_POST['account_no'] == '1') $account_no = mysqli_real_escape_string($readConnection, $_POST['account_no1']);
			else $account_no = '';
			if ($_POST['remarks'] == '1') $remarks = mysqli_real_escape_string($readConnection, $_POST['remarks1']);
			else $remarks = '';


			if ($id != '') {
				$query_cus = "UPDATE mbc_custom SET mandate='" . $mandate . "',in_time='" . $in_time . "',out_time='" . $out_time . "',total_time='" . $total_time . "',over_time='" . $over_time . "',deposit_slip_no='" .

					$deposit_slip_no . "',seal_tag='" . $seal_tag . "',hic_slip_no='" . $hic_slip_no . "',cust_gene_no='" . $cust_gene_no . "',pick_amt='" . $pick_amt . "',currency_denomi='" . $currency_denomi . "',deposit_type='" . $deposit_type . "',acc_no='" .

					$acc_no . "',cheque_no='" . $cheque_no . "',receipt_no='" . $receipt_no . "',hcl_slip_no='" . $hcl_slip_no . "',cheque_amt='" . $cheque_amt . "',bank_name='" . $bank_name . "',account_no='" . $account_no . "',remarks='" . $remarks . "',updated_by='" .

					$user . "',updated_date=now() WHERE id='$id' and status='Y' ";
				$nav = '14';
			} else {
				echo $query_cus = "INSERT INTO mbc_custom(client_id,cust_id,mandate,in_time,out_time,total_time,over_time,deposit_slip_no,seal_tag,hic_slip_no,cust_gene_no, 

pick_amt,currency_denomi,deposit_type,acc_no,cheque_no,receipt_no,hcl_slip_no,cheque_amt,bank_name,account_no,remarks,created_by,created_date,status) VALUES('" . $client_id . "','" . $cust_id . "','" . $mandate . "','" . $in_time . "','" .

					$out_time . "','" . $total_time . "','" . $over_time . "','" . $deposit_slip_no . "','" . $seal_tag . "','" . $hic_slip_no . "','" . $cust_gene_no . "','" . $pick_amt . "','" . $currency_denomi . "','" . $deposit_type . "','" . $acc_no . "','" . $cheque_no . "','" .

					$receipt_no . "','" . $hcl_slip_no . "','" . $cheque_amt . "','" . $bank_name . "','" . $account_no . "','" . $remarks . "','" . $user . "', now(), 'Y')";
				$nav = '13';
			}
		}

		$sql_cus = mysqli_query($writeConnection, $query_cus);
		if ($sql_cus == 1) {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=" . $nav);
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2");
		}
	}

	//Android Device Receipt Details
	else if ($pid == "mf_trans") {
		$mfrec_id = $_POST['mfrec_id'];
		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);
		$from1 = date("d-M-Y");
		$update_time = $from1 . ", " . $time;

		$sql1 = "SELECT * FROM  mf_receipt WHERE mfrec_id='" . $mfrec_id . "' AND status='Y'";
		$qu1 = mysqli_query($sql1);
		while ($r1 = mysqli_fetch_array($qu1)) {

			$sql33 = "SELECT trans_id FROM daily_collection WHERE trans_id='" . $r1['trans_id'] . "' AND status='Y'";
			$qu33 = mysqli_query($sql33);
			$n33 = mysqli_num_rows($qu33);
			if ($n33 == 0) {
				// If New collection entry available
				$sql2 = "select pickup_amount as req_amount from daily_trans where trans_id='" . $r1['trans_id'] . "' and status='Y'";
				$qu2 = mysqli_query($sql2);
				$r2 = mysqli_fetch_assoc($qu2);

				if ($r1['rec_type'] == "M") {
					$multi_rec = "Y";
					$sql4 = "SELECT * FROM mf_receipt_mul WHERE  mfrec_id='" . $mfrec_id . "' AND status='Y' ORDER BY mfrec_infoid";
					$qu4 = mysqli_query($sql4);
					$n4 = mysqli_num_rows($qu4);
					$t_rec = $n4;
					$c_code = $rec_no = $pis_hcl_no = $hcl_no = $deposit_slip = "MULTI";
				} else {
					$multi_rec = "N";
					$t_rec = 1;
					$c_code = $r1['client_code'];
					$rec_no = $r1['sealtag_no'];
					$pis_hcl_no = $r1['deposit_slip'];
					$hcl_no = $r1['hci_no'];
					$deposit_slip = $r1['pis_no'];
				}

				$diff_amount = $r2['req_amount'] - $r1['pickup_amount'];
				$coll_date = date("Y-m-d", strtotime($r1['update_time']));
				$coll_time = date("h:i:s A", strtotime($r1['update_time']));

				$sql12 = "INSERT INTO daily_collection (trans_id, multi_rec, t_rec, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, req_amount, pick_amount, diff_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins,o1000s, o500s, dep_type1, dep_accid, dep_branch, dep_slip, dep_amount1, coll_remarks, other_remarks, pick_time, staff_id, dep_amount2, dep_bank2, other_remarks2, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", '" . $multi_rec . "', " . $t_rec . ", '" . $c_code . "', '" . $rec_no . "', '" . $pis_hcl_no . "', '" . $hcl_no . "', '" . $deposit_slip . "', '" . $r2['req_amount'] . "', '" . $r1['pickup_amount'] . "', '" . $diff_amount . "', '" . $r1['no_2000'] . "','" . $r1['no_1000'] . "', '" . $r1['no_500'] . "', '" . $r1['no_100'] . "', '" . $r1['no_50'] . "', '" . $r1['no_20'] . "', '" . $r1['no_10'] . "', '" . $r1['no_5'] . "', '" . $r1['no_coins'] . "','" . $r1['ono_1000'] . "', '" . $r1['ono_500'] . "', '" . $r1['dep_type'] . "', '" . $r1['account_no'] . "', '" . $r1['branch_name'] . "', '" . $r1['deposit_slip_no'] . "', '" . $r1['dep_amount'] . "', '" . $r1['rec_status'] . "', '" . $r1['remarks'] . "', '" . $r1['update_time'] . "', '" . $r1['update_by'] . "', 0, '', '', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";
				//echo $sql12;
				$qu12 = mysqli_query($sql12);
				$i = 1;
				if ($r1['rec_type'] == "M") {
					while ($r4 = mysqli_fetch_array($qu4)) {

						$sql11 = "INSERT INTO daily_collectionmul (trans_id, rec_id, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, req_amount, diff_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins,o1000s, o500s, pick_amount, mul_remarks, staff_id, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", " . $i . ", '" . $r4['client_code'] . "', '" . $r4['sealtag_no'] . "', '" . $r4['deposit_slip'] . "', '" . $r4['hci_no'] . "', '" . $r4['pis_no'] . "', 0, 0, '" . $r4['no_2000'] . "','" . $r4['no_1000'] . "', '" . $r4['no_500'] . "', '" . $r4['no_100'] . "', '" . $r4['no_50'] . "', '" . $r4['no_20'] . "', '" . $r4['no_10'] . "', '" . $r4['no_5'] . "', '" . $r4['no_coins'] . "','" . $r4['ono_1000'] . "', '" . $r4['ono_500'] . "', '" . $r4['pickup_amount'] . "', '" . $r4['mul_remarks'] . "', '" . $r4['update_by'] . "', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";
						$qu11 = mysqli_query($sql11);
						$i++;
					}
				}


				if ($qu12 == 1) {
					$sql13 = " UPDATE mf_receipt SET checked_by='" . $user . "', checked_time='" . $update_time . "' WHERE mfrec_id='" . $mfrec_id . "' AND status='Y'";
					$qu13 = mysqli_query($sql13);
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_3");
				} else {
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_4");
				}
			} else {
				// If Already collection entry available
				$sql2 = "SELECT pickup_amount AS req_amount FROM  daily_trans WHERE trans_id='" . $r1['trans_id'] . "' AND status='Y'";
				$qu2 = mysqli_query($sql2);
				$r2 = mysqli_fetch_assoc($qu2);

				if ($r1['rec_type'] == "M") {
					$multi_rec = "Y";
					$sql4 = "SELECT * FROM mf_receipt_mul WHERE mfrec_id='" . $mfrec_id . "' AND status='Y' ORDER BY mfrec_infoid";
					$qu4 = mysqli_query($sql4);
					$n4 = mysqli_num_rows($qu4);
					$t_rec = $n4;
					$c_code = $rec_no = $pis_hcl_no = $hcl_no = $deposit_slip = "MULTI";
				} else {
					$multi_rec = "N";
					$t_rec = 1;
					$c_code = $r1['client_code'];
					$rec_no = $r1['sealtag_no'];
					$pis_hcl_no = $r1['deposit_slip'];
					$hcl_no = $r1['hci_no'];
					$deposit_slip = $r1['pis_no'];
				}

				$diff_amount = $r2['req_amount'] - $r1['pickup_amount'];
				$coll_date = date("Y-m-d", strtotime($r1['update_time']));
				$coll_time = date("h:i:s A", strtotime($r1['update_time']));

				$sql12 = "UPDATE daily_collection set multi_rec='" . $multi_rec . "', t_rec=" . $t_rec . ", c_code='" . $c_code . "', rec_no='" . $rec_no . "', pis_hcl_no='" . $pis_no . "', hcl_no='" . $hcl_no . "', gen_slip='" . $deposit_slip . "', req_amount='" . $r2['req_amount'] . "', pick_amount='" . $r1['pickup_amount'] . "', diff_amount='" . $diff_amount . "', 2000s='" . $r1['no_2000'] . "',1000s='" . $r1['no_1000'] . "', 500s='" . $r1['no_500'] . "', 100s='" . $r1['no_100'] . "', 50s='" . $r1['no_50'] . "', 20s='" . $r1['no_20'] . "', 10s='" . $r1['no_10'] . "', 5s='" . $r1['no_5'] . "', coins='" . $r1['no_coins'] . "',o1000s='" . $r1['ono_1000'] . "', o500s='" . $r1['ono_500'] . "', dep_type1='" . $r1['dep_type'] . "', dep_accid='" . $r1['account_no'] . "', dep_branch='" . $r1['branch_name'] . "', dep_slip='" . $r1['deposit_slip_no'] . "', dep_amount1='" . $r1['dep_amount'] . "', coll_remarks='" . $r1['rec_status'] . "', other_remarks='" . $r1['remarks'] . "', pick_time='" . $r1['update_time'] . "', staff_id='" . $r1['update_by'] . "', dep_amount2=0, dep_bank2='', other_remarks2='', coll_date='" . $coll_date . "', coll_time='" . $coll_time . "', user_name='" . $user . "' where trans_id=" . $r1['trans_id'] . " and status='Y'";


				$qu12 = mysqli_query($sql12);
				$i = 1;
				if ($r1['rec_type'] == "M") {
					while ($r4 = mysqli_fetch_array($qu4)) {
						$sqld = "DELETE FROM daily_collectionmul WHERE trans_id='" . $r1['trans_id'] . "' ";
						$qud = mysqli_query($sqld);
						$sql11 = "INSERT INTO daily_collectionmul (trans_id, rec_id, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, req_amount, diff_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins,o1000s, o500s, pick_amount, mul_remarks, staff_id, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", " . $i . ", '" . $r4['client_code'] . "', '" . $r4['sealtag_no'] . "', '" . $r4['deposit_slip'] . "', '" . $r4['hci_no'] . "', '" . $r4['pis_no'] . "', 0, 0, '" . $r4['no_2000'] . "','" . $r4['no_1000'] . "', '" . $r4['no_500'] . "', '" . $r4['no_100'] . "', '" . $r4['no_50'] . "', '" . $r4['no_20'] . "', '" . $r4['no_10'] . "', '" . $r4['no_5'] . "', '" . $r4['no_coins'] . "','" . $r4['ono_1000'] . "', '" . $r4['ono_500'] . "', '" . $r4['pickup_amount'] . "', '" . $r4['mul_remarks'] . "', '" . $r4['update_by'] . "', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";
						//echo $sql11;
						$qu11 = mysqli_query($sql11);
						$i++;
					}
				}

				if ($qu12 == 1) {
					$sql13 = "UPDATE mf_receipt SET checked_by='" . $user . "', checked_time='" . $update_time . "' WHERE mfrec_id='" . $mfrec_id . "' AND status='Y'";
					$qu13 = mysqli_query($sql13);
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_3");
				} else {
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_4");
				}
			}
		}
	}

	//Handheld Device Receipt Details
	else if ($pid == "hd_trans") {
		$mfrec_id = $_POST['mfrec_id'];

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);
		$from1 = date("d-M-Y");
		$update_time = $from1 . ", " . $time;

		$sql1 = "SELECT * FROM hd_receipt WHERE hdrec_id='" . $mfrec_id . "' AND status='Y'";
		$qu1 = mysqli_query($readConnection, $sql1);
		while ($r1 = mysqli_fetch_array($qu1)) {

			$sql33 = "SELECT trans_id FROM daily_collection WHERE trans_id='" . $r1['trans_id'] . "' AND status='Y'";
			$qu33 = mysqli_query($readConnection, $sql33);
			$n33 = mysqli_num_rows($qu33);

			if ($n33 == 0) {
				// If New collection entry available
				$sql2 = "SELECT pickup_amount AS req_amount FROM daily_trans WHERE trans_id='" . $r1['trans_id'] . "' AND status='Y'";
				$qu2 = mysqli_query($readconnection, $sql2);
				$r2 = mysqli_fetch_assoc($qu2);

				if ($r1['rec_type'] == "M") {
					$multi_rec = "Y";
					$sql4 = "SELECT * FROM hd_receipt_mul WHERE hdrec_id='" . $mfrec_id . "' AND status='Y' ORDER BY hdrecmul_id";
					$qu4 = mysqli_query($readConnection, $sql4);
					$n4 = mysqli_num_rows($qu4);
					$t_rec = $n4;
					$c_code = $rec_no = $pis_hcl_no = $hcl_no = "MULTI";
				} else {
					$multi_rec = "N";
					$t_rec = 1;
					$c_code = "";
					$rec_no = $r1['rec_no'];
					$pis_hcl_no = $r1['pis_no'];
					$hcl_no = $r1['hci_no'];
				}

				$diff_amount = $r2['req_amount'] - $r1['pickup_amount'];
				$coll_date = date("Y-m-d", strtotime($r1['update_time']));
				$coll_time = date("h:i:s A", strtotime($r1['update_time']));

				$sql12 = "INSERT INTO daily_collection (trans_id, multi_rec, t_rec, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, req_amount, pick_amount, diff_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins, dep_type1, dep_accid, dep_branch, dep_slip, dep_amount1, coll_remarks, other_remarks, pick_time, staff_id, dep_amount2, dep_bank2, other_remarks2, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", '" . $multi_rec . "', " . $t_rec . ", '" . $c_code . "', '" . $rec_no . "', '" . $r1['sealtag_no'] . "', '" . $hcl_no . "', '" . $pis_hcl_no . "', " . $r2['req_amount'] . ", " . $r1['pickup_amount'] . ", " . $diff_amount . "," . $r1['no_2000'] . ", " . $r1['no_1000'] . ", " . $r1['no_500'] . ", " . $r1['no_100'] . ", " . $r1['no_50'] . ", " . $r1['no_20'] . ", " . $r1['no_10'] . ", " . $r1['no_5'] . ", " . $r1['no_coins'] . ", '" . $r1['dep_type'] . "', '" . $r1['account_no'] . "', '" . $r1['branch_name'] . "', '" . $r1['deposit_slip_no'] . "', " . $r1['dep_amount'] . ", '" . $r1['rec_status'] . "', '" . $r1['remarks'] . "', '" . $r1['update_time'] . "', '" . $r1['update_by'] . "', 0, '', '', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";

				$qu12 = mysqli_query($writeConnection, $sql12);
				$i = 1;
				if ($r1['rec_type'] == "M") {
					while ($r4 = mysqli_fetch_array($qu4)) {
						$client_code1 = explode("-", $r4['client_code']);

						$sql11 = "INSERT INTO daily_collectionmul (trans_id, rec_id, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, pick_amount, req_amount, diff_amount,2000s, 1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins,  mul_remarks, staff_id, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", " . $i . ", '" . $client_code1[0] . "', '" . $r4['rec_no'] . "', '" . $r4['sealtag_no'] . "', '" . $r4['hci_no'] . "', '" . $r4['pis_no'] . "', " . $r4['pickup_amount'] . ", 0, 0, " . $r4['no_2000'] . "," . $r4['no_1000'] . ", " . $r4['no_500'] . ", " . $r4['no_100'] . ", " . $r4['no_50'] . ", " . $r4['no_20'] . ", " . $r4['no_10'] . ", " . $r4['no_5'] . ", " . $r4['no_coins'] . ", '" . $r4['mrec_status'] . "', '" . $r4['update_by'] . "', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";

						$qu11 = mysqli_query($writeConnection, $sql11);
						$i++;
					}
				}


				if ($qu12 == 1) {
					$sql13 = "UPDATE hd_receipt SET checked_by='" . $user . "', checked_time='" . $update_time . "' WHERE hdrec_id='" . $mfrec_id . "' AND status='Y'";
					$qu13 = mysqli_query($writeConnection, $sql13);
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&&t_rec=$t_rec&nav=2_3");
				} else {
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_4");
				}
			} else {
				// If Already collection entry available
				$sql2 = "SELECT pickup_amount AS req_amount FROM  daily_trans WHERE trans_id='" . $r1['trans_id'] . "' AND status='Y'";
				$qu2 = mysqli_query($readConnection, $sql2);
				$r2 = mysqli_fetch_assoc($qu2);

				if ($r1['rec_type'] == "M") {
					$multi_rec = "Y";
					$sql4 = "SELECT * FROM hd_receipt_mul WHERE hdrec_id='" . $mfrec_id . "' AND status='Y' ORDER BY hdrecmul_id";
					$qu4 = mysqli_query($readConnection, $sql4);
					$n4 = mysqli_num_rows($qu4);
					$t_rec = $n4;
					$c_code = $rec_no = $pis_hcl_no = $hcl_no = "MULTI";
				} else {
					$multi_rec = "N";
					$t_rec = 1;
					$c_code = "";
					$rec_no = $r1['rec_no'];
					$pis_hcl_no = $r1['pis_no'];
					$hcl_no = $r1['hci_no'];
				}

				$diff_amount = $r2['req_amount'] - $r1['pickup_amount'];
				$coll_date = date("Y-m-d", strtotime($r1['update_time']));
				$coll_time = date("h:i:s A", strtotime($r1['update_time']));

				$sql12 = "UPDATE daily_collection set multi_rec='" . $multi_rec . "', t_rec=" . $t_rec . ", c_code='" . $c_code . "', rec_no='" . $rec_no . "', pis_hcl_no='" . $r1['sealtag_no'] . "', hcl_no='" . $hcl_no . "', gen_slip='" . $pis_hcl_no . "', req_amount=" . $r2['req_amount'] . ", pick_amount=" . $r1['pickup_amount'] . ", diff_amount=" . $diff_amount . ",2000s=" . $r1['no_2000'] . ", 1000s=" . $r1['no_1000'] . ", 500s=" . $r1['no_500'] . ", 100s=" . $r1['no_100'] . ", 50s=" . $r1['no_50'] . ", 20s=" . $r1['no_20'] . ", 10s=" . $r1['no_10'] . ", 5s=" . $r1['no_5'] . ", coins=" . $r1['no_coins'] . ", dep_type1='" . $r1['dep_type'] . "', dep_accid='" . $r1['account_no'] . "', dep_branch='" . $r1['branch_name'] . "', dep_slip='" . $r1['deposit_slip_no'] . "', dep_amount1=" . $r1['dep_amount'] . ", coll_remarks='" . $r1['rec_status'] . "', other_remarks='" . $r1['remarks'] . "', pick_time='" . $r1['update_time'] . "', staff_id='" . $r1['update_by'] . "', dep_amount2=0, dep_bank2='', other_remarks2='', coll_date='" . $coll_date . "', coll_time='" . $coll_time . "', user_name='" . $user . "' where trans_id=" . $r1['trans_id'] . " and status='Y'";
				//echo $sql12;
				$qu12 = mysqli_query($writeConnection, $sql12);
				$i = 1;
				if ($r1['rec_type'] == "M") {
					while ($r4 = mysqli_fetch_array($qu4)) {
						$sqld = "DELETE FROM daily_collectionmul WHERE trans_id='" . $r1['trans_id'] . "' ";
						$qud = mysqli_query($writeConnection, $sqld);

						$client_code1 = explode("-", $r4['client_code']);

						$sql11 = "INSERT INTO daily_collectionmul (trans_id, rec_id, c_code, rec_no, pis_hcl_no, hcl_no, gen_slip, pick_amount, req_amount, diff_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins,  mul_remarks, staff_id, coll_date, coll_time, user_name, status) VALUES (" . $r1['trans_id'] . ", " . $i . ", '" . $client_code1[0] . "', '" . $r4['rec_no'] . "', '" . $r4['sealtag_no'] . "', '" . $r4['hci_no'] . "','" . $r4['pis_no'] . "', " . $r4['pickup_amount'] . ", 0, 0, " . $r4['no_2000'] . "," . $r4['no_1000'] . ", " . $r4['no_500'] . ", " . $r4['no_100'] . ", " . $r4['no_50'] . ", " . $r4['no_20'] . ", " . $r4['no_10'] . ", " . $r4['no_5'] . ", " . $r4['no_coins'] . ", '" . $r4['mrec_status'] . "', '" . $r4['update_by'] . "', '" . $coll_date . "', '" . $coll_time . "', '" . $user . "', 'Y')";

						$qu11 = mysqli_query($writeConnection, $sql11);
						$i++;
					}
				}

				if ($qu12 == 1) {
					$sql13 = "UPDATE hd_receipt SET checked_by='" . $user . "', checked_time='" . $update_time . "' WHERE hdrec_id='" . $mfrec_id . "' AND status='Y'";
					$qu13 = mysqli_query($writeConnection, $sql13);
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_3");
				} else {
					// mysqli_close($conn);
					header("Location:../?pid=$pid&id=$mfrec_id&t_rec=$t_rec&nav=2_4");
				}
			}
		}
	} elseif ($pid == "dc_trans") {
		$upload_id = $_POST['upload_id'];
		$check_status = $_POST['check_status'];
		$remarks = $_POST['remarks'];

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);
		$from1 = date("d-M-Y");
		$update_time = $from1 . ", " . $time;

		echo $sql = "UPDATE dcomb_trans SET check_status='" . $check_status . "', check_remarks='" . $remarks . "', checked_by='" . $user . "', checked_time='" . $update_time . "' WHERE upload_id='" . $upload_id . "' ";

		if (mysqli_query($writeConnection, $sql) == 1) {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_3");
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_4");
		}
	}



	//Cash Van Details
	else if ($pid == 'Cash_Van_Details') {
		//echo "<pre>";print_r($_POST); die;
		$id = mysqli_real_escape_string($readConnection, $_POST['id']);
		$Registration_NO = mysqli_real_escape_string($readConnection, $_POST['Registration_NO']);
		$ref_no = mysqli_real_escape_string($readConnection, $_POST['ref_no']);
		$loc_deploy = mysqli_real_escape_string($readConnection, $_POST['loc_deploy']);
		$vec_model = mysqli_real_escape_string($readConnection, $_POST['vec_model']);
		$vec_no = mysqli_real_escape_string($readConnection, $_POST['vec_no']);
		$Engine_NO = mysqli_real_escape_string($readConnection, $_POST['Engine_NO']);
		$Chassis_No = mysqli_real_escape_string($readConnection, $_POST['Chassis_No']);

		if ($_POST['date_of_purchase'] != '') {
			$date_of_purchase = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['date_of_purchase'])));
		} else {
			$date_of_purchase = '0000-00-00';
		}

		if ($_POST['FC_Due_date'] != '') {
			$FC_Due_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['FC_Due_date'])));
		} else {
			$FC_Due_date = '0000-00-00';
		}
		$Insurance_Company = mysqli_real_escape_string($readConnection, $_POST['Insurance_Company']);
		$Insurance_No = mysqli_real_escape_string($readConnection, $_POST['Insurance_No']);
		if ($_POST['ins_expiry_date'] != '') {
			$ins_expiry_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['ins_expiry_date'])));
		} else {
			$ins_expiry_date = '0000-00-00';
		}
		$road_tax_expiry_date = mysqli_real_escape_string($readConnection, $_POST['road_tax_expiry_date']);
		$vec_maint = mysqli_real_escape_string($readConnection, $_POST['vec_maint']);
		$misc_exp = mysqli_real_escape_string($readConnection, $_POST['misc_exp']);
		$vec_incharge = mysqli_real_escape_string($readConnection, $_POST['vec_incharge']);
		$hypo_with = mysqli_real_escape_string($readConnection, $_POST['hypo_with']);
		$vehicle_type = mysqli_real_escape_string($readConnection, $_POST['vehicle_type']);

		//new fields 24-11-2023

		$van_type = mysqli_real_escape_string($readConnection, $_POST['van_type']);
		$rbi_guide = mysqli_real_escape_string($readConnection, $_POST['rbi_guide']);
		//

		$bal_amt = mysqli_real_escape_string($readConnection, $_POST['bal_amt']);
		$expense = mysqli_real_escape_string($readConnection, $_POST['expense']);
		$pollution_serial_no = mysqli_real_escape_string($readConnection, $_POST['pollution_serial_no']);

		if ($_POST['pollution_expiry_date'] != '') {
			$pollution_expiry_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['pollution_expiry_date'])));
		} else {
			$pollution_expiry_date = '0000-00-00';
		}
		$road_tax_amt = mysqli_real_escape_string($readConnection, $_POST['road_tax_amt']);
		$idv_value = mysqli_real_escape_string($readConnection, $_POST['idv_value']);
		$insurance_premium_amt = mysqli_real_escape_string($readConnection, $_POST['insurance_premium_amt']);


		$Owner_Name = mysqli_real_escape_string($readConnection, $_POST['Owner_Name']);
		$Owner_mobile = mysqli_real_escape_string($readConnection, $_POST['Owner_mobile']);
		$remarks = mysqli_real_escape_string($readConnection, $_POST['remarks']);

		$tool_kit = mysqli_real_escape_string($readConnection, $_POST['tool_kit']);
		$maintenance_budget = mysqli_real_escape_string($readConnection, $_POST['maintenance_budget']);
		$fuel_type = mysqli_real_escape_string($readConnection, $_POST['fuel_type']);

		if ($_POST['permit_due_date'] != '') {
			$permit_due_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['permit_due_date'])));
		} else {
			$permit_due_date = '0000-00-00';
		}

		// $front_cctv1_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['front_cctv1_install_date'])));
		// $front_cctv1_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['front_cctv1_warranty_date'])));
		// $front_cctv2_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['front_cctv2_install_date'])));
		// $front_cctv2_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['front_cctv2_warranty_date'])));
		// $rear_cctv3_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['rear_cctv3_install_date'])));
		// $rear_cctv3_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['rear_cctv3_warranty_date'])));
		// $outside_cctv4_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['outside_cctv4_install_date'])));
		// $outside_cctv4_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['outside_cctv4_warranty_date'])));
		// $driver_cabin_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['driver_cabin_install_date'])));
		// $driver_cabin_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['driver_cabin_warranty_date'])));
		// $monitor_install_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['monitor_install_date'])));
		// $monitor_warranty_date=mysqli_real_escape_string(date('Y-m-d',strtotime($_POST['monitor_warranty_date'])));


		$cctv_type = mysqli_real_escape_string($readConnection, $_POST['cctv_type']);
		$cctv_title = mysqli_real_escape_string($readConnection, $_POST['cctv_title']);
		$camera_count = mysqli_real_escape_string($readConnection, $_POST['camera_count']);
		$monitor_count = mysqli_real_escape_string($readConnection, $_POST['monitor_count']);


		if ($_POST['install_date'] != '') {
			$install_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['install_date'])));
		} else {
			$install_date = '0000-00-00';
		}
		if ($_POST['warranty_date'] != '') {
			$warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['warranty_date'])));
		} else {
			$warranty_date = '0000-00-00';
		}
		$camera_type = mysqli_real_escape_string($readConnection, $_POST['camera_type']);
		$dvr_serial_no = mysqli_real_escape_string($readConnection, $_POST['dvr_serial_no']);
		$memory_type = mysqli_real_escape_string($readConnection, $_POST['memory_type']);

		$sec_cam_back_outside = mysqli_real_escape_string($readConnection, $_POST['2cam_back_outside']);
		$sec_cam_back_out_count = mysqli_real_escape_string($readConnection, $_POST['2cam_back_out_count']);
		$sec_monitor_back_out_count = mysqli_real_escape_string($readConnection, $_POST['2monitor_back_out_count']);

		if ($_POST['2cam_back_out_warranty_date'] != '') {
			$sec_cam_back_out_warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['2cam_back_out_warranty_date'])));
		} else {
			$sec_cam_back_out_warranty_date = '0000-00-00';
		}
		if ($_POST['2cam_back_out_install_date'] != '') {
			$sec_cam_back_out_install_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['2cam_back_out_install_date'])));
		} else {
			$sec_cam_back_out_install_date = '0000-00-00';
		}
		$sec_cam_back_out_type = mysqli_real_escape_string($readConnection, $_POST['2cam_back_out_type']);
		$sec_cam_back_out_dvr_serial_no = mysqli_real_escape_string($readConnection, $_POST['2cam_back_out_dvr_serial_no']);
		$sec_cam_back_out_memory_type = mysqli_real_escape_string($readConnection, $_POST['2cam_back_out_memory_type']);

		$cctv_front = mysqli_real_escape_string($readConnection, $_POST['cctv_front']);
		$cctv_cash_cabin = mysqli_real_escape_string($readConnection, $_POST['cctv_cash_cabin']);
		$cctv_back_out = mysqli_real_escape_string($readConnection, $_POST['cctv_back_out']);
		$front_count = mysqli_real_escape_string($readConnection, $_POST['front_count']);
		$cash_cabin_count = mysqli_real_escape_string($readConnection, $_POST['cash_cabin_count']);
		$back_out_count = mysqli_real_escape_string($readConnection, $_POST['back_out_count']);
		$front_monitor_count = mysqli_real_escape_string($readConnection, $_POST['front_monitor_count']);
		$cash_cabin_monitor_count = mysqli_real_escape_string($readConnection, $_POST['cash_cabin_monitor_count']);
		$back_out_monitor_count = mysqli_real_escape_string($readConnection, $_POST['back_out_monitor_count']);

		if ($_POST['front_install_date'] != '') {
			$front_install_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_install_date'])));
		} else {
			$front_install_date = '0000-00-00';
		}


		if ($_POST['cash_cabin_install_date'] != '') {
			$cash_cabin_install_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['cash_cabin_install_date'])));
		} else {
			$cash_cabin_install_date = '0000-00-00';
		}


		if ($_POST['back_out_install_date'] != '') {
			$back_out_install_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_out_install_date'])));
		} else {
			$back_out_install_date = '0000-00-00';
		}

		if ($_POST['front_warranty_date'] != '') {
			$front_warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_warranty_date'])));
		} else {
			$front_warranty_date = '0000-00-00';
		}

		if ($_POST['cash_cabin_warranty_date'] != '') {
			$cash_cabin_warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['cash_cabin_warranty_date'])));
		} else {
			$cash_cabin_warranty_date = '0000-00-00';
		}

		if ($_POST['back_out_warranty_date'] != '') {
			$back_out_warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_out_warranty_date'])));
		} else {
			$back_out_warranty_date = '0000-00-00';
		}
		$front_cam_type = mysqli_real_escape_string($readConnection, $_POST['front_cam_type']);
		$cash_cabin_cam_type = mysqli_real_escape_string($readConnection, $_POST['cash_cabin_cam_type']);
		$back_out_cam_type = mysqli_real_escape_string($readConnection, $_POST['back_out_cam_type']);
		$front_dvr_serial_no = mysqli_real_escape_string($readConnection, $_POST['front_dvr_serial_no']);
		$cash_cabin_dvr_serial_no = mysqli_real_escape_string($readConnection, $_POST['cash_cabin_dvr_serial_no']);
		$back_out_dvr_serial_no = mysqli_real_escape_string($readConnection, $_POST['back_out_dvr_serial_no']);
		$front_memory_type = mysqli_real_escape_string($readConnection, $_POST['front_memory_type']);
		$cash_cabin_memory_type = mysqli_real_escape_string($readConnection, $_POST['cash_cabin_memory_type']);
		$back_out_memory_type = mysqli_real_escape_string($readConnection, $_POST['back_out_memory_type']);


		//===========TYRE================================
		$front_left_tyre = mysqli_real_escape_string($readConnection, $_POST['front_left_tyre']);
		$front_left_tyre_no = mysqli_real_escape_string($readConnection, $_POST['front_left_tyre_no']);

		if ($_POST['front_left_tyre_fitment'] != '') {
			$front_left_tyre_fitment = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_left_tyre_fitment'])));
		} else {
			$front_left_tyre_fitment = '0000-00-00';
		}
		$front_left_tyre_kms = mysqli_real_escape_string($readConnection, $_POST['front_left_tyre_kms']);

		if ($_POST['front_left_tyre_rotation'] != '') {
			$front_left_tyre_rotation = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_left_tyre_rotation'])));
		} else {
			$front_left_tyre_rotation = '0000-00-00';
		}

		$front_right_tyre = mysqli_real_escape_string($readConnection, $_POST['front_right_tyre']);
		$front_right_tyre_no = mysqli_real_escape_string($readConnection, $_POST['front_right_tyre_no']);

		if ($_POST['front_right_tyre_fitment'] != '') {
			$front_right_tyre_fitment = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_right_tyre_fitment'])));
		} else {
			$front_right_tyre_fitment = '0000-00-00';
		}
		$front_right_tyre_kms = mysqli_real_escape_string($readConnection, $_POST['front_right_tyre_kms']);

		if ($_POST['front_right_tyre_rotation'] != '') {
			$front_right_tyre_rotation = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['front_right_tyre_rotation'])));
		} else {
			$front_right_tyre_rotation = '0000-00-00';
		}

		$back_left_tyre = mysqli_real_escape_string($readConnection, $_POST['back_left_tyre']);
		$back_left_tyre_no = mysqli_real_escape_string($readConnection, $_POST['back_left_tyre_no']);

		if ($_POST['back_left_tyre_fitment'] != '') {
			$back_left_tyre_fitment = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_left_tyre_fitment'])));
		} else {
			$back_left_tyre_fitment = '0000-00-00';
		}
		$back_left_tyre_kms = mysqli_real_escape_string($readConnection, $_POST['back_left_tyre_kms']);

		if ($_POST['back_left_tyre_rotation'] != '') {
			$back_left_tyre_rotation = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_left_tyre_rotation'])));
		} else {
			$back_left_tyre_rotation = '0000-00-00';
		}

		$back_right_tyre = mysqli_real_escape_string($readConnection, $_POST['back_right_tyre']);
		$back_right_tyre_no = mysqli_real_escape_string($readConnection, $_POST['back_right_tyre_no']);

		if ($_POST['back_right_tyre_fitment'] != '') {
			$back_right_tyre_fitment = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_right_tyre_fitment'])));
		} else {
			$back_right_tyre_fitment = '0000-00-00';
		}
		$back_right_tyre_kms = mysqli_real_escape_string($readConnection, $_POST['back_right_tyre_kms']);

		if ($_POST['back_right_tyre_rotation'] != '') {
			$back_right_tyre_rotation = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['back_right_tyre_rotation'])));
		} else {
			$back_right_tyre_rotation = '0000-00-00';
		}

		$spare_tyre = mysqli_real_escape_string($readConnection, $_POST['spare_tyre']);
		$spare_tyre_no = mysqli_real_escape_string($readConnection, $_POST['spare_tyre_no']);

		if ($_POST['spare_tyre_fitment'] != '') {
			$spare_tyre_fitment = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['spare_tyre_fitment'])));
		} else {
			$spare_tyre_fitment = '0000-00-00';
		}
		$spare_tyre_kms = mysqli_real_escape_string($readConnection, $_POST['spare_tyre_kms']);

		if ($_POST['spare_tyre_rotation'] != '') {
			$spare_tyre_rotation = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['spare_tyre_rotation'])));
		} else {
			$spare_tyre_rotation = '0000-00-00';
		}
		//===========TYRE END================================

		$gps_vendor_Name = mysqli_real_escape_string($readConnection, $_POST['gps_vendor_Name']);
		$gps_status = mysqli_real_escape_string($readConnection, $_POST['gps_status']);
		$gps_imei_no = mysqli_real_escape_string($readConnection, $_POST['gps_imei_no']);

		if ($_POST['gps_installed_date'] != '') {
			$gps_installed_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['gps_installed_date'])));
		} else {
			$gps_installed_date = '0000-00-00';
		}

		if ($_POST['gps_warranty_date'] != '') {
			$gps_warranty_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['gps_warranty_date'])));
		} else {
			$gps_warranty_date = '0000-00-00';
		}
		$gps_panic = mysqli_real_escape_string($readConnection, $_POST['gps_panic']);
		$gps_hooter = mysqli_real_escape_string($readConnection, $_POST['gps_hooter']);
		$gps_auto_dialer = mysqli_real_escape_string($readConnection, $_POST['gps_auto_dialer']);
		$gps_sim_no = mysqli_real_escape_string($readConnection, $_POST['gps_sim_no']);
		$gps_mobile_no = mysqli_real_escape_string($readConnection, $_POST['gps_mobile_no']);

		if ($_POST['gps_call_credit_date'] != '') {
			$gps_call_credit_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['gps_call_credit_date'])));
		} else {
			$gps_call_credit_date = '0000-00-00';
		}
		$gps_call_credit_amt = mysqli_real_escape_string($readConnection, $_POST['gps_call_credit_amt']);
		$gps_call_credit_expense = mysqli_real_escape_string($readConnection, $_POST['gps_call_credit_expense']);
		$gps_bal_call_cnt = mysqli_real_escape_string($readConnection, $_POST['gps_bal_call_cnt']);



		if ($_POST['battery_fitment_date'] != '') {
			$battery_fitment_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['battery_fitment_date'])));
		} else {
			$battery_fitment_date = '0000-00-00';
		}
		$battery_serial_no = mysqli_real_escape_string($readConnection, $_POST['battery_serial_no']);
		$battery_warrenty_period = mysqli_real_escape_string($readConnection, $_POST['battery_warrenty_period']);
		$cv_status = mysqli_real_escape_string($readConnection, $_POST['cv_status']);




		$date = date('Y-m-d H:i:s');


		if ($id != '') {

			//$inst="insert into cash_van_history select * from cash_van where id=".$id;
			//echo $inst;
			//$rr=mysqli_query($writeConnection,$inst);

			$query = "UPDATE cash_van 
						  SET ref_no='$ref_no',loc_deploy='$loc_deploy',
						  vec_model='$vec_model', 
						  vec_no = '$vec_no',    	
						  engine_no='$Engine_NO', 
						  chassis_no='$Chassis_No',
						  date_of_purchase='$date_of_purchase',
						  FC_Due_date='$FC_Due_date',
						  insurance_company='$Insurance_Company' , 
						  insurance_no='$Insurance_No' , 
						  ins_expiry_date='$ins_expiry_date',
						  road_tax_expiry_date='$road_tax_expiry_date' , 
						  vec_maint='$vec_maint',
						  misc_exp='$misc_exp',
						  vec_incharge='$vec_incharge',
						  hypo_with='$hypo_with',
						  vehicle_type='$vehicle_type' , 
						  cashvan_type='$van_type',
						  rbi_guidelines='$rbi_guide',
						  Owner_Name='$Owner_Name' , 
						  Owner_mobile='$Owner_mobile' , 
						  remarks='$remarks',
						  tool_kit='$tool_kit',maintenance_budget='$maintenance_budget',bal_amt='$bal_amt',expense='$expense',pollution_serial_no='$pollution_serial_no',pollution_expiry_date='$pollution_expiry_date',road_tax_amt='$road_tax_amt',idv_value='$idv_value',insurance_premium_amt='$insurance_premium_amt',fuel_type='$fuel_type',permit_due_date='$permit_due_date',cctv_type='$cctv_type',cctv_title='$cctv_title',camera_count='$camera_count',monitor_count='$monitor_count',install_date='$install_date',warranty_date='$warranty_date',camera_type='$camera_type',dvr_serial_no='$dvr_serial_no',memory_type='$memory_type',2cam_back_outside='$sec_cam_back_outside',2cam_back_out_count='$sec_cam_back_out_count',2monitor_back_out_count='$sec_monitor_back_out_count',2cam_back_out_warranty_date='$sec_cam_back_out_warranty_date',2cam_back_out_install_date='$sec_cam_back_out_install_date',2cam_back_out_type='$sec_cam_back_out_type',2cam_back_out_dvr_serial_no='$sec_cam_back_out_dvr_serial_no',2cam_back_out_memory_type='$sec_cam_back_out_memory_type',cctv_front='$cctv_front',cctv_cash_cabin='$cctv_cash_cabin',cctv_back_out='$cctv_back_out',front_count='$front_count',cash_cabin_count='$cash_cabin_count',back_out_count='$back_out_count',front_monitor_count='$front_monitor_count',cash_cabin_monitor_count='$cash_cabin_monitor_count',back_out_monitor_count='$back_out_monitor_count',front_install_date='$front_install_date',cash_cabin_install_date='$cash_cabin_install_date',back_out_install_date='$back_out_install_date',front_warranty_date='$front_warranty_date',cash_cabin_warranty_date='$cash_cabin_warranty_date',back_out_warranty_date='$back_out_warranty_date',front_cam_type='$front_cam_type',cash_cabin_cam_type='$cash_cabin_cam_type',back_out_cam_type='$back_out_cam_type',front_dvr_serial_no='$front_dvr_serial_no',cash_cabin_dvr_serial_no='$cash_cabin_dvr_serial_no',back_out_dvr_serial_no='$back_out_dvr_serial_no',front_memory_type='$front_memory_type',cash_cabin_memory_type='$cash_cabin_memory_type',back_out_memory_type='$back_out_memory_type',front_left_tyre='$front_left_tyre',front_left_tyre_no='$front_left_tyre_no',front_left_tyre_fitment='$front_left_tyre_fitment',front_left_tyre_kms='$front_left_tyre_kms',front_right_tyre='$front_right_tyre',front_right_tyre_no='$front_right_tyre_no',front_right_tyre_fitment='$front_right_tyre_fitment',front_right_tyre_kms='$front_right_tyre_kms',back_left_tyre='$back_left_tyre',back_left_tyre_no='$back_left_tyre_no',back_left_tyre_fitment='$back_left_tyre_fitment',back_left_tyre_kms='$back_left_tyre_kms',back_right_tyre='$back_right_tyre',back_right_tyre_no='$back_right_tyre_no',back_right_tyre_fitment='$back_right_tyre_fitment',back_right_tyre_kms='$back_right_tyre_kms',front_left_tyre_rotation='$front_left_tyre_rotation',front_right_tyre_rotation='$front_right_tyre_rotation',back_left_tyre_rotation='$back_left_tyre_rotation',back_right_tyre_rotation='$back_right_tyre_rotation',spare_tyre='$spare_tyre',spare_tyre_no='$spare_tyre_no',spare_tyre_fitment='$spare_tyre_fitment',spare_tyre_kms='$spare_tyre_kms',spare_tyre_rotation='$spare_tyre_rotation',gps_vendor_Name='$gps_vendor_Name',gps_status='$gps_status',gps_imei_no='$gps_imei_no',gps_installed_date='$gps_installed_date',gps_warranty_date='$gps_warranty_date',gps_panic='$gps_panic',gps_hooter='$gps_hooter',gps_auto_dialer='$gps_auto_dialer',gps_sim_no='$gps_sim_no',gps_mobile_no='$gps_mobile_no',gps_call_credit_date='$gps_call_credit_date',gps_call_credit_amt='$gps_call_credit_amt',gps_call_credit_expense='$gps_call_credit_expense',gps_bal_call_cnt='$gps_bal_call_cnt',battery_serial_no='$battery_serial_no',battery_fitment_date='$battery_fitment_date',battery_warrenty_period='$battery_warrenty_period',cv_status='$cv_status',
						  
						  updated_by='$per',
						  updated_date='now()' 
					  WHERE  id=" . $id;

			$exc = mysqli_query($writeConnection, $query);
			$nav = '2_1_2';
		} else {
			$query1 = "select id from cash_van where registration_no='" . $Registration_NO . "' AND status='Y' ";
			$result = mysqli_query($readConnection, $query1);
			if (mysqli_num_rows($result) > 0) {
				header("Location:index.php?pid=Cash_Van_Details&nav=2_2_1");
			} else {
				$query = "INSERT INTO cash_van(ref_no,registration_no,loc_deploy,vec_model,vec_no,engine_no,chassis_no,date_of_purchase,FC_Due_date,insurance_company,insurance_no,ins_expiry_date,road_tax_expiry_date,vec_maint,misc_exp,vec_incharge,hypo_with,vehicle_type,cashvan_type,rbi_guidelines,Owner_Name,Owner_mobile,remarks,tool_kit,maintenance_budget,bal_amt,expense,pollution_serial_no,pollution_expiry_date,road_tax_amt,idv_value,insurance_premium_amt,fuel_type,permit_due_date,cctv_type,cctv_title,camera_count,monitor_count,install_date,warranty_date,camera_type,dvr_serial_no,memory_type,2cam_back_outside,2cam_back_out_count,2monitor_back_out_count,2cam_back_out_warranty_date,2cam_back_out_install_date,2cam_back_out_type,2cam_back_out_dvr_serial_no,2cam_back_out_memory_type,cctv_front,cctv_cash_cabin,cctv_back_out,front_count,cash_cabin_count,back_out_count,front_monitor_count,cash_cabin_monitor_count,back_out_monitor_count,front_install_date,cash_cabin_install_date,back_out_install_date,front_warranty_date,cash_cabin_warranty_date,back_out_warranty_date,front_cam_type,cash_cabin_cam_type,back_out_cam_type,front_dvr_serial_no,cash_cabin_dvr_serial_no,back_out_dvr_serial_no,front_memory_type,cash_cabin_memory_type,back_out_memory_type,front_left_tyre,front_left_tyre_no,front_left_tyre_fitment,front_left_tyre_kms,front_right_tyre,front_right_tyre_no,front_right_tyre_fitment,front_right_tyre_kms,back_left_tyre,back_left_tyre_no,back_left_tyre_fitment,back_left_tyre_kms,back_right_tyre,back_right_tyre_no,back_right_tyre_fitment,back_right_tyre_kms,front_left_tyre_rotation,front_right_tyre_rotation,back_left_tyre_rotation,back_right_tyre_rotation,spare_tyre,spare_tyre_no,spare_tyre_fitment,spare_tyre_kms,spare_tyre_rotation,gps_vendor_Name,gps_status,gps_imei_no,gps_installed_date,gps_warranty_date,gps_panic,gps_hooter,gps_auto_dialer,gps_sim_no,gps_mobile_no,gps_call_credit_date,gps_call_credit_amt,gps_call_credit_expense,gps_bal_call_cnt,battery_serial_no,battery_fitment_date,battery_warrenty_period,status,cv_status)VALUES('$ref_no','$Registration_NO','$loc_deploy','$vec_model','$vec_no','$Engine_NO','$Chassis_No','$date_of_purchase','$FC_Due_date','$Insurance_Company','$Insurance_No','$ins_expiry_date','$road_tax_expiry_date','$vec_maint','$misc_exp','$vec_incharge','$hypo_with','$vehicle_type','$van_type','$rbi_guide','$Owner_Name','$Owner_mobile','$remarks','$tool_kit','$maintenance_budget','$bal_amt','$expense','$pollution_serial_no','$pollution_expiry_date','$road_tax_amt','$idv_value','$insurance_premium_amt','$fuel_type','$permit_due_date','$cctv_type','$cctv_title','$camera_count','$monitor_count','$install_date','$warranty_date','$camera_type','$dvr_serial_no','$memory_type','$sec_cam_back_outside','$sec_cam_back_out_count','$sec_monitor_back_out_count','$sec_cam_back_out_warranty_date','$sec_cam_back_out_install_date','$sec_cam_back_out_type','$sec_cam_back_out_dvr_serial_no','$sec_cam_back_out_memory_type','$cctv_front','$cctv_cash_cabin','$cctv_back_out','$front_count','$cash_cabin_count','$back_out_count','$front_monitor_count','$cash_cabin_monitor_count','$back_out_monitor_count','$front_install_date','$cash_cabin_install_date','$back_out_install_date','$front_warranty_date','$cash_cabin_warranty_date','$back_out_warranty_date','$front_cam_type','$cash_cabin_cam_type','$back_out_cam_type','$front_dvr_serial_no','$cash_cabin_dvr_serial_no','$back_out_dvr_serial_no','$front_memory_type','$cash_cabin_memory_type','$back_out_memory_type','$front_left_tyre','$front_left_tyre_no','$front_left_tyre_fitment','$front_left_tyre_kms','$front_right_tyre','$front_right_tyre_no','$front_right_tyre_fitment','$front_right_tyre_kms','$back_left_tyre','$back_left_tyre_no','$back_left_tyre_fitment','$back_left_tyre_kms','$back_right_tyre','$back_right_tyre_no','$back_right_tyre_fitment','$back_right_tyre_kms','$front_left_tyre_rotation','$front_right_tyre_rotation','$back_left_tyre_rotation','$back_right_tyre_rotation','$spare_tyre','$spare_tyre_no','$spare_tyre_fitment','$spare_tyre_kms','$spare_tyre_rotation','$gps_vendor_Name','$gps_status','$gps_imei_no','$gps_installed_date','$gps_warranty_date','$gps_panic','$gps_hooter','$gps_auto_dialer','$gps_sim_no','$gps_mobile_no','$gps_call_credit_date','$gps_call_credit_amt','$gps_call_credit_expense','$gps_bal_call_cnt','$battery_serial_no','$battery_fitment_date','$battery_warrenty_period','Y','$cv_status')";
				$nav = '2_1_1';
				$exc = mysqli_query($writeConnection, $query);
			}
		}
		if ($exc) {
			// mysqli_close($conn);
			header("Location:../?pid=Cash_Van_Details&nav=" . $nav);
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=Cash_Van_Details&nav=2_3");
		}
	} else if ($pid == 'cv_daily_activities') {
		//print_r($_POST); 
		$id = mysqli_real_escape_string($readConnection, $_POST['id']);
		$ref_no = mysqli_real_escape_string($readConnection, $_POST['ref_no']);
		$opening_km = mysqli_real_escape_string($readConnection, $_POST['opening_km']);
		$closeing_km = mysqli_real_escape_string($readConnection, $_POST['closeing_km']);
		$total_running = mysqli_real_escape_string($readConnection, $_POST['total_running']);
		$fuel_type = mysqli_real_escape_string($readConnection, $_POST['fuel_type']);
		$fuel_litre = mysqli_real_escape_string($readConnection, $_POST['fuel_litre']);
		$average_kpl = mysqli_real_escape_string($readConnection, $_POST['average_kpl']);
		$total_fuel = mysqli_real_escape_string($readConnection, $_POST['total_fuel']);
		$trans_date = mysqli_real_escape_string($readConnection, date('Y-m-d', strtotime($_POST['trans_date'])));


		if ($id != '') {
			$query = "update cash_van_daily_activities set trans_date='$trans_date',opening_km='$opening_km',closeing_km='$closeing_km',total_running='$total_running',fuel_type='$fuel_type',fuel_litre='$fuel_litre',average_kpl='$average_kpl',total_fuel='$total_fuel',updated_by='$user',updated_date=now() where id=$id";
			$nav = '2_1_4';
			$rs = mysqli_query($writeConnection, $query);
		} else {
			$sql = "insert into cash_van_daily_activities(ref_no,trans_date,opening_km,closeing_km,total_running,fuel_type,fuel_litre,average_kpl,total_fuel,status,created_by,created_date) values('$ref_no','$trans_date','$opening_km','$closeing_km','$total_running','$fuel_type','$fuel_litre','$average_kpl','$total_fuel','Y','$user',now())";
			$nav = '2_1';
			$rs = mysqli_query($writeConnection, $sql);
		}
		if ($rs) {
			// mysqli_close($conn);
			header("Location:../?pid=cv_daily_activities&nav=" . $nav);
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=cv_daily_activities&nav=2_3");
		}
	}

	//Service master
	else if ($pid == 'Service_Master') {
		$service_id = $_POST['service_id'];
		if ($_POST['group_name'] == 'others') {
			$group = $_POST['other_group'];
		} else {
			$group = $_POST['group_name'];
		}
		if ($service_id == '') {
			$sql = "insert into cashvan_services(region_id,client_id,service_code,service_name,group_name,service_type,description,start_date,end_date,update_by,update_time,status) values('" . $_POST['region_id'] . "','" . $_POST['client_id'] . "','" . $_POST['service_code'] . "','" . $_POST['service_name'] . "','" . $group . "','" . $_POST['service_type'] . "','" . $_POST['description'] . "','" . date('Y-m-d', strtotime($_POST['start_date'])) . "','" . $_POST['end_date'] . "','" . $user . "','now()','Y')";
			$nav = '2_1_1';
		} else {
			$sql = "update cashvan_services set region_id='" . $_POST['region_id'] . "',client_id='" . $_POST['client_id'] . "',service_name='" . $_POST['service_name'] . "',group_name='" . $group . "',service_type='" . $_POST['service_type'] . "',description='" . $_POST['description'] . "',start_date='" . date('Y-m-d', strtotime($_POST['start_date'])) . "',end_date='" . $_POST['end_date'] . "',update_by='" . $user . "',update_time=now() where service_id='" . $_POST['service_id'] . "' and status='Y'";
			$nav = '2_1_2';
		}
		$excs = mysqli_query($writeConnection, $sql);
		if ($excs) {
			// mysqli_close($conn);
			header("Location:../?pid=Service_Master&nav=" . $nav);
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=Service_Master&nav=2_3");
		}
	}



	//Mapp Cahvan to Service
	else if ($pid == 'Service_Map') {

		$service_date = $_POST['service_date'];
		$service_no = $_POST['service_no'];
		$vehicle1 = '';
		$gun_cnt = 0;
		$drv_cnt = 0;
		$ce_cnt = 0;
		$mbc_cnt = 0;
		$ldr_cnt = 0;
		$cv = $_POST['cv'];
		$cashvan = $_POST['cashvan'];
		if (isset($_POST['gunman'])) {
			$gun_str = 'G-';
			foreach ($_POST['gunman'] as $num1 => $gunid) {
				if ($gunid != '') $gun_cnt++;
			}
			if ($gun_cnt > 0) $gunny = implode(",", $_POST['gunman']);
			if (isset($gunny)) {
				//Check if already exists
				$sel_gun = mysqli_query($writeConnection, "Select name,crew_id from dcv_crew where service_id = '" . $_POST['service_name'] . "' and vehicle_id='" . $cv . "' and desig='gunman' and status='Y' ");
				if (mysqli_num_rows($sel_gun) > 0) {
					$gun_r = mysqli_fetch_object($sel_gun);
					$gun = "Update dcv_crew set vehicle_id='" . $cashvan . "',name ='" . $gunny . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $gun_r->crew_id . "' ";
				} else {
					$gun = "insert into dcv_crew(service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $_POST['service_name'] . "','" . $cashvan . "','gunman','" . $gunny . "','Y','" . $user_name . "',now())";
				}

				$gun_exc = mysqli_query($writeConnection, $gun);
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
				$sel_dr = mysqli_query($writeConnection, "Select name,crew_id from dcv_crew where service_id = '" . $_POST['service_name'] . "' and vehicle_id='" . $cv . "' and desig='driver' and status='Y' ");
				if (mysqli_num_rows($sel_dr) > 0) {
					$driv_r = mysqli_fetch_object($sel_dr);
					$drive = "Update dcv_crew set vehicle_id='" . $cashvan . "',name ='" . $drivyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $driv_r->crew_id . "'";
				} else {
					$drive = "insert into dcv_crew(service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $_POST['service_name'] . "','" . $cashvan . "','driver','" . $drivyy . "','Y','" . $user_name . "',now())";
				}

				$drive_exc = mysqli_query($writeConnection, $drive);
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
				$sel_ld = mysqli_query($writeConnnection, "Select name,crew_id from dcv_crew where service_id = '" . $_POST['service_name'] . "' and vehicle_id='" . $cv . "' and desig='loader' and status='Y'");
				if (mysqli_num_rows($sel_ld) > 0) {
					$ld_r = mysqli_fetch_object($sel_ld);
					$load = "Update dcv_crew set vehicle_id='" . $cashvan . "',name ='" . $loadyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $ld_r->crew_id . "'";
				} else {
					$load = "insert into dcv_crew(service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $_POST['service_name'] . "','" . $cashvan . "','loader','" . $loadyy . "','Y','" . $user_name . "',now())";
				}

				$load_exc = mysqli_query($writeConnection, $load);
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
				$sel_ce = mysqli_query($writeConnection, "Select name,crew_id from dcv_crew where service_id = '" . $_POST['service_name'] . "' and vehicle_id='" . $cv . "' and desig='ce' and status='Y'");
				if (mysqli_num_rows($sel_ce) > 0) {
					$ce_r = mysqli_fetch_object($sel_ce);
					$ce = "Update dcv_crew set vehicle_id='" . $cashvan . "',name ='" . $ceyy . "',updated_by='" . $user_name . "',updated_date=now() where crew_id='" . $ce_r->crew_id . "'";
				} else {
					$ce = "insert into dcv_crew(service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $_POST['service_name'] . "','" . $cashvan . "','ce','" . $ceyy . "','Y','" . $user_name . "',now())";
				}

				$ce_exc = mysqli_query($writeConnection, $ce);
			}
			$ceyy = '';
		}


		//Crew
		$crew = '';
		if (isset($gun_str))		$crew .= $gun_str . $gun_cnt . ",";
		if (isset($drv_str))		$crew .= $drv_str . $drv_cnt . ",";
		if (isset($ldr_str))		$crew .= $ldr_str . $ldr_cnt . ",";
		if (isset($mbc_str))		$crew .= $mbc_str . $mbc_cnt . ",";
		if (isset($ce_str))		$crew .= $ce_str . $ce_cnt;

		//Update Services		
		$sel_ser = mysqli_query($writeConnection, "Select vehicle_id,crew_members from cashvan_services where service_id='" . $_POST['service_name'] . "' and status='Y' ");
		$ser_row = mysqli_fetch_object($sel_ser);
		if ($ser_row->vehicle_id != '' && $ser_row->vehicle_id != '0') {
			$veh = explode(",", $ser_row->vehicle_id);
			if (isset($cv) && $cv != '') {
				$crew_no = array_search($cv, $veh);
				$veh[$crew_no] = $cashvan;
				$vehicle1 = implode(",", $veh);
			} else {
				$vehicle1 = $ser_row->vehicle_id . "," . $cashvan;
			}
		} else $vehicle1 = $cashvan;

		if ($ser_row->crew_members != '') {
			$crew_arr = explode("/", $ser_row->crew_members);
			if (isset($cv) && $cv != '' && isset($crew_no) && $crew_no >= 0) {
				$crew_arr[$crew_no] = $crew;
				$crew1 = implode("/", $crew_arr);
			} else $crew1 = $ser_row->crew_members . "/" . $crew;
		} else $crew1 = $crew;
		$up_ser = "Update cashvan_services set vehicle_id ='" . $vehicle1 . "',crew_members='" . $crew1 . "',update_by='" . $user_name . "',update_time=now() where service_id='" . $_POST['service_name'] . "' and status='Y' ";
		$excs = mysqli_query($writeConnection, $up_ser);

		if ($excs) {
			// mysqli_close($conn);
			header("Location:../?pid=Service_Map&nav=2_1_1");
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=Service_Map&nav=2_5");
		}
	}




	//Assign Vans
	else if ($pid == 'View_Transaction_Logs') {
		$sid = $_POST['sid'];
		$vehicles = '';
		$sel_service = mysqli_query($readConnection, "Select cvs.service_id,vd.from_date from vantrans_details vd join cashvan_services cvs on cvs.service_id=vd.service_id where vd.sid='" . $sid . "' ");
		$rr = mysqli_fetch_assoc($sel_service);
		$gun_cnt = 0;
		$drv_cnt = 0;
		$ce_cnt = 0;
		$ldr_cnt = 0;
		$cashvan = $_POST['vehicle_no'];
		if (isset($_POST['gunman'])) {
			$gun_str = 'G-';
			foreach ($_POST['gunman'] as $num1 => $gunid) {
				if ($gunid != '') $gun_cnt++;
			}
			if ($gun_cnt > 0) $gunids = implode(",", $_POST['gunman']);
			if (isset($gunids)) {
				$gun = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $sid . "','" . $rr['from_date'] . "','" . $rr['service_id'] . "','" . $cashvan . "','gunman','" . $gunids . "','Y','" . $user_name . "',now())";
				$gun_exc = mysqli_query($writeConnection, $gun);
			}
			$gunids = '';
		}
		if (isset($_POST['driver'])) {
			$drv_str = 'D-';
			foreach ($_POST['driver'] as $num1 => $drvid) {
				if ($drvid != '') $drv_cnt++;
			}
			if ($drv_cnt > 0) $drvids = implode(",", $_POST['driver']);
			if (isset($drvids)) {
				$drive = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $sid . "','" . $rr['from_date'] . "','" . $rr['service_id'] . "','" . $cashvan . "','driver','" . $drvids . "','Y','" . $user_name . "',now())";
				$drive_exc = mysqli_query($writeConnection, $drive);
			}
			$drvids = '';
		}
		if (isset($_POST['loader'])) {
			$ldr_str = 'L-';
			foreach ($_POST['loader'] as $num1 => $loadid) {
				if ($loadid != '') $drv_cnt++;
			}
			if ($drv_cnt > 0) $loadids = implode(",", $_POST['loader']);
			if (isset($loadids)) {
				$load = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $sid . "','" . $rr['from_date'] . "','" . $rr['service_id'] . "','" . $cashvan . "','loader','" . $loadids . "','Y','" . $user_name . "',now())";
				$load_exc = mysqli_query($writeConnection, $load);
			}
			$loadids = '';
		}
		if (isset($_POST['ce'])) {
			$ce_str = 'CE-';
			foreach ($_POST['ce'] as $num1 => $ceid) {
				if ($ceid != '') $ce_cnt++;
			}
			if ($ce_cnt > 0) $ceids = implode(",", $_POST['ce']);
			if (isset($ceids)) {
				$ce = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $sid . "','" . $rr['from_date'] . "','" . $rr['service_id'] . "','" . $cashvan . "','ce','" . $ceids . "','Y','" . $user_name . "',now())";
				$ce_exc = mysqli_query($writeConnection, $ce);
			}
			$ceids = '';
		}

		//Crew
		$crew = '';
		if (isset($gun_str))		$crew .= $gun_str . $gun_cnt . ",";
		if (isset($drv_str))		$crew .= $drv_str . $drv_cnt . ",";
		if (isset($ldr_str))		$crew .= $ldr_str . $ldr_cnt . ",";
		if (isset($ce_str))		$crew .= $ce_str . $ce_cnt;
		$upda_trans = mysqli_query($writeConnection, "Update vantrans_details set vehicle_no='" . $cashvan . "',crew_members='" . $crew . "',updated_by='" . $user_name . "',updated_date=now() where sid='" . $sid . "'");
		if ($upda_trans) {
			// mysqli_close($conn);
			header("Location:../?pid=View_Transaction_Logs&nav=2_3_6");
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=View_Transaction_Logs&nav=2_1_1");
		}
	}



	// Cash Van Trans Information
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

	// Edit Transaction Log
	else if ($pid == "edit_dailyupload") {
		$id = $_REQUEST['id'];
		$Amount = $_POST['Amount'];
		$Type = $_POST['Type'];

		//Update Service Mapping
		$sel_query = "update cashvan_mapping set Total_Amount='" . $Amount . "',Cash_Type='" . $Type . "' where status!='N' and id='" . $id . "' ";
		$sel_exc = mysqli_query($writeConnection, $sel_query);
		if ($sel_exc) {
			// mysqli_close($conn);
			header('Location:../?pid=Edit_Transaction&nav=2_1_1');
			exit;
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=Edit_Transaction&nav=2_2_6');
			exit;
		}
	} else if ($pid == 'vault_mandate_master') {
		$vault_type = $_POST['vault_type'];
		if ($_POST['id'] == '') {
			$ins_entry = "insert into vault_mandate_master(entry_date,region,vault_name,vault_type,cashier,status,created_by,created_date) values ('" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $_POST['region'] . "','" . $_POST['vault_name'] . "','" . $vault_type . "','" . $_POST['cashier'] . "','Y','" . $user_name . "',now())";
			$nav = '2_1';
		} else {
			$ins_entry = "update vault_mandate_master set entry_date='" . date('Y-m-d', strtotime($_POST['entry_date'])) . "',region='" . $_POST['region'] . "',vault_name='" . $_POST['vault_name'] . "',vault_type='" . $_POST['vault_type'] . "',cashier='" . $_POST['cashier'] . "',updated_by='" . $user_name . "',updated_time=now() where v_id='" . $id . "' and status='Y' ";
			$nav = '2_3';
		}
		$exe = mysqli_query($ins_entry);
		if ($exe) {
			// mysqli_close($conn);
			header('Location:../?pid=vault_mandate_master&nav=' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=vault_mandate_master&nav=2_5');
		}
	} else if ($pid == 'vault_ce_input') {

		$ce_id = $_POST['ce_name'];
		$date = date('Y-m-d', strtotime($_POST['dates']));
		$vault_id = $_POST['vault_id'];

		$thous_cnt = 0;
		$fvhs_cnt = 0;
		$huds_cnt = 0;
		$ffty_cnt = 0;
		$twty_cnt = 0;
		$ten_cnt = 0;
		$fv_cnt = 0;
		$coins = 0;
		$amount_total = 0;
		$trans_cnt = count($_POST['type']);
		$bag_total = 0;
		$cash_total = 0;
		foreach ($_POST['type'] as $k => $v) {
			if ($_POST['type'][$k] == 'vault' && $_POST['vault_type'][$k] == 'cash') {
				$thous_cnt += $_POST['2000s'][$k];
				$thous_cnt += $_POST['1000s'][$k];
				$fvhs_cnt += $_POST['500s'][$k];
				$huds_cnt += $_POST['100s'][$k];
				$ffty_cnt += $_POST['500s'][$k];
				$twty_cnt += $_POST['20s'][$k];
				$ten_cnt += $_POST['10s'][$k];
				$fv_cnt += $_POST['5s'][$k];
				$coins += $_POST['1s'][$k];
				$amount_total += $_POST['amount'][$k];
				$cash_total += $_POST['amount'][$k];
			} else if ($_POST['type'][$k] == 'vault' && $_POST['vault_type'][$k] == 'bag') {
				$bag_total += $_POST['amount'][$k];
				$amount_total += $_POST['amount'][$k];
			}
		}
		if ($vault_id != '') {
			$ins_ce = "update vault_ce_entry set entry_date='" . $date . "',1000s='" . $thous_cnt . "',500s='" . $fvhs_cnt . "',100s='" . $huds_cnt . "',50s='" . $ffty_cnt . "',20s='" . $twty_cnt . "',10s='" . $ten_cnt . "',5s='" . $fv_cnt . "',1s='" . $coins . "',total_amount='" . $amount_total . "',cash_total='" . $cash_total . "',bag_total='" . $bag_total . "',updated_by='" . $user_name . "',updated_date=now() where id='" . $vault_id . "' and status='Y' ";
			$nav = '2_3';
		} else {
			$ins_ce = "insert into vault_ce_entry(entry_date,ce_id,1000s,500s,100s,50s,20s,10s,5s,1s,total_amount,cash_total,bag_total,status,created_by,created_date) values('" . $date . "','" . $ce_id . "','" . $thous_cnt . "','" . $fvhs_cnt . "','" . $huds_cnt . "','" . $ffty_cnt . "','" . $twty_cnt . "','" . $ten_cnt . "','" . $fv_cnt . "','" . $coins . "','" . $amount_total . "','" . $cash_total . "','" . $bag_total . "','Y','" . $user_name . "',now())";
			$nav = '2_1';
		}
		$ce_ins = mysqli_query($ins_ce);
		if ($ce_ins) $ins_id = mysqli_insert_id();

		//Multiple Entry 

		foreach ($_POST['type'] as $key => $val) {
			//Insertion
			if ($_POST['deno_id'][$key] == '') {
				$ins1 = 'insert into vault_ce_entry_mul(ce_entry_id,vault_name,entry_date,type,vault_type,ce_id,trans_id,clients,customers,shops,receipt_no,amount,cashier_id,1000s,500s,100s,50s,20s,10s,5s,1s,difference,status,created_by,created_date) values';
				$ins2 .= '("' . $ins_id . '","' . mysqli_real_escape_string($_POST['vault_name'][$key]) . '","' . $date . '","' . mysqli_real_escape_string($_POST['type'][$key]) . '","' . mysqli_real_escape_string($_POST['vault_type'][$key]) . '","' . $ce_id . '","' . mysqli_real_escape_string($_POST['trans_id'][$key]) . '","' . mysqli_real_escape_string($_POST['clients'][$key]) . '","' . mysqli_real_escape_string($_POST['customers'][$key]) . '","' . mysqli_real_escape_string($_POST['shops'][$key]) . '","' . mysqli_real_escape_string($_POST['receipt_no'][$key]) . '","' . mysqli_real_escape_string($_POST['amount'][$key]) . '","' . mysqli_real_escape_string($_POST['cashier_id'][$key]) . '","' . mysqli_real_escape_string($_POST['1000s'][$key]) . '","' . mysqli_real_escape_string($_POST['500s'][$key]) . '","' . mysqli_real_escape_string($_POST['100s'][$key]) . '","' . mysqli_real_escape_string($_POST['50s'][$key]) . '","' . mysqli_real_escape_string($_POST['20s'][$key]) . '","' . mysqli_real_escape_string($_POST['10s'][$key]) . '","' . mysqli_real_escape_string($_POST['5s'][$key]) . '","' . mysqli_real_escape_string($_POST['1s'][$key]) . '","' . mysqli_real_escape_string($_POST['difference'][$key]) . '","Y","' . $user_name . '",now()),';
			} else {
				//Updation
				$ups = 'update vault_ce_entry_mul set vault_name="' . mysqli_real_escape_string($_POST['vault_name'][$key]) . '",type="' . mysqli_real_escape_string($_POST['type'][$key]) . '",vault_type="' . mysqli_real_escape_string($_POST['vault_type'][$key]) . '",receipt_no="' . mysqli_real_escape_string($_POST['receipt_no'][$key]) . '",amount="' . mysqli_real_escape_string($_POST['amount'][$key]) . '",cashier_id="' . mysqli_real_escape_string($_POST['cashier_id'][$key]) . '",1000s="' . mysqli_real_escape_string($_POST['1000s'][$key]) . '",500s="' . mysqli_real_escape_string($_POST['500s'][$key]) . '",100s="' . mysqli_real_escape_string($_POST['100s'][$key]) . '",50s="' . mysqli_real_escape_string($_POST['50s'][$key]) . '",20s="' . mysqli_real_escape_string($_POST['20s'][$key]) . '",10s="' . mysqli_real_escape_string($_POST['10s'][$key]) . '",5s="' . mysqli_real_escape_string($_POST['5s'][$key]) . '",1s="' . mysqli_real_escape_string($_POST['1s'][$key]) . '",difference="' . mysqli_real_escape_string($_POST['difference'][$key]) . '",updated_by="' . $user_name . '",updated_date=now() where status="Y" and deno_id="' . $_POST['deno_id'][$key] . '" and trans_id="' . mysqli_real_escape_string($_POST['trans_id'][$key]) . '" ';
				$ups_exc = mysqli_query($ups);
			}
		}
		if (isset($ins2) && $ins2 != '') {
			$ins_qu = substr($ins2, 0, -1);
			$ins_ex = mysqli_query($ins1 . $ins_qu);
		}

		if ($ce_ins) {
			// mysqli_close($conn);
			header('Location:../?pid=vault_ce_input&nav=' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=vault_ce_input&nav=2_2');
		}
	}



	//MBC MASTER
	else if ($pid == 'mbc_master') {

		if ($_POST['group_name'] == 'others') {
			$group = $_POST['other_group'];
		} else {
			$group = $_POST['group_name'];
		}

		if ($_POST['id'] == '') {
			$mbc_entry = "insert into mbc_master(client_id,mbc_name,group_name,region_id,location_id,phone_no,email,address,remark,mbc_status,status,created_by,created_date) values ('" . $_POST['client_id'] . "','" . $_POST['mbc_name'] . "','" . $group . "','" . $_POST['region_id'] . "','" . $_POST['location'] . "','" . $_POST['phone_no'] . "','" . $_POST['email'] . "','" . $_POST['address'] . "','" . $_POST['remark'] . "','" . $_POST["mbc_status"] . "','Y','" . $user_name . "',now())";

			$nav = '2_1_1';
		} else {
			$mbc_entry = "update mbc_master set client_id='" . $_POST['client_id'] . "',mbc_name='" . $_POST['mbc_name'] . "',group_name='" . $group . "',region_id='" . $_POST['region_id'] . "',location_id='" . $_POST['location'] . "',phone_no='" . $_POST['phone_no'] . "',email='" . $_POST['email'] . "',address='" . $_POST['address'] . "',remark='" . $_POST['remark'] . "',mbc_status='" . $_POST["mbc_status"] . "',updated_by='" . $user_name . "',updated_date=now() where 
	
	id='" . $id . "' and status='Y' ";

			$nav = '2_1_6';
		}

		$exe = mysqli_query($writeConnection, $mbc_entry);
		if ($exe) {
			// mysqli_close($conn);
			header('Location:../?pid=mbc_master&nav=' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=mbc_master&nav=2_5');
		}
	}

	//MBC MAPPING

	else if ($pid == 'mbc_mapping') {

		if ($_POST['id'] == '') {
			echo $mbc_map = "insert into mbc_mapping(mbc_name,location,ce_id,status,created_by,created_date) values ('" . $_POST['mbc_name'] . "','" . $_POST['location'] . "','" . $_POST['ce_id'] . "','Y','" . $user_name . "',now())";

			$nav = '2_1_1';
		} else {
			$mbc_map = "update mbc_mapping set mbc_name='" . $_POST['mbc_name'] . "',location='" . $_POST['location'] . "',ce_id='" . $_POST['ce_id'] . "',updated_by='" . $user_name . "',updated_date=now() where map_id='" . $id . "' and  status='Y' ";
			$nav = '2_1_6';
		}

		$exe = mysqli_query($writeConnection, $mbc_map);
		if ($exe) {
			// mysqli_close($conn);
			header('Location:../?pid=mbc_mapping&nav=' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=mbc_mapping&nav=2_5');
		}
	} else if ($pid == 'mbc_collection') {
		$emp_id = $_REQUEST['aemp_id'];
		$trans_no = $_POST['trans_no'];
		$id = $_POST['id'];

		$tot_amt1 = $_POST['tot_amt1'];
		$amt_1000 = $_POST['amt_1000'];
		$amt_500 = $_POST['amt_500'];
		$amt_100 = $_POST['amt_100'];
		$amt_50 = $_POST['amt_50'];
		$amt_20 = $_POST['amt_20'];
		$amt_10 = $_POST['amt_10'];
		$amt_5 = $_POST['amt_5'];
		$coins = $_POST['coins'];
		$deno_tot1 = $_POST['deno_tot1'];
		$deno_diff = $_POST['deno_diff'];
		$dep_slip = $_POST['dep_slip'];
		$sel_no = $_POST['sel_no'];
		$hic_no = $_POST['hic_no'];
		$cust_no = $_POST['cust_no'];
		$pick_amt = $_POST['pick_amt'];
		$dep_type1 = $_POST['dep_type1'];
		$dep_acc1 = $_POST['dep_acc1'];
		$tot_time1 = $_POST['tot_time'];
		$over_time1 = $_POST['over_time'];
		//$date2 = date('Y-m-d',strtotime($_POST['date2']));
		$cheq_no = $_POST['cheq_no'];
		$rec_no = $_POST['rec_no'];
		$hcl_no = $_POST['hcl_no'];
		$cheq_amt = $_POST['cheq_amt'];
		$bank_name = $_POST['bank_name'];
		$day = $_POST['day3'];
		$acc_no = $_POST['acc_no'];
		$date3 = date('Y-m-d', strtotime($_POST['date3']));
		$remarks = $_POST['remarks'];
		$in_time3 = $_POST['in_time3'];
		$out_time3 = $_POST['out_time3'];
		$status = $_POST['status'];
		//Insert
		echo  $query = "Update mbc_daily_collection set entry_date='" . $date3 . "',day='" . $day . "',s_1000='" . $amt_1000 . "',s_500='" . $amt_500 . "',s_100='" . $amt_100 . "',s_50='" . $amt_50 . "',s_20='" . $amt_20 . "',s_10='" . $amt_10 . "',	s_5='" .

			$amt_5 . "',coins='" . $coins . "',tot_amt='" . $deno_tot1 . "',diff_amt='" . $deno_diff . "',dep_slip_no='" . $dep_slip . "',seal_tag_no='" . $sel_no . "',	hcl_tag_no='" . $hic_no . "',cust_gen_no='" . $cust_no . "',pick_amt='" . $pick_amt . "',dep_type='" .

			$dep_type1 . "',dep_acc_no='" . $dep_acc1 . "',cheque_no='" . $cheq_no . "',recepit_no='" . $rec_no . "',cheque_amt='" . $cheq_amt . "',hcl_no='" . $hcl_no . "',bank_name='" . $bank_name . "',acc_no='" . $acc_no . "',in_time='" . $in_time3 . "',out_time='" .

			$out_time3 . "',tot_time='" . $tot_time1 . "',over_time='" . $over_time1 . "',remarks='" . $remarks . "',status='Y',updated_by='" . $emp_id . "',updated_date=now() where trans_id='" . $id . "'";



		$exc = mysqli_query($query);

		if ($exc) {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_1_1');
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_2_1');
		}
	}


	// Cashier Entry
	else if ($pid == 'vault_cashier_entry') {

		$entry_date = date('Y-m-d', strtotime($_POST['entry_date']));
		$cashier_id = $_POST['cashier_id'];
		$id = $_POST['id'];
		$vault_id = $_POST['vault_id'];
		$diff_amount = $_POST['diff_amount'];
		$collect_amount = $_POST['collect_amount'];

		//Vault Cashier Entry
		if ($id == '') {
			$ins_entry = "insert into vault_cashier_entry(entry_date,cashier_id,amount,diff_amount,vault_id,status,created_by,created_date) values('" . $entry_date . "','" . $cashier_id . "','" . $collect_amount . "','" . $diff_amount . "','" . $vault_id . "','Y','" . $user_name . "',now())";
			$entry_ex = mysqli_query($ins_entry);
			if ($entry_ex)	$ins_id = mysqli_insert_id();
		} else {
			$up_query = "Update vault_cashier_entry set diff_amount='" . $diff_amount . "',amount = '" . $collect_amount . "',updated_by='" . $user_name . "',updated_date=now() where e_id = '" . $id . "' and status='Y' ";
			$up_exc = mysqli_query($up_query);
		}
		//Non - Issuable
		if ($id == '') {
			//Day
			$non_day = "insert into vault_nonissuable_details(nis_date,cashier_entry_id,cashier_id,coll_type,nis_1000s,nis_500s,nis_100s,nis_50s,nis_20s,nis_10s,nis_5s,nis_1s,nis_total_amount,nis_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Day','" . $_POST['1000s_n_d'] . "','" . $_POST['500s_n_d'] . "','" . $_POST['100s_n_d'] . "','" . $_POST['50s_n_d'] . "','" . $_POST['20s_n_d'] . "','" . $_POST['10s_n_d'] . "','" . $_POST['5s_n_d'] . "','" . $_POST['1s_n_d'] . "','" . $_POST['grand_total_noissue_d'] . "','" . $_POST['total_noissue_d'] . "','Y','" . $user_name . "',now())";
			$non_day_exc = mysqli_query($non_day);
			//Evening
			$non_eve = "insert into vault_nonissuable_details(nis_date,cashier_entry_id,cashier_id,coll_type,nis_1000s,nis_500s,nis_100s,nis_50s,nis_20s,nis_10s,nis_5s,nis_1s,nis_total_amount,nis_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Evening','" . $_POST['1000s_n_e'] . "','" . $_POST['500s_n_e'] . "','" . $_POST['100s_n_e'] . "','" . $_POST['50s_n_e'] . "','" . $_POST['20s_n_e'] . "','" . $_POST['10s_n_e'] . "','" . $_POST['5s_n_e'] . "','" . $_POST['1s_n_e'] . "','" . $_POST['grand_total_noissue_e'] . "','" . $_POST['total_noissue_e'] . "','Y','" . $user_name . "',now())";
			$non_eve_exc = mysqli_query($non_eve);
			//Total 
			$non_total = "insert into vault_nonissuable_details(nis_date,cashier_entry_id,cashier_id,coll_type,nis_1000s,nis_500s,nis_100s,nis_50s,nis_20s,nis_10s,nis_5s,nis_1s,nis_total_amount,nis_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Total','" . $_POST['1000s_n_t'] . "','" . $_POST['500s_n_t'] . "','" . $_POST['100s_n_t'] . "','" . $_POST['50s_n_t'] . "','" . $_POST['20s_n_t'] . "','" . $_POST['10s_n_t'] . "','" . $_POST['5s_n_t'] . "','" . $_POST['1s_n_t'] . "','" . $_POST['grand_total_n_t'] . "','" . $_POST['total_n_t'] . "','Y','" . $user_name . "',now())";
			$non_tot_exc = mysqli_query($non_total);
		} else {
			//Day
			$unon_day = "update vault_nonissuable_details set nis_1000s='" . $_POST['1000s_n_d'] . "',nis_500s='" . $_POST['500s_n_d'] . "',nis_100s='" . $_POST['100s_n_d'] . "',nis_50s='" . $_POST['50s_n_d'] . "',nis_20s='" . $_POST['20s_n_d'] . "',nis_10s='" . $_POST['10s_n_d'] . "',nis_5s='" . $_POST['5s_n_d'] . "',nis_1s='" . $_POST['1s_n_d'] . "',nis_total_amount='" . $_POST['grand_total_noissue_d'] . "',nis_total_notes='" . $_POST['total_noissue_d'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Day' and status='Y' ";
			$unon_day_exc = mysqli_query($unon_day);
			//Evening
			$unon_eve = "update vault_nonissuable_details set nis_1000s='" . $_POST['1000s_n_e'] . "',nis_500s='" . $_POST['500s_n_e'] . "',nis_100s='" . $_POST['100s_n_e'] . "',nis_50s='" . $_POST['50s_n_e'] . "',nis_20s='" . $_POST['20s_n_e'] . "',nis_10s='" . $_POST['10s_n_e'] . "',nis_5s='" . $_POST['5s_n_e'] . "',nis_1s='" . $_POST['1s_n_e'] . "',nis_total_amount='" . $_POST['grand_total_noissue_e'] . "',nis_total_notes='" . $_POST['total_noissue_e'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Evening' and status='Y' ";
			$unon_eve_exc = mysqli_query($unon_eve);
			//Total
			$unon_tot = "update vault_nonissuable_details set nis_1000s='" . $_POST['1000s_n_t'] . "',nis_500s='" . $_POST['500s_n_t'] . "',nis_100s='" . $_POST['100s_n_t'] . "',nis_50s='" . $_POST['50s_n_t'] . "',nis_20s='" . $_POST['20s_n_t'] . "',nis_10s='" . $_POST['10s_n_t'] . "',nis_5s='" . $_POST['5s_n_t'] . "',nis_1s='" . $_POST['1s_n_t'] . "',nis_total_amount='" . $_POST['grand_total_n_t'] . "',nis_total_notes='" . $_POST['total_n_t'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Total' and status='Y' ";
			$unon_tot_exc = mysqli_query($unon_tot);
		}
		//Issuable 
		if ($id == '') {
			//Day
			$iss_day = "insert into vault_issuable_details(is_date,cashier_entry_id,cashier_id,coll_type,is_1000s,is_500s,is_100s,is_50s,is_20s,is_10s,is_5s,is_1s,is_total_amount,is_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Day','" . $_POST['1000s_i_d'] . "','" . $_POST['500s_i_d'] . "','" . $_POST['100s_i_d'] . "','" . $_POST['50s_i_d'] . "','" . $_POST['20s_i_d'] . "','" . $_POST['10s_i_d'] . "','" . $_POST['5s_i_d'] . "','" . $_POST['1s_i_d'] . "','" . $_POST['grand_total_issue_d'] . "','" . $_POST['total_issue_d'] . "','Y','" . $user_name . "',now())";
			$iss_day_exc = mysqli_query($iss_day);
			//Evening
			$iss_eve = "insert into vault_issuable_details(is_date,cashier_entry_id,cashier_id,coll_type,is_1000s,is_500s,is_100s,is_50s,is_20s,is_10s,is_5s,is_1s,is_total_amount,is_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Evening','" . $_POST['1000s_i_e'] . "','" . $_POST['500s_i_e'] . "','" . $_POST['100s_i_e'] . "','" . $_POST['50s_i_e'] . "','" . $_POST['20s_i_e'] . "','" . $_POST['10s_i_e'] . "','" . $_POST['5s_i_e'] . "','" . $_POST['1s_i_e'] . "','" . $_POST['grand_total_issue_e'] . "','" . $_POST['total_issue_e'] . "','Y','" . $user_name . "',now())";
			$iss_eve_exc = mysqli_query($iss_eve);
			//Total 
			$iss_total = "insert into vault_issuable_details(is_date,cashier_entry_id,cashier_id,coll_type,is_1000s,is_500s,is_100s,is_50s,is_20s,is_10s,is_5s,is_1s,is_total_amount,is_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Total','" . $_POST['1000s_i_t'] . "','" . $_POST['500s_i_t'] . "','" . $_POST['100s_i_t'] . "','" . $_POST['50s_i_t'] . "','" . $_POST['20s_i_t'] . "','" . $_POST['10s_i_t'] . "','" . $_POST['5s_i_t'] . "','" . $_POST['1s_i_t'] . "','" . $_POST['grand_total_issue_t'] . "','" . $_POST['total_issue_t'] . "','Y','" . $user_name . "',now())";
			$iss_tot_exc = mysqli_query($iss_total);
		} else {
			//Day
			$uiss_day = "update vault_issuable_details set is_1000s='" . $_POST['1000s_i_d'] . "',is_500s='" . $_POST['500s_i_d'] . "',is_100s='" . $_POST['100s_i_d'] . "',is_50s='" . $_POST['50s_i_d'] . "',is_20s='" . $_POST['20s_i_d'] . "',is_10s='" . $_POST['10s_i_d'] . "',is_5s='" . $_POST['5s_i_d'] . "',is_1s='" . $_POST['1s_i_d'] . "',is_total_amount='" . $_POST['grand_total_issue_d'] . "',is_total_notes='" . $_POST['total_issue_d'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Day' and status='Y' ";
			$uiss_day_exc = mysqli_query($uiss_day);
			//Evening
			$uiss_eve = "update vault_issuable_details set is_1000s='" . $_POST['1000s_i_e'] . "',is_500s='" . $_POST['500s_i_e'] . "',is_100s='" . $_POST['100s_i_e'] . "',is_50s='" . $_POST['50s_i_e'] . "',is_20s='" . $_POST['20s_i_e'] . "',is_10s='" . $_POST['10s_i_e'] . "',is_5s='" . $_POST['5s_i_e'] . "',is_1s='" . $_POST['1s_i_e'] . "',is_total_amount='" . $_POST['grand_total_issue_e'] . "',is_total_notes='" . $_POST['total_issue_e'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Evening' and status='Y' ";
			$uiss_eve_exc = mysqli_query($uiss_eve);
			//Total
			$uiss_tot = "update vault_issuable_details set is_1000s='" . $_POST['1000s_i_t'] . "',is_500s='" . $_POST['500s_i_t'] . "',is_100s='" . $_POST['100s_i_t'] . "',is_50s='" . $_POST['50s_i_t'] . "',is_20s='" . $_POST['20s_i_t'] . "',is_10s='" . $_POST['10s_i_t'] . "',is_5s='" . $_POST['5s_i_t'] . "',is_1s='" . $_POST['1s_i_t'] . "',is_total_amount='" . $_POST['grand_total_issue_t'] . "',is_total_notes='" . $_POST['total_issue_t'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Total' and status='Y' ";
			$uiss_tot_exc = mysqli_query($uiss_tot);
		}
		//Total 
		if ($id == '') {
			//Day
			$tot_day = "insert into vault_total_cash_details(t_date,cashier_entry_id,cashier_id,coll_type,t_1000s,t_500s,t_100s,t_50s,t_20s,t_10s,t_5s,t_1s,t_total_amount,t_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Day','" . $_POST['1000s_t_d'] . "','" . $_POST['500s_t_d'] . "','" . $_POST['100s_t_d'] . "','" . $_POST['50s_t_d'] . "','" . $_POST['20s_t_d'] . "','" . $_POST['10s_t_d'] . "','" . $_POST['5s_t_d'] . "','" . $_POST['1s_t_d'] . "','" . $_POST['grand_total_t_d'] . "','" . $_POST['total_t_d'] . "','Y','" . $user_name . "',now())";
			$tot_day_exc = mysqli_query($tot_day);
			//Evening
			$tot_eve = "insert into vault_total_cash_details(t_date,cashier_entry_id,cashier_id,coll_type,t_1000s,t_500s,t_100s,t_50s,t_20s,t_10s,t_5s,t_1s,t_total_amount,t_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Evening','" . $_POST['1000s_t_e'] . "','" . $_POST['500s_t_e'] . "','" . $_POST['100s_t_e'] . "','" . $_POST['50s_t_e'] . "','" . $_POST['20s_t_e'] . "','" . $_POST['10s_t_e'] . "','" . $_POST['5s_t_e'] . "','" . $_POST['1s_t_e'] . "','" . $_POST['grand_total_t_e'] . "','" . $_POST['total_t_e'] . "','Y','" . $user_name . "',now())";
			$tot_eve_exc = mysqli_query($tot_eve);
			//Total 
			$tot_total = "insert into vault_total_cash_details(t_date,cashier_entry_id,cashier_id,coll_type,t_1000s,t_500s,t_100s,t_50s,t_20s,t_10s,t_5s,t_1s,t_total_amount,t_total_notes,status,created_by,created_date) values('" . $entry_date . "','" . $ins_id . "','" . $cashier_id . "','Total','" . $_POST['1000s_t_t'] . "','" . $_POST['500s_t_t'] . "','" . $_POST['100s_t_t'] . "','" . $_POST['50s_t_t'] . "','" . $_POST['20s_t_t'] . "','" . $_POST['10s_t_t'] . "','" . $_POST['5s_t_t'] . "','" . $_POST['1s_t_t'] . "','" . $_POST['grand_total_t_t'] . "','" . $_POST['total_t_t'] . "','Y','" . $user_name . "',now())";
			$tot_tot_exc = mysqli_query($tot_total);
			$nav = '2_1';
		} else {
			//Day
			$utot_day = "update vault_total_cash_details set t_1000s='" . $_POST['1000s_t_d'] . "',t_500s='" . $_POST['500s_t_d'] . "',t_100s='" . $_POST['100s_t_d'] . "',t_50s='" . $_POST['50s_t_d'] . "',t_20s='" . $_POST['20s_t_d'] . "',t_10s='" . $_POST['10s_t_d'] . "',t_5s='" . $_POST['5s_t_d'] . "',t_1s='" . $_POST['1s_t_d'] . "',t_total_amount='" . $_POST['grand_total_t_d'] . "',t_total_notes='" . $_POST['total_t_d'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Day' and status='Y' ";
			$utot_day_exc = mysqli_query($utot_day);
			//Evening
			$utot_eve = "update vault_total_cash_details set t_1000s='" . $_POST['1000s_t_e'] . "',t_500s='" . $_POST['500s_t_e'] . "',t_100s='" . $_POST['100s_t_e'] . "',t_50s='" . $_POST['50s_t_e'] . "',t_20s='" . $_POST['20s_t_e'] . "',t_10s='" . $_POST['10s_t_e'] . "',t_5s='" . $_POST['5s_t_e'] . "',t_1s='" . $_POST['1s_t_e'] . "',t_total_amount='" . $_POST['grand_total_t_e'] . "',t_total_notes='" . $_POST['total_t_e'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Evening' and status='Y' ";
			$utot_eve_exc = mysqli_query($utot_eve);
			//Total
			$utot_tot = "update vault_total_cash_details set t_1000s='" . $_POST['1000s_t_t'] . "',t_500s='" . $_POST['500s_t_t'] . "',t_100s='" . $_POST['100s_t_t'] . "',t_50s='" . $_POST['50s_t_t'] . "',t_20s='" . $_POST['20s_t_t'] . "',t_10s='" . $_POST['10s_t_t'] . "',t_5s='" . $_POST['5s_t_t'] . "',t_1s='" . $_POST['1s_t_t'] . "',t_total_amount='" . $_POST['grand_total_t_t'] . "',t_total_notes='" . $_POST['total_t_t'] . "',updated_by='" . $user_name . "',updated_date=now() where cashier_entry_id='" . $id . "' and coll_type='Total' and status='Y' ";
			$utot_tot_exc = mysqli_query($utot_tot);
			$nav = '2_2';
		}

		if ($tot_tot_exc) {
			// mysqli_close($conn);
			header('Location:../?pid=vault_cashier_entry&nav=' . $nav);
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=vault_cashier_entry&nav=' . $nav);
		}
	}


	//Vault in
	else if ($pid == 'vault_entry') {
		$id = $_POST['id'];
		if ($id != '') {
			$exc = mysqli_query("Update vault_in_out_details set sup_id ='" . $_POST['supervisor'] . "',vault_incharge='" . $_POST['vault_in_charge'] . "',updated_by='" . $user_name . "',updated_date=now() where id='" . $id . "' and status='Y' ");
			$nav = '2_1_3';
		} else {
			if ($_POST['vault_type'] == 'Cash') {
				$query = "insert into vault_in_out_details(date,vault_status,vault_id,vault_type,avail_amount,dep_amount,1000s,500s,100s,50s,20s,10s,5s,1s,in_time,cashier_id,sup_id,vault_incharge,status,created_by,created_date) values('" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','IN','" . $_POST['vault_name'] . "','" . $_POST['vault_type'] . "','" . $_POST['grand_total_rupe'] . "','" . $_POST['n_grand_total_rupe'] . "','" . $_POST['n_1000s'] . "','" . $_POST['n_500s'] . "','" . $_POST['n_100s'] . "','" . $_POST['n_50s'] . "','" . $_POST['n_20s'] . "','" . $_POST['n_10s'] . "','" . $_POST['n_5s'] . "','" . $_POST['n_1s'] . "','" . $_POST['time_in'] . "','" . $_POST['cashier_id'] . "','" . $_POST['supervisor'] . "','" . $_POST['vault_in_charge'] . "','Y','" . $user_name . "',now())";
			} else if ($_POST['vault_type'] == 'Bag') {
				$query = "insert into vault_in_out_details(date,vault_status,vault_id,vault_type,avail_bag,dep_bag,1000s,500s,100s,50s,20s,10s,5s,1s,in_time,cashier_id,sup_id,vault_incharge,status,created_by,created_date) values('" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','IN','" . $_POST['vault_name'] . "','" . $_POST['vault_type'] . "','" . $_POST['no_bags'] . "','" . $_POST['no_bags'] . "','" . $_POST['n_1000s'] . "','" . $_POST['n_500s'] . "','" . $_POST['n_100s'] . "','" . $_POST['n_50s'] . "','" . $_POST['n_20s'] . "','" . $_POST['n_10s'] . "','" . $_POST['n_5s'] . "','" . $_POST['n_1s'] . "','" . $_POST['time_in'] . "','" . $_POST['cashier_id'] . "','" . $_POST['supervisor'] . "','" . $_POST['vault_in_charge'] . "','Y','" . $user_name . "',now())";
			}

			$exc = mysqli_query($query);
			$nav = '2_1';
			if ($exc) {
				$q1 = "update vault_ce_entry_mul set vstatus='V' where cashier_id='" . $_POST['cashier_id'] . "' and vault_name='" . $_POST['vault_name'] . "' and vault_type = '" . strtolower($_POST['vault_type']) . "' and entry_date='" . date('Y-m-d', strtotime($_POST['entry_date'])) . "' and status='Y' ";

				$ex1 = mysqli_query($q1);

				$sel_tr = mysqli_query("Select vtrans_id,vault_in,balance from vault_daily_trans where trans_date='" . date('Y-m-d', strtotime($_POST['entry_date'])) . "' and trans_type='" . $_POST['vault_type'] . "' and vault_id='" . $_POST['vault_name'] . "'");
				//$vault_in = $balance = 0;
				if (mysqli_num_rows($sel_tr) > 0) {
					$tr_rw = mysqli_fetch_object($sel_tr);
					$vault_in = $tr_rw->vault_in + $_POST['n_grand_total_rupe'];
					$balance = $tr_rw->balance + $_POST['n_grand_total_rupe'];
					$up_rw = mysqli_query("Update vault_daily_trans set vault_in='" . $vault_in . "',balance='" . $balance . "',updated_by='" . $user_name . "',updated_date=now() where vtrans_id = '" . $tr_rw->vtrans_id . "' and status='Y' ");
				} else {
					if ($_POST['vault_type'] == 'Cash') {
						$ins_trans = mysqli_query("insert into vault_daily_trans(vault_id,trans_date,trans_type,vault_in,balance,status,created_by,created_date) values('" . $_POST['vault_name'] . "','" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $_POST['vault_type'] . "','" . $_POST['n_grand_total_rupe'] . "','" . $_POST['n_grand_total_rupe'] . "','Y','" . $user_name . "',now())");
					} else if ($_POST['vault_type'] == 'Bag') {
						$ins_trans = mysqli_query("insert into vault_daily_trans(vault_id,trans_date,trans_type,vault_in,balance,status,created_by,created_date) values('" . $_POST['vault_name'] . "','" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $_POST['vault_type'] . "','" . $_POST['no_bags'] . "','" . $_POST['no_bags'] . "','Y','" . $user_name . "',now())");
					}
				}
			}
		}
		if ($exc) { // mysqli_close($conn); header("Location:../?pid=$pid&nav=$nav"); 
		} else { // mysqli_close($conn); header("Location:../?pid=$pid&nav=2_1_1"); 
		}
	}



	//Vault Out Deposit
	else if ($pid == 'vault_out_deposit') {
		$tot_amt = $tot_bag = 0;
		if ($_POST['id'] == '') {
			foreach ($_POST['vault_type'] as $key => $val) {
				if ($val == 'Cash') {
					$avail_amt = $_POST['available_amount'];
					$tot_amt += $_POST['break_amount'][$key];
				} else if ($val == 'Bag') {
					$avail_amt = $_POST['available_bag'];
					$tot_bag += $_POST['break_amount'][$key];
				}
				$up_dep = "insert into vault_deposit_details(dep_date,vault_id,vault_type,break_amount,avail_amount,dep_bank,deposit_by,remarks,status,created_by,	created_date) values('" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $_POST['vault_id'] . "','" . $_POST['vault_type'][$key] . "','" . $_POST['break_amount'][$key] . "','" . $avail_amt . "','" . $_POST['account_id'][$key] . "','" . $_POST['ce_id'][$key] . "','" . $_POST['remarks'][$key] . "','Y','" . $user_name . "',now())";
				$dep_exc = mysqli_query($up_dep);
				$nav = '2_1';
			}
		} else {
			//Update	
		}
		$chk_aval = mysqli_query("Select vtrans_id,trans_date,trans_type,balance from vault_daily_trans where vault_id ='" . $_POST['vault_id'] . "' and trans_date='" . date('Y-m-d', strtotime($_POST['entry_date'])) . "' and status='Y' ");
		if (mysqli_num_rows($chk_aval) > 0) {
			while ($rw_avl = mysqli_fetch_array($chk_aval)) {
				if ($rw_avl['trans_type'] == 'Cash') {
					$up_trans = "Update vault_daily_trans set balance=(balance-$tot_amt),vault_out=(vault_out+$tot_amt) where vtrans_id='" . $rw_avl['vtrans_id'] . "'";
				} else if ($rw_avl['trans_type'] == 'Bag') {
					$up_trans = "Update vault_daily_trans set balance=(balance-$tot_bag),vault_out=(vault_out+$tot_bag) where vtrans_id='" . $rw_avl['vtrans_id'] . "'";
				}
				$uptr_ex = mysqli_query($up_trans);
			}
		} else {
			$sel_sql = "SELECT vtrans_id,trans_date,trans_type,balance FROM vault_daily_trans where status='Y' and vault_id ='" . $_POST['vault_id'] . "' and trans_date<'" . date('Y-m-d', strtotime($_POST['entry_date'])) . "' group by trans_type, trans_date order by vtrans_id desc limit 0,2";
			$sel_ex = mysqli_query($sel_sql);
			while ($rw = mysqli_fetch_array($sel_ex)) {
				if ($rw['trans_type'] == 'Cash') {
					$ins_next = mysqli_query("insert into vault_daily_trans(vault_id,trans_date,trans_type,open_balance,vault_out,balance,status,created_by,created_date) values('" . $_POST['vault_id'] . "','" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $rw['trans_type'] . "','" . $rw['balance'] . "','" . $tot_amt . "','" . ($rw['balance'] - $tot_amt) . "','Y','" . $user_name . "',now())");
				} else if ($rw['trans_type'] == 'Bag') {
					$ins_next = mysqli_query("insert into vault_daily_trans(vault_id,trans_date,trans_type,open_balance,vault_out,balance,status,created_by,created_date) values('" . $_POST['vault_id'] . "','" . date('Y-m-d', strtotime($_POST['entry_date'])) . "','" . $rw['trans_type'] . "','" . $rw['balance'] . "','" . $tot_bag . "','" . ($rw['balance'] - $tot_bag) . "','Y','" . $user_name . "',now())");
				}
			}
		}
		if ($dep_exc) {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=" . $nav);
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_5");
		}
	} else if ($pid == 'mapping') {

		$id = $_POST['id'];
		$shop_id = $_POST['shop_id'];
		$pre_ce = $_POST['pre_ce'];
		$region_name = $_POST['branch'];
		$sec_ce = $_POST['sec_ce'];
		$add_ce = $_POST['add_ce'];
		$updated_by = $_POST['updated_by'];
		$updated_date = $_POST['updated_date'];

		$status = $_POST['status'];
		if ($id == '') {
			//Insert
			$query = "insert into ce_mapping(shop_id,region_name,pre_ce,updated_by,updated_date,status) values('" . $shop_id . "','" . $region_name . "','" . $pre_ce . "','" . $update_by . "','" . $update_date . "','Y')";
			$exc = mysqli_query($query);

			if ($exc) {
				$page = $_SERVER['HTTP_REFERER'];
				header('Location:index.php?pid=' . $pid . '&nav=2_1_1');
			} else {
				$page = $_SERVER['HTTP_REFERER'];
				header('Location:index.php?pid=' . $pid . '&nav=2_2_1');
			}
		} else {
			// Update
			$query = "Update normal set date='" . $date . "',location='" . $location . "',day='" . $day . "',outer_address='" . $outer_address . "',mbc_staff_name='" . $mbc_staff_name . "',id_no='" . $id_no . "',in_time='" . $in_time . "',out_time='" . $out_time . "',tot_time='" . $tot_time . "',remark='" . $remark . "',updated_date=now() where id='" . $id . "' and status='Y'  ";
			$exc = mysqli_query($query);
			if ($exc) {
				$page = $_SERVER['HTTP_REFERER'];
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_3_7');
			} else {
				$page = $_SERVER['HTTP_REFERER'];
				// mysqli_close($conn);
				header('Location:index.php?pid=' . $pid . '&nav=2_2_1');
			}
		}
	} else if ($pid == 'cheque') {

		$trans_no = $_POST['trans_no'];
		$id = $_POST['id'];
		$date1 = date('Y-m-d', strtotime($_POST['date1']));
		$in_time1 = $_POST['in_time1'];
		$out_time1 = $_POST['out_time1'];
		$no_of_stud = $_POST['no_of_stud'];
		$tot_amt1 = $_POST['tot_amt1'];
		$amt_1000 = $_POST['amt_1000'];
		$amt_500 = $_POST['amt_500'];
		$amt_100 = $_POST['amt_100'];
		$amt_50 = $_POST['amt_50'];
		$amt_20 = $_POST['amt_20'];
		$amt_10 = $_POST['amt_10'];
		$amt_5 = $_POST['amt_5'];
		$coins = $_POST['coins'];
		$deno_tot1 = $_POST['deno_tot1'];
		$deno_diff = $_POST['deno_diff'];
		$remarks1 = $_POST['remarks1'];
		$date2 = date('Y-m-d', strtotime($_POST['date2']));
		$no_slip = $_POST['no_slip'];
		$type = $_POST['type'];
		$pjt_code = $_POST['pjt_code'];
		$pty_type = $_POST['pty_type'];
		$pty_category = $_POST['pty_category'];
		$ch_no = $_POST['ch_no'];
		$bank_name = $_POST['bank_name'];
		$bank_branch = $_POST['bank_branch'];
		$amt2 = $_POST['amt2'];
		$remarks2 = $_POST['remarks2'];
		$date3 = date('Y-m-d', strtotime($_POST['date3']));
		$day3 = $_POST['day3'];
		$in_time3 = $_POST['in_time3'];
		$out_time3 = $_POST['out_time3'];
		$remarks3 = $_POST['remarks3'];
		$status = $_POST['status'];
		//Insert
		$query = "Update mbc_daily_trans set trans_no='" . $trans_no . "',date1='" . $date1 . "',in_time1='" . $in_time1 . "',out_time1='" . $out_time1 . "',no_of_stud='" . $no_of_stud . "',tot_amt1='" . $tot_amt1 . "',amt_1000='" . $amt_1000 . "',amt_500='" . $amt_500 . "',amt_100='" . $amt_100 . "',amt_50='" . $amt_50 . "',amt_10='" . $amt_10 . "',amt_5='" . $amt_5 . "',coins='" . $coins . "',deno_tot1='" . $deno_tot1 . "',deno_diff='" . $deno_diff . "', remarks1='" . $remarks1 . "',date2='" . $date2 . "',no_slip='" . $no_slip . "',type='" . $type . "',pjt_code='" . $pjt_code . "',pty_type='" . $pty_type . "',pty_category='" . $pty_category . "',ch_no='" . $ch_no . "',bank_name='" . $bank_name . "',bank_branch='" . $bank_branch . "',amt2='" . $amt2 . "',remarks2='" . $remarks2 . "',date3='" . $date3 . "',day3='" . $day3 . "',in_time3='" . $in_time3 . "',out_time3='" . $out_time3 . "',remarks3='" . $remarks3 . "',status='Y',updated_by='" . $emp_id . "',updated_date=now() where trans_id='" . $id . "'   ";
		$exc = mysqli_query($query);

		if ($exc) {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_1_1');
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_2_1');
		}
	} else if ($pid == "client_login") {

		$login_uname  = !empty($_POST['login_uname']) ? $_POST['login_uname'] : "";
		$login_password = !empty($_POST['login_password']) ? $_POST['login_password'] : "";
		$client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : "";
		$img_auth = !empty($_POST['img_auth']) ? $_POST['img_auth'] : "";
		$status = !empty($_POST['status']) ? $_POST['status'] : "";
		$cust_id = !empty($_POST['cust_id']) ? $_POST['cust_id'] : "";

		if ($id == '') {
			$query = "insert into client_login(user_name,password,img_auth,client_id,cust_id,status,per) values('" . $login_uname . "','" . $login_password . "','" . $img_auth . "','" . $client_id . "','" . $cust_id . "','" . $status . "','" . $per . "')";
			$nav = 1;
		} else {
			$query = "Update client_login set password='" . $login_password . "',img_auth='" . $img_auth . "',client_id='" . $client_id . "',cust_id='" . $cust_id . "',per='" . $per . "',status='" . $status . "' where user_name='" . $id . "' ";
			$nav = 3;
		}

		$exc = mysqli_query($writeConnection, $query);
		if ($exc) {
			// mysqli_close($conn);
			header('Location:../?pid=client_login&nav=' . $nav);
			exit;
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=client_login&nav=2');
			exit;
		}
	}

	if ($pid == 'report_type') {
		$shop_id = $_POST['shop_id'];
		$select_sql = mysqli_query($readConnection, "select sd.shop_id,sd.shop_name,cd.cust_name,cld.client_name,lm.location,reg.region_name,sc.pri_ce,rce.mobile1 from shop_details sd join cust_details cd on  sd.cust_id=cd.cust_id join client_details cld on cld.client_id=cd.client_id join location_master lm on lm.loc_id=sd.location join radiant_location rl on rl.location_id=sd.location join region_master reg on reg.region_id=rl.region_id join shop_cemap sc on sc.shop_id=sd.shop_id join radiant_ce rce on sc.pri_ce=rce.ce_id where sd.shop_id='" . $shop_id . "' and sd.status='Y' and cd.status='Y' and cld.status='Y' and sc.status='Y' and lm.status='Y' and rl.status='Y'");
		$row = mysqli_fetch_object($select_sql);
		$date = date('Y-m-d', strtotime($_POST['tran_date']));
		$rad_shopid = $_POST['rad_shopid'];
		$req_amt = $_POST['req_amt'];
		$req_type = $_POST['req_type'];
		$sms_date = date('d-M-Y, h:i:s A');


		//auto increment
		$query = "select max(trans_no) as id from daily_trans where pickup_date='" . $date . "'";
		$exec = mysqli_query($readConnection, $query);
		$max_id = mysqli_fetch_object($exec);
		if ($max_id->id != NULL) {
			$query2 = "select trans_no from daily_trans where trans_no='" . $max_id->id . "'";
			$exec = mysqli_query($readConnection, $query2);
			$row_btn_code = mysqli_fetch_object($exec);
			$a = explode(".", $row_btn_code->trans_no);
			$res = str_pad($a[0] + 1, 5, '0', STR_PAD_LEFT);
			$result = $res . "." . date('ymd', strtotime($date));
		} else {
			$result = '00001' . "." . date('ymd', strtotime($date));
		}
		$sql = "insert into daily_trans(trans_no,region,location,client_name,cust_name,pickup_code,pickup_name,pickup_amount,type,staff_id,mobile1,sent_by,sms_sent,pickup_date,status)values('" . $result . "','" . $row->region_name . "','" . $row->location . "','" . $row->client_name . "','" . $row->cust_name . "','" . $row->shop_id . "','" . $row->shop_name . "','" . $req_amt . "','" . $req_type . "','" . $row->pri_ce . "','" . $row->mobile1 . "','" . $user_name . "','" . $sms_date . "','" . $date . "','Y')";
		if (mysqli_query($writeConnection, $sql) == TRUE) {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_3');
		} else {
			// mysqli_close($conn);
			header('Location:../?pid=' . $pid . '&nav=2_2');
		}
	} else if ($pid == 'dis_cre') {

		foreach ($_POST['enable_desc'] as $key => $val) {

			$sql_upd = mysqli_query($writeConnection, "SELECT trans_id FROM daily_discrepancy WHERE `trans_id`='" . $key . "' AND status='Y'");
			if (mysqli_num_rows($sql_upd) > 0) {
				$query = "UPDATE `daily_discrepancy` SET `s_100`='" . $_POST['descin']['1_1_' . $key] . "', `e_100`='" . $_POST['descin']['1_2_' . $key] . "', `m_100`='" . $_POST['descin']['1_3_' . $key] . "', `f_100`='" . $_POST['descin']['1_4_' . $key] . "', `s_200`='" . $_POST['descin']['2_1_' . $key] . "', `e_200`='" . $_POST['descin']['2_2_' . $key] . "', `m_200`='" . $_POST['descin']['2_3_' . $key] . "', `f_200`='" . $_POST['descin']['2_4_' . $key] . "',`s_500`='" . $_POST['descin']['3_1_' . $key] . "', `e_500`='" . $_POST['descin']['3_2_' . $key] . "', `m_500`='" . $_POST['descin']['3_3_' . $key] . "', `f_500`='" . $_POST['descin']['3_4_' . $key] . "', `s_1000`='" . $_POST['descin']['4_1_' . $key] . "', `e_1000`='" . $_POST['descin']['4_2_' . $key] . "', `m_1000`='" . $_POST['descin']['4_3_' . $key] . "', `f_1000`='" . $_POST['descin']['4_4_' . $key] . "',`s_2000`='" . $_POST['descin']['5_1_' . $key] . "', `e_2000`='" . $_POST['descin']['5_2_' . $key] . "',`m_2000`='" . $_POST['descin']['5_3_' . $key] . "', `f_2000`='" . $_POST['descin']['5_4_' . $key] . "',`remarks`='" . $_POST['remarks'][$key] . "', `update_by`='" . $user . "', `update_time`=now() WHERE `trans_id`='" . $key . "' AND status='Y'";
			} else {

				$query = "INSERT INTO `daily_discrepancy` (`trans_id`, `s_100`, `e_100`, `m_100`, `f_100`,`s_200`, `e_200`, `m_200`, `f_200`, `s_500`, `e_500`, `m_500`, `f_500`, `s_1000`, `e_1000`, `m_1000`, `f_1000`, `s_2000`, `e_2000`, `m_2000`, `f_2000`, `remarks`, `update_by`, `update_time`, `status`) VALUES ('" . $key . "', '" . $_POST['descin']['1_1_' . $key] . "', '" . $_POST['descin']['1_2_' . $key] . "', '" . $_POST['descin']['1_3_' . $key] . "', '" . $_POST['descin']['1_4_' . $key] . "', '" . $_POST['descin']['2_1_' . $key] . "', '" . $_POST['descin']['2_2_' . $key] . "', '" . $_POST['descin']['2_3_' . $key] . "', '" . $_POST['descin']['2_4_' . $key] . "', '" . $_POST['descin']['3_1_' . $key] . "', '" . $_POST['descin']['3_2_' . $key] . "', '" . $_POST['descin']['3_3_' . $key] . "', '" . $_POST['descin']['3_4_' . $key] . "', '" . $_POST['descin']['4_1_' . $key] . "', '" . $_POST['descin']['4_2_' . $key] . "', '" . $_POST['descin']['4_3_' . $key] . "', '" . $_POST['descin']['4_4_' . $key] . "', '" . $_POST['descin']['5_1_' . $key] . "', '" . $_POST['descin']['5_2_' . $key] . "', '" . $_POST['descin']['5_3_' . $key] . "', '" . $_POST['descin']['5_4_' . $key] . "' ,  '" . $_POST['remarks'][$key] . "' , '" . $user . "', now(), 'Y')";
			}

			$sql = mysqli_query($writeConnection, $query);
		}

		$amount = $_POST['amount'];

		foreach ($amount as $key1 => $val1) {
			echo "SELECT region FROM `daily_deposti_dis_bank` WHERE `region`='" . $_POST['region_name'] . "' AND `client_name`='" . $_POST['client'] . "' AND `dep_date`='" . date('Y-m-d', strtotime($_POST['sdate'])) . "' AND `rec_no`='" . $key1 . "' AND status='Y'";
			$query_cc = mysqli_query($writeConnection, "SELECT region FROM `daily_deposti_dis_bank` WHERE `region`='" . $_POST['region_name'] . "' AND `client_name`='" . $_POST['client'] . "' AND `dep_date`='" . date('Y-m-d', strtotime($_POST['sdate'])) . "' AND `rec_no`='" . $key1 . "' AND status='Y'");
			$row_cc = mysqli_num_rows($query_cc);
			if ($row_cc > 0) {
				echo $sql_cd = "UPDATE `daily_deposti_dis_bank` SET `acc_id`='" . $_POST['acc_id'][$key1] . "', `amount`='" . $_POST['amount'][$key1] . "', `2000s`='" . $_POST['2000s'][$key1] . "',`1000s`='" . $_POST['1000s'][$key1] . "', `500s`='" . $_POST['500s'][$key1] . "',`200s`='" . $_POST['200s'][$key1] . "', `100s`='" . $_POST['100s'][$key1] . "', `50s`='" . $_POST['50s'][$key1] . "', `20s`='" . $_POST['20s'][$key1] . "', `10s`='" . $_POST['10s'][$key1] . "', `5s`='" . $_POST['5s'][$key1] . "', `coins`='" . $_POST['coins'][$key1] . "', `remarks`='" . $_POST['remarkscd'][$key1] . "', `created_by`='" . $user . "', `created_date`=now() WHERE `region`='" . $_POST['region_name'] . "' AND `client_name`='" . $_POST['client'] . "' AND `dep_date`='" . date('Y-m-d', strtotime($_POST['sdate'])) . "' AND `rec_no`='" . $key1 . "' AND status='Y'";
			} else {
				$sql_cd = "INSERT INTO `daily_deposti_dis_bank` (`region`, `client_name`, `dep_date`, `rec_no`, `acc_id`, `amount`, `2000s`,`1000s`, `500s`, `200s`,`100s`, `50s`, `20s`, `10s`, `5s`, `coins`, `remarks`, `created_by`, `created_date`, `status`) VALUES
('" . $_POST['region_name'] . "', '" . $_POST['client'] . "', '" . date('Y-m-d', strtotime($_POST['sdate'])) . "', '" . $key1 . "', '" . $_POST['acc_id'][$key1] . "', '" . $_POST['amount'][$key1] . "', '" . $_POST['2000s'][$key1] . "','" . $_POST['1000s'][$key1] . "', '" . $_POST['500s'][$key1] . "', '" . $_POST['200s'][$key1] . "','" . $_POST['100s'][$key1] . "', '" . $_POST['50s'][$key1] . "', '" . $_POST['20s'][$key1] . "', '" . $_POST['10s'][$key1] . "', '" . $_POST['5s'][$key1] . "', '" . $_POST['coins'][$key1] . "', '" . $_POST['remarkscd'][$key1] . "', '" . $user . "', now(), 'Y')";
			}
			mysqli_query($writeConnection, $sql_cd);
		}
		// mysqli_close($conn);
		header('Location:../?pid=dis_cre&nav=1');
		exit;
	} else if ($pid == 'dupload_m') {

		$target_path = "../Excel/";
		$entry_date = date("Y-m-d");

		//Get the Extension	
		$filename = date('d-m-Y_G_i_s') . '_' . $_FILES['uploadfile']['name'];
		$base1 = basename($filename);
		$exts = explode(".", $base1);
		$target = $exts[0];
		$target = rand(10, 100) . '_' . $target . "$dreg_id." . $exts[1];
		$target_path = $target_path . $target;
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path)) {
			$_SESSION['dynamic_region'] = $_POST['region'];
			$_SESSION['dynamic_file'] = $target;
			header("Location:../?pid=$pid&nav=1");
		} else {
			header("Location:../?pid=$pid&nav=2");
		}

	} else if ($pid == 'dupload_m1') {

		$entry_date = date("Y-m-d");
		$region_name = $_SESSION['dynamic_region'];
		$sqlr = "SELECT region_id FROM region_master WHERE region_name='" . $region_name . "' AND status='Y'";
		$qur = mysqli_query($readConnection, $sqlr);
		while ($rr = mysqli_fetch_array($qur)) {
			$region_id = $rr['region_id'];
		}

		if ($region_id >= 1 && $region_id < 10) $dreg_id = "0" . $region_id;
		if ($region_id >= 10 && $region_id < 100) $dreg_id = $region_id;
		$dfd = date("dd-mm-yyyy");
		$ddate = date("ymd");


		$target_path = "../Excel/" . $_SESSION['dynamic_file'];
		error_reporting(E_ALL ^ E_NOTICE);

		$spreadsheet = IOFactory::load($target_path);
        $sheet = $spreadsheet->getActiveSheet();
        $dataUploaded = $sheet->toArray();

		$noTrans = 1;
		$sqlt = "SELECT trans_id FROM daily_trans_m WHERE request_date='" . $entry_date . "' AND status";
		$qut = mysqli_query($writeConnection, $sqlt);
		$nt = mysqli_num_rows($qut);
		$total_rec = $nt + 1;

		foreach ($dataUploaded as $value) {

			if (trim($value[0]) != "Location") {
				if (trim($value[10]) != "") {
					
					$drec_id = sprintf("%05d", $total_rec);
					$trans_no = 'D' . $ddate . "." . $drec_id;
					$sql = "INSERT INTO `daily_trans_m` (`trans_no`, `region`, `location`, `client_name`, `cust_name`, `crm_code`, `contact_person`, `address`, `shop_mobile`, `ptp_date`, `request_date`, `pickup_amount`, `pickup_type`, `client_call_center_number`, `client_failure_notice_mobile_number`, `created_by`, `status`,`password`) VALUES ('" . $trans_no . "', '" . $region_name . "', '" . trim($value[0]) . "', '" . trim($value[1]) . "', '" . trim($value[2]) . "', '" . trim($value[3]) . "', '" . trim($value[4]) . "', '" . trim(mysqli_real_escape_string($writeConnection,$value[5])) . "', '" . trim($value[6]) . "', '" . trim($value[7]) . "', '" . trim(date('Y-m-d', strtotime($value[8]))) . "', '" . trim($value[9]) . "', '" . trim($value[10]) . "', '" . trim($value[11]) . "', '" . trim($value[12]) . "', '" . $user . "','Y','0')";
					$qu = mysqli_query($writeConnection, $sql);
					$total_rec++;
					$noTrans++;
				}
			}
		}

		$total_rec--;
		$noTrans--;

		$pid = 'dupload_m';
		unset($_SESSION['dynamic_file']);
		unset($_SESSION['dynamic_region']);
		header("Location:../?pid=$pid&nav=2_1&region=$region_name&total=$noTrans&con=1");
	}



	//Dynamice Change Point Daily Collect 
	elseif ($pid == "dcollect_m") {

		$mclass = new sendSms();

		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);
		$trans_id = $_POST['trans_id'];

		$remarksss = $_POST['coll_remarks'] . '-' . $_POST['other_remarks'];
		$coll_date = date('Y-m-d');
		if ($_POST['t_rec'] > 1) {
			$amt_dif = $_POST['req_amount'] - $_POST['pick_amount1'];
			$sql_trans = mysqli_query("SELECT  trans_id FROM daily_collection_m WHERE trans_id='" . $trans_id . "' AND status='Y'");
			if (mysqli_num_rows($sql_trans) > 0) {


				$query_daily = "UPDATE  `daily_collection_m` SET `multi_rec`='Y', `c_code`='MULTI', `rec_no`='MULTI', `pis_hcl_no`='MULTI', `hcl_no`='MULTI', `gen_slip`='MULTI', `pick_amount`='" . $_POST['pick_amount1'] . "', `diff_amount`='" . $amt_dif . "', `dep_type1`='" . $_POST['dep_type1'] . "', `dep_accid`='" . $_POST['dep_acc1'] . "', `dep_branch`='" . $_POST['dep_branch'] . "', `dep_slip`='" . $_POST['dep_slip'] . "', `dep_amount1`='" . $_POST['dep_amount1'] . "', `coll_remarks`='" . $remarksss . "', `coll_date`='" . $coll_date . "', `coll_time`='" . $time . "', `pick_time`='" . $_POST['pick_time'] . "' WHERE `trans_id`='" . $trans_id . "' AND status='Y'";
			} else {
				$query_daily = "INSERT INTO `daily_collection_m` (`trans_id`, `multi_rec`, `t_rec`, `c_code`, `rec_no`, `pis_hcl_no`, `hcl_no`, `gen_slip`, `req_amount`, `pick_amount`, `diff_amount`, `dep_type1`, `dep_accid`, `dep_branch`, `dep_slip`, `dep_amount1`, `coll_remarks`, `coll_date`, `coll_time`, `pick_time`, `staff_id`, `user_name`, `status`) VALUES ('" . $trans_id . "', 'Y', '" . $_POST['t_rec'] . "', 'MULTI', 'MULTI', 'MULTI', 'MULTI', 'MULTI', '0', '" . $_POST['pick_amount1'] . "', '" . $amt_dif . "', '" . $_POST['dep_type1'] . "', '" . $_POST['dep_acc1'] . "', '" . $_POST['dep_branch'] . "', '" . $_POST['dep_slip'] . "', '" . $_POST['dep_amount1'] . "', '" . $remarksss . "', '" . $coll_date . "', '" . $time . "', '" . $_POST['pick_time'] . "', '" . $_POST['ce_id'] . "', '" . $user . "',  'Y')";
			}
			$sql_daily1 = mysqli_query($query_daily);

			for ($i = 1; $i <= $_POST['t_rec']; $i++) {
				$sql_2 = mysqli_query("SELECT trans_id FROM daily_collectionmul_m WHERE trans_id='" . $trans_id . "' AND rec_id='" . $i . "'");
				if (mysqli_num_rows($sql_2) > 0) {
					$query_mul = "UPDATE `daily_collectionmul_m` SET `c_code`='" . $_POST['c_code' . $i] . "', `rec_no`='" . $_POST['rec_no' . $i] . "', `pis_hcl_no`='" . $_POST['pis_hcl_no' . $i] . "', `hcl_no`='" . $_POST['hcl_no' . $i] . "', `gen_slip`='" . $_POST['gen_slip' . $i] . "', `pick_amount`='" . $_POST['pick_amount' . $i] . "', `reason_for_nill`='" . $_POST['reason_for_nill' . $i] . "', `staff_id`='" . $_POST['ce_id'] . "', `coll_time`='" . $time . "' WHERE `trans_id`='" . $trans_id . "' AND `rec_id`='" . $i . "'";
				} else {
					$query_mul = "INSERT INTO `daily_collectionmul_m` (`trans_id`, `rec_id`, `c_code`, `rec_no`, `pis_hcl_no`, `hcl_no`, `gen_slip`, `pick_amount`, `reason_for_nill`, `staff_id`, `coll_date`, `coll_time`, `user_name`, `status`) VALUES ('" . $trans_id . "', '" . $i . "', '" . $_POST['c_code' . $i] . "', '" . $_POST['rec_no' . $i] . "', '" . $_POST['pis_hcl_no' . $i] . "', '" . $_POST['hcl_no' . $i] . "', '" . $_POST['gen_slip' . $i] . "', '" . $_POST['pick_amount' . $i] . "', '" . $_POST['reason_for_nill'] . "', '" . $_POST['ce_id'] . "', '" . $coll_date . "', '" . $time . "', '" . $user . "',  'Y')";
				}

				$sql_mul = mysqli_query($query_mul);
			}
		} else {
			$amt_dif = $_POST['req_amount'] - $_POST['pick_amount1'];
			$sql_trans = mysqli_query("SELECT  trans_id FROM daily_collection_m WHERE trans_id='" . $trans_id . "' AND status='Y'");
			if (mysqli_num_rows($sql_trans) > 0) {
				$query_daily = "UPDATE  `daily_collection_m` SET `c_code`='" . $_POST['c_code1'] . "', `rec_no`='" . $_POST['rec_no1'] . "', `pis_hcl_no`='" . $_POST['pis_hcl_no1'] . "', `hcl_no`='" . $_POST['hcl_no1'] . "', `gen_slip`='" . $_POST['gen_slip1'] . "', `req_amount`='" . $_POST['req_amount'] . "', `pick_amount`='" . $_POST['pick_amount1'] . "', `diff_amount`='" . $amt_dif . "', `reason_for_nill`='" . $_POST['reason_for_nill1'] . "', `dep_type1`='" . $_POST['dep_type1'] . "', `dep_accid`='" . $_POST['dep_acc1'] . "', `dep_branch`='" . $_POST['dep_branch'] . "', `dep_slip`='" . $_POST['dep_slip'] . "', `dep_amount1`='" . $_POST['dep_amount1'] . "', `coll_remarks`='" . $remarksss . "', `coll_date`='" . $coll_date . "', `coll_time`='" . $time . "', `pick_time`='" . $_POST['pick_time'] . "' WHERE `trans_id`='" . $trans_id . "' AND status='Y'";
			} else {
				$query_daily = "INSERT INTO `daily_collection_m` (`trans_id`, `multi_rec`, `t_rec`, `c_code`, `rec_no`, `pis_hcl_no`, `hcl_no`, `gen_slip`, `req_amount`, `pick_amount`, `diff_amount`, `reason_for_nill`, `dep_type1`, `dep_accid`, `dep_branch`, `dep_slip`, `dep_amount1`, `coll_remarks`, `coll_date`, `coll_time`, `pick_time`, `staff_id`, `user_name`, `status`) VALUES ('" . $trans_id . "', 'N', '1', '" . $_POST['c_code1'] . "', '" . $_POST['rec_no1'] . "', '" . $_POST['pis_hcl_no1'] . "', '" . $_POST['hcl_no1'] . "', '" . $_POST['gen_slip1'] . "', '" . $_POST['req_amount'] . "', '" . $_POST['pick_amount1'] . "', '" . $amt_dif . "', '" . $_POST['reason_for_nill1'] . "', '" . $_POST['dep_type1'] . "', '" . $_POST['dep_acc1'] . "', '" . $_POST['dep_branch'] . "', '" . $_POST['dep_slip'] . "', '" . $_POST['dep_amount1'] . "', '" . $remarksss . "', '" . $coll_date . "', '" . $time . "', '" . $_POST['pick_time'] . "', '" . $_POST['ce_id'] . "', '" . $user . "',  'Y')";
			}
			$sql_daily = mysqli_query($query_daily);
		}

		if ($_POST['field1'] != '' || $_POST['field2'] != '' || $_POST['field3'] || $_POST['field4']) {
			$sql_filed = mysqli_query("SELECT * FROM daily_collectionannex_m WHERE trans_id='" . $trans_id . "'");
			if (mysqli_num_rows($sql_filed) > 0) {
				$sql_query_field = "UPDATE daily_collectionannex_m SET `field1`='" . $_POST['field1'] . "', `field2`='" . $_POST['field2'] . "', `field3`='" . $_POST['field3'] . "', `field4`='" . $_POST['field4'] . "' WHERE trans_id='" . $trans_id . "'";
			} else {
				$sql_query_field = "INSERT INTO `daily_collectionannex_m` (`trans_id`, `field1`, `field2`, `field3`, `field4`, `status`) VALUES ('" . $trans_id . "', '" . $_POST['field1'] . "', '" . $_POST['field2'] . "', '" . $_POST['field3'] . "', '" . $_POST['field4'] . "', 'Y')";
			}
			mysqli_query($sql_query_field);
		}


		$sql_daily_change = mysqli_query("SELECT a.contact_person,  a.cust_name, a.pickup_type, a.pickup_amount, a.client_call_center_number, a.client_failure_notice_mobile_number, a.client_name, a.shop_mobile FROM daily_trans_m AS a JOIN radiant_ce AS b ON a.ce_id=b.ce_id  WHERE a.trans_id='" . $trans_id . "' AND a.status='Y' AND b.status='Y'");
		$res_daily_change = mysqli_fetch_assoc($sql_daily_change);
		$shop_mobile = $res_daily_change['shop_mobile'];
		$client_fail_mobile = $res_daily_change['client_failure_notice_mobile_number'];
		$call_center_no = $res_daily_change['client_call_center_number'];
		$ce_id = $res_daily_change['contact_person'];
		$cust_namess = $res_daily_change['cust_name'];


		if ($_POST['coll_remarks'] == 'Cash Received') {

			$sql_sms_logs = mysqli_query("INSERT INTO sms_log (`sent_type`, `user_id`, `sent_date`, `sent_time`, `no_sms`, `status`) VALUES ('Dynamic Cash Pickup - Success', '" . $user . "',  '" . date('Y-m-d') . "', '" . $time . "', '1', 'Y')");
			$sent_id = mysqli_insert_id();


			if ($cust_namess != 'Polaris') // polaris sms sent condation 
			{
				if ($cust_namess == 'LCI') {

					$smsmessage = "Dear " . $res_daily_change['contact_person'] . ", Received with thanks by mode of " . $res_daily_change['pickup_type'] . ", Receipt No." . $_POST['rec_no1'] . ", amount: " . $res_daily_change['pickup_amount'] . ". If any queries Please call us " . $call_center_no;
				} else {
					$smsmessage = "Dear: " . $res_daily_change['contact_person'] . ", Received with thanks by mode of " . $res_daily_change['pickup_type'] . ", Receipt No." . $_POST['rec_no1'] . ", amount: " . $res_daily_change['pickup_amount'] . ". If any queries Please call us " . $call_center_no;
				}


				if ($shop_mobile != "") {
					$cmobile1 = $shop_mobile;
					$mclass->sendSmsToUser($smsmessage, $shop_mobile, "");
					$no_sms++;
				}
			}

			$sstatus = "Sent";
			if ($cmobile1 == "") $sstatus = "Not Sent";
			$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Customer')");
		} else {
			if ($_POST['coll_remarks'] != '') {
				$sql_sms_logs = mysqli_query("INSERT INTO sms_log (`sent_type`, `user_id`, `sent_date`, `sent_time`, `no_sms`, `status`) VALUES ('Dynamic Cash Pickup - Failure Notice', '" . $user . "',  '" . date('Y-m-d') . "', '" . $time . "', '2', 'Y')");
				$sent_id = mysqli_insert_id();


				if ($cust_namess != 'Polaris') // polaris sms sent condation 
				{
					if ($cust_namess == 'LCI') {
						//Dear: surya, Greeting from Lifecell, We can't collect your payment of Rs. 2000 for the reason XXXXXXX . We will reschedule shortly. If any queries Please call us 8655228400			
						$smsmessage = "Dear: " . $res_daily_change['contact_person'] . ",  We can't collect your " . $res_daily_change['pickup_type'] . " of Rs. " . $res_daily_change['pickup_amount'] . " for the reason " . $_POST['reason_for_nill'] . ". We will reschedule shortly. If any queries Please call us " . $call_center_no;
					} else {
						$smsmessage = "Dear: " . $res_daily_change['contact_person'] . ", Greeting from " . $res_daily_change['client_name'] . ", We can't collect your " . $res_daily_change['pickup_type'] . "  amount: " . $res_daily_change['pickup_amount'] . " for the reason " . $_POST['reason_for_nill'] . ". We will reschedule shortly. If any queries Please call us " . $call_center_no;
					}
					//$res_trans['sms_sent']==0 && 
					if ($shop_mobile != "") {
						$cmobile1 = $shop_mobile;
						$mclass->sendSmsToUser($smsmessage, $shop_mobile, "");
						$no_sms++;
					}
				} // polaris condation end

				$sstatus = "Sent";
				if ($cmobile1 == "") $sstatus = "Not Sent";

				$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Customer')");



				//Fail
				//Customer
				if ($cust_namess != 'Polaris') // polaris sms sent condation 
				{
					if ($cust_namess == 'LCI') {

						$smsmessage = "Alert: Dear " . $res_daily_change['contact_person'] . " & " . $shop_mobile . ", Collection was unsuccessful for the reson " . $_POST['reason_for_nill'] . ". Confirm for rescheduling  by calling us " . $call_center_no;
					} else {
						$smsmessage = "Alert: Your " . $res_daily_change['contact_person'] . " & " . $shop_mobile . ", Collection unsuccessful for the reson " . $_POST['reason_for_nill'] . ". Confirm for rescheduling";
					}
					if ($client_fail_mobile != "") {
						$cmobile1 = $client_fail_mobile;
						$mclass->sendSmsToUser($smsmessage, $client_fail_mobile, "");
						$no_sms++;
					}
				}
				$sstatus = "Sent";
				if ($cmobile1 == "") $sstatus = "Not Sent";
				$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Client')");
			}
		}
		// mysqli_close($conn);
		header("Location:../?pid=$pid&nav=2_1&id=$trans_id");
	}










	//Dynamice Change Point Daily Cheque Collect 
	else if ($pid == 'chqcollect_m') {
		$mclass = new sendSms();
		$dep_date = date("Y-m-d");
		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('d-m-Y h:i:s A', $time_now);

		$t_rec = $_POST['t_rec'];
		$trans_id = $_POST['trans_id'];
		for ($i = 1; $i <= $t_rec; $i++) {
			if ($_POST['deposition_date' . $i] != '') {
				$deposition_date = $_POST['deposition_date' . $i];
			}
			if ($_POST['chq_date' . $i] != '') {
				$chq_date = $_POST['chq_date' . $i];
			}
			if ($_POST['dispatch_date' . $i] != '') {
				$dispatch_date = $_POST['dispatch_date' . $i];
			}
			$sql_query = mysqli_query("SELECT * FROM daily_chequepickup_m WHERE trans_id='" . $trans_id . "' AND t_rec='" . $i . "'");
			if (mysqli_num_rows($sql_query) > 0) {
				$sql_chq = "UPDATE `daily_chequepickup_m` SET `client_code`='" . $_POST['c_code' . $i] . "', `rec_no`='" . $_POST['rec_no' . $i] . "', `deposition_date`='" . $deposition_date . "', `no_cheque`='" . $_POST['no_cheque' . $i] . "', `chq_number`='" . $_POST['chq_number' . $i] . "', `chq_date`='" . $_POST['chq_date' . $i] . "', `cheque_amt`='" . $_POST['cheque_amt' . $i] . "', `deposit_bank`='" . $_POST['deposit_bank' . $i] . "', `account_no`='" . $_POST['account_no' . $i] . "', `dispatch_date`='" . $_POST['dispatch_date' . $i] . "', `courier_desc`='" . $_POST['courier_desc' . $i] . "', `distance_cust_bank`='" . $_POST['distance_cust_bank' . $i] . "', `courier_amount`='" . $_POST['courier_amount' . $i] . "', `courier_name`='" . $_POST['courier_name' . $i] . "', `pod_no`='" . $_POST['pod_no' . $i] . "', `courier_status`='" . $_POST['courier_status' . $i] . "', `scan_copy`='" . $_POST['scan_copy' . $i] . "', `remarks`='" . $_POST['remarks' . $i] . "'  WHERE `trans_id`='" . $trans_id . "' AND  `t_rec`='" . $i . "'";
			} else {

				$sql_chq = "INSERT INTO `daily_chequepickup_m` (`trans_id`, `t_rec`, `client_code`, `rec_no`, `deposition_date`, `no_cheque`, `chq_number`, `chq_date`, `cheque_amt`, `deposit_bank`, `account_no`, `dispatch_date`, `courier_desc`, `distance_cust_bank`, `courier_amount`, `courier_name`, `pod_no`, `courier_status`, `scan_copy`, `remarks`, `updated_by`, `update_date`, `status`) VALUES ('" . $trans_id . "', '" . $i . "', '" . $_POST['c_code' . $i] . "', '" . $_POST['rec_no' . $i] . "',  '" . $deposition_date . "', '" . $_POST['no_cheque' . $i] . "', '" . $_POST['chq_number' . $i] . "', '" . $_POST['chq_date' . $i] . "', '" . $_POST['cheque_amt' . $i] . "',  '" . $_POST['deposit_bank' . $i] . "', '" . $_POST['account_no' . $i] . "', '" . $_POST['dispatch_date' . $i] . "', '" . $_POST['courier_desc' . $i] . "', '" . $_POST['distance_cust_bank' . $i] . "',  '" . $_POST['courier_amount' . $i] . "',  '" . $_POST['courier_name' . $i] . "',  '" . $_POST['pod_no' . $i] . "', '" . $_POST['courier_status' . $i] . "', '" . $_POST['scan_copy' . $i] . "', '" . $_POST['remarks' . $i] . "' , '" . $user . "', '" . $time . "', 'Y')";
			}

			$query_cq = mysqli_query($sql_chq);



			//SMS Part
			$sql_daily_change = mysqli_query("SELECT a.contact_person, a.cust_name, a.pickup_type, a.pickup_amount, a.client_call_center_number, a.client_failure_notice_mobile_number, a.client_name, a.shop_mobile FROM daily_trans_m AS a JOIN radiant_ce AS b ON a.ce_id=b.ce_id  WHERE a.trans_id='" . $trans_id . "' AND a.status='Y' AND b.status='Y'");
			$res_daily_change = mysqli_fetch_assoc($sql_daily_change);
			$shop_mobile = $res_daily_change['shop_mobile'];
			$client_fail_mobile = $res_daily_change['client_failure_notice_mobile_number'];
			$call_center_no = $res_daily_change['client_call_center_number'];
			$ce_id = $res_daily_change['contact_person'];
			$cust_namess = $res_daily_change['cust_name'];

			if ($_POST['reason_for_nill'] == '') {

				$sql_sms_logs = mysqli_query("INSERT INTO sms_log (`sent_type`, `user_id`, `sent_date`, `sent_time`, `no_sms`, `status`) VALUES ('Dynamic Cheque Pickup - Success', '" . $user . "',  '" . date('Y-m-d') . "', '" . $time . "', '1', 'Y')");
				$sent_id = mysqli_insert_id();

				//Success
				//CE Customer
				if ($cust_namess != 'Polaris') // polaris sms sent condation 
				{
					$smsmessage = "Dear: " . $res_daily_change['contact_person'] . ", Received with thanks by mode of " . $res_daily_change['pickup_type'] . ", Recipt No." . $_POST['rec_no1'] . ", amount: " . $res_daily_change['pickup_amount'] . ". If any queries Please call us " . $call_center_no;




					if ($shop_mobile != "") {
						$cmobile1 = $shop_mobile;
						$mclass->sendSmsToUser($smsmessage, $shop_mobile, "");
						$no_sms++;
					}
				} // condation end				

				$sstatus = "Sent";
				if ($cmobile1 == "") $sstatus = "Not Sent";
				$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Customer')");
			} else {

				$sql_sms_logs = mysqli_query("INSERT INTO sms_log (`sent_type`, `user_id`, `sent_date`, `sent_time`, `no_sms`, `status`) VALUES ('Dynamic Cheque Pickup - Failure Notice', '" . $user . "',  '" . date('Y-m-d') . "', '" . $time . "', '2', 'Y')");
				$sent_id = mysqli_insert_id();
				//Fail
				//Customer
				if ($cust_namess != 'Polaris') // polaris sms sent condation 
				{
					$smsmessage = "Dear: " . $res_daily_change['contact_person'] . ", Greeting from " . $res_daily_change['client_name'] . ", We can't collect your " . $res_daily_change['pickup_type'] . "  amount: " . $res_daily_change['pickup_amount'] . " for the reason " . $_POST['reason_for_nill'] . ". We willreschedule shortly. If any queries Please call us " . $call_center_no;

					if ($shop_mobile != "") {
						$cmobile1 = $shop_mobile;
						$mclass->sendSmsToUser($smsmessage, $shop_mobile, "");
						$no_sms++;
					}
				} // polieas condation end

				$sstatus = "Sent";
				if ($cmobile1 == "") $sstatus = "Not Sent";

				$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Customer')");



				//Fail
				//Client
				if ($cust_namess != 'Polaris') // polaris sms sent condation 
				{
					$smsmessage = "Alert: Your " . $res_daily_change['contact_person'] . " & " . $shop_mobile . ", Collection unsuccessful for the reson " . $_POST['reason_for_nill'] . ". Confirm for rescheduling";
					if ($client_fail_mobile != "") {
						$cmobile1 = $client_fail_mobile;
						$mclass->sendSmsToUser($smsmessage, $client_fail_mobile, "");
						$no_sms++;
					}
				} //comdation end

				$sstatus = "Sent";
				if ($cmobile1 == "") $sstatus = "Not Sent";
				$qt1 = mysqli_query("insert into sms_trans_m (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status,sms_types) values('" . $sent_id . "','" . $ce_id . "','" . $cmobile1 . "','" . $cmobile2 . "','" . mysqli_real_escape_string($smsmessage) . "','" . $time . "','" . $sstatus . "','', 'Client')");
			}
		}
		if ($query_cq == 1) {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_1");
		} else {
			// mysqli_close($conn);
			header("Location:../?pid=$pid&nav=2_2");
		}
	}




	//Daily Upload Temp
	else if ($pid == 'dupload') {
		$target_path = "../Excel/";
		$entry_date = date("Y-m-d");

		if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['error'] === UPLOAD_ERR_OK) {
			$tmpFilePath = $_FILES['uploadfile']['tmp_name'];
			$filename = $_FILES['uploadfile']['name'];

			// Check if the temporary file exists and is readable
			if (file_exists($tmpFilePath) || is_readable($tmpFilePath)) {

				$spreadsheet = IOFactory::load($tmpFilePath);
				$sheet = $spreadsheet->getActiveSheet();

				$data = $sheet->toArray();

				$base1 = basename($filename);
				$exts = explode(".", $base1);
				$target = $exts[0];
				$target = $target . "$dreg_id." . $exts[1];
				$target_path = $target_path . $target;
				$region_name = $_POST['region'];

				$dfd = date("dd-mm-yyyy");
				$ddate = date("ymd");
				$file = move_uploaded_file($tmpFilePath, $target_path);

				$qu1 = mysqli_query($writeConnection, "DELETE FROM tdaily_trans WHERE region='" . $region_name . "'");
				$noTrans = 1;

				//Last trans count
				$transNoQuery = mysqli_query($writeConnection, "SELECT COUNT(trans_id) AS trans_count FROM daily_trans where pickup_date = '" . $entry_date . "'");
				$transCount = mysqli_fetch_array($transNoQuery)['trans_count'];

				//loop
				foreach ($data as $row) {
					if (trim($row[0]) != "Region" && trim($row[6]) != "" && trim($row[0]) != "Location") {
						$sql9 = "SELECT cd.cust_name, cl.client_name,sd.service_type,sd.shop_name,lm.location
			FROM shop_details as sd
			INNER JOIN cust_details as cd ON sd.cust_id = cd.cust_id 
			INNER JOIN client_details as cl ON cd.client_id = cl.client_id
			INNER JOIN location_master as lm ON lm.loc_id = sd.location
			WHERE sd.shop_id='" . $row[3] . "' 
			and sd.point_type='Active' AND sd.status='Y' AND cd.status='Y' AND cl.status='Y' AND lm.status='Y'";
						$qu9 = mysqli_query($writeConnection, $sql9);
						$r9 = mysqli_fetch_array($qu9);
						$location = $r9['location'];
						$cust_name = $r9['cust_name'];
						$client_name = $r9['client_name'];
						$shop_name = $r9['shop_name'];
						$service_type = $r9['service_type'];

						if ($service_type == "Cash Pickup" || $service_type == "Cash Delivery" || $service_type == "DD Delivery" || $service_type == "Both" || $service_type == "Cheque Pickup") {
							$type = ($service_type == "Cash Pickup" || $service_type == "Both" || $service_type == "Cheque Pickup") ? "Pickup" : "Delivery";
							if ($transCount == 0) $transCount++;
							$transNo = sprintf('%05d.%s', $transCount, $ddate);

							$values[] = " ('" . $transNo . "','" . $region_name . "', '" . $location . "', '" . $client_name . "', '" . $cust_name . "', '" . mysqli_real_escape_string($writeConnection, trim($row[3])) . "', '" . $shop_name . "', '" . mysqli_real_escape_string($writeConnection, trim($row[5])) . "', '" . $type . "', '', '" . $user . "', '" . $entry_date . "', 'Y')";
							$transCount++;
							$noTrans++;
						}
					}
				}
				$exeSql = "INSERT INTO tdaily_trans (`trans_no`, `region`, `location`, `client_name`, `cust_name`, `pickup_code`, `pickup_name`, `pickup_amount`, `type`, `client_code`, `update_by`, `pickup_date`, `status`) VALUES " . implode(',', $values);
				// echo $exeSql;die;

				$sqlTdt = mysqli_query($writeConnection,$exeSql);

				$noTrans--;

				header("Location:../?pid=$pid&nav=2_1&region=$region_name&total=$noTrans&con=1");
			} else {
				header("Location:../?pid=$pid&nav=2_2");
			}

			header("Location:../?pid=$pid&nav=2_1&region=$region_name&total=$noTrans&con=1");
		} else {
			throw new Exception("No file uploaded or there was an upload error.");
		}
	}

	//Daily Upload
	elseif ($pid == "dupload1") {


		$mclass = new sendSms();
		$entry_date = date("Y-m-d");

		$remarks_type = array('Inactive' => 'Inactive', 'Ready' => 'Pickup On Hold');

		$no_sms = 0;
		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);

		//Request Transactions
		$region_id = $_POST['region'];
		$sqls1 = "select * from region_master where region_id='" . $region_id . "' and status='Y'";
		$qus1 = mysqli_query($writeConnection, $sqls1);
		while ($rs1 = mysqli_fetch_array($qus1)) {
			$tregion = $rs1['region_name'];
		}
		$msg_type = $_POST['msg_type'];
		$sec_mobile = $_POST['sec_mobile'];
		$zero_value = $_POST['zero_value'];
		$conform = $_POST['conform'];
		$conforms = "";
		$count = count($conform);
		if ($count > 0) {
			$sqls = "insert into sms_log (sent_type,user_id,sent_date,sent_time,no_sms,status) values ('Daily Transactions-Request','" . $user . "','" . $entry_date . "','" . $time . "',0,'Y')";
			$qus = mysqli_query($writeConnection, $sqls);
			$sent_id = mysqli_insert_id($writeConnection);
		} 
		for ($i = 0; $i < $count; $i++) {
			//Collect States
			if ($conform[$i] !== "on") {
				//if($conforms!="") $conforms=$conforms.",".$conform[$i]; else $conforms=$conform[$i];

				$sql1 = "select * from tdaily_trans where trans_id='" . $conform[$i] . "' and status='Y'";
				$qu1 = mysqli_query($writeConnection, $sql1);
				while ($r1 = mysqli_fetch_array($qu1)) {
					$pri_ce = "";
					$sec_ce = "";
					$add_ce = "";
					$sql2 = "select * from shop_cemap where shop_id='" . $r1['pickup_code'] . "' and status='Y'";
					$qu2 = mysqli_query($writeConnection, $sql2);
					while ($r2 = mysqli_fetch_array($qu2)) {
						$pri_ce = $r2['pri_ce'];
						$sec_ce = $r2['sec_ce'];
						$add_ce = $r2['add_ce'];
					}

					//Get Primary CE Mobile
					$pmobile1 = "";
					$pmobile2 = "";
					$sql3 = "select * from radiant_ce where ce_id='" . $pri_ce . "' and status='Y'";
					$qu3 = mysqli_query($writeConnection, $sql3);
					while ($r3 = mysqli_fetch_array($qu3)) {
						$pname = $r3['ce_name'];
						$pmobile1 = $r3['mobile1'];
						$pmobile2 = $r3['mobile2'];
					}

					$smobile1 = "";
					$smobile2 = "";
					//Get secondary CE Mobile
					$sql3 = "select * from radiant_ce where ce_id='" . $sec_ce . "' and status='Y'";
					$qu3 = mysqli_query($readConnection, $sql3);
					while ($r3 = mysqli_fetch_array($qu3)) {
						$sname = $r3['ce_name'];
						$smobile1 = $r3['mobile1'];
						$smobile2 = $r3['mobile2'];
					}

					$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
					$time = date('h:i:s A', $time_now);
					$from1 = date("d-M-Y");
					$from = $from1 . ", " . $time;

					//Trans Check & Insert Start

					if ($r1['client_code'] == '') {
						$condition = " and pickup_amount='" . $r1['pickup_amount'] . "'";
					} else {
						$condition = " and client_code!=''";
					}
					//and pickup_amount='".$r1['pickup_amount']."'

					$sqlop = "select pickup_code from daily_trans where pickup_code='" . $r1['pickup_code'] . "' and type='" . $r1['type'] . "' " . $condition . "  and pickup_date='" . $entry_date . "' and status='Y'";
					$quop = mysqli_query($writeConnection, $sqlop);
					$nop = mysqli_num_rows($quop);
					if ($nop == 0) {

						$ddate = date("ymd");

						$total_rec = 1;


						$total_pickup_amt = 0;
						$client_code = '';

						$sql_multi_cc = mysqli_query($writeConnection, "SELECT trans_id,client_code,pickup_amount FROM tdaily_trans WHERE pickup_code='" . $r1['pickup_code'] . "' AND pickup_date='" . $entry_date . "' AND client_code!='' AND  status='Y' ORDER BY trans_id ASC");
						$row_multi_cc = mysqli_num_rows($sql_multi_cc);
						if ($row_multi_cc > 1) {
							while ($res_multi_cc = mysqli_fetch_assoc($sql_multi_cc)) {
								if (in_array($res_multi_cc['trans_id'], $conform)) {
									$client_code .= $res_multi_cc['client_code'] . '-' . $res_multi_cc['pickup_amount'] . ',';
									$total_pickup_amt += $res_multi_cc['pickup_amount'];
								}
							}
							$client_code = substr($client_code, 0, -1);
						} else {
							$client_code = 	$r1['client_code'];
							$total_pickup_amt = $r1['pickup_amount'];
						}

						$sql_lost = mysqli_query($writeConnection, "SELECT point_type FROM shop_details WHERE shop_id='" . $r1['pickup_code'] . "'");
						$res_lost = mysqli_fetch_assoc($sql_lost);

						$sql2_uss = "select pri_ce from shop_cemap where shop_id='" . $r1['pickup_code'] . "' and status='Y'";
						$qu2_uss = mysqli_query($writeConnection, $sql2_uss);
						$r2_uss = mysqli_fetch_array($qu2_uss);
						$row_uss = mysqli_num_rows($qu2_uss);
						$pri_ce_uss = $r2_uss['pri_ce'];



						if ($res_lost['point_type'] != 'Lost' && $res_lost['point_type'] != 'Inactive' && $pri_ce_uss != '') { // raman 2019-07-29
							$sql4 = "INSERT INTO daily_trans (trans_no,region,location,client_name,cust_name,pickup_code,pickup_name,pickup_amount,type,client_code,staff_id,mobile1,mobile2,sent_by, sms_sent,pickup_date,status) 
SELECT (CASE 
WHEN (ceil(count(trans_id)) >= '1' && ceil(count(trans_id)) < '9' ) 
THEN concat('0000',count(trans_id)+1,'." . $ddate . "') 
WHEN (ceil(count(trans_id)) >= '9' && ceil(count(trans_id)) < '99')
THEN concat('000',count(trans_id)+1,'." . $ddate . "') 
WHEN (ceil(count(trans_id)) >= '99' && ceil(count(trans_id)) < '999') 
THEN concat('00',count(trans_id)+1,'." . $ddate . "') 
WHEN (ceil(count(trans_id)) >= '999' && ceil(count(trans_id)) < '9999' ) 
THEN concat('0',count(trans_id)+1,'." . $ddate . "')
WHEN (ceil(count(trans_id)) >= '9999' && ceil(count(trans_id)) < '99999' )
THEN concat(count(trans_id)+1,'.','" . $ddate . "')
WHEN (ceil(count(trans_id)) = 0  )
THEN concat('00001','.','" . $ddate . "')
END),'" . $r1['region'] . "','" . $r1['location'] . "','" . $r1['client_name'] . "','" . $r1['cust_name'] . "','" . $r1['pickup_code'] . "','" . $r1['pickup_name'] . "'," . $total_pickup_amt . ",'" . $r1['type'] . "','" . $client_code . "', '" . $pri_ce . "','" . $pmobile1 . "','" . $pmobile2 . "','" . $user . "','" . $from . "','" . $entry_date . "','Y'
from daily_trans where pickup_date='" . $entry_date . "' ";


							$qu4 = mysqli_query($writeConnection, $sql4);
						}

						$sms_pass = 0;
						if (($zero_value == "ok" && $r1['pickup_amount'] == 0) || ($r1['pickup_amount'] > 0))
							$sms_pass = 1;



						if ($msg_type == 1) {
							$cmobile1 = "";
							$cmobile2 = "";
							$smsmessage = "";

							//Sent SMS to Selected CEs
							$pri_ce1 = substr($pri_ce, 3, 7);


							$SmsTransNo = !empty($drec_id) ? $drec_id : '-';
							$SmsLocation = !empty($r1['location']) ? $r1['location'] : '-';
							$SmsPointCode = !empty($r1['pickup_code']) ? $r1['pickup_code'] : '-';
							$SmsShopName = !empty($r1['pickup_name']) ? substr($r1['pickup_name'], 0, 28) : '-';
							$SmsAmount = !empty($r1['pickup_amount']) ? $r1['pickup_amount'] : '-';
							$SmsCeName = !empty($pname) ? $pname : '-';
							$SmsCeId = !empty($pri_ce1) ? $pri_ce1 : '-';

							$smsmessage = 'Request, Trans ID: ' . trim($SmsTransNo) . ', Location: ' . trim($SmsLocation) . ', Shop Code: ' . trim($SmsPointCode) . ', Shop Name: ' . trim($SmsShopName) . ', Amount: ' . trim($SmsAmount) . ', CE Name: ' . trim($SmsCeName) . ', CE ID: ' . trim($SmsCeId);

							//$smsmessage="Request, Trans ID: ".$drec_id.", Location: ".$r1['location'].", Shop Code: ".$r1['pickup_code'].", Shop Name: ".$r1['pickup_name'].", ".$r1['type']." Amount: ".$r1['pickup_amount'].", CE Name: ".$pname.", CE ID: ".$pri_ce1;

							//echo $smsmessage;
							//Primary CE First Number 
							//$offline=="No"&&
							if ($sms_pass == 1 && $pmobile1 != "") {
								$cmobile1 = $pmobile1;
								$mclass->sendSmsToUser($smsmessage, $pmobile1, "");
								$no_sms++;
							}
							//Primary CE Second Number 
							//$offline=="No"&&
							if ($sms_pass == 1 && $pmobile2 != "" && $sec_mobile == "ok") {
								$cmobile2 = $pmobile2;
								$mclass->sendSmsToUser($smsmessage, $pmobile2, "");
								$no_sms++;
							}
							//echo 'Mob:'.$cmobile1;

							$sstatus = "Sent";
							if ($cmobile1 == "" && $cmobile2 == "") $sstatus = "Not Sent";


							$qt1 = mysqli_query($writeConnection, "insert into sms_trans (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status) values(" . $sent_id . ",'" . $pri_ce . "','" . $cmobile1 . "','" . $cmobile2 . "','" . $smsmessage . "','" . $time . "','" . $sstatus . "','')");
						} elseif ($msg_type == 2) {
							//Primary CE SMS
							$cmobile1 = "";
							$cmobile2 = "";
							$smsmessage = "";
							//Sent SMS to Selected CEs
							$pri_ce1 = substr($pri_ce, 3, 7);


							$SmsTransNo = !empty($drec_id) ? $drec_id : '-';
							$SmsLocation = !empty($r1['location']) ? $r1['location'] : '-';
							$SmsPointCode = !empty($r1['pickup_code']) ? $r1['pickup_code'] : '-';
							$SmsShopName = !empty($r1['pickup_name']) ? substr($r1['pickup_name'], 0, 28) : '-';
							$SmsAmount = !empty($r1['pickup_amount']) ? $r1['pickup_amount'] : '-';
							$SmsCeName = !empty($pname) ? $pname : '-';
							$SmsCeId = !empty($pri_ce1) ? $pri_ce1 : '-';

							$smsmessage = 'Request, Trans ID: ' . trim($SmsTransNo) . ', Location: ' . trim($SmsLocation) . ', Shop Code: ' . trim($SmsPointCode) . ', Shop Name: ' . trim($SmsShopName) . ', Amount: ' . trim($SmsAmount) . ', CE Name: ' . trim($SmsCeName) . ', CE ID: ' . trim($SmsCeId);





							//$smsmessage="Request, Trans ID: ".$drec_id.", Location: ".$r1['location'].", Shop Code: ".$r1['pickup_code'].", Shop Name: ".$r1['pickup_name'].", ".$r1['type']." Amount: ".$r1['pickup_amount'].", CE Name: ".$pname.", CE ID: ".$pri_ce1;

							//echo $smsmessage;
							//Primary CE First Number 
							//$offline=="No"&&
							if ($sms_pass == 1 && $pmobile1 != "") {
								$cmobile1 = $pmobile1;
								$mclass->sendSmsToUser($smsmessage, $pmobile1, "");
								$no_sms++;
							}
							//Primary CE Second Number 
							//$offline=="No"&&
							if ($sms_pass == 1 && $pmobile2 != "" && $sec_mobile == "ok") {
								$cmobile2 = $pmobile2;
								$mclass->sendSmsToUser($smsmessage, $pmobile2, "");
								$no_sms++;
							}

							$sstatus = "Sent";
							if ($cmobile1 == "" && $cmobile2 == "") $sstatus = "Not Sent";

							$qt1 = mysqli_query($writeConnection, "insert into sms_trans (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status) values(" . $sent_id . ",'" . $pri_ce . "','" . $cmobile1 . "','" . $cmobile2 . "','" . $smsmessage . "','" . $time . "','" . $sstatus . "','')");

							//Secondary CE SMS
							$cmobile1 = "";
							$cmobile2 = "";
							$smsmessage = "";
							$sec_ce1 = substr($sec_ce, 3, 7);


							$SmsTransNo = !empty($drec_id) ? $drec_id : '-';
							$SmsLocation = !empty($r1['location']) ? $r1['location'] : '-';
							$SmsPointCode = !empty($r1['pickup_code']) ? $r1['pickup_code'] : '-';
							$SmsShopName = !empty($r1['pickup_name']) ? substr($r1['pickup_name'], 0, 28) : '-';
							$SmsAmount = !empty($r1['pickup_amount']) ? $r1['pickup_amount'] : '-';
							$SmsCeName = !empty($sname) ? $sname : '-';
							$SmsCeId = !empty($sec_ce1) ? $sec_ce1 : '-';

							$smsmessage = 'Request, Trans ID: ' . trim($SmsTransNo) . ', Location: ' . trim($SmsLocation) . ', Shop Code: ' . trim($SmsPointCode) . ', Shop Name: ' . trim($SmsShopName) . ', Amount: ' . trim($SmsAmount) . ', CE Name: ' . trim($SmsCeName) . ', CE ID: ' . trim($SmsCeId);

							//$smsmessage="Request, Trans ID: ".$drec_id.", Location: ".$r1['location'].", Shop Code: ".$r1['pickup_code'].", Shop Name: ".$r1['pickup_name'].", ".$r1['type']." Amount: ".$r1['pickup_amount'].", CE Name: ".$sname.", CE ID: ".$sec_ce1;


							//Secondary CE First Number 
							if ($offline == "No" && $sms_pass == 1 && $smobile1 != "") {
								$cmobile1 = $smobile1;
								$mclass->sendSmsToUser($smsmessage, $smobile1, "");
								$no_sms++;
							}
							//Secondary CE Second Number 
							if ($offline == "No" && $sms_pass == 1 && $smobile2 != "" && $sec_mobile == "ok") {
								$cmobile2 = $smobile2;
								$mclass->sendSmsToUser($smsmessage, $smobile2, "");
								$no_sms++;
							}

							$sstatus = "Sent";
							if ($cmobile1 == "" && $cmobile2 == "") $sstatus = "Not Sent";
							$qt1 = mysqli_query($writeConnection, "insert into sms_trans (sent_id,sent_ceid,sent_mobile1,sent_mobile2,sent_content,sms_time,sent_status,del_status) values(" . $sent_id . ",'" . $sec_ce . "','" . $cmobile1 . "','" . $cmobile2 . "','" . $smsmessage . "','" . $time . "','" . $sstatus . "','')");
						}
					}

					//Trans Check & Insert End
					//Installment No
					if ($ins_no == 20) {
						$qus = mysqli_query($writeConnection, "update sms_log set no_sms=" . $no_sms . " where sent_id='" . $sent_id . "' ");
						$ins_no = 1;
					} else {
						$ins_no++;
					}
				}
			}
		}
		if ($no_sms > 0) {
			$qus = mysqli_query($writeConnection, "update sms_log set no_sms=" . $no_sms . " where sent_id='" . $sent_id . "' ");
		}
		$rcount = $count;


		//Beat Transactions Via SMS

		$entry_date = date("Y-m-d");
		$no_sms = 0;
		$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
		$time = date('h:i:s A', $time_now);

		//Insert Main SMS Log
		//Beat Send SMS Hide as per Sangeetha Instruction as on 26-09-2011

		//Get Previous Transactions Total
		$sqlt = "select * from daily_trans where pickup_date='" . $entry_date . "'";
		$qut = mysqli_query($readConnection, $sqlt);
		$nt = mysqli_num_rows($qut);

		$dfd = date("dd-mm-yyyy");

		$ddate = date("ymd");

		$no_sms = 0;

		$location = $_POST['location'];

		$locations = "";
		$count = ($location != "") ? count($location) : 0;

		$ce_count = 0;
		$bcount = 0;
		$loc = array();
		for ($i = 0; $i < $count; $i++) {
			if ($location[$i] !== "on") {
				array_push($loc, $location[$i]);
			}
		}


		//Location Looping
		foreach ($loc as $value) {

			$sqls = "SELECT * FROM shop_details INNER JOIN shop_cemap ON shop_details.shop_id = shop_cemap.shop_id WHERE location='" . $value . "' AND pickup_type='Beat' and shop_details.point_type!='Lost' AND shop_details.status='Y' AND shop_cemap.status='Y'";

			$qus = mysqli_query($writeConnection, $sqls);

			while ($rs = mysqli_fetch_array($qus)) {


				$sqlp = "select * from daily_trans where pickup_code='" . $rs['shop_id'] . "' and pickup_date='" . $entry_date . "' and type='Pickup' and status='Y'";

				$qup = mysqli_query($writeConnection, $sqlp);
				$np = mysqli_num_rows($qup);


				//Beat Transaction Not Sent then
				if ($np == 0) {

					//Insert daily Tranasction Details
					$pri_ce = "";
					$sec_ce = "";
					$cemap_de = 0;
					$sql2 = "select * from shop_cemap where shop_id='" . $rs['shop_id'] . "' and status='Y' and pri_ce!=''";

					$qu2 = mysqli_query($writeConnection, $sql2);
					$rowsss = mysqli_num_rows($qu2);
					$cemap_de = $rowsss;

					while ($r2 = mysqli_fetch_array($qu2)) {
						$pri_ce = $r2['pri_ce'];
						$sec_ce = $r2['sec_ce'];
					}

					//Get Primary CE Mobile
					$pmobile1 = "";
					$pmobile2 = "";
					$sql3 = "select * from radiant_ce where ce_id='" . $pri_ce . "' and status='Y'";
					$qu3 = mysqli_query($writeConnection, $sql3);
					while ($r3 = mysqli_fetch_array($qu3)) {
						$pname = $r3['ce_name'];
						$pmobile1 = $r3['mobile1'];
						$pmobile2 = $r3['mobile2'];
					}



					$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
					$time = date('h:i:s A', $time_now);
					$from1 = date("d-M-Y");
					$from = $from1 . ", " . $time;

					$location = "";
					$client_name = "";
					$cust_name = "";

					$sql8 = "select * from location_master where loc_id='" . $rs['location'] . "' ";
					$qu8 = mysqli_query($writeConnection, $sql8);
					while ($r8 = mysqli_fetch_array($qu8)) {
						$location = $r8['location'];
					}

					$sql9 = "SELECT * FROM shop_details INNER JOIN cust_details ON shop_details.cust_id = cust_details.cust_id INNER JOIN client_details ON cust_details.client_id = client_details.client_id WHERE shop_id='" . $rs['shop_id'] . "' and shop_details.point_type!='Lost' AND shop_details.status='Y'";
					$qu9 = mysqli_query($writeConnection, $sql9);
					while ($r9 = mysqli_fetch_array($qu9)) {
						$cust_name = $r9['cust_name'];
						$client_name = $r9['client_name'];
					}


					if ($rs['service_type'] == "Cash Pickup" || $rs['service_type'] == "Cash Delivery" || $rs['service_type'] == "Both" || $rs['service_type'] == "Cheque Pickup" || $rs['service_type'] == "Evening Pickup") {
						if ($rs['service_type'] == "Cash Pickup")
							$type = "Pickup";
						elseif ($rs['service_type'] == "Cash Delivery")
							$type = "Delivery";
						elseif ($rs['service_type'] == "Both")
							$type = "Pickup";
						elseif ($rs['service_type'] == "Cheque Pickup")
							$type = "Pickup";
						elseif ($rs['service_type'] == "Evening Pickup")
							$type = "Pickup";


						$total_rec = 1;

						if ($rs['service_type'] == 'Evening Pickup') {
							$trans_no .= '-E';
							$even_date = $ddate . '-E';
						} else {
							$even_date = $ddate;
						}
						echo $trans_no;


						$sql55 = "select pickup_code from daily_trans where pickup_code='" . $rs['shop_id'] . "' and pickup_date = '" . $entry_date . "' and status='Y'";
						$qu55 = mysqli_query($writeConnection, $sql55);
						if (mysqli_num_rows($qu55) == 0) {
							if ($cemap_de > 0) {


								$sql4 = "INSERT INTO daily_trans (trans_no,region,location,client_name,cust_name,pickup_code,pickup_name,pickup_amount,type,client_code,staff_id,mobile1,mobile2,sent_by, sms_sent,pickup_date,status) 
SELECT (CASE 
WHEN (ceil(count(trans_id)) >= '1' && ceil(count(trans_id)) < '9' ) 
THEN concat('0000',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '9' && ceil(count(trans_id)) < '99')
THEN concat('000',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '99' && ceil(count(trans_id)) < '999') 
THEN concat('00',count(trans_id)+1,'." . $even_date . "') 
WHEN (ceil(count(trans_id)) >= '999' && ceil(count(trans_id)) < '9999' ) 
THEN concat('0',count(trans_id)+1,'." . $even_date . "')
WHEN (ceil(count(trans_id)) >= '9999' && ceil(count(trans_id)) < '99999' )
THEN concat(count(trans_id)+1,'.','" . $even_date . "')
WHEN (ceil(count(trans_id)) = 0  )
THEN concat('00001','.','" . $even_date . "')
END),'" . $tregion . "','" . $location . "','" . $client_name . "','" . $cust_name . "','" . $rs['shop_id'] . "','" . $rs['shop_name'] . "',0,'" . $type . "', '', '" . $pri_ce . "','" . $pmobile1 . "','" . $pmobile2 . "','" . $user . "','" . $from . "','" . $entry_date . "','Y'
from daily_trans where pickup_date='" . $entry_date . "' ";



								$qu4 = mysqli_query($writeConnection, $sql4);

								if ($rs['point_type'] == 'Inactive' || $rs['point_type'] == 'Ready') {
									$trans_id_insert = mysqli_insert_id();
									if ($rs['service_type'] == 'Cash Pickup' || $rs['service_type'] == 'Both') {
									}
									if ($rs['service_type'] == 'Cheque Pickup') {
									}
									// mysqli_query($quer);
								}


								$bcount++;
							}
						}

						$time_now = mktime(date('h') + 5, date('i') + 30, date('s'));
						$time = date('h:i:s A', $time_now);
					}
				}
			}
		}


		// // mysqli_close($conn);
		header("Location:../?pid=dupload&nav=2_3&rtotal=$rcount&btotal=$bcount");
	}
}




if ($pid == 'Daily_upload') {

	//echo "helllo"; exit;
	//print_r($_POST);die;
	$dcv_total = 0;
	$sel_dcv = mysqli_query($readConnection, "Select service_id,vehicle_id,crew_members from cashvan_services where service_type='DCV' and status='Y' and vehicle_id!='0' and vehicle_id!='' ");
	if (mysqli_num_rows($sel_dcv) > 0) {
		while ($dcv_rw = mysqli_fetch_assoc($sel_dcv)) {
			$vans = explode(",", $dcv_rw['vehicle_id']);
			$crew = explode("/", $dcv_rw['crew_members']);
			foreach ($vans as $key => $vanid) {
				$chk_dcv = mysqli_query($readConnection, "Select sid from vantrans_details where service_type='DCV' and trans_date='" . date('Y-m-d') . "' and service_id = '" . $dcv_rw['service_id'] . "' and vehicle_no='" . $vanid . "' and status='Y' ");
				if (mysqli_num_rows($chk_dcv) ==0) {
					$ins_dcv = "insert into vantrans_details(service_id,trans_date,vehicle_no,crew_members,service_type,status,created_by,created_date) values('" . $dcv_rw['service_id'] . "','" . date('Y-m-d') . "','" . $vanid . "','" . $crew[$key] . "','DCV','Y','" . $user_name . "',now())";
					$dcv_exc = mysqli_query($writeConnection, $ins_dcv);
					$dcv_id = mysqli_insert_id($writeConnection);
					$sel_dcv_crw = mysqli_query($writeConnection, "Select desig,name from dcv_crew where service_id='" . $dcv_rw['service_id'] . "' and vehicle_id='" . $vanid . "' and status='Y'");
					while ($crw_rw = mysqli_fetch_assoc($sel_dcv_crw)) {
						$ins_dcv_crew = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $dcv_id . "','" . date('Y-m-d') . "','" . $dcv_rw['service_id'] . "','" . $vanid . "','" . $crw_rw['desig'] . "','" . $crw_rw['name'] . "','Y','" . $user_name . "',now())";
						$dcv_crw_exc = mysqli_query($writeConnection, $ins_dcv_crew);
					}
					if ($dcv_exc) $dcv_total++;
				}
			}
		}
	}

	if (isset($_REQUEST['excel']) && $_REQUEST['excel'] == 'empty') {
		// mysqli_close($conn);
		header("Location:../?pid=Daily_upload&nav=2_1_8&dtotal=$dcv_total");
	}
	//vantrans_details

	//Trans No
	$tr_quer = "select trans_no from cashvan_request where id =(select max(id) from cashvan_request)";
	$tr_exc = mysqli_query($readConnection, $tr_quer);
	$tr_row = mysqli_fetch_object($tr_exc);
	$trans = explode(".", $tr_row->trans_no);
	$tod_dat = date('ymd');
	if ($trans[1] == date('ymd')) {
		$trans[0] = str_pad($trans[0] + 1, 5, '0', STR_PAD_LEFT);
		$trans_id_res = implode(".", $trans);
	} else {
		$trans_id_res = "00001." . date('ymd');
	}
	$totals1 = 0;
	$tr_res = $trans_id_res;
	if (isset($_POST['submit1'])) {
		foreach ($_POST['service_codes'] as $num => $vals) {

			$sel_ty = "Select service_type,service_name,cld.client_name,service_id,vehicle_id,crew_members from cashvan_services cs join client_details cld on cld.client_id = cs.client_id where cs.service_code='" . $vals . "' and cs.status='Y' and cld.status='Y' ";
			$ty_exc = mysqli_query($readConnection, $sel_ty);
			$ty_row = mysqli_fetch_assoc($ty_exc);
			if ($ty_row['service_type'] == 'DCV') {
				$sel_dcv = mysqli_query($readConnection, "Select service_id,vehicle_id,crew_members from cashvan_services where service_type='DCV' and status='Y' and vehicle_id!='0' and vehicle_id!='' and service_code='" . $vals . "' ");
				if (mysqli_num_rows($sel_dcv) > 0) {
					while ($dcv_rw = mysqli_fetch_assoc($sel_dcv)) {
						$vans = explode(",", $dcv_rw['vehicle_id']);
						$crew = explode("/", $dcv_rw['crew_members']);
						foreach ($vans as $key => $vanid) {

							$ins_dcv = "insert into vantrans_details(service_id,trans_date,vehicle_no,crew_members,service_type,status,created_by,created_date) values('" . $dcv_rw['service_id'] . "','" . date('Y-m-d') . "','" . $vanid . "','" . $crew[$key] . "','DCV','Y','" . $user_name . "',now())";
							$dcv_exc = mysqli_query($writeConnection, $ins_dcv);
							$dcv_id = mysqli_insert_id();
							$sel_dcv_crw = mysqli_query($writeConnection, "Select desig,name from dcv_crew where service_id='" . $dcv_rw['service_id'] . "' and vehicle_id='" . $vanid . "' and status='Y'");
							while ($crw_rw = mysqli_fetch_assoc($sel_dcv_crw)) {
								$ins_dcv_crew = "insert into daily_cashvan_crew(trans_id,trans_date,service_id,vehicle_id,desig,name,status,created_by,created_date) values('" . $dcv_id . "','" . date('Y-m-d') . "','" . $dcv_rw['service_id'] . "','" . $vanid . "','" . $crw_rw['desig'] . "','" . $crw_rw['name'] . "','Y','" . $user_name . "',now())";
								$dcv_crw_exc = mysqli_query($writeConnection, $ins_dcv_crew);
							}

							if ($dcv_exc) $dcv_total++;
						}
					}
				}
			} else {


				//$sel_qu = "Select id from cashvan_request where date='".date('Y-m-d')."' and service_code = '".$vals."' and status='Y' ";
				//$ex1 = mysqli_query($sel_qu);
				//echo $sel_qu;die;
				//if(mysqli_num_rows($ex1) == 0){

				$ins_sql = "insert into cashvan_request(trans_no,date,service_code,region,client_name,amount,type,status,created_by,created_date) values('" . $tr_res . "','" . date('Y-m-d') . "','" . $vals . "','" . $_POST['region'] . "','" . $ty_row['client_name'] . "','" . $_POST['amount'][$num] . "','" . $ty_row['service_type'] . "','Y','" . $per . "',now())";
				$ins_exc = mysqli_query($writeConnection, $ins_sql);
				//$sel_cvr = mysqli_query("Select sid from vantrans_details where trans_date='".date('Y-m-d')."' and status='Y' and service_type='".$ty_row['service_type']."' and  service_id='".$ty_row['service_id']."'");
				//if(mysqli_num_rows($sel_cvr) == 0){
				$ins_trans = mysqli_query($writeConnection, "insert into vantrans_details(service_id,trans_date,service_type,pickup_amount,status,created_by,created_date,update_time,update_by) values('" . $ty_row['service_id'] . "','" . date('Y-m-d') . "','" . $ty_row['service_type'] . "','" . $_POST['amount'][$num] . "','Y','" . $user_name . "',now(),'" . $user_name . "',now())");
				if ($ins_exc && $ins_trans)	$totals1++;

				$tr = explode(".", $tr_res);
				$tr_one = $tr[0];
				$tr[0] = str_pad($tr_one + 1, 5, '0', STR_PAD_LEFT);
				$tr_res = implode(".", $tr);
				$tr_one++;
				//}
				//}
			}
		}
		// mysqli_close($conn);
		header("Location:../?pid=Daily_upload&nav=2_1_5&totals=$totals1&dtotal=$dcv_total");
		exit;
	}
}




if ($pid == 'ind_loc') {

	$state_name = trim($_POST['state_name']);
	$district_name = trim($_POST['district_name']);
	$location = trim($_POST['location']);
	$cluster = trim($_POST['cluster']);
	$cihloc = trim($_POST['cihloc']);
	$pincode = trim($_POST['pin_code']);
	$tier = trim($_POST['tier']);

	$sql = "SELECT * FROM `location_master` WHERE `pincode` ='$pincode' and location ='$location'";
	$res = mysqli_query($readConnection, $sql);
	$count = mysqli_num_rows($res);
	if ($count == 0) {
		$newloc_sql = "INSERT INTO `location_master`(`country`, `state`, `district`, `location`, `pincode`, `cih_location`, `cl_bcl`,`tier`,`status`) VALUES('India','$state_name','$district_name','$location','$pincode','$cihloc','$cluster','$tier','Y')";
		$newloc_qry = mysqli_query($writeConnection, $newloc_sql);
		echo "<div  class='alert alert-success' style='padding: 7px;'>One Record Inserted</div>";
	} else {
		echo "<div class='alert alert-danger' style='padding: 7px;'>Record Already Available</div>";
	}
}


if ($pid == 'gold_pickup') {

	$invo_no = $_POST['invo_no'];
	$region_id = $_POST['region_id'];
	$location = $_POST['location'];
	$pickup_time = $_POST['pickup_time'];
	$receipt_no = $_POST['receipt_no'];
	$no_items = $_POST['no_items'];
	$jewel_descr = $_POST['jewel_descr'];
	$weight_invoice = $_POST['weight_invoice'];
	$weight_radiant = $_POST['weight_radiant'];
	$weight_equitas = $_POST['weight_equitas'];
	$total_weight = $_POST['total_weight'];
	$invoice_val = $_POST['invoice_val'];
	$loan_amt = $_POST['loan_amt'];
	$seal_tagno = $_POST['seal_tagno'];

	//del
	$del_invo_no = $_POST['del_invo_no'];
	$del_region_id = $_POST['del_region_id'];
	$del_location = $_POST['del_location'];
	$del_pickup_time = $_POST['del_pickup_time'];
	$del_receipt_no = $_POST['del_receipt_no'];
	$del_no_items = $_POST['del_no_items'];
	$del_jewel_descr = $_POST['del_jewel_descr'];

	$del_weight_invoice = $_POST['del_weight_invoice'];
	$del_weight_radiant = $_POST['del_weight_radiant'];
	$del_weight_equitas = $_POST['del_weight_equitas'];
	$del_total_weight = $_POST['del_total_weight'];
	$del_invoice_val = $_POST['del_invoice_val'];
	$del_loan_amt = $_POST['del_loan_amt'];
	$del_seal_tagno = $_POST['del_seal_tagno'];
	$cur_date = date('Y-m-d');

	// micro finance

	$del_to_micro = $_POST['del_to_micro'];
	$micro_recp_no = $_POST['micro_recp_no'];
	$micro_seltag_no = $_POST['micro_seltag_no'];
	$micro_no_items = $_POST['micro_no_items'];
	$micro_del_jewel_descr = $_POST['micro_del_jewel_descr'];
	$micro_total_weight = $_POST['micro_total_weight'];
	$micro_invoice_val = $_POST['micro_invoice_val'];
	$micro_time = $_POST['micro_time'];
	$micro_remarks = $_POST['micro_remarks'];
	$report_name = $_POST['report_name'];
	$date = date('Y-m-d', strtotime($_POST['from_date']));


	// amway god Pickup
	$cust_name = $_POST['cust_name'];
	$am_pickup_date = date('Y-m-d', strtotime($_POST['am_pickup_date']));
	$am_no_coin_pickup = $_POST['am_no_coin_pickup'];
	$am_pickup_time = $_POST['am_pickup_time'];
	$am_pickup_rec_no = $_POST['am_pickup_rec_no'];
	$am_seal_tag = $_POST['am_seal_tag'];
	$am_pickup_time = $_POST['am_pickup_time'];
	$am_pickup_person = $_POST['am_pickup_person'];
	$valut_location = $_POST['valut_location'];
	$am_del_location = $_POST['am_del_location'];
	$am_del_date = date('Y-m-d', strtotime($_POST['am_del_date']));
	$am_no_coin = $_POST['am_no_coin'];
	$am_del_time = $_POST['am_del_time'];
	$am_rec_num = $_POST['am_rec_num'];
	$am_del_person = $_POST['am_del_person'];
	$am_vault_days = $_POST['am_vault_days'];
	$am_remarks = $_POST['am_remarks'];






	if ($report_name == 'pickup') {
		if ($id == '') {

			$sql = "INSERT INTO `gold_pickup` (type,pickup_date,`invo_no`, `region_id`, `location`, `pickup_time`, `receipt_no`, `no_items`, `jewel_descr`, `weight_invoice`, `weight_radiant`, `weight_equitas`, `total_weight`, `invoice_val`, `loan_amt`, `seal_tagno`,`created_by`, `created_date`, `updated_by`, `updated_date`, `status`) VALUES ('" . $report_name . "','" . $date . "','" . $invo_no . "', '" . $region_id . "', '" . $location . "', '" . $pickup_time . "', '" . $receipt_no . "', '" . $no_items . "', '" . $jewel_descr . "', '" . $weight_invoice . "', '" . $weight_radiant . "', '" . $weight_equitas . "', '" . $total_weight . "', '" . $invoice_val . "', '" . $loan_amt . "', '" . $seal_tagno . "', '" . $user_name . "', '" . $cur_date . "', '" . $user_name . "', '" . $cur_date . "', 'Y')";
		} else {

			$sql = "update gold_pickup set pickup_date='" . $date . "',invo_no='" . $invo_no . "',region_id='" . $region_id . "',location='" . $location . "',pickup_time='" . $pickup_time . "',receipt_no='" . $receipt_no . "',no_items='" . $no_items . "',jewel_descr='" . $jewel_descr . "',weight_invoice='" . $weight_invoice . "',weight_radiant='" . $weight_radiant . "',weight_equitas='" . $weight_equitas . "',total_weight='" . $total_weight . "',invoice_val='" . $invoice_val . "',loan_amt='" . $loan_amt . "',seal_tagno='" . $seal_tagno . "',updated_by='" . $user_name . "',updated_date='" . $cur_date . "' where id='" . $id . "'";
		}
	} elseif ($report_name == 'delivery') {
		if ($id == '') {

			$sql = "INSERT INTO `gold_delivery` (type,del_date,`del_invo_no`, `region_id`, `location`, `del_pickup_time`, `del_receipt_no`, `del_no_items`, `del_jewel_descr`, `del_weight_invoice`, `del_weight_radiant`, `del_weight_equitas`, `del_total_weight`, `del_invoice_val`, `del_loan_amt`, `del_seal_tagno`, `created_by`, `created_date`, `status`) VALUES ('" . $report_name . "','" . $date . "','" . $del_invo_no . "', '" . $region_id . "', '" . $location . "', '" . $del_pickup_time . "', '" . $del_receipt_no . "', '" . $del_no_items . "', '" . $del_jewel_descr . "', '" . $del_weight_invoice . "', '" . $del_weight_radiant . "', '" . $del_weight_equitas . "', '" . $del_total_weight . "','" . $del_invoice_val . "', '" . $del_loan_amt . "', '" . $del_seal_tagno . "', '" . $user_name . "', '" . $cur_date . "','Y')";
		} else {
			$sql = "update gold_delivery set del_date='" . $date . "', 	del_invo_no='" . $del_invo_no . "',region_id='" . $region_id . "',location='" . $location . "',del_pickup_time='" . $del_pickup_time . "',del_receipt_no='" . $del_receipt_no . "',del_no_items='" . $del_no_items . "',del_jewel_descr='" . $del_jewel_descr . "',del_weight_invoice='" . $del_weight_invoice . "',del_weight_radiant='" . $del_weight_radiant . "',del_weight_equitas='" . $del_weight_equitas . "',del_total_weight='" . $del_total_weight . "',del_invoice_val='" . $del_invoice_val . "',del_loan_amt='" . $del_loan_amt . "',del_seal_tagno='" . $del_seal_tagno . "' where id='" . $id . "'";
		}
	} elseif ($report_name == 'delivery_micro') {
		if ($id == '') {

			$sql = "INSERT INTO `gold_delivery_micro` (type,del_date,`del_to_micro`, `region_id`, `location`, `micro_receipt_no`, `micro_seal_tagno`, `micro_no_items`, `micro_jewel_descr`, `micro_total_weight`, `micro_invoice_val`, `micro_pickup_time`, `remarks`, `created_by`, `created_date`, `status`) VALUES ('" . $report_name . "','" . $date . "','" . $del_to_micro . "', '" . $region_id . "', '" . $location . "', '" . $micro_recp_no . "', '" . $micro_seltag_no . "', '" . $micro_no_items . "', '" . $micro_del_jewel_descr . "', '" . $micro_total_weight . "', '" . $micro_invoice_val . "', '" . $micro_time . "', '" . $micro_remarks . "', '" . $user_name . "', '" . $cur_date . "', 'Y')";
		} else {
			$sql = "update gold_delivery_micro set del_date='" . $date . "',del_to_micro='" . $del_to_micro . "',region_id='" . $region_id . "',location='" . $location . "',micro_receipt_no='" . $micro_recp_no . "',micro_seal_tagno='" . $micro_seltag_no . "',micro_no_items='" . $micro_no_items . "',micro_jewel_descr='" . $micro_del_jewel_descr . "',micro_total_weight='" . $micro_total_weight . "',micro_invoice_val='" . $micro_invoice_val . "',micro_pickup_time='" . $micro_time . "',remarks='" . $micro_remarks . "' where id='" . $id . "'";
		}
	} elseif ($report_name == 'amway_pickup') {
		if ($id == '') {

			$sql = "INSERT INTO `amyway_gold_pickup` (amw_date,`type`, `cust_name`, `vault_location`, `del_location`, `del_date`, `del_no_of_coins`, `del_time`, `del_receipt_no`, `del_person`, `pickup_date`, `pickup_no_coins`, `pickup_time`, `pickup_receipt_no`, `seal_tag_no`, `pickup_person`, `days_keepin_valut`, `Remarks`, `created_date`, `created_by`, `status`) VALUES ('" . $date . "','" . $report_name . "', '" . $cust_name . "', '" . $valut_location . "', '" . $am_del_location . "', '" . $am_del_date . "', '" . $am_no_coin . "', '" . $am_del_time . "', '" . $am_rec_num . "', '" . $am_del_person . "', '" . $am_pickup_date . "', '" . $am_no_coin_pickup . "', '" . $am_pickup_time . "', '" . $am_pickup_rec_no . "', '" . $am_seal_tag . "', '" . $am_pickup_person . "', '" . $am_vault_days . "', '" . $am_remarks . "', '" . $cur_date . "', '" . $user_name . "', 'Y')";
		} else {
			$sql = "update amyway_gold_pickup set type='" . $report_name . "',cust_name='" . $cust_name . "',vault_location='" . $valut_location . "',del_location='" . $am_del_location . "',del_date='" . $am_del_date . "',del_no_of_coins='" . $am_no_coin . "',del_time='" . $am_del_time . "',del_receipt_no='" . $am_rec_num . "',del_person='" . $am_del_person . "',pickup_date='" . $am_pickup_date . "',pickup_no_coins='" . $am_no_coin_pickup . "',pickup_time='" . $am_pickup_time . "',pickup_receipt_no='" . $am_pickup_rec_no . "',seal_tag_no='" . $am_seal_tag . "',pickup_person='" . $am_pickup_person . "',days_keepin_valut='" . $am_vault_days . "',Remarks='" . $am_remarks . "',created_date='" . $cur_date . "',created_by='" . $user_name . "',status='Y' where id='" . $id . "'";
		}
	}

	if (!empty($report_name)) {
		$sql_q = mysqli_query($writeConnection, $sql);
	}


	if ($sql_q) {
		// mysqli_close($conn);
		header("Location:../?pid=gold_pickup&nav=2_1_1");
	} else {
		// mysqli_close($conn);
		header("Location:../?pid=gold_pickup&nav=2_2_1");
	}
}






if ($pid == 'recon_entry') {

	$deposit_id = $_POST['dep_id'];
	$depo_amount1 = $_POST['reg_amt'];
	$account_idss = $_POST['acc_id'];
	$dep_type = $_POST['dep_type'];
	$dep_slipes = $_POST['recon_slip'];
	$deposit_status = $_POST['ro_remarks'];
	$mirror_remarks = $_POST['mirror_remarks'];
	$dates = $_POST['dates'];
	$types = $_POST['types'];
	$updatedName = $_POST['updatedName'];
	$slipCheckedRemark = $_POST['slipCheckedRemark'];


	$chk_trans = mysqli_query($readConnection, "Select * from checked_transactions where status='Y' and  deposit_id='" . $deposit_id . "' ");
	if (mysqli_num_rows($chk_trans) > 0) {
		$chk_row = mysqli_fetch_assoc($chk_trans);
		//Update
		if ($updatedName != '') {
			$checkedTrans =   mysqli_query($writeConnection, "update checked_transactions  set updated_by='" . $updatedName . "' where status='Y' and  deposit_id='" . $deposit_id . "' and updated_by='' "); // raman 2020-12-17
		}



		if ($dep_slipes != '' || $mirror_remarks != '' || $mirror_remarks == '') {
			$chk = "update checked_transactions set check_amount ='" . $depo_amount1 . "', deposit_slip = '" . $dep_slipes . "', remarks_sec='" . $mirror_remarks . "',recon_user='" . $user_name . "',recon_time=now(),slip_checked_remark='" . $slipCheckedRemark . "' where check_id='" . $chk_row['check_id'] . "' and status='Y' ";

			$accTransUpdate = mysqli_query($readConnection, "Select deposit_id from accountwise_bank_recon where status='Y' and  deposit_id='" . $deposit_id . "' "); // update for existing reords for green tick mark
			if (mysqli_num_rows($accTransUpdate) > 0) {
				$accountwiseTrans =   mysqli_query($writeConnection, "update accountwise_bank_recon  set status='R' where status='Y' and  deposit_id='" . $deposit_id . "' "); // raman 2020-12-08

			}
		}
	} else {

		//Insert
		$chk = "insert into checked_transactions(updated_by,acc_id,check_date,deposit_id,check_amount,deposit_slip,remarks,status,created_by,created_date,slip_checked_remark) values('" . $updatedName . "','" . $account_idss . "','" . date('Y-m-d', strtotime($dates)) . "','" . $deposit_id . "','" . $depo_amount1 . "', '" . $dep_slipes . "', '" . $deposit_status . "','Y','" . $user_name . "',now(),'" . $slipCheckedRemark . "')";
	}
	$chk_exc = mysqli_query($writeConnection, $chk);


	if ($chk_exc) {
		// mysqli_close($conn);
		header("Location:../?pid=check_trans&nav=2_1");
	} else {
		// mysqli_close($conn);
		header("Location:../?pid=check_trans&nav=2_5");
	}
}

if ($pid == 'del_recon_entry') {

	$deposit_id = $_POST['dep_id'];
	$trans_id = $_POST['trans_id'];
	$depo_amount1 = $_POST['reg_amt'];
	$account_idss = $_POST['acc_id'];
	$dep_type = $_POST['dep_type'];
	$dep_slipes = $_POST['recon_slip'];
	$deposit_status = $_POST['ro_remarks'];
	$mirror_remarks = $_POST['mirror_remarks'];
	$dates = $_POST['dates'];
	$types = $_POST['types'];


	$chk_trans = mysqli_query($writeConnection, "Select check_id from delivery_checked_transactions where status='Y' and acc_id = '" . $account_idss . "' and check_date='" . date('Y-m-d', strtotime($dates)) . "' and deposit_id='" . $deposit_id . "' ");
	if (mysqli_num_rows($chk_trans) > 0) {
		$chk_row = mysqli_fetch_assoc($chk_trans);

		if ($dep_slipes != '' || $mirror_remarks != '') {
			$chk = "update delivery_checked_transactions set check_amount ='" . $depo_amount1 . "', deposit_slip = '" . $dep_slipes . "', remarks_sec='" . $mirror_remarks . "',recon_user='" . $user_name . "',recon_time=now() where check_id='" . $chk_row['check_id'] . "' and status='Y' ";
		}
	} else {
		//Insert
		$chk = "insert into delivery_checked_transactions(acc_id,check_date,deposit_id,trans_id,check_amount,deposit_slip,remarks,status,created_by,created_date) values('" . $account_idss . "','" . date('Y-m-d', strtotime($dates)) . "','" . $deposit_id . "','" . $trans_id . "','" . $depo_amount1 . "', '" . $dep_slipes . "', '" . $deposit_status . "','Y','" . $user_name . "',now())";
	}
	$chk_exc = mysqli_query($writeConnection, $chk);


	if ($chk_exc) {
		// mysqli_close($conn);
		header("Location:../?pid=del_check_trans&nav=2_1");
	} else {
		// mysqli_close($conn);
		header("Location:../?pid=del_check_trans&nav=2_5");
	}
}

//===================================VAULT RECON ENTRY============================

if ($pid == 'vault_recon_entry') {

	$deposit_id = $_POST['dep_id'];
	$depo_amount1 = $_POST['reg_amt'];
	$account_idss = $_POST['acc_id'];
	$dep_type = $_POST['dep_type'];
	$dep_slipes = $_POST['recon_slip'];
	$deposit_status = $_POST['ro_remarks'];
	$mirror_remarks = $_POST['mirror_remarks'];
	$dates = $_POST['dates'];
	$types = $_POST['types'];

	//echo "Select check_id from vault_checked_transactions where status='Y' and acc_id = '".$account_idss."' and check_date='".date('Y-m-d',strtotime($dates))."' and deposit_id='".$deposit_id."' "; die;

	$chk_trans = mysqli_query($writeConnection, "Select check_id from vault_checked_transactions where status='Y' and acc_id = '" . $account_idss . "' and check_date='" . date('Y-m-d', strtotime($dates)) . "' and deposit_id='" . $deposit_id . "' ");
	if (mysqli_num_rows($chk_trans) > 0) {
		$chk_row = mysqli_fetch_assoc($chk_trans);


		if ($dep_slipes != '' || $mirror_remarks != '') {
			$chk = "update vault_checked_transactions set check_amount ='" . $depo_amount1 . "', deposit_slip = '" . $dep_slipes . "', remarks_sec='" . $mirror_remarks . "',recon_user='" . $user_name . "',recon_time=now() where check_id='" . $chk_row['check_id'] . "' and status='Y' ";
		}
	} else {

		//Insert
		$chk = "insert into vault_checked_transactions(acc_id,check_date,deposit_id,check_amount,deposit_slip,remarks,status,created_by,created_date) values('" . $account_idss . "','" . date('Y-m-d', strtotime($dates)) . "','" . $deposit_id . "','" . $depo_amount1 . "', '" . $dep_slipes . "', '" . $deposit_status . "','Y','" . $user_name . "',now())";
	}
	$chk_exc = mysqli_query($writeConnection, $chk);


	if ($chk_exc) {
		// mysqli_close($conn);
		//header("Location:../?pid=check_trans&nav=2_1");  
		header("Location:../?pid=check_recon_entry&nav=2_1");
	} else {
		// mysqli_close($conn);
		header("Location:../?pid=check_recon_entry&nav=2_5");
	}
}


if ($pid == 'recon_entry2') {
	$ch_id = $_POST['ch_id'];

	$sql_q = mysqli_query($writeConnection, "update checked_transactions set status='N' where check_id='" . $ch_id . "'");
}


if ($pid == 'save_hole_records') {
	foreach ($_POST['rec_id'] as $vals) {
		$ex = explode('%', $vals);
		$dep_id = $ex[0];
		$sql_ch = mysqli_query($readConnection, "select trans_id from daily_deposit where dep_id='" . $dep_id . "' ");
		$sql_ch1 = mysqli_fetch_assoc($sql_ch);

		$ss = mysqli_query($readConnection, "select trans_id from daily_trans where trans_id in (" . $sql_ch1['trans_id'] . ") and status='Y'");
		$ss_num = mysqli_num_rows($ss);

		$cc = explode(',', $sql_ch1['trans_id']);

		if (!in_array('NaN', $cc)) {
			if ($ss_num > 0) {
				$get_amt = mysqli_query($readConnection, "Select dd.dep_id,dd.dep_type,dd.dep_amount,rc.ce_id,rc.ce_name, rl.location_id, rm.region_name, dd.acc_id,cl.client_name,sd.customer_code,dc.rec_no,dc.dep_slip,cd.cust_name from daily_deposit dd join radiant_ce rc on dd.staff_id=rc.ce_id join radiant_location rl on rl.location_id=rc.loc_id join region_master rm on rm.region_id = rl.region_id join daily_collection dc on dc.trans_id=dd.trans_id join daily_trans dt on dt.trans_id=dd.trans_id join shop_details sd on sd.shop_id=dt.pickup_code join cust_details cd on cd.cust_id=sd.cust_id join client_details cl on cl.client_id=cd.client_id where dd.status='Y' and rc.status='Y' and rl.status='Y' and rm.status='Y' and dd.dep_id='" . $dep_id . "' and rm.region_id in(" . $_SESSION['region'] . ")  ORDER BY dd.dep_amount DESC");
			} else {
				$get_amt = mysqli_query($readConnection, "Select dd.dep_id,dd.dep_type,dd.dep_amount,rc.ce_id,rc.ce_name, rl.location_id, rm.region_name, dd.acc_id,cl.client_name,sd.customer_code,dc.rec_no,dc.dep_slip,cd.cust_name from daily_deposit dd join radiant_ce rc on dd.staff_id=rc.ce_id join radiant_location rl on rl.location_id=rc.loc_id join region_master rm on rm.region_id = rl.region_id join daily_coinpickup dc on dc.trans_id=dd.trans_id join daily_trans_coin dt on dt.trans_id=dd.trans_id join shop_details sd on sd.shop_id=dt.pickup_code join cust_details cd on cd.cust_id=sd.cust_id join client_details cl on cl.client_id=cd.client_id where dd.status='Y' and rc.status='Y' and rl.status='Y' and rm.status='Y' and dd.dep_id='" . $dep_id . "' and rm.region_id in(" . $_SESSION['region'] . ")  ORDER BY dd.dep_amount DESC");
			}
		} else {

			$get_amt = mysqli_query($readConnection, "select * from daily_deposit dd join radiant_ce rc on dd.staff_id=rc.ce_id join radiant_location rl on rl.location_id=rc.loc_id join region_master rm on rm.region_id = rl.region_id join location_master lm on lm.loc_id=rl.location_id where dd.dep_id='" . $dep_id . "' and dd.status='Y' and rm.status='Y' and rc.status='Y' and rl.status='Y' and rm.region_id in(" . $_SESSION['region'] . ")");
		}
		$row_amt = mysqli_fetch_assoc($get_amt);

		$depo_amount1 = $row_amt['dep_amount'];
		$account_idss = $row_amt['acc_id'];
		$dep_type = $row_amt['dep_type'];
		$deposit_status = "";
		$mirror_remarks = "";
		$dates = $ex[1];
		$deposit_slip = "";


		$query1 = mysqli_query($writeConnection, " select deposit_id from checked_transactions where deposit_id ='" . $dep_id . "' and status = 'Y' ");
		if (mysqli_num_rows($query1) > 0) {
			$chk = "UPDATE checked_transactions SET remarks_sec ='Checked by H.O Banking',check_date ='" . date('Y-m-d', strtotime($dates)) . "',recon_user='" . $user_name . "',recon_time=now() WHERE deposit_id = '" . $dep_id . "' AND status = 'Y' ";
		} else {
			$chk = "insert into checked_transactions(remarks_sec,acc_id,check_date,deposit_id,check_amount,deposit_slip,remarks,status,recon_user,recon_time) values('Checked by H.O Banking','','" . date('Y-m-d', strtotime($dates)) . "','" . $dep_id . "','', '" . $deposit_slip . "', '" . $deposit_status . "','Y','" . $user_name . "',now())";
		}
		$chk_exc = mysqli_query($writeConnection, $chk);
	}
	if ($chk_exc) {
		echo '<div class="alert alert-success" style="padding: 7px;"><div align="center"><b>Successfully Updated...</b></div></div>';
	} else {
		echo '<div class="alert alert-danger" style="padding: 7px;"><div align="center"><b>Sorry Try Again...</b></div></div>';
	}


}



?>