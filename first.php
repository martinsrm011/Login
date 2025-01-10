<?php
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
	//mysqli_close($conn);
	header('location:../login.php');	
}
$region = $_SESSION['region'];
$user_name=$_SESSION['lid'];
$per = $_SESSION['per'];
$sql_user = mysqli_query($reportConnection,"select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysqli_fetch_array($sql_user);
$login_regoin_exp = explode(',', $res_user['region']);
$login_regoin = $res_user['region'];

$cdate=date("Y-m-d",strtotime($_REQUEST['trans_date']));
$pday = date('Y-m-d', strtotime('-1 day', strtotime($cdate)));
$trans_date_c = $_REQUEST['trans_date'];
$cid=$_REQUEST['group_name'];
$amends = $_REQUEST['amendment'];
$gid1=explode(": ",$cid);
$group_name=$gid1[1];

/* district */
$sql_district = "SELECT location_id,location_name FROM shop_city_loc WHERE status='Y'";
$qu_district = mysqli_query($reportConnection,$sql_district);
while($res_district= mysqli_fetch_assoc($qu_district)) {
	$district_all[$res_district['location_id']]['district'] = $res_district['location_name'];
}
//print_r($district_all);die;
/* district */
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
$spreadsheet->getActiveSheet()->setCellValue('A2', 'GROUP NAME:'.$group_name);
$spreadsheet->getActiveSheet()->setCellValue('A3', 'PICKUP DATE:'.date('d-M-Y', strtotime($trans_date_c)));


//Account Details
$sql_acc = "SELECT acc_id, branch_name,account_no,bank_name, bank_type FROM bank_master WHERE status='Y'";
$qu_acc = mysqli_query($reportConnection,$sql_acc);
while($r_acc = mysqli_fetch_array($qu_acc)) {
	$dep_branch_s[$r_acc['acc_id']]['branch_name'] = $r_acc['branch_name'];
	$dep_branch_s[$r_acc['acc_id']]['account_no'] =	$r_acc['account_no'];
	$dep_branch_s[$r_acc['acc_id']]['bank_name'] =	$r_acc['bank_name'];
	$dep_branch_s[$r_acc['acc_id']]['bank_type'] =	$r_acc['bank_type'];
}


//Assign Variable for method

$sheet=$spreadsheet->getActiveSheet();

//All Bank Details
$sql_bank = "SELECT bank_code, bank_name FROM all_banks WHERE status='Y'";
$qu_bank = mysqli_query($reportConnection,$sql_bank);
while($res_bank = mysqli_fetch_assoc($qu_bank)) {
	$bank_all[$res_bank['bank_name']] = $res_bank['bank_code']	;
}

//print_r($bank_all); die;
//Zone Details
$sql_zone = mysqli_query($reportConnection,"SELECT zone_id, zone_name FROM zone_master WHERE status='Y'");
while($res_zone = mysqli_fetch_assoc($sql_zone)) {
	$zone[$res_zone['zone_id']] = $res_zone['zone_name'];
}

//Amendment
$amendment = array();
$sql_query_amn = mysqli_query($reportConnection,"SELECT a.trans_id FROM daily_trans AS a INNER JOIN daily_collection_amend AS b ON a.trans_id=b.trans_id WHERE a.pickup_date='".$cdate."' AND a.`status`='Y' AND b.`status`='Y'");
while($res_amen = mysqli_fetch_assoc($sql_query_amn)) {
	$amendment[] = $res_amen['trans_id']; 
}

$points_type = array('Client Bank'=>'CB', 'Partner Bank'=>'PB', 'Burial'=>'Burial');

$row_no = 4;
$start_rows = 5;
$calc = 4;
$gtotal = 0;
$report_type = 0;

$alb_a = range('A', 'Z');
$alb_b = range('A', 'Z');
$alb_d = range('A', 'Z');
foreach($alb_b as $val2) {
	$alb_b1[]='A'.$val2;
}
foreach($alb_d as $val3) {
	$alb_d1[]='B'.$val3;
}
$alb_c1 = array_merge($alb_a,$alb_b1);
$alb_c = array_merge($alb_c1,$alb_d1);
$cllor_title = 'da9694';
$cllor_reg = '00FFFF';
$cllor_gtotal = '92D050';

$alb = array();

//Title
if($group_name=='KOTAK MAHINDRA BANK') {
	
	$end_filed = 'AH';
	$sheet->setCellValue('A'.$row_no, 'Region');
	$sheet->setCellValue('B'.$row_no, 'Location');
	$sheet->setCellValue('C'.$row_no, 'Pick Up Point Address');
	$sheet->setCellValue('D'.$row_no, 'Customer Name');
	$sheet->setCellValue('E'.$row_no, 'Client Code');
	$sheet->setCellValue('F'.$row_no, 'Pick Up Amount');
	$sheet->setCellValue('G'.$row_no, '2000');
	$sheet->setCellValue('H'.$row_no, '1000');
	$sheet->setCellValue('I'.$row_no, '500');
	$sheet->setCellValue('J'.$row_no, '100');
	$sheet->setCellValue('K'.$row_no, '50');
	$sheet->setCellValue('L'.$row_no, '20');
	$sheet->setCellValue('M'.$row_no, '10');
	$sheet->setCellValue('N'.$row_no, '5');
	$sheet->setCellValue('O'.$row_no, '1');
	$sheet->setCellValue('P'.$row_no, 'Deno Total');
	$sheet->setCellValue('Q'.$row_no, 'Kotak Deposit Slip No.');
	$sheet->setCellValue('R'.$row_no, 'HCI Slip No');
	$sheet->setCellValue('S'.$row_no, 'HCI No');
	$sheet->setCellValue('T'.$row_no, 'Sealing Tag No.');
	$sheet->setCellValue('U'.$row_no, 'Kotak Dep ');
	$sheet->setCellValue('V'.$row_no, 'Vault Amount');
	$sheet->setCellValue('W'.$row_no, 'Cash Disposal');
	$sheet->setCellValue('X'.$row_no, 'Bank Name & A/c No.');
	$sheet->setCellValue('Y'.$row_no, 'Branch Name');
	$sheet->setCellValue('Z'.$row_no, 'Previous day Vault deposited  details');
	$sheet->setCellValue('AA'.$row_no, 'Remarks ');
	$sheet->setCellValue('AB'.$row_no, 'Type of Pickup');
	$sheet->setCellValue('AC'.$row_no, 'Active & In-Active Points');
	$sheet->setCellValue('AD'.$row_no, 'LOI Received Date');
	$sheet->setCellValue('AE'.$row_no, 'Pickup Started Date');
	$sheet->setCellValue('AF'.$row_no, 'Date of discontinuation');
	$sheet->setCellValue('AG'.$row_no, 'Cash Limit');
	$sheet->setCellValue('AH'.$row_no, 'Point ID');
}
else if($group_name=="Radiant Consol" || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup" || $group_name=="VFS Migration" || $group_name=="Other Migration" || $group_name=="SCB-HYUNDAI") {
	if($group_name=="Radiant Consol" || $group_name=="Consol Evening Pickup")
	{
		$end_filed = 'AS';
	}
	else if($group_name=="SCB-HYUNDAI" ||  $group_name=="Other Migration")
	{
		$end_filed = 'AR';
	}
    else{
	$end_filed = 'AQ';
	}
	$sheet->setCellValue('A'.$row_no, 'Vendor');
	$sheet->setCellValue('B'.$row_no, 'Pick Date');
	$sheet->setCellValue('C'.$row_no, 'Location');
	$sheet->setCellValue('D'.$row_no, 'Location Code');
	$sheet->setCellValue('E'.$row_no, 'Customer Code');
	$sheet->setCellValue('F'.$row_no, 'Customer Name');
	$sheet->setCellValue('G'.$row_no, 'Pickup Point');
	$sheet->setCellValue('H'.$row_no, 'Pk Point Code');
	$sheet->setCellValue('I'.$row_no, 'Dep Slip No');
	$sheet->setCellValue('J'.$row_no, 'Request Amount');
	$sheet->setCellValue('K'.$row_no, 'Pickup Amount');
	$sheet->setCellValue('L'.$row_no, '2000');
	$sheet->setCellValue('M'.$row_no, '1000');
	$sheet->setCellValue('N'.$row_no, '500');
	$sheet->setCellValue('O'.$row_no, '200');
	$sheet->setCellValue('p'.$row_no, '100');
	$sheet->setCellValue('Q'.$row_no, '50');
	$sheet->setCellValue('R'.$row_no, '20');
	$sheet->setCellValue('S'.$row_no, '10');
	$sheet->setCellValue('T'.$row_no, '5');
	$sheet->setCellValue('U'.$row_no, 'coins');
	$sheet->setCellValue('V'.$row_no, 'Deno Total');
	$sheet->setCellValue('W'.$row_no, 'Difference');
	$sheet->setCellValue('X'.$row_no, 'PIS No');
	if($group_name=="VFS Migration" ) {
		$sheet->setCellValue('Y'.$row_no, 'Seal Tag No');
		$sheet->setCellValue('Z'.$row_no, 'HCL No.');
		
		//$sheet->setCellValue('AA'.$row_no, 'Seal Tag No.');
		$sheet->setCellValue('AA'.$row_no, 'Customer Ref.');
		$sheet->setCellValue('AB'.$row_no, 'Vendor Slip No');
		$sheet->setCellValue('AC'.$row_no, 'Remarks');
		$sheet->setCellValue('AD'.$row_no, 'PB Amount');
		$sheet->setCellValue('AE'.$row_no, 'Region');
		$sheet->setCellValue('AF'.$row_no, 'Dep Amount');
		$sheet->setCellValue('AG'.$row_no, 'Bank');
		$sheet->setCellValue('AH'.$row_no, 'Remarks for Chennai');
		$sheet->setCellValue('AI'.$row_no, 'Shop ID');
		$sheet->setCellValue('AJ'.$row_no, 'Bank Deposit Type  as per the Point master ');
		$sheet->setCellValue('AK'.$row_no, 'Bank Deposit Account No as per the Point master');
		$sheet->setCellValue('AL'.$row_no, 'Bank Deposit Type  as per the Transaction  ');
		$sheet->setCellValue('AM'.$row_no, 'Bank Deposit Account No as per the Transaction ');
		$sheet->setCellValue('AN'.$row_no, 'Point Type');
		$sheet->setCellValue('AO'.$row_no, 'LOI Date');
		$sheet->setCellValue('AP'.$row_no, 'Point Deactivation Date');
		$sheet->setCellValue('AQ'.$row_no, 'Inactive Date');
		$sheet->setCellValue('AR'.$row_no, 'Same Day/Next Day Deposit');

	}
	else {
		$sheet->setCellValue('Y'.$row_no, 'HCL No');
		$sheet->setCellValue('Z'.$row_no, 'Seal Tag No');
		
		$sheet->setCellValue('AA'.$row_no, 'Customer Ref.');
		$sheet->setCellValue('AB'.$row_no, 'Vendor Slip No');
		$sheet->setCellValue('AC'.$row_no, 'Remarks');
		$sheet->setCellValue('AD'.$row_no, 'PB Amount');
		$sheet->setCellValue('AE'.$row_no, 'Region');
		$sheet->setCellValue('AF'.$row_no, 'Dep Amount');
		$sheet->setCellValue('AG'.$row_no, 'Bank');
		$sheet->setCellValue('AH'.$row_no, 'Remarks for Chennai');
		if($group_name=='Radiant Consol' || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup") {
			$sheet->setCellValue('AI'.$row_no, 'Mode Of Deposit');
			$sheet->setCellValue('AJ'.$row_no, 'Shop ID');
			$sheet->setCellValue('AK'.$row_no, 'Bank Deposit Type  as per the Point master ');
			$sheet->setCellValue('AL'.$row_no, 'Bank Deposit Account No as per the Point master');
			$sheet->setCellValue('AM'.$row_no, 'Bank Deposit Type  as per the Transaction  ');
			$sheet->setCellValue('AN'.$row_no, 'Bank Deposit Account No as per the Transaction ');
			$sheet->setCellValue('AO'.$row_no, 'Point Type');
			$sheet->setCellValue('AP'.$row_no, 'LOI Date');
			$sheet->setCellValue('AQ'.$row_no, 'Point Deactivation Date');
			$sheet->setCellValue('AR'.$row_no, 'Inactive Date');
			if($group_name=='Radiant Consol' || $group_name=='Consol Evening Pickup')
			{
				$sheet->setCellValue('AS'.$row_no, 'RBI City Name');

			}

		}
		else {
			$sheet->setCellValue('AI'.$row_no, 'Shop ID');
			$sheet->setCellValue('AJ'.$row_no, 'Bank Deposit Type  as per the Point master ');
			$sheet->setCellValue('AK'.$row_no, 'Bank Deposit Account No as per the Point master');
			$sheet->setCellValue('AL'.$row_no, 'Bank Deposit Type  as per the Transaction  ');
			$sheet->setCellValue('AM'.$row_no, 'Bank Deposit Account No as per the Transaction ');
			$sheet->setCellValue('AN'.$row_no, 'Point Type');
			$sheet->setCellValue('AO'.$row_no, 'LOI Date');
			$sheet->setCellValue('AP'.$row_no, 'Point Deactivation Date');
			$sheet->setCellValue('AQ'.$row_no, 'Inactive Date');
			if($group_name == 'SCB-HYUNDAI' || $group_name == 'Other Migration')
			{
				$sheet->setCellValue('AR'.$row_no, 'RBI City Name');

			}


		}
	}
	
}
else if($group_name=="Reliance Console") {
	$cllor_title = '92CDDC';
	$cllor_reg = 'FABF8F';
	$cllor_gtotal = '008000';
	$end_filed = 'AQ';
	$title = array('Vendor','Pick Date','Location','Location Code','Customer Code','Customer Name','Pickup Point','Pk Point Code',
	'Dep Slip No','Pickup Amount', '2000', '1000', '500','200', '100', '50', '20', '10', '5', 'Coins', 'Total', 'Difference','PIS No',
	'Hcl No.','Seal Tag','Customer Ref.','Vendor Slip No','Remarks','PB Amount','Deposit Amount','BANK','REMARKS FOR CHENNAI','Shop ID',
	'Bank Deposit Type  as per the Point master ','Bank Deposit Account No as per the Point master','Bank Deposit Type  as per the Transaction  ','Bank Deposit Account No as per the Transaction ','Point Type',
	'LOI Date','Point Deactivation Date','Inactive Date','Region','RBI City Name');
	
}
else if($group_name=='TTL') {
	$cllor_title = '92CDDC';
	$cllor_reg = 'FABF8F';
	$cllor_gtotal = '008000';
	$end_filed = 'AT';
	$title = array('SR.NO','Date','Region','Location','Pick Up Point','Pick Up Code','Client Code','Arrange ment','Disposal','Bank Name','PIS Number','Deposit Slip No','Request Amount','Pick Up Amount','Difference','PB Amount', '2000', '1000', '500', '200','100', '50', '20', '10', '5', 'Coins','Old 1000', 'Old 500', 'Total', 'Difference','Remarks','PB Deposit Slip No','Daily Limit','State Code','Payment Day / Deposition Day','Shop ID','Bank Deposit Type  as per the Point master ','Bank Deposit Account No as per the Point master','Bank Deposit Type  as per the Transaction  ','Bank Deposit Account No as per the Transaction ','Point Type','LOI Date','Point Deactivation Date','Inactive Date','Point ID','Request ID');
}
else if($group_name=='Toll Plaza') {
	$cllor_title = '92CDDC';
	$cllor_reg = 'FABF8F';
	$cllor_gtotal = '008000';
	$end_filed = 'AK';
	$title = array('Region','Pickup Agency Name','Client Name','Location','ICICI Deposit Slip No.','HCI Slip No. for Cash','Pickup Amount','Vault Amount','Deposit Amount','Deposit Account No.','Deposit Branch Name','Diff.','Previous day vault deposit amount ','Ac No. & Branch','Remarks','2000','1000','500','200','100','50','20','10','5','Coins','Total','Diff.','Shop ID','Bank Deposit Type  as per the Point master ','Bank Deposit Account No as per the Point master','Bank Deposit Type  as per the Transaction  ','Bank Deposit Account No as per the Transaction ','Point Type','LOI Date','Point Deactivation Date','Inactive Date','Point ID');
	
}
else if($group_name=="Reliance Communications"){
	$cllor_title = '92CDDC';
	$cllor_reg = 'FABF8F';
	$cllor_gtotal = '008000';
	$end_filed = 'AN';
	$title = array('Vendor','Pick Date','Location','Point Name','Location Code','Customer Code','Customer Name','Pickup Point',
	'OTC CODE','PIS No','PIS Amount','Pickup Amount', 'Difference', '2000', '1000', '500','200', '100', '50', '20', '10', '5', 'Coins',
	 'Deno Total', 'Deno Difference','Remarks','PB Amount','Deposit Amount','BANK','REMARKS FOR CHENNAI','Branch','Shop ID',
	 'Bank Deposit Type  as per the Point master ','Bank Deposit Account No as per the Point master',
	 'Bank Deposit Type  as per the Transaction  ','Bank Deposit Account No as per the Transaction ','Point Type','LOI Date',
	 'Point Deactivation Date','Inactive Date');
}
else if($group_name=="Group-1" || $group_name=="Group-3" ||  $group_name=="Group-2" || $group_name=="Group1-Airport Pickup") {
	$cllor_title = '92CDDC';
	$cllor_reg = 'FABF8F';
	$cllor_gtotal = '008000';
	$end_filed = 'BA';
	$title = array('SR.NO','Date','Region','Location','Pick Up Point','Pick Up Code','Client Code','Arrangement','Bank Code','Pickup Agency Code','PIS Number','Deposit Slip No','Request Amount','Pick Up Amount','Difference','PB Amount', '2000', '1000', '500', '200','100', '50', '20', '10', '5', 'Coins','Old 1000', 'Old 500', 'Total', 'Difference','Remarks','PB Deposit Slip No','Daily Limit','State Code','Payment Day / Deposition Day','Pickup time','Payer ID Code','Deposit Amount','BANK','REMARKS FOR CHENNAI','Shop ID','Bank Deposit Type  as per the Point master ','Bank Deposit Account No as per the Point master','Bank Deposit Type  as per the Transaction  ','Bank Deposit Account No as per the Transaction ','Point Type','LOI Date','Point Deactivation Date','Inactive Date','Point ID','State Name','Pin Code','Request ID');
}
if(!empty($title)) {
	foreach($title as $key3=>$val3) {
		$sheet->setCellValue($alb_c[$key3].$row_no, $val3);
	}
}
$row_no++;
$start_rows = 5;
$gtotal_rows = array();

if($group_name=='KOTAK MAHINDRA BANK') {
	$sql="SELECT a.trans_id, a.pickup_code, a.pickup_amount, a.`type`, b.shop_id, b.customer_code, b.loc_code, b.div_code, b.shop_name, b.address, b.shop_code, b.loi_date, b.sact_date, b.sdeact_date, b.subcustomer_code, b.hierarchy_code, b.sol_id, b.pickup_type, b.point_type, b.cash_limit, b.inactive_date, c.cust_name, d.location, f.region_name, f.zone_id  FROM daily_trans AS a  INNER JOIN  shop_details AS b ON a.pickup_code=b.shop_id INNER JOIN cust_details AS c ON b.cust_id=c.cust_id  INNER JOIN location_master  AS d ON b.location=d.loc_id INNER JOIN  radiant_location AS e ON e.location_id=d.loc_id INNER JOIN region_master AS f ON e.region_id=f.region_id WHERE a.pickup_date='".$cdate."' AND  f.region_id IN(".$login_regoin.") AND c.group_name='".$group_name."'  AND (b.service_type='Cash Pickup' OR b.service_type='Both')   AND a.`status`='Y' AND b.`status`='Y' AND c.`status`='Y' AND e.status='Y' AND f.`status`='Y'  ORDER BY f.zone_id, c.cust_name,d.location";
}

else 
{
$sql="SELECT c.cust_id,b.state,b.pincode,a.pickup_date,a.trans_id, a.pickup_code, a.pickup_amount, a.`type`, a.client_code,  b.shop_id,b.customer_code, b.loc_code, b.div_code, b.shop_name, b.address, b.shop_code, b.loi_date, b.sact_date, b.sdeact_date, b.subcustomer_code, b.hierarchy_code, b.sol_id, b.pickup_type, b.dep_bank, b.point_type, b.inactive_date,b.city_name, b.acc_id, b.cash_limit, c.cust_name, d.location,d.district, f.region_name, f.zone_id  FROM daily_trans AS a  INNER JOIN  shop_details AS b ON a.pickup_code=b.shop_id INNER JOIN cust_details AS c ON b.cust_id=c.cust_id  INNER JOIN location_master  AS d ON b.location=d.loc_id INNER JOIN  radiant_location AS e ON e.location_id=d.loc_id INNER JOIN region_master AS f ON e.region_id=f.region_id WHERE a.pickup_date='".$cdate."' AND  f.region_id IN(".$login_regoin.") AND c.group_name='".$group_name."'  AND (b.service_type='Cash Pickup' OR b.service_type='Both') AND type='Pickup' AND a.`status`='Y' AND b.`status`='Y' AND c.`status`='Y' AND e.status='Y' AND f.`status`='Y'  ORDER BY c.cust_name, f.region_name, d.location";
}


$trans=array();
$result=mysqli_query($reportConnection,$sql);
$n=mysqli_num_rows($result);
$i = 1;
if($n>0) {
	while($r=mysqli_fetch_array($result)) {
		
		$pick_amount = $dep_amount = $client_amt = $burial_amt = $partner_amt = $valut_amt = $prev_vault = $kotak_dep = $no_notes =  0;
		$dbank = $loi_date  = $sact_date = $sdeact_date = $inactive_date = '';
		$agency_code = 'NIL';
		
		$pickup_amount	=	!empty($r['pickup_amount'])?$r['pickup_amount']:"0";
		$pickup_type	=	!empty($r['pickup_type'])?$r['pickup_type']:"";
		
		$trans[$r['trans_id']]['shop_id']=!empty($r['pickup_code'])?$r['pickup_code']:"";
		$trans[$r['trans_id']]['region_name']= 	!empty($r['region_name'])?$r['region_name']:"";
		$trans[$r['trans_id']]['location']=	!empty($r['location'])?$r['location']:""; 
		$trans[$r['trans_id']]['loc_code']=	!empty($r['loc_code'])?$r['loc_code']:"";
		if($r['loi_date']!='0000-00-00') {
			$trans[$r['trans_id']]['loi_date']=	!empty($r['loi_date'])?date('d/m/Y', strtotime($r['loi_date'])):"";	
		}
		if($r['sact_date']!='0000-00-00' && $r['sact_date']!='') {
			$trans[$r['trans_id']]['sact_date']	=	!empty($r['sact_date'])?date('d/m/Y', strtotime($r['sact_date'])):"";	
		}
		if($r['sdeact_date']!='0000-00-00' && $r['sdeact_date']!='') {
			$trans[$r['trans_id']]['sdeact_date'] =	!empty($r['sdeact_date'])?date('d/m/Y', strtotime($r['sdeact_date'])):"";	
		}
		if($r['inactive_date']!='0000-00-00' && $r['inactive_date']!='01-01-1970' && $r['inactive_date']!='') {
			$trans[$r['trans_id']]['inactive_date'] =	!empty($r['inactive_date'])?date('d-m-Y', strtotime($r['inactive_date'])):"";	
		}

		$trans[$r['trans_id']]['cust_id']	=!empty($r['cust_id'])?$r['cust_id']:"";

		$trans[$r['trans_id']]['div_code']= !empty($r['div_code'])?$r['div_code']:"";
		$trans[$r['trans_id']]['customer_code']	=!empty($r['customer_code'])?$r['customer_code']:"";
		$trans[$r['trans_id']]['shop_code']	=!empty($r['shop_code'])?$r['shop_code']:"";
		$trans[$r['trans_id']]['cust_name']	=!empty($r['cust_name'])?$r['cust_name']:"";
		$trans[$r['trans_id']]['shop_name']	= !empty($r['shop_name'])?$r['shop_name']:"";
		$trans[$r['trans_id']]['address']=!empty($r['address'])?$r['address']:"";
		$trans[$r['trans_id']]['sol_id']= !empty($r['sol_id'])?$r['sol_id']:"";
		$trans[$r['trans_id']]['pickup_type']= 	!empty($r['pickup_type'])?$r['pickup_type']:"";
		$trans[$r['trans_id']]['point_type']=!empty($r['point_type'])?$r['point_type']:"";		
		$trans[$r['trans_id']]['subcustomer_code']=!empty($r['subcustomer_code'])?$r['subcustomer_code']:"";
		$trans[$r['trans_id']]['hierarchy_code']=!empty($r['hierarchy_code'])?$r['hierarchy_code']:"";
		$trans[$r['trans_id']]['cash_limit']=!empty($r['cash_limit'])?$r['cash_limit']:"0";
		$trans[$r['trans_id']]['zone_name']=!empty($r['zone_id'])?$zone[$r['zone_id']]:"";
		$trans[$r['trans_id']]['client_code']=!empty($r['client_code'])?$r['client_code']:"";
		$trans[$r['trans_id']]['region_name']=!empty($r['region_name'])?$r['region_name']:"";
		$trans[$r['trans_id']]['acc_id_shop']= $shop_acc_id=!empty($r['acc_id'])?$r['acc_id']:"";
		$trans[$r['trans_id']]['dep_bank_shop']=!empty($r['dep_bank'])?$r['dep_bank']:"";
		$trans[$r['trans_id']]['pickup_amount']=!empty($r['pickup_amount'])?$r['pickup_amount']:"0";
		$trans[$r['trans_id']]['pickup_date']=!empty($r['pickup_date'])?$r['pickup_date']:"";
		$trans[$r['trans_id']]['state']=!empty($r['state'])?$r['state']:"";
		$trans[$r['trans_id']]['pincode']=!empty($r['pincode'])?$r['pincode']:"";
		$trans[$r['trans_id']]['cityname']=!empty($r['city_name'])?$r['city_name']:"";
		$trans[$r['trans_id']]['district']=!empty($r['district'])?$r['district']:"";

	
		if($trans[$r['trans_id']]['customer_code']!='') $customer_code_m 	= 	explode(',', $trans[$r['trans_id']]['customer_code']);
		if($trans[$r['trans_id']]['shop_code']!='') $shop_code_m	 	=	explode(',', $trans[$r['trans_id']]['shop_code']);
		if($trans[$r['trans_id']]['client_code']!='') $client_code_m = explode(',', $trans[$r['trans_id']]['client_code']); else $client_code_m 	= 	explode(',', $trans[$r['trans_id']]['customer_code']);
		
		$trans_id[] = $r['trans_id'];
		$shop_id[] = $r['shop_id'];
		$acc_id_shop[]=$r['acc_id'];
		
		$trans[$r['trans_id']]['remark'] = 'Report Awating';
				//Remarks
				if($r['point_type']=='Inactive' || $r['point_type']=='Lost') {
					$trans[$r['trans_id']]['remark'] = 'Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';
					$trans[$r['trans_id']]['remarks'] = 'Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';;
				}
				else {
					if($pickup_type=='Request' && $pickup_amount==0) {
						$trans[$r['trans_id']]['remarks'] = 'No Request';
						$trans[$r['trans_id']]['remark'] = 'No Request';
					}
					else {
						$trans[$r['trans_id']]['remarks'] = 'Report Awating';
						$trans[$r['trans_id']]['remark'] = 'Report Awating';
					}
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
		
			
	}
}


if(!empty($trans_id)) {
	
	 $cu_trans_id = implode(',',$trans_id); 

	 $sql2="SELECT multi_rec, trans_id, rec_no, pis_hcl_no, hcl_no, gen_slip,  cust_field1, cust_field2, cust_field3, cust_field4, pick_amount, diff_amount, coll_remarks, other_remarks, 2000s,1000s, 500s,200s, 100s, 50s, 20s, 10s, 5s, coins,o1000s, o500s, dep_type1, dep_accid, dep_branch, dep_amount1, dep_slip, pick_time,dep_amount2,dep_bank2,other_remarks2 FROM daily_collection WHERE trans_id IN (".$cu_trans_id.") AND status='Y'";   
	   $qu2=mysqli_query($reportConnection,$sql2);
	   $n2=mysqli_num_rows($qu2); 
	while($r2=mysqli_fetch_array($qu2)) {
		
		$dep_type1	=   !empty($r2['dep_type1'])?$r2['dep_type1']:"1";
		$dep_amount 	=	!empty($r2['dep_amount1'])?$r2['dep_amount1']:"0";
		$pick_amount	=	!empty($r2['pick_amount'])?$r2['pick_amount']:"0";
		
	   $trans[$r2['trans_id']]['multi_rec']=!empty($r2['multi_rec'])?$r2['multi_rec']:"";
	   $trans[$r2['trans_id']]['rec_no']=!empty($r2['rec_no'])?$r2['rec_no']:"";
	   $trans[$r2['trans_id']]['pis_hcl_no']	=!empty($r2['pis_hcl_no'])?$r2['pis_hcl_no']:"";
	   $trans[$r2['trans_id']]['hcl_no']	=!empty($r2['hcl_no'])?$r2['hcl_no']:"";
	   $trans[$r2['trans_id']]['gen_slip']=!empty($r2['gen_slip'])?$r2['gen_slip']:"";
	   
	   $trans[$r2['trans_id']]['cust_field1']=!empty($r2['cust_field1'])?$r2['cust_field1']:"";
	   $trans[$r2['trans_id']]['cust_field2']=!empty($r2['cust_field2'])?$r2['cust_field2']:"";
	   $trans[$r2['trans_id']]['cust_field3']=!empty($r2['cust_field3'])?$r2['cust_field3']:"";
	   $trans[$r2['trans_id']]['cust_field4']=!empty($r2['cust_field4'])?$r2['cust_field4']:"";
	   
	    $trans[$r2['trans_id']]['pick_amount']=!empty($r2['pick_amount'])?$r2['pick_amount']:"0";
	   $trans[$r2['trans_id']]['dep_type1']= !empty($r2['dep_type1'])?$r2['dep_type1']:"";
	   $trans[$r2['trans_id']]['dep_accid']=!empty($r2['dep_accid'])?$r2['dep_accid']:"";
	   $trans[$r2['trans_id']]['dep_amount']=!empty($r2['dep_amount1'])?$r2['dep_amount1']:"0";
	   $trans[$r2['trans_id']]['dep_branch']=!empty($r2['dep_branch'])?$r2['dep_branch']:"0";
	   $trans[$r2['trans_id']]['dep_slip']=!empty($r2['dep_slip'])?$r2['dep_slip']:"0";  
	   $trans[$r2['trans_id']]['_2000s']=!empty($r2['2000s'])?$r2['2000s']:"0"; 
	   $trans[$r2['trans_id']]['_1000s']=!empty($r2['1000s'])?$r2['1000s']:"0";
	   $trans[$r2['trans_id']]['_500s']=!empty($r2['500s'])?$r2['500s']:"0";
	   $trans[$r2['trans_id']]['_200s']=!empty($r2['200s'])?$r2['200s']:"0";
	   $trans[$r2['trans_id']]['_100s']=!empty($r2['100s'])?$r2['100s']:"0";
	   $trans[$r2['trans_id']]['_50s']=!empty($r2['50s'])?$r2['50s']:"0";
	   $trans[$r2['trans_id']]['_20s']=!empty($r2['20s'])?$r2['20s']:"0";
	   $trans[$r2['trans_id']]['_10s']=!empty($r2['10s'])?$r2['10s']:"0";
	   $trans[$r2['trans_id']]['_5s']=!empty($r2['5s'])?$r2['5s']:"0";
	   $trans[$r2['trans_id']]['_coins']=!empty($r2['coins'])?$r2['coins']:"0";
	   $trans[$r2['trans_id']]['_o1000s']=!empty($r2['o1000s'])?$r2['o1000s']:"0";
	   $trans[$r2['trans_id']]['_o500s']=!empty($r2['o500s'])?$r2['o500s']:"0";
	   $trans[$r2['trans_id']]['coll_remarks']=!empty($r2['coll_remarks'])?$r2['coll_remarks']:"";
	   $trans[$r2['trans_id']]['other_remarks']=!empty($r2['other_remarks'])?$r2['other_remarks']:"";
	   $trans[$r2['trans_id']]['pick_time']=!empty($r2['pick_time'])?$r2['pick_time']:"";
	   
	   $trans[$r2['trans_id']]['dep_amount2']=!empty($r2['dep_amount2'])?$r2['dep_amount2']:"";
	   $trans[$r2['trans_id']]['dep_bank2']=!empty($r2['dep_bank2'])?$r2['dep_bank2']:"";
	   $trans[$r2['trans_id']]['other_remarks2']=!empty($r2['other_remarks2'])?$r2['other_remarks2']:"";
	   
	   $dep_accid[] = $trans[$r2['trans_id']]['dep_accid'];
	   
	   $coll_remarks=	!empty($r2['coll_remarks'])?$r2['coll_remarks']:"";
	   $other_remarks=	!empty($r2['other_remarks'])?$r2['other_remarks']:"";
	   if($coll_remarks=='Others') $remark = $other_remarks; else $remark = $coll_remarks;
	   $trans[$r2['trans_id']]['remark'] = !empty($remark)?$remark:"";
	   
		if($group_name=="Group-1" || $group_name=="Group-3" || $group_name=="Group1-Airport Pickup"){
			$no_notes = '2000X'.$trans[$r2['trans_id']]['_2000s'].',1000X'.$trans[$r2['trans_id']]['_1000s'].', 500X'.$trans[$r2['trans_id']]['_500s'].', 100X'.$trans[$r2['trans_id']]['_100s'].', 50X'.$trans[$r2['trans_id']]['_50s'].', 20X'.$trans[$r2['trans_id']]['_20s'].', 10X'.$trans[$r2['trans_id']]['_10s'].', 5X'.$trans[$r2['trans_id']]['_5s'].', Coins X'.$trans[$r2['trans_id']]['_coins'].',1000X'.$trans[$r2['trans_id']]['_o1000s'].', 500X'.$trans[$r2['trans_id']]['_o500s'];
		}
		
		//echo $trans[$r2['trans_id']]['point_type']; die;
	  
		/*if($trans[$r2['trans_id']]['point_type']=='Inactive' || $trans[$r2['trans_id']]['point_type']=='Lost') {
			 $trans[$r2['trans_id']]['remark']='Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';
	   	}
	   	else {
	
			if($trans[$r2['trans_id']]['coll_remarks']=='Others')  $trans[$r2['trans_id']]['remark'] = $trans[$r2['trans_id']]['other_remarks']; else if($trans[$r2['trans_id']]['coll_remarks']!='')$trans[$r2['trans_id']]['remark'] = $trans[$r2['trans_id']]['coll_remarks'];else $trans[$r2['trans_id']]['remark'] = 'Report Awating';
		
	    
		if($trans[$r2['trans_id']]['pickup_type']=='Request' && $trans[$r2['trans_id']]['pickup_amount']==0) {
			 $trans[$r2['trans_id']]['remark']	=	"No Request";
		}
		} */
		
		//echo $trans[$r2['trans_id']]['remark']; //die;
		if($trans[$r2['trans_id']]['dep_type1']=="Burial")$trans[$r2['trans_id']]['agency_code']="RCMSMUM";elseif($trans[$r2['trans_id']]['dep_type1']=="Partner Bank") {
			/*$acc_idss = '';
			$acc_idss = $dep_branch_s[$acc_id_shop]['bank_name'];
			$agency_code = $bank_all[$acc_idss]; */
			$acc_idss = '';
			$acc_idss = $dep_branch_s[$trans[$r2['trans_id']]['acc_id_shop']]['bank_name'];
			if($bank_all[$acc_idss]=='SBI' || $bank_all[$acc_idss]=='PNB') {
				$trans[$r2['trans_id']]['agency_code'] = $bank_all[$acc_idss].'CASH'; 
			}
			else {
				$trans[$r2['trans_id']]['agency_code'] = $bank_all[$acc_idss]; 
			}
						
		}else $trans[$r2['trans_id']]['agency_code']="NIL";
		
		
		
		if($trans[$r2['trans_id']]['agency_code']!="RCMSMUM") {
			if($trans[$r2['trans_id']]['dep_bank_shop']=="Partner Bank") $trans[$r2['trans_id']]['dbank']="PB"; else $trans[$r2['trans_id']]['dbank']=$trans[$r2['trans_id']]['dep_bank_shop'];	
		}
		else {
			if($trans[$r2['trans_id']]['dep_type1']=="Partner Bank") $trans[$r2['trans_id']]['dbank']="PB"; else $trans[$r2['trans_id']]['dbank']=$trans[$r2['trans_id']]['dep_type1'];
		}
		
	   
	   if($trans[$r2['trans_id']]['dep_type1']=='Client Bank') { $client_amt=$trans[$r2['trans_id']]['pick_amount']; }else if($trans[$r2['trans_id']]['dep_type1']=='Burial') { $trans[$r2['trans_id']]['burial_amt']=$trans[$r2['trans_id']]['pick_amount']; }else if($trans[$r2['trans_id']]['dep_type1']=='Partner Bank') { $trans[$r2['trans_id']]['partner_amt']=$trans[$r2['trans_id']]['pick_amount']; } else if($trans[$r2['trans_id']]['dep_type1']=='Vault') { $trans[$r2['trans_id']]['valut_amt']=$trans[$r2['trans_id']]['pick_amount']; $trans[$r2['trans_id']]['dep_amount'] = 0; }
	
	
	   
	  // print_r($trans); die;
	    if($dep_accid!='') { 
		$depacc=implode(',',$dep_accid);
		   $sql_acc = "SELECT branch_name,account_no,bank_name FROM bank_master WHERE acc_id='".$trans[$r2['trans_id']]['dep_accid']."' AND status='Y'";
		   $qu_acc = mysqli_query($reportConnection,$sql_acc);
		   while($r_acc = mysqli_fetch_array($qu_acc))
		   {
		   $trans[$r2['trans_id']]['dep_branchs']=$r_acc['branch_name'];
		   $trans[$r2['trans_id']]['account_no']=$r_acc['account_no'];
		   $trans[$r2['trans_id']]['bank_name']=$r_acc['bank_name'];
		   }
	   }
		
			
			$shop_id_m=implode(',',$shop_id);
		//Prev. Day Vault Amount Desposit
		
		$sql_prev = mysqli_query($reportConnection,"SELECT b.dep_amount1,b.trans_id FROM daily_trans AS a INNER JOIN daily_collection AS b ON a.trans_id=b.trans_id
	WHERE a.pickup_code='".$trans[$r2['trans_id']]['shop_id']."' AND a.pickup_date='".$pday."'  AND b.dep_type1='Vault' AND a.`status`='Y' AND b.`status`='Y'");
		if(mysqli_num_rows($sql_prev)>0)
		{
			while($res_prev = mysqli_fetch_array($sql_prev))
			{
			$trans[$r2['trans_id']]['prev_vault']= $res_prev['dep_amount1'];
			}
		}
		}	
		
		if($group_name=='TTL') {
			$accidshop=implode(',',$acc_id_shop);
			$sql4="SELECT a.bank_code FROM all_banks AS a INNER JOIN bank_master AS b ON a.bank_name=b.bank_name WHERE  b.acc_id IN ('".$acc_id_shop."') AND a.status='Y' AND b.status='Y'";
			$qu4=mysqli_query($reportConnection,$sql4) or die(mysqli_error());
			$n4=mysqli_num_rows($qu4);
			while($r4=mysqli_fetch_array($qu4))
			{
			$bank_shortname = $r4['bank_code'];
			}
		}
		
		//echo $table_mul;
			
		
		$sql_m = mysqli_query($reportConnection,"SELECT trans_id,2000s,1000s,500s,200s,100s,50s,20s,10s,5s,coins,o1000s,o500s,rec_id, c_code, pick_amount, rec_no,  pis_hcl_no,hcl_no,cust_field1, mul_remarks,gen_slip FROM $table_mul WHERE trans_id IN (".$cu_trans_id.") AND status='Y' order by rec_id");
		
		 $nm=mysqli_num_rows($sql_m);
				while($rm = mysqli_fetch_assoc($sql_m)) {
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['c_code'] = !empty($rm['c_code'])?$rm['c_code']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['point_codes'] = !empty($rm['point_codes'])?$rm['point_codes']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['rec_no'] = !empty($rm['rec_no'])?$rm['rec_no']:"0";		
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['pis_hcl_no'] = !empty($rm['pis_hcl_no'])?$rm['pis_hcl_no']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['hcl_no'] = !empty($rm['hcl_no'])?$rm['hcl_no']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field1'] = !empty($rm['cust_field1'])?$rm['cust_field1']:"";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field2'] = !empty($rm['cust_field2'])?$rm['cust_field2']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field3'] = !empty($rm['cust_field3'])?$rm['cust_field3']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field4'] = !empty($rm['cust_field4'])?$rm['cust_field4']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field5'] = !empty($rm['cust_field5'])?$rm['cust_field5']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['pick_amount'] = !empty($rm['pick_amount'])?$rm['pick_amount']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['dep_slip'] = !empty($rm['dep_slip'])?$rm['dep_slip']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['2000s'] = !empty($rm['2000s'])?$rm['2000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['1000s'] = !empty($rm['1000s'])?$rm['1000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['500s'] = !empty($rm['500s'])?$rm['500s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['200s'] = !empty($rm['200s'])?$rm['200s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['100s'] = !empty($rm['100s'])?$rm['100s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['50s'] = !empty($rm['50s'])?$rm['50s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['20s'] = !empty($rm['20s'])?$rm['20s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['10s'] = !empty($rm['10s'])?$rm['10s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['5s'] = !empty($rm['5s'])?$rm['5s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['coins'] = !empty($rm['coins'])?$rm['coins']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['o1000s'] = !empty($rm['o1000s'])?$rm['o1000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['o500s'] = !empty($rm['o500s'])?$rm['o500s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['remark'] = !empty($rm['mul_remarks'])?$rm['mul_remarks']:"";
		
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['gen_slip'] = !empty($rm['gen_slip'])?$rm['gen_slip']:"0";	
				}
				
	}
					
		if(!empty($trans_id)){
	$i = 1;
	foreach($trans as $key4=>$val4) {
		
			$r = $val4;
			$r2 = $val4;
			$remark = $val4['remark'];
			$dep_bank_shop=$shop_dep_bank=!empty($val4['dep_bank_shop'])?$val4['dep_bank_shop']:"";
			$pickup_amount	=	!empty($r['pickup_amount'])?$r['pickup_amount']:"0";
			$pick_amount	=	!empty($r2['pick_amount'])?$r2['pick_amount']:"0";
			$acc_id_shop 	= 	$shop_acc_id	=	!empty($r['acc_id_shop'])?$r['acc_id_shop']:"";

			
		if($val4['customer_code']!='') $customer_code_m 	= 	explode(',', $val4['customer_code']);
			if($val4['shop_code']!='') $shop_code_m	 	=	explode(',', $val4['shop_code']);
			if($val4['client_code']!='') $client_code_m = explode(',', $val4['client_code']); else $client_code_m 	= 	explode(',', $val4['customer_code']);
			
	
			if($val4['valut_amt']!=''){ $valut_amt=$val4['valut_amt']; }else { $valut_amt=0;}
			if($val4['burial_amt']!=''){ $burial_amt=$val4['burial_amt']; }else { $burial_amt=0;}
		if($val4['dep_amount']!=0) { $dep_acc_no = $val4['subcustomer_code']; }else { $dep_acc_no = '-'; }
		$customer_code_m = explode(',',$val4['customer_code']);
		
		if($group_name=='Toll Plaza') {
						
			$sheet->setCellValue('A'.$row_no, $val4['zone_name']);
			$sheet->setCellValue('B'.$row_no, 'Radiant');
			$sheet->setCellValue('C'.$row_no, $val4['cust_name']);
			$sheet->setCellValue('D'.$row_no, $val4['shop_name']);
			$sheet->setCellValue('E'.$row_no, $val4['rec_no']);
			$sheet->setCellValue('F'.$row_no, $val4['hcl_no']);
			$sheet->setCellValue('G'.$row_no, $val4['pick_amount']);
			$sheet->setCellValue('H'.$row_no, $valut_amt);
			$sheet->setCellValue('I'.$row_no, $val4['dep_amount']);
			
			$sheet->setCellValue('J'.$row_no, $dep_acc_no);
			$sheet->setCellValue('K'.$row_no, $val4['dep_branch']);
			$sheet->setCellValue('L'.$row_no, '0');
			$sheet->setCellValue('M'.$row_no, !empty($val4['prev_vault'])?$val4['prev_vault']:"0");
			
			$sheet->setCellValue('N'.$row_no, $val4['account_no'].'-'.$val4['dep_branchs']);
			$sheet->setCellValue('O'.$row_no, $val4['remark']);
			$sheet->setCellValue('P'.$row_no, $val4['_2000s']);
			$sheet->setCellValue('Q'.$row_no, $val4['_1000s']);
			$sheet->setCellValue('R'.$row_no, $val4['_500s']);
			$sheet->setCellValue('S'.$row_no, $val4['_200s']);
			$sheet->setCellValue('T'.$row_no, $val4['_100s']);
			$sheet->setCellValue('U'.$row_no, $val4['_50s']);
			$sheet->setCellValue('V'.$row_no, $val4['_20s']);
			$sheet->setCellValue('W'.$row_no, $val4['_10s']);
			$sheet->setCellValue('X'.$row_no, $val4['_5s']);
			$sheet->setCellValue('Y'.$row_no, $val4['_coins']);
			$totals = '=(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.'*T4)+(U'.$row_no.'*U4)+(V'.$row_no.'*V4)+(W'.$row_no.'*W4)+(X'.$row_no.'*X4)+(Y'.$row_no.')';				
			$sheet->setCellValue('Z'.$row_no, $totals);
			$diff = '=G'.$row_no.'-Z'.$row_no;
			$sheet->setCellValue('AA'.$row_no, $diff);
			
			$sheet->setCellValue('AB'.$row_no, $val4['shop_id']);
			$sheet->setCellValue('AC'.$row_no, $val4['dep_bank_shop']);
			if($val4['acc_id_shop']!='') {
				$sheet->setCellValue('AD'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
			}
			$sheet->setCellValue('AE'.$row_no, $val4['dep_type1']);
			if($val4['dep_accid']!='') {
				$sheet->setCellValue('AF'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
			}
			$sheet->setCellValue('AG'.$row_no, $val4['point_type']);
			$sheet->setCellValue('AH'.$row_no, $val4['loi_date']);
			$sheet->setCellValue('AI'.$row_no, $val4['sdeact_date']);
			$sheet->setCellValue('AJ'.$row_no, $val4['inactive_date']);	
			$sheet->setCellValue('AK'.$row_no, $val4['shop_id']);	
			$row_no++;
			$i++;
		}
		elseif($group_name=="Group-1" || $group_name=="Group-3" || $group_name=="Group-2" || $group_name=="Group1-Airport Pickup"){
						
			$cust_rep = 1;
			if($i==1) {
				$alb = array('M', 'N', 'O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD');	
			}
			$cust_names = $val4['cust_name'];
			if($cust_names!=$cust_names1 && $i!=1) {
				$sheet->setCellValue('C'.$row_no, $cust_names1.' Total');		
				foreach($alb as $key=>$val){
					$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
			//echo $val4['multi_rec']; die;
			
			if($val4['agency_code']=="AXIS") $val4['agency_code']="UTI-BK";
			if($val4['multi_rec']=='Y') {
				$cu_trans_id = implode(',',$trans_id);
				foreach($val4['multi'] as $key5=>$val5) {
					
								
					$no_notes = '0';
					$no_notes = '2000X'.$val5['2000s'].',1000X'.$val5['1000s'].', 500X'.$val5['500s'].', 200X'.$val5['200s'].',100X'.$val5['100s'].', 50X'.$val5['50s'].', 20X'.$val5['20s'].', 10X'.$val5['10s'].', 5X'.$val5['5s'].', Coins X'.$val5['coins'].',1000X'.$val5['o1000s'].', 500X'.$val5['o500s'];
					
					$dep_slipno="";
					if($dep_slipno=="" && $val5['rec_no']!="0") { $dep_slipno=$val5['rec_no']; }
					if($val5['hcl_no']!="0" && $val5['hcl_no']!=''){if($dep_slipno=="") $dep_slipno=$val5['hcl_no']; else  $dep_slipno.="/".$val5['hcl_no'];}
					if($val4['dep_bank_shop']=='Partner Bank'){ $dbank="PB"; }else { $dbank=$val4['dep_bank_shop'];}
					
					$sheet->setCellValue('A'.$row_no, $i);
					$sheet->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
					$sheet->setCellValue('C'.$row_no, $val4['region_name']);
					$sheet->setCellValue('D'.$row_no, $val4['location']);
					$sheet->setCellValue('E'.$row_no, $val4['shop_name']);
					$sheet->setCellValue('F'.$row_no, $val4['shop_code']);
					$sheet->setCellValue('G'.$row_no, $val5['c_code']);
					$sheet->setCellValue('H'.$row_no, $val4['pickup_type']);
					$sheet->setCellValue('I'.$row_no, $dbank);
					if($val4['agency_code']!=''){ $agencycode=$val4['agency_code'];}else { $agencycode='NIL';}
					$sheet->setCellValue('J'.$row_no, $agencycode);
					$sheet->setCellValue('K'.$row_no, $val5['pis_hcl_no']);
					$sheet->setCellValue('L'.$row_no, $dep_slipno);
					if($key5==1) {
						$sheet->setCellValue('M'.$row_no, $val4['pickup_amount']);
					}
					else {
						$sheet->setCellValue('M'.$row_no, '0');
					}
					$sheet->setCellValue('N'.$row_no, !empty($val5['pick_amount'])?$val5['pick_amount']:"0");
					$sheet->setCellValue('O'.$row_no, '=M'.$row_no.'-N'.$row_no);
					if($val4['partner_amt']!='' && $val4['partner_amt']!=0) {
						$sheet->setCellValue('P'.$row_no, $val5['pick_amount']);
					}
					else
					{
						$sheet->setCellValue('P'.$row_no, '0');
					}
					$sheet->setCellValue('Q'.$row_no, !empty($val5['2000s'])?$val5['2000s']:"0");
					$sheet->setCellValue('R'.$row_no, !empty($val5['1000s'])?$val5['1000s']:"0");
					$sheet->setCellValue('S'.$row_no, !empty($val5['500s'])?$val5['500s']:"0");
					$sheet->setCellValue('T'.$row_no, !empty($val5['200s'])?$val5['200s']:"0");
					$sheet->setCellValue('U'.$row_no, !empty($val5['100s'])?$val5['100s']:"0");
					$sheet->setCellValue('V'.$row_no, !empty($val5['50s'])?$val5['50s']:"0");
					$sheet->setCellValue('W'.$row_no, !empty($val5['20s'])?$val5['20s']:"0");
					$sheet->setCellValue('X'.$row_no, !empty($val5['10s'])?$val5['10s']:"0");
					$sheet->setCellValue('Y'.$row_no, !empty($val5['5s'])?$val5['5s']:"0");
					$sheet->setCellValue('Z'.$row_no, !empty($val5['coins'])?$val5['coins']:"0");
					$sheet->setCellValue('AA'.$row_no, !empty($val5['o1000s'])?$val5['o1000s']:"0");
					$sheet->setCellValue('AB'.$row_no, !empty($val5['o500s'])?$val5['o500s']:"0");
					$totals = '=(Q'.$row_no.'*2000)+(R'.$row_no.'*1000)+(S'.$row_no.'*500)+(T'.$row_no.'*200)+(U'.$row_no.'*100)+(V'.$row_no.'*50)+(W'.$row_no.'*20)+(X'.$row_no.'*10)+(Y'.$row_no.'*5)+(Z'.$row_no.')+(AA'.$row_no.'*1000)+(AB'.$row_no.'*500)';				
					$sheet->setCellValue('AC'.$row_no, $totals);
			
					
					$diff = '=N'.$row_no.'-AC'.$row_no;
					$sheet->setCellValue('AD'.$row_no, $diff);
			
					
					
					$sheet->setCellValue('AE'.$row_no, $val5['remark']);
					$sheet->setCellValue('AF'.$row_no, !empty($val4['dep_slip'])?$val4['dep_slip']:"0");
					$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
					$sheet->setCellValue('AH'.$row_no, $val4['hierarchy_code']);
					$sheet->setCellValue('AI'.$row_no, $val4['div_code']);
					$sheet->setCellValue('AJ'.$row_no, $val4['pick_time']);
					$sheet->setCellValue('AK'.$row_no, $val4['sol_id']);
					if($group_name=="Group-1") {
					$sheet->getStyle('AK'.$row_no)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
					}
					$sheet->setCellValue('AL'.$row_no, $val4['dep_amount2']);
					$sheet->setCellValue('AM'.$row_no, $val4['dep_bank2']);
					$sheet->setCellValue('AN'.$row_no, $val4['other_remarks2']);
					
					$sheet->setCellValue('AO'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AP'.$row_no, $val4['dep_bank_shop']);
					if($val4['acc_id_shop']!='') {
						$sheet->setCellValue('AQ'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AR'.$row_no, $val4['dep_type1']);
					if($val4['dep_accid']!='') {
						$sheet->setCellValue('AS'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AT'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AU'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AV'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AW'.$row_no, $val4['inactive_date']);	
					$sheet->setCellValue('AX'.$row_no, $val4['shop_id']);	
					$sheet->setCellValue('AY'.$row_no, $val4['state']);	
					$sheet->setCellValue('AZ'.$row_no, $val4['pincode']);
					$sheet->setCellValue('BA'.$row_no, $val5['cust_field1']);	
					$row_no++;
					$i++;
				}
			}
			else {
				$dep_slipno="";
				
				if($dep_slipno==""&& $val4['rec_no']!="0") $dep_slipno=$val4['rec_no']; 
				if($val4['hcl_no']!="0" && $val4['hcl_no']!=''){if($dep_slipno=="") $dep_slipno=$val4['hcl_no']; else $dep_slipno.="/".$val4['hcl_no'];}
				
				
				$sheet->setCellValue('A'.$row_no, $i);
				$sheet->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
				$sheet->setCellValue('C'.$row_no, $val4['region_name']);
				$sheet->setCellValue('D'.$row_no, $val4['location']);
				$sheet->setCellValue('E'.$row_no, $val4['shop_name']);
				$sheet->setCellValue('F'.$row_no, $val4['shop_code']);
				$sheet->setCellValue('G'.$row_no, $val4['customer_code']);
				$sheet->setCellValue('H'.$row_no, $val4['pickup_type']);
				
				if($val4['dep_bank_shop']=='Partner Bank'){ $dbank="PB"; }else { $dbank=$val4['dep_bank_shop'];}
				
				$sheet->setCellValue('I'.$row_no, $dbank);
				if($val4['agency_code']!=''){ $agencycode=$val4['agency_code'];}else { $agencycode='NIL';}
				$sheet->setCellValue('J'.$row_no, $agencycode);
				$sheet->setCellValue('K'.$row_no, $val4['pis_hcl_no']);
				
				//if($dep_slipno==""&& $val4['rec_no']!="") { $dep_slipno=$val4['rec_no']; }else if($dep_slipno==""&& $val4['hcl_no']!=""){$dep_slipno=$val4['hcl_no']; }
				
				$sheet->setCellValue('L'.$row_no, $dep_slipno);
				$sheet->setCellValue('M'.$row_no, $val4['pickup_amount']);
				
				$sheet->setCellValue('N'.$row_no, !empty($val4['pick_amount'])?$val4['pick_amount']:"0");
				$sheet->setCellValue('O'.$row_no, '=M'.$row_no.'-N'.$row_no);
				$sheet->setCellValue('P'.$row_no, !empty($val4['partner_amt'])?$val4['partner_amt']:"0");
				$sheet->setCellValue('Q'.$row_no, !empty($val4['_2000s'])?$val4['_2000s']:"0");
			$sheet->setCellValue('R'.$row_no, !empty($val4['_1000s'])?$val4['_1000s']:"0");
			$sheet->setCellValue('S'.$row_no, !empty($val4['_500s'])?$val4['_500s']:"0");
			$sheet->setCellValue('T'.$row_no, !empty($val4['_200s'])?$val4['_200s']:"0");
			$sheet->setCellValue('U'.$row_no, !empty($val4['_100s'])?$val4['_100s']:"0");
			$sheet->setCellValue('V'.$row_no, !empty($val4['_50s'])?$val4['_50s']:"0");
			$sheet->setCellValue('W'.$row_no, !empty($val4['_20s'])?$val4['_20s']:"0");
			$sheet->setCellValue('X'.$row_no, !empty($val4['_10s'])?$val4['_10s']:"0");
			$sheet->setCellValue('Y'.$row_no, !empty($val4['_5s'])?$val4['_5s']:"0");
			$sheet->setCellValue('Z'.$row_no, !empty($val4['_coins'])?$val4['_coins']:"0");
			$sheet->setCellValue('AA'.$row_no, !empty($val4['_o1000s'])?$val4['_o1000s']:"0");
			$sheet->setCellValue('AB'.$row_no, !empty($val4['_o500s'])?$val4['_o500s']:"0");
			$totals = '=(Q'.$row_no.'*2000)+(R'.$row_no.'*1000)+(S'.$row_no.'*500)+(T'.$row_no.'*200)+(U'.$row_no.'*100)+(V'.$row_no.'*50)+(W'.$row_no.'*20)+(X'.$row_no.'*10)+(Y'.$row_no.'*5)+(Z'.$row_no.')+(AA'.$row_no.'*1000)+(AB'.$row_no.'*500)';		
			$sheet->setCellValue('AC'.$row_no, $totals);
			$diff = '=N'.$row_no.'-AC'.$row_no;
			$sheet->setCellValue('AD'.$row_no, $diff);
				
				
				
				$sheet->setCellValue('AE'.$row_no, $remark);
				$sheet->setCellValue('AF'.$row_no, !empty($val4['dep_slip'])?$val4['dep_slip']:"0");
				$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
				$sheet->setCellValue('AH'.$row_no, $val4['hierarchy_code']);
				$sheet->setCellValue('AI'.$row_no, $val4['div_code']);
				$sheet->setCellValue('AJ'.$row_no, $val4['pick_time']);
				$sheet->setCellValue('AK'.$row_no, $val4['sol_id']);
				if($group_name=="Group-1") {
					$sheet->getStyle('AK'.$row_no)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
					}
				$sheet->setCellValue('AL'.$row_no, $val4['dep_amount2']);
				$sheet->setCellValue('AM'.$row_no, $val4['dep_bank2']);
				$sheet->setCellValue('AN'.$row_no, $val4['other_remarks2']);
				
				$sheet->setCellValue('AO'.$row_no, $val4['shop_id']);
				$sheet->setCellValue('AP'.$row_no, $val4['dep_bank_shop']);
				if($val4['acc_id_shop']!='') {
					$sheet->setCellValue('AQ'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
				}
				$sheet->setCellValue('AR'.$row_no, $val4['dep_type1']);
				if($val4['dep_accid']!='') {
					$sheet->setCellValue('AS'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
				}
				$sheet->setCellValue('AT'.$row_no, $val4['point_type']);
				$sheet->setCellValue('AU'.$row_no, $val4['loi_date']);
				$sheet->setCellValue('AV'.$row_no, $val4['sdeact_date']);
				$sheet->setCellValue('AW'.$row_no, $val4['inactive_date']);
				$sheet->setCellValue('AX'.$row_no, $val4['shop_id']);	
				$sheet->setCellValue('AY'.$row_no, $val4['state']);	
				$sheet->setCellValue('AZ'.$row_no, $val4['pincode']);	
				$sheet->setCellValue('BA'.$row_no, $val4['cust_field1']);
				$row_no++;
				$i++;
			}
			$cust_names1 = $val4['cust_name'];
			
		}		
		else if($group_name=='KOTAK MAHINDRA BANK') {
			if($i==1) {
				$alb = array('F','G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'T', 'U', 'V', 'Y');	
			}
			$zones = $val4['zone_name'];
			if($zones!=$zones1 && $i!=1) {
				$sheet->setCellValue('C'.$row_no, $zones1.' Total');		
				foreach($alb as $key=>$val){
					$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$sheet->getStyle('A'.$row_no.':Y'.$row_no)->getFont()->setBold(true);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
			if($val4['dep_type1']=='Client Bank' || $val4['dep_type1']=='Partner Bank') { $kotak_dep = $val4['pick_amount']; } 
			
			if($val4['multi_rec']=='Y') {
				$s = 0;
				$sql_m = mysqli_query($reportConnection,"SELECT rec_id, pick_amount, 2000s,1000s, 500s, 100s, 50s, 20s, 10s, 5s, coins, rec_no, hcl_no, gen_slip, pis_hcl_no, mul_remarks FROM daily_collectionmul WHERE trans_id='".$r['trans_id']."' AND status='Y'");
				while($rm = mysqli_fetch_assoc($sql_m)) {
					$sheet->setCellValue('A'.$row_no, $val4['zone_name']);
					$sheet->setCellValue('B'.$row_no, $val4['shop_name']);
					$sheet->setCellValue('C'.$row_no, $val4['address']);
					$sheet->setCellValue('D'.$row_no, $val4['cust_name']);
					$sheet->setCellValue('E'.$row_no, $customer_code_m[$s]);
					$sheet->setCellValue('F'.$row_no, $rm['pick_amount']);
					$sheet->setCellValue('G'.$row_no, $rm['2000s']);
					$sheet->setCellValue('H'.$row_no, $rm['1000s']);
					$sheet->setCellValue('I'.$row_no, $rm['500s']);
					$sheet->setCellValue('J'.$row_no, $rm['100s']);
					$sheet->setCellValue('K'.$row_no, $rm['50s']);
					$sheet->setCellValue('L'.$row_no, $rm['20s']);
					$sheet->setCellValue('M'.$row_no, $rm['10s']);
					$sheet->setCellValue('N'.$row_no, $rm['5s']);
					$sheet->setCellValue('O'.$row_no, $rm['coins']);
					$totals = '=(G'.$row_no.'*G4)+(H'.$row_no.'*H4)+(I'.$row_no.'*I4)+(J'.$row_no.'*J4)+(K'.$row_no.'*K4)+(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.')';				
					$sheet->setCellValue('P'.$row_no, $totals);
					$sheet->setCellValue('Q'.$row_no, $rm['rec_no']);
					$sheet->setCellValue('R'.$row_no, $rm['hcl_no']);
					$sheet->setCellValue('S'.$row_no, $rm['gen_slip']);
					$sheet->setCellValue('T'.$row_no, $rm['pis_hcl_no']);
					if($rm['rec_id']==1) {
						$sheet->setCellValue('U'.$row_no, $kotak_dep);
						$sheet->setCellValue('V'.$row_no, $valut_amt);
						$sheet->setCellValue('W'.$row_no, $burial_amt);
					}
					else {
						$sheet->setCellValue('U'.$row_no, '0');
						$sheet->setCellValue('V'.$row_no, '0');
						$sheet->setCellValue('W'.$row_no, '0');
							
					}
					if($dep_accid!='0') { 
						$sheet->setCellValue('X'.$row_no, $val4['bank_name'].' & '.$val4['account_no']);
					}
					$sheet->setCellValue('Y'.$row_no, $val4['dep_branchs']);
					$sheet->setCellValue('Z'.$row_no, $val4['prev_vault']);
					$sheet->setCellValue('AA'.$row_no, $rm['mul_remarks']);
					$sheet->setCellValue('AB'.$row_no, $val4['pickup_type']);
					$sheet->setCellValue('AC'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AD'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AE'.$row_no, $val4['sact_date']);
					$sheet->setCellValue('AF'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
					$sheet->setCellValue('AH'.$row_no, $val4['shop_id']);
					$row_no++;
					$i++;
					$s++;
				}
			}
			else {
				foreach($customer_code_m as $key=>$val) {
					$sheet->setCellValue('A'.$row_no, $val4['zone_name']);
					$sheet->setCellValue('B'.$row_no, $val4['shop_name']);
					$sheet->setCellValue('C'.$row_no, $val4['address']);
					$sheet->setCellValue('D'.$row_no, $val4['cust_name']);
					$sheet->setCellValue('E'.$row_no, $val);
					$sheet->setCellValue('F'.$row_no, $val4['pick_amount']);
					$sheet->setCellValue('G'.$row_no, $val4['_2000s']);
					$sheet->setCellValue('H'.$row_no, $val4['_1000s']);
					$sheet->setCellValue('I'.$row_no, $val4['_500s']);
					$sheet->setCellValue('J'.$row_no, $val4['_100s']);
					$sheet->setCellValue('K'.$row_no, $val4['_50s']);
					$sheet->setCellValue('L'.$row_no, $val4['_20s']);
					$sheet->setCellValue('M'.$row_no, $val4['_10s']);
					$sheet->setCellValue('N'.$row_no, $val4['5s']);
					$sheet->setCellValue('O'.$row_no, $val4['_coins']);
					$totals = '=(G'.$row_no.'*G4)+(H'.$row_no.'*H4)+(I'.$row_no.'*I4)+(J'.$row_no.'*J4)+(K'.$row_no.'*K4)+(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.')';				
					$sheet->setCellValue('P'.$row_no, $totals);
					$sheet->setCellValue('Q'.$row_no, $val4['rec_no']);
					$sheet->setCellValue('R'.$row_no, $val4['hcl_no']);
					$sheet->setCellValue('S'.$row_no, $val4['gen_slip']);
					$sheet->setCellValue('T'.$row_no, $val4['pis_hcl_no']);
					$sheet->setCellValue('U'.$row_no, $kotak_dep);
					$sheet->setCellValue('V'.$row_no, $valut_amt);
					$sheet->setCellValue('W'.$row_no, $burial_amt);
					if($dep_accid!='0') { 
						$sheet->setCellValue('X'.$row_no, $val4['bank_name'].' & '.$val4['dep_acc_no']);
					}
					$sheet->setCellValue('Y'.$row_no, $val4['dep_branchs']);
					$sheet->setCellValue('Z'.$row_no, $val4['prev_vault']);
					$sheet->setCellValue('AA'.$row_no, $val4['remark']);
					$sheet->setCellValue('AB'.$row_no, $val4['pickup_type']);
					$sheet->setCellValue('AC'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AD'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AE'.$row_no, $val4['sact_date']);
					$sheet->setCellValue('AF'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
					$sheet->setCellValue('AH'.$row_no, $val4['shop_id']);
					$row_no++;
					$i++;
				}
			}
			$zones1 = $val4['zone_name'];
		}
		else if($group_name=='TTL') {
			$cust_rep = 1;
			$agency_code = 'NILL';
			
			if($val4['dep_type1']=='Burial') { $agency_code = 'RCMSMUM'; }
			else if($val4['dep_type1']=='Partner Bank') { 
			if($bank_shortname=='BOB' || $bank_shortname=='INDUS') { $agency_code =  $bank_shortname; } 
			else if($bank_shortname=='AXIS') {
				$agency_code =  'UTI-BK';
			}
			else {
				$agency_code =  $bank_shortname.'CASH';
			}
			}
						
			if($i==1) {
				$alb = array('M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA','AB','AC','AC');	
			}
			$cust_names = $val4['cust_name'];
			if($cust_names!=$cust_names1 && $i!=1) {
				$sheet->setCellValue('D'.$row_no, $cust_names1.' Total');		
				foreach($alb as $key=>$val){
					$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
				
			
			if($val4['multi_rec']=='Y') {
				$s = 0;
				$ttl_val[1];
				
				
				foreach($client_code_m as $keys=>$val) {
					$ttl_val1 = explode('-', $val);
					$ttl_val2[$ttl_val1[0]] = $ttl_val1[1];
				}
					
				foreach($val4['multi'] as $key5=>$val5) {
					
					$ttl_val = $ttl_val2[$val5['c_code']];
					$sheet->setCellValue('A'.$row_no, $i);
					$sheet->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
					$sheet->setCellValue('C'.$row_no, $val4['region_name']);
					$sheet->setCellValue('D'.$row_no, $val4['location']);
					$sheet->setCellValue('E'.$row_no, $val4['address']);
					$sheet->setCellValue('F'.$row_no, $val4['shop_code']);
					$sheet->setCellValue('G'.$row_no, $val5['c_code']);
					$sheet->setCellValue('H'.$row_no, $val4['pickup_type']);
					if($n2>0) {
						//echo $points_type[$val4['dep_type1']]; die;
						$sheet->setCellValue('I'.$row_no, !empty($val4['dep_type1'])?$points_type[$val4['dep_type1']]:"NIL");
					}
					else {
						//echo $points_type[$val4['dep_bank_shop']]; die;
						$sheet->setCellValue('I'.$row_no, !empty($val4['dep_bank_shop'])?$points_type[$val4['dep_bank_shop']]:"NIL");
					}
					
					$sheet->setCellValue('J'.$row_no, $val4['agency_code']);
					$sheet->setCellValue('K'.$row_no, $val5['pis_hcl_no']);
					$sheet->setCellValue('L'.$row_no, $val5['rec_no']);
					$sheet->setCellValue('M'.$row_no, $ttl_val);
					$sheet->setCellValue('N'.$row_no, $val5['pick_amountrm']);
					$sheet->setCellValue('O'.$row_no, $ttl_val-$val5['pick_amountrm']);
					if($val4['dep_type1']=='Partner Bank') {
						$sheet->setCellValue('P'.$row_no, $val5['pick_amountrm']);		
					}
					else {
						$sheet->setCellValue('P'.$row_no, '0');
					}
					$sheet->setCellValue('Q'.$row_no, $val5['2000s']);
					$sheet->setCellValue('R'.$row_no, $val5['1000s']);
					$sheet->setCellValue('S'.$row_no, $val5['500s']);
					$sheet->setCellValue('T'.$row_no, $val5['200s']);
					$sheet->setCellValue('U'.$row_no, $val5['100s']);
					$sheet->setCellValue('V'.$row_no, $val5['50s']);
					$sheet->setCellValue('W'.$row_no, $val5['20s']);
					$sheet->setCellValue('X'.$row_no, $val5['10s']);
					$sheet->setCellValue('Y'.$row_no, $val5['5s']);
					$sheet->setCellValue('Z'.$row_no, $val5['coins']);
					$sheet->setCellValue('AA'.$row_no, $val5['o1000s']);
					$sheet->setCellValue('AB'.$row_no, $val5['o500s']);
					$totals = '=(Q'.$row_no.'*2000)+(R'.$row_no.'*1000)+(S'.$row_no.'*500)+(T'.$row_no.'*200)+(U'.$row_no.'*100)+(V'.$row_no.'*50)+(W'.$row_no.'*20)+(X'.$row_no.'*10)+(Y'.$row_no.'*5)+(Z'.$row_no.')+(AA'.$row_no.'*1000)+(AB'.$row_no.'*500)';		
					$sheet->setCellValue('AC'.$row_no, $totals);
			
					
					$diff = '=N'.$row_no.'-AC'.$row_no;
					$sheet->setCellValue('AD'.$row_no, $diff);
			
					
					
					
					if($ttl_val>0) {
						$sheet->setCellValue('AE'.$row_no, $val5['mul_remarks']);
					}
					else {
						$sheet->setCellValue('AE'.$row_no ,'No Request');
					}
					if($val4['dep_type1']=='Partner Bank' && $val5['pick_amountrm']>0)  {
						$sheet->setCellValue('AF'.$row_no, $val4['dep_slip']);
					}
					$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
					$sheet->setCellValue('AH'.$row_no, $val4['hierarchy_code']);
					$sheet->setCellValue('AI'.$row_no, $val4['div_code']);
					
					$sheet->setCellValue('AJ'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AK'.$row_no, $val4['dep_bank_shop']);
					if($shop_acc_id!='') {
						$sheet->setCellValue('AL'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AM'.$row_no, $val4['dep_type1']);
					if($dep_accid!='') {
						$sheet->setCellValue('AN'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AO'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AP'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AQ'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AR'.$row_no, $val4['inactive_date']);	
					$sheet->setCellValue('AS'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AT'.$row_no, $val5['cust_field1']);
					$row_no++;
					$i++;
					$s++;
				}
			}
			else {
				foreach($client_code_m as $key=>$val) {
					//print_r($val);exit;
					$ttl_val = explode('-', $val);
					
					$sheet->setCellValue('A'.$row_no, $i);
					$sheet->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
					$sheet->setCellValue('C'.$row_no, $val4['region_name']);
					$sheet->setCellValue('D'.$row_no, $val4['location']);
					$sheet->setCellValue('E'.$row_no, $val4['address']);
					$sheet->setCellValue('F'.$row_no, $val4['shop_code']);
					$sheet->setCellValue('G'.$row_no, $ttl_val[0]);
					$sheet->setCellValue('H'.$row_no, $val4['pickup_type']);
					if($n2>0) {
						//$sheet->setCellValue('I'.$row_no, !empty($val4['dep_type1'])?$points_type[$val4['dep_type1']]:"NIL");
						$sheet->setCellValue('I'.$row_no, !empty($val4['dep_bank_shop'])?$points_type[$val4['dep_bank_shop']]:"NIL");
					}
					else {
						//$sheet->setCellValue('I'.$row_no, !empty($val4['dep_bank_shop'])?$points_type[$val4['dep_bank_shop']]:"NIL");
						$sheet->setCellValue('I'.$row_no, !empty($val4['dep_type1'])?$points_type[$val4['dep_type1']]:"NIL");
					}
					$sheet->setCellValue('J'.$row_no, $agency_code);
					$sheet->setCellValue('K'.$row_no, $val4['pis_hcl_no']);
					$sheet->setCellValue('L'.$row_no, $val4['rec_no']);
					if($ttl_val[1]!='' && count($ttl_val)!=1) {
						$request_amt = $ttl_val[1];
					}else {
						$request_amt = $pickup_amount;
						
					}
					$sheet->setCellValue('M'.$row_no, $request_amt);
					$sheet->setCellValue('N'.$row_no, $pick_amount);
					$sheet->setCellValue('O'.$row_no, $request_amt-$pick_amount);
					if($val4['dep_type1']=='Partner Bank') {
						$sheet->setCellValue('P'.$row_no, $pick_amount);
					}
					else {
						$sheet->setCellValue('P'.$row_no, '0');
					}
					
					$_2000s=!empty($val4['_2000s'])?$val4['_2000s']:"0";
					$_1000s=!empty($val4['_1000s'])?$val4['_1000s']:"0";
					$_500s=!empty($val4['_500s'])?$val4['_500s']:"0";
					$_200s=!empty($val4['_200s'])?$val4['_200s']:"0";
					$_100s=!empty($val4['_100s'])?$val4['_100s']:"0";
					$_50s=!empty($val4['_50s'])?$val4['_50s']:"0";
					$_20s=!empty($val4['_20s'])?$val4['_20s']:"0";
					$_10s=!empty($val4['_10s'])?$val4['_10s']:"0";
					$_5s=!empty($val4['_5s'])?$val4['_5s']:"0";
					$_coins=!empty($val4['_coins'])?$val4['_coins']:"0";
					$_o1000s=!empty($val4['_o1000s'])?$val4['_o1000s']:"0";
					$_o500s=!empty($val4['_o500s'])?$val4['_o500s']:"0";
					
			$sheet->setCellValue('Q'.$row_no,$_2000s); 
			$sheet->setCellValue('R'.$row_no, $_1000s);
			$sheet->setCellValue('S'.$row_no, $_500s);
			$sheet->setCellValue('T'.$row_no, $_200s);
			$sheet->setCellValue('U'.$row_no, $_100s);
			$sheet->setCellValue('V'.$row_no, $_50s);
			$sheet->setCellValue('W'.$row_no, $_20s);
			$sheet->setCellValue('X'.$row_no, $_10s);
			$sheet->setCellValue('Y'.$row_no, $_5s);
			$sheet->setCellValue('Z'.$row_no, $_coins);
			$sheet->setCellValue('AA'.$row_no, $_o1000s);
			$sheet->setCellValue('AB'.$row_no, $_o500s);
			$totals = '=(Q'.$row_no.'*2000)+(R'.$row_no.'*1000)+(S'.$row_no.'*500)+(T'.$row_no.'*200)+(U'.$row_no.'*100)+(V'.$row_no.'*50)+(W'.$row_no.'*20)+(X'.$row_no.'*10)+(Y'.$row_no.'*5)+(Z'.$row_no.')+(AA'.$row_no.'*1000)+(AB'.$row_no.'*500)';				
			$sheet->setCellValue('AC'.$row_no, $totals);
			$diff = '=N'.$row_no.'-AC'.$row_no;
			$sheet->setCellValue('AD'.$row_no, $diff);
					
					
					if($request_amt > 0) {
						$sheet->setCellValue('AE'.$row_no, $remark);
					}
					else {
						$sheet->setCellValue('AE'.$row_no ,'No Request');
					}
					if($val4['dep_type1']=='Partner Bank' && $val4['pick_amount']>0)  {
						$sheet->setCellValue('AF'.$row_no, $val4['dep_slip']);
					}
					$sheet->setCellValue('AG'.$row_no, $val4['cash_limit']);
					$sheet->setCellValue('AH'.$row_no, $val4['hierarchy_code']);
					$sheet->setCellValue('AI'.$row_no, $val4['div_code']);
					
					$sheet->setCellValue('AJ'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AK'.$row_no, $val4['dep_bank_shop']);
					if($shop_acc_id!='') {
						$sheet->setCellValue('AL'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AM'.$row_no, $val4['dep_type1']);
					if($val4['dep_accid']!='') {
						$sheet->setCellValue('AN'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AO'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AP'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AQ'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AR'.$row_no, $val4['inactive_date']);
					$sheet->setCellValue('AS'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AT'.$row_no, $val4['cust_field1']);
					$row_no++;
					$i++;
				}
			}
			$zones1 = $val4['zone_name'];
			$cust_names1 = $val4['cust_name'];
		}
		else if($group_name=='Radiant Consol' || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup" || $group_name=="VFS Migration" || $group_name=="Other Migration" || $group_name=="SCB-HYUNDAI") {
			
			if($i==1) {
				if($group_name=="VFS Migration" ) {
					$alb = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','W','AE','AG');	
				}
				else {
					$alb = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','W','AD','AF');
				}
			}
			$region_names = $val4['region_name'];
			$cust_names = $val4['cust_name'];
			if($group_name=="VFS Migration") {
				if($cust_names!=$cust_names1 && $i!=1) {
					$sheet->setCellValue('C'.$row_no, $region_names1.' Total');		
					foreach($alb as $key=>$val){
						$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
					}
					$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
					$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
					$gtotal_rows[]= $row_no;				
					$row_no++;
					$start_rows = $row_no;
				}

			}
			else {
				$cust_rep = 1;
				if($cust_names!=$cust_names1 && $i!=1) {
					$sheet->setCellValue('C'.$row_no, $cust_names1.' Total');		
					foreach($alb as $key=>$val){
						$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
					}
					$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
					$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
					$gtotal_rows[]= $row_no;				
					$row_no++;
					$start_rows = $row_no;
				}
			}
			
		
			if($val4['multi_rec']=='Y') {
				
				foreach($val4['multi'] as $key5=>$val5) {
					$sheet->setCellValue('A'.$row_no, 'RADIANT');
					$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
					$sheet->setCellValue('C'.$row_no, $val4['location']);
					$sheet->setCellValue('D'.$row_no, $val4['loc_code']);
					$sheet->setCellValue('E'.$row_no, $val4['customer_code']);
					$sheet->setCellValue('F'.$row_no, $val4['cust_name']);
					$sheet->setCellValue('G'.$row_no, $val4['shop_name']);

					if($val4['cust_id'] == "7784" || $val4['cust_id'] == "4489")
					{
						$sheet->setCellValueExplicit('H'.$row_no, $val4['shop_code'], PHPExcel_Cell_DataType::TYPE_STRING);
					}
					else
					{
						$sheet->setCellValue('H'.$row_no, $val4['shop_code']);
					}

					$sheet->setCellValue('I'.$row_no, $val5['rec_no']);
					$sheet->setCellValue('J'.$row_no, $val4['pickup_amount']);
					$sheet->setCellValue('K'.$row_no, !empty($val5['pick_amount'])?$val5['pick_amount']:"0");
					
					// Denomination changed on 11-11-2016
					$sheet->setCellValue('L'.$row_no, !empty($val5['2000s'])?$val5['2000s']:"0");
					$sheet->setCellValue('M'.$row_no, !empty($val5['1000s'])?$val5['1000s']:"0");
					$sheet->setCellValue('N'.$row_no, !empty($val5['500s'])?$val5['500s']:"0");
					$sheet->setCellValue('O'.$row_no, !empty($val5['200s'])?$val5['200s']:"0");
					$sheet->setCellValue('P'.$row_no, !empty($val5['100s'])?$val5['100s']:"0");
					$sheet->setCellValue('Q'.$row_no, !empty($val5['50s'])?$val5['50s']:"0");
					$sheet->setCellValue('R'.$row_no, !empty($val5['20s'])?$val5['20s']:"0");
					$sheet->setCellValue('S'.$row_no, !empty($val5['10s'])?$val5['10s']:"0");
					$sheet->setCellValue('T'.$row_no, !empty($val5['5s'])?$val5['5s']:"0");
					$sheet->setCellValue('U'.$row_no, !empty($val5['coins'])?$val5['coins']:"0");
					$totals = '=(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.'*T4)+(U'.$row_no.')';	
					$sheet->setCellValue('V'.$row_no, $totals);
					$sheet->setCellValue('W'.$row_no, '=K'.$row_no.'-V'.$row_no);
					
					$sheet->setCellValue('X'.$row_no, $val5['pis_hcl_no']);
					if($group_name=="VFS Migration" ) {
						$sheet->setCellValue('Y'.$row_no, $val5['hcl_no']);
						$sheet->setCellValue('Z'.$row_no, $val5['gen_slip']);
						
						//$sheet->setCellValue('AA'.$row_no, $val5['hcl_no']);
						$sheet->setCellValue('AA'.$row_no, '');
						$sheet->setCellValue('AB'.$row_no, '');
						$sheet->setCellValue('AC'.$row_no, $val5['remark']);
						if($key5==1) {
							if($val4['dep_type1']!='Burial' && $val4['dep_type1']!='')
							{
								$sheet->setCellValue('AD'.$row_no, $val4['pick_amount']);
							}
						}
						$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
						if($key5==1) {
							$sheet->setCellValue('AF'.$row_no, $val4['dep_amount2']);
							$sheet->setCellValue('AG'.$row_no, $val4['dep_bank2']);
							$sheet->setCellValue('AH'.$row_no, $val4['other_remarks2']);
						}
						
						$sheet->setCellValue('AI'.$row_no, $val4['shop_id']);
						$sheet->setCellValue('AJ'.$row_no, $val4['dep_bank_shop']);
						if($shop_acc_id!='') {
							$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
						}
						$sheet->setCellValue('AL'.$row_no, $val4['dep_type1']);
						if($val4['dep_accid']!='') {
							$sheet->setCellValue('AM'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
						}
						$sheet->setCellValue('AN'.$row_no, $val4['point_type']);
						$sheet->setCellValue('AO'.$row_no, $val4['loi_date']);
						$sheet->setCellValue('AP'.$row_no, $val4['sdeact_date']);
						$sheet->setCellValue('AQ'.$row_no, $val4['inactive_date']);
						$sheet->setCellValue('AR'.$row_no, $val4['cust_field1']);
						
					}
					else {
						$sheet->setCellValue('Y'.$row_no, $val5['hcl_no']);
						$sheet->setCellValue('Z'.$row_no, $val5['gen_slip']);
						
						$sheet->setCellValue('AA'.$row_no, '');
						$sheet->setCellValue('AB'.$row_no, '');
						$sheet->setCellValue('AC'.$row_no, $val5['remark']);
						//if($rm['rec_id']==1) {
							/*if($dep_type1=='Vault')
							{
							$sheet->setCellValue('AA'.$row_no, $valut_amt);
							}
							else
							{
								$sheet->setCellValue('AA'.$row_no, $partner_amt);
							}*/
							if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
							{
							$sheet->setCellValue('AD'.$row_no, $val5['pick_amount']);
							}
						//}
						$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
						if($key5==1) {
							$sheet->setCellValue('AF'.$row_no, $val4['dep_amount2']);
							$sheet->setCellValue('AG'.$row_no, $val4['$dep_bank2']);
							$sheet->setCellValue('AH'.$row_no, $val4['other_remarks2']);
						}
						if($group_name=='Radiant Consol' || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup") {
							$sheet->setCellValue('AI'.$row_no, $val4['dep_bank_shop']);
							$sheet->setCellValue('AJ'.$row_no, $val4['shop_id']);
							$sheet->setCellValue('AK'.$row_no, $val4['dep_bank_shop']);
							if($shop_acc_id!='') {
								$sheet->setCellValue('AL'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
							}
							$sheet->setCellValue('AM'.$row_no, $val4['dep_type1']);
							if($val4['dep_accid']!='') {
								$sheet->setCellValue('AN'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
							}
							$sheet->setCellValue('AO'.$row_no, $val4['point_type']);
							$sheet->setCellValue('AP'.$row_no, $val4['loi_date']);
							$sheet->setCellValue('AQ'.$row_no, $val4['sdeact_date']);
							$sheet->setCellValue('AR'.$row_no, $val4['inactive_date']);
							if($group_name=='Radiant Consol' || $group_name == 'Consol Evening Pickup')
							{   
									if(!empty($val4['cityname']))
									{
									//$sheet->setCellValue('AS'.$row_no, $val4['district']);
									$sheet->setCellValue('AS'.$row_no,$district_all[$val4["cityname"]]['district']);
	
									}
									else{
										$sheet->setCellValue('AS'.$row_no,"0");
	
									}	
								
							    
						    }
					    }
						else {
							$sheet->setCellValue('AI'.$row_no, $val4['shop_id']);
							$sheet->setCellValue('AJ'.$row_no, $val4['dep_bank_shop']);
							if($shop_acc_id!='') {
								$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
							}
							$sheet->setCellValue('AL'.$row_no, $val4['dep_type1']);
							if($val4['dep_accid']!='') {
								$sheet->setCellValue('AM'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
							}
							$sheet->setCellValue('AN'.$row_no, $val4['point_type']);
							$sheet->setCellValue('AO'.$row_no, $val4['loi_date']);
							$sheet->setCellValue('AP'.$row_no, $val4['sdeact_date']);
							$sheet->setCellValue('AQ'.$row_no, $val4['inactive_date']);	
							if($group_name == "SCB-HYUNDAI" ||  $group_name == "Other Migration")
							{
								if(!empty($val4["cityname"]))
								{
									//$sheet->setCellValue('AR'.$row_no,$val4['district']);	
									$sheet->setCellValue('AR'.$row_no,$district_all[$val4["cityname"]]['district']);


								}
								else{
									$sheet->setCellValue('AR'.$row_no,"0");	

								}
							}
						}
					}
					
					$row_no++;
					$i++;
				}
			
			}
			else {
				$sheet->setCellValue('A'.$row_no, 'RADIANT');
				$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
				$sheet->setCellValue('C'.$row_no, $val4['location']);
				$sheet->setCellValue('D'.$row_no, $val4['loc_code']);
				$sheet->setCellValue('E'.$row_no, $val4['customer_code']);
				$sheet->setCellValue('F'.$row_no, $val4['cust_name']);
				$sheet->setCellValue('G'.$row_no, $val4['shop_name']);

				if($val4['cust_id'] == "7784" || $val4['cust_id'] == "4489")
				{
					$sheet->setCellValueExplicit('H'.$row_no, $val4['shop_code'], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				else
				{
					$sheet->setCellValue('H'.$row_no, $val4['shop_code']);
				}
				
				$sheet->setCellValue('I'.$row_no, $val4['rec_no']);
				$sheet->setCellValue('J'.$row_no, $val4['pickup_amount']);
				$sheet->setCellValue('K'.$row_no, !empty($val4['pick_amount'])?$val4['pick_amount']:"0");
				
				// Denomination changed on 11-11-2016
				$sheet->setCellValue('L'.$row_no, !empty($val4['_2000s'])?$val4['_2000s']:"0");
				$sheet->setCellValue('M'.$row_no, !empty($val4['_1000s'])?$val4['_1000s']:"0");
				$sheet->setCellValue('N'.$row_no, !empty($val4['_500s'])?$val4['_500s']:"0");
				$sheet->setCellValue('O'.$row_no, !empty($val4['_200s'])?$val4['_200s']:"0");
				$sheet->setCellValue('P'.$row_no, !empty($val4['_100s'])?$val4['_100s']:"0");
				$sheet->setCellValue('Q'.$row_no, !empty($val4['_50s'])?$val4['_50s']:"0");
				$sheet->setCellValue('R'.$row_no, !empty($val4['_20s'])?$val4['_20s']:"0");
				$sheet->setCellValue('S'.$row_no, !empty($val4['_10s'])?$val4['_10s']:"0");
				$sheet->setCellValue('T'.$row_no, !empty($val4['_5s'])?$val4['_5s']:"0");
				$sheet->setCellValue('U'.$row_no, !empty($val4['_coins'])?$val4['_coins']:"0");
				$totals = '=(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.'*T4)+(U'.$row_no.')';
				$sheet->setCellValue('V'.$row_no, $totals);
				$sheet->setCellValue('W'.$row_no, '=K'.$row_no.'-V'.$row_no);
				
				$sheet->setCellValue('X'.$row_no, $val4['pis_hcl_no']);
				if($group_name=="VFS Migration" ) {
					$sheet->setCellValue('Y'.$row_no, $val4['hcl_no']);
					$sheet->setCellValue('Z'.$row_no, $val4['gen_slip']);
					
					//$sheet->setCellValue('AA'.$row_no, $val4['hcl_no']);
					$sheet->setCellValue('AA'.$row_no, '');
					$sheet->setCellValue('AB'.$row_no, '');
					$sheet->setCellValue('AC'.$row_no, $val4['remark']);
					if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
							{
					$sheet->setCellValue('AD'.$row_no, $val4['pick_amount']);
							}
					$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
					$sheet->setCellValue('AF'.$row_no, $val4['dep_amount2']);
					$sheet->setCellValue('AG'.$row_no, $val4['dep_bank2']);
					$sheet->setCellValue('AH'.$row_no, $val4['other_remarks2']);
					
					$sheet->setCellValue('AI'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AJ'.$row_no, $val4['dep_bank_shop']);
					if($shop_acc_id!='') {
						$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AL'.$row_no, $val4['dep_type1']);
					if($val4['dep_accid']!='') {
						$sheet->setCellValue('AM'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AN'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AO'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AP'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AQ'.$row_no, $val4['inactive_date']);
					$sheet->setCellValue('AR'.$row_no, $val4['cust_field1']);
				}
				else {
					$sheet->setCellValue('Y'.$row_no, $val4['hcl_no']);
					$sheet->setCellValue('Z'.$row_no, $val4['gen_slip']);
					
					$sheet->setCellValue('AA'.$row_no, '');
					$sheet->setCellValue('AB'.$row_no, '');
					$sheet->setCellValue('AC'.$row_no, $remark);
					/*if($dep_type1=='Vault')
							{
							$sheet->setCellValue('AA'.$row_no, $valut_amt);
							}
							else
							{
								$sheet->setCellValue('AA'.$row_no, $partner_amt);
							}*/
					if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
					{
					$sheet->setCellValue('AD'.$row_no, $val4['pick_amount']);
					}
					$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
					$sheet->setCellValue('AF'.$row_no, $val4['dep_amount2']);
					$sheet->setCellValue('AG'.$row_no, $val4['dep_bank2']);
					$sheet->setCellValue('AH'.$row_no, $val4['other_remarks2']);
					if($group_name=='Radiant Consol' || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup") {
						$sheet->setCellValue('AI'.$row_no, $val4['dep_bank_shop']);	
						
						$sheet->setCellValue('AJ'.$row_no, $val4['shop_id']);
						$sheet->setCellValue('AK'.$row_no, $val4['dep_bank_shop']);
						if($shop_acc_id!='') {
							$sheet->setCellValue('AL'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
						}
						$sheet->setCellValue('AM'.$row_no, $val4['dep_type1']);
						if($val4['dep_accid']!='') {
							$sheet->setCellValue('AN'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
						}
						$sheet->setCellValue('AO'.$row_no, $val4['point_type']);
						$sheet->setCellValue('AP'.$row_no, $val4['loi_date']);
						$sheet->setCellValue('AQ'.$row_no, $val4['sdeact_date']);
						$sheet->setCellValue('AR'.$row_no, $val4['inactive_date']);
						if($group_name=='Radiant Consol' || $group_name == 'Consol Evening Pickup')
						{
							if(!empty($val4['cityname']))
							{
								$sheet->setCellValue('AS'.$row_no,$district_all[$val4['cityname']]['district']);


							}
							else{
								$sheet->setCellValue('AS'.$row_no,"0");

							}
						}
					}
					else {
						$sheet->setCellValue('AI'.$row_no, $val4['shop_id']);
						$sheet->setCellValue('AJ'.$row_no, $val4['dep_bank_shop']);
						if($shop_acc_id!='') {
							$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
						}
						$sheet->setCellValue('AL'.$row_no, $val4['dep_type1']);
						if($val4['dep_accid']!='') {
							$sheet->setCellValue('AM'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branchs[$val4['dep_accid']]['account_no']);
						}
						$sheet->setCellValue('AN'.$row_no, $val4['point_type']);
						$sheet->setCellValue('AO'.$row_no, $val4['loi_date']);
						$sheet->setCellValue('AP'.$row_no, $val4['sdeact_date']);
						$sheet->setCellValue('AQ'.$row_no, $val4['inactive_date']);	
						if($group_name == 'SCB-HYUNDAI' || $group_name == "Other Migration")
						{
							  if(!empty($val4["cityname"]))
							  {
								$sheet->setCellValue('AR'.$row_no,$district_all[$val4["cityname"]]['district']);

							  }
							  else{
								$sheet->setCellValue('AR'.$row_no, "0");	

							  }
						}
					}
				}
				$row_no++;
				$i++;
			}
			
			
			
			$cust_names1 = $val4['cust_name'];
			$region_names1 = $val4['region_name'];
			
		}
		/* Reliance console */
		else if($group_name=="Reliance Console") {
			
			$cust_rep = 1;
			if($i==1) {
				$alb = array('J','K','L','M','N','O','P','Q','R','S','T','U','V','AC','AD');
			}
			$cust_names = $val4['cust_name'];
			if($cust_names!=$cust_names1 && $i!=1) {
				$sheet->setCellValue('C'.$row_no, $cust_names1.' Total');		
				foreach($alb as $key=>$val){
					$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
			
			if($val4['multi_rec']=='Y') {
					foreach($val4['multi'] as $key5=>$val5) {
					$sheet->setCellValue('A'.$row_no, 'RCMS');
					$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
					$sheet->setCellValue('C'.$row_no, $val4['location']);
					$sheet->setCellValue('D'.$row_no, $val4['loc_code']);
					$sheet->setCellValue('E'.$row_no, $val4['customer_code']);
					$sheet->setCellValue('F'.$row_no, $val4['cust_name']);
					$sheet->setCellValue('G'.$row_no, $val4['shop_name']);
					$sheet->setCellValue('H'.$row_no, $val4['shop_code']);
					$sheet->setCellValue('I'.$row_no, $val5['rec_no']);					
					$sheet->setCellValue('J'.$row_no, $val5['pick_amount']);
					
					// Denomination changed on 11-11-2016
					$sheet->setCellValue('K'.$row_no, $val5['2000s']);
					$sheet->setCellValue('L'.$row_no, $val5['1000s']);
					$sheet->setCellValue('M'.$row_no, $val5['500s']);
					$sheet->setCellValue('N'.$row_no, $val5['200s']);
					$sheet->setCellValue('O'.$row_no, $val5['100s']);
					$sheet->setCellValue('P'.$row_no, $val5['50s']);
					$sheet->setCellValue('Q'.$row_no, $val5['20s']);
					$sheet->setCellValue('R'.$row_no, $val5['10s']);
					$sheet->setCellValue('S'.$row_no, $val5['5s']);
					$sheet->setCellValue('T'.$row_no, $val5['coins']);
					$totals = '=(K'.$row_no.'*K4)+(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.')';	
					$sheet->setCellValue('U'.$row_no, $totals);
					$sheet->setCellValue('V'.$row_no, '=J'.$row_no.'-U'.$row_no);
					
					$sheet->setCellValue('W'.$row_no, $val5['pis_hcl_no']);
					$sheet->setCellValue('X'.$row_no, $val5['hcl_no']);
					$sheet->setCellValue('Y'.$row_no, $val5['gen_slip']);
					$sheet->setCellValue('Z'.$row_no, '');
					$sheet->setCellValue('AA'.$row_no, '');
					$sheet->setCellValue('AB'.$row_no, $val5['remark']);
					if($val4['$dep_type1']!='Burial' and $val4['$dep_type1']!='')
							{
							$sheet->setCellValue('AC'.$row_no, $val5['pick_amount']);
							}
					if($key5==1) {
						//$sheet->setCellValue('Z'.$row_no, $partner_amt);	
						$sheet->setCellValue('AD'.$row_no, $val4['dep_amount2']);
						$sheet->setCellValue('AE'.$row_no, $val4['dep_bank2']);
						$sheet->setCellValue('AF'.$row_no, $val4['dep_bank2']);
					}
					
					$sheet->setCellValue('AG'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AH'.$row_no, $val4['dep_bank_shop']);
					if($shop_acc_id!='') {
						$sheet->setCellValue('AI'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AJ'.$row_no, $val4['dep_type1']);
					if($dep_accid!='') {
						$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AL'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AM'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AN'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AO'.$row_no, $val4['sdeact_date']);	
					$sheet->setCellValue('AP'.$row_no, $val4['region_name']);	
					if(!empty($val4['cityname']))
					{
						$sheet->setCellValue('AQ'.$row_no,$district_all[$val4["cityname"]]['district']);


					}
					else{
						$sheet->setCellValue('AQ'.$row_no,'0');

					}	
					
					$row_no++;
					$i++;
				}
			}
			else {
				$sheet->setCellValue('A'.$row_no, 'RCMS');
				$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
				$sheet->setCellValue('C'.$row_no, $val4['location']);
				$sheet->setCellValue('D'.$row_no, $val4['loc_code']);
				$sheet->setCellValue('E'.$row_no, $val4['customer_code']);
				$sheet->setCellValue('F'.$row_no, $val4['cust_name']);
				$sheet->setCellValue('G'.$row_no, $val4['shop_name']);
				$sheet->setCellValue('H'.$row_no, $val4['shop_code']);
				$sheet->setCellValue('I'.$row_no, $val4['rec_no']);				
				$sheet->setCellValue('J'.$row_no, $val4['pick_amount']);
				
				// Denomination changed on 11-11-2016
				$sheet->setCellValue('K'.$row_no, $val4['_2000s']);
				$sheet->setCellValue('L'.$row_no, $val4['_1000s']);
				$sheet->setCellValue('M'.$row_no, $val4['_500s']);
				$sheet->setCellValue('N'.$row_no, $val4['_200s']);
				$sheet->setCellValue('O'.$row_no, $val4['_100s']);
				$sheet->setCellValue('P'.$row_no, $val4['_50s']);
				$sheet->setCellValue('Q'.$row_no, $val4['_20s']);
				$sheet->setCellValue('R'.$row_no, $val4['_10s']);
				$sheet->setCellValue('S'.$row_no, $val4['_5s']);
				$sheet->setCellValue('T'.$row_no, $val4['_coins']);
				$totals = '=(K'.$row_no.'*K4)+(L'.$row_no.'*L4)+(M'.$row_no.'*M4)+(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.')';
				$sheet->setCellValue('U'.$row_no, $totals);
				$sheet->setCellValue('V'.$row_no, '=J'.$row_no.'-U'.$row_no);
				
				$sheet->setCellValue('W'.$row_no, $val4['pis_hcl_no']);
				$sheet->setCellValue('X'.$row_no, $val4['hcl_no']);
				$sheet->setCellValue('Y'.$row_no, $val4['gen_slip']);
				$sheet->setCellValue('Z'.$row_no, '');
				$sheet->setCellValue('AA'.$row_no, '');
				$sheet->setCellValue('AB'.$row_no, $remark);
				
				if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
							{
							$sheet->setCellValue('AC'.$row_no, $val4['pick_amount']);
							}
				//$sheet->setCellValue('Z'.$row_no, $partner_amt);
				$sheet->setCellValue('AD'.$row_no, $val4['dep_amount2']);
				$sheet->setCellValue('AE'.$row_no, $val4['dep_bank2']);
				$sheet->setCellValue('AF'.$row_no, $val4['other_remarks2']);
				
				$sheet->setCellValue('AG'.$row_no, $val4['shop_id']);
				$sheet->setCellValue('AH'.$row_no, $val4['dep_bank_shop']);
				if($val4['acc_id_shop']!='') {
					$sheet->setCellValue('AI'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
				}
				$sheet->setCellValue('AJ'.$row_no, $val4['dep_type1']);
				if($val4['dep_accid']!='') {
					$sheet->setCellValue('AK'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
				}
				$sheet->setCellValue('AL'.$row_no, $val4['point_type']);
				$sheet->setCellValue('AM'.$row_no, $val4['loi_date']);
				$sheet->setCellValue('AN'.$row_no, $val4['sdeact_date']);
				$sheet->setCellValue('AO'.$row_no, $val4['inactive_date']);
				$sheet->setCellValue('AP'.$row_no, $val4['region_name']);	
				if(!empty($val4['cityname']))
					{
						$sheet->setCellValue('AQ'.$row_no,$district_all[$val4["cityname"]]['district']);


					}
				else{
						$sheet->setCellValue('AQ'.$row_no,'0');

					}	
							
				$row_no++;
				$i++;
			}
			$cust_names1 = $val4['cust_name'];
		}
		else if($group_name=="Reliance Communications"){
			if($i==1) {
				$alb = array('K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB');	
			}
			$cust_names = $val4['cust_name'];
			if($cust_names!=$cust_names1 && $i!=1) {
				$sheet->setCellValue('C'.$row_no, $cust_names1.' Total');		
				foreach($alb as $key=>$val){
					$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$sheet->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
			
			if($val4['multi_rec']=='Y') {
				foreach($val4['multi'] as $key5=>$val5) {
					$sheet->setCellValue('A'.$row_no, "RADIANT");
					$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
					$sheet->setCellValue('C'.$row_no, $val4['location']);
					$sheet->setCellValue('D'.$row_no, $val4['shop_name']);
					$sheet->setCellValue('E'.$row_no, $val4['loc_code']);
					$sheet->setCellValue('F'.$row_no, $val4['customer_code']);
					$sheet->setCellValue('G'.$row_no, $val4['cust_name']);
					$sheet->setCellValue('H'.$row_no, $val4['address']);
					$sheet->setCellValue('I'.$row_no, $val4['shop_code']);
					$sheet->setCellValue('J'.$row_no, $val5['pis_hcl_no']);
					$sheet->setCellValue('K'.$row_no, $val5['hcl_no']);
					$sheet->setCellValue('L'.$row_no, $val5['pick_amount']);
					$sheet->setCellValue('M'.$row_no, '=K'.$row_no.'-L'.$row_no);
					
					// Denomination changed on 11-11-2016
					$sheet->setCellValue('N'.$row_no, $val5['2000s']);
					$sheet->setCellValue('O'.$row_no, $val5['1000s']);
					$sheet->setCellValue('P'.$row_no, $val5['500s']);
					$sheet->setCellValue('Q'.$row_no, $val5['200s']);
					$sheet->setCellValue('R'.$row_no, $val5['100s']);
					$sheet->setCellValue('S'.$row_no, $val5['50s']);
					$sheet->setCellValue('T'.$row_no, $val5['20s']);
					$sheet->setCellValue('U'.$row_no, $val5['10s']);
					$sheet->setCellValue('V'.$row_no, $val5['5s']);
					$sheet->setCellValue('W'.$row_no, $val5['coins']);
					$totals = '=(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.'*T4)+(U'.$row_no.'*U4)+(V'.$row_no.'*V4)+(W'.$row_no.')';	
					$sheet->setCellValue('X'.$row_no, $totals);
					$sheet->setCellValue('Y'.$row_no, '=L'.$row_no.'-X'.$row_no);
					
					$sheet->setCellValue('Z'.$row_no, $val5['remark']);
					if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
							{
							$sheet->setCellValue('AA'.$row_no, $val5['pick_amount']);
							}
					if($val4['rec_id']==1){
						//$sheet->setCellValue('Z'.$row_no, $partner_amt);
						$sheet->setCellValue('AB'.$row_no, $val4['dep_amount2']);
						$sheet->setCellValue('AC'.$row_no, $val4['dep_bank2']);
						$sheet->setCellValue('AD'.$row_no, $val4['other_remarks2']);
					}
					$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
					
					$sheet->setCellValue('AF'.$row_no, $val4['shop_id']);
					$sheet->setCellValue('AG'.$row_no, $val4['dep_bank_shop']);
					if($val4['acc_id_shop']!='') {
						$sheet->setCellValue('AH'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
					}
					$sheet->setCellValue('AI'.$row_no, $val4['dep_type1']);
					if($val4['dep_accid']!='') {
						$sheet->setCellValue('AJ'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
					}
					$sheet->setCellValue('AK'.$row_no, $val4['point_type']);
					$sheet->setCellValue('AL'.$row_no, $val4['loi_date']);
					$sheet->setCellValue('AM'.$row_no, $val4['sdeact_date']);
					$sheet->setCellValue('AN'.$row_no, $val4['inactive_date']);	
					//$sheet->setCellValue('AN'.$row_no, $r['shop_id']);
					$row_no++;
					$i++;
				}
			}else{
				$sheet->setCellValue('A'.$row_no, "RADIANT");
				$sheet->setCellValue('B'.$row_no, date("d-m-Y",strtotime($cdate)));
				$sheet->setCellValue('C'.$row_no, $val4['location']);
				$sheet->setCellValue('D'.$row_no, $val4['shop_name']);
				$sheet->setCellValue('E'.$row_no, $val4['loc_code']);
				$sheet->setCellValue('F'.$row_no, $val4['customer_code']);
				$sheet->setCellValue('G'.$row_no, $val4['cust_name']);
				$sheet->setCellValue('H'.$row_no, $val4['address']);
				$sheet->setCellValue('I'.$row_no, $val4['shop_code']);
				$sheet->setCellValue('J'.$row_no, $val4['pis_hcl_no']);
				$sheet->setCellValue('K'.$row_no, $val4['hcl_no']);
				$sheet->setCellValue('L'.$row_no, !empty($val4['pick_amount'])?$val4['pick_amount']:"0");
				$sheet->setCellValue('M'.$row_no, '=K'.$row_no.'-L'.$row_no);
				
				// Denomination changed on 11-11-2016
				$sheet->setCellValue('N'.$row_no, !empty($val4['_2000s'])?$val4['_2000s']:"0");
				$sheet->setCellValue('O'.$row_no, !empty($val4['_1000s'])?$val4['_1000s']:"0");
				$sheet->setCellValue('P'.$row_no, !empty($val4['_500s'])?$val4['_500s']:"0");
				$sheet->setCellValue('Q'.$row_no, !empty($val4['_200s'])?$val4['_200s']:"0");
				$sheet->setCellValue('R'.$row_no, !empty($val4['_100s'])?$val4['_100s']:"0");
				$sheet->setCellValue('S'.$row_no, !empty($val4['_50s'])?$val4['_50s']:"0");
				$sheet->setCellValue('T'.$row_no, !empty($val4['_20s'])?$val4['_20s']:"0");
				$sheet->setCellValue('U'.$row_no, !empty($val4['_10s'])?$val4['_10s']:"0");
				$sheet->setCellValue('V'.$row_no, !empty($val4['_5s'])?$val4['_5s']:"0");
				$sheet->setCellValue('W'.$row_no, !empty($val4['_coins'])?$val4['_coins']:"0");
				$totals = '=(N'.$row_no.'*N4)+(O'.$row_no.'*O4)+(P'.$row_no.'*P4)+(Q'.$row_no.'*Q4)+(R'.$row_no.'*R4)+(S'.$row_no.'*S4)+(T'.$row_no.'*T4)+(U'.$row_no.'*U4)+(V'.$row_no.'*V4)+(W'.$row_no.')';	
				$sheet->setCellValue('X'.$row_no, $totals);
				$sheet->setCellValue('Y'.$row_no, '=L'.$row_no.'-X'.$row_no);
				
				$sheet->setCellValue('Z'.$row_no, $remark);
				//$sheet->setCellValue('Z'.$row_no, $partner_amt);
				if($val4['dep_type1']!='Burial' and $val4['dep_type1']!='')
							{
							$sheet->setCellValue('AA'.$row_no, $val4['pick_amount']);
							}
				$sheet->setCellValue('AB'.$row_no, $val4['dep_amount2']);
				$sheet->setCellValue('AC'.$row_no, $val4['dep_bank2']);
				$sheet->setCellValue('AD'.$row_no, $val4['other_remarks2']);
				$sheet->setCellValue('AE'.$row_no, $val4['region_name']);
				
				$sheet->setCellValue('AF'.$row_no, $val4['shop_id']);
				$sheet->setCellValue('AG'.$row_no, $val4['dep_bank_shop']);
				if($val4['acc_id_shop']!='') {
					$sheet->setCellValue('AH'.$row_no, $dep_branch_s[$val4['acc_id_shop']]['bank_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['branch_name'].'-'.$dep_branch_s[$val4['acc_id_shop']]['account_no']);
				}
				$sheet->setCellValue('AI'.$row_no, $val4['dep_type1']);
				if($val4['dep_accid']!='') {
					$sheet->setCellValue('AJ'.$row_no, $dep_branch_s[$val4['dep_accid']]['bank_name'].'-'.$dep_branch_s[$val4['dep_accid']]['branch_name'].'-'.$dep_branch_s[$val4['dep_accid']]['account_no']);
				}
				$sheet->setCellValue('AK'.$row_no, $val4['point_type']);
				$sheet->setCellValue('AL'.$row_no, $val4['loi_date']);
				$sheet->setCellValue('AM'.$row_no, $val4['sdeact_date']);
				$sheet->setCellValue('AN'.$row_no, $val4['inactive_date']);
				//$sheet->setCellValue('AN'.$row_no, $r['shop_id']);
				$row_no++;
				$i++;
			}
			$cust_names1 = $val4['cust_name'];
		}
	
	}
		 }

//Grand Total
if($n>0) {
	if($group_name=='Toll Plaza') {	
		$sheet->setCellValue('C'.$row_no, 'Grand Total');
		$sheet->setCellValue('G'.$row_no, '=SUM(G5:G'.($row_no-1).')');
		$sheet->setCellValue('H'.$row_no, '=SUM(H5:H'.($row_no-1).')');
		$sheet->setCellValue('I'.$row_no, '=SUM(I5:I'.($row_no-1).')');
		$sheet->setCellValue('M'.$row_no, '=SUM(M5:M'.($row_no-1).')');
		$sheet->setCellValue('P'.$row_no, '=SUM(P5:P'.($row_no-1).')');
		$sheet->setCellValue('Q'.$row_no, '=SUM(Q5:Q'.($row_no-1).')');
		$sheet->setCellValue('R'.$row_no, '=SUM(R5:R'.($row_no-1).')');
		$sheet->setCellValue('S'.$row_no, '=SUM(S5:S'.($row_no-1).')');
		$sheet->setCellValue('T'.$row_no, '=SUM(T5:T'.($row_no-1).')');
		$sheet->setCellValue('U'.$row_no, '=SUM(U5:U'.($row_no-1).')');
		$sheet->setCellValue('V'.$row_no, '=SUM(V5:V'.($row_no-1).')');
		$sheet->setCellValue('W'.$row_no, '=SUM(Q5:W'.($row_no-1).')');			
		$sheet->setCellValue('X'.$row_no, '=SUM(X5:X'.($row_no-1).')');
		$sheet->setCellValue('Y'.$row_no, '=SUM(Y5:Y'.($row_no-1).')');
		$sheet->setCellValue('Z'.$row_no, '=SUM(Z5:Z'.($row_no-1).')');
		$sheet->setCellValue('AA'.$row_no, '=SUM(AA5:AA'.($row_no-1).')');
		$sheet->getStyle('A'.$row_no.':AA'.$row_no)->getFont()->setBold(true);
	}
	else  {	
		
		if($group_name=='TTL' || $group_name=='Radiant Consol' || $group_name=="Consol Evening Pickup" || $group_name=="Consol Holiday pickup" || $group_name=="VFS Migration" || $group_name=="Other Migration" || $group_name=="Group-1" || $group_name=="Group-3" || $group_name=="SCB-HYUNDAI"){
			$sheet->setCellValue('C'.$row_no, $cust_names1.' Total');
		}
		else {
			$sheet->setCellValue('C'.$row_no, $zones1.' Total');
		}
		foreach($alb as $key=>$val){
			$sheet->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
		}
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFont()->setBold(true);
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
		$gtotal_rows[] = $row_no;
		$row_no++;
	
		//print'<pre>';print_r($gtotal_rows);
		$sheet->setCellValue('C'.$row_no, 'Grand Total');		
		foreach($alb as $key=>$val){
			foreach($gtotal_rows as $val1) {
				$load_result.= $val.$val1.',';
			}
			$load_result = substr($load_result, 0, -1);
			$sheet->setCellValue($val.$row_no, '=SUM('.$load_result.')');
			$load_result = '';
		}
		
		$sheet->getStyle('A'.$row_no.':Y'.$row_no)->getFont()->setBold(true);
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_gtotal);	
		$sheet->getStyle('A'.$row_no.':'.$end_filed.$row_no)->applyFromArray(
																	  array(
																		  'font'  => array(
																			  'bold' => true,
																			  'color' => array('rgb' => '000000')																		
																		  )
																	  )
																  );  
		
		
	}
}

$last_row = $row_no;

$file_name = $group_name.'_'.$trans_date_c.'.xlsx';
$sheet_name = $group_name;

$sheet->getStyle('A1:'.$end_filed.'4')->getFont()->setBold(true);
$sheet->getStyle('A4:'.$end_filed.'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A4:'.$end_filed.'4')->getFill()->getStartColor()->setARGB($cllor_title);
$sheet->getStyle('A4:'.$end_filed.'4')->applyFromArray(
																array(
																	'font'  => array(
																		'bold' => true,
																		'color' => array('rgb' => '000000')																		
																	)
																)
															); 
$sheet->getStyle('A4:'.$end_filed.'4')->getAlignment()->setWrapText(true); 
$sheet->mergeCells('A1:'.$end_filed.'1');
$sheet->mergeCells('A2:'.$end_filed.'2');
$sheet->mergeCells('A3:'.$end_filed.'3');

for($s = 1;$s<=$last_row;$s++){					
  $sheet->getStyle("A$s:".$end_filed.$s)->applyFromArray(array(
		  'borders' => array(
			  'allborders' => array(
				  'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
			  )
		  )
	  ));
}

for($s = 1;$s<=$last_row;$s++){					
  $spreadsheet->getActiveSheet()->getStyle('A'.$s.':'.$end_filed.$s)->getBorders()->
    getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}

// Rename worksheet
$sheet->setTitle($sheet_name);


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
exit;
