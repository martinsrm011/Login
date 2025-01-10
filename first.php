<?php
//echo "hiii"; die;
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
@session_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require '../dependencies/vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;



include('../DbConnection/dbConnect.php');


$user=$_SESSION['lid'];
if($user=='') {
	header('location:../login.php');	
}
$region = $_SESSION['region'];
$user_name=$_SESSION['lid'];
$per = $_SESSION['per'];
$sql_user = mysqli_query($reportConnection,"select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysqli_fetch_array($sql_user);
$login_regoin_exp = explode(',', $res_user['region']);
$login_regoin = $res_user['region'];
$per = $res_user['per'];

$trans_date_c = $_REQUEST['trans_date'];
$trans_date = date('Y-m-d', strtotime($_REQUEST['trans_date']));
$day = date('d', strtotime($_REQUEST['trans_date']));
$pday = date('Y-m-d', strtotime('-1 day', strtotime($trans_date)));
$cid = $_REQUEST['cust_name'];
$amends = $_REQUEST['amendment'];

$sqlc = "SELECT * FROM cust_details AS a INNER JOIN client_details AS b ON a.client_id=b.client_id WHERE a.cust_id=".$cid." AND a.status='Y' AND b.status='Y'";
$quc=mysqli_query($reportConnection,$sqlc);
$rc=mysqli_fetch_array($quc);

$dcust_name = $rc['cust_name'];
$dclient_name = $rc['client_name'];
$dclient_id = $rc['client_id'];
$dcust_id = $rc['cust_id'];
$name = str_replace(" ","_",$rc['client_name']);
$deno = $rc['deno'];


// Create new PHPExcel object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data

$spreadsheet->getActiveSheet()->setCellValue('A1', 'RADIANT CASH MANAGEMENT SERVICES PVT LTD');
$spreadsheet->getActiveSheet()->setCellValue('A2', 'CLIENT NAME:'.$dclient_name);
$spreadsheet->getActiveSheet()->setCellValue('A3', 'PICKUP DATE:'.date('d-M-Y', strtotime($trans_date_c)));

//Zone Details
$sql_zone = mysqli_query($reportConnection,"SELECT zone_id, zone_name FROM zone_master WHERE status='Y'");
while($res_zone = mysqli_fetch_assoc($sql_zone)) {
	$zone[$res_zone['zone_id']] = $res_zone['zone_name'];
}
//Account Details
$sql_acc = "SELECT acc_id, branch_name,account_no,bank_name, bank_type FROM bank_master WHERE status='Y'";
$qu_acc = mysqli_query($reportConnection,$sql_acc);
while($r_acc = mysqli_fetch_array($qu_acc)) {
	$dep_branchs[$r_acc['acc_id']]['branch_name'] =	$r_acc['branch_name'];
	$dep_branchs[$r_acc['acc_id']]['account_no'] =	$r_acc['account_no'];
	$dep_branchs[$r_acc['acc_id']]['bank_name'] =	$r_acc['bank_name'];
	$dep_branchs[$r_acc['acc_id']]['bank_type'] =	$r_acc['bank_type'];
}

$sql_bank = "SELECT bank_code, bank_name FROM all_banks WHERE status='Y'";
$qu_bank = mysqli_query($reportConnection,$sql_bank);
while($res_bank = mysqli_fetch_assoc($qu_bank)) {
	$bank_all[$res_bank['bank_name']] = $res_bank['bank_code']	;
}

//Amendment
$amendment = array();
$sql_query_amn = mysqli_query($reportConnection,"SELECT a.trans_id FROM daily_trans AS a INNER JOIN daily_collection_amend AS b ON a.trans_id=b.trans_id WHERE a.pickup_date='".$trans_date."' AND a.`status`='Y' AND b.`status`='Y'");
while($res_amen = mysqli_fetch_assoc($sql_query_amn)) {
	$amendment[] = $res_amen['trans_id']; 
}

$row_no = 4;
$start_rows = 5;
$calc = 4;
$gtotal = 0;
$report_type = $day_check = 0;

$alb_a = range('A', 'Z');
$alb_b = range('A', 'Z');
foreach($alb_b as $val2) {
	$alb_b1[]='A'.$val2;
}
$alb_c = array_merge($alb_a,$alb_b1);						
													
$alb = array();
//Title

if($dclient_id==97) {
	
	$end_filed = 'AG';
	$title = array('Radiant Branch','Location','Client Name','Pickup Point','Pickup Code','Client Code','Arrangement','Bank Code','Pickup Agency Code','HCI Slip No','Deposit Slip No','Request Amount','Pickup Amount','Difference','PB Amount','No of Notes','Burial Amount','Vault','2000','1000','500','200','100','50','20','10','5','Others','Total','Denominations (Diff)','Remarks','Pickup Schedule','Shop Id');

}




if(!empty($title)) {
	foreach($title as $key3=>$val3) {
		$spreadsheet->getActiveSheet()->setCellValue($alb_c[$key3].$row_no, $val3);
	}
}

$row_no++;
$start_rows = 5;



 if($dclient_id==97) {
	$sql="SELECT b.state,a.trans_id, a.pickup_code, a.pickup_amount, a.`type`, b.pickup_type, b.shop_id,b.customer_code, b.loc_code, b.div_code, b.shop_name, b.address, b.shop_code, b.subcustomer_code, b.hierarchy_code, b.sol_id, b.point_type, b.cash_limit,  b.dep_bank, b.acc_id, b.loi_date, b.sdeact_date, b.inactive_date, c.cust_name, d.location, f.region_name, f.zone_id  FROM daily_trans AS a  INNER JOIN  shop_details AS b ON a.pickup_code=b.shop_id INNER JOIN cust_details AS c ON b.cust_id=c.cust_id  INNER JOIN location_master  AS d ON b.location=d.loc_id INNER JOIN  radiant_location AS e ON e.location_id=d.loc_id INNER JOIN region_master AS f ON e.region_id=f.region_id WHERE a.pickup_date='".$trans_date."' AND  f.region_id IN(".$login_regoin.") AND c.client_id='".$dclient_id."' AND (b.service_type='Cash Pickup' OR b.service_type='Both')  AND  type='Pickup'  AND a.`status`='Y' AND b.`status`='Y' AND c.`status`='Y' AND e.status='Y' AND f.`status`='Y'  GROUP BY a.trans_id  ORDER BY f.region_name ,c.cust_name,d.location";
}


$result=mysqli_query($reportConnection,$sql);
$n=mysqli_num_rows($result);
$i = 1;
if($n>0) {
	$start = $row_no;
while($r=mysqli_fetch_array($result)) {
	
	$region_name = $multi_rec = $remark =  $customer_code_m = $shop_code_m = $loi_date = $sdeact_date = $inactive_date = '';
	$pick_amount = $dep_amount = $client_amt = $burial_amt = $partner_amt = $valut_amt = $prev_vault = $_others = $prev_dep_accid =  $prev_dep_branch =  $others_deno = 0;
	
	if($r['loi_date']!='0000-00-00' && $r['loi_date']!='01-01-1970' && $r['loi_date']!='') {
		$loi_date 	=	!empty($r['loi_date'])?date('d-m-Y', strtotime($r['loi_date'])):"";	
	}
	if($r['sdeact_date']!='0000-00-00' && $r['sdeact_date']!='01-01-1970' && $r['sdeact_date']!='') {
		$sdeact_date =	!empty($r['sdeact_date'])?date('d-m-Y', strtotime($r['sdeact_date'])):"";	
	}
	if($r['inactive_date']!='0000-00-00' && $r['inactive_date']!='01-01-1970' && $r['inactive_date']!='') {
		$inactive_date =	!empty($r['inactive_date'])?date('d-m-Y', strtotime($r['inactive_date'])):"";	
	}
		
		
	$cust_id_cu		= 	!empty($r['cust_id'])?$r['cust_id']:"";
	$shop_id 		= 	!empty($r['pickup_code'])?$r['pickup_code']:"";
	$pickup_amount	= 	!empty($r['pickup_amount'])?$r['pickup_amount']:"0";
	$region_name 	= 	!empty($r['region_name'])?$r['region_name']:"";
	$location 		=	!empty($r['location'])?$r['location']:""; 
	$loc_code 		=	!empty($r['loc_code'])?$r['loc_code']:"";
	$div_code		= 	!empty($r['div_code'])?$r['div_code']:"";
	$customer_code 	=	!empty($r['customer_code'])?$r['customer_code']:"";
	$shop_code		=	!empty($r['shop_code'])?$r['shop_code']:"";
	$cust_name		=	!empty($r['cust_name'])?$r['cust_name']:"";
	$shop_name		= 	!empty($r['shop_name'])?$r['shop_name']:"";
	$address		= 	!empty($r['address'])?$r['address']:"";
	$phone			= 	!empty($r['phone'])?$r['phone']:"";	
	$contact_name	= 	!empty($r['contact_name'])?$r['contact_name']:"";	
	
	$sol_id			= 	!empty($r['sol_id'])?$r['sol_id']:"";
	$subcustomer_code = !empty($r['subcustomer_code'])?$r['subcustomer_code']:"";
	$hierarchy_code	=	!empty($r['hierarchy_code'])?$r['hierarchy_code']:"";
	$cash_limit		=	!empty($r['cash_limit'])?$r['cash_limit']:"0";
	$zone_name		= 	!empty($r['zone_id'])?$zone[$r['zone_id']]:"";
	$service_type	= 	!empty($r['service_type'])?$zone[$r['service_type']]:"-";
	
	
	$pickup_type	= 	!empty($r['pickup_type'])?$r['pickup_type']:"";
	$shop_acc_id	= 	!empty($r['acc_id'])?$r['acc_id']:"";
	$point_type		= 	!empty($r['point_type'])?$r['point_type']:"";
	$shop_dep_bank 	= 	!empty($r['dep_bank'])?$r['dep_bank']:"";
	$pickup_date    =   !empty($r['pickup_date'])?$r['pickup_date']:"";
	
	if($r['client_code']!='Beat' && $r['client_code']!='') {
		$trans_client_code	= explode(',',$r['client_code']);	
	}
	
	if($amends==1) {
		if(in_array($r['trans_id'], $amendment)) {
			$table_sing = "daily_collection_amend";
			$table_mul = "daily_collectionmul_amend";
		}
		else {
			$table_sing = "daily_collection";
			$table_mul = "daily_collectionmul";
		}
	}
	else {
		$table_sing = "daily_collection";
		$table_mul = "daily_collectionmul";
	}
	
	if($customer_code!='') $customer_code_m 	= 	explode(',', $customer_code);
	if($shop_code!='') $shop_code_m	 	=	explode(',', $shop_code);
	
	 $sql2="SELECT t_rec, multi_rec, trans_id,c_code, point_codes, rec_no, pis_hcl_no, hcl_no, gen_slip, cust_field1, cust_field2, cust_field3, cust_field4, pick_amount, diff_amount, coll_remarks, other_remarks, reason_for_nill, 2000s,1000s, 500s,200s, 100s, 50s, 20s, 10s, 5s, coins, dep_type1, dep_accid, dep_branch, dep_amount1, dep_slip, pick_time, pick_time,dep_amount2,dep_bank2,other_remarks2 FROM $table_sing WHERE trans_id='".$r['trans_id']."' AND status='Y'";   
   $qu2=mysqli_query($reportConnection,$sql2);
   $n2=mysqli_num_rows($qu2);
   $n21=mysqli_num_rows($qu2); 
   $r2=mysqli_fetch_array($qu2);
   
   $t_rec	=	!empty($r2['t_rec'])?$r2['t_rec']:"";
   $c_code	=	!empty($r2['c_code'])?$r2['c_code']:"";
   $point_codes	=	!empty($r2['point_codes'])?$r2['point_codes']:"";
   
   $multi_rec	=	!empty($r2['multi_rec'])?$r2['multi_rec']:"";
   $rec_no		=	!empty($r2['rec_no'])?$r2['rec_no']:"";
   $pis_hcl_no	=	!empty($r2['pis_hcl_no'])?$r2['pis_hcl_no']:"";
   $gen_slip	=	!empty($r2['gen_slip'])?$r2['gen_slip']:"";
   $hcl_no		=	!empty($r2['hcl_no'])?$r2['hcl_no']:"";
   $cust_field1	=	!empty($r2['cust_field1'])?$r2['cust_field1']:"";
   $cust_field2	=	!empty($r2['cust_field2'])?$r2['cust_field2']:"";
   $cust_field3	=	!empty($r2['cust_field3'])?$r2['cust_field3']:"";
   $cust_field4	=	!empty($r2['cust_field4'])?$r2['cust_field4']:"";
   $pick_time 	=	!empty($r2['pick_time'])?$r2['pick_time']:"";
   
   $pick_amount	=	!empty($r2['pick_amount'])?$r2['pick_amount']:"0";
   $dep_type1	=   !empty($r2['dep_type1'])?$r2['dep_type1']:"";
   $dep_accid	=	!empty($r2['dep_accid'])?$r2['dep_accid']:"";
   $dep_branch_dep	=	!empty($r2['dep_branch'])?$r2['dep_branch']:"";
   
   $dep_amount 	=	!empty($r2['dep_amount1'])?$r2['dep_amount1']:"0";
   $dep_slip 	=	!empty($r2['dep_slip'])?$r2['dep_slip']:"0";   
   $_2000s		=	!empty($r2['2000s'])?$r2['2000s']:"0";
   $_1000s		=	!empty($r2['1000s'])?$r2['1000s']:"0";
   $_500s		=	!empty($r2['500s'])?$r2['500s']:"0";
    $_200s		=	!empty($r2['200s'])?$r2['200s']:"0";
   $_100s		=	!empty($r2['100s'])?$r2['100s']:"0";
   $_50s		=	!empty($r2['50s'])?$r2['50s']:"0";
   $_20s		=	!empty($r2['20s'])?$r2['20s']:"0";
   $_10s		=	!empty($r2['10s'])?$r2['10s']:"0";
   $_5s			=	!empty($r2['5s'])?$r2['5s']:"0";
   $_coins		=	!empty($r2['coins'])?$r2['coins']:"0";
   $others_deno = ($_5s*5)+$_coins;
   $coll_remarks=	!empty($r2['coll_remarks'])?$r2['coll_remarks']:"";
   $other_remarks=	!empty($r2['other_remarks'])?$r2['other_remarks']:"";
   if($r2['trans_id']!='')
   {
   $trans_id1[]=$r2['trans_id'];
   }
   //unset($trans_id1);
   $reason_for_nill=!empty($r2['reason_for_nill'])?$r2['reason_for_nill']:"";
   $dep_amount2 = !empty($r2['dep_amount2'])?$r2['dep_amount2']:"0";
   $dep_bank2 = !empty($r2['dep_bank2'])?$r2['dep_bank2']:"";
   $other_remarks2 = !empty($r2['other_remarks2'])?$r2['other_remarks2']:"";
   if(($r['point_type']=='Inactive' || $r['point_type']=='Lost') && $n2==0) {
   		$remark='Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';
   }else {
   
	   if($n2>0) {
	   		if($coll_remarks=='Others') $remark = $other_remarks; else $remark = $coll_remarks;
	   }
	   else {
		   if($pickup_type=='Request' && $pickup_amount==0) {		   
			   $remark = 'No Request';
		   }
		   else  {
				$remark = 'Report Awating';   
		   }
		}
	}
  	
   if($dep_type1=='Client Bank') { $client_amt=$pick_amount; }else if($dep_type1=='Burial') { $burial_amt=$pick_amount; }else if($dep_type1=='Partner Bank') { $partner_amt=$pick_amount; } else if($dep_type1=='Vault') { $valut_amt=$pick_amount; $dep_amount = 0; }
  	if($dclient_id==20) {
		if($dep_type1=='Partner Bank' || $dep_type1=='Client Bank') { $partner_amt=$pick_amount; }
	} 
  
   
   if($dclient_id==30||$dclient_id==21){
   		if($vault_amount>0) $dep_amount=0;
   }
   
   
   //Field Details
   $sql_field = mysqli_query($reportConnection,"SELECT * FROM daily_collectionannex WHERE trans_id='".$r['trans_id']."' AND status='Y'");
   $res_field = mysqli_fetch_assoc($sql_field);
   $field1	=	!empty($res_field['field1'])?$res_field['field1']:"";
   $field2	=	!empty($res_field['field2'])?$res_field['field2']:"";
   $field3	=	!empty($res_field['field3'])?$res_field['field3']:"";
   $field4	=	!empty($res_field['field4'])?$res_field['field4']:"";
   
   
   
   
   if($dep_accid!='') { 
	   $sql_acc = "SELECT branch_name,account_no,bank_name FROM bank_master WHERE acc_id='".$dep_accid."' AND status='Y'";
	   $qu_acc = mysqli_query($reportConnection,$sql_acc);
	   $r_acc = mysqli_fetch_array($qu_acc);
	   $dep_branch	=	$r_acc['branch_name'];
	   $dep_accno	=	$r_acc['account_no'];
	   $b_name2		=	$r_acc['bank_name'];
   }
	
	//Prev. Day Vault Amount Desposit
	$sql_prev = mysqli_query($reportConnection,"SELECT dep_amount1, dep_accid, dep_branch FROM daily_trans AS a INNER JOIN daily_collection AS b ON a.trans_id=b.trans_id
WHERE a.pickup_code='".$shop_id."' AND a.pickup_date='".$pday."'  AND b.dep_type1='Vault' AND a.`status`='Y' AND b.`status`='Y'");
	if(mysqli_num_rows($sql_prev)>0) {
		$res_prev = mysqli_fetch_assoc($sql_prev);
		$prev_vault = $res_prev['dep_amount1'];
		$prev_dep_accid = $res_prev['dep_accid'];
		$prev_dep_branch = $res_prev['dep_branch'];
	}

	
		if($dclient_id==97) {
		
		if($multi_rec=="Y") {
			$sql_mul = mysqli_query($reportConnection,"SELECT point_codes,c_code,rec_id, rec_no, pick_amount, hcl_no, mul_remarks,2000s,1000s,500s,200s,100s,50s,20s,10s,5s,coins FROM $table_mul WHERE trans_id='".$r['trans_id']."' AND status='Y'");
			while($rm = mysqli_fetch_assoc($sql_mul)) {
						
						$spreadsheet->getActiveSheet()->setCellValue('A'.$row_no, $region_name);
						$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, $location);
						$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no, $cust_name);

						$spreadsheet->getActiveSheet()->setCellValue('D'.$row_no, $shop_name);
						$spreadsheet->getActiveSheet()->setCellValue('E'.$row_no, $rm['point_codes']);
						$spreadsheet->getActiveSheet()->setCellValue('F'.$row_no, $rm['c_code']);
						$spreadsheet->getActiveSheet()->setCellValue('G'.$row_no, $pickup_type);
						$spreadsheet->getActiveSheet()->setCellValue('H'.$row_no, '-');
						$spreadsheet->getActiveSheet()->setCellValue('I'.$row_no, 'RCMSMUM');
						$spreadsheet->getActiveSheet()->setCellValue('J'.$row_no, $rm['hcl_no']);
						$spreadsheet->getActiveSheet()->setCellValue('K'.$row_no, $rm['rec_no']);		
						$spreadsheet->getActiveSheet()->setCellValue('L'.$row_no, $pickup_amount);
						$spreadsheet->getActiveSheet()->setCellValue('M'.$row_no, $rm['pick_amount']);
						$spreadsheet->getActiveSheet()->setCellValue('N'.$row_no, '=L'.$row_no.'-M'.$row_no);
						if($dep_type1 == 'Partner Bank'){
							$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, $rm['pick_amount']);
						}
						else{
							$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, '0');
						}
						$spreadsheet->getActiveSheet()->setCellValue('P'.$row_no, '=(S'.$row_no.')+(T'.$row_no.')+(U'.$row_no.')+(V'.$row_no.')+(W'.$row_no.')+(X'.$row_no.')+(Y'.$row_no.')+(Z'.$row_no.')+(AA'.$row_no.')');
						if($dep_type1 == 'Burial'){
							$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, $rm['pick_amount']);
						}
						else{
							$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, '0');
						}
						if($dep_type1 == 'Vault'){
							$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, $rm['pick_amount']);
						}
						else{
							$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, '0');
						}
						// Denomination Calculation
						$spreadsheet->getActiveSheet()->setCellValue('S'.$row_no, $rm['2000s']);
						$spreadsheet->getActiveSheet()->setCellValue('T'.$row_no, $rm['1000s']);
						$spreadsheet->getActiveSheet()->setCellValue('U'.$row_no, $rm['500s']);
						$spreadsheet->getActiveSheet()->setCellValue('V'.$row_no, $rm['200s']);
						$spreadsheet->getActiveSheet()->setCellValue('W'.$row_no, $rm['100s']);
						$spreadsheet->getActiveSheet()->setCellValue('X'.$row_no, $rm['50s']);
						$spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, $rm['20s']);
						$spreadsheet->getActiveSheet()->setCellValue('Z'.$row_no, $rm['10s']);
						$spreadsheet->getActiveSheet()->setCellValue('AA'.$row_no, $rm['5s']);
						$spreadsheet->getActiveSheet()->setCellValue('AB'.$row_no, $rm['coins']);
						$totals = '=(S'.$row_no.'*2000)+(T'.$row_no.'*1000)+(U'.$row_no.'*500)+(V'.$row_no.'*200)+(W'.$row_no.'*100)+(X'.$row_no.'*50)+(Y'.$row_no.'*20)+(Z'.$row_no.'*10)+(AA'.$row_no.'*5)+(AB'.$row_no.'*1)';
						$spreadsheet->getActiveSheet()->setCellValue('AC'.$row_no, $totals);		
						$spreadsheet->getActiveSheet()->setCellValue('AD'.$row_no, '=M'.$row_no.'-AC'.$row_no);
						$spreadsheet->getActiveSheet()->setCellValue('AE'.$row_no, $rm['mul_remarks']);
						$spreadsheet->getActiveSheet()->setCellValue('AF'.$row_no, 'Daily');
						$spreadsheet->getActiveSheet()->setCellValue('AG'.$row_no, $r['shop_id']);
						$row_no++;
						$i++;
						$j++;
			}
		}
		else {
				$spreadsheet->getActiveSheet()->setCellValue('A'.$row_no, $region_name);
				$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, $location);
				$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no, $cust_name);

				$spreadsheet->getActiveSheet()->setCellValue('D'.$row_no, $shop_name);
				$spreadsheet->getActiveSheet()->setCellValue('E'.$row_no, $shop_code);
				$spreadsheet->getActiveSheet()->setCellValue('F'.$row_no, $customer_code);
				$spreadsheet->getActiveSheet()->setCellValue('G'.$row_no, $pickup_type);
				$spreadsheet->getActiveSheet()->setCellValue('H'.$row_no, '-');
				$spreadsheet->getActiveSheet()->setCellValue('I'.$row_no, 'RCMSMUM');
				$spreadsheet->getActiveSheet()->setCellValue('J'.$row_no, $hcl_no);
				$spreadsheet->getActiveSheet()->setCellValue('K'.$row_no, $rec_no);
				$spreadsheet->getActiveSheet()->setCellValue('L'.$row_no, $pickup_amount);
				$spreadsheet->getActiveSheet()->setCellValue('M'.$row_no, $pick_amount);
				$spreadsheet->getActiveSheet()->setCellValue('N'.$row_no, '=L'.$row_no.'-M'.$row_no);
				if($dep_type1 == 'Partner Bank'){
					$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, $pick_amount);
				}
				else{
					$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, '0');
				}
				$spreadsheet->getActiveSheet()->setCellValue('P'.$row_no, '=(S'.$row_no.')+(T'.$row_no.')+(U'.$row_no.')+(V'.$row_no.')+(W'.$row_no.')+(X'.$row_no.')+(Y'.$row_no.')+(Z'.$row_no.')+(AA'.$row_no.')');
				
				if($dep_type1 == 'Burial'){
					$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, $pick_amount);
				}
				else{
					$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, '0');
				}
				if($dep_type1 == 'Vault'){
					$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, $pick_amount);
				}
				else{
					$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, '0');
				}
				$spreadsheet->getActiveSheet()->setCellValue('S'.$row_no, $_2000s);
				// Denomination Calculation
				$spreadsheet->getActiveSheet()->setCellValue('T'.$row_no, $_1000s);
				$spreadsheet->getActiveSheet()->setCellValue('U'.$row_no, $_500s);
				$spreadsheet->getActiveSheet()->setCellValue('V'.$row_no, $_200s);
				$spreadsheet->getActiveSheet()->setCellValue('W'.$row_no, $_100s);
				$spreadsheet->getActiveSheet()->setCellValue('X'.$row_no, $_50s);
				$spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, $_20s);
				$spreadsheet->getActiveSheet()->setCellValue('Z'.$row_no, $_10s);
				$spreadsheet->getActiveSheet()->setCellValue('AA'.$row_no, $_5s);
				$spreadsheet->getActiveSheet()->setCellValue('AB'.$row_no, $_coins);
				$totals = '=(S'.$row_no.'*2000)+(T'.$row_no.'*1000)+(U'.$row_no.'*500)+(V'.$row_no.'*200)+(W'.$row_no.'*100)+(X'.$row_no.'*50)+(Y'.$row_no.'*20)+(Z'.$row_no.'*10)+(AA'.$row_no.'*5)+(AB'.$row_no.'*1)';	
				$spreadsheet->getActiveSheet()->setCellValue('AC'.$row_no, $totals);
				//$spreadsheet->getActiveSheet()->setCellValue('AC'.$row_no, '=N'.$row_no.'-AC'.$row_no);
				$spreadsheet->getActiveSheet()->setCellValue('AD'.$row_no, '=M'.$row_no.'-AC'.$row_no);
				$spreadsheet->getActiveSheet()->setCellValue('AE'.$row_no, $remark);
				$spreadsheet->getActiveSheet()->setCellValue('AF'.$row_no, 'Daily');
				$spreadsheet->getActiveSheet()->setCellValue('AG'.$row_no, $r['shop_id']);
				$row_no++;
				$i++;
		}
		//$region_names1 = $region_name;
		
	}
	


}


$alb = array('L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD');
	if(!empty($alb)) {
		foreach($alb as $key=>$val){
			$spreadsheet->getActiveSheet()->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
		}
	}
}	
	$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no,' Grand Total Amount');
	$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':AD'.$row_no)->getFont()->setBold(true);
	$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB('92d050');
	
	$last_row = $row_no;
	$row_no+=3;
	$file_name = 'PICKUP_'.$name.'_'.$trans_date_c.'.xlsx';  
	$sheet_name = $name;

$spreadsheet->getActiveSheet()->getStyle('A1:'.$end_filed.'4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getFill()->getStartColor()->setARGB('da9694');
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getAlignment()->setWrapText(true); 
$spreadsheet->getActiveSheet()->getStyle('AH4:AH'.$last_row)->getAlignment()->setWrapText(true); 
$spreadsheet->getActiveSheet()->getStyle('AI4:AI'.$last_row)->getAlignment()->setWrapText(true); 
$spreadsheet->getActiveSheet()->mergeCells('A1:'.$end_filed.'1');
$spreadsheet->getActiveSheet()->mergeCells('A2:'.$end_filed.'2');
$spreadsheet->getActiveSheet()->mergeCells('A3:'.$end_filed.'3');

for($s = 1;$s<=$last_row;$s++){					
  $spreadsheet->getActiveSheet()->getStyle('A'.$s.':'.$end_filed.$s)->getBorders()->
    getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}
// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle($sheet_name);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);
//mysqli_close($conn);
// Redirect output to a client’s web browser (Excel2007)



  
// If you're serving to IE 9, then the following may be needed


// If you're serving to IE over SSL, then the following may be needed
// Date in the past
// always modified
// HTTP/1.1
// HTTP/1.0

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
ob_end_clean();
$writer->save("php://output");
exit; ?>
